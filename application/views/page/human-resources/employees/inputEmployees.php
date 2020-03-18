<style type="text/css">
    .bg-required{color: red;font-weight: bold;}
</style>
<!-- OLD FORM -->
<div class="panel panel-primary" id="form-employee">
    <div class="panel-heading" style="border-radius: 0px;">
        <h4 class="panel-title"><i class="fa fa-edit"></i> Please, Fill out this form with correctly data</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;">

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-id-card"></i> Fill out this field based on Identity Card (KTP)
                        <span class="bg-required pull-right">required</span></h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="form-group">
                                    <label>Full Name </label>
                                    <input class="form-control required" id="formName" autocomplete="off"/>
                                    <small class="text-danger text-message"></small>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label>Title Ahead</label>
                                    <input class="form-control" id="formTitleAhead" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label>Title Behind</label>
                                    <input class="form-control" id="formTitleBehind" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="form-control required" id="formGender">
                                        <option value="">Choose one</option>
                                        <option value="L">Male</option>
                                        <option value="P">Female</option>
                                    </select>
                                    <small class="text-danger text-message"></small>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>Number of ID Card </label>
                                    <input class="form-control number required" id="formKTP" autocomplete="off" />
                                    <small class="text-danger text-message"></small>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Religion </label>
                                    <select class="form-control required" id="formReligion">
                                        <option value="">Choose one</option>
                                    </select>
                                    <small class="text-danger text-message"></small>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <label>Marital Status</label>
                                <select class="form-control required" id="formMaritalStatus">
                                    <option value="">Choose One</option>
                                </select>
                                <small class="text-danger text-message"></small>
                            </div>
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label>Blood</label>
                                    <input class="form-control required" id="formBlood" maxlength="3" />
                                    <small class="text-danger text-message"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group" style="margin-bottom:0px">
                                    <label>Place of Birth</label>
                                </div>
                                <div class="form-group">
                                    <label>Type name of city</label>
                                    <input class="form-control required" id="formPlaceOfBirht" />
                                    <small class="text-danger text-message"></small>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group" style="margin-bottom:0px">
                                    <label>Date of birth</label>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Year</label>
                                            <select class="select2-req" id="formYearBirth">
                                                <option>Choose one</option>
                                            </select>
                                            <small class="text-danger text-message"></small>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Month</label>
                                            <select class="form-control required" id="formMontBirth">
                                                <option value="">Choose one</option>
                                            </select>
                                            <small class="text-danger text-message"></small>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <select class="form-control required" id="formDateBirth">
                                                <option value="">Choose one</option>
                                            </select>
                                            <small class="text-danger text-message"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea rows="3" class="form-control required" id="formAddress"></textarea>
                                    <small class="text-danger text-message"></small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" id="PlaceIDCard">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Country</label>
                                    <select class="com-CountryID select2-req" id="CountryID" name="CountryID"></select>
                                    <small class="text-danger text-message"></small>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Province</label>
                                    <select class="form-control isrequire com-ProvinceID" id="ProvinceID" name="ProvinceID">
                                        <option value="">Choose one</option>
                                    </select>
                                    <small class="text-danger text-message"></small>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>Region</label>
                                    <select class="form-control isrequire com-RegionID" id="RegionID" name="RegionID">
                                        <option value="">Choose one</option>
                                    </select>
                                    <small class="text-danger text-message"></small>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>District</label>
                                    <select class="form-control isrequire com-DistrictID" id="DistrictID" name="DistrictID">
                                        <option value="">Choose one</option>
                                    </select>
                                    <small class="text-danger text-message"></small>
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label>Postcode</label>
                                    <input type="text" class="form-control required number" id="formPostcode" maxlength="5">
                                    <small class="text-danger text-message"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="fa fa-id-card"></i> Fill out this field based on Employee Data
                            <span class="bg-required pull-right">required</span>
                        </h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>NIK / NIP</label>
                                            <input class="form-control required number" id="formNIP" />
                                            <small class="text-danger text-message"></small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Access Card Number</label>
                                            <input type="text" class="form-control number required" id="CardNumber">
                                            <small class="text-danger text-message"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Status Employees</label>
                                            <select class="form-control required" id="formStatusEmployee">
                                                <option value="">Choose one</option>
                                            </select>
                                            <small class="text-danger text-message"></small>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Level of Education</label>
                                            <select class="form-control required" id="formLevelEducationID">
                                                <option value="">Choose one</option>
                                            </select>
                                            <small class="text-danger text-message"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Email Other</label>
                                            <input class="form-control required" id="formEmail" autocomplete="off" />
                                            <small class="text-danger text-message"></small>
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input class="form-control number" id="formPhone"  autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Mobile</label>
                                            <input class="form-control number required" id="formMobile" autocomplete="off" />
                                            <small class="text-danger text-message"></small>
                                        </div>
                                    </div>                                
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group" style="margin-bottom:0px">
                                            <label>Internal telphone number</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Extension</label>                                            
                                            <input class="form-control number" id="formExtension" autocomplete="off" maxlength="5" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Password Telp</label>                                            
                                            <input class="form-control number" id="formPassTelp" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Email PU</label>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="formEmailPU" disabled>
                                                    <span class="input-group-addon">@podomorouniversity.ac.id</span>
                                                </div>
                                                <label> *Email PU Auto Generete by system</label>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                
                            </div>
                            
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Photo</label>
                                </div>
                                <div class="form-group">
                                    <center>
                                    <img id="imgThumbnail" src="<?php echo base_url('images/icon/userfalse.png'); ?>" style="max-width: 100px;width: 100%;">
                                    </center>
                                    <form id="fmPhoto" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                        <input id="formPhoto" class="hide" value="" hidden />
                                        <div class="form-group">
                                                <label class="btn btn-sm btn-default btn-default-warning btn-upload" style="width:100%">
                                                <i class="fa fa-upload margin-right"></i> Upload Photo
                                                <input type="file" id="filePhoto" name="userfile" class="uploadPhotoEmp" style="display: none;" accept="image/*">
                                            </label>
                                            <p style="font-size: 12px;color: #ccc;">*) NIK / NIP must be fill before upload photo</p>
                                        </div>
                                    </form>
                                    <div style="text-align: left;padding-top: 10px;border-top: 1px solid #ccc;margin-top: 0px;">
                                        Size : <span id="imgSize">0</span> Kb <br/>
                                        Type : <span id="imgType">-</span>
                                        <input id="formImgType" class="hide" hidden readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="well" style="padding: 10px;text-align: center;margin-bottom: 15px;">
                            <h4>Position Main</h4>

                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Division</label>
                                        <select class="form-control required" id="form_MainDivision"></select>
                                        <small class="text-danger text-message"></small>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Position</label>
                                        <select class="form-control required" id="form_MainPosition"></select>
                                        <small class="text-danger text-message"></small>
                                    </div>
                                </div>
                            </div>
                            <div id = "AddingProdi"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="well" style="padding: 10px;text-align: center;margin-bottom: 15px;">
                            <h4>Position Other 1</h4>

                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Division</label>
                                        <select class="form-control" id="form_Other1Division"></select>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Position</label>
                                        <select class="form-control" id="form_Other1Position"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="well" style="padding: 10px;text-align: center;margin-bottom: 15px;">
                            <h4>Position Other 2</h4>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Division</label>
                                        <select class="form-control" id="form_Other2Division"></select>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Position</label>
                                        <select class="form-control" id="form_Other2Position"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="well" style="padding: 10px;text-align: center;margin-bottom: 15px;">
                            <h4>Position Other 3</h4>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Division</label>
                                        <select class="form-control" id="form_Other3Division"></select>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Position</label>
                                        <select class="form-control" id="form_Other3Position"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <i class="fa fa-id-card"></i> Fill out this field based on Lecturer Data
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Status Lecturer</label>
                                            <select class="form-control" id="formStatusLecturer">
                                                <option>* Not Set</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>NUP</label>
                                            <input class="form-control" id="formNUP" />
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>NIDN</label>
                                            <input class="form-control" id="formNIDN" />
                                        </div>
                                    </div>

                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>NIDK</label>
                                            <input class="form-control" id="formNIDK" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Status Forlap</label>
                                            <select class="form-control" id="formStatusForlap">
                                                <option>* Not Set</option>
                                                <option disabled>------</option>
                                                <option value="0">NUP (Contract)</option>
                                                <option value="1">NIDN (Permanent)</option>
                                                <option value="2">NIDK (Special)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Programme Study</label>
                                            <select class="form-control" id="formProgrammeStudy"></select>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Academic Position</label>
                                            <select class="form-control" id="formLecturerAcademicPositionID">
                                                <option>* Not Set</option>
                                                <option disabled>------</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">                        
                                        <div class="form-group">
                                            <label>Profession</label>
                                            <input class="form-control" id="formProfession">
                                        </div>
                                    </div>
                                    <div class="col-xs-4">                        
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="formSerdos" value="1">
                                                Certified Dosen (Serdos)
                                            </label>
                                        </div>
                                        <input type="text" id="formSerdosNumber" class="form-control" disabled placeholder="Serdos Number">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="panel-footer text-right">
        <button class="btn btn-success" id="btnSave">Save and Next</button>
    </div>
</div>

<script>
    var Prodi = <?php echo json_encode($ProdiArr) ?>;
    var splitBagi = 5;
    var split = parseInt(Prodi.length / splitBagi);
    var sisa = Prodi.length % splitBagi;
    if (sisa > 0) {
          split++;
    }
    $(document).ready(function () {
        $("#formYearBirth").select2({width:'100%'});
        loadSelectOptionMaritalStatus('#formMaritalStatus');
        loadYearOfBirth('#formYearBirth');
        loadMonthBirth('#formMontBirth');

        loadSelectOptionCountry("#CountryID",'');

        // Division
        loadSelectOptionDivision('#form_MainDivision','');
        loadSelectOptionPosition('#form_MainPosition','');

        loadSelectOptionDivision('#form_Other1Division','');
        loadSelectOptionPosition('#form_Other1Position','');

        loadSelectOptionDivision('#form_Other2Division','');
        loadSelectOptionPosition('#form_Other2Position','');

        loadSelectOptionDivision('#form_Other3Division','');
        loadSelectOptionPosition('#form_Other3Position','');

        loadSelectOptionReligi('#formReligion','');
        loadSelectOptionEmployeesStatus2('#formStatusEmployee',"");
        loadSelectOptionLecturerStatus2('#formStatusLecturer',"");

        $('#formProgrammeStudy').append('<option value="">* Not set</option>' +
            '<option disabled>-------------------</option>');
        loadSelectOptionBaseProdi('#formProgrammeStudy','');
        
        var loadFirs = setInterval(function () {
            var Year = $('#formYearBirth').find(':selected').val();
            var Month = $('#formMontBirth').find(':selected').val();
            if(Year!='' && Year!=null && Month!='' && Month!=null){
                loadCountDays(Year,Month,'#formDateBirth','');
                clearInterval(loadFirs);
            }
        },1000);
        FuncEvform_MainDivision();

        loadSelectOptionLevelEducation('#formLevelEducationID','');
        loadSelectOptionLecturerAcademicPosition('#formLecturerAcademicPositionID','');


        $('#CountryID').change(function () {
            var value = $(this).val();
            if($.isNumeric(value)){
                if(value == '001'){
                    $("#form-employee .isrequire").addClass("required").prop("disabled",false);;
                    loadSelectOptionLoc_Province('#ProvinceID','');
                }else{
                    $("#form-employee .isrequire").val("").removeClass("required").prop("disabled",true);
                }
            }
            var ProvinceID = $('#ProvinceID').val();
            $('#RegionID').html('<option value="">Choose one</option>');
            $('#DistrictID').html('<option value="">Choose one</option>');
            if(ProvinceID!='' && ProvinceID!=null){
                loadSelectOptionLoc_Regions(ProvinceID,'#RegionID','');
            }
        });

        
        $('#ProvinceID').change(function () {
            var ProvinceID = $('#ProvinceID').val();
            $('#RegionID').html('<option value="">Choose one</option>');
            $('#DistrictID').html('<option value="">Choose one</option>');
            if(ProvinceID!='' && ProvinceID!=null){
                loadSelectOptionLoc_Regions(ProvinceID,'#RegionID','');
            }
        });


        $('#RegionID').change(function () {
            var RegionID = $('#RegionID').val();
            $('#DistrictID').html('<option value="" disabled selected>-- Select District --</option>');
            if(RegionID!='' && RegionID!=null){
                loadSelectOptionLoc_District(RegionID,'#DistrictID','');
            }
        });

        $("body #form-employee").on("keyup keydown",".number",function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
        });

        $('#formBlood').keyup(function(){
            var value = $(this).val();
            $(this).val(value.toUpperCase());
        });

    });

    // SerDOS
    $('#formSerdos').change(function () {
        if($('#formSerdos').is(':checked')){
            $('#formSerdosNumber').prop('disabled',false);
        } else {
            $('#formSerdosNumber').prop('disabled',true);
            $('#formSerdosNumber').val('');
        }
    });

    function FuncEvform_MainDivision()
    {
            var Opform_MainDivision = function(NIP,Type = 'AdminID'){
                var getRow = 0;
                $("#AddingProdi").empty();
                var InputHtml = '<div class = "row">'+
                                    '<div class = "col-xs-12">'+
                                        '<table class="table" id ="tablechkAddingProdi">'
                                        ;
                $("#AddingProdi").append(InputHtml);                
                for (var i = 0; i < split; i++) {
                    if ((sisa > 0) && ((i+1) == split) ) {
                                        splitBagi = sisa;
                    }
                    $('#tablechkAddingProdi').append('<tr id = "Prodi'+i+'">');
                    for (var k = 0; k < splitBagi; k++) {
                        var selected = (NIP == Prodi[getRow][Type]) ? 'checked' : '';
                        $('#Prodi'+i).append('<td>'+
                                            '<input type="checkbox" class = "chkProdi" name="chkProdi" value = "'+Prodi[getRow].ID+'" '+selected+'>&nbsp'+ Prodi[getRow].NameEng+
                                         '</td>'
                                        );
                        getRow++;
                    }
                    $('#Prodi'+i).append('</tr>');
                }
                $('#AddingProdi').append('</table></div></div>');   
            }

        $("#form_MainDivision").change(function(){
            var getValue = $(this).val();
            var form_MainPosition = $("#form_MainPosition").val();
            if (getValue == 15 || form_MainPosition == 6) { // if selected Admin Prodi
                 Opform_MainDivision('');
            }
            else
            {
                $("#AddingProdi").empty();
            }
        })

        $("#form_MainPosition").change(function(){
            var getValue = $(this).val();
            var form_MainDivision = $("#form_MainDivision").val();
            if (getValue == 6 || form_MainDivision == 15) { // if selected Admin Prodi
                 Opform_MainDivision('');
            }
            else
            {
                $("#AddingProdi").empty();
            }
        })
    }

    $(document).on('change','#formYearBirth,#formMontBirth',function () {
        loadCountDays();
    });

    $('#btnSave').click(function () {
        var itsme = $(this);
        var itsform = itsme.parent().parent();
        itsform.find(".select2-req").each(function(){
            var value = $(this).val();
            if($.isNumeric(value)){
                if($.trim(value) == ''){
                    $(this).addClass("error");
                    $(this).parent().find(".text-message").text("Please fill this field");
                    error = false;
                }else{
                    error = true;
                    $(this).removeClass("error");
                    $(this).parent().find(".text-message").text("");
                }
            }else{
                error = false;  
                $(this).addClass("error");
                $(this).parent().find(".text-message").text("Please fill this field");
            }
        });
        itsform.find(".required").each(function(){
            var value = $(this).val();
            if($.trim(value) == ''){
                $(this).addClass("error");
                $(this).parent().find(".text-message").text("Please fill this field");
                error = false;
            }else{
                error = true;
                $(this).removeClass("error");
                $(this).parent().find(".text-message").text("");
            }
        });
        
        var totalError = itsform.find(".error").length;
        if(error && totalError == 0 ){
            itsme.prop("disabled",true).text("Loading..");
            saveEmployees();        
        }else{
            alert("Please fill out the field.");
        }

    });

    $(document).on('click','#btnSubmitEmployees',function () {
        //saveEmployees();        
    });

    function saveEmployees() {

        var formNIP = $('#formNIP').val();
        var formNUP = $('#formNUP').val();
        var formNIDN = $('#formNIDN').val();
        var formNIDK = $('#formNIDK').val();
        var formKTP = $('#formKTP').val();
        var formReligion = $('#formReligion').val();
        var formName = $('#formName').val();
        var formGender = $('#formGender').val();
        var formTitleAhead = $('#formTitleAhead').val();
        var formTitleBehind = $('#formTitleBehind').val();

        var formPlaceOfBirht = $('#formPlaceOfBirht').val();

        // Date Of Birth
        var formYearBirth = $('#formYearBirth').val();
        var formMontBirth = $('#formMontBirth').val();
        var formDateBirth = $('#formDateBirth').val();

        var formPhone = $('#formPhone').val();
        var formMobile = $('#formMobile').val();
        var formBlood = $('#formBlood').val();

        var formEmailPU = $('#formEmailPU').val();
        var formEmail = $('#formEmail').val();
        var formMaritalStatus = $('#formMaritalStatus').val();
        var formAddress = $('#formAddress').val();
        var formPostcode = $('#formPostcode').val();

        // Position Main
        var form_MainDivision = $('#form_MainDivision').val();
        var form_MainPosition = $('#form_MainPosition').val();

        // Position Other1
        var form_Other1Division = $('#form_Other1Division').val();
        var form_Other1Position = $('#form_Other1Position').val();

        // Position Other2
        var form_Other2Division = $('#form_Other2Division').val();
        var form_Other2Position = $('#form_Other2Position').val();

        // Position Other3
        var form_Other3Division = $('#form_Other3Division').val();
        var form_Other3Position = $('#form_Other3Position').val();

        var formStatusEmployee = $('#formStatusEmployee').val();
        var formProgrammeStudy = $('#formProgrammeStudy').val();
        var Access_Card_Number = $('#CardNumber').val();
        
        var CountryID = $('#CountryID').val();
        var ProvinceID = $('#ProvinceID').val();
        var RegionID = $('#RegionID').val();
        var DistrictID = $('#DistrictID').val();
        var formExtension = $('#formExtension').val();
        var formPassTelp = $('#formPassTelp').val();

        var formSerdos = ($('#formSerdos').is(":checked")) ? '1' : '0';
        var formSerdosNumber = $('#formSerdosNumber').val();
        var SerdosForm = true;
        if(formSerdos=='1' && formSerdosNumber==''){
            SerdosForm = false
        }

        if(formNIP!=null && formNIP!=''
            && formName!='' && formName!=null
            && formYearBirth!='' && formYearBirth!=null
            && formMontBirth!='' && formMontBirth!=null
            && formDateBirth!='' && formDateBirth!=null
            && form_MainDivision!='' && form_MainDivision!=null
            && form_MainPosition!='' && form_MainPosition!=null
            && SerdosForm == true
            && formMaritalStatus !='' && formPostcode !='' && formAddress !='' && formReligion !='' && formGender !=''
        ){
            loading_button('#btnSubmitEmployees');
            $('#btnCloseEmployees').prop('disabled',true);

            var PositionMain = form_MainDivision+'.'+form_MainPosition;

            // check validation Admin Prodi
                var arr_Prodi = [];
                if (form_MainDivision == 15 || form_MainPosition == 6) {
                    $(".chkProdi:checked").each(function(){
                        valuee = this.value;
                        arr_Prodi.push(valuee);
                    })

                    // if (arr_Prodi.length == 0) {
                    //     toastr.error('Please fill Type Admin Prodi','Error');
                    //     return;
                    // }
                }

            var DateOfBirht = formYearBirth+'-'+formMontBirth+'-'+formDateBirth;
            var Password_Old = formDateBirth+''+formMontBirth+''+formYearBirth.substr(2,2);

            var PositionOther1 = (form_Other1Division!='' && form_Other1Position!='' &&
                form_Other1Division!=null && form_Other1Position!=null)
                ? form_Other1Division+'.'+form_Other1Position : '';

            var PositionOther2 = (form_Other2Division!='' && form_Other2Position!='' &&
                form_Other2Division!=null && form_Other2Position!=null)
                ? form_Other2Division+'.'+form_Other2Position : '';

            var PositionOther3 = (form_Other3Division!='' && form_Other3Position!='' &&
                form_Other3Division!=null && form_Other3Position!=null)
                ? form_Other3Division+'.'+form_Other3Position : '';

            var fileType = $('#formImgType').val();
            var fileName = formNIP+'.'+fileType;

            var emailPU = (formEmailPU!='')
                ? formEmailPU+'@podomorouniversity.ac.id'
                : '';

            var formLevelEducationID = $('#formLevelEducationID').val();
            var formLecturerAcademicPositionID = $('#formLecturerAcademicPositionID').val();

            var formStatusLecturer = $('#formStatusLecturer').val();
            var formStatusForlap = $('#formStatusForlap').val();

            var formProfession = $('#formProfession').val();

            var data = {
                arr_Prodi : arr_Prodi,
                action : 'addEmployees',
                formInsert : {
                    ReligionID : formReligion,
                    PositionMain : PositionMain,
                    ProdiID : formProgrammeStudy,

                    LevelEducationID: formLevelEducationID,
                    LecturerAcademicPositionID: formLecturerAcademicPositionID,
                    
                    NIP : formNIP,
                    NUP : formNUP,
                    NIDN : formNIDN,
                    NIDK : formNIDK,
                    KTP : formKTP,
                    Name : formName,
                    TitleAhead : formTitleAhead,
                    TitleBehind : formTitleBehind,
                    Gender : formGender,
                    PlaceOfBirth : formPlaceOfBirht,
                    DateOfBirth : DateOfBirht,
                    Phone : formPhone,
                    HP : formMobile,
                    Blood : formBlood,
                    Email : formEmail,
                    EmailPU : emailPU,
                    MaritalStatus: formMaritalStatus,
                    Password_Old : Password_Old,
                    Address : formAddress,
                    Postcode : formPostcode,
                    Photo : fileName,
                    PositionOther1 : PositionOther1,
                    PositionOther2 : PositionOther2,
                    PositionOther3 : PositionOther3,
                    StatusEmployeeID : formStatusEmployee,
                    StatusLecturerID : formStatusLecturer,
                    StatusForlap : formStatusForlap,
                    Profession: formProfession,
                    Status : '-1',
                    Access_Card_Number : Access_Card_Number,
                    Serdos : formSerdos,
                    SerdosNumber : formSerdosNumber,
                    CountryID   : CountryID,
                    ProvinceID  : ProvinceID,
                    RegionID    : RegionID,
                    DistrictID  : DistrictID,
                    Extension   : formExtension,
                    PassTelp    : formPassTelp
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudEmployees';
            console.log(data);
            $.post(url,{token:token},function (result) {
                console.log(result);
                if(result.status==0 || result.status=='0'){
                    // toastr.error('NIK / NIP is exist','Error');
                    toastr.error(result.msg,'Error');
                    $('#NotificationModal').modal('hide');
                } else {
                    if(fileType!=''){
                        uploadPhoto(fileName);
                    }
                    $('#NotificationModal').modal('hide');
                    var dtmodal = result.arr_callback;
                    // show modal User & Password serta email nya untuk di print
                    var html  = '<div class = "row">'+
                                    '<div class = "col-md-12">'+
                                        '<table class = "table">'+
                                            '<tr>'+
                                                '<td>'+
                                                    'Username PC'+
                                                '</td>'+
                                                '<td>'+
                                                    ':'+
                                                '</td>'+
                                                '<td>'+
                                                    '<div class = "UsernamePC" dt = "'+dtmodal.UsernamePC+'">'+dtmodal.UsernamePC+
                                                    '</div>'+
                                                '</td>'+
                                            '</tr>'+ 
                                            '<tr>'+
                                                '<td>'+
                                                    'Username Aplikasi PCAM'+
                                                '</td>'+
                                                '<td>'+
                                                    ':'+
                                                '</td>'+
                                                '<td>'+
                                                    '<div class = "UsernamePCam" dt = "'+dtmodal.UsernamePCam+'">'+dtmodal.UsernamePCam+
                                                    '</div>'+
                                                '</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                                '<td>'+
                                                    'Password'+
                                                '</td>'+
                                                '<td>'+
                                                    ':'+
                                                '</td>'+
                                                '<td>'+
                                                    '<div class = "PasswordFill" dt = "'+dtmodal.Password+'">'+dtmodal.Password+
                                                    '</div>'+
                                                '</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                                '<td>'+
                                                    'Email PU'+
                                                '</td>'+
                                                '<td>'+
                                                    ':'+
                                                '</td>'+
                                                '<td>'+
                                                    '<div class = "EmailPUFill" dt = "'+dtmodal.EmailPU+'">'+dtmodal.EmailPU+
                                                    '</div>'+
                                                '</td>'+
                                            '</tr>'+
                                        '</table>'+
                                    '</div>'+
                                '</div>';                           
                    var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
                                 '<button type="button" id="ModalbtnSaveForm" class="btn btn-success">Print</button>'+
                                 /*ADDED BY FEBRI @ FEB 2020*/
                                 '<a href="'+base_url_js+'human-resources/employees/employees-additional-info/'+dtmodal.UsernamePC+'" class="btn btn-info">Go to additional form</a>';
                                 /*END ADDED BY FEBRI @ FEB 2020*/
                    
                    $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Akses'+'</h4>');
                    $('#GlobalModalLarge .modal-body').html(html);
                    $('#GlobalModalLarge .modal-footer').html(footer);
                    $('#GlobalModalLarge').modal({
                        'show' : true,
                        'backdrop' : 'static'
                    });            

                    toastr.success('Employees Saved','Success');

                    setTimeout(function () {
                        $(location).attr("href",base_url_js+"human-resources/employees/career-level/"+formNIP+"?next=Y");
                    },2000);

                    //$('input').val('');

                }

            });

        } else {
            var msg = '<ol>';
            if(formName==''){
                msg += '<li>Name is required</li>';
            } if (formYearBirth=='' || formMontBirth=='' || formDateBirth==''){
                msg += '<li>Birthday is required</li>';
            } if(form_MainDivision==''){
                msg += '<li>Main Division is required</li>';
            } if (form_MainPosition==''){
                msg += '<li>Main Position is required</li>';
            } if(SerdosForm==false){
                msg += '<li>Serdos Number are required</li>';
            }
            /*ADDED BY FEBRI @ FEB 2020*/
            if(formNIP == ''){
                msg += '<li>NIP is required</li>';
            }
            if(formKTP == ''){
                msg += '<li>No KTP is required</li>';
            }
            if(formReligion == ''){
                msg += '<li>Religion is required</li>';
            }
            if(formGender == ''){
                msg += '<li>Gender is required</li>';
            }
            if(formPlaceOfBirht == ''){
                msg += '<li>Place of birth is required</li>';
            }
            if(formAddress == ''){
                msg += '<li>Address is required</li>';
            }
            if(formPostcode == ''){
                msg += '<li>Postal code is required</li>';
            }
            if(formMaritalStatus == ''){
                msg += '<li>Marital Status is required</li>';
            } 
            msg += "</ol>";
            /*END ADDED BY FEBRI @ FEB 2020*/
            toastr.error(msg,'Error');
        }


    }


    $(document).off('click', '#ModalbtnSaveForm').on('click', '#ModalbtnSaveForm',function(e) {
        var UsernamePC = $('.UsernamePC').attr('dt'); 
        var UsernamePCam = $('.UsernamePCam').attr('dt');
        var PasswordFill = $('.PasswordFill').attr('dt');
        var EmailPUFill = $('.EmailPUFill').attr('dt');

        var url = base_url_js+'save2pdf/print_akses_karyawan';
        data = {
          UsernamePC : UsernamePC,
          UsernamePCam : UsernamePCam,
          PasswordFill : PasswordFill,
          EmailPUFill : EmailPUFill,
        }
        var token = jwt_encode(data,"UAP)(*");
        FormSubmitAuto(url, 'POST', [
            { name: 'token', value: token },
        ]);

    })

    $(document).on('change','.uploadPhotoEmp',function () {
        // uploadPhoto();
        viewImageBeforeUpload(this,'#imgThumbnail','#imgSize','#imgType','','#formImgType');
    });



    function uploadPhoto(fileName) {

        if(fileName!='' && fileName!=null){

            var formData = new FormData( $("#fmPhoto")[0]);
            var url = base_url_js+'human-resources/upload_photo?fileName='+fileName;

            $.ajax({
                url : url,  // Controller URL
                type : 'POST',
                data : formData,
                async : false,
                cache : false,
                contentType : false,
                processData : false,
                success : function(data) {

                    var jsonData = JSON.parse(data);

                    // if(typeof jsonData.success=='undefined'){
                    //     toastr.error(jsonData.error,'Error');
                    //     // alert(jsonData.error);
                    // }
                    // else {
                    //     toastr.success('File Saved','Success!!');
                    // }

                }
            });

        } else {
            toastr.error('NIK / NIK is empty','Error');
        }

    }
</script>
<!-- END OLD FORM -->