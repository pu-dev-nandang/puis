<div class="row">
	<div class="col-md-12">
		<div class="col-md-8 col-md-offset-2">
			<div class="thumbnail">
				<div class="row" style="margin-top: 10px">
					<div class="col-md-3 col-md-offset-1">
						<div class="well">
							<div class="form-group">
								<label class="control-label">Year</label>
								<select class = "select2-select-00 full-width-fix" id = "Year">

								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Department</label>
								<select class = "select2-select-00 full-width-fix" id = "DepartementPost">

								</select>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-md-offset-1">
						<div class="well">
							<div style="margin-top: -15px">
								<label>Budget Remaining</label>
							</div>
							<div id = "Page_Budget_Remaining">
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
 <!-- <pre> -->
	<?php 
	//print_r($this->session->all_userdata());
	 ?>
<!-- </pre>  -->
<div id ="Page_Input_PR" style="margin-top: 10px">
	
</div>
<script type="text/javascript">
	var arr_Year = <?php echo json_encode($arr_Year) ?>;
	var BudgetMax = 0;
	var BudgetRemaining = [];
	var PostBudgetDepartment = [];
	$(document).ready(function() {
		LoadFirstLoad();

		function LoadFirstLoad()
		{
			loadYear();
			getAllDepartementPU();
			loadShowBUdgetRemaining(BudgetRemaining);
		}


		function loadYear()
		{
			$("#Year").empty();
			var OPYear = '';
			OPYear = '';
			for (var i = 0; i < arr_Year.length; i++) {
				var selected = (arr_Year[i].Year == "<?php echo $Year ?>") ? 'selected' : '';
				OPYear += '<option value ="'+arr_Year[i].Year+'" '+selected+'>'+arr_Year[i].Year+'</option>';
			}
			$("#Year").append(OPYear);
			$('#Year').select2({
			   //allowClear: true
			});
			$( "#Year" ).prop( "disabled", true );
		}

		function getAllDepartementPU()
		{
		  var Div = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";
		  var url = base_url_js+"api/__getAllDepartementPU";
		  $('#DepartementPost').empty();
		  $.post(url,function (data_json) {
		    for (var i = 0; i < data_json.length; i++) {
		        var selected = (data_json[i]['Code']==Div) ? 'selected' : '';
		        $('#DepartementPost').append('<option value="'+ data_json[i]['Code']  +'" '+selected+'>'+data_json[i]['Name2']+'</option>');
		    }
		   
		    $('#DepartementPost').select2({
		       //allowClear: true
		    });
		    $( "#DepartementPost" ).prop( "disabled", true );
		    Load_input_PR();
		  })
		}

		function Load_input_PR()
		{
			var Year = $("#Year").val();
			var Departement = $("#DepartementPost").val();
			var url = base_url_js+"budgeting/detail_budgeting_remaining";
			var data = {
					    Year : Year,
						Departement : Departement,
					};
			var token = jwt_encode(data,'UAP)(*');
			$.post(url,{token:token},function (resultJson) {
				var response = jQuery.parseJSON(resultJson);
				PostBudgetDepartment = response.data;
				var html = '<div class = "row" style = "margin-left : 0px">'+
								'<div class = "col-md-3">'+
									'<button type="button" class="btn btn-default btn-add-pr"> <i class="icon-plus"></i> Add</button>'+
								'</div>'+
							'</div>'+
							'<div class = "row" style = "margin-top : 10px;margin-left : 0px;margin-right : 0px">'+		
								'<div class = "col-md-12">'+
									'<div class = "table-responsive">'+
										'<table class="table table-bordered tableData" id ="table_input_pr">'+
										'<thead>'+
										'<tr>'+
											'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Select Post Budget Item</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Item</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Desc</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Qty</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Unit Cost</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Sub Total</th>'+
				                            '<th width = "150px" style = "text-align: center;background: #20485A;color: #FFFFFF;">Date Needed</th>'+
				                            '<th width = "100px" style = "text-align: center;background: #20485A;color: #FFFFFF;">Budget Status</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Upload Files</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Action</th>'+
										'</tr></thead>'+
										'<tbody></tbody></table></div></div></div>';
				var SaveBtn = '<div class = "row" style = "margin-top : 10px;margin-left : 0px;margin-right : 0px">'+
									'<div class = "col-md-12">'+
										'<div class = "pull-right">'+
											'<button class = "btn btn-success" id = "SaveBudget">Submit</button>'+
										'</div>'+
									'</div>'+
								'</div>';
				var InputTax = 	'<div class = "row" style = "margin-top : 10px;margin-left : 0px;margin-right : 0px">'+
									'<div class = "col-sm-2">'+
										'<div class = "form-group">'+
											'<label>PPN</label>'+
											'<input type = "text" class = "form-control" id = "ppn">'+
										'</div>'+
									'</div>'+
								'</div>';												
				$("#Page_Input_PR").html(html+InputTax+SaveBtn);
				$("#ppn").maskMoney({thousands:'', decimal:'', precision:0,allowZero: true});
				$("#ppn").maskMoney('mask', '9894');
				AddingTable();

			}).fail(function() {
			  toastr.info('No Result Data'); 
			}).always(function() {
			                
			});

		}

		// $(".btn-add-pr").click(function(){
		$(document).off('click', '.btn-add-pr').on('click', '.btn-add-pr',function(e) {
			AddingTable();
		})

		function AddingTable()
		{
			var fill = '';
			var No = 1;
			var getfill = function(No){
				var action = '<td></td>';
				if (No > 1) {
					action = '<td><button type="button" class="btn btn-danger btn-delete btn-delete-item"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>';
				}
				var a = '<tr>'+
							'<td>'+No+'</td>'+
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
							'<td><input type="number" min = "1" class="form-control qty"  value="1" disabled></td>'+
							'<td><input type="text" class="form-control UnitCost" disabled></td>'+
							'<td><input type="text" class="form-control SubTotal" disabled value = "0"></td>'+
							'<td>'+
								'<div id="datetimepicker1'+No+'" class="input-group input-append date datetimepicker">'+
		                            '<input data-format="yyyy-MM-dd" class="form-control" id="tgl'+No+'" type=" text" readonly="" value = "<?php echo date('Y-m-d') ?>">'+
		                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
                        		'</div>'+
                        	'</td>'+
                        	'<td></td>'+
                        	'<td><input type="file" data-style="fileinput" class = "BrowseFile" ID = "BrowseFile'+No+'" multiple></td>'+action
                        '</tr>';	

				return a;				
			}

			if ($("#table_input_pr tbody").children().length == 0) {
				fill = getfill(No);
				$('#table_input_pr tbody').append(fill);
				$('.datetimepicker').datetimepicker();
			}
			else
			{
				No = $('#table_input_pr > tbody > tr:last').find('td:eq(0)').text();
				No++;
				fill = getfill(No);
				$('#table_input_pr tbody').append(fill);
				$('.datetimepicker').datetimepicker();
			}
			//eventTableFunction();
		}
		
		$(document).off('click', '.SearchPostBudget').on('click', '.SearchPostBudget',function(e) {
			var ev = $(this);
			var html = '';
			html ='<div class = "row">'+
					'<div class = "col-md-12">'+
						'<table id="example" class="table table-bordered display select" cellspacing="0" width="100%">'+
               '<thead>'+
                  '<tr>'+
                     // '<th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>'+
                     '<th></th>'+
                     '<th>Post Budget Item</th>'+
                     '<th>Remaining</th>'+
                  '</tr>'+
               '</thead>'+
          '</table></div></div>';

				$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Select Post Budget Item'+'</h4>');
				$('#GlobalModalLarge .modal-body').html(html);
				$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
              '<button type="button" id="ModalbtnSaveForm" class="btn btn-success">Save</button>');
				$('#GlobalModalLarge').modal({
				    'show' : true,
				    'backdrop' : 'static'
				});

			var table = $('#example').DataTable({
			      "data" : PostBudgetDepartment,
			      'columnDefs': [
				      {
				         'targets': 0,
				         'searchable': false,
				         'orderable': false,
				         'className': 'dt-body-center',
				         'render': function (data, type, full, meta){
				             return '<input type="checkbox" name="id[]" value="' + full.ID + '" estvalue="' + full.Value + '">';
				         }
				      },
				      {
				         'targets': 1,
				         'render': function (data, type, full, meta){
				             return full.PostName+'-'+full.RealisasiPostName;
				         }
				      },
				      {
				         'targets': 2,
				         'render': function (data, type, full, meta){
				             return formatRupiah(full.Value);
				         }
				      },
			      ],
			      // 'order': [[1, 'asc']]
			});

			// Handle click on checkbox to set state of "Select all" control
			$('#example tbody').on('change', 'input[type="checkbox"]', function(){
				$('input[type="checkbox"]:not(.uniform)').prop('checked', false);
				$(this).prop('checked',true);
			   
			});

			$("#ModalbtnSaveForm").click(function(){
				var chkbox = $('input[type="checkbox"]:checked:not(.uniform)');
				var checked = chkbox.val();
				var estvalue = chkbox.attr('estvalue');
				var n = estvalue.indexOf(".");
				estvalue = estvalue.substring(0, n);
				var row = chkbox.closest('tr');
				var PostBudgetItem = row.find('td:eq(1)').text();
				var fillItem = ev.closest('tr');
				fillItem.find('td:eq(1)').find('.PostBudgetItem').val(PostBudgetItem);
				fillItem.find('td:eq(1)').find('.PostBudgetItem').attr('id_budget_left',checked);
				fillItem.find('td:eq(1)').find('.PostBudgetItem').attr('remaining',estvalue);
				fillItem.find('td:eq(4)').find('.qty').trigger('change');
				$('#GlobalModalLarge').modal('hide');
			})

		})

		$(document).off('change keyup', '.qty').on('change keyup', '.qty',function(e) {
			var qty = $(this).val();
			var fillItem = $(this).closest('tr');
			var estvalue = fillItem.find('td:eq(5)').find('.UnitCost').val();
			estvalue = findAndReplace(estvalue, ".","");
			var SubTotal = parseInt(qty) * parseInt(estvalue);
			fillItem.find('td:eq(6)').find('.SubTotal').val(SubTotal);
			fillItem.find('td:eq(6)').find('.SubTotal').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
			fillItem.find('td:eq(6)').find('.SubTotal').maskMoney('mask', '9894');
			_BudgetRemaining();
			FuncBudgetStatus(fillItem);
			var id_budget_left = fillItem.find('td:eq(1)').find('.PostBudgetItem').attr('id_budget_left');
		})

		$(document).off('keyup', '.UnitCost').on('keyup', '.UnitCost',function(e) {
			var fillItem = $(this).closest('tr');
			var qty = fillItem.find('td:eq(4)').find('.qty').val();
			var estvalue = $(this).val();
			estvalue = findAndReplace(estvalue, ".","");
			var SubTotal = parseInt(qty) * parseInt(estvalue);
			fillItem.find('td:eq(6)').find('.SubTotal').val(SubTotal);
			fillItem.find('td:eq(6)').find('.SubTotal').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
			fillItem.find('td:eq(6)').find('.SubTotal').maskMoney('mask', '9894');
			_BudgetRemaining();
			FuncBudgetStatus(fillItem);
			var id_budget_left = fillItem.find('td:eq(1)').find('.PostBudgetItem').attr('id_budget_left');
			
		})

		function _BudgetRemaining()
		{
			// loadingStart();
			loading_page('#Page_Budget_Remaining');
			BudgetRemaining = [];
			var arr_id_budget_left = [];
			$('.PostBudgetItem').each(function(){
				var id_budget_left = $(this).attr('id_budget_left');
				arr_id_budget_left.push(id_budget_left);
			})

			for (var i = 0; i < arr_id_budget_left.length; i++) {
				var total = 0;
				var PostBudgetItem = '';
				var Remaining = 0;
				var GetNO = i + 1;
				var id_budget_left = arr_id_budget_left[i];
				var RemainingNoFormat = 0;
				$('.PostBudgetItem[id_budget_left="'+id_budget_left+'"]').each(function(){
					var fillItem = $(this).closest('tr');
					var SubTotal = fillItem.find('td:eq(6)').find('.SubTotal').val();
					var SubTotal = findAndReplace(SubTotal, ".","");
					PostBudgetItem = fillItem.find('td:eq(1)').find('.PostBudgetItem').val();
					total += parseInt(SubTotal);
				})

				for (var l = 0; l < PostBudgetDepartment.length; l++) { // find Value awal
					var B_id_budget_left = PostBudgetDepartment[l].ID;
					if (B_id_budget_left == id_budget_left) {
						Remaining = parseInt(PostBudgetDepartment[l].Value) - parseInt(total);
						var dataarr = {
							PostBudgetItem : PostBudgetItem,
							Remaining : formatRupiah(Remaining),
							No : GetNO,
							id_budget_left : id_budget_left,
							RemainingNoFormat : Remaining,
						}

						// check id_budget_left existing in BudgetRemaining
						for (var k = 0; k < BudgetRemaining.length; k++) {
							if (BudgetRemaining[k].id_budget_left == id_budget_left) {
								var removeItem = k;
								BudgetRemaining = $.grep(BudgetRemaining, function(value,index) {
								  return index != removeItem;
								});
								break;
							}
						}
						BudgetRemaining.push(dataarr);
						break;
					}
				}					
			}
			
			// console.log(BudgetRemaining);
			loadShowBUdgetRemaining(BudgetRemaining);
			// loadingEnd(500)

		}

		$(document).off('click', '.SearchItem').on('click', '.SearchItem',function(e) {
			$(".uniform").prop('disabled', true);
			var ev = $(this);
			var html = '';
			html ='<div class = "row">'+
					'<div class = "col-md-12">'+
						'<table id="example" class="table table-bordered display select" cellspacing="0" width="100%">'+
               '<thead>'+
                  '<tr>'+
                     // '<th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>'+
                     '<th></th>'+
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
			$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
	        '<button type="button" id="ModalbtnSaveForm" class="btn btn-success">Save</button>');
			$('#GlobalModalLarge').modal({
			    'show' : true,
			    'backdrop' : 'static'
			});

			var url = base_url_js+'rest/Catalog/__Get_Item';
			var data = {
				action : 'choices',
				auth : 's3Cr3T-G4N',
				department : "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>"
			};
		    var token = jwt_encode(data,"UAP)(*");
			var table = $('#example').DataTable({
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
			             return '<input type="checkbox" name="id[]" value="' + full[6] + '" estvalue="' + full[7] + '">';
			         }
			      }],
			      'order': [[1, 'asc']]
			   });

			   // Handle click on checkbox to set state of "Select all" control
			   $('#example tbody').on('change', 'input[type="checkbox"]', function(){
			   	$('input[type="checkbox"]:not(.uniform)').prop('checked', false);
			   	$(this).prop('checked',true);
			      
			   });

			   $("#ModalbtnSaveForm").click(function(){
			   		var chkbox = $('input[type="checkbox"]:checked:not(.uniform)');
			   		var checked = chkbox.val();
			   		var estvalue = chkbox.attr('estvalue');
			   		var n = estvalue.indexOf(".");
			   		estvalue = estvalue.substring(0, n);
			   		var row = chkbox.closest('tr');
			   		var Item = row.find('td:eq(1)').text();
			   		var Desc = row.find('td:eq(2)').text();
			   		var Est = row.find('td:eq(3)').text();
			   		var Photo = row.find('td:eq(4)').html();
			   		var DetailCatalog =  row.find('td:eq(5)').html();
			   		var arr = Item+'@@'+Desc+'@@'+Est+'@@'+Photo+'@@'+DetailCatalog;
			   		var fillItem = ev.closest('tr');
			   		fillItem.find('td:eq(2)').find('.Item').val(Item);
			   		fillItem.find('td:eq(2)').find('.Item').attr('savevalue',checked);
			   		fillItem.find('td:eq(2)').find('.Item').attr('estvalue',estvalue);
			   		fillItem.find('td:eq(3)').find('.Detail').attr('data',arr);
			   		fillItem.find('td:eq(5)').find('.UnitCost').val(estvalue);
			   		fillItem.find('td:eq(5)').find('.UnitCost').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
			   		fillItem.find('td:eq(5)').find('.UnitCost').maskMoney('mask', '9894');

			   		fillItem.find('td:eq(4)').find('.qty').prop('disabled', false);
			   		fillItem.find('td:eq(5)').find('.UnitCost').prop('disabled', false);

			   		fillItem.find('td:eq(4)').find('.qty').trigger('change');
			   		$('#GlobalModalLarge').modal('hide');
			   })
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

		$(document).off('click', '.btn-delete-item').on('click', '.btn-delete-item',function(e) {
			var fillItem = $(this).closest('tr');
			var id_budget_left = fillItem.find('td:eq(1)').find('.PostBudgetItem').attr('id_budget_left');

			$( this )
              .closest( 'tr')
              .remove();
              _BudgetRemaining();
              SortByNumbering();
		})

		function loadShowBUdgetRemaining(BudgetRemaining)
		{
			setTimeout(function () {
	           $("#Page_Budget_Remaining").empty();
	           var html = '<div class = "row">'+
	           				'<div class = "col-md-12">'+
	           					'<table class="table table-bordered tableData" id ="tableData3">'+
	           						'<thead>'+
	           							'<tr>'+
	           								'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
	           								'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Post Budget Item</th>'+
	           								'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Remaining</th>'+
	           							'</tr>'+
	           						'</thead><tbody>';
	           							
	           for (var i = 0; i < BudgetRemaining.length; i++) {
	           	var No = i + 1;
	           	html += '<tr>'+
	           				'<td>'+No+'</td>'+
	           				'<td>'+BudgetRemaining[i].PostBudgetItem+'</td>'+
	           				'<td>'+BudgetRemaining[i].Remaining+'</td>'+
	           			'</tr>';	
	           }

	           html += '</tbody>'+
	           		'</table>'+
	           		'</div>'+
	           		'</div>';		

	           $("#Page_Budget_Remaining").html(html);
			},1000);
			
		}

		function SortByNumbering()
		{
			var no = 1;
			$("#table_input_pr tbody tr").each(function(){
				var a = $(this);
				a.find('td:eq(0)').html(no);
				no++;
			})
		}

		function FuncBudgetStatus(fillItem)
		{
			var id_budget_left = fillItem.find('td:eq(1)').find('.PostBudgetItem').attr('id_budget_left');
			var GetBudgetRemaining = function(id_budget_left,BudgetRemaining){
				var Remaining = 0;
				for (var i = 0; i < BudgetRemaining.length; i++) {
					if (id_budget_left == BudgetRemaining[i].id_budget_left) {
						Remaining = BudgetRemaining[i].RemainingNoFormat;
						break;
					}
				}
				return Remaining;
			};

			var Remaining = GetBudgetRemaining(id_budget_left,BudgetRemaining);
			// console.log(Remaining);

			var OP = [
					{
						name  : 'IN',
						color : 'green'
					},
					{
						name  : 'Exceed',
						color : 'red'
					},
					{
						name  : 'Cross',
						color : 'yellow'
					},
				];

			var DefaultName = (Remaining >= 0) ? 'IN' : 'Exceed';
			var disabled = (DefaultName == 'Exceed') ? 'disabled' : '';
			var html = '<select class = "form-control BudgetStatus"  '+disabled+'>';

			for (var i = 0; i < OP.length; i++) {
				if (DefaultName == 'IN') {
					if (OP[i].name == 'Exceed') {
						continue;
					}
				}
				var selected = (DefaultName == OP[i].name) ? 'selected' : '';
				html += '<option value = "'+OP[i].name+'"'+selected+'>'+OP[i].name+'</option>';
			}
			html += '</select>';

			fillItem.find('td:eq(8)').html(html);
		}
		
		$(document).off('click', '#SaveBudget').on('click', '#SaveBudget',function(e) {
			loading_button('#SaveBudget');
			// Budget Status
				if ($('.BudgetStatus').length) {
					// check Budget status tidak boleh exceeds
						var bool = true;
						$(".BudgetStatus").each(function(){
							if ($(this).val() == 'Exceed') {
								bool = false;
								return false;
							}
						})

						if (!bool) {
							toastr.error('Budget Status having value is Exceed','!!!Error');
							$('#SaveBudget').prop('disabled',false).html('Submit');
						}
						else
						{
							// ok
							var validation = validation_input();
							if (validation) {
								SubmitPR('','Add');
							}
							
						}
				}
				else
				{
					toastr.error('Budget Status is required','!!!Error');
					$('#SaveBudget').prop('disabled',false).html('Submit');
				}
		})


		function validation_input()
		{
			var find = true;
			$(".PostBudgetItem").each(function(){
				var fillItem = $(this).closest('tr');
				var PostBudgetItem = $(this).val();
				if (PostBudgetItem == '') {
					find = false;
					toastr.error("Post Budget Item is required",'!!!Error');
					return false;
				}

				var Item = fillItem.find('td:eq(2)').find('.Item').val();
				if (Item == '') {
					find = true;
					toastr.error("Item is required",'!!!Error');
					return false;
				}

			})

			$(".BrowseFile").each(function(){
				var IDFile = $(this).attr('id');
				if (!file_validation2(IDFile) ) {
				  return false;
				}
			})

			return find;

		}

		function file_validation2(ID_element)
		{
		    var files = $('#'+ID_element)[0].files;
		    var error = '';
		    var msgStr = '';
		    var max_upload_per_file = 4;
		    if (files.length > max_upload_per_file) {
		      msgStr += '1 Document should not be more than 4 Files<br>';

		    }
		    else
		    {
		      for(var count = 0; count<files.length; count++)
		      {
		       var no = parseInt(count) + 1;
		       var name = files[count].name;
		       console.log(name);
		       var extension = name.split('.').pop().toLowerCase();
		       if(jQuery.inArray(extension, ['pdf','jpg']) == -1)
		       {
		        msgStr += 'File Number '+ no + ' Invalid Type File<br>';
		        //toastr.error("Invalid Image File", 'Failed!!');
		        // return false;
		       }

		       var oFReader = new FileReader();
		       oFReader.readAsDataURL(files[count]);
		       var f = files[count];
		       var fsize = f.size||f.fileSize;
		       console.log(fsize);

		       if(fsize > 2000000) // 2mb
		       {
		        msgStr += 'File Number '+ no + ' Image File Size is very big<br>';
		        //toastr.error("Image File Size is very big", 'Failed!!');
		        //return false;
		       }
		       
		      }
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

		function SubmitPR(PRCode,Action)
		{
			var Year = $("#Year").val();
			var Departement = $("#DepartementPost").val();
			var PPN = $("#ppn").val();
			var FormInsertDetail = [];
			var form_data = new FormData();
			var PassNumber = 0;
			$(".PostBudgetItem").each(function(){
				var ID_budget_left = $(this).attr('id_budget_left');
				var fillItem = $(this).closest('tr');
				var ID_m_catalog = fillItem.find('td:eq(2)').find('.Item').attr('savevalue');
				var Qty = fillItem.find('td:eq(4)').find('.qty').val();
				var UnitCost = fillItem.find('td:eq(5)').find('.UnitCost').val();
				UnitCost = findAndReplace(UnitCost, ".","");
				var No = fillItem.find('td:eq(0)').text();
				var SubTotal = fillItem.find('td:eq(6)').find('.SubTotal').val();
				SubTotal = findAndReplace(SubTotal, ".","");
				var DateNeeded = fillItem.find('td:eq(7)').find('#tgl'+No).val();
				var BudgetStatus = fillItem.find('td:eq(8)').find('.BudgetStatus').val();

				if ( $( '#'+'BrowseFile'+No ).length ) {
					var UploadFile = $('#'+'BrowseFile'+No)[0].files;
					for(var count = 0; count<UploadFile.length; count++)
					{
					 form_data.append("UploadFile"+PassNumber+"[]", UploadFile[count]);
					}
				}

				 var data = {
				 	ID_budget_left : ID_budget_left,
				 	ID_m_catalog : ID_m_catalog,
				 	Qty : Qty,
				 	UnitCost : UnitCost,
				 	SubTotal : SubTotal,
				 	DateNeeded : DateNeeded,
				 	BudgetStatus : BudgetStatus,
				 }
				 var token = jwt_encode(data,"UAP)(*");
				 FormInsertDetail.push(token);
				 PassNumber++

			})

			form_data.append('data',FormInsertDetail);
			form_data.append('Action',Action);
			form_data.append('PRCode',PRCode);
			var url = base_url_js + "budgeting/submitpr"
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
			    $('#SaveBudget').prop('disabled',false).html('Submit');
			  }
			})

		}
	}); // exit document Function
</script>
