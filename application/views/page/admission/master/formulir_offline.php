<style>
    .row-sma {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .form-time {
        padding-left: 0px;
        padding-right: 0px;
    }
    .row-sma .fa-plus-circle {
        color: green;
    }
    .row-sma .fa-minus-circle {
        color: red;
    }
    .btn-action {

        text-align: right;
    }

    #tableDetailTahun thead th {
        text-align: center;
    }

    .form-filter {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #ccc;
    }
    .filter-time {
        padding-left: 0px;
    }
</style>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-12 formAddFormKD">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> <?php echo $NameMenu ?></h4>
            </div>
            <div class="widget-content">
                <!--  -->
                <div class="row row-sma">
                    <label class="col-sm-1 control-label">Angkatan</label>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-3">
                                <select class="select2-select-00 col-md-4 full-width-fix" id="selectTahun">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <button class="btn btn-inverse btn-notification btn-add" id="generate">Generate Formulir Code</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-sma">
                    <label class="col-sm-1 control-label">Qty</label>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-1">
                                <select class="select2-select-00 col-md-4 full-width-fix" id="selectqty">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div id="pageData">
                    
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        loadTahun();
        loadNumberFormulir();
        loadQty();
    });

    $(document).on('click','#generate', function () {
       var selectTahun = $("#selectTahun").val();
       var selectQty = $("#selectqty").val();
       processGenerate(selectTahun,selectQty);
    });

    $(document).on('change','#selectTahun',function () {
        loadNumberFormulir();
    });

    function processGenerate(tahun,qty)
    {
        loading_button('#generate');
        var url = base_url_js+'admission/master-registration/GenerateFormulirOffline';
        var data = {
            selectTahun : tahun,
            qty : qty
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{token:token},function (data_json) {
            setTimeout(function () {
             loadNumberFormulir();
             $('#generate').prop('disabled',false).html('Generate Formulir Code');  
            },500);
        });
    }

    function loadTahun()
    {
      var academic_year_admission = "<?php echo $academic_year_admission ?>";
      var thisYear = (new Date()).getFullYear();
      var startTahun = parseInt(thisYear);
      var selisih = (2018 < parseInt(thisYear)) ? parseInt(1) + (parseInt(thisYear) - parseInt(2018)) : 1;
      for (var i = 0; i <= selisih; i++) {
          var selected = (( parseInt(startTahun) + parseInt(i) )==academic_year_admission) ? 'selected' : '';
          $('#selectTahun').append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
      }

      $('#selectTahun').select2({
        // allowClear: true
      });

    }

    function loadQty()
    {
        $('#selectqty').empty();
        var totaldata = "<?php echo $totalData ?>";
        totaldata = parseInt(totaldata);
        for (var i = 5; i <= totaldata; i =  i + 5) {
            var selected = (i==0) ? 'selected' : '';
            $('#selectqty').append('<option value="'+ i +'" '+selected+'>'+i+'</option>');
        }
        $('#selectqty').select2({
          // allowClear: true
        });
    }

    function loadNumberFormulir()
    {
        $("#pageData").empty();
        loading_page('#pageData');
        var selectTahun = $("#selectTahun").val();
        var url = base_url_js+'admission/master-registration/loadDataFormulirOffline';
        var data = {
            selectTahun : selectTahun
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{token:token},function (data_json) {
            setTimeout(function () {
               $("#pageData").html(data_json);
            },500);
        });
    }

    $(document).on('click','.btn-print', function () {
       var formulir_code = $(this).attr('data-smt');
       var url_token = $(this).attr('data-token');
       var url = base_url_js+'admission/master-registration/DataFormulirOffline/downloadPDFToken';
       var data = {
         formulir_code : formulir_code,
         url_token : url_token
       };
       var token = jwt_encode(data,"UAP)(*");
       FormSubmitAuto(url, 'POST', [
           { name: 'token', value: token },
       ]);
    });
</script>