

<style>
    #dataTable tr th, #dataTable tr td {
        text-align: center;
    }
</style>

<div class="well">
    <div class="row">
        <div class="col-md-12">
            <div style="text-align: right;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
            <table class="table dataTable2Excel" id="dataTable" data-name="Jabatan_dosen_tetap">
                <thead>
                <tr>
                    <th style="width: 1%;" rowspan="2">No</th>
                    <th rowspan="2">Pendidikan</th>
                    <th colspan="4">Jabatan Akademik</th>
                    <th style="width: 10%;" rowspan="2">Tenaga Pengajar</th>
                    <th style="width: 10%;" rowspan="2">Jumlah</th>
                </tr>
                <tr>
                    <th style="width: 10%;">Asisten Ahli</th>
                    <th style="width: 10%;">Lektor</th>
                    <th style="width: 10%;">Lektor Kepala</th>
                    <th style="width: 10%;">Guru Besar</th>
                </tr>
                </thead>
                <tbody id="listData"></tbody>
            </table>

        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        loadData();
    });

    function loadData() {
        var url = base_url_js+'api3/__getJabatanAkademikDosenTetap';
        $.getJSON(url,function (jsonResult) {

            $('#listData').empty();
            if(jsonResult.length>0){

                $.each(jsonResult,function (i,v) {

                    var td = '';
                    var total = 0;
                    $.each(v.details,function (i2, v2) {
                       td = td+'<td>'+v2.dataEmployees.length+'</td>';
                        total = total + parseInt(v2.dataEmployees.length);
                    });

                    $('#listData').append('<tr>' +
                        '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.Level+' - '+v.Description+'</td> '+td+

                        '<td style="text-align: center;background: lightyellow;border-left: 1px solid #ccc;">'+total+'</td>' +
                        '</tr>');

                });

            }

        });
    }
</script>