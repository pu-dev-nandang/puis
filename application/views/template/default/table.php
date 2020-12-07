<div class="row">
	<div class="col-md-12">
		 <!-- <div class="table-responsive"> -->
			<table class = "table table-bordered" id = "table_default" data-url="<?php echo $table_default['url']; ?>">
				<thead>
					<tr class="column">
					    <?php
					    foreach ($table_default['columns'] as $key => $column) {
					        echo '<th style="' . (isset($column['width']) ? 'width:' . $column['width'] : '') . ' ;text-align: center;background: #20485A;color: #FFFFFF;'.'" ' . (isset($column['id']) ? 'id="' . $column['id'] . '"' : '') . ' class="' . (isset($column['class']) ? $column['class'] : '') . '" data-data="' . $key . '" data-sort="' . (isset($column['sort']) ? $column['sort'] : '') . '">' . $column['title'] . '</th>';
					    }
					    ?>
					</tr>
					<tr class="filterSearch">
					    <?php $filter = $this->session->userdata($filter_name); ?>
					    <?php foreach ($table_default['columns'] as $key => $column) { ?>
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
					                   <div style="text-align: center;"><i class="fa fa-cog margin-right"></i></div>
					                    <?php endif; ?>
					                <?php } ?>
					        </td>
					    <?php } ?>
					</tr>
				</thead>
				<tbody>

				</tbody>
		   </table>
		 <!-- </div> -->
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url('js/template/table/'); ?>default.js"></script>