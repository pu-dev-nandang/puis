<div class="row">
	<div class="col-md-8">
			<table class = "table table-bordered" id = "tbl_total_top5_Content">
				<thead>
					<tr class="column">
					    <?php
					    foreach ($tbl_total_top5_content['columns'] as $key => $column) {
					        echo '<th style="' . (isset($column['width']) ? 'width:' . $column['width'] : '') . '" ' . (isset($column['id']) ? 'id="' . $column['id'] . '"' : '') . ' class="' . (isset($column['class']) ? $column['class'] : '') . '" data-data="' . $key . '" data-sort="' . (isset($column['sort']) ? $column['sort'] : '') . '">' . $column['title'] . '</th>';
					    }
					    ?>
					</tr>
					<tr class="filterSearch">
					    <?php $filter = $this->session->userdata('tbl_total_top5_Content'); ?>
					    <?php foreach ($tbl_total_top5_content['columns'] as $key => $column) { ?>
					        <td>
					            <?php if ($column['filter']) { ?>
					                <?php if ($column['filter']['type'] == 'text') : ?>
					                    <div class="input-group">
					                        <span class="input-group-addon"><i class="icon-search"></i></span>
					                        <input type="text" name="<?php echo $key; ?>" class="form-control form-control-sm" autocomplete="off" value="<?php echo ($filter && isset($filter[$key])) ? $filter[$key] : ''; ?>">
					                    </div>
					                <?php elseif ($column['filter']['type'] == 'dropdown') : ?>
					                    <?php echo form_dropdown($key, $column['filter']['options'], ($filter && isset($filter[$key])) ? $filter[$key] : NULL, ['class' => 'form-control form-control-sm']); ?>
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
	<div class="col-md-4">
		<div id = "graph_pie_top5_content" class="chart"></div>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url('js/it/'); ?>summary_knowledgebase/top5Content.js"></script>