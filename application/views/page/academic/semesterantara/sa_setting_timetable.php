
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
        </div>

        <div class="thumbnail" style="min-height: 100px;padding: 15px;">
            <table class="table">
                <tbody>
                <tr>
                    <td style="width: 25%;">Group <span style="font-size: 10px;color: red;">*</span></td>
                    <td style="width: 1%;">:</td>
                    <td>
                        <input id="formIDSASemester" value="<?= $IDSASemester; ?>" class="hide" />
                        <input id="formType" value="<?= $IDSASemester; ?>" class="hide" />
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

                <button class="btn btn-success" id="submitSetTimetables">Submit</button>
            </div>
        </div>
    </div>
</div>


<script>
    
    $(document).ready(function () {

        loadSelectOptionLecturersSingle('#formCoordinator','');
        loadSelectOptionLecturersSingle('#formTeamTeaching','');
        fillDays('#formDay','Eng','');
        loadSelect2OptionClassroom('#formClassroom','');

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

        var formIDSASemester = $('#formIDSASemester').val();
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

            loading_button('#submitSetTimetables');
            var ClassroomID = formClassroom.split('.')[0];
            var data = {
                action : 'actionSASchedule',
                type : 'insert',
                dataSch : {
                    IDSASemester : formIDSASemester,
                    ClassGroup : formClassGroup.toUpperCase(),
                    Coordinator : formCoordinator,
                    DayID : formDay,
                    Start : formStart,
                    End : formEnd,
                    ClassroomID : ClassroomID,
                    EnteredBy : sessionNIP,
                    EnteredAt : dateTimeNow()
                },

                TeamTeaching : formTeamTeaching,
                ArrIDSSD : ArrIDSSD
            };

            var url = base_url_js+'api2/__crudSemesterAntara';
            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {

                console.log(jsonResult);

                if(jsonResult.Status<0){
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
                    '            </table><div id="listStudent" class="hide"></div>');

                var no =1;
                $.each(jsonResult,function (i,v) {

                    var Students = v.Students;

                    var formListCourse = $('#formListCourse').val();
                    var arrIDSSD = (formListCourse!='') ? JSON.parse(formListCourse) : [];

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

                    $('#listStudent').append('<textarea id="showStd_'+v.IDSSD+'">'+JSON.stringify(Students)+'</textarea>' +
                        '<textarea id="showCourse_'+v.IDSSD+'">'+JSON.stringify(v)+'</textarea>');

                    no++;

                });

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
        var Course = JSON.parse($('#showCourse_'+IDSSD).val());

        arrIDSSD.push(IDSSD);

        $('#formListCourse').val(JSON.stringify(arrIDSSD));


        $('#showSelectedCourse').append('<li style="margin-bottom:5px;" id="listSelc'+Course.IDSSD+'">'+Course.CourseEng+' ' +
            '| <a href="javascript:void(0);" class="btnShowStd" data-id="'+Course.IDSSD+'">'+Course.Students.length+' student</a> ' +
            '| <button class="btn btn-sm btn-danger btn-sm-round btn-act removeCourse" data-id="'+Course.IDSSD+'"><i class="fa fa-trash"></i></button></li>');


        setTimeout(function () {
            $('.btn-act').prop('disabled',false);
            loadCourse();
        },500);

    });

    $(document).on('click','.removeCourse',function () {

        $('.btn-act').prop('disabled',true);
        var IDSSD = $(this).attr('data-id');

        $('#listSelc'+IDSSD).remove();

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
            $('.btn-act').prop('disabled',false);
        },500);

    });

    
</script>