<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">List Need Approval</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;" id = "pageTableSurat">
        
    </div>
</div>
<script type="text/javascript">
	var oTable;
	var App_table = {
		Loaded : function(){
			$('#rowChooseDocument').removeClass('hide');
			loading_page('#pageTableSurat');
			App_table.DomListRequestDocument();
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
												'<th>Doc & User &nbsp <input type="checkbox" name="select_all" value="1" id="TblList-select-all"></th>'+
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
						'</div>'+
						'<div class = "row" style = "margin-top:5px;">'+
							'<div class = "col-md-12">'+
								'<div style = "padding: 5px;">'+
									'<button class="btn btn-block btn-success" id = "ApproveCheckList">Approve</button>'+
								'</div>'
							'</div>'+
						'</div>'			
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
		           url : base_url_js+"__request-document-generator/__NeedApproval", // json datasource
		           ordering : false,
		           type: "GET",  // method  , by default get
		        },
		         'columnDefs': [
		         	
		         	{
		         	   'targets': 0,
		         	   'searchable': false,
		         	   'orderable': false,
		         	   'className': 'dt-body-center',
		         	   'render': function (data, type, full, meta){
		         	       var ht = '<input type="checkbox" name="id[]" value="' + full[6] + '">'+' <span class="badge">'+full[0]+'</span>'+
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
		               // 'className': 'dt-body-center',
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
		               	   var btnApprove = '';
		               	   var btnReject = ''; 
		               	   var stRow = tokenRow['Status'];
		               	   if (stRow !='Batal' && stRow !='Approve') {
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

		Status : ['Request'],
		filtering : [
			{
				text : 'For me',
				value : '1',
			},
		],

		__GenerateByData_settingTemplate : function(dt,settingTemplate){
			for (var i = 0; i < settingTemplate.USER.length; i++) {
				settingTemplate.USER[i] = settingTemplate.USER[i]+'.'+dt['UserNIP'];
			}

			// for input
			for (var i = 0; i < settingTemplate.INPUT.length; i++) {
				for (key in dt){
					if (key == settingTemplate.INPUT[i].mapping) {
						settingTemplate.INPUT[i].value = dt[key];
						break;
					}
				}
			}

			var InputJson = dt['InputJson'];
			
			InputJson =  jQuery.parseJSON( InputJson )
			if (InputJson != null && InputJson != '') {
				// for table
				if (InputJson['TABLE'] !== undefined) {
					 settingTemplate['TABLE']['paramsUser'] = InputJson['TABLE'];
				}

				// for GET
				if (InputJson['GET'] !== undefined) {
					 settingTemplate['GET'] = InputJson['GET'];
				}

				// console.log(settingTemplate['GET']);

			}

			return settingTemplate;

		},

		ApproveOrReject : function(dataID,dt,approval_number,decision,Note=''){
			if (confirm('Are you sure ?')) {
				var url = base_url_js+"__request-document-generator/__ApproveOrReject";
				var settingTemplate = jQuery.parseJSON(dt['masterDocument'][0]['Config']);
				settingTemplate = App_table.__GenerateByData_settingTemplate(dt,settingTemplate);
			    var data = {
			       settingTemplate : settingTemplate,
			       ID : dt['ID_document'], // get from data
			       DepartmentID : dt['DepartmentID'],
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
			    		getNeedApproval();
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
			}
			
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

		ApproveByChecklist : function(selector,dt){
			if (confirm('Are you sure ?')) {
				var data = [];
				for (var i = 0; i < dt.length; i++) {
					var temp = {};
					var settingTemplate = jQuery.parseJSON(dt[i]['dt']['masterDocument'][0]['Config']);
					settingTemplate = App_table.__GenerateByData_settingTemplate(dt[i]['dt'],settingTemplate);
					var ID = dt[i]['dt']['ID_document'];
					var DepartmentID = dt[i]['dt']['DepartmentID'];
					var dataID = dt[i]['ID'];
					var approval_number = dt[i]['approval_number'];

					var temp = {
						settingTemplate : settingTemplate,
						ID : ID,
						DepartmentID : DepartmentID,
						dataID : dataID,
						approval_number : approval_number,
					};

					data.push(temp);
				}
				// console.log(data);
				// return;
				var url = base_url_js+"__request-document-generator/__ApproveByChecklist";
				var token =  jwt_encode(data,'UAP)(*');
				loadingStart();
			    AjaxSubmitTemplate(url,token).then(function(response){
			    	if (response == 1) {
			    		toastr.success('Saved');
			    	}
			    	else
			    	{
			    	    toastr.error('Something error,please try again');
			    	}
			    	oTable.ajax.reload( null, false );
			    	getNeedApproval();
			    	loadingEnd(1000);
				}).fail(function(response){
			        toastr.error('Connection error,please try again');
			        oTable.ajax.reload( null, false );
			        getNeedApproval();
			        loadingEnd(500);
			    })
			}
		}, 
	};

	$(document).ready(function(){
		App_table.Loaded();
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

	// Handle click on "Select all" control
	$(document).off('click', '#TblList-select-all').on('click', '#TblList-select-all',function(e) {
	   // Get all rows with search applied
	   var rows = oTable.rows({ 'search': 'applied' }).nodes();
	   // Check/uncheck checkboxes for all rows in the table
	   $('input[type="checkbox"]', rows).prop('checked', this.checked);
	});

	$(document).off('click', '#ApproveCheckList').on('click', '#ApproveCheckList',function(e) {
		var Arr = [];
		var selector = $(this);
		oTable.$('input[type="checkbox"]:checked').each(function(){
		  var tr = $(this).closest('tr');
		  var approval_number =  tr.find('.btnApprove').attr('approval_number');
		  var dt = jwt_decode(tr.find('.btnApprove').attr('data'));
		  var v = $(this).val();
		  var temp = {
		  	ID : v,
		  	approval_number : approval_number,
		  	dt : dt,
		  }
		  Arr.push(temp);
		}); // exit each function

		App_table.ApproveByChecklist(selector,Arr);
	})

	
</script>