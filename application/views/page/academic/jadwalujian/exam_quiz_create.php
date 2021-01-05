
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
        padding:5px;
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
</style>

<pre>
    <?php

    $ID = (count($dataQuiz)>0) ? $dataQuiz[0]['ID'] : '';
    $Title = (count($dataQuiz)>0) ? $dataQuiz[0]['Title'] : '';
    $NoteForExam = (count($dataQuiz)>0) ? $dataQuiz[0]['NoteForExam'] : '';
    $NotesForStudents = (count($dataQuiz)>0) ? $dataQuiz[0]['NotesForStudents'] : '';
//    print_r($dataQuiz);

    ?>
</pre>

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
            </div>
            <div class="panel-footer" style="text-align: right;">
                Total Point : <span id="viewPoint" style="margin-right: 7px;">0</span>
                <button class="btn btn-success" id="btnSaveQuiz">Save</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        var ID = getUrlParameter('id');
        if(ID!=''){
            getDataExist(ID);
        }
    });

    function getDataExist(ID){
        var data = {
            action : 'getQuizInThisID',
            ID : ID
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudQuiz';
        $.post(url,{token:token},function (jsonResult) {

            var details = jsonResult.Details;

            var arrQID = [];
            if(details.length>0){
                $.each(details,function (i,v) {
                    arrQID.push(v.QID);
                });
            }
            var va = (arrQID.length>0) ? JSON.stringify(arrQID) : '';
            $('#dataTempQuiz').val(va);

            var vaLoad = (arrQID.length>0) ? JSON.stringify(jsonResult) : '';
            $('#dataLoadQuiz').val(vaLoad);

            setTimeout(function () {
                loadDataQuiz();
            },2000);

        });
    }

    $('#btnSaveQuiz').click(function () {

        var Title = $('#Title').val();
        var NoteForExam = $('#NoteForExam').val();
        var NotesForStudents = $('#NotesForStudents').val();
        var dataTempQuiz = $('#dataTempQuiz').val();

        if(Title!='' && Title!=null &&
            NoteForExam!='' && NoteForExam!=null &&
            dataTempQuiz!='' && dataTempQuiz!=null){

            var d = (dataTempQuiz!='') ? JSON.parse(dataTempQuiz) : [];

            var totalPoint = 0;
            var dataForm = [];

            if(d.length>0){
                for(var i=0;i<d.length;i++){
                    var point_quiz = $('#point_quiz_'+d[i]).val();
                    point_quiz = (point_quiz!='' && point_quiz!=null) ? point_quiz : 0;
                    totalPoint = totalPoint + parseFloat(point_quiz);
                    var arr = {
                        QID : d[i],
                        Point : point_quiz
                    };
                    dataForm.push(arr);
                }
            }

            if(totalPoint!=100){
                toastr.warning('The total points must be equal to 100','Warning');
            } else {

                if(confirm('Are you sure?')){
                    var ID = $('#ID').val();
                    loading_modal_show();
                    var data = {
                        action : 'saveDataQuiz',
                        QuizID : (ID!='') ? ID : '',
                        NIP : sessionNIP,
                        ForExam : '1',
                        Title : Title,
                        NoteForExam : NoteForExam,
                        NotesForStudents : NotesForStudents,
                        dataForm : dataForm
                    };

                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'api4/__crudQuiz';

                    $.post(url,{token:token},function (jsonResult) {

                        if(jsonResult.Status==1 || jsonResult.Status=='1'){
                            toastr.success('Saved quiz','Success');
                            setTimeout(function () {
                                window.location.href='';
                            },1000);
                        } else if(jsonResult.Status==-1 || jsonResult.Status=='-1') {
                            toastr.warning(jsonResult.Message,'Warning');
                            alert(jsonResult.Message);
                            setTimeout(function () {
                                window.location.href='';
                            },1000);

                        } else if(jsonResult.Status==-2 || jsonResult.Status=='-2') {
                            toastr.warning(jsonResult.Message,'Warning');
                            loadDataQuiz();
                        }

                        setTimeout(function () {
                            loading_modal_hide();
                        },500);

                    });
                }

            }

        } else {
            toastr.warning('Please, fill in the required form','Warning');
        }


    });

    $(document).on('click','.addToQuizFromMyQuestion',function () {

        var ID = $(this).attr('data-id');
        var dataTempQuiz = $('#dataTempQuiz').val();

        var d = (dataTempQuiz!='' && dataTempQuiz!=null) ? JSON.parse(dataTempQuiz) : [];

        var pushID = true;
        if(d.length>0){
            for(var i=0;i<d.length;i++){
                if(ID==d[i]){
                    toastr.warning('The question is already in the quiz','Warning');
                    pushID = false;
                    break;
                }
            }
        }
        else {
            pushID = true;
        }

        if(pushID){
            d.push(ID);
            var newVal = JSON.stringify(d);
            $('#dataTempQuiz').val(newVal);
            loadDataQuiz();
            toastr.success('Added to quiz','Success');
        }

    });

    $(document).on('click','.btnRemoveQuestion',function () {

        var dataLoadQuiz = $('#dataLoadQuiz').val();
        var dataQ = (dataLoadQuiz!='' && dataLoadQuiz!=null) ? JSON.parse(dataLoadQuiz) : [];
        var TotalAnswer = (dataLoadQuiz!='' && dataLoadQuiz!=null
            && dataQ.Quiz.length>0) ? parseInt(dataQ.TotalAnswer) : 0;

        if(TotalAnswer>0){
            toastr.warning('Quiz cannot be edited','Warning');
        } else {
            if(confirm('Are you sure?')){

                var ID = $(this).attr('data-id');
                var dataTempQuiz = $('#dataTempQuiz').val();
                var d = JSON.parse(dataTempQuiz);

                var newArr = [];
                if(d.length>0){
                    for(var i=0;i<d.length;i++){
                        if(d[i]!=ID){
                            newArr.push(d[i]);
                        }

                    }
                }

                if(newArr.length>0){
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

    $('#btnAddQuestion').click(function () {
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
            action : 'getMasterQuestion',
            NIP : sessionNIP,
            Portal : 'pcam'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudQuiz';

        var dataTable = $('#dataTableMasterQuestion').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Question . . ."
            },
            "responsive" : true,
            "ajax":{
                url : url, // json datasource
                ordering : false,
                data : {token:token},
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );

        $('#GlobalModalLarge').modal({
            'backdrop' : 'static',
            'show' : true
        });
    });

    $(document).on('keyup','.form-quiz-point',function () {

        var va = $(this).val();
        var d = parseFloat(va);

        if(d<=0){
            $(this).val(1);
        } else if(d>100){
            $(this).val(100);
        } else {
            $(this).val(d);
        }

        // Count point
        countPointQuestion();

    });



    function loadDataQuiz() {
        var dataTempQuiz = $('#dataTempQuiz').val();
        loading_page('#loadQuestionListOnQuiz');

        if(dataTempQuiz!='' && dataTempQuiz!=null) {
            var d = (dataTempQuiz!='') ? JSON.parse(dataTempQuiz) : [];

            var dataLoadQuiz = $('#dataLoadQuiz').val();
            var dataQ = (dataLoadQuiz!='' && dataLoadQuiz!=null) ? JSON.parse(dataLoadQuiz) : [];
            var TotalAnswer = 0;

            if(dataLoadQuiz!='' && dataLoadQuiz!=null && dataQ.Quiz.length>0){
                TotalAnswer = parseInt(dataQ.TotalAnswer);
                $('#formNotesForStudents').val(dataQ.Quiz[0].NotesForStudents);
                $('#viewPoint').html('100');
                $('#btnSaveQuiz').prop('disabled',false);
            }

            var data = {
                action : 'getArrDataQuestion',
                ArrQID : d
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api4/__crudQuiz';

            var disabled = (TotalAnswer>0) ? 'disabled' : '';

            $.post(url,{token:token},function (jsonResult) {

                if (jsonResult.length>0){

                    var listQuestoin = '';
                    $.each(jsonResult,function (i,v) {

                        var o = v.Option;
                        var listOpt = '';
                        if(o.length>0){
                            $.each(o,function (i2,v2) {
                                var isAns = (v2.IsTheAnswer==1 || v2.IsTheAnswer=='1')
                                    ? '<i class="fa fa-check-circle" style="color: green;margin-right:5px;"></i>'
                                    : '';

                                var pointBBG = (v2.Point!='' && v2.Point!=null && parseFloat(v2.Point)>0)
                                    ? '#7CB342' : '#E57373';


                                var viewPoint = (v2.Point!='' && v2.Point!=null) ? '<span class="lbl-point" style="background: '+pointBBG+'; ">'+v2.Point+'</span>' : '';
                                listOpt = listOpt + '<li style="line-height: 1.428571;margin-bottom: 0px;">'+viewPoint+isAns+v2.Option+'</li>';
                            });
                        }

                        var q = v.Question;
                        var expandDetail = (q.QTID==1 || q.QTID==2) ?
                            '                            <div class="collapse" id="collapseExample_'+i+'">' +
                            '                                <hr/>' +
                            '                                        <div class="well">' +
                            '                                            <ul>'+listOpt+'</ul>' +
                            '                                        </div>' +
                            '                            </div>'
                            : '';


                        var alertManualCorrection = (q.QTID==3)
                            ? '<div class="alert alert-warning alert-essay" role="alert">' +
                            '<i class="fa fa-exclamation-triangle margin-right"></i> Need manual correction</div>' : '';

                        var valuePoint = '';
                        var dataTempQuizPoint = $('#dataTempQuizPoint').val();
                        if(dataTempQuizPoint!=''){
                            valuePoint = searchPointTemporary(q.ID);
                        } else if(dataLoadQuiz!='' && dataLoadQuiz!=null && dataQ.Details.length>0){
                            valuePoint = searchID(q.ID,dataQ.Details);
                        }

                        var viewQuestion = (v.Status==1 || v.Status=='1')
                            ? '<span class="lbl-'+q.QTID+'">'+q.Type+'</span> - '+q.Question
                            : '<div class="alert alert-danger" style="margin-bottom: 0px;">Question is outdated, please <b>delete</b> it immediately</div>';

                        listQuestoin = listQuestoin + '<li class="item-quiz" data-id="'+q.ID+'">' +
                            '                            <a role="button" data-toggle="collapse" href="#collapseExample_'+i+'" aria-expanded="true" aria-controls="collapseExample">'+viewQuestion+'</a>' +
                            '                           <div class="panel-act-question-in-quiz">' +
                            '                                   <input class="form-control form-quiz-point" value="'+valuePoint+'" id="point_quiz_'+q.ID+'" placeholder="Point..." type="number" '+disabled+' style="max-width: 100px;display: inline;" >' +
                            '                                   <button class="btn btn-danger btn-sm btnRemoveQuestion" data-id="'+v.QID+'" '+disabled+'><i class="fa fa-trash"></i></button>' +
                            '                           </div>' +expandDetail+alertManualCorrection+
                            '                        </li>';

                    });

                    setTimeout(function () {
                        $('#loadQuestionListOnQuiz').html('<ol id="listQuiz">'+listQuestoin+'</ol>');
                        if(TotalAnswer<=0){
                            $('#listQuiz').sortable({
                                axis: 'y',
                                update: function (event, ui) {
                                    var dataUpdate = [];
                                    $('#listQuiz li.item-quiz').each(function () {
                                        dataUpdate.push($(this).attr('data-id'));
                                    });

                                    $('#dataTempQuiz').val(JSON.stringify(dataUpdate));

                                }
                            });
                        }

                        $('#showNoteQuiz,.panel-footer').removeClass('hide');
                        var disabledForm = (TotalAnswer>0) ? true : false;
                        $('#formNotesForStudents,#formDuration,#btnSaveQuiz').prop('disabled',disabledForm);

                        // loading_page_modal('hide');
                    },500);

                }

            });

        } else {
            $('#loadQuestionListOnQuiz').html('<div style="text-align: center;">' +
                '<img src="'+base_url_js+'images/icon/empty.jpg" style="width: 100%;max-width: 200px;" />' +
                '<h3 style="color: #9E9E9E;"><b>--- No question ---</b></h3>' +
                '</div>');
        }

    }

    function searchID(nameKey, myArray){
        for (var i=0; i < myArray.length; i++) {
            if (myArray[i].QID === nameKey) {
                return myArray[i].Point;
            }
        }
    }

    function countPointQuestion(){
        var dataTempQuiz = $('#dataTempQuiz').val();
        var totalPoint = 0;
        if(dataTempQuiz!='') {
            var d2 = (dataTempQuiz != '') ? JSON.parse(dataTempQuiz) : [];

            if(d2.length>0){
                var dataTempQuizPoint = [];
                for(var i=0;i<d2.length;i++){
                    var point_quiz = $('#point_quiz_'+d2[i]).val();
                    var arrPoint = {
                        ID : d2[i],
                        Point : point_quiz
                    };

                    dataTempQuizPoint.push(arrPoint);

                    point_quiz = (point_quiz!='' && point_quiz!=null) ? point_quiz : 0;
                    totalPoint = totalPoint + parseFloat(point_quiz);
                }
                $('#dataTempQuizPoint').val(JSON.stringify(dataTempQuizPoint));
            }
        }

        $('#btnSaveQuiz').prop('disabled',(totalPoint!=100) ? true : false);

        $('#viewPoint').html(totalPoint);
    }

    function searchPointTemporary(ID) {
        var dataTempQuizPoint = $('#dataTempQuizPoint').val();
        var point = 0;
        if(dataTempQuizPoint!='' && dataTempQuizPoint!=null){
            var d = JSON.parse(dataTempQuizPoint);
            if(d.length>0){
                $.each(d,function (i,v) {
                    if(v.ID==ID){
                        point = v.Point
                    }
                })
            }
        }
        return point;

    }



</script>