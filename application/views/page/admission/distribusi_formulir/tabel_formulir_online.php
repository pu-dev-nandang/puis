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
					<th>Tahun</th>
					<th>Formulir Code & Kwitansi</th>
					<th>No Ref</th>
					<th style="width: 100px;">Activated by Candidate</th>
					<th>Nama Candidate</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Sekolah</th>
				</tr>
			</thead>
			<tbody> 
				<?php for ($i = 0; $i < count($datadb); $i++): ?>
				<?php $NoKwitansi = ($datadb[$i]['NoKwitansi'] == null || $datadb[$i]['NoKwitansi'] == ''  ) ? '-' : $datadb[$i]['NoKwitansi'] ;  ?>
						<?php
							if ($NoKwitansi != '-') {
								$MaxKw = 4;
								$L = strlen($NoKwitansi);
								for ($z=0; $z < $MaxKw - $L; $z++) { 
									$NoKwitansi = '0'.$NoKwitansi;
								}

								$NoKwitansi = 'ON-'.$NoKwitansi;
							}	
						?>

						<?php $tokenEdit = $datadb[$i]; $tokenEdit = $this->jwt->encode($tokenEdit,"UAP)(*");?>
							 <tr data-id = "<?php echo $datadb[$i]['ID'] ?>" token = "<?php echo $tokenEdit ?>">
							 	<td><?php echo $datadb[$i]['Years'] ?></td>
							 	<td><?php echo $datadb[$i]['FormulirCode'] ?> & <a href = "javascript:void(0)"  formulir = "<?php echo $datadb[$i]['FormulirCode'] ?>" class = "ShowKwitansi" nokwitansi = "<?php echo $datadb[$i]['NoKwitansi'] ?>" ref = "<?php echo $datadb[$i]['No_Ref'] ?>" NamaLengkap = "<?php echo $datadb[$i]['NameCandidate'] ?>" hp = "<?php echo ($datadb[$i]['Phone'] != '') ? '+'.$datadb[$i]['Phone'] : '' ?>" pembayaran = "Pembelian Formulir Pendaftaran" jumlah="<?php echo $datadb[$i]['PriceFormulir'] ?>" date="<?php echo $datadb[$i]['VerificationAT'] ?>" jenis="Transfer" ><?php echo $NoKwitansi ?></a></td>
							 	<td><?php echo $datadb[$i]['No_Ref'] ?></td>
							 	<?php if ($datadb[$i]['StatusUsed'] == 0): ?>
							 		<?php $status_used = '<td style="color:  green;">No</td>'; ?>
							 	<?php else: ?>
							 		<?php $status_used = '<td style="color:  red;">Yes</td>'; ?>
							 	<?php endif ?>
							 	<?php echo $status_used ?>
							 	<td><?php echo $datadb[$i]['NameCandidate'] ?></td>
							 	<td><?php echo $datadb[$i]['Email'] ?></td>
							 	<td><?php echo ($datadb[$i]['Phone'] != '') ? '+'.$datadb[$i]['Phone'] : '' ?></td>
							 	<td><?php echo $datadb[$i]['SchoolName'] ?></td>
							 </tr>
				<?php endfor; ?>
			</tbody>
		</table>
	</div>			
	<!-- <br> -->
	<!-- <hr class="style-eight" /> -->
	<script type="text/javascript">
		
	</script>
<?php else: ?>
<div align = 'center'>No Result Data...</div>		
<?php endif ?>


