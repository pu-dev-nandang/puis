<style>
#tableTicket td:nth-child(1),
#tableTicket td:nth-child(4) {
    border-right: 1px solid #CCCCCC;
}

#tableTicket td:nth-child(3),
#tableTicket td:nth-child(4) {
    text-align: left;
}

.ticket-number-table {
    right: 0px;
    background: #607D8B;
    padding: 0px 10px 1px 10px;
    color: #fff;
    font-size: 11px;
    border-bottom-left-radius: 7px;
    border-top-left-radius: 7px;
}
</style>
<?php $this->load->view('dashboard/ticketing/LoadCssTicketToday') ?>
<div class="row" style="margin-top: 5px;">
    <div class="col-xs-8">
        <div class="thumbnail">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row" style="margin-top: 30px;">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="well">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Department</label>
                                                    <select class="select2-select-00 full-width-fix" id="SelectDepartmentID"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select class="form-control" id="SelectStatusTicketID"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>For</label>
                                                    <select class="form-control" id="FilterFor">
                                                        <option selected value="%">--All--</option>
                                                        <option value="1">--Worker--</option>
                                                        <option value="99">--Outgoing--</option>
                                                        <option value="-99">--Incoming--</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="thumbnail" style="padding: 10px;">
                                                    <b>Status : </b><i class="fa fa-circle" style="color:#d8ea8e;"></i> All Worker have been done.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-centre" id="tableTicket">
                                    <thead>
                                        <tr>
                                            <th style="width: 1%;">No</th>
                                            <th style="width: 7%;">No Ticket</th>
                                            <th style="width: 15%;text-align: left;">Requested By</th>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-4">
        <div class="thumbnail">
            <div class="row" style="padding: 10px;">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-12">
                            <div style="padding: 10px;text-align: center;">
                                <h4 style="color: green;"><u>Incoming Ticket All</u></h4>
                            </div>
                            <div class="well">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Month</label>
                                            <select class="form-control" id = "OpMonth"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Year</label>
                                            <select class="form-control" id = "OpYear"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-md-offset-4" style="text-align: center;">
                                        <div class="form-group">
                                            <label>Select View</label>
                                            <select class="form-control" id = "OpShowAll">
                                                <option value="1">Table</option>
                                                <option value="2" selected>Graph</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-primary" style="border-color: #42a4ca;">
                                <div class="panel-heading clearfix" style="background-color: #42a4ca;border-color: #42a4ca;">
                                    <h4 class="panel-title pull-left" style="padding-top: 7.5px;"></h4>
                                </div>
                                <div class="panel-body" id = "PageDashboardAll">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="padding: 10px;">
                <div style="padding: 10px;text-align: center;">
                    <h4 style="color: green;"><u>Incoming Ticket Today</u></h4>
                </div>
                <div class="col-md-6 col-md-offset-3">
                    <div class="well">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label>Select View</label>
                                    <select class="form-control" id = "OpShowToday">
                                        <option value="1">Table</option>
                                        <option value="2" selected>Graph</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12" style="padding: 10px;">
                <div class="panel panel-primary" style="border-color: #42a4ca;">
                    <div class="panel-heading clearfix" style="background-color: #42a4ca;border-color: #42a4ca;">
                        <h4 class="panel-title pull-left" style="padding-top: 7.5px;"></h4>
                    </div>
                    <div class="panel-body">
                        <div id="PageToday">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var oTable;

var App_ticket_tikcet_list = {
    Loaded: function() {
        var selectorDepartment = $('#SelectDepartmentID');
        LoadSelectOptionDepartmentFiltered(selectorDepartment);
        var selectorStatus = $('#SelectStatusTicketID');
        LoadSelectOptionStatusTicket(selectorStatus);
        var firstLoad = setInterval(function() {
            var SelectDepartmentID = $('#SelectDepartmentID').val();
            var selectStatus = $('#SelectStatusTicketID').val();
            if (SelectDepartmentID != '' && SelectDepartmentID != null && selectStatus != '' &&
                selectStatus != null) {
                /*
                    LoadAction
                */
                App_ticket_tikcet_list.LoadTicketList();
                clearInterval(firstLoad);
            }
        }, 1000);
        setTimeout(function() {
            clearInterval(firstLoad);
        }, 5000);
    },

    LoadTicketList: function() {
        $('#tableTicket tbody').empty();
        var table = $('#tableTicket').DataTable({
            "fixedHeader": true,
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "lengthMenu": [
                [5, 10],
                [5, 10]
            ],
            "iDisplayLength": 5,
            "ordering": false,
            "language": {
                "searchPlaceholder": "Search",
            },
            "ajax": {
                url: base_url_js + "rest_ticketing/__LoadTicketList" + '?apikey=' +
                    Apikey, // json datasource
                ordering: false,
                type: "post", // method  , by default get
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Hjwtkey", Hjwtkey);
                },
                data: function(token) {
                    // Read values
                    var TicketStatus = $('#SelectStatusTicketID option:selected').val();
                    var SelectDepartmentID = $('#SelectDepartmentID option:selected').val();
                    var FilterFor = $('#FilterFor option:selected').val();
                    var data = {
                        auth: 's3Cr3T-G4N',
                        TicketStatus: TicketStatus,
                        DepartmentID: SelectDepartmentID,
                        NIP: sessionNIP,
                        FilterFor: FilterFor,
                    };
                    var get_token = jwt_encode(data, "UAP)(*");
                    token.token = get_token;
                },
                error: function() { // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append(
                        '<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
                    );
                    $("#employee-grid_processing").css("display", "none");
                }
            },
            'createdRow': function(row, data, dataIndex) {
                var TicketStatus = $('#SelectStatusTicketID option:selected').val();
                var SelectDepartmentID = $('#SelectDepartmentID option:selected').val();
                var stylerow = (App_ticket_tikcet_list.cekCloseWorker(jwt_decode(data[9]),SelectDepartmentID) &&
                    TicketStatus == '2') ? 'background-color: #d8ea8e;' : '';
                $(row).attr('style', stylerow);

                var htmlNoticket = '<div class = "ticket-number-table">' + data[1] + '</div>';
                $(row).find('td:eq(1)').html(htmlNoticket);

                var htmlRequestedBy = '<div class = "row">';
                htmlRequestedBy +=
                    '<div class ="col-xs-1"><i class="fa fa-user margin-right"></i>' +
                    '</div>' +
                    '<div class ="col-xs-10"><b>' + data[2] + '<br/>' + ' from ' + '<br/>' + data[
                        12] + '</b>' + '</div>';
                htmlRequestedBy += '</div>';
                $(row).find('td:eq(2)').html(htmlRequestedBy);

                var htmlTicket = '';
                var FileUpload = (data[10] != null && data[10] != undefined && data[10] != '') ?
                    '<p><a href= "' + data[10] + '" target="_blank">Files Upload<a></p>' : '';
                htmlTicket += '<h3 style = "margin-top:0px;">' + '<b>' + data[3] + '</b>' +
                    '</h3>' +
                    '<p>' + nl2br(data[4]) + '</p>' +
                    FileUpload;
                $(row).find('td:eq(3)').html(htmlTicket);

                var htmlAction = '';
                var DetailAction = '<a href="javascript:void(0)" class ="ModalReadMore_list" token = "' +
                    data[9] + '" >Detail</a>';
                var setAction = '';
                var SelectDepartmentID = $('#SelectDepartmentID option:selected').val();
                var EncodeDepartment = jwt_encode(SelectDepartmentID, 'UAP)(*');
                if (data[8] == 1 && data[11] == 'write') {
                    var hrefActionTicket = base_url_js + 'ticket' + '/set_action_first/' + data[1] +
                        '/' + EncodeDepartment;
                    setAction = '<a href="' + hrefActionTicket + '">Set Action</a>';
                } else if (data[8] == 2 && data[11] == 'write') {
                    var cekPageAction = (App_ticket_tikcet_list.cekPageAction(jwt_decode(data[9]),SelectDepartmentID));
                        if (cekPageAction) {
                            var hrefActionTicket = base_url_js + 'ticket' + '/set_action_progress/' + data[
                                1] + '/' + EncodeDepartment
                            setAction = '<a href="' + hrefActionTicket + '">Set Action</a>';
                        }

                }
                htmlAction += '<div class="btn-group">' +
                    '<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                    '<i class="fa fa-edit"></i> <span class="caret"></span>' +
                    '</button>' +
                    '<ul class="dropdown-menu">' +
                    '<li>' + DetailAction + '</li>' +
                    '<li role="separator" class="divider"></li>' +
                    ' <li>' + setAction + '</li>' +
                    '</ul>' +
                    '</div>';
                $(row).find('td:eq(4)').html(htmlAction);

                $(row).find('td:eq(5)').html('<label class="text-primary">' + data[6] + '</label>');

                var styleSt = '';
                if (data[7] == 'Close' || data[7] == 'Has Given Rate') {
                    styleSt = 'style = "color:green;"';
                }
                $(row).find('td:eq(6)').html('<span ' + styleSt + '>' + data[7] + '</span>');
            },
            dom: 'l<"toolbar">frtip',
            "initComplete": function(settings, json) {

            }
        });

        oTable = table;
    },

    cekPageAction(data,SelectDepartmentID){
        var data_received = data.data_received;
        var bool = false;
        for (let index = 0; index < data_received.length; index++) {
            var DataReceived_Details = data_received[index].DataReceived_Details;
            if (data_received[index].DepartmentReceivedID==SelectDepartmentID && data_received[index].ReceivedStatus == "0") {
                bool = true;
                break;
            }
        }
        return bool;
    },

    cekCloseWorker: function(data,SelectDepartmentID) {
        var data_received = data.data_received;
        var bool = true;
        for (let index = 0; index < data_received.length; index++) {
            var DataReceived_Details = data_received[index].DataReceived_Details;
            if (DataReceived_Details.length == 0 && data_received[index].DepartmentReceivedID==SelectDepartmentID) {
                bool = false;
            }
            else
            {
                if (DataReceived_Details.length > 0 && data_received[index].DepartmentReceivedID==SelectDepartmentID) {
                    for (let j = 0; j < DataReceived_Details.length; j++) {
                        if (DataReceived_Details[j].Status == "1") {
                            bool = false;
                            break;
                        }

                    }
                }

            }

            if (!bool) {
                break;
            }
        }
        return bool;

    }


};

$(document).ready(function() {
    App_ticket_tikcet_list.Loaded();
})

$(document).off('change', '#FilterFor').on('change',
    '#FilterFor',
    function(e) {
        oTable.ajax.reload(null, false);
})

$(document).off('change', '#SelectStatusTicketID').on('change',
    '#SelectStatusTicketID',
    function(e) {
      var v = $('#SelectStatusTicketID option:selected').val();
      if (v ==1) {
        $('#FilterFor').find('option[value="1"]').prop('disabled',true);
        $('#FilterFor').find('option[value="99"]').prop('disabled',true);
      }
      else {
        $('#FilterFor').find('option[value="1"]').prop('disabled',false);
      $('#FilterFor').find('option[value="99"]').prop('disabled',false);
      }

        oTable.ajax.reload(null, false);
})

$(document).off('click', '.ModalReadMore_list').on('click', '.ModalReadMore_list', function(e) {
    var token = $(this).attr('token');
    AppModalDetailTicket.ModalReadMore('', '', token);
})
</script>

<!-- Graph ticket -->
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/sparkline/jquery.sparkline.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.tooltip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.time.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.orderBars.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.pie.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.selection.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.growraf.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

<script type="text/javascript" src="<?php echo base_url('js/ticketing/Class_ticketing_dashboard.js'); ?>"></script>
<script type="text/javascript">
    let App_ticketing_dashboard;
    $(document).ready(function(e){
        App_ticketing_dashboard = new Class_ticketing_dashboard();
        let PageToday = $('#PageToday');
        let selectorTableAll = $('#tableDashboardAll').find('table');
        let GraphDashboardAll = $('#GraphDashboardAll');
        let selectorMonth = $('#OpMonth');
        let selectorYear = $('#OpYear');
        let selectorShowAll = $('#OpShowAll');
        let selectorShowToday = $('#OpShowToday');
        let PageDashboardAll = $('#PageDashboardAll');
        App_ticketing_dashboard.LoadDefault(selectorMonth,selectorYear,selectorShowAll,selectorShowToday,PageToday,PageDashboardAll);
        // console.log(moment().format('YYYY-MM-DD'));
    })


    $(document).off('change','#OpMonth,#OpYear').on('change','#OpMonth,#OpYear',function(e){
        if ($('#PageDashboardAll').find('.dataTables_wrapper').length) {
            App_ticketing_dashboard.TableAll.ajax.reload( null, false );
        }
        else
        {
            let selectorMonth = $('#OpMonth');
            let selectorYear = $('#OpYear');
            let selectorShowAll = $('#OpShowAll');
            let PageDashboardAll = $('#PageDashboardAll');
            App_ticketing_dashboard.PageDashboardAll(selectorMonth,selectorYear,selectorShowAll,PageDashboardAll);
        }
        
    })

    $(document).off('click','.aHrefDetailAll').on('click','.aHrefDetailAll',function(e){
        let action = $(this).attr('action');
        let dataDecode = jwt_decode($(this).attr('data'));
        let selectorPage = $('#PageDashboardAll');
        let valueText = $(this).text();
        let DeptText = $(this).closest('tr').find('td:eq(1)').text();
        let pageSet = 'All';
        App_ticketing_dashboard.pageDetailAll(selectorPage,action,dataDecode,valueText,DeptText,pageSet);
    })

    $(document).off('click','.aHrefDetailToday').on('click','.aHrefDetailToday',function(e){
        let action = $(this).attr('action');
        let dataDecode = jwt_decode($(this).attr('data'));
        let selectorPage = $('#PageToday');
        let valueText = $(this).text();
        let DeptText = $(this).closest('tr').find('td:eq(1)').text();
        let pageSet = 'Today';
        App_ticketing_dashboard.pageDetailAll(selectorPage,action,dataDecode,valueText,DeptText,pageSet);
    })

    $(document).off('click','.btn-back-detail').on('click','.btn-back-detail',function(e){
        let action = $(this).attr('action');
        if (action == 'All') {
            let selectorMonth = $('#OpMonth');
            let selectorYear = $('#OpYear');
            let selectorShowAll = $('#OpShowAll');
            let PageDashboardAll = $('#PageDashboardAll');
            App_ticketing_dashboard.PageDashboardAll(selectorMonth,selectorYear,selectorShowAll,PageDashboardAll);
        }
        else
        {
            let selectorShowToday = $('#OpShowToday');
            let PageToday = $('#PageToday');
            App_ticketing_dashboard.PageToday(selectorShowToday,PageToday);

        }
    })

    $(document).off('change','#OpShowToday').on('change','#OpShowToday',function(e){
        let selectorShowToday = $('#OpShowToday');
        let PageToday = $('#PageToday');
        App_ticketing_dashboard.PageToday(selectorShowToday,PageToday);
    })

    $(document).off('change','#OpShowAll').on('change','#OpShowAll',function(e){
        let selectorMonth = $('#OpMonth');
        let selectorYear = $('#OpYear');
        let selectorShowAll = $('#OpShowAll');
        let PageDashboardAll = $('#PageDashboardAll');
        App_ticketing_dashboard.PageDashboardAll(selectorMonth,selectorYear,selectorShowAll,PageDashboardAll);;
    })

    $(document).off('click', '.ModalReadMore').on('click', '.ModalReadMore',function(e) {
        var selector = $(this);
        let data = jwt_decode(selector.attr('data'));
        // console.log(data);
        var setTicket = '';
        var ID = data['ID'];
        var token = selector.attr('data')
        AppModalDetailTicket.ModalReadMore(ID,setTicket,token);
    })

    $(document).off('change', '#SelectDepartmentID').on('change',
        '#SelectDepartmentID',
        function(e) {
            oTable.ajax.reload(null, false);
            let PageToday = $('#PageToday');
            let selectorTableAll = $('#tableDashboardAll').find('table');
            let GraphDashboardAll = $('#GraphDashboardAll');
            let selectorMonth = $('#OpMonth');
            let selectorYear = $('#OpYear');
            let selectorShowAll = $('#OpShowAll');
            let selectorShowToday = $('#OpShowToday');
            let PageDashboardAll = $('#PageDashboardAll');
            App_ticketing_dashboard.LoadDefault(selectorMonth,selectorYear,selectorShowAll,selectorShowToday,PageToday,PageDashboardAll);
            
    })
    
</script>