
<style>
    #tbInput td {
        /*text-align: center;*/
    }
    .form-datetime[readonly] {
        background-color: #ffffff;
        color: #333333;
        cursor: text;
    }
    #tableEditExamStd thead tr th, #tableEditExamStd tbody tr td {
        text-align: center;
    }
</style>


<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <table class="table table-hover" id="tbInput">
            <tr>
                <th style="width: 10%;">Exam | Date</th>
                <td style="width: 1%;">:</td>
                <td style="text-align: left;">
                    <div class="row">
                        <div class="col-xs-3">
                            <label class="radio-inline">
                                <input type="radio" name="formExam" id="formUTS" value="uts" class="formExam form-exam" checked> UTS
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="formExam" id="formUAS" value="uas" class="formExam form-exam"> UAS
                            </label>
                        </div>
                        <div class="col-xs-6">
                            <input type="text" id="formDate" readonly class="form-control form-exam form-datetime">
                            <input id="formInputDate" class="hide" hidden readonly>
                            <input id="formDayID" type="hidden" class="hide" hidden readonly>
                        </div>
                    </div>

                </td>
            </tr>
            <tr>
                <th>Group</th>
                <td>:</td>
                <td>
                    <div class="row">
                        <div class="col-xs-8">
                            <input id="formSemesterID" type="hidden" class="hide" hidden readonly>
                            <div id="viewGroup"></div>
                        </div>
                        <div class="col-xs-2" style="padding-top: 5px;">
                            <textarea id="formStudent" class="hide" hidden readonly></textarea>
                            <textarea id="AllStudent" class="hide" hidden readonly></textarea>
                            <b class="label label-primary"> <span id="dataTotalStudent">0</span> of <span id="OfDataTotalStudent">0</span></b> Students |
                            <a href="javascript:void(0);" class="btnEditStudent form-exam" data-classgroup="" data-notr="">Edit</a>
                        </div>
                        <div class="col-xs-2" style="text-align: right;">
                            <button class="btn btn-default btn-default-success form-exam" id="addNewGroup"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                            <button class="btn btn-default btn-default-danger form-exam" id="deleteNewGroup" disabled><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </td>
            </tr>

            <tbody id="trNewGroup"></tbody>

            <tr>
                <th>Waktu</th>
                <td>:</td>
                <td>
                    <div class="row">
                        <div class="col-md-4">
                            <div id="inputStart" class="input-group">
                                <input data-format="hh:mm" type="text" id="formStart" class="form-control form-exam" value="00:00"/>
                                <span class="add-on input-group-addon">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                            </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div id="inputEnd" class="input-group">
                                <input data-format="hh:mm" type="text" id="formEnd" class="form-control form-exam" value="00:00"/>
                                <span class="add-on input-group-addon">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                            </span>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Room</th>
                <td>:</td>
                <td>
                    <select class="select2-select-00 form-exam" style="max-width: 300px !important;" size="5" id="formClassroom">
                        <option value=""></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Pengawas 1</th>
                <td>:</td>
                <td style="text-align: left;">
                    <select class="select2-select-00 form-exam" style="max-width: 300px !important;" size="5" id="formPengawas1">
                        <option value=""></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Pengawas 2</th>
                <td>:</td>
                <td style="text-align: left;">
                    <select class="select2-select-00 form-exam" style="max-width: 300px !important;" size="5" id="formPengawas2">
                        <option value=""></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td id="trAlertJadwal" class="hide" colspan="3">
                    <div class="alert alert-warning" role="alert">
                        <b>Group Class sudah dibuatkan <b id="jmlJadwal"></b> Jadwal Ujian</b>
                    </div>
                </td>
            </tr>
        </table>

        <div style="text-align: right;">
            <button id="btnSaveSetSchedule" class="btn btn-primary">Save</button>
        </div>
        <hr/>

        <div id="divAlertBentrok"></div>
    </div>

</div>



<script>

    $(document).ready(function () {

        window.notr = 0;

        getDataCourse('#viewGroup','');
        dateInputJadwal();

        loadSelectOptionClassroom('#formClassroom','');
        $('#inputStart,#inputEnd').datetimepicker({
            pickDate: false,
            pickSeconds : false
        });

        loadSelectOptionEmployeesSingle('#formPengawas1','');
        loadSelectOptionEmployeesSingle('#formPengawas2','');
        $('#formPengawas1,#formPengawas2,#formClassroom').select2({allowClear: true});
    });

    // ===== NEW GROUP ======

    $('#addNewGroup').click(function () {
        notr = notr + 1;

        $('#trNewGroup').append('<tr id="trG'+notr+'">' +
            '<td></td>' +
            '<td></td>' +
            '<td>' +
            '<div class="row">' +
            '<div class="col-xs-8">' +
            '<div id="viewGroup'+notr+'"></div>' +
            '</div>' +
            '<div class="col-xs-4">' +
            '<textarea id="formStudent'+notr+'" class="hide" hidden readonly></textarea>' +
            '<textarea id="AllStudent'+notr+'" class="hide" hidden readonly></textarea>' +
            '<b class="label label-primary"> <span id="dataTotalStudent'+notr+'">0</span> of <span id="OfDataTotalStudent'+notr+'">0</span></b> Student |' +
            '<a href="javascript:void(0);" class="btnEditStudent" data-classgroup="" data-notr="'+notr+'">Edit</a>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '</tr>');

        $('#deleteNewGroup').prop('disabled',false);
        getDataCourse('#viewGroup'+notr,notr);
    });

    $('#deleteNewGroup').click(function () {

        if(notr>0){
            $('#trG'+notr).remove();
            notr = notr - 1;
            if(notr==0){
                $('#deleteNewGroup').prop('disabled',true);
            }
        } else {
            $('#deleteNewGroup').prop('disabled',true);
        }

    });

    // ===========================

    // ===== Save Schedule ====

    $('#btnSaveSetSchedule').click(function () {

        var formInputDate = $('#formInputDate').val();
        errorForm('formDate',formInputDate,0);

        var formCourse = $('#formCourse').val();
        errorForm('formCourse',formCourse,1);

        // ==== Other Course ====

        var CourseOtherNext = [];
        if(notr>0){
            for(var r=1;r<=notr;r++){
                var formCourseOther = $('#formCourse'+r).val();
                errorForm('formCourse'+r,formCourseOther,1);
                if(formCourseOther!='' && formCourseOther!=null){
                    CourseOtherNext.push(1);
                } else {
                    CourseOtherNext.push(0);
                }
            }
        }

        // ======================

        var formStart = $('#formStart').val();
        errorForm('formStart',formStart,0);

        var formEnd = $('#formEnd').val();
        errorForm('formEnd',formEnd,0);

        var formClassroom = $('#formClassroom').val();
        errorForm('formClassroom',formClassroom,1);

        var formPengawas1 = $('#formPengawas1').val();
        errorForm('formPengawas1',formPengawas1,1);

        if($.inArray(0,CourseOtherNext)==-1 && formInputDate!='' && formInputDate!=null
            && formCourse!='' && formCourse!=null && formStart!='' && formStart!=null
            && formEnd!='' && formEnd!=null && formClassroom!='' && formClassroom!=null
            && formPengawas1!='' && formPengawas1!=null){

            var formSemesterID = $('#formSemesterID').val();
            var formDayID = $('#formDayID').val();
            var Type = $('input[name=formExam]:checked').val();

            var formPengawas2 = $('#formPengawas2').val();

            var insert_details = [];
            var insert_group = [formCourse];

            // Requered
            var formStudent = $('#formStudent').val();
            var Arr_formStudent = JSON.parse(formStudent);

            var AllStudent = $('#AllStudent').val();
            var Arr_AllStudent = JSON.parse(AllStudent);

            if(Arr_formStudent.length>0){
                for(var q=0;q<Arr_AllStudent.length;q++){
                    var d = Arr_AllStudent[q];
                    if(d.IDEd == '' || d.IDEd == null){

                        if($.inArray(d.NPM,Arr_formStudent)!=-1){
                            var arr_s = {
                                ScheduleID : formCourse,
                                MhswID : d.MhswID,
                                NPM : d.NPM,
                                DB_Students : d.DB_Students
                            };
                            insert_details.push(arr_s);
                        }
                    }

                }
            }


            // Other MK
            if(notr>0){
                for(var h=1;h<=notr;h++){
                    var formCourse_ot = $('#formCourse'+h).val();
                    var formStudent_ot = $('#formStudent'+h).val();
                    var Arr_formStudent_ot = JSON.parse(formStudent_ot);

                    var AllStudent_ot = $('#AllStudent'+h).val();
                    var Arr_AllStudent_ot = JSON.parse(AllStudent_ot);

                    if(Arr_formStudent_ot.length>0){
                        for(var o=0;o<Arr_AllStudent_ot.length;o++){
                            var od = Arr_AllStudent_ot[o];
                            if(od.IDEd == '' || od.IDEd == null){
                                if($.inArray(od.NPM,Arr_formStudent_ot)!=-1){

                                    if($.inArray(formCourse_ot,insert_group)==-1){
                                        insert_group.push(formCourse_ot);
                                    }

                                    var arr_s_ot = {
                                        ScheduleID : formCourse_ot,
                                        MhswID : od.MhswID,
                                        NPM : od.NPM,
                                        DB_Students : od.DB_Students
                                    };
                                    insert_details.push(arr_s_ot);
                                }
                            }
                        }
                    }

                }
            }
            
            var data = {
                action : 'setExamSchedule',
                insert_exam : {
                    SemesterID : formSemesterID,
                    Type : Type,
                    ExamDate : formInputDate,
                    DayID : formDayID,
                    ExamClassroomID : formClassroom,
                    ExamStart : formStart,
                    ExamEnd : formEnd,
                    Pengawas1 : formPengawas1,
                    Pengawas2 : formPengawas2

                },
                insert_details : insert_details,
                insert_group : insert_group

            };

            var token = jwt_encode(data,'UAP)(*');

            checkBentrol(formSemesterID,Type,formInputDate,formClassroom,formStart,formEnd,token);



        }
        
    });
    
    function checkBentrol(SemesterID,Type,Date,RoomID,Start,End,token2save) {

        loading_button('#btnSaveSetSchedule');
        $('.form-exam').prop('disabled',true);
        
        var data = {
            action : 'checkBentrokExam',
            SemesterID : SemesterID,
            Type : Type,
            Date : Date,
            RoomID : RoomID,
            Start : Start,
            End : End
        };
        
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudJadwalUjian';
        
        $.post(url,{token:token},function (jsonResult) {


            setTimeout(function () {
                $('#btnSaveSetSchedule').html('Save');
                $('.form-exam,#btnSaveSetSchedule').prop('disabled',false);

            },500);

            if(jsonResult.length>0){
                $('#divAlertBentrok').html('<div class="alert alert-danger" role="alert">' +
                    '                <b>Conflict with </b>' +
                    '                <hr/><div id="dataBentrok"></div>' +
                    '            </div>');

                for(var i=0;i<jsonResult.length;i++){
                    var d = jsonResult[i];
                    $('#dataBentrok').append('<ul id="ulC'+i+'">' +
                        '                </ul>' +
                        '                <div style="color: blue;margin-top: 10px;margin-left: 20px;">' +
                        '                    '+moment(d.ExamDate).format('dddd, DD MMM YYYY')+' | '+d.Room+' | '+d.ExamStart.substr(0,5)+' - '+d.ExamEnd.substr(0,5)+'' +
                        '                </div>' +
                        '                <hr/>');

                    for(var c=0;c<d.Course.length;c++){
                        var dc = d.Course[c];
                        $('#ulC'+i).append('<li>'+dc.NameEng+'</li>');
                    }
                }

            } else {
                $('#divAlertBentrok').html('');
                saveSchedule(token2save);
            }
        });
        
        
    }

    function saveSchedule(token) {
        var url = base_url_js+'api/__crudJadwalUjian';

        $.post(url,{token:token},function (result) {
            toastr.success('Schedule Saved','Success');
            setTimeout(function () {
                window.location.href = '';
            },1000);
        });
    }

    function errorForm(element,value,type_form) {
        // type_form :
        // 0 = form biasa
        // 1 = select2 form
        var el = (type_form==1) ? '#s2id_'+element+' .select2-choice' : '#'+element;

        if(value=='' || value==null){
            $(el).css('border','1px solid red');
        } else {
            $(el).css('border','1px solid green');
        }

        setTimeout(function () {
            $(el).css('border','1px solid #ccc');
        },5000);


    }

    //===========================


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

    $(document).on('change','.showStudent',function () {

        var tr_no = $(this).attr('data-tr');

        var url = base_url_js+'api/__crudJadwalUjian';
        var ScheduleID = $('#formCourse'+tr_no).val();

        if(ScheduleID!='' && ScheduleID!=null){
            var ExamType = $('input[type=radio][name=formExam]:checked').val();
            var token = jwt_encode({
                action:'checkCourse4Exam',
                ScheduleID:ScheduleID,
                Type : ExamType
            },'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {

                var arr_NPM_draf = [];
                var std = jsonResult.StudentsDetails;

                // Cek jika apakah sudah di setting jadwal group ini
                if(jsonResult.Exam.length>0){
                    if(std.length>0){
                        for(var s=0;s<std.length;s++){
                            if(std[s].IDEd!='' && std[s].IDEd!=null){

                            } else {
                                arr_NPM_draf.push(std[s].NPM);
                            }
                        }
                    }
                } else {
                    if(std.length>0){

                        for(var s=0;s<std.length;s++){
                            arr_NPM_draf.push(std[s].NPM);
                        }
                    }
                }

                $('#formStudent'+tr_no).val(JSON.stringify(arr_NPM_draf));
                $('#AllStudent'+tr_no).val(JSON.stringify(std));

                $('#dataTotalStudent'+tr_no).html(arr_NPM_draf.length);
                $('#OfDataTotalStudent'+tr_no).html(std.length);

                var group = $('#formCourse'+tr_no+' option:selected').text();
                $('.btnEditStudent[data-notr='+tr_no+']').attr('data-classgroup',group);


            });

        } else {

            $('#formStudent'+tr_no).val('');
            $('#AllStudent'+tr_no).val('');

            $('#dataTotalStudent'+tr_no).html(0);
            $('#OfDataTotalStudent'+tr_no).html(0);
        }


    });

    $(document).on('click','.btnEditStudent',function () {
        var no_tr = $(this).attr('data-notr');
        var Classgroup = $(this).attr('data-classgroup');
        var Student_In_Draf = $('#formStudent'+no_tr).val();
        var AllStudent = $('#AllStudent'+no_tr).val();


        if(Student_In_Draf!='' && Student_In_Draf!=null && AllStudent!='' && AllStudent!=null){
            var Arr_Student_In_Draf = JSON.parse(Student_In_Draf);
            var Arr_AllStudent = JSON.parse(AllStudent);
            $('#GlobalModal .modal-header').html('<h4 class="modal-title">Edit Student | '+Classgroup+'</h4>');

            var dataHTML = '<div class="row">' +
                '<div class="col-md-2 col-md-offset-3">' +
                '<label>Student : </label>' +
                '</div> ' +
                '<div class="col-md-4">' +
                '<select class="form-control" id="selectSumStd"></select>' +
                '<input id="StdDisabled" class="hide" hidden readonly />' +
                '<input id="dataNo_tr" value="'+no_tr+'" class="hide" hidden readonly />' +
                '<input id="dataAllStudent" value="'+Arr_AllStudent.length+'" class="hide" hidden readonly />' +
                '<hr/>' +
                '</div> ' +
                '</div>' +
                '<div class="table-responsive">' +
                '<table id="tableEditExamStd" class="table table-bordered table-striped">' +
                '            <thead>' +
                '            <tr style="background: #438848;color: #FFFFFF;">' +
                '                <th style="width: 7%;"></th>' +
                '                <th style="width: 7%;">No</th>' +
                '                <th style="width: 20%;">NPM</th>' +
                '                <th>Name</th>' +
                '            </tr>' +
                '            </thead>' +
                '            <tbody id="rwStdExam"></tbody>' +
                '        </table>' +
                '</div>';

            $('#GlobalModal .modal-body').html(dataHTML);
            $('#GlobalModal .modal-footer').html('Selected : <b id="modalStdCk"></b> of <b id="modalAllStd"></b> Students | ' +
                '<button id="btnCloseStdCk" class="btn btn-default" data-dismiss="modal">Close</button>');

            var totalDisabled = 0;
            var totalCk = 0;
            if(Arr_AllStudent.length>0){
                var no = 1;
                for(var i=0;i<Arr_AllStudent.length;i++){
                    var d = Arr_AllStudent[i];
                    var ck = '<input type="checkbox" id="ckS'+i+'" value="'+d.NPM+'" class="checkStdExam" />';
                    if($.inArray(d.NPM,Arr_Student_In_Draf)!=-1){
                        ck = '<input type="checkbox" id="ckS'+i+'" value="'+d.NPM+'" class="checkStdExam" checked />';
                        totalCk = totalCk+1;
                    }

                    if(d.IDEd!='' && d.IDEd!=null){
                        ck = '<i class="fa fa-check-circle" style="color: green;"></i>';
                        totalDisabled = totalDisabled+1;
                    }

                    $('#rwStdExam').append('<tr>' +
                        '<td>'+ck+'</td>' +
                        '<td>'+no+'</td>' +
                        '<td>'+d.NPM+'</td>' +
                        '<td style="text-align: left;">'+d.Name+'</td>' +
                        '</tr>');

                    $('#selectSumStd').append('<option value="'+no+'">'+no+'</option>');

                    no += 1;
                }
            }

            $('#selectSumStd').val(totalCk);
            $('#StdDisabled').val(totalDisabled);

            $('#modalStdCk').html(totalCk);
            $('#modalAllStd').html(Arr_AllStudent.length);



        }
        else {
            $('#GlobalModal .modal-body').html('<div style="text-align:center;"><h4>Data Not Yet</h4></div>');
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        }

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

    $(document).on('change','.checkStdExam',function () {
        loadSelectedStudent();
    });

    $(document).on('change','#selectSumStd',function () {
        var selectSumStd = $('#selectSumStd').val();
        var stdDisabled = $('#StdDisabled').val();

         var st = parseInt(stdDisabled);
         var end = parseInt(stdDisabled) + parseInt(selectSumStd);

        $('.checkStdExam').prop('checked',false);
        for(var i=st;i<end;i++){
            $('#ckS'+i).prop('checked',true);
        }

        loadSelectedStudent();

    });

    function loadSelectedStudent() {
        var no_tr = $('#dataNo_tr').val();
        var dataAllStudent = $('#dataAllStudent').val();

        var npm_update_to_draf = [];
        for(var i=0;i<dataAllStudent;i++){
            if($('#ckS'+i).is(':checked')){
                npm_update_to_draf.push($('#ckS'+i).val());
            }
        }
        $('#formStudent'+no_tr).val(JSON.stringify(npm_update_to_draf));
        $('#dataTotalStudent'+no_tr).html(npm_update_to_draf.length);
        $('#selectSumStd').val(npm_update_to_draf.length);
        $('#modalStdCk').html(npm_update_to_draf.length);


    }

    function dateInputJadwal() {
        var dataForm = $('input[name=formExam]:checked').val();
        var url = base_url_js+'api/__crudJadwalUjian';
        var token = jwt_encode({action:'checkDateExam'},'UAP)(*');

        $( "#formDate" ).val('');
        $( "#formDate" ).datepicker( "destroy" );

        $.post(url,{token:token},function (jsonResult) {

            $('#formSemesterID').val(jsonResult.SemesterID);

            var dateStart = jsonResult.utsStart;
            var dateEnd = jsonResult.utsEnd;

            if(dataForm=='uas'){
                dateStart = jsonResult.uasStart;
                dateEnd = jsonResult.uasEnd;
            }

            var splitStart = dateStart.split('-');
            var C_dateStart_Y = splitStart[0].trim();
            var C_dateStart_M = parseInt(splitStart[1].trim())-1;
            var C_dateStart_D = splitStart[2].trim();

            var splitEnd = dateEnd.split('-');
            var C_dateEnd_Y = splitEnd[0].trim();
            var C_dateEnd_M = parseInt(splitEnd[1].trim())-1;
            var C_dateEnd_D = splitEnd[2].trim();

            $('#formDate').datepicker({
                showOtherMonths:true,
                autoSize: true,
                dateFormat: 'dd MM yy',
                minDate : new Date(C_dateStart_Y,C_dateStart_M,C_dateStart_D),
                maxDate : new Date(C_dateEnd_Y,C_dateEnd_M,C_dateEnd_D),
                onSelect : function () {
                    var data_date = $(this).val().split(' ');
                    var momentDate = moment(data_date[2]+'-'+(parseInt(convertDateMMtomm(data_date[1])) + 1)+'-'+data_date[0]);
                    var CustomMoment = momentDate.day();
                    var day = (CustomMoment==0) ? 7 : CustomMoment;
                    $('#formDayID').val(day);
                    $('#formInputDate').val(momentDate.format('YYYY-MM-DD'));
                }
            });
        });
    }

    function getDataCourse(element,notr) {

        var nor = (notr!='' && notr!=null && typeof notr !== 'undefined') ? notr : '';
        var idC = 'formCourse'+nor;

        var url = base_url_js+'api/__crudJadwalUjian';
        var token = jwt_encode({action:'read'},'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {

            $(element).html('<select class="select2-select-00 full-width-fix showStudent form-exam"' +
                '                            size="5" data-tr="'+nor+'" id="'+idC+'"></select>');

            $('#'+idC).empty();
            $('#'+idC).append('<option value=""></option>');
            for(var i=0;i<jsonResult.length;i++){
                var data = jsonResult[i];

                $('#'+idC).append('<option value="'+data.ID+'">'+data.ClassGroup+' - '+data.CourseEng+' ( '+data.CoordinatorName+' )</option>');
            }

            $('#'+idC).select2({allowClear: true});
        });
    }

    function countTotalStudent() {

        var data_npm_to_exam = [];

        // Requered
        var formStudent = $('#formStudent').val();
        console.log(formStudent);
        if(formStudent!='' && formStudent!=null){
            var arr_formStudent = JSON.parse(formStudent);
            console.log(arr_formStudent);
            for(var i=0;i<arr_formStudent.length;i++){
                data_npm_to_exam.push(arr_formStudent[i]);
            }
        }

        if(notr>0){
            for(var n=0;n<notr;n++){
                var formStudent_s = $('#formStudent'+n).val();
                if(formStudent_s!='' && formStudent_s!=null){
                    var arr_formStudent_s = JSON.parse(formStudent_s);
                    for(var t=0;t<arr_formStudent_s.length;t++){
                        data_npm_to_exam.push(arr_formStudent_s[t]);
                    }
                }
            }
        }

        $('#formTotalStudent').val(JSON.stringify(data_npm_to_exam));
        $('#viewTotalStudent').html(data_npm_to_exam.length);

    }

</script>