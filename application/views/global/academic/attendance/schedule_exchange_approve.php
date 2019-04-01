
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4" style="text-align: center;margin-top: 30px;">
            <div class="thumbnail" style="padding: 15px;">

                <div id="divPr">
                    <h3 style="color: #9e9e9e;margin-top: 7px;">
                        <i class="fa fa-refresh fa-spin fa-fw"></i>
                        Procressing...
                    </h3>
                </div>






            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        window.EXID = "<?php echo $EXID; ?>";
        window.NIP = "<?php echo $NIP; ?>";

        loadData();

    });

    function loadData() {


        var data = {
            action : 'approveExchange',
            EXID : EXID,
            ApprovedBy : NIP,
            ApprovedAt : dateTimeNow()
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudScheduleExchage';

        $.post(url,{token:token},function (jsonResult) {
            setTimeout(function () {

                if(jsonResult.Status!=-1){
                    $('#divPr').html('<h3 style="color: green;margin-top: 7px;">' +
                        '                    <i class="fa fa-check-circle"></i>' +
                        '                    Approved <br/>' +
                        '                <small>By '+jsonResult.By+'</small></h3>');
                } else {
                    $('#divPr').html('<h3 style="color: red;margin-top: 7px;">' +
                        '                    <i class="fa fa-times-circle-o"></i>' +
                        '                    Rejected <br/>' +
                        '                <small>By '+jsonResult.By+'</small></h3>' +
                        '<div style="background: lightyellow;border: 1px solid #CCCCCC;padding: 10px;text-align: left;">' +
                        '                    <span style="color: blue;">Comment : </span> '+jsonResult.Comment+'  ' +
                        '                </div>');
                }


            },1000);
        });

    }
</script>