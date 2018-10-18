<script type="text/javascript" src="<?php echo base_url('assets/plugins/bootstrap-wysihtml5/wysihtml5.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.min.js'); ?>"></script>
<style type="text/css">
  .setfont
  {
    font-size: 12px;
  }
  
</style>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-2"></div>
    <div class="col-md-8 formAddFormKD">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> <?php echo $NameMenu ?></h4>
            </div>
            <div class="widget-content">
                <!--  -->
                <div class="box-body">
                  <!--<form id='formAdd' action = '<?php echo base_url();?>administrator/email_setting/submit' method ='POST' name='form' enctype="multipart/form-data">-->
                    <div class="form-group">
                      <label for="email">Header:</label>
                      <input type="text" class="form-control input-width-xxlarge" id="header" name ='header' value = '<?php echo $getData[0]['Header'] ?>'>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="pwd">Font Header:</label>
                            <select class="form-control selectFont" id="selectFontHeader" style="width: 30%;">
                                <option></option>
                            </select>
                    </div>
                    <hr>
                    <div class="form-group">
                      <label>Contain 1:</label>
                      <textarea rows="12" name="" class="form-control " style="width: 100%;" id = "contain1"><?php echo $getData[0]['Contain1'] ?></textarea>
                      <label class="control-label" for="pwd">Font Contain 1:</label>
                          <select class="form-control selectFont" id="selectFontContain1" style="width: 30%;">
                              <option></option>
                          </select>
                    </div>
                    <hr>
                    <div class="form-group">
                      <label>Contain 2:</label>
                      <textarea rows="12" name="" class="form-control " style="width: 100%;" id = "contain2"><?php echo $getData[0]['Contain2'] ?></textarea>
                      <label class="control-label" for="pwd">Font Contain 2:</label>
                          <select class="form-control selectFont" id="selectFontContain2" style="width: 30%;">
                              <option></option>
                          </select>
                        <p style="color: red;">* Boleh Kosong</p>  
                    </div>
                    <hr>
                      <button type="button" class="btn btn-default btn-add" id="sbmt">Submit</button>
                      <button type="button" class="btn btn-info btn-read" id = 'test'>Test</button>
                  <!--</form>-->
                </div>
                <!-- /.box-body -->
                <!-- end widget -->
            </div>
            <hr/>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
      loadselectFont();

    $('#test').click(function(){
      loading_button('#test');
      var header = $("#header").val();
      var selectFontHeader = $("#selectFontHeader").val();
      var contain1 = $("#contain1").val();
      var selectFontContain1 = $("#selectFontContain1").val();
      var contain2 = $("#contain2").val();
      var selectFontContain2 = $("#selectFontContain2").val();
      var url = base_url_js+'admission/master-config/testing_print_label_token';
      var data = {
            header : header, 
            selectFontHeader : selectFontHeader, 
            contain1 : contain1, 
            selectFontContain1 : selectFontContain1,
            contain2 : contain2,
            selectFontContain2 : selectFontContain2,
                  };
      var token = jwt_encode(data,"UAP)(*");
      $.post(url,{token:token},function (data_json) {
          setTimeout(function () {
               var response = jQuery.parseJSON(data_json);
               //console.log(response);
               //window.location.href = base_url_js+'fileGet/'+response;
               window.open(base_url_js+'fileGet/'+response,'_blank');
              $('#test').prop('disabled',false).html('Test');
          },1000);
      });
    })// exit click function

    function loadselectFont()
    {
        $('.selectFont').empty();
        for (var i = 5; i <= 25; i++) {
            var selected = (i==12) ? 'selected' : '';
            $('.selectFont').append('<option value="'+ i +'" '+selected+'>'+i+'</option>');
        }
        
    }

    $('#sbmt').click(function(){
      loading_button('#sbmt');
      var header = $("#header").val();
      var selectFontHeader = $("#selectFontHeader").val();
      var contain1 = $("#contain1").val();
      var selectFontContain1 = $("#selectFontContain1").val();
      var contain2 = $("#contain2").val();
      var selectFontContain2 = $("#selectFontContain2").val();
      var url = base_url_js+'admission/master-config/save_set_print_label';
     var data = {
            header : header, 
            selectFontHeader : selectFontHeader, 
            contain1 : contain1, 
            selectFontContain1 : selectFontContain1,
            contain2 : contain2,
            selectFontContain2 : selectFontContain2,
                  };
      var token = jwt_encode(data,"UAP)(*");
      if (validation2(data))
      {
          $.post(url,{token:token},function (data_json) {
              setTimeout(function () {
                  var response = jQuery.parseJSON(data_json);
                  console.log(response);
                    toastr.options.fadeOut = 10000;
                    toastr.success("Done", 'Success!');
                  $('#sbmt').prop('disabled',false).html('Submit');
              },2000);
          });  
      }
      else
      {
            $('#sbmt').prop('disabled',false).html('Submit');
      }
    })// exit click function

    function validation2(arr)
    {
      var toatString = "";
      var result = "";
      for(var key in arr) {
         switch(key)
         {
          case "header" :      
          case "contain1" :      
                result = Validation_required(arr[key],key);
                if (result['status'] == 0) {
                  toatString += result['messages'] + "<br>";
                }
                break;
         }

      }
      if (toatString != "") {
        toastr.error(toatString, 'Failed!!');
        return false;
      }

      return true;
    }

    function GetData()
    {
      <?php if (count($getData) > 0): ?>
            $("#selectFontHeader option").filter(function() {
               //may want to use $.trim in here
               return $(this).val() == "<?php echo $getData[0]['setFont1'] ?>"; 
            }).prop("selected", true);

            $("#selectFontContain1 option").filter(function() {
               //may want to use $.trim in here
               return $(this).val() == "<?php echo $getData[0]['setFontHeader'] ?>"; 
            }).prop("selected", true);

            $("#selectFontContain2 option").filter(function() {
               //may want to use $.trim in here
               return $(this).val() == "<?php echo $getData[0]['setFont2'] ?>"; 
            }).prop("selected", true);
      <?php endif ?>
    }
  }); // exit document Function  

</script>