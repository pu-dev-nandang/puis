<div class="row" style="padding:10px;">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;"><?php echo $heading ?></h4>
                <?php if (isset($add) && $add != ''): ?>
                    <div class="toolbar no-padding pull-right">
                        <a href="<?php echo $add ?>" class="btn btn-add">
                            <i class="icon-plus"></i> Add
                       </a>
                    </div>
                <?php endif ?>
            </div>
            <div class="panel-body">
            	<?php $this->load->view('template/default/table'); ?>
            </div>
        </div>
    </div>
</div>