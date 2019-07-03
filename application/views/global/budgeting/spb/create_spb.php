<style type="text/css">
	.thumbnail {
	    display: inline-block;
	    display: block;
	    height: auto;
	    max-width: 100%;
	    padding: 16px;
	    line-height: 1.428571429;
	    background-color: #fff;
	    border: 1px solid #aec10b;
	    border-radius: 20px;
	    -webkit-transition: all .2s ease-in-out;
	    transition: all .2s ease-in-out;
	}

	#datatablesServer.dataTable tbody tr:hover {
	   background-color:#71d1eb !important;
	   cursor: pointer;
	}

	h3.header-blue {
	    margin-top: 0px;
	    border-left: 7px solid #2196F3;
	    padding-left: 10px;
	    font-weight: bold;
	}


	.borderless thead>tr>th {
	    vertical-align: bottom;
	    border-bottom: none !important;
	}

	.borderless thead>tr>th, .borderless tbody>tr>th, .borderless tfoot>tr>th, .borderless thead>tr>td, .borderless tbody>tr>td, .borderless tfoot>tr>td {
		    padding: 4px;
		    line-height: 1.428571429;
		    vertical-align: top;
		    border-top: none !important;
	}

	.TD1 {
		width: 35%;
	}

	.TD2 {
		width: 5%;
	}
</style>
<div class="row">
	<div class="col-xs-6 col-md-offset-3" style="min-width: 600px;overflow: auto;">
		<div class="thumbnail">
			<div id = "page_po_list"></div>
		</div>	
	</div>
</div>
<div class="row" style="margin-top: 10px;">
	<div class="col-md-8 col-md-offset-2" style="min-width: 800px;overflow: auto;">
		<div class="well">
			<div align="center"><h2>Surat Permohonan Pembayaran</h2></div>
			<hr style="height:2px;border:none;color:#333;background-color:#333;margin-top: -3px;">
			<table class="table borderless" style="font-weight: bold;">
				<thead></thead>
				<tbody>
					<tr>
						<td class="TD1">
							NOMOR
						</td>
						<td class="TD2">
							:
						</td>
						<td>
							[CodeSPB]
						</td>				
					</tr>
					<tr>
						<td class="TD1">
							VENDOR/SUPPLIER
						</td>
						<td class="TD2">
							:
						</td>
						<td>
							[Vendor]
						</td>				
					</tr>
					<tr>
						<td class="TD1">
							NO KWT/INV
						</td>
						<td class="TD2">
							:
						</td>
						<td>
							[NO Invoice]
							<br>
							<label style="color: red">Upload Invoice</label>
							<input type="file" data-style="fileinput" class="BrowseInvoice" id="BrowseInvoice" accept="image/*,application/pdf">
							<div id = "FileInvoice">
								
							</div>
							<br>
							[NO Tanda Terima]
							<br>
							<label style="color: red">Upload Tanda Terima</label>
							<input type="file" data-style="fileinput" class="BrowseTT" id="BrowseTT" accept="image/*,application/pdf">
							<div id = "FileTT">
								
							</div>
						</td>				
					</tr>
					<tr>
						<td class="TD1">
							TANGGAL
						</td>
						<td class="TD2">
							:
						</td>
						<td>
							[Tanggal]
						</td>				
					</tr>
					<tr>
						<td class="TD1">
							PERIHAL
						</td>
						<td class="TD2">
							:
						</td>
						<td>
							[Perihal]
						</td>				
					</tr>
				</tbody>
			</table>
			<hr style="height:2px;border:none;color:#333;background-color:#333;margin-top: -3px;">
			<table class="table borderless">
				<thead>
					<tr>
						<td class="TD1">
							Mohon dibayarkan / ditransfer kepada
						</td>
						<td>
							<b>[Vendor]</b>
						</td>
					</tr>
					<tr style="height: 50px;">
						<td class="TD1">
							No Rekening
						</td>
						<td>
							<b>[No Rek] & [Select Bank]</b>
						</td>
					</tr>
				</thead>
			</table>
			<table class="table borderless">	
				<tbody>
					<tr>
						<td>
							<b>PEMBAYARAN : </b>
						</td>
					</tr>
					<tr>
						<td class="TD1">
							<b>Harga</b>
						</td>
						<td class="TD2">
							=
						</td>
						<td>
							[Input Nominal] 
						</td>
						<td>
							(include PPN)
						</td>
					</tr>
					<tr>
						<td class="TD1">
							<b>Pembayaran I</b>
						</td>
						<td class="TD2">
							=
						</td>
						<td>
							[Input Nominal] 
							<br>
							<hr style="height:2px;border:none;color:#333;background-color:#333;margin-top: 5px;">
						</td>
						<td>
							(include PPN)
						</td>
					</tr>
					<tr style="height: 50px;">
						<td class="TD1">
							<b>Sisa Pembayaran</b>
						</td>
						<td class="TD2">
							=
						</td>
						<td>
							[Nominal auto script] 
						</td>
						<td>
							(include PPN)
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td>
							<p class="terbilang" style="font-weight: bold;">Terbilang : [Nominal auto script]</p>
						</td>
					</tr>
				</tfoot>
			</table>
			<div id="r_signatures">
				<div class="row">
					<div class="col-md-12">
						<table class="table table-striped table-bordered table-hover table-checkable tableApproval" style="margin-top : 5px">
							<thead>
								<tr>
									<th>Requested by</th>
									<th>Approval by</th>
									<th>Approval by</th>
								</tr>
							</thead>
							<tbody>
								<tr style="height : 51px">
									<td><i class="fa fa-check" style="color: green;"></i></td>
									<td><i class="fa fa-check" style="color: green;"></i></td>
									<td><i class="fa fa-check" style="color: green;"></i></td>
								</tr>	
								<tr>
									<td>Alhadi Rahman</td>
									<td>Nandang Mulyadi</td>
									<td>Irfan Firdaus</td>
								</tr>
							</tbody>
						</table>			
					</div>
				</div>
			</div>
			<div id = "r_action">
				<div class="row">
					<div class="col-md-12">
						<div class="pull-right">
							<button class="btn btn-primary" id="btnEditInput"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button> &nbsp
							<button class="btn btn-success"> Submit</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var ClassDt = {
		Dt_selection : [],
		ThisTableSelect : '',
		Dt_ChooseSelectPO : [],
		action_mode : '<?php echo $action_mode ?>',
		SPBCode : '<?php echo $SPBCode ?>',
		SPBData : [],
		htmlPage_po_list : function(){
			var html = '';
			html = '<div class = "row" style = "margin-right : 0px;margin-left:0px;">'+
					 '<div class col-md-12>'+
					 	'<div style="padding: 5px;">'+
					 		'<h3 class="header-blue">Choose PO / SPK</h3>'+
					 	'</div>'+
					 	'<div class = "table-responsive">'+
					 	'<table class="table table-bordered datatable2" id = "tableData_po">'+
					 		'<thead>'+
					 			'<tr>'+
					 				'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
					 				'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Code</th>'+
					 				'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Type</th>'+
					 				'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Supplier</th>'+
					 			'</tr>'+
					 		'<thead>'+
					 		'<tbody id="dataRow" style="background-color: #8ED6EA;"></tbody>'+
		        		'</table>'+
		        		'</div>'+
		        	 '</div>'+
		        	'</div>';

		    return html;    	 				
		},	
	};

	$(document).ready(function() {
		$('#page_po_list').html(ClassDt.htmlPage_po_list);
		loadingEnd(500);
	}); // exit document Function
</script>