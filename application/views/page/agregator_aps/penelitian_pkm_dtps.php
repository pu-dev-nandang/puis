<h3 align="center">Penelitian dan PkM DTPS</h3><br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<div class="well">
    <div class="row">
        <div class="col-md-12">
            <div style="text-align: right;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
            <br/>
            <div id="viewTable"></div>
        </div>
    </div>
</div>                    
                    
<script>
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
        if(filterProdi!='' && filterProdi!=null){
            $('#viewProdiID').html(filterProdi);
            $('#viewProdiName').html($('#filterProdi option:selected').text());

            LoadTableData(filterProdi);
        }
    }

    function LoadTableData(filterProdi)
    {
        var P = filterProdi.split('.');
        var ProdiID = P[0];
        var data = {
            auth : 's3Cr3T-G4N',
            mode : 'JudulPenelitian&JudulPKM',
            ProdiID : ProdiID,
        };
        var token = jwt_encode(data,"UAP)(*");
        var url = base_url_js+"rest3/__get_APS_CrudAgregatorTB3";
        $.post(url,{token:token},function (jsonResult) {
             var selector = $('#viewTable');
             var header = jsonResult.header;
             var html = '';
             var html = '<table class = "table table-bordered dataTable2Excel" id = "dataTablesEWMP" data-name="TblEWMP">'+
                            '<thead>'+
                                '<tr>';
             for (var i = 0; i < header.length; i++) {
                 html += '<th rowspan = "'+header[i].rowspan+'" colspan = "'+header[i].colspan+'">'+header[i].Name+'</th>';
             }

             html += '</tr>';
             html += '<tr>';

             for (var i = 0; i < header.length; i++) {
                 if (header[i].colspan > 1) {
                    var Sub = header[i].Sub;
                    for (var k = 0; k < Sub.length; k++) {
                       html += '<th>'+Sub[k]+'</th>'; 
                    }
                 }
             }

             html += '</tr>';
             html += '</thead>'+
                     '<tbody></tbody></table>';

            selector.html(html);  

            var selector = $('#dataTablesEWMP tbody');
            var body = jsonResult.body;
            var arr_total_row = [];
            for (var i = 0; i < body.length; i++) {
               var t = '<tr>';
               var arr = body[i];
               for (var j = 0; j < arr.length; j++) {
                   var dt = arr[j].data;
                   if (Array.isArray(dt)) {
                         t+= '<td>'+arr[j].show+'</td>';
                   }
                   else
                   {
                    // using a href
                    t+= '<td><a href = "javascript:void(0);" class = "datadetail" data = "'+arr[j].data+'">'+arr[j].show+'</a></td>';
                   }

                   if (j>=2) {
                        var ind = j - 2;
                        if (arr_total_row[ind] != undefined) {
                           arr_total_row[ind] += parseInt(arr[j].show); 
                        }
                        else
                        {
                            arr_total_row[ind] = parseInt(arr[j].show);
                        }
                   }
               }

               t += '</tr>';
               selector.append(t);
            }

            // console.log(arr_total_row);

            var selector = $('#dataTablesEWMP');
            var t = '<tfoot>'+
                        '<tr>'+
                            '<td colspan = "2" style = "font-weight: bold;">Jumlah</td>';
            for (var i = 0; i < arr_total_row.length; i++) {
                t+= '<td>'+arr_total_row[i]+'</td>';
            }

            t+= '</tr></tfoot>';

            selector.append(t);                

        });        
    }

    $(document).off('click', '.datadetail').on('click', '.datadetail',function(e) {
        var v = parseInt($(this).html());
        if (v > 0) {
            var dt = $(this).attr('data');
            dt = jwt_decode(dt);
            // console.log(dt);
            var html =  '<div class = "row">'+
                            '<div class = "col-md-12">'+
                                '<table class = "table">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<td>No</td>'+
                                            '<td>Name</td>'+
                                            '<td>Judul</td>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody>';
                    if (dt.length > 0) {
                        for (var i = 0; i < dt.length; i++) {
                            var Judul = (dt[i]["Judul_litabmas"] !== undefined) ? dt[i]["Judul_litabmas"] : dt[i]["Judul_PKM"]
                            html += '<tr>'+
                                        '<td>'+ (parseInt(i)+1) + '</td>'+
                                        '<td>'+ dt[i].Name + '</td>'+
                                        '<td>'+ Judul + '</td>'+
                                    '</tr>';
                        }
                    }
                    else
                    {
                        html += '<tr>'+
                                    '<td colspan="4"><label>No Data Detail</label></td>'+
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
</script>