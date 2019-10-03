<h3 align="center"><b>Ekuivalen Waktu Mengajar Penuh (EWMP) Dosen Tetap Perguruan Tinggi</b></h3><br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<style>
    #dataTablesEkuivalen tr th, #dataTablesEkuivalen tr td {
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
            <p style="color: orangered;">*) Table  Ekuivalen Waktu Mengajar Penuh (EWMP) Dosen Tetap Perguruan Tinggi mencakup laporan APS table 3.a.3</p>

        </div>

    </div>

</div>                    
                    
                    <script>
                        var oTable;
                        var oSettings;

                        
                        $('#saveToExcel').click(function () {

                            $('select[name="dataTablesEkuivalen_length"]').val(-1);

                            oSettings[0]._iDisplayLength = oSettings[0].fnRecordsTotal();
                            oTable.draw();

                            setTimeout(function () {
                                saveTable2Excel('dataTable2Excel');
                            },1000);
                        });

                $(document).ready(function () {
                    var firstLoad = setInterval(function () {
                        var filterProdi = $('#filterProdi').val();
                        if(filterProdi!='' && filterProdi!=null){
                            loadPage();
                            clearInterval(firstLoad);
                        }
                    },1000);
                    setTimeout(function () {
                        clearInterval(firstLoad);
                    },5000);
            
                });
                $('#filterProdi').change(function () {
                    var filterProdi = $('#filterProdi').val();
                    if(filterProdi!='' && filterProdi!=null){
                        loadPage();
                    }
                });
                function loadPage() {
                    var filterProdi = $('#filterProdi').val();
                    if(filterProdi!='' && filterProdi!=null){
                        $('#viewProdiID').html(filterProdi);
                        $('#viewProdiName').html($('#filterProdi option:selected').text());
                    $('#viewTable').html(' <table class="table dataTable2Excel" data-name="Ekuivalen-Mengajar-Dosen-Tetap" id="dataTablesEkuivalen">' +
                        '                <thead>' +
                        '                <tr>' +
                        '                    <th rowspan="3" style="width: 1%;">No</th>' +
                        '                    <th rowspan="3" style="width: 10%;">Nama Dosen</th>' +
                        '                    <th rowspan="3" style="width: 5%;">DTPS</th>' +
                        '                    <th colspan="6" style="width: 50%;">Ekuivalen Waktu Mengajar Penuh (EWMP) pada saat TS dalam satuan kredit semester (sks)</th>' +
                        '                    <th rowspan="3" style="width: 10%;">Jumlah</th>' +
                        '                    <th rowspan="3" style="width: 10%;">Rata-Rata Per Semester (SKS)</th>' +
                        '                </tr>' +
                        '                <tr>' +
                        '                   <th colspan="3">Pendidikan: Pembelajaran dan Pembimbingan</th>' +
                        '                   <th rowspan="2">Penelitian</th>' +
                        '                   <th rowspan="2">PkM</th>' +
                        '                   <th rowspan="2">Tugas Tambahan dan/atau Penunjang</th>' +
                        '               </tr>' +
                        '               <tr>' +
                        '                   <th>PS yang Diakreditasi</th>' +
                        '                   <th>PS Lain di dalam PT</th>' +
                        '                   <th>PS Lain di luar PT</th>' +
                        '               </tr>' +
                        '                </thead>' +
                        '                <tbody id="listData"></tbody>' +
                        '            </table>');

                    

                         oTable = $('#dataTablesPAM').DataTable();
                        oSettings = oTable.settings();


               
                    }
                }
            </script>