
<style>
    #dataTableSchedule tr th, #dataTableSchedule tr td{
        text-align: center;
    }
    #tableStdAttd thead th, #tableStdAttd tbody td {
        text-align: center;
    }
    .btn-delete-attd {
        padding: 1px 5px;
        border-radius: 12px;
    }
</style>

<div class="col-md-12">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="thumbnail">
                <div class="row">
                    <div class="col-xs-4">
                        <select class="form-control" id="filterAttendanceType">
<!--                            <option selected disabled>--- Type ---</option>-->
                            <option value="0">Timetable</option>
                            <option value="1">UTS</option>
                            <option value="2">UAS</option>
                        </select>
                    </div>
                    <div class="col-xs-8">
                        <select class="form-control" id="filterAttendance">
                            <option></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr/>
</div>

<div class="col-md-12">
    <div id="dataShow"></div>
</div>

<script>
    $(document).ready(function () {
        window.totalMhs = 0;
        getSelectOptionAttendance();
        setTimeout(function () {
            getDataAttendance();
        },500);
    });

    $('#filterAttendanceType').change(function () {

        var type = $('#filterAttendanceType').val();

        if(type==0){
            getSelectOptionAttendance();
        } else {
            var opt = $('#filterAttendance');
            opt.empty();
            opt.prop('disabled',true)
        }

    });

    $('#filterAttendance').change(function () {
        getDataAttendance();
    });

    function getDataAttendance() {
        var type = $('#filterAttendanceType').val();
        var DataAttendance = $('#filterAttendance').val();

        if(type==0 && DataAttendance!='' && DataAttendance!=null){

            var AttendanceID = DataAttendance.split('.')[0];


            var url = base_url_js+'api/__crudAttendance';
            var data = {
                action : 'getAttendance',
                AttendanceID : AttendanceID
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {

                var htmlTable = '<table class="table table-bordered" id="dataTableSchedule" style="margin-bottom: 70px !important;">' +
                    '        <thead>' +
                    '        <tr style="background-color: #436888;color: #ffffff;">' +
                    '            <th rowspan="2"  style="width: 1%;">Meet</th>' +
                    '            <th rowspan="2"  style="width: 5%;"><i class="fa fa-exchange"></i></th>' +
                    '            <th rowspan="2" >Lecturer</th>' +
                    '            <th colspan="2">Students</th>' +
                    '            <th rowspan="2" style="width: 5%;">Action</th>' +
                    '            <th rowspan="2" style="width: 15%;">BAP</th>' +
                    '            <th rowspan="2" style="width: 15%;">Note For Venue</th>' +
                    '            <th rowspan="2" style="width: 15%;">Note For Equipment</th>' +
                    '        </tr>' +
                    '        <tr style="background-color: #436888;color: #ffffff;">' +
                    '            <th style="width: 3%;background: #438848;">P</th>' +
                    '            <th style="width: 3%;background: #884343;">A</th>' +
                    '        </tr>' +
                    '        </thead>' +
                    '        <tbody id="dataRwSchedule">' +
                    '        </tbody>' +
                    '    </table><hr/>';

                $('#dataShow').html(htmlTable);

                var dataAttd = jsonResult[0];

                var tr = $('#dataRwSchedule');

                for(var a=1;a<=14;a++){
                    var bg_td = (a%2==1) ? "background-color:#fafafa;" : "background-color:#ffffff;" ;

                    var Sc_Ex_Status = dataAttd['ScheduleExchange_Status'+a];

                    var btn_Sc_Ex_Status = '-';
                    if(Sc_Ex_Status=='1') {
                        btn_Sc_Ex_Status = '<i class="fa fa-exchange fa-2x" aria-hidden="true" style="color: orangered;"></i>';
                    } else if (Sc_Ex_Status=='0'){
                        btn_Sc_Ex_Status = '<i class="fa fa-question-circle fa-2x" aria-hidden="true" style="color: blue;"></i>';
                    }

                    var btnAct = '<div class="btn-group">' +
                        '  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        '    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="caret"></span>' +
                        '  </button>' +
                        '  <ul class="dropdown-menu">' +
                        '    <li><a href="javascript:void(0);" class="inputLecturerAttd" data-no="'+a+'" data-id="'+dataAttd['ID']+'">Input Attendance Lecturer</a></li>' +
                        '    <li><a href="javascript:void(0);" class="inputStudentAttd" data-no="'+a+'" data-id="'+dataAttd['ID']+'">Input Attendance Students</a></li>' +
                        '    <li role="separator" class="divider"></li>' +
                        '    <li><a href="javascript:void(0);" class="inputScheduleExchange" data-no="'+a+'" data-id="'+dataAttd['ID']+'">Set Schedule Exchange</a></li>' +
                        '  </ul>' +
                        '</div>';

                    var BAP = (dataAttd['BAP'+a]!='' && dataAttd['BAP'+a]!=null) ? dataAttd['BAP'+a] : '-' ;

                    if(dataAttd['AttdLecturers'+a].length>0){
                        var rw_span = (dataAttd['AttdLecturers'+a].length);
                           tr.append('<tr style="'+bg_td+'">' +
                               '            <td rowspan="'+rw_span+'">'+a+'</td>' +
                               '            <td rowspan="'+rw_span+'">'+btn_Sc_Ex_Status+'</td>' +
                               '            <td style="text-align: left;"><b>'+dataAttd['AttdLecturers'+a][0].Name+'</b>' +
                               '                <span style="float: right;">'+moment(dataAttd['AttdLecturers'+a][0].Date).format('dddd, DD MMM YYYY')+' | '+dataAttd['AttdLecturers'+a][0].In.substr(0,5)+' - '+dataAttd['AttdLecturers'+a][0].Out.substr(0,5)+' | ' +
                               '                <button class="btn btn-sm btn-default btn-default-danger btn-delete-attd" data-id="'+dataAttd['AttdLecturers'+a][0].ID+'"><i  class="fa fa-minus-circle"></i></button></span>'+
                               '            </td>' +
                               '            <td rowspan="'+rw_span+'">'+dataAttd['S_P'+a]+'</td>' +
                               '            <td rowspan="'+rw_span+'">'+dataAttd['S_A'+a]+'</td>' +
                               '            <td rowspan="'+rw_span+'">'+btnAct+'</td>' +
                               '            <td rowspan="'+rw_span+'" style="text-align: left;">'+BAP+'</td>' +
                               '            <td rowspan="'+rw_span+'" style="text-align: left;background: #ffc1072e;"></td>' +
                               '            <td rowspan="'+rw_span+'" style="text-align: left;background: #8bc34a73;"></td>' +
                               '        </tr>');

                           for(var lecA=1;lecA<dataAttd['AttdLecturers'+a].length;lecA++){
                               tr.append('<tr  style="'+bg_td+'">' +
                                   '<td style="text-align: left;"><b>'+dataAttd['AttdLecturers'+a][lecA].Name+'</b>' +
                                   '<span style="float: right;">'+moment(dataAttd['AttdLecturers'+a][lecA].Date).format('dddd, DD MMM YYYY')+' | '+dataAttd['AttdLecturers'+a][lecA].In.substr(0,5)+' - '+dataAttd['AttdLecturers'+a][lecA].Out.substr(0,5)+' | ' +
                                   '<button class="btn btn-sm btn-default btn-default-danger btn-delete-attd" data-id="'+dataAttd['AttdLecturers'+a][0].ID+'"><i  class="fa fa-minus-circle"></i></button></span>'+
                                   '</td>' +
                                   '</tr>');
                           }


                    } else {
                        tr.append('<tr  style="'+bg_td+'">' +
                            '            <td>'+a+'</td>' +
                            '            <td>'+btn_Sc_Ex_Status+'</td>' +
                            '            <td style="text-align: left;">-</td>' +
                            '            <td>'+dataAttd['S_P'+a]+'</td>' +
                            '            <td>'+dataAttd['S_A'+a]+'</td>' +
                            '            <td>'+btnAct+'</td>' +
                            '            <td>'+BAP+'</td>' +
                            '            <td rowspan="" style="text-align: left;background: #ffc1072e;"></td>' +
                            '            <td rowspan="" style="text-align: left;background: #8bc34a73;"></td>' +
                            '        </tr>');
                    }



                }

            });
        }

    }

    function getSelectOptionAttendance() {
        var ScheduleID = '<?php echo $ScheduleID; ?>';

        var url = base_url_js+'api/__crudAttendance';
        var data = {
            action : 'read',
            ScheduleID : ScheduleID
        };
        var token = jwt_encode(data,'UAP)(*');
        var opt = $('#filterAttendance');
        opt.prop('disabled',false)
        $.post(url,{token:token},function (jsonResult) {

            opt.empty();
            if(jsonResult.length>0){
                for(var i=0;i<jsonResult.length;i++){
                    var dataR = jsonResult[i];
                    var time = dataR.StartSessions.substr(0,5)+' - '+dataR.EndSessions.substr(0,5);
                    opt.append('<option value="'+dataR.AttendanceID+'.'+dataR.ScheduleID+'.'+dataR.ID+'.'+dataR.Credit+'.'+dataR.TimePerCredit+'">'+dataR.DayNameEng+', '+time+' | Ruang '+dataR.Room+'</option>');
                }
            }
        });
    }

</script>

