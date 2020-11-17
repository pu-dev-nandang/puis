<div class="row">
	<div class="col-md-12">
		<div id="chart_total_per_division_AC" class="chart" style="padding: 0px;position: relative;min-height: 150px;"></div> 
	</div>
</div>
<div class="row" style="margin-top: 20px;">
	<div class="col-md-12">
		<div id="chart_total_per_division_NAC" class="chart" style="padding: 0px;position: relative;min-height: 150px;"></div> 
	</div>
</div>
<div class="row" style="margin-top: 20px;">
	<div class="col-md-12">
		<div id="chart_total_per_division_NAC2" class="chart" style="padding: 0px;position: relative;min-height: 150px;"></div> 
	</div>
</div>

<div class="row" style="margin-top: 20px;">
	<div class="col-md-12">
		<div id ="table_total_per_division">
			<table class = "table table-bordered" id = "tbl_total_per_division">
				<thead>
					<tr class="column">
					    <?php
					    foreach ($tbl_total_kb_per_divisi['columns'] as $key => $column) {
					        echo '<th style="' . (isset($column['width']) ? 'width:' . $column['width'] : '') . '" ' . (isset($column['id']) ? 'id="' . $column['id'] . '"' : '') . ' class="' . (isset($column['class']) ? $column['class'] : '') . '" data-data="' . $key . '" data-sort="' . (isset($column['sort']) ? $column['sort'] : '') . '">' . $column['title'] . '</th>';
					    }
					    ?>
					</tr>
				</thead>
				<tbody>

				</tbody>
		   </table>
		</div> 
	</div>
</div>

<script type="text/javascript">
	let filterTbl = '';
	<?php if ($this->session->userdata('tbl_total_per_division')): ?>
		filterTbl = '<?php echo $this->session->userdata('tbl_total_per_division') ?>';
	<?php endif ?>
</script>

<script type="text/javascript" src="<?php echo base_url('js/it/'); ?>summary_knowledgebase/total_kb_per_divisi.js"></script>