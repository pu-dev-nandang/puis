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

<!--=== Bars ===-->
<div class="row" style="margin-left: 0px;margin-right: 0px;margin-top: -30px">
	<div class="col-xs-6 col-md-offset-5">
		<h2>Semester <?php echo $getSemester[0]['Name'] ?></h2>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> Summary Payment Students</h4>
				<!-- <div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div> -->
			</div>
			<div class="widget-content">
				<div id="chart_bars_vertical" class="chart" style="padding: 0px;position: relative;min-height: 400px;"></div>
				<!-- <div id="page_load_sum_pay_chart" class="chart"></div> -->
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header">
					<h4><i class="icon-reorder"></i>Outstanding Payment Students</h4>
			</div>
			<div class="widget-content" style="min-height: 400px;">
				<!-- <div id="page_load_tbl"></div> -->
				<div class="table-responsive">
					<table class="table table-bordered datatable2" id = "datatable2">
					    <thead>
					    <tr>
					        <!-- <th style="width: 12%;">Program Study</th> -->
					        <th style="width: 20%;">Nama & NPM</th>
					        <th style="width: 15%;">Payment Type</th>
					        <th style="width: 15%;">Semester</th>
					        <th style="width: 10%;">Action</th>
					    </tr>
					    </thead>
					    <tbody id="dataRow"></tbody>
					</table>
				</div>
			</div>
		</div> <!-- /.widget box -->
	</div> <!-- /.col-md-6 -->
</div>

<div class="row">
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
	</div> <!-- /.col-md-6 -->
</div>

<script type="text/javascript">
	$(document).ready(function(){
		loadChartSummaryPayment();
		loadDataOutstanding();
		loadChartSummaryPaymentAdmission();
		loadChartSummaryFormulirAdmission();

	});

	function loadChartSummaryPayment()
	{
		loading_page("#chart_bars_vertical");
        var url = base_url_js+'finance/summary_payment';
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
		   	data: response['unsetPaid'],
		   	bars: {
		   		show: true,
		   		barWidth: 0.2,
		   		order: 3
		   	}
		   });

		   // console.log(ds);
		   var xAxis = [];
		   for (var i = 0; i < response['unsetPaid'].length; i++) {
		   	var cd = response['unsetPaid'];
		   	var taa = 'ta_' + cd[i][0];
		   	var aa = [cd[i][0], taa];
		   	xAxis.push(aa);

		   }

		   // console.log(xAxis);
		   
		   // Initialize Chart
		   $.plot("#chart_bars_vertical", ds, $.extend(true, {}, Plugins.getFlotDefaults()	, {	
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

	function loadChartSummaryFormulirAdmission()
	{
		loading_page("#page_load_sum_formulir_admission");
        var url = base_url_js+'finance/summary_payment_formulir';
		$.post(url,function (resultJson) {
		   var response = jQuery.parseJSON(resultJson);
		   console.log(response);
		   var ds = new Array();

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
   		   		order: 3
   		   	}
   		   });

		   // console.log(ds);
		   var xAxis = [];
		   for (var i = 0; i < response['Paid_Off'].length; i++) {
		   	var cd = response['Paid_Off'];
		   	var taa = cd[i][0];
		   	var aa = [cd[i][0], taa];
		   	xAxis.push(aa);

		   }

		   // console.log(xAxis);
		   
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

	function loadDataOutstanding()
	{
	    $.fn.dataTable.ext.errMode = 'throw';
	    //alert('hsdjad');
	    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
	    {
	        return {
	            "iStart": oSettings._iDisplayStart,
	            "iEnd": oSettings.fnDisplayEnd(),
	            "iLength": oSettings._iDisplayLength,
	            "iTotal": oSettings.fnRecordsTotal(),
	            "iFilteredTotal": oSettings.fnRecordsDisplay(),
	            "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
	            "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
	        };
	    };

	    var table = $('#datatable2').DataTable( {
	        "processing": true,
	        "destroy": true,
	        "serverSide": true,
	        "iDisplayLength" : 5,
	        "ordering" : false,
	        "ajax":{
	            url : base_url_js+"finance/dashboard_getoutstanding_today", // json datasource
	            ordering : false,
	            type: "post",  // method  , by default get
	            data : {tahun : $("#selectTahun").val()},
	            error: function(){  // error handling
	                $(".employee-grid-error").html("");
	                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
	                $("#employee-grid_processing").css("display","none");
	            }
	        },
	        'createdRow': function( row, data, dataIndex ) {
	              // if(data[6] == 'Lunas')
	              // {
	              //   $(row).attr('style', 'background-color: #8ED6EA; color: black;');
	              // }
	        },
	    } );

	}

	$(document).on('click','.edit', function () {
	        var PaymentID = $(this).attr('PaymentID');
	        var NPM = $(this).attr('NPM');
	        var semester = $(this).attr('semester');
	        var PTID = $(this).attr('ptid');
	        var data = {
	            PaymentID : PaymentID,
	            NPM : NPM,
	            semester  : semester,
	            PTID : PTID,
	        };
	        var token = jwt_encode(data,'UAP)(*');
	        window.open(base_url_js+'finance/edit_telat_bayar/'+token,'_blank');

	});
</script>