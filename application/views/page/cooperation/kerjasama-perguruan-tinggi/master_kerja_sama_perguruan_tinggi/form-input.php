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
				<label style="margin-left: 9px;">Perjanjian</label>
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
			<div class="form-group">
				<label>Desc</label>
				<input type="text" name="Desc" class="form-control input">
			</div>
			<div style="padding: 5px;">
                <button class="btn btn-block btn-success" id="btnSave" mode = "add" data-id = "">Save</button>
            </div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var S_Table_example_  =  '';
	$(document).ready(function() {
		// set data default
		SetDataDefault();	
	})

	function SetDataDefault()
	{
		$('.datetimepicker').datetimepicker({
        	format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
        });
        $('.input:first').focus();
        $('input:not([type="checkbox"])').val('');
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
                 '<th>Code</th>'+
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
			      {
			         'targets': 2,
			         'render': function (data, type, full, meta){
			             return full.Name2;
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
			if (nm != 'BentukKegiatan' && nm != 'ManfaatKegiatan' && type != 'file' && nm != 'Desc') {
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

	    	   if(fsize > 5000000) // 5mb
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
			ker_department : DepartmentSelected,
		}

		console.log(data);
		var token = jwt_encode(data,"UAP)(*");
		form_data.append('token',token);

		console.log(form_data);

		var url = base_url_js + "cooperation/Kerja_Sama_Perguruan_Tinggi_Master/Submit";
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
</script>