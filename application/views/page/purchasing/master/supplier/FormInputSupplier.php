<div class="row" style="margin-right: 10px;margin-left: 10px;margin-top: 10px">
	<p class="hide" id = "legendCode" style="color: red"><strong>The Code Auto will be get it after submit</strong></p>
	<div class="form-horizontal">
		<div class="form-group">
			<div class="row">
				<div class="col-xs-2">
				    <label class="control-label">Code</label>
				</div>
				<?php if ($action == 'add'): ?>
				  <div class="col-sm-6">
				    <strong>Code Automatic</strong>
				  </div>
				<?php else: ?>
				    <div class="col-sm-6">
				      <strong>Code Manual</strong>
				    </div>  
				<?php endif ?>
			</div>
			<div class="row <?php echo $a = ($action == 'add') ? '' : 'hide' ?>">
				<div class="col-xs-3 col-md-offset-2">
				  <input type="checkbox" class="NeedPrefix" name="NeedPrefix" value="0" <?php echo $a = ($action == 'add') ? '' :'checked' ?> >&nbsp; No
				  <input type="checkbox" class="NeedPrefix" name="NeedPrefix" value="1">&nbsp; Yes
				</div>
			</div>
			<div class="row <?php echo $a = ($action == 'add') ? 'hide' :'' ?>" id = "rowCodePost">
			  <div class="col-xs-3 col-md-offset-2">
			    <input type="text" name="CodeSupplier" id= "CodeSupplier" placeholder="Code" class="form-control">
			  </div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-xs-2">
				    <label class="control-label">NamaSupplier</label>
				</div>    
				<div class="col-xs-3">
				   <input type="text" name="Desc" id= "NamaSupplier" placeholder="Input NamaSupplier" class="form-control" maxlength="40">
				   <span id="charsNamaSupplier">40</span> characters remaining
				</div>
				<div class="col-xs-1">
				    <label class="control-label">Alamat</label>
				</div> 
				<div class="col-xs-5">
				   <input type="text" name="Alamat" id= "Alamat" placeholder="Input Alamat" class="form-control" maxlength="100">
				   <span id="charsAlamat">100</span> characters remaining
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-xs-2">
				    <label class="control-label">PIC Name</label>
				</div>    
				<div class="col-xs-3">
				   <input type="text" name="PICName" id= "PICName" placeholder="Input PIC Name" class="form-control">
				</div>
				<div class="col-xs-1">
				    <label class="control-label">Jabatan PIC</label>
				</div>    
				<div class="col-xs-3">
				   <input type="text" name="Jabatan" id= "Jabatan" placeholder="Input Jabatan PIC" class="form-control">
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-xs-2">
				    <label class="control-label">Website</label>
				</div>    
				<div class="col-xs-3">
				   <input type="text" name="Desc" id= "Website" placeholder="Input Website" class="form-control">
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-xs-2">
					<label class="control-label">NoTelp</label>
				</div>
				<div class="col-xs-3">
					<div class="input-group">
						<span class="input-group-btn">
							<button class="btn btn-default SearchPostBudget" type="button">+
							</button>
						</span>
						<input type="text" name="NoTelp" id= "NoTelp" placeholder="Input NoTelp" class="form-control" value="62">
					</div>
				</div>
				<div class="col-xs-1">
					<label class="control-label">NoHp</label>
				</div>
				<div class="col-xs-3">
					<div class="input-group">
						<span class="input-group-btn">
							<button class="btn btn-default SearchPostBudget" type="button">+
							</button>
						</span>
						<input type="text" name="NoHp" id= "NoHp" placeholder="Input NoHp" class="form-control" value="62">
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-xs-2">
					<label class="control-label">Detail Info(Dynamic Input)</label>
				</div>
				<div class="col-xs-2">
					<button class="btn btn-default" id = "addDetailInfo"><i class="icon-plus"></i> Add</button>
				</div>
			</div>
			<div class="row" id = "pageAddDetailInfo" style="margin-right: 0px;margin-left: 0px;margin-top: 10px;">
				<div class="col-md-6 col-md-offset-2">

				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-xs-2">
					<label class="control-label">CategorySupplier</label>
				</div>
				<div class="col-xs-3">
				   <select class="full-width-fix" id="CategorySupplier">
                        <option></option>
                    </select>
				</div>
				<div class="col-xs-6">
				    <button class="btn btn-default btn-default-success btnAddCollapseCategory btn-custom" type="button">
				        <i class="fa fa-plus-circle" aria-hidden="true"></i>
				    </button>
				    <button class="btn btn-default btn-default-primary btnEditCollapseCategory btn-custom" type="button">
				        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
				    </button>
				    <button class="btn btn-default btn-default-warning btnDeleteCategory btn-custom" type="button">
				        <i class="fa fa-trash" aria-hidden="true"></i>
				    </button>
				</div>
			</div>
			<div class="row">
			    <div class="col-xs-3 col-md-offset-2">
			        <div class="collapse" id="addCategory" style="margin-top: 10px;">
			            <div class="well">
			                <div class="row">
			                    <div class="col-xs-9">
			                        <input class="form-control" id="CategoryName" placeholder="Input Category Supplier...">
			                    </div>
			                    <label class="col-xs-2">
			                        <a href="javascript:void(0)" id="btnAddCategoryName" class="btn btn-default btn-block btn-default-success"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
			                    </label>
			                </div>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-xs-2">
					<label class="control-label">Detail Item(Dynamic Input)</label>
				</div>
				<div class="col-xs-2">
					<button class="btn btn-default" id = "addDetailItem"><i class="icon-plus"></i> Add</button>
				</div>
			</div>
			<div class="row" id = "pageAddDetailItem" style="margin-right: 0px;margin-left: 0px;margin-top: 10px;">
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
	$(document).ready(function() {
		LoadFirst();
		$('#NoTelp,#NoHp').maskMoney({thousands:'', decimal:'', precision:0,allowZero: true});
		$('#NoTelp,#NoHp').maskMoney('mask', '9894');
	}); // exit document Function

	function LoadFirst()
	{
		<?php if ($action == 'edit'): ?>
			$("#rowCodePost").removeClass('hide');
			$("#legendCode").addClass('hide');
			$("#CodeSupplier").val('<?php echo $get[0]['CodeSupplier'] ?>');
			$("#NamaSupplier").val('<?php echo $get[0]['NamaSupplier'] ?>');
			$("#Alamat").val('<?php echo $get[0]['Alamat'] ?>');
			$("#PICName").val('<?php echo $get[0]['PICName'] ?>');
			$("#Website").val('<?php echo $get[0]['Website'] ?>');
			$("#NoTelp").val('<?php echo $get[0]['NoTelp'] ?>');
			$("#NoHp").val('<?php echo $get[0]['NoHp'] ?>');
			$("#PICName").val('<?php echo $get[0]['PICName'] ?>');
			$("#Jabatan").val('<?php echo $get[0]['JabatanPIC'] ?>');
			// $("#PICName").val('<?php echo $get[0]['PICName'] ?>');

			<?php $DetailInfo = $get[0]['DetailInfo'] ?>

			<?php if ($DetailInfo != "" || $DetailInfo != null): ?>
				var DetailInfo = [];
			    DetailInfo =  <?php echo $DetailInfo ?>;
				if (DetailInfo != '') {
					var Input = '';
					for(var key in DetailInfo) {
						Input += '<div class = "row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px">'+
										'<div class="col-md-6 col-md-offset-2">'+
											'<div class="col-xs-4">'+
												'<input type="text" class="form-control addDetailInfo" placeholder = "Input Name" value = "'+key+'">'+
											'</div>'+
											'<div class="col-xs-6">'+
												'<input type="text" class="form-control addDetailInfo" placeholder = "Input Value" value = "'+DetailInfo[key]+'">'+
											'</div>'+
											'<div class="col-xs-2">'+
												'<button type="button" class="btn btn-danger btn-delete-addDetailInfo"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>'+
											'</div>'+	
										'</div>'+
									'</div>';
					}

					$("#pageAddDetailInfo").append(Input);	

					$(".btn-delete-addDetailInfo").click(function(){
						$(this)
						  .parentsUntil( 'div[class="row"]' ).remove();
					})	
				}
			<?php endif ?>

			<?php $DetailItem = $get[0]['DetailItem'] ?>

			<?php if ($DetailItem != "" || $DetailItem != null): ?>
				var DetailItem = [];
			    DetailItem =  <?php echo $DetailItem ?>;
				if (DetailItem != '') {
					var Input = '';
					for(var key in DetailItem) {
						Input += '<div class = "row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px">'+
										'<div class="col-md-6 col-md-offset-2">'+
											'<div class="col-xs-4">'+
												'<input type="text" class="form-control addDetailItem" placeholder = "Input Name" value = "'+key+'">'+
											'</div>'+
											'<div class="col-xs-6">'+
												'<input type="text" class="form-control addDetailItem" placeholder = "Input Value" value = "'+DetailItem[key]+'">'+
											'</div>'+
											'<div class="col-xs-2">'+
												'<button type="button" class="btn btn-danger btn-delete-addDetailItem"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>'+
											'</div>'+	
										'</div>'+
									'</div>';
					}

					$("#pageAddDetailItem").append(Input);	

					$(".btn-delete-addDetailItem").click(function(){
						$(this)
						  .parentsUntil( 'div[class="row"]' ).remove();
					})	
				}
			<?php endif ?>

			<?php if ($arr_lock > 0): ?>
				$('#CodeSupplier,#NamaSupplier,#PICName,#Jabatan').prop('disabled',true);
			<?php endif ?>

		<?php endif ?>

		loadSelectCategory();
		// save categoryName
		$("#btnAddCategoryName").click(function(){
			var url = base_url_js+'purchasing/page/supplier/saveCategoryFormInput';
			var CategoryName = $("#CategoryName").val();
			var id_data = $('#CategoryName').attr('id_data');
			var action = $(this).attr('action');
			if (CategoryName == '' || CategoryName == null) {
				toastr.error('Category is Required','!!!Failed');
			}
			else
			{
				loading_button('#btnAddCategoryName');
				var data = {
				            CategoryName : CategoryName,
				            action : action,
				            id_data : id_data,
				            };
				var token = jwt_encode(data,"UAP)(*");          
				$.post(url,{token:token},function (data_json) {
				    // jsonData = data_json;
				    // var obj = JSON.parse(data_json); 
				    // console.log(obj);
				}).done(function() {
				   $(".btnAddCollapseCategory").click();
				   loadSelectCategory();
				   $('#CategoryName').val('');
				}).fail(function() {
				  toastr.error('The Database connection error, please try again', '!!!Failed');
				}).always(function() {
				 $('#btnAddCategoryName').prop('disabled',false).html('<i class="fa fa-floppy-o" aria-hidden="true"></i>');
				});
			}
		})

		$('.btnDeleteCategory').click(function(){
			var ID = $('#CategorySupplier option:selected').val();
			if (confirm('Are you sure ?')) {
				loading_button('.btnDeleteCategory');
				var url = base_url_js+'purchasing/page/supplier/saveCategoryFormInput';
				var data = {
				            action : 'delete',
				            id_data : ID,
				            CategoryName : '',
				            };
				var token = jwt_encode(data,"UAP)(*");          
				$.post(url,{token:token},function (data_json) {
				    // jsonData = data_json;
				    // var obj = JSON.parse(data_json); 
				    // console.log(obj);
				}).done(function(data_json) {
					var obj = JSON.parse(data_json);
					if (obj == 0) {
						toastr.error('The data has been used for transaction, Cannot be action','!!!Failed');
					}
					else
					{
						loadSelectCategory();
						if($('#datatablesServer').length){
							var page = 'DataIntable';
							LoadPageSupplier(page);
						}
					}
				   
				}).fail(function() {
				  toastr.error('The Database connection error, please try again', '!!!Failed');
				}).always(function() {
				 $('.btnDeleteCategory').prop('disabled',false).html('<i class="fa fa-trash" aria-hidden="true"></i>');
				});
			}
		})

		$('.btnAddCollapseCategory').click(function(){
			var t = $('#addCategory').attr('class');
			if (t == 'collapse') {
				$('#btnAddCategoryName').attr('action','add');
				$('#CategoryName').val('');
				$('#addCategory').attr('class','in');
				$('#addCategory').attr('style','margin-top: 10px; height: auto;');
			}
			else
			{
				$('#addCategory').attr('class','collapse');
				$('#addCategory').attr('style','margin-top: 10px; height: 0px;');
			}

		})

		$(".btnEditCollapseCategory").click(function(){
			var t = $('#addCategory').attr('class');
			if (t == 'collapse') {
				var ID = $('#CategorySupplier option:selected').val();
				var Value = $('#CategorySupplier option:selected').text();
				$('#CategoryName').attr('id_data',ID);
				$('#CategoryName').val(Value);
				$('#btnAddCategoryName').attr('action','edit');
				$('#addCategory').attr('class','in');
				$('#addCategory').attr('style','margin-top: 10px; height: auto;');
			}
			else
			{
				$('#addCategory').attr('class','collapse');
				$('#addCategory').attr('style','margin-top: 10px; height: 0px;');
			}
		})

		$("#CategorySupplier").change(function(){
			var t = $('#addCategory').attr('class');
			if (t == 'in') {
				$('#addCategory').attr('class','collapse');
				$('#addCategory').attr('style','margin-top: 10px; height: 0px;');
				$('#CategoryName').val('');
			}
		})

		$("#NamaSupplier").keyup(function(){
			var maxLength = 40;
			var length = $(this).val().length;
			var length = maxLength-length;
			$('#charsNamaSupplier').text(length);
		})

		$("#Alamat").keyup(function(){
			var maxLength = 100;
			var length = $(this).val().length;
			var length = maxLength-length;
			$('#charsAlamat').text(length);
		})

		$(".NeedPrefix").click(function(){
		    $('input.NeedPrefix').prop('checked', false);
		    $(this).prop('checked',true);
		});

		$(".NeedPrefix").change(function(){
		  var valuee = $(this).val();
		  if(valuee == 0)
		  {
		    $("#rowCodePost").removeClass('hide');
		    $("#legendCode").addClass('hide');
		  }
		  else
		  {
		    $("#rowCodePost").addClass('hide');
		    $("#legendCode").removeClass('hide');
		  }
		})

		ClickFunctionAdd();
		ClickFunctionBtnSave();

	}

	function loadSelectCategory()
	{
		// add select category supplier
		var url = base_url_js+"purchasing/table_all/m_categorysupplier";
		$('#CategorySupplier').empty()
		$.post(url,function (data_json) {
		    var obj = JSON.parse(data_json);
		      var b = 0;
		      for(var i=0;i<obj.length;i++){
		      	if(obj[i].Active == 1){
		      		var selected = (b==0) ? 'selected' : '';
		      		//var selected = (data_json[i].RegionName=='Kota Jakarta Pusat') ? 'selected' : '';
		      		$('#CategorySupplier').append('<option value="'+obj[i].ID+'" '+selected+'>'+obj[i].CategoryName+'</option>');
		      		b++;
		      	}
		          
		      }
		      $('#CategorySupplier').select2({
		         //allowClear: true
		      });

		      <?php if ($action == 'edit'): ?>
		      	$("#CategorySupplier option").filter(function() {
		      	   //may want to use $.trim in here
		      	   return $(this).val() == '<?php echo $get[0]['CategorySupplier'] ?>'; 
		      	 }).prop("selected", true);

		      	$('#CategorySupplier').select2({
		      	   //allowClear: true
		      	});
		      <?php endif ?>
		}).done(function () {
		  
		});
	}

	function ClickFunctionAdd()
	{
		$("#addDetailInfo").click(function()
		{
			var Input = '<div class = "row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px">'+
							'<div class="col-md-6 col-md-offset-2">'+
								'<div class="col-xs-4">'+
									'<input type="text" class="form-control addDetailInfo" placeholder = "Input Name">'+
								'</div>'+
								'<div class="col-xs-6">'+
									'<input type="text" class="form-control addDetailInfo" placeholder = "Input Value">'+
								'</div>'+
								'<div class="col-xs-2">'+
									'<button type="button" class="btn btn-danger btn-delete-addDetailInfo"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>'+
								'</div>'+	
							'</div>'+
						'</div>';

			$("#pageAddDetailInfo").append(Input);	

			$(".btn-delete-addDetailInfo").click(function(){
				$(this)
				  .parentsUntil( 'div[class="row"]' ).remove();
			})		
		})

		$("#addDetailItem").click(function()
		{
			var Input = '<div class = "row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px">'+
							'<div class="col-md-6 col-md-offset-2">'+
								'<div class="col-xs-4">'+
									'<input type="text" class="form-control addDetailItem" placeholder = "Input Name">'+
								'</div>'+
								'<div class="col-xs-6">'+
									'<input type="text" class="form-control addDetailItem" placeholder = "Input Value">'+
								'</div>'+
								'<div class="col-xs-2">'+
									'<button type="button" class="btn btn-danger btn-delete-addDetailItem"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>'+
								'</div>'+	
							'</div>'+
						'</div>';

			$("#pageAddDetailItem").append(Input);	

			$(".btn-delete-addDetailItem").click(function(){
				$(this)
				  .parentsUntil( 'div[class="row"]' ).remove();
			})		
		})
	}

	function ClickFunctionBtnSave()
	{
		$("#btnSaveForm").click(function()
		{	
			if (confirm("Are you sure?") == true) {
				loading_button('#btnSaveForm');
				var url = base_url_js+'purchasing/page/supplier/saveFormInput';

				var NeedPrefix = $('.NeedPrefix:checked').val();
				var checkAddDetailInfo = getAddDetailInfo();
				var checkAddDetailItem = getAddDetailItem();

				var CodeSupplier = $("#CodeSupplier").val();
				var NamaSupplier = $("#NamaSupplier").val();
				var PICName = $("#PICName").val();
				var JabatanPIC = $("#Jabatan").val();
				var Alamat = $("#Alamat").val();
				var NoTelp = '+'+$("#NoTelp").val();
				var NoHp = '+'+$("#NoHp").val();
				var Website = $("#Website").val();
				var CategorySupplier = $("#CategorySupplier").val();
				var data = {
							NeedPrefix : NeedPrefix,
				            CodeSupplier : CodeSupplier,
				            NamaSupplier : NamaSupplier,
				            PICName : PICName,
				            JabatanPIC : JabatanPIC,
				            Alamat : Alamat,
				            Website : Website,
				            Action : "<?php echo $action ?>",
				            NoTelp : NoTelp,
				            NoHp : NoHp,
				            DetailInfo : checkAddDetailInfo,
				            CategorySupplier : CategorySupplier,
				            DetailItem : checkAddDetailItem,
				            <?php if ($action == 'edit'): ?>
				            	ID : "<?php echo $get[0]['ID'] ?>",
				            <?php endif ?>
				            };
				var token = jwt_encode(data,"UAP)(*");
				if (validationInput = validation(data)) {
				    $.post(url,{token:token},function (data_json) {
				        var response = jQuery.parseJSON(data_json);
    			      $('.pageAnchor[page="FormInput"]').trigger('click');
    			      	if (CountColapses2 == 0) {
    			      		$('.pageAnchor[page="DataIntable"]').trigger('click');
    			      		// LoadPageSupplier('DataIntable');
      			      	}
    			      	else
    			      	{
    			      		LoadPageSupplier('DataIntable');
    			      	}
				    }).done(function() {
				      // loadTable();
				    }).fail(function() {
				      toastr.error('The Database connection error, please try again', '!!!Failed');
				    }).always(function() {
				     $('#btnSaveForm').prop('disabled',false).html('Save');

				    });
				} // if validation
				else
				{
				    $('#btnSaveForm').prop('disabled',false).html('Save');
				}// exit validation
				
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
	      case  "NamaSupplier" :
	      case  "PICName" :
	      case  "Alamat" :
			      result = Validation_required(arr[key],key);
			      if (result['status'] == 0) {
			        toatString += result['messages'] + "<br>";
			      }
	            break;
	     }

	  }
	  if (toatString != "") {
	    toastr.error(toatString, '!!!Failed');
	    return false;
	  }

	  return true;
	}

	function getAddDetailInfo()
	{
		var arr = {};
		if (jQuery(".addDetailInfo").length) {
		  var get = [];
		  $('.addDetailInfo').each(function(){
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
			arr = '';
		}

		return arr;
		
	}

	function getAddDetailItem()
	{
		var arr = {};
		if (jQuery(".addDetailItem").length) {
		  var get = [];
		  $('.addDetailItem').each(function(){
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
			arr = '';
		}

		return arr;
		
	}

</script>