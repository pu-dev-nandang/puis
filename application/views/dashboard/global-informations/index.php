<style type="text/css">
	#global-information-dash #click-me{cursor: pointer;}
	#global-information-dash .card .icon{font-size: 4em;text-align: center;}
	#global-information-dash .card .title{font-size: 2em;font-weight: bold;margin-bottom: 0px}
</style>
<div id="global-information-dash">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<div class="panel panel-default" id="click-me" data-href="global-informations/students">
					<div class="panel-body">
						<div class="row card">
							<div class="col-sm-3">
								<p class="icon"><i class="fa fa-users"></i></p>
							</div>
							<div class="col-sm-9 col-md-9">
								<p class="title">PU Users</p>
								<p class="desc">Service that allows you to get to know about data information of our Student, Lecturer and Employees.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			

		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#global-information-dash").on("click","#click-me",function(){
			var href = $(this).data("href");
			loading_modal_show();
			$(location).attr("href","<?=site_url()?>"+href);
		});
	});
</script>