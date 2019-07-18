
<div class="well">

    <div class="row">

        <div class="col-md-3 form-data-edit" style="border-right: 1px solid #CCCCCC;">

            <div style="text-align: right;">
                <button class="btn btn-default" id="btnLembagaAudit"><i class="fa fa-cog margin-right"></i> Lembaga Audit</button>
            </div>

            <div>
                <div class="form-group">
                    <label>Lembaga Audit</label>
                    <input class="hide" id="form_ID">
                    <select class="form-control" id="form_LembagaAuditID"></select>
                </div>
                <div class="form-group">
                    <label>Tahun Perolehan</label>
                    <input class="form-control" type="number" id="form_Year">
                </div>
                <div class="form-group">
                    <label>Opini</label>
                    <textarea class="form-control" rows="3" id="form_Opinion"></textarea>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea class="form-control" rows="3" id="form_Description"></textarea>
                </div>
                <div style="text-align: right;">
                    <button class="btn btn-primary" id="btnSaveForm">Save</button>
                </div>
            </div>

        </div>
        <div class="col-md-9">
            <div id="viewData"></div>
        </div>

    </div>

</div>

<script>

    $(document).ready(function () {

        window.act = "<?= $accessUser; ?>";
        if(parseInt(act)<=0){
            $('.form-data-edit').remove();
        } else {
            loadDataLembagaAudit();
        }
        loadDataTable();
    });

    $('#btnLembagaAudit').click(function () {

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Lembaga Audit</h4>');

        var body = '<div class="row">' +
            '    <div class="col-md-5">' +
            '        <div class="well">' +
            '            <div class="form-group">' +
            '                <input class="hide" id="formID">' +
            '                <input class="form-control" id="formLembaga" placeholder="Input lembaga..">' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <textarea class="form-control" id="formDescription" placeholder="Input description..."></textarea>' +
            '            </div>' +
            '            <div>' +
            '                <button class="btn btn-success" id="btnSaveLembaga">Save</button>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    ' +
            '    <div class="col-md-7">' +
            '        <table class="table table-striped" id="tableViewLemabagaSurview">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Lembaga</th>' +
            '                <th style="width: 2%;"><i class="fa fa-cog"></i></th>' +
            '            </tr>' +
            '            </thead>' +
            '           <tbody id="listLembaga"></tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>';
        
        $('#GlobalModal .modal-body').html(body);

        loadDataLembagaAudit();

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        $('#btnSaveLembaga').click(function () {

            var formID = $('#formID').val();
            var formLembaga = $('#formLembaga').val();
            var formDescription = $('#formDescription').val();

            if(formLembaga!='' && formLembaga!=null &&
                formDescription!='' && formDescription!=null){

                loading_buttonSm('#btnSaveLembaga');

                var data = {
                    action : 'updateLembagaAudit' ,
                    ID : (formID!='' && formID!=null) ? formID : '',
                    Lembaga : formLembaga,
                    Description : formDescription
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api3/__crudLembagaSurview';

                $.post(url,{token:token},function (jsonResult) {

                    $('#formID').val('');
                    $('#formLembaga').val('');
                    $('#formDescription').val('');

                    toastr.success('Data saved','Success');

                    loadDataLembagaAudit();

                    setTimeout(function () {

                        $('#btnSaveLembaga').html('Save').prop('disabled',false);

                    },500);

                });

            } else {
                toastr.warning('All form is required','Warning');
            }



        });
        
    });
    
    function loadDataLembagaAudit() {

        var url = base_url_js+'api3/__crudLembagaSurview';
        var token = jwt_encode({action : 'readLembagaAudit'},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            $('#listLembaga,#formAE_LembagaID').empty();

            if(jsonResult.length>0){

                $.each(jsonResult,function (i,v) {
                    var no = i+1;
                    $('#listLembaga').append('<tr>' +
                        '<td>'+no+'</td>' +
                        '<td style="text-align: left;"><b>'+v.Lembaga+'</b><br/>'+v.Description+'</td>' +
                        '<td><button class="btn btn-default btn-sm btnEditLV" data-no="'+no+'"><i class="fa fa-edit"></i></button>' +
                        '<textarea id="btnEditLV_'+no+'" class="hide">'+JSON.stringify(v)+'</textarea></td>' +
                        '</tr>');

                    $('#form_LembagaAuditID').append('<option value="'+v.ID+'">'+v.Lembaga+'</option>');
                });

            } else {
                $('#listLembaga').append('<tr><td colspan="3">-- Tidak ada data --</td></tr>');
            }

        });

    }

    $(document).on('click','.btnEditLV',function () {

        var no = $(this).attr('data-no');
        var dataForm = $('#btnEditLV_'+no).val();
        var dataForm = JSON.parse(dataForm);

        $('#formID').val(dataForm.ID);
        $('#formLembaga').val(dataForm.Lembaga);
        $('#formDescription').val(dataForm.Description);

    });

    // ================ =====================
    $('#btnSaveForm').click(function () {

        var form_ID = $('#form_ID').val();
        var form_LembagaAuditID = $('#form_LembagaAuditID').val();
        var form_Year = $('#form_Year').val();
        var form_Opinion = $('#form_Opinion').val();
        var form_Description = $('#form_Description').val();

        if(form_LembagaAuditID!='' && form_LembagaAuditID!=null &&
            form_Year!='' && form_Year!=null &&
        form_Opinion!='' && form_Opinion!=null &&
        form_Description!='' && form_Description!=null){

            loading_button('#btnSaveForm');

            var data = {
                action : 'crudFEA',
                ID : (form_ID!='' && form_ID!=null) ? form_ID : '',
                dataForm : {
                    LembagaAuditID : form_LembagaAuditID,
                    Year : form_Year,
                    Opinion : form_Opinion,
                    Description : form_Description
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB1';

            $.post(url,{token:token},function (result) {

                toastr.success('Data saved','Success');

                // var formAE_DueDate = $('#formAE_DueDate').datepicker("getDate");

                loadDataTable();

                $('#form_ID').val('');
                $('#form_LembagaAuditID').val('');
                $('#form_Year').val('');
                $('#form_Opinion').val('');
                $('#form_Description').val('');

                setTimeout(function () {
                    $('#btnSaveForm').html('Save').prop('disabled',false);
                },500);

            })

        } else {
            toastr.warning('Please, fill in the required form','Warning');
        }

    });

    function loadDataTable() {

        $('#viewData').html('<table class="table table-striped" id="tableData">' +
            '                    <thead>' +
            '                    <tr>' +
            '                        <th style="width: 1%">No</th>' +
            '                        <th style="width: 15%">Lembaga</th>' +
            '                        <th style="width: 5%">Year</th>' +
            '                        <th style="width: 10%">Opini</th>' +
            '                        <th>Keterangan</th>' +
            '                        <th style="width: 5%"><i class="fa fa-cog"></i></th>' +
            '                    </tr>' +
            '                    </thead>' +
            '                   <tbody id="listData"></tbody>' +
            '                </table>');

        var token = jwt_encode({action:'viewListAKE',Previlege:act},'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB1';

        var dataTable = $('#tableData').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Lembaga, Sertifikat, Lingkup, Tingkat"
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

    $(document).on('click','.btnEditAE',function () {

        var no = $(this).attr('data-no');
        var dataDetail = $('#viewDetail_'+no).val();
        dataDetail = JSON.parse(dataDetail);


        $('#form_ID').val(dataDetail.ID);

        $('#form_LembagaAuditID').val(dataDetail.LembagaAuditID);
        $('#form_Year').val(dataDetail.Year);
        $('#form_Opinion').val(dataDetail.Opinion);
        $('#form_Description').val(dataDetail.Description);


    });

</script>