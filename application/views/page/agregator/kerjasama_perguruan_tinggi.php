<style>
    #TblKerjaSama tr th, #TblKerjaSama tr td {
        text-align: center;
    }

    #TblKerjaSama tr td {
        vertical-align : middle;
        text-align:center;
        border-right: 1px solid #ccc;
    }
</style>
<div class="well">

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12" style="text-align: right">
                    <b>Download File : </b>
                    <button class="btn btn-success btn-circle" id="btndownloaadExcel"><i class="fa fa-file-excel-o"></i> </button>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id = "TblKerjaSama" style="width: 100%">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Lembaga Mitra Kerjasama</th>
                                    <th rowspan="2">Kategori</th>
                                    <th colspan="3">Tingkat</th>
                                    <th rowspan="2">Bentuk Kegiatan / Manfaat</th>
                                    <th rowspan="2">Bukti Kerjasama</th>
                                    <th rowspan="2">Masa Berlaku (Tahun Berakhir, YYYY)</th>
                                    <th rowspan="2">Semester</th>
                                </tr>
                                <tr>
                                    <th>Internasional</th>
                                    <th>Nasional</th>
                                    <th>Wilayah </th>
                                </tr>
                            </thead>
                            <tbody id = "Listdata"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <p style="color: orangered;">*) Yang terhitung hanya kerja sama yang melakukan kegiatan</p>
    </div>

</div>
<script type="text/javascript">
    var passToExcel = [];
    $(document).ready(function () {
        LoadFirst();
    });

    function LoadFirst()
    {
        LoadTableData();
    }

    function LoadTableData()
    {
        var data = {
            auth : 's3Cr3T-G4N',
            mode : 'DataKerjaSamaAggregator',
        };
        var token = jwt_encode(data,"UAP)(*");
        $('#TblKerjaSama tbody').empty();

        var table = $('#TblKerjaSama').DataTable({
            "fixedHeader": true,
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "lengthMenu": [[15], [15]],
            "iDisplayLength" : 15,
            "ordering" : false,
          "language": {
              "searchPlaceholder": "Search",
          },
            "ajax":{
                url : base_url_js+"rest2/__get_data_kerja_sama_perguruan_tinggi", // json datasource
                ordering : false,
                type: "post",  // method  , by default get
                data : {token : token},
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            },
            'createdRow': function( row, data, dataIndex ) {
                var Internasional = (data[2] == 1) ? '<i class="fa fa-check" style="color: green;"></i>' : '<i class="fa fa-minus-circle" style="color: red;"></i>';
                var Nasional = (data[3] == 1) ? '<i class="fa fa-check" style="color: green;"></i>' : '<i class="fa fa-minus-circle" style="color: red;"></i>';
                var Lokal = (data[4] == 1) ? '<i class="fa fa-check" style="color: green;"></i>' : '<i class="fa fa-minus-circle" style="color: red;"></i>';
                $( row ).find('td:eq(3)').html(Internasional);
                $( row ).find('td:eq(4)').html(Nasional);
                $( row ).find('td:eq(5)').html(Lokal);

                var File = jQuery.parseJSON(data[7]);
                var html = data[6]+'</br><a href = "'+base_url_js+'fileGetAny/cooperation-'+File[0]+'" target="_blank" class = "Fileexist">Attachment</a>';
                $( row ).find('td:eq(7)').html(html);

                 $( row ).find('td:eq(8)').html(data[8]);
                 $( row ).find('td:eq(9)').html(data[9]);
                 $( row ).find('td:eq(2)').html(data[13]);
            },
            dom: 'l<"toolbar">frtip',
            "initComplete": function(settings, json) {
                passToExcel = json.queryPass;
            }
        });
    }

    $("#btndownloaadExcel").click(function(){
        if (passToExcel != '') {
          var url = base_url_js+'agregator/excel-kerjasama-perguruan-tinggi';
          data = {
            passToExcel : passToExcel
          }
          var token = jwt_encode(data,"UAP)(*");
          FormSubmitAuto(url, 'POST', [
              { name: 'token', value: token },
          ]);
        }
    })
</script>
