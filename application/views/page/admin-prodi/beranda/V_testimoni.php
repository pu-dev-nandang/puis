
<div class="row" style="margin-top: 30px;">
    <div class="col-md-6">
        <table class="table">
            <tr>
                <td style="width: 15%;">Language</td>
                <td style="width: 1%;">:</td>
                <td>
                    <select style="max-width: 150px;" id="LangID" class="form-control"></select>
                </td>
            </tr>
            <tr>
                <td style="width: 15%;">Student</td>
                <td style="width: 1%;">:</td>
                <td>
                      <select style="width: 100%;" id="formName" ></select>
                </td>
            </tr>
            <tr>
                <td style="width: 15%;">Upload Images</td>
                <td style="width: 1%;">:</td>
                <td>
                  <input type="file" id="uploadFile" name="uploadFile" accept="jpg/png">
                  <span class="red">* Images size weight x height : 200 x 250px</span>
                </td>
            </tr>
            <tr>
                <td>Description</td>
                <td>:</td>
                <td>
                    <textarea id="Description" class="form-control"></textarea>
                </td>
            </tr>

            <tr>
                <td colspan="3" style="text-align: right;">
                    <button class="btn btn-success" id="btnSave">Save</button>
                </td>
            </tr>
        </table>
    </div>

    <div class="col-md-6" style="border-left: 1px solid #CCCCCC;">
        <div id="viewDataDesc"></div>
    </div>
</div>

<script>
    $(document).ready(function () {

        LoadNama();
        window.G_Type = 'testimonials';
        loadDataTestimonials();
        loadSelectOptionLanguageProdi('#LangID','');

        $('#Description').summernote({
            placeholder: 'Text your announcement',
            tabsize: 2,
            height: 300,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });

        
        var firsLoad = setInterval(function () {

            var LangID = $('#LangID').val();
            if(LangID!='' && LangID!=null){
                loadDataOption();
                clearInterval(firsLoad);
            }

        },1000);

    });

    $('#LangID').change(function () {
        var LangID = $('#LangID').val();
        if(LangID!='' && LangID!=null){
            loadDataOption();
        }
    });

    function loadDataTestimonials() {
        var data = {
            action : 'readProdiTexting',
            Type : G_Type
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api-prodi/__crudDataProdi';
        var imgmhs = base_url_js+'images/Testimonials/';
        $.post(url,{token:token},function (jsonResult) {
            $('#viewDataDesc').empty();
            if(jsonResult.length>0){

                $.each(jsonResult,function (i,v) {
                    $('#viewDataDesc').append('<div class="well"> <img src="'+imgmhs+''+v.Photo+'"  width="15%"><h3><b> '+v.NPM+' || '+v.Name+'</b></h3><hr><h3 style="margin-top: 5px;"><b>'+v.Language+'</b></h3><div>'+v.Description+'</div></div>');
                });

            } else {
                $('#viewDataDesc').html('<div class="well">Data not yet</div>');
            }

        });
    }

    function loadDataOption() {
        var LangID = $('#LangID').val();
        if(LangID!='' && LangID!=null){
            var data = {
                action : 'readDataProdiTexting',
                Type : G_Type,
                LangID : LangID
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api-prodi/__crudDataProdi';
            $.post(url,{token:token},function (jsonResult) {
                if(jsonResult.length>0){
                    $('#Description').summernote('code', jsonResult[0].Description);
                } else {
                    $('#Description').summernote('code', '');
                }

            });
        }
    }

    
    function LoadNama() { // load data student
        var selector =$('#formName');
        var url = base_url_js+'api/__getStudentsServerSide';
        var data = {
            auth : 's3Cr3T-G4N',
            mode : 'showDataDosen'
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token },function (resultJson) {
            
        }).done(function(resultJson) {
            //var response = jQuery.parseJSON(resultJson);
            selector.empty()
            for (var i = 0; i < resultJson.length; i++) {
                var NPM = resultJson[i].NPM
                var Name = resultJson[i].Name
                selector.append(
                    ' <option value="'+NPM+'">'+NPM+'|'+Name+'</option>'
                    )

            }
            selector.select2({
                //allowClear: true

            });
        }).fail(function() {
          toastr.info('No Result Data');
        }).always(function() {
                        
        }); 

    }

    $('#btnSave').click(function () {

        var NPM = $('#formName').val(); 
        // alert(NPM);
        var LangID = $('#LangID').val();
        var Description = $('#Description').val();
        
        var form_data = new FormData();
        var find = true;

        if(LangID!='' && LangID!=null &&
            Description!='' && Description!=null){

            var prodi_texting = {
              LangID : LangID,
              Type : 'testimonials',
              Description : Description,
              UpdatedBy : sessionNIP,
            };

            var student_testimonials = {
              NPM : NPM,
            }
           
            // upload file
            $('input[type="file"]').each(function(){
                var IDFile = $(this).attr('id');
                var ev = $(this);
                var NameItem = 'ID '+IDFile;
                if (!file_validation2(ev,NameItem) ) {
                  find = false;
                  return false;
                }
            })
            if (find) { // validasi file berhasil
                    // console.log('asd');
                      if ( $( '#'+'uploadFile').length ) { // jika upload file
                      var UploadFile = $('#'+'uploadFile')[0].files;
                        for(var count = 0; count<UploadFile.length; count++)
                        {
                         form_data.append("uploadFile[]", UploadFile[count]);
                        }
                      }
            var data = {
              action : 'saveDataTestimonials',
              prodi_texting : prodi_texting,
              student_testimonials : student_testimonials,
            };
          
            loading_button('#btnSave');
            var token = jwt_encode(data,'UAP)(*');
            form_data.append('token',token);
            var url = base_url_js+'api-prodi/__crudDataProdi';
            $.ajax({
              type:"POST",
              url:url,
              data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
              contentType: false,       // The content type used when sending data to the server.
              cache: false,             // To unable request pages to be cached
              processData:false,
              dataType: "json",
              success:function(data)
              {
                loadDataTestimonials();
                toastr.success('Data saved','Success');
                $('#btnSave').html('save');
                $('#btnSave').prop('disabled',false);

              },
              error: function (data) {
                 toastr.error('Form required','Error');
                 thisbtn.prop('disabled',false).html('Save');
              }
            })
          }
        }
    });

    function file_validation2(ev,TheName = '')
  {
      var files = ev[0].files;
      var error = '';
      var msgStr = '';
      var max_upload_per_file = 4;
      

      if (files.length > max_upload_per_file) {
        msgStr += TheName +' should not be more than 4 Files<br>';

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
          // msgStr += TheName +' which file Number '+ no + ' Invalid Type File<br>';
          msgStr += TheName +' Invalid Type File<br>';
          //toastr.error("Invalid Image File", 'Failed!!');
          // return false;
         }

         var oFReader = new FileReader();
         oFReader.readAsDataURL(files[count]);
         var f = files[count];
         var fsize = f.size||f.fileSize;

         
         console.log(fsize);

         if(fsize > 2000000) // 2mb
         {
          // msgStr += TheName + ' which file Number '+ no + ' Image File Size is very big<br>';
          msgStr += TheName + ' Image File Size is very big<br>';
          //toastr.error("Image File Size is very big", 'Failed!!');
          //return false;
         }

                
        // oFReader.onload = function (e) { //max widht height

        //     var image = new Image();

        //     image.src = e.target.result;
        //     image.onload = function () {

        //         var height = this.height;
        //         var width = this.width;
        //         console.log(this);
        //         if ((height == 500 ) && (width == 1920 )) {
        //             msgStr += TheName + 'Height and Width must not exceed 1920*500.';
        //             return false;
        //         }else{
        //           return true;
        //         }
                
        //     };
        // }
         
        }
      }

      if (msgStr != '') {
        toastr.error(msgStr, 'Failed!!');
        return false;
      }
      else
      {
        return true;
      }
  }

</script>
