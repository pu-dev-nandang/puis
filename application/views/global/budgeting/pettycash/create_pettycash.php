<div id="Content_entry">

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
	var ClassDt = {
		ID_payment : '',
		BudgetRemaining : [],
		Year : "<?php echo $Year ?>",
		Departement : "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>",
		NmDepartement_Existing : '',
		RuleAccess : [],
		PostBudgetDepartment : [],
		DtExisting : [],
	};

	$(document).ready(function() {
		loadingStart();
		LoadFirstLoad();
	})

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

	function LoadFirstLoad()
	{
		var ID_payment = ClassDt.ID_payment;
		__loadRuleInput().then(function(data){
			var access = data['access'];
			if (access.length > 0) {
				ClassDt.RuleAccess = data;
				var se_content = $('#Content_entry');
				makeDomHTML(se_content);
				if (ID_payment == '') {

				}
			}
			else
			{
				$("#Content_entry").empty();
				$("#Content_entry").html('<h2 align = "center">Your not authorize these modul</h2>');
			}

			loadingEnd(500);
		})

	}

	function makeDomHTML(se_content)
	{
		var html = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;">';
			html += '<div class="col-md-4">'+
						'<p id = "labelPeriod">Period : <label>'+ClassDt.Year+'/'+(parseInt(ClassDt.Year)+1 )+'</label></p>'+
						'<p id = "labelDepartment">Department : '+DivSessionName+'</p>'+
						'<p id = "Date">'+'Tanggal : <?php echo date('Y-m-d') ?>'+'</p>'+
						'<p id = "labelCode"></p>'+
						'<p id = "Status"></p>'+
					'</div>'+
					'<div class="col-md-4">'+
						'<div class="well">'+
							'<div style="margin-top: -15px">'+
								'<label>Budget Remaining</label>'+
							'</div>'+
							'<div id = "Page_Budget_Remaining">'+
								''+
							'</div>'+
						'</div>'+
					'</div>';
			html += '</div>';

        var htmlBtnAdd = '<div class = "row" style = "margin-left : 0px">'+
							'<div class = "col-md-3">'+
								'<button type="button" class="btn btn-default btn-add-item"> <i class="icon-plus"></i> Add Item</button>'+
							'</div>'+
						'</div>';

		var htmlInput = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_input">'+
							'<div class = "col-md-12">'+
								'<div class="table-responsive">'+
									'<table class="table table-bordered tableData" id ="table_input" style = "min-width: 1200px;">'+
									'<thead>'+
									'<tr>'+
										'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 15%;">BUDGET</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 30%;">DIBAYAR UNTUK</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 15%;">NOMOR ACC</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 25%;">JUMLAH RUPIAH</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">ACTION</th>'+
									'</tr></thead>'+
									'<tbody></tbody></table>'+
								'</div>'+
							'</div>'+
						  '</div>';
		var htmlgrandtotal = '<div class = "row">'+
								'<div class = "col-md-offset-6 col-md-6">'+
									'<h3 id = "phtmltotal" align = "right"> Total : '+formatRupiah(0)+'</h3>'+
								'</div>'+
							  '</div>';						  
						  
		var htmlTerbilang = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_Terbilang">'+
						  '</div>';

		var Supporting_documents = '<div class = "row" style = "margin-top : 10px;margin-left : 0px;margin-right : 0px">'+
							'<div class = "col-md-6">'+
								'<div class = "form-group">'+
									'<label>Supporting documents</label>'+
									'<input type="file" data-style="fileinput" class="BrowseFileSD" id="BrowseFileSD" multiple="" accept="image/*,application/pdf">'+
								'</div>'+
							'</div>'+
						'</div>';				  					  

		var htmlApproval = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_Approval">'+
						  '</div>';	

		var htmlButton = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_Button">'+
						  '</div>';					
			
		se_content.html(html+htmlBtnAdd+htmlInput+htmlgrandtotal+htmlTerbilang+Supporting_documents+htmlApproval+htmlButton);

		if (ClassDt.ID_payment != '') {
			makeApproval();
		}
		MakeButton();	
	}

	$(document).off('click', '.btn-add-item').on('click', '.btn-add-item',function(e) {
		// before adding row lock all input in last tr
		var row = $('#table_input tbody tr:last');
		row.find('td').find('input,select,button:not(.Detail),textarea').prop('disabled',true);
		row.find('td:eq(13)').find('button').prop('disabled',false);
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
					'<td>'+
						'<input type="text" class="form-control NomorAcc">'+
						'<label class = "lblNomorAcc"></label>'+
					'</td>'+
					'<td><input type="text" class="form-control SubTotal" value = "0" disabled></td>'+
                	action
                '</tr>';
        $('#table_input tbody').append(html);
        MakeAutoNumbering();
	}

	function MakeAutoNumbering()
	{
		var no = 1;
		$("#table_input tbody tr").each(function(){
			var a = $(this);
			a.find('td:eq(0)').html(no);
			no++;
			$(this).find('.SubTotal').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
			$(this).find('.SubTotal').maskMoney('mask', '9894');  
		})
	}

	$(document).off('click', '.SearchPostBudget').on('click', '.SearchPostBudget',function(e) {
		var ev = $(this);
		var dt = JSON.parse(localStorage.getItem("PostBudgetDepartment"));
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
		
		ClassDt.BudgetRemaining = BudgetRemaining_arr;
		MakeTableRemaining();

		// write Grand total
		$('#phtmltotal').html('Total : '+formatRupiah(GrandTotal));
		// make terbilang
		_ajax_terbilang(GrandTotal).then(function(data){
			$('#Page_Terbilang').html('<div class = "col-xs-12"><label>Terbilang (Rupiah) : '+data+' Rupiah</label></div>');
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

	$(document).off('click', '.btn-delete-item').on('click', '.btn-delete-item',function(e) {
		var tr = $(this).closest('tr');
		tr.remove();
		MakeAutoNumbering();
		var row = $('#table_input tbody tr:last');
		row.find('input,button').prop('disabled',false);
		row.find('td:eq(5)').find('button').prop('disabled',false);
		__BudgetRemaining(); 
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

	function makeApproval()
	{
		var DtExisting = ClassDt.DtExisting;
		var data = DtExisting;
		var JsonStatus = jQuery.parseJSON(data[0].JsonStatus);

		/* Page_Approval */
		// only admin & Finance to custom approval
			// console.log(JsonStatus);
			var html_add_approver = '';
			var bool = false;
			if (data[0].CreatedBy == NIP) {
				bool = true;
			}

			if (bool || DivSession == 'NA.9') { // NA.9 Finance
				html_add_approver = '<a href = "javascript:void(0)"  class="btn btn-default btn-default-success" type="button" id = "add_approver" ID_payment = "'+ClassDt.ID_payment+'">'+
                        			'<i class="fa fa-plus-circle" aria-hidden="true"></i>'+
                    		'</a>';
			}

			var html = '<div class = "col-md-6 col-md-offset-6"><div class = "table-responsive">'+
		    				html_add_approver+
							'<table class = "table table-striped table-bordered table-hover table-checkable tableApproval" style = "margin-top : 5px">'+
								'<thead><tr>';

				// html += '<th>'+'Created by'+'</th>';
				for (var i = 0; i < JsonStatus.length; i++) {
					html += '<th>'+JsonStatus[i].NameTypeDesc+'</th>';
				}
				html +=	'</th></thead>'+'<tbody><tr style = "height : 51px">';

				// html += '<td>'+'<i class="fa fa-check" style="color: green;"></i>'+'</td>'
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
					html += '<td>'+v+'</td>';		
				}
				html += '</tr><tr>';
				// html += '<td>'+data[0].NameCreatedBy+'</td>';
				for (var i = 0; i < JsonStatus.length; i++) {
					html += '<td>'+JsonStatus[i].NameAprrovedBy+'</td>';		
				}
				html +=	'</tr></tbody>'+'</table></div></div>';
				$('#Page_Approval').html(html);
	}

	function MakeButton()
	{
		var dt = ClassDt.RuleAccess;
		if (ClassDt.ID_payment != '') { 
			var DtExisting = ClassDt.DtExisting;
			var dataa = DtExisting;
			if (dataa[0].Status == 3) {
				var html = '<div class = "col-md-6 col-md-offset-6" align = "right">'+
							'<button class = "btn btn-success" id = "SaveSubmit" ID_payment = "'+ClassDt.ID_payment+'" action = "1">Submit</button>'+
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
					$('#Page_Button').html(html);
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
							btn_edit = '<button class = "btn btn-primary" id = "btnEditInput"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>&nbsp<button class = "btn btn-success" id = "SaveSubmit" ID_payment = "'+ClassDt.ID_payment+'" action = "1" disabled>Submit</button>&nbsp';
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

				$("#Page_Button").html(html);
			}
			else
			{
				if (dataa[0].Status == 2) {
					var html = '<div class = "col-md-12">'+
				   							'<div class = "pull-right">'+
				   								'<button class="btn btn-default" id="pdfprint" ID_payment = "'+ClassDt.ID_payment+'"> <i class = "fa fa-file-pdf-o"></i> Print PDF</button>'+
				   							'</div>'+
				   						'</div>';
				   	$("#Page_Button").html(html);
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
						'<button class = "btn btn-success" id = "SaveSubmit" id_dataa = "" ID_payment = "" action = "1">Submit</button>'+
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
				$('#Page_Button').html(html);
			}
			else
			{
				// check rule entry
				$('.btn-add-item,input[type="file"],.btn-delete-file').prop('disabled',true);
			}		   
		}
		
	}
</script>