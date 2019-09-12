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
        loadIPK();
    });
    
    function loadIPK() {

        var data = {
            action : 'viewIPK',
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB5';

        $.post(url,{token:token},function (jsonResult) {
            var HtmlTable ='<table class ="table dataTable2Excel" data-name="TblviewIPK">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th colspan="2" style="border-right: 1px solid #ccc;"></th>'+
                                        '<th style="border-right: 1px solid #ccc; text-align: center"> Jumlah PS </th>'+
                                        '<th colspan="3" style="border-right: 1px solid #ccc; text-align: center">Jumlah Lulusan pada</th>'+
                                        '<th colspan="3" style="border-right: 1px solid #ccc; text-align: center">Rata-rata IPK Lulusan pada</th>'+
                                        '<th style="border-right: 1px solid #ccc;"></th>'+
                                    '</tr>'+
                                    '<tr>';    

           var header = jsonResult.header;
           var arr_total = [];  
           for (var i = 0; i < header.length; i++) {
                HtmlTable += '<td>'+header[i]+'</td>';
                if (i >= 2 && i <= 5) { // define total Jumlah PS dan Jumlah Lulusan pada
                    arr_total.push(0);
                }
            }

            HtmlTable+= '</tr></thead><tbody id = "listData"></tbody>';
            HtmlTable+= '</table>';
            $('#viewTable').html(HtmlTable);
            var body = jsonResult.body;
            var htmlBody = '';
            for (var i = 0; i < body.length; i++) {
                htmlBody += '<tr>';
                var No = parseInt(i) + 1;
                var arr_body = body[i];
                // console.log(arr_body);
                htmlBody += '<td>'+No+'</td>';
                for (var j = 0; j < arr_body.length; j++) {
                    if(j < 5){
                        htmlBody += '<td>'+arr_body[j]+'</td>';
                        if (j >= 1) { // isi total Jumlah PS dan Jumlah Lulusan pada
                            arr_total[(j-1)] = parseInt(arr_total[(j-1)]) + parseInt(arr_body[j]);
                        }
                    }
                    else
                    {
                        IPK = getCustomtoFixed(arr_body[j],2);
                        htmlBody += '<td>'+IPK+'</td>';
                    }
                }

                htmlBody += '</tr>';
            }

            $('#listData').append(htmlBody);  

            // console.log(arr_total);
            var tbl = $('#listData').closest('table');
            var isian = '';
            for (var i = 0; i < arr_total.length; i++) {
                isian += '<td>'+arr_total[i]+'</td>';
            }
            tbl.append(
                '<tfoot>'+
                    '<tr>'+
                        '<td colspan = "2">Jumlah</td>'+
                        isian+
                    '</tr>'+
                '</tfoot>'        
                );

        });    
    }
</script>