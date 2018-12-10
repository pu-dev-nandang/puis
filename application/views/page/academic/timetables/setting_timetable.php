
<style>
    .span-sesi {
        font-size: 1.1em;
        font-weight: bold;
    }
    select.fm-prodi, select#formProgramsCampusID {
        max-width: 300px;
    }

    .table thead>tr>th, .table tbody>tr>th, .table tfoot>tr>th, .table thead>tr>td, .table tbody>tr>td, .table tfoot>tr>td {
        border-top: none;
    }
    .table tbody+tbody {
        border-top: 1px solid #ddd;
    }
</style>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <table class="table">
            <tr>
                <td style="width: 20%;">Academic Year</td>
                <td style="width: 1%;">:</td>
                <td>
                    <strong id="semesterName">-</strong>
                    <input id="formSemesterID" class="hide" type="hidden" readonly/>
                </td>
            </tr>
            <tr>
                <td>Program</td>
                <td>:</td>
                <td>
                    <select class="form-control" id="formProgramsCampusID"></select>
                </td>
            </tr>
            <tr>
                <td>Class Combined ?</td>
                <td>:</td>
                <td>
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="radio-inline">
                                <input type="radio" name="formCombinedClasses" class="form-jadwal" value="0" checked> No
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="formCombinedClasses" class="form-jadwal" value="1"> Yes
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
                <td>Programme Study</td>
                <td>:</td>
                <td>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control fm-prodi" data-id="1" id="formBaseProdi1">
                                <option value="" selected disabled>--- Select Programme Study ---</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" id="formGroupProdi1" style="max-width: 200px;" disabled><option selected disabled>-- Select Group Prodi --</option></select>
                            <input class="hide" readonly value="0" id="viewGroupProdi1">
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="">
                <td>Course</td>
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
                <td>Class Group</td>
                <td>:</td>
                <td>
                    <span class="btn-default-primary hide" id="viewClassGroup" style="padding-left: 5px;padding-right: 5px;"> - </span>
                    <input type="hide" class="hide" id="formClassGroup" />
                    <input type="text" class="form-control" style="max-width: 150px;" onkeyup="this.value = this.value.toUpperCase();" id="formClassGroupCadangan" />
                    <div id="alertClassGroup"></div>
                </td>
            </tr>

            <tr>
                <td>Coordinator</td>
                <td>:</td>
                <td>
                    <select class="select2-select-00 full-width-fix form-jadwal"
                            size="5" id="formCoordinator">
                        <option value=""></option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Team Teaching ?</td>
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
                    <span class="label label-warning span-sesi">--- Sub Sesi 1 ---</span>
                </td>
            </tr>
            <tr class="trNewSesi1">
                <td>Room | Day | Credit</td>
                <td>:</td>
                <td>
                    <div class="row">
                        <div class="col-xs-5">
                            <select class="form-control form-jadwal form-classroom formtime" data-id="1" id="formClassroom1">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            <select class="form-control form-jadwal form-day formtime" data-id="1" id="formDay1"></select>
                        </div>
                        <div class="col-xs-3">
                            <input class="form-control form-credit formtime" data-id="1" placeholder="Credit" id="formCredit1" type="number"/>
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
                            <select class="form-control form-timepercredit formtime" data-id="1" id="formTimePerCredit1"></select>
                            <a href="javascript:void(0)" id="addTimePerCredit" style="font-size:10px;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah <i>Time Per Credit</i></a>
                        </div>
                        <div class="col-xs-4">
                            <div id="div_formSesiAwal1" data-no="1" class="input-group">
                                <input data-format="hh:mm" type="text" id="formSesiAwal1" class="form-control form-attd formtime" value="00:00"/>
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

        window.SemesterAntara = 0;
        window.dataSesi = 1;
        window.dataProdi = 1;

        loadAcademicYearOnPublish('');
        loadSelectOptionConf('#formProgramsCampusID','programs_campus','');
        loadSelectOptionLecturersSingle('#formCoordinator','');
        loadSelectOptionLecturersSingle('#formTeamTeaching','');

        loadSelectOptionBaseProdi('#formBaseProdi'+dataProdi);

        loadSelectOptionClassroom('#formClassroom'+dataSesi,'');
        fillDays('#formDay'+dataSesi,'Eng','');

        loadSelectOptionTimePerCredit('#formTimePerCredit'+dataSesi,'');
        $('#div_formSesiAwal'+dataSesi).datetimepicker({
            pickDate: false,
            pickSeconds : false
        })
            .on('changeDate', function(e) {

                var no = $(this).attr('data-no');
                setSesiAkhir(no);
                checkSchedule(no);
            });
    });

    $(document).on('change','.formtime',function () {
        var ID = $(this).attr('data-id');
        setSesiAkhir(ID);
        checkSchedule(ID);
    });

    $(document).on('change','#formMataKuliah1',function () {
        var data = $(this).val();
        if(data!=null && data!=''){
            // data.split('|');
            // console.log(data);
            // console.log(data.split('|')[2]);
            $('#textTotalSKSMK').val(data.split('|')[2]);
            $('#viewMaksCredit').html(data.split('|')[2]);

            // var cr = $('#formCredit1').val();
            if(dataSesi==1){
                $('#formCredit1').val(data.split('|')[2]);
            }
        }
    });

    $('input[type=radio][name=formCombinedClasses]').change(function () {
        loadformCombinedClasses($(this).val());
    });

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

    function resetFormSetSchedule() {
        $('input[type=radio][name=formCombinedClasses][value=0]').prop('checked',true);
        dataProdi = 1;
        $('#bodyAddProdi').remove();
        $('#btnControlProdi').addClass('hide');
        $('#divGabugan').addClass('hide');

        $('#formBaseProdi1').val('');
        $('#formGroupProdi1').empty();
        $('#formGroupProdi1').append('<option selected disabled>-- Select Group Prodi --</option>');
        $('#formCoordinator').select2("val","");
        $('#formMataKuliah1').select2("val","");

        $('#viewClassGroup').text('-');
        $('#ClassgroupParalel').empty();
        $('#formParalel').prop('checked',false);
        $('#formClassGroup,#formCredit1,#formSesiAwal1,#formSesiAkhir1,#textTotalSKSMK').val('');
        $('#formTeamTeaching').prop('disabled',true);
        $('#formTeamTeaching').select2("val", null);
        $('input[type=radio][name=formteamTeaching][value=0]').prop('checked',true);

        dataSesi=1;
        $('#subsesi1').addClass('hide');
        $('#bodyAddSesi').html('');

        // Group
        $('#formClassGroupCadangan').val('');
        $('#alertClassGroup').html('');

        $('#textTotalSKSMK').val();
        $('#viewMaksCredit').text('0');
    }

    function requiredForm(element) {
        $(element).css('border','1px solid red');
        setTimeout(function () {
            $(element).css('border','1px solid #cccccc');
        },5000);
        return false;
    }
</script>


<!-- GABUNGAN -->
<script>

    $('#btnAddProdi').click(function () {
        dataProdi += 1;
        $('#bodyAddProdi').append('<tr class="tr-prodi tr-p'+dataProdi+'">' +
            '                <td>Program Studi</td>' +
            '                <td>:</td>' +
            '                <td>' +
            '<div class="row">' +
            '<div class="col-md-6"><select class="form-control fm-prodi" data-id="'+dataProdi+'" id="formBaseProdi'+dataProdi+'"><option value="" selected disabled>--- Select Programme Study ---</option></select></div>' +
            '<div class="col-md-6">' +
            '   <select class="form-control" id="formGroupProdi'+dataProdi+'" style="max-width: 200px;" disabled><option selected disabled>-- Select Group Prodi --</option></select>' +
            '   <input class="hide" readonly value="0" id="viewGroupProdi'+dataProdi+'">' +
            '</div>' +
            '</div>' +
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

    $(document).on('change','.fm-prodi',function () {
        var divNum = $(this).attr('data-id');
        var Prodi = $(this).val();
        if(Prodi!=''){

            var ProdiID = Prodi.split('.')[0];
            getCourseOfferings(ProdiID,divNum);
            loadProdiGroup(ProdiID,divNum);

            if(dataProdi==1){

                $('#viewClassGroup').text('-');
                $('#ClassgroupParalel').empty();
                $('#formParalel').prop('checked',false);

                setGroupClass();
            }
        }

    });

    function loadProdiGroup(ProdiID,divNum) {
        var data = {
            action : 'readProdiGroup',
            ProdiID : ProdiID
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudSchedule';
        $('#formGroupProdi'+divNum).empty();
        $('#formGroupProdi'+divNum).append('<option selected disabled>-- Select Group Prodi --</option>');
        $.post(url,{token:token},function (jsonResult) {
            if(jsonResult.length>0){
                $('#formGroupProdi'+divNum).prop('disabled',false);
                $('#viewGroupProdi'+divNum).val(1);
                for(var i=0; i<jsonResult.length;i++){
                    var d = jsonResult[i];
                    $('#formGroupProdi'+divNum).append('<option value="'+d.ID+'">'+d.Code+'</option>');
                }
            } else {
                $('#formGroupProdi'+divNum).prop('disabled',true);
                $('#viewGroupProdi'+divNum).val(0);
            }
        });
    }
</script>

<!-- CLASS GROUP -->
<script>
    // Cek Jadwal
    $(document).on('keyup','#formClassGroupCadangan',function () {
        cekGroupCuy();
    });

    $(document).on('blur','#formClassGroupCadangan',function () {
        cekGroupCuy();
    });
    function cekGroupCuy() {
        var g = $('#formClassGroupCadangan').val();
        if(g!='' && g!=null){
            var data = {
                action : 'checkGroup',
                Group : g
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudSchedule';
            // $('#alertClassGroup').html('');
            $.post(url,{token:token},function (result) {

                $('#btnSavejadwal').prop('disabled',true);

                if(result.length>0){
                    $('#btnSavejadwal').prop('disabled',true);
                    $('#alertClassGroup').html('<span style="color: red;"><i class="fa fa-times-circle"></i> | Group Exist</span>');
                } else {
                    $('#btnSavejadwal').prop('disabled',false);
                    $('#alertClassGroup').html('<span style="color: green;"><i class="fa fa-check-circle"></i> | Group Can Use</span>');
                }
            });
        } else {
            $('#btnSavejadwal').prop('disabled',true);
            $('#alertClassGroup').html('');
        }
    }
</script>

<!-- TEAM TEACHING -->
<script>
    $('input[type=radio][fm=dtt-form]').change(function () {
        loadformTeamTeaching($(this).val(),'#formTeamTeaching');
    });

    $(document).on('click','#btnAddTimePerCredit',function () {
        var Time = $('#formTime').val();

        if(Time!=''){
            $('#formTime').prop('disabled',true);
            loading_buttonSm('#btnAddTimePerCredit');
            var url = base_url_js+'api/__crudTimePerCredit';
            var data = {
                action : 'add',
                formData : {
                    Time : Time,
                    UpdateBy : sessionNIP,
                    UpdateAt : dateTimeNow()
                }
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (json_result) {
                $('#formTime,#btnAddTimePerCredit').prop('disabled',false);
                $('#btnAddTimePerCredit').html('<i class="fa fa-plus-circle" aria-hidden="true"></i> Add');

                setTimeout(function () {
                    if(json_result.inserID==0){
                        toastr.warning('Data Exist','Warning!');
                    } else {

                        for(var d=1;d<=parseInt(dataSesi);d++){
                            var selected = $('#formTimePerCredit'+d).val();
                            loadSelectOptionTimePerCredit('#formTimePerCredit'+d,selected);
                        }


                        $('#formTime').val('');
                        $('#rowTime').append('<tr id="tr'+json_result.inserID+'">' +
                            '<td class="td-center">'+Time+' Minute</td>' +
                            '<td class="td-center">' +
                            '<button class="btn btn-default btn-default-danger btn-delete-timepercredit" data-id="'+json_result.inserID+'">Delete</button>' +
                            '</td>' +
                            '</tr>');
                        toastr.success('Data Saved','Success!');
                    }
                },1000);
            });

        } else {
            $('#formTime').css('border','1px solid red');
            setTimeout(function () {
                $('#formTime').css('border','1px solid #ccc');
            },5000);
        }
    });
    $(document).on('click','.btn-delete-timepercredit',function () {
        var ID = $(this).attr('data-id');
        var token = jwt_encode({action:'delete',ID:ID},'UAP)(*');
        var url = base_url_js+'api/__crudTimePerCredit';

        $.post(url,{token:token},function (json_result) {
            if(json_result.inserID==0){
                toastr.warning('Data tidak dapat di hapus','Warning!');
            } else {
                for(var d=1;d<=parseInt(dataSesi);d++){
                    var selected = $('#formTimePerCredit'+d).val();
                    loadSelectOptionTimePerCredit('#formTimePerCredit'+d,selected);
                }
                $('#tr'+ID).remove();
                toastr.success('Data deleted','Success!');
            }
        });

    });

    function loadformTeamTeaching(value,element_dosen) {
        if(value==1){
            $(element_dosen).prop('disabled',false);
        } else {
            $(element_dosen).select2("val", null);
            $(element_dosen).prop('disabled',true);
        }
    }

</script>

<!-- NEW SESI -->
<script>
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
</script>

<!-- TIME PER CREDIT -->
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

<!-- SAVE SETTING -->
<script>
    $('#btnSavejadwal').click(function () {

        var process = [];

        var SemesterID = $('#formSemesterID').val();
        var ProgramsCampusID = $('#formProgramsCampusID').val();
        var CombinedClasses = (dataProdi>1) ? '1' : '0';

        var schedule_details_course=[];
        var arrCDID = [];

        for(var p=1;p<=dataProdi;p++){
            var formBaseProdi = $('#formBaseProdi'+p).val();
            var formMataKuliah = $('#formMataKuliah'+p).val();

            var viewGroupProdi = parseInt($('#viewGroupProdi'+p).val());
            var formGroupProdi = $('#formGroupProdi'+p).val();

            // Jika Prodi memiliki Group Prodi
            if(viewGroupProdi==1){
                if(formBaseProdi!=null && formBaseProdi!='' &&
                    formMataKuliah!=null && formMataKuliah!='' &&
                    formGroupProdi!=null && formGroupProdi!=''){

                    var CDID = formMataKuliah.split('|')[0];

                    if($.inArray(CDID,arrCDID)==-1){
                        var cdArr = {
                            ScheduleID : 0,
                            ProdiID : formBaseProdi.split('.')[0],
                            ProdiGroupID : formGroupProdi,
                            CDID : CDID,
                            MKID : formMataKuliah.split('|')[1]
                        };
                        schedule_details_course.push(cdArr);
                        arrCDID.push(CDID);
                    } else {
                        toastr.error('Please check course, course can not same','Error');
                        process.push(0);
                    }

                } else {
                    requiredForm('#formBaseProdi'+p);
                    requiredForm('#formGroupProdi'+p);
                    requiredForm('#s2id_formMataKuliah'+p+' a');
                    process.push(0);
                }
            }
            // Jika Prodi TIDAK memiliki Group Prodi
            else {
                if(formBaseProdi!=null && formBaseProdi!='' &&
                    formMataKuliah!=null && formMataKuliah!=''){

                    var CDID = formMataKuliah.split('|')[0];

                    if($.inArray(CDID,arrCDID)==-1){
                        var cdArr = {
                            ScheduleID : 0,
                            ProdiID : formBaseProdi.split('.')[0],
                            CDID : CDID,
                            MKID : formMataKuliah.split('|')[1]
                        };
                        schedule_details_course.push(cdArr);
                        arrCDID.push(CDID);
                    } else {
                        toastr.error('Please check course, course can not same','Error');
                        process.push(0);
                    }

                }
                else {
                    requiredForm('#formBaseProdi'+p);
                    requiredForm('#s2id_formMataKuliah'+p+' a');
                    process.push(0);
                }
            }


        }

        var ClassGroup = $('#formClassGroupCadangan').val();
        if(ClassGroup=='' || ClassGroup==null){
            requiredForm('#formClassGroupCadangan');
                process.push(0);
        }

        var Coordinator = $('#formCoordinator').val();
        // if(Coordinator=='' || Coordinator==null){
        //     requiredForm('#s2id_formCoordinator a');
        //         process.push(0);
        // }

        var TeamTeaching = $('input[name=formteamTeaching]:checked').val();
        var UpdateBy = sessionNIP;
        var UpdateAt = dateTimeNow();

        var teamTeachingArray = [];

        if(TeamTeaching==1){
            var formTeamTeaching = $('#formTeamTeaching').val();
            if(formTeamTeaching!=null){
                for(var t=0;t<formTeamTeaching.length;t++){
                    if(Coordinator!=formTeamTeaching[t]){
                        var dt = {
                            ScheduleID : 0,
                            NIP :  formTeamTeaching[t],
                            Status : '0'
                        };
                        teamTeachingArray.push(dt);
                    } else {
                        toastr.error('Teamteching and Coordinator can not same NIP/Lecturer','Error');
                        process.push(0);
                    }
                }
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

        // if(textTotalSKSMK<totalCredit){
        //     process.push(0);
        //     toastr.error('Credit must be less than equal '+textTotalSKSMK,'Error');
        // }

        if($.inArray(0,process)==-1){

            loading_button('#btnSavejadwal');
            $('#removeNewSesi,#addNewSesi').prop('disabled',true);
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
                },500);
            });


        } else {
            toastr.info('Form Required','Info');
        }


    });
</script>