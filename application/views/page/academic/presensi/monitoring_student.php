
<style>
    #tableMonAttdStd tr th {
        text-align: center;
        background-color: #436888;
        color: #ffffff;
    }
    #tableMonAttdStd tr td {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="well">
            <div class="row">
                <div class="col-xs-4">
                    <select id="filterSemester" class="form-control filter-presensi"></select>
                </div>
                <div class="col-xs-8">
                    <div id="viewGroup"></div>
                </div>

            </div>
        </div>

        <hr/>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h3 style="margin-top: 0px;border-left: 11px solid #ff9800;padding-left: 10px;font-weight: bold;" id="viewCourse">-</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tableMonAttdStd">
                <thead>
                <tr>
                    <th rowspan="2" style="width: 2%;">No</th>
                    <th rowspan="2" style="width: 5%;">NIM</th>
                    <th rowspan="2">Name</th>
                    <th rowspan="2" style="width: 10%;">Day</th>
                    <th colspan="14">Session</th>
                    <th rowspan="2" style="width: 5%;">Target</th>
                    <th rowspan="2" style="width: 5%;">Real</th>
                    <th rowspan="2" style="width: 7%;">%</th>
                </tr>
                <tr>
                    <th style="width: 3%;">1</th>
                    <th style="width: 3%;">2</th>
                    <th style="width: 3%;">3</th>
                    <th style="width: 3%;">4</th>
                    <th style="width: 3%;">5</th>
                    <th style="width: 3%;">6</th>
                    <th style="width: 3%;">7</th>
                    <th style="width: 3%;">8</th>
                    <th style="width: 3%;">9</th>
                    <th style="width: 3%;">10</th>
                    <th style="width: 3%;">11</th>
                    <th style="width: 3%;">12</th>
                    <th style="width: 3%;">13</th>
                    <th style="width: 3%;">14</th>
                </tr>
                </thead>
                <tbody id="rowAttdStd"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#filterSemester').empty();
        loSelectOptionSemester('#filterSemester','');

        window.varG = true;

        setInterval(function () {
            if(varG){
                loadGroupDiv();
            }
        },1000);

    });

    // Change Smester ID
    $(document).on('change','#filterSemester',function () {
        // checkPage();
        loadGroupDiv();
        $('#divpagePresensi').html('<div style="text-align:center;"><h3 style="color: #ccc;font-weight: bold;">-- Select Class Group --</h3></div>');
    });

    $(document).on('change','#filterClassGroup',function () {
        loadPageAttdStudent();
    });

    function loadGroupDiv() {
        $('#viewGroup').html('');
        $('#viewGroup').html('<select class="select2-select-00 full-width-fix"' +
            '                                size="5" id="filterClassGroup"><option></option></select>');
        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){
            var SemesterID = filterSemester.split('.')[0];
            loadSelectOptionClassGroupAttendance(SemesterID,'#filterClassGroup','');
            $('#filterClassGroup').select2({allowClear: true});

            varG = false;
        }

    }

    function loadPageAttdStudent() {

        var filterSemester = $('#filterSemester').val();
        var filterClassGroup = $('#filterClassGroup').val();

        if(filterSemester!='' && filterSemester!=null && filterClassGroup!='' && filterClassGroup!=null){

            var SemesterID = filterSemester.split('.')[0];
            var ScheduleID = filterClassGroup.split('.')[0];

            var url = base_url_js+'api/__crudAttendance';
            var token = jwt_encode(
                {
                    action : 'getStdAttendance',
                    SemesterID : SemesterID ,
                    ScheduleID : ScheduleID
                },'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {

                var tr = $('#rowAttdStd');

                tr.empty();



                    // Data Course
                    var course = jsonResult.Course[0];
                    $('#viewCourse').html(course.NameEng);

                    var student = jsonResult.Student;
                    if(student.length>0){
                        var no = 1;
                        for(var i=0;i<student.length;i++){
                            var d = student[i];

                            var rwSpan = 1 + d.Attendance.length;

                            tr.append('<tr>' +
                                '<td rowspan="'+rwSpan+'">'+(no++)+'</td>' +
                                '<td style="text-align: left;" rowspan="'+rwSpan+'">'+d.NPM+'</td>' +
                                '<td style="text-align: left;" rowspan="'+rwSpan+'"><b>'+d.Name+'</b></td>' +
                                '</tr>');

                            for(var a=0;a<d.Attendance.length;a++){
                                var da = d.Attendance[a];


                                var trEnd = '</tr>';

                                if(a==0){
                                    var target = 14 * d.Attendance.length;
                                    trEnd = '<td rowspan="'+rwSpan+'">'+target+'</td>' +
                                        '<td rowspan="'+rwSpan+'">7</td>' +
                                        '<td rowspan="'+rwSpan+'">7</td>' +
                                        '</tr>';
                                }

                                tr.append('<tr>' +
                                    '<td style="text-align: left;">'+da.DayEng+'</td>' +
                                    '<td>'+checkSesi(da.M1)+'</td>' +
                                    '<td>'+checkSesi(da.M2)+'</td>' +
                                    '<td>'+checkSesi(da.M3)+'</td>' +
                                    '<td>'+checkSesi(da.M4)+'</td>' +
                                    '<td>'+checkSesi(da.M5)+'</td>' +
                                    '<td>'+checkSesi(da.M6)+'</td>' +
                                    '<td>'+checkSesi(da.M7)+'</td>' +
                                    '<td>'+checkSesi(da.M8)+'</td>' +
                                    '<td>'+checkSesi(da.M9)+'</td>' +
                                    '<td>'+checkSesi(da.M10)+'</td>' +
                                    '<td>'+checkSesi(da.M11)+'</td>' +
                                    '<td>'+checkSesi(da.M12)+'</td>' +
                                    '<td>'+checkSesi(da.M13)+'</td>' +
                                    '<td>'+checkSesi(da.M14)+'</td>' +
                                    ''+trEnd);
                            }


                        }
                    } else {
                        tr.append('<tr><td colspan="19">--- Student Not Yet ---</td></tr>');
                    }



            });

        }


    }

    function checkSesi(Meet) {
        var res = '-';
        if(Meet!=null && Meet!=''){
            res = '<i class="fa fa-check-circle" style="color: green;"></i>';
        } else if (Meet=='2' || Meet==2) {
            res = '<i class="fa fa-times-circle" style="color: red;"></i>';
        }

        return res;
    }

</script>