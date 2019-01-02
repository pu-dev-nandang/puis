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
							<div class="form-group">
								<label class="control-label">Category / Group</label>
								<select class = "select2-select-00 full-width-fix" id = "PostBudget">

								</select>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-md-offset-1">
						<div class="well">
							<div style="margin-top: -15px">
								<label>Budget These Month</label>
							</div>
							<div id = "Page_Budget">
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id ="Page_Input_PR" style="margin-top: 10px">
	
</div>
<script type="text/javascript">
	var arr_Year = <?php echo json_encode($arr_Year) ?>;
	$(document).ready(function() {
		LoadFirstLoad();

		function LoadFirstLoad()
		{
			loadYear();
			getAllDepartementPU();
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

			$("#Year").change(function(){
				loadSelectPostRealiasi();
			})
		}

		function getAllDepartementPU()
		{
		  var url = base_url_js+"api/__getAllDepartementPU";
		  $('#DepartementPost').empty();
		  $.post(url,function (data_json) {
		    for (var i = 0; i < data_json.length; i++) {
		        var selected = (i==11) ? 'selected' : '';
		        $('#DepartementPost').append('<option value="'+ data_json[i]['Code']  +'" '+selected+'>'+data_json[i]['Name2']+'</option>');
		    }
		   
		    $('#DepartementPost').select2({
		       //allowClear: true
		    });

		    $("#DepartementPost").change(function(){
		    	loadSelectPostRealiasi();
		    })

		    loadSelectPostRealiasi();

		  })
		}

		function loadSelectPostRealiasi()
		{
			var Year = $("#Year").val();
			var Departement = $("#DepartementPost").val();
			var url = base_url_js+"budgeting/getPostBudgetDepartement";

			var data = {
					    Year : Year,
						Departement : Departement,
					};
			var token = jwt_encode(data,'UAP)(*');
			$.post(url,{token:token},function (resultJson) {
				var response = jQuery.parseJSON(resultJson);
				$("#PostBudget").empty();
				if (response.length > 0) {
					var PostBudget = '';
					var abc = 0;
					for (var i = 0; i < response.length; i++) {
						var selected = (i == 0) ? 'selected' : '';
						PostBudget += '<option value ="'+response[i].CodePost+'" '+selected+'>'+response[i].PostName+'</option>';
						abc++;
						
					}

					if (abc > 0) {
						$("#PostBudget").append(PostBudget);
						$('#PostBudget').select2({
						   //allowClear: true
						});
						loadPostBudgetThisMonth();
						$("#PostBudget").change(function(){
							loadPostBudgetThisMonth();
						})
					}
					else
					{
						toastr.info('No Result Data in category, please add in config navigation'); 
					}
					
				}
				else
				{
					toastr.info('No Result Data in category, please add in config navigation'); 
				}

			}).fail(function() {
			  toastr.info('No Result Data'); 
			}).always(function() {
			                
			});
		}

		function loadPostBudgetThisMonth()
		{
			var Departement = $("#DepartementPost").val();
			var PostBudget = $('#PostBudget').val();
			var url = base_url_js+"budgeting/PostBudgetThisMonth_Department";
			var data = {
						Departement : Departement,
						PostBudget : PostBudget
					};
			var token = jwt_encode(data,'UAP)(*');
			$.post(url,{token:token},function (resultJson) {
				var response = jQuery.parseJSON(resultJson);
				if (response.length > 0) {
					load_budget(response)
				}
				else
				{
					toastr.info('Budget doesn\'t exist'); 
				}
			}).fail(function() {
			  toastr.info('No Result Data'); 
			}).always(function() {
			                
			});
		}

		function load_budget(response)
		{
			$("#Page_Budget").empty();
			var html = '<div class = row>'+
							'<div class = "col-md-12">'+
								'<div class = "table-responsive">'+
									'<table class="table table-bordered tableData" id ="tableData3">'+
										'<thead>'+
										'<tr>'+
											'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">Choose</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Budget</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Remaining</th>'+
										'</tr></thead>'+
										'<tbody></tbody></table></div></div></div>';
			$("#Page_Budget").html(html);
			var isi = '';
			for (var i = 0; i < response.length; i++) {
				isi += '<tr>';
				isi += '<td><input type="checkbox" class="uniform" value="'+response[i]['Value']+'" id_table="'+response[i]['ID']+'">'+
						'<td>'+response[i]['RealisasiPostName']+'</td>'+
						'<td>'+formatRupiah(response[i]['Value'])+'</td>';
				isi += '</tr>';		

			}

			$("#tableData3 tbody").append(isi);
			var table = $("#tableData3").DataTable({
			    'iDisplayLength' : 5,
			    'ordering' : true,
			});

			loading_page("#Page_Input_PR");
			setTimeout(function () {
			    Load_input_PR();
			},1000);
		}

		function Load_input_PR()
		{
			var html = '<div class = "row">'+
							'<div class = "col-md-12">'+
								'<div class = "table-responsive">'+
									'<table class="table table-bordered tableData" id ="table_input_pr">'+
									'<thead>'+
									'<tr>'+
										'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Item</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Desc</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Qty</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Unit Cost</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Sub Total</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Date Needed</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Budget Status</th>'+
			                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Upload Files</th>'+
									'</tr></thead>'+
									'<tbody></tbody></table></div></div></div>';
			$("#Page_Input_PR").html(html);						

		}
	}); // exit document Function
</script>