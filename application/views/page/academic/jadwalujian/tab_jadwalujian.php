<div class="row" style="margin-top: 30px;">

    <div class="col-md-4">
        <div class="">
            <label>Semester Antara</label>
            <input type="checkbox" id="formSemesterAntara" data-toggle="toggle" data-style="ios"/>
        </div>
    </div>
    <div class="col-md-8" style="text-align: right;">
        <button data-page="jadwalujian" type="button" class="btn btn-success btn-action
                        control-jadwal"><i class="fa fa-calendar right-margin" aria-hidden="true"></i> Exam Schedule</button>
        <button data-page="inputjadwalujian" type="button" class="btn btn-default btn-default-success btn-action control-jadwal">
            <i class="fa fa-pencil right-margin" aria-hidden="true"></i> Set Exam Schedule
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
        $('#formCourse').val('');
        dataStudentForExam = [];
        dataAllStudentForExam = [];


        $('#trAlertJadwal').addClass('hide');

        $('#dataTotalStudent').html(0);
        $('#OfDataTotalStudent').html(0);
        $('#btnEditExamStudents').attr('data-classgroup','');
        dateInputJadwal();
    });

    $(document).on('change','#formCourse',function () {
        var url = base_url_js+'api/__crudJadwalUjian';
        var ScheduleID = $('#formCourse').val();
        var ExamType = $('input[type=radio][name=formExam]:checked').val();
        var token = jwt_encode({
            action:'checkCourse',
            ScheduleID:ScheduleID,
            Type : ExamType
        },'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {

            dataStudentForExam = [];
            dataAllStudentForExam = [];

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
            $('#btnEditExamStudents').attr('data-classgroup',$('#formCourse option[value='+ScheduleID+']').text());
            $('#btnEditExamStudents').attr('data-ex',(jsonResult.Exam.length>0) ? 1 : 0);
            if(jsonResult.Exam.length>0){
                $('#trAlertJadwal').removeClass('hide');
                $('#jmlJadwal').html(jsonResult.Exam.length);
                if(jsonResult.StudentsDetails.length>0){

                    for(var s=0;s<dataStudents.length;s++){
                        if(dataStudents[s].IDEd!='' && dataStudents[s].IDEd!=null){
                            dataStudentForExamDisabled.push(dataStudents[s]);
                        }
                        dataAllStudentForExam.push(dataStudents[s]);
                    }

                }
            }
            else {
                $('#trAlertJadwal').addClass('hide');
                if(jsonResult.StudentsDetails.length>0){
                    for(var s=0;s<dataStudents.length;s++){

                        dataStudentForExam.push(dataStudents[s]);
                        dataAllStudentForExam.push(dataStudents[s]);
                    }
                }
            }

            $('#dataTotalStudent').html(dataStudentForExam.length);
            $('#OfDataTotalStudent').html(dataAllStudentForExam.length);

        });
    });

    $(document).on('click','#btnEditExamStudents',function () {

        var Classgroup = $(this).attr('data-classgroup');
        var dataEx = $(this).attr('data-ex');

        if(Classgroup!='' && Classgroup!=null){
            $('#GlobalModal .modal-header').html('<h4 class="modal-title">Edit Students | '+Classgroup+'</h4>');

            var dataHTML = '<table id="tableEditExamStd" class="table table-bordered table-striped">' +
                '            <thead>' +
                '            <tr style="background: #438848;color: #FFFFFF;">' +
                // '                <th style="width: 7%;">' +
                // '                    <div class="checkbox" style="margin: 0px;">' +
                // '                       <label>' +
                // '                           <input id="checkAllStd" type="checkbox"> All' +
                // '                       </label>' +
                // '                    </div>' +
                // '                </th>' +
                '                <th style="width: 7%;"></th>' +
                '                <th style="width: 7%;">No</th>' +
                '                <th style="width: 20%;">NPM</th>' +
                '                <th>Name</th>' +
                '            </tr>' +
                '            </thead>' +
                '            <tbody id="rwStdExam"></tbody>' +
                '        </table>';

            $('#GlobalModal .modal-body').html(dataHTML);
            $('#GlobalModal .modal-footer').html('Selected : <b id="modalStdCk"></b> of <b id="modalAllStd"></b> Students | ' +
                '<button type="button" id="btnCloseStdCk" class="btn btn-default" data-dismiss="modal">Close</button>');

            $('#rwStdExam').empty();
            if(dataAllStudentForExam.length>0){

                var noS = 0;

                // var ck = '';
                $('#modalStdCk').html(dataStudentForExam.length);
                $('#modalAllStd').html(dataAllStudentForExam.length);
                for(var sm=0;sm<dataAllStudentForExam.length;sm++) {
                    var ck = '<input type="checkbox" id="ckS'+sm+'" class="checkStdExam" />';
                    if(dataStudentForExam.length>0){
                        for(var s2=0;s2<dataStudentForExam.length;s2++){
                            if(dataStudentForExam[s2].MhswID == dataAllStudentForExam[sm].MhswID){
                                ck = '<input type="checkbox" id="ckS'+sm+'" class="checkStdExam" checked />';
                            }
                        }
                    }

                    if(dataStudentForExamDisabled.length>0){
                        for(var d=0;d<dataStudentForExamDisabled.length;d++){
                            if(dataStudentForExamDisabled[d].MhswID == dataAllStudentForExam[sm].MhswID
                                && dataStudentForExamDisabled[d].IDEd!=''
                                && dataStudentForExamDisabled[d].IDEd!=null){
                                ck = '<i class="fa fa-check-circle" style="color: green;"></i>';
                            }
                        }
                    }


                    $('#rwStdExam').append('<tr>' +
                        '<td id="trChcek'+sm+'">'+ck+'</td>' +
                        '<td>'+(noS += 1)+'</td>' +
                        '<td>'+dataAllStudentForExam[sm].NPM+'</td>' +
                        '<td style="text-align:left;">'+dataAllStudentForExam[sm].Name+'</td>' +
                        '</tr>');

                    // if(dataEx==1){
                    //
                    // }

                }

                $('#checkAllStd').prop('checked',false);
                if(dataAllStudentForExam.length == dataStudentForExam.length){
                    $('#checkAllStd').prop('checked',true);
                }

            }
            else {
                $('#rwStdExam').append('<tr><td colspan="3">-- No Students --</td></tr>');
            }


            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        }

    });

    $(document).on('change','#checkAllStd',function () {
        if($(this).is(':checked')){
            $('.checkStdExam').prop('checked',true);
        } else {
            $('.checkStdExam').prop('checked',false);
        }
        checkStdExam();
    });

    $(document).on('change','.checkStdExam',function () {
        checkStdExam();
    });

    function checkStdExam() {
        if(dataAllStudentForExam.length>0){
            dataStudentForExam = [];
            for(var sm=0;sm<dataAllStudentForExam.length;sm++){
                if($('#ckS'+sm).is(':checked')){
                    dataStudentForExam.push(dataAllStudentForExam[sm]);
                }
            }

            $('#modalStdCk').html(dataStudentForExam.length);
            $('#modalAllStd').html(dataAllStudentForExam.length);

            $('#checkAllStd').prop('checked',false);
            $('#btnCloseStdCk').prop('disabled',true);
            if(dataStudentForExam.length<=0){
                toastr.warning('Not Selected Student','Warning');
            } else {
                $('#btnCloseStdCk').prop('disabled',false);
                if(dataStudentForExam.length == dataAllStudentForExam.length){
                    $('#checkAllStd').prop('checked',true);
                }
                $('#dataTotalStudent').html(dataStudentForExam.length);
            }

            // console.log(dataStudentForExam);
        }
    }

</script>


<!-- Load Jadwal -->
<script>
    $(document).on('change','.form-filter',function () {
        loadDataScheduleExam();
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
            $('#trExam').empty();
            $.post(url,{token:token},function (resultJson) {
                console.log(resultJson);
                if(resultJson.length>0){

                    for(var i=0;i<resultJson.length;i++){
                        var dataEx = resultJson[i];
                        var pengawas = (dataEx.Pengawas2!=null) ?
                            '- '+dataEx.Pengawas1Name+'<br/>- '+dataEx.Pengawas2Name :
                            '- '+dataEx.Pengawas1Name;

                        var btnActionUjian = '<div class="dropdown">' +
                            '  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">' +
                            '    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>' +
                            '    <span class="caret"></span>' +
                            '  </button>' +
                            '  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">' +
                            '    <li><a href="javascript:void(0);" data-idexam="'+dataEx.ID+'" class="">Edit</a></li>' +
                            '    <li><a href="javascript:void(0);" data-idexam="'+dataEx.ID+'" class="">Edit Peserta</a></li>' +
                            '    <li><a href="javascript:void(0);" data-idexam="'+dataEx.ID+'" data-act="save2pdf_Layout" class="btn2PDFExam">Layout</a></li>' +
                            '    <li><a href="javascript:void(0);" data-idexam="'+dataEx.ID+'" data-act="save2pdf_DraftQuestions" class="btn2PDFExam">Naskah Soal</a></li>' +
                            '    <li><a href="javascript:void(0);" data-idexam="'+dataEx.ID+'" data-act="save2pdf_AnswerSheet" class="btn2PDFExam">Lembar Jawaban</a></li>' +
                            '    <li><a href="javascript:void(0);" data-idexam="'+dataEx.ID+'" data-act="save2pdf_NewsEvent" class="btn2PDFExam">Berita Acara</a></li>' +
                            '    <li><a href="javascript:void(0);" data-idexam="'+dataEx.ID+'" data-act="save2pdf_AttendanceList" class="btn2PDFExam">Daftar Hadir</a></li>' +
                            '    <li role="separator" class="divider"></li>' +
                            '    <li><a href="javascript:void(0);" data-idexam="'+dataEx.ID+'" class="">Delete</a></li>' +
                            '  </ul>' +
                            '</div>';

                        $('#trExam').append('<tr>' +
                            '<td style="text-align: center;">'+dataEx.ClassGroup+'</td>' +
                            // '<td style="text-align: center;">'+dataEx.CourseDetails.MKCode+'</td>' +
                            '<td style="text-align: left;">'+dataEx.CourseDetails.MKNameEng+'</td>' +
                            '<td style="text-align: left;">'+dataEx.CourseDetails.Coordinator+'</td>' +
                            '<td style="text-align: left;">'+pengawas+'</td>' +
                            '<td>'+btnActionUjian+'</td>' +
                            '<td>'+dataEx.DayEng+', '+moment(dataEx.ExamDate).format('DD MMMM YYYY')+'</td>' +
                            '<td>'+dataEx.ExamStart.substr(0,5)+' - '+dataEx.ExamEnd.substr(0,5)+'</td>' +
                            '<td>'+dataEx.Room+'</td>' +
                            '</tr>');
                    }

                }
            });
        }
    }
</script>

<!-- Save 2 PDF -->
<script>
    $(document).on('click','.btnGetLayout',function () {
        var IDExam = $(this).attr('data-idexam');
        var url = base_url_js+'api/__crudJadwalUjian';
        var token = jwt_encode({action:'save2pdfLayout', IDExam:IDExam},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {

        });
    });

    $(document).on('click','.btn2PDFExam',function () {
        var action = $(this).attr('data-act');
        var IDExam = $(this).attr('data-idexam');
        save2pdf_exam(action,IDExam);
    });

    function save2pdf_exam(action,IDExam) {
        
        var data = {
          action : action,
          IDExam : IDExam
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudJadwalUjian';

        $.post(url,{token:token},function (jsonResult) {


            return false;

            var token_pdf = jwt_encode(jsonResult,'UAP)(*');;

            var pdf_u =  'exam-layout';
            if(action=='save2pdf_DraftQuestions'){
                pdf_u = 'draft-questions';
            }
            else if(action=='save2pdf_AnswerSheet'){
                pdf_u = 'answer-sheet';
            }
            else if(action=='save2pdf_NewsEvent'){
                pdf_u = 'news-event';
            }
            else if(action=='save2pdf_AttendanceList'){
                pdf_u = 'attendance-list';
            }

            var url_pdf = base_url_js+'ave2pdf/'+pdf_u+'?token='+token_pdf;
            window.open(
                url_pdf,
                '_blank' // <- This is what makes it open in a new window.
            );

        });

    }
</script>