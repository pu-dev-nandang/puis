
<style>
    .span-sesi {
        font-size: 1.3em;
        font-weight: bold;
    }
    .td-center {
        padding-top: 15px !important;
        padding-bottom: 15px !important;
    }

    .form-sesiawal[readonly] {
        background-color: #ffffff;
        color: #333333;
        cursor: text;
    }
    .tr-prodi {
        background: lightyellow;
    }

    li.highlighted {
        background: #8bc34a !important;
    }
</style>

<div class="row" style="margin-bottom: 30px;">
    <label class="col-md-8 col-md-offset-2">
<!--        <button  data-page="jadwal" class="btn btn-info btn-action">-->
<!--            <i class="fa fa-arrow-circle-left right-margin" aria-hidden="true"></i> Back</button>-->

        <table class="table" id="tableForm" style="margin-top: 10px;">
            <tr>
                <td style="width: 190px;">Tahun Akademik</td>
                <td style="width: 1px;">:</td>
                <td>
                    <strong id="semesterName">-</strong>
                    <input id="formSemesterID" class="hide" type="hidden" readonly/>
                </td>
            </tr>
            <tr>
                <td>
                    Program Kuliah
                </td>
                <td>:</td>
                <td>
                    <select class="form-control form-jadwal" id="formProgramsCampusID"></select>
                </td>
            </tr>

            <tr>
                <td>Kelas Gabungan ?</td>
                <td>:</td>
                <td>
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="radio-inline">
                                <input type="radio" name="formCombinedClasses" class="form-jadwal" value="0" checked> Tidak
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="formCombinedClasses" class="form-jadwal" value="1"> Ya
                            </label>
                        </div>
                    </div>

                </td>
            </tr>

            <tr id="divGabugan" class="hide">
                <td colspan="3" class="td-center">
                    <span class="label label-info span-sesi">Gabungan</span>
                </td>
            </tr>
            <tr class="">
                <td>Program Studi</td>
                <td>:</td>
                <td>
                    <select class="form-control fm-prodi" data-id="1" id="formBaseProdi1">
                        <option value="" selected disabled>--- Select Prodi ---</option>
                    </select>
                </td>
            </tr>
            <tr class="">
                <td>Mata Kuliah</td>
                <td>:</td>
                <td>
                    <div id="dataMK1"></div>
                    <input id="textTotalSKSMK" type="hide" class="hide" hidden readonly>
                </td>
            </tr>
            <tbody id="bodyAddProdi"></tbody>
            <tr class="hide" id="btnControlProdi">
                <td colspan="3" class="td-center">
                    <button class="btn btn-default btn-default-danger" data-remove="0" id="btnRemoveProdi"><i class="fa fa-minus-circle right-margin" aria-hidden="true"></i> Remove Prodi</button> |
                    <button class="btn btn-default btn-default-success" id="btnAddProdi"><i class="fa fa-plus-circle right-margin" aria-hidden="true"></i> Add Prodi</button>
                </td>
            </tr>

            <tr class="hide">
                <td>Group Paralel</td>
                <td>:</td>
                <td>
                    <div class="row">
                        <div class="col-xs-3">
                            <div class="checkbox" style="margin:0px;">
                                <label>
                                    <input type="checkbox" id="formParalel"> Paralel
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <select class="form-control" id="ClassgroupParalel"></select>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td>Group Kelas</td>
                <td>:</td>
                <td>
                    <span class="btn-default-primary hide" id="viewClassGroup" style="padding-left: 5px;padding-right: 5px;"> - </span>
                    <input type="hide" class="hide" id="formClassGroup" />
                    <input type="text" class="form-control" style="max-width: 150px;" onkeyup="this.value = this.value.toUpperCase();" id="formClassGroupCadangan" />
                    <div id="alertClassGroup"></div>
                </td>
            </tr>

            <tr>
                <td>Dosen Koordinator</td>
                <td>:</td>
                <td>
                    <select class="select2-select-00 full-width-fix form-jadwal"
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
                                <input type="radio" class="form-jadwal" fm="dtt-form" name="formteamTeaching" data-id="1" value="0" checked> Tidak
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="form-jadwal"  fm="dtt-form" name="formteamTeaching" data-id="1" value="1"> Ya
                            </label>
                        </div>
                        <div class="col-md-8">
                            <select class="select2-select-00 full-width-fix form-jadwal"
                                    size="5" multiple id="formTeamTeaching" disabled></select>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="3" class="td-center hide" id="subsesi1">
                    <span class="label label-warning span-sesi">--- Sub Sesi <span id="TextNoSesi1"></span> ---</span>
                </td>
            </tr>
            <tr class="trNewSesi1">
                <td>Room | Day | Credit</td>
                <td>:</td>
                <td>
                    <div class="row">
                        <div class="col-xs-5">
                            <select class="form-control form-jadwal form-classroom" data-id="1" id="formClassroom1">
                                <option value=""></option>
                            </select>
                            <a href="javascript:void(0)" id="addClassRoom" style="font-size:10px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Ruangan</a>
                        </div>
                        <div class="col-xs-4">
                            <select class="form-control form-jadwal form-day" data-id="1" id="formDay1"></select>
                        </div>
                        <div class="col-xs-3">
                            <input class="form-control form-credit" data-id="1" placeholder="Credit" id="formCredit1" type="number"/>
                            <p style="margin:0px;color: #009688;">Maks Credit : <span id="viewMaksCredit">0</span></p>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="trNewSesi1">
                <td>Time</td>
                <td>:</td>
                <td>
                    <div class="row">

                        <div class="col-xs-4">
                            <select class="form-control form-timepercredit" data-id="1" id="formTimePerCredit1"></select>
                            <a href="javascript:void(0)" id="addTimePerCredit" style="font-size:10px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah <i>Time Per Credit</i></a>
                        </div>
                        <div class="col-xs-4">
                            <div id="div_formSesiAwal1" data-no="1" class="input-group">
                                <input data-format="hh:mm" type="text" id="formSesiAwal1" class="form-control form-attd" value="00:00"/>
                                <span class="add-on input-group-addon">
                                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" id="formSesiAkhir1" style="color: #333;" readonly />
                        </div>
                    </div>
                    <div id="alertBentrok1"></div>
                </td>
            </tr>
            <tbody id="bodyAddSesi"></tbody>
        </table>

        <hr/>

        <div style="text-align: right;margin-bottom: 70px;">
            <button class="btn btn-default btn-default-danger" id="removeNewSesi">Remove Sub Sesi</button>
            <button class="btn btn-default btn-default-success" data-group="1" id="addNewSesi">Add Sub Sesi</button>
            |
            <button class="btn btn-success" id="btnSavejadwal">Save</button>
        </div>
    </div>
</div>



<script>

    $(document).ready(function () {
        loadSelectOptionBaseProdi('#formBaseProdi1');
        window.dataProdi = 1;
    });

    $('#btnAddProdi').click(function () {
        dataProdi += 1;
        $('#bodyAddProdi').append('<tr class="tr-prodi tr-p'+dataProdi+'">' +
            '                <td>Program Studi</td>' +
            '                <td>:</td>' +
            '                <td>' +
            '                    <select class="form-control fm-prodi" data-id="'+dataProdi+'" id="formBaseProdi'+dataProdi+'"><option value="" selected disabled>--- Select Prodi ---</option></select>' +
            '                </td>' +
            '            </tr>' +
            '            <tr class="tr-prodi tr-p'+dataProdi+'">' +
            '                <td>Mata Kuliah</td>' +
            '                <td>:</td>' +
            '                <td>' +
            '                    <div id="dataMK'+dataProdi+'"></div>' +
            '                </td>' +
            '            </tr>');


        $('#btnRemoveProdi').prop('data-remove',dataProdi);
        loadSelectOptionBaseProdi('#formBaseProdi'+dataProdi);

    });

    $('#btnRemoveProdi').click(function () {
        // var IDrm = $(this).attr('data-remove');

        if(dataProdi>1){
            $('.tr-p'+dataProdi).remove();
            dataProdi -= 1;

            if(dataProdi==1){
                $('input[type=radio][name=formCombinedClasses][value=0]').prop('checked',true);
                $('#btnControlProdi').addClass('hide');
                $('#divGabugan').addClass('hide');
                setGroupClass();
            }
        } else {
            $('input[type=radio][name=formCombinedClasses][value=0]').prop('checked',true);
            $('#btnControlProdi').addClass('hide');
            $('#divGabugan').addClass('hide');
            setGroupClass();

        }

    });

    function getCourseOfferings(ProdiID,divNum) {
        var url = base_url_js+'api/__crudCourseOfferings';
        var SemesterID = $('#formSemesterID').val();
        var data = {
            action : 'readToSchedule',
            formData : {
                SemesterID : SemesterID,
                ProdiID : ProdiID,
                IsSemesterAntara : ''+SemesterAntara
            }
        };
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {

            // console.log(jsonResult);

            if(jsonResult.length>0){
                $('#dataMK'+divNum).html('<select class="select2-select-00 full-width-fix" size="5" id="formMataKuliah'+divNum+'">' +
                    '                        <option value=""></option>' +
                    '                    </select>');

                for(var i=0;i<jsonResult.length;i++){
                        var semester = jsonResult[i].Offerings.Semester;

                        var mk = jsonResult[i].Details;
                        for(var m=0;m<mk.length;m++){
                            var dataMK = mk[m];
                            var asalSmt = (semester!=dataMK.Semester) ? '('+dataMK.Semester+')' : '';
                            var schDisabled = (dataMK.ScheduleID!="") ? '' : '';
                            var schMK = (dataMK.ScheduleID!="") ? 'highlighted' : '';
                            $('#formMataKuliah'+divNum).append('<option value="'+dataMK.CDID+'|'+dataMK.ID+'|'+dataMK.TotalSKS+'" class="'+schMK+'" '+schDisabled+'>Smt '+semester+' '+asalSmt+' - '+dataMK.MKCode+' | '+dataMK.MKNameEng+'</option>');
                        }

                    $('#formMataKuliah'+divNum).append('<option disabled>-------</option>');

                }

                $('#formMataKuliah'+divNum).select2({allowClear: true});
            } else {
                $('#dataMK'+divNum).html('<b>No Course To Offerings</b>')
            }
        });
    }

</script>

<script>
    $(document).ready(function () {

        window.dataSesi = 1;

        $('#TextNoSesi'+dataSesi).html(dataSesi);
        $('.form-filter-jadwal').prop('disabled',true);

        if(SemesterAntara==0){
            loadAcademicYearOnPublish('');
        } else {
            loadAcademicYearOnPublish('SemesterAntara');
        }

        loadSelectOptionConf('#formProgramsCampusID','programs_campus','');
        // loadSelectOptionAllMataKuliahSingle('#formMataKuliah','');
        loadSelectOptionLecturersSingle('#formCoordinator','');
        loadSelectOptionLecturersSingle('#formTeamTeaching','');

        loadSelectOptionClassroom('#formClassroom'+dataSesi,'');
        fillDays('#formDay'+dataSesi,'Eng','');

        loadSelectOptionTimePerCredit('#formTimePerCredit'+dataSesi,'');


        $('#formCoordinator,#formTeamTeaching').select2({allowClear: true});



        $('input[type=radio][name=formCombinedClasses]').change(function () {
            loadformCombinedClasses($(this).val());
        });

        $('#div_formSesiAwal'+dataSesi).datetimepicker({
            pickDate: false,
            pickSeconds : false
        })
            .on('changeDate', function(e) {
            var d = new Date(e.localDate);
            var no = $(this).attr('data-no');
            var TimePerCredit = $('#formTimePerCredit'+no).val();
            var Credit = $('#formCredit'+no).val();
            var totalTime = parseInt(TimePerCredit) * parseInt(Credit);

            var sesiAkhir = moment().hours(d.getHours()).minutes(d.getMinutes()).add(parseInt(totalTime), 'minute').format('HH:mm');

            $('#formSesiAkhir'+no).val(sesiAkhir);
            checkSchedule(no);
        });

    });


    $('#addNewSesi').click(function () {

        var newSesi = true;

        var Classroom = $('#formClassroom'+dataSesi).val(); if(Classroom==''){ newSesi = requiredForm('#s2id_formClassroom'+dataSesi+' a'); }
        var Credit = $('#formCredit'+dataSesi).val(); if(Credit==''){newSesi = requiredForm('#formCredit'+dataSesi);}
        var TimePerCredit = $('#formTimePerCredit'+dataSesi).val(); if(TimePerCredit==''){newSesi = requiredForm('#formTimePerCredit'+dataSesi);}
        var StartSessions = $('#formSesiAwal'+dataSesi).val(); if(StartSessions==''){newSesi = requiredForm('#formSesiAwal'+dataSesi);}
        var EndSessions = $('#formSesiAkhir'+dataSesi).val(); if(EndSessions==''){newSesi = requiredForm('#formSesiAkhir'+dataSesi);}

        if(newSesi){
            dataSesi = dataSesi + 1;

            $('#subsesi1').removeClass('hide');
            $('#bodyAddSesi').append('<tr class="trNewSesi'+dataSesi+'">' +
                '                <td colspan="3" class="td-center">' +
                '                    <span class="label label-warning span-sesi">--- Sub Sesi '+dataSesi+' ---</span>' +
                '                </td>' +
                '            </tr>' +
                '            <tr class="trNewSesi'+dataSesi+'">' +
                '                <td>Ruang | Hari | Credit</td>' +
                '                <td>:</td>' +
                '                <td>' +
                '                    <div class="row">' +
                '                        <div class="col-xs-5">' +
                '                            <select class="form-control form-jadwal form-classroom" data-id="'+dataSesi+'" id="formClassroom'+dataSesi+'">' +
                '                                <option value=""></option>' +
                '                            </select>' +
                '                        </div>' +
                '                        <div class="col-xs-4">' +
                '                            <select class="form-control form-jadwal form-day" data-id="'+dataSesi+'" id="formDay'+dataSesi+'"></select>' +
                '                        </div>' +
                '                        <div class="col-xs-3">' +
                '                            <input class="form-control form-credit" data-id="'+dataSesi+'" placeholder="Credit" id="formCredit'+dataSesi+'" type="number"/>' +
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
                '                            <select class="form-control form-timepercredit" data-id="'+dataSesi+'" id="formTimePerCredit'+dataSesi+'">' +
                '                                <option></option>' +
                '                                <option></option>' +
                '                            </select>' +
                '                        </div>' +
                '                        <div class="col-xs-4">' +
                '                           <div id="div_formSesiAwal'+dataSesi+'" data-no="'+dataSesi+'" class="input-group">' +
                '                                <input data-format="hh:mm" type="text" id="formSesiAwal'+dataSesi+'" class="form-control form-attd" value="00:00"/>' +
                '                                <span class="add-on input-group-addon">' +
                '                                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>' +
                '                                </span>' +
                '                            </div>' +
                '                        </div>' +
                '                        <div class="col-xs-4">' +
                '                            <input type="text" class="form-control" id="formSesiAkhir'+dataSesi+'" style="color: #333;" readonly />' +
                '                        </div>' +
                '                    </div>' +
                '<div id="alertBentrok'+dataSesi+'"></div>' +
                '                </td>' +
                '            </tr>');

            loadSelectOptionClassroom('#formClassroom'+dataSesi,'');
            fillDays('#formDay'+dataSesi,'Eng','');
            loadSelectOptionTimePerCredit('#formTimePerCredit'+dataSesi,'');

            $('#div_formSesiAwal'+dataSesi).datetimepicker({
                pickDate: false,
                pickSeconds : false
            }).on('changeDate', function(e) {
                var d = new Date(e.localDate);
                var no = $(this).attr('data-no');
                var TimePerCredit = $('#formTimePerCredit'+no).val();
                var Credit = $('#formCredit'+no).val()

                var totalTime = parseInt(TimePerCredit) * parseInt(Credit);

                var sesiAkhir = moment().hours(d.getHours()).minutes(d.getMinutes()).add(parseInt(totalTime), 'minute').format('HH:mm');

                $('#formSesiAkhir'+no).val(sesiAkhir);
                checkSchedule(no);
            });

        } else {
            toastr.warning('Form Sub Sesi '+dataSesi+' Harus Diisi','Warning!');
        }

    });
    
    $('#removeNewSesi').click(function () {
        if(dataSesi>1){
            $('.trNewSesi'+dataSesi).remove();
            dataSesi = dataSesi - 1;
            if(dataSesi==1){
                $('#subsesi1').addClass('hide');
            }
        } else {
            $('#subsesi1').addClass('hide');
            toastr.warning('Belum Ada Sub Sesi','Info');
        }

    });

    $('#addClassRoom').click(function () {
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Classroom</h4>');
        $('#GlobalModal .modal-body').html('<div class="row">' +
            '                            <div class="col-xs-4">' +
            '                                <label>Room</label>' +
            '                                <input type="text" class="form-control" id="formRoom">' +
            '                            </div>' +
            '                            <div class="col-xs-4">' +
            '                                <label>Seat</label>' +
            '                                <input type="number" class="form-control" id="formSeat">' +
            '                            </div>' +
            '                            <div class="col-xs-4">' +
            '                                <label>Seat For Exam</label>' +
            '                                <input type="number" class="form-control" id="formSeatForExam">' +
            '                            </div>' +
            '                        </div>');
        $('#GlobalModal .modal-footer').html('<button type="button" id="btnCloseClassroom" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '<button type="button" class="btn btn-success" id="btnSaveClassroom">Save</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    
    function setSesiAkhir(ID) {
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

    function setGroupClass() {

        var ProgramsCampusID = $('#formProgramsCampusID').val();
        var SemesterID = $('#formSemesterID').val();

        var CombinedClasses = $('input[name=formCombinedClasses]:checked').val();
        var formBaseProdi = $('#formBaseProdi1').val();

        if(formBaseProdi!=null && formBaseProdi!=''){
            if($('#formParalel').is(':checked') && $('#ClassgroupParalel').val()==null){

                var url = base_url_js+'api/__getClassGroupParalel';
                var ProdiCode = (CombinedClasses==0) ? formBaseProdi.split('.')[1] : 'ZO';

                var data = {
                    ProgramsCampusID : ProgramsCampusID,
                    SemesterID : SemesterID,
                    ProdiCode : ProdiCode,
                    IsSemesterAntara : SemesterAntara
                };
                var token = jwt_encode(data,'UAP)(*');
                $('#ClassgroupParalel').empty();
                var alphabet = genCharArray();
                $.post(url,{token:token},function (jsonResult) {

                    $('#ClassgroupParalel').append('<option value="" disabled selected>-- New Group --</option>');
                    if(jsonResult.length>0){
                        for(var s=0;s<jsonResult.length;s++){
                            var d = jsonResult[s];
                            var gr =  d.ProdiCode +'-'+d.Numeric;
                            var n = alphabet[d.alp];
                            $('#ClassgroupParalel').append('<option value="'+gr+'-'+n+'">'+gr+'</option>');
                        }
                    }
                });

                setGroupClassFinal('','A');

            } else if($('#formParalel').is(':checked') && $('#ClassgroupParalel').val()!=null){
                // var alphabet = genCharArray();
                var group = $('#ClassgroupParalel').val();

                setGroupClassFinal(group,'');
            } else {
                setGroupClassFinal('','');
            }
        }

    }

    function setGroupClassFinal(group,alp) {

        var value = $('input[type=radio][name=formCombinedClasses]:checked').val();

        var CombinedClasses = $('input[name=formCombinedClasses]:checked').val();
        var formBaseProdi = $('#formBaseProdi1').val();

        var ProgramsCampusID = $('#formProgramsCampusID').val();
        var SemesterID = $('#formSemesterID').val();
        var ProdiCode = (CombinedClasses==0) ? formBaseProdi.split('.')[1] : 'ZO';

        if(value==1){
            var data = {
                ProgramsCampusID : ProgramsCampusID,
                SemesterID : SemesterID,
                ProdiCode : ProdiCode,
                IsSemesterAntara : SemesterAntara
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__getClassGroup';
            $.post(url,{token:token},function (result) {
                // console.log(result);

                var g = (alp!='') ? result.Group+'-'+alp : result.Group;
                var gg = (group=='') ? g : group;

                $('#viewClassGroup').html(gg);
                $('#formClassGroup').val(gg);
            });
        }
        else {
            if(formBaseProdi!=null){

                var ProdiCode = (CombinedClasses==0) ? formBaseProdi.split('.')[1] : 'ZO';

                var data = {
                    ProgramsCampusID : ProgramsCampusID,
                    SemesterID : SemesterID,
                    ProdiCode : ProdiCode,
                    IsSemesterAntara : SemesterAntara
                };
                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api/__getClassGroup';
                $.post(url,{token:token},function (result) {

                    var g = (alp!='') ? result.Group+'-'+alp : result.Group;
                    var gg = (group=='') ? g : group;

                    $('#viewClassGroup').html(gg);
                    $('#formClassGroup').val(gg);
                });
            }
        }
    }

    function loadAcademicYearOnPublish(smt) {
        var url = base_url_js+"api/__getAcademicYearOnPublish";
        $.getJSON(url,{smt:smt},function (data_json) {
            if(smt=='SemesterAntara'){
                $('#formSemesterID').val(data_json.SemesterID);
            } else {
                $('#formSemesterID').val(data_json.ID);
            }

            $('#semesterName').html(data_json.Year+''+data_json.Code+' | '+data_json.Name);

        });
    }

    function loadformCombinedClasses(value) {


        if(value==1){
            $('#btnControlProdi').removeClass('hide');
            $('#divGabugan').removeClass('hide');
            getCourseOfferings('','readgabungan');

        } else {
            dataProdi = 1;
            $('#btnControlProdi').addClass('hide');
            $('#divGabugan').addClass('hide');
            $('.tr-prodi').remove();

            var Prodi  = $('#formBaseProdi1').val();
            if(Prodi!=null){
                var ProdiID = Prodi.split('.');
                getCourseOfferings(ProdiID[0],1);
            }
        }

        setGroupClass();

    }

    function loadformTeamTeaching(value,element_dosen) {
        if(value==1){
            $(element_dosen).prop('disabled',false);
        } else {
            $(element_dosen).select2("val", null);
            $(element_dosen).prop('disabled',true);
        }
    }

    function resetFormSetSchedule() {
        $('input[type=radio][name=formCombinedClasses][value=0]').prop('checked',true);
        dataProdi = 1;
        $('#bodyAddProdi').remove();
        $('#btnControlProdi').addClass('hide');
        $('#divGabugan').addClass('hide');

        $('#formBaseProdi1').val('');
        $('#formCoordinator').select2("val","");
        $('#formMataKuliah1').select2("val","");

        $('#viewClassGroup').text('-');
        $('#ClassgroupParalel').empty();
        $('#formParalel').prop('checked',false);
        $('#formClassGroup,#formCredit1,#formSesiAwal1,#formSesiAkhir1,#textTotalSKSMK').val('');
        $('#formTeamTeaching').prop('disabled',true);

        dataSesi=1;
        $('#subsesi1').addClass('hide');
        $('#bodyAddSesi').html('');

        // Group
        $('#formClassGroupCadangan').val('');
        $('#alertClassGroup').html('');
    }

    function requiredForm(element) {
        $(element).css('border','1px solid red');
        setTimeout(function () {
            $(element).css('border','1px solid #cccccc');
        },5000);
        return false;
    }

    function checkSchedule(ID) {
        var SemesterID = $('#formSemesterID').val();
        var ProgramsCampusID = $('#formProgramsCampusID').val();

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
                    IsSemesterAntara : ''+SemesterAntara,
                    ClassroomID : ClassroomID,
                    DayID : DayID,
                    StartSessions : StartSessions,
                    EndSessions : EndSessions
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__checkSchedule';
            $.post(url,{token:token},function (json_result) {
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
</script>


<!-- Save Scedule -->
<script>
    $('#btnSavejadwal').click(function () {

        var process = [];

        var SemesterID = $('#formSemesterID').val();
        var ProgramsCampusID = $('#formProgramsCampusID').val();
        // var CombinedClasses = $('input[name=formCombinedClasses]:checked').val();
        var CombinedClasses = (dataProdi>1) ? '1' : '0';

        var schedule_details_course=[];

        for(var p=1;p<=dataProdi;p++){

            var formBaseProdi = $('#formBaseProdi'+p).val();
            var formMataKuliah = $('#formMataKuliah'+p).val();

            if(formBaseProdi!=null && formBaseProdi!='' &&
                formMataKuliah!=null && formMataKuliah!=''){

                var cdArr = {
                    ScheduleID : 0,
                    ProdiID : formBaseProdi.split('.')[0],
                    CDID : formMataKuliah.split('|')[0],
                    MKID : formMataKuliah.split('|')[1]
                };
                schedule_details_course.push(cdArr);

            } else {
                requiredForm('#s2id_formBaseProdi'+p+' a');
                requiredForm('#s2id_formMataKuliah'+p+' a');
                process.push(0);
            }
        }

        // var ClassGroup = $('#formClassGroup').val();
        var ClassGroup = $('#formClassGroupCadangan').val();

        // console.log(ClassGroup);

        var Coordinator = $('#formCoordinator').val();

        var TeamTeaching = $('input[name=formteamTeaching]:checked').val();
        var UpdateBy = sessionNIP;
        var UpdateAt = dateTimeNow();


        var teamTeachingArray = [];
        if(TeamTeaching==1){
            var formTeamTeaching = $('#formTeamTeaching').val();

            if(formTeamTeaching!=null){
                for(var t=0;t<formTeamTeaching.length;t++){
                    var dt = {
                        ScheduleID : 0,
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
        var totalCredit = 0;
        for(var i=1;i<=dataSesi;i++){
            var ClassroomID = $('#formClassroom'+i).val();
            var DayID = $('#formDay'+i).val();
            var Credit = $('#formCredit'+i).val(); if(Credit==''){process.push(0); requiredForm('#formCredit'+dataSesi);}
            var TimePerCredit = $('#formTimePerCredit'+i).val();
            var StartSessions = $('#formSesiAwal'+i).val(); if(StartSessions==''){process.push(0); requiredForm('#formSesiAwal'+dataSesi);}
            var EndSessions = $('#formSesiAkhir'+i).val();if(EndSessions==''){process.push(0); requiredForm('#formSesiAkhir'+dataSesi);}

            totalCredit = parseInt(totalCredit) + parseInt(Credit);
            var arrSesi = {
                ScheduleID : 0,
                ClassroomID : ClassroomID,
                Credit : Credit,
                DayID : DayID,
                TimePerCredit : TimePerCredit,
                StartSessions : StartSessions,
                EndSessions : EndSessions
            };

            dataScheduleDetailsArray.push(arrSesi);
        }

        if(CombinedClasses==0 && textTotalSKSMK!=totalCredit){
            process.push(0);
        }


        if($.inArray(0,process)==-1){

            loading_button('#btnSavejadwal');
            $('#removeNewSesi,#addNewSesi').prop('disabled',true);

            // var dataClassGroup = ClassGroup.split('-');
            // var typeParalel = ($('#formParalel').is(':checked')) ? '1' : '0';
            var SubSesi = (dataSesi>1) ? '1' : '0';
            var data = {
                action : 'add',
                ID : '',
                formData :
                    {
                        schedule : {
                            SemesterID : SemesterID,
                            ProgramsCampusID : ProgramsCampusID,
                            CombinedClasses : CombinedClasses,
                            ClassGroup : ClassGroup,
                            Coordinator : Coordinator,
                            TeamTeaching : TeamTeaching,
                            SubSesi : SubSesi,
                            TotalAssigment : 5,
                            IsSemesterAntara : ''+SemesterAntara,
                            UpdateBy : UpdateBy,
                            UpdateAt : UpdateAt
                        },
                        schedule_details : dataScheduleDetailsArray,
                        schedule_details_course : schedule_details_course,
                        schedule_team_teaching : teamTeachingArray

                    }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudSchedule';
            $.post(url,{token:token},function (result) {
                resetFormSetSchedule();
                toastr.success('Schedule Saved','Success!!');
                setTimeout(function () {
                    $('#btnSavejadwal').html('Save');
                    $('#btnSavejadwal,#removeNewSesi,#addNewSesi').prop('disabled',false);
                },1000);
            });

        } else {
            toastr.error('Form Required','Error!');
        }



    });
</script>

<!-- CRUD Time Per Credit-->
<script>
    $('#addTimePerCredit').click(function () {

        var url = base_url_js+'api/__crudTimePerCredit';
        var token = jwt_encode({action:'read'},'UAP)(*');
        $.post(url,{token:token},function (data_json) {
            if(data_json.length>0){
                $('#NotificationModal .modal-body').html('' +
                    '<div class="form-group">' +
                    '<div class="row">' +
                    '<div class="col-md-8">' +
                    '<div class="input-group">' +
                    '      <input type="number" class="form-control" id="formTime">' +
                    '      <span class="input-group-btn">' +
                    '        <button class="btn btn-success" id="btnAddTimePerCredit" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add</button>' +
                    '      </span>' +
                    '    </div>' +
                    '</div>' +
                    '<div class="col-md-4">' +
                    '<button class="btn btn-default" style="float: right;" data-dismiss="modal">Close</button>' +
                    '</div></div> </div> ' +
                    '<table class="table table-bordered">' +
                                                    '    <thead>' +
                                                    '    <tr>' +
                                                    '        <th class="th-center">Time</th>' +
                                                    '        <th class="th-center" style="width: 110px;">Action</th>' +
                                                    '    </tr>' +
                                                    '    </thead>' +
                                                    '    <tbody id="rowTime"></tbody>' +
                                                    '</table>');
                for(var i=0;i<data_json.length;i++){
                    $('#rowTime').append('<tr id="tr'+data_json[i].ID+'">' +
                        '<td class="td-center">'+data_json[i].Time+' Minute</td>' +
                        '<td class="td-center">' +
                        '<button class="btn btn-default btn-default-danger btn-delete-timepercredit" data-id="'+data_json[i].ID+'">Delete</button>' +
                        '</td>' +
                        '</tr>');
                };


                $('#NotificationModal').modal({
                    'show' : true
                });
            }
        });


    });
</script>