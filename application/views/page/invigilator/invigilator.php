
<style>
    .tb-profile{
        border-top: 7px solid #03a9f4;
        border-radius: 0px;
        padding: 15px;
        /*padding-top: 30px;*/
    }
    .tb-sch {
        border-top: 7px solid #ff5722;
        border-radius: 0px;
        padding: 10px;
        margin-bottom: 15px;
    }
    .tb-sch h3{
        font-weight: bold;
        border-left: 9px solid #ff5722;
        padding-left: 7px;
    }

    .tb-sch-list {
        border-top: 7px solid #ffc107;
        border-radius: 0px;
        padding: 10px;
        margin-bottom: 15px;
    }

    .tb-sch-list h3 {
        font-weight: bold;
        border-left: 9px solid #ffc107;
        padding-left: 7px;
    }

    #table-sch-inv thead tr th, #table-sch-inv-today thead tr th {
        text-align: center;
        background: #437e88;
        color: #ffffff;
    }

    #table-sch-inv tbody tr td,#table-sch-inv-today tbody tr td {
        text-align: center;
    }
    .img-profile {
        max-width: 100px;
        border: 2px solid #CCCCCC;
        padding: 3px;
    }
</style>


<div class="container" style="margin-top: 30px;">
    <div class="row">

        <div class="col-md-12" style="text-align: center;margin-bottom: 20px;">
            <img src="<?php echo base_url('images/logo-header-hitam-putih.png'); ?>" style="max-width: 300px;">
        </div>

        <div class="col-md-2">
            <div class="thumbnail tb-profile animated fadeInLeft" style="min-height: 100px;">
                <div style="text-align: center;">
                    <?php $imgProfile = (file_exists('./uploads/employees/'.$this->session->userdata('Photo')))
                        ? url_pas.'uploads/employees/'.$this->session->userdata('Photo') :
                        url_pas.'images/icon/no_image.png';
                    $name = (strlen($this->session->userdata('Name'))>15) ? substr($this->session->userdata('Name'),0,14).'_' : $this->session->userdata('Name'); ?>
                    <img src="<?php echo $imgProfile; ?>" style="width: 100%;max-width: 100px;padding:5px;border:1px solid #CCCCCC;">
                    <h3 style="margin-bottom: 0px;"><b><?php echo $name; ?></b></h3>
                    <h5 style="margin-top: 3px;color: #009688;"><?php echo $this->session->userdata('NIP'); ?></h5>



                    <hr style="margin-bottom: 5px;"/>
                    <button id="btnLogOutInv" class="btn btn-danger">Sign Out Now</button>
                    <hr style="margin-bottom: 7px;margin-top: 5px;"/>
                    <div class="alert alert-warning" role="alert">

                        Auto Sign Out <br/> active on <b id="viewCD" >-</b></div>
                </div>
            </div>
            <div style="color: #FFFFFF;margin-top: 10px;">
                Login at : <?php echo date('d M Y h:i:s',strtotime($this->session->userdata('LoginAt'))); ?>
            </div>
        </div>
        <div class="col-md-10">
            <div class="thumbnail tb-sch  animated flipInX" style="min-height: 100px;">
                <div class="pull-right" style="color: #2196f3;">
                    <span id="dateToday"></span>
                </div>

                <h3>Invigilator Schedule</h3>

                <hr/>

                <div class="col-md-8 col-md-offset-2">
                    <div class="well" style="padding-bottom: 5px;">
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-control form-filter-inv" id="filterTypeSemester">
                                        <option value="1">Semester Reguler</option>
                                        <option value="2">Semester Antara</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-control form-filter-inv" id="filterSemester">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-control form-filter-inv" id="filterTypeExam">
                                        <option value="uts">UTS</option>
                                        <option value="uas">UAS</option>
                                        <option disabled>-- Make-up Exams --</option>
                                        <option value="re_uts" style="color: orangered;">Make-up UTS</option>
                                        <option value="re_uas" style="color: orangered;">Make-up UAS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div style="margin-top: 20px;">

                    <table class="table table-bordered table-striped" id="table-sch-inv">
                        <thead>
                        <tr>
                            <th style="width: 1%;">No</th>
                            <th style="width: 7%;">Group</th>
                            <th>Course</th>
                            <th>Day, Date Time</th>
                            <th style="width: 10%;">Room</th>
                            <th style="width: 10%;">Attendance</th>
                            <th style="width: 10%;">Layout</th>
                        </tr>
                        </thead>
                        <tbody id="dataInvSchedule"></tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
</div>

<script>
    $(document).ready(function () {

        loSelectOptionSemester('#filterSemester','');

        var firstLoad = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            var filterTypeExam = $('#filterTypeExam').val();
            if(filterTypeExam!='' && filterTypeExam!=null && filterSemester!='' && filterSemester!=null){
                loadDataScheduleInvigilator();
                clearInterval(firstLoad);
            }
        },1000);

        var sessionLoginAt = "<?php echo $this->session->userdata('LoginAt'); ?>";
        var endSesi = sessionLoginAt.split(' ')[1];

        // countdw('#viewCD',endSesi);
        // timeOutCw(endSesi);
    });

    $('#filterTypeSemester').change(function () {

        var filterTypeSemester = $('#filterTypeSemester').val();
        $('#filterSemester').empty();
        if(filterTypeSemester==1){
            loSelectOptionSemester('#filterSemester','');
        } else {
            loSelectOptionSemesterAntara('#filterSemester','');
        }

        var firstLoad = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            var filterTypeExam = $('#filterTypeExam').val();
            if(filterTypeExam!='' && filterTypeExam!=null && filterSemester!='' && filterSemester!=null){
                loadDataScheduleInvigilator();
                clearInterval(firstLoad);
            }
        },1000);

    });

    $('.form-filter-inv').change(function () {
        loadDataScheduleInvigilator();
    });

    $('#btnLogOutInv').click(function () {
        outInv();
    });

    function countdw(element,EndSessions) {

        var ens = EndSessions.split(':');
        var start = moment();
        var end   = moment().hours(ens[0]).minutes(ens[1]).seconds(ens[2]).add(15,'minutes');

        var en = moment().valueOf();
        var d = end.diff(start);
        var fiveSeconds = parseInt(en) + parseInt(d);

        if(d<=0){
            outInv();
        } else {
            $(element)
                .countdown(fiveSeconds, function(event) {
                    $(this).text(
                        // event.strftime('%D days %H:%M:%S')
                        event.strftime('%H:%M:%S')
                    );
                });


        }


    }

    function timeOutCw(EndSessions) {
        var ens = EndSessions.split(':');
        var t_start = moment().unix();
        var t_end = moment().hours(ens[0]).minutes(ens[1]).seconds(ens[2]).add(15,'minutes').unix();
        var timeOut = (parseInt(t_end) - parseInt(t_start))*1000;

        setTimeout(function () {
            outInv();
        },timeOut);
    }

    function loadDataScheduleInvigilator() {

        var filterTypeSemester = $('#filterTypeSemester').val();

        var filterTypeExam = $('#filterTypeExam').val();
        var filterSemester = $('#filterSemester').val();

        if(filterSemester!='' && filterSemester!=null && filterTypeExam!='' && filterTypeExam!=null){

            var SemesterID = filterSemester.split('.')[0];

            var url = base_url_js+'api/__crudInvigilator';
            var token = jwt_encode({action:'readScheduleInvigilator',TypeSemester:filterTypeSemester,SemesterID:SemesterID,TypeExam:filterTypeExam,NIP : sessionNIP},'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {

                $('#dataInvSchedule').empty();
                if(jsonResult.length>0){
                    var no = 0;
                    for(var i=0;i<jsonResult.length;i++){
                        var d = jsonResult[i];
                        var ddt = moment(d.ExamDate).format('dddd, DD MMM YYYY');
                        var time = d.ExamStart.substr(0,5)+' - '+d.ExamEnd.substr(0,5);

                        no+=1;

                        var btnAttd = (d.ButtonAttendance==1 || d.ButtonAttendance=='1')
                            ? '<button data-id="'+d.ID+'" class="btn btn-primary btnLoadAttendance">Attendance</button>' : '-';

                        var btnLayout = (d.ButtonAttendance==1 || d.ButtonAttendance=='1')
                            ? '<a href="'+base_url_js+'save2pdf/exam-layout/'+filterTypeSemester+'/'+d.ID+'" target="_blank" class="btn btn-default btn-sm btn-default-success"><i class="fa fa-arrows-alt margin-right"></i> Layout</a>'
                            : '';

                        var OnlineLearning = (d.OnlineLearning !== 'undefined' && (d.OnlineLearning==1 || d.OnlineLearning=='1'))
                            ? '<div><span class="label label-success">Online</span></div>' : '';

                        var StartOnlineLearning = ((d.ButtonAttendance==1 || d.ButtonAttendance=='1') && (d.OnlineLearning !== 'undefined' && (d.OnlineLearning==1 || d.OnlineLearning=='1')))
                            ? '<td colspan="2"><button class="btn btn-success btnEnterTheClass" data-tkn="'+jwt_encode(d,'s3Cr3T-G4N')+'" >Enter the class</button></td>'
                            : '<td>'+btnAttd+'</td>' +
                            '<td>'+btnLayout+'</td>';

                        $('#dataInvSchedule').append('<tr>' +
                            '<td>'+no+'</td>' +
                            '<td>'+d.ClassGroup+OnlineLearning+'</td>' +
                            '<td style="text-align: left;">'+d.MKCode+' - '+d.CourseEng+'</td>' +
                            '<td style="text-align: right">'+ddt+'<br/><b>'+time+'</b></td>' +
                            '<td>'+d.Room+'</td>' +StartOnlineLearning+
                            '</tr>');

                    }


                }
                else {
                    $('#dataInvSchedule').append('<tr>' +
                        '<td colspan="7" style="text-align: center;">--- Data Not Yet ---</td>' +
                        '</tr>');
                }


            });

        }
    }

    $(document).on('click','.btnEnterTheClass',function () {


        loading_modal_show();

       var tkn = $(this).attr('data-tkn');
       var d = jwt_decode(tkn,'s3Cr3T-G4N');

       var token = jwt_encode(
           {
               action : 'insertInvigilator',
               dataForm : {
                   ExamID : d.ID,
                   NIP : sessionNIP
               }
           },'s3Cr3T-G4N');

       var url = base_url_js+'api4/__crudExamOnline';

       $.post(url,{token:token},function () {

           var tkn = jwt_encode({
               ExamID : d.ID,
               CourseEng : d.CourseEng,
               ClassGroup : d.ClassGroup
           },'s3Cr3T-G4N');

           setTimeout(function () {
               loading_modal_hide();
               window.location.replace(base_url_js+'invigilator/detail-exam/'+tkn);
           },500);



       });

    });

    $(document).on('click','.btnLoadAttendance',function () {
        var ID = $(this).attr('data-id');

        var data = {
            action : 'readAttendanceExam',
            ID : ID,
            TypeSemester : filterTypeSemester
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudAttendance2';

        $.post(url,{token:token},function (jsonResult) {
            // console.log(jsonResult);

            $('#GlobalModal .modal-header').html('<h4 class="modal-title">Students Attendance <input class="hide" id="totalStudents" value="'+jsonResult.length+'" ></h4>');
            $('#GlobalModal .modal-body').html('<div class="row">' +
                '    <div class="col-md-12">' +
                '       <div>' +
                '<table class="table">' +
                '    <thead>' +
                '    <tr>' +
                '        <th style="width: 1%;">No</th>' +
                '        <th>Student</th>' +
                '        <th style="width: 15%;">Attendace</th>' +
                '    </tr>' +
                '    </thead>' +
                '    <tbody id="dataStudent">' +
                '    </tbody>' +
                '</table>' +
                '</div>' +
                '        <hr>' +
                '        <div style="text-align: right;">' +
                '            <button type="button" class="btn btn-success" id="btnSubmitAttdStd" disabled>Submit</button> | ' +
                '            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
                '        </div>' +
                '    </div>' +
                '</div>');

            if(jsonResult.length>0){
                var no = 1;
                $.each(jsonResult,function (i, v) {

                    var ckcAttd = (v.Status==1 || v.Status=='1') ? 'checked' : '';

                    $('#dataStudent').append('<tr id="trAttd'+no+'">' +
                        '<td style="text-align: center;color: #333;">'+no+'</td>' +
                        '<td>' +
                        '   <span style="font-size: 17px;">'+v.Name+'</span><br/>'+v.NPM+'' +
                        '<input id="formID'+no+'" class="hide" value="'+v.EXDID+'"/>' +
                        '</td>' +
                        '<td>' +
                        '<div class="checkbox checbox-switch switch-success ">' +
                        '    <label>' +
                        '        <input type="checkbox" class="checkAttdStd" id="formAttd'+no+'" '+ckcAttd+'>' +
                        '        <span></span>' +
                        '    </label>' +
                        '</div>' +
                        '</td>' +
                        '</tr>');

                    no +=1;
                });
            } else {
                $('#dataStudent').append('<tr><td colspan="3" style="text-align: center;">-- Student Not Yet --</td></tr>');
                $('#btnSubmitAttdStd').remove();
            }

            checkAttendance();

            $('.checkAttdStd').change(function () {
                checkAttendance();
            });

            $('#btnSubmitAttdStd').prop('disabled',false)

            $('#btnSubmitAttdStd').click(function () {

                loading_buttonSm('#btnSubmitAttdStd');
                $('button[data-dismiss=modal]').prop('disabled',true);

                var arrAttd = [];

                var totalStudents = $('#totalStudents').val();
                for(var i=1;i<=parseInt(totalStudents);i++){
                    var ExamDetailsID = $('#formID'+i).val();
                    var arr = {
                        ExamDetailsID : ExamDetailsID,
                        Status : ($('#formAttd'+i).is(':checked')) ? '1' : '-1'
                    };

                    arrAttd.push(arr);
                }

                var data2 = {
                    action : 'insertAttdExam',
                    arrAttd : arrAttd,
                    TypeSemester : filterTypeSemester
                };

                var token2 = jwt_encode(data2,'UAP)(*');
                $.post(url,{token:token2},function (result) {
                    toastr.success('Attendance saved','Success');
                    setTimeout(function () {
                        // $('#btnSubmitAttdStd').html('Submit');
                        // $('button[data-dismiss=modal],#btnSubmitAttdStd').prop('disabled',false);

                        $('#GlobalModal').modal('hide');
                    },500);
                })


            });


            $('#GlobalModal .modal-footer').addClass('hide');

            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });

        })


    });

    function checkAttendance() {
        var totalStudents = $('#totalStudents').val();
        for(var i=1;i<=parseInt(totalStudents);i++){
            if($('#formAttd'+i).is(':checked')){
                $('#trAttd'+i).css({
                    'color' : '#333',
                    'background' : '#fff'
                });
            } else {
                $('#trAttd'+i).css({
                    'color':'red',
                    'background' : '#ff00001c'
                });
            }
        }
    }

    function outInv() {
        loading_button('#btnLogOutInv');
        var url = base_url_js+"auth/logMeOut";
        $.post(url,function (result) {
            setTimeout(function () {
                window.location.href = base_url_sign_out;
            },2000);
        });
    }
</script>