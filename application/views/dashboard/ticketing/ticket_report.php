<?php $this->load->view('dashboard/ticketing/LoadCssTicketToday') ?>
<div class="row" style="margin-top: 30px;">
	<div class="col-md-4 col-md-offset-4">
		<div class="well">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
					    <label>Department</label>
					    <select class="select2-select-00 full-width-fix" id="SelectDepartmentID"></select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6" id="Monthly">
		<div class="panel panel-primary" style="border-color: #42a4ca;">
			<div class="panel-heading clearfix" style="background-color: #42a4ca;border-color: #42a4ca;">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Monthly</h4>
            </div>
            <div class="panel-body">
            	<div class="row">
            		<div class="col-xs-12 pageFilter">
            			<div class="well">
            				<div class="row">
            					<div class="col-md-12">
			            			<div class="form-group">
										<label>Month</label>
										<select class="form-control" id = "OpMonth"></select>
									</div>
									<div class="form-group">
										<label>Year</label>
										<select class="form-control" id = "OpYear"></select>
									</div>
									<div class="form-group">
									    <label>Status</label>
									    <select class="form-control SelectStatusTicketID" id="SelectStatusTicketID"></select>
									</div>
            					</div>
            				</div>
            			</div>
            		</div>
            		<div class="col-xs-12 pagePieChartCategory">
            			<div class="thumbnail">
            				<div class="row">
            					<div style="padding: 10px;text-align: center;">
            						<h4 style="color: green;"><u>By Category</u></h4>
            					</div>
            					<div class="col-md-12">
            						<div class="chart chart-large" id = "ShowPieChartByCategory">
            							
            						</div>
            					</div>
                                          <br/>
            					<div class="col-md-12">
            						<div class="" id = "ShowTblByCategory">
            							
            						</div>
            					</div>
            				</div>
            			</div>
            		</div>
            	</div>
            	<hr/>
            	<div class="row">
            		<div class="col-xs-12 pageGroupByWorker">
            			<div class="thumbnail">
            				<div class="row">
            					<div style="padding: 10px;text-align: center;">
            						<h4 style="color: green;"><u>By Worker</u></h4>
            					</div>
            					<div class="col-xs-12 pageFilterStatusWorker">
            						<div class="well">
            							<div class="row">
            								<div class="col-md-12">
            									<div class="form-group">
            									    <label>Worker</label>
            									    <select class="form-control SelectStatusWorker" id="">
            									    	<option value="2" selected>Close</option>
            									    	<option value="1">Working</option>
            									    	<option value="-1">Withdrawn</option>
            									    </select>
            									</div>
            								</div>
            							</div>
            						</div>
            					</div>
            					<div class="col-xs-12">
            						<div class="thumbnail">
            							<div class="row">
            								<div class="col-md-12 pagePieChartWorker">
            									pageFilterStatusWorker
            								</div>
                                                            <br/>
            								<div class="col-md-12 pageTableWorker">
            									pageTableWorker
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
	</div>
	<div class="col-sm-6" id = "Daily">
		<div class="panel panel-primary" style="border-color: #42a4ca;">
			<div class="panel-heading clearfix" style="background-color: #42a4ca;border-color: #42a4ca;">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Daily</h4>
            </div>
            <div class="panel-body">
            	<div class="row">
            		<div class="col-xs-12 pageFilter">
            			<div class="well">
            				<div class="row">
            					<div class="col-md-12" style="padding-left: 0px !important;padding-right: 0px !important; ">
            						<div class="form-group">
            						    <label>Status</label>
            						    <select class="form-control SelectStatusTicketID" id="SelectStatusTicketID"></select>
            						</div>
			            			<div class="form-group">
			            				<label>Date</label>
			            				<div class="input-group input-append date datetimepicker" id="datetimepickerFilterReport">
			            				    <input data-format="yyyy-MM-dd" class="form-control" type="text" readonly="" id = "dateFilterReport" value = "<?php echo date('Y-m-d') ?>" >
			            				    <span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
			            				</div>
			            				<br/>
			            				<span class = "btn btnSearchDateReport btn-primary">Search</button>
			            			</div>
            					</div>
            				</div>
            			</div>
            		</div>
            		<div class="col-xs-12 pagePieChartCategory">
            			<div class="thumbnail">
            				<div class="row">
            					<div style="padding: 10px;text-align: center;">
            						<h4 style="color: green;"><u>By Category</u></h4>
            					</div>
            					<div class="col-md-12">
            						<div class="chart" id = "ShowPieChartByCategory">
            							pagePieChartCategory
            						</div>
            					</div>
                                          <br/>
            					<div class="col-md-12">
            						<div class="table-responsive" id = "ShowTblByCategory">
            							pageTableCategory
            						</div>
            					</div>
            				</div>
            			</div>
            		</div>
            	</div>
            	<hr/>
            	<div class="row">
            		<div class="col-xs-12 pageGroupByWorker">
            			<div class="thumbnail">
            				<div class="row">
            					<div style="padding: 10px;text-align: center;">
            						<h4 style="color: green;"><u>By Worker</u></h4>
            					</div>
            					<div class="col-xs-12 pageFilterStatusWorker">
            						<div class="well">
            							<div class="row">
            								<div class="col-md-12">
            									<div class="form-group">
            									    <label>Worker</label>
            									    <select class="form-control SelectStatusWorker" id="">
            									    	<option value="2" selected>Close</option>
            									    	<option value="1">Working</option>
            									    	<option value="-1">Withdrawn</option>
            									    </select>
            									</div>
            								</div>
            							</div>
            						</div>
            					</div>
            					<div class="col-xs-12">
            						<div class="thumbnail">
            							<div class="row">
            								<div class="col-md-12 pagePieChartWorker">
            									pageFilterStatusWorker
            								</div>
                                                            <br/>
            								<div class="col-md-12 pageTableWorker">
            									pageTableWorker
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
	</div>
</div>
<!-- graph-->
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
<!-- graph-->

<script type="text/javascript" src="<?php echo base_url('js/ticketing/Class_ticketing_dashboard.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/ticketing/Class_ticketing_report.js'); ?>"></script>

<script type="text/javascript">
      let App_ticketing_dashboard;
      let requestHeader;
      $(document).ready(function(e){
            // loadingStart();
            requestHeader = {
                  Hjwtkey : Hjwtkey,
            }
            App_ticketing_dashboard = new Class_ticketing_dashboard();
            App_ticketing_report = new Class_ticketing_report();
      	const selectorDepartment = $('#SelectDepartmentID');
      	LoadSelectOptionDepartmentFiltered(selectorDepartment);
            const selectorMonth = $('#OpMonth');
            const selectorYear = $('#OpYear');
            const selectorStatus1 = $('#Monthly').find('.SelectStatusTicketID');
            App_ticketing_report.LoadMonthly(selectorMonth,selectorYear,selectorStatus1);

      })

      $(document).off('click', '.ModalReadMore').on('click', '.ModalReadMore',function(e) {
          var selector = $(this);
          let data = jwt_decode(selector.attr('data'));
          // console.log(data);
          var setTicket = '';
          var ID = data['ID'];
          var token = selector.attr('data')
          AppModalDetailTicket.ModalReadMore(ID,setTicket,token);
      })
</script>