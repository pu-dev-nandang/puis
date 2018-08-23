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
                    <label class="control-label">VA Status :</label>
                </div>    
                <div class="col-sm-6">
                   <input type="number" class="form-control"  id="VA_status" value="1" min = "0" max = "1">
                </div>
            </div>
        </div> 
        <div style="text-align: center;">       
    		<div class="col-sm-12" id="BtnFooter">
                <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>
                <button type="button" id="ModalbtnSaveForm" class="btn btn-success" aksi = "<?php echo $action ?>" kodeuniq = "<?php echo $id ?>">Save</button>
                <!--<button type="button" id="ModalbtnEditForm" class="btn btn-default btn-default-success hide">Edit Data</button>-->
    		</div>
        </div>    
    </form>
<script type="text/javascript">
    $(document).ready(function () {
        construct();
    });

      function construct()
      {
          <?php if ($action == 'edit'): ?>
              loadDataEdit();
           <?php endif ?> 
      }

    <?php if ($action == 'edit'): ?>

    function loadDataEdit()
    {

        $("#VA_status").val("<?php echo $getDataEdit[0]['VA_active'] ?>");
    }

    <?php endif ?> 
</script>