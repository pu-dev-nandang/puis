<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
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
			MakeDomHtml(ID_payment,TypePay,CodeSPB,Code_po_create,PR);
		}
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


	function MakeDomHtml(ID_payment,TypePay,CodeSPB,Code_po_create,PR)
	{
		var html = '';
		html += __showHistory(ID_payment,Code_po_create,PR);
		var se_content = $('#pageContent');
		se_content.html(html);

	}

	function __showHistory(ID_payment_ori,Code_po_create,PR)
	{
		var html = '';
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
						html += '<tr>'+
									'<td class = "TD1"><label>PO / SPK Code</label></td>'+
									'<td>:</td>'+
									'<td>'+'<a href = "javascript:void(0)" Code_po_create = "'+Code_po_create+'" class = "printpo" TypeCode = "'+TypeCode_PO+'">'+Code_po_create+'</a></td>'+
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
					  default:
					    FolderPayment = '';
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
		return html;
	}

	$(document).off('click', '.printpr').on('click', '.printpr',function(e) {
		var url = base_url_js+'save2pdf/print/prdeparment';
		var PRCode = $(this).attr('prcode');
		data = {
		  PRCode : PRCode,
		}
		var token = jwt_encode(data,"UAP)(*");
		FormSubmitAuto(url, 'POST', [
		    { name: 'token', value: token },
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
		var dt_arr = ClassDt.po_payment_data;
		var dtspb = dt_arr.dtspb;
		var ID_payment = dtspb[0]['ID'];
		var po_data = ClassDt.po_data;
		var Dataselected = ClassDt.all_po_payment;

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