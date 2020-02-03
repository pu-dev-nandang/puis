<?php $message = $this->session->flashdata('message');
	if(!empty($message)){ ?>
	<script type="text/javascript">
	$(document).ready(function(){
		toastr.info("<?= $this->session->flashdata('message');?>",'Info!');
	});
	</script>
<?php } ?>
<div id="config-mail">
	<div class="row">
		<div class="col-sm-12">
			<form id="form-config-mail" action="" method="post" autocomplete="off">
				<input type="hidden" name="ID" value="<?=(!empty($result) ? $result->ID : null)?>">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"><i class="fa fa-edit"></i><?=$title?></h4>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-2">
								<div class="form-group">
									<label>Maximum limit receiver mail</label>
									<input type="text" class="form-control required" required name="limit" value="<?=(!empty($result) ? $result->limit : null)?>">
									<span class="text-danger text-message"></span>
								</div>
								<div class="form-group">
									<label>Default Mail BCC</label>
									<input type="text" class="form-control required" required name="mail_bcc" value="<?=(!empty($result) ? $result->mail_bcc : null)?>">
									<span class="text-danger text-message"></span>
								</div>
								<div class="form-group">
									<button class="btn btn-primary btn-submit" type="button">Save changes</button>
									<a class="btn btn-default" href="<?=site_url('global-informations/message-blast')?>" >Cancel</a>
								</div>
							</div>
						</div>						
					</div>
				</div>
			</form>
		</div>
	</div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		$(".btn-submit").click(function(){
			var error = false;
			var itsme = $(this);
			var itsform = itsme.parent().parent();
		 	itsform.find(".required").each(function(){
			  	var value = $(this).val();
			  	if($.trim(value) == ''){
			  		$(this).addClass("error");
			  		$(this).parent().find(".text-message").text("Please fill this field");
			  		error = false;
			  	}else{
			  		error = true;
			  		$(this).removeClass("error");
			  		$(this).parent().find(".text-message").text("");
			  	}
		  	});
		 	
		 	var totalError = itsform.find(".error").length;
		  	if(error && totalError == 0){
		  		if(confirm("Are you sure wants to save this ?")){
			  		loading_modal_show();
					$("#form-config-mail")[0].submit();
		  		}
		  	}else{
		  		alert("Please fill out the field");
		  	}
		});
	});
</script>