<style>
    #IncidentDate {
        background: #ffffff;
        cursor: pointer;
        color: #333333;
    }

    #tableData tr td:nth-child(1), #tableData tr td:nth-child(5) {
        border-right: 1px solid #CCCCCC;
    }
</style>

<div class="row" style="margin-bottom: 15px;">

    <div class="col-md-4 col-md-offset-4">
        <div class="well">
            <select class="form-control filter-table" id="filterBaseProdi">
                <option value="">--- All Study Program ---</option>
                <option disabled>------------------</option>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div style="text-align: right;">
            <button class="btn btn-default" id="btnCrudMedical">Add Medical History</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" id="loadTable"></div>
</div>

<script>
    $(document).ready(function () {
        loadSelectOptionBaseProdi('#filterBaseProdi','');
        getDataTableMedicalHistory();
    });

    $('#filterBaseProdi').change(function () {
        getDataTableMedicalHistory();
    });

    $('#btnCrudMedical').click(function () {
        loadModalMedicalRecord('');
    });

    function loadModalMedicalRecord(MedicalHistoryID) {

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Medical Record</h4>');

        var htmlss = '<div class="col-md-12"><div class="row">' +
            '            <div class="form-group">' +
            '                <label>Search Student</label>' +
            '                <input id="filterStudent" class="form-control" placeholder="Search student by NPM or Name">' +
            '                <input id="MedicalHistoryID" value="'+MedicalHistoryID+'" class="hide">' +
            '                <input id="NPM" class="hide formMedicalRecord">' +
            '                <div style="margin-top: 15px;margin-bottom: 15px;"><table class="table table-striped table-centre"><tbody id="loadStudent"></tbody></table></div><hr/>' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <label>Student Selected</label>' +
            '                <div id="viewStudent">-</div>' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <label>Date of Incident</label>' +
            '                <input id="IncidentDate" class="form-control formMedicalRecord" readonly >' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <label>Description</label>' +
            '                <textarea id="Description" class="form-control formMedicalRecord" rows="3"></textarea>' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <label>Executor</label>' +
            '                <input id="Executor" class="form-control formMedicalRecord">' +
            '            </div>' +
            '        </div></div>';

        $('#GlobalModal .modal-body').html(htmlss);

        $( "#IncidentDate" )
            .datepicker({
                showOtherMonths:true,
                autoSize: true,
                dateFormat: 'dd MM yy',
                // minDate: new Date(moment().year(),moment().month(),moment().date()),
                onSelect : function () {
                    // var data_date = $(this).val().split(' ');
                    // var nextelement = $(this).attr('nextelement');
                    // nextDatePick(data_date,nextelement);
                }
            });

        if(MedicalHistoryID!=''){
            var data = $('#txt_'+MedicalHistoryID).val();
            var v = JSON.parse(data);

            $('#NPM').val((v.NPM!='' && v.NPM!=null)? v.NPM : '');
            $('#viewStudent').html('<b style="color: darkgreen;">'+v.NPM+' - '+v.Name+'</b>');
            $('#Description').val((v.Description!='' && v.Description!=null)? v.Description : '');
            $('#Executor').val((v.Executor!='' && v.Executor!=null)? v.Executor : '');

            (v.IncidentDate!=='0000-00-00' && v.IncidentDate!==null) ? $('#IncidentDate').datepicker('setDate',new Date(v.IncidentDate)) : '';
        }

        $('#GlobalModal .modal-footer').html('' +
            '<button class="btn btn-success" id="btnSaveMedicalRecord">Save</button> ' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        $('#btnSaveMedicalRecord').click(function () {

            loading_buttonSm('#btnSaveMedicalRecord');
            $('button[data-dismiss=modal]').prop('disabled',true);

            var MedicalHistoryID = ($('#MedicalHistoryID').val()!='' && $('#MedicalHistoryID').val()!=null)
                ? $('#MedicalHistoryID').val() : '';

            var data = {
                action : 'setDataMedicalHistory',
                ID : MedicalHistoryID,
                UserID : sessionNIP,
                dataForm : {
                    NPM : $('#NPM').val(),
                    IncidentDate : ($('#IncidentDate').datepicker("getDate")!=null)
                        ? moment($('#IncidentDate').datepicker("getDate")).format('YYYY-MM-DD') : '',
                    Description : $('#Description').val(),
                    Executor : $('#Executor').val(),
                    Entred : '1',
                    Updated : '1'
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudMedicalRecord';
            $.post(url,{token:token},function (jsonResult) {
                getDataTableMedicalHistory();
                toastr.success('Data saved','Success');
                setTimeout(function () {
                    $('#GlobalModal').modal('hide');
                },500);

            });

        });
    }

    $(document).on('keyup','#filterStudent',function () {

        var filterStudent = $('#filterStudent').val();

        if(filterStudent!='' && filterStudent!=null) {
            var url = base_url_js + 'api/__getStudentsServerSide';
            $.post(url,{key : filterStudent},function (jsonResult) {

                $('#loadStudent').empty();
                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {
                        $('#loadStudent').append('<tr>' +
                            '<td style="width: 1%;">'+(i+1)+'</td>' +
                            '<td style="text-align: left;"><b>'+v.Name+'</b><br/>'+v.NPM+'</td>' +
                            '<td style="width: 5%;"><button class="btn btn-sm btn-default btnAddStudent" data-name="'+v.Name+'" data-npm="'+v.NPM+'"><i class="fa fa-plus"></i></button></td>' +
                            '</tr>');
                    });
                } else {
                    $('#loadStudent').html('<tr>' +
                        '<td colspan="3">Student not yet</td>' +
                        '</tr>');
                }

            });
        }

    });
    $(document).on('click','.btnAddStudent',function () {
        var Name = $(this).attr('data-name');
        var NPM = $(this).attr('data-npm');

        $('#viewStudent').html('<b style="color: darkgreen;">'+NPM+' - '+Name+'</b>');
        $('#NPM').val(NPM);

    });

    function getDataTableMedicalHistory() {

        $('#loadTable').html('<table class="table table-striped table-centre" id="tableData">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th style="width: 20%;">Student</th>' +
            '                <th style="width: 15%;">Date of Incident</th>' +
            '                <th>Description</th>' +
            '                <th style="width: 15%;">Executor</th>' +
            '                <th style="width: 5%;"><i class="fa fa-cog"></i></th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody></tbody>' +
            '        </table>');

        var filterBaseProdi = $('#filterBaseProdi').val();
        var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null) ? filterBaseProdi.split('.')[0] : '';

        var data = {
            action : 'getDataMedicalHistory',
            ProdiID : ProdiID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudMedicalRecord';
        var dataTable = $('#tableData').DataTable( {
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

    $(document).on('click','.btnEditMedicalHistory',function () {
        var MedicalHistoryID = $(this).attr('data-id');
        loadModalMedicalRecord(MedicalHistoryID);
    });

    $(document).on('click','.btnRemoveMedicalHistory',function () {
        var MedicalRecordID = $(this).attr('data-id');
        if(confirm('Are you sure?')){
            var data = {
                action : 'removeDataMedicalHistory',
                ID : MedicalRecordID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudMedicalRecord';

            $.post(url,{token:token},function (result) {
                toastr.success('Data removed','Success');
                getDataTableMedicalHistory();
            });
        }
    });

</script>