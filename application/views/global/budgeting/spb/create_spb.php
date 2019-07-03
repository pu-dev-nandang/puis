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
		<div class="well" id ="page_spb">
			<!-- <div align="center"><h2>Surat Permohonan Pembayaran</h2></div>
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
			</div> -->
		</div>
	</div>
</div>
<script type="text/javascript">
	var IDDepartementPUBudget = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";
	var ClassDt = {
		Dt_selection : [],
		ThisTableSelect : '',
		Dt_ChooseSelectPO : [],
		action_mode : '<?php echo $action_mode ?>',
		SPBCode : '<?php echo $SPBCode ?>',
		SPBData : [],
		G_data_bank : <?php echo json_encode($G_data_bank) ?>,
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
					 				'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Invoice</th>'+
					 				'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Left</th>'+
					 			'</tr>'+
					 		'<thead>'+
					 		'<tbody id="dataRow"></tbody>'+
		        		'</table>'+
		        		'</div>'+
		        	 '</div>'+
		        	'</div>';

		    return html;    	 				
		},	
	};

	$(document).ready(function() {
		$('#page_po_list').html(ClassDt.htmlPage_po_list);
		Get_data_po().then(function(data){
			$('.C_radio_pr:first').prop('checked',true);
			$('.C_radio_pr:first').trigger('change');
			loadingEnd(500);
		})

		
	}); // exit document Function

	function Get_data_po(){
	   var action_edit = (ClassDt.POCode == '') ? '': 'edit';
       var def = jQuery.Deferred();
       var data = {
           IDDepartementPUBudget : IDDepartementPUBudget,
           sessionNIP : sessionNIP,
   		   auth : 's3Cr3T-G4N',
   		   action : 'forspb',
       };
       var token = jwt_encode(data,"UAP)(*");
       	var table = $('#tableData_po').DataTable({
       		"fixedHeader": true,
       	    "processing": true,
       	    "destroy": true,
       	    "serverSide": true,
       	    "iDisplayLength" : 10,
       	    "ordering" : false,
       	    "ajax":{
       	        url : base_url_js+"rest2/__get_data_po/2", // json datasource
       	        ordering : false,
       	        type: "post",  // method  , by default get
       	        data : {token : token},
       	        error: function(){  // error handling
       	            $(".employee-grid-error").html("");
       	            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
       	            $("#employee-grid_processing").css("display","none");
       	            def.reject();

       	        },
       	    },
    	    'createdRow': function( row, data, dataIndex ) {
    	    	$( row ).find('td:eq(0)').attr('align','center');
       	    	var code_url = findAndReplace(data[1],'/','-');
              	var ListPR = data[parseInt(data.length) - 1];
              	var dtinvoice = ListPR[1];
              	var PRHTML = '';
	              // for (var i = 0; i < ListPR.length; i++) {
	              //   PRHTML += '<li>'+ListPR[i]+'</li>';
	              // }

	              PRHTML += '<li>'+ListPR[0]+'</li>';

	              	var input_radio = '<input type="radio" name="optradio" code="'+data[1]+'" class = "C_radio_pr" InvoicePO = "'+dtinvoice.InvoicePO+'" InvoiceLeftPO = "'+dtinvoice.InvoiceLeftPO+'" supplier = "'+data[3]+'">';
	       	    	if (data[2] == 'PO') {
	       	    		$( row ).find('td:eq(1)').html('<div align = "left">'+input_radio+' &nbsp <a href="javascript:void(0)" code="'+data[1]+'">'+data[1]+'</a><br>Created : '+data[parseInt(data.length) - 2]+'<br>'+PRHTML+'</div>');
	       	    	}
	       	    	else
	       	    	{
	       	    		$( row ).find('td:eq(1)').html('<div align = "left">'+input_radio+' &nbsp <a href="javascript:void(0)" code="'+data[1]+'">'+data[1]+'</a><br>Created : '+data[parseInt(data.length) - 2]+'<br>'+PRHTML+'</div>');
	       	    	}

	       	    	$( row ).find('td:eq(4)').html('<div align = "left">'+formatRupiah(dtinvoice.InvoicePO)+'</div>');
	       	    	$( row ).find('td:eq(5)').html('<div align = "left">'+formatRupiah(dtinvoice.InvoiceLeftPO)+'</div>');
    	    },
       	    "initComplete": function(settings, json) {
       	        def.resolve(json);
       	    }
       	});
       return def.promise();
	}


	$(document).off('change', '.C_radio_pr:checked').on('change', '.C_radio_pr:checked',function(e) {
		var Code = $(this).attr('code');
		var InvoicePO = $(this).attr('invoicepo');
		var InvoiceleftPO = $(this).attr('invoiceleftpo');
		var Supplier = $(this).attr('supplier');
		Get_detail_by_SPB(Code).then(function(data){
			if (data.msg == '') {
				MakeDom_spb(Code,InvoicePO,InvoiceleftPO,Supplier,data);
			}
			else
			{
				$('#page_spb').html('<p style = "color:red">'+data.msg+'</p>');
			}
			
		})
		
	})

	function Get_detail_by_SPB(Code){
       var def = jQuery.Deferred();
       var url = base_url_js + 'rest2/__spb_for_po';
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

	function OPBank(IDselected = null)
	{
		var h = '';
		var dtbank = ClassDt.G_data_bank;
		h = '<select class = " form-control dtbank" style = "width : 80%">';
			var temp = ['Read','Write'];
			for (var i = 0; i < dtbank.length; i++) {
				var selected = (IDselected == dtbank[i].ID) ? 'selected' : '';
				h += '<option value = "'+dtbank[i].ID+'" '+selected+' >'+dtbank[i].Name+'</option>';
			}
		h += '</select>';	

		return h;
	}


	function MakeDom_spb(Code,InvoicePO,InvoiceleftPO,Supplier,data)
	{
		var html = '';
		Supplier = Supplier.split('||');
		Supplier = Supplier[1].trim();
		html += '<div align="center"><h2>Surat Permohonan Pembayaran</h2></div>'+
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
									formatRupiah(InvoiceleftPO)+
								'</td>'+
								'<td>'+
									'(include PPN)'+
								'</td>'+
							'</tr>'+
							'<tr>'+
								'<td class="TD1">'+
									'<label class="TypePembayaran" type = "Pembayaran'+( parseInt(data.dt.length)+1 )+'"><b>Pembayaran '+( parseInt(data.dt.length)+1 )+'</b></label>'+
								'</td>'+
								'<td class="TD2">'+
									'='+
								'</td>'+
								'<td>'+
									'<input type = "text" class = "form-control Money_Pembayaran" invoiceleftpo="'+InvoiceleftPO+'">'+ 
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
									'<button class="btn btn-default hide" id="print_page"> <i class="fa fa-print" aria-hidden="true"></i> Print</button> &nbsp'+
									'<button class="btn btn-primary hide" id="btnEditInput"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button> &nbsp'+
									'<button class="btn btn-success" id = "submit"> Submit</button>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>';
		$('#page_spb').html(html);			
		$('.Money_Pembayaran').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		$('.Money_Pembayaran').maskMoney('mask', '9894');

		$('.datetimepicker').datetimepicker({
			format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
		});

		$('.dtbank[tabindex!="-1"]').select2({
		    //allowClear: true
		});
	}

	$(document).off('keyup keydown', '.Money_Pembayaran').on('keyup keydown', '.Money_Pembayaran',function(e) {
		var v = $(this).val();
		v = findAndReplace(v, ".","");
		var InvoiceleftPO = $(this).attr('invoiceleftpo');
		var n = InvoiceleftPO.indexOf(".");
		InvoiceleftPO = InvoiceleftPO.substring(0, n);
		var sisa = parseInt(InvoiceleftPO) - parseInt(v);
		if (sisa < 0) {
			toastr.info('Pembayaran melebihi harga');
			v = InvoiceleftPO;
			$(this).val(v);
			$(this).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
			$(this).maskMoney('mask', '9894');
			sisa = 0;
		}
		$('.Sisa_Pembayaran').html(formatRupiah(sisa));
		// ajax terbilang
		setTimeout(function () {
		    _ajax_terbilang(v).then(function(data){
		    	$('.terbilang').html('Terbilang (Rupiah) : '+data+' Rupiah');
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

	$(document).off('click', '#submit').on('click', '#submit',function(e) {
		// validation
		var validation = validation_input();
		if (validation) {
			SubmitSPB('#submit');
		}

	})

	function validation_input()
	{
		var find = true;
		var data = {
			NoInvoice : $('.NoInvoice').val(),
			NoTandaTerima : $('.NoTT').val(),
			TglSPB : $('.TglSPB').val(),
			Perihal : $('.Perihal').val(),
			NoRekening : $('.NoRekening').val(),
			Pembayaran : $('.Money_Pembayaran').val(),
		};
		if (validation(data) ) {
			// Upload Tanda Terima 
			$(".BrowseTT").each(function(){
				var IDFile = $(this).attr('id');
				var ev = $(this);
				if (!file_validation2(ev,'Tanda Terima ') ) {
				  $("#submit").prop('disabled',false);
				  find = false;
				  return false;
				}
			})

			// Upload Invoice 
			$(".BrowseInvoice").each(function(){
				var IDFile = $(this).attr('id');
				var ev = $(this);
				if (!file_validation2(ev,'Invoice ') ) {
				  $("#submit").prop('disabled',false);
				  find = false;
				  return false;
				}
			})
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

	function submit(elementbtn,action="add")
	{
		var Code_po_create = $('.C_radio_pr:checked').attr('code');
		var Departement = IDDepartementPUBudget;
		var ID_budget_left = 0;
		var form_data = new FormData();

		var UploadFile = $('.BrowseInvoice')[0].files;
		form_data.append("UploadInvoice", UploadFile[0]);

		var UploadFile = $('.BrowseTT')[0].files;
		form_data.append("UploadTandaTerima", UploadFile[0]);

		var NoInvoice = $('.NoInvoice').val();
		var NoTandaTerima = $('.NoTT').val();
		var Datee = $('.TglSPB').val();
		var Perihal = $('.Perihal').val();
		var No_Rekening = $('.NoRekening').val();
		var ID_bank = $('.dtbank option:selected').val();
		var Invoice = $('.Money_Pembayaran').val();
		Invoice = findAndReplace(Invoice, ".","");
		var TypeInvoice = $('.TypePembayaran').attr('type');

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
		};

		var token = jwt_encode(data,"UAP)(*");
		form_data.append('token',token);

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
		    

		  },
		  error: function (data) {
		    toastr.error("Connection Error, Please try again", 'Error!!');
		    nmbtn = 'Submit';
		    $(elementbtn).prop('disabled',false).html(nmbtn);
		  }
		})
	}
</script>