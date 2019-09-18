<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-primary">
		    <div class="panel-heading clearfix">
		        <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Kerja Sama Perguruan Tinggi</h4>
		    </div>
		    <div class="panel-body">
		        <div class="row">
		        	<div class="col-md-4">
		        		<div class="thumbnail">
		        			<div class="row">
		        				<div class="col-md-12">
		        					<div style="padding: 15px;">
		        						<h3 style="color: red;">Form Input</h3>
		        					</div>
		        					<div class="form-group">
		        						<label>Lembaga  Mitra Kerja Sama</label>
		        						<input type="text" name="Lembaga" class="form-control input">
		        					</div>
		        					<div class="form-group">
		        						<table class="table">
		        							<tr>
		        								<td>
		        									<label>Department</label>
		        									<button class="btn btn-primary btn-default" id = "addDepartment"><i class="icon-plus"></i> Add</button>
		        								</td>
		        								<td style="width: 90%" class="ListDepartmentSelected">
		        									<strong>--Empty Department Selected--</strong> 
		        								</td>
		        							</tr>
		        						</table>
		        					</div>
		        					<div class="form-group">
		        						<div class="row">
		        							<div class="col-xs-12">
		        								<table class="table">
		        									<tr>
		        										<td style="border-top: none;">MOU</td>
		        										<td style="border-top: none;"><input type="checkbox" name="Perjanjian" class="input" value="MOU"></td>
		        										<td style="border-top: none;"><input type="file" data-style="fileinput" class="input" data = "MOU"></td>
		        									</tr>
		        									<tr>
		        										<td style="border-top: none;">MOA</td>
		        										<td style="border-top: none;"><input type="checkbox" name="Perjanjian" class="input" value="MOA"></td>
		        										<td style="border-top: none;"><input type="file" data-style="fileinput" class="input" data = "MOA"></td>
		        									</tr>
		        									<tr>
		        										<td style="border-top: none;">IA</td>
		        										<td style="border-top: none;"><input type="checkbox" name="Perjanjian" class="input" value="IA"></td>
		        										<td style="border-top: none;"><input type="file" data-style="fileinput" class="input" data = "IA"></td>
		        									</tr>
		        								</table>
		        							</div>
		        						</div>
		        					</div>
		        					<div class="form-group">
		        						<label>Kategori</label>
		        						<select class="form-control input" name ="Kategori">
		        							<option disabled selected value="!">--Pilih--</option>
		        							<option value="PKM">PKM</option>
		        							<option value="Penelitian">Penelitian</option>
		        							<option value="Pendidikan">Pendidikan</option>
		        							<option value="Tridarma">Tridarma</option>
		        						</select>
		        					</div>
		        					<div class="form-group">
		        						<label>Tingkat</label>
		        						<select class="form-control input" name ="Tingkat">
		        							<option disabled selected value="!">--Pilih--</option>
		        							<option value="Internasional">Internasional</option>
		        							<option value="Nasional">Nasional</option>
		        							<option value="Lokal">Wilayah/ Lokal</option>
		        						</select>
		        					</div>
		        					<div class="form-group">
		        						<label>Judul Kegiatan</label>
		        						<input type="text" name="JudulKegiatan" class="form-control input">
		        					</div>
		        					<div class="form-group">
		        						<label>Bentuk Kegiatan</label>
		        						<input type="text" name="BentukKegiatan" class="form-control input">
		        					</div>
		        					<div class="form-group">
		        						<label>Manfaat Kegiatan</label>
		        						<input type="text" name="ManfaatKegiatan" class="form-control input">
		        					</div>
		        					<div class="form-group">
		        						<div class="row">
		        							<div class="col-xs-12">
		        								<table class="table">
		        									<tr>
		        										<td>
		        											<label>Bukti</label>
		        											<textarea name="BuktiName" class="form-control input"></textarea>
		        										</td>
		        									</tr>
		        									<tr>
		        										<td style="border-bottom: 1px solid #ddd;border-top: none;">
		        											<input type="file" data-style="fileinput" class="input" name="BuktiUpload">
		        										</td>
		        									</tr>
		        								</table>
		        							</div>
		        						</div>
		        					</div>
		        					<div class="form-group">
		        						<div class="row">
		        							<div class="col-xs-12">
		        								<table class="table">
		        									<tr>
		        										<td style="border-top: none;">
		        											<label>Start Date</label>
															<div class="input-group input-append date datetimepicker">
									                            <input data-format="yyyy-MM-dd" class="form-control input" type=" text" readonly="" value = "" name = "StartDate">
									                            <span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
									                		</div>
		        										</td>
		        										<td style="border-top: none;">
		        											<label>End Date</label>
															<div class="input-group input-append date datetimepicker">
									                            <input data-format="yyyy-MM-dd" class="form-control input" type=" text" readonly="" value = "" name = "EndDate">
									                            <span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
									                		</div>
		        										</td>
		        									</tr>
		        								</table>
		        							</div>
		        						</div>
		        					</div>
									<div style="padding: 5px;">
				                        <button class="btn btn-block btn-success" id="btnSave" mode = "add" data-id = "">Save</button>
				                    </div>
		        				</div>
		        			</div>
		        		</div>
		        	</div>
		        	<div class="col-md-8">
		        		<div class="thumbnail">
		        			<div class="row" style="margin-left: 0px;margin-right: 0px;">
		        				<div class="col-md-12">
		        					<div style="padding: 15px;">
		        						<h3 style="color: red;">Data</h3>
		        					</div>
		        					<div class="row">
		        						<div class="col-md-12">
		        							<div class="well">
		        								<div style="color: red;"><b>Filtering</b></div>
			        							<div class="row" style="margin-top: 10px;">
			        								<div class="col-md-3">
			        									<div class="thumbnail">
			        										<div class="form-group">
			        											<label>Kategori</label>
			        											<select class="form-control SearchKategori" name ="Kategori">
			        												<option selected value="%">--All--</option>
			        												<option value="PKM">PKM</option>
			        												<option value="Penelitian">Penelitian</option>
			        												<option value="Pendidikan">Pendidikan</option>
			        												<option value="Tridarma">Tridarma</option>
			        											</select>
			        										</div>
															<div class="form-group">
																<label>Tingkat</label>
																<select class="form-control SearchTingkat" name ="Kategori">
																	<option selected value="%">--All--</option>
																	<option value="Internasional">Internasional</option>
																	<option value="Nasional">Nasional</option>
																	<option value="Lokal">Wilayah/ Lokal</option>
																</select>
															</div>
			        									</div>
			        								</div>
			        								<div class="col-md-3">
			        									<div class="thumbnail">
				        									<div class="form-group">
				        										<label>Perjanjian</label>
				        										<table class="">
																	<tr>
																		<td style="border-top: none;">MOU</td>
																		<td style="border-top: none;"><input type="checkbox" name="Perjanjian" class="SearchPerjanjian" value="MOU"></td>
																	</tr>
																	<tr>
																		<td style="border-top: none;">MOA</td>
																		<td style="border-top: none;"><input type="checkbox" name="Perjanjian" class="SearchPerjanjian" value="MOA"></td>
																	</tr>
																	<tr>
																		<td style="border-top: none;">IA</td>
																		<td style="border-top: none;"><input type="checkbox" name="Perjanjian" class="SearchPerjanjian" value="IA"></td>
																	</tr>
				        										</table>
				        									</div>
			        									</div>
			        								</div>
			        								<div class="col-md-6">
			        									<div class="thumbnail">
			        										<table class="table">
			        											<tr>
			        												<td>
			        													<label class="checkbox-inline">
			        														<input type="checkbox" class="dateOP" name="dateOP" id="dateOPRange" value="0">
			        														Date range
			        													</label>
			        												</td>
			        												<td>
					        											<label>Start Date</label>
																		<div class="input-group input-append date datetimepicker">
												                            <input data-format="yyyy-MM-dd" class="form-control SearchDate" type=" text" readonly="" value = "" name = "StartDate">
												                            <span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
												                		</div>
			        												</td>
			        												<td>
					        											<label>End Date</label>
																		<div class="input-group input-append date datetimepicker">
												                            <input data-format="yyyy-MM-dd" class="form-control SearchDate" type=" text" readonly="" value = "" name = "EndDate">
												                            <span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
												                		</div>
			        												</td>
			        											</tr>
			        										</table>
			        										<div align="right">
			        											<button class="btn btn-primary SearchDateBtn"><i class="fa fa-search"></i> Search</button>
			        										</div>
			        									</div>
			        								</div>
			        							</div>
		        							</div>
		        						</div>
		        						<!-- <div class="col-md-2" align="right">
		        							Export Excel
		        						</div> -->
		        					</div>
		        					<div class="row" style="margin-top: 5px;">
		        						<div class="table-responsive">
		        						<div class="col-md-12">
											<table class="table table-bordered" id="TblKerjaSama">
												<thead>
													<tr>
														<th>No</th>
														<th>Lembaga</th>
														<!-- <th>Kategori</th> -->
														<!-- <th>Tingkat</th> -->
														<th>Judul Kegiatan</th>
														<!-- <th>Bentuk Kegiatan</th> -->
														<!-- <th>Manfaat Kegiatan</th> -->
														<th>Bukti</th>
														<th>Date</th>
														<th>Perjanjian</th>
														<th>Department</th>
														<th><i class="fa fa-cog"></i></th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>
		        						</div>
		        						</div>
		        					</div>
		        				</div>
		        			</div>
		        		</div>
		        	</div>
		        </div>   
		    </div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var S_Table_example_ = '';
	var QueryPass = '';
	$("#container").attr('class','fixed-header sidebar-closed');
	$(document).ready(function() {
		LoadFirstLoad();
	})

	function LoadFirstLoad()
	{
		loadingStart();
		LoadDataForTable();
		// set data default
		SetDataDefault();
		loadingEnd(1000);
	}

	function SetDataDefault()
	{
		$('.datetimepicker').datetimepicker({
        	format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
        });
        $('.input:first').focus();
        $('input:not([type="checkbox"])').val('');
        // $('select[class="input"] option').filter(function() {
        //    //may want to use $.trim in here
        //    return $(this).val() == '!'; 
        //  }).prop("selected", true);

        $('.input[type="checkbox"]').prop('checked',false);
        $('textarea').val('');
        $('#btnSave').attr('mode','add');
        $('#btnSave').attr('data-id','');
        $('.divFile').remove();
        $('.divFileUpload').remove();
        $('.ListDepartmentSelected').html('<strong>--Empty Department Selected--</strong>');
	}

	$(document).off('click', '#addDepartment').on('click', '#addDepartment',function(e) {
		load_data_department_ajax().then(function(data_json){
			ModalTblDepartment(data_json);
		})
	})

	function ModalTblDepartment(dt)
	{
		// get all list department existing first for edit
			var listDepartmentSelected = [];
			if ($('#AddDepartSelected').length) {
				$('#AddDepartSelected li').each(function(){
					var c = $(this).attr('code');
					listDepartmentSelected.push(c);
				})
			}
			
		var html = '';
		html ='<div class = "row">'+
				'<div class = "col-md-12">'+
					'<table id="example_budget" class="table table-bordered display select" cellspacing="0" width="100%">'+
           '<thead>'+
              '<tr>'+
                 '<th>Select &nbsp <input type="checkbox" name="select_all" value="1" id="example-select-all"></th>'+
                 '<th>Departement</th>'+
              '</tr>'+
           '</thead>'+
      '</table></div></div>';

		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Select Budget'+'</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>'+
			'<button type="button" id="ModalbtnSaveForm" class="btn btn-success">Save</button>');
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});

		var table = $('#example_budget').DataTable({
		      "data" : dt,
		      'columnDefs': [
			      {
			         'targets': 0,
			         'searchable': false,
			         'orderable': false,
			         'className': 'dt-body-center',
			         'render': function (data, type, full, meta){
			         	 var checked = '';
			         	 for (var i = 0; i < listDepartmentSelected.length; i++) {
			         	 	if (full.Code == listDepartmentSelected[i]) {
			         	 		checked = 'checked';
			         	 		break;
			         	 	}
			         	 }
			             return '<input type="checkbox" name="id[]" value="' + full.Code + '" dt = "'+full.Abbr+'" '+checked+'>';
			         }
			      },
			      {
			         'targets': 1,
			         'render': function (data, type, full, meta){
			             return full.Abbr;
			         }
			      },
		      ],
		      'createdRow': function( row, data, dataIndex ) {
		      		
		      },
		      // 'order': [[1, 'asc']]
		});

		S_Table_example_ = table;

	}

	// Handle click on "Select all" control
	$(document).off('click', '#example-select-all').on('click', '#example-select-all',function(e) {
	   // Get all rows with search applied
	   var rows = S_Table_example_.rows({ 'search': 'applied' }).nodes();
	   // Check/uncheck checkboxes for all rows in the table
	   $('input[type="checkbox"]', rows).prop('checked', this.checked);
	});

	$(document).off('click', '#ModalbtnSaveForm').on('click', '#ModalbtnSaveForm',function(e) {
		var checkboxArr = [];
		S_Table_example_.$('input[type="checkbox"]').each(function(){
			if(this.checked){
				var v = $(this).val();
				var n = $(this).attr('dt');
				var temp = {
					Code : v,
					Name : n,
				};

				checkboxArr.push(temp);
			}
		}); // exit each function

		// write html Department Selected
		var selector = $('.ListDepartmentSelected');
		HtmlPageDepartmentSelected(checkboxArr,selector);
		$('#GlobalModalLarge').modal('hide');

	})

	function HtmlPageDepartmentSelected(arr,selector,classdt='input_li')
	{
		var html = '<div class = "row">';
		var MaxRow = 11;
		var Total = arr.length;
		var split = parseInt(Total / MaxRow);
		var sisa = Total % MaxRow;
		if (sisa > 0) {
		    split++;
		}

		var col = parseInt(12 / split);
		var sisa = 12 % split;
		if (sisa > 0) {
			col--;
		}

		var r = 0;
		for (var x = 0; x < split; x++) {
			var lihtml = '<ul class ="'+classdt+'" style ="margin-left:-30px;">';
			for (var z = 0; z < MaxRow; z++) {
				// console.log(r);
				if (r == Total) {
					break;
				}
				lihtml += '<li code = "'+arr[r].Code+'">'+arr[r].Name+'</li>';
				r++;	
			}
			lihtml += '</ul>';
			html += '<div class = "col-md-'+col+'" >'+lihtml+'</div>';
		}

		html += '</div>';

		selector.html(html);	

	}

	$(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
		var mode = $(this).attr('mode');
		var data_id = $(this).attr('data-id');
		if (validation_input(mode)) {
			var el = '#btnSave';
			loading_button(el);
			SubmitData(el,mode,data_id);
		}
	})

	function validation_input(mode)
	{
		var bool = true;
		// filter input
		$('.input').each(function(){
			var nm = $(this).attr('name');
			var v = $(this).val();
			var type = $(this).attr('type');
			if (nm != 'BentukKegiatan' && nm != 'ManfaatKegiatan' && type != 'file') {
				if (v =='' || v == '!' || v == undefined) {
					toastr.error(nm+' required','!Error Input');
					bool = false;
					return false;
				}
			}
		})

		// filter input_li
		if (bool) {
			if (!$('.input_li').length) {
				toastr.error('Please choose Department','!Error Input');
				bool = false;
			}
		}

		// filter perjanjian required file
		var c = 0;
		$('.input[name="Perjanjian"]').each(function(){
			if(this.checked){
				var tr = $(this).closest('tr');
				var S_file = tr.find('td:eq(2)').find('input');
				var NameUpload = S_file.attr('data');
				if (mode == 'add') {
					if (!file_validation2(S_file,NameUpload) ) {
					  bool = false;
					  return false;
					}
				}
				c++;
			}
		})

		if (bool) {
			if (c==0) {
				toastr.error('Please choose MOU/MOA and IA','!Error Input');
				bool = false;
			}
		}

		if (bool) {
			// bukti upload
			var S_file = $('.input[name="BuktiUpload"]');
			var NameUpload = 'BuktiUpload';
			if (mode == 'add') {
				if (!file_validation2(S_file,NameUpload) ) {
				  bool = false;
				  return false;
				}
			}

		}	

		return bool;
	}

	function file_validation2(ev,TheName = '')
	{
	    var files = ev[0].files;
	    var error = '';
	    var msgStr = '';
	    var max_upload_per_file = 4;
	    if (files.length > 0) {
	    	if (files.length > max_upload_per_file) {
	    	  msgStr += 'Upload File '+TheName + ' 1 Document should not be more than 4 Files<br>';

	    	}
	    	else
	    	{
	    	  for(var count = 0; count<files.length; count++)
	    	  {
	    	   var no = parseInt(count) + 1;
	    	   var name = files[count].name;
	    	   var extension = name.split('.').pop().toLowerCase();
	    	   if(jQuery.inArray(extension, ['jpg' ,'png','jpeg','pdf','doc','docx']) == -1)
	    	   {
	    	    msgStr += 'Upload File '+TheName + ' Invalid Type File<br>';
	    	    //toastr.error("Invalid Image File", 'Failed!!');
	    	    // return false;
	    	   }

	    	   var oFReader = new FileReader();
	    	   oFReader.readAsDataURL(files[count]);
	    	   var f = files[count];
	    	   var fsize = f.size||f.fileSize;
	    	   // console.log(fsize);

	    	   if(fsize > 2000000) // 2mb
	    	   {
	    	    msgStr += 'Upload File '+TheName +  ' Image File Size is very big<br>';
	    	    //toastr.error("Image File Size is very big", 'Failed!!');
	    	    //return false;
	    	   }
	    	   
	    	  }
	    	}
	    }
	    else
	    {
	    	msgStr += 'Upload File '+TheName + ' Required';
	    }
	    

	    if (msgStr != '') {
	      toastr.error(msgStr, 'Failed!!');
	      return false;
	    }
	    else
	    {
	      return true;
	    }
	}

	function SubmitData(el,mode,data_id)
	{
		// Form
		var form_data = new FormData();

		var kerjasama = {};
		$('.input:not([type="checkbox"]):not([type="file"])').each(function(){
			var nm = $(this).attr('name');
			var v = $(this).val();
			kerjasama[nm] = v;	
			// console.log(nm);
		})

		console.log(kerjasama);

		var DepartmentSelected = [];
		$('.input_li li').each(function(){
			var v =$(this).attr('code');
			DepartmentSelected.push(v);
		})

		console.log(DepartmentSelected);

		var Perjanjian = [];
		$('.input[name="Perjanjian"]').each(function(){
			if(this.checked){
				var v =$(this).val();
				Perjanjian.push(v);
				var tr = $(this).closest('tr');
				var S_file = tr.find('td:eq(2)').find('input');

				if ( S_file.length ) {
					var UploadFile = S_file[0].files;
					form_data.append("Upload_"+v+"[]", UploadFile[0]);
				}
			}
		})

		console.log(Perjanjian);

		// BuktiUpload
		var S_file = $('.input[name="BuktiUpload"]');
		if ( S_file.length ) {
			var UploadFile = S_file[0].files;
			form_data.append("BuktiUpload[]", UploadFile[0]);
		}

		var data = {
			ID : data_id,
			mode : mode,
			kerjasama : kerjasama,
			k_perjanjian : Perjanjian,
			k_department : DepartmentSelected,
		}

		console.log(data);
		var token = jwt_encode(data,"UAP)(*");
		form_data.append('token',token);

		console.log(form_data);

		var url = base_url_js + "cooperation/Kerja_Sama_Perguruan_Tinggi/Submit";
		$.ajax({
		  type:"POST",
		  url:url,
		  data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
		  contentType: false,       // The content type used when sending data to the server.
		  cache: false,             // To unable request pages to be cached
		  processData:false,
		  dataType: "json",
		  success:function(data)
		  {
		  	if (data.Status == 0) {
		  		toastr.error("Connection Error, Please try again", 'Error!!');
			}
		  	else{
		  		LoadDataForTable();
		  		SetDataDefault();
		  		toastr.success('Data Saved');
			}

			$(el).prop('disabled',false).html('Save');
		 },	
		 error: function (data) {
		    toastr.error("Connection Error, Please try again", 'Error!!');
		    $(el).prop('disabled',false).html('Save');
		 }
		})
	}

	function LoadDataForTable()
	{
		QueryPass = '';
		var SearchPerjanjian = [];
		$('.SearchPerjanjian:checked').each(function(){
			var v = $(this).val();
			SearchPerjanjian.push(v);
		})

		var StartDate = $('.SearchDate[name="StartDate"]').val();
		var EndDate = $('.SearchDate[name="EndDate"]').val();

		var SearchKategori = $('.SearchKategori option:selected').val();
		var SearchTingkat = $('.SearchTingkat option:selected').val();
		var data = {
		    auth : 's3Cr3T-G4N',
		    SearchPerjanjian : SearchPerjanjian,
		    SearchKategori : SearchKategori,
		    SearchTingkat : SearchTingkat,
		    StartDate : StartDate,
		    EndDate : EndDate,
		};
		var token = jwt_encode(data,"UAP)(*");
		$('#TblKerjaSama tbody').empty();

		var table = $('#TblKerjaSama').DataTable({
			"fixedHeader": true,
		    "processing": true,
		    "destroy": true,
		    "serverSide": true,
	      "lengthMenu": [[5], [5]],
		    "iDisplayLength" : 5,
		    "ordering" : false,
	      "language": {
	          "searchPlaceholder": "Search",
	      },
		    "ajax":{
		        url : base_url_js+"rest2/__get_data_kerja_sama_perguruan_tinggi", // json datasource
		        ordering : false,
		        type: "post",  // method  , by default get
		        data : {token : token},
		        error: function(){  // error handling
		            $(".employee-grid-error").html("");
		            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
		            $("#employee-grid_processing").css("display","none");
		        }
		    },
	   	    'createdRow': function( row, data, dataIndex ) {
	   	    	// console.log(data);
	   	    	var Bukti = data[3];
	   	    	var a = Bukti.split('--');
	   	    	var html = '';
	   	    	var File = jQuery.parseJSON(a[1]);
	   	    	html = a[0]+'<br>'+'<a href = "'+base_url_js+'fileGetAny/cooperation-'+File[0]+'" target="_blank" class = "Fileexist">File</a>';
	   	    	$( row ).find('td:eq(3)').html(html);

	   	    	var Perjanjian = data[5];
	   	    	html = '';
	   	    	var cc = Perjanjian.split(',');
	   	    	for (var i = 0; i < cc.length; i++) {
	   	    		var zc = cc[i];
	   	    		a = zc.split('--');
	   	    		File = jQuery.parseJSON(a[1]);
	   	    		html += '<li>'+a[0]+'<br>'+'<a href = "'+base_url_js+'fileGetAny/cooperation-'+File[0]+'" target="_blank" class = "Fileexist" style="margin-left:19px;">File</a></li>';
	   	    	}
	   	    	
	   	    	$( row ).find('td:eq(5)').html(html);

	   	    	var Departement = data[6];
	   	    	html = '';
	   	    	cc = Departement.split(',');
	   	    	var arr = [];
	   	    	for (var i = 0; i < cc.length; i++) {
	   	    		var zc = cc[i];
	   	    		a = zc.split('--');
	   	    		var temp = {
	   	    			Code : a[0],
	   	    			Name : a[1],
	   	    		}
	   	    		arr.push(temp);
	   	    	}
	   	    	var selector = $( row ).find('td:eq(6)');
	   	    	HtmlPageDepartmentSelected(arr,selector,'listtbl');

	   	    	var tokenEdit = data[9];

	   	    	html = '<div class="btn-group">  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">    <i class="fa fa-pencil"></i> <span class="caret"></span>  </button>  <ul class="dropdown-menu">    <li><a href="javascript:void(0);" class="btnEdit" data-id="'+data[7]+'" tokenedit="'+tokenEdit+'"><i class="fa fa fa-edit"></i> Edit</a></li>    <li role="separator" class="divider"></li>    <li><a href="javascript:void(0);" class="btnRemove" data-id="'+data[7]+'"><i class="fa fa fa-trash"></i> Remove</a></li>  </ul></div>';
	   	    	$( row ).find('td:eq(7)').html(html);

	   	    	if (QueryPass == '') {
	   	    		QueryPass = data[8];
	   	    	}
	   	    },
	        dom: 'l<"toolbar">frtip',
	   	    "initComplete": function(settings, json) {

	   	    }
		});
	}

	$(document).off('change', '.SearchTingkat,.SearchKategori').on('change', '.SearchTingkat,.SearchKategori',function(e) {
		LoadDataForTable();
	})

	$(document).off('click', '.SearchPerjanjian').on('click', '.SearchPerjanjian',function(e) {
		LoadDataForTable();
	})

	$(document).off('click', '#dateOPRange').on('click', '#dateOPRange',function(e) {
		if (this.checked) {
			var datee = "<?php echo date('Y-m-d') ?>";
			$('.SearchDate').val(datee);
			// $('.SearchDateBtn').prop('disabled',false);
		}
		else
		{
			$('.SearchDate').val('');
			// $('.SearchDateBtn').prop('disabled',true);
		}
	})

	$(document).off('click', '.SearchDateBtn').on('click', '.SearchDateBtn',function(e) {
		LoadDataForTable();
	})

	$(document).off('click', '.btnEdit').on('click', '.btnEdit',function(e) {
		var ID = $(this).attr('data-id');
		var tokenedit = $(this).attr('tokenedit');
		// decode data
		var dt = jwt_decode(tokenedit);
		FormEditSelected(dt);
	});

	$(document).off('click', '.btnRemove').on('click', '.btnRemove',function(e) {
		var ID = $(this).attr('data-id');
		var url = base_url_js+'cooperation/Kerja_Sama_Perguruan_Tinggi/Submit';
		var data = {
		    ID : ID,
		    mode : 'delete',
		};
		if (confirm('Are you sure ?')) {
			var token = jwt_encode(data,"UAP)(*");
			$.post(url,{ token:token },function (resultJson) {
			  	if (data.Status == 0) {
			  		toastr.error("Connection Error, Please try again", 'Error!!');
				}
			  	else{
			  		LoadDataForTable();
			  		SetDataDefault();
			  		toastr.success('The data has been deleted');
				}
			}).fail(function() {
			  toastr.info('No Result Data'); 
			}).always(function() {
			                
			});	
		}
	});

	 function FormEditSelected(dt)
	 {
	 	console.log(dt);
	 	for (key in dt){
	 		if (key != 'Perjanjian' && key != 'DepartmentKS' && key != 'BuktiUpload' && key != 'KerjasamaID') {
	 			if (key == 'Kategori' || key == 'Tingkat') {
	 				$('.input[name="'+key+'"] option').filter(function() {
	 				   //may want to use $.trim in here
	 				   return $(this).val() == dt[key]; 
	 				}).prop("selected", true);
	 			}
	 			else
	 			{
	 				// console.log(dt[key]);
	 				$('.input[name="'+key+'"]').val(dt[key]);
	 			}
	 		}
	 		else
	 		{
	 			switch(key) {
	 			  case 'DepartmentKS':
	 			    var rsPass = [];
	 			    var arr = dt[key].split(',');
	 			    for (var i = 0; i < arr.length; i++) {
	 			    	var d = arr[i];
	 			    	var cc = d.split('--');
	 			    	var temp = {
	 			    		Code : cc[0],
	 			    		Name : cc[1],
	 			    	};

	 			    	rsPass.push(temp);
	 			    }
	 			    var selector = $('.ListDepartmentSelected');
	 			    HtmlPageDepartmentSelected(rsPass,selector);
	 			    break;
	 			  case 'Perjanjian':
	 			  	var rsPass = [];
	 			  	var Sper = $('.input[name="Perjanjian"]');
	 			  	Sper.prop('checked',false);
	 			  	$('.divFile').remove();
	 			  	var arr = dt[key].split(',');
	 			  	for (var i = 0; i < arr.length; i++) {
	 			  		var d = arr[i];
	 			  		var cc = d.split('--');
	 			  		var v = cc[0];
	 			  		var f = cc[1];
	 			  		f = jQuery.parseJSON(f);
	 			  		f = '<a href = "'+base_url_js+'fileGetAny/cooperation-'+f[0]+'" target="_blank" class = "Fileexist">File</a>';
	 			  		var IDP = cc[2];
	 			  		Sper.each(function(){
	 			  			if (this.value == v) {
	 			  				$(this).prop('checked',true);
	 			  				var tr = $(this).closest('tr');
	 			  				//console.log(tr);
	 			  				if (tr.find('td:eq(2)').find('.divFile').length  ) {
	 			  					tr.find('td:eq(2)').find('.divFile').remove();
	 			  				}

	 			  				tr.find('td:eq(2)').append('<div class="divFile">'+f+'</div>');
	 			  			}
	 			  		})
	 			  	}
	 			  break;
	 			  case 'BuktiUpload':
	 			  	var td = $('input[name="'+key+'"]').closest('td');
	 			  	if (td.find('.divFileUpload').length  ) {	
	 			  		td.find('.divFileUpload').remove();
	 			  	}

	 			  	var f = dt[key];
	 			  	f = jQuery.parseJSON(f);
	 			  	f = '<a href = "'+base_url_js+'fileGetAny/cooperation-'+f[0]+'" target="_blank" class = "Fileexist">File</a>';	
	 			  	td.append('<div class="divFileUpload">'+f+'</div>');
	 			  break;
	 			  case 'KerjasamaID':
	 			  	$('#btnSave').attr('mode','edit');
	 			  	$('#btnSave').attr('data-id',dt[key]);
	 			  break;
	 			  default:
	 			    // code block
	 			}
	 		}
	 	}
	 }

</script>
