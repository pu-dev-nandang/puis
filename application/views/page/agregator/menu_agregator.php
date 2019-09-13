<style>
/*.btn-circle {*/
    /*width: 30px;*/
    /*height: 30px;*/
    /*padding: 6px 0px;*/
    /*border-radius: 15px;*/
    /*text-align: center;*/
    /*font-size: 12px;*/
    /*line-height: 1.42857;*/
/*}*/
 
/*.btn-round{*/
    /*border-radius: 8px;*/
/*}*/
</style> 


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


<div class="row">
    <div class="col-md-2" id="menuLeft">

        <div>
            <a href="<?= base_url('agregator/setting'); ?>" class="btn btn-primary btn-block btn-round"><i class="fa fa-cog"></i> Setting</a>
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

        var elm = $('a[href="'+base_url_js+'<?= $this->uri->segment(1).'/'.$this->uri->segment(2); ?>"]');
        elm.addClass('active-left-menu');
        elm.parent().parent().addClass('in');




        //var elm2 = $('#"'+base_url_js+'agregator/<?//= $this->uri->segment(2); ?>//"]');


        //console.log(elm.attr('id'));
        //
        //var IDMenu =  $('#data_');
        //
        //
        //var MyMenu = JSON.parse('<?//= $MyMenu; ?>//');
        //
        //console.log(IDMenu);
        //console.log(MyMenu);
        //
        //var v = ($.inArray(IDMenu,MyMenu)!=-1) ? '1' : '0';
        //$('#dataView').val(v);

        //         elm.attr('id', 'classAct');


    });

    $(document).on('click','.btnRemove',function () {

        if(confirm('Hapus data?')){

            var ID = $(this).attr('data-id');
            var table = $(this).attr('data-tb');
            var file = $(this).attr('data-file');

            $('.btnAction').prop('disabled',true);

            var url = base_url_js+'api3/__crudAgregatorTB1';

            var data = {
                action: 'removeDataAgg',
                ID : ID,
                Table : table,
                File : file
            };

            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (result) {
                loadDataTable();
                toastr.success('Data removed','Success');
                setTimeout(function () {
                    // loadDataTable();
                },500);

            });


        }


    });

</script>