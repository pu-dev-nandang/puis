<div class="table-responsive">
	<table class="table table-bordered tableData" id ="datatablesServer">
		<thead>
			<tr>
				<th width = "3%">No</th>
				<th>Department</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php for ($i = 0; $i < count($GetDeparment); $i++): ?>
				<tr>
					<td><?php echo $GetDeparment[$i]['No'] ?></td>
					<td><?php echo $GetDeparment[$i]['Name2'] ?></td>
					<td><?php echo $GetDeparment[$i]['st'] ?></td>
					<td align="center"><?php echo $GetDeparment[$i]['stcode'] ?></td>
				</tr>
			<?php endfor; ?>
		</tbody>
	</table>
</div>