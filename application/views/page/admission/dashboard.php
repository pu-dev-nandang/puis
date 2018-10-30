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
<div class="row" style="margin-left: 0px;margin-right: 0px;margin-top: -30px">
	<div class="col-xs-6 col-md-offset-5">
		<h2>Angkatan <?php echo $set_ta ?></h2>
	</div>
</div>
<div class="row" style="margin-left: 0px;margin-right: 0px">
	<div class="col-sm-4 col-md-3 col-md-offset-3 hidden-xs">
		<div class="statbox widget box box-shadow">
			<div class="widget-content">
				<div class="visual yellow">
					<span class="image"><img src="<?php echo base_url('assets/icon/finance.png'); ?>" style="height: 25px"></span>
				</div>
				<div class="title">Formulir Receive</div>
				<div class="valueFormulir"></div>
				<a class="moreFormulir" href="javascript:void(0);">View More <i class="pull-right icon-angle-right"></i></a>
			</div>
		</div> <!-- /.smallstat -->
	</div> <!-- /.col-md-3 -->
	<div class="col-sm-4 col-md-3 hidden-xs">
		<div class="statbox widget box box-shadow">
			<div class="widget-content">
				<div class="visual yellow">
					<span class="image"><img src="<?php echo base_url('assets/icon/finance.png'); ?>" style="height: 25px"></span>
				</div>
				<div class="title">Tuition Fee Receive</div>
				<div class="valueTuitionFee"></div>
				<a class="moreTuitionFee" href="javascript:void(0);">View More <i class="pull-right icon-angle-right"></i></a>
			</div>
		</div> <!-- /.smallstat -->
	</div> <!-- /.col-md-3 -->
</div>
<div class="row">
	<div class="col-md-12">
		<div class="row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px">
			<div class="col-md-6">
				<div class="widget box">
					<div class="widget-header">
						<h4><i class="icon-reorder"></i> Summary Payment Intake</h4>
						<!-- <div class="toolbar no-padding">
							<div class="btn-group">
								<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
							</div>
						</div> -->
					</div>
					<div class="widget-content">
						<!-- <div id="chart_bars_vertical" class="chart"></div> -->
						<div id="page_load_sum_pay_admission_chart" class="chart"></div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="widget box">
					<div class="widget-header">
							<h4><i class="icon-reorder"></i> Summary Formulir Intake</h4>
					</div>
					<div class="widget-content">
						<div id="page_load_sum_formulir_admission" class="chart"></div>
					</div>
				</div> <!-- /.widget box -->
			</div>
		</div>
	</div>
</div>
<div class="row" style="margin-left: 0px;margin-right: 0px">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> Summary Formulir Per Marketing</h4>
				<!-- <div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div> -->
			</div>
			<div class="widget-content">	
				<!-- <div id="chart_bars_vertical" class="chart"></div> -->
				<div id="page_load_sum_formulir_persales" class="chart"></div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		loadChartSummaryPaymentAdmission();
		loadChartSummaryFormulirAdmission();
		loadChartSummaryFormulirPerSales();
		loadSumBox();

	});

	function loadSumBox()
	{
		loading_page(".valueFormulir");
		loading_page(".valueTuitionFee");
		var url = base_url_js+'admisssion/SummaryBox';
		$.post(url,function (resultJson) {
			var response = jQuery.parseJSON(resultJson);
			$(".valueFormulir").html(formatRupiah(response['Formulir']))
			$(".valueTuitionFee").html(formatRupiah(response['tuition_fee']))
		}); // exit spost

	}

	$(".moreFormulir").click(function(){
		var url = base_url_js+'admission/export_PenjualanFormulirFinance';
		data = {
		  cf : 3,
		  SelectSetTa : "<?php echo $set_ta ?>",
		}
		var token = jwt_encode(data,"UAP)(*");
		FormSubmitAuto(url, 'POST', [
		    { name: 'token', value: token },
		]);
		
	})

	$(".moreTuitionFee").click(function(){
		var url = base_url_js+'admission/TuitionFee_Excel';
		data = {
		  Prodi : 0,
		  Year : "<?php echo $set_ta ?>",
		}
		var token = jwt_encode(data,"UAP)(*");
		FormSubmitAuto(url, 'POST', [
		    { name: 'token', value: token },
		]);
		
	})

	function loadChartSummaryFormulirPerSales()
	{
		loading_page("#page_load_sum_formulir_persales");
        var url = base_url_js+'admisssion/SummaryFormulirPerSales';
		$.post(url,function (resultJson) {
		   var response = jQuery.parseJSON(resultJson);
		   var ds = new Array();
		   ds.push({
   		   	label: "Qty Sales",
   		   	data: response['arr_result'],
   		   	bars: {
   		   		show: true,
   		   		barWidth: 0.2,
   		   		order: 1
   		   	}
   		   });
		   var xAxis = [];
		   for (var i = 0; i < response['arr_result'].length; i++) {
		   	var cd = response['arr_result'];
		   	var taa = cd[i][2];
		   	var aa = [cd[i][0], taa];
		   	xAxis.push(aa);

		   }

		   // Initialize Chart
		   $.plot("#page_load_sum_formulir_persales", ds, $.extend(true, {}, Plugins.getFlotDefaults()	, {	
		   	series: {
		   		lines: { show: false },
		   		points: { show: false }
		   	},
		   	grid:{
		   		hoverable: true
		   	},
		   	tooltip: true,
		   	tooltipOpts: {
		   		content: '%s: %y'
		   	},
		   	xaxis: { ticks:xAxis},
		   }));

		}); // exit spost
	}

	function loadChartSummaryFormulirAdmission()
	{
		loading_page("#page_load_sum_formulir_admission");
        var url = base_url_js+'finance/summary_payment_formulir';
		$.post(url,function (resultJson) {
		   var response = jQuery.parseJSON(resultJson);
		   var ds = new Array();
		   // console.log(response);
		   ds.push({
   		   	label: "Sold Out",
   		   	data: response['Paid_Off'],
   		   	bars: {
   		   		show: true,
   		   		barWidth: 0.2,
   		   		order: 1
   		   	}
   		   });

   		   ds.push({
   		   	label: "",
   		   	data: response['Return_Formulir'],
   		   	bars: {
   		   		// show: true,
   		   		barWidth: 0.2,
   		   		order: 2
   		   	}
   		   });

   		   ds.push({
   		   	label: "Return",
   		   	data: response['Return_Formulir'],
   		   	bars: {
   		   		show: true,
   		   		barWidth: 0.2,
   		   		order: 3,
   		   	},
   		   });

		   // console.log(ds);
		   var xAxis = [];
		   for (var i = 0; i < response['Paid_Off'].length; i++) {
		   	var cd = response['Paid_Off'];
		   	var taa = cd[i][0];
		   	var aa = [cd[i][0], taa];
		   	xAxis.push(aa);

		   }
		   
		   // Initialize Chart
		   $.plot("#page_load_sum_formulir_admission", ds, $.extend(true, {}, Plugins.getFlotDefaults()	, {	
		   	series: {
		   		lines: { show: false },
		   		points: { show: false }
		   	},
		   	grid:{
		   		hoverable: true
		   	},
		   	tooltip: true,
		   	tooltipOpts: {
		   		content: '%s: %y'
		   	},
		   	xaxis: { ticks:xAxis}
		   }));

		}); // exit spost
	}	

	function loadChartSummaryPaymentAdmission()
	{
		loading_page("#page_load_sum_pay_admission_chart");
        var url = base_url_js+'finance/summary_payment_admission';
		$.post(url,function (resultJson) {
		   var response = jQuery.parseJSON(resultJson);
		   var ds = new Array();

		   ds.push({
		   	label: "Paid Off",
		   	data: response['Paid_Off'],
		   	bars: {
		   		show: true,
		   		barWidth: 0.2,
		   		order: 1
		   	}
		   });

		   ds.push({
		   	label: "Unpaid Off",
		   	data: response['Unpaid_Off'],
		   	bars: {
		   		show: true,
		   		barWidth: 0.2,
		   		order: 2
		   	}
		   });

		   ds.push({
		   	label: "Unset Paid",
		   	data: response['Unset_Paid'],
		   	bars: {
		   		show: true,
		   		barWidth: 0.2,
		   		order: 3
		   	}
		   });

		   // console.log(ds);
		   var xAxis = [];
		   for (var i = 0; i < response['Unpaid_Off'].length; i++) {
		   	var cd = response['Unpaid_Off'];
		   	var taa = cd[i][0];
		   	var aa = [cd[i][0], taa];
		   	xAxis.push(aa);

		   }

		   // console.log(xAxis);
		   
		   // Initialize Chart
		   $.plot("#page_load_sum_pay_admission_chart", ds, $.extend(true, {}, Plugins.getFlotDefaults()	, {	
		   	series: {
		   		lines: { show: false },
		   		points: { show: false }
		   	},
		   	grid:{
		   		hoverable: true
		   	},
		   	tooltip: true,
		   	tooltipOpts: {
		   		content: '%s: %y'
		   	},
		   	xaxis: { ticks:xAxis}
		   }));

		}); // exit spost
	}
</script>