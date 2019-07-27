

<style>
    #dataTable tr th, #dataTable tr td {
        text-align: center;
    }
    .tdJml {
        background: lightyellow;
    }
</style>


<div class="well">
    <div class="row">

        <div class="col-md-12">
            <table class="table" id="dataTable">
                <thead>
                <tr>
                    <th rowspan="2" style="width: 1%;">No</th>
                    <th rowspan="2">Program Studi</th>
                    <th colspan="3">Pendidikan Tertinggi</th>
                    <th rowspan="2" style="width: 10%;">Jumlah</th>
                </tr>
                <tr>
                    <th style="width: 10%;">Doktor</th>
                    <th style="width: 10%;">Magister</th>
                    <th style="width: 10%;">Profesi</th>
                </tr>
                </thead>
                <tbody id="listTable"></tbody>
            </table>
        </div>

    </div>
</div>

<script>

    $(document).ready(function () {
        loadKecukupanDosen();
    });

    function loadKecukupanDosen() {
        var url = base_url_js+'api3/__getKecukupanDosen';

        $.getJSON(url,function (jsonResult) {

            $('#listTable').empty();
            if(jsonResult.length>0){
                var p = 0; var m = 0;var d=0; var j=0
                $.each(jsonResult,function (i,v) {

                    var edu = '';
                    var totalLec = 0;
                    $.each(v.dataLecturers,function (i,v) {
                        var det = v.Details.length;
                        totalLec = totalLec + det;
                        edu = edu+'<td>'+det+'</td>';

                        if(i==2){
                            p = p+det
                        }

                        if(i==1){
                            m = m+det
                        }

                        if(i==0){
                            d = d+det
                        }

                    });

                    edu = edu+'<th class="tdJml" style="border-left: 1px solid #CCCCCC;">'+totalLec+'</th>';
                    j = j + totalLec;

                    $('#listTable').append('<tr>' +
                        '<td style="border-right: 1px solid #CCCCCC;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.Code+' - '+v.Name+'</td>'+edu+' ' +
                        '</tr>');
                });

                $('#listTable').append('<tr>' +
                    '<th colspan="2" class="tdJml">Jumlah</th>' +
                    '<th class="tdJml">'+m+'</th>' +
                    '<th class="tdJml">'+d+'</th>' +
                    '<th class="tdJml">'+p+'</th>' +
                    '<th class="tdJml">'+j+'</th>' +
                    '</tr>');
            }

        });
    }
    
</script>