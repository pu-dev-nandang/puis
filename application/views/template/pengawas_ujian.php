<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<!--    <meta http-equiv="X-UA-Compatible" content="ie=edge">-->
    <title>Invigilator Page | Podomoro University</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('images/icon/favicon.png'); ?>">

    <?php echo $include; ?>

    <style>
        body {
            font-family: 'Noto Sans', sans-serif;
            background: #607d8b;
        }
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

</head>

<body>

<!--<pre>-->
<!--    --><?php //print_r($this->session->all_userdata()); ?>
<!--</pre>-->

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
                    <img src="<?php echo $imgProfile; ?>" class="img-rounded img-profile">
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

                <div class="col-md-6 col-md-offset-3">
                    <div class="well">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control form-filter-inv" id="filterSemester">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control form-filter-inv" id="filterTypeExam">
                                        <option value="uts">UTS</option>
                                        <option value="uas">UAS</option>
                                        <option disabled>-- Make-up Exams --</option>
                                        <option value="re_uts">UTS</option>
                                        <option value="re_uas">UAS</option>
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

<!--        <div class="col-md-4">-->
<!--            <div class="thumbnail tb-sch-list  animated flipInX" style="min-height: 100px;">-->
<!---->
<!--                <h3>Today</h3>-->
<!---->
<!--                <div style="margin-top: 20px;">-->
<!---->
<!--                    <table class="table table-bordered" id="table-sch-inv-today">-->
<!--                        <thead>-->
<!--                        <tr>-->
<!--                            <th style="width: 5%;">No</th>-->
<!--                            <th>Time</th>-->
<!--                            <th style="width: 20%;">Room</th>-->
<!--                            <th style="width: 5%;">Action</th>-->
<!--                        </tr>-->
<!--                        </thead>-->
<!--                        <tbody id="dataInvScheduleToday"></tbody>-->
<!--                    </table>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->

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
        var filterTypeExam = $('#filterTypeExam').val();
        var filterSemester = $('#filterSemester').val();

        if(filterSemester!='' && filterSemester!=null && filterTypeExam!='' && filterTypeExam!=null){

            var SemesterID = filterSemester.split('.')[0];

            var url = base_url_js+'api/__crudInvigilator';
            var token = jwt_encode({action:'readScheduleInvigilator',SemesterID:SemesterID,TypeExam:filterTypeExam,NIP : sessionNIP},'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {

                $('#dataInvSchedule,#dataInvScheduleToday').empty();
                if(jsonResult.length>0){
                    var no = 0;
                    var noToday = 0;
                    var today = moment().format('YYYY-MM-DD');
                    for(var i=0;i<jsonResult.length;i++){
                        var d = jsonResult[i];
                        var ddt = moment(d.ExamDate).format('dddd, DD MMM YYYY');
                        var time = d.ExamStart.substr(0,5)+' - '+d.ExamEnd.substr(0,5);

                        no+=1;
                        $('#dataInvSchedule').append('<tr>' +
                            '<td>'+no+'</td>' +
                            '<td>'+d.ClassGroup+'</td>' +
                            '<td style="text-align: left;">'+d.MKCode+' - '+d.CourseEng+'</td>' +
                            '<td style="text-align: right">'+ddt+'<br/><b>'+time+'</b></td>' +
                            '<td>'+d.Room+'</td>' +
                            '<td><button data-id="'+d.ID+'" class="btn btn-primary btnLoadAttendance">Attendance</button></td>' +
                            '<td><a href="'+base_url_js+'save2pdf/exam-layout/'+d.ID+'" target="_blank" class="btn btn-default btn-sm btn-default-success"><i class="fa fa-arrows-alt margin-right"></i> Layout</a></td>' +
                            '</tr>');

                        // if(today==d.ExamDate){
                        //     noToday+=1;
                        //     $('#dataInvScheduleToday').append('<tr>' +
                        //         '<td>'+noToday+'</td>' +
                        //         '<td>'+time+'</td>' +
                        //         '<td>'+d.Room+'</td>' +
                        //         '<td><a href="'+base_url_js+'save2pdf/exam-layout/'+d.ID+'" target="_blank" class="btn btn-default btn-sm btn-default-success"><i class="fa fa-arrows-alt margin-right"></i> Layout</a></td>' +
                        //         '</tr>');
                        // }

                    }

                    $('.btnLoadAttendance').click(function () {
                        var ID = $(this).attr('data-id');
                        
                        var data = {
                            action : 'readAttendanceExam',
                            ID : ID
                        };
                        
                        var token = jwt_encode(data,'UAP)(*');
                        var url = base_url_js+'api2/__crudAttendance2';
                        
                        $.post(url,{token:token},function (jsonResult) {
                            console.log(jsonResult);

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
                                        '<input id="formID'+no+'" class="hide" value="'+v.ID+'"/>' +
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
                                    arrAttd : arrAttd
                                };

                                var token2 = jwt_encode(data2,'UAP)(*');
                                $.post(url,{token:token2},function (result) {
                                    toastr.success('Attendance saved','Success');
                                    setTimeout(function () {
                                        $('#btnSubmitAttdStd').html('Submit');
                                        $('button[data-dismiss=modal],#btnSubmitAttdStd').prop('disabled',false);
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

                    // if(noToday==0){
                    //     $('#dataInvScheduleToday').append('<tr>' +
                    //         '<td colspan="4" style="text-align: center;">--- Data Not Yet ---</td>' +
                    //         '</tr>');
                    // }
                } else {
                    $('#dataInvSchedule').append('<tr>' +
                        '<td colspan="5" style="text-align: center;">--- Data Not Yet ---</td>' +
                        '</tr>');
                    $('#dataInvScheduleToday').append('<tr>' +
                        '<td colspan="4" style="text-align: center;">--- Data Not Yet ---</td>' +
                        '</tr>');
                }


            });

        }
    }

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


<div class="modal fade" id="GlobalModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
</html>