<div class="col-md-12" id = "pageInput">
	
</div>

<script type="text/javascript">
	$(document).ready(function() {
		var arr_PostBudget = [];
		arr_PostBudget = <?php echo json_encode($arr_PostBudget) ?>;
		LoadFirstLoad(arr_PostBudget)
	}); // exit document Function

	function LoadFirstLoad(arr_PostBudget)
	{
		// checkData pada table creator_budget_approval dan creator_budget
		var url = base_url_js+"budgeting/getCreatorBudget";
		var data = {
				    Year : "<?php echo $Year ?>",
					Departement : "<?php echo $Departement ?>",
				};
				var token = jwt_encode(data,'UAP)(*');
		$.post(url,{token:token},function (resultJson) {
			var response = jQuery.parseJSON(resultJson);
			var arr1 = response['creator_budget_approval'];
			if(arr1.length > 0)
			{
				makeDomExisting(response);
			}
			else
			{
				makeDomAwal(arr_PostBudget);
			}
		}); 
	}

	function makeDomAwal(arr_PostBudget)
	{
		console.log(arr_PostBudget);
		var arr_bulan = [];
		arr_bulan = <?php echo json_encode($arr_bulan) ?>;
		console.log(arr_bulan);
		// var Dom = '';
			var OPFreq = '';
			for (var i = 1; i <= 12; i++) {
				var selected = (i == 12) ? 'selected' : '';
				OPFreq += '<option value = "'+i+'" '+selected+'>'+i+'</option>';
			}

			for (var i = 0; i < arr_PostBudget.length; i++) {
				var divBulan = '<div class = "row">'+'<div class = "col-xs-12">';
				for (var j = 0; j < arr_bulan.length; j++) {
					if (i == 0) {
						divBulan += '<div class = "col-xs-1">'+
										'<div class="form-group">'+	
											'<label>'+arr_bulan[j].MonthName+'</label>'+
											'<input type = "text" class = "form-control InputBulan'+arr_PostBudget[i]['CodePostBudget']+'" placeholder="Input Unit Cost..." value = "0" keyValue = "'+arr_bulan[j].keyValue+'">'+
										'</div>'+
									'</div>';				
					} else {							
						divBulan += '<div class = "col-xs-1">'+
										'<div class="form-group">'+	
											// '<label>'+arr_bulan[j]+'</label>'+
											'<input type = "text" class = "form-control InputBulan'+arr_PostBudget[i]['CodePostBudget']+'" placeholder="Input Unit Cost..." value = "0" keyValue = "'+arr_bulan[j].keyValue+'">'+
										'</div>'+
									'</div>';	
					}
				}// exit loop bulan
				divBulan += '</div></div>';
			if(i == 0)
			{
				var Dom = '<div class="row">'+
							  	'<div class="col-xs-2">'+
									'<div class="form-group">'+
										'<label>Post Budget</label>'+
										'<select class="select2-select-00 full-width-fix" id="PostBudget'+arr_PostBudget[i]['CodePostBudget']+'">'+
											'<option value ="'+arr_PostBudget[i]['CodePostBudget']+'" selected>'+arr_PostBudget[i]['PostName']+'-'+arr_PostBudget[i]['RealisasiPostName']+'</option>'+
										 '</select>'+
									'</div>'+
								'</div>'+
								'<div class="col-xs-2">'+
									'<div class="form-group">'+
										'<label>Unit Cost</label>'+
										'<input type = "text" class = "form-control" id = "UnitCost'+arr_PostBudget[i]['CodePostBudget']+'" placeholder="Input Unit Cost..." value = "0">'+	
									'</div>'+	
								'</div>'+
								'<div class="col-xs-1">'+
									'<div class="form-group">'+
										'<label>Freq</label>'+
										'<select class="select2-select-00 full-width-fix" id = "UnitCost'+arr_PostBudget[i]['CodePostBudget']+'">'+
											OPFreq+
										'</select>'+
									'</div>'+	
								'</div>'+
								'<div class="col-xs-6" id = "tblInputBulan'+arr_PostBudget[i]['CodePostBudget']+'">'+
									divBulan+
								'</div>'+
								'<div class="col-xs-1" id = "InputSubtotal'+arr_PostBudget[i]['CodePostBudget']+'">'+
									'<p>Limit : '+formatRupiah(arr_PostBudget[i]['Budget'])+'</p>'+
									'<p id = "Subtotal'+arr_PostBudget[i]['CodePostBudget']+'"></p>'+
								'</div>'+
								// '<div class="col-xs-1" id = "BatasMax'+arr_PostBudget[i]['CodePostBudget']+'">'

								// '</div>'+
						'</div>';

			}
			else
			{
				var Dom = '<div class="row">'+
							  	'<div class="col-xs-2">'+
									'<div class="form-group">'+
										// '<label>Post Budget</label>'+
										'<select class="select2-select-00 full-width-fix" id="PostBudget">'+
											'<option value ="'+arr_PostBudget[i]['CodePostBudget']+'" selected>'+arr_PostBudget[i]['PostName']+'-'+arr_PostBudget[i]['RealisasiPostName']+'</option>'+
										 '</select>'+
									'</div>'+
								'</div>'+
								'<div class="col-xs-2">'+
									'<div class="form-group">'+
										// '<label>Unit Cost</label>'+
										'<input type = "text" class = "form-control" id = "UnitCost'+arr_PostBudget[i]['CodePostBudget']+'" placeholder="Input Unit Cost..." value = "0">'+
									'</div>'+	
								'</div>'+
								'<div class="col-xs-1">'+
									'<div class="form-group">'+
										// '<label>Freq</label>'+
										'<select class="select2-select-00 full-width-fix" id = "UnitCost'+arr_PostBudget[i]['CodePostBudget']+'">'+
											OPFreq+
										'</select>'+
									'</div>'+	
								'</div>'+
								'<div class="col-xs-6" id = "tblInputBulan'+arr_PostBudget[i]['CodePostBudget']+'">'+
									divBulan+
								'</div>'+
								'<div class="col-xs-1" id = "InputSubtotal'+arr_PostBudget[i]['CodePostBudget']+'">'+
									'<p>Limit : '+formatRupiah(arr_PostBudget[i]['Budget'])+'</p>'
								'</div>'+
								// '<div class="col-xs-1" id = "BatasMax'+arr_PostBudget[i]['CodePostBudget']+'">'

								// '</div>'+
						'</div>';

					} // exit if
			
				$("#pageInput").append(Dom);	
				$('#UnitCost'+arr_PostBudget[i]['CodePostBudget']).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
				$('.InputBulan'+arr_PostBudget[i]['CodePostBudget']).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		} // exkit looping

		
	}
</script>