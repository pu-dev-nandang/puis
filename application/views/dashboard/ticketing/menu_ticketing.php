

<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <!--        <li class="--><?php //if($this->uri->segment(3)=='list-student') { echo 'active'; } ?><!--">-->
        <!--            <a href="--><?php //echo base_url('academic/final-project/list-student'); ?><!--">Final Project</a>-->
        <!--        </li>-->
        <li class="<?php if($this->uri->segment(2)=='' || $this->uri->segment(2)=='ticket-today') { echo 'active'; } ?>">
            <a href="<?php echo base_url('ticket/ticket-today'); ?>">Ticket Today</a>
        </li>


        <li class="<?php if($this->uri->segment(2)=='ticket-list') { echo 'active'; } ?>">
            <a href="<?php echo base_url('ticket/ticket-list'); ?>">Ticket List</a>
        </li>

        <li class="<?php if($this->uri->segment(2)=='setting') { echo 'active'; } ?>">
            <a href="<?php echo base_url('ticket/setting'); ?>">Setting</a>
        </li>

    </ul>
    <div style="border-top: 1px solid #cccccc">

        <div class="row">
            <div class="col-md-12">
                <?php echo $page; ?>
            </div>
        </div>

    </div>
</div>


<script>
    window.rest_setting = <?php echo json_encode($Authen) ?>;
    window.DepartmentID = "<?php echo $DepartmentID ?>";
    window.DepartmentAbbr = "<?php echo $DepartmentAbbr ?>";
    var Hjwtkey = rest_setting[0].Hjwtkey;
    var Apikey = rest_setting[0].Apikey;
    window.ArrSelectOptionDepartment = <?php echo json_encode($ArrSelectOptionDepartment) ?>;
    $(document).ready(function() {
        $('.fixed-header').addClass('sidebar-closed');
    });

    function LoadSelectOptionDepartmentFiltered(selector){
        selector.empty();
        for (var i = 0; i < ArrSelectOptionDepartment.length; i++) {
           var selected = (ArrSelectOptionDepartment[i].Code == DepartmentID) ? 'selected' : '';
           selector.append(
                '<option value = "'+ArrSelectOptionDepartment[i].Code+'" '+selected+' abbr = "'+ArrSelectOptionDepartment[i].Abbr+'" >'+ArrSelectOptionDepartment[i].Name2+'</option>'
            );
           selector.select2({

           });
        }
    }

    function LoadSelectOptionStatusTicket(selector,selectedata=2){
        var url =base_url_js+"rest_ticketing/__CRUDStatusTicket";
        var dataform = {
            action : 'read',
            auth : 's3Cr3T-G4N',
        };
        var token = jwt_encode(dataform,'UAP)(*');
        AjaxLoadRestTicketing(url,token).then(function(response){
          selector.empty();
          response =  response.data;
          for (var i = 0; i < response.length; i++) {
             var selected = (response[i][1] == selectedata) ? 'selected' : '';
             selector.append(
                  '<option value = "'+response[i][1]+'" '+selected+' >'+response[i][2]+'</option>'
              );
          }
        })
    }

    function UpdateVarDepartmentID(getValue,getAbbr){
         window.DepartmentID = getValue;
         window.DepartmentAbbr = getAbbr;
         return true;
    }

    function AjaxLoadRestTicketing(url='',token=''){
         var def = jQuery.Deferred();
         var form_data = new FormData();
         form_data.append('token',token);
         $.ajax({
           type:"POST",
           url:url+'?apikey='+Apikey,
           data: form_data,
           contentType: false,       // The content type used when sending data to the server.
           cache: false,             // To unable request pages to be cached
           processData:false,
           dataType: "json",
           beforeSend: function (xhr)
           {
              xhr.setRequestHeader("Hjwtkey",Hjwtkey);
           },
           success:function(data)
           {
            def.resolve(data);
           },  
           error: function (data) {
             // toastr.info('No Result Data'); 
             def.reject();
           }
         })
         return def.promise();
    }

    function LoadSelectOptionCategory(selector,CategorySelected = '')
    {
        var url =base_url_js+"rest_ticketing/__CRUDCategory";
        var dataform = {
            action : 'read',
            auth : 's3Cr3T-G4N',
        };
        var token = jwt_encode(dataform,'UAP)(*');
        AjaxLoadRestTicketing(url,token).then(function(response){
             selector.empty();
             var dataresponse = response.data;
             if (dataresponse.length>0) {
                for (var i = 0; i < dataresponse.length; i++) {
                   var selected = (CategorySelected == dataresponse[i][3]) ? 'selected' : '';
                   if (selected == '') {
                    selected = (i==0) ? 'selected' : '';
                   }
                   selector.append(
                        '<option value = "'+dataresponse[i][3]+'" '+selected+' department = "'+dataresponse[i][7]+'" >'+dataresponse[i][7]+' - '+dataresponse[i][1]+'</option>'
                    );
                }

                selector.select2({

                });
             }
        })
    }

    function AjaxSubmitRestTicketing(url='',token='',ArrUploadFilesSelector=[]){
         var def = jQuery.Deferred();
         var form_data = new FormData();
         form_data.append('token',token);
         if (ArrUploadFilesSelector.length>0) {
            for (var i = 0; i < ArrUploadFilesSelector.length; i++) {
                var NameField = ArrUploadFilesSelector[i].NameField+'[]';
                var Selector = ArrUploadFilesSelector[i].Selector;
                var UploadFile = Selector[0].files;
                for(var count = 0; count<UploadFile.length; count++)
                {
                 form_data.append(NameField, UploadFile[count]);
                }
            }
         }

         $.ajax({
           type:"POST",
           url:url+'?apikey='+Apikey,
           data: form_data,
           contentType: false,       // The content type used when sending data to the server.
           cache: false,             // To unable request pages to be cached
           processData:false,
           dataType: "json",
           beforeSend: function (xhr)
           {
              xhr.setRequestHeader("Hjwtkey",Hjwtkey);
           },
           success:function(data)
           {
            def.resolve(data);
           },  
           error: function (data) {
             // toastr.info('No Result Data'); 
             def.reject();
           }
         })
         return def.promise();
    }

    function file_validation_ticketing(ev,TheName = '')
    {
        var files = ev[0].files;
        var error = '';
        var msgStr = '';
        var max_upload_per_file = 4;
        if (files.length > 0) {
          if (files.length > max_upload_per_file) {
            msgStr += 'Upload File '+TheName + ' 1 Document should not be more than 4 Files<br>';

          }
          else
          {
            for(var count = 0; count<files.length; count++)
            {
             var no = parseInt(count) + 1;
             var name = files[count].name;
             var extension = name.split('.').pop().toLowerCase();
             if(jQuery.inArray(extension, ['jpg' ,'png','jpeg','pdf','doc','docx']) == -1)
             {
              msgStr += 'Upload File '+TheName + ' Invalid Type File<br>';
             }

             var oFReader = new FileReader();
             oFReader.readAsDataURL(files[count]);
             var f = files[count];
             var fsize = f.size||f.fileSize;

             if(fsize > 2000000) // 2mb
             {
              msgStr += 'Upload File '+TheName +  ' Image File Size is very big<br>';
             }
             
            }
          }
        }
        else
        {
          msgStr += 'Upload File '+TheName + ' Required';
        }
        return msgStr;
    }

    var AppModalDetailTicket = {
      tracking_list_html : function(data){
        var data_received = data.data_received;
        var html =  '';
        if (data_received.length > 0) {
          html +=  '<div class="tracking-list">';
          for (var i = 0; i < data_received.length; i++) {
            var row = data_received[i];
            var GetWorker = '';
            var DataReceived_Details = row.DataReceived_Details;
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
                  st = 'withdrawn';
                }
                else if(r.Status == "1"){
                  st = 'working';
                }
                else{
                  st = 'done';
                }
                GetWorker += '<tr>'+
                                '<td style="padding:4px;">'+r.NameWorker+'</td>'+
                                '<td style="padding:4px;">'+'<span>'+r.DueDateShow+'</span>'+'</td>'+
                                '<td style="padding:4px;">'+'<span>'+st+'</span>'+'</td>'+
                             '</tr>';
              }

              GetWorker += '</table>';
            }
            html +=  '<div class="tracking-item">'+
                        '<div class="tracking-icon status-intransit">'+
                          '                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">' +
                          '                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>' +
                          '                            </svg>' +
                        '</div>'+
                        '<div class="tracking-date">'+row.ReceivedAtTracking+'</div>'+
                        '<div class="tracking-content">'+
                          row.CategoryDescriptions+'<span>'+row.NameDepartmentDestination+' </span>'+
                        '</div>'+
                        GetWorker+
                      '</div>';  
          }

          html +=  '</div">';
        }

        return html;
        
      },

      ModalReadMore : function(ID,setTicket,token)
      { 
        // $('.modal-dialog').attr('style','width:100%;');
        var data = jwt_decode(token);

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Read More</h4>');
        var tracking_list_html  = this.tracking_list_html(data);
        var htmlss = '<div class="row">'+
                        '<div class = "col-md-12">'+
                          '<div id = "tracking">'+
                              '<div class = "thumbnail" style="border-radius: 0px;border-bottom: none;padding: 15px;">'+
                                '<table class="table" id="tableDetailTicket">' +
                                     ' <tr>'+
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
                                      '        <td>'+data.Message+'</td>' +
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
                                '</table>'+
                              '</div>'+
                              tracking_list_html+
                          '</div>'+
                        '</div>'+
                      '</div>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '');

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
      },
    };
</script>