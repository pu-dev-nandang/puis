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
    #ulCurrentGroup {
        list-style-type: none;
        padding-left: 0px;
        margin-bottom: 0px;
    }

    #ulCurrentGroup li .btnDeleteGroup {
        /*color: red;*/
        border-radius: 60px;
        padding: 1px;
        padding-left: 5px;
        padding-right: 6px;
    }
    #ulCurrentGroup li .btnEditStudent {
        padding: 1px;
        padding-left: 5px;
        padding-right: 6px;
    }

    #ulCurrentGroup li {
        padding-bottom: 10px;
    }
</style>

<!--<pre>-->
<!--    --><?php //print_r($arrExam); ?>
<!--</pre>-->

<?php if(count($arrExam)>0){
    $d = $arrExam[0];
    ?>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <input value="<?php echo $d['ID']; ?>" id="formExamID" class="hide" hidden readonly>
            <input value="<?php echo $d['SemesterID']; ?>" id="formSemesterID" class="hide" hidden readonly>
            <table class="table" id="tbInput">
                <tr>
                    <th style="width: 15%;">Exam | Date</th>
                    <td style="width: 1%;">:</td>
                    <td style="text-align: left;">
                        <div class="row">
                            <div class="col-xs-3">
                                <label class="radio-inline">
                                    <input type="radio" name="formExam" id="formUTS" value="uts" class="formExam form-exam" <?php if($d['Type']=='uts'){echo 'checked'; } ?>> UTS
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="formExam" id="formUAS" value="uas" class="formExam form-exam" <?php if($d['Type']=='uas'){echo 'checked'; } ?>> UAS
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <input type="text" id="formDate" readonly class="form-control form-exam form-datetime">
                                <span id="viewDate"></span>
                                <input id="formInputDate" value="<?php echo $d['ExamDate']; ?>" class="hide" readonly hidden>
                                <input id="formDayID" value="<?php echo $d['DayID']; ?>" class="hide" readonly hidden>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <th>Promgramme Study</th>
                    <td>:</td>
                    <td>
                        <select class="form-control" id="formBaseProdi" style="max-width: 350px;"></select>
                    </td>
                </tr>
                <tr style="background: lightyellow;">
                    <th>Current Group</th>
                    <td>:</td>
                    <td>
                        <ul id="ulCurrentGroup">
                            <?php $totalStudent=0; for($t=0;$t<count($d['Course']);$t++){ $dc = $d['Course'][$t]; ?>
                                <li id="liGr<?php echo $dc['ScheduleID']; ?>"><b><?php echo $dc['ClassGroup'].' - '.$dc['CourseEng']; ?>
                                    <textarea id="textStd<?php echo $t; ?>" class="hide" hidden readonly><?php echo json_encode($dc['DetailStudent']); ?></textarea>
                                    </b> |
                                    <button class="btn btn-sm btn-default btn-default-primary btnEditStudent4EditExam" data-no-arr="<?php echo $t; ?>"
                                            data-examid="<?php echo $d['ID']; ?>"
                                            data-id="<?php echo $dc['ScheduleID']; ?>"><span id="viewTextStd<?php echo $t; ?>"><?php echo count($dc['DetailStudent']); ?></span> Student</button> |
                                    <button class="btn btn-sm btn-default btn-default-danger btnDeleteGroup" data-examid="<?php echo $d['ID']; ?>" data-id="<?php echo $dc['ScheduleID']; ?>"><i class="fa fa-trash"></i></button></li>
                            <?php $totalStudent = $totalStudent + count($dc['DetailStudent']); } ?>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <th>Group</th>
                    <td>:</td>
                    <td>
                        <div class="row">
                            <div class="col-xs-8">
                                <input id="formSemesterID" value="<?php echo $d['SemesterID']; ?>" class="hide" readonly hidden>
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
                                    <input data-format="hh:mm" type="text" id="formStart" class="form-control form-exam" value="<?php echo substr($d['ExamStart'],0,5); ?>"/>
                                    <span class="add-on input-group-addon">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                            </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div id="inputEnd" class="input-group">
                                    <input data-format="hh:mm" type="text" id="formEnd" class="form-control form-exam" value="<?php echo substr($d['ExamEnd'],0,5); ?>"/>
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
                    <th>Online Exams</th>
                    <td>:</td>
                    <td>
                        <div class="checkbox checbox-switch switch-primary" style="margin-top: 0px;">
                            <label>
                                <input type="checkbox" <?= ($d['OnlineLearning']==1 || $d['OnlineLearning']=='1') ? 'checked' : ''; ?> id="formOnlineLearning">
                                <span></span>
                                <!--                            <i> | Filter Attendance in UAS (75%)</i>-->
                            </label>
                        </div>
                        <button class="btn btn-default" id="uploadSoal">Upload Soal</button>
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
                <input id="viewTotalStudent" class="hide" hidden readonly value="<?php echo $totalStudent; ?>">
                <a href="<?php echo base_url('academic/exam-schedule'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left margin-right"></i> Back to List</a>
                <button id="btnSaveEditSchedule" class="btn btn-primary">Save</button>
            </div>
            <hr/>

            <div id="divAlertBentrok"></div>
        </div>

    </div>

    <script>
        $(document).ready(function () {

            loading_modal_show();

            window.notr = 0;

            $('#formBaseProdi').append('<option value="">-- Select Programme Study --</option>' +
                '<option disabled>------------------------------------------</option>');
            loadSelectOptionBaseProdi('#formBaseProdi',<?php echo $d['InsertByProdiID']; ?>);

            var formDate = "<?php echo $d['ExamDate']; ?>";
            $('#viewDate').html(moment(formDate).format('dddd, DD MMM YYYY'));
            // console.log(new Date(formDate));
            // $('#formDate').datepicker('setDate',new Date(formDate));

            dateInputJadwal_();
            loadSelect2OptionClassroom('#formClassroom','<?php echo $d['ExamClassroomID'].'.'.$d['Seat'].'.'.$d['SeatForExam']; ?>');

            $('#inputStart,#inputEnd').datetimepicker({
                pickDate: false,
                pickSeconds : false
            });

            loadSelectOptionEmployeesSingle('#formPengawas1','<?php echo $d['Pengawas1']; ?>');
            loadSelectOptionEmployeesSingle('#formPengawas2','<?php echo $d['Pengawas2']; ?>');

            $('#formClassroom,#formPengawas1,#formPengawas2').select2({allowClear: true});

            getDataCourse('#viewGroup','');


        });

        // === Upload Soal Class Online ====

        $('#uploadSoal').click(function () {

            var formExamID = $('#formExamID').val();

            var data = {
                action : 'getDataExamTask',
                ExamID : formExamID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudJadwalUjian';

            $.post(url,{token:token},function (jsonResult) {


                var formDescription = (jsonResult.length>0) ? jsonResult[0].Description : '';
                var formAction = (jsonResult.length>0) ? 'edit' : 'add';

                var formNameFile = formExamID+'_'+moment().unix();
                var showFile = '';
                var formNameFileOld = '';
                var btnRemove = 'hide';
                var IDExamTask = '';
                if(jsonResult.length>0){
                    var file = (jsonResult[0].File!='' && jsonResult[0].File!=null) ? jsonResult[0].File : '';
                    if(file!=''){
                        showFile = (jsonResult.length>0)
                            ? '<iframe src="'+base_url_js+'/uploads/task-exam/'+file+'"></iframe>' : '';
                        formNameFileOld = file;
                    }
                    IDExamTask = jsonResult[0].ID;
                    btnRemove = '';


                }


                $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                    '<h4 class="modal-title">Upload Soal</h4>');

                var htmlss = ' <div class="row">' +
                    '        <div class="col-md-12">' +
                    '            <form id="formID" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">' +
                    '            <div class="form-group">' +
                    '                <label>Description</label>' +
                    '                <input class="hide" id="formAction" name="formAction" value="'+formAction+'">' +
                    '                <input class="hide" id="formExamID" name="formExamID" value="'+formExamID+'">' +
                    '                <input class="hide" id="formNIP" name="formNIP" value="'+sessionNIP+'">' +
                    '                <textarea id="formDescription" name="formDescription" class="form-control">'+formDescription+'</textarea>' +
                    '            </div>' +
                    '            <div class="form-group">' +
                    '                <label>File (pdf)</label>' +
                    '                <input type="file" id="formFileSoal" name="userfile" accept="application/pdf">' +
                    '                   <input type="text" class="hide" hidden name="formNameFile" id="formNameFile" value="'+formNameFile+'" />' +
                    '                   <input type="text" class="hide" hidden name="formNameFileOld" id="formNameFileOld" value="'+formNameFileOld+'" />' +
                    '                   <div id="viewFileSize"></div>' +
                    '                   <p class="help-block">Maximum file size of 5 mb</p>' +
                    '            </div>' +
                    '           <div>'+showFile+'</div>' +
                    '           </form>' +
                    '        </div>' +
                    '    </div>';

                $('#GlobalModal .modal-body').html(htmlss);

                $('#formDescription').summernote({
                    placeholder: 'Text your description',
                    tabsize: 2,
                    height: 200,
                    toolbar: [
                        // [groupName, [list of button]]
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']]
                    ]
                });

                $('#GlobalModal .modal-footer').html('' +
                    '<button class="btn btn-default '+btnRemove+'" id="removeExamTask" data-id="'+IDExamTask+'" style="color: red;float: left;">Remove Data</button>' +
                    '<button class="btn btn-success" id="submitSoalExam">Save</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

                $('#GlobalModal').modal({
                    'show' : true,
                    'backdrop' : 'static'
                });

            });


        });

        $(document).on('change','#formFileSoal',function () {
            readURL(this);
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();


                var _size = input.files[0].size;
                var fSExt = new Array('Bytes', 'KB', 'MB', 'GB'),
                    i=0;while(_size>900){_size/=1024;i++;}
                var exactSize = (Math.round(_size*100)/100)+' '+fSExt[i];
                $('#viewFileSize').html('<div style="color: #034df4;font-size: 12px;margin-top: 10px;">Your file size: '+exactSize+'</div>');

                var fileSize = input.files[0].size;
                if(fileSize > 5000000){
                    alert('Maximum file size of 5 mb');
                    $('#btnSubmitTast').prop('disabled',true);
                } else {
                    $('#btnSubmitTast').prop('disabled',false);
                }


            }
        }

        $(document).on('click','#submitSoalExam',function () {

            var formExamID = $('#formExamID').val();
            var formDescription = $('#formDescription').val();


            if(formExamID!='' && formExamID!=null &&
                formDescription!='' && formDescription!=null){

                if(confirm('Are you sure?')){
                    var formFileSoal = $('#formFileSoal').val();
                    var fileUpload = (formFileSoal!='') ? 1 : 0;
                    var formData = new FormData( $("#formID")[0]);
                    var url = base_url_js+'upload/upload-exam-task?f='+fileUpload;
                    $.ajax({
                        url : url,  // Controller URL
                        type : 'POST',
                        data : formData,
                        async : false,
                        cache : false,
                        contentType : false,
                        processData : false,
                        success : function(data) {
                            var jsonData = data;

                            if(typeof jsonData.success=='undefined'){
                                alert(jsonData.error);
                            } else {
                                toastr.success('Data saved','Success');
                                $('#GlobalModal').modal('hide');
                            }


                        }
                    });
                }

            }
            else {
                toastr.error('Form Are Required','Error!');
            }

        });

        // ===================

        $(document).on('click','.btnDeleteGroup',function () {

            var ScheduleID = $(this).attr('data-id');
            var ExamID = $(this).attr('data-examid');


            $('#NotificationModal .modal-header').addClass('hide');
            $('#NotificationModal .modal-body').html('<div style="text-align: center;">' +
                '<h4>Menghapus group akan menghapus data student</h4>' +
                '<hr/>' +
                '<button type="button" class="btn btn-danger" id="btnDeleteGroup">Yes</button> ' +
                '<button type="button" class="btn btn-default" data-dismiss="modal">No</button> ' +
                '</div>');
            $('#NotificationModal .modal-footer').addClass('hide');
            $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
            });

            $('#btnDeleteGroup').click(function () {

                loading_buttonSm('#btnDeleteGroup');
                $('.btn-default[data-dismiss=modal]').prop('disabled',true);

                var url = base_url_js+'api/__crudJadwalUjian';
                var token = jwt_encode({action:'deleteGroupExam',ExamID:ExamID,ScheduleID:ScheduleID},'UAP)(*');
                $.post(url,{token:token},function (result) {
                    $('#liGr'+ScheduleID).remove();
                    toastr.success('Data removed','Success');
                    setTimeout(function () {
                        $('#NotificationModal').modal('hide');
                        if(result==-1 || result=='-1'){
                            window.location.href = base_url_js+'academic/exam-schedule/list-exam';
                        } else {
                            window.location.href = '';
                        }
                    },500);
                });
            });

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

        // ===== Save Edit Exam Schedule =====

        $('#btnSaveEditSchedule').click(function () {
            var formInputDate = $('#formInputDate').val();

            var formBaseProdi = $('#formBaseProdi').val();
            errorForm('formBaseProdi',formBaseProdi,0);

            var formCourse = $('#formCourse').val();

            var formStart = $('#formStart').val();
            errorForm('formStart',formStart,0);

            var formEnd = $('#formEnd').val();
            errorForm('formEnd',formEnd,0);

            var formClassroom = $('#formClassroom').val();
            errorForm('formClassroom',formClassroom,1);

            var formPengawas1 = $('#formPengawas1').val();
            errorForm('formPengawas1',formPengawas1,1);


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
            if(formBaseProdi!='' && formBaseProdi!=null){
                if(formStart!='' && formStart!=null
                    && formEnd!='' && formEnd!=null && formClassroom!=''
                    && formClassroom!=null
                    && formPengawas1!='' && formPengawas1!=null){

                    var formExamID = $('#formExamID').val();
                    var formSemesterID = $('#formSemesterID').val();
                    var formDayID = $('#formDayID').val();
                    var Type = $('input[name=formExam]:checked').val();

                    var formPengawas2 = $('#formPengawas2').val();


                    var insert_details = [];
                    var insert_group = (formCourse!='' && formCourse!=null) ? [formCourse] : [];

                    var formStudent = $('#formStudent').val();
                    var Arr_formStudent = (formStudent!='' && formStudent!=null) ? JSON.parse(formStudent) : [];

                    var AllStudent = $('#AllStudent').val();
                    var Arr_AllStudent = (AllStudent!='' &&AllStudent!=null) ? JSON.parse(AllStudent) : [];

                    if(Arr_formStudent.length>0){
                        for(var q=0;q<Arr_AllStudent.length;q++){
                            var d = Arr_AllStudent[q];
                            if(d.IDEd == '' || d.IDEd == null){

                                if($.inArray(d.NPM,Arr_formStudent)!=-1){
                                    var arr_s = {
                                        ScheduleID : formCourse,
                                        MhswID : d.MhswID,
                                        NPM : d.NPM,
                                        Name : ucwords(d.Name),
                                        DB_Students : d.DB_Students
                                    };
                                    insert_details.push(arr_s);
                                }
                            }

                        }
                    }

                    // Other Course
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
                                                Name : ucwords(od.Name),
                                                DB_Students : od.DB_Students
                                            };
                                            insert_details.push(arr_s_ot);
                                        }
                                    }
                                }
                            }

                        }
                    }


                    var RoomID = formClassroom.split('.')[0];
                    var SeatForExam = formClassroom.split('.')[2];
                    var viewTotalStudent = $('#viewTotalStudent').val();
                    var totalStudent = parseInt(insert_details.length) + parseInt(viewTotalStudent);

                    var OnlineLearning = ($('#formOnlineLearning').is(':checked')) ? '1' : '0';

                    if(totalStudent <= SeatForExam){
                        var ProdiID = formBaseProdi.split('.')[0];
                        var data = {
                            action : 'editGroupExam',
                            ExamID : formExamID,
                            SemesterID : formSemesterID,
                            updateExam : {
                                Type : Type,
                                ExamDate : formInputDate,
                                DayID : formDayID,
                                ExamClassroomID : RoomID,
                                ExamStart : formStart,
                                ExamEnd : formEnd,
                                Pengawas1 : formPengawas1,
                                Pengawas2 : formPengawas2,
                                OnlineLearning : OnlineLearning,
                                Status : '1',
                                InsertByProdiID : ProdiID,
                                UpdateBy : sessionNIP,
                                UpdateAt : dateTimeNow()
                            },
                            insert_details : insert_details,
                            insert_group : insert_group
                        };

                        var token = jwt_encode(data,'UAP)(*');

                        checkBentrok(formExamID,formSemesterID,Type,formInputDate,RoomID,formStart,formEnd,token);
                    }
                    else {
                        toastr.error('Classroom not Enought','Error');
                    }

                }
            } else {
                if(confirm('is this a combined class schedule?')){
                    if(formStart!='' && formStart!=null
                        && formEnd!='' && formEnd!=null && formClassroom!=''
                        && formClassroom!=null
                        && formPengawas1!='' && formPengawas1!=null){

                        var formExamID = $('#formExamID').val();
                        var formSemesterID = $('#formSemesterID').val();
                        var formDayID = $('#formDayID').val();
                        var Type = $('input[name=formExam]:checked').val();

                        var formPengawas2 = $('#formPengawas2').val();


                        var insert_details = [];
                        var insert_group = (formCourse!='' && formCourse!=null) ? [formCourse] : [];

                        var formStudent = $('#formStudent').val();
                        var Arr_formStudent = (formStudent!='' && formStudent!=null) ? JSON.parse(formStudent) : [];

                        var AllStudent = $('#AllStudent').val();
                        var Arr_AllStudent = (AllStudent!='' &&AllStudent!=null) ? JSON.parse(AllStudent) : [];

                        if(Arr_formStudent.length>0){
                            for(var q=0;q<Arr_AllStudent.length;q++){
                                var d = Arr_AllStudent[q];
                                if(d.IDEd == '' || d.IDEd == null){

                                    if($.inArray(d.NPM,Arr_formStudent)!=-1){
                                        var arr_s = {
                                            ScheduleID : formCourse,
                                            MhswID : d.MhswID,
                                            NPM : d.NPM,
                                            Name : ucwords(d.Name),
                                            DB_Students : d.DB_Students
                                        };
                                        insert_details.push(arr_s);
                                    }
                                }

                            }
                        }

                        // Other Course
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
                                                    Name : ucwords(od.Name),
                                                    DB_Students : od.DB_Students
                                                };
                                                insert_details.push(arr_s_ot);
                                            }
                                        }
                                    }
                                }

                            }
                        }


                        var RoomID = formClassroom.split('.')[0];
                        var SeatForExam = formClassroom.split('.')[2];
                        var viewTotalStudent = $('#viewTotalStudent').val();
                        var totalStudent = parseInt(insert_details.length) + parseInt(viewTotalStudent);
                        var OnlineLearning = ($('#formOnlineLearning').is(':checked')) ? '1' : '0';

                        if(totalStudent <= SeatForExam){
                            var ProdiID = formBaseProdi.split('.')[0];
                            var data = {
                                action : 'editGroupExam',
                                ExamID : formExamID,
                                SemesterID : formSemesterID,
                                updateExam : {
                                    Type : Type,
                                    ExamDate : formInputDate,
                                    DayID : formDayID,
                                    ExamClassroomID : RoomID,
                                    ExamStart : formStart,
                                    ExamEnd : formEnd,
                                    Pengawas1 : formPengawas1,
                                    Pengawas2 : formPengawas2,
                                    OnlineLearning : OnlineLearning,
                                    Status : '1',
                                    InsertByProdiID : ProdiID,
                                    UpdateBy : sessionNIP,
                                    UpdateAt : dateTimeNow()
                                },
                                insert_details : insert_details,
                                insert_group : insert_group
                            };

                            var token = jwt_encode(data,'UAP)(*');

                            checkBentrok(formExamID,formSemesterID,Type,formInputDate,RoomID,formStart,formEnd,token);
                        }
                        else {
                            toastr.error('Classroom not Enought','Error');
                        }

                    }
                }
            }



        });

        function checkBentrok(ExamID,SemesterID,Type,Date,RoomID,Start,End,token2save) {

            loading_button('#btnSaveEditSchedule');
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
                    $('#btnSaveEditSchedule').html('Save');
                    $('.form-exam,#btnSaveEditSchedule').prop('disabled',false);

                },500);

                if(jsonResult.length>0){

                    if(jsonResult.length==1 && jsonResult[0].ID==ExamID){
                        $('#divAlertBentrok').html('');
                        saveEditSchedule(token2save);
                    } else {
                        $('#divAlertBentrok').html('<div class="alert alert-danger" role="alert">' +
                            '                <b>Conflict with </b>' +
                            '                <hr/><div id="dataBentrok"></div>' +
                            '            </div>');

                        for(var i=0;i<jsonResult.length;i++){
                            var d = jsonResult[i];

                            if(d.ID!=ExamID){
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

                        }
                    }

                } else {
                    $('#divAlertBentrok').html('');
                    saveEditSchedule(token2save);
                }
            });


        }

        function saveEditSchedule(token) {

            // Cek bentrok dengan venue
            var url2 = base_url_js+'api2/__checkConflict_Venue';

            var formInputDate = $('#formInputDate').val();
            var formStart = $('#formStart').val();
            var formEnd = $('#formEnd').val();

            var textRoom = $('#formClassroom option:selected').text();
            var RoomName = textRoom.split('|');
            var data2 = {
                Start : formInputDate+' '+formStart+':00',
                End : formInputDate+' '+formEnd+':00',
                RoomName : RoomName[0].trim()
            };
            var token2 = jwt_encode(data2,"UAP)(*");

            $.post(url2,{token:token2},function (result) {
                var bool = result.bool;
                if (bool) {
                    saveEditSchedule_2(token)
                } else {
                    if(confirm('Conflict with Venue, are you sure?')){
                        saveEditSchedule_2(token)
                    }
                }
            });





        }

        function saveEditSchedule_2(token) {
            $.getJSON(base_url_js+'api/__checkBentrokScheduleAPI');
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

        // ===================================


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

        $(document).on('click','.btnEditStudent4EditExam',function () {


           var no_array = $(this).attr('data-no-arr');

            var arrCourse = $('#textStd'+no_array).val();

            var std = (arrCourse!='' && arrCourse!=null) ? JSON.parse(arrCourse) : [];


            $('#NotificationModal .modal-header').addClass('hide');
            $('#NotificationModal .modal-body').html('<table class="table table-bordered table-bordered">' +
                '<thead><tr>' +
                '<th style="width: 1%;text-align: center;">No</th>' +
                '<th>Student</th>' +
                '<th style="width: 5%;text-align: center;">Action</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody id="tbEdtiExStd"></tbody>' +
                '</table>' +
                '<div style="text-align: right;"><hr/><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>');

            if(std.length>0){
                var no=1;
                for(var s=0;s<std.length;s++){
                    var ds = std[s];
                    $('#tbEdtiExStd').append('<tr id="trStdmodalEditExam'+ds.ID+'">' +
                        '<td style="text-align: center;">'+no+'</td>' +
                        '<td><b>'+ds.Name+'</b><br/>'+ds.NPM+'</td>' +
                        '<td style="text-align: center;"><button class="btn btn-block btn-danger btnDelete4EditExamDelStd" data-no-arr="'+no_array+'" data-id="'+ds.ID+'">Delete</button></td>' +
                        '</tr>');
                    no++;
                }
            } else {
                $('#tbEdtiExStd').append('<tr><td colspan="3" style="text-align: center;">--- Data Not yet ---</td></tr>');
            }

            $('#NotificationModal .modal-footer').addClass('hide');
            $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
            });

        });

        $(document).on('click','.btnDelete4EditExamDelStd',function () {

            if(confirm('Do you want to delete this data??')){

                var no_array = $(this).attr('data-no-arr');

                var arrCourse = $('#textStd'+no_array).val();

                var std = (arrCourse!='' && arrCourse!=null) ? JSON.parse(arrCourse) : [];

                var Exam_detail_ID = $(this).attr('data-id');

                loading_buttonSm('.btnDelete4EditExamDelStd[data-id='+Exam_detail_ID+']');
                $('.btnDelete4EditExamDelStd').prop('disabled',true);


                var url = base_url_js+'api/__crudJadwalUjian';
                var token = jwt_encode({action:'deleteStuden4EditExam',Exam_detail_ID:Exam_detail_ID},'UAP)(*');
                $.post(url,{token:token},function (result) {

                    var newArrStd = [];
                    for(var u=0;u<std.length;u++){
                        if(std[u].ID!=Exam_detail_ID){
                            newArrStd.push(std[u]);
                        }
                    }

                    var na = (newArrStd.length>0) ? JSON.stringify(newArrStd) : '';

                    $('#textStd'+no_array).val(na);
                    $('#viewTextStd'+no_array).html(newArrStd.length);

                    var viewTotalStudent = $('#viewTotalStudent').val();
                    var newVal = parseInt(viewTotalStudent) - 1;
                    $('#viewTotalStudent').val(newVal);

                    if(newArrStd.length<=0){
                        $('#NotificationModal').modal('hide');
                    }

                    setTimeout(function () {

                        $('#trStdmodalEditExam'+Exam_detail_ID).animateCss('zoomOut',function () {
                            $('#trStdmodalEditExam'+Exam_detail_ID).remove();
                        });
                        $('.btnDelete4EditExamDelStd').prop('disabled',false);

                    },1000);
                });
            }


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

        function dateInputJadwal_() {
            var dataForm = $('input[name=formExam]:checked').val();
            var formSemesterID = $('#formSemesterID').val();

            var url = base_url_js+'api/__crudJadwalUjian';
            var token = jwt_encode({action:'checkDateExam4Edit',SemesterID:formSemesterID},'UAP)(*');

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

                        // $('#viewDate').html(momentDate.format('dddd, DD MMM YYYY'));
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

                loading_modal_hide();
            });
        }

        $(document).on('click','#removeExamTask',function () {
            if(confirm('Are you sure?')){
                var ID = $(this).attr('data-id');

                var url = base_url_js+'upload/remove-exam-task/'+ID;

                $.post(url,function (result) {

                    toastr.success('Data removed','Success');
                    $('#GlobalModal').modal('hide');

                });
            }
        })
    </script>

<?php } else {

} ?>