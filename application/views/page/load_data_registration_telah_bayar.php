<div id = "loadingProcess"></div>
<div class="col-md-12">
    <table class="table table-striped table-bordered table-hover table-checkable" id ="tblTelahBayar">
    	<thead>
    		<tr style="background: #333;color: #fff;">
    		<th style="width: 15px;">No</th>
    		<th>Nama</th>
    		<th>Email</th>
    		<th>Price Formulir & Kwitansi</th>
    		<!-- <th>VA Number</th>
    		<th>Biling ID</th> -->
    		<!-- <th>File Upload</th> -->
    		<th>Sekolah</th>
    		<th>Formulir Code</th>
    		<th>Register At</th>
    		<th>Tanggal Bayar</th>
    		</tr>
    	</thead>
    	<tbody>
    	</tbody>
    </table>
</div>
<script type="text/javascript">

	$(document).ready(function() {
		getJsonDataRegistrationUpload();
	}); // exit document Function

	function getJsonDataRegistrationUpload()
	{
		var url = base_url_js+'api/__getDataRegisterTelahBayar';
		//var url_images = 'http://localhost/register/upload/';
		loading_page('#loadingProcess');
		var Tahun  = "<?php echo $tahun ?>";
		var data = {Tahun : Tahun};
		$.post(url,data,function (data_json) {
			//var response = jQuery.parseJSON(data_json);
			var no = 1;
			$("#loadingProcess").remove();
			for (var i = 0; i < data_json.length; i++) {
				var varFileUpload = '<td>'+
								'<a href="javascript:void(0);" onclick="showModalImage(\''+url_images+data_json[i].FileUpload+'\')">File Upload'+
								'</a>'+
								'</td>'	;
				if (data_json[i].FileUpload == null ) {
					varFileUpload = '<td style="'+
    							'color:  red;'+
								'">Bukti Pembayaran belum diupload'+
							  '</td>';
				}

				var NoKwitansi = (data_json[i]['NoKwitansi'] == null || data_json[i]['NoKwitansi'] == '') ? '-' : data_json[i]['NoKwitansi'];
				// generate no kwitansi
				var Max = 4;
				if (NoKwitansi != '-') {
					var l = NoKwitansi.length;
					for (let z = 0; z < (parseInt(Max) - parseInt(l) ); z++) {
						NoKwitansi = '0'+NoKwitansi;
					}

					NoKwitansi = 'ON-'+NoKwitansi;
				}

				$("#tblTelahBayar tbody").append(
					'<tr>'+
						'<td>'+no+'</td>'+
						'<td>'+data_json[i]['Name']+'</td>'+
						'<td>'+data_json[i]['Email']+'</br>'+data_json[i]['Phone']+'</td>'+
						'<td>'+formatRupiah(data_json[i]['PriceFormulir'])+
								'<br/>'+
								'<a href = "javascript:void(0)"  formulir = "'+data_json[i]['FormulirCode']+'" class = "ShowKwitansi" nokwitansi = "'+data_json[i]['NoKwitansi']+'" ref="'+data_json[i]['No_Ref']+'" NamaLengkap="'+data_json[i]['Name']+'" hp="'+data_json[i]['Phone']+'" pembayaran = "Pembelian Formulir Pendaftaran" jumlah = "'+data_json[i]['PriceFormulir']+'" date = "'+data_json[i]['VerificationAT']+'" jenis = "Transfer"  >'+NoKwitansi+'</a>'+
						'</td>'+
						// '<td>'+data_json[i]['VA_number']+'</td>'+
						// '<td>'+data_json[i]['BilingID']+'</td>'+
						// varFileUpload+
						'<td>'+data_json[i]['SchoolName']+'</td>'+
						'<td>'+data_json[i]['FormulirCode']+' / '+data_json[i]['No_Ref']+'</td>'+
						'<td>'+data_json[i]['RegisterAT']+'</td>'+
						'<td>'+data_json[i]['VerificationAT']+'</td>'+
					'</tr>'
					);
				no++;
			}
			if (data_json.length > 0 ) {
				$("#btn-proses").removeClass('hide');
			}
		    LoaddataTable('#tblTelahBayar');
		});
	}

	function showModalImage(urlImage)
	{
		$('#NotificationModal .modal-header').addClass('hide');
		$('#NotificationModal .modal-body').html('<center>' +
		    '                    <img src="'+urlImage+'">'+
		    '                </center>');
		$('#NotificationModal .modal-footer').addClass('hide');
		$('#NotificationModal').modal({
		    //'backdrop' : 'static',
		    'show' : true
		});
	}
</script>
