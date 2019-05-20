

<style>
    #tableStudent th, #tableStudent td {
        text-align: center;
    }

    .sp-sts {
        font-size: 12px;
    }

    #tableSelected {
        text-align: center;
    }
    .btn-removestd {
        border-radius: 15px;
        padding: 2px 7px;
    }
</style>

<div class="row">

    <div class="col-xs-6" style="border-right: 1px solid #CCCCCC;">

        <div style="text-align: right;margin-bottom: 10px;">
            <button class="btn btn-success" id="addNewStudentToSA">Add Student</button>
        </div>

        <div id="viewStudent"></div>
    </div>
    <div class="col-xs-6">
        <input id="formIDSAStudent" class="hide" value="">
        <input id="formMentorNIP" class="hide" value="">
        <input id="formProdiID" class="hide" value="">
        <input id="formNPM" class="hide" value="">
        <input id="formClassOf" class="hide" value="">


        <input id="formMaxCredit" class="hide" value="0">
        <input id="formTotalCredit" class="hide" value="0">
        <div id="alertGlobal"></div>

        <div id="panelSA"></div>

        <h3 style="margin-top: 0px;border-left: 7px solid #67a9a2;padding-left: 10px;font-weight: bold;" id="viewName">-</h3>

        <table class="table table-striped" id="tableListCourse">
            <thead>
            <tr style="background: #67a9a2;color: #ffffff;">
                <th style="width: 1%;">No</th>
                <th style="width: 5%;">Semester</th>
                <th style="width: 10%;">Code</th>
                <th>Course</th>
                <th style="width: 10%;">Credit</th>
                <th style="width: 10%;">Score</th>
                <th style="width: 5%;"><i class="fa fa-cog"></i></th>
                <th style="width: 15%;">Status</th>
            </tr>
            </thead>
            <tbody id="rowSelected"></tbody>
        </table>

        <hr/>

        <div class="row" id="viewSemester"></div>

        <div id="showTableCourse"></div>

    </div>

</div>

<script>

    $(document).ready(function () {

        loadStudents();
        loadSemesterAntara();


    });

    function loadStudents() {

        $('#viewStudent').html('<table class="table table-striped" id="tableStudent">' +
            '                <thead>' +
            '                <tr>' +
            '                    <th style="width: 1%;">No</th>' +
            '                    <th style="width: 30%;">Student</th>' +
            '                    <th>Course</th>' +
            '                    <th style="width: 1%;"><i class="fa fa-cog"></i></th>' +
            '                </tr>' +
            '                </thead>' +
            '            </table>');

        var data = {
            SASemesterID : '<?=$SASemesterID; ?>'
        };

        var token = jwt_encode(data,'UAP)(*');

        var dataTable = $('#tableStudent').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Group, Lecturer"
            },
            "ajax":{
                url : base_url_js+"api2/__getStudentSA", // json datasource
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
    
    $('#addNewStudentToSA').click(function () {
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Students</h4>');
        $('#GlobalModal .modal-body').html('<div class="row">' +
            '    <div class="col-md-12">' +
            '        <div class="form-group">' +
            '            <input class="form-control" id="formInputStudent" placeholder="NIM, Name">' +
            '            <hr/>' +
            '        </div>' +
            '        <table class="table table-striped">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th style="width: 10%;">NIM</th>' +
            '                <th>Name</th>' +
            '                <th style="width: 10%;">Action</th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody id="listStudent"></tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>');
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        $('#formInputStudent').keyup(function () {
            var formInputStudent = $('#formInputStudent').val();
            if(formInputStudent!='' && formInputStudent.length>=3){

                var url = base_url_js+'api2/__getStudentList';

                $.getJSON(url,{Key:formInputStudent,SASemesterID : '<?=$SASemesterID; ?>'},function (jsonResult) {

                    $('#listStudent').empty();
                    if(jsonResult.length>0){
                        var no =1;
                        $.each(jsonResult,function (i,v) {

                            var buttonAdd = (v.IDSAStudent!=null && v.IDSAStudent!='') ? '-'
                                : '<button class="btn btn-sm btn-success btnAddStd" data-npm="'+v.NPM+'" data-mentor="'+v.NIP+'"><i class="fa fa-download"></i></button>';

                            $('#listStudent').append('<tr>' +
                                '<td>'+no+'</td>' +
                                '<td>'+v.NPM+'</td>' +
                                '<td>'+v.Name+'</td>' +
                                '<td id="tdBtn_'+v.NPM+'">'+buttonAdd+'</td>' +
                                '</tr>');

                            no ++;
                        })
                    } else {
                        $('#listStudent').append('<tr><td colspan="3" style="text-align: center;">-- Data Not Yet --</td></tr>');
                    }

                });

            }
        });



    });

    // Add Student
    $(document).on('click','.btnAddStd',function () {

        if(confirm('Are you sure?')){
            $('.btnAddStd').prop('disabled',true);
            var NPM = $(this).attr('data-npm');
            var Mentor = $(this).attr('data-mentor');

            var data = {
                action : 'addStudentSA',
                dataForm : {
                    SASemesterID : '<?=$SASemesterID; ?>',
                    NPM : NPM,
                    Mentor : Mentor,
                    RequestedAt : dateTimeNow()
                }
            };

            var token = jwt_encode(data,'UAP)(*');

            var url = base_url_js+'api2/__crudSemesterAntara';

            $.post(url,{token:token},function (result) {

                toastr.success('Student added','Success');
                loadStudents();
                setTimeout(function () {
                    $('#tdBtn_'+NPM).html('-');
                    $('.btnAddStd').prop('disabled',false);
                },500);
            });
        }

    });

    // Remove Student
    $(document).on('click','.btn-removestd',function () {

        if(confirm('Are you sure?')){
            $('.btn-removestd').prop('disabled',true);
            var IDSAStudent = $(this).attr('data-id');
            var NPM = $(this).attr('data-npm');
            var data = {
                action : 'rmStudentSA',
                IDSAStudent : IDSAStudent
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api2/__crudSemesterAntara';

            $.post(url,{token:token},function (result) {
                toastr.success('Student removed','Success');

                var formNPM = $('#formNPM').val();

                if(formNPM==NPM){
                    $('#formNPM').val('');
                    $('#showTableCourse,#viewSemester').empty();
                    loadSelected();
                }

                setTimeout(function () {
                    loadStudents();
                },500);
            });
        }


    });

    $(document).on('click','.showSAStudent',function () {
        var IDSAStudent = $(this).attr('data-idstd');
        var MentorNIP = $(this).attr('data-mentor');
        var ProdiID = $(this).attr('data-prodi');
        var NPM = $(this).attr('data-npm');
        var ClassOf = $(this).attr('data-classof');

        $('#formIDSAStudent').val(IDSAStudent);
        $('#formMentorNIP').val(MentorNIP);
        $('#formProdiID').val(ProdiID);
        $('#formNPM').val(NPM);
        $('#formClassOf').val(ClassOf);

        $('#viewName').html(NPM+' - '+$(this).text());

        loadSelected();
    });

    // =============================================== PANEL KANAN ===============================================

    function loadSemesterAntara() {

        var data = {
            action : 'readAcademicYearSA'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudSemesterAntara';

        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                var d = jsonResult[0];
                $('#formMaxCredit').val(d.MaxCredit);
                $('#viewMaxCredit').html(d.MaxCredit);
                loadSelected();
            } else {
                $('#viewMaxCredit').val(-1);
                $('#alertGlobal').html('<div class="container">' +
                    '        <div class="row">' +
                    '            <div class="col-xs-12">' +
                    '                <div class="thumbnail" style="padding: 15px;text-align: center;"><h3 style="color: #CCCCCC;">-- Semester Antara Not Active --</h3></div>' +
                    '            </div>' +
                    '        </div>' +
                    '    </div>');

                $('#panelSA').remove();
            }
        });

    }

    function loadSelected() {

        $('#rowSelected').html('<tr><td colspan="7" id="loadingTable"></td></tr>');
        loading_page('#loadingTable');

        var formNPM = $('#formNPM').val();
        var formClassOf = $('#formClassOf').val();

        if(formNPM!='' && formNPM!=null &&
            formClassOf!='' && formClassOf!=null){

            $('#viewSemester').html('<div class="col-md-4 col-md-offset-4">' +
                '                <div class="well">' +
                '                    <select class="form-control" id="formSemester"></select>' +
                '                </div>' +
                '            </div>')

            for(var i=0;i<=8;i++){
                var ap = (i==0) ? '<option value="">-- All Semester --</option><option disabled>-------</option>' : '<option value="'+i+'">Semester '+i+'</option>';
                $('#formSemester').append(ap);
            }

            var data = {
                action : 'readSelectedCourse',
                NPM : formNPM,
                ClassOf : formClassOf,
                SASemesterID : '<?=$SASemesterID; ?>'
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api2/__crudSemesterAntara';

            $.post(url,{token:token},function (jsonResult) {

                setTimeout(function () {

                    $('#rowSelected').empty();

                    if(jsonResult.length>0){
                        var no = 1;

                        var totalCredit = 0;

                        $.each(jsonResult,function (i,v) {

                            var p = (v.SP.length>0) ? v.SP[0] : [];
                            var Grade = (v.SP.length>0 && p.Grade!=null && p.Grade!='') ? p.Grade : '';
                            var Score = (v.SP.length>0 && p.Score!=null && p.Score!='') ? ' | '+p.Score : '';

                            totalCredit = totalCredit + parseInt(v.Credit);

                            var btnRemove = '<button class="btn btn-sm btn-default btn-default-danger btnAddSASPDelete" data-ssdid="'+v.SSDID+'"><i class="fa fa-minus"></i></button>';

                            var Status = '<div  style="font-size: 11px;">Not yet send</div>';
                            if(v.Status==1 || v.Status=='1'){
                                Status = '<div  style="font-size: 11px;color: royalblue;">Waiting for approval from the Mentor</div>';
                            } else if(v.Status==2 || v.Status=='2'){
                                Status = '<div  style="font-size: 11px;color: royalblue;">Waiting for approval from the Kaprodi</div>';
                            }  else if(v.Status==-2 || v.Status=='-2'){
                                Status = '<div  style="font-size: 11px;color: red;">Rejected by Mentor</div>';
                            } else if(v.Status==3 || v.Status=='3'){
                                Status = '<div  style="font-size: 11px;color: green;"><i class="fa fa-check"></i> Approved</div>';
                            } else if(v.Status==-3 || v.Status=='-3'){
                                Status = '<div  style="font-size: 11px;color: red;">Rejected by Kaprodi</div>';
                            }

                            $('#rowSelected').append('<tr>' +
                                '<td style="border-right: 1px solid #ccc;">'+no+'</td>' +
                                '<td>'+v.Semester+'</td>' +
                                '<td>'+v.MKCode+'</td>' +
                                '<td style="text-align: left;">'+v.CoureEng+'</td>' +
                                '<td>'+v.Credit+'</td>' +
                                '<td style="text-align: right;">'+Grade+' '+Score+'</td>' +
                                '<td>'+btnRemove+'</td>' +
                                '<td>'+Status+'</td>' +
                                '</tr>');

                            if(parseInt(no)==parseInt(jsonResult.length)){

                                $('#rowSelected').append('<tr style="font-weight: bold;">' +
                                    '<td style="background: #7b7b7b;color: #fff;" colspan="4">Total Credit</td>' +
                                    '<td style="background: #7b7b7b;color: #fff;">'+totalCredit+'</td>' +
                                    '</tr>');

                                $('#formTotalCredit').val(totalCredit);
                                $('#viewTotalCredit').html(totalCredit);


                            }

                            no++;
                        });


                    }
                    else {
                        $('#rowSelected').html('<tr>' +
                            '<td colspan="8" style="text-align: center;"><h3 style="color: #CCCCCC;">-- Course Not Yet --</h3></td>' +
                            '</tr>');
                        $('#formTotalCredit').val(0);
                        $('#viewTotalCredit').html(0);
                    }

                    loadCurriculum();

                },500);


            })

        } else {
            $('#viewSemester').remove();
            $('#loadingTable').html('<tr><td colspan="8">-- Data Not Yet --</td></tr>');
        }

    }

    function loadCurriculum() {

        loading_page('#showTableCourse');

        var formSemester = $('#formSemester').val();

        var Semester = (formSemester!='' && formSemester!=null && formSemester!=0)
            ? formSemester
            : '';

        var formProdiID = $('#formProdiID').val();
        var formNPM = $('#formNPM').val();
        var formClassOf = $('#formClassOf').val();

        var data = {
            action : 'readCourse',
            ProdiID : formProdiID,
            NPM : formNPM,
            ClassOf : formClassOf,
            Semester : Semester
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudSemesterAntara';

        $.post(url,{token:token},function (jsonResult) {

            setTimeout(function () {
                if(jsonResult.length>0){

                    var formTotalCredit = $('#formTotalCredit').val();
                    var formMaxCredit = $('#formMaxCredit').val();

                    $('#showTableCourse').html('<table class="table table-striped" id="tableCurriculum">' +
                        '            <thead>' +
                        '            <tr>' +
                        '                <th style="width: 1%;">No</th>' +
                        '                <th style="width: 5%;">Semester</th>' +
                        '                <th style="width: 10%;">Code</th>' +
                        '                <th>Course</th>' +
                        '                <th style="width: 5%;">Credit</th>' +
                        '                <th style="width: 10%;">Score</th>' +
                        '                <th style="width: 10%;"><i class="fa fa-cog"></i></th>' +
                        '            </tr>' +
                        '            </thead>' +
                        '            <tbody id="showCourse"></tbody>' +
                        '        </table>');

                    var no = 1;
                    $.each(jsonResult,function (i,v) {

                        var p = (v.SP.length>0) ? v.SP[0] : [];
                        var Grade = (v.SP.length>0 && p.Grade!=null && p.Grade!='') ? p.Grade : '';
                        var Score = (v.SP.length>0 && p.Score!=null && p.Score!='') ? ' | '+p.Score : '';
                        var Score_ = (v.SP.length>0 && p.Score!=null && p.Score!='') ? p.Score : '';

                        var btnAct = '';
                        var color = '#9e9e9e;';
                        if(Grade!='A' && Grade!='A-'){

                            if(v.DataGet.length>0){
                                btnAct = '<i style="color: green;" class="fa fa-check-circle"></i>';
                                color = 'green;'
                                // btnAct = '<button class="btn btn-sm btn-default btn-default-danger btnAddSASPRemove" data-cdid="'+v.CDID+'" data-mkid="'+v.MKID+'"><i class="fa fa-minus"></i></button>';
                            } else {

                                var CreditSementara = parseInt(formTotalCredit) + parseInt(v.Credit);

                                if(parseInt(formMaxCredit)>=CreditSementara){
                                    btnAct = '<button class="btn btn-sm btn-default btn-default-success btnAddSASP" data-grade="'+Grade.trim()+'" data-score="'+Score_+'" data-credit="'+v.Credit+'" data-cdid="'+v.CDID+'" data-mkid="'+v.MKID+'"><i class="fa fa-download"></i></button>';
                                    color = '#333;'
                                } else {
                                    btnAct = '<div style="font-size: 10px;color: orangered;">Credit Not Enought</div>';
                                    color = '#333;'
                                }


                            }

                        }

                        $('#showCourse').append('<tr style="color: '+color+'">' +
                            '<td style="border-right: 1px solid #ccc;">'+no+'</td>' +
                            '<td>'+v.Semester+'</td>' +
                            '<td>'+v.MKCode+'</td>' +
                            '<td style="text-align: left;">'+v.CoureEng+'</td>' +
                            '<td>'+v.Credit+'</td>' +
                            '<td style="text-align: right;">'+Grade+' '+Score+'</td>' +
                            '<td>'+btnAct+'</td>' +
                            '</tr>');

                        if(no==jsonResult.length){
                            $('#tableCurriculum').DataTable({
                                "processing": false,
                                "serverSide": false,
                                "iDisplayLength" : 10,
                                "ordering" : false
                            });
                        }

                        no++;
                    });



                }
                else {
                    $('#showTableCourse').html('<h3 style="color: #CCCCCC;text-align: center;">Course Not Yet</h3>');
                }
            },500);

        });

    }

    $(document).on('click','.btnAddSASP',function () {

        var CDID = $(this).attr('data-cdid');
        var MKID = $(this).attr('data-mkid');

        var Credit = $(this).attr('data-credit');
        var Grade = $(this).attr('data-grade');
        var Score = $(this).attr('data-score');

        loading_buttonSm('.btnAddSASP[data-cdid='+CDID+']');

        $('.btnAddSASP,.btnAddSASPDelete').prop('disabled',true);

        var formIDSAStudent = $('#formIDSAStudent').val();
        var formMentorNIP = $('#formMentorNIP').val();
        var formNPM = $('#formNPM').val();

        var Type = (Grade!='' && Grade!=null) ? 'Ul' : 'Br';

        var data = {
            action : 'enteredCourseByAcademic',
            Mentor : formMentorNIP,
            dataDetails : {
                IDSAStudent : formIDSAStudent,
                NPM : formNPM,
                CDID : CDID,
                MKID : MKID,
                Type : Type,
                Credit : Credit,
                Grade : Grade,
                Score : Score,
                EntredAt : dateTimeNow(),
                Status : '3'
            }

        };

        var token = jwt_encode(data,'UAP)(*');

        var url = base_url_js+'api2/__crudSemesterAntara';

        $.post(url,{token:token},function (result) {
            toastr.success('Course Entred','Success');
            loadStudents();
            setTimeout(function () {
                loadSelected();
            },500);

        });

    });

    $(document).on('click','.btnAddSASPDelete',function () {

        if(confirm('Are you sure to remove?')){

            $('.btnAddSASPDelete,.btnAddSASP').prop('disabled',true);

            var SSDID = $(this).attr('data-ssdid');

            loading_buttonSm('.btnAddSASPDelete[data-ssdid='+SSDID+']');
            var formIDSAStudent = $('#formIDSAStudent').val();
            var data = {
                action : 'deleteCourseSA',
                IDSAStudent : formIDSAStudent,
                SSDID : SSDID
            };

            var token = jwt_encode(data,'UAP)(*');

            var url = base_url_js+'api2/__crudSemesterAntara';

            $.post(url,{token:token},function (result) {

                toastr.success('Caourse removed','Success');
                loadStudents();
                setTimeout(function () {
                    loadSelected();
                },500);
            });
        }



    });

</script>