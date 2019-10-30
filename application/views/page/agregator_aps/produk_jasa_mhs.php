Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<div class="well">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="form-group">
                <label for="">Tahun</label>
                <select name="" id="FilterTahun" class="form-control">
                </select>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-12">
            <div style="text-align: right;margin-bottom: 30px;">
                <button id="saveToExcel" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
            <div id="viewTable" class="table-responsive">
                <table class="table dataTable2Excel" data-name="Produk-jasa-dtps" id="dataTablesPJD">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Nama Produk/Jasa</th>
                            <th>Deskripsi Produk/Jasa</th>
                            <th>Bukti</th>
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
var App_produk_jasa_mhs = {
    LoadYear : function(SelectedData = null)
    {
        var selector = $('#FilterTahun');
        selector.empty();
        var StartYear = 2014;
        var YearNow = <?php echo date('Y') ?>;
        for (var i = StartYear; i <= YearNow; i++) {
           var selected = (SelectedData == i) ? 'selected' : '';
           selector.append(
                '<option value = "'+i+'" '+selected+' >'+i+'</option>'
            );
        }
    },

    LoadAjaxTable : function(){
         var recordTable = $('#dataTablesPJD').DataTable({
             "processing": true,
             "serverSide": false,
             "ajax":{
                 url : base_url_js+"rest3/__get_APS_CrudAgregatorTB8", // json datasource
                 ordering : false,
                 type: "post",  // method  , by default get
                 data : function(token){
                       // Read values
                        var ProdiID = $('#filterProdi option:selected').val();
                        var Year = $('#FilterTahun option:selected').val();
                        var data = {
                               mode : 'produk_jasa_mhs',
                               auth : 's3Cr3T-G4N',
                               ProdiID : ProdiID,
                               Year : Year,
                           };
                       // Append to data
                       token.token = jwt_encode(data,'UAP)(*');
                    }                                                                     
              },
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
        App_produk_jasa_mhs.LoadYear();
        var firstLoad = setInterval(function () {
            var filterProdi = $('#filterProdi').val();
            var FilterTahun = $('#FilterTahun').val();
            if(filterProdi!='' && filterProdi!=null && FilterTahun !='' && FilterTahun!=null){
                $('#viewProdiID').html(filterProdi);
                $('#viewProdiName').html($('#filterProdi option:selected').text());
                App_produk_jasa_mhs.LoadAjaxTable();
                clearInterval(firstLoad);
            }
        },1000);
        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);
    }

};

$(document).ready(function () {
   App_produk_jasa_mhs.loaded();

});
$('#filterProdi').change(function () {
    var filterProdi = $('#filterProdi').val();
    if(filterProdi!='' && filterProdi!=null){
        oTable.ajax.reload( null, false );
        $('#viewProdiID').html(filterProdi);
        $('#viewProdiName').html($('#filterProdi option:selected').text());
    }
});

$(document).off('change', '#FilterTahun').on('change', '#FilterTahun',function(e) {
    oTable.ajax.reload( null, false );
})

$('#saveToExcel').click(function () {
       $('select[name="dataTablesKurikulum_length"]').val(-1);
       oSettings[0]._iDisplayLength = oSettings[0].fnRecordsTotal();
       oTable.draw();
       setTimeout(function () {
           saveTable2Excel('dataTable2Excel');
       },1000);
});
</script>