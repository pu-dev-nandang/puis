

<style>
    #dataTable tr th, #dataTable tr td {
        text-align: center;
    }
</style>

<div class="well">
    <div class="row">
        <div class="col-md-12">

            <table class="table table-bordered" id="dataTable">
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
                    $.each(v.details,function (i2, v2) {
                       td = td+'<td>'+v2.dataEmployees.length+'</td>';
                    });

                    $('#listData').append('<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.Level+' - '+v.Description+'</td> '+td+
                        '<td></td>' +
                        '<td></td>' +
                        '</tr>');

                });

            }

        });
    }
</script>