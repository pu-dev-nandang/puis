<style type="text/css">
    button, input, select, textarea {
        /*margin: 7px;*/
        font-family: inherit;
        font-size: 100%;
    }
</style>
    <form class="form-horizontal" id="formModal">
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 hide" align = 'center' id='msgMENU' style="color: red;">MSG</div>
            </div>   
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Year:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="Year">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 hide" align = 'center' id='msgMENU' style="color: red;">MSG</div>
            </div>   
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Start Period:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix bulan" id="MonthStart">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">End Period :</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix bulan" id="MonthEnd">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
        <div style="text-align: center;">       
    		<div class="col-sm-12" id="BtnFooter">
                <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>
                <button type="button" id="ModalbtnSaveForm" class="btn btn-success" action = "<?php echo $action ?>" kodeuniq = "<?php echo $id ?>">Save</button>
    		</div>
        </div>    
    </form>
<script type="text/javascript">
    $(document).ready(function () {
        loadYear();
        loadMonth();
    });

    function loadYear()
    {
      var thisYear = (new Date()).getFullYear();
      var startTahun = parseInt(thisYear);
      var selisih = (2018 < parseInt(thisYear)) ? parseInt(5) + (parseInt(thisYear) - parseInt(2018)) : 5;
      for (var i = 0; i <= selisih; i++) {
          var selected = (i==0) ? 'selected' : '';
          $('#Year').append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
      }

    }

    function loadMonth()
    {
      var month = {
        01 : 'Jan',
        02 : 'Feb',
        03 : 'Mar',
        04 : 'April',
        05 : 'Mei',
        06 : 'Jun',
        07 : 'Jul',
        08 : 'Aug',
        09 : 'Sep',
        10 : 'Okt',
        11 : 'Nov',
        12 : 'Des'
      }

      for(var key in month) {
        var selected = (key==1) ? 'selected' : '';
        var getKey = key.toString();
        if (getKey.length == 1) {
          var value = '0' + getKey;
        }
        else
        {
          var value = key;
        }
        $('.bulan').append('<option value="'+ value +'" '+selected+'>'+month[key]+'</option>');
      }

      <?php if ($action == 'edit'): ?>
          $("#Year option").filter(function() {
             //may want to use $.trim in here
             return $(this).val() == '<?php echo $getData[0]['Year'] ?>'; 
           }).prop("selected", true);

          var MonthStart = '<?php echo $getData[0]['StartPeriod'] ?>';
          MonthStart = MonthStart.split('-');
          MonthStart = MonthStart[1];

          $("#MonthStart option").filter(function() {
             //may want to use $.trim in here
             return $(this).val() == MonthStart; 
           }).prop("selected", true);

          var MonthEnd = '<?php echo $getData[0]['EndPeriod'] ?>';
          MonthEnd = MonthEnd.split('-');
          MonthEnd = MonthEnd[1];

          $("#MonthEnd option").filter(function() {
             //may want to use $.trim in here
             return $(this).val() == MonthEnd; 
           }).prop("selected", true);

          $('.bulan').select2({
            // allowClear: true
          });

          $('#Year').select2({
            // allowClear: true
          });
      <?php else: ?>
          $("#MonthStart option").filter(function() {
             //may want to use $.trim in here
             return $(this).val() == '09'; 
           }).prop("selected", true);

          $("#MonthEnd option").filter(function() {
             //may want to use $.trim in here
             return $(this).val() == '08'; 
           }).prop("selected", true);

          $('.bulan').select2({
            // allowClear: true
          });

          $('#Year').select2({
            // allowClear: true
          });
      <?php endif ?>
    }
</script>