

<style>
    .panel-description {
        /*min-height: 150px;*/
        overflow: auto;
        max-height: 150px;
    }
    .panel-title {
        padding: 10px;
        border: 1px solid #f3e7e7;
        border-radius: 5px;
        margin-bottom: 10px;
        background: #fff9f9;
    }
    #tableData td:first-child {
        border-right: 1px solid #cccccc;
    }
    .form-control[readonly] {
        background: #ffffff;
        color: #333333;
        cursor: pointer;
    }
</style>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="well">
            <div class="row">
                <div class="col-md-6">
                    <label>Portal</label>
                    <select id="filterPortal" class="form-control">
                        <option value="">-- All Portal --</option>
                        <option value="emp">Employees</option>
                        <option value="std">Students</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Date</label>

                    <div class="input-group">
                        <input id="filterDate" class="form-control" readonly />
                        <span class="input-group-btn">
                        <button class="btn btn-default" id="btnRemoveDate" type="button"><i class="fa fa-times-circle"></i></button>
                    </span>
                    </div>

                    <input id="filterDateData" class="hide" />

                </div>
            </div>
        </div>
        <hr/>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="loadTable"></div>
    </div>

</div>

<script>

    $(document).ready(function () {

        $('#filterDate').datepicker({
            showOtherMonths:true,
            autoSize: true,
            dateFormat: 'dd MM yy',
            onSelect : function () {
                var data_date = $(this).val().split(' ');
                var momentDate = moment(data_date[2]+'-'+(parseInt(convertDateMMtomm(data_date[1])) + 1)+'-'+data_date[0]);
                $('#filterDateData').val(momentDate.format('YYYY-MM-DD'));
                loadDataTable();
            }
        });

        loadDataTable();

    });

    $('#filterPortal').change(function () {
        loadDataTable();
    });

    $('#btnRemoveDate').click(function () {
        $('#filterDate,#filterDateData').val('');
        loadDataTable();
    });



    function loadDataTable() {
        $('#loadTable').html('<table class="table table-centre" id="tableData">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Data Eula</th>' +
            '                <th style="width: 15%;">Published</th>' +
            '            </tr>' +
            '            </thead>' +
            '        </table>');

        var filterPortal = $('#filterPortal').val();
        var filterDateData = $('#filterDateData').val();

        var data = {
            FilterPortal : (filterPortal!='' && filterPortal!=null) ? filterPortal : '',
            FilterDate : (filterDateData!='' && filterDateData!=null) ? filterDateData : ''
        };
        var token = jwt_encode(data,'UAP)(*');

        var url = base_url_js+'api4/__getListEula';

        var dataTable = $('#tableData').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Title, Description"
            },
            "ajax":{
                url :url, // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    loading_modal_hide();
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );
    }

</script>