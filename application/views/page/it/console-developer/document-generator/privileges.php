<style type="text/css">
	#datatable_employees.dataTable tbody tr:hover {
	   background-color:#71d1eb !important;
	   cursor: pointer;
	}
</style>
<div class="row">
	<div class="col-md-4 col-md-offset-4">
		<div class="thumbnail">
			<div class="form-group">
				<label>Choose Department</label>
				<select class="form-control Input" id = "DepartmentIDChoose" name = "Department">
				<?php for ($i=0;$i < count($Arr_DepartmetnPU);$i++): ?>
					<option value="<?php echo $Arr_DepartmetnPU[$i]['Code'] ?>" ><?php echo $Arr_DepartmetnPU[$i]['Name2'] ?></option>
				<?php endfor ?>
				</select>
			</div>
		</div>
	</div>
</div>

<div class="row" style="margin-top: 15px;">
	<div class="col-md-3">
		<div class="panel panel-default">
		    <div class="panel-heading">
		        <h4 class="panel-title">Form</h4>
		    </div>
		    <div class="panel-body" style="min-height: 100px;" id ="pageForm">
		    	<div class="row">
		    		<div class="col-md-12">
		    			<div class = "form-group">
		    			    <label>Choose Employees</label>
		    			    <div class="input-group">
		    			        <input type="text" class="form-control Input" name="NIP" readonly id="NIP">
		    			        <span class="input-group-btn">
		    			            <button class="btn btn-default SearchNIPEMP" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
		    			        </span>
		    			    </div>
		    			    <label for="Name"></label>
		    			</div>
		    			<div class="form-group">
		    				<label>Level</label>
		    				<select class="form-control Input" name="Level" id = "Level">
		    					<option value="Admin">Admin</option>
		    					<option value="User" selected>User</option>
		    				</select>
		    			</div>
		    		</div>
		    	</div>
		    </div>
		    <div class="panel-footer" style="text-align: right;">
		        <button class="btn btn-success" id="btnSave" action = "add" data-id="">Save</button>
		    </div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="panel panel-default">
		    <div class="panel-heading">
		        <h4 class="panel-title">List</h4>
		    </div>
		    <div class="panel-body" style="min-height: 100px;" id = "pageTable">
		        <div class="row">
		        	<div class="col-md-12">
		        		<div class="table-responsive">
		        			<table class = "table table-striped" id = "TblList">
		        				<thead>
		        					<tr>
		        						<th>NIP - Name</th>
		        						<th>Access Level</th>
		        						<th>Action</th>
		        					</tr>
		        				</thead>
		        				<tbody></tbody>
		        			</table>
		        		</div>
		        	</div>
		        </div>
		    </div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var oTable;
	var S_Table_example_emp;
	var App_Table = {
		Loaded : function(){
			App_Table.LoadTable();
		},

		LoadTable : function(){
		   var recordTable = $('#TblList').DataTable({
		       "processing": true,
		       "serverSide": false,
		       "lengthMenu": [
		           [10, 25],
		           [10, 25]
		       ],
		       "iDisplayLength": 10,
		       "ajax":{
		           url : base_url_js+"it/__request-document-generator/__CRUDPrivileges", // json datasource
		           ordering : false,
		           type: "post",  // method  , by default get
		           data : function(token){
		                 // Read values
		                  var data = {
		                         DepartmentIDChoose : $('#DepartmentIDChoose option:selected').val(),
		                         action : 'read',
		                     };
		                 // Append to data
		                 token.token = jwt_encode(data,'UAP)(*');
		           }                                                                     
		        },
		         'columnDefs': [
		            {
		               'targets': 2,
		               'className': 'dt-body-center',
		               'render': function (data, type, full, meta){
		               		var btnEdit = '<li><a href="javascript:void(0);" class="btnEdit" data-id="'+full[2]+'" data = "'+full[3]+'"><i class="fa fa fa-edit"></i> Edit</a></li>';
		               	    var btnRemove = '<li><a href="javascript:void(0);" class="btnRemove" data-id="'+full[2]+'" ><i class="fa fa fa-remove"></i> Remove</a></li>';
		                   var btnAction = '<div class="btn-group">' +
		                       '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
		                       '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
		                       '  </button>' +
		                       '  <ul class="dropdown-menu">' +
		                      		btnEdit +
		                       		btnRemove +
		                       '  </ul>' +
		                       '</div>';
		                   return btnAction;
		               }
		            },
		            
		         ],
		       'createdRow': function( row, data, dataIndex ) {
		               
		       },
		       dom: 'l<"toolbar">frtip',
		       initComplete: function(){
		         
		      }  
		   });
		   
		   oTable = recordTable;
		},


	};


	var App_form = {
		Loaded : function(){
			$('#NIP').val('');
			$('#NIP').attr('datatoken','');
			$('#NIP').closest('.form-group').find('label[for="Name"]').html('');
			$('#btnSave').attr('action','add');
			$('#btnSave').attr('data-id',"");
		},

		SearchNIPEMP : function(selector){
		      var html = '';
		      html ='<div class = "row">'+
		              '<div class = "col-md-12">'+
		                '<div class="table-responsive">'+
		                  '<table id="datatable_employees" class="table table-bordered display select" cellspacing="0" width="100%">'+
		         '<thead>'+
		            '<tr>'+
		               '<th style = "width:5%;">No</th>'+
		               '<th>NIP - Name</th>'+
		               '<th>Division</th>'+
		               '<th>Position</th>'+
		            '</tr>'+
		         '</thead>'+
		         '<tbody></tbody>'+
		    	'</table></div></div></div>';

		      $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Select Employees'+'</h4>');
		      $('#GlobalModalLarge .modal-body').html(html);
		      $('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');
		      $('#GlobalModalLarge').modal({
		          'show' : true,
		          'backdrop' : 'static'
		      });

		      var table = $('#datatable_employees').DataTable({
		          "fixedHeader": true,
		          "processing": true,
		          "destroy": true,
		          "serverSide": true,
		          "lengthMenu": [
		              [10, 25],
		              [10, 25]
		          ],
		          "iDisplayLength": 10,
		          "ordering": false,
		          "language": {
		              "searchPlaceholder": "Search",
		          },
		          "ajax": {
		              url: base_url_js + "rest3/__LoadEmployees_server_side", // json datasource
		              ordering: false,
		              type: "post", // method  , by default get
		              data: function(token) {
		                  var data = {
		                      auth: 's3Cr3T-G4N',
		                  };
		                  var get_token = jwt_encode(data, "UAP)(*");
		                  token.token = get_token;
		              },
		              error: function() { // error handling
		                  $(".datatable_employees-grid-error").html("");
		                  $("#datatable_employees-grid").append(
		                      '<tbody class="datatable_employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
		                  );
		                  $("#datatable_employees-grid_processing").css("display", "none");
		              }
		          },
		          'createdRow': function(row, data, dataIndex) {
		              var dt = jwt_decode(data.data);
		              $(row).attr('datatoken',data.data);
		              $(row).find('td:eq(1)').html(dt['NIP']+' - '+dt['Name']);
		              $(row).find('td:eq(2)').html(dt['DepartmentName']);
		              $(row).find('td:eq(3)').html(dt['PositionName']);
		          },
		          dom: 'l<"toolbar">frtip',
		          "initComplete": function(settings, json) {

		          }
		      });

		      S_Table_example_emp = table;

		      S_Table_example_emp.on( 'click', 'tr', function (e) {
		          var row = $(this);
		          var datatoken = jwt_decode(row.attr('datatoken'));
		          selector.closest('.input-group').find('input').val(datatoken['NIP']);
		          selector.closest('.input-group').find('input').attr('datatoken',row.attr('datatoken'));
		          selector.closest('.form-group').find('label[for="Name"]').html(datatoken['Name']);
		          $('#GlobalModalLarge').modal('hide');
		      });

		},

		ActionData : function(selector,action="add",ID=""){
		    var data = {};
		    $('.Input').not('div').each(function(){
		        var field = $(this).attr('name');
		        data[field] = $(this).val();
		    })

		    var dataform = {
		        action : action,
		        data : data,
		        ID : ID,
		    };
		    // cek validation jika tidak delete
		    var validation = (action == 'delete') ? true : App_form.Validation(data);
		    if (validation) {
		        if (confirm('Are you sure ?')) {
		            loading_button2(selector);
		            var url = base_url_js+"it/__request-document-generator/__CRUDPrivileges";
		            var token = jwt_encode(dataform,'UAP)(*');
		            $.post(url,{token:token},function (resultJson) {
		                    
		            }).done(function(response) {
		               if (response.status == 1) {
		                   end_loading_button2(selector);
		                   oTable.ajax.reload( null, false );
		                   App_form.Loaded();
		                   toastr.success('Success');
		               }
		               else
		               {
		                   toastr.error(response.msg);
		                   end_loading_button2(selector);
		               }
		            }).fail(function() {
		                toastr.error("Connection Error, Please try again", 'Error!!');
		                end_loading_button2(selector);
		            });
		        }
		    }
		},

		Validation : function(arr){
		    var toatString = "";
		    var result = "";
		    for(key in arr){
		       switch(key)
		       {
		        default :
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
		    return true
		},


	};


	$(document).ready(function(e){
		$('#DepartmentIDChoose').removeClass('form-control');
		$('#DepartmentIDChoose').addClass('select2-select-00 full-width-fix');
		$('#DepartmentIDChoose').select2({});
		App_Table.Loaded();
		App_form.Loaded();
	})

	$(document).off('change', '#DepartmentIDChoose').on('change', '#DepartmentIDChoose',function(e) {
	   oTable.ajax.reload( null, false );
	})

	$(document).off('click', '.SearchNIPEMP').on('click', '.SearchNIPEMP',function(e) {
	   var itsme = $(this);
	   App_form.SearchNIPEMP(itsme);
	})

	$(document).off('click', '.btnRemove').on('click', '.btnRemove',function(e) {
	    var ID = $(this).attr('data-id');
	    var action = 'delete';
	    var selector = $(this);
	    App_form.ActionData(selector,action,ID);
	})

	$(document).off('click', '.btnEdit').on('click', '.btnEdit',function(e) {
	    var ID = $(this).attr('data-id');
	    var Token = $(this).attr('data');
	    var data = jwt_decode(Token);
	    // console.log(data);
	    for(var key in data) {
	        if (key == 'Level') {
	            $(".Input[name='"+key+"'] option").filter(function() {
	               //may want to use $.trim in here
	               return $(this).val() == data[key]; 
	             }).prop("selected", true);
	        }
	        else
	        {
	            $('.Input[name="'+key+'"]').val(data[key]);
	            $('.Input[name="'+key+'"]').closest('.form-group').find('label[for="Name"]').html(data['Name'])
	        }
	    }
	    
	    $('#btnSave').attr('action','edit');
	    $('#btnSave').attr('data-id',ID);
	})

	$(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
	    var ID = $(this).attr('data-id');
	    var selector = $(this);
	    var action = $(this).attr('action');
	    App_form.ActionData(selector,action,ID);
	})
</script>