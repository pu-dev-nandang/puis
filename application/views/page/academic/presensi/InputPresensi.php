
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

    $(document).on('click','.rdAttd',function () {
        var No = $(this).attr('data-no');
        var valu = $(this).val();
        // console.log(No);
        $('#trA'+No+',#trP'+No).css('background','none');

        if(valu=='1'){
            $('#trP'+No).css('background','#438848');
        } else {
            $('#trA'+No).css('background','#884343');
        }

        countP_A();

    });

    $(document).on('click','#ckAllP',function () {
        $('.trA,.trP').css('background','none');
        if($('#ckAllP').is(':checked')){
            $('.trP').css('background','#438848');
            $('.rd-trA').prop('checked',false);
            $('.rd-trP').prop('checked',true);

            $('#ckAllA').prop('checked',false);
        } else {
            $('.rd-trP').prop('checked',false);
        }
        countP_A();
    });

    $(document).on('click','#ckAllA',function () {
        $('.trA,.trP').css('background','none');
        if($('#ckAllA').is(':checked')){
            $('.trA').css('background','#884343');
            $('.rd-trP').prop('checked',false);
            $('.rd-trA').prop('checked',true);

            $('#ckAllP').prop('checked',false);
        } else {
            $('.rd-trA').prop('checked',false);
        }
        countP_A();
    });


    $(document).on('click','.inputStudentAttd',function () {

        var ID = $(this).attr('data-id');
        var No = $(this).attr('data-no');


        // var DataSemester = $('#filterSemester').val();
        // var SemesterID = DataSemester.split('.')[0];
        var SemesterID = 13;

        var DataAttendance = $('#filterAttendance').val();
        var SDID = DataAttendance.split('.')[1];

        var url = base_url_js+'api/__crudAttendance';
        var data = {
            action : 'getAttdStudents',
            SemesterID : SemesterID,
            ScheduleID : 4,
            Meeting : No
        };
        var token = jwt_encode(data,'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            console.log(jsonResult);
        });

        return false;

        $('#GlobalModalLarge .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Attendance Students | Pertemuan '+No+'</h4>');


        var body_attd = '<table class="table table-bordered table-striped" id="tableStdAttd">' +
            '        <thead>' +
            '        <tr style="background:#436f88;color: #ffffff;">' +
            '            <th style="width: 1%;">No</th>' +
            '            <th style="width: 15%;">NPM</th>' +
            '            <th>Name</th>' +
            '            <th  style="width: 5%;">' +
            '                P' +
            '                <br/>' +
            '                <input id="ckAllP" type="checkbox">' +
            '            </th>' +
            '            <th style="width: 5%;">' +
            '                A' +
            '                <br/>' +
            '                <input id="ckAllA" type="checkbox">' +
            '            </th>' +
            '            <th style="width: 25%;">Description</th>' +
            '        </tr>' +
            '        </thead>' +
            '        <tbody id="trBody">' +
            '        </tbody>' +
            '    </table>';



        $('#GlobalModalLarge .modal-body').html(body_attd);

        window.totalMhs = 10;
        for(var i=0;i<totalMhs;i++){
            $('#trBody').append('<tr>' +
                '<td>'+i+'</td>' +
                '<td></td>' +
                '<td></td>' +
                '<td class="trP" id="trP'+i+'"><input type="radio" class="rdAttd rd-trP" data-mhs="'+totalMhs+'" data-no="'+i+'" name="optRAttd'+i+'" value="1"></td>' +
                '<td class="trA" id="trA'+i+'" style="background: #884343;"><input type="radio" class="rdAttd rd-trA" data-mhs="'+totalMhs+'" data-no="'+i+'" name="optRAttd'+i+'" value="2" checked></td>' +
                '<td><textarea class="form-control" rows="3"></textarea></td>' +
                '</tr>');
        }

        $('#GlobalModalLarge .modal-footer').html('Total : '+totalMhs+' | P : <span id="totalP"></span> | A : <span id="totalA"></span> | <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> ' +
            '<button class="btn btn-success" id="btnSaveAttdLecturer" data-no="'+No+'" data-id="'+ID+'">Save</button>');
        $('#GlobalModalLarge').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        countP_A();

    });

    function countP_A() {

        var p = 0;
        var a = 0;
        for(var i=0;i<totalMhs;i++){
            var valu = $('input[type=radio][name=optRAttd'+i+']:checked').val();
            if(valu=='1') {
                p += 1;
            } else {
                a += 1;
            }
        }

        if(totalMhs!=a){
            $('#ckAllA').prop('checked',false);
        } else {
            $('#ckAllA').prop('checked',true);
        }

        if(totalMhs!=p){
            $('#ckAllP').prop('checked',false);
        } else {
            $('#ckAllP').prop('checked',true);
        }

        $('#totalP').html(p);
        $('#totalA').html(a);
    }

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
                    '            <th rowspan="2"  style="width: 5%;"><i class="fa fa-repeat"></i></th>' +
                    '            <th colspan="3">Lecturer</th>' +
                    '            <th colspan="2">Students</th>' +
                    '            <th rowspan="2" style="width: 10%;">Action</th>' +
                    '            <th rowspan="2">BAP</th>' +
                    '        </tr>' +
                    '        <tr style="background-color: #436888;color: #ffffff;">' +
                    '            <th style="width: 25%;">Date</th>' +
                    '            <th style="width: 5%;">In</th>' +
                    '            <th style="width: 5%;">Out</th>' +
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
                        '    <li><a href="javascript:void(0);" class="inputReplacementSchedule" data-no="'+a+'" data-id="'+dataAttd['ID']+'">Set Replacement Schedule</a></li>' +
                        '  </ul>' +
                        '</div>';

                    var btnDate = (dataAttd['Date'+a]!='' && dataAttd['Date'+a]!=null) ? moment(dataAttd['Date'+a]).format('dddd, DD MMM YYYY') : '-';

                    var btnIn = (dataAttd['In'+a]!='' && dataAttd['In'+a]!=null) ? dataAttd['In'+a].substr(0,5) : '-';

                    var btnOut = (dataAttd['Out'+a]!='' && dataAttd['Out'+a]!=null) ? dataAttd['Out'+a].substr(0,5) : '-';

                    var BAP = (dataAttd['BAP'+a]!='' && dataAttd['BAP'+a]!=null) ? dataAttd['BAP'+a] : '-' ;

                    tr.append('<tr>' +
                        '            <td>'+a+'</td>' +
                        '            <td>-</td>' +
                        '            <td>'+btnDate+'</td>' +
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

            opt.empty();
            opt.append('<option selected disabled>-- Select Schedule --</option>');
            opt.append('<option disabled>----------------------</option>');
            if(jsonResult.length>0){

                for(var i=0;i<jsonResult.length;i++){
                    var dataR = jsonResult[i];
                    var time = dataR.StartSessions.substr(0,5)+' - '+dataR.EndSessions.substr(0,5);
                    opt.append('<option value="'+dataR.AttendanceID+'.'+dataR.ScheduleID+'">'+dataR.DayNameEng+', '+time+' | Ruang '+dataR.Room+'</option>');
                }
            }
        });
    }


</script>