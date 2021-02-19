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

<style type="text/css">
	.dataTables_filter{ display: none; }
</style>

<!--=== Bars ===-->
<div class="row" style="margin-left: 0px;margin-right: 0px;margin-top: -30px">
	<div class="col-md-5">
		<?php if ($this->session->userdata('NIP') == '2018018'): ?>
			<button class="btn btn-info btn-sync-payment">Sync Payment</button>
		<?php endif ?>
	</div>
	<div class="col-xs-6">
		<div>
			<h2>Semester <?php echo $getSemester[0]['Name'] ?></h2>
			<span style="color: red;margin-left: 50px;">Last Updated : <?php echo date('d M Y',strtotime($LastUpdated)) ?></span>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
		    <div class="panel-heading clearfix">
		        <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Summary Payment Students</h4>
		    </div>
		    <div class="panel-body">
		    	<div class="row">
		    		<div class="col-md-12">
		    			<div id="chart_bars_vertical" class="chart" style="padding: 0px;position: relative;min-height: 400px;"></div> 
		    		</div>
		    	</div>
		    </div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
				    <div class="panel-heading clearfix">
				        <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Payment Information</h4>
				    </div>
				    <div class="panel-body">
				    	<div class="row">
				    		<div class="col-md-6">
				    			<table class="table tblSPP">
				    				<caption style = "font-weight: bold;">SPP</caption>
				    				<tr>
				    					<td>Total Invoice</td>
				    					<td class="invoice" style = "color:green;"></td>
				    				</tr>
				    				<tr>
				    					<td>Total Pembayaran</td>
				    					<td class="pay" style = "color:green;"></td>
				    				</tr>
				    				<tr>
				    					<td>Sisa</td>
				    					<td class="sisa" style = "color:green;"></td>
				    				</tr>
				    			</table>
				    		</div>
				    		<div class="col-md-6">
				    			<table class="table tblAnother">
				    				<caption style = "font-weight: bold;">Another</caption>
				    				<tr>
				    					<td>Total Invoice</td>
				    					<td class="invoice" style = "color:green;"></td>
				    				</tr>
				    				<tr>
				    					<td>Total Pembayaran</td>
				    					<td class="pay" style = "color:green;"></td>
				    				</tr>
				    				<tr>
				    					<td>Sisa</td>
				    					<td class="sisa" style = "color:green;"></td>
				    				</tr>
				    			</table>
				    		</div>
				    		<div class="col-md-6">
				    			<table class="table tblBPP">
				    				<caption style = "font-weight: bold;">BPP</caption>
				    				<tr>
				    					<td>Total Invoice</td>
				    					<td class="invoice" style = "color:green;"></td>
				    				</tr>
				    				<tr>
				    					<td>Total Pembayaran</td>
				    					<td class="pay" style = "color:green;"></td>
				    				</tr>
				    				<tr>
				    					<td>Sisa</td>
				    					<td class="sisa" style = "color:green;"></td>
				    				</tr>
				    			</table>
				    		</div>
				    		<div class="col-md-6">
				    			<table class="table tblCredit">
				    				<caption style = "font-weight: bold;">Credit</caption>
				    				<tr>
				    					<td>Total Invoice</td>
				    					<td class="invoice" style = "color:green;"></td>
				    				</tr>
				    				<tr>
				    					<td>Total Pembayaran</td>
				    					<td class="pay" style = "color:green;"></td>
				    				</tr>
				    				<tr>
				    					<td>Sisa</td>
				    					<td class="sisa" style = "color:green;"></td>
				    				</tr>
				    			</table>
				    		</div>
				    	</div>
				    </div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
				    <div class="panel-heading clearfix">
				        <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Detail Summary Payment Students</h4>
				    </div>
				    <div class="panel-body" style="max-height: 400px;overflow-y: auto;">
				    	<div class="row">
				    		<div class="col-md-12">
				    			<table class="table table-bordered tblPaidOff">
				    				<caption style = "font-weight: bold;">Paid Off Detail</caption>
				    				<thead>
				    					<tr>
				    						<th>NPM - Nama</th>
				    						<th>Prodi</th>
				    						<th>TA</th>
				    					</tr>
				    					<tr class="filterTblData">
				    						<th>NPM - Nama</th>
				    						<th>Prodi</th>
				    						<th>TA</th>
				    					</tr>
				    				</thead>
				    				<tbody>
				    					
				    				</tbody>
				    			</table>
				    		</div>
				    	</div>
				    	<div class="row">
				    		<div class="col-md-12">
				    			<table class="table table-bordered tblunpaidOff">
				    				<caption style = "font-weight: bold;">Unpaid Off Detail</caption>
				    				<thead>
				    					<tr>
				    						<th>NPM - Nama</th>
				    						<th>Prodi</th>
				    						<th>TA</th>
				    					</tr>
				    					<tr class="filterTblData">
				    						<th>NPM - Nama</th>
				    						<th>Prodi</th>
				    						<th>TA</th>
				    					</tr>
				    				</thead>
				    				<tbody>
				    					
				    				</tbody>
				    			</table>
				    		</div>
				    	</div>
				    	<div class="row">
				    		<div class="col-md-12">
				    			<table class="table table-bordered tblunsetPaid">
				    				<caption style = "font-weight: bold;">Unset Paid Detail</caption>
				    				<thead>
				    					<tr>
				    						<th>NPM - Nama</th>
				    						<th>Prodi</th>
				    						<th>TA</th>
				    					</tr>
				    					<tr class="filterTblData">
				    						<th>NPM - Nama</th>
				    						<th>Prodi</th>
				    						<th>TA</th>
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
		</div>	
	</div>
    <!-- <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Outstanding Payment Students</h4>
            </div>
            <div class="panel-body" style="min-height: 400px;">
                <div class="table-responsive">
                	<table class="table table-bordered datatable2" id = "datatable2">
                	    <thead>
                	    <tr>
                	        <th style="width: 12%;">Program Study</th> 
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
        </div>
    </div> -->
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
		    <div class="panel-heading clearfix">
		        <h4 class="panel-title pull-left" style="padding-top: 7.5px;">SPP - Summary Payment Students</h4>
		    </div>
		    <div class="panel-body">
		    	<div class="row">
		    		<div class="col-md-12">
		    			<div id="SPP_chart_bars_vertical" class="chart" style="padding: 0px;position: relative;min-height: 400px;"></div> 
		    		</div>
		    	</div>
		    </div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
		    <div class="panel-heading clearfix">
		        <h4 class="panel-title pull-left" style="padding-top: 7.5px;">DetailSPP - Summary Payment Students</h4>
		    </div>
		    <div class="panel-body">
		    	<div class="row">
		    		<div class="col-md-12">
		    			<table class="table table-bordered SPPtblPaidOff">
		    				<caption style = "font-weight: bold;">Paid Off</caption>
		    				<thead>
		    					<tr>
		    						<th>NPM - Nama</th>
		    						<th>Prodi</th>
		    						<th>TA</th>
		    						<th>Invoice</th>
		    						<th>Pembayaran</th>
		    						<th>Sisa</th>
		    					</tr>
		    					<tr class="filterTblData">
		    						<th>NPM - Nama</th>
		    						<th>Prodi</th>
		    						<th>TA</th>
		    						<th></th>
		    						<th></th>
		    						<th></th>
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
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Summary Payment Intake</h4>
            </div>
            <div class="panel-body">
              <div id="page_load_sum_pay_admission_chart" class="chart"></div>             
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Summary Formulir Intake</h4>
            </div>
            <div class="panel-body">
              <div id="page_load_sum_formulir_admission" class="chart"></div>            
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		loadChartSummaryPayment();
		//loadDataOutstanding();
		loadChartSummaryPaymentAdmission();
		loadChartSummaryFormulirAdmission();

	});

	const Summary_Payment_Students_detail = (dataMhs,tableSelector) => {
		tableSelector.find('tbody').empty();
		for (var i = 0; i < dataMhs.length; i++) {
			let ta = dataMhs[i]['TA'];
			ta = ta.split('_');
			ta = ta[1];
			tableSelector.find('tbody').append(
					'<tr>'+
						'<td>'+dataMhs[i]['NPM'] + ' - '+ dataMhs[i]['Name']+'</td>'+
						'<td>'+dataMhs[i]['Prodi']+
						'<td>'+ta+'</td>'+
					'</tr>'
				);
		}

		  tableSelector.find('thead tr').clone(true).appendTo( '#example thead' );
		   tableSelector.find('thead tr:eq(1) th').each( function (i) {
		      var title = $(this).text();
		      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
		
		      $( 'input', this ).on( 'keyup change', function () {
		          if ( table.column(i).search() !== this.value ) {
		              table
		                  .column(i)
		                  .search( this.value )
		                  .draw();
		          }
		      } );
		  } );

		    // DataTable
		    var table = tableSelector.DataTable({
		    	//"searching": false,
		    	"scrollY": "200px",
		    	"scrollCollapse": true,
		    	"paging": false,
		    	//"ordering" : false,
		    	orderCellsTop: true,
		    	fixedHeader: true
		    });
	}

	const payment_information_type = (response) => {
		const Payment_Detail = response.Payment_Detail;

		const get_SPP = Payment_Detail['SPP'];
		$('.tblSPP').find('.invoice').html(formatRupiah(parseInt(get_SPP['Tot'])));
		$('.tblSPP').find('.pay').html(formatRupiah(parseInt(get_SPP['TotPay'])));
		$('.tblSPP').find('.sisa').html(formatRupiah(parseInt(get_SPP['TotSisa'])));

		const get_BPP = Payment_Detail['BPP'];
		$('.tblBPP').find('.invoice').html(formatRupiah(parseInt(get_BPP['Tot'])));
		$('.tblBPP').find('.pay').html(formatRupiah(parseInt(get_BPP['TotPay'])));
		$('.tblBPP').find('.sisa').html(formatRupiah(parseInt(get_BPP['TotSisa'])));

		const get_Another = Payment_Detail['Another'];
		$('.tblAnother').find('.invoice').html(formatRupiah(parseInt(get_Another['Tot'])));
		$('.tblAnother').find('.pay').html(formatRupiah(parseInt(get_Another['TotPay'])));
		$('.tblAnother').find('.sisa').html(formatRupiah(parseInt(get_Another['TotSisa'])));

		const get_Credit = Payment_Detail['Credit'];
		$('.tblCredit').find('.invoice').html(formatRupiah(parseInt(get_Credit['Tot'])));
		$('.tblCredit').find('.pay').html(formatRupiah(parseInt(get_Credit['TotPay'])));
		$('.tblCredit').find('.sisa').html(formatRupiah(parseInt(get_Credit['TotSisa'])));

		// show detail table 
		const tblPaidOffSelector =  $('.tblPaidOff');
		const dataMhs_PaidOff =  response['Paid_Off_detail']['data_mhs'];
		Summary_Payment_Students_detail(dataMhs_PaidOff,tblPaidOffSelector);

		let tblSelector =  $('.tblunpaidOff');
		let dataMhs=  response['Unpaid_Off_detail']['data_mhs'];
		Summary_Payment_Students_detail(dataMhs,tblSelector);

		tblSelector =  $('.tblunsetPaid');
		dataMhs=  response['Unset_Paid_detail']['data_mhs'];
		Summary_Payment_Students_detail(dataMhs,tblSelector);

	}

	const excecute_barChart = (element,response) => {
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
		$.plot(element, ds, $.extend(true, {}, Plugins.getFlotDefaults()	, {	
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
	}

	function loadChartSummaryPayment()
	{
		loading_page("#chart_bars_vertical");
        var url = base_url_js+'finance/summary_payment';
		$.post(url,function (resultJson) {
		   var response = jQuery.parseJSON(resultJson);
		   console.log(response);

		   payment_information_type(response);

		   // barchart payment type
		   excecute_barChart('#SPP_chart_bars_vertical',response['Payment_Detail']['SPP']['BarChart']);

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
		   // console.log(response);
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

	const sync_payment = async(selector) => {
		const htmlButton = selector.html();
		loading_button2(selector);

		const url = base_url_js + 'page/finance/c_finance/sync_payment';

		try{
			const response =  await AjaxSubmitFormPromises(url);
			if (response.status == 1) {
				toastr.success('Sync success');
			}
			else
			{
				toastr.info(response.msg);
			}
		}
		catch(err){
			console.log(err);
			toastr.error('something wrong');
		}

		end_loading_button2(selector,htmlButton);
	}

	$(document).on('click','.btn-sync-payment',function(e){
		const itsme = $(this);
		sync_payment(itsme);	
	})
</script>