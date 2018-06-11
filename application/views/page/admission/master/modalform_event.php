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
                    <label class="control-label">Harga Formulir Event:</label>
                </div>    
                <div class="col-sm-6">
                    <input type="text" id="price_event"  class="form-control" placeholder="" value =" <?php echo ($action == 'add') ? $harga_formulir_offline : $getData[0]['evn_price'] ?>">
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Nama Event :</label>
                </div>    
                <div class="col-sm-6">
                    <input type="text" id="name_event"  class="form-control" placeholder="" value="<?php echo ($action == 'add') ? '' : $getData[0]['evn_name'] ?>">
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
    
</script>