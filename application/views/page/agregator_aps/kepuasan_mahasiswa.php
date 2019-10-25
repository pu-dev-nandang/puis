Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
                    
<script>
var App_kepuasan_mhs = {
    LoadAjaxTable : function(){
         
    },

    loaded : function(){
        var firstLoad = setInterval(function () {
            var filterProdi = $('#filterProdi').val();
            if(filterProdi!='' && filterProdi!=null){
                $('#viewProdiID').html(filterProdi);
                $('#viewProdiName').html($('#filterProdi option:selected').text());
                App_kepuasan_mhs.LoadAjaxTable();
                clearInterval(firstLoad);
            }
        },1000);
        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);
    }

};

$(document).ready(function () {
   App_kepuasan_mhs.loaded();

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