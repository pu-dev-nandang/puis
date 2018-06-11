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
<?php if (count($datadb) > 0 ): ?>
	<div id = "tblData" class="table-responsive">
		<table class="table table-striped table-bordered table-hover table-checkable tableData">
			<!-- <caption><strong>List Dokumen</strong></caption> -->
			<thead>
				<tr>
					<th>No</th>
					<th>Sales</th>
					<!-- <th style="width: 100px;">Activated by Candidate</th> -->
					<th>Provinsi</th>
					<th>Wilayah</th>
					<th>Sekolah</th>
					<th>Alamat Sekolah</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php $no = $no + 1; ?> 
				<?php for ($i = 0; $i < count($datadb); $i++): ?>
							 <tr>
							 	<td><?php echo $no++ ?></td>
							 	<td><?php echo $datadb[$i]['Name'].' | '.$datadb[$i]['NIP'] ?></td>
							 	<td><?php echo $datadb[$i]['ProvinceName'] ?></td>
							 	<td><?php echo $datadb[$i]['RegionName'] ?></td>
							 	<td><?php echo $datadb[$i]['SchoolName'] ?></td>
							 	<td><?php echo $datadb[$i]['SchoolAddress'] ?></td>
							 	<td>
							 		<?php if ($datadb[$i]['Active'] == 1): ?>
							 			<i class="fa fa-check-circle" style="color: green;"></i>
							 		<?php else: ?>
							 			<i class="fa fa-minus-circle" style="color: red;"></i>						
							 		<?php endif ?>
							 	</td>
							 	<td>
							 		<div class="btn-group">
							 		  <span data-smt="<?php echo $datadb[$i]['ID'] ?>" class="btn btn-xs btn-edit">
							 		    <i class="fa fa-pencil-square-o"></i> Edit
							 		   </span>
							 		   <span data-smt="<?php echo $datadb[$i]['ID'] ?>" class="btn btn-xs btn-Active" data-active = "<?php echo $datadb[$i]['Active'] ?>">
							 		     <i class="fa fa-hand-o-right"></i> Change Active
							 		    </span>
							 		   <span data-smt="<?php echo $datadb[$i]['ID'] ?>" class="btn btn-xs btn-delete">
							 		     <i class="fa fa-trash"></i> Delete
							 		    </span>
							 		</div>
							 	</td>
							 </tr>
				<?php endfor; ?>
			</tbody>
		</table>
	</div>			
	<script type="text/javascript">
	
	</script>
<?php else: ?>
<div align = 'center'>No Result Data...</div>		
<?php endif ?>


