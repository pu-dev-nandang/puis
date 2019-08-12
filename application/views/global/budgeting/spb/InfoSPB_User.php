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
	localStorage.setItem("PostBudgetDepartment_awal", JSON.stringify(<?php echo $detail_budgeting_remaining ?>));
	localStorage.setItem("PostBudgetDepartment", JSON.stringify(<?php echo $detail_budgeting_remaining ?>));
	var DivSession = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";
	var DivSessionName = '<?php echo $NameDepartement ?>';
	var NIP = sessionNIP;
	var S_Table_example_budget = '';

	var IDDepartementPUBudget = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";
	var ClassDt = {
		ID_payment : '<?php echo (isset($ID_payment)) ? $ID_payment : '' ?>',
		Code : "<?php echo $SPBCode ?>",
		BudgetRemaining : [],
		Year : "<?php echo $Year ?>",
		Departement : IDDepartementPUBudget,
		NmDepartement_Existing : '',
		ThisTableSelect : '',
		RuleAccess : [],
		DtExisting : [],
		G_data_bank : <?php echo json_encode($G_data_bank) ?>,
		PostBudgetDepartment : <?php echo $detail_budgeting_remaining ?>,
		PostBudgetDepartment_awal : <?php echo $detail_budgeting_remaining ?>,
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
	    LoadFirstLoad();
	}); // exit document Function

	function LoadFirstLoad()
	{
		var ID_payment = ClassDt.ID_payment;
		if (ID_payment != '') {
			__load_data_payment().then(function(data2){
				ClassDt.Departement = data2.payment[0].Departement;
				__loadRuleInput().then(function(data){
					var access = data['access'];
					if (access.length > 0) {
						ClassDt.RuleAccess = data;
						var se_content = $('#pageContent');

						ClassDt.DtExisting = data2;
						Make_PostBudgetDepartment_existing();
						makeDomHTML(se_content);
					}
					else
					{
						if (DivSession == 'NA.9') {
							var se_content = $('#pageContent');
							ClassDt.DtExisting = data2;
							Make_PostBudgetDepartment_existing();
							makeDomHTML(se_content);
						}
						else
						{
							$("#pageContent").empty();
							$("#pageContent").html('<h2 align = "center">Your not authorize these modul</h2>');
						}
						
					}

					loadingEnd(500);
				})

			})	
		}
		else
		{
			__loadRuleInput().then(function(data){
				var access = data['access'];
				if (access.length > 0) {
					ClassDt.RuleAccess = data;
					var se_content = $('#pageContent');
					makeDomHTML(se_content);
				}
				else
				{
					$("#pageContent").empty();
					$("#pageContent").html('<h2 align = "center">Your not authorize these modul</h2>');
				}

				loadingEnd(500);
			})
		}
	}

	function __load_data_payment()
	{
		var def = jQuery.Deferred();
		var ID_payment = ClassDt.ID_payment;
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

	function __loadRuleInput()
	{
		var def = jQuery.Deferred();
		var ID_payment = ClassDt.ID_payment;
		// check Rule for Input
		var url = base_url_js+"budgeting/checkruleinput";
		var data = {
			NIP : NIP,
		};
		if (ID_payment != '') {
			data = {
				NIP : NIP,
				Departement : ClassDt.Departement,
			};
		}
		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{ token:token },function (resultJson) {
			
		}).done(function(resultJson) {
		  var response = jQuery.parseJSON(resultJson);
		  def.resolve(response);
		}).fail(function() {
		  toastr.info('No Result Data');
		  def.reject(); 
		});
		return def.promise();
	}

	function makeDomHTML(se_content)
	{
		var html = '';
		var TypeInvoice = 'Pembayaran 1';
		var btnSPb = '';
		var Dis = '';
		var CodeWr = '';
		var LinkFileInvoice = '';
		var LinkUploadTandaTerima = '';
		var CodeSupplier = '';
		var Supplier = '';
		var Supplier_ = '';
		var NoInvoice = '';
		var NoTandaTerima = '';
		var Datee = '';
		var Perihal = '';
		var ID_bank = '';
		var NoRekening = '';
		var IsiInput = '';
		var NoIOM = '';
		if (ClassDt.ID_payment !='') {
			// console.log(ClassDt);
			var DtExisting = ClassDt.DtExisting;
			var dtspb = DtExisting.payment;
			Dis = 'disabled';
			CodeWr = dtspb[0]['Code'];
			UploadInvoice = jQuery.parseJSON(dtspb[0].Detail[0]['UploadInvoice']);
			UploadInvoice = UploadInvoice[0];
			LinkFileInvoice = '<a href = "'+base_url_js+'fileGetAny/budgeting-spb-'+UploadInvoice+'" target="_blank" class = "Fileexist">File Document</a>';

			UploadTandaTerima = jQuery.parseJSON(dtspb[0].Detail[0]['UploadTandaTerima']);
			UploadTandaTerima = UploadTandaTerima[0];
			LinkUploadTandaTerima = '<a href = "'+base_url_js+'fileGetAny/budgeting-spb-'+UploadTandaTerima+'" target="_blank" class = "Fileexist">File Document</a>';
			var DetailPayment = dtspb[0].Detail;
			CodeSupplier = DetailPayment[0].CodeSupplier;
			Supplier = '<label>'+DetailPayment[0].NamaSupplier+'</label><br>'+DetailPayment[0].Website+'<br>PIC : '+DetailPayment[0].PICName+
						'<br>Alamat : '+DetailPayment[0].Alamat;
			Supplier_ = DetailPayment[0].NamaSupplier;
			NoInvoice = DetailPayment[0].NoInvoice;			
			NoTandaTerima = DetailPayment[0].NoTandaTerima;			
			Datee = DetailPayment[0].Datee;	
			Perihal = DetailPayment[0].Perihal;
			ID_bank = DetailPayment[0].ID_bank;
			NoRekening = DetailPayment[0].No_Rekening;
			IsiInput = 	AddingTable_existing();
			NoIOM = dtspb[0].NoIOM;
		}	
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
								'<div class="input-group" style = "width:450px;">'+
									'<input type="text" class="form-control InpSupplierChoice" readonly value = "'+CodeSupplier+'">'+
									'<span class="input-group-btn">'+
										'<button class="btn btn-default SearchVendor" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
									'</span>'+
								'</div>'+
								'<label class = "lblSupplierChoice">'+Supplier+'</label>'+	
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
								'<input type = "text" class = "form-control NoInvoice" placeholder = "Input No Invoice...." value = "'+NoInvoice+'" '+Dis+'>'+
								'<br>'+
								'<label style="color: red">Upload Invoice</label>'+
								'<input type="file" data-style="fileinput" class="BrowseInvoice" id="BrowseInvoice" accept="image/*,application/pdf" '+Dis+' >'+
								'<div id = "FileInvoice">'+
								LinkFileInvoice+
								'</div>'+
								'<br>'+
								'<label>No Tanda Terima</label>'+
								'<input type = "text" class = "form-control NoTT" placeholder = "Input No Tanda Terima...." value = "'+NoTandaTerima+'" '+Dis+' >'+
								'<br>'+
								'<label style="color: red">Upload Tanda Terima</label>'+
								'<input type="file" data-style="fileinput" class="BrowseTT" id="BrowseTT" accept="image/*,application/pdf">'+
								'<div id = "FileTT"  '+Dis+'>'+
								LinkUploadTandaTerima+
								'</div>'+
							'</td>'+			
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
		                            '<input data-format="yyyy-MM-dd" class="form-control TglSPB" type=" text" readonly="" value = "'+Datee+'">'+
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
								'<input type = "text" class = "form-control Perihal" placeholder ="Input Perihal..." '+Dis+' value = "'+Perihal+'">'+
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
									'<b>'+'<div class = "GetSupplierChoice"></div>'+'</b>'+
								'</td>'+
							'</tr>'+
							'<tr style="height: 50px;">'+
								'<td class="TD1">'+
									'No Rekening'+
								'</td>'+
								'<td>'+
									'<div class= "row">'+
										'<div class="col-xs-5">'+
											OPBank(ID_bank,Dis)+
										'</div>'+
										'<div class="col-xs-1">'+
											'<b>&</b>'+
										'</div>'+
										'<div class="col-xs-5">'+
											'<input type = "text" class = "form-control NoRekening" placeholder="No Rekening" value = "'+NoRekening+'">'+
										'</div>'+
									'</div>'+		
								'</td>'+
							'</tr>'+
						'</thead>'+
					'</table>'+
					'<table class="table borderless">'+	
						'<tbody>'+
							'<tr style ="height:40px;">'+
								'<td colspan = "3" align="center">'+
									'<u><b>PEMBAYARAN : </b></u>'+
								'</td>'+
							'</tr>'+
							'<tr>'+
								'<td style="width:150px;">'+
									'<button type="button" class="btn btn-default btn-add-item"> <i class="icon-plus"></i> Add Item</button>'+
								'</td>'+
								'<td colspan="2">'+
									'<div class="well">'+
										'<label>Budget Remaining</label>'+
										'<div id = "Page_Budget_Remaining">'+
											''+
										'</div>'+
									'</div>'+
								'</td>'+		
							'</tr>'+
							'<tr style = "height:100px;">'+
								'<td colspan="4">'+
									'<table class = "table" id = "tblDetail" style="background-color:#f9f9f9 !important;">'+
										'<thead>'+
											'<tr>'+
												'<th>'+'No'+'</th>'+
												'<th>'+'Budget'+'</th>'+
												'<th>'+'Dibayar Untuk'+'</th>'+
												'<th>'+'Jumlah Rupiah (Include PPN)'+'</th>'+
												'<th>'+'Action'+'</th>'+
											'</tr>'+
										'</thead>'+
										'<tbody>'+
											IsiInput+
										'</tbody>'+
									'</table>'+
								'</td>'+	
							'</tr>'+
						'</tbody>'+
						'<tfoot>'+
							'<tr>'+
								'<td colspan = "3">'+
									'<p class="terbilang" style="font-weight: bold;">Terbilang : [Nominal auto script]</p>'+
								'</td>'+
							'</tr>'+
						'</tfoot>'+
					'</table>'+
					'<div class = "row" style = "margin-top : 10px;">'+
						'<div class = "col-md-6">'+
							'<div class = "form-group" style="width:350px;">'+
								'<label>NoIOM</label>'+
								'<input type="text" class="form-control" id = "NoIOM" value = "'+NoIOM+'">'+
							'</div>'+
							'<div class = "form-group">'+
								'<label>Upload IOM</label>'+
								'<input type="file" data-style="fileinput" class="BrowseFileSD" id="BrowseFileSD" multiple="" accept="image/*,application/pdf">'+
							'</div>'+
						'</div>'+
					'</div>'+
					'<div id="r_signatures"></div>'+
					'<div id = "r_action">'+
						// '<div class="row">'+
						// 	'<div class="col-md-12">'+
						// 		'<div class="pull-right">'+
						// 			'<button class="btn btn-default hide print_page"> <i class="fa fa-print" aria-hidden="true"></i> Print</button> &nbsp'+
						// 			'<button class="btn btn-primary hide btnEditInput"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button> &nbsp'+
						// 			'<button class="btn btn-success submit"> Submit</button>'+
						// 		'</div>'+
						// 	'</div>'+
						// '</div>'+
						btnSPb+
					'</div>'+
				'</div></div></div>';
		se_content.html(html);			

		se_content.find('.datetimepicker').datetimepicker({
			format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
		});

		if (ClassDt.ID_payment =='') {
			se_content.find('.dtbank[tabindex!="-1"]').select2({
			    //allowClear: true
			});
		}
		else
		{
			se_content.find('.SubTotal,.Money_harga').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
			se_content.find('.SubTotal,.Money_harga').maskMoney('mask', '9894');
			$('.SubTotal:last').trigger('keyup');

			var Supporting_documents = jQuery.parseJSON(dtspb[0]['UploadIOM']);
			var htmlSupporting_documents = '';
			var htmlSupporting_documents = '';
			if (Supporting_documents != null) {
				if (Supporting_documents.length > 0) {
					for (var i = 0; i < Supporting_documents.length; i++) {
						htmlSupporting_documents += '<li style = "margin-top : 4px;"><a href = "'+base_url_js+'fileGetAny/budgeting-spb-'+Supporting_documents[i]+'" target="_blank" class = "Fileexist">File '+(i+1)+'</a>&nbsp<button class="btn-xs btn-default btn-delete btn-default-warning btn-custom btn-delete-file" filepath = "budgeting-spb-'+Supporting_documents[i]+'" type="button" idtable = "'+ClassDt.ID_payment+'" table = "db_payment.payment" field = "UploadIOM" typefield = "1" delimiter = "" fieldwhere = "ID"><i class="fa fa-trash" aria-hidden="true"></i></button></li>';
					}
				}
			}
			$('#BrowseFileSD').closest('.col-md-6').append(htmlSupporting_documents);

			if (dtspb[0]['Status'] == -1) {
				var row = $('#tblDetail tbody tr:not(:last)');
				row.find('td').find('input:not(.Detail),select,button:not(.Detail),textarea').prop('disabled',true);
				$('.btn-add-item').prop('disabled',false);
			}
			else
			{
				$('button:not(#Log):not(#btnBackToHome):not(.Detail)').prop('disabled',true);
				$('input,textarea').prop('disabled',true);
			}

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
			makeSignaturesSPB(se_content,JsonStatus);
			if (JsonStatus[0].NIP != sessionNIP) {
				$('#add_approver').remove();
			}
			// __BudgetRemaining();
		}
		MakeButton();
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

	function makepage_status()
	{
		var Dataselected2 = ClassDt.DtExisting;
		var dtspb = Dataselected2.payment;
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

	function MakeButton()
	{
		var dt = ClassDt.RuleAccess;
		if (ClassDt.ID_payment != '') { 
			var DtExisting = ClassDt.DtExisting;
			var dataa = DtExisting['payment'];
			if (dataa[0].Status == -1) {
				var html = '<div class = "col-md-6 col-md-offset-6" align = "right">'+
							'<button class = "btn btn-success submit" ID_payment = "'+ClassDt.ID_payment+'" action = "1">Submit</button>'+
						   '</div>';
				var r_access = dt['access'];
				var rule = dt['rule'];
				// allow access dengan ID_m_userrole: "1"
				var bool = false;
				for (var i = 0; i < r_access.length; i++) {
					var ID_m_userrole = r_access[i].ID_m_userrole;
					// search rule Entry = 1
					for (var j = 0; j < rule.length; j++) {
						var ID_m_userrole_ = rule[j].ID_m_userrole;
						if (ID_m_userrole == ID_m_userrole_) {
							var Entry = rule[j].Entry
							if (Entry == 1) {
								bool = true;
								break;
							}
						}
					}
				}

				if (bool) {
					$('#r_action').html(html);
				}
				else
				{
					// check rule entry
					$('.btn-add-item,input[type="file"],.btn-delete-file').prop('disabled',true);
					$('button:not(#Log):not(#btnBackToHome):not(.Detail)').prop('disabled',true);
					$('input,textarea').prop('disabled',true);
				}
			}
			else if(dataa[0].Status == 1)
			{
				var btn_edit = '';
				var html = '';
				// after submit dan sebelum approval bisa melakukan edit
					var booledit = false;
					if (dt["access"] != undefined) {
						var r_access = dt['access'];
						var rule = dt['rule'];
						for (var i = 0; i < r_access.length; i++) {
							var ID_m_userrole = r_access[i].ID_m_userrole;
							// search rule Entry = 1
							for (var j = 0; j < rule.length; j++) {
								var ID_m_userrole_ = rule[j].ID_m_userrole;
								if (ID_m_userrole == ID_m_userrole_) {
									var Entry = rule[j].Entry
									if (Entry == 1) {
										booledit = true;
										break;
									}
								}
							}
						}
					}

					if (booledit) {
						var JsonStatus = jQuery.parseJSON(dataa[0].JsonStatus);
						var booledit2 = false;
						for (var i = 1; i < JsonStatus.length; i++) {
							if (JsonStatus[i].Status == 1 || JsonStatus[i].Status == '1') {
								booledit2 = true;
								break;
							}
						}

						if (!booledit2) {
							btn_edit = '<button class = "btn btn-primary" id = "btnEditInput"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>&nbsp<button class = "btn btn-success submit" id = "SaveSubmit" ID_payment = "'+ClassDt.ID_payment+'" action = "1" disabled>Submit</button>&nbsp';
						}
					}

				var JsonStatus = jQuery.parseJSON(dataa[0].JsonStatus);
				var bool = false;
				var HierarkiApproval = 0; // for check hierarki approval;
				var NumberOfApproval = 0; // for check hierarki approval;
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

				html = '<div class = "col-md-6 col-md-offset-6" align = "right">'+btn_edit;

				if (bool && HierarkiApproval == NumberOfApproval) { // rule approval
					html += '<button class = "btn btn-primary" id = "Approve" action = "approve" ID_payment = "'+ClassDt.ID_payment+'" approval_number = "'+NumberOfApproval+'">Approve</button>'+
									'&nbsp'+
									'<button class = "btn btn-inverse" id = "Reject" action = "reject" ID_payment = "'+ClassDt.ID_payment+'" approval_number = "'+NumberOfApproval+'">Reject</button>'+
							'</div>';
					
				}

				$("#r_action").html(html);
			}
			else
			{
				if (dataa[0].Status == 2) {
					var html = '<div class = "col-md-12">'+
				   							'<div class = "pull-right">'+
				   								'<button class="btn btn-default" id="pdfprint" ID_payment = "'+ClassDt.ID_payment+'"> <i class = "fa fa-file-pdf-o"></i> Print PDF</button>'+
				   							'</div>'+
				   						'</div>';
				   	$("#r_action").html(html);
				}
				// remove edit approval jika telah approve semua
				$('#add_approver').remove();
			}

			// show button add new pr
			$('.btn-add-new-pr').removeClass('hide');

		}
		else
		{
			var html = '<div class = "col-md-6 col-md-offset-6" align = "right">'+
						'<button class = "btn btn-success submit" id = "SaveSubmit" id_dataa = "" ID_payment = "" action = "1">Submit</button>'+
					   '</div>';
			var r_access = dt['access'];
			var rule = dt['rule'];
			// allow access dengan ID_m_userrole: "1"
			var bool = false;
			for (var i = 0; i < r_access.length; i++) {
				var ID_m_userrole = r_access[i].ID_m_userrole;
				// search rule Entry = 1
				for (var j = 0; j < rule.length; j++) {
					var ID_m_userrole_ = rule[j].ID_m_userrole;
					if (ID_m_userrole == ID_m_userrole_) {
						var Entry = rule[j].Entry
						if (Entry == 1) {
							bool = true;
							break;
						}
					}
				}
			}

			if (bool) {
				$('#r_action').html(html);
			}
			else
			{
				// check rule entry
				$('.btn-add-item,input[type="file"],.btn-delete-file').prop('disabled',true);
			}		   
		}
		
	}

	function AddingTable_existing()
	{
		var html = '';
		var DtExisting = ClassDt.DtExisting;
		var DtExisting = DtExisting.payment;
		var action = '<td><button type="button" class="btn btn-danger btn-delete btn-delete-item"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>';
		var DetailPayment = DtExisting[0].Detail;
		var DetailTypePayment = DetailPayment[0].Detail;
		var Budget =  JSON.parse(localStorage.getItem("PostBudgetDepartment"));
		for (var i = 0; i < DetailTypePayment.length; i++) {
					var ID_budget_left = DetailTypePayment[i]['ID_budget_left'];
					var remaining = 0;
					for (var j = 0; j < Budget.length; j++) {
						var ID_budget_left_ = Budget[j].ID;
						if (ID_budget_left == ID_budget_left_) {
							remaining = parseInt(Budget[j].Value) - parseInt(Budget[j].Using);
							break;
						}
					}

					var DataPostBudget = DetailTypePayment[i]['DataPostBudget'];
					ClassDt.Year = DataPostBudget[0].Year;


					html += '<tr>'+
								'<td>'+(i+1)+'</td>'+
								'<td>'+
									'<div class="input-group">'+
										'<input type="text" class="form-control PostBudgetItem" readonly id_budget_left = "'+ID_budget_left+'" remaining = "'+remaining+'" value = "'+DataPostBudget[0]['RealisasiPostName']+'">'+
										'<span class="input-group-btn">'+
											'<button class="btn btn-default SearchPostBudget" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
										'</span>'+
									'</div>'+
									'<label class = "lblBudget">'+DataPostBudget[0]['RealisasiPostName']+'</label>'+
								'</td>'+
								'<td>'+
									'<input type="text" class="form-control NamaBiaya" value = "'+DetailTypePayment[i]['NamaBiaya']+'">'+
									'<label class = "lblNamaBiaya"></label>'+
								'</td>'+	
								'<td><input type="text" class="form-control SubTotal" value = "'+parseInt(DetailTypePayment[i]['Invoice'])+'"></td>'+
			                	action
			                '</tr>';
		}
		return html;
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


	$(document).off('click', '.btn-add-item').on('click', '.btn-add-item',function(e) {
		// before adding row lock all input in last tr
		var row = $('#tblDetail tbody tr:last');
		row.find('input,select,button:not(.Detail),textarea').prop('disabled',true);
		// row.find('td:eq(4)').find('button').prop('disabled',false);
		AddingTable();
	})

	function AddingTable()
	{
		var action = '<td><button type="button" class="btn btn-danger btn-delete btn-delete-item"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>';
		var html = '<tr>'+
					'<td></td>'+
					'<td>'+
						'<div class="input-group">'+
							'<input type="text" class="form-control PostBudgetItem" readonly>'+
							'<span class="input-group-btn">'+
								'<button class="btn btn-default SearchPostBudget" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
							'</span>'+
						'</div>'+
						'<label class = "lblBudget"></label>'+
					'</td>'+
					'<td>'+
						'<input type="text" class="form-control NamaBiaya">'+
						'<label class = "lblNamaBiaya"></label>'+
					'</td>'+
					'<td><input type="text" class="form-control SubTotal" value = "0" disabled></td>'+
                	action
                '</tr>';
        $('#tblDetail tbody').append(html);
        MakeAutoNumbering();
	}

	function MakeAutoNumbering()
	{
		var no = 1;
		$("#tblDetail tbody tr").each(function(){
			var a = $(this);
			a.find('td:eq(0)').html(no);
			no++;
			$(this).find('.SubTotal').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
			$(this).find('.SubTotal').maskMoney('mask', '9894');  
		})
	}

	$(document).off('click', '.SearchPostBudget').on('click', '.SearchPostBudget',function(e) {
		var ev = $(this);
		var dt = ClassDt.PostBudgetDepartment;
		// show all Budget yang memiliki nilai besar dari 0
		dt = __Selection_BudgetDepartment(dt);
		var html = '';
		html ='<div class = "row">'+
				'<div class = "col-md-12">'+
					'<table id="example_budget" class="table table-bordered display select" cellspacing="0" width="100%">'+
           '<thead>'+
              '<tr>'+
                 '<th>No</th>'+
                 '<th>Post Budget Item</th>'+
                 '<th>Remaining</th>'+
              '</tr>'+
           '</thead>'+
      '</table></div></div>';

		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Select Budget'+'</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});

		var table = $('#example_budget').DataTable({
		      "data" : dt,
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
			             return full.NameHeadAccount+'-'+full.RealisasiPostName;
			         }
			      },
			      {
			         'targets': 2,
			         'render': function (data, type, full, meta){
			             return formatRupiah(full.Value-full.Using);
			         }
			      },
		      ],
		      'createdRow': function( row, data, dataIndex ) {
		      		$(row).attr('CodePost', data.CodePost);
		      		$(row).attr('CodeHeadAccount', data.CodeHeadAccount);
		      		$(row).attr('CodePostRealisasi', data.CodePostRealisasi);
		      		$(row).attr('money', (data.Value - data.Using) );
		      		$(row).attr('id_budget_left', data.ID);
		      		$(row).attr('NameHeadAccount', data.NameHeadAccount);
		      		$(row).attr('RealisasiPostName', data.RealisasiPostName);
		      },
		      // 'order': [[1, 'asc']]
		});

		table.on( 'order.dt search.dt', function () {
		        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		            cell.innerHTML = i+1;
		        } );
		    } ).draw();

		S_Table_example_budget = table;

		S_Table_example_budget.on( 'click', 'tr', function (e) {
			var row = $(this);
			var CodePost = row.attr('CodePost');
			var CodeHeadAccount = row.attr('CodeHeadAccount');
			var CodePostRealisasi = row.attr('CodePostRealisasi');
			var money = row.attr('money');
			var id_budget_left = row.attr('id_budget_left');
			var NameHeadAccount = row.attr('NameHeadAccount');
			var RealisasiPostName = row.attr('RealisasiPostName');
			var fillItem = ev.closest('tr');
			fillItem.find('td:eq(1)').find('.PostBudgetItem').val(RealisasiPostName);
			fillItem.find('td:eq(1)').find('.lblBudget').html(RealisasiPostName);
			fillItem.find('td:eq(1)').find('.PostBudgetItem').attr('id_budget_left',id_budget_left);
			fillItem.find('td:eq(1)').find('.PostBudgetItem').attr('remaining',money);
			fillItem.find('.SubTotal').prop('disabled',false);
			fillItem.find('.SubTotal').trigger('keyup');
			$('#GlobalModalLarge').modal('hide');
		} );
	})

	function __Selection_BudgetDepartment(dt,Min = 0)
	{
		var arr =[];
		for (var i = 0; i < dt.length; i++) {
			var v = parseInt(dt[i].Value) - parseInt(dt[i].Using);
			if (v > Min) {
				arr.push(dt[i]);
			}
		}
		return arr;
	}

	$(document).off('keyup', '.SubTotal').on('keyup', '.SubTotal',function(e) {
		__BudgetRemaining(); 
	})

	function __BudgetRemaining()
	{
		ClassDt.BudgetRemaining = [];
		var Budget = [];
		var Budget =  JSON.parse(localStorage.getItem("PostBudgetDepartment"));
		var GrandTotal = 0;
		
		var BudgetRemaining_arr = [];
		$('.PostBudgetItem').each(function(){
			var arr = [];
			var tr = $(this).closest('tr');
			var id_budget_left =  $(this).attr('id_budget_left');
			var SubTotal = tr.find('.SubTotal').val();
			SubTotal = findAndReplace(SubTotal, ".","");
			GrandTotal = parseInt(GrandTotal) + parseInt(SubTotal);

			var temp = {
				id_budget_left : id_budget_left,
				Using : SubTotal,
			}
			arr.push(temp);

			for (var i = 0; i < Budget.length; i++) {
				var id_budget_left_ = Budget[i].ID;
				var Using = Budget[i].Using;
				var bool = false;
				for (var j = 0; j < arr.length; j++) {
					var id_budget_left = arr[j].id_budget_left;
					if (id_budget_left == id_budget_left_) {
						Using = parseInt(Using) + parseInt(arr[j].Using);
						bool = true;
					}
				}
				Budget[i].Using = Using;
				if (bool) { // jika Post Budget selected
					// check Budget Remaining already exist
					var bool2 = true;
					for (var j = 0; j < BudgetRemaining_arr.length; j++) {
						id_budget_left_re = BudgetRemaining_arr[j].ID;
						if (id_budget_left_ == id_budget_left_re) {
							// Update Using
							BudgetRemaining_arr[j].Using = Budget[i].Using;
							bool2 = false;
							break;
						}
					}

					if (bool2) {
						BudgetRemaining_arr.push(Budget[i]);
					}
					
				}
			}
		})
		
		ClassDt.PostBudgetDepartment = Budget;
		ClassDt.BudgetRemaining = BudgetRemaining_arr;
		MakeTableRemaining();

		// write Grand total
		$('#phtmltotal').html('Total : '+formatRupiah(GrandTotal));
		// make terbilang
		_ajax_terbilang(GrandTotal).then(function(data){
			$('.terbilang').html('<div class = "col-xs-12"><label>Terbilang (Rupiah) : '+data+' Rupiah</label></div>');
		})
	}

	function MakeTableRemaining()
	{
		$("#Page_Budget_Remaining").empty();
		var BudgetRemaining = ClassDt.BudgetRemaining;
		var html = '<div class = "row">'+
						'<div class = "col-md-12">'+
						'<div style="overflow : auto;max-height : 200px;">'+
							'<table class="table table-bordered tableData" id ="tableData3">'+
								'<thead>'+
									'<tr>'+
										'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
										'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Budget</th>'+
										'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Remaining</th>'+
									'</tr>'+
								'</thead><tbody>';
									
		for (var i = 0; i < BudgetRemaining.length; i++) {
			var No = i + 1;
			html += '<tr>'+
						'<td>'+No+'</td>'+
						'<td>'+BudgetRemaining[i].NameHeadAccount+'-'+BudgetRemaining[i].RealisasiPostName+'</td>'+
						'<td>'+formatRupiah(BudgetRemaining[i].Value - BudgetRemaining[i].Using)+'</td>'+
					'</tr>';	
		}

		html += '</tbody>'+
				'</table>'+
				'</div>'+
				'</div></div>';		

		$("#Page_Budget_Remaining").html(html);
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

	$(document).off('click', '.SearchVendor').on('click', '.SearchVendor',function(e) {
		var ev = $(this);
		var html = '';
		html ='<div class = "row">'+
				'<div class = "col-md-12">'+
					'<table id="example_budget" class="table table-bordered display select" cellspacing="0" width="100%">'+
           '<thead>'+
              '<tr>'+
                 '<th>No</th>'+
                 '<th>CategorySupplier</th>'+
                 '<th>Code Supplier</th>'+
                 '<th>Supplier</th>'+
                 '<th width = "40%">'+'DetailItem'+'</th>'+
              '</tr>'+
           '</thead>'+
           '<tbody></tbody>'+
      '</table></div></div>';

		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Select Supplier'+'</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});

		Get_data_supplier().then(function(data){
			S_Table_example_budget.on( 'click', 'tr', function (e) {
				var row = $(this);
				var CodeSupplier = row.find('td:eq(2)').text();
				var Supplier = row.find('td:eq(3)').html();
				console.log(CodeSupplier);
				var fillItem = ev.closest('tr');
				fillItem.find('.InpSupplierChoice').val(CodeSupplier);
				fillItem.find('.lblSupplierChoice').html(Supplier);
				$('#GlobalModalLarge').modal('hide');
			} );
		})
		
	})

	function Get_data_supplier(){
       var def = jQuery.Deferred();
       var data = {
   		   auth : 's3Cr3T-G4N',
       };
       var token = jwt_encode(data,"UAP)(*");
       	var table = $('#example_budget').DataTable({
       		"fixedHeader": true,
       	    "processing": true,
       	    "destroy": true,
       	    "serverSide": true,
       	    "iDisplayLength" : 5,
       	    "ordering" : false,
       	    "ajax":{
       	        url : base_url_js+"rest2/__Supplier_DataIntable_server_side", // json datasource
       	        ordering : false,
       	        type: "post",  // method  , by default get
       	        data : {token : token},
       	        error: function(){  // error handling
       	            $(".example_budget-error").html("");
       	            $("#example_budget").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
       	            $("#example_budget_processing").css("display","none");
       	            def.reject();

       	        },
       	    },
    	    'createdRow': function( row, data, dataIndex ) {
    	    	$( row ).find('td:eq(0)').attr('align','center');
    	    	$(row).find('td:eq(4)').html(data[6]);
	       	    
    	    },
       	    "initComplete": function(settings, json) {
       	        def.resolve(json);
       	    }
       	});
       	S_Table_example_budget = table;
       return def.promise();
	}

	$(document).off('click', '.btn-delete-item').on('click', '.btn-delete-item',function(e) {
		var tr = $(this).closest('tr');
		tr.remove();
		MakeAutoNumbering();
		var row = $('#tblDetail tbody tr:last');
		row.find('input,button').prop('disabled',false);
		row.find('td:eq(4)').find('button').prop('disabled',false);
		__BudgetRemaining(); 
	})

	$(document).off('click', '.submit').on('click', '.submit',function(e) {
		var htmltext = $(this).text();
		if (confirm("Are you sure ?") == true) {
			loading_button('.submit');
			/*
				1.Cek Budget Remaining tidak boleh ada yang kurang dari 0
				2.Validation Inputan
				3.Validation Auth Max Limit
				4.Validation File Upload
			*/

			var ID_payment = ClassDt.ID_payment;
			var action = "1";
			if (validation_input() && __CekBudgetRemaining() ) {
				// console.log('ok validation')
				SubmitData(ID_payment,action,'.submit');
				// $('.submit').prop('disabled',false).html(htmltext);
			}
			else
			{
				$('.submit').prop('disabled',false).html(htmltext);
			}
		}

	})

	function __CekBudgetRemaining()
	{
		var bool = true;
		var dt = ClassDt.BudgetRemaining;
		for (var i = 0; i < dt.length; i++) {
			var v = parseInt(dt[i].Value) - parseInt(dt[i].Using);
			if (v < 0) {
				bool = false;
				toastr.error("Budget Remaining cannot be less than 0",'!!!Error');
				break;
			}
		}

		return bool;
	}

	function validation_input()
	{
		var find = true;
		var Total = 0
		var aa = $(".PostBudgetItem").length;
		if (aa == 0) {
			toastr.error("Post Budget Item is required",'!!!Error');
			find = false;
		}
		else
		{
			$(".PostBudgetItem").each(function(){
				var fillItem = $(this).closest('tr');
				var PostBudgetItem = $(this).val();
				if (PostBudgetItem == '') {
					find = false;
					toastr.error("Post Budget Item is required",'!!!Error');
					return false;
				}

				var NamaBiaya = fillItem.find('.NamaBiaya').val();
				if (NamaBiaya == '') {
					find = false;
					toastr.error("Dibayar untuk is required",'!!!Error');
					return false;
				}

				// find subtotal to check maxlimit
					var SubTotal = fillItem.find('.SubTotal').val();
					SubTotal = findAndReplace(SubTotal, ".","");
					SubTotal = parseInt(SubTotal);
					Total += parseInt(SubTotal);
			})
			var data = {
				CodeSupplier : $('.InpSupplierChoice').val(),
				NoInvoice : $('.NoInvoice').val(),
				NoTandaTerima :$('.NoTT').val(),
				TglSPB :$('.TglSPB').val(),
				Perihal : $('.Perihal').val(),
				NoRekening : $('.NoRekening').val(),
				Pembayaran : Total,
			};
			// console.log(data);
			if (!validation(data) ) {
				find = false;
				return false;
			}

			var MaxLimit = __GetMaxLimit();

			if (Total > MaxLimit) {
				toastr.error("You have authorize Max Limit : "+ formatRupiah(MaxLimit),'!!!Error');
				find = false;
				return false;
			}

			var NoIOM = $('#NoIOM').val();
			if (NoIOM == '') {
				find = false;
				toastr.error("Nomor IOM is required",'!!!Error');
				return false;
			}

			if (ClassDt.ID_payment == '') {
				$(".BrowseFileSD").each(function(){
					var IDFile = $(this).attr('id');
					var ev = $(this);
					if (!file_validation2(ev,'IOM') ) {
					  $(".submit").prop('disabled',true);
					  find = false;
					  return false;
					}
				})

				// Upload Tanda Terima 
				$(".BrowseTT").each(function(){
					var IDFile = $(this).attr('id');
					var ev2 = $(this);
					if (!file_validation2(ev2,'Tanda Terima ') ) {
					  $(".submit").prop('disabled',false);
					  find = false;
					  return false;
					}
				})

				// Upload Invoice 
				$(".BrowseInvoice").each(function(){
					var IDFile = $(this).attr('id');
					var ev2 = $(this);
					if (!file_validation2(ev2,'Invoice ') ) {
					  $(".submit").prop('disabled',false);
					  find = false;
					  return false;
					}
				})
			}

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

	function __GetMaxLimit()
	{
		var MaxLimit = 0;
		var dt = ClassDt.RuleAccess;
		var access = dt.access;
		var rule = dt.rule;
		for (var i = 0; i < access.length; i++) {
			var NIP_ = access[i].NIP;
			if (NIP_ == NIP) {
				var ID_m_userrole = access[i].ID_m_userrole;
				// get BudgetRemaining
				var dt2 = ClassDt.BudgetRemaining;
				var temp = [];
				for (var j = 0; j < dt2.length; j++) {
					var CodePost = dt2[j].CodePost;
					// hitung paling panjang approval jika ada 2 atau lebih dari Budget Category
						var C_ = 0;
						var IndexID = 0;
						for (var k = 0; k < rule.length; k++) {
							var CodePost_ = rule[k].CodePost;
							var ID_m_userrole_ = rule[k].ID_m_userrole;
							var Approved = rule[k].Approved;
							if (CodePost == CodePost_ && ID_m_userrole == ID_m_userrole_) {
								C_++;
								IndexID = k;
							}
						}

						var temp2 = {
							CodePost : CodePost,
							Count : C_,
							MaxLimit : rule[IndexID].MaxLimit,
						}

						temp.push(temp2);
				}

				// ambil nilai temp paling tinggi
				MaxLimit = 0;
				for (var j = 0; j < temp.length; j++) {
					// var Count = temp[j].Count;
					var MaxLimit_ = parseInt(temp[j].MaxLimit);
					for (var k = j+1; k < temp.length; k++) {
						// var Count_ = temp[k].Count;
						var MaxLimit__ = parseInt(temp[k].MaxLimit);
						if (MaxLimit__ >= MaxLimit_) {
							// j = k-1;
							break;
						}
						else
						{
							// j = k - 1;
						}

						j = k;
					}

					MaxLimit = MaxLimit_;
				}
				break;
			}
		}
		return MaxLimit;
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

	function SubmitData(ID_payment,Action,ID_element)
	{
		var Year = ClassDt.Year;
		var Departement = ClassDt.Departement;
		var Perihal = $('#Perihal').val();
		var NoIOM = $('#NoIOM').val();
		var FormInsertDetail = [];
		var form_data = new FormData();
		var PassNumber = 0;
		var Total = 0
		$(".PostBudgetItem").each(function(){
			var ID_budget_left = $(this).attr('id_budget_left');
			var fillItem = $(this).closest('tr');
			var NamaBiaya = fillItem.find('.NamaBiaya').val();
			var SubTotal = fillItem.find('.SubTotal').val();
			SubTotal = findAndReplace(SubTotal, ".","");
			SubTotal = parseInt(SubTotal);
			Total += parseInt(SubTotal);
			 var data = {
			 	ID_budget_left : ID_budget_left,
			 	NamaBiaya : NamaBiaya,
			 	SubTotal : SubTotal,
			 	PassNumber : PassNumber,
			 }
			 var token = jwt_encode(data,"UAP)(*");
			 FormInsertDetail.push(token);
			 PassNumber++
		})
		// console.log(form_data);

		if ( $( '#'+'BrowseFileSD').length ) {
			var UploadFile = $('#'+'BrowseFileSD')[0].files;
			for(var count = 0; count<UploadFile.length; count++)
			{
			 form_data.append("Supporting_documents[]", UploadFile[count]);
			}
		}

		if ( $('.BrowseInvoice').length ) {
			var UploadFile = $('.BrowseInvoice')[0].files;
			form_data.append("UploadInvoice[]", UploadFile[0]);
		}

		if ( $('.BrowseTT').length ) {
			var UploadFile = $('.BrowseTT')[0].files;
			form_data.append("UploadTandaTerima[]", UploadFile[0]);
		}
		
		// return;
		var dataInput = {
			CodeSupplier : $('.InpSupplierChoice').val() ,
			NoInvoice : $('.NoInvoice').val(),
			NoTandaTerima : $('.NoTT').val(),
			Datee : $('.TglSPB').val(),
			Perihal : $('.Perihal').val(),
			No_Rekening : $('.NoRekening').val(),
			ID_bank : $('.dtbank option:selected').val(),
			Invoice : Total,
			TypeInvoice : 'Pembayaran 1',
		}

		var token = jwt_encode(dataInput,"UAP)(*");
		form_data.append('dataInput',token);

		var token = jwt_encode(FormInsertDetail,"UAP)(*");
		form_data.append('token',token);

		token = jwt_encode(ID_payment,"UAP)(*");
		form_data.append('ID_payment',token);

		token = jwt_encode(NoIOM,"UAP)(*");
		form_data.append('NoIOM',token);

		form_data.append('Action',Action);

		token = jwt_encode(Year,"UAP)(*");
		form_data.append('Year',token);

		token = jwt_encode(Departement,"UAP)(*");
		form_data.append('Departement',token);

		token = jwt_encode(ClassDt.BudgetRemaining,"UAP)(*");
		form_data.append('BudgetRemaining',token);

		var BudgetLeft_awal = JSON.parse(localStorage.getItem("PostBudgetDepartment_awal"));
		token = jwt_encode(BudgetLeft_awal,"UAP)(*");
		form_data.append('BudgetLeft_awal',token);

		// var url = base_url_js + "budgeting/submit_pettycash_user"
		var url = base_url_js + "budgeting/submit_spb_user"
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
		    switch (Action)
		    {
		       case "1":
		       		var St_error = data['St_error'];
		       		var msg = data['msg'];
		       		if (St_error == 0) {
		       			if (data['BudgetChange'] == 1) {
		       				ClassDt.ID_payment = data['ID_payment'];
		       				ReloadBudgetRemaining().then(function(data2){
		       					LoadFirstLoad();
		       				})
		       			}
		       			toastr.error(msg,'!!!Failed');
		       		}
		       		else
		       		{
		       			if (data['BudgetChange'] == 1) { // alert Budget Remaining telah di update oleh transaksi lain
		       				toastr.info('Budget Remaining already have updated by another person.Please check !!!');
		       				loadingStart();
		       				if (ClassDt.ID_payment != '') {
		       					// load lagi Budget remaining
		       						var ID_payment = ClassDt.ID_payment;
		       						var url = base_url_js+'budgeting/GetData_payment';
		       						// var url = base_url_js+'budgeting/GetDataPR';
		       						var data = {
		       						    ID_payment : ID_payment,
		       						};
		       						var token = jwt_encode(data,"UAP)(*");
		       						$.post(url,{ token:token },function (resultJson) {
		       							var response = jQuery.parseJSON(resultJson);
		       							// Load Budget Department
		       							var arr_data= response['data'];
		       							var Year = arr_data[0]['Year'];
		       							ClassDt.NmDepartement_Existing =  arr_data[0]['NameDepartement'];
		       							var Departement = arr_data[0]['Departement'];
		       							var url = base_url_js+"budgeting/detail_budgeting_remaining";
		       							var data = {
		       									    Year : Year,
		       										Departement : Departement,
		       									};
		       							var token = jwt_encode(data,'UAP)(*');
		       							$.post(url,{token:token},function (resultJson) {
		       								var response2 = jQuery.parseJSON(resultJson);
		       								Make_PostBudgetDepartment_existing(response2.data);
		       								ClassDt.PostBudgetDepartment_awal = response2.data;
		       								localStorage.setItem("PostBudgetDepartment_awal", JSON.stringify(ClassDt.PostBudgetDepartment_awal));
		       								__BudgetRemaining(); 
		       								loadingEnd(1500);
		       							}).fail(function() {
		       							  toastr.info('No Result Data'); 
		       							}).always(function() {
		       							                
		       							});

		       						}).fail(function() {
		       						  toastr.info('No Result Data'); 
		       						}).always(function() {
		       						                
		       						});			       					
		       				}
		       				else
		       				{
		       					// load lagi Budget remaining
		       					var Year = ClassDt.Year;
		       					var Departement = ClassDt.Departement;
		       					var url = base_url_js+"budgeting/detail_budgeting_remaining";
		       					var data = {
		       							    Year : Year,
		       								Departement : Departement,
		       							};
		       					var token = jwt_encode(data,'UAP)(*');
		       					$.post(url,{token:token},function (resultJson) {
		       						var response = jQuery.parseJSON(resultJson);
		       						ClassDt.PostBudgetDepartment = response.data;
		       						ClassDt.PostBudgetDepartment_awal = response.data;
		       						localStorage.setItem("PostBudgetDepartment", JSON.stringify(ClassDt.PostBudgetDepartment));
		       						localStorage.setItem("PostBudgetDepartment_awal", JSON.stringify(ClassDt.PostBudgetDepartment_awal));
		       						__BudgetRemaining(); 
		       						loadingEnd(1500);
		       					}).fail(function() {
		       					  toastr.info('No Result Data'); 
		       					}).always(function() {
		       					                
		       					});
		       				}

		       			}
		       			else
		       			{
		       				// success
		       				var Status = NameStatus(data['StatusPayment']);
		       				$('#page_status').html('Status : '+Status);
		       				// Update Variable ClassDt
		       				ClassDt.ID_payment = data['ID_payment'];
		       				ReloadBudgetRemaining().then(function(data2){
		       					LoadFirstLoad();
		       				})
		       			}

		       		}
		       		$('.submit').prop('disabled',false).html('Submit');

		       		break;
		       default: 
		           alert('Default case');
		    }

		  },
		  error: function (data) {
		    toastr.error("Connection Error, Please try again", 'Error!!');
		    var nmbtn = '';
		    if (ID_element == '.submit') {
		    	nmbtn = 'Submit';
		    }
		    else if(ID_element == '.submit')
		    {
		    	nmbtn = 'Submit';
		    }
		    $(ID_element).prop('disabled',false).html(nmbtn);
		  }
		})
	}

	function ReloadBudgetRemaining()
	{
		// load lagi Budget remaining
		var def = jQuery.Deferred();
		var Year = ClassDt.Year;
		var Departement = ClassDt.Departement;
		var url = base_url_js+"budgeting/detail_budgeting_remaining";
		var data = {
				    Year : Year,
					Departement : Departement,
				};
		var token = jwt_encode(data,'UAP)(*');
		$.post(url,{token:token},function (resultJson) {
			var response = jQuery.parseJSON(resultJson);
			ClassDt.PostBudgetDepartment = response.data;
			ClassDt.PostBudgetDepartment_awal = response.data;
			localStorage.setItem("PostBudgetDepartment", JSON.stringify(ClassDt.PostBudgetDepartment));
			localStorage.setItem("PostBudgetDepartment_awal", JSON.stringify(ClassDt.PostBudgetDepartment_awal));
			__BudgetRemaining(); 
			loadingEnd(1500);
			def.resolve(response);
		}).fail(function() {
		  toastr.info('No Result Data');
		  def.reject(); 
		}).always(function() {
		                
		});
		return def.promise();
	}

	function NameStatus(Status)
	{
		switch (Status)
	    {
	       case "1":
	       case 1:
	       	Status = 'Awaiting Approval';
	       break;
	       case "2":
	       case 2:
	       	Status = 'Done';
	       break;
	       case "-1":
	       case -1:
	       	Status = 'Reject';
	       break;
	       case "4":
	       case 4:
	       	Status = 'Cancel';
	       break;
	       default: 
	           alert('No Status');
	    }

	    return Status;
	}

	function Make_PostBudgetDepartment_existing()
	{
		/*
			Note : 
			Pengembalian Post Budget using ke awal sebelum pr tercreate
		*/
		arr_budget_departement = JSON.parse(localStorage.getItem("PostBudgetDepartment_awal"));
		var arr = [];
		var DtExisting = ClassDt.DtExisting;
		var DtExisting = DtExisting.payment;
		var DetailPayment = DtExisting[0].Detail;
		var DetailTypePayment = DetailPayment[0].Detail;
		var arr_detail = DetailTypePayment;
		for (var i = 0; i < arr_budget_departement.length; i++) {
			var CodePostRealisasi = arr_budget_departement[i]['CodePostRealisasi'];
			var Using = arr_budget_departement[i]['Using'];
			var Value = arr_budget_departement[i]['Value'];
			// console.log(arr_budget_departement[i]);	
			// console.log(arr_detail);	
			// console.log(Using+' Start');	

			for (var j = 0; j < arr_detail.length; j++) {
				var CodePostRealisasi_ = arr_detail[j].DataPostBudget[0].CodePostRealisasi;
				if (CodePostRealisasi_ == CodePostRealisasi) {
					var SubTotal = parseInt(arr_detail[j].Invoice);
					var Cost1 = SubTotal;
					// console.log(Using+' Before');	
					Using = parseInt(Using) - Cost1;
					// console.log(Using+' After');	
					arr_budget_departement[i]['Using'] = Using;

					//break;
				}
			}

		}
		ClassDt.PostBudgetDepartment = arr_budget_departement;
		localStorage.setItem("PostBudgetDepartment", JSON.stringify(ClassDt.PostBudgetDepartment));
	}

	$(document).off('click', '#btnEditInput').on('click', '#btnEditInput',function(e) {
		var row = $('#tblDetail tbody tr:last');
		row.find('td').find('input:not(.PostBudgetItem),button').prop('disabled',false);
		$('.submit').prop('disabled',false);
		$('.btn-add-item,input[type="file"],.btn-delete-file').prop('disabled',false);
		$('#NoIOM').prop('disabled',false);
		$('#Perihal').prop('disabled',false);
		$(this).remove();
	})

	$(document).off('click', '.btn_circulation_sheet').on('click', '.btn_circulation_sheet',function(e) {
	    var url = base_url_js+'rest2/__show_info_payment';
	    var ID_payment = ClassDt.ID_payment;
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

	$(document).off('click', '#Approve').on('click', '#Approve',function(e) {
		if (confirm('Are you sure ?')) {
			loading_button('#Approve');
			var ID_payment = $(this).attr('ID_payment');
			var approval_number = $(this).attr('approval_number');
			// var url = base_url_js + 'rest/__approve_pr';
			var url = base_url_js + 'rest/__approve_payment_user';
			var data = {
				ID_payment : ID_payment,
				approval_number : approval_number,
				NIP : NIP,
				action : 'approve',
				auth : 's3Cr3T-G4N',
				DtExisting : ClassDt.DtExisting,
			}

			var token = jwt_encode(data,"UAP)(*");
			$.post(url,{ token:token },function (resultJson) {
				if (resultJson['Reload'] == 1) {
					toastr.info(resultJson['msg']);
					LoadFirstLoad();
				}
				else
				{
					LoadFirstLoad();
					toastr.success('Approve Successful');
					$('#Approve').prop('disabled',false).html('<i class="fa fa-handshake-o"> </i> Approve');
				}
			}).fail(function() {
			  $('#Approve').prop('disabled',false).html('<i class="fa fa-handshake-o"> </i> Approve');
			  // toastr.info('No Result Data');
			  toastr.error('The Database connection error, please try again', 'Failed!!');
			}).always(function() {
			    //$('#Approve').prop('disabled',false).html('<i class="fa fa-handshake-o"> </i> Approve');
			});
		}

	})

	$(document).off('click', '#Reject').on('click', '#Reject',function(e) {
		if (confirm('Are you sure ?')) {
			var ID_payment = $(this).attr('ID_payment');
			var approval_number = $(this).attr('approval_number');
			// show modal insert reason
			$('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Please Input Reason ! </b> <br>' +
			    '<input type = "text" class = "form-group" id ="NoteDel" style="margin: 0px 0px 15px; height: 30px; width: 329px;" maxlength="100"><br>'+
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

				var url = base_url_js + 'rest/__approve_payment_user';
				var data = {
					ID_payment : ID_payment,
					approval_number : approval_number,
					NIP : NIP,
					action : 'reject',
					auth : 's3Cr3T-G4N',
					NoteDel : NoteDel,
					DtExisting : ClassDt.DtExisting,
				}

				var token = jwt_encode(data,"UAP)(*");
				$.post(url,{ token:token },function (resultJson) {
					// if (resultJson == '') {
					// 	LoadFirstLoad();
					// }
					// else
					// {
					// 	// $('#reject').prop('disabled',false).html('<i class="fa fa-handshake-o"> </i> Approve');
					// }
					if (resultJson['Reload'] == 1) {
						toastr.info(resultJson['msg']);
						LoadFirstLoad();
					}
					else
					{
						LoadFirstLoad();
						toastr.success('Reject Successful');
						$('#Approve').prop('disabled',false).html('<i class="fa fa-handshake-o"> </i> Approve');
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

	$(document).off('click', '#pdfprint').on('click', '#pdfprint',function(e) {
		var ID_payment =ClassDt.ID_payment;
		var DataPayment = ClassDt.DtExisting;
		var dt = DataPayment.payment;
		var data = {
		  ID_payment : ID_payment,
		  TypePay : dt[0].Type,
		  DataPayment : DataPayment,
		}
		var token = jwt_encode(data,"UAP)(*");
		var url = base_url_js+'save2pdf/print/payment_user';
		
		FormSubmitAuto(url, 'POST', [
		    { name: 'token', value: token },
		]);
	})
</script>
