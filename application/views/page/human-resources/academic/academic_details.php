
<div class="tab-pane active" id="tab_mata_kuliah">

    <table class="table table-bordered table-striped">
        <tr>
            <td style="width: 25%">NIP</td>
            <td><span id="dataNIP"></span></td>
        </tr>
        <tr>
            <td>NIDN</td>
            <td><span id="dataNIDN"></span></td>
        </tr>
        <tr>
            <td>No KTP</td>
            <td><span id="dataKTP"></span></td>
        </tr>

        <tr>
            <td>Pasport</td>
            <td><span id="dataPasport"></span></td>
        </tr>
        <tr>
            <td>Gender</td>
            <td><span id="dataGender"></span></td>
        </tr>
        <tr>
            <td>Religion</td>
            <td><span id="dataReligian"></span></td>
        </tr>
        <tr>
            <td>Place Of Birth</td>
            <td><span id="dataPlaceOfBirth"></span></td>
        </tr>
        <tr>
            <td>Date Of Birth</td>
            <td><span id="dataDateOfBirth"></span></td>
        </tr>
        <tr>
            <td>Telephone</td>
            <td><span id="dataTelephone"></span></td>
        </tr>
        <tr>
            <td>HP</td>
            <td><span id="dataHP"></span></td>
        </tr>
        <tr>
            <td>Email PU</td>
            <td><span id="dataEmailPU"></span></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><span id="dataEmail"></span></td>
        </tr>
        <tr>
            <td>Address</td>
            <td><span id="dataAddress"></span></td>
        </tr>
        <!--<tr>
            <td>Postal Code</td>
            <td><span id="dataPostalCode"></span></td>
        </tr> -->
        <tr>
            <td>Province</td>
            <td><span id="dataProvince"></span></td>
        </tr>
        <!--<tr>
            <td>Districts</td>
            <td><span id="dataDistricts"></span></td>
        </tr> -->
        <tr>
            <td>Nationality</td>
            <td><span id="dataNationality">INDONESIA</span></td>
        </tr>
    </table>
</div>

<script>
    $(document).ready(function () {
        loadLecturerDetails();
    });
    function loadLecturerDetails() {
        var url = base_url_js+'api/__crudAcademic';
        var NIP = '<?php echo $NIP; ?>';

        var token = jwt_encode({action:'read',NIP:NIP},'UAP)(*');

        $.post(url,{token:token},function (resultJson) {

            if(resultJson.Gender == "P") {
                var jenis_kelamin = "Female";
            } else {
                var jenis_kelamin = "Male";
            }

            $('#dataImg').html('<img src="'+base_url_img_employee+''+resultJson.Photo+'" class="img-thumbnail">');

            $('#dataName').html(resultJson.TitleAhead+' '+resultJson.Name+' '+resultJson.TitleBehind);

            $('#dataNIP').html(resultJson.NIP);
            $('#dataNIDN').html(resultJson.NIDN);
            $('#dataKTP').html(resultJson.KTP);
            $('#dataGender').html(jenis_kelamin);
            $('#dataReligian').html(resultJson.Religion);
            $('#dataPlaceOfBirth').html(resultJson.PlaceOfBirth);
            $('#dataDateOfBirth').html(resultJson.DateOfBirth);
            $('#dataTelephone').html(resultJson.Phone);
            $('#dataHP').html(resultJson.HP);
            $('#dataEmailPU').html(resultJson.EmailPU);
            $('#dataEmail').html(resultJson.Email);
            $('#dataAddress').html(resultJson.Address);
            $('#dataProvince').html(resultJson.Nameprov);

            });
            
    }
</script>





