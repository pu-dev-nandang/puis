<style type="text/css">
.borderless thead>tr>th {
    vertical-align: bottom;
    border-bottom: none !important;
}

.borderless thead>tr>th, .borderless tbody>tr>th, .borderless tfoot>tr>th, .borderless thead>tr>td, .borderless tbody>tr>td, .borderless tfoot>tr>td {
	    padding: 8px;
	    line-height: 1.428571429;
	    vertical-align: top;
	    border-top: none !important;
	}
@page {
  size: A4;
  margin: 1;
}
@media print {
    .container { 
      display: block !important;
        font-size: 9px; 
        top: -40pt;
        left:0pt;
        right: 0pt;
    }
    table{
    	font-size: 8px; 
    }
    
    .btn,.noPrint a { 
    	display:none !important;
    }
}
</style>
<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="row noPrint">
	<div class="col-xs-2">
		<?php if ($this->session->userdata('IDdepartementNavigation') == 4): ?>
			<div><a href="<?php echo base_url().'purchasing/transaction/po/list' ?>" class = "btn btn-warning"> <i class="fa fa-arrow-circle-left"></i> Back to List</a></div>
		<?php else: ?>
			<div><a href="<?php echo base_url().'budgeting_po' ?>" class = "btn btn-warning"> <i class="fa fa-arrow-circle-left"></i> Back to List</a></div>
		<?php endif ?>
			<?php if ($bool): ?>

			<?php endif ?>
	</div>
</div>
<div id="DocPenawaran" class="row"></div>
<div class="row" style="margin-top: 2px;">
	<div class="col-xs-12">
		<table class="table borderless">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align :center">
						<p><h3>Purchase Order</h3></p>
						<p><?php echo $Code ?></p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<?php if ($bool): ?>
<div id = "PageContain">
	
</div>
<?php else: ?>
	<div class="row">
		<div class="col-xs-12" align="center">
			<h2>Your not Authorize</h2>
		</div>
	</div>	
<?php endif ?>

<script type="text/javascript">
	$(document).ready(function() {
	    $("#container").attr('class','fixed-header sidebar-closed');
	}); // exit document Function
</script>

<?php if ($bool): ?>
<script type="text/javascript">
	var DivisionID = '<?php echo $this->session->userdata('IDdepartementNavigation') ?>';
	var ClassDt = {
		Code : "<?php echo $Code ?>",
		po_create_m  : <?php echo json_encode($G_data) ?>,
		po_data : [],
		PRCode_arr : [],
		total_po_detail : 0,
	};
	$(document).ready(function() {
	   loadingStart();
	   Get_data_po().then(function(data){
	   		ClassDt.po_data = data;
	   		WriteHtml();
	   })
	}); // exit document Function


	function Get_data_po()
	{
		var def = jQuery.Deferred();
		var url = base_url_js+"rest2/__Get_data_po_by_Code";
		var data = {
		    Code : ClassDt.Code,
		    auth : 's3Cr3T-G4N',
		};
		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{token:token},function (resultJson) {
			def.resolve(resultJson);
		}).fail(function() {
		  toastr.info('No Result Data');
		  def.reject(); 
		})
			
		return def.promise();
	}

	function WriteHtml()
	{
		var dt = ClassDt.po_data;
		var po_create = dt.po_create;
		var JsonStatus = po_create[0]['JsonStatus'];
		JsonStatus = jQuery.parseJSON(JsonStatus);
		var PICPU = JsonStatus[0]['Name'];
		// PageContain
		var html = '<div class = "row">'+
						'<div class = "col-xs-12">'+
							'<table class = "table borderless" style = "margin-left: -8px;">'+
								'<thead></thead>'+
								'<tbody>'+
									'<tr>'+
										'<td>'+
											'<div><b>YAY Pendidikan Agung Podomoro</b></div>'+
											'<div>Podomoro City APL Tower, Lantai 5</div>'+
											'<div>Jl. Let Jend. S. Parman Kav 28, Jakarta 11470</div>'+
											'<div>Telp 021 29200456</div>'+
											'<div style = "margin-top:20px;">PIC : '+PICPU+'</div>'+
										'</td>'+
										'<td></td>'+
										'<td>'+
											'<div style = "margin-left : 50%">'+
												'<div><u>Jakarta, '+po_create[0]['CreatedAt_Indo']+'</u></div>'+
												'<div style = "margin-top : 20px;">Kepada Yth :</div>'+
												'<div><b>'+po_create[0]['NamaSupplier']+'</b></div>'+
												'<div>'+po_create[0]['PICName']+' ('+po_create[0]['NoTelp']+')'+'</div>'+
											'</div>'+	
										'</td>'+
									'</tr>'+
								'</tbody>'+
							'</table>'+
						'</div>'+						
					'</div>'+
					'<div class = "row" style = "margin-top : 10px;">'+
						'<div class = "col-xs-12">'+
							'<div>Bersama ini kami meminta untuk dikirim barang-barang sebagai berikut :</div>'+
						'</div>'+
					'</div>'+
					'<div id = "r_tblDetail"></div>'+
					'<div id = "r_terbilang"></div>'+
					'<div id = "r_signatures"></div>'+
					'<div id = "r_footer"></div>'+
					'<div id = "r_action"></div>';
		$('#PageContain').html(html);
		makeTblDetail();
		makeSignatures();
		makeFooter();
		makeDocPenawaran();
		makeAction();						
	}

	function makeDocPenawaran()
	{
		var po_data = ClassDt.po_data;
		var po_create = po_data['po_create'];
		var FileOffer = jQuery.parseJSON(po_create[0]['FileOffer']);
		var StatusName = '';
		switch(po_create[0]['Status']) {
				  case 0:
				  case '0':
				  	StatusName = 'Draft';
				    break;
				  case 1:
				  case '1':
				  	StatusName = 'Issued & Approval Process';
				    break;
				  case 2:
				  case '2':
				  	StatusName = 'Approval Done';
				    break;
				  case -1:
				  case '-1':
				  	StatusName = 'Reject';
				    break;       
				  case 4:
				  case '4':
				  	StatusName = 'Cancel';
				    break;    
		}
		$('#DocPenawaran').html('<div class="col-xs-12"><div style = "color : red">Status : '+StatusName+'</div><div><a href="'+base_url_js+'fileGetAny/budgeting-po-'+FileOffer[0]+'" target="_blank"> Doc Penawaran</a></div></div>');

	}

	function makeAction()
	{
		// r_action sessionNIP
		/*
			1.Baca Status dahulu
			2.Status Approved semua maka show PDF & muncul tombol Create SPB
			3.Ada tombol edit ke halaman Open PO
			4.ada tombol edit PO
			5.Tombol Approve & Reject
		*/
		var html = '<div class = "row noPrint"><div class = "col-xs-12"></div></div>'; 
		var po_data = ClassDt.po_data;
		var po_create = po_data['po_create'];
		var btn_edit = '<button class = "btn btn-primary" id = "BtnEdit"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>';
		var btn_submit = '<button class = "btn btn-success" id = "BtnSubmit" disabled> <i class="fa fa-database" aria-hidden="true"></i> Submit</button>';
		var btn_custom = '<button class = "btn btn-warning" id = "BtnCustom"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Custom</button>';
		var btn_approve = '<button class="btn btn-primary" id="Approve" action="approve">Approve</button>';
		var btn_reject = '<button class="btn btn-inverse" id="Reject" action="reject">Reject</button>';
		var btn_pdf = '<button class="btn btn-default" id="pdfprint"> <i class="fa fa-file-pdf-o"></i> Print PDF</button>';
		var btn_create_spb = '<button class="btn btn-default" id="btn_create_spb"> <i class="fa fa-file-text" aria-hidden="true"></i> Create SPB</button>';
		var Status = po_create[0]['Status'];
		switch(Status) {
		  case 0:
		  case '0':
		  	var JsonStatus = po_create[0]['JsonStatus'];
		  	JsonStatus = jQuery.parseJSON(JsonStatus);
		  	if (JsonStatus[0]['NIP'] == sessionNIP || DivisionID == '4') {
		  		$('#r_action').html(html);
		  		$('#r_action').find('.col-xs-12').html('<div class = "pull-right">'+btn_edit+'&nbsp'+btn_custom+'&nbsp'+btn_submit+'</div>');
		  	}
		    
		    break;
		  case 1:
		  case '1':
		    var JsonStatus = po_create[0]['JsonStatus'];
		    JsonStatus = jQuery.parseJSON(JsonStatus);

		    if (JsonStatus[0]['NIP'] == sessionNIP || DivisionID == '4') {
		    	var booledit2 = true;
		    	for (var i = 1; i < JsonStatus.length; i++) {
		    		if (JsonStatus[i].Status == 1 || JsonStatus[i].Status == '1') {
		    			booledit2 = false;
		    			break;
		    		}
		    	}

		    	if (booledit2) {
		    		$('#r_action').html(html);
		    		$('#r_action').find('.col-xs-12').html('<div class = "pull-right">'+btn_edit+'&nbsp'+btn_custom+'&nbsp'+btn_submit+'</div>');
		    	}
		    }

		    // for approval
		    	var bool = false;
		    	var HierarkiApproval = 0; // for check hierarki approval;
		    	var NumberOfApproval = 0; // for check hierarki approval;
		    	var NIP = sessionNIP;
		    	for (var i = 0; i < JsonStatus.length; i++) {
		    		NumberOfApproval++;
		    		if (JsonStatus[i]['Status'] == 0) {
		    			// check status before
		    			if (i > 0) {
		    				var ii = i - 1;
		    				if (JsonStatus[ii]['Status'] == 1) {
		    					HierarkiApproval++;
		    				}

		    				// if (JsonStatus[ii]['NameTypeDesc'] != 'Approval by') {
		    				// 	HierarkiApproval++;
		    				// }
		    				// HierarkiApproval++;
		    			}
		    			else
		    			{
		    				HierarkiApproval++;
		    			}
		    			
		    			// if (NIP == JsonStatus[i]['NIP'] && JsonStatus[i]['NameTypeDesc'] == 'Approval by') {
		    			if (NIP == JsonStatus[i]['NIP']) {
		    				bool = true;
		    				break;
		    			}
		    		}
		    		else
		    		{
		    			HierarkiApproval++;
		    		}
		    	}


		    	if (bool && HierarkiApproval == NumberOfApproval) { // rule approval
		    		$('#r_action').html(html);
		    		$('#r_action').find('.col-xs-12').html('<div class = "pull-right">'+btn_approve+'&nbsp'+btn_reject+'</div>');
		    		$('#Approve').attr('approval_number',NumberOfApproval);
		    		$('#Reject').attr('approval_number',NumberOfApproval);
		    	}

		    break;
		  case 2:
		  case '2':
		    // code block
		    break;
		  case -1:
		  case '-1':
		    // code block
		    break;
		  case 4:
		  case '4':
		    // code block
		    break;       
		  default:
		    // code block
		}

	}

	function makeFooter()
	{
		//r_footer
		var html = '<div class = "row" style = "margin-top : 40px;">'+
						'<div class = "col-xs-4">'+
							'<table class = "table borderless">'+
									'<thead></thead>'+
									'<tbody>'+
										'<tr style = "height : 70px">'+
											'<td>'+
												'No. PR : '+
											'</td>'+
											'<td>';

		var arr = ClassDt.PRCode_arr;
		var t = '';									
		for (var i = 0; i < arr.length; i++) {
			t += '<li>'+arr[i]+'</li>';
		}

		html += t;
		html += '</td></tr>';
		html += '<tr style = "height : 100px">'+
					'<td colspan = "2">'+
						'<b>Diterima oleh Vendor,'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<td colspan = "2">'+
						'<i>(Tandatangan,Nama,Stampel),</br>Note : Copi PO mohon dapat dilampirkan pada kami bersama invoice</i>'+
					'</td>'+
				'</tr>';

		html += '</tbody></table></div></div>';		
		$('#r_footer').html(html);


	}

	function makeSignatures(){
		// r_signatures
		var dt = ClassDt.po_data;
		var po_create = dt['po_create'];
		var html = '<div class= "row" style = "margin-top : 40px;">'+
						'<div class = "col-xs-12">'+
							'<table class = "table borderless">'+
								'<thead>'+
									'<tr>'
				;
		var JsonStatus = jQuery.parseJSON(po_create[0]['JsonStatus']);
		for (var i = 0; i < JsonStatus.length; i++) {
			var style = '';
			if (i == 0) {
				style = 'style = "text-align :left"';
			}
			else if(parseInt(JsonStatus.length)-1 == i){
				style = 'style = "text-align :right"';
			}
			else
			{
				style = 'style = "text-align :center"';
			}
			html += '<th '+style+'>'+
						JsonStatus[i].NameTypeDesc+
					'</th>';	
		}

		html += '</tr>';

		html += '</thead>'+
					'<tbody>'+
						'<tr style = "height : 51px">';
		for (var i = 0; i < JsonStatus.length; i++) {
			var v = '-';
			if (JsonStatus[i].Status == '2' || JsonStatus[i].Status == 2) {
				v = '<i class="fa fa-times" aria-hidden="true" style="color: red;"></i>';
			}
			else if(JsonStatus[i].Status == '1' || JsonStatus[i].Status == 1 )
			{
				v = '<i class="fa fa-check" style="color: green;"></i>';
			}
			else
			{
				v = '-';
			}

			var style = '';
			if (i == 0) {
				style = 'style = "text-align :left"';
			}
			else if(parseInt(JsonStatus.length)-1 == i){
				style = 'style = "text-align :right"';
			}
			else
			{
				style = 'style = "text-align :center"';
			}
			html += '<td '+style+'>'+
						v+
					'</td>';	
		}

		html += '</tr></tbody>';				
		html += '<tfoot>'+
					'<tr>';

		for (var i = 0; i < JsonStatus.length; i++) {
			var style = '';
			if (i == 0) {
				style = 'style = "text-align :left"';
			}
			else if(parseInt(JsonStatus.length)-1 == i){
				style = 'style = "text-align :right"';
			}
			else
			{
				style = 'style = "text-align :center"';
			}
			html += '<td '+style+'><b>'+JsonStatus[i].Name+'</b></td>';		
		}

		html += '</tr></tfoot></table></div></div>';							
		$('#r_signatures').html(html);

	}

	function makeTblDetail()
	{
		// r_tblDetail
		var dt = ClassDt.po_data;
		var po_create = dt['po_create'];
		htmlBtnAdd =    '';
		var IsiInputPO = MakeIsiPO();
		var Subtotal = 	parseInt(ClassDt.total_po_detail)+parseInt(po_create[0]['AnotherCost'])	// 0 adalah persentase		
		var htmlInputPO = '<div class = "row" style = "margin-top : 15px;">'+
							'<div class = "col-md-12">'+
								//'<div class="table-responsive">'+
									'<table class="table table-bordered tableData" id ="table_input_po">'+
									'<thead>'+
									'<tr>'+
										'<th width = "3%" style = "text-align: center;background: #67a9a2;color: #FFFFFF;">No</th>'+
			                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 350px;">Nama Barang</th>'+
			                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 350px;">Spesification</th>'+
			                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 200px;">Date Needed</th>'+
			                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 100px;">Qty</th>'+
			                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 250px;">Harga</th>'+
    		                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 150px;">PPN(%)</th>'+
    		                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 150px;">Discount(%)</th>'+
			                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 250px;">Sub Total</th>'+
									'</tr></thead>'+
									'<tbody>'+IsiInputPO+'</tbody>'+
									'<tfoot>'+
										'<tr style = "background-color: #3c6560;color: #FFFFFF">'+
											'<td colspan = "6">Total</td>'+
											'<td colspan = "3" class = "tdTotal" value = "'+ClassDt.total_po_detail+'">'+formatRupiah(ClassDt.total_po_detail)+'</td>'+
										'</tr>'+
										'<tr style = "background-color: #3c6560;color: #FFFFFF">'+
											'<td colspan = "6">Biaya Lain-Lain</td>'+
											'<td colspan = "3" class = "tdAnotherCost" value = "'+po_create[0]['AnotherCost']+'">'+formatRupiah(po_create[0]['AnotherCost'])+'</td>'+
										'</tr>'+
										'<tr style = "background-color: #3c6560;color: #FFFFFF">'+
											'<td colspan = "6">Sub Total</td>'+
											'<td colspan = "3" class = "tdSubtotal_All" value = "'+Subtotal+'">'+formatRupiah(Subtotal)+'</td>'+
										'</tr>'+
										'<tr>'+
											'<td colspan = "9" class = "tdNotes" value = "'+po_create[0]['Notes']+'"><b>'+po_create[0]['Notes']+'</b></td>'+
										'</tr>'+		
									'</table>'+
								//'</div>'+
						   '</div></div>';

		_ajax_terbilang(Subtotal).then(function(data){
			var html = htmlBtnAdd + htmlInputPO;			   
			$('#r_tblDetail').html(html);
			$('#r_terbilang').html('<div class = "row" style = "margin-top : 20px;">'+
										'<div class="col-xs-12">'+
											'<b>Terbilang (Rupiah) : '+data+'</b>'+
										'</div>'+
									'</div>'		
			);

	    	loadingEnd(1000);
		})		   

	}

	function _ajax_terbilang(bilangan)
	{
		var def = jQuery.Deferred();
		var url = base_url_js+"rest2/__ajax_terbilang";
		var data = {
		    bilangan : bilangan,
		    auth : 's3Cr3T-G4N',
		};
		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{token:token},function (resultJson) {
			def.resolve(resultJson);
		}).fail(function() {
		  toastr.info('No Result Data');
		  def.reject(); 
		})
			
		return def.promise();
	}

	function MakeIsiPO()
	{
		var dt = ClassDt.po_data;
		var po_detail = dt['po_detail'];
		var html = '';
		var total = 0;
		for (var i = 0; i < po_detail.length; i++) {
			var Spesification = '';
			DetailCatalog = jQuery.parseJSON(po_detail[i]['DetailCatalog']);
			if (typeof(DetailCatalog) == 'object') {
				Spesification = '<div>Detail Catalog</div>';
				Spesification += '<div>';
				for (var prop in DetailCatalog) {
					Spesification += prop + ' :  '+DetailCatalog[prop]+'<br>';
				}

				Spesification +='</div>';
			}

			// if (po_detail[i]['Desc'] != '' && po_detail[i]['Desc'] != null && po_detail[i]['Desc'] != undefined) {
			// 	var st = (Spesification != '') ? 'style = "margin-top : 5px;"' : '';
			// 	Spesification += '<div '+st+'>Desc</div>';
			// 	Spesification += '<div>'+po_detail[i]['Desc']+'</div>';

			// }

			if (po_detail[i]['Spec_add'] != '' && po_detail[i]['Spec_add'] != null && po_detail[i]['Spec_add'] != undefined) {
				Spesification += '<div style = "margin-top : 5px;">Additional</div>';
				Spesification += '<div>'+po_detail[i]['Spec_add']+'</div>';
			}
			
			html +='<tr ID_po_detail = "'+po_detail[i]['ID_po_detail']+'">'+
						'<td>'+(i+1)+'</td>'+
						'<td>'+po_detail[i]['Item']+'<br>'+po_detail[i]['Desc']+'</td>'+
						'<td>'+Spesification+'</td>'+
						'<td>'+'<div align="center">'+po_detail[i]['DateNeeded']+'</div></td>'+
						'<td class = "tdqty" value = "'+po_detail[i]['QtyPR']+'">'+'<div align="center">'+po_detail[i]['QtyPR']+'</div></td>'+
						'<td class = "tdUnitCost" value = "'+po_detail[i]['UnitCost_PO']+'">'+'<div align="center">'+formatRupiah(po_detail[i]['UnitCost_PO'])+'</div></td>'+
						'<td class = "tdPPN" value = "'+po_detail[i]['PPN_PO']+'">'+'<div align="center">'+po_detail[i]['PPN_PO']+'</div></td>'+
						'<td class = "tdDiscount" value = "'+po_detail[i]['Discount_PO']+'">'+'<div align="center">'+po_detail[i]['Discount_PO']+'</div></td>'+
						'<td class = "tdSubtotal" value = "'+po_detail[i]['Subtotal']+'" max = "'+po_detail[i]['Subtotal_PR']+'">'+'<div align="center">'+formatRupiah(po_detail[i]['Subtotal'])+'</div></td>'+
					'</tr>';

			total = parseInt(total) + parseInt(po_detail[i]['Subtotal']);

			// add PRCode
			if (ClassDt.PRCode_arr.length == 0) {
				ClassDt.PRCode_arr.push(po_detail[i]['PRCode']);
			}
			else
			{
				var bool = true;
				for (var j = 0; j < ClassDt.PRCode_arr.length; j++) {
					var arr = ClassDt.PRCode_arr;
					if (arr[j] == po_detail[i]['PRCode']) {
						bool = false;
						break;
					}
				}

				if (bool) {
					ClassDt.PRCode_arr.push(po_detail[i]['PRCode']);
				}
			}			
		}

		ClassDt.total_po_detail = total;
		return html;
	}

	$(document).off('click', '#BtnEdit').on('click', '#BtnEdit',function(e) {
		$(this).attr('class','btn btn-danger');
		$(this).find('i').remove();
		$(this).html('Cancel');
		$(this).attr('id','BtnCancel');
		__input_reload();
		$('#BtnSubmit').prop('disabled',false);
		
	})

	function __input_reload()
	{
		$('#table_input_po tbody').find('tr').each(function(){
			var value = $(this).find('.tdUnitCost').attr('value');
			var n = value.indexOf(".");
			value = value.substring(0, n);
			$(this).find('.tdUnitCost').html('<input type="text" class="form-control UnitCost" value="'+value+'">');
			$(this).find('.UnitCost').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
			$(this).find('.UnitCost').maskMoney('mask', '9894');

			var value = $(this).find('.tdPPN').attr('value');
			var n = value.indexOf(".");
			value = value.substring(0, n);
			$(this).find('.tdPPN').html('<input type="number" class="form-control PPN" value="'+value+'">');
			
			var value = $(this).find('.tdDiscount').attr('value');
			var n = value.indexOf(".");
			value = value.substring(0, n);
			$(this).find('.tdDiscount').html('<input type="number" class="form-control Discount" value="'+value+'">');

		})

		var value  = $('#table_input_po tfoot').find('.tdAnotherCost').attr('value');
		var n = value.indexOf(".");
		value = value.substring(0, n);
		$('#table_input_po tfoot').find('.tdAnotherCost').html('<input type="text" class="form-control AnotherCost" value="'+value+'">');
		$('#table_input_po tfoot').find('.AnotherCost').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		$('#table_input_po tfoot').find('.AnotherCost').maskMoney('mask', '9894');

		var value  = $('#table_input_po tfoot').find('.tdNotes').attr('value');
		$('#table_input_po tfoot').find('.tdNotes').html('<input type="text" class="form-control Notes" value="'+value+'">');
	}

	$(document).off('keyup', '.Discount,.PPN').on('keyup', '.Discount,.PPN',function(e) {
		if ($(this).val() == '') {
			$(this).val(0);
		}
	})

	$(document).off('keyup', '.UnitCost,.Discount,.PPN,.AnotherCost').on('keyup', '.UnitCost,.Discount,.PPN,.AnotherCost',function(e) {
		var tr = $(this).closest('tr');
		var ChangeBool = CountSubTotal_table(tr);
		if (!ChangeBool) {
			__input_reload();
			var bool = CountSubTotal_table(tr);
			if (bool) {
				$('#BtnSubmit').prop('disabled',false);
			}
			
		}
	})

	

	$(document).off('keydown', '.Discount,.PPN').on('keydown', '.Discount,.PPN',function(e) {
		if (e.keyCode === 190) {
		    e.preventDefault();
		}
	})

	function CountSubTotal_table(ev)
	{
		var SubTotal_All = 0;
		var bool = true;
		$('#table_input_po tbody').find('tr').each(function(){
			if (bool) {
				var ev = $(this);
				var qty = ev.find('.tdqty').attr('value');
				var UnitCost = ev.find('.UnitCost').val();
				UnitCost = findAndReplace(UnitCost, ".","");
				var PPN = ev.find('.PPN').val();
				var Discount = ev.find('.Discount').val();
				var total_raw = (parseInt(UnitCost) * parseInt(qty));
				var PPN_ = (parseInt(PPN) * parseInt(total_raw) ) / 100;
				var Discount_ = (parseInt(Discount) * parseInt(total_raw)) / 100;
				var Subtotal = parseInt(total_raw) + parseInt(PPN_) - parseInt(Discount_);
				var Subtotal_limit = ev.find('.tdSubtotal').attr('max');
				var n = Subtotal_limit.indexOf(".");
				Subtotal_limit = Subtotal_limit.substring(0, n);
				Subtotal_limit = parseInt(Subtotal_limit);
				if (Subtotal > Subtotal_limit) {
					var NmBrg = ev.find('td:eq(1)').html();
					toastr.info('Subtotal '+NmBrg + ' melebihi dari Anggaran PR yaitu '+formatRupiah(Subtotal_limit));
					bool = false;
					return;
				}
				else
				{
					ev.find('.tdSubtotal').attr('value',Subtotal);
					ev.find('.tdSubtotal').html('<div align="center">'+formatRupiah(Subtotal)+'</div>');
					SubTotal_All = parseInt(SubTotal_All) + parseInt(Subtotal);
				}
			}

		})

		if (bool) {
			var AnotherCost = $('#table_input_po tfoot').find('.AnotherCost').val();
			AnotherCost = findAndReplace(AnotherCost, ".","");
			$('#table_input_po tfoot').find('.tdTotal').html(formatRupiah(SubTotal_All));
			SubTotal_All = parseInt(SubTotal_All) + parseInt(AnotherCost);
			$('#table_input_po tfoot').find('.tdSubtotal_All').html(formatRupiah(SubTotal_All));
			// loading page r_terbilang for ajax later && show total
				loading_page('#r_terbilang');
				_ajax_terbilang(SubTotal_All).then(function(data){
					$('#r_terbilang').html('<div class = "row" style = "margin-top : 20px;">'+
												'<div class="col-xs-12">'+
													'<b>Terbilang (Rupiah) : '+data+'</b>'+
												'</div>'+
											'</div>'		
					);
				})
		}

		return bool;		
	}

	$(document).off('click', '#BtnCancel').on('click', '#BtnCancel',function(e) {
		window.location.reload(true);	
	})

	$(document).off('click', '#BtnSubmit').on('click', '#BtnSubmit',function(e) {
		if (confirm('Are you sure ?')) {
			loadingStart();
			var po_data = ClassDt.po_data;
			var arr_post_data_detail = [];
			$('#table_input_po tbody').find('tr').each(function(){
				var ID_po_detail = $(this).attr('id_po_detail');
				var UnitCost = $(this).find('.UnitCost').val();
				UnitCost = findAndReplace(UnitCost, ".","");
				var Discount = $(this).find('.Discount').val();
				var PPN = $(this).find('.PPN').val();
				var Subtotal = $(this).find('.tdSubtotal').attr('value');
				var temp = {
					ID_po_detail :ID_po_detail,
					UnitCost : UnitCost,
					Discount : Discount,
					PPN : PPN,
					Subtotal : Subtotal,
				};

				arr_post_data_detail.push(temp);
			})

			var AnotherCost = $('#table_input_po tfoot').find('.AnotherCost').val();
			AnotherCost = findAndReplace(AnotherCost, ".","");
			var Notes =  $('#table_input_po tfoot').find('.Notes').val();
			var url = base_url_js+"po_spk/submit_create";
			var data = {
			    po_data : po_data,
			    arr_post_data_detail : arr_post_data_detail,
			    AnotherCost : AnotherCost,
			    Notes : Notes,
			};
			var token = jwt_encode(data,"UAP)(*");
			var action_mode = 'modifycreated';
				action_mode = jwt_encode(action_mode,"UAP)(*");
			var action_submit = 'PO';
				action_submit = jwt_encode(action_submit,"UAP)(*");	
			$.post(url,{token:token,action_mode:action_mode,action_submit:action_submit},function (resultJson) {
				var rs = jQuery.parseJSON(resultJson);
				if (rs.Status == 1) {
					Get_data_po().then(function(data){
							ClassDt.po_data = data;
							WriteHtml();
					})
				}
				else
				{
					if (rs.Change == 1) {
						toastr.info('The Data already have updated by another person,Please check !!!');
						Get_data_po().then(function(data){
								ClassDt.po_data = data;
								WriteHtml();
						})
					}
					else
					{
						toastr.error(rs.msg,'!!!Failed');
					}
				}
			}).fail(function() {
			  toastr.error('','!!!Failed');
			  
			})

		}

	})

	$(document).off('click', '#BtnCancel').on('click', '#BtnCancel',function(e) {
		window.location.reload(true);	
	})		
</script>
<?php endif ?>	