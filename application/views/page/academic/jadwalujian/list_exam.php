

<style>
    #tableShowExam>thead>tr>th, #tableExam>tbody>tr>td {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="thumbnail" style="margin-bottom: 10px;">
            <div class="row">
                <div class="col-xs-9" style="">
                    <select id="filterSemester" class="form-control form-filter-list-exam">
                    </select>
                </div>
                <div class="col-xs-3" style="">
                    <select id="filterExam" class="form-control form-filter-list-exam">
                        <option value="uts">UTS</option>
                        <option value="uas">UAS</option>
                    </select>
                </div>
<!--                <div class="col-xs-5" style="">-->
<!--                    <select id="filterBaseProdi" class="form-control form-filter">-->
<!--                        <option value="">-- All Programme Study --</option>-->
<!--                    </select>-->
<!--                </div>-->

            </div>
        </div>
        <hr/>

    </div>
</div>


<div class="row">
    <div class="col-md-12" style="min-height: 150px;">
        <div id="divTable"></div>
    </div>
</div>

<form id="form2savePDF_Exam" action="" target="_blank" hidden method="post">
    <textarea id="formAreaPDF_Exam" class="hide" hidden readonly name="token"></textarea>
</form>


<script>
    $(document).ready(function () {
        // loadSelectOptionBaseProdi('#filterBaseProdi','');
        loSelectOptionSemester('#filterSemester','');

        var loadFirst = setInterval(function () {

            var filterSemester = $('#filterSemester').val();
            if(filterSemester!='' && filterSemester!=null){
                loadDataExam();
                clearInterval(loadFirst);
            }

        },1000);

    });

    $('.form-filter-list-exam').change(function () {
        loadDataExam();
    });

    $(document).on('click','.btnDeleteExam',function () {

        var ExamID = $(this).attr('data-id');

        $('#NotificationModal .modal-header').addClass('hide');
        $('#NotificationModal .modal-body').html('<div style="text-align: center;">' +
            '<h4>Delete exam schedule ?</h4>' +
            '<hr/>' +
            '<button type="button" class="btn btn-danger" data-id="'+ExamID+'" id="btnDeleteExam">Yes</button> | ' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button> ' +
            '</div>');
        $('#NotificationModal .modal-footer').addClass('hide');
        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });
    });

    $(document).on('click','#btnDeleteExam',function () {

        loading_buttonSm('#btnDeleteExam');
        $('.btn-default[data-dismiss=modal]').prop('disabled',true);

        var ExamID = $(this).attr('data-id');
        var token = jwt_encode({action : 'deleteExamInExamList', ExamID : ExamID},'UAP)(*');
        var url = base_url_js+'api/__crudJadwalUjian';
        $.post(url,{token:token},function (result) {
            loadDataExam();
            setTimeout(function () {
                $('#NotificationModal').modal('hide');
            },500);
        });

    });

    $(document).on('click','.btnSave2PDF_Exam',function () {
        var token = $(this).attr('data-token');
        var url = $(this).attr('data-url');

        $('#form2savePDF_Exam').attr('action',base_url_js+''+url);
        $('#formAreaPDF_Exam').val(token);

        $('#form2savePDF_Exam').submit();

    });
    
    function loadDataExam() {
        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){

            loading_page('#divTable');


            setTimeout(function () {
                $('#divTable').html('<div class="">' +
                    '                <table class="table table-bordered" id="tableShowExam">' +
                    '                    <thead>' +
                    '                    <tr style="background: #437e88;color: #ffffff;">' +
                    '                        <th style="width: 1%;">No</th>' +
                    '                        <th>Course</th>' +
                    '                        <th style="width: 20%;">Pengawas</th>' +
                    '                        <th style="width: 5%;">Student</th>' +
                    '                        <th style="width: 5%;">Action</th>' +
                    '                        <th style="width: 20%;">Date</th>' +
                    '                        <th style="width: 10%;">Time</th>' +
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
                    Type : filterExam
                };

                var token = jwt_encode(data,'UAP)(*');

                var dataTable = $('#tableShowExam').DataTable( {
                    "processing": true,
                    "serverSide": true,
                    "iDisplayLength" : 10,
                    "ordering" : false,
                    "language": {
                        "searchPlaceholder": "Day, Room, Name / NIP Pengawas"
                    },
                    "ajax":{
                        url : base_url_js+"api/__getScheduleExam?token="+token, // json datasource
                        ordering : false,
                        type: "post",  // method  , by default get
                        error: function(){  // error handling
                            $(".employee-grid-error").html("");
                            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                            $("#employee-grid_processing").css("display","none");
                        }
                    }
                } );
            },500);


        }
    }
</script>