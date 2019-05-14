<div class="row">
	<div class="col-xs-2">
		<?php if ($this->session->userdata('IDdepartementNavigation') == 4): ?>
			<div><a href="<?php echo base_url().'purchasing/transaction/po/list' ?>" class = "btn btn-warning"> <i class="fa fa-arrow-circle-left"></i> Back to List</a></div>
		<?php else: ?>
			<div><a href="<?php echo base_url().'global/transaction/po/list' ?>" class = "btn btn-warning"> <i class="fa fa-arrow-circle-left"></i> Back to List</a></div>
		<?php endif ?>
			<?php if ($bool): ?>
				<!-- <div style="margin-top: 10px;"><button class="btn btn-danger" id = "CustomItem" code = "<?php echo $Code ?>"> <i class="fa fa-th-list right-margin" aria-hidden="true"></i> Custom Item</button></div> -->
			<?php endif ?>
	</div>
</div>
<div class="row" style="margin-top: 2px;">
	<div class="col-xs-4 col-md-offset-4">
		<div class="row">
			<div class="col-xs-4 col-md-offset-2" align="center">
				<p><h3>Purchase Order</h3></p>
				<p><?php echo $Code ?></p>
			</div>	
		</div>
		<!-- <p><h3>Purchase Order</h3></p>
		<p><?php echo $Code ?></p> -->
	</div>
	<div class="col-xs-4" align="right">
		
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
		total_po_detail : 0,
	};
	$(document).ready(function() {
	   //loadingStart();
	   Get_data_po().then(function(data){
	   		ClassDt.po_data = data;
	   		WriteHtml();
	   		// Get_data_pr().then(function(data){

	   				
	   		//     //loadingEnd(500);
	   		// })
	       loadingEnd(2000);
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
						'<div class = "col-xs-4">'+
							'<div><b>YAY Pendidikan Agung Podomoro</b></div>'+
							'<div>Podomoro City APL Tower, Lantai 5</div>'+
							'<div>Jl. Let Jend. S. Parman Kav 28, Jakarta 11470</div>'+
							'<div>Telp 021 29200456</div>'+
							'<div style = "margin-top:20px;">PIC : '+PICPU+'</div>'+
						'</div>'+
						'<div class = "col-xs-3 col-md-offset-5">'+
							'<div><u>Jakarta, '+po_create[0]['CreatedAt_Indo']+'</u></div>'+
							'<div style = "margin-top : 20px;">Kepada Yth :</div>'+
							'<div><b>'+po_create[0]['NamaSupplier']+'</b></div>'+
							'<div>'+po_create[0]['PICName']+' ('+po_create[0]['NoTelp']+')'+'</div>'+
						'</div>'+
					'</div>'+
					'<div class = "row" style = "margin-top : 10px;">'+
						'<div class = "col-xs-12">'+
							'<div>Bersama ini kami meminta untuk dikirim barang-barang sebagai berikut :</div>'+
						'</div>'+
					'</div>'+
					'<div id = "r_tblDetail"></div>'+
					'<div id = "r_terbilang"></div>'+
					'<div id = "r_signatures"></div>'+
					'<div id = "r_footer"></div>';
		$('#PageContain').html(html);
		makeTblDetail();						
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
								'<div class="table-responsive">'+
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
									'</table>'+
								'</div>'+
						   '</div></div>';
			var html = htmlBtnAdd + htmlInputPO;			   
			$('#r_tblDetail').html(html);			   			

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

		}

		ClassDt.total_po_detail = total;
		return html;
	}
</script>
<?php endif ?>	