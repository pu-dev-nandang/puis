<div id="company-form">
	<div class="row">
		<div class="col-sm-12" style="margin-bottom:10px">
			<div class="btn-group">
				<button class="btn btn-sm btn-warning btn-back" type="button"><i class="fa fa-chevron-left"></i> back to list</button>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title"><i class="fa fa-edit"></i> Form Company</h4>
				</div>
				<div class="panel-body">
					<form id="form-company" action="<?=site_url('human-resources/master_insurance_company/save')?>" method="post" autocomplete="off">
						<input type="hidden" name="ID" value="<?=(!empty($detail) ? $detail->ID : '')?>">
						<div class="form-group">
							<label>Company Name</label>
							<input type="text" name="Name" class="form-control required" required value="<?=(!empty($detail) ? $detail->Name : '')?>" placeholder="PT. Company Name">
							<small class="text-danger text-message"></small>
						</div>
						<div class="form-group">
							<label>Address</label>
							<textarea name="Address" class="form-control required" required placeholder="Jl. name of road, Villages, City/Distrik, Province, Postal Code"><?=(!empty($detail) ? $detail->Address : null)?></textarea>
							<small class="text-danger text-message"></small>
						</div>
						<div class="form-group">
							<label>Additional Info</label>
							<textarea name="Address" class="form-control" placeholder="Contact number/mail/fax"><?=(!empty($detail) ? $detail->AdditionlInfo : null)?></textarea>
							<small class="text-danger text-message"></small>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<div class="form-group">
									<label>Category</label>
									<select class="form-control required" required readonly name="Category" >
										<option value="insurance" selected>Insurance</option>
									</select>
									<small class="text-danger text-message"></small>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="form-group">
									<label>Is Active</label>
									<select class="form-control required" required name="Category" >
										<option value="">Choose One</option>
										<option value="1" <?=(!empty($detail) ? (($detail->isActive == 1) ? 'selected':'') : '')?> >Yes</option>
										<option value="0" <?=(!empty($detail) ? (($detail->isActive == 0) ? 'selected':'') : '')?> >No</option>
									</select>
									<small class="text-danger text-message"></small>
								</div>	
							</div>
						</div>
						<div class="form-group">
							<button type="button" class="btn btn-sm btn-default btn-back">Cancel</button>
							<button class="btn btn-sm btn-primary btn-save" type="button">Save changes</button>
						</div>
					</form>
				</div>
			</div>
			
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(".btn-back").click(function(){
			loading_modal_show();
			location.reload(true);
		});
	});
</script>