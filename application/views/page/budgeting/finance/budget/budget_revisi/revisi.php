<div class="row" style="margin-left: 10px;margin-right: 10px;">
	<div class="col-md-4">
		<div class="thumbnail">
			<div class="row">
				<div class="col-md-12">
					<div style="padding: 15px;">
                        <h3 class="header-blue">Form Revisi Budget</h3>
                        <div style="background: lightyellow; border: 1px solid #ccc;padding: 15px;color: #f44336;margin-bottom: 20px;">
                            <b>Budget yang direvisi adalah budget pada periode yang berjalan</b>
                        </div>
                    </div>
					<div class="form-group">
						<label>Department</label>
						<select class="select2-select-00 full-width-fix" id="DepartementBRevisiRevisi">
							
						</select>
					</div>
					<div class="form-group">
						<label>Post Budget</label>
						<select class="select2-select-00 full-width-fix" id="SelectPostBudget">
					
						</select>
					</div>
					<div class="form-group">
						<label>Type</label>
						<select class="form-control" id="SelectType">
							<option value = "" disabled selected>--Choose Type--</option>
							<option value="Add">Penambahan</option>
							<option value="Less">Pengurangan</option>
						</select>
					</div>
					<div class="form-group">
						<label>Nominal</label>
						<input type="text" name="Invoice" class="form-control" id="Invoice">
					</div>
					<div class="form-group">
						<label>Reason</label>
						<textarea class = "form-control" rows = "4" id = "Reason"></textarea>
					</div>
                    <div class="form-group">
						<label class="lblBudgetOld" style="color: red">Budget Old : Rp.0,-</label>
					</div>
                    <div class="form-group">
						<label class="lblBudgetNew" style="color: red">Budget New : Rp.0,-</label>
					</div>
					<div style="padding: 5px;">
                        <button class="btn btn-block btn-success" id="btnSubmitBRevisiRevisi">Save</button>
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
						<h3 class="header-blue">List Revisi Budget</h3>
					</div>
					<table id="tblListRevisi" class="table table-bordered display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>No</th>
								<th>Date & By</th>
								<th>Post Budget</th>
								<th>Type</th>
								<th>Nominal</th>
								<th>Reason</th>
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
		LoadFirstLoadBrevisi_Revisi();
		loadingEnd(500);
	}); // exit document Function

	function LoadFirstLoadBrevisi_Revisi()
	{
		getAllDepartementPU__Brevisi_Revisi();
		$('#Invoice').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		$('#Invoice').maskMoney('mask', '9894');
		LoadTableBrevisiRevisi();
	}

	function LoadTableBrevisiRevisi()
	{
		var url = base_url_js+'budgeting/EntryBudget/budget_revisi/Revisi/load';
		$.post(url,function (data_json) {
		    var response = jQuery.parseJSON(data_json);
		    var table = $('#tblListRevisi').DataTable({
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
		    	         	var DetailPostBudget = full.DetailPostBudget;
		    	         	var htmlwr = 'NameUnitDiv : '+DetailPostBudget[0].NameUnitDiv+
		    	         				 '<br>'+DetailPostBudget[0].NameHeadAccount+'-'+DetailPostBudget[0].RealisasiPostName
		    	             return htmlwr;
		    	         }
		    	      },
		    	      {
		    	         'targets': 3,
		    	         'render': function (data, type, full, meta){
		    	         	 if (full.Type == 'Add') {
		    	         	 	return 'Penambahan';
		    	         	 }
		    	         	 else
		    	         	 {
		    	         	 	return 'Pengurangan';
		    	         	 }
		    	             
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
	
	function getAllDepartementPU__Brevisi_Revisi()
	{
	  var url = base_url_js+"api/__getAllDepartementPU";
	  $('#DepartementBRevisiRevisi').empty();
	  $('#DepartementBRevisiRevisi').append('<option></option>');
	  $.post(url,function (data_json) {
	    for (var i = 0; i < data_json.length; i++) {
	    	$('#DepartementBRevisiRevisi').append('<option value="'+ data_json[i]['Code']  +'" '+''+'>'+data_json[i]['Name2']+'</option>');
	    }
	   
	    $('#DepartementBRevisiRevisi').select2({
	       placeholder: "--Choose Department--",
	       allowClear: true
	    });
	  })
	}

	$(document).off('change', '#DepartementBRevisiRevisi').on('change', '#DepartementBRevisiRevisi',function(e) {
		loadingStart();
		load_table_activated_period_years_Brevisi().then(function(response){
			var Year = response[0].Year;
			var Departement = $('#DepartementBRevisiRevisi option:selected').val();
			load_budget_remaining__(Year,Departement).then(function(response2){
				$('#SelectPostBudget').empty();
				$('#SelectPostBudget').append('<option></option>');
				var response2 = response2.data;
				for (var i = 0; i < response2.length; i++) {
					var Using = response2[i]['Using'];
					var Valuee = response2[i]['Value'];
					$('#SelectPostBudget').append('<option value="'+ response2[i]['ID']  +'" '+''+' Valuee = "'+Valuee+'">'+response2[i]['NameHeadAccount']+'-'+response2[i]['RealisasiPostName']+'</option>');
				}
				
				$('#SelectPostBudget').select2({
				   placeholder: "--Choose Post Budget--",
				   allowClear: true
				});
				// console.log(response2);
				loadingEnd(500);
			})

		})
	})

	$(document).off('change', '#SelectPostBudget').on('change', '#SelectPostBudget',function(e) {
		var lblBudgetOld = $('.lblBudgetOld');
		var valuee = $('#SelectPostBudget option:selected').attr('valuee');
		var n = valuee.indexOf(".");
		valuee = valuee.substring(0, n);
		valuee = formatRupiah(valuee);
		lblBudgetOld.html('Budget Old : '+valuee);
	})

	$(document).off('keyup', '#Invoice').on('keyup', '#Invoice',function(e) {
		var v = $(this).val();
		v = findAndReplace(v, ".","");
		var SelectType = $('#SelectType option:selected').val();
		// console.log(SelectType);
		if (SelectType == '' || SelectType == null || SelectType == undefined) {
			$(this).val(0);
			toastr.info('Please choose Type');
		}
		else
		{
			var lblBudgetNew = $('.lblBudgetNew');
			var valuee = $('#SelectPostBudget option:selected').attr('valuee');
			var n = valuee.indexOf(".");
			valuee = valuee.substring(0, n);
			if (SelectType == 'Add') {
				var rs = parseInt(valuee) + parseInt(v);
			}
			else
			{
				var rs = parseInt(valuee) - parseInt(v);
			}

			lblBudgetNew.html('Budget New : '+formatRupiah(rs));

		}
	})

	$(document).off('click', '#btnSubmitBRevisiRevisi').on('click', '#btnSubmitBRevisiRevisi',function(e) {
		var ev = $(this);
		if (confirm('Are you sure ?')) {
			if (validation_BrevisiRevisi()) {
				var SelectPostBudget = $('#SelectPostBudget option:selected').val();
				var SelectType = $('#SelectType option:selected').val();
				var Invoice = $('#Invoice').val();
				Invoice = findAndReplace(Invoice, ".","");
				var Reason = $('#Reason').val();

				var data = {
					ID_budget_left : SelectPostBudget,
					Type : SelectType,
					Invoice : Invoice,
					Reason : Reason,
				};
				var token = jwt_encode(data,"UAP)(*");
				loading_button('#btnSubmitBRevisiRevisi');
				var url = base_url_js+'budgeting/EntryBudget/budget_revisi/Revisi/save';
				$.post(url,{token:token},function (data_json) {
				    LoadPageBudgetRevisi('Revisi');
				}).done(function() {
				  // loadTable();
				}).fail(function() {
				  toastr.error('The Database connection error, please try again', 'Failed!!');
				}).always(function() {
				 $('#btnSubmitBRevisiRevisi').prop('disabled',false).html('Save');

				});
			}
		}	
	})

	function validation_BrevisiRevisi()
	{
		var DepartementBRevisiRevisi = $('#DepartementBRevisiRevisi option:selected').val();
		var SelectPostBudget = $('#SelectPostBudget option:selected').val();
		var SelectType = $('#SelectType option:selected').val();
		var Invoice = $('#Invoice').val();
		var Reason = $('#Reason').val();

		var arr = {
			Department : DepartementBRevisiRevisi,
			PostBudget : SelectPostBudget,
			Type : SelectType,
			Invoice : Invoice,
			Reason : Reason,
		};

		var toatString = "";
		var result = "";
		for(var key in arr) {
		   switch(key)
		   {
		    case  "Department" :
		    case  "PostBudget" :
		    case  "Type" :
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
		   }

		}
		if (toatString != "") {
		  toastr.error(toatString, 'Failed!!');
		  return false;
		}

		return true;
	}
		
</script>