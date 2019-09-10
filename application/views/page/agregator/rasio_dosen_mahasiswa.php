

<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }
</style>

<div class="well">


    <div class="row">
        <div class="col-md-12">

            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="form-group">
                        <label>Filter Status</label>
                        <select class="form-control" id="filterStatusForlap">
                            <option value="1" disabled="" selected="">Aktif</option>
                            <option value="0" disabled="">Non Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div style="text-align: right"> <b>Download File : </b><button class="btn btn-success btn-circle" id="btndownloaadExcel" title="Dowload Excel"><i class="fa fa-file-excel-o"></i> </button></div>
            <table class="table" id="tableData">
                <thead>
                <tr>
                    <th style="width: 1%;">No</th>
                    <th>Prodi</th>
                    <th style="width: 15%;">Jumlah Dosen</th>
                    <th style="width: 15%;">Jumlah Mahasiswa</th>
                    <th style="width: 15%;">Jumlah Mahasiswa TA</th>
                </tr>
                </thead>
                <tbody id="listData"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var passToExcel = [];
    $(document).ready(function () {
        loadLecturerCertificate();
    });

    $('#filterStatusForlap').change(function () {
        loadLecturerCertificate();
    });

    function loadLecturerCertificate() {
        passToExcel = [];
        var filterStatusForlap = $('#filterStatusForlap').val();
        var status = (filterStatusForlap!='' && filterStatusForlap!=null)
        ? filterStatusForlap : 'all';

        var url = base_url_js+'api3/__getRasioDosenMahasiswa?s='+status;
        $.getJSON(url,function (jsonResult) {

            $('#listData').empty();

            if(jsonResult.length>0){

                var ds = 0;
                var ds_c = 0;
                var x = 0;

                $.each(jsonResult,function (i,v) {
                    $('#listData').append('<tr>' +
                        '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.Name+'</td>' +
                        '<td style="border-left: 1px solid #ccc;">'+v.TotalLecturer+'</td>' +
                        '<td style="border-left: 1px solid #ccc;">'+v.TotalMahasiwa+'</td>' +
                        '<td style="border-left: 1px solid #ccc;">0</td>' +
                        '</tr>');

                    ds = ds + parseInt(v.TotalLecturer);
                    ds_c = ds_c + parseInt(v.TotalMahasiwa);
                    //ds_x = ds_x + parseInt(x);
                });

                $('#listData').append('<tr>' +
                    '<th colspan="2">Jumlah</th>' +
                    '<th>'+ds+'</th>' +
                    '<th>'+ds_c+'</th>' +
                    //'<th>'+ds_x+'</th>' +
                    '</tr>')


                passToExcel = jsonResult;
            }

        });

    }

    $(document).off('click', '#btndownloaadExcel').on('click', '#btndownloaadExcel',function(e) {
        if (passToExcel.length > 0) {
            var url = base_url_js+'agregator/excel-rasio-dosen-mahasiswa';
            data = {
              passToExcel : passToExcel,
            }
            var token = jwt_encode(data,"UAP)(*");
            FormSubmitAuto(url, 'POST', [
                { name: 'token', value: token },
            ]);
        }

    })
</script>