

<style>
    #tablePerolehanDana tr th, #tablePerolehanDana tr td {
        text-align: center;
    }
    #tablePerolehanDana tr td:first-child {
        text-align: left;
    }

    #dataTablePD tr th, #dataTablePD tr td {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-4">
        <div class="well" style="min-height: 100px;">
            <table class="table" id="tablePerolehanDana">
                <thead>
                <tr>
                    <td style="width: 37%;">Tahun</td>
                    <td style="width: 1%;">:</td>
                    <td>
                        <input class="hide" id="ID">
                        <input class="form-control" id="Year"/>
                    </td>
                </tr>
                </thead>
                <thead>
                <tr>
                    <th colspan="3">1. Mahasiswa</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>SPP</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="1_SPP">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Sumbangan Lainnya</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="1_SumbanganLainnya">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Lain - lain</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="1_Lain">
                        </div>
                    </td>
                </tr>
                </tbody>
                <thead>
                <tr>
                    <th colspan="3">2. Kementerian/ Yayasan</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td>Anggaran rutin*)</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="2_AnggranRutin">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Anggaran pembangunan</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="2_AnggaranPembangunan">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Hibah penelitian</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="2_HibahPenelitian">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Hibah PkM</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="2_HibahPKM">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Lain-lain</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="2_Lain">
                        </div>
                    </td>
                </tr>
                </tbody>


                <thead>
                <tr>
                    <th colspan="3">3. PT sendiri**)</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Jasa layanan profesi dan/atau keahlian</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="3_Jasa">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Produk institusi</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="3_ProdukInstitusi">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Kerjasama kelembagaan (pemerintah atau swasta)</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="3_Kerjasama">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Lain-lain</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="3_Lain">
                        </div>
                    </td>
                </tr>
                </tbody>

                <thead>
                <tr>
                    <th colspan="3">4. Sumber lain (dalam dan luar negeri)</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Hibah</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="4_Hibah">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Dana lestari dan filantropis</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="4_DanaLestari">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Lain-lain</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="4_Lain">
                        </div>
                    </td>
                </tr>
                </tbody>


                <thead>
                <tr>
                    <th colspan="3">5. Dana penelitian dan PkM ***)</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Dana penelitian</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="5_DanaPenelitian">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Dana PkM</td>
                    <td>:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rp</span>
                            <input type="text" class="form-control formMoney" id="5_DanaPKM">
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="3" style="text-align: right;">
                        <button class="btn btn-success" id="btnSave">Save</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-8">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="well">
                    <select class="form-control" id="filterYear"></select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="text-align: right;margin-bottom: 20px;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>

            <div class="col-md-12" id="viewDataTable">

            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        $('.formMoney').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
        $('.formMoney').maskMoney('mask', '9894');

        loadSelectOptionYearSD();

        var firstLoad = setInterval(function () {
            var filterYear = $('#filterYear').val();
            if(filterYear!='' && filterYear!=null){
                loadDataMoney();
                clearInterval(firstLoad);
            }
        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },3000);

    });

    $('#filterYear').change(function () {
        var filterYear = $('#filterYear').val();
        if(filterYear!='' && filterYear!=null){
            loadDataMoney();
        }
    });

    $('#btnSave').click(function () {

        var ID = $('#ID').val();
        var Year = $('#Year').val();

        if(Year!='' && Year!=null){

            loading_buttonSm('#btnSave');

            var dataForm = {
                Year : Year
            };

            $('.formMoney').each(function () {
                var id = $(this).attr('id');
                var v = $(this).val();

                dataForm[id] = (v!='' && v!=null)
                    ? clearDotMaskMoney(v) : 0;
            });

            var data = {
                action : 'updateNewSumberDana',
                ID : (ID!='' && ID!=null) ? ID : '',
                dataForm : dataForm
            };

            // console.log(data);

            var token = jwt_encode(data,'UAP)(*');

            var url = base_url_js+'api3/__crudAgregatorTB4';
            
            $.post(url,{token:token},function (result) {

                if(parseInt(result)>0){
                    toastr.success('Data saved','Success');
                    setTimeout(function (args) {
                        window.location.href="";
                    },500);
                } else {
                    toastr.warning('Year is exist','Warning');
                    setTimeout(function () {
                        $('#btnSave').html('Save').prop('disabled',false);
                    },500);
                }



            });


        }


    });

    function loadSelectOptionYearSD() {
        var data = {
            action : 'readYearSDNewSumberDana'
        };

        // console.log(data);

        var token = jwt_encode(data,'UAP)(*');

        var url = base_url_js+'api3/__crudAgregatorTB4';

        $.post(url,{token:token},function (jsonResult) {
            $('#filterYear').empty();
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {
                    $('#filterYear').append('<option value="'+v.Year+'">'+v.Year+'</option>');
                })
            }
        });
    }
    
    function loadDataMoney() {
        var filterYear = $('#filterYear').val();
        if(filterYear!='' && filterYear!=null){

            $('#viewDataTable').html('<table class="table table-bordered dataTable2Excel table2excel_with_colors" data-name="Perolehan-Dana" id="dataTablePD">' +
                '                    <thead>' +
                '                    <tr>' +
                '                        <th rowspan="2">No</th>' +
                '                        <th rowspan="2">Sumber Dana</th>' +
                '                        <th rowspan="2">Jenis Dana</th>' +
                '                        <th colspan="3">Jumlah Dana (Rupiah)</th>' +
                '                        <th style="width: 20%;" rowspan="2">Jumlah (Rupiah)</th>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <th style="width: 20%;">TS-2 <span id="Year_TS2"></span></th>' +
                '                        <th style="width: 20%;">TS-1 <span id="Year_TS1"></span></th>' +
                '                        <th style="width: 20%;">TS <span id="Year_TS"></span></th>' +
                '                    </tr>' +
                '                    </thead>' +
                '' +
                '                    <tbody>' +
                '                    <tr>' +
                '                        <td rowspan="4">1</td>' +
                '                        <td style="text-align: left;" rowspan="3">Mahasiswa</td>' +
                '                        <td style="text-align: left;">SPP</td>' +
                '                        <td id="1_SPP_TS2"></td>' +
                '                        <td id="1_SPP_TS1"></td>' +
                '                        <td id="1_SPP_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <td style="text-align: left;">Sumbangan lainnya</td>' +
                '                        <td id="1_SumbanganLainnya_TS2"></td>' +
                '                        <td id="1_SumbanganLainnya_TS1"></td>' +
                '                        <td id="1_SumbanganLainnya_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <td style="text-align: left;">Lain-lain</td>' +
                '                        <td id="1_Lain_TS2"></td>' +
                '                        <td id="1_Lain_TS1"></td>' +
                '                        <td id="1_Lain_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <th style="background: lightyellow;" colspan="2">Jumlah</th>' +
                '                        <th style="background: lightyellow;" id="Jumlah_B_TS_3"></th>' +
                '                        <th style="background: lightyellow;" id="Jumlah_B_TS_2"></th>' +
                '                        <th style="background: lightyellow;" id="Jumlah_B_TS_1"></th>' +
                '                    </tr>' +
                '                    </tbody>' +
                '' +
                '                    <tbody>' +
                '                    <tr>' +
                '                        <td rowspan="6">2</td>' +
                '                        <td rowspan="5">Kementerian/ Yayasan</td>' +
                '                        <td style="text-align: left;">Anggaran rutin*)</td>' +
                '                        <td id="2_AnggranRutin_TS2"></td>' +
                '                        <td id="2_AnggranRutin_TS1"></td>' +
                '                        <td id="2_AnggranRutin_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <td style="text-align: left;">Anggaran pembangunan</td>' +
                '                        <td id="2_AnggaranPembangunan_TS2"></td>' +
                '                        <td id="2_AnggaranPembangunan_TS1"></td>' +
                '                        <td id="2_AnggaranPembangunan_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <td style="text-align: left;">Hibah penelitian</td>' +
                '                        <td id="2_HibahPenelitian_TS2"></td>' +
                '                        <td id="2_HibahPenelitian_TS1"></td>' +
                '                        <td id="2_HibahPenelitian_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <td style="text-align: left;">Hibah PkM</td>' +
                '                        <td id="2_HibahPKM_TS2"></td>' +
                '                        <td id="2_HibahPKM_TS1"></td>' +
                '                        <td id="2_HibahPKM_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <td style="text-align: left;">Lain-lain</td>' +
                '                        <td id="2_Lain_TS2"></td>' +
                '                        <td id="2_Lain_TS1"></td>' +
                '                        <td id="2_Lain_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <th style="background: lightyellow;" colspan="2">Jumlah</th>' +
                '                        <th style="background: lightyellow;" id="Jumlah_B_TS_3_2"></th>' +
                '                        <th style="background: lightyellow;" id="Jumlah_B_TS_2_2"></th>' +
                '                        <th style="background: lightyellow;" id="Jumlah_B_TS_1_2"></th>' +
                '                    </tr>' +
                '                    </tbody>' +
                '' +
                '                    <tbody>' +
                '                    <tr>' +
                '                        <td rowspan="5">3</td>' +
                '                        <td rowspan="4">PT sendiri**)</td>' +
                '                        <td style="text-align: left;">Jasa layanan profesi dan/atau keahlian</td>' +
                '                        <td id="3_Jasa_TS2"></td>' +
                '                        <td id="3_Jasa_TS1"></td>' +
                '                        <td id="3_Jasa_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <td style="text-align: left;">Produk institusi</td>' +
                '                        <td id="3_ProdukInstitusi_TS2"></td>' +
                '                        <td id="3_ProdukInstitusi_TS1"></td>' +
                '                        <td id="3_ProdukInstitusi_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <td style="text-align: left;">Kerjasama kelembagaan (pemerintah atau swasta)</td>' +
                '                        <td id="3_Kerjasama_TS2"></td>' +
                '                        <td id="3_Kerjasama_TS1"></td>' +
                '                        <td id="3_Kerjasama_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <td style="text-align: left;">Lain-lain</td>' +
                '                        <td id="3_Lain_TS2"></td>' +
                '                        <td id="3_Lain_TS1"></td>' +
                '                        <td id="3_Lain_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <th style="background: lightyellow;" colspan="2">Jumlah</th>' +
                '                        <th style="background: lightyellow;" id="Jumlah_B_TS_3_3"></th>' +
                '                        <th style="background: lightyellow;" id="Jumlah_B_TS_2_3"></th>' +
                '                        <th style="background: lightyellow;" id="Jumlah_B_TS_1_3"></th>' +
                '                    </tr>' +
                '                    </tbody>' +
                '' +
                '                    <tbody>' +
                '                    <tr>' +
                '                        <td rowspan="4">4</td>' +
                '                        <td rowspan="3">Sumber lain (dalam dan luar negeri)</td>' +
                '                        <td style="text-align: left;">Hibah</td>' +
                '                        <td id="4_Hibah_TS2"></td>' +
                '                        <td id="4_Hibah_TS1"></td>' +
                '                        <td id="4_Hibah_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <td style="text-align: left;">Dana lestari dan filantropis</td>' +
                '                        <td id="4_DanaLestari_TS2"></td>' +
                '                        <td id="4_DanaLestari_TS1"></td>' +
                '                        <td id="4_DanaLestari_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <td style="text-align: left;">Lain-lain</td>' +
                '                        <td id="4_Lain_TS2"></td>' +
                '                        <td id="4_Lain_TS1"></td>' +
                '                        <td id="4_Lain_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <th  style="background: lightyellow;" colspan="2">Jumlah</th>' +
                '                        <td  style="background: lightyellow;" id="Jumlah_B_TS_3_4"></td>' +
                '                        <td  style="background: lightyellow;" id="Jumlah_B_TS_2_4"></td>' +
                '                        <td  style="background: lightyellow;" id="Jumlah_B_TS_1_4"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <th  style="background: #e0eeff;" colspan="3">Jumlah (1 + 2 + 3 + 4)</th>' +
                '                        <th  style="background: #e0eeff;" id="jumlahSum_3"></th>' +
                '                        <th  style="background: #e0eeff;" id="jumlahSum_2"></th>' +
                '                        <th  style="background: #e0eeff;" id="jumlahSum_1"></th>' +
                '                    </tr>' +
                '                    </tbody>' +
                '' +
                '                    <tbody>' +
                '                    <tr>' +
                '                        <td rowspan="3">5</td>' +
                '                        <td rowspan="2">Dana penelitian dan PkM ***)</td>' +
                '                        <td style="text-align: left;">Dana penelitian</td>' +
                '                        <td id="5_DanaPenelitian_TS2"></td>' +
                '                        <td id="5_DanaPenelitian_TS1"></td>' +
                '                        <td id="5_DanaPenelitian_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <td style="text-align: left;">Dana PkM</td>' +
                '                        <td id="5_DanaPKM_TS2"></td>' +
                '                        <td id="5_DanaPKM_TS1"></td>' +
                '                        <td id="5_DanaPKM_TS"></td>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <th  style="background: lightyellow;" colspan="2">Jumlah (5)</th>' +
                '                        <th  style="background: lightyellow;" id="Jumlah_B_TS_3_5"></th>' +
                '                        <th  style="background: lightyellow;" id="Jumlah_B_TS_2_5"></th>' +
                '                        <th  style="background: lightyellow;" id="Jumlah_B_TS_1_5"></th>' +
                '                    </tr>' +
                '                    <tr>' +
                '                        <th style="background: #e0eeff;" colspan="3">Jumlah (1 + 2 + 3 + 4 + 5)</th>' +
                '                        <th style="background: #e0eeff;" id="jumlahSum_3_1"></th>' +
                '                        <th style="background: #e0eeff;" id="jumlahSum_2_1"></th>' +
                '                        <th style="background: #e0eeff;" id="jumlahSum_1_1"></th>' +
                '                    </tr>' +
                '                    </tbody>' +
                '                </table>');

            // Load Year
            $('#Year_TS').html(' ( '+filterYear+' )');
            $('#Year_TS1').html(' ( '+(parseInt(filterYear)-1)+' )');
            $('#Year_TS2').html(' ( '+(parseInt(filterYear)-2)+' )');

            var data = {
                action : 'readNewSumberDana',
                Year : filterYear
            };

            var token = jwt_encode(data,'UAP)(*');

            var url = base_url_js+'api3/__crudAgregatorTB4';

            $.post(url,{token:token},function (jsonResult) {

                var d_TS = (jsonResult.TS.length>0) ? jsonResult.TS[0] : 0;
                var d_TS1 = (jsonResult.TS1.length>0) ? jsonResult.TS1[0] : 0;
                var d_TS2 = (jsonResult.TS2.length>0) ? jsonResult.TS2[0] : 0;

                var keyNames_TS = (d_TS!=0) ? Object.keys(d_TS) : [];
                // console.log(d_TS,d_TS1,d_TS2);
                //
                // return false;

                var Jumlah_B_TS_1 = 0;
                var Jumlah_B_TS_2 = 0;
                var Jumlah_B_TS_3 = 0;

                var Jumlah_B_TS_1_2 = 0;
                var Jumlah_B_TS_2_2 = 0;
                var Jumlah_B_TS_3_2 = 0;

                var Jumlah_B_TS_1_3 = 0;
                var Jumlah_B_TS_2_3 = 0;
                var Jumlah_B_TS_3_3 = 0;

                var Jumlah_B_TS_1_4 = 0;
                var Jumlah_B_TS_2_4 = 0;
                var Jumlah_B_TS_3_4 = 0;

                var Jumlah_B_TS_1_5 = 0;
                var Jumlah_B_TS_2_5 = 0;
                var Jumlah_B_TS_3_5 = 0;
                if(keyNames_TS.length>0){
                    for(var i=0;i<keyNames_TS.length;i++){

                        if(i>=2 && i<=18){

                            var vts = (d_TS!=0) ? d_TS[keyNames_TS[i]] : d_TS;
                            $('#'+keyNames_TS[i]+'_TS').html('<div style="text-align: right;">'+formatRupiah(vts)+'</div>');

                            var vts1 = (d_TS1!=0) ? d_TS1[keyNames_TS[i]] : d_TS1;
                            $('#'+keyNames_TS[i]+'_TS1').html('<div style="text-align: right;">'+formatRupiah(vts1)+'</div>');

                            var vts2 = (d_TS2!=0) ? d_TS2[keyNames_TS[i]] : d_TS2;
                            $('#'+keyNames_TS[i]+'_TS2').html('<div style="text-align: right;">'+formatRupiah(vts2)+'</div>');

                            var t = parseFloat(vts) + parseFloat(vts1) + parseFloat(vts2);
                            $('#'+keyNames_TS[i]+'_TS2').parent().append('<td><div style="text-align: right;">'+formatRupiah(t)+'</div></td>');

                            // 1
                            if(i>=2 && i<=4){
                                Jumlah_B_TS_1 = Jumlah_B_TS_1 + parseFloat(vts);
                                Jumlah_B_TS_2 = Jumlah_B_TS_2 + parseFloat(vts1);
                                Jumlah_B_TS_3 = Jumlah_B_TS_3 + parseFloat(vts2);

                                if(i==4){
                                    $('#Jumlah_B_TS_3').html('<div style="text-align: right">'+formatRupiah(Jumlah_B_TS_3)+'</div>');
                                    $('#Jumlah_B_TS_2').html('<div style="text-align: right">'+formatRupiah(Jumlah_B_TS_2)+'</div>');
                                    $('#Jumlah_B_TS_1').html('<div style="text-align: right">'+formatRupiah(Jumlah_B_TS_1)+'</div>');

                                    var t = parseFloat(Jumlah_B_TS_1) + parseFloat(Jumlah_B_TS_2) + parseFloat(Jumlah_B_TS_3);

                                    $('#Jumlah_B_TS_1').parent().append('<td><div style="text-align: right;">'+formatRupiah(t)+'</div></td>');
                                }
                            }

                            // 2
                            if(i>=5 && i<=9){
                                Jumlah_B_TS_1_2 = Jumlah_B_TS_1_2 + parseFloat(vts);
                                Jumlah_B_TS_2_2 = Jumlah_B_TS_2_2 + parseFloat(vts1);
                                Jumlah_B_TS_3_2 = Jumlah_B_TS_3_2 + parseFloat(vts2);

                                if(i==9){
                                    $('#Jumlah_B_TS_1_2').html('<div style="text-align: right">'+formatRupiah(Jumlah_B_TS_1_2)+'</div>');
                                    $('#Jumlah_B_TS_2_2').html('<div style="text-align: right">'+formatRupiah(Jumlah_B_TS_2_2)+'</div>');
                                    $('#Jumlah_B_TS_3_2').html('<div style="text-align: right">'+formatRupiah(Jumlah_B_TS_3_2)+'</div>');

                                    var t = parseFloat(Jumlah_B_TS_1_2) + parseFloat(Jumlah_B_TS_2_2) + parseFloat(Jumlah_B_TS_3_2);

                                    $('#Jumlah_B_TS_3_2').parent().append('<td><div style="text-align: right;">'+formatRupiah(t)+'</div></td>');
                                }
                            }

                            // 3
                            if(i>=10 && i<=13){
                                Jumlah_B_TS_1_3 = Jumlah_B_TS_1_3 + parseFloat(vts);
                                Jumlah_B_TS_2_3 = Jumlah_B_TS_2_3 + parseFloat(vts1);
                                Jumlah_B_TS_3_3 = Jumlah_B_TS_3_3 + parseFloat(vts2);

                                if(i==13){
                                    $('#Jumlah_B_TS_1_3').html('<div style="text-align: right">'+formatRupiah(Jumlah_B_TS_1_3)+'</div>');
                                    $('#Jumlah_B_TS_2_3').html('<div style="text-align: right">'+formatRupiah(Jumlah_B_TS_2_3)+'</div>');
                                    $('#Jumlah_B_TS_3_3').html('<div style="text-align: right">'+formatRupiah(Jumlah_B_TS_3_3)+'</div>');

                                    var t = parseFloat(Jumlah_B_TS_1_3) + parseFloat(Jumlah_B_TS_2_3) + parseFloat(Jumlah_B_TS_3_3);

                                    $('#Jumlah_B_TS_3_3').parent().append('<td><div style="text-align: right;">'+formatRupiah(t)+'</div></td>');
                                }
                            }

                            // 4
                            if(i>=14 && i<=16){
                                Jumlah_B_TS_1_4 = Jumlah_B_TS_1_4 + parseFloat(vts);
                                Jumlah_B_TS_2_4 = Jumlah_B_TS_2_4 + parseFloat(vts1);
                                Jumlah_B_TS_3_4 = Jumlah_B_TS_3_4 + parseFloat(vts2);

                                if(i==16){
                                    $('#Jumlah_B_TS_1_4').html('<div style="text-align: right">'+formatRupiah(Jumlah_B_TS_1_4)+'</div>');
                                    $('#Jumlah_B_TS_2_4').html('<div style="text-align: right">'+formatRupiah(Jumlah_B_TS_2_4)+'</div>');
                                    $('#Jumlah_B_TS_3_4').html('<div style="text-align: right">'+formatRupiah(Jumlah_B_TS_3_4)+'</div>');

                                    var t = parseFloat(Jumlah_B_TS_1_4) + parseFloat(Jumlah_B_TS_2_4) + parseFloat(Jumlah_B_TS_3_4);

                                    $('#Jumlah_B_TS_3_4').parent().append('<td><div style="text-align: right;">'+formatRupiah(t)+'</div></td>');

                                    // Jumlah (1 + 2 + 3 + 4)
                                    var jumlahSum_1 = Jumlah_B_TS_1 + Jumlah_B_TS_1_2 + Jumlah_B_TS_1_3 + Jumlah_B_TS_1_4;
                                    var jumlahSum_2 = Jumlah_B_TS_2 + Jumlah_B_TS_2_2 + Jumlah_B_TS_2_3 + Jumlah_B_TS_2_4;
                                    var jumlahSum_3 = Jumlah_B_TS_3 + Jumlah_B_TS_3_2 + Jumlah_B_TS_3_3 + Jumlah_B_TS_3_4;

                                    $('#jumlahSum_3').html('<div style="text-align: right">'+formatRupiah(jumlahSum_3)+'</div>');
                                    $('#jumlahSum_2').html('<div style="text-align: right">'+formatRupiah(jumlahSum_2)+'</div>');
                                    $('#jumlahSum_1').html('<div style="text-align: right">'+formatRupiah(jumlahSum_1)+'</div>');

                                    var t = parseFloat(jumlahSum_1) + parseFloat(jumlahSum_2) + parseFloat(jumlahSum_3);

                                    $('#jumlahSum_1').parent().append('<td><div style="text-align: right;">'+formatRupiah(t)+'</div></td>');
                                }
                            }

                            // 5
                            if(i>=17 && i<=18){
                                Jumlah_B_TS_1_5 = Jumlah_B_TS_1_5 + parseFloat(vts);
                                Jumlah_B_TS_2_5 = Jumlah_B_TS_2_5 + parseFloat(vts1);
                                Jumlah_B_TS_3_5 = Jumlah_B_TS_3_5 + parseFloat(vts2);

                                if(i==18){
                                    $('#Jumlah_B_TS_3_5').html('<div style="text-align: right">'+formatRupiah(Jumlah_B_TS_3_5)+'</div>');
                                    $('#Jumlah_B_TS_2_5').html('<div style="text-align: right">'+formatRupiah(Jumlah_B_TS_2_5)+'</div>');
                                    $('#Jumlah_B_TS_1_5').html('<div style="text-align: right">'+formatRupiah(Jumlah_B_TS_1_5)+'</div>');

                                    var t = parseFloat(Jumlah_B_TS_1_5) + parseFloat(Jumlah_B_TS_2_5) + parseFloat(Jumlah_B_TS_3_5);

                                    $('#Jumlah_B_TS_1_5').parent().append('<td><div style="text-align: right;">'+formatRupiah(t)+'</div></td>');

                                    var jumlahSum_1_1 = Jumlah_B_TS_1 + Jumlah_B_TS_1_2 + Jumlah_B_TS_1_3 + Jumlah_B_TS_1_4 + Jumlah_B_TS_1_5;
                                    var jumlahSum_2_1 = Jumlah_B_TS_2 + Jumlah_B_TS_2_2 + Jumlah_B_TS_2_3 + Jumlah_B_TS_2_4 + Jumlah_B_TS_2_5;
                                    var jumlahSum_3_1 = Jumlah_B_TS_3 + Jumlah_B_TS_3_2 + Jumlah_B_TS_3_3 + Jumlah_B_TS_3_4 + Jumlah_B_TS_3_5;

                                    $('#jumlahSum_3_1').html('<div style="text-align: right">'+formatRupiah(jumlahSum_3_1)+'</div>');
                                    $('#jumlahSum_2_1').html('<div style="text-align: right">'+formatRupiah(jumlahSum_2_1)+'</div>');
                                    $('#jumlahSum_1_1').html('<div style="text-align: right">'+formatRupiah(jumlahSum_1_1)+'</div>');

                                    var t = parseFloat(jumlahSum_1_1) + parseFloat(jumlahSum_2_1) + parseFloat(jumlahSum_3_1);
                                    $('#jumlahSum_1_1').parent().append('<td><div style="text-align: right;">'+formatRupiah(t)+'</div></td>');
                                }
                            }



                        }
                    }
                }

                // $('#1_SPP_TS').html(d_TS.1_SPP);
                // $('#1_SumbanganLainnya_TS').html(d_TS.1_SPP);

            });


        }




    }

</script>