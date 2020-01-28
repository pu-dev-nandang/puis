<div class="panel panel-default">
    <div class="panel-heading">
      <div class="row">
        <div class="col-sm-6">
          <h4 class="panel-title">List</h4>
        </div>
        <div class="col-sm-6">
            <div class="pull-right">
              <a href="<?php echo base_url().'request-document-generator/Template/CategorySrt' ?>" class = "btn btn-sm btn-primary" >Category Document</a>
            </div>
        </div>
      </div>
    </div>
    <div class="panel-body" style="min-height: 100px;">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped" id="TblDocument">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Document Name</th>
                                <th>Document Alias</th>
                                <th>Document Template</th>
                                <th>Preview</th>
                                <th>Action</th>
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
    var S_Table_example__;
    var App_table_document = {
        LoadTable : function(){
           var recordTable = $('#TblDocument').DataTable({
               "processing": true,
               "serverSide": false,
               "ajax":{
                   url : base_url_js+"document-generator-action/__loadtableMaster", // json datasource
                   ordering : false,
                   type: "post",  // method  , by default get
                   data : function(token){
                         // Read values
                          var data = {
                                 Active : 1,
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
                    {
                       'targets': 3,
                       'searchable': false,
                       'orderable': false,
                       'className': 'dt-body-center',
                       'render': function (data, type, full, meta){
                           var btnAction = '<a href="'+full[3]+'" class="btn btn-primary" target="_blank"> View</a>';
                           return btnAction;
                       }
                    },
                    {
                       'targets': 4,
                       'searchable': false,
                       'orderable': false,
                       'className': 'dt-body-center',
                       'render': function (data, type, full, meta){
                           var btnAction = '<a href="javascript:void(0);" class="btn btn-success PreviewTable" data="'+full[6]+'"> Preview</a>';
                           return btnAction;
                       }
                    },
                    {
                       'targets': 5,
                       'searchable': false,
                       'orderable': false,
                       'className': 'dt-body-center',
                       'render': function (data, type, full, meta){
                           var btnAction = '<a href="javascript:void(0);" class="btn btn-danger btnRemoveDocument" data-id="'+full[5]+'"><i class="fa fa fa-trash"></i> </a> <a href="javascript:void(0);" class="btn btn-primary btnEditDepartment" data-id="'+full[5]+'" data="'+full[6]+'">Access Department </a>';
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
           recordTable.on( 'order.dt search.dt', function () {
                                      recordTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                          cell.innerHTML = i+1;
                                      } );
                                  } ).draw();
           oTable = recordTable;
        },

        PreviewTemplateTable : function(selector){
            var data = jwt_decode(selector.attr('data'));
            var config = jQuery.parseJSON( data['Config'] );
            var PathTemplate = data['PathTemplate'];
            var url = base_url_js+"document-generator-action/__preview_template_table";
            var data = {
                config : config,
                PathTemplate : PathTemplate,
            };
            var token = jwt_encode(data,'UAP)(*');
            loading_button2(selector);
            AjaxSubmitTemplate(url,token).then(function(response){
                if (response.status == 1) {
                    window.open(response.callback, '_blank');
                }
                else
                {
                    toastr.error('Something error,please try again');
                }
                end_loading_button2(selector,'Preview');
            }).fail(function(response){
               toastr.error('Connection error,please try again');
               end_loading_button2(selector,'Preview');
            })
            // console.log(config);
            // console.log(data);
        },

        RemoveDocument : function(selector){
            if (confirm('Are you sure ?')) {
                var url = base_url_js+"document-generator-action/__RemoveDocumentMaster";
                var data = {
                    ID : selector.attr('data-id'),
                };
                var token = jwt_encode(data,'UAP)(*');
                loading_button2(selector);
                AjaxSubmitTemplate(url,token).then(function(response){
                    if (response.status == 1) {
                        oTable.ajax.reload( null, false );
                    }
                    else
                    {
                        toastr.error('Something error,please try again');
                    }
                    end_loading_button2(selector,'<i class="fa fa fa-trash"></i>');
                }).fail(function(response){
                   toastr.error('Connection error,please try again');
                   end_loading_button2(selector,'<i class="fa fa fa-trash"></i>');
                })
            }

        },

        EditDepartment : function(selector){
            var html = '';
            var ID = selector.attr('data-id');
            var dataToken = jwt_decode(selector.attr('data'));
            // console.log(dataToken);return;
            html ='<div class = "row">'+
                    '<div class = "col-md-12">'+
                        '<table id="docEdit" class="table table-bordered display select" cellspacing="0" width="100%">'+
               '<thead>'+
                  '<tr>'+
                     '<th>Select &nbsp <input type="checkbox" name="select_all" value="1" id="docEdit-select-all"></th>'+
                     '<th>Code</th>'+
                     '<th>Departement</th>'+
                  '</tr>'+
               '</thead>'+
          '</table></div></div>';

            $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Select Department'+'</h4>');
            $('#GlobalModalLarge .modal-body').html(html);
            $('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>'+
                '<button type="button" id="ModalbtnSaveFormDepartment" class="btn btn-success" data-id = "'+ID+'">Save</button>');
            $('#GlobalModalLarge').modal({
                'show' : true,
                'backdrop' : 'static'
            });
            var url = base_url_js+'api/__getAllDepartementPU';
            $.get( url, function( dt ) {
                var table = $('#docEdit').DataTable({
                      "processing": true,
                      "serverSide": false,
                      "data" : dt,
                      'columnDefs': [
                          {
                             'targets': 0,
                             'searchable': false,
                             // 'orderable': false,
                             'className': 'dt-body-center',
                             'render': function (data, type, full, meta){
                                 var checked = '';
                                 var document_access_department = dataToken['document_access_department'];
                                 for (var i = 0; i < document_access_department.length; i++) {
                                   if (full.Code == document_access_department[i].Department) {
                                       checked = 'checked';
                                       break;
                                   }
                                 }
                                 return '<input type="checkbox" name="id[]" value="' + full.Code + '" dt = "'+full.Abbr+'" '+checked+'><div class ="hide">'+checked+'</div>';
                             }
                          },
                          {
                             'targets': 1,
                             'render': function (data, type, full, meta){
                                 return full.Abbr;
                             }
                          },
                          {
                             'targets': 2,
                             'render': function (data, type, full, meta){
                                 return full.Name2;
                             }
                          },
                      ],
                      'createdRow': function( row, data, dataIndex ) {
                            // console.log(data);
                      },
                      'order': [[0, 'Desc']]
                });

                S_Table_example__ = table;
            });
        },

        SaveEditDepartment : function(selector){
          var DepartmentArr = [];
          var ID = selector.attr('data-id');
          S_Table_example__.$('input[type="checkbox"]:checked').each(function(){
            var v = $(this).val();
            var n = $(this).attr('dt');
            var temp = {
              Code : v,
              Name : n,
            };

            DepartmentArr.push(temp);
          }); // exit each function


          var data = {
              DepartmentArr : DepartmentArr,
              ID : ID,
          };
          
          var token = jwt_encode(data,'UAP)(*');
          loading_button2(selector);
          var url = base_url_js+"document-generator-action/__save_edit_department_access";
          AjaxSubmitTemplate(url,token).then(function(response){
              if (response.status == 1) {
                 toastr.success('Saved'); 
                 location.reload();
              }
              else
              {
                  toastr.error('Something error,please try again');
                  end_loading_button2(selector,'Save');
              }
          }).fail(function(response){
             toastr.error('Connection error,please try again');
             end_loading_button2(selector,'Save');
          })

        },  

    };

    $(document).ready(function(){
        App_table_document.LoadTable();
    });

    $(document).off('click', '.PreviewTable').on('click', '.PreviewTable',function(e) {
       var itsme = $(this);
       App_table_document.PreviewTemplateTable(itsme);
    })

    $(document).off('click', '.btnRemoveDocument').on('click', '.btnRemoveDocument',function(e) {
       var itsme = $(this);
       App_table_document.RemoveDocument(itsme);
    })

    $(document).off('click', '.btnEditDepartment').on('click', '.btnEditDepartment',function(e) {
       var itsme = $(this);
       App_table_document.EditDepartment(itsme);
    })

    $(document).off('click', '#ModalbtnSaveFormDepartment').on('click', '#ModalbtnSaveFormDepartment',function(e) {
       var itsme = $(this);
       App_table_document.SaveEditDepartment(itsme);
    })

    // Handle click on "Select all" control
    $(document).off('click', '#docEdit-select-all').on('click', '#docEdit-select-all',function(e) {
       // Get all rows with search applied
       var rows = S_Table_example__.rows({ 'search': 'applied' }).nodes();
       // Check/uncheck checkboxes for all rows in the table
       $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });
    
</script>