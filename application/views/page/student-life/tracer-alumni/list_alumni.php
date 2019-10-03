

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
        <table class="table table-bordered" id="tableAlumni">
            <thead>
            <tr>
                <th style="width: 1%;">No</th>
                <th>Alumni</th>
            </tr>
            </thead>
        </table>
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


        getAlmuni();
    });

    function getAlmuni() {

        var token = jwt_encode({action : 'viewAlumni'},'UAP)(*');
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

                        var EndMonth = (v.EndMonth!='' && v.EndMonth!=null) ? moment().months((parseInt(v.EndMonth)-1)).format('MMMM') : '';
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

                        $('#listBodyExperience').append('<tr>' +
                            '    <td>' +
                            '        <p>'+StartMonth+' '+StartYear+' - '+Last+'</p>' +
                            '        <p class="block-helped">'+viewLamaKerja+'</p>' +
                            '    </td>' +
                            '    <td>' +
                            '        <h3 style="margin-top: 0px;font-weight: bold;">'+v.Title+'<br/><small>'+v.PositionLevel+' | '+v.Location+'</small></h3>' +
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
                            '</tr>');
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
            '<input class="hide" id="ID" value="'+IDEx+'">' +
            '    <div class="col-md-12" id="formAdd">' +
            '        <div class="form-group">' +
            '            <label>Title</label>' +
            '            <input class="form-control hide" id="NPM" value="'+NPM+'">' +
            '            <input class="form-control" id="Title">' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Company</label>' +
            '            <input class="form-control" id="Company">' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Industry</label>' +
            '            <input class="form-control" id="Industry">' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Position Level</label>' +
            '            <select class="form-control" id="PositionLevelID"></select>' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Location</label>' +
            '            <textarea class="form-control" id="Location" rows="2"></textarea>' +
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
            '               <option value="1">Yes</option>' +
            '               <option value="0">No</option>' +
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

            $('#Title').val(dataToken.Title);
            $('#Company').val(dataToken.Company);
            $('#Industry').val(dataToken.Industry);

            $('#Location').val(dataToken.Location);
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

</script>