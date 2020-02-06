<?php $message = $this->session->flashdata('message');
	if(!empty($message)){ ?>
	<script type="text/javascript">
	$(document).ready(function(){
		toastr.info("<?= $this->session->flashdata('message');?>",'Info!');
	});
	</script>
<?php } ?>
<style type="text/css">#container.fixed-header{margin-top: 0px}</style>
<div id="subject-type">
	<div class="row">
		<div class="col-sm-12 text-center">
			<button type="button" class="btn btn-danger" onclick="window.open('', '_self', ''); window.close();"><i class="fa fa-times"></i> Close windows</button>
		</div>
		<div class="col-sm-12">
			<div class="panel panel-default"  style="margin-top:10px">
				<div class="panel-heading">
					<div class="pull-right">
						<button class="btn btn-xs btn-success btn-add-new" type="button"><i class="fa fa-plus"></i> Add New Record</button>
					</div>
					<h4 class="panel-title">
						<i class="fa fa-bars"></i> List of template subject
					</h4>
				</div>
				<div class="panel-body">					
					<div id="fetch-data-tables">
						<table class="table table-bordered" id="table-list-data">
							<thead>
								<tr>
									<th width="2%">Code</th>
									<th>Subject</th>
									<th>Status</th>
									<th width="5%"></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="6">Empty data</td>
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
	function fetchingSubject() {
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
                "searchPlaceholder": "Subject name"
            },
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "ajax":{
                url : base_url_js+'global-informations/subject-type/fetching', // json datasource
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
	                "data": "ID",
	                "render": function (data, type, row) {
	                	return "<label>ST00"+data+"</label>";
	                }
	            },
            	{
	                "data": "subject",
	            },
	            { 
	            	"data": "IsActive",
	            	"render": function(data, type, row){
	            		return "<label>"+((data==1)?"Active":"Non Active")+"</label>";
	            	}
	          	},
	            {
	                "data": "ID",
	                "render": function (data, type, row) {
	                	return '<button type="button" class="btn btn-sm btn-warning btn-update" data-id="'+data+'"><i class="fa fa-edit"></i></button>';
	                }
	            },

	        ],
        });
    }
	$(document).ready(function(){
		loading_modal_show();
		$("#container.fixed-header").addClass("sidebar-closed");
		$("header").remove();
		$("#content .crumbs").remove();
		fetchingSubject();
		$(".btn-add-new").click(function(){
			$.ajax({
			    type : 'POST',
			    url : base_url_js+"global-informations/subject-type/form",
			    dataType : 'html',
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
					$("body #global-informations #subject-type").html(response);
			    }
			});
		});

		$("#form-filter .btn-filter").click(function(){
			$('#fetch-data-tables #table-list-data').DataTable().destroy();
			fetchingSubject();
		});

		$("body #subject-type #table-list-data").on("click",".btn-update",function(){
			var ID = $(this).data("id");
			var data = {
              ID : ID,
          	};
          	var token = jwt_encode(data,'UAP)(*');
			$.ajax({
			    type : 'POST',
			    url : base_url_js+"global-informations/subject-type/form",
			    data : {token:token},
			    dataType : 'html',
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
					$("body #global-informations #subject-type").html(response);
			    }
			});
		});
	});
</script>