<script type="text/javascript">
 $(document).ready(function(){
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-services").addClass("active");
    });
</script>
<input type="hidden" id="nip" name="NIP" value="<?=$NIP?>">

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-bars"></i> Self Service</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Change Password</h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered">
               <thead>
            <tr>
                <th width="15%">Menu</th>
                <th width="15%">Usename</th>
                <th width="15%">Password</th>
                <th width="40%">Deskripsi</th>
                <th width="40%">Status</th>
                <th width="15%">Action</th>
                
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left;">
                    Portal / Microsoft Account
                </td>
                <td style="text-align: left;">
                    <?=$NIP?>
                </td>
                <?php if (empty($detail->Password_Curr)): ?>
                    <td style="text-align: left;">
                    Default
                </td>
                <?php else: ?>
                    <td style="text-align: left;">
                    <?php
                    $res = substr($plan['plan_password'],3);
                     echo "***".$res; 
                     ?>
                </td>
                <?php endif ?>
                <td style="text-align: left;">
                    Wifi, Microsoft Account, Komputer
                </td>
                <td style="text-align: left;">
                   
                </td>
                <td>
                    <button class="btn btn-default btn-sm" title="Change Password" data-toggle="modal" data-target="#modal_changepass">Change Password</button>
                </td>
            </tr>
            
            <tr>
                <td style="text-align: left;">
                    G-Suite Account
                </td>
                <td style="text-align: left;">
                    <?=$detail->EmailPU?>
                    
                </td>
                <?php if (empty($req['NewPassword'])): ?>
                    <td style="text-align: left;">
                    Default
                </td>
                <?php else: ?>
                
                    <td style="text-align: left;">
                    <?php
                    $res = substr($req['NewPassword'],3);
                     echo "***".$res; 
                     ?>
                </td>
                <?php endif ?>
                <td style="text-align: left;">
                    G-Suite 
                </td>
                <td style="text-align: left;">
                    <?php if (empty($req['NewPassword'])): ?>
                        
                    <?php else: ?>
                        <?php if ($req['Status']==0): ?>
                        Pending
                    <?php else: ?>
                        Finish
                    <?php endif ?>
                    <?php endif ?>
                    
                </td>
                <td>
                    <button class="btn btn-default btn-sm" title="Change Password" data-toggle="modal" data-target="#modal_gsuite">Request Change Password</button>
                </td>

            </tr>
            
        </tbody>
       
            </table> 
            <p style="text-align: left;margin-top: 15px;color: #9E9E9E;font-size: 12px;">
                    * Default: Password that hasn't been changed
                    </p>                           
                        </div>
                    </div>

                </div>
               
            </div>
        </div>
     
    </div>



<div class="modal" id="modal_changepass" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header" style="border-bottom: none;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
      <div class="modal-body">
        <div class="thumbnail" style="min-height: 100px;padding: 15px;text-align: center; border: none; margin-bottom: 0px;">
                <img src="<?php echo base_url('images/logo.jpg'); ?>" style="max-width: 200px;">
                  <hr/>
            </div>
            <div class="form-group">
                <label>Current Password</label>
                  <input type="password" id="inputpasswordold" class="form-control" placeholder="Input current password..." autofocus>
                  <span id="alertCurrPass"></span>
                </div>
        <div class="form-group">
                  
                  <input type="password" id="inputpassword" class="form-control" placeholder="Input new password..." disabled>
                </div>
            <div class="form-group">
                  
                  <input type="password" id="inputpasswordre" class="form-control" placeholder="Re-input new password..." disabled>
                  <span id="alertPass" style="float: right;"></span>
                </div>   
                <p style="text-align: left;margin-top: 15px;color: #9E9E9E;font-size: 12px;">
                    - Minimum 8 character
                    <br/>
                    - Case sensitive</p> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btnchangepass" disabled>Submit</button>
      </div>
    </div>
  </div> 
</div> 

<div class="modal" id="modal_gsuite" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header" style="border-bottom: none;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
      <div class="modal-body">
        <div class="thumbnail" style="min-height: 100px;padding: 15px;text-align: center; border: none; margin-bottom: 0px;">
                <img src="<?php echo base_url('images/logo.jpg'); ?>" style="max-width: 200px;">
                  <hr/>
            </div>
        <div class="form-group">
                  
                  <input type="password" id="inputpasswordreq" class="form-control" placeholder="Input new password..." autofocus>
                </div>
            <div class="form-group">
                  
                  <input type="password" id="inputpasswordrereq" class="form-control" placeholder="Re-input new password..." disabled>
                  <span id="alertPass" style="float: right;"></span>
                </div>   
                <p style="text-align: left;margin-top: 15px;color: #9E9E9E;font-size: 12px;">
                    - Minimum 8 character
                    <br/>
                    - Case sensitive</p> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btn_gsuite" disabled>Submit</button>
      </div>
    </div>
  </div> 
</div> 


<script>
  //change password portal
   $(document).on('keyup','#modal_changepass #inputpasswordold',function () {
        countCharCurr();
    });

    $(document).on('blur','#modal_changepass #inputpasswordold',function () {
        countCharCurr();
    });


  function countCharCurr() {
        var c = $('#modal_changepass #inputpasswordold').val();
        var d = true;
        if(c.length>=0){
            d = false;
        }
        $('#modal_changepass #inputpassword').prop('disabled',false);

    }

 $(document).on('keyup','#modal_changepass #inputpassword',function () {
        countChar();
    });

    $(document).on('blur','#modal_changepass #inputpassword',function () {
        countChar();
    });

    function countChar() {
        var c = $('#modal_changepass #inputpassword').val();
        var d = true;
        if(c.length>=8){
            d = false;
        }
        $('#modal_changepass #inputpasswordre,#modal_changepass #btnchangepass').prop('disabled',d);
        $('#modal_changepass #inputpasswordre').css('border','1px solid #ccc');
        $('#alertPass').html('');

    }

    $(document).on('keyup','#modal_changepass #inputpasswordre',function () {
        checkPassword();
    });

    $(document).on('blur','#modal_changepass #inputpasswordre,#modal_changepass #inputpassword',function () {
        checkPassword();
    });

    $(document).on('keypress','#modal_changepass #inputpasswordre,#modal_changepass #inputpasswordre',function (e) {
        if (e.which == 13) {
            changepassportal();
            return false;   
        }
    });

    function checkPassword() {
        var inputpassword = $('#modal_changepass #inputpassword').val();
        var inputpasswordre = $('#modal_changepass #inputpasswordre').val();

        if(inputpassword!='' && inputpassword!=null && inputpasswordre!='' && inputpasswordre!=null){
            if(inputpassword==inputpasswordre){
                $('#modal_changepass #btnchangepass').prop('disabled',false);
                $('#modal_changepass #inputpasswordre').css('border','1px solid green');
                $('#modal_changepass #alertPass').html('<i style="color: green;">Match</i>');
            } else {
                $('#modal_changepass #btnchangepass').prop('disabled',true);
                $('#modal_changepass #inputpasswordre').css('border','1px solid red');
                $('#modal_changepass #alertPass').html('<i style="color: red;">Not match</i>');
            }
        }

    }


function loading_button(element) {
        $(''+element).html('<i class="fa fa-refresh fa-spin fa-fw right-margin"></i> Loading...');
        $(''+element).prop('disabled',true);
    }

$('#btnchangepass').click(function () {
        changepassportal();

    });


  async function changepassportal()
  {
     loading_button('#btnchangepass');

    var inputpasswordold = $('#modal_changepass #inputpasswordold').val();
    var inputpassword = $('#modal_changepass #inputpassword').val();
    var inputpasswordre = $('#modal_changepass #inputpasswordre').val();
    var nip = $("#nip").val();
    if (inputpassword!='' && inputpassword!=null && inputpasswordre!='' && inputpasswordre!=null) {
        if(inputpassword==inputpasswordre){
                // Cek apakah password < 8 karakter
                if(inputpassword.length>=8){
                  
                    var boolValidation = true;
                    var dataShareMenu = {
                      nip : nip,
                      pass:inputpassword,
                      passold: inputpasswordold,
                    };
                
                    var dataAjax = {
                      action : 'changeportalpass',
                      dataShareMenu : dataShareMenu,
                    }
                    var token = jwt_encode(dataAjax,'UAP)(*');
                    var url = "<?php echo base_url('change-password'); ?>";
                    try{
                      var ajax = await AjaxSubmitFormPromises(url,token);
                        
                      if(ajax['status'] == 1){
                        toastr.success('Password has been changed','Success');
                            setTimeout(function () {
                                window.location.href="<?php echo base_url('profile/self-services/'); ?>"+nip;
                            },1000);
                       
                      }
                      else
                      {
                        $('#modal_changepass #inputpasswordold').css('border','1px solid red');
                        $('#modal_changepass #alertCurrPass').html('<i style="color: red;">Wrong password</i>');
                      }
                    
                    }
                    catch(err){
                      toastr.info('something error');
                    }
                }
                else {
                    toastr.error('Password less than 8 character','Error');
                }
            } else {
                toastr.error('Password and Re-password not match','Error');
            }
    }else{

        if(inputpassword=='' || inputpassword==null) {
               $('#modal_changepass #inputpassword').css('border','1px solid red');
        setTimeout(function (args) {
                    $('#modal_changepass #inputpassword').css('border','1px solid #ccc');
                },1000);
            }

            if(inputpasswordre=='' || inputpasswordre==null){
                $('#modal_changepass #inputpasswordre').css('border','1px solid red');
        setTimeout(function (args) {
                    $('#modal_changepass #inputpasswordre').css('border','1px solid #ccc');
                },1000);
            }
            if(inputpasswordold=='' || inputpasswordold==null){
                $('#modal_changepass #inputpasswordold').css('border','1px solid red');
        setTimeout(function (args) {
                    $('#modal_changepass #inputpasswordold').css('border','1px solid #ccc');
                },1000);
            }
        
    }
    setTimeout(function (args) {
            $('#btnchangepass').html('Submit').prop('disabled',false);
        },1000);
    
  }



//change password g-suite
 $(document).on('keyup','#modal_gsuite #inputpasswordreq',function () {
        countChargsuite();
    });

    $(document).on('blur','#modal_gsuite #inputpasswordreq',function () {
        countChargsuite();
    });

    function countChargsuite() {
        var c = $('#modal_gsuite #inputpasswordreq').val();
        var d = true;
        if(c.length>=8){
            d = false;
        }
        $('#modal_gsuite #inputpasswordrereq,#modal_gsuite #btn_gsuite').prop('disabled',d);
        $('#modal_gsuite #inputpasswordrereq').css('border','1px solid #ccc');
        $('#alertPass').html('');

    }

    $(document).on('keyup','#modal_gsuite #inputpasswordrereq',function () {
        checkPasswordgsuite();
    });

    $(document).on('blur','#modal_gsuite #inputpasswordrereq,#modal_gsuite #inputpasswordreq',function () {
        checkPasswordgsuite();
    });

    $(document).on('keypress','#modal_gsuite #inputpasswordrereq,#modal_gsuite #inputpasswordrereq',function (e) {
        if (e.which == 13) {
            changepassgsuite();
            return false;   
        }
    });

    function checkPasswordgsuite() {
        var inputpasswordreq = $('#modal_gsuite #inputpasswordreq').val();
        var inputpasswordrereq = $('#modal_gsuite #inputpasswordrereq').val();

        if(inputpasswordreq!='' && inputpasswordreq!=null && inputpasswordrereq!='' && inputpasswordrereq!=null){
            if(inputpasswordreq==inputpasswordrereq){
                $('#modal_gsuite #btn_gsuite').prop('disabled',false);
                $('#modal_gsuite #inputpasswordrereq').css('border','1px solid green');
                $('#modal_gsuite #alertPass').html('<i style="color: green;">Match</i>');
            } else {
                $('#modal_gsuite #btn_gsuite').prop('disabled',true);
                $('#modal_gsuite #inputpasswordrereq').css('border','1px solid red');
                $('#modal_gsuite #alertPass').html('<i style="color: red;">Not match</i>');
            }
        }

    }

  $('#btn_gsuite').click(function () {
        changepassgsuite();

    });

   async function changepassgsuite()
  {
     loading_button('#btn_gsuite');

    var inputpasswordreq = $('#modal_gsuite #inputpasswordreq').val();
    var inputpasswordrereq = $('#modal_gsuite #inputpasswordrereq').val();
    var nip = $("#nip").val();
    if (inputpasswordreq!='' && inputpasswordreq!=null && inputpasswordrereq!='' && inputpasswordrereq!=null) {
        if(inputpasswordreq==inputpasswordrereq){
                // Cek apakah password < 8 karakter
                if(inputpasswordreq.length>=8){
                    var boolValidation = true;
                    var dataShareMenu = {
                      nip : nip,
                      pass:inputpasswordreq,
                    };
                
                    var dataAjax = {
                      action : 'changegsuitepass',
                      dataShareMenu : dataShareMenu,
                    }
                    var token = jwt_encode(dataAjax,'UAP)(*');
                    var url = "<?php echo base_url('change-password'); ?>";
                    try{
                      var ajax = await AjaxSubmitFormPromises(url,token);
                        
                      if(ajax['status'] == 1){
                        
                        toastr.success('Password reset request was successful','Success');
                            setTimeout(function () {
                               window.location.href="<?php echo base_url('profile/self-services/'); ?>"+nip;
                            },1000);
                       
                      }
                      else
                      {
                        alert(ajax['msg']);
                      }
                    
                    }
                    catch(err){
                      toastr.info('something error');
                    }
                }
                else {
                    toastr.error('Password less than 8 character','Error');
                }
            } else {
                toastr.error('Password and Re-password not match','Error');
            }
    }else{

        if(inputpasswordreq=='' || inputpasswordreq==null) {
               $('#modal_gsuite #inputpasswordreq').css('border','1px solid red');
        setTimeout(function (args) {
                    $('#modal_gsuite #inputpasswordreq').css('border','1px solid #ccc');
                },1000);
            }

            if(inputpasswordrereq=='' || inputpasswordrereq==null){
                $('#modal_gsuite #inputpasswordrereq').css('border','1px solid red');
        setTimeout(function (args) {
                    $('#modal_gsuite #inputpasswordrereq').css('border','1px solid #ccc');
                },1000);
            }
        
    }
    setTimeout(function (args) {
            $('#btn_gsuite').html('Submit').prop('disabled',false);
        },1000);
    
  }


</script>