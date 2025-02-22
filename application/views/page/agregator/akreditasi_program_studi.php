
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
            <div style="text-align: right"> <b>Download File : </b><button class="btn btn-success btn-circle" id="btndownloaadExcel" title="Dowload Excel"><i class="fa fa-file-excel-o"></i> </button></div>
            <div id = "content_data">

            </div>
        </div>
  </div>

</div>

<script>
    var passToExcel = [];
    $(document).ready(function () {
        loadAkreditasiProdi();
        newDescritionInput.getDescription();
    });

    function __MakeHtmlTable(header)
    {
        var html = '';
        var totalCol = 1; // tambah total
        for (var i = 0; i < header.length; i++) {
            var Detail = header[i].Detail;
            var tot = Detail.length;
            totalCol += tot;
        }

        html += '<table class = "table" id="tableData"> '+
                    '<thead>'+
                        '<tr>'+
                            '<th rowspan = "3"  style="vertical-align : middle;text-align:center;" >No</th>'+
                            '<th rowspan = "3"  style="vertical-align : middle;text-align:center;" >Status & Peringkat Akreditasi</th>'+
                            '<th colspan="'+totalCol+'" style="vertical-align : middle;text-align:center;" >Jumlah Program Studi</th>'+
                        '</tr>'+
                        '<tr>';


        for (var i = 0; i < header.length; i++) {
           var Type = header[i].Type;
           var ll = header[i].Detail.length;
           html += '<th colspan = "'+ll+'" style="border-right: 1px solid #ccc;">'+Type+'</th>';
        }
        html += '<th rowspan="2" style="border-right: 1px solid #ccc;">'+'Total'+'</th>';
        html += '</tr>'+
                    '<tr>';


        for (var i = 0; i < header.length; i++) {
           var Detail = header[i].Detail;
           for (var j = 0; j < Detail.length; j++) {
               html += '<th style="border-right: 1px solid #ccc;">'+Detail[j].Name+'</th>';
           }

        }

        html += '</tr>';

        html +=  '</thead>'+
                '<tbody id="listData"></tbody>'+
            '</table> ';

        return html;
    }

    function loadAkreditasiProdi() {

        passToExcel = [];
        var url = base_url_js+'api3/__getAkreditasiProdi';
        $.getJSON(url,function (jsonResult) {
            // make table
            var header = jsonResult.header;
            var MakeHtmlTable = __MakeHtmlTable(header);
            $('#content_data').html(MakeHtmlTable);
            // isian table
            var htmlIsiTable = '';
            var fill = jsonResult.fill;
            for (var i = 0; i < fill.length; i++) {
                var No = parseInt(i) + 1;
                var AccreditationName = fill[i].AccreditationName;
                var Total = 0;
                htmlIsiTable += '<tr>'+
                                    '<td>'+No+'</td>'+
                                    '<td>'+AccreditationName+'</td>';
                var TypeProgramStudy = fill[i].TypeProgramStudy;
                for (var j = 0; j < TypeProgramStudy.length; j++) {
                   var Data =  TypeProgramStudy[j].Data
                   for (var k = 0; k < Data.length; k++) {
                       htmlIsiTable += '<td>'+'<a href = "javascript:void(0);" class = "datadetail" data = "'+Data[k].data+'">'+Data[k].Count+'</a>'+'</td>';
                       Total += parseInt(Data[k].Count);
                   }
                }

                htmlIsiTable += '<td>'+Total+'</td>';

                htmlIsiTable += '</tr>';
            }

            $('#listData').append(htmlIsiTable);

            passToExcel =jsonResult;
        });

    }

    $(document).off('click', '#btndownloaadExcel').on('click', '#btndownloaadExcel',function(e) {
        if (passToExcel["header"] != undefined) {
            var header = passToExcel['header'];
            var fill = passToExcel['fill'];
            if (fill.length > 0) {
                // console.log(passToExcel);
                var url = base_url_js+'agregator/excel-akreditasi-program-studi';
                data = {
                  header : header,
                  fill : fill,
                }
                var token = jwt_encode(data,"UAP)(*");
                FormSubmitAuto(url, 'POST', [
                    { name: 'token', value: token },
                ]);
            }
        }

    })

    function hitungRow(cl) {

        var res = 0;
        $(cl).each(function () {
            res += parseInt($(this).attr('data-val'));
        });

        return res;
    }

    $(document).off('click', '.datadetail').on('click', '.datadetail',function(e) {
        var v = parseInt($(this).html());
        if (v > 0) {
            var dt = $(this).attr('data');
            dt = jwt_decode(dt);
            var html =  '<div class = "row">'+
                            '<div class = "col-md-12">'+
                                '<table class = "table">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<td>No</td>'+
                                            '<td>Program Studi</td>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody>';
                    for (var i = 0; i < dt.length; i++) {
                        html += '<tr>'+
                                    '<td>'+ (parseInt(i)+1) + '</td>'+
                                    '<td>'+ dt[i].Name + '</td>'+
                                '</tr>';
                    }

                    html  += '</tbody></table></div></div>';


            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Detail</h4>');
            $('#GlobalModal .modal-body').html(html);
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        }
    })

    $(document).on('click','.btnSaveDescription',function(e){
        const itsme =  $(this);
        newDescritionInput.saveDescription(itsme);
    })

</script>
