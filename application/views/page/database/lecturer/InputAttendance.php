
<style>
    #dataTableSchedule tr th, #dataTableSchedule tr td{
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
        var AttendanceID = $('#filterAttendance').val();
        
        if(type==0 && AttendanceID!='' && AttendanceID!=null){
            var url = base_url_js+'api/__crudAttendance';
            var data = {
                action : 'getAttendance',
                AttendanceID : AttendanceID
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {
                console.log(jsonResult);
                var htmlTable = '<table class="table table-bordered table-striped" id="dataTableSchedule">' +
                    '        <thead>' +
                    '        <tr style="background-color: #436888;color: #ffffff;">' +
                    '            <th rowspan="2"  style="width: 5%;">Attd</th>' +
                    '            <th rowspan="2"  style="width: 5%;"><i class="fa fa-repeat"></i></th>' +
                    '            <th colspan="2">Lecturer</th>' +
                    '            <th colspan="2">Students</th>' +
                    '            <th rowspan="2" style="width: 10%;">Action</th>' +
                    '            <th rowspan="2">BAP</th>' +
                    '        </tr>' +
                    '        <tr style="background-color: #436888;color: #ffffff;">' +
                    '            <th style="width: 15%;">In</th>' +
                    '            <th style="width: 15%;">Out</th>' +
                    '            <th style="width: 5%;">P</th>' +
                    '            <th style="width: 5%;">A</th>' +
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
                        '    Action <span class="caret"></span>' +
                        '  </button>' +
                        '  <ul class="dropdown-menu">' +
                        '    <li><a href="javascript:void(0);" class="inputLecturerAttd" data-no="'+a+'" data-id="'+dataAttd['ID']+'">Attendance Lecturer</a></li>' +
                        '    <li><a href="javascript:void(0);" class="" data-no="'+a+'" data-id="'+dataAttd['ID']+'">Attendance Students</a></li>' +
                        '    <li role="separator" class="divider"></li>' +
                        '    <li><a href="javascript:void(0);" class="inputReplacementSchedule" data-no="'+a+'" data-id="'+dataAttd['ID']+'">Set Replacement Schedule</a></li>' +
                        '  </ul>' +
                        '</div>';

                    var btnIn = (dataAttd['In'+a]!='' && dataAttd['In'+a]!=null) ? 'btn-del-in' : 'btn-add-in';

                    var btnOut = (dataAttd['Out'+a]!='' && dataAttd['Out'+a]!=null) ? 'btn-del-out' : 'btn-add-out';

                    var BAP = (dataAttd['BAP'+a]!='' && dataAttd['BAP'+a]!=null) ? dataAttd['BAP'+a] : '-' ;

                    tr.append('<tr>' +
                        '            <td>'+a+'</td>' +
                        '            <td>-</td>' +
                        '            <td>'+btnIn+'</td>' +
                        '            <td>'+btnOut+'</td>' +
                        '            <td></td>' +
                        '            <td></td>' +
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
            console.log(jsonResult);
            opt.empty();
            opt.append('<option selected disabled>-- Select Schedule --</option>');
            opt.append('<option disabled>----------------------</option>');
            if(jsonResult.length>0){

                for(var i=0;i<jsonResult.length;i++){
                    var dataR = jsonResult[i];
                    var time = dataR.StartSessions.substr(0,5)+' - '+dataR.EndSessions.substr(0,5);
                    opt.append('<option value="'+dataR.AttendanceID+'">'+dataR.DayNameEng+', '+time+' | Ruang '+dataR.Room+'</option>');
                }
            }
        });
    }


</script>