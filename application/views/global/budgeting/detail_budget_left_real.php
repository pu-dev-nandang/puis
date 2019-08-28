<div class="row">
	<div class="col-xs-12" align="center">
		<h4><u><?php echo $G_data[0]['NameHeadAccount'].'-'.$G_data[0]['RealisasiPostName'] ?></u></h4>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<h4> First : <?php echo 'Rp '.number_format($G_data[0]['PriceBudgetAwal'],2,',','.') ?></h4>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<h4> Result : <?php echo 'Rp '.number_format($G_data[0]['Value'],2,',','.') ?></h4>
	</div>
</div>
<div id = "root" style="margin-top: 10px;">
	
</div>

<script type="text/javascript">
var ID_budget_left = "<?php echo $ID_budget_left ?>";
var G_budget_left_payment = <?php echo json_encode($G_budget_left_payment) ?>;
var month =['','Jan','Feb','Mar','April','Mei','Jun','Jul','Aug','Sep','Okt','Nov','Des'];
var G_data = <?php echo json_encode($G_data) ?>;
$(document).ready(function() {
	// loadingStart();
	// console.log(G_data);
	LoadFirstLoad();
}); // exit document Function

function LoadFirstLoad()
{
	var se_content = $('#root');
	var html = '';
	for (var i = 0; i < G_budget_left_payment.length; i++) {
		var MonthName = month[parseInt(G_budget_left_payment[i].Month)];
		html += '<div class = "row content_perbulan" year = "'+G_budget_left_payment[i].Year+'" month = "'+G_budget_left_payment[i].Month+'" >'+
					'<div class = "col-xs-12">'+
						'<div class="panel panel-primary" style="border-color: #437d73;">'+
							'<div class="panel-heading clearfix" style="background-color: #437d73;"><h4 class="panel-title pull-left" style="padding-top: 7.5px;">'+G_budget_left_payment[i].Year+' - '+MonthName+'</h4>'+
							'</div>'+
							'<div class="panel-body">'+
								'adasd'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>';	
	}

	se_content.html(html);
	LoopAjaxCallback();
}

function LoopAjaxCallback()
{
	$('.content_perbulan').each(function(){
		var se_content = $(this).find('.panel-body');
		se_content.html('<div class="row">' +
		    '<div class="col-md-12" style="text-align: center;">' +
		    '<h3 class="animated flipInX"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i> <span>Loading page . . .</span></h3>' +
		    '</div>' +
		    '</div>');

		var Year = $(this).attr('Year');
		var Month = $(this).attr('Month');
		load_budget_real_detail_byMonthYear__(Year,Month).then(function(response){
			var TotLess = 0;
			var TotAdd = 0;
			for (var i = 0; i < response.length; i++) {
				var Invoice = parseInt(response[i].Invoice);
				if (Invoice < 0) {
					var tt = Math.abs(response[i].Invoice);
					TotLess += parseInt(tt);
				}
				else
				{
					var tt = Math.abs(response[i].Invoice);
					TotAdd += parseInt(tt);
				}
				
			}	


			var html = '';
				html = '<div class = "row">'+
							'<div class = "col-xs-12">'+
								'<div class = "table-responsive">'+
								'<table id="tblBudgetRemaining" class="table table-bordered tblBudgetRemaining" cellspacing="0" width="100%">'+
							           '<thead>'+
							              '<tr>'+
							                 '<th style = "text-align: center;background: #20485A;color: #FFFFFF;width : 3%;">No</th>'+
							                 '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Payment</th>'+
							                 '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Less</th>'+
							                 '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Add</th>'+
							                 '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">At</th>'+
							                 '<th style = "text-align: center;background: #20485A;color: #FFFFFF;">By</th>'+
							              '</tr>'+
							           '</thead>'+
							           '<tbody></tbody>'+
			      				'</table>'+
			      				'</div>'+
			      			'</div>'+
			      		'</div>'+
			      		'<div class = "row TotalCount">'+
			      			'<div class = "col-xs-12">'+
			      				'<div class = "pull-right">'+
				      				'<p style = "color:red"> Total Less : '+formatRupiah(TotLess)+'</p>'+
				      				'<p style = "color:red"> Total Add : '+formatRupiah(TotAdd)+'</p>'+
				      			'</div>'+	
			      			'</div>'+
			      		'</div>';
			se_content.html(html);

			var table = se_content.find('.tblBudgetRemaining').DataTable({
			      "data" : response,
			      "searching": false,
			      "lengthMenu": [[5], [5]],
			      'columnDefs': [
				      {
				         'targets': 0,
				         'searchable': false,
				         'orderable': false,
				         'className': 'dt-body-center',
				         'render': function (data, type, full, meta){
				             return '';
				         }
				      },
				      {
				         'targets': 1,
				         'render': function (data, type, full, meta){
				         	var html = '';
				         	if (full.TypePayment != 'Revisi' && full.TypePayment != 'Mutasi') {
				         		// a href to finap
				         		var ID_ap = full.ID_ap;
				         		var tokenLink = jwt_encode(ID_ap,"UAP)(*");
				         		html = '<a href="'+base_url_js+'finance_ap/global/'+tokenLink+'" target="_blank">'+full.TypePayment+'</a>';
				         	}
				         	else
				         	{
				         		html = full.TypePayment;
				         	}
				         	
				         	if (full.CodeSPB != '' && full.CodeSPB != null) {
				         		html += '<br/>Code :'+full.CodeSPB;
				         	}	
				         	if (full.Code_po_create != '' && full.Code_po_create != null && full.Code_po_create != undefined) {
				         		html += '<br/>PO/SPK : '+full.Code_po_create;
				         	}
				             return html;
				         }
				      },
				      {
				         'targets': 2,
				         'render': function (data, type, full, meta){
				             	var html = '-';
				             	var Invoice = parseInt(full.Invoice);
				             	 if (Invoice < 0) {
				             	 	InvoiceWR = formatRupiah(Math.abs(Invoice));
				             	 	html = InvoiceWR;
				             	 }
				                 return html;
				         }
				      },
				      {
				         'targets': 3,
				         'render': function (data, type, full, meta){
				         	var html = '-';
				         	var Invoice = parseInt(full.Invoice);
				         	 if (Invoice >= 0) {
				         	 	InvoiceWR = formatRupiah(Math.abs(Invoice));
				         	 	html = InvoiceWR;
				         	 }
				             return html;
				         }
				      },
				      {
				         'targets': 4,
				         'render': function (data, type, full, meta){
				             return full.PostingDate;
				         }
				      },
				      {
				         'targets': 5,
				         'render': function (data, type, full, meta){
				             return full.NameCreatedPayment;
				         }
				      },
			      ],
			      'createdRow': function( row, data, dataIndex ) {
			      		var Invoice = parseInt(data.Invoice);
			      		if (Invoice < 0) {
			      			$(row).find('td:eq(2)').attr('Invoice',data.Invoice);
			      			$(row).find('td:eq(3)').attr('Invoice',0);
			      		}
			      		else
			      		{
			      			$(row).find('td:eq(2)').attr('Invoice',0);
			      			$(row).find('td:eq(3)').attr('Invoice',data.Invoice);
			      		}
			      		
			      },
			      // 'order': [[1, 'asc']]
			});

			table.on( 'order.dt search.dt', function () {
			        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
			            cell.innerHTML = i+1;
			        } );
			} ).draw();
		})
	})
}

function load_budget_real_detail_byMonthYear__(Year,Month)
{
	var def = jQuery.Deferred();
	var url = base_url_js+"rest2/__load_budget_real_detail_byMonthYear";
	var data = {
			    Year : Year,
				Month : Month,
				auth : 's3Cr3T-G4N',
				ID_budget_left : ID_budget_left,
			};
	var token = jwt_encode(data,'UAP)(*');
	$.post(url,{token:token},function (resultJson) {
		
	}).done(function(resultJson) {
		def.resolve(resultJson);
	}).fail(function() {
	  toastr.info('No Result Data'); 
	  def.reject();
	}).always(function() {
	                
	});
	return def.promise();
}


</script>