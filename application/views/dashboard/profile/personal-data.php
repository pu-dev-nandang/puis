<div class="personal-data">
    <form id="form-personal-data" action="<?=base_url('profile/save-changes/'.$NIP)?>" method="post" enctype="multipart/form-data" style="margin:0px">
        <input class="form-control" name="NIP" type="hidden" value="<?=$NIP?>" />
        <input class="form-control" name="action" type="hidden" value="profile" />
        <div class="panel panel-primary" id="form-employee" style="border-radius:0px 4px 4px 4px">
            <div class="panel-heading" style="border-radius: 0px;">
                <h4 class="panel-title"><i class="fa fa-edit"></i> Please, Fill out this form with correctly data</h4>
            </div>
            <div class="panel-body" style="min-height: 100px;">
            
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><i class="fa fa-id-card"></i> Fill out this field based on Identity Card (KTP)
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Number of ID Card </label>
                                            <input class="form-control number required profile-KTP" name="KTP" id="formKTP" autocomplete="off" />
                                            <small class="text-danger text-message"></small>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Full Name </label>
                                            <input class="form-control required profile-Name" name="Name" id="formName" autocomplete="off" disabled/>
                                            <small class="text-danger text-message"></small>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select class="form-control required profile-Gender" name="Gender" id="formGender" >
                                                <option value="">Choose one</option>
                                                <option value="L">Male</option>
                                                <option value="P">Female</option>
                                            </select>
                                            <small class="text-danger text-message"></small>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Religion </label>
                                            <select class="form-control required profile-ReligionID" name="ReligionID" id="formReligion">
                                                <option value="">Choose one</option>
                                            </select>
                                            <small class="text-danger text-message"></small>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <label>Marital Status</label>
                                        <select class="form-control profile-MaritalStatus" id="formMaritalStatus" <?=(!empty($employee->MaritalStatus) ? 'disabled':'')?> >
                                            <option value="">Choose One</option>
                                        </select>
                                        <small class="text-danger text-message"></small>
                                    </div>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label>Blood</label>
                                            <input class="form-control required profile-Blood" name="Blood" id="formBlood" maxlength="3" />
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
                                            <input class="form-control required profile-PlaceOfBirth" name="PlaceOfBirth" id="formPlaceOfBirht" />
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
                                                    <select class="select2-req" name="BYear" id="formYearBirth" disabled>
                                                        <option>Choose one</option>
                                                    </select>
                                                    <small class="text-danger text-message"></small>
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <div class="form-group">
                                                    <label>Month</label>
                                                    <select class="form-control required" name="BMonth" id="formMontBirth" disabled>
                                                        <option value="">Choose one</option>
                                                    </select>
                                                    <small class="text-danger text-message"></small>
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <select class="form-control required" name="BDay" id="formDateBirth" disabled>
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
                                            <textarea rows="3" class="form-control required profile-Address" name="Address" id="formAddress"></textarea>
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
                                            <input type="text" class="form-control required number profile-Postcode" name="Postcode" id="formPostcode" maxlength="5" autocomplete="off">
                                            <small class="text-danger text-message"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                                
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-id-card"></i> General Data
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Access Card Number</label>
                                                            <input type="text" class="form-control number required profile-Access_Card_Number" name="Access_Card_Number" id="CardNumber">
                                                            <small class="text-danger text-message"></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Email Other</label>
                                                            <input class="form-control required profile-Email" name="Email" id="formEmail" autocomplete="off" />
                                                            <small class="text-danger text-message"></small>
                                                        </div>
                                                    </div>  
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Phone</label>
                                                            <input class="form-control number profile-Phone" name="Phone" id="formPhone"  autocomplete="off" />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Mobile</label>
                                                            <input class="form-control number required profile-HP" name="HP" id="formMobile" autocomplete="off" />
                                                            <small class="text-danger text-message"></small>
                                                        </div>
                                                    </div>                                
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="panel panel-default">
                                                            <div class="panel-body">
                                                                <p><label>Current Address</label>
                                                                <label class="pull-right"><input type="checkbox" class="same-data"> same data as IDCard</label></p>
                                                                <div class="form-group">
                                                                    <label>Address</label>
                                                                    <textarea class="samedata form-control required profile-CurrAddress" id="CurrAddress" name="CurrAddress"></textarea>
                                                                    <small class="text-danger text-message"></small>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group">
                                                                            <label>Post Code</label>
                                                                            <input type="text" name="CurrPostCode" id="CurrPostCode" class="samedata form-control required profile-CurrPostCode number" maxlength="5">
                                                                            <small class="text-danger text-message"></small>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label>Phone</label>
                                                                            <input type="text" name="CurrPhone" id="CurrPhone" class="samedata form-control profile-CurrPhone number" maxlength="13">
                                                                            <small class="text-danger text-message"></small>
                                                                        </div>
                                                                    </div>
                                                                </div>
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
                                                    <img class="img-thumbnail" id="imgThumbnail" src="<?php echo base_url('images/icon/userfalse.png'); ?>" style="max-width: 100px;width: 100%;">
                                                    </center>
                                                    <div class="form-group">
                                                        <label class="btn btn-sm btn-default btn-default-warning btn-upload" style="width:100%">
                                                            <i class="fa fa-upload margin-right"></i> Upload Photo
                                                            <input type="file" id="filePhoto" name="userfile" class="uploadPhotoEmp" style="display: none;" accept="image/png, image/jpeg">
                                                        </label>                                                                
                                                    </div>
                                                    <div class="alert alert-info">
                                                        Dimension of this picture must 4x6 cm (472x709 pixels or 1,5x2,2 inches) and Maximum size 2MB
                                                    </div>
                                                </div>
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
                <button class="btn btn-success" id="btnSave" type="button">Save changes</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $formParent = $("#form-personal-data");
        var myData = fetchAdditionalData("<?=$employee->NIP?>");
        if(!jQuery.isEmptyObject(myData)){
            $.each(myData,function(key,value){
                $formParent.find(".profile-"+key).val(value);
            });
            var DateOfBirth = myData.DateOfBirth;
            var exDOB = DateOfBirth.split('-');
            loadYearOfBirth('#formYearBirth',exDOB[0].trim());
            $("#formYearBirth").select2({width:'100%'});
            loadMonthBirth('#formMontBirth',exDOB[1].trim());
            loadCountDays(exDOB[0],exDOB[1],'#formDateBirth',exDOB[2]);

            loadSelectOptionMaritalStatus('#formMaritalStatus',myData.MaritalStatus);
            loadSelectOptionReligi('#formReligion',myData.ReligionID);
            loadSelectOptionCountry("#CountryID",(jQuery.isEmptyObject(myData.CountryID) ? '001':myData.CountryID ) );
            loadSelectOptionLoc_Province('#ProvinceID',myData.ProvinceID);
            loadSelectOptionLoc_Regions(myData.ProvinceID,'#RegionID',myData.RegionID);
            loadSelectOptionLoc_District(myData.RegionID,'#DistrictID',myData.DistrictID);
            var Country = $formParent.find("#CountryID").val();
            if(jQuery.isEmptyObject(myData.CountryID)){
                $formParent.find(".isrequire").addClass("required").prop("disabled",false);;
            }else{
                if(myData.CountryID == "001"){
                    $formParent.find(".isrequire").addClass("required").prop("disabled",false);;                    
                }else{
                    $formParent.find(".isrequire").val("").removeClass("required").prop("disabled",true);                    
                }
            }

            loadSelectOptionLevelEducation('#formLevelEducationID',myData.LevelEducationID);
        }


        $formParent.on("click","#btnSave",function(){
            var itsme = $(this);
            var itsform = itsme.parent().parent().parent();
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
                loading_modal_show();
                $formParent[0].submit();
            }else{
                alert("Please fill out the field.");
            }
        });

        $("#filePhoto").change(function(){
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                  $('#imgThumbnail').attr('src', e.target.result);
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });

        $('#CountryID').change(function () {
            var value = $(this).val();
            if($.isNumeric(value)){
                if(value == '001'){
                    $formParent.find(".isrequire").addClass("required").prop("disabled",false);;
                    loadSelectOptionLoc_Province('#ProvinceID','');
                }else{
                    $formParent.find(".isrequire").val("").removeClass("required").prop("disabled",true);
                    $formParent.find(".isrequire").next("small").text("");
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

        $formParent.on("change",".same-data",function(){
            if($(this).is(':checked')){
                var Address = $formParent.find("#formAddress").val();
                var PostCode = $formParent.find("#formPostcode").val();
                var Phone = $formParent.find("#formPhone").val();

                $formParent.find("#CurrAddress").val(Address);
                $formParent.find("#CurrPostCode").val(PostCode);
                $formParent.find("#CurrPhone").val(Phone);
            }else{
                $formParent.find(".samedata").val("");
            }
        });
            
    });
</script>