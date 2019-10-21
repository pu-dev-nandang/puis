<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Data Live</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;">
        <table class="table" id = "Tbl_routes_live">
            <thead>
                <tr>
                    <td style="width: 8%">No</td>
                    <td>Slug</td>
                    <td>Controller</td>
                    <td>Type</td>
                    <td>Updated by</td>
                    <td>Updated at</td>
                    <td>Action</td>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script type="text/javascript">
    var App_table_routes_live = {
        LoadData : function(){
             var recordTable = $('#Tbl_routes_live').DataTable({ 
                 "processing": true,
                 "serverSide": false,
                 "pageLength": 10,
                 "ajax":{
                     url : base_url_js+"it/console-developer/routes/submit", // json datasource
                     ordering : false,
                     type: "post",  // method  , by default get
                     // data : {token : token} 
                     data: function(token){
                               // Read values
                                var data = {
                                       action : 'read',
                                       server : 'live',
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
                         'targets': 6,
                         'searchable': false,
                         'orderable': false,
                         'className': 'dt-body-center',
                         'render': function (data, type, full, meta){
                             var btnAction = '<div class="btn-group">' +
                                 '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                 '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
                                 '  </button>' +
                                 '  <ul class="dropdown-menu" style="min-width:50px !important;">' +
                                 '    <li><a href="javascript:void(0);" class="btnEdit" data-id="'+full[8]+'" data = "'+full[9]+'" server = "live"><i class="fa fa fa-edit"></i></a></li>' +
                                 '    <li role="separator" class="divider"></li>' +
                                 '    <li><a href="javascript:void(0);" class="btnRemove" data-id="'+full[8]+'" server = "live"><i class="fa fa fa-trash"></i></a></li>' +
                                 '  </ul>' +
                                 '</div>';
                             return btnAction;
                         }
                      },
                   ],
                 'createdRow': function( row, data, dataIndex ) {
                         
                 },
                 "order": [[ 5, "desc" ]],
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
        }
    };

    $(document).ready(function(){
        App_table_routes_live.LoadData();
    });

    $(document).off('click', '.btnEdit').on('click', '.btnEdit',function(e) {
        var ID = $(this).attr('data-id');
        var server = $(this).attr('server');
        var Token = $(this).attr('data');
        var data = jwt_decode(Token);
        for(var key in data) {
            if (key == 'Type') {
                $(".input[name='Type'] option").filter(function() {
                   //may want to use $.trim in here
                   return $(this).val() == data.Type; 
                 }).prop("selected", true);
            }
            else
            {
                $('.input[name="'+key+'"]').val(data[key]);
            }
        }
        
        $('#btnSave').attr('action','edit');
        $('#btnSave').attr('data-id',ID);
        $('#btnSave').attr('server',server);
    })
</script>