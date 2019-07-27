
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

            <table class="table" id="tableData">
                <thead>
                <tr>
                    <th style="width: 1%" rowspan="3">No</th>
                    <th rowspan="3">Status & Peringkat Akreditasi</th>
                    <th colspan="12">Jumlah Program Studi</th>
                    <th style="width: 5%;" rowspan="3">Jumlah</th>
                </tr>
                <tr>
                    <th colspan="3" style="border-right: 1px solid #ccc;">Akademik</th>
                    <th colspan="3" style="border-right: 1px solid #ccc;">Profesi</th>
                    <th colspan="6">Vokasi</th>
                </tr>
                <tr>
                    <th style="width: 5%;">S-3</th>
                    <th style="width: 5%;">S-2</th>
                    <th style="width: 5%;border-right: 1px solid #ccc;">S-1</th>
                    <th style="width: 5%;">Sp-2</th>
                    <th style="width: 5%;">Sp-1</th>
                    <th style="width: 5%;border-right: 1px solid #ccc;">Profesi</th>
                    <th style="width: 5%;">S-3T</th>
                    <th style="width: 5%;">S-2T</th>
                    <th style="width: 5%;">D-4</th>
                    <th style="width: 5%;">D-3</th>
                    <th style="width: 5%;">D-2</th>
                    <th style="width: 5%;">D-1</th>
                </tr>
                </thead>
                <tbody id="listData"></tbody>
            </table>

        </div>

    </div>

</div>

<script>

    $(document).ready(function () {
        loadAkreditasiProdi();
    });

    function loadAkreditasiProdi() {

        var url = base_url_js+'api3/__getAkreditasiProdi';
        $.getJSON(url,function (jsonResult) {

            if(jsonResult.length>0){

                var arr_jml = [];

                $.each(jsonResult,function (i,v) {

                    var td = '';
                    var total = 0;
                    $.each(v.Details,function (i2,v2) {

                        var c = (parseInt(v2.Prodi)>0) ? 'td-av' : '';
                        total = total + parseInt(v2.Prodi);
                        td = td+'<td  style="border-right: 1px solid #ccc;" class="'+c+' cl_'+i2+'" data-val="'+v2.Prodi+'">'+v2.Prodi+'</td>';


                    });

                   $('#listData').append('<tr>' +
                       '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                       '<td style="text-align: left;">'+v.Label+'</td>' +
                       td+'<td class="higligh">'+total+'</td>' +
                       '</tr>');
                });


                $('#listData').append('<tr class="higligh">' +
                    '<th colspan="2">Jumlah</th>' +
                    '<th>'+hitungRow('.cl_0')+'</th>' +
                    '<th>'+hitungRow('.cl_1')+'</th>' +
                    '<th>'+hitungRow('.cl_2')+'</th>' +
                    '<th>'+hitungRow('.cl_3')+'</th>' +
                    '<th>'+hitungRow('.cl_4')+'</th>' +
                    '<th>'+hitungRow('.cl_5')+'</th>' +
                    '<th>'+hitungRow('.cl_6')+'</th>' +
                    '<th>'+hitungRow('.cl_7')+'</th>' +
                    '<th>'+hitungRow('.cl_8')+'</th>' +
                    '<th>'+hitungRow('.cl_9')+'</th>' +
                    '<th>'+hitungRow('.cl_10')+'</th>' +
                    '<th>'+hitungRow('.cl_11')+'</th>' +
                    '<th>'+hitungRow('.cl_12')+'</th>' +
                    '</tr>');
            }

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