<style>
    .row-sma {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .form-time {
        padding-left: 0px;
        padding-right: 0px;
    }
    .row-sma .fa-plus-circle {
        color: green;
    }
    .row-sma .fa-minus-circle {
        color: red;
    }
    .btn-action {

        text-align: right;
    }

    #tableDetailTahun thead th {
        text-align: center;
    }

    .form-filter {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #ccc;
    }
    .filter-time {
        padding-left: 0px;
    }
</style>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> List Approve</h4>
            </div>
            <div class="widget-content">
                <div id="pageData">
                                
                </div>
            </div>
            <hr/>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        loadNumberFormulir();
    });

    function loadNumberFormulir()
    {
        $("#pageData").empty();
        loading_page('#pageData');
        var html_table ='<div class="col-md-12">'+
                         '<div class="table-responsive">'+
                            '<table class="table table-striped table-bordered table-hover table-checkable datatable">'+
                                '<thead>'+
                                    '<tr>'+
                                    '<th style="width: 15px;">No</th>'+
                                   ' <th>Start</th>'+
                                   ' <th>End</th>'+
                                   ' <th>Time</th>'+
                                   ' <th>Agenda</th>'+
                                   ' <th>Room</th>'+
                                   ' <th>Equipment Add</th>'+
                                   ' <th>Personel Support</th>'+
                                   ' <th>Req Date</th>'+
                                   ' <th>Req Layout</th>'+
                                    '</tr>'+
                               ' </thead>'+
                                '<tbody>'+
                                '</tbody>'+
                            '</table>'+
                         '</div>'+   
                        '</div>';
        var url = base_url_js+'vreservation/json_list_approve';
        $.post(url,function (data_json) {
            setTimeout(function () {
                var response = jQuery.parseJSON(data_json);
               $("#pageData").html(html_table);
               for (var i = 0; i < response.length; i++) {
                var status = '<td style="'+
                                'color:  green;'+
                                '">IN'+
                              '</td>';
                if (response[i]['Status'] == 1 ) {
                    status = '<td style="'+
                                'color:  red;'+
                                '">Sold Out'+
                              '</td>';
                }
                $(".datatable tbody").append(
                    '<tr>'+
                        '<td>'+no+'</td>'+
                        '<td>'+response[i]['Years']+'</td>'+
                        '<td>'+response[i]['FormulirCode']+'</td>'+
                        status+
                        '<td>'+response[i]['CreateAT']+'</td>'+
                        '<td>'+response[i]['Name']+'</td>'+
                    '</tr>' 
                    );
                no++;
            }
            LoaddataTable('.datatable');
            },500);
        });
    }
</script>