
<style>
    #tableTimeTalbesOnline a {
        /*text-decoration: none;*/
    }


    .checkbox.checbox-switch label span:before, .checkbox-inline.checbox-switch span:before {
        left: -7px !important;
    }

    .checkbox.checbox-switch label > input:checked + span:before, .checkbox-inline.checbox-switch > input:checked + span:before {
        left: 9px !important;
    }
</style>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="well">
            <div class="row">
                <div class="col-md-6">
                    <select class="form-control" id="filterSemester"></select>
                </div>
                <div class="col-md-6">
                    <select class="form-control" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="viewTable"></div>






<script>
    
    $(document).ready(function () {

        loSelectOptionSemester('#filterSemester','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');

        var firstLoad = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            if(filterSemester!='' && filterSemester!=null){
                getOnlineClass();
                clearInterval(firstLoad);
            }
        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);


    });

    $('#filterSemester,#filterBaseProdi').change(function () {
        getOnlineClass();
    });

    function getOnlineClass() {

        $('#viewTable').html('<table class="table table-bordered table-striped table-centre" id="tableTimeTalbesOnline">' +
            '    <thead>' +
            '    <tr style="background: #f3f1f1;">' +
            '        <th style="width: 3%;">No</th>' +
            '        <th style="width: 15%;">Course</th>' +
            '        <th>1</th>' +
            '        <th>2</th>' +
            '        <th>3</th>' +
            '        <th>4</th>' +
            '        <th>5</th>' +
            '        <th>6</th>' +
            '        <th>7</th>' +
            '        <th>8</th>' +
            '        <th>9</th>' +
            '        <th>10</th>' +
            '        <th>11</th>' +
            '        <th>12</th>' +
            '        <th>13</th>' +
            '        <th>14</th>' +
            '    </tr>' +
            '    </thead>' +
            '</table>');

        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){

            var SemesterID = filterSemester.split('.')[0];
            var filterBaseProdi = $('#filterBaseProdi').val();
            var ProdiID = (filterBaseProdi!='') ? filterBaseProdi.split('.')[0] : '';

            var data = {
                SemesterID : SemesterID,
                ProdiID : ProdiID
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api4/__getDataOnlineClass';

            var dataTable = $('#tableTimeTalbesOnline').DataTable({
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "Group, (Co)Lecturer, Classroom"
                },
                "ajax":{
                    url : url, // json datasource
                    data : {token:token},
                    ordering : false,
                    type: "post",  // method  , by default get
                    error: function(){  // error handling
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
                    }
                }
            });

        }

    }

    $(document).on('click','.btnAdmShowAttendance',function () {

        var ScheduleID = $(this).attr('data-schid');
        var Session = $(this).attr('data-session');
        var RangeStart = $(this).attr('data-start');
        var RangeEnd = $(this).attr('data-end');
        var dataRow = JSON.parse($('#text_'+ScheduleID).val());


        var data = {
            action : 'getMonitoringAttd',
            ScheduleID : ScheduleID,
            Session : Session
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudOnlineClass';

        $.post(url,{token:token},function (jsonResult) {

            var modalID = '#GlobalModal';
            $(modalID+' .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">'+dataRow.ClassGroup+' - '+dataRow.CourseEng+' | Session '+Session+'</h4>');


            var viewSch = '';
            if(jsonResult.Schedule.length>0){
                $.each(jsonResult.Schedule,function (i,v) {
                    var st = v.StartSessions.substr(0,5);
                    var en = v.EndSessions.substr(0,5);
                    viewSch = viewSch+'<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td>'+v.NameEng+', '+st+' - '+en+'</td>' +
                        '<td>'+v.Room+'</td>' +
                        '</tr>'
                });
            }

            var tmSch = '<h3 style=""><i class="fa fa-calendar margin-right"></i> Schedule</h3>' +
                '<table class="table table-striped table-bordered table-centre">' +
                '    <thead>' +
                '    <tr style="background: #efefef;">' +
                '        <th style="width: 1%;">No</th>' +
                '        <th>Date, time</th>' +
                '        <th style="width: 25%;">Room</th>' +
                '    </tr>' +
                '    </thead>' +
                '    <tbody>'+viewSch+'</tbody>' +
                '</table>';

            var viewLec = '';
            if(jsonResult.Lecturer.length>0){
                $.each(jsonResult.Lecturer,function (i,v) {
                    var Forum = (parseInt(v.Forum)>0) ? '<i class="fa fa-check" style="color: green;"></i>' : '-';
                    var Task = (parseInt(v.Task)>0) ? '<i class="fa fa-check" style="color: green;"></i>' : '-';

                    var UploadMaterialBy = (v.Material.length>0) ? 'Uploaded by : '+v.Material[0].Name : '';
                    var Material = (v.Material.length>0)
                        ? '<i class="fa fa-check" style="color: green;"></i> | <span style="font-size: 10px;">'+UploadMaterialBy+'</span>' : '-';


                    var check = (parseInt(v.SessionAttend)==parseInt(v.SessionAttendSch)) ? 'checked' : '';

                    var isPresent =  '<div class="checkbox checbox-switch switch-success ">' +
                        '           <label>' +
                        '               <input type="checkbox" class="checkAttdLec" '+check+' data-nip="'+v.NIP+'">' +
                        '               <span></span>' +
                        '           </label>' +
                        '       </div>' ;

                    viewLec = viewLec+'<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td style="text-align: left;"><b>'+v.Name+'</b><br/>'+v.NIP+'</td>' +
                        '<td>'+Forum+'</td>' +
                        '<td>'+Task+'</td>' +
                        '<td>'+Material+'</td>' +
                        '<td>'+isPresent+'</td>' +
                        '</tr>';
                });
            }

            var tmLec = '<h3><i class="fa fa-users margin-right"></i> Lecturer</h3>' +
                '<table class="table table-bordered table-striped table-centre">' +
                '    <thead>' +
                '    <tr style="background: #efefef;">' +
                '        <th style="width: 1%;">No</th>' +
                '        <th>Lecturer</th>' +
                '        <th style="width: 10%;">Forum</th>' +
                '        <th style="width: 10%;">Task</th>' +
                '        <th style="width: 20%;">Material</th>' +
                '        <th style="width: 17%;">Attendance</th>' +
                '    </tr>' +
                '    </thead>' +
                '    <tbody>'+viewLec+'</tbody>' +
                '</table>';

            var viewStd = '';
            if(jsonResult.Student.length>0){
                $.each(jsonResult.Student,function (i,v) {
                    var Forum = (parseInt(v.TotalComment)>0) ? '<i class="fa fa-check" style="color: green;"></i>' : '-';
                    var Task = (v.TotalTask.length>0)
                        ? '<div id="viewTask_'+v.TotalTask[0].ID+'"><i class="fa fa-check" style="color: green;"></i>' +
                            '<a href="javascript:void(0);" class="btnRemoveTask" data-id="'+v.TotalTask[0].ID+'">Remove</a></div>'
                        : '-';

                    var check = (parseInt(v.SessionAttend)==parseInt(v.SessionAttendSch)) ? 'checked' : '';

                    var isPresent =  '<div class="checkbox checbox-switch switch-success ">' +
                    '           <label>' +
                    '               <input type="checkbox" class="checkAttdStd" '+check+' data-npm="'+v.NPM+'">' +
                    '               <span></span>' +
                    '           </label>' +
                    '       </div>' ;

                    viewStd = viewStd+'<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td style="text-align: left;"><b>'+v.Name+'</b><br/>'+v.NPM+'</td>' +
                        '<td>'+Forum+'</td>' +
                        '<td>'+Task+'</td>' +
                        '<td>'+isPresent+'</td>' +
                        '</tr>';
                });
            }

            var tmStd = '<h3><i class="fa fa-graduation-cap margin-right"></i> Student</h3>' +
                '<table class="table table-bordered table-striped table-centre">' +
                '    <thead>' +
                '    <tr style="background: #efefef;">' +
                '        <th style="width: 1%;">No</th>' +
                '        <th>Student</th>' +
                '        <th style="width: 10%;">Forum</th>' +
                '        <th style="width: 10%;">Task</th>' +
                '        <th style="width: 17%;">Attendance</th>' +
                '    </tr>' +
                '    </thead>' +
                '    <tbody>'+viewStd+'</tbody>' +
                '</table>';

            var viewRangeStart = moment(RangeStart).format('dddd, DD MMMM YYYY');
            var viewRangeEnd = moment(RangeEnd).format('dddd, DD MMMM YYYY');

            var htmlss = '<div class="alert alert-info" style="text-align: center;"><b>'+viewRangeStart+' - '+viewRangeEnd+'</b></div>' +
                        '<input class="hide" id="attdModal_ScheduleID" value="'+ScheduleID+'" />' +
                        '<input class="hide" id="attdModal_RangeStart" value="'+RangeStart+'" />' +
                        '<input class="hide" id="attdModal_RangeEnd" value="'+RangeEnd+'" />' +
                        '<input class="hide" id="attdModal_Session" value="'+Session+'" />' +
                        '<textarea class="hide" id="attdModal_Schedule">'+JSON.stringify(jsonResult.Schedule)+'</textarea>' +
                        ''+tmSch+tmLec+tmStd;

            $(modalID+' .modal-body').html(htmlss);

            $(modalID+' .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

            $(modalID).on('shown.bs.modal', function () {
                $('#formSimpleSearch').focus();
            });

            $(modalID).modal({
                'show' : true,
                'backdrop' : 'static'
            });

        });

    });

    // Set Attendance Student
    $(document).on('click','.checkAttdStd',function () {

        var ScheduleID = $('#attdModal_ScheduleID').val();
        var Session = $('#attdModal_Session').val();
        var Schedule = $('#attdModal_Schedule').val();
        var dAttd = JSON.parse(Schedule);

        var arrIDAttd = [];
        $.each(dAttd,function (i,v) {
            arrIDAttd.push(v.ID_Attd);
        });


        var NPM = $(this).attr('data-npm');

        var m = ($(this).is(':checked')) ? '1' : '2';

        var data = {
            action : 'UpdateStudentAttdInOnline',
            ArrIDAttd : arrIDAttd,
            Meet : Session,
            Attendance : m,
            NPM : NPM
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudAttendance2';

        $.post(url,{token:token},function (result) {

        });

    });

    // Set Attendance Lecturer
    $(document).on('click','.checkAttdLec',function () {

        var ScheduleID = $('#attdModal_ScheduleID').val();
        var RangeStart = $('#attdModal_RangeStart').val();
        var RangeEnd = $('#attdModal_RangeEnd').val();
        var Session = $('#attdModal_Session').val();
        var Schedule = $('#attdModal_Schedule').val();
        var dAttd = JSON.parse(Schedule);
        var NIP = $(this).attr('data-nip');

        var IPPublic = localStorage.getItem('IPPublic');

        var arrIDAttd = [];
        $.each(dAttd,function (i,v) {
            arrIDAttd.push({
                ID_Attd : v.ID_Attd,
                Meet : Session,
                NIP : NIP,
                Date : RangeStart,
                In : v.StartSessions,
                Out : v.EndSessions,
                ModifyBy : sessionNIP,
                ModifyAt : dateTimeNow(),
                IP_Public : IPPublic,
                IsOnline : '1'
            });
        });

        var m = ($(this).is(':checked')) ? '1' : '2';

        var data = {
            action : 'UpdateLecturertAttdInOnline',
            ArrIDAttd : arrIDAttd,
            Status : m
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudAttendance2';

        $.post(url,{token:token},function (result) {

        });


    });

    // Remove task student
    $(document).on('click','.btnRemoveTask',function () {
       if(confirm('Are you sure?')){
           var ID = $(this).attr('data-id');
           var data = {
               action : 'removeTaskStudent',
               ID : ID
           };
           var token = jwt_encode(data,'UAP)(*');
           var url = base_url_js+'api4/__crudOnlineClass';

           $.post(url,{token:token},function (result) {
               toastr.success('Task removed','Success');
               setTimeout(function () {
                   $('#viewTask_'+ID).html('-');
               },500);
           });
       }
    });

</script>