<style>
    .table-schedule th,.table-schedule td {
        text-align: center;
    }
    .btn-act {
        padding: 1px 9px;
        margin-bottom: 5px;
    }
    .table-schedule span.label {
        position: relative !important;
    }
    .list-sch {
        padding-left: 0px;
        list-style: none;
    }
</style>
<div id="divSchedule"></div>

<script>
    $(document).ready(function () {
        loadDataSchedule();
    });
    
    function loadDataSchedule() {
        var url = base_url_js+'rest/__geTimetable';

        var data = {
            auth : {
                user : 'siak'
            },
            action : 'getTimeTable',
            NIP : Lecturer_NIP,
            date : moment().format('YYYY-MM-DD')
        };

        var token = jwt_encode(data,'s3Cr3T-G4N');

        $.post(url,{token:token},function (jsonResult) {
            $('#divSchedule').html('');

            for(var i=0;i<jsonResult.length;i++){

                var dataResult = jsonResult[i];

                if(dataResult.DetailsCoordinator.length>0 || dataResult.DetailsTeamTeaching.length>0) {


                    $('#divSchedule').prepend('<div class="thumbnail" style="padding: 0px;margin-bottom: 30px;">' +
                        '    <h3 class="heading-small">' + dataResult.Semester + '</h3>' +
                        '    <div class="table-responsive" style="padding: 10px;">' +
                        '        <table class="table table-bordered table-schedule" width="100%" style="margin-bottom: 0px;">' +
                        '            <thead>' +
                        '            <tr style="background: #437e88;color: #ffffff;">' +
                        '                <th style="width: 1%;">No</th>' +
                        '                <th style="width: 5%;">Code</th>' +
                        '                <th>Course</th>' +
                        '                <th style="width: 15%;">Lecturers</th>' +
                        '                <th style="width: 7%;">Group</th>' +
                        // '                <th style="width: 7%;">Students</th>' +
                        '                <th style="width: 5%;">Attd</th>' +
                        // '                <th style="width: 10%;">Action</th>' +
                        '                <th style="width: 5%;">Action</th>' +
                        '                <th style="width: 9%;">Day</th>' +
                        '                <th style="width: 15%;">Time</th>' +
                        '                <th style="width: 5%;">Room</th>' +
                        '            </tr>' +
                        '            </thead>' +
                        '            <tbody id="dataTimetable' + dataResult.SemesterID + '"></tbody>' +
                        '        </table>' +
                        '    </div>' +
                        '<div style="text-align: right;padding: 10px;">' +
                        'Download PDF : <a class="btn btn-sm btn-default btn-default-success">Schedule</a> | ' +
                        '<a class="btn btn-sm btn-default btn-default-success">UTS</a> | ' +
                        '<a class="btn btn-sm btn-default btn-default-success">UAS</a>' +
                        '</div> ' +
                        '</div>');

                    var tr = $('#dataTimetable' + dataResult.SemesterID);
                    var no = 1;
                    if (dataResult.SemesterID < 13) {
                        for (var t = 0; t < dataResult.DetailsCoordinator.length; t++) {
                            var dataTr = dataResult.DetailsCoordinator[t];
                            tr.append('<tr>' +
                                '<td>' + (no++) + '</td>' +
                                '<td>' + dataTr.MKCode + '</td>' +
                                '<td style="text-align: left;"><b>' + dataTr.MKNameEng + '</b><br/><i>' + dataTr.MKName + '</i></td>' +
                                '<td>-</td>' +
                                // '<td>' + dataTr.ProdiCode + '</td>' +
                                '<td>-</td>' +
                                '<td>100%</td>' +
                                '<td>-</td>' +
                                '<td>' + dataTr.Day + '</td>' +
                                '<td>' + dataTr.Start.substr(0, 5) + ' - ' + dataTr.End.substr(0, 5) + '</td>' +
                                '<td>' + dataTr.Classroom + '</td>' +
                                '</tr>');
                        }
                        for (var t = 0; t < dataResult.DetailsTeamTeaching.length; t++) {
                            var dataTr = dataResult.DetailsTeamTeaching[t];
                            tr.append('<tr>' +
                                '<td>' + (no++) + '</td>' +
                                '<td>' + dataTr.MKCode + '</td>' +
                                '<td style="text-align: left;"><b>' + dataTr.MKNameEng + '</b><br/><i>' + dataTr.MKName + '</i></td>' +
                                '<td>-</td>' +
                                // '<td>' + dataTr.ProdiCode + '</td>' +
                                '<td>-</td>' +
                                '<td>100%</td>' +
                                '<td>-</td>' +
                                '<td>' + dataTr.Day + '</td>' +
                                '<td>' + dataTr.Start.substr(0, 5) + ' - ' + dataTr.End.substr(0, 5) + '</td>' +
                                '<td>' + dataTr.Classroom + '</td>' +
                                '</tr>');
                        }
                    }
                    else {

                        if (dataResult.DetailsCoordinator.length > 0) {
                            for (var dc = 0; dc < dataResult.DetailsCoordinator.length; dc++) {
                                var data = dataResult.DetailsCoordinator[dc];

                                // Lecturer
                                var lec = '<b><a href="javascript:void(0)">'+data.CoordinatorName+'</a></b><br/>';

                                if(data.detailTeamTeaching.length>0){
                                    for(var t=0;t<data.detailTeamTeaching.length;t++){
                                        var dt = data.detailTeamTeaching[t];
                                        var ln = (t==(data.detailTeamTeaching.length - 1))?'':'<br/>';
                                        lec = lec+'<a href="javascript:void(0)">'+dt.Name+'</a>'+ln;
                                    }
                                }

                                var btnAction = '<div class="dropdown">' +
                                    '  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">' +
                                    '    <i class="fa fa-pencil-square-o"></i>' +
                                    '    <span class="caret"></span>' +
                                    '  </button>' +
                                    '  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">' +
                                    '    <li><a href="javascript:void(0);" class="btnLecturerActionAttd" data-page="InputPresensi" data-id="'+data.ID+'">Attendance</a></li>' +
                                    '    <li><a href="javascript:void(0);" class="btnLecturerAction" data-page="InputScore" data-id="'+data.ID+'">Score</a></li>' +
                                    '    <li><a href="javascript:void(0);" class="btnLecturerAction" data-page="InputMaterial" data-id="'+data.ID+'">Material</a></li>' +
                                    '    <li role="separator" class="divider"></li>' +
                                    '    <li><a href="javascript:void(0);" class="btnGrade" data-page="InputGrade1" data-group="'+data.ClassGroup+'" data-id="'+data.ID+'">Grade Approval</a></li>' +
                                    // '    <li><a href="#">Separated link</a></li>' +
                                    '  </ul>' +
                                    '</div>';

                                if (data.detailSesi.length > 1) {
                                    var rwspn = 1 + parseInt(data.detailSesi.length);

                                    tr.append('<tr>' +
                                        '<td class="tb-center" rowspan="' + rwspn + '">' + (no++) + '</td>' +
                                        '<td class="tb-center" rowspan="' + rwspn + '"><ul id="course_code' + data.ID + '" class="list-sch"></ul></td>' +
                                        '<td rowspan="' + rwspn + '" style="text-align: left;">' +
                                        '<span id="course_c' + data.ID + '" class="list-sch"></span>' +
                                        '</td>' +
                                        '<td  rowspan="' + rwspn + '" style="text-align: left;font-size: 10px;">'+lec+'</td>' +
                                        '<td  rowspan="' + rwspn + '" class="tb-center">' + data.ClassGroup + '</td>' +
                                        '<td class="tb-center"  rowspan="' + rwspn + '">1</td>' +
                                        '</tr>');

                                    // Schedule
                                    for (var s = 0; s < data.detailSesi.length; s++) {
                                        var sc = data.detailSesi[s];

                                        tr.append('<tr style="background: #ffeb3b14;">' +
                                            '<td class="tb-center" style="background: #ffffff;">'+btnAction+'</td>' +
                                            '<td class="tb-center">' + sc.NameEng + '</td>' +
                                            '<td class="tb-center">' + sc.StartSessions.substr(0, 5) + ' - ' + sc.EndSessions.substr(0, 5) + '</td>' +
                                            '<td class="tb-center"><strong>' + sc.Room + '</strong></td>' +
                                            '</tr>');
                                    }
                                }
                                else {
                                    var rwspn = 1;
                                    var sc = data.detailSesi[0];

                                    tr.append('<tr>' +
                                        '<td class="tb-center" rowspan="' + rwspn + '">' + (no++) + '</td>' +
                                        '<td class="tb-center" rowspan="' + rwspn + '"><ul id="course_code' + data.ID + '" class="list-sch"></ul></td>' +
                                        '<td rowspan="' + rwspn + '" style="text-align: left;">' +
                                        '<span id="course_c' + data.ID + '" class="list-sch"></span>' +
                                        '</td>' +
                                        '<td  rowspan="' + rwspn + '" style="text-align: left;font-size: 10px;">'+lec+'</td>' +
                                        '<td  rowspan="' + rwspn + '" class="tb-center">' + data.ClassGroup + '</td>' +
                                        '<td class="tb-center"  rowspan="' + rwspn + '">1</td>' +
                                        '<td class="tb-center">'+btnAction+'</td>' +
                                        '<td  style="background: #ffeb3b14;" class="tb-center">' + sc.NameEng + '</td>' +
                                        '<td  style="background: #ffeb3b14;" class="tb-center">' + sc.StartSessions.substr(0, 5) + ' - ' + sc.EndSessions.substr(0, 5) + '</td>' +
                                        '<td  style="background: #ffeb3b14;" class="tb-center"><strong>' + sc.Room + '</strong></td>' +
                                        '</tr>');

                                }




                                // Course
                                for (var c = 0; c < data.detailCourse.length; c++) {
                                    var course = data.detailCourse[c];
                                    // console.log(course);

                                    $('#course_c' + data.ID).html('<a href="javascript:void(0);" class="btnDetailCourse" id="btnDetailCourse'+data.ID+'"><b>' + course.NameEng + '</b></a><br/><i>'+course.Name+'</i>');
                                    $('#course_code' + data.ID).append('<li>' + course.MKCode + '</li>');

                                    $('#btnmaterial'+data.ID+',#btnscore'+data.ID+',#btnDetailCourse'+data.ID).attr({
                                        'data-id' : data.ID,
                                        'data-mkid' : course.MKID,
                                        'data-mkname' : course.NameEng,
                                        'data-mkcode' : course.MKCode
                                    });

                                }

                            }
                        }

                        if(dataResult.DetailsTeamTeaching.length>0){
                            // console.log(dataResult.DetailsTeamTeaching);
                            for(var ttcD=0;ttcD<dataResult.DetailsTeamTeaching.length;ttcD++){
                                var data = dataResult.DetailsTeamTeaching[ttcD];

                                var btnAct = '<a class="btn btn-sm btn-default btn-act btn-default-primary"><i class="fa fa-users margin-right"></i> Attd</a> ' +
                                    '<a class="btn btn-sm btn-default btn-act btn-default-warning btn-material" href="javacript:void(0)" id="btnmaterial'+data.ID+'"><i class="fa fa-bookmark margin-right"></i> Material</a>';

                                if(data.StatusTeamTeaching=='1'){
                                    var btnAct = '<a class="btn btn-sm btn-default btn-act btn-default-primary"><i class="fa fa-users margin-right"></i> Attd</a> ' +
                                        '<a class="btn btn-sm btn-default btn-act btn-default-danger tab-btn-academic" href="javascript:void(0)" data-page="score" id="btnscore'+data.ID+'"><i class="fa fa-pencil-square-o margin-right"></i> Score</a> ' +
                                        '<a class="btn btn-sm btn-default btn-act btn-default-warning btn-material" href="javacript:void(0)" id="btnmaterial'+data.ID+'"><i class="fa fa-bookmark margin-right"></i> Material</a>';
                                }

                                if(data.detailSesi.length>1){
                                    var rwspn = 1 + parseInt(data.detailSesi.length);

                                    tr.append('<tr style="background: #ffeb3b21;">' +
                                        '<td class="tb-center" rowspan="'+rwspn+'">'+(no++)+'</td>' +
                                        '<td class="tb-center" rowspan="'+rwspn+'"><span id="course_code'+data.ID+'" class="list-sch"></span></td>' +
                                        '<td rowspan="'+rwspn+'" style="text-align: left;"><span id="course_c'+data.ID+'" class="list-sch"></span></td>' +
                                        '<td  rowspan="'+rwspn+'" class="tb-center">'+data.ClassGroup+'</td>' +
                                        '<td class="tb-center"  rowspan="'+rwspn+'">1</td>' +
                                        '<td class="tb-center"  rowspan="'+rwspn+'">1</td>' +
                                        '<td class="tb-center"  rowspan="'+rwspn+'">1</td>' +
                                        '</tr>');

                                    // Schedule
                                    for(var s=0;s<data.detailSesi.length;s++){
                                        var sc = data.detailSesi[s];

                                        tr.append('<tr style="background: #ffeb3b21;">' +
                                            '<td class="tb-center">'+sc.NameEng+'</td>' +
                                            '<td class="tb-center">'+sc.StartSessions.substr(0,5)+' - '+sc.EndSessions.substr(0,5)+'</td>' +
                                            '<td class="tb-center"><strong>'+sc.Room+'</strong></td>' +
                                            '</tr>');
                                    }
                                }
                                else {
                                    var rwspn = 1;
                                    var sc = data.detailSesi[0];


                                    tr.append('<tr>' +
                                        '<td class="tb-center" rowspan="'+rwspn+'">'+(no++)+'</td>' +
                                        '<td class="tb-center" rowspan="'+rwspn+'"><ul id="course_code'+data.ID+'" class="list-sch"></ul></td>' +
                                        '<td rowspan="'+rwspn+'" style="text-align: left;"><span id="course_c'+data.ID+'" class="list-sch"></span><br/>'+btnAct+'</td>' +
                                        '<td class="tb-center"  rowspan="'+rwspn+'">-</td>' +
                                        '<td  rowspan="'+rwspn+'" class="tb-center">'+data.ClassGroup+'</td>' +
                                        '<td class="tb-center"  rowspan="'+rwspn+'">100%</td>' +
                                        '<td class="tb-center">'+sc.NameEng+'</td>' +
                                        '<td class="tb-center">'+sc.StartSessions.substr(0,5)+' - '+sc.EndSessions.substr(0,5)+'</td>' +
                                        '<td class="tb-center"><strong>'+sc.Room+'</strong></td>' +
                                        '</tr>');

                                }

                                // Course
                                for(var c=0;c<data.detailCourse.length;c++){
                                    var course = data.detailCourse[c];
                                    $('#course_c'+data.ID).html('<b>'+course.NameEng+'</b><br/><i>'+course.Name+'</i>');
                                    $('#course_code'+data.ID).html('<li>'+course.MKCode+' (team)</li>');

                                    $('#btnmaterial'+data.ID+',#btnscore'+data.ID+',#btnDetailCourse'+data.ID).attr({
                                        'data-id' : data.ID,
                                        'data-mkid' : course.MKID,
                                        'data-mkname' : course.NameEng,
                                        'data-mkcode' : course.MKCode
                                    });

                                }
                            }
                        }


                    }
                }

            }




        });
    }
</script>