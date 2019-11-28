<?php $this->load->view('dashboard/ticketing/LoadCssTicketToday') ?>
<style type="text/css">
	.row {
	    margin-right: 0px;
	    margin-left: 0px;
	}
</style>
<div class="row" style="margin-top: 10px;">
	<div class="col-md-4">
		<div class="well">
			<div style="text-align: center;">
				<img data-src="<?php echo base_url('uploads/employees/'.$DataTicket[0]['PhotoRequested']); ?>" style="margin-top: -3px;" class="img-circle img-fitter" width="100">
				<h4><b>Ticket Data</b></h4>
			</div>
			<table class="table" id="tableDetailTicket">
				<tr>
					<td style="width: 25%;">NoTicket</td>
					<td>:</td>
					<td><?php echo $DataTicket[0]['NoTicket'] ?></td>
				</tr>
				<tr>
					<td style="width: 25%;">Title</td>
					<td>:</td>
					<td><?php echo $DataTicket[0]['Title'] ?></td>
				</tr>
				<tr>
					<td style="width: 25%;">Category</td>
					<td>:</td>
					<td><?php echo $DataTicket[0]['NameDepartmentDestination'].' - '.$DataTicket[0]['CategoryDescriptions'] ?></td>
				</tr>
				<tr>
					<td style="width: 25%;">Message</td>
					<td>:</td>
					<td><?php echo $DataTicket[0]['Message'] ?></td>
				</tr>
				<tr>
					<td style="width: 25%;">Requested by</td>
					<td>:</td>
					<td><?php echo $DataTicket[0]['NameRequested'] ?></td>
				</tr>
				<tr>
					<td style="width: 25%;">Requested on</td>
					<td>:</td>
					<td><?php echo $DataTicket[0]['RequestedAt'] ?></td>
				</tr>
			</table>
			<br/>
			<div id ="ShowProgressList">
				
			</div>
		</div>
	</div>
	<div class="col-md-4" id = "PageAssignTo">

	</div>
	<div class="col-md-4" id = "PageTransferTo">

	</div>
</div>
<script type="text/javascript">
	var DataTicket = <?php echo json_encode($DataTicket) ?>;
	var DataAll = <?php echo json_encode($DataAll) ?>;
	var DataReceivedSelected = <?php echo json_encode($DataReceivedSelected) ?>;
	console.log(DataReceivedSelected);
	var App_set_action_progress = {
		Loaded : function(){
			var DataGet = DataAll[0];
			var htmlGetProgressList =  AppModalDetailTicket.tracking_list_html(DataGet);
			$('#ShowProgressList').html(htmlGetProgressList);
		}
	};
	$(document).ready(function(){
		App_set_action_progress.Loaded();
	})
</script>