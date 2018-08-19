
<style>
    .crumbs {
        margin: -20px -20px;
        margin-bottom: 30px;
    }
</style>

<div class="crumbs">
    <ul id="breadcrumbs" class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="<?php echo base_url(); ?>">Dashboard</a>
        </li>
        <?php for($i=1;$i<=count($segment);$i++){
            if($i!=1) { ?>
            <li class="current">
                <a href="javascript:void(0);" title="">
                    <?php echo ucwords(str_replace("-"," ",$segment[$i])); ?>
                </a>
            </li>
        <?php }
        } ?>

    </ul>

    <ul class="crumb-buttons">
        <li><a href="javascript:void(0);" title="">Department :
                <span style="color:#ff1100;"><?php echo ucwords(strtolower(str_replace('-',' ',$departement))); ?>
                </span><span></span></a></li>
    </ul>

</div>