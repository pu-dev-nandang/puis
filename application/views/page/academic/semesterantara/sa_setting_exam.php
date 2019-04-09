

<style>
    input[readonly] {
        color: #333333;
    }
    .form-control[readonly] {
        background-color: #fff;
    }
    .form-red {
        color: red;
    }
</style>

<div class="row">

    <div class="col-md-6 col-md-offset-3">

        <div class="well" style="min-height: 100px;">

            <h4 style="border-left: 7px solid orangered;padding-left: 7px;font-weight: bold;">Setting Exam</h4>

            <input id="formExamIDSA" class="hide" value="<?=$ExamIDSA;?>">
            <textarea id="dataExamSA" class="hide"><?=$dataExamSA;?></textarea>

            <table class="table" style="margin-top: 30px;">

                <tr>
                    <td style="width: 20%;">Group Class <span class="form-red">*</span></td>
                    <td style="width: 1%;">:</td>
                    <td id="viewCourse"></td>
                </tr>
                <tr>
                    <td>Type <span class="form-red">*</span></td>
                    <td>:</td>
                    <td>
                        <div class="row">
                            <div class="col-xs-3">
                                <select class="form-control" id="formType">
                                    <option value="uts">UTS</option>
                                    <option value="uas">UAS</option>
                                </select>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>Date <span class="form-red">*</span></td>
                    <td>:</td>
                    <td>
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="text" id="formDate" readonly class="form-control form-exam form-datetime">
                                <input id="formInputDate" class="hide" readonly>
                            </div>
                            <div class="col-xs-6">
                                <div id="viewTgl" style="padding-top: 7px;color: #607d8b;"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Time, Room <span class="form-red">*</span></td>
                    <td>:</td>
                    <td>
                        <div class="row">
                            <div class="col-xs-3">
                                <div id="inputStart" class="input-group">
                                    <input data-format="hh:mm" type="text" id="formStart" class="form-control form-exam" value="00:00"/>
                                    <span class="add-on input-group-addon">
                                        <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div id="inputEnd" class="input-group">
                                    <input data-format="hh:mm" type="text" id="formEnd" class="form-control form-exam" value="00:00"/>
                                    <span class="add-on input-group-addon">
                                        <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-6" style="border-left: 1px solid #CCCCCC;">
                                <select class="select2-select-00 form-exam" style="width: 100% !important;" size="5" id="formClassroom">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Invigilator</td>
                    <td>:</td>
                    <td>
                        <div class="row">
                            <div class="col-xs-6">

                                <select class="select2-select-00 form-exam" style="width: 100% !important;" size="5" id="formPengawas1">
                                    <option value=""></option>
                                </select>
                                <span class="form-red">* <span style="font-size: 10px;">Invigilator 1 required</span></span>
                            </div>
                            <div class="col-xs-6">
                                <select class="select2-select-00 form-exam" style="width: 100% !important;" size="5" id="formPengawas2">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">
                        <button class="btn btn-primary" id="btnSubmitExam" disabled>Submit</button>
                    </td>
                </tr>

            </table>


        </div>

    </div>

</div>

<div class="hide" id="listStudent">

</div>

<script>

    $(document).ready(function () {

        window.dataExamSA = $('#dataExamSA').val();
        window.dataEx = JSON.parse(dataExamSA);

        if(dataEx.length>0){
            console.log(dataEx);
            var dx = dataEx[0];
            $('#formType').val(dx.Type);
            $('#formStart').val(dx.Start.substr(0,5));
            $('#formEnd').val(dx.End.substr(0,5));

            $('#formInputDate').val(dx.ExamDate);
            $('#viewTgl').html('<i class="fa fa-arrow-right" style="margin-right: 5px;"></i> <b>'+moment(dx.ExamDate).format('dddd, DD MMM YYYY')+'</b>');
        }

        loadTimeTablesSa();

        var cl = (dataEx.length>0)
            ? dataEx[0].ClassroomID+'.'+dataEx[0].Seat+'.'+dataEx[0].SeatForExam
            : '';
        loadSelect2OptionClassroom('#formClassroom',cl);

        var p1 = (dataEx.length>0) ? dataEx[0].Invigilator1 : '';
        var p2 = (dataEx.length>0) ? dataEx[0].Invigilator2 : '';
        loadSelectOptionEmployeesSingle('#formPengawas1',p1);
        loadSelectOptionEmployeesSingle('#formPengawas2',p2);
        $('#formPengawas1,#formPengawas2,#formClassroom').select2({allowClear: true});

        $('#inputStart,#inputEnd').datetimepicker({
            pickDate: false,
            pickSeconds : false
        });

        var firsload = setInterval(function () {

            var formType = $('#formType').val();
            if(formType!='' && formType!=null){
                loadDate();
                clearInterval(firsload);
            }

        },1000);

    });

    $('#formType').change(function () {
        loadDate();
    });

    function loadDate() {

        var formType = $('#formType').val();
        if(formType!='' && formType!=null){

            $( "#formDate" ).val('');
            $( "#formDate" ).datepicker( "destroy" );

            var data = {
                action : 'academicYear',
                SASemesterID : '<?=$SASemesterID; ?>',
                Type : formType.toLowerCase()
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api2/__crudSemesterAntara';

            $.post(url,{token:token},function (jsonResult) {

                var dateStart = jsonResult.Start;
                var dateEnd = jsonResult.End;

                if(dateStart!=null && dateStart!='' && dateEnd!=null && dateEnd!=''){

                    $('#btnSubmitExam').prop('disabled',false);

                    var splitStart = dateStart.split('-');
                    var C_dateStart_Y = splitStart[0].trim();
                    var C_dateStart_M = parseInt(splitStart[1].trim())-1;
                    var C_dateStart_D = splitStart[2].trim();

                    var splitEnd = dateEnd.split('-');
                    var C_dateEnd_Y = splitEnd[0].trim();
                    var C_dateEnd_M = parseInt(splitEnd[1].trim())-1;
                    var C_dateEnd_D = splitEnd[2].trim();

                    $('#formDate').datepicker({
                        showOtherMonths:true,
                        autoSize: true,
                        dateFormat: 'dd MM yy',
                        minDate : new Date(C_dateStart_Y,C_dateStart_M,C_dateStart_D),
                        maxDate : new Date(C_dateEnd_Y,C_dateEnd_M,C_dateEnd_D),
                        onSelect : function () {
                            var data_date = $(this).val().split(' ');
                            var momentDate = moment(data_date[2]+'-'+(parseInt(convertDateMMtomm(data_date[1])) + 1)+'-'+data_date[0]);
                            // var CustomMoment = momentDate.day();
                            // var day = (CustomMoment==0) ? 7 : CustomMoment;
                            // $('#formDayID').val(day);
                            $('#formInputDate').val(momentDate.format('YYYY-MM-DD'));
                        }
                    });
                } else {
                    $('#btnSubmitExam').prop('disabled',true);
                }


            });

        }


    }

    function loadTimeTablesSa() {

        var data = {
            action : 'loadTimetableSA',
            SASemesterID : '<?=$SASemesterID; ?>'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudSemesterAntara';

        $.post(url,{token:token},function (jsonResult) {


            $('#listStudent').empty();

            if(jsonResult.length>0){
                $('#viewCourse').html('<select class="select2-select-00 full-width-fix" size="5" multiple id="formCourse"></select>');

                $.each(jsonResult,function (i,v) {

                    var sc = '';

                    if(dataEx.length>0){
                        if(dataEx[0].Course.length>0){
                            $.each(dataEx[0].Course,function (i2,v2) {
                                if(parseInt(v2.ScheduleIDSA)==parseInt(v.ID)){
                                    sc = 'selected';
                                    return false;
                                }
                            })
                        }
                    }

                    $('#formCourse').append('<option value="'+v.ID+'" '+sc+'>'+v.ClassGroup+' - '+v.CourseEng+'</option>');
                    $('#listStudent').append('<textarea id="viewStudent_'+v.ID+'">'+JSON.stringify(v.Students)+'</textarea>');
                });

                $('#formCourse').select2({allowClear: true});

            } else {
                $('#viewCourse').html('<b style="color: red;">Course Not Yet</b>');
            }

        });

    }

    $('#btnSubmitExam').click(function () {

        var formCourse = $('#formCourse').val();
        var formType = $('#formType').val();
        var formInputDate = $('#formInputDate').val();

        var formStart = $('#formStart').val();
        var formEnd = $('#formEnd').val();

        var formClassroom = $('#formClassroom').val();

        var formPengawas1 = $('#formPengawas1').val();
        var formPengawas2 = $('#formPengawas2').val();

        if(formCourse!='' && formCourse!=null && formInputDate!='' && formStart!='' && formStart!='00:00' && formEnd!=''
            && formEnd!='00:00' && formClassroom!='' && formClassroom!=null && formPengawas1!='' && formPengawas1!=null){

            loading_button('#btnSubmitExam');
            var std = [];
            if(formCourse.length>0){
                for(var i=0;i<formCourse.length;i++){

                    var viewStudent = $('#viewStudent_'+formCourse[i]).val();
                    var dataStd = (viewStudent!='') ? JSON.parse(viewStudent) : [];

                    if(dataStd.length>0){
                        for(var s=0;s<dataStd.length;s++){
                            var d = dataStd[s];
                            std.push({
                                NPM : d.NPM,
                                ScheduleIDSA : d.ScheduleIDSA
                            });
                        }
                    }

                }
            }


            var ClassroomID = formClassroom.split('.')[0].trim();

            var formExamIDSA = $('#formExamIDSA').val();
            var action = (formExamIDSA!='') ? 'editSAExam' : 'addSAExam';
            var ExamIDSA = (formExamIDSA!='') ? formExamIDSA : '0';

            var data = {
                action : action,
                ExamIDSA : ExamIDSA,
                dataForm : {
                    SASemesterID : '<?=$SASemesterID; ?>',
                    Type : formType,
                    ExamDate : formInputDate,
                    Start : formStart,
                    End : formEnd,
                    ClassroomID : ClassroomID,
                    Invigilator1 : formPengawas1,
                    Invigilator2 : formPengawas2,
                    EntredBy : sessionNIP,
                    EntredAt : dateTimeNow()
                },
                dataCourse : formCourse,
                dataStudent : std
            };


            var url = base_url_js+'api2/__crudSemesterAntara';
            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {

                toastr.success('Data saved','Success');
                setTimeout(function () {
                    window.location.href='';
                },500);

            });

        } else {
            toastr.error('Please fill form requred','Error');
        }

    });


</script>