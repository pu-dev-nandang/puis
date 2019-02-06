

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4" style="margin-top: 30px;">
            <div id="loadView"></div>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {
        window.IDAM = parseInt("<?php echo $IDAM; ?>");
        window.NIP = "<?php echo $NIP; ?>";
        window.action = "<?php echo $action; ?>";

        // Cek status modify attendance
        checkStatus();
    });

    function checkStatus() {

        $('#loadView').html('<div class="thumbnail" style="padding: 10px;">' +
            '                    <h3 style="color: #9e9e9e;margin-top: 7px;text-align: center;">' +
            '                        <i class="fa fa-refresh fa-spin fa-fw"></i>' +
            '                        Procressing...' +
            '                    </h3>' +
            '                </div>');

        var data = {
             action : 'checkStatusModifyAttd',
             IDAM : IDAM,
             NIP : NIP
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudModifyAttendance';

        $.post(url,{token:token},function (jsonResult) {

            var oops = '<div class="thumbnail" style="min-height: 10px;text-align: center;padding: 10px;">' +
                '                    <h3 class="help-block">' +
                '                        <i class="fa fa-meh-o fa-3x"></i> Oops!<hr/>' +
                '                        Modify Attendance Not Yet</h3>' +
                '                </div>';

            if(jsonResult.length>0){
                var d = jsonResult[0];

                if(d.Status==1 || d.Status=='1'){

                    setTimeout(function () {
                        $('#loadView').html('' +
                            '<div class="alert alert-info" style="margin-bottom: 10px;padding: 15px;text-align: center;">' +
                            '                <h4 class="" style="margin-top: 0px;">This request has been completed by '+ucwords(d.Lecturer)+'</h4>' +
                            '            </div>' +
                            '<div class="thumbnail" style="min-height: 10px;text-align: center;padding: 10px;border: 1px solid green;">' +
                            '                    <h3 style="color: green;margin-top: 7px;">                    <i class="fa fa-check-circle"></i> Approved<br>' +
                            '                        <small>By '+ucwords(d.Lecturer)+'</small></h3>' +
                            '                </div>');
                    },500);

                } else if(d.Status==-1 || d.Status=='-1'){

                    setTimeout(function () {
                        $('#loadView').html('' +
                            '<div class="alert alert-info" style="margin-bottom: 10px;padding: 15px;text-align: center;">' +
                            '                <h4 class="" style="margin-top: 0px;">This request has been completed by '+ucwords(d.Lecturer)+'</h4>' +
                            '            </div>' +
                            '<div class="thumbnail" style="min-height: 10px;text-align: center;padding: 10px;border: 1px solid darkred;">' +
                            '                    <h3 style="color: darkred;margin-top: 7px;"> <i class="fa fa-check-circle"></i> Rejected<br>' +
                            '                        <small>By '+ucwords(d.Lecturer)+'</small></h3>' +
                            '              <div style="background: lightyellow;border: 1px solid #ccc;padding: 10px;text-align: left;">' +
                            '                <span style="color: blue;">Reason : </span> '+d.Reason+' ' +
                            '            </div>' +
                            '                </div>');
                    },500);

                } else if(d.Status==0 || d.Status=='0'){

                    if(action=='approve'){
                        actionApprove();
                    } else if(action=='reject') {

                        setTimeout(function () {
                            $('#loadView').html('<div class="thumbnail" style="min-height: 10px;text-align: center;padding: 10px;">' +
                                '                    <div class="form-group">' +
                                '                        <label>Reason</label>' +
                                '                        <textarea id="formReasonReject" class="form-control" rows="3"></textarea>' +
                                '                    </div>' +
                                '                    <button class="btn btn-block btn-danger" id="btnSubmitReson">Submit</button>' +
                                '                </div>');

                            $('#formReasonReject').focus();

                            $('#btnSubmitReson').click(function () {

                                var formReasonReject = $('#formReasonReject').val();
                                if(formReasonReject!='' && formReasonReject!=null){

                                    if(confirm('Are you sure?')) {

                                        loading_button('#btnSubmitReson');
                                        $('#formReasonReject').prop('disabled',true);
                                        var data = {
                                            action : 'rejectedModifyAttd',
                                            IDAM : IDAM,
                                            Updated1At : dateTimeNow(),
                                            Updated1By : NIP,
                                            Reason : formReasonReject
                                        };

                                        var token = jwt_encode(data,'UAP)(*');
                                        var url = base_url_js+'api2/__crudModifyAttendance';
                                        $.post(url,{token:token},function (result) {
                                            setTimeout(function () {
                                                checkStatus();
                                            },500);
                                        });
                                    }

                                } else {
                                    toastr.warning('Reason is required','Warning');
                                    $('#formReasonReject').focus();
                                }


                            });

                        },500);


                    } else {

                        setTimeout(function () {
                            $('#loadView').html(oops);
                        },500);

                    }

                } else {

                    setTimeout(function () {
                        $('#loadView').html(oops);
                    },500);

                }

            }
            else {
                setTimeout(function () {
                    $('#loadView').html(oops);
                },500);
            }

        });

    }

    // Approve
    function actionApprove() {

        var data = {
            action : 'approvedModifyAttd',
            IDAM : IDAM,
            Updated1At : dateTimeNow(),
            Updated1By : NIP
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudModifyAttendance';
        $.post(url,{token:token},function (result) {
            setTimeout(function () {
                checkStatus();
            },500);
        });


    }


</script>