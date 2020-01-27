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
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Request Document</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;" id ="pageRequestDocument">

    </div>
    <div class="panel-footer" style="text-align: right;">
        <button class="btn btn-primary hide" id="Preview">Preview</button>
        <button class="btn btn-success" id="btnSave" action = "add" data-id="" disabled>Save</button>
    </div>
</div>
<script type="text/javascript">
	var settingTemplate = [];
	var S_Table_example_emp;
	var S_Table_example_mhs;
	var App_input = {
		Loaded : function(){
			loading_page('#pageRequestDocument');
			var firstLoad = setInterval(function () {
	            var SelectMasterSurat = $('#MasterSurat').val();
	            if(SelectMasterSurat!='' && SelectMasterSurat!=null ){
	                /*
	                    LoadAction
	                */
	                App_input.LoadPageDefaultInput();
	                clearInterval(firstLoad);
	            }
	        },1000);
	        setTimeout(function () {
	            clearInterval(firstLoad);
	        },5000);
		},

		LoadPageDefaultInput : function(){
			if (typeof msgMasterDocument !== 'undefined') {
			    $('#pageRequestDocument').html('<p style="color:red;">'+msgMasterDocument+'</p>');
			    $('#Preview').addClass('hide');
			    // $('#btnSave').prop('disabled',true);
			    $('#btnSave').attr('action','add');
			    $('#btnSave').attr('data-id','');
			    settingTemplate = [];
			}
		},

		DomRequestDocument : function(IDMasterSurat,TokenData){
			$('#Preview').addClass('hide');
			var dt = jwt_decode(TokenData);
			var DocumentName = dt.DocumentName;
			var DocumentAlias = dt.DocumentAlias;
			var Config = jQuery.parseJSON(dt.Config);
			settingTemplate = Config;
			App_input.DomSetTemplate(DocumentName,DocumentAlias,TokenData);
			$('#Preview').removeClass('hide');
			var ev = $('#btnSave').closest('.panel-footer');
			ev.find('#btnSave').remove();
			ev.append('<button class="btn btn-success" id="btnSave" action="add" data-id="" disabled="">Save</button>')
		},

		DomSetTemplate : function(DocumentName,DocumentAlias){
			var selectorPage = $('#pageRequestDocument');
			// defined page
			var html = 	'<div style = "padding:5px;">'+
                            '<h3><u><b>'+DocumentName+' / '+DocumentAlias+'</b></u></h3>'+
                        '</div>'+
						'<div class = "row">'+
							'<div class = "col-md-9" id = "Page_INPUT">'+
							'</div>'+
							'<div class = "col-md-3" id = "Page_Approval">'+
							'</div>'+
						'</div>'+
						'<div class = "row">'+
							'<div class = "col-md-6" id = "Page_TABLE">'+
							'</div>'+
							'<div class = "col-md-6" id = "Page_GET">'+
							'</div>'+
						'</div>';
			selectorPage.html(html);
			var selectorPage_INPUT = $('#Page_INPUT');
			App_input.DomSetPage_INPUT(selectorPage_INPUT,settingTemplate.INPUT);

			var selectorPage_Approval = $('#Page_Approval');
			App_input.DomSetPage_Approval(selectorPage_Approval,settingTemplate.SET.Signature);	

			var selectorPage_Table = $('#Page_TABLE');
			if (settingTemplate['TABLE']['KEY'] != undefined) {
				App_input.DomSetPage_TABLE(selectorPage_Table,settingTemplate.TABLE);	
			}

			if (settingTemplate['GET']['MHS'] != undefined || settingTemplate['GET']['EMP'] != undefined ) {
				App_input.DomSetPage_GET(settingTemplate.GET);	
			}

		},

		// GET

		DomSetPage_GET : function(dt){
			for(key in dt){
			    switch(key) {
			      case "EMP":
			        App_input.Dom_EMP(dt[key]);
			        break;
			      case "MHS":
			      App_input.Dom_MHS(dt[key]);
			        break;

			    }
			}
		},

		Dom_MHS : function(dt){
		    var selector = $('#Page_GET');
		    var html = '<div class = "thumbnail" style = "margin-top:5px;">';
		    for (var i = 0; i < dt.length; i++) {
		        var Choose = dt[i].Choose;
		        var keyNumber = dt[i].number;
		        switch(Choose) {
		          case "NPM":
		            html += '<div class = "row GET" keyindex = "'+i+'" keynumber="'+keyNumber+'">'+
		                        '<div class = "col-md-12">'+
		                            '<div style = "padding:5px;">'+
		                                '<h3><u><b>Students '+keyNumber+' </b></u></h3>'+
		                            '</div>'+
		                            '<div class = "form-group">'+
		                                '<label>Choose Students</label>'+
		                                '<div class="input-group">'+
		                                    '<input type="text" class="form-control Input" readonly name="'+key+'" key="GET" field="'+key+'">'+
		                                    '<span class="input-group-btn">'+
		                                        '<button class="btn btn-default SearchNPMSTD" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
		                                    '</span>'+
		                                '</div>'+
		                                '<label for="Name"></label>'+
		                            '</div>'+
		                        '</div>'+
		                    '</div>'        

		                        ;
		            break;

		        }
		    }

		    html += '</div>';
		    selector.html(html);
		},

		Dom_EMP : function(dt){
		    var selector = $('#Page_GET');
		    var html = '<div class = "thumbnail" style = "margin-top:5px;">';
		    // console.log(dt);
		    for (var i = 0; i < dt.length; i++) {
		        var Choose = dt[i].Choose;
		        var keyNumber = dt[i].number;
		        switch(Choose) {
		          case "NIP":
		            html += '<div class = "row GET" keyindex = "'+i+'" keynumber="'+keyNumber+'" >'+
		                        '<div class = "col-md-12">'+
		                            '<div style = "padding:5px;">'+
		                                '<h3><u><b>Employees '+keyNumber+' </b></u></h3>'+
		                            '</div>'+
		                            '<div class = "form-group">'+
		                                '<label>Choose Employees</label>'+
		                                '<div class="input-group">'+
		                                    '<input type="text" class="form-control Input" readonly name="'+key+'" key="GET" field="'+key+'">'+
		                                    '<span class="input-group-btn">'+
		                                        '<button class="btn btn-default SearchNIPEMP" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
		                                    '</span>'+
		                                '</div>'+
		                                '<label for="Name"></label>'+
		                            '</div>'+
		                        '</div>'+
		                    '</div>'        

		                        ;
		            break;

		        }
		    }

		    html += '</div>';
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
		          selector.closest('.form-group').find('label[for="Name"]').html(datatoken['Name']);
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
		          selector.closest('.form-group').find('label[for="Name"]').html(datatoken['Name']);
		          $('#GlobalModalLarge').modal('hide');
		      });

		},

		// END GET

		DomSetPage_TABLE : function(selector,dt){
			var html = '<div class = "thumbnail" style = "margin-top:5px;">'+
			                '<div class = "row">'+
			                    '<div class = "col-md-12">'+
			                        '<div style = "padding:15px;">'+
			                            '<h3><u><b>Parameter for Table</b></u></h3>'+
			                        '</div>';
			var paramsChoose = dt['API']['paramsChoose'];
			var paramsUser = dt['paramsUser'];
			// console.log(dt);
			for (var i = 0; i < paramsUser.length; i++) {
				var arr = paramsUser[i];
				for (key in arr){
					if (key == '#NIP') {
					  var dtRowEmp = dt['API']['selectEmployees'];
					  var htmlOPEMP = App_input.SelectAPIOPEMP(dtRowEmp,key);
					  html  += '<div class = "form-group" keyindex = "'+i+'">'+
					              '<label>Choose Parameter '+params[key]+'</label>'+
					              htmlOPEMP+
					          '</div>';
					}
					else if(key == '#NPM'){
						html  += '<div class = "form-group" keyindex = "'+i+'">'+
						            '<label>Choose Parameter '+key+'</label>'+
						            '<div class="input-group">'+
						                '<input type="text" class="form-control Input" readonly field="PARAMS" name="'+key+'" key="TABLE">'+
						                '<span class="input-group-btn">'+
						                    '<button class="btn btn-default SearchNPMSTD" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
						                '</span>'+
						            '</div>'+
						            '<label for="Name"></label>'+
						        '</div>';
					}
					else
					{
						
						if (key.substring(0, 1) == '#') {
							var dtRow = dt['API']['paramsChoose'][key];
							var htmlOP = App_input.SelectAPIOPByParams(dtRow,key);
							html += '<div class = "form-group" keyindex = "'+i+'">'+
							            '<label>Choose Parameter '+key+'</label>'+
							            htmlOP+
							        '</div>';  
						}
						
					}
				}
			}


			// console.log(dt);
			// for (key in paramsChoose){
			// 	var htmlOP = App_input.SelectAPIOPByParams(dt['API']['paramsChoose'][key],key);
			// 	html += '<div class = "form-group">'+
   //                          '<label>Choose '+key+'</label>'+
   //                          htmlOP+
   //                      '</div>';
			// }
			html  += '</div></div></div>';
			selector.html(html);
		},

		SelectAPIOPEMP : function(data,paramsChoose){
		    var html =  '<select class = "form-control Input" field="PARAMS" name="'+paramsChoose+'" key = "TABLE">';
		    for (var i = 0; i < data.length; i++) {
		       html +=  '<option value = "'+data[i].NIP+'">'+data[i].Name+'</option>';
		    }

		    html  += '</select>';

		    return html;
		},

		SelectAPIOPByParams : function(data,paramsChoose){
			var html =  '<select class = "form-control Input" field="PARAMS" name="'+paramsChoose+'" key = "TABLE">';
			for (var i = 0; i < data.length; i++) {
			   var selected = (data[i].Selected == 1) ? 'selected'  : ''; 
			   html +=  '<option value = "'+data[i].ID+'" '+selected+' >'+data[i].Value+'</option>';
			}

			html  += '</select>';

			return html;
		},

		DomSetPage_INPUT : function(selector,dt){
			var html = '<div class = "thumbnail" style = "margin-top:5px;">'+
			                '<div class = "row">'+
			                    '<div class = "col-md-12">'+
			                        '<div style = "padding:15px;">'+
			                            '<h3><u><b>Input by Request</b></u></h3>'+
			                        '</div>';
			for (var i = 0; i < dt.length; i++) {
				html  +=   '<div class = "form-group">'+
				                '<label>'+dt[i].field+'</label>'+
				                '<textarea class="form-control Input" name="'+dt[i].mapping+'" placeholder = "'+dt[i].value+'" />'+
				            '</div>';
			}

			html  += '</div></div></div>';
			selector.append(html);
		},

		DomSetPage_Approval : function(selector,dt){
			loading_anytext(selector,'Loading approval');
			// console.log(dt);
			var html = '<div class = "thumbnail" style = "margin-top:5px;">'+
			                '<div class = "row">'+
			                    '<div class = "col-md-12">'+
			                        '<div style = "padding:15px;">'+
			                            '<h3><u><b>Approval</b></u></h3>'+
			                        '</div>';
			/*  Check Need Approval or Not */
			var bool = false;
			for (var i = 0; i < dt.length; i++) {
				var userChoose = dt[i].user;
				var selectChoose = dt[i].select;
				for (var j = 0; j < selectChoose.length; j++) {
					var ID = selectChoose[j].ID;
					if (ID == userChoose) {
						if (i == 0) {
							bool = true;
							html += '<ul>';
						}
						var textVerify = (dt[i].verify == 1) ? 'Approve by System' : 'Approve manual';
						html += '<li style = "margin-left : -20px;">Approval '+(i+1)+' : '+'<span style="color:green;">'+selectChoose[j].Value+'</span>'+'<br/>'+'<label>'+textVerify+'</label>'+'</li>';
						break;
					}
				}
				
				if (bool) {
					html += '</ul>';
				}

			}

			selector.html(html);
			
		},

		SubmitPreviewPDF : function(selector){
			// console.log(settingTemplate.INPUT);
			for (var i = 0; i < settingTemplate.INPUT.length; i++) {
				$('.Input').each(function(e){
					var nameattr= $(this).attr('name');
					if (settingTemplate.INPUT[i].mapping == nameattr  ) {
						settingTemplate.INPUT[i].value = $(this).val();
						return;
					}
				})
			}

			// special for TABLE
			var data = {};
			$('.Input[key="TABLE"][field="PARAMS"]').each(function(e){
				var keyindex = parseInt($(this).closest('.form-group').attr('keyindex'));
				
				var nm = $(this).attr('name');
				var v = $(this).find('option:selected').val();
				if (v == undefined) {
				  v = $(this).val();
				}
				settingTemplate['TABLE']['paramsUser'][keyindex][nm] = v;
			})

			// console.log(settingTemplate);return;

			// FOR GET
			$('.Input[key="GET"]').each(function(e){
				var field = $(this).attr('field');
				var el = $(this);
				var keynumber = el.closest('.GET').attr('keynumber');
				var keyindex = parseInt(el.closest('.GET').attr('keyindex'));
				var dt = jwt_decode(el.attr('datatoken'));
				// console.log(dt);
				// settingTemplate['GET'][field][keyindex]['user'] = {};
				// settingTemplate['GET'][field][keyindex]['user'] = dt;
				settingTemplate['GET'][field][keyindex] = dt;
			})

			// console.log(settingTemplate['GET']);return;

			var url = base_url_js+"__request-document-generator/__previewbyUserRequest";
		    var data = {
		       settingTemplate : settingTemplate,
		       ID : $('#MasterSurat option:selected').val(),
		       DepartmentID : DepartmentID,
		    }
		    var token =  jwt_encode(data,'UAP)(*');
		    loading_button2(selector);
		    AjaxSubmitTemplate(url,token).then(function(response){
		    	if (response.status == 1) {
		    		$('#btnSave').prop('disabled',false);
		    	    window.open(response.callback, '_blank');
		    	}
		    	else
		    	{
		    	    toastr.error('Something error,please try again');
		    	}
		    	end_loading_button2(selector,'Preview');
			}).fail(function(response){
		        toastr.error('No data result');
		        end_loading_button2(selector,'Preview');
		    })
		},

		SaveData : function(selector,action,dataID=""){
			var url = base_url_js+"__request-document-generator/__savebyUserRequest";
		    var data = {
		       settingTemplate : settingTemplate,
		       ID : $('#MasterSurat option:selected').val(),
		       DepartmentID : DepartmentID,
		       action : action,
		       dataID : dataID,
		    }
		    var token =  jwt_encode(data,'UAP)(*');
		    loading_button2(selector);
		    AjaxSubmitTemplate(url,token).then(function(response){
		    	if (response == 1) {
		    		toastr.success('Saved');
		    	    $('#MasterSurat').trigger('change');
		    	    getNeedApproval();
		    	}
		    	else
		    	{
		    	    toastr.error('Something error,please try again');
		    	}
		    	end_loading_button2(selector,'Save');
			}).fail(function(response){
		        toastr.error('Connection error,please try again');
		        end_loading_button2(selector,'Save');
		    })
		},
	};

	$(document).ready(function(){
		App_input.Loaded();
	})

	$(document).off('click', '#Preview').on('click', '#Preview',function(e) {
	   var itsme = $(this);
	   App_input.SubmitPreviewPDF(itsme);
	})

	$(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
	   var itsme = $(this);
	   var action = itsme.attr('action');
	   var dataID = itsme.attr('data-id');
	   App_input.SaveData(itsme,action,dataID);
	})

	$(document).off('click', '.SearchNIPEMP').on('click', '.SearchNIPEMP',function(e) {
	   var itsme = $(this);
	   App_input.SearchNIPEMP(itsme);
	})

	$(document).off('click', '.SearchNPMSTD').on('click', '.SearchNPMSTD',function(e) {
	   var itsme = $(this);
	   App_input.SearchNPMSTD(itsme);
	})
</script>