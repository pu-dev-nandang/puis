<style type="text/css">
	#message-blast .heading > h2{margin-top: 0px}
	#message-blast .fetch-message .messages > .middle > .list{overflow: auto;max-height: 50em;padding-top: 10px}
	#message-blast .fetch-message{padding: 10px;border-bottom:1px solid #ddd;}
</style>
<?php $message = $this->session->flashdata('message');
	if(!empty($message)){ ?>
	<script type="text/javascript">
	$(document).ready(function(){
		toastr.info("<?= $this->session->flashdata('message');?>",'Info!');
	});
	</script>
<?php } ?>
<div id="message-blast">
	<div class="row">
		<div class="col-sm-12">
			<div class="main-ctn">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-sm-6">
								<a class="btn btn-primary btn-new-msg" href="<?=site_url('global-informations/message-blast/new')?>"><i class="fa fa-edit"></i> Create New Message</a>
								<button class="btn btn-info" type="button" type="button" data-toggle="collapse" data-target="#coll-filter" aria-expanded="false" aria-controls="coll-filter"><i class="fa fa-filter"></i> Filter</button>
							</div>
							<div class="col-sm-6">
								<div class="pull-right">
									<div class="dropdown">
									  <button id="drpConfig" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									    <i class="fa fa-cog"></i>
									  </button>
									  <ul class="dropdown-menu pull-right" aria-labelledby="drpConfig">
									    <li><a href="<?=site_url('global-informations/subject-type')?>"><i class="fa fa-list-alt"></i> Subject Type</a></li>
									  </ul>
									</div>
								</div>								
							</div>
							
						</div>
					</div>
					<div class="panel-body collapse" style="padding:0px" id="coll-filter">
						<div class="fetch-message">
							<div class="row">
								<div class="col-sm-3">
									<div class="messages">
										<div class="top">
											<form id="form-filter" action="" method="post" autocomplete="off">
												<div class="row">
													<div class="col-sm-6">
														<div class="src-msg">
															<div class="text-right">
																<input type="text" name="keywords" placeholder="Search here" class="form-control">
															</div>
														</div>		
													</div>
													<div class="col-sm-6">
														<div class="form-group" style="margin-bottom:0px">
															<select class="form-control" name="sort_label">
																<option value="">Sort by</option>
																<option value="mail_to">Receiver</option>
																<option value="SubjectOth">Subject</option>
																<option value="MessageOth">Message</option>
																<option value="created">Date</option>
															</select>
														</div>	
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>								
							</div>
						</div>
					</div>
					<div class="panel-body" style="padding:0px">
						<div class="load-list-mail"><center><i class="fa fa-circle-o-notch fa-spin"></i><center></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	function fetchMyMail() {
		var filtering = $("#form-filter").serialize();
        var token = jwt_encode({Filter : filtering},'UAP)(*');
      	$.ajax({
		    type : 'POST',
		    url : base_url_js+"global-informations/message-blast/mymail",
		    data : {token:token},
		    dataType : 'html',
		    beforeSend :function(){
		    	//loading_modal_show();		    	
		    	$("body #message-blast .load-list-mail").html("<div class='text-center'><i class='fa fa-circle-o-notch fa-spin'></i></div>");
		    },error : function(jqXHR){
            	$("body #message-blast .load-list-mail").html("<div class='text-center'><i class='fa fa-times'></i> fetching error</div>");
            	$("body #modalGlobal .modal-body").html(jqXHR.responseText);
	      	  	$("body #modalGlobal").modal("show");
		    },success : function(response){
		    	//loading_modal_hide();
		    	$("body #message-blast .load-list-mail").html(response);
		    }
		});
	}
	$(document).ready(function(){
		fetchMyMail();
		$("#message-blast input[name=keywords]").on("keyup",function(){
			var value = $(this).val();
			if($.trim(value).length >0){
				fetchMyMail();
			}
		});
		$("#message-blast select[name=sort_label]").on("change",function(){
			var value = $(this).val();
			if($.trim(value).length >0){
				fetchMyMail();
			}
		});
		$("#message-blast").on("click",".mailing-list .btn-remove-mail",function(){
			var itsme = $(this);
			var mailID = itsme.data("mail");
			if(mailID != 0){
				if(confirm("Are you sure wants to remove this message ?")){
					reqDelete(mailID);
				}
			}			
		});
	});

	function reqDelete(mailID) {
		var data = {
          MAILID : mailID,
      	};
      	var token = jwt_encode(data,'UAP)(*');	
      	$.ajax({
		    type : 'POST',
		    url : base_url_js+"global-informations/message-blast/removeMail",
		    data : {token:token},
		    dataType : 'json',
		    beforeSend :function(){
		    	loading_modal_show();		    	
		    },error : function(jqXHR){
		    	loading_modal_hide();
            	$("body #modalGlobal .modal-body").html(jqXHR.responseText);
	      	  	$("body #modalGlobal").modal("show");
		    },success : function(response){
		    	loading_modal_hide();
		    	toastr.info(response.message);
		    	fetchMyMail();
		    }
		});
	}
</script>


