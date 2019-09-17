

<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }
</style>

<div class="well">

    <div class="row">
        <div class="col-md-2 col-md-offset-5">
            <select class="form-control" id="filterSemester"></select>
        </div>
        <div class="col-md-5">
            <div style="text-align: right;margin-bottom: 20px;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">


            <table class="table dataTable2Excel" data-name="Rasio_Dosen_terhadap_Mahasiswa" id="tableData">
                <thead>
                <tr>
                    <th colspan="5">2019/2020 Ganjil</th>
                </tr>
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

            <p style="color: orangered;">
                <br/>*) Mahasiswa yang terhitung adalah mahasiswa yang aktif (Tidak termasuk mahasiswa cuti / mangkir)
                <br/>*) Dosen yang terhitung adalah dosen yang aktif (Sesuai status Lecturer HRD)
            </p>
        </div>
    </div>
</div>

<script>
    var passToExcel = [];
    $(document).ready(function () {
        loSelectOptionSemester('#filterSemester','');

        var firstLoad = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            if(filterSemester!='' && filterSemester!=null){
                loadLecturerCertificate();
                clearInterval(firstLoad);
            }
        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },1000);


    });

    $('#filterSemester').change(function () {
        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){
            loadLecturerCertificate();
        }

    });

    function loadLecturerCertificate() {

        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){

            // console.log(filterSemester);
            var SemesterID = filterSemester.split('.')[0];

            passToExcel = [];

            var url = base_url_js+'api3/__getRasioDosenMahasiswa?smt='+SemesterID;
            $.getJSON(url,function (jsonResult) {

                $('#listData').empty();

                if(jsonResult.length>0){

                    var ds = 0;
                    var ds_c = 0;
                    var ds_x = 0;

                    $.each(jsonResult,function (i,v) {
                        $('#listData').append('<tr>' +
                            '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                            '<td style="text-align: left;">'+v.Name+'</td>' +
                            '<td style="border-left: 1px solid #ccc;">'+v.TotalLecturer+'</td>' +
                            '<td style="border-left: 1px solid #ccc;">'+v.TotalMahasiwa+'</td>' +
                            '<td style="border-left: 1px solid #ccc;">'+v.TotalMahasiwaTA+'</td>' +
                            '</tr>');

                        ds = ds + parseInt(v.TotalLecturer);
                        ds_c = ds_c + parseInt(v.TotalMahasiwa);
                        ds_x = ds_x + parseInt(v.TotalMahasiwaTA);
                    });

                    $('#listData').append('<tr>' +
                        '<th colspan="2">Jumlah</th>' +
                        '<th>'+ds+'</th>' +
                        '<th>'+ds_c+'</th>' +
                        '<th>'+ds_x+'</th>' +
                        '</tr>');


                    passToExcel = jsonResult;
                }

            });

        }

    }

</script>