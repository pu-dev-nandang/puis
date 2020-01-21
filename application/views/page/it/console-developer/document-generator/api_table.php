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
		        <button class="btn btn-success" id="btnSave" action = "add" data-id="" disabled>Save</button>
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
	var App_query = {
		Loaded : function(){
			$('.Input').val('');
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

		RunQuery : function(selector,action="add",ID=""){
			var data = {};
			var c = 0; // for add parameter obj
			$('.Input').not('div').each(function(){
		        var field = $(this).attr('name');
		        var key = $(this).attr('key');
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

			var dataform = {
			    action : action,
			    data : data,
			    ID : ID,
			};
			loading_button2(selector);
			var url = base_url_js+"it/__request-document-generator/__sqlQueryLanguange";
			var token = jwt_encode(dataform,'UAP)(*');
			$.post(url,{token:token},function (resultJson) {

			}).done(function(response) {
				var st = response['status'];
				if (st == 2) {
					App_query.SetDomParams(response['data']);
				}
				else if(st==0){
					toastr.error(JSON.stringify(response['callback']));
				}
				else if(st==1)
				{
					// exceute query
					App_query.SetDomTBLResult(response['data']['query']);
				}
				end_loading_button2(selector,'Run');				
			}).fail(function() {
				end_loading_button2(selector,'Run');
			});

		},

		SetDomTBLResult : function(data){
			var selector =  $('#TBLQuery');

			if (data.length > 0) {
				// console.log(data);
				var arr_header = data[0];
				var html = '<div class = "well">'+
								'<div style = "padding:15px;">'+
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
				html  += '<div class = "row" style = "margin-top:5px;">'+
							'<div class = "col-md-12">'+
								'<div class = "form-group">'+
									'<label>API Name Table</label>'+
									'<input type = "text" class = "form-control Input" name = "ApiNameTable" />'+
								'</div>'+
							'</div>'+
						  '</div>';		
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

	};

	$(document).ready(function(e){
		App_query.Loaded();
	})

	$(document).off('click', '#BtnRun').on('click', '#BtnRun',function(e) {
	   var itsme = $(this);
	   App_query.RunQuery(itsme,'run');
	})

	$(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
	   var itsme = $(this);
	   App_query.RunQuery(itsme,'add');
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