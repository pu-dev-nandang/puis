let total_invoice = 0;
let left_payment = -1;
let data_get = [];
let have_pay = -1;

const default_var = () => {
	total_invoice = -1;
	left_payment = -1;
	have_pay = -1;
	let new_arr = [];
	data_get = new_arr.slice(0);
}

const load_default = () => {
	default_var();
}

const key_press_nim = async(NIM) => {
	const url = base_url_js+'finance/get_created_tagihan_mhs/1';
	const data_post = {
            ta : '',
            prodi : '',
            PTID  : '',
            NIM : NIM,
            StatusPayment : '0',
        };
    var token = jwt_encode(data_post,'UAP)(*');
    loadingStart();
    try{
    	const response = await AjaxSubmitFormPromises(url,token);
    	create_html_choose_payment(response.loadtable);
    }
    catch(err){
    	toastr.info('No Result Data'); 
    }

    loadingEnd(1000);
}

const create_html_choose_payment = (data_db) => {
	if (! $('.panel-body').find('.choose_content').length) {
		$('.panel-body').append(
				'<div class = "choose_content" >'+
					'<div class="row" style="margin-top: 10px">'+
						'<div class="col-md-12">'+
							'<table class="table table-bordered datatable2" id = "datatable2">'+
								'<thead>'+
									'<tr style="background: #333;color: #fff;">'+
										'<th style="width: 1%;">Choose</th>'+
										'<th style="width: 12%;">Program Study</th>'+
										'<th style="width: 20%;">Nama,NPM &  VA</th>'+
										'<th style="width: 15%;">Payment Type</th>'+
										'<th style="width: 15%;">Email PU</th>'+
										'<th style="width: 15%;">IPS</th>'+
										'<th style="width: 15%;">IPK</th>'+
										'<th style="width: 10%;">Discount</th>'+
										'<th style="width: 10%;">Invoice</th>'+
										'<th style="width: 10%;">Status</th>'+
									'</tr>'+
								'</thead>'+
								'<tbody id="dataRow"></tbody>'+
							'</table>'+
						'</div>'+
					'</div>'+
				'</div>'
			)
	}

	$('#dataRow').empty();
	$('.panel-body').find('.payment_content').remove();

	if (data_db.length > 0) {
		data_get = data_db.slice(0);
		for(var i=0;i<data_get.length;i++){
			var ccc = 0;
			var yy = (data_get[i]['InvoicePayment'] != '') ? formatRupiah(data_get[i]['InvoicePayment']) : '-';
			var status = '';
			if(data_get[i]['StatusPayment'] == 0)
			{
			  status = 'Belum Approve <br> Belum Lunas';
			}
			else
			{
			  status = 'Approve';
			  // check lunas atau tidak
			    // count jumlah pembayaran dengan status 1
			    var b = 0;
			    for (var j = 0; j < data_get[i]['DetailPayment'].length; j++) {
			      var a = data_get[i]['DetailPayment'][j]['Status'];
			      if(a== 1)
			      {
			        b = parseInt(b) + parseInt(data_get[i]['DetailPayment'][j]['Invoice']);
			      }
			    }

			    if(b < data_get[i]['InvoicePayment'])
			    {
			      status += '<br> Belum Lunas';
			      ccc = 1;
			    }
			    else
			    {
			      status += '<br> Lunas';
			      ccc = 2
			    }
			}

			var tr = '<tr NPM = "'+data_get[i]['NPM']+'">';
			var inputCHK = ''; 
			if (ccc == 0) {
			 tr = '<tr NPM = "'+data_get[i]['NPM']+'">';
			 inputCHK = '<input type="checkbox" class="uniform" value ="'+data_get[i]['NPM']+'" Prodi = "'+data_get[i]['ProdiEng']+'" Nama ="'+data_get[i]['Nama']+'" semester = "'+data_get[i]['SemesterID']+'" ta = "'+data_get[i]['Year']+'" invoice = "'+data_get[i]['InvoicePayment']+'" discount = "'+data_get[i]['Discount']+'" PTID = "'+data_get[i]['PTID']+'" PTName = "'+data_get[i]['PTIDDesc']+'" PaymentID = "'+data_get[i]['PaymentID']+'" Status = "'+ccc+'">';
			} else if(ccc == 1) {
			   tr = '<tr style="background-color: #eade8e; color: black;" NPM = "'+data_get[i]['NPM']+'">';
			   inputCHK = '<input type="checkbox" class="uniform" value ="'+data_get[i]['NPM']+'" Prodi = "'+data_get[i]['ProdiEng']+'" Nama ="'+data_get[i]['Nama']+'" semester = "'+data_get[i]['SemesterID']+'" ta = "'+data_get[i]['Year']+'" invoice = "'+data_get[i]['InvoicePayment']+'" discount = "'+data_get[i]['Discount']+'" PTID = "'+data_get[i]['PTID']+'" PTName = "'+data_get[i]['PTIDDesc']+'" PaymentID = "'+data_get[i]['PaymentID']+'" Status = "'+ccc+'">'; 
			}
			else
			{
			 tr = '<tr style="background-color: #8ED6EA; color: black;" NPM = "'+data_get[i]['NPM']+'">';
			 inputCHK = ''; 
			} 

			if(data_get[i]['StatusPayment'] == 0){
				var bintang = setBintangFinance(data_get[i]['Pay_Cond']);
				$('#dataRow').append(tr +
				                       '<td>'+inputCHK+'</td>' +
				                       '<td>'+data_get[i]['ProdiEng']+'<br>'+data_get[i]['SemesterName']+'</td>' +
				                       '<td>'+bintang+'<br/>'+data_get[i]['Nama']+'<br>'+data_get[i]['NPM']+'<br>'+data_get[i]['VA']+'</td>' +
				                       '<td>'+data_get[i]['PTIDDesc']+'</td>' +
				                       '<td>'+data_get[i]['EmailPU']+'</td>' +
				                       '<td>'+getCustomtoFixed(data_get[i]['IPS'],2)+'</td>' +
				                       '<td>'+getCustomtoFixed(data_get[i]['IPK'],2)+'</td>' +
				                       '<td>'+data_get[i]['Discount']+'%</td>' +
				                       '<td>'+yy+'</td>' +
				                       '<td>'+status+'</td>' +
				                       '</tr>');
			}
		}
	}
	else{
		if (data_db.length == 0) {
		  toastr.info('No result data');
		}
		else
		{
		  toastr.error('Error', 'Failed!!');
		}
	}
}

const count_left_paymet = (data_choose) => {
	left_payment = 0;
	total_invoice = 0;

	if (!jQuery.isEmptyObject(data_choose)) {
		total_invoice = parseInt(data_choose.InvoicePayment);
		let DetailPayment = data_choose.DetailPayment;
		let byr = 0;
		for (var i = 0; i < DetailPayment.length; i++) {
			if (DetailPayment[i].Status == 1 || DetailPayment[i].Status == '1') {
				byr += parseInt(DetailPayment[i].Invoice);
			}
			else
			{
				let payment_student_details = DetailPayment[i].payment_student_details;
				if (payment_student_details.length > 0) {
					for (var j = 0; j < payment_student_details.length; j++) {
							byr += parseInt(payment_student_details[j].Pay);
					}
				}
				
			}
		}

		have_pay = byr;
		left_payment = Math.abs(have_pay - total_invoice);
	}
}

const create_html_pay = (PaymentID) => {
	let data_choose = data_get.find(x => x.PaymentID === PaymentID);
	count_left_paymet(data_choose);

	if (!$('.panel-body').find('.payment_content').length) {
		$('.panel-body').append(
				'<div class = "payment_content"></div>'
			);
	}

	console.log(data_choose);

	const sel = $('.panel-body').find('.payment_content');

	let DetailPayment = data_choose.DetailPayment;

	let html_detail_payment = '';

	for (var i = 0; i < DetailPayment.length; i++) {
		let html_lunas_chk = (DetailPayment[i].Status == 1 || DetailPayment[i].Status == '1' ) ? '<i class="fa fa-check-circle" style="color: green;"></i>' : '<i class="fa fa-minus-circle" style="color: red;"></i>';

		let html_pay = '<span>Invoice : '+formatRupiah(DetailPayment[i].Invoice)+'</span> &nbsp'+html_lunas_chk;

		let detail_new_pay = '';
		let payment_student_details = DetailPayment[i].payment_student_details;
		if (payment_student_details.length > 0) {
			 detail_new_pay += '<ol>';
			 for (var j = 0; j < payment_student_details.length; j++) {
			 	detail_new_pay += '<li>'+formatRupiah(payment_student_details[j].Pay)+ ' : <span style = "color :green;">'+payment_student_details[j].Pay_Date+'</span></li>';
			 }

			 html_pay = '<a data-toggle="collapse" href="#detail-payment-list_'+i+'" aria-expanded="false">'+
							html_pay+
							'</a>'+
							'<div id = "detail-payment-list_'+i+'" class="panel-collapse collapse">'+
								detail_new_pay+
							'</div>';
		}
		else
		{
			if (DetailPayment[i].DatePayment != null) {
				html_pay += ' | <span style = "color :green;">Tgl Bayar : '+DetailPayment[i].DatePayment+'</span>';
			}
			
		}

		html_detail_payment += '<li style = "font-weight: bold;">'+html_pay+'</li>';
	}

	let html_invoice = '<div class = "row">'+
							'<div class = "col-md-12">'+
								'<ol><label>Cicilan : </label>'+
										html_detail_payment+
								'</ol>'+									
							'</div>'+
					   '</div>'

	sel.html(
			'<div class = "row">'+
				'<div class = "col-md-6">'+
					'<div class="panel panel-default">'+
						'<div class="panel-heading" role="tab" id="content-invoice">'+
							'<h5 class="panel-title">'+
							'<a data-toggle="collapse" href="#content-invoice-data" aria-expanded="false" aria-controls="answerOne">'+
							'<span style = "color:blue;">Total Bayar : '+formatRupiah(have_pay)+' | '+'Invoice : '+formatRupiah(data_choose.InvoicePayment)+' | Sisa : '+formatRupiah(left_payment)+' </span>'+
							'</a>'+
							'</h5>'+
						'</div>'+
						'<div id="content-invoice-data" class="panel-collapse collapse" role="tabpanel" >'+
							'<div class="panel-body">'+
							html_invoice+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>'
		)


}


$(document).on('keypress','#NIM', function (event)
{
    if (event.keyCode == 10 || event.keyCode == 13) {
      valuee = $(this).val();
      key_press_nim(valuee);
    }
}); // exit enter

$(document).on('click','#idbtn-cari',function(e){
	const vv = $('#NIM').val();
	key_press_nim(vv);
})

$(document).on('click','.uniform',function(e){
	$('input.uniform').prop('checked', false);
	$(this).prop('checked',true);
	const PaymentID = $(this).attr('paymentid');
	create_html_pay(PaymentID);
})


