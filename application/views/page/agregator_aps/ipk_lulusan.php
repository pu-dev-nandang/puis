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
    var ProdiName = $('#filterProdi option:selected').text();
    var data = {
        auth : 's3Cr3T-G4N',
        mode : 'IPKLulusan',
        ProdiID : ProdiID,
    };
    var token = jwt_encode(data,"UAP)(*");
    var url = base_url_js+"rest3/__get_APS_CrudAgregatorTB7";
    $.post(url,{token:token},function (jsonResult) {
        var selectorTbl = $('#viewTable');
        var htmltable = '<table class="table table-bordered dataTable2Excel" id ="dataTablesIPKLulusan" data-name="IPKLulusan">'+
                            '<thead>'+
                                '<tr>'+
                                    '<th colspan = "6"><label>Prodi : '+ProdiName+'</label></th>'+
                                '</tr>'+ 
                                '<tr>'+
                                '    <th rowspan = "2">No</th>'+
                                '    <th rowspan = "2">Tahun Lulus</th>'+
                                '    <th rowspan = "2">Jumlah Lulusan</th>'+
                                '    <th colspan = "3">Indeks Prestasi Kumulatif</th>'+
                                '</tr>'+
                                '<tr>'+
                                '<th>Min</th>'+
                                '<th>Rata - Rata</th>'+
                                '<th>Maks</th>'+
                                '</tr>'+
                            '</thead>'+
                            '<tbody></tbody>'+
                        '</table> '

        selectorTbl.html(htmltable);
        var selector = $('#dataTablesIPKLulusan tbody');
        selector.empty();
        for (let i = 0; i < jsonResult.length; i++) {
            var No = parseInt(i)+ 1;
            var html = '';
            html += '<tr>'+
                        '<td>'+No+'</td>';
            
            var arr = jsonResult[i];
            for (let k = 0; k < arr.length; k++) {
                if (k == 1) {
                    var ahref = '<a href = "javascript:void(0);" class = "datadetail" data = "'+arr[k].token+'">'+arr[k].Count+'</a>';
                    html += '<td>'+ahref+'</td>';
                }
                else{
                    if (k== 0) {
                        html += '<td>'+arr[k]+'</td>';
                    }
                    else{
                        html += '<td>'+getCustomtoFixed(arr[k],2)+'</td>';
                    }
                   
                }
                
            }

            html += '</tr>';

            selector.append(html);
            
        }


    });
}

$(document).off('click', '.datadetail').on('click', '.datadetail',function(e) {
    var v = parseInt($(this).html());
    if (v > 0) {
        var dt = $(this).attr('data');
        
        dt = jwt_decode(dt);
        // console.log(dt);
        var html =  '<div class = "row">'+
                        '<div class = "col-md-12">'+
                            '<table class = "table">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<td>No</td>'+
                                        '<td>NPM</td>'+
                                        '<td>NAMA</td>'+
                                        '<td>IPK</td>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody>';
                for (var i = 0; i < dt.length; i++) {
                    html += '<tr>'+
                                '<td>'+ (parseInt(i)+1) + '</td>'+
                                '<td>'+ dt[i].NPM + '</td>'+
                                '<td>'+ dt[i].Name + '</td>'+
                                '<td>'+getCustomtoFixed(dt[i].IPK,2)+ '</td>'+

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