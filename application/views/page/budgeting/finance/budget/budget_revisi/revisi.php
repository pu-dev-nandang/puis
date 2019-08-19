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
		if (confirm('Are you sure ?')) {

		}
	})
		
</script>