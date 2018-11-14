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

	.tableDepartement > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
	   background-color: #f5f5f5;
	}
</style>
<div class="row">
		<h2 align="center">Period Sep 2018 - Aug 2019</h2>
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
		var data = [];
		var month = [
		         'Jan',
		         'Feb',
		         'Mar',
		         'April',
		         'Mei',
		         'Jun',
		         'Jul',
		         'Aug',
		         'Sep',
		         'Okt',
		         'Nov',
		         'Des'
		];

		var departement = [
			'IT',
			'Finance',
			'Yayasan',
			'Rectorate',
			'SPMI',
			'LPM',
			'LPPM',
			'Academic Service',
			'Institutional Reation',
			'General Affair',
			'Marketing & Admission',
			'Library',
			'Human Resource',
			'Prodi Architecture',
			'Prodi Construction Engineering and Management',
			'Prodi Entrepreneurship',
			'Prodi Accounting',
			'Prodi Hotel Business Program',
			'Prodi Business Law',
			'Prodi Structural Engineering',
			'Prodi Urban Regional Planning',
			'Prodi Environmental Engineering',
			'Prodi Product Design',
		];

		var Start = 9;
		var tblHeader = ['Department'];
		for (var i = 0; i < month.length; i++) {
			var StartIndeks = parseInt(Start) - 1;
			tblHeader.push(month[StartIndeks]);
			Start++;
			if ((parseInt(Start) - 1) >= month.length) {Start = 1};
		}

		var tbl = '<table class="table table-bordered tableDepartement" id ="tableDepartement"><thead><tr>';
		for (var i = 0; i < tblHeader.length; i++) {
			tbl += (i == 0) ? '<th style="width: 8%;">'+tblHeader[i]+'</th>':'<th>'+tblHeader[i]+'</th>';
		}

		tbl += '</tr></thead>';

		var Rp = 25000000;
		var RpArr = [];
		RpArr.push('');
		for (var i = 0; i < 12; i++) {
			var aa = parseInt(i) * 500000;
			Rp = parseInt(25000000) + parseInt(aa);
			RpArr.push(Rp);
		}

		for (var i = 0; i < 23; i++) {
			var aa = parseInt(i) * 500000;
			var temp = {};
			for (var j = 0; j < RpArr.length; j++) {
				if (j == 0) {
					temp[tblHeader[j]] = departement[i];
				}
				else
				{
					temp[tblHeader[j]] = parseInt(RpArr[j]) + parseInt(aa);
				}
				
			}
			data.push(temp);
		}

		tbl += '<tbody>';

		console.log(data);
		var total = [];
		var totalAll = 0;
		for (var i = 0; i < data.length; i++) {
			tbl += '<tr>';
			for (var j = 0; j < tblHeader.length; j++) {
				var valuee = parseInt(data[i][tblHeader[j]] / 1000);
				tbl += (j == 0) ? '<td style="width: 8%;">'+data[i][tblHeader[j]]+'</td>' : '<td>'+formatRupiah(valuee)+'</td>';
				if (j != 0) {
					var bool = total.hasOwnProperty(tblHeader[j]); // check array key object
					if (bool) {
						total[tblHeader[j]] = parseInt(total[tblHeader[j]]) + parseInt(valuee);
					}
					else
					{	
						total[tblHeader[j]] = valuee;
					}

					totalAll = parseInt(totalAll) + parseInt(valuee);
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
			'<div class = "col-md-3 col-md-offset-9" style = "background-color : #20485A; min-height : 50px;color: #FFFFFF;" align = "center"><h4>Total : '+formatRupiah(totalAll)+'</h4></div>'
		);
	}
</script>