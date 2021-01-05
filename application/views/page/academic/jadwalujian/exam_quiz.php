

<div class="row">
    <div class="col-md-12" style="margin-bottom: 20px;text-align: right;">
        <a href="<?= base_url('academic/exam-schedule/exam-quiz-create'); ?>" target="_blank" class="btn btn-success">Create Exam Quiz</a>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">List Quiz</h4>
            </div>
            <div class="panel-body">
                <div id="divTable"></div>
            </div>
        </div>
    </div>
</div>




<script>

    $(document).ready(function () {
        getListQuiz();
    });
    
    function getListQuiz() {

        $('#divTable').html('<table id="tableShowingData" class="table table-bordered table-centre">' +
            '                    <thead>' +
            '                    <tr>' +
            '                        <th style="width: 3%;">No</th>' +
            '                        <th>Title</th>' +
            '                        <th style="width: 10%;">' +
            '                            <i class="fa fa-cog"></i>' +
            '                        </th>' +
            '                        <th style="width: 3%;">Question</th>' +
            '                        <th style="width: 3%;">Link</th>' +
            '                    </tr>' +
            '                    </thead>' +
            '                </table>');

        var data = {
            action : 'getQuizExam'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudQuiz';

        window.dataTable = $('#tableShowingData').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Day, Room, Name / NIP Invigilator"
            },
            "ajax":{
                url : url, // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );

    }

    $(document).on('click','.addExamSch',function () {

        var ID = $(this).attr('data-id');

        $('#GlobalModalXtraLarge .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Exam Schedule</h4>');

        var htmlss = '<div class="row">' +
            '    <div class="col-md-8" style="border-right: 1px solid #ccc;">' +
            '        <div class="row" style="margin-bottom: 15px;">' +
            '            <div class="col-md-4">' +
            '                <select id="filterSemester" class="form-control form-filter-list-exam">' +
            '                </select>' +
            '            </div>' +
            '            <div class="col-md-4">' +
            '                <select id="filterExam" class="form-control form-filter-list-exam">' +
            '                    <option value="uts">UTS</option>' +
            '                    <option value="uas">UAS</option>' +
            '                </select>' +
            '            </div>' +
            '            <div class="col-md-4">' +
            '                <select class="form-control" id="form2PDFDate"></select>' +
            '            </div>' +
            '        </div>' +
            '        <div id="row"><div class="col-md-12">' +
            '           <div id="divTable"></div></div></div>' +
            '    </div>' +
            '    <div class="col-md-4">' +
            '        <input id="QuizIDinModal" class="hide" value="'+ID+'" />' +
            '        <div id="showListExamRegistred"></div>' +
            '    </div>' +
            '</div>';

        $('#GlobalModalXtraLarge .modal-body').html(htmlss);

        loSelectOptionSemester('#filterSemester','');

        var loadFirst = setInterval(function () {

            var filterSemester = $('#filterSemester').val();
            var form2PDFDate = $('#form2PDFDate').val();
            if(filterSemester!='' && filterSemester!=null && form2PDFDate==null){
                // loadClassGroup();
                load__DateExam();
                clearInterval(loadFirst);
            }

        },1000);

        $('.form-filter-list-exam').change(function () {
            load__DateExam();
        });

        $('#form2PDFDate').change(function () {
            loadDataExam();
        });

        loadListQuizInExam();

        $('#GlobalModalXtraLarge .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModalXtraLarge').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

    $(document).on('click','.btnActAddToQuiz',function () {
        var ExamID = $(this).attr('data-id');
        var QuizID = $('#QuizIDinModal').val();

        var data = {
            action : 'addingExamInQuiz',
            ExamID : ExamID,
            QuizID : QuizID
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudQuiz';

        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.Status==1 || jsonResult.Status=='1'){
                toastr.success('Data saved','Success');
                loadListQuizInExam();
            } else {
                toastr.warning('Exam has been registered in the quiz','Warning');
            }

        });


    });

    function loadListQuizInExam() {

        var ID = $('#QuizIDinModal').val();

        var data = {
            action : 'getExamInQuiz',
            ID : ID
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudQuiz';

        loading_page('#showListExamRegistred');

        $.post(url,{token:token},function (jsonResult) {

            var listExamReg = '<tr>' +
                '<td colspan="3">No data</td>' +
                '</tr>';
            var Title = '';
            var NoteForExam = '';
            if(jsonResult.length>0){

                listExamReg = '';
                Title = jsonResult[0].Title;
                NoteForExam = jsonResult[0].NoteForExam;

                var ListXeam = jsonResult[0].ListXeam;

                 $.each(ListXeam,function (i,v) {

                     var btnRemove = '<button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>';

                     var exam = '';
                     if(v.Detail.length>0){
                         $.each(v.Detail,function (i2,v2) {
                             var koma = (i2==0 || i2==(v.Detail.length - 1)) ? '' : ', ';
                             exam = v2.ClassGroup+' | '+exam+koma+''+v2.CourseEng;
                         })
                     }

                     var examTime = (v.ExamStart!=null && v.ExamStart!='')
                         ? ' '+v.ExamStart.substr(0,5)+' '+v.ExamEnd.substr(0,5) : '';
                     var examDate = (v.ExamDate!=null && v.ExamDate!='')
                         ? '<br/>Exam Date : '+moment(v.ExamDate).format('DD MMM YYYY') : '';

                     listExamReg = listExamReg+'<tr>' +
                         '<td>'+(i+1)+'</td>' +
                         '<td style="text-align: left;">' +
                         '  '+exam+examDate+examTime+' ' +
                         '</td>' +
                         '<td>'+btnRemove+'</td>' +
                         '</tr>';


                 });

            }

            setTimeout(function () {
                $('#showListExamRegistred').html('' +
                    '<di>' +
                    '    <table class="table table-bordered" style="margin-bottom:20px !important;">' +
                    '        <tr>' +
                    '            <td style="border-bottom:none;"><b>Title : </b></td>' +
                    '        </tr><tr><td style="border-top:none;">'+Title+'</td></tr>' +
                    '        <tr>' +
                    '            <td style="border-bottom:none;"><b>Note For Exam : </b></td>' +
                    '        </tr><tr><td style="border-top:none;">'+NoteForExam+'</td></tr>' +
                    '    </table>' +
                    '</di>' +
                    '<div>' +
                    '    <table class="table table-bordered table-centre table-striped">' +
                    '        <thead>' +
                    '        <tr style="background: #eceff1;">' +
                    '            <td style="width: 1%;">No</td>' +
                    '            <td>Exam</td>' +
                    '            <td style="width: 10%;"><i class="fa fa-cog"></i></td>' +
                    '        </tr>' +
                    '        </thead>' +
                    '        <tbody>'+listExamReg+'</tbody>' +
                    '    </table>' +
                    '</div>');
            },500);

            console.log('datanya',jsonResult);

        });

    }

    function load__DateExam() {
        var filterSemester = $('#filterSemester').val();
        var filterExam = $('#filterExam').val();
        if(filterSemester!='' && filterSemester!=null){
            var url = base_url_js+'api/__crudJadwalUjian';
            var token = jwt_encode({action:'checkDateExam',SemesterID : filterSemester.split('.')[0],Type : filterExam},'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {

                $('#form2PDFDate').empty();
                $('#form2PDFDate').append('<option value="">-- All Date --</option>');

                if(jsonResult.length>0){
                    for(var i=0;i<jsonResult.length;i++){
                        var d = jsonResult[i];
                        $('#form2PDFDate').append('<option value="'+moment(d).format('YYYY-MM-DD')+'">'+moment(d).format('ddd, DD MMM YYYY')+'</option>');
                    }
                }

                // if(jsonResult.utsStart!=null && jsonResult.utsStart!=''){
                //     var filterExam = $('#filterExam').val();
                //     var start = (filterExam=='UTS' || filterExam=='uts') ? jsonResult.utsStart : jsonResult.uasStart;
                //     var end = (filterExam=='UTS' || filterExam=='uts') ? jsonResult.utsEnd : jsonResult.uasEnd;
                //     var rangeDate = momentRange(start,end);
                //     if(typeof rangeDate.details !== undefined){
                //
                //     }
                //
                // }

                loadDataExam();

            });
        }

    }

    function loadDataExam() {

        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null) {

            var form2PDFDate = $('#form2PDFDate').val();
            loading_page('#divTable');


            setTimeout(function () {
                $('#divTable').html('<div class="">' +
                    '                <table class="table table-bordered table-centre" id="tableShowExam">' +
                    '                    <thead>' +
                    '                    <tr style="background: #437e88;color: #ffffff;">' +
                    '                        <th style="width: 1%;">No</th>' +
                    '                        <th>Course</th>' +
                    '                        <th style="width: 20%;">Invigilator</th>' +
                    '                        <th style="width: 5%;">Student</th>' +
                    '                        <th style="width: 5%;">Action</th>' +
                    '                        <th style="width: 15%;">Day, Date ,Time</th>' +
                    '                        <th style="width: 7%;">Room</th>' +
                    '                    </tr>' +
                    '                    </thead>' +
                    '                    <tbody id="trExam"></tbody>' +
                    '                </table>' +
                    '            </div>');

                var filterExam = $('#filterExam').val();
                var filterBaseProdi = $('#filterBaseProdi').val();
                var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null) ? filterBaseProdi.split('.')[0] : '';

                var data = {
                    action : 'showDataExam',
                    SemesterID : filterSemester.split('.')[0],
                    Semester : $('#filterSemester option:selected').text(),
                    ProdiID : ProdiID,
                    ExamDate : form2PDFDate,
                    Type : filterExam,
                    ForQuiz : '1',
                };

                var token = jwt_encode(data,'UAP)(*');

                window.dataTable = $('#tableShowExam').DataTable( {
                    "processing": true,
                    "serverSide": true,
                    "iDisplayLength" : 10,
                    "ordering" : false,
                    "language": {
                        "searchPlaceholder": "Day, Room, Name / NIP Invigilator"
                    },
                    "ajax":{
                        url : base_url_js+"api/__getScheduleExam", // json datasource
                        data : {token:token},
                        ordering : false,
                        type: "post",  // method  , by default get
                        error: function(){  // error handling
                            $(".employee-grid-error").html("");
                            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                            $("#employee-grid_processing").css("display","none");
                        }
                    }
                } );

                // === Load Filter


            },500);

        }

    }

</script>