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


.CustomMargin{
	width:800px; margin:0 auto;
}	
@page {
  size: A4;
  /*margin: 0.5;*/
  margin-left: 0.5pt;
  margin-top: 10pt;
  margin-bottom : 1pt;
  margin-right : 1pt;
}
@media print {
    .container { 
      display: block !important;
        font-size: 10px; 
        /*top: -60pt;*/
        /*left:0pt;*/
        /*right: 0pt;*/
        page-break-after: always; /* Set Just One Page */
    }
    table{
    	font-size: 10px; 
    }
    
    .btn, .noPrint, a { 
    	display:none !important;
    }
    .CustomTD
    {
    	width: 200px !important;
    }
}

</style>
<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="row noPrint">
	<div class="col-xs-2">
		<?php if ($this->session->userdata('IDdepartementNavigation') == 4): ?>
			<div><a href="<?php echo base_url().'purchasing/transaction/po/list' ?>" class = "btn btn-warning"> <i class="fa fa-arrow-circle-left"></i> Back to List</a></div>
		<?php else: ?>
			<div><a href="<?php echo base_url().'budgeting_po' ?>" class = "btn btn-warning"> <i class="fa fa-arrow-circle-left"></i> Back to List</a></div>
		<?php endif ?>
			<?php if ($bool): ?>

			<?php endif ?>
	</div>
	<?php if ($bool): ?>
	<div class="col-xs-2 col-md-offset-8">
		<div class="toolbar no-padding pull-right" style = "margin-left : 10px;">
            <span data-smt="" class="btn btn-add-new-po" page = "purchasing/transaction/po/list/open">
                <i class="icon-plus"></i> New PO / SPK
           </span>
        </div>
	</div>
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
						<p><h3><u>Surat Perintah Kerja</u></h3></p>
						<p style="margin-top: -10px;"><?php echo $Code ?></p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<?php if ($bool): ?>
<div id = "PageContain" class="CustomMargin">
	
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

	function __Get_spk_pembukaan(Date)
	{
		var def = jQuery.Deferred();
		var url = base_url_js+"rest2/__Get_spk_pembukaan";
		var data = {
		    Date : Date,
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
		var po_detail = dt.po_detail;
		var JsonStatus = po_create[0]['JsonStatus'];
		JsonStatus = jQuery.parseJSON(JsonStatus);
		var PICPU = JsonStatus[0]['Name'];

		// tulis html untuk mengerjakan dengan penggabungan catalog
		var U_mengerjakan = po_create[0]['JobSpk'];
			if (U_mengerjakan == '') {
				for (var i = 0; i < po_detail.length; i++) {
					if ( i == parseInt(po_detail.length - 1)) { // jika loop data terakhir
						if (po_detail.length != 1) {
							U_mengerjakan += ' dan '+po_detail[i]['Item'];
						}
						else
						{
							U_mengerjakan += po_detail[i]['Item'];
						}
						
					}
					else if (i==0) { // data awal
						U_mengerjakan += po_detail[i]['Item'];
					}
					else
					{
						U_mengerjakan += ' , '+po_detail[i]['Item'];
					}

				}
			}

		var F_isiDeskripsi = function(po_detail) // make function variable
		{
			var html = '';
				for (var i = 0; i < po_detail.length; i++) {
					html += '<tr id_po_detail = "'+po_detail[i]['ID_po_detail']+'">'+
								'<td>'+po_detail[i]['Item']+'</td>'+
								'<td>'+po_detail[i]['QtyPR']+'</td>'+
								'<td unitcost_po = "'+po_detail[i]['UnitCost_PO']+'">'+formatRupiah(po_detail[i]['UnitCost_PO'])+'</td>'+
								'<td ppn_po = "'+po_detail[i]['PPN_PO']+'">'+parseInt(po_detail[i]['PPN_PO'])+'</td>'+
								'<td max ="'+po_detail[i]['Subtotal_PR']+'" subtotal= "'+po_detail[i]['Subtotal']+'">'+formatRupiah(po_detail[i]['Subtotal'])+'</td>'+
							'</tr>';	
				}
			return html;
		};

		var Deskripsi = F_isiDeskripsi(po_detail);	

		var F_harga_total = function(po_detail){
			var total = 0;
			for (var i = 0; i < po_detail.length; i++) {
				total += parseInt(po_detail[i]['Subtotal']);
			}
			return(total);
		};

		var Total = F_harga_total(po_detail);

		__Get_spk_pembukaan(po_create[0]['CreatedAt']).then(function(data){
			var html = '<div class = "row">'+
							'<div class = "col-xs-12">'+
								'<div>'+data+'</div>'+
							'</div>'+
						'</div>'+
						'<div class= "row" style = "margin-top : 10px;margin-left:5px;margin-right : 5px;">'+
							'<div class= "col-xs-12">'+
								'<table class = "table borderless">'+
									'<thead></thead>'+
									'<tbody>'+
										'<tr>'+
											'<td rowspan = "5" style = "width : 45px;">I</td>'+
											'<td colspan = "3"><b>PEMBERI TUGAS</b></td>'+
										'</tr>'+
										'<tr>'+
											'<td style = "width : 300px;" class = "CustomTD"><b>NAMA PERUSAHAAN</b></td>'+
											'<td style = "width : 10px;"><b>:</b></td>'+
											'<td><b>YAY PENDIDIKAN AGUNG PODOMORO</b></td>'+
										'</tr>'+
										'<tr>'+
											'<td style = "width : 300px;" class = "CustomTD"><b>PENANGGUNG JAWAB</b></td>'+
											'<td style = "width : 10px;"><b>:</b></td>'+
											'<td><b>SERIAN WIJATNO & WIBOWO NGASERIN</b></td>'+
										'</tr>'+
										'<tr>'+
											'<td style = "width : 300px;" class = "CustomTD"><b>JABATAN</b></td>'+
											'<td style = "width : 10px;"><b>:</b></td>'+
											'<td><b>SEKRETARIS & KETUA YAYASAN</b></td>'+
										'</tr>'+
										'<tr>'+
											'<td style = "width : 300px;" class = "CustomTD"><b>ALAMAT</b></td>'+
											'<td style = "width : 10px;"><b>:</b></td>'+
											'<td><b>Jl.S. Parman Kav 28, Tanjung Duren Selatan, Grogol Petamburan,Jakarta Barat</b></td>'+
										'</tr>'+
									'</tbody>'+
								'</table>'+
							'</div>'+
						'</div>'+
						'<div class = "row" style = "margin-top : 15px;">'+
							'<div class = "col-xs-12">'+
								'<div>Yang selanjutnya disebut sebagai <b>PIHAK PERTAMA</b></div>'+
							'</div>'+
						'</div>'+
						'<div class= "row" style = "margin-top : 10px;margin-left:5px;margin-right : 5px;">'+
							'<div class= "col-xs-12">'+
								'<table class = "table borderless">'+
									'<thead></thead>'+
									'<tbody>'+
										'<tr>'+
											'<td rowspan = "5" style = "width : 45px;">II</td>'+
											'<td colspan = "3"><b>PENERIMA TUGAS</b></td>'+
										'</tr>'+
										'<tr>'+
											'<td style = "width : 300px;" class = "CustomTD"><b>NAMA PERUSAHAAN</b></td>'+
											'<td style = "width : 10px;"><b>:</b></td>'+
											'<td><b>'+po_create[0]['NamaSupplier'].toUpperCase()+'</b></td>'+
										'</tr>'+
										'<tr>'+
											'<td style = "width : 300px;" class = "CustomTD"><b>PENANGGUNG JAWAB</b></td>'+
											'<td style = "width : 10px;"><b>:</b></td>'+
											'<td><b>'+po_create[0]['PICName'].toUpperCase()+'</b></td>'+
										'</tr>'+
										'<tr>'+
											'<td style = "width : 300px;" class = "CustomTD"><b>JABATAN</b></td>'+
											'<td style = "width : 10px;"><b>:</b></td>'+
											'<td><b>'+po_create[0]['JabatanPIC'].toUpperCase()+'</b></td>'+
										'</tr>'+
										'<tr>'+
											'<td style = "width : 300px;" class = "CustomTD"><b>ALAMAT</b></td>'+
											'<td style = "width : 10px;"><b>:</b></td>'+
											'<td><b>'+po_create[0]['Alamat']+'</b></td>'+
										'</tr>'+
									'</tbody>'+
								'</table>'+
							'</div>'+
						'</div>'+
						'<div class = "row" style = "margin-top : 15px;">'+
							'<div class = "col-xs-12">'+
								'<div>Yang selanjutnya disebut sebagai <b>PIHAK KEDUA</b></div>'+
							'</div>'+
						'</div>'+
						'<div class= "row" style = "margin-top : 10px;margin-left:5px;margin-right : 5px;">'+
							'<div class= "col-xs-12">'+
								'<table class = "table borderless" id = "table_input_spk">'+
									'<thead></thead>'+
									'<tbody>'+
										'<tr>'+
											'<td rowspan = "6" style = "width : 45px;"></td>'+
											'<td colspan = "3"></td>'+
										'</tr>'+
										'<tr>'+
											'<td style = "width : 300px;" class = "CustomTD"><b>UNTUK MENGERJAKAN</b></td>'+
											'<td style = "width : 10px;"><b>:</b></td>'+
											'<td class = "U_mengerjakan" dt = "'+U_mengerjakan+'">'+
												'<div>'+
													'<b>'+nl2br(U_mengerjakan)+'</b>'+
												'</div>'+
											'</td>'+
										'</tr>'+
										'<tr>'+
											'<td colspan="3">'+
												'<div style = "margin-top : 8px;">'+
													'<b>Deskripsi</b>'+
													'<table class = "table table-bordered" id = "Tbl_Deskripsi">'+
														'<thead>'+
															'<tr>'+
																'<th >'+
																'</th>'+
																'<th>Qty</th>'+
																'<th>UnitCost</th>'+
																'<th width>PPN(%)</th>'+
																'<th>Total</th>'+
															'</tr>'+
														'</thead>'+
														'<tbody>'+
															Deskripsi+
														'</tbody>'+
													'</table>'+				
												'</div>'+
											'</td>'+
										'</tr>'+		
										'<tr>'+
											'<td style = "width : 300px;" class = "CustomTD"><b>HARGA TOTAL</b></td>'+
											'<td style = "width : 10px;"><b>:</b></td>'+
											'<td class="TotalSPK"><b>'+formatRupiah(Total)+'</b></td>'+
										'</tr>'+
										'<tr>'+
											'<td style = "width : 300px;" class = "CustomTD"><b>CARA PEMBAYARAN</b></td>'+
											'<td style = "width : 10px;"><b>:</b></td>'+
											'<td class= "tdNotes" notes = "'+po_create[0]['Notes']+'">'+nl2br(po_create[0]['Notes'])+'</td>'+
										'</tr>'+
										'<tr>'+
											'<td style = "width : 300px;" class = "CustomTD"><b>SYARAT - SYARAT</b></td>'+
											'<td style = "width : 10px;"><b>:</b></td>'+
											'<td class="tdNotes2" notes2 = "'+po_create[0]['Notes2']+'">'+nl2br(po_create[0]['Notes2'])+'</td>'+
										'</tr>'+
									'</tbody>'+
								'</table>'+
							'</div>'+
						'</div>'+
						'<div id = "r_footer"></div>'+
						'<div id = "r_signatures"></div>'+
						'<div id = "r_action" style = "margin-left : -15px;margin-right:-15px;"></div>';

			$('#PageContain').html(html);

			if (ClassDt.PRCode_arr.length == 0) {
				ClassDt.PRCode_arr.push(po_detail[0]['PRCode']);
			}
			else
			{
				var bool = true;
				for (var j = 0; j < ClassDt.PRCode_arr.length; j++) {
					var arr = ClassDt.PRCode_arr;
					if (arr[j] == po_detail[0]['PRCode']) {
						bool = false;
						break;
					}
				}

				if (bool) {
					ClassDt.PRCode_arr.push(po_detail[0]['PRCode']);
				}
			}


			makeFooter();
			makeDocPenawaran();
			makeSignatures();
			makeAction();
			loadingEnd(1000);									

		})
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

		$('#DocPenawaran').html('<div class="col-xs-12 No"><div class = "noPrint" style = "color : red">Status : '+StatusName+'</div><div><a href="'+base_url_js+'fileGetAny/budgeting-po-'+FileOffer[0]+'" target="_blank"> Doc Penawaran</a></div><div><a href="javascript:void(0)" class="btn btn-info btn_circulation_sheet">Info</a></div></div>');

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
		var btn_create_spb = '<button class="btn btn-default" id="btn_create_spb"> <i class="fa fa-file-text" aria-hidden="true"></i> Create SPB</button>';
		var btn_cancel = '<button class= "btn btn-danger" id="btn_cancel" style = "background-color: #150909;">Cancel SPK</button>';
		var btn_cancel_po_pr = '<button class= "btn btn-danger" id="btn_cancel2">Cancel SPK & PR</button>';
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
		    		$('#r_action').find('.col-xs-12').html('<div class = "pull-right">'+btn_edit+'&nbsp'+btn_re_open+'&nbsp'+btn_submit+'&nbsp'+btn_cancel+'&nbsp'+btn_cancel_po_pr+'</div>');;
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
		var dt = ClassDt.po_data;
		var po_create = dt.po_create;
		var po_detail = dt.po_detail;
		//r_footer

		// Perbandingan Vendor
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
				t += '<li><a href="'+base_url_js+'fileGetAny/budgeting-po-'+File[0]+'" target="_blank">'+pre_po_supplier[i].NamaSupplier+'</a>'+'</li>';
			}
			
			html_vendor += t;
			html_vendor += '</td></tr>';
			html_vendor += '</tbody></table></div></div>';

		/*
		End Vendor
		*/
		var html = '';
		html += html_vendor;
		html += '<div class = "row">'+
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
			t += '<li>'+arr[i]+'</li>';
		}

		html += t;
		html += '</td></tr>';
		html += '</tbody></table></div></div>';

		html += '<div class = "row">'+
					'<div class = "col-xs-12">'+
						'<table class = "table borderless">'+
							'<thead></thead>'+
							'<tbody>'+
								'<tr>'+
									'<td><div align = "left"><b>PIHAK I</b></div></td>'+
									'<td style="text-align :right"><div><b>PIHAK II</b></div></td>'+
								'<tr>'+
									'<td><div align = "left"><b>YAY PENDIDIKAN AGUNG PODOMORO</b></div></td>'+
									'<td style="text-align :right"><div><b>'+po_create[0]['NamaSupplier'].toUpperCase()+'</b></div></td>'+	
								'<tr>'+
								'<tr style = "height : 60px;">'+
									'<td></td>'+
									'<td></td>'+
								'</tr>'+
								'<tr>'+
									'<td><div align = "left"><b><u>SERIAN WIJATNO & WIBOWO NGASERIN</u></b></br>SEKRETARIS & KETUA YAYASAN</div></td>'+
									'<td style="text-align :right"><div><b><u>'+po_create[0]['PICName'].toUpperCase()+'</u></b></br>'+po_create[0]['JabatanPIC'].toUpperCase()+'</div></td>'+	
								'<tr>'+		
							'</tbody>'+
						'</table>'+
					'</div>'+
				'</div>';		

		$('#r_footer').html(html);


	}

	function makeSignatures(){
		// r_signatures
		var dt = ClassDt.po_data;
		var po_create = dt['po_create'];
		var html = '<div class= "row noPrint" style = "margin-top : 100px;">'+
						'<div class = "col-xs-6 col-md-offset-6">'+
							'<table class = "table">'+
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
						'<tr style = "height : 51px">';
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

	$(document).off('click', '#BtnEdit').on('click', '#BtnEdit',function(e) {
		$(this).attr('class','btn btn-danger');
		$(this).find('i').remove();
		$(this).html('Reset');
		$(this).attr('id','BtnCancel');
		__input_reload();
		$('#BtnSubmit').prop('disabled',false);
		
	})

	function __input_reload()
	{
		$('#Tbl_Deskripsi tbody').find('tr').each(function(){
			var unitcost_po = $(this).find('td:eq(2)').attr('unitcost_po');
			var n = unitcost_po.indexOf(".");
			value = unitcost_po.substring(0, n);
			// write html input
			$(this).find('td:eq(2)').html('<input type="text" class="form-control UnitCost" value="'+value+'">')
			$(this).find('.UnitCost').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
			$(this).find('.UnitCost').maskMoney('mask', '9894');

			var ppn_po = $(this).find('td:eq(3)').attr('ppn_po');
			var n = ppn_po.indexOf(".");
			value = ppn_po.substring(0, n);
			$(this).find('td:eq(3)').html('<input type="number" class="form-control PPN" value="'+value+'">');
		})

		var value  = $('#table_input_spk').find('.tdNotes').attr('notes');
		// $('#table_input_spk').find('.tdNotes').html('<input type="text" class="form-control Notes" value="'+value+'">');
		$('#table_input_spk').find('.tdNotes').html('<textarea class = "form-control Notes">'+value+'</textarea>');

		var value  = $('#table_input_spk').find('.tdNotes2').attr('notes2');
		// $('#table_input_spk').find('.tdNotes2').html('<input type="text" class="form-control Notes2" value="'+value+'">');
		$('#table_input_spk').find('.tdNotes2').html('<textarea class = "form-control Notes2">'+value+'</textarea>');

		// Untuk Mengerjakan
		var value  = $('.U_mengerjakan').attr('dt');
		// $('#table_input_spk').find('.U_mengerjakan').html('<input type="text" class="form-control Inp_U_mengerjakan" value="'+value+'">');
		$('#table_input_spk').find('.U_mengerjakan').html('<textarea class = "form-control Inp_U_mengerjakan">'+value+'</textarea>');
		
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
		var td = $(this).closest('td');
		var ChangeBool = CountSubTotal_table(td);
		if (!ChangeBool) {
			__input_reload();
			var bool = CountSubTotal_table(td);
			if (bool) {
				$('#BtnSubmit').prop('disabled',false);
			}
			
		}
		else
		{
			// cari all total 
				var Total = 0;
				$('#Tbl_Deskripsi tbody').find('tr').each(function(){
					Total += parseInt($(this).find('td:eq(4)').attr('subtotal'))
				})

				$('.TotalSPK').html(formatRupiah(Total));
		}
	})

	

	$(document).off('keydown', '.Discount,.PPN').on('keydown', '.Discount,.PPN',function(e) {
		if (e.keyCode === 190) {
		    e.preventDefault();
		}
	})

	function CountSubTotal_table(ev)
	{
		var bool = true;

		// process
		var tr = ev.closest('tr');
		var Subtotal_limit = tr.find('td:eq(4)').attr('max');
		var n = Subtotal_limit.indexOf(".");
		Subtotal_limit = Subtotal_limit.substring(0, n);
		var PPN = tr.find('.PPN').val();
		var UnitCost = tr.find('.UnitCost').val();
		UnitCost = findAndReplace(UnitCost, ".","");
		var qty = tr.find('td:eq(1)').html();

		var total_raw = (parseInt(UnitCost) * parseInt(qty));
		var PPN_ = (parseInt(PPN) * parseInt(total_raw) ) / 100;
		var Discount_ = 0;
		var Subtotal = parseInt(total_raw) + parseInt(PPN_) - parseInt(Discount_);

		if (Subtotal > Subtotal_limit) {
			toastr.info('Subtotal melebihi dari Anggaran PR yaitu '+formatRupiah(Subtotal_limit));
			bool = false;
			return;
		}
		else
		{
			tr.find('td:eq(4)').attr('subtotal',Subtotal);
			tr.find('td:eq(4)').html(formatRupiah(Subtotal));
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
			$('#Tbl_Deskripsi tbody').find('tr').each(function(){
				var ID_po_detail = $(this).attr('id_po_detail');
				var UnitCost = $(this).find('.UnitCost').val();
				UnitCost = findAndReplace(UnitCost, ".","");
				var PPN = $(this).find('.PPN').val();
				var Subtotal = $(this).find('td:eq(4)').attr('subtotal');
				var temp = {
					ID_po_detail :ID_po_detail,
					UnitCost : UnitCost,
					PPN : PPN,
					Subtotal : Subtotal,
				};
				arr_post_data_detail.push(temp);
			})

			var Notes =  $('#table_input_spk').find('.Notes').val();
			var Notes2 =  $('#table_input_spk').find('.Notes2').val();
			var JobSpk = $('.Inp_U_mengerjakan').val();
			var url = base_url_js+"po_spk/submit_create";
			var data = {
			    po_data : po_data,
			    arr_post_data_detail : arr_post_data_detail,
			    Notes : Notes,
			    Notes2 : Notes2,
			    JobSpk : JobSpk,
			};
			
			var token = jwt_encode(data,"UAP)(*");
			var action_mode = 'modifycreated';
				action_mode = jwt_encode(action_mode,"UAP)(*");
			var action_submit = 'SPK';
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

	$(document).off('click', '#print_page').on('click', '#print_page',function(e) {
		window.print();
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
			$('#Tbl_Deskripsi tbody').find('tr').each(function(){
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
				var action_submit = 'SPK';
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
			$('#Tbl_Deskripsi tbody').find('tr').each(function(){
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
				var action_submit = 'SPK';
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
</script>
<?php endif ?>	