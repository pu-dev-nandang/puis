<style type="text/css">
        #dataTablesAM thead th {
            vertical-align: middle;
        }

        #dataTablesAM thead>tr>th, #dataTablesAM tbody>tr>th, #dataTablesAM tfoot>tr>th, #dataTablesAM thead>tr>td, #dataTablesAM tbody>tr>td, #dataTablesAM tfoot>tr>td {
            padding: 3px;
            line-height: 1.428571429;
            vertical-align: top;
        }

        #dataTablesAM tbody {
            width:2000px;
            overflow:auto;
        }
        #dataTablesAM thead,#dataTablesAM tbody tr {
            width:2000px;
           
        }
        #dataTablesAM thead {
            width: calc( 100% - -46.8em )
        }
        #dataTablesAM table {
            width:2000px;
        }
</style>
Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<div class="well">
    <div class="row">
        <div class="col-md-12">
            <div style="text-align: right;margin-bottom: 30px;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
            <div id="viewTable" class="table-responsive">
                <table class="table table-bordered dataTable2Excel" data-name="Alumni-Mhs" id="dataTablesAM" style = "min-width: 900px;">
                   <thead>
                      <tr>
                          <th rowspan="2">Tahun Lulus</th>
                          <th rowspan="2">Jumlah Lulusan</th>
                          <th rowspan="2">Jumlah Lulusan yang Terlacak</th>
                          <th colspan="3">Jumlah lulusan dengan waktu tunggu mendapatkan pekerjaan (Sarjana)</th>
                          <th colspan="3">Jumlah lulusan dengan waktu tunggu mendapatkan pekerjaan (Sarjana Terapan)</th>
                          <th colspan="3">Jumlah lulusan dengan tingkat keseuaian bidang kerja</th>
                          <th rowspan="2">Jumlah Lulusan yang Telah Bekerja/ Berwirausaha</th>
                          <th colspan="3">Jumlah Lulusan yang Bekerja Berdasarkan Tingkat/Ukuran Tempat Kerja/Berwirausaha</th>
                          <th rowspan="2">Jumlah Tanggapan Kepuasan Pengguna yang Terlacak</th>
                      </tr>
                      <tr>
                          <th>WT < 6 bulan</th>
                          <th>6 ≤ WT ≤ 18 bulan</th>
                          <th>WT > 18 bulan</th>
                          <th>WT < 3 bulan</th>
                          <th>3 ≤ WT ≤ 6 bulan</th>
                          <th>WT > 6 bulan</th>
                          <th>Rendah</th>
                          <th>Sedang</th>
                          <th>Tinggi</th>
                          <th>Lokal/ Wilayah/ Berwirausaha tidak Berbadan Hukum</th>
                          <th>Nasional/ Berwirausaha Berbadan Hukum</th>
                          <th>Multinasiona/ Internasional</th>
                      </tr>
                  </thead>
                  <tbody></tbody>
                  <tfoot></tfoot>
                </table>
            </div>
            
        </div>

    </div>
</div>                    

<script>
var App_alumni_mahasiswa = {
    LoadAjaxTable : function(){
        // fill tahun
        var selector = $('#dataTablesAM tbody');
        var url = base_url_js+"rest3/__get_APS_CrudAgregatorTB8";
        var ProdiID = $('#filterProdi option:selected').val();
        var FilterSemester = $('#FilterSemester option:selected').val();
        var data = {
               mode : 'alumni_mahasiswa',
               auth : 's3Cr3T-G4N',
               ProdiID : ProdiID,
        };

        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{ token:token },function (resultJson) {
            selector.empty();
            var html = '';
            // define arr_total
            var arr_total = [];
            var ss = resultJson[0];
            for (var i = 1; i < ss.length; i++) {
                arr_total.push(0);
            }

            for (var i = 0; i < resultJson.length; i++) {
                var row = resultJson[i];
                html += '<tr>';
                for (var j = 0; j < row.length; j++) {
                    if (j==0) {
                       html += '<td>'+row[j]+'</td>'; 
                    }
                    else
                    {
                        var n = j -1;
                        arr_total[n] = arr_total[n]+row[j].total;
                       html += '<td>'+'<a href = "javascript:void(0);" class = "datadetail" data = "'+row[j].dt+'">'+row[j].total+'</a>'+'</td>';
                    }
                    
                }
                html += '</tr>';
            }
            selector.html(html);

            var html2 = '<tr>'+
                            '<td style= "font-weight:600;text-align:center">Total</td>';
            var selector2 = $('#dataTablesAM tfoot');
            selector2.empty();
            for (var i = 0; i < arr_total.length; i++) {
                html2 += '<td>'+arr_total[i]+'</td>';
            }

            html2 += '</tr>';
            selector2.html(html2);

        }).fail(function() {
            toastr.error("Connection Error, Please try again", 'Error!!');
        }).always(function() {

        });

    },

    loaded : function(){
        var firstLoad = setInterval(function () {
            var filterProdi = $('#filterProdi').val();
            if(filterProdi!='' && filterProdi!=null){
                $('#viewProdiID').html(filterProdi);
                $('#viewProdiName').html($('#filterProdi option:selected').text());
                App_alumni_mahasiswa.LoadAjaxTable();
                clearInterval(firstLoad);
            }
        },1000);
        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);
    }

};

$(document).ready(function () {
   App_alumni_mahasiswa.loaded();
});
$('#filterProdi').change(function () {
    var filterProdi = $('#filterProdi').val();
    if(filterProdi!='' && filterProdi!=null){
        $('#viewProdiID').html(filterProdi);
        $('#viewProdiName').html($('#filterProdi option:selected').text());
        App_alumni_mahasiswa.LoadAjaxTable();
    }
});
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
