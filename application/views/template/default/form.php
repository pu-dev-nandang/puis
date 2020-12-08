<style type="text/css">
    .note-editor{
        top: 20px;
        padding: 15px;
        margin: 15px;
    }
</style>
<form id="form" <?php echo (isset($form['class'])) ? 'class = "'.$form['class'].'"' : '' ?>  action="<?php echo $form['action']; ?>" method="post" enctype="multipart/form-data">
<div class="row" style="padding:10px;">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;"><?php echo $heading ?></h4>
            </div>
            <div class="panel-body">
                <?php echo $form['build']; ?>
            </div>
            <div class="panel-footer">
                <a href="<?php echo $base_module_url; ?>" class="btn btn-default">Cancel</a>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
</form>

<script type="text/javascript" src="<?php echo base_url('js/template/form/'); ?>jquery.form.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('js/template/form/'); ?>default.js"></script>