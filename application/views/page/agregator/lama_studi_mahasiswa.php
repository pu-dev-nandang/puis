<!-- <h1>lama_studi_mahasiswa</h1> -->
 

<style>
    /*#dataTablesPAM tr th, #dataTablesPAM tr td {
        text-align: center;
    }*/

    #dataTablesPAM tr th{
        text-align: center;
    }
</style>

<div class="well">

    <div class="row">
        <div class="col-md-12">
            <div style="text-align: right;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
                <!-- <button id="saveToExcel" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button> -->
            </div>
            <br/>
            <div id="viewTable"></div>
        </div>
    </div>
</div>
<script>
    
    $(document).ready(function () {
        loadDataPAM();
    });

    function loadDataPAM() {
        var HtmlTable = ' <table class="table dataTable2Excel" id="dataTablesPAM" data-name="TblLamaStudiMahasiswa">' +
            '                <thead>' +
			'                <tr>    ' +
			'                    <th colspan="2" style="border-right: 1px solid #ccc;"></th> ' +
			'                    <th colspan="3" style="border-right: 1px solid #ccc;">Jumlah Lulusan pada</th> ' +
			'                    <th colspan="3" style="border-right: 1px solid #ccc;">Rata-rata Masa Studi Lulusan pada</th>  ' +
			'                    <th style="border-right: 1px solid #ccc;"></th>  ' +
			'                </tr>';
            


        var data = {
            action : 'viewLamaStudy',
            Type : '1'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB5';

        $.post(url,{token:token},function (jsonResult) {
        	// console.log(jsonResult); 
            var header = jsonResult.header;
            var arr_total = [];  
            for (var i = 0; i < header.length; i++) {
                 HtmlTable += '<td style = "text-align: center">'+header[i]+'</td>';
                 if (i >= 2 && i <= 4) { // define total Jumlah PS dan Jumlah Lulusan pada
                     arr_total.push(0);
                 }
            }

            HtmlTable+= '</tr></thead><tbody id = "listData"></tbody>';
            HtmlTable+= '</table>';
            $('#viewTable').html(HtmlTable);

            var body = jsonResult.body;
            if (body.length > 0) {
                var htmlBody = '';
                for (var i = 0; i < body.length; i++) {
                    htmlBody += '<tr>';
                    var No = parseInt(i) + 1;
                    var arr_body = body[i];
                    htmlBody += '<td>'+No+'</td>';
                    for (var j = 0; j < arr_body.length; j++) {
                        if(j < 4){
                            htmlBody += '<td style = "text-align: center">'+arr_body[j]+'</td>';
                            if (j >= 1) { // isi total Jumlah PS dan Jumlah Lulusan pada
                                arr_total[(j-1)] = parseInt(arr_total[(j-1)]) + parseInt(arr_body[j]);
                            }
                        }
                        else
                        {
                            RataMasaStudy = getCustomtoFixed(arr_body[j],1);
                            htmlBody += '<td style = "text-align: center">'+RataMasaStudy+'</td>';
                        }
                    }

                    htmlBody += '</tr>';
                }

                $('#listData').append(htmlBody);
                // console.log(arr_total);
                var tbl = $('#listData').closest('table');
                var isian = '';
                for (var i = 0; i < arr_total.length; i++) {
                    isian += '<td style = "text-align: center">'+arr_total[i]+'</td>';
                }
                tbl.append(
                    '<tfoot>'+
                        '<tr>'+
                            '<td colspan = "2">Jumlah</td>'+
                            isian+
                        '</tr>'+
                    '</tfoot>'        
                    );  

            }

        });
    }
</script>