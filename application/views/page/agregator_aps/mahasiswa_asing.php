<h3 align="center">Mahasiswa Asing</h3><br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<div class="well">
    <div class="row">
        <div class="col-md-12">
            <div style="text-align: right;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
            <br/>
            <div id = "content_dt" class="table-responsive">

            </div>
        </div>
    </div>
</div> 
                    
<script>

var AppJQ = {
    LoadAjaxForTable : function(ProdiID,ProdiName){
                    var data = {
                        action : 'readDataMHSBaruAsingByProdi',
                        ProdiID : ProdiID,
                        ProdiName : ProdiName,
                    };

                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'api3/__crudAgregatorTB2';

                    $.post(url,{token:token},function (jsonResult) {
                        AppJQ.MakeTable(jsonResult);                           
                    });
                },
    MakeTable : function(jsonResult){
                    var selector = $('#content_dt');
                    var htmlTable = '<table class = "table table-bordered dataTable2Excel" data-name="TblMhsAsing">'+
                                        '<thead>'+
                                            '<tr>';
                    var header = jsonResult.header;
                    for (var i = 0; i < header.length; i++) {
                       htmlTable += '<th colspan = "'+header[i].colspan+'" rowspan = "'+header[i].rowspan+'"  >'+header[i].Name+'</th>';
                    }

                    htmlTable += '</tr>';

                    // for sub 
                    htmlTable += '<tr>'; 
                    for (var i = 0; i < header.length; i++) {
                       if (header[i].colspan > 1) {
                        var dt = header[i].dt;
                        for (var j = 0; j < dt.length; j++) {
                             htmlTable += '<th>'+dt[j]+'</th>';
                        }
                       }
                    }

                    htmlTable += '</tr>';                       
                    htmlTable += '</thead>';  
                    htmlTable += '<tbody>'+
                                    '<tr>';  

                    // fill body
                    var body = jsonResult.body;
                    for (var i = 0; i < body.length; i++) {
                        htmlTable += '<td>'+body[i]+'</td>';
                    }

                    htmlTable += '</tr>';
                    htmlTable += '</tbody></table>';

                    selector.html(htmlTable);
                },
    loaded : function(ProdiID,ProdiName){
                AppJQ.LoadAjaxForTable(ProdiID,ProdiName);
            },
};

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
    var filterProdi = $('#filterProdi option:selected').val();
    var filterProdiName = $('#filterProdi option:selected').text();
    if(filterProdi!='' && filterProdi!=null){
        $('#viewProdiID').html(filterProdi);
        $('#viewProdiName').html($('#filterProdi option:selected').text());
        AppJQ.loaded(filterProdi,filterProdiName);
    }
}
</script>