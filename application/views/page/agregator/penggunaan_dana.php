

<style>
    #dataDanaTable tr th, #dataDanaTable tr td {
        text-align: center;
    }
</style>

<div class="well">
    <div class="row">

        <div class="col-md-3">

            <div class="form-group">
                <label>Jenis Penggunaan</label>
                <input id="formID" class="hide">
                <select class="form-control" id="formJPID"></select>
                <a style="float: right;" href="javascript:void(0);" id="btnCrud_JP"><i class="fa fa-edit margin-right"></i> Jenis Penggunaan</a>
            </div>
            <div class="form-group">
                <label>Year</label>
                <input class="form-control" id="formYear" />
            </div>
            <div class="form-group">
                <label>Jumlah Dana</label>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">Rp</span>
                    <input type="text" class="form-control" id="formPrice">
                </div>
            </div>
            <div class="form-group" style="text-align: right;">
                <button class="btn btn-primary" id="btnSave">Save</button>
            </div>

        </div>
        <div class="col-md-9">

            <div id="viewData"></div>

        </div>

    </div>
</div>



<script>

    $(document).ready(function () {

        $('#formPrice').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
        $('#formPrice').maskMoney('mask', '9894');
        loadJenisDana();
        loadPenggunaanDana();

    });

    $('#btnSave').click(function () {

        var formID = $('#formID').val();
        var formJPID = $('#formJPID').val();
        var formYear = $('#formYear').val();
        var formPrice = $('#formPrice').val();

        if(formYear !='' && formYear!=null &&
        formPrice !='' && formPrice!=null){

            loading_buttonSm('#btnSave');

            var data = {
                action : 'updatePenggunaanDana',
                ID : (formID!='' && formID!=null) ? formID : '',
                dataForm : {
                    JPID : formJPID,
                    Year : formYear,
                    Price : clearDotMaskMoney(formPrice)
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB4';
            
            $.post(url,{token:token},function (result) {
                toastr.success('Data saved','Success');
                loadPenggunaanDana();
                setTimeout(function () {

                    $('#formID').val('');
                    $('#formYear').val('');
                    $('#formPrice').val(0);

                    $('#btnSave').prop('disabled',false).html('Save');
                },500);

            });

        } else {
            toastr.error('Year & Price is required','Error');
        }

    });

    function loadPenggunaanDana() {

        var data = {
            action : 'viewPenggunaanDana'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB4';

        $('#viewData').html('<table class="table" id="dataDanaTable">' +
            '                    <thead>' +
            '                    <tr>' +
            '                        <th style="width: 1%;">No</th>' +
            '                        <th>Jenis Penggunaan</th>' +
            '                        <th style="width: 10%;">Year</th>' +
            '                        <th style="width: 25%;">Dana</th>' +
            '                        <th style="width: 10%;"><i class="fa fa-cog"></i></th>' +
            '                    </tr>' +
            '                    </thead>' +
            '                    <tbody id="loadListDana"></tbody>' +
            '                </table>');

        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){

                $.each(jsonResult,function (i,v) {

                    var btn = '<div class="btn-group">' +
                        '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        '    <i class="fa fa-edit"></i> <span class="caret"></span>' +
                        '  </button>' +
                        '  <ul class="dropdown-menu">' +
                        '    <li><a href="javascript:void(0);" data-id="'+v.ID+'" class="btnEditPD">Edit</a></li>' +
                        '    <li role="separator" class="divider"></li>' +
                        '    <li><a href="javascript:void(0);" data-id="'+v.ID+'" class="btnRemovePD">Remove</a></li>' +
                        '  </ul>' +
                        '</div>' +
                        '<textarea id="viewData_'+v.ID+'" class="hide">'+JSON.stringify(v)+'</textarea>';

                    $('#loadListDana').append('<tr>' +
                        '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.JP+'</td>' +
                        '<td>'+v.Year+'</td>' +
                        '<td>'+formatRupiah(v.Price)+'</td>' +
                        '<td style="border-left: 1px solid #ccc;">'+btn+'</td>' +
                        '</tr>');
                })
            }

        });

    }

    $(document).on('click','.btnEditPD',function () {
        var ID = $(this).attr('data-id');
        var viewData_ = $('#viewData_'+ID).val();
        var d = JSON.parse(viewData_);

        $('#formID').val(d.ID);
        $('#formJPID').val(d.JPID);
        $('#formYear').val(d.Year);
        $('#formPrice').val(d.Price);

        $('#formPrice').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
        $('#formPrice').maskMoney('mask', '9894');


    });

    $(document).on('click','.btnRemovePD',function () {

        if(confirm('Hapus data?')){

            var ID = $(this).attr('data-id');
            var data = {
                action : 'removePenggunaanDana',
                ID : ID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB4';

            $.post(url,{token:token},function (result) {
                toastr.success('Data removed','Success');
                loadPenggunaanDana();
            });

        }

    });

    // ==============

    $('#btnCrud_JP').click(function () {

        var bodyModal = '<div class="well row">' +
            '    <div class="col-md-8">' +
            '        <input class="hide" id="formJP_ID">' +
            '        <input class="form-control" id="formJP_Jenis">' +
            '    </div>' +
            '    <div class="col-md-4">' +
            '        <button class="btn btn-block btn-success" id="btnJPSave">Save</button>' +
            '    </div>' +
            '</div>' +
            '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <hr/>' +
            '        <table class="table table-striped">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Jenis</th>' +
            '                <th style="width: 15%;"><i class="fa fa-cog"></i></th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody id="listData"></tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>';

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Sumber Dana</h4>');
        $('#GlobalModal .modal-body').html(bodyModal);
        loadJenisDana();
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        $('#btnJPSave').click(function () {

            var formJP_ID = $('#formJP_ID').val();
            var formJP_Jenis = $('#formJP_Jenis').val();

            if(formJP_Jenis!='' && formJP_Jenis!=null){

                loading_buttonSm('#btnJPSave');

                var data = {
                    action : 'updateJenisDana',
                    ID : formJP_ID,
                    Jenis : formJP_Jenis
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api3/__crudAgregatorTB4';

                $.post(url,{token:token},function (result) {
                    loadJenisDana();
                    setTimeout(function () {
                        $('#btnJPSave').prop('disabled',false).html('Save');
                        $('#formJP_ID').val('');
                        $('#formJP_Jenis').val('');
                    },500);
                });

            }

        });

    });

    function loadJenisDana() {

        var data = {
            action : 'viewJenisDana'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB4';

        $.post(url,{token:token},function (jsonResult) {

            $('#listData,#formJPID').empty();
            if(jsonResult.length>0){

                $.each(jsonResult,function (i,v) {
                    $('#listData').append('<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td>'+v.Jenis+'</td>' +
                        '<td><button class="btn btn-sm btn-default btnEditJD" data-id="'+v.ID+'" data-j="'+v.Jenis+'"><i class="fa fa-edit"></i></button></td>' +
                        '</tr>');

                    $('#formJPID').append('<option value="'+v.ID+'">'+v.Jenis+'</option>');
                });

            }

        });

    }

    $(document).on('click','.btnEditJD',function () {
        var j = $(this).attr('data-j');
        var ID = $(this).attr('data-id');

        $('#formJP_ID').val(ID);
        $('#formJP_Jenis').val(j);
    })


</script>