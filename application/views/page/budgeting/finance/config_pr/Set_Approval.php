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
					<label>Department</label>
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
	var cfg_m_type_approval = <?php echo json_encode($cfg_m_type_approval) ?>;
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
								// '<div class ="row">'+	
								// 	'<div class = "col-xs-3"><b>* Please Enter to save data</b></div>'+
								// '</div>'+
								'<div class = "row">'+
									'<div class = "col-md-12">'+
										'<div class="table-responsive">'+
											'<table class="table table-bordered tableData" id ="tableData7">'+
											'<thead>'+
											'<tr>'+
												'<th width = "3%">No</th>'+
					                            '<th>RoleUser</th>'+
					                            '<th>Name</th>'+
					                            '<th>Type User</th>'+
					                            '<th>Visible</th>'+
					                            '<th>Action</th>'+
											'</tr></thead>'	
							;
		TableGenerate += '<tbody>';
		var url = base_url_js+"budgeting/get_cfg_set_roleuser_pr/"+$('#DepartementUserRole').val();
		$.post(url,function (resultJson) {
			var response = jQuery.parseJSON(resultJson);
			for (var i = 0; i < response.length; i++) {
				var input = '<button class = "btn btn-default btnInput btn-write" ID_m_userrole = "'+response[i]['ID']+'">Set Input</button>';
				var btnDelete = '';
				var cmb = '';
				var visible = '';
				var btn_save = '';

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

					cmb = '<select class = "cmbTypeUser form-control" >';
						for (var j = 0; j < cfg_m_type_approval.length; j++) {
							var selected = (cfg_m_type_approval[j].ID == response[i]['TypeDesc'])? 'selected' : '';
							cmb += '<option value = "'+cfg_m_type_approval[j].ID+'" '+selected+' >'+cfg_m_type_approval[j].Name+'</option>';
						}
						cmb += '</select>';

					
					visible = '<select class = "cmbVisibel form-control" >';
								var cc = ["Yes","No"];
								for (var j = 0; j < cc.length; j++) {
									var s = (response[i]['Visible'] == cc[j]) ? 'selected' : '';
									visible += '<option value = "'+cc[j]+'" '+s+'>'+cc[j]+'</option>';
								}
					visible += '</select>';

					btnDelete = '<button type="button" class="btn btn-danger btn-delete btn-delete-setRoleUser btn-write" code="'+response[i]['ID_set_roleuser']+'"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
				}
				TableGenerate += '<tr>'+
									'<td width = "3%">'+ (parseInt(i) + 1)+'</td>'+
									'<td>'+ response[i]['NameUserRole']+'</td>'+
									'<td id = "'+response[i]['ID']+'">'+ input+'</td>'+
									'<td>'+cmb+'</td>'+
									'<td>'+visible+'</td>'+
									'<td>'+btnDelete+'</td>'+
								'</tr>';
			}

			TableGenerate += '</tbody></table></div></div></div>';
			var divSave = '<div class = "row">'+
								'<div class = "col-md-12" align = "right">'+
									'<button class="btn btn-primary btn-save-approval btn-write">Save</button>'+
								'</div>'+
							'</div>';		
			$("#loadPageTable").html(TableGenerate+divSave);

			ClickFunctionButton();
			KeypressFunctionInput();

		})

	}

	function ClickFunctionButton()
	{
		$(document).off('click', '.btnInput').on('click', '.btnInput',function(e) {		
			var ID_m_userrole = $(this).attr('ID_m_userrole');
			var row = $(this).closest('tr');
			// adding combo Type User
				var cmb = '<select class = "cmbTypeUser form-control" >';
					for (var i = 0; i < cfg_m_type_approval.length; i++) {
						var selected = (i == 2)? 'selected' : '';
						cmb += '<option value = "'+cfg_m_type_approval[i].ID+'" '+selected+' >'+cfg_m_type_approval[i].Name+'</option>';
					}
					cmb += '</select>';

				var visible = '<select class = "cmbVisibel form-control" >'+
								'<option value = "Yes" selected>Yes</option>'+
								'<option value = "No">No</option>'+
							  '</select>';
				
				var btnDelete = '<button type="button" class="btn btn-danger btn-delete btn-delete-setRoleUser" code=""> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';		  		

			input = '<div class = "row">'+
						'<div class = "col-xs-6">'+
							'<input type = "text" class = "form-control FormInputData" ID_m_userrole = "'+ID_m_userrole+'" placeholder = "Input..." id = "m_userRole'+ID_m_userrole+'" ID_set_roleuser = "">'+
						'</div>'+
						'<div class = "col-xs-3">'+
							'<label id = "labelName'+ID_m_userrole+'" ></label>'+
						'</div>'+
					'</div>';			
			$("#"+ID_m_userrole).html(input);
			row.find('td:eq(3)').html(cmb);
			row.find('td:eq(4)').html(visible);
			row.find('td:eq(5)').html(btnDelete);
			KeypressFunctionInput();
		})

		$(document).off('click', '.btn-delete-setRoleUser').on('click', '.btn-delete-setRoleUser',function(e) {	
			var ID = $(this).attr('code');
			var tr = $(this).closest('tr');
			var id_m_userrole = tr.find('.FormInputData').attr('id_m_userrole');
			if (ID != '' && ID != null && ID != undefined) {
				var Action = "delete";
				if (confirm("Are you sure?") == true) {
				  	var data = {
			  	                   ID_set_roleuser : ID,
			  	                   Action : Action
		  	                   };
				  	var token = jwt_encode(data,"UAP)(*");
				  	var url = base_url_js+'budgeting/save_cfg_set_roleuser_pr';
				  	$.post(url,{token:token},function (data_json) {
	  	               var obj = JSON.parse(data_json); 
	  	               if(obj['status'] == 1)
	  	               {
	  	               	//loadPageTable();
	  	               	// Back normal to row
	  	               		tr.find('td:eq(2)').html('<button class="btn btn-default btnInput" id_m_userrole="'+id_m_userrole+'">Set Input</button>');
	  	               		tr.find('td:eq(3)').html('');
	  	               		tr.find('td:eq(4)').html('');
	  	               		tr.find('td:eq(5)').html('');
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
			}
			else
			{
				tr.find('td:eq(2)').html('<button class="btn btn-default btnInput" id_m_userrole="'+id_m_userrole+'">Set Input</button>');
				tr.find('td:eq(3)').html('');
				tr.find('td:eq(4)').html('');
				tr.find('td:eq(5)').html('');
			}
			
		})
	}

	function KeypressFunctionInput()
	{
		// $(".FormInputData").keypress(function(event){
		$(document).off('keypress', '.FormInputData').on('keypress', '.FormInputData',function(e) {	
			var ID_m_userrole = $(this).attr('id');
			loadAutoCompleteUser(ID_m_userrole)
		})

		$(document).off('click', '.btn-save-approval').on('click', '.btn-save-approval',function(e) {
			// get all data
			var dt = [];
			loading_button('.btn-save-approval');
			var Departement = $('#DepartementUserRole').val();
			$('.FormInputData').each(function(){
				var NIP = $(this).val();
				var tr = $(this).closest('tr');
				var id_set_roleuser = $(this).attr('id_set_roleuser');
				var id_m_userrole = $(this).attr('id_m_userrole');
				var TypeDesc = tr.find('.cmbTypeUser').val();
				var Visible = tr.find('.cmbVisibel').val();
				var subAction = (id_set_roleuser == '' || id_set_roleuser == null || id_set_roleuser == undefined) ? 'add' : 'edit';

				var temp = {
					FormInsert : {
						NIP : NIP,
						ID_m_userrole : id_m_userrole,
						TypeDesc : TypeDesc,
						Visible : Visible,
						Departement : Departement,
					},
					Method : {
						Action : subAction,
						ID : id_set_roleuser,
					}

				}

				dt.push(temp);
			})

			var url = base_url_js+'budgeting/save_cfg_set_roleuser_pr';
			var data = {
	  	       			   dt : dt,
	  	                   Action : ""
  	                   };
			var token = jwt_encode(data,"UAP)(*");
			$.post(url,{token:token},function (data_json) {
	           var obj = JSON.parse(data_json); 
	           if(obj['status'] == 1)
	           {
	           	toastr.success("Done", 'Success!');
	           	loadPageTable();
	           }
	           else
	           {
	           	toastr.error(obj,'Failed!!');
	           }
	           $('.btn-save-approval').prop('disabled',false).html('Save');
	       }).done(function() {
	         
	       }).fail(function() {
	         toastr.error('The Database connection error, please try again', 'Failed!!');
	       }).always(function() {
	       		
	       });

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