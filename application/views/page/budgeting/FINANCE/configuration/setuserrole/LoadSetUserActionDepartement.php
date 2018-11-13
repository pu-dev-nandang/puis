<style type="text/css">
	#tableData7 thead th,#tableData7 tfoot td {

	    text-align: center;
	    background: #20485A;
	    color: #FFFFFF;

	}

	#tableData7>thead>tr>th, #tableData7>tbody>tr>th, #tableData7>tfoot>tr>th, #tableData7>thead>tr>td, #tableData7>tbody>tr>td, #tableData7>tfoot>tr>td {
	    border: 1px solid #b7b7b7
	}
</style>
<div class="row" style="margin-right: 0px;margin-left: 0px">
	<div class="col-md-6 col-md-offset-3">
		<div class="thumbnail" style="height: 90px">
			<div class="col-md-6 col-md-offset-3">
				<div class="form-group">
					<label>Departement</label>
					<select class="select2-select-00 full-width-fix" id="DepartementUserRole">
					     <!-- <option></option> -->
					 </select>
				</div>	
			</div>
		</div>
	</div>	
</div>	

<div class="row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px" id = "loadPageTable">
	
</div>

<script type="text/javascript">
	$(document).ready(function() {
		LoadFirstLoad();
	}); // exit document Function

	function LoadFirstLoad()
	{
		getAllDepartementPU();
	}

	function getAllDepartementPU()
	{
	  var url = base_url_js+"api/__getAllDepartementPU";
	  $('#DepartementUserRole').empty();
	  $.post(url,function (data_json) {
	    for (var i = 0; i < data_json.length; i++) {
	        var selected = (i==0) ? 'selected' : '';
	        $('#DepartementUserRole').append('<option value="'+ data_json[i]['Code']  +'" '+selected+'>'+data_json[i]['Name2']+'</option>');
	    }
	   
	    $('#DepartementUserRole').select2({
	       //allowClear: true
	    });

	    $("#DepartementUserRole").change(function(){
	    	loadPageTable();
	    })

	    loadPageTable();

	  })
	}

	function loadPageTable()
	{
		$("#loadPageTable").empty();
		var TableGenerate = '<div class="col-md-12" id = "pageForTable">'+
								'<div class ="row">'+	
									'<div class = "col-xs-3"><b>* Please Enter to save data</b></div>'+
								'</div>'+
								'<div class = "row">'+
									'<div class = "col-md-12">'+
										'<div class="table-responsive">'+
											'<table class="table table-bordered tableData" id ="tableData7">'+
											'<thead>'+
											'<tr>'+
												'<th width = "3%">No</th>'+
					                            '<th>RoleUser</th>'+
					                            '<th>Name</th>'+
					                            '<th>Action</th>'+
											'</tr></thead>'	
							;
		TableGenerate += '<tbody>';
		var url = base_url_js+"budgeting/get_cfg_set_roleuser/"+$('#DepartementUserRole').val();
		$.post(url,function (resultJson) {
			var response = jQuery.parseJSON(resultJson);
			for (var i = 0; i < response.length; i++) {
				var input = '<button class = "btn btn-default btnInput" ID_m_userrole = "'+response[i]['ID']+'">Set Input</button>';
				var btnDelete = '';
				if(response[i]['NIP'] != null)
				{
					// input = '<input type = "text" class = "form-control FormInputData" ID_m_userrole = "'+response[i]['ID']+'" value "'+response[i]['NIP']+'" id = "m_userRole'+response[i]['ID']+'">';
					input = '<div class = "row">'+
						'<div class = "col-xs-6">'+
							'<input type = "text" class = "form-control FormInputData" ID_m_userrole = "'+response[i]['ID']+'" value = "'+response[i]['NIP']+'" placeholder = "Input..." id = "m_userRole'+response[i]['ID']+'" ID_set_roleuser = "'+response[i]['ID_set_roleuser']+'">'+
						'</div>'+
						'<div class = "col-xs-3">'+
							'<label id = "labelName'+response[i]['ID']+'" >'+response[i]['NamaUser']+' | '+response[i]['NIP']+'</label>'+
						'</div>'+
					'</div>';

					btnDelete = '<button type="button" class="btn btn-danger btn-delete btn-delete-setRoleUser" code="'+response[i]['ID_set_roleuser']+'"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
				}
				TableGenerate += '<tr>'+
									'<td width = "3%">'+ (parseInt(i) + 1)+'</td>'+
									'<td>'+ response[i]['NameUserRole']+'</td>'+
									'<td id = "'+response[i]['ID']+'">'+ input+'</td>'+
									'<td>'+ btnDelete+'</td>'+
								'</tr>';
			}

			TableGenerate += '</tbody></table></div></div></div>';
			$("#loadPageTable").html(TableGenerate);

			ClickFunctionButton();
			KeypressFunctionInput();

		})

	}

	function ClickFunctionButton()
	{
		$(".btnInput").click(function(){
			var ID_m_userrole = $(this).attr('ID_m_userrole');
			input = '<div class = "row">'+
						'<div class = "col-xs-6">'+
							'<input type = "text" class = "form-control FormInputData" ID_m_userrole = "'+ID_m_userrole+'" placeholder = "Input..." id = "m_userRole'+ID_m_userrole+'" ID_set_roleuser = "">'+
						'</div>'+
						'<div class = "col-xs-3">'+
							'<label id = "labelName'+ID_m_userrole+'" ></label>'+
						'</div>'+
					'</div>';			
			$("#"+ID_m_userrole).html(input);
			KeypressFunctionInput();
		})

		$(".btn-delete-setRoleUser").click(function(){
			var ID = $(this).attr('code');
			var Action = "delete";
			if (confirm("Are you sure?") == true) {
			  	var data = {
		  	                   ID_set_roleuser : ID,
		  	                   Action : Action
	  	                   };
			  	var token = jwt_encode(data,"UAP)(*");
			  	var url = base_url_js+'budgeting/save_cfg_set_roleuser';
			  	$.post(url,{token:token},function (data_json) {
  	               var obj = JSON.parse(data_json); 
  	               if(obj == "")
  	               {
  	               	loadPageTable()
  	               	toastr.success("Done", 'Success!');
  	               }
  	               else
  	               {
  	               	toastr.error(obj,'Failed!!');
  	               }

  	           }).done(function() {
  	             
  	           }).fail(function() {
  	             toastr.error('The Database connection error, please try again', 'Failed!!');
  	           }).always(function() {
  	           		

  	           });
			}	
			else {
                return false;
            }
		})
	}

	function KeypressFunctionInput()
	{
		$(".FormInputData").keypress(function(event){
			var ID_m_userrole = $(this).attr('id');
			loadAutoCompleteUser(ID_m_userrole)
			var replaceText = 'm_userRole';
			var res = ID_m_userrole.replace(replaceText,"");
			if (event.keyCode == 10 || event.keyCode == 13) {
			  if(this.value != "")
			  {
			  	var url = base_url_js+'budgeting/save_cfg_set_roleuser';
			  	var NIP = this.value;
			  	var ID_set_roleuser = $(this).attr('ID_set_roleuser');
			  	var Departement = $("#DepartementUserRole").val();
			  	var data = {
		  	       			   ID_m_userrole : res,
		  	                   NIP : NIP,
		  	                   Departement : Departement,
		  	                   ID_set_roleuser : ID_set_roleuser,
		  	                   Action : ""
	  	                   };
			  	var token = jwt_encode(data,"UAP)(*");
			  	$.post(url,{token:token},function (data_json) {
  	               var obj = JSON.parse(data_json); 
  	               if(obj == "")
  	               {
  	               	toastr.success("Done", 'Success!');
  	               }
  	               else
  	               {
  	               	toastr.error(obj,'Failed!!');
  	               }

  	           }).done(function() {
  	             
  	           }).fail(function() {
  	             toastr.error('The Database connection error, please try again', 'Failed!!');
  	           }).always(function() {
  	           		

  	           });
			  }
			  else
			  {
			  	toastr.error('The Name is Required','Failed!!');
			  }
			}
		})
	}

	function loadAutoCompleteUser(ID_m_userrole)
	{
	    $("#"+ID_m_userrole).autocomplete({
	      minLength: 3,
	      select: function (event, ui) {
	        event.preventDefault();
	        var selectedObj = ui.item;
	        $("#"+ID_m_userrole).val(selectedObj.value);
	        var replaceText = 'm_userRole';
	        var res = ID_m_userrole.replace(replaceText,"");
	        $("#labelName"+res).html(selectedObj.label);
	      },
	      /*select: function (event,  ui)
	      {

	      },*/
	      source:
	      function(req, add)
	      {
	        var url = base_url_js+'autocompleteAllUser';
	        var Nama = $("#"+ID_m_userrole).val();
	        var data = {
	                    Nama : Nama,
	                    };
	        var token = jwt_encode(data,"UAP)(*");          
	        $.post(url,{token:token},function (data_json) {
	            var obj = JSON.parse(data_json);
	            add(obj.message) 
	        })
	      } 
	    })

	}

</script>