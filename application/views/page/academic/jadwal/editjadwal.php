
<style>
    .span-sesi {
        font-size: 1.3em;
        font-weight: bold;
    }
    .td-center {
        padding-top: 15px !important;
        padding-bottom: 15px !important;
    }

    /*.form-sesiawal[readonly] {*/
        /*background-color: #ffffff;*/
        /*color: #333333;*/
        /*cursor: text;*/
    /*}*/
</style>

<div class="row" style="margin-bottom: 30px;">
    <label class="col-md-8 col-md-offset-2">
<!--        <button data-page="jadwal" class="btn btn-warning btn-action"><i class="fa fa-arrow-left right-margin" aria-hidden="true"></i> Back Schedule</button>-->
<!--        <button  data-page="jadwal" class="btn btn-info btn-action">-->
<!--            <i class="fa fa-arrow-circle-left right-margin" aria-hidden="true"></i> Back</button>-->

        <table class="table" id="tableForm" style="margin-top: 10px;">
            <tr>
                <td style="width: 190px;">Tahun Akademik</td>
                <td style="width: 1px;">:</td>
                <td>
                    <div id="semesterName">-</div>
                    <input id="formSemesterID" class="hide" type="hidden" readonly/>
                </td>
            </tr>
            <tr>
                <td>
                    Program Kuliah
                </td>
                <td>:</td>
                <td>
                    <div id="viewProgramsCampus"></div>
                </td>
            </tr>

            <tr>
                <td>Kelas Gabungan ?</td>
                <td>:</td>
                <td>
                    <div id="viewCombinedClasses"></div>
                </td>
            </tr>
            <tr>
                <td>Course</td>
                <td>:</td>
                <td>
                    <div id="viewBaseProdi"></div>
                </td>
            </tr>

            <tr>
                <td>Group Kelas</td>
                <td>:</td>
                <td>
                    <span class="btn-default-primary" id="viewClassGroup" style="padding-left: 5px;padding-right: 5px;"> - </span>
                </td>
            </tr>
            <tr>
                <td>Dosen Koordinator</td>
                <td>:</td>
                <td>
                    <select class="select2-select-00 full-width-fix form-jadwal-edit-sc"
                            size="5" id="formCoordinator">
                        <option value=""></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Dosen Team Teaching ?</td>
                <td>:</td>
                <td>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="radio-inline">
                                <input type="radio" class="form-jadwal-edit-sc" fm="dtt-form-edit-sc" name="formteamTeaching" value="0" checked> Tidak
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="form-jadwal-edit-sc"  fm="dtt-form-edit-sc" name="formteamTeaching" value="1"> Ya
                            </label>
                        </div>
                        <div class="col-md-8">
                            <select class="select2-select-00 full-width-fix form-jadwal-edit-sc"
                                    size="5" multiple id="formTeamTeaching" disabled></select>
                        </div>
                    </div>
                </td>
            </tr>



            <tbody id="bodyAddSesi"></tbody>
        </table>

        <hr/>

        <div style="text-align: right;">
            <button class="btn btn-danger btn-act-editForm" style="float: left;" id="btnRemove">Remove</button>
            <button class="btn btn-default btn-default-success btn-act-editForm" id="addNewSesi">Add Sub Sesi</button>
            |
            <button class="btn btn-success btn-act-editForm" id="btnSavejadwal">Save</button>
<!--            <button class="btn btn-default" onclick="checkSchedule(1,2,'10:41:00','11:01:00')" id="cek">cek</button>-->
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
        window.dataSesiArr = [];
        window.dataSesiDb = 1;
        window.dataSesi = 0;
        window.dataSesiNewArr = [];

        window.timeOption = {
            format : 'hh:ii',
            weekStart: 1,
            todayBtn:  0,
            autoclose: 1,
            todayHighlight: 0,
            startView: 1,
            minView: 0,
            maxView: 1,
            forceParse: 1};
        window.ScheduleID = '<?php echo $ScheduleID; ?>';

        loadSelectOptionLecturersSingle('#formTeamTeaching','');
        $('#formTeamTeaching').select2({allowClear: true});
        getDataSchedule();

    });


    $('#btnSavejadwal').click(function () {


        var process = [];

        // schedule ---

        var Coordinator = $('#formCoordinator').val();

        var TeamTeaching = $('input[name=formteamTeaching]:checked').val();
        var UpdateBy = sessionNIP;
        var UpdateAt = dateTimeNow();

        // schedule_team_teaching ---
        var teamTeachingArray = [];
        if(TeamTeaching==1){
            var formTeamTeaching = $('#formTeamTeaching').val();


            if(formTeamTeaching!=null){

                for(var t=0;t<formTeamTeaching.length;t++){
                    var dt = {
                        ScheduleID : ScheduleID,
                        NIP :  formTeamTeaching[t],
                        Status : '0'
                    };
                    teamTeachingArray.push(dt);
                }
            }
            else {
                process.push(0); requiredForm('#s2id_formTeamTeaching .select2-choices');
            }
        }


        // schedule_sesi ---
        var textTotalSKSMK = $('#textTotalSKSMK').val();
        var dataScheduleDetailsArray = [];
        var dataScheduleDetailsArrayNew = [];
        var totalCredit = 0;


        // Sub Sesi Dari DB
        if(dataSesiArr.length>0){
            for(var i=0;i<dataSesiArr.length;i++){
                var sdID = $('#sdID'+dataSesiArr[i]).val();
                var ClassroomID = $('#formClassroom'+dataSesiArr[i]).val();
                var DayID = $('#formDay'+dataSesiArr[i]).val();
                var Credit = $('#formCredit'+dataSesiArr[i]).val(); if(Credit=='' || Credit==0){process.push(0); requiredForm('#formCredit'+dataSesiArr[i]);}
                var TimePerCredit = $('#formTimePerCredit'+dataSesiArr[i]).val();
                var StartSessions = $('#formSesiAwal'+dataSesiArr[i]).val(); if(StartSessions==''){process.push(0); requiredForm('#formSesiAwal'+dataSesiArr[i]);}
                var EndSessions = $('#formSesiAkhir'+dataSesiArr[i]).val();if(EndSessions==''){process.push(0); requiredForm('#formSesiAkhir'+dataSesiArr[i]);}

                totalCredit = parseInt(totalCredit) + parseInt(Credit);
                var arrSesi = {
                    sdID : sdID,
                    update : {
                        ScheduleID : ScheduleID,
                        ClassroomID : ClassroomID,
                        Credit : Credit,
                        DayID : DayID,
                        TimePerCredit : TimePerCredit,
                        StartSessions : StartSessions,
                        EndSessions : EndSessions
                    }
                };

                dataScheduleDetailsArray.push(arrSesi);
            }
        }

        // Sub Sesi New
        if(dataSesiNewArr.length>0){
            for(var i=0;i<dataSesiNewArr.length;i++){
                var ClassroomID = $('#formClassroom'+dataSesiNewArr[i]).val();
                var DayID = $('#formDay'+dataSesiNewArr[i]).val();
                var Credit = $('#formCredit'+dataSesiNewArr[i]).val(); if(Credit=='' || Credit==0){process.push(0); requiredForm('#formCredit'+dataSesiNewArr[i]);}
                var TimePerCredit = $('#formTimePerCredit'+dataSesiNewArr[i]).val();
                var StartSessions = $('#formSesiAwal'+dataSesiNewArr[i]).val(); if(StartSessions==''){process.push(0); requiredForm('#formSesiAwal'+dataSesiNewArr[i]);}
                var EndSessions = $('#formSesiAkhir_newSub'+dataSesiNewArr[i]).val();if(EndSessions==''){process.push(0); requiredForm('#formSesiAkhir'+dataSesiNewArr[i]);}

                totalCredit = parseInt(totalCredit) + parseInt(Credit);
                var arrSesi = {
                    ScheduleID : ScheduleID,
                    ClassroomID : ClassroomID,
                    Credit : Credit,
                    DayID : DayID,
                    TimePerCredit : TimePerCredit,
                    StartSessions : StartSessions,
                    EndSessions : EndSessions
                };

                dataScheduleDetailsArrayNew.push(arrSesi);
            }
        }

        var SubSesi = ((parseInt(dataSesiArr.length) + parseInt(dataSesiNewArr.length))>1) ? '1' : '0';

        // console.log(dataSesiArr);
        // console.log(dataSesiNewArr);



        if($.inArray(0,process)==-1){

                var data = {
                    action : 'edit',
                    ID : ScheduleID,
                    formData :
                        {
                            schedule : {
                                Coordinator : Coordinator,
                                TeamTeaching : TeamTeaching,
                                SubSesi : SubSesi,
                                UpdateBy : UpdateBy,
                                UpdateAt : UpdateAt
                            },
                            schedule_team_teaching : {
                                teamTeachingArray : teamTeachingArray
                            },

                            schedule_details : {
                                dataScheduleDetailsArray : dataScheduleDetailsArray,
                                dataScheduleDetailsArrayNew : dataScheduleDetailsArrayNew
                            }

                        }
                };



                // $('#tableForm .form-sesiawal').prop('readonly',false);
                $('#formCoordinator,input[name=formteamTeaching],.form-jadwal-edit-sc,' +
                    '.btn-act-editForm,.btn-delete-sesi-edit-sc').prop('disabled',true);
                if(TeamTeaching==1 && formTeamTeaching!=null){
                    $('#formTeamTeaching').prop('disabled',true);
                }

                loading_button('#btnSavejadwal');


                // return false;

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api/__crudSchedule';
                $.post(url,{token:token},function (jsonResult) {

                    toastr.success('Data Saved','Success');

                    setTimeout(function () {
                        // $('#tableForm .form-sesiawal').prop('readonly',true);
                        $('#formCoordinator,input[name=formteamTeaching],.form-jadwal-edit-sc,.btn-act-editForm,.btn-delete-sesi-edit-sc').prop('disabled',false);
                        $('#btnSavejadwal').html('Save');
                        if(TeamTeaching==1 && formTeamTeaching!=null){
                            $('#formTeamTeaching').prop('disabled',false);
                        }

                        window.location.href= base_url_js+'academic/jadwal';
                    },3000);

                });

        } else {
            toastr.error('Form Required','Error');
        }


    });


    $('#btnRemove').click(function () {
        // var ScheduleID = $(this).attr('data-id');
        $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Remove Schedule ?? </b> ' +
            '<button type="button" id="btnRemoveYesEditSc" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
            '<button type="button" id="btnRemoveNoEditSc" class="btn btn-default" data-dismiss="modal">No</button>' +
            '</div>');
        $('#NotificationModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    
    function setSesiAkhir_EditJadwal(ID) {
        var TimePerCredit = $('#formTimePerCredit'+ID).val();
        var SesiAwal = $('#formSesiAwal'+ID).val();
        var Credit = $('#formCredit'+ID).val();

        if(TimePerCredit!='' && SesiAwal!='' && Credit!='' && typeof SesiAwal != 'undefined'){
            var totalTime = parseInt(TimePerCredit) * parseInt(Credit);
            var expSesi = SesiAwal.split(':');
            var sesiAkhir = moment()
                .hours(expSesi[0])
                .minutes(expSesi[1])
                .add(parseInt(totalTime), 'minute').format('HH:mm');

            $('#formSesiAkhir'+ID).val(sesiAkhir);
            $('#formSesiAkhir_newSub'+ID).val(sesiAkhir);
        }
    }

    function loadformTeamTeaching(value,element_dosen) {
        if(value==1){
            $(element_dosen).prop('disabled',false);
        } else {
            $(element_dosen).select2("val", null);
            $(element_dosen).prop('disabled',true);
        }
    }

    function requiredForm(element) {
        $(element).css('border','1px solid red');
        setTimeout(function () {
            $(element).css('border','1px solid #cccccc');
        },5000);
        return false;
    }

    function checkSchedule_EditJadwal(ID) {

        var SemesterID = $('#formSemesterID').val();
        var ProgramsCampusID = $('#formProgramsCampusID').val();

        var element = '#alertBentrok'+ID;
        var ClassroomID = $('#formClassroom'+ID).val();
        var DayID = $('#formDay'+ID).val();
        var StartSessions = $('#formSesiAwal'+ID).val();
        var formSesiAkhir = $('#formSesiAkhir'+ID).val();
        var formSesiAkhir_newSub = $('#formSesiAkhir_newSub'+ID).val();

        var EndSessions = (typeof formSesiAkhir_newSub  !== "undefined") ? formSesiAkhir_newSub : formSesiAkhir ;

        if(ClassroomID!='' && DayID!='' && StartSessions!='' && EndSessions!='') {
            var data = {
                action : 'check',
                formData : {
                    SemesterID : SemesterID,
                    IsSemesterAntara : ''+SemesterAntara,
                    ClassroomID : ClassroomID,
                    DayID : DayID,
                    StartSessions : StartSessions,
                    EndSessions : EndSessions
                }
            };

            // console.log(data);

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__checkSchedule';
            $.post(url,{token:token},function (json_result) {

                var MKID_ = $('#formMKID').val();
                var MKCode_ = $('#formMKCode').val();

                $('#btnSavejadwal,#addNewSesi').prop('disabled',false);
                $(element).html('');
                $('.trNewSesi'+ID).css('background','#ffffff');
                if(json_result.length>0){
                    $('#btnSavejadwal,#addNewSesi').prop('disabled',true);
                    $('.trNewSesi'+ID).css('background','#ffeb3b63');
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
                    for(var i=0;i<json_result.length;i++){
                        var data = json_result[i];
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
            });
        }

    }

    function getDataSchedule() {

        var url = base_url_js+'api/__crudSchedule';
        var data = {
            action : 'readOneSchedule',
            ScheduleID : ScheduleID
        };
        var token = jwt_encode(data,'UAP)(*');

        $.post(url,{token:token},function (JSONresult) {


            $('#semesterName').html('<b style="color:green;">'+JSONresult.semesterName+'</b>');
            $('#viewProgramsCampus').html('<b style="color:green;">'+JSONresult.viewProgramsCampus+'</b>');
            var viewCombinedClasses = (JSONresult.CombinedClasses==1) ? 'Yes' : 'No';

            var dataProdi = JSONresult.Courses.length;
            var viewBaseProdi = $('#viewBaseProdi');
            for(var i=0;i<dataProdi;i++){

            }

            $('#viewCombinedClasses').html('<b style="color:green;">'+viewCombinedClasses+'</b>');


            $('#viewBaseProdi').html('<ul id="listCourse" style="list-style-type: none;padding-left:0px;"></ul>');
            for(var c=0;c<JSONresult.Courses.length;c++){
                var course = JSONresult.Courses[c];
                $('#listCourse').append('<li><strong><span style="color:#1785dc;">'+course.ProdiEng+'</span> | '+course.MKCode+' - '+course.NameEng+'</strong></li>');
            }

            $('#textTotalSKSMK').val(JSONresult.TotalSKS);

            $('#viewClassGroup').text(JSONresult.viewClassGroup);


            loadSelectOptionLecturersSingle('#formCoordinator',JSONresult.NIP);
            $('#formCoordinator').select2({allowClear: true});

            if(JSONresult.TeamTeaching==1) {
                $('#formTeamTeaching').empty();
                $('#formTeamTeaching').prop('disabled',false);
                $('input[name=formteamTeaching][value=1]').prop('checked',true);
                loadSelectOptionLecturersSingle('#formTeamTeaching',JSONresult.DetailTeamTeaching);
                $('#formTeamTeaching').select2({allowClear: true});
            } else {
                $('#formTeamTeaching').prop('disabled',true);
                $('input[name=formteamTeaching][value=0]').prop('checked',true);
            }

            $('#formSemesterID').val(JSONresult.SemesterID);

            loadSubSesi(JSONresult.SubSesiDetails);

            $('#btnRemove').attr('data-code',JSONresult.MKCode);

        });
    }

    function loadSubSesi(SubSesiDetails) {
        var hd = (SubSesiDetails.length==1) ? 'hide' : '';
        var btnRv = (SubSesiDetails.length==1) ? 'disabled' : '';
        var tb = $('#bodyAddSesi');

        dataSesi = SubSesiDetails.length;

        for(var i=0;i<SubSesiDetails.length;i++){


            var btn_conf = (i!=0) ? 'hide' : '';



            // dataSesiArr.push(parseInt(SubSesiDetails[i].sdID));
            dataSesiArr.push(parseInt(dataSesiDb));
            tb.append('<tr class="trNewSesi'+dataSesiDb+' '+hd+'"  id="headerSubSesi'+dataSesiDb+'">' +
                '                <td colspan="3" class="td-center " id="subsesi'+dataSesiDb+'">' +
                '                    <span class="btn btn-info span-sesi">--- Sub Sesi ---</span>' +
                '                    <button style="float:right;" '+btnRv+' class="btn btn-default btn-default-danger btn-delete-sesi-edit-sc" data-sesi="'+dataSesiDb+'" data-sd="'+SubSesiDetails[i].sdID+'">Remove This Sub Sesi</button>' +
                '                </td>' +
                '            </tr>' +
                '            <tr class="trNewSesi'+dataSesiDb+'">' +
                '                <td>Room | Day | Credit <input type="hide" class="hide" readonly id="sdID'+dataSesiDb+'" value="'+SubSesiDetails[i].sdID+'" /> </td>' +
                '                <td>:</td>' +
                '                <td>' +
                '                    <div class="row">' +
                '                        <div class="col-xs-5">' +
                '                            <select class="form-control form-jadwal-edit-sc form-classroom-edit-sc" data-id="'+dataSesiDb+'" id="formClassroom'+dataSesiDb+'">' +
                '                                <option value=""></option>' +
                '                            </select>' +
                '                            <a href="javascript:void(0)" id="addClassRoom"  class="'+btn_conf+'" style="font-size:10px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Ruangan</a>' +
                '                        </div>' +
                '                        <div class="col-xs-4">' +
                '                            <select class="form-control form-jadwal-edit-sc form-day-edit-sc" data-id="'+dataSesiDb+'" id="formDay'+dataSesiDb+'"></select>' +
                '                        </div>' +
                '                        <div class="col-xs-3">' +
                '                            <input class="form-control form-jadwal-edit-sc form-credit-edit-sc" placeholder="Credit" dataSesiDb="'+dataSesiDb+'" data-id="'+dataSesiDb+'" id="formCredit'+dataSesiDb+'" type="number"/>' +
                '                        </div>' +
                '                    </div>' +
                '                </td>' +
                '            </tr>' +
                '            <tr class="trNewSesi'+dataSesiDb+'">' +
                '                <td>Time</td>' +
                '                <td>:</td>' +
                '                <td>' +
                '                    <div class="row">' +
                '                        <div class="col-xs-4">' +
                '                            <select class="form-control form-jadwal-edit-sc form-timepercredit-edit-sc" data-id="'+dataSesiDb+'" id="formTimePerCredit'+dataSesiDb+'"></select>' +
                '                            <a href="javascript:void(0)" id="addTimePerCredit" class="'+btn_conf+'" style="font-size:10px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah <i>Time Per Credit</i></a>' +
                '                        </div>' +
                '                        <div class="col-xs-4">' +
                '                           <div id="div_formSesiAwal'+dataSesiDb+'" data-no="'+dataSesiDb+'" class="input-group">' +
                '                                <input data-format="hh:mm" type="text" id="formSesiAwal'+dataSesiDb+'" class="form-control form-attd" value="'+SubSesiDetails[i].StartSessions.substr(0,5)+'"/>' +
                '                                <span class="add-on input-group-addon">' +
                '                                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>' +
                '                                </span>' +
                '                            </div>' +
                '                        </div>' +
                '                        <div class="col-xs-4">' +
                '                            <input type="text" class="form-control form-jadwal-edit-sc" id="formSesiAkhir'+dataSesiDb+'" value="'+SubSesiDetails[i].EndSessions.substr(0,5)+'" style="color: #333;" readonly />' +
                '                        </div>' +
                '                    </div>' +
                '                    <div id="alertBentrok'+dataSesiDb+'"></div>' +
                '                </td>' +
                '            </tr>');

            loadSelectOptionClassroom('#formClassroom'+dataSesiDb,SubSesiDetails[i].ClassroomID);
            fillDays('#formDay'+dataSesiDb,'Eng',SubSesiDetails[i].DayID);
            $('#formCredit'+dataSesiDb).val(SubSesiDetails[i].Credit);
            // $('#formSesiAkhir'+dataSesiDb).val();

            var TimePerCredit = SubSesiDetails[i].TimePerCredit;

            loadSelectOptionTimePerCredit('#formTimePerCredit'+dataSesiDb,TimePerCredit);


            $('#div_formSesiAwal'+dataSesiDb).datetimepicker({
                pickDate: false,
                pickSeconds : false
            }).on('changeDate', function(e) {
                var no = $(this).attr('data-no');
                var d = new Date(e.localDate);
                var Credit = $('#formCredit'+no).val();
                var totalTime = parseInt(TimePerCredit) * parseInt(Credit);

                var sesiAkhir = moment()
                    .hours(d.getHours())
                    .minutes(d.getMinutes())
                    .add(parseInt(totalTime), 'minute').format('HH:mm');

                $('#formSesiAkhir'+no).val(sesiAkhir);

                setSesiAkhir_EditJadwal(no);
            });

            dataSesiDb += 1;

        }
    }


    $('#addNewSesi').click(function () {

        var newSesi = true;

        if(dataSesiArr.length==1){
            $('#headerSubSesi'+dataSesiArr[0]).removeClass('hide');
        }


        if(newSesi){
            dataSesi = dataSesi + 1;

            dataSesiNewArr.push(dataSesi);


            $('#subsesi1').removeClass('hide');
            $('#bodyAddSesi').append('<tr class="trNewSesi'+dataSesi+'">' +
                '                <td colspan="3" class="td-center">' +
                '                    <span class="btn btn-warning span-sesi">--- Sub Sesi ---</span>' +
                '                    <button style="float:right;" class="btn btn-default btn-default-danger btn-delete-sesi-edit-sc" data-sesi="'+dataSesi+'" data-sd="">Remove This Sub Sesi</button>' +
                '                </td>' +
                '            </tr>' +
                '            <tr class="trNewSesi'+dataSesi+'">' +
                '                <td>Ruang | Hari | Credit</td>' +
                '                <td>:</td>' +
                '                <td>' +
                '                    <div class="row">' +
                '                        <div class="col-xs-5">' +
                '                            <select class="form-control form-jadwal-edit-sc form-classroom-edit-sc" data-id="'+dataSesi+'" id="formClassroom'+dataSesi+'">' +
                '                                <option value=""></option>' +
                '                            </select>' +
                '                        </div>' +
                '                        <div class="col-xs-4">' +
                '                            <select class="form-control form-jadwal-edit-sc form-day-edit-sc" data-id="'+dataSesi+'" id="formDay'+dataSesi+'"></select>' +
                '                        </div>' +
                '                        <div class="col-xs-3">' +
                '                            <input class="form-control form-jadwal-edit-sc form-credit-edit-sc" data-id="'+dataSesi+'" placeholder="Credit" id="formCredit'+dataSesi+'" type="number"/>' +
                '                        </div>' +
                '                    </div>' +
                '                </td>' +
                '            </tr>' +
                '            <tr class="trNewSesi'+dataSesi+'">' +
                '                <td>Time</td>' +
                '                <td>:</td>' +
                '                <td>' +
                '                    <div class="row">' +
                '                        <div class="col-xs-4">' +
                '                            <select class="form-control form-jadwal-edit-sc form-timepercredit-edit-sc" data-id="'+dataSesi+'" id="formTimePerCredit'+dataSesi+'">' +
                '                            </select>' +
                '                        </div>' +
                '                        <div class="col-xs-4">' +
                '                           <div id="div_formSesiAwal_newSub'+dataSesi+'" data-no="'+dataSesi+'" class="input-group">' +
                '                                <input data-format="hh:mm" type="text" id="formSesiAwal'+dataSesi+'" class="form-control form-attd" value="00:00"/>' +
                '                                <span class="add-on input-group-addon">' +
                '                                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>' +
                '                                </span>' +
                '                            </div>' +
                '                        </div>' +
                '                        <div class="col-xs-4">' +
                '                            <input type="text" class="form-control form-jadwal-edit-sc" id="formSesiAkhir_newSub'+dataSesi+'" style="color: #333;" readonly />' +
                '                        </div>' +
                '                    </div>' +
                '<div id="alertBentrok'+dataSesi+'"></div>' +
                '                </td>' +
                '            </tr>');

            loadSelectOptionClassroom('#formClassroom'+dataSesi,'');
            fillDays('#formDay'+dataSesi,'Eng','');
            loadSelectOptionTimePerCredit('#formTimePerCredit'+dataSesi,'');



            $('#div_formSesiAwal_newSub'+dataSesi).datetimepicker({
                pickDate: false,
                pickSeconds : false
            }).on('changeDate', function(e) {
                var no = $(this).attr('data-no');
                var d = new Date(e.localDate);
                var Credit = $('#formCredit'+no).val();
                var TimePerCredit = $('#formTimePerCredit'+no).val();
                var totalTime = parseInt(TimePerCredit) * parseInt(Credit);

                var sesiAkhir = moment()
                    .hours(d.getHours())
                    .minutes(d.getMinutes())
                    .add(parseInt(totalTime), 'minute').format('HH:mm');

                $('#formSesiAkhir_newSub'+no).val(sesiAkhir);
                checkSchedule_EditJadwal(no);
            });


        } else {
            toastr.warning('Form Sub Sesi '+dataSesi+' Harus Diisi','Warning!');
        }

    });


</script>




