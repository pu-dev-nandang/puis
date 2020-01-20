
<style>
    #tableSchedule thead tr th {
        background: #20525a;
        color: #FFFFFF;
        text-align: center;
    }
    #tableSchedule tbody tr td {
        text-align: center;
    }
    #viewCourse {
        margin-top: 0px;
        border-left: 10px solid orange;
        padding-left: 7px;
    }

    #formSesiAkhir {
        color: #333;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <a href="<?php echo base_url('academic/timetables'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left margin-right"></i> Back to List</a>
        <hr/>
        <div id="showAlertEditingCourse"></div>
    </div>
</div>

<div class="row">
    <div class="col-md-5 panel-edit-course hide">
        <div class="well" style="padding: 15px;">
            <input id="formSDID" class="hide" readonly/>
            <input id="dataMaxCredit" class="hide" readonly/>
            <input id="dataUseCredit" class="hide" readonly/>
            <input id="viewClassGroup" class="hide" readonly/>
            <div class="form-group">
                <label>Room</label>
                <select class="form-control fm_cekbentrok" id="formClassroom" style="max-width: 350px;">
                    <option value="" disabled selected>-- Select Room --</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Day</label>
                        <select class="form-control fm_cekbentrok" id="formDay">
                            <option value="" disabled selected>-- Select Day --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Credit</label>
                        <input class="form-control" id="formCredit" type="number"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Time Per Credit</label>
                        <select class="form-control fm_cekbentrok" id="formTimePerCredit">
                            <option value="" disabled selected>-- Select Time/Credit --</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Start</label>
                        <div id="div_formSesiAwal" class="input-group">
                            <input data-format="hh:mm" type="text" id="formSesiAwal" class="form-control" value="00:00"/>
                            <span class="add-on input-group-addon">
                                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>End</label>
                        <input class="form-control" id="formSesiAkhir" readonly />
                    </div>
                </div>
            </div>



            <div class="row">

                <div class="ol-md-12">
                    <div id="alertBentrok"></div>
                </div>

                <div class="col-md-12" style="text-align: right;">
                    <hr/>
                    <button class="btn btn-success" id="btnAddSchedule" disabled>Save</button>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-7">
        <h3 id="viewCourse">-</h3>
        <table class="table table-bordered table-striped" id="tableSchedule">
            <thead>
            <tr>
                <th style="width: 1%;">Credit</th>
                <th style="width: 7%;"><small>Time Per Credit</small></th>
                <th style="width: 17%;">Day, Time</th>
                <th style="width: 7%;">Room</th>
                <th class="panel-edit-course hide" style="width: 5%;">Action</th>
            </tr>
            </thead>
            <tbody id="rwDataSch"></tbody>
        </table>

    </div>
</div>

<script>
    $(document).ready(function () {
        window.dataSesiArr = [];
        window.dataSesiDb = 1;

        window.EditTimeTable = null;
        loading_modal_show();
        checkAccessToEditTimeTable();

        loadDataSchedule();

        var firsLoad = setInterval(function () {
            var dataMaxCredit = $('#dataMaxCredit').val();
            if(dataMaxCredit!='' && dataMaxCredit!=null){
                loadDataScheduleDetails();
                clearInterval(firsLoad);
            }
        },1000);



        loadSelectOptionClassroom('#formClassroom','');
        fillDays('#formDay','Eng','');
        loadSelectOptionTimePerCredit('#formTimePerCredit','');
        $('#div_formSesiAwal').datetimepicker({
            pickDate: false,
            pickSeconds : false
        })
            .on('changeDate', function(e) {

                setSesiAkhir();
                checkSchedule();
            });
    });

    function checkAccessToEditTimeTable(){

        var SemesterID = parseInt("<?php echo $SemesterID ?>");

        var data = {
            action : 'getDateKRSOnline',
            SemesterID : SemesterID
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudKurikulum';

        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                var d = jsonResult[0];
                if(d.EditTimeTable=='0'){
                    // $('.panel-edit-course').removeClass('hide');
                    $('.panel-edit-course').remove();

                    $('#showAlertEditingCourse').html('<div class="alert alert-danger" role="alert" style="text-align: center;">' +
                        '            <b><i class="fa fa-warning fa-3x"></i>' +
                        '                <br/>Unable to make changes or delete the current schedule</b>' +
                        '            <div>' +
                        '                for more information please contact the <u>IT Development Team</u>' +
                        '            </div>' +
                        '        </div>');

                } else {
                    $('.panel-edit-course').removeClass('hide');
                }

                window.EditTimeTable = d.EditTimeTable;
                loadDataScheduleDetails();

                setTimeout(function () {
                    loading_modal_hide();
                },1000);
            }





        });

    }

    function loadDataScheduleDetails(){
        var SemesterID = parseInt("<?php echo $SemesterID ?>");
        var ScheduleID = parseInt("<?php echo $ScheduleID ?>");

        var data = {
            action : 'loadEditSchedule',
            SemesterID : SemesterID,
            ScheduleID : ScheduleID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudSchedule';

        $.post(url,{token:token},function (jsonResult) {

            var maxCredit = $('#dataMaxCredit').val();
            var sisaCredit = parseInt(maxCredit);

            var subSesi = jsonResult.dataSchedule;
            var dataUseCredit = 0;
            if(subSesi.length>0){
                $('#rwDataSch').empty();
                $('#viewClassGroup').val(subSesi[0].ClassGroup);
                for(var i=0;i<subSesi.length;i++){
                    var d = subSesi[i];

                    sisaCredit = sisaCredit - parseInt(d.Credit);
                    dataUseCredit = dataUseCredit + parseInt(d.Credit);

                    var st = d.StartSessions.substr(0,5);
                    var en = d.EndSessions.substr(0,5);
                    var time = st+' - '+en;

                    var btnActCourse = (EditTimeTable=='1')
                        ? '<td><button class="btn btn-sm btn-default btn-default-primary btnEditAction" data-id="'+d.SDID+'" data-room="'+d.ClassroomID+'" ' +
                            'data-day="'+d.DayID+'" data-credit="'+d.Credit+'|'+d.TimePerCredit+'" data-time="'+st+'|'+en+'"><i class="fa fa-edit"></i></button> | ' +
                            '<button class="btn btn-sm btn-default btn-default-danger btnDeleteAction" data-id="'+d.SDID+'" data-totalsubsesi="'+subSesi.length+'"><i class="fa fa-trash"></i></button></td>'
                        : '';

                    $('#rwDataSch').append('<tr>' +
                        '<td>'+d.Credit+'</td>' +
                        '<td>'+d.TimePerCredit+'</td>' +
                        '<td>'+d.DayEng+', '+time+'</td>' +
                        '<td>'+d.Room+'</td>'+btnActCourse+
                        '</tr>');

                }
            }

            $('#dataUseCredit').val(dataUseCredit);
            $('#formCredit').val(maxCredit);
            // $('#formCredit').val(sisaCredit);
            var sv = (sisaCredit==0) ? true : false;
            $('#btnAddSchedule').prop('disabled',sv)
            $('#formSDID,#formClassroom,#formDay,#formTimePerCredit').val('');
            $('#formSesiAwal').val('00:00');
            $('#formSesiAkhir').val('');

            // loadSubSesi(jsonResult.dataSchedule);


        });
    }

    function loadDataSchedule() {
        var SemesterID = parseInt("<?php echo $SemesterID ?>");
        var ScheduleID = parseInt("<?php echo $ScheduleID ?>");

        var data = {
            action : 'loadEditCourseSchedule',
            SemesterID : SemesterID,
            ScheduleID : ScheduleID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudSchedule';

        $.post(url,{token:token},function (jsonResult) {

            var course = jsonResult.Schedule[0];
            $('#viewCourse').html(course.CourseEng+' | <small>Credit : '+course.TotalCredit+'</small>');
            // $('#formCredit').val(course.TotalCredit);
            $('#dataMaxCredit').val(course.TotalCredit);
        });
    }

    $(document).on('click','.btnEditAction',function () {


        var SDID = $(this).attr('data-id');
        var ClassroomID = $(this).attr('data-room');
        var DayID = $(this).attr('data-day');
        var dataCredit = $(this).attr('data-credit');
        var dataTime = $(this).attr('data-time');

        var Credit = dataCredit.split('|')[0];
        var TimePerCredit = dataCredit.split('|')[1];

        var StartSessions = dataTime.split('|')[0];
        var EndSessions = dataTime.split('|')[1];

        $('#formSDID').val(SDID);

        $('#formClassroom').val(ClassroomID);
        $('#formDay').val(DayID);
        $('#formCredit').val(Credit);
        $('#formTimePerCredit').val(TimePerCredit);

        $('#formSesiAwal').val(StartSessions);
        $('#formSesiAwal').attr('value',StartSessions);
        $('#formSesiAkhir').val(EndSessions);

        $('#btnAddSchedule').prop('disabled',false);

    });

    $('#btnAddSchedule').click(function () {

        var dataUseCredit = $('#dataUseCredit').val();
        var maxCredit = $('#dataMaxCredit').val();



        var SemesterID = parseInt("<?php echo $SemesterID ?>");
        var ScheduleID = parseInt("<?php echo $ScheduleID ?>");

        var formSDID = $('#formSDID').val();
        var action = (formSDID!='' && formSDID!=null) ? 'updateCourse_Edit' : 'updateCourse_Add' ;

        var formClassroom = $('#formClassroom').val();
        var formDay = $('#formDay').val();
        var formCredit = $('#formCredit').val();
        var formTimePerCredit = $('#formTimePerCredit').val();

        var formSesiAwal = $('#formSesiAwal').val();
        var formSesiAkhir = $('#formSesiAkhir').val();

        if(formClassroom!='' && formClassroom!=null
        && formDay!='' && formDay!=null && formSesiAkhir!='' && formSesiAkhir!=null
            && formTimePerCredit!='' && formTimePerCredit!=null  && formCredit!=''
            && formCredit!=null ){
            var newCredit = dataUseCredit + formCredit;
            if(formCredit <= maxCredit){
                loading_buttonSm('#btnAddSchedule');

                var data = {
                    action : action,
                    ID : formSDID,
                    SemesterID : SemesterID,
                    ScheduleID : ScheduleID,
                    formInsert : {
                        ClassroomID : formClassroom,
                        Credit : formCredit,
                        DayID : formDay,
                        TimePerCredit : formTimePerCredit,
                        StartSessions : formSesiAwal,
                        EndSessions : formSesiAkhir
                    },
                    UpdateLog : {
                        UpdateBy : sessionNIP,
                        UpdateAt : dateTimeNow()
                    }
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api/__crudSchedule';

                $.post(url,{token:token},function (jsonResult) {

                    var viewClassGroup = $('#viewClassGroup').val();
                    var arrToken = {
                        Subject : 'Updating Schedule In Timetable | Group : '+viewClassGroup,
                        URL : 'academic/timetables/list',
                        From : sessionName,
                        Icon : sessionUrlPhoto
                    };
                    var dataToken = jwt_encode(arrToken,'UAP)(*');
                    addNotification(dataToken,null);

                    toastr.success('Data saved','Success');
                    setTimeout(function () {
                        $('#btnAddSchedule').html('Save');
                        loadDataScheduleDetails();
                    },500);
                });
            }
            else {
                toastr.warning('Credit can not mare then maximum credit','Warning');
            }
        }
        else {
            toastr.warning('All form schedule are required, please check your data','Warning!');
        }

    });



    $('.fm_cekbentrok').change(function () {
        setSesiAkhir();
        checkSchedule();
    });

    $('#formCredit').keyup(function () {
        setSesiAkhir();
        checkSchedule();
    });

    $('#formCredit').blur(function () {
        setSesiAkhir();
        checkSchedule();
    });

    function checkSchedule() {

        var ID = '';

        var SemesterID = parseInt("<?php echo $SemesterID ?>");
        var ScheduleID = parseInt("<?php echo $ScheduleID ?>");

        var formSDID = $('#formSDID').val();


        var element = '#alertBentrok'+ID;
        var ClassroomID = $('#formClassroom'+ID).val();
        var DayID = $('#formDay'+ID).val();
        var StartSessions = $('#formSesiAwal'+ID).val();
        var EndSessions = $('#formSesiAkhir'+ID).val();

        if(ClassroomID!='' && DayID!='' && StartSessions!='' && EndSessions!='') {
            var data = {
                action : 'check',
                formData : {
                    SemesterID : SemesterID,
                    IsSemesterAntara : 0,
                    ClassroomID : ClassroomID,
                    DayID : DayID,
                    StartSessions : StartSessions,
                    EndSessions : EndSessions
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__checkSchedule';
            $.post(url,{token:token},function (json_result) {

                $('#btnAddSchedule').prop('disabled',true);

                $(element).html('');


                if(json_result.length>0){

                    $(element).append('<div class="row">' +
                        '                        <div class="col-xs-12" style="margin-top: 20px;">' +
                        '                            <div class="alert alert-danger" role="alert">' +
                        '                                <b><i class="fa fa-exclamation-triangle" aria-hidden="true" style="margin-right: 5px;"></i> Jadwal bentrok</b>, Silahklan rubah : Ruang / Hari / Jam' +
                        '                                <hr style="margin-bottom: 3px;margin-top: 10px;"/>' +
                        '                                <ol id="ulbentrok'+ID+'">' +
                        '                                </ol>' +
                        '                            </div>' +
                        '                        </div>' +
                        '' +
                        '                    </div>');

                    var ol = $('#ulbentrok'+ID);

                    //Cek jumlah jadwal yang bentrok
                    if(json_result.length==1 && formSDID!=''){
                        if(json_result[0].sdID==formSDID){
                            // console.log('bukan bentrok');
                            $(element).html('');
                            $('#btnAddSchedule').prop('disabled',false);
                        } else {
                            var data = json_result[0];
                            ol.append('<li>' +
                                'Group <strong style="color:#333;">'+data.ClassGroup+'</strong> : <span style="color: blue;">'+data.Room+' | '+daysEng[(parseInt(data.DayID)-1)]+' '+data.StartSessions+' - '+data.EndSessions+'</span>' +
                                '<ul style="color: #607d8b;" id="dtMK'+i+'"></ul>' +
                                '</li>');

                            var ul = $('#dtMK'+i);
                            for(var m=0;m<data.DetailsCourse.length;m++){
                                var mk_ = data.DetailsCourse[m];
                                ul.append('<li>'+mk_.MKCode+' | '+mk_.NameEng+'</li>');
                            }
                        }
                    } else {

                        for(var i=0;i<json_result.length;i++){
                            var data = json_result[i];

                            if(formSDID!='' && data.sdID==formSDID){

                            } else {
                                ol.append('<li>' +
                                    'Group <strong style="color:#333;">'+data.ClassGroup+'</strong> : <span style="color: blue;">'+data.Room+' | '+daysEng[(parseInt(data.DayID)-1)]+' '+data.StartSessions+' - '+data.EndSessions+'</span>' +
                                    '<ul style="color: #607d8b;" id="dtMK'+i+'"></ul>' +
                                    '</li>');

                                var ul = $('#dtMK'+i);
                                for(var m=0;m<data.DetailsCourse.length;m++){
                                    var mk_ = data.DetailsCourse[m];
                                    ul.append('<li>'+mk_.MKCode+' | '+mk_.NameEng+'</li>');
                                }
                            }


                        }

                    }

                } else {
                    $('#btnAddSchedule').prop('disabled',false);
                }
            });
        }

    }

    function setSesiAkhir() {
        var ID = '';
        var TimePerCredit = $('#formTimePerCredit'+ID).val();
        var SesiAwal = $('#formSesiAwal'+ID).val();
        var Credit = $('#formCredit'+ID).val();

        // console.log(ID);
        // console.log(SesiAwal);
        if(TimePerCredit!='' && SesiAwal!='' && Credit!='' && typeof SesiAwal != 'undefined'){
            var totalTime = parseInt(TimePerCredit) * parseInt(Credit);
            var expSesi = SesiAwal.split(':');
            var sesiAkhir = moment()
                .hours(expSesi[0])
                .minutes(expSesi[1])
                .add(parseInt(totalTime), 'minute').format('HH:mm');

            $('#formSesiAkhir'+ID).val(sesiAkhir);
        }
    }

    // === DELETE SubSesi ===
    $(document).on('click','.btnDeleteAction',function () {
        var totalsubsesi = $(this).attr('data-totalsubsesi');
        var SDID = $(this).attr('data-id');


        if(parseInt(totalsubsesi)>1){
            $('#NotificationModal .modal-body').html('<div style="text-align: center;"><span>Are you sure to delete this schedule?</span><hr/> ' +
                '<button type="button" class="btn btn-danger" id="btnDeleteActionCourse" data-id="'+SDID+'" style="margin-right: 5px;">Yes</button>' +
                '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
                '</div>');

        } else {
            $('#NotificationModal .modal-body').html('<div style="text-align: center;">' +
                '<img src="'+base_url_js+'images/icon/stop.png" style="text-align: center;max-width: 70px;" /> ' +
                '<br/><span>Schedule can not to delete</span><hr/> ' +
                '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
                '</div>');
        }


        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });
    });

    $(document).on('click','#btnDeleteActionCourse',function () {

        loading_buttonSm('#btnDeleteActionCourse');
        $('button[data-dismiss=modal]').prop('disabled',true);

        var SemesterID = parseInt("<?php echo $SemesterID ?>");
        var ScheduleID = parseInt("<?php echo $ScheduleID ?>");
        var SDID = $(this).attr('data-id');


        var data = {
            action : 'deleteScheduleCourse',
            SemesterID : SemesterID,
            ScheduleID : ScheduleID,
            SDID : SDID,
            UpdateLog : {
                UpdateBy : sessionNIP,
                UpdateAt : dateTimeNow()
            }

        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudSchedule';

        $.post(url,{token:token},function (result) {
            var viewClassGroup = $('#viewClassGroup').val();
            var arrToken = {
                Subject : 'Deleting Schedule In Timetable | Group : '+viewClassGroup,
                URL : 'academic/timetables/list',
                From : sessionName,
                Icon : sessionUrlPhoto
            };
            var dataToken = jwt_encode(arrToken,'UAP)(*');
            addNotification(dataToken,null);

            loadDataScheduleDetails();
            setTimeout(function () {
                $('#NotificationModal').modal('hide');
            },500);
        });

    });
</script>