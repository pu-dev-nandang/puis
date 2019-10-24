Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<div class="well">
    <div class="row">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="form-group">
                    <label for="">Semester</label>
                    <select name="" id="FilterSemester" class="form-control">
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div style="text-align: right;margin-bottom: 30px;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
            <div id="viewTable" class="table-responsive">
                <table class="table dataTable2Excel" data-name="Publikasi-ilmiah-dtps" id="dataTablesPID">
                   <thead>
                      <tr>
                          <th>No</th>
                          <th>Judul Penelitian/PkM</th>
                          <th>Nama Dosen</th>
                          <th>Mata Kuliah</th>
                          <th>Bentuk Integrasi</th>
                          <th>Tahun</th>
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
var App_integrasi_penelitian_pkm = {
    LoadAjaxTable : function(){
        // fill tahun
        var selector = $('#dataTablesPID tbody');
        var url = base_url_js+"rest3/__get_APS_CrudAgregatorTB5";
        var ProdiID = $('#filterProdi option:selected').val();
        var FilterSemester = $('#FilterSemester option:selected').val();
        var data = {
               mode : 'Integrasi_penelitian_dkm',
               auth : 's3Cr3T-G4N',
               ProdiID : ProdiID,
               FilterSemester : FilterSemester,
        };

        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{ token:token },function (resultJson) {
            selector.empty();
            var html = '';
            if (resultJson.length > 0) {
                for (var i = 0; i < resultJson.length; i++) {
                    var row = resultJson[i];
                    html += '<tr>';
                    for (var j = 0; j < row.length; j++) {
                       html += '<td>'+ ((row[j] == null) ? '' : row[j]) +'</td>';
                    }
                    html += '</tr>';
                }
                
            }
            else
            {
                html += '<tr>'+
                            '<td colspan = "6" style = "font-weight:600;text-align:center;">No Data Found on the Server</td>'+
                        '</tr>';    
            }
            selector.html(html);
        }).fail(function() {
            toastr.error("Connection Error, Please try again", 'Error!!');
        }).always(function() {

        });

    },

    loaded : function(){
        loSelectOptionSemester('#FilterSemester','selectedNow');
        var firstLoad = setInterval(function () {
            var filterProdi = $('#filterProdi').val();
            var FilterSemester = $('#FilterSemester').val();
            if(filterProdi!='' && filterProdi!=null && FilterSemester!='' && FilterSemester!=null){
                $('#viewProdiID').html(filterProdi);
                $('#viewProdiName').html($('#filterProdi option:selected').text());
                App_integrasi_penelitian_pkm.LoadAjaxTable();
                clearInterval(firstLoad);
            }
        },1000);
        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);
    }

};

$(document).ready(function () {
   App_integrasi_penelitian_pkm.loaded();

});
$('#filterProdi').change(function () {
    var filterProdi = $('#filterProdi').val();
    if(filterProdi!='' && filterProdi!=null){
        $('#viewProdiID').html(filterProdi);
        $('#viewProdiName').html($('#filterProdi option:selected').text());
        App_integrasi_penelitian_pkm.LoadAjaxTable();
    }
});

$(document).off('change', '#FilterSemester').on('change', '#FilterSemester',function(e) {
    App_integrasi_penelitian_pkm.LoadAjaxTable();
})
</script>