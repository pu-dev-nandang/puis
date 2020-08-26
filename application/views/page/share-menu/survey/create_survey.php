




<div class="row">



    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Target Student</h4>
            </div>
            <div class="panel-body">

                <div style="margin-bottom: 15px;">
                    <div style="color: blue;border-bottom: 1px solid #ccc;padding-bottom: 10px;margin-bottom: 10px;">
                        <label class="radio-inline">
                            <input type="radio" name="IsAllUser" id="IsAllUser1" value="-1" checked> Bukan untuk mahasiswa
                        </label>
                    </div>
                    <div>
                        <label class="radio-inline">
                            <input type="radio" name="IsAllUser" id="IsAllUser2" value="1"> Semua mahasiswa
                        </label>
                    </div>
                    <div>
                        <label class="radio-inline">
                            <input type="radio" name="IsAllUser" id="IsAllUser3" value="0"> Custom
                        </label>
                    </div>
                </div>

                <div id="panelCustomStd" class="hide">
                    <div class="well">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Class Of</label>
                                <select class="form-control"></select>
                            </div>
                            <div class="col-md-5">
                                <label>Prodi</label>
                                <select class="form-control"></select>
                            </div>
                            <div class="col-md-4">
                                <label>Status Student</label>
                                <select class="form-control"></select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-12 text-right">
                                <button class="btn btn-sm btn-primary">Add</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 1%;">No</th>
                            <th>Target</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>All student</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-6" style="margin-bottom: 70px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Target Employees</h4>
            </div>
            <div class="panel-body">

                <div style="color: blue;border-bottom: 1px solid #ccc;padding-bottom: 10px;margin-bottom: 10px;">
                    <label class="radio-inline">
                        <input type="radio" name="inlineRadioOptions" id="inlineRadio4" value="option4" checked> Bukan untuk Dosen & Tenaga Pendidik
                    </label>
                </div>
                <div>
                    <label class="radio-inline">
                        <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> Semua Dosen & Tenga Pendidik
                    </label>
                </div>
                <div>
                    <label class="radio-inline">
                        <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Semua Dosen (selain tenaga pendidik)
                    </label>
                </div>
                <div>
                    <label class="radio-inline">
                        <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3"> Semua Tenga Pendidik (selain dosen)
                    </label>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <i class="fa fa-edit margin-right"></i> Create Survey
                </h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Title</label>
                    <input class="form-control">
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Start</label>
                            <input class="form-control" type="date">
                        </div>
                        <div class="col-md-6">
                            <label>End</label>
                            <input class="form-control" type="date">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Note</label>
                    <textarea class="form-control" rows="2"></textarea>
                </div>

            </div>

            <div class="panel-footer text-right">
                <button class="btn btn-success">Save</button>
            </div>

        </div>

    </div>


</div>



<script>

    $(document).ready(function () {
        // setLoadFullPage();
    });

    $('input[type=radio][name="IsAllUser"]').change(function () {
        var val = $('input[type=radio][name="IsAllUser"]:checked').val();
        if(val=='0'){
            $('#panelCustomStd').removeClass('hide');
        } else {
            $('#panelCustomStd').addClass('hide');
        }
    });



</script>