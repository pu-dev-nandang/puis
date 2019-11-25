<style type="text/css">
	.row {
	    margin-right: 0px;
	    margin-left: 0px;
	}
</style>
<div class="row" style="margin-top: 10px;">
	<div class="col-md-4 col-md-offset-4">
		<div class="well">
			<div style="text-align: center;"><h4><b>Ticket Data</b></h4></div>
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
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
			    <h4 class="panel-title">Assign To</h4>
			</div>
			<div class="panel-body" style="min-height: 100px;">
				<span data-smt="" class="btn btn btn-add-assign_to">
                    <i class="icon-plus"></i> Add
                </span>
                <div id="FormAssignTo" style="margin-top: 10px;"></div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
			    <h4 class="panel-title">Transfer To</h4>
			</div>
			<div class="panel-body" style="min-height: 100px;">
				<span data-smt="" class="btn btn btn-add-transfer_to">
                    <i class="icon-plus"></i> Add
                </span>
                <div id="FormTransferTo" style="margin-top: 10px;"></div>
			</div>
		</div>
	</div>
</div>
<br/>
<div class="pull-right">
	<button class="btn btn-success">Save</button>
</div>

<script type="text/javascript">
	var DataTicket = <?php echo json_encode($DataTicket) ?>;
	var DataCategory = <?php echo json_encode($DataCategory) ?>;
	var DataDisposition = <?php echo json_encode($DataDisposition) ?>;
	var DataEmployees = <?php echo json_encode($DataEmployees) ?>;
	var App_AssignTo = {
		DomContentForm : function(selector){
			var html = '';
			var valTextArea = '';
			if (DataDisposition.length == 0 && $('.form-assign-to').length == 0) {
				valTextArea = DataTicket[0].Message;
			}
			html += '<div class = "row form-assign-to">'+
						'<div class = "form-horizontal well">'+
							'<div class = "form-group">'+
								'<div class = "row">'+
									'<div class = "col-xs-3">'+
										'<label>'+'Category'+'</label>'+
									'</div>'+
									'<div class = "col-xs-6">'+
										'<select class="select2-select-00 full-width-fix input_assign_to" name = "CategoryDispositionID"></select>'+
									'</div>'+
								'</div>'+
							'</div>'+
							'<div class = "form-group">'+
								'<div class = "row">'+
									'<div class = "col-xs-3">'+
										'<label>'+'Message'+'</label>'+
									'</div>'+
									'<div class = "col-xs-6">'+
										 '<textarea class="form-control input_assign_to" rows="3" name="MessageDisposition">'+valTextArea+'</textarea>'+
									'</div>'+
								'</div>'+
							'</div>'+
							'<div class = "form-group">'+
								'<div class = "row">'+
									'<div class = "col-xs-3">'+
										'<label>'+'Due Date'+'</label>'+
									'</div>'+
									'<div class = "col-xs-4">'+
										'<div class="input-group input-append date datetimepicker">'+
				                            '<input data-format="yyyy-MM-dd" class="form-control input_assign_to" type="text" name = "DueDate" readonly="" value = "<?php echo date('Y-m-d') ?>">'+
				                            '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
				                		'</div>'+
									'</div>'+
								'</div>'+
							'</div>'+
							'<div class = "form-group">'+
								'<div class = "row">'+
									'<div class = "col-xs-3">'+
										'<label>'+'Worker'+'</label>'+
									'</div>'+
									'<div class = "col-xs-6">'+
										'<select class="select2-select-00 full-width-fix input_assign_to" multiple size="5" name="NIP">'+
										'</select>'+
									'</div>'+
								'</div>'+
							'</div>'+
							'<div style = "text-align:right;" >'+
								'<button class = "btn btn-danger removeRowAssignTo"><i class = "fa fa-trash"></i> Delete</button>'+
							'</div>'+
						'</div>'+
						'<hr/>'+
					'</div>';
			selector.append(html);

			var selectorCategory = $('.form-assign-to:last').find('.input_assign_to[name="CategoryDispositionID"]');
			App_set_ticket.LoadSelectOptionCategory(selectorCategory);
			var selectorEmployees = $('.form-assign-to:last').find('.input_assign_to[name="NIP"]');			 			
			App_set_ticket.LoadSelectOptionWorker(selectorEmployees);
			$('.form-assign-to:last').find('.datetimepicker').datetimepicker({
				useCurrent: false,
				format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
			});				 			
		},

		DomContentRemove : function(selector){
			var selector_closest = selector.closest('.form-assign-to');
			selector_closest.remove();
		}
	};

	var App_transfer_to = {
		DomContentForm : function(selector){
			var html = '';
			var valTextArea = '';
			if (DataDisposition.length == 0 && $('.form-transfer-to').length == 0) {
				valTextArea = DataTicket[0].Message;
			}
			html += '<div class = "row form-transfer-to">'+
						'<div class = "form-horizontal well">'+
							'<div class = "form-group">'+
								'<div class = "row">'+
									'<div class = "col-xs-3">'+
										'<label>'+'Category'+'</label>'+
									'</div>'+
									'<div class = "col-xs-6">'+
										'<select class="select2-select-00 full-width-fix input_transfer_to" name = "CategoryDispositionID"></select>'+
									'</div>'+
								'</div>'+
							'</div>'+
							'<div class = "form-group">'+
								'<div class = "row">'+
									'<div class = "col-xs-3">'+
										'<label>'+'Message'+'</label>'+
									'</div>'+
									'<div class = "col-xs-6">'+
										 '<textarea class="form-control input_transfer_to" rows="3" name="MessageDisposition">'+valTextArea+'</textarea>'+
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

			var selectorCategory = $('.form-transfer-to:last').find('.input_transfer_to[name="CategoryDispositionID"]');
			App_set_ticket.LoadSelectOptionCategory(selectorCategory,'transfer_to');
		},

		DomContentRemove : function(selector){
			var selector_closest = selector.closest('.form-transfer-to');
			selector_closest.remove();
		}
	};

	var App_set_ticket = {
		LoadSelectOptionCategory : function(selector,type="assign_to"){
			var CategorySelected = '';
			// get value Category
			var arr_selected_category = [];
			$('.input_assign_to[name = "CategoryDispositionID"]').each(function(){
				var v = $(this).find('option:selected').val();
				arr_selected_category.push(v);
			})

			if (DataDisposition.length == 0) {
				CategorySelected = DataTicket[0].CategoryID;
			}

			selector.empty();
			if (type == 'assign_to') {
				for (var i = 0; i < DataCategory.length; i++) {
					var check = App_set_ticket.excludeOptionCategory(DataCategory[i][3],arr_selected_category);
					if (DataCategory[i][4] == DepartmentID && check) {
						var selected = (CategorySelected == DataCategory[i][3]) ? 'selected' : '';
						selector.append(
						     '<option value = "'+DataCategory[i][3]+'" '+selected+' department = "'+DataCategory[i][7]+'" >'+DataCategory[i][7]+' - '+DataCategory[i][1]+'</option>'
						 );
					}
				}
			}
			else
			{
				for (var i = 0; i < DataCategory.length; i++) {
					if (DataCategory[i][4] != DepartmentID) {
						var selected = (CategorySelected == DataCategory[i][3]) ? 'selected' : '';
						selector.append(
						     '<option value = "'+DataCategory[i][3]+'" '+selected+' department = "'+DataCategory[i][7]+'" >'+DataCategory[i][7]+' - '+DataCategory[i][1]+'</option>'
						 );
					}
				}
			}
			
			selector.select2({

			});
			
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

		LoadSelectOptionWorker : function(selector){
			selector.empty();
			for (var i = 0; i < DataEmployees.length; i++) {
				var data = DataEmployees[i];
				selector.append('<option value="'+data.NIP+'">'+data.Name+'</option>')
			}
			selector.select2({allowClear: true});
		},	
	};

	$(document).ready(function(){

	})

	$(document).off('click', '.btn-add-assign_to').on('click', '.btn-add-assign_to',function(e) {
	   var selector = $('#FormAssignTo');
	   App_AssignTo.DomContentForm(selector);
	})

	$(document).off('click', '.removeRowAssignTo').on('click', '.removeRowAssignTo',function(e) {
	   var selector = $(this);
	   App_AssignTo.DomContentRemove(selector);
	})

	$(document).off('click', '.btn-add-transfer_to').on('click', '.btn-add-transfer_to',function(e) {
	   var selector = $('#FormTransferTo');
	   App_transfer_to.DomContentForm(selector);
	})
	
	$(document).off('click', '.removeRowtransferTo').on('click', '.removeRowtransferTo',function(e) {
	   var selector = $(this);
	   App_transfer_to.DomContentRemove(selector);
	})
		
</script>