<div class="row" style="margin-left: 10px;margin-right: 10px;">
	<div class="col-md-4">
		<div class="thumbnail">
			<div class="row">
				<div class="col-md-12">
					<div style="padding: 15px;">
                        <h3 class="header-blue">Form Mutasi Budget</h3>
                        <div style="background: lightyellow; border: 1px solid #ccc;padding: 15px;color: #f44336;margin-bottom: 20px;">
                            <b>Budget yang dimutasi adalah budget pada periode yang berjalan</b>
                        </div>
                    </div>
                    <div class="well">
                    	<div style="text-align: center;">
                            <h4 style="margin-top: 0px;">From :</h4>
                        </div>
                        <div class="form-group">
                        	<label>Department</label>
                        	<select class="select2-select-00 full-width-fix c_department" id="DepartementBRevisiMutasi1">
                        		
                        	</select>
                        </div>
                        <div class="form-group">
                        	<label>Post Budget</label>
                        	<select class="select2-select-00 full-width-fix" id="SelectPostBudget1">
                        
                        	</select>
                        </div>
                        <div class="form-group">
                        	<label>Nominal</label>
                        	<input type="text" name="Invoice" class="form-control" id="InvoiceMutasi">
                        </div>
    					<div class="form-group">
    						<label>Reason</label>
    						<textarea class = "form-control" rows = "4" id = "Reason"></textarea>
    					</div>
                        <div class="form-group">
    						<label class="lblBudgetLeft" style="color: red">Budget Left : Rp.0,-</label>
    					</div>
                    </div>

                    <div class="well">
                    	<div style="text-align: center;">
                            <h4 style="margin-top: 0px;">To :</h4>
                        </div>
                        <div class="form-group">
                        	<label>Department</label>
                        	<select class="select2-select-00 full-width-fix c_department" id="DepartementBRevisiMutasi2">
                        		
                        	</select>
                        </div>
                        <div class="form-group">
                        	<label>Post Budget</label>
                        	<select class="select2-select-00 full-width-fix" id="SelectPostBudget2">
                        
                        	</select>
                        </div>
                        <div class="form-group">
    						<label class="lblBudgetResult" style="color: red">Budget Left : Rp.0,-</label>
    					</div>
                    </div>

					<div style="padding: 5px;">
                        <button class="btn btn-block btn-success" id="btnSubmitBRevisiMutasi">Save</button>
                    </div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="thumbnail">
			<div class="row">
				<div class="col-md-12">
					<div style="padding: 15px;">
						<h3 class="header-blue">List Mutasi Budget</h3>
					</div>
					<table id="tblListMutasi" class="table table-bordered display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th rowspan="2" style="vertical-align : middle;text-align:center;">No</th>
								<th rowspan="2" style="vertical-align : middle;text-align:center;">Date & By</th>
								<th colspan="2" style="vertical-align : middle;text-align:center;">Post Budget</th>
								<th rowspan="2" style="vertical-align : middle;text-align:center;">Nominal</th>
								<th rowspan="2" style="vertical-align : middle;text-align:center;">Reason</th>
							</tr>
							<tr>
								<td style="vertical-align : middle;text-align:center;">From</td>					
								<td style="vertical-align : middle;text-align:center;">To</td>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>		
				</div>		
			</div>			
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		LoadFirstLoadBrevisi_Mutasi();
		loadingEnd(500);
	}); // exit document Function

	function LoadFirstLoadBrevisi_Mutasi()
	{
		getAllDepartementPU__Brevisi_Mutasi();
		$('#InvoiceMutasi').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		$('#InvoiceMutasi').maskMoney('mask', '9894');
		LoadTableBrevisiMutasi();
	}

	function LoadTableBrevisiMutasi()
	{
		var url = base_url_js+'budgeting/EntryBudget/budget_revisi/Mutasi/load';
		$.post(url,function (data_json) {
		    var response = jQuery.parseJSON(data_json);
		    var table = $('#tblListMutasi').DataTable({
		          "data" : response,
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
		    	             return full.CreateAt+'<br>'+full.Name;
		    	         }
		    	      },
		    	      {
		    	         'targets': 2,
		    	         'render': function (data, type, full, meta){
		    	         	var DetailPostBudget = full.DetailPostBudget_a;
		    	         	var htmlwr = 'NameUnitDiv : '+DetailPostBudget[0].NameUnitDiv+
		    	         				 '<br>'+DetailPostBudget[0].NameHeadAccount+'-'+DetailPostBudget[0].RealisasiPostName
		    	             return htmlwr;
		    	         }
		    	      },
		    	      {
		    	         'targets': 3,
		    	         'render': function (data, type, full, meta){
		    	         	var DetailPostBudget = full.DetailPostBudget_b;
		    	         	var htmlwr = 'NameUnitDiv : '+DetailPostBudget[0].NameUnitDiv+
		    	         				 '<br>'+DetailPostBudget[0].NameHeadAccount+'-'+DetailPostBudget[0].RealisasiPostName
		    	             return htmlwr;
		    	         }
		    	      },
		    	      {
		    	         'targets': 4,
		    	         'render': function (data, type, full, meta){
		    	         	return formatRupiah(full.Invoice);
		    	         }
		    	      },
		    	      {
		    	         'targets': 5,
		    	         'render': function (data, type, full, meta){
		    	         	return full.Reason;
		    	         }
		    	      },
		          ],
		          'createdRow': function( row, data, dataIndex ) {

		          },
		          // 'order': [[1, 'asc']]
		    });

		    table.on( 'order.dt search.dt', function () {
		            table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		                cell.innerHTML = i+1;
		            } );
		        } ).draw();	
		}).done(function() {
		  // loadTable();
		}).fail(function() {
		  toastr.error('The Database connection error, please try again', 'Failed!!');
		}).always(function() {

		});
	}

	function getAllDepartementPU__Brevisi_Mutasi()
	{
	  var url = base_url_js+"api/__getAllDepartementPU";
	  $('.c_department').empty();
	  $('.c_department').append('<option></option>');
	  $.post(url,function (data_json) {
	    for (var i = 0; i < data_json.length; i++) {
	    	$('.c_department').append('<option value="'+ data_json[i]['Code']  +'" '+''+'>'+data_json[i]['Name2']+'</option>');
	    }
	   
	    $('.c_department').select2({
	       placeholder: "--Choose Department--",
	       allowClear: true
	    });
	  })
	}

	$(document).off('change', '#DepartementBRevisiMutasi1').on('change', '#DepartementBRevisiMutasi1',function(e) {
		var Departement = $('#DepartementBRevisiMutasi1 option:selected').val();
		var SelectorTarget = $('#SelectPostBudget1');
		LoadPostBudgetOPbyDepartment(Departement,SelectorTarget);
	})

	$(document).off('change', '#SelectPostBudget1').on('change', '#SelectPostBudget1',function(e) {
		// var lblBudgetLeft = $('.lblBudgetLeft');
		// var valuee = $('#SelectPostBudget1 option:selected').attr('valuee');
		// var n = valuee.indexOf(".");
		// valuee = valuee.substring(0, n);
		// valuee = formatRupiah(valuee);
		// lblBudgetLeft.html('Budget Left : '+valuee);

		$('#InvoiceMutasi').trigger('keyup');
	})

	$(document).off('change', '#DepartementBRevisiMutasi2').on('change', '#DepartementBRevisiMutasi2',function(e) {
		var Departement = $('#DepartementBRevisiMutasi2 option:selected').val();
		var SelectorTarget = $('#SelectPostBudget2');
		LoadPostBudgetOPbyDepartment(Departement,SelectorTarget);
	})

	$(document).off('change', '#SelectPostBudget2').on('change', '#SelectPostBudget2',function(e) {
		// var lblBudgetResult = $('.lblBudgetResult');
		// var valuee = $('#SelectPostBudget2 option:selected').attr('valuee');
		// var n = valuee.indexOf(".");
		// valuee = valuee.substring(0, n);
		// valuee = formatRupiah(valuee);
		// lblBudgetResult.html('Budget Left : '+valuee);

		$('#InvoiceMutasi').trigger('keyup');
	})

	function LoadPostBudgetOPbyDepartment(Departement,SelectorTarget)
	{
		loadingStart();
		load_table_activated_period_years_Brevisi().then(function(response){
			var Year = response[0].Year;
			load_budget_remaining__(Year,Departement).then(function(response2){
				SelectorTarget.empty();
				SelectorTarget.append('<option></option>');
				var response2 = response2.data;
				for (var i = 0; i < response2.length; i++) {
					var Using = response2[i]['Using'];
					var Valuee = response2[i]['Value'];
					SelectorTarget.append('<option value="'+ response2[i]['ID']  +'" '+''+' Valuee = "'+Valuee+'">'+response2[i]['NameHeadAccount']+'-'+response2[i]['RealisasiPostName']+'</option>');
				}
				
				SelectorTarget.select2({
				   placeholder: "--Choose Post Budget--",
				   allowClear: true
				});
				// console.log(response2);
				loadingEnd(500);
			})
		})
	}

	$(document).off('keyup', '#InvoiceMutasi').on('keyup', '#InvoiceMutasi',function(e) {
		var v = $(this).val();
		v = findAndReplace(v, ".","");
		var lblBudgetLeft = $('.lblBudgetLeft');
		var valuee = $('#SelectPostBudget1 option:selected').attr('valuee');
		var n = valuee.indexOf(".");
		valuee = valuee.substring(0, n);
		var rs = parseInt(valuee) - parseInt(v);
		lblBudgetLeft.html('Budget Left : '+formatRupiah(rs));

		var valuee2 = $('#SelectPostBudget2 option:selected').attr('valuee');
		if (valuee2 != '' && valuee2 != null && valuee2 != undefined) {
			var n = valuee2.indexOf(".");
			valuee2 = valuee2.substring(0, n);
			var lblBudgetResult = $('.lblBudgetResult');
			var rs = parseInt(valuee2) + parseInt(v);
			lblBudgetResult.html('Budget Result : '+formatRupiah(rs));
		}

	})

	$(document).off('click', '#btnSubmitBRevisiMutasi').on('click', '#btnSubmitBRevisiMutasi',function(e) {
		var ev = $(this);
		if (confirm('Are you sure ?')) {
			if (validation_BMutasi()) {
				// console.log('true')
				// var SelectPostBudget = $('#SelectPostBudget option:selected').val();
				// var SelectType = $('#SelectType option:selected').val();
				// var Invoice = $('#Invoice').val();
				// Invoice = findAndReplace(Invoice, ".","");
				// var Reason = $('#Reason').val();

				// var data = {
				// 	ID_budget_left : SelectPostBudget,
				// 	Type : SelectType,
				// 	Invoice : Invoice,
				// 	Reason : Reason,
				// };

				var SelectPostBudget1 = $('#SelectPostBudget1 option:selected').val();
				var SelectPostBudget2 = $('#SelectPostBudget2 option:selected').val();
				var Invoice = $('#InvoiceMutasi').val();
				var Invoice = findAndReplace(Invoice, ".","");
				var Reason = $('#Reason').val();
				var data1 = {
					ID_budget_left : SelectPostBudget1,
					Type : 'Less',
					Invoice : Invoice,
					Reason : Reason,
				}

				var data2 = {
					ID_budget_left : SelectPostBudget2,
					Type : 'Add',
					Invoice : Invoice,
					Reason : 'Hasil Mutasi',
				}

				var data = {
					data1 : data1,
					data2 : data2,
				}

				var token = jwt_encode(data,"UAP)(*");
				loading_button('#btnSubmitBRevisiMutasi');
				var url = base_url_js+'budgeting/EntryBudget/budget_revisi/Mutasi/save';
				$.post(url,{token:token},function (data_json) {
				    LoadPageBudgetRevisi('Mutasi');
				}).done(function() {
				  // loadTable();
				}).fail(function() {
				  toastr.error('The Database connection error, please try again', 'Failed!!');
				}).always(function() {
				 $('#btnSubmitBRevisiMutasi').prop('disabled',false).html('Save');

				});
			}
			else
			{
				console.log('validasi false')
			}
		}	
	})

	function validation_BMutasi()
	{
		var SelectPostBudget1 = $('#SelectPostBudget1 option:selected').val();
		var SelectPostBudget2 = $('#SelectPostBudget2 option:selected').val();
		var Invoice = $('#InvoiceMutasi').val();
		var Reason = $('#Reason').val();

		var arr = {
			SelectPostBudget1 : SelectPostBudget1,
			SelectPostBudget2 : SelectPostBudget2,
			Invoice : Invoice,
			Reason : Reason,
		};

		var toatString = "";
		var result = "";
		for(var key in arr) {
		   switch(key)
		   {
		    case  "Reason" :
		    	  result = Validation_required(arr[key],key);
		    	  if (result['status'] == 0) {
		    	    toatString += result['messages'] + "<br>";
		    	  }	
		          break;
		    case  "Invoice" :
		          var valuee = findAndReplace(arr[key], ".","");
		          valuee = parseInt(valuee);
		          if (valuee <= 0) {
		          	toatString += 'Invoice cannot be zero '+ "<br>";
		          }
		          break;
		    case  "SelectPostBudget1" :
		          result = Validation_required(arr[key],key);
		          if (result['status'] == 0) {
		            toatString += result['messages'] + "<br>";
		          }

		          if (arr[key] == arr['SelectPostBudget2']) {
		          	toatString += 'Post Budget is same' + "<br>";
		          }	
		          break;
		    case  "SelectPostBudget2" :
		          result = Validation_required(arr[key],key);
		          if (result['status'] == 0) {
		            toatString += result['messages'] + "<br>";
		          }

		          if (arr[key] == arr['SelectPostBudget1']) {
		          	toatString += 'Post Budget is same' + "<br>";
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
		
</script>