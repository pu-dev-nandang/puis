<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="<?php if($this->uri->segment(2)=='document-generator' &&  ( $this->uri->segment(3) == '' ||  $this->uri->segment(3) == null ) ) { echo 'active'; } ?>">
            <a href="<?php echo base_url('rectorat/document-generator'); ?>">Document</a>
        </li>
        <?php if ($this->session->userdata('PositionMain')['IDDivision'] == 12 && $this->session->userdata('NIP') == '2018018'): ?>
          <li class="<?php if($this->uri->segment(3)=='setting') { echo 'active'; } ?>">
              <a href="<?php echo base_url('rectorat/document-generator/setting'); ?>">Setting</a>
          </li>
        <?php endif ?>
    </ul>
    <div style="border-top: 1px solid #cccccc">

        <div class="row" style="margin-top: 15px;">
            <div class="col-md-12">
                <?php echo $page; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  function file_validation_generator(ev,TheName = '')
  {
      var files = ev[0].files;
      var error = '';
      var msgStr = '';
      var max_upload_per_file = 1;
      if (files.length > 0) {
        if (files.length > max_upload_per_file) {
          msgStr += 'Upload File '+TheName + ' 1 Document should not be more than 1 Files<br>';

        }
        else
        {
          for(var count = 0; count<files.length; count++)
          {
           var no = parseInt(count) + 1;
           var name = files[count].name;
           var extension = name.split('.').pop().toLowerCase();
           if(jQuery.inArray(extension, ['docx']) == -1)
           {
            msgStr += 'Upload File '+TheName + ' Invalid Type File<br>';
           }

           var oFReader = new FileReader();
           oFReader.readAsDataURL(files[count]);
           var f = files[count];
           var fsize = f.size||f.fileSize;

           if(fsize > 3000000) // 3mb
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


  function AjaxSubmitTemplate(url='',token='',ArrUploadFilesSelector=[]){
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
         url:url,
         data: form_data,
         contentType: false,       // The content type used when sending data to the server.
         cache: false,             // To unable request pages to be cached
         processData:false,
         dataType: "json",
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
</script>
