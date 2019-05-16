
<style>
    #tableTimetableSA th {
        text-align: center;
        background: #607D8B;
        color: #fff;
    }

    #tableTimetableSA td:first-child {
        border-right: 1px solid #ccc;
    }

    #tableListStudent th {
        background: #ececec;
        color: #607D8B;
    }

    #tableListStudent th, #tableListStudent td {
        text-align: center;
    }
    .td-attd {
        width: 4%;
    }

    .ck-attd {
        padding-left : 0px;
    }

    .ck-attd input[type=checkbox]{
         float: none;
         margin-left: 0px;
    }

    .sts-pay {
        display: block;
        min-height: 20px;
        margin-top: 13px;
        margin-bottom: 7px;
        vertical-align: middle;
    }
</style>

<!--<h1>sa_timetable</h1>-->
<div style="text-align: right;margin-bottom: 15px;">
    <button class="btn btn-default" id="loadDocumentExam"><i class="fa fa-download margin-right"></i> Exam Document</button>
</div>
<div id="viewTableTimetable"></div>

<input id="tempScheduleIDSA" class="hide">
<textarea id="tempStd" class="hide"></textarea>



<script>

    $(document).ready(function () {
        // loadTimetableSA();
        loadTimetables();
    });

    function loadTimetables() {

        $('#viewTableTimetable').html('<table class="table table-striped" id="tableTimetableSA">' +
            '    <thead>' +
            '    <tr>' +
            '        <th style="width: 1%;border-right: 1px solid #ccc;">No</th>' +
            '        <th style="width: 7%;">Group</th>' +
            '        <th>Course</th>' +
            '        <th style="width: 12%;">Schedule</th>' +
            '        <th style="width: 15%;">Lecturers</th>' +
            '        <th style="width: 5%;">Students</th>' +
            '        <th style="width: 5%;"><i class="fa fa-cog"></i></th>' +
            '        <th style="width: 12%;">UTS</th>' +
            '        <th style="width: 12%;">UAS</th>' +
            '    </tr>' +
            '    </thead>' +
            '</table>');

        var data = {
            SASemesterID : '<?=$SASemesterID; ?>'
        };

        var token = jwt_encode(data,'UAP)(*');

        var dataTable = $('#tableTimetableSA').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Group, Lecturer"
            },
            "ajax":{
                url : base_url_js+"api2/__getTimetableSA", // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        });


    }

    $(document).on('click','.showStd',function () {
        var token = $(this).attr('data-token');
        var course = $(this).attr('data-course');

        var dataToken = jwt_decode(token,'UAP)(*');
        console.log(dataToken);

        $('#GlobalModalLarge .modal-dialog').css('width','1200px');
        $('#GlobalModalLarge .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+course+'</h4>');

        $('#GlobalModalLarge .modal-body').html('<div class="row">' +
            '    <div class="col-md-12">' +
            '        <table class="table table-bordered table-striped" id="tableListStudent">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Student</th>' +
            '                <th style="width: 4%;">BPP</th>' +
            '                <th style="width: 4%;">Crdt</th>' +
            '                <th class="td-attd">1</th>' +
            '                <th class="td-attd">2</th>' +
            '                <th class="td-attd">3</th>' +
            '                <th class="td-attd">4</th>' +
            '                <th class="td-attd">5</th>' +
            '                <th class="td-attd">6</th>' +
            '                <th class="td-attd">7</th>' +
            '                <th class="td-attd">8</th>' +
            '                <th class="td-attd">9</th>' +
            '                <th class="td-attd">10</th>' +
            '                <th class="td-attd">11</th>' +
            '                <th class="td-attd">12</th>' +
            '                <th class="td-attd">13</th>' +
            '                <th class="td-attd">14</th>' +
            '                <th class="td-attd">UTS</th>' +
            '                <th class="td-attd">UAS</th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody id="listStudent"></tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>');

        if(dataToken.length>0){
            var no = 1;
            $.each(dataToken,function (i,v) {

                var StatusBPP = (v.StatusBPP==0 || v.StatusBPP=='0')
                    ? '<i class="fa fa-times-circle sts-pay" style="color: red;"></i>'
                    : '<i class="fa fa-check-circle sts-pay" style="color: green;"></i>';

                var StatusCredit = (v.StatusCredit==0 || v.StatusCredit=='0')
                    ? '<i class="fa fa-times-circle sts-pay" style="color: red;"></i>'
                    : '<i class="fa fa-check-circle sts-pay" style="color: green;"></i>';

                var p = ((v.StatusBPP==0 || v.StatusBPP=='0') && (v.StatusCredit==0 || v.StatusCredit=='0'))
                    ? '-'
                    : '<div class="checkbox ck-attd">' +
                    '  <label>' +
                    '    <input type="checkbox" value="">' +
                    '  </label>' +
                    '</div>';

                // var p = '<input type="checkbox " value="1">';


                $('#listStudent').append('<tr>' +
                    '<td>'+no+'</td>' +
                    '<td style="text-align: left;"><b>'+v.Name+'</b><br/>'+v.NPM+'</td>' +
                    '<td>'+StatusBPP+'</td>' +
                    '<td>'+StatusCredit+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +

                    '</tr>');

                // for(var a=1;a<=14;a++){
                //     var p = ((v.StatusBPP==0 || v.StatusBPP=='0') && (v.StatusCredit==0 || v.StatusCredit=='0'))
                //         ? '-'
                //         : '<div class="checkbox ck-attd">' +
                //         '  <label>' +
                //         '    <input type="checkbox " value="1">' +
                //         '  </label>' +
                //         '</div>';
                //
                //     $('#tdAttd_'+no).append('<td>'+p+'</td>');
                // }
                //
                // // UTS
                // var UTS = ((v.StatusBPP==0 || v.StatusBPP=='0') && (v.StatusCredit==0 || v.StatusCredit=='0'))
                //     ? '-'
                //     : '<div class="checkbox ck-attd">' +
                //     '  <label>' +
                //     '    <input type="checkbox " value="1">' +
                //     '  </label>' +
                //     '</div>';
                //
                // $('#tdAttd_'+no).append('<td>'+UTS+'</td>');
                //
                //
                // // UAS
                // var UAS = ((v.StatusBPP==0 || v.StatusBPP=='0') && (v.StatusCredit==0 || v.StatusCredit=='0'))
                //     ? '-'
                //     : '<div class="checkbox ck-attd">' +
                //     '  <label>' +
                //     '    <input type="checkbox " value="1">' +
                //     '  </label>' +
                //     '</div>';
                //
                // $('#tdAttd_'+no).append('<td>'+UAS+'</td>');

                no++;
            })
        }

        $('#GlobalModalLarge .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModalLarge').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    function loadTimetableSA() {

        var data = {
            action : 'loadTimetableSA',
            SASemesterID : '<?=$SASemesterID; ?>'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudSemesterAntara';

        $.post(url,{token:token},function (jsonResult) {

            console.log(jsonResult);

        });

        //
        // return false;
        //
        // var dataTable = $('#tableMonScore').DataTable( {
        //     "processing": true,
        //     "serverSide": true,
        //     "iDisplayLength" : 10,
        //     "ordering" : false,
        //     "language": {
        //         "searchPlaceholder": "NIM, Student, Group, Lecturer"
        //     },
        //     "ajax":{
        //         url : base_url_js+"api/__getMonScoreStd", // json datasource
        //         data : {token:token},
        //         ordering : false,
        //         type: "post",  // method  , by default get
        //         error: function(){  // error handling
        //             $(".employee-grid-error").html("");
        //             $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
        //             $("#employee-grid_processing").css("display","none");
        //         }
        //     }
        // });

    }

    $(document).on('click','.loadSyllabusRPS',function () {

        var ScheduleIDSA = $(this).attr('data-id');
        var Course = $(this).attr('data-course');

        var data = {
            action : 'loadDocumentSA',
            ScheduleIDSA : ScheduleIDSA
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudSemesterAntara';

        $.post(url,{token:token},function (jsonResult) {

            console.log(jsonResult);

            var d = jsonResult[0];

            var bodyGrade = 'Syllabus & RPS not been sent';
            if(d.DocumentStatus==1 || d.DocumentStatus=='1' || d.DocumentStatus==2 || d.DocumentStatus=='2'){

                var btnSyllabus = (d.Syllabus!=null && d.Syllabus!='') ? '<a href="'+base_url_portal_lecturers+'uploads/silabus/'+d.Syllabus+'" target="_blank" class="btn btn-sm btn-block btn-default">Download Syllabus</a>' : '-';
                var btnRPS = (d.RPS!=null && d.RPS!='') ? '<a href="'+base_url_portal_lecturers+'uploads/sap/'+d.RPS+'" target="_blank" class="btn btn-sm btn-block btn-default">Download RPS</a>' : '-';

                var Evaluation = (d.Evaluasi!=null && d.Evaluasi!='') ? d.Evaluasi : 0;
                var UTS = (d.UTS!=null && d.UTS!='') ? d.UTS : 0;
                var UAS = (d.UAS!=null && d.UAS!='') ? d.UAS : 0;

                bodyGrade = '<table class="table">' +
                    '    <tr>' +
                    '        <td style="width: 50%;text-align: center;">'+btnSyllabus+'</td>' +
                    '        <td style="width: 50%;text-align: center;">'+btnRPS+'</td>' +
                    '    </tr>' +
                    '    <tr>' +
                    '        <td>Assignment</td>' +
                    '        <td>'+Evaluation+' %</td>' +
                    '    </tr>' +
                    '    <tr>' +
                    '        <td>Mid Exam</td>' +
                    '        <td>'+UTS+' %</td>' +
                    '    </tr>' +
                    '    <tr>' +
                    '        <td>Exam Exam</td>' +
                    '        <td>'+UAS+' %</td>' +
                    '    </tr>' +
                    '</table>' +
                    '<div id="statusDock"></div>';
            }



            var ds = (d.DocumentStatus==1 || d.DocumentStatus=='1') ? '' : 'disabled';

            $('#GlobalModal .modal-header').html('<h4 class="modal-title">'+Course+'</h4>');
            $('#GlobalModal .modal-body').html(bodyGrade);

            if(d.DocumentStatus==2 || d.DocumentStatus=='2'){
                $('#statusDock').html('<div class="alert alert-success" style="margin-bottom: 0px;"><b>Approved</b></div>');
            } else if(d.DocumentStatus==-2 || d.DocumentStatus=='-2'){
                $('#statusDock').html('<div class="alert alert-danger" style="margin-bottom: 0px;"><b>Rejected</b></div>');
            } else {
                $('#statusDock').html('');
            }

            // $('#GlobalModal .modal-footer').addClass('hide');
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> | ' +
                '<button type="button" class="btn btn-danger btnact" data-status="-2" '+ds+'>Rejected</button>' +
                '<button type="button" class="btn btn-success btnact" data-status="2" '+ds+'>Approved</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });

            $('.btnact').click(function () {

                $('.btnact,button[data-dismiss=modal]').prop('disabled',true);

                var status = $(this).attr('data-status');
                var data = {
                    action : 'updateStatusDocument',
                    ScheduleIDSA : ScheduleIDSA,
                    DocumentStatus : status
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api2/__crudSemesterAntara';

                $.post(url,{token:token},function (result) {
                    toastr.success('Submited','Success');
                    setTimeout(function () {
                        $('button[data-dismiss=modal]').prop('disabled',false);
                    },500);
                });


            });

        });


    });

    $(document).on('click','.btnAttdStd',function () {

        var ScheduleIDSA = $(this).attr('data-id');
        var tokenStd = $(this).attr('data-std');
        var Course = $(this).attr('data-course');
        var dataToken = jwt_decode(tokenStd);

        $('#tempScheduleIDSA').val(ScheduleIDSA);
        $('#tempStd').val(tokenStd);

        var dataCheck = {
            action : 'checkLectAttd',
            ScheduleIDSA : ScheduleIDSA,
            Type : 'lec'
        };

        var tokenCheck = jwt_encode(dataCheck,'UAP)(*');
        var url2 = base_url_js+'api2/__crudSemesterAntara';


        $.post(url2,{token:tokenCheck},function (ArrMeet){



            $('#GlobalModal .modal-header').html('<h4 class="modal-title">'+Course+'</h4>');

            $('#GlobalModal .modal-footer').html(' <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button id="submitAttendance" class="btn btn-success">Submit</button> ');


            var bodyModal = '<div class="row">' +
                '    <div class="col-md-6 col-md-offset-3">' +
                '        <div class="well">' +
                '            <select class="form-control" id="filterSesion"></select>' +
                '            <input class="hide" id="formScheduleIDSA" value="'+ScheduleIDSA+'" />' +
                '        </div>' +
                '           <hr/>' +
                '    </div>' +
                '</div>' +
                '<div class="row">' +
                '    <div class="col-md-12">' +
                '        <table class="table">' +
                '            <thead>' +
                '            <tr>' +
                '                <th style="width: 1%;">No</th>' +
                '                <th>Student</th>' +
                '                <th style="width: 20%;">Action</th>' +
                '                <th style="width:30%;" class="hide">Reason</th>' +
                '            </tr>' +
                '            </thead>' +
                '            <tbody id="listStudent"></tbody>' +
                '        </table>' +
                '    </div>' +
                '</div>';
            $('#GlobalModal .modal-body').html(bodyModal);

            for (var i = 1;i<=14;i++){
                var dsb = ($.inArray(''+i,ArrMeet)!=-1) ? '' : 'disabled';
                var selc = '';
                if(ArrMeet.length>0){
                    var idS = ArrMeet[0];
                    selc = (parseInt(idS)==parseInt(i)) ? 'selected' : '';
                }
                $('#filterSesion').append('<option value="'+i+'" '+dsb+' '+selc+'>Sessions '+i+'</option>');
            }

            if(ArrMeet.length>0){
                loadAttendanceStd(ArrMeet[0]);
            }

            $('#submitAttendance').click(function () {

                if(dataToken.length>0){

                    loading_buttonSm('#submitAttendance');
                    $('.formAttdStd, .attd-reason').prop('disabled',true);

                    var filterSesion = $('#filterSesion').val();
                    var formScheduleIDSA = $('#formScheduleIDSA').val();

                    var no = 1;
                    var AttdStd = [];
                    $.each(dataToken,function (v,i) {

                        var UserID = $('#formNPM_'+no).val();

                        var Status = ($('#formAttdStd_'+no).is(':checked')) ? '1' : '2';

                        var arr = {
                            ScheduleIDSA : formScheduleIDSA,
                            UserID : UserID,
                            Meet : filterSesion,
                            Status : Status,
                            Type : 'std',
                            Reason : $('#formReason_'+no).val(),
                            UpdatedAt : dateTimeNow(),
                            UpdatedBy : sessionNIP
                        };

                        AttdStd.push(arr);

                        no++;

                    });


                    var data = {
                        action : 'inputAttendanceSA',
                        dataAttd : AttdStd
                    };

                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'api2/__crudSemesterAntara';

                    $.post(url,{token:token},function (result) {
                        toastr.success('Attendance Saved','Success');
                        setTimeout(function () {
                            $('#submitAttendance').html('Submit').prop('disabled',false);
                            $('.formAttdStd, .attd-reason').prop('disabled',false);
                        },500);

                    })


                }

            });

            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });

        });

    });

    function loadAttendanceStd(Meet) {

        var ScheduleIDSA = $('#tempScheduleIDSA').val();
        var tempStd = $('#tempStd').val();

        var dataToken = jwt_decode(tempStd);

        var data = {
            action : 'loadAttdStd',
            ScheduleIDSA : ScheduleIDSA,
            Meet : Meet,
            Type : 'std'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudSemesterAntara';

        $.post(url,{token:token},function (jsonResult) {

            $('#listStudent').empty();
            // $('#studentSA').val(JSON.stringify(jsonResult));

            if(dataToken.length>0){
                var no = 1;
                var dataStdCheckd = jsonResult;
                $.each(dataToken,function (i,v) {

                    var DefaultChecked = ($.inArray(''+v.NPM,dataStdCheckd)!=-1) ? 'checked' : '';
                    var style_ = ($.inArray(''+v.NPM,dataStdCheckd)!=-1) ? '' : 'style="background:#ffebeb;color:red;"';

                    $('#listStudent').append('<tr id="tr_'+no+'" '+style_+'>' +
                        '<td>'+no+'</td>' +
                        '<td>'+v.Name+'<br/>'+v.NPM+'' +
                        '<input id="formNPM_'+no+'" class="hide" value="'+v.NPM+'" >' +
                        '</td>' +
                        '<td>' +
                        '<label class="">' +
                        '                                    <input type="checkbox" id="formAttdStd_'+no+'" data-no="'+no+'" '+DefaultChecked+' class="custom-control-input formAttdStd">' +
                        '                                    <span class="custom-control-indicator"></span>' +
                        '                                </label>' +
                        '</td>' +
                        '<td class="hide">' +
                        '<textarea class="form-control attd-reason" id="formReason_'+no+'" rows="2"></textarea>' +
                        '</td>' +
                        '</tr>');

                    no++;
                });

            }
            else {
                $('#listStudent').append('<tr>' +
                    '<td colspan="4" style="text-align: center;color: #CCCCCC;">-- Student Not Yet --</td>' +
                    '</tr>');
            }
        });

    }

    $('#loadDocumentExam').click(function () {

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Exam Document</h4>');



        var htmlss = '<div class="row">' +
            '    <div class="col-md-3">' +
            '        <div class="form-group">' +
            '            <label>Exam</label>' +
            '            <select class="form-control" id="formExamType">' +
            '                <option value="uts">UTS</option>' +
            '                <option value="uas">UAS</option>' +
            '                <option disabled>--- Make-up Exams ---</option>' +
            '                <option value="re_uts" style="color: orangered;">Make-up UTS</option>' +
            '                <option value="re_uas" style="color: orangered;">Make-up UAS</option>' +
            '            </select>' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-4">' +
            '        <div class="form-group">' +
            '            <label>Date</label>' +
            '            <select class="form-control" id="formExamDate"></select>' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-5">' +
            '        <div class="form-group">' +
            '            <label>Document</label>' +
            '            <select class="form-control" id="formPDFTypeDocument">' +
            '               <option value="5">Tamplate Map Soal</option>' +
            '               <option value="1">Berita Acara Penyerahan</option>' +
            '               <option value="2">Berita Acara Pelaksanaan Ujian</option>' +
            '               <option value="3">Exam Attendance</option>' +
            '               <option disabled="">-----------------------</option>' +
            '               <option value="4">Pengawas</option>' +
            '            </select>' +
            '        </div>' +
            '    </div>' +
            '</div>';

        $('#GlobalModal .modal-body').html(htmlss);

        loadExamDate2PDF();

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> ' +
            '<button type="button" class="btn btn-success" id="downloadDocumentExam" disabled>Download</button>');


        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        $('#formExamType').change(function () {
            loadExamDate2PDF();
        });

        $('#downloadDocumentExam').click(function () {
            var formExamType = $('#formExamType').val();
            var formExamDate = $('#formExamDate').val();
            var formPDFTypeDocument = $('#formPDFTypeDocument').val();

            var dataSemester = JSON.parse($('#dataSemester').val());
            var SemesterName = (dataSemester.length>0) ? dataSemester[0].Name : '';


            var data = {
                SASemesterID : '<?=$SASemesterID; ?>',
                Semester : SemesterName,
                Type : formExamType,
                ExamDate : formExamDate,
                DocumentType : formPDFTypeDocument,
                IsSemesterSA : 1
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'save2pdf/filterDocument';

            FormSubmitAuto(url,'POST',[{ name: 'token', value: token }]);



        });




    });

    function loadExamDate2PDF() {

        $('#downloadDocumentExam').prop('disabled',true);
        $('#formExamDate').empty();

        var formExamType = $('#formExamType').val();

        var data = {
            action : 'loadDateExamSA',
            SASemesterID : '<?=$SASemesterID; ?>',
            Type : formExamType
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudSemesterAntara';

        $.post(url,{token:token},function (jsonResult) {

            // console.log(jsonResult);
            if(jsonResult.length>0){
                var dateOpt = '';
                $.each(jsonResult,function (i,v) {
                    var d = moment(v.ExamDate).format('ddd, DD MMM YYYY');
                    dateOpt = dateOpt+'<option value="'+v.ExamDate+'">'+d+'</option>';
                });

                $('#formExamDate').append(dateOpt);

                $('#downloadDocumentExam').prop('disabled',false);
            }


        });
    }



</script>