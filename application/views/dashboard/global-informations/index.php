<style type="text/css">
	#global-information-dash #click-me{cursor: pointer;}
	#global-information-dash .card .icon{font-size: 4em;text-align: center;}
	#global-information-dash .card .title{font-size: 2em;font-weight: bold;margin-bottom: 0px}
</style>
<div id="global-information-dash">
	<div class="container">
		<div class="row">

		<?php if($this->session->userdata('PositionMain')['IDDivision'] == 12 || 
				 $this->session->userdata('PositionMain')['IDDivision'] == 2  || 
				 $this->session->userdata('PositionMain')['IDDivision'] == 6 ){ ?>
			<div class="col-sm-4">
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
		<?php } ?>
			<div class="col-sm-4">
				<div class="panel panel-default" id="click-me" data-href="global-informations/message-blast">
					<div class="panel-body">
						<div class="row card">
							<div class="col-sm-3">
								<p class="icon"><i class="fa fa-envelope"></i></p>
							</div>
							<div class="col-sm-9 col-md-9">
								<p class="title">Message Blast</p>
								<p class="desc">Service that allows you to easily send messages to multiple email utilizing an automated messaging system.</p>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="panel panel-default" id="click-me" data-href="global-informations/package-order">
					<div class="panel-body">
						<div class="row card">
							<div class="col-sm-3">
								<p class="icon"><i class="fa fa-archive"></i></p>
							</div>
							<div class="col-sm-9 col-md-9">
								<p class="title">Package Order</p>
								<p class="desc">Service that allows you to easily monitoring your package order from GA Dept.</p>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="panel panel-default" id="click-me" data-href="global-informations/extention-phone">
					<div class="panel-body">
						<div class="row card">
							<div class="col-sm-3">
								<p class="icon"><i class="fa fa-phone-square"></i></p>
							</div>
							<div class="col-sm-9 col-md-9">

								<p class="title">Phone Extention</p>
								<p class="desc">Service that allows you to easily to find employer extenstion phone.</p>

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