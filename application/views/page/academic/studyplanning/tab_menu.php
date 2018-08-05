
<style>
    .toggle-group .btn-default {
        z-index: 1000 !important;
    }
    .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
    .toggle.ios .toggle-handle { border-radius: 20px; }

    #tableDataStudents tr th,#tableDataStudents tr td {
        text-align: center;
    }
    #tableStd>tbody>tr>td {
        border-top: none;
        padding-top: 3px;
        padding-bottom: 3px;
    }
    .list-scd {
        list-style-type: none;
        padding-left: 0px;
    }

    table.table-krs th, table.table-krs td {
        text-align: center;
    }
    .t-center {
        text-align: center;
    }

    #tbStdLC tr th {
        text-align: center;
        background: #007475;
        color: #FFFFFF;
    }
</style>



<div class="row" style="margin-top: 30px;">

    <div class="col-md-2">
        <button class="btn btn-default btn-default-warning btn-block" id="btnLimitCredit" disabled>Set Limit Credit</button>
    </div>
    <div class="col-md-10">
        <div class="thumbnail">
            <div class="row">
                <div class="col-xs-3">
                    <select class="form-control" id="filterProgramCampus"></select>
                </div>
                <div class="col-xs-3">
                    <select class="form-control" id="filterSemester"></select>
                </div>
                <div class="col-xs-4">
                    <select class="form-control" id="filterBaseProdi"></select>
                </div>
                <div class="col-xs-2">
                    <select class="form-control" id="filterSemesterSchedule"></select>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="row">
    <div class="col-md-12">
        <hr/>
        <div id="divPage"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        window.SemesterAntara = 0;

        window.NPM = '';
        window.ta = '';

        // Data Schedule yang sudah di kirim oleh setudent
        window.ScheduleExist = [];
        window.totalCreditExist = 0;

        window.dataMaxCredit = 0;
        window.totalMyCourse = 0;
        window.totalCredit = 0;
        window.KRSDraf = [];


        // Daftar KRS yang sudah menjadi KSM
        window.ArrDrafKRS = [];

        $('input[type=checkbox][data-toggle=toggle]').bootstrapToggle();
        loadSelectOptionProgramCampus('#filterProgramCampus','');

        loSelectOptionSemester('#filterSemester','');

        loadSelectOptionBaseProdi('#filterBaseProdi','');

        window.myInv = setInterval(loadPageFirst,500);
        window.myInv2 = setInterval(loadTableFirst,500);

    });

    function loadPageFirst(){
        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){
            loadSemester();
            $('#btnLimitCredit').attr('data-semesterid',filterSemester.split('.')[0]);
            $('#btnLimitCredit').prop('disabled',false);
            clearInterval(myInv);
        }
    }

    function loadTableFirst(){
        var filterSemesterSchedule = $('#filterSemesterSchedule').val();
        if(filterSemesterSchedule!='' && filterSemesterSchedule!=null){
            getStudents();
            clearInterval(myInv2);
        }
    }


    $(document).on('change','#filterSemester',function () {
        loadSemester();
    });

    function loadSemester() {
        var Semester = $('#filterSemester').val();
        if(Semester!='' && Semester!=null){
            var SemesterID = (Semester!='' && Semester!= null) ? Semester.split('.')[0] : '';
            $('#filterSemesterSchedule').empty();
            loadSelectOPtionAllSemester('#filterSemesterSchedule','',SemesterID,SemesterAntara);
        }
    }

    $(document).on('change','#filterSemester,#filterBaseProdi,#filterSemesterSchedule',function () {
        getStudents();
    });

    $(document).on('click','#btnBack',function () {
        getStudents();
    })

    $(document).on('click','.detailStudyPlan',function () {

        // Data Schedule yang sudah di kirim oleh setudent
        ScheduleExist = [];
        totalCreditExist = 0;

        dataMaxCredit = 0;
        totalMyCourse = 0;
        totalCredit = 0;
        KRSDraf = [];
        ArrDrafKRS = [];


        // var NPM =
        ta = $(this).attr('data-ta');
        NPM = $(this).attr('data-npm');
        showDataKRSStudent();


    });

    function showDataKRSStudent() {
        var data = {
            action : 'detailStudent',
            NPM : NPM,
            ta : ta
        };
        var url = base_url_js+'api/__crudStudyPlanning';
        var token = jwt_encode(data,'UAP)(*');

        ScheduleExist = [];
        KRSDraf = [];

        $.post(url,{token:token},function (jsonResult) {

            var dataStd = jsonResult;

            var emailMhs = (dataStd.EmailPU!=null && dataStd.EmailPU!='') ? '' : 'hide';
            var emailDsn = (dataStd.MentorEmailPU!=null && dataStd.MentorEmailPU!='') ? '' : 'hide';

            $('#divPage').html('<div class="col-md-8 col-md-offset-2" style="margin-bottom: 15px;" xmlns="http://www.w3.org/1999/html">' +
                '            <div class="row">' +
                '<div class="col-md-12" style="margin-bottom: 15px;"><button class="btn btn-warning" id="btnBack"><i class="fa fa-arrow-left right-margin" aria-hidden="true"></i> Back</button></div>' +
                '                <div class="col-xs-2">' +
                '                    <img class="img-thumbnail" style="width: 100%;" src="'+base_url_img_student+''+dataStd.Student_DB+'/'+dataStd.Photo+'">' +
                '                </div>' +
                '                <div class="col-xs-9">' +
                '                    <b>'+dataStd.NPM+' - '+dataStd.Name+'</b>' +
                '                    <div class="'+emailMhs+'"><br/><span>'+dataStd.EmailPU+'</span><br/>' +
                '                    <a style="font-size: 10px;"><i class="fa fa-envelope-o" aria-hidden="true"></i> Send Message</a>' +
                '                    </div>' +
                '                    <div class="thumbnail" style="margin-bottom: 10px;margin-top: 10px;">' +
                '                        <table class="table" id="tableStd">' +
                '                            <tr>' +
                '                                <td style="width: 15%;">Mentor</td>' +
                '                                <td style="width: 1%;">:</td>' +
                '                                <td><b>'+dataStd.Mentor+'</b>' +
                '                                    <div class="'+emailDsn+'"><br/>' + dataStd.MentorEmailPU +
                '                                           <br/><a style="font-size: 10px;"><i class="fa fa-envelope-o" aria-hidden="true"></i> Send Message</a>' +
                '                                    </div>' +
                '                                </td>' +
                '                            </tr>' +
                '                            <tr>' +
                '                                <td>IPK</td>' +
                '                                <td>:</td>' +
                '                                <td>'+parseFloat(dataStd.DetailSemester.IPK).toFixed(2)+'</td>' +
                '                            </tr>' +
                '                            <tr>' +
                '                                <td>Last IPS</td>' +
                '                                <td>:</td>' +
                '                                <td>'+parseFloat(dataStd.DetailSemester.LastIPS).toFixed(2)+' | '+dataStd.DetailSemester.MaxCredit.Credit+' Credit</td>' +
                '                            </tr>' +
                '                        </table>' +
                '                    </div>' +
                '                    <hr/>' +
                '                </div>' +
                '            </div>' +
                '<div class="col-xs-12"> <div class="thumbnail" style="padding: 10px;">' +
                '            <b>Status : </b>' +
                '            <i class="fa fa-circle" style="color:#d8d8d8;"></i> Student has not sent KRS |' +
                '            <i class="fa fa-circle" style="color:#00BCD4;"></i> Waiting Approval Mentor |' +
                '            <i class="fa fa-circle" style="color: #369c3a;"></i> KRS Approved Mentor |' +
                '            <i class="fa fa-check-circle" style="color: #369c3a;"></i> KRS Approved Kaprodi' +
                '        </div></div>' +
                '        </div>' +

                '        <table class="table table-striped table-bordered table-krs">' +
                '            <thead>' +
                '            <tr style="background:#437e88;color:#ffffff;">' +
                '                <th style="width: 7%;">Code</th>' +
                '                <th>Course</th>' +
                '                <th style="width: 5%;">Type</th>' +
                '                <th style="width: 5%;">Credit</th>' +
                '                <th style="width: 5%;">Group</th>' +
                '                <th>Schedule</th>' +
                '                <th style="width: 5%;">Status</th>' +
                '            </tr>' +
                '            </thead><tbody id="dataSchedule"></tbody>' +
                '        </table><hr/>' +
                '<div id="dataPageLoad" class="well"></div>');

            var tr = $('#dataSchedule');
            dataMaxCredit = dataStd.DetailSemester.MaxCredit.Credit;
            totalMyCourse = dataStd.Schedule.length;
            for(var i=0;i<dataStd.Schedule.length;i++){
                var dataSc = dataStd.Schedule[i];
                var status = '<i class="fa fa-circle" style="color:#d8d8d8;"></i>';
                if(dataSc.KRSStatus==1){
                    status = '<i class="fa fa-circle" style="color:#00BCD4;"></i>';
                } else if(dataSc.KRSStatus==2){
                    status = '<i class="fa fa-circle" style="color:#369c3a;"></i>';
                } else if(dataSc.KRSStatus==3){
                    status = '<i class="fa fa-check-circle" style="color: #369c3a;"></i>';
                }

                tr.append('<tr>' +
                    '<td>'+dataSc.MKCode+'</td>' +
                    '<td style="text-align: left;"><b>'+dataSc.NameEng+'</b><br/><i>'+dataSc.Name+'</i></td>' +
                    '<td>'+dataSc.TypeSP+'</td>' +
                    '<td>'+dataSc.Credit+'</td>' +
                    '<td>'+dataSc.ClassGroup+'</td>' +
                    '<td><ul id="sc'+i+'" class="list-scd"></ul></td>' +
                    '<td>'+status+'</td>' +
                    '</tr>');

                ScheduleExist.push(dataSc.ScheduleID);

                ArrDrafKRS.push(dataSc.CDID);
                totalCredit = parseInt(totalCredit) + parseInt(dataSc.Credit);


                var sc = $('#sc'+i);
                for(var s=0;s<dataSc.DetailSchedule.length;s++){
                    var dataSCD = dataSc.DetailSchedule[s];
                    var st = dataSCD.StartSessions.split(':');
                    var en = dataSCD.EndSessions.split(':');

                    var start = st[0]+':'+st[1];
                    var end = en[0]+':'+en[1];
                    sc.append('<li>R.'+dataSCD.Room+' | <span style="text-align: right;">'+dataSCD.DayNameEng+', '+start+' - '+end+'<span></li>');
                }

                totalCreditExist = totalCreditExist + parseInt(dataSc.Credit);
                if(i==(dataStd.Schedule.length - 1)){
                    tr.append('<tr style="background: lightyellow;font-weight: bold;">' +
                        '<td colspan="3">Total Credit</td>' +
                        '<td>'+totalCreditExist+'</td>' +
                        '</tr>');
                }
            }

            if(typeof dataStd.DetailOfferings.Arr_CDID !== "undefined"){

                $('#dataPageLoad').html('' +
                    '<div class="tableRequired">' +
                    // '<div class="alert alert-warning hide" role="alert" id="alertKRS"><b>KRS Waiting Approval (Penasehat Akademik)</b></div>' +
                    '                    <h3><span class="label label-primary">Required Course</span></h3>' +
                    '                </div>' +
                    '                <div class="tableRequired">' +
                    '                    <div class="table-responsive" style="margin-top:30px;">' +
                    '                       <table class="table table-bordered">' +
                    '                           <thead>' +
                    '                               <tr style="background: #20485a;color: #ffffff;">' +
                    // '                                   <th class="t-center" style="width: 10px;">No</th>' +
                    '                                   <th class="t-center" style="width: 90px;">Code</th>' +
                    '                                   <th class="t-center">Course</th>' +
                    '                                   <th class="t-center" style="width: 10px;">Status</th>' +
                    '                                   <th class="t-center" style="width: 10px;">Credit</th>' +
                    '                                   <th class="t-center" style="width: 75px;">Group</th>' +
                    '                                   <th class="t-center" style="width: 355px;">Schedule</th>' +
                    '                                   <th class="t-center" style="width: 5px;">Action</th>' +
                    '                               </tr>' +
                    '                           </thead>' +
                    '                           <tbody id="dataCourse">' +
                    '                           </tbody>' +
                    '                       </table>'+
                    '                    </div>' +
                    '                </div>' +

                    '<div class="tableOptional">' +
                    '                    <h3><span class="label label-warning">Optional Course</span></h3>' +
                    '                </div>' +
                    '                <div class="tableOptional">' +
                    '                    <div class="table-responsive" style="margin-top:30px;">' +
                    '                       <table class="table table-bordered"">' +
                    '                           <thead>' +
                    '                               <tr style="background: #90855e;color: #ffffff;">' +
                    // '                                   <th class="t-center" style="width: 10px;">No</th>' +
                    '                                   <th class="t-center" style="width: 90px;">Code</th>' +
                    '                                   <th class="t-center">Course</th>' +
                    '                                   <th class="t-center" style="width: 10px;">Status</th>' +
                    '                                   <th class="t-center" style="width: 10px;">Credit</th>' +
                    '                                   <th class="t-center" style="width: 75px;">Group</th>' +
                    '                                   <th class="t-center" style="width: 355px;">Schedule</th>' +
                    '                                   <th class="t-center" style="width: 5px;">Action</th>' +
                    '                               </tr>' +
                    '                           </thead>' +
                    '                           <tbody id="dataCourseOptional">' +
                    '                           </tbody>' +
                    '                       </table>'+
                    '                    </div>' +
                    '                </div>' +
                    '<br/>' +
                    '<div style="text-align: right;">' +
                    '<span id="totalCredit"></span> of <span id="dataMaxCredit"></span> Credit' +
                    '</div>');

                var no = 1;
                var totalReq = 0;
                var totalOpt = 0;

                for(var i=0;i<dataStd.DetailOfferings.Schedule.length;i++){

                    var sc = dataStd.DetailOfferings.Schedule[i];

                    var tr = '';


                    if(sc.MKType==1 && dataStd.DetailOfferings.Semester==sc.Semester){
                        totalReq += 1;
                        tr = $('#dataCourse');
                    } else {
                        totalOpt += 1;
                        tr = $('#dataCourseOptional');
                    }

                    var bg = (sc.SPID!=null) ? '<span class="btn btn-default btn-default-warning btn-sm"><b>Ul</b></span>' : '<span class="btn btn-default btn-default-primary btn-sm"><b>Br</b></span>';

                    var typesp = (sc.SPID!=null) ? 'Ul' : 'Br';
                    var btnAct='<button class="btn btn-sm btn-danger btn-delete-krs" id="btnDeleteKRS'+sc.ID+'" data-semesterid="'+dataStd.DetailOfferings.SemesterID+'" data-credit="'+sc.Credit+'" data-cdid="'+sc.CDID+'" data-group="'+sc.ClassGroup+'" data-id="'+sc.ID+'" data-typesp="'+typesp+'"><i class="fa fa-trash"></i></button>';
                    if(ScheduleExist.indexOf(sc.ID) == -1){
                        btnAct='<button class="btn btn-success btn-sm btn-add-krs" id="btnAddKRS'+sc.ID+'" data-semesterid="'+dataStd.DetailOfferings.SemesterID+'" data-credit="'+sc.Credit+'" data-cdid="'+sc.CDID+'" data-group="'+sc.ClassGroup+'" data-id="'+sc.ID+'" data-typesp="'+typesp+'"><i class="fa fa-download"></i></button>';
                    }

                    tr.append('<tr>' +
                        '<td style="text-align: center;">'+sc.MKCode+'</td>' +
                        '<td><strong>'+sc.MKNameEng+'</strong><br/><i>'+sc.MKName+'</i><br/><p style="color: blue;">Semester '+sc.Semester+'</p>' +
                        '<div id="alertSC'+sc.ID+'"></div> ' +
                        '</td>' +
                        '<td style="text-align: center;">'+bg+'</td>' +
                        '<td style="text-align: center;">'+sc.Credit+'</td>' +
                        '<td style="text-align: center;">'+sc.ClassGroup+'</td>' +
                        '<td><div id="scd'+no+'"></div></td>' +
                        '<td style="text-align: center;">' +
                        '<div id="btnActKRSOnline'+sc.ID+'">'+btnAct+'</div> ' +
                        '<textarea id="scheduleArr'+sc.ID+'" class="hide" hidden readonly></textarea>' +
                        '</td>' +
                        '</tr>');

                    var sc_detail = sc.ScheduleDetails;
                    var scd = $('#scd'+no);
                    scd.html('');
                    var Draf = [];

                    for(var d=0;d<sc_detail.length;d++){

                        var data = sc_detail[d];

                        var DrafArr = {
                            Course : sc.MKNameEng,
                            ID : data.ID,
                            ScheduleID : data.ScheduleID,
                            ClassroomID : data.ClassroomID,
                            DayID : data.DayID,
                            StartSessions : data.StartSessions,
                            EndSessions : data.EndSessions
                        };

                        if(totalMyCourse>0 && $.inArray(sc.CDID,ArrDrafKRS)!=-1){KRSDraf.push(DrafArr);}
                        Draf.push(DrafArr);

                        var alert = (sc.SubSesi==1)? 'alert-warning' : 'alert-info';

                        var extStart = data.StartSessions.split(':');
                        var start = extStart[0]+':'+extStart[1];

                        var extEnd = data.EndSessions.split(':');
                        var end = extEnd[0]+':'+extEnd[1];

                        scd.append('<div class="alert '+alert+' alert-schedule" role="alert" style="width: 330px;"><b>R.'+data.Room+' </b> ' +
                            '<span class="seat"><span class="CountSeat'+sc.ID+'">'+data.CountSeat+'</span> of '+data.Seat+' Seat</span>' +
                            '<span style="float: right;">'+data.DayNameEng+', '+start+' - '+end+'</span></div>');
                        $('#btnAddKRS'+sc.ID).attr('data-seat',data.Seat);

                    }

                    $('#scheduleArr'+sc.ID).val(JSON.stringify(Draf));


                    no += 1;
                }

                $('#totalMyCourse').text(totalMyCourse);
                $('#totalCredit').text(totalCredit);
                $('#dataMaxCredit').text(dataMaxCredit);

                // console.log(Draf);

            }

        });
    }

    
    function getStudents() {

        var ProgramID = $('#filterProgramCampus').val();
        // var ProdiID = $('#filterBaseProdi').val().split('.')[0];
        var filterBaseProdi = $('#filterBaseProdi').val();
        var filterSemesterSchedule = $('#filterSemesterSchedule').val();
        var ClassOf = (filterSemesterSchedule != '' && filterSemesterSchedule != null) ? filterSemesterSchedule.split('|')[1].split('.')[1] : '';

        if (ProgramID != null && filterBaseProdi != null && filterSemesterSchedule != null && ClassOf != "") {
            var ProdiID = filterBaseProdi.split('.')[0];

            var data = {
                action: 'read',
                dataWhere: {
                    ProgramID: ProgramID,
                    ProdiID: ProdiID,
                    ClassOf: ClassOf
                }
            };

            $('#divPage').html('<div class="table-responsive"><table class="table table-striped table-bordered" id="tableDataStudents">' +
                '            <thead style="background: #007475;color: #ffffff;">' +
                '            <tr>' +
                '                <th rowspan="2" style="width: 1%;">No</th>' +
                '                <th rowspan="2" style="width: 7%;">NPM</th>' +
                '                <th rowspan="2">Student</th>' +
                '                <th rowspan="2" style="width: 15%;">Mentor</th>' +
                '                <th colspan="2" style="width: 10%;">Payment</th>' +
                '                <th rowspan="2" style="width: 10%;">Last IPS</th>' +
                '                <th rowspan="2" style="width: 10%;">IPK</th>' +
                '                <th rowspan="2" style="width: 5%;">Credit Taken</th>' +
                '                <th rowspan="2" style="width: 5%;">Max Credit</th>' +
                '            </tr>' +
                '            <tr>' +
                '               <th style="width: 10%;">BPP</th>' +
                '               <th style="width: 10%;">Credit</th>' +
                '            </tr>' +
                '            </thead>' +
                '            <tbody id="dataStudents"></tbody>' +
                '        </table></div>');

            var token = jwt_encode(data, 'UAP)(*');

            var url = base_url_js+'api/__crudStudyPlanning';
            $.post(url, {token: token}, function (jsonResult) {

                // console.log(jsonResult);

            var tr = $('#dataStudents');
            var no = 1;
            for (var i = 0; i < jsonResult.length; i++) {

                var CreditUnit = 0;
                var StudyPlanning = jsonResult[i].StudyPlanning;
                for (var c = 0; c < StudyPlanning.length; c++) {
                    var stp = StudyPlanning[c];
                    CreditUnit = CreditUnit + parseInt(stp.TotalSKS);
                }

                var Student = jsonResult[i].Student;

                var sendMailStd = (Student.EmailPU!=null && Student.EmailPU!='') ? '<br/><a style="color: #03a9f4;" href="javascript:void(0);" class="sendEmail" data-email="'+Student.EmailPU+'"><i class="fa fa-envelope-o" aria-hidden="true"></i> Send Email</a>' : '';

                tr.append('<tr>' +
                    '<td>' + no + '</td>' +
                    '<td>' + Student.NPM + '</td>' +
                    '<td style="text-align: left;">' +
                    '   <b>' +
                    '       <a href="javascript:void(0)" class="detailStudyPlan" data-npm="' + Student.NPM + '" data-ta="' + Student.ClassOf + '">' + Student.Name + '</a></b>' + sendMailStd +
                    '</td>' +
                    '<td id="mentorData'+no+'" style="text-align: left;">-</td>' +
                    '<td id="bpp'+no+'">-</td>' +
                    '<td id="credit'+no+'">-</td>' +
                    '<td>' +parseFloat(Student.DetailSemester.LastIPS).toFixed(2)+ '</td>' +
                    '<td>' + parseFloat(Student.DetailSemester.IPK).toFixed(2) + '</td>' +
                    '<td>' + CreditUnit + '</td>' +
                    '<td>' + Student.DetailSemester.MaxCredit.Credit + '</td>' +
                    '</tr>');

                if(Student.DetailPayment.length>0){
                    for(var p=0;p<Student.DetailPayment.length;p++){
                        var dt = Student.DetailPayment[p];
                        if(dt.PTID=='2'){
                            $('#bpp'+no).html('<i class="fa fa-check-circle" style="color: green;"></i>');
                        }
                        if(dt.PTID=='3'){
                            $('#credit'+no).html('<i class="fa fa-check-circle" style="color: green;"></i>');
                        }
                    }
                }

                if(Student.DetailMentor.length>0){
                    var dataMentor = Student.DetailMentor[0];
                    // var spDsn = dataMentor.Mentor.split(' ');
                    // var dsn = (spDsn.length>2) ? spDsn[0]+' '+spDsn[1] : dataMentor.Mentor;
                    var dsn = (dataMentor.Mentor!='' && dataMentor.Mentor!=null) ? dataMentor.Mentor : '-';
                    var divMentor = dsn+'<br/><i>'+dataMentor.NIP+'</i>';
                    $('#mentorData'+no).html(divMentor);
                }

                no++;
            }

            $('#tableDataStudents').DataTable({
                'pageLength': 25
            });
        });

        }

    }

    $(document).on('click','.sendEmail',function () {
        var email = $(this).attr('data-email');
        var url = 'https://mail.google.com/mail/?view=cm&fs=1&to='+email;
        PopupCenter(url,'xtf','900','500')

    });

    $(document).on('click','.btn-delete-krs',function () {
        var SemesterID = $(this).attr('data-semesterid');
        var Group = $(this).attr('data-group');
        var ID = $(this).attr('data-id');
        var Credit = $(this).attr('data-credit');
        var CDID = $(this).attr('data-cdid');
        var typesp = $(this).attr('data-typesp');

        $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Delete '+Group+' ??<hr/></b> ' +
            '<button type="button" class="btn btn-primary" data-id="'+ID+'" data-credit="'+Credit+'" data-cdid="'+CDID+'" data-typesp="'+typesp+'" data-semesterid="'+SemesterID+'" id="btnYesDeleteKRS" style="margin-right: 5px;">Yes</button>' +
            '<button type="button" class="btn btn-default" id="btnNoDeleteKRS" data-dismiss="modal">No</button>' +
            '</div>');
        $('#NotificationModal').modal('show');
    });

    $(document).on('click','#btnYesDeleteKRS',function () {
        var SemesterID = $('#btnYesDeleteKRS').attr('data-semesterid');
        var ScheduleID = $('#btnYesDeleteKRS').attr('data-id');

        var Credit = $(this).attr('data-credit');
        var CDID = $(this).attr('data-cdid');
        var typesp = $(this).attr('data-typesp');
        loading_buttonSm('#btnDeleteKRS'+ScheduleID);
        loading_buttonSm('#btnYesDeleteKRS');
        $('#btnNoDeleteKRS').prop('disabled',true);

        var data = {
            action : 'deleteKRS',
            SemesterID : SemesterID,
            ScheduleID : ScheduleID,
            NPM : NPM,
            Student_DB : 'ta_'+ta
        };

        // console.log(data);

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudKRSOnline';

        $.post(url,{token:token},function (jsonResult) {
            showDataKRSStudent();

            setTimeout(function () {
                $('#btnActKRSOnline'+ScheduleID).html('<button class="btn btn-success btn-sm btn-add-krs"' +
                    ' id="btnAddKRS'+ScheduleID+'" data-semesterid="'+SemesterID+'" data-credit="'+Credit+'" ' +
                    'data-cdid="'+CDID+'" data-id="'+ScheduleID+'" data-typesp="'+typesp+'"><i class="fa fa-download"></i></button>');

                $('#NotificationModal').modal('hide');
            },1000);

        });


    });

    $(document).on('click','.btn-add-krs',function () {

        var SemesterID = $(this).attr('data-semesterid');
        var ScheduleID = $(this).attr('data-id');
        var MaxSeat = $(this).attr('data-seat');
        var CDID = $(this).attr('data-cdid');
        var TypeSP = $(this).attr('data-typesp');
        var Credit = $(this).attr('data-credit');
        var Group = $(this).attr('data-group');

        checkCountSeat(SemesterID,ScheduleID,CDID,TypeSP,Credit,MaxSeat,Group);

    });

    function checkCountSeat(SemesterID,ScheduleID,CDID,TypeSP,Credit,MaxSeat,Group) {
        var data = {
            action : 'checkCountSeat',
            whereCheck : {
                SemesterID : SemesterID,
                ScheduleID : ScheduleID,
                CDID : CDID
            }
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudKRSOnline';


        var dataSchedule = $('#scheduleArr'+ScheduleID).val();
        var DrafArr = JSON.parse(dataSchedule);

        var NextCredit = parseInt(totalCredit) + parseInt(Credit);
        // return false;

        var process = [];

        // Dari Lokal Dulu
        for(var d=0;d<DrafArr.length;d++){

            for(var k=0;k<KRSDraf.length;k++){

                if(DrafArr[d].DayID==KRSDraf[k].DayID){
                    if( (DrafArr[d].StartSessions >= KRSDraf[k].StartSessions && DrafArr[d].StartSessions <= KRSDraf[k].EndSessions) ||
                        (DrafArr[d].EndSessions >= KRSDraf[k].StartSessions && DrafArr[d].EndSessions <= KRSDraf[k].EndSessions) ||
                        (DrafArr[d].StartSessions <= KRSDraf[k].StartSessions && DrafArr[d].EndSessions >= KRSDraf[k].EndSessions)
                    ){
                        process.push(0);
                        $('#alertSC'+DrafArr[d].ScheduleID).html('<div class="alert alert-danger" style="margin-bottom:0px;" role="alert">' +
                            '<b><i class="fa fa-level-down" aria-hidden="true"></i> Conflict with : </b> '+KRSDraf[k].Course+'</div>')
                        toastr.error(KRSDraf[k].Course,'Error Conflict with');
                        return false;
                    }
                }

            }

        }

        if($.inArray(0,process)==-1){

            if(dataMaxCredit>=NextCredit){

                $.post(url,{token:token},function (resultJson) {

                    var s = resultJson.length + 1 ;

                    if(parseInt(MaxSeat)<s){
                        $('.CountSeat'+ScheduleID).html(resultJson.length);
                        toastr.error('Class Full','Error');
                    } else {
                        $('.CountSeat'+ScheduleID).html(s);
                        saveToDraf(SemesterID,ScheduleID,CDID,TypeSP,Credit,Group);
                        for(var d=0;d<DrafArr.length;d++){
                            KRSDraf.push(DrafArr[d]);
                        }
                        $('#alertSC'+ScheduleID).html('');

                    }

                });

            }
            else {
                toastr.error('Not Enough Credit','Error');
            }
        }

        // return false;


    }

    function saveToDraf(SemesterID,ScheduleID,CDID,TypeSP,Credit,Group) {

        // $('#btnAddKRS'+ScheduleID).css('padding','3px 5px 3px 5px');
        loading_buttonSm('#btnAddKRS'+ScheduleID);

        var data = {
            action : 'add',
            Student_DB : 'ta_'+ta,
            formData : {
                SemesterID : SemesterID,
                NPM : NPM,
                ScheduleID : ScheduleID,
                CDID : CDID,
                TypeSP : TypeSP,
                Status : '3',
                Input_At : dateTimeNow()
            }
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudKRSOnline';
        $.post(url,{token:token},function (resultJSON) {

            showDataKRSStudent();
            setTimeout(function () {

                $('#btnActKRSOnline'+ScheduleID).html('<button class="btn btn-sm btn-danger btn-delete-krs" ' +
                    ' id="btnDeleteKRS'+ScheduleID+'" data-semesterid="'+SemesterID+'" data-credit="'+Credit+'" data-cdid="'+CDID+'"' +
                    ' data-group="'+Group+'" data-id="'+ScheduleID+'" data-typesp="'+TypeSP+'"><i class="fa fa-trash"></i></button>');
                toastr.success('Saved','Success');

                totalMyCourse = totalMyCourse + 1;
                totalCredit = totalCredit + parseInt(Credit);


                $('#totalCredit').text(totalCredit);

            },500);

        });

    }
</script>
<script>
<!-- Set Limit Credit -->

    $(document).on('click','#btnLimitCredit',function () {

        var SemesterID = $(this).attr('data-semesterid');

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Limit Credit</h4>');

        var htmlLimit = '<div class="row">' +
            '            <div class="col-xs-6">' +
            '                <input value="'+SemesterID+'" id="formSemesterID" hidden readonly /> ' +
            '                <select class="form-control" id="selectLC_Curriculum"></select>' +
            '            </div>' +
            '            <div class="col-xs-6">' +
            '                <select class="form-control" id="selectLC_Prodi"></select>' +
            '            </div>' +
            '           </div>' +
            '           <div class="row">' +
            '            <div class="col-xs-8"><hr/>' +
            '               <div class="form-group">' +
            '               <label>Student</label>' +
            '                  <div id="dataStudents">Selc. Student</div>' +
            '               </div>' +
            '            </div>' +
            '            <div class="col-xs-4"><hr/>' +
            '               <div class="form-group">' +
            '                   <label>Limit Credit</label>' +
            '                   <input type="number" id="formCredit" class="form-control"/>' +
            '               </div>' +
            '            </div>' +
            '        </div>' +
            '<div class="row">' +
            '<div class="col-xs-12" style="text-align: right;">' +
            '<button class="btn btn-success" id="btnSaveLC">Add</button>' +
            '</div>' +
            '</div>' +
            '' +
            '<div class="row">' +
            '<div class="col-xs-12"><hr/>' +
            '<table class="table table-bordered table-striped" id="tbStdLC">' +
            '<thead>' +
            '<tr>' +
            '<th style="width:3%">No</th>' +
            '<th>Student</th>' +
            '<th style="width:15%">Credit</th>' +
            '<th style="width:15%">Action</th>' +
            '</tr>' +
            '</thead><tbody id="dataRWStdLC"></tbody>' +
            '</table>' +
            '</div>' +
            '</div> ';

        $('#GlobalModal .modal-body').html(htmlLimit);

        loadSelectOptionCurriculum('#selectLC_Curriculum','');
        loadSelectOptionBaseProdi('#selectLC_Prodi','');

        setTimeout(function () {
            loadStd();
        },1000);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    $(document).on('change','#selectLC_Curriculum,#selectLC_Prodi',function () {
        loadStd();
    });

    function loadStd() {

        var Curriculum = $('#selectLC_Curriculum').val();
        var Prodi = $('#selectLC_Prodi').val();

        if(Curriculum!='' && Curriculum!=null && Prodi!='' && Prodi!=null){
            loading_data('#dataStudents');

            var db_student = 'ta_'+Curriculum.split('.')[1];
            var ProdiID = Prodi.split('.')[0];
            var data = {
              action : 'getStudents',
                DB_Student : db_student,
                ProdiID : ProdiID,
                SemesterID : $('#formSemesterID').val()
            };

            var tr = $('#dataRWStdLC');
            tr.empty();
            $('#dataStudents').html('Student Not Yet');

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudLimitCredit';
            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.Students.length>0){
                    $('#dataStudents').html('<select class="select2-select-00 full-width-fix" size="5" id="selectLC_Students">' +
                        '                        <option value=""></option>' +
                        '                    </select>');

                    for(var i=0;i<jsonResult.Students.length;i++){
                        var d = jsonResult.Students[i];
                        var dis ='';
                        if(d.LCID!='' && d.LCID!=null){
                            dis = 'disabled'
                        }
                        $('#selectLC_Students').append('<option value="'+d.NPM+'" '+dis+'>'+d.NPM+' - '+d.Name+'</option>');
                    }
                    $('#selectLC_Students').select2({allowClear: true});
                }

                // Load Table
                if(jsonResult.dataLC.length>0){
                    var no = 1;
                    for(var c=0;c<jsonResult.dataLC.length;c++){
                        var dlc = jsonResult.dataLC[c];
                        tr.append('<tr id="trLC'+dlc.LCID+'">' +
                            '<td style="text-align: center;">'+no+'</td>' +
                            '<td><b>'+dlc.Name+'</b><br/>'+dlc.NPM+'</td>' +
                            '<td style="text-align: center;">'+dlc.Credit+'</td>' +
                            '<td style="text-align: center;">' +
                            '   <button class="btn btn-danger btn-del-lc" data-id="'+dlc.LCID+'">Del</button>' +
                            '</td>' +
                            '</tr>');
                        no++;
                    }
                }
                else {
                    tr.append('<tr><td colspan="4" style="text-align: center;">-- Data Not Yet --</td></tr>');
                }
            });
        }

    }

    $(document).on('click','.btn-del-lc',function () {

        if(confirm('Delete data?')){
            var LCID = $(this).attr('data-id');
            var data = {
              action : 'deleteLC',
              LCID : LCID
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudLimitCredit';
            $.post(url,{token:token},function () {
                // loadStd();
                toastr.success('Data deleted','Success');
                loading_buttonSm('.btn-del-lc[data-id='+LCID+']');
                setTimeout(function () {
                    loadStd();
                },500);
            });
        }

    });
    
    $(document).on('click','#btnSaveLC',function () {
        var Students = $('#selectLC_Students').val();
        var Credit = $('#formCredit').val();

        if(Students!='' && Students!=null
            && Credit!='' && Credit!=null){

            loading_buttonSm('#btnSaveLC');

            var data = {
              action : 'addLC',
              dataInsert : {
                  SemesterID : $('#formSemesterID').val(),
                  NPM : Students,
                  Credit : Credit,
                  UpdateBy : sessionNIP,
                  UpdateAt : dateTimeNow()
              }
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudLimitCredit';
            $.post(url,{token:token},function (result) {
                loadStd();
                toastr.success('Data deleted','Success');
                $('#formCredit').val('');
                setTimeout(function () {
                    $('#btnSaveLC').prop('disabled',false).html('Add');
                },500);
            });

        }

    });

</script>