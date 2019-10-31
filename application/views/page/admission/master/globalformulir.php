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
                  <div class="col-md-3">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Generate</h4>
                            </div>
                            <div class="panel-body" style="min-height: 100px;">
                                <div class="form-group">
                                  <label>Angkatan</label>
                                 <select class="select2-select-00 col-md-4 full-width-fix" id="selectTahunInput">
                                       <option></option>
                                  </select>
                                </div>
                                <?php if ($Division == 12): // IT ?>
                                  <div class="form-group">
                                    <label>Division</label>
                                   <select class="select2-select-00 col-md-4 full-width-fix" id="Division">
                                       <option></option>
                                   </select>
                                  </div>
                                <?php endif ?>
                                <div class="form-group">
                                  <label>Start</label>
                                  <input type="number" class="form-control" id="Start" min = "1">
                                </div>
                                <div class="form-group">
                                  <label>End</label>
                                  <input type="number" class="form-control" id="End" min = "1">
                                </div>
                                <div class="form-group">
                                  <label>Choose</label>
                                  <select class="form-control" id = "TypeFormulir">
                                    <option value="On">Online</option>
                                    <option value="Off" selected>Offline</option>
                                  </select>
                                </div>
                            </div>
                            <div class="panel-footer" style="text-align: right;">
                                <button class="btn btn-inverse btn-notification btn-add" id="generate">Generate Formulir Code</button>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="row">
                      <div class="col-md-3 col-md-offset-4">
                        <label>Angkatan</label>
                        <select class="select2-select-00 col-md-4 full-width-fix" id="selectTahun">
                            <option></option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div id = "pageContent"></div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  var App_globalformulir = {
    loadSetDefault : function(){
      App_globalformulir.loadTahun();
      App_globalformulir.LoadDepartment();
      $('#Start').val(1);
      $('#End').val(100);
      var firstLoad = setInterval(function () {
          var selectTahun = $('#selectTahun').val();
          if(selectTahun!='' && selectTahun!=null ){
              App_globalformulir.LoadData();
              clearInterval(firstLoad);
          }
      },1000);
      setTimeout(function () {
          clearInterval(firstLoad);
      },2000);
    },
    LoadDepartment : function(){
      var selector = $('#Division');
      selector.empty();
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
      })
    },
    loadTahun : function(){
      var academic_year_admission = "<?php echo $academic_year_admission ?>";
      var thisYear = (new Date()).getFullYear();
      var startTahun = parseInt(thisYear);
      var selisih = (2018 < parseInt(thisYear)) ? parseInt(1) + (parseInt(thisYear) - parseInt(2018)) : 1;
      $('#selectTahunInput').empty();
      $('#selectTahun').empty();
      for (var i = 0; i <= selisih; i++) {
          var selected = (( parseInt(startTahun) + parseInt(i) )==academic_year_admission) ? 'selected' : '';
          $('#selectTahun').append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
          $('#selectTahunInput').append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
      }

      $('#selectTahun').select2({
        // allowClear: true
      });
      $('#selectTahunInput').select2({
        // allowClear: true
      });
    },
    LoadTableData : function(division,selectTahun,){
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
                                  '<th>Type Formulir</th>'+
                              '</tr>'+
                          '</thead>'+
                          '<tbody>'+
                          '</tbody>'+
                      '</table></div>';
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
               var TypeFormulir = (data_json[i]['TypeFormulir'] == null) ?  '' : data_json[i]['TypeFormulir'];
               if (TypeFormulir == 'Off') {
                TypeFormulir = 'Offline';
               }
               else if(TypeFormulir == 'On'){
                TypeFormulir = 'Online';
               }
              $(".datatable tbody").append(
               '<tr>'+
                   '<td>'+no+'</td>'+
                   '<td>'+data_json[i]['Years']+'</td>'+
                   '<td>'+data_json[i]['FormulirCodeGlobal']+'</td>'+
                   '<td>'+((data_json[i]['FormulirCode'] == null) ? '' : data_json[i]['FormulirCode'])+'</td>'+
                   status+
                   '<td>'+data_json[i]['Division']+'</td>'+
                   '<td>'+TypeFormulir+'</td>'+
               '</tr>' 
               );
              no++;
          }
          LoaddataTable('.datatable');
      });
    },
    LoadData : function(){
      loading_page('#pageContent');
      <?php if ($Division == 12): ?>
          var firstLoad = setInterval(function () {
              var selectDivision = $('#Division').val();
              if(selectDivision!='' && selectDivision!=null ){
                  var division = $('#Division option:selected').val();
                  var selectTahun = $("#selectTahun option:selected").val();
                  App_globalformulir.LoadTableData(division,selectTahun);
                  clearInterval(firstLoad);
              }
          },1000);
          setTimeout(function () {
              clearInterval(firstLoad);
          },2000);
      <?php else: ?>
          var division = <?php echo $Division ?>;
          var selectTahun = $("#selectTahun").val();
          App_globalformulir.LoadTableData(division,selectTahun);    
      <?php endif ?>
    },
    loaded : function(){
      App_globalformulir.loadSetDefault();
    },
  };
    $(document).ready(function () {
        App_globalformulir.loaded();

        $("#generate").click(function(){
            if (confirm('Are you sure ?')) {
              loading_button('#generate');
              var Angkatan = $("#selectTahunInput option:selected").val();
              //var prefix = $("#prefix").val();
              var Start = $("#Start").val();
              var End = $("#End").val();
              var TypeFormulir = $('#TypeFormulir option:selected').val();
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
                  TypeFormulir : TypeFormulir,
              };
              var token = jwt_encode(data,"UAP)(*");
              var url = base_url_js+'admission/master/generate_formulir_global';
              if (Start != '' && End != '') {
                  $.post(url,{token:token},function (data_json) {
                      App_globalformulir.LoadData();
                      $('#generate').prop('disabled',false).html('Generate Formulir Code');  
                  });
              }
              else
              {
                  toastr.error('Code Number Required','!Failed');
                  $('#generate').prop('disabled',false).html('Generate Formulir Code');  
              }
            }
        })

        $("#selectTahun").change(function(){
          App_globalformulir.LoadData();
        })

    });
</script>
