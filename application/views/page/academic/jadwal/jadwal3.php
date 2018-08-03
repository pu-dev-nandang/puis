

<div class="thumbnail" style="margin-bottom: 10px;">
    <div class="row">
        <div class="col-xs-2 hide" style="">
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
        <div class="col-xs-2">

            <div id="selectSemesterSc">
                <select class="form-control" id="filterSemesterSchedule"></select>
            </div>
        </div>
        <div class="col-xs-1">
            <!-- Single button -->
            <div class="btn-group">
                <button type="button" id="btnDropdownExport" disabled class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-download"></i> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" style="min-width: 100px;">
                    <li><a href="#" id="btnSchedule2PDF" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a></li>
                    <li><a href="#" id="btnSchedule2Excel" target="_blank"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a></li>
                </ul>
            </div>
        </div>
        <div class="col-xs-2" style="">
            <select class="form-control form-filter-jadwal" id="filterDay">
                <option value="1">Monday</option>
                <option value="2">Tuesday</option>
                <option value="3">Wednesday</option>
                <option value="4">Thrusday</option>
                <option value="5">Friday</option>
                <option value="6">Saturday</option>
                <option value="7">Sunday</option>
            </select>
        </div>
    </div>


</div>

<div class="thumbnail hide" style="padding: 5px;">
    <label class="checkbox-inline">
        <input type="checkbox" id="filterDayCheckAll" class="filterDay" value="0" checked> All Days
    </label>
    <label class="checkbox-inline">
        <input type="checkbox" class="filterDay" value="1"> Monday
    </label>
    <label class="checkbox-inline">
        <input type="checkbox" class="filterDay" value="2"> Tuesday
    </label>
    <label class="checkbox-inline">
        <input type="checkbox" class="filterDay" value="3"> Wednesday
    </label>
    <label class="checkbox-inline">
        <input type="checkbox" class="filterDay" value="4"> Thrusday
    </label>
    <label class="checkbox-inline">
        <input type="checkbox" class="filterDay" value="5"> Friday
    </label>
    <label class="checkbox-inline" style="color: red;">
        <input type="checkbox" class="filterDay" value="6"> Saturday
    </label>
    <label class="checkbox-inline" style="color: red;">
        <input type="checkbox" class="filterDay" value="7"> Sunday
    </label>
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
        $('#filterSemester').append('<option value="" disabled selected>-- Year Academic--</option>' +
            '                <option disabled>------------------------------------------</option>');
        loSelectOptionSemester('#filterSemester','');

        loadAcademicYearOnPublish();

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


                $('#selectSemesterSc').html('<select class="form-control" id="filterSemesterSchedule"></select>');
                // $('#filterSemesterSchedule').empty();
                $('#filterSemesterSchedule').append('<option value="">-- All Semester --</option>' +
                    '                <option disabled>------------</option>');
                loadSelectOPtionAllSemester('#filterSemesterSchedule','',data_json.ID,SemesterAntara);
            },1000);

        });
    }

    function filterSchedule() {
        var ProgramsCampusID = $('#filterProgramCampus').find(':selected').val();
        var SemesterID = $('#filterSemester').find(':selected').val().split('.')[0];
        var Prodi = $('#filterBaseProdi').find(':selected').val();
        var ProdiID = (Prodi!='') ? Prodi.split('.')[0] : '';
        var CombinedClasses = $('#filterCombine').find(':selected').val();
        var filterSemesterSchedule = $('#filterSemesterSchedule').find(':selected').val();
        var Semester = (filterSemesterSchedule!='' && filterSemesterSchedule!=null) ? filterSemesterSchedule.split('|')[0] : filterSemesterSchedule;

        getSchedule(ProgramsCampusID,SemesterID,ProdiID,CombinedClasses,Semester);
    }

    function getSchedule(ProgramsCampusID,SemesterID,ProdiID,CombinedClasses,Semester) {
        // if(SemesterID!=null && SemesterID!='' && ProdiID!=null && ProdiID!=''){
        if(SemesterID!=null && SemesterID!=''){

            loading_page('#dataScedule');

            $('#btnDropdownExport').prop('disabled',true);

            var data = {
                action : 'read',
                DayID : $('#filterDay').val(),
                dataWhere  : {
                    ProgramsCampusID : ProgramsCampusID,
                    SemesterID : SemesterID,
                    ProdiID : ProdiID,
                    CombinedClasses : CombinedClasses,
                    IsSemesterAntara : ''+SemesterAntara,
                    Semester : Semester
                    // Days : checkedDay,
                    // DaysName : {
                    //     Eng : daysEng,
                    //     Ind : daysInd
                    // }
                }
            };

            var url = base_url_js+'api/__crudSchedule';
            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (data_result) {
                var div = $('#dataScedule');

                setTimeout(function () {

                    var dataToExport = [];
                    if(data_result.length>0){
                        div.html('');
                        $('input[type=checkbox][class=filterDay]').prop('checked',false);
                        $('#filterDayCheckAll').prop('checked',true);
                        checkedDay = [];

                        for(var i=0;i<data_result.length;i++){

                            var dataToExportPerDay = [];

                            var classDay = (i>4) ? 'label-danger' : 'label-info';
                            var tr_bg_color = (i>4) ? '#884343c7' : '#438882';

                            div.append('' +
                                '<div class="widget box widget-schedule" id="dayWidget'+data_result[i].Day.ID+'">' +
                                '    <div class="widget-header">' +
                                '        <h4 class=""><span class="'+classDay+'" style="color: #ffffff;padding: 5px;padding-left:10px;padding-right:10px;font-weight: bold;">'+data_result[i].Day.NameEng+'</span></h4>' +
                                '    </div>' +
                                '    <div class="widget-content no-padding">' +
                                '<table class="table table-bordered table-striped" id="scTable'+i+'">' +
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
                                '    <tbody id="trData'+i+'"></tbody>' +
                                '</table>' +
                                '        <div id="">' +
                                '        </div>' +
                                '' +
                                '    </div>' +
                                '</div>');

                            var table = $('#trData'+i);
                            var sc = data_result[i].Details;
                            var no = 1;


                            if(sc.length>0){
                                for(var r=0;r<sc.length;r++){



                                    var gabungan = (sc[r].CombinedClasses==0) ? 'No' : 'Yes';

                                    // var StartSessions = moment()
                                    //     .hours(sc[r].StartSessions.split(':')[0])
                                    //     .minutes(sc[r].StartSessions.split(':')[1])
                                    //     .format('LT');

                                    var StartSessions = sc[r].StartSessions.substr(0,5);

                                    // var EndSessions = moment()
                                    //     .hours(sc[r].EndSessions.split(':')[0])
                                    //     .minutes(sc[r].EndSessions.split(':')[1])
                                    //     .format('LT');

                                    var EndSessions = sc[r].EndSessions.substr(0,5);

                                    var teamTeaching = '';
                                    var DetailCourse = sc[r].DetailCourse;

                                    if(DetailCourse.length>0){

                                        var dataTeamTeaching = [];
                                        if(sc[r].TeamTeaching==1){

                                            for(var t=0;t<sc[r].DetailTeamTeaching.length;t++){
                                                var tcm = sc[r].DetailTeamTeaching;
                                                teamTeaching = teamTeaching +'<div style="margin-bottom: 7px;"><span class="label label-info-inline"><b>'+tcm[t].Lecturer+'</b></span></div>';
                                                dataTeamTeaching.push(tcm[t].Lecturer);
                                            }
                                        }

                                        var Subsesi = (sc[r].SubSesi==1)? '<span class="label label-warning">Sub-Sesi</span>' :'';

                                        var data2Token = {
                                            Group : sc[r].ClassGroup,
                                            Coordinator : sc[r].Lecturer,
                                            Students : sc[r].StudentsDetails
                                        };

                                        var tokenStd = jwt_encode(data2Token,'UAP)(*');

                                        table.append('<tr>' +
                                            '<td class="td-center"><b><a href="javascript:void(0)" class="btn-action" data-page="editjadwal" data-id="'+sc[r].ID+'">'+sc[r].ClassGroup+'</a></b><br/>'+Subsesi+'</td>' +
                                            '<td><ul id="listCourse'+i+''+r+'" style="padding-left:0px;list-style-type: none;"></ul></td>' +
                                            '<td class="td-center">'+sc[r].Credit+'</td>' +
                                            '<td>' +
                                            '<div style="color: #427b44;margin-bottom: 10px;"><b>'+sc[r].Lecturer+'</b></div>'+teamTeaching+
                                            '</td>' +
                                            '<td class="td-center"><a href="javascript:void(0);" data-std="'+tokenStd+'" class="btnDetailStudents">'+sc[r].StudentsDetails.length+'</a></td>' +
                                            '<td class="td-center">'+StartSessions+' - '+EndSessions+'</td>' +
                                            '<td class="td-center">'+sc[r].Room+'</td>' +
                                            '</tr>');

                                        var ls = $('#listCourse'+i+''+r);

                                        var lscss = (DetailCourse.length>1) ? 'style="margin-bottom: 15px;"' : '';
                                        for(var s=0;s<DetailCourse.length;s++){
                                            var course = DetailCourse[s];
                                            var baseSmt = (course.Semester!=course.BaseSemester) ? '('+course.BaseSemester+')' : '';
                                            ls.append('<li '+lscss+'><b>'+course.MKNameEng+'</b><br/><i>'+course.MKName+'</i><br/>' +
                                                '<span class="label label-default">'+course.MKCode+'</span> | <span class="label label-success-inline"><b>'+course.ProdiEng+'</b></span> | ' +
                                                '<span class="label label-danger-inline"><b>Semester '+course.Semester+' '+baseSmt+'</b></span></li>');
                                        }

                                        var p_data_course = {
                                            Time : StartSessions+' - '+EndSessions,
                                            ClassRoom : sc[r].Room,
                                            ClassGroup : sc[r].ClassGroup,
                                            Course : DetailCourse[0].MKNameEng,
                                            CombinedClasses : sc[r].CombinedClasses,
                                            Coordinator : sc[r].Lecturer,
                                            TeamTeaching : dataTeamTeaching,
                                            Students : sc[r].StudentsDetails.length
                                        };

                                        dataToExportPerDay.push(p_data_course);

                                    }




                                    no += 1;
                                }

                            }
                            else {
                                table.append('<tr>' +
                                    '<td colspan="7">Schedule Not Yet</td>' +
                                    '</tr>');
                            }

                            var Program = ($('#filterProgramCampus').val() == '') ? 'All' : $('#filterProgramCampus option:selected').text().trim();
                            var AcademicYear = ($('#filterSemester').val() == '') ? 'All' : $('#filterSemester option:selected').text().trim();
                            var Prodi = ($('#filterBaseProdi').val() == '') ? 'All' : $('#filterBaseProdi option:selected').text().trim();
                            var Combine = ($('#filterCombine').val() == '') ? 'All' : $('#filterCombine').val();
                            var Semester = ($('#filterSemesterSchedule').val() == '') ? 'All' : $('#filterSemesterSchedule').val();

                            var dataToExportPerDayDetails = {
                                DayID : data_result[i].Day.ID,
                                DayNameEng : data_result[i].Day.NameEng,
                                SemesterDetails : {
                                    Program : Program,
                                    AcademicYear : AcademicYear,
                                    Prodi : Prodi,
                                    Combine : (Combine==1)? 'Yes' : 'No',
                                    Semester : Semester.split('|')[0]
                                },
                                CourseDetails : dataToExportPerDay
                            };

                            dataToExport.push(dataToExportPerDayDetails);

                        }

                    }
                    else {
                        div.append('<h1>Data Kosong</h1>');
                    }

                    // console.log(dataToExport);
                    token2export = jwt_encode(dataToExport,"UAP)(*");

                    $('#btnSchedule2PDF').attr('href',base_url_js+'save2pdf/schedule-pdf?token='+token2export);
                    $('#btnSchedule2Excel').attr('href',base_url_js+'save2pdf/schedule-excel?token='+token2export);
                    $('#btnDropdownExport').prop('disabled',false);

                },100);

            });
        }

    }
</script>