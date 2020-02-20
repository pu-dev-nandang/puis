<?php $message = $this->session->flashdata('message');
	if(!empty($message)){ ?>
	<script type="text/javascript">
	$(document).ready(function(){
		toastr.info("<?= $this->session->flashdata('message');?>",'Info!');
	});
	</script>
<?php } ?>

<div id="structur-organization">
	<div class="row">
		<div class="col-sm-3">
			<form id="form-sto-post" action="<?=base_url('human-resources/master-aphris/structure-organization-save')?>" method="post" autocomplete="off">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<i class="fa fa-edit"></i> Form Organization
						</h4>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label>Name</label>
							<input type="hidden" name="ID" class="form-control required sto-ID" required >
							<input type="text" name="heading" class="form-control required sto-heading" required >
							<small class="text-danger text-message"></small>
						</div>
						<div class="form-group">
							<label>Highest Node</label>
							<input type="text" name="title" class="form-control required sto-title" required >
							<small class="text-danger text-message"></small>
						</div>
						<div class="form-group">
							<label>Status</label>
							<select class="form-control required sto-isActive" required name="isActive">
								<option value="">Choose One</option>
								<option value="1">Active</option>
								<option value="0">Non Active</option>
							</select>
							<small class="text-danger text-message"></small>
						</div>
					</div>
					<div class="panel-footer text-right">
						<button class="btn btn-success btn-sm btn-submit" type="button">Save changes</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-sm-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<i class="fa fa-bars"></i> List of structure organization
					</h4>
				</div>
				<div class="panel-body">
					<div class="data-tables">
						<table class="table table-bordered" id="table-sto-list">
							<thead>
								<tr>
									<th width="5%">No</th>
									<th>Name</th>
									<th>Highest Node</th>
									<th>Status</th>
									<th width="10%"></th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($result)){ $no=1;
							foreach ($result as $v) { ?>
								<tr>
									<td><?=$no++?></td>
									<td><?=$v->heading?></td>
									<td><?=$v->title?></td>
									<td><?=($v->isActive == 1) ? "Active":"Not Active"?></td>
									<td>
										<div class="btn-groups">
											<button class="btn btn-warning btn-sm btn-edit" data-id="<?=$v->ID?>" type="button">Edit</button>
											<a class="btn btn-info btn-sm btn-view" href="<?=base_url('human-resources/master-aphris/structure-organization-view/'.str_replace(" ", "-", $v->heading).'/STOPU00'.$v->ID)?>">View</a>
										</div>
									</td>
								</tr>
							<?php } }else{ ?>
								<tr>
									<td colspan="4">Empty results</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$formSTO = $("#form-sto-post");
		$tableSTOList = $("#table-sto-list");
		$tableSTOList.on("click",".btn-edit",function(){
			var itsme = $(this);
			var ID = itsme.data("id");
			var data = {
	          ID : ID,
	      	};
	      	var token = jwt_encode(data,'UAP)(*');
	      	$.ajax({
			    type : 'POST',
			    url : base_url_js+"human-resources/master-aphris/structure-organization-detail",
			    data : {token:token},
			    dataType : 'json',
	            error : function(jqXHR){
	            	$("body #GlobalModal .modal-body").html(jqXHR.responseText);
		      	  	$("body #GlobalModal").modal("show");
			    },success : function(response){
	            	if(!jQuery.isEmptyObject(response)){
	            		$.each(response,function(k,v){
	            			$formSTO.find(".sto-"+k).val(v);
	            		});
	            	}else{alert("Failed fetch node.");}
			    }
			});
		});
		$formSTO.on("click",".btn-submit",function(){
			var error = false;
			var itsme = $(this);
			var itsform = itsme.parent().parent().parent().parent().parent();
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
		  		itsme.prop("disabled",true);itsme.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>');
		  		$formSTO[0].submit();
		  	}else{
		  		alert("Please fill out the field.");
		  	}
		});
	});
</script>