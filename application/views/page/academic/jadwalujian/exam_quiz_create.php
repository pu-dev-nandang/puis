<style>
    #panel-filter .well {
        padding-bottom: 5px;
    }

    #listQuiz li.item-quiz {
        background: #fafafa;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 5px 10px 5px 10px;
        margin-bottom: 15px;
        line-height: 1.428571;
        position: relative;
        margin-right: 136px;
    }

    #listQuiz {
        padding-inline-start: 15px;
    }

    #listQuiz .label {
        position: relative;
        left: 0px;
    }

    #listQuiz .well {
        padding: 5px;
        margin-bottom: 5px;
    }

    #listQuiz li.item-quiz a {
        color: #333333;
        text-decoration: none;
    }

    #listQuiz li.item-quiz a:hover {
        color: blue;
        background: lightyellow;
    }

    #listQuiz .btn-remove-quiz {
        font-size: 11px;
        padding: 1px 5px 1px 5px;
    }

    .form-question {
        resize: none;
    }

    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

    .lbl-1 {
        background: #2196F3;
        color: #fff;
        padding: 3px 5px 3px 5px;
        border-radius: .25em;
        font-size: 10px;
    }

    .lbl-2 {
        background: #ce56e2;
        color: #fff;
        padding: 3px 5px 3px 5px;
        border-radius: .25em;
        font-size: 10px;
    }

    .lbl-3 {
        background: #FF9800;
        color: #fff;
        padding: 3px 5px 3px 5px;
        border-radius: .25em;
        font-size: 10px;
    }

    .lbl-point {
        margin-right: 5px;
        font-size: 11px;
        color: #fff;
        padding: 1px 5px 1px 5px;
        border-radius: .25em;
    }

    .panel-act-question-in-quiz {
        position: absolute;
        top: 0px;
        right: -142px;
    }

    .panel-course-list {
        border-left: 5px solid #9e9e9e75;
        padding-left: 4px;
        margin-bottom: 10px;
    }
</style>


<?php

$ID = (count($dataQuiz) > 0) ? $dataQuiz[0]['ID'] : '';
$Title = (count($dataQuiz) > 0) ? $dataQuiz[0]['Title'] : '';
$NoteForExam = (count($dataQuiz) > 0) ? $dataQuiz[0]['NoteForExam'] : '';
$NotesForStudents = (count($dataQuiz) > 0) ? $dataQuiz[0]['NotesForStudents'] : '';
$listExam = '';
if (count($dataQuiz) > 0) {
    if (count($dataQuiz[0]['ExamQuiz']) > 0) {
        $listExam = json_encode(($dataQuiz[0]['ExamQuiz']));
    }
}


?>


<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Action : Create</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Title <sup style="color: orangered;">* required</sup></label>
                    <input class="hide" readonly id="ID" value="<?= $ID; ?>">
                    <input class="form-control" id="Title" value="<?= $Title; ?>">
                </div>
                <div class="form-group">
                    <label>Note <sup style="color: orangered;">* required</sup></label>
                    <textarea class="form-control" rows="4" id="NoteForExam"><?= $NoteForExam; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Note For Student</label>
                    <textarea class="form-control" rows="4" id="NotesForStudents"><?= $NotesForStudents; ?></textarea>
                </div>
                <div class="form-group">
                    <button id="btnAddQuestion" class="btn btn-default">Add question</button>
                    <textarea id="dataTempQuiz" class="hide"></textarea>
                    <textarea id="dataTempQuizPoint" class="hide"></textarea>
                    <textarea id="dataLoadQuiz" class="hide"></textarea>
                </div>
                <div id="loadQuestionListOnQuiz"></div>

                <hr />
                <div class="form-group">
                    <button id="btnAddSchedule" class="btn btn-default">Add Exam Schedule</button>
                    <textarea id="temporarySchedule" class="hide"><?= $listExam; ?></textarea>
                </div>
                <table class="table table-centre table-bordered table-striped" id="dataTableExamSchedule" style="width:100%!important;;">
                    <thead>
                        <tr style="background-color: #eceff1;">
                            <th style="width: 1%;">No</th>
                            <th style="width: 17%;">Exam Schedule</th>
                            <th>Course</th>
                            <th style="width: 25%;">Invigilator</th>
                            <th style="width: 5%;"><i class="fa fa-cog"></i></th>
                        </tr>
                    </thead>
                    <tbody id="listTemporarySchedule"></tbody>
                </table>
            </div>
            <div class="panel-footer" style="text-align: right;">
                Total Point : <span id="viewPoint" style="margin-right: 7px;">0</span>
                <button class="btn btn-success" id="btnSaveQuiz">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var ID = getUrlParameter('id');
        if (ID != '') {
            getDataExist(ID);
            loadDataScheduleAdd();
        }
    });

    function getDataExist(ID) {
        var data = {
            action: 'getQuizInThisID',
            ID: ID
        };
        var token = jwt_encode(data, 'UAP)(*');
        var url = base_url_js + 'api4/__crudQuiz';
        $.post(url, {
            token: token
        }, function(jsonResult) {

            var details = jsonResult.Details;

            var arrQID = [];
            if (details.length > 0) {
                $.each(details, function(i, v) {
                    arrQID.push(v.QID);
                });
            }
            var va = (arrQID.length > 0) ? JSON.stringify(arrQID) : '';
            $('#dataTempQuiz').val(va);

            var vaLoad = (arrQID.length > 0) ? JSON.stringify(jsonResult) : '';
            $('#dataLoadQuiz').val(vaLoad);

            setTimeout(function() {
                loadDataQuiz();
            }, 2000);

        });
    }

    $('#btnSaveQuiz').click(function() {

        var Title = $('#Title').val();
        var NoteForExam = $('#NoteForExam').val();
        var NotesForStudents = $('#NotesForStudents').val();
        var dataTempQuiz = $('#dataTempQuiz').val();

        var temporarySchedule = $('#temporarySchedule').val();

        if (Title != '' && Title != null &&
            NoteForExam != '' && NoteForExam != null &&
            dataTempQuiz != '' && dataTempQuiz != null &&
            temporarySchedule != '' && temporarySchedule != null) {

            var d = (dataTempQuiz != '') ? JSON.parse(dataTempQuiz) : [];

            var totalPoint = 0;
            var dataForm = [];

            if (d.length > 0) {
                for (var i = 0; i < d.length; i++) {
                    var point_quiz = $('#point_quiz_' + d[i]).val();
                    point_quiz = (point_quiz != '' && point_quiz != null) ? point_quiz : 0;
                    totalPoint = totalPoint + parseFloat(point_quiz);
                    var arr = {
                        QID: d[i],
                        Point: point_quiz
                    };
                    dataForm.push(arr);
                }
            }

            if (totalPoint != 100) {
                toastr.warning('The total points must be equal to 100', 'Warning');
            } else {

                if (confirm('Are you sure?')) {
                    var ID = $('#ID').val();
                    loading_modal_show();

                    var dataTemporarySchedule = JSON.parse(temporarySchedule);

                    var data = {
                        action: 'saveDataQuiz',
                        QuizID: (ID != '') ? ID : '',
                        NIP: sessionNIP,
                        ForExam: '1',
                        Title: Title,
                        NoteForExam: NoteForExam,
                        NotesForStudents: NotesForStudents,
                        dataForm: dataForm,
                        dataTemporarySchedule: dataTemporarySchedule
                    };

                    var token = jwt_encode(data, 'UAP)(*');
                    var url = base_url_js + 'api4/__crudQuiz';

                    $.post(url, {
                        token: token
                    }, function(jsonResult) {

                        if (jsonResult.Status == 1 || jsonResult.Status == '1') {
                            toastr.success('Saved quiz', 'Success');
                            setTimeout(function() {
                                window.location.href = '';
                            }, 1000);
                        } else if (jsonResult.Status == -1 || jsonResult.Status == '-1') {
                            toastr.warning(jsonResult.Message, 'Warning');
                            alert(jsonResult.Message);
                            setTimeout(function() {
                                window.location.href = '';
                            }, 1000);

                        } else if (jsonResult.Status == -2 || jsonResult.Status == '-2') {
                            toastr.warning(jsonResult.Message, 'Warning');
                            loadDataQuiz();
                        }

                        setTimeout(function() {
                            loading_modal_hide();
                        }, 500);

                    });
                }

            }

        } else {
            toastr.warning('Please, fill in the required form', 'Warning');
        }


    });

    $(document).on('click', '.addToQuizFromMyQuestion', function() {

        var ID = $(this).attr('data-id');
        var dataTempQuiz = $('#dataTempQuiz').val();

        var d = (dataTempQuiz != '' && dataTempQuiz != null) ? JSON.parse(dataTempQuiz) : [];

        var pushID = true;
        if (d.length > 0) {
            for (var i = 0; i < d.length; i++) {
                if (ID == d[i]) {
                    toastr.warning('The question is already in the quiz', 'Warning');
                    pushID = false;
                    break;
                }
            }
        } else {
            pushID = true;
        }

        if (pushID) {
            d.push(ID);
            var newVal = JSON.stringify(d);
            $('#dataTempQuiz').val(newVal);
            loadDataQuiz();
            toastr.success('Added to quiz', 'Success');
        }

    });

    $(document).on('click', '.btnRemoveQuestion', function() {

        var dataLoadQuiz = $('#dataLoadQuiz').val();
        var dataQ = (dataLoadQuiz != '' && dataLoadQuiz != null) ? JSON.parse(dataLoadQuiz) : [];
        var TotalAnswer = (dataLoadQuiz != '' && dataLoadQuiz != null &&
            dataQ.Quiz.length > 0) ? parseInt(dataQ.TotalAnswer) : 0;

        if (TotalAnswer > 0) {
            toastr.warning('Quiz cannot be edited', 'Warning');
        } else {
            if (confirm('Are you sure?')) {

                var ID = $(this).attr('data-id');
                var dataTempQuiz = $('#dataTempQuiz').val();
                var d = JSON.parse(dataTempQuiz);

                var newArr = [];
                if (d.length > 0) {
                    for (var i = 0; i < d.length; i++) {
                        if (d[i] != ID) {
                            newArr.push(d[i]);
                        }

                    }
                }

                if (newArr.length > 0) {
                    $('#dataTempQuiz').val(JSON.stringify(newArr));
                } else {
                    $('#dataTempQuiz').val('');
                }

                // Point
                countPointQuestion();

                loadDataQuiz();

            }
        }

    });

    $('#btnAddQuestion').click(function() {
        // GlobalModalLarge

        $('#GlobalModalLarge .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '        <h4 class="modal-title">Master Question</h4>');

        $('#GlobalModalLarge .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModalLarge .modal-body').html('<div id="divTableQuestion"></div>');

        $('#divTableQuestion').html('<div class="">' +
            '            <table class="table table-centre table-bordered table-striped" id="dataTableMasterQuestion" style="width:100%!important;;">' +
            '                <thead>' +
            '                <tr style="background-color: #eceff1;">' +
            '                    <th style="width: 1%;">No</th>' +
            '                    <th>Question</th>' +
            '                    <th style="width: 5%;"><i class="fa fa-cog"></i></th>' +
            '                </tr>' +
            '                </thead>' +
            '                <tbody></tbody>' +
            '            </table>' +
            '        </div>');

        var data = {
            action: 'getMasterQuestion',
            NIP: sessionNIP,
            Portal: 'pcam'
        };

        var token = jwt_encode(data, 'UAP)(*');
        var url = base_url_js + 'api4/__crudQuiz';

        var dataTable = $('#dataTableMasterQuestion').DataTable({
            "processing": true,
            "serverSide": true,
            "iDisplayLength": 10,
            "ordering": false,
            "language": {
                "searchPlaceholder": "Question . . ."
            },
            "responsive": true,
            "ajax": {
                url: url, // json datasource
                ordering: false,
                data: {
                    token: token
                },
                type: "post", // method  , by default get
                error: function() { // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                }
            }
        });

        $('#GlobalModalLarge').modal({
            'backdrop': 'static',
            'show': true
        });
    });

    $(document).on('keyup', '.form-quiz-point', function() {

        var va = $(this).val();
        var d = parseFloat(va);

        if (d <= 0) {
            $(this).val(1);
        } else if (d > 100) {
            $(this).val(100);
        } else {
            $(this).val(d);
        }

        // Count point
        countPointQuestion();

    });

    $('#btnAddSchedule').click(function() {

        $('#GlobalModalXtraLarge .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '        <h4 class="modal-title">List Exam Schedule</h4>');

        $('#GlobalModalXtraLarge .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        var body = /*html*/ `
        <div class="row">
            <div class="col-md-8 col-lg-offset-2">
                <div class="well">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Exam Type</label>
                            <select id="filterExamType" class="form-control">
                                <option value="uts">UTS</option>
                                <option value="uas">UAS</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Exam Schedule</label>
                            <select class="form-control" id="filterExamDate"></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="viewTableExamSchedule" class="col-md-12"></div>
        </div>`;

        $('#GlobalModalXtraLarge .modal-body').html(body);




        // getListSchedule('uas');
        getExamDateList();

        $('#GlobalModalXtraLarge').modal({
            'backdrop': 'static',
            'show': true
        });

    });

    $(document).on('change', '#filterExamType', function() {
        getExamDateList();
    });

    $(document).on('change', '#filterExamDate', function() {
        getListSchedule();
    });

    function getExamDateList() {

        $('#viewTableExamSchedule').html('<h3>Please, select date of exam</h3>');

        var filterExamType = $('#filterExamType').val();
        var data = {
            action: 'ExamDateList',
            Type: filterExamType
        };

        var token = jwt_encode(data, 'UAP)(*');
        var url = base_url_js + 'api4/__crudQuiz';

        $('#filterExamDate').empty();

        $.post(url, {
            token: token
        }, function(jsonResult) {
            var opt = '';
            if (jsonResult.length > 0) {
                jsonResult.forEach(currentItem => {
                    var date = moment(currentItem.ExamDate).format('DD MMMM YYYY');
                    opt = opt + '<option value="' + currentItem.ExamDate + '">' + date + '</option>';
                });

                $('#filterExamDate').html(`<option selected disabled>-- Select Date --</option>${opt}`);
            } else {
                $('#filterExamDate').html(`<option selected disabled>-- No Data --</option>`);
            }

        });

    }

    async function getTempList() {

        return new Promise(async (resolve, reject) => {
            var temporarySchedule = $('#temporarySchedule').val();

            var tempData = [];
            if (temporarySchedule != '') {
                temporarySchedule = JSON.parse(temporarySchedule);
                $.each(temporarySchedule, function(i, v) {
                    tempData.push(v.ExamID);
                });

            }
            resolve(tempData);
        })

    }
    async function getListSchedule() {

        var filterExamType = $('#filterExamType').val();
        var filterExamDate = $('#filterExamDate').val();

        if (filterExamType != '' && filterExamType != null &&
            filterExamDate != '' && filterExamDate != null) {

            var listTemporary = await getTempList();

            console.log(listTemporary);

            var data = {
                action: 'getListScheduleExamSemesterActive',
                Type: filterExamType,
                Date: filterExamDate,
                OnlineLearning: '1'
            };

            var token = jwt_encode(data, 'UAP)(*');
            var url = base_url_js + 'api4/__crudQuiz';

            $.post(url, {
                token: token
            }, function(resultJSON) {

                var body = /*html*/
                    `<table class="table table-centre table-bordered table-striped" id="dataTableExamSchedule" style="width:100%!important;;">
                                    <thead>
                                    <tr style="background-color: #eceff1;">
                                        <th style="width: 1%;">No</th>
                                        <th style="width: 17%;">Exam Schedule</th>
                                        <th>Course</th>
                                        <th style="width: 25%;">Invigilator</th>
                                        <th style="width: 5%;"><i class="fa fa-cog"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody id="viewListExamSchedule"></tbody>
                                </table>`;

                $('#viewTableExamSchedule').html(body);

                // $('#viewListExamSchedule').empty();

                if (resultJSON.length > 0) {
                    $.each(resultJSON, function(i, v) {


                        var ExamStart = v.ExamStart.substr(0, 5);
                        var ExamEnd = v.ExamEnd.substr(0, 5);
                        var ExamDate = moment(v.ExamDate).format('dddd, DD MMM YYYY');

                        var viewExamDate = ExamDate + ' <label class="label label-default">' + ExamStart + '-' + ExamEnd + '</label>';

                        var inv1 = (v.Pengawas1 != '' && v.Pengawas1 != null) ? v.Pengawas1 + ' - ' + v.Pengawas1Name : '';
                        var inv2 = (v.Pengawas2 != '' && v.Pengawas2 != null) ? '<br/>' + v.Pengawas2 + ' - ' + v.Pengawas2Name : '';

                        var Schedule = '';


                        v.Schedule.forEach(item => {
                            var partOfSch = /*html*/
                                `<div class="panel-course-list">
                            Group : ${item.ClassGroup}
                            <br/> Code : ${item.MKCode}
                            <br/> Course : ${item.Name} <i>(${item.NameEng})</i>
                            </div>`;
                            Schedule = Schedule + partOfSch;
                        });


                        var dataToAdd = {
                            Schedule: v.Schedule,
                            ExamID: v.ID,
                            ExamDate: ExamDate,
                            ExamStart: ExamStart,
                            ExamEnd: ExamEnd,
                            inv1: inv1,
                            inv2: inv2,
                        };

                        var tokenToAdd = jwt_encode(dataToAdd, 'UAP)(*');

                        var btnAct = '<button class="btn btn-sm btn-success addingSchedule" data-token="' + tokenToAdd + '">Add</button>';

                        if (parseInt(v.OnlineLearning) == 1) {
                            if ((v.QuizID != null && v.QuizID != '') || ($.inArray(v.ID, listTemporary) != -1)) {
                                btnAct = '';
                            }
                        } else {
                            btnAct = '<button class="btn btn-default btn-sm" disabled style="color:red;">Offline Exam</button>'
                        }


                        var row = '<tr>' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>' + viewExamDate + '</td>' +
                            '<td style="text-align:left;">' + Schedule + '</td>' +
                            '<td style="text-align:left;">' + inv1 + inv2 + '</td>' +
                            '<td>' + btnAct + '</td>' +
                            '</tr>';

                        $('#viewListExamSchedule').append(row);

                    });

                    $('#dataTableExamSchedule').dataTable({
                        pageLength: 25
                    });


                }
                // console.log(resultJSON);

            });

        } else {
            toastr.warning('Select Type & Date of Exam', 'Warning');
        }


    }

    $(document).on('click', '.addingSchedule', function() {

        var token = $(this).attr('data-token');
        var dataToken = jwt_decode(token, 'UAP)(*');

        var temporarySchedule = $('#temporarySchedule').val();

        var newData = [];
        if (temporarySchedule != '') {
            newData = JSON.parse(temporarySchedule);
        }

        newData.push(dataToken);
        $('#temporarySchedule').val(JSON.stringify(newData));

        $(this).remove();

        loadDataScheduleAdd();

    });

    $(document).on('click', '.removingSchedule', function() {

        var ExamID = $(this).attr('data-id');
        var temporarySchedule = $('#temporarySchedule').val();

        // console.log(temporarySchedule);

        var newData = [];
        if (temporarySchedule != '') {
            temporarySchedule = JSON.parse(temporarySchedule);
            $.each(temporarySchedule, function(i, v) {
                if (ExamID != v.ExamID) {
                    newData.push(v);
                }
            })
        }

        newData = (newData.length > 0) ? JSON.stringify(newData) : '';
        // console.log(newData);
        $('#temporarySchedule').val(newData);
        loadDataScheduleAdd()

    });

    function loadDataScheduleAdd() {

        var temporarySchedule = $('#temporarySchedule').val();

        $('#listTemporarySchedule').empty();
        if (temporarySchedule != '') {
            temporarySchedule = JSON.parse(temporarySchedule);
            if (temporarySchedule.length > 0) {

                $.each(temporarySchedule, function(i, v) {

                    var Schedule = '';
                    v.Schedule.forEach(item => {
                        var partOfSch = /*html*/
                            `<div class="panel-course-list">
                            Group : ${item.ClassGroup}
                            <br/> Code : ${item.MKCode}
                            <br/> Course : ${item.Name} <i>(${item.NameEng})</i>
                            </div>`;
                        Schedule = Schedule + partOfSch;
                    });


                    var viewExamDate = v.ExamDate + ' <label class="label label-default">' + v.ExamStart + '-' + v.ExamEnd + '</label>';

                    var row = '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td>' + viewExamDate + '</td>' +
                        '<td style="text-align:left;">' + Schedule + '</td>' +
                        '<td style="text-align:left;">' + v.inv1 + v.inv2 + '</td>' +
                        '<td><button class="btn btn-sm btn-danger removingSchedule" data-id="' + v.ExamID + '"><i class="fa fa-trash"></i></button></td>' +
                        '</tr>';

                    $('#listTemporarySchedule').append(row);

                });
            }

        }

    }



    function loadDataQuiz() {
        var dataTempQuiz = $('#dataTempQuiz').val();
        loading_page('#loadQuestionListOnQuiz');

        if (dataTempQuiz != '' && dataTempQuiz != null) {
            var d = (dataTempQuiz != '') ? JSON.parse(dataTempQuiz) : [];

            var dataLoadQuiz = $('#dataLoadQuiz').val();
            var dataQ = (dataLoadQuiz != '' && dataLoadQuiz != null) ? JSON.parse(dataLoadQuiz) : [];
            var TotalAnswer = 0;

            if (dataLoadQuiz != '' && dataLoadQuiz != null && dataQ.Quiz.length > 0) {
                TotalAnswer = parseInt(dataQ.TotalAnswer);
                $('#formNotesForStudents').val(dataQ.Quiz[0].NotesForStudents);
                $('#viewPoint').html('100');
                $('#btnSaveQuiz').prop('disabled', false);
            }

            var data = {
                action: 'getArrDataQuestion',
                ArrQID: d
            };

            var token = jwt_encode(data, 'UAP)(*');
            var url = base_url_js + 'api4/__crudQuiz';

            var disabled = (TotalAnswer > 0) ? 'disabled' : '';

            $.post(url, {
                token: token
            }, function(jsonResult) {

                if (jsonResult.length > 0) {

                    var listQuestoin = '';
                    $.each(jsonResult, function(i, v) {

                        var o = v.Option;
                        var listOpt = '';
                        if (o.length > 0) {
                            $.each(o, function(i2, v2) {
                                var isAns = (v2.IsTheAnswer == 1 || v2.IsTheAnswer == '1') ?
                                    '<i class="fa fa-check-circle" style="color: green;margin-right:5px;"></i>' :
                                    '';

                                var pointBBG = (v2.Point != '' && v2.Point != null && parseFloat(v2.Point) > 0) ?
                                    '#7CB342' : '#E57373';


                                var viewPoint = (v2.Point != '' && v2.Point != null) ? '<span class="lbl-point" style="background: ' + pointBBG + '; ">' + v2.Point + '</span>' : '';
                                listOpt = listOpt + '<li style="line-height: 1.428571;margin-bottom: 0px;">' + viewPoint + isAns + v2.Option + '</li>';
                            });
                        }

                        var q = v.Question;
                        var expandDetail = (q.QTID == 1 || q.QTID == 2) ?
                            '                            <div class="collapse" id="collapseExample_' + i + '">' +
                            '                                <hr/>' +
                            '                                        <div class="well">' +
                            '                                            <ul>' + listOpt + '</ul>' +
                            '                                        </div>' +
                            '                            </div>' :
                            '';


                        var alertManualCorrection = (q.QTID == 3) ?
                            '<div class="alert alert-warning alert-essay" role="alert">' +
                            '<i class="fa fa-exclamation-triangle margin-right"></i> Need manual correction</div>' : '';

                        var valuePoint = '';
                        var dataTempQuizPoint = $('#dataTempQuizPoint').val();
                        if (dataTempQuizPoint != '') {
                            valuePoint = searchPointTemporary(q.ID);
                        } else if (dataLoadQuiz != '' && dataLoadQuiz != null && dataQ.Details.length > 0) {
                            valuePoint = searchID(q.ID, dataQ.Details);
                        }

                        var viewQuestion = (v.Status == 1 || v.Status == '1') ?
                            '<span class="lbl-' + q.QTID + '">' + q.Type + '</span> - ' + q.Question :
                            '<div class="alert alert-danger" style="margin-bottom: 0px;">Question is outdated, please <b>delete</b> it immediately</div>';

                        listQuestoin = listQuestoin + '<li class="item-quiz" data-id="' + q.ID + '">' +
                            '                            <a role="button" data-toggle="collapse" href="#collapseExample_' + i + '" aria-expanded="true" aria-controls="collapseExample">' + viewQuestion + '</a>' +
                            '                           <div class="panel-act-question-in-quiz">' +
                            '                                   <input class="form-control form-quiz-point" value="' + valuePoint + '" id="point_quiz_' + q.ID + '" placeholder="Point..." type="number" ' + disabled + ' style="max-width: 100px;display: inline;" >' +
                            '                                   <button class="btn btn-danger btn-sm btnRemoveQuestion" data-id="' + v.QID + '" ' + disabled + '><i class="fa fa-trash"></i></button>' +
                            '                           </div>' + expandDetail + alertManualCorrection +
                            '                        </li>';

                    });

                    setTimeout(function() {
                        $('#loadQuestionListOnQuiz').html('<ol id="listQuiz">' + listQuestoin + '</ol>');
                        if (TotalAnswer <= 0) {
                            $('#listQuiz').sortable({
                                axis: 'y',
                                update: function(event, ui) {
                                    var dataUpdate = [];
                                    $('#listQuiz li.item-quiz').each(function() {
                                        dataUpdate.push($(this).attr('data-id'));
                                    });

                                    $('#dataTempQuiz').val(JSON.stringify(dataUpdate));

                                }
                            });
                        }

                        $('#showNoteQuiz,.panel-footer').removeClass('hide');
                        var disabledForm = (TotalAnswer > 0) ? true : false;
                        $('#formNotesForStudents,#formDuration,#btnSaveQuiz').prop('disabled', disabledForm);

                        // loading_page_modal('hide');
                    }, 500);

                }

            });

        } else {
            $('#loadQuestionListOnQuiz').html('<div style="text-align: center;">' +
                '<img src="' + base_url_js + 'images/icon/empty.jpg" style="width: 100%;max-width: 200px;" />' +
                '<h3 style="color: #9E9E9E;"><b>--- No question ---</b></h3>' +
                '</div>');
        }

    }

    function searchID(nameKey, myArray) {
        for (var i = 0; i < myArray.length; i++) {
            if (myArray[i].QID === nameKey) {
                return myArray[i].Point;
            }
        }
    }

    function countPointQuestion() {
        var dataTempQuiz = $('#dataTempQuiz').val();
        var totalPoint = 0;
        if (dataTempQuiz != '') {
            var d2 = (dataTempQuiz != '') ? JSON.parse(dataTempQuiz) : [];

            if (d2.length > 0) {
                var dataTempQuizPoint = [];
                for (var i = 0; i < d2.length; i++) {
                    var point_quiz = $('#point_quiz_' + d2[i]).val();
                    var arrPoint = {
                        ID: d2[i],
                        Point: point_quiz
                    };

                    dataTempQuizPoint.push(arrPoint);

                    point_quiz = (point_quiz != '' && point_quiz != null) ? point_quiz : 0;
                    totalPoint = totalPoint + parseFloat(point_quiz);
                }
                $('#dataTempQuizPoint').val(JSON.stringify(dataTempQuizPoint));
            }
        }

        $('#btnSaveQuiz').prop('disabled', (totalPoint != 100) ? true : false);

        $('#viewPoint').html(totalPoint);
    }

    function searchPointTemporary(ID) {
        var dataTempQuizPoint = $('#dataTempQuizPoint').val();
        var point = 0;
        if (dataTempQuizPoint != '' && dataTempQuizPoint != null) {
            var d = JSON.parse(dataTempQuizPoint);
            if (d.length > 0) {
                $.each(d, function(i, v) {
                    if (v.ID == ID) {
                        point = v.Point
                    }
                })
            }
        }
        return point;

    }
</script>