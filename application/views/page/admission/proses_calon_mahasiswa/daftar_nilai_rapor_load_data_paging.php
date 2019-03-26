<style type="text/css">
	.btn-Hitung {
		background-color: #e20f0f;
	}
	.btn-Save{
		background-color: #1ace37;
	}

	hr.style-eight {
		height: 10px;
		border: 1;
		box-shadow: inset 0 9px 9px -3px rgba(11, 99, 184, 0.8);
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		-ms-border-radius: 5px;
		-o-border-radius: 5px;
		border-radius: 5px;
	}
</style>
<?php if (count($datadb) > 0 ): ?>
	<div id = "tblData" class="table-responsive">
		<table class="table table-striped table-bordered table-hover table-checkable tableData">
			<!-- <caption><strong>List Dokumen</strong></caption> -->
			<thead>
				<tr>
					<th style="width: 10px;">No</th>
					<th style="width: 200px;">Data</th>
					<?php for ($i = 0; $i < count($mataujian); $i++): ?>
						<?php 
							$NamaUjian = (strlen($mataujian[$i]['NamaUjian']) > 15) ? substr($mataujian[$i]['NamaUjian'], 0,15).'...' : $mataujian[$i]['NamaUjian'] ;
						 ?>
						<th style="width: 100px;"><?php echo $NamaUjian.'('.$mataujian[$i]['Bobot'].')' ?></th>
					<?php endfor; ?>
					<th style="width: 40px;">Jml Bobot</th>
					<th style="width: 100px;">Jml Bobot * Nilai</th>
					<th style="width: 40px;">IP & Indeks</th>
					<th style="width: 40px;">Rangking</th>
					<th style="width: 40px;">File Rangking</th>
				</tr>
			</thead>
			<tbody> 
				<?php for ($i = 0; $i < count($datadb); $i++): ?>
					<?php $jml_bobot = 0 ?>
						<tr class = 'ID_ujian_perprody<?php echo $datadb[$i]['ID_register_formulir'] ?>'>
							<td rowspan="2"><?php echo $no++ ?></td>
							<td>
								<?php echo $datadb[$i]['NameCandidate'] ?>
								<br>
								<?php echo $datadb[$i]['NamePrody'] ?>	
								<br>
								<?php 
								$Code = ($datadb[$i]['No_Ref'] != "") ? $datadb[$i]['FormulirCode'].' / '.$datadb[$i]['No_Ref'] : $datadb[$i]['FormulirCode'];
								echo $Code;
								 ?>	
								</td>
							<?php for ($j = 0; $j < count($mataujian); $j++): ?>
								<td>
									<select class="ID_ujian_perprody select2-select-00 col-md-4 full-width-fix" id-mataujian ='<?php echo $mataujian[$j]['ID'] ?>' bobot = '<?php echo $mataujian[$j]['Bobot'] ?>' id-formulir = '<?php echo $datadb[$i]['ID_register_formulir'] ?>'>
										<?php for ($k = 0; $k <= 5; $k++): ?>
										<?php $selected = ($k == 3) ? 'selected' : '' ?>	
									    <option value="<?php echo $k ?>" <?php echo $selected ?>><?php echo $k ?></option>
									    <?php endfor; ?>
									</select>
								</td>	
								<?php $jml_bobot = $jml_bobot + $mataujian[$j]['Bobot'] ?>
							<?php endfor; ?>	
							<td><input type="text" class = 'jml_bobot form-control' value="<?php echo $jml_bobot ?>" readonly></td>
							<td><input type="text" class = 'bobot_nilai form-control' id = 'bobot_nilai<?php echo $datadb[$i]['ID_register_formulir'] ?>' readonly></td>
							<td><input type="text" class = 'indeks form-control' id = 'indeks<?php echo $datadb[$i]['ID_register_formulir'] ?>' readonly></td>
							<td>
								<select id='Rangking<?php echo $datadb[$i]['ID_register_formulir'] ?>' class="Rangking select2-select-00 col-md-4 full-width-fix" id-formulir = '<?php echo $datadb[$i]['ID_register_formulir'] ?>'>
									<option value ='0' selected>0</option>
									<?php for ($l = 1; $l <= 10; $l++): ?>
										<option  value="<?php echo $l ?>"><?php echo $l ?></option>
									<?php endfor; ?>	
								</select>
							</td>
							<td>
								<select id='FileRapor<?php echo $datadb[$i]['ID_register_formulir'] ?>' class="FileRapor select2-select-00 col-md-4 full-width-fix" id-formulir = '<?php echo $datadb[$i]['ID_register_formulir'] ?>' >
									<option value ="0" selected >--File--</option>
								<?php $getDokument = $this->m_admission->getDataDokumentRegister($datadb[$i]['ID_register_formulir'])  ?>
								<?php for ($l = 0; $l < count($getDokument); $l++): ?>
									<option  value="<?php echo $getDokument[$l]['ID'] ?>"><?php echo $getDokument[$l]['Attachment'] ?></option>
								<?php endfor; ?>
								</select>
								<a href="javascript:void(0)" class="show_a_href" id = "show<?php echo $datadb[$i]['ID_register_formulir'] ?>" filee = "" Email = "<?php echo $datadb[$i]['Email'] ?>">Show</a>		
							</td>
						</tr>
						<!--  Input Nilai Finance-->
						<tr register_formulir = "<?php echo $datadb[$i]['ID_register_formulir'] ?>">
							<td>
								<div class="row">
									<div class="col-md-12" align="center">
										Input Nilai to Finance
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<select class="form-control PilihJurusan">
											<option value="0">--Please Choose--</option>
											<?php for ($m = 0; $m < count($G_Jurusan); $m++): ?>
												<option value="<?php echo $G_Jurusan[$m]['ID'] ?>"><?php echo $G_Jurusan[$m]['Type'] ?></option>
											<?php endfor; ?>
										</select>
									</div>
								</div>
							</td>
						</tr>
				<?php endfor; ?>
			</tbody>
		</table>
	</div>
	<div class="col-xs-12" align = "right">
	   <button class="btn btn-inverse btn-notification btn-Hitung" id="btn-Hitung">Hitung</button>
	   <button class="btn btn-inverse btn-notification btn-Save hide" id="btn-Save">Save</button>
	</div>			
<?php else: ?>
<div align = 'center'>No Result Data...</div>		
<?php endif ?>

<script type="text/javascript">
	window.grade = <?php echo $grade ?>;
	var G_Jurusan_sub = <?php echo json_encode($G_Jurusan_sub); ?>;
	$(document).ready(function () {
		$('.ID_ujian_perprody').select2({
		   //allowClear: true
		});

		$('.kelulusan').select2({
		   //allowClear: true
		});
		// console.log(grade);

		// $("#btn-Hitung").click(function(){
		// 	prosesData();
		// })

		// $("#btn-Save").click(function(){
		//   loading_button('#btn-Save');
		//   var data = {
		//   					processs1 : processs,
		//   					rangking : processs1,
		//   					arr_fin : arr_fin,
		//   			};
		//   var token = jwt_encode(data,"UAP)(*");
		//   var url = base_url_js+'admission/proses-calon-mahasiswa/set-nilai-rapor/save';
		//   	$.post(url,{token:token},function (data_json) {
		//         var response = jQuery.parseJSON(data_json);
		//         toastr.success('Data berhasil disimpan', 'Success!');
		//         // loadTableData(1);
		//         $('#btn-Save').prop('disabled',false).html('Save');
	 //      	}).done(function() {
	 //      	      loadTableData(1);
	 //      	      $('#btn-Save').prop('disabled',false).html('Save');
	 //  	    }).fail(function() {
	 //  	      toastr.error('The Database connection error, please try again', 'Failed!!');
	 //  	    }).always(function() {
	 //  	      $('#btn-Save').prop('disabled',false).html('Save');
	 //  	    });
		// })
		
	});

$(document).off('click', '#btn-Hitung').on('click', '#btn-Hitung',function(e) {
	prosesData();
});	

$(document).off('click', '#btn-Save').on('click', '#btn-Save',function(e) {
		  loading_button('#btn-Save');
		  var data = {
		  					processs1 : processs,
		  					rangking : processs1,
		  					arr_fin : arr_fin,
		  			};
		  var token = jwt_encode(data,"UAP)(*");
		  var url = base_url_js+'admission/proses-calon-mahasiswa/set-nilai-rapor/save';
		  	$.post(url,{token:token},function (data_json) {
		        var response = jQuery.parseJSON(data_json);
		        toastr.success('Data berhasil disimpan', 'Success!');
		        // loadTableData(1);
		        $('#btn-Save').prop('disabled',false).html('Save');
	      	}).done(function() {
	      	      loadTableData(1);
	      	      $('#btn-Save').prop('disabled',false).html('Save');
	  	    }).fail(function() {
	  	      toastr.error('The Database connection error, please try again', 'Failed!!');
	  	    }).always(function() {
	  	      $('#btn-Save').prop('disabled',false).html('Save');
	  	    });
});	

$(document).off('change', '.PilihJurusan').on('change', '.PilihJurusan',function(e) {
    var row = $(this).closest('tr');
    // clear all td except jurusan
    for (var i = 0; i <= 11; i++) {
    	var s = i+1;
    	row.find('td:eq('+s+')').remove();
    }

    var v = $(this).val();
    var arr_choice = [];
    for (var i = 0; i < G_Jurusan_sub.length; i++) {
    	if (G_Jurusan_sub[i].ID_m_criteria_rapor_fin == v) {
    		arr_choice.push(G_Jurusan_sub[i].NmMtPel)
    	}
    }

    for (var i = 0; i < arr_choice.length; i++) {
    	var s = i+1;
    	var op = '<select class = "form-control NilaiFin" mataujian = "'+arr_choice[i]+'">';
    	for (var j = 0; j <= 100; j++) {
    		op += '<option value = "'+j+'">'+j+'</option>';
    	}

    	op += '</select>';
    	var btn_edit = '<button class = "btn btn-primary btn-xs edit_pelajaran"><i class="fa fa-pencil-square-o"></i></button>';
    	var html = '<div align = "right">'+btn_edit+'</div><div class = "form-group"><label>'+arr_choice[i]+'</label>'+
    						op+'</div>';
    	var cc = row.find('td:eq('+s+')');
    	if (cc.length) {
    		cc.remove();
    	}
    	row.append('<td align = "center"></td>');					
    	row.find('td:eq('+s+')').html(html);
    }

    var s = i+1;
    // add td sisa
    for (var i = s; i <= 9; i++) {
    	var cc = row.find('td:eq('+i+')');
    	if (cc.length) {
    		cc.remove();
    	}

    	row.append('<td align = "center"></td>');
    }

    // console.log(row.find('td').length);
    if (row.find('td').length < 10 ) {
    	row.append('<td align = "center"></td>');
    }	
});

$(document).off('click', '.edit_pelajaran').on('click', '.edit_pelajaran',function(e) {
	// get Nama Mata Ujian
	var r = $(this).closest('td');
	var nm = r.find('.NilaiFin').attr('mataujian');
	var input = '<div class = "diveditpelajaran" style = "margin-bottom : 5px;margin-top : 5px;"><input type = "text" class = "form-control inputmt_pelajaran" value = "'+nm+'"></div>';
	r.find('label').remove();
	r.find('.NilaiFin').before(input);
	$(this).remove();
	var btn_save = '<button class = "btn btn-primary btn-xs save_edit_pelajaran"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>';
	r.find('div[align= "right"]').html(btn_save);
});

$(document).off('click', '.save_edit_pelajaran').on('click', '.save_edit_pelajaran',function(e) {
	var r = $(this).closest('td');
	var inputmt_pelajaran = r.find('.inputmt_pelajaran').val();
	r.find('.NilaiFin').attr('mataujian',inputmt_pelajaran);
	var btn_edit = '<button class = "btn btn-primary btn-xs edit_pelajaran"><i class="fa fa-pencil-square-o"></i></button>';
	r.find('.diveditpelajaran').remove();
	r.find('.NilaiFin').before('<label>'+inputmt_pelajaran+'</label>');
	r.find('div[align= "right"]').html(btn_edit);
});

</script>

