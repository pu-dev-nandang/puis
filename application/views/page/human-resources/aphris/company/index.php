<?php $message = $this->session->flashdata('message');
	if(!empty($message)){ ?>
	<script type="text/javascript">
	$(document).ready(function(){
		toastr.info("<?= $this->session->flashdata('message');?>",'Info!');
	});
	</script>
<?php } ?>

<div id="master-company">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading" style="padding-bottom:15px">
					<div class="pull-right">
						<button class="btn btn-xs btn-primary btn-add-record" type="button"><i class="fa fa-plus"></i> Add New Record</button>
					</div>
					<h4 class="panel-title">
						<i class="fa fa-bars"></i> <span>List of master company</span>
					</h4>
				</div>
				<div class="panel-body">
					<div id="fetch-data-tables">
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsives">
									<table class="table table-bordered" id="table-list-data">
										<thead>
											<tr>
												<th width="2%">No</th>
												<th>Company Name</th>
												<th>Address</th>
												<th>Category Company</th>
												<th width="5%"></th>
											</tr>
										</thead>
										<tbody>
											<tr><td colspan="6">Empty result</td></tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		
		function fetchingDataInsurace() {
	        //loading_modal_show();
	        var data = {
              IndustryID : 33,
          	};
          	var token = jwt_encode(data,'UAP)(*');

	        var dataTable = $('#fetch-data-tables #table-list-data').DataTable( {
	            "destroy": true,
	            "retrieve":true,
	            "processing": true,
	            "serverSide": true,
	            "iDisplayLength" : 10,
	            "ordering" : false,
	            "responsive": true,
	            "language": {
	                "searchPlaceholder": "NIM, Name, Study Program"
	            },
	            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
	            "ajax":{
	                url : base_url_js+'human-resources/master-aphris/master_company/fetch', // json datasource
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
	                //loading_modal_hide();
	            },
	            "columns": [
	            	{
		                "data": "no",
		            },{
		                "data": "Name",
		            },
		            { "data": "Address",
		              "render": function (data, type, row) {
	                    var trimAdditionalInfo = $.trim("AdditionalInfo");
	                    return "<p>"+data+"</p>";
	                  }
		          	},
		            { "data": "IndustryTypeID",
		              "render": function(data,type,row){
		              	if(!jQuery.isEmptyObject(row.Industry)){
		              		return row.Industry.name;
		              	}else {
		              		return '';
		              	}
		              }
		            },
		            { "data": "ID",
		              "render": function (data, type, row) {
	                    return '<div class="btn-group"><button class="btn btn-xs btn-warning btn-edit" type="button" title="Edit" data-id="'+data+'"><i class="fa fa-edit"></i></button></div>';
	                  }	
	            	},
		            
		        ],
	        });
	    }

		$(document).ready(function(){
			fetchingDataInsurace();
			$(".btn-add-record").click(function(){
				$.ajax({
				    type : 'POST',
				    url : base_url_js+"human-resources/master-aphris/master_company/form",
				    dataType : 'html',
				    beforeSend :function(){loading_modal_show()},
		            error : function(jqXHR){
		            	loading_modal_hide();
		            	$("body #GlobalModal .modal-body").html(jqXHR.responseText);
			      	  	$("body #GlobalModal").modal("show");
				    },success : function(response){
		            	loading_modal_hide();
				    	$("#master-company").html(response);
				    }
				});
			});

			$("#master-company #table-list-data").on("click","tbody .btn-edit",function(){
				var ID = $(this).data("id");
				var data = {
	              ID : ID,
	          	};
	          	var token = jwt_encode(data,'UAP)(*');
				$.ajax({
				    type : 'POST',
				    url : base_url_js+"human-resources/master-aphris/master_company/form",
				    data : {token:token},
				    dataType : 'html',
				    beforeSend :function(){loading_modal_show()},
		            error : function(jqXHR){
		            	loading_modal_hide();
		            	$("body #GlobalModal .modal-body").html(jqXHR.responseText);
			      	  	$("body #GlobalModal").modal("show");
				    },success : function(response){
		            	loading_modal_hide();
				    	$("#master-company").html(response);
				    }
				});
			});
		});
	</script>
</div>