<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>Podomoro University</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('images/icon/favicon.png'); ?>">
</head>
<?php echo $include; ?>

<body style="background: #f2f2f2;">
<div class="container" >
	<div class="row">
		<div id="login-overlay" class="modal-dialog center" style="z-index:0;max-width: 405px;">
		    <div class="modal-content">
		        <div class="modal-body" style="padding-bottom:0px;">
		            <div class="row">
		                <div class="col-xs-12" style="text-align: center;">
		                    <img src="<?php echo url_sign_out ?>assets/icon/logo.jpg" style="max-width: 200px;">
		                    <h2> Autentifikasi</h2>
		                    <hr/>
		                </div>
		            </div>

		            <div class="row" id = "contentIsi">
		                <div class="col-md-6">
		                	<div class="form-group">
		                	<label>Username</label>
		                	<input type="text" id = "Username" class="form-control">
		                	</div>
		                </div>
		                <div class="col-md-6">
		                	<div class="form-group">
		                	<label>Password</label>
		                	<input type="password" id = "Password" class="form-control">
		                	</div>
		                </div>
		            </div>
		            <div class="row">
		            	<div class="col-md-12">
		            		<button class="btn btn-primary" id = "btnSave" tokendata = "<?php echo $tokendata ?>">Submit</button>
		            	</div>
		            </div>
		            <div class="row">
		                <div class="col-xs-12" style="text-align: center;font-size: 12px;color: #9E9E9E;">
		                    <hr style="margin-bottom:10px;" />
		                    <p>© 2018 Universitas Agung Podomoro
		                        <br/> Version 2.0.1
		                    </p>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</div>
</body>
</html>
<script type="text/javascript">
	// console.log(document.URL);
	var Notification = <?php echo $Notification ?>;
	if (Notification == 1) {
		toastr.error('You are not authorize','!Failed');
	}
	
	$(document).ready(function(){
		$('#Username').focus();
	})

	$(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
	    var tokendata = $(this).attr('tokendata');
	    var selector = $(this);
	    loading_button2(selector);
	    var Username = $('#Username').val();
	    var Password = $('#Password').val();
	    var getCurrentURL = document.URL;
	    var url = getCurrentURL;
	
	    FormSubmitAuto(url, 'POST', [
	        { name: 'Username', value: Username },
	        { name: 'Password', value: Password },
	        { name: 'tokendata', value: tokendata },
	    ],'');
	})
</script>