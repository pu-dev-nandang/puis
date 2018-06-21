
<style>
    #dataTableSchedule tr th, #dataTableSchedule tr td{
        text-align: center;
    }
    #tableStdAttd thead th, #tableStdAttd tbody td {
        text-align: center;
    }
</style>

<div class="col-md-12">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="thumbnail">
                <div class="row">
                    <div class="col-xs-4">
                        <select class="form-control" id="filterAttendanceType">
                            <option selected disabled>--- Type ---</option>
                            <option value="0">Schedule</option>
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
    });

    $('#filterAttendanceType').click(function () {

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

                var htmlTable = '<table class="table table-bordered table-striped" id="dataTableSchedule">' +
                    '        <thead>' +
                    '        <tr style="background-color: #436888;color: #ffffff;">' +
                    '            <th rowspan="2"  style="width: 5%;">Attd</th>' +
                    '            <th rowspan="2"  style="width: 5%;"><i class="fa fa-exchange"></i></th>' +
                    '            <th colspan="2">Lecturer</th>' +
                    '            <th colspan="2">Students</th>' +
                    '            <th rowspan="2" style="width: 10%;">Action</th>' +
                    '            <th rowspan="2">BAP</th>' +
                    '        </tr>' +
                    '        <tr style="background-color: #436888;color: #ffffff;">' +
                    '            <th style="width: 25%;">Name</th>' +
                    '            <th style="width: 20%;">Date</th>' +
                    // '            <th style="width: 5%;">In</th>' +
                    // '            <th style="width: 5%;">Out</th>' +
                    '            <th style="width: 5%;background: #438848;">P</th>' +
                    '            <th style="width: 5%;background: #884343;">A</th>' +
                    '        </tr>' +
                    '        </thead>' +
                    '        <tbody id="dataRwSchedule">' +
                    '        </tbody>' +
                    '    </table>';

                $('#dataShow').html(htmlTable);

                var dataAttd = jsonResult[0];


                var tr = $('#dataRwSchedule');
                for(var a=1;a<=14;a++){

                    var btnAct = '<div class="btn-group">' +
                        '  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        '    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="caret"></span>' +
                        '  </button>' +
                        '  <ul class="dropdown-menu">' +
                        '    <li><a href="javascript:void(0);" class="inputLecturerAttd" data-no="'+a+'" data-id="'+dataAttd['ID']+'">Attendance Lecturer</a></li>' +
                        '    <li><a href="javascript:void(0);" class="inputStudentAttd" data-no="'+a+'" data-id="'+dataAttd['ID']+'">Attendance Students</a></li>' +
                        '    <li role="separator" class="divider"></li>' +
                        '    <li><a href="javascript:void(0);" class="inputScheduleExchange" data-no="'+a+'" data-id="'+dataAttd['ID']+'">Set Schedule Exchange</a></li>' +
                        '  </ul>' +
                        '</div>';

                    var btnDate = (dataAttd['Date'+a]!='' && dataAttd['Date'+a]!=null) ? moment(dataAttd['Date'+a]).format('dddd, DD MMM YYYY') : '-';

                    var btnIn = (dataAttd['In'+a]!='' && dataAttd['In'+a]!=null) ? dataAttd['In'+a].substr(0,5) : '-';

                    var btnOut = (dataAttd['Out'+a]!='' && dataAttd['Out'+a]!=null) ? dataAttd['Out'+a].substr(0,5) : '-';

                    var BAP = (dataAttd['BAP'+a]!='' && dataAttd['BAP'+a]!=null) ? dataAttd['BAP'+a] : '-' ;

                    var NameNip = (dataAttd['NIP'+a]!='' && dataAttd['NIP'+a]!=null) ? '<b>'+dataAttd['Name'+a]+'</b><br/>'+dataAttd['NIP'+a] : '-';

                    tr.append('<tr>' +
                        '            <td>'+a+'</td>' +
                        '            <td><button class="btn btn-sm btn-default btn-default-warning"><i class="fa fa-clock-o" aria-hidden="true"></i></button></td>' +
                        '            <td style="text-align: left;">'+NameNip+'</td>' +
                        '            <td style="text-align: right;">' +
                        '               <b>'+btnDate+'</b><br/>' +
                        '           <b style="color: #438848;"><i class="fa fa-download" style="margin-right: 5px;" aria-hidden="true"></i> '+btnIn+' | </b>' +
                        '           <b style="color: #436888;"><i class="fa fa-upload" style="margin-right: 5px;" aria-hidden="true"></i> '+btnOut+'</b>' +
                        '           </td>' +
                        '            <td>'+dataAttd['S_P'+a]+'</td>' +
                        '            <td>'+dataAttd['S_A'+a]+'</td>' +
                        '            <td>'+btnAct+'</td>' +
                        '            <td>'+BAP+'</td>' +
                        '        </tr>');
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
            opt.append('<option selected disabled>-- Select Schedule --</option>');
            opt.append('<option disabled>----------------------</option>');
            if(jsonResult.length>0){

                for(var i=0;i<jsonResult.length;i++){
                    var dataR = jsonResult[i];
                    var time = dataR.StartSessions.substr(0,5)+' - '+dataR.EndSessions.substr(0,5);
                    opt.append('<option value="'+dataR.AttendanceID+'.'+dataR.ScheduleID+'.'+dataR.ID+'">'+dataR.DayNameEng+', '+time+' | Ruang '+dataR.Room+'</option>');
                }
            }
        });
    }

</script>

