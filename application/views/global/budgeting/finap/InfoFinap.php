<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<style type="text/css">
	.TD1 {
		width: 40%;
	}
</style>
<div class="row noPrint">
	<div class="col-xs-2">
		<div><a href="<?php echo base_url().'finance_ap' ?>" class = "btn btn-warning"> <i class="fa fa-arrow-circle-left"></i> Back to List</a></div>
		<br>
		<div id="page_status" class="noPrint"></div>
	</div>
	<div class="col-md-8" style="min-width: 800px;overflow: auto;">
		<div class="well" id = "pageContent" style="margin-top: 10px;">

		</div>
	</div>
</div>
<script type="text/javascript">
	var ClassDt = {
		po_data : <?php echo json_encode($po_data) ?>,
		po_payment_data : [],
		all_po_payment : [],
		ID_payment_fin : '<?php echo $ID_payment_fin ?>',
		ID_payment : '<?php echo $ID_payment ?>',
		Code_po_create : '<?php echo $Code_po_create ?>',
		TypePay : '<?php echo $TypePay ?>',
		CodeSPB : '<?php echo $CodeSPB ?>',
		PRCode : '<?php echo $PRCode ?>',
	};

	$(document).ready(function() {
		loadFirst();
	});

	function loadFirst()
	{
		var TypePay = ClassDt.TypePay;
		var ID_payment = ClassDt.ID_payment;
		var CodeSPB = ClassDt.CodeSPB;
		var Code_po_create = ClassDt.Code_po_create;
		var PR = ClassDt.PRCode;

		if (Code_po_create != '' && Code_po_create != null) {
			Get_data_spb_grpo(Code_po_create).then(function(data){
				ClassDt.all_po_payment = data;
				var dt_arr = __getRsViewGRPO_SPB(ID_payment,data);
				ClassDt.po_payment_data = dt_arr;
				MakeDomHtml(ID_payment,TypePay,CodeSPB,Code_po_create,PR);
			})
			
		}
		else
		{
			__load_data_payment(ID_payment).then(function(data){
				ClassDt.po_payment_data = data;
				MakeDomHtml(ID_payment,TypePay,CodeSPB,Code_po_create,PR);
			})
			// MakeDomHtml(ID_payment,TypePay,CodeSPB,Code_po_create,PR);
		}
	}

	function __load_data_payment(ID_payment)
	{
		var def = jQuery.Deferred();
		var url = base_url_js+'rest2/__Get_data_payment_user';
		var data = {
		    ID_payment : ID_payment,
		    auth : 's3Cr3T-G4N',
		};
		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{ token:token },function (resultJson) {
			
		}).done(function(resultJson) {
			def.resolve(resultJson);
		}).fail(function() {
		  toastr.info('No Result Data');
		  def.reject();  
		}).always(function() {
		                
		});	
		return def.promise();
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

	function __getRsViewGRPO_SPB(ID_payment,Dataselected)
	{
		var arr=[];
		if (typeof Dataselected.dtspb !== "undefined") {
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
		}

		return arr;
	}


	function MakeDomHtml(ID_payment,TypePay,CodeSPB,Code_po_create,PR)
	{
		var html = '';
		html += __showHistory(ID_payment,Code_po_create,PR);
		var se_content = $('#pageContent');
		se_content.html(html);
		var DataPaymentSelected = ClassDt.po_payment_data;
		var DivPageRealisasi = se_content;
		var htmlrelisasi = makePagerealisasi(DataPaymentSelected,DivPageRealisasi);

	}

	function __showHistory(ID_payment_ori,Code_po_create,PR)
	{
		var html = '';
		if (Code_po_create != '' && Code_po_create != null) {
			var all_po_payment = ClassDt.all_po_payment;
			var dtspb_all = all_po_payment.dtspb;
				for (var i = 0; i < dtspb_all.length; i++) {
					var ID_payment = dtspb_all[i].ID;
					if (ID_payment == ID_payment_ori) {
						var dt_arr = __getRsViewGRPO_SPB(ID_payment,all_po_payment);
						var po_payment_data = dt_arr;
						var dtspb = po_payment_data.dtspb;
						html += '<div class ="row" style ="margin-top:30px;">'+
										'<div class = "col-xs-12">'+
											'<div align="center"><h2 class="payment_number">Payment</h2></div>'+
											'<hr style="height:2px;border:none;color:#333;background-color:#333;margin-top: -3px;">'+
											'<table class="table borderless" style="font-weight: bold;">'+
											'<thead></thead>'+
											'<tbody>';

						var se_content = $('#page_content');
						if (PR != '' && PR != null) {
							html += '<tr>'+
										'<td class = "TD1"><label>PR Code</label></td>'+
										'<td>:</td>'+
										'<td>'+'<a href = "javascript:void(0)" prcode = "'+PR+'" class = "printpr">'+PR+'</a></td>'+
									'</tr>';	
						}

						if (Code_po_create != '' && Code_po_create != null) {
							var po_data = ClassDt.po_data;
							var po_create = po_data.po_create;
							var TypeCode_PO = po_create[0]['TypeCode'].toLowerCase();
							var POPrint_Approve = jQuery.parseJSON(po_create[0]['POPrint_Approve']);
							var ahrefPO_SPK = '<a href = "javascript:void(0)" Code_po_create = "'+Code_po_create+'" class = "printpo" TypeCode = "'+TypeCode_PO+'">'+Code_po_create+'</a>';
							if (POPrint_Approve != null && POPrint_Approve != '') {
								ahrefPO_SPK = '<a href = "'+base_url_js+'fileGetAny/budgeting-po-'+POPrint_Approve[0]+'" target="_blank" class = "Fileexist">'+Code_po_create+'</a>'
							}

							html += '<tr>'+
										'<td class = "TD1"><label>PO / SPK Code</label></td>'+
										'<td>:</td>'+
										'<td>'+ahrefPO_SPK+'</td>'+
									'</tr>';

							var pre_po_supplier = po_data.pre_po_supplier;
							var t = '';									
							for (var i = 0; i < pre_po_supplier.length; i++) {
								var File = jQuery.parseJSON(pre_po_supplier[i].FileOffer);
								var Reason = (pre_po_supplier[i].ApproveSupplier == 1) ? '<label style="margin-left:19px;">Reason : <br>'+ nl2br(pre_po_supplier[i].Desc)+'</label>' : '';
								var Approve = (pre_po_supplier[i].ApproveSupplier == 1) ? ' (Approve) ' : '';
								// t += '<li><a href="'+base_url_js+'fileGetAny/budgeting-po-'+File[0]+'" target="_blank">'+pre_po_supplier[i].NamaSupplier+'</a>'+'</li>';
								t += '<li><a href="'+base_url_js+'fileGetAny/budgeting-po-'+File[0]+'" target="_blank">'+pre_po_supplier[i].NamaSupplier+Approve+'</a>'+'<br>'+
									Reason+
									'</li>';
							}		


							html += '<tr>'+
										'<td class = "TD1"><label>Perbandingan Vendor</label></td>'+
										'<td>:</td>'+
										'<td>'+t+'</td>'+
									'</tr>';			
						}

						var lblAdd = '';
						var TypePay = dtspb[0].Type;
						if (TypePay == 'Spb') {
							lblAdd = "<br> Code : "+dtspb[0].Code;
						}

						html += '<tr>'+
									'<td class = "TD1"><label>Payment Type'+' '+lblAdd+'</label></td>'+
									'<td>:</td>'+
									'<td>'+'<a href = "javascript:void(0)" ID_payment = "'+ID_payment+'" class = "printpay">'+TypePay+'</a></td>'+
								'</tr>';

						var FolderPayment = '';
						switch(TypePay) {
						  case "Spb":
						    FolderPayment = "spb";
						    break;
						  case "Bank Advance":
						    FolderPayment = "bankadvance";
						    break;
						  case "Cash Advance":
						    FolderPayment = "cashadvance";
						    break;
						  case "Petty Cash":
						    FolderPayment = "pettycash";
						    break;   
						  default:
						    FolderPayment = '';
						}

						// check IOM exist or not
						var UploadIOM = jQuery.parseJSON(dtspb[0].UploadIOM);
						if (UploadIOM != '' && UploadIOM != null && UploadIOM != undefined) {
							UploadIOM = UploadIOM[0];
							html += '<tr>'+
										'<td class = "TD1"><label>IOM</label></td>'+
										'<td>:</td>'+
										'<td>'+'<a href = "'+base_url_js+'fileGetAny/budgeting-'+FolderPayment+'-'+UploadIOM+'" target="_blank" class = "Fileexist">'+dtspb[0].NoIOM+'</a>'+'</td>'+
									'</tr>';
						}					


						if (Code_po_create != '' && Code_po_create != null) {
							// make GRPO
							var TDGRPO = '-';
							var Good_Receipt = dtspb[0].Good_Receipt;
							if (Good_Receipt.length > 0) {
								TDGRPO = '<button class = "btn btn-primary ShowGRPOMODAL" id_payment = "'+ID_payment+'">Show GRPO</button>';
							}	
							html += '<tr>'+
										'<td class = "TD1"><label>GRPO</label></td>'+
										'<td>:</td>'+
										'<td>'+TDGRPO+'</td>'+
									'</tr>';	

						}		
						
						// check for document invoice
						if (typeof dtspb[0].Detail[0]['UploadInvoice'] !== "undefined") {
							var UploadInvoice = jQuery.parseJSON(dtspb[0].Detail[0]['UploadInvoice']);
							if (UploadInvoice.length > 0 && UploadInvoice != '' && UploadInvoice != null && UploadInvoice != undefined) {
								UploadInvoice = UploadInvoice[0];
								html += '<tr>'+
											'<td class = "TD1"><label>Invoice</label></td>'+
											'<td>:</td>'+
											'<td>'+'<a href = "'+base_url_js+'fileGetAny/budgeting-'+FolderPayment+'-'+UploadInvoice+'" target="_blank" class = "Fileexist">'+dtspb[0].Detail[0]['NoInvoice']+'</a>'+'</td>'+
										'</tr>';
							}
							
							var UploadTandaTerima = jQuery.parseJSON(dtspb[0].Detail[0]['UploadTandaTerima']);
							if (UploadTandaTerima.length > 0 && UploadTandaTerima != '' && UploadTandaTerima != null && UploadTandaTerima != undefined) {
								UploadTandaTerima = UploadTandaTerima[0];
								html += '<tr>'+
											'<td class = "TD1"><label>Tanda Terima</label></td>'+
											'<td>:</td>'+
											'<td>'+'<a href = "'+base_url_js+'fileGetAny/budgeting-'+FolderPayment+'-'+UploadTandaTerima+'" target="_blank" class = "Fileexist">'+dtspb[0].Detail[0]['NoTandaTerima']+'</a>'+'</td>'+
										'</tr>';
							}

						}

						var UploadVoucher = jQuery.parseJSON(dtspb[0].FinanceAP[0].UploadVoucher);
						var htmlUploadVoucher = '';
						if (UploadVoucher != '' && UploadVoucher != null && UploadVoucher != undefined) {
							UploadVoucher = UploadVoucher[0];
							htmlUploadVoucher = '<a href = "'+base_url_js+'fileGetAny/finance-'+UploadVoucher+'" target="_blank" class = "Fileexist">'+'File '+dtspb[0].FinanceAP[0].NoVoucher+'</a>';
						}

						html += '<tr>'+
									'<td class = "TD1"><label>No Dokumen</label></td>'+
									'<td>:</td>'+
									'<td><label>'+dtspb[0].FinanceAP[0].NoVoucher+'</label></td>'+
								'</tr>'+
								'<tr>'+
									'<td><label>Upload Dokumen</label></td>'+
									'<td>:</td>'+
									'<td>'+htmlUploadVoucher+'</td>'+
								'</tr>';

						html += '</tbody></table>';
						html += '</div></div>';	
						break;
					}
					
				}
		}
		else
		{
			var po_payment_data = ClassDt.po_payment_data;
			// check for document invoice
			if (typeof po_payment_data.dtspb !== "undefined") {
				var dtspb = po_payment_data.dtspb;
			}
			else
			{
				var dtspb = po_payment_data.payment;
			}

			var CodeSPB = dtspb[0].Code;
			var TypePay = dtspb[0].Type;
			var ID_payment = dtspb[0].ID;

			html += '<div class ="row FormPage" style ="margin-top:30px;">'+
							'<div class = "col-xs-8 col-md-offset-2" style = "min-width: 600px;overflow: auto;">'+
								'<div class="well">'+
								'<div align="center"><h2 class="payment_number">Payment</h2></div>'+
								'<hr style="height:2px;border:none;color:#333;background-color:#333;margin-top: -3px;">'+
								'<table class="table borderless" style="font-weight: bold;">'+
								'<thead></thead>'+
								'<tbody>';
			var lblAdd = '';
			if (TypePay == 'Spb') {
				lblAdd = "<br> Code : "+CodeSPB;
			}

			html += '<tr>'+
						'<td class = "TD1"><label>Payment Type : '+TypePay+' '+lblAdd+'</label></td>'+
						'<td>:</td>'+
						'<td>'+'<a href = "javascript:void(0)" ID_payment = "'+ID_payment+'" class = "printpay">'+TypePay+'</a></td>'+
					'</tr>';

			var FolderPayment = '';
			switch(TypePay) {
			  case "Spb":
			    FolderPayment = "spb";
			    break;
			  case "Bank Advance":
			    FolderPayment = "bankadvance";
			    break;
			  case "Cash Advance":
			    FolderPayment = "cashadvance";
			    break;
			  case "Petty Cash":
			    FolderPayment = "pettycash";
			     break;  
			  default:
			    FolderPayment = '';
			}


			// check IOM exist or not
			var UploadIOM = jQuery.parseJSON(dtspb[0].UploadIOM);
			if (UploadIOM != '' && UploadIOM != null && UploadIOM != undefined) {
				UploadIOM = UploadIOM[0];
				html += '<tr>'+
							'<td class = "TD1"><label>IOM</label></td>'+
							'<td>:</td>'+
							'<td>'+'<a href = "'+base_url_js+'fileGetAny/budgeting-'+FolderPayment+'-'+UploadIOM+'" target="_blank" class = "Fileexist">'+dtspb[0].NoIOM+'</a>'+'</td>'+
						'</tr>';
			}


			if (typeof dtspb[0].Detail[0]['UploadInvoice'] !== "undefined") {
				var UploadInvoice = jQuery.parseJSON(dtspb[0].Detail[0]['UploadInvoice']);
				if (UploadInvoice.length > 0 && UploadInvoice != '' && UploadInvoice != null && UploadInvoice != undefined) {
					UploadInvoice = UploadInvoice[0];
					html += '<tr>'+
								'<td class = "TD1"><label>Invoice</label></td>'+
								'<td>:</td>'+
								'<td>'+'<a href = "'+base_url_js+'fileGetAny/budgeting-'+FolderPayment+'-'+UploadInvoice+'" target="_blank" class = "Fileexist">'+dtspb[0].Detail[0]['NoInvoice']+'</a>'+'</td>'+
							'</tr>';
				}
				
				var UploadTandaTerima = jQuery.parseJSON(dtspb[0].Detail[0]['UploadTandaTerima']);
				if (UploadTandaTerima.length > 0 && UploadTandaTerima != '' && UploadTandaTerima != null && UploadTandaTerima != undefined) {
					UploadTandaTerima = UploadTandaTerima[0];
					html += '<tr>'+
								'<td class = "TD1"><label>Tanda Terima</label></td>'+
								'<td>:</td>'+
								'<td>'+'<a href = "'+base_url_js+'fileGetAny/budgeting-'+FolderPayment+'-'+UploadTandaTerima+'" target="_blank" class = "Fileexist">'+dtspb[0].Detail[0]['NoTandaTerima']+'</a>'+'</td>'+
							'</tr>';
				}

			}

			var UploadVoucher = jQuery.parseJSON(dtspb[0].FinanceAP[0].UploadVoucher);
			var htmlUploadVoucher = '';
			if (UploadVoucher != '' && UploadVoucher != null && UploadVoucher != undefined) {
				UploadVoucher = UploadVoucher[0];
				htmlUploadVoucher = '<a href = "'+base_url_js+'fileGetAny/finance-'+UploadVoucher+'" target="_blank" class = "Fileexist">'+'File '+dtspb[0].FinanceAP[0].NoVoucher+'</a>';
			}

			html += '<tr>'+
						'<td class = "TD1"><label>No Dokumen</label></td>'+
						'<td>:</td>'+
						'<td><label>'+dtspb[0].FinanceAP[0].NoVoucher+'</label></td>'+
					'</tr>'+
					'<tr>'+
						'<td><label>Upload Dokumen</label></td>'+
						'<td>:</td>'+
						'<td>'+htmlUploadVoucher+'</td>'+
					'</tr>';

			html += '</tbody></table>';

		}
		
		return html;
	}

	$(document).off('click', '.printpr').on('click', '.printpr',function(e) {
		// var url = base_url_js+'save2pdf/print/prdeparment';
		// var PRCode = $(this).attr('prcode');
		// data = {
		//   PRCode : PRCode,
		// }
		// var token = jwt_encode(data,"UAP)(*");
		// FormSubmitAuto(url, 'POST', [
		//     { name: 'token', value: token },
		// ]);

		// show page pr
		var PRCode = $(this).attr('prcode');
		var PRCodeURL = jwt_encode(PRCode,"UAP)(*");
		var url = base_url_js+'budgeting_pr/'+PRCodeURL;
		FormSubmitAuto(url, 'POST', [
		    {},
		]);
	})

	$(document).off('click', '.printpo').on('click', '.printpo',function(e) {
		// print pdf
		var url = base_url_js+'save2pdf/print/spk_or_po';
		data = {
		  Code : $(this).attr('code_po_create') ,
		  type : $(this).attr('TypeCode'),
		}
		var token = jwt_encode(data,"UAP)(*");
		FormSubmitAuto(url, 'POST', [
		    { name: 'token', value: token },
		]);
	})

	$(document).off('click', '.printpay').on('click', '.printpay',function(e) {
		var ID_payment = $(this).attr('id_payment');
		var po_data = ClassDt.po_data;
		var Dataselected = ClassDt.all_po_payment;
		if (typeof Dataselected.dtspb !== "undefined") { // trigger non po/spk
			var dt_arr = __getRsViewGRPO_SPB(ID_payment,Dataselected);

			var url = base_url_js+'save2pdf/print/pre_pembayaran';
			var data = {
			  ID_payment : ID_payment,
			  dt_arr : dt_arr,
			  po_data : po_data,
			  Dataselected : Dataselected,
			}
			var token = jwt_encode(data,"UAP)(*");
		}
		else
		{
			var DataPayment = ClassDt.po_payment_data;
			var dt = DataPayment.payment;
			var data = {
			  ID_payment : ID_payment,
			  TypePay : dt[0].Type,
			  DataPayment : DataPayment,
			}
			var token = jwt_encode(data,"UAP)(*");
			var url = base_url_js+'save2pdf/print/payment_user';
		}
		
		FormSubmitAuto(url, 'POST', [
		    { name: 'token', value: token },
		]);
	})


	function makePagerealisasi(DataPaymentSelected,DivPageRealisasi)
	{
		var html = '';
		if (typeof DataPaymentSelected.dtspb !== "undefined") {
			var dtspb = DataPaymentSelected.dtspb;
		}
		else
		{
			var dtspb = DataPaymentSelected.payment;
		}
		// var dtspb = DataPaymentSelected.dtspb;
		// selain spb
		var Type = dtspb[0].Type;
		if (Type != 'Spb') {
			var FolderName = '';
			if (Type == 'Bank Advance') {
				FolderName = 'bankadvance';
			}
			else if (Type == 'Cash Advance') {
				FolderName = 'cashadvance';
			}
			else if (Type == 'Petty Cash') {
				FolderName = 'pettycash';
			}

			var Detail = dtspb[0].Detail;
			var Realisasi = Detail[0].Realisasi;
			var Dis = '';
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
			var htmldetail = '';
			if (Realisasi.length > 0) { // exist
				Dis = 'disabled';
				StatusRealiasi = Realisasi[0].Status;
				btn_hide_submit = 'hide';
			
				if (StatusRealiasi == 2) {
					Dis = 'disabled';
				}

				ID_Realisasi = Realisasi[0].ID;
				UploadInvoice = jQuery.parseJSON(Realisasi[0]['UploadInvoice']);
				UploadInvoice = UploadInvoice[0];
				NoInvoice = Realisasi[0].NoInvoice;
				LinkFileInvoice = '<a href = "'+base_url_js+'fileGetAny/budgeting-'+FolderName+'-'+UploadInvoice+'" target="_blank" class = "Fileexist">'+NoInvoice+'</a>';
				UploadTandaTerima = jQuery.parseJSON(Realisasi[0]['UploadTandaTerima']);
				UploadTandaTerima = UploadTandaTerima[0];
				NoTandaTerima = Realisasi[0].NoTandaTerima;
				LinkUploadTandaTerima = '<a href = "'+base_url_js+'fileGetAny/budgeting-'+FolderName+'-'+UploadTandaTerima+'" target="_blank" class = "Fileexist">'+NoTandaTerima+'</a>';
				
				Date_Realisasi = Realisasi[0].Date_Realisasi;
				JsonStatus = jQuery.parseJSON(Realisasi[0]['JsonStatus']);

				// show detail realisasi
				// console.log(dtspb);
				var Detail_detail = Detail[0].Detail;
				var loopDetail = '';
				for (var i = 0; i < Detail_detail.length; i++) {
					var NamaBiaya = Detail_detail[i].NamaBiaya;
					var Invoice_detail = formatRupiah(Detail_detail[i].Invoice);
					var InvoiceRealisasi = formatRupiah(0);
					var ID_cash_advance_detail = Detail_detail[i].ID;
					var Realisasi_detail = Detail_detail[i].Realisasi;
					for (var k = 0; k < Realisasi_detail.length; k++) {
						if (Type == 'Cash Advance') {
							var ID_compare = Realisasi_detail[k].ID_cash_advance_detail;
						}
						else if(Type == 'Bank Advance')
						{
							var ID_compare = Realisasi_detail[k].ID_bank_advance_detail;
						}
						else if (Type == 'Petty Cash') {
							var ID_compare = Realisasi_detail[k].ID_petty_cash_detail;
						}
						
						if (ID_cash_advance_detail == ID_compare) {
							InvoiceRealisasi = formatRupiah(Realisasi_detail[k].InvoiceRealisasi);
							break;
						}
					}

					loopDetail += '<tr>'+
										'<td>'+NamaBiaya+'</td>'+
										'<td>'+Invoice_detail+'</td>'+
										'<td>'+InvoiceRealisasi+'</td>'+
									'</tr>';	
				}
				htmldetail = '<tr>'+
								'<td class="TD1">'+
									'Detail'+
								'</td>'+
								'<td class="TD2">'+
									':'+
								'</td>'+
								'<td>'+
									'<table class = "table">'+
										'<thead>'+
											'<tr>'+
												'<th>'+
													'Nama Biaya'+
												'</th>'+
												'<th>'+
													'Invoice'+
												'</th>'+
												'<th>'+
													'Invoice Realisasi'+
												'</th>'+
											'</th>'+
										'</thead>'+
										'<tbody>'+
											loopDetail+
										'</tbody>'+
									'</table>'+
								'</td>'+
							'</tr>';

				// show petty cash
				var PettyCashHtml = '';
				if (Type == 'Cash Advance') {
					var CodePettyCash = Realisasi[0]['CodePettyCash'];
					a_href = '<a href = "javascript:void(0)" class ="ViewPettyCash" code = "'+CodePettyCash+'" ID_Realisasi = "'+ID_Realisasi+'" ID_payment = "'+dtspb[0].ID+'">'+CodePettyCash+'</a>';
					PettyCashHtml = '<tr>'+
										'<td class="TD1">'+
											'Petty Cash'+
										'</td>'+
										'<td class="TD2">'+
											':'+
										'</td>'+
										'<td>'+
											a_href+
										'</td>	'+			
									'</tr>';
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
												'<div id = "FileInvoice">'+
												LinkFileInvoice+
												'</div>'+
												'<br>'+
												'<label>No Tanda Terima</label>'+
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
												Date_Realisasi+
											'</td>	'+			
										'</tr>'+
										PettyCashHtml+
										htmldetail+
									'</tbody>'+
								'</table>'+
								'<div id="r_signatures_realisasi"></div>'+
								'<div id = "r_action_realisasi">'+
									'<div class="row">'+
										'<div class="col-md-12">'+
											'<div class="pull-right">'+
												''+
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
			
		}

		function makeSignaturesRealiasi (DivPageRealisasi,JsonStatus)
		{
			if (JsonStatus != '') {
				var html = '<div class= "row" style = "margin-top : 20px;">'+
								'<div class = "col-xs-12">'+
									''+
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

		function makeActionRealisasi(DivPageRealisasi,Realisasi)
		{
			var dtspb = Realisasi;
			var html = '<div class = "row noPrint"><div class = "col-xs-12"></div></div>'; 
			var btn_edit = '<button class="btn btn-primary btnEditInputRealisasiCA" status="'+dtspb[0]['Status']+'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>';
			var btn_submit = '<button class="btn btn-success submitRealisasiCA" disabled> Submit</button>';
			
			var btn_approve = '<button class="btn btn-primary" id="Approve_realisasi" action="approve">Approve</button>';
			var btn_reject = '<button class="btn btn-inverse" id="Reject_realisasi" action="reject">Reject</button>';
			var btn_print = '<button class="btn btn-default print_page_realisasi" ID_payment = "'+ClassDt.ID_payment+'"> <i class="fa fa-print" aria-hidden="true"></i> Print</button>';
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
			    		$('#Approve_realisasi').attr('approval_number',NumberOfApproval);
			    		$('#Reject_realisasi').attr('approval_number',NumberOfApproval);
			    	}

			    break;
			  case 2:
			  case '2':
			  	var JsonStatus = dtspb[0]['JsonStatus'];
			  	JsonStatus = jQuery.parseJSON(JsonStatus);
			  	DivPageRealisasi.find('div[id="r_action_realisasi"]').html(html);
			  	DivPageRealisasi.find('div[id="r_action_realisasi"]').find('.col-xs-12').html('<div class = "pull-right">'+btn_print+'</div>');
			    break;
			  default:
			    // code block
			}
		}
		
	}

	$(document).off('click', '#Approve_realisasi').on('click', '#Approve_realisasi',function(e) {
		// console.log(ClassDt);return;
		if (confirm('Are you sure ?')) {
			loading_button('#Approve_realisasi');
			var ID_payment = ClassDt.ID_payment;
			var ID_Realisasi = $(this).closest('.realisasi_page').attr('ID_Realisasi');
			var approval_number = $(this).attr('approval_number');
			// var url = base_url_js + 'rest2/__approve_po';
			var url = base_url_js + 'rest2/__approve_payment_realisasi';
			var data = {
				ID_payment : ID_payment,
				ID_Realisasi : ID_Realisasi,
				approval_number : approval_number,
				NIP : sessionNIP,
				action : 'approve',
				auth : 's3Cr3T-G4N',
				payment_data : ClassDt.po_payment_data,
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


	$(document).off('click', '#Reject_realisasi').on('click', '#Reject_realisasi',function(e) {
		if (confirm('Are you sure ?')) {
			var ID_payment = ClassDt.ID_payment;
			var ID_Realisasi = $(this).closest('.realisasi_page').attr('ID_Realisasi');
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

				var url = base_url_js + 'rest2/__approve_payment_realisasi';
				var data = {
					ID_payment : ID_payment,
					ID_Realisasi : ID_Realisasi,
					approval_number : approval_number,
					NIP : sessionNIP,
					action : 'reject',
					auth : 's3Cr3T-G4N',
					NoteDel : NoteDel,
					payment_data : ClassDt.DataPaymentPO,
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

	$(document).off('click', '.ShowGRPOMODAL').on('click', '.ShowGRPOMODAL',function(e) {
		var ID_payment = $(this).attr('id_payment');
		var data = ClassDt.all_po_payment;
		var dt_arr = __getRsViewGRPO_SPB(ID_payment,data);
		var dtspb = dt_arr.dtspb;
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

	$(document).off('click', '.print_page_realisasi').on('click', '.print_page_realisasi',function(e) {
		var ID_payment = $(this).attr('id_payment');
		var po_data = ClassDt.po_data;
		var Dataselected = ClassDt.all_po_payment;
		if (typeof Dataselected.dtspb !== "undefined") { // trigger non po/spk
			var dt_arr = __getRsViewGRPO_SPB(ID_payment,Dataselected);

			var url = base_url_js+'save2pdf/print/pre_pembayaran_realisasi_po';
			var data = {
			  ID_payment : ID_payment,
			  dt_arr : dt_arr,
			  po_data : po_data,
			  Dataselected : Dataselected,
			}
			var token = jwt_encode(data,"UAP)(*");
		}
		else
		{
			var DataPayment = ClassDt.po_payment_data;
			var dt = DataPayment.payment;
			var data = {
			  ID_payment : ID_payment,
			  TypePay : dt[0].Type,
			  DataPayment : DataPayment,
			}
			var token = jwt_encode(data,"UAP)(*");
			var url = base_url_js+'save2pdf/print/payment_user_realisasi';
		}
		
		FormSubmitAuto(url, 'POST', [
		    { name: 'token', value: token },
		]);
	})


	$(document).off('click', '.ViewPettyCash').on('click', '.ViewPettyCash',function(e) {
		var CodePettyCash = $(this).attr('code');
		var ID_Realisasi = $(this).attr('ID_Realisasi');
		var ID_payment = $(this).attr('id_payment');
		var po_data = ClassDt.po_data;
		var Dataselected = ClassDt.all_po_payment;
		if (typeof Dataselected.dtspb !== "undefined") { // trigger non po/spk
			var dt_arr = __getRsViewGRPO_SPB(ID_payment,Dataselected);

			var url = base_url_js+'save2pdf/print/realisasi_petty_cash';
			var data = {
			  ID_payment : ID_payment,
			  dt_arr : dt_arr,
			  po_data : po_data,
			  Dataselected : Dataselected,
			  CodePettyCash : CodePettyCash,
			}
			var token = jwt_encode(data,"UAP)(*");
		}
		else
		{
			var DataPayment = ClassDt.po_payment_data;
			var dt = DataPayment.payment;
			var data = {
			  ID_payment : ID_payment,
			  TypePay : dt[0].Type,
			  DataPayment : DataPayment,
			  CodePettyCash : CodePettyCash,
			}
			var token = jwt_encode(data,"UAP)(*");
			var url = base_url_js+'save2pdf/print/payment_user_realisasi';
		}
		
		FormSubmitAuto(url, 'POST', [
		    { name: 'token', value: token },
		]);
	})
	
</script>