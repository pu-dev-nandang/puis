
<style>
    #tableListStd thead tr th {
        text-align: center;
        background-color: #436888;
        color: #ffffff;
    }
    #tableListStd tbody tr td {
        text-align: center;
    }
    #tableProdi tr th, #tableProdi tr td {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-4">
        <div class="well">
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-block btn-default btn-default-primary" id="btnLimitCredit" disabled>Set Limit Credit</button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-block btn-default btn-default-warning" id="btnDefaultCredit">Set Default Credit</button>
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-8">
        <div class="well">
            <div class="row">
                <div class="col-md-2">
                    <select class="form-control filterSP" id="filterProgramCampus"></select>
                </div>
                <div class="col-md-3">
                    <select class="form-control filterSP" id="filterSemester"></select>
                </div>
                <div class="col-md-3">
                    <select class="form-control filterSP" id="filterCurriculum">
                        <option value="">--- All Curriculum ---</option>
                        <option disabled>-----------------------------</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control filterSP" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                        <option disabled>-----------------------------</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr/>
        <div id="loadTable"></div>
    </div>
</div>




<script>
    $(document).ready(function () {

        window.SemesterAntara = 0;

        loadSelectOptionProgramCampus('#filterProgramCampus','');
        loSelectOptionSemester('#filterSemester','');
        loadSelectOptionCurriculumNoSelect('#filterCurriculum','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');

        var loadFirst = setInterval(function () {

            var filterSemester = $('#filterSemester').val();
            if(filterSemester!='' && filterSemester!=null){
                getStudents();
                $('#btnLimitCredit').attr('data-semesterid',filterSemester.split('.')[0]);
                $('#btnLimitCredit').prop('disabled',false);

                clearInterval(loadFirst);

            }

        },1000);

    });

    $(document).on('change','.filterSP',function () {
        getStudents();
    });

    function getStudents() {

        var filterProgramCampus = $('#filterProgramCampus').val();
        var filterSemester = $('#filterSemester').val();


        if(filterProgramCampus!='' && filterProgramCampus!=null
            && filterSemester!='' && filterSemester!=null){


            $('#loadTable').html('<table class="table table-bordered table-striped" id="tableListStd">' +
                '            <thead>' +
                '            <tr>' +
                '                <th rowspan="2" style="width: 3%;">No</th>' +
                '                <th rowspan="2" style="width: 15%;">Students</th>' +
                '                <th rowspan="2" style="width: 15%;">Mentor</th>' +
                '                <th colspan="2">Payment</th>' +
                '                <th rowspan="2">Course</th>' +
                '                <th rowspan="2" style="width: 7%;">Credit</th>' +
                '                <th rowspan="2" style="width: 5%;">Action</th>' +
                '                <th rowspan="2" style="width: 5%;">Batal Tambah</th>' +
                '            </tr>' +
                '            <tr>' +
                '                <th style="width: 5%;">BPP</th>' +
                '                <th style="width: 5%;">Credit</th>' +
                '            </tr>' +
                '            </thead>' +
                '            <tbody></tbody>' +
                '        </table>');

            var filterBaseProdi = $('#filterBaseProdi').val();
            var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null) ? filterBaseProdi.split('.')[0] : '';

            var filterCurriculum = $('#filterCurriculum').val();
            var Year = (filterCurriculum!='' && filterCurriculum!=null) ? filterCurriculum.split('.')[1] : '';

            var exSemester = filterSemester.split('.');
            var data = {
                ProgramID : filterProgramCampus,
                SemesterID : exSemester[0],
                Year : Year,
                ProdiID : ProdiID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__getDataStudyPlanning';

            var dataTable = $('#tableListStd').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "NIM , Name Student"
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


    }

    function getStudents2() {

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
</script>


<!-- SET LIMIT -->
<script>

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

<!-- Set Default Credit -->
<script>
    $('#btnDefaultCredit').click(function () {

        var token = jwt_encode({action:'viewAllProdi'},'UAP)(*');
        var url = base_url_js+'api3/__crudAllProgramStudy';

        $.post(url,{token:token},function (jsonResult) {

            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Programme Study</h4>');

            $('#GlobalModal .modal-body').html('<div class="">' +
                '    <table class="table table-striped" id="tableProdi">' +
                '        <thead>' +
                '        <tr>' +
                '            <th style="width: 1%;">No</th>' +
                '            <th>Programme Study</th>' +
                '            <th style="width: 15%;">Credit Semester 1</th>' +
                '        </tr>' +
                '        </thead>' +
                '        <tbody id="listProdi"></tbody>' +
                '    </table>' +
                '</div>');

            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {
                    $('#listProdi').append('<tr>' +
                        '<td style="border-right: 1px solid #CCCCCC;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;"><b>'+v.NameEng+'</b><br/>'+v.Name+'</td>' +
                        '<td><input class="form-control inputUpdate" data-id="'+v.ID+'" type="number" value="'+v.DefaultCredit+'"></td>' +
                        '</tr>');
                });
            }

            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button class="btn btn-success" id="btnSaveP">Save</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
            
            $('#btnSaveP').click(function () {

                loading_buttonSm('#btnSaveP');
                $('#inputUpdate, button[data-dismiss=modal]').prop('disabled',true)

                var dataUpdate = [];
                $('.inputUpdate').each(function () {
                   var arr = {
                       ID : $(this).attr('data-id'),
                       Credit : $(this).val()
                   };

                    dataUpdate.push(arr);

                });

                var data = {
                    action : 'updateCreditAllProdi',
                    dataForm : dataUpdate
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api3/__crudAllProgramStudy';

                $.post(url,{token:token},function (result) {
                    getStudents();
                    toastr.success('Data saved','Success');
                    setTimeout(function () {
                        $('#btnSaveP').html('Save').prop('disabled',false);
                        $('#inputUpdate, button[data-dismiss=modal]').prop('disabled',false)
                    },500);

                });

            });

            

        });



    });

</script>