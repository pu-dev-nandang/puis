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
		<div><a href="<?php echo base_url().'budgeting_menu/pembayaran/bank_advance' ?>" class = "btn btn-warning"> <i class="fa fa-arrow-circle-left"></i> Back to List</a></div>
		<br>
		<div id="page_status" class="noPrint"></div>
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
		ID_payment : "<?php echo $ID_payment ?>",
		ThisTableSelect : '',
		DataPaymentPO : [],
		DataPaymentSelected : [],
		po_data : [],
		G_data_bank : <?php echo json_encode($G_data_bank) ?>,
	};
	$(document).ready(function() {
		// buat navigasi menu active
		var Menu_ = $('#nav').find('li[segment2="pembayaran"]:first');
		Menu_.addClass('current open');
		var SubMenu = Menu_.find('.sub-menu');
		SubMenu.find('li[segment3="bank_advance"]').addClass('current');
		
	    // $("#container").attr('class','fixed-header sidebar-closed');
	    loadingStart();
	    // loadFirst
	    loadFirst();
	}); // exit document Function

	function loadFirst()
	{
		$('#pageContent').empty();
		var Code_po_create = ClassDt.Code_po_create;
		var ID_payment = ClassDt.ID_payment;
		Get_data_spb_grpo(Code_po_create).then(function(data){
			ClassDt.DataPaymentPO = data;
			var dt_arr = __getRsViewGRPO_SPB(ID_payment);
			ClassDt.DataPaymentSelected = dt_arr;
			Get_data_detail_po(Code_po_create).then(function(data2){
				// Define data
				ClassDt.po_data = data2;
				var se_content = $('#pageContent');
				makeDomBank_Advance(ID_payment,se_content)
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

	function __getRsViewGRPO_SPB(ID_payment)
	{
		var arr=[];
		var Dataselected = ClassDt.DataPaymentPO;
		var dtspb = Dataselected.dtspb;
		var dtspb_rs = [];
		// get indeks array
		for (var i = 0; i < dtspb.length; i++) {
			if (ID_payment == dtspb[i].ID) {
				break;
			}
		}

		dtspb_rs[0] = dtspb[i];
		arr = {
			dtspb : dtspb_rs,
		};

		return arr;
	}

	function OPTypePay(NameSelected = '',Dis='')
	{
		var h = '';
		var dt = ['Cash','Transfer'];
		h = '<select class = " form-control TypePay" style = "width : 80%" '+Dis+'>';
			for (var i = 0; i < dt.length; i++) {
				var selected = (NameSelected == dt[i]) ? 'selected' : '';
				h += '<option value = "'+dt[i]+'" '+selected+' >'+dt[i]+'</option>';
			}
		h += '</select>';	

		return h;
	}

	function OPBank(IDselected = null,Dis='')
	{
		var h = '';
		var dtbank = ClassDt.G_data_bank;
		h = '<select class = " form-control dtbank" style = "width : 80%" '+Dis+'>';
			var temp = ['Read','Write'];
			if (IDselected != null) {
				var selected = (IDselected == 0) ? 'selected' : '';
				h += '<option value = "0" '+selected+' >'+'--No Choose--'+'</option>';
			}
			for (var i = 0; i < dtbank.length; i++) {
				var selected = (IDselected == dtbank[i].ID) ? 'selected' : '';
				h += '<option value = "'+dtbank[i].ID+'" '+selected+' >'+dtbank[i].Name+'</option>';
			}
		h += '</select>';	

		return h;
	}

	function makeDomBank_Advance(ID_payment,se_content)
	{
		var DataPaymentSelected = ClassDt.DataPaymentSelected;
		var data = ClassDt.DataPaymentPO;
		var dtspb = DataPaymentSelected.dtspb;
		var Code = ClassDt.Code_po_create;
		var InvoicePO = dtspb[0].InvoicePO;
		var InvoiceleftPO = dtspb[0].InvoiceleftPO;
		var po_data = ClassDt.po_data;
		var po_create = po_data.po_create;
		var Supplier = po_create[0].NamaSupplier;

		var html = '';
		var htmlAdd ='<div class = "BAAdd">';
		var EndhtmlAdd = '</div>';
		Invoice = parseInt(dtspb[0].Detail[0].Invoice);
		TypePay = dtspb[0].Detail[0].TypePay;
		ID_bank = dtspb[0].Detail[0].ID_bank;
		NoRekening = dtspb[0].Detail[0].No_Rekening;
		Nama_Penerima = dtspb[0].Detail[0].Nama_Penerima;
		Date_Needed = dtspb[0].Detail[0].Date_Needed;
		Perihal = dtspb[0].Detail[0].Perihal;
		Dis = 'disabled';
		btn_hide = '';
		var btn_hide_print = 'hide';
		Status = dtspb[0]['Status'];
		if (Status == 2) {
			btn_hide_print = '';
		}

		// hitung Left PO
		var InvoiceleftPO = parseInt(InvoicePO);
		var c = 0;
		for (var i = 0; i < data.dtspb.length; i++) {
			if (ID_payment == data.dtspb[i].ID && i > 0) {
				if (data.dtspb[i]['Detail'][0].Invoice != null && data.dtspb[i]['Detail'][0].Invoice != 'null') {
					// InvoiceleftPO -= parseInt(data.dtspb[parseInt(i) - 1]['Detail'][0].Invoice);
					for (var j = 0; j < i; j++) {
						InvoiceleftPO -= parseInt(data.dtspb[j]['Detail'][0].Invoice);
					}
					c++;
				}
				else
				{
					InvoiceleftPO -= parseInt(0);
				}
				break;
			}
		}
		InvoiceleftPO = (parseFloat(InvoiceleftPO)).toFixed(2);
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
		var TDGRPO = '-';
		var Good_Receipt = dtspb[0].Good_Receipt;
		if (Good_Receipt.length > 0) {
			TDGRPO = '<button class = "btn btn-primary ShowGRPOMODAL" id_payment = "'+dtspb[0].ID+'">Show GRPO</button>';
		}	
		html += htmlAdd+'<div class = "row"><div class="col-xs-12 page_status"></div><div class = "col-xs-12"><div align="center"><h2>BANK ADVANCE FORM</h2></div>'+
					'<hr style="height:2px;border:none;color:#333;background-color:#333;margin-top: -3px;">'+
					'<label>Mohon dapat diberikan Bank Advance dengan perincian sebagai berikut:</label>'+
					'<table class="table borderless" style="font-weight: bold;">'+
					'<thead></thead>'+
					'<tbody>'+
						'<tr>'+
							'<td class="TD1">'+
								'Kegiatan'+
							'</td>'+
							'<td class="TD2">'+
								':'+
							'</td>'+
							'<td>'+
								'<span color = "red" class = "Perihal">'+Perihal+'</span>'+
							'</td>'+
						'</tr>'+
						'<tr>'+
							'<td class="TD1">'+
								'Biaya'+
							'</td>'+
							'<td class="TD2">'+
								':'+
							'</td>'+
							'<td>'+
								'<input type = "text" class = "form-control Money_Pembayaran" invoiceleftpo="'+InvoiceleftPO+'" value = "'+Invoice+'" '+Dis+'>'+ 
							'</td>'+		
						'</tr>'+
						'<tr>'+
							'<td class="TD1">'+
								'Uang diberikan melalui: (pilih salah satu)'+
							'</td>'+
							'<td class="TD2">'+
								':'+
							'</td>'+
							'<td>'+
								OPTypePay(TypePay,Dis)+	
							'</td>'+		
						'</tr>'+
						'<tr>'+
							'<td class="TD1">'+
								'Bank'+
							'</td>'+
							'<td class="TD2">'+
								':'+
							'</td>'+
							'<td>'+
								OPBank(ID_bank,Dis)+
							'</td>'+		
						'</tr>'+
						'<tr>'+
							'<td class="TD1">'+
								'No Rekening'+
							'</td>'+
							'<td class="TD2">'+
								':'+
							'</td>'+
							'<td>'+
								'<input type = "text" class = "form-control NoRekening" placeholder="No Rekening" value = "'+NoRekening+'" '+Dis+'>'+
							'</td>'+		
						'</tr>'+
						'<tr>'+
							'<td class="TD1">'+
								'Nama Penerima'+
							'</td>'+
							'<td class="TD2">'+
								':'+
							'</td>'+
							'<td>'+
								'<input type = "text" class = "form-control Nama_Penerima" placeholder="Nama Penerima" value = "'+Nama_Penerima+'" '+Dis+'>'+
							'</td>'+		
						'</tr>'+
						'<tr>'+
							'<td class="TD1">'+
								'Dibutuhkan pada tanggal:'+
							'</td>'+
							'<td class="TD2">'+
								':'+
							'</td>'+
							'<td>'+
								'<div class="input-group input-append date datetimepicker" style= "width:50%;">'+
		                            '<input data-format="yyyy-MM-dd" class="form-control TglBA" type=" text" readonly="" value = "'+Date_Needed+'">'+
		                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
		                		'</div>'+
							'</td>	'+			
						'</tr>'+
						'<tr>'+
							'<td class = "TD1"><label>GRPO</label></td>'+
							'<td>:</td>'+
							'<td>'+TDGRPO+'</td>'+
						'</tr>'+
					'</tbody>'+
					'</table>'+
					'<div id="r_signatures"></div>'+
					'<div id = "r_action">'+
						'<div class="row">'+
							'<div class="col-md-12">'+
								'<div class="pull-right">'+
									'<button class="btn btn-default '+btn_hide_print+' print_page"> <i class="fa fa-print" aria-hidden="true"></i> Print</button>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div></div></div>'+EndhtmlAdd;
		se_content.html(html);			
		se_content.find('.Money_Pembayaran').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		se_content.find('.Money_Pembayaran').maskMoney('mask', '9894');

		se_content.find('.datetimepicker').datetimepicker({
			format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
		});

		var JsonStatus = jQuery.parseJSON(dtspb[0]['JsonStatus']);
		makeSignatures(se_content,JsonStatus);
		makepage_status(DataPaymentSelected,se_content);
		makeAction();
		if (JsonStatus[0].NIP != sessionNIP) {
			$('#add_approver').remove();
		}

		// show page realisasi
		if (DataPaymentSelected.dtspb[0].Status == 2) {
			var DivPageRealisasi = se_content.find('.BAAdd');
			makePagerealisasi(DataPaymentSelected,DivPageRealisasi); 
		}			

	}

	$(document).off('click', '.ShowGRPOMODAL').on('click', '.ShowGRPOMODAL',function(e) {
		var ID_payment = $(this).attr('id_payment');
		var DataPaymentSelected = ClassDt.DataPaymentSelected;
		var dtspb = DataPaymentSelected.dtspb;
		var dtgood_receipt_spb = dtspb[0].Good_Receipt;
		var html = '';
		for (var i = 0; i < dtgood_receipt_spb.length; i++) {
			var FileDocument = jQuery.parseJSON(dtgood_receipt_spb[i]['FileDocument']);
			FileDocument = FileDocument[0];
			var FileTandaTerima = jQuery.parseJSON(dtgood_receipt_spb[i]['FileTandaTerima']);
			FileTandaTerima = FileTandaTerima[0];
			var dtgood_receipt_detail = dtgood_receipt_spb[i].Detail;
			var OPPo_detail_edit = '';
			for (var j = 0; j < dtgood_receipt_detail.length; j++) {
				OPPo_detail_edit += OPPo_detail(dtgood_receipt_detail[j].ID_po_detail,[],dtgood_receipt_detail[j].QtyDiterima,'disabled');
			}

				html += '<div class = "row"><div class = "col-xs-12"><div align="center"><h2>Good Receipt PO </h2></div>'+
						'<hr style="height:2px;border:none;color:#333;background-color:#333;margin-top: -3px;">'+
						'<br>'+
						'<div id = "page_po_item">'+
							OPPo_detail_edit+
						'</div>'+
						'<br>'+
						'<div class = "form-horizontal" style="margin-top:5px;">'+
										'<div class="form-group">'+
											'<label class = "col-sm-2">No Document</label>'+	
												'<div class="col-sm-4">'+'<input type = "text" class = "form-control NoDocument" placeholder = "Input No Document...." value="'+dtgood_receipt_spb[i]['NoDocument']+'" disabled><br>'+
												'<a href = "'+base_url_js+'fileGetAny/budgeting-grpo-'+FileDocument+'" target="_blank" class = "Fileexist">File Document</a>'+
												'</div>'+
										'</div>'+
						'</div>'+				
						'<div class = "form-horizontal" style="margin-top:5px;">'+
										'<div class="form-group">'+
											'<label class = "col-sm-2">No Tanda Terima</label>'	+
												'<div class="col-sm-4">'+'<input type = "text" class = "form-control NoTandaTerimaGRPO" placeholder = "Input No Tanda Terima...." value="'+dtgood_receipt_spb[i]['NoTandaTerima']+'" disabled>'+
												'<a href = "'+base_url_js+'fileGetAny/budgeting-grpo-'+FileTandaTerima+'" target="_blank" class = "Fileexist">File Tanda Terima'+
												'</a>'+
												'</div>'+
										'</div>'+
						'</div>'+
						'<div class = "form-horizontal" style="margin-top:5px;">'+
										'<div class="form-group">'+
											'<label class = "col-sm-2">Tanggal</label>'	+
												'<div class="col-sm-4">'+'<div class="input-group input-append date datetimepicker">'+
			                            '<input data-format="yyyy-MM-dd" class="form-control TglGRPO" type=" text" readonly="" value="'+dtgood_receipt_spb[i]['Date']+'" disabled>'+
			                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
			                		'</div></div>'+
						'</div>'+
					'</div></div></div>';
		}

		var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
		    '';
		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Info GRPO</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html(footer);
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});
	})

	function OPPo_detail(IDselected = null,arr_IDPass=[],value_qty=0,action_btn='')
	{
		var h = '';
		var po_data = ClassDt.po_data;
		var po_detail= po_data.po_detail;
		h = '<div class = "form-horizontal GroupingItem" style="margin-top:15px;">'+
				'<div class="form-group">'+
					'<label class = "col-sm-2">Item</label>'
			;
		h += '<div class="col-sm-6"><select class = " form-control Item" '+action_btn+'>'+
				'<option value = "" disabled selected>--Pilih Item--</option>';
			for (var i = 0; i < po_detail.length; i++) {
				var bool = true;
				for (var k = 0; k < arr_IDPass.length; k++) {
					if (po_detail[i].ID_po_detail ==arr_IDPass[k] ) {
						bool = false;
						break;
					}
				}
				if (bool) {
					// get qty left

					var selected = (IDselected == po_detail[i].ID_po_detail) ? 'selected' : '';
					h += '<option value = "'+po_detail[i].ID_po_detail+'" '+selected+' qtypr="'+po_detail[i].QtyPR+'">'+po_detail[i].Item+'</option>';
				}
				
			}
		h += '</select></div>';	

		h += '<div class="col-sm-2"><input type="text" class="form-control QtyDiterima" value="'+value_qty+'" '+action_btn+'></div>';
		h += '</div></div>';
		return h;
	}

	function makePagerealisasi(DataPaymentSelected,DivPageRealisasi)
	{
		var html = '';
		var dtspb = DataPaymentSelected.dtspb;
		var JsonStatusdtspb = dtspb[0].JsonStatus;
		JsonStatusdtspb = jQuery.parseJSON(JsonStatusdtspb);
		var Detail = dtspb[0].Detail;
		var Realisasi = Detail[0].Realisasi;
		var Dis = 'disabled';
		if (JsonStatusdtspb[0]['NIP'] == sessionNIP) {
			Dis = '';
		}
		var ID_Realisasi = '';
		var ID_payment_type = Detail[0].ID;
		var UploadInvoice = '';
		var LinkFileInvoice = '';
		var NoInvoice = '';
		var UploadTandaTerima = '';
		var LinkUploadTandaTerima = '';
		var NoTandaTerima = '';
		var Date_Realisasi = '';
		var JsonStatus = '';
		var StatusRealiasi = '';
		var btn_hide_submit = '';
		var btnRealisasi = '<button class="btn btn-success submitRealisasiBA '+btn_hide_submit+'" '+Dis+'> Submit</button>';
		if (Realisasi.length > 0) { // exist
			Dis = 'disabled';
			StatusRealiasi = Realisasi[0].Status;
			btn_hide_submit = 'hide';
			btnRealisasi = '<button class="btn btn-primary hide btnEditInputRealisasiBA"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button> &nbsp'+
									'<button class="btn btn-success submitRealisasiBA '+btn_hide_submit+'" '+Dis+'> Submit</button>';
			if (StatusRealiasi == 2) {
				Dis = 'disabled';
				btnRealisasi = '';
			}

			ID_Realisasi = Realisasi[0].ID;
			UploadInvoice = jQuery.parseJSON(Realisasi[0]['UploadInvoice']);
			UploadInvoice = UploadInvoice[0];
			LinkFileInvoice = '<a href = "'+base_url_js+'fileGetAny/budgeting-bankadvance-'+UploadInvoice+'" target="_blank" class = "Fileexist">File Document</a>';
			NoInvoice = Realisasi[0].NoInvoice;
			UploadTandaTerima = jQuery.parseJSON(Realisasi[0]['UploadTandaTerima']);
			UploadTandaTerima = UploadTandaTerima[0];
			LinkUploadTandaTerima = '<a href = "'+base_url_js+'fileGetAny/budgeting-bankadvance-'+UploadTandaTerima+'" target="_blank" class = "Fileexist">File Document</a>';
			NoTandaTerima = Realisasi[0].NoTandaTerima;
			Date_Realisasi = Realisasi[0].Date_Realisasi;
			JsonStatus = jQuery.parseJSON(Realisasi[0]['JsonStatus']);
		}

		html += '<div class = "row realisasi_page" ID_Realisasi = "'+ID_Realisasi+'">'+
					'<div class = "col-xs-12">'+
						'<div align="center"><h2>REALISASI</h2></div>'+
						'<hr style="height:2px;border:none;color:#333;background-color:#333;margin-top: -3px;">'+
						'<table class="table borderless" style="font-weight: bold;">'+
							'<thead></thead>'+
							'<tbody>'+
								'<tr>'+
									'<td class="TD1">'+
										'NO KWT/INV'+
									'</td>'+
									'<td class="TD2">'+
										':'+
									'</td>'+
									'<td>'+
										'<label>No Invoice</label>'+
										'<input type = "text" class = "form-control NoInvoice" placeholder = "Input No Invoice...." value="'+NoInvoice+'" '+Dis+'>'+
										'<br>'+
										'<label style="color: red">Upload Invoice</label>'+
										'<input type="file" data-style="fileinput" class="BrowseInvoice" id="BrowseInvoice" accept="image/*,application/pdf" '+Dis+'><br>'+
										'<div id = "FileInvoice">'+
										LinkFileInvoice+
										'</div>'+
										'<br>'+
										'<label>No Tanda Terima</label>'+
										'<input type = "text" class = "form-control NoTT" placeholder = "Input No Tanda Terima...." value="'+NoTandaTerima+'" '+Dis+'>'+
										'<br>'+
										'<label style="color: red">Upload Tanda Terima</label>'+
										'<input type="file" data-style="fileinput" class="BrowseTT" id="BrowseTT" accept="image/*,application/pdf" '+Dis+'>'+
										'<div id = "FileTT" '+Dis+'>'+
										LinkUploadTandaTerima+
										'</div>'+
									'</td>'+			
								'</tr>'+
								'<tr>'+
									'<td class="TD1">'+
										'TANGGAL REALISASI'+
									'</td>'+
									'<td class="TD2">'+
										':'+
									'</td>'+
									'<td>'+
										'<div class="input-group input-append date datetimepicker" style= "width:50%;">'+
				                            '<input data-format="yyyy-MM-dd" class="form-control TglRealisasiBA" type=" text" readonly="" value = "'+Date_Realisasi+'">'+
				                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
				                		'</div>'+
									'</td>	'+			
								'</tr>'+
							'</tbody>'+
						'</table>'+
						'<div id="r_signatures_realisasi"></div>'+
						'<div id = "r_action_realisasi">'+
							'<div class="row">'+
								'<div class="col-md-12">'+
									'<div class="pull-right">'+
										btnRealisasi+
									'</div>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>'+	
				'</div>';		
		DivPageRealisasi.append(html);
		DivPageRealisasi.find('.datetimepicker').datetimepicker({
			format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
		});

		if (Realisasi.length > 0) {
			makeSignaturesRealiasi(DivPageRealisasi,JsonStatus);
			makeActionRealisasi(DivPageRealisasi,Realisasi);
		}
		
	}

	function makeActionRealisasi(DivPageRealisasi,Realisasi)
	{
		var dtspb = Realisasi;
		var html = '<div class = "row noPrint"><div class = "col-xs-12"></div></div>'; 
		var btn_edit = '<button class="btn btn-primary btnEditInputRealisasiBA" status="'+dtspb[0]['Status']+'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>';
		var btn_submit = '<button class="btn btn-success submitRealisasiBA" disabled> Submit</button>';
		
		// var btn_approve = '<button class="btn btn-primary" id="Approve_realisasi" action="approve">Approve</button>';
		var btn_approve = '';
		// var btn_reject = '<button class="btn btn-inverse" id="Reject_realisasi" action="reject">Reject</button>';
		var btn_reject = '';
		var btn_print = '<button class="btn btn-default print_page_realisasi"> <i class="fa fa-print" aria-hidden="true"></i> Print</button>';
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
		  		DivPageRealisasi.find('div[id="r_action_realisasi"]').html(html);
		  		DivPageRealisasi.find('div[id="r_action_realisasi"]').find('.col-xs-12').html('<div class = "pull-right">'+btn_edit+'&nbsp'+btn_submit+'</div>');
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
		    		DivPageRealisasi.find('div[id="r_action_realisasi"]').html(html);
		    		DivPageRealisasi.find('div[id="r_action_realisasi"]').find('.col-xs-12').html('<div class = "pull-right">'+btn_edit+'&nbsp'+btn_submit+'</div>');
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
		    		DivPageRealisasi.find('div[id="r_action_realisasi"]').html(html);
		    		DivPageRealisasi.find('div[id="r_action_realisasi"]').find('.col-xs-12').html('<div class = "pull-right">'+btn_approve+'&nbsp'+btn_reject+'</div>');
		    		// $('#Approve_realisasi').attr('approval_number',NumberOfApproval);
		    		// $('#Reject_realisasi').attr('approval_number',NumberOfApproval);
		    	}

		    break;
		  case 2:
		  case '2':
		  	var JsonStatus = dtspb[0]['JsonStatus'];
		  	JsonStatus = jQuery.parseJSON(JsonStatus);
		  	if (JsonStatus[0]['NIP'] == sessionNIP) {
		  		DivPageRealisasi.find('div[id="r_action_realisasi"]').html(html);
		  		DivPageRealisasi.find('div[id="r_action_realisasi"]').find('.col-xs-12').html('<div class = "pull-right">'+btn_print+'</div>');
		  	}
		    break;
		  default:
		    // code block
		}
	}

	function makeSignaturesRealiasi (DivPageRealisasi,JsonStatus)
	{
		if (JsonStatus != '') {
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
			DivPageRealisasi.find('#r_signatures_realisasi').html(html);
		}
	}


	function makeAction()
	{
		var Dataselected2 = ClassDt.DataPaymentSelected;
		var dtspb = Dataselected2.dtspb;

		var html = '<div class = "row noPrint"><div class = "col-xs-12"></div></div>'; 
		var btn_edit = '<button class="btn btn-primary btnEditInputBA" status="'+dtspb[0]['Status']+'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>';
		var btn_submit = '<button class="btn btn-success submitBA" disabled> Submit</button>';
		
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

	function makeSignatures(se_content,JsonStatus)
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

	function makepage_status(Dataselected2,se_content)
	{
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

		se_content.find('.page_status').html('<div style = "color : red">Status : '+StatusName+'</div><div><a href="javascript:void(0)" class="btn btn-info btn_circulation_sheet" code="'+dtspb[0]['Code']+'" ID_payment = "'+dtspb[0]['ID']+'">Info</a></div></div>');

	}

	$(document).off('click', '.btn_circulation_sheet').on('click', '.btn_circulation_sheet',function(e) {
	    var url = base_url_js+'rest2/__show_info_payment';
	    var ID_payment = $(this).attr('id_payment');
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
   			$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Info Payment'+'</h4>');
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

	$(document).off('click', '.btnEditInputBA').on('click', '.btnEditInputBA',function(e) {
		var Status = $(this).attr('status');
		if (Status != 2) {
			var ev2 = $(this).closest('#pageContent');
			ev2.find('input').not('.TglBA').prop('disabled',false);
			ev2.find('button').prop('disabled',false);
			ev2.find('select').prop('disabled',false);
			ev2.find('.dtbank[tabindex!="-1"]').select2({
			    //allowClear: true
			});
			$(this).remove();
			ev2.find('.TypePay').trigger('change');
		}
		else
		{
			toastr.info('Data Bank Advance telah approve, tidak bisa edit');
		}	
	})

	$(document).off('click', '.submitBA').on('click', '.submitBA',function(e) {
		// validation
		var ev = $(this).closest('#pageContent');
		var action = 'edit';
		if (confirm('Are you sure?')) {
			var validation = validation_input_ba(ev);
			if (validation) {
				SubmitBA('.submitBA',ev,action);
			}
		}	
	})

	function validation_input_ba(ev)
	{
		var find = true;
		var data = {
			Biaya : ev.find('.Money_Pembayaran').val(),
			TypePay : ev.find('.TypePay').val(),
			ID_bank : ev.find('.dtbank').val(),
			NoRekening : ev.find('.NoRekening').val(),
			Nama_Penerima : ev.find('.Nama_Penerima').val(),
			Date_Needed : ev.find('.TglBA').val(),
		};
		if (validationBA(data) ) {
			
		}
		else
		{
			find = false;
		}
		
		return find;
	}

	function validationBA(arr)
	{
	  var toatString = "";
	  var result = "";
	  for(var key in arr) {
	     switch(key)
	     {
	      case  "Biaya" :
	            if (arr[key] <= 0) {
	            	toatString += 'Pembayaran tidak boleh kecil sama dengan nol' + "<br>";
	            }
	            break;
	      case  "TypePay" :
	            if (arr[key] == 'Transfer') {
	            	var tt = arr['ID_bank'];
	            	result = Validation_required(tt,'Bank');
	            	if (result['status'] == 0) {
	            	  toatString += result['messages'] + "<br>";
	            	}

	            	var tt = arr['NoRekening'];
	            	result = Validation_required(tt,'No Rekening');
	            	if (result['status'] == 0) {
	            	  toatString += result['messages'] + "<br>";
	            	}  
	            }
	            break;      
	      case  "Date_Needed" :
	      case  "Nama_Penerima" :
	            result = Validation_required(arr[key],key);
	            if (result['status'] == 0) {
	              toatString += result['messages'] + "<br>";
	            } 
	            break;      
	     }

	  }
	  if (toatString != "") {
	    toastr.error(toatString, 'Failed!!');
	    return false;
	  }

	  return true;
	}

	function SubmitBA(elementbtn,ev,action)
	{
		loadingStart();
		var Code_po_create = ClassDt.Code_po_create;
		var Departement = IDDepartementPUBudget;
		var ID_payment = ClassDt.ID_payment;
		var ID_budget_left = 0;
		var form_data = new FormData();

		var Biaya = ev.find('.Money_Pembayaran').val();
		Biaya = findAndReplace(Biaya, ".","");
		var TypePay = ev.find('.TypePay').val();
		var Perihal = ev.find('.Perihal').text();
		var No_Rekening = ev.find('.NoRekening').val();
		var ID_bank = ev.find('.dtbank option:selected').val();
		if (TypePay == 'Cash') {
			ID_bank = 0;
		}
		var Nama_Penerima = ev.find('.Nama_Penerima').val();
		var Date_Needed = ev.find('.TglBA').val();

		var data = {
			Code_po_create : Code_po_create,
			Departement : Departement,
			ID_budget_left : ID_budget_left,
			Biaya : Biaya,
			TypePay : TypePay,
			Perihal : Perihal,
			No_Rekening : No_Rekening,
			ID_bank : ID_bank,
			Nama_Penerima : Nama_Penerima,
			Date_Needed : Date_Needed,
			ID_payment : ID_payment,
			action : action,
		};

		var token = jwt_encode(data,"UAP)(*");
		form_data.append('token',token);

		var DataPaymentSelected = ClassDt.DataPaymentSelected;
		var data = ClassDt.DataPaymentPO;
		var dtspb = DataPaymentSelected.dtspb;
		var data_verify = {
			Code_po_create : Code_po_create,
			InvoicePO : dtspb[0].InvoicePO,
			InvoiceLeftPO : dtspb[0].InvoiceLeftPO,
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

		var url = base_url_js + "budgeting/submitba"
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
		  				loadFirst();
		  			},1000);
		  			// load first load data
		  			
		  		}
		  		else
		  		{
		  			loadingEnd(500);
		  			toastr.error("Connection Error, Please try again", 'Error!!');
		  		}
		  	}
		  	else{
		  		toastr.success('Saved');
		  		setTimeout(function () {
		  			loadFirst();
		  			//window.location.href = base_url_js+'budgeting_menu/pembayaran/spb';
		  		},1500);
		  	}
		    
		  },
		  error: function (data) {
		    toastr.error("Connection Error, Please try again", 'Error!!');
		  }
		})
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
		// setTimeout(function () {
		//     _ajax_terbilang(v).then(function(data){
		//     	ev.find('.terbilang').html('Terbilang (Rupiah) : '+data+' Rupiah');
		//     })
		// },500);

	})

	$(document).off('change', '.TypePay').on('change', '.TypePay',function(e) {
		var ev = $(this).closest('#pageContent');
		if ($(this).val() == 'Cash') {
			ev.find('.NoRekening').prop('disabled',true);
			// ev.find('.Nama_Penerima').prop('disabled',true);
			ev.find('.dtbank').prop('disabled',true);
		}
		else
		{
			ev.find('.NoRekening').prop('disabled',false);
			// ev.find('.Nama_Penerima').prop('disabled',false);
			ev.find('.dtbank').prop('disabled',false);
		}
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


	$(document).off('click', '#Approve').on('click', '#Approve',function(e) {
		if (confirm('Are you sure ?')) {
			loading_button('#Approve');
			var ID_payment = ClassDt.ID_payment;
			var Code_po_create = ClassDt.Code_po_create;
			var approval_number = $(this).attr('approval_number');
			// var url = base_url_js + 'rest2/__approve_po';
			var url = base_url_js + 'rest2/__approve_payment';
			var data = {
				ID_payment : ID_payment,
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
					loadFirst();
				}
				else
				{
					if (rs.Change == 1) {
						toastr.info('The Data already have updated by another person,Please check !!!');
						loadFirst();
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
			var ID_payment = ClassDt.ID_payment;
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

				var url = base_url_js + 'rest2/__approve_payment';
				var data = {
					ID_payment : ID_payment,
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
						loadFirst();
					}
					else
					{
						if (rs.Change == 1) {
							toastr.info('The Data already have updated by another person,Please check !!!');
							loadFirst();
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

	$(document).off('click', '.print_page').on('click', '.print_page',function(e) {
		var dt_arr = ClassDt.DataPaymentSelected;
		var dtspb = dt_arr.dtspb;
		var ID_payment = dtspb[0]['ID'];
		var po_data = ClassDt.po_data;
		var Dataselected = ClassDt.DataPaymentPO;

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

	$(document).off('click', '.submitRealisasiBA').on('click', '.submitRealisasiBA',function(e) {
		// validation
		var ev = $(this).closest('.realisasi_page');
		var DataPaymentSelected = ClassDt.DataPaymentSelected;
		var dtspb = DataPaymentSelected.dtspb;
		var Detail = dtspb[0].Detail;
		var Realisasi = Detail[0].Realisasi;
		var action = 'add';
		if (Realisasi.length > 0) {
			var action = 'edit';
		}
		if (confirm('Are you sure?')) {
			var validation = validation_input_ba_realisasi(ev,action);
			if (validation) {
				submitBA_realisasi('.submitRealisasiBA',ev,action);
			}
		}

	})

	function validation_input_ba_realisasi(ev,action)
	{
		var find = true;
		var data = {
			NoInvoice : ev.find('.NoInvoice').val(),
			NoTandaTerima : ev.find('.NoTT').val(),
			Date_Realisasi : ev.find('.TglRealisasiBA').val(),
		};

		if (validation__(data) ) {
			if (action == 'add') {
				// Upload Tanda Terima 
				ev.find(".BrowseTT").each(function(){
					var IDFile = $(this).attr('id');
					var ev2 = $(this);
					if (!file_validation2(ev2,'Tanda Terima ') ) {
					  find = false;
					  return false;
					}
				})

				// Upload Invoice 
				ev.find(".BrowseInvoice").each(function(){
					var IDFile = $(this).attr('id');
					var ev2 = $(this);
					if (!file_validation2(ev2,'Invoice ') ) {
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

	function validation__(arr)
	{
		var toatString = "";
		var result = "";
		for(var key in arr) {
		   switch(key)
		   {
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

	function file_validation2(ev,TheName = '')
	{
	    var files = ev[0].files;
	    var error = '';
	    var msgStr = '';
	    var max_upload_per_file = 4;
	    if (files.length > 0) {
	    	if (files.length > max_upload_per_file) {
	    	  msgStr += 'Upload File '+TheName + ' 1 Document should not be more than 4 Files<br>';

	    	}
	    	else
	    	{
	    	  for(var count = 0; count<files.length; count++)
	    	  {
	    	   var no = parseInt(count) + 1;
	    	   var name = files[count].name;
	    	   var extension = name.split('.').pop().toLowerCase();
	    	   if(jQuery.inArray(extension, ['jpg' ,'png','jpeg','pdf','doc','docx']) == -1)
	    	   {
	    	    msgStr += 'Upload File '+TheName + ' Invalid Type File<br>';
	    	    //toastr.error("Invalid Image File", 'Failed!!');
	    	    // return false;
	    	   }

	    	   var oFReader = new FileReader();
	    	   oFReader.readAsDataURL(files[count]);
	    	   var f = files[count];
	    	   var fsize = f.size||f.fileSize;
	    	   // console.log(fsize);

	    	   if(fsize > 2000000) // 2mb
	    	   {
	    	    msgStr += 'Upload File '+TheName +  ' Image File Size is very big<br>';
	    	    //toastr.error("Image File Size is very big", 'Failed!!');
	    	    //return false;
	    	   }
	    	   
	    	  }
	    	}
	    }
	    else
	    {
	    	msgStr += 'Upload File '+TheName + ' Required';
	    }
	    

	    if (msgStr != '') {
	      toastr.error(msgStr, 'Failed!!');
	      return false;
	    }
	    else
	    {
	      return true;
	    }
	}

	function submitBA_realisasi(elementbtn,ev,action)
	{
		loadingStart();
		var ID_Realisasi = ev.attr('ID_Realisasi');
		var ID_payment = ClassDt.ID_payment;
		var DataPaymentSelected = ClassDt.DataPaymentSelected;
		var dtspb = DataPaymentSelected.dtspb;
		var Detail = dtspb[0].Detail;
		var ID_payment_type = Detail[0].ID; // ID Cash Advance
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
		var Date_Realisasi = ev.find('.TglRealisasiBA').val();

		var data = {
			ID_Realisasi : ID_Realisasi,
			NoInvoice : NoInvoice,
			NoTandaTerima : NoTandaTerima,
			Date_Realisasi : Date_Realisasi,
			ID_payment_type : ID_payment_type,
			ID_payment : ID_payment,
			action : action,
		};

		var token = jwt_encode(data,"UAP)(*");
		form_data.append('token',token);
		var url = base_url_js + "budgeting/submitba_realisasi_by_po"
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
		  		loadingEnd(500);
		  		toastr.error("Connection Error, Please try again", 'Error!!');
		  	}
		  	else{
		  		toastr.success('Saved');
		  		setTimeout(function () {
		  			loadFirst();
		  			loadingEnd(500);
		  		},1500);
		  	}
		    
		  },
		  error: function (data) {
		    toastr.error("Connection Error, Please try again", 'Error!!');
		    loadingEnd(500);
		  }
		})
	}

	$(document).off('click', '.btnEditInputRealisasiBA').on('click', '.btnEditInputRealisasiBA',function(e) {
		var Status = $(this).attr('status');
		if (Status != 2) {
			var ev2 = $(this).closest('.realisasi_page');
			ev2.find('input').not('.TglRealisasiBA').prop('disabled',false);
			ev2.find('button').prop('disabled',false);
			//ev2.find('select').prop('disabled',false);
			// ev2.find('.dtbank[tabindex!="-1"]').select2({
			//     //allowClear: true
			// });
			$(this).remove();
		}
		else
		{
			toastr.info('Realisasi telah approve tidak bisa edit');
		}	
	})

	$(document).off('click', '.print_page_realisasi').on('click', '.print_page_realisasi',function(e) {
		var dt_arr = ClassDt.DataPaymentSelected;
		var dtspb = dt_arr.dtspb;
		var ID_payment = dtspb[0]['ID'];
		var po_data = ClassDt.po_data;
		var Dataselected = ClassDt.DataPaymentPO;
		// console.log(dt_arr);return;

		var url = base_url_js+'save2pdf/print/pre_pembayaran_realisasi_po';
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
