<div id="subject-type">
	<div class="row">
		<div class="col-sm-12">
			<a class="btn btn-sm btn-warning" href="<?=site_url('global-informations/message-blast')?>">
				<i class="fa fa-chevron-left"></i> Bact to message
			</a>
		</div>
		<div class="col-sm-12">
			<div class="panel panel-default"  style="margin-top:10px">
				<div class="panel-heading">
					<h4 class="panel-title">
						<i class="fa fa-filter"></i> Form Filter
					</h4>
				</div>
				<div class="panel-body">
					<form id="form-filter" action="" method="post" autocomplete="off">
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label>Subject</label>
									<input type="text" name="subject" placeholder="Subject or alternate subject" class="form-control">
								</div>
							</div>
							<div class="col-sm-2">
								<div class="form-group">
									<label>Status</label>
									<select class="form-control" name="status">
										<option value="">-Choose one-</option>
										<option value="1">Active</option>
										<option value="0">Not Active</option>
									</select>
								</div>	
							</div>
							<div class="col-sm-2" style="line-height:75px">
								<div class="form-group">
									<button class="btn btn-sm btn-primary" type="button"><i class="fa fa-search"></i> Search</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="panel panel-default"  style="margin-top:10px">
				<div class="panel-heading">
					<h4 class="panel-title">
						<i class="fa fa-bars"></i> List of subject type
					</h4>
				</div>
				<div class="panel-body">
					<div class="fetch-data-tables">
						<table class="table table-bordered" id="table-list-data">
							<thead>
								<tr>
									<th width="2%">No</th>
									<th>Subject</th>
									<th>Alternate Subject</th>
									<th>Status</th>
									<th colspan="3"></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="6">Empty data</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>

<script type="text/javascript">
	function fetchingSubject() {
		
	}
	$(document).ready(function(){

	});
</script>