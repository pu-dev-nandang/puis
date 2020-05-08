<style>
    #tableScore thead tr th,#tableDataScore tbody tr td {
        text-align: center;
    }

    #tableScore thead tr {
        background-color: #436888;color: #ffffff;
    }

    .tbGradeC tr td{
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="well">
            <div class="row">
                <div class="col-xs-3">
                    <select id="filterSemester" class="form-control filter-score"></select>
                </div>
                <div class="col-xs-4">
                    <select id="filterBaseProdi" class="form-control filter-score">
                        <option value="">-- All Programme Study --</option>
                    </select>
                </div>
                <div class="col-xs-3">
                    <!-- 0 = Plan, 1 = waiting appr, 2 = Approval, -2 = Returned -->
                    <select class="form-control filter-score" id="filterStatusGrade">
                        <option value="">-- All Status Syllabus & RPS --</option>
                        <option value="null">Not Yet Send</option>
                        <option value="0">Returned</option>
                        <option value="1">Waiting Approval</option>
                        <option disabled>------------------------</option>
                        <option value="2" style="background: #bdf3bd;">Approved</option>
                        <option disabled>------------------------</option>
                        <option value="-2" style="background: #ffa5a5;">Not Approved</option>
                    </select>
                </div>
                <div class="col-xs-2">
                    <select class="form-control filter-score" id="filterType">
                        <option value="">-- All Status --</option>
                        <option disabled>----------------------------</option>
                        <optgroup label="Mid Exam Score">
                            <option value="11">Already Input</option>
                            <option value="10">Not Yet Input</option>
                            <option value="12">Approve</option>
                            <option value="13">Not Approve</option>
                        </optgroup>
                        <option disabled>----------------------------</option>
                        <optgroup label="Final Exam">
                            <option value="21">Already Input</option>
                            <option value="20">Not Yet Input</option>
                            <option value="22">Approve</option>
                            <option value="23">Not Approve</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
        <hr/>
    </div>


</div>
<div class="row">
    <div class="col-md-12">
        <div id="divPageScore"></div>
    </div>
</div>


<script>
    $(document).ready(function () {

        loSelectOptionSemester('#filterSemester','');

        loadSelectOptionBaseProdi('#filterBaseProdi','');


        var loadFirstPage = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            if(filterSemester!='' && filterSemester!=null){
                loadCourse();
                clearInterval(loadFirstPage);
            }
        },1000);


    });

    $(document).on('change','.filter-score',function () {
        loadCourse();
    });

    function loadCourse(){
        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){

            loading_page('#divPageScore');

            setTimeout(function () {

                var filterBaseProdi = $('#filterBaseProdi').val();
                var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null) ? filterBaseProdi.split('.')[0] : '' ;

                var filterStatusGrade = $('#filterStatusGrade').val();

                var filterType = $('#filterType').val();

                $('#divPageScore').html('<div class="">' +
                    '                <table class="table table-bordered table-striped" id="tableScore">' +
                    '                    <thead>' +
                    '                    <tr>' +
                    '                        <th rowspan="2" style="width: 1%;">No</th>' +
                    '                        <th rowspan="2">Course</th>' +
                    '                        <th rowspan="2" style="width: 9%;">Group</th>' +
                    '                        <th rowspan="2" style="width: 5%;">Credit</th>' +
                    '                        <th rowspan="2" style="width: 20%;">Lecturer</th>' +
                    '                        <th rowspan="2" style="width: 5%;">Student</th>' +
                    '                        <th colspan="2" style="width: 7%;">Mid Exam</th>' +
                    '                        <th colspan="2" style="width: 7%;">Final Exam</th>' +
                    '                        <th rowspan="2" style="width: 7%;">Action</th>' +
                    '                        <th rowspan="2" style="width: 5%;">Syllabus & RPS</th>' +
                    '                    </tr>' +
                    '                    <tr>' +
                    '                       <th style="1%;">I</th>' +
                    '                       <th style="1%;">A</th>' +
                    '                       <th style="1%;">I</th>' +
                    '                       <th style="1%;">A</th>' +
                    '                    </tr>' +
                    '                    </thead>' +
                    '                    <tbody id="trExam"></tbody>' +
                    '                </table>' +
                    '            </div>');

                var data = {
                    SemesterID : filterSemester.split('.')[0],
                    ProdiID : ProdiID,
                    StatusGrade : filterStatusGrade,
                    IsSemesterAntara : '0',
                    Type : filterType
                };

                var token = jwt_encode(data,'UAP)(*');

                var dataTable = $('#tableScore').DataTable( {
                    "processing": true,
                    "serverSide": true,
                    "iDisplayLength" : 10,
                    "ordering" : false,
                    "language": {
                        "searchPlaceholder": "Group, Course, Coordinator"
                    },
                    "ajax":{
                        url : base_url_js+"api/__getListCourseInScore", // json datasource
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

<!-- Grade -->
<script>
    $(document).on('click','.btnGrade',function () {

        var ScheduleID = $(this).attr('data-id');
        var ClassGroup = $(this).attr('data-group');

        var url = base_url_js+'api/__crudScore';

        var token = jwt_encode({action:'getGrade',ScheduleID:ScheduleID},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {

            var bodyGrade ='';
            if(jsonResult.length>0){
                var dataG = jsonResult[0];

                var silabus = (dataG.Silabus!=null && dataG.Silabus!='') ? '<a class="btn btn-block btn-default" target="_blank" href="'+base_url_portal_lecturers+'uploads/silabus/'+dataG.Silabus+'">Download Syllabus</a>' : 'Not yet upload';
                var sap = (dataG.SAP!=null && dataG.SAP!='') ? '<a class="btn btn-block btn-default" target="_blank" href="'+base_url_portal_lecturers+'uploads/sap/'+dataG.SAP+'">Download RPS</a>' : 'Not yet upload';
                var status = 'Not Yet Send Grade';
                var btnAct = 'disabled';
                var btnCheck = '';
                var appc = (dataG.Status=='0') ? 'checked' : '';
                if(dataG.Status=='1') {
                    btnAct = '';
                    btnCheck = '';
                    status = 'Waiting Approval';
                }
                else if(dataG.Status=='2') {
                    btnCheck = '';
                    status = '<i class="fa fa-check-circle" style="color: green;"></i> Approved';
                } else if(dataG.Status=='-2'){
                    btnCheck = '';
                    status = '<i class="fa fa-times-circle" style="color: darkred;"></i> Not Approved';
                }

                var reason = (dataG.ReasonNotApprove!=null && dataG.ReasonNotApprove!='') ? dataG.ReasonNotApprove : '';

                bodyGrade = '<h4>Syllabus & RPS</h4>' +
                    '                    <table class="table table-bordered tbGradeC">' +
                    '                        <tr>' +
                    '                            <td style="width: 50%;">'+silabus+'</td>' +
                    '                            <td style="width: 50%;">'+sap+'</td>' +
                    '                        </tr>' +
                    '                    </table><hr/>' +
                    '                    <h4>Score Weighted</h4>' +
                    '                    <table class="table table-bordered tbGradeC">' +
                    '                        <tr style="background: #9e9e9e3d;font-weight: bold;">' +
                    '                            <td colspan="5" style="width: 60%;">Assigment</td>' +
                    '                            <td style="width: 10%;">UTS</td>' +
                    '                            <td style="width: 10%;">UAS</td>' +
                    '                            <td style="width: 20%;">Status</td>' +
                    '                        </tr>' +
                    '                        <tr>' +
                    '                            <td colspan="5">'+dataG.Assigment+' %</td>' +
                    '                            <td rowspan="3">'+dataG.UTS+' %</td>' +
                    '                            <td rowspan="3">'+dataG.UAS+' %</td>' +
                    '                            <td rowspan="3" id="viewStatus'+dataG.ID+'">'+status+'</td>' +
                    '                        </tr>' +
                    '                        <tr style="background: #0080001f;font-weight: bold;">' +
                    '                            <td>1</td>' +
                    '                            <td>2</td>' +
                    '                            <td>3</td>' +
                    '                            <td>4</td>' +
                    '                            <td>5</td>' +
                    '                        </tr>' +
                    '                        <tr>' +
                    '                            <td>'+dataG.Assg1+' %</td>' +
                    '                            <td>'+dataG.Assg2+' %</td>' +
                    '                            <td>'+dataG.Assg3+' %</td>' +
                    '                            <td>'+dataG.Assg4+' %</td>' +
                    '                            <td>'+dataG.Assg5+' %</td>' +
                    '                        </tr>' +
                    '                        <tr>' +
                    '                            <td colspan="9">' +
                    '                               <textarea class="form-control" id="formReasonNotApprove" rows="3" placeholder="Please, input reason if you do not approve">'+reason+'</textarea>' +
                    '                           </td>' +
                    '                        </tr>' +
                    '                        <tr>' +
                    // '                            <td>Action</td>' +
                    '                            <td colspan="9" style="text-align: right;">' +
                    '                                <button data-id="'+dataG.ID+'" id="btnGradeNotApprove" class="btn btn-default btn-default-danger">Not Approved</button> | ' +
                    '                                <button data-id="'+dataG.ID+'" id="btnGradeApprove" class="btn btn-default btn-default-success" '+appc+' '+btnAct+'>Approved</button>' +
                    '                            </td>' +
                    '                        </tr>' +
                    '                    </table>' +
                    // '                    <hr/>' +
                    '                    <div class="checkbox hide">' +
                    '                        <label>' +
                    '                            <input id="checkGradeAgain" type="checkbox" value="'+dataG.ID+'" '+btnCheck+'> Berikan Akses Untuk Input Ulang Silabus & SAP' +
                    '                        </label>' +
                    '                    </div>';

            } else {
                bodyGrade = '<div style="text-align:center;"><h3>Belum Input Grade</h3></div>';
            }

            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">'+ClassGroup+'</h4>');
            $('#GlobalModal .modal-body').html(bodyGrade);


            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });

        });

    });

    $(document).on('click','#btnGradeApprove',function () {

        var ID = $(this).attr('data-id');
        loading_button('#btnGradeApprove');

        var url = base_url_js+'api/__crudScore';
        var token = jwt_encode({action:'gradeUpdate',ID:ID,Status:'2'},'UAP)(*');
        $.post(url,{token:token},function (result) {
            loadCourse();
            toastr.success('Grade Approved','Saved');
            setTimeout(function () {
                // $('#btnGradeApprove').html('Approved');
                // $('#viewStatus'+ID).html('<i class="fa fa-check-circle" style="color: green;"></i> Approved');

                $('#GlobalModal').modal('hide');
            },500);

        })
    });

    $(document).on('click','#btnGradeNotApprove',function () {
        var formReasonNotApprove = $('#formReasonNotApprove').val();

        if(formReasonNotApprove!='' && formReasonNotApprove!=null){

            loading_button('#btnGradeNotApprove');
            var ID = $(this).attr('data-id');

            var url = base_url_js+'api/__crudScore';
            var token = jwt_encode({action:'updateNotApprove',ID:ID,ReasonNotApprove:formReasonNotApprove,Status:'-2'},'UAP)(*');

            $.post(url,{token:token},function (resultBack) {
                loadCourse();
                toastr.success('Reason sent','Success');
                setTimeout(function () { $('#GlobalModal').modal('hide'); },500);
            });

        } else {
            toastr.error('Reason is required','Error');
        }

    });

    $(document).on('change','#checkGradeAgain',function () {
        var ID = $('#checkGradeAgain').val();
        var url = base_url_js+'api/__crudScore';
        var Status = '0';

        if($('#checkGradeAgain').is(':checked')){
            Status = '0';
            $('#btnGradeApprove').prop('disabled',true);
            $('#viewStatus'+ID).html('Not Yet Send Grade');
        } else {
            Status = '1';
            $('#btnGradeApprove').prop('disabled',false);
            $('#viewStatus'+ID).html('Waiting Approval');
        }

        var token = jwt_encode({action:'gradeUpdate',ID:ID,Status:Status},'UAP)(*');
        $.post(url,{token:token},function (result) {
            loadCourse();
            toastr.success('Grade Approved','Saved');
        });
    });

    $(document).on('click','#btnBackFromInputScore',function () {
        loadCourse();
    })
</script>
