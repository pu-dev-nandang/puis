<style type="text/css">
	#tableDepartement tbody {
	    display:block;
	    height:600px;
	    overflow:auto;
	}
	#tableDepartement thead,#tableDepartement tfoot,#tableDepartement tbody tr {
	    display:table;
	    width:100%;
	    table-layout:fixed; /* even columns width , fix width of table too*/
	}
	#tableDepartement thead,#tableDepartement tfoot {
	    /*width: calc( 100% - 1em ) scrollbar is average 1em/16px width, remove it from thead width */
	     width: calc( 100% - 1.3em )
	}
	#tableDepartement table {
	    width:400px;
	}

	.tableDepartement thead th,.tableDepartement tfoot td {

	    text-align: center;
	    background: #20485A;
	    color: #FFFFFF;

	}
</style>
<div class="row">
		<h2 align="center">Period <?php echo $GetPeriod['StartMonth'] ?> -  <?php echo $GetPeriod['EndMonth'] ?></h2>
</div>
<div class="row">
	<div class="col-md-2">
		<p style="color: red;font-size: 20px">(.000)</p>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		 <div class="table-responsive" id = 'pagetableDepartement'>
		 	
		 </div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12" id = "AllTotal">
		 
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
	    load_table_rekap_finance();
	});

	function load_table_rekap_finance()
	{

		loading_page("#pagetableDepartement");
		var url = base_url_js+"rest/__budgeting_dashboard";
		var data = {
				    auth : 's3Cr3T-G4N' ,
				};
		var token = jwt_encode(data,'UAP)(*');
		$.post(url,{token:token},function (resultJson) {
			var month = resultJson.month;
			var data = resultJson.data;
			var StartMonth = resultJson.StartMonth;
			var EndMonth = resultJson.EndMonth;
			// make header
			var tblHeader = ['Departement'];
			for (var i = 0; i < month.length; i++) {
				var StartIndeks = parseInt(StartMonth) - 1;
				tblHeader.push(month[StartIndeks]);
				StartMonth++;
				if ((parseInt(StartMonth) - 1) >= month.length) {StartMonth = 1};
			}

			var tbl = '<table class="table table-bordered tableDepartement" id ="tableDepartement"><thead><tr>';
			for (var i = 0; i < tblHeader.length; i++) {
				tbl += (i == 0) ? '<th style="width: 8%;">'+tblHeader[i]+'</th>':'<th>'+tblHeader[i]+'</th>';
			}

			tbl += '</tr></thead>';
			tbl += '<tbody>';

			var total = [];
			var totalAll = 0;
			for (var i = 0; i < data.length; i++) {
				tbl += '<tr>';
				for (var j = 0; j < tblHeader.length; j++) {
					var datamonth = data[i]['data'];
					// find value month
					if (j == 0) {
						tbl += '<td style="width: 8%;">'+data[i]['DepartementName']+'</td>';
					}
					else
					{
						var monthtblheader = tblHeader[j];
						// get indexs
						var monthtblheader = month.indexOf(monthtblheader)+1;
						var valueCost = 0;
						for (var k = 0; k < datamonth.length; k++) {
							var m1 = datamonth[k].month;
							if (monthtblheader == m1) {
								valueCost = datamonth[k].value;
								valueCost = valueCost / 1000; // (.000)
								break;
							}
						}

						tbl += '<td style="width: 8%;">'+formatRupiah(valueCost)+'</td>';
						// count total
						var bool = total.hasOwnProperty(tblHeader[j]); // check array key object
						if (bool) {
							total[tblHeader[j]] = parseInt(total[tblHeader[j]]) + parseInt(valueCost);
						}
						else
						{	
							total[tblHeader[j]] = valueCost;
						}

						totalAll = parseInt(totalAll) + parseInt(valueCost);
					}
					
				}

				tbl += '</tr>';
			}

			tbl += '</tbody>';
			tbl += '<tfoot><tr>';
			tbl += '<td style="width: 8%;">'+'Grand Total'+'</td>';
			for(var key in total) {
			  tbl += '<td>'+formatRupiah(total[key])+'</td>';
			}
			tbl += '</tfoot></table>';
			$("#pagetableDepartement").html(tbl);

			$("#AllTotal").html(
				'<div class = "col-md-3 col-md-offset-9" style = "background-color : #20485A; min-height : 50px;color: #FFFFFF;" align = "center"><h4>Total : '+formatRupiah((totalAll * 1000))+'</h4></div>'
			);


		});

	}
</script>