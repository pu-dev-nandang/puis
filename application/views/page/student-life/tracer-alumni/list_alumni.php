

<style>
    #tableAlumni tr th, #tableAlumni tr td {
        text-align: center;
    }
    .active-td {
        background: lightyellow;
    }
    .active-td a {
        color: #f44336;
    }

    .table-details tbody>tr>td:first-child {
        width: 20%;
    }
    .table-details tbody>tr>td:nth-child(2){
        width: 2%;
    }
    .table-details tbody>tr>td {
        padding: 1px;
        border-top: none !important;
        background: transparent !important;
    }
</style>

<div class="row">
    <div class="col-md-4">
        <div class="well">
            <div class="row">
                <div class="col-md-12">
                    <select class="form-control" id="filterYear">
                        <option value="" selected>All graduation year</option>
                        <option disabled>----------------</option>
                    </select>
                </div>
                <div class="col-md-5 hide" style="border-left: 1px solid #CCCCCC;">
<!--                    <button class="btn btn-block btn-default" id="btnShowMasterCompany">Master Company</button>-->
                </div>
            </div>

        </div>
        <div id="viewTableListAlumni"></div>
    </div>
    <div class="col-md-8" style="border-left: 1px solid #CCCCCC;">
        <input class="hide" value="" id="dataNPMStd">
        <input class="hide" value="" id="IDEx">
        <input class="hide" value="" id="ExToken">
        <input class="hide" value="" id="dataName">
        <div id="loadViewAlumni"></div>

    </div>
</div>

<script>

    $(document).ready(function () {

        loadSelectOptionGraduationYear('#filterYear','');

        getAlmuni();
    });

    $('#filterYear').change(function () {
        getAlmuni();
    });

    function getAlmuni() {

        $('#viewTableListAlumni').html('<table class="table table-bordered" id="tableAlumni">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Alumni</th>' +
            '                <th  style="width: 7%;">Graduation Year</th>' +
            '            </tr>' +
            '            </thead>' +
            '        </table>');

        var filterYear = $('#filterYear').val();

        var token = jwt_encode({action : 'viewAlumni',Year:filterYear},'UAP)(*');
        var url = base_url_js+'api3/__crudTracerAlumni';

        var dataTable = $('#tableAlumni').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "NIM, Name"
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
        });

    }

    $(document).on('click','.showDetailAlumni',function () {

        var Name = $(this).attr('data-name');
        var NPM = $(this).attr('data-npm');
        $('#dataNPMStd').val(NPM);
        $('#dataName').val(Name);
        loadDataExperience();

    });

    $(document).on('click','#btnAddingExperience',function () {
        loadCrudExperience();
    });

    function loadMhsActive() {

        var NPM = $('#dataNPMStd').val();


        if(NPM!=''){

            $('#tableAlumni td.active-td').removeClass('active-td');
            $('a[data-npm='+NPM+']').parent().parent().parent().addClass('active-td');

        }


    }

    function loadDataExperience() {
        loading_page_simple('#loadViewAlumni','center');

        var NPM = $('#dataNPMStd').val();
        var Name = $('#dataName').val();

        var token = jwt_encode({action : 'showExperience',NPM : NPM},'UAP)(*');
        var url = base_url_js+'api3/__crudTracerAlumni';

        loadMhsActive();

        $.post(url,{token:token},function (jsonResult) {

            // console.log(jsonResult);

            setTimeout(function () {

                $('#loadViewAlumni').html('<div class="well">' +
                    '            <div class="row">' +
                    '                <div class="col-md-8">' +
                    '                    <h3 id="viewName" style="margin-top: 7px;font-weight: bold;">'+Name+'</h3>' +
                    '                </div>' +
                    '                <div class="col-md-4" style="text-align: right;">' +
                    '                    <button class="btn btn-success" id="btnAddingExperience">Add Experience</button>' +
                    '                </div>' +
                    '            </div>' +
                    '        </div>' +
                    '       <table class="table table-striped">' +
                    '            <thead>' +
                    '            <tr>' +
                    '                <th style="width: 20%;text-align: center;">Date</th>' +
                    '                <th style="text-align: center;">Company</th>' +
                    '                <th style="width: 10%;text-align: center;"><i class="fa fa-cog"></i></th>' +
                    '            </tr>' +
                    '            </thead>' +
                    '            <tbody id="listBodyExperience"></tbody>' +
                    '        </table>');

                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {

                        var StartMonth = (v.StartMonth!='' && v.StartMonth!=null) ? moment().months((parseInt(v.StartMonth)-1)).format('MMMM') : '';
                        var StartYear = (v.StartYear!='' && v.StartYear!=null) ? v.StartYear : '';

                        var EndMonth = (v.EndMonth!='' && v.EndMonth!=null)
                            ? moment().months((parseInt(v.EndMonth)-1)).format('MMMM') : '';
                        var EndYear = (v.EndYear!='' && v.EndYear!=null) ? v.EndYear : '';

                        var Last = (v.Status=='1') ? 'Present job' : EndMonth+' '+EndYear;

                        var pMonth = (moment().month() + 1);
                        var pYear = moment().year();

                        if(v.Status=='0'){
                            pMonth = v.EndMonth;
                            pYear = v.EndYear;
                        }

                        var y = (parseInt(pYear)-parseInt(StartYear));
                        var m = (parseInt(pMonth) >= parseInt(v.StartMonth)) ? Math.abs(parseInt(pMonth)-parseInt(v.StartMonth))
                            : 12 - Math.abs(parseInt(pMonth)-parseInt(v.StartMonth));

                        var viewLamaKerja = (y>0) ? y+' year '+m+' months' : m+' months';

                        var WorkSuitability = (v.WorkSuitability!='0')
                            ? '<i class="fa fa-check-circle margin-right" style="color: green;" aria-hidden="true"></i> Yes'
                            : '<i class="fa fa-times-circle margin-right" aria-hidden="true"></i> No';

                        var WorkSuitability = '<b style="color: red;">Low</b>';
                        if(v.WorkSuitability=='1' || v.WorkSuitability==1){
                            WorkSuitability = '<b style="color: royalblue;">Medium</b>';
                        }
                        else if(v.WorkSuitability=='2' || v.WorkSuitability==2){
                            WorkSuitability = '<b style="color: green;">High</b>';
                        }

                        var LastUpdated = "";
                        if(!jQuery.isEmptyObject(v.UpdatedBy)){
                            var LogsB = "";
                            var LogsA = "";
                            if(!jQuery.isEmptyObject(v.Logs)){
                                var parse = jQuery.parseJSON(v.Logs);

                                if(parse['before'] == 0){
                                    LogsB = "Low";
                                }else if(parse['before'] == 1){
                                    LogsB = "Middle";
                                }else if(parse['before'] == 2 ){
                                    LogsB = "High";
                                }else{
                                    LogsB = "unknown";
                                }

                                if(parse['after'] == 0){
                                    LogsA = "Low";
                                }else if(parse['after'] == 1){
                                    LogsA = "Middle";
                                }else if(parse['after'] == 2 ){
                                    LogsA = "High";
                                }else{
                                    LogsA = "unknown";
                                }


                            }
                            LastUpdated ='<tr><td colspan="3"><p>Last edited by</p><p><span>'+v.UpdatedBy+' at '+v.UpdatedAt+'</span>'+(!jQuery.isEmptyObject(v.Logs) ? ' - <span>Change work suitability from <b>'+LogsB+'</b> tobe <b>'+LogsA+'</b></span>' : '')+'</p></td></tr>';
                        }


                        $('#listBodyExperience').append('<tr>' +
                            '    <td>' +
                            '        <p>'+StartMonth+' '+StartYear+' - '+Last+'</p>' +
                            '        <p class="block-helped">'+viewLamaKerja+'</p>' +
                            '    </td>' +
                            '    <td>' +
                            '        <h3 style="margin-top: 0px;font-weight: bold;">'+v.Title+'<br/><small>'+v.PositionLevel+' | '+v.Address+'</small></h3>' +
                            '        <table class="table table-details">' +
                            '            <tr>' +
                            '                <td>Company</td>' +
                            '                <td>:</td>' +
                            '                <td>'+v.Company+'</td>' +
                            '            </tr>' +
                            '            <tr>' +
                            '            <tr>' +
                            '                <td>Industry</td>' +
                            '                <td>:</td>' +
                            '                <td>'+v.Industry+'</td>' +
                            '            </tr>' +
                            '            <tr>' +
                            '            <tr>' +
                            '                <td>Position Level</td>' +
                            '                <td>:</td>' +
                            '                <td>'+v.PositionLevel+'</td>' +
                            '            </tr>' +
                            '            <tr>' +
                            '                <td>Job Description</td>' +
                            '                <td>:</td>' +
                            '                <td>'+v.JobDescription+'</td>' +
                            '            </tr>' +
                            '            <tr>' +
                            '                <td>Work Suitability</td>' +
                            '                <td>:</td>' +
                            '                <td>'+WorkSuitability+'</td>' +
                            '            </tr>' +
                            '        </table>' +
                            '    </td>' +
                            '    <td style="text-align: center;">' +
                            '        <button class="btn btn-default btnEditExperience" data-token="'+jwt_encode(v,'UAP)(*')+'" data-id="'+v.ID+'">Edit</button>' +
                            '    </td>' +
                            '</tr>'+LastUpdated+'');
                    });
                } else {
                    $('#listBodyExperience').append('<tr>' +
                        '<td colspan="3" style="text-align: center;">Data not yet</td>' +
                        '</tr>');
                }

            },500);

        });

    }

    function loadCrudExperience() {

        var viewName = $('#viewName').text();
        var NPM = $('#dataNPMStd').val();

        var IDEx = $('#IDEx').val();

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+viewName+'</h4>');
        $('#GlobalModal .modal-body').html('<div class="row">' +
            '    <div class="col-md-12">' +
            '        <div class="well">' +
            '           <div style="text-align: right;margin-bottom: 5px;"><a href="'+base_url_js+'student-life/master/company/add-company" target="_blank">Add new master company</a></div>' +
            '            <input class="form-control" placeholder="Search company" id="filterCompany">' +
            '            <table class="table table-centre">' +
            '               <thead>' +
            '                   <tr>' +
            '                       <th style="width: 1%;">No</th>' +
            '                       <th>Company</th>' +
            '                       <th style="width: 5%;"><i class="fa fa-cog"></i></th>' +
            '                   </tr>' +
            '               </thead>' +
            '               <tbody id="showFilterCompany"></tbody>' +
            '            </table>' +
            '        </div>' +
            '    </div>' +
            '</div>' +
            '<div class="row">' +
            '<input class="hide" id="ID" value="'+IDEx+'">' +
            '    <div class="col-md-12" id="formAdd">' +
            '        <div class="form-group">' +
            '            <input class="form-control hide" id="CompanyID">' +
            '            <h3 id="viewCompany" style="text-align: center;color: royalblue;">-</h3>' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Title</label>' +
            '            <input class="form-control hide" id="NPM" value="'+NPM+'">' +
            '            <input class="form-control" id="Title">' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Job Level</label>' +
            '            <div class="row">' +
            '               <div class="col-xs-5">' +
            '                   <select class="form-control" id="JobType">' +
            '                       <option value="1">Bekerja</option>' +
            '                       <option value="2">Berwirausaha</option>' +
            '                   </select>' +
            '               </div>' +
            '               <div class="col-xs-7">' +
            '                   <select class="form-control" id="JobLevelID">' +
            '                   </select>' +
            '               </div>' +
            '           </div>' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Position Level</label>' +
            '            <select class="form-control" id="PositionLevelID"></select>' +
            '        </div>' +
            '        <div class="form-group">' +
            '           <div class="row">' +
            '               <div class="col-xs-4">' +
            '                   <label>Start Month</label>' +
            '                   <select class="form-control" id="StartMonth"></select>' +
            '               </div>' +
            '               <div class="col-xs-4">' +
            '                   <label>Star Year</label>' +
            '                   <input class="form-control" type="number" id="StartYear">' +
            '               </div>' +
            '               <div class="col-xs-4">' +
            '                   <label>Current position ?</label>'+
            '                   <select class="form-control" id="Status">' +
            '                     <option value="1">Yes</option>' +
            '                     <option value="0">No</option>' +
            '                   </select>' +
            '               </div>' +
            '           </div>' +
            '        </div>' +
            '        <div class="form-group hide" id="formEnd">' +
            '           <div class="row">' +
            '               <div class="col-xs-4">' +
            '                   <label>End Month</label>' +
            '                   <select class="form-control" id="EndMonth"></select>' +
            '               </div>' +
            '               <div class="col-xs-4">' +
            '                   <label>End Year</label>' +
            '                   <input class="form-control" type="number" id="EndYear">' +
            '               </div>' +
            '           </div>' +
            '        </div>' +
            '        <div class="form-group">' +
            '           <label>Work Suitability</label>' +
            '           <select class="form-control" style="width: 150px;" id="WorkSuitability">' +
            '               <option value="0">Low</option>' +
            '               <option value="1">Medium</option>' +
            '               <option value="2">High</option>' +
            '           </select>' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Job Description</label>' +
            '            <textarea class="form-control" rows="3" id="JobDescription"></textarea>' +
            '        </div>' +
            '    </div>' +
            '</div>');

        var Token = $('#ExToken').val();
        var dataToken = (Token!='') ? jwt_decode(Token,'UAP)(*') : [];

        var PositionLevelID = (Token!='') ? dataToken.PositionLevelID : '';
        loadSelectOptionExperiencePosistionLevel('#PositionLevelID',PositionLevelID);
        loadSelectOptionMonth('#StartMonth','');
        loadSelectOptionMonth('#EndMonth','');


        
        if(Token!=''){

            // console.log(dataToken);

            $('#Title').val(dataToken.Title);
            $('#viewCompany').html(dataToken.Company);
            $('#CompanyID').val(dataToken.CompanyID);

            $('#StartMonth').val(dataToken.StartMonth);
            $('#StartYear').val(dataToken.StartYear);
            $('#Status').val(dataToken.Status);
            $('#EndMonth').val(dataToken.EndMonth);
            $('#EndYear').val(dataToken.EndYear);
            $('#WorkSuitability').val(dataToken.WorkSuitability);
            $('#JobDescription').val(dataToken.JobDescription);

            if(dataToken.Status=='0'){
                $('#formEnd').removeClass('hide');
            } else {
                $('#formEnd').addClass('hide');
            }

            var JobType = (dataToken.JobType!='' && dataToken.JobType!=null) ? dataToken.JobType : '1';
            var JobLevelID = (dataToken.JobLevelID!='' && dataToken.JobLevelID!=null && parseInt(dataToken.JobLevelID)!=0)
                ? dataToken.JobLevelID : '';
            loadSelectOptionJobLevel(JobType,JobLevelID);

        } else {
            loadSelectOptionJobLevel('1','');
        }

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '<button type="button" class="btn btn-success" id="btnSave">Save</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        $('#Status').change(function () {

            var Status = $('#Status').val();
            if(Status=='1'){
                $('#formEnd').addClass('hide');
            } else {
                $('#formEnd').removeClass('hide');
            }

        });

        $('#btnSave').click(function () {

            var formTrue = true;
            var dataForm = '{';
            var lg = $('#formAdd .form-control').length;
            $('#formAdd .form-control').each(function (i,v) {

                var ID = $(this).attr('id');
                var v = $(this).val();
                var vr = (v!='') ? v : "";

                var Status = $('#Status').val();
                if(Status=='0'){
                    if(v==''){
                        formTrue = false;
                        $('#'+ID).css('border','1px solid red');
                    } else {
                        $('#'+ID).css('border','1px solid green');
                    }
                } else {
                    if(ID!='EndYear' && v==''){
                        formTrue = false;
                        $('#'+ID).css('border','1px solid red');
                    } else {
                        $('#'+ID).css('border','1px solid green');
                    }

                    if(ID=='EndMonth'){
                        vr = "";
                    }
                }


                var koma = ((i+1) == lg) ? '' : ',';

                dataForm = dataForm+'"'+ID+'" : "'+vr+'"'+koma;


                if((i+1) == lg){
                    dataForm = dataForm+'}';
                }

            });


            var dataForm = JSON.parse(dataForm);
            var ID = $('#ID').val();

            if(formTrue){

                loading_buttonSm('#btnSave');

                var data = {
                    action : 'updateDataExperience',
                    ID : (ID!='') ? ID : '',
                    dataForm : dataForm
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api3/__crudTracerAlumni';

                $.post(url,{token:token},function (result) {
                    toastr.success('Data saved');
                    loadDataExperience();
                    $('#IDEx').val('');
                    $('#ExToken').val('');
                    setTimeout(function () {
                        $('#GlobalModal').modal('hide');
                    },500);
                });

            } else {
                toastr.warning('All form are required');
            }


        });

    }

    $(document).on('click','.btnEditExperience',function () {
        var ID = $(this).attr('data-id');
        var Token = $(this).attr('data-token');
        $('#IDEx').val(ID);
        $('#ExToken').val(Token);
        loadCrudExperience();
    });

    $('#btnShowMasterCompany').click(function () {

        $('#GlobalModalLarge .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Master Company</h4>');

        $('#GlobalModalLarge .modal-body').html('<div class="row">' +
            '   <input class="hide" id="ID">' +
            '    <div class="col-md-4" style="border-right: 1px solid #CCCCCC;" id="dataForm">' +
            '        <div class="form-group">' +
            '            <label>Company Name</label>' +
            '            <input class="form-control" id="Name">' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Industry</label>' +
            '            <input class="form-control" id="Industry">' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Phone</label>' +
            '            <input class="form-control" id="Phone">' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Address</label>' +
            '            <textarea class="form-control" id="Address" rows="3"></textarea>' +
            '        </div>' +
            '        <div class="form-group" style="text-align: right;">' +
            '           <button class="btn btn-success" id="btnSave">Save</button>' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-8" id="divTableCompany">' +
            '    </div>' +
            '</div>');

        loadDataCompany();

        $('#btnSave').click(function () {
            var elm = $('#dataForm .form-control');
            var dataForm = '';

            var sumt = true;
            elm.each(function (i,v) {

                var koma = ((i+1)<elm.length) ? ',' : '';
                dataForm = dataForm+'"'+v.id+'":"'+v.value+'"'+koma;

                if(v.value==''){
                    $('#'+v.id).css('border','1px solid red');
                    sumt = false;
                } else {
                    $('#'+v.id).css('border','1px solid green');
                }

            });

            dataForm = JSON.parse('{'+dataForm+'}');

            if(sumt){

                loading_buttonSm('#btnSave');

                var ID = $('#ID').val();

                var data = {
                    action : 'saveMasterCompany',
                    ID : (ID!='' && ID!=null) ? ID : '',
                    dataForm : dataForm
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api3/__crudTracerAlumni';

                $.post(url,{token:token},function (result) {
                    toastr.success('Data saved','Success');
                    loadDataCompany();
                    $('#btnSave').html('Save').prop('disabled',false);
                    setTimeout(function () {
                        $('#ID').val('');
                        elm.each(function (i,v) {
                            $('#'+v.id).css('border','1px solid #ccc');
                            $('#'+v.id).val('');

                        });
                    },500);
                });

            }
            else {
                toastr.error('All form are required');
            }

        });

        $('#GlobalModalLarge .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModalLarge').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

    function loadDataCompany() {
        var data = {
            action : 'loadMasterCompany'
        };

        $('#divTableCompany').html('<table class="table table-centre table-striped" id="tableCompany">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Company</th>' +
            '                <th style="width: 5%;"><i class="fa fa-cog"></i></th>' +
            '                <th>Address</th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody id="listDataCompany">' +
            '            </tbody>' +
            '        </table>');

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudTracerAlumni';
        $.post(url,{token:token},function (jsonResult) {

           if(jsonResult.length>0){
            $.each(jsonResult,function (i,v) {

                var btnAct = '<div class="btn-group">' +
                    '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                    '    <i class="fa fa-edit"></i> <span class="caret"></span>' +
                    '  </button>' +
                    '  <ul class="dropdown-menu">' +
                    '    <li><a href="javascript:void(0);" class="btnCompanyEdit" data-tkn="'+jwt_encode(v,'UAP)(*')+'">Edit</a></li>' +
                    '    <li role="separator" class="divider"></li>' +
                    '    <li><a href="javascript:void(0);" class="btnCompanyRemove" data-id="'+v.ID+'">Remove</a></li>' +
                    '  </ul>' +
                    '</div>';

                $('#listDataCompany').append('<tr>' +
                    '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                    '<td style="text-align: left;"><b>'+v.Name+'</b>' +
                    '<br/>Industy : '+v.Industry+
                    '<br/>Phone : '+v.Phone+
                    '</td>' +
                    '<td>'+btnAct+'</td>' +
                    '<td style="text-align: left;">'+v.Address+'</td>' +
                    '</tr>');
            });

            $('#tableCompany').dataTable();
           }
        });
    }

    $(document).on('click','.btnCompanyEdit',function () {
       var tkn = $(this).attr('data-tkn');
       var d = jwt_decode(tkn,'UAP)(*');
       $('#ID').val(d.ID);
        $('#dataForm .form-control').each(function (i,v) {
            $('#'+v.id).val(d[v.id]);
        });
    });

    $(document).on('click','.btnCompanyRemove',function () {

        if(confirm('Are you sure ?')){
            var ID = $(this).attr('data-id');
            var data = {
                action : 'removeMasterCompany',
                ID : ID
            };
            var token = jwt_encode(data,'UAP)(*');

            var url = base_url_js+'api3/__crudTracerAlumni';
            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.Status==1 || jsonResult.Status=='1'){
                    toastr.success(jsonResult.Msg,'Success');
                    setTimeout(function (                                                                                                                                            ) {
                        loadDataCompany();
                    },500);
                } else {
                    toastr.warning(jsonResult.Msg,'Warning');
                }

            });
        }


    });

    $(document).on('keyup','#filterCompany',function () {
       var filterCompany = $('#filterCompany').val();
       if(filterCompany!='' && filterCompany!=null){
           var data = {
               action : 'searchMasterCompany',
               Key : filterCompany
           };
           var token = jwt_encode(data,'UAP)(*');

           var url = base_url_js+'api3/__crudTracerAlumni';

           $.post(url,{token:token},function (jsonResult) {

               $('#showFilterCompany').empty();

               if(jsonResult.length>0){
                   $.each(jsonResult,function (i,v) {
                       $('#showFilterCompany').append('<tr>' +
                           '<td>'+(i+1)+'</td>' +
                           '<td style="text-align: left;" id="NameC_'+v.ID+'">'+v.Name+'</td>' +
                           '<td><button class="btn btn-sm btn-default btnSelectCompany" data-id="'+v.ID+'"><i class="fa fa-download"></i></button></td>' +
                           '</tr>');
                   });
               }
           });

       }
    });
    $(document).on('click','.btnSelectCompany',function () {
       var ID = $(this).attr('data-id');
       var Name = $('#NameC_'+ID).text();

       $('#CompanyID').val(ID);
       $('#viewCompany').html(Name);

    });

    $(document).on('change','#JobType',function () {
        var JobType = $('#JobType').val();
        loadSelectOptionJobLevel(JobType)
    });

    function loadSelectOptionJobLevel(JobType,Selected) {

        var data = {
            action : 'getJobLevel',
            JobType : JobType
        };
        var token = jwt_encode(data,'UAP)(*');

        var url = base_url_js+'api3/__crudTracerAlumni';

        $.post(url,{token:token},function (jsonResult) {
            $('#JobLevelID').empty();
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var sc = (Selected!='' && v.ID==Selected) ? 'selected' : '';

                    $('#JobLevelID').append('<option value="'+v.ID+'" '+sc+'>'+v.Description+'</option>');
                });
            }

        });

    }
</script>
