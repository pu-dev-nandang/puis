Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<div class="well">
    <div class="row">
        <div class="col-md-12">
            <div style="text-align: right;margin-bottom: 30px;">
                <button id="saveToExcel" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
            <div id="viewTable" class="table-responsive">
                <table class="table dataTable2Excel" data-name="Produk-melibatkan-mhs" id="dataTablesPMM">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Dosen</th>
                            <th>Tema PKM Roadmap</th>
                            <th>Nama Mahasiswa</th>
                            <th>Judul Kegiatan</th>
                            <th>Tahun</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            
        </div>

    </div>
</div>


<script>
var oTable;
var oSettings; 
var App_penelitian_melibatkan_mhs = {
    LoadAjaxTable : function(){
         var recordTable = $('#dataTablesPMM').DataTable({
             "processing": true,
             "serverSide": false,
             "ajax":{
                 url : base_url_js+"rest3/__get_APS_CrudAgregatorTB6", // json datasource
                 ordering : false,
                 type: "post",  // method  , by default get
                 data : function(token){
                       // Read values
                        var ProdiID = $('#filterProdi option:selected').val();
                        var Year = $('#FilterTahun option:selected').val();
                        var data = {
                               mode : 'penelitian_melibatkan_mhs',
                               auth : 's3Cr3T-G4N',
                               ProdiID : ProdiID,
                           };
                       // Append to data
                       token.token = jwt_encode(data,'UAP)(*');
                    }                                                                     
              },
              "order": [[ 5, "desc" ]],
               'columnDefs': [
                  {
                     'targets': 0,
                     'searchable': false,
                     'orderable': false,
                     'className': 'dt-body-center',
                  },
                  
               ],
             'createdRow': function( row, data, dataIndex ) {
                     
             },
             dom: 'l<"toolbar">frtip',
             initComplete: function(){
               
            }  
         });

         recordTable.on( 'order.dt search.dt', function () {
                                    recordTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                        cell.innerHTML = i+1;
                                    } );
                                } ).draw();

         oTable = recordTable;
         oSettings = oTable.settings();
    },

    loaded : function(){
        var firstLoad = setInterval(function () {
            var filterProdi = $('#filterProdi').val();
            if(filterProdi!='' && filterProdi!=null){
                $('#viewProdiID').html(filterProdi);
                $('#viewProdiName').html($('#filterProdi option:selected').text());
                App_penelitian_melibatkan_mhs.LoadAjaxTable();
                clearInterval(firstLoad);
            }
        },1000);
        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);
    }

};

$(document).ready(function () {
   App_penelitian_melibatkan_mhs.loaded();

});
$('#filterProdi').change(function () {
    var filterProdi = $('#filterProdi').val();
    if(filterProdi!='' && filterProdi!=null){
        oTable.ajax.reload( null, false );
        $('#viewProdiID').html(filterProdi);
        $('#viewProdiName').html($('#filterProdi option:selected').text());
    }
});

$('#saveToExcel').click(function () {
       $('select[name="dataTablesKurikulum_length"]').val(-1);
       oSettings[0]._iDisplayLength = oSettings[0].fnRecordsTotal();
       oTable.draw();
       setTimeout(function () {
           saveTable2Excel('dataTable2Excel');
       },1000);
});
</script>