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
<style type="text/css">
  /* FANCY COLLAPSE PANEL STYLES */
  .fancy-collapse-panel .panel-default > .panel-heading {
  padding: 0;

  }
  .fancy-collapse-panel .panel-heading a {
  padding: 12px 35px 12px 15px;
  display: inline-block;
  width: 100%;
  background-color: #EE556C;
  color: #ffffff;
  position: relative;
  text-decoration: none;
  }
  .fancy-collapse-panel .panel-heading a:after {
  font-family: "FontAwesome";
  content: "\f147";
  position: absolute;
  right: 20px;
  font-size: 20px;
  font-weight: 400;
  top: 50%;
  line-height: 1;
  margin-top: -10px;
  }

  .fancy-collapse-panel .panel-heading a.collapsed:after {
  content: "\f196";
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
	<div class="col-md-3">
		<button type="button" class="btn btn-default btn-write btn-tambah hide"> <i class="icon-plus"></i> Add</button>
	</div>
</div>
<div id = "content_input" class="fancy-collapse-panel" style="margin-top: 10px;min-width: 1200px;overflow: auto;">
	
</div>
<script type="text/javascript">
	var IDDepartementPUBudget = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";
	var ClassDt = {
		ThisTableSelect : '',
		Dataselected : [],
		po_data : [],
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

	var PaymentType = ['Spb','Bank Advance','Cash Advance'];	
	$(document).ready(function() {
		$('#page_po_list').html(ClassDt.htmlPage_po_list);
		Get_data_po().then(function(data){
			$('.C_radio_pr:first').prop('checked',true);
			$('.C_radio_pr:first').trigger('change');
		})
	});

	function Get_data_po(){
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
       	    "iDisplayLength" : 5,
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
		loadingStart();
		var Code = $(this).attr('code');
		Get_data_spb_grpo(Code).then(function(data){
			ClassDt.Dataselected = data;
			Get_data_detail_po(Code).then(function(data2){
				// Define data
				ClassDt.po_data = data2;
				MakeDomHtml(data);
				loadingEnd(500);
			})

		})

	})

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

	function MakeDomHtml(data)
	{
		var html = '';
		var template_html = '';
		if (data.dtspb.length == 0) {
			template_html += __template_html('add','');
		}
		else
		{
			 // data existing
			var dtspb = data.dtspb;
			for (var i = 0; i < dtspb.length; i++) {
				var ID_payment = dtspb[i]['ID'];
				var Type = dtspb[i]['Type'];
				template_html += __template_html('edit',ID_payment,i,Type);
			}
			$('.btn-tambah').removeClass('hide');
		}
	
		$('#content_input').html(template_html);
	}

	function __OPPayment(NameSelected = '')
	{
		var html = '';
		NameSelected = (NameSelected == '') ? 'Spb' : NameSelected;
		html += '<select class = "form-control ChoosePayment" style = "width:350px;">';
		for (var i = 0; i < PaymentType.length; i++) {
			var selected = (PaymentType[i] == NameSelected) ? 'selected' : '';
			html += '<option value = "'+PaymentType[i]+'" '+selected+'>'+PaymentType[i]+'</option>';
		}
		html += '</select>';
		return html;
	}

	function __htmlContentGRPO(number,button = 0)
	{
		var html = '';
		var htmlDefault = '<div class = "row">'+
			    				'<div class = "col-xs-12">'+
			    					'<div class="panel panel-default">'+
			    					   ' <div class="panel-heading" role="tab" id="headingOne">'+
			    					        '<h4 class="panel-title">'+
			    					            '<a href="javascript:void(0)" class="pageAnchor_pembayaran" page = "FormInputGR" data-toggle="collapse" status = "0" data-target=".FormInputGR'+number+'" type = "gr">Good Receipt'+
			    					            '</a>'+
			    					        '</h4>'+
			    					    '</div>'+
			    					    '<div class="collapse FormInputGR'+number+'">'+
			    					        '<div class="panel-body pageFormInput">'+
			    					            
			    					        '</div>'+
			    					    '</div>'+
			    					'</div>'+
			    				'</div>'+
			    			'</div>';
	    html += '<div class="col-xs-6 GRPORow">'+
	    			htmlDefault+	
				'</div>';
		if (button == 1) {
			return htmlDefault;
		}
		else
		{
			return html;	
		}		
			
	}

	function __HtmlPageContentPayment(number,NameSelected = '')
	{
		NameSelected = (NameSelected == '') ? 'Spb' : NameSelected;
		var html = '';
		var htmlContentGRPO = __htmlContentGRPO(number);
		html += '<div class="row PageContentPayment" style = "margin-top:10px;">'+
					'<div class="col-xs-6">'+
						'<div class="panel panel-default">'+
						    '<div class="panel-heading" role="tab" id="headingOne">'+
						        '<h4 class="panel-title">'+
						            '<a href="javascript:void(0)" class="pageAnchor_pembayaran" page = "FormInputPayment" data-toggle="collapse" status = "0" data-target=".FormInputPayment'+number+'" type = "'+NameSelected+'">'+NameSelected+
						            '</a>'+
						        '</h4>'+
						    '</div>'+
						    '<div class="collapse FormInputPayment'+number+'">'+
						        '<div class="panel-body pageFormInput">'+
						        '</div>'+
						    '</div>'+
						'</div>'+
					'</div>'+
					htmlContentGRPO+	
				'</div>';

		return html;
	}

	function __template_html(action='add',ID_payment='',number=0,Type='')
	{
		/* Choose Payment */
			var html = '';
			var htmlChoosePayment = __OPPayment(Type);
			var PageContentPayment = __HtmlPageContentPayment(number,Type);
			html = '<div class ="row FormPage" action = "'+action+'" ID_payment = "'+ID_payment+'" number="'+number+'">'+
						'<div class="col-xs-12" >'+
							'<div class="panel panel-primary">'+
								'<div class="panel-heading clearfix" style = "background-color: #437d73;">'+
									'<h4 class="panel-title pull-left" style="padding-top: 7.5px;">Payment & GRPO</h4>'+
								'</div>'+
								'<div class="panel-body">'+
									'<div class = "row">'+
										'<div class = "col-xs-6">'+
											'<div class = "thumbnail">'+
												'<label>Choose Payment</label>'+
												htmlChoosePayment+
											'</div>'+
										'</div>'+	
										'<div class = "col-xs-6">'+
											'<button class="btn btn-primary btn-add-grpo" style = "margin-top:57px;"><i class="fa icon-plus"></i> </button>'+
										'</div>'+
									'</div>'+
										PageContentPayment+		
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>';
			return html;						
	}


	$(document).off('click', '.pageAnchor_pembayaran').on('click', '.pageAnchor_pembayaran',function(e) {
		var page = $(this).attr('page');
		var type = $(this).attr('type');
		// get number
		var row = $(this).closest('.FormPage');
		var number = row.attr('number');
		var se_ = $('.'+page+number);
		var se_content = se_.find('.pageFormInput');
		
		// for collapse or in
		var st = $(this).attr('status');
		if (st == 0) {
			// get action & id_spb_created
			var action = row.attr('action');
			var ID_payment = row.attr('ID_payment');
			if (type == 'Spb') {
				if (action=='add') {
					makeDomSPBAdd(action,ID_payment,number,se_content);
				}
				else
				{
					makeDomSPBView(action,ID_payment,number,se_content);
				}
				
			}
			else if(type == 'Bank Advance')
			{
				makeDomBank_Advance(action,ID_payment,number,se_content);
			}
			else if(type == 'Cash Advance')
			{
				makeDomCash_Advance(action,ID_payment,number,se_content);
			}
			else if(type == 'gr')
			{
				// var number = $(this).attr('data-target');
				// number = findAndReplace(number,'.FormInputGR','');
				// number = parseInt(number);
				// var se_ = $('.'+page+number);
				// var se_content = se_.find('.pageFormInput');
				if (action=='add') {
					makeDomGRPOAdd(action,ID_payment,number,se_content);
				}
				else
				{
					makeDomGRPOView(action,ID_payment,number,se_content);
				}
			}

			// se_content.html('asdasd'); 
			$(this).attr('status',1);
		}
		else
		{
			// se_content.empty(); 
			$(this).attr('status',0);
		}
	})

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

	function makeDomSPBAdd(action,ID_spb_created,number,se_content)
	{
		var Code = $('.C_radio_pr:checked').attr('code');
		var InvoicePO = $('.C_radio_pr:checked').attr('invoicepo');
		var InvoiceleftPO = $('.C_radio_pr:checked').attr('invoiceleftpo');
		var Supplier = $('.C_radio_pr:checked').attr('supplier');

		var data = ClassDt.Dataselected;
		var dtspb = data.dtspb;
		var TypeInvoice = 'Pembayaran 1';
		if (dtspb.length > 0) {
			var InvoiceleftPO = parseInt(InvoicePO);
			var c = 0;
			for (var i = 0; i < dtspb.length; i++) {
				if (dtspb[i].Invoice != null && dtspb[i].Invoice != 'null') {
					InvoiceleftPO -= parseInt(dtspb[i].Invoice);
					c++;
				}
				else
				{
					InvoiceleftPO -= parseInt(0);
				}
			}

			InvoiceleftPO = (parseFloat(InvoiceleftPO)).toFixed(2);
			var TypeInvoice = 'Pembayaran ' + (parseInt(c)+1);
		}

		var html = '';
		Supplier = Supplier.split('||');
		Supplier = Supplier[1].trim();
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
									'<label class="TypePembayaran" type = "'+TypeInvoice+'"><b>'+TypeInvoice+'</b></label>'+
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
									'<button class="btn btn-default hide print_page"> <i class="fa fa-print" aria-hidden="true"></i> Print</button> &nbsp'+
									'<button class="btn btn-primary hide btnEditInput"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button> &nbsp'+
									'<button class="btn btn-success submit"> Submit</button>'+
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

		se_content.find('.dtbank[tabindex!="-1"]').select2({
		    //allowClear: true
		});

	}

	function makeDomBank_Advance(action,ID_payment,number,se_content,button = 0)
	{
		var Code = $('.C_radio_pr:checked').attr('code');
		var InvoicePO = $('.C_radio_pr:checked').attr('invoicepo');
		var InvoiceleftPO = $('.C_radio_pr:checked').attr('invoiceleftpo');
		var Supplier = $('.C_radio_pr:checked').attr('supplier');

		var data = ClassDt.Dataselected;
		var dtspb = data.dtspb;
		var TypeInvoice = 'Pembayaran 1';
		if (dtspb.length > 0) {
			var InvoiceleftPO = parseInt(InvoicePO);
			var c = 0;
			for (var i = 0; i < dtspb.length; i++) {
				if (dtspb[i].Invoice != null && dtspb[i].Invoice != 'null') {
					InvoiceleftPO -= parseInt(dtspb[i].Invoice);
					c++;
				}
				else
				{
					InvoiceleftPO -= parseInt(0);
				}
			}

			InvoiceleftPO = (parseFloat(InvoiceleftPO)).toFixed(2);
			var TypeInvoice = 'Pembayaran ' + (parseInt(c)+1);
		}

		var html = '';
		Supplier = Supplier.split('||');
		Supplier = Supplier[1].trim();
		var htmlAdd = (button == 0) ? '<div class = "BAAdd">' : '';
		var EndhtmlAdd = (button == 0) ? '</div>' : '';
		// check exist or not for edit
		var dt_arr = __getRsViewGRPO_SPB(ID_payment);
		var dtspb = dt_arr.dtspb;
		console.log(dtspb);
		var Invoice = 0;
		var TypePay = "Transfer";
		var ID_bank = 7;
		var NoRekening = "";
		var Nama_Penerima = "";
		var Date_Needed = "";
		var Perihal = 'Pembayaran '+Code;
		var Dis = '';
		var btn_hide = 'hide';
		var btn_hide_print = 'hide';
		var Status = 0;
		
		if (typeof dtspb[0] !== "undefined") {
			if (dtspb[0].Type == 'Bank Advance' && dtspb[0].Detail.length == 0 && action == 'edit') {
				makeDomBank_Advance('add',ID_payment,number,se_content);
				return;
			}
			else
			{
				if (action == 'edit' && dtspb[0].Type == 'Bank Advance' && dtspb[0].Detail.length > 0) {
					Invoice = parseInt(dtspb[0].Detail[0].Biaya);
					TypePay = dtspb[0].Detail[0].TypePay;
					ID_bank = dtspb[0].Detail[0].ID_bank;
					NoRekening = dtspb[0].Detail[0].No_Rekening;
					Nama_Penerima = dtspb[0].Detail[0].Nama_Penerima;
					Date_Needed = dtspb[0].Detail[0].Date_Needed;
					Perihal = dtspb[0].Detail[0].Perihal;
					Dis = 'disabled';
					btn_hide = '';
					Status = dtspb[0]['Status'];
					if (Status == 2) {
						btn_hide_print = '';
					}

					// hitung Left PO
					var InvoiceleftPO = parseInt(InvoicePO);
					var c = 0;
					for (var i = 0; i < data.dtspb.length; i++) {
						if (ID_payment == data.dtspb[i].ID && i > 0) {
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
				}
				
			}
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
					'</tbody>'+
					'</table>'+
					'<div id="r_signatures"></div>'+
					'<div id = "r_action">'+
						'<div class="row">'+
							'<div class="col-md-12">'+
								'<div class="pull-right">'+
									'<button class="btn btn-default '+btn_hide_print+' print_page"> <i class="fa fa-print" aria-hidden="true"></i> Print</button> &nbsp'+
									'<button class="btn btn-primary '+btn_hide+' btnEditInputBA" status="'+Status+'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button> &nbsp'+
									'<button class="btn btn-success submitBA" '+Dis+'> Submit</button>'+
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

		se_content.find('.dtbank[tabindex!="-1"]').select2({
		    //allowClear: true
		});
		if (typeof dtspb[0] !== "undefined") {
			if (action == 'edit' && dtspb[0].Type == 'Bank Advance' && dtspb[0].Detail.length > 0) {
				var JsonStatus = jQuery.parseJSON(dtspb[0]['JsonStatus']);
				makeSignaturesSPB(se_content,JsonStatus);
				makepage_status(dt_arr,se_content);
			}
		}	
		

	}

	function makeDomCash_Advance(action,ID_payment,number,se_content,button = 0)
	{
		var Code = $('.C_radio_pr:checked').attr('code');
		var InvoicePO = $('.C_radio_pr:checked').attr('invoicepo');
		var InvoiceleftPO = $('.C_radio_pr:checked').attr('invoiceleftpo');
		var Supplier = $('.C_radio_pr:checked').attr('supplier');

		var data = ClassDt.Dataselected;
		var dtspb = data.dtspb;
		var TypeInvoice = 'Pembayaran 1';
		if (dtspb.length > 0) {
			var InvoiceleftPO = parseInt(InvoicePO);
			var c = 0;
			for (var i = 0; i < dtspb.length; i++) {
				if (dtspb[i].Invoice != null && dtspb[i].Invoice != 'null') {
					InvoiceleftPO -= parseInt(dtspb[i].Invoice);
					c++;
				}
				else
				{
					InvoiceleftPO -= parseInt(0);
				}
			}

			InvoiceleftPO = (parseFloat(InvoiceleftPO)).toFixed(2);
			var TypeInvoice = 'Pembayaran ' + (parseInt(c)+1);
		}

		var html = '';
		Supplier = Supplier.split('||');
		Supplier = Supplier[1].trim();
		var htmlAdd = (button == 0) ? '<div class = "CAAdd">' : '';
		var EndhtmlAdd = (button == 0) ? '</div>' : '';
		// check exist or not for edit
		var dt_arr = __getRsViewGRPO_SPB(ID_payment);
		var dtspb = dt_arr.dtspb;
		console.log(dtspb);
		var Invoice = 0;
		var TypePay = "Cash";
		var ID_bank = 0;
		var NoRekening = "";
		var Nama_Penerima = "";
		var Date_Needed = "";
		var Perihal = 'Pembayaran '+Code;
		var Dis = '';
		var btn_hide = 'hide';
		var btn_hide_print = 'hide';
		var Status = 0;

		if (typeof dtspb[0] !== "undefined") {
			if (dtspb[0].Type == 'Cash Advance' && dtspb[0].Detail.length == 0 && action == 'edit') {
				makeDomCash_Advance('add',ID_payment,number,se_content);
				return;
			}
			else
			{
				if (action == 'edit' && dtspb[0].Type == 'Cash Advance' && dtspb[0].Detail.length > 0) {
					Invoice = parseInt(dtspb[0].Detail[0].Biaya);
					TypePay = dtspb[0].Detail[0].TypePay;
					ID_bank = dtspb[0].Detail[0].ID_bank;
					NoRekening = dtspb[0].Detail[0].No_Rekening;
					Nama_Penerima = dtspb[0].Detail[0].Nama_Penerima;
					Date_Needed = dtspb[0].Detail[0].Date_Needed;
					Perihal = dtspb[0].Detail[0].Perihal;
					Dis = 'disabled';
					btn_hide = '';
					Status = dtspb[0]['Status'];
					if (Status == 2) {
						btn_hide_print = '';
					}

					// hitung Left PO
					var InvoiceleftPO = parseInt(InvoicePO);
					var c = 0;
					for (var i = 0; i < data.dtspb.length; i++) {
						if (ID_payment == data.dtspb[i].ID && i > 0) {
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
									'<button class="btn btn-success submitCA" '+Dis+'> Submit</button>'+
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

		se_content.find('.dtbank[tabindex!="-1"]').select2({
		    //allowClear: true
		});

		if (typeof dtspb[0] !== "undefined") {
			if (action == 'edit' && dtspb[0].Type == 'Cash Advance' && dtspb[0].Detail.length > 0) {
				var JsonStatus = jQuery.parseJSON(dtspb[0]['JsonStatus']);
				makeSignaturesSPB(se_content,JsonStatus);
				makepage_status(dt_arr,se_content);
			}
		}
	}

	function OPPo_detail(IDselected = null,arr_IDPass=[],value_qty=0,action_btn='')
	{
		var h = '';
		var po_data = ClassDt.po_data;
		var po_detail= po_data.po_detail;
		h = '<div class = "form-horizontal GroupingItem" style="margin-top:15px;">'+
				'<div class="form-group">'+
					'<label class = "col-sm-2">Pilih Item</label>'
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
		h += '<div class="col-sm-1"><button class="btn btn-danger btn-delete-item" '+action_btn+'><i class="fa fa-trash"></i> </button></div>';
		h += '</div></div>';
		return h;
	}

	function makeDomGRPOAdd(action,ID_payment,number,se_content,button = 0)
	{
		var html = '';
		var po_data = ClassDt.po_data;
		html += '<div class = "row"><div class = "col-xs-12"><div align="center"><h2>Good Receipt PO <button class="btn btn-warning btn-delete-grpo" ID_good_receipt_spb = ""><i class="fa fa-trash"></i> </button></h2></div>'+
					'<hr style="height:2px;border:none;color:#333;background-color:#333;margin-top: -3px;">'+
					'<button class="btn btn-default btn-add-item"><i class="fa icon-plus"></i>  </button>'+
					'<br>'+
					'<div id = "page_po_item">'+
						OPPo_detail()+
					'</div>'+
					'<br>'+
					'<div class = "form-horizontal" style="margin-top:5px;">'+
									'<div class="form-group">'+
										'<label class = "col-sm-2">No Document</label>'+	
											'<div class="col-sm-4">'+'<input type = "text" class = "form-control NoDocument" placeholder = "Input No Document...."></div>'+
										'<label class = "col-sm-1">Upload Document</label>'+
											'<div class="col-sm-4">'+'<input type="file" data-style="fileinput" class="BrowseDocument" id="BrowseDocument" accept="image/*,application/pdf"></div>'+
									'</div>'+
					'</div>'+				
					'<div class = "form-horizontal" style="margin-top:5px;">'+
									'<div class="form-group">'+
										'<label class = "col-sm-2">No Tanda Terima</label>'	+
											'<div class="col-sm-4">'+'<input type = "text" class = "form-control NoTandaTerimaGRPO" placeholder = "Input No Tanda Terima...."></div>'+
										'<label class = "col-sm-1">Upload Tanda Terima</label>'+
											'<div class="col-sm-4">'+'<input type="file" data-style="fileinput" class="BrowseTTGRPO" id="BrowseTTGRPO" accept="image/*,application/pdf"></div>'+
									'</div>'+
					'</div>'+
					'<div class = "form-horizontal" style="margin-top:5px;">'+
									'<div class="form-group">'+
										'<label class = "col-sm-2">Tanggal</label>'	+
											'<div class="col-sm-4">'+'<div class="input-group input-append date datetimepicker">'+
		                            '<input data-format="yyyy-MM-dd" class="form-control TglGRPO" type=" text" readonly="" value = "">'+
		                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
		                		'</div></div>'+
					'</div>'+
					'<div id = "r_action">'+
						'<div class="row">'+
							'<div class="col-md-12">'+
								'<div class="pull-right">'+
									'<button class="btn btn-primary hide btnEditInputGRPO"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button> &nbsp'+
									'<button class="btn btn-success submitGRPO"> Submit</button>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>'+												
				'</div></div>';


		se_content.append(html);
		// se_content.append(html);
		se_content.find('.QtyDiterima').maskMoney({thousands:'', decimal:'', precision:0,allowZero: true});
		se_content.find('.QtyDiterima').maskMoney('mask', '9894');
		se_content.find('.datetimepicker').datetimepicker({
			format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
		});		
	}

	$(document).off('change', '.ChoosePayment').on('change', '.ChoosePayment',function(e) {
		var v = $(this).val();
		var ev = $(this).closest('.FormPage');
		var action = ev.attr('action');
		var number = ev.attr('number');
		// if (action == 'add') {
		// 	var html = __HtmlPageContentPayment(number,v);
		// }
		// else
		// {
		// 	var html = '';
		// }
		var html = __HtmlPageContentPayment(number,v);
		ev.find('.PageContentPayment').remove();
		var r = $(this).closest('.row');
		r.after(html);
	})

	$(document).off('keyup keydown', '.Money_Pembayaran').on('keyup keydown', '.Money_Pembayaran',function(e) {
		var ev = $(this).closest('.FormPage');
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


	$(document).off('click', '.submit').on('click', '.submit',function(e) {
		// validation
		var ev = $(this).closest('.FormPage');
		var action = ev.attr('action');
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
			// check berdasarkan ID_payment
			var ID_payment = ev.attr('id_payment');
			if (ID_payment != '' && ID_payment != null && ID_payment != undefined) {
				var dt_arr = __getRsViewGRPO_SPB(ID_payment);
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

	function SubmitSPB(elementbtn,ev,action="add")
	{
		loadingStart();
		var Code_po_create = $('.C_radio_pr:checked').attr('code');
		var Departement = IDDepartementPUBudget;
		var ID_payment = ev.attr('id_payment');
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

		var data_verify = {
			Code_po_create : Code_po_create,
			InvoicePO : $('.C_radio_pr:checked').attr('invoicepo'),
			InvoiceLeftPO : $('.C_radio_pr:checked').attr('invoiceleftpo'),
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
		  				Get_data_po().then(function(data){
		  					$('.C_radio_pr:first').prop('checked',true);
		  					$('.C_radio_pr:first').trigger('change');
		  					loadingEnd(500);
		  				})
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
		  			Get_data_po().then(function(data){
		  				$('.C_radio_pr[code="'+Code_po_create+'"]').prop('checked',true);
		  				$('.C_radio_pr[code="'+Code_po_create+'"]').trigger('change');
		  				loadingEnd(500);
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

	function __getRsViewGRPO_SPB(ID_payment)
	{
		var arr=[];
		var Dataselected = ClassDt.Dataselected;
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

	function makeDomSPBView(action,ID_payment,number,se_content)
	{
		var Code = $('.C_radio_pr:checked').attr('code');
		var InvoicePO = $('.C_radio_pr:checked').attr('invoicepo');
		var InvoiceleftPO = $('.C_radio_pr:checked').attr('invoiceleftpo');
		var Supplier = $('.C_radio_pr:checked').attr('supplier');

		Supplier = Supplier.split('||');
		Supplier = Supplier[1].trim();
		var ev = se_content.closest('.FormPage');
		// var ID_payment = ev.attr('ID_payment');
		var dt_arr = __getRsViewGRPO_SPB(ID_payment);
		var dtspb = dt_arr.dtspb;
		console.log(dtspb);
		var data = ClassDt.Dataselected;
		// hitung Left PO
		var InvoiceleftPO = parseInt(InvoicePO);
		var c = 0;
		for (var i = 0; i < data.dtspb.length; i++) {
			if (ID_payment == data.dtspb[i].ID && i > 0) {
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
		var btnSPb = '<button class="btn btn-default hide print_page"> <i class="fa fa-print" aria-hidden="true"></i> Print</button> &nbsp'+
					'<button class="btn btn-primary hide btnEditInput" status="'+dtspb[0]['Status']+'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button> &nbsp'+
					'<button class="btn btn-success submit"> Submit</button>';

		if (dtspb[0]['Code'] != '' && dtspb[0]['Code'] != null) {
			var UploadInvoice = jQuery.parseJSON(dtspb[0].Detail[0]['UploadInvoice']);
			UploadInvoice = UploadInvoice[0];
			LinkFileInvoice = '<a href = "'+base_url_js+'fileGetAny/budgeting-spb-'+UploadInvoice+'" target="_blank" class = "Fileexist">File Document</a>';

			var UploadTandaTerima = jQuery.parseJSON(dtspb[0].Detail[0]['UploadTandaTerima']);
			UploadTandaTerima = UploadTandaTerima[0];
			LinkUploadTandaTerima = '<a href = "'+base_url_js+'fileGetAny/budgeting-spb-'+UploadTandaTerima+'" target="_blank" class = "Fileexist">File Document</a>';

			if (dtspb[0]['Status'] == 2) {
				btnSPb = '<button class="btn btn-default print_page"> <i class="fa fa-print" aria-hidden="true"></i> Print</button>';
			}
			else if(dtspb[0]['Status'] == 0 || dtspb[0]['Status'] == 1 || dtspb[0]['Status'] == -1)
			{
				btnSPb = '<button class="btn btn-default hide print_page"> <i class="fa fa-print" aria-hidden="true"></i> Print</button> &nbsp'+
						'<button class="btn btn-primary btnEditInput" status="'+dtspb[0]['Status']+'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button> &nbsp'+
						'<button class="btn btn-success submit" disabled> Submit</button>';
			}
			else
			{
				btnSPbs = '';
			}

			// Fill Type Pembayaran
			var TypeInvoice = dtspb[0].Detail[0]['TypeInvoice'];
		}
		else
		{
			makeDomSPBAdd('add',ID_payment,number,se_content);
			return;
		}

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
			if (dtspb[0]['Code'] != '') {
				var JsonStatus = jQuery.parseJSON(dtspb[0]['JsonStatus']);
				var bool = true;
				for (var i = 1; i < JsonStatus.length; i++) {
					if (JsonStatus[i].Status == 1) {
						bool = false;
						break;
					}
				}

				if (!bool) {
					se_content.find('button').not('.print_page').remove();
				}
				makeSignaturesSPB(se_content,JsonStatus);
				makepage_status(dt_arr,se_content);
			}
			else
			{
				se_content.find('.dtbank[tabindex!="-1"]').select2({
				    //allowClear: true
				});
			}
		// end action			

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

	$(document).off('click', '.btnEditInput').on('click', '.btnEditInput',function(e) {
		var Status = $(this).attr('status');
		if (Status != 2) {
			var ev2 = $(this).closest('.pageFormInput');
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

	function makeDomGRPOView(action,ID_payment,number,se_content)
	{
		var ev = se_content.closest('.FormPage');
		var ID_payment = ev.attr('ID_payment');
		var dt_arr = __getRsViewGRPO_SPB(ID_payment);
		var po_data = ClassDt.po_data;
		var dtspb = dt_arr.dtspb;
		console.log(dtspb);
		// get Status
		var Status = dtspb[0].Status;
		var dtgood_receipt_spb = dtspb[0].Good_Receipt;
		var html = '';
		// if (dtgood_receipt_spb.length > 0 && (number + 1) == dtgood_receipt_spb.length) {
		if (dtgood_receipt_spb.length > 0) {
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

				html += '<div class = "row"><div class = "col-xs-12"><div align="center"><h2>Good Receipt PO <button class="btn btn-warning btn-delete-grpo" ID_good_receipt_spb = "'+dtgood_receipt_spb[i].ID+'"><i class="fa fa-trash"></i> </button></h2></div>'+
						'<hr style="height:2px;border:none;color:#333;background-color:#333;margin-top: -3px;">'+
						'<button class="btn btn-default btn-add-item" disabled><i class="fa icon-plus"></i> </button>'+
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
											'<label class = "col-sm-1">Upload Document</label>'+
												'<div class="col-sm-4">'+'<input type="file" data-style="fileinput" class="BrowseDocument" id="BrowseDocument" accept="image/*,application/pdf" disabled>'+
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
											'<label class = "col-sm-1">Upload Tanda Terima</label>'+
												'<div class="col-sm-4">'+'<input type="file" data-style="fileinput" class="BrowseTTGRPO" id="BrowseTTGRPO" accept="image/*,application/pdf" disabled>'+
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
						'<div id = "r_action">'+
							'<div class="row">'+
								'<div class="col-md-12">'+
									'<div class="pull-right">'+
										'<button class="btn btn-primary btnEditInputGRPO" ID_good_receipt_spb = "'+dtgood_receipt_spb[i].ID+'" Status = "'+Status+'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button> &nbsp'+
										'<button class="btn btn-success submitGRPO" Status = "'+Status+'" disabled> Submit</button>'+
									'</div>'+
								'</div>'+
							'</div>'+
						'</div>'+												
					'</div></div></div>';
			}

			se_content.html(html);
			se_content.find('.QtyDiterima').maskMoney({thousands:'', decimal:'', precision:0,allowZero: true});
			se_content.find('.QtyDiterima').maskMoney('mask', '9894');
			se_content.find('.datetimepicker').datetimepicker({
				format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
			});	
		}
		else{
			makeDomGRPOAdd(action,ID_payment,number,se_content)
		}
	}

	$(document).off('change', '.Item').on('change', '.Item',function(e) {
		var ev = $(this).closest('.row');
		var vv = $(this).find('option:selected').val();
		ev.find('.Item').not(this).each(function(){
			$(this).find('option[value="'+vv+'"]').remove();
		})

		ev.find('.QtyDiterima').each(function(){
			$(this).trigger('keyup');
			$(this).trigger('keydown');
		})
	})

	$(document).off('click', '.btn-add-item').on('click', '.btn-add-item',function(e) {
		console.log('asdsad');
		var ev = $(this).closest('.row');
		var arr_selected = [];
		var po_data = ClassDt.po_data;
		var po_detail= po_data.po_detail;
		ev.find('.Item').each(function(){
			var v = $(this).find('option:selected').val();
			arr_selected.push(v);
		})
		console.log(arr_selected);
		// if (arr_selected.length != po_detail.length	) {
		// 	var html = OPPo_detail(null,arr_selected);
		// 	$('#page_po_item').append(html);
		// 	ev.find('.QtyDiterima').maskMoney({thousands:'', decimal:'', precision:0,allowZero: true});
		// 	ev.find('.QtyDiterima').maskMoney('mask', '9894');
		// }
		var html = OPPo_detail(null,arr_selected);
		ev.find('div[ID="page_po_item"]').append(html);
		// $('#page_po_item').append(html);
		ev.find('.QtyDiterima').maskMoney({thousands:'', decimal:'', precision:0,allowZero: true});
		ev.find('.QtyDiterima').maskMoney('mask', '9894');

		// console.log(arr_selected);
	})

	$(document).off('keyup keydown', '.QtyDiterima').on('keyup keydown', '.QtyDiterima',function(e) {
		var ev = $(this).closest('.GroupingItem');
		var QtyPR = ev.find('.Item').find('option:selected').attr('qtypr');
		// var ID_po_detail = $(this).find('option:selected').val();
		var ID_po_detail = ev.find('.Item').find('option:selected').val();
		var r = $(this).closest('.row');
		var btn = r.find('.submitGRPO');
		btn.prop('disabled',false);
		var index__ = btn.index();
		var qtyExisting = __GetQtyDiterima(ID_po_detail,index__);
		var v = $(this).val();
		v = findAndReplace(v, ".","");
		// console.log(qtyExisting);
		var sisa = parseInt(QtyPR)-parseInt(qtyExisting);
		if (sisa==0) {
			toastr.info('Qty telah mencukupi, silahkan hapus Item ini');
			btn.prop('disabled',true);
		}
		else
		{
			if (v>sisa) {
				$(this).val(sisa);
				$(this).maskMoney({thousands:'', decimal:'', precision:0,allowZero: true});
				$(this).maskMoney('mask', '9894');
				toastr.info('Tidak boleh melebihi sisa qty('+sisa+')');
			}
		}
		
	})

	function __GetQtyDiterima(ID_po_detail,index__)
	{
		var rs = 0;
		var tt = ClassDt.Dataselected;
		var dtspb = tt['dtspb'];
		for (var i = 0; i < dtspb.length; i++) {
			var Good_Receipt = dtspb[i].Good_Receipt;
			for (var j = 0; j < Good_Receipt.length; j++) {
				var dtgood_receipt_detail = Good_Receipt[j].Detail;
				for (var k = 0; k < dtgood_receipt_detail.length; k++) {
					if (ID_po_detail== dtgood_receipt_detail[k].ID_po_detail) {
						if (k != index__) {
							rs += parseInt(dtgood_receipt_detail[k].QtyDiterima);
						}
						
					}
				}
			}
		}
		// console.log(rs);
		return rs;
	}

	// $(document).off('click', '.btn-add-item').on('click', '.btn-add-item',function(e) {
	// 	var ev = $(this).closest('.FormPage');
	// 	var arr_selected = [];
	// 	var po_data = ClassDt.po_data;
	// 	var po_detail= po_data.po_detail;
	// 	ev.find('.Item').each(function(){
	// 		var v = $(this).find('option:selected').val();
	// 		arr_selected.push(v);
	// 	})
	// 	if (arr_selected.length != po_detail.length	) {
	// 		var html = OPPo_detail(null,arr_selected);
	// 		$('#page_po_item').append(html);
	// 		ev.find('.QtyDiterima').maskMoney({thousands:'', decimal:'', precision:0,allowZero: true});
	// 		ev.find('.QtyDiterima').maskMoney('mask', '9894');
	// 	}	
	// 	// console.log(arr_selected);
	// })

	$(document).off('click', '.submitGRPO').on('click', '.submitGRPO',function(e) {
		// validation
		var ev = $(this).closest('.FormPage');
		var ev2 = $(this).closest('.col-xs-12');
		var action = ev.attr('action');
		if (confirm('Are you sure?')) {
			var validation = validation_input_GRPO(ev2);
			if (validation) {
				SubmitGRPO('.submitGRPO',ev,action,ev2);
			}
		}
	})

	function validation_input_GRPO(ev)
	{
		var find = true;
		var data = {
			NoDocument : ev.find('.NoDocument').val(),
			NoTandaTerimaGRPO : ev.find('.NoTandaTerimaGRPO').val(),
			TglGRPO : ev.find('.TglGRPO').val(),
		};
		if (validation(data) ) {
			// var action = ev.attr('action');
			var ID_good_receipt_spb = ev.find('.btn-delete-grpo').attr('id_good_receipt_spb');
			if (ID_good_receipt_spb == '' || ID_good_receipt_spb == undefined || ID_good_receipt_spb == null ) {
				var action = 'add';
				ID_good_receipt_spb = '';
			}
			else
			{
				var action = 'edit';
			}
			// console.log(action)

			if (action == 'add') {
				// Upload Document
				ev.find(".BrowseDocument").each(function(){
					var IDFile = $(this).attr('id');
					var ev2 = $(this);
					if (!file_validation2(ev2,'Upload Document ') ) {
					  ev.find(".submitGRPO").prop('disabled',false);
					  find = false;
					  return false;
					}
				})

				// Upload Tanda Terima GRPO
				ev.find(".BrowseTTGRPO").each(function(){
					var IDFile = $(this).attr('id');
					var ev2 = $(this);
					if (!file_validation2(ev2,'Tanda Terima ') ) {
					  ev.find(".submitGRPO").prop('disabled',false);
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

		// check item
		var arr = [];
		ev.find('.Item').each(function(){
			var v = $(this).val();
			arr.push(v);
		})

		var arr_qty_diterima = [];
		ev.find('.QtyDiterima').each(function(){
			var v = $(this).val();
			arr_qty_diterima.push(v);
		})

		for (var i = 0; i < arr_qty_diterima.length; i++) {
			if (arr_qty_diterima[i] == 0) {
				toastr.error('Qty tidak boleh 0','!!!Error');
				find=false;
				break;
			}
		}

		for (var i = 0; i < arr.length; i++) {
			if (arr[i]=='' || arr[i]==null || arr[i]==undefined) {
				toastr.error('Item belum dipilih','!!!Error');
				find=false;
				break;

			}
			else
			{
				for (var k = i+1; k < arr.length; k++) {
					if (arr[i]==arr[k]) {
						toastr.error('Item yang dipilih tidak boleh sama','!!!Error');
						find=false;
						break;
					}
				}

				if (!find) {
					break;
				}
			}
		}
		
		return find;
	}

	function SubmitGRPO(elementbtn,ev,action="add",ev2)
	{
		loadingStart();
		var Code_po_create = $('.C_radio_pr:checked').attr('code');
		var Departement = IDDepartementPUBudget;
		var ID_payment = ev.attr('ID_payment');
		var form_data = new FormData();

		if ( ev2.find('.BrowseDocument').length ) {
			var UploadFile = ev2.find('.BrowseDocument')[0].files;
			form_data.append("FileDocument[]", UploadFile[0]);
		}
		
		if ( ev2.find('.BrowseTTGRPO').length ) {
			var UploadFile = ev2.find('.BrowseTTGRPO')[0].files;
			form_data.append("FileTandaTerima[]", UploadFile[0]);
		}

		var NoDocument = ev2.find('.NoDocument').val();
		var NoTandaTerima = ev2.find('.NoTandaTerimaGRPO').val();
		var ID_budget_left = 0;

		var arr_item = [];
		ev2.find('.Item').each(function(){
			var ID_po_detail = $(this).find('option:selected').val();
			var ev2 = $(this).closest('.GroupingItem');
			var QtyDiterima = ev2.find('.QtyDiterima').val();
			var temp = {
				ID_po_detail : ID_po_detail,
				QtyDiterima : QtyDiterima,
			}
			arr_item.push(temp);
		})

		var ID_good_receipt_spb = ev2.find('.btn-delete-grpo').attr('id_good_receipt_spb');
		if (ID_good_receipt_spb == '' || ID_good_receipt_spb == undefined || ID_good_receipt_spb == null ) {
			var action2 = 'add';
			ID_good_receipt_spb = '';
		}
		else
		{
			var action2 = 'edit';
		}

		var data = {
			Code_po_create : Code_po_create,
			Departement : Departement,
			ID_budget_left : ID_budget_left,
			NoDocument : NoDocument,
			NoTandaTerima :NoTandaTerima,
			ID_payment : ID_payment,
			action : action,
			action2 : action2,
			arr_item : arr_item,
			TglGRPO : ev2.find('.TglGRPO').val(),
			po_data : ClassDt.po_data,
			ID_good_receipt_spb : ID_good_receipt_spb,
		};
		// console.log(data);
		// return false;
		var token = jwt_encode(data,"UAP)(*");
		form_data.append('token',token);

		var data_verify = {
			Code_po_create : Code_po_create,
			InvoicePO : $('.C_radio_pr:checked').attr('invoicepo'),
			InvoiceLeftPO : $('.C_radio_pr:checked').attr('invoiceleftpo'),
		};

		var token2 = jwt_encode(data_verify,"UAP)(*");
		form_data.append('token2',token2);

		// var url = base_url_js + "budgeting/submit"
		var url = base_url_js + "budgeting/submitgrpo"
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
		  				Get_data_po().then(function(data){
		  					$('.C_radio_pr[code="'+Code_po_create+'"]').prop('checked',true);
		  					$('.C_radio_pr[code="'+Code_po_create+'"]').trigger('change');
		  					loadingEnd(500);
		  				})
		  			},1000);
		  			// load first load data
		  			
		  		}
		  		else
		  		{
		  			toastr.error("Connection Error, Please try again", 'Error!!');
		  			loadingEnd(500);
		  		}
		  	}
		  	else{
		  		toastr.success('Saved');
		  		setTimeout(function () {
		  			Get_data_po().then(function(data){
		  				$('.C_radio_pr[code="'+Code_po_create+'"]').prop('checked',true);
		  				$('.C_radio_pr[code="'+Code_po_create+'"]').trigger('change');
		  				loadingEnd(500);
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

	$(document).off('click', '.btnEditInputGRPO').on('click', '.btnEditInputGRPO',function(e) {
		var Status = $(this).attr('status');
		if (Status != 2) {
			var ev = $(this).closest('.FormPage');
			ev.find('.btn-add-grpo').prop('disabled',true);

			// var ev2 = $(this).closest('.pageFormInput');
			var ev2 = $(this).closest('.col-xs-12');
			ev2.find('input').not('.TglGRPO').prop('disabled',false);
			ev2.find('button').prop('disabled',false);
			ev2.find('select').prop('disabled',false);
			$(this).remove();
		}
		else
		{
			toastr.info('Data SPB telah approve, tidak bisa edit');
		}
		
	})

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

	$(document).off('click', '.submitBA').on('click', '.submitBA',function(e) {
		// validation
		var ev = $(this).closest('.FormPage');
		var action = ev.attr('action');
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

	function SubmitBA(elementbtn,ev,action="add")
	{
		loadingStart();
		var Code_po_create = $('.C_radio_pr:checked').attr('code');
		var Departement = IDDepartementPUBudget;
		var ID_payment = ev.attr('id_payment');
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

		var data_verify = {
			Code_po_create : Code_po_create,
			InvoicePO : $('.C_radio_pr:checked').attr('invoicepo'),
			InvoiceLeftPO : $('.C_radio_pr:checked').attr('invoiceleftpo'),
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
		  				Get_data_po().then(function(data){
		  					$('.C_radio_pr:first').prop('checked',true);
		  					$('.C_radio_pr:first').trigger('change');
		  					loadingEnd(500);
		  				})
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
		  			Get_data_po().then(function(data){
		  				$('.C_radio_pr[code="'+Code_po_create+'"]').prop('checked',true);
		  				$('.C_radio_pr[code="'+Code_po_create+'"]').trigger('change');
		  				loadingEnd(500);
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


	$(document).off('change', '.TypePay').on('change', '.TypePay',function(e) {
		console.log('asd');
		var ev = $(this).closest('.FormPage');
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

	$(document).off('click', '.btnEditInputBA').on('click', '.btnEditInputBA',function(e) {
		var Status = $(this).attr('status');
		if (Status != 2) {
			var ev2 = $(this).closest('.pageFormInput');
			ev2.find('input').not('.TglBA').prop('disabled',false);
			ev2.find('button').prop('disabled',false);
			ev2.find('select').prop('disabled',false);
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
		var ev = $(this).closest('.FormPage');
		var action = ev.attr('action');
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

	function submitCA(elementbtn,ev,action="add")
	{
		loadingStart();
		var Code_po_create = $('.C_radio_pr:checked').attr('code');
		var Departement = IDDepartementPUBudget;
		var ID_payment = ev.attr('id_payment');
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

		var data_verify = {
			Code_po_create : Code_po_create,
			InvoicePO : $('.C_radio_pr:checked').attr('invoicepo'),
			InvoiceLeftPO : $('.C_radio_pr:checked').attr('invoiceleftpo'),
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
		  				Get_data_po().then(function(data){
		  					$('.C_radio_pr:first').prop('checked',true);
		  					$('.C_radio_pr:first').trigger('change');
		  					loadingEnd(500);
		  				})
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
		  			Get_data_po().then(function(data){
		  				$('.C_radio_pr[code="'+Code_po_create+'"]').prop('checked',true);
		  				$('.C_radio_pr[code="'+Code_po_create+'"]').trigger('change');
		  				loadingEnd(500);
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

	$(document).off('click', '.btnEditInputCA').on('click', '.btnEditInputCA',function(e) {
		var Status = $(this).attr('status');
		if (Status != 2) {
			var ev2 = $(this).closest('.pageFormInput');
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

	$(document).off('click', '.btn-add-grpo').on('click', '.btn-add-grpo',function(e) {
		var ev = $(this).closest('.FormPage');
		var GRPORow = ev.find('.GRPORow');
		var se_content = GRPORow.find('.pageFormInput');
		var ID_payment = ev.attr('ID_payment');
		var number = ev.attr('number');
		// check data existing telah submit atau belum
		var dt_arr = __getRsViewGRPO_SPB(ID_payment);
		var dtspb = dt_arr.dtspb;
		if (typeof dtspb[0] !== "undefined") {
			var dtgood_receipt_spb = dtspb[0].Good_Receipt;
			if (dtgood_receipt_spb.length == $('.btn-delete-grpo').length ) {
				$(".btnEditInputGRPO").addClass('hide');
				$(".submitGRPO").addClass('hide');
				makeDomGRPOAdd('add',ID_payment,number,se_content);
			}
			else
			{
				toastr.info('Mohon untuk submit GRPO sebelumnya dahulu');
			}
		}
		else
		{
			toastr.info('Mohon untuk submit GRPO ini dahulu');
		}
		
	})

	$(document).off('click', '.btn-delete-grpo').on('click', '.btn-delete-grpo',function(e) {
		var index__ = $(this).index();
		var r = $(this).closest('.row');
		if ($('.btn-delete-grpo').length > 1  ) {
			r.remove();
			$('.btnEditInputGRPO:eq('+(parseInt(index__) - 1)+')').removeClass('hide');
			$('.submitGRPO:eq('+(parseInt(index__) - 1)+')').removeClass('hide');
		}
		else
		{
			toastr.info('Page GRPO memiliki sisa 1, aksi dibatalkan')
		}
		
	})

	$(document).off('click', '.btn-delete-item').on('click', '.btn-delete-item',function(e) {
		// console.log('asdsad');
		var ev = $(this).closest('div[id="page_po_item"]');
		// console.log(ev.find('.Item').length);
		if (ev.find('.Item').length > 1) {
			$(this).closest('.form-horizontal').remove();
		}
	})
</script>