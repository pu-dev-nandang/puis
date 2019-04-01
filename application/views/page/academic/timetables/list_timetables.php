
<style>
    #tableShowStudent tr th, #tableShowStudent tr td {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="well">

            <div class="row">
                <div class="col-xs-2">
                    <select class="form-control option-filter" id="filterProgramCampus"></select>
                </div>
                <div class="col-xs-2">
                    <select class="form-control option-filter" id="filterSemester"></select>
                </div>
                <div class="col-xs-4">
                    <select class="form-control option-filter" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                        <option disabled>------------------------------------------</option>
                    </select>
                </div>
                <div class="col-xs-2">
                    <select class="form-control option-filter" id="filterCombine">
                        <option value="">-- Show All --</option>
                        <option value="1">Combine Class Yes</option>
                        <option value="0">Combine Class No</option>
                    </select>
                </div>
                <div class="col-xs-2">
                    <select class="form-control option-filter" id="filterDay">
                        <option value="">-- Show All Day --</option>
                        <option disabled>-------------------</option>
                    </select>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="dataTimeTables"></div>
    </div>
</div>


<script>
    $(document).ready(function () {
        loadSelectOptionProgramCampus('#filterProgramCampus','');
        loSelectOptionSemester('#filterSemester','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');
        fillDays('#filterDay','Eng','');

        var loadFirst = setInterval(function () {

            var filterProgramCampus = $('#filterProgramCampus').val();
            var filterSemester = $('#filterSemester').val();

            if(filterProgramCampus!='' && filterProgramCampus!=null
                && filterSemester!='' && filterSemester!=null){
                loadTimetables();
                clearInterval(loadFirst);
            }

        },1000);

    });

    $('.option-filter').change(function () {
        loadTimetables();
    });

    $(document).on('click','.btnTimetablesEditDelete',function () {

        var ScheduleID = $(this).attr('data-id');
        var ClassGroup = $(this).attr('data-group');
        var SemesterID = $('#filterSemester').val();

        if(ScheduleID!='' && ScheduleID!=null &&
            SemesterID!='' && SemesterID!=null) {
            var data = {
                action : 'checkStudentToDelete',
                ScheduleID : ScheduleID,
                SemesterID : SemesterID.split('.')[0]
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudSchedule';

            $.post(url,{token:token},function (jsonResult) {
                var ap = jsonResult.Approve.length;
                var pl = jsonResult.Plan.length;

                $('#NotificationModal .modal-body').html('<div class="row">' +
                    '<div class="col-md-12">' +
                    '<table class="table">' +
                    '<tr>' +
                    '   <td>KRS Approveed</td>' +
                    '   <td style="width:30%;">'+ap+' Students</td>' +
                    '</tr>' +
                    '<tr>' +
                    '   <td>KRS Not Yet Approved</td>' +
                    '   <td>'+pl+' Students</td>' +
                    '</tr>' +
                    '</table></div>' +
                    '</div>' +
                    '<div style="text-align: center;"><div style="background:lightyellow;padding:10px;border:1px solid red;margin-bottom:15px;"><span style="color:red;">*) If you delete this data, then the data student in the study planning will be deleted too</span></div> ' +
                    '<button type="button" class="btn btn-danger" id="btnActDeleteTimeTables" data-group="'+ClassGroup+'" data-id="'+ScheduleID+'" style="margin-right: 5px;">Yes</button>' +
                    '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
                    '</div>');

                $('#NotificationModal').modal({
                    'backdrop' : 'static',
                    'show' : true
                });

            });

        }

    });

    $(document).on('click','#btnActDeleteTimeTables',function () {

        var ScheduleID = $(this).attr('data-id');
        var ClassGroup = $(this).attr('data-group');
        var SemesterID = $('#filterSemester').val();

        if(ScheduleID!='' && ScheduleID!=null &&
            SemesterID!='' && SemesterID!=null) {

            loading_button('#btnActDeleteTimeTables');
            $('button[data-dismiss=modal]').prop('disabled',true);

            var data = {
                action : 'deleteTimettables',
                ScheduleID : ScheduleID,
                SemesterID : SemesterID.split('.')[0]
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudSchedule';
            $.post(url,{token:token},function (jsonResult) {
                loadTimetables();

                var arrToken = {
                    Subject : 'Deleting Timetable | Group : '+ClassGroup,
                    URL : 'academic/timetables/list',
                    From : sessionName,
                    Icon : sessionUrlPhoto
                };
                var dataToken = jwt_encode(arrToken,'UAP)(*');
                addNotification(dataToken,null);

                setTimeout(function () {
                    $('#NotificationModal').modal('hide');
                },500);
            });

        }

    });

    function loadTimetables() {
        var filterProgramCampus = $('#filterProgramCampus').val();
        var filterSemester = $('#filterSemester').val();

        if(filterProgramCampus!='' && filterProgramCampus!=null
            && filterSemester!='' && filterSemester!=null){

            var div = $('#dataTimeTables');
            div.html('');

            var filterBaseProdi = $('#filterBaseProdi').val();
            var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null) ? filterBaseProdi.split('.')[0] : '';

            var filterCombine = $('#filterCombine').val();
            var CombinedClasses = (filterCombine!='' && filterCombine!=null) ? filterCombine : '';

            var filterDay = $('#filterDay').val();
            var DayID = (filterDay!='' && filterDay!=null) ? filterDay : '';


            div.html('' +
                '<div class="widget box widget-schedule">' +
                '    <div class="widget-header">' +
                '        <h4 class=""><span class="" style="color: #ffffff;padding: 5px;padding-left:10px;padding-right:10px;font-weight: bold;">Mo</span></h4>' +
                '       <div class="toolbar no-padding">' +
                '           <div class="btn-group">' +
                '               <a href="javacript:void(0);" id="btnDownloadLecturerAttendance"><span class="btn btn-xs" id="btn_addmk">' +
                '                   <i class="fa fa-download margin-right"></i> Lecturer Attendance' +
                '               </span></a>' +
                '           </div>' +
                '       </div>' +
                '    </div>' +
                '    <div class="widget-content no-padding">' +
                '<table class="table table-bordered table-striped" id="tableTimeTalbes">' +
                '    <thead>' +
                '    <tr style="background: #438882;color: #fff;">' +
                // '        <th style="width:3px;" class="th-center">No</th>' +
                '        <th style="width:1%;" class="th-center">No</th>' +
                '        <th style="width:7%;" class="th-center">Group</th>' +
                '        <th style="" class="th-center">Course</th>' +
                '        <th style="width:5%;" class="th-center">Credit</th>' +
                '        <th style="width:20%;" class="th-center">Lecturers</th>' +
                '        <th style="width:5%;" class="th-center">Students</th>' +
                '        <th style="width:5%;" class="th-center">Action</th>' +
                '        <th style="width:17%;" class="th-center">Day, Time</th>' +
                '    </tr>' +
                '    </thead>' +
                '    <tbody id="trDataSc"></tbody>' +
                '</table>' +
                '        <div id="">' +
                '        </div>' +
                '' +
                '    </div>' +
                '</div>');

            var data = {
                ProgramCampusID : filterProgramCampus,
                SemesterID : filterSemester.split('.')[0],
                ProdiID : ProdiID,
                CombinedClasses : CombinedClasses,
                DayID : DayID

            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__getTimetables';

            var dataTable = $('#tableTimeTalbes').DataTable( {
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
            } );

        }
    }

    $(document).on('click','.showStudent',function () {

        var url = base_url_js+'api/__crudSchedule';

        var SemesterID = $(this).attr('data-smtid');
        var ScheduleID = $(this).attr('data-scheduleid');
        var CDID = $(this).attr('data-cdid');

        var Course = $(this).attr('data-course');

        var data = {
            action : 'getDataStudents',
            Flag : 'sp',
            SemesterID : SemesterID,
            ScheduleID : ScheduleID,
            CDID : CDID
        };

        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {

            var dataHtml = '<div id="divStudent" style="text-align: center;"><h4>Student Not Yet</h4></div>';

            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">'+Course+'</h4>');
            $('#GlobalModal .modal-body').html(dataHtml);
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

            if(jsonResult.length>0){
                $('#divStudent').html('<div class="table-responsive">' +
                    '    <table class="table table-striped" id="tableShowStudent">' +
                    '        <thead>' +
                    '        <tr>' +
                    '            <th style="width: 1%;">No</th>' +
                    '            <th style="width: 5%;">NIM</th>' +
                    '            <th>Student</th>' +
                    '            <th style="width: 25%;">Action</th>' +
                    '        </tr>' +
                    '        </thead>' +
                    '        <tbody id="rowDataStudent"></tbody>' +
                    '    </table>' +
                    '</div>');
                var no = 1;
                for(var i=0;i<jsonResult.length;i++){
                    var d = jsonResult[i];

                    var btn = '<button class="btn btn-default btn-block btnActSwStd btnSetResign" id="btnActNo'+i+'" data-no="'+i+'" data-db="'+d.DB_Student+'" data-spid="'+d.SPID+'">Set Resign</button>';
                    if(d.StatusResign==1 || d.StatusResign=='1'){
                        btn = '<button class="btn btn-block btn-success btnActSwStd btnUnSetResign" id="btnActNo'+i+'" data-no="'+i+'" data-db="'+d.DB_Student+'" data-spid="'+d.SPID+'">Unset Resign</button>'
                    }

                    var co = (d.StatusResign==1 || d.StatusResign=='1') ? 'red' : '#3f51b5';

                    $('#rowDataStudent').append('<tr>' +
                        '<td style="color: #9e9e9e;">'+no+'</td>' +
                        '<td>'+d.NPM+'</td>' +
                        '<td style="text-align: left;color: '+co+';" id="viewName'+i+'">'+ucwords(d.Name)+'</td>' +
                        '<td>'+btn+'</td>' +
                        '</tr>');

                    no++;
                }
            }

            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });

        });

    });

    $(document).on('click','.btnSetResign',function () {

        if(confirm('Are you sure to submit?')){

            var No = $(this).attr('data-no');
            var SPID = $(this).attr('data-spid');
            var DB_Student = $(this).attr('data-db');

            var url = base_url_js+'api/__crudSchedule';
            var data = {
                action : 'updateStudyPlanningResignStatus',
                SPID : SPID,
                DB_Student : DB_Student,
                StatusResign : '1'
            };

            $('.btnActSwStd, button[data-dismiss=modal]').prop('disabled',true);
            loading_button('#btnActNo'+No);

            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {

                $('#btnActNo'+No).removeClass('btn-default btnSetResign')
                    .addClass('btn-success btnUnSetResign').html('Unset Resign');
                $('#viewName'+No).css('color','red');

                $('.btnActSwStd, button[data-dismiss=modal]').prop('disabled',false);

            });


        }

    });

    $(document).on('click','.btnUnSetResign',function () {
        if(confirm('Are you sure to submit?')){

            var No = $(this).attr('data-no');
            var SPID = $(this).attr('data-spid');
            var DB_Student = $(this).attr('data-db');

            var url = base_url_js+'api/__crudSchedule';
            var data = {
                action : 'updateStudyPlanningResignStatus',
                SPID : SPID,
                DB_Student : DB_Student,
                StatusResign : '0'
            };

            $('.btnActSwStd, button[data-dismiss=modal]').prop('disabled',true);
            loading_button('#btnActNo'+No);



            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {

                $('#btnActNo'+No).removeClass('btn-success btnUnSetResign')
                    .addClass('btn-default btnSetResign').html('Set Resign');
                $('#viewName'+No).css('color','#3f51b5');

                $('.btnActSwStd, button[data-dismiss=modal]').prop('disabled',false);

            });
        }
    });


    $(document).on('click','#btnDownloadLecturerAttendance',function () {
        $('#NotificationModal .modal-body').html('<div style="text-align: center;">' +
            '<div class="row"><div class="col-xs-4">Select day : </div><div class="col-xs-8"> <select class="form-control" id="selectdayToPDF"></select></div></div>' +
            '<div class="row"><div class="col-xs-12">' +
            '<hr/>' +
            '<button type="button" class="btn btn-success" id="btnSaveToPDF" style="margin-right: 5px;">Save PDF</button> | ' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '</div>' +
            '</div>' +
            '</div>');
        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });

        fillDays('#selectdayToPDF','Eng','');
    });

    $(document).on('click','#btnSaveToPDF',function () {
        var filterSemester = $('#filterSemester').val();
        var selectdayToPDF = $('#selectdayToPDF').val();

        if(filterSemester!='' && filterSemester!=null &&
            selectdayToPDF!='' && selectdayToPDF!=null){

            var url = base_url_js+'save2pdf/schedule-pdf';
            var data = {
                SemesterID : filterSemester.split('.')[0],
                DayID : selectdayToPDF
            };
            var token = jwt_encode(data,"UAP)(*");
            FormSubmitAuto(url, 'POST', [
                { name: 'token', value: token },
            ]);

        }

    });



    // Details Students
    $(document).on('click','.showStudentCuy',function () {
        var Course = $(this).attr('data-course');
        var no = $(this).attr('data-no');

        var detailsStudentCuy = $('#detailsStudentCuy'+no).val();

        var dataStudent = JSON.parse(detailsStudentCuy);

        var Planning = dataStudent.Planning;
        var dataHtmlModal = '';

        if(Planning.length>0){
            dataHtmlModal = '<div class="row">' +
                '    <div class="col-md-12">' +
                '        <table class="table table-striped">' +
                '            <thead>' +
                '            <tr>' +
                '                <th style="width: 1%;text-align: center;">No</th>' +
                '                <th style="text-align: center;">Students</th>' +
                '                <th style="width: 15%;text-align: center;">Status</th>' +
                '                <th style="width: 15%;text-align: center;">Attendance</th>' +
                '            </tr>' +
                '            </thead>' +
                '            <tbody id="loadDataStdCuy"></tbody>' +
                '        </table>' +
                '    </div>' +
                '</div>';
        }


        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+Course+'</h4>');
        $('#GlobalModal .modal-body').html(dataHtmlModal);
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        if(Planning.length>0){
            var noAsc =1;
            $.each(Planning,function (i,v) {

                var sts = ($.inArray(v.NPM,dataStudent.Approve)!=-1)
                    ? '<span style="color: green;"><i class="fa fa-check-circle"></i></span>' : '-';
                var stsClass = ($.inArray(v.NPM,dataStudent.Approve)!=-1)
                    ? '' : 'style="color:red;"';

                var arr_ID_Attd = [];
                if(v.Attendance.length>0){
                    $.each(v.Attendance,function (i2, v2) {
                        arr_ID_Attd.push(v2.ID_Attd);
                    });
                }

                var btnAttd = '<button class="btn btn-sm btn-default btn-default-danger btnRmvAttdStudent" data-no="'+noAsc+'" data-npm="'+v.NPM+'" data-attd="'+arr_ID_Attd.sort()+'">Dell</button>';
                // Cek Attendance
                if(v.TotalAttd < v.Attendance.length){
                    btnAttd = '<button class="btn btn-sm btn-default btn-default-success btnAddAttdStudent" data-no="'+noAsc+'" data-npm="'+v.NPM+'" data-attd="'+arr_ID_Attd.sort()+'">Add</button>';
                }

                $('#loadDataStdCuy').append('<tr '+stsClass+'>' +
                    '<td style="text-align: center;">'+noAsc+'</td>' +
                    '<td><span style="font-size: 15px;">'+v.Name+'</span><br/>'+v.NPM+'</td>' +
                    '<td style="text-align: center;">'+sts+'</td>' +
                    '<td style="text-align: center;" id="td_s'+noAsc+'">'+btnAttd+'</td>' +
                    '</tr>');

                noAsc +=1 ;
            });
        }


        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        $('.btnAddAttdStudent').click(function () {

            if(confirm('Are you sure?')){
                $('.btnRmvAttdStudent,.btnAddAttdStudent').prop('disabled',true);

                var No = $(this).attr('data-no');
                var NPM = $(this).attr('data-npm');
                var Arr_ID_Attd = $(this).attr('data-attd').split(',');

                var dataForm = [];
                if(Arr_ID_Attd.length>0){
                    for (var o=0;o<Arr_ID_Attd.length;o++){
                        var arr = {
                            ID_Attd : Arr_ID_Attd[o],
                            NPM : NPM,
                            UpdateBy : sessionNIP,
                            UpdateAt : dateTimeNow()
                        };
                        dataForm.push(arr);
                    }
                }
                var data = {
                    action : 'addAttendanceFromTimetables',
                    dataForm : dataForm
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api/__crudSchedule';

                $.post(url,{token:token},function (result) {
                    toastr.success('Updated','Success');
                    $('#td_s'+No).html('Added');
                    loadTimetables();
                    setTimeout(function () {
                        $('.btnRmvAttdStudent,.btnAddAttdStudent').prop('disabled',false);
                    },500);

                });
            }

        });

        $('.btnRmvAttdStudent').click(function () {

            if(confirm('Are you sure?')){
                $('.btnRmvAttdStudent,.btnAddAttdStudent').prop('disabled',true);

                var No = $(this).attr('data-no');
                var NPM = $(this).attr('data-npm');
                var Arr_ID_Attd = $(this).attr('data-attd').split(',');

                var dataForm = [];
                if(Arr_ID_Attd.length>0){
                    for (var o=0;o<Arr_ID_Attd.length;o++){
                        var arr = {
                            ID_Attd : Arr_ID_Attd[o],
                            NPM : NPM,
                            UpdateBy : sessionNIP,
                            UpdateAt : dateTimeNow()
                        };
                        dataForm.push(arr);
                    }
                }
                var data = {
                    action : 'removeAttendanceFromTimetables',
                    dataForm : dataForm
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api/__crudSchedule';

                $.post(url,{token:token},function (result) {
                    toastr.success('Updated','Success');
                    $('#td_s'+No).html('Deleted');
                    loadTimetables();
                    setTimeout(function () {
                        $('.btnRmvAttdStudent,.btnAddAttdStudent').prop('disabled',false);
                    },500);

                });
            }

        });


    });



</script>