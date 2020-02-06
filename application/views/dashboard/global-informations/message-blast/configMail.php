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
		<div class="col-sm-12" style="margin-bottom:10px">
			<a class="btn btn-warning" href="<?=site_url('global-informations/message-blast')?>" ><i class="fa fa-angle-double-left"></i> Back</a>
		</div>
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title"><i class="fa fa-edit"></i><?=$title?></h4>
				</div>
				<div class="panel-body">
					<form id="form-config-mail" action="" method="post" autocomplete="off">
						<input type="hidden" name="ID" value="<?=(!empty($result) ? $result->ID : null)?>">
						<div class="row">
							<div class="col-sm-3">
								<h4>SMTP Mail</h4>	
								<div class="row">
									<div class="col-sm-8">
										<div class="form-group">
											<label>SMTP HOST</label>
											<input type="text" class="form-control required" required name="smtp_host" value="<?=(!empty($result) ? $result->smtp_host : null)?>">
											<span class="text-danger text-message"></span>
										</div>		
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label>PORT</label>
											<input type="text" class="form-control required" required name="smtp_port" value="<?=(!empty($result) ? $result->smtp_port : null)?>">
											<span class="text-danger text-message"></span>
										</div>		
									</div>
								</div>
								<div class="form-group">
									<label>SMTP MAIL</label>
									<input type="text" class="form-control required" required name="smtp_mail" value="<?=(!empty($result) ? $result->smtp_mail : null)?>">
									<span class="text-danger text-message"></span>
								</div>								
								<div class="form-group">
									<label>SMTP MAIL PASSWORD</label>
									<input type="text" class="form-control required" required name="smtp_mail_pass" value="<?=(!empty($result) ? $result->smtp_mail_pass : null)?>">
									<span class="text-danger text-message"></span>
								</div>
							</div>
							<div class="col-sm-3">
								<h4>Default Mail Sender</h4>
								<div class="form-group">
									<label>Mail Label</label>
									<input type="text" class="form-control required" required name="mail_from_label" value="<?=(!empty($result) ? $result->mail_from_label : null)?>">
									<span class="text-danger text-message"></span>
								</div>
								<div class="form-group">
									<label>Mail</label>
									<input type="text" class="form-control required" required name="mail_from" value="<?=(!empty($result) ? $result->mail_from : null)?>">
									<span class="text-danger text-message"></span>
								</div>

								
							</div>
							<div class="col-sm-3">
								<h4>Mail Blast Default</h4>
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
							</div>
						</div>	
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label>Template Mail</label>
									<div class="tabulasi">
										<ul class="nav nav-tabs" role="tablist">
											<li role="presentation"><a href="#editor" aria-controls="editor" role="tab" data-toggle="tab">Editor</a></li>
										    <li role="presentation" class="active"><a href="#preview" aria-controls="preview" role="tab" data-toggle="tab">Preview</a></li>
									    </ul>
									    <div class="tab-content">
										    <div role="tabpanel" class="tab-pane" id="editor">
										    	<textarea class="form-control required" rows="10" required name="template_message"><?=(!empty($result->template_message) ? $result->template_message : null)?></textarea>
										    </div>
										    <div role="tabpanel" class="tab-pane active" id="preview">
										    	<div class="prev-editor"><?=(!empty($result->template_message) ? $result->template_message : null)?></div>
										    </div>
									  	</div>
									</div>									
								</div>
							</div>
						</div>	
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<button class="btn btn-primary btn-submit" type="button">Save changes</button>
									<a class="btn btn-default" href="<?=site_url('global-informations/message-blast')?>" >Cancel</a>
								</div>
							</div>
						</div>
					</form>				
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		$(".btn-submit").click(function(){
			var error = false;
			var itsme = $(this);
			var itsform = itsme.parent().parent().parent().parent();
		 	itsform.find(".required").each(function(){
			  	var value = $(this).val();
			  	if($.trim(value) == ''){
			  		$(this).addClass("error");
			  		$(this).parent().find(".text-message").text("Please fill this field");
			  		error = false;
			  		console.log($(this).attr("name")+"="+value);
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