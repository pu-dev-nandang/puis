

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
      ModalDetailTicket : function(ID,setTicket,token)
      { 
        // $('.modal-dialog').attr('style','width:100%;');
        var data = jwt_decode(token);
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Detail Ticket</h4>');

        var htmlss = '<div class="row"><div class = "col-md-12"><table class="table" id="tableDetailTicket">' +
            '    <tr>' +
            '        <td style="width: 25%;">Title</td>' +
            '        <td>:</td>' +
            '        <td>'+data.Title+'</td>' +
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
            '</table></div></div>';

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