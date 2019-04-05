<div class="row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "dtContent">
	
</div>
<script type="text/javascript">
	var ClassDt = {
		BudgetRemaining : [],
		PRCodeVal : "<?php echo $PRCodeVal ?>",
		Year : "<?php echo $Year ?>",
		RuleAccess : [],
		PostBudgetDepartment : [],
	};

	var S_Table_example_budget = '';
	var S_Table_example_catalog = '';
	var S_Table_example_combine = '';

	$(document).ready(function() {
		LoadFirstLoad();
	})

	function LoadFirstLoad()
	{
		// check Rule for Input
		var url = base_url_js+"budgeting/checkruleinput";
		var data = {
			NIP : NIP,
		};
		if (ClassDt.PRCodeVal != '') {
			data = {
				NIP : NIP,
				Departement : DivSession,
				PRCodeVal : ClassDt.PRCodeVal,
			};
		}
		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{ token:token },function (resultJson) {
			var response = jQuery.parseJSON(resultJson);

			var access = response['access'];
			if (access.length > 0) {
				ClassDt.RuleAccess = response;
				load_htmlPR();
			}
			else
			{
				$("#pageContent").empty();
				$("#pageContent").html('<h2 align = "center">Your not authorize these modul</h2>');
			}
			
		})
	}

	function load_htmlPR()
	{
		// check data edit or new
		if (ClassDt.PRCodeVal != '') {
			// edit
		}
		else
		{
			// Load Budget Department
			var Year = ClassDt.Year;
			var Departement = DivSession;
			var url = base_url_js+"budgeting/detail_budgeting_remaining";
			var data = {
					    Year : Year,
						Departement : Departement,
					};
			var token = jwt_encode(data,'UAP)(*');
			$.post(url,{token:token},function (resultJson) {
				var response = jQuery.parseJSON(resultJson);
				ClassDt.PostBudgetDepartment = response.data;
				localStorage.setItem("PostBudgetDepartment", JSON.stringify(ClassDt.PostBudgetDepartment));
				// new
				makeDomAwal();
			}).fail(function() {
			  toastr.info('No Result Data'); 
			}).always(function() {
			                
			});
			
		}
	}

	function makeDomAwal()
	{
		var html = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;">';
			html += '<div class="col-md-4">'+
						'<p id = "labelPeriod">Period : <label>'+ClassDt.Year+'/'+(parseInt(ClassDt.Year)+1 )+'</label></p>'+
						'<p id = "labelDepartment">Department : '+DivSessionName+'</p>'+
						'<p id = "labelPrcode"></p>'+
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
								'<button type="button" class="btn btn-default btn-add-pr"> <i class="icon-plus"></i> Add</button>'+
							'</div>'+
						'</div>';
		var htmlInputPR = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_PR">'+
							'<div class = "col-md-12">'+
								'<div class="table-responsive">'+
									'<table class="table table-bordered tableData" id ="table_input_pr">'+
									'<thead>'+
									'<tr>'+
										'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Budget</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Catalog</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Desc</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Spec Add</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Need</th>'+
			                            '<th width = "4%" style = "text-align: center;background: #20485A;color: #FFFFFF;">Qty</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Unit Cost</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">PPH(%)</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Sub Total</th>'+
			                            '<th width = "150px" style = "text-align: center;background: #20485A;color: #FFFFFF;">Date Needed</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Upload Files</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Combine Budget</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Action</th>'+
									'</tr></thead>'+
									'<tbody></tbody></table>'+
								'</div>'+
							'</div>'+
						  '</div>';
		var Notes = 	'<div class = "row" style = "margin-top : 10px;margin-left : 0px;margin-right : 0px">'+
							'<div class = "col-md-6">'+
								'<div class = "form-group">'+
									'<label>Note</label>'+
									'<textarea id= "Notes" class = "form-control" rows = "4"></textarea>'+
								'</div>'+
							'</div>'+
							'<div class = "col-md-6">'+
								'<h3 id = "phtmltotal" align = "right"> Total : '+formatRupiah(0)+'</h3>'+
							'</div>'+
						'</div>';

		var Supporting_documents = '<div class = "row" style = "margin-top : 10px;margin-left : 0px;margin-right : 0px">'+
							'<div class = "col-md-6">'+
								'<div class = "form-group">'+
									'<label>Supporting documents</label>'+
									'<input type="file" data-style="fileinput" class="BrowseFileSD" id="BrowseFileSD" multiple="" accept="image/*,application/pdf">'+
								'</div>'+
							'</div>'+
						'</div>';					  

		var htmlInputFooter = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_Footer">'+
							Notes+Supporting_documents+
						  '</div>';

		var htmlApproval = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_Approval">'+
						  '</div>';	

		var htmlButton = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_Button">'+
						  '</div>';

		$('#dtContent').html(html+htmlBtnAdd+htmlInputPR+htmlInputFooter+htmlApproval+htmlButton);	

	}

	$(document).off('click', '.btn-add-pr').on('click', '.btn-add-pr',function(e) {
		// before adding row lock all input in last tr
		var row = $('#table_input_pr tbody tr:last');
		row.find('td').find('input,select,button,textarea').prop('disabled',true);
		row.find('td:eq(13)').find('button').prop('disabled',false);
		AddingTable();
	})

	$(document).off('click', '.btn-delete-item').on('click', '.btn-delete-item',function(e) {
		var tr = $(this).closest('tr');
		tr.remove();
		MakeAutoNumbering();
		var row = $('#table_input_pr tbody tr:last');
		row.find('td').find('input,select,button,textarea').prop('disabled',false);
		row.find('td:eq(13)').find('button').prop('disabled',false);
		__BudgetRemaining(); 
	})	

	function AddingTable()
	{
		action = '<td><button type="button" class="btn btn-danger btn-delete btn-delete-item"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>';
		var html = '<tr>'+
					'<td></td>'+
					'<td>'+
						'<div class="input-group">'+
							'<input type="text" class="form-control PostBudgetItem" readonly>'+
							'<span class="input-group-btn">'+
								'<button class="btn btn-default SearchPostBudget" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
							'</span>'+
						'</div>'+
					'</td>'+
					'<td>'+
						'<div class="input-group">'+
							'<input type="text" class="form-control Item" readonly>'+
							'<span class="input-group-btn">'+
								'<button class="btn btn-default SearchItem" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
							'</span>'+
						'</div>'+
					'</td>'+
					'<td><button class = "btn btn-primary Detail">Detail</button></td>'+
					'<td>'+
						'<textarea class = "form-control SpecAdd" rows = "2"></textarea>'+
					'</td>'+
					'<td>'+
						'<textarea class = "form-control Need" rows = "2"></textarea>'+
					'</td>'+
					'<td><input type="number" min = "1" class="form-control qty"  value="1" disabled></td>'+
					'<td><input type="text" class="form-control UnitCost" disabled></td>'+
					'<td><input type="number" class="form-control PPH" value = "10"></td>'+
					'<td><input type="text" class="form-control SubTotal" disabled value = "0"></td>'+
					'<td>'+
						'<div class="input-group input-append date datetimepicker">'+
                            '<input data-format="yyyy-MM-dd" class="form-control" type=" text" readonly="" value = "<?php echo date('Y-m-d') ?>">'+
                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
                		'</div>'+
                	'</td>'+
                	'<td><input type="file" data-style="fileinput" class = "BrowseFile" multiple accept="image/*,application/pdf"></td>'+
                	'<td>No</td>'+
                	action
                '</tr>';
        $('#table_input_pr tbody').append(html);

        MakeAutoNumbering();        	
	}

	function MakeAutoNumbering()
	{
		var no = 1;
		$("#table_input_pr tbody tr").each(function(){
			var a = $(this);
			a.find('td:eq(0)').html(no);
			no++;
		})
	}

	$(document).off('click', '.SearchPostBudget').on('click', '.SearchPostBudget',function(e) {
		var ev = $(this);
		var dt = ClassDt.PostBudgetDepartment;
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
			fillItem.find('td:eq(1)').find('.PostBudgetItem').attr('id_budget_left',id_budget_left);
			fillItem.find('td:eq(1)').find('.PostBudgetItem').attr('remaining',money);
			fillItem.find('td:eq(6)').find('.qty').trigger('change');
			$('#GlobalModalLarge').modal('hide');
		} );
	})

	$(document).off('click', '.SearchItem').on('click', '.SearchItem',function(e) {
		var ev = $(this);
		var html = '';
			html ='<div class = "row">'+
					'<div class = "col-md-12">'+
						'<table id="example_catalog" class="table table-bordered display select" cellspacing="0" width="100%">'+
               '<thead>'+
                  '<tr>'+
                     '<th>No</th>'+
                     '<th>Item</th>'+
                     '<th>Desc</th>'+
                     '<th>Estimate Value</th>'+
                     '<th>Photo</th>'+
                     '<th>DetailCatalog</th>'+
                  '</tr>'+
               '</thead>'+
          '</table></div></div>';
			$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Catalog'+'</h4>');
			$('#GlobalModalLarge .modal-body').html(html);
			$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');
			$('#GlobalModalLarge').modal({
			    'show' : true,
			    'backdrop' : 'static'
			});

			var url = base_url_js+'rest/Catalog/__Get_Item';
			var data = {
				action : 'choices',
				auth : 's3Cr3T-G4N',
				department : DivSession,
				approval : 1,
			};
		    var token = jwt_encode(data,"UAP)(*");
			var table = $('#example_catalog').DataTable({
			      'ajax': {
			         'url': url,
			         'type' : 'POST',
			         'data'	: {
			         	token : token,
			         },
			         dataType: 'json'
			      },
			      'columnDefs': [{
			         'targets': 0,
			         'searchable': false,
			         'orderable': false,
			         'className': 'dt-body-center',
			         'render': function (data, type, full, meta){
			             return '';
			         }
			      }],
			      'createdRow': function( row, data, dataIndex ) {
			      		$(row).attr('id_m_catalog', data[6]);
			      		$(row).attr('estprice', data[7]);
			      	
			      },
			      // 'order': [[1, 'asc']]
			   });

		table.on( 'order.dt search.dt', function () {
		        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		            cell.innerHTML = i+1;
		        } );
		    } ).draw();
		S_Table_example_catalog = table;

		S_Table_example_catalog.on( 'click', 'tr', function (e) {
			var row = $(this);
			var fillItem = ev.closest('tr');
			var id_m_catalog = row.attr('id_m_catalog');
			var estprice = row.attr('estprice');
			var n = estprice.indexOf(".");
			estprice = estprice.substring(0, n);

			var Item = row.find('td:eq(1)').text();
			var Desc = row.find('td:eq(2)').text();
			var Est = row.find('td:eq(3)').text();
			var Photo = row.find('td:eq(4)').html();
			var DetailCatalog =  row.find('td:eq(5)').html();
			var arr = Item+'@@'+Desc+'@@'+Est+'@@'+Photo+'@@'+DetailCatalog;
			
			fillItem.find('td:eq(2)').find('.Item').val(Item);
			fillItem.find('td:eq(2)').find('.Item').attr('id_m_catalog',id_m_catalog);
			fillItem.find('td:eq(2)').find('.Item').attr('estprice',estprice);
			fillItem.find('td:eq(3)').find('.Detail').attr('data',arr);
			fillItem.find('td:eq(7)').find('.UnitCost').val(estprice);
			fillItem.find('td:eq(7)').find('.UnitCost').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
			fillItem.find('td:eq(7)').find('.UnitCost').maskMoney('mask', '9894');
			fillItem.find('td:eq(6)').find('.qty').prop('disabled', false);
			fillItem.find('td:eq(7)').find('.UnitCost').prop('disabled', false);

			fillItem.find('td:eq(6)').find('.qty').trigger('change');
			$('#GlobalModalLarge').modal('hide');
		} );    	
	})

	$(document).off('click', '.Detail').on('click', '.Detail',function(e) {
		var data = $(this).attr('data');
		var arr = data.split('@@');
		var html = '';
			html ='<div class = "row">'+
					'<div class = "col-md-12">'+
						'<table id="example" class="table table-bordered display select" cellspacing="0" width="100%">'+
               '<thead>'+
                  '<tr>'+
                     '<th>Item</th>'+
                     '<th>Desc</th>'+
                     '<th>Estimate Value</th>'+
                     '<th>Photo</th>'+
                     '<th>DetailCatalog</th>'+
                  '</tr>'+
               '</thead>'+
               '<tbody><tr>';
               		for (var i = 0; i < arr.length; i++) {
               			html += '<td>'+arr[i]+'</td>';
               		}
               		html += '</tr></tbody>';
         html += '</table></div></div>';
		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Catalog'+'</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>');
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});
	})

	$(document).off('change', '.qty').on('change', '.qty',function(e) {
		var tr = $(this).closest('tr');
		CountSubTotal_table(tr);
	})

	$(document).off('change', '.PPH').on('change', '.PPH',function(e) {
		var tr = $(this).closest('tr');
		CountSubTotal_table(tr);
	})

	function CountSubTotal_table(tr)
	{
		var qty = tr.find('.qty').val();
		qty = findAndReplace(qty, ".","");
		var UnitCost = tr.find('.UnitCost').val();
		UnitCost = findAndReplace(UnitCost, ".","");
		var hitung = qty * UnitCost;
		var PPH = (tr.find('.PPH').val() / 100 ) * hitung;
		hitung = hitung + PPH;
		tr.find('.SubTotal').val(hitung);
		tr.find('.SubTotal').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		tr.find('.SubTotal').maskMoney('mask', '9894');
		__BudgetRemaining(); 
	}

	function __BudgetRemaining()
	{
		ClassDt.BudgetRemaining = [];
		var Budget = [];
		var Budget =  JSON.parse(localStorage.getItem("PostBudgetDepartment"));
		
		var arr = [];
		$('.PostBudgetItem').each(function(){
			var tr = $(this).closest('tr');
			var id_budget_left =  $(this).attr('id_budget_left');
			var SubTotal = tr.find('.SubTotal').val();
			SubTotal = findAndReplace(SubTotal, ".","");
			var temp = {
				id_budget_left : id_budget_left,
				Using : SubTotal,
			}

			arr.push(temp);
		})

		var BudgetRemaining_arr = [];
		for (var i = 0; i < Budget.length; i++) {
			var v = Budget[i].Value;
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
				BudgetRemaining_arr.push(Budget[i]);
			}
		}

		// show search combine seperti Post Budget Department
		for (var i = 0; i < BudgetRemaining_arr.length; i++) {
			var id_budget_left = BudgetRemaining_arr[i]['ID'];
			var Remaining = BudgetRemaining_arr[i].Value - BudgetRemaining_arr[i].Using;
			if (Remaining < 0) {
				var tr = $('.PostBudgetItem:last').closest('tr');
				var InputCombine = '<button class="btn btn-default SearchPostBudget_Combine" type="button" less = "'+Remaining+'"><i class="fa fa-search" aria-hidden="true"></i></button>';
				tr.find('td:eq(12)').html(InputCombine);
			}
			else
			{
				var tr = $('.PostBudgetItem:last').closest('tr');
				tr.find('td:eq(12)').html('No');
			}
		}

		ClassDt.PostBudgetDepartment = Budget;
		ClassDt.BudgetRemaining = BudgetRemaining_arr;
		MakeTableRemaining();


	}

	function MakeTableRemaining()
	{
		$("#Page_Budget_Remaining").empty();
		var BudgetRemaining = ClassDt.BudgetRemaining;
		var html = '<div class = "row">'+
						'<div class = "col-md-12">'+
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
				'</div>';		

		$("#Page_Budget_Remaining").html(html);
	}

	$(document).off('click', '.SearchPostBudget_Combine').on('click', '.SearchPostBudget_Combine',function(e) {
		var ev = $(this);
		var dt = ClassDt.PostBudgetDepartment;
		var html = '';
		html ='<div class = "row">'+
				'<div class = "col-md-12">'+
					'<table id="example_budget_combine" class="table table-bordered display select" cellspacing="0" width="100%">'+
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
		$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>'+
		              '<button type="button" id="ModalbtnSaveForm_Combine" class="btn btn-success">Save</button>');
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});

		var table = $('#example_budget_combine').DataTable({
		      "data" : dt,
		      'columnDefs': [
			      {
			         'targets': 0,
			         'searchable': false,
			         'orderable': false,
			         'className': 'dt-body-center',
			         'render': function (data, type, full, meta){
			             return '<input type="checkbox" name="id[]" value="' + full.ID + '" estvalue="' + (full.Value-full.Using)+'">';
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

		S_Table_example_combine = table;

		$(document).off('click', '#ModalbtnSaveForm_Combine').on('click', '#ModalbtnSaveForm_Combine',function(e) {
			var checkboxArr = [];
			S_Table_example_combine.$('input[type="checkbox"]').each(function(){
			  if(this.checked){
			     var tr = $(this).closest('tr');
			     var CodePost = tr.attr('CodePost');
			     var CodeHeadAccount = tr.attr('CodeHeadAccount');
			     var CodePostRealisasi = tr.attr('CodePostRealisasi');
			     var money = tr.attr('money');
			     var id_budget_left = tr.attr('id_budget_left');
			     var NameHeadAccount = tr.attr('NameHeadAccount');
			     var RealisasiPostName = tr.attr('RealisasiPostName');
			     var temp = {
			     	RealisasiPostName : RealisasiPostName,
			     	id_budget_left : id_budget_left,
			     	money : money,
			     }

			     checkboxArr.push(temp);
			  }

			}); // exit each function

			  var td = ev.closest('td');
			  // check div exist
			  var aa = td.find('.liCombine');
			  if (aa.length) {
			  	aa.remove();
			  }

			 var InputLi = '<ul class = "liCombine">';
			 for (var i = 0; i < checkboxArr.length; i++) {
			 	InputLi += '<li id_budget_left = "'+checkboxArr[i].id_budget_left+'" money = "'+checkboxArr[i].money+'">'+checkboxArr[i].RealisasiPostName+'</li>';
			  }
				 InputLi += '</ul>';
				 td.append(InputLi);
				 td.attr('style','width : 250px;');

			$('#GlobalModalLarge').modal('hide');	 
		})
	})
</script>