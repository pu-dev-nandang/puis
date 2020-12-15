<style>
    #dataTableAc tr th, #dataTableAc tr td {
        text-align: center;
    }
    .tableStd tr th, .tableStd tr td {
        text-align: center;
    }
    .tableStd tr td:first-child {
        text-align: left;
    }
</style>


<div id="generate-edom">
     <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">                                
                <div class="panel-body">
                    <div class="table-responsive">                                        
                        <div id="loadTable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function(){
       loadData();
    });

    function loadData() {

        $('#loadTable').html('<table id="tableDataSA" class="table table-bordered table-striped table-centre" style="width:100%";>' +
            '               <thead>' +
            '                <tr style="background: #337ab7; color:#fff;">' +
            '                   <th style="width: 1%;">No</th>'+
            '                    <th style="width: 25%;">Judul</th>'+
            '                    <th style="width: 10%;">Tahun Pelaksanaan</th>'+
            '                    <th style="width: 5%;">Lama Kegiatan</th>'+
            '                    <th style="width: 5%;">Dibuat oleh</th>'+
            '                    <th style="width: 5%;">Tanggal dibuat</th>'+
            '                    <th style="width: 10%;">Status Data</th>'+
            '                    <th style="width: 5%;">Aksi</th>'+
            '                </tr>' +
            '                </thead>' +
            '           </table>');

           
          
        var data = {
            action : 'viewData',
        };
           
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+"research/all-research-data";
        var dataTable = $('#tableDataSA').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Judul"
            },
            "ajax":{
                url :url, // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    // loading_modal_hide();
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );
    }
    
</script>