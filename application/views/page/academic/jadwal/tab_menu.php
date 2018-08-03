
<style>
    .tab-right {
        float: right !important;
    }

    .toggle-group .btn-default {
        z-index: 1000 !important;
    }
    .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
    .toggle.ios .toggle-handle { border-radius: 20px; }
</style>


<div class="row" style="margin-top: 30px;">

    <div class="col-md-4">
        <div class="">
            <label>Semester Antara</label>
            <input type="checkbox" id="formSemesterAntara" data-toggle="toggle" data-style="ios"/>
        </div>
    </div>
    <div class="col-md-8" style="text-align: right;">
        <div class="btn-group" role="group" aria-label="...">

            <button data-page="jadwal" type="button" class="btn btn-primary btn-action
                        control-jadwal"><i class="fa fa-calendar right-margin" aria-hidden="true"></i> Timetables</button>

            <button data-page="ruangan" type="button" class="btn btn-default btn-default-primary btn-action
                        control-jadwal"><i class="fa fa-window-restore right-margin" aria-hidden="true"></i> Room Timetables (Comming Soon)</button>
        </div>
        |
        <button data-page="penawaran_mk" type="button" class="btn btn-default btn-default-primary btn-action control-jadwal">
            <i class="fa fa-exchange right-margin" aria-hidden="true"></i> Course Offerings
        </button>
        <button data-page="inputjadwal" type="button" class="btn btn-default btn-default-primary btn-action control-jadwal">
            <i class="fa fa-pencil right-margin" aria-hidden="true"></i> Set Timetables
        </button>
    </div>

</div>


<div class="row">
    <div class="col-md-12">
        <hr/>
        <div id="dataPage"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadPage('jadwal','');
        $('input[type=checkbox][data-toggle=toggle]').bootstrapToggle();

        checkSemesterAntara();
        window.SemesterAntara = 0;
        window.PageNow = 'jadwal';
        window.PageScdNow = '';
    });

    $('#formSemesterAntara').change(function () {

        if($('#formSemesterAntara').is(':checked')){
            SemesterAntara = 1;
        } else {
            SemesterAntara = 0;
        }

        if(PageNow=='inputjadwal'){
            if(SemesterAntara==0){
                loadAcademicYearOnPublish('');
            } else {
                loadAcademicYearOnPublish('SemesterAntara');
            }
            resetFormSetSchedule();
        } else if(PageNow=='penawaran_mk'){
            resetPenawaranMK();
        } else if(PageNow=='jadwal'){
            loadPage('jadwal','');
        }


        
        // Reset Penawaran MK
    });


    $(document).on('click','.btn-action',function () {
        var page = $(this).attr('data-page');
        var ScheduleID = (page=='editjadwal') ? $(this).attr('data-id') : '';
        PageNow = page;
        PageScdNow = ScheduleID;

        if(page!='editjadwal'){
            $('.btn-action').removeClass('btn-primary');
            $('.btn-action').addClass('btn-default btn-default-primary');

            $('button[data-page='+page+']').removeClass('btn-default btn-default-primary');
            $('button[data-page='+page+']').addClass('btn-primary');
        }



        loadPage(page,ScheduleID);
    });

    $(document).on('click','.btnDetailStudents',function () {

        var token = $(this).attr('data-std');
        var dataArr = jwt_decode(token,'UAP)(*');
        var dataHtml = '<h4>Students not yet</h4>';
        if(dataArr.Students.length>0){
            dataHtml = '<table class="table table-bordered">' +
                '    <thead>' +
                '    <tr style="background: #005975; color: #ffffff;">' +
                '        <th style="width: 20%;text-align: center;">NPM</th>' +
                '        <th style="text-align: center;">Name</th>' +
                '    </tr>' +
                '    </thead>' +
                '    <tbody id="dataMHS"></tbody>' +
                '</table>' +
                '<div style="text-align: right;"><a href="'+base_url_js+'save2pdf/listStudentsFromCourse?token='+token+'" target="_blank" class="btn btn-sm btn-info">Download Students</a></div>';
        }

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+dataArr.Group+' - '+dataArr.Coordinator+'</h4>');
        $('#GlobalModal .modal-body').html(dataHtml);
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        for(var m=0;m<dataArr.Students.length;m++){
            var d = dataArr.Students[m];
            $('#dataMHS').append('<tr>' +
                '<td style="text-align: center;">'+d.NPM+'</td>' +
                '<td><b>'+d.Name+'</b></td>' +
                '</tr>');
        }

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    function resetPenawaranMK() {
        $('#formSemester').val('');
        $('#box1View,#box1Storage,#box2View,#box2Storage').empty();
        $('#OfferingDiv').addClass('hide');
        $('#btnAnother').html('');
        $('#dataOfferings').empty();
    }

    function checkSemesterAntara() {
        var url = base_url_js+'api/__crudTahunAkademik';
        var token = jwt_encode({action:'checkSemesterAntara'},'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {
            if(jsonResult.length>0){
                $('#formSemesterAntara').prop('disabled',false);
            } else {
                $('#formSemesterAntara').prop('disabled',true);
            }
        });
    }

    function loadPage(page,ScheduleID) {
        loading_page('#dataPage');
        var data = {
            page : page,
            ScheduleID : ScheduleID
        };

        var token = jwt_encode(data,"UAP)(*");
        var url = base_url_js+'academic/__setPageJadwal';
        $.post(url,{token:token},function (page) {
            setTimeout(function () {
                $('#dataPage').html(page);
            },100);
        });
    }
</script>

<script>
    // Untuk Page Jadwal
    $(document).on('change','#filterSemester',function () {
        var Semester = $('#filterSemester').val();
        var SemesterID = (Semester!='' && Semester!= null) ? Semester.split('.')[0] : '';

        console.log(SemesterID);

        $('#selectSemesterSc').html('<select class="form-control" id="filterSemesterSchedule"></select>');
        // $('#filterSemesterSchedule').empty();
        $('#filterSemesterSchedule').append('<option value="">-- All Semester --</option>' +
            '                <option disabled>------------</option>');
        loadSelectOPtionAllSemester('#filterSemesterSchedule','',SemesterID,SemesterAntara);
        filterSchedule();

    });

    $(document).on('change','#filterProgramCampus,#filterBaseProdi,#filterCombine,#filterSemesterSchedule,#filterDay',function () {
        filterSchedule();
    });

    $(document).on('change','input[type=checkbox][class=filterDay]',function () {
        var v = $(this).val();

        if(v==0){
            $('input[type=checkbox][class=filterDay]').prop('checked',false);
            $(this).prop('checked',true);
            checkedDay = [];
        }
        else {

            if($('input[type=checkbox][value='+v+']').is(':checked')){
                checkedDay.push($(this).val());
            } else {
                checkedDay = $.grep(checkedDay, function(value) {
                    return value != v;
                });
            }


            $('input[type=checkbox][value=0]').prop('checked',false);
            // $(this).prop('checked',true);
        }

        if(checkedDay.length==0){
            $('input[type=checkbox][value=0]').prop('checked',true);
            $('.widget-schedule').removeClass('hide');
        }
        else {
            $('.widget-schedule').addClass('hide');
            if(checkedDay.length>0){
                for(var i=0;i<checkedDay.length;i++){
                    $('#dayWidget'+checkedDay[i]).removeClass('hide');
                }
            }
        }

    });
</script>

<!--Penawaran MK-->
<script>
    $(document).on('click','.btnSmtAnother-cl',function () {

        var tg = $(this).attr('data-tg');

        var id = $(this).attr('data-id');
        var dataCourse = $('#dataMK'+id).val();

        var dataJSON = JSON.parse(dataCourse);

        if(tg==1){
            $(this).addClass('btn-default btn-default-warning');
            $(this).removeClass('btn-warning');
            $(this).attr('data-tg',0);

            for(var i=0;i<dataJSON.DetailSemester.length;i++){
                var Courses = dataJSON.DetailSemester[i];


                $('#box1View option[value='+Courses.CDID+']').remove();
                $('#box2View option[value='+Courses.CDID+']').remove();
            }

        } else {
            $(this).removeClass('btn-default btn-default-warning');
            $(this).addClass('btn-warning');
            $(this).attr('data-tg',1);

            for(var i=0;i<dataJSON.DetailSemester.length;i++){
                var Courses = dataJSON.DetailSemester[i];

                if(Courses.Offering==false){
                    var color = (Courses.StatusMK==1) ? '#ff9800' : 'red';
                    var status = (Courses.StatusMK==1) ? '' : 'disabled';

                    $('#box1View').append('<option value="'+Courses.CDID+'" style="color: '+color+';" '+status+'>Smt '+Courses.Semester+' - '+Courses.MKCode+' | '+Courses.NameMKEng+' (Credit : '+Courses.TotalSKS+')</option>');
                }


            }

        }


    });
</script>

<!-- Input Jadwal -->
<script>

    $(document).on('change','.fm-prodi',function () {
        var divNum = $(this).attr('data-id');
        var Prodi = $(this).val();
        if(Prodi!=''){

            var ProdiID = Prodi.split('.')[0];
            getCourseOfferings(ProdiID,divNum);
            if(dataProdi==1){

                $('#viewClassGroup').text('-');
                $('#ClassgroupParalel').empty();
                $('#formParalel').prop('checked',false);

                setGroupClass();
            }
        }

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

    $(document).on('change','#formParalel',function () {
        if(!$(this).is(':checked')){
            $('#ClassgroupParalel').empty();
        }
        setGroupClass();
    });
    $(document).on('change','#ClassgroupParalel',function () {
        setGroupClass();
    });

    // Onchange Cek kelas Bentrok
    $(document).on('change','.form-classroom,.form-day',function () {
        var ID = $(this).attr('data-id');
        checkSchedule(ID);
    });

    $(document).on('keyup','.form-sesiawal,.form-credit',function () {
        var ID = $(this).attr('data-id');
        setSesiAkhir(ID);
        checkSchedule(ID);
    });

    $(document).on('change','.form-sesiawal,.form-timepercredit,.form-credit',function () {
        var ID = $(this).attr('data-id');
        setSesiAkhir(ID);
        checkSchedule(ID);

    });

    $(document).on('change','input[type=radio][fm=dtt-form]',function () {
        loadformTeamTeaching($(this).val(),'#formTeamTeaching');
    });

    // Untuk Add Class Room
    $(document).on('click','#btnSaveClassroom',function () {

        var process = true;

        var Room = $('#formRoom').val(); process = (Room=='') ? errorInput('#formRoom') : true ;
        var Seat = $('#formSeat').val(); var processSeat = (Seat!='' && $.isNumeric(Seat) && Math.floor(Seat)==Seat) ? true : errorInput('#formSeat') ;
        var SeatForExam = $('#formSeatForExam').val(); var processSeatForExam = (SeatForExam!='' && $.isNumeric(SeatForExam) && Math.floor(SeatForExam)==SeatForExam) ? true : errorInput('#formSeatForExam') ;


        if(Room!='' && processSeat && processSeatForExam){
            $('#formRoom,#formSeat,#formSeatForExam,#btnCloseClassroom').prop('disabled',true);
            loading_button('#btnSaveClassroom');
            loading_page('#viewClassroom');

            var data = {
                action : 'add',
                ID : '',
                formData : {
                    Room : Room,
                    Seat : Seat,
                    SeatForExam : SeatForExam,
                    Status : 0,
                    UpdateBy : sessionNIP,
                    UpdateAt : dateTimeNow()
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+"api/__crudClassroom";

            $.post(url,{token:token},function (data_result) {

                for(var i=1;i<=parseInt(dataSesi);i++){
                    var selected = $('#formClassroom'+i).val();
                    loadSelectOptionClassroom('#formClassroom'+i,selected);
                }

                setTimeout(function () {

                    if(data_result.inserID!=0) {
                        toastr.success('Data tersimpan','Success!');
                        $('#GlobalModal').modal('hide');

                    } else {
                        $('#formRoom,#formSeat,#formSeatForExam,#btnCloseClassroom').prop('disabled',false);
                        $('#btnSaveClassroom').prop('disabled',false).html('Save');
                        toastr.warning('Room is exist','Warning');
                    }
                },500);

            });
        } else {
            toastr.error('Form Required','Error!');
        }
    });
</script>

<!-- Input Jadwal // Crud Time Per Credit -->
<script>
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
                            '<button class="btn btn-default btn-default-danger" data-id="'+json_result.inserID+'">Delete</button>' +
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
        }
    }
</script>

<!-- Edit Jadwal -->
<script>
    $(document).on('click','#btnRemoveYesEditSc',function () {
        loading_buttonSm('#btnRemoveYesEditSc');
        $('#btnRemoveNoEditSc').prop('disabled',true);
        var data = {
            action : 'delete',
            ScheduleID : ScheduleID
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudSchedule';
        $.post(url,{token:token},function (result) {
            toastr.success('Data Removed','Sucess!');
            setTimeout(function () {
                window.location.href = base_url_js+'academic/timetables';
            },1500);
        });
    });

    $(document).on('change','.form-timepercredit-edit-sc,.form-credit-edit-sc',function () {
        var ID = $(this).attr('data-id');
        setSesiAkhir_EditJadwal(ID);
        checkSchedule_EditJadwal(ID);

    });

    $(document).on('keyup','.form-credit-edit-sc',function () {
        var ID = $(this).attr('data-id');
        setSesiAkhir_EditJadwal(ID);
        checkSchedule_EditJadwal(ID);

    });

    // Onchange Cek kelas Bentrok
    $(document).on('change','.form-classroom-edit-sc,.form-day-edit-sc',function () {
        var ID = $(this).attr('data-id');
        checkSchedule_EditJadwal(ID);
    });

    $(document).on('change','input[type=radio][fm=dtt-form-edit-sc]',function () {
        loadformTeamTeaching($(this).val(),'#formTeamTeaching');
    });


    // ========

    $(document).on('click','.btn-delete-sesi-edit-sc',function () {
        var Sesi = $(this).attr('data-sesi');
        var sdID = $(this).attr('data-sd');

        if(sdID==''){

            $('.trNewSesi'+Sesi).remove();

            dataSesiNewArr = $.grep(dataSesiNewArr, function(value) {
                return value != Sesi;
            });

            if(dataSesiArr.length==1 && dataSesiNewArr.length==0){
                $('#headerSubSesi'+dataSesiArr[0]).addClass('hide');
            }
        } else {
            $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Remove <span style="color:red;">Sub Sesi '+Sesi+'</span> ?? </b> ' +
                '<button type="button" id="btnRemoveSubSesiYesEditSc" data-sd="'+sdID+'" data-sesi="'+Sesi+'" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
                '<button type="button" id="btnRemoveSubSesiNoEditSc" class="btn btn-default" data-dismiss="modal">No</button>' +
                '</div>');
            $('#NotificationModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        }


    });

    $(document).on('click','#btnRemoveSubSesiYesEditSc',function () {
        var Sesi = $(this).attr('data-sesi');
        var sdID = $(this).attr('data-sd');

        loading_buttonSm('#btnRemoveSubSesiYesEditSc');
        $('#btnRemoveSubSesiNoEditSc').prop('disabled',true);
        var url = base_url_js+'api/__crudSchedule';
        var token = jwt_encode({action:'deleteSubSesi',sdID:sdID},'UAP)(*');
        $.post(url,{token:token},function (result) {

            dataSesiArr = $.grep(dataSesiArr, function(value) {
                return value != Sesi;
            });
            $('.trNewSesi'+Sesi).remove();


            dataSesiDb = dataSesiDb - 1;

            if(dataSesiArr.length==1){
                $('#headerSubSesi'+dataSesiArr[0]).addClass('hide');
            }

            $('#NotificationModal').modal('hide');

        });
    });

    $(document).on('change','#replaceSchedule',function () {

        if ($(this).is(':checked')){
            var sdID = $(this).val();
            $('#formReplaceSD').val(sdID);
            $('#btnSavejadwal,#addNewSesi').prop('disabled',false);
        } else {
            $('#formReplaceSD').val('');
            $('#btnSavejadwal,#addNewSesi').prop('disabled',true);
        }

    });
</script>

<!-- Edit Jadwal ---- CRUD Room -->
<script>
    $(document).on('click','#addClassRoom',function () {
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
    $(document).on('click','#btnSaveClassroom',function () {


        var process = true;

        var Room = $('#formRoom').val(); process = (Room=='') ? errorInput('#formRoom') : true ;
        var Seat = $('#formSeat').val(); var processSeat = (Seat!='' && $.isNumeric(Seat) && Math.floor(Seat)==Seat) ? true : errorInput('#formSeat') ;
        var SeatForExam = $('#formSeatForExam').val(); var processSeatForExam = (SeatForExam!='' && $.isNumeric(SeatForExam) && Math.floor(SeatForExam)==SeatForExam) ? true : errorInput('#formSeatForExam') ;


        if(Room!='' && processSeat && processSeatForExam){
            $('#formRoom,#formSeat,#formSeatForExam,#btnCloseClassroom').prop('disabled',true);
            loading_button('#btnSaveClassroom');
            loading_page('#viewClassroom');

            var data = {
                action : 'add',
                ID : '',
                formData : {
                    Room : Room,
                    Seat : Seat,
                    SeatForExam : SeatForExam,
                    Status : 0,
                    UpdateBy : sessionNIP,
                    UpdateAt : dateTimeNow()
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+"api/__crudClassroom";

            $.post(url,{token:token},function (data_result) {

                for(var i=1;i<=parseInt(dataSesi);i++){
                    var selected = $('#formClassroom'+i).val();
                    loadSelectOptionClassroom('#formClassroom'+i,selected);
                }

                setTimeout(function () {

                    if(data_result.inserID!=0) {
                        toastr.success('Data tersimpan','Success!');
                        $('#GlobalModal').modal('hide');

                    } else {
                        $('#formRoom,#formSeat,#formSeatForExam,#btnCloseClassroom').prop('disabled',false);
                        $('#btnSaveClassroom').prop('disabled',false).html('Save');
                        toastr.warning('Room is exist','Warning');
                    }
                },1000);

            });
        } else {
            toastr.error('Form Required','Error!');
        }
    });
</script>


<!-- Edit Jadwal ----  CRUD Time Per Credit-->
<script>
    $(document).on('click','#addTimePerCredit',function () {

        var url = base_url_js + 'api/__crudTimePerCredit';
        var token = jwt_encode({action: 'read'}, 'UAP)(*');
        $.post(url, {token: token}, function (data_json) {
            if (data_json.length > 0) {
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
                for (var i = 0; i < data_json.length; i++) {
                    $('#rowTime').append('<tr id="tr' + data_json[i].ID + '">' +
                        '<td class="td-center">' + data_json[i].Time + ' Minute</td>' +
                        '<td class="td-center">' +
                        '<button class="btn btn-default btn-default-danger btn-delete-timepercredit" data-id="' + data_json[i].ID + '">Delete</button>' +
                        '</td>' +
                        '</tr>');
                }
                ;


                $('#NotificationModal').modal({
                    'show': true
                });
            }
        })
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
                            '<button class="btn btn-default btn-default-danger" data-id="'+json_result.inserID+'">Delete</button>' +
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
</script>