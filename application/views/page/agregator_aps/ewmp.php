<h3 align="center"><b>Ekuivalen Waktu Mengajar Penuh (EWMP) Dosen Tetap Perguruan Tinggi</b></h3><br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<style>
    #dataTablesEkuivalen tr th, #dataTablesEkuivalen tr td {
        text-align: center;
    }
</style>

<div class="well">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="form-group">
                <label for="">Tahun</label>
                <select name="" id="FilterTahun" class="form-control">
                </select>
            </div>
        </div>
    </div>
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
    $(document).ready(function () {
        loadSelectYear('#FilterTahun');
        var firstLoad = setInterval(function () {
            var filterProdi = $('#filterProdi').val();
            var FilterTahun = $('#FilterTahun').val();
            if(filterProdi!='' && filterProdi!=null && FilterTahun!='' && FilterTahun!=null){
                loadPage();
                clearInterval(firstLoad);
            }
        },1000);
        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);

    });

    function loadSelectYear(element) {
        var Year = <?php echo date('Y')  ?>;
        var StartYear = 2014;
        for (let i = StartYear; i <= Year; i++) {
            $(element).append('<option value="'+i+'">'+i+'</option>');
        }
    }

    $('#filterProdi').change(function () {
        var filterProdi = $('#filterProdi').val();
        if(filterProdi!='' && filterProdi!=null){
            loadPage();
        }
    });

    $(document).off('change', '#FilterTahun').on('change', '#FilterTahun',function(e) {
        loadPage();
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

            var ProdiName = $('#filterProdi option:selected').text();
            var FilterTahun = $('#FilterTahun').val();
            var P = filterProdi.split('.');
            var ProdiID = P[0];
            var data = {
                auth : 's3Cr3T-G4N',
                mode : 'EWMP',
                ProdiID : ProdiID,
                FilterTahun : FilterTahun,
            };
            var token = jwt_encode(data,"UAP)(*");
            var url = base_url_js+"rest3/__get_APS_CrudAgregatorTB3";
            $.post(url,{token:token},function (jsonResult) {
                var selector = $('#listData');
                selector.empty();
                for (let i = 0; i < jsonResult.length; i++) {
                    var arr = jsonResult[i];
                    var htmlBody = '<tr>';
                    for (let k = 0; k < arr.length; k++) {
                        if (k == (arr.length - 1) ) {
                            htmlBody += '<td>'+getCustomtoFixed(arr[k],1)+'</td>';
                        }
                        else
                        {
                            if (k==3 || k == 4) { // PS yang Diakreditasi dan PS Lain di dalam PT
                                htmlBody += '<td>'+'<a href = "javascript:void(0);" class = "datadetail" data = "'+arr[k].data+'">'+arr[k].count+'</a>'+'</td>';
                            }
                            else if (k==6){
                                htmlBody += '<td>'+'<a href = "javascript:void(0);" class = "datadetailPenelitian" data = "'+arr[k].data+'">'+arr[k].count+'</a>'+'</td>';
                            }
                            else if (k==7){
                                htmlBody += '<td>'+'<a href = "javascript:void(0);" class = "datadetailPKM" data = "'+arr[k].data+'">'+arr[k].count+'</a>'+'</td>';
                            }
                            else if (k==8){
                                htmlBody += '<td>'+'<a href = "javascript:void(0);" class = "datadetailTgsTambahan" data = "'+arr[k].data+'">'+arr[k].count+'</a>'+'</td>';
                            }
                            else{
                                htmlBody += '<td>'+arr[k]+'</td>';
                            }
                            
                        }
                    }
                    htmlBody+= '</tr>';

                    selector.append(htmlBody);
                }
            });


        }
    }

    $(document).off('click', '.datadetail').on('click', '.datadetail',function(e) {
        var v = parseInt($(this).html());
        if (v > 0) {
            var dt = $(this).attr('data');
            // console.log(dt);
            dt = jwt_decode(dt);
            // console.log(dt);
            var html =  '<div class = "row">'+
                            '<div class = "col-md-12">'+
                                '<table class = "table">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<td>No</td>'+
                                            '<td>Mata Kuliah</td>'+
                                            '<td>SKS</td>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody>';
                    for (var i = 0; i < dt.length; i++) {
                        html += '<tr>'+
                                    '<td>'+ (parseInt(i)+1) + '</td>'+
                                    '<td>'+ dt[i].ClassGroup+' - '+ dt[i].NameEng+ '</td>'+
                                    '<td>'+ dt[i].Credit + '</td>'+
                                '</tr>';    
                    }

                    html  += '</tbody></table></div></div>';                


            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Detail</h4>');
            $('#GlobalModal .modal-body').html(html);
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        }
    })

    $(document).off('click', '.datadetailPenelitian').on('click', '.datadetailPenelitian',function(e) {
        var v = parseInt($(this).html());
        if (v > 0) {
            var dt = $(this).attr('data');
            // console.log(dt);
            dt = jwt_decode(dt);
            // console.log(dt);
            var html =  '<div class = "row">'+
                            '<div class = "col-md-12">'+
                                '<table class = "table">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<td>No</td>'+
                                            '<td>Judul</td>'+
                                            '<td>SKS</td>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody>';
                    for (var i = 0; i < dt.length; i++) {
                        html += '<tr>'+
                                    '<td>'+ (parseInt(i)+1) + '</td>'+
                                    '<td>'+ dt[i].Judul_litabmas+ '</td>'+
                                    '<td>'+ dt[i].Credit + '</td>'+
                                '</tr>';    
                    }

                    html  += '</tbody></table></div></div>';                


            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Detail</h4>');
            $('#GlobalModal .modal-body').html(html);
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        }
    }) 

    $(document).off('click', '.datadetailPKM').on('click', '.datadetailPKM',function(e) {
        var v = parseInt($(this).html());
        if (v > 0) {
            var dt = $(this).attr('data');
            // console.log(dt);
            dt = jwt_decode(dt);
            // console.log(dt);
            var html =  '<div class = "row">'+
                            '<div class = "col-md-12">'+
                                '<table class = "table">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<td>No</td>'+
                                            '<td>Judul</td>'+
                                            '<td>SKS</td>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody>';
                    for (var i = 0; i < dt.length; i++) {
                        html += '<tr>'+
                                    '<td>'+ (parseInt(i)+1) + '</td>'+
                                    '<td>'+ dt[i].Judul_PKM+ '</td>'+
                                    '<td>'+ dt[i].Credit + '</td>'+
                                '</tr>';    
                    }

                    html  += '</tbody></table></div></div>';                


            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Detail</h4>');
            $('#GlobalModal .modal-body').html(html);
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        }
    }) 

    $(document).off('click', '.datadetailTgsTambahan').on('click', '.datadetailTgsTambahan',function(e) {
        var v = parseInt($(this).html());
        if (v > 0) {
            var dt = $(this).attr('data');
            // console.log(dt);
            dt = jwt_decode(dt);
            // console.log(dt);
            var html =  '<div class = "row">'+
                            '<div class = "col-md-12">'+
                                '<table class = "table">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<td>No</td>'+
                                            '<td>Name</td>'+
                                            '<td>Position</td>'+
                                            '<td>SKS</td>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody>';
                    for (var i = 0; i < dt.length; i++) {
                        html += '<tr>'+
                                    '<td>'+ (parseInt(i)+1) + '</td>'+
                                    '<td>'+ dt[i].NameEmployee+ '</td>'+
                                    '<td>'+ dt[i].Position + '</td>'+
                                    '<td>'+ dt[i].SKS + '</td>'+
                                '</tr>';    
                    }

                    html  += '</tbody></table></div></div>';                


            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Detail</h4>');
            $('#GlobalModal .modal-body').html(html);
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        }
    }) 
</script>