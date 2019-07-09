<style type="text/css">
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
#table_input_po thead>tr>th, #table_input_po tbody>tr>th, #table_input_po tfoot>tr>th, #table_input_po thead>tr>td, #table_input_po tbody>tr>td, #table_input_po tfoot>tr>td {
	    padding: 4px;
	}

@page {
  size: A4;
  margin: 0.5;
}
@media print {
    .container { 
      display: block !important;
        font-size: 10px; 
        top: -35pt;
        page-break-after: always; /* Set Just One Page */
    }
    table{
    	font-size: 10px; 
    }
    
    .btn,.noPrint, a { 
    	display:none !important;
    }
}
</style>
<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="row noPrint">
	<div class="col-xs-2">
		<div><a href="<?php echo base_url().'budgeting_menu/pembayaran/spb' ?>" class = "btn btn-warning"> <i class="fa fa-arrow-circle-left"></i> Back to List</a></div>
	</div>
	<div class="col-md-8" style="min-width: 800px;overflow: auto;">
		<div class="well" id = "pageContent">

		</div>
	</div>
</div>
<script type="text/javascript">
	var IDDepartementPUBudget = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";
	var ClassDt = {
		Code_po_create : "<?php echo $Code_po_create ?>",
		Code : "<?php echo $Code ?>",
		ThisTableSelect : '',
		Dataselected : [],
		Dataselected2 : [],
		po_data : [],
		G_data_bank : <?php echo json_encode($G_data_bank) ?>,
	};
	$(document).ready(function() {
		$("#container").attr('class','fixed-header sidebar-closed');
		loadingStart();
	    // loadFirst
	    loadFirst();
	}); // exit document Function

	function loadFirst()
	{
		var Code_po_create = ClassDt.Code_po_create;
		var Code = ClassDt.Code;
		Get_data_spb_grpo(Code_po_create).then(function(data){
			var dt_arr = __getRsViewGRPO_SPB(Code,data);
			ClassDt.Dataselected = data; // all data spb by code po
			ClassDt.Dataselected2 = dt_arr; // SPB Selected
			Get_data_detail_po(Code_po_create).then(function(data2){
				console.log(ClassDt);
				// Define data
				ClassDt.po_data = data2;
				MakeDomHtml();
				loadingEnd(500);
			})

		})
	}

	function Get_data_spb_grpo(Code)
	{
       var def = jQuery.Deferred();
       var url = base_url_js + 'rest2/__Get_data_spb_grpo';
       var data = {
           auth : 's3Cr3T-G4N',
           Code : Code,
       };
       var token = jwt_encode(data,"UAP)(*");
       $.post(url,{ token:token },function (resultJson) {
       		def.resolve(resultJson);
       }).fail(function() {
       	  def.reject();
		  toastr.error('The Database connection error, please try again', 'Failed!!');
		}).always(function() {

		});
       return def.promise();
	}

	function Get_data_detail_po(Code)
	{
		var def = jQuery.Deferred();
		var url = base_url_js+"rest2/__Get_data_po_by_Code";
		var data = {
		    Code : Code,
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

	function __getRsViewGRPO_SPB(CodeSPB,Dataselected)
	{
		var arr=[];
		var dtspb = Dataselected.dtspb;
		var dtspb_rs = [];
		// get indeks array
		for (var i = 0; i < dtspb.length; i++) {
			if (CodeSPB == dtspb[i].Code) {
				break;
			}
		}

		dtspb_rs[0] = dtspb[i];

		var dtgood_receipt_spb = Dataselected.dtgood_receipt_spb;
		var dtgood_receipt_spb_rs = [];
		// get dtgood_receipt_spb from ID_spb_created
		dtgood_receipt_spb_rs[0] = dtgood_receipt_spb[i];
		var ID_good_receipt_spb = dtgood_receipt_spb_rs[0].ID;
		// get dtgood_receipt_detail from ID_good_receipt_spb
		var dtgood_receipt_detail_rs = [];
		var dtgood_receipt_detail = Dataselected.dtgood_receipt_detail;
		for (var i = 0; i < dtgood_receipt_detail.length; i++) {
			if (dtgood_receipt_detail[i].ID_good_receipt_spb == ID_good_receipt_spb) {
				dtgood_receipt_detail_rs.push(dtgood_receipt_detail[i]);
			}
		}

		arr = {
			dtspb : dtspb_rs,
			dtgood_receipt_spb : dtgood_receipt_spb_rs,
			dtgood_receipt_detail : dtgood_receipt_detail_rs,
		};

		return arr;
	}

	function MakeDomHtml()
	{
		var se_content = $('#pageContent');
		var Code = ClassDt.Code;
		var data = ClassDt.Dataselected;
		var Dataselected2 = ClassDt.Dataselected2;
		var dtspb = Dataselected2.dtspb;
		var po_data = ClassDt.po_data;
		var po_create = po_data.po_create;
		var InvoicePO = dtspb[0].InvoicePO;
		var Supplier = po_create[0].NamaSupplier;
		// hitung Left PO
		var InvoiceleftPO = parseInt(InvoicePO);
		for (var i = 0; i < data.dtspb.length; i++) {
			if (Code == data.dtspb[i].Code && i > 0) {
				if (data.dtspb[i].Invoice != null && data.dtspb[i].Invoice != 'null') {
					InvoiceleftPO -= parseInt(data.dtspb[parseInt(i) - 1].Invoice);
				}
				else
				{
					InvoiceleftPO -= parseInt(0);
				}
				break;
			}
		}

		// Fill Type Pembayaran
		var TypeInvoice = 'Pembayaran ' + (parseInt(i)+1);
		// update all null to be ''
		for (var i = 0; i < dtspb.length; i++) {
			var arr = dtspb[i];
			for(var key in arr) {
				if (arr[key] == null || arr[key] == 'null') {
					dtspb[i][key] = '';
				}
			}
		}

		// for edit jika CodeSPB belum di isi
		var Dis = (dtspb[0]['Code'] == '' || dtspb[0]['Code'] == null ) ? '' : 'disabled';
		var CodeWr = (dtspb[0]['Code'] == '' || dtspb[0]['Code'] == null ) ? 'auto by system' : dtspb[0]['Code'];
		var LinkFileInvoice = '';
		var LinkUploadTandaTerima = '';
		// var Invoice = 0;
		if (dtspb[0]['Code'] != '') {
			var UploadInvoice = jQuery.parseJSON(dtspb[0]['UploadInvoice']);
			UploadInvoice = UploadInvoice[0];
			LinkFileInvoice = '<a href = "'+base_url_js+'fileGetAny/budgeting-po-'+UploadInvoice+'" target="_blank" class = "Fileexist">File Document</a>';

			var UploadTandaTerima = jQuery.parseJSON(dtspb[0]['UploadTandaTerima']);
			UploadTandaTerima = UploadTandaTerima[0];
			LinkUploadTandaTerima = '<a href = "'+base_url_js+'fileGetAny/budgeting-po-'+UploadTandaTerima+'" target="_blank" class = "Fileexist">File Document</a>';

			// Invoice = dtspb[0]['Invoice'];
			// var n = Invoice.indexOf(".");
			// Invoice = Invoice.substring(0, n);

			// Fill Type Pembayaran
			var TypeInvoice = 'Pembayaran ' + (parseInt(i));
		}
		
		var html = '';
		html += '<div class = "row"><div class = "col-xs-12"><div align="center"><h2>Surat Permohonan Pembayaran</h2></div>'+
					'<hr style="height:2px;border:none;color:#333;background-color:#333;margin-top: -3px;">'+
					'<table class="table borderless" style="font-weight: bold;">'+
					'<thead></thead>'+
					'<tbody>'+
						'<tr>'+
							'<td class="TD1">'+
								'NOMOR'+
							'</td>'+
							'<td class="TD2">'+
								':'+
							'</td>'+
							'<td>'+
								'<span color = "red">'+CodeWr+'</span>'+
							'</td>'+
						'</tr>'+
						'<tr>'+
							'<td class="TD1">'+
								'VENDOR/SUPPLIER'+
							'</td>'+
							'<td class="TD2">'+
								':'+
							'</td>'+
							'<td>'+
								Supplier+
							'</td>'+		
						'</tr>'+
						'<tr>'+
							'<td class="TD1">'+
								'NO KWT/INV'+
							'</td>'+
							'<td class="TD2">'+
								':'+
							'</td>'+
							'<td>'+
								'<label>No Invoice</label>'+
								'<input type = "text" class = "form-control NoInvoice" placeholder = "Input No Invoice...." value="'+dtspb[0]['NoInvoice']+'" '+Dis+'>'+
								'<br>'+
								'<label style="color: red">Upload Invoice</label>'+
								'<input type="file" data-style="fileinput" class="BrowseInvoice" id="BrowseInvoice" accept="image/*,application/pdf" '+Dis+'><br>'+
								'<div id = "FileInvoice">'+
								LinkFileInvoice+
								'</div>'+
								'<br>'+
								'<label>No Tanda Terima</label>'+
								'<input type = "text" class = "form-control NoTT" placeholder = "Input No Tanda Terima...." value="'+dtspb[0]['NoTandaTerima']+'" '+Dis+'>'+
								'<br>'+
								'<label style="color: red">Upload Tanda Terima</label>'+
								'<input type="file" data-style="fileinput" class="BrowseTT" id="BrowseTT" accept="image/*,application/pdf">'+
								'<div id = "FileTT" '+Dis+'>'+
								LinkUploadTandaTerima+
								'</div>'+
							'</td>	'+			
						'</tr>'+
						'<tr>'+
							'<td class="TD1">'+
								'TANGGAL'+
							'</td>'+
							'<td class="TD2">'+
								':'+
							'</td>'+
							'<td>'+
								'<div class="input-group input-append date datetimepicker" style= "width:50%;">'+
		                            '<input data-format="yyyy-MM-dd" class="form-control TglSPB" type=" text" readonly="" value = "'+dtspb[0]['Datee']+'">'+
		                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
		                		'</div>'+
							'</td>	'+			
						'</tr>'+
						'<tr>'+
							'<td class="TD1">'+
								'PERIHAL'+
							'</td>'+
							'<td class="TD2">'+
								':'+
							'</td>'+
							'<td>'+
								'<input type = "text" class = "form-control Perihal" placeholder ="Input Perihal..." value="'+dtspb[0]['Perihal']+'" '+Dis+'>'+
							'</td>	'+			
						'</tr>'+
					'</tbody>'+
					'</table>'+
					'<hr style="height:2px;border:none;color:#333;background-color:#333;margin-top: -3px;">'+
					'<table class="table borderless">'+
						'<thead>'+
							'<tr>'+
								'<td class="TD1">'+
									'Mohon dibayarkan / ditransfer kepada'+
								'</td>'+
								'<td>'+
									'<b>'+Supplier+'</b>'+
								'</td>'+
							'</tr>'+
							'<tr style="height: 50px;">'+
								'<td class="TD1">'+
									'No Rekening'+
								'</td>'+
								'<td>'+
									'<div class= "row">'+
										'<div class="col-xs-5">'+
											OPBank(dtspb[0]['ID_bank'],Dis)+
										'</div>'+
										'<div class="col-xs-1">'+
											'<b>&</b>'+
										'</div>'+
										'<div class="col-xs-5">'+
											'<input type = "text" class = "form-control NoRekening" placeholder="No Rekening"  value="'+dtspb[0]['No_Rekening']+'" '+Dis+'>'+
										'</div>'+
									'</div>'+		
								'</td>'+
							'</tr>'+
						'</thead>'+
					'</table>'+
					'<table class="table borderless">'+	
						'<tbody>'+
							'<tr>'+
								'<td>'+
									'<b>PEMBAYARAN : </b>'+
								'</td>'+
							'</tr>'+
							'<tr>'+
								'<td class="TD1">'+
									'<b>Harga</b>'+
								'</td>'+
								'<td class="TD2">'+
									'='+
								'</td>'+
								'<td>'+
									formatRupiah(InvoiceleftPO)+
								'</td>'+
								'<td>'+
									'(include PPN)'+
								'</td>'+
							'</tr>'+
							'<tr>'+
								'<td class="TD1">'+
									'<label class="TypePembayaran" type = "'+TypeInvoice+'"><b>'+TypeInvoice+'</b></label>'+
								'</td>'+
								'<td class="TD2">'+
									'='+
								'</td>'+
								'<td>'+
									'<input type = "text" class = "form-control Money_Pembayaran" invoiceleftpo="'+(parseFloat(InvoiceleftPO)).toFixed(2)+'" value="'+parseInt(dtspb[0]['Invoice'])+'" '+Dis+'>'+ 
									'<br>'+
									'<hr style="height:2px;border:none;color:#333;background-color:#333;margin-top: 5px;">'+
								'</td>'+
								'<td>'+
									'(include PPN)'+
								'</td>'+
							'</tr>'+
							'<tr style="height: 50px;">'+
								'<td class="TD1">'+
									'<b>Sisa Pembayaran</b>'+
								'</td>'+
								'<td class="TD2">'+
									'='+
								'</td>'+
								'<td>'+
									'<label class = "Sisa_Pembayaran"></label>'+
								'</td>'+
								'<td>'+
									'(include PPN)'+
								'</td>'+
							'</tr>'+
						'</tbody>'+
						'<tfoot>'+
							'<tr>'+
								'<td>'+
									'<p class="terbilang" style="font-weight: bold;">Terbilang : [Nominal auto script]</p>'+
								'</td>'+
							'</tr>'+
						'</tfoot>'+
					'</table>'+
					'<div id="r_signatures"></div>'+
					'<div id = "r_action">'+
						'<div class="row">'+
							'<div class="col-md-12">'+
								'<div class="pull-right">'+
									''+
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div></div></div>';
		se_content.html(html);			
		se_content.find('.Money_Pembayaran').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		se_content.find('.Money_Pembayaran').maskMoney('mask', '9894');

		se_content.find('.datetimepicker').datetimepicker({
			format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
		});
		se_content.find('.Money_Pembayaran').trigger('keyup');

		// make action
			/*
				jika approval satu telah approve maka tidak boleh melakukan edit lagi
			*/
			if (dtspb[0]['Code'] != '') {
				var JsonStatus = jQuery.parseJSON(dtspb[0]['JsonStatus']);
				var bool = true;
				for (var i = 1; i < JsonStatus.length; i++) {
					if (JsonStatus[i].Status == 1) {
						bool = false;
						break;
					}
				}

				if (!bool) {
					se_content.find('button').not('.print_page').remove();
				}

				makeAction();
				makeSignaturesSPB(se_content,JsonStatus);
				if (JsonStatus[0].NIP != sessionNIP) {
					$('#add_approver').remove();
				}
			}
			else
			{
				se_content.find('.dtbank[tabindex!="-1"]').select2({
				    //allowClear: true
				});
			}
		// end action
		
	}

	function makeAction()
	{
		var Dataselected2 = ClassDt.Dataselected2;
		var dtspb = Dataselected2.dtspb;

		var html = '<div class = "row noPrint"><div class = "col-xs-12"></div></div>'; 
		var po_data = ClassDt.po_data;
		var po_create = po_data['po_create'];
		var btn_edit = '<button class="btn btn-primary btnEditInput"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>';
		var btn_submit = '<button class="btn btn-success submit" disabled> Submit</button>';
		
		var btn_approve = '<button class="btn btn-primary" id="Approve" action="approve">Approve</button>';
		var btn_reject = '<button class="btn btn-inverse" id="Reject" action="reject">Reject</button>';
		var btn_print = '<button class="btn btn-default hide print_page"> <i class="fa fa-print" aria-hidden="true"></i> Print</button>';
		var Status = dtspb[0]['Status'];
		switch(Status) {
		  case 0:
		  case '0':
		  case -1:
		  case '-1':
		  case 4:
		  case '4':
		  	var JsonStatus = dtspb[0]['JsonStatus'];
		  	JsonStatus = jQuery.parseJSON(JsonStatus);
		  	if (JsonStatus[0]['NIP'] == sessionNIP) {
		  		$('#r_action').html(html);
		  		$('#r_action').find('.col-xs-12').html('<div class = "pull-right">'+btn_edit+'&nbsp'+btn_submit+'</div>');
		  	}
		    break;
		  case 1:
		  case '1':
		    var JsonStatus = dtspb[0]['JsonStatus'];
		    JsonStatus = jQuery.parseJSON(JsonStatus);

		    if (JsonStatus[0]['NIP'] == sessionNIP) {
		    	var booledit2 = true;
		    	for (var i = 1; i < JsonStatus.length; i++) {
		    		if (JsonStatus[i].Status == 1 || JsonStatus[i].Status == '1') {
		    			booledit2 = false;
		    			break;
		    		}
		    	}

		    	if (booledit2) {
		    		$('#r_action').html(html);
		    		$('#r_action').find('.col-xs-12').html('<div class = "pull-right">'+btn_edit+'&nbsp'+btn_submit+'</div>');
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
		  	var JsonStatus = dtspb[0]['JsonStatus'];
		  	JsonStatus = jQuery.parseJSON(JsonStatus);
		  	if (JsonStatus[0]['NIP'] == sessionNIP || DivisionID == '4') {
		  		$('#r_action').html(html);
		  		$('#r_action').find('.col-xs-12').html('<div class = "pull-right">'+btn_print+'</div>');
		  	}
		    break;
		  default:
		    // code block
		}
	}

	function makeSignaturesSPB(se_content,JsonStatus)
	{
		var html = '<div class= "row" style = "margin-top : 20px;">'+
						'<div class = "col-xs-12">'+
							'<a href="javascript:void(0)" class="btn btn-default btn-default-success" type="button" id="add_approver"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>'+
							'<table class = "table borderless">'+
								'<thead>'+
									'<tr>'
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
						'<tr style = "height : 20px">';
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
		se_content.find('#r_signatures').html(html);
	}

	function OPBank(IDselected = null,Dis='')
	{
		var h = '';
		var dtbank = ClassDt.G_data_bank;
		h = '<select class = " form-control dtbank" style = "width : 80%" '+Dis+'>';
			var temp = ['Read','Write'];
			for (var i = 0; i < dtbank.length; i++) {
				var selected = (IDselected == dtbank[i].ID) ? 'selected' : '';
				h += '<option value = "'+dtbank[i].ID+'" '+selected+' >'+dtbank[i].Name+'</option>';
			}
		h += '</select>';	

		return h;
	}

	$(document).off('keyup keydown', '.Money_Pembayaran').on('keyup keydown', '.Money_Pembayaran',function(e) {
		var ev = $(this).closest('#pageContent');
		var v = $(this).val();
		v = findAndReplace(v, ".","");
		var InvoiceleftPO = $(this).attr('invoiceleftpo');
		// console.log(InvoiceleftPO);
		var n = InvoiceleftPO.indexOf(".");
		InvoiceleftPO = InvoiceleftPO.substring(0, n);
		// console.log(InvoiceleftPO);
		var sisa = parseInt(InvoiceleftPO) - parseInt(v);
		if (sisa < 0) {
			ev.find('.submit').prop('disabled',true);
			toastr.info('Pembayaran melebihi harga');
			v = InvoiceleftPO;
			$(this).val(v);
			$(this).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
			$(this).maskMoney('mask', '9894');
			sisa = 0;
			ev.find('.submit').prop('disabled',false);
		}
		ev.find('.Sisa_Pembayaran').html(formatRupiah(sisa));
		// ajax terbilang
		setTimeout(function () {
		    _ajax_terbilang(v).then(function(data){
		    	ev.find('.terbilang').html('Terbilang (Rupiah) : '+data+' Rupiah');
		    })
		},500);

	})

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
</script>
