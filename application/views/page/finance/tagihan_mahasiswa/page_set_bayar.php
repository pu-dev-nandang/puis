<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="row">
    <div class="col-xs-12" >
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Set Bayar</h4>
            </div>
            <div class="panel-body">
               <div class="row" style="margin-top: 30px;">
                   <div class="col-md-3">
                       <div class="thumbnail" style="min-height: 30px;padding: 10px;">
                           <label>Input NPM</label>
                           <input type="text" name="" class="form-control" placeholder="Input NPM Mahasiswa" id = "NIM">
                       </div>
                   </div>
                   <div class="col-md-3">
                     <div class="thumbnail" style="min-height: 30px;padding: 10px;">
                         <label>Pilih Semester</label>
                         <select class="form-control" id="selectSemester">
                         </select>
                     </div>
                   </div>
               </div>
               <div class="row">
                 <div class="col-md-12" align="right">
                   <button type="button" class="btn btn-default" id = 'idbtn-cari'><span class="glyphicon glyphicon-search"></span> Cari</button>
                 </div>
               </div>
            </div>
            <div class="panel-footer">
              <button href="<?php echo base_url().'finance/tagihan-mhs/cek-tagihan-mhs' ?>" class = "btn btn-default btn-back">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  const phpNPM = "<?php echo $NPM ?>";
  if (phpNPM != '') {
    $('#NIM').val(phpNPM);
  }
  const phpSemesterID = "<?php echo $SemesterID ?>";
  const phppaymentid = "<?php echo $paymentid ?>";
</script>

<script type="text/javascript" src="<?php echo base_url('js/finance/tagihan-mahasiswa/page_set_bayar.js'); ?>"></script>