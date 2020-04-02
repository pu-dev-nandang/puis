<div class="row">
    <div class="col-md-3 panel-admin" style="border-right: 1px solid #CCCCCC;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Input</h4>
            </div>
            <div class="panel-body" style="min-height: 100px;">
                <div class="form-group">
                    <label>TA</label>
                    <select class="form-control input" name = "TA"></select>
                </div>
                <div class="form-group">
                    <label>Date of Entry</label>
                    <div class="input-group input-append date datetimepicker">
                        <input data-format="yyyy-MM-dd" class="form-control input" type=" text" readonly="" value = "<?php echo date('Y-m-d') ?>" name = "Tgl_msk">
                        <span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
                    </div>
                </div>
                <div class="form-group">
                    <label>SK Number</label>
                    <input type="text" class="form-control input" name = "NoSK">
                </div>
                <div class="form-group">
                    <label>Upload File</label>
                    <input type="file" class="form-control" name = "FileUpload" id = "UploadFile">
                    <div class = "fileShow"></div>
                </div>
            </div>
            <div class="panel-footer" style="text-align: right;">
                <button class="btn btn-success" action= "add" data-id ="" id="btnSave">Save</button>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="thumbnail" style="min-height: 50px;">
            <div class="row">
                <div class="col-md-12">
                    <div id="viewtable">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var oTable;
    var AppJQ = {
        LoadSelectOptionTA : function(){
                                var selector = $('.input[name="TA"]');
                                selector.empty();
                                var url = base_url_js+'api3/__getAllTA_MHS';
                                $.get(url,function (resultJson) {
                                            
                                }).done(function(resultJson) {
                                    for (var i = 0; i < resultJson.length; i++) {
                                        selector.append(
                                                '<option value = "'+resultJson[i]+'">'+resultJson[i]+'</option>'
                                            );
                                    }
                                })
        },
        setDefaultInput : function(){
            $('.input[name="NoSK"]').val('');
            $('#UploadFile').val('');
            $('#btnSave').attr('action','add');
            $('#btnSave').attr('data-id','');
            $('.fileShow').find('li').remove();
        },
        loaded : function(){
            loadingStart();
            AppJQ.LoadSelectOptionTA();
            var firstLoad = setInterval(function () {
                var TA = $('.input[name="TA"]').val();
                if(TA!='' && TA!=null){
                    $('.datetimepicker').datetimepicker({
                     format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
                    });
                    loadingEnd(500);
                    AppJQ.LoadTable();
                    AppJQ.LoadAjaxData();
                    clearInterval(firstLoad);
                }
            },1000);
            setTimeout(function () {
                clearInterval(firstLoad);
            },1000);

        },
        LoadTable : function(){
              var selector = $('#viewtable');
              var htmltable = '<table class = "table" id = "TblSKMHS">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<th>No</th>'+
                                            '<th>Graduate Year</th>'+
                                            '<th>Date Of Entry</th>'+
                                            '<th>SK Number</th>'+
                                            '<th>FileUpload</th>'+
                                            '<th>Update At</th>'+
                                            '<th>Update By</th>'+
                                            '<th>Action</th>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody></tbody>'+
                                '</table>';        
              selector.html(htmltable);
        },
        LoadAjaxData : function(){
                var data = {
                    action : 'read',
                };
                 var token = jwt_encode(data,'UAP)(*');
                 var recordTable = $('#TblSKMHS').DataTable({
                     "processing": true,
                     "serverSide": false,
                     "ajax":{
                         url : base_url_js+"secretariat-rectorate/master_data/crud_sk_mhs", // json datasource
                         ordering : false,
                         type: "post",  // method  , by default get
                         data : {token : token}                                    
                     },
                           'columnDefs': [
                              {
                                 'targets': 0,
                                 'searchable': false,
                                 'orderable': false,
                                 'className': 'dt-body-center',
                              },
                              {
                                 'targets': 4,
                                 'searchable': false,
                                 'orderable': false,
                                 'className': 'dt-body-center',
                                 'render': function (data, type, full, meta){
                                    var FileJson = jQuery.parseJSON(full[4]);
                                     var fileAhref =(full[4] == '' || full[4] == null || FileJson.length == 0) ? '' : '<a href = "'+base_url_js+'fileGetAny/rektorat-'+FileJson[0]+'" target="_blank" class = "Fileexist">File'+'</a>';
                                     return fileAhref;
                                 }
                              },   
                              {
                                 'targets': 7,
                                 'searchable': false,
                                 'orderable': false,
                                 'className': 'dt-body-center',
                                 'render': function (data, type, full, meta){
                                     var btnAction = '<div class="btn-group">' +
                                         '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                         '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
                                         '  </button>' +
                                         '  <ul class="dropdown-menu">' +
                                         '    <li><a href="javascript:void(0);" class="btnEdit" data-id="'+full[8]+'" data = "'+full[9]+'"><i class="fa fa fa-edit"></i> Edit</a></li>' +
                                         '    <li role="separator" class="divider"></li>' +
                                         '    <li><a href="javascript:void(0);" class="btnRemove" data-id="'+full[8]+'"><i class="fa fa fa-trash"></i> Remove</a></li>' +
                                         '  </ul>' +
                                         '</div>';
                                     return btnAction;
                                 }
                              },
                           ],
                     'createdRow': function( row, data, dataIndex ) {
                             
                     },
                     dom: 'l<"toolbar">frtip',
                     initComplete: function(){
                       
                    }  
                 });

                 oTable = recordTable;
        },
        ActionData : function(selector,action="add",ID=""){
            var form_data = new FormData();
            var data = {};
            $('.input').each(function(){
                var field = $(this).attr('name');
                if (field == 'TA') {
                   data.TA = $(this).find('option:selected').val();
                }
                else
                {
                    data[field] = $(this).val();
                }
            })
            var dataform = {
                ID : ID,
                data : data,
                action : action,
            };
            var token = jwt_encode(dataform,"UAP)(*");
            form_data.append('token',token);


            if ( $( '#'+'UploadFile').length ) {
                var UploadFile = $('#'+'UploadFile')[0].files;
                for(var count = 0; count<UploadFile.length; count++)
                {
                 form_data.append("FileUpload[]", UploadFile[count]);
                }
            }
            if (confirm('Are you sure ?')) {
                loading_button2(selector);
                var url = base_url_js + "secretariat-rectorate/master_data/crud_sk_mhs";
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
                            AppJQ.setDefaultInput();
                            end_loading_button2(selector);
                            oTable.ajax.reload( null, false );
                          },
                          error: function (data) {
                            toastr.error("Connection Error, Please try again", 'Error!!');
                            end_loading_button2(selector);
                            
                          }
                        })
            }
            
        },

        DeleteFile : function(selector,filePath,idtable,fieldwhere,table,field,typefield,delimiter){
            var li = selector.closest('li');
            var DeleteDb = {
                auth : 'Yes',
                detail : {
                    idtable : idtable,
                    fieldwhere : fieldwhere,
                    table : table,
                    field : field,
                    typefield : typefield,
                    delimiter : delimiter,
                },
            }

            if (confirm('Are you sure ?')) {
                 loading_button2(selector);
                 var url = base_url_js + 'rest2/__remove_file';
                 var data = {
                     filePath : filePath,
                     auth : 's3Cr3T-G4N',
                     DeleteDb :DeleteDb,
                 }

                 var token = jwt_encode(data,"UAP)(*");
                 $.post(url,{ token:token },function (resultJson) {
                     if (resultJson == 1) {
                         li.remove();
                         oTable.ajax.reload( null, false );
                     }
                     else{
                         toastr.error('', '!!!Failed');
                     }
                 }).fail(function() {
                   toastr.error('The Database connection error, please try again', 'Failed!!');
                 }).always(function() {
                     end_loading_button2(selector);
                 });
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

               if(fsize > 2000000) // 2mb
               {
                // msgStr += TheName + ' which file Number '+ no + ' Image File Size is very big<br>';
                msgStr += TheName + ' Image File Size is very big<br>';
                //toastr.error("Image File Size is very big", 'Failed!!');
                //return false;
               }
               
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
        },


    };

    // ---- Event --- //

    $(document).ready(function() {
        AppJQ.loaded();
    })

    $(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
       var selector = $(this);
       var action = selector.attr('action');
       var ID = selector.attr('data-id');
       // cek validation file
       var S_upload = $('#UploadFile');
       var cekFile =  AppJQ.validation_file(S_upload,'Upload File');
       if (cekFile) {
        AppJQ.ActionData(selector,action,ID);
       }
    })

    $(document).off('click', '.btnEdit').on('click', '.btnEdit',function(e) {
        var ID = $(this).attr('data-id');
        var Token = $(this).attr('data');
        var data = jwt_decode(Token);
        $(".input[name='TA'] option").filter(function() {
           //may want to use $.trim in here
           return $(this).val() == data.TA; 
         }).prop("selected", true);
        $('.input[name="Tgl_msk"]').val(data.Tgl_msk);
        $('.input[name="NoSK"]').val(data.NoSK);
        if (data.FileUpload != null && data.FileUpload != '') {
             var FileJSon = jQuery.parseJSON(data.FileUpload);
             if (FileJSon.length > 0) {
                html = '<li style = "margin-top : 4px;"><a href = "'+base_url_js+'fileGetAny/rektorat-'+FileJSon[0]+'" target="_blank" class = "Fileexist">File'+'</a>&nbsp<button class="btn-xs btn-default btn-delete btn-default-warning btn-custom btn-delete-file" filepath = "rektorat-'+FileJSon[0]+'" type="button" idtable = "'+ID+'" table = "db_rektorat.sk_tgl_msk" field = "FileUpload" typefield = "1" delimiter = "" fieldwhere = "ID"><i class="fa fa-trash" aria-hidden="true"></i></button></li>';
                $('.fileShow').html(html);
             }
        }

        $('#btnSave').attr('action','edit');
        $('#btnSave').attr('data-id',ID);
    })

    $(document).off('click', '.btn-delete-file').on('click', '.btn-delete-file',function(e) {
        var Sthis = $(this);
        var filePath = Sthis.attr('filepath');
        var idtable = Sthis.attr('idtable');
        var fieldwhere = Sthis.attr('fieldwhere');
        var table = Sthis.attr('table');
        var field = Sthis.attr('field');
        var typefield = Sthis.attr('typefield');
        var delimiter = Sthis.attr('delimiter');
        AppJQ.DeleteFile(Sthis,filePath,idtable,fieldwhere,table,field,typefield,delimiter);
    })

    $(document).off('click', '.btnRemove').on('click', '.btnRemove',function(e) {
        var ID = $(this).attr('data-id');
        var selector = $(this);
        AppJQ.ActionData(selector,'delete',ID);
    })
</script>