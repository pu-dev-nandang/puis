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
                    <label class="control-label">Pilih Start Rangking:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix selectRangking" id="selectRangking1">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Pilih End Rangking:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix selectRangking" id="selectRangking2">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Pilih Potongan :</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="selectPotongan">
                        <option></option>
                    </select>
                    Dalam Bentuk % (Persen)
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
        loadSelectRangking();
        loadSelectPotongan();
    });

    function loadSelectRangking()
    {
        $(".selectRangking").empty();
        for(var i=1;i<=10;i++){
            var selected = (i==1) ? 'selected' : '';
            //$('#selectWilayah').append('<option value="'+data_json['data'][i].kode_wilayah+'" '+selected+'>'+data_json['data'][i].nama+'</option>');
            $('.selectRangking').append('<option value="'+i+'" '+selected+'>'+i+'</option>');
        }
        $('.selectRangking').select2({
           allowClear: true
        });

        <?php if ($action == 'edit'): ?>
          $("#selectRangking1 option").filter(function() {
             //may want to use $.trim in here
             return $(this).val() == "<?php echo $getData[0]['StartRange'] ?>"; 
           }).prop("selected", true);
          $('#selectRangking1').select2({
             allowClear: true
          });

          $("#selectRangking2 option").filter(function() {
             //may want to use $.trim in here
             return $(this).val() == "<?php echo $getData[0]['EndRange'] ?>"; 
           }).prop("selected", true);
          $('#selectRangking2').select2({
             allowClear: true
          });
        <?php endif ?>

    }

    function loadSelectPotongan()
      {
          $("#selectPotongan").empty();
          for(var i=10;i<=100;i=i+10){
              var selected = (i==0) ? 'selected' : '';
              //$('#selectWilayah').append('<option value="'+data_json['data'][i].kode_wilayah+'" '+selected+'>'+data_json['data'][i].nama+'</option>');
              $('#selectPotongan').append('<option value="'+i+'" '+selected+'>'+i+'</option>');
          }
          $('#selectPotongan').select2({
             allowClear: true
          });

          <?php if ($action == 'edit'): ?>
            $("#selectPotongan option").filter(function() {
               //may want to use $.trim in here
               return $(this).val() == "<?php echo $getData[0]['DiskonSPP'] ?>"; 
             }).prop("selected", true);
            $('#selectPotongan').select2({
               allowClear: true
            });
          <?php endif ?>

      }
</script>