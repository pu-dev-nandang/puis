
<style>
    .std {
        display: inline-block;
        border: 1px solid #9e9ea0bd;
        padding: 5px 10px 5px 10px;
        margin-left: 5px;
        border-radius: 5px;
        font-size: 11px;
        background: #6ca62942;
    }
    .std2 {
        display: inline-block;
        border: 1px solid #9e9ea0bd;
        padding: 5px 10px 5px 10px;
        margin-left: 5px;
        border-radius: 5px;
        font-size: 11px;
        background: #ff9e9e4a;
    }

    #tableData tr td:first-child {
        border-right: 1px solid #CCCCCC;
    }
</style>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-8 col-md-offset-2">
        <div class="well">
            <div class="row">
                <div class="col-md-7">
                    <label>Programme Study</label>
                    <select class="form-control" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                        <option disabled>------------------------------------------</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label>Status Lecturers</label>
                    <select class="form-control" id="filterLecturerStatus"></select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" id="divLoadTableData"></div>
</div>

<script>

    $(document).ready(function () {

        loadSelectOptionBaseProdi('#filterBaseProdi','');
        loadSelectOptionLecturerStatus('#filterLecturerStatus','3');

        var firstLoad = setInterval(function () {

            var filterLecturerStatus = $('#filterLecturerStatus').val();

            if(filterLecturerStatus!='' && filterLecturerStatus!=null){
                loadDataLecturer();
                clearInterval(firstLoad);
            }

        },1000);

    });

    $('#filterBaseProdi,#filterLecturerStatus').change(function () {
        loadDataLecturer();
    });

    function loadDataLecturer() {

        var filterLecturerStatus = $('#filterLecturerStatus').val();

        if(filterLecturerStatus!='' && filterLecturerStatus!=null) {


            $('#divLoadTableData').html('<table class="table table-centre" id="tableData">' +
                '            <thead>' +
                '            <tr>' +
                '                <th style="width: 1%;">No</th>' +
                '                <th style="width: 20%;">Lecturer</th>' +
                '                <th>Student</th>' +
                '            </tr>' +
                '            </thead>' +
                '            <tbody></tbody>' +
                '        </table>');

            var filterBaseProdi = $('#filterBaseProdi').val();
            var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null) ? filterBaseProdi.split('.')[0] : '';

            var token = jwt_encode(
                {action : 'viewYudisiumLecturer',ProdiID : ProdiID,
                    LecturerStatus : filterLecturerStatus},'UAP)(*');
            var url = base_url_js+'api3/__crudYudisium';

            var dataTable = $('#tableData').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {"searchPlaceholder": "NIP, Name"},
                "ajax":{
                    url : url, // json datasource
                    data : {token:token},
                    ordering : false,
                    type: "post",  // method  , by default get
                    error: function(){  // error handling
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
                    }
                }
            });

        }



    }

</script>