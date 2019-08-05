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
	var DivSessionName = '';
	<?php 
	    $d = $this->session->userdata('IDDepartementPUBudget');
	    $d = explode('.', $d);
	 ?>
	<?php if ($d == 'AC'): ?>
	     DivSessionName = '<?php echo $this->session->userdata('prodi_active') ?>';
	<?php elseif($d == 'FT'): ?> 
	    DivSessionName = '<?php echo $this->session->userdata('faculty_active') ?>';   
	<?php else: ?>
	     <?php $P = $this->session->userdata('PositionMain'); 
	            $P = $P['Division'];
	     ?>
	     DivSessionName = '<?php echo $P ?>'; 
	<?php endif ?> 
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
	    loadFirst();
	}); // exit document Function

	function loadFirst()
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
		var TypeInvoice = 'Pembayaran';
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
								'<span color = "red">auto by system</span>'+
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
									'<input type="text" class="form-control InpSupplierChoice" readonly>'+
									'<span class="input-group-btn">'+
										'<button class="btn btn-default SearchPostBudget" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
									'</span>'+
								'</div>'+
								'<label class = "lblSupplierChoice">'+'</label>'+	
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
								'<input type = "text" class = "form-control NoInvoice" placeholder = "Input No Invoice....">'+
								'<br>'+
								'<label style="color: red">Upload Invoice</label>'+
								'<input type="file" data-style="fileinput" class="BrowseInvoice" id="BrowseInvoice" accept="image/*,application/pdf">'+
								'<div id = "FileInvoice">'+
								'	'+
								'</div>'+
								'<br>'+
								'<label>No Tanda Terima</label>'+
								'<input type = "text" class = "form-control NoTT" placeholder = "Input No Tanda Terima....">'+
								'<br>'+
								'<label style="color: red">Upload Tanda Terima</label>'+
								'<input type="file" data-style="fileinput" class="BrowseTT" id="BrowseTT" accept="image/*,application/pdf">'+
								'<div id = "FileTT">'+
								'	'+
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
		                            '<input data-format="yyyy-MM-dd" class="form-control TglSPB" type=" text" readonly="" value = "">'+
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
								'<input type = "text" class = "form-control Perihal" placeholder ="Input Perihal...">'+
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
											OPBank()+
										'</div>'+
										'<div class="col-xs-1">'+
											'<b>&</b>'+
										'</div>'+
										'<div class="col-xs-5">'+
											'<input type = "text" class = "form-control NoRekening" placeholder="No Rekening">'+
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
									'<input type = "text" class = "form-control Money_harga">'+ 
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
									'<input type = "text" class = "form-control Money_Pembayaran">'+ 
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
									'<button class="btn btn-default hide print_page"> <i class="fa fa-print" aria-hidden="true"></i> Print</button> &nbsp'+
									'<button class="btn btn-primary hide btnEditInput"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button> &nbsp'+
									'<button class="btn btn-success submit"> Submit</button>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div></div></div>';
		se_content.html(html);			
		se_content.find('.Money_Pembayaran,.Money_harga').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		se_content.find('.Money_Pembayaran,.Money_harga').maskMoney('mask', '9894');

		se_content.find('.datetimepicker').datetimepicker({
			format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
		});

		se_content.find('.dtbank[tabindex!="-1"]').select2({
		    //allowClear: true
		});

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
</script>
