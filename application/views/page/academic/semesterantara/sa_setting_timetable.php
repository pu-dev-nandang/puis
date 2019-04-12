
<style>
    .btn-sm-round {
        border-radius: 36px;
        padding: 6px;
        padding-top: 1px;
        padding-bottom: 1px;
    }
    #tableCourse tr th, #tableCourse tr td {
        text-align: center;
    }
</style>

<input id="formSASemesterID" value="<?= $SASemesterID; ?>" class="hide" />
<input id="formScheduleIDSA" class="hide" value="<?=$ScheduleIDSA;?>">
<textarea id="dataScheduleSA" class="hide"><?=$dataScheduleSA;?></textarea>

<div class="row">
    <div class="col-md-8">
        <div class="well" style="min-height: 100px;">
            <h4 style="border-left: 7px solid orangered;padding-left: 7px;font-weight: bold;">List of Courses</h4>

            <hr/>
            <div id="viewLoadTable"></div>

        </div>
    </div>
    <div class="col-md-4">
        <div class="thumbnail" style="min-height: 100px;margin-bottom: 20px;padding: 15px;">
            <h4 style="border-left: 7px solid orangered;padding-left: 7px;font-weight: bold;">List of Selected Courses</h4>
            <textarea class="hide" id="formListCourse"></textarea>
            <hr/>
            <ul id="showSelectedCourse"></ul>

            <div id="">
                <hr/>
                <ul id="showSelectedCoursePerluHapus"></ul>
            </div>
        </div>

        <div class="thumbnail" style="min-height: 100px;padding: 15px;">
            <table class="table">
                <tbody>
                <tr>
                    <td style="width: 25%;">Group <span style="font-size: 10px;color: red;">*</span></td>
                    <td style="width: 1%;">:</td>
                    <td>
                        <input class="form-control" id="formClassGroup" onkeyup="var start = this.selectionStart;
                                                                                var end = this.selectionEnd;this.value = this.value.toUpperCase();this.setSelectionRange(start, end);" style="width: 170px;"/></td>
                </tr>
                <tr>
                    <td>Coordinator <span style="font-size: 10px;color: red;">*</span></td>
                    <td>:</td>
                    <td>
                        <select class="select2-select-00 full-width-fix"
                                size="5" id="formCoordinator">
                            <option value=""></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Team Teaching</td>
                    <td>:</td>
                    <td>
                        <select class="select2-select-00 full-width-fix"
                                size="5" multiple id="formTeamTeaching"></select>
                    </td>
                </tr>
                <tr>
                    <td>Day</td>
                    <td>:</td>
                    <td>
                        <select class="form-control" id="formDay" style="width: 170px;"></select>
                    </td>
                </tr>
                <tr>
                    <td>Time</td>
                    <td>:</td>
                    <td>
                        <div class="row">
                            <div class="col-xs-5">
                                <div id="div_formSesiAwal" data-no="1" class="input-group">
                                    <input data-format="hh:mm" type="text" id="formStart" class="form-control" value="00:00" placeholder="Start"/>
                                    <span class="add-on input-group-addon">
                                        <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-5">
                                <div id="div_formSesiAkhir" data-no="1" class="input-group">
                                    <input data-format="hh:mm" type="text" id="formEnd" class="form-control" value="00:00" placeholder="End"/>
                                    <span class="add-on input-group-addon">
                                        <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Room <span style="font-size: 10px;color: red;">*</span></td>
                    <td>:</td>
                    <td>
                        <select class="select2-select-00 form-exam" style="max-width: 300px !important;" size="5" id="formClassroom">
                            <option value=""></option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>

            <div style="text-align: right;">
                <p style="text-align: left;padding-left: 15px;color: red;">*) Required</p>
                <hr/>

                <div id="btnActSetTTM">
                    <button class="btn btn-success" id="submitSetTimetables">Submit</button>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    
    $(document).ready(function () {

        window.dataScheduleSA = $('#dataScheduleSA').val();
        window.dataSSA = JSON.parse(dataScheduleSA);

        var cor = '';
        var ttc = '';
        var dayS = '';
        var cls = '';
        if(dataSSA.length>0){
            // console.log(dataSSA);
            var da = dataSSA[0];
            $('#formClassGroup').val(da.ClassGroup);

            cor = da.Coordinator;
            dayS = da.DayID;
            cls = da.ClassroomID+'.'+da.Seat+'.'+da.SeatForExam;

            $('#formStart').val(da.Start.substr(0,5));
            $('#formEnd').val(da.End.substr(0,5));

            if(da.TeamTeaching.length>0){
                ttc = da.TeamTeaching;
            }

            if(da.DataCourse.length>0){
                $('#formListCourse').val(JSON.stringify(da.DataCourse));
            }

            $('#btnActSetTTM').prepend('<button class="btn btn-danger" id="removeTimetablesSA" style="float: left;">Remove</button>');
        }

        loadSelectOptionLecturersSingle('#formCoordinator',cor);
        loadSelectOptionLecturersSingle('#formTeamTeaching',ttc);
        fillDays('#formDay','Eng',dayS);
        loadSelect2OptionClassroom('#formClassroom',cls);

        loadCourse();

        $('#div_formSesiAwal,#div_formSesiAkhir').datetimepicker({
            pickDate: false,
            pickSeconds : false
        }).on('changeDate', function(e) {
            // var d = new Date(e.localDate);
            // var no = $(this).attr('data-no');
            // var TimePerCredit = $('#formTimePerCredit'+no).val();
            // var Credit = $('#formCredit'+no).val()
            //
            // var totalTime = parseInt(TimePerCredit) * parseInt(Credit);
            //
            // var sesiAkhir = moment().hours(d.getHours()).minutes(d.getMinutes()).add(parseInt(totalTime), 'minute').format('HH:mm');
            //
            // $('#formSesiAkhir'+no).val(sesiAkhir);
            // checkSchedule(no);
        });

    });

    $('#submitSetTimetables').click(function () {

        var formSASemesterID = $('#formSASemesterID').val();
        var formClassGroup = $('#formClassGroup').val();
        var formCoordinator = $('#formCoordinator').val();
        var formTeamTeaching = $('#formTeamTeaching').val();

        var formDay = $('#formDay').val();
        var formStart = $('#formStart').val();
        var formEnd = $('#formEnd').val();
        var formClassroom = $('#formClassroom').val();

        var formListCourse = $('#formListCourse').val();
        var ArrIDSSD = JSON.parse(formListCourse);

        if(formClassGroup != '' && formClassGroup!=null &&
            formClassroom != '' && formClassroom!=null && ArrIDSSD.length>0){

            var formScheduleIDSA = $('#formScheduleIDSA').val();

            loading_button('#submitSetTimetables');

            var ClassroomID = formClassroom.split('.')[0];

            var dataSch = (dataSSA.length>0) ?
                { SASemesterID : formSASemesterID,
                    ClassGroup : formClassGroup.toUpperCase(),
                    Coordinator : formCoordinator,
                    DayID : formDay,
                    Start : formStart,
                    End : formEnd,
                    ClassroomID : ClassroomID,
                    UpdatedBy : sessionNIP,
                    UpdatedAt : dateTimeNow() }
                :
                { SASemesterID : formSASemesterID,
                    ClassGroup : formClassGroup.toUpperCase(),
                    Coordinator : formCoordinator,
                    DayID : formDay,
                    Start : formStart,
                    End : formEnd,
                    ClassroomID : ClassroomID,
                    EnteredBy : sessionNIP,
                    EnteredAt : dateTimeNow() };


            var data = {
                action : 'actionSASchedule',
                type : (dataSSA.length>0) ? 'update' : 'insert',
                ScheduleIDSA : (dataSSA.length>0) ? formScheduleIDSA : '',
                dataSch : dataSch,
                TeamTeaching : formTeamTeaching,
                ArrIDSSD : ArrIDSSD
            };

            var url = base_url_js+'api2/__crudSemesterAntara';
            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {

                console.log(jsonResult);

                if(jsonResult.Status<=0){
                    $('#formClassGroup').css('border','1px solid red');
                    toastr.error('Class Group Exist','Error');
                    setTimeout(function () {
                        $('#submitSetTimetables').prop('disabled',false).html('Submit');
                    },500);
                } else {
                    toastr.success('Timetable saved','Success');
                    setTimeout(function () {
                        window.location.href = '';
                    },500);
                }

            });

        } else {
            toastr.warning('Form required','Warning');
        }

    });

    $(document).on('click','#removeTimetablesSA',function () {

        if(confirm('Are you sure to remove?')){
            var formScheduleIDSA = $('#formScheduleIDSA').val();
            var formSASemesterID = $('#formSASemesterID').val();
            if(formScheduleIDSA!='' && formSASemesterID!=''){
                var data = {
                    action : 'actionSASchedule',
                    type : 'remove',
                    ScheduleIDSA : formScheduleIDSA
                };

                var url = base_url_js+'api2/__crudSemesterAntara';
                var token = jwt_encode(data,'UAP)(*');

                $.post(url,{token:token},function (result) {
                    toastr.success('Timetable removed','Success');
                    setTimeout(function () {
                        window.location.replace(base_url_js+'academic/semester-antara/timetable/'+formSASemesterID);
                    },500);
                })
            }
        }

    });


    // ====== Course ===========
    
    function loadCourse() {

        var dataSemester = JSON.parse($('#dataSemester').val());


        var data = {
            action : 'loadCourseSemesterAntara',
            SASemesterID : dataSemester[0].ID
        };

        var url = base_url_js+'api2/__crudSemesterAntara';
        var token = jwt_encode(data,'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                
                $('#viewLoadTable').html('<table class="table" id="tableCourse">' +
                    '                <thead>' +
                    '                <tr>' +
                    '                    <th style="width: 1%;">No</th>' +
                    '                    <th style="width: 10%;">Code</th>' +
                    '                    <th>Course</th>' +
                    '                    <th style="width: 10%;">Student</th>' +
                    '                    <th style="width: 10%;">Action</th>' +
                    '                </tr>' +
                    '                </thead>' +
                    '               <tbody id="listCourse"></tbody>' +
                    '            </table>');

                $('#showSelectedCourse,#showSelectedCoursePerluHapus').empty();

                var no =1;
                var fillListCourse = [];
                var formListCourse = $('#formListCourse').val();
                var arrIDSSD = (formListCourse!='') ? JSON.parse(formListCourse) : [];

                $.each(jsonResult,function (i,v) {

                    var Students = v.Students;

                    var btn = ($.inArray(''+v.IDSSD,arrIDSSD)!=-1)
                        ? '-'
                        : '<button class="btn btn-sm btn-success btn-sm-round btn-act addCourse" data-id="'+v.IDSSD+'"><i class="fa fa-arrow-right"></i></button>';

                    $('#listCourse').append('<tr>' +
                        '<td style="border-right: 1px solid #CCCCCC;">'+no+'</td>' +
                        '<td>'+v.MKCode+'</td>' +
                        '<td style="text-align: left;font-size: 15px;font-weight: bold;">'+v.CourseEng+'</td>' +
                        '<td><a href="javascript:void(0);" class="btnShowStd" data-id="'+v.IDSSD+'">'+Students.length+' Students</a></td>' +
                        '<td style="border-left: 1px solid #CCCCCC;">'+btn+'</td>' +
                        '</tr>');

                    // $('#listStudent').append('<textarea id="showStd_'+v.IDSSD+'">'+JSON.stringify(Students)+'</textarea>' +
                    //     '<textarea id="showCourse_'+v.IDSSD+'">'+JSON.stringify(v)+'</textarea>');

                    if($.inArray(''+v.IDSSD,arrIDSSD)!=-1){
                        fillListCourse.push(v.IDSSD);
                        $('#showSelectedCourse').append('<li style="margin-bottom:5px;" id="listSelc'+v.IDSSD+'">'+v.CourseEng+' ' +
                            '| <a href="javascript:void(0);" class="btnShowStd" data-id="'+v.IDSSD+'">'+Students.length+' student</a> ' +
                            '| <button class="btn btn-sm btn-danger btn-sm-round btn-act removeCourse" data-id="'+v.IDSSD+'"><i class="fa fa-trash"></i></button></li>');
                    }

                    no++;

                });

                // Cek apakah ada yang perlu dihapus

                if(fillListCourse.length!=arrIDSSD.length){
                    for(var p=0;p<arrIDSSD.length;p++){
                        if($.inArray(''+arrIDSSD[p],fillListCourse)==-1){
                            $('#showSelectedCoursePerluHapus').append('<li style="margin-bottom:5px;color: red;">Please remove this ID : '+arrIDSSD[p]+' <button class="btn btn-sm btn-danger btn-sm-round btn-act forseRemoveCourse" data-id="'+arrIDSSD[p]+'"><i class="fa fa-trash"></i></button></li>');
                        }
                    }
                }

                $('#tableCourse').dataTable({
                    'ordering' : false,
                    'pageLength' : 10
                });
                
            } else {
                $('#viewLoadTable').html('<div style="text-align: center;color: #CCCCCC;"><h3>-- Data not yet --</h3></div>');
            }

        })

    }

    $(document).on('click','.btnShowStd',function () {

        var IDSSD = $(this).attr('data-id');

        var listStudents = JSON.parse($('#showStd_'+IDSSD).val());

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Student</h4>');
        $('#GlobalModal .modal-body').html('<table class="table table-striped">' +
            '    <tr>' +
            '        <th style="width: 1%;">No</th>' +
            '        <th style="width: 15%;">NPM</th>' +
            '        <th>Student</th>' +
            '    </tr>' +
            '    <tbody id="showListStd"></tbody>' +
            '</table>');

        var no =1;
        $.each(listStudents,function (i,v) {
            $('#showListStd').append('<tr>' +
                '<td>'+no+'</td>' +
                '<td>'+v.NPM+'</td>' +
                '<td>'+v.Name+'</td>' +
                '</tr>');
           no++;
        });

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

    $(document).on('click','.addCourse',function () {

        $('.btn-act').prop('disabled',true);
        var IDSSD = $(this).attr('data-id');

        var formListCourse = $('#formListCourse').val();
        var arrIDSSD = (formListCourse!='') ? JSON.parse(formListCourse) : [];

        // Load Course
        // var Course = JSON.parse($('#showCourse_'+IDSSD).val());

        arrIDSSD.push(IDSSD);

        $('#formListCourse').val(JSON.stringify(arrIDSSD));


        setTimeout(function () {
            $('.btn-act').prop('disabled',false);
            loadCourse();
        },500);

    });

    $(document).on('click','.removeCourse',function () {

        $('.btn-act').prop('disabled',true);
        var IDSSD = $(this).attr('data-id');

        var formListCourse = $('#formListCourse').val();
        var arrIDSSD = (formListCourse!='') ? JSON.parse(formListCourse) : [];

        var newArr = [];
        for(var i=0;i<arrIDSSD.length;i++){
            if(parseInt(arrIDSSD[i])!= parseInt(IDSSD)){
                newArr.push(arrIDSSD[i]);
            }
        }

        $('#formListCourse').val(JSON.stringify(newArr));

        setTimeout(function () {
            loadCourse();
        },500);

    });

    $(document).on('click','.forseRemoveCourse',function () {
        $('.forseRemoveCourse').prop('disabled',true);

        var formScheduleIDSA = $('#formScheduleIDSA').val();
        if(formScheduleIDSA!='' && formScheduleIDSA!=null){
            var IDSSD = $(this).attr('data-id');

            var data = {
                action : 'forseRemoveSSC',
                dataForm : {
                    ScheduleIDSA : formScheduleIDSA,
                    IDSSD : IDSSD
                }

            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api2/__crudSemesterAntara';
            $.post(url,{token:token},function (result) {

                var formListCourse = $('#formListCourse').val();
                var arrIDSSD = (formListCourse!='') ? JSON.parse(formListCourse) : [];

                var newArr = [];
                for(var i=0;i<arrIDSSD.length;i++){
                    if(parseInt(arrIDSSD[i])!= parseInt(IDSSD)){
                        newArr.push(arrIDSSD[i]);
                    }
                }

                $('#formListCourse').val(JSON.stringify(newArr));

                setTimeout(function () {
                    loadCourse();
                },500);
            })
        }
    })

    
</script>