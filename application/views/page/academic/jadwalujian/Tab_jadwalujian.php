<div class="row" style="margin-top: 30px;">

    <div class="col-md-4">
        <div class="">
            <label>Semester Antara</label>
            <input type="checkbox" id="formSemesterAntara" data-toggle="toggle" data-style="ios"/>
        </div>
    </div>
    <div class="col-md-8" style="text-align: right;">
        <button data-page="jadwalujian" type="button" class="btn btn-success btn-action
                        control-jadwal"><i class="fa fa-calendar right-margin" aria-hidden="true"></i> Schedule Exam</button>
        <button data-page="inputjadwalujian" type="button" class="btn btn-default btn-default-success btn-action control-jadwal">
            <i class="fa fa-pencil right-margin" aria-hidden="true"></i> Set Schedule Exam
        </button>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <div id="dataPage"></div>
    </div>
</div>

<script>
    $(document).ready(function () {

        loadPage('jadwalujian','');

        window.SemesterAntara = 0;

    });

    // $(document).on('change','#filterSemester',function () {
    //     var Semester = $('#filterSemester').val();
    //     var SemesterID = (Semester!='' && Semester!= null) ? Semester.split('.')[0] : '';
    //
    //     $('#selectSemesterSc').html('<select class="form-control" id="filterSemesterSchedule"></select>');
    //     // $('#filterSemesterSchedule').empty();
    //     $('#filterSemesterSchedule').append('<option value="" disabled selected>-- Semester --</option>' +
    //         '                <option disabled>------------</option>');
    //     loadSelectOPtionAllSemester('#filterSemesterSchedule','',SemesterID,SemesterAntara);
    //     // filterSchedule();
    //
    // });

    $(document).on('click','.btn-action',function () {
        var page = $(this).attr('data-page');
        var ScheduleID = (page=='editjadwal') ? $(this).attr('data-id') : '';
        PageNow = page;
        PageScdNow = ScheduleID;

        if(page!='editjadwal'){
            $('.btn-action').removeClass('btn-success');
            $('.btn-action').addClass('btn-default btn-default-success');

            $('button[data-page='+page+']').removeClass('btn-default btn-default-success');
            $('button[data-page='+page+']').addClass('btn-success');
        }

        loadPage(page,ScheduleID);
    });

    function loadPage(page,ScheduleID) {
        loading_page('#dataPage');
        var data = {
            page : page,
            ScheduleID : ScheduleID
        };

        var token = jwt_encode(data,"UAP)(*");
        var url = base_url_js+'academic/__setPageJadwalUjian';
        $.post(url,{token:token},function (page) {
            setTimeout(function () {
                $('#dataPage').html(page);
            },500);
        });
    }
</script>

<!-- Input Jadwal Ujian -->
<script>
    $(document).on('change','.formExam',function () {
        dateInputJadwal();
    });

    $(document).on('change','#formCourse',function () {
        var url = base_url_js+'api/__crudJadwalUjian';
        var ScheduleID = $('#formCourse').val();
        var token = jwt_encode({action:'checkCourse',ScheduleID:ScheduleID},'UAP)(*');

        // $('#dataCourse,#dataCoordinator,#dataTeamTeaching').html('');

        $.post(url,{token:token},function (jsonResult) {

            dataStudentForExam = [];

            $('#divDetails').html('<h3 style="color: #FF9800;"><b>Course</b></h3>' +
                '        <div id="dataCourse"></div>' +
                '        <h3 style="color: #FF9800;"><b>Coordinator</b></h3>' +
                '        <div id="dataCoordinator"></div>' +
                '        <div id="dataTeamTeaching"></div>');

            var course = jsonResult.Course;
            for(var i=0;i<course.length;i++){
                var dataCourse = course[i];
                $('#dataCourse').append('<b>'+dataCourse.MKCode+' - '+dataCourse.Course+' | '+dataCourse.Credit+' SKS</b>' +
                                        '<br/><i>'+dataCourse.ProdiEng+'</i><br/>');
            }

            var coordinator = jsonResult.Coordinator[0];
            $('#dataCoordinator').html('<b>'+coordinator.NIP+' - '+coordinator.Name+'</b>');

            var teamTeaching = jsonResult.TeamTeaching;
            if(teamTeaching.length>0){
                $('#dataTeamTeaching').html('<h3 style="color: #FF9800;"><b>Team</b></h3>' +
                    '            <div id="dataTeam"></div>');

                for(var t=0;t<teamTeaching.length;t++){
                    var dataT = teamTeaching[t];
                    $('#dataTeam').append('<b>'+dataT.NIP+' - '+dataT.Lecturer+'</b><br/>');
                }
            }

            var dataStudents = jsonResult.StudentsDetails;
            for(var s=0;s<dataStudents.length;s++){
                dataStudentForExam.push(dataStudents[s].MhswID);
            }


            $('#dataTotalStudent').html(jsonResult.TotalStudents);

        });
    });


</script>

<script>
    $(document).on('change','.form-filter',function () {
        loadDataScheduleExam();
    })
</script>