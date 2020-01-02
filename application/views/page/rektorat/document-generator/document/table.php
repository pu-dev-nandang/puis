<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">List</h4>
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
                           var btnAction = '<a href="javascript:void(0);" class="btn btn-danger btnRemove" data-id="'+full[6]+'"><i class="fa fa fa-trash"></i> </a>';
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
            console.log(config);
            console.log(data);
        }  

    };

    $(document).ready(function(){
        App_table_document.LoadTable();
    });

    $(document).off('click', '.PreviewTable').on('click', '.PreviewTable',function(e) {
       var itsme = $(this);
       App_table_document.PreviewTemplateTable(itsme);

    })
    
</script>