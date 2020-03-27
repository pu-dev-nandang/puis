class Clas_portal_eksternal extends Clas_global_portal_eksternal {
	
	constructor() {
		super()
		this.dataTableList;
	}

	LoadPageDefault = (selectorPageInput,selectorPageList) => {
		this.LoadPageInput(selectorPageInput);
		this.LoadPageList(selectorPageList);
	}

	LoadPageList = (selectorPage) => {
		this.set_html_PageList().writeHtml(selectorPage).insertJs(() => {
			const selectorTable =  selectorPage.find('table');
			this.LoadTableList(selectorTable);
		});
	}

	set_html_PageList = () => {
		this.Wrhtml = '<div class = "row">'+
						'<div class = "col-md-12">'+
							'<div class = "table-responsive">'+
								'<table class = "table table-bordered">'+
									'<thead>'+
										'<tr>'+
											'<th style ="width:3%">No</th>'+
											'<th style = "width:30%;">Nama & Email,</br>NIP & NIDN</th>'+
											'<th style = "width:30%;">Type As</th>'+
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
			$('.datetimepicker').datetimepicker({
				format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
			});
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
					  					'<button class="btn btn-block btn-success" id = "btnSaveEksternal" action = "add" data-id = "">Save</button>'+
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
												//'<p style = "color : red;">if blank password, the password get from tanggal lahir with format ddmmyy</p>'+
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

	LoadTableList = (selectorTable) =>  {
		let table = selectorTable.DataTable({
		    "fixedHeader": true,
		    "processing": true,
		    "destroy": true,
		    "serverSide": true,
		    "lengthMenu": [
		        [5, 10],
		        [5, 10]
		    ],
		    "iDisplayLength": 5,
		    "ordering": false,
		    "language": {
		        "searchPlaceholder": "Search Name,NIP/NIM,Email,NIDN",
		    },
		    "ajax": {
		        url: base_url_js + "rest_research/__datatable_LoadListUserEskternal", // json datasource
		        ordering: false,
		        type: "post", // method  , by default get
		        data: function(token) {
		            // Read values
		            if ($('.FilterOPtypeUser').length) {
		            	var data = {
		            	    auth: 's3Cr3T-G4N',
		            	    TypeUser : $('.FilterOPtypeUser').find('option:selected').val(),
		            	};
		            }
		            else
		            {
		            	var data = {
		            	    auth: 's3Cr3T-G4N',
		            	};
		            }
		            
		            var get_token = jwt_encode(data, "UAP)(*");
		            token.token = get_token;
		        },
		        error: function() { // error handling

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
		            'orderable': true,
		            'className': 'dt-body-center',
		            'render': function (data, type, full, meta){
		            	let decodedata = jwt_decode(full.data);
		            	// console.log(decodedata);
		            	const Nama = decodedata['Nama'];
		            	const Email = (decodedata['Email'] != '' && decodedata['Email'] != null) ? decodedata['Email'] : '-';
		            	let NIP = '-';
		            	let NIDN = '-';
		            	if (decodedata['TypeUser'] == 'Dosen') {
		            		NIP = (decodedata['NIP'] != '' && decodedata['NIP'] != null) ? decodedata['NIP'] : '-';
		            		NIDN = (decodedata['NIDN'] != '' && decodedata['NIDN'] != null) ? decodedata['NIDN'] : '-';
		            	}
		            	else if(decodedata['TypeUser'] == 'Mahasiswa'){
		            		NIP = (decodedata['NIM'] != '' && decodedata['NIM'] != null) ? decodedata['NIM'] : '-';
		            	}
		            	
		                let html = '<label>'+Nama+'</label><br/><span style="color :blue">'+Email+'</span><br/><span style="color:green">'+NIP+'</span> & <span>'+NIDN+'</span>';
		                return html;
		            }
		        },
		        {
		            'targets': 2,
		            'searchable': false,
		            'orderable': true,
		            'className': 'dt-body-center',
		            'render': function (data, type, full, meta){
		            	let decodedata = jwt_decode(full.data);
		            	// console.log(decodedata);
		            	const AsDosen = (decodedata['F_dosen'] == 1) ? 'Dosen : <i class="fa fa-check-circle" style="color: green;"></i>' : 'Dosen : <i class="fa fa-minus-circle" style="color: red;"></i>';
		            	const AsMHS = (decodedata['F_mhs'] == 1) ? 'Mahasiswa : <i class="fa fa-check-circle" style="color: green;"></i>' : 'Mahasiswa : <i class="fa fa-minus-circle" style="color: red;"></i>';
		            	const AsKolaborasi = (decodedata['F_kolaborasi'] == 1) ? 'Kolaborasi : <i class="fa fa-check-circle" style="color: green;"></i>' : 'Kolaborasi : <i class="fa fa-minus-circle" style="color: red;"></i>';
		            	const AsReviewer = (decodedata['F_reviewer'] == 1) ? 'Reviewer : <i class="fa fa-check-circle" style="color: green;"></i>' : 'Reviewer : <i class="fa fa-minus-circle" style="color: red;"></i>';
		                let html = AsDosen+'<br/>'+
		                		   AsMHS+'<br/>'+
		                		   AsKolaborasi+'<br/>'+
		                		   AsReviewer+'<br/>'+
		                		 '';
		                return html;
		            }
		        },
		        {
		            'targets': 3,
		            'searchable': false,
		            'orderable': true,
		            'className': 'dt-body-center',
		            'render': function (data, type, full, meta){
		            	let html = '<div class="btn-group">' +
            	                   '<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
            	                   '<i class="fa fa-edit"></i> <span class="caret"></span>' +
            	                   '</button>' +
            	                   '<ul class="dropdown-menu">' +
            	                   '<li>'+'<a href = "javascript:void(0)" class = "modalDetail" token = "'+full.data+'">Detail '+'</li>'+
            	                   '<li role="separator" class="divider"></li>'+
            	                   '<li>'+'<a href = "javascript:void(0)" class = "btnEditEksternal" token = "'+full.data+'">Edit '+'</li>'+
            	                   '<li>'+'<a href = "javascript:void(0)" class = "btnRemovetEksternal" token = "'+full.data+'">Remove '+'</li>'+
            	                   '<li role="separator" class="divider"></li>'+
            	                   '<li>'+'<a href = "javascript:void(0)" class = "btnResetPasswordEksternal" token = "'+full.data+'">Reset Password '+'</li>'+
            	                   '</ul>' +
            	                   '</div>';
		                return html;
		            }
		        },
		    ],
		    'createdRow': function(row, data, dataIndex) {
		        
		    },
		    dom: 'l<"toolbar">frtip',
		    "initComplete": function(settings, json) {
		    	          $("div.toolbar")
		    	             .html('<div class="toolbar no-padding pull-right" style = "margin-left : 10px;">'+
					    				    '<select class = "form-control FilterOPtypeUser">'+
												'<option value = "%" selected>All Type User</option>'+
												'<option value = "Dosen">Dosen</option>'+
												'<option value = "Mahasiswa">Mahasiswa</option>'+
												'<option value = "Instansi">Instansi</option>'+
											'</select>'+
		    				'</div>');
		    }
		});

		this.dataTableList = table;
	}

	savePortalEksternal = async(selector,action,ID) => {
		const cls = this;
		const btn = selector.html();
		let data = {};
		let data_validate = [];
		let dataForm  = {};
		$('.FrmRegistrasi').not('div').each(function(e){
			var Name = $(this).attr('name');
			var label = $(this).closest('.form-group').find('label').text();
			var validate = $(this).attr('validate');
			var lengthmin = $(this).attr('lengthmin');
			var typeInput = $(this).attr('type');
			if (!$(this).is("select")) {
				if (typeInput == 'checkbox') {
					if ($(this).is(':checked')) {
						var txt = 1;
					}
					else
					{
						var txt = 0;
					}
				}
				else
				{
					var txt = $(this).val();
				}
				
			}
			else
			{
				var txt = $(this).find('option:selected').val();
			}
			data[Name] = txt;
			var temp = {
				Name : Name,
				label : label,
				validate : validate,
				lengthmin : lengthmin,
				txt : txt,
			}
			data_validate.push(temp);
		})
		data['TypeUser'] = $('.OPtypeUser').find('option:selected').val();

		const validate_data  = (action == 'delete') ? true : cls.validation_data(data_validate);
		if (validate_data) {
			if (confirm('Are you sure ?')) {
				const url = base_url_js+'rest_research/__CRUDUserEksternal';
				let dataform = {
					auth : 's3Cr3T-G4N',
					data : data,
					action : action,
					ID : ID,
				}
				let token = jwt_encode(dataform,'UAP)(*');
				loading_button2(selector);
				const response = await AjaxSubmitFormPromises(url,token);
				if (response.status == 1) {
					cls.dataTableList.ajax.reload(null, false);
					$('.OPtypeUser').trigger('change');
					toastr.success('Success');
				}
				else
				{
					toastr.error(response.msg);
				}
				end_loading_button2(selector,btn);
			}
			
		}
		else
		{
			// console.log('Form required');
		}

	}

	validation_data = (data) => {
		let toastring = '';
		for (var i = 0; i < data.length; i++) {
			var validate = data[i].validate;
			var Name = data[i].Name;
			var label = data[i].label;
			var lengthmin = parseInt(data[i].lengthmin);
			var txt = data[i].txt;
			var result = "";
			    switch(validate)
			    {
			      	case  "" :
			      			continue;
			      	 	break;
			      	case  "required" :
			      		result = Validation_required(txt,label);
			      		if (result['status'] == 0) {
			      		  toastring += result['messages'] + "<br>";
			      		}
			      		else
			      		{
			      			result = Validation_leastCharacter(lengthmin,txt,label);
			      			if (result['status'] == 0) {
			      			  toastring += result['messages'] + "<br>";
			      			}
			      		}	
			      	 	break;
			      	case  "email" :
			      		  result = Validation_required(txt,label);
			      		  if (result['status'] == 0) {
			      		    toastring += result['messages'] + "<br>";
			      		  }
			      		  else
			      		  {
			      		  	result = Validation_email(txt,label);
			      		  	if (result['status'] == 0) {
			      		  	  toastring += result['messages'] + "<br>";
			      		  	}
			      		  }
			      				
			      	 	break;
			    }
		}

		if (toastring != "") {
		  toastr.error(toastring, '!!!Failed');
		  return false;
		}

		return true;
	}
}