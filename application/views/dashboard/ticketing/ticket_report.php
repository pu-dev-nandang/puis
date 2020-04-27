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
                        <br/>
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
            						<div class="" id = "ShowTblByCategory">
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
            									
            								</div>
                                                            <br/>
            								<div class="col-md-12 pageTableWorker">
            									
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
            const selectorStatus2 = $('#Daily').find('.SelectStatusTicketID');
            const selectorDateFilter = $('#Daily').find('#dateFilterReport');
            App_ticketing_report.LoadDaily(selectorDateFilter,selectorStatus2);

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

      $(document).on('click','.btnSearchDateReport',async function(e){
          const selectorStatus2 = $('#Daily').find('.SelectStatusTicketID');
          const selectorDateFilter = $('#Daily').find('#dateFilterReport');
          
          const cls = App_ticketing_report;
          const selectorChart =  $('#Daily').find('#ShowPieChartByCategory');
          const selectorTable =  $('#Daily').find('#ShowTblByCategory');
          cls.loading_page(selectorChart);
          cls.loading_page(selectorTable);

          const selectorChartWorker = $('#Daily').find('.pagePieChartWorker');
          const selectorTableWorker = $('#Daily').find('.pageTableWorker');
          cls.loading_page(selectorChartWorker);
          cls.loading_page(selectorTableWorker);
          
          let Daily = [];
          Daily[0] = await cls.D_Category(selectorDateFilter,selectorStatus2);
          cls.makeDomCategory(Daily[0],selectorChart,selectorTable);
          Daily[1] = await cls.D_Worker(selectorDateFilter,selectorStatus2);
          cls.makeDomWorker(Daily[1],selectorChartWorker,selectorTableWorker); 
      })

      $(document).on('change','#Daily .SelectStatusTicketID',async function(e){
            const selectorStatus2 = $('#Daily').find('.SelectStatusTicketID');
            const selectorDateFilter = $('#Daily').find('#dateFilterReport');
            
            const cls = App_ticketing_report;
            const selectorChart =  $('#Daily').find('#ShowPieChartByCategory');
            const selectorTable =  $('#Daily').find('#ShowTblByCategory');
            cls.loading_page(selectorChart);
            cls.loading_page(selectorTable);

            const selectorChartWorker = $('#Daily').find('.pagePieChartWorker');
            const selectorTableWorker = $('#Daily').find('.pageTableWorker');
            cls.loading_page(selectorChartWorker);
            cls.loading_page(selectorTableWorker);
            
            let Daily = [];
            Daily[0] = await cls.D_Category(selectorDateFilter,selectorStatus2);
            cls.makeDomCategory(Daily[0],selectorChart,selectorTable);
            Daily[1] = await cls.D_Worker(selectorDateFilter,selectorStatus2);
            cls.makeDomWorker(Daily[1],selectorChartWorker,selectorTableWorker); 
      })

      $(document).on('change','#Daily .SelectStatusWorker',async function(e){
            const selectorChartWorker = $('#Daily').find('.pagePieChartWorker');
            const selectorTableWorker = $('#Daily').find('.pageTableWorker');
            App_ticketing_report.loading_page(selectorChartWorker);
            App_ticketing_report.loading_page(selectorTableWorker);
            const selectorStatus2 = $('#Daily').find('.SelectStatusTicketID');
            const selectorDateFilter = $('#Daily').find('#dateFilterReport');
            let Daily = await App_ticketing_report.D_Worker(selectorDateFilter,selectorStatus2);
            App_ticketing_report.makeDomWorker(Daily,selectorChartWorker,selectorTableWorker);
      })

      $(document).on('change','#Monthly #OpMonth,#Monthly #OpYear,#Monthly .SelectStatusTicketID',async function(e){
            const selectorMonth = $('#OpMonth');
            const selectorYear = $('#OpYear');
            const selectorStatus1 = $('#Monthly').find('.SelectStatusTicketID');
            const cls = App_ticketing_report;
            const selectorChartMonthly =  $('#Monthly').find('#ShowPieChartByCategory');
            const selectorTableMonthly =  $('#Monthly').find('#ShowTblByCategory');
            cls.loading_page(selectorChartMonthly);
            cls.loading_page(selectorTableMonthly);

            const selectorChartWorkerMonthly = $('#Monthly').find('.pagePieChartWorker');
            const selectorTableWorkerMonthly = $('#Monthly').find('.pageTableWorker');
            cls.loading_page(selectorChartWorkerMonthly);
            cls.loading_page(selectorTableWorkerMonthly);
            
            let Monthly = [];
            Monthly[0] = await cls.M_Category(selectorMonth,selectorYear,selectorStatus1);
            cls.makeDomCategory(Monthly[0],selectorChartMonthly,selectorTableMonthly);
            
            Monthly[1] = await cls.M_Worker(selectorMonth,selectorYear,selectorStatus1);
            cls.makeDomWorker(Monthly[1],selectorChartWorkerMonthly,selectorTableWorkerMonthly);
      })

      $(document).on('change','#Monthly .SelectStatusWorker',async function(e){
            const selectorChartWorker = $('#Monthly').find('.pagePieChartWorker');
            const selectorTableWorker = $('#Monthly').find('.pageTableWorker');
            App_ticketing_report.loading_page(selectorChartWorker);
            App_ticketing_report.loading_page(selectorTableWorker);
            const selectorMonth = $('#OpMonth');
            const selectorYear = $('#OpYear');
            const selectorStatus1 = $('#Monthly').find('.SelectStatusTicketID');
            let Monthly = await App_ticketing_report.M_Worker(selectorMonth,selectorYear,selectorStatus1);
            App_ticketing_report.makeDomWorker(Monthly,selectorChartWorker,selectorTableWorker);
      })

      $(document).on('change','#SelectDepartmentID',function(e){
            const selectorMonth = $('#OpMonth');
            const selectorYear = $('#OpYear');
            const selectorStatus1 = $('#Monthly').find('.SelectStatusTicketID');
            App_ticketing_report.LoadMonthly(selectorMonth,selectorYear,selectorStatus1);
            const selectorStatus2 = $('#Daily').find('.SelectStatusTicketID');
            const selectorDateFilter = $('#Daily').find('#dateFilterReport');
            App_ticketing_report.LoadDaily(selectorDateFilter,selectorStatus2);
      })
</script>