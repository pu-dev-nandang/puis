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
<div class="container" style="text-align: center;">
	<div class="row">
		<div id="login-overlay" class="modal-dialog center" style="z-index:0;max-width: 405px;">
		    <div class="modal-content">
		        <div class="modal-body" style="padding-bottom:0px;">

		            <div class="row">
		                <div class="col-xs-12" style="text-align: center;">
		                    <img src="<?php echo url_sign_out ?>assets/icon/logo.jpg" style="max-width: 200px;">
		                    <hr/>
		                </div>
		            </div>

		            <div class="row" id = "contentIsi">
		                <div class="col-md-12">
		                	<div class="form-group">
		                	<label>Reason</label>
		                	<input type="text" id = "reason" class="form-control">
		                	</div>
		                </div>
		            </div>
		            <div class="row">
		            	<div class="col-md-12">
		            		<button class="btn btn-danger" id = "cancel">Cancel</button>
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
<!-- Modal Notification -->
<div class="modal fade" id="NotificationModal" role="dialog" style="top: 100px;">
    <div class="modal-dialog" style="width: 400px;" role="document">
        <div class="modal-content animated flipInX">
            <!--            <div class="modal-header"></div>-->
            <div class="modal-body"></div>
            <!--            <div class="modal-footer"></div>-->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
	var t_booking = <?php echo json_encode($t_booking) ?>;
	$(document).ready(function(){
		$("#cancel").click(function(){
			if ($("#reason").val() == "") {
				toastr.error('Please fill The Reason','!Failed');
				return;
			}
			else
			{
				loadingStart();
				var url = base_url_js+"submitcancelvenue";
				var data = {
							Reason : $("#reason").val(),
							t_booking : t_booking,
							auth : 's3Cr3T-G4N',
							Code : "<?php echo $Code ?>",
							Approver : "<?php echo $Approver ?>",
						};
				var token = jwt_encode(data,'UAP)(*');
				$.post(url,{token:token},function (resultJson) {
					$("#contentIsi").html('<h3>Thank for your appreciated</h3>');
					$("#cancel").remove();
					var socket = io.connect( 'http://'+window.location.hostname+':3000' );
					// var socket = io.connect( '<?php echo serverRoot ?>'+':3000' );
					  socket.emit('update_schedule_notifikasi', { 
					    update_schedule_notifikasi: '1',
					    date : '',
					  });
					loadingEnd(1000);
				});
			}
		})
	});
</script>

