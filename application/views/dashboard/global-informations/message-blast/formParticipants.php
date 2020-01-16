<div id="participants-frm">
	<form id="form-participants" method="post" autocomplete="off">
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">External Participants</h4>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-8">
								<div class="form-group ext-mailing-list">
									<label>Email</label>
									<input type="text" name="extmail" class="form-control" placeholder="name@domain.com" >
								</div>
							</div>
							<div class="col-sm-4 text-right">
								<label>Action</label>
								<div class="form-group ext-action">
									<div class="btn-group">
										<button class="btn btn-default btn-plus" title="Add new mail" type="button"><i class="fa fa-plus"></i></button>
										<button class="btn btn-default btn-minus" title="Remove the last mail" type="button"><i class="fa fa-minus"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>		
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">Internal Participants</h4>
					</div>
					<div class="panel-body">
						<div class="participant-ctn">
							<div class="category-mail">
								<div class="row form-group">
									<label class="col-sm-2">Type Participant</label>
									<div class="col-sm-2">
										<select class="form-control" name="type_participant">
											<option value="">-Choose type-</option>
											<option value="Student">Student</option>
											<option value="Lecturer">Lecturer</option>
											<option value="Employee">Employee</option>
										</select>
									</div>
								</div>
							</div>
							<div class="filter-participant">-</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$formparticipants = $("#form-participants");
		$formparticipants.on("keyup","input[name=extmail]",function(){
			var value = $(this).val();
			console.log(value);
			var validasiMail = isValidEmailAddress( value );
			if( !validasiMail ){
				$(this).addClass("error");
				$(this).css({border:'1px solid red'});
			}else{
				$(this).removeAttr("style");
				$(this).removeClass("error");
			}
		});
		$formparticipants.on("click",".btn-plus",function(){
			var firstMail = $formparticipants.find("input[name=extmail]:first");
			if(!firstMail.hasClass("error")){
				var cloneMail = $formparticipants.find("input[name=extmail]:first").clone();
				cloneMail.val("");
				$formparticipants.find(".ext-mailing-list").append(cloneMail);
			}
		});
		$formparticipants.on("click",".btn-minus",function(){
			var countMail = $formparticipants.find("input[name=extmail]");
			if(countMail.length > 1){
				$formparticipants.find("input[name=extmail]:last").remove();				
			}
		});

		function isValidEmailAddress(emailAddress) {
		    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
		    return pattern.test(emailAddress);
		}

		$formparticipants.on("change","select[name=type_participant]",function(){
			var value = $(this).val();
			alert(value);
		});		

	});
</script>