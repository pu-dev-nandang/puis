<style type="text/css">
    #datatable_STD.dataTable tbody tr:hover {
       background-color:#71d1eb !important;
       cursor: pointer;
    }

    #datatable_employees.dataTable tbody tr:hover {
       background-color:#71d1eb !important;
       cursor: pointer;
    }
</style>
<div class="row">
	<div class="col-md-9">
		<div class="panel panel-default">
		    <div class="panel-heading">
		        <h4 class="panel-title">Form</h4>
		    </div>
		    <div class="panel-body" style="min-height: 100px;" id ="pageForm">
		    	<div class="row">
		    		<div class="col-md-12">
		    			<div class="form-group">
		    				<label>SQL</label>
		    				<textarea class="form-control Input" name = "Query" id = "SQL" rows=" 5"></textarea>
		    				<p><b>Example :</b></p>
		    				<p style="color: red;">
		    					SELECT mk.NameEng as MataKuliahNameEng, cd.TotalSKS AS Credit,14 as Sesi FROM db_academic.schedule s
	                              LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
	                              LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
	                              LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID)
	                              WHERE s.SemesterID = <b style="color: black;">?</b> AND s.Coordinator = <b style="color: black;">?</b> GROUP BY s.ID
	                              UNION ALL
	                              SELECT mk2.NameEng as MataKuliahNameEng, cd.TotalSKS AS Credit,14 as Sesi FROM db_academic.schedule_details_course sdc2
	                              LEFT JOIN db_academic.schedule s2 ON (s2.ID = sdc2.ScheduleID)
	                              LEFT JOIN db_academic.mata_kuliah mk2 ON (mk2.ID = sdc2.MKID)
	                              LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc2.CDID)
	                              LEFT JOIN db_academic.schedule_team_teaching stt ON (sdc2.ScheduleID = stt.ScheduleID)
	                              WHERE s2.SemesterID = <b style="color: black;">?</b> AND stt.NIP = <b style="color: black;">?</b> GROUP BY s2.ID
		    				</p>
		    				<p style="color: blue;">* (?) -> Penyisipan Parameter dalam query </p>
		    			</div>
		    			<div class="form-group">
		    				<label>Params</label>
		    				<input class="form-control Input" id = "Params" name="Params"></input>
		    				<p><b>Example :</b></p>
		    				<p style="color: red;">
		    					["#SemesterID","$NIP","#SemesterID","$NIP"]
		    				</p>
		    				<p style="color: blue;">* (#) -> Parameter yang diambil berdasarkan pilihan user </p>
		    				<p style="color: blue;">* ($) -> Parameter yang diambil berdasarkan session user </p>
		    			</div>
		    		</div>
		    	</div>
		    	<div id = "ParamsResult"></div>
		    	<div class="row" style="margin-top: 10px;">
		    		<div class="col-md-12" id = "TBLQuery">
		    			
		    		</div>
		    	</div>
		    </div>
		    <div class="panel-footer" style="text-align: right;">
		    	<button class="btn btn-primary" id ="BtnRun">Run</button>
		        <button class="btn btn-success" id="btnSave" action = "add" data-id="" disabled dt="">Save</button>
		    </div>
		</div>
	</div>
	<div class="col-md-3">
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
		        						<th>Name</th>
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
	var S_Table_example_emp;
	var S_Table_example_mhs;
	var S_Table_example_;
	var oTable;
	var App_query = {
		Loaded : function(){
			$('.Input').val('');
			App_query.LoadTable();
		},

		SetDomParams : function(dt){
			var selector = $('#ParamsResult');
			var html = '';
			// console.log(dt);
			for (var i = 0; i < dt.length; i++) {
				var arr = dt[i];
				var str = arr['name'];
				var strKey = str;
				if (str.substring(0, 1) == '#' ) {
					var keyObj = strKey.replace("#", "__");
				}
				else if(str.substring(0, 1) == '$'){
					var keyObj = strKey.replace("$", "__");
				}

				if (keyObj == '__NIP') {
					html += '<div class = "row" style = "margin-top:5px;" keyindex= "'+i+'">'+
								'<div class = "col-md-12">'+
									'<div class = "thumbnail">'+
										'<div style = "padding:10px;">'+
											'<label>Parameter : '+str+'</label>'+
											'<div class="input-group">'+
											    '<input type="text" class="form-control Input" readonly name="'+strKey+'" key="user">'+
											    '<span class="input-group-btn">'+
											        '<button class="btn btn-default SearchNIPEMP" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
											    '</span>'+
											'</div>'+
											'<label for="Name"></label>'+
										'</div>'+
									'</div>'+
								'</div>'+
							'</div>';
				}
				else if(keyObj == '__NPM'){
					html += '<div class = "row" style = "margin-top:5px;" keyindex= "'+i+'">'+
								'<div class = "col-md-12">'+
									'<div class = "thumbnail">'+
										'<div style = "padding:10px;">'+
											'<label>Parameter : '+str+'</label>'+
											'<div class="input-group">'+
											    '<input type="text" class="form-control Input" readonly name="'+strKey+'" key="user">'+
											    '<span class="input-group-btn">'+
											        '<button class="btn btn-default SearchNPMSTD" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
											    '</span>'+
											'</div>'+
											'<label for="Name"></label>'+
										'</div>'+
									'</div>'+
								'</div>'+
							'</div>';
				}
				else
				{
					var dtRow = arr[keyObj];
					var htmlOP = App_query.SelectAPIOPByParams(dtRow,strKey);
					html += '<div class = "row" style = "margin-top:5px;" keyindex= "'+i+'">'+
								'<div class = "col-md-12">'+
									'<div class = "thumbnail">'+
										'<div style = "padding:10px;">'+
											'<label>Parameter : '+str+'</label>'+
											htmlOP+
										'</div>'+
									'</div>'+
								'</div>'+
							'</div>';
				}

			}

			selector.html(html);
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
		          selector.closest('.input-group').find('.Input').val(datatoken['NIP']);
		          selector.closest('.input-group').find('.Input').attr('datatoken',row.attr('datatoken'));
		          selector.closest('.thumbnail').find('label[for="Name"]').html(datatoken['Name']);
		          $('#GlobalModalLarge').modal('hide');
		      });

		},

		SearchNPMSTD : function(selector){
		      var html = '';
		      html ='<div class = "row">'+
		              '<div class = "col-md-12">'+
		                '<div class="table-responsive">'+
		                  '<table id="datatable_STD" class="table table-bordered display select" cellspacing="0" width="100%">'+
				         '<thead>'+
				            '<tr>'+
				               '<th style = "width:5%;">No</th>'+
				               '<th>NPM - Name</th>'+
				               '<th>Prodi</th>'+
				            '</tr>'+
				         '</thead>'+
				         '<tbody></tbody>'+
				    '</table></div></div></div>';

		      $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Select Students'+'</h4>');
		      $('#GlobalModalLarge .modal-body').html(html);
		      $('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');
		      $('#GlobalModalLarge').modal({
		          'show' : true,
		          'backdrop' : 'static'
		      });

		      var table = $('#datatable_STD').DataTable({
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
		              url: base_url_js + "rest3/__LoadStudents_server_side", // json datasource
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
		                  $(".datatable_STD-grid-error").html("");
		                  $("#datatable_STD-grid").append(
		                      '<tbody class="datatable_STD-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
		                  );
		                  $("#datatable_STD-grid_processing").css("display", "none");
		              }
		          },
		          'createdRow': function(row, data, dataIndex) {
		              var dt = jwt_decode(data.data);
		              $(row).attr('datatoken',data.data);
		              $(row).find('td:eq(1)').html(dt['NPM']+' - '+dt['Name']);
		              $(row).find('td:eq(2)').html(dt['ProdiName']);
		          },
		          dom: 'l<"toolbar">frtip',
		          "initComplete": function(settings, json) {

		          }
		      });

		      S_Table_example_mhs = table;

		      S_Table_example_mhs.on( 'click', 'tr', function (e) {
		          var row = $(this);
		          var datatoken = jwt_decode(row.attr('datatoken'));
		          selector.closest('.input-group').find('.Input').val(datatoken['NPM']);
		          selector.closest('.input-group').find('.Input').attr('datatoken',row.attr('datatoken'));
		          selector.closest('.thumbnail').find('label[for="Name"]').html(datatoken['Name']);
		          $('#GlobalModalLarge').modal('hide');
		      });

		},

		SelectAPIOPByParams : function(data,paramsChoose){
			var html =  '<select class = "form-control Input" name="'+paramsChoose+'" key = "user">';
			for (var i = 0; i < data.length; i++) {
			   var selected = (data[i].Selected == 1) ? 'selected'  : ''; 
			   html +=  '<option value = "'+data[i].ID+'" '+selected+' >'+data[i].Value+'</option>';
			}

			html  += '</select>';

			return html;
		},

		RunQuery : function(selector,action="add",ID="",DepartmentArr=[]){
			var data = {};
			var selectorHTML = selector.html();
			var c = 0; // for add parameter obj
			if (action != 'delete') {
				$('.Input').not('div').each(function(){
			        var field = $(this).attr('name');
			        var key = $(this).attr('key');
			        // console.log(field);
			        if (key == 'user' ) {
			        	if (c == 0) {
			        		data['user'] = [];
			        		c = 1;
			        	}
			        	var temp = [];
			        	var keyindex = parseInt($(this).closest('.row').attr('keyindex'));
			        	data['user'][keyindex] = {};
			        	data['user'][keyindex][field] = $(this).val();
			        }
			        else
			        {
			        	data[field] = $(this).val();
			        }
			        
			    })

			    if ( $('.Input[name="ApiNameTable"]').length ) {
			    	// validation
			    	var q = $('.Input[name="Query"]').val();
			    	var ap = $('.Input[name="ApiNameTable"]').val();
			    	// console.log(q);
			    	// console.log(ap);
			    	if ( q == '' || ap == ''  ) {
			    		toastr.info('Query & ApiNameTable are required');
			    		return;
			    	}
			    }
			}


			var dataform = {
			    action : action,
			    data : data,
			    ID : ID,
			};

			// console.log(dataform);

			if (action == 'add' || action == 'edit') {
				if (DepartmentArr.length == 0) {
					toastr.info('Please choose least one department');
					return;
				}
				dataform['DepartmentArr'] = DepartmentArr;
			}

			loading_button2(selector);
			var url = base_url_js+"it/__request-document-generator/__sqlQueryLanguange";
			var token = jwt_encode(dataform,'UAP)(*');
			$.post(url,{token:token},function (resultJson) {

			}).done(function(response) {
				var st = response['status'];
				if (st == 2) {
					App_query.SetDomParams(response['data']);
					$('#TBLQuery').empty();
				}
				else if(st==0){
					toastr.error(JSON.stringify(response['callback']));
					$('#ParamsResult').empty();
					$('#TBLQuery').empty();
				}
				else if(st==1)
				{
					if (action != 'run') {
						toastr.success('Success'); 
						location.reload();
					}
					else
					{
						// exceute query
						if (data['Params']=='') {
							$('#ParamsResult').empty();
						}
						App_query.SetDomTBLResult(response['data']['query']);

					}
				}
				end_loading_button2(selector,selectorHTML);				
			}).fail(function() {
				end_loading_button2(selector,selectorHTML);
			});

		},

		SetDomTBLResult : function(data){
			var selector =  $('#TBLQuery');

			if (data.length > 0) {
				// console.log(data);
				var arr_header = data[0];
				var valueApiNameTable = '';
				var dt = $('#btnSave').attr('dt');
				// console.log(dt);
				if (dt != '') {
					var dt_decode = jwt_decode(dt);
					// console.log(dt_decode);
					if (dt_decode == undefined) {
						valueApiNameTable = '';
					}
					else
					{
						valueApiNameTable = dt_decode['ApiNameTable'];
					}
				}

				var html = '<div class = "well">';
				html  += '<div class = "row" style = "margin-top:5px;">'+
							'<div class = "col-md-12">'+
								'<div class = "form-group">'+
									'<label>API Name Table</label>'+
									'<input type = "text" class = "form-control Input" name = "ApiNameTable" value = "'+valueApiNameTable+'" />'+
								'</div>'+
							'</div>'+
						  '</div>';
									html  += '<div style = "padding:15px;">'+
												'<label>Query Result</label>'+
											'</div>'+
											'<div class = "row">'+
													'<div class = "col-md-12">'+
														'<div class = "table-responsive">';	
				html += '<table class="table" id = "TBLResultQuery"><thead><tr>';
				for (key in arr_header){
					html += '<th>'+key+'</th>';

				}

				html +='</tr></thead>';

				html +='<tbody>';

				for (var i = 0; i < data.length; i++) {
					var row = data[i];
					html += '<tr>';
					for (key in row){
						html += '<td>'+row[key]+'</td>';
					}

					html  += '</tr>';

				}

				html += '</tbody>';

				html += '</table>';
				html += '</div></div></div>';
						
				html += '</div>';

				selector.html(html);

				$('#btnSave').prop('disabled',false);
			}
			else
			{
				selector.html('<label>No data result</label>');
				$('#btnSave').prop('disabled',true);

			}
		},

		ShowModalDepartment : function(selector,action='add',ID='',get_data=[]){
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

		    $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Select Department'+'</h4>');
		    $('#GlobalModalLarge .modal-body').html(html);
		    $('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>'+
		        '<button type="button" id="ModalbtnSaveForm" action = "'+action+'" data-id = "'+ID+'" class="btn btn-success">Save</button>');
		    $('#GlobalModalLarge').modal({
		        'show' : true,
		        'backdrop' : 'static'
		    });
		    var url = base_url_js+'api/__getAllDepartementPU';
		    $.get( url, function( dt ) {
		        var table = $('#example_budget').DataTable({
		              "processing": true,
		              "serverSide": false,
		              "data" : dt,
		              'columnDefs': [
		                  {
		                     'targets': 0,
		                     'searchable': false,
		                     'orderable': false,
		                     'className': 'dt-body-center',
		                     'render': function (data, type, full, meta){
		                         var checked = '';
		                         // console.log(get_data);
		                         if (get_data['document_access_department'] != undefined) {
		                         	var document_access_department = get_data['document_access_department'];
		                         	for (var i = 0; i < document_access_department.length; i++) {
		                         		if (document_access_department[i]['Department'] == full.Code) {
		                         			checked = 'checked';
		                         			break;
		                         		}
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
		                    // console.log(data);
		              },
		              // 'order': [[1, 'asc']]
		        });

		        S_Table_example_ = table;
		    });

		},

		LoadTable : function(){
		   var recordTable = $('#TblList').DataTable({
		       "processing": true,
		       "serverSide": false,
		       "ajax":{
		           url : base_url_js+"it/__request-document-generator/__sqlQueryLanguange", // json datasource
		           ordering : false,
		           type: "POST",  // method  , by default get
		           data : function(token){
		                 // Read values
		                  var data = {
		                         action : 'read',
		                     };
		                 // Append to data
		                 token.token = jwt_encode(data,'UAP)(*');
		           }
		        },
		         'columnDefs': [
		            {
		               'targets': 1,
		               'searchable': false,
		               'orderable': false,
		               // 'className': 'dt-body-center',
		               'render': function (data, type, full, meta){
		               	   var btnEdit = '<li><a href="javascript:void(0);" class="btnEdit" data-id="'+full[1]+'" data = "'+full[2]+'"><i class="fa fa fa-edit"></i> Edit</a></li>';
		               	   var btnRemove = '<li><a href="javascript:void(0);" class="btnRemove" data-id="'+full[1]+'"><i class="fa fa fa-remove"></i> Remove</a></li>';

		               	   var btnAction = '<div class="btn-group">' +
		               	       '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
		               	       '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
		               	       '  </button>' +
		               	       '  <ul class="dropdown-menu">' +
		               	      		btnEdit +
		               	       // '    <li role="separator" class="divider"></li>' +
		               	       		btnRemove +
		               	       '  </ul>' +
		               	       '</div>';

		                   var ht = btnAction;
		                   return ht;
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

	$(document).ready(function(e){
		App_query.Loaded();
	})

	$(document).off('click', '.btnEdit').on('click', '.btnEdit',function(e) {
	   var itsme = $(this);
	   var ID = itsme.attr('data-id');
	   var data  = jwt_decode(itsme.attr('data'));
	   var action = 'edit';
	   $('#btnSave').attr('action',action);
	   $('#btnSave').attr('data-id',ID);
	   $('#btnSave').attr('dt',itsme.attr('data'));
	   // console.log(data);
	   
	   	$('.Input').each(function(e){
	   		var nm = $(this).attr('name');
	   		for (key in data){
	   			if (key == nm ) {
	   				$(this).val(data[key]);
	   				break;
	   			}
	   		}
	   	})

	   	$('#BtnRun').trigger('click');
	})

	$(document).off('click', '.btnRemove').on('click', '.btnRemove',function(e) {
		var itsme = $(this);
		var action = 'delete';
		var ID = itsme.attr('data-id');
		App_query.RunQuery(itsme,action,ID);
	})

	$(document).off('click', '#BtnRun').on('click', '#BtnRun',function(e) {
	   var itsme = $(this);
	   // $('#ParamsResult').empty();
	   // $('#TBLQuery').empty();
	   App_query.RunQuery(itsme,'run');
	})

	$(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
	   var itsme = $(this);
	   var action = itsme.attr('action');
	   var ID = itsme.attr('data-id');
	   var dt = [];
	   var get_dt = itsme.attr('dt');
	   if (get_dt != '') {
	   		dt = jwt_decode(get_dt);
	   		if (dt == undefined) {
	   			dt = [];
	   		}
	   }
	   App_query.ShowModalDepartment(itsme,action,ID,dt);
	})

	// Handle click on "Select all" control
	$(document).off('click', '#example-select-all').on('click', '#example-select-all',function(e) {
	   // Get all rows with search applied
	   var rows = S_Table_example_.rows({ 'search': 'applied' }).nodes();
	   // Check/uncheck checkboxes for all rows in the table
	   $('input[type="checkbox"]', rows).prop('checked', this.checked);
	});

	$(document).off('click', '#ModalbtnSaveForm').on('click', '#ModalbtnSaveForm',function(e) {
	   var itsme = $(this);
	   var action = itsme.attr('action');
	   var ID = itsme.attr('data-id');
	   var DepartmentArr = [];
	   S_Table_example_.$('input[type="checkbox"]:checked').each(function(){
	     var v = $(this).val();
	     var n = $(this).attr('dt');
	     var temp = {
	       Code : v,
	       Name : n,
	     };

	     DepartmentArr.push(temp);
	   }); // exit each function

	   App_query.RunQuery(itsme,action,ID,DepartmentArr);
	   
	})

	$(document).off('click', '.SearchNIPEMP').on('click', '.SearchNIPEMP',function(e) {
	   var itsme = $(this);
	   App_query.SearchNIPEMP(itsme);
	})

	$(document).off('click', '.SearchNPMSTD').on('click', '.SearchNPMSTD',function(e) {
	   var itsme = $(this);
	   App_query.SearchNPMSTD(itsme);
	})
</script>