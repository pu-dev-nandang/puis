<div id="subject-type-form">
	<div class="row" style="margin-bottom:10px">
		<div class="col-sm-12">
			<a class="btn btn-sm btn-warning" href="<?=site_url('global-informations/subject-type')?>">
				<i class="fa fa-chevron-left"></i> Bact to list
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">Form Subject Type</h4>
				</div>
				<div class="panel-body">
					<form id="form-subject-type" action="<?=site_url('global-informations/subject-type/save')?>" method="post" autocomplete="off">
					<input type="hidden" name="ID" value="<?=(!empty($detail) ? $detail->ID : null)?>">
						<div class="form-group">
							<label>Subject</label>
							<input type="text" name="subject" class="form-control required" required value="<?=(!empty($detail) ? $detail->subject : null)?>" placeholder="Subject message">
						</div>
						<div class="form-group">
							<label>Message</label>
							<textarea class="form-control required" required name="template" id="template-message"><?=!empty($detail) ? $detail->template : null?></textarea>
						</div>
						<div class="row form-group">
							<label class="col-sm-1">Active</label>
							<div class="col-sm-2">
								
							<select class="form-control required" required name="IsActive">
								<option value="">Choose One</option>
								<option value="1" <?=(!empty($detail) ? (($detail->IsActive==1) ? 'selected':'') : '')?> >Yes</option>
								<option value="0" <?=(!empty($detail) ? (($detail->IsActive==0) ? 'selected':'') : '')?> >No</option>
							</select>
							</div>
						</div>
						<div class="btn-group">
							<button class="btn btn-sm btn-primary btn-save" type="button">Save changes</button>
							<a class="btn btn-sm btn-default" href="<?=site_url('global-informations/subject-type')?>">
								Cancel
							</a>
						</div>

					</form>
				</div>
			</div>
			
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#template-message").summernote({
            placeholder: 'Text your message',
            tabsize: 2,
            height: 300,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ],
            callbacks: {
              onPaste: function (e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
                e.preventDefault();
                var div = $('<div />');
                div.append(bufferText);
                div.find('*').removeAttr('style');
                setTimeout(function () {
                  document.execCommand('insertHtml', false, div.html());
                }, 10);
              }
            }
    	});

		
		$("body #subject-type-form form").on("click",".btn-save",function(){
			var error = false;
			var itsme = $(this);
			var itsform = itsme.parent().parent().parent();
		 	itsform.find(".required").each(function(){
			  	var value = $(this).val();
			  	if($.trim(value) == ''){
			  		$(this).addClass("error");
			  		$(this).parent().find(".message-error").text("Please fill this field");
			  		error = false;
			  	}else{
			  		error = true;
			  		$(this).removeClass("error");
			  	}
		  	});
		 	
		 	var totalError = itsform.find(".error").length;
		  	if(error && totalError == 0 ){
		  		if(confirm("Are you sure wants to changes this information ?")){
					$(this).prop("disabled",true);
					$("body #form-subject-type")[0].submit();
		  		}
		  	}else{
		  		alert("Please check out your form again.");
		  	}
		});
	
	});
</script>