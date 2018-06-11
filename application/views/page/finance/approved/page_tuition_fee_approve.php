<div class="col-md-12">
	<div id = "divTableData"></div>
</div>
<script type="text/javascript">
	window.payment_type = <?php echo $payment_type ?>;
	window.getDataCalonMhs = <?php echo $getDataCalonMhs ?>;
	$(document).ready(function () {
		// console.log(payment_type);
		// console.log(getDataCalonMhs);
		loadtable_header(loadDataTable);
	});

	function loadtable_header(callback)
	{
	    var div = '';
	    var enddiv = '</div>';
	    var table = '';
	    $("#divTableData").empty();
	    if(getDataCalonMhs.length == 0)
	    {
	    	div = '<div id = "tblData" class="table-responsive" align = "center">No Result Data...';
	    	$("#divTableData").html(div+table+enddiv);
	    }
	    else
	    {
	    	div = '<div id = "tblData" class="table-responsive">';
	    	table = '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
	    	'<thead>'+
	    	    '<tr>'+
	    	        '<th style="width: 5px;">No  <input type="checkbox" class="uniform" value="nothing" id ="dataResultCheckAll"></th>'+
	    	        '<th style="width: 55px;">Nama</th>'+
	    	        '<th style="width: 55px;">Prodi</th>'+
	    	        '<th style="width: 55px;">Sekolah</th>'
	    	for (var i = 0; i < payment_type.length; i++) {
	    	    table += '<th style="width: 75px;">'+payment_type[i].Abbreviation+'</th>' ;
	    	    table += '<th style="width: 70px;">Discount</th>';	
	    	}
	    		
	    	table += '</tr>' ;	
	    	table += '</thead>' ;	
	    	table += '<tbody>' ;	
	    	table += '</tbody>' ;	
	    	table += '</table>' ;	
	    	$("#divTableData").html(div+table+enddiv);
	    	$("#divTableData").append(
	    		'<div class="col-xs-12" align = "right">'+
	   				'<button class="btn btn-inverse btn-notification btn-Save" id="btn-Save">Submit</button>'+
				'</div>'
	    		);
	    	callback();
	    }
	    
	}

	function loadDataTable()
	{
		var no = 1;
		for (var i = 0; i < getDataCalonMhs.length; i++) {
			var DiskonSPP = getDataCalonMhs[i]['Discount-SPP'];
			var isi_payment = '';
			for (var j = 0; j < payment_type.length; j++) {
				var value_cost = 0;
					for(var key in getDataCalonMhs[i]) {
						// var keyDiscount = 
						if (key == payment_type[j]['Abbreviation']) {
							value_cost = getDataCalonMhs[i][key];
							DiskonSPP = getDataCalonMhs[i]['Discount-'+key];
						}
					}
				isi_payment += '<td>'+value_cost+'</td>';
				isi_payment += '<td>'+DiskonSPP+'%</td>';
			}

			$(".tableData tbody").append(
					'<tr>'+
						'<td align= "center">'+no+'&nbsp<input type="checkbox" class="uniform" value ="'+getDataCalonMhs[i]['ID_register_formulir']+'"</td>'+
						'<td>'+getDataCalonMhs[i]['Name']+'</td>'+
						'<td>'+getDataCalonMhs[i]['NamePrody']+'</td>'+
						'<td>'+getDataCalonMhs[i]['SchoolName']+'</td>'+
						isi_payment+
					'</tr>' 	
			);

			no++;
		}
		pageHtml = 'tuition_fee';
	}
</script>