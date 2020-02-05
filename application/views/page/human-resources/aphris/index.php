<?php $message = $this->session->flashdata('message');
	if(!empty($message)){ ?>
	<script type="text/javascript">
	$(document).ready(function(){
		toastr.info("<?= $this->session->flashdata('message');?>",'Info!');
	});
	</script>
<?php } ?>

<div id="master-aphris">
	<div class="row">
		<div class="col-sm-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<i class="fa fa-edit"></i> Form <?=str_replace("_", " ", $title)?>
					</h4>
				</div>
				<div class="panel-body">
					<form id="form-master" action="<?=site_url('human-resources/master-aphris/saveMaster')?>" method="post" autocomplete="off">
						<input type="hidden" name="DBNAME" class="form-control" value="<?=$title?>">
						<input type="hidden" name="ID" class="form-control ID">
						<div class="form-group">
							<label>Name</label>
							<input type="text" name="name" class="form-control name required" required>
							<small class="text-danger text-message"></small>
						</div>
						<div class="form-group">
							<label>Description</label>
							<textarea class="form-control description" name="description"></textarea>
						</div>

						<div class="form-group">
							<label>Active</label>
							<select class="form-control required IsActive" required name="IsActive">
								<option value="">Choose One</option>
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>
							<small class="text-danger text-message"></small>
						</div>

						<div class="form-group text-right">
							<button class="btn btn-success btn-submit" type="button"><i class="fa fa-paper-plane-o"></i> Save changes</button>
						</div>

					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<i class="fa fa-bars"></i> <span>List of <?=str_replace("_", " ", $title)?></span>
					</h4>
				</div>
				<div class="panel-body">
					<div class="list">
						<table class="table table-bordered" id="table-list-data">
							<thead>
								<tr>
									<th width="2%">No</th>
									<th>Name</th>
									<th>Status</th>
									<th width="5%">Edit</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="5">No data available in table</td>
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
	function fetchingData() {
        //loading_modal_show();
        var data = {
          DBNAME : "<?=$title?>",
      	};
      	var token = jwt_encode(data,'UAP)(*');
        var dataTable = $('#master-aphris #table-list-data').DataTable( {
            "destroy": true,
            "retrieve":true,
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "responsive": true,
            "language": {
                "searchPlaceholder": "Name"
            },
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "ajax":{
                url : base_url_js+'human-resources/master-aphris/fetchMaster', // json datasource
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
	                "data": null,
	                "render": function (data, type, row, meta) {
				        return meta.row + meta.settings._iDisplayStart + 1;
				    }
	            },
	            {
	                "data": "name",
	                "render": function (data, type, row) {
	            		return "<p>"+data+"</p>"+((!jQuery.isEmptyObject(row.description)) ? "<p>"+row.description+"</p>" : '');
	            	}
	            },
	            { 
	            	"data": "IsActive",
	            	"render": function (data, type, row) {
	            		return "<label>"+((data==1)?"Active":"Non Active")+"</label>";
	            	}
	          	},
	          	{ 
	          		"data": "ID",
	          		"render": function (data, type, row) {
				        return '<button type="button" class="btn btn-warning btn-edit" data-id="'+data+'"><i class="fa fa-edit"></i></button>';
				    }
	          	},
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
		fetchingData();

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
			  	}else{
			  		error = true;
			  		$(this).removeClass("error");
			  		$(this).parent().find(".text-message").text("");
			  	}
		  	});
		 	
		 	var totalError = itsform.find(".error").length;
		  	if(error && totalError == 0 ){
		  		loading_modal_show();
		  		$("#form-master")[0].submit();
		  	}else{
		  		alert("Please fill out the field.");
		  	}
		  	
		});

		$("#table-list-data").on("click","tbody .btn-edit",function(){
			var itsme = $(this);
			var ID = itsme.data("id");

			var data = {
              ID : ID,
          	};
          	var token = jwt_encode(data,'UAP)(*');
          	$.ajax({
			    type : 'POST',
			    url : base_url_js+"human-resources/master-aphris/detailMaster",
			    data : {token:token},
			    dataType : 'json',
			    beforeSend :function(){loading_modal_show()},
	            error : function(jqXHR){
	            	loading_modal_hide();
	            	$("body #GlobalModal .modal-body").html(jqXHR.responseText);
		      	  	$("body #GlobalModal").modal("show");
			    },success : function(response){
	            	loading_modal_hide();
			    	if(jQuery.isEmptyObject(response)){
			    		alert("Data not founded. Try again.");
			    	}else{
			    		$.each(response,function(k,v){
			    			$("#form-master").find("."+k).val(v);
			    		});
			    	}
			    }
			});
		});

	});
</script>