<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="row" style="margin-top: 5px;" class="btn-read">
	 <div class="col-md-12">
	     <div class="widget box">
	         <div class="widget-header">
	             <h4 class="header"><i class="icon-reorder"></i>Daftar Menu</h4>
	             <div class="toolbar no-padding">
	                 <div class="btn-group">
	                   <span data-smt="" class="btn btn-xs btn-write btn_add_menu">
	                     <i class="icon-plus"></i> Add Menu
	                    </span>
	                 </div>
	             </div>
	         </div>
	         <div class="widget-content">
	             <div class="row">
	             	<div class="col-md-12">
	             		<table class="table table-striped table-bordered table-hover table-checkable" id = "example_budget">
	             			<thead>
	             				<tr>
	             					<th style = "text-align: center;background: #20485A;color: #FFFFFF;">
	             						MENU
	             					</th>
	             					<th style = "text-align: center;background: #20485A;color: #FFFFFF;">
	             						ICON (https://fontawesome.com/v4.7.0/icons/)
	             					</th>
	             					<th style = "text-align: center;background: #20485A;color: #FFFFFF;">
	             						DEPARTMENT
	             					</th>
	             					<th style = "text-align: center;background: #20485A;color: #FFFFFF;">
	             						SORT
	             					</th>
	             					<th style = "text-align: center;background: #20485A;color: #FFFFFF;">
	             						Action
	             					</th>
	             				</tr>
	             			</thead>
	             			<tbody id = "Tbodydatatable">
	             				
	             			</tbody>
	             		</table>
	             	</div>
	             </div>
	         </div>
	     </div>
	 </div>
</div>
<script type="text/javascript">
	var Arr_Department =  <?php echo json_encode($Arr_Department) ?>;
	var temp = {
		Abbr : "",
		Code : 0,
		Name1 : 'All Department',
		Name2 : 'All Department'
	}
	Arr_Department.push(temp); // add item all department
	var Arr_Menu =  <?php echo json_encode($Arr_Menu) ?>;
	var S_Table_example_budget = '';
	$(document).ready(function () {
       LoadData(Arr_Menu);
	});

	function LoadData(dt)
	{
		// console.log(Arr_Department);

		var html = '';
		var OPD = function(IDDepartement)
		{
			var h = '';
			h = '<select class = " form-control Department" style = "width : 80%">';
				for (var i = 0; i < Arr_Department.length; i++) {
					var selected = (IDDepartement == Arr_Department[i].Code) ? 'selected' : '';
					h += '<option value = "'+Arr_Department[i].Code+'" '+selected+' >'+Arr_Department[i].Name1+'</option>';
				}
			h += '</select>';	

			return h;
		}

		var table = $('#example_budget').DataTable({
		      "data" : dt,
		      'iDisplayLength' : 10,
		      'columnDefs': [
			      {
			         'targets': 0,
			         'searchable': false,
			         'orderable': false,
			         'className': 'dt-body-center',
			         'render': function (data, type, full, meta){
			             return '<input type="text" class="form-control NameMenu" value="'+full.Menu+'">'+
							'<div class = "hide">'+full.Menu+'</div>';
			         }
			      },
			      {
			         'targets': 1,
			         'render': function (data, type, full, meta){
			             return '<input type="text" class="form-control NameIcon" value="'+full.Icon+'">'+
							'<div class = "hide">'+full.Icon+'</div>';
			         }
			      },
			      {
			         'targets': 2,
			         'render': function (data, type, full, meta){
			             return OPD(full.IDDepartement)+
							'<div class = "hide">'+full.NameDepartement+'</div>';
			         }
			      },
			      {
			         'targets': 3,
			         'render': function (data, type, full, meta){
			             return '<input type="text" class="form-control Sort" value="'+full.Sort+'">'+
							'<div class = "hide">'+full.Sort+'</div>';
			         }
			      },
			       {
			         'targets': 4,
			         'render': function (data, type, full, meta){
			             return '<button class = "btn btn-primary btn-save btn-write" action = "edit">Save</button>&nbsp'+'<button class = "btn btn-danger btn-delete btn-write">Delete</button>';
			         }
			      },
		      ],
		      'createdRow': function( row, data, dataIndex ) {
		      		$(row).attr('id-key',data.ID);

		      },
		      'order': [[3, 'asc']],

		});
		S_Table_example_budget = table;
		var rows = S_Table_example_budget.rows({ 'search': 'applied' }).nodes();
	   // Check/uncheck checkboxes for all rows in the table
	    $('.Department[tabindex!="-1"]', rows).select2({
		    		    //allowClear: true
		});

		$('.Sort', rows).maskMoney({thousands:'', decimal:'', precision:0,allowZero: true});
		$('.Sort', rows).maskMoney('mask', '9894');
	}

	$(document).off('click', '.btn-save').on('click', '.btn-save',function(e) {
		var ev = $(this).closest('tr');
		var thiss = $(this);
		if (confirm('Are you sure ?')) {
			thiss.html('<i class="fa fa-refresh fa-spin fa-fw right-margin"></i> Loading...');
			thiss.prop('disabled',true);

		   var ID = ev.attr('id-key');
		   var Name = ev.find('.NameMenu');
		   var Icon = ev.find('.NameIcon');
		   var IDDepartement = ev.find('.IDDepartement option:selected');
		   var Sort = ev.find('.Sort');
		   var action = thiss.attr('action');
		   var url = base_url_js+"budgeting/menu/menu/save";
		   var data = {
			    action : action,
			    ID : ID,
			    Name : Name,
			    Icon : Icon,
			    IDDepartement : IDDepartement,
			    Sort : Sort,
			};
			var token = jwt_encode(data,"UAP)(*");
			$.post(url,{ token:token },function (resultJson) {

		    }).fail(function() {
			  toastr.info('No Result Data'); 
			}).always(function() {
			    thiss.prop('disabled',false).html('Save');          
			});
		}
	});

	$(document).off('click', '.btn_add_menu').on('click', '.btn_add_menu',function(e) {
		var OPD = function(IDDepartement)
		{
			var h = '';
			h = '<select class = " form-control Department" style = "width : 80%">';
				for (var i = 0; i < Arr_Department.length; i++) {
					var selected = (IDDepartement == Arr_Department[i].Code) ? 'selected' : '';
					h += '<option value = "'+Arr_Department[i].Code+'" '+selected+' >'+Arr_Department[i].Name1+'</option>';
				}
			h += '</select>';	

			return h;
		}

		$( S_Table_example_budget.table().body() )
		    .append('<tr>'+
		    			'<td>'+'<input type="text" class="form-control NameMenu" value=""><div class = "hide"></div>'+'</td>'+
		    			'<td>'+'<input type="text" class="form-control NameIcon" value=""><div class = "hide"></div>'+'</td>'+
		    			'<td>'+OPD(0)+'<div class = "hide"></div>'+'</td>'+
		    			'<td>'+'<input type="text" class="form-control Sort" value=""><div class = "hide"></div>'+'</td>'+
		    			'<td>'+'<button class = "btn btn-primary btn-save btn-write" action = "add">Save</button>&nbsp'+'<button class = "btn btn-danger btn-delete btn-write">Delete</button>'+'</td>'+
		    		'</tr>'	
		    			);

		    $('.Department[tabindex!="-1"]').select2({
			    		    //allowClear: true
			});

			$('.Sort').maskMoney({thousands:'', decimal:'', precision:0,allowZero: true});
			$('.Sort').maskMoney('mask', '9894');    
		

	});
</script>