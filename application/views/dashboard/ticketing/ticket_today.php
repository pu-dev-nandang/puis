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
            <div class="row bg-ticket">
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
            '            <textarea class="form-control input_form" rows="3" name="Message"></textarea>' +
            '        </td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>File</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <input type="file" name = "Files" id = "UploadFile">' +
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
                    $('#GlobalModal').modal({
                        'show' : true,
                        'backdrop' : 'static'
                    });
                    $('.input_form[name="CategoryID"]').trigger('change');
                    clearInterval(firstLoad);
                }
            },1000);
            setTimeout(function () {
                clearInterval(firstLoad);
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
            html += '<h3 class="open-ticket">Open Ticket <span>'+count+'</span></h3>'+
                        '<hr/>'+
                        '<div class="timeline-centered">';
            if (data.length >0) {
                var EncodeDepartment = jwt_encode(DepartmentID,'UAP)(*');
                for (var i = 0; i < data.length; i++) {
                    var row = data[i];
                    var pfiles = (row.Files != null && row.Files != '') ? '<p><a href= "'+row.Files+'" target="_blank">Files Upload<a></p>' : '';
                    var hrefActionTicket = (row.setTicket == 'write') ? base_url_js+'ticket'+'/set_action_first/'+row.NoTicket+'/'+EncodeDepartment : '#';
                    html += '<article class="timeline-entry">'+
                                ' <div class="timeline-entry-inner">'+
                                    '<div class="timeline-icon">'+
                                        '<img data-src="'+row.Photo+'" style="margin-top: -3px;" class="img-circle img-fitter" width="57">'+
                                    '</div>'+
                                    '<div class="timeline-label">'+
                                        '<div class="ticket-division">'+row.NameDepartmentDestination+'</div>'+
                                        '<div class="ticket-number">'+row.NoTicket+'</div>'+
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
            html += '<h3 class="pending-ticket">Pending Ticket <span>'+count+'</span></h3>'+
                        '<hr/>'+
                        '<div class="timeline-centered">';
            if (data.length >0) {
                var EncodeDepartment = jwt_encode(DepartmentID,'UAP)(*');
                for (var i = 0; i < data.length; i++) {
                    var row = data[i];
                    var pfiles = (row.Files != null && row.Files != '') ? '<p><a href= "'+row.Files+'" target="_blank">Files Upload<a></p>' : '';
                    var hrefActionTicket = (row.setTicket == 'write') ? base_url_js+'ticket'+'/set_action_first/'+row.NoTicket+'/'+EncodeDepartment : '#';
                    html += '<article class="timeline-entry">'+
                                ' <div class="timeline-entry-inner">'+
                                    '<div class="timeline-icon">'+
                                        '<img data-src="'+row.Photo+'" style="margin-top: -3px;" class="img-circle img-fitter" width="57">'+
                                    '</div>'+
                                    '<div class="timeline-label">'+
                                       '<div class="ticket-division">'+row.NameDepartmentDestination+'</div>'+
                                       '<div class="ticket-number">'+row.NoTicket+'</div>'+
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
            html += '<h3 class="progres-ticket">Progres Ticket <span>'+count+'</span></h3>'+
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
                    for (var j = 0; j < data_received.length; j++) {
                        if (data_received[j].SetAction == "1") {
                            if (department_handle == '') {
                                department_handle += data_received[j].NameDepartmentDestination;
                                arr_filter_depart.push(data_received[j].DepartmentReceivedID);
                            }
                            else
                            {
                                var booldepart = true;
                                for (var k = 0; k < arr_filter_depart.length; k++) {
                                    if (arr_filter_depart[k] == data_received[j].DepartmentReceivedID ) {
                                       booldepart = false; 
                                       break;
                                    }
                                }
                                if (booldepart) {
                                    department_handle += '<br/>'+data_received[j].NameDepartmentDestination;
                                }
                                
                            }
                        }
                    }
                    html += '<article class="timeline-entry">'+
                                ' <div class="timeline-entry-inner">'+
                                    '<div class="timeline-icon">'+
                                        '<img data-src="'+row.Photo+'" style="margin-top: -3px;" class="img-circle img-fitter" width="57">'+
                                    '</div>'+
                                    '<div class="timeline-label">'+
                                       '<div class="ticket-division">'+department_handle+'</div>'+
                                       '<div class="ticket-number">'+row.NoTicket+'</div>'+
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
            html += '<h3 class="close-ticket">Close Ticket <span>'+count+'</span></h3>'+
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
                    for (var j = 0; j < data_received.length; j++) {
                        if (data_received[j].SetAction == "1") {
                            if (department_handle == '') {
                                department_handle += data_received[j].NameDepartmentDestination;
                                arr_filter_depart.push(data_received[j].DepartmentReceivedID);
                            }
                            else
                            {
                                var booldepart = true;
                                for (var k = 0; k < arr_filter_depart.length; k++) {
                                    if (arr_filter_depart[k] == data_received[j].DepartmentReceivedID ) {
                                       booldepart = false; 
                                       break;
                                    }
                                }
                                if (booldepart) {
                                    department_handle += '<br/>'+data_received[j].NameDepartmentDestination;
                                }
                                
                            }
                        }
                    }
                    html += '<article class="timeline-entry">'+
                                ' <div class="timeline-entry-inner">'+
                                    '<div class="timeline-icon">'+
                                        '<img data-src="'+row.Photo+'" style="margin-top: -3px;" class="img-circle img-fitter" width="57">'+
                                    '</div>'+
                                    '<div class="timeline-label">'+
                                       '<div class="ticket-division">'+department_handle+'</div>'+
                                       '<div class="ticket-number">'+row.NoTicket+'</div>'+
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
    App_ticket_ticket_today.ModalFormCreateNewTicket();
});

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

    $(document).on('click','.showReadMoreTicket',function () {
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Read More Ticket</h4>');

        var htmlss = '<div class="row">' +
            '' +
            '        <div class="col-md-12 col-lg-12">' +
            '            <div id="tracking-pre"></div>' +
            '            <div id="tracking">' +
            '' +
            '                <div class="thumbnail" style="border-radius: 0px;border-bottom: none;padding: 15px;">' +
            '                    <h3 style="margin-top: 0px;margin-bottom: 3px;"><b>Lorem Ipsum is simply dummy text of the printing</b></h3>' +
            '                    <div style="margin-bottom: 10px;color: cornflowerblue;">Nandang Mulyadi | 19 Januari 2019 09:00</div>' +
            '                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letrase' +
            '                </div>' +
            '' +
            '                <div class="tracking-list">' +
            '                    <div class="tracking-item">' +
            '                        <div class="tracking-icon status-intransit">' +
            '                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">' +
            '                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>' +
            '                            </svg>' +
            '                            <!-- <i class="fas fa-circle"></i> -->' +
            '                        </div>' +
            '                        <div class="tracking-date">Aug 10, 2018<span>05:01 PM</span></div>' +
            '                        <div class="tracking-content">DESTROYEDPER SHIPPER INSTRUCTION<span>KUALA LUMPUR (LOGISTICS HUB), MALAYSIA, MALAYSIA </span></div>' +
            '                    </div>' +
            '                    <div class="tracking-item">' +
            '                        <div class="tracking-icon status-intransit">' +
            '                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">' +
            '                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>' +
            '                            </svg>' +
            '                            <!-- <i class="fas fa-circle"></i> -->' +
            '                        </div>' +
            '                        <div class="tracking-date">Aug 10, 2018<span>11:19 AM</span></div>' +
            '                        <div class="tracking-content">SHIPMENT DELAYSHIPPER INSTRUCTION TO DESTROY<span>SHENZHEN, CHINA, PEOPLE\'S REPUBLIC</span></div>' +
            '                    </div>' +
            '                    <div class="tracking-item">' +
            '                        <div class="tracking-icon status-intransit">' +
            '                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">' +
            '                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>' +
            '                            </svg>' +
            '                            <!-- <i class="fas fa-circle"></i> -->' +
            '                        </div>' +
            '                        <div class="tracking-date">Jul 27, 2018<span>04:08 PM</span></div>' +
            '                        <div class="tracking-content">DELIVERY ADVICERequest Instruction from ORIGIN<span>KUALA LUMPUR (LOGISTICS HUB), MALAYSIA, MALAYSIA</span></div>' +
            '                    </div>' +
            '                    <div class="tracking-item">' +
            '                        <div class="tracking-icon status-intransit">' +
            '                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">' +
            '                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>' +
            '                            </svg>' +
            '                            <!-- <i class="fas fa-circle"></i> -->' +
            '                        </div>' +
            '                        <div class="tracking-date">Jul 20, 2018<span>05:25 PM</span></div>' +
            '                        <div class="tracking-content">Delivery InfoCLOSED-OFFICE/HOUSE CLOSED<span>KUALA LUMPUR (LOGISTICS HUB), MALAYSIA, MALAYSIA</span></div>' +
            '                    </div>' +
            '' +
            '                </div>' +
            '            </div>' +
            '        </div>' +
            '    </div>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '');


        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

</script>

