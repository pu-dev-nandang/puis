Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
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
        mode : 'RekognisiDosenKaryaIlmiah',
        ProdiID : ProdiID,
    };
    var token = jwt_encode(data,"UAP)(*");
    var url = base_url_js+"rest3/__get_APS_CrudAgregatorTB3";
    $.post(url,{token:token},function (jsonResult) {
        var selector = $('#viewTable');
         var header = jsonResult.header;
         var html = '';
         var html = '<table class = "table table-bordered dataTable2Excel" id = "dataTablesRekognisiDosen" data-name="TblRekognisiDosen">'+
                        '<thead>'+
                            '<tr>';
         for (var i = 0; i < header.length; i++) {
             html += '<th rowspan = "'+header[i].rowspan+'" colspan = "'+header[i].colspan+'">'+header[i].Name+'</th>';
         }

         html += '</tr>';
         html += '<tr>';

         for (var i = 0; i < header.length; i++) {
             if (header[i].colspan > 1) {
                var Sub = header[i].Sub;
                for (var k = 0; k < Sub.length; k++) {
                   html += '<th>'+Sub[k]+'</th>'; 
                }
             }
         }

         html += '</tr>';
         html += '</thead>'+
                 '<tbody></tbody></table>';

        selector.html(html); 

        var selector = $('#dataTablesRekognisiDosen tbody');
        var body = jsonResult.body;
        if (body.length > 0) {
            for (var i = 0; i < body.length; i++) {
                  var t = '<tr>';
                  var arr = body[i];
                  for (var j = 0; j < arr.length; j++) {
                        t+= '<td>'+arr[j]+'</td>'; 
                  }

                  t += '</tr>';
                  selector.append(t);   
            }
        }
        else
        {
            selector.append('<tr><th colspan="9">No data found in the server</th></tr>');
        }
        
    });

}
</script>