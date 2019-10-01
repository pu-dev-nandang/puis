<h3 align="center"><b>Kurikulum, Capaian Pembelajaran, dan Rencana Pembelajaran</b></h3><br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
 <style>
     #dataTablesKurikulum tr th, #dataTablesKurikulum tr td {
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
             <p style="color: orangered;">*) Table prestasi mahasiswa mencakup laporan APS table 5a</p>

         </div>

     </div>

 </div>                        
                    
            <script>
                var oTable;
                var oSettings;

                
                $('#saveToExcel').click(function () {

                    $('select[name="dataTablesKurikulum_length"]').val(-1);

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
                    $('#viewTable').html(' <table class="table dataTable2Excel" data-name="kurikulum-capaian" id="dataTablesKurikulum">' +
                        '                <thead>' +
                        '                <tr>' +
                        '                    <th rowspan="2" style="width: 1%;">No</th>' +
                        '                    <th rowspan="2">Semester</th>' +
                        '                    <th rowspan="2" style="width: 10%;">Kode Matakuliah</th>' +
                        '                    <th rowspan="2" style="width: 10%;">Nama Matakuliah</th>' +
                        '                    <th rowspan="2" style="width: 10%;">Matakuliah Kompetensi</th>' +
                        '                    <th colspan="3" style="width: 25%;">Bobot Kredit</th>' +
                        '                    <th rowspan="2" style="width: 20%;">Konversi Kredit ke Jam</th>' +
                        '                    <th colspan="4" style="width: 40%;">Capaian Pembelajaran</th>' +
                        '                    <th rowspan="2" style="width: 10%;">Dokumen Rencana Pembelajaran</th>' +
                        '                    <th rowspan="2" style="width: 10%;">Unit Penyelenggara</th>' +
                        '                </tr>' +
                        '                <tr>' +
                        '                   <th>Kuliah/ Responsi/ Tutorial</th>' +
                        '                   <th>Seminar</th>' +
                        '                   <th>Pratikum/ Praktik/ Praktik Lapangan</th>' +
                        '                   <th>Sikap</th>' +
                        '                   <th>Penegetahuan</th>' +
                        '                   <th>Keterampilan Umum</th>' +
                        '                   <th>Keterampilan Khusus</th>' +
                        '               </tr>' +
                        '                </thead>' +
                        '                <tbody id="listData"></tbody>' +
                        '            </table>');
                    }
                }
            </script>