let total_invoice = 0;
let left_payment = -1;
let data_get = [];
let have_pay = -1;

function loadSelectOptionSemesterByload(element,selected) {

    var token = jwt_encode({action:'read'},'UAP)(*');
    var url = base_url_js+'api/__crudTahunAkademik';
    $.post(url,{token:token},function (jsonResult) {

       if(jsonResult.length>0){
           for(var i=0;i<jsonResult.length;i++){
               var dt = jsonResult[i];
               var sc = (selected==dt.Status) ? 'selected' : '';
               // var v = (option=="Name") ? dt.Name : dt.ID;
               $(element).append('<option value="'+dt.ID+'.'+dt.Name+'" '+sc+'>'+dt.Name+'</option>');
           }
       }
    });

}

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
	if (NIM != '') {
			var Semester = $('#selectSemester').val();
			Semester = Semester.split('.');
			Semester = Semester[0];
			const url = base_url_js+'finance/get_created_tagihan_mhs/1';
			const data_post = {
		            ta : '',
		            prodi : '',
		            PTID  : '',
		            NIM : NIM,
		            StatusPayment : '',
		            Semester : Semester,
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
	else
	{
		toastr.info('Input NPM');
	}
	
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
			 // inputCHK = ''; 
			 inputCHK = '<input type="checkbox" class="uniform" value ="'+data_get[i]['NPM']+'" Prodi = "'+data_get[i]['ProdiEng']+'" Nama ="'+data_get[i]['Nama']+'" semester = "'+data_get[i]['SemesterID']+'" ta = "'+data_get[i]['Year']+'" invoice = "'+data_get[i]['InvoicePayment']+'" discount = "'+data_get[i]['Discount']+'" PTID = "'+data_get[i]['PTID']+'" PTName = "'+data_get[i]['PTIDDesc']+'" PaymentID = "'+data_get[i]['PaymentID']+'" Status = "'+ccc+'">'; 
			} 

			// if(data_get[i]['StatusPayment'] == 0){
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
			// }
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

	// console.log(data_choose);

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
			 	detail_new_pay += '<li>'+formatRupiah(payment_student_details[j].Pay)+ ' &nbsp | <label>Tgl Bayar</label> : <span style = "color :green;">'+payment_student_details[j].Pay_Date+'</span></li>';
			 }

			 html_pay = '<a data-toggle="collapse" href="#detail-payment-list_'+i+'" aria-expanded="false">'+
							html_pay+ ' | detail bayar : '+ 
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
					   '</div>';

	let html_set_pay = '<div class = "form-group">'+
							'<label class="col-xs-2 control-label">Nominal</label><div class="col-xs-6"><input type="text" id = "cost-payment" value = "" class = "form-control costInput"><br>Tanggal Bayar<div id="datetimepicker-payment" class="input-group input-append date datetimepicker">'+
							            '<input data-format="yyyy-MM-dd hh:mm:ss" class="form-control" id="datetime_deadline-payment" type="text"></input>'+
							            '<span class="input-group-addon add-on">'+
							              '<i data-time-icon="icon-time" data-date-icon="icon-calendar">'+
							              '</i>'+
							            '</span>'+
							        '</div></div>'+
					   '</div>';

	let htmlBtn = '<div class = "row">'+
				'<div class = "col-md-12">'+
					'<div style = "padding:10px;">'+
						'<button class="btn btn-block btn-success" id="btnSubmitBayar">Submit</button>'+
					'</div>'+
				'</div>'+
			'</div>';

	if (parseInt(left_payment) <= 0) {
		html_set_pay = '<h2 style = "text-align:center;color:red;">Data telah lunas</h2>';
		htmlBtn = '';
	}

	// for Verify Bukti Bayar
	    var htmlPaymentProof = '<div class = "row" style = "margin-bottom : 10px;">'+
	                              '<div class = "col-md-12">'+
	                              	  '<div class = "well" style = "padding:25px;">'+
		                                  '<h5>List Bukti Bayar</h5>'+
		                                    '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
		                                      '<thead>'+
		                                        '<tr>'+
		                                           '<th style="width: 5px;">No</th>'+
		                                           '<th style="width: 55px;">File</th>'+
		                                           '<th style="width: 55px;">Money Paid</th>'+
		                                           '<th style="width: 55px;">Account Name</th>'+
		                                           '<th style="width: 55px;">Account Owner</th>'+
		                                           '<th style="width: 55px;">Transaction Date</th>'+
		                                           '<th style="width: 55px;">Upload Date</th>'+
		                                           '<th style="width: 55px;">Bank</th>'+
		                                           '<th style="width: 55px;">Status</th>'+
		                                           '<th style="width: 55px;">Action</th>'+
		                                        '</tr>'+
		                                      '</thead>'+
		                                    '<tbody>';
	  var payment_proof = data_choose.payment_proof;
	  if (payment_proof.length > 0) {
	    for (var i = 0; i < payment_proof.length; i++) {
	      var FileUpload = jQuery.parseJSON(payment_proof[i]['FileUpload']);
	      var FileAhref = '';

	      switch(payment_proof[i].VerifyFinance) {
	        case '1':
	          var VerifyFinance = '<span style="color: green;"><i class="fa fa-check-circle margin-right"></i> Verify</span>';
	          break;
	        case '2':
	          var VerifyFinance  = '<span style="color: red;"><i class="fa fa-remove margin-right"></i>Reject</span>';
	          break;
	        default:
	          var VerifyFinance  = '<span style="color: green;"><i class="fa fa-info-circle margin-right"></i>Not Yet Verify</span>';
	      }

	      for (var j = 0; j < FileUpload.length; j++) {
	        FileAhref = '<a href ="'+base_url_js+'fileGetAny/document-'+data_choose.NPM+'-'+FileUpload[j].Filename+'" target="_blank">File '+ ((i+1)+j)+'</a>';
	      }

	      var btnVerify = (payment_proof[i]['VerifyFinance'] == 1)? '' : '<button class = "verify" idtable = "'+payment_proof[i]['ID']+'">Verify</button><div style = "margin-top : 10px"><button class = "rejectverify" idtable = "'+payment_proof[i]['ID']+'">Reject</button></div>';

	      htmlPaymentProof += '<tr>'+
	                              '<td>'+(i+1)+'</td>'+
	                              '<td>'+FileAhref+'</td>'+
	                              '<td>'+formatRupiah(payment_proof[i]['Money'])+'</td>'+
	                              '<td>'+payment_proof[i]['NoRek']+'</td>'+
	                              '<td>'+payment_proof[i]['AccountOwner']+'</td>'+
	                              '<td>'+payment_proof[i]['Date_transaction']+'</td>'+
	                              '<td>'+payment_proof[i]['Date_upload']+'</td>'+
	                              '<td>'+payment_proof[i]['NmBank']+'</td>'+
	                              '<td>'+VerifyFinance+'</td>'+
	                              '<td>'+btnVerify+'</td>'+
	                          '</tr>';
	    }

	    htmlPaymentProof += '</tbody></table></div></div></div>';

	  }
	  else
	  {
	  	htmlPaymentProof += '<tr><td colspan ="10">Data tidak ada </td></tr>';
	  }

	// end Verify Bukti Bayar

	sel.html(
			'<hr/>'+htmlPaymentProof+
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
				'<div class = "col-md-6">'+
					'<div class="panel panel-default">'+
						'<div class="panel-heading">'+
							'<h5 class="panel-title">'+
							'Set Input Bayar'+
							'</h5>'+
						'</div>'+
						'<div class="panel-body">'+
						html_set_pay+
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>'+htmlBtn
		)

	$('.costInput').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
	$('.costInput').maskMoney('mask', '9894');

	$('#datetimepicker-payment').datetimepicker({
	 // startDate: today,
	 // startDate: '+2d',
	 //startDate: date.addDays(i),
	 format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
	});

	$('#datetime_deadline-payment').prop('readonly',true);
}

const reload_Data = async() => {
	default_var();
	const paymentid = $('#dataRow').find('.uniform:checked').attr('paymentid');
	const vv = $('#NIM').val();
	await key_press_nim(vv);

	$('#dataRow').find('.uniform[paymentid="'+paymentid+'"]').prop('checked', true);

	create_html_pay(paymentid);

	$('a[href="#content-invoice-data"]').trigger('click');
	//$('#content-invoice-data').find('ol li:last-child').find('a').trigger('click');
}

const sbmt_payment = async(selector) => {

	if (confirm('are you sure ?')) {
		const paymentid = $('#dataRow').find('.uniform:checked').attr('paymentid');
		var Pay = findAndReplace($('#cost-payment').val(), ".",""); 
		const Pay_Date = $('#datetime_deadline-payment').val();

		loading_button2(selector);
		const url = base_url_js + 'page/finance/c_finance/bayar_kuliah';
		const data = {
			paymentid : paymentid,
			Pay : Pay,
			Pay_Date  : Pay_Date,
		};

		var token = jwt_encode(data,'UAP)(*');
		try{
			const response = await AjaxSubmitFormPromises(url,token);
			
			if (response['status'] == 1) {
				toastr.success('Saved');
			}
			else
			{
				toastr.info(response.msg);
			}
			
			if (response['reload'] == 1) {
				reload_Data();
			}
		}	
		catch(err){	
			console.log(err);
			toastr.error('something wrong, please contact it');
		}

		end_loading_button2(selector,'Submit');
	}

}

$(document).ready(function(e){
	loadSelectOptionSemesterByload('#selectSemester',1);
})

$(document).on('change','#selectSemester',function(e){
	const vv = $('#NIM').val();
	key_press_nim(vv);
})

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

$(document).on('click','#btnSubmitBayar',function(e){
	const itsme = $(this);
	sbmt_payment(itsme);
})

$(document).on('click','.verify', function () {
  if (confirm('Are you sure')) {
    var s = $(this);
    var idtable = $(this).attr('idtable');
    loading_button('.verify[idtable="'+idtable+'"]');
    var url = base_url_js+'finance/verify_bukti_bayar';
    var data = {
        idtable : idtable,
    };
    var token = jwt_encode(data,'UAP)(*');
    $.post(url,{token:token},function (resultJson) {
       // var resultJson = jQuery.parseJSON(resultJson);
       var fillitem = s.closest('tr');
       fillitem.find('td:eq(8)').html('<span style="color: green;"><i class="fa fa-check-circle margin-right"></i> Verify</span>');
       s.remove();
       toastr.success('The data has been verified');
    }).fail(function() {
      toastr.info('No Action...');
    }).always(function() {

    });
  }


});

$(document).on('click','.rejectverify', function () {
  var idtable = $(this).attr('idtable');
  var s = $(this);
  var tditem = $(this).closest('td');
  tditem.attr('style','width : 350px;');
  tditem.append('<div style = "margin-top : 10px"><input type = "text" class = "form-control" placeholder = "Input Reason" id = "reason'+idtable+'" ></div>');
  tditem.append('<div class = "row" style = "margin-top : 10px"><div class = "col-xs-12"><button class = "btn btn-success saverejectverify" idtable = "'+idtable+'">Save</button></div></div>');
  s.remove();
  $(".saverejectverify").click(function(){
    if (confirm('Are you sure')) {
      var idtable = $(this).attr('idtable');
      var s = $(this);
      var ReasonCancel = $("#reason"+idtable).val();
      loading_button('.saverejectverify[idtable="'+idtable+'"]');
      var url = base_url_js+'finance/reject_bukti_bayar';
      var data = {
          idtable : idtable,
          ReasonCancel : ReasonCancel,
      };
      var token = jwt_encode(data,'UAP)(*');
      $.post(url,{token:token},function (resultJson) {
         // var resultJson = jQuery.parseJSON(resultJson);
         var fillitem = s.closest('tr');
         fillitem.find('td:eq(8)').html('<span style="color: red;"><i class="fa fa-remove margin-right"></i>Reject</span>');
         toastr.success('The data has been verified');
         s.remove();
         $("#reason"+idtable).remove();
         loadData(1);
      }).fail(function() {
        toastr.info('No Action...');
        // toastr.error('The Database connection error, please try again', 'Failed!!');
      }).always(function() {

      });
    }
  })
});

