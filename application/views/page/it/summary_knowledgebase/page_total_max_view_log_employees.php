<div class="row">
	<div class="col-md-12">
			<table class = "table table-bordered" id = "tbl_total_top100_view_log_employees">
				<thead>
					<tr class="column">
					    <?php
					    foreach ($total_top100_view_log_employees['columns'] as $key => $column) {
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

<script type="text/javascript">
	let filterTbl_total_top100_view_log_employees = '';
	<?php if ($this->session->userdata('total_top100_view_log_employees')): ?>
		filterTbl_total_top100_view_log_employees = '<?php echo $this->session->userdata('total_top100_view_log_employees') ?>';
	<?php endif ?>
</script>

<script type="text/javascript" src="<?php echo base_url('js/it/'); ?>summary_knowledgebase/total_top100_view_log_employees.js"></script>