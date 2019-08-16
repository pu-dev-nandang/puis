
<style>
    #formDate {
        background: #ffffff;
        color: #333333;
        cursor: pointer;
    }
</style>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-6 col-md-offset-3">
        <div class="well">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Type</label>
                        <select id="formType" class="form-control">
                            <option value="1">Seminar Proposal</option>
                            <option value="2">Seminar Hasil</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Date</label>
                        <input type="text" id="formDate"  readonly class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Room</label>
                            <select class="select2-select-00 form-exam" style="width: 100%;" size="5" id="formClassroom">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Time Start</label>
                        <div id="inputStart" class="input-group">
                            <input data-format="hh:mm" type="text" id="formStart" class="form-control form-exam" value="00:00"/>
                            <span class="add-on input-group-addon">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Time End</label>
                        <div id="inputEnd" class="input-group">
                            <input data-format="hh:mm" type="text" id="formEnd" class="form-control form-exam" value="00:00"/>
                            <span class="add-on input-group-addon">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Coordinator Penguji</label>
                <select class="form-exam" style="width: 100%;" size="5" id="formCOPenguji">
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group">
                <label>Team Penguji</label>
                <select class="form-exam" multiple style="width: 100%;" size="5" id="formTeamPenguji"></select>
            </div>
            <div class="form-group">
                <label>Student</label>
                <div id="viewDataStd"></div>

            </div>
            <div class="form-group" style="text-align: right;">
                <button class="btn btn-success" id="btnSaveSch">Save</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        loadSelect2OptionClassroom('#formClassroom','');
        $('#inputStart,#inputEnd').datetimepicker({
            pickDate: false,
            pickSeconds : false
        });
        loadSelectOptionLecturersSingle('#formCOPenguji','');
        loadSelectOptionLecturersSingle('#formTeamPenguji','');

        $('#formCOPenguji,#formClassroom,#formTeamPenguji').select2({allowClear: true});

        $( "#formDate" )
            .datepicker({
                showOtherMonths:true,
                autoSize: true,
                dateFormat: 'dd MM yy',
                // minDate: new Date(moment().year(),moment().month(),moment().date()),
                onSelect : function () {
                    // var data_date = $(this).val().split(' ');
                    // var nextelement = $(this).attr('nextelement');
                    // nextDatePick(data_date,nextelement);
                }
            });


        loadStudent();


    });

    $('#formType').change(function () {
        loadStudent();
    });

    function loadStudent() {
        $('#viewDataStd').html('<select class="form-exam" multiple style="width: 100%;" size="5" id="formStudent"></select>');
        var formType = $('#formType').val();
        if(formType==1){
            loadSelectOptionStudentRegisterYudisium('#formStudent','');
            $('#formStudent').select2({allowClear: true});
        } else {
            loadSelectOptionStudentRegisterSeminarhasil('#formStudent','');
            $('#formStudent').select2({allowClear: true});
        }
    }

    $('#btnSaveSch').click(function () {

        var formType = $('#formType').val();
        var formDate = $('#formDate').datepicker("getDate");
        var formClassroom = $('#formClassroom').val();
        var formStart = $('#formStart').val();
        var formEnd = $('#formEnd').val();
        var formCOPenguji = $('#formCOPenguji').val();
        var formTeamPenguji = $('#formTeamPenguji').val();
        var formStudent = $('#formStudent').val();

        if(formType!='' && formType!=null &&
            formDate!='' && formDate!=null &&
        formClassroom!='' && formClassroom!=null &&
        formStart!='' && formStart!=null &&
        formEnd!='' && formEnd!=null &&
        formCOPenguji!='' && formCOPenguji!=null &&
        formTeamPenguji!='' && formTeamPenguji!=null &&
        formStudent!='' && formStudent!=null){

            loading_modal_show();

            var ClassroomID = formClassroom.split('.')[0];

            var Lecturer = [formCOPenguji];
            if(formTeamPenguji!='' && formTeamPenguji!=null){
                for(var i=0;i<formTeamPenguji.length;i++){
                    if(formCOPenguji!=formTeamPenguji[i]){
                        Lecturer.push(formTeamPenguji[i]);
                    }

                }
            }

            var data = {
                action : 'updateDataSchFP',
                ID : '',
                dataForm : {
                    Type : formType,
                    Date : moment(formDate).format('YYYY-MM-DD'),
                    ClassroomID : ClassroomID,
                    Start : formStart,
                    End : formEnd
                },
                Lecturer : Lecturer,
                Student : formStudent
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudFinalProject';

            $.post(url,{token:token},function (result) {

                toastr.success('Schedule saved','Success');
                setTimeout(function () {
                    window.location.href='';
                },500);

            });

        } else {
            toastr.warning('All forms are required','Warning');
        }

    });

</script>