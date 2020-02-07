<?php $message = $this->session->flashdata('message');
	if(!empty($message)){ ?>
	<script type="text/javascript">
	$(document).ready(function(){
		toastr.info("<?= $this->session->flashdata('message');?>",'Info!');
	});
	</script>
<?php } ?>
<div id="access-role">
	<div class="row">
		<div class="col-sm-12" style="margin-bottom:10px">
			<a class="btn btn-warning" href="<?=site_url('global-informations/message-blast')?>" ><i class="fa fa-angle-double-left"></i> Back</a>
		</div>
		<div class="col-sm-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<i class="fa fa-edit"></i> Form Access Role
					</h4>
				</div>
				<div class="panel-body">
					<form id="form-access-role" action="<?=site_url('global-informations/message-blast/saveRoles')?>" method="post" autocomplte="off">
						<input type="hidden" class="ID" name="ID">
						<div class="row">
							<div class="col-sm-12">
								<label>Position Main</label>
							</div>
							<div class="col-sm-12">
								<div class="form-group">						
									<select class="form-control required division" required name="division">
										<option value="">-Choose Division-</option>
										<?php foreach ($division as $d) {
										echo '<option value="'.$d->ID.'">'.$d->Division.'</option>';
										} ?>
									</select>
									<small class="text-danger text-message"></small>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-groups">
						                  <label>Position</label>
						                </div>
						                <div style="overflow-y:auto;overflow-x:hidden;max-height:200px">
					                	<div class="row">
						                <?php if(!empty($position)){
						                foreach ($position as $p) { ?>
						                		<div class="col-sm-6">
						                			<div class="checkbox">
								                      <label>
								                        <input type="checkbox" class="position position-<?=$p->ID?>" value="<?=$p->ID?>" name="position[]" > <?=$p->Description?>
								                      </label>
							                      </div>
						                		</div>
						                <?php } } ?>
					                	</div>	
					                	</div>	
									</div>
								</div>	
								
							</div>
						</div>
						
						
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Is write mail ?</label>
									<select class="form-control required isWrite" required name="isWrite">
										<option value="">Choose One</option>
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
									<small class="text-danger text-message"></small>
								</div>								
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label>Is delete mail ?</label>
									<select class="form-control required isDelete" required name="isDelete">
										<option value="">Choose One</option>
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
									<small class="text-danger text-message"></small>
								</div>								
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label>Can view all mail ?</label>
									<select class="form-control required isView" required name="isView">
										<option value="">Choose One</option>
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
									<small class="text-danger text-message"></small>
								</div>								
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Can create template ?</label>
									<select class="form-control required isCreateTemplate" required name="isCreateTemplate">
										<option value="">Choose One</option>
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
									<small class="text-danger text-message"></small>
								</div>								
							</div>

						</div><br>
						<div class="form-group">
							<button class="btn btn-primary btn-submit" type="button">Save changes</button>
							<a class="btn btn-default" href="<?=site_url('global-informations/message-blast')?>" >Cancel</a>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<i class="fa fa-bars"></i> List of Access Role
					</h4>
				</div>
				<div class="panel-body">
					<div id="fetch-data-tables">
						<table class="table table-bordered" id="table-list-data">
							<thead>
								<tr>
									<th width="2%">No</th>
									<th>Division/Position (Postion Main)</th>
									<th>Write</th>
									<th>Delete</th>
									<th>View All</th>
									<th>Create Template</th>
									<th width="10%"></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="6">No data available in table</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function fetchingAccessRole() {
        var filtering = $("#form-filter").serialize();
        var token = jwt_encode({Filter : filtering},'UAP)(*');

        var dataTable = $('#fetch-data-tables #table-list-data').DataTable( {
            "destroy": true,
            "retrieve":true,
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "responsive": true,
            "language": {
                "searchPlaceholder": "Division/Position"
            },
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "ajax":{
                url : base_url_js+'global-informations/message-blast/fetchingRoles', // json datasource
                ordering : false,
                data : {token:token},
                type: "post",  // method  , by default get
                error: function(jqXHR){  // error handling
                    //loading_modal_hide();
                    $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        '<h4 class="modal-title">Error Fetch Student Data</h4>');
                    $('#GlobalModal .modal-body').html(jqXHR.responseText);
                    $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
                    $('#GlobalModal').modal({
                        'show' : true,
                        'backdrop' : 'static'
                    });
                }
            },
            "initComplete": function(settings, json) {
                loading_modal_hide();
            },
            "columns": [
	          	{
	                "data": null,
	                "render": function (data, type, row, meta) {
				        return meta.row + meta.settings._iDisplayStart + 1;
				    }
	            },
            	{
	                "data": "PositionMain",
	                "render": function(data, type, row){
	            		return "<span>"+row.Division+" / "+row.Position+"</span>";
	            	}
	            },
	            { 
	            	"data": "isWrite",
	            	"render": function(data, type, row){
	            		return "<span>"+((data==1) ? "Yes":"No")+"</span>";
	            	}
	          	},
	          	{ 
	            	"data": "isDelete",
	            	"render": function(data, type, row){
	            		return "<span>"+((data==1) ? "Yes":"No")+"</span>";
	            	}
	          	},
	          	{ 
	            	"data": "isView",
	            	"render": function(data, type, row){
	            		return "<span>"+((data==1) ? "Yes":"No")+"</span>";
	            	}
	          	},
	          	{ 
	            	"data": "isCreateTemplate",
	            	"render": function(data, type, row){
	            		return "<span>"+((data==1) ? "Yes":"No")+"</span>";
	            	}
	          	},

	            {
	                "data": "ID",
	                "render": function (data, type, row) {
	                	return '<div class="btn-group"><button type="button" class="btn btn-sm btn-warning btn-update" data-id="'+data+'"><i class="fa fa-edit"></i></button>'+
	                		   '<button type="button" class="btn btn-sm btn-danger btn-remove" data-id="'+data+'"><i class="fa fa-trash"></i></button></div>';
	                }
	            }
	        ],
	        "order": [[ 1, 'asc' ]]
        });
		
		dataTable.on( 'order.dt search.dt', function () {
	        dataTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	            cell.innerHTML = i+1;
	            console.log(i);
	        } );
	    } ).draw();
    }
	$(document).ready(function(){
		fetchingAccessRole();
		$formPost = $("#form-access-role");
		$formPost.on("change","select[name=division]",function(){
    		var value = $(this).val();
    		if($.trim(value) > 0){
    			$formPost.find("select[name=position]").prop("disabled",false);
    		}
    	});
    	$formPost.on("change","select[name=position]",function(){
    		var division = $formPost.find("select[name=division]").val();
    		if($.trim(division).length == 0){
    			alert("Please fill up field Division");
    			$(this).val("");
    			$(this).prop("disabled",true);
    		}
    	});

    	$(".btn-submit").click(function(){
			var error = false;
			var itsme = $(this);
			var itsform = itsme.parent().parent();
		 	itsform.find(".required").each(function(){
			  	var value = $(this).val();
			  	if($.trim(value) == ''){
			  		$(this).addClass("error");
			  		$(this).parent().find(".text-message").text("Please fill this field");
			  		error = false;
			  		console.log("error:"+$(this).attr('name'));
			  	}else{
			  		error = true;
			  		$(this).removeClass("error");
			  		$(this).parent().find(".text-message").text("");
			  	}
		  	});
		 	
		 	var totalError = itsform.find(".error").length;

		  	if(error && totalError == 0){
		  		loading_modal_show();
				$.ajax({
				    type : 'POST',
				    url : itsform.attr("action"),
				    data : itsform.serialize(),
				    dataType : 'json',
				    beforeSend :function(){
				    	loading_modal_show();
				    },error : function(jqXHR){
		            	loading_modal_hide();
	                    $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
	                        '<h4 class="modal-title">Error Fetch Student Data</h4>');
	                    $('#GlobalModal .modal-body').html(jqXHR.responseText);
	                    $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
	                    $('#GlobalModal').modal({
	                        'show' : true,
	                        'backdrop' : 'static'
	                    });
				    },success : function(response){
		            	loading_modal_hide();	
		            	if(jQuery.isEmptyObject(response)){
				    		alert("Failed submited. Try again.");
				    	}else{
				    		$formPost.find(".form-control").val("");
				    		$formPost.find(".position").prop("checked",false);
				    		toastr.info(response.message,'Info!');
				    		$('#fetch-data-tables .table').DataTable().destroy();
    						fetchingAccessRole();
				    	}				
				    }
				});
		  	}else{
		  		alert("Please fill out the field");		  		
		  	}
		});

		$("#table-list-data tbody").on("click",".btn-update",function(){
			var ID = $(this).data("id");
			var data = {
              ID : ID,
          	};
          	var token = jwt_encode(data,'UAP)(*');
			$.ajax({
			    type : 'POST',
			    url : base_url_js+"global-informations/message-blast/detailRoles",
			    data : {token:token},
			    dataType : 'json',
			    beforeSend :function(){
			    	loading_modal_show();
			    },error : function(jqXHR){
	            	loading_modal_hide();
                    $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        '<h4 class="modal-title">Error Fetch Student Data</h4>');
                    $('#GlobalModal .modal-body').html(jqXHR.responseText);
                    $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
                    $('#GlobalModal').modal({
                        'show' : true,
                        'backdrop' : 'static'
                    });
			    },success : function(response){
	            	loading_modal_hide();
	            	$formPost.find(".position").prop("checked",false);
					if(jQuery.isEmptyObject(response)){
			    		alert("Data not founded. Try again.");
			    	}else{
	            		$.each(response,function(k,v){
			    			if(k == "position"){
		    					$formPost.find(".position").addClass("check-ones");
		    					$formPost.find(".position-"+v).prop("checked",true);
			    			}else{
			    				$formPost.find("."+k).val(v).prop("disabled",false);
			    			}
			    		});
			    	}
			    }
			});
		});

		$formPost.on("change",".check-ones",function(){
			$('input.position').not(this).prop('checked', false);
		});


		$("#table-list-data tbody").on("click",".btn-remove",function(){
			var ID = $(this).data("id");
			if(confirm("Are you sure wants to remove this ?")){
				var data = {
	              ID : ID,
	          	};
	          	var token = jwt_encode(data,'UAP)(*');
				$.ajax({
				    type : 'POST',
				    url : base_url_js+"global-informations/message-blast/deleteRoles",
				    data : {token:token},
				    dataType : 'json',
				    beforeSend :function(){
				    	loading_modal_show();
				    },error : function(jqXHR){
		            	loading_modal_hide();
	                    $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
	                        '<h4 class="modal-title">Error Fetch Student Data</h4>');
	                    $('#GlobalModal .modal-body').html(jqXHR.responseText);
	                    $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
	                    $('#GlobalModal').modal({
	                        'show' : true,
	                        'backdrop' : 'static'
	                    });
				    },success : function(response){
		            	loading_modal_hide();
						if(jQuery.isEmptyObject(response)){
				    		alert("Data not founded. Try again.");
				    	}else{
		            		toastr.info(response.message,'Info!');
		            		$('#fetch-data-tables .table').DataTable().destroy();
							fetchingAccessRole();
				    	}
				    }
				});
			}
		});


	});
</script>