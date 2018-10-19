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
					<?php if (isset($chkActive)): ?>
						<th class="checkbox-column" style="width: 60px;">
							<input type="checkbox" class="uniform" value="nothing" id ="dataResultCheckAll">
						</th>
					<?php endif ?>
					<th>Name</th>
					<th>Program Study</th>
					<th style="width: 100px;">Gender</th>
					<th>NIK KTP</th>
					<th>Nationality</th>
					<th>Agama</th>
					<th>Tempat, Tgl Lahir</th>
					<th>Email</th>
					<th>HP</th>
					<th>Sekolah</th>
					<th>Jurusan Sekolah</th>
					<th>Tahun Lulus</th>
				</tr>
			</thead>
			<tbody> 
				<?php for ($i = 0; $i < count($datadb); $i++): ?>
							 <tr>
							 	<?php if (isset($chkActive)): ?>
							 	<td><?php echo $no++ ?> <input type="checkbox" class="uniform" value ="<?php echo $datadb[$i]['ID_register_formulir'] ?>"></td>
							 	<?php endif ?>
							 	<td>
							 		<?php echo $datadb[$i]['Name'] ?><br>
							 			<?php 
							 			$Code = ($datadb[$i]['No_Ref'] != "") ? $datadb[$i]['FormulirCode'].' / '.$datadb[$i]['No_Ref'] : $datadb[$i]['FormulirCode'];
							 			echo $Code;
							 			 ?>	
							 	</td>
							 	<td><?php echo $datadb[$i]['NamePrody'] ?></td>
							 	<td><?php echo $datadb[$i]['Gender'] ?></td>
							 	<td><?php echo $datadb[$i]['IdentityCard'] ?></td>
							 	<td><?php echo $datadb[$i]['Nationality'] ?></td>
							 	<td><?php echo $datadb[$i]['Religion'] ?></td>
							 	<td><?php echo $datadb[$i]['PlaceDateBirth'] ?></td>
							 	<td><?php echo $datadb[$i]['Email'] ?></td>
							 	<td><?php echo $datadb[$i]['PhoneNumber'] ?></td>
							 	<td><?php echo $datadb[$i]['SchoolName'] ?></td>
							 	<td><?php echo $datadb[$i]['SchoolMajor'] ?></td>
							 	<td><?php echo $datadb[$i]['YearGraduate'] ?></td>
							 </tr>
				<?php endfor; ?>
			</tbody>
		</table>
	</div>			
<?php else: ?>
<div align = 'center'>No Result Data...</div>		
<?php endif ?>



