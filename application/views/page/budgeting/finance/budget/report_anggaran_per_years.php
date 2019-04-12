<div class="row">
	<div class="col-md-12">
		<div class="col-md-6 col-md-offset-3">
			<div class="thumbnail" style="height: 100px">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<div class="form-group">
							<label>Year</label>
							<select class="select2-select-00 full-width-fix" id="Years">
								 <?php for ($i=0; $i < count($arr_Year); $i++): ?>
								 	<?php $selected = ($arr_Year[$i]['Activated'] == 1) ? 'selected' : ''; ?>
								 	<option value="<?php echo $arr_Year[$i]['Year'] ?>" <?php echo $selected ?>><?php echo $arr_Year[$i]['Year'].'-'.($arr_Year[$i]['Year'] + 1) ?></option>
								 <?php endfor ?>
							 </select>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id = "DomContent">
	
</div>
<script type="text/javascript">
	var ClassDt = {
		arr_post : [],
		arr_BudgetCategory : [],
		arr_HeadAccount : [],
		arr_pass : [],
	};
	
	$(document).ready(function() {
		$('#Years').select2({
		   //allowClear: true
		});
		LoadFirstLoad();
	}); // exit document Function

	function LoadFirstLoad()
	{
		var url = base_url_js+"rest/__budgeting/getAllBudget";
		var Year = $("#Years").val();
		var data = {
					    Year : Year,
					    auth : 's3Cr3T-G4N',
					};
		var token = jwt_encode(data,'UAP)(*');
		$.post(url,{token:token},function (resultJson) {
			ClassDt.arr_post = resultJson['post'];
			ClassDt.arr_BudgetCategory = resultJson['BudgetCategory'];
			ClassDt.arr_HeadAccount = resultJson['HeadAccount'];
			ClassDt.arr_pass =resultJson['post']; 
			MakeDOM();
			loadingEnd(1000);
		});	
	}

	function MakeDOM()
	{
		var html = '';
			html += '<div class = "row" style = "margin-top : 10px;">'+
						'<div id="ViewMap" class="col-md-8 col-md-offset-2"></div>'+
					'</div>'+
					'<div class = "row" style = "margin-top : 10px;">'+
						'<div class = "col-md-8 col-md-offset-2" align = "right">'+
							'<button class = "btn btn-default" id = "btnMerge">Merge</button>'+
							'&nbsp<button class="btn btn-excel" id = "export_excel_mapping"><i class="fa fa-file-excel-o"></i> Excel</button>'+
							'&nbsp<button class = "btn btn-danger" id = "cancelMapping">Cancel</button>'+
						'</div>'+
					'</div>';		
			$('#DomContent').html(html);
			html = '';
			html += '<table class="table table-bordered tableData" id ="tableDataMapping">'+
						'<caption><h4>Mapping Budget</h4></caption>'+
						'<thead>'+
							'<tr>'+
								'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
	                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Budget Category</th>'+
	                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Total</th>'+
							'</tr>'+
						'</thead>';
			html += '<tbody>';
				var dt = ClassDt.arr_pass;
				for (var i = 0; i < dt.length; i++) {
					var arr_HeadAccount = dt[i].HeadAccount;
					html += '<tr data-toggle="collapse" data-target="#'+dt[i].CodePost+'" class="accordion-toggle" CodePost= "'+dt[i].CodePost+'" total = "'+dt[i].total+'">'+
								'<td>'+(i+1)+'</td>'+
								'<td>'+dt[i].CodePost+' || '+dt[i].PostName+'</td>'+
								'<td>'+formatRupiah(dt[i].total)+'</td>'+
							'</tr>'+
							'<tr>'+
								'<td colspan="3" class="hiddenRow">'+
									'<div class="accordian-body collapse" id="'+dt[i].CodePost+'" style = "margin : 15px !important;">'+
										'<table class="table table-bordered tableData">'+
											'<thead>'+
												'<tr>'+
													'<th width = "3%" style = "text-align: center;">No</th>'+
													'<th style = "text-align: center;">Head Account</th>'+
													'<th style = "text-align: center;">Department</th>'+
													'<th style = "text-align: center;">Total</th>'+
												'</tr>'+
											'</thead>'+
											'<tbody>';		
									for (var j = 0; j < arr_HeadAccount.length; j++) {
										var dt2 = arr_HeadAccount[j];
										if (!dt2.hasOwnProperty("Merger")) {
											arr_HeadAccount[j]['Merger'] = [];
										}
										var merger = arr_HeadAccount[j]['Merger'].toString();
										html += '<tr>'+
													'<td>'+(j+1)+'</td>'+
													'<td>'+arr_HeadAccount[j].CodeHeadAccount+' || '+arr_HeadAccount[j].NameHeadAccount+' '+'<input type = "checkbox" class = "HA_unifrom" CodeHeadAccount = "'+arr_HeadAccount[j].CodeHeadAccount+'" total = "'+arr_HeadAccount[j].total+'" merger = "'+merger+'">'+'</td>'+
													'<td>'+arr_HeadAccount[j].NameUnitDiv+'</td>'+
													'<td>'+formatRupiah(arr_HeadAccount[j].total)+'</td>'+
												'</tr>';	
									}
										html += '</tbody></table>';
									html += ' </div>'+
								' </td>'+
							'</tr>';		
				}

			html += '</tbody></table>';	
			$('#ViewMap').html(html);

	}

	$(document).off('show.bs.collapse', '.accordian-body').on('show.bs.collapse', '.accordian-body',function(e) {
	    $(this).closest("table")
	           .find(".collapse.in")
	           .not(this)
	           .collapse('toggle')
	});

	$(document).off('click', '.HA_unifrom').on('click', '.HA_unifrom',function(e) {
	   var CodePost = $(this).closest('.accordian-body').attr('id');
	   // uncheck selain checkbox CodePost
	   $('.accordian-body[id != "'+CodePost+'"]').find('.HA_unifrom').prop('checked',false);
	});

	$(document).off('click', '#btnMerge').on('click', '#btnMerge',function(e) {
		if (confirm('Are you sure ?')) {
			// loadingStart();
			var rr = [];
			var dt = ClassDt.arr_post;
			console.log(dt);
			var tt = [];
			var dd = [];
			var bc = '';
			$('.HA_unifrom:checked').each(function(){
				var codeheadaccount = $(this).attr('codeheadaccount');
				var total = $(this).attr('total');
				var merger = $(this).attr('merger');
				var temp = {
					codeheadaccount : codeheadaccount,
					total : total,
					merger : merger,
				}
				tt.push(temp);
				bc = $(this).closest('.accordian-body').attr('id');
			})

			for (var i = 0; i < dt.length; i++) {
				var CodePost = dt[i].CodePost;
				if (CodePost == bc) {
					var ha = dt[i].HeadAccount;
					var TotMerge = 0;
					var temp3 = [];
					for (var j = 0; j < ha.length; j++) {
						var CodeHeadAccount = ha[j].CodeHeadAccount;
						var bool = false;
						var NameHeadAccount = '';
						for (var k = 0; k < tt.length; k++) {
							var CodeHeadAccount_ = tt[k].codeheadaccount;
							if (CodeHeadAccount_ != 'Merge') {
								if (CodeHeadAccount_ == CodeHeadAccount) {
									dd.push(CodeHeadAccount_);	
									TotMerge = parseFloat(TotMerge) + parseFloat(tt[k].total);
									bool = true;
									if (NameHeadAccount == '') {
										NameHeadAccount = ha[j].NameHeadAccount;
									}
								}
							}
							else
							{
								var merger = tt[k].merger;
								merger = merger.split(',');
								for (var m = 0; m < merger.length; m++) {
									var mer = merger[m];
									if (mer == CodeHeadAccount) {	
										TotMerge = parseFloat(TotMerge) + parseFloat(ha[j].total);
										bool = true;
										if (NameHeadAccount == '') {
											NameHeadAccount = ha[j].NameHeadAccount;
										}
										dd.push(mer);
									}
								}
							}
							
							
						}

						if (!bool) {
							temp3.push(ha[j]);
						}

					}

					var temp = {
						CodeDiv : 'Merge',
						CodeHeadAccount : 'Merge',
						NameHeadAccount : NameHeadAccount,
						NameUnitDiv : 'Merge',
						UnitDiv : 'Merge',
						total : TotMerge,
						Merger: dd,
					};

					temp3.push(temp);

					var temp2 = {
						CodePost : CodePost,
						HeadAccount : temp3,
						PostName : dt[i].PostName,
						total : dt[i].total,
					};

					rr.push(temp2);
				}
				else
				{
					rr.push(dt[i]);
				}
			}
			console.log(rr);
			ClassDt.arr_pass = rr;
			MakeDOM();
			// loadingEnd(1000);
		}
	});

	$(document).off('click', '#cancelMapping').on('click', '#cancelMapping',function(e) {
		if (confirm('Are you sure ?')) {
			$('.pageAnchor[page="report_anggaran_per_years"]').trigger('click');
		}
	});
</script>
