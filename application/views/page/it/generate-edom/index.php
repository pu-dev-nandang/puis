<div id="generate-edom">
	<div class="row">
		<div class="col-sm-6 col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<i class="fa fa-tasks"></i> Generate Edom
					</h4>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-4">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<i class="fa fa-cog"></i> Form Request Generate
									</h4>
								</div>
								<div class="panel-body">
									<form id="form-generate" action="<?=site_url('it/generate-edom/request')?>" method="post" autocomplete="off">
										<div class="form-group">
											<label>Semester</label>
											<select class="form-control required" required name="semester">
												<option value="">Choose one</option>
												<?php if(!empty($semester)){ 
												foreach ($semester as $s) {
													echo '<option value="'.$s->ID.'#'.$s->Year.'">'.$s->Name.'</option>';
												} } ?>
											</select>
											<small class="text-danger text-message"></small>
										</div>
										<div class="btn-group">
											<button class="btn btn-sm btn-primary btn-request" type="button">Generate Now</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="col-sm-8">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title"><i class="fa fa-bars"></i> Table List</h4>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th width="2%">No</th>
													<th>Table Name</th>
													<th>Last Generate</th>
												</tr>
											</thead>
											<tbody>
												<?php if(!empty($edoms)){ $no=1; 
												foreach ($edoms as $e) { ?>
												<tr>
													<td><?=$no++?></td>
													<td><?=$e->TableName?></td>
													<td><?=$e->LastUpdated?></td>
												</tr>
												<?php } }else{echo "<tr><td colspan='3'>Empty Results</td></tr>";} ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#form-generate").on("click",".btn-request",function(){
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
		  	if(error && totalError == 0 ){
		  		var itsme = $(this);
	    		var Semester = itsform.find("select[name=semester]").val();
				var data = {
	              Semester : Semester,
	          	};
	          	var token = jwt_encode(data,'UAP)(*');
	          	$.ajax({
				    type : 'POST',
				    url : itsform.attr("action"),
				    data : {token:token},
				    dataType : 'json',
				    beforeSend :function(){loading_modal_show();},
		            error : function(jqXHR){
		            	loading_modal_hide();
		            	$("body #modalGlobal .modal-body").html(jqXHR.responseText);
			      	  	$("body #modalGlobal").modal("show");
				    },success : function(response){
				    	loading_modal_hide();
				    	location.reload();
				    }
				});
		  	}else{
		  		alert("Please fill out the field.");
		  	}
		});

		function getFormData($form){
		    var unindexed_array = $form.serializeArray();
		    var indexed_array = {};

		    $.map(unindexed_array, function(n, i){
		        indexed_array[n['name']] = n['value'];
		    });

		    return indexed_array;
		}
	});
</script>