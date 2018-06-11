
<style>
    #tableExam>thead>tr>th {
        text-align: center;
    }
</style>

<hr/>
<div class="thumbnail" style="margin-bottom: 10px;">
    <div class="row">
<!--        <div class="col-xs-2" style="">-->
<!--            <select class="form-control form-filter-jadwal" id="filterProgramCampus"></select>-->
<!--        </div>-->
        <div class="col-xs-4" style="">
            <select id="filterSemester" class="form-control form-filter form-filter-jadwal">
            </select>
        </div>
        <div class="col-xs-5" style="">
            <select id="filterBaseProdi" class="form-control form-filter form-filter-jadwal">
                <option value="">--- All Program Study ---</option>
                <option disabled>------------------------------------------</option>
            </select>
        </div>
        <div class="col-xs-3" style="">
            <select id="filterExam" class="form-control form-filter form-filter-jadwal">
                <option disabled selected>--- Exam Type ---</option>
                <option value="uts">UTS</option>
                <option value="uas">UAS</option>
            </select>
        </div>
<!--        <div class="col-xs-2">-->
<!--            <div id="selectSemesterSc">-->
<!--                <select class="form-control form-filter" id="filterSemesterSchedule"></select>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>

<div class="row">
    <div class="col-md-12" style="min-height: 150px;">
        <div class="">
            <hr/>
            <table class="table table-bordered" id="tableExam">
                <thead>
                <tr style="background: #437e88;color: #ffffff;">
                    <th style="width: 7%;">MKCode</th>
                    <th>Course</th>
                    <th style="width: 15%;">Lecturers</th>
                    <th style="width: 15%;">Pengawas</th>
                    <th style="width: 15%;">Date</th>
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
        // loadSelectOptionProgramCampus('#filterProgramCampus','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');
        $('#filterSemester').append('<option value="" disabled selected>-- Year Academic--</option>' +
            '                <option disabled>------------------------------------------</option>');

        loSelectOptionSemester('#filterSemester','');


    });

    function loadDataScheduleExam() {

        var filterSemester = $('#filterSemester').val();
        var filterBaseProdi = $('#filterBaseProdi').val();
        var filterExam = $('#filterExam').val();

        if(filterSemester!='' && filterSemester!=null && filterBaseProdi!='' && filterBaseProdi!=null && filterExam!='' && filterExam!=null){
            var filterSemesterSplit = filterSemester.split('.');
            var filterBaseProdiSplit = filterBaseProdi.split('.');

            var data = {
                action : 'readSchedule',
                SemesterID : filterSemesterSplit[0],
                Type : filterExam,
                ProdiID : filterBaseProdiSplit[0]
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudJadwalUjian';

            $.post(url,{token:token},function (resultJson) {
                console.log(resultJson);
                if(resultJson.length>0){

                    for(var i=0;i<resultJson.length;i++){
                        var dataEx = resultJson[i];

                        var pengawas = (dataEx.Pengawas2!=null) ? dataEx.Pengawas1+' - '+dataEx.Pengawas1Name+'<br/>'+dataEx.Pengawas2+' - '+dataEx.Pengawas2Name : dataEx.Pengawas1+' - '+dataEx.Pengawas1Name;

                        $('#trExam').append('<tr>' +
                            '<td style="text-align: center;">'+dataEx.CourseDetails.MKCode+'</td>' +
                            '<td>'+dataEx.CourseDetails.MKNameEng+'</td>' +
                            '<td>'+dataEx.CourseDetails.Coordinator+'</td>' +
                            '<td>'+pengawas+'</td>' +
                            '<td style="text-align: center;">'+dataEx.DayEng+', '+moment(dataEx.ExamDate).format('DD MMMM YYYY')+'</td>' +
                            '<td style="text-align: center;">'+dataEx.Room+'</td>' +
                            '</tr>');
                    }

                }
            });
        }
    }
</script>