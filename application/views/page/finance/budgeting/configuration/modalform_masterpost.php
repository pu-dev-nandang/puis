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
                <div class="col-sm-4">
                    <label class="control-label">Post Code:</label>
                </div>
                <div class="col-sm-6">
                  <strong>Code Automatic</strong>
                </div>    
            </div>
            <div class="row">
              <div class="col-md-3 col-md-offset-4">
                <input type="checkbox" class="NeedPrefix" name="NeedPrefix" value="0">&nbsp; No
                <input type="checkbox" class="NeedPrefix" name="NeedPrefix" value="1">&nbsp; Yes
              </div>
            </div>
            <div class="row hide" id = "rowCodePost">
              <div class="col-md-6 col-md-offset-4">
                <input type="text" name="CodePost" id= "CodePost" placeholder="Code" class="form-control">
              </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Post Name:</label>
                </div>    
                <div class="col-sm-6">
                   <input type="text" name="PostName" id= "PostName" placeholder="PostName" class="form-control">
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
        $(".NeedPrefix").change(function(){
          var valuee = $(this).val();
          if(valuee == 0)
          {
            $("#rowCodePost").removeClass('hide');
          }
          else
          {
            $("#rowCodePost").addClass('hide');
          }
        })

        $(".NeedPrefix").click(function(){
            $('input.NeedPrefix').prop('checked', false);
            $(this).prop('checked',true);
        });

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