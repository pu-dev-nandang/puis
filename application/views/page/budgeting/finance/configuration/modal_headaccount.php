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
                    <label class="control-label">HeadAccount Code:</label>
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
            <div class="row <?php echo $a = ($action == 'add') ? 'hide' :'' ?>" id = "rowCodeHeadAccount">
              <div class="col-md-6 col-md-offset-4">
                <input type="text" name="CodePost" id= "CodeHeadAccount" placeholder="Code" class="form-control">
              </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Budget Category :</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 full-width-fix" id="PostItem">
                        <!-- <option></option> -->
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">HeadAccountName:</label>
                </div>    
                <div class="col-sm-6">
                   <input type="text" name="HeadAccountName" id= "HeadAccountName" placeholder="HeadAccountName" class="form-control">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Department:</label>
                </div>    
                <div class="col-sm-6">
                   <select class="select2-select-00 full-width-fix" id="Departement">
                        <!-- <option></option> -->
                    </select>
                </div>
            </div>
        </div>
        <div style="text-align: center;">       
        <div class="col-sm-12" id="BtnFooter">
                <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>
                <button type="button" id="ModalbtnSaveForm3" class="btn btn-success" action = "<?php echo $action ?>" kodeuniq = "<?php echo $id ?>">Save</button>
        </div>
        </div>    
    </form>
    
<script type="text/javascript">
    $(document).ready(function () {
        getDataPostItem();
        getAllDepartementPU();
        <?php if ($action == 'edit'): ?>
          $("#CodeHeadAccount").val('<?php echo $getData[0]['CodeHeadAccount'] ?>');
          $("#HeadAccountName").val('<?php echo $getData[0]['Name'] ?>');
        <?php endif ?>

        $(".NeedPrefix").change(function(){
          var valuee = $(this).val();
          if(valuee == 0)
          {
            $("#rowCodeHeadAccount").removeClass('hide');
            $("#legendCode").addClass('hide');
          }
          else
          {
            $("#rowCodeHeadAccount").addClass('hide');
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
      var url = base_url_js+"budgeting/table_all/cfg_post/1";
      $('#PostItem').empty();
      $.post(url,function (data_json) {
        var response = jQuery.parseJSON(data_json);
        for (var i = 0; i < response.length; i++) {
            var selected = (i==0) ? 'selected' : '';
            $('#PostItem').append('<option value="'+ response[i]['CodePost']  +'" '+selected+'>'+response[i]['PostName']+'</option>');
        }

        <?php if ($action == 'edit'): ?>
          $("#PostItem option").filter(function() {
            //may want to use $.trim in here
            return $(this).val() == "<?php echo $getData[0]['CodePost'] ?>"; 
          }).prop("selected", true);
        <?php endif ?>

        $('#PostItem').select2({
           //allowClear: true
        });
      }).done(function () {
        //loadAlamatSekolah();
      });

    }

    function getAllDepartementPU()
    {
      var url = base_url_js+"api/__getAllDepartementPU";
      $('#Departement').empty();
      $.post(url,function (data_json) {
        for (var i = 0; i < data_json.length; i++) {
            var selected = (i==0) ? 'selected' : '';
            $('#Departement').append('<option value="'+ data_json[i]['Code']  +'" '+selected+'>'+data_json[i]['Name2']+'</option>');
        }

        // lock department if not finance
        var sessIDDepartementPUBudget = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";
        if (sessIDDepartementPUBudget != 'NA.9') {
          $("#Departement option").filter(function() {
           //may want to use $.trim in here
           return $(this).val() == sessIDDepartementPUBudget; 
         }).prop("selected", true);
         $( "#Departement" ).prop( "disabled", true );
        }

        <?php if ($action == 'edit'): ?>
            $("#Departement option").filter(function() {
             //may want to use $.trim in here
             return $(this).val() == "<?php echo $getData[0]['Departement'] ?>"; 
           }).prop("selected", true);

            // lock department if not finance
               if (sessIDDepartementPUBudget != 'NA.9') {
                $( "#Departement" ).prop( "disabled", true );
               }
        <?php endif ?>
       
        $('#Departement').select2({
           //allowClear: true
        });
      }).done(function () {
        //loadAlamatSekolah();
      });
    }
</script>