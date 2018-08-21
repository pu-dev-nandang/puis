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
                    <label class="control-label">Choice Group User</label>
                </div>    
                <div class="col-sm-6">
                    <select class="full-width-fix" id="selectGroupuUser">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Booking Day :</label>
                </div>    
                <div class="col-sm-6">
                   <input type="number" class="form-control"  id="BookingDay" value="1">
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
        loadSelectGroupUser();
    });

      function loadSelectGroupUser()
      {
          var url = base_url_js+"vreservation/getGroupPrevileges";
          $('#selectGroupuUser').empty()
          $.post(url,function (data_json) {
              var obj = JSON.parse(data_json);
              //$('#selectGroupuUser').append('<option value="'+'0'+'" '+''+'>'+'--Choice Group User --'+'</option>');
                for(var i=0;i<obj.length;i++){
                    var selected = (i==0) ? 'selected' : '';
                    //var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
                    $('#selectGroupuUser').append('<option value="'+obj[i].ID+'" '+selected+'>'+obj[i].GroupAuth+'</option>');
                    // $('#selectGroupuUser2').append('<option value="'+obj[i].ID+'" '+selected+'>'+obj[i].GroupAuth+'</option>');
                }
                $('#selectGroupuUser').select2({
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
        var selectGroupuUser = "<?php echo $getDataEdit[0]['ID_group_user'] ?>";

        $("#selectGroupuUser option").filter(function() {
           //may want to use $.trim in here
           return $(this).val() == selectGroupuUser; 
        }).prop("selected", true);

        $('#selectGroupuUser').select2({
           //allowClear: true
        });

        $("#BookingDay").val("<?php echo $getDataEdit[0]['BookingDay'] ?>");
    }

    <?php endif ?> 
</script>