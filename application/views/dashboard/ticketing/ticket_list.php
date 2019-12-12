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
                                    <option value="99">--Incoming--</option>
                                    <option value="-99">--Outgoing--</option>
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
                var stylerow = (App_ticket_tikcet_list.cekCloseWorker(jwt_decode(data[9])) &&
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
                var DetailAction = '<a href="javascript:void(0)" class ="ModalReadMore" token = "' +
                    data[9] + '" >Detail</a>';
                var setAction = '';
                var SelectDepartmentID = $('#SelectDepartmentID option:selected').val();
                var EncodeDepartment = jwt_encode(SelectDepartmentID, 'UAP)(*');
                if (data[8] == 1 && data[11] == 'write') {
                    var hrefActionTicket = base_url_js + 'ticket' + '/set_action_first/' + data[1] +
                        '/' + EncodeDepartment;
                    setAction = '<a href="' + hrefActionTicket + '">Set Action</a>';
                } else if (data[8] == 2 && data[11] == 'write') {
                    var hrefActionTicket = base_url_js + 'ticket' + '/set_action_progress/' + data[
                        1] + '/' + EncodeDepartment
                    setAction = '<a href="' + hrefActionTicket + '">Set Action</a>';
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

    cekCloseWorker: function(data) {
        var data_received = data.data_received;
        var bool = true;
        for (let index = 0; index < data_received.length; index++) {
            var DataReceived_Details = data_received[index].DataReceived_Details;
            for (let j = 0; j < DataReceived_Details.length; j++) {
                if (DataReceived_Details[j].Status == "1") {
                    bool = false;
                    break;
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

$(document).off('change', '#SelectDepartmentID,#SelectStatusTicketID,#FilterFor').on('change',
    '#SelectDepartmentID,#SelectStatusTicketID,#FilterFor',
    function(e) {
        oTable.ajax.reload(null, false);
    })

$(document).off('click', '.ModalReadMore').on('click', '.ModalReadMore', function(e) {
    var token = $(this).attr('token');
    AppModalDetailTicket.ModalReadMore('', '', token);
})
</script>