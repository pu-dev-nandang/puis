<div class="col-md-12">
	<div id = "divTableData"></div>
</div>
<script type="text/javascript">
	window.getDataCalonMhs = <?php echo $getDataCalonMhs ?>;
	$(document).ready(function () {
		// console.log(getDataCalonMhs);
		// loadtable_header(loadDataTable);
		// is not null + 0 = lunas, is not null + 1 = Belum lunas, is null = '' , cicilan 0 = null
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
	    	        '<th style="width: 55px;">Sekolah</th>'+
	    	        '<th style="width: 55px;">Email</th>'+
	    	        '<th style="width: 55px;">VA</th>'+
	    	        '<th style="width: 55px;">Status Formulir</th>'+
	    	        '<th style="width: 55px;">Formulir Code</th>'+
	    	        '<th style="width: 55px;">Prodi</th>'+
	    	        '<th style="width: 55px;">File</th>'+
	    	        '<th style="width: 55px;">Rangking</th>'+
	    	        '<th style="width: 55px;">Biaya Kuliah</th>'+
	    	        '<th style="width: 55px;">Pembayaran</th>';
	    		
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
		for (var i = 0; i < getDataCalonMhs.length; i++) {
			var StatusFormulir = (getDataCalonMhs[i]['FormulirCode'] == null) ? '<td>Belum Bayar</td>' : '<td>Sudah Bayar</td>';
			var FormulirCode = (getDataCalonMhs[i]['FormulirCode'] == null) ? '<td>-</td>' : '<td>'+getDataCalonMhs[i]['FormulirCode']+'</td>';
			var NMPrody = (getDataCalonMhs[i]['NameEng'] == null) ? '<td>-</td>' : '<td>'+getDataCalonMhs[i]['NameEng']+'</td>';
			var File = (getDataCalonMhs[i]['FormulirCode'] == null) ? '<td>-</td>' : '<td><button class="btn btn-inverse btn-notification btn-show" id-register-formulir = '+getDataCalonMhs[i]['ID_register_formulir']+' email = "'+getDataCalonMhs[i]['Email']+'" Nama = "'+getDataCalonMhs[i]['Name']+'">Show</button></td>';
			var Rangking = '<td>-</td>';
			var Bkuliah = '<td>-</td>';
			var Cicilan = '<td>-</td>';
			if(getDataCalonMhs[i]['FormulirCode'] != null)
			{
				if(getDataCalonMhs[i]['Rangking'] == 0)
				{
					Rangking = '<td>No Rangking</td>';
				}
				else
				{
					Rangking = '<td>'+getDataCalonMhs[i]['Rangking']+'</td>';
				}

				if (getDataCalonMhs[i]['chklunas'] > 0) {
					if(getDataCalonMhs[i]['NotLunas'] == 0)
					{
						Bkuliah = '<td>Sudah Lunas &nbsp <button class = "Detail" id-register-formulir = "'+getDataCalonMhs[i]['ID_register_formulir']+'" Nama = "'+getDataCalonMhs[i]['Name']+'">Detail</button></td>';
					}
					else
					{
						Bkuliah = '<td>Belum Lunas &nbsp <button class = "Detail" id-register-formulir = "'+getDataCalonMhs[i]['ID_register_formulir']+'" Nama = "'+getDataCalonMhs[i]['Name']+'">Detail</button></td>';
					}
				}
				
				if(getDataCalonMhs[i]['Cicilan'] > 0)
				{
					Cicilan = '<td>'+getDataCalonMhs[i]['Cicilan']+'x Cicilan</td>';
				}
			}
			$(".tableData tbody").append(
					'<tr>'+
						'<td align= "center">'+no+'</td>'+
						'<td>'+getDataCalonMhs[i]['Name']+'</td>'+
						'<td>'+getDataCalonMhs[i]['SchoolName']+'</td>'+
						'<td>'+getDataCalonMhs[i]['Email']+'</td>'+
						'<td>'+getDataCalonMhs[i]['VA_number']+'</td>'+
						StatusFormulir+
						FormulirCode+
						NMPrody+
						File+
						Rangking+
						Bkuliah+
						Cicilan+
					'</tr>' 	
			);

			no++;
		}
	}
</script>