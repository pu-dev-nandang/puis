<?php 
$Division = $this->session->userdata('PositionMain')['IDDivision'];
?>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i><?php echo $NameMenu ?></h4>
            </div>
            <div class="widget-content">
                <div class = 'row'>
                  <div class="col-md-12">
                      <div class="form-horizontal" role="form">
                          <div class="form-group">
                            <label for="inputType" class="col-md-1 control-label">Angkatan</label>
                            <div class="col-md-3">
                                <select class="select2-select-00 col-md-4 full-width-fix" id="selectTahun">
                                    <option></option>
                                </select>
                            </div>
                            <?php if ($Division == 12): // IT ?>
                                <label for="inputType" class="col-md-1 control-label">Division</label>
                                <div class="col-md-3">
                                    <select class="select2-select-00 col-md-4 full-width-fix" id="Division">
                                        <option></option>
                                    </select>
                                </div>
                            <?php endif ?>
                          </div>
                          <div class="form-group">
                              <span class="col-md-1 control-label">Code Number</span>
                              <div class="col-md-6">
                                  <div class="form-group row">
                                      <label for="inputKey" class="col-md-1 control-label">Start</label>
                                      <div class="col-md-2">
                                          <input type="number" class="form-control" id="Start" min = "1">
                                      </div>
                                      <label for="inputValue" class="col-md-1 control-label">End</label>
                                      <div class="col-md-2">
                                          <input type="number" class="form-control" id="End" min = "1">
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group" style="margin-left: 10px">
                            <div class="col-xs-2 col-md-1">
                                <button class="btn btn-inverse btn-notification btn-add" id="generate">Generate Formulir Code</button>
                            </div>
                          </div>
                      </div>
                  </div>
                </div>
                </hr>
                <div class="row">
                    <div class="col-md-12">
                        <div id = "pageContent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        loadTahun();
        loadData();

        $("#generate").click(function(){
            loading_button('#generate');
            var Angkatan = $("#selectTahun").val();
            //var prefix = $("#prefix").val();
            var Start = $("#Start").val();
            var End = $("#End").val();
            <?php if ($Division == 12): ?>
                var division = $('#Division').val();
            <?php else: ?>
                var division = <?php echo $Division ?>;
            <?php endif ?>
            var data = {
                Angkatan : Angkatan,
                // prefix : prefix,
                Start : Start,
                End : End,
                division : division,
            };
            var token = jwt_encode(data,"UAP)(*");
            var url = base_url_js+'admission/master/generate_formulir_global';
            if (Start != '' && End != '') {
                $.post(url,{token:token},function (data_json) {
                    loadData();
                    $('#generate').prop('disabled',false).html('Generate Formulir Code');  
                });
            }
            else
            {
                toastr.error('Code Number Required','!Failed');
                $('#generate').prop('disabled',false).html('Generate Formulir Code');  
            }
            

        })



    });

    function loadTahun()
    {
      var thisYear = (new Date()).getFullYear();
      var startTahun = parseInt(thisYear);
      var selisih = (2018 < parseInt(thisYear)) ? parseInt(1) + (parseInt(thisYear) - parseInt(2018)) : 1;
      for (var i = 0; i <= selisih; i++) {
          var selected = (i==1) ? 'selected' : '';
          $('#selectTahun').append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
      }

      $('#selectTahun').select2({
        // allowClear: true
      });

    }

    function loadData()
    {
        loading_page('#pageContent');
        <?php if ($Division == 12): ?>
            $("#Division").empty();
            var url = base_url_js+"rest/__getTableData/db_employees/division";
              var data = {
                              auth : 's3Cr3T-G4N',
                         };
              var token = jwt_encode(data,"UAP)(*");
              $.post(url,{token:token},function (resultJson) {
                var OpDiv = resultJson;
                   for (var i = 0; i < OpDiv.length; i++) {
                     var selected = (i==9) ? 'selected' : '';
                     $('#Division').append('<option value="'+ OpDiv[i].ID +'" '+selected+'>'+OpDiv[i].Division+'</option>');
                   }
                   $('#Division').select2({
                     // allowClear: true
                   });
                   var division = $('#Division').val();
                   var selectTahun = $("#selectTahun").val();
                   var url = base_url_js+'rest/__loadDataFormulirGlobal';
                   var data = {
                       selectTahun : selectTahun,
                       auth : 's3Cr3T-G4N',
                       division : division,
                   };
                   var token = jwt_encode(data,"UAP)(*");
                   $.post(url,{token:token},function (data_json) {
                       var html = '<div class = "table-responsive"><table class="table table-striped table-bordered table-hover table-checkable datatable">'+
                                       '<thead>'+
                                           '<tr>'+
                                               '<th style="width: 15px;">No</th>'+
                                               '<th>Angkatan</th>'+
                                               '<th>Formulir Code</th>'+
                                               '<th>Formulir Code Link</th>'+
                                               '<th>Status</th>'+
                                               '<th>Division</th>'+
                                           '</tr>'+
                                       '</thead>'+
                                       '<tbody>'+
                                       '</tbody>'+
                                   '</table></div>';
                       setTimeout(function () {
                          $("#pageContent").empty();
                          $("#pageContent").html(html);
                          for (var i = 0; i < data_json.length; i++) {
                              var no = i+1;
                               var status = '<td style="'+
                                               'color:  green;'+
                                               '">IN'+
                                             '</td>';
                               if (data_json[i]['Status'] == 1 ) {
                                   status = '<td style="'+
                                               'color:  red;'+
                                               '">Sold Out'+
                                             '</td>';
                               }
                              $(".datatable tbody").append(
                               '<tr>'+
                                   '<td>'+no+'</td>'+
                                   '<td>'+data_json[i]['Years']+'</td>'+
                                   '<td>'+data_json[i]['FormulirCodeGlobal']+'</td>'+
                                   '<td>'+data_json[i]['FormulirCode']+'</td>'+
                                   status+
                                   '<td>'+''+'</td>'+
                               '</tr>' 
                               );
                              no++;
                          }
                          LoaddataTable('.datatable');
                       },500);
                   });
             })
            
        <?php else: ?>
            var division = <?php echo $Division ?>;
            var selectTahun = $("#selectTahun").val();
            var url = base_url_js+'rest/__loadDataFormulirGlobal';
            var data = {
                selectTahun : selectTahun,
                auth : 's3Cr3T-G4N',
                division : division,
            };
            var token = jwt_encode(data,"UAP)(*");
            $.post(url,{token:token},function (data_json) {
                var html = '<div class = "table-responsive"><table class="table table-striped table-bordered table-hover table-checkable datatable">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th style="width: 15px;">No</th>'+
                                        '<th>Angkatan</th>'+
                                        '<th>Formulir Code</th>'+
                                        '<th>Formulir Code Link</th>'+
                                        '<th>Status</th>'+
                                        '<th>Division</th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody>'+
                                '</tbody>'+
                            '</table></div>';
                setTimeout(function () {
                   $("#pageContent").empty(); 
                   $("#pageContent").html(html);
                   for (var i = 0; i < data_json.length; i++) {
                       var no = i+1;
                        var status = '<td style="'+
                                        'color:  green;'+
                                        '">IN'+
                                      '</td>';
                        if (data_json[i]['Status'] == 1 ) {
                            status = '<td style="'+
                                        'color:  red;'+
                                        '">Sold Out'+
                                      '</td>';
                        }
                       $(".datatable tbody").append(
                        '<tr>'+
                            '<td>'+no+'</td>'+
                            '<td>'+data_json[i]['Years']+'</td>'+
                            '<td>'+data_json[i]['FormulirCodeGlobal']+'</td>'+
                            '<td>'+data_json[i]['FormulirCode']+'</td>'+
                            status+
                           '<td>'+''+'</td>'+
                        '</tr>' 
                        );
                       no++;
                   }
                   LoaddataTable('.datatable');
                },500);
            });    
        <?php endif ?>
    }
</script>