
<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }

    .td-av {
        background: #ffe7c4;
        font-weight: bold;
    }
    .higligh {
        background: lightyellow;
    }
    
</style>

<div class="well">
    <div class="row">
        <div class="col-md-12">

            <div>
                <div class="col-md-3 col-md-offset-4">
                    <select class="form-control" id="filterTahun"><option id="0" selected> Semua Tahun</option></select>
                </div>
                <div style="text-align: right;margin-bottom: 0px;">
                    <button id="saveToExcel" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
                </div>
                
            </div>
            <br/>

            <div class="">
                <table class="table table-striped table-bordered dataTable2Excel" id="tableHKIPaten"  data-name="tableHKIPaten">
                    <thead>
                    <tr style="background: #20485A;color: #FFFFFF;">
                        <th style="text-align: center; width: 5%;">No</th>
                        <th style="text-align: center; width: 20%;">Luaran Penelitian dan PkM</th>
                        <th style="text-align: center; width: 10%;">Tahun Perolehan (YYYY)</th> 
                        <th style="text-align: center; width: 15%;">Keterangan</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
         
            <!-- <div id="viewTable"></div> -->
        </div>
    </div>
</div>


<script>

    var oTableGet;
    var oSettingsGet;

    $(document).ready(function () {
        loadSelectOptionClassOf_DSC('#filterTahun');
        loadDataHKI_paten();
    });

    $('#filterTahun').change(function () {
        
        var status = $('#filterTahun option:selected').attr('id');
        loadDataHKI_paten(status);
    });


    function loadDataHKI_paten(status) {
        var status = $('#filterTahun option:selected').attr('id');

        var dataTable = $('#tableHKIPaten').DataTable({
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"api3/__getHkiPaten?s="+status, // json datasource
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        });

        oTableGet = dataTable;
        oSettingsGet = oTableGet.settings();
    }

    $('#saveToExcel').click(function () {

       $('select[name="dataTablesKurikulum_length"]').val(-1);
       oSettingsGet[0]._iDisplayLength = oSettingsGet[0].fnRecordsTotal();
       oTableGet.draw();
       setTimeout(function () {
           saveTable2Excel('dataTable2Excel');
       },1000);
    });
</script>

<script>
    //$(document).ready(function () {
    //    
    //    loadSelectOptionClassOf_DSC('#filterTahun');
    //    loadDataTable();
        //loadSelectOptionClassOf_DSC();
    //});

    function loadSelectOptionClassOf_DSC() {
        var url = base_url_js+'api/__getKurikulumSelectOptionDSC';
        $.getJSON(url,function (jsonResult) {
            for(var i=0;i<jsonResult.length;i++){
               $('#filterTahun').append('<option id="'+jsonResult[i].Year+'">'+jsonResult[i].Year+' </option>');
            }
        });
    }

    // function hitungRow(cl) {

    //     var res = 0;
    //     $(cl).each(function () {
    //         res += parseInt($(this).attr('data-val'));
    //     });

    //     return res;
    // }

</script>