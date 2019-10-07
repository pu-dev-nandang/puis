
<div class="" style="margin-top: 30px;">

     <div class="col-md-12">
        <div class="tabbable tabbable-custom tabbable-full-width">
            <?php $activeMenu = $this->uri->segment(1); ?>
            <ul class="nav nav-tabs">
                <li>
                    <a href="<?php echo base_url('rectorat/master_data/config_jabatan_per_sks'); ?>">Config Jabatan Per SKS</a>
                </li>
                <!-- <li>
                    <a href="<?php echo base_url('library/yudisium/final-project'); ?>">Config Jabatan Per SKS</a>
                </li> -->
            </ul>
            <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                <?php echo $page; ?>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function () {

        var menu_active = "<?php echo $this->uri->segment(3); ?>";
        var arrMenu = ['config_jabatan_per_sks'];
        setMenuSelected('.nav-tabs','li','active',arrMenu,menu_active);

    });
</script>



