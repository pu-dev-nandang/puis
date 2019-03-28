<style type="text/css">
    button, input, select, textarea {
        /*margin: 7px;*/
        font-family: inherit;
        font-size: 100%;
    }
</style>
<p class="hide" id = "legendCode" style="color: red"><strong>The Code will be get it after submit</strong></p>
    <form class="form-horizontal" id="formModal">
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Budget Category Code:</label>
                </div>
                <?php if ($action == 'add'): ?>
                  <div class="col-sm-6">
                    <strong>Code Automatic</strong>
                  </div>
                <?php else: ?>
                    <div class="col-sm-6">
                      <strong>Code Manual</strong>
                    </div>  
                <?php endif ?>
            </div>
            <div class="row <?php echo $a = ($action == 'add') ? '' : 'hide' ?>">
              <div class="col-md-3 col-md-offset-4">
                <input type="checkbox" class="NeedPrefix" name="NeedPrefix" value="0" <?php echo $a = ($action == 'add') ? '' :'checked' ?> >&nbsp; No
                <input type="checkbox" class="NeedPrefix" name="NeedPrefix" value="1">&nbsp; Yes
              </div>
            </div>
            <div class="row <?php echo $a = ($action == 'add') ? 'hide' :'' ?>" id = "rowCodePost">
              <div class="col-md-6 col-md-offset-4">
                <input type="text" name="CodePost" id= "CodePost" placeholder="Code" class="form-control" maxlength="10">
              </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Budget Category Name:</label>
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
        <?php if ($action == 'edit'): ?>
          $("#CodePost").val('<?php echo $getData[0]['CodePost'] ?>')
          $("#PostName").val('<?php echo $getData[0]['PostName'] ?>')
        <?php endif ?>

        $(".NeedPrefix").change(function(){
          var valuee = $(this).val();
          if(valuee == 0)
          {
            $("#rowCodePost").removeClass('hide');
            $("#legendCode").addClass('hide');
          }
          else
          {
            $("#rowCodePost").addClass('hide');
            $("#legendCode").removeClass('hide');
          }
        })

        $(".NeedPrefix").click(function(){
            $('input.NeedPrefix').prop('checked', false);
            $(this).prop('checked',true);
        });

    });
</script>