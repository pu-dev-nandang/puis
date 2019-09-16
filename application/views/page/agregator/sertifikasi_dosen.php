

<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }
</style>

<div class="well">


    <div class="row">
        <div class="col-md-12">
            <div style="text-align: right;margin-bottom: 20px;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
            <table class="table table-striped dataTable2Excel" id="tableData" data-name="setifikasi_dosen">
                <thead>
                <tr>
                    <th style="width: 1%;">No</th>
                    <th>Prodi</th>
                    <th style="width: 15%;">Jumlah Dosen</th>
                    <th style="width: 15%;">Jumlah Dosen Bersertifikat</th>
                </tr>
                </thead>
                <tbody id="listData"></tbody>
            </table>

            <p style="color: orangered;">*) Dosen yang terhitung adalah dosen mempunyai (NIDN atau NIDK)</p>
            <p style="color: orangered;">*) Dosen yang Bersertifikat adalah dosen yang sudah tersertifikasi dosen (SERDOS)</p>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadLecturerCertificate();
    });


    function loadLecturerCertificate() {


        var url = base_url_js+'api3/__getLecturerCertificate';
        $.getJSON(url,function (jsonResult) {

            $('#listData').empty();

            if(jsonResult.length>0){

                var ds = 0;
                var ds_c = 0;

                $.each(jsonResult,function (i,v) {
                    $('#listData').append('<tr>' +
                        '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.Name+'</td>' +
                        '<td style="border-left: 1px solid #ccc;">'+v.TotalLecturer+'</td>' +
                        '<td style="border-left: 1px solid #ccc;">'+v.TotalLecturerCertifies+'</td>' +
                        '</tr>');

                    ds = ds + parseInt(v.TotalLecturer);
                    ds_c = ds_c + parseInt(v.TotalLecturerCertifies);
                });

                $('#listData').append('<tr>' +
                    '<th colspan="2">Jumlah</th>' +
                    '<th>'+ds+'</th>' +
                    '<th>'+ds_c+'</th>' +
                    '</tr>')
            }

        });

    }
</script>