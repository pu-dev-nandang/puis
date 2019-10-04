<div class="row">
    <div class="col-xs-12" >
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Pembayaran Formulir Online</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                	<div class="col-md-3">
                	    <div class="thumbnail" style="min-height: 80px;padding: 10px;">
                	    	Angkatan
                	        <select class="select2-select-00 col-xs-2 full-width-fix" id="selectTahun">
                                  <option></option>
                            </select>
                	    </div>
                	</div>
                </div>
                <div class="row" style="margin-top: 30px;">
                	<div class="col-md-12">
                		<div class="widget box">
                			<div class="widget-header">
                				<h4><i class="icon-reorder"></i>Belum Bayar Formulir</h4>
                				<div class="toolbar no-padding">
                					<!--<div class="btn-group">
                						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                					</div>-->
                				</div>
                			</div>
                			<div class="widget-content">
                				<div id="dataBelumBayar">

                				</div>
                			</div>
                			<div id = "tblResultCSV" class = "col-md-12 hide">
                				<table class="table table-striped table-bordered table-hover table-checkable datatable2">
                					<caption><strong>Hasil Pencarian Ke File CSV</strong></caption>
                					<thead>
                						<tr>
                							<th class="checkbox-column">
                								<input type="checkbox" class="uniform" value="nothing;nothing;nothing;nothing;nothing" id ="dataResultCheckAll">
                							</th>
                							<th class="hidden-xs">Nama</th>
                							<th>Email</th>
                							<th>Price Formulir</th>
                							<th>File Upload</th>
                							<th>Sekolah</th>
                              <th>Phone</th>
                							<th>Register At</th>
                							<th>Upload At</th>
                							<th>Total Searching</th>
                						</tr>
                					</thead>
                					<tbody>
                					</tbody>
                				</table>
                			</div>
                				<div class="col-xs-12" align = "right">
                				   <button class="btn btn-inverse btn-notification hide" id="btn-confirm">Confirm</button>
                				</div>
                				<br>
                		</div>
                	</div> <!-- /.col-md-6 -->
                </div>
                <hr>
                <div class="row" style="margin-top: 30px;">
                    <div class="col-md-12">
                        <div class="widget box">
                            <div class="widget-header">
                                <h4 class="header"><i class="icon-reorder"></i>Telah Bayar Formulir
                                </h4>
                            </div>
                            <div class="widget-content">
                                <!--  -->
                                <div id="dataRegTelahBayar">
                                </div>
                                <!-- -->
                            </div>
                            <hr/>
                            <div id="page">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
	window.dataGet; // deklarasi menampus file table
	//window.RegisterID; // deklarasi menampung file table
	//window.url_images = 'http://localhost/register/upload/';
	window.url_images = '<?php echo $this->GlobalVariableAdi['url_registration'] ?>'+'upload/';
	$(document).ready(function () {
		loadTahun();
	    loaddataBelumBayar();
	    loadDataTelahBayar();
	    // loadSelectbaris();
	});

	$(document).on('change','#selectTahun', function () {
	    loaddataBelumBayar();
	    loadDataTelahBayar();
	});

	function loadTahun()
	  {
	  	var academic_year_admission = "<?php echo $academic_year_admission ?>";
	      var thisYear = (new Date()).getFullYear();
          var startTahun = parseInt(thisYear);
          var selisih = (2018 < parseInt(thisYear)) ? parseInt(1) + (parseInt(thisYear) - parseInt(2018)) : 1;
          for (var i = 0; i <= selisih; i++) {
            var selected = (( parseInt(startTahun) + parseInt(i) )==academic_year_admission) ? 'selected' : '';
            $('#selectTahun').append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
          }

          $('#selectTahun').select2({
           // allowClear: true
          });

	      $('#selectStatus').select2({
	        // allowClear: true
	      });
	  }

	function loadSelectbaris()
	{
		// var thisYear = (new Date()).getFullYear();
		var angkaAwal = 20;
		var startAngka = 1;
		var selisih = parseInt(angkaAwal) - parseInt(startAngka);
		for (var i = 0; i <= selisih; i++) {
		    var selected = (i==5) ? 'selected' : '';
		    $('.angkaSelect').append('<option value="'+ ( parseInt(startAngka) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startAngka) + parseInt(i) )+'</option>');
		}

		 $("#kolomPrice option").filter(function() {
		   //may want to use $.trim in here
		   return $(this).val() == 4;
		 }).prop("selected", true);

		 $("#kolomDebit option").filter(function() {
		   //may want to use $.trim in here
		   return $(this).val() == 5;
		 }).prop("selected", true);


		 $("#kolomKredit option").filter(function() {
		   //may want to use $.trim in here
		   return $(this).val() == 5;
		 }).prop("selected", true);

		$('.angkaSelect').select2({
		  // allowClear: true
		});

	}

	function loadDataTelahBayar()
	{
		$("#dataRegTelahBayar").empty();
		loading_page('#dataRegTelahBayar');
		var url = base_url_js+'loadDataRegistrationTelahBayar';
		var data = {tahun : $("#selectTahun").val()};
		// console.log(data);
		$.post(url,data,function (data_json) {
		    setTimeout(function () {
		        $("#dataRegTelahBayar").html(data_json);
		    },500);
		});
	}

	function loaddataBelumBayar()
	{
		$("#dataBelumBayar").empty();
		loading_page('#dataBelumBayar');
		var url = base_url_js+'loadDataRegistrationBelumBayar';
		var data = {tahun : $("#selectTahun").val()};
		$.post(url,data,function (data_json) {
		    setTimeout(function () {
		        $("#dataBelumBayar").html(data_json);
		    },500);
		});
	}

	$(document).on('click','.btn_bayar', function () {
		var regid = $(this).attr('regid');
		// show modal verifikasi bayar
			var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
            '';
            var html = '<div class = "row">'+
            				'<div class = "col-md-12">'+
            					'<div id="datetimepicker1'+'" class="input-group input-append date datetimepicker"  style = "width : 210px;">'+
            					    '<input data-format="yyyy-MM-dd" class="form-control" id="tgl" type=" text" readonly="" value = "<?php echo date('Y-m-d') ?>">'+
            					    '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
            					'</div>'+
            				'</div>'+
            			'</div>'+
            			'<div class = "row" style = "margin-top : 10px"><div class = "col-xs-12"><button class = "btn btn-success savePay" regid = "'+regid+'">Save</button>'+
            			'</div></div>';
			$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Action'+'</h4>');
			$('#GlobalModalLarge .modal-body').html(html);
			$('#GlobalModalLarge .modal-footer').html(footer);
			$('#GlobalModalLarge').modal({
			    'show' : true,
			    'backdrop' : 'static'
			});

			$('#datetimepicker1').datetimepicker({
			  format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
			});
	});

	$(document).on('click','.savePay', function () {
		if (confirm('Are you sure you want to save this thing into the database?')) {
			var RegID = $(this).attr('RegID');
			var Year = $('#selectTahun option:selected').val();
			loading_button(".savePay[RegID='"+RegID+"']");
			var url = base_url_js+'finance/bayar_manual_mahasiswa_formulironline';
			var data = {
			    RegID : RegID,
			    Year : Year,
			    tgl : $('#tgl').val(),
			};
			var token = jwt_encode(data,'UAP)(*');
			$.post(url,{token:token},function (resultJson) {
				var rs = jQuery.parseJSON(resultJson);
				if (rs == 1) {
					loaddataBelumBayar();
					loadDataTelahBayar();
					$('#GlobalModalLarge').modal('hide');
				}
				else
				{
					toastr.info('Formulir Number Online atau Formulir Number Global tidak ada, Silahkan kontak pihak Admisi');
				}

			}).fail(function() {
			  toastr.info('No Action...');
			  // toastr.error('The Database connection error, please try again', 'Failed!!');
			}).always(function() {
				$(".savePay[RegID='"+RegID+"']").prop('disabled',false).html('Submit');
			});
		}
	});

	$(document).on('click','#btn-proses', function () {
		$("#dataBelumBayar").empty();
		loading_page('#dataBelumBayar');
	  if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
	  		toastr.error('The File APIs are not fully supported in this browser.', 'Failed!!');
	        return;
	      }

	      input = document.getElementById('rekKoran');
	      if (!input) {
	        toastr.error('Um, couldnot find the fileinput element.', 'Failed!!');
	      }
	      else if (!input.files) {
	        toastr.error('This browser doesnot seem to support the `files', 'Failed!!');
	      }
	      else if (!input.files[0]) {
	        toastr.error('Please select a file before clicking Proses', 'Failed!!');
	      }
	      else {
	        file = input.files[0];
	        fr = new FileReader();
	        fr.onload = receivedText;
          	fr.readAsText(file);
          	//fr.readAsDataURL(file);
	      }

	});

	function receivedText()
	{
		processData(fr.result);
	}

	function processData(allText) {
	    var allTextLines = allText.split(/\r\n|\n/);
	    var headers = allTextLines[0].split(',');
	    var lines = [];
	    var totalData = 0;
	    var totalDataChecking = 0;

	    var DeBitName = $("#DeBitName").val().trim();
	    var CreditName = $("#CreditName").val().trim();
	    var baris = $("#baris").val();
	    var kolom = $("#kolom").val();
	    var kolomKredit = $("#kolomKredit").val();
	    var kolomDebit = $("#kolomDebit").val();
	    var LineAkhirData = $('#LineAkhirData').val().trim();
	    var dataValidation = {
	    						DeBitName : DeBitName,
	    						CreditName : CreditName,
	    						LineAkhirData : LineAkhirData
	    					};

	    if (validation(dataValidation)) {
	    	baris = parseInt(baris) - parseInt(1); // karena array dimulai dari 0
	    	kolom = parseInt(kolom) - parseInt(1); // karena array dimulai dari 0
	    	kolomKredit = parseInt(kolomKredit) - parseInt(1); // karena array dimulai dari 0
	    	kolomDebit = parseInt(kolomDebit) - parseInt(1); // karena array dimulai dari 0
	    	for (var i=0; i<allTextLines.length; i++) {
	    	    var data = allTextLines[i].split(',');
	    	    if(i<baris)
	    	    {
	    	    	if (data[kolomDebit] == DeBitName || data[kolomKredit] == CreditName) {
	    	    		toastr.error("Format tidak sesuai", 'Failed!!');
	    	    		break;
	    	    	}
	    	    }
	    	    else
	    	    {
	    	    	if (data[kolomDebit] == DeBitName || data[kolomKredit] == CreditName) {
	    	    		totalData++;
	    	    		lines.push(data);
	    	    	}

	    	    	if (data[0] == LineAkhirData) {
	    	    		totalDataChecking = parseInt(i) - parseInt(5);
	    	    	}
	    	    }
	    	}

	    	if (totalData = totalDataChecking) {
	    		processData2(lines)
	    	}
	    	else
	    	{
	    		toastr.error("Format tidak sesuai", 'Failed!!');
	    	}
	    }	// exit if
	}

  function validation(arr)
  {
  	// console.log(arr);
    var toatString = "";
    var result = "";
    for(var key in arr) {
       switch(key)
       {
        case  "DeBitName" :
        case  "CreditName" :
        case  "LineAkhirData" :
              result =  Validation_required(arr[key],key);
              if (result['status'] == 0) {
                toatString += result['messages'] + "<br>";
              }
              break;
       }

    }
    if (toatString != "") {
      toastr.error(toatString, 'Failed!!');
      return false;
    }

    return true;
  }

	function processData2(datacsv) {
	    var dataSaveTBL = [];
	    //console.log(dataGet);
	    for (var i = 0; i < dataGet.length; i++) {
	    	var PriceFormulirDB = dataGet[i]['PriceFormulir'];
	    	var count = 0;
	    	for (var j = 0; j < datacsv.length; j++) {
	    		if (datacsv[j][4] == "CR") {
	    			var PriceFormulirCSV = datacsv[j][3];
	    			if (PriceFormulirDB == PriceFormulirCSV) {
	    				//console.log(PriceFormulirDB);
	    				count++;
	    			}
	    		}
	    	}
	    	//console.log(count + " -- PriceFormulirDB : " +PriceFormulirDB);
	    	if (count > 0) {
	    		var valueToPush = { }
	    		for(var key in dataGet[i]) {
	    			valueToPush[key] = dataGet[i][key];
	    		}
	    		valueToPush['count'] = count;
	    		dataSaveTBL.push(valueToPush);
	    	}
	    }
	    console.log(dataSaveTBL);
	    generateTableConfirm(dataSaveTBL);
	}

	function generateTableConfirm(dataResult)
	{
		$.fn.dataTable.ext.errMode = 'throw';
		$(".datatable2 tbody").empty();
		$(".datatable2").addClass("hide");
		$("#btn-confirm").addClass("hide");
		for (var i = 0; i < dataResult.length; i++) {
			var varFileUpload = '<td>'+
								'<a href="javascript:void(0);" onclick="showModalImage(\''+url_images+dataResult[i].FileUpload+'\')">File Upload'+
								'</a>'+
								'</td>'	;
			if (dataResult[i].FileUpload == null ) {
				varFileUpload = '<td style="'+
							'color:  red;'+
							'">Bukti Pembayaran belum diupload'+
						  '</td>';
			}
			var total_searching = '<td>1</td>';
			if (dataResult[i]['count'] > 1) {
				total_searching ='<td style="'+
							'color:  red;'+
							'">'+dataResult[i]['count']+
						  '</td>';
			}

			$(".datatable2 tbody").append( '<tr>'+
					  '<td class="checkbox-column">'+
					  	'<input type="checkbox" class="uniform" value ="'+dataResult[i]['ID']+";"+dataResult[i]['FileUpload']+";"+dataResult[i]['Email']+";"+dataResult[i]['count']+";"+dataResult[i]['PriceFormulir']+'">'+
					  '</td>'+
					  '<td>'+dataResult[i]['Name']+'</td>'+
					  '<td>'+dataResult[i]['Email']+'</td>'+
					  '<td>'+dataResult[i]['PriceFormulir']+'</td>'+
					  varFileUpload+
					  '<td>'+dataResult[i]['SchoolName']+'</td>'+
            '<td>'+dataResult[i]['Phone']+'</td>'+
					  '<td>'+dataResult[i]['RegisterAT']+'</td>'+
					  '<td>'+dataResult[i]['uploadAT']+'</td>'+
					  total_searching+
				  '</tr>'

			);
		}

		setTimeout(function () {
		     $("#dataBelumBayar").html('');
		     $("#btn-confirm").removeClass('hide');
		     $(".datatable2").removeClass('hide');
		     $("#tblResultCSV").removeClass('hide');
		     //LoaddataTable('.datatable2');
		},500);
	}

	$(document).on('click','#dataResultCheckAll', function () {
		$('input.uniform').not(this).prop('checked', this.checked);
		  /*$(".uniform", $(".datatable2").fnGetNodes()).each(function () {
		  	$(this).prop("checked", true);
		  	//$('input.uniform').not(this).prop('checked', this.checked);
		  });*/
	});

	$(document).on('click','#btn-confirm', function () {
		loading_button('#btn-confirm');
		var RegisterID = getValueChecbox('.datatable2');
		 if (RegisterID.length == 0) {
		 	toastr.error("Silahkan checked dahulu", 'Failed!!');
		 }
		 else
		 {
		 	var msg = '';
		 	console.log(RegisterID);
		 	for (var i = 0; i < RegisterID.length; i++) {
		 		var split = RegisterID[i].split(';');
		 		if (split[0] != 'nothing') {
		 			if (split[1] == 'null') {
		 				msg = '<ul><li>Apakah anda yakin untuk mengkonfirmasi yang belum melakukan upload bukti pembayaran ?</li>';
		 				break;
		 			}
		 		}
		 	}

		 	for (var i = 0; i < RegisterID.length; i++) {
		 		var split = RegisterID[i].split(';');
		 		if (split[0] != 'nothing') {
		 			if (split[3] > 1) {
		 				msg += '<li>Apakah anda yakin untuk menkonfirmasi bahwa price number = '+split[4]+' memiliki lebih dari satu data pada file Rekening Koran ?</li>';
		 				break;
		 			}
		 		}
		 	}

		 	if(msg == '')
		 	{
	 			 //var getAllRegisterID;
	 			 $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Apakah anda yakin untuk melakukan request ini ?? </b> ' +
	 			     '<button type="button" id="confirmYesProcess" class="btn btn-primary" style="margin-right: 5px;" data-pass = "'+RegisterID+'">Yes</button>' +
	 			     '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
	 			     '</div>');
	 			 $('#NotificationModal').modal('show');
	 		     console.log(RegisterID);
		 	}
		 	else{
		 		msg += '</ul>'
		 		$('#NotificationModal .modal-body').html('<div style="text-align: left;"><b>'+msg+'</b></div> ' +
		 		    '<button type="button" id="confirmYesProcess" class="btn btn-primary" style="margin-right: 5px;" data-pass = "'+RegisterID+'">Yes</button>' +
		 		    '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
		 		    '');
		 		$('#NotificationModal').modal('show');
		 	}

		 }
		 $('#btn-confirm').prop('disabled',false).html('Confirm');
	});

	$(document).on('click','#confirmYesProcess', function () {
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

        var url = base_url_js+'finance/confirmed-verifikasi-pembayaran-registration_online';
        var arrdata = $(this).attr('data-pass');
        var data = {
            arrdata : arrdata,
        };

        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{token:token},function (data_json) {
            setTimeout(function () {
               toastr.options.fadeOut = 10000;
               toastr.success('Data berhasil disimpan', 'Success!');
               loaddataBelumBayar();
               loadDataTelahBayar();
               $("#tblResultCSV").addClass('hide');
               $('#NotificationModal').modal('hide');
               $("#btn-confirm").addClass("hide");
               //window.location.reload(true);
            },500);
        });

	});

	$(document).off('click', '.ShowKwitansi').on('click', '.ShowKwitansi',function(e) {
		// console.log('asd');
		var NoKwitansi = $(this).attr('NoKwitansi');
		if (NoKwitansi != '-' && NoKwitansi != '' && NoKwitansi != null) {
			var FormulirCode = $(this).attr('formulir');
			var NoFormRef = $(this).attr('ref');
			var namalengkap = $(this).attr('namalengkap');
			var hp = $(this).attr('hp');
			var jurusan = $(this).attr('jurusan');
			var pembayaran = $(this).attr('pembayaran');
			var jenis = $(this).attr('jenis');
			var jumlah = $(this).attr('jumlah');
			var date = $(this).attr('date');
			var formulir = $(this).attr('formulir');
			var url = base_url_js+'admission/export_kwitansi_formulironline';
			var NumForm = NoKwitansi;
				data = {
					NoFormRef : NoFormRef ,
					namalengkap : namalengkap ,
					hp : hp ,
					jurusan :  jurusan ,
					pembayaran :  pembayaran,
					jenis : jenis ,
					jumlah : jumlah ,
					date : date,
					NumForm : NumForm,
				}
				var token = jwt_encode(data,"UAP)(*");
				FormSubmitAuto(url, 'POST', [
					{ name: 'token', value: token },
				]);
		}
		
	})
</script>
