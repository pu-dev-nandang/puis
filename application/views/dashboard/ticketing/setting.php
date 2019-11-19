<div class="row" style="margin-top: 30px;">
    <div class="container-fluid">
        <div class="col-md-8">
            <div class="widget box">
                <div class="widget-header" id="widgetSmt0">
                    <h4><i class="icon-reorder"></i> Category</h4>
                    <?php if (isset($action) && $action == 'write'): ?>
                        <div class="toolbar no-padding">
                            <div class="btn-group">
                                <span data-smt="1" class="btn btn-xs btn-add-category">
                                    Add Category
                                </span>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
                <div class="widget-content no-padding ">
                    <table class="table table-striped" id = "Tblticket_setting_category">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Description</th>
                            <th>Updated By</th>
                            <?php if (isset($action)): ?>
                                <?php if ($action == 'write'): ?>
                                    <th>Action</th>
                                <?php endif ?>  
                            <?php endif ?>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="widget box">
                <div class="widget-header" id="widgetSmt0">
                    <h4><i class="icon-reorder"></i> Admin</h4>
                    <?php if (isset($action) && $action == 'write'): ?>
                    <div class="toolbar no-padding">
                        <div class="btn-group">
                            <span data-smt="1" class="btn btn-xs btn-add-admin">
                                Add Admin
                            </span>
                        </div>
                    </div>
                    <?php endif ?>
                </div>
                <div class="widget-content no-padding ">
                    <table class="table table-striped" id="Tblticket_setting_admin">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <?php if (isset($action) && $action == 'write'): ?>
                                <th>Action</th>
                            <?php endif ?>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var oTable;
    var oTable2;
    var App_ticketing_setting_category = {
        LoadTable : function(){
             var recordTable = $('#Tblticket_setting_category').DataTable({
                 "processing": true,
                 "serverSide": false,
                 "ajax":{
                     url : base_url_js+"rest_ticketing/__CRUDCategory?apikey="+Apikey, // json datasource
                     ordering : false,
                     type: "post",  // method  , by default get
                     beforeSend: function (xhr)
                     {
                        xhr.setRequestHeader("Hjwtkey",Hjwtkey);
                     },
                     data : function(token){
                           // Read values
                            var data = {
                                   action : 'read',
                                   DepartmentID : DepartmentID,
                                   auth : 's3Cr3T-G4N',
                               };
                           // Append to data
                           token.token = jwt_encode(data,'UAP)(*');
                     }                                                                     
                  },
                   'columnDefs': [
                      {
                         'targets': 0,
                         'searchable': false,
                         'orderable': false,
                         'className': 'dt-body-center',
                      },
                      <?php if (isset($action)): ?>
                      <?php if ($action == 'write'): ?>
                        {
                           'targets': 3,
                           'searchable': false,
                           'orderable': false,
                           'className': 'dt-body-center',
                           'render': function (data, type, full, meta){
                               var btnAction = '<div class="btn-group">' +
                                   '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                   '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
                                   '  </button>' +
                                   '  <ul class="dropdown-menu">' +
                                   '    <li><a href="javascript:void(0);" class="btnEdit" data-id="'+full[3]+'" data = "'+full[9]+'"><i class="fa fa fa-edit"></i> Edit</a></li>' +
                                   '    <li role="separator" class="divider"></li>' +
                                   '    <li><a href="javascript:void(0);" class="btnRemove" data-id="'+full[3]+'"><i class="fa fa fa-trash"></i> Remove</a></li>' +
                                   '  </ul>' +
                                   '</div>';
                               return btnAction;
                           }
                        },
                      <?php endif ?>
                      <?php endif ?>
                      
                   ],
                 'createdRow': function( row, data, dataIndex ) {
                         
                 },
                 dom: 'l<"toolbar">frtip',
                 initComplete: function(){
                   
                }  
             });

             recordTable.on( 'order.dt search.dt', function () {
                                        recordTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                            cell.innerHTML = i+1;
                                        } );
                                    } ).draw();

             oTable = recordTable;
        },

        ModalForm : function(judul = 'Form Category',action='add',ID='',data=[]){
            var html = '';
            html = '<div class="row">'+
                        '<div class="col-md-12">'+
                            '<div class = "row">'+
                                '<div class="col-sm-3">'+
                                    '<label class="control-label">Descriptions</label>'+
                                '</div>'+
                                '<div class="col-sm-6">'+
                                     '<textarea class="form-control input" name="Descriptions"></textarea>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>';                    

            $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+judul+'</h4>');
            $('#GlobalModalLarge .modal-body').html(html);
            $('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>'+
                '<button type="button" id="ModalbtnSaveForm" class="btn btn-success" action = "'+action+'" data-id = "'+ID+'">Save</button>');
            $('#GlobalModalLarge').modal({
                'show' : true,
                'backdrop' : 'static'
            });

            if (action=='edit') {
                for(var key in data) {
                    $('.input[name="'+key+'"]').val(data[key]);
                }
            }
        },

        ActionData : function(selector,action="add",ID=""){
            var data = {};
            $('.input').each(function(){
                var field = $(this).attr('name');
                data[field] = $(this).val();
            })
            data['DepartmentID'] = DepartmentID;
            data['UpdatedBy'] = sessionNIP;
            var dataform = {
                action : action,
                data : data,
                ID : ID,
                auth : 's3Cr3T-G4N',
            };
            // cek validation jika tidak delete
            var validation = (action == 'delete') ? true : App_ticketing_setting_category.Validation(data);
            if (validation) {
                if (confirm('Are you sure ?')) {
                    loading_button2(selector);
                    var url = base_url_js+"rest_ticketing/__CRUDCategory?apikey="+Apikey;
                    var token = jwt_encode(dataform,'UAP)(*');
                    var form_data = new FormData();
                    form_data.append('token',token);
                    $.ajax({
                      type:"POST",
                      url:url,
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
                        if (data.status == 1) {
                            end_loading_button2(selector);
                            oTable.ajax.reload( null, false );
                            $('#GlobalModalLarge').modal('hide');
                        }
                        else
                        {
                            toastr.error(data.msg);
                            end_loading_button2(selector);
                        }
                        
                      },  
                      error: function (data) {
                        toastr.error("Connection Error, Please try again", 'Error!!');
                        end_loading_button2(selector); 
                      }
                    })
                }
            }
        },

        Validation : function(arr){
            var toatString = "";
            var result = "";
            for(key in arr){
               switch(key)
               {
                case  "Descriptions" :
                      result = Validation_required(arr[key],key);
                      if (result['status'] == 0) {
                        toatString += result['messages'] + "<br>";
                      }
                      break;
               }
            }

            if (toatString != "") {
              toastr.error(toatString, 'Failed!!');
              return false;
            }
            return true
        },

        Loaded : function(){
           this.LoadTable();
        },
    };

    var App_ticketing_setting_admin = {
        LoadTable : function(){
             var recordTable = $('#Tblticket_setting_admin').DataTable({
                 "processing": true,
                 "serverSide": false,
                 "ajax":{
                     url : base_url_js+"rest_ticketing/__CRUDAdmin?apikey="+Apikey, // json datasource
                     ordering : false,
                     type: "post",  // method  , by default get
                     beforeSend: function (xhr)
                     {
                        xhr.setRequestHeader("Hjwtkey",Hjwtkey);
                     },
                     data : function(token){
                           // Read values
                            var data = {
                                   action : 'read',
                                   DepartmentID : DepartmentID,
                                   auth : 's3Cr3T-G4N',
                               };
                           // Append to data
                           token.token = jwt_encode(data,'UAP)(*');
                     }                                                                     
                  },
                   'columnDefs': [
                      {
                         'targets': 0,
                         'searchable': false,
                         'orderable': false,
                         'className': 'dt-body-center',
                      },
                      <?php if (isset($action) && $action == 'write'): ?>
                        {
                           'targets': 2,
                           'searchable': false,
                           'orderable': false,
                           'className': 'dt-body-center',
                           'render': function (data, type, full, meta){
                               var btnAction = '<div class="btn-group">' +
                                   '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                   '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
                                   '  </button>' +
                                   '  <ul class="dropdown-menu">' +
                                   '    <li><a href="javascript:void(0);" class="btnEditAdmin" data-id="'+full[2]+'" data = "'+full[10]+'"><i class="fa fa fa-edit"></i> Edit</a></li>' +
                                   '    <li role="separator" class="divider"></li>' +
                                   '    <li><a href="javascript:void(0);" class="btnRemoveAdmin" data-id="'+full[2]+'"><i class="fa fa fa-trash"></i> Remove</a></li>' +
                                   '  </ul>' +
                                   '</div>';
                               return btnAction;
                           }
                        },
                      <?php endif ?>
                      
                   ],
                 'createdRow': function( row, data, dataIndex ) {
                         
                 },
                 dom: 'l<"toolbar">frtip',
                 initComplete: function(){
                   
                }  
             });

             recordTable.on( 'order.dt search.dt', function () {
                                        recordTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                            cell.innerHTML = i+1;
                                        } );
                                    } ).draw();

             oTable2 = recordTable;
        },

        ModalForm : function(judul = "Form Admin",action='add',ID='',data=[]){
            var html = '';
            html = '<div class="row">'+
                        '<div class="col-md-12">'+
                            '<div class = "row">'+
                                '<div class="col-sm-3">'+
                                    '<label class="control-label">Employee</label>'+
                                '</div>'+
                                '<div class="col-sm-6">'+
                                     '<input type = "text" class = "form-control showAutoComplete" placeholder = "Input NIP or Name">'+
                                     '<input type = "hidden" class = "form-control input" name = "NIP">'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>';

            $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+judul+'</h4>');
            $('#GlobalModalLarge .modal-body').html(html);
            $('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>'+
                '<button type="button" id="ModalbtnSaveFormAdmin" class="btn btn-success" action = "'+action+'" data-id = "'+ID+'">Save</button>');
            $('#GlobalModalLarge').modal({
                'show' : true,
                'backdrop' : 'static'
            });

            if (action == 'edit') {
                for(var key in data) {
                    $('.input[name="'+key+'"]').val(data[key]);
                    if (key == 'NIP') {
                       $('.input[name="'+key+'"]').attr('selectedtext',data['NameAdmin']);
                       $('.showAutoComplete').val(data['NameAdmin']);
                    }
                }
            }

            $(".showAutoComplete").autocomplete({
              minLength: 4,
              select: function (event, ui) {
                event.preventDefault();
                var selectedObj = ui.item;
                $('.input[name="NIP"]').val(selectedObj.value);
                $('.input[name="NIP"]').attr('selectedtext',selectedObj.label);
                $('.showAutoComplete').val(selectedObj.label);
              },
              source:
              function(req, add)
              {
                var url = base_url_js+"rest_ticketing/__AutocompleteEmployees?apikey="+Apikey;
                var search = $(".showAutoComplete").val();
                var data = {
                            DepartmentID : DepartmentID,
                            auth : 's3Cr3T-G4N',
                            search : search,
                            };
                var token = jwt_encode(data,"UAP)(*");
                $.ajax({
                  type: 'POST',
                  url: url,
                  data: {token:token},
                  dataType: "json",
                  beforeSend: function (xhr)
                  {
                     xhr.setRequestHeader("Hjwtkey",Hjwtkey);
                  },
                  success:function(obj)
                  {
                    add(obj.message) 
                  }
                });          
              } 
            })

            $( ".showAutoComplete" ).autocomplete( "option", "appendTo", "#GlobalModalLarge .modal-body" );

        },

        ActionData : function(selector,action="add",ID=""){
            var data = {};
            $('.input').each(function(){
                var field = $(this).attr('name');
                data[field] = $(this).val();
            })
            data['DepartmentID'] = DepartmentID;
            data['UpdatedBy'] = sessionNIP;
            var dataform = {
                action : action,
                data : data,
                ID : ID,
                auth : 's3Cr3T-G4N',
            };
            // cek validation jika tidak delete
            var validation = (action == 'delete') ? true : App_ticketing_setting_admin.Validation(data);
            if (validation) {
                if (confirm('Are you sure ?')) {
                    loading_button2(selector);
                    var url = base_url_js+"rest_ticketing/__CRUDAdmin?apikey="+Apikey;
                    var token = jwt_encode(dataform,'UAP)(*');
                    var form_data = new FormData();
                    form_data.append('token',token);
                    $.ajax({
                      type:"POST",
                      url:url,
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
                        if (data.status == 1) {
                            end_loading_button2(selector);
                            oTable2.ajax.reload( null, false );
                            $('#GlobalModalLarge').modal('hide');
                        }
                        else
                        {
                            toastr.error(data.msg);
                            end_loading_button2(selector);
                        }
                        
                      },  
                      error: function (data) {
                        toastr.error("Connection Error, Please try again", 'Error!!');
                        end_loading_button2(selector); 
                      }
                    })
                }
            }
        },

        Validation : function(arr){
            var toatString = "";
            var result = "";
            for(key in arr){
               switch(key)
               {
                case  "NIP" :
                      result = Validation_required(arr[key],key);
                      if (result['status'] == 0) {
                        toatString += result['messages'] + "<br>";
                      }
                      else
                      {
                        var selector = $('.input[name="'+key+'"]');
                        var selectedtext = selector.attr('selectedtext');
                        var showAutoCompleteVal = $('.showAutoComplete').val();
                        if (showAutoCompleteVal != selectedtext) {
                            selector.val('');
                            $('.showAutoComplete').val('');
                            toatString += 'Something wrong, please try again' + "<br>";
                        }

                      }
                      break;
               }
            }

            if (toatString != "") {
              toastr.error(toatString, 'Failed!!');
              return false;
            }
            return true
        },

        Loaded : function(){
           this.LoadTable();
        },
    };

$(document).ready(function(){
    App_ticketing_setting_category.Loaded();
    App_ticketing_setting_admin.Loaded();
});

$(document).off('click', '.btn-add-category').on('click', '.btn-add-category',function(e) {
    App_ticketing_setting_category.ModalForm();
})

$(document).off('click', '#ModalbtnSaveForm').on('click', '#ModalbtnSaveForm',function(e) {
    var selector = $(this);
    var action = $(this).attr('action');
    var data_id = $(this).attr('data-id');
    App_ticketing_setting_category.ActionData(selector,action,data_id);
})

$(document).off('click','.btnRemove').on('click','.btnRemove',function(e){
    var selector = $(this);
    var data_id = $(this).attr('data-id');
    App_ticketing_setting_category.ActionData(selector,'delete',data_id);
})

$(document).off('click','.btnEdit').on('click','.btnEdit',function(e){
    var ID = $(this).attr('data-id');
    var Token = $(this).attr('data');
    var data = jwt_decode(Token);
   App_ticketing_setting_category.ModalForm('Form Category','edit',ID,data);
})

$(document).off('click', '.btn-add-admin').on('click', '.btn-add-admin',function(e) {
    App_ticketing_setting_admin.ModalForm();
})

$(document).off('click', '#ModalbtnSaveFormAdmin').on('click', '#ModalbtnSaveFormAdmin',function(e) {
    var selector = $(this);
    var action = $(this).attr('action');
    var data_id = $(this).attr('data-id');
    App_ticketing_setting_admin.ActionData(selector,action,data_id);
})

$(document).off('click','.btnRemoveAdmin').on('click','.btnRemoveAdmin',function(e){
    var selector = $(this);
    var data_id = $(this).attr('data-id');
    App_ticketing_setting_admin.ActionData(selector,'delete',data_id);
})

$(document).off('click','.btnEditAdmin').on('click','.btnEditAdmin',function(e){
    var ID = $(this).attr('data-id');
    var Token = $(this).attr('data');
    var data = jwt_decode(Token);
    App_ticketing_setting_admin.ModalForm('Form Admin','edit',ID,data);
})

</script>