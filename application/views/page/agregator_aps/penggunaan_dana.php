<h3 align="center">Penggunaan Dana</h3><br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
                    
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

            <div class="col-md-3" id = "inputForm">

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
                    <label>Jumlah Dana UPPS</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Rp</span>
                        <input type="text" class="form-control" id="formPriceUPPS">
                    </div>
                </div>
                <div class="form-group">
                    <label>Jumlah Dana PS</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Rp</span>
                        <input type="text" class="form-control" id="formPricePS">
                    </div>
                </div>
                <div class="form-group" style="text-align: right;">
                    <button class="btn btn-primary" id="btnSave">Save</button>
                </div>
            </div>
            <div class="col-md-9" id = "ViewData">
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
                        <table class="table table-bordered  dataTable2Excel table2excel_with_colors" data-name="Penggunaan-Dana-aps"  id="tablePD">
                            <thead>
                            <tr>
                                <th rowspan="2" style="width: 1%;">No</th>
                                <th rowspan="2">Jenis Penggunaan</th>
                                <th colspan="3">Unit Pengelolaan Program Studi (Rupiah)</th>
                                <th rowspan="2" style="width: 10%;">Rata - Rata</th>
                                <th colspan="3">Program Studi (Rupiah)</th>
                                <th rowspan="2" style="width: 10%;">Rata - Rata</th>
                            </tr>
                            <tr>
                                <th style="width: 10%;">TS-2 <span id="viewTS2"></span></th>
                                <th style="width: 10%;">TS-1 <span id="viewTS1"></span></th>
                                <th style="width: 10%;">TS <span id="viewTS"></span></th>
                                <th style="width: 10%;">TS-2 <span id="viewTS5"></span></th>
                                <th style="width: 10%;">TS-1 <span id="viewTS4"></span></th>
                                <th style="width: 10%;">TS <span id="viewTS3"></span></th>
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

                
<script>


        $(document).ready(function () {

            // var firstLoad = setInterval(function () {
            //     var filterProdi = $('#filterProdi').val();
            //     if(filterProdi!='' && filterProdi!=null){
            //         loadPage();
            //         clearInterval(firstLoad);
            //     }
            // },1000);

            loadSOPenggunaanDanaYear('filterYear');

            $('#formPriceUPPS').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
            $('#formPriceUPPS').maskMoney('mask', '9894');

            $('#formPricePS').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
            $('#formPricePS').maskMoney('mask', '9894');
            loadJenisDana();
            var firstLoad_year = setInterval(function (args) {
                // var filterYear = $('#filterYear').val();
                var filterProdi = $('#filterProdi').val();
                if(filterProdi!='' && filterProdi!=null){
                    $('#viewProdiID').html(filterProdi);
                    $('#viewProdiName').html($('#filterProdi option:selected').text());
                     loadPage();
                    clearInterval(firstLoad_year);
                }
            },1000);
            
            setTimeout(function () {
                //clearInterval(firstLoad);
                clearInterval(firstLoad_year);
            },5000);

        });

        function setDefaultTable(Year = '')
        {
            loadSOPenggunaanDanaYear('filterYear');
            var firstLoad_year = setInterval(function (args) {
            var filterYear = $('#filterYear').val();
                if(filterYear!='' && filterYear!=null){
                     loadPage();
                    clearInterval(firstLoad_year);
                }
            },1000);
            
            setTimeout(function () {
                clearInterval(firstLoad_year);
            },5000);
        }

        $('#btnSave').click(function () {

            var filterProdi = $('#filterProdi').val();
            var P = filterProdi.split('.');
            var ProdiID = P[0];
            var formJPID = $('#formJPID').val();
            var formYear = $('#formYear').val();
            var formPriceUPPS = $('#formPriceUPPS').val();

            var formPricePS = $('#formPricePS').val();

            if(formYear !='' && formYear!=null &&
            formPriceUPPS !='' && formPriceUPPS!=null&&
            formPricePS !='' && formPricePS!=null){

                loading_buttonSm('#btnSave');

                var data = {
                    action : 'updatePenggunaanDana_aps',
                    dataForm : {
                        JPID : formJPID,
                        Year : formYear,
                        ProdiID : ProdiID,
                        PriceUPPS : clearDotMaskMoney(formPriceUPPS),
                        PricePS : clearDotMaskMoney(formPricePS),
                    }
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api3/__crudAgregatorTB4';
                
                $.post(url,{token:token},function (result) {
                    toastr.success('Data saved','Success');
                    setDefaultTable(formYear);

                    setTimeout(function () {

                        $('#formID').val('');
                        $('#formYear').val('');
                        $('#formPriceUPPS').val(0);
                        $('#formPricePS').val(0);

                        $('#btnSave').prop('disabled',false).html('Save');
                    },500);

                });

            } else {
                toastr.error('Year & Price is required','Error');
            }

        });

        $('#filterProdi').change(function () {
            loadPage()
        });
        $('#filterYear').change(function () {
           var filterYear = $('#filterYear').val();
           if(filterYear!='' && filterYear!=null){
               loadPenggunaanDana(filterProdi);
           }
        });

        function loadPage() {
            var filterProdi = $('#filterProdi').val();
            if(filterProdi!='' && filterProdi!=null){
                $('#viewProdiID').html(filterProdi);
                $('#viewProdiName').html($('#filterProdi option:selected').text());
                loadPenggunaanDana(filterProdi);
            }
        }

        $(document).on('click','.btnRemovePD',function () {

            if(confirm('Hapus data?')){

                var ID = $(this).attr('data-id');
                var data = {
                    action : 'removePenggunaanDana_aps',
                ID : ID
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api3/__crudAgregatorTB4';

                $.post(url,{token:token},function (result) {
                    toastr.success('Data removed','Success');
                    loadPenggunaanDana(filterProdi);
                });

            }

        });

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
                '<h4 class="modal-title">Jenis Pengeluaran</h4>');
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
                        action : 'updateJenisDana_aps',
                        ID : formJP_ID,
                        Jenis : formJP_Jenis
                    };

                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'api3/__crudAgregatorTB4';

                    $.post(url,{token:token},function (result) {
                        loadJenisDana();
                        loadPenggunaanDana(filterProdi);
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
function loadSOPenggunaanDanaYear(elm) {
    var data = {
        action : 'viewPenggunaanDanaYear_aps'
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
function loadPenggunaanDana(filterProdi) {

    var filterYear = $('#filterYear').val();
    if(filterYear!='' && filterYear!=null){

        var Year = filterYear;
        var Year1 = parseInt(filterYear) - 1;
        var Year2 = parseInt(filterYear) - 2;
        var Year3 = filterYear;
        var Year4 = parseInt(filterYear) - 1;
        var Year5 = parseInt(filterYear) - 2;
        var P = filterProdi.split('.');
        var ProdiID = P[0];

        $('#viewTS5').html('( '+Year5+' )');
        $('#viewTS4').html('( '+Year4+' )');
        $('#viewTS3').html('( '+filterYear+' )');
        $('#viewTS2').html('( '+Year2+' )');
        $('#viewTS1').html('( '+Year1+' )');
        $('#viewTS').html('( '+filterYear+' )');

        var data = {
            action : 'viewPenggunaanDana_aps',
            Year : Year,
            Year1 : Year1,
            Year2 : Year2,
            Year3 : Year3,
            Year4 : Year4,
            Year5 : Year5,
            ProdiID : ProdiID,
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
                var jml_rataUPPS = 0;
                var jml_th6 = 0;
                var jml_th5 = 0;
                var jml_th4 = 0;
                var jml_rataPS = 0;


                $.each(jsonResult,function (i,v) {

                    var jml = parseFloat(v.th3) + parseFloat(v.th2) + parseFloat(v.th1);
                    var rataUPPS = jml/3;
                    rataUPPS = getCustomtoFixed(rataUPPS,2);
                    var jml = parseFloat(v.th4) + parseFloat(v.th5) + parseFloat(v.th6);
                    var rataPS = jml/3;
                    rataPS = getCustomtoFixed(rataPS,2);
                    $('#loadListDana').append('<tr>' +
                        '<td>'+no+'</td>' +
                        '<td style="text-align: left;">'+v.Jenis+'</td>' +
                        '<td style="text-align: right;"><a href="javascript:void(0);" class="editNominal" data-year="'+Year2+'" data-jpid="'+v.ID+'" data-v="'+parseFloat(v.th3)+'">'+formatRupiah(v.th3)+'</a></td>' +
                        '<td style="text-align: right;"><a href="javascript:void(0);" class="editNominal" data-year="'+Year1+'" data-jpid="'+v.ID+'" data-v="'+parseFloat(v.th2)+'">'+formatRupiah(v.th2)+'</a></td>' +
                        '<td style="text-align: right;"><a href="javascript:void(0);" class="editNominal" data-year="'+Year+'" data-jpid="'+v.ID+'" data-v="'+parseFloat(v.th1)+'">'+formatRupiah(v.th1)+'</a></td>' +
                        '<td style="text-align: right;">'+formatRupiah(rataUPPS)+'</td>' +
                        '<td style="text-align: right;"><a href="javascript:void(0);" class="editNominal" data-year="'+Year5+'" data-jpid="'+v.ID+'" data-v="'+parseFloat(v.th6)+'">'+formatRupiah(v.th6)+'</a></td>' +
                        '<td style="text-align: right;"><a href="javascript:void(0);" class="editNominal" data-year="'+Year4+'" data-jpid="'+v.ID+'" data-v="'+parseFloat(v.th5)+'">'+formatRupiah(v.th5)+'</a></td>' +
                        '<td style="text-align: right;"><a href="javascript:void(0);" class="editNominal" data-year="'+Year3+'" data-jpid="'+v.ID+'" data-v="'+parseFloat(v.th4)+'">'+formatRupiah(v.th4)+'</a></td>' +
                        '<td style="text-align: right;">'+formatRupiah(rataPS)+'</td>' +
                        '</tr>');
                    jml_th3 = jml_th3+ parseFloat(v.th3);
                    jml_th2 = jml_th2+ parseFloat(v.th2);
                    jml_th1 = jml_th1+ parseFloat(v.th1);
                    jml_rataUPPS = jml_rataUPPS+ parseFloat(rataUPPS);
                    jml_th6 = jml_th6+ parseFloat(v.th6);
                    jml_th5 = jml_th5+ parseFloat(v.th5);
                    jml_th4 = jml_th4+ parseFloat(v.th4);
                    jml_rataPS = jml_rataPS+ parseFloat(rataPS);

                    no += 1;
                    if(no==7){

                        $('#loadListDana').append('<tr>' +
                            '<td colspan="2" style="background: lightyellow;">Jumlah</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th3)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th2)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th1)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_rataUPPS)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th6)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th5)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th4)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_rataPS)+'</td>' +
                            '</tr>');

                        jml_th3 = 0;
                        jml_th2 = 0;
                        jml_th1 = 0;
                        jml_rataUPPS = 0;
                        jml_th6 = 0;
                        jml_th5 = 0;
                        jml_th4 = 0;
                        jml_rataPS = 0;

                        no =7;
                    } else if(i==7){
                        $('#loadListDana').append('<tr>' +
                            '<td colspan="2" style="background: lightyellow;">Jumlah</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th3)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th2)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th1)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_rataUPPS)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th6)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th5)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th4)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_rataPS)+'</td>' +
                            '</tr>');

                        jml_th3 = 0;
                        jml_th2 = 0;
                        jml_th1 = 0;
                        jml_rataUPPS = 0;
                        jml_th6 = 0;
                        jml_th5 = 0;
                        jml_th4 = 0;
                        jml_rataPS = 0;

                    } else if(i==10){
                        $('#loadListDana').append('<tr>' +
                            '<td colspan="2" style="background: lightyellow;">Jumlah</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th3)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th2)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th1)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_rataUPPS)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th6)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th5)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_th4)+'</td>' +
                            '<td style="text-align: right;background: lightyellow;">'+formatRupiah(jml_rataPS)+'</td>' +
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
function loadJenisDana() {

    var data = {
        action : 'viewJenisDana_aps'
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