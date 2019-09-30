<h3>This is the page : prestasi_mahasiswa.php</h3><br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
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
            <p style="color: orangered;">*) Table prestasi mahasiswa mencakup laporan APS table 8.b1, 8.b2,</p>

        </div>

    </div>

</div>                    
                    
                    <script>
                        var oTable;
                        var oSettings;

                        
                        $('#saveToExcel').click(function () {

                            $('select[name="dataTablesPAM_length"]').val(-1);

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
                    $('#viewTable').html(' <table class="table dataTable2Excel" data-name="Prestasi-Akademik-Mahasiswa" id="dataTablesPAM">' +
                        '                <thead>' +
                        '                <tr>' +
                        '                    <th rowspan="2" style="width: 1%;">No</th>' +
                        '                    <th rowspan="2">Kegiatan</th>' +
                        '                    <th rowspan="2" style="width: 10%;">Kategori</th>' +
                        '                    <th rowspan="2" style="width: 15%;">Waktu</th>' +
                        '                    <th colspan="3" style="width: 25%;">Tingkat</th>' +
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

                    var P = filterProdi.split('.');
                    var ProdiID = P[0];
                    var data = {
                        action : 'viewDataPAM_APS',
                        ProdiID : ProdiID,
                        // Type : '0'
                    };

                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'api3/__crudAgregatorTB5';
                    
                    $.post(url,{token:token},function (jsonResult) {

                        if(jsonResult.length>0){
                            $.each(jsonResult,function (i,v) {


                                var Provinsi = (v.Level=='Provinsi') ? 'v' : '';
                                var Nasional = (v.Level=='Nasional') ? 'v' : '';
                                var Internasional = (v.Level=='Internasional') ? 'v' : '';
                                var lbl = (v.Type=='1' || v.Type==1)
                                    ? '<span class="label label-success">Academic</span>'
                                    : '<span class="label label-default">Non Academic</span>';

                                $('#listData').append('<tr>' +
                                    '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                                    '<td style="text-align: left;">'+v.Event+'</td>' +
                                    '<td style="text-align: center;">'+lbl+'</td>' +
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
                }
            </script>