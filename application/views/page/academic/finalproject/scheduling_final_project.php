
<style>
    #formDate {
        background: #ffffff;
        color: #333333;
        cursor: pointer;
    }

    #viewTableStudent table tr th, #viewTableStudent table tr td {
        text-align: center;
    }
</style>


<div class="row" style="margin-top: 30px;">
    <div class="col-md-8 col-md-offset-2">
        <div class="well">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-4">
                        <label>Semester</label>
                        <input id="formID" class="hide" value="<?= $ID; ?>">
                        <select class="form-control" id="filterSemester"></select>
                        <div id="viewSemester"></div>
                    </div>
                    <div class="col-md-4 hide">
                        <label>Type</label>
                        <select id="formType" class="form-control">
                            <option value="1">Seminar Proposal</option>
                            <option value="2">Seminar Hasil</option>
                            <option disabled>-------</option>
                            <option value="3">Make-up Seminar Proposal</option>
                            <option value="4">Make-up Seminar Hasil</option>
                        </select>
                        <div id="viewType"></div>
                    </div>
                    <div class="col-md-4">
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
                <div id="viewDataStd"></div>
            </div>
            <div id="viewTableStudent"></div>

            <div class="form-group" style="text-align: right;">
                <button class="btn btn-success" id="btnSaveSch">Save</button>
                <textarea class="hide" id="dataEdit"><?= $DataEdit ?></textarea>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {



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

        // Cek edit
        var ID = "<?= $ID; ?>";
        if(ID!=''){
            var dataEdit = $('#dataEdit').val();
            var d = (dataEdit!='') ? JSON.parse(dataEdit) : [];

            if(d.length>0){

                var v = d[0];

                $('#formType').val(v.Type);
                loSelectOptionSemester('#filterSemester',v.SemesterID);
                loadSelect2OptionClassroom('#formClassroom',v.ClassroomID+'.'+v.Seat+'.'+v.SeatForExam);
                $('#formDate').datepicker('setDate',new Date(v.Date));

                $('#formStart').val(v.Start);
                $('#formEnd').val(v.End);

                $('#inputStart,#inputEnd').datetimepicker({
                    pickDate: false,
                    pickSeconds : false
                });

                var Examiner = v.Examiner;
                var teamEx = [];
                if(Examiner.length>0){
                    $.each(Examiner,function (i,v) {
                       if(i!=0){
                           teamEx.push(v.NIP);
                       }
                    });
                }

                loadSelectOptionLecturersSingle('#formCOPenguji',Examiner[0].NIP);
                loadSelectOptionLecturersSingle('#formTeamPenguji',teamEx);

                $('#formCOPenguji,#formClassroom,#formTeamPenguji').select2({allowClear: true});

                $('#viewDataStd').html('<select class="form-exam" multiple style="width: 100%;" size="5" id="formStudent"></select>');


                $('#viewTableStudent').html('<div class="thumbnail" style="margin-bottom: 15px;padding: 15px;">' +
                    '                <div class="form-group">' +
                    '                    <table class="table">' +
                    '                        <thead>' +
                    '                        <tr>' +
                    '                            <th style="width: 1%;">No</th>' +
                    '                            <th>Student</th>' +
                    '                            <th style="width: 37%;">Mentor</th>' +
                    '                            <th style="width: 5%;"><i class="fa fa-cog"></i></th>' +
                    '                        </tr>' +
                    '                        </thead>' +
                    '                        <tbody id="listStdOk"></tbody>' +
                    '                    </table>' +
                    '                </div>' +
                    '            </div>');

                var Student = v.Student;
                if(Student.length>0){

                    $.each(Student,function (i,v) {

                        var Mentor1 = (v.MentorFP1_Name!='' && v.MentorFP1_Name!=null) ? '<div>- '+v.MentorFP1_Name+'</div>' : '';
                        var Mentor2 = (v.MentorFP2_Name!='' && v.MentorFP2_Name!=null) ? '<div>- '+v.MentorFP2_Name+'</div>' : '';

                        var btnAct = (v.Status==2 || v.Status=='2')
                            ? '<button data-id="'+v.ID+'" data-npm="'+v.NPM+'" class="btn btn-sm btn-danger btnRemoveStd"><i class="fa fa-trash"></i></button>'
                            : '-';
                        $('#listStdOk').append('<tr>' +
                            '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                            '<td style="text-align: left;"><b>'+v.Name+'</b><br/>'+v.NPM+'</td>' +
                            '<td style="text-align: left;">'+Mentor1+''+Mentor2+'</td>' +
                            '<td id="td_'+v.ID+'">'+btnAct+'</td>' +
                            '</tr>');
                    });
                }


                if(v.Type=='1' || v.Type==1){
                    loadSelectOptionStudentYudisium('#formStudent','','1',v.SemesterID);
                    $('#formStudent').select2({allowClear: true});
                } else {
                    loadSelectOptionStudentYudisium('#formStudent','','3',v.SemesterID);
                    $('#formStudent').select2({allowClear: true});
                }

                $('#formType,#filterSemester').addClass('hide');
                var viewType = $('#formType option:selected').text();
                $('#viewType').html('<b>'+viewType+'</b>');

                var firstLoadSmt = setInterval(function (args) {
                    var filterSemester = $('#filterSemester').val();
                    if(filterSemester!='' && filterSemester!=null){
                        var viewSemester = $('#filterSemester option:selected').text();
                        $('#viewSemester').html('<b>'+viewSemester+'</b>');
                        clearInterval(firstLoadSmt);
                    }
                },1000);

                setTimeout(function () {
                    clearInterval(firstLoadSmt);
                },5000);





            } else {
                loadInsertForm();
            }

        } else {
            loadInsertForm();
        }







    });

    function loadInsertForm() {

        loSelectOptionSemester('#filterSemester','');
        loadSelect2OptionClassroom('#formClassroom','');

        $('#inputStart,#inputEnd').datetimepicker({
            pickDate: false,
            pickSeconds : false
        });

        loadSelectOptionLecturersSingle('#formCOPenguji','');
        loadSelectOptionLecturersSingle('#formTeamPenguji','');

        $('#formCOPenguji,#formClassroom,#formTeamPenguji').select2({allowClear: true});


        var firsLoad = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            if(filterSemester!='' && filterSemester!=null){
                loadStudent();
                clearInterval(firsLoad);
            }
        },1000);

        setTimeout(function () {
            clearInterval(firsLoad);
        },5000);


    }

    $('#formType,#filterSemester').change(function () {
        loadStudent();
    });

    function loadStudent() {

        var filterSemester = $('#filterSemester').val();

        if(filterSemester!='' && filterSemester!=null) {

            var SemesterID = filterSemester.split('.')[0];

            $('#viewDataStd').html('<select class="form-exam" multiple style="width: 100%;" size="5" id="formStudent"></select>');
            var formType = $('#formType').val();
            if(formType==1){
                loadSelectOptionStudentYudisium('#formStudent','','1',SemesterID);
                $('#formStudent').select2({allowClear: true});
            } else if(formType==2) {
                loadSelectOptionStudentYudisium('#formStudent','','3',SemesterID);
                $('#formStudent').select2({allowClear: true});
            } else if(formType==3) {
                loadSelectOptionStudentYudisium('#formStudent','','-3',SemesterID);
                $('#formStudent').select2({allowClear: true});
            } else if(formType==4) {
                loadSelectOptionStudentYudisium('#formStudent','','-5',SemesterID);
                $('#formStudent').select2({allowClear: true});
            }
        }


    }

    $('#btnSaveSch').click(function () {

        var filterSemester = $('#filterSemester').val();
        var formType = $('#formType').val();
        var formDate = $('#formDate').datepicker("getDate");
        var formClassroom = $('#formClassroom').val();
        var formStart = $('#formStart').val();
        var formEnd = $('#formEnd').val();
        var formCOPenguji = $('#formCOPenguji').val();
        var formTeamPenguji = $('#formTeamPenguji').val();

        if(filterSemester!='' && filterSemester!=null &&
            formType!='' && formType!=null &&
            formDate!='' && formDate!=null &&
        formClassroom!='' && formClassroom!=null &&
        formStart!='' && formStart!=null &&
        formEnd!='' && formEnd!=null &&
        formCOPenguji!='' && formCOPenguji!=null &&
        formTeamPenguji!='' && formTeamPenguji!=null){

            loading_modal_show();

            var SemesterID = filterSemester.split('.')[0];

            var ClassroomID = formClassroom.split('.')[0];

            var Lecturer = [formCOPenguji];
            if(formTeamPenguji!='' && formTeamPenguji!=null){
                for(var i=0;i<formTeamPenguji.length;i++){
                    if(formCOPenguji!=formTeamPenguji[i]){
                        Lecturer.push(formTeamPenguji[i]);
                    }

                }
            }

            var formID = $('#formID').val();

            var formStudent = $('#formStudent').val();


            var data = {
                action : 'updateDataSchFP',
                ID : (formID!='' && formID!=null) ? formID : '',
                dataForm : {
                    SemesterID : SemesterID,
                    Type : formType,
                    Date : moment(formDate).format('YYYY-MM-DD'),
                    ClassroomID : ClassroomID,
                    Start : formStart,
                    End : formEnd
                },
                StatusStd : (formType==1 || formType=='1') ? '2' : '4',
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

    $(document).on('click','.btnRemoveStd',function () {
        if(confirm('Are you sure?')){
            var ID = $(this).attr('data-id');
            var NPM = $(this).attr('data-npm');

            var data = {
              action : 'removeStudentSchFP',
              ID : ID,
              NPM : NPM
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudFinalProject';

            $.post(url,{token:token},function (result) {
                loadStudent();
                toastr.success('Data removed','Success');
                $('#td_'+ID).html('Removed');
            });
        }
    });
</script>