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
					<!-- <th>NIK KTP</th> -->
					<th>Email</th>
					<th>HP</th>
					<th>Sekolah</th>
					<?php for ($i = 0; $i < count($mataujian); $i++): ?>
						<?php 
							$NamaUjian = (strlen($mataujian[$i]['NamaUjian']) > 15) ? substr($mataujian[$i]['NamaUjian'], 0,15).'...' : $mataujian[$i]['NamaUjian'] ;
						 ?>
						<th style="width: 100px;"><?php echo $NamaUjian.'('.$mataujian[$i]['Bobot'].')' ?></th>
					<?php endfor; ?>
					<th>Bobot</th>
					<th>Grade</th>
					<th>Rangking</th>
					<th>File Rangking</th>
				</tr>
			</thead>
			<tbody> 
				<?php for ($i = 0; $i < count($datadb); $i++): ?>
							 <tr>
							 	<?php if (isset($chkActive)): ?>
							 	<td><?php echo $no++ ?> <input type="checkbox" class="uniform" value ="<?php echo $datadb[$i]['ID_register_formulir'] ?>"></td>
							 	<?php endif ?>
							 	<td><?php echo $datadb[$i]['Name'] ?></td>
							 	<td><?php echo $datadb[$i]['NamePrody'] ?></td>
							 	<!-- <td><?php echo $datadb[$i]['IdentityCard'] ?></td> -->
							 	<td><?php echo $datadb[$i]['Email'] ?></td>
							 	<td><?php echo $datadb[$i]['PhoneNumber'] ?></td>
							 	<td><?php echo $datadb[$i]['SchoolName'] ?></td>
							 	<?php $jml_bobot = 0 ?>
							 	<?php $tot_Nilai = 0 ?>
							 	<?php $NilaiAkhir = 0 ?>
							 	<?php $Grade = '' ?>
							 	<?php for ($j = 0; $j < count($mataujian); $j++): ?>
							 		<?php $getData = $this->m_admission->getValuePerid_ujian_register($mataujian[$j]['ID'],$datadb[$i]['ID_register_formulir']) ?>
							 		<td>
							 			<?php echo $getData[0]['Value'] ?>
							 			<?php $tot_Nilai = $tot_Nilai + ($getData[0]['Value'] * $mataujian[$j]['Bobot']) ?>
							 		</td>	
							 		<?php $jml_bobot = $jml_bobot + $mataujian[$j]['Bobot'] ?>
							 	<?php endfor; ?>
							 	<?php $NilaiAkhir = $tot_Nilai / $jml_bobot ?>
							 	<td><?php echo $jml_bobot ?></td>
							 	<?php for ($k = 0; $k < count($grade); $k++): ?>
							 		<?php if ($NilaiAkhir >=  $grade[$k]['StartRange'] && $NilaiAkhir <= $grade[$k]['EndRange'] ): ?>
							 			<?php $Grade = $grade[$k]['Grade'] ?>
							 		<?php endif ?>
							 	<?php endfor; ?>
							 	<td><?php echo $Grade ?></td>
							 	<?php $getData = $this->m_admission->getRangking($datadb[$i]['ID_register_formulir']) ?>
							 	<td><?php echo $getData[0]['Rangking'] ?></td>	
							 	<td><a href="<?php echo $url_registration ?>document/<?php echo $datadb[$i]['Email'] ?>/<?php echo $getData[0]['Attachment'] ?>" target="_blank">File</a></td>	
							 </tr>
				<?php endfor; ?>
			</tbody>
		</table>
	</div>			
<?php else: ?>
<div align = 'center'>No Result Data...</div>		
<?php endif ?>



