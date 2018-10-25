
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
        <a href="<?php echo base_url('academic/timetables/list'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left margin-right"></i> Back to List</a>
        <hr/>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="well" style="padding: 15px;">
            <input id="formSDID" class="hide" readonly/>
            <div class="form-group">
                <label>Room</label>
                <select class="form-control" id="formClassroom" style="max-width: 350px;">
                    <option value="" disabled selected>-- Select Room --</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Day</label>
                        <select class="form-control" id="formDay">
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
                        <select class="form-control" id="formTimePerCredit">
                            <option value="" disabled selected>-- Select Time/Credit --</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Start</label>
                        <div id="div_formSesiAwal" data-no="1" class="input-group">
                            <input data-format="hh:mm" type="text" id="formSesiAwal" class="form-control form-attd formtime" value="00:00"/>
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
                <th style="width: 5%;">Action</th>
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

        loadDataSchedule();

        var firsLoad = setInterval(function () {
            var formCredit = $('#formCredit').val();
            if(formCredit!='' && formCredit!=null){
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

                var no = $(this).attr('data-no');
                setSesiAkhir(no);
                checkSchedule(no);
            });
    });

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

            var maxCredit = $('#formCredit').val();
            var sisaCredit = parseInt(maxCredit);

            var subSesi = jsonResult.dataSchedule;
            if(subSesi.length>0){
                $('#rwDataSch').empty();
                for(var i=0;i<subSesi.length;i++){
                    var d = subSesi[i];

                    sisaCredit = sisaCredit- parseInt(d.Credit);

                    var st = d.StartSessions.substr(0,5);
                    var en = d.EndSessions.substr(0,5);
                    var time = st+' - '+en;
                    $('#rwDataSch').append('<tr>' +
                        '<td>'+d.Credit+'</td>' +
                        '<td>'+d.TimePerCredit+'</td>' +
                        '<td>'+d.DayEng+', '+time+'</td>' +
                        '<td>'+d.Room+'</td>' +
                        '<td>' +
                        '<button class="btn btn-sm btn-default btn-default-primary btnEditAction" data-id="'+d.SDID+'" data-room="'+d.ClassroomID+'" ' +
                        'data-day="'+d.DayID+'" data-credit="'+d.Credit+'|'+d.TimePerCredit+'" data-time="'+st+'|'+en+'"><i class="fa fa-edit"></i></button> | ' +
                        '<button class="btn btn-sm btn-default btn-default-danger"><i class="fa fa-trash"></i></button> ' +
                        '</td>' +
                        '</tr>');

                }
            }

            $('#formCredit').val(sisaCredit);
            var sv = (sisaCredit==0) ? true : false;
            $('#btnAddSchedule').prop('disabled',sv)
            $('#formClassroom,#formDay,#formTimePerCredit').val('');
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
            $('#viewCourse').html(course.CourseEng+' | <small>Max credit : '+course.TotalCredit+'</small>');
            $('#formCredit').val(course.TotalCredit);
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
        $('#formSesiAkhir').val(EndSessions);

    });

    $('#btnAddSchedule').click(function () {
        var SemesterID = parseInt("<?php echo $SemesterID ?>");
        var ScheduleID = parseInt("<?php echo $ScheduleID ?>");

        var formSDID = $('#formSDID').val();
        var action = (formSDID!='' && formSDID!=null) ? 'updateEditCourse' : 'updateAddCourse' ;

        var formClassroom = $('#formClassroom').val();
        var formDay = $('#formDay').val();
        var formCredit = $('#formCredit').val();
        var formTimePerCredit = $('#formTimePerCredit').val();

        var formSesiAwal = $('#formSesiAwal').val();
        var formSesiAkhir = $('#formSesiAkhir').val();

        var data = {
            action : action,
            ID : formSDID,
            ScheduleID : ScheduleID,
            formInsert : {
                ClassroomID : formClassroom,
                Credit : formCredit,
                DayID : formDay,
                TimePerCredit : formTimePerCredit,
                StartSessions : formSesiAwal,
                EndSessions : formSesiAkhir
            }
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudSchedule';

        $.post(url,{token:token},function (jsonResult) {
            loadDataScheduleDetails();
        });

    });

</script>