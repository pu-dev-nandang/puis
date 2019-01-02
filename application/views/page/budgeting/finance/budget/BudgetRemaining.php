<div class="row">
	<div class="col-md-12">
		<div class="col-md-6 col-md-offset-3">
			<div class="thumbnail" style="height: 100px">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<div class="form-group">
							<label>Year</label>
							<select class="select2-select-00 full-width-fix" id="Years">
							     <!-- <option></option> -->
							 </select>
						</div>	
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<p style="color: red;font-size: 20px">(.000)</p>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row" id = "PageTable" style="margin-top: 10px;margin-right: 10px;margin-left: 10px">

</div>

<div class="row" id = "PageDetail" style="margin-top: 30px;margin-right: 10px;margin-left: 10px">

</div>

<script type="text/javascript">
	$(document).ready(function() {
		LoadFirstLoad()
	    
	}); // exit document Function

	function LoadFirstLoad()
	{
		$("#pageInputApproval").remove();
		$("#pageInput").remove();
		// load Year
		$("#Years").empty();
		var url = base_url_js+'budgeting/table_all/cfg_dateperiod/1';
		var thisYear = (new Date()).getFullYear();
		$.post(url,function (resultJson) {
			var response = jQuery.parseJSON(resultJson);
			for(var i=0;i<response.length;i++){
			    //var selected = (i==0) ? 'selected' : '';
			    var selected = (response[i].Activated==1) ? 'selected' : '';
			    $('#Years').append('<option value="'+response[i].Year+'" '+selected+'>'+response[i].Year+'</option>');
			}
			$('#Years').select2({
			   //allowClear: true
			});

			// get change function
			$("#Years").change(function(){
				loadPageData();
			})

			loadPageData();
		}); 
	}

	function loadPageData()
	{
		loading_page("#PageTable");
		var url = base_url_js+"budgeting/getListBudgetingRemaining";
		var data = {
				    Year : $("#Years").val() ,
				};
		var token = jwt_encode(data,'UAP)(*');
		$.post(url,{token:token},function (resultJson) {
			var response = jQuery.parseJSON(resultJson);
			var test = '<div class = "row"><div class="col-md-12"><div class="col-md-1 col-md-offset-11" align = "right"><button class = "btn btn-excel-all" Year = "'+data['Year']+'" ><i class="fa fa-download"></i> Excel</button></div></div></div>';
			var TableGenerate = '<div class = "row"style = "margin-top : 10px"><div class="col-md-12" id = "pageForTable">'+
									'<div class="table-responsive">'+
										'<table class="table table-bordered tableData" id ="tableData3">'+
										'<thead>'+
										'<tr>'+
											'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Departement</th>'+
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Budget Remaining</th>'+
											'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Detail</th>'+
											'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Print</th>'+
										'</tr></thead>'	
								;
			TableGenerate += '<tbody>';
			var total = 0;
			for (var i = 0; i < response.length; i++) {
				var st = '';
				Print = '';
				if(response[i].Approval == 1)
				{
					st = '<button class = "btn btn-primary btn-detail" Year = "'+data['Year']+'" Departement = "'+response[i].ID+'"  NameDepartement = "'+response[i].NameDepartement+'" total = "'+response[i].GrandTotal+'"><i class="fa fa-search" aria-hidden="true"></i> Detail</button>';
					Print = '<button class = "btn btn-excel" Year = "'+data['Year']+'" Departement = "'+response[i].ID+'" total = "'+response[i].GrandTotal+'"><i class="fa fa-file-excel-o"></i> Excel</button>';
				}
				else if(response[i].Approval == 0)
				{
					st = '';
				}
				else
				{
					st = '';
				}

				var GrandTotal = parseInt(response[i].GrandTotal) / 1000 // for ribuan
				TableGenerate += '<tr>'+
									'<td width = "3%">'+ (parseInt(i) + 1)+'</td>'+
									'<td>'+ response[i].NameDepartement+'</td>'+
									'<td>'+ formatRupiah(GrandTotal) +'</td>'+
									'<td>'+ st+'</td>'+
									'<td>'+ Print+'</td>'+
								'</tr>';
				total = parseInt(total) + parseInt(response[i].GrandTotal);				
			}

			TableGenerate += '</tbody></table></div></div></div>';
			var SumTotal = '<div class = "row" style = "margin-top : 10px"><div class="col-md-12">'+
								'<div class="col-md-3 col-md-offset-9" style="background-color : #20485A; min-height : 50px;color: #FFFFFF;" align="center"><h4>Total : '+formatRupiah(total)+'</h4>'+
								'</div>'+
							'</div></div>';
			$("#PageTable").html(test+TableGenerate+SumTotal);
			var t = $('#tableData3').DataTable({
				"pageLength": 10
			});
			// console.log(response);

			funcExportExcel();
			funcDetail();
		});
	}

	function funcExportExcel()
	{
		$('#tableData3 tbody').on('click', '.btn-excel', function () {
		// $(".btn-excel").click(function(){
			var Year = $(this).attr('Year');
			var Departement = $(this).attr('Departement');
			var Total = $(this).attr('total');

			var url = base_url_js+'budgeting/export_excel_budget_remaining';
			data = {
			  Year : Year,
			  Departement : Departement,
			  Total : Total
			}
			var token = jwt_encode(data,"UAP)(*");
			FormSubmitAuto(url, 'POST', [
			    { name: 'token', value: token },
			]);
		})
	}


	function funcDetail()
	{
		$('#tableData3 tbody').on('click', '.btn-detail', function () {
			$('html, body').animate({ scrollTop: $('#PageDetail').offset().top }, 'slow');
			loading_page("#PageDetail");
			var Year = $(this).attr('Year');
			var Departement = $(this).attr('Departement');
			var NameDepartement = $(this).attr('NameDepartement');
			var total = $(this).attr('total');

			var url = base_url_js+'budgeting/detail_budgeting_remaining';
			data = {
			  Year : Year,
			  Departement : Departement,
			}
			var token = jwt_encode(data,"UAP)(*");

			$.post(url,{token:token},function (resultJson) {
				var response = jQuery.parseJSON(resultJson);
				var pageGenerate = funcTableGenerate(response);
				var HPage = '<div class = "row">'+
								'<div class = "col-md-12" align = "center">'+
									'<h2>Detail Departement of '+NameDepartement+'</h2>'+
								'</div>'+
							'</div>';
				var SumTotal = '<div class = "row" style = "margin-top : 10px"><div class="col-md-12">'+
									'<div class="col-md-3 col-md-offset-9" style="background-color : #20485A; min-height : 50px;color: #FFFFFF;" align="center"><h4>Total : '+formatRupiah(total)+'</h4>'+
									'</div>'+
								'</div></div>';					
				$("#PageDetail").html(HPage+pageGenerate+SumTotal);
				var t = $('#tableData4').DataTable({
					"pageLength": 10
				});
			});

		})
	}

	function funcTableGenerate(response)
	{
		var generateJson = funcgenerateJson(response);
		var TableGenerate = '<div class = "row">'+
								'<div class = "col-md-12">'+
									'<div class = "table-responsive">'+
										'<table class="table table-bordered tableData" id ="tableData4">'+
											'<thead>'+
												'<tr>'+
													'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
						                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Post Budget</th>'
							;
		var Thbulan = generateJson['Bulan'];
		console.log(Thbulan);
		for (var i = 0; i < Thbulan.length; i++) {
				TableGenerate += '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">'+Thbulan[i]['MonthName']+'</th>';
		}

		TableGenerate += '</tr></thead>';
		TableGenerate += '<tbody>';
		var getData = generateJson['data'];
		console.log(getData);
		for (var i = 0; i < getData.length; i++) {
			TableGenerate += '<tr>'+
								'<td width = "3%">'+ (parseInt(i) + 1)+'</td>'+
								'<td>'+ getData[i].NamePostBudget+'</td>'
						;
			var Detail = getData[i].Detail;
			for (var j = 0; j < Detail.length; j++) {
				var valueee = parseInt(Detail[j].Value) / 1000; // for ribuan
				var valueee = formatRupiah(valueee);
				var AhrefRealization = '<a href="javascript:void(0);" class = "btn-realization" CodePostBudget = "'+getData[i].CodePostBudget+'" YearsMonth = "'+Detail[j].YearsMonth+'" value = "'+Detail[j].Value+'">'+valueee+'</a>'
				TableGenerate += '<td>'+AhrefRealization+'</td>';
			}

			TableGenerate += '</tr>';					
		}

		TableGenerate += '</tbody></table></div></div></div>';
		return TableGenerate;

	}

	function funcgenerateJson(response)
	{
		var data = response['data'];
		var HBulan = response['arr_bulan'];
		var GroupByCodePostBudget = funcGroupByCodePostBudget(data);
		var temp = {
			data : GroupByCodePostBudget,
			Bulan : HBulan,
		}
		return temp;
	}

	function funcGroupByCodePostBudget(data)
	{
		var arr = [];
		for (var i = 0; i < data.length; i++) {
			var CodePostBudget1 = data[i]['CodePostBudget'];
			var YearsMonth = data[i]['YearsMonth'];
			YearsMonth = YearsMonth.split("-");
			YearsMonth = YearsMonth[0]+'-'+YearsMonth[1];
			var arrTemp = [];
			var arrTemp2 = [];
			var temp = {
				YearsMonth : YearsMonth,
				Value : data[i]['Value']
			}
			arrTemp2.push(temp);
				for (var j = i+1; j < data.length; j++) {
					var CodePostBudget2 = data[j]['CodePostBudget'];
					if (CodePostBudget1 == CodePostBudget2) {
						var YearsMonth = data[j]['YearsMonth'];
						YearsMonth = YearsMonth.split("-");
						YearsMonth = YearsMonth[0]+'-'+YearsMonth[1];
						var temp = {
										YearsMonth : YearsMonth,
										Value : data[j]['Value']
									}
						arrTemp2.push(temp);
					} else {
						break;
					}
				}

			var JoinArr = {
				CodePostBudget : CodePostBudget1,
				NamePostBudget : data[i]['PostName']+'-'+data[i]['RealisasiPostName'],
				Detail : arrTemp2
			}

			arr.push(JoinArr);
			i = j-1;
		}

		return arr;

	}
</script>