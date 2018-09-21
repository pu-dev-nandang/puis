

<style>
    #tableShowExam>thead>tr>th, #tableExam>tbody>tr>td {
        text-align: center;
    }

    #tableStudent thead tr th {
        text-align: center;
        background: #005975;
        color: #ffffff;
    }
    #tableStudent tbody tr td {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-6">
        <div class="well" style="margin-bottom: 10px;">
            <div class="row">
                <div class="col-xs-4" style="">
                    <select id="filterSemester" class="form-control form-filter-list-exam">
                    </select>
                </div>
                <div class="col-xs-3" style="">
                    <select id="filterExam" class="form-control form-filter-list-exam">
                        <option value="uts">UTS</option>
                        <option value="uas">UAS</option>
                    </select>
                </div>
                <div class="col-xs-5">
                    <select class="form-control" id="form2PDFDate"></select>
                </div>

            </div>
        </div>
        <hr/>

    </div>

    <div class="col-md-4">
        <div class="well" style="margin-bottom: 10px;min-height: 20px;">
            <div class="row">
                <div class="col-xs-7">
                    <select class="form-control" id="formPDFTypeDocument">
                        <option value="5">Tamplate Map Soal</option>
                        <option value="1">Berita Acara Penyerahan</option>
                        <option value="2">Berita Acara Pelaksanaan Ujian</option>
                        <option value="3">Exam Attendance</option>
                        <option disabled>-----------------------</option>
                        <option value="4">Pengawas</option>
                    </select>
                </div>
                <div class="col-xs-5">
                    <button class="btn btn-default btn-block btn-default-success" id="btnSavePDFDocument">Download to PDF</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="thumbnail" style="padding: 15px;">
            <div class="row">
                <div class="col-xs-12">
                    <div class="checkbox checbox-switch switch-primary">
                        <label>
                            <input type="checkbox"id="layoutExam" />
                            <span></span>
                            <b> | Random Layout</b>
                        </label>
                    </div>
                </div>
            </div>
        </div>
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
            var form2PDFDate = $('#form2PDFDate').val();
            if(filterSemester!='' && filterSemester!=null && form2PDFDate==null){
                load__DateExam();
                clearInterval(loadFirst);
            }

        },1000);

        loadConfigLayout();

    });

    // ==== Button Show Student ====

    $(document).on('click','.btnShowDetailStdExam',function () {

        var ExamID = $(this).attr('data-examid');

        var token = jwt_encode({action:'readDetailStudent', ExamID : ExamID},'UAP)(*');
        var url = base_url_js+'api/__crudJadwalUjian';

        $.post(url,{token:token},function (jsonResult) {
            var dataHtml = '<div style="text-align:center;"><h3>--- Data not yet ---</h3></div>';
            console.log(jsonResult);
            if(jsonResult.length>0){

                dataHtml = '<table class="table table-bordered" id="tableStudent">' +
                    '    <thead>' +
                    '    <tr>' +
                    '        <th rowspan="2" style="width: 2%;">N0</th>' +
                    '        <th rowspan="2">Name</th>' +
                    '        <th rowspan="2" style="width: 13%;">Attd</th>' +
                    '        <th colspan="2" style="width: 35">Payment</th>' +
                    '    </tr>' +
                    '    <tr>' +
                    '       <th style="width: 17%">BPP</th>' +
                    '       <th style="width: 17%">Credit</th>' +
                    '   </tr>' +
                    '    </thead>' +
                    '    <tbody id="dataMHSExam"></tbody>' +
                    '</table>' +
                    '';
                $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                    '<h4 class="modal-title">Details Student</h4>');
                $('#GlobalModal .modal-body').html(dataHtml);
                $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

                var no_std = 1;
                for(var i=0;i<jsonResult.length;i++){
                    var d = jsonResult[i];

                    var BPP = '<span style="color: #ff9800;" ><i class="fa fa-exclamation-triangle margin-right"></i> Unset</span>';
                    var Credit = '<span style="color: #ff9800;" ><i class="fa fa-exclamation-triangle margin-right"></i> Unset</span>';

                    if(d.DetailPayment.BPP.Status==1 || d.DetailPayment.BPP.Status=='1'){
                        BPP = '<span style="color: green;"><i class="fa fa-check-circle margin-right"></i> Paid</span>';
                    } else if(d.DetailPayment.BPP.Status==0 || d.DetailPayment.BPP.Status=='0'){
                        BPP = '<span style="color:red;"><i class="fa fa-times-circle margin-right"></i> Unpaid</span>';
                    }

                    if(d.DetailPayment.Credit.Status==1 || d.DetailPayment.Credit.Status=='1'){
                        Credit = '<span style="color: green;"><i class="fa fa-check-circle margin-right"></i> Paid</span>';
                    } else if(d.DetailPayment.Credit.Status==0 || d.DetailPayment.Credit.Status=='0'){
                        Credit = '<span style="color:red;"><i class="fa fa-times-circle margin-right"></i> Unpaid</span>';
                    }

                    var AttdPercentage = (typeof  d.DetailAttendance.Percentage !== undefined
                        && d.DetailAttendance.Percentage!=null && d.DetailAttendance.Percentage!='') ? d.DetailAttendance.Percentage : 0;
                    $('#dataMHSExam').append('<tr>' +
                        '<td>'+(no_std++)+'</td>' +
                        '<td style="text-align: left;"><b>'+d.Name+'</b><br/>'+d.NPM+'</td>' +
                        '<td>'+AttdPercentage.toFixed()+' %</td>' +
                        '<td>'+BPP+'</td>' +
                        '<td>'+Credit+'</td>' +
                        '</tr>');
                }

                $('#GlobalModal').modal({
                    'show' : true,
                    'backdrop' : 'static'
                });

            }

        });
    });

    // ==========================
    
    function loadConfigLayout() {

        var token = jwt_encode({action:'readConfig',ConfigID:1},'UAP)(*');
        var url = base_url_js+'api/__crudConfig';
        $.post(url,{token:token},function (jsonResult) {
            var c = (jsonResult[0].Status==1 || jsonResult[0].Status=='1') ? 'checked' : '';
            $('#layoutExam').prop('checked',c);
        });
    }

    $('#layoutExam').change(function () {

        var status = ($('#layoutExam').is(':checked')) ? '1' : '0';

        var token = jwt_encode({action:'updateConfig',ConfigID:1,Status:status},'UAP)(*');
        var url = base_url_js+'api/__crudConfig';
        $.post(url,{token:token},function (result) {
            toastr.success('Data Saved','Success');
        });
    });

    $('.form-filter-list-exam').change(function () {
        load__DateExam();
    });

    $('#form2PDFDate').change(function () {
        loadDataExam();
    });

    // === btn cetak pdf ===

    $('#btnSavePDFDocument').click(function () {

        var filterSemester = $('#filterSemester').val();
        var filterExam = $('#filterExam').val();
        var form2PDFDate = $('#form2PDFDate').val();
        var formPDFTypeDocument = $('#formPDFTypeDocument').val();

        if(filterSemester!='' && filterSemester!=null && filterExam!='' && filterExam!= null
        && form2PDFDate!='' && form2PDFDate!=null && formPDFTypeDocument!='' && formPDFTypeDocument!=null ){
            var data = {
                SemesterID : filterSemester.split('.')[0],
                Semester : $('#filterSemester option:selected').text(),
                Type : filterExam,
                ExamDate : form2PDFDate,
                DocumentType : formPDFTypeDocument
            };
            var token = jwt_encode(data,'UAP)(*');
            $('#form2savePDF_Exam').attr('action',base_url_js+'save2pdf/filterDocument');
            $('#formAreaPDF_Exam').val(token);

            $('#form2savePDF_Exam').submit();
        }

    });

    // ====================

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

    function load__DateExam() {
        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){
            var url = base_url_js+'api/__crudJadwalUjian';
            var token = jwt_encode({action:'checkDateExam',SemesterID : filterSemester.split('.')[0]},'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {

                $('#form2PDFDate').empty();
                $('#form2PDFDate').append('<option value="">-- All Date --</option>');
                if(jsonResult.utsStart!=null && jsonResult.utsStart!=''){
                    var filterExam = $('#filterExam').val();
                    var start = (filterExam=='UTS' || filterExam=='uts') ? jsonResult.utsStart : jsonResult.uasStart;
                    var end = (filterExam=='UTS' || filterExam=='uts') ? jsonResult.utsEnd : jsonResult.uasEnd;
                    var rangeDate = momentRange(start,end);
                    if(typeof rangeDate.details !== undefined){
                        for(var i=0;i<rangeDate.details.length;i++){
                            var d = rangeDate.details[i];
                            $('#form2PDFDate').append('<option value="'+moment(d).format('YYYY-MM-DD')+'">'+moment(d).format('dddd, DD MMM YYYY')+'</option>');
                        }
                    }

                }

                loadDataExam();

            });
        }

    }

    function loadDataExam() {
        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){

            var form2PDFDate = $('#form2PDFDate').val();


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
                    '                        <th style="width: 15%;">Day, Date ,Time</th>' +
                    '                        <th style="width: 7%;">Room</th>' +
                    '                        <th style="width: 15%;">Insert by</th>' +
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
            },500);


        }
    }
</script>