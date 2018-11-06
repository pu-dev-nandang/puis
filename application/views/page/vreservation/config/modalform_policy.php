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
        <div class="form-group"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Access Category Room :</label>
                </div>    
                <div class="col-sm-6" id ="access_category_room">
                    
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
        loadCategoryRoom();
    });

    function loadCategoryRoom()
    {
      var Category = [];
      <?php if ($action == 'edit'): ?>
        var Category = <?php echo $getDataEdit[0]['CategoryRoom'] ?>;
      <?php endif ?>
      
      var token = jwt_encode({action:'read'},"UAP)(*");
      var url = base_url_js+'api/__crudCategoryClassroomVreservation';
      $.post(url,{token:token},function (json_result) {
          $('#access_category_room').empty();
         var splitBagi = 1;
         var split = parseInt(json_result.length / splitBagi);
         var sisa = json_result.length % splitBagi;
         
         if (sisa > 0) {
               split++;
         }
         var getRow = 0;
         $('#access_category_room').append('<table class="table" id ="tablechk_category_room">');
         for (var i = 0; i < split; i++) {
           if ((sisa > 0) && ((i + 1) == split) ) {
                               splitBagi = sisa;    
           }
           $('#tablechk_category_room').append('<tr id = "a'+i+'">');
           for (var k = 0; k < splitBagi; k++) {
                var Checked = '';
               for (var l = 0; l < Category.length; l++) {
                 if (json_result[getRow].ID == Category[l]) {
                  Checked = 'checked';break;
                 }
               }
               $('#a'+i).append('<td>'+
                                   '<input type="checkbox" '+Checked+' class = "chke_category_room" name="chke_category_room" value = "'+json_result[getRow].ID+'">&nbsp'+ json_result[getRow].Name+' / '+json_result[getRow].NameEng+
                                '</td>'
                               );
               getRow++;
           }
           $('#a'+i).append('</tr>');
         }
         $('#tablechk_category_room').append('</table>');
              
      });
    }

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