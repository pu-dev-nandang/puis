<?php $this->load->view('dashboard/ticketing/LoadCssTicketToday') ?>
<div class="container" style="margin-top: 30px;">
    <div class="row">
        <div class="">
            <div class="row" >
                <div class="col-md-4 col-md-offset-4">
                    <div class="well">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Department</label>
                                <select class ="select2-select-00 full-width-fix" id ="SelectDepartmentID"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" style="text-align: right;">
                    <button class="btn btn-default" id="btnCreateNewTicket">Create new ticket</button>
                    <!-- <button class="btn btn-default">Check my ticket</button> -->
                </div>
            </div>
            <div class="row bg-ticket2">
                <div class="col-md-3 panel-ticket" id="PanelPendingTicket">
                </div>
                <div class="col-md-3 panel-ticket" id="PanelOpenTicket">
                </div>
                <div class="col-md-3 panel-ticket" id = "PanelProgressTicket">
                </div>
                <div class="col-md-3 panel-ticket" id = "PanelCloseTicket">
                </div>
            </div>
        </div>

    </div>
</div>


<script>
var App_ticket_ticket_today = {
    Loaded : function(){
        loading_page('#PanelOpenTicket');
        loading_page('#PanelPendingTicket');
        loading_page('#PanelProgressTicket');
        loading_page('#PanelCloseTicket');
        var selectorDepartment = $('#SelectDepartmentID');
        LoadSelectOptionDepartmentFiltered(selectorDepartment);
        var firstLoad = setInterval(function () {
            var SelectDepartmentID = $('#SelectDepartmentID').val();
            if(SelectDepartmentID!='' && SelectDepartmentID!=null ){
                /*
                    LoadAction
                */
                App_ticket_ticket_today.OpenTicketRest();
                App_ticket_ticket_today.PendingTicketRest();
                App_ticket_ticket_today.ProgressTicketRest();
                App_ticket_ticket_today.ProgressCloseRest();
                clearInterval(firstLoad);
            }
        },1000);
        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);
    },

    ModalFormCreateNewTicket : function(judul = 'Form Ticket',action='add',ID='',data=[]){
        var htmlss = '<table class="table" id="tableNewTicket">' +
            '    <tr>' +
            '        <td>Category</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <select class="select2-select-00 full-width-fix input_form" name = "CategoryID"></select>' +
            '        </td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Department</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <label class="lblDepartment">Auto selected by Category</label>' +
            '        </td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Title</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <input class="form-control input_form" name = "Title">' +
            '        </td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Message</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <textarea class="form-control input_form" rows="7" name="Message"></textarea>' +
            '        </td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>File</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <input type="file" name = "Files" id = "UploadFile">' +
            '            <p style = "color:red">(jpg,png) Max 2mb                                                    ' +
            '        </td>' +
            '    </tr>' +
            '    <tr class="hide" id = "tr_ticket_number">' +
            '        <td>Ticket Number</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <label class ="TicketNumber"></label>' +
            '        </td>' +
            '    </tr>' +
            '</table>';


        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+judul+'</h4>');
        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('' +
            '<button type="button" class="btn btn-success" id="btnsave_ticket">Submit</button> ' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '');
        if (action=='add') {
            var selector =  $('.input_form[name="CategoryID"]');
            LoadSelectOptionCategory(selector);
            var firstLoad = setInterval(function () {
                var SelectCategoryID = $('.input_form[name="CategoryID"]').find('option:selected').val();
                if(SelectCategoryID!='' && SelectCategoryID!=null && SelectCategoryID !='' && SelectCategoryID!=null){
                    loadingEnd(1);
                    setTimeout(function () {
                        $('#GlobalModal').modal({
                            'show' : true,
                            'backdrop' : 'static'
                        });
                        $('.input_form[name="CategoryID"]').trigger('change');
                    },500);
                    clearInterval(firstLoad);
                }
            },1000);
            setTimeout(function () {
                clearInterval(firstLoad);
                loadingEnd(500);
            },5000);
        }
        else
        {
            /*
                edit
            */
        }
    },

    ActionCreateNewTicket : function(selector,action="create",ID="")
    {
        var data = {};
        $('.input_form').not('div').each(function(){
            var field = $(this).attr('name');
            data[field] = $(this).val();
        })
        data['RequestedBy'] = sessionNIP;
        data['DepartmentTicketID'] = DepartmentID;
        var dataform = {
            action : action,
            data : data,
            ID : ID,
            auth : 's3Cr3T-G4N',
            DepartmentAbbr : DepartmentAbbr,
        };

        var ArrUploadFilesSelector = [];
        var UploadFile = $('#UploadFile');
        var valUploadFile = UploadFile.val();
        if (valUploadFile) {
            var NameField = UploadFile.attr('name');
            var temp = {
                NameField : NameField,
                Selector : UploadFile,
            };
            ArrUploadFilesSelector.push(temp);
        }

        // cek validation jika tidak delete
        var validation = (action == 'delete') ? true : App_ticket_ticket_today.ValidationCreateNewTicket(data,ArrUploadFilesSelector);
        if (validation) {
            if (confirm('Are you sure ?')) {
                loading_button2(selector);
                var url = base_url_js+"rest_ticketing/__event_ticketing";
                var token = jwt_encode(dataform,'UAP)(*');
                AjaxSubmitRestTicketing(url,token,ArrUploadFilesSelector).then(function(response){
                    if (response.status == 1) {
                        selector.remove();
                        var response_callback = response.callback;
                        $('.TicketNumber').html(response_callback.NoTicket);
                        $('#tr_ticket_number').removeClass('hide');
                        $('.input_form,#UploadFile').prop('disabled',true);
                        toastr.success('Ticket Created');
                        App_ticket_ticket_today.OpenTicketRest();
                    }
                    else
                    {
                        toastr.error(response.msg);
                        end_loading_button2(selector);
                    }
                }).fail(function(response){
                   toastr.error('Connection error,please try again');
                   end_loading_button2(selector);     
                })
            }
        }
    },

    ValidationCreateNewTicket : function(arr,ArrUploadFilesSelector=[]){
        var toatString = "";
        var result = "";
        for(key in arr){
           switch(key)
           {
            case  "Title" :
                  result = Validation_required(arr[key],key);
                  if (result['status'] == 0) {
                    toatString += result['messages'] + "<br>";
                  }
                  break;
            case  "Message" :
                  result = Validation_required(arr[key],key);
                  if (result['status'] == 0) {
                    toatString += result['messages'] + "<br>";
                  }
                  break;
           }
        }

        // validation files
        if (ArrUploadFilesSelector.length>0 && ArrUploadFilesSelector[0].Selector.length) {
          var selectorfile = ArrUploadFilesSelector[0].Selector
          var FilesValidation = file_validation_ticketing(selectorfile,'File Ticketing');
          if (FilesValidation != '') {
              toatString += FilesValidation + "<br>";
          }
          
        }

        if (toatString != "") {
          toastr.error(toatString, 'Failed!!');
          return false;
        }
        return true
    },

    OpenTicketRest : function(){
        if (!$('#PanelOpenTicket').find('.flipInX').length) {
            loading_page('#PanelOpenTicket');
        }
        var url = base_url_js+"rest_ticketing/__ticketing_dashboard";
        var dataform = {
            action : 'open_ticket',
            auth : 's3Cr3T-G4N',
            DepartmentID : DepartmentID,
            NIP : sessionNIP,
        }
        var token = jwt_encode(dataform,'UAP)(*');
        
        AjaxLoadRestTicketing(url,token).then(function(response){
            var html = '';
            var count = response.count;
            var data = response.data;
            html += '<h3 class="open-ticket">Today Open-Ticket <span>'+count+'</span></h3>'+
                        '<hr/>'+
                        '<div class="timeline-centered">';
            if (data.length >0) {
                var EncodeDepartment = jwt_encode(DepartmentID,'UAP)(*');
                for (var i = 0; i < data.length; i++) {
                    var row = data[i];
                    var pfiles = (row.Files != null && row.Files != '') ? '<p><a href= "'+row.Files+'" target="_blank">Files Upload<a></p>' : '';
                    var hrefActionTicket = (row.setTicket == 'write') ? base_url_js+'ticket'+'/set_action_first/'+row.NoTicket+'/'+EncodeDepartment : '#';
                    
                    // NANDANG
                    var styleAsRequest = (row.RequestedBy == sessionNIP) ? 'background:#ffeb3b52' : '';

                    html += '<article class="timeline-entry">'+
                                ' <div class="timeline-entry-inner">'+
                                    '<div class="timeline-icon">'+
                                        '<img data-src="'+row.Photo+'" style="margin-top: -3px;" class="img-circle img-fitter" width="57">'+
                                        '<div class="ticket-number2">'+row.NoTicket+'</div>'+
                                    '</div>'+
                                    '<div class="timeline-label" style="'+styleAsRequest+'">'+
                                        '<div class="ticket-division">'+row.NameDepartmentDestination+'</div>'+
                                        '<h2><a href="'+hrefActionTicket+'">'+'<span>'+row.Title+'</span>'+'</a> </h2>'+
                                        '<div class="ticket-submited">'+row.NameRequested+' | '+row.RequestedAt+'</div>'+
                                        '<p>'+nl2br(row.Message)+'</p>'+
                                        pfiles+
                                        '<div style="text-align: center;margin-top: 10px;">'+
                                            '<a href="javascript:void(0);" class="ModalReadMore" setTicket ="'+row.setTicket+'" token = "'+row.token+'" data-id ="'+row.ID+'">Read more <i class="fa fa-angle-double-right"></i></a>'+
                                        '</div>'+
                                    '</div>'+
                            '</article>';
                }
            }
            else
            {
                html += '<label>No data found in server</label>';
            }

            html += '</div>';
            $('#PanelOpenTicket').html(html);

            $('.img-fitter').imgFitter({
                // CSS background position
                backgroundPosition: 'center center',
                // for image loading effect
                fadeinDelay: 400,
                fadeinTime: 1200
            });
            
        }).fail(function(response){
           toastr.error('Error open ticket');
        })
    },
    PendingTicketRest : function(){
        if (!$('#PanelPendingTicket').find('.flipInX').length) {
            loading_page('#PanelPendingTicket');
        }
        var url = base_url_js+"rest_ticketing/__ticketing_dashboard";
        var dataform = {
            action : 'pending_ticket',
            auth : 's3Cr3T-G4N',
            DepartmentID : DepartmentID,
            NIP : sessionNIP,
        }
        var token = jwt_encode(dataform,'UAP)(*');
        
        AjaxLoadRestTicketing(url,token).then(function(response){
            var html = '';
            var count = response.count;
            var data = response.data;
            html += '<h3 class="pending-ticket">Pending-Ticket <span>'+count+'</span></h3>'+
                        '<hr/>'+
                        '<div class="timeline-centered">';
            if (data.length >0) {
                var EncodeDepartment = jwt_encode(DepartmentID,'UAP)(*');
                for (var i = 0; i < data.length; i++) {
                    var row = data[i];
                    var pfiles = (row.Files != null && row.Files != '') ? '<p><a href= "'+row.Files+'" target="_blank">Files Upload<a></p>' : '';
                    var hrefActionTicket = (row.setTicket == 'write') ? base_url_js+'ticket'+'/set_action_first/'+row.NoTicket+'/'+EncodeDepartment : '#';

                    var styleAsRequest = (row.RequestedBy == sessionNIP) ? 'background:#ffeb3b52' : '';
                    html += '<article class="timeline-entry">'+
                                ' <div class="timeline-entry-inner">'+
                                    '<div class="timeline-icon">'+
                                        '<img data-src="'+row.Photo+'" style="margin-top: -3px;" class="img-circle img-fitter" width="57">'+
                                        '<div class="ticket-number2">'+row.NoTicket+'</div>'+
                                    '</div>'+
                                    '<div class="timeline-label" style="'+styleAsRequest+'">'+
                                       '<div class="ticket-division">'+row.NameDepartmentDestination+'</div>'+
                                       '<h2><a href="'+hrefActionTicket+'">'+'<span>'+row.Title+'</span>'+'</a> </h2>'+
                                       '<div class="ticket-submited">'+row.NameRequested+' | '+row.RequestedAt+'</div>'+
                                       '<p>'+nl2br(row.Message)+'</p>'+
                                       pfiles+
                                       '<div style="text-align: center;margin-top: 10px;">'+
                                           '<a href="javascript:void(0);" class="ModalReadMore" setTicket ="'+row.setTicket+'" token = "'+row.token+'" data-id ="'+row.ID+'">Read more <i class="fa fa-angle-double-right"></i></a>'+
                                       '</div>'+
                                   '</div>'+
                            '</article>';
                }
            }
            else
            {
                html += '<label>No data found in server</label>';
            }

            html += '</div>';
            $('#PanelPendingTicket').html(html);


            $('.img-fitter').imgFitter({
                // CSS background position
                backgroundPosition: 'center center',
                // for image loading effect
                fadeinDelay: 400,
                fadeinTime: 1200
            });

        }).fail(function(response){
           toastr.error('Error open ticket');
        })
    },

    ProgressTicketRest : function(){
        if (!$('#PanelProgressTicket').find('.flipInX').length) {
            loading_page('#PanelProgressTicket');
        }

        var url = base_url_js+"rest_ticketing/__ticketing_dashboard";
        var dataform = {
            action : 'progress_ticket',
            auth : 's3Cr3T-G4N',
            DepartmentID : DepartmentID,
            NIP : sessionNIP,
        }
        var token = jwt_encode(dataform,'UAP)(*');
        
        AjaxLoadRestTicketing(url,token).then(function(response){
            var html = '';
            var count = response.count;
            var data = response.data;
            html += '<h3 class="progres-ticket">Progres-Ticket <span>'+count+'</span></h3>'+
                        '<hr/>'+
                        '<div class="timeline-centered">';
            if (data.length >0) {
                var EncodeDepartment = jwt_encode(DepartmentID,'UAP)(*');
                for (var i = 0; i < data.length; i++) {
                    var row = data[i];
                    var pfiles = (row.Files != null && row.Files != '') ? '<p><a href= "'+row.Files+'" target="_blank">Files Upload<a></p>' : '';
                    var hrefActionTicket = (row.setTicket == 'write') ? base_url_js+'ticket'+'/set_action_progress/'+row.NoTicket+'/'+EncodeDepartment : '#';
                    var department_handle = '';
                    var data_received = row.data_received;
                    var Received = App_ticket_ticket_today.getLastReceived(data_received);
                    var Worker = App_ticket_ticket_today.getWorker(data_received);
                    var TransferTo = App_ticket_ticket_today.getTransferTo(data_received);
                    var arr_filter_depart = [];
                    // for (var j = 0; j < data_received.length; j++) {
                    //     if (data_received[j].SetAction == "1") {
                    //         if (department_handle == '') {
                    //             department_handle += data_received[j].NameDepartmentDestination;
                    //             arr_filter_depart.push(data_received[j].DepartmentReceivedID);
                    //         }
                    //         else
                    //         {
                    //             var booldepart = true;
                    //             for (var k = 0; k < arr_filter_depart.length; k++) {
                    //                 if (arr_filter_depart[k] == data_received[j].DepartmentReceivedID ) {
                    //                    booldepart = false; 
                    //                    break;
                    //                 }
                    //             }
                    //             if (booldepart) {
                    //                 department_handle += '<br/>'+data_received[j].NameDepartmentDestination;
                    //             }
                                
                    //         }
                    //     }
                    // }

                    department_handle = data_received[0].NameDepartmentDestination;

                    var styleAsRequest = (row.RequestedBy == sessionNIP) ? 'background:#ffeb3b52' : '';

                    html += '<article class="timeline-entry">'+
                                ' <div class="timeline-entry-inner">'+
                                    '<div class="timeline-icon">'+
                                        '<img data-src="'+row.Photo+'" style="margin-top: -3px;" class="img-circle img-fitter" width="57">'+
                                        '<div class="ticket-number2">'+row.NoTicket+'</div>'+
                                    '</div>'+
                                    '<div class="timeline-label" style="'+styleAsRequest+'">'+
                                       '<div class="ticket-division">'+department_handle+'</div>'+
                                       
                                       '<h2><a href="'+hrefActionTicket+'">'+'<span>'+row.Title+'</span>'+'</a> </h2>'+
                                       '<div class="ticket-submited">'+row.NameRequested+' | '+row.RequestedAt+'</div>'+
                                       '<p>'+nl2br(row.Message)+'</p>'+
                                       pfiles+
                                       '<div class="ticket-accepted">'+
                                        '<div class="separator"><b>Received</b></div>'+
                                            Received['NameReceivedBy']+' | '+ Received['ReceivedAt']+
                                            '<div style="margin-top: 10px;">'+
                                                '<p>'+
                                                    'From : '+row.NameDepartmentTicket+
                                                    '<br/>'+
                                                    'Assign to : '+Worker+
                                                    '<br/>'+
                                                    'Transfer to : '+TransferTo+
                                                '</p>'+    
                                            '</div>'+
                                       '</div>'+
                                       '<div style="text-align: center;margin-top: 10px;">'+
                                           '<a href="javascript:void(0);" class="ModalReadMore" setTicket ="'+row.setTicket+'" token = "'+row.token+'" data-id ="'+row.ID+'">Read more <i class="fa fa-angle-double-right"></i></a>'+
                                       '</div>'+
                                   '</div>'+
                            '</article>';
                }
            }
            else
            {
                html += '<label>No data found in server</label>';
            }

            html += '</div>';
            $('#PanelProgressTicket').html(html);


            $('.img-fitter').imgFitter({
                // CSS background position
                backgroundPosition: 'center center',
                // for image loading effect
                fadeinDelay: 400,
                fadeinTime: 1200
            });

        }).fail(function(response){
           toastr.error('Error open ticket');
        })


    },

    ProgressCloseRest : function(){
        if (!$('#PanelCloseTicket').find('.flipInX').length) {
            loading_page('#PanelCloseTicket');
        }

        var url = base_url_js+"rest_ticketing/__ticketing_dashboard";
        var dataform = {
            action : 'close_ticket',
            auth : 's3Cr3T-G4N',
            DepartmentID : DepartmentID,
            NIP : sessionNIP,
        }
        var token = jwt_encode(dataform,'UAP)(*');
        
        AjaxLoadRestTicketing(url,token).then(function(response){
            var html = '';
            var count = response.count;
            var data = response.data;
            html += '<h3 class="close-ticket">Today Close-Ticket <span>'+count+'</span></h3>'+
                        '<hr/>'+
                        '<div class="timeline-centered">';
            if (data.length >0) {
                var EncodeDepartment = jwt_encode(DepartmentID,'UAP)(*');
                for (var i = 0; i < data.length; i++) {
                    var row = data[i];
                    var pfiles = (row.Files != null && row.Files != '') ? '<p><a href= "'+row.Files+'" target="_blank">Files Upload<a></p>' : '';
                    var hrefActionTicket = '#';
                    var department_handle = '';
                    var data_received = row.data_received;
                    var Received = App_ticket_ticket_today.getLastReceived(data_received,"0");
                    var Worker = App_ticket_ticket_today.getWorker(data_received,"0");
                    var TransferTo = App_ticket_ticket_today.getTransferTo(data_received);
                    var arr_filter_depart = [];
                    // for (var j = 0; j < data_received.length; j++) {
                    //     // console.log(data_received[j]);
                    //     if (data_received[j].DataReceived_Details.length > 0) {
                    //         if (department_handle == '') {
                    //             department_handle += data_received[j].NameDepartmentDestination;
                    //             arr_filter_depart.push(data_received[j].DepartmentReceivedID);
                    //         }
                    //         else
                    //         {
                    //             var booldepart = true;
                    //             for (var k = 0; k < arr_filter_depart.length; k++) {
                    //                 if (arr_filter_depart[k] == data_received[j].DepartmentReceivedID ) {
                    //                    booldepart = false; 
                    //                    break;
                    //                 }
                    //             }
                    //             if (booldepart) {
                    //                 department_handle += '<br/>'+data_received[j].NameDepartmentDestination;
                    //             }
                                
                    //         }
                    //     }
                        
                    // }

                    department_handle = data_received[0].NameDepartmentDestination;
                    var styleAsRequest = (row.RequestedBy == sessionNIP) ? 'background:#ffeb3b52' : '';
                    
                    html += '<article class="timeline-entry">'+
                                ' <div class="timeline-entry-inner">'+
                                    '<div class="timeline-icon">'+
                                        '<img data-src="'+row.Photo+'" style="margin-top: -3px;" class="img-circle img-fitter" width="57">'+
                                        '<div class="ticket-number2">'+row.NoTicket+'</div>'+
                                    '</div>'+
                                    '<div class="timeline-label" style="'+styleAsRequest+'">'+
                                       '<div class="ticket-division">'+department_handle+'</div>'+
                                       '<h2><a href="'+hrefActionTicket+'">'+'<span>'+row.Title+'</span>'+'</a> </h2>'+
                                       '<div class="ticket-submited">'+row.NameRequested+' | '+row.RequestedAt+'</div>'+
                                       '<p>'+nl2br(row.Message)+'</p>'+
                                       pfiles+
                                       '<div class="ticket-accepted">'+
                                        '<div class="separator"><b>Received</b></div>'+
                                            Received['NameReceivedBy']+' | '+ Received['ReceivedAt']+
                                            '<div style="margin-top: 10px;">'+
                                                '<p>'+
                                                    'From : '+row.NameDepartmentTicket+
                                                    '<br/>'+
                                                    'Assign to : '+Worker+
                                                    '<br/>'+
                                                    'Transfer to : '+TransferTo+
                                                '</p>'+    
                                            '</div>'+
                                       '</div>'+
                                       '<div style="text-align: center;margin-top: 10px;">'+
                                           '<a href="javascript:void(0);" class="ModalReadMore" setTicket ="'+row.setTicket+'" token = "'+row.token+'" data-id ="'+row.ID+'">Read more <i class="fa fa-angle-double-right"></i></a>'+
                                       '</div>'+
                                   '</div>'+
                            '</article>';
                }
            }
            else
            {
                html += '<label>No data found in server</label>';
            }

            html += '</div>';
            $('#PanelCloseTicket').html(html);


            $('.img-fitter').imgFitter({
                // CSS background position
                backgroundPosition: 'center center',
                // for image loading effect
                fadeinDelay: 400,
                fadeinTime: 1200
            });

        }).fail(function(response){
           toastr.error('Error open ticket');
        })
    },

    getLastReceived : function(data,SetAction = "1"){
        var rs = [];
        var count = data.length;
        var NameReceivedBy = '';
        var ReceivedAt = '';
        for (var i = 0; i < count; i++) {
            if (data[i].SetAction == SetAction && data[i].DataReceived_Details.length > 0) {
                NameReceivedBy = data[i].NameReceivedBy;
                ReceivedAt = data[i].ReceivedAt;
            }
        }

        rs = {
            NameReceivedBy : NameReceivedBy,
            ReceivedAt : ReceivedAt,
        };
        return rs;
    },

    getWorker : function(data,SetAction = "1"){
        var rs = '';
        var count = data.length;
        for (var i = 0; i < count; i++) {
            if (data[i].SetAction == SetAction && data[i].DataReceived_Details.length > 0) {
                var details = data[i].DataReceived_Details;
                for (var j = 0; j < details.length; j++) {
                    if (rs == '') {
                        rs += details[j].NameWorker;
                    }
                    else
                    {
                        rs += ','+details[j].NameWorker;
                    }
                }
            }
        }

        return rs;
    },

    getTransferTo : function(data){
        var rs = '';
        var count = data.length;
        for (var i = 0; i < count; i++) {
            if (data[i].Flag == "1") {
                if (rs == '') {
                    rs += data[i].NameDepartmentDestination;
                }
                else
                {
                     rs += ','+data[i].NameDepartmentDestination;
                }
                
            }
        }

        return rs;
    },

    GiveRatingCheck : function(){
        loadingStart();
        var url = base_url_js+"rest_ticketing/__ticketing_GiveRatingCheck";
        var dataform = {
            NIP : sessionNIP,
            auth : 's3Cr3T-G4N',
        }
        var token = jwt_encode(dataform,'UAP)(*');
                
        AjaxLoadRestTicketing(url,token).then(function(response){
            if (response.length == 0) {
                App_ticket_ticket_today.ModalFormCreateNewTicket();
            }
            else
            {
                /*
                    action fill rating
                */
                loadingEnd(500);
                setTimeout(function () {
                     App_ticket_ticket_today.setModalFormRating(response);
                },1000);
               
            }
        }).fail(function(response){
           toastr.error('Error connection to server');
        })
    },

    setModalFormRating : function(data){
        var html = '';
        for (var i = 0; i < data.length; i++) {
            var tokenData = jwt_encode(data[i],'UAP)(*');
            var htmlInfo = App_ticket_ticket_today.__show_html_show_more(tokenData);
            html += '<div class ="row">'+
                        '<div class = "well">'+
                            '<div class  = "col-md-12">'+
                                htmlInfo+
                            '</div>'+ 
                        '</div>'+
                    '</div>';      

        }

        $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Form Rating'+'</h4>');
        $('#GlobalModalLarge .modal-body').html(html);
        $('#GlobalModalLarge .modal-footer').html('<button class = "btn btn-success" id = "BtnSubmitRating">Submit </button> <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');
        $('#GlobalModalLarge').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        /*
            remove data yg tidak memiliki class input_form
        */
        $('#GlobalModalLarge').find('.well').each(function(e){
            var itsme = $(this);
            if (!itsme.find('.input_form').length) {
                itsme.remove();
            }
        })
    },

    __show_html_show_more : function(token){
        var data = jwt_decode(token);
        var tracking_list_html  = App_ticket_ticket_today.tracking_list_html(data);
        var pFiles = '';
        if (data.Files != null && data.Files != '') {
          pFiles =  '    <tr>' +
           '        <td>Files Upload</td>' +
           '        <td>:</td>' +
           '        <td>'+'<a href= "'+data.Files+'" target="_blank">Files Upload<a>'+'</td>' +
           '    </tr>' ;
        }
        var htmlss = '<div class="row">'+
                        '<div class = "col-md-4">'+
                            '<table class="table" id="tableDetailTicket">' +
                                 ' <tr style = "color:green;">'+
                                    '<td style="width: 25%;">NoTicket</td>'+
                                    '<td>:</td>'+
                                   ' <td>'+data.NoTicket+'</td>'+
                                  '</tr>'+
                                  '    <tr>' +
                                  '        <td style="width: 25%;">Title</td>' +
                                  '        <td>:</td>' +
                                  '        <td>'+data.Title+'</td>' +
                                  '    </tr>' +
                                  '    <tr>' +
                                  '        <td style="width: 25%;">Category</td>' +
                                  '        <td>:</td>' +
                                  '        <td>'+data.NameDepartmentDestination+' - '+data.CategoryDescriptions+'</td>' +
                                  '    </tr>' +
                                  '    <tr>' +
                                  '        <td>Message</td>' +
                                  '        <td>:</td>' +
                                  '        <td>'+nl2br(data.Message)+'</td>' +
                                  '    </tr>' +
                                  '    <tr>' +
                                  '        <td>Requested by</td>' +
                                  '        <td>:</td>' +
                                  '        <td>'+data.NameRequested+'</td>' +
                                  '    </tr>' +
                                  '    <tr>' +
                                  '        <td>Requested on</td>' +
                                  '        <td>:</td>' +
                                  '        <td>'+data.RequestedAt+'</td>' +
                                  '    </tr>' +
                                  pFiles+
                            '</table>'+
                        '</div>'+
                        '<div class = "col-md-8">'+
                           '<div id = "tracking">'+
                                tracking_list_html+
                           '</div>'+ 
                        '</div>'+
                      '</div>';
        return htmlss;
    },

    tracking_list_html : function(data){
        var data_received = data.data_received;
        var html =  '<b>Status : </b><i class="fa fa-circle" style="color:#d0af0c;"></i> Transfer To | <i class="fa fa-circle" style="color:lightgreen;"></i> Done '+
                    '<br/>';
        if (data_received.length > 0) {
          html +=  '<div class="tracking-list">';
          for (var i = 0; i < data_received.length; i++) {
            var row = data_received[i];
            // console.log(row);
            var tokenData =jwt_encode(row,'UAP)(*');
            var GetWorker = '';
            var DataReceived_Details = row.DataReceived_Details;
            var DataRating = row.DataRating;
            if (DataReceived_Details.length >  0) {
              GetWorker += '<table class = "table" style ="margin-top:15px;">'+
                              '<tr>'+
                                  '<td style="padding:4px;">Worker</td>'+
                                  '<td style="padding:4px;">DueDate</td>'+
                                  '<td style="padding:4px;">Status</td>'+
                              '</tr>';   
              for (var j = 0; j < DataReceived_Details.length; j++) {
                var r = DataReceived_Details[j];
                var st = '';
                if (r.Status == "-1") {
                  st = '<span style="color: red;"><i class="fa fa-minus-circle" aria-hidden="true"></i> '+'withdrawn'+'</span>';
                }
                else if(r.Status == "1"){
                  st = '<span style="color: #2196F3;"><i class="fa fa-user-circle-o" aria-hidden="true"></i> '+'working'+'</span>';
                }
                else{
                  st = '<span style="color: green;"><i class="fa fa-check-circle" aria-hidden="true"></i> '+'done'+'</span>';
                }
                GetWorker += '<tr>'+
                                '<td style="padding:4px;">'+r.NameWorker+'</td>'+
                                '<td style="padding:4px;">'+'<span>'+r.DueDateShow+'</span>'+'</td>'+
                                '<td style="padding:4px;">'+st+'</td>'+
                             '</tr>';
              }

              GetWorker += '</table>';

              if (row.Comment != '' && row.Comment != null && row.Comment != undefined ) {
                GetWorker += '<div class = "form-group" style="margin-top:5px;color:#0066ff;">'+
                                '<label>Comment from Handler : </label>'+
                                '<p>'+br2nl(row.Comment)+'</p>'+
                              '</div>';  
              }

              if (DataRating.length == 0) {
                GetWorker += '<div class = "thumbnail input_form" tokenData = "'+tokenData+'">'+
                                  '<div class = "form-group">'+
                                      '<label>Giving Rate</label>'+
                                      App_ticket_ticket_today.LoadSelectOptionRate()+
                                  '</div>'+
                                  '<div class = "form-group">'+
                                      '<label>Comment</label>'+
                                     '<textarea class="form-control fieldInput" rows="3" name="Comment"></textarea>'+
                                  '</div>'+
                                  '<div style = "text-align:right">'+
                                      '<button class = "btn btn-success btnSaveRating">Save</button>'+  
                                  '</div>'+
                              '</div>';   
              }
               
            }
            
            var SvgColor = '';
            if (row.Flag == "1") {
              SvgColor = 'style = "color:#d0af0c;" ';
            }
            if(row.ReceivedStatus == "1"){
              SvgColor = 'style = "color:lightgreen;" ';
            }
            
            html +=  '<div class="tracking-item">'+
                        '<div class="tracking-icon status-intransit">'+
                          '<svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" '+SvgColor+'>' +
                          '                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>' +
                          '</svg>' +
                        '</div>'+
                        '<div class="tracking-date">'+row.ReceivedAtTracking+'</div>'+
                        '<div class="tracking-content">'+
                          row.CategoryDescriptions+'<span>'+row.NameDepartmentDestination+' </span>'+
                        '</div>'+
                        GetWorker+
                      '</div>';  
          }

          html +=  '</div>';
        }

        return html;
    },

    LoadSelectOptionRate : function(){
        var html = '<select class = "form-control fieldInput" name = "Rate">'+
                    '<option value = "" selected disabled>--Choose Rate--</option>';
        for (var i = 1; i <= 5; i++) {
            var bintang = '';
            for (var j = 0; j < i; j++) {
                bintang += '*';
            }
            html += '<option value = "'+i+'">'+bintang+'</option>';
        }

        html += '</select>';
        return html;

    },

    SaveRating : function(selector){
        /* closest tracking-item */
        var selector_input_form = selector.closest('.input_form');
        var selector_tracking_item = selector.closest('.tracking-item');
        var tokendata = selector_input_form.attr('tokendata');
        var getData = jwt_decode(tokendata);
        var Rate = selector_input_form.find('.fieldInput[name="Rate"] option:selected').val();
        var Comment = selector_input_form.find('.fieldInput[name="Comment"]').val();
        var ModalBody = selector_tracking_item.closest('.modal-body');
        if (Rate != '' && Rate != undefined && Rate != null && Comment != '' && Comment != undefined && Comment != null) {
            var data = {
                ReceivedID : getData.ID,
                Rate : Rate,
                Comment : Comment,
                EntredBy : sessionNIP,
            };
            var dataform = {
                action : 'rating',
                data : data,
                auth : 's3Cr3T-G4N',
                TicketID :  getData.TicketID,
            };
            
            loading_button2(selector);
            var url = base_url_js+"rest_ticketing/__event_ticketing";
            var token = jwt_encode(dataform,'UAP)(*');
            AjaxSubmitRestTicketing(url,token).then(function(response){
                if (response.status == 1) {
                    selector_input_form.remove();
                    if (!selector_tracking_item.closest('.well').find('.input_form').length) {
                        selector_tracking_item.closest('.well').remove();
                    }
                    toastr.success('Thank you');

                    if ( !ModalBody.find('.well').length ) {
                        ModalBody.find('.row').html('<p><h4 style = "color:green;"><b>Thank for your rating.</b></h4></p>'+
                                                     '<p style = "color:Red;">Please Submit to Create Ticket</p>'
                            );
                    }
                    
                }
                else
                {
                    toastr.error(response.msg);
                    end_loading_button2(selector);
                }
            }).fail(function(response){
               toastr.error('Connection error,please try again');
               end_loading_button2(selector);     
            })
        }
        else
        {
            toastr.info('Rate and Comment are required');
        }
        
    },

};

$(document).ready(function() {
    App_ticket_ticket_today.Loaded();
});

$(document).off('change', '#SelectDepartmentID').on('change', '#SelectDepartmentID',function(e) {
    var getValue = $(this).find('option:selected').val();
    var getAbbr = $(this).find('option:selected').attr('abbr');
    var setDepartment = UpdateVarDepartmentID(getValue,getAbbr);
    if (setDepartment) {
        /*
            LoadAction
        */
        App_ticket_ticket_today.OpenTicketRest();
        App_ticket_ticket_today.PendingTicketRest();
        App_ticket_ticket_today.ProgressTicketRest();
        App_ticket_ticket_today.ProgressCloseRest();
    }
})
$(document).off('change', '.input_form[name="CategoryID"]').on('change', '.input_form[name="CategoryID"]',function(e) {
    var ToDepartmentSelected = $(this).find('option:selected').attr('department');
    $('.lblDepartment').html(ToDepartmentSelected);
})


$('#btnCreateNewTicket').click(function () {
    App_ticket_ticket_today.GiveRatingCheck();
});

$(document).off('click', '#BtnSubmitRating').on('click', '#BtnSubmitRating',function(e) {
    $('#GlobalModalLarge').find('#ModalbtnCancleForm').trigger('click');
    App_ticket_ticket_today.GiveRatingCheck();
})


$(document).off('click', '#btnsave_ticket').on('click', '#btnsave_ticket',function(e) {
    var selector = $(this);
    App_ticket_ticket_today.ActionCreateNewTicket(selector);
})

$(document).off('click', '.ModalReadMore').on('click', '.ModalReadMore',function(e) {
    var selector = $(this);
    var setTicket = selector.attr('setticket');
    var ID = selector.attr('data-id');
    var token = selector.attr('token');
    AppModalDetailTicket.ModalReadMore(ID,setTicket,token);
})

$(document).off('click', '.btnSaveRating').on('click', '.btnSaveRating',function(e) {
    var selector = $(this);
    App_ticket_ticket_today.SaveRating(selector);
})


</script>

