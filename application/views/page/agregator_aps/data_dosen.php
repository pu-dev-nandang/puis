<style type="text/css">
    #dataTablesDataDosen thead th {
        vertical-align: middle;
    }
</style>
<h3>This is the page : data_dosen.php</h3><br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<div class="well">
    <div class="row">
        <div class="col-md-12">
            <div style="text-align: right;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
            <br/>
            <div id="viewTable"></div>
        </div>
    </div>
</div>                       
                    
<script>
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
        LoadTableData(filterProdi);
    }
}

function LoadTableData(filterProdi)
{
    var P = filterProdi.split('.');
    var ProdiID = P[0];
    var data = {
        auth : 's3Cr3T-G4N',
        mode : 'DataDosen',
        ProdiID : ProdiID,
    };
    var token = jwt_encode(data,"UAP)(*");
    var url = base_url_js+"rest3/__get_APS_CrudAgregatorTB3";
    $.post(url,{token:token},function (jsonResult) {
        var selector = $('#viewTable');
        var html = '<div class="table-responsive">';

        // get data pertama untuk ts
        var JMLDibimbingBy_PS = jsonResult[0]['JMLDibimbingBy_PS'];
        var thYear = '';
        for (var i = 0; i < JMLDibimbingBy_PS.length; i++) {
            thYear += '<th>'+JMLDibimbingBy_PS[i].Year+'</th>';
        }

        html += '<table class="table table-bordered dataTable2Excel" id ="dataTablesDataDosen" style = "min-width: 1200px;"  data-name="DataDosen">'+
                        '<thead>'+
                            '<tr>'+
                                '<th rowspan = "3">No</th>'+
                                '<th rowspan = "3">Nama Dosen</th>'+
                                '<th rowspan = "3">NIDN</th>'+
                                '<th rowspan = "3">NIDK</th>'+
                                '<th rowspan = "3">Pendidikan Pasca Sarjana</th>'+
                                '<th rowspan = "3">Perusahaan/ Industri</th>'+
                                '<th rowspan = "3">Pendidikan Tertinggi</th>'+
                                '<th rowspan = "3">Bidang Keahlian</th>'+
                                '<th rowspan = "3">Kesesuaian dengan Kompetensi Inti PS</th>'+
                                '<th rowspan = "3">Jabatan Akademik</th>'+
                                '<th rowspan = "3">Sertifikat Pendidik Profesional</th>'+
                                '<th rowspan = "3">Sertifikat  Kompetensi/ Profesi/  Industri</th>'+
                                '<th rowspan = "3">Mata Kuliah yang Diampu pada PS yang Diakreditasi</th>'+
                                '<th rowspan = "3">Kesesuaian Bidang Keahlian dengan Mata Kuliah yang Diampu</th>'+
                                '<th rowspan = "3">Mata Kuliah yang Diampu pada PS Lain</th>'+
                                '<th rowspan = "3">Bobot Kredit (sks)</th>'+
                                '<th colspan = "6">Jumlah Mahasiswa yang Dibimbing</th>'+
                                '<th rowspan = "3">Rata-rata Jumlah Bimbingan/ Tahun</th>'+
                                '<th rowspan = "3">Rata-rata Jumlah Bimbingan di seluruh Program/ Tahun</th>'+
                            '</tr>'+
                            '<tr>'+
                                '<th colspan = "3">pada PS yang Diakreditasi</th>'+
                                '<th colspan = "3">pada PS Lain pada Program yang sama di PT</th>'+
                            '</tr>'+
                            '<tr>'+
                                thYear+thYear+
                            '</tr>'+
                        '</thead>'+
                        '<tbody></tbody>'+
                    '</table></div>';
        selector.html(html);                               

        var selector = $('#dataTablesDataDosen tbody');
        var html_tbody = '';
        for (var i = 0; i < jsonResult.length; i++) {
            var No = parseInt(i) + 1;
            html_tbody += '<tr>';
            var arr = jsonResult[i];
            for (key in arr) {
               if (key == 'rata2BimBingan' || key == 'rata2BimBinganAll') {
                 html_tbody += '<td>'+getCustomtoFixed(arr[key],1)+'</td>';
               }
               else if(key == 'JMLDibimbingBy_PS')
               {
                var d = arr[key];
                for (var j = 0; j < d.length; j++) {
                   if (d[j].tot == 0) {
                    html_tbody += '<td>'+d[j].tot+'</td>';
                   }
                   else
                   {
                     html_tbody += '<td>'+'<a href = "javascript:void(0);" class = "datadetail" data = "'+d[j].data+'">'+d[j].tot+'</a>'+'</td>';
                   } 
                }
               }
               else if(key == 'JMLDibimbingBy_LainPS')
               {
                var d = arr[key];
                for (var j = 0; j < d.length; j++) {
                   if (d[j].tot == 0) {
                    html_tbody += '<td>'+d[j].tot+'</td>';
                   }
                   else
                   {
                     html_tbody += '<td>'+'<a href = "javascript:void(0);" class = "datadetail" data = "'+d[j].data+'">'+d[j].tot+'</a>'+'</td>';
                   } 
                }
               }
               else
               {
                 html_tbody += '<td>'+arr[key]+'</td>';
               }
            }

            html_tbody += '</tr>';
        }

        selector.append(html_tbody);
    });
}    
</script>