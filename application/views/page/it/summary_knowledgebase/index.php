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

<div class="row">
	<div class="col-md-6">
		<div class="thumbnail">
			<div style="padding:15px;">
				<h4 style="color: blue;">Total Kb per Divisi</h4>
			</div>
			<div class="row" style="padding:10px;">
			    <div class="col-md-12">
			        <div class="panel panel-primary">
			            <div class="panel-heading clearfix">
			                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Chart & Table</h4>
			            </div>
			            <div class="panel-body" style="max-height: 300px;overflow-y: auto;">
			            	<?php echo $page_total_kb_per_divisi ?>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="thumbnail">
			<div style="padding:15px;">
				<h4 style="color: blue;">Total top 100 view kb by Employees</h4>
			</div>
			<div class="row" style="padding:10px;">
			    <div class="col-md-12">
			        <div class="panel panel-primary">
			            <div class="panel-heading clearfix">
			                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Table</h4>
			            </div>
			            <div class="panel-body" style="max-height: 300px;overflow-y: auto;">
			            	<?php echo $page_total_max_view_log_employees ?>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="thumbnail">
			<div style="padding:15px;">
				<h4 style="color: blue;">Total top 5 view kb by Employees</h4>
			</div>
			<div class="row" style="padding:10px;">
			    <div class="col-md-12">
			        <div class="panel panel-primary">
			            <div class="panel-heading clearfix">
			                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Pie Chart</h4>
			            </div>
			            <div class="panel-body" style="max-height: 300px;overflow-y: auto;">
			            	<?php echo $page_total_top10By_EMP ?>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>
</div>

<div class="row" style="max-height: 600px;overflow-y: auto;margin-top: 10px;">
	<div class="col-md-12">
		<div class="thumbnail">
			<div style="padding:15px;">
				<h4 style="color: blue;">Total top 5 view content per Divisi (Max 5 per divisi)</h4>
			</div>
			<div class="row" style="padding:10px;">
			    <div class="col-md-12">
			        <div class="panel panel-primary">
			            <div class="panel-heading clearfix">
			                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Chart & Table</h4>
			            </div>
			            <div class="panel-body">
			            	<?php echo $page_max_view_content_per_divisi ?>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>
</div>

<div class="row" style="max-height: 600px;overflow-y: auto;margin-top: 10px;">
	<div class="col-md-12">
		<div class="thumbnail">
			<div style="padding:15px;">
				<h4 style="color: blue;">Search</h4>
			</div>
			<div class="row" style="padding:10px;">
			    <div class="col-md-6">
			        <div class="panel panel-primary">
			            <div class="panel-heading clearfix">
			                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Filter by Employees</h4>
			            </div>
			            <div class="panel-body">
		            		<?php echo  $page_search_filter_by_employees ?>
			            </div>
			        </div>
			    </div>
			    <div class="col-md-6">
			        <div class="panel panel-primary">
			            <div class="panel-heading clearfix">
			                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Filter by content</h4>
			            </div>
			            <div class="panel-body">
		            		<?php echo  $page_search_filter_by_content ?>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>
</div>
