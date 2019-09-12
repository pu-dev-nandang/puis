
<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }
</style>

<div class="well">

    <div class="row">

        <!-- <div class="col-md-3 form-data-edit" style="border-right: 1px solid #CCCCCC;">
            <div class="form-group">
                <label>Year</label>
                <input class="hide" id="formID">
                <select class="form-control" id="formYear"></select>
            </div>
            <div class="form-group">
                <label>Prodi</label>
                <select class="form-control" id="formProdiID"></select>
            </div>
            <div class="form-group">
                <label>Jumlah Mahasiswa</label>
                <input type="number" class="form-control" id="formTotalStudent">
            </div>
            <div class="form-group" style="text-align: right;">
                <button class="btn btn-primary btn-round" id="btnSave">Save</button>
            </div>

        </div> -->
        <div class="col-md-12">
            <div style="text-align: right;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
                <!-- <button id="saveToExcel" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button> -->
            </div>
            <div id = "content_dt">
                
            </div>
        </div>
    </div>

<script>
    $(document).ready(function () {
        var firstLoad = setInterval(function () {

            loadDataTable();
            clearInterval(firstLoad);

        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);

    });

    $("#btndownloaadExcel").click(function(){
        var akred = "0";

        var url = base_url_js+'agregator/excel-mahasiswa-asing';
        data = {
          akred : akred
        }
        var token = jwt_encode(data,"UAP)(*");
        FormSubmitAuto(url, 'POST', [
            { name: 'token', value: token },
        ]);
    })

    $('#filterYear').change(function () {
        loadDataTable();

    });

    function selectyearstudy() {

        var url = base_url_js+'api3/__crudAgregatorTB5';
        var token = jwt_encode({action : 'yearstudy'},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            $('#formYear').append('<option disabled selected></option>');
                for(var i=0;i<jsonResult.length;i++){
                   $('#formYear').append('<option id="'+jsonResult[i].Year+'"> '+jsonResult[i].Year+' </option>');
                }
            });
      }


    function loadDataTable() {
        var data = {
            action : 'readDataMHSBaruAsing',
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB2';

        $.post(url,{token:token},function (jsonResult) {
            // arr_header_table
            var arr_header_table = jsonResult.header;
            var arr_total = [];
            // make table
            var htmlTable = '<table class = "table dataTable2Excel" data-name="TblMhsAsing">'+
                                '<thead>'+
                                    '<tr>';
            for (var i = 0; i < arr_header_table.length; i++) {
                htmlTable += '<td>'+arr_header_table[i]+'</td>';
                if (i > 1) {
                    arr_total.push(0);
                }
            }

            htmlTable += '</tr>'+
                        '</thead>'+
                        '<tbody id="listStd"></tbody>'+
                        '</table>';
            $('#content_dt').html(htmlTable);            
            $('#listStd').empty();

            var arr_body_table = jsonResult.body;
            // console.log(arr_body_table);
            if(arr_body_table.length>0){
                for (var i = 0; i < arr_body_table.length; i++) {
                    var No = parseInt(i) + 1;
                    var htmlTableBody = '<tr>'+
                                            '<td>'+No+'</td>';
                    var arr = arr_body_table[i];
                    for(var key in arr) {
                        htmlTableBody += '<td>'+arr[key]+'</td>';
                    }

                    for (var m = 1; m < arr.length; m++) {
                        // console.log(m + ' ,,' +arr[m]);
                       arr_total[(m-1)] = parseInt(arr_total[(m-1)]) + parseInt(arr[m]);
                    }

                    htmlTableBody += '</tr>';

                    $('#listStd').append(htmlTableBody);
                }
                // console.log(arr_total);
                var tbl = $('#listStd').closest('table');
                var isian = '';
                for (var i = 0; i < arr_total.length; i++) {
                    isian += '<td>'+arr_total[i]+'</td>';
                }
                tbl.append(
                    '<tfoot>'+
                        '<tr>'+
                            '<td colspan = "2">Jumlah</td>'+
                            isian+
                        '</tr>'+
                    '</tfoot>'        
                    );


            } else {
                $('#listStd').append('<tr><td colspan="5">Data not yet</td></tr>');
            }

        });

    }
</script>