

<div class="container" style="margin-top: 30px;">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="thumbnail" style="min-height: 10px;padding: 15px;text-align: center;">
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

        loadStatusExchange();

    });

    function loadStatusExchange() {
        var data = {
            action : 'readStatusExchange',
            EXID : EXID
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudScheduleExchage';

        $.post(url,{token:token},function (jsonResult) {

            setTimeout(function () {
                if(jsonResult.Status==0 || jsonResult.Status=='0'){
                    $('#divPr').html('<div class="form-group">' +
                        '                    <label>Reason</label>' +
                        '                    <textarea class="form-control" id="formReason" autofocus rows="3"></textarea>' +
                        '                </div>' +
                        '                <div>' +
                        '                    <button id="btnSubmitReason" class="btn btn-block btn-danger">Submit</button>' +
                        '                </div>');
                }
                else if(jsonResult.Status==1 || jsonResult.Status=='1' || jsonResult.Status==2 || jsonResult.Status=='2'){
                    $('#divPr').html('<h3 style="color: green;margin-top: 7px;">' +
                        '                    <i class="fa fa-check-circle"></i>' +
                        '                    Approved <br/>' +
                        '                <small>By '+jsonResult.By+'</small></h3>');
                }
                else if(jsonResult.Status==-1 || jsonResult.Status=='-1'){
                    $('#divPr').html('<h3 style="color: red;margin-top: 7px;">' +
                        '                    <i class="fa fa-times-circle-o"></i>' +
                        '                    Rejected <br/>' +
                        '                <small>By '+jsonResult.By+'</small></h3>' +
                        '<div style="background: lightyellow;border: 1px solid #CCCCCC;padding: 10px;text-align: left;">' +
                        '                    <span style="color: blue;">Comment : </span> '+jsonResult.Comment+'  ' +
                        '                </div>');
                }
            },500);

        });

    }

    $(document).on('click','#btnSubmitReason',function () {

        var formReason = $('#formReason').val();
        if(formReason!='' && formReason!=null){

            loading_button('#btnSubmitReason');
            $('#formReason').prop('disabled',true);


            var data = {
                action : 'rejectedExchange',
                Comment : formReason,
                EXID : EXID,
                ApprovedAt : dateTimeNow(),
                ApprovedBy : NIP
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api2/__crudScheduleExchage';

            $.post(url,{token:token},function (jsonResult) {
                toastr.success('Reason saved','Success');
                setTimeout(function () {
                    loadStatusExchange();
                },500);
            });
        } else {
            toastr.error('Reason is required','Error');
            $('#formReason').focus();
        }


    });
</script>