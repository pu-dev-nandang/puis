<style>
    #tableTicket td:nth-child(1), #tableTicket td:nth-child(4){
        border-right: 1px solid #CCCCCC;
    }
    #tableTicket td:nth-child(3), #tableTicket td:nth-child(4){
        text-align: left;
    }
</style>

<div class="row" style="margin-top: 30px;">
    <div class="container-fluid">
        <div class="col-md-6 col-md-offset-3">
            <div class="well">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>Department</label>
                            <select class ="select2-select-00 full-width-fix" id ="SelectDepartmentID"></select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id ="SelectStatusTicketID"></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-centre" id="tableTicket">
            <thead>
            <tr>
                <th style="width: 1%;">No</th>
                <th style="width: 7%;">No Ticket</th>
                <th style="width: 10%;text-align: left;">Requested By</th>
                <th>Ticket</th>
                <th style="width: 5%;"><i class="fa fa-cog"></i></th>
                <th style="width: 10%;">Created Date</th>
                <th style="width: 5%;">Status</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>   
</div>
<script type="text/javascript">
    var oTable;
    var App_ticket_tikcet_list = {
        Loaded : function(){
            var selectorDepartment = $('#SelectDepartmentID');
            LoadSelectOptionDepartmentFiltered(selectorDepartment);
            var selectorStatus = $('#SelectStatusTicketID');
            LoadSelectOptionStatusTicket(selectorStatus);
            var firstLoad = setInterval(function () {
                var SelectDepartmentID = $('#SelectDepartmentID').val();
                var selectStatus = $('#SelectStatusTicketID').val();
                if(SelectDepartmentID!='' && SelectDepartmentID!=null && selectStatus != '' && selectStatus != null   ){
                    /*
                        LoadAction
                    */
                    App_ticket_tikcet_list.LoadTicketList();
                    clearInterval(firstLoad);
                }
            },1000);
            setTimeout(function () {
                clearInterval(firstLoad);
            },5000);
        },

        LoadTicketList : function(){
            $('#tableTicket tbody').empty();
            var table = $('#tableTicket').DataTable({
                "fixedHeader": true,
                "processing": true,
                "destroy": true,
                "serverSide": true,
              "lengthMenu": [[5,10], [5,10]],
                "iDisplayLength" : 5,
                "ordering" : false,
              "language": {
                  "searchPlaceholder": "Search",
              },
                "ajax":{
                    url : base_url_js+"rest_ticketing/__LoadTicketList"+'?apikey='+Apikey, // json datasource
                    ordering : false,
                    type: "post",  // method  , by default get
                    beforeSend: function (xhr)
                    {
                      xhr.setRequestHeader("Hjwtkey",Hjwtkey);
                    },
                   data : function(token){
                           // Read values
                            var TicketStatus = $('#SelectStatusTicketID option:selected').val();
                            var SelectDepartmentID = $('#SelectDepartmentID option:selected').val();
                            var data = {
                                auth : 's3Cr3T-G4N',
                                TicketStatus : TicketStatus,
                                DepartmentID : SelectDepartmentID,
                                NIP : sessionNIP,
                            };
                            var get_token = jwt_encode(data,"UAP)(*");
                            token.token = get_token;
                    },
                    error: function(){  // error handling
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
                    }
                },
                'createdRow': function( row, data, dataIndex ) {
                    var htmlTicket = '';
                    htmlTicket += '<h3>'+data[3]+'</h3>'+
                                    '<p>'+data[4]+'</p>'
                                        ;
                     $( row ).find('td:eq(3)').html(htmlTicket);
                     var htmlAction = '';
                     htmlAction += '<div class="btn-group">'+
                        '<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                            '<i class="fa fa-edit"></i> <span class="caret"></span>'+
                        '</button>'+
                        '<ul class="dropdown-menu">'+
                            '<li><a href="#">Detail</a></li>'+
                            '<li role="separator" class="divider"></li>'+
                           ' <li><a href="#">Print</a></li>'+
                        '</ul>'+
                    '</div>';
                     $( row ).find('td:eq(4)').html(htmlAction);
                     $( row ).find('td:eq(5)').html(data[6]);
                     $( row ).find('td:eq(6)').html(data[7]);
                },
                dom: 'l<"toolbar">frtip',
                "initComplete": function(settings, json) {

                }
            });

            oTable = table;
        },
    };

    $(document).ready(function(){
        App_ticket_tikcet_list.Loaded();
    })

    $(document).off('change', '#SelectDepartmentID,#SelectStatusTicketID').on('change', '#SelectDepartmentID,#SelectStatusTicketID',function(e) {
       oTable.ajax.reload( null, false );
    })
</script>