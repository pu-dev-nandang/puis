<h3 align="center">Regkonisi Dosen dan Karya Ilmiah</h3><br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
<div class="well">
    <div class="row">
        <div class="col-md-12">
            <div style="text-align: right;">
                <button onclick="saveTable2Excel('dataTable2Excel')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 10px;">
      <div class="col-md-4 col-md-offset-4">
        <div class="form-group">
          <label>Tahun</label>
          <select class="form-control" id = "filterTahun">
            
          </select>
        </div>
      </div>
    </div>
    <div class="row" >
        <div class="col-md-12">
            <div id="viewTable"></div>
        </div>
    </div>
</div>                      
<script>
$(document).ready(function () {
  LoadTahun();
    var firstLoad = setInterval(function () {
        var filterProdi = $('#filterProdi').val();
        var filterTahun = $('#filterTahun').val();
        if(filterProdi!='' && filterProdi!=null && filterTahun!='' && filterTahun!=null){
            loadPage();
            clearInterval(firstLoad);
        }
    },1000);
    setTimeout(function () {
        clearInterval(firstLoad);
    },5000);

});

function LoadTahun(){
  var selector = $('#filterTahun');
  selector.empty();
  var StartTahun = 2014;
  var EndTahun = <?php echo date('Y') ?>;
  for (var i = StartTahun; i <= EndTahun; i++) {
    var selected = (i==EndTahun) ? 'selected' : '';
    selector.append(
        '<option value = "'+i+'" '+selected+' >'+i+'</option>'
      );
  }
}  

$('#filterProdi,#filterTahun').change(function () {
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
    var filterTahun = $('#filterTahun option:selected').val();
    var data = {
        auth : 's3Cr3T-G4N',
        mode : 'RekognisiDosenKaryaIlmiah',
        ProdiID : ProdiID,
        filterTahun : filterTahun,
    };
    var token = jwt_encode(data,"UAP)(*");
    var url = base_url_js+"rest3/__get_APS_CrudAgregatorTB3";
    $.post(url,{token:token},function (jsonResult) {
        var selector = $('#viewTable');
         var header = jsonResult.header;
         var html = '';
         var html = '<table class = "table table-bordered dataTable2Excel" id = "dataTablesRekognisiDosen" data-name="TblRekognisiDosen">'+
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

        var selector = $('#dataTablesRekognisiDosen tbody');
        var body = jsonResult.body;
        if (body.length > 0) {
          // var BodyGrouping = __BodyGrouping(body);
          var BodyGrouping = body;
          // console.log(BodyGrouping);
            for (var i = 0; i < BodyGrouping.length; i++) {
                  var t = '<tr>';
                  var arr = BodyGrouping[i];
                  var NoObj = arr[0];
                  var StrTr = '';
                  var temp_arr_str = [];
                  if (NoObj["rowspan"] !== undefined) {
                    for (var j = 0; j < arr.length; j++) {
                      var Obj = arr[j];
                      if (j>=2 && j<=5) { // arr
                        var wrObj = (Obj[0] !== undefined) ? Obj[0] : '';
                        t+= '<td>'+wrObj+'</td>';
                        if (Obj.length > 1) {
                          var tt_arr = [];
                          for (var y = 1; y < Obj.length; y++) {
                            var wrObj2 = (Obj[y] !== undefined) ? Obj[y] : '';
                            tt_arr.push(Obj[y]);
                             // StrTr+= '<td>'+wrObj2+'</td>';
                          }

                          temp_arr_str.push(tt_arr);
                        }
                      }
                      else
                      {
                         t+= '<td rowspan = "'+Obj['rowspan']+'">'+Obj['value']+'</td>';
                      }
                    }

                  }
                  else
                  {
                    for (var j = 0; j < arr.length; j++) {
                        t+= '<td>'+arr[j]+'</td>'; 
                    }
                  }

                  t += '</tr>';
                  console.log(temp_arr_str);
                  if (temp_arr_str.length > 0) {
                    var countLength = temp_arr_str[0].length;
                    for (var j = 0; j < countLength; j++) {
                      var tdValue = '';
                      for (var col = 0; col < 4; col++) {
                        tdValue += '<td>'+temp_arr_str[col][j]+'</td>';
                      }
                      StrTr += '<tr>'+
                                  tdValue+
                               '</tr>';   
                    }
                  }
                  
                  if (StrTr != '') {
                    t += StrTr;
                  }
                  selector.append(t);   
            }

            // for (var i = 0; i < body.length; i++) {
            //       var t = '<tr>';
            //       var arr = body[i];
            //       for (var j = 0; j < arr.length; j++) {
            //             t+= '<td>'+arr[j]+'</td>'; 
            //       }

            //       t += '</tr>';
            //       selector.append(t);   
            // }
        }
        else
        {
            selector.append('<tr><th colspan="9">No data found in the server</th></tr>');
        }
        
    });

}
/*
function __BodyGrouping(arr){
  // console.log(arr);
  var rs = [];
  var No = 1;
  var arr_skip = [];
  for (var i = 0; i < arr.length; i++) {

    // find arr_skip
    var Skip = false;
    for (var y = 0; y < arr_skip.length; y++) {
      if (i ==arr_skip[y] ) {
        Skip = true;
        break;
      }
    }

    if (Skip) {
      continue;
    }

    var arr_rs = [];
    var dt = arr[i];
    var NameDosen1 = dt[1];
    var Tahun1 = dt[6];
    var Judul1 = dt[7];
    var JmlSitasi1 = dt[8];
    var rowspan = 1;
    var arr_same =[];
    for (var j = i+1; j < arr.length; j++) {
       var dt2 = arr[j];
       var NameDosen2 = dt2[1];
       var Tahun2 = dt2[6];
       var Judul2 = dt2[7];
       var JmlSitasi2 = dt2[8];
       if (NameDosen1 != NameDosen2) {
        break;
       }
       else
       {
        if (Tahun1 == Tahun2 && Judul1 == Judul2  && JmlSitasi1  == JmlSitasi2 ) {
          arr_same.push(dt2);
          arr_skip.push(j);
          rowspan++;
        }

       }
    } // end loop j

    if (arr_same.length>0) {
     // temukan arr key 2 sampe 5
     var temp = {
      rowspan : rowspan,
      value : No,
     }

     arr_rs.push(temp)

     var temp = {
      rowspan : rowspan,
      value : NameDosen1,
     }

     arr_rs.push(temp);

     // 2
     var arr_temp2 = [];
     var arr_temp3 = [];
     var arr_temp4 = [];
     var arr_temp5 = [];
     arr_temp2.push(dt[2]);
     arr_temp3.push(dt[3]);
     arr_temp4.push(dt[4]);
     arr_temp5.push(dt[5]);

     for (var z = 0; z < arr_same.length; z++) {
       var dt_same = arr_same[z];
       arr_temp2.push(dt_same[2]);
       arr_temp3.push(dt_same[3]);
       arr_temp4.push(dt_same[4]);
       arr_temp5.push(dt_same[5]);
     }

     arr_rs.push(arr_temp2);
     arr_rs.push(arr_temp3);
     arr_rs.push(arr_temp4);
     arr_rs.push(arr_temp5);

     var temp = {
      rowspan : rowspan,
      value : Tahun1,
     }

     arr_rs.push(temp);

     var temp = {
      rowspan : rowspan,
      value : Judul1,
     }

     arr_rs.push(temp);

     var temp = {
      rowspan : rowspan,
      value : JmlSitasi1,
     }

     arr_rs.push(temp);

    } // end if
    else
    {
      dt[0] = No;
      arr_rs = dt;
    }

    rs.push(arr_rs);
    No++;

  } // end loop i
  // console.log(rs);
  return rs;
}*/
</script>