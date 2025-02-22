<script type="text/javascript" src="<?php echo base_url('assets/plugins/bootstrap-wysihtml5/wysihtml5.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.min.js'); ?>"></script>
<style type="text/css">
  .setfont
  {
    font-size: 12px;
  }
  
</style>
<div class="row btn-read" style="margin-top: 30px;">
    <div class="col-md-2"></div>
    <div class="col-md-8 formAddFormKD">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> Set Email</h4>
            </div>
            <div class="widget-content">
                <!--  -->
                <div class="box-body">
                  <!--<form id='formAdd' action = '<?php echo base_url();?>administrator/email_setting/submit' method ='POST' name='form' enctype="multipart/form-data">-->
                    <div class="form-group">
                      <label for="email">Email address:</label>
                      <input type="email" class="form-control input-width-xxlarge" id="email" name ='email' value = '<?php echo $email['setting']['smtp_user'] ?>'>
                    </div>
                    <div class="form-group">
                      <label for="pwd">Password:</label>
                      <input type="password" class="form-control input-width-xxlarge" id="pwd" name = 'pass' value = '<?php echo $email['setting']['smtp_pass'] ?>'>
                    </div>
                    <div class="form-group">
                      <label>SMTP Host:</label>
                      <input type="text" class="form-control input-width-xxlarge" id="smtp_host" name = 'smtp_host' value = '<?php echo $email['setting']['smtp_host']?>'>
                    </div>
                    <div class="form-group">
                      <label>SMTP Port:</label>
                      <input type="text" class="form-control input-width-xxlarge" id="smtp_port" name = 'smtp_port' value = '<?php echo $email['setting']['smtp_port'] ?>'>
                    </div>
                    <div class="form-group">
                      <label>Text Messages:</label>
                      <textarea rows="20" name="wysiwyg" class="form-control wysiwyg"><?php echo $email['text'] ?></textarea>
                    </div>
                      <button type="button" class="btn btn-default btn-edit" id="sbmt">Submit</button>
                      <button type="button" class="btn btn-info" id = 'test_connection'>Test Connection</button>
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
    $('#test_connection').click(function(){
      loading_button('#test_connection');
      var email = $('#email').val();
      var pwd = $('#pwd').val();
      var smtp_host = $('#smtp_host').val();
      var smtp_port = $('#smtp_port').val();
      var url = base_url_js+'admission/master-config/testing_email';
      var data = {email : email, pwd : pwd, smtp_port : smtp_port, smtp_host : smtp_host};
      var token = jwt_encode(data,"UAP)(*");
      $.post(url,{token:token},function (data_json) {
          setTimeout(function () {
              var response = jQuery.parseJSON(data_json);
              if (response.status == 1) {
                toastr.options.fadeOut = 10000;
                toastr.success(response.msg, 'Success!');
              }
              else
              {
                toastr.options.fadeOut = 10000;
                toastr.error(response.msg, 'Failed!!');
              }
              $('#test_connection').prop('disabled',false).html('Test Connection');
          },500);
      });
    })// exit click function

    $('#sbmt').click(function(){
      loading_button('#sbmt');
      var email = $('#email').val();
      var pwd = $('#pwd').val();
      var smtp_host = $('#smtp_host').val();
      var smtp_port = $('#smtp_port').val();
      var text = $('.wysiwyg').val();
      var url = base_url_js+'admission/master-config/save_email';
      var data = {email : email, pwd : pwd, smtp_port : smtp_port, smtp_host : smtp_host, text : text};
      var token = jwt_encode(data,"UAP)(*");
      $.post(url,{token:token},function (data_json) {
          setTimeout(function () {
              var response = jQuery.parseJSON(data_json);
              console.log(response);
                toastr.options.fadeOut = 10000;
                toastr.success("Done", 'Success!');
              $('#sbmt').prop('disabled',false).html('Submit');
          },500);
      });
    })// exit click function
  }); // exit document Function  

</script>