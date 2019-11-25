<div class="row" style="margin-top: 10px;">
	<div class="col-md-4 col-md-offset-4">
		<div class="well">
			<div style="text-align: center;"><h4><b>Ticket Data</b></h4></div>
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
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
			    <h4 class="panel-title">Assign To</h4>
			</div>
			<div class="panel-body" style="min-height: 100px;">
				<span data-smt="" class="btn btn-xs btn-add-assign_to">
                    <i class="icon-plus"></i> Add
                </span>
                <div id="FormAssignTo"></div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
			    <h4 class="panel-title">Transfer To</h4>
			</div>
			<div class="panel-body" style="min-height: 100px;">
				<span data-smt="" class="btn btn-xs btn-add-transfer_to">
                    <i class="icon-plus"></i> Add
                </span>
                <div id="FormTransferTo"></div>
			</div>
		</div>
	</div>
</div>
<br/>
<div class="pull-right">
	<button class="btn btn-success">Save</button>
</div>

<script type="text/javascript">
	var DataTicket = <?php echo json_encode($DataTicket) ?>;
	var DataCategory = <?php echo json_encode($DataCategory) ?>;
	var AssignTo = {


	};

	$(document).ready(function(){

	})

	
</script>