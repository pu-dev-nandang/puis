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
	    	    table += '<th style="width: 70px;">Pot</th>';	
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
			var DiskonSPP = getDataCalonMhs[i]['DiskonSPP'];
			var isi_payment = '';
			for (var j = 0; j < payment_type.length; j++) {
				/*var selecTOption = '<select class="select2-select-00 col-md-4 full-width-fix" id="'+payment_type[j]['Abbreviation']+getDataCalonMhs[i]['ID_register_formulir']+'">'+
							    '<option></option>'+
							'</select>';*/
				var selecTOption = '<select class="selecTOption getDom" id="'+payment_type[j]['Abbreviation']+getDataCalonMhs[i]['ID_register_formulir']+'" id-formulir = "'+getDataCalonMhs[i]['ID_register_formulir']+'" payment-type = "Discount-'+payment_type[j]['Abbreviation']+'" payment-type_ID = "'+payment_type[j]['ID']+'">';
				var value_cost = 0;
					for(var key in getDataCalonMhs[i]) {
						// console.log('key = ' + key + '? ' + 'getDataCalonMhs[i][key] = ' + getDataCalonMhs[i][key]);
						if (key == payment_type[j]['Abbreviation']) {
							value_cost = getDataCalonMhs[i][key];
						}
					}
				$('#'+payment_type[j]['Abbreviation']+getDataCalonMhs[i]['ID_register_formulir']).empty();			
				for (var k = 0; k <= 100; k=k+5) {
					if (payment_type[j]['Abbreviation'] == 'SPP') {
						var selected = (k==DiskonSPP) ? 'selected' : '';
					}
					else
					{
						var selected = (k==0) ? 'selected' : '';
					}

					selecTOption += '<option value="'+k+'" '+selected+'>'+k+'%'+'</option>';
				}
				selecTOption += '</select>';
				
				if (payment_type[j]['Abbreviation'] == 'SPP') {
					value_cost = value_cost - ((DiskonSPP/100)*value_cost);
					value_cost = value_cost.toFixed(2);
					// console.log(value_cost);
				}	
				var cost = '<input class="form-control costInput getDom" id="cost_'+getDataCalonMhs[i]['ID_register_formulir']+'" id-formulir = "'+getDataCalonMhs[i]['ID_register_formulir']+'" value = "'+value_cost+'" payment-type = "'+payment_type[j]['Abbreviation']+'" payment-type_ID = "'+payment_type[j]['ID']+'" readonly>';
				isi_payment += '<td>'+cost+'</td>';
				isi_payment += '<td>'+selecTOption+'</td>';
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

		$('.costInput').maskMoney({thousands:'.', decimal:',', precision:2,allowZero: true});
		$('.costInput').maskMoney('mask', '9894');
		pageHtml = 'tuition_fee';
	}

	$(document).on('change','.selecTOption', function () {
		var id_formulir = $(this).attr('id-formulir');
		var payment_type = $(this).attr('payment-type');
		var valuee = $(this).val();
		var value_cost = 0;
		for (var i = 0; i < getDataCalonMhs.length; i++) {
			if (id_formulir == getDataCalonMhs[i]['ID_register_formulir']) {
				for(var key in getDataCalonMhs[i]) {
					// console.log('key = ' + key + '? ' + 'getDataCalonMhs[i][key] = ' + getDataCalonMhs[i][key]);
					var keyTemp = 'Discount-'+key;
					if (keyTemp == payment_type) {
						value_cost = getDataCalonMhs[i][key];
					}
				}
			}
		}	
		value_cost = value_cost - ((valuee/100)*value_cost);
		value_cost = value_cost.toFixed(2);
		var payment_type_input = payment_type.split("-");
		$('.costInput[payment-type="'+payment_type_input[1]+'"][id-formulir = "'+id_formulir+'"]').val(value_cost);
		$('.costInput').maskMoney({thousands:'.', decimal:',', precision:2,allowZero: true});
		$('.costInput').maskMoney('mask', '9894');
	});
</script>