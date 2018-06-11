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
                    <label class="control-label">Pilih Tingkat:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="selectTingkat">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Pilih Potongan SPP :</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix potongan" id="selectPotonganSPP">
                        <option></option>
                    </select>
                    Dalam Bentuk % (Persen)
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Pilih Potongan SKS:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix potongan" id="selectPotonganSKS">
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
        loadSelectTingkat();
        loadSelectPotongan();
    });

    function loadSelectTingkat()
    {
        $("#selectTingkat").empty();
        $('#selectTingkat').append('<option value="'+'Internasional'+'" '+'selected'+'>'+'Internasional'+'</option>');
        $('#selectTingkat').append('<option value="'+'Nasional'+'" '+''+'>'+'Nasional'+'</option>');
        $('#selectTingkat').append('<option value="'+'Provinsi'+'" '+''+'>'+'Provinsi'+'</option>');
        $('#selectTingkat').append('<option value="'+'Daerah'+'" '+''+'>'+'Daerah'+'</option>');

        $('#selectTingkat').select2({
           allowClear: true
        });

        <?php if ($action == 'edit'): ?>
          $("#selectTingkat option").filter(function() {
             //may want to use $.trim in here
             return $(this).val() == "<?php echo $getData[0]['Tingkat'] ?>"; 
           }).prop("selected", true);
          $('#selectTingkat').select2({
             allowClear: true
          });

        <?php endif ?>

    }

    function loadSelectPotongan()
      {
          $(".potongan").empty();
          for(var i=0;i<=100;i=i+10){
              var selected = (i==0) ? 'selected' : '';
              //$('#selectWilayah').append('<option value="'+data_json['data'][i].kode_wilayah+'" '+selected+'>'+data_json['data'][i].nama+'</option>');
              $('.potongan').append('<option value="'+i+'" '+selected+'>'+i+'</option>');
          }
          $('.potongan').select2({
             allowClear: true
          });

          <?php if ($action == 'edit'): ?>
            $("#selectPotonganSPP option").filter(function() {
               //may want to use $.trim in here
               return $(this).val() == "<?php echo $getData[0]['DiskonSPP'] ?>"; 
             }).prop("selected", true);
            $('#selectPotonganSPP').select2({
               allowClear: true
            });

            $("#selectPotonganSKS option").filter(function() {
               //may want to use $.trim in here
               return $(this).val() == "<?php echo $getData[0]['DiskonBiayaSKS'] ?>"; 
             }).prop("selected", true);
            $('#selectPotonganSKS').select2({
               allowClear: true
            });
          <?php endif ?>
      }
</script>