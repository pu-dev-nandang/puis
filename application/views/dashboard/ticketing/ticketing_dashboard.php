<div class="row" style="margin-top: 10px;">
	<div class="col-xs-6">
		<div class="thumbnail">
			<div class="row">
				<div class="col-xs-12">
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
												<option value="1">Table</option>
												<option value="2" selected>Graph</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div style="padding: 10px;text-align: center;">
						<h4 style="color: green;"><u>Ticket All</u></h4>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div class="panel panel-primary" style="border-color: #42a4ca;">
								<div class="panel-heading clearfix" style="background-color: #42a4ca;border-color: #42a4ca;">
					                <h4 class="panel-title pull-left" style="padding-top: 7.5px;"></h4>
					            </div>
					            <div class="panel-body" id = "PageDashboardAll">
					            	
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
										<option value="1">Table</option>
										<option value="2" selected>Graph</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div style="padding: 10px;margin-top: 70px;text-align: center;">
				<h4 style="color: green;"><u>Ticket Today</u></h4>
			</div>
			<div class="panel panel-primary" style="border-color: #42a4ca;">
				<div class="panel-heading clearfix" style="background-color: #42a4ca;border-color: #42a4ca;">
	                <h4 class="panel-title pull-left" style="padding-top: 7.5px;"></h4>
	            </div>
	            <div class="panel-body">
	            	<div id="PageToday">
	            		
	            	</div>
	            </div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/sparkline/jquery.sparkline.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.tooltip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.time.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.orderBars.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.pie.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.selection.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/flot/jquery.flot.growraf.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

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
		if ($('#PageDashboardAll').find('.dataTables_wrapper').length) {
			App_ticketing_dashboard.TableAll.ajax.reload( null, false );
		}
		else
		{
			let selectorMonth = $('#OpMonth');
			let selectorYear = $('#OpYear');
			let selectorShowAll = $('#OpShowAll');
			let PageDashboardAll = $('#PageDashboardAll');
			App_ticketing_dashboard.PageDashboardAll(selectorMonth,selectorYear,selectorShowAll,PageDashboardAll);
		}
		
	})

	$(document).off('click','.aHrefDetailAll').on('click','.aHrefDetailAll',function(e){
		let action = $(this).attr('action');
		let dataDecode = jwt_decode($(this).attr('data'));
		let selectorPage = $('#PageDashboardAll');
		let valueText = $(this).text();
		let DeptText = $(this).closest('tr').find('td:eq(1)').text();
		let pageSet = 'All';
		App_ticketing_dashboard.pageDetailAll(selectorPage,action,dataDecode,valueText,DeptText,pageSet);
	})

	$(document).off('click','.aHrefDetailToday').on('click','.aHrefDetailToday',function(e){
		let action = $(this).attr('action');
		let dataDecode = jwt_decode($(this).attr('data'));
		let selectorPage = $('#PageToday');
		let valueText = $(this).text();
		let DeptText = $(this).closest('tr').find('td:eq(1)').text();
		let pageSet = 'Today';
		App_ticketing_dashboard.pageDetailAll(selectorPage,action,dataDecode,valueText,DeptText,pageSet);
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
			let selectorShowToday = $('#OpShowToday');
			let PageToday = $('#PageToday');
			App_ticketing_dashboard.PageToday(selectorShowToday,PageToday);

		}
	})

	$(document).off('change','#OpShowToday').on('change','#OpShowToday',function(e){
		let selectorShowToday = $('#OpShowToday');
		let PageToday = $('#PageToday');
		App_ticketing_dashboard.PageToday(selectorShowToday,PageToday);
	})

	$(document).off('change','#OpShowAll').on('change','#OpShowAll',function(e){
		let selectorMonth = $('#OpMonth');
		let selectorYear = $('#OpYear');
		let selectorShowAll = $('#OpShowAll');
		let PageDashboardAll = $('#PageDashboardAll');
		App_ticketing_dashboard.PageDashboardAll(selectorMonth,selectorYear,selectorShowAll,PageDashboardAll);;
	})

	
</script>