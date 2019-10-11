<div class="well">
	<div class="row">
		<div class="col-md-3 col-md-offset-4">
			<div class="form-group">
				<label>Choose TA</label>
				<select class="form-control" id = "SelectTA"></select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">

		</div>
	</div>
	<div id="content_data">

	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
	    loading_page('#viewTable1');
	    LoadDataPage();
	});

	function LoadDataPage()
	{
		var selector = $('#SelectTA');
		load_select_ta_mhs(selector).then(function(response){
			MakeTable1();
		})
	}

	function MakeTable1()
	{
		var SelectTA = $('#SelectTA option:selected').val();
		var data = {
		    action : 'viewRasioKelulusanTepatWaktuDanRasioKeberhasilanStudi',
		    TA : SelectTA,
		};

		var token = jwt_encode(data,'UAP)(*');
		var url = base_url_js+'api3/__crudAgregatorTB5';

		$.post(url,{token:token},function (jsonResult) {
			MakeContentData(jsonResult);
			// MakeContentData2(jsonResult);
		});

	}

	function MakeContentData(jsonResult)
	{
		var html = '<div class = "row" '+StyleRow+'>'+
							'<div class = "col-md-12">'+
								'<div style="text-align: right;">'+
								    '<button onclick="saveTable2Excel(\'dataTable2Excel\')" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>'+
								'</div>'+
								'<p style="color: green;" class = "PercentRatio"></p>'+
								'<br/>'+
								'<table class = "table table-bordered dataTable2Excel" data-name="TblLamaRasioKelulusan">';
			var SumRata = 0;
			var CountStdB0 = 0;
			for (var i = 0; i < jsonResult.length; i++) {
				var MasaStudi = jsonResult[i].MasaStudi;
				var ProdiName = jsonResult[i]['ProdiName'];
				var TheadBuild = '';
				var header = jsonResult[i]['header'];
				for (var j = 0; j < header.length; j++) {
					TheadBuild += '<td rowspan = "'+header[j].Rowspan+'" colspan = "'+header[j].Colspan+'">'+header[j].Name+'</td>';
				}

				// loop sub
				var TheadBuild_sub = '';
				for (var j = 0; j < header.length; j++) {
					var Sub = header[j].Sub;
					if (Sub.length) {
						for (var k = 0; k < Sub.length; k++) {
							var ss = (MasaStudi == k) ? 'style = "background-color: lightyellow;"' : '';
							TheadBuild_sub += '<td '+ss+'>'+Sub[k]+'</td>';
						}
					}
				}

				var data = jsonResult[i]['data'];
				var tbodyFill = '';
				for (var j = 0; j < data.length; j++) {
					tbodyFill += '<tr>';
					var dt = data[j];
					var wrPersent = (j>1) ? '%' : '';
					for (var k = 0; k < dt.length; k++) {
						var ss = (MasaStudi == (k-1) ) ? 'style = "background-color: lightyellow;"' : '';
						if (k==0) {
							tbodyFill += '<td>'+dt[k]+'</td>';
						}
						else
						{
							// get total std per prodi untuk rata-rata rasio per tahun
							if (k == 1 && j == 0) {
								if (dt[k] > 0) {
									CountStdB0++;
								}
							}

							if ( (dt.length) - 1 == k && j == 1) {
								tbodyFill += '<td>'+getCustomtoFixed(dt[k],0)+'</td>';
							}
							else if( (dt.length) - 1 == k && j != 1) {
								tbodyFill += '<td>'+'-'+'</td>';
							}
							else
							{
								tbodyFill += '<td '+ss+'>'+getCustomtoFixed(dt[k],0)+wrPersent+'</td>';

							}

							if (MasaStudi == (k-1) && j == 2 ) { // for sum untuk year
								// console.log(getCustomtoFixed(dt[k],0));
								SumRata += parseInt(getCustomtoFixed(dt[k],0));
							}

						}

					}

					tbodyFill += '</tr>';
				}


				var StyleRow = (i == 0) ? '' : ' style = "margin-top:10px;" '
				html += 	'<tr>'+
								'<td colspan = "10" align = "center"><h2 style = "margin-top: 0px;border-left: 7px solid #2196F3;padding-left: 10px;    font-weight: bold;">'+ProdiName+'</h2></td>'+
							'</tr>'+
							'<tr>'+
								TheadBuild+
							'</tr>'+
							'<tr>'+
								TheadBuild_sub+
							'</tr>'+
							tbodyFill
			}

			html += 	'</table>'+
					'</div>'+
				'</div>';

		$('#content_data').html(html);
		var rt = 0;
		if (CountStdB0 > 0) {
			rt = SumRata / CountStdB0;
		}

		rt =  getCustomtoFixed(rt,1);
		var Ta = $('#SelectTA option:selected').val();
		// $('.PercentRatio').html('*) Pesentase Ratio '+Ta+' : '+rt+'%');
		$('.PercentRatio').html('<table class = "table">'+
									'<tr>'+
										'<th>'+'*) Pesentase Rasio '+Ta+' : '+rt+'%'+'</th>'+
									'</tr>'+
								'</table>'
			);

	}

	// function MakeContentData2(jsonResult)
	// {
	// 	var html = '';
	// 		for (var i = 0; i < jsonResult.length; i++) {
	// 			var ProdiName = jsonResult[i]['ProdiName'];
	// 			var TheadBuild = '';
	// 			var header = jsonResult[i]['header'];
	// 			for (var j = 0; j < header.length; j++) {
	// 				TheadBuild += '<th rowspan = "'+header[j].Rowspan+'" colspan = "'+header[j].Colspan+'">'+header[j].Name+'</th>';
	// 			}

	// 			// loop sub
	// 			var TheadBuild_sub = '';
	// 			for (var j = 0; j < header.length; j++) {
	// 				var Sub = header[j].Sub;
	// 				if (Sub.length) {
	// 					for (var k = 0; k < Sub.length; k++) {
	// 						TheadBuild_sub += '<th>'+Sub[k]+'</th>';
	// 					}
	// 				}
	// 			}

	// 			var data = jsonResult[i]['data'];
	// 			var tbodyFill = '';
	// 			for (var j = 0; j < data.length; j++) {
	// 				tbodyFill += '<tr>';
	// 				var dt = data[j];
	// 				for (var k = 0; k < dt.length; k++) {
	// 					tbodyFill += '<td>'+dt[k]+'</td>';
	// 				}

	// 				tbodyFill += '</tr>';
	// 			}


	// 			var StyleRow = (i == 0) ? '' : ' style = "margin-top:10px;" '
	// 			html += '<div class = "row" '+StyleRow+'>'+
	// 						'<div class = "col-md-12">'+
	// 							'<table class = "table table-bordered">'+
	// 								'<thead>'+
	// 									'<tr>'+
	// 										'<th colspan = "10" align = "center"><h2 style = "margin-top: 0px;border-left: 7px solid #2196F3;padding-left: 10px;    font-weight: bold;">'+ProdiName+'</h2></th>'+
	// 									'</tr>'+
	// 									'<tr>'+
	// 										TheadBuild+
	// 									'</tr>'+
	// 									'<tr>'+
	// 										TheadBuild_sub+
	// 									'</tr>'+
	// 								'</thead>'+
	// 								'<tbody>'+
	// 									tbodyFill+
	// 								'</tbody>'+
	// 							'</table>'+
	// 						'</div>'+
	// 					'</div>';
	// 		}

	// 	$('#content_data').html(html);
	// }


	$(document).off('click', '#SelectTA').on('click', '#SelectTA',function(e) {
		MakeTable1();
	})

	$(document).off('click', '.datadetail').on('click', '.datadetail',function(e) {
	    var v = parseInt($(this).html());
	    if (v > 0) {
	        var dt = $(this).attr('data');
	        // console.log(dt);
	        dt = jwt_decode(dt);
	        var html =  '<div class = "row">'+
	                        '<div class = "col-md-12">'+
	                            '<table class = "table">'+
	                                '<thead>'+
	                                    '<tr>'+
	                                        '<td>No</td>'+
	                                        '<td>NPM</td>'+
	                                        '<td>NAMA</td>'+
	                                    '</tr>'+
	                                '</thead>'+
	                                '<tbody>';
	                for (var i = 0; i < dt.length; i++) {
	                    html += '<tr>'+
	                                '<td>'+ (parseInt(i)+1) + '</td>'+
	                                '<td>'+ dt[i].NPM + '</td>'+
	                                '<td>'+ dt[i].Name + '</td>'+
	                            '</tr>';
	                }

	                html  += '</tbody></table></div></div>';


	        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
	            '<h4 class="modal-title">Detail</h4>');
	        $('#GlobalModal .modal-body').html(html);
	        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
	        $('#GlobalModal').modal({
	            'show' : true,
	            'backdrop' : 'static'
	        });
	    }
	})
</script>
