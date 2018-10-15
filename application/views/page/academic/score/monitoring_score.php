
<style>
    #tableMonScore thead tr th,#tableDataScore tbody tr td {
        text-align: center;
    }

    #tableMonScore thead tr {
        background-color: #436888;color: #ffffff;
    }

    .tbGradeC tr td{
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="well">
            <div class="row">
                <div class="col-xs-3">
                    <select class="form-control filter-mon-score" id="filterSemester"></select>
                </div>
                <div class="col-xs-2">
                    <select class="form-control filter-mon-score" id="filterAscYear"></select>
                </div>
                <div class="col-xs-5">
                    <select class="form-control filter-mon-score" id="filterBaseProdi">
                        <option value="">--- All Programme Study ---</option>
                        <option disabled>-------------------</option>
                    </select>
                </div>
                <div class="col-xs-2">
                    <select class="form-control filter-mon-score" id="filterType">
                        <option value="">--- All Status ---</option>
                        <option disabled>-------------------</option>
                        <optgroup label="Mid Exam Score">
                            <option value="11">Already Input</option>
                            <option value="10">Not Yet Input</option>
                        </optgroup>
                        <optgroup label="Final Exam Score">
                            <option value="21">Already Input</option>
                            <option value="20">Not Yet Input</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="dataTB"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loSelectOptionSemester('#filterSemester','');
        loadSelectOptionForce('#filterAscYear','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');

        var loadFirst = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            var filterAscYear = $('#filterAscYear').val();
            if(filterSemester!='' && filterSemester!=null && filterAscYear!='' && filterAscYear!=null){
                loadData();
                clearInterval(loadFirst);
            }
        },1000);

    });

    $(document).on('change','.filter-mon-score',function () {
        loadData();
    });

    function loadData() {
        var filterSemester = $('#filterSemester').val();
        var filterAscYear = $('#filterAscYear').val();
        if(filterSemester!='' && filterSemester!=null && filterAscYear!='' && filterAscYear!=null){

            loading_page('#dataTB');

            setTimeout(function () {

                var filterBaseProdi = $('#filterBaseProdi').val();
                var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null) ? filterBaseProdi.split('.')[0] : '' ;

                var Year = filterAscYear.split('.')[1];

                var filterType = $('#filterType').val();


                $('#dataTB').html('<table class="table table-bordered table-striped" id="tableMonScore">' +
                    '            <thead>' +
                    '            <tr>' +
                    '                <th rowspan="2" style="width: 1%;">No</th>' +
                    '                <th rowspan="2" style="width: 15%;">Student</th>' +
                    '                <th rowspan="2" style="width: 7%;">Code</th>' +
                    '                <th rowspan="2">Course</th>' +
                    '                <th rowspan="2" style="width: 7%;">Group</th>' +
                    '                <th rowspan="2" style="width: 15%;">Lecturer</th>' +
                    '                <th colspan="5" style="width: 10%;">Assignment Score</th>' +
                    '                <th colspan="2" style="width: 10%;">Exam Score</th>' +
                    '            </tr>' +
                    '            <tr>' +
                    '                <th style="width: 3%;">1</th>' +
                    '                <th style="width: 3%;">2</th>' +
                    '                <th style="width: 3%;">3</th>' +
                    '                <th style="width: 3%;">4</th>' +
                    '                <th style="width: 3%;">5</th>' +
                    '                <th style="width: 5%;">Mid</th>' +
                    '                <th style="width: 5%;">Final</th>' +
                    '            </tr>' +
                    '            </thead>' +
                    '        </table>');

                var data = {
                    SemesterID : filterSemester.split('.')[0],
                    ProdiID : ProdiID,
                    Year : Year,
                    Type : filterType
                };

                var token = jwt_encode(data,'UAP)(*');

                var dataTable = $('#tableMonScore').DataTable( {
                    "processing": true,
                    "serverSide": true,
                    "iDisplayLength" : 25,
                    "ordering" : false,
                    "language": {
                        "searchPlaceholder": "NIM, Student, Group, Lecturer"
                    },
                    "ajax":{
                        url : base_url_js+"api/__getMonScoreStd", // json datasource
                        data : {token:token},
                        ordering : false,
                        type: "post",  // method  , by default get
                        error: function(){  // error handling
                            $(".employee-grid-error").html("");
                            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                            $("#employee-grid_processing").css("display","none");
                        }
                    }
                } );

            },500);

        }
    }
</script>