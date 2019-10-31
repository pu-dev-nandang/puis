


<div class="row">
    <div class="col-md-6" style="">

        <div class="well">
            <h3 style="margin-top: 0px;">Dosen</h3>
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <select class="form-control" id="filterYearDsn"></select>
                </div>
                <div class="col-md-4" style="text-align: right;">
                    <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <hr/>
                    <table class="table table-striped table-bordered table-centre dataTable2Excel" data-name="dsn-produk-jasa-yg-diadopsi">
                        <thead>
                        <tr>
                            <th style="width: 1%;">No</th>
                            <th style="width: 5%;">Thn</th>
                            <th style="width: 20%;">Nama Produk / Jasa</th>
                            <th>Deskripsi Produk / Jasa</th>
                            <th style="width: 15%;">Tingkat Kesiapterapan Teknologi</th>
                        </tr>
                        </thead>
                        <tbody id="listDsn"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-6" style="">

        <div class="well">
            <h3 style="margin-top: 0px;">Mahasiswa</h3>
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <select class="form-control" id="filterYearMhs"></select>
                </div>
                <div class="col-md-4" style="text-align: right;">
                    <button onclick="saveTable2Excel('dataTable2Excel2')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <hr/>
                    <table class="table table-striped table-bordered table-centre dataTable2Excel2" data-name="mhs-produk-jasa-yg-diadopsi">
                        <thead>
                        <tr>
                            <th style="width: 1%;">No</th>
                            <th style="width: 5%;">Thn</th>
                            <th style="width: 20%;">Nama Produk / Jasa</th>
                            <th>Deskripsi Produk / Jasa</th>
                            <th style="width: 15%;">Tingkat Kesiapterapan Teknologi</th>
                        </tr>
                        </thead>
                        <tbody id="listMhs"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>



<script>

    var dbDsn = 'db_agregator.produk_jasa';
    var dbMhs = 'db_agregator.produk_jasa_mhs';

    $(document).ready(function () {
        selectOptionYearProduk('#filterYearDsn',dbDsn);
        selectOptionYearProduk('#filterYearMhs',dbMhs);

        var firsld1 = setInterval(function () {
            var filterYearDsn = $('#filterYearDsn').val();
            if(filterYearDsn!='' && filterYearDsn!=null){
                loadDataDsn();
                clearInterval(firsld1);
            }
        },1000);

        var firsld2 = setInterval(function () {
            var filterYearMhs = $('#filterYearMhs').val();
            if(filterYearMhs!='' && filterYearMhs!=null){
                loadDataMhs();
                clearInterval(firsld2);
            }
        },1000);


        setTimeout(function () {
            clearInterval(firsld1);
            clearInterval(firsld2);
            },5000);

    });

    function selectOptionYearProduk(element,db) {

        var url = base_url_js+'api3/__crudAgregatorTB5';
        var data = {
            action : 'getYearJasaAdopsi',
            db : db
        };

        var token = jwt_encode(data,'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {

            $(element).empty();
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {
                    $(element).append('<option value="'+v.Year+'">Tahun '+v.Year+'</option>');
                });
            }

        });

    }

    $('#filterYearDsn').change(function () {
        loadDataDsn();
    });

    $('#filterYearMhs').change(function () {
        loadDataMhs();
    });

    function loadDataDsn() {
        var filterYearDsn = $('#filterYearDsn').val();
        if(filterYearDsn!='' && filterYearDsn!=null){

            var url = base_url_js+'api3/__crudAgregatorTB5';
            var data = {
                action : 'getDetailJasaAdopsi',
                Year : filterYearDsn,
                db : dbDsn
            };

            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {
                console.log(jsonResult);
                $('#listDsn').empty();
                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {
                        $('#listDsn').append('<tr>' +
                            '<td>'+(i+1)+'</td>' +
                            '<td>'+v.Year+'</td>' +
                            '<td style="text-align: left;">'+v.NamaProdukJasa+'</td>' +
                            '<td style="text-align: left;">'+v.DeskripsiProdukJasa+'</td>' +
                            '<td></td>' +
                            '</tr>');
                    });
                } else {
                    $('#listDsn').append('<tr><td colspan="5">Tidak ada data</td></tr>');
                }
            });

        }
    }

    function loadDataMhs() {
        var filterYearMhs = $('#filterYearMhs').val();
        if(filterYearMhs!='' && filterYearMhs!=null){

            var url = base_url_js+'api3/__crudAgregatorTB5';
            var data = {
                action : 'getDetailJasaAdopsi',
                Year : filterYearMhs,
                db : dbMhs
            };

            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {
                console.log(jsonResult);
                $('#listMhs').empty();
                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {
                        $('#listMhs').append('<tr>' +
                            '<td>'+(i+1)+'</td>' +
                            '<td>'+v.Year+'</td>' +
                            '<td style="text-align: left;">'+v.NamaProdukJasa+'</td>' +
                            '<td style="text-align: left;">'+v.DeskripsiProdukJasa+'</td>' +
                            '<td></td>' +
                            '</tr>');
                    });
                } else {
                    $('#listMhs').append('<tr><td colspan="5">Tidak ada data</td></tr>');
                }
            });

        }
    }
</script>