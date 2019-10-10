<h3 align="center"><b>Kurikulum, Capaian Pembelajaran, dan Rencana Pembelajaran</b></h3><br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
 <style>
     #dataTablesKurikulum tr th, #dataTablesKurikulum tr td {
         text-align: center;
     }
 </style>
 <div class="well">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <select class="form-control" id="selectKurikulum"></select>
        </div>
    </div>
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
    var AppJQ = {
        BuatTable : function(){
                        var selector = $('#viewTable');
                        var htmltable = ' <table class="table dataTable2Excel" data-name="kurikulum-capaian" id="dataTablesKurikulum">' +
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
                                        '            </table>';
                            selector.html(htmltable);            
                        
        },
        loadPage : function(){
                       var filterProdi = $('#filterProdi option:selected').val();
                       var filterProdiName = $('#filterProdi option:selected').text();
                       if(filterProdi!='' && filterProdi!=null){
                           $('#viewProdiID').html(filterProdi);
                           $('#viewProdiName').html($('#filterProdi option:selected').text());
                           var data = {
                               ProdiID : filterProdi,
                               ProdiName : filterProdiName,
                               auth : 's3Cr3T-G4N',
                               mode : 'KurikulumCapaianRencana',
                           };

                            var token = jwt_encode(data,'UAP)(*');
                            var recordTable = $('#dataTablesKurikulum').DataTable({
                                "processing": true,
                                "serverSide": false,
                                "ajax":{
                                    url : base_url_js+"rest3/__get_APS_CrudAgregatorTB7", // json datasource
                                    ordering : false,
                                    type: "post",  // method  , by default get
                                    data : {token : token}                                    
                                        
                                },
                                'createdRow': function( row, data, dataIndex ) {
                                        
                                },
                                dom: 'l<"toolbar">frtip',
                                initComplete: function(){
                                  
                               }  
                            });

                            oTable = recordTable;
                            oSettings = oTable.settings();
                       } 
        },
        loaded : function(){
                    AppJQ.BuatTable();
                    loadSelectOptionCurriculum('#selectKurikulum','');
                    var firstLoad = setInterval(function () {
                        var filterProdi = $('#filterProdi').val();
                        var Kurikulum = $('#selectKurikulum').val();
                        if(filterProdi!='' && filterProdi!=null && Kurikulum != '' && Kurikulum != null){
                            AppJQ.loadPage();
                            clearInterval(firstLoad);
                        }
                    },1000);
                    setTimeout(function () {
                        clearInterval(firstLoad);
                    },5000);
        },
    };
    
    $(document).ready(function () {
        AppJQ.loaded();
    });
    $('#filterProdi').change(function () {
        var filterProdi = $('#filterProdi').val();
        if(filterProdi!='' && filterProdi!=null){
            AppJQ.loadPage();
        }
    });

    // $('#saveToExcel').click(function () {
    //                    $('select[name="dataTablesKurikulum_length"]').val(-1);
    //                    oSettings[0]._iDisplayLength = oSettings[0].fnRecordsTotal();
    //                    oTable.draw();
    //                    setTimeout(function () {
    //                        saveTable2Excel('dataTable2Excel');
    //                    },1000);
    // });
</script>