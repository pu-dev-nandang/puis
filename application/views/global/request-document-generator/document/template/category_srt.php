<div class="row">
	<div class="col-md-12">
		<a class="btn btn-sm btn-warning" href="<?php echo base_url().'request-document-generator/Template' ?>">
			<i class="fa fa-chevron-left"></i> Back to Template
		</a>
	</div>
</div>

<div class="row" style="margin-top: 10px;">
	<div class="col-md-6">
		<div class="panel panel-default">
		    <div class="panel-heading">
		        <h4 class="panel-title">List Category Document</h4>
		    </div>
		    <div class="panel-body" style="min-height: 100px;" id = "pageTableCategorySurat">
		        <div class="row">
		        	<div class="col-md-12">
		        		<div class="table-responsive">
		        			<table class = "table table-striped" id = "TblCategorySrt" >
		        				<thead>
		        					<tr>
		        						<th>Name Category</th>
		        						<th style="width: 25%;">Desc</th>
		        						<th>Dept Created</th>
		        						<th>Updated</th>
		        						<th>Action</th>
		        					</tr>
		        				</thead>
		        				<tbody>
		        				</tbody>
		        			</table>
		        		</div>
		        	</div>
		        </div>
		    </div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-default">
		    <div class="panel-heading">
		        <h4 class="panel-title">Form Category Document</h4>
		    </div>
		    <div class="panel-body" style="min-height: 100px;" id ="pageFormCategorySurat">
		    	<div class="row">
		    		<div class="col-md-4">
		    			<div class="form-group">
		    				<label>Name</label>
		    				<input type="text" name="NameCategorySrt" class="form-control Input">
		    			</div>
		    		</div>
		    		<div class="col-md-8">
		    			<label>Desc</label>
		    			<textarea rows="3" name="DescSrt" class="form-control Input"></textarea>
		    		</div>
		    	</div>
		    	<div class="thumbnail" style="margin-top: 10px;">
		    		<div class="row">
		    			<div class="col-md-12">
		    				<div style="padding: 15px;">
		    					<h3><u><b>Pola Nomor Surat</b></u></h3>
		    				</div>
		    				<div class="row">
		    					<div class="col-md-4">
				    				<p style ="color : red;">Sample : 041/UAP/R/SKU/X/2019</p>
				    				<p>
		                                <b>Keterangan</b> : <br/>
		                                * 041 : Increment, 3 character <br/>  
		                                * UAP : Prefix <br/>+   
		                                * R : Prefix <br/>   
		                                * SKU : Prefix <br/>    
		                                * X : Bulan Romawi <br/>  
		                                * 2019 : Tahun <br/>
		                                * / : delimiter <br/>
		                            </p>
		    					</div>
		    					<div class="col-md-8">
		    						<div class="form-group">
		    							<label>Prefix</label>
		    							<input type = "text" class="form-control Config" value = "UAP/R/SKU"  field="PolaNoSurat" name = "prefix" key ="SET" />
		    						</div>
		    					</div>
		    				</div>
		    			</div>
		    		</div>
		    	</div>
		    </div>
		    <div class="panel-footer" style="text-align: right;">
		        <button class="btn btn-success" id="btnSaveCategorySrt" action = "add" data-id="">Save</button>
		    </div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var oTableCategorySrt;
	var App_category_srt = {
		Loaded : function(){
			App_category_srt.LoadTable();
			App_category_srt.setDefault();
		},

		setDefault : function(){
			$('.panel-footer').html('<button class="btn btn-success" id="btnSaveCategorySrt" action = "add" data-id="">Save</button>');
			$('.Config').val('UAP/R/SKU');
			$('.Input').val('');
			$('.Input[name="NameCategorySrt"]').focus();
		},

		LoadTable : function(){
			var recordTable = $('#TblCategorySrt').DataTable({
			    "processing": true,
			    "serverSide": false,
			     "iDisplayLength" : 25,
			    "ajax":{
			        url : base_url_js+"__request-document-generator/__LoadTableCategorySrt", // json datasource
			        ordering : false,
			        type: "post",  // method  , by default get
			        data : function(token){
			              // Read values
			               var data = {
			                      Active : 1,
			                  };
			              // Append to data
			              token.token = jwt_encode(data,'UAP)(*');
			        }                                                                     
			     },
			      'columnDefs': [
			      	{
			      	   'targets': 1,
			      	   // 'searchable': false,
			      	   // 'orderable': false,
			      	   'className': 'dt-body-center',
			      	   'render': function (data, type, full, meta){
			      	       var ht = nl2br(full[1]);
			      	       return ht;
			      	   }
			      	},
			      	{
			      	   'targets': 3,
			      	   // 'searchable': false,
			      	   // 'orderable': false,
			      	   'className': 'dt-body-center',
			      	   'render': function (data, type, full, meta){
			      	       var ht = '<span class="label label-info">'+full[4]+'</span><br>'+
			      	       			'<label>'+full[5]+'</label>'	
			      	       			;
			      	       return ht;
			      	   }
			      	},
			         {
			            'targets': 4,
			            'searchable': false,
			            'orderable': false,
			            // 'className': 'dt-body-center',
			            'render': function (data, type, full, meta){
			            	   var TokenRead = jwt_decode(full[7]);
			            	   var btnEdit = '';
			            	   var btnRemove = '';
			            	   if (DepartmentID == TokenRead['Department']) {
			            	   		btnEdit = '<li><a href="javascript:void(0);" class="btnEditCategorySrt" data-id="'+full[6]+'" data = "'+full[7]+'"><i class="fa fa fa-edit"></i> Edit</a></li>';
			            	   		btnRemove = '<li><a href="javascript:void(0);" class="btnRemoveCategorySrt" data-id="'+full[6]+'" data = "'+full[7]+'"><i class="fa fa fa-remove"></i> Remove</a></li>';
			            	   }

			            	   var btnAction = '<div class="btn-group">' +
			            	       '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
			            	       '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
			            	       '  </button>' +
			            	       '  <ul class="dropdown-menu">' +
			            	      		btnEdit +
			            	       		btnRemove +
			            	       '  </ul>' +
			            	       '</div>';
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
			
			oTableCategorySrt = recordTable;
		},

		Submit : function(selector,action,ID){
			var data = {};
			$('.Input').not('div').each(function(){
				var field = $(this).attr('name');
				var v = $(this).val();
				data[field] = v;
			})

			var Config = {};
			Config['SET'] = {};
			Config['SET'] = {
				PolaNoSurat : {
					setting : {
						prefix : $('.Config[name="prefix"]').val(),
					},
					value : 'method_default',
					sample : "041/UAP/R/SKU/X/2019",

				},
			};

			data['Config'] = Config;
			var dataform = {
				data : data,
				ID : ID,
				action : action,
			}
			var token = jwt_encode(dataform,'UAP)(*');
			var url = base_url_js+"document-generator-action/__submit_CategorySrt";
			var validation = (action != 'delete') ? App_category_srt.validation_check(data) : true;
			if (validation) {
				if (confirm('Are you sure ?')) {
					loading_button2(selector);
					AjaxSubmitTemplate(url,token).then(function(response){
					    if (response.status == 1) {
					       toastr.success('Saved'); 
					       App_category_srt.setDefault();
					       oTableCategorySrt.ajax.reload( null, false );
					    }
					    else
					    {
					        toastr.error('Something error,please try again');
					        end_loading_button2(selector,'Save');
					    }
					}).fail(function(response){
					   toastr.error('Connection error,please try again');
					   end_loading_button2(selector,'Save');
					})
				}
			}
			

		},

		validation_check : function(arr){
			var toatString = "";
			var result = "";
			for(key in arr){
			   switch(key)
			   {
			    default :
			          result = Validation_required(arr[key],key);
			          if (result['status'] == 0) {
			            toatString += result['messages'] + "<br>";
			          }
			          break;
			   }
			}

			if (toatString != "") {
			  toastr.error(toatString, 'Failed!!');
			  return false;
			}
			return true
		},
	}

	$(document).ready(function(e){
		App_category_srt.Loaded();
	})

	$(document).off('click', '#btnSaveCategorySrt').on('click', '#btnSaveCategorySrt',function(e) {
	   var itsme = $(this);
	   var action=itsme.attr('action');
	   var ID = itsme.attr('data-id');
	   App_category_srt.Submit(itsme,action,ID);
	})

	$(document).off('click', '.btnRemoveCategorySrt').on('click', '.btnRemoveCategorySrt',function(e) {
		var itsme = $(this);
		var action='delete';
		var ID = itsme.attr('data-id');
		App_category_srt.Submit(itsme,action,ID);
	})

	$(document).off('click', '.btnEditCategorySrt').on('click', '.btnEditCategorySrt',function(e) {
		var itsme = $(this);
		var ID = itsme.attr('data-id');
		var data = jwt_decode(itsme.attr('data'));
		var Config = jQuery.parseJSON(data['Config']);
		$('.Input').each(function(){
			var name = $(this).attr('name');
			for (key in data){
				if (key == name) {
					$(this).val(data[key]);
					break;
				}
			}
		})

		$('.Config[name="prefix"]').val(Config['SET']['PolaNoSurat']['setting']['prefix']);

		$('#btnSaveCategorySrt').attr('data-id',ID);
		$('#btnSaveCategorySrt').attr('action','edit');
	})
	
</script>