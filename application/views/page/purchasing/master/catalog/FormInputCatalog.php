<div class="row" style="margin-right: 10px;margin-left: 10px;margin-top: 10px">
	<div class="form-horizontal">
		<div class="form-group">
			<div class="row">
				<div class="col-xs-2">
				    <label class="control-label">For Departement</label>
				</div>
				<div class="col-xs-3">
					<select class="select2-select-00 full-width-fix" id="Departement">
					     <!-- <option></option> -->
					 </select>
				</div>	 
				<div class="col-xs-2">
				    <label class="control-label">Description</label>
				</div>    
				<div class="col-xs-3">
				   <input type="text" name="Desc" id= "Desc" placeholder="Input Desc" class="form-control" maxlength="50">
				   <span id="charsDesc">50</span> characters remaining
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-xs-2">
				    <label class="control-label">Item Name</label>
				</div>    
				<div class="col-xs-3">
				   <input type="text" name="ItemName" id= "ItemName" placeholder="Input ItemName" class="form-control" maxlength="35">
				   <span id="charsItemName">35</span> characters remaining
				</div>
				<div class="col-xs-2">
				    <label class="control-label">Est Value</label>
				</div> 
				<div class="col-xs-3">
				   <input type="text" name="EstValue" id= "EstValue" class="form-control">
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-xs-2">
				    <label class="control-label">Category</label>
				</div> 
				<div class="col-xs-3">
					<select class="select2-select-00 full-width-fix" id="CategoryCatalog">
					     <!-- <option></option> -->
					 </select>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-xs-2">
				    <label class="control-label">Foto</label>
				</div>    
				<div class="col-xs-3">
				   <input type="file" data-style="fileinput" id="ExFile" multiple>
				   <span><b>JPG,PNG & Max 3 File</b></span>
				</div>
				<?php if ($action == 'edit'): ?>
					<div class="col-xs-3" ID = "ShowPhoto">
					   
					</div>
				<?php endif ?>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-xs-2">
					<label class="control-label">Specification / Detail</label>
				</div>
				<div class="col-xs-2">
					<button class="btn btn-default" id = "addDetail"><i class="icon-plus"></i> Add</button>
				</div>
			</div>
			<div class="row" id = "pageAddDetail" style="margin-right: 0px;margin-left: 0px;margin-top: 10px;">
				<div class="col-md-6 col-md-offset-2">

				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-xs-2">
					<label class="control-label">Add Department</label>
				</div>
				<div class="col-xs-2">
					<button class="btn btn-primary btn-default" id = "addDepartment"><i class="icon-plus"></i> Add</button>
				</div>
			</div>
			<div class="row" id = "pageAddDepartment" style="margin-right: 0px;margin-left: 0px;margin-top: 10px;">
				<div class="col-md-6 col-md-offset-2">
					
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-md-3 col-md-offset-9">
					<button type="button" id="btnSaveForm" class="btn btn-success" action = "">Save</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var S_Table_example_ = '';
	$(document).ready(function() {
		LoadFirst();
	}); // exit document Function

	function LoadFirst()
	{
		$("#ItemName").keyup(function(){
			var maxLength = 35;
			var length = $(this).val().length;
			var length = maxLength-length;
			$('#charsItemName').text(length);
		})

		$("#Desc").keyup(function(){
			var maxLength = 50;
			var length = $(this).val().length;
			var length = maxLength-length;
			$('#charsDesc').text(length);
		})

		getAllDepartementPU();
		getCategoryCatalog();
		ClickFunctionAdd();
		ClickFunctionBtnSave();
		$('#EstValue').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});

		<?php if ($action == 'edit'): ?>
			$("#Desc").val("<?php echo $get[0]['Desc'] ?>");
			$("#ItemName").val("<?php echo $get[0]['Item'] ?>");

			<?php if ($arr_lock > 0): ?>
				$("#Desc").prop('disabled',true);
				$("#ItemName").prop('disabled',true);
			<?php endif ?>

			var ShowPhoto = "<?php echo $get[0]['Photo'] ?>";
			var temp = '';
			if(ShowPhoto != '')
			{
				ShowPhoto = ShowPhoto.split(",");
				temp = '<ul>';
					for (var i = 0; i < ShowPhoto.length; i++) {
						temp += '<li>'+'<a href = "'+base_url_js+'fileGetAny/budgeting-catalog-'+ShowPhoto[i]+'" target = "_blank">'+ShowPhoto[i]+'</a>&nbsp<button class="btn-xs btn-default btn-delete btn-default-warning btn-custom btn-delete-file"  filepath = "budgeting-catalog-'+ShowPhoto[i]+'" type="button" idtable = "<?php echo $get[0]['ID'] ?>" table = "db_purchasing.m_catalog" field = "Photo" typefield = "0" delimiter = "," fieldwhere = "ID"><i class="fa fa-trash" aria-hidden="true"></i></button></li>';
					}
				temp += '</ul>'
			}
			

			$("#ShowPhoto").html(temp);

			var Cost = "<?php echo $get[0]['EstimaValue'] ?>";
			var n = Cost.indexOf(".");
			var Cost = Cost.substring(0, n);
			$("#EstValue").val(Cost);
			$('#EstValue').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
			$('#EstValue').maskMoney('mask', '9894');

			<?php $getDetail = $get[0]['DetailCatalog'] ?>
			<?php if ($getDetail != "" || $getDetail != null): ?>
				var getDetail = [];
			    getDetail =  <?php echo $getDetail ?>;
				if (getDetail != '') {
					var Input = '';
					for(var key in getDetail) {
						Input += '<div class = "row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px">'+
										'<div class="col-md-6 col-md-offset-2">'+
											'<div class="col-xs-4">'+
												'<input type="text" class="form-control addDetailinput" placeholder = "Input Name" value = "'+key+'">'+
											'</div>'+
											'<div class="col-xs-6">'+
												'<input type="text" class="form-control addDetailinput" placeholder = "Input Value" value = "'+getDetail[key]+'">'+
											'</div>'+
											'<div class="col-xs-2">'+
												'<button type="button" class="btn btn-danger btn-delete-detail"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>'+
											'</div>'+	
										'</div>'+
									'</div>';
					}

					$("#pageAddDetail").append(Input);	

					$(".btn-delete-detail").click(function(){
						$(this)
						  .parentsUntil( 'div[class="row"]' ).remove();
					})	
				}
			<?php endif ?>
		<?php endif ?>

	}

	function ClickFunctionAdd()
	{
		$("#addDetail").click(function()
		{
			var Input = '<div class = "row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px">'+
							'<div class="col-md-6 col-md-offset-2">'+
								'<div class="col-xs-4">'+
									'<input type="text" class="form-control addDetailinput" placeholder = "Input Name">'+
								'</div>'+
								'<div class="col-xs-6">'+
									'<input type="text" class="form-control addDetailinput" placeholder = "Input Value">'+
								'</div>'+
								'<div class="col-xs-2">'+
									'<button type="button" class="btn btn-danger btn-delete"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>'+
								'</div>'+	
							'</div>'+
						'</div>';

			$("#pageAddDetail").append(Input);	

			$(".btn-delete").click(function(){
				$(this)
				  .parentsUntil( 'div[class="row"]' ).remove();
			})		
		})
	}

	function getAllDepartementPU()
	{
	  var url = base_url_js+"api/__getAllDepartementPU";
	  $('#Departement').empty();
	  $.post(url,function (data_json) {
	    for (var i = 0; i < data_json.length; i++) {
	        var selected = (i==0) ? 'selected' : '';
	        $('#Departement').append('<option value="'+ data_json[i]['Code']  +'" '+selected+'>'+data_json[i]['Name2']+'</option>');
	    }
	   
	    $('#Departement').select2({
	       //allowClear: true
	    });

	    <?php if ($action == 'edit'): ?>
	    	$("#Departement option").filter(function() {
	    	   //may want to use $.trim in here
	    	   return $(this).val() == '<?php echo $get[0]['Departement'] ?>'; 
	    	 }).prop("selected", true);

	    	$('#Departement').select2({
	    	   //allowClear: true
	    	});
	    <?php endif ?>


	  })
	}

	function getCategoryCatalog()
	{
	  var url = base_url_js+"rest2/__getCategoryCatalog/1";
	  $('#CategoryCatalog').empty();
	  $.post(url,function (data_json) {
	    for (var i = 0; i < data_json.length; i++) {
	        var selected = (i==0) ? 'selected' : '';
	        $('#CategoryCatalog').append('<option value="'+ data_json[i]['ID']  +'" '+selected+'>'+data_json[i]['Name']+'</option>');
	    }
	   
	    $('#CategoryCatalog').select2({
	       //allowClear: true
	    });

	    <?php if ($action == 'edit'): ?>
	    	$("#CategoryCatalog option").filter(function() {
	    	   //may want to use $.trim in here
	    	   return $(this).val() == '<?php echo $get[0]['ID_category_catalog'] ?>'; 
	    	 }).prop("selected", true);

	    	$('#CategoryCatalog').select2({
	    	   //allowClear: true
	    	});
	    <?php endif ?>

	  })
	}

	function file_validation(ID_element)
	{
	    var files = $('#'+ID_element)[0].files;
	    var error = '';
	    var msgStr = '';
	    var max_upload_per_file = 3;
	    if (files.length > max_upload_per_file) {
	      msgStr += '1 Document should not be more than 3 Files<br>';

	    }
	    else
	    {
	      for(var count = 0; count<files.length; count++)
	      {
	       var name = files[count].name;
	       console.log(name);
	       var extension = name.split('.').pop().toLowerCase();
	       if(jQuery.inArray(extension, ['png','jpg','jpeg']) == -1)
	       {
	        var no = parseInt(count) + 1;
	        msgStr += 'File Number '+ no + ' Invalid Type File<br>';
	        //toastr.error("Invalid Image File", 'Failed!!');
	        // return false;
	       }

	       var oFReader = new FileReader();
	       oFReader.readAsDataURL(files[count]);
	       var f = files[count];
	       var fsize = f.size||f.fileSize;
	       console.log(fsize);

	       if(fsize > 2000000) // 2mb
	       {
	        msgStr += 'File Number '+ no + ' Image File Size is very big<br>';
	        //toastr.error("Image File Size is very big", 'Failed!!');
	        //return false;
	       }
	       
	      }
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

	function ClickFunctionBtnSave()
	{
		$("#btnSaveForm").click(function()
		{	
			if (confirm("Are you sure?") == true) {
				loading_button('#btnSaveForm');
				var checkAddDetail = getAddDetail();
				// console.log(checkAddDetail);
				if(checkAddDetail != '')
				{
					var ChkObject = isObject(checkAddDetail);
					if (ChkObject) {
						var checkFile = file_validation('ExFile');
						if (checkFile) {
							saveFileAndData(checkAddDetail);
						}
						
					} else {
						toastr.error('The Data Detail is empty','Failed!!')
					}
				}
				else
				{
					var checkFile = file_validation('ExFile');
					if (checkFile) {
						saveFileAndData(checkAddDetail);
					}
				}
				
			}	
			else {
               
            }

		})
	}

	function validation(arr)
	{
	  var toatString = "";
	  var result = "";
	  for(var key in arr) {
	     switch(key)
	     {
	      case  "Detail" :
	      case  "Action" :
	      case  "Departement" :
	       case  "Desc" :
	            break;
	      case  "Item" :
	            result = Validation_required(arr[key],key);
	            if (result['status'] == 0) {
	              toatString += result['messages'] + "<br>";
	            }
	            break;
	      // case  "Desc" :
	      //       result = Validation_required(arr[key],key);
	      //       if (result['status'] == 0) {
	      //         toatString += result['messages'] + "<br>";
	      //       }
	      //       break; 
          case  "EstimaValue" :
          	// if (arr[key] == 0) {
          	// 	toatString += 'Estimate Value must be higher than 0' + "<br>";
          	// }
          	result = Validation_required(arr[key],key);
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

	function saveFileAndData(Detail)
	{
		var form_data = new FormData();
		var url = base_url_js + "purchasing/page/catalog/saveFormInput";
		var DataArr = {
		                Detail : Detail,
		                Action : "<?php echo $action ?>",
		                Departement : $("#Departement").val(),
		                Item : $("#ItemName").val(),
		                Desc : $("#Desc").val(),
		                EstimaValue : findAndReplace($("#EstValue").val(),".",""),
		                ID_category_catalog : $('#CategoryCatalog').val(),
		                <?php if ($action == 'edit'): ?>
		                	ID : "<?php echo $get[0]['ID'] ?>",
		                <?php endif ?>
		              };

		if (validationInput = validation(DataArr)) {
			var token = jwt_encode(DataArr,"UAP)(*");
			form_data.append('token',token);
			//form_data.append('fileData',fileData);
			var files = $('#ExFile')[0].files;
			for(var count = 0; count<files.length; count++)
			{
			 form_data.append("fileData[]", files[count]);
			}
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
			    if(data.status == 1) {
			      toastr.options.fadeOut = 100000;
			      toastr.success(data.msg, 'Success!');
			      $('.pageAnchor[page="FormInput"]').trigger('click');
			      	if (CountColapses2 == 0) {
			      		$('.pageAnchor[page="DataIntable"]').trigger('click');
			      		// LoadPageCatalog('DataIntable');
  			      	}
			      	else
			      	{
			      		LoadPageCatalog('DataIntable');
			      	}
			    }
			    else
			    {
			      toastr.options.fadeOut = 100000;
			      toastr.error(data.msg, 'Failed!!');
			    }
			  setTimeout(function () {
			      toastr.clear();
			 	$('#btnSaveForm').prop('disabled',false).html('Save');
			    },1000);

			  },
			  error: function (data) {
			    toastr.error(data.msg, 'Connection error, please try again!!');
			    $('#btnSaveForm').prop('disabled',false).html('Save');
			  }
			})

		}
		else
		{
		   $('#btnSaveForm').prop('disabled',false).html('Save');
		}              
		
	}

	function getAddDetail()
	{
		var arr = {};
		if (jQuery(".addDetailinput").length) {
		  var get = [];
		  $('.addDetailinput').each(function(){
		  		get.push(this.value);
		  });
		  var bool = true;
		  for (var i = 0; i < get.length; i= i + 2) {
		  	if (get[i] == '') {
		  		bool = false;
		  		break
		  	} else {
		  		var l = parseInt(i) + 1;
		  		if (get[l] == '') {
		  			bool = false;
		  			break
		  		} else {
		  			arr[get[i]] = get[l];
		  		}
		  	}
		  }

		  if (bool) {
		  	return arr;
		  } else {
		  	arr = [];
		  	return arr;
		  }

		}
		else
		{
			arr = {};
		}

		return arr;
		
	}

	$(document).off('click', '#addDepartment').on('click', '#addDepartment',function(e) {
		var PRCode = $(this).attr('PRCode');
	    var url = base_url_js+'api/__getAllDepartementPU';
   		$.post(url,function (data_json) {
   			ModalTblDepartment(data_json);
		});
		
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
                 '<th>Select</th>'+
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
			             return '<input type="checkbox" name="id[]" value="' + full.Code + '" dt = "'+full.Name1+'" '+checked+'>';
			         }
			      },
			      {
			         'targets': 1,
			         'render': function (data, type, full, meta){
			             return full.Name1;
			         }
			      },
		      ],
		      'createdRow': function( row, data, dataIndex ) {
		      		
		      },
		      // 'order': [[1, 'asc']]
		});

		S_Table_example_ = table;

	}

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

		// write html di pageAddDepartment
		HtmlPageAddDepart(checkboxArr);
		$('#GlobalModalLarge').modal('hide');

	})

	function HtmlPageAddDepart(arr)
	{
		var html = '<ul id ="AddDepartSelected">';
		for (var i = 0; i < arr.length; i++) {
			html += '<li code = "'+arr[i].Code+'">'+arr[i].Name+'</li>';
		}
		html += '</ul>';
		$('#pageAddDepartment').find('.col-md-6').html(html);
	}
</script>