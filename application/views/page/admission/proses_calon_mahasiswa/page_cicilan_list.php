<div class="col-md-12">
	<div id = "divTableData"></div>
</div>
<script type="text/javascript">
	window.max_cicilan = <?php echo $max_cicilan ?>;
	window.getDataCalonMhs = <?php echo $getDataCalonMhs ?>;
	$(document).ready(function () {
		console.log(max_cicilan);
		console.log(getDataCalonMhs);
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
	    	        '<th style="width: 5px;">No</th>'+
	    	        '<th style="width: 55px;">Nama</th>'+
	    	        '<th style="width: 55px;">Prodi</th>'
	    	for (var i = 0; i < max_cicilan[0]['max_cicilan']; i++) {
	    		var a = parseInt(i) + 1
	    	    table += '<th style="width: 75px;">'+'Cicilan '+a+'</th>' ;
	    	    // table += '<th style="width: 150;">Deadline</th>';	
	    	}

	    	table += '<th style="width: 70px;">Action</th>';	
	    	table += '</tr>' ;	
	    	table += '</thead>' ;	
	    	table += '<tbody>' ;	
	    	table += '</tbody>' ;	
	    	table += '</table>' ;	
	    	$("#divTableData").html(div+table+enddiv);
	    	callback();
	    }
	    
	}

	function loadDataTable()
	{
		var no = 1;
		var a = '';
		for (var i = 0; i < getDataCalonMhs.length; i++) {
			var lunas = 0;
			for (var j = 0; j < max_cicilan[0]['max_cicilan']; j++) {
				var b = parseInt(j) + 1;
				var c = (getDataCalonMhs[i]['Cicilan_'+b]['Status'] == 0) ? 'Belum Bayar' :'Sudah Bayar';
				if (getDataCalonMhs[i]['Cicilan_'+b]['Status'] == '') {
					c = '-';
				}
				var d = (getDataCalonMhs[i]['Cicilan_'+b]['BilingID'] != "" && getDataCalonMhs[i]['Cicilan_'+b]['Status'] == 0) ? '<br>Deadline : <input class = "deadline" id = "deadline_'+no+'" value = "'+getDataCalonMhs[i]['Cicilan_'+b]['Deadline']+'" cicilan = "'+b+'">' :'<br> Deadline : '+getDataCalonMhs[i]['Cicilan_'+b]['Deadline'];
				a += '<td> Invoice : Rp '+getDataCalonMhs[i]['Cicilan_'+b]['Invoice']+'<br>BilingID : '+getDataCalonMhs[i]['Cicilan_'+b]['BilingID']+'<br>Status : '+c+d+'</td>';

				if(getDataCalonMhs[i]['Cicilan_'+b]['Status'] == 1 || getDataCalonMhs[i]['Cicilan_'+b]['Status'] == '')
				{
					lunas++;
				}
				// lunas = (getDataCalonMhs[i]['Cicilan_'+b]['Status'] == 1) ? lunas=lunas + 1 : 0;
				// console.log(lunas);
			}
			
			var btn_sbmt = (lunas != max_cicilan[0]['max_cicilan']) ? '<td><button class="btn btn-inverse btn-notification btn-Save" id="btn-Save_'+no+'" ID_register_formulir = "'+getDataCalonMhs[i]['ID_register_formulir']+'">Edit Deadline</button>'+'</td>' : '<td></td>';
			$(".tableData tbody").append(
					'<tr>'+
						'<td align= "center">'+no+'</td>'+
						'<td>'+getDataCalonMhs[i]['Name']+'</td>'+
						'<td>'+getDataCalonMhs[i]['NamePrody']+'</td>'+
						a+
						btn_sbmt+
					'</tr>' 	
			);
			a = '';
			no++;
		}

		$('.deadline').datetimepicker({
			// startDate: today,
			startDate: '+1d',
		});
		pageHtml = 'cicilan';
	}
</script>