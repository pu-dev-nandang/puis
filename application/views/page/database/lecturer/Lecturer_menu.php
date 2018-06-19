
<style>
    .form-attd[readonly] {
        cursor: cell;
        background-color: #fff;
        color: #333;
    }
</style>

<div class="row" style="margin-top: 30px;">

    <div class="col-md-12" style="margin-bottom: 15px;">
        <a href="<?php echo base_url('database/lecturers') ?>" class="btn btn-warning"><i class="fa fa-arrow-circle-left"></i> Back to list lecturers</a>
    </div>

    <div class="col-md-12">

        <div class="thumbnail" style="padding: 15px;">
            <div class="row">
                <div class="col-xs-1" style="text-align: right;padding-right: 0px;">
                    <div id="viewPhoto"></div>
                </div>
                <div class="col-xs-11">
                    <h3 style="margin-top: 0px;border-left: 11px solid #2196f3;padding-left: 10px;font-weight: bold;" id="viewName"></h3>
                    <table class="table">
                        <tr>
                            <td style="width: 50%;">
                                <i class="fa fa-envelope margin-right"></i> (Email PU) <a id="viewEmailPU"></a><br/>
                                <i class="fa fa-envelope margin-right"></i> (Email Other) <a id="viewEmailOther"></a><br/>
                                <i class="fa fa-phone margin-right"></i> (Phone) <span id="viewPhone"></span> <br/>
                                <i class="fa fa-phone margin-right"></i> (HP) <span id="viewHP"></span>
                            </td>
                            <td>
                                <i class="fa fa-map-marker margin-right"></i> <span id="viewAddress"></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <hr/>

        <div class="tabbable tabbable-custom tabbable-full-width">
            <ul class="nav nav-tabs">
                <li class="active"><a href="javascript:void(0)" class="menuDetails" data-page="lecturer_details" data-toggle="tab">Biodata</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="lecturer_academic" data-toggle="tab">Academic</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="Schedule_lecturer" data-toggle="tab">Schedule</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="Students" data-toggle="tab">Students</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="Research" data-toggle="tab">Research</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="Loyality" data-toggle="tab">Loyality</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="Appreciation" data-toggle="tab">Appreciation</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="Scientific Work" data-toggle="tab">Scientific Work</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="Activities" data-toggle="tab">Activities</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="Journal" data-toggle="tab">Journal</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="Books" data-toggle="tab">Books</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="Position" data-toggle="tab">Position</a></li>
            </ul>
            <div class="tab-content">

                <button onclick="ref()">Refresh Attd</button>
                <!--=== Overview ===-->
                <hr/>
                <div id="divPage"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadDataThumb();
        window.NIP = '<?php echo $NIP; ?>';

        var data = {
            NIP : NIP,
            page : 'lecturer_details'
        };
        var token = jwt_encode(data,'UAP)(*');

        loadPage(token);


        window.Lecturer_NIP = 0;

        $('input[id$="endTime"]').datetimepicker({
            format: 'HH:mm'
        });

    });

    $(document).on('click','.menuDetails',function () {
        var page = $(this).attr('data-page');
        var data = {
            NIP : NIP,
            page : page
        };
        var token = jwt_encode(data,'UAP)(*');

        loadPage(token);
    });

    $(document).on('click','.btnLecturerAction',function () {
        var page = $(this).attr('data-page');
        var ScheduleID = $(this).attr('data-id');
        var data = {
            NIP : NIP,
            page : page,
            ScheduleID : ScheduleID
        };
        var token = jwt_encode(data,'UAP)(*');
        loadPage(token);

    });


    function loadDataThumb() {
        var url = base_url_js+'api/__crudLecturer';
        var NIP = '<?php echo $NIP; ?>';

        var token = jwt_encode({action:'readMini',NIP:NIP},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {

            $('#viewPhoto').html('<img class="img-rounded" src="'+base_url_img_employee+''+jsonResult.Photo_new+'" />');


            $('#viewName').html(jsonResult.NIP+' - '+jsonResult.TitleAhead.trim()+' '+jsonResult.Name+' '+jsonResult.TitleBehind.trim()+' ' +
                            '<span style="float:right;"><button id="btnLoginPortal" data-nip="'+jsonResult.NIP+'" data-password="'+jsonResult.Password+'" class="btn btn-sm btn-primary" style="padding: 0px 5px;">Login Portal</button> | '+jsonResult.Division+' <i class="fa fa-angle-right"></i> <b>'+jsonResult.Position+'</b></span>');

            Lecturer_NIP = jsonResult.NIP.trim();

            var emailPU = (jsonResult.EmailPU!=null && jsonResult.EmailPU!='') ? jsonResult.EmailPU : '-';
            $('#viewEmailPU').html(emailPU);

            var emailOther = (jsonResult.Email!=null && jsonResult.Email!='') ? jsonResult.Email : '-';
            $('#viewEmailOther').html(emailOther);

            var Phone = (jsonResult.Phone!=null && jsonResult.Phone!='') ? jsonResult.Phone : '-';
            $('#viewPhone').html(Phone);

            var HP = (jsonResult.HP!=null && jsonResult.HP!='') ? jsonResult.HP : '-';
            $('#viewHP').html(HP);

            $('#viewAddress').html(jsonResult.Address.trim());
        });
    }

    function loadPage(token) {
        var url = base_url_js+'database/loadpagelecturersDetails';

        loading_page('#divpage');
        $.post(url,{token:token},function (html) {
            setTimeout(function () {
                $('#divPage').html(html);
            },500)
        });
    }

    $(document).on('click','#btnLoginPortal',function () {

        var username = $(this).attr('data-nip');
        var password = $(this).attr('data-password');

        var token = jwt_encode({username:username,password:password},'s3Cr3T-G4N');

        var url = base_url_portal_lecturers+'auth/loginFromAkademik?token='+token;
        PopupCenter(url,'xtf','1300','500');

    });


</script>


<!-- Input Score -->
<script>
    $(document).on('change','#formTotalAsg',function () {
        var valu = $('#formTotalAsg').val();

        $('.formAsg').prop('disabled',false);
        for(var d=(parseInt(valu)+1); d<=5;d++ ){
            $('.formAsg'+d).val(0).prop('disabled',true);
        }
        for(var i=0;i<dataIDSturyPlanning.length;i++){
            countScore(dataIDSturyPlanning[i]);
        }
    });


    $(document).on('keyup','.formScore',function () {
        var ID = $(this).attr('data-id');
        if($(this).val()>100){
            $(this).val(100);
        }
        countScore(ID);

    });
    $(document).on('change','.formScore',function () {
        var ID = $(this).attr('data-id');
        if($(this).val()>100){
            $(this).val(100);
        }
        countScore(ID);
    });
    $(document).on('blur','.formScore',function () {
        var ID = $(this).attr('data-id');
        if($(this).val()==''){
            $(this).val(0);
        }
        if($(this).val()>100){
            $(this).val(100);
        }
        countScore(ID);
    });

    $(document).on('click','#btnSaveScore',function () {
        var formUpdate = [];
        for(var i=0;i<dataIDSturyPlanning.length;i++){
            var da = {
                DB_Student : $('#db_student'+dataIDSturyPlanning[i]).val(),
                ID : dataIDSturyPlanning[i],
                dataForm : {
                    Evaluasi1 : $('#formAsg1'+dataIDSturyPlanning[i]).val(),
                    Evaluasi2 : $('#formAsg2'+dataIDSturyPlanning[i]).val(),
                    Evaluasi3 : $('#formAsg3'+dataIDSturyPlanning[i]).val(),
                    Evaluasi4 : $('#formAsg4'+dataIDSturyPlanning[i]).val(),
                    Evaluasi5 : $('#formAsg5'+dataIDSturyPlanning[i]).val(),
                    UTS : $('#formUTS'+dataIDSturyPlanning[i]).val(),
                    UAS : $('#formUAS'+dataIDSturyPlanning[i]).val(),
                    Score : $('#formScoreValue'+dataIDSturyPlanning[i]).val(),
                    Grade : $('#formGrade'+dataIDSturyPlanning[i]).val(),
                    GradeValue : $('#formGradeValue'+dataIDSturyPlanning[i]).val()
                }
            };

            formUpdate.push(da);
        }


        var url = base_url_js+'api/__crudScore';
        var token = jwt_encode({action:'update',formUpdate:formUpdate},'UAP)(*');
        $.post(url,{token:token},function (resultJson) {
            toastr.success('Score Saved','Success!');
        });

    });

    function countScore(ID) {
        var TotalAsg = $('#formTotalAsg').val();

        var TotalAsgValue = 0;
        for(var a=1;a<=TotalAsg;a++){
            var n = ($('#formAsg'+a+''+ID).val()!='') ? $('#formAsg'+a+''+ID).val() : 0;
            TotalAsgValue = TotalAsgValue + parseFloat(n);
        }

        // Misal => tugas 30%, UTS 35%, UAS 35%

        var AvgAsg = (parseFloat(TotalAsgValue) / parseInt(TotalAsg)) * (Grade_Assigment/100);
        var AvgUTS = parseFloat($('#formUTS'+ID).val()) * (Grade_UTS/100);
        var AvgUAS = parseFloat($('#formUAS'+ID).val()) * (Grade_UAS/100);

        var TotalScore = AvgAsg + AvgUTS + AvgUAS;
        var Score = parseFloat(TotalScore).toFixed(2);
        $('#score'+ID).html('<b>'+Score+'</b>');
        $('#formScoreValue'+ID).val(Score);

        var url = base_url_js+'api/__crudScore';
        var token = jwt_encode({action:'grade',Score:Score},'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {

            var color = '';
            if(jsonResult.Grade=='A' || jsonResult.Grade=='A-') {
                color = 'style="color:green;"';
            }
            else if(jsonResult.Grade=='B+' || jsonResult.Grade=='B' || jsonResult.Grade=='B-'){
                color = 'style="color:blue;"';
            }
            else if(jsonResult.Grade=='C+' || jsonResult.Grade=='C'){
                color = 'style="color:#dc8300;"';
            }
            else if(jsonResult.Grade=='D' || jsonResult.Grade=='E'){
                color = 'style="color:red;"';
            }

            $('#grade'+ID).html('<b '+color+'>'+jsonResult.Grade+'</b>');
            $('#formGrade'+ID).val(jsonResult.Grade);
            $('#formGradeValue'+ID).val(jsonResult.Score);

        });

    }
</script>

<!-- Grade -->
<script>
    $(document).on('click','.btnGrade',function () {

        var ScheduleID = $(this).attr('data-id');
        var ClassGroup = $(this).attr('data-group');

        var url = base_url_js+'api/__crudScore';

        var token = jwt_encode({action:'getGrade',ScheduleID:ScheduleID},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {

            var bodyGrade = '<div style="text-align:center;"><h3>Belum Input Grade</h3></div>';
            if(jsonResult.length>0){
                var dataG = jsonResult[0];

                var silabus = (dataG.Silabus!=null && dataG.Silabus!='') ? '<a target="_blank" href="'+base_url_portal_lecturers+'uploads/silabus/'+dataG.Silabus+'">Download Silabus</a>' : 'Belum Upload';
                var sap = (dataG.SAP!=null && dataG.SAP!='') ? '<a target="_blank" href="'+base_url_portal_lecturers+'uploads/sap/'+dataG.SAP+'">Download SAP</a>' : 'Belum Upload';
                var status = 'Not Yet Send Grade';
                var btnAct = 'disabled';
                var btnCheck = 'disabled';
                if(dataG.Status=='1') {
                    btnAct = '';
                    btnCheck = '';
                    status = 'Waiting Approval';
                } else if(dataG.Status=='2') {
                    btnCheck = '';
                    status = '<i class="fa fa-check-circle" style="color: green;"></i> Approved';
                }

                bodyGrade = '<h4>Silabus & SAP</h4>' +
                    '                    <table class="table">' +
                    '                        <tr>' +
                    '                            <td style="width: 50%;">'+silabus+'</td>' +
                    '                            <td style="width: 50%;">'+sap+'</td>' +
                    '                        </tr>' +
                    '                    </table>' +
                    '                    <h4>Grade</h4>' +
                    '                    <table class="table">' +
                    '                        <tr>' +
                    '                            <td style="width: 20%;">Assigment</td>' +
                    '                            <td style="width: 20%;">UTS</td>' +
                    '                            <td style="width: 20%;">UAS</td>' +
                    '                            <td style="width: 20%;">Status</td>' +
                    '                        </tr>' +
                    '                        <tr>' +
                    '                            <td>'+dataG.Assigment+' %</td>' +
                    '                            <td>'+dataG.UTS+' %</td>' +
                    '                            <td>'+dataG.UAS+' %</td>' +
                    '                            <td id="viewStatus'+dataG.ID+'">'+status+'</td>' +
                    '                        </tr>' +
                    '                        <tr>' +
                    '                            <td colspan="4" style="text-align: right;">' +
                    '                                <button data-id="'+dataG.ID+'" id="btnGradeApprove" class="btn btn-default btn-default-success" '+btnAct+'>Approved</button>' +
                    '                            </td>' +
                    '                        </tr>' +
                    '                    </table>' +
                    '                    <hr/>' +
                    '                    <div class="checkbox">' +
                    '                        <label>' +
                    '                            <input id="checkGradeAgain" type="checkbox" value="'+dataG.ID+'" '+btnCheck+'> Berikan Akses Untuk Input Ulang Silabus & SAP' +
                    '                        </label>' +
                    '                    </div>';


            } else {

            }

            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">'+ClassGroup+'</h4>');
            $('#GlobalModal .modal-body').html(bodyGrade);
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });

        });

    });

    $(document).on('click','#btnGradeApprove',function () {

        var ID = $(this).attr('data-id');
        loading_button('#btnGradeApprove');

        var url = base_url_js+'api/__crudScore';
        var token = jwt_encode({action:'gradeUpdate',ID:ID,Status:'2'},'UAP)(*');
        $.post(url,{token:token},function (result) {
            setTimeout(function () {
                $('#btnGradeApprove').html('Approved');
                $('#viewStatus'+ID).html('<i class="fa fa-check-circle" style="color: green;"></i> Approved');
                toastr.success('Grade Approved','Saved');
            },500);

        })
    });

    $(document).on('change','#checkGradeAgain',function () {
        var ID = $('#checkGradeAgain').val();
        var url = base_url_js+'api/__crudScore';
        var Status = '0';

       if($('#checkGradeAgain').is(':checked')){
           Status = '0';
           $('#btnGradeApprove').prop('disabled',true);
           $('#viewStatus'+ID).html('Not Yet Send Grade');
       } else {
           Status = '1';
           $('#btnGradeApprove').prop('disabled',false);
           $('#viewStatus'+ID).html('Waiting Approval');
       }

        var token = jwt_encode({action:'gradeUpdate',ID:ID,Status:Status},'UAP)(*');
        $.post(url,{token:token},function (result) {
            toastr.success('Grade Approved','Saved');
        });
    });
</script>

<script>
    function ref() {
        var data = {
            NIP : NIP,
            page : 'InputAttendance',
            ScheduleID : 1
        };
        var token = jwt_encode(data,'UAP)(*');

        loadPage(token);
    }
</script>

<!-- Attendance Lecturer -->
<script>
    $(document).on('click','.inputLecturerAttd',function () {
        var ID = $(this).attr('data-id');
        var No = $(this).attr('data-no');

        var url = base_url_js+'api/__crudAttendance';
        var data = {
            action : 'getAttdLecturers',
            ID : ID,
            No : No
        };
        var token = jwt_encode(data,'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {

            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Attendance '+No+'</h4>');

            var attd_nip = jsonResult.NIP;
            var attd_bap = (jsonResult.BAP!='' && jsonResult.BAP!=null) ? jsonResult.BAP : '';
            // var attd_date = (jsonResult.Date!='' && jsonResult.Date!=null) ? jsonResult.Date : '';
            var attd_in = (jsonResult.In!='' && jsonResult.In!=null) ? jsonResult.In : '';
            var attd_out = (jsonResult.Out!='' && jsonResult.Out!=null) ? jsonResult.Out : '';


            var body_attd = '<div class="row">' +
                '                        <div class="col-xs-4">' +
                '                            <div class="form-group">' +
                '                                <label>Date</label>' +
                '                               <input type="text" id="formDate" class="form-control form-attd" readonly>' +
                '                            </div>' +
                '                            <div class="form-group">' +
                '                                <label>In</label>' +
                '                                <div id="inputIn" class="input-group">' +
                '                                    <input data-format="hh:mm:ss" type="text" id="formIn" class="form-control form-attd" value="'+attd_in+'" readonly/>' +
                '                                    <span class="add-on input-group-addon">' +
                '                                        <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>' +
                '                                    </span>' +
                '                                </div>' +
                '                            </div>' +
                '                            <div class="form-group">' +
                '                                <label>Out</label>' +
                '                                <div id="inputOut" class="input-group">' +
                '                                    <input data-format="hh:mm:ss" type="text" id="formOut" class="form-control form-attd" value="'+attd_out+'" readonly/>' +
                '                                    <span class="add-on input-group-addon">' +
                '                                      <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>' +
                '                                    </span>' +
                '                                </div>' +
                '                            </div>' +
                '                        </div>' +
                '                        <div class="col-xs-8">' +
                '                            <div class="form-group">' +
                '                                <label>BAP</label>' +
                '                                <textarea class="form-control" id="formBAP" rows="9">'+attd_bap+'</textarea>' +
                '                            </div>' +
                '                        </div>' +
                '                    </div>';

            $('#GlobalModal .modal-body').html(body_attd);



            $('#inputIn').datetimepicker({
                pickDate: false
            });

            $('#inputOut').datetimepicker({
                pickDate: false
            });

            $("#formDate").datepicker({
                showOtherMonths:true,
                autoSize: true,
                dateFormat: 'dd MM yy'
                // minDate: new Date(moment().year(),moment().month(),moment().date()),
                // onSelect : function () {
                //     var data_date = $(this).val().split(' ');
                //     var nextelement = $(this).attr('nextelement')
                //     nextDatePick(data_date,nextelement);
                // }
            });

            if(jsonResult.Date !=='0000-00-00' && jsonResult.Date != null){
                var d = new Date(jsonResult.Date);

                $('#formDate').datepicker('setDate',d);
            } else {

            }



            $('a.ui-state-default').attr('href','javascript:void(0)');

            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> ' +
                '<button class="btn btn-success" id="btnSaveAttdLecturer" data-no="'+No+'" data-id="'+ID+'">Save</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        });

    });

    $(document).on('click','#btnSaveAttdLecturer',function () {
        var ID = $(this).attr('data-id');
        var No = $(this).attr('data-no');
        var NIP = '<?php echo $NIP; ?>';

        var formDate = $('#formDate').datepicker("getDate");
        var formIn = $('#formIn').val();
        var formOut = $('#formOut').val();
        var formBAP = $('#formBAP').val();


        if(formDate!=null && formDate!='' &&
            formIn!=null && formIn!='' &&
            formOut!=null && formOut!='' &&
            formBAP!=null && formBAP!=''){

            $('#formDate,#formIn,#formOut,#formBAP').prop('disabled',true);
            loading_buttonSm('#btnSaveAttdLecturer');

            var url = base_url_js+'api/__crudAttendance';
            var data = {
                action : 'UpdtAttdLecturers',
                ID : ID,
                No : No,
                formUpdate : {
                    NIP : NIP,
                    Date : moment(formDate).format('YYYY-MM-DD'),
                    In : formIn,
                    Out : formOut,
                    BAP : formBAP
                }
            };
            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (resultJson) {
                getDataAttendance();
                toastr.success('Data Saved','Success');
                $('#GlobalModal').modal('hide');
            });

        } else {
            toastr.warning('Form Required','Warning');
        }


    });

</script>

<!-- Schedule Replacement -->
<script>
    $(document).on('click','.inputReplacementSchedule',function () {
        var ID = $(this).attr('data-id');
        var No = $(this).attr('data-no');

        var body_attd = 'ok';

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Attendance '+No+'</h4>');

        $('#GlobalModal .modal-body').html(body_attd);
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> ' +
            '<button class="btn btn-success">Save</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });
</script>

