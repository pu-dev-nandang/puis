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
                    <label class="control-label">Sub Account Code:</label>
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
            <div class="row <?php echo $a = ($action == 'add') ? 'hide' :'' ?>" id = "rowCodePostRealisasi">
              <div class="col-md-6 col-md-offset-4">
                <input type="text" name="CodePost" id= "CodePostRealisasi" placeholder="Code" class="form-control">
              </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Head Account :</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 full-width-fix" id="HeadAccount">
                        <!-- <option></option> -->
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Sub Account Name:</label>
                </div>    
                <div class="col-sm-6">
                   <input type="text" name="PostName" id= "RealisasiPostName" placeholder="Sub Account Name" class="form-control">
                </div>
            </div>
        </div>
        <div style="text-align: center;">       
        <div class="col-sm-12" id="BtnFooter">
                <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>
                <button type="button" id="ModalbtnSaveForm2" class="btn btn-success" action = "<?php echo $action ?>" kodeuniq = "<?php echo $id ?>">Save</button>
        </div>
        </div>    
    </form>
    
<script type="text/javascript">
    $(document).ready(function () {
        getDataPostItem();
        <?php if ($action == 'edit'): ?>
          $("#CodePostRealisasi").val('<?php echo $getData[0]['CodePostRealisasi'] ?>');
          $("#RealisasiPostName").val('<?php echo $getData[0]['RealisasiPostName'] ?>');
        <?php endif ?>

        $(".NeedPrefix").change(function(){
          var valuee = $(this).val();
          if(valuee == 0)
          {
            $("#rowCodePostRealisasi").removeClass('hide');
            $("#legendCode").addClass('hide');
          }
          else
          {
            $("#rowCodePostRealisasi").addClass('hide');
            $("#legendCode").removeClass('hide');
          }
        })

        $(".NeedPrefix").click(function(){
            $('input.NeedPrefix').prop('checked', false);
            $(this).prop('checked',true);
        });

    });

    function getDataPostItem()
    {
      var url = base_url_js+"budgeting/get_cfg_head_account";
      $('#HeadAccount').empty();
      $.post(url,function (data_json) {
        var response = jQuery.parseJSON(data_json);
        var sessIDDepartementPUBudget = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";
        for (var i = 0; i < response.length; i++) {
            var CodeDepartment = response[i].Departement;
            if (sessIDDepartementPUBudget != 'NA.9') {
              if (CodeDepartment == sessIDDepartementPUBudget) {
                var selected = (i==0) ? 'selected' : '';
                $('#HeadAccount').append('<option value="'+ response[i]['CodeHeadAccount']  +'" '+selected+'>'+response[i]['NameHeadAccount']+' {'+response[i]['DepartementName']+'}'+'</option>');
              } 
            } else {
              var selected = (i==0) ? 'selected' : '';
              $('#HeadAccount').append('<option value="'+ response[i]['CodeHeadAccount']  +'" '+selected+'>'+response[i]['NameHeadAccount']+' {'+response[i]['DepartementName']+'}'+'</option>');
            }
             
        }

        <?php if ($action == 'edit'): ?>
          $("#HeadAccount option").filter(function() {
            //may want to use $.trim in here
            return $(this).val() == "<?php echo $getData[0]['CodeHeadAccount'] ?>"; 
          }).prop("selected", true);
        <?php endif ?>

        $('#HeadAccount').select2({
           //allowClear: true
        });
      }).done(function () {
        //loadAlamatSekolah();
      });

    }
</script>