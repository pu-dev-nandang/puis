<div id="log-content">
	<div class="row" style="margin-bottom:15px">
		<div class="col-sm-4">
			<div class="btn-group">
				<button class="btn btn-warning btn-sm" type="button" onclick="window.history.go(-1); return false;"><i class="fa fa-angle-double-left"></i> Going back</button>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<div class="panel panel-default" id="filter-form">
				<div class="panel-heading">
					<h4 class="panel-title"><i class="fa fa-filter"></i> Filter</h4>
				</div>
				<div class="panel-body">
					<form id="form-filter" action="<?=base_url()?>" method="post" autocomplete="off">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Type Content</label>
									<select class="form-control" name="TypeContent">
										<option value="">-choose one-</option>
										<option <?=($typecontent == 'user_qna') ? 'selected':''?> value="user_qna">Help</option>
										<option <?=($typecontent == 'knowledge_base') ? 'selected':''?> value="knowledge_base">Knowledge Base</option>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>NIP</label>
									<input type="text" name="NIP" class="form-control">
								</div>		
							</div>					
						</div>
						<div class="row">
							<div class="col-sm-12 text-left">
								<button class="btn btn-primary btn-sm btn-filter" type="button"><i class="fa fa-search"></i> Search</button>
							</div>
						</div>
					</form>			
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="panel panel-default" id="result-table">
				<div class="panel-heading">
					<h4 class="panel-title">
						<i class="fa fa-bars"></i> List content
					</h4>
				</div>
				<div class="panel-body">
					<div class="fetch-data table-responsive">
						<table class="table table-bordered" id="table-list-data">
							<thead>
								<tr>
									<th width="5%">No</th>
									<th>Employee</th>
									<th>Type Content</th>
									<th>Total</th>
									<th>Detail</th>
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
	function fetchLogActivity() {
		var filtering = $("#form-filter").serialize();		

        var token = jwt_encode({Filter : filtering},'UAP)(*');
        var dataTable = $('#table-list-data').DataTable( {
            "destroy": true,
            "ordering" : false,
            "retrieve":true,
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 5,
            "responsive": true,
            "ajax":{
                url : base_url_js+'admin-fetch-log', // json datasource
                ordering : false,
                data : {token:token},
                type: "post",  // method  , by default get
                error: function(jqXHR){  // error handling
                    loading_modal_hide();
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
            		"data":"NIP",
            		render: function (data, type, row, meta) {
				        return meta.row + meta.settings._iDisplayStart + 1;
				    }
            	},
            	{
            		"data":"NIP",
            		"render": function (data, type, row, meta) {
            			var label = data+"/"+row.Name;
            			return label;
            		}
            	},
            	{
            		"data":"TypeContent",
            		"render": function (data, type, row, meta) {
            			var label = data;
            			var name = 'undefined';
            			if(data == 'user_qna'){
            				name = 'help';
            			}else if(data == 'knowledge_base'){
            				name = 'knowledge base';
            			}
            			label = '<span class="capitalize">'+name+'</span>'
            			return label;
            		}
            	},
            	{
            		"data":"totalRead", 
            		"render": function (data, type, row, meta) {
            			var label = data+" articles has been read";
            			return label;
            		}           		
            	},
            	{
            		"data":"NIP", 
            		"render": function (data, type, row, meta) {
            			var label = '<button class="btn btn-default btn-detail btn-xs" data-nip="'+row.NIP+'" data-type="'+row.TypeContent+'" type="button"><i class="fa fa-folder-open"></i></button>';
            			return label;
            		}           		
            	},
        	]
        });
	}


	$(document).ready(function(){
		fetchLogActivity();
		$("#form-filter .btn-filter").click(function(){
	    	$('body #table-list-data').DataTable().destroy();
	        fetchLogActivity();
	    });
	    $("#table-list-data").on("click",".btn-detail",function(){
	    	var itsme = $(this);
	    	var NIP = itsme.data("nip");
	    	var TYPE = itsme.data("type");

	    	var dataPost = {
		        NIP : NIP,
		        TypeContent : TYPE
	      	}
		        
	      	var token = jwt_encode(dataPost,'UAP)(*');

	      	$.ajax({
		        type : 'POST',
		        url : base_url_js+"admin-log-detail",
		        data : {token:token},
		        dataType : 'html',
		        beforeSend :function(){itsme.html('<i class="fa fa-spinner fa-spin"></i>');},
		        error : function(jqXHR){
					$('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
		                '<h4 class="modal-title">Error !</h4>');
					$("body #GlobalModal .modal-body").html(jqXHR.responseText);
					$('body #GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
					$("body #GlobalModal").modal("show");
		        },success : function(response){
		        	$("#GlobalModal .modal-dialog").css({"width":"80%"});
		        	itsme.html('<i class="fa fa-folder-open"></i>');
		          	$('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
		                '<h4 class="modal-title">Historical log</h4>');
		          	$('body #GlobalModal .modal-footer').hide();
	            	$('#GlobalModal .modal-body').html(response);
		        }
		    });


			
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });	    	
	    });
	});
</script>
