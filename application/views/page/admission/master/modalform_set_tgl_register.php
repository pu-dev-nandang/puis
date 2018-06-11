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
                    <label class="control-label">Start Date:</label>
                </div>    
                <div class="col-sm-6">
                    <input type="text" id="startDate"  class="form-control" readonly="" value = ''>
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">End Date :</label>
                </div>    
                <div class="col-sm-6">
                    <input type="text" id="endDate"  class="form-control" readonly="">
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
        $('#startDate').datetimepicker({
            // startDate: today,
            startDate: '+1d',
        });

        $('#endDate').datetimepicker({
            // startDate: today,
            startDate: '+1d',
        });

        <?php if ($action == 'edit'): ?>
          $("#startDate").val('<?php echo $getData[0]['Start_register'] ?>');
          $("#endDate").val('<?php echo $getData[0]['Deadline_register'] ?>');
        <?php endif ?>
    });
</script>