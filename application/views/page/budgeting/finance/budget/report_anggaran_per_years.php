<div class="row">
	<div class="col-md-12">
		<div class="col-md-6 col-md-offset-3">
			<div class="thumbnail" style="height: 100px">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<div class="form-group">
							<label>Year</label>
							<select class="select2-select-00 full-width-fix" id="Years">
								 <?php for ($i=0; $i < count($arr_Year); $i++): ?>
								 	<?php $selected = ($arr_Year[$i]['Activated'] == 1) ? 'selected' : ''; ?>
								 	<option value="<?php echo $arr_Year[$i]['Year'] ?>" <?php echo $selected ?>><?php echo $arr_Year[$i]['Year'].'-'.($arr_Year[$i]['Year'] + 1) ?></option>
								 <?php endfor ?>
							 </select>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row" style="margin-top: 10px;">
	<div class="col-md-12">
		
	</div>
</div>
<script type="text/javascript">
	var arr_post = [];
	var arr_BudgetCategory = [];
	var arr_HeadAccount = [];
	$(document).ready(function() {
		$('#Years').select2({
		   //allowClear: true
		});

		loadingEnd(500);
	}); // exit document Function
</script>
