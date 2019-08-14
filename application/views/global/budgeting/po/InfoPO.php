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
		<?php if ($this->session->userdata('IDdepartementNavigation') == 4): ?>
			<div><a href="<?php echo base_url().'purchasing/transaction/po/list' ?>" class = "btn btn-warning"> <i class="fa fa-arrow-circle-left"></i> Back to List</a></div>
		<?php else: ?>
			<div>
				<a href="<?php echo base_url().'budgeting_po' ?>" class = "btn btn-warning"> <i class="fa fa-arrow-circle-left"></i> Back to List</a>
			</div>
		<?php endif ?>
			<?php if ($bool): ?>
		
			<?php endif ?>
	</div>
	<?php if ($bool): ?>
	<?php if ($this->session->userdata('IDdepartementNavigation') == 4): ?>
		<div class="col-xs-2 col-md-offset-8">
			<div class="toolbar no-padding pull-right" style = "margin-left : 10px;">
	            <span data-smt="" class="btn btn-add-new-po" page = "purchasing/transaction/po/list/open">
	                <i class="icon-plus"></i> New PO / SPK
	           </span>
	        </div>
		</div>
	<?php endif ?>
	<?php endif ?>
</div>
<div id="DocPenawaran" class="row noPrint"></div>
<div class="row" style="margin-top: 2px;">
	<div class="col-xs-12">
		<table class="table borderless">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align :center">
						<p><h3>Purchase Order</h3></p>
						<p style="margin-top: -10px;"><?php echo $Code ?></p>
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
		G_pay_type : <?php echo json_encode($G_pay_type) ?>,
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
		var PICPU = '';
		if (JsonStatus.length > 0) {
			var PICPU = JsonStatus[0]['Name'];
		}
		
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
											'<div style = "margin-top:20px;margin-left:5px;">PIC : '+PICPU+'</div>'+
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
					'<div class = "row" style = "margin-top : -5px;">'+
						'<div class = "col-xs-12">'+
							'<div>Bersama ini kami meminta untuk dikirim barang-barang sebagai berikut :</div>'+
						'</div>'+
					'</div>'+
					'<div id = "r_tblDetail"></div>'+
					'<div id = "r_terbilang"></div>'+
					'<div id = "r_signatures"></div>'+
					'<div id = "r_footer"></div>'+
					'<div id = "r_upload_file" class = "noPrint"></div>'+
					'<div id = "r_action"></div>';
		$('#PageContain').html(html);
		makeTblDetail();
		makeSignatures();
		makeFooter();
		makeDocPenawaran();
		// make Upload File
		makeUploadFile();
		makeAction();						
	}

	function makeUploadFile()
	{
		var html = '';
		var po_data = ClassDt.po_data;
		var po_create = po_data['po_create'];
		var Status = po_create[0]['Status'];
		if (Status == 2) {
			html = '<div class = "row" style = "margin-top : 10px;margin-left : 0px;margin-right : 0px">'+
							'<div class = "col-md-6">'+
								'<div class = "form-group">'+
									'<label>Upload File</label>'+
									'<input type="file" data-style="fileinput" class="BrowseFileSD" id="BrowseFileSD" accept="image/*,application/pdf">'+
								'</div>'+
							'</div>'+
						'</div>';

			$('#r_upload_file').html(html);	
			// just admin to do upload the file
			var JsonStatus = po_create[0]['JsonStatus'];
		  	JsonStatus = jQuery.parseJSON(JsonStatus);
		  	if (JsonStatus[0]['NIP'] != sessionNIP && DivisionID != '4') {
		  		$('#BrowseFileSD').closest('.col-md-6').find('.form-group').remove();
		  	}		
			var POPrint_Approve = jQuery.parseJSON(po_create[0]['POPrint_Approve']);
			if (POPrint_Approve != null && POPrint_Approve != '') {
				var htUpload = '';
				htUpload += '<a href = "'+base_url_js+'fileGetAny/budgeting-po-'+POPrint_Approve[0]+'" target="_blank" class = "Fileexist">File Approve'+'</a>';
				$('#BrowseFileSD').closest('.col-md-6').append(htUpload);
			}
			
		}
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

		// $('#DocPenawaran').html('<div class="col-xs-12"><div style = "color : red">Status : '+StatusName+'</div><div><a href="'+base_url_js+'fileGetAny/budgeting-po-'+FileOffer[0]+'" target="_blank"> Doc Penawaran</a></div><div><a href="javascript:void(0)" class="btn btn-info btn_circulation_sheet">Info</a></div></div>');
		$('#DocPenawaran').html('<div class="col-xs-12"><div style = "color : red">Status : '+StatusName+'</div><div><a href="javascript:void(0)" class="btn btn-info btn_circulation_sheet">Info</a></div></div>');

	}

	$(document).off('click', '.btn_circulation_sheet').on('click', '.btn_circulation_sheet',function(e) {
	    var url = base_url_js+'rest2/__show_info_po';
   		var data = {
   		    Code : ClassDt.Code,
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

            if (data_json['po_invoice_status'].length > 0) {
              html += '<div class = "row" style = "margin-top:10px;"><div class="col-md-12"><div class="well">';
          html += '<table class="table table-striped table-bordered table-hover table-checkable tableData" id = "TblModal2">'+
                      '<caption><h4>Invoice Status</h4></caption>'+
                      '<thead>'+
                          '<tr>'+
                              '<th style="width: 5px;">Invoice PO</th>'+
                              '<th style="width: 55px;">Paid</th>'+
                              '<th style="width: 55px;">Left</th>';
            html += '</tr>' ;
            html += '</thead>' ;
            html += '<tbody>' ;
            html += '</tbody>' ;
            html += '</table></div></div></div>' ;
            }	

   			var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
   			    '';
   			$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Info PO/SPK Code : '+ClassDt.Code+'</h4>');
   			$('#GlobalModalLarge .modal-body').html(html);
   			$('#GlobalModalLarge .modal-footer').html(footer);
   			$('#GlobalModalLarge').modal({
   			    'show' : true,
   			    'backdrop' : 'static'
   			});

   			// make datatable
   				var table = $('#TblModal').DataTable({
   				      "data" : data_json['po_circulation_sheet'],
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


          if (data_json['po_invoice_status'].length > 0) {
            var table2 = $('#TblModal2').DataTable({
                  "data" : data_json['po_invoice_status'],
                  "ordering": false,
                  "searching": false,
                  "paging":   false,
                  'columnDefs': [
                    {
                       'targets': 0,
                       'render': function (data, type, full, meta){
                           return formatRupiah(full.InvoicePO);
                       }
                    },
                    {
                       'targets': 1,
                       'render': function (data, type, full, meta){
                           return formatRupiah(full.InvoicePayPO);
                       }
                    },
                    {
                       'targets': 2,
                       'render': function (data, type, full, meta){
                           return formatRupiah(full.InvoiceLeftPO);
                       }
                    },
                  ],
                  'createdRow': function( row, data, dataIndex ) {
                      $(row).find('td:eq(0)').attr('style','width : 10px;')
                    
                  },
            });

            table2.on( 'order.dt search.dt', function () {
                    table2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                        cell.innerHTML = i+1;
                    } );
            } ).draw();
          }

   		});
	})

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
		var btn_re_open = '<button class = "btn btn-warning" id = "BtnReopen"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Re-Open</button>';
		var btn_approve = '<button class="btn btn-primary" id="Approve" action="approve">Approve</button>';
		var btn_reject = '<button class="btn btn-inverse" id="Reject" action="reject">Reject</button>';
		var btn_pdf = '<button class="btn btn-default" id="pdfprint"> <i class="fa fa-file-pdf-o"></i> PDF</button>';
		var btn_print = '<button class="btn btn-default" id="print_page"> <i class="fa fa-print" aria-hidden="true"></i> Print</button>';
		// var btn_create_spb = '<button class="btn btn-default" id="btn_create_spb"> <i class="fa fa-file-text" aria-hidden="true"></i> Create SPB</button>';
		var btn_create_spb = '';
		var btn_cancel = '<button class= "btn btn-danger" id="btn_cancel" style = "background-color: #150909;">Cancel PO</button>';
		var btn_cancel_po_pr = '<button class= "btn btn-danger" id="btn_cancel2">Cancel PO & PR</button>';
		var Status = po_create[0]['Status'];
		switch(Status) {
		  case 0:
		  case '0':
		  case -1:
		  case '-1':
		  	var JsonStatus = po_create[0]['JsonStatus'];
		  	JsonStatus = jQuery.parseJSON(JsonStatus);
		  	if (JsonStatus[0]['NIP'] == sessionNIP || DivisionID == '4') {
		  		$('#r_action').html(html);
		  		$('#r_action').find('.col-xs-12').html('<div class = "pull-right">'+btn_edit+'&nbsp'+btn_re_open+'&nbsp'+btn_submit+'&nbsp'+btn_cancel+'&nbsp'+btn_cancel_po_pr+'</div>');
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
		    		$('#r_action').find('.col-xs-12').html('<div class = "pull-right">'+btn_edit+'&nbsp'+btn_re_open+'&nbsp'+btn_submit+'&nbsp'+btn_cancel+'&nbsp'+btn_cancel_po_pr+'</div>');
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
		  	var JsonStatus = po_create[0]['JsonStatus'];
		  	JsonStatus = jQuery.parseJSON(JsonStatus);
		  	if (JsonStatus[0]['NIP'] == sessionNIP || DivisionID == '4') {
		  		$('#r_action').html(html);
		  		$('#r_action').find('.col-xs-12').html('<div class = "pull-right">'+btn_print+'&nbsp'+btn_create_spb+'&nbsp'+btn_cancel+'&nbsp'+btn_cancel_po_pr+'</div>');
		  	}
		    break;
		  case 4:
		  case '4':
		    var JsonStatus = po_create[0]['JsonStatus'];
		    JsonStatus = jQuery.parseJSON(JsonStatus);
		    if (JsonStatus[0]['NIP'] == sessionNIP || DivisionID == '4') {
		    	$('#r_action').html(html);
		    	$('#r_action').find('.col-xs-12').html('<div class = "pull-right">'+btn_re_open+'</div>');
		    }
		    break;       
		  default:
		    // code block
		}

	}

	function makeFooter()
	{
		//r_footer
		// Perbandingan Vendor
		var dt = ClassDt.po_data;
		var pre_po_supplier = dt.pre_po_supplier;
		var html_vendor = '';
			html_vendor =  '<div class = "row noPrint">'+
						'<div class = "col-xs-5">'+
							'<table class = "table borderless">'+
									'<thead></thead>'+
									'<tbody>'+
										'<tr style = "height : 40px">'+
											'<td>'+
												'Vendor : '+
											'</td>'+
											'<td>';
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
			
			html_vendor += t;
			html_vendor += '</td></tr>';
			html_vendor += '</tbody></table></div></div>';

		/*
		End Vendor
		*/
		var html = '';
		html += html_vendor;
		html += '<div class = "row" style = "margin-top : 10px;">'+
						'<div class = "col-xs-5">'+
							'<table class = "table borderless">'+
									'<thead></thead>'+
									'<tbody>'+
										'<tr style = "height : 40px">'+
											'<td>'+
												'No. PR : '+
											'</td>'+
											'<td>';

		var arr = ClassDt.PRCode_arr;
		var t = '';									
		for (var i = 0; i < arr.length; i++) {
			var tokenLink = jwt_encode(arr[i],"UAP)(*");
			var ahref = '<a href="'+base_url_js+'budgeting_pr/'+tokenLink+'" target="_blank">'+arr[i]+'</a>'; 
			t += '<li>'+ahref+'</li>';
		}

		html += t;
		html += '</td></tr>';
		html += '<tr style = "height : 80px">'+
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
		var html = '<div class= "row" style = "margin-top : 20px;">'+
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
		$('#r_signatures').html(html);

	}

	function makeTblDetail()
	{
		// r_tblDetail
		var dt = ClassDt.po_data;
		var po_create = dt['po_create'];
		htmlBtnAdd =    '';
		var IsiInputPO = MakeIsiPO();
		var Subtotal = 	parseInt(ClassDt.total_po_detail)	// 0 adalah persentase		
		var htmlInputPO = '<div class = "row" style = "margin-top : 5px;">'+
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
    		                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 150px;">Another Cost</th>'+
			                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 250px;">Sub Total</th>'+
									'</tr></thead>'+
									'<tbody>'+IsiInputPO+'</tbody>'+
									'<tfoot>'+
										// '<tr style = "background-color: #3c6560;color: #FFFFFF">'+
										// 	'<td colspan = "7">Total</td>'+
										// 	'<td colspan = "3" class = "tdTotal" value = "'+ClassDt.total_po_detail+'">'+formatRupiah(ClassDt.total_po_detail)+'</td>'+
										// '</tr>'+
										'<tr style = "background-color: #3c6560;color: #FFFFFF">'+
											'<td colspan = "7">Sub Total</td>'+
											'<td colspan = "3" class = "tdSubtotal_All" value = "'+Subtotal+'">'+formatRupiah(Subtotal)+'</td>'+
										'</tr>'+
										'<tr>'+
											'<td colspan = "10" class = "tdNotes" value = "'+po_create[0]['Notes']+'" id_pay_type = "'+po_create[0]['ID_pay_type']+'" ><b>'+nl2br(po_create[0]['Notes'])+'</b></td>'+
										'</tr>'+		
									'</table>'+
								//'</div>'+
						   '</div></div>';

		_ajax_terbilang(Subtotal).then(function(data){
			var html = htmlBtnAdd + htmlInputPO;			   
			$('#r_tblDetail').html(html);
			$('#r_terbilang').html('<div class = "row" style = "margin-top : 10px;">'+
										'<div class="col-xs-12">'+
											'<b>Terbilang (Rupiah) : '+data+' Rupiah</b>'+
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
			// console.log(Object.entries(DetailCatalog).length);
			if (typeof(DetailCatalog) == 'object' && Object.entries(DetailCatalog).length > 0) {
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
						'<td class = "tdAnotherCost" value = "'+po_detail[i]['AnotherCost']+'">'+'<div align="center">'+formatRupiah(po_detail[i]['AnotherCost'])+'</div></td>'+
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
		$(this).html('Reset');
		$(this).attr('id','BtnCancel');
		__input_reload();
		$('#BtnSubmit').prop('disabled',false);
		
	})

	function __OPpay_type(ID=0)
	{
		var html = '';
		html = '<select class = "form-control pay_type" style="width:70%;">';
		var G_pay_type = ClassDt.G_pay_type;
		for (var i = 0; i < G_pay_type.length; i++) {
			var selected = (ID==G_pay_type[i].ID) ? 'selected' : '';
			html += '<option value ="'+G_pay_type[i].ID+'" '+selected+' >'+G_pay_type[i].Name+'</option>';
		}

		html += '<select>';
		return html;
	}

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
			// $(this).find('.tdPPN').html('<input type="number" class="form-control PPN" value="'+value+'">');
			$(this).find('.tdPPN').html('<input type="text" class="form-control PPN" value="'+value+'">');
			$(this).find('.PPN').maskMoney({thousands:'', decimal:'', precision:0,allowZero: true});
			$(this).find('.PPN').maskMoney('mask', '9894');
			
			var value = $(this).find('.tdDiscount').attr('value');
			var n = value.indexOf(".");
			value = value.substring(0, n);
			// $(this).find('.tdDiscount').html('<input type="number" class="form-control Discount" value="'+value+'">');
			$(this).find('.tdDiscount').html('<input type="text" class="form-control Discount" value="'+value+'">');
			$(this).find('.Discount').maskMoney({thousands:'', decimal:'', precision:0,allowZero: true});
			$(this).find('.Discount').maskMoney('mask', '9894');

			var value = $(this).find('.tdAnotherCost').attr('value');
			var n = value.indexOf(".");
			value = value.substring(0, n);
			$(this).find('.tdAnotherCost').html('<input type="text" class="form-control AnotherCost" value="'+value+'">');
			$(this).find('.AnotherCost').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
			$(this).find('.AnotherCost').maskMoney('mask', '9894');

		})

		// var value  = $('#table_input_po tfoot').find('.tdAnotherCost').attr('value');
		// var n = value.indexOf(".");
		// value = value.substring(0, n);
		// $('#table_input_po tfoot').find('.tdAnotherCost').html('<input type="text" class="form-control AnotherCost" value="'+value+'">');
		// $('#table_input_po tfoot').find('.AnotherCost').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		// $('#table_input_po tfoot').find('.AnotherCost').maskMoney('mask', '9894');

		var value  = $('#table_input_po tfoot').find('.tdNotes').attr('value');
		// $('#table_input_po tfoot').find('.tdNotes').html('<input type="text" class="form-control Notes" value="'+value+'">');
		// $('#table_input_po tfoot').find('.tdNotes').html('<textarea class = "form-control Notes">'+value+'</textarea>');
		var ID_pay_type = $('#table_input_po tfoot').find('.tdNotes').attr('id_pay_type');
		var htmlPayType = __OPpay_type(ID_pay_type);
		$('#table_input_po tfoot').find('.tdNotes').html(htmlPayType);
	}

	$(document).off('keyup', '.Discount,.PPN').on('keyup', '.Discount,.PPN',function(e) {
		if ($(this).val() == '') {
			$(this).val(0);
		}
	})

	$(document).off('change', '.Discount,.PPN').on('change', '.Discount,.PPN',function(e) {
		$('.UnitCost').trigger('keyup');
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
				// another cost
				var AnotherCost = ev.find('.AnotherCost').val();
				AnotherCost = findAndReplace(AnotherCost, ".","");
				Subtotal = parseInt(Subtotal) + parseInt(AnotherCost);

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
			// var AnotherCost = $('#table_input_po tfoot').find('.AnotherCost').val();
			// AnotherCost = findAndReplace(AnotherCost, ".","");
			// $('#table_input_po tfoot').find('.tdTotal').html(formatRupiah(SubTotal_All));
			SubTotal_All = parseInt(SubTotal_All);
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
				var AnotherCost = $(this).find('.AnotherCost').val();
				AnotherCost = findAndReplace(AnotherCost, ".","");
				var Subtotal = $(this).find('.tdSubtotal').attr('value');
				var temp = {
					ID_po_detail :ID_po_detail,
					UnitCost : UnitCost,
					Discount : Discount,
					PPN : PPN,
					AnotherCost : AnotherCost,
					Subtotal : Subtotal,
				};

				arr_post_data_detail.push(temp);
			})

			// var AnotherCost = $('#table_input_po tfoot').find('.AnotherCost').val();
			// AnotherCost = findAndReplace(AnotherCost, ".","");
			// var Notes =  $('#table_input_po tfoot').find('.Notes').val();
			var Notes =  $('#table_input_po tfoot').find('.pay_type option:selected').text();
			var ID_pay_type =  $('#table_input_po tfoot').find('.pay_type option:selected').val();
			var url = base_url_js+"po_spk/submit_create";
			var data = {
			    po_data : po_data,
			    arr_post_data_detail : arr_post_data_detail,
			    // AnotherCost : AnotherCost,
			    Notes : Notes,
			    ID_pay_type : ID_pay_type,
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

	$(document).off('click', '#Approve').on('click', '#Approve',function(e) {
		if (confirm('Are you sure ?')) {
			loading_button('#Approve');
			var Code = ClassDt.Code;
			var approval_number = $(this).attr('approval_number');
			var url = base_url_js + 'rest2/__approve_po';
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

			  // toastr.info('No Result Data');
			  toastr.error('The Database connection error, please try again', 'Failed!!');
			}).always(function() {
			    //$('#Approve').prop('disabled',false).html('<i class="fa fa-handshake-o"> </i> Approve');
			});
		}
	})

	$(document).off('click','#BtnReopen').on('click','#BtnReopen',function(e){
		var Code = ClassDt.Code;
		var CodeURL = findAndReplace(Code, "/","-");
		var token = jwt_encode(CodeURL,"UAP)(*");
		window.location.href = base_url_js+'purchasing/transaction/po/list/open?POCode='+token;
	})

	$(document).off('click', '#Reject').on('click', '#Reject',function(e) {
		if (confirm('Are you sure ?')) {
			var Code = ClassDt.Code;
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

				var url = base_url_js + 'rest2/__approve_po';
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

	// $(document).off('click', '#print_page').on('click', '#print_page',function(e) {
	// 	window.print();
	// })

	$(document).off('click', '#print_page').on('click', '#print_page',function(e) {
		// print pdf
		var url = base_url_js+'save2pdf/print/spk_or_po';
		var PRCode = $(this).attr('prcode');
		data = {
		  Code : ClassDt.Code,
		  type : 'po',
		}
		var token = jwt_encode(data,"UAP)(*");
		FormSubmitAuto(url, 'POST', [
		    { name: 'token', value: token },
		]);
	})

	$(document).off('click', '#btn_cancel').on('click', '#btn_cancel',function(e) {
		if (confirm('Are you sure?')) {
			POCode = ClassDt.Code;
			var PRRejectItem = false;
			var arr = ClassDt.PRCode_arr;
			var PRCode = arr[0];
			var url = base_url_js+"po_spk/submit_create";
			var po_data = ClassDt.po_data;
			var arr_post_data_ID_po_detail = [];
			$('#table_input_po tbody').find('tr').each(function(){
				var ID_po_detail = $(this).attr('id_po_detail');
				arr_post_data_ID_po_detail.push(ID_po_detail);
			})

			$('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Please Input Reason ! </b> <br>' +
			    '<input type = "text" class = "form-group" id ="NoteDel" style="margin: 0px 0px 15px; height: 30px; width: 329px;" maxlength="100"><br>'+
			    '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
			    '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
			    '</div>');
			$('#NotificationModal').modal('show');
			$("#confirmYes").click(function(){
				var NoteDel = $('#NoteDel').val();
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

				var data = {
				    po_data : po_data,
				    arr_post_data_ID_po_detail : arr_post_data_ID_po_detail,
				    PRRejectItem : PRRejectItem,
				    NoteDel : NoteDel,
				    PRCode : PRCode,
				};

				var token = jwt_encode(data,"UAP)(*");
				var action_mode = 'cancel';
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
					$('#NotificationModal').modal('hide');
				}).fail(function() {
				  toastr.error('','!!!Failed');
				  $('#NotificationModal').modal('hide');
				})	
			})	

		}
	})

	$(document).off('click', '#btn_cancel2').on('click', '#btn_cancel2',function(e) {
		POCode = ClassDt.Code;
		var PRRejectItem = true;
		var arr = ClassDt.PRCode_arr;
		var PRCode = arr[0];
		if (confirm('Apakah perlu untuk cancel All ITEM dari '+PRCode+ ' yang berada pada PO ini ?')) {
			var url = base_url_js+"po_spk/submit_create";
			var po_data = ClassDt.po_data;
			var arr_post_data_ID_po_detail = [];
			$('#table_input_po tbody').find('tr').each(function(){
				var ID_po_detail = $(this).attr('id_po_detail');
				arr_post_data_ID_po_detail.push(ID_po_detail);
			})

			$('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Please Input Reason ! </b> <br>' +
			    '<input type = "text" class = "form-group" id ="NoteDel" style="margin: 0px 0px 15px; height: 30px; width: 329px;" maxlength="100"><br>'+
			    '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
			    '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
			    '</div>');
			$('#NotificationModal').modal('show');
			$("#confirmYes").click(function(){
				var NoteDel = $('#NoteDel').val();
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

				var data = {
				    po_data : po_data,
				    arr_post_data_ID_po_detail : arr_post_data_ID_po_detail,
				    PRRejectItem : PRRejectItem,
				    NoteDel : NoteDel,
				    PRCode : PRCode,
				};

				var token = jwt_encode(data,"UAP)(*");
				var action_mode = 'cancel';
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
					$('#NotificationModal').modal('hide');
				}).fail(function() {
				  toastr.error('','!!!Failed');
				  $('#NotificationModal').modal('hide');
				})	
			})	

		}
	})

	$(document).off('click', '.btn-add-new-po').on('click', '.btn-add-new-po',function(e) {
	  var page = $(this).attr('page');
	  window.location.href = base_url_js+page;
	})

	$(document).off('click', '#btn_create_spb').on('click', '#btn_create_spb',function(e) {
		var POCode = ClassDt.Code;
		POCode = findAndReplace(POCode, "/","-");
		var url = base_url_js+'global/purchasing/transaction/create_spb_by_po/'+POCode;
		window.location.href = url;
	})
	 
	$(document).off('change', '#BrowseFileSD').on('change', '#BrowseFileSD',function(e) {
	    var Code = ClassDt.Code;
	    // console.log(ID_element);
	    var ev = $(this);
	    var ID_element = 'BrowseFileSD';
	    if (file_validation(ev,'Upload File')) {
	      SaveFileUpload(Code,ID_element);
	    }
	      
	});

	function SaveFileUpload(Code,ID_element)
	{
	    var form_data = new FormData();
	    //var fileData = document.getElementById(ID_element).files[0];
	    var url = base_url_js + "po_spk/upload_file_Approve";
	    var DataArr = {
	                    Code : Code,
	                  };
	    var token = jwt_encode(DataArr,"UAP)(*");
	    form_data.append('token',token);
	    //form_data.append('fileData',fileData);
	    var files = $('#'+ID_element)[0].files;
	    for(var count = 0; count<files.length; count++)
	    {
	     form_data.append("fileData[]", files[count]);
	    }
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
	        if(data.status == 1) {
	          Get_data_po().then(function(data){
	          		ClassDt.po_data = data;
	          		WriteHtml();
	          })
	          toastr.options.fadeOut = 100000;
	          toastr.success(data.msg, 'Success!');
	        }
	        else
	        {
	          toastr.options.fadeOut = 100000;
	          toastr.error(data.msg, 'Failed!!');
	        }
	      setTimeout(function () {
	          toastr.clear();
	        },1000);

	      },
	      error: function (data) {
	        toastr.error(data.msg, 'Connection error, please try again!!');
	      }
	    })
	}

	function file_validation(ev,TheName = '')
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
</script>
<?php endif ?>	