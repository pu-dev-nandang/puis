<table class="table" id = "TBLSetting_Monthly_Report">
	<thead>
		<tr>
			<th>No</th>
			<th>Nama</th>
			<th>Departement / Prodi</th>
			<th>Access</th>
			<th>Updated at</th> 	
			<th>Updated by</th>
			<th>Action</th>	
		</tr>
	</thead>
	<tbody></tbody>
</table>
<script type="text/javascript">
	var AppData_Setting_Monthly_Report =  {
		loaded : function(){
		    AppData_Setting_Monthly_Report.LoadAjaxData();
		},
		LoadAjaxData : function(){
		         var recordTable = $('#TBLSetting_Monthly_Report').DataTable({
		             "processing": true,
		             "serverSide": false,
		             "ajax":{
		                 url : base_url_js+"rektorat/crud_setting_monthly_report", // json datasource
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
	                      // {
                       //           'targets': 5,
                       //           'searchable': false,
                       //           'orderable': false,
                       //           'className': 'dt-body-center',
                       //           'render': function (data, type, full, meta){
                       //              var FileJson = jQuery.parseJSON(full[5]);
                       //               var fileAhref =(full[5] == '' || full[5] == null || FileJson.length == 0) ? '' : '<a href = "'+base_url_js+'fileGetAny/rektorat-monthlyreport-'+FileJson[0]+'" target="_blank" class = "btn btn-xs btn-primary"><i class="fa fa-file-pdf-o"></i>View File'+'</a>';
                       //               return fileAhref;
                       //           }
                       //        }, 
	                   
	                      	{
	                      	   'targets': 6,
	                      	   'searchable': false,
	                      	   'orderable': false,
	                      	   'className': 'dt-body-center',
	                      	   'render': function (data, type, full, meta){
	                      	       var btnAction = '<div class="btn-group">' +
	                      	           '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
	                      	           '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
	                      	           '  </button>' +
	                      	           '  <ul class="dropdown-menu">' +
	                      	           '    <li><a href="javascript:void(0);" class="btnEdit" data-id="'+full[7]+'" data = "'+full[6]+'"><i class="fa fa fa-edit"></i> Edit</a></li>' +
	                      	           '    <li role="separator" class="divider"></li>' +
	                      	           '    <li><a href="javascript:void(0);" class="btnRemove" data-id="'+full[7]+'"><i class="fa fa fa-trash"></i> Remove</a></li>' +
	                      	           '  </ul>' +
	                      	           '</div>';
	                      	       return btnAction;
	                      	   }
	                      	},
	                  
	                      
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
	    AppData_Setting_Monthly_Report.loaded();
	})

		$(document).off('click', '.btn-delete-file').on('click', '.btn-delete-file',function(e) {
		    var Sthis = $(this);
		    var filePath = Sthis.attr('filepath');
		    var idtable = Sthis.attr('idtable');
		    var fieldwhere = Sthis.attr('fieldwhere');
		    var table = Sthis.attr('table');
		    var field = Sthis.attr('field');
		    var typefield = Sthis.attr('typefield');
		    var delimiter = Sthis.attr('delimiter');
		    AppForm_Setting_Monthly_Report.DeleteFile(Sthis,filePath,idtable,fieldwhere,table,field,typefield,delimiter);
		})
		$(document).off('click', '.btnRemove').on('click', '.btnRemove',function(e) {
			var ID = $(this).attr('data-id');
			var selector = $(this);
			AppForm_Setting_Monthly_Report.ActionData(selector,'delete',ID);
		})

		$(document).off('click', '.btnEdit').on('click', '.btnEdit',function(e) {
		    var ID = $(this).attr('data-id');
		    var Token = $(this).attr('data');
		    var data = jwt_decode(Token);
		    console.log(data);
		    $('.input').not('div').each(function(e){
		    	var key = $(this).attr('name');
		    	if (!$(this).is("select")) {
		    		$('.input[name="'+key+'"]').val(data[key]);
		    	}
		    	else
		    	{
		    		$(".input[name='"+key+"'] option").filter(function() {
		    		   //may want to use $.trim in here
		    		   return $(this).val() == data[key]; 
		    		}).prop("selected", true);
		    	}
		    })
		    $(".input[name='"+"NIP"+"']").select2({
		        //allowClear: true
		    }); 
	        
		    $('#btnSave').attr('action','edit');
		    $('#btnSave').attr('data-id',ID);
		})

</script>