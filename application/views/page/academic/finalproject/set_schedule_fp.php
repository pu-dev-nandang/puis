
<style>
    #formDate {
        background: #ffffff;
        color: #333333;
        cursor: pointer;
    }
</style>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-3" style="border-right: 1px solid #CCCCCC;">
        <div class="well">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Type</label>
                        <select class="form-control">
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
                <label>Room</label>
                <select class="select2-select-00 form-exam" style="width: 100%;" size="5" id="formClassroom">
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Time Start</label>
                        <div id="inputStart" class="input-group">
                            <input data-format="hh:mm" type="text" id="formStart" class="form-control form-exam" value="00:00"/>
                            <span class="add-on input-group-addon">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                <label>Examiner Coordinator</label>
                <select class="form-exam" style="width: 100%;" size="5" id="formCOPenguji">
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group">
                <label>Examiner Team</label>
                <select class="form-exam" multiple style="width: 100%;" size="5" id="formTeamPenguji"></select>
            </div>
            <div class="form-group">
                <label>Students who already have a mentor</label>
                <select class="form-exam" multiple style="width: 100%;" size="5" id="formStudent"></select>
            </div>
            <div class="form-group" style="text-align: right;">
                <button class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
    <div class="col-md-8">

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
        loadSelectOptionStudentRegisterYudisium('#formStudent','');
        $('#formCOPenguji,#formClassroom,#formTeamPenguji,#formStudent').select2({allowClear: true});

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


    });

</script>