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
		               	   var link = base_url_js+'uploads/document-generator/'+tokenRow['Path'];
		               	   var btnEdit = '';
		               	   var btnBatal = '';
		               	   var btnApprove = '';
		               	   var btnReject = '';
		               	   if (tokenRow['Approve1Status'] == 0 && tokenRow['UserNIP'] == sessionNIP ) {
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
	};

	$(document).ready(function(){
		App_table.Loaded();
	})

	$(document).off('change', '#opFilteringStatus,#opFilteringData').on('change', '#opFilteringStatus,#opFilteringData',function(e) {
		oTable.ajax.reload( null, false );
	})
</script>