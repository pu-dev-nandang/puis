<style type="text/css">
	.find-participants{cursor: pointer;padding: 5px}
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
								<button class="btn btn-xs btn-default btn-draft" title="Save as Draft" type="button"><i class="fa fa-bookmark"></i> Save as draft</button>
								<button class="btn btn-xs btn-danger btn-remove" type="button"><i class="fa fa-trash"></i> Discard</button>
							</div>
						</div>
					</div>
					<div class="panel-body">
						<div class="frm-msg">
							<form id="form-submit-mail" action="" method="post" autocomplete="off">
								<div class="form-group">
									<label>From</label>
									<input type="text" name="from" value="it.support@podomorouniversity.ac.id" readonly class="form-control readonly required" required>
									<small class="text-danger text-message"></small>
								</div>
								<div class="form-group">
									<label>To</label>
									<div class="input-group">
										<input type="text" name="to" class="form-control required readonly" readonly required>
      									<div class="input-group-addon" style="padding:0px">
											<span class="find-participants"><i class="fa fa-search"></i> Find participants</span>
      									</div>
									</div>
									<small class="text-danger text-message"></small>										
								</div>
								<div class="form-group">
									<label>CC</label>
									<div class="input-group">
										<input type="text" name="cc" class="form-control readonly " readonly>
										<div class="input-group-addon" style="padding:0px">
											<span class="find-participants"><i class="fa fa-search"></i> Find participants</span>
      									</div>
									</div>
									<small class="text-danger text-message"></small>
								</div>
								<div class="form-group">
									<label>Subject</label>
									<input type="text" name="subject" class="form-control required" required>
									<small class="text-danger text-message"></small>
								</div>
								<div class="form-group">
									<label>Message</label>
									<textarea class="form-control required" required name="message" id="message-blst"></textarea>
									<small class="text-danger text-message"></small>
								</div>
								<div class="form-group">
									<button class="btn btn-primary btn-sm btn-submit" type="button"><i class="fa fa-paper-plane-o"></i> Send</button>
									<button class="btn btn-default btn-sm btn-reset" type="reset">Discard</button>
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
			var textParent = itsme.parent().prev().clone();
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
			    	$("body #modal-participants .modal-body").html(response);
			    }
			});
		});
	});
</script>