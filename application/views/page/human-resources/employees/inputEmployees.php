
<div class="panel panel-primary">
    <div class="panel-heading" style="border-radius: 0px;">
        <h4 class="header">Add Employees</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;">

        <div class="row">
            <div class="col-md-6" style="border-right: 1px solid #afafafb5;">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>NIK / NIP</label>
                            <input class="form-control" id="formNIP">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>NIDN</label>
                            <input class="form-control" id="formNIDN">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-8">
                        <div class="form-group">
                            <label>No KTP</label>
                            <input class="form-control" id="formKTP" />
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>Religion</label>
                            <select class="form-control" id="formReligion"></select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-9">
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" id="formName"/>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Gender</label>
                            <select class="form-control" id="formGender">
                                <option value="L">Male</option>
                                <option value="P">Female</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Title Ahead</label>
                            <input class="form-control" id="formTitleAhead" />
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Title Behind</label>
                            <input class="form-control" id="formTitleBehind" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Place Of Birth</label>
                            <input class="form-control" id="formPlaceOfBirht" />
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">

                            <div class="thumbnail" style="padding: 10px;text-align: center;">
                                <h4>Date Of Birth</h4>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Year</label>
                                            <select class="form-control" id="formYearBirth"></select>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Mont</label>
                                            <select class="form-control" id="formMontBirth"></select>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <select class="form-control" id="formDateBirth"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Phone</label>
                            <input class="form-control" id="formPhone" />
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Mobile</label>
                            <input class="form-control" id="formMobile" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Email PU</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="formEmailPU">
                                    <span class="input-group-addon">@podomorouniversity.ac.id</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Email Other</label>
                            <input class="form-control" id="formEmail" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Address</label>
                            <textarea rows="3" class="form-control" id="formAddress"></textarea>
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
                                        <select class="form-control" id="form_MainDivision"></select>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Position</label>
                                        <select class="form-control" id="form_MainPosition"></select>
                                    </div>
                                </div>
                            </div>
                            <div id = "AddingProdi">
                                
                            </div>
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
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Status Employees</label>
                            <select class="form-control" id="formStatusEmployee"></select>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Programme Study</label>
                            <select class="form-control" id="formProgrammeStudy"></select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Level of Education</label>
                            <select class="form-control" id="formLevelEducationID"></select>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Lecturer Academic Position</label>
                            <select class="form-control" id="formLecturerAcademicPositionID">
                                <option></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-3" style="text-align: center;border-right: 1px solid #CCCCCC;">
                        <hr/>
                        <img id="imgThumbnail" src="<?php echo base_url('images/icon/userfalse.png'); ?>" style="max-width: 100px;width: 100%;">
                        <div style="text-align: left;padding-top: 10px;border-top: 1px solid #ccc;margin-top: 10px;">
                            Size : <span id="imgSize">0</span> Kb <br/>
                            Type : <span id="imgType">-</span>
                            <input id="formImgType" class="hide" hidden readonly />
                        </div>
                    </div>
                    <div class="col-xs-9">
                        <hr/>
                        <div class="form-group">
                            <label>Photo</label>
                            <form id="fmPhoto" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                <input id="formPhoto" class="hide" value="" hidden />
                                <div class="form-group"><label class="btn btn-sm btn-default btn-default-warning btn-upload">
                                        <i class="fa fa-upload margin-right"></i> Upload Photo
                                        <input type="file" id="filePhoto" name="userfile" class="uploadPhotoEmp"
                                               style="display: none;" accept="image/*">
                                    </label>
                                    <p style="font-size: 12px;color: #ccc;">*) NIK / NIP must be fill before upload photo</p>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-12" style="text-align: right;">
                <hr/>
                <button class="btn btn-success" id="btnSave">Save</button>
            </div>
        </div>

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
        loadYearOfBirth('#formYearBirth');
        loadMonthBirth('#formMontBirth');

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
        loadSelectOptionEmployeesStatus('#formStatusEmployee','');

        $('#formProgrammeStudy').append('<option value="">-- Non Academic (Employee) --</option>' +
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
        var NIP = $('#formNIP').val();
        $('#NotificationModal .modal-body').html('<div style="text-align: center;">' +
            'Pastikan NIK / NIP yang dimasukan tidak salah. <br/>' +
            'NIK / NIP : <b>'+NIP+'</b> ' +
            '<hr/>' +
            '<button type="button" class="btn btn-default" id="btnCloseEmployees" data-dismiss="modal">Close</button> | ' +
            '<button type="button" class="btn btn-success" id="btnSubmitEmployees">Submit</button>' +
            '</div> ');

        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });
    });

    $(document).on('click','#btnSubmitEmployees',function () {
        saveEmployees();
    });

    function saveEmployees() {

        var formNIP = $('#formNIP').val();
        var formNIDN = $('#formNIDN').val();
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

        var formEmailPU = $('#formEmailPU').val();
        var formEmail = $('#formEmail').val();
        var formAddress = $('#formAddress').val();

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

        if(formNIP!=null && formNIP!=''
            && formName!='' && formName!=null
            && formYearBirth!='' && formYearBirth!=null
            && formMontBirth!='' && formMontBirth!=null
            && formDateBirth!='' && formDateBirth!=null
            && form_MainDivision!='' && form_MainDivision!=null
            && form_MainPosition!='' && form_MainPosition!=null
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


            var data = {
                arr_Prodi : arr_Prodi,
                action : 'addEmployees',
                formInsert : {
                    ReligionID : formReligion,
                    PositionMain : PositionMain,
                    ProdiID : formProgrammeStudy,

                    LevelEducationID: formLevelEducationID,
                    LecturerAcademicPositionID: formLecturerAcademicPositionID,
                    // CityID : formProgrammeStudy,
                    // ProvinceID : formProgrammeStudy,

                    NIP : formNIP,
                    NIDN : formNIDN,
                    KTP : formKTP,
                    Name : formName,
                    TitleAhead : formTitleAhead,
                    TitleBehind : formTitleBehind,
                    Gender : formGender,
                    PlaceOfBirth : formPlaceOfBirht,
                    DateOfBirth : DateOfBirht,
                    Phone : formPhone,
                    HP : formMobile,
                    Email : formEmail,
                    EmailPU : emailPU,
                    Password_Old : Password_Old,
                    Address : formAddress,
                    Photo : fileName,
                    PositionOther1 : PositionOther1,
                    PositionOther2 : PositionOther2,
                    PositionOther3 : PositionOther3,
                    StatusEmployeeID : formStatusEmployee,
                    Status : '-1'
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudEmployees';
            $.post(url,{token:token},function (result) {

                if(result==0 || result=='0'){
                    toastr.error('NIK / NIP is exist','Error');
                } else {
                    if(fileType!=''){
                        uploadPhoto(fileName);
                    }
                    toastr.success('Employees Saved','Success');

                }

                setTimeout(function () {
                    $('#NotificationModal').modal('hide');
                    window.location.href = '';
                },1000);


            });
        }


    }


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