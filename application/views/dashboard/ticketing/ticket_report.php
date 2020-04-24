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
            		<div class="col-xs-3 pageFilter">
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
									    <select class="form-control" id="SelectStatusTicketID"></select>
									</div>
            					</div>
            				</div>
            			</div>
            		</div>
            		<div class="col-xs-9 pagePieChartCategory">
            			<div class="thumbnail">
            				<div class="row">
            					<div style="padding: 10px;text-align: center;">
            						<h4 style="color: green;"><u>By Category</u></h4>
            					</div>
            					<div class="col-md-7">
            						<div class="chart" id = "ShowPieChartByCategory">
            							pagePieChartCategory
            						</div>
            					</div>
            					<div class="col-md-5">
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
            					<div class="col-xs-3 pageFilterStatusWorker">
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
            					<div class="col-xs-9">
            						<div class="thumbnail">
            							<div class="row">
            								<div class="col-md-6 pagePieChartWorker">
            									pageFilterStatusWorker
            								</div>
            								<div class="col-md-6 pageTableWorker">
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
            		<div class="col-xs-3 pageFilter">
            			<div class="well">
            				<div class="row">
            					<div class="col-md-12" style="padding-left: 0px !important;padding-right: 0px !important; ">
            						<div class="form-group">
            						    <label>Status</label>
            						    <select class="form-control" id="SelectStatusTicketID"></select>
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
            		<div class="col-xs-9 pagePieChartCategory">
            			<div class="thumbnail">
            				<div class="row">
            					<div style="padding: 10px;text-align: center;">
            						<h4 style="color: green;"><u>By Category</u></h4>
            					</div>
            					<div class="col-md-7">
            						<div class="chart" id = "ShowPieChartByCategory">
            							pagePieChartCategory
            						</div>
            					</div>
            					<div class="col-md-5">
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
            					<div class="col-xs-3 pageFilterStatusWorker">
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
            					<div class="col-xs-9">
            						<div class="thumbnail">
            							<div class="row">
            								<div class="col-md-6 pagePieChartWorker">
            									pageFilterStatusWorker
            								</div>
            								<div class="col-md-6 pageTableWorker">
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

<script type="text/javascript">
	$(document).ready(function(e){
		const selectorDepartment = $('#SelectDepartmentID');
		LoadSelectOptionDepartmentFiltered(selectorDepartment);
	})
</script>