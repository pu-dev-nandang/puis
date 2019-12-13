<style type="text/css">
	.tabbable{border-bottom: 1px solid #ddd}
	.tabbable-custom > .nav-tabs > li:hover{border-top: 3px solid #4d7496;}
	#table-list-data thead tr th {
	    background: #20525a;
	    color: #ffffff;
	    text-align: center;
	}
	#table-list-data .detail-user{cursor: pointer;}
	#table-list-data .detail-user > img.std-img{width: 45px;float: left;margin-right: 10px;}
	#table-list-data .detail-user > p{margin:0px;font-weight: bold;color: #4d7496}
	#table-list-data .detail-user > p.name{text-transform: uppercase;}
	#table-list-data .detail-user > p.email{font-weight: 100;color: #000}
</style>
<div id="student-list">
	<div class="row">
		<div class="col-sm-12">
			<div id="filter-form">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"><i class="fa fa-filter"></i> Form Filter</h4>
					</div>
					<div class="panel-body">
						<form id="form-filter" method="post" autocomplete="off" class="form-inline">
							<div class="form-group">
								<label>Student</label>								
								<input type="text" class="form-control" name="student" placeholder="NIM or Name or Email">								
							</div>
							<div class="form-group">
								<label>Class of</label>								
								<select class="form-control" name="class_of">
									<option value="">-Choose year-</option>
									<?php for ($i=2014; $i <= date('Y'); $i++) { 
									echo '<option value="'.$i.'">'.$i.'</option>';
									} ?>
								</select>								
							</div>
							<div class="form-group">
								<label>Study Program</label>								
								<select class="form-control" name="study_program">
									<option value="">-Choose one-</option>
									<?php foreach ($studyprogram as $s) { 
									echo '<option value="'.$s->ID.'">'.$s->Name.'</option>';
									} ?>
								</select>								
							</div>
							<div class="form-group">
								<label>Status</label>								
								<select class="form-control" name="status">
									<option value="">-Choose one-</option>
									<?php foreach ($statusstd as $t) { 
									echo '<option value="'.$t->CodeStatus.'">'.$t->Description.'</option>';
									} ?>
								</select>								
							</div>
							<div class="form-group" style="padding-top:22px">
								<button class="btn btn-primary btn-filter" type="button"><i class="fa fa-search"></i> Search</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div id="fetch-data-tables">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5 class="panel-title"><i class="fa fa-bars"></i> List of students</h5>
					</div>
					<div class="panel-body">
						<div class="table-list">
							<table class="table table-bordered table-striped" id="table-list-data">
								<thead>
									<tr>
										<th width="2%">No</th>
										<th width="30%">Student</th>
										<th width="8%">Class of</th>
										<th>Study Program</th>
										<th width="20%">Status</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<script type="text/javascript">
	function fetchingData() {
    	var data = getFormData($("#form-filter"));    	
        var token = jwt_encode(data,'UAP)(*');
    	var dataTable = $('#fetch-data-tables .table').DataTable( {
				            destroy: true,
							retrieve:true,
				            "processing": true,
				            "serverSide": true,
				            "iDisplayLength" : 10,
				            "ordering" : false,
				            "language": {
				                "searchPlaceholder": "NIM, Name, Programme Study"
				            },
				            "ajax":{
				                url : base_url_js+'global-informations/studentsFetch', // json datasource
				                ordering : false,
				                data : {token:token},
				                type: "post",  // method  , by default get
				                error: function(jqXHR){  // error handling
				                    $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
						                '<h4 class="modal-title">Error Fetch Student Data</h4>');
						            $('#GlobalModal .modal-body').html(jqXHR.responseText);
						            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
						            $('#GlobalModal').modal({
						                'show' : true,
						                'backdrop' : 'static'
						            });
				                }
				            }
				        });
    }

    function getFormData($form){
	    var unindexed_array = $form.serializeArray();
	    var indexed_array = {};

	    $.map(unindexed_array, function(n, i){
	        indexed_array[n['name']] = n['value'];
	    });

	    return indexed_array;
	}

    $(document).ready(function(){
    	fetchingData();
    	$("#form-filter .btn-filter").click(function(){
    		$('#fetch-data-tables .table').DataTable().destroy();
    		fetchingData();
    	});

    	$("#student-list").on("click","#table-list-data .detail-user",function(){
    		var itsme = $(this);
    		var NPM = itsme.data("user");
			var data = {
              NPM : NPM,
          	};
          	var token = jwt_encode(data,'UAP)(*');
          	$.ajax({
			    type : 'POST',
			    url : base_url_js+"global-informations/studentsDetail",
			    data : {token:token},
			    dataType : 'html',
			    beforeSend :function(){$('#ajax-loader').show();},
	            error : function(jqXHR){
	            	$('#ajax-loader').hide(); 
	            	$("body #modalGlobal .modal-body").html(jqXHR.responseText);
		      	  	$("body #modalGlobal").modal("show");
			    },success : function(response){
			    	$("#student-list").html(response);
			    }
			});
    	});
    });
</script>