Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<div id="content_data">
    
</div>
<script>
    var passToExcel = [];
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
        passToExcel = [];
        if(filterProdi!='' && filterProdi!=null){
            $('#viewProdiID').html(filterProdi);
            $('#viewProdiName').html($('#filterProdi option:selected').text());

             var htmltable = '<div class = "row"><div class = "col-md-12">'+
                '<div style="text-align: right"> <b>Download File : </b><button class="btn btn-success btn-circle" id="btndownloaadExcel" title="Dowload Excel"><i class="fa fa-file-excel-o"></i> </button></div>'+
                '<table class="table table-striped table-bordered" id="tableData">'+
                 '<thead>'+
                     '<tr style="background: #20485A;color: #FFFFFF;">'+
                         '<th style="vertical-align : middle;text-align:center;width: 2%;">No</th>'+
                         '<th style="vertical-align : middle;text-align:center;width: 15%;">Nama Dosen</th>'+
                         '<th style="vertical-align : middle;text-align:center;">NIDN</th>'+
                         '<th style="vertical-align : middle;text-align:center;">Pendidikan Pasca Sarjana</th>'+
                         '<th style="vertical-align : middle;text-align:center;">Bidang Keahlian</th>'+
                         '<th style="vertical-align : middle;text-align:center;">Jabatan Akademik</th>'+
                         '<th style="vertical-align : middle;text-align:center;">Sertifikat Pendidik Profesional</th>'+
                         '<th style="vertical-align : middle;text-align:center;">Mata Kuliah yang Diampu pada PS yang Diakreditasi</th>'+
                         '<th style="vertical-align : middle;text-align:center;">Kesesuaian Bidang Keahlian dengan Mata Kuliah yang Diampu</th>'+
                     '</tr>'+
                 '</thead>'+
                 '<tbody id="listData"></tbody>'+
             '</table></div></div>';
            $('#content_data').html(htmltable);

            var data = {
                           action : 'readDataDosenTidakTetap',
                           filterProdi : filterProdi,
                           filterProdiName : filterProdiName,
                       };
           var token = jwt_encode(data,'UAP)(*');
           var url = base_url_js+'api4/__crudAgregatorTB3';
            $.post(url,{token:token},function (jsonResult) {
                
            })

        }
    }

</script>