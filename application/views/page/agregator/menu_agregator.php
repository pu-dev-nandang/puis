

<style>
    .list-group {
        margin-bottom: 0px;
    }
    .list-group-item {
        /*border: none;*/
        border-left: 0px;
        border-right: 0px;
        border-bottom: 0px solid #ccc !important;
    }

    #menuLeft .panel-default>.panel-heading {
        background-color: #e8e8e8;
    }

    .active-left-menu {
        background: #c8e0ff;
    }
</style>

<?php //print_r($listMenu); ?>

<div class="row">
    <div class="col-md-2" id="menuLeft">

        <div>
            <a href="<?= base_url('agregator/setting'); ?>" class="btn btn-default btn-block"><i class="fa fa-cog"></i> Setting</a>
            <hr/>
        </div>

        <div class="panel-group" id="accordion">

            <?php foreach ($listMenu AS $item){ ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?= $item['ID']; ?>">
                        <h4 class="panel-title">
                            <?= $item['Name']; ?>
                        </h4>
                    </a>
                </div>
                <div id="collapse_<?= $item['ID']; ?>" class="panel-collapse collapse">
                    <div class="list-group">
                        <?php foreach ($item['Menu'] AS $itm2){ ?>
                            <a href="<?= base_url($itm2['URL']); ?>" class="list-group-item"><?= $itm2['Name']; ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <?php } ?>


        </div>
    </div>
    <div class="col-md-10">
        <?= $page; ?>

    </div>
</div>





<script>

    $(document).ready(function () {

        $('.fixed-header').addClass('sidebar-closed');

        $('.list-group-item').removeClass('active-left-menu');
        $('.collapse').removeClass('in');

        var elm = $('a[href="'+base_url_js+'agregator/<?= $this->uri->segment(2); ?>"]');
        elm.addClass('active-left-menu');
        elm.parent().parent().addClass('in');

    });

</script>