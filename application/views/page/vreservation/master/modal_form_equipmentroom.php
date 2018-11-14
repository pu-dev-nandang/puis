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
                    <label class="control-label">Choose Room:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="selectRoom">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 hide" align = 'center' id='msgMENU' style="color: red;">MSG</div>
            </div>   
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Choose Equipment Item:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 col-md-4 full-width-fix" id="selectEquipmentItem">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Qty :</label>
                </div>    
                <div class="col-sm-6">
                   <input type="number" class="form-control"  id="Qty" value="1">
                </div>
            </div>
        </div>
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Note :</label>
                </div>    
                <div class="col-sm-6">
                   <input type="text" class="form-control"  id="Note">
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
    $(document).ready(function() {
        loadSelectOptionRoom();
        loadSelectItemEquipment();
    });

    function loadSelectOptionRoom()
    {
        $("#selectRoom").empty();
        var url = base_url_js+'api/__crudClassroomVreservation';
        var token = jwt_encode({action:'read'},"UAP)(*");
        $.post(url,{token:token},function (json_result) {
            for(var i=0;i<json_result.length;i++){
                var data = json_result[i];
                var selected = (i==0) ? 'selected' : '';
                //$('#selectWilayah').append('<option value="'+data_json['data'][i].kode_wilayah+'" '+selected+'>'+data_json['data'][i].nama+'</option>');
                $('#selectRoom').append('<option value="'+data.Room+'" '+selected+'>'+data.Room+'</option>');
            }
            $('#selectRoom').select2({
               allowClear: true
            });
            <?php if ($action == 'edit'): ?>
                var Room = "<?php echo $getDataEdit[0]['Room'] ?>";
                   $("#selectRoom option").filter(function() {
                      //may want to use $.trim in here
                      return $(this).val() == Room; 
                    }).prop("selected", true);
                   $('#selectRoom').select2({
                      allowClear: true
                   });

                   var Qty = "<?php echo $getDataEdit[0]['Qty'] ?>";
                   $("#Qty").val(Qty);
                   var Note = "<?php echo $getDataEdit[0]['Note'] ?>";
                   $("#Note").val(Note);
            <?php endif ?>
        }).done(function () {
              
        });

    }

    function loadSelectItemEquipment()
    {
        $("#selectEquipmentItem").empty();
        var url = base_url_js+'vreservation/master/getDataEquipmentMaster';
        $.post(url,function (json_result) {
          var data = jQuery.parseJSON(json_result);
          // console.log(data);
            for(var i=0;i<data.length;i++){
                var selected = (i==0) ? 'selected' : '';
                //$('#selectWilayah').append('<option value="'+data_json['data'][i].kode_wilayah+'" '+selected+'>'+data_json['data'][i].nama+'</option>');
                $('#selectEquipmentItem').append('<option value="'+data[i]['ID']+'" '+selected+'>'+data[i]['Equipment']+'</option>');
            }
            $('#selectEquipmentItem').select2({
               allowClear: true
            });
            <?php if ($action == 'edit'): ?>
                var ID_m_equipment = "<?php echo $getDataEdit[0]['ID_m_equipment'] ?>";
                   $("#selectEquipmentItem option").filter(function() {
                      //may want to use $.trim in here
                      return $(this).val() == ID_m_equipment; 
                    }).prop("selected", true);
                   $('#selectEquipmentItem').select2({
                      allowClear: true
                   });
            <?php endif ?>
        }).done(function () {
              
        });
    }

</script>