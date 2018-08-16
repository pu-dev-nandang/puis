

<div class="thumbnail" style="margin-bottom: 10px;">
    <div class="row">
        <div class="col-xs-2" style="">
            <select class="form-control form-filter-jadwal" id="filterProgramCampus"></select>
        </div>
        <div class="col-xs-2" style="">
            <select id="filterSemester" class="form-control form-filter-jadwal">
            </select>
        </div>
        <div class="col-xs-3" style="">
            <select id="filterBaseProdi" class="form-control form-filter-jadwal"></select>
        </div>

        <div class="col-xs-2" style="">
            <select class="form-control form-filter-jadwal" id="filterCombine">
                <option value="">-- Show All --</option>
                <option value="1">Combine Class Yes</option>
                <option value="0">Combine Class No</option>
            </select>
        </div>

<!--        <div class="col-xs-1 hide">-->

<!--            <div class="btn-group">-->
<!--                <button type="button" id="btnDropdownExport" disabled class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                    <i class="fa fa-download"></i> <span class="caret"></span>-->
<!--                </button>-->
<!--                <ul class="dropdown-menu" style="min-width: 100px;">-->
<!--                    <li><a href="#" id="btnSchedule2PDF" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a></li>-->
<!--                    <li><a href="#" id="btnSchedule2Excel" target="_blank"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a></li>-->
<!--                </ul>-->
<!--            </div>-->
<!--        </div>-->
        <div class="col-xs-2" style="">
            <select class="form-control form-filter-jadwal" id="filterDay"></select>
        </div>
    </div>

</div>

<div id="dataScedule" style="margin-top: 30px;">
</div>

<script>
    $(document).ready(function () {

        window.token2export = '';

        $('.form-filter-jadwal').prop("disabled",false);
        window.checkedDay = [];
        $('#filterProgramCampus').empty();
        loadSelectOptionProgramCampus('#filterProgramCampus','');

        $('#filterBaseProdi').empty();
        $('#filterBaseProdi').append('<option value="">-- All Programme Study --</option>' +
            '<option disabled>------------------------------------------</option>');
        loadSelectOptionBaseProdi('#filterBaseProdi','');


        $('#filterSemester').empty();
        // $('#filterSemester').append('<option value="" disabled selected>-- Year Academic--</option>' +
        //     '                <option disabled>------------------------------------------</option>');
        loSelectOptionSemester('#filterSemester','');

        loadAcademicYearOnPublish();

        fillDays('#filterDay','Eng','');

    });

    function loadAcademicYearOnPublish() {
        var url = base_url_js+"api/__getAcademicYearOnPublish";
        $.getJSON(url,function (data_json) {
            // console.log(data_json);
            setTimeout(function () {
                var program = $('#filterProgramCampus').val();
                getSchedule(program,data_json.ID,'','','');
                var selectedYear = data_json.ID+'.'+data_json.Year+'.'+data_json.Code;
                $('#filterSemester').val(selectedYear);
            },500);

        });
    }

    function filterSchedule() {
        var ProgramsCampusID = $('#filterProgramCampus').find(':selected').val();
        var SemesterID = $('#filterSemester').find(':selected').val().split('.')[0];
        var Prodi = $('#filterBaseProdi').find(':selected').val();
        var ProdiID = (Prodi!='') ? Prodi.split('.')[0] : '';
        var CombinedClasses = $('#filterCombine').find(':selected').val();

        getSchedule(ProgramsCampusID,SemesterID,ProdiID,CombinedClasses);
    }

    function getSchedule(ProgramsCampusID,SemesterID,ProdiID,CombinedClasses) {

        var div = $('#dataScedule');
        div.html('');

        if(SemesterID!=null && SemesterID!=''){
            // $('#dataScedule').html('');

            var classDay = 'label-info';
            var tr_bg_color = '#438882';
            var dayView = $('#filterDay option:selected').text();
            div.html('' +
                '<div class="widget box widget-schedule">' +
                '    <div class="widget-header">' +
                '        <h4 class=""><span class="'+classDay+'" style="color: #ffffff;padding: 5px;padding-left:10px;padding-right:10px;font-weight: bold;">'+dayView+'</span></h4>' +
                '    </div>' +
                '    <div class="widget-content no-padding">' +
                '<table class="table table-bordered table-striped" id="tableSchedule">' +
                '    <thead>' +
                '    <tr style="background: '+tr_bg_color+';color: #fff;">' +
                // '        <th style="width:3px;" class="th-center">No</th>' +
                '        <th style="width:9%;" class="th-center">Group</th>' +
                '        <th style="" class="th-center">Course</th>' +
                '        <th style="width:5%;" class="th-center">Credit</th>' +
                '        <th style="width:20%;" class="th-center">Lecturers</th>' +
                '        <th style="width:5%;" class="th-center">Students</th>' +
                '        <th style="width:17%;" class="th-center">Time</th>' +
                '        <th style="width:7%;" class="th-center">Room</th>' +

                // '        <th class="th-center">Action</th>' +
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
                action : 'read',
                DayID : $('#filterDay').val(),
                dataWhere  : {
                    ProgramsCampusID : ProgramsCampusID,
                    SemesterID : SemesterID,
                    ProdiID : ProdiID,
                    CombinedClasses : CombinedClasses,
                    IsSemesterAntara : ''+SemesterAntara

                }
            };

            var token = jwt_encode(data,'UAP)(*');

            var dataTable = $('#tableSchedule').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "Group, (Co)Lecturer, Classroom"
                },
                "ajax":{
                    url : base_url_js+"api/__getSchedulePerDay?token="+token, // json datasource
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