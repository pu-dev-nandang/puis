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
					<div class="col-md-2">
						<p style="color: red;font-size: 20px">(.000)</p>
					</div>
					<div class="col-md-8 col-md-offset-1">
						<b>Status Budget: </b><i class="fa fa-circle" style="color:#8ED6EA;"></i> Approve | <i class="fa fa-circle" style="color: #eade8e;"></i> Not Approve | <i class="fa fa-circle" style="color: #da2948;"></i> Not Set
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row" id = "PageTable" style="margin-top: 10px;margin-right: 0px;margin-left: 10px">

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
			    $('#Years').append('<option value="'+response[i].Year+'" '+selected+'>'+response[i].Year+' - '+(parseInt(response[i].Year) + 1)+'</option>');
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
		var url = base_url_js+"budgeting/getListBudgetingDepartement";
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
				                            '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Grand Total Budget</th>'+
											'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Status</th>'+
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
					st = '<i class="fa fa-circle" style="color:#8ED6EA;"></i>';
					Print = '<button class = "btn btn-excel" Year = "'+data['Year']+'" Departement = "'+response[i].ID+'"><i class="fa fa-file-excel-o"></i> Excel</button>';
				}
				else if(response[i].Approval == 0)
				{
					st = '<i class="fa fa-circle" style="color: #eade8e;"></i>';
				}
				else
				{
					st = '<i class="fa fa-circle" style="color: #da2948;"></i>';
				}
				var GrandTotal = parseInt(response[i].GrandTotal) / 1000;
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
		});
	}

	function funcExportExcel()
	{
		$('#tableData3 tbody').on('click', '.btn-excel', function () {
		// $(".btn-excel").click(function(){
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

		$(".btn-excel-all").click(function(){
			var Year = $(this).attr('Year');

			var url = base_url_js+'budgeting/export_excel_budget_creator_all';
			data = {
			  Year : Year,
			}
			var token = jwt_encode(data,"UAP)(*");
			FormSubmitAuto(url, 'POST', [
			    { name: 'token', value: token },
			]);
		})
	}

</script>
