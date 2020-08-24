<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<div class="widget-header">
				<h4 class="header"><i class="icon-reorder"></i> <?php echo $NameMenu ?></h4>
			</div>
			<div class="widget-content">
				<div class="row">
					<div class="col-md-4 col-md-offset-4">
						<div class="thumbnail">
							<div class="form-group" style="padding: 10px;">
								<label>Class Of</label>
								<select class="form-control" id = "searchClassOf">
									
								</select>
							</div>
							<div style="padding-left: 10px;">
								<pre style="color: red;">Refund data tidak termasuk kategori intake</pre>
								<p  style="color: red;">* <i class="fa fa-circle" style="color:#bdc80e;"></i> <span style="color: #bdc80e;">Online</span></p>
								<p  style="color: red;"> * <i class="fa fa-circle" style="color:#db4273;"></i> <span style="color: #db4273;">Offline</span></p>
							</div>
						</div>
					</div>
				</div>
			    <div class="row">
			    	<div class="col-md-12">
			    		<div style="margin: 10px;">
			    			<table class="table table-bordered" id = "tableRefund">
			    				<caption><h3 style="color: blue;">Choose Data</h3></h3></caption>
			    				<thead>
			    					<tr>
			    						<td style="width: 3%">No</td>
			    						<td style="width: 25%">Personal Info</td>
			    						<td>Formulir Number</td>
			    						<td>Action</td>
			    					</tr>
			    				</thead>
			    				<tbody></tbody>
			    			</table>		    			
			    		</div>
			    	</div>
			    </div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	const getUrl = window.location.href;
	let actionCountPayment = 0;
	let oTableRefund;

	const loadDefault = () => {
		SelectOpLoadClassOf();
		LoadDataRefund();
	};

	const SelectOpLoadClassOf = () => {
		const starClassOf = 2019;
		const endClassOf = <?php echo date('Y') ?>;
		$('#searchClassOf').empty();
		for (var i = starClassOf; i <= endClassOf; i++) {
			const selected = (i == endClassOf) ? 'selected' : '';
			$('#searchClassOf').append(
				'<option value = "'+i+'" '+selected+' >Class of '+i+'</option>'
				);
		}
	};

	const LoadDataRefund = () => {
		$('#tableRefund tbody').empty();
		var table = $('#tableRefund').DataTable({
		    "fixedHeader": true,
		    "processing": true,
		    "destroy": true,
		    "serverSide": true,
		    "lengthMenu": [
		        [10,25],
		        [10,25]
		    ],
		    "iDisplayLength": 10,
		    "ordering": false,
		    "language": {
		        "searchPlaceholder": "Search Formulir Code / Name",
		    },
		    "ajax": {
		        url: base_url_js+'admission/proses-calon-mahasiswa/getDataPersonal_Candidate_to_be_mhs', // json datasource
		        ordering: false,
		        type: "post", // method  , by default get
		        data : function(datapost){
                       // Append to data
                       datapost.tahun = $('#searchClassOf option:selected').val();
                       datapost.FormulirType = '%';
                       datapost.StatusPayment = '%';
                 },
		        error: function() { // error handling
		            $(".tableRefund-grid-error").html("");
		            $("#tableRefund-grid").append(
		                '<tbody class="tableRefund-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
		            );
		            $("#tableRefund-grid_processing").css("display", "none");
		        }
		    },
		    'columnDefs': [
		        {
		          'targets': 0,
		          'searchable': false,
		          'orderable': false,
		          'className': 'dt-body-center',
		        },
		        {
		          'targets': 3,
		          'searchable': false,
		          'orderable': false,
		          'className': 'dt-body-center',
		          'render': function (data, type, full, meta){
		            let html = '<button class = "btn btn-primary chooseDataRefund" style = "width:100%;" datatoken = "'+full[10]+'">Choose</button>';
		            return html;
		           }
		        },
		    ],
		    'createdRow': function(row, data, dataIndex) {
		         $( row ).find('td:eq(3)').attr('style','vertical-align: middle;');
		    },
		    dom: 'l<"toolbar">frtip',
		    "initComplete": function(settings, json) {
		    	$('input[type="checkbox"]').remove();
		    }
		});

		oTableRefund = table;
	}

	$(document).ready(function(e){
		loadDefault();
	})

	$(document).on('change','#searchClassOf',function(e){
		oTableRefund.ajax.reload(null, false);
	})

	const paymentCount = (data) => {

		let Invoice = 0;
		let Pay = 0;
		let Left = 0;

		for (var i = 0; i < data.length; i++) {
			Invoice += parseInt(data[i].Invoice);
			if (data[i].Status == 1) {
				Pay += parseInt(data[i].Invoice);
			}
		}

		Left =  Invoice - Pay;

		return {
			Invoice :Invoice ,
			Pay : Pay,
			Left : Left,
		}
	}

	const dataModalAction = async(data) => {
		const decodeToken = jwt_decode(data);
		var url = base_url_js+'finance/getPayment_detail_admission';
		var data = {
		    ID_register_formulir : decodeToken.ID_register_formulir,
		};
		var token = jwt_encode(data,'UAP)(*');
		const response = await AjaxSubmitFormPromises(url,token);
		
		const procePayment = paymentCount(response);
		var html = '';
		var table = '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
		              '<thead>'+
		                  '<tr>'+
		                      '<th style="width: 5px;">No</th>'+
		                      '<th style="width: 55px;">Invoice</th>'+
		                      '<th style="width: 55px;">BilingID</th>'+
		                      '<th style="width: 55px;">Status</th>'+
		                      '<th style="width: 55px;">Deadline</th>'+
		                      '<th style="width: 55px;">UpdateAt</th>';
		table += '</tr>' ;
		table += '</thead>' ;
		table += '<tbody>' ;

		var isi = '';
		const DetailPaymentArr = response;
		for (var j = 0; j < DetailPaymentArr.length; j++) {
		  var yy = (DetailPaymentArr[j]['Invoice'] != '') ? formatRupiah(DetailPaymentArr[j]['Invoice']) : '-';
		  var status = (DetailPaymentArr[j]['Status'] == 0) ? 'Belum Bayar' : 'Sudah Bayar';
		  isi += '<tr>'+
		        '<td>'+ (j+1) + '</td>'+
		        '<td>'+ yy + '</td>'+
		        '<td>'+ DetailPaymentArr[j]['BilingID'] + '</td>'+
		        '<td>'+ status + '</td>'+
		        '<td>'+ DetailPaymentArr[j]['Deadline'] + '</td>'+
		        '<td>'+ DetailPaymentArr[j]['UpdateAt'] + '</td>'+
		      '<tr>';
		}

		table += isi+'</tbody>' ;
		table += '</table>' ;

		html += table;

		html += '<br/>';
		html += '<div class = "row" style = "padding-left : 20px;padding-right : 20px;">'+
					'<div class = "col-xs-4">'+
						'<p style = "color:green;">Invoice : '+formatRupiah(procePayment.Invoice)+'</p>'+
					'</div>'+
					'<div class = "col-xs-4">'+
						'<p style = "color:green;">Bayar : '+formatRupiah(procePayment.Pay)+'</p>'+
					'</div>'+
					'<div class = "col-xs-4">'+
						'<p style = "color:green;">Sisa : '+formatRupiah(procePayment.Left)+'</p>'+
					'</div>'+
				'</div>';

		html += '<div class = "row">'+
					'<div class = "col-md-12">'+
						'<div class = "well" style = "padding:10px;">'+
								'<div class = "row">'+
									'<div class = "col-md-12">'+
										'<h4 style = "color:red;">Set Refund</h4>'+
									'</div>'+
									'<br/>'+
									'<div class = "col-md-4">'+
										'<div class = "form-group">'+
											'<label>Price</label>'+
											'<input type="text"  class = "form-control frmInput" name = "Price" rule = "required">'+
										'</div>'+	
									'</div>'+
									'<div class = "col-md-4">'+
										'<div class = "form-group">'+
											'<label>Description</label>'+
											'<textarea class = "form-control frmInput" name = "Desc" rule = ""></textarea>'+
										'</div>'+
									'</div>'+
								'</div>'+
						'</div>'+
					'</div>'+
				'</div>';

		var footer = '<button class = "btn btn-success btnSaveSetRefund" ID_register_formulir = "'+decodeToken.ID_register_formulir+'">Save</button> <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
		    '';

		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Detail Payment '+decodeToken.Name+'</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html(footer);
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});

		actionCountPayment = (procePayment.Invoice + procePayment.Pay);

		$('.frmInput[name="Price"]').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		$('.frmInput[name="Price"]').maskMoney('mask', '9894');


	}

	const valueOnly = (valData) => {
		let arr = valData.split('.');
		let str = '';
		for (var i = 0; i < arr.length; i++) {
			str += arr[i];
		}
		return str;
	};

	const actionSaveRefund = async(selector,ID_register_formulir) => {
		let Price = $('.frmInput[name="Price"]').val();
		Price = valueOnly(Price);
		let Desc = $('.frmInput[name="Desc"]').val();
		if (Price != '' && Price > 0) {
			if (Price > actionCountPayment) {
				toastr.info('Refund tidak boleh lebih besar dari (Invoice+Bayar)');
				return;
			}

			const data = {
				action : 'setRefund',
				data : {
					ID_register_formulir : ID_register_formulir,
					Price : Price,
					Desc : Desc,
				}
			};

			var token = jwt_encode(data,"UAP)(*");	
			loading_button2(selector);
			try{
				const response = await AjaxSubmitFormPromises(getUrl,token);
				if (response.status != 1) {
					toastr.error(response.msg);
					end_loading_button2(itsme);
				}
				else
				{
					oTableRefund.ajax.reload(null, false);
					toastr.success('Saved');
					$('#GlobalModalLarge').modal('hide');
					
				}
			}
			catch(err){
				toastr.error('something wrong');
				end_loading_button2(selector);
			}

		}
		else
		{
			toastr.info('Price tidak boleh kecil dari 0');
		}
	};

	$(document).on('click','.btnSaveSetRefund',function(e){
		const itsme =  $(this);
		const ID_register_formulir =  itsme.attr('id_register_formulir');
		actionSaveRefund(itsme,ID_register_formulir);
	})

	$(document).on('click','.chooseDataRefund',function(e){
		actionCountPayment = 0;
		const dataToken  = $(this).attr('datatoken');
		dataModalAction(dataToken);
	})
</script>