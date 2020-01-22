<div id="master-company">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading" style="padding-bottom:15px">
					<div class="pull-right">
						<button class="btn btn-xs btn-primary btn-add-record" type="button"><i class="fa fa-plus"></i> Add New Record</button>
					</div>
					<h4 class="panel-title">
						<i class="fa fa-bars"></i> <span>Data of Insurance Company</span>
					</h4>
				</div>
				<div class="panel-body">
					<div id="fetch-company"><center><i class="fa fa-spinner fa-spin"></i> processing..</center></div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		function fetchData(){
			$.ajax({
			    type : 'POST',
			    url : base_url_js+"human-resources/master_insurance_company/fetch",
			    data: "Category='insurance'",
			    dataType : 'html',
			    beforeSend :function(){loading_modal_show()},
	            error : function(jqXHR){
	            	loading_modal_hide();
	            	$("body #modalGlobal .modal-body").html(jqXHR.responseText);
		      	  	$("body #modalGlobal").modal("show");
			    },success : function(response){
	            	loading_modal_hide();
			    	$("body #fetch-company").html(response);
			    }
			});
		}
		$(document).ready(function(){
			fetchData();
			$(".btn-add-record").click(function(){
				$.ajax({
				    type : 'POST',
				    url : base_url_js+"human-resources/master_insurance_company/form",
				    dataType : 'html',
				    beforeSend :function(){loading_modal_show()},
		            error : function(jqXHR){
		            	loading_modal_hide();
		            	$("body #modalGlobal .modal-body").html(jqXHR.responseText);
			      	  	$("body #modalGlobal").modal("show");
				    },success : function(response){
		            	loading_modal_hide();
				    	$("#master-company").html(response);
				    }
				});
			});
		});
	</script>
</div>