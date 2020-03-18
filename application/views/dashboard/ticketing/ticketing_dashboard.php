<div class="row" style="margin-top: 10px;">
	<div class="col-xs-6">
		<div class="thumbnail">
			<div class="row">
				<div class="col-xs-12">
					<div style="padding: 10px;text-align: center;">
						<h4 style="color: green;"><u>Ticket All</u></h4>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div class="well">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Month</label>
											<select class="form-control" id = "OpMonth"></select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Year</label>
											<select class="form-control" id = "OpYear"></select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-md-offset-4" style="text-align: center;">
										<div class="form-group">
											<label>Select View</label>
											<select class="form-control" id = "OpShowAll">
												<option value="1" selected>Table</option>
												<option value="2">Graph</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12">
							<div class="panel panel-primary" style="border-color: #42a4ca;">
								<div class="panel-heading clearfix" style="background-color: #42a4ca;border-color: #42a4ca;">
					                <h4 class="panel-title pull-left" style="padding-top: 7.5px;"></h4>
					            </div>
					            <div class="panel-body" id = "PageDashboardAll">
					            	<!-- <div class="row">
					            		<div class="col-xs-12">
					            			<div class="table-responsive">
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
					            	</div> -->	
					            </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-6" id = "tableDashboardToday">
		<div class="thumbnail">
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<div class="well">
						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<label>Select View</label>
									<select class="form-control" id = "OpShowToday">
										<option value="1" selected>Table</option>
										<option value="2">Graph</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="PageToday">
				
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url('js/ticketing/Class_ticketing_dashboard.js'); ?>"></script>
<script type="text/javascript">
	let App_ticketing_dashboard;
	$(document).ready(function(e){
		App_ticketing_dashboard = new Class_ticketing_dashboard();
		let PageToday = $('#PageToday');
		let selectorTableAll = $('#tableDashboardAll').find('table');
		let GraphDashboardAll = $('#GraphDashboardAll');
		let selectorMonth = $('#OpMonth');
		let selectorYear = $('#OpYear');
		let selectorShowAll = $('#OpShowAll');
		let selectorShowToday = $('#OpShowToday');
		let PageDashboardAll = $('#PageDashboardAll');
		App_ticketing_dashboard.LoadDefault(selectorMonth,selectorYear,selectorShowAll,selectorShowToday,PageToday,PageDashboardAll);
		// console.log(moment().format('YYYY-MM-DD'));
	})


	$(document).off('change','#OpMonth,#OpYear').on('change','#OpMonth,#OpYear',function(e){
		App_ticketing_dashboard.TableAll.ajax.reload( null, false );
	})

	$(document).off('click','.aHrefDetailAll').on('click','.aHrefDetailAll',function(e){
		let action = $(this).attr('action');
		let dataDecode = jwt_decode($(this).attr('data'));
		let selectorPage = $('#PageDashboardAll');
		let valueText = $(this).text();
		let DeptText = $(this).closest('tr').find('td:eq(1)').text();
		App_ticketing_dashboard.pageDetailAll(selectorPage,action,dataDecode,valueText,DeptText);
	})

	$(document).off('click','.btn-back-detail').on('click','.btn-back-detail',function(e){
		let action = $(this).attr('action');
		if (action == 'All') {
			let selectorMonth = $('#OpMonth');
			let selectorYear = $('#OpYear');
			let selectorShowAll = $('#OpShowAll');
			let PageDashboardAll = $('#PageDashboardAll');
			App_ticketing_dashboard.PageDashboardAll(selectorMonth,selectorYear,selectorShowAll,PageDashboardAll);
		}
		else
		{
			// App_ticketing_dashboard.TableToday.ajax.reload( null, false );
		}
	})
</script>