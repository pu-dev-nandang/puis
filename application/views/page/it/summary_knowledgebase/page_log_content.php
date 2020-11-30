<?php $filter = $this->session->userdata('tbl_kb_log_content'); ?>
<?php 
$checkedAllDate = '';
$start_date = '';
$end_date = '';
if (!empty($filter)) {
	if ( empty($filter['start_date']) && empty($filter['end_date']) ) {
		$checkedAllDate = 'checked';
	}
	else
	{
		$start_date = $filter['start_date'];
		$end_date = $filter['end_date'];
	}
}


 ?>
<div class="row">
	<div class="col-md-12">
		<div class="well">
			<div class="row">
				<div class="col-md-3">
			  		<label class="checkbox-inline">
			  			<input type="checkbox" class="dateCheckedLogContent" name="dateOP" id="dateCheckedLogContent" value="0" <?php echo $checkedAllDate ?> > ALL Date
			  		</label>

			  	</div>
    			<div class="col-md-4">
    				<label>Start</label>
					<div id="filter_emp_datetimepicker1" class="input-group input-append date datetimepicker">
						<input data-format="yyyy-MM-dd" class="form-control" id="datetimepicker1_by_employee" type=" text" readonly="" value="<?php echo $start_date ?>">
						<span class="input-group-addon add-on">
							<i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i>
						</span>
					</div>
    			</div>
    			<div class="col-md-4">
    				<label>End</label>
    				<div id="filter_emp_datetimepicker2" class="input-group input-append date datetimepicker">
    					<input data-format="yyyy-MM-dd" class="form-control" id="datetimepicker2_by_employee" type=" text" readonly="" value="<?php echo $end_date ?>">
    					<span class="input-group-addon add-on">
    						<i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i>
    					</span>
    				</div>
    			</div>
			</div>
			<div class="row" style="margin-top: 15px;">
				<div class="col-md-4">
					<button class="btn btn-primary btn-sm btnSearchLogContent">Search</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
			<table class = "table table-bordered" id = "tbl_kb_log_content">
				<thead>
					<tr class="column">
					    <?php
					    foreach ($tbl_kb_log_content['columns'] as $key => $column) {
					        echo '<th style="' . (isset($column['width']) ? 'width:' . $column['width'] : '') . '" ' . (isset($column['id']) ? 'id="' . $column['id'] . '"' : '') . ' class="' . (isset($column['class']) ? $column['class'] : '') . '" data-data="' . $key . '" data-sort="' . (isset($column['sort']) ? $column['sort'] : '') . '">' . $column['title'] . '</th>';
					    }
					    ?>
					</tr>
					<tr class="filterSearch_log_content">
					    <?php foreach ($tbl_kb_log_content['columns'] as $key => $column) { ?>
					        <td>
					            <?php if ($column['filter']) { ?>
					                <?php if ($column['filter']['type'] == 'text') : ?>
					                    <div class="input-group">
					                        <span class="input-group-addon"><i class="icon-search"></i></span>
					                        <input type="text" name="<?php echo $key; ?>" class="form-control form-control-sm" autocomplete="off" value="<?php echo ($filter && isset($filter[$key])) ? $filter[$key] : ''; ?>">
					                    </div>
					                <?php elseif ($column['filter']['type'] == 'dropdown') : ?>
					                    <?php echo form_dropdown($key, $column['filter']['options'], ($filter && isset($filter[$key])) ? $filter[$key] : NULL, ['class' => 'col-md-12']); ?>
					                <?php elseif ($column['filter']['type'] == 'action') : ?>
					                    <button type="button" class="btn btn-primary btn-sm"><i class="icon-filter3"></i></button>
					                    <?php endif; ?>
					                <?php } ?>
					        </td>
					    <?php } ?>
					</tr>
				</thead>
				<tbody>

				</tbody>
		   </table>
	</div>
</div>

<script type="text/javascript">
	const dateNowLogContent = '<?php echo date('Y-m-d') ?>';
</script>

<script type="text/javascript" src="<?php echo base_url('js/it/'); ?>summary_knowledgebase/page_log_content.js"></script>