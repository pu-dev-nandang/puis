
<style>
    #tableSetting tr th, #tableSetting tr td {
        text-align: center;
    }
    #tableSetting tr td:nth-child(2){
        text-align: left;
    }
</style>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="table-responsive">
<!--            <div class="checkbox checbox-switch switch-primary">-->
<!--                <label>-->
<!--                    <input type="checkbox"id="layoutExam2" />-->
<!--                    <span></span>-->
<!--                </label>-->
<!--            </div>-->
            <table class="table table-striped" id="tableSetting">
                <thead>
                <tr>
                    <th style="width: 1%;">No</th>
                    <th>Setting</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>Random Layout</td>
                    <td>
                        <div class="checkbox checbox-switch switch-primary">
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
                    <td></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>UTS Filter Attendance</td>
                    <td></td>
                </tr>

                <tr>
                    <td>4</td>
                    <td>UAS Filter Payment</td>
                    <td></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>UAS Filter Attendance</td>
                    <td></td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Attendance Student</td>
                    <td></td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {
        loadConfigLayout();
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
</script>