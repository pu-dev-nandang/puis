

<div class="thumbnail" style="margin-bottom: 10px;">
    <div class="row">
        <div class="col-xs-2" style="">
            <select class="form-control" id="filterST_ProgramCampus"></select>
        </div>
        <div class="col-xs-2" style="">
            <select id="filterST_Semester" class="form-control">
            </select>
        </div>
        <div class="col-xs-3" style="">
            <select id="filterST_BaseProdi" class="form-control"></select>
        </div>

        <div class="col-xs-2 hide">
            <select class="form-control form-filter-jadwal" id="filterST_Combine">
                <option value="">-- Show All --</option>
                <option value="1">Combine Class Yes</option>
                <option value="0">Combine Class No</option>
            </select>
        </div>

        <div class="col-xs-2" style="">
            <div id="selectST_SemesterSc"></div>
        </div>
    </div>

</div>

<div id="dataST_Scedule" style="margin-top: 30px;">
</div>
<script>
    $(document).ready(function () {
        // $('.form-filter-jadwal').prop("disabled",false);
        window.checkedDay = [];
        $('#filterST_ProgramCampus').empty();
        loadSelectOptionProgramCampus('#filterST_ProgramCampus','');

        $('#filterST_BaseProdi').empty();
        // $('#filterST_BaseProdi').append('<option value="">-- All Programme Study --</option>' +
        //     '<option disabled>------------------------------------------</option>');
        loadSelectOptionBaseProdi('#filterST_BaseProdi','');


        $('#filterST_Semester').empty();
        // $('#filterSemester').append('<option value="" disabled selected>-- Year Academic--</option>' +
        //     '                <option disabled>------------------------------------------</option>');
        loSelectOptionSemester('#filterST_Semester','');
        loadST_semester();

        setTimeout(function () {
            filterST_Schedule();
        },1000);

    });
    
    function filterST_Schedule() {
        var ProgramsCampusID = $('#filterST_ProgramCampus').find(':selected').val();
        var SemesterID = $('#filterST_Semester').find(':selected').val().split('.')[0];
        var Prodi = $('#filterST_BaseProdi').find(':selected').val();
        var ProdiID = (Prodi!='') ? Prodi.split('.')[0] : '';
        var CombinedClasses = $('#filterST_Combine').find(':selected').val();
        var Semester = $('#filterST_SemesterSchedule').find(':selected').val();

        getST_Schedule(ProgramsCampusID,SemesterID,ProdiID,CombinedClasses,Semester);
    }

    function getST_Schedule(ProgramsCampusID,SemesterID,ProdiID,CombinedClasses,Semester) {
        var div = $('#dataST_Scedule');
        div.html('');
        if(SemesterID!=null && SemesterID!=''){

            var tr_bg_color = '#555555';
            var semesterView = $('#filterST_SemesterSchedule option:selected').text();
            var prodiView = $('#filterST_BaseProdi option:selected').text();
            div.html('' +
                '<div class="widget box widget-schedule">' +
                '    <div class="widget-header">' +
                '        <h4 class=""><span class="label-info" style="color: #ffffff;padding: 5px;padding-left:10px;padding-right:10px;font-weight: bold;">'+semesterView+'</span> <span  class="label-danger" style="color: #ffffff;padding: 5px;padding-left:10px;padding-right:10px;font-weight: bold;">'+prodiView+'</span></h4>' +
                '    </div>' +
                '    <div class="widget-content no-padding">' +
                '<table class="table table-bordered table-striped" id="tableST_Schedule">' +
                '    <thead>' +
                '    <tr style="background: '+tr_bg_color+';color: #fff;">' +
                // '        <th style="width:1%;" class="th-center">No</th>' +
                '        <th style="width:9%;" class="th-center">Group</th>' +
                '        <th style="" class="th-center">Course</th>' +
                '        <th style="width:5%;" class="th-center">Credit</th>' +
                '        <th style="width:20%;" class="th-center">Lecturers</th>' +
                '        <th style="width:5%;" class="th-center">Students</th>' +
                '        <th style="width:17%;" class="th-center">Date, Time</th>' +
                '        <th style="width:7%;" class="th-center">Room</th>' +

                // '        <th class="th-center">Action</th>' +
                '    </tr>' +
                '    </thead>' +
                '    <tbody id="trDataSc"></tbody>' +
                '</table>' +
                '        <div id="">' +
                '        </div>' +
                '    </div>' +
                '</div>');

            var data = {
                action : 'readPerSemester',
                DayID : $('#filterDay').val(),
                dataWhere  : {
                    ProgramsCampusID : ProgramsCampusID,
                    SemesterID : SemesterID,
                    ProdiID : ProdiID,
                    CombinedClasses : CombinedClasses,
                    IsSemesterAntara : ''+SemesterAntara,
                    Semester : Semester
                }
            };

            var token = jwt_encode(data,'UAP)(*');

            var dataTable = $('#tableST_Schedule').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "bLengthChange" : false,
                "bInfo" : false,
                "bFilter" : false,
                "language": {
                    "searchPlaceholder": "Group, (Co)Lecturer, Classroom"
                },
                "ajax":{
                    url : base_url_js+"api/__getSchedulePerSemester?token="+token, // json datasource
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
