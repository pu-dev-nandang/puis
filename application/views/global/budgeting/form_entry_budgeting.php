<div class="col-md-12" id = "pageInput">
	
</div>

<script type="text/javascript">
	var arr_Year = <?php echo json_encode($arr_Year) ?>;
	$(document).ready(function() {
		var arr_PostBudget = [];
		arr_PostBudget = <?php echo json_encode($arr_PostBudget) ?>;
		LoadFirstLoad(arr_PostBudget);

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
			$("#pageInput").empty();
			$("#pageNote").remove();
			
			if(arr1.length > 0)
			{
				makeDomExisting(response);
			}
			else
			{
				if(arr_PostBudget.length > 0){
					makeDomAwal(arr_PostBudget);
				}
				else
				{
					$("#pageInput").html('<h3>Post budget not set</h3>');
				}
				
			}

			$("#Year").change(function(){
				var valuee = $(this).val();
				LoadPage('EntryBudget/'+valuee);
			})

		}); 
	}

	function makeDomAwal(arr_PostBudget)
	{
		var arr_bulan = [];
		arr_bulan = <?php echo json_encode($arr_bulan) ?>;
		// var Dom = '';
			var OPFreq = '';
			for (var i = 0; i <= 50; i++) {
				var selected = (i == 0) ? 'selected' : '';
				OPFreq += '<option value = "'+i+'" '+selected+'>'+i+'</option>';
			}

			var OPYear = '';
			if (!$("#Departement").length) {
				OPYear = '<select class = "form-control" id = "Year">';
				for (var i = 0; i < arr_Year.length; i++) {
					var selected = (arr_Year[i].Year == "<?php echo $Year ?>") ? 'selected' : '';
					OPYear += '<option value ="'+arr_Year[i].Year+'" '+selected+'>'+arr_Year[i].Year+'</option>';
				}
				OPYear += '</select>';
			}
			

			// make dom legend status limit & subtotal
			var domLegend = '<div class = "row">'+
								'<div class="col-md-2">'+
									'<p style="color: red;font-size: 20px">(.000)</p>'+
								'</div>'+
								'<div class = "col-md-3 col-md-offset-1">'+
									OPYear+
								'</div>'+
								'<div class = "col-md-3 col-md-offset-2">'+
									'<b>Status : </b><br>'+
									'<i class="fa fa-check-circle" style="color: green;"></i>'+
									' On Budget limit || '+
									'<i class="fa fa-minus-circle" style="color: red;"></i>'+
									' Exceeded Budget Limit'+
								'</div>'+
							'<div>'		
								;
			$("#pageInput").append(domLegend);

			for (var i = 0; i < arr_PostBudget.length; i++) {
				var divBulan = '<div class = "row">'+'<div class = "col-md-12">';
				for (var j = 0; j < arr_bulan.length; j++) {
					if (i == 0) {
						divBulan += '<div class = "col-md-1">'+
										'<div class="form-group">'+	
											'<label>'+arr_bulan[j].MonthName+'</label>'+
											'<input type = "text" class = "form-control InputBulan'+arr_PostBudget[i]['CodePostBudget']+'" placeholder="Input Unit Cost..." value = "0" keyValue = "'+arr_bulan[j].keyValueFirst+'" code = "'+arr_PostBudget[i]['CodePostBudget']+'">'+
										'</div>'+
									'</div>';				
					} else {							
						divBulan += '<div class = "col-md-1">'+
										'<div class="form-group">'+	
											// '<label>'+arr_bulan[j]+'</label>'+
											'<input type = "text" class = "form-control InputBulan'+arr_PostBudget[i]['CodePostBudget']+'" placeholder="Input Unit Cost..." value = "0" keyValue = "'+arr_bulan[j].keyValueFirst+'" code = "'+arr_PostBudget[i]['CodePostBudget']+'">'+
										'</div>'+
									'</div>';	
					}
				}// exit loop bulan
				divBulan += '</div></div>';
					if(i == 0)
					{
						var Dom = '<div class="row" code = "'+arr_PostBudget[i]['CodePostBudget']+'" style = "margin-top : 10px;">'+
									  	'<div class="col-md-2">'+
											'<div class="form-group">'+
												'<label>Post Budget</label>'+
												'<select class="select2-select-00 full-width-fix" id="PostBudget'+arr_PostBudget[i]['CodePostBudget']+'">'+
													'<option value ="'+arr_PostBudget[i]['CodePostBudget']+'" selected>'+arr_PostBudget[i]['PostName']+'-'+arr_PostBudget[i]['RealisasiPostName']+'</option>'+
												 '</select>'+
											'</div>'+
										'</div>'+
										'<div class="col-md-1">'+
											'<div class="form-group">'+
												'<label>Unit Cost</label>'+
												'<input type = "text" class = "form-control" id = "UnitCost'+arr_PostBudget[i]['CodePostBudget']+'" placeholder="Input Unit Cost..." value = "0">'+	
											'</div>'+	
										'</div>'+
										'<div class="col-md-1">'+
											'<div class="form-group">'+
												'<label>Freq</label>'+
												'<select class="select2-select-00 full-width-fix" id = "Freq'+arr_PostBudget[i]['CodePostBudget']+'">'+
													OPFreq+
												'</select>'+
											'</div>'+	
										'</div>'+
										'<div class="col-md-7" id = "tblInputBulan'+arr_PostBudget[i]['CodePostBudget']+'">'+
											divBulan+
										'</div>'+
										'<div class="col-md-1" id = "InputSubtotal'+arr_PostBudget[i]['CodePostBudget']+'" budget = "'+arr_PostBudget[i]['Budget']+'" total = "0" style = "font-size: 12px;">'+
											'<div class="form-group">'+
												'<label>Subtotal</label>'+
												'<p id = "Subtotal'+arr_PostBudget[i]['CodePostBudget']+'">0</p>'+
											'</div>'+	
										'</div>'+
								'</div>';

					}
					else
					{
						var Dom = '<div class="row" code = "'+arr_PostBudget[i]['CodePostBudget']+'">'+
									  	'<div class="col-md-2">'+
											'<div class="form-group">'+
												// '<label>Post Budget</label>'+
												'<select class="select2-select-00 full-width-fix" id="PostBudget">'+
													'<option value ="'+arr_PostBudget[i]['CodePostBudget']+'" selected>'+arr_PostBudget[i]['PostName']+'-'+arr_PostBudget[i]['RealisasiPostName']+'</option>'+
												 '</select>'+
											'</div>'+
										'</div>'+
										'<div class="col-md-1">'+
											'<div class="form-group">'+
												// '<label>Unit Cost</label>'+
												'<input type = "text" class = "form-control" id = "UnitCost'+arr_PostBudget[i]['CodePostBudget']+'" placeholder="Input Unit Cost..." value = "0">'+
											'</div>'+	
										'</div>'+
										'<div class="col-md-1">'+
											'<div class="form-group">'+
												// '<label>Freq</label>'+
												'<select class="select2-select-00 full-width-fix" id = "Freq'+arr_PostBudget[i]['CodePostBudget']+'">'+
													OPFreq+
												'</select>'+
											'</div>'+	
										'</div>'+
										'<div class="col-md-7" id = "tblInputBulan'+arr_PostBudget[i]['CodePostBudget']+'">'+
											divBulan+
										'</div>'+
										'<div class="col-md-1" id = "InputSubtotal'+arr_PostBudget[i]['CodePostBudget']+'" budget = "'+arr_PostBudget[i]['Budget']+'" total = "0" style = "font-size: 12px;">'+
											'<div class="form-group">'+
												'<p id = "Subtotal'+arr_PostBudget[i]['CodePostBudget']+'">0</p>'+
											'</div>'+	
										'</div>'+
								'</div>';

					} // exit if
			
				$("#pageInput").append(Dom);
				if(i == arr_PostBudget.length - 1)
				{
					var DomsubTotalPermonth = '<div class = "row">'+
												'<div class = "col-md-7 col-md-offset-4">'						
											;
					for (var j = 0; j < arr_bulan.length; j++) {
						DomsubTotalPermonth += '<div class = "col-md-1">'+
													'<div class="form-group subTotalPermonth" keyValue = "'+arr_bulan[j].keyValueFirst+'" style = "font-size: 12px;">'+	
														
													'</div>'+
												'</div>';
						
					}

					DomsubTotalPermonth += '</div></div>';								
					$("#pageInput").append(DomsubTotalPermonth);
				}

				$('#UnitCost'+arr_PostBudget[i]['CodePostBudget']).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
				$('.InputBulan'+arr_PostBudget[i]['CodePostBudget']).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});

				$(".InputBulan"+arr_PostBudget[i]['CodePostBudget']).keyup(function(){
					var code = $(this).attr('code');
					funcCheck(code);
					// console.log(arr);
				})

				$("#UnitCost"+arr_PostBudget[i]['CodePostBudget']).keyup(function(){
					var code = $(this).closest('div[class="row"]').attr('code');
					funcCheck(code);
				})

				$("#Freq"+arr_PostBudget[i]['CodePostBudget']).change(function(){
					var code = $(this).closest('div[class="row"]').attr('code');
					funcCheck(code);
				})
			} // exkit looping

		// pPageInput After
		var Note = '<div id = "pageNote"><div class = "row" >'+
						'<div class = "col-md-12">'+
							'<div class = "col-md-4">'+
								'<div class="form-group">'+
									'<label>Note</label>'+
									'<input type = "text" class = "form-control" id = "Note" placeholder="Input Note..." maxlength="70">'+
									'<span id="charsNote">70</span> characters remaining'+
								'</div>'+
							'</div>'+	
							'<div class = "col-md-2 col-md-offset-10" id = "GrandTotal">'+
							
							'</div>'+
						'</div>'+
					'</div>'+
					'<div class = "row">'+
						'<div class = "col-md-12">'+
							'<div class = "col-md-2 col-md-offset-10">'+
								'<button class = "btn btn-success" id = "SaveBudget">Submit</button>'+
							'</div>'+
						'</div>'+
					'</div></div>';			
						;
		$("#pageInput").after(Note);	
		$("#Note").keyup(function(){
			var maxLength = 70;
			var length = $(this).val().length;
			var length = maxLength-length;
			$('#charsNote').text(length);
		})

		$("#SaveBudget").click(function(){
			var ID = '';
			saveData(ID);
		})							
	}

	function saveData(ID,action = '')
	{
		if (confirm("Are you sure?") == true) {
			loadingStart();
			var arr_pass = [];
			var arr_PostBudget = [];
			arr_PostBudget = <?php echo json_encode($arr_PostBudget) ?>;
			var creator_budget_approval = {
				Departement : "<?php echo $Departement ?>",
				Year : arr_PostBudget[0]['Year'],
				Note : $("#Note").val(),
			};
	
			for (var i = 0; i < arr_PostBudget.length; i++) {
				var CodePostBudget = arr_PostBudget[i]['CodePostBudget'];
				var UnitCost = $("#UnitCost"+CodePostBudget).val();
				UnitCost = findAndReplace(UnitCost,".","");
				var Freq = $("#Freq"+CodePostBudget).val();

				// bandingkan freq dengan input bulan

				var arr = [];
				$(".InputBulan"+CodePostBudget).each(function(){
					var valueee = $(this).val();
					var keyvalue = $(this).attr('keyvalue');
					try{
						valueee = findAndReplace(valueee,".","");
					}
					catch(err)
					{
						valueee = valueee;
					}
					arr.push({month : keyvalue,value : valueee,});
				})

				var checkTot_Freq = 0;
				for (var x = 0; x < arr.length; x++) {
					checkTot_Freq = parseInt(checkTot_Freq) + parseInt(arr[x].value);
				}

				if (checkTot_Freq != Freq) {
					toastr.info("Your Month Input is not same with Freq, Please check");
					loadingEnd(1000);
					return;
					break;
				}

				UnitCost = UnitCost * 1000; // for ribuan
				var Total = parseInt(UnitCost * Freq);

				var creator_budget = {
					CodePostBudget : CodePostBudget,
					UnitCost : UnitCost,
					Freq : Freq,
					DetailMonth : arr,
					SubTotal : Total,
				};

				arr_pass.push(creator_budget);
			}

			var url = base_url_js+"budgeting/saveCreatorbudget";
			if(action == '')
			{
				action = (ID == "") ? 'add' : 'edit';
			}
			else
			{
				action = action;
			}
			
			var data = {
				creator_budget :arr_pass,
				creator_budget_approval : creator_budget_approval,
				ID : ID,
				action : action,
				};
			// console.log(data);loadingEnd(1000);return;	
			var token = jwt_encode(data,'UAP)(*');
			$.post(url,{token:token},function (resultJson) {
				var response = jQuery.parseJSON(resultJson);
				LoadFirstLoad(arr_PostBudget);
				loadingEnd(1000);
			}); 
		}
		else
		{
			
		}
		

	}

	function funcCheck(code)
	{
		var UnitCost = $("#UnitCost"+code).val();
		var Freq = $("#Freq"+code).val();
		try{
			UnitCost = findAndReplace(UnitCost,".","");
		}
		catch(err)
		{
			UnitCost = UnitCost;
		}
		
		var Total = parseInt(UnitCost * Freq);
		var Budget = $("#InputSubtotal"+code).attr('budget');
		try{
			var n = Budget.indexOf(".");
			Budget = Budget.substring(0, n);
		}
		catch(err)
		{
			Budget = Budget;
		}

		var arr = [];
		$(".InputBulan"+code).each(function(){
			var valueee = $(this).val();
			try{
				valueee = findAndReplace(valueee,".","");
			}
			catch(err)
			{
				valueee = valueee;
			}
			arr.push(valueee);
		})

		var checkTot_Freq = 0;
		for (var x = 0; x < arr.length; x++) {
			checkTot_Freq = parseInt(checkTot_Freq) + parseInt(arr[x]);
		}

		if (checkTot_Freq > Freq) {
			$(".InputBulan"+code).each(function(){
				$(this).val(0);
				$(this).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
				$(this).maskMoney('mask', '9894');
			})
			$("#InputSubtotal"+code).attr('total',"0");

			toastr.info("Your Input Exceeded than Freq, The Input Was Reset");
		}
		else
		{
			var domStatusLimit = '';
			Budget = Budget / 1000 // for ribuan
			var Sisa = parseInt(Total) - parseInt(Budget); 
			var Sisa2 = parseInt(Budget) - parseInt(Total); 
			if (Total > Budget) {
				domStatusLimit = '<p><i class="fa fa-minus-circle" style="color: red;"></i> ('+formatDigitNumber(Sisa)+')</p>';

			}
			else
			{
				domStatusLimit = '<p><i class="fa fa-check-circle" style="color: green;"></i> ('+formatDigitNumber(Sisa2)+')</p>';
			}

			$("#Subtotal"+code).html('<p>'+ formatDigitNumber(Total)+'</p>'+domStatusLimit);
			$("#InputSubtotal"+code).attr('total',Total);
			funcSubtotalPerMonth();
			funcGrantotal();
		}
	}

	function funcSubtotalPerMonth()
	{
		var arr_bulan = [];
		arr_bulan = <?php echo json_encode($arr_bulan) ?>;
		for (var j = 0; j < arr_bulan.length; j++) {
			var key = arr_bulan[j].keyValueFirst;
			var SubTotalMonth = 0;
			$('input[keyvalue ="'+key+'"]').each(function(){
				val = $(this).val();
				val = findAndReplace(val,".","");
				var code = $(this).attr('code');
				var UnitCost = $("#UnitCost"+code).val();
				UnitCost = findAndReplace(UnitCost,".","");
				var Count = parseInt(val) * parseInt(UnitCost);
				SubTotalMonth = parseInt(SubTotalMonth) + parseInt(Count);
			})
			// SubTotalMonth = SubTotalMonth * 1000 // for ribuan
			var fr = formatDigitNumber(SubTotalMonth);
			// fr = findAndReplace(fr,"Rp.","" );
			// fr = findAndReplace(fr," ","");
			$('.subTotalPermonth[keyvalue ="'+key+'"]').html(fr);
			
		}
	}

	function funcGrantotal()
	{
		var arr_PostBudget = [];
		arr_PostBudget = <?php echo json_encode($arr_PostBudget) ?>;
		var GrandTotal = 0;
		for (var i = 0; i < arr_PostBudget.length; i++) {
			var code = arr_PostBudget[i]['CodePostBudget'];
			var subTotal = $("#InputSubtotal"+code).attr('total');
			GrandTotal = parseInt(GrandTotal) + parseInt(subTotal);
		}
		GrandTotal = GrandTotal * 1000 // untuk ribuan
		// get all total
		$("#GrandTotal").html('<p style = "color : green">'+'Grand Total : <br>'+formatRupiah(GrandTotal)+'</p>');
	}

	function makeDomExisting(response)
	{
		// check apakah divisi finance atau tidak
		var fin = "<?php echo $fin ?>";
		var creator_budget_approval = response['creator_budget_approval'];
		var creator_budget = response['creator_budget'];
		var arr_PostBudget = [];
		arr_PostBudget = <?php echo json_encode($arr_PostBudget) ?>;
		var arr_bulan = [];
		arr_bulan = <?php echo json_encode($arr_bulan) ?>;

		var OPYear = '';
		if (!$("#Departement").length) {
			OPYear = '<select class = "form-control" id = "Year">';
			for (var i = 0; i < arr_Year.length; i++) {
				var selected = (arr_Year[i].Year == "<?php echo $Year ?>") ? 'selected' : '';
				OPYear += '<option value ="'+arr_Year[i].Year+'" '+selected+'>'+arr_Year[i].Year+'</option>';
			}
			OPYear += '</select>';
		}

		// make dom legend status limit & subtotal
		var domLegend = '<div class = "row">'+
							'<div class="col-md-3">'+
								'<p style="color: red;font-size: 20px">(.000)</p>'+
							'</div>'+
							'<div class = "col-md-3 col-md-offset-1">'+
								OPYear+
							'</div>'+
							'<div class = "col-md-3 col-md-offset-2">'+
								'<b>Status : </b><br>'+
								'<i class="fa fa-check-circle" style="color: green;"></i>'+
								' On Budget limit || '+
								'<i class="fa fa-minus-circle" style="color: red;"></i>'+
								' Exceeded Budget Limit'+
							'</div>'+
						'<div>'		
							;

			$("#pageInput").append(domLegend);

			for (var i = 0; i < arr_PostBudget.length; i++) {
				var divBulan = '<div class = "row">'+'<div class = "col-md-12">';
				var DetailMonth = creator_budget[i]['DetailMonth'];
				DetailMonth = jQuery.parseJSON(DetailMonth);

				var Cost = creator_budget[i]['UnitCost'];
				var n = Cost.indexOf(".");
				var Cost = Cost.substring(0, n);
				Cost = parseInt(Cost) / 1000; // for ribuan
				for (var j = 0; j < arr_bulan.length; j++) {
					if (i == 0) {
						divBulan += '<div class = "col-md-1">'+
										'<div class="form-group">'+	
											'<label>'+arr_bulan[j].MonthName+'</label>'+
											'<input type = "text" class = "form-control InputBulan'+arr_PostBudget[i]['CodePostBudget']+'" placeholder="Input Unit Cost..." value = "'+DetailMonth[j].value+'" keyValue = "'+arr_bulan[j].keyValueFirst+'" code = "'+arr_PostBudget[i]['CodePostBudget']+'" keyvalueD = "'+DetailMonth[j].month+'">'+
										'</div>'+
									'</div>';				
					} else {							
						divBulan += '<div class = "col-md-1">'+
										'<div class="form-group">'+	
											// '<label>'+arr_bulan[j]+'</label>'+
											'<input type = "text" class = "form-control InputBulan'+arr_PostBudget[i]['CodePostBudget']+'" placeholder="Input Unit Cost..." value = "'+DetailMonth[j].value+'" keyValue = "'+arr_bulan[j].keyValueFirst+'" code = "'+arr_PostBudget[i]['CodePostBudget']+'" keyvalueD = "'+DetailMonth[j].month+'">'+
										'</div>'+
									'</div>';	
					}
				}// exit loop bulan
				divBulan += '</div></div>';

				var OPFreq = '';
					for (var y = 0; y <= 50; y++) {
						var selected = (y == creator_budget[i]['Freq']) ? 'selected' : '';
						OPFreq += '<option value = "'+y+'" '+selected+'>'+y+'</option>';
					}

					if(i == 0)
					{
						var Dom = '<div class="row" code = "'+arr_PostBudget[i]['CodePostBudget']+'" style = "margin-top : 10px;">'+
									  	'<div class="col-md-2">'+
											'<div class="form-group">'+
												'<label>Post Budget</label>'+
												'<select class="select2-select-00 full-width-fix" id="PostBudget'+arr_PostBudget[i]['CodePostBudget']+'">'+
													'<option value ="'+arr_PostBudget[i]['CodePostBudget']+'" selected>'+arr_PostBudget[i]['PostName']+'-'+arr_PostBudget[i]['RealisasiPostName']+'</option>'+
												 '</select>'+
											'</div>'+
										'</div>'+
										'<div class="col-md-1">'+
											'<div class="form-group">'+
												'<label>Unit Cost</label>'+
												'<input type = "text" class = "form-control" id = "UnitCost'+arr_PostBudget[i]['CodePostBudget']+'" placeholder="Input Unit Cost..." value = "'+Cost +'">'+	
											'</div>'+	
										'</div>'+
										'<div class="col-md-1">'+
											'<div class="form-group">'+
												'<label>Freq</label>'+
												'<select class="select2-select-00 full-width-fix" id = "Freq'+arr_PostBudget[i]['CodePostBudget']+'">'+
													OPFreq+
												'</select>'+
											'</div>'+	
										'</div>'+
										'<div class="col-md-7" id = "tblInputBulan'+arr_PostBudget[i]['CodePostBudget']+'">'+
											divBulan+
										'</div>'+
										'<div class="col-md-1" id = "InputSubtotal'+arr_PostBudget[i]['CodePostBudget']+'" budget = "'+arr_PostBudget[i]['Budget']+'" total = "'+creator_budget[i]['SubTotal']+'" style = "font-size: 12px;">'+
											// '<br><p>Limit : '+formatRupiah(arr_PostBudget[i]['Budget'])+'</p>'+
											// '<p id = "Subtotal'+arr_PostBudget[i]['CodePostBudget']+'">'+formatRupiah(creator_budget[i]['SubTotal'])+'</p>'+
											'<div class="form-group">'+
												'<label>Subtotal</label>'+
												'<p id = "Subtotal'+arr_PostBudget[i]['CodePostBudget']+'">'+formatDigitNumber(creator_budget[i]['SubTotal'])+'</p>'+
											'</div>'+
										'</div>'+
										// '<div class="col-md-1" id = "BatasMax'+arr_PostBudget[i]['CodePostBudget']+'">'

										// '</div>'+
								'</div>';

					}
					else
					{
						var Dom = '<div class="row" code = "'+arr_PostBudget[i]['CodePostBudget']+'">'+
									  	'<div class="col-md-2">'+
											'<div class="form-group">'+
												// '<label>Post Budget</label>'+
												'<select class="select2-select-00 full-width-fix" id="PostBudget">'+
													'<option value ="'+arr_PostBudget[i]['CodePostBudget']+'" selected>'+arr_PostBudget[i]['PostName']+'-'+arr_PostBudget[i]['RealisasiPostName']+'</option>'+
												 '</select>'+
											'</div>'+
										'</div>'+
										'<div class="col-md-1">'+
											'<div class="form-group">'+
												// '<label>Unit Cost</label>'+
												'<input type = "text" class = "form-control" id = "UnitCost'+arr_PostBudget[i]['CodePostBudget']+'" placeholder="Input Unit Cost..." value = "'+ Cost +'">'+
											'</div>'+	
										'</div>'+
										'<div class="col-md-1">'+
											'<div class="form-group">'+
												// '<label>Freq</label>'+
												'<select class="select2-select-00 full-width-fix" id = "Freq'+arr_PostBudget[i]['CodePostBudget']+'">'+
													OPFreq+
												'</select>'+
											'</div>'+	
										'</div>'+
										'<div class="col-md-7" id = "tblInputBulan'+arr_PostBudget[i]['CodePostBudget']+'">'+
											divBulan+
										'</div>'+
										'<div class="col-md-1" id = "InputSubtotal'+arr_PostBudget[i]['CodePostBudget']+'" budget = "'+arr_PostBudget[i]['Budget']+'" total = "'+creator_budget[i]['SubTotal']+'" style = "font-size: 12px;">'+
											// '<p>Limit : '+formatRupiah(arr_PostBudget[i]['Budget'])+'</p>'+
											// '<p id = "Subtotal'+arr_PostBudget[i]['CodePostBudget']+'">'+formatRupiah(creator_budget[i]['SubTotal'])+'</p>'+
											'<div class="form-group">'+
												'<p id = "Subtotal'+arr_PostBudget[i]['CodePostBudget']+'">'+formatDigitNumber(creator_budget[i]['SubTotal'])+'</p>'+
											'</div>'+
										'</div>'+
										// '<div class="col-md-1" id = "BatasMax'+arr_PostBudget[i]['CodePostBudget']+'">'

										// '</div>'+
								'</div>';

					} // exit if

				$("#pageInput").append(Dom);
				if(i == arr_PostBudget.length - 1)
				{
					var DomsubTotalPermonth = '<div class = "row">'+
												'<div class = "col-md-7 col-md-offset-4">'						
											;
					for (var j = 0; j < arr_bulan.length; j++) {
						DomsubTotalPermonth += '<div class = "col-md-1">'+
													'<div class="form-group subTotalPermonth" keyValue = "'+arr_bulan[j].keyValueFirst+'" style = "font-size: 12px;">'+	
														
													'</div>'+
												'</div>';
						
					}

					DomsubTotalPermonth += '</div></div>';								
					$("#pageInput").append(DomsubTotalPermonth);
				}

				$('#UnitCost'+arr_PostBudget[i]['CodePostBudget']).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
				$('#UnitCost'+arr_PostBudget[i]['CodePostBudget']).maskMoney('mask', '9894');
				$('.InputBulan'+arr_PostBudget[i]['CodePostBudget']).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
				$('.InputBulan'+arr_PostBudget[i]['CodePostBudget']).maskMoney('mask', '9894');

				var code = arr_PostBudget[i]['CodePostBudget'];
				funcCheck(code);
				$(".InputBulan"+arr_PostBudget[i]['CodePostBudget']).keyup(function(){
					var code = $(this).attr('code');
					funcCheck(code);
					// console.log(arr);
				})

				$("#UnitCost"+arr_PostBudget[i]['CodePostBudget']).keyup(function(){
					var code = $(this).closest('div[class="row"]').attr('code');
					funcCheck(code);
				})

				$("#Freq"+arr_PostBudget[i]['CodePostBudget']).change(function(){
					var code = $(this).closest('div[class="row"]').attr('code');
					// $(this).closest('div[class="row"]').css( "background-color", "red" );
					// console.log(code);
					funcCheck(code);
				})

			} // exit looping

		var StatusApproval = creator_budget_approval[0]['Approval'];	
		// pageInput After
		var ApprovalBtn = (fin == "1") ? '<div class = "col-md-4">'+'<button type="button" class="btn btn-default" code="'+code+'" ID = "ApprovalBtn"> <i class="fa fa-handshake-o" aria-hidden="true"></i> Approve</button></div>' : '';
		var ExportExcel = (StatusApproval == 1) ? '<div class = "col-md-4">'+'<button type="button" class="btn btn-default" ID = "ExportExcel" Year = "'+creator_budget_approval[0]['Year']+'" Departement = "'+creator_budget_approval[0]['Departement']+'"> <i class="fa fa-file-excel-o"></i> Excel</button></div>' : '';
		if (StatusApproval == 1) {
			ApprovalBtn = ExportExcel;
		}
		var Note = '<div id = "pageNote"><div class = "row">'+
						'<div class = "col-md-12">'+
							'<div class = "col-md-4">'+
								'<div class="form-group">'+
									'<label>Note</label>'+
									'<input type = "text" class = "form-control" id = "Note" placeholder="Input Note..." maxlength="70" value = "'+creator_budget_approval[0]['Note']+'">'+
									'<span id="charsNote">70</span> characters remaining'+
								'</div>'+
							'</div>'+	
							'<div class = "col-md-2 col-md-offset-10" id = "GrandTotal">'+
							
							'</div>'+
						'</div>'+
					'</div>'+
					'<div class = "row">'+
						'<div class = "col-md-12">'+
							'<div class = "col-md-2 col-md-offset-10">'+
								'<div class = "col-md-4">'+
									'<button class = "btn btn-success" id = "SaveBudget">Submit</button>'+
								'</div>'+	
								ApprovalBtn
							'</div>'+
						'</div>'+
					'</div></div>';			
						;
		$("#pageInput").after(Note);

		funcGrantotal();

		$("#Note").keyup(function(){
			var maxLength = 70;
			var length = $(this).val().length;
			var length = maxLength-length;
			$('#charsNote').text(length);
		})

		$("#SaveBudget").click(function(){
			var ID = creator_budget_approval[0]['ID'];
			saveData(ID);
		})

		$("#ApprovalBtn").click(function(){
			var ID = creator_budget_approval[0]['ID'];
			saveData(ID,'approval');
		})			

		if (StatusApproval == 1) {
			dofuncApproved();
			doFuncExportExcel();
		}


	}

	function doFuncExportExcel()
	{
		$("#ExportExcel").click(function(){
			var Year = $(this).attr('Year');
			var Departement = $(this).attr('Departement');

			var url = base_url_js+'budgeting/export_excel_budget_creator';
			data = {
			  Year : Year,
			  Departement : Departement,
			}
			var token = jwt_encode(data,"UAP)(*");
			FormSubmitAuto(url, 'POST', [
			    { name: 'token', value: token },
			]);
		})
	}

	function dofuncApproved()
	{
		var waitForEl = function(selector, callback) {
		  if (jQuery(selector).length) {
		    callback();
		  } else {
		    setTimeout(function() {
		      waitForEl(selector, callback);
		    }, 100);
		  }
		};

		waitForEl("#SaveBudget", function() {
		   $("#SaveBudget").remove();
		});

		waitForEl("#ApprovalBtn", function() {
		   $("#ApprovalBtn").remove();
		});

		if(jQuery("#pageInputApproval").length)
		{
			$("#pageInputApproval input").each(function(){
				$(this).attr('readonly',true);
				$(this).attr('disabled',true);
			})

			$("#pageInputApproval select:not(#Year)").each(function(){
				$(this).attr('readonly',true);
				$(this).attr('disabled',true);
			})
		}
		else
		{
			$("#pageInput input").each(function(){
				$(this).attr('readonly',true);
				$(this).attr('disabled',true);
			})

			$("#pageNote input").each(function(){
				$(this).attr('readonly',true);
				$(this).attr('disabled',true);
			})

			$("#pageInput select:not(#Year)").each(function(){
				$(this).attr('readonly',true);
				$(this).attr('disabled',true);
			})
		}
	}
</script>