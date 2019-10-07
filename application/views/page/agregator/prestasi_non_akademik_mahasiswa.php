
<style>
    #dataTablesPAM tr th, #dataTablesPAM tr td {
        text-align: center;
    }
</style>

<div class="well">

    <div class="row">

        <div class="col-md-12">
            <div style="text-align: right;margin-bottom: 30px;">
                <button id="saveToExcel" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
            <div id="viewTable"></div>


        </div>
        <p style="color: orangered;">
            *) Non-Akademik : minimal 0.10 % dari total mahasiswa aktif. (Nilai terbesar adalah tingkat internasional)
            *) Internasional + Nssiaonal + Provinsi/Wilayah :3
        </p>
    </div>

</div>

<script>

    var oTable;
    var oSettings;

    $(document).ready(function () {

        loadDataPAM();

    });

    function loadDataPAM() {

        $('#viewTable').html(' <table class="table dataTable2Excel" data-name="Prestasi-Non-Akademik-mahasiswa" id="dataTablesPAM">' +
            '                <thead>' +
            '                <tr>' +
            '                    <th rowspan="2" style="width: 1%;">No</th>' +
            '                    <th rowspan="2">Kegiatan</th>' +
            '                    <th rowspan="2" style="width: 15%;">Waktu</th>' +
            '                    <th colspan="3" style="width: 15%;">Tingkat</th>' +
            '                    <th rowspan="2" style="width: 20%;">Prestasi</th>' +
            '                </tr>' +
            '                <tr>' +
            '                   <th>Provinsi / Wilayah</th>' +
            '                   <th>Nasional</th>' +
            '                   <th>Internasional</th>' +
            '               </tr>' +
            '                </thead>' +
            '                <tbody id="listData"></tbody>' +
            '            </table>');


        var data = {
            action : 'viewPAM',
            Type : '0'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB5';

        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {


                    var Provinsi = (v.Level=='Provinsi') ? 'v' : '';
                    var Nasional = (v.Level=='Nasional') ? 'v' : '';
                    var Internasional = (v.Level=='Internasional') ? 'v' : '';

                    $('#listData').append('<tr>' +
                        '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.Event+'</td>' +
                        '<td>'+moment(v.StartDate).format('DD-MM-YYYY')+'</td>' +
                        '<td>'+Provinsi+'</td>' +
                        '<td>'+Nasional+'</td>' +
                        '<td>'+Internasional+'</td>' +
                        '<td>'+v.Achievement+'</td>' +
                        '</tr>');

                });
            }

            oTable = $('#dataTablesPAM').DataTable();
            oSettings = oTable.settings();


        });
    }



    $('#saveToExcel').click(function () {

        $('select[name="dataTablesPAM_length"]').val(-1);

        oSettings[0]._iDisplayLength = oSettings[0].fnRecordsTotal();
        oTable.draw();

        setTimeout(function () {
            saveTable2Excel('dataTable2Excel');
        },1000);
    });
</script>
