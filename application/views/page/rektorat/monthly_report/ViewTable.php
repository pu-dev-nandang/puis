<?php if (count($auth) > 0): ?>
	<div class="row">
	    <div class="col-md-3 col-md-offset-4">
	        <div class="well">
	            <label>Departement</label>
				   <select class="select2-select-00 full-width-fix" id="Division">
				   	<?php if ($DivisionID == "NA.12" || $DivisionID == 'NA.2'): ?>
				   		<option value="%" selected > ALL </option>
				   	<?php endif ?>
	                  
	                <?php for($i = 0; $i < count($G_division); $i++): ?>
	                	<?php if ($DivisionID == "NA.12" || $DivisionID == 'NA.2'): ?>
	                		<option value="<?php echo $G_division[$i]['Code'] ?>" > <?php echo $G_division[$i]['Name2'] ?> </option>
	                	<?php else: ?>
	                		<?php if ($G_division[$i]['Code'] == $DivisionID): ?>
	                		<option value="<?php echo $G_division[$i]['Code'] ?>" > <?php echo $G_division[$i]['Name2'] ?> </option>	
	                		<?php endif ?>
	                	<?php endif ?>
	                	
	                  
	                <?php endfor ?>
	               </select>
	        </div>
	        <hr/>
	    </div>
	</div>
	<div class="row">    
	    <div class="col-md-12">
	    	<table class="table" id = "TBLMonthly_Report">
	    		<thead>
	    			<tr>
	    				<th>No</th>
	    				<th>Title</th>
	    				<th>Departement / Prodi</th>
	    				<th>Description</th>
	    				<th>Date Report</th>
	    				<th>File</th>
	    				<th>Updated at</th> 	
	    				<th>Updated by</th>
	    				<th>Action</th>	
	    			</tr>
	    		</thead>
	    		<tbody></tbody>
	    	</table>
	    </div>
	</div>
<?php else: ?>
	<div class="row">
		<div class="col-md-12">
			<div style="padding: 10px;text-align: center;">
				<h4>Unauthorize</h4>
			</div>
		</div>
	</div>
<?php endif ?>


<script type="text/javascript">
	var AppData_Monthly_Report =  {
		loaded : function(){
		    AppData_Monthly_Report.LoadAjaxData();
		},
		LoadAjaxData : function(){
		         var recordTable = $('#TBLMonthly_Report').DataTable({
		             "processing": true,
		             "serverSide": false,
		             "ajax":{
		                 url : base_url_js+"rektorat/crud_monthly_report", // json datasource
		                 ordering : false,
		                 type: "post",  // method  , by default get
		                 data : function(token){
                               // Read values
	                   			var data = {
	                                   action : 'read',
	                                   DivisionID : $('#Division option:selected').val(),	
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
	                      {
                                 'targets': 5,
                                 'searchable': false,
                                 'orderable': false,
                                 'className': 'dt-body-center',
                                 'render': function (data, type, full, meta){
                                    var FileJson = jQuery.parseJSON(full[5]);
                                    // console.log(FileJson);
                                     var fileAhref =(FileJson == '' || FileJson == null || FileJson.length == 0) ? '' : '<a href = "'+base_url_js+'fileGetAny/rektorat-monthlyreport-'+FileJson[0]+'" target="_blank" class = "btn btn-xs btn-primary"><i class="fa fa-file-pdf-o"></i>View File'+'</a>';
                                     return fileAhref;
                                 }
                              }, 
	                   
	                      	{
	                      	   'targets': 8,
	                      	   'searchable': false,
	                      	   'orderable': false,
	                      	   'className': 'dt-body-center',
	                      	   'render': function (data, type, full, meta){
	                      	       var btnAction = '<div class="btn-group">' +
	                      	           '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
	                      	           '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
	                      	           '  </button>' +
	                      	           '  <ul class="dropdown-menu">' +
	                      	           '    <li><a href="javascript:void(0);" class="btnEdit" data-id="'+full[9]+'" data = "'+full[8]+'"><i class="fa fa fa-edit"></i> Edit</a></li>' +
	                      	           '    <li role="separator" class="divider"></li>' +
	                      	           '    <li><a href="javascript:void(0);" class="btnRemove" data-id="'+full[9]+'"><i class="fa fa fa-trash"></i> Remove</a></li>' +
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
		DeleteFile : function(selector,filePath,idtable,fieldwhere,table,field,typefield,delimiter){
			var htmlbtn = selector.html();
            var li = selector.closest('li');
            var DeleteDb = {
                auth : 'Yes',
                detail : {
                    idtable : idtable,
                    fieldwhere : fieldwhere,
                    table : table,
                    field : field,
                    typefield : typefield,
                    delimiter : delimiter,
                },
            }

            if (confirm('Are you sure ?')) {
                 loading_button2(selector);
                 var url = base_url_js + 'rest2/__remove_file';
                 var data = {
                     filePath : filePath,
                     auth : 's3Cr3T-G4N',
                     DeleteDb :DeleteDb,
                 }

                 var token = jwt_encode(data,"UAP)(*");
                 $.post(url,{ token:token },function (resultJson) {
                     if (resultJson == 1) {
                         li.remove();
                         oTable.ajax.reload( null, false );
                     }
                     else{
                         toastr.error('', '!!!Failed');
                     }
                 }).fail(function() {
                   toastr.error('The Database connection error, please try again', 'Failed!!');
                 }).always(function() {
                     end_loading_button2(selector,htmlbtn);
                 });
            }
        },

	};

	$(document).ready(function() {
	    AppData_Monthly_Report.loaded();
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
	    AppForm_Monthly_Report.DeleteFile(Sthis,filePath,idtable,fieldwhere,table,field,typefield,delimiter);
	})
	$(document).off('click', '.btnRemove').on('click', '.btnRemove',function(e) {
		var ID = $(this).attr('data-id');
		var selector = $(this);
		AppForm_Monthly_Report.ActionData(selector,'delete',ID);
	})

	$(document).off('click', '.btnEdit').on('click', '.btnEdit',function(e) {
	    var ID = $(this).attr('data-id');
	    var Token = $(this).attr('data');
	    var data = jwt_decode(Token);
	    for(var key in data) {
	       	$('.input[name="'+key+'"]').val(data[key]);
        }
        if (data.FileUpload != null && data.FileUpload != '') {
         var FileJSon = jQuery.parseJSON(data.FileUpload);
         if (FileJSon.length > 0) {
            html = '<li style = "margin-top : 4px;"><a href = "'+base_url_js+'fileGetAny/rektorat-monthlyreport-'+FileJSon[0]+'" target="_blank" class = "Fileexist">File'+'</a>&nbsp<button class="btn-xs btn-default btn-delete btn-default-warning btn-custom btn-delete-file" filepath = "rektorat-monthlyreport-'+FileJSon[0]+'" type="button" idtable = "'+ID+'" table = "db_rektorat.monthly_report" field = "FileUpload" typefield = "1" delimiter = "" fieldwhere = "ID"><i class="fa fa-trash" aria-hidden="true"></i></button></li>';
            $('.fileShow').html(html);
         }
    }
	    $('#btnSave').attr('action','edit');
	    $('#btnSave').attr('data-id',ID);
	})


	$(document).on('change','#Division',function(e){
		oTable.ajax.reload(null, false);
	})
</script>