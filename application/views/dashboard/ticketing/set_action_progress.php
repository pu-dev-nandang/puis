<?php $this->load->view('dashboard/ticketing/LoadCssTicketToday') ?>
<?php 
	$hide = ($Authent['callback']['Detail']['Admin']) ? '' : 'hide';

 ?>
<style type="text/css">
	.row {
	    margin-right: 0px;
	    margin-left: 0px;
	}
</style>
<div class="row" style="margin-top: 10px;">
	<div class="col-md-4">
		<div class="well">
			<div style="text-align: center;">
				<img data-src="<?php echo base_url('uploads/employees/'.$DataTicket[0]['PhotoRequested']); ?>" style="margin-top: -3px;" class="img-circle img-fitter" width="100">
				<h4><b>Ticket Data</b></h4>
			</div>
			<table class="table" id="tableDetailTicket">
				<tr>
					<td style="width: 25%;">NoTicket</td>
					<td>:</td>
					<td><?php echo $DataTicket[0]['NoTicket'] ?></td>
				</tr>
				<tr>
					<td style="width: 25%;">Title</td>
					<td>:</td>
					<td><?php echo $DataTicket[0]['Title'] ?></td>
				</tr>
				<tr>
					<td style="width: 25%;">Category</td>
					<td>:</td>
					<td><?php echo $DataTicket[0]['NameDepartmentDestination'].' - '.$DataTicket[0]['CategoryDescriptions'] ?></td>
				</tr>
				<tr>
					<td style="width: 25%;">Message</td>
					<td>:</td>
					<td><?php echo $DataTicket[0]['Message'] ?></td>
				</tr>
				<tr>
					<td style="width: 25%;">Requested by</td>
					<td>:</td>
					<td><?php echo $DataTicket[0]['NameRequested'] ?></td>
				</tr>
				<tr>
					<td style="width: 25%;">Requested on</td>
					<td>:</td>
					<td><?php echo $DataTicket[0]['RequestedAt'] ?></td>
				</tr>
			</table>
			<br/>
			<div id ="ShowProgressList">
				
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
			    <h4 class="panel-title">Assign To</h4>
			</div>
			<div class="panel-body" style="min-height: 100px;">
                <div id="PageAssignTo" style="margin-top: 10px;"></div>
			</div>
		</div>
	</div>
	<div class="col-md-4 <?php echo $hide ?>">
		<div class="panel panel-default">
			<div class="panel-heading">
			    <h4 class="panel-title">Transfer To</h4>
			</div>
			<div class="panel-body" style="min-height: 100px;">
				<span data-smt="" class="btn btn btn-add-transfer_to">
                    <i class="icon-plus"></i> Add
                </span>
                <div id="FormTransferTo" style="margin-top: 10px;"></div>
                <br/>
                <button class="btn btn-success hide" id = "SbmtTransferTo">Save</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var DataTicket = <?php echo json_encode($DataTicket) ?>;
	console.log(DataTicket);
	var DataAll = <?php echo json_encode($DataAll) ?>;
	var DataReceivedSelected = <?php echo json_encode($DataReceivedSelected) ?>;
	console.log(DataReceivedSelected);
	var Authent = <?php echo json_encode($Authent) ?>;
	var DataCategory = <?php echo json_encode($DataCategory) ?>;
	var DataEmployees = <?php echo json_encode($DataEmployees) ?>;
	var Auth = Authent.callback.Detail;
	var AdminAuth =Auth.Admin 
	var WorkerAuth =Auth.Worker 
	console.log(Authent);
	var App_set_action_progress = {
		Loaded : function(){
			var DataGet = DataAll[0];
			var htmlGetProgressList =  AppModalDetailTicket.tracking_list_html(DataGet);
			$('#ShowProgressList').html(htmlGetProgressList);
			var selector_AssignTo = $('#PageAssignTo');
			var selector_TransferTo = $('#PageTransferTo');
			App_AssignTo.DomContentForm(selector_AssignTo);
		},

		LoadSelectOptionCategory : function(selector,type="assign_to"){
			var CategorySelected = '';
			// get value Category
			var arr_selected_category = [];
			$('.input_assign_to[name = "CategoryReceivedID"]').each(function(){
				var v = $(this).find('option:selected').val();
				arr_selected_category.push(v);
			})
			CategorySelected = DataReceivedSelected[0].CategoryReceivedID;

			selector.empty();
			if (type == 'assign_to') {
				for (var i = 0; i < DataCategory.length; i++) {
					if (DataCategory[i][4] == DepartmentID) {
						var selected = (CategorySelected == DataCategory[i][3]) ? 'selected' : '';
						selector.append(
						     '<option value = "'+DataCategory[i][3]+'" '+selected+' department = "'+DataCategory[i][7]+'" code = "'+DataCategory[i][4]+'" >'+DataCategory[i][7]+' - '+DataCategory[i][1]+'</option>'
						 );
					}
				}
			}
			else
			{
				// get value Category
				var arr_selected_category2 = [];
				$('.input_transfer_to[name = "CategoryReceivedID"]').each(function(){
					var v = $(this).find('option:selected').val();
					arr_selected_category2.push(v);
				})
				for (var i = 0; i < DataCategory.length; i++) {
					var check = App_set_action_progress.excludeOptionCategory(DataCategory[i][3],arr_selected_category2);
					if (DataCategory[i][4] != DepartmentID && check) {
						var selected = (CategorySelected == DataCategory[i][3]) ? 'selected' : '';
						selector.append(
						     '<option value = "'+DataCategory[i][3]+'" '+selected+' department = "'+DataCategory[i][7]+'" code = "'+DataCategory[i][4]+'" >'+DataCategory[i][7]+' - '+DataCategory[i][1]+'</option>'
						 );
					}
				}
			}
			
			selector.select2({

			});
			
		},

		ActionClosedProject : function(selector){
			var arr_worker = DataReceivedSelected[0].DataReceived_Details;
			var bool = true;
			if (arr_worker.length > 0) {
				for (var i = 0; i < arr_worker.length; i++) {
					if (arr_worker[i].Status == "1") {
						bool = false;
						break;
					}
				}
			}
			else
			{
				// bool = false;
				bool = true;
			}
			
			if (!bool) {
				toastr.info('You can\'t close this project before all worker is done');
				return;
			}
			else
			{
				// DataReceivedSelected
				if (confirm('Are you sure ?')) {
					loadingStart();
				    loading_button2(selector);
				    var url = base_url_js+"rest_ticketing/__event_ticketing";
				    var dataform = {
				        action : 'close_project',
				        auth : 's3Cr3T-G4N',
				        ID : DataReceivedSelected[0].ID,
				        data : {
				        	SetAction : "0",
				        	ReceivedStatus : "1",
				        }
				    };

					var token = jwt_encode(dataform,'UAP)(*');
					AjaxSubmitRestTicketing(url,token).then(function(response){
					    if (response.status == 1) {
					    	toastr.success('Success');
					    	setInterval(function(){
					    	 window.location.href = base_url_js+'ticket/ticket-today'; 
					    	}, 3000);
					        
					    }
					    else
					    {
					        toastr.error(response.msg);
					        end_loading_button2(selector,'Close Project');
					    }
					}).fail(function(response){
					   toastr.error('Connection error,please try again');
					   end_loading_button2(selector,'Close Project');
					   loadingEnd(1000); 
					})    
					
				}
			}

		},

		CategoryChangeEvent  : function(selector,value,type="assign_to"){
			if (type =='assign_to') {
				var Index = $('.input_assign_to[name="CategoryReceivedID"]').index(selector);
				var bool = true;
				$('.input_assign_to[name="CategoryReceivedID"]:not(":eq('+Index+')")').each(function(){
					var v = $(this).val();
					if (value == v) {
						bool = false;
						return;
					}
				})

				if (!bool) {
					toastr.info('Category is exist, please check your value');
					App_set_action_progress.LoadSelectOptionCategory(selector);
				}
			}
			else
			{
				var Index = $('.input_transfer_to[name="CategoryReceivedID"]').index(selector);
				var bool = true;
				$('.input_transfer_to[name="CategoryReceivedID"]:not(":eq('+Index+')")').each(function(){
					var v = $(this).val();
					if (value == v) {
						bool = false;
						return;
					}
				})

				if (!bool) {
					toastr.info('Category is exist, please check your value');
					App_set_action_progress.LoadSelectOptionCategory(selector,'transfer_to');
				}
			}	
					
		},

		excludeOptionCategory : function(ID,arr_selected_category){
			var bool = true;
			for (var i = 0; i < arr_selected_category.length; i++) {
				if (ID == arr_selected_category[i]) {
					bool = false;
					break;
				}
			}

			return bool;
		},
	};

	var App_AssignTo = {
		DomWorkerHtml : function(dataworker){
			console.log(dataworker);
			var hide = (AdminAuth) ? '' : 'hide';
			var html = '<span class="btn btn btn-add-worker '+hide+' ">'+
                    '<i class="icon-plus"></i> Worker'+
                '</span>';
			html += '<table class = "table tableworker" style ="margin-top:15px;">'+
						'<thead>'+
			                '<tr>'+
			                    '<th style="padding:4px;">Worker</th>'+
			                    '<th style="padding:4px;">DueDate</th>'+
			                    '<th style="padding:4px;">Status</th>'+
			                    '<th style="padding:4px;">Action</th>'+
			                '</tr>'+
			            '</thead>';
			html += '<tbody>';    
			for (var i = 0; i < dataworker.length; i++) {
				var row = dataworker[i];
				var token = jwt_encode(row,'UAP)(*');
				var st = '';
				if (row.Status == "-1") {
				  st = 'withdrawn';
				}
				else if(row.Status == "1"){
				  st = 'working';
				}
				else{
				  st = 'done';
				}

				var hide = 'hide';
				if ( (sessionNIP == row.NIP  || AdminAuth ) ) {
					hide = '';
				}

            	html += '<tr>'+
            					'<td style="padding:4px;">'+row.NameWorker+'</td>'+
            					'<td style="padding:4px;">'+row.DueDateShow+'</td>'+
            					'<td style="padding:4px;">'+st+'</td>'+
            					'<td style="padding:4px;"><button class = "btn btn-default btnActionWorker '+hide+' " data-id = "'+row.ID+'" token = "'+token+'">Action</button></td>'+
            			'</tr>';		
            }

            html += '</tbody>';
            html += '</table>';
            return html;               
		},

		DomContentForm : function(selector){
			var html = '';
			var valTextArea = DataReceivedSelected[0].MessageReceived;
			var DomWorkerHtml = this.DomWorkerHtml(DataReceivedSelected[0].DataReceived_Details);
			console.log(DataReceivedSelected[0]);
			var hide = (AdminAuth) ? '' : 'hide';
			var dis = (AdminAuth) ? '' : 'disabled';
			html += '<div class = "row form-assign-to">'+
						'<div class = "form-horizontal well">'+
							'<div class = "form-group">'+
								'<div class = "row">'+
									'<div class = "col-xs-3">'+
										'<label>'+'Category'+'</label>'+
									'</div>'+
									'<div class = "col-xs-9">'+
										'<select class="select2-select-00 full-width-fix input_assign_to" name = "CategoryReceivedID" '+dis+' ></select>'+
									'</div>'+
								'</div>'+
							'</div>'+
							'<div class = "form-group">'+
								'<div class = "row">'+
									'<div class = "col-xs-3">'+
										'<label>'+'Message'+'</label>'+
									'</div>'+
									'<div class = "col-xs-9">'+
										 '<textarea class="form-control input_assign_to" rows="3" name="MessageReceived" '+dis+' >'+valTextArea+'</textarea>'+
									'</div>'+
								'</div>'+
							'</div>'+
							'<div style = "text-align:right;padding:15px;">'+
								'<button class = "btn btn-primary '+hide+' btnUpdateReceived " data-id = "'+DataReceivedSelected[0].ID+'" >Update</button>'+
							'</div>'+
							'<div class = "form-group" id = "ContentWorker">'+
								DomWorkerHtml+
							'</div>'+
						'</div>'+
						'<hr/>'+
						'<div style = "text-align:right;">'+
							'<button class = "btn btn-success '+hide+' btnCloseReceived " data-id = "'+DataReceivedSelected[0].ID+'">Close Project</button>'+
						'</div>'+
					'</div>';
			selector.html(html);

			var selectorCategory = $('.form-assign-to:last').find('.input_assign_to[name="CategoryReceivedID"]');
			App_set_action_progress.LoadSelectOptionCategory(selectorCategory);
		},

		ActionReceivedUpdate : function(selector,ID){
			if (confirm('Are you sure ?')) {
				loading_button2(selector);
				var url = base_url_js+"rest_ticketing/__event_ticketing";
				var data = {
					CategoryReceivedID : $('.input_assign_to[name="CategoryReceivedID"] option:selected').val(),
					MessageReceived : $('.input_assign_to[name="MessageReceived"]').val(),
				}
				var dataform = {
				    action : 'update_received',
				    auth : 's3Cr3T-G4N',
				    ID : ID,
				    data : data,
				};

				var token = jwt_encode(dataform,'UAP)(*');
				AjaxSubmitRestTicketing(url,token).then(function(response){
				    if (response.status == 1) {
				    	toastr.success('Success');
				    	end_loading_button2(selector);
				    }
				    else
				    {
				        toastr.error(response.msg);
				        end_loading_button2(selector);
				    }
				}).fail(function(response){
				   toastr.error('Connection error,please try again');
				   end_loading_button2(selector);  
				})
			}

		},

		LoadOptionStatusWorker : function(dataselected=''){

			var op = '<select class = "form-control input_modal_assign_to" name="Status" >';
			var arr_op = [];
			if (AdminAuth) {
				arr_op = ["-1","1","2"];
				arr_op = [{
					value : "-1",
					name  : "withdrawn",
				},
				{
					value : "1",
					name  : "working",
				},
				{
					value : "2",
					name  : "closed",
				},
				];
			}
			else
			{
				arr_op = [
				{
					value : "1",
					name  : "working",
				},
				{
					value : "2",
					name  : "closed",
				},
				];
			}

			for (var i = 0; i < arr_op.length; i++) {
				var selected = (dataselected == arr_op[i].value) ? 'selected' : '';
				op += '<option value = "'+arr_op[i].value+'" '+selected+' >'+arr_op[i].name+'</option>';
			}

			op += '</select>';
			return op;
		},

		ModalAction : function(ID,data){
			var trDate = '';
			if (AdminAuth) {
				trDate = '<tr>'+
							'<td>Due Date</td>'+
							'<td>'+
								'<div class="input-group input-append date datetimepicker">'+
		                            '<input data-format="yyyy-MM-dd" class="form-control input_modal_assign_to" type="text" name = "DueDate" readonly="" value = "'+data.DueDate+'">'+
		                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
		                		'</div>'+
		                	'</td>'	
							;
			}
			var htmlss = '<table class = "table">'+
							'<tr>'+
								'<td>Name</td>'+
								'<td>'+data.NameWorker+'</td>'+
							'</tr>'+
							trDate+
							'<tr>'+
								'<td>Status</td>'+
								'<td>'+this.LoadOptionStatusWorker(data.Status)+'</td>'+
							'</tr>'+
						'</table>';		
								;
			$('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
			    '<h4 class="modal-title">'+data.NameWorker+'</h4>');
			$('#GlobalModal .modal-body').html(htmlss);

			$('#GlobalModal .modal-footer').html('' +
			    '<button type="button" class="btn btn-success" id="btnsave_update_worker" data-id="'+data.ID+'">Submit</button> ' +
			    '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
			    '');

			$('#GlobalModal').modal({
			    'show' : true,
			    'backdrop' : 'static'
			});

			$('.datetimepicker').datetimepicker({
				useCurrent: false,
				format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
			});		
		},

		ActionUpdateWorker : function(selector,ID,action='update_worker'){
			if (confirm('Are you sure ?')) {
				loading_button2(selector);
				var url = base_url_js+"rest_ticketing/__event_ticketing";
				var data = {};
				$('.input_modal_assign_to').each(function(){
					var field = $(this).attr('name');
					var v = $(this).val();
					if (field != undefined) {
						data[field] = v;
					}
					
				})
				data['ReceivedID'] = DataReceivedSelected[0].ID;
				var dataform = {
				    action : action,
				    auth : 's3Cr3T-G4N',
				    ID : ID,
				    data : data,
				    datacallback : {
				    	NoTicket : DataTicket[0].NoTicket,
				    	DepartmentID : DataReceivedSelected[0].DepartmentReceivedID,
				    	NIP : sessionNIP,
				    },
				};
				// console.log(dataform);return;
				var token = jwt_encode(dataform,'UAP)(*');
				AjaxSubmitRestTicketing(url,token).then(function(response){
				    if (response.status == 1) {
				    	var callback = response.callback;
				    	DataTicket = callback.DataTicket;
				    	DataAll = callback.DataAll;
				    	DataReceivedSelected = callback.DataReceivedSelected;
				    	Authent = callback.Authent;
				    	var Auth = Authent.callback.Detail;
			    		var AdminAuth =Auth.Admin 
			    		var WorkerAuth =Auth.Worker
			    		App_set_action_progress.Loaded();
				    	toastr.success('Success');
				    	$('#GlobalModal').modal('hide');
				    	end_loading_button2(selector);
				    }
				    else
				    {
				        toastr.error(response.msg);
				        end_loading_button2(selector);
				    }
				}).fail(function(response){
				   toastr.error('Connection error,please try again');
				   end_loading_button2(selector);  
				})
			}
		},

		ModalAddWorker : function(){
			var trDate = '<tr>'+
						'<td>Due Date</td>'+
						'<td>'+
							'<div class="input-group input-append date datetimepicker">'+
	                            '<input data-format="yyyy-MM-dd" class="form-control input_modal_assign_to" type="text" name = "DueDate" readonly="" value = "<?php echo date('Y-m-d') ?>">'+
	                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
	                		'</div>'+
	                	'</td>'	
						;
			var htmlss = '<table class = "table">'+
							'<tr>'+
								'<td>Name</td>'+
								'<td>'+'<select class="select2-select-00 full-width-fix input_modal_assign_to" name="NIP">'+'</td>'+
							'</tr>'+
							trDate+
							'<tr>'+
								'<td>Status</td>'+
								'<td>'+this.LoadOptionStatusWorker("1")+'</td>'+
							'</tr>'+
						'</table>';		
								;
			$('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
			    '<h4 class="modal-title">'+'Add Worker'+'</h4>');
			$('#GlobalModal .modal-body').html(htmlss);

			$('#GlobalModal .modal-footer').html('' +
			    '<button type="button" class="btn btn-success" id="btnsave_insert_worker">Submit</button> ' +
			    '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
			    '');

			$('#GlobalModal').modal({
			    'show' : true,
			    'backdrop' : 'static'
			});

			$('.datetimepicker').datetimepicker({
				useCurrent: false,
				format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
			});

			var selectorEmployees = $('.input_modal_assign_to[name="NIP"]');			 			
			this.LoadSelectOptionWorker(selectorEmployees);
		},

		LoadSelectOptionWorker : function(selector){
			selector.empty();
			var dt = DataReceivedSelected[0].DataReceived_Details;
			for (var i = 0; i < DataEmployees.length; i++) {
				var data = DataEmployees[i];
				var bool = true;
				for (var j = 0; j < dt.length; j++) {
					if (dt[j].NIP==data.NIP) {
						bool = false;
						break;
					}
				}
				if (bool) {
					selector.append('<option value="'+data.NIP+'">'+data.Name+'</option>');
				}
			}
			selector.select2({allowClear: true});
		},
	};

	var App_transfer_to = {
		DomContentForm : function(selector){
			var html = '';
			var valTextArea = '';
			html += '<div class = "row form-transfer-to">'+
						'<div class = "form-horizontal well">'+
							'<div class = "form-group">'+
								'<div class = "row">'+
									'<div class = "col-xs-3">'+
										'<label>'+'Category'+'</label>'+
									'</div>'+
									'<div class = "col-xs-6">'+
										'<select class="select2-select-00 full-width-fix input_transfer_to" name = "CategoryReceivedID"></select>'+
									'</div>'+
								'</div>'+
							'</div>'+
							'<div class = "form-group">'+
								'<div class = "row">'+
									'<div class = "col-xs-3">'+
										'<label>'+'Message'+'</label>'+
									'</div>'+
									'<div class = "col-xs-6">'+
										 '<textarea class="form-control input_transfer_to" rows="3" name="MessageReceived">'+valTextArea+'</textarea>'+
									'</div>'+
								'</div>'+
							'</div>'+
							'<div style = "text-align:right;" >'+
								'<button class = "btn btn-danger removeRowtransferTo"><i class = "fa fa-trash"></i> Delete</button>'+
							'</div>'+
						'</div>'+
						'<hr/>'+
					'</div>';
			selector.append(html);

			var selectorCategory = $('.form-transfer-to:last').find('.input_transfer_to[name="CategoryReceivedID"]');
			App_set_action_progress.LoadSelectOptionCategory(selectorCategory,'transfer_to');
		},

		DomContentRemove : function(selector){
			var selector_closest = selector.closest('.form-transfer-to');
			selector_closest.remove();
		},

		SubmitSetActionTransferTo : function(selector){
			var bool = true;
			$('.form-transfer-to').each(function(){
				var Index = $('.form-transfer-to').index(this);
				var Index = parseInt(Index)+1;
				var itsme = $(this);
				var CategoryReceivedID =  itsme.find('.input_transfer_to[name="CategoryReceivedID"] option:selected').val();
				var MessageReceived =  itsme.find('.input_transfer_to[name="MessageReceived"]').val();
				if (MessageReceived == '' ||  MessageReceived == undefined) {
					toastr.info('Please check input Transfer To on index of '+Index);
					bool = false;
					return;
				}
			})

			if (bool) {
				var transfer_to = [];
				$('.form-transfer-to').each(function(){
					var Index = $('.form-transfer-to').index(this);
					var Index = parseInt(Index)+1;
					var itsme = $(this);
					var CategoryReceivedID =  itsme.find('.input_transfer_to[name="CategoryReceivedID"] option:selected').val();
					var DepartmentReceivedID =  itsme.find('.input_transfer_to[name="CategoryReceivedID"] option:selected').attr('code');
					var MessageReceived =  itsme.find('.input_transfer_to[name="MessageReceived"]').val();
					var tempreceived = {
						TicketID : DataTicket[0].ID,
						DepartmentReceivedID : DepartmentReceivedID,
						CategoryReceivedID : CategoryReceivedID,
						MessageReceived : MessageReceived,
						// ReceivedBy : sessionNIP,
						SetAction : '1',
						Flag : '1',
					};
					var received = DataReceivedSelected[0].DataReceived_Details;
					if (received.length == 0) {
						var postreceived = {
							ID : DataReceivedSelected[0].ID,
							action : 'update',
							data : {
								SetAction : '0',
								ReceivedStatus : "1",
								ReceivedBy : sessionNIP,
								DepartmentTransferToID : DepartmentReceivedID,
								// CategoryReceivedID : CategoryReceivedID,
							},
						}

						transfer_to.push(postreceived);
					}
					else
					{
						var postreceived = {
							ID : DataReceivedSelected[0].ID,
							action : 'update',
							data : {
								DepartmentTransferToID : DepartmentReceivedID,
								// CategoryReceivedID : CategoryReceivedID,
							},
						}

						transfer_to.push(postreceived);
					}

					var postreceived = {
						ID : '',
						action : 'insert',
						data : tempreceived,
						CreatedBy : sessionNIP,
					}

					transfer_to.push(postreceived);

				})

				if (confirm('Are you sure ?')) {
					loadingStart();
					loading_button2(selector);
					var url = base_url_js+"rest_ticketing/__event_ticketing";
					var dataform = {
					    action : 'received',
					    auth : 's3Cr3T-G4N',
					    data : {
					    	transfer_to : transfer_to,
					    },
					    datacallback : {
					    	NoTicket : DataTicket[0].NoTicket,
					    	DepartmentID : DataReceivedSelected[0].DepartmentReceivedID,
					    	NIP : sessionNIP,
					    },
					};

					var token = jwt_encode(dataform,'UAP)(*');
					AjaxSubmitRestTicketing(url,token).then(function(response){
					    if (response.status == 1) {
		    		    	var callback = response.callback;
		    		    	DataTicket = callback.DataTicket;
		    		    	DataAll = callback.DataAll;
		    		    	DataReceivedSelected = callback.DataReceivedSelected;
		    		    	Authent = callback.Authent;
		    		    	var Auth = Authent.callback.Detail;
		    	    		var AdminAuth =Auth.Admin 
		    	    		var WorkerAuth =Auth.Worker
		    	    		App_set_action_progress.Loaded();
					    	toastr.success('Success');
					    	setInterval(function(){
					    	 end_loading_button2(selector);
					    	 loadingEnd(100); 
					    	}, 3000);
					    }
					    else
					    {
					        toastr.error(response.msg);
					        end_loading_button2(selector);
					        loadingEnd(100);
					    }
					}).fail(function(response){
					   toastr.error('Connection error,please try again');
					   end_loading_button2(selector);  
					   loadingEnd(1000); 
					})

				}

			} 
		},

		ActionBTNShowHide : function(){
			if ($('.form-transfer-to').length) {
				$('#SbmtTransferTo').removeClass('hide');
			}
			else
			{
				$('#SbmtTransferTo').addClass('hide');
			}
		}
	};


	$(document).ready(function(){
		App_set_action_progress.Loaded();
	})

	$(document).off('click', '.btnUpdateReceived').on('click', '.btnUpdateReceived',function(e) {
	   var selector = $(this);
	   var ID = selector.attr('data-id');
	   App_AssignTo.ActionReceivedUpdate(selector,ID);
	})

	$(document).off('click', '.btnActionWorker').on('click', '.btnActionWorker',function(e) {
	   var selector = $(this);
	   var ID = selector.attr('data-id');
	   var data = jwt_decode(selector.attr('token'));
	   App_AssignTo.ModalAction(ID,data);
	})

	$(document).off('click', '#btnsave_update_worker').on('click', '#btnsave_update_worker',function(e) {
	   var selector = $(this);
	   var ID = selector.attr('data-id');
	   App_AssignTo.ActionUpdateWorker(selector,ID);
	})

	$(document).off('click', '.btn-add-worker').on('click', '.btn-add-worker',function(e) {
	   App_AssignTo.ModalAddWorker();
	})

	$(document).off('click', '#btnsave_insert_worker').on('click', '#btnsave_insert_worker',function(e) {
	   var selector = $(this);
	   var ID = '';
	   App_AssignTo.ActionUpdateWorker(selector,ID,'insert_worker');
	})
	
	$(document).off('click', '.btn-add-transfer_to').on('click', '.btn-add-transfer_to',function(e) {
	   var selector = $('#FormTransferTo');
	   App_transfer_to.DomContentForm(selector);
	   App_transfer_to.ActionBTNShowHide();
	})

	$(document).off('click', '.removeRowtransferTo').on('click', '.removeRowtransferTo',function(e) {
	   var selector = $(this);
	   App_transfer_to.DomContentRemove(selector);
	   App_transfer_to.ActionBTNShowHide();
	})

	$(document).off('change', '.input_transfer_to[name="CategoryReceivedID"]').on('change', '.input_transfer_to[name="CategoryReceivedID"]',function(e) {
	  var selector = $(this);
	  var value = $(this).val();
	  App_set_action_progress.CategoryChangeEvent(selector,value,'transfer_to');
	})

	$(document).off('click', '#SbmtTransferTo').on('click', '#SbmtTransferTo',function(e) {
		var selector = $(this);
		App_transfer_to.SubmitSetActionTransferTo(selector);
	})

	$(document).off('click', '.btnCloseReceived').on('click', '.btnCloseReceived',function(e) {
		var selector = $(this);
		App_set_action_progress.ActionClosedProject(selector);
	})

	
</script>