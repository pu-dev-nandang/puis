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
		<div><a href="<?php echo base_url().'budgeting_menu/pembayaran/cashadvance' ?>" class = "btn btn-warning"> <i class="fa fa-arrow-circle-left"></i> Back to List</a></div>
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
		SubMenu.find('li[segment3="cashadvance"]').addClass('current');
		
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
				makeDomCash_Advance(ID_payment,se_content)
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

	function makeDomCash_Advance(ID_payment,se_content)
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
		var htmlAdd ='<div class = "CAAdd">'
		var EndhtmlAdd = '</div>';
		Invoice = parseInt(dtspb[0].Detail[0].Invoice);
		TypePay = dtspb[0].Detail[0].TypePay;
		ID_bank = dtspb[0].Detail[0].ID_bank;
		NoRekening = dtspb[0].Detail[0].No_Rekening;
		Nama_Penerima = dtspb[0].Detail[0].Nama_Penerima;
		Date_Needed = dtspb[0].Detail[0].Date_Needed;
		Perihal = dtspb[0].Detail[0].Perihal;
		Dis = 'disabled';
		var btn_hide = '';
		var btn_hide_print = 'hide';
		var btn_hide_submit = '';
		Status = dtspb[0]['Status'];
		if (Status == 2) {
			btn_hide_print = '';
			btn_hide = 'hide';
			btn_hide_submit = 'hide';
		}

		// hitung Left PO
		var InvoiceleftPO = parseInt(InvoicePO);
		var c = 0;
		for (var i = 0; i < data.dtspb.length; i++) {
			if (ID_payment == data.dtspb[i].ID && i > 0) {
				if (data.dtspb[i]['Detail'][0].Invoice != null && data.dtspb[i]['Detail'][0].Invoice != 'null') {
					InvoiceleftPO -= parseInt(data.dtspb[parseInt(i) - 1]['Detail'][0].Invoice);
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

		html += htmlAdd+'<div class = "row"><div class="col-xs-12 page_status"></div><div class = "col-xs-12"><div align="center"><h2>CASH ADVANCE FORM</h2></div>'+
					'<hr style="height:2px;border:none;color:#333;background-color:#333;margin-top: -3px;">'+
					'<label>Mohon dapat diberikan Cash Advance dengan perincian sebagai berikut:</label>'+
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
								'<select class = "form-control TypePay" disabled>'+
									'<option value = "Cash" selected>Cash</option>'+ 
								'</select>'+	
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
		                            '<input data-format="yyyy-MM-dd" class="form-control TglCA" type=" text" readonly="" value = "'+Date_Needed+'">'+
		                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
		                		'</div>'+
							'</td>	'+			
						'</tr>'+
					'</tbody>'+
					'</table>'+
					'<div id="r_signatures"></div>'+
					'<div id = "r_action">'+
						'<div class="row">'+
							'<div class="col-md-12">'+
								'<div class="pull-right">'+
									'<button class="btn btn-default '+btn_hide_print+' print_page"> <i class="fa fa-print" aria-hidden="true"></i> Print</button> &nbsp'+
									'<button class="btn btn-primary '+btn_hide+'  btnEditInputCA"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button> &nbsp'+
									'<button class="btn btn-success submitCA '+btn_hide_submit+'" '+Dis+'> Submit</button>'+
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
	}

	function makeAction()
	{
		var Dataselected2 = ClassDt.DataPaymentSelected;
		var dtspb = Dataselected2.dtspb;

		var html = '<div class = "row noPrint"><div class = "col-xs-12"></div></div>'; 
		var btn_edit = '<button class="btn btn-primary btnEditInputCA" status="'+dtspb[0]['Status']+'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>';
		var btn_submit = '<button class="btn btn-success submitCA" disabled> Submit</button>';
		
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

	$(document).off('click', '.btnEditInputCA').on('click', '.btnEditInputCA',function(e) {
		var Status = $(this).attr('status');
		if (Status != 2) {
			var ev2 = $(this).closest('#pageContent');
			ev2.find('input').not('.TglCA').prop('disabled',false);
			ev2.find('button').prop('disabled',false);
			//ev2.find('select').prop('disabled',false);
			// ev2.find('.dtbank[tabindex!="-1"]').select2({
			//     //allowClear: true
			// });
			$(this).remove();
			ev2.find('.TypePay').trigger('change');
		}
		else
		{
			toastr.info('Data Bank Advance telah approve, tidak bisa edit');
		}	
	})

	$(document).off('click', '.submitCA').on('click', '.submitCA',function(e) {
		// validation
		var ev = $(this).closest('#pageContent');
		var action = 'edit';
		if (confirm('Are you sure?')) {
			var validation = validation_input_ca(ev);
			if (validation) {
				submitCA('.submitCA',ev,action);
			}
		}	
	})

	function validation_input_ca(ev)
	{
		var find = true;
		var data = {
			Biaya : ev.find('.Money_Pembayaran').val(),
			TypePay : ev.find('.TypePay').val(),
			Date_Needed : ev.find('.TglCA').val(),
		};
		if (validationCA(data) ) {
			
		}
		else
		{
			find = false;
		}
		
		return find;
	}

	function validationCA(arr)
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
	      case  "Date_Needed" :
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

	function submitCA(elementbtn,ev,action)
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
		var No_Rekening = '';
		var ID_bank = ev.find('.dtbank option:selected').val();
		if (TypePay == 'Cash') {
			ID_bank = 0;
		}
		
		var Nama_Penerima = sessionName;
		var Date_Needed = ev.find('.TglCA').val();

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

		var url = base_url_js + "budgeting/submitca"
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
