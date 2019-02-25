
<style>
    /*#tableSetting tr th {*/
        /*text-align: center;*/
    /*}*/
    #tableSetting tr td:nth-child(2){
        text-align: left;
    }
</style>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="">
            <table class="table table-striped" id="tableSetting">
                <thead>
                <tr>
                    <th style="width: 1%;">No</th>
                    <th>Setting</th>
                    <th style="width: 40%;">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>Random Layout</td>
                    <td>
                        <div class="checkbox checbox-switch switch-primary" style="margin: 0px;">
                            <label>
                                <input type="checkbox"id="layoutExam" />
                                <span></span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>UTS Filter Payment</td>
                    <td>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="checkbox checbox-switch switch-primary" style="margin: 0px;">
                                    <label>
                                        <input type="checkbox" id="UTSPaymentBPP" value="1" />
                                        <span></span> BPP
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkbox checbox-switch switch-primary" style="margin: 0px;">
                                    <label>
                                        <input type="checkbox" id="UTSPaymentCredit" value="1" />
                                        <span></span> Credit
                                    </label>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>UTS Filter Attendance</td>
                    <td>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="checkbox checbox-switch switch-primary" style="margin: 0px;margin-top: 5px;">
                                    <label>
                                        <input type="checkbox" id="UTSAttd" value="1" />
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="UTSAttdValue">
                                    <span class="input-group-addon" id="basic-addon2"><i class="fa fa-percent" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>4</td>
                    <td>UAS Filter Payment</td>
                    <td>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="checkbox checbox-switch switch-primary" style="margin: 0px;">
                                    <label>
                                        <input type="checkbox" id="UASPaymentBPP" value="1" />
                                        <span></span> BPP
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkbox checbox-switch switch-primary" style="margin: 0px;">
                                    <label>
                                        <input type="checkbox" id="UASPaymentCredit" value="1" />
                                        <span></span> Credit
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>UAS Filter Attendance</td>
                    <td>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="checkbox checbox-switch switch-primary" style="margin: 0px;margin-top: 5px;">
                                    <label>
                                        <input type="checkbox" id="UASAttd" value="1" />
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="UASAttdValue" placeholder="">
                                    <span class="input-group-addon" id="basic-addon2"><i class="fa fa-percent" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td colspan="3" style="text-align: right;">
                        <button class="btn btn-success" id="btnSavedSetting">Save</button>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {
        loadConfigLayout();
        loadConfigExam();
    });


    function loadConfigLayout() {

        var token = jwt_encode({action:'readConfig',ConfigID:1},'UAP)(*');
        var url = base_url_js+'api/__crudConfig';
        $.post(url,{token:token},function (jsonResult) {
            var c = (jsonResult[0].Status==1 || jsonResult[0].Status=='1') ? 'checked' : '';
            $('#layoutExam').prop('checked',c);
        });
    }

    $('#layoutExam').change(function () {

        var status = ($('#layoutExam').is(':checked')) ? '1' : '0';

        var token = jwt_encode({action:'updateConfig',ConfigID:1,Status:status},'UAP)(*');
        var url = base_url_js+'api/__crudConfig';
        $.post(url,{token:token},function (result) {
            toastr.success('Data Saved','Success');
        });
    });


    function loadConfigExam() {

        var token = jwt_encode({action : 'readExamSetting'},'UAP)(*');
        var url = base_url_js+'api/__crudConfig';

        $.post(url,{token:token},function (jsonResult) {
            console.log(jsonResult);
            var d = jsonResult[0];

            $('#UTSAttdValue').val(d.UTSAttdValue);
            $('#UASAttdValue').val(d.UASAttdValue);

            var UTSPaymentBPP = (d.UTSPaymentBPP==1 || d.UTSPaymentBPP=='1') ? true : false;
            var UTSPaymentCredit = (d.UTSPaymentCredit==1 || d.UTSPaymentCredit=='1') ? true : false;
            var UTSAttd = (d.UTSAttd==1 || d.UTSAttd=='1') ? true : false;
            var UASPaymentBPP = (d.UASPaymentBPP==1 || d.UASPaymentBPP=='1') ? true : false;
            var UASPaymentCredit = (d.UASPaymentCredit==1 || d.UASPaymentCredit=='1') ? true : false;
            var UASAttd = (d.UASAttd==1 || d.UASAttd=='1') ? true : false;

            $('#UTSPaymentBPP').prop('checked',UTSPaymentBPP);
            $('#UTSPaymentCredit').prop('checked',UTSPaymentCredit);
            $('#UTSAttd').prop('checked',UTSAttd);
            $('#UASPaymentBPP').prop('checked',UASPaymentBPP);
            $('#UASPaymentCredit').prop('checked',UASPaymentCredit);
            $('#UASAttd').prop('checked',UASAttd);

        });
    }
    // Saving Exam Setting
    $('#btnSavedSetting').click(function () {
        loading_buttonSm('#btnSavedSetting');

        var UTSPaymentBPP = ($('#UTSPaymentBPP').is(':checked')) ? '1' : '0';
        var UTSPaymentCredit = ($('#UTSPaymentCredit').is(':checked')) ? '1' : '0';
        var UTSAttd = ($('#UTSAttd').is(':checked')) ? '1' : '0';
        var UTSAttdValue = ($('#UTSAttdValue').val()!='' && $('#UTSAttdValue').val()!=null) ? $('#UTSAttdValue').val() : 0;
        var UASPaymentBPP = ($('#UASPaymentBPP').is(':checked')) ? '1' : '0';
        var UASPaymentCredit = ($('#UASPaymentCredit').is(':checked')) ? '1' : '0';
        var UASAttd = ($('#UASAttd').is(':checked')) ? '1' : '0';
        var UASAttdValue = ($('#UASAttdValue').val()!='' && $('#UASAttdValue').val()!=null) ? $('#UASAttdValue').val() : 0;

        var data = {
            action : 'updateExamSetting',
            formData : {
                UTSPaymentBPP : UTSPaymentBPP,
                UTSPaymentCredit : UTSPaymentCredit,
                UTSAttd : UTSAttd,
                UTSAttdValue : UTSAttdValue,
                UASPaymentBPP : UASPaymentBPP,
                UASPaymentCredit : UASPaymentCredit,
                UASAttd : UASAttd,
                UASAttdValue : UASAttdValue
            }
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudConfig';

        $.post(url,{token:token},function (jsonResult) {
            toastr.success('Setting saved','Success');
            loadConfigExam();
            setTimeout(function () {
                $('#btnSavedSetting').html('Save').prop('disabled',false);
            },500);
        });

    });
</script>