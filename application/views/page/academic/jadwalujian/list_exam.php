

<style>
    #tableShowExam>thead>tr>th, #tableExam>tbody>tr>td {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="thumbnail" style="margin-bottom: 10px;">
            <div class="row">
                <div class="col-xs-5" style="">
                    <select id="filterSemester" class="form-control form-filter">
                    </select>
                </div>
                <div class="col-xs-2" style="">
                    <select id="filterExam" class="form-control form-filter">
                        <option value="uts">UTS</option>
                        <option value="uas">UAS</option>
                    </select>
                </div>
                <div class="col-xs-5" style="">
                    <select id="filterBaseProdi" class="form-control form-filter">
                        <option value="">-- All Programme Study --</option>
                    </select>
                </div>

            </div>
        </div>
        <hr/>

    </div>
</div>


<div class="row">
    <div class="col-md-12" style="min-height: 150px;">
        <div class="">
            <table class="table table-bordered" id="tableShowExam">
                <thead>
                <tr style="background: #437e88;color: #ffffff;">
                    <th style="width: 1%;">No</th>
                    <th>Course</th>
                    <th style="width: 15%;">Pengawas</th>
                    <th style="width: 5%;">Action</th>
                    <th style="width: 20%;">Date</th>
                    <th style="width: 10%;">Time</th>
                    <th style="width: 7%;">Room</th>
                </tr>
                </thead>
                <tbody id="trExam"></tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        loadSelectOptionBaseProdi('#filterBaseProdi','');
        loSelectOptionSemester('#filterSemester','');

        var loadFirst = setInterval(function () {

            var filterSemester = $('#filterSemester').val();
            if(filterSemester!='' && filterSemester!=null){
                loadDataExam();
                clearInterval(loadFirst);
            }

        },1000);

    });
    
    function loadDataExam() {
        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){

            var filterExam = $('#filterExam').val();
            var filterBaseProdi = $('#filterBaseProdi').val();
            var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null) ? filterBaseProdi.split('.')[0] : '';

            var data = {
                action : 'showDataExam',
                SemesterID : filterSemester.split('.')[0],
                ProdiID : ProdiID,
                Type : filterExam
            };

            var token = jwt_encode(data,'UAP)(*');


            var dataTable = $('#tableShowExam').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "Group, (Co)Lecturer, Classroom"
                },
                "ajax":{
                    url : base_url_js+"api/__getScheduleExam?token="+token, // json datasource
                    ordering : false,
                    type: "post",  // method  , by default get
                    error: function(){  // error handling
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
                    }
                }
            } );
            
            // $.post(url,{token:token},function (jsonResult) {
            //
            // });

        }
    }
</script>