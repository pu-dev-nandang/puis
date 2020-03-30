<div class="row">
	<div class="col-xs-12">
		<div class="well">
			<div class="row">
				<div class="col-md-6">
					<div style="text-align: center;">
						<h4>Edit Exam Submited</h4>
					</div>
					<table class="table">
						<tr>
							<td>Description</td>
							<td> : </td>							
							<td>
								<textarea class="form-control FrmEditExamSubmit" name = "Description" validate = "required" rows="6"></textarea> 
							</td>							
						</tr>
						<tr>
							<td>File</td>
							<td> : </td>							
							<td>
								<label class="btn btn-sm btn-primary">Upload File 
									<input name = "Files" id = "UploadFile" type="file" style="display: none;" accept="application/pdf">
								</label>
								<p style = "color:red">PDF max 5 mb
								<br/>
								<br/>
								<div id = "fileExisting">
									
								</div>
							</td>							
						</tr>
					</table>
					<div style="padding: 10px">
						<button class="btn btn-success btnExamSubmited">Submit</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	const NPM = "<?php echo $NPM ?>"
	const ExamID = "<?php echo $ExamID ?>"
	const dataExisting = <?php echo json_encode($dataExisting) ?>;
	console.log(dataExisting);

	const App_edit_exam_submited = {
		Loaded : () => {
			if (dataExisting.length > 0) {
				$('.FrmEditExamSubmit[name="Description"]').val(dataExisting[0].Description);
				if (dataExisting[0].File != null && dataExisting[0].File != '') {
					$('#fileExisting').html(
							'<a href = "'+base_url_js+'uploads/task-exam/'+dataExisting[0].File+'"  class = "btn btn-default" target = "_blank">File</a>'
						);
				}
			}
		},

		submit : async(selector) => {
			let htmlBtn = selector.html();
			let data = {};
			let dataForm = {};
			let dataValidation = [];

			$('.FrmEditExamSubmit').not('div').each(function(e){
				let name = $(this).attr('name');
				let validate = $(this).attr('validate');
				data[name] = $(this).val();
				let temp = {
					name : name,
					text : $(this).closest('tr').find('td:eq(0)').text(),
					validate : validate,
					txt : $(this).val(),
				}

				dataValidation.push(temp);
			})
			var ArrUploadFilesSelector = [];
			var UploadFile = $('#UploadFile');
			var valUploadFile = UploadFile.val();
			if (valUploadFile) {
			    var NameField = UploadFile.attr('name');
			    var temp = {
			        NameField : NameField,
			        Selector : UploadFile,
			    };
			    ArrUploadFilesSelector.push(temp);
			}

			// console.log(ArrUploadFilesSelector);return;

			const validation = App_edit_exam_submited.validation_data(dataValidation,ArrUploadFilesSelector);
			if (validation) {
				if (confirm('Are you sure ?')) {
					const url = base_url_js+'academic/__editExamSubmited';
					let dataForm = {
						action : 'add_or_edit',
						data : data,
						NPM : NPM,
						ExamID : ExamID,
					}
					// console.log(dataForm);
					const token = jwt_encode(dataForm,'UAP)(*');
					loading_button2(selector);
					try {
					  var response =  await AjaxSubmitFormPromises(url,token,ArrUploadFilesSelector);
					  if (response.status == 1) {
					  	toastr.success('Success');
					  	window.location.replace(base_url_js+'academic/exam-schedule');
					  }
					  else
					  {
					  	toastr.error('Somthing Error','!Error');
					  	end_loading_button2(selector,htmlBtn);
					  }

					  	
					}
					catch(err) {
					  toastr.error('Somthing Error','!Error');
					  end_loading_button2(selector,htmlBtn);
					}

					

				}
			}

		},

		validation_data : (dataValidation,ArrUploadFilesSelector) => {
			let toatString = "";
			
			for (var i = 0; i < dataValidation.length; i++) {
				const validate = dataValidation[i].validate;
				const Name = dataValidation[i].name;
				const label = dataValidation[i].text;
				const txt = dataValidation[i].txt;
				let result = "";
				switch(validate)
			    {
			      	case  "" :
			      			continue;
			      	 	break;
			      	case  "required" :
			      		result = Validation_required(txt,label);
			      		if (result['status'] == 0) {
			      		  toatString += result['messages'] + "<br>";
			      		}
			      	 	break;
				}
				
			}

			// for file
			if (ArrUploadFilesSelector.length>0 && ArrUploadFilesSelector[0].Selector.length) {
			  var selectorfile = ArrUploadFilesSelector[0].Selector
			  var FilesValidation = App_edit_exam_submited.file_validation(selectorfile,'');
			  if (FilesValidation != '') {
			      toatString += FilesValidation + "<br>";
			  }
			  
			}

			if (toatString != "") {
			  toastr.error(toatString, 'Failed!!');
			  return false;
			}
			return true
		},

		file_validation : (ev,TheName='') => {
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
			     if(jQuery.inArray(extension, ['pdf']) == -1)
			     {
			      msgStr += 'Upload File '+TheName + ' Invalid Type File<br>';
			     }

			     var oFReader = new FileReader();
			     oFReader.readAsDataURL(files[count]);
			     var f = files[count];
			     var fsize = f.size||f.fileSize;

			     if(fsize > 5000000) // 5mb
			     {
			      msgStr += 'Upload File '+TheName +  ' Image File Size is very big<br>';
			     }
			     
			    }
			  }
			}
			else
			{
			  msgStr += 'Upload File '+TheName + ' Required';
			}
			return msgStr;
		},
	}

	$(document).ready(function(e){
		App_edit_exam_submited.Loaded();
	})

	$(document).on('click','.btnExamSubmited',function(e){
		const selector = $(this);
		App_edit_exam_submited.submit(selector);
	})
</script>