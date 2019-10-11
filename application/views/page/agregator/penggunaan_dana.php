

<style>
    #dataDanaTable tr th, #dataDanaTable tr td {
        text-align: center;
    }
    #tablePD tr th, #tablePD tr td {
        text-align: center;
    }
</style>

<div class="well">
    <div class="row">

        <div class="col-md-3 form-data-edit">

            <div class="form-group">
                <label>Jenis Penggunaan</label>
                <input id="formID" class="hide">
                <select class="form-control" id="formJPID"></select>
                <a style="float: right;" href="javascript:void(0);" class="" id="btnCrud_JP"><i class="fa fa-edit margin-right"></i> Jenis Penggunaan</a>
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

            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="well">
                        <select class="form-control" id="filterYear"></select>
                    </div>
                </div>
                <div class="col-md-4" style="text-align: right;margin-bottom: 20px;">
                    <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered  dataTable2Excel table2excel_with_colors" data-name="Penggunaan-Dana"  id="tablePD">
                        <thead>
                        <tr>
                            <th rowspan="2" style="width: 1%;">No</th>
                            <th rowspan="2">Jenis Penggunaan</th>
                            <th colspan="3">Dana (Rupiah)</th>
                            <th rowspan="2" style="width: 20%;">Jumlah (Rupiah)</th>
                        </tr>
                        <tr>
                            <th style="width: 20%;">TS-2 <span id="viewTS2"></span></th>
                            <th style="width: 20%;">TS-1 <span id="viewTS1"></span></th>
                            <th style="width: 20%;">TS <span id="viewTS"></span></th>
                        </tr>
                        </thead>
                        <tbody id="loadListDana"></tbody>
                    </table>
                </div>
            </div>

            <div id="viewData2"></div>

        </div>

    </div>
</div>

<style>
    .c-block {
        cursor: text;
        text-decoration: none !important;
        color: #333333;
    }

    .c-block:hover {
        color: #333333 !important;
    }
</style>

<script>

    $(document).ready(function () {

        window.act = "<?= $accessUser; ?>";
        if(parseInt(act)<=0){
            $('.form-data-edit').remove();
        } else {

        }

        loadSOPenggunaanDanaYear('filterYear');

        $('#formPrice').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
        $('#formPrice').maskMoney('mask', '9894');
        loadJenisDana();



        var firstLoad = setInterval(function (args) {
            var filterYear = $('#filterYear').val();
            if(filterYear!='' && filterYear!=null){
                loadPenggunaanDana();
                clearInterval(firstLoad);
            }
        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);


    });

    $('#btnSave').click(function () {

        var formJPID = $('#formJPID').val();
        var formYear = $('#formYear').val();
        var formPrice = $('#formPrice').val();

        if(formYear !='' && formYear!=null &&
        formPrice !='' && formPrice!=null){

            loading_buttonSm('#btnSave');

            var data = {
                action : 'updatePenggunaanDana',
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
                // loadSOPenggunaanDanaYear('filterYear');
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

    $('#filterYear').change(function () {
       var filterYear = $('#filterYear').val();
       if(filterYear!='' && filterYear!=null){
           loadPenggunaanDana();
       }
    });

    function loadSOPenggunaanDanaYear(elm) {
        var data = {
            action : 'viewPenggunaanDanaYear'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB4';

        $('#'+elm).empty();

        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {
                    $('#'+elm).append('<option value="'+v.Year+'">Tahun '+v.Year+'</option>');
                });
            }

        });
    }

    function loadPenggunaanDana() {

        var filterYear = $('#filterYear').val();
        if(filterYear!='' && filterYear!=null){

            var Year = filterYear;
            var Year1 = parseInt(filterYear) - 1;
            var Year2 = parseInt(filterYear) - 2;

            $('#viewTS2').html('( '+Year2+' )');
            $('#viewTS1').html('( '+Year1+' )');
            $('#viewTS').html('( '+filterYear+' )');

            var data = {
                action : 'viewPenggunaanDana',
                Year : Year,
                Year1 : Year1,
                Year2 : Year2
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB4';

            $.post(url,{token:token},function (jsonResult) {

                $('#loadListDana').empty();

                if(jsonResult.length>0){

                    var no = 1;
                    var jml_th3 = 0;
                    var jml_th2 = 0;
                    var jml_th1 = 0;
                    var jml_jml = 0;


                    var class_block = (parseInt(act)<=0)? 'c-block' : '';


                    $.each(jsonResult,function (i,v) {

                        var jml = parseFloat(v.th3) + parseFloat(v.th2) + parseFloat(v.th1);



                        $('#loadListDana').append('<tr>' +
                            '<td>'+no+'</td>' +
                            '<td style="text-align: left;">'+v.Jenis+'</td>' +
                            '<td style="text-align: right;"><a href="javascript:void(0);" class="editNominal '+class_block+'" data-year="'+Year2+'" data-jpid="'+v.ID+'" data-v="'+parseFloat(v.th3)+'">'+formatRupiah(v.th3)+'</a></td>' +
                            '<td style="text-align: right;"><a href="javascript:void(0);" class="editNominal '+class_block+'" data-year="'+Year1+'" data-jpid="'+v.ID+'" data-v="'+parseFloat(v.th2)+'">'+formatRupiah(v.th2)+'</a></td>' +
                            '<td style="text-align: right;"><a href="javascript:void(0);" class="editNominal '+class_block+'" data-year="'+Year+'" data-jpid="'+v.ID+'" data-v="'+parseFloat(v.th1)+'">'+formatRupiah(v.th1)+'</a></td>' +
                            '<td style="text-align: right;">'+formatRupiah(jml)+'</td>' +
                            '</tr>');
                        jml_th3 = jml_th3+ parseFloat(v.th3);
                        jml_th2 = jml_th2+ parseFloat(v.th2);
                        jml_th1 = jml_th1+ parseFloat(v.th1);
                        jml_jml = jml_jml+ parseFloat(jml);

                        no += 1;
                        if(no==8){

                            $('#loadListDana').append('<tr>' +
                                '<td colspan="2" style="background: lightyellow;">Jumlah</td>' +
                                '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th3)+'</td>' +
                                '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th2)+'</td>' +
                                '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th1)+'</td>' +
                                '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_jml)+'</td>' +
                                '</tr>');

                            jml_th3 = 0;
                            jml_th2 = 0;
                            jml_th1 = 0;
                            jml_jml = 0;

                            no =1;
                        } else if(i==8){
                            $('#loadListDana').append('<tr>' +
                                '<td colspan="2" style="background: lightyellow;">Jumlah</td>' +
                                '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th3)+'</td>' +
                                '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th2)+'</td>' +
                                '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th1)+'</td>' +
                                '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_jml)+'</td>' +
                                '</tr>');
                        }
                    });

                }

                return false;

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



    }

    $(document).on('click','.editNominal',function () {
        var year = $(this).attr('data-year');
        var Dana = $(this).attr('data-v');
        var JPID = $(this).attr('data-jpid');


        $('#formJPID').val(JPID);
        $('#formYear').val(year);
        $('#formPrice').val(Dana);
        $('#formPrice').focus();


    });

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

            if(formJP_Jenis!='' && formJP_Jenis!=null && formJP_ID!='' && formJP_ID!=null){

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
                    loadPenggunaanDana();
                    setTimeout(function () {
                        $('#btnJPSave').prop('disabled',false).html('Save');
                        $('#formJP_ID').val('');
                        $('#formJP_Jenis').val('');
                    },500);
                });

            } else {
                toastr.warning('Hanya dapat digunakan untuk edit','Warning');
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