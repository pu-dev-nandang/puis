
<style>

    #tableData tr th, #tableData tr td {
        text-align: center;
    }

</style>

<div class="well">

    <div class="row">

        <div class="col-md-3 form-data-edit" style="border-right: 1px solid #CCCCCC;">

            <div style="text-align: right;">
                <button class="btn btn-success btn-round" id="btnLembagaAudit"><i class="fa fa-cog margin-right"></i> Lembaga Audit</button>
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
                    <button class="btn btn-primary btn-round" id="btnSaveForm"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div style="text-align: right;"> <button class="btn btn-success btn-round" id="btndownloaadExcel" title="Dowload Excel"><i class="fa fa-file-excel-o"></i> Excel </button></div> <p></p>
            <div id="viewData" class="table-responsive"></div>
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


    $("#btndownloaadExcel").click(function(){
       
        var akred = "0";
        var url = base_url_js+'agregator/excel-audit-keuangan-eksternal';  
        data = {
          akred : akred
        }
        var token = jwt_encode(data,"UAP)(*");
        FormSubmitAuto(url, 'POST', [
            { name: 'token', value: token },
        ]);
    })

    $('#btnLembagaAudit').click(function () {

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Lembaga Audit</h4>');

        var body = '<div class="row">' +
            '    <div class="col-md-5">' +
            '        <div class="well">' +
            '            <div class="form-group">' +
            '                <input class="hide" id="formID">' +
            '                <input class="form-control" id="formLembaga" placeholder="Input Lembaga...">' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <textarea class="form-control" id="formDescription" placeholder="Input Description..."></textarea>' +
            '            </div>' +
            '            <div class="form-group" style="text-align:right;">' +
            '                <button class="btn btn-success btn-round text-right" id="btnSaveLembaga"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>' +
            '            </div>' +
            '        </div>' +
            '   </br/>'+
            '    </div>' +
            '    ' +
            '    <div class="col-md-7">' +
            '        <table class="table table-striped table-bordered" id="tableViewLemabagaSurview">' +
            '            <thead>' +
            '            <tr style="background: #20485A;color: #FFFFFF;">' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Nama Lembaga</th>' +
            '                <th style="width: 2%;text-align: center"><i class="fa fa-cog"></i></th>' +
            '            </tr>' +
            '            </thead>' +
            '           <tbody id="listLembaga"></tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>';
        
        $('#GlobalModal .modal-body').html(body);

        loadDataLembagaAudit();

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-primary btn-round" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>');
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

                    if(jsonResult==0 || jsonResult=='0') { 
                        toastr.error('Maaf nama Lembaga sudah Ada!','Error');
                        $('#btnSaveLembaga').html('Save').prop('disabled',false);

                    } else {

                        $('#formID').val('');
                        $('#formLembaga').val('');
                        $('#formDescription').val('');

                        toastr.success('Data saved','Success');
                        loadDataLembagaAudit();
                        setTimeout(function () {
                            $('#btnSaveLembaga').html('Save').prop('disabled',false);
                        },500);
                    }

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
                        '<td style="text-align: left;"><div class="btn-group btnAction"> ' +
                        '    <button type="button" class="btn btn-sm btn-default dropdown-toggle dropdown-menu-left" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> ' +
                        '        <i class="fa fa-pencil"></i> <span class="caret"></span> '+
                        '    </button> '+
                        '    <ul class="dropdown-menu"> '+
                        '        <li><a class="btnEditLV" data-no="'+v.ID+'"  data-lembaga="'+v.Lembaga+'" data-desc="'+v.Description+'"><i class="fa fa fa-edit"></i> Edit</a></li> '+
                        '        <li role="separator" class="divider"></li> '+
                        '        <li><a class="btnDeleteLV" data-id="'+v.ID+'"><i class="fa fa fa-trash"></i> Remove</a></li> '+
                        '    </ul> '+
                        '</div> </td>'+
                        '</tr>');

                    $('#form_LembagaAuditID').append('<option value="'+v.ID+'">'+v.Lembaga+'</option>');
                });

            } else {
                $('#listLembaga').append('<tr><td colspan="3">-- Tidak ada data --</td></tr>');
            }

        });

    }

    $(document).on('click','.btnEditLV',function () {

        var ID = $(this).attr('data-no');
        var Lembaga = $(this).attr('data-lembaga');
        var Description = $(this).attr('data-desc');

        $('#formID').val(ID);
        $('#formLembaga').val(Lembaga);
        $('#formDescription').val(Description);

    });

    $(document).on('click','.btnDeleteLV',function () {
        
        if(confirm('Yakin Hapus data?')) {
    
            $('.btnDeleteLV').prop('disabled',true);
    
            var no = $(this).attr('data-id');
            var url = base_url_js+'api3/__crudAgregatorTB1';
    
            var data = {
                action: 'removeMasterAudit',
                ID : no
            };
    
            var token = jwt_encode(data,'UAP)(*');
    
            $.post(url,{token:token},function (result) {
    
                toastr.success('Data removed','Success');
                loadDataTable();

                $('#form_ID').val('');
                $('#form_LembagaAuditID').val('');
                $('#form_Year').val('');
                $('#form_Opinion').val('');
                $('#form_Description').val('');
                loadDataLembagaAudit();
                setTimeout(function () {
                    //loadDataTable();
                },500);
    
           });
        }
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

        $('#viewData').html('<table class="table table-striped table-bordered" id="tableData">' +
            '                    <thead>' +
            '                    <tr style="background: #20485A;color: #FFFFFF;">' +
            '                        <th style="width: 1%">No</th>' +
            '                        <th style="width: 15%">Lembaga</th>' +
            '                        <th style="width: 5%">Year</th>' +
            '                        <th style="width: 20%">Opini</th>' +
            '                        <th style="width: 5%"><i class="fa fa-cog"></i></th>' +
            '                        <th>Keterangan</th>' +
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
        });

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