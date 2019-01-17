

<div class="row">
    <div class="col-md-12">
        <a href="<?php echo base_url('academic/transfer-student/programme-study'); ?>" class="btn btn-warning">
            <i class="fa fa-arrow-left margin-right"></i>
            Back to list</a>

        <hr/>
        <h3 style="margin-top: 0px;border-left: 7px solid #2196F3;
    padding-left: 10px;font-weight: bold;">Course Conversion</h3>
    </div>
</div>


<div class="row">
    <div class="col-md-6" style="border-right: 1px solid #CCCCCC;min-height: 100px;">
        <div class="well" style="text-align: center;padding: 5px;font-size: 16px;font-weight: bolder;">
            From :
        </div>
    </div>
    <div class="col-md-6">
        <div class="well" style="text-align: center;padding: 5px;font-size: 16px;font-weight: bolder;">
            To :
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadDataTransferStudent();
    });

    function loadDataTransferStudent() {
        var TSID = parseInt('<?php echo $TSID; ?>');
        var token = jwt_encode({action : 'readDataTransferStudent', TSID : TSID},'UAP)(*');
        var url = base_url_js+'api/__crudTransferStudent';
        $.post(url,{token:token},function (jsonResult) {

        });
    }
</script>