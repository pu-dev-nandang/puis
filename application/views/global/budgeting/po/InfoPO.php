<style type="text/css">
.borderless thead>tr>th {
    vertical-align: bottom;
    border-bottom: none !important;
}

.borderless thead>tr>th, .borderless tbody>tr>th, .borderless tfoot>tr>th, .borderless thead>tr>td, .borderless tbody>tr>td, .borderless tfoot>tr>td {
	    padding: 8px;
	    line-height: 1.428571429;
	    vertical-align: top;
	    border-top: none !important;
	}
@page {
  size: A4;
  margin: 1;
}
@media print {
    .container { 
      display: block !important;
        font-size: 10px; 
        top: -70pt;
        left:0pt;
        right: 0pt;
    }
    .tbody1 {
      font-size: 10px; 
    }
    .btn .noPrint a { 
    	display:none !important;
    }
}
</style>
<div class="row noPrint">
	<div class="col-xs-2">
		<?php if ($this->session->userdata('IDdepartementNavigation') == 4): ?>
			<div><a href="<?php echo base_url().'purchasing/transaction/po/list' ?>" class = "btn btn-warning"> <i class="fa fa-arrow-circle-left"></i> Back to List</a></div>
		<?php else: ?>
			<div><a href="<?php echo base_url().'global/transaction/po/list' ?>" class = "btn btn-warning"> <i class="fa fa-arrow-circle-left"></i> Back to List</a></div>
		<?php endif ?>
			<?php if ($bool): ?>

			<?php endif ?>
	</div>
</div>
<div id="DocPenawaran" class="row"></div>
<div class="row" style="margin-top: 2px;">
	<div class="col-xs-12">
		<table class="table borderless">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align :center">
						<p><h3>Purchase Order</h3></p>
						<p><?php echo $Code ?></p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<?php if ($bool): ?>
<div id = "PageContain">
	
</div>
<?php else: ?>
	<div class="row">
		<div class="col-xs-12" align="center">
			<h2>Your not Authorize</h2>
		</div>
	</div>	
<?php endif ?>

<script type="text/javascript">
	$(document).ready(function() {
	    $("#container").attr('class','fixed-header sidebar-closed');
	}); // exit document Function
</script>

<?php if ($bool): ?>
<script type="text/javascript">
	var ClassDt = {
		Code : "<?php echo $Code ?>",
		po_create_m  : <?php echo json_encode($G_data) ?>,
		po_data : [],
		PRCode_arr : [],
		total_po_detail : 0,
	};
	$(document).ready(function() {
	   loadingStart();
	   Get_data_po().then(function(data){
	   		ClassDt.po_data = data;
	   		WriteHtml();
	   })
	}); // exit document Function


	function Get_data_po()
	{
		var def = jQuery.Deferred();
		var url = base_url_js+"rest2/__Get_data_po_by_Code";
		var data = {
		    Code : ClassDt.Code,
		    auth : 's3Cr3T-G4N',
		};
		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{token:token},function (resultJson) {
			def.resolve(resultJson);
		}).fail(function() {
		  toastr.info('No Result Data');
		  def.reject(); 
		})
			
		return def.promise();
	}

	function WriteHtml()
	{
		var dt = ClassDt.po_data;
		var po_create = dt.po_create;
		var JsonStatus = po_create[0]['JsonStatus'];
		JsonStatus = jQuery.parseJSON(JsonStatus);
		var PICPU = JsonStatus[0]['Name'];
		// PageContain
		var html = '<div class = "row">'+
						'<div class = "col-xs-12">'+
							'<table class = "table borderless" style = "margin-left: -8px;">'+
								'<thead></thead>'+
								'<tbody>'+
									'<tr>'+
										'<td>'+
											'<div><b>YAY Pendidikan Agung Podomoro</b></div>'+
											'<div>Podomoro City APL Tower, Lantai 5</div>'+
											'<div>Jl. Let Jend. S. Parman Kav 28, Jakarta 11470</div>'+
											'<div>Telp 021 29200456</div>'+
											'<div style = "margin-top:20px;">PIC : '+PICPU+'</div>'+
										'</td>'+
										'<td></td>'+
										'<td>'+
											'<div style = "margin-left : 50%">'+
												'<div><u>Jakarta, '+po_create[0]['CreatedAt_Indo']+'</u></div>'+
												'<div style = "margin-top : 20px;">Kepada Yth :</div>'+
												'<div><b>'+po_create[0]['NamaSupplier']+'</b></div>'+
												'<div>'+po_create[0]['PICName']+' ('+po_create[0]['NoTelp']+')'+'</div>'+
											'</div>'+	
										'</td>'+
									'</tr>'+
								'</tbody>'+
							'</table>'+
						'</div>'+						
						// '<div class = "col-xs-4">'+
						// 	'<div><b>YAY Pendidikan Agung Podomoro</b></div>'+
						// 	'<div>Podomoro City APL Tower, Lantai 5</div>'+
						// 	'<div>Jl. Let Jend. S. Parman Kav 28, Jakarta 11470</div>'+
						// 	'<div>Telp 021 29200456</div>'+
						// 	'<div style = "margin-top:20px;">PIC : '+PICPU+'</div>'+
						// '</div>'+
						// '<div class = "col-xs-3 col-md-offset-5">'+
						// 	'<div><u>Jakarta, '+po_create[0]['CreatedAt_Indo']+'</u></div>'+
						// 	'<div style = "margin-top : 20px;">Kepada Yth :</div>'+
						// 	'<div><b>'+po_create[0]['NamaSupplier']+'</b></div>'+
						// 	'<div>'+po_create[0]['PICName']+' ('+po_create[0]['NoTelp']+')'+'</div>'+
						// '</div>'+
					'</div>'+
					'<div class = "row" style = "margin-top : 10px;">'+
						'<div class = "col-xs-12">'+
							'<div>Bersama ini kami meminta untuk dikirim barang-barang sebagai berikut :</div>'+
						'</div>'+
					'</div>'+
					'<div id = "r_tblDetail"></div>'+
					'<div id = "r_terbilang"></div>'+
					'<div id = "r_signatures"></div>'+
					'<div id = "r_footer"></div>'+
					'<div id = "r_action"></div>';
		$('#PageContain').html(html);
		makeTblDetail();
		makeSignatures();
		makeFooter();
		makeDocPenawaran();
		makeAction();						
	}

	function makeDocPenawaran()
	{
		console.log(ClassDt);
		var po_data = ClassDt.po_data;
		var po_create = po_data['po_create'];
		var FileOffer = jQuery.parseJSON(po_create[0]['FileOffer']);
		$('#DocPenawaran').html('<div class="col-xs-12"><a href="'+base_url_js+'fileGetAny/budgeting-po-'+FileOffer[0]+'" target="_blank"> Doc Penawaran</a></div>');

	}

	function makeAction()
	{
		// r_action

	}

	function makeFooter()
	{
		//r_footer
		var html = '<div class = "row" style = "margin-top : 40px;">'+
						'<div class = "col-xs-4">'+
							'<table class = "table borderless">'+
									'<thead></thead>'+
									'<tbody>'+
										'<tr style = "height : 70px">'+
											'<td>'+
												'No. PR : '+
											'</td>'+
											'<td>';

		var arr = ClassDt.PRCode_arr;
		var t = '';									
		for (var i = 0; i < arr.length; i++) {
			t += '<li>'+arr[i]+'</li>';
		}

		html += t;
		html += '</td></tr>';
		html += '<tr style = "height : 100px">'+
					'<td colspan = "2">'+
						'<b>Diterima oleh Vendor,'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<td colspan = "2">'+
						'<i>(Tandatangan,Nama,Stampel),</br>Note : Copi PO mohon dapat dilampirkan pada kami bersama invoice</i>'+
					'</td>'+
				'</tr>';

		html += '</tbody></table></div></div>';		
		$('#r_footer').html(html);


	}

	function makeSignatures(){
		// r_signatures
		var dt = ClassDt.po_data;
		var po_create = dt['po_create'];
		var html = '<div class= "row" style = "margin-top : 40px;">'+
						'<div class = "col-xs-12">'+
							'<table class = "table borderless">'+
								'<thead>'+
									'<tr>'
				;
		var JsonStatus = jQuery.parseJSON(po_create[0]['JsonStatus']);
		for (var i = 0; i < JsonStatus.length; i++) {
			var style = '';
			if (i == 0) {
				style = 'style = "text-align :left"';
			}
			else if(parseInt(JsonStatus.length)-1 == i){
				style = 'style = "text-align :right"';
			}
			else
			{
				style = 'style = "text-align :center"';
			}
			html += '<th '+style+'>'+
						JsonStatus[i].NameTypeDesc+
					'</th>';	
		}

		html += '</tr>';

		html += '</thead>'+
					'<tbody>'+
						'<tr style = "height : 51px">';
		for (var i = 0; i < JsonStatus.length; i++) {
			var v = '-';
			if (JsonStatus[i].Status == '2' || JsonStatus[i].Status == 2) {
				v = '<i class="fa fa-times" aria-hidden="true" style="color: red;"></i>';
			}
			else if(JsonStatus[i].Status == '1' || JsonStatus[i].Status == 1 )
			{
				v = '<i class="fa fa-check" style="color: green;"></i>';
			}
			else
			{
				v = '-';
			}

			var style = '';
			if (i == 0) {
				style = 'style = "text-align :left"';
			}
			else if(parseInt(JsonStatus.length)-1 == i){
				style = 'style = "text-align :right"';
			}
			else
			{
				style = 'style = "text-align :center"';
			}
			html += '<td '+style+'>'+
						v+
					'</td>';	
		}

		html += '</tr></tbody>';				
		html += '<tfoot>'+
					'<tr>';

		for (var i = 0; i < JsonStatus.length; i++) {
			var style = '';
			if (i == 0) {
				style = 'style = "text-align :left"';
			}
			else if(parseInt(JsonStatus.length)-1 == i){
				style = 'style = "text-align :right"';
			}
			else
			{
				style = 'style = "text-align :center"';
			}
			html += '<td '+style+'><b>'+JsonStatus[i].Name+'</b></td>';		
		}

		html += '</tr></tfoot></table></div></div>';							
		$('#r_signatures').html(html);

	}

	function makeTblDetail()
	{
		// r_tblDetail
		var dt = ClassDt.po_data;
		var po_create = dt['po_create'];
		htmlBtnAdd =    '';
		var IsiInputPO = MakeIsiPO();
		var Subtotal = 	parseInt(ClassDt.total_po_detail)+parseInt(po_create[0]['AnotherCost'])	// 0 adalah persentase		
		var htmlInputPO = '<div class = "row" style = "margin-top : 15px;">'+
							'<div class = "col-md-12">'+
								//'<div class="table-responsive">'+
									'<table class="table table-bordered tableData" id ="table_input_po">'+
									'<thead>'+
									'<tr>'+
										'<th width = "3%" style = "text-align: center;background: #67a9a2;color: #FFFFFF;">No</th>'+
			                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 350px;">Nama Barang</th>'+
			                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 350px;">Spesification</th>'+
			                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 200px;">Date Needed</th>'+
			                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 100px;">Qty</th>'+
			                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 250px;">Harga</th>'+
    		                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 150px;">PPN(%)</th>'+
    		                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 150px;">Discount(%)</th>'+
			                            '<th style = "text-align: center;background: #67a9a2;color: #FFFFFF;width : 250px;">Sub Total</th>'+
									'</tr></thead>'+
									'<tbody>'+IsiInputPO+'</tbody>'+
									'<tfoot>'+
										'<tr style = "background-color: #3c6560;color: #FFFFFF">'+
											'<td colspan = "6">Total</td>'+
											'<td colspan = "3">'+formatRupiah(ClassDt.total_po_detail)+'</td>'+
										'</tr>'+
										'<tr style = "background-color: #3c6560;color: #FFFFFF">'+
											'<td colspan = "6">Biaya Lain-Lain</td>'+
											'<td colspan = "3">'+formatRupiah(po_create[0]['AnotherCost'])+'</td>'+
										'</tr>'+
										'<tr style = "background-color: #3c6560;color: #FFFFFF">'+
											'<td colspan = "6">Sub Total</td>'+
											'<td colspan = "3">'+formatRupiah(Subtotal)+'</td>'+
										'</tr>'+
										'<tr>'+
											'<td colspan = "9"><b>'+po_create[0]['Notes']+'</b></td>'+
										'</tr>'+		
									'</table>'+
								//'</div>'+
						   '</div></div>';

		_ajax_terbilang(Subtotal).then(function(data){
			var html = htmlBtnAdd + htmlInputPO;			   
			$('#r_tblDetail').html(html);
			$('#r_terbilang').html('<div class = "row" style = "margin-top : 20px;">'+
										'<div class="col-xs-12">'+
											'<b>Terbilang (Rupiah) : '+data+'</b>'+
										'</div>'+
									'</div>'		
			);

	    	loadingEnd(1000);
		})		   

	}

	function _ajax_terbilang(bilangan)
	{
		var def = jQuery.Deferred();
		var url = base_url_js+"rest2/__ajax_terbilang";
		var data = {
		    bilangan : bilangan,
		    auth : 's3Cr3T-G4N',
		};
		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{token:token},function (resultJson) {
			def.resolve(resultJson);
		}).fail(function() {
		  toastr.info('No Result Data');
		  def.reject(); 
		})
			
		return def.promise();
	}

	function MakeIsiPO()
	{
		var dt = ClassDt.po_data;
		var po_detail = dt['po_detail'];
		var html = '';
		var total = 0;
		for (var i = 0; i < po_detail.length; i++) {
			var Spesification = '';
			DetailCatalog = jQuery.parseJSON(po_detail[i]['DetailCatalog']);
			if (DetailCatalog.length > 0) {
				Spesification = '<div>Detail Catalog</div>';
				Spesification += '<div>';
				for (var prop in DetailCatalog) {
					Spesification += prop + ' :  '+DetailCatalog[prop]+'<br>';
				}

				Spesification +='</div>';
			}

			if (po_detail[i]['Desc'] != '' && po_detail[i]['Desc'] != null && po_detail[i]['Desc'] != undefined) {
				Spesification += '<div style = "margin-top : 5px;">Desc</div>';
				Spesification += '<div>'+po_detail[i]['Desc']+'</div>';

			}

			if (po_detail[i]['Spec_add'] != '' && po_detail[i]['Spec_add'] != null && po_detail[i]['Spec_add'] != undefined) {
				Spesification += '<div style = "margin-top : 5px;">Desc</div>';
				Spesification += '<div>'+po_detail[i]['Spec_add']+'</div>';
			}
			
			html +='<tr>'+
						'<td>'+(i+1)+'</td>'+
						'<td>'+po_detail[i]['Item']+'</td>'+
						'<td>'+Spesification+'</td>'+
						'<td>'+'<div align="center">'+po_detail[i]['DateNeeded']+'</div></td>'+
						'<td>'+'<div align="center">'+po_detail[i]['QtyPR']+'</div></td>'+
						'<td>'+'<div align="center">'+formatRupiah(po_detail[i]['UnitCost_PO'])+'</div></td>'+
						'<td>'+'<div align="center">'+po_detail[i]['PPN_PO']+'</div></td>'+
						'<td>'+'<div align="center">'+po_detail[i]['Discount_PO']+'</div></td>'+
						'<td>'+'<div align="center">'+formatRupiah(po_detail[i]['Subtotal'])+'</div></td>'+
					'</tr>';

			total = parseInt(total) + parseInt(po_detail[i]['Subtotal']);

			// add PRCode
			if (ClassDt.PRCode_arr.length == 0) {
				ClassDt.PRCode_arr.push(po_detail[i]['PRCode']);
			}
			else
			{
				var bool = true;
				for (var j = 0; j < ClassDt.PRCode_arr.length; j++) {
					var arr = ClassDt.PRCode_arr;
					if (arr[j] == po_detail[i]['PRCode']) {
						bool = false;
						break;
					}
				}

				if (bool) {
					ClassDt.PRCode_arr.push(po_detail[i]['PRCode']);
				}
			}			
		}

		ClassDt.total_po_detail = total;
		return html;
	}
</script>
<?php endif ?>	