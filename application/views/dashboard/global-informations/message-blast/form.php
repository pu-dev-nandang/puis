<style type="text/css">
.find-participants{cursor: pointer;padding: 5px}
.box-mail{border:1px solid #ccc;box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;}	
.box-mail .list-mail,.box-mail .act-find, .box-mail .act-show-list{padding: 5px;}
.box-mail .list-mail,.box-mail .act-find, .box-mail .act-show-list, .form-control{height: 35px;width: 100%}
.box-mail .act-find{text-align: center;background: #ddd;border-left: 1px solid #ccc;line-height: 2;}
.box-mail .list-mail{overflow: auto;}
.box-mail .act-show-list{text-align: right;background: red}
.bg-mail{background: #9ee4dead; padding: 2px; margin: 1px 2px;border-radius: 5px;float: left}
.bg-mail > .remove-mail{color: #989090;cursor: pointer}
</style>
<div id="message-blast">
	<div class="row">
		<div class="col-sm-12">
			<div class="create-message">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-sm-6">
								<h4 class="panel-title"><?=$title?></h4>								
							</div>
							<div class="col-sm-6 text-right">
								<!-- <button class="btn btn-xs btn-default btn-draft" title="Save as Draft" type="button"><i class="fa fa-bookmark"></i> Save as draft</button> -->
								<button class="btn btn-xs btn-danger btn-discard" type="button"><i class="fa fa-trash"></i> Discard</button>
							</div>
						</div>
					</div>
					<div class="panel-body">
						<div class="frm-msg">
							<form id="form-submit-mail" action="<?=site_url('global-informations/message-blast/send')?>" method="post" autocomplete="off">
								<div class="form-group">
									<label>From</label>
									<input type="text" name="mail_from" value="pu@podomorouniversity.ac.id" readonly class="form-control readonly required" required>
									<small class="text-danger text-message"></small>
								</div>
								<div class="form-group">
									<label>To</label>
									<div class="box-mail receiver" data-target="receiver">
										<div class="row">
											<div class="col-sm-10 col-md-9 left">
												<div class="list-mail"></div>
											</div>
											<div class="col-sm-2 col-md-3 right">
												<div class="act-find find-participants">
													<span><i class="fa fa-search"></i> Find participants</span>																									
												</div>
											</div>
										</div>
									</div>
									<small class="text-danger text-message"></small>										
								</div>
								<div class="form-group">
									<label>CC</label>
									<div class="box-mail receiver-cc" data-target="receiver-cc">
										<div class="row">
											<div class="col-sm-10 col-md-9 left">
												<div class="list-mail"></div>
											</div>
											<div class="col-sm-2 col-md-3 right">
												<div class="act-find find-participants">
													<span><i class="fa fa-search"></i> Find participants</span>																									
												</div>
											</div>
										</div>
									</div>
									<small class="text-danger text-message"></small>
								</div>
								
								<div class="form-group">
									<label>BCC</label>
									<div class="box-mail receiver-bcc" data-target="receiver-bcc">
										<div class="row">
											<div class="col-sm-10 col-md-9 left">
												<div class="list-mail"></div>
											</div>
											<div class="col-sm-2 col-md-3 right">
												<div class="act-find find-participants">
													<span><i class="fa fa-search"></i> Find participants</span>																									
												</div>
											</div>
										</div>
									</div>
									<small class="text-danger text-message"></small>
								</div>

								<div class="form-group">
									<label>Type of Subject</label>
									<select class="form-control required" name="typeSubject" id="subjectType" required>
										<option value="">-Choose one-</option>	
										<?php if(!empty($subject)){
										foreach ($subject as $s) {
											echo "<option value='".$s->ID."'>".$s->subject."</option>";
										} } ?>
									</select>
									<small class="text-danger text-message"></small>
								</div>
								<div class="form-group">
									<label>Subject</label>
									<input type="text" name="subject" class="form-control required subject-field" required>
									<small class="text-danger text-message"></small>
								</div>
								<div class="form-group">
									<label>Message</label>
									<textarea class="form-control required subject-field" required name="message" id="message-blst"></textarea>
									<small class="text-danger text-message"></small>
								</div>
								<div class="form-group">
									<button class="btn btn-primary btn-sm btn-submit" type="button"><i class="fa fa-paper-plane-o"></i> Send</button>
									<button class="btn btn-default btn-sm btn-discard" type="reset">Discard</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="modal-participants" class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document"style="width:80%">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Add Participants</h4>
	      </div>
	      <div class="modal-body"></div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        	<button type="button" class="btn btn-primary btn-add-participants">Add participants</button>
	      </div>
	    </div>
	  </div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#message-blst').summernote({
            placeholder: 'Text your announcement',
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
		

		$("#form-submit-mail").on("click",".find-participants",function(){
			var itsme = $(this);
			var textParent = itsme.parent().parent().parent().data("target");
            $("body #modal-participants").modal("show");
            $.ajax({
			    type : 'POST',
			    url : base_url_js+"global-informations/message-blast/formParticipants",
			    dataType : 'html',
			    beforeSend :function(){
			    	$("body #modal-participants .modal-body").html("<i class='fa fa-circle-o-notch fa-spin'></i> fetching data..");
			    },error : function(jqXHR){
	            	$("body #modal-participants .modal-body").html(jqXHR.responseText);
			    },success : function(response){
			    	$("body #modal-participants .modal-header .modal-title").text("Add participants for "+textParent);
			    	$("body #modal-participants .modal-body").html(response);
			    	$("body #modal-participants .modal-body").append("<input type='hidden' name='belongsto' value='"+textParent+"'> ");
			    }
			});
		});


		$("body #modal-participants").on("click",".btn-add-participants",function(){
			var itsme = $(this);
			var belongsto = $("body #modal-participants .modal-body input[name=belongsto]").val();
			var participantMail = [];
			if(belongsto.length > 1){
			/* check if there's no list of email */
			/* get the external mail list*/
				$formParticipants = $("body #form-participants");
				$extMails = $formParticipants.find(".ext-mailing-list > input[name=extmail]");
				var totalExtMailError = $extMails.hasClass("error");
				if(totalExtMailError > 0){
					alert("External email address participants type is incorrect. Try again.");
				}else{
					$extMails.each(function () {
						var value = $(this).val();
						value = $.trim(value);
						if(value.length > 0){
					    	participantMail.push({name:value,email:value});
						}
					});
				}

			/*get the internal mail*/
				$internalCheckbox = $("body #modal-participants").find("#internal-participants .chk-child");
				$internalCheckbox.each(function() {
					if( $(this).is(":checked") ){
						var email = $(this).val();
						var name = $(this).data("name");
						email = $.trim(email);
						name = $.trim(name);
						if(name.length > 0 && email.length > 0){
							participantMail.push({name:name,email:email});
						}
					}
				});

				appendEmailTOReceiver(belongsto,participantMail);

			}
		});


		$("#form-submit-mail").on("click",".remove-mail",function(){
			if(confirm("Are you sure wants to remove this mail ?")){
				$(this).parent().remove();
			}
		});


		function appendEmailTOReceiver(destination,mails) {
			if(destination.length > 0 && mails.length > 0){
				var storedData = "";
				$.each(mails,function(key,value){
					var isExist = $("body #message-blast #form-submit-mail .box-mail."+destination+" .list-mail > .bg-mail").hasClass('mail-'+destination+'-'+value.email+'');
					if(!isExist){
						storedData += '<div class="bg-mail mail-'+destination+'-'+value.email+'">'+
								      '<input type="hidden" class="name" value="'+value.name+'" name="main_'+destination+'_to_name[]">' +
									  '<input type="hidden" class="mail" value="'+value.email+'" name="mail_'+destination+'_to[]">'+
									  '<span class="mail mail-'+key+'" title="'+value.name+'" >'+value.email+'</span> <span class="remove-mail"><i class="fa fa-times"></i></span></div>';
					}
				});
				
				$("body #message-blast #form-submit-mail .box-mail."+destination+" .list-mail").append(storedData);
				var TotalMails = $("body #message-blast #form-submit-mail .box-mail."+destination+" .list-mail .bg-mail").length;
				$("body #message-blast #form-submit-mail .box-mail."+destination).next().text("Total: "+(TotalMails)+" participant mails");
				$formParticipants = $("body #form-participants");
				$extMails = $formParticipants.find(".ext-mailing-list > input[name=extmail]");
				$extMails.val("");
				$extMails.not("input[name=extmail]:first").remove();
				toastr.info('Successfully added to '+destination.toUpperCase());
				
			}else{
				toastr.warning('Internal server error. Failed add participants mail from '+destination.toUpperCase());
				//alert("Internal server error. Failed add participants mail from "+destination.toUpperCase());
			}
		}


		$(".btn-discard").click(function(){
			if(confirm("Are you sure wants to DISCARD this message ?")){
				$(location).attr("href","<?=site_url('global-informations/message-blast')?>");
			}
		});


		$("#subjectType").change(function(){
			var ID = $(this).val();
			if($.trim(ID).length > 0){
				var data = {
	          		ID : ID
	          	};
	          	var token = jwt_encode(data,'UAP)(*');
				$.ajax({
				    type : 'POST',
				    url : base_url_js+"global-informations/subject-type/getSubjectTypeID",
				    data : {token:token},
				    dataType : 'json',
				    beforeSend :function(){},
				    error : function(jqXHR){
		            	$('body #GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
	                        '<h4 class="modal-title">Error Fetch Student Data</h4>');
	                    $('body #GlobalModal .modal-body').html(jqXHR.responseText);
	                    $('body #GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
	                    $('body #GlobalModal').modal({
	                        'show' : true,
	                        'backdrop' : 'static'
	                    });
				    },success : function(response){
				    	if(!jQuery.isEmptyObject(response)){
				    		$("#form-submit-mail input[name=subject]").val(response.subject);
				    		var receiver = $("#form-submit-mail .box-mail.receiver .list-mail .bg-mail").length;
				    		var dearDestination = "Dear ";
				    		if(receiver > 0 ){
				    			if(receiver == 1){
				    				var nameReceiver = $("#form-submit-mail .box-mail.receiver .list-mail .bg-mail:first > .name").val();
				    				dearDestination += nameReceiver+",";
				    			}else{
				    				dearDestination += "All,";
				    			}
				    		}else{dearDestination="Dear,"}
				    		var message = "<p>"+dearDestination+"</p>"+response.template;
				    		$('#message-blst').summernote("code",message);
				    	}
				    }
				});
			}else{
				$("#form-submit-mail .subject-field").val("");
				$('#message-blst').summernote("code","");
			}
		});


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
		 	var totalReceiver = $("#form-submit-mail .box-mail.receiver .list-mail .bg-mail").length;

		  	if(error && totalError == 0 && totalReceiver > 0){
		  		loading_modal_show();
				$("#form-submit-mail")[0].submit();
		  	}else{
		  		alert("Please fill out the field"+((totalReceiver == 0) ? " and participants.":"") );
		  		if(totalReceiver == 0){
		  			$(".box-mail.receiver").next().text("Please fill this field");
		  		}
		  	}
		});

	});
</script>