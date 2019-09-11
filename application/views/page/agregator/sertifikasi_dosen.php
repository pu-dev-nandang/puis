

<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }
</style>

<div class="well">


    <div class="row">
        <div class="col-md-12">

            <div style="text-align: right;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>

            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="form-group">
                        <label>Filter Status Forlap</label>
                        <select class="form-control" id="filterStatusForlap">
                            <option value="all">Semua</option>
                            <option disabled>-----</option>
                            <option value="1">Permanent</option>
                            <option value="0">Contract</option>
                        </select>
                    </div>
                </div>
            </div>


            <table class="table table-bordered table-striped dataTable2Excel" id="tableData" data-name="setifikasi_dosen">
                <thead>
                <tr  style="background: #20485A;color: #FFFFFF;">
                    <th style="width: 1%;">No</th>
                    <th>Unit Pengelola (Fakultas/Departemen/Jurusan)</th>
                    <th style="width: 15%;">Jumlah Dosen</th>
                    <th style="width: 15%;">Jumlah Dosen Bersertifikat **)</th>
                </tr>
                </thead>
                <tbody id="listData"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadLecturerCertificate();
    });

    $('#filterStatusForlap').change(function () {
        loadLecturerCertificate();
    });

    function loadLecturerCertificate() {

        var filterStatusForlap = $('#filterStatusForlap').val();
        var status = (filterStatusForlap!='' && filterStatusForlap!=null)
        ? filterStatusForlap : 'all';

        var url = base_url_js+'api3/__getLecturerCertificate?s='+status;
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