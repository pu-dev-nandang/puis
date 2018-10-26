<div class="col-md-12">
	<div id = "divTableData"></div>
</div>
<script type="text/javascript">
	window.payment_type = <?php echo $payment_type ?>;
	window.getDataCalonMhs = <?php echo $getDataCalonMhs ?>;
	$(document).ready(function () {
		// console.log(payment_type);
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
	    	        '<th style="width: 5px;">No  <!--<input type="checkbox" class="uniform" value="nothing" id ="dataResultCheckAll">--></th>'+
	    	        '<th style="width: 55px;">Nama,Prodi & Sekolah</th>'+
	    	        '<th style="width: 55px;">Formulir Code</th>'+
	    	        '<th style="width: 10%;">Beasiswa, File & Rangking</th>'
	    	        //'<th style="width: 55px;">Prodi</th>'+
	    	        //'<th style="width: 55px;">Sekolah</th>'
	    	for (var i = 0; i < payment_type.length; i++) {
	    	    table += '<th style="width: 75px;">'+payment_type[i].Abbreviation+'</th>' ;
	    	    //table += '<th style="width: 70px;">Pot</th>';	
	    	}
	    	 table += '<th style="width: 75px;">'+'Keterangan'+'</th>' ;	
	    	table += '</tr>' ;	
	    	table += '</thead>' ;	
	    	table += '<tbody>' ;	
	    	table += '</tbody>' ;	
	    	table += '</table>' ;	
	    	$("#divTableData").html(div+table+enddiv);
	    	/*$("#divTableData").append(
	    		'<div class="col-xs-12" align = "right">'+
	   				'<button class="btn btn-inverse btn-notification btn-Save hide" id="btn-Save">Submit</button>'+
				'</div>'
	    		);*/
	    	callback();
	    }
	    
	}

	function loadDataTable()
	{
		var no = <?php echo $no ?>;
		max_cicilan = getDataCalonMhs[0]['getMaxCicilan'];
		for (var i = 0; i < getDataCalonMhs.length; i++) {
			var DiskonSPP = getDataCalonMhs[i]['DiskonSPP'];
			var isi_payment = '';
			var getDiscount = getDataCalonMhs[i]['getDiscount'];
			var getBeasiswa = getDataCalonMhs[i]['getBeasiswa'];
			var getDocument = getDataCalonMhs[i]['Document'];
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
					/*for (var k = 0; k <= 100; k=k+5) {
						if (payment_type[j]['Abbreviation'] == 'SPP') {
							var selected = (k==DiskonSPP) ? 'selected' : '';
						}
						else
						{
							var selected = (k==0) ? 'selected' : '';
						}

						selecTOption += '<option value="'+k+'" '+selected+'>'+k+'%'+'</option>';
					}*/
					for (var k = 0; k < getDiscount.length; k++) {
						if (payment_type[j]['Abbreviation'] == 'SPP') {
							var selected = (getDiscount[k]['Discount']==DiskonSPP) ? 'selected' : '';
						}
						else
						{
							var selected = (getDiscount[k]['Discount']==0) ? 'selected' : '';
						}

						selecTOption += '<option value="'+getDiscount[k]['Discount']+'" '+selected+'>'+getDiscount[k]['Discount']+'%'+'</option>';
					}	
					selecTOption += '</select>';
					
					if (payment_type[j]['Abbreviation'] == 'SPP') {
						value_cost = value_cost - ((DiskonSPP/100)*value_cost);
						value_cost = value_cost.toFixed(2);
						// console.log(value_cost);
					}	
					var cost = '<input class="form-control costInput getDom" id="cost_'+getDataCalonMhs[i]['ID_register_formulir']+'" id-formulir = "'+getDataCalonMhs[i]['ID_register_formulir']+'" value = "'+value_cost+'" payment-type = "'+payment_type[j]['Abbreviation']+'" payment-type_ID = "'+payment_type[j]['ID']+'" readonly>';
					isi_payment += '<td>'+selecTOption+'<br><br>'+cost+'</td>';
					//isi_payment += '<td>'+selecTOption+'</td>';
				}

			var selecTOption = '<select class="getBeasiswa" id-formulir = "'+getDataCalonMhs[i]['ID_register_formulir']+'" id = "getBeasiswa'+getDataCalonMhs[i]['ID_register_formulir']+'" >';
			for (var k = 0; k < getBeasiswa.length; k++) {
				if (getDataCalonMhs[i]['RangkingRapor'] != 0) {
					var selected = (getBeasiswa[k]['ID']==4) ? 'selected' : '';
				}
				else
				{
					//var selected = (k==0) ? 'selected' : '';
					if (k == 0) {
						selecTOption += '<option value="'+'0'+'" '+selected+'>'+'Tidak Beasiswa'+''+'</option>';
					}
				}

				selecTOption += '<option value="'+getBeasiswa[k]['ID']+'" '+selected+'>'+getBeasiswa[k]['DiscountType']+''+'</option>';
			}	
			selecTOption += '</select>';

			var selecTOption2 = '<select class="getDokumen" id-formulir = "'+getDataCalonMhs[i]['ID_register_formulir']+'" style="width: 260px;"" id = "getDokumen'+getDataCalonMhs[i]['ID_register_formulir']+'" >';
				selecTOption2 += '<option value="'+'0'+'" '+'selected'+'>'+'--File Uploaded--'+''+'</option>';
			for (var k = 0; k < getDocument.length; k++) {
				if (getDataCalonMhs[i]['RangkingRapor'] != 0) {
					var selected = (getDocument[k]['Attachment']==getDataCalonMhs[i]['Attachment']) ? 'selected' : '';
				}

				selecTOption2 += '<option value="'+getDocument[k]['ID']+'" '+selected+'>'+getDocument[k]['Attachment']+''+'</option>';
			}	

			selecTOption2 += '</select>';

			showFile = '';
			if (getDataCalonMhs[i]['RangkingRapor'] != 0) {
				showFile = '<a href="javascript:void(0)" class="show_a_href" id = "show'+getDataCalonMhs[i]['ID_register_formulir']+'" filee = "'+getDataCalonMhs[i]['Attachment']+'" Email = "'+getDataCalonMhs[i]['Email']+'">Show</a>';
			}

			var textArea = '<textarea rows="2" cols="5" name="textarea" class="limited form-control ket" data-limit="50" maxlength="50" id-formulir = "'+getDataCalonMhs[i]['ID_register_formulir']+'" id = "ket'+getDataCalonMhs[i]['ID_register_formulir']+'">'+getDataCalonMhs[i]['NoteRev']+'</textarea>Max<span id="chars'+getDataCalonMhs[i]['ID_register_formulir']+'">50</span> characters';	

			var Code = (getDataCalonMhs[i]['No_Ref'] != '') ? getDataCalonMhs[i]['FormulirCode'] + ' / ' + getDataCalonMhs[i]['No_Ref'] : getDataCalonMhs[i]['FormulirCode'];
			var Rangking = (getDataCalonMhs[i]['RangkingRapor'] != 0) ? 'Rangking : '+getDataCalonMhs[i]['RangkingRapor'] : "";
			$(".tableData tbody").append(
					'<tr id = "id_formulir'+getDataCalonMhs[i]['ID_register_formulir']+'">'+
						'<td align= "center">'+no+'&nbsp<input type="checkbox" nama ="'+getDataCalonMhs[i]['Name']+'" class="uniform" value ="'+getDataCalonMhs[i]['ID_register_formulir']+'"</td>'+
						'<td>'+getDataCalonMhs[i]['Name']+'<br>'+getDataCalonMhs[i]['NamePrody']+'<br>'+getDataCalonMhs[i]['SchoolName']+'</td>'+
						'<td>'+Code+'</td>'+
						'<td>'+selecTOption+'<br><br>'+selecTOption2+'<br><br>'+Rangking+'<br><br>'+showFile+'</td>'+
						//'<td>'+getDataCalonMhs[i]['NamePrody']+'</td>'+
						//'<td>'+getDataCalonMhs[i]['SchoolName']+'</td>'+
						isi_payment+
						'<td>'+textArea+'</td>'+

					'</tr>' 	
			);

			no++;
		}

		$('.costInput').maskMoney({thousands:'.', decimal:',', precision:2,allowZero: true});
		$('.costInput').maskMoney('mask', '9894');

		EvkeyketTable();

		pageHtml = 'tuition_fee';
	}

	function EvkeyketTable()
	{
		$(".ket").keyup(function(){
			var maxLength = $(this).attr('maxlength');
			var length = $(this).val().length;
			var id_formulir = $(this).attr('id-formulir');
			var length = maxLength-length;
			$('#chars'+id_formulir).text(length);
		})
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