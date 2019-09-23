<h3>This is the page : kerjasama_tridharma.php</h3><br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<div id="content_dt">
    
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
        var selector = $('#content_dt');

        if(filterProdi!='' && filterProdi!=null){
            $('#viewProdiID').html(filterProdi);
            $('#viewProdiName').html($('#filterProdi option:selected').text());

            var html = '<div class = "row" style = "margin-top : 10px;">'+
                            '<div class = "col-xs-12">'+
                                '<div class = "well">'+
                                    '<div class = "row">'+
                                        '<div class = "col-xs-12">'+
                                            '<table class="table table-bordered" id = "TblKerjaSama" style="width: 100%">'+
                                        '<thead>'+
                                           ' <tr>'+
                                              '  <th rowspan="2">No</th>'+
                                             '   <th rowspan="2">Lembaga Mitra Kerjasama</th>'+
                                                '<th colspan="3">Tingkat</th>'+
                                             '   <th rowspan="2">Judul Kegiatan Kerjasama</th>'+
                                             '   <th rowspan="2">Manfaat bagi PS yang Diakreditasi</th>'+
                                             '   <th rowspan="2">Waktu dan Durasi</th>'+
                                             '   <th rowspan="2">Bukti Kerjasama</th>'+
                                             '   <th rowspan="2">Masa Berlaku (Tahun Berakhir, YYYY)</th>'+
                                             '   <th rowspan="2">Semester</th>'+
                                            '</tr>'+
                                            '<tr>'+
                                                '<th>Internasional</th>'+
                                                '<th>Nasional</th>'+
                                                '<th>Wilayah </th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody id = "Listdata"></tbody>'+
                                    '</table>'+
                                '</div>'+
                            '</div>'+
                        '</div></div></div>';            

            selector.html(html);

            LoadTableData(filterProdi);
        }
    }

    function LoadTableData(filterProdi)
    {
        var P = filterProdi.split('.');
        var ProdiID = P[0];
        var data = {
            auth : 's3Cr3T-G4N',
            mode : 'DataKerjaSamaAggregator',
            ProdiID : ProdiID,
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
                $( row ).find('td:eq(2)').html(Internasional);
                $( row ).find('td:eq(2)').attr('style','text-align:center');
                $( row ).find('td:eq(3)').html(Nasional);
                $( row ).find('td:eq(3)').attr('style','text-align:center');
                $( row ).find('td:eq(4)').html(Lokal);
                $( row ).find('td:eq(4)').attr('style','text-align:center');
                $( row ).find('td:eq(5)').html(data[10]);
                $( row ).find('td:eq(6)').html(data[11]);
                $( row ).find('td:eq(7)').html('Start : '+data[12]+'<br/>'+'End : '+data[8]);

                var File = jQuery.parseJSON(data[7]);
                var html = data[6]+'</br><a href = "'+base_url_js+'fileGetAny/cooperation-'+File[0]+'" target="_blank" class = "Fileexist">Attachment</a>';
                $( row ).find('td:eq(8)').html(html);
                $( row ).find('td:eq(9)').html(data[8]);
                 $( row ).find('td:eq(10)').html(data[9]);
            },
            dom: 'l<"toolbar">frtip',
            "initComplete": function(settings, json) {
                passToExcel = json.queryPass;
            }
        });
    }

</script>