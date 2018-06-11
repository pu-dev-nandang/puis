<style type="text/css">
	.btn-reject {
		background-color: #e20f0f;
	}
	.btn-approve{
		background-color: #1ace37;
	}

	hr.style-eight {
		height: 10px;
		border: 1;
		box-shadow: inset 0 9px 9px -3px rgba(11, 99, 184, 0.8);
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		-ms-border-radius: 5px;
		-o-border-radius: 5px;
		border-radius: 5px;
	}
</style>
<?php $no = $start + 1; ?>
<?php if (count($datadb) > 0 ): ?>
	<div id = "tblData" class="table-responsive">
		<table class="table table-striped table-bordered table-hover table-checkable tableData">
			<!-- <caption><strong>List Dokumen</strong></caption> -->
			<thead>
				<tr>
					<th>No <input type="checkbox" class="uniform" value="nothing" id ="dataResultCheckAll"></th>
					<th>Va Number</th>
					<th>BilingID</th>
					<th>Nama</th>
					<th>Email</th>
					<th>Tanggal Tidak Aktif</th>
				</tr>
			</thead>
			<tbody> 
				<?php for ($i = 0; $i < count($datadb); $i++): ?>
							 <tr>
							 	<td><?php echo $no++ ?> <input type="checkbox" class="uniform" value="<?php echo $datadb[$i]['BilingID'] ?>" id ="dataResultCheckAll<?php echo $i ?>"></td>
							 	<td><?php echo $datadb[$i]['VA_number'] ?></td>
							 	<td><?php echo $datadb[$i]['BilingID'] ?></td>
							 	<td><?php echo $datadb[$i]['Name'] ?></td>
							 	<td><?php echo $datadb[$i]['Email'] ?></td>
							 	<td><?php echo $datadb[$i]['DeletedAT'] ?></td>
							 </tr>
				<?php endfor; ?>
			</tbody>
		</table>
	</div>			
	<!-- <div class="col-xs-12" align = "right"> -->
	   <!-- <button class="btn btn-inverse btn-notification btn-approve" id="btn-approve">Confirm</button> -->
	<!-- </div> -->
	<!-- <br> -->
	<!-- <hr class="style-eight" /> -->
	<script type="text/javascript">
		$(document).on('click','#dataResultCheckAll', function () {
			$('input.uniform').not(this).prop('checked', this.checked);
		});
	</script>

<?php else: ?>
<div align = 'center'>No Result Data...</div>		
<?php endif ?>


