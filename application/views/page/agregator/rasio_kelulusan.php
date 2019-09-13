<div class="well">
	<div class="row">
		<div style="text-align: right;">
		    <button onclick="saveTable2Excel('dataTable2Excel1')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
		</div>
		<div id = "viewTable1">
			
		</div>	
	</div>
	<!-- <div class="row" style="margin-top: 5px;">
		<div style="text-align: right;">
		    <button onclick="saveTable2Excel('dataTable2Excel2')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
		</div>
		<div id = "viewTable2">
			
		</div>	
	</div>
	<div class="row" style="margin-top: 5px;">
		<div style="text-align: right;">
		    <button onclick="saveTable2Excel('dataTable2Excel3')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
		</div>
		<div id = "viewTable3">
			
		</div>	
	</div> -->
</div>
<script type="text/javascript">
	$(document).ready(function () {
	    loading_page('#viewTable1');
	    // loading_page('#viewTable2');
	    // loading_page('#viewTable3');
	    LoadDataPage();
	});

	function LoadDataPage()
	{
		MakeTable1();
		// MakeTable2();
	}

	function MakeTable1()
	{
		var htmlTable = '<table class = "table dataTable2Excel1" id="MakeTable1" data-name="TblRasioKelulusanTepatWaktuDanRasioKeberhasilanStudi">'+
							'<thead>'+
								'<tr>'+
									'<th></th>'+
									'<th colspan = "5" style="text-align:center">Jumlah Mahasiswa per Angkatan pada Tahun</th>'+
									'<th style="width:15%" rowspan = "2">Jumlah Lulusan s.d. akhir TS </th>'+
								'</tr>'+
								'<tr>';	

		var data = {
		    action : 'viewRasioKelulusanTepatWaktuDanRasioKeberhasilanStudi',
		};

		var token = jwt_encode(data,'UAP)(*');
		var url = base_url_js+'api3/__crudAgregatorTB5';

		$.post(url,{token:token},function (jsonResult) {
			var header = jsonResult.header;
			for (var i = 0; i < header.length; i++) {
				htmlTable += '<th style = "text-align: center">'+header[i]+'</th>';
			}

			htmlTable+= '</tr></thead><tbody id = "listData1"></tbody>';
            htmlTable+= '</table>';
            $('#viewTable1').html(htmlTable);
            var htmlTableBody = '';
            var body = jsonResult.body;
            for (var i = 0; i < body.length; i++) {
            	htmlTableBody += '<tr>';
            	var arr_body = body[i];
            	for (var j = 0; j < arr_body.length; j++) {
            		htmlTableBody += '<td style = "text-align: center">'+arr_body[j]+'</td>';
            	}

            	htmlTableBody += '</tr>';
            }

            $('#listData1').append(htmlTableBody);
		});						

	}


	function MakeTable1()
	{
		var htmlTable ='<div align = "center"><strong>Rasio kelulusan tepat waktu dan rasio keberhasilan studi pada program Sarjana/Diploma Empat/Sarjana Terapan</strong></div><br/>';
		htmlTable += '<table class = "table dataTable2Excel1" id="MakeTable1" data-name="TblRasioKelulusanTepatWaktuDanRasioKeberhasilanStudi">'+
							'<thead>'+
								'<tr>'+
									'<th rowspan="2" style="border-right: 1px solid #ccc;text-align:center;width:25%">Tahun Masuk</th>'+
									'<th colspan = "5" style="border-right: 1px solid #ccc;text-align:center">Jumlah Mahasiswa per Angkatan pada Tahun</th>'+
									'<th style="width:15%" rowspan = "2">Jumlah Lulusan s.d. akhir TS </th>'+
								'</tr>'+
								'<tr>';	

		var data = {
		    action : 'viewRasioKelulusanTepatWaktuDanRasioKeberhasilanStudi',
		};

		var token = jwt_encode(data,'UAP)(*');
		var url = base_url_js+'api3/__crudAgregatorTB5';

		$.post(url,{token:token},function (jsonResult) {
			var header = jsonResult.header;
			for (var i = 1; i < header.length; i++) {
				htmlTable += '<th style = "text-align: center">'+header[i]+'</th>';
			}

			htmlTable+= '</tr></thead><tbody id = "listData1"></tbody>';
            htmlTable+= '</table>';
            $('#viewTable1').html(htmlTable);
            var htmlTableBody = '';
            var body = jsonResult.body;
            for (var i = 0; i < body.length; i++) {
            	htmlTableBody += '<tr>';
            	var arr_body = body[i];
            	for (var j = 0; j < arr_body.length; j++) {
            		htmlTableBody += '<td style = "text-align: center">'+arr_body[j]+'</td>';
            	}

            	htmlTableBody += '</tr>';
            }

            $('#listData1').append(htmlTableBody);

            $('#viewTable1').append('<p style="color: orangered;">'+
                '<br>*) Mahasiswa yang terhitung adalah mahasiswa yang aktif (Tidak termasuk mahasiswa cuti / mangkir)'+
                ''+
            '</p>')
		});						

	}
</script>