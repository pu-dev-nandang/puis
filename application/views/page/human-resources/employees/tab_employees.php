
<?php

$btnFilesEmp = ($this->uri->segment(3)=='files') ? 'btn-primary' : 'btn-default btn-default-primary';
$btnInputEmp = ($this->uri->segment(3)=='input-employees') ? 'btn-primary' : 'btn-default btn-default-primary';
$btnListEmp = ($this->uri->segment(2)=='employees' && $this->uri->segment(3)=='') ? 'btn-primary' : 'btn-default btn-default-primary';

?>

<div class="row">
    <div class="col-md-12" style="text-align: right;">

        <a href="<?php echo base_url('human-resources/employees'); ?>" class="btn btn-default <?php echo $btnListEmp; ?>">List Employees</a> |
        <a href="<?php echo base_url('human-resources/employees/input-employees'); ?>" class="btn <?php echo $btnInputEmp; ?>">Add Employees</a> |
        <!--<a href="<?php //echo base_url('human-resources/employees/files'); ?>" class="btn btn-default <?php //echo $btnFilesEmp; ?>">Files Employees</a> -->
        <hr/>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php echo $page; ?>
    </div>
</div>

<!--<div class="row">-->
<!--    <div class="col-md-12">-->
<!--        <div class="tabbable tabbable-custom tabbable-full-width">-->
<!--            <ul class="nav nav-tabs">-->
<!--                <li class="active"><a href="#tab_mata_kuliah" data-toggle="tab">Employees</a></li>-->
<!--                <li><a href="#tab_mata_kuliah" data-toggle="tab">Dosen</a></li>-->
<!--            </ul>-->
<!--            <div class="tab-content row">-->
                <!--=== Overview ===-->
<!--                <div class="tab-pane active" id="tab_mata_kuliah">-->
<!---->
<!--                    --><?php //echo $page; ?>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

