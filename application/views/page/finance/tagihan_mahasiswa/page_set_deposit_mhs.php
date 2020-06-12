<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading clearfix">
			    <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Set Deposit Mahasiswa</h4>
			</div>
			<div class="panel-body">
  				<div class="row">
  					<div class="col-md-12">
  						<div class="table-responsive">
  							<table class="table table-bordered" id= "tableMHS">
  								<thead>
  									<tr style="background: #333;color: #fff;">
  										<td>No</td>
  										<td>NPM,Name,Email</td>
  										<td>Prodi</td>
  										<td>Deposit</td>
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
	let oTable;
	const getTable = () => {
		const selectorTable = $('#tableMHS');
		var table = selectorTable.DataTable({
		    "fixedHeader": true,
		    "processing": true,
		    "destroy": true,
		    "serverSide": true,
		    "lengthMenu": [
		        [10, 25],
		        [10, 25]
		    ],
		    "iDisplayLength": 10,
		    "ordering": false,
		    "language": {
		        "searchPlaceholder": "Search",
		    },
		    "ajax": {
		        url: base_url_js + "rest3/__LoadStudents_server_side", // json datasource
		        ordering: false,
		        type: "post", // method  , by default get
		        data: function(token) {
		            var data = {
		                auth: 's3Cr3T-G4N',
		            };
		            var get_token = jwt_encode(data, "UAP)(*");
		            token.token = get_token;
		        },
		        error: function() { // error handling
		            $(".datatable_STD-grid-error").html("");
		            $("#datatable_STD-grid").append(
		                '<tbody class="datatable_STD-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
		            );
		            $("#datatable_STD-grid_processing").css("display", "none");
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
		          'targets': 1,
		          'searchable': false,
		          'orderable': false,
		          'className': 'dt-body-center',
		          'render': function (data, type, full, meta){
		            let html = '';
		            let dt = jwt_decode(full['data']);
		            html = dt['NPM']+' - '+dt['Name']+'<br/>'+'<span style = "color : green;">'+dt['EmailPU']+'</span>';
		            return html;
		          }
		        },
		        {
		          'targets': 2,
		          'searchable': false,
		          'orderable': false,
		          'className': 'dt-body-center',
		          'render': function (data, type, full, meta){
		          	 let html = '';
		             let dt = jwt_decode(full['data']);
		             html = dt['ProdiName'];
		             return html;
		          }
		        },
		        {
		          'targets': 3,
		          'searchable': false,
		          'orderable': false,
		          'className': 'dt-body-center',
		          'render': function (data, type, full, meta){
		          	 let html = '';
		             let dt = jwt_decode(full['data']);
		             html = formatRupiah(dt['Deposit']);
		             return html;
		          }
		        },
		        {
		            'targets': 4,
		            'searchable': false,
		            'orderable': false,
		            'className': 'dt-body-center',
		            'render': function (data, type, full, meta){
		                let btnAction = '';
		                let dt = jwt_decode(full['data']);
		                let NPM =  dt['NPM'];
		                btnAction = '<button class = "btn btn-sm btn-success btnCredit" data = "'+full['data']+'"  npm = "'+NPM+'">Credit </button> '+

		                			'<button class = "btn btn-sm btn-warning btnDebit" data = "'+full['data']+'"  npm = "'+NPM+'">Debit</button> ' +

		                			'<button class = "btn btn-sm btn-primary btnLog"  data = "'+full['data']+'" npm = "'+NPM+'">Log</button>'
		                			;
		                return btnAction;
		            }
		        },
		    ],
		    'createdRow': function(row, data, dataIndex) {
		      $(row).attr('datatoken',data.data);
		    },
		    dom: 'l<"toolbar">frtip',
		    "initComplete": function(settings, json) {

		    }
		});

		oTable = table;
	};

	const CreDe = (NPM,action,dataMHS) => {
		const title = action+' || '+dataMHS['Name'];
		let html  = '<div class = "row">'+
						'<div class = "col-md-8">'+
							'<div class = "form-group">'+
								'<label>Input '+action+'</label>'+
								'<input style = "width:50%;" type = "text" class = "form-control frmMoney" placeholder = "Input '+action+'" />'+
							'</div>'+
							'<div class = "form-group">'+
								'<label>Description</label>'+
								'<textarea class =  "form-control frmDesc" placeholder = "Description" rows = "3"></textarea>'+
							'</div>'+
						'</div>'+
					'</div>';


		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+title+'</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnSaveDeposit" class="btn btn-success" npm = "'+NPM+'" action = "'+action+'"  >Save</button>'+' <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});

		$('.frmMoney').maskMoney({thousands:'.', decimal:',', precision:2,allowZero: true});
		$('.frmMoney').maskMoney('mask', '9894');
	};

	const showLogDepo = (NPM,dataMHS) => {

		const Title = 'History' +' || '+dataMHS['Name'];
		let html = '<div class = "row">'+
						'<div class = "col-md-12">'+
							'<table class = "table tblLog">'+
								'<thead>'+
									'<tr>'+
										'<td>No</td>'+
										'<td>Credit</td>'+
										'<td>Debit</td>'+
										'<td>Desc</td>'+
										'<td>By</td>'+
										'<td>Time</td>'+
									'</tr>'+
								'</thead>'+
								'<tbody></tbody>'+
							'</table>'+
						'</div>'+
					'</div>'	

		;


		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+Title+'</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});

		const selTable = $('.tblLog');
		// action datatables
		ModalTabelHistory(NPM,selTable);

	}

	const ModalTabelHistory = (NPM,selTable) => {
		var table = selTable.DataTable({
		    "fixedHeader": true,
		    "processing": true,
		    "destroy": true,
		    "serverSide": false,
		    "lengthMenu": [
		        [10, 25],
		        [10, 25]
		    ],
		    "iDisplayLength": 10,
		    "ordering": false,
		    "language": {
		        "searchPlaceholder": "Search",
		    },
		    "ajax": {
		        url: base_url_js + "rest3/__HistoryDeposit", // json datasource
		        ordering: false,
		        type: "post", // method  , by default get
		        data: function(token) {
		            var data = {
		                auth: 's3Cr3T-G4N',
		                action : 'history',
		                data : {
		                	NPM : NPM,
		                }
		            };
		            var get_token = jwt_encode(data, "UAP)(*");
		            token.token = get_token;
		        },
		        error: function() { // error handling
		            $(".datatable_STD-grid-error").html("");
		            $("#datatable_STD-grid").append(
		                '<tbody class="datatable_STD-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
		            );
		            $("#datatable_STD-grid_processing").css("display", "none");
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
		          'targets': 1,
		          'searchable': false,
		          'orderable': false,
		          'className': 'dt-body-center',
		          'render': function (data, type, full, meta){
		            let html = '';
		            html =formatRupiah(full[1]);
		            return html;
		          }
		        },
		        {
		          'targets': 2,
		          'searchable': false,
		          'orderable': false,
		          'className': 'dt-body-center',
		          'render': function (data, type, full, meta){
		          	 let html = '';
		             html =formatRupiah(full[2]);
		             return html;
		          }
		        },
		        {
		          'targets': 3,
		          'searchable': false,
		          'orderable': false,
		          'className': 'dt-body-center',
		          'render': function (data, type, full, meta){
		          	 let html = '';
		             html =full[4];
		             return html;
		          }
		        },
		        {
		            'targets': 4,
		            'searchable': false,
		            'orderable': false,
		            'className': 'dt-body-center',
		            'render': function (data, type, full, meta){
		             let html = '';
		             html =full[6];
		             return html;
		            }
		        },
		        {
		            'targets': 5,
		            'searchable': false,
		            'orderable': false,
		            'className': 'dt-body-center',
		            'render': function (data, type, full, meta){
		              let html = '';
		              html =moment(full[5]).format('dddd, DD MMM YYYY HH:mm');
		              return html;
		            }
		        },
		    ],
		    'createdRow': function(row, data, dataIndex) {
		      
		    },
		    dom: 'l<"toolbar">frtip',
		    "initComplete": function(settings, json) {

		    }
		});
	}

	$(document).ready(function(e){
		getTable();
	})

	$(document).on('click','.btnCredit',function(e){
		const action = "Credit";
		const NPM = $(this).attr('npm');
		const data = jwt_decode($(this).attr('data'));
		CreDe(NPM,action,data);
	})

	$(document).on('click','.btnDebit',function(e){
		const action = "Debit";
		const NPM = $(this).attr('npm');
		const data = jwt_decode($(this).attr('data'));
		CreDe(NPM,action,data);
	})

	$(document).on('click','.btnLog',function(e){
		const NPM = $(this).attr('npm');
		const data = jwt_decode($(this).attr('data'));
		showLogDepo(NPM,data);
	})

	$(document).on('click','#ModalbtnSaveDeposit', async function(e){
		const itsme = $(this);
		const action = itsme.attr('action');
		const NPM = itsme.attr('npm');
		let getValue = $('.frmMoney').val();
		getValue = findAndReplace(getValue, ".","");
		getValue = findAndReplace(getValue, ",",".");
		const CreDeInput = (action == 'Credit') ? {Credit : getValue} : {Debit : getValue};
		const url  =  base_url_js+'finance/deposit/action';
		let dataForm = {
			action : action,
			data :  {
				NPM : NPM,
				Desc : $('.frmDesc').val(),
			},
		};
		dataForm['data'] = {...dataForm['data'],...CreDeInput};
		let token = jwt_encode(dataForm,'UAP)(*');
		if(confirm('Are you sure ?')){
			const frmMoney = parseFloat( getValue);
			if (frmMoney > 0) {
				loading_button2(itsme);
				try {
				  const ajaxGetResponse = await AjaxSubmitFormPromises(url,token);
				  if(ajaxGetResponse.status == 1){
				  		toastr.success('Saved');
				  		oTable.ajax.reload(null, false);
				  		await timeout(2500);
				  		$('#GlobalModalLarge').modal('hide');
				  }
				  else
				  {
				  	toastr.error(ajaxGetResponse.msg,'!Error');
				  }
				}
				catch(err) {
				  console.log(err);
				}

				end_loading_button2(itsme);
			}
			else
			{
				toastr.info('Input '+action+ ' tidak boleh 0');
			}

		}
		
	})
</script>