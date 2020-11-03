<?php if (count($auth) > 0): ?>
  
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Input Monthly Report</h4>
        </div>
        <div class="panel-body" style="min-height: 100px;">
          <?php if ($auth[0]['Access'] == 'Write' ): ?>
              <div class="form-group">
                  <label>Title</label>
                  <input type="text" class="form-control input" name = "Title">
              </div>
              <div class="form-group">
                  <label>Description</label>
                  <input type="text" class="form-control input" name = "Desc">
              </div>
              <div class="form-group">
                  <div class="row">
                    <div class="col-md-12" style="text-align: center;">
                      <label><u>Monthly Report</u></label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Month</label>
                        <select class="select2-select-00 full-width-fix select2-offscreen input" name = "Bulan">
                          
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Year</label>
                        <select class="select2-select-00 full-width-fix select2-offscreen input" name = "Tahun">
                          
                        </select>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group">
                  <label>Upload File</label>
                  <input type="file" class="form-control" name = "File" id = "UploadFile">
                  <div class = "fileShow"></div> 
                  <br>File Max 5 MB                                
              </div>
          <?php else: ?>
            <div class="row">
              <div class="col-md-12">
                <div style="padding: 10px;text-align: center;">
                  <h3>You don't have access to this menu</h3>
                </div>
              </div>
            </div>
          <?php endif ?>  
        </div>
        <div class="panel-footer" style="text-align: right;">
          <?php if ($auth[0]['Access'] == 'Write' ): ?>
            <button class="btn btn-success" action= "add" data-id ="" id="btnSave">Save</button>
          <?php endif ?> 
        </div>

    </div>
  

<?php else: ?>
  <div class="row">
    <div class="col-md-12">
      <div style="padding: 10px;text-align: center;">
        <h3>You don't have access to this menu</h3>
      </div>
    </div>
  </div>
<?php endif ?>
<script type="text/javascript">

  const LoadMonth = () => {
    const el = $('.input[name="Bulan"]');
    el.empty();
    SelectOptionloadBulan(el);
  }

  const LoadYear = () => {
    const el = $('.input[name="Tahun"]');
    const startYear  = 2014;
    const endYear  = <?php echo date('Y') ?>;

    let arr = [];

    for (var i = startYear; i <= endYear; i++) {
      arr.push(i);
    }

    el.empty();
    el.append('<option value="'+'all'+'" '+'selected'+'>'+'-'+'</option>');

    for (var i = 0; i < arr.length; i++) {
      el.append('<option value="'+ arr[i] +'" '+''+'>'+arr[i]+'</option>');
    }

    el.select2({
      // allowClear: true
    });


  }

   var AppForm_Monthly_Report = {
        setDefaultInput : function(){
            $('.input').val('');
            $('#btnSave').attr('action','add');
            $('#btnSave').attr('data-id','');
            LoadMonth();
            LoadYear();

        },
        ActionData : function(selector,action="add",ID=""){
            var htmlbtn = selector.html();
            var form_data = new FormData();
            var data = {};
            var valid = true;
            $('.input').not('div').each(function(){
                var field = $(this).attr('name');
                data[field] = $(this).val();

                if (field == 'Tahun' && $(this).val() == 'all' ) {
                  valid = false;
                  toastr.info('Please choose Tahun');
                  return;
                }
                else
                {
                  if ($(this).val() == '' || $(this).val() === undefined ) {
                    valid = false;
                    toastr.info('Please choose '+field);
                    return;
                  }
                }
                

            })

            if (valid) {
                data['DateReport'] = data['Tahun']+'-'+data['Bulan']+'-01';
                delete data['Tahun']; 
                delete data['Bulan']; 

                var dataform = {
                    action : action,
                    data : data,
                    ID : ID,
                };
                var token = jwt_encode(dataform,"UAP)(*");
                form_data.append('token',token);

                if ( $( '#'+'UploadFile').length ) {
                    var UploadFile = $('#'+'UploadFile')[0].files;
                    for(var count = 0; count<UploadFile.length; count++)
                    {
                     form_data.append("File[]", UploadFile[count]);
                    }
                }
                if (confirm('Are you sure ?')) {
                    loading_button2(selector);
                    var url = base_url_js + "rektorat/crud_monthly_report";
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
                                AppForm_Monthly_Report.setDefaultInput();
                                end_loading_button2(selector,htmlbtn);
                                oTable.ajax.reload( null, false );
                              },
                              error: function (data) {
                                toastr.error("Connection Error, Please try again", 'Error!!');
                                end_loading_button2(selector,htmlbtn);
                                
                              }
                            })
                }
            }

            
        },


        validation_file : function(selector,TheName = ''){
            var files = selector[0].files;
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
               // console.log(fsize);

               if(fsize > 5000000) // 5mb
               {
                // msgStr += TheName + ' which file Number '+ no + ' Image File Size is very big<br>';
                msgStr += TheName + ' Image File Size is very big<br>';
                //toastr.error("Image File Size is very big", 'Failed!!');
                //return false;
               }
               
              }
            }

            if (msgStr != "") {
              toastr.error(msgStr, 'Failed!!');
              return false;
            }
            else
            {
              return true;
            }
        },
        loaded : function(){
            $('#datetimepicker6').datetimepicker({
                format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false
            })
            AppForm_Monthly_Report.setDefaultInput();
        },

    };

    $(document).ready(function() {
        AppForm_Monthly_Report.loaded();
    })


    $(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
       var selector = $(this);
       var action = selector.attr('action');
       var ID = selector.attr('data-id'); 
       var S_upload = $('#UploadFile');
       var cekFile =  AppForm_Monthly_Report.validation_file(S_upload,'Upload File');
       if (cekFile) {
        AppForm_Monthly_Report.ActionData(selector,action,ID);
       }
       
    })
</script>