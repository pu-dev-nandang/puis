Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<div class="well">
    <div class="row">
        <div class="col-md-12">
            <div style="text-align: right;margin-bottom: 30px;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
            <div id="viewTable" class="table-responsive">
                <table class="table dataTable2Excel table-bordered" data-name="Luaran-Penelititan-DTPS" id="TBLPD">
                    <thead>
                        <tr>
                            <th style="width: 3%;">No</th>
                            <th>Luaran Penelitian dan PKM</th>
                            <th>Dosen</th>
                            <th>Tahun</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            
        </div>

    </div>
</div>

<script>

var APP_luaran_penelitan_dtps = {
    LoadAjaxTable : function(){
        var selector = $('#TBLPD tbody');
        var url = base_url_js+"rest3/__get_APS_CrudAgregatorTB3";
        var ProdiID = $('#filterProdi option:selected').val();
        var data = {
               mode : 'luaran_penelitan_dtps',
               auth : 's3Cr3T-G4N',
               ProdiID : ProdiID,
        };
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{ token:token },function (resultJson) {
            selector.empty();
            // console.log(resultJson);
            var html = '';
            for (var i = 0; i < resultJson.length; i++) {
               var dt = resultJson[i].Data;
               for (var j = 0; j < dt.length; j++) { // row
                   var fill = dt[j];
                   html += '<tr>';
                   for (var k = 0; k < fill.length; k++) { // col
                       var colspan = 'colspan = "'+fill[k].colspan+'"';
                       var style = 'style = '+fill[k].style;
                       var text = fill[k].text;
                       html += '<td '+colspan+' '+style+'>'+text+'</td>';
                       
                   }
                   // console.log(dt.length);
                   html += '</tr>';
                   if (dt.length == 1) { // buat no found server
                    html += '<tr>';
                    html += '<td colspan = "5" style = "text-align:center;font-weight:600;">'+'--No Data Found--'+'</td>';
                    html += '</tr>';
                   }
               }
            }
            selector.html(html);
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
                APP_luaran_penelitan_dtps.LoadAjaxTable();
                clearInterval(firstLoad);
            }
        },1000);
        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);
    }
};    
$(document).ready(function () {
    APP_luaran_penelitan_dtps.loaded();
});
$('#filterProdi').change(function () {
    var filterProdi = $('#filterProdi').val();
    if(filterProdi!='' && filterProdi!=null){
        APP_luaran_penelitan_dtps.LoadAjaxTable();
        $('#viewProdiID').html(filterProdi);
        $('#viewProdiName').html($('#filterProdi option:selected').text());
    }
});

</script>