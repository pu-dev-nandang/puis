<div class="panel panel-primary" style="margin:20px;">
	<div class="panel-heading">
        	<h3 class="panel-title">Form Employees</h3>
	</div>
<div class="panel-body">
	<div class="col-md-12 col-sm-12">
		<div class="form-group col-md-3 col-sm-3">
	        <label for="name">NIP*	</label>
	        <input type="text" class="form-control input-sm" id="NIP" placeholder="">
	    </div>
	    <div class="form-group col-md-3 col-sm-3">
	        <label for="name">NIDN	</label>
	        <input type="text" class="form-control input-sm" id="NIDN" placeholder="">
	    </div>
	    <div class="form-group col-md-6 col-sm-6">
	        <label for="name">Name*	</label>
	        <input type="text" class="form-control input-sm" id="Name" placeholder="">
	    </div>
	    <div class = "form-group col-md-3 col-sm-3">
	        <label for="name">Gender*	</label>
	          <select class="form-control input-sm" id="Gender">
	        	<option>-- Select Gender--</option>
	        	<option value="P">Female</option>
	        	<option value="L">Male</option>
	          </select>
	    </div>
	    <div class = "form-group col-md-3 col-sm-3">
	        <label for="name">PlaceOfBirth*	</label>
	          <input type="text" class="form-control input-sm" id="PlaceOfBirth" placeholder="">
	    </div>
	    <div class = "form-group col-md-2 col-sm-2">
	        <label for="name">Years*	</label>
	          <select class="form-control input-sm" id="BirthYears">
	        	<option></option>
	          </select>
	    </div>
	    <div class = "form-group col-md-2 col-sm-2">
	        <label for="name">Month*	</label>
	            <select class="form-control input-sm" id="BirthMonth">
	          		<option></option>
	            </select>
	    </div>
	    <div class = "form-group col-md-2 col-sm-2">
	        <label for="name">Date*	</label>
		        <select class="form-control input-sm" id="BirthDate">
		      		<option></option>
		        </select>
	    </div>
	    <div class="form-group col-md-3 col-sm-3">
	        <label for="name">TitleAhead	</label>
	        <input type="text" class="form-control input-sm" id="TitleAhead" placeholder="">
	    </div>
	    <div class="form-group col-md-3 col-sm-3">
	        <label for="name">TitleBehind	</label>
	        <input type="text" class="form-control input-sm" id="TitleBehind" placeholder="">
	    </div>
	    <div class="form-group col-md-6 col-sm-6">
	        <label for="name">KTP*	</label>
	        <input type="text" class="form-control input-sm" id="KTP" placeholder="">
	    </div>
	    <div class="form-group col-md-6 col-sm-6">
	        <label for="mobile">Phone</label>
	        <input type="text" class="form-control input-sm" id="Phone" placeholder="">
	    </div>
	    <div class="form-group col-md-6 col-sm-6">
	        <label for="mobile">Mobile*</label>
	        <input type="text" class="form-control input-sm" id="HP" placeholder="">
	    </div>
	    <div class="form-group col-md-6 col-sm-6">
	        <label for="email">Email</label>
	        <input type="email" class="form-control input-sm" id="Email" placeholder="">
	    </div>
	    <div class="form-group col-md-6 col-sm-6">
	        <label for="email">Email PU*</label>
	        <input type="email" class="form-control input-sm" id="EmailPU" placeholder="">
	    </div>
		<div class="form-group col-md-6 col-sm-6">
	      <label for="address">Address*</label>
	      <textarea class="form-control input-sm" id="Address" rows="3"></textarea>
	    </div>
		<div class="form-group col-md-3 col-sm-3">
		    <label for="photo">Photo*</label>
		    <input type="file" id="Photo">
		    <p class="help-block">Please upload individual photo. Group photo is not acceptable.</p>
		    <?php if ($action == 'edit'): ?>
		    	<div class = "col-md-3">
		    		<img id="foto" src="#" alt="your image" />
		    	</div>
		    <?php endif ?>
		    
		</div>
		<div class = "form-group col-md-6 col-sm-6">
		    <label for="name">Religiion*	</label>
		      <select class="form-control input-sm" id="ReligionID">
		    	<option></option>
		      </select>
		</div>
		<div class = "form-group col-md-6 col-sm-6">
		    <label for="name">Division*	</label>
		      <select class="form-control input-sm divisi" id="Division">
		    	<option></option>
		      </select>
		</div>
		<div class = "form-group col-md-6 col-sm-6">
		    <label for="name">Position Main*	</label>
		      <select class="form-control input-sm position" id="PositionMain">
		    	<option></option>
		      </select>
		</div>
		<div class = "form-group col-md-6 col-sm-6">
			<div class="thumbnail" style="min-height: 100px;padding: 10px;" align = "center">
			    <label for="name" >Position Other 1</label>
			    <br>
			      <div class = "col-md-6 col-sm-6">
			      	<label for="name">Division</label>
			      	  <select class="form-control input-sm divisi" id="PositionOtherdivisi1">
			      		<option></option>
			      	  </select>
			      </div>	
			      <div class = "col-md-6 col-sm-6">
			      	<label for="name">Position</label>
			      	  <select class="form-control input-sm position" id="PositionOtherMain1">
			      		<option></option>
			      	  </select>
			      </div>
			</div>      
		</div>
		<div class = "form-group col-md-6 col-sm-6">
			<div class="thumbnail" style="min-height: 100px;padding: 10px;" align = "center">
			    <label for="name" >Position Other 2</label>
			    <br>
			      <div class = "col-md-6 col-sm-6">
			      	<label for="name">Division</label>
			      	  <select class="form-control input-sm divisi" id="PositionOtherdivisi2">
			      		<option></option>
			      	  </select>
			      </div>	
			      <div class = "col-md-6 col-sm-6">
			      	<label for="name">Position</label>
			      	  <select class="form-control input-sm position" id="PositionOtherMain2">
			      		<option></option>
			      	  </select>
			      </div>
			</div>      
		</div>
		<div class = "form-group col-md-6 col-sm-6">
			<div class="thumbnail" style="min-height: 100px;padding: 10px;" align = "center">
			    <label for="name" >Position Other 3</label>
			    <br>
			      <div class = "col-md-6 col-sm-6">
			      	<label for="name">Division</label>
			      	  <select class="form-control input-sm divisi" id="PositionOtherdivisi3">
			      		<option></option>
			      	  </select>
			      </div>	
			      <div class = "col-md-6 col-sm-6">
			      	<label for="name">Position</label>
			      	  <select class="form-control input-sm position" id="PositionOtherMain3">
			      		<option></option>
			      	  </select>
			      </div>
			</div>      
		</div>
		<div class = "form-group col-md-12 col-sm-6">
		    <label for="name">Status Employee*</label>
		      <select class="form-control input-sm" id="StatusEmployeeID">
		    	<option></option>
		      </select>
		</div>
	</div>

	<div class="col-md-12 col-sm-12">
		<div class="form-group col-md-3 col-sm-3 pull-right" >
				<button class="btn btn-success  btn-submit" id="btn-submit"> <i class="icon-pencil icon-white"></i> <span><strong>Submit</strong></span></button>
				<a href="<?php echo base_url('database/employees') ?>" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Cancel</a>
		</div>
	</div>
</div>

<script type="text/javascript">
	window.NIPedit = '<?php echo $NIPedit ?>';
	window.ActionEmp = '<?php echo $action ?>';
	$(document).ready(function() {
		  loadTahunLahir();
		  loadBulanLahir();
		  loadDataAgama();
		  loadDataDivisi();
		  loadDataPosition();
		  loadDataStatusEmployee();
		  if (ActionEmp == 'edit') {
		  	loaddataEmployee();
		  }

	});

	function loaddataEmployee()
	{
		$('#NotificationModal .modal-header').addClass('hide');
		$('#NotificationModal .modal-body').html('<center>' +
		    '                    <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>' +
		    '                    <br/>' +
		    '                    Loading Data . . .' +
		    '                </center>');
		$('#NotificationModal .modal-footer').addClass('hide');
		$('#NotificationModal').modal({
		    'backdrop' : 'static',
		    'show' : true
		});
		var url = base_url_js+'api/employees/searchnip/'+NIPedit;
		$.post(url,function (data_json) {
		    setTimeout(function () {
		       console.log(data_json);
		       // load all data to input
			       $("#foto").attr('src',base_url_js+'uploads/employees/'+NIPedit+'.jpg');
			       $("#NIP").val(data_json[0]['NIP']);
			       $("#NIDN").val(data_json[0]['NIDN']);
			       $("#Name").val(data_json[0]['Name']);

			       $("#Gender option").filter(function() {
			         //may want to use $.trim in here
			         return $(this).val() == data_json[0]['Gender']; 
			       }).prop("selected", true);

			       $("#PlaceOfBirth").val(data_json[0]['PlaceOfBirth']);
			       var DateOfBirth = data_json[0]['DateOfBirth'];
			       var a = DateOfBirth.split('-');
			       
			       $("#BirthYears option").filter(function() {
			         //may want to use $.trim in here
			         return $(this).val() == a[0]; 
			       }).prop("selected", true);

			       $("#BirthMonth option").filter(function() {
			         //may want to use $.trim in here
			         return $(this).val() == a[1]; 
			       }).prop("selected", true);

			       $("#BirthDate option").filter(function() {
			         //may want to use $.trim in here
			         return $(this).val() == a[2]; 
			       }).prop("selected", true);
			       
			       $("#TitleAhead").val(data_json[0]['TitleAhead']);
			       $("#TitleBehind").val(data_json[0]['TitleBehind']);
			       $("#KTP").val(data_json[0]['KTP']);
			       $("#Phone").val(data_json[0]['Phone']);
			       $("#HP").val(data_json[0]['HP']);
			       $("#Email").val(data_json[0]['Email']);
			       $("#EmailPU").val(data_json[0]['EmailPU']);
			       $("#Address").val(data_json[0]['Address']);

			       $("#ReligionID option").filter(function() {
			         //may want to use $.trim in here
			         return $(this).val() == data_json[0]['ReligionID']; 
			       }).prop("selected", true);
			       try {
				       var b = data_json[0]['PositionMain'].split('.');
				       $("#Division option").filter(function() {
				         //may want to use $.trim in here
				         return $(this).val() == b[0]; 
				       }).prop("selected", true);

				       $("#PositionMain option").filter(function() {
				         //may want to use $.trim in here
				         return $(this).val() == b[1];  
				       }).prop("selected", true);
				    }   
				    catch (e) {
				        // handle the unsavoriness if needed
				    }   

				    try {
				       b = data_json[0]['PositionOther1'].split('.');
				       $("#PositionOtherdivisi1 option").filter(function() {
				         //may want to use $.trim in here
				         return $(this).val() == b[0]; 
				       }).prop("selected", true);

				       $("#PositionOtherMain1 option").filter(function() {
				         //may want to use $.trim in here
				         return $(this).val() == b[1]; 
				       }).prop("selected", true);
				    }   
				    catch (e) {
				        // handle the unsavoriness if needed
				    }   
				    try {   
				       b = data_json[0]['PositionOther2'].split('.');
				       $("#PositionOtherdivisi2 option").filter(function() {
				         //may want to use $.trim in here
				         return $(this).val() == b[0]; 
				       }).prop("selected", true);

				       $("#PositionOtherMain2 option").filter(function() {
				         //may want to use $.trim in here
				         return $(this).val() == b[1]; 
				       }).prop("selected", true);
				    }   
				    catch (e) {
				        // handle the unsavoriness if needed
				    }    
				    try {   
				       b = data_json[0]['PositionOther3'].split('.');
				       $("#PositionOtherdivisi3 option").filter(function() {
				         //may want to use $.trim in here
				         return $(this).val() == b[0]; 
				       }).prop("selected", true);

				       $("#PositionOtherMain3 option").filter(function() {
				         //may want to use $.trim in here
				         return $(this).val() == b[1]; 
				       }).prop("selected", true);
				    }   
				    catch (e) {
				        // handle the unsavoriness if needed
				    }
				    $("#StatusEmployeeID option").filter(function() {
				      //may want to use $.trim in here
				      return $(this).val() == data_json[0]['StatusEmployeeID']; 
				    }).prop("selected", true);   
		       $('#NotificationModal').modal('hide');
		    },500);
		});
	}

	$(document).on('change','#BirthMonth', function () {
		var tahun = $("#BirthYears").find(':selected').val();
		var bulan = $(this).find(':selected').val();
		//moment("2012-02", "YYYY-MM").daysInMonth()
		loadCountDays(tahun,bulan,'#BirthDate');
		
	});

  function loadDataAgama()
  {
  	 $('#ReligionID').empty()
  	  var url = base_url_js+'api/__getAgama';
	  $.get(url,function (data_json) {
	  	 for(var i=0;i<data_json.length;i++){
	  	     var selected = (i==0) ? 'selected' : '';
	  	     $('#ReligionID').append('<option value="'+data_json[i].IDReligion+'" '+selected+'>'+data_json[i].Religion+'</option>');
	  	 } 
	  })
  }

  function loadDataDivisi()
  {
  	 $('.divisi').empty()
  	  var url = base_url_js+'api/__getDivision';
	  $.get(url,function (data_json) {
	  	$('#PositionOtherdivisi1').append('<option value="'+''+'" '+''+'>'+'---PositionOtherdivisi1---'+'</option>');
	  	$('#PositionOtherdivisi2').append('<option value="'+''+'" '+''+'>'+'---PositionOtherdivisi2---'+'</option>');
	  	$('#PositionOtherdivisi3').append('<option value="'+''+'" '+''+'>'+'---PositionOtherdivisi3---'+'</option>');
	  	 for(var i=0;i<data_json.length;i++){
	  	     var selected = (i==0) ? 'selected' : '';
	  	     $('#Division').append('<option value="'+data_json[i].ID+'" '+selected+'>'+data_json[i].Division+'</option>');
	  	     $('#PositionOtherdivisi1').append('<option value="'+data_json[i].ID+'" '+''+'>'+data_json[i].Division+'</option>');
	  	     $('#PositionOtherdivisi2').append('<option value="'+data_json[i].ID+'" '+''+'>'+data_json[i].Division+'</option>');
	  	     $('#PositionOtherdivisi3').append('<option value="'+data_json[i].ID+'" '+''+'>'+data_json[i].Division+'</option>');
	  	 } 
	  })
  }

  function loadDataPosition()
  {
  	 $('.position').empty()
  	  var url = base_url_js+'api/__getPosition';
	  $.get(url,function (data_json) {
	  	 $('#PositionOtherMain1').append('<option value="'+''+'" '+''+'>'+'---PositionOtherMain1---'+'</option>');
	  	 $('#PositionOtherMain2').append('<option value="'+''+'" '+''+'>'+'---PositionOtherMain2---'+'</option>');
	  	 $('#PositionOtherMain3').append('<option value="'+''+'" '+''+'>'+'---PositionOtherMain3---'+'</option>');
	  	 for(var i=0;i<data_json.length;i++){
	  	     var selected = (i==0) ? 'selected' : '';
	  	     $('#PositionMain').append('<option value="'+data_json[i].ID+'" '+selected+'>'+data_json[i].Position+'</option>');
	  	     $('#PositionOtherMain1').append('<option value="'+data_json[i].ID+'" '+''+'>'+data_json[i].Position+'</option>');
	  	     $('#PositionOtherMain2').append('<option value="'+data_json[i].ID+'" '+''+'>'+data_json[i].Position+'</option>');
	  	     $('#PositionOtherMain3').append('<option value="'+data_json[i].ID+'" '+''+'>'+data_json[i].Position+'</option>');
	  	 } 
	  })
   }

   function loadDataStatusEmployee()
   {
  	 $('#StatusEmployeeID').empty()
  	  var url = base_url_js+'api/__getStatusEmployee';
	  $.get(url,function (data_json) {
	  	 for(var i=0;i<data_json.length;i++){
	  	     var selected = (i==0) ? 'selected' : '';
	  	     $('#StatusEmployeeID').append('<option value="'+data_json[i].IDStatus+'" '+selected+'>'+data_json[i].Description+'</option>');
	  	 } 
	  })
   }

	function loadTahunLahir()
	{
		$('#BirthYears').empty();
		var thisYear = (new Date()).getFullYear();
		var startTahun = parseInt(thisYear) - parseInt(120);
		var selisih = parseInt(thisYear) - parseInt(startTahun);
		for (var i = 0; i <= selisih; i++) {
		    var selected = (i==0) ? 'selected' : '';
		    $('#BirthYears').append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
		}
	}

	function loadBulanLahir()
	{
		$('#BirthMonth').empty();
		var month = {
			01 : 'Jan',
			02 : 'Feb',
			03 : 'Mar',
			04 : 'April',
			05 : 'Mei',
			06 : 'Jun',
			07 : 'Jul',
			08 : 'Aug',
			09 : 'Sep',
			10 : 'Okt',
			11 : 'Nov',
			12 : 'Des'
		}

		for(var key in month) {
			var selected = (key==1) ? 'selected' : '';
			var getKey = key.toString();
			if (getKey.length == 1) {
				var value = '0' + getKey;
			}
			else
			{
				var value = key;
			}
			$('#BirthMonth').append('<option value="'+ value +'" '+selected+'>'+month[key]+'</option>');
		}

		var tahun = $("#BirthYears").find(':selected').val();
		var bulan = $("#BirthMonth").find(':selected').val();
		//moment("2012-02", "YYYY-MM").daysInMonth()
		loadCountDays(tahun,bulan,'#BirthDate');
	}

	function loadCountDays(tahun,bulan,elementTarget)
	{
			$(elementTarget).empty();
			var countDays = moment(tahun+"-"+bulan, "YYYY-MM").daysInMonth()
			// get dd 
		  	for (var i = 1; i <= countDays ; i++) {
				var selected = (i==1) ? 'selected' : '';
						var getKey = i.toString();
						if (getKey.length == 1) {
							var value = '0' + getKey;
						}
						else
						{
							var value = i;
						}
				  		$(elementTarget).append('<option value="'+ value +'" '+selected+'>'+value+'</option>');
			}
	}

	$(document).on('click','#btn-submit', function () {
	 	loading_button('#btn-submit');
	 	var DataArr = getDataInput();
	 	 if (varlidationInput(DataArr)) {
			console.log("Validation ok");
			toastr.clear();
			setTimeout(function () {
		     processData(DataArr);
		 	},1000);
	 	 }
	 	 else
	 	 {
	 	 	$('#btn-submit').prop('disabled',false).html('<i class="icon-pencil icon-white"></i> <span><strong>Submit</strong></span>'); 
	 	 }
	});

	function file_validation()
	{
		try{
			var name = document.getElementById("Photo").files[0].name;
			var ext = name.split('.').pop().toLowerCase();
			if(jQuery.inArray(ext, ['png','jpg','jpeg']) == -1) 
			{
			  toastr.error("Invalid Image File", 'Failed!!');
			  return false;
			}
			var oFReader = new FileReader();
			oFReader.readAsDataURL(document.getElementById("Photo").files[0]);
			var f = document.getElementById("Photo").files[0];
			var fsize = f.size||f.fileSize;
			if(fsize > 200000) // 200kb
			{
			 toastr.error("images larger than 200 kb", 'Failed!!');
			 return false;
			}

		}
		catch(err)
		{
			return false;
		}
	    return true;
	}


	function varlidationInput(data)
	{
		var toatString = "";
		var result = "";
		console.log(data);
		for(var key in data) {
		   switch(key)
		   {
		    case  "Address" :
		    case  "BirthDate" :
		    case  "BirthMonth" :
		    case  "BirthYears" :
		    case  "EmailPU" :
		    case  "Gender" :
		    case  "HP" :
		    case  "KTP" :
		    case  "NIP" :
		    case  "Name" :
		    case  "PlaceOfBirth" :
		    case  "PositionMain" :
		    case  "Division" :
		    case  "ReligionID" :
		    case  "StatusEmployeeID" :
		    	  result = Validation_required(data[key],key);
		          if (result['status'] == 0) {
		            toatString += result['messages'] + "<br>";
		          } 
		          break;
	  	 case "file_validation" :
	  	 	  if (!data[key]) {
	  	 	  	toatString += "File Upload error" + "<br>";
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


	  function getDataInput()
	  {
	  	 var data = {};
	  	 var NIP = $("#NIP").val().trim();
 	  	 var NIDN = $("#NIDN").val().trim();
	  	 var Name = $("#Name").val().trim();
	  	 var Gender = $("#Gender").val().trim();
	  	 var PlaceOfBirth = $("#PlaceOfBirth").val().trim();
	  	 var BirthYears = $('#BirthYears').find(':selected').val();
	  	 var BirthMonth = $('#BirthMonth').find(':selected').val();
	  	 var BirthDate = $('#BirthDate').find(':selected').val();
	  	 var TitleAhead = $("#TitleAhead").val().trim();
	  	 var TitleBehind = $("#TitleBehind").val().trim();
	  	 var KTP = $("#KTP").val().trim();
	  	 var Phone = $("#Phone").val().trim();
	  	 var HP = $("#HP").val().trim();
	  	 var Email = $("#Email").val().trim();
	  	 var EmailPU = $("#EmailPU").val().trim();
	  	 var Address = $("#Address").val().trim();

	  	 var ReligionID = $("#ReligionID").val().trim();
	  	 var Division = $("#Division").val().trim();
	  	 var PositionMain = $("#PositionMain").val().trim();
	  	 var PositionOtherdivisi1 = $("#PositionOtherdivisi1").val().trim();
	  	 var PositionOtherMain1 = $("#PositionOtherMain1").val().trim();
	  	 var PositionOtherdivisi2 = $("#PositionOtherdivisi2").val().trim();
	  	 var PositionOtherMain2 = $("#PositionOtherMain2").val().trim();
	  	 var PositionOtherdivisi3 = $("#PositionOtherdivisi3").val().trim();
	  	 var PositionOtherMain3 = $("#PositionOtherMain3").val().trim();
	  	 var StatusEmployeeID = $("#StatusEmployeeID").val().trim();
	  	 
	  	 data = {
	  	 		NIP	: NIP,
	  	 		NIDN	: NIDN,
	  	 		Name	: Name,
	  	 		Gender	: Gender,
	  	 		PlaceOfBirth	: PlaceOfBirth,
	  	 		BirthYears	: BirthYears,
	  	 		BirthMonth	: BirthMonth,
	  	 		BirthDate	: BirthDate,
	  	 		TitleAhead	: TitleAhead,
	  	 		TitleBehind	: TitleBehind,
	  	 		KTP	: KTP,
	  	 		Phone	: Phone,
	  	 		HP	: HP,
	  	 		Email	: Email,
	  	 		EmailPU	: EmailPU,
	  	 		Address	: Address,
	  	 		<?php if ($action == 'add'): ?>
	  	 			file_validation : file_validation(),
	  	 		<?php endif ?>
	  	 		ReligionID	: ReligionID,
	  	 		Division	: Division,
	  	 		PositionMain	: PositionMain,
	  	 		PositionOtherdivisi1	: PositionOtherdivisi1,
	  	 		PositionOtherMain1	: PositionOtherMain1,
	  	 		PositionOtherdivisi2	: PositionOtherdivisi2,
	  	 		PositionOtherMain2	: PositionOtherMain2,
	  	 		PositionOtherdivisi3	: PositionOtherdivisi3,
	  	 		PositionOtherMain3	: PositionOtherMain3,
	  	 		StatusEmployeeID	: StatusEmployeeID,
	  	 		NIPedit : NIPedit,
	  	 };
	  	 return data;
	  }

    function processData(DataArr)
    {
    	var form_data = new FormData();
    	var fileData = document.getElementById("Photo").files[0];
    	var Action = "<?php echo $action ?>";
    	var url = base_url_js + "database/employees/form_input_submit";
    	var token = jwt_encode(DataArr,"UAP)(*");
    	form_data.append('token',token);
    	form_data.append('fileData',fileData);
    	form_data.append('Action',Action);
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
    	    }
    	    else
    	    {
    	    	toastr.options.fadeOut = 100000;
    	    	toastr.error(data.msg, 'Failed!!');
    	    }
      	setTimeout(function () {
           toastr.clear();
       	},1000);

      	if(data.status == 1) {
  			setTimeout(function () {
  		     $('#btn-submit').prop('disabled',false).html('<i class="icon-pencil icon-white"></i> <span><strong>Submit</strong></span>');
  		 	},1000);
      	}
      	$('#btn-submit').prop('disabled',false).html('<i class="icon-pencil icon-white"></i> <span><strong>Submit</strong></span>');
   		
    	  },
    	  error: function (data) {
    	    toastr.error("Connection Error, Please try again", 'Error!!');
    	    $('#btn-submit').prop('disabled',false).html('<i class="icon-pencil icon-white"></i> <span><strong>Submit</strong></span>');  
    	  }
    	})
    }
</script>