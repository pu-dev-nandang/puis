<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">List</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;" id = "pageTableSurat">
        
    </div>
</div>
<script type="text/javascript">
	var App_table = {
		Loaded : function(){
			$('#rowChooseDocument').removeClass('hide');
			loading_page('#pageTableSurat');
			var firstLoad = setInterval(function () {
	            var SelectMasterSurat = $('#MasterSurat').val();
	            if(SelectMasterSurat!='' && SelectMasterSurat!=null ){
	                /*
	                    LoadAction
	                */
	                App_table.LoadPageDefaultTable();
	                clearInterval(firstLoad);
	            }
	        },1000);
	        setTimeout(function () {
	            clearInterval(firstLoad);
	        },5000);
		},

		LoadPageDefaultTable : function(){
			if (typeof msgMasterDocument !== 'undefined') {
			    $('#pageTableSurat').html('<p style="color:red;">'+msgMasterDocument+'</p>');
			}
		},

		__opStatus : function(dtselected=''){
			var html = '<select class = "form-control" id = "opFilteringStatus">';
			var dt = App_table.Status;
			for (var i = 0; i < dt.length; i++) {
				var selected = (dtselected == dt[i]) ? 'selected' : '';
				html += '<option value = "'+dt[i]+'">'+dt[i]+'</option>';
			}

			html += '</select>';
			return html;
		},

		__opFiltering : function(dtselected=''){
			var html = '<select class = "form-control" id = "opFilteringData">';
			var dt = App_table.filtering;
			for (var i = 0; i < dt.length; i++) {
				var selected = (dtselected == dt[i].value) ? 'selected' : '';
				html += '<option value = "'+dt[i].value+'">'+dt[i].text+'</option>';
			}

			html += '</select>';
			return html;
		},

		DomListRequestDocument : function(IDMasterSurat,TokenData){
			loading_page('#pageTableSurat');
			var opStatus = App_table.__opStatus("Request");
			var opFiltering = App_table.__opFiltering("%");
			var html = '<div class ="row" >'+
							'<div class = "col-md-6 col-md-offset-3">'+
								'<div class="row" >'+
									'<div class = "col-md-6">'+
										'<div class = "form-group">'+
											'<label>Status</label>'+
											opStatus+
										'</div>'+
									'</div>'+
									'<div class = "col-md-6">'+
										'<div class = "form-group">'+
											'<label>Filtering</label>'+
											opFiltering+
										'</div>'+
									'</div>'+
								'</div>'+
							'</div>'+
						'</div>'+
						'<div class = "row" style = "margin-top:5px;">'+
							'<div class = "col-md-12">'+
								'<div class = "table-responsive" id = "tblList">'+
									'<table class = "table table-striped" id = "TblList" style = "min-width: 650px;">'+
										'<thead>'+
											'<tr>'+
												'<th>Doc & User</th>'+
												'<th>Date</th>'+
												'<th>Approval</th>'+
												'<th>Status</th>'+
												'<th style ="width:20%;">Action</th>'+
											'</tr>'+
										'</thead>'+
										'<tbody></tbody>'+
									'</table>'+			
								'</div>'+
							'</div>'+
						'</div>';		
						;
			$('#pageTableSurat').html(html);
			/* Load Action table */
			App_table.LoadTable();

		},

		LoadTable : function(){
		   var recordTable = $('#TblList').DataTable({
		       "processing": true,
		       "serverSide": false,
		       "ajax":{
		           url : base_url_js+"__request-document-generator/__LoadTablebyUserRequest", // json datasource
		           ordering : false,
		           type: "post",  // method  , by default get
		           data : function(token){
		                 // Read values
		                  var data = {
		                         opFilteringStatus : $('#opFilteringStatus option:selected').val(),
		                         opFilteringData : $('#opFilteringData option:selected').val(),
		                         IDMasterSurat : $('#MasterSurat option:selected').val(),

		                     };
		                 // Append to data
		                 token.token = jwt_encode(data,'UAP)(*');
		           }                                                                     
		        },
		         'columnDefs': [
		         	
		         	{
		         	   'targets': 0,
		         	   // 'searchable': false,
		         	   // 'orderable': false,
		         	   'className': 'dt-body-center',
		         	   'render': function (data, type, full, meta){
		         	       var ht = '<span class="badge">'+full[0]+'</span>'+
		         	       			'<br><label>'+full[1]+'</label>'+
		         	       			'<br><span class="label label-primary">'+full[2]+'</span>'
		         	       			;
		         	       return ht;
		         	   }
		         	},
		         	{
		         	   'targets': 1,
		         	   // 'searchable': false,
		         	   // 'orderable': false,
		         	   'className': 'dt-body-center',
		         	   'render': function (data, type, full, meta){
		         	       var ht = full[3];
		         	       return ht;
		         	   }
		         	},
		         	{
		         	   'targets': 3,
		         	   // 'searchable': false,
		         	   // 'orderable': false,
		         	   'className': 'dt-body-center',
		         	   'render': function (data, type, full, meta){
		         	       var ht = '<span class="label label-info">'+full[5]+'</span>';
		         	       return ht;
		         	   }
		         	},
		            {
		               'targets': 2,
		               // 'searchable': false,
		               // 'orderable': false,
		               'className': 'dt-body-center',
		               'render': function (data, type, full, meta){
		                   var ht = full[4];
		                   return ht;
		               }
		            },
		            {
		               'targets': 4,
		               'searchable': false,
		               'orderable': false,
		               // 'className': 'dt-body-center',
		               'render': function (data, type, full, meta){
		               	   var tokenRow = jwt_decode(full[7]);
		               	   // console.log(tokenRow);
		               	   var link = base_url_js+'uploads/document-generator/'+tokenRow['Path'];
		               	   var btnEdit = '';
		               	   var btnBatal = '';
		               	   var btnApprove = '';
		               	   var btnReject = ''; 
		               	   var stRow = tokenRow['Status'];
		               	   if (stRow !='Batal' && stRow !='Approve') {
		               	   	if (tokenRow['Approve1Status'] != 1 && tokenRow['UserNIP'] == sessionNIP ) {
		               	   			btnEdit = '<li><a href="javascript:void(0);" class="btnEdit" data-id="'+full[6]+'" data = "'+full[7]+'"><i class="fa fa fa-edit"></i> Edit</a></li>';
		               	   			btnBatal = '<li><a href="javascript:void(0);" class="btnBatal" data-id="'+full[6]+'" data = "'+full[7]+'"><i class="fa fa fa-remove"></i> Batal</a></li>';
		               	   	}
		               	   	// for approval 1
		               	   	if (tokenRow['Approve1Status'] == 0 && tokenRow['Approve1'] == sessionNIP ) {
		               	   			btnApprove = '<li><a href="javascript:void(0);" class="btnApprove" data-id="'+full[6]+'" data = "'+full[7]+'" approval_number = "1"><i class="fa fa-floppy-o"></i> Approve</a></li>';
		               	   			btnReject = '<li><a href="javascript:void(0);" class="btnReject" data-id="'+full[6]+'" data = "'+full[7]+'" approval_number = "1"><i class="fa fa-minus-square"></i> Reject</a></li>';
		               	   	}

		               	   	// for approval 2
		               	   	if (tokenRow['Approve2Status'] == 0 && tokenRow['Approve2'] == sessionNIP && tokenRow['Approve1Status'] == 1 ) {
		               	   			btnApprove = '<li><a href="javascript:void(0);" class="btnApprove" data-id="'+full[6]+'" data = "'+full[7]+'" approval_number = "2"><i class="fa fa-floppy-o"></i> Approve</a></li>';
		               	   			btnReject = '<li><a href="javascript:void(0);" class="btnReject" data-id="'+full[6]+'" data = "'+full[7]+'" approval_number = "2"><i class="fa fa-minus-square"></i> Reject</a></li>';
		               	   	}

		               	   	// for approval 3
		               	   	if (tokenRow['Approve3Status'] == 0 && tokenRow['Approve3'] == sessionNIP && tokenRow['Approve2Status'] == 1 ) {
		               	   			btnApprove = '<li><a href="javascript:void(0);" class="btnApprove" data-id="'+full[6]+'" data = "'+full[7]+'" approval_number = "3"><i class="fa fa-floppy-o"></i> Approve</a></li>';
		               	   			btnReject = '<li><a href="javascript:void(0);" class="btnReject" data-id="'+full[6]+'" data = "'+full[7]+'" approval_number = "3"><i class="fa fa-minus-square"></i> Reject</a></li>';
		               	   	}

		               	   }

		               	   btnLog = '<li><a href="javascript:void(0);" class="btnLog" data-id="'+full[6]+'" data = "'+full[7]+'"><i class="fa fa-book"></i> History</a></li>';

		               	   // console.log(tokenRow);
		               	   var btnAction = '<div class="btn-group">' +
		               	       '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
		               	       '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
		               	       '  </button>' +
		               	       '  <ul class="dropdown-menu">' +
		               	       		'<li>'+'<a class="btnPreviewTable" href="'+link+'" target="_blank"><i class="fa fa-print"></i> Preview</a>'+'</li>'+
		               	      		btnEdit +
		               	       // '    <li role="separator" class="divider"></li>' +
		               	       		btnBatal +
		               	       // '    <li role="separator" class="divider"></li>' +
		               	       		btnApprove+
		               	       // '    <li role="separator" class="divider"></li>' +
		               	       		btnReject+
		               	       		btnLog+
		               	       '  </ul>' +
		               	       '</div>';

		                   // var ht = '<a class="btn btn-info btnPreviewTable" href="'+link+'" target="_blank">Preview</a>  &nbsp'+btnAction;
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

		Status : ['Request','Reject','Approve','Batal'],
		filtering : [
			{
				text : 'All',
				value : '%',
			},
			{
				text : 'For me',
				value : '1',
			},
		],

		form_edit : function(ID,dt){
			$('#btnSave').attr('action','edit');
			$('#btnSave').attr('data-id',ID);
			$('.Input').each(function(){
				var nm = $(this).attr('name');
				$(this).val(dt[nm]);
			})

			// special for table
			var InputJson = dt['InputJson'];
			if (InputJson != null && InputJson != '') {
				InputJson =  jQuery.parseJSON( InputJson )
				// for (key in InputJson){
				// 	$('.Input[field="PARAMS"][name="#'+key+'"] option').filter(function() {
				// 	   //may want to use $.trim in here
				// 	   return $(this).val() == InputJson[key]; 
				// 	}).prop("selected", true);
				// }

				// console.log(InputJson);

				if (InputJson['TABLE'] !== undefined) {
					var arr = InputJson['TABLE'];
					for (var i = 0; i < arr.length; i++) {
						var arrkey = arr[i];
						// console.log(arrkey);
						for(key in arrkey){
							if (key.substring(0, 1) == '#') {
								var elClosest = $('.form-group[keyindex="'+i+'"]');
								if ( elClosest.find('select .Input').length ) {
									elClosest.find('.Input[field="PARAMS"][name="'+key+'"] option').filter(function() {
									   //may want to use $.trim in here
									   return $(this).val() == arrkey[key]; 
									}).prop("selected", true);
								}
								else
								{
									elClosest.find('.Input').val(arrkey[key]);
								}
							}
						}
						
					}
				}


				// for GET
				if (InputJson['GET'] !== undefined) {
					for(key in InputJson['GET']){
						for (var i = 0; i < InputJson['GET'][key].length; i++) {
							var get_token = jwt_encode(InputJson['GET'][key][i], "UAP)(*");
							if (key == 'EMP') {
								$('.GET[keyindex="'+i+'"]').find('.Input[key="GET"][name="'+key+'"]').val( InputJson['GET'][key][i]['NIP'] );
								$('.GET[keyindex="'+i+'"]').find('label[for="Name"]').html( InputJson['GET'][key][i]['Name'] );
							}
							else
							{
								$('.GET[keyindex="'+i+'"]').find('.Input[key="GET"][name="'+key+'"]').val( InputJson['GET'][key][i]['NPM'] );
								$('.GET[keyindex="'+i+'"]').find('label[for="Name"]').html( InputJson['GET'][key][i]['Name'] );
							}

							$('.GET[keyindex="'+i+'"]').find('.Input[key="GET"][name="'+key+'"]').attr( 'datatoken',get_token );
							
						}
					}
				}
				
			}

			
		},

		ApproveOrReject : function(dataID,dt,approval_number,decision,Note=''){
			var url = base_url_js+"__request-document-generator/__ApproveOrReject";
		    var data = {
		       settingTemplate : settingTemplate,
		       ID : $('#MasterSurat option:selected').val(),
		       DepartmentID : DepartmentID,
		       dataID : dataID,
		       decision : decision,
		       approval_number : approval_number,
		       Note : Note,
		    }
		    var token =  jwt_encode(data,'UAP)(*');
		    loadingStart();
		    AjaxSubmitTemplate(url,token).then(function(response){
		    	if (response == 1) {
		    		toastr.success('Saved');
		    		oTable.ajax.reload( null, false );
		    	}
		    	else
		    	{
		    	    toastr.error('Something error,please try again');
		    	}
		    	loadingEnd(1000);
			}).fail(function(response){
		        toastr.error('Connection error,please try again');
		        loadingEnd(500);
		    })
		},

		Logdata : function(ID,dt){
				var url = base_url_js+"__request-document-generator/__logData";
			    var data = {
			       ID : ID,
			    }
			    var token =  jwt_encode(data,'UAP)(*');
			    AjaxSubmitTemplate(url,token).then(function(response){
			    	var html = '<div class = "row">'+
			    					'<div class = "col-md-12">'+
			    						'<div class = "table-responsive">'+
			    							'<table class = "table table-striped">'+
			    								'<thead>'+
			    									'<tr>'+
			    										'<th>Doc</th>'+
			    										'<th>History</th>'+
			    										'<th>By</th>'+
			    										'<th>At</th>'+
			    									'</tr>'+
			    								'</thead>'+
			    								'<tbody>';

			    	for (var i = 0; i < response.length; i++) {
						html += '<tr>'+
									'<td>'+response[i].DocumentName+'</td>'+
									'<td>'+response[i].Log+'</td>'+
									'<td>'+response[i].NameBy+'</td>'+
									'<td>'+response[i].CreatedAt+'</td>'+
								'</tr>';	
					}

					html +='</tbody></table>';
					html += '</div></div></div>';								

			    	var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>'+
			    	    '';
			    	$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'History'+'</h4>');
			    	$('#GlobalModalLarge .modal-body').html(html);
			    	$('#GlobalModalLarge .modal-footer').html(footer);
			    	$('#GlobalModalLarge').modal({
			    	    'show' : true,
			    	    'backdrop' : 'static'
			    	});
				}).fail(function(response){
			        toastr.error('Connection error,please try again');
			        
			    })
		}, 
	};

	$(document).ready(function(){
		App_table.Loaded();
	})

	$(document).off('change', '#opFilteringStatus,#opFilteringData').on('change', '#opFilteringStatus,#opFilteringData',function(e) {
		oTable.ajax.reload( null, false );
	})

	$(document).off('click', '.btnEdit').on('click', '.btnEdit',function(e) {
		var dataID = $(this).attr('data-id');
		var dataToken = jwt_decode($(this).attr('data'));
		App_table.form_edit(dataID,dataToken);
	})

	$(document).off('click', '.btnApprove').on('click', '.btnApprove',function(e) {
		var dataID = $(this).attr('data-id');
		var dataToken = jwt_decode($(this).attr('data'));
		var approval_number = $(this).attr('approval_number');
		App_table.ApproveOrReject(dataID,dataToken,approval_number,'Approve');
	})

	$(document).off('click', '.btnReject').on('click', '.btnReject',function(e) {
		var dataID = $(this).attr('data-id');
		var dataToken = $(this).attr('data');
		var approval_number = $(this).attr('approval_number');
		$('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Please Input Reason ! </b> <br>' +
		    '<textarea type = "text" class = "form-group" id ="NoteDel" style="margin: 0px 0px 15px; width: 329px;" maxlength="100" row="4" /><br>'+
		    '<button type="button" id="confirmYesReject" class="btn btn-primary" style="margin-right: 5px;" data-id="'+dataID+'" data="'+dataToken+'" approval_number = "'+approval_number+'">Yes</button>' +
		    '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
		    '</div>');
		$('#NotificationModal').modal('show');
	})

	$(document).off('click', '#confirmYesReject').on('click', '#confirmYesReject',function(e) {
		var dataID = $(this).attr('data-id');
		var dataToken = jwt_decode($(this).attr('data'));
		var approval_number = $(this).attr('approval_number');
		var Note = $('#NoteDel').val();
		App_table.ApproveOrReject(dataID,dataToken,approval_number,'Reject',Note);
	})

	

	$(document).off('click', '.btnLog').on('click', '.btnLog',function(e) {
		var dataID = $(this).attr('data-id');
		var dataToken = jwt_decode($(this).attr('data'));
		App_table.Logdata(dataID,dataToken);
	})
</script>