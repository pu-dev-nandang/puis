<style type="text/css">
    #dataTablesDataDosen thead th {
        vertical-align: middle;
    }

    #dataTablesDataDosen thead>tr>th, #dataTablesDataDosen tbody>tr>th, #dataTablesDataDosen tfoot>tr>th, #dataTablesDataDosen thead>tr>td, #dataTablesDataDosen tbody>tr>td, #dataTablesDataDosen tfoot>tr>td {
        padding: 3px;
        line-height: 1.428571429;
        vertical-align: top;
    }

    #dataTablesDataDosen tbody {
        display:block;
        height:520px;
        width:2200px;
        overflow:auto;
    }
    #dataTablesDataDosen thead,#dataTablesDataDosen tbody tr {
        display:table;
        width:2200px;
        table-layout:fixed; /* even columns width , fix width of table too*/
    }
    #dataTablesDataDosen thead {
        width: calc( 100% - 1em ) scrollbar is average 1em/16px width, remove it from thead width 
    }
    #dataTablesDataDosen table {
        width:2200px;
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
            <p style="color: orangered;">*) Table data dosen mencakup laporan APS table 3.a1, 3.a2, 3.a4, 3a5</p>
            <p style="color: orangered;">*) Data Dosen yang terhitung adalah dosen yang mempunyai status forlap</p>
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
        var html = '<div style = "height:700px;overflow-y:auto;overflow-x:auto;">';

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
                        ''
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
                var v = (arr[key] == null || arr[key] == undefined ) ? '' : arr[key];
                 html_tbody += '<td>'+v+'</td>';
               }
            }

            html_tbody += '</tr>';
        }

        selector.append(html_tbody);
    });
}

$(document).off('click', '.datadetail').on('click', '.datadetail',function(e) {
    var v = parseInt($(this).html());
    if (v > 0) {
        var dt = $(this).attr('data');
        // console.log(dt);
        dt = jwt_decode(dt);
        var html =  '<div class = "row">'+
                        '<div class = "col-md-12">'+
                            '<table class = "table">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<td>No</td>'+
                                        '<td>NPM</td>'+
                                        '<td>NAMA</td>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody>';
                for (var i = 0; i < dt.length; i++) {
                    html += '<tr>'+
                                '<td>'+ (parseInt(i)+1) + '</td>'+
                                '<td>'+ dt[i].NPM + '</td>'+
                                '<td>'+ dt[i].Name + '</td>'+
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