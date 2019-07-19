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
		<br>
		<div id="page_status" class="noPrint"></div>
	</div>
	<div class="col-md-8" style="min-width: 800px;overflow: auto;">
		<div class="well" id = "pageContent" style="margin-top: 10px;">

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
		// buat navigasi menu active
		var Menu_ = $('#nav').find('li[segment2="pembayaran"]:first');
		Menu_.addClass('current open');
		var SubMenu = Menu_.find('.sub-menu');
		SubMenu.find('li[segment3="spb"]').addClass('current');

		// $("#container").attr('class','fixed-header sidebar-closed');
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
		arr = {
			dtspb : dtspb_rs,
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
		var c = 0;
		for (var i = 0; i < data.dtspb.length; i++) {
			if (Code == data.dtspb[i].Code && i > 0) {
				if (data.dtspb[i].Invoice != null && data.dtspb[i].Invoice != 'null') {
					InvoiceleftPO -= parseInt(data.dtspb[parseInt(i) - 1].Invoice);
					c++;
				}
				else
				{
					InvoiceleftPO -= parseInt(0);
				}
				break;
			}
		}

		// Fill Type Pembayaran
		var TypeInvoice = 'Pembayaran ' + (parseInt(c)+1);
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
		var btnSPb = '';

		var UploadInvoice = jQuery.parseJSON(dtspb[0].Detail[0]['UploadInvoice']);
		UploadInvoice = UploadInvoice[0];
		LinkFileInvoice = '<a href = "'+base_url_js+'fileGetAny/budgeting-spb-'+UploadInvoice+'" target="_blank" class = "Fileexist">File Document</a>';

		var UploadTandaTerima = jQuery.parseJSON(dtspb[0].Detail[0]['UploadTandaTerima']);
		UploadTandaTerima = UploadTandaTerima[0];
		LinkUploadTandaTerima = '<a href = "'+base_url_js+'fileGetAny/budgeting-spb-'+UploadTandaTerima+'" target="_blank" class = "Fileexist">File Document</a>';

		// Fill Type Pembayaran
		var TypeInvoice = dtspb[0].Detail[0]['TypeInvoice'];
		
		var html = '';
		html += '<div class = "row"><div class="col-xs-12 page_status"></div><div class = "col-xs-12"><div align="center"><h2>Surat Permohonan Pembayaran</h2></div>'+
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
								'<input type = "text" class = "form-control NoInvoice" placeholder = "Input No Invoice...." value="'+dtspb[0].Detail[0]['NoInvoice']+'" '+Dis+'>'+
								'<br>'+
								'<label style="color: red">Upload Invoice</label>'+
								'<input type="file" data-style="fileinput" class="BrowseInvoice" id="BrowseInvoice" accept="image/*,application/pdf" '+Dis+'><br>'+
								'<div id = "FileInvoice">'+
								LinkFileInvoice+
								'</div>'+
								'<br>'+
								'<label>No Tanda Terima</label>'+
								'<input type = "text" class = "form-control NoTT" placeholder = "Input No Tanda Terima...." value="'+dtspb[0].Detail[0]['NoTandaTerima']+'" '+Dis+'>'+
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
		                            '<input data-format="yyyy-MM-dd" class="form-control TglSPB" type=" text" readonly="" value = "'+dtspb[0].Detail[0]['Datee']+'">'+
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
								'<input type = "text" class = "form-control Perihal" placeholder ="Input Perihal..." value="'+dtspb[0].Detail[0]['Perihal']+'" '+Dis+'>'+
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
											OPBank(dtspb[0].Detail[0]['ID_bank'],Dis)+
										'</div>'+
										'<div class="col-xs-1">'+
											'<b>&</b>'+
										'</div>'+
										'<div class="col-xs-5">'+
											'<input type = "text" class = "form-control NoRekening" placeholder="No Rekening"  value="'+dtspb[0].Detail[0]['No_Rekening']+'" '+Dis+'>'+
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
									'<input type = "text" class = "form-control Money_Pembayaran" invoiceleftpo="'+(parseFloat(InvoiceleftPO)).toFixed(2)+'" value="'+parseInt(dtspb[0].Detail[0]['Invoice'])+'" '+Dis+'>'+ 
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
									btnSPb+
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
			var JsonStatus = jQuery.parseJSON(dtspb[0]['JsonStatus']);
			var bool = true;
			for (var i = 1; i < JsonStatus.length; i++) {
				if (JsonStatus[i].Status == 1) {
					bool = false;
					break;
				}
			}

			if (!bool) {
				if (dtspb[0]['Status'] == 2) {
					se_content.find('button').not('.print_page').remove();
				}
				
			}
			makepage_status();
			makeAction();
			makeSignaturesSPB(se_content,JsonStatus);
			if (JsonStatus[0].NIP != sessionNIP) {
				$('#add_approver').remove();
			}	
		// end action
		
	}

	function makepage_status()
	{
		var Dataselected2 = ClassDt.Dataselected2;
		var dtspb = Dataselected2.dtspb;
		var StatusName = '';
		switch(dtspb[0]['Status']) {
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

		$('#page_status').html('<div style = "color : red">Status : '+StatusName+'</div><div><a href="javascript:void(0)" class="btn btn-info btn_circulation_sheet" code="'+dtspb[0]['Code']+'">Info</a></div></div>');

	}

	function makeAction()
	{
		var Dataselected2 = ClassDt.Dataselected2;
		var dtspb = Dataselected2.dtspb;

		var html = '<div class = "row noPrint"><div class = "col-xs-12"></div></div>'; 
		var btn_edit = '<button class="btn btn-primary btnEditInput" status="'+dtspb[0]['Status']+'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>';
		var btn_submit = '<button class="btn btn-success submit" disabled> Submit</button>';
		
		var btn_approve = '<button class="btn btn-primary" id="Approve" action="approve">Approve</button>';
		var btn_reject = '<button class="btn btn-inverse" id="Reject" action="reject">Reject</button>';
		var btn_print = '<button class="btn btn-default print_page"> <i class="fa fa-print" aria-hidden="true"></i> Print</button>';
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
		  	if (JsonStatus[0]['NIP'] == sessionNIP) {
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

	$(document).off('click', '.btnEditInput').on('click', '.btnEditInput',function(e) {
		var Status = $(this).attr('status');
		if (Status != 2) {
			var ev2 = $(this).closest('#pageContent');
			ev2.find('input').not('.TglSPB').prop('disabled',false);
			ev2.find('button').prop('disabled',false);
			ev2.find('select').prop('disabled',false);
			ev2.find('.dtbank[tabindex!="-1"]').select2({
			    //allowClear: true
			});
			$(this).remove();
		}
		else
		{
			toastr.info('Data SPB telah approve, tidak bisa edit');
		}	
	})

	$(document).off('click', '.submit').on('click', '.submit',function(e) {
		// validation
		var ev = $(this).closest('#pageContent');
		var action = 'edit';
		if (confirm('Are you sure?')) {
			var validation = validation_input_spb(ev);
			if (validation) {
				SubmitSPB('.submit',ev,action);
			}
		}

	})

	function validation_input_spb(ev)
	{
		var find = true;
		var data = {
			NoInvoice : ev.find('.NoInvoice').val(),
			NoTandaTerima : ev.find('.NoTT').val(),
			TglSPB : ev.find('.TglSPB').val(),
			Perihal : ev.find('.Perihal').val(),
			NoRekening : ev.find('.NoRekening').val(),
			Pembayaran : ev.find('.Money_Pembayaran').val(),
		};
		if (validation(data) ) {

			// check berdasarkan Code SPB
			var dt_arr = ClassDt.Dataselected2;
			var dtspb = dt_arr.dtspb;
			if (dtspb[0]['Code'] == '' || dtspb[0]['Code'] == 'null' || dtspb[0]['Code'] == null) {
				// Upload Tanda Terima 
				ev.find(".BrowseTT").each(function(){
					var IDFile = $(this).attr('id');
					var ev2 = $(this);
					if (!file_validation2(ev2,'Tanda Terima ') ) {
					  ev.find(".submit").prop('disabled',false);
					  find = false;
					  return false;
					}
				})

				// Upload Invoice 
				ev.find(".BrowseInvoice").each(function(){
					var IDFile = $(this).attr('id');
					var ev2 = $(this);
					if (!file_validation2(ev2,'Invoice ') ) {
					  ev.find(".submit").prop('disabled',false);
					  find = false;
					  return false;
					}
				})
			}
			
		}
		else
		{
			find = false;
		}
		
		return find;
	}

	function validation(arr)
	{
	  var toatString = "";
	  var result = "";
	  for(var key in arr) {
	     switch(key)
	     {
	      case  "Pembayaran" :
	            if (arr[key] <= 0) {
	            	toatString += 'Pembayaran tidak boleh kecil sama dengan nol' + "<br>";
	            }
	            break;
	      default :
	            result = Validation_required(arr[key],key);
	            if (result['status'] == 0) {
	              toatString += result['messages'] + "<br>";
	            }       
	     }

	  }
	  if (toatString != "") {
	    toastr.error(toatString, 'Failed!!');
	    return false;
	  }

	  return true;
	}

	function SubmitSPB(elementbtn,ev,action="add")
	{
		loadingStart();
		var Code_po_create = ClassDt.Code_po_create;
		var dt_arr = ClassDt.Dataselected2;
		var dtspb = dt_arr.dtspb;
		var Departement = IDDepartementPUBudget;
		var ID_payment = dtspb[0]['ID'];
		var ID_budget_left = 0;
		var form_data = new FormData();

		if ( ev.find('.BrowseInvoice').length ) {
			var UploadFile = ev.find('.BrowseInvoice')[0].files;
			form_data.append("UploadInvoice[]", UploadFile[0]);
		}

		if ( ev.find('.BrowseTT').length ) {
			var UploadFile = ev.find('.BrowseTT')[0].files;
			form_data.append("UploadTandaTerima[]", UploadFile[0]);
		}

		var NoInvoice = ev.find('.NoInvoice').val();
		var NoTandaTerima = ev.find('.NoTT').val();
		var Datee = ev.find('.TglSPB').val();
		var Perihal = ev.find('.Perihal').val();
		var No_Rekening = ev.find('.NoRekening').val();
		var ID_bank = ev.find('.dtbank option:selected').val();
		var Invoice = ev.find('.Money_Pembayaran').val();
		Invoice = findAndReplace(Invoice, ".","");
		var TypeInvoice = ev.find('.TypePembayaran').attr('type');

		var data = {
			Code_po_create : Code_po_create,
			Departement : Departement,
			ID_budget_left : ID_budget_left,
			NoInvoice : NoInvoice,
			NoTandaTerima :NoTandaTerima,
			Datee :Datee,
			Perihal : Perihal,
			No_Rekening : No_Rekening,
			ID_bank : ID_bank,
			Invoice : Invoice,
			TypeInvoice  : TypeInvoice,
			ID_payment : ID_payment,
			action : action,
		};

		var token = jwt_encode(data,"UAP)(*");
		form_data.append('token',token);

		var data = ClassDt.Dataselected;
		var InvoicePO = dtspb[0].InvoicePO;
		// hitung Left PO
		var InvoiceleftPO = parseInt(InvoicePO);
		var Code = ClassDt.Code;
		for (var i = 0; i < data.dtspb.length; i++) {
			if (Code == data.dtspb[i].Code && i > 0) {
				if (data.dtspb[i]['Detail'][0].Invoice != null && data.dtspb[i]['Detail'][0].Invoice != 'null') {
					InvoiceleftPO -= parseInt(data.dtspb[parseInt(i) - 1]['Detail'][0].Invoice);
				}
				else
				{
					InvoiceleftPO -= parseInt(0);
				}
				break;
			}
		}
		var data_verify = {
			Code_po_create : Code_po_create,
			InvoicePO : InvoicePO,
			InvoiceLeftPO : InvoiceleftPO,
		};

		var token2 = jwt_encode(data_verify,"UAP)(*");
		form_data.append('token2',token2);

		var token3 = jwt_encode(ClassDt.po_data,"UAP)(*");
		form_data.append('token3',token3);

		// pass po_detail agar dapat approval
		var po_detail = ClassDt.po_data.po_detail;
		var temp = [];
		for (var i = 0; i < po_detail.length; i++) {
			var arr = po_detail[i];
			var token_ = jwt_encode(arr,"UAP)(*");
			temp.push(token_);
		}

		var token4 = jwt_encode(temp,"UAP)(*");
		form_data.append('token4',token4);


		// var url = base_url_js + "budgeting/submit"
		var url = base_url_js + "budgeting/submitspb"
		$.ajax({
		  type:"POST",
		  url:url,
		  data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
		  contentType: false,       // The content type used when sending data to the server.
		  cache: false,             // To unable request pages to be cached
		  processData:false,
		  dataType: "json",
		  success:function(data)
		  {
		  	if (data.Status == 0) {
		  		if (data.Change == 1) {
		  			toastr.info('Terjadi perubahan data, halaman akan direfresh');
		  			setTimeout(function () {
		  				Get_data_spb_grpo(Code_po_create).then(function(data){
		  							var dt_arr = __getRsViewGRPO_SPB(Code,data);
		  							ClassDt.Dataselected = data; // all data spb by code po
		  							ClassDt.Dataselected2 = dt_arr; // SPB Selected
		  							Get_data_detail_po(Code_po_create).then(function(data2){
		  								// Define data
		  								ClassDt.po_data = data2;
		  								MakeDomHtml();
		  								loadingEnd(500);
		  							})

		  						})
		  			},1000);
		  			// load first load data
		  			
		  		}
		  		else
		  		{
		  			toastr.error("Connection Error, Please try again", 'Error!!');
		  		}
		  	}
		  	else{
		  		toastr.success('Saved');
		  		setTimeout(function () {
		  			Get_data_spb_grpo(Code_po_create).then(function(data){
  						var dt_arr = __getRsViewGRPO_SPB(Code,data);
  						ClassDt.Dataselected = data; // all data spb by code po
  						ClassDt.Dataselected2 = dt_arr; // SPB Selected
  						Get_data_detail_po(Code_po_create).then(function(data2){
  							// Define data
  							ClassDt.po_data = data2;
  							MakeDomHtml();
  							loadingEnd(500);
  						})

  					})
		  			//window.location.href = base_url_js+'budgeting_menu/pembayaran/spb';
		  		},1500);
		  	}
		  },
		  error: function (data) {
		    toastr.error("Connection Error, Please try again", 'Error!!');
		    nmbtn = 'Submit';
		    ev.find(elementbtn).prop('disabled',false).html(nmbtn);
		  }
		})
	}

	$(document).off('click', '#Approve').on('click', '#Approve',function(e) {
		if (confirm('Are you sure ?')) {
			loading_button('#Approve');
			var Code = ClassDt.Code;
			var Code_po_create = ClassDt.Code_po_create;
			var approval_number = $(this).attr('approval_number');
			// var url = base_url_js + 'rest2/__approve_po';
			var url = base_url_js + 'rest2/__approve_spb';
			var data = {
				Code : Code,
				approval_number : approval_number,
				NIP : sessionNIP,
				action : 'approve',
				auth : 's3Cr3T-G4N',
				po_data : ClassDt.po_data,
			}

			var token = jwt_encode(data,"UAP)(*");
			$.post(url,{ token:token },function (resultJson) {
				var rs = resultJson;
				if (rs.Status == 1) {
					Get_data_spb_grpo(Code_po_create).then(function(data){
  						var dt_arr = __getRsViewGRPO_SPB(Code,data);
  						ClassDt.Dataselected = data; // all data spb by code po
  						ClassDt.Dataselected2 = dt_arr; // SPB Selected
  						Get_data_detail_po(Code_po_create).then(function(data2){
  							// Define data
  							ClassDt.po_data = data2;
  							MakeDomHtml();
  							loadingEnd(500);
  						})

  					})
				}
				else
				{
					if (rs.Change == 1) {
						toastr.info('The Data already have updated by another person,Please check !!!');
						Get_data_spb_grpo(Code_po_create).then(function(data){
  						var dt_arr = __getRsViewGRPO_SPB(Code,data);
  						ClassDt.Dataselected = data; // all data spb by code po
  						ClassDt.Dataselected2 = dt_arr; // SPB Selected
  						Get_data_detail_po(Code_po_create).then(function(data2){
  							// Define data
  							ClassDt.po_data = data2;
  							MakeDomHtml();
  							loadingEnd(500);
  						})

  					})
					}
					else
					{
						toastr.error(rs.msg,'!!!Failed');
					}
				}
			}).fail(function() {
			  // toastr.info('No Result Data');
			  toastr.error('The Database connection error, please try again', 'Failed!!');
			}).always(function() {
			    //$('#Approve').prop('disabled',false).html('<i class="fa fa-handshake-o"> </i> Approve');
			});
		}
	})

	$(document).off('click', '#Reject').on('click', '#Reject',function(e) {
		if (confirm('Are you sure ?')) {
			var Code = ClassDt.Code;
			var Code_po_create = ClassDt.Code_po_create;
			var approval_number = $(this).attr('approval_number');
			// show modal insert reason
			$('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Please Input Reason ! </b> <br>' +
			    '<input type = "text" class = "form-group" id ="NoteDel" style="margin: 0px 0px 15px; height: 30px; width: 329px;" maxlength="30"><br>'+
			    '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
			    '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
			    '</div>');
			$('#NotificationModal').modal('show');

			$("#confirmYes").click(function(){
				var NoteDel = $("#NoteDel").val();
				$('#NotificationModal .modal-header').addClass('hide');
				$('#NotificationModal .modal-body').html('<center>' +
				    '                    <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>' +
				    '                    <br/>' +
				    '                    Loading Data . . .' +
				    '                </center>');
				$('#NotificationModal .modal-footer').addClass('hide');
				$('#NotificationModal').modal({
				    'backdrop' : 'static',
				    'show' : true
				});

				var url = base_url_js + 'rest2/__approve_spb';
				var data = {
					Code : Code,
					approval_number : approval_number,
					NIP : sessionNIP,
					action : 'reject',
					auth : 's3Cr3T-G4N',
					NoteDel : NoteDel,
					po_data : ClassDt.po_data,
				}

				var token = jwt_encode(data,"UAP)(*");
				$.post(url,{ token:token },function (resultJson) {
					var rs = resultJson;
					if (rs.Status == 1) {
						Get_data_spb_grpo(Code_po_create).then(function(data){
	  						var dt_arr = __getRsViewGRPO_SPB(Code,data);
	  						ClassDt.Dataselected = data; // all data spb by code po
	  						ClassDt.Dataselected2 = dt_arr; // SPB Selected
	  						Get_data_detail_po(Code_po_create).then(function(data2){
	  							// Define data
	  							ClassDt.po_data = data2;
	  							MakeDomHtml();
	  							loadingEnd(500);
	  						})

	  					})
					}
					else
					{
						if (rs.Change == 1) {
							toastr.info('The Data already have updated by another person,Please check !!!');
							Get_data_spb_grpo(Code_po_create).then(function(data){
		  						var dt_arr = __getRsViewGRPO_SPB(Code,data);
		  						ClassDt.Dataselected = data; // all data spb by code po
		  						ClassDt.Dataselected2 = dt_arr; // SPB Selected
		  						Get_data_detail_po(Code_po_create).then(function(data2){
		  							// Define data
		  							ClassDt.po_data = data2;
		  							MakeDomHtml();
		  							loadingEnd(500);
		  						})

		  					})
						}
						else
						{
							toastr.error(rs.msg,'!!!Failed');
						}
					}
					$('#NotificationModal').modal('hide');
				}).fail(function() {
				  // toastr.info('No Result Data');
				  toastr.error('The Database connection error, please try again', 'Failed!!');
				  $('#NotificationModal').modal('hide');
				}).always(function() {
				    // $('#reject').prop('disabled',false).html('<i class="fa fa-handshake-o"> </i> Approve');
				    //$('#NotificationModal').modal('hide');
				});
			})	
		}

	})

	$(document).off('click', '.btn_circulation_sheet').on('click', '.btn_circulation_sheet',function(e) {
	    var url = base_url_js+'rest2/__show_info_payment';
	    var Dataselected2 = ClassDt.Dataselected2;
	    var dtspb = Dataselected2.dtspb;
	    var ID_payment = dtspb[0]['ID'];
	    var Code = $(this).attr('code');
   		var data = {
   		    ID_payment : ID_payment,
   		    auth : 's3Cr3T-G4N',
   		};
   		var token = jwt_encode(data,"UAP)(*");
   		$.post(url,{ token:token },function (data_json) {
   			var html = '<div class = "row"><div class="col-md-12"><div class="well">';
   				html += '<table class="table table-striped table-bordered table-hover table-checkable tableData" id = "TblModal">'+
                      '<caption><h4>Circulation Sheet</h4></caption>'+
                      '<thead>'+
                          '<tr>'+
                              '<th style="width: 5px;">No</th>'+
                              '<th style="width: 55px;">Desc</th>'+
                              '<th style="width: 55px;">Date</th>'+
                              '<th style="width: 55px;">By</th>';
		        html += '</tr>' ;
		        html += '</thead>' ;
		        html += '<tbody>' ;
		        html += '</tbody>' ;
		        html += '</table></div></div></div>' ;

   			var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
   			    '';
   			$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Info SPB Code : '+Code+'</h4>');
   			$('#GlobalModalLarge .modal-body').html(html);
   			$('#GlobalModalLarge .modal-footer').html(footer);
   			$('#GlobalModalLarge').modal({
   			    'show' : true,
   			    'backdrop' : 'static'
   			});

   			// make datatable
   				var table = $('#TblModal').DataTable({
   				      "data" : data_json['payment_circulation_sheet'],
   				      'columnDefs': [
   					      {
   					         'targets': 0,
   					         'searchable': false,
   					         'orderable': false,
   					         'className': 'dt-body-center',
   					         'render': function (data, type, full, meta){
   					             return '';
   					         }
   					      },
   					      {
   					         'targets': 1,
   					         'render': function (data, type, full, meta){
   					             return full.Desc;
   					         }
   					      },
   					      {
   					         'targets': 2,
   					         'render': function (data, type, full, meta){
   					             return full.Date;
   					         }
   					      },
   					      {
   					         'targets': 3,
   					         'render': function (data, type, full, meta){
   					             return full.Name;
   					         }
   					      },
   				      ],
   				      'createdRow': function( row, data, dataIndex ) {
   				      		$(row).find('td:eq(0)').attr('style','width : 10px;')
   				      	
   				      },
   				});

   				table.on( 'order.dt search.dt', function () {
   				        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
   				            cell.innerHTML = i+1;
   				        } );
   				} ).draw();

   		});
	})

	$(document).off('click', '.print_page').on('click', '.print_page',function(e) {
		var dt_arr = ClassDt.Dataselected2;
		var dtspb = dt_arr.dtspb;
		var ID_payment = dtspb[0]['ID'];
		var po_data = ClassDt.po_data;
		var Dataselected = ClassDt.Dataselected;

		var url = base_url_js+'save2pdf/print/pre_pembayaran';
		var data = {
		  ID_payment : ID_payment,
		  dt_arr : dt_arr,
		  po_data : po_data,
		  Dataselected : Dataselected,
		}
		var token = jwt_encode(data,"UAP)(*");
		FormSubmitAuto(url, 'POST', [
		    { name: 'token', value: token },
		]);
	})
</script>
