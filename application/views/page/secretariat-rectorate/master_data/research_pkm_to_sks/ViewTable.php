<table class="table" id = "TBLResearch_Pkm_to_Sks">
	<thead>
		<tr>
			<th>No</th>
			<th>Jenis Publikasi</th>
			<th>SKS</th>
			<th>Updated_at</th>
			<th>Updated_by</th>
			<?php if (isset($action)): ?>
				<?php if ($action == 'write'): ?>
					<th>Action</th>	
				<?php endif ?>	
			<?php endif ?>
		</tr>
	</thead>
	<tbody></tbody>
</table>
<script type="text/javascript">
	// console.log('<?php echo $action ?>')
	var AppData_Research_Pkm_to_Sks =  {
		loaded : function(){
		    AppData_Research_Pkm_to_Sks.LoadAjaxData();
		},
		LoadAjaxData : function(){
		         var recordTable = $('#TBLResearch_Pkm_to_Sks').DataTable({
		             "processing": true,
		             "serverSide": false,
		             "ajax":{
		                 url : base_url_js+"secretariat-rectorate/master_data/crud_research_pkm_to_sks", // json datasource
		                 ordering : false,
		                 type: "post",  // method  , by default get
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
	                         'targets': 0,
	                         'searchable': false,
	                         'orderable': false,
	                         'className': 'dt-body-center',
	                      },
	                      
	                      <?php if (isset($action)): ?>
	                      <?php if ($action == 'write'): ?>
	                      	{
	                      	   'targets': 5,
	                      	   'searchable': false,
	                      	   'orderable': false,
	                      	   'className': 'dt-body-center',
	                      	   'render': function (data, type, full, meta){
	                      	   	// console.log('<?php echo $action ?>')
	                      	       var btnAction = '<div class="btn-group">' +
	                      	           '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
	                      	           '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
	                      	           '  </button>' +
	                      	           '  <ul class="dropdown-menu">' +
	                      	           '    <li><a href="javascript:void(0);" class="btnEdit" data-id="'+full[6]+'" data = "'+full[5]+'"><i class="fa fa fa-edit"></i> Edit</a></li>' +
	                      	           '  </ul>' +
	                      	           '</div>';
	                      	       return btnAction;
	                      	   }
	                      	},
	                      <?php endif ?>
	                      <?php endif ?>
	                      
	                   ],
		             'createdRow': function( row, data, dataIndex ) {
		                     
		             },
		             dom: 'l<"toolbar">frtip',
		             initComplete: function(){
		               
		            }  
		         });

		         recordTable.on( 'order.dt search.dt', function () {
		                     		        recordTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		                     		            cell.innerHTML = i+1;
		                     		        } );
		                     		    } ).draw();

		         oTable = recordTable;
		},
	};

	$(document).ready(function() {
	    AppData_Research_Pkm_to_Sks.loaded();
	})
	<?php if (isset($action)): ?>
	<?php if ($action == 'write'): ?>
		$(document).off('click', '.btnRemove').on('click', '.btnRemove',function(e) {
			var ID = $(this).attr('data-id');
			var selector = $(this);
			AppForm_Research_Pkm_to_Sks.ActionData(selector,'delete',ID);
		})

		$(document).off('click', '.btnEdit').on('click', '.btnEdit',function(e) {
		    var ID = $(this).attr('data-id');
		    var Token = $(this).attr('data');
		    var data = jwt_decode(Token);
		    for(var key in data) {
		       	$('.input[name="'+key+'"]').val(data[key]);
	        }
		    $('#btnSave').attr('action','edit');
		    $('#btnSave').attr('data-id',ID);
		    $('#btnSave').prop('disabled',false);
		})
	<?php endif ?>
	<?php endif ?>
</script>