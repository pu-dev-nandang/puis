<div class="row" style="margin-right: 10px;margin-left: 10px;margin-top: 10px">
	<div class="form-horizontal">
		<div class="form-group">
			<div class="row">
				<div class="col-xs-2">
				    <label class="control-label">Name</label>
				</div>    
				<div class="col-xs-3">
				   <input type="text" name="Name" id= "Name" placeholder="Input Name" class="form-control" maxlength="35">
				   <span id="charsItemName">35</span> characters remaining
				</div>
				<div class="col-xs-2">
				    <label class="control-label">Days</label>
				</div> 
				<div class="col-xs-3">
				   <input type="text" name="Days" id= "Days" class="form-control">
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
		$("#Name").keyup(function(){
			var maxLength = 35;
			var length = $(this).val().length;
			var length = maxLength-length;
			$('#charsItemName').text(length);
		})

		ClickFunctionBtnSave();
		$('#Days').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});

		<?php if ($action == 'edit'): ?>
			$("#Name").val("<?php echo $get[0]['Name'] ?>");
			$("#Days").val("<?php echo $get[0]['Days'] ?>");
		<?php endif ?>

	}

	function ClickFunctionBtnSave()
	{
	    $(document).off('click', '#btnSaveForm').on('click', '#btnSaveForm',function(e) {
			if (confirm("Are you sure?") == true) {
				loading_button('#btnSaveForm');
				saveFileAndData__();
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
          case  "Name" :
          case  "Days" :
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

	function saveFileAndData__(Detail)
	{
		var Name = $('#Name').val();
		var Days = $('#Days').val();
		Days = findAndReplace(Days, ".","");

		var form_data = new FormData();
		var url = base_url_js + "purchasing/page/catalog/saveFormInput_category";
		var DataArr = {
		                Name : Name,
		                Days : Days,
		                Action : "<?php echo $action ?>",
		                <?php if ($action == 'edit'): ?>
		                	ID : "<?php echo $get[0]['ID'] ?>",
		                <?php endif ?>
		              };

		if (validationInput = validation(DataArr)) {
			var token = jwt_encode(DataArr,"UAP)(*");
			form_data.append('token',token);
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
			      $('.pageAnchor[page="FormInputCategory"]').trigger('click');
			      	if (CountColapses_2 == 0) {
			      		$('.pageAnchor[page="DataIntableCategory"]').trigger('click');
			      		// LoadPageCatalog('DataIntable');
			      		// console.log('DataIntableCategory');
  			      	}
			      	else
			      	{
			      		// console.log('LoadPageCatalogCategory');
			      		LoadPageCatalogCategory('DataIntableCategory');
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
</script>