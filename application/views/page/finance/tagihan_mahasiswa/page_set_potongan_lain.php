<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<style type="text/css">
  .btn-submit{
    background-color: #1ace37;
  }
</style>
<div class="row">
    <div class="col-xs-12" >
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Set Potongan Lain</h4>
            </div>
            <div class="panel-body">
               <div class="row" style="margin-top: 30px;">
                   <div class="col-md-3">
                       <div class="thumbnail" style="min-height: 30px;padding: 10px;">
                           <input type="text" name="" class="form-control" placeholder="Input NPM Mahasiswa" id = "NIM">
                       </div>
                   </div>
               </div>
               <br>
               <div class="row">
                 <div class="col-md-12" align="right">
                   <button type="button" class="btn btn-default" id = 'idbtn-cari'><span class="glyphicon glyphicon-search"></span> Cari</button>
                 </div>
               </div>
               <div class="row" style="margin-top: 10px">
                   <div class="col-md-12">
                       <table class="table table-bordered datatable2 hide" id = "datatable2">
                           <thead>
                           <tr style="background: #333;color: #fff;">
                               <th style="width: 1%;">Choose</th>
                               <th style="width: 12%;">Program Study</th>
                               <!-- <th style="width: 10%;">Semester</th> -->
                               <th style="width: 20%;">Nama,NPM &  VA</th>
                               <!-- <th style="width: 5%;">NPM</th> -->
                               <!-- <th style="width: 5%;">Year</th> -->
                               <th style="width: 15%;">Payment Type</th>
                               <th style="width: 15%;">Email PU</th>
                               <th style="width: 15%;">IPS</th>
                               <th style="width: 15%;">IPK</th>
                               <th style="width: 10%;">Discount</th>
                               <th style="width: 10%;">Invoice</th>
                               <th style="width: 10%;">Status</th>
                               <th style="width: 10%;">Detail Payment</th>
                           </tr>
                           </thead>
                           <tbody id="dataRow"></tbody>
                       </table>
                       <hr>
                       <div id = "inputPotongan" class="hide">
                         <div class="widget box">
                             <div class="widget-header">
                                 <h4 class="header"><i class="icon-reorder"></i></h4>
                             </div>
                             <div class="widget-content">
                                 <!--  -->
                                  
                                 <!-- end widget -->
                             </div>
                             <hr/>
                         </div>
                       </div>
                   </div>
               </div>
            </div>
        </div>
    </div>
</div>




<script>
    window.dataa = '';
    window.dataaModal = '';
    window.get_Invoice = '';
    var checkApprove = 0;
    $(document).ready(function () {
        
    });

    $(document).on('keypress','#NIM', function (event)
    {

        if (event.keyCode == 10 || event.keyCode == 13) {
          valuee = $(this).val();
          loadData(1,valuee);
        }
    }); // exit enter

    $(document).on('click','#idbtn-cari', function () {
        var NPM = $("#NIM").val();
        result = Validation_required(NPM,'NPM');
        if (result['status'] == 0) {
          toastr.error(result['messages'], 'Failed!!');
        }
        else
        {
          loadData(1,NPM);
        }
    });

    function loadData(page,NPM) {
        var NIM = NPM;
        $(".widget-content").empty();
        $("#inputPotongan").addClass('hide');
        $('#datatable2').addClass('hide');

        $('#NotificationModal .modal-header').addClass('hide');
            $('#NotificationModal .modal-body').html('<center>' +
                '                    <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>' +
                '                    <br/>' +
                '                    Loading Data . . .' +
                '                </center>');
            $('#NotificationModal .modal-footer').addClass('hide');
            $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
            });
            $('#dataRow').html('');
            var url = base_url_js+'finance/get_created_tagihan_mhs_not_approved/'+page;
            var data = {
                ta : '',
                prodi : '',
                PTID  : '',
                NIM : NIM,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
               var resultJson = jQuery.parseJSON(resultJson);
               console.log(resultJson);
                var Data_mhs = resultJson.loadtable;
                dataaModal = Data_mhs;
                if (Data_mhs.length > 0) {
                    for(var i=0;i<Data_mhs.length;i++){
                         var ccc = 0;
                         var yy = (Data_mhs[i]['InvoicePayment'] != '') ? formatRupiah(Data_mhs[i]['InvoicePayment']) : '-';
                         // get_Invoice = Data_mhs[i]['InvoicePayment'];
                         //  var n = get_Invoice.indexOf(".");
                         // get_Invoice = get_Invoice.substring(0, n);
                         // dataa = {ID : Data_mhs[i]['PaymentID'],PTID : Data_mhs[i]['PTID'],SemesterID : Data_mhs[i]['SemesterID']};
                         // proses status
                         var status = '';
                         if(Data_mhs[i]['StatusPayment'] == 0)
                         {
                           status = 'Belum Approve <br> Belum Lunas';
                         }
                         else
                         {
                           status = 'Approve';
                           // check lunas atau tidak
                             // count jumlah pembayaran dengan status 1
                             var b = 0;
                             for (var j = 0; j < Data_mhs[i]['DetailPayment'].length; j++) {
                               var a = Data_mhs[i]['DetailPayment'][j]['Status'];
                               if(a== 1)
                               {
                                 b = parseInt(b) + parseInt(Data_mhs[i]['DetailPayment'][j]['Invoice']);
                               }
                             }

                             // console.log('b : '+b+ '  ..InvoicePayment : ' + Data_mhs[i]['InvoicePayment']);
                             if(b < Data_mhs[i]['InvoicePayment'])
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

                        var tr = '<tr NPM = "'+Data_mhs[i]['NPM']+'">';
                        var inputCHK = ''; 
                        if (ccc == 0) {
                         tr = '<tr NPM = "'+Data_mhs[i]['NPM']+'">';
                         inputCHK = '<input type="checkbox" class="uniform" value ="'+Data_mhs[i]['NPM']+'" Prodi = "'+Data_mhs[i]['ProdiEng']+'" Nama ="'+Data_mhs[i]['Nama']+'" semester = "'+Data_mhs[i]['SemesterID']+'" ta = "'+Data_mhs[i]['Year']+'" invoice = "'+Data_mhs[i]['InvoicePayment']+'" discount = "'+Data_mhs[i]['Discount']+'" PTID = "'+Data_mhs[i]['PTID']+'" PTName = "'+Data_mhs[i]['PTIDDesc']+'" PaymentID = "'+Data_mhs[i]['PaymentID']+'" Status = "'+ccc+'">'; 
                        } else if(ccc == 1) {
                           tr = '<tr style="background-color: #eade8e; color: black;" NPM = "'+Data_mhs[i]['NPM']+'">';
                           inputCHK = '<input type="checkbox" class="uniform" value ="'+Data_mhs[i]['NPM']+'" Prodi = "'+Data_mhs[i]['ProdiEng']+'" Nama ="'+Data_mhs[i]['Nama']+'" semester = "'+Data_mhs[i]['SemesterID']+'" ta = "'+Data_mhs[i]['Year']+'" invoice = "'+Data_mhs[i]['InvoicePayment']+'" discount = "'+Data_mhs[i]['Discount']+'" PTID = "'+Data_mhs[i]['PTID']+'" PTName = "'+Data_mhs[i]['PTIDDesc']+'" PaymentID = "'+Data_mhs[i]['PaymentID']+'" Status = "'+ccc+'">'; 
                        }
                        else
                        {
                         tr = '<tr style="background-color: #8ED6EA; color: black;" NPM = "'+Data_mhs[i]['NPM']+'">';
                         inputCHK = ''; 
                        }

                        if(Data_mhs[i]['StatusPayment'] == 0) // menandakan belum approve
                         {
                           // show bintang
                           var bintang = (Data_mhs[i]['Pay_Cond'] == 1) ? '<p style="color: red;">*</p>' : '<p style="color: red;">**</p>';
                           if (Data_mhs[i]['DetailPayment'].length == 1) { // menandakan untuk setting cicilan maka harus memiliki satu detail payment
                               $('#dataRow').append(tr +
                                                      '<td>'+inputCHK+'</td>' +
                                                      '<td>'+Data_mhs[i]['ProdiEng']+'<br>'+Data_mhs[i]['SemesterName']+'</td>' +
                                                      // '<td>'+Data_mhs[i]['SemesterName']+'</td>' +
                                                      '<td>'+bintang+Data_mhs[i]['Nama']+'<br>'+Data_mhs[i]['NPM']+'<br>'+Data_mhs[i]['VA']+'</td>' +
                                                      // '<td>'+Data_mhs[i]['NPM']+'</td>' +
                                                      // '<td>'+Data_mhs[i]['Year']+'</td>' +
                                                      '<td>'+Data_mhs[i]['PTIDDesc']+'</td>' +
                                                      '<td>'+Data_mhs[i]['EmailPU']+'</td>' +
                                                      '<td>'+getCustomtoFixed(Data_mhs[i]['IPS'],2)+'</td>' +
                                                      '<td>'+getCustomtoFixed(Data_mhs[i]['IPK'],2)+'</td>' +
                                                      '<td>'+Data_mhs[i]['Discount']+'%</td>' +
                                                      '<td>'+yy+'</td>' +
                                                      '<td>'+status+'</td>' +
                                                      '<td>'+'<button class = "DetailPayment" NPM = "'+Data_mhs[i]['NPM']+'">View</button>'+'</td>' +
                                                      '</tr>');
                            }
                         } 
                        
                    }

                    if(Data_mhs.length > 0)
                    {
                      checkApprove = 1;
                       $('#datatable2').removeClass('hide');
                        
                    }
                } else {
                  if (Data_mhs.length == 0) {
                    toastr.info('No result data');
                  }
                  else
                  {
                    toastr.error('Error', 'Failed!!');
                  }
                }
            }).fail(function() {
              
              toastr.info('No Result Data'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {
                $('#NotificationModal').modal('hide');
            });
    }

    function ajax_data_deadline_semester_antara(SemesterID)
    {
      var def = jQuery.Deferred();
      var data = {
          SemesterID : SemesterID,
          auth : 's3Cr3T-G4N',
      };
      var token = jwt_encode(data,"UAP)(*");
      var url = base_url_js+'rest/__cek_deadline_payment_semester_antara';
      $.post(url,{ token:token },function () {

      }).done(function(data_json) {
        def.resolve(data_json);
      }).fail(function() {
          def.reject();
      });
      return def.promise();
    }

    const htmlPotonganDynamic = (withButton = 0) => {
    	let htmlViewSelector = $('#contentPotongan');
    	let htmlScript = '<div class = "row"><div class = "col-xs-3">'+
    						'<div class = "form-group">'+
    							'<label>DiscountName</label>'+
    							'<input type="text"  class = "form-control frmInput" name = "DiscountName" rule = "required">'+
    						'</div>'+	
    					 '</div>'+
    					 '<div class = "col-xs-3">'+
    						'<div class = "form-group">'+
    							'<label>Value</label>'+
    							'<input type="text"  class = "form-control frmInput" name = "DiscountValue" rule = "required">'+
    						'</div>'+	
    					 '</div>'+
    					 '<div class = "col-xs-4">'+
    						'<div class = "form-group">'+
    							'<label>Description</label>'+
    							'<textarea class = "form-control frmInput" name = "Description" rule = ""></textarea>'+
    						'</div>'+
    					 '</div>'+
    					 (
    						(withButton == 1) ? '<div class = "col-xs-2"><button class = "btn btn-danger deleteInputPotongan" style = "margin-top:25px;">Delete</button>' : ''	) + '</div>'+

    				'</div>';
    	return htmlScript;				 

    }

    const viewPotonganInput = (NPM,PTID,SemesterID) => {
    	const htmlViewSelector = $('#inputPotongan').find('.widget-content');
    	let htmlScript = '<div id  = "contentPotongan" npm = "'+NPM+'" ptid = "'+PTID+'" SemesterID = "'+SemesterID+'" style = "margin:10px;">'+ 
    						'<div style = "margin-bottom:10px;">'+
    							'<button class = "btn btn-default" id = "addPotongan">Add</button>'+
    						'</div>'+
    							
    					 '<div>';
    	htmlViewSelector.html(htmlScript);
    	$("#inputPotongan").removeClass('hide');
    	$('#contentPotongan').append(htmlPotonganDynamic());
    	$('.frmInput[name="DiscountValue"]').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
    	$('.frmInput[name="DiscountValue"]').maskMoney('mask', '9894');
    	$('.frmInput[name="DiscountName"]').focus();
    	$('#inputPotongan').find('hr:last').after('<div style = "padding : 10px;text-align:right;"><span id = "viewTotal" style = "color:green;">Total : Rp.0 </span> <button class = "btn btn-primary" id= "btnSavePotongan" style = "margin-left:10px;">Save</button></div>');
    }

    $(document).on('click','.deleteInputPotongan',function(e){
    	$(this).closest('.row').remove();
    	countDiscountValue();
    })

    $(document).on('click','#addPotongan',function(e){
    	$('#contentPotongan').append(htmlPotonganDynamic(1));
    	$('.frmInput[name="DiscountValue"]').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
    	$('.frmInput[name="DiscountValue"]').maskMoney('mask', '9894');
    	$('.frmInput[name="DiscountName"]:last').focus();
    })

    $(document).on('click','.uniform', function () {
      $('input.uniform').prop('checked', false);
      $(this).prop('checked',true);
      var PTID = $(this).attr('ptid');
      var SemesterID = $(this).attr('semester');
      var NPM = $(this).val();
      viewPotonganInput(NPM,PTID,SemesterID);
      
    });

    const valueOnly = (valData) => {
    	let arr = valData.split('.');
    	let str = '';
    	for (var i = 0; i < arr.length; i++) {
    		str += arr[i];
    	}
    	return str;
    };

    const countDiscountValue = () => {
    	let total = 0;
    	$('.frmInput[name="DiscountValue"]').each(function(e){
    		total += parseInt(valueOnly($(this).val()));
    	})
    	$('#viewTotal').html(formatRupiah(total));
    }

    $(document).on('keyup','.frmInput[name="DiscountValue"]',function(e){
    	countDiscountValue();
    })

    $(document).on('click','#btnSavePotongan',async function(e){
    	const itsme = $(this);
    	let data = {};
    	let tempArr = [];
    	let obj = {};
    	let booleanCheck = true;
    	let x =1;
    	$('.frmInput').each(function(e){
    		const name = $(this).attr('name');
    		const rule = $(this).attr('rule');
    		const valueData = (name == 'DiscountValue') ? valueOnly($(this).val()) : $(this).val();
    		if (name == 'DiscountValue') {
    			if (parseInt(valueData) <= 0) {
    				booleanCheck = false;
    				return;
    			}
    		}
    		else if(name == 'Description'){
    			// no code
    		}
    		else
    		{
    		 	const check = Validation_required(valueData,'');
    		 	if(check['status'] == 0){
    		 		booleanCheck = false;
    				return;
    		 	}
    		}

    		if (x == 3) {
    			obj[name] = valueData;
    			tempArr.push(obj);
    			obj = {};
    			x = 0;

    		}
    		else
    		{
    			obj[name] = valueData;
    		}

    		x++;

    	})

    	if (!booleanCheck) {
    		toastr.info('All form are required and value must be more than 0')
    		return;
    	}

    	data = {
    		NPM : $('#contentPotongan').attr('npm'),
    		PTID : $('#contentPotongan').attr('ptid'),
    		SemesterID : $('#contentPotongan').attr('semesterid'),
    		data : tempArr
    	}

    	const url = base_url_js+'finance/tagihan-mhs/set-potongan-lain/submit';
    	var token = jwt_encode(data,"UAP)(*");	
    	loading_button2(itsme)
    	try{
    		const response = await AjaxSubmitFormPromises(url,token);
    		if (response.status != 1) {
    			toastr.error(response.msg);
    			end_loading_button2(itsme);
    		}
    		else
    		{
    			toastr.success('Saved');
    			location.reload();
    		}
    	}	
    	catch(err){
    		toastr.error('something wrong');
    		end_loading_button2(itsme);
    	}

    	

    })

    $(document).on('click','.DetailPayment', function () {
        var NPM = $(this).attr('NPM');
        var html = '';
        var table = '<div class = "row"><div class= col-md-12><table class="table table-striped table-bordered table-hover table-checkable tableData">'+
                      '<thead>'+
                          '<tr>'+
                              '<th style="width: 5px;">No</th>'+
                              '<th style="width: 55px;">Nama</th>'+
                              '<th style="width: 55px;">Invoice</th>'+
                              '<th style="width: 55px;">BilingID</th>'+
                              '<th style="width: 55px;">Status</th>'+
                              '<th style="width: 55px;">Deadline</th>'+
                              '<th style="width: 55px;">UpdateAt</th>';
        table += '</tr>' ;  
        table += '</thead>' ; 
        table += '<tbody>' ;
        var isi = '';
        // console.log(dataaModal);
        var CancelPayment = [];
        for (var i = 0; i < dataaModal.length; i++) {
          if(dataaModal[i]['NPM'] == NPM)
          {
            CancelPayment = dataaModal[i]['cancelPay'];
            var totCancelPayment = CancelPayment.length;
            var DetailPaymentArr = dataaModal[i]['DetailPayment'];
            var Nama = dataaModal[i]['Nama'];
            for (var j = 0; j < DetailPaymentArr.length; j++) {
              var yy = (DetailPaymentArr[j]['Invoice'] != '') ? formatRupiah(DetailPaymentArr[j]['Invoice']) : '-';
              var status = (DetailPaymentArr[j]['Status'] == 0) ? 'Belum Bayar' : 'Sudah Bayar';
              isi += '<tr>'+
                    '<td>'+ (j+1) + '</td>'+
                    '<td>'+ Nama + '</td>'+
                    '<td>'+ yy + '</td>'+
                    '<td>'+ DetailPaymentArr[j]['BilingID'] + '</td>'+
                    '<td>'+ status + '</td>'+
                    '<td>'+ DetailPaymentArr[j]['Deadline'] + '</td>'+
                    '<td>'+ DetailPaymentArr[j]['UpdateAt'] + '</td>'+
                  '<tr>'; 
            }
            break;
          }
        }

        table += isi+'</tbody>' ; 
        table += '</table></div></div>' ;
        html += table;

        var htmlReason = '<div class = "row"><div class= col-md-12><h5>List Cancel Payment</h5><table class="table table-striped table-bordered table-hover table-checkable tableData">'+
                      '<thead>'+
                          '<tr>'+
                              '<th style="width: 5px;">No</th>'+
                              '<th style="width: 55px;">Reason</th>'+
                              '<th style="width: 55px;">CancelAt</th>'+
                              '<th style="width: 55px;">CancelBy</th>';
        htmlReason += '</tr>' ;  
        htmlReason += '</thead>' ; 
        htmlReason += '<tbody>' ;
        for (var i = 0; i < CancelPayment.length; i++) {
          var No = parseInt(i) + 1;
          htmlReason += '<tr>'+
                '<td>'+ (i+1) + '</td>'+
                '<td>'+ CancelPayment[i]['Reason'] + '</td>'+
                '<td>'+ CancelPayment[i]['CancelAt'] + '</td>'+
                '<td>'+ CancelPayment[i]['Name'] + '</td>'+
              '<tr>'; 
        }

        htmlReason += '</tbody>' ; 
        htmlReason += '</table></div></div>' ;
        if (CancelPayment.length > 0) {
          html += htmlReason;
        }

        var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
            '';

        $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Detail Payment'+'</h4>');
        $('#GlobalModalLarge .modal-body').html(html);
        $('#GlobalModalLarge .modal-footer').html(footer);
        $('#GlobalModalLarge').modal({
            'show' : true,
            'backdrop' : 'static'
        });    

    });

</script>