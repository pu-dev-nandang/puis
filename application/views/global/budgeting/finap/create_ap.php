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

	.TD1 {
		width: 35%;
	}

	.TD2 {
		width: 5%;
	}	
</style>
<div class="row">
	<div class="col-xs-8 col-md-offset-2" style="min-width: 600px;overflow: auto;">
		<div class="thumbnail">
			<div id = "page_payment_list"></div>
		</div>	
	</div>
</div>
<div id="page_content" style="min-width: 800px;overflow: auto;">

</div>
<script type="text/javascript">
	var Onetime = 0;
	var ClassDt = {
		htmlPage_payment_list : function(){
			var html = '';
			html = '<div class = "row" style = "margin-right : 0px;margin-left:0px;">'+
					 '<div class col-md-12>'+
					 	'<div style="padding: 5px;">'+
					 		'<h3 class="header-blue">Choose Payment</h3>'+
					 	'</div>'+
					 	'<div class = "table-responsive">'+
					 	'<table class="table table-bordered datatable2" id = "tableData_payment">'+
					 		'<thead>'+
					 			'<tr>'+
					 				'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
					 				'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Payment</th>'+
					 				'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Department</th>'+
					 				'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Status</th>'+
					 				'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Info</th>'+
					 			'</tr>'+
					 		'<thead>'+
					 		'<tbody id="dataRow"></tbody>'+
		        		'</table>'+
		        		'</div>'+
		        	 '</div>'+
		        	'</div>';

		    return html;    	 				
		},
		po_data : [],
		po_payment_data : [],
		all_po_payment : [],
	};

	$(document).ready(function() {
		var IDDepartementPUBudget = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";
		if (IDDepartementPUBudget != 'NA.9') {
			$('#page_payment_list').html('<h2 align="center">Your are not authorize</h2>');
			loadingEnd(500);
		}
		else
		{
			$('#page_payment_list').html(ClassDt.htmlPage_payment_list);
			Get_data_payment().then(function(data){
				$('.C_radio:first').prop('checked',true);
				$('.C_radio:first').trigger('change');
				loadingEnd(500);
			})
		}
	});

	function Get_data_payment(){
       var def = jQuery.Deferred();
       var UriSegment = "<?php echo $sget ?>";
       var data = {
   		   auth : 's3Cr3T-G4N',
       };
       var token = jwt_encode(data,"UAP)(*");
       if (UriSegment != '' && UriSegment != null) {
       	var tokenSearch = jwt_decode(UriSegment,"UAP)(*");
       	if (Onetime == 0) {
   		       	var table = $('#tableData_payment').DataTable({
   		       		"fixedHeader": true,
   		       	    "processing": true,
   		       	    "destroy": true,
   		       	    "serverSide": true,
   		       	    "lengthMenu": [[5], [5]],
   		       	    "oSearch": {"sSearch": tokenSearch},
   		       	    "iDisplayLength" : 5,
   		       	    "ordering" : false,
   		       	    "ajax":{
   		       	        url : base_url_js+"rest2/__get_data_payment", // json datasource
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
   		    	    	       var ListPR = data[parseInt(data.length) - 1];
   		    	    	       var PRHTML = '';
   		    	    	       PRHTML += ListPR[0];
   		    	    	       var ID_payment = ListPR[1].ID_payment;
   		    	    	       var CodeSPB = ListPR[1].CodeSPB;
   		    	    	       var TypePay = ListPR[1].TypePay;
   		    	    	       var Perihal = ListPR[1].Perihal;
   		    	    	       var Code_po_create = '';
   		    	    	       if (data[1] != null && data[1] != '') {
   		    	    	       	var Code_po_create = data[1];
   		    	    	       }

   		    	    	       var input_radio = '<input class="C_radio" type="radio" name="optradio" id_payment="'+ID_payment+'" TypePay = "'+TypePay+'" CodeSPB= "'+CodeSPB+'" Code_po_create= "'+Code_po_create+'" PRCode= "'+PRHTML+'" >';
   		    	    	       var Payment = input_radio + ' Type : '+TypePay;
   		    	    	       if (TypePay == 'Spb') {
   		    	    	       	Payment += '<br><a href="javascript:void(0)">Code : '+CodeSPB+'</a>';
   		    	    	       }
   		    	    	      if (Code_po_create != '') {
   		    	    	      	 Payment += '<br><label> PO/SPK Code : '+Code_po_create+'</label>';
   		    	    	      }
   		    	    	      if (Code_po_create != '') {
   		    	    	      	 Payment += '<br>PR Code : '+PRHTML;
   		    	    	      }

   		    	    	       Payment += '<p style = "color : red;">Perihal : '+Perihal+'</p>';
   		    	    	       Payment += 'Created : '+data[parseInt(data.length) - 2];
   		    	    	       
   		    	    	       $( row ).find('td:eq(1)').html(Payment);
   			    		    	
   			    		    	$( row ).find('td:eq(2)').attr('align','center');
   			    		    	$( row ).find('td:eq(4)').attr('align','center');
   			    		    	$( row ).find('td:eq(4)').html('<a href="javascript:void(0)" class="btn btn-info btn_circulation_sheet" id_payment="'+ID_payment+'">Info</a>');
   		    	    },
   		       	    "initComplete": function(settings, json) {
   		       	    	// $('.C_radio:first').prop('checked', true);
   		       	    	// $('.C_radio:checked').trigger('change');
   		       	    	// $('.C_radio:first').trigger('click');
   		       	        def.resolve(json);
   		       	    }
   		       	});
       		Onetime++;       	
       	}
       	else
       	{
		       	var table = $('#tableData_payment').DataTable({
		       		"fixedHeader": true,
		       	    "processing": true,
		       	    "destroy": true,
		       	    "serverSide": true,
		       	    "lengthMenu": [[5], [5]],
		       	    "oSearch": {"sSearch": tokenSearch},
		       	    "iDisplayLength" : 5,
		       	    "ordering" : false,
		       	    "ajax":{
		       	        url : base_url_js+"rest2/__get_data_payment", // json datasource
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
		    	    	       var ListPR = data[parseInt(data.length) - 1];
		    	    	       var PRHTML = '';
		    	    	       PRHTML += ListPR[0];
		    	    	       var ID_payment = ListPR[1].ID_payment;
		    	    	       var CodeSPB = ListPR[1].CodeSPB;
		    	    	       var TypePay = ListPR[1].TypePay;
		    	    	       var Perihal = ListPR[1].Perihal;
		    	    	       var Code_po_create = '';
		    	    	       if (data[1] != null && data[1] != '') {
		    	    	       	var Code_po_create = data[1];
		    	    	       }

		    	    	       var input_radio = '<input class="C_radio" type="radio" name="optradio" id_payment="'+ID_payment+'" TypePay = "'+TypePay+'" CodeSPB= "'+CodeSPB+'" Code_po_create= "'+Code_po_create+'" PRCode= "'+PRHTML+'" >';
		    	    	       var Payment = input_radio + ' Type : '+TypePay;
		    	    	       if (TypePay == 'Spb') {
		    	    	       	Payment += '<br><a href="javascript:void(0)">Code : '+CodeSPB+'</a>';
		    	    	       }
		    	    	      if (Code_po_create != '') {
		    	    	      	 Payment += '<br><label> PO/SPK Code : '+Code_po_create+'</label>';
		    	    	      }
		    	    	      if (Code_po_create != '') {
		    	    	      	 Payment += '<br>PR Code : '+PRHTML;
		    	    	      }

		    	    	       Payment += '<p style = "color : red;">Perihal : '+Perihal+'</p>';
		    	    	       Payment += 'Created : '+data[parseInt(data.length) - 2];
		    	    	       
		    	    	       $( row ).find('td:eq(1)').html(Payment);
			    		    	
			    		    	$( row ).find('td:eq(2)').attr('align','center');
			    		    	$( row ).find('td:eq(4)').attr('align','center');
			    		    	$( row ).find('td:eq(4)').html('<a href="javascript:void(0)" class="btn btn-info btn_circulation_sheet" id_payment="'+ID_payment+'">Info</a>');
		    	    },
		       	    "initComplete": function(settings, json) {
		       	    	// $('.C_radio:first').prop('checked', true);
		       	    	// $('.C_radio:checked').trigger('change');
		       	    	// $('.C_radio:first').trigger('click');
		       	        def.resolve(json);
		       	    }
		       	});
       	}

       }
       else
       {
   	       	var table = $('#tableData_payment').DataTable({
   	       		"fixedHeader": true,
   	       	    "processing": true,
   	       	    "destroy": true,
   	       	    "serverSide": true,
   	       	    "lengthMenu": [[5], [5]],
   	       	    "oSearch": {"sSearch": tokenSearch},
   	       	    "iDisplayLength" : 5,
   	       	    "ordering" : false,
   	       	    "ajax":{
   	       	        url : base_url_js+"rest2/__get_data_payment", // json datasource
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
   	    	    	       var ListPR = data[parseInt(data.length) - 1];
   	    	    	       var PRHTML = '';
   	    	    	       PRHTML += ListPR[0];
   	    	    	       var ID_payment = ListPR[1].ID_payment;
   	    	    	       var CodeSPB = ListPR[1].CodeSPB;
   	    	    	       var TypePay = ListPR[1].TypePay;
   	    	    	       var Perihal = ListPR[1].Perihal;
   	    	    	       var Code_po_create = '';
   	    	    	       if (data[1] != null && data[1] != '') {
   	    	    	       	var Code_po_create = data[1];
   	    	    	       }

   	    	    	       var input_radio = '<input class="C_radio" type="radio" name="optradio" id_payment="'+ID_payment+'" TypePay = "'+TypePay+'" CodeSPB= "'+CodeSPB+'" Code_po_create= "'+Code_po_create+'" PRCode= "'+PRHTML+'" >';
   	    	    	       var Payment = input_radio + ' Type : '+TypePay;
   	    	    	       if (TypePay == 'Spb') {
   	    	    	       	Payment += '<br><a href="javascript:void(0)">Code : '+CodeSPB+'</a>';
   	    	    	       }
   	    	    	      if (Code_po_create != '') {
   	    	    	      	 Payment += '<br><label> PO/SPK Code : '+Code_po_create+'</label>';
   	    	    	      }
   	    	    	      if (Code_po_create != '') {
   	    	    	      	 Payment += '<br>PR Code : '+PRHTML;
   	    	    	      }

   	    	    	       Payment += '<p style = "color : red;">Perihal : '+Perihal+'</p>';
   	    	    	       Payment += 'Created : '+data[parseInt(data.length) - 2];
   	    	    	       
   	    	    	       $( row ).find('td:eq(1)').html(Payment);
   		    		    	
   		    		    	$( row ).find('td:eq(2)').attr('align','center');
   		    		    	$( row ).find('td:eq(4)').attr('align','center');
   		    		    	$( row ).find('td:eq(4)').html('<a href="javascript:void(0)" class="btn btn-info btn_circulation_sheet" id_payment="'+ID_payment+'">Info</a>');
   	    	    },
   	       	    "initComplete": function(settings, json) {
   	       	    	// $('.C_radio:first').prop('checked', true);
   	       	    	// $('.C_radio:checked').trigger('change');
   	       	    	// $('.C_radio:first').trigger('click');
   	       	        def.resolve(json);
   	       	    }
   	       	});
       }
       	
       return def.promise();
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

	$(document).off('change', '.C_radio:checked').on('change', '.C_radio:checked',function(e) {
		var TypePay = $(this).attr('TypePay');
		var ID_payment = $(this).attr('ID_payment');
		var CodeSPB = $(this).attr('CodeSPB');
		var Code_po_create = $(this).attr('Code_po_create');
		var PR = $(this).attr('prcode');
		ClassDt.all_po_payment = [];
		ClassDt.po_payment_data = [];
		ClassDt.po_data = [];
		if (Code_po_create != '' && Code_po_create != null) {
			Get_data_spb_grpo(Code_po_create).then(function(data){
				ClassDt.all_po_payment = data;
				var dt_arr = __getRsViewGRPO_SPB(ID_payment,data);
				ClassDt.po_payment_data = dt_arr;
				Get_data_detail_po(Code_po_create).then(function(data){
					ClassDt.po_data = data;
					MakeDomHtml(ID_payment,TypePay,CodeSPB,Code_po_create,PR);
				})
			})
			
		}
		else
		{
			
			__load_data_payment(ID_payment).then(function(data){
				ClassDt.po_payment_data = data;
				MakeDomHtml(ID_payment,TypePay,CodeSPB,Code_po_create,PR);
			})
			
		}
		
	})

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

	function __showHistory(ID_payment_ori,Code_po_create,PR)
	{
		var html = '';
		var all_po_payment = ClassDt.all_po_payment;
		var index__ = 0;
		var dtspb_all = all_po_payment.dtspb;
		for (var i = 0; i < dtspb_all.length; i++) {
			if (dtspb_all[i].ID == ID_payment_ori) {
				index__ = i - 1;
				break;
			}
		}
		
		if (index__ >= 0) {
			for (var z = 0; z < dtspb_all.length; z++) {
				var ID_payment = dtspb_all[z].ID;
				if (ID_payment == ID_payment_ori) {
					break;
				}
				var dt_arr = __getRsViewGRPO_SPB(ID_payment,all_po_payment);
				var po_payment_data = dt_arr;
				var dtspb = po_payment_data.dtspb;
				html += '<div class ="row" style ="margin-top:30px;">'+
								'<div class = "col-xs-8 col-md-offset-2" style = "min-width: 600px;overflow: auto;">'+
									'<div class="well" style = "background-color:#f5f0f0;">'+
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
							'<td class = "TD1"><label>No Giro</label></td>'+
							'<td>:</td>'+
							'<td><label>'+dtspb[0].FinanceAP[0].NoVoucher+'</label></td>'+
						'</tr>'+
						'<tr>'+
							'<td><label>Upload Voucher</label></td>'+
							'<td>:</td>'+
							'<td>'+htmlUploadVoucher+'</td>'+
						'</tr>';

				html += '</tbody></table>';
				html += '</div></div></div>';	
			}
			
		}

		return html;
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

	function MakeDomHtml(ID_payment,TypePay,CodeSPB,Code_po_create,PR)
	{
		var html = '';
		if (PR != '' && PR != null) {
			html += __showHistory(ID_payment,Code_po_create,PR);
		}
		
		html += '<div class ="row FormPage" style ="margin-top:30px;">'+
						'<div class = "col-xs-8 col-md-offset-2" style = "min-width: 600px;overflow: auto;">'+
							'<div class="well">'+
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
		// console.log('*'+TypePay+'*');		
		// console.log('*'+FolderPayment+'*');		
		var po_payment_data = ClassDt.po_payment_data;
		// check for document invoice
		if (typeof po_payment_data.dtspb !== "undefined") {
			var dtspb = po_payment_data.dtspb;
		}
		else
		{
			var dtspb = po_payment_data.payment;
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

		html += '<tr>'+
					'<td class = "TD1"><label>No Giro</label></td>'+
					'<td>:</td>'+
					'<td><input type ="text" class = "form-control NoVoucher" style = "width : 350px;"></td>'+
				'</tr>'+
				'<tr>'+
					'<td><label>Upload Voucher</label></td>'+
					'<td>:</td>'+
					'<td><input type="file" data-style="fileinput" class="BrowseVoucher" id="BrowseVoucher" accept="image/*,application/pdf"></td>'+
				'</tr>';

		html += '</tbody></table>';
		html += '<div id = "r_action">'+
						'<div class="row">'+
							'<div class="col-md-12">'+
								'<div class="pull-right">'+
									'<button class="btn btn-inverse" id="Reject" action="reject" ID_payment = "'+ID_payment+'">Reject</button>'+
									'&nbsp'+
									'<button class="btn btn-success" id = "Paid" ID_payment = "'+ID_payment+'"> Paid</button>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>';		
		html += '</div></div></div>';		

		se_content.html(html);

		// berikan auto number
		var s = 1; 
		$('.payment_number').each(function(){
			$(this).html('Payment ('+s+')');
			s++;
		})		

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


	$(document).off('click', '#Reject').on('click', '#Reject',function(e) {
		var ID_payment = $(this).attr('ID_payment');
		if (confirm('Are you sure ?')) {
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

				var url = base_url_js + 'rest2/__reject_payment_from_fin';
				var data = {
					ID_payment : ID_payment,
					NIP : sessionNIP,
					auth : 's3Cr3T-G4N',
					NoteDel : NoteDel,
				}

				var token = jwt_encode(data,"UAP)(*");
				$.post(url,{ token:token },function (resultJson) {
					var rs = resultJson;
					$('#page_content').empty();
					if (rs.Status == 1) {
						$('#page_payment_list').html(ClassDt.htmlPage_payment_list);
						Get_data_payment().then(function(data){
							$('.C_radio:first').prop('checked',true);
							$('.C_radio:first').trigger('change');
							loadingEnd(500);
						})
						toastr.success('Payment telah berhasil di reject');
					}
					else
					{
						if (rs.Change == 1) {
							toastr.info('The Data already have updated by another person,Please check !!!');
							$('#page_payment_list').html(ClassDt.htmlPage_payment_list);
							Get_data_payment().then(function(data){
								$('.C_radio:first').prop('checked',true);
								$('.C_radio:first').trigger('change');
								loadingEnd(500);
							})
						}
						else
						{
							toastr.error(rs.msg,'!!!Failed');
						}
					}
					$('#NotificationModal').modal('hide');
					$('html, body').animate({ scrollTop: $(".panel-primary:first").offset().top }, 'slow');
				}).fail(function() {
				  toastr.error('The Database connection error, please try again', 'Failed!!');
				  $('#NotificationModal').modal('hide');
				}).always(function() {

				});
			})	
		}

	})


	$(document).off('click', '#Paid').on('click', '#Paid',function(e) {
		var ID_payment = $(this).attr('ID_payment');
		if (confirm('Are you sure ?')) {
			var validation = __validation();
			if (validation) {
				loadingStart();
				var form_data = new FormData();
				var UploadFile = $('.BrowseVoucher')[0].files;
				form_data.append("UploadVoucher[]", UploadFile[0]);

				var url = base_url_js + 'rest2/__paid_payment_from_fin';
				var data = {
					ID_payment : ID_payment,
					NoVoucher : $('.NoVoucher').val(),
					NIP : sessionNIP,
					auth : 's3Cr3T-G4N',
					po_data : ClassDt.po_data,
					po_payment_data : ClassDt.po_payment_data,
				}

				var token = jwt_encode(data,"UAP)(*");
				form_data.append('token',token);
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
				  	$('#page_content').empty();
				  	var rs = data;
				  	if (rs.Status == 1) {
				  		$('#page_payment_list').html(ClassDt.htmlPage_payment_list);
				  		Get_data_payment().then(function(data){
				  			$('.C_radio:first').prop('checked',true);
				  			$('.C_radio:first').trigger('change');
				  			loadingEnd(500);
				  		})
				  		toastr.success('Payment telah berhasil dibayarkan');
				  	}
				  	else
				  	{
				  		if (rs.Change == 1) {
				  			toastr.info('The Data already have updated by another person,Please check !!!');
				  			$('#page_payment_list').html(ClassDt.htmlPage_payment_list);
				  			Get_data_payment().then(function(data){
				  				$('.C_radio:first').prop('checked',true);
				  				$('.C_radio:first').trigger('change');
				  				loadingEnd(500);
				  			})
				  		}
				  		else
				  		{
				  			toastr.error(rs.msg,'!!!Failed');
				  		}
				  	}
				  	$('#NotificationModal').modal('hide');
				  	$('html, body').animate({ scrollTop: $(".panel-primary:first").offset().top }, 'slow');
				  },
				  error: function (data) {
				    toastr.error("Connection Error, Please try again", 'Error!!');
				    loadingEnd(500);
				  }
				})
			}
			
		}

	})

	function __validation()
	{
		var find = true;
		var NoVoucher = $('.NoVoucher').val();
		var toatString = "";
		var result = "";
		result = Validation_required(NoVoucher,'No Voucher');
		if (result['status'] == 0) {
		  toatString += result['messages'] + "<br>";
		}

		// check file
		$(".BrowseVoucher").each(function(){
			var IDFile = $(this).attr('id');
			var ev2 = $(this);
			if (!file_validation2(ev2,'Upload Dokumen') ) {
			  find = false;
			  return false;
			}
		})

		if (toatString != "" || (!find) ) {
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
											'<label class = "col-sm-2">No Giro</label>'+	
												'<div class="col-sm-4">'+'<input type = "text" class = "form-control NoDocument" placeholder = "Input No Giro...." value="'+dtgood_receipt_spb[i]['NoDocument']+'" disabled><br>'+
												'<a href = "'+base_url_js+'fileGetAny/budgeting-grpo-'+FileDocument+'" target="_blank" class = "Fileexist">File Voucher</a>'+
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

	
</script>