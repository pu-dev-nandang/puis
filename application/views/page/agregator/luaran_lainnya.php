
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
            <p style="color:#3968c6;"><b> Buku Ber-ISBN, Book Chapter </b></p>
            <div id="viewTable"></div>
        </div>

    </div>

</div>

<script>

    $(document).ready(function () {
        loadAkreditasiProdi();
    });

    function loadAkreditasiProdi() {


         $('#viewTable').html(' <table class="table table-bordered" id="dataTablesLuaran">' +
            '    <thead>  '+
            '     <tr>   '+
            '        <th style="text-align: center; width: 5%;">No</th>  '+
            '        <th style="text-align: center;">Luaran Penelitian dan PkM</th>  '+
            '        <th style="text-align: center; width: 15%;">Tahun Perolehan (YYYY)</th>  '+
            '        <th style="text-align: center;">Keterangan</th>  '+
            '    </tr>  '+
            '    </thead>  '+
            '       <tbody id="listData"></tbody>   '+
            //'    <tfoot id="listDataFoot">  </tfoot>'+
            '    </tfoot> '+
            '    </table>');

        var url = base_url_js+'api3/__getLuaranlainnya';
        $.getJSON(url,function (jsonResult) {

            if(jsonResult.length>0) {

                for (var i = 0; i < jsonResult.length; i++) {
                    var v = jsonResult[i]; 
                    var tahun = moment(v.Tgl_terbit).format('YYYY');

                    $('#listData').append('<tr>' +
                        '   <td style="text-align: center;">'+(i+1)+'</td>' +
                        '   <td style="text-align: left;">'+v.Judul+'</td>' +
                        '   <td style="text-align: center;">'+tahun+'</td>' +
                        '   <td style="text-align: left;">'+v.Ket+'</td>' +
                        '</tr>');

                    var total = parseInt(jsonResult.length);
                }

            }
                
             $('#dataTablesLuaran').dataTable();

        });

    }

    function hitungRow(cl) {

        var res = 0;
        $(cl).each(function () {
            res += parseInt($(this).attr('data-val'));
        });

        return res;
    }

</script>