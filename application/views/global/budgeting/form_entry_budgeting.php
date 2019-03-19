<!-- jika finance authorize -->
<?php if ($fin == 1): ?>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="thumbnail" style="height: 100px">
			<div class="col-xs-6 col-md-offset-3" "="">
				<div class="form-group">
					<label>Departement</label>
					<select class="select2-select-00 full-width-fix" id="Departement">
						<?php for($i=0; $i < count($arr_department_pu); $i++): ?>
							<?php $selected = ($i == 0) ? 'selected' : '' ?>
							<option value="<?php echo $arr_department_pu[$i]['Code'] ?>" <?php echo $selected  ?> > <?php echo $arr_department_pu[$i]['Name2'] ?></option>
						<?php endfor ?>
					</select>	
				</div>	
			</div>
		</div>
	</div>
</div>
<?php endif ?>
<!-- jika finance authorize -->

<div class="row" style="margin-left: 10px;margin-right: 10px">
	<div class="col-md-3">
		<button class = "btn btn-default" id = "ChooseSubAccount">Choose Sub Account</button>
	</div>
	<div class="col-md-4 col-md-offset-1">
		<div class="form-group">
			<label>Year</label>
			<select class="select2-select-00 full-width-fix" id="Year">
				<?php for($i=0; $i < count($arr_Year); $i++): ?>
					<?php $selected = ($arr_Year[$i]['Activated'] == 1) ? 'selected' : '' ?>
					<option value="<?php echo $arr_Year[$i]['Year']?>" <?php echo $selected  ?> > <?php echo $arr_Year[$i]['Year'] ?> - <?php echo ($arr_Year[$i]['Year'] + 1) ?></option>
				<?php endfor ?>
			</select>	
		</div>	
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#Departement').select2({
	   //allowClear: true
	});

	$('#Year').select2({
	   //allowClear: true
	});
}); // exit document Function
</script>

