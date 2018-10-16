
<div class="row">
    <div class="col-md-12">
        <a href="<?php echo base_url('database/students'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left margin-right"></i> Back to List Student</a>
    </div>
</div>

<div class="row">

    <div class="col-md-4">
        <hr/>
        <div class="panel panel-primary">
            <div class="panel-body" style="min-height: 100px;">

                <div style="text-align: left;">
                    <h1 style="margin-bottom: 20px;margin-top: 0px"><i class="fa fa-user margin-right"></i> Biodata</h1>
                </div>

                <div style="text-align: center;margin-bottom: 10px;">
                    <img id="viewImage" width="100" class="img-rounded">
                </div>

                <table class="table">
                    <tr>
                        <th style="width: 25%;">NIM</th>
                        <th style="width: 1%;">:</th>
                        <td>
                            <input class="form-control" disabled id="formNPM">
                        </td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <th>:</th>
                        <td>
                            <input class="form-control formBiodata" id="formName">
                        </td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <th>:</th>
                        <td>
                            <label class="radio-inline">
                                <input type="radio" name="formGender" value="L"> Male
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="formGender" value="P"> Female
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th>Place Of Birth</th>
                        <th>:</th>
                        <td>
                            <input class="form-control formBiodata" id="formPlaceOfBirth">
                        </td>
                    </tr>
                    <tr>
                        <th>Date of Birth</th>
                        <th>:</th>
                        <td>
                            <input class="form-control formBiodata" id="formDateOfBirth">
                        </td>
                    </tr>

                    <tr>
                        <th>Phone</th>
                        <th>:</th>
                        <td>
                            <input class="form-control formBiodata" id="formPhone">
                        </td>
                    </tr>
                    <tr>
                        <th>HP</th>
                        <th>:</th>
                        <td>
                            <input class="form-control formBiodata" id="formHP">
                        </td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <th>:</th>
                        <td>
                            <input class="form-control formBiodata" id="formEmail">
                        </td>
                    </tr>
                    <tr>
                        <th>Email PU</th>
                        <th>:</th>
                        <td>
                            <input class="form-control formBiodata" id="formEmailPU">
                        </td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <th>:</th>
                        <td>
                            <textarea rows="3" class="form-control formBiodata" id="formAddress"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>Jacket</th>
                        <th>:</th>
                        <td>
                            <input class="form-control formBiodata" id="formJacket">
                        </td>
                    </tr>
                </table>

                <div style="text-align: right;">
                    <hr/>
                    <button class="btn btn-success" id="btnSaveBiodata">Save</button>
                </div>

            </div>
        </div>

    </div>

    <div class="col-md-4">
        <hr/>
        <div class="panel panel-primary">
            <div class="panel-body" style="min-height: 100px;">

                <div style="text-align: left;">
                    <h1 style="margin-bottom: 20px;margin-top: 0px"><i class="fa fa-bookmark margin-right"></i> Academic</h1>
                </div>

                <table class="table">
                    <tr>
                        <th style="width: 30%;">Programs</th>
                        <th style="width: 1%;">:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Programs</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Programme Study</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Study Level</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Mentor Academic</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                </table>

            </div>
        </div>

    </div>

    <div class="col-md-4">
        <hr/>
        <div class="panel panel-primary">
            <div class="panel-body" style="min-height: 100px;">

                <div style="text-align: left;">
                    <h1 style="margin-bottom: 20px;margin-top: 0px"><i class="fa fa-user-secret margin-right"></i> Parent</h1>
                </div>

                <table class="table">
                    <tr>
                        <th colspan="3" style="text-align: center;background: lightyellow;">FATHER</th>
                    </tr>
                    <tr>
                        <th style="width: 25%;">Name</th>
                        <th style="width: 1%;">:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Education</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Occupation</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>HP</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Adress</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>

                    <tr>
                        <th colspan="3" style="text-align: center;background: lightyellow;">Mother</th>
                    </tr>

                    <tr>
                        <th>Name</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Education</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Occupation</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>HP</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Adress</th>
                        <th>:</th>
                        <td>
                            <input class="form-control">
                        </td>
                    </tr>

                </table>

            </div>
        </div>

    </div>


</div>

<script>

    $(document).ready(function () {
        loadDataStd();
    });
    
    $('#btnSaveBiodata').click(function () {

        var DB_Student = "<?php echo $DB_Student; ?>";

        var formNPM = $('#formNPM').val();
        var formName = $('#formName').val();
        var formEmailPU = $('#formEmailPU').val();

        if(formNPM!='' && formNPM!=null
            && formName!='' && formName!=null
            && formEmailPU!='' && formEmailPU!=null){

            loading_buttonSm('#btnSaveBiodata');
            $('.formBiodata').prop('disabled',true);

            var formGender = $('input[type=radio][name=formGender]:checked').val();
            var formPlaceOfBirth = $('#formPlaceOfBirth').val();
            var formDateOfBirth = $('#formDateOfBirth').val();
            var formPhone = $('#formPhone').val();
            var formHP = $('#formHP').val();
            var formEmail = $('#formEmail').val();
            var formEmailPU = $('#formEmailPU').val();

            var data = {
                action : 'updateBiodataStudent',
                DB_Student : DB_Student,
                NPM : formNPM,
                EmailPU : formEmailPU,
                dataForm : {
                    Name : formName,
                    Gender : formGender,
                    PlaceOfBirth : formPlaceOfBirth,
                    DateOfBirth : formDateOfBirth,
                    Phone : formPhone,
                    HP : formHP,
                    Email : formEmail,
                    Address : formAddress,
                    Jacket : formJacket
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudStudent';
            $.post(url,{token:token},function (result) {

                setTimeout(function () {
                    $('#btnSaveBiodata').html('Save');
                    $('.formBiodata,#btnSaveBiodata').prop('disabled',false);
                },500);
            });

        }

    });

    function loadDataStd() {
        var NPM = "<?php echo $NPM; ?>";
        var DB_Student = "<?php echo $DB_Student; ?>";

        var data = {
            action : 'readDataStudent',
            DB_Student : DB_Student,
            NPM : NPM
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudStudent';
        
        $.post(url,{token:token},function (jsonResult) {
            console.log(jsonResult);
            if(jsonResult.length>0){
                var d = jsonResult[0];
                $('#formNPM').val(d.NPM);
                $('#formName').val(d.Name);
                $('input[type=radio][name=formGender][value='+d.Gender+']').prop('checked',true);
                $('#formPlaceOfBirth').val(d.PlaceOfBirth);
                $('#formDateOfBirth').val(d.DateOfBirth);
                $('#formPhone').val(d.Phone);
                $('#formHP').val(d.HP);
                $('#formEmail').val(d.Email);
                $('#formEmailPU').val(d.EmailPU);
                $('#formAddress').val(d.Address);
                $('#formJacket').val(d.Jacket);

                $('#viewImage').attr('data-src',base_url_js+'uploads/students/'+DB_Student+'/'+d.Photo);
                $('#viewImage').imgFitter({

                    // CSS background position
                    backgroundPosition: 'center center',

                    // for image loading effect
                    fadeinDelay: 400,
                    fadeinTime: 1200

                });

            }
        });

    }
</script>
