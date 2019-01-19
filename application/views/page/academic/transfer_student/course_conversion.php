
<style>
    h5.header-orange {
        border-left: 7px solid #FF5722;
        font-weight: bold;
        padding-left: 6px;
    }

    .tableTimetable tr th {
        background: #607D8B;
        color: #fff;
        text-align: center;
    }

    .tableTimetableAfter tr th {
        background: #9e9e9e;
        color: #fff;
        text-align: center;
    }

    .tableTimetable tr td, .tableTimetableAfter tr td {
        text-align: center;
    }

</style>

<div class="row">
    <div class="col-md-12">
        <a href="<?php echo base_url('academic/transfer-student/programme-study'); ?>" class="btn btn-warning">
            <i class="fa fa-arrow-left margin-right"></i>
            Back to list</a>

        <hr/>
        <h3 style="margin-top: 0px;border-left: 7px solid #2196F3;
    padding-left: 10px;font-weight: bold;">Course Conversion</h3>
    </div>
</div>


<div class="row">
    <div class="col-md-6" style="border-right: 1px solid #CCCCCC;min-height: 100px;">
        <div class="well" style="text-align: center;padding: 5px;">
            <div id="viewNameB"></div>
            From - <span style="color: #ff5722;" id="viewProdi_B">-</span> :
            <input class="hide" id="dataTransferFromProdi">
            <input class="hide" id="dataTransferFromClassOf">
            <input class="hide" id="dataTransferFromNPM">
        </div>
        <div id="loadSemesterBefore"></div>
    </div>
    <div class="col-md-6">
        <div class="well" style="text-align: center;padding: 5px;">
            <div id="viewNameA"></div>
            To - <span style="color: #ff5722;" id="viewProdi_A">-</span> :
            <input class="hide" id="dataTransferMhswID">
            <input class="hide" id="dataTransferToProdi">
            <input class="hide" id="dataTransferToClassOf">
            <input class="hide" id="dataTransferToNPM">
        </div>
        <div id="loadSemesterAfter"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        window.TSID = parseInt('<?php echo $TSID; ?>');
        window.arrSemesterIDAfter = [];
        loadDataTransferStudent();
    });

    function loadDataTransferStudent() {

        var token = jwt_encode({action : 'readDataTransferStudent', TSID : TSID},'UAP)(*');
        var url = base_url_js+'api/__crudTransferStudent';
        $.post(url,{token:token},function (jsonResult) {



            $('#dataTransferMhswID').val(jsonResult.DataTransfer[0].MhswID);
            $('#dataTransferFromProdi').val(jsonResult.DataTransfer[0].ProdiBefore);
            $('#dataTransferToProdi').val(jsonResult.DataTransfer[0].ProdiAfter);

            $('#dataTransferFromClassOf').val(jsonResult.DataTransfer[0].ClassOfBefore);
            $('#dataTransferToClassOf').val(jsonResult.DataTransfer[0].ClassOfAfter);

            $('#dataTransferFromNPM').val(jsonResult.DataTransfer[0].Before);
            $('#dataTransferToNPM').val(jsonResult.DataTransfer[0].After);

            $('#viewNameB').html('<h3 style="margin-top: 5px;">'+jsonResult.DataTransfer[0].StudentName+'<br/><small>'+jsonResult.DataTransfer[0].Before+'</small></h3>');
            $('#viewNameA').html('<h3 style="margin-top: 5px;">'+jsonResult.DataTransfer[0].StudentName+'<br/><small>'+jsonResult.DataTransfer[0].After+'</small></h3>');

            $('#viewProdi_B').html(jsonResult.DataTransfer[0].CodeProdi_B+' | '+jsonResult.DataTransfer[0].ClassOfBefore);
            $('#viewProdi_A').html(jsonResult.DataTransfer[0].CodeProdi_A+' | '+jsonResult.DataTransfer[0].ClassOfAfter);

            var dataBefore = jsonResult.Before;
            if(dataBefore.length>0){
                $('#loadSemesterBefore').empty();
                $('#loadSemesterAfter').empty();
                for(var b=0;b<dataBefore.length;b++){
                    var d_B = dataBefore[b];
                    $('#loadSemesterBefore').append('<h5 class="header-orange">Semester '+d_B.Semester+'</h5>' +
                        '        <table class="table table-striped table-bordered tableTimetable ">' +
                        '            <thead>' +
                        '            <tr>' +
                        '                <th style="width: 1%;">No</th>' +
                        '                <th style="width: 10%;">Code</th>' +
                        '                <th>Course</th>' +
                        '                <th style="width: 5%;">Credit</th>' +
                        '                <th style="width: 15%;">Final Score</th>' +
                        '                <th style="width: 5%;"><i class="fa fa-cog"></i></th>' +
                        '            </tr>' +
                        '            </thead>' +
                        '            <tbody id="beforeRow'+d_B.Semester+'"></tbody>' +
                        '        </table><hr/>');


                    if(d_B.Course.length>0){
                        var noB = 1;
                        for(var dc = 0;dc<d_B.Course.length;dc++){
                            var d_c_B = d_B.Course[dc];
                            $('#beforeRow'+d_B.Semester).append('<tr>' +
                                '<td>'+(noB++)+'</td>' +
                                '<td id="viewCode'+d_c_B.ID+'" >'+d_c_B.MKCode+'</td>' +
                                '<td id="viewCourse'+d_c_B.ID+'"  style="text-align: left;"><b>'+d_c_B.NameEng+'</b><br/><i>'+d_c_B.Name+'</i></td>' +
                                '<td id="viewCredit'+d_c_B.ID+'" >'+d_c_B.Credit+'</td>' +
                                '<td id="viewScore'+d_c_B.ID+'" ><span style="color: blue;">'+d_c_B.Score+'</span> | '+d_c_B.Grade+'</td>' +
                                '<td><button class="btn btn-sm btn-success btnActToAdd" data-id="'+d_c_B.ID+'"><i class="fa fa-arrow-right"></i></button></td>' +
                                '</tr>');
                        }
                    }

                }
            }


            var dataAfter = jsonResult.After;
            if(dataAfter.length>0){
                for(var b=0;b<dataAfter.length;b++){
                    var d_A = dataAfter[b];
                    arrSemesterIDAfter.push(d_A.SemesterID);
                    $('#loadSemesterAfter').append('<h5 class="header-orange">Semester '+d_A.Semester+'</h5>' +
                        '        <table class="table table-striped table-bordered tableTimetableAfter ">' +
                        '            <thead>' +
                        '            <tr>' +
                        '                <th style="width: 1%;">No</th>' +
                        '                <th style="width: 10%;">Code</th>' +
                        '                <th>Course</th>' +
                        '                <th style="width: 5%;">Credit</th>' +
                        '                <th style="width: 15%;">Final Score</th>' +
                        '                <th style="width: 5%;"><i class="fa fa-cog"></i></th>' +
                        '            </tr>' +
                        '            </thead>' +
                        '            <tbody id="AfterRow'+d_A.Semester+'"></tbody>' +
                        '        </table><hr/>');

                    if(d_A.Course.length>0){
                        var noA = 1;
                        for(var dc = 0;dc<d_A.Course.length;dc++){
                            var d_c_A = d_A.Course[dc];
                            $('#AfterRow'+d_A.Semester).append('<tr>' +
                                '<td>'+(noA++)+'</td>' +
                                '<td id="viewCode'+d_c_A.ID+'" >'+d_c_A.MKCode+'</td>' +
                                '<td id="viewCourse'+d_c_A.ID+'"  style="text-align: left;"><b>'+d_c_A.NameEng+'</b><br/><i>'+d_c_A.Name+'</i></td>' +
                                '<td id="viewCredit'+d_c_A.ID+'" >'+d_c_A.Credit+'</td>' +
                                '<td id="viewScore'+d_c_A.ID+'" ><span style="color: blue;">'+d_c_A.Score+'</span> | '+d_c_A.Grade+'</td>' +
                                '<td><button class="btn btn-sm btn-danger btnActToDelete" data-id="'+d_c_A.ID+'"><i class="fa fa-trash"></i></button></td>' +
                                '</tr>');
                        }
                    }
                }
            }

        });
    }

    $(document).on('click','.btnActToAdd',function () {


        var ID = $(this).attr('data-id');

        var viewCode = $('#viewCode'+ID).html();
        var viewCourse = $('#viewCourse'+ID).html();
        var viewCredit = $('#viewCredit'+ID).html();
        var viewScore = $('#viewScore'+ID).html();

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Conversion</h4>');
        $('#GlobalModal .modal-body').html('<div class="row">' +
            '<div class="col-md-12">' +
            '<div class="table-responsive">' +
            '<table class="table">' +
            '    <tr>' +
            '        <td style="width: 15%;">Code</td>' +
            '        <td style="width: 1%;">:</td>' +
            '        <td>'+viewCode+'</td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Course</td>' +
            '        <td>:</td>' +
            '        <td>'+viewCourse+'</td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Credit</td>' +
            '        <td>:</td>' +
            '        <td id="viewCreditInModal">'+viewCredit+'</td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Score</td>' +
            '        <td>:</td>' +
            '        <td>'+viewScore+'</td>' +
            '    </tr>' +
            '<tr>' +
            '<td colspan="3" style="text-align: center;font-weight: bold;background: #4b768a;color: #fff;">Conversion to : </td>' +
            '</tr>' +
            '    <tr>' +
            '        <td>Semester</td>' +
            '        <td>:</td>' +
            '        <td><select class="form-control" id="formConversionSemester" style="max-width: 150px;"></select></td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Replace</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <div id="divCourse"></div>' +
            '        </td>' +
            '    </tr>' +
            '</table>' +
            '</div>' +
            '<div id="alertCredit"></div>' +
            '</div>' +
            '</div>');


        var Smt = 1;
        for(var i=0;i<arrSemesterIDAfter.length;i++){
            $('#formConversionSemester').append('<option value="'+arrSemesterIDAfter[i]+'.'+Smt+'">Semester '+(Smt++)+'</option>');
        }

        loadCourseTS(1);


        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '<button type="button" class="btn btn-success" id="btnSubmitConversion" data-id="'+ID+'">Submit</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    $(document).on('click','#btnSubmitConversion',function () {

        var SPID = $(this).attr('data-id');

        var formConversionSemester = $('#formConversionSemester').val();
        var formConversionCourse = $('#formConversionCourse').val(); // Val = CDID.MKID.Credit.GradeValue

        if(formConversionSemester!='' && formConversionSemester!=null &&
            formConversionCourse!='' && formConversionCourse!=null){

            var viewCredit = parseInt($('#viewCredit'+SPID).text());

            var SemesterID = formConversionSemester.split('.')[0];
            var MhswID = $('#dataTransferMhswID').val();
            var CDID = formConversionCourse.split('.')[0];
            var MKID = formConversionCourse.split('.')[1];
            var Credit = formConversionCourse.split('.')[2];

            var NPM_B = $('#dataTransferFromNPM').val();
            var NPM_A = $('#dataTransferToNPM').val();

            var data = {
                action : 'replaceCourseTransferStd',
                DB_B : 'ta_'+$('#dataTransferFromClassOf').val(),
                DB_A : 'ta_'+$('#dataTransferToClassOf').val(),
                NPM_A : NPM_A,
                SPID : SPID,
                SemesterID : SemesterID,
                MhswID : MhswID,
                CDID : CDID,
                MKID : MKID,
                Credit : Credit,
                insertHistory : {
                    TSID : TSID,
                    NPM_Before : NPM_B,
                    TA_Before : $('#dataTransferFromClassOf').val(),
                    SPID_Before : SPID,
                    NPM_After : NPM_A,
                    TA_After : $('#dataTransferToClassOf').val()

                }
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudTransferStudent';

            $.post(url,{token:token},function (result) {
                loadDataTransferStudent();
                $('#GlobalModal').modal('hide');
            });

        }


    });
    
    $(document).on('change','#formConversionSemester',function () {

        var formConversionSemester = $('#formConversionSemester').val();

        if(formConversionSemester!='' && formConversionSemester!=null){
            var Semester = formConversionSemester.split('.')[1];
            loadCourseTS(Semester);
        }

    });
    
    function loadCourseTS(Semester) {

        loading_page_simple('#divCourse');

        var ClassOf = $('#dataTransferToClassOf').val();
        var ProdiID = $('#dataTransferToProdi').val();

        var token = jwt_encode({action : 'getCourseTransferStudent', ClassOf : ClassOf, Semester : Semester, ProdiID : ProdiID},'UAP)(*');
        var url = base_url_js+'api/__crudTransferStudent';

        $.post(url,{token:token},function (jsonResult) {

            // console.log(jsonResult);

            setTimeout(function () {

                if(jsonResult.length>0){
                    $('#divCourse').html('<select class="select2-select-00 full-width-fix form-jadwal"' +
                        '                                    size="5" id="formConversionCourse"><option></option></select>');
                    for(var i=0;i<jsonResult.length;i++){
                        var d = jsonResult[i];
                        $('#formConversionCourse').append('<option value="'+d.CDID+'.'+d.MKID+'.'+d.Credit+'">'+d.MKCode+' - '+d.NameEng+' ('+d.Credit+' Credit)</option>');
                    }
                    $('#formConversionCourse').select2({allowClear: true});
                } else {
                    $('#divCourse').html('<span style="color:red;">No courses</span>');
                }



            },500);

        });

    }

    $(document).on('change','#formConversionCourse',function () {

        var formConversionCourse = $('#formConversionCourse').val();
        if(formConversionCourse!='' && formConversionCourse!=null){

            $('#alertCredit').html('');

            var CreditN = parseInt(formConversionCourse.split('.')[2]);
            var viewCreditInModal = parseInt($('#viewCreditInModal').text());
            if(viewCreditInModal!=CreditN){
                $('#alertCredit').html('<div class="alert alert-warning" role="alert"><b><i class="fa fa-exclamation-triangle margin-right"></i> Warning !</b> ' +
                    '<div style="margin-left: 23px;"><b>Credit is not same !</b> if continued, the old credit will be replaced in the new timetables</div></div>');
            }
        }
    });

    // Remove In New KRS
    $(document).on('click','.btnActToDelete',function () {

        var SPID = $(this).attr('data-id');
        if(confirm('Are you sure?') && SPID!='' && SPID!=null){

            var ClassOf = $('#dataTransferToClassOf').val();

            var token = jwt_encode(
                {
                    action : 'removeDataTransferStudent',
                    SPID : SPID,
                    NPM_A : $('#dataTransferToNPM').val(),
                    ClassOf : ClassOf
                },'UAP)(*');
            var url = base_url_js+'api/__crudTransferStudent';

            $.post(url,{token:token},function (result) {
                setTimeout(function () {
                    loadDataTransferStudent();
                },500);
            });

        }

    });

</script>