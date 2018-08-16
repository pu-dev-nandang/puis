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
                    <label class="control-label">Choice Division</label>
                </div>    
                <div class="col-sm-6">
                    <select class="full-width-fix" id="selectDivision">
                        <option></option>
                    </select>
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
        loadDataDivisionSelect();
    });

    function loadDataDivisionSelect()
    {
        var url = base_url_js+"api/__getDivision";
        $('#selectDivision').empty()
        $.post(url,function (data_json) {
              for(var i=0;i<data_json.length;i++){
                  var selected = (i==0) ? 'selected' : '';
                  //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
                  $('#selectDivision').append('<option value="'+data_json[i].ID+'" '+selected+'>'+data_json[i].Division+'</option>');
              }
              $('#selectDivision').select2({
                 //allowClear: true
              });
        }).done(function () {
          <?php if ($action == 'edit'): ?>
              loadDataEdit();
          <?php endif ?> 
        });
    }

    <?php if ($action == 'edit'): ?>

    function loadDataEdit()
    {
        var selectDivision = "<?php echo $getDataEdit[0]['ID_division'] ?>";

        $("#selectDivision option").filter(function() {
           //may want to use $.trim in here
           return $(this).val() == selectDivision; 
        }).prop("selected", true);

        $('#selectDivision').select2({
           //allowClear: true
        });
    }

    <?php endif ?> 
</script>