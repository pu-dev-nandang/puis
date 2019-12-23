
<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }

    .td-av {
        background: #ffe7c4;
        font-weight: bold;
    }
    .higligh {
        background: lightyellow;
    }

</style>

<div class="well">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-3 col-md-offset-4">
                    <select class="form-control" id="filterTahun"><option id="0" selected> Semua Tahun</option></select>
                </div>
            <div style="text-align: right;margin-bottom: 20px;">
                <!-- <button class="btn btn-primary form-data-add" id="btnLembagaMitra"><i class="fa fa-plus"></i> Sitasi Karya Ilmiah </button>  -->
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
            <div class="">
                <table class="table table-bordered table-striped dataTable2Excel" id="tableSitasiKarya"  data-name="tableSitasiKarya">
                    <thead>
                    <tr style="background: #20485A;color: #FFFFFF;">
                        <th style="vertical-align : middle; text-align: center; width: 5%;">No</th>
                        <th style="vertical-align : middle; text-align: center; width: 13%;">Nama Penulis</th>
                        <th style="vertical-align : middle; text-align: center;">Judul Artikel yang Disitasi (Jurnal, Volume, Tahun, Nomor, Halaman)  </th> 
                        <th style="text-align: center; width: 8%;">Banyaknya Artikel yang Mensitasi</th>
                        <th style="vertical-align : middle; text-align: center; width: 10%;">Tahun (YYYY)</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
             
            <!-- <div id="viewTable"></div> -->
        </div>
    </div>
</div>

 <div class="alert alert-info">
                <strong>Keterangan :</strong><br/> Jika Judul Artiket Sitasi Ilmiah berwarna biru, data sitasi ilmiah tersebut sudah terdaftar dan diambil dari data SINTA.
              </div>

<script>

    var oTableGet;
    var oSettingsGet;

    $(document).ready(function () {
        loadSelectOptionClassOf_DSC('#filterTahun');
        loadData_sitasi(status);
        
    });

    $('#filterTahun').change(function () {
        var status = $('#filterTahun option:selected').attr('id');
        loadData_sitasi(status);
    });


    function loadData_sitasi(status) {
        var status = $('#filterTahun option:selected').attr('id');

        var dataTable = $('#tableSitasiKarya').DataTable({
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"api3/__getSitasiKarya?s="+status, // json datasource
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        });

        oTableGet = dataTable;
        oSettingsGet = oTableGet.settings();
    }

    $('#saveToExcel').click(function () {

       $('select[name="dataTablesKurikulum_length"]').val(-1);
       oSettingsGet[0]._iDisplayLength = oSettingsGet[0].fnRecordsTotal();
       oTableGet.draw();
       setTimeout(function () {
           saveTable2Excel('dataTable2Excel');
       },1000);
    });
</script>


<script>
    //$(document).ready(function () {

    //    window.act = "<?= $accessUser; ?>";
    //    if(parseInt(act)<=0){
    //        $('.form-data-add').remove();
    //    } 
    //    else {
    //    }

    //    loadAkreditasiProdi();
    //});

    function loadSelectOptionClassOf_DSC() {
        var url = base_url_js+'api/__getKurikulumSelectOptionDSC';
        $.getJSON(url,function (jsonResult) {
            for(var i=0;i<jsonResult.length;i++){
               $('#filterTahun').append('<option id="'+jsonResult[i].Year+'">'+jsonResult[i].Year+' </option>');
            }
        });
    }

    function loadAkreditasiProdi() {

         $('#viewTable').html(' <table class="table table-bordered dataTable2Excel" id="dataTablesLuaran" data-name="sitasi_karya_ilmiah">' +
            '    <thead>  '+
            '     <tr style="background: #20485A;color: #FFFFFF;">   '+
            '        <th style="text-align: center; width: 5%;">No</th>  '+
            '        <th style="text-align: center;"> Nama Penulis</th>  '+
            '        <th style="text-align: center;"> Judul Artikel yang Disitasi (Jurnal, Volume, Tahun, Nomor, Halaman)  </th>'+
            '        <th style="text-align: center; width: 12%;">Banyaknya Artikel yang Mensitasi</th>  '+
            '        <th style="text-align: center; width: 8%;">Tahun (YYYY)</th>  '+
            '    </tr>  '+
            '    </thead>  '+
            '       <tbody id="listData"></tbody>   '+
            //'    <tfoot id="listDataFoot">  </tfoot>'+
            '    </tfoot> '+
            '    </table>');

        var url = base_url_js+'api3/__getSitasiKarya';
        $.getJSON(url,function (jsonResult) {

            if(jsonResult.length>0) {
                  var sumx = 0;

                for (var i = 0; i < jsonResult.length; i++) {
                    var v = jsonResult[i];

                    $('#listData').append('<tr>' +
                        '   <td style="text-align: center;">'+(i+1)+'</td>' +
                        '   <td style="text-align: left;">'+v.Name+'</td>' +
                        '   <td style="text-align: left;">'+v.Title+'</td>' +
                        '   <td style="text-align: center;">'+v.Citation+'</td>' +
                        '   <td style="text-align: center;">'+v.Year+'</td>' +
                        '</tr>');
                    var total = parseInt(jsonResult.length);
                    var sumx = sumx + parseInt(v.Citation);
                    //sum += v.Banyak_artikel;
                }
            }

            $('#dataTablesLuaran').dataTable();

            $('#listData').append('<tr>' +
                    '<th colspan="3" style="text-align: center;">Jumlah</th>' +
                    '<th style="text-align: center;">'+sumx+'</th>' +
                    '</tr>')
            //console.log(sum);
        });

    }

    function hitungRow(cl) {

        var res = 0;
        $(cl).each(function () {
            res += parseInt($(this).attr('data-val'));
        });

        return res;
    }

</script>
