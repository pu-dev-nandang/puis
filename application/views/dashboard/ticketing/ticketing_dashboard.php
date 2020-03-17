<div class="row" style="margin-top: 10px;">
	<div class="col-xs-4">
		<div class="well">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Month</label>
						<select class="form-control"></select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Year</label>
						<select class="form-control"></select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-4" id = "table1">
		<div class="thumbnail">
			<div class="row">
				<div class="col-xs-12">
					<table class="table table-centre">
						<thead>
							<tr>
								<th>No</th>
								<th>Dept</th>
								<th>Tot</th>
								<th>Open</th>
								<th>Progress</th>
								<th>Closed</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-4" id = "table2">
		<div class="thumbnail">
			<div class="row">
				<div class="col-xs-12">
					<table class="table table-centre">
						<thead>
							<tr>
								<th>No</th>
								<th>Dept</th>
								<th>Tot</th>
								<th>Open</th>
								<th>Progress</th>
								<th>Closed</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-6" id = "pie1">
		
	</div>
	<div class="col-xs-6" id = "pie1">
		
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url('js/ticketing/Class_ticketing_dashboard.js'); ?>"></script>
<script type="text/javascript">
	let App_ticketing_dashboard;
	$(document).ready(function(e){
		App_ticketing_dashboard = new Class_ticketing_dashboard(ArrSelectOptionDepartment);
		App_ticketing_dashboard.LoadDefault();
	})
	
</script>