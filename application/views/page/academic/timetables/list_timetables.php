
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
                <div class="col-xs-3">
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
                <div class="col-xs-1">
                    <button class="btn btn-block btn-default"><i class="fa fa-download"></i></button>
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
                    '<button type="button" class="btn btn-danger" id="btnActDeleteTimeTables" data-id="'+ScheduleID+'" style="margin-right: 5px;">Yes</button>' +
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
                '               <a href="'+base_url_js+'save2pdf/schedule-pdf?token=" target="_blank"><span class="btn btn-xs" id="btn_addmk">' +
                '                   <i class="icon-download"></i> Download PDF' +
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
</script>