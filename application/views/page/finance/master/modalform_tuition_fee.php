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
                <div class="col-sm-3">
                    <label class="control-label">Pilih Tipe Pembayaran:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="selectTypePembayaran">
                        <!-- <option></option> -->
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 hide" align = 'center' id='msgMENU' style="color: red;">MSG</div>
            </div>   
            <div class="row">
                <div class="col-sm-3">
                    <label class="control-label">Pilih Program Studi:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="selectProdi">
                        <!-- <option></option> -->
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-3">
                    <label class="control-label">Pilih Tahun Angkatan :</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="selectClassOf">
                        <!-- <option></option> -->
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-3">
                    <label class="control-label">Cost :</label>
                </div>    
                <div class="col-sm-6">
                   <input type="text" name="Cost" id= "Cost" placeholder="Input Cost" class="form-control">
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-3">
                    <label class="control-label">Bintang :</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="selectPay_Cond">
                        <option value="1">*</option>
                        <option value="2">**</option>
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
        loadSelectOptionPaymentTypeAll('#selectTypePembayaran','0');
        loadSelectOptionBaseProdi('#selectProdi','0');
        LoadTahunAngkatan();
        $('#Cost').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
    });

    function LoadTahunAngkatan()
    {
      var thisYear = (new Date()).getFullYear();
      var startTahun = 2014;
      var selisih = parseInt(thisYear+1) - parseInt(startTahun);
      for (var i = 0; i <= selisih; i++) {
          var selected = (i==0) ? 'selected' : '';
          $('#selectClassOf').append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
      }

      var selectedCuriculum = '<?php echo $selectCurriculum ?>';
       $("#selectClassOf option").filter(function() {
         //may want to use $.trim in here
         return $(this).val() == selectedCuriculum; 
       }).prop("selected", true);

    }
</script>