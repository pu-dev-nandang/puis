Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
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
                        mode : 'MasaStudiLulusan',
                        ProdiID : ProdiID,
                        ProdiName : ProdiName,
                        auth : 's3Cr3T-G4N',
                    };

                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'rest3/__get_APS_CrudAgregatorTB7';

                    $.post(url,{token:token},function (jsonResult) {
                        AppJQ.MakeTable(jsonResult);                           
                    });
                },
    MakeTable : function(jsonResult){
                    var selector = $('#content_dt');
                    var htmlTable = '<table class = "table table-bordered dataTable2Excel" data-name="TblMasaStudiLulusan">'+
                                        '<thead>'+
                                            '<tr>';
                    var header = jsonResult.header;
                    // tulis prodi Name
                    var filterProdiName = $('#filterProdi option:selected').text();
                    var colH = 0;
                    for (var i = 0; i < header.length; i++) {
                        colH += header[i].colspan;
                    }
                    htmlTable += '<th colspan = "'+colH+'"><label style = "text-align:center;">'+filterProdiName+'</label></th>';
                    htmlTable += '</tr><tr>';

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
                        var dd = body[i];
                        for (var j = 0; j < dd.length; j++) {
                            var d = dd[j];
                            if (d["dt"] != undefined) {
                                htmlTable += '<td>'+'<a href = "javascript:void(0);" class = "datadetail" data = "'+d.dt+'">'+d.count+'</a>'+'</td>';
                            }
                            else{
                                // rata-rata masa studi
                                if (j == (dd.length) - 1  ) {
                                    htmlTable += '<td>'+getCustomtoFixed(d,1)+'</td>';
                                    
                                }
                                else
                                {
                                    htmlTable += '<td>'+d+'</td>';
                                }
                                
                            }

                        }

                       htmlTable += '</tr>';
                    }

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