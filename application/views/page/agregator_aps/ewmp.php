<h3>This is the page : ewmp.php</h3><br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
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
        }
    }
</script>