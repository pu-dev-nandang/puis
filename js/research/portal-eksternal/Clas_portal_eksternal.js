class Clas_portal_eksternal extends Clas_global_portal_eksternal {
	
	constructor() {
		super()
	}

	LoadPageDefault = (selectorPageInput,selectorPageList) => {
		this.LoadPageInput(selectorPageInput);
		this.LoadPageList(selectorPageList);
	}

	LoadPageList = (selectorPage) => {
		this.set_html_PageList().writeHtml(selectorPage).insertJs(() => {
						
		});
	}

	set_html_PageList = () => {
		this.Wrhtml = '<div class = "row">'+
						'<div class = "col-md-12">'+
							'<div class = "table-responsive">'+
								'<table class = "table table-bordered">'+
									'<thead>'+
										'<tr>'+
											'<th style ="width:5%">No</th>'+
											'<th>Nama & Email</th>'+
											'<th>Type As</th>'+
											'<th>Action</th>'+
										'</tr>'+
									'</thead>'+
									'<tbody></tbody>'+
								'</table>'+
							'</div>'+
						'</div>'+	
					  '</div>';
		return this;
	}

	__OPUniversity = async(selector,selected='') => {
		const url = base_url_js+'rest_global/__load_university_or_instansi';
		let dataform = {
			auth : 's3Cr3T-G4N',
		}
		let token = jwt_encode(dataform,'UAP)(*');
		const response = await AjaxSubmitFormPromises(url,token,[],Apikey,requestHeader);
		
		if (response.length > 0) {
			selector.empty();
			let sel = (selected == '') ? 'selected' : '';
			selector.append('<option value = " " '+sel+'>--No Choose--</option>');
			for (var i = 0; i < response.length; i++) {
				selector.append('<option value = "'+response[i].ID+'">'+response[i].Name_University+'</option>');
			}

			selector.select2();
		}
	}

	LoadPageInput = (selectorPage) => {
		this.set_html_PageInput().writeHtml(selectorPage).insertJs(() => {
			let selectorUniversity = selectorPage.find('.FrmRegistrasi[name="ID_University"]');
			this.__OPUniversity(selectorUniversity);
		});
	}

	set_html_PageInput = () => {
		const optionType = '<div class = "row">'+
								'<div class ="col-md-4 col-md-offset-4">'+
									'<div class = "thumbnail">'+
										'<div class = "row">'+
											'<div class ="col-xs-12">'+
												'<div class="form-group">'+
													'<label>Type User</label>'+
													'<select class = "form-control OPtypeUser">'+
														'<option value = "Dosen" selected>Dosen</option>'+
														'<option value = "Mahasiswa">Mahasiswa</option>'+
														'<option value = "Instansi">Instansi</option>'+
													'</select>'+
												'</div>'+
											'</div>'+
										'</div>'+
									'</div>'+	
								'</div>'+
						   '</div>';
		this.Wrhtml = optionType;
		const htmlProfiledosen = this.htmlProfiledosen();
		const htmlPass = this.htmlPass();
		const htmlTypeAsNonMHS = this.htmlTypeAsNonMHS();
		this.Wrhtml += '<div class ="row pageHtmlInput" style ="margin-top:10px;">'+
							'<div class ="col-md-6">'+ 
								'<div class = "well">'+
									'<div style = "padding : 10px;">'+
										'<h4>Registration Profile</h4>'+
									'</div>'+
									'<div id = "pageBiodata">'+
										htmlProfiledosen+
									'</div>'+	
								'</div>'+	
							'</div>'+
							'<div class ="col-md-6">'+ 
								'<div class = "well">'+
									'<div id = "pageAccess">'+
										'<div style = "padding : 10px;">'+
											'<h4>Access</h4>'+
										'</div>'+
										htmlPass+
									'</div>'+
									'<div style = "padding : 10px;margin-top:80px;">'+
										'<h4>Type As</h4>'+
									'</div>'+
									'<div id = "pageTypeAs">'+
										htmlTypeAsNonMHS+
									'</div>'+	
								'</div>'+	
							'</div>'+
					  '</div>'+
					  '<div class = "row" style = "margin-top:10px;">'+
					  	'<div class = "col-md-12">'+
					  		'<div class = "well">'+
					  			'<div class = "row">'+
					  				'<div class = "col-xs-12">'+
					  					'<button class="btn btn-block btn-success" id = "btnSetAction" action = "add" data-id = "">Save</button>'+
					  				'</div>'+
					  			'</div>'+
					  		'</div>'+
					  	'</div>'+
					  '</div>';
		return this;
	}

	htmlTypeAsNonMHS = () => {
		const html  = '<div class = "row">'+
						'<div class ="col-md-12">'+
							'<div class ="table-responsive">'+
								'<table class = "table">'+
									'<tr>'+
										'<td>'+
											'<div class="checkbox">'+
												'<label>Dosen</label>'+
												'<input type = "checkbox" class = " FrmRegistrasi" name ="F_dosen" validate ="" lengthmin = "0" >'+
											'</div>'+
										'</td>'+
										'<td>'+
											'<div class="checkbox">'+
												'<label>Kolaborasi</label>'+
												'<input type = "checkbox" class = " FrmRegistrasi" name ="F_kolaborasi" validate ="" lengthmin = "0" >'+
											'</div>'+
										'</td>'+
										'<td>'+
											'<div class="checkbox">'+
												'<label>Reviewer</label>'+
												'<input type = "checkbox" class = " FrmRegistrasi" name ="F_reviewer" validate ="" lengthmin = "0" >'+
											'</div>'+
										'</td>'+
									'</tr>'+
								'</table>'+
							'</div>'+
						'</div>'+
					  '</div>';	
			return html;	
	}

	htmlTypeAsMHS = () => {
		const html  = '<div class = "row">'+
						'<div class ="col-md-12">'+
							'<div class ="table-responsive">'+
								'<table class = "table">'+
									'<tr>'+
										'<td>'+
											'<div class="checkbox">'+
												'<label>Mahasiswa</label>'+
												'<input type = "checkbox" class = " FrmRegistrasi" name ="F_mhs" validate ="" lengthmin = "0" >'+
											'</div>'+
										'</td>'+
									'</tr>'+
								'</table>'+
							'</div>'+
						'</div>'+
					  '</div>';	
			return html;	
	}

	htmlPass = () => {
		const html  = '<div class = "row">'+
						'<div class ="col-md-12">'+
							'<div class ="table-responsive">'+
								'<table class = "table">'+
									'<tr>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>Password</label>'+
												'<input type = "password" class = "form-control FrmRegistrasi" name ="Password" validate ="required" lengthmin = "6" placeholder ="Input Password">'+
												'<p style = "color : red;">if blank password, the password get from tanggal lahir with format ddmmyy</p>'+
											'</div>'+
										'</td>'+
									'</tr>'+
								'</table>'+
							'</div>'+
						'</div>'+
					  '</div>';	
			return html;		  	
	}

	htmlProfiledosen = () => {
		const html  = '<div class = "row">'+
						'<div class ="col-md-12">'+
							'<div class ="table-responsive">'+
								'<table class = "table">'+
									'<tr>'+
										'<td colspan = "2">'+
											'<div class = "form-group">'+
												'<label>Nama <span style = "color:red;">*</span></label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="Nama" validate ="required" lengthmin = "3" placeholder ="Input Nama">'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>NIDN</label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="NIDN" validate ="" lengthmin = "0" placeholder ="Input NIDN">'+
											'</div>'+
										'</td>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>NIP<span style = "color:red;"> *</span></label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="NIP" validate ="required" lengthmin = "3" placeholder ="Input NIP">'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td colspan = "2">'+
											'<div class = "form-group">'+
												'<label>Email <span style = "color:red;"> *</span></label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="Email" validate ="email" lengthmin = "3" placeholder ="Input Email">'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>No Telp</label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="No_telepon" validate ="" lengthmin = "0" placeholder ="Input No Telp">'+
											'</div>'+
										'</td>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>No HP</label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="No_handphone" validate ="" lengthmin = "0" placeholder ="Input No HP">'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>JK <span style = "color:red;"> *</span></label>'+
												'<select class = "form-control FrmRegistrasi"  name = "JK" validate = "required" lengthmin ="1">'+
													'<option value = "" disabled>--Choose JK--</option>'+
													'<option value = "Laki-Laki">L</option>'+
													'<option value = "Perempuan">P</option>'+
												'</select>'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+	
										'<td>'+
											'<div class = "form-group">'+
												'<label>Tempat Lahir</label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="Tmp_lahir" validate ="" lengthmin = "0" placeholder ="Input Tempat lahir">'+
											'</div>'+
										'</td>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>Tanggal Lahir</label>'+
												'<div class="input-group input-append date datetimepicker">'+
						                            '<input data-format="yyyy-MM-dd" class="form-control FrmRegistrasi" type=" text" readonly="" name ="Tgl_lahir" validate ="" lengthmin = "0">'+
						                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
						                		'</div>'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td colspan = "3">'+
											'<div class = "form-group">'+
												'<label>Alamat</label>'+
												'<textarea class = "form-control FrmRegistrasi" name ="Alamat" validate ="" lengthmin = "0" placeholder ="Input alamat"></textarea>'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td colspan = "3">'+
											'<div class = "form-group">'+
												'<label>Instansi / Universitas</label>'+
												'<select class = "select2-select-00 full-width-fix FrmRegistrasi" name="ID_University" validate ="required" lengthmin = "1"></select>'+
											'</div>'+
										'</td>'+
									'</tr>'+
								'</table>'+
							'</div>'+
						'</div>'+
					'</div>';

		return html;
	}

	htmlProfileMHS = () => {
		const html  = '<div class = "row">'+
						'<div class ="col-md-12">'+
							'<div class ="table-responsive">'+
								'<table class = "table">'+
									'<tr>'+
										'<td colspan = "2">'+
											'<div class = "form-group">'+
												'<label>Nama <span style = "color:red;">*</span></label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="Nama" validate ="required" lengthmin = "3" placeholder ="Input Nama">'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>NIM</label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="NIM" validate ="" lengthmin = "0" placeholder ="Input NIM">'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td colspan = "2">'+
											'<div class = "form-group">'+
												'<label>Email <span style = "color:red;"> *</span></label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="Email" validate ="email" lengthmin = "3" placeholder ="Input Email">'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>No Telp</label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="No_telepon" validate ="" lengthmin = "0" placeholder ="Input No Telp">'+
											'</div>'+
										'</td>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>No HP</label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="No_handphone" validate ="" lengthmin = "0" placeholder ="Input No HP">'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>JK <span style = "color:red;"> *</span></label>'+
												'<select class = "form-control FrmRegistrasi"  name = "JK" validate = "required" lengthmin ="1">'+
													'<option value = "" disabled>--Choose JK--</option>'+
													'<option value = "Laki-Laki">L</option>'+
													'<option value = "Perempuan">P</option>'+
												'</select>'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+	
										'<td>'+
											'<div class = "form-group">'+
												'<label>Tempat Lahir</label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="Tmp_lahir" validate ="" lengthmin = "0" placeholder ="Input Tempat lahir">'+
											'</div>'+
										'</td>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>Tanggal Lahir</label>'+
												'<div class="input-group input-append date datetimepicker">'+
						                            '<input data-format="yyyy-MM-dd" class="form-control FrmRegistrasi" type=" text" readonly="" name ="Tgl_lahir" validate ="" lengthmin = "0">'+
						                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
						                		'</div>'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td colspan = "3">'+
											'<div class = "form-group">'+
												'<label>Alamat</label>'+
												'<textarea class = "form-control FrmRegistrasi" name ="Alamat" validate ="" lengthmin = "0" placeholder ="Input alamat"></textarea>'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td colspan = "3">'+
											'<div class = "form-group">'+
												'<label>Instansi / Universitas</label>'+
												'<select class = "select2-select-00 full-width-fix FrmRegistrasi" name="ID_University" validate ="required" lengthmin = "1"></select>'+
											'</div>'+
										'</td>'+
									'</tr>'+
								'</table>'+
							'</div>'+
						'</div>'+
					'</div>';

		return html;
	}

	htmlProfileInstansi = () => {
		const html  = '<div class = "row">'+
						'<div class ="col-md-12">'+
							'<div class ="table-responsive">'+
								'<table class = "table">'+
									'<tr>'+
										'<td colspan = "2">'+
											'<div class = "form-group">'+
												'<label>Nama <span style = "color:red;">*</span></label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="Nama" validate ="required" lengthmin = "3" placeholder ="Input Nama">'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>NIP</label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="NIP" validate ="" lengthmin = "0" placeholder ="Input NIP">'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td colspan = "2">'+
											'<div class = "form-group">'+
												'<label>Email <span style = "color:red;"> *</span></label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="Email" validate ="email" lengthmin = "3" placeholder ="Input Email">'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>No Telp</label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="No_telepon" validate ="" lengthmin = "0" placeholder ="Input No Telp">'+
											'</div>'+
										'</td>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>No HP</label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="No_handphone" validate ="" lengthmin = "0" placeholder ="Input No HP">'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>JK <span style = "color:red;"> *</span></label>'+
												'<select class = "form-control FrmRegistrasi"  name = "JK" validate = "required" lengthmin ="1">'+
													'<option value = "" disabled>--Choose JK--</option>'+
													'<option value = "Laki-Laki">L</option>'+
													'<option value = "Perempuan">P</option>'+
												'</select>'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+	
										'<td>'+
											'<div class = "form-group">'+
												'<label>Tempat Lahir</label>'+
												'<input type = "text" class = "form-control FrmRegistrasi" name ="Tmp_lahir" validate ="" lengthmin = "0" placeholder ="Input Tempat lahir">'+
											'</div>'+
										'</td>'+
										'<td>'+
											'<div class = "form-group">'+
												'<label>Tanggal Lahir</label>'+
												'<div class="input-group input-append date datetimepicker">'+
						                            '<input data-format="yyyy-MM-dd" class="form-control FrmRegistrasi" type=" text" readonly="" name ="Tgl_lahir" validate ="" lengthmin = "0">'+
						                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
						                		'</div>'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td colspan = "3">'+
											'<div class = "form-group">'+
												'<label>Alamat</label>'+
												'<textarea class = "form-control FrmRegistrasi" name ="Alamat" validate ="" lengthmin = "0" placeholder ="Input alamat"></textarea>'+
											'</div>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td colspan = "3">'+
											'<div class = "form-group">'+
												'<label>Instansi / Universitas</label>'+
												'<select class = "select2-select-00 full-width-fix FrmRegistrasi" name="ID_University" validate ="required" lengthmin = "1"></select>'+
											'</div>'+
										'</td>'+
									'</tr>'+
								'</table>'+
							'</div>'+
						'</div>'+
					'</div>';

		return html;
	}
}