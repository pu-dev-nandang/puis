

<div class="row" style="margin-bottom: 15px;">
    <div class="col-md-12">
        <button class="btn btn-success pull-right" id="btnAddSurvey">Create Survey</button>
    </div>
</div>

<div class="row">
    <div class="col-md-12" id="loadTable"></div>
</div>




<script>

    $(document).ready(function () {
        setLoadFullPage();
        loadTableSurveyList();
    });

    function loadTableSurveyList(){
        $('#loadTable').html('<table id="tableData" class="table table-bordered table-striped table-centre">' +
            '            <thead>' +
            '            <tr style="background: #eceff1;">' +
            '                <th style="width: 3%;">No</th>' +
            '                <th>Title</th>' +
            '                <th style="width: 5%;">Question</th>' +
            '                <th style="width: 5%;"><i class="fa fa-users"></i></th>' +
            '                <th style="width: 7%;"><i class="fa fa-cog"></i></th>' +
            '                <th style="width: 20%;">Publication Date</th>' +
            '                <th style="width: 5%;">Status</th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody></tbody>' +
            '        </table>');

        var data = {
            action : 'getListSurvey',
            DepartmentID : sessionIDdepartementNavigation,
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudSurvey';

        var dataTable = $('#tableData').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Question..."
            },
            "ajax":{
                url :url, // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    loading_modal_hide();
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );


    }

    $(document).on('click','.showQuestionList',function () {

        var ID = $(this).attr('data-id');
        var data = {
            action : 'showQuestionInSurvey',
            SurveyID : ID,
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudSurvey';

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Show Question</h4>');

        var htmlss = '<div id="panelShowQuestion"></div>';

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModal .modal-body').html(htmlss);

        loading_page('#panelShowQuestion');

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){

                var tr = '';
                $.each(jsonResult,function (i,v) {
                    tr = tr+'<tr>' +
                        '<td>'+(i + 1)+'</td>' +
                        '<td style="text-align: left;"><span class="label label-primary">'+v.Category+'</span>' +
                        ' <span class="label label-success">'+v.Type+'</span>' +
                        '       <dvi>'+v.Question+'</div></td>' +
                        '<td>'+v.AverageRate+'</td>' +
                        '</tr>';
                })

            }

            var dataListQuestion = '<div>' +
                '    <table class="table table-bordered table-striped table-centre">' +
                '        <thead>' +
                '        <tr>' +
                '            <th style="width: 3%">No</th>' +
                '            <th>Question</th>' +
                '            <th style="width: 13%">Average</th>' +
                '        </tr>' +
                '        </thead>' +
                '        <tbody>'+tr+'</tbody>' +
                '    </table>' +
                '</div>';

            setTimeout(function () {
                $('#panelShowQuestion').html(dataListQuestion);
            },500);

        });



    });

    $(document).on('click','.showAlreadyFillOut',function () {


        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Show Detail Already Fill Out</h4>');

        var htmlss = '<div class="">' +
            '    <table class="table table-bordered table-striped table-centre" id="tableShowUserAlreadyFillOut">' +
            '        <thead>' +
            '        <tr>' +
            '            <th style="width: 1%;">No</th>' +
            '            <th>User</th>' +
            '            <th style="width: 10%;">Type</th>' +
            '            <th style="width: 25%;">Entred At</th>' +
            '        </tr>' +
            '        </thead>' +
            '    </table>' +
            '</div>';

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModal .modal-body').html(htmlss);

        var ID = $(this).attr('data-id');
        var data = {
            action : 'showUserAlreadyFill',
            SurveyID : ID,
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudSurvey';


        var dataTable = $('#tableShowUserAlreadyFillOut').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Username, Name..."
            },
            "ajax":{
                url :url, // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    loading_modal_hide();
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

    $('#btnAddSurvey').click(function () {
        updateSurvey('');
    });

    $(document).on('click','.btnEditSurvey',function () {
        var ID = $(this).attr('data-id');
        var data = {
            action : 'getOneDataSurvey',
            ID : ID
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudSurvey';

        $.post(url,{token:token},function (jsonResult) {
            if(jsonResult.length>0){
                localStorage.setItem('dataSurvey',JSON.stringify(jsonResult[0]));
                updateSurvey(ID);
            }
        });


    });

    function updateSurvey(ID) {

        var Title = '';
        var StartDate = '';
        var EndDate = '';
        var Note = '';

        var btnSave = '<button class="btn btn-success" id="btnCreateSurvey">Create</button>';
        var formDisabled = '';

        if(ID!=''){
            var dataSurvey = localStorage.getItem('dataSurvey');
            var d = JSON.parse(dataSurvey);
            Title = d.Title;
            StartDate = d.StartDate;
            EndDate = d.EndDate;
            Note = d.Note;

            if(d.Status!='0'){
                btnSave = '';
                formDisabled = 'disabled'
            } else {
                btnSave = '<button class="btn btn-success" id="btnCreateSurvey">Edit</button>';
            }

        }


        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Create Survey</h4>');

        var htmlss = '<div class="form-group">' +
            '                    <label>Title</label>' +
            '                    <input id="formSurveyID" class="hide" '+formDisabled+' value="'+ID+'">' +
            '                    <input id="formSurveyTitle" class="form-control" '+formDisabled+' value="'+Title+'">' +
            '                </div>' +
            '                <div class="form-group">' +
            '                    <div class="row">' +
            '                        <div class="col-md-6">' +
            '                            <label>Start</label>' +
            '                            <input id="formSurveyStartDate" '+formDisabled+' value="'+StartDate+'" class="form-control" type="date">' +
            '                        </div>' +
            '                        <div class="col-md-6">' +
            '                            <label>End</label>' +
            '                            <input id="formSurveyEndDate" '+formDisabled+' value="'+EndDate+'" class="form-control" type="date">' +
            '                        </div>' +
            '                    </div>' +
            '                </div>' +
            '                <div class="form-group">' +
            '                    <label>Note</label>' +
            '                    <textarea class="form-control" id="formSurveyNote" '+formDisabled+' rows="2">'+Note+'</textarea>' +
            '                </div>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#formSurveyTitle,#formSurveyStartDate,#formSurveyEndDate,#formSurveyNote').css('color','#333');

        $('#GlobalModal .modal-footer').html('' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> '+btnSave+
            '' +
            '');

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    }

    $(document).on('click','.btnPublishSurvey',function () {
        var ID = $(this).attr('data-id');
        updateStatusSurvey(ID,'1',
            'After the survey is published you cannot withdraw it, are you sure?');
    });

    $(document).on('click','.btnCloseSurvey',function () {
        var ID = $(this).attr('data-id');
        updateStatusSurvey(ID,'2',
            'If the survey is closed, the user cannot fill out your survey, are you sure?');
    });

    function updateStatusSurvey(ID,Status,msg){
        if(confirm(msg)){
            var data = {
                action : 'setStatusSurvey',
                ID : ID,
                Status : Status,
                NIP : sessionNIP
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'apimenu/__crudSurvey';

            $.post(url,{token:token},function (jsonResult) {
                $('#viewStatusSurvey_'+ID).html(jsonResult.Label);

                if(Status==1){
                    $('#li_btn_Publish_'+ID).remove();
                    $('#li_btn_Close_'+ID).removeClass('hide');
                } else if(Status==2){
                    $('#li_btn_Publish_'+ID+',#li_btn_Close_'+ID).remove();
                }



            });

        }
    }

    $(document).on('click','#btnCreateSurvey',function () {
        var formSurveyTitle  = $('#formSurveyTitle').val();
        var formSurveyStartDate  = $('#formSurveyStartDate').val();
        var formSurveyEndDate  = $('#formSurveyEndDate').val();
        var formSurveyNote  = $('#formSurveyNote').val();

        if(formSurveyTitle!='' && formSurveyTitle!=null &&
            formSurveyStartDate!='' && formSurveyStartDate!=null &&
        formSurveyEndDate!='' && formSurveyEndDate!=null){

            loading_button('#btnCreateSurvey');

            var formSurveyID = $('#formSurveyID').val();
            var data = {
                action : 'updateSurvey',
                ID : (formSurveyID!='' && formSurveyID!=null) ? formSurveyID : '',
                NIP : sessionNIP,
                dataSurvey : {
                    DepartmentID : sessionIDdepartementNavigation,
                    Title : formSurveyTitle,
                    StartDate : formSurveyStartDate,
                    EndDate : formSurveyEndDate,
                    Note : formSurveyNote
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'apimenu/__crudSurvey';

            $.post(url,{token:token},function (jsonResult) {
                toastr.success('Data saved','Success');
                loadTableSurveyList();
                setTimeout(function () {
                    $('#GlobalModal').modal('hide');
                },500);

            });

        } else {
            toastr.warning('All form are required','Warning');
        }
    });

    $(document).on('click','.btnManageTarget',function () {
        var ID = $(this).attr('data-id');

        var data = {
            action : 'getDataTargetSurvey',
            ID : ID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudSurvey';

        $.post(url,{token:token},function (jsonResult) {

            // 0 = Unpublish, 1 = Publish, 2 = Close
            var btnSave = (parseInt(jsonResult.Status)<=0)
                ? '<button class="btn btn-success" id="btnSaveTarget" data-id="'+ID+'">Save</button>' : '';

            var disabledForm = (parseInt(jsonResult.Status)<=0) ? '' : 'disabled';

            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Manage Target</h4>');

            // 1 = All emp, 2 = Hanya dosen, 3 = Hanya tenaga pendidik (selain dosen)

            var htmlss = '<div class="panel panel-default">' +
                '            <div class="panel-heading">' +
                '                <h4 class="panel-title">Target Employees</h4>' +
                '            </div>' +
                '            <div class="panel-body">' +
                '                <div style="color: blue;border-bottom: 1px solid #ccc;padding-bottom: 10px;margin-bottom: 10px;">' +
                '                    <label class="radio-inline">' +
                '                        <input type="radio" name="survUserEmp" '+disabledForm+' value="-1" checked> Bukan untuk Dosen & Tenaga Pendidik' +
                '                    </label>' +
                '                </div>' +
                '                <div>' +
                '                    <label class="radio-inline">' +
                '                        <input type="radio" name="survUserEmp" '+disabledForm+' value="1"> Semua Dosen & Tenga Pendidik' +
                '                    </label>' +
                '                </div>' +
                '                <div>' +
                '                    <label class="radio-inline">' +
                '                        <input type="radio" name="survUserEmp" '+disabledForm+' value="2"> Semua Dosen (selain tenaga pendidik)' +
                '                    </label>' +
                '                </div>' +
                '                <div>' +
                '                    <label class="radio-inline">' +
                '                        <input type="radio" name="survUserEmp" '+disabledForm+' value="3"> Semua Tenga Pendidik (selain dosen)' +
                '                    </label>' +
                '                </div>' +
                '            </div>' +
                '        </div>' +
                '' +
                '' +
                '<div class="panel panel-default">' +
                '            <div class="panel-heading">' +
                '                <h4 class="panel-title">Target Student</h4>' +
                '            </div>' +
                '            <div class="panel-body">' +
                '                <div style="margin-bottom: 15px;">' +
                '                    <div style="color: blue;border-bottom: 1px solid #ccc;padding-bottom: 10px;margin-bottom: 10px;">' +
                '                        <label class="radio-inline">' +
                '                            <input type="radio" name="surv_std_TypeUser" '+disabledForm+' value="-1" checked> Bukan untuk mahasiswa' +
                '                        </label>' +
                '                    </div>' +
                '                    <div>' +
                '                        <label class="radio-inline">' +
                '                            <input type="radio" name="surv_std_TypeUser" '+disabledForm+' value="1"> Semua mahasiswa' +
                '                        </label>' +
                '                    </div>' +
                '                    <div>' +
                '                        <label class="radio-inline">' +
                '                            <input type="radio" name="surv_std_TypeUser" '+disabledForm+' value="2"> Semua mahasiswa aktif' +
                '                        </label>' +
                '                    </div>' +
                '                    <div>' +
                '                        <label class="radio-inline">' +
                '                            <input type="radio" name="surv_std_TypeUser" '+disabledForm+' value="3"> Semua Alumni' +
                '                        </label>' +
                '                    </div>' +
                '                    <div>' +
                '                        <label class="radio-inline">' +
                '                            <input type="radio" name="surv_std_TypeUser" '+disabledForm+' value="0"> Custom' +
                '                        </label>' +
                '                    </div>' +
                '                </div>' +
                '                <div id="panelCustomStd" class="hide">' +
                '                    <div class="well" id="panelAddCustomStd">' +
                '                        <div class="row">' +
                '                            <div class="col-md-3">' +
                '                                <label>Class Of</label>' +
                '                                <select class="form-control" id="formUsr_ClassOf"></select>' +
                '                            </div>' +
                '                            <div class="col-md-5">' +
                '                                <label>Prodi</label>' +
                '                                <select class="form-control" id="formUsr_ProdiID"></select>' +
                '                            </div>' +
                '                            <div class="col-md-4">' +
                '                                <label>Status Student</label>' +
                '                                <select class="form-control" id="formUsr_StatusStudentID"></select>' +
                '                            </div>' +
                '                        </div>' +
                '                        <div class="row" style="margin-top: 10px;">' +
                '                            <div class="col-md-12 text-right">' +
                '                                <button id="btnAddCustomTargetStd" data-id="'+ID+'" class="btn btn-sm btn-primary">Add</button>' +
                '                            </div>' +
                '                        </div>' +
                '                    </div>' +
                '                   <input class="hide" id="dataStatusSurvey" value="'+jsonResult.Status+'"/>' +
                '                    <div id="viewTableTargetCustom"></div>' +
                '                </div>' +
                '' +
                '            </div>' +
                '        </div>';

            $('#GlobalModal .modal-body').html(htmlss);

            // Employees
            var dataEmployee = jsonResult.Employee;
            if(dataEmployee.length>0){
                $('input[name=survUserEmp][value='+dataEmployee[0].TypeUser+']').attr('checked',true);
            }

            var dataStudent = jsonResult.Student;
            if(dataStudent.length>0){
                $('input[name=surv_std_TypeUser][value='+dataStudent[0].TypeUser+']').attr('checked',true);
                if(dataStudent[0].TypeUser==0 || dataStudent[0].TypeUser=='0'){
                    $('#panelCustomStd').removeClass('hide');
                } else {
                    $('#panelCustomStd').addClass('hide');
                }
                loadDataTargetCustomStudent(ID);
            }

            if( (parseInt(jsonResult.Status)<=0)){
                loadSelectOptionClassOf_DESC('#formUsr_ClassOf','','HideLabel');
                loadSelectOptionBaseProdi('#formUsr_ProdiID','');
                loadSelectOptionStatusStudent('#formUsr_StatusStudentID',3);
            } else {
                $('#panelAddCustomStd').remove();
            }



            $('#GlobalModal .modal-footer').html('' +
                '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> '+btnSave+
                '' +
                '');

            $('#GlobalModal').on('shown.bs.modal', function () {
                $('#formSimpleSearch').focus();
            });

            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });

        });

    });

    function loadDataTargetCustomStudent(ID){

        var data = {
            action : 'getDataTargetCustomStudent',
            ID : ID
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudSurvey';

        loading_page('#viewTableTargetCustom');
        var dataStatusSurvey = $('#dataStatusSurvey').val();

        $.post(url,{token:token},function (jsonResult) {

            var tr = '';
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var btnRemove = (parseInt(dataStatusSurvey)<=0)
                        ? '<button class="btn btn-danger btn-sm removeCustomTargetStudent" ' +
                        'data-id="'+v.TargetUserID+'" data-id-survey="'+ID+'"><i class="fa fa-trash"></i></button>'
                        : '-';

                    tr = tr+'<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td style="text-align: left;"><b>'+v.Prodi+'</b><br/>'+v.ClassOf+' - '+v.Description+'</td>' +
                        '<td>'+btnRemove+'</td>' +
                        '</tr>';
                });
            } else {
                tr = '<tr><td colspan="3">No data</td></tr>'
            }

            setTimeout(function () {
                $('#viewTableTargetCustom').html('<div class="table-responsive">' +
                    '    <table class="table table-bordered table-striped table-centre">' +
                    '        <thead>' +
                    '        <tr>' +
                    '            <th style="width: 3%;">No</th>' +
                    '            <th>Program Study</th>' +
                    '            <th style="width: 10%;"><i class="fa fa-cog"></i></th>' +
                    '        </tr>' +
                    '        </thead>' +
                    '       <tbody>'+tr+'</tbody>' +
                    '    </table>' +
                    '</div>');
            },500);
        });

    }

    $(document).on('change','input[type=radio][name="surv_std_TypeUser"]',function () {
        var val = $('input[type=radio][name="surv_std_TypeUser"]:checked').val();
        if(val=='0'){
            $('#panelCustomStd').removeClass('hide');
        } else {
            $('#panelCustomStd').addClass('hide');
        }
    });

    $(document).on('click','#btnSaveTarget',function () {

        loading_button('#btnSaveTarget');

        var ID = $(this).attr('data-id');
        var survUserEmp = $('input[name=survUserEmp]:checked').val();
        var survUserStd = $('input[type=radio][name="surv_std_TypeUser"]:checked').val();
        var data = {
            action : 'updateTargetSurvey',
            ID : ID,
            surv_survey_usr_emp : survUserEmp,
            surv_survey_usr_std : survUserStd,
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudSurvey';

        $.post(url,{token:token},function (jsonResult) {

            toastr.success('Data saved','Success');

            setTimeout(function () {
                $('#btnSaveTarget').html('Save').prop('disabled',false);
            },500);

        });
    });

    $(document).on('click','#btnAddCustomTargetStd',function () {

        var ID = $(this).attr('data-id');

        var formUsr_ClassOf = $('#formUsr_ClassOf').val();
        var formUsr_ProdiID = $('#formUsr_ProdiID').val();
        var formUsr_StatusStudentID = $('#formUsr_StatusStudentID').val();

        if(formUsr_ClassOf!='' && formUsr_ClassOf!=null &&
            formUsr_ProdiID!='' && formUsr_ProdiID!=null &&
        formUsr_StatusStudentID!='' && formUsr_StatusStudentID!=null) {

            var ClassOf = formUsr_ClassOf;
            var ProdiID = formUsr_ProdiID.split('.')[0];
            var StatusStudentId = formUsr_StatusStudentID;

            var data = {
                action : 'setDataTargetUsrtStdDetail',
                ID : ID,
                dataForm : {
                    ClassOf : ClassOf,
                    ProdiID : ProdiID,
                    StatusStudentId : StatusStudentId
                }
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'apimenu/__crudSurvey';

            $.post(url,{token:token},function (jsonResult) {
                if(jsonResult.Status==1){
                    loadDataTargetCustomStudent(ID);
                    toastr.success('Data saved','Success');
                } else {
                    toastr.warning('Data has been entered','Warning');
                }
            });


        } else {
            toastr.warning('All form are required','Warning');
        }

    });

    $(document).on('click','.removeCustomTargetStudent',function () {
        if(confirm('Are you sure?')){

            $('.removeCustomTargetStudent').prop('disabled',true);

            var TargetUserID = $(this).attr('data-id');
            var ID = $(this).attr('data-id-survey');
            var data = {
                action : 'removeDataFromTargetUsrtStdDetail',
                ID : TargetUserID
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'apimenu/__crudSurvey';

            $.post(url,{token:token},function (jsonResult) {
                loadDataTargetCustomStudent(ID);
                toastr.success('Removed Data','Success');
            });
        }
    });


</script>