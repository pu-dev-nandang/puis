
<div class="panel panel-primary">
    <div class="panel-heading" style="border-radius: 0px;">
        <h4 class="header">Edit Employees</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;">

        <div class="row">
            <div class="col-md-6" style="border-right: 1px solid #afafafb5;">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>NIK / NIP</label>
                            <input class="form-control" disabled id="formNIP" value="<?php echo $arrEmp['NIP']; ?>">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>NIDN</label>
                            <input class="form-control" id="formNIDN" value="<?php echo $arrEmp['NIDN']; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-8">
                        <div class="form-group">
                            <label>No KTP</label>
                            <input class="form-control" id="formKTP" value="<?php echo $arrEmp['KTP']; ?>" />
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
                            <input class="form-control" id="formName" value="<?php echo $arrEmp['Name']; ?>" />
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
                            <input class="form-control" id="formTitleAhead" value="<?php echo $arrEmp['TitleAhead']; ?>" />
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Title Behind</label>
                            <input class="form-control" id="formTitleBehind" value="<?php echo $arrEmp['TitleBehind']; ?>" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Place Of Birth</label>
                            <input class="form-control" id="formPlaceOfBirht" value="<?php echo $arrEmp['PlaceOfBirth']; ?>" />
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
                            <input class="form-control" id="formPhone" value="<?php echo $arrEmp['Phone']; ?>" />
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Mobile</label>
                            <input class="form-control" id="formMobile" value="<?php echo $arrEmp['HP']; ?>" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Email PU</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <?php $EmailPU = ($arrEmp['EmailPU']!='') ? explode('@',$arrEmp['EmailPU'])[0] : ''; ?>
                                    <input type="text" class="form-control" id="formEmailPU" value="<?php echo $EmailPU; ?>" />
                                    <span class="input-group-addon">@podomorouniversity.ac.id</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Email Other</label>
                            <input class="form-control" id="formEmail" value="<?php echo $arrEmp['Email']; ?>" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Address</label>
                            <textarea rows="3" class="form-control" id="formAddress"><?php echo $arrEmp['Address']; ?></textarea>
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
                    <div class="col-xs-12">
                        <label>Ijazah</label>

                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-3" style="text-align: center;border-right: 1px solid #CCCCCC;">
                        <hr/>
                        <?php $imgPr = ($arrEmp['Photo']!='' && $arrEmp['Photo']!=null &&
                            file_exists('./uploads/employees/'.$arrEmp['Photo']))
                            ? base_url('uploads/employees/'.$arrEmp['Photo'])
                            : base_url('images/icon/userfalse.png'); ?>
                        <img id="imgThumbnail" src="<?php echo $imgPr; ?>" style="max-width: 100px;width: 100%;">
                        <div style="text-align: left;padding-top: 10px;border-top: 1px solid #ccc;margin-top: 10px;">
                            Size : <span id="imgSize">-</span> Kb <br/>
                            Type : <span id="imgType">-</span>
                            <input id="formImgType" class="hide" hidden readonly />
                        </div>
                    </div>
                    <div class="col-xs-9">
                        <hr/>
                        <label>Photo</label>
                        <div class="form-group">

                            <form id="fmPhoto" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                <input id="formPhoto" class="hide" value="" hidden />
                                <label class="btn btn-sm btn-default btn-default-warning btn-upload">
                                    <i class="fa fa-upload margin-right"></i> Upload Photo
                                    <input type="file" id="filePhoto" name="userfile" class="uploadPhotoEmp"
                                           style="display: none;" accept="image/*">
                                </label>
                                <p style="font-size: 12px;color: #ccc;">*) NIK / NIP must be fill before upload photo</p>
                            </form>
                        </div>

                        <label>Ijazah (Maksimum Size 8 Mb)</label>
                        <form id="fmIjazah" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                            <input id="formIjazahExt" class="hide" value="" />
                            <div class="form-group">
                                <label class="btn btn-sm btn-default btn-default-primary btn-upload">
                                    <i class="fa fa-upload margin-right"></i> Upload Ijazah (.pdf)
                                    <input type="file" id="fileIjazah" name="userfile" class="uploadIjazah"
                                           style="display: none;" accept="application/pdf">
                                </label>
                            </div>
                        </form>
                    </div>

                </div>

            </div>

        </div>

        <div class="row">
            <div class="col-md-12"><hr/></div>
            <div class="col-md-6">
                <div class="hide">
                    <?php
                    $idBtnDelPermananet = ($btnDelPermanent['Status']==0) ? 'id="btnDelPeranentCuy"' : '';
                    $btnDis = ($btnDelPermanent['Status']==0) ? '' : 'disabled';

                    ?>
                    <div>

                        <button class="btn btn-warning" <?php echo $btnDis; ?> <?php echo $idBtnDelPermananet; ?>>Permanent Delete</button>
                        <p style="margin-top: 5px;color: blue;">*) <?php echo $btnDelPermanent['Msg']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6" style="text-align: right;">


                <button class="btn btn-success" id="btnUpdate">Save</button> |
                <button class="btn btn-danger hide" disabled hidden id="btnDelete">Delete</button>
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
        loadSelectOptionReligi('#formReligion',<?= $arrEmp['ReligionID']; ?>);
        $('#formGender').val("<?= $arrEmp['Gender']; ?>");

        // Load Year
        var DateOfBirth = "<?= $arrEmp['DateOfBirth']; ?>";
        var exDOB = DateOfBirth.split('-');
        loadYearOfBirth('#formYearBirth',exDOB[0].trim());
        loadMonthBirth('#formMontBirth',exDOB[1].trim());

        loadCountDays(exDOB[0],exDOB[1],'#formDateBirth',exDOB[2]);

        // Division Main
        var MainPosition = "<?= $arrEmp['PositionMain']; ?>";
        var MainDivision_ID = '';
        var MainPosition_ID = '';
        if(MainPosition!='' && MainPosition!=null){
            var expMain = MainPosition.split('.');
            MainDivision_ID = expMain[0];
            MainPosition_ID = expMain[1];
        }
        loadSelectOptionDivision('#form_MainDivision',MainDivision_ID);
        loadSelectOptionPosition('#form_MainPosition',MainPosition_ID);


        $('#form_Other1Division,#form_Other1Position,' +
            '#form_Other2Division,#form_Other2Position,' +
            '#form_Other3Division,#form_Other3Position').append('<option>-- Empty --</option>');
        $('#form_Other1Division,#form_Other1Position,' +
            '#form_Other2Division,#form_Other2Position,' +
            '#form_Other3Division,#form_Other3Position').append('<option disabled>-----------</option>');

        // Other 1
        var Other1Position = "<?php echo $arrEmp['PositionOther1']; ?>";
        var Other1Division_ID = '';
        var Other1Position_ID = '';
        if(Other1Position!='' && Other1Position!=null){
            var expOther1 = Other1Position.split('.');
            Other1Division_ID = expOther1[0];
            Other1Position_ID = expOther1[1];
        }

        loadSelectOptionDivision('#form_Other1Division',Other1Division_ID);
        loadSelectOptionPosition('#form_Other1Position',Other1Position_ID);

        // Other 2
        var Other2Position = "<?php echo $arrEmp['PositionOther2']; ?>";
        var Other2Division_ID = '';
        var Other2Position_ID = '';
        if(Other2Position!='' && Other2Position!=null){
            var expOther2 = Other2Position.split('.');
            Other2Division_ID = expOther2[0];
            Other2Position_ID = expOther2[1];
        }

        loadSelectOptionDivision('#form_Other2Division',Other2Division_ID);
        loadSelectOptionPosition('#form_Other2Position',Other2Position_ID);

        // Other 3
        var Other3Position = "<?php echo $arrEmp['PositionOther3']; ?>";
        var Other3Division_ID = '';
        var Other3Position_ID = '';
        if(Other3Position!='' && Other3Position!=null){
            var expOther3 = Other3Position.split('.');
            Other3Division_ID = expOther3[0];
            Other3Position_ID = expOther3[1];
        }

        loadSelectOptionDivision('#form_Other3Division',Other3Division_ID);
        loadSelectOptionPosition('#form_Other3Position',Other3Position_ID);

        loadSelectOptionEmployeesStatus('#formStatusEmployee',"<?php echo $arrEmp['StatusEmployeeID']; ?>");

        var ProdiID = "<?php echo $arrEmp['ProdiID']; ?>";
        // if(ProdiID=='' )
        $('#formProgrammeStudy').append('<option selected>-- Select Programme Study --</option>');
        $('#formProgrammeStudy').append('<option disabled>-------------------</option>');
        loadSelectOptionBaseProdi('#formProgrammeStudy',ProdiID);
        FuncEvform_MainDivision();

        loadSelectOptionLevelEducation('#formLevelEducationID',<?= $arrEmp['LevelEducationID']; ?>);
        loadSelectOptionLecturerAcademicPosition('#formLecturerAcademicPositionID',<?= $arrEmp['LecturerAcademicPositionID']; ?>);

    });

    function FuncEvform_MainDivision()
    {
        // chk NIP is exist in ProgramStudy
            var Find = false;
            var NIP = $("#formNIP").val();
            var ProdiGet = '';
            for (var i = 0; i < Prodi.length; i++) {
                var AdminID = Prodi[i].AdminID;
                if (AdminID == NIP) {
                    ProdiGet = Prodi[i].ID; 
                    Find = true;
                }
            }

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

            var waitForEl = function(selector, callback) {
              if (jQuery(selector).length) {
                callback();
              } else {
                setTimeout(function() {
                  waitForEl(selector, callback);
                }, 100);
              }
            };

            waitForEl("#form_MainPosition option[value='1']", function() {
              waitForEl("#form_MainDivision option[value='1']", function() {
                var form_MainPosition = $("#form_MainPosition").val();
                var form_MainDivision = $("#form_MainDivision").val();
                // console.log(form_MainDivision)
                if (form_MainPosition == 6 || form_MainDivision == 15) {
                    if (form_MainDivision == 15) {
                        Opform_MainDivision(NIP); 
                    }
                    else
                    {
                      Opform_MainDivision(NIP,'KaprodiID'); 
                    }
                }
              });  
            });

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


    // Aksi Delete =====================
    $('#btnDelete').click(function () {
        var formNIP = $('#formNIP').val();
        $('#NotificationModal .modal-body').html('<div style="text-align: center;">' +
            'NIK / NIP : <b>'+formNIP+'</b>, Do you want to <b style="color:red;">delete</b>?' +
            '<hr/>' +
            '<button type="button" class="btn btn-default" id="btnDelNo" data-dismiss="modal">No</button> | ' +
            '<button type="button" class="btn btn-danger" id="btnDelYes">Yes</button>' +
            '</div> ');

        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });

    });

    $(document).on('click','#btnDelYes',function () {
        loading_buttonSm('#btnDelYes');
        $('#btnDelNo').prop('disabled',true);
        var formNIP = $('#formNIP').val();
        var token = jwt_encode({action:'deleteEmployees',NIP:formNIP}, 'UAP)(*');
        var url = base_url_js + 'api/__crudEmployees';
        $.post(url,{token:token},function (result) {
            setTimeout(function () {
                $('#NotificationModal').modal('hide');
                window.location.href= base_url_js+'human-resources/employees';
            },500);
        });
    });
    // ============================

    // Aksi Delete Permanen =========
    $('#btnDelPeranentCuy').click(function () {
        var formNIP = $('#formNIP').val();
        $('#NotificationModal .modal-body').html('<div style="text-align: center;">' +
            'NIK / NIP : <b>'+formNIP+'</b>, Do you want to <b style="color:red;">Permanent delete</b>?' +
            '<hr/>' +
            '<button type="button" class="btn btn-default" id="btnPDelNo" data-dismiss="modal">No</button> | ' +
            '<button type="button" class="btn btn-danger" id="btnPDelYes">Yes</button>' +
            '</div> ');

        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });

    });

    $(document).on('click','#btnPDelYes',function () {
        loading_buttonSm('#btnPDelYes');
        $('#btnPDelNo').prop('disabled',true);
        var formNIP = $('#formNIP').val();
        var token = jwt_encode({action:'deletePermanantEmployees',NIP:formNIP}, 'UAP)(*');
        var url = base_url_js + 'api/__crudEmployees';
        $.post(url,{token:token},function (result) {
            setTimeout(function () {
                $('#NotificationModal').modal('hide');
                window.location.href= base_url_js+'human-resources/employees';
            },500);
        });
    });

    // =====================================

    // Aksi Update Employee ==============
    $(document).on('change','.uploadPhotoEmp',function () {
        // uploadPhoto();
        viewImageBeforeUpload(this,'#imgThumbnail','#imgSize','#imgType','','#formImgType');
    });

    // Aksi View before upload ijazah
    $(document).on('change','.uploadIjazah',function () {

        var input = this;

        if (input.files && input.files[0]) {
            var sz = parseFloat(input.files[0].size) / 1000000; // ukuran MB
            var ext = input.files[0].type.split('/')[1];

            var ds = true;
            if(Math.floor(sz)<=8){
                ds = false;
                $('#formIjazahExt').val(ext);
            } else {
                alert('Maksimum size 8 Mb');
            }

            $('#btnUpdate').prop('disabled',ds);
        }
    });

    $('#btnUpdate').click(function () {
        updateEmployees();
    });

    function updateEmployees() {
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
        ) {
            loading_button('#btnUpdate');
            $('.form-control').prop('disabled', true);

            var PositionMain = form_MainDivision + '.' + form_MainPosition;

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

            var DateOfBirht = formYearBirth + '-' + formMontBirth + '-' + formDateBirth;
            var Password_Old = formDateBirth + '' + formMontBirth + '' + formYearBirth.substr(2, 2);

            var PositionOther1 = (form_Other1Division != '' && form_Other1Position != '' &&
                form_Other1Division != null && form_Other1Position != null)
                ? form_Other1Division + '.' + form_Other1Position : '';

            var PositionOther2 = (form_Other2Division != '' && form_Other2Position != '' &&
                form_Other2Division != null && form_Other2Position != null)
                ? form_Other2Division + '.' + form_Other2Position : '';

            var PositionOther3 = (form_Other3Division != '' && form_Other3Position != '' &&
                form_Other3Division != null && form_Other3Position != null)
                ? form_Other3Division + '.' + form_Other3Position : '';

            var fileType = $('#formImgType').val();


            var emailPU = (formEmailPU != '')
                ? formEmailPU + '@podomorouniversity.ac.id'
                : '';

            var DeletePhoto = (fileType!='') ? 1 : 0;
            var LastPhoto = "<?php echo $arrEmp['Photo'];?>";

            var fileName = (fileType!='') ? formNIP + '.' + fileType : LastPhoto ;

            var formIjazahExt = $('#formIjazahExt').val();
            var fileNameIjazah = (formIjazahExt!='') ? 'IJAZAH_'+formNIP+'.'+formIjazahExt : '';


            var formLevelEducationID = $('#formLevelEducationID').val();
            var formLecturerAcademicPositionID = $('#formLecturerAcademicPositionID').val();

            var data = {
                arr_Prodi : arr_Prodi,
                action : 'UpdateEmployees',
                NIP : formNIP,
                DeletePhoto : DeletePhoto,
                LastPhoto : LastPhoto,
                formUpdate: {
                    ReligionID: formReligion,
                    PositionMain: PositionMain,
                    ProdiID: formProgrammeStudy,

                    LevelEducationID: formLevelEducationID,
                    LecturerAcademicPositionID: formLecturerAcademicPositionID,

                    // CityID : formProgrammeStudy,
                    // ProvinceID : formProgrammeStudy,

                    NIDN: formNIDN,
                    KTP: formKTP,
                    Name: formName,
                    TitleAhead: formTitleAhead,
                    TitleBehind: formTitleBehind,
                    Gender: formGender,
                    PlaceOfBirth: formPlaceOfBirht,
                    DateOfBirth: DateOfBirht,
                    Phone: formPhone,
                    HP: formMobile,
                    Email: formEmail,
                    EmailPU: emailPU,
                    Password_Old: Password_Old,
                    Address: formAddress,
                    Photo: fileName,
                    PositionOther1: PositionOther1,
                    PositionOther2: PositionOther2,
                    PositionOther3: PositionOther3,
                    StatusEmployeeID: formStatusEmployee
                }
            };

            var token = jwt_encode(data, 'UAP)(*');
            var url = base_url_js + 'api/__crudEmployees';
            $.post(url,{token:token},function (result) {

                if(result==0 || result=='0'){
                    toastr.error('NIK / NIP is exist','Error');
                } else {
                    if(fileType!=''){
                        uploadPhoto(fileName);
                    }

                    // Upload Ijazah
                    if(fileNameIjazah!='' && formIjazahExt!=''){
                        uploadIjazah(fileNameIjazah);
                    }
                    toastr.success('Employees Saved','Success');

                }

                setTimeout(function () {
                    // $('#NotificationModal').modal('hide');
                    window.location.href = '';
                },1000);


            });

        }
        else {
            var msg = '';
            if(formName==''){
                msg = 'Name is required';
            } else if (formYearBirth=='' || formMontBirth=='' || formDateBirth==''){
                msg = 'Birthday is required';
            } else if(form_MainDivision==''){
                msg = 'Main Division is required';
            } else if (form_MainPosition==''){
                msg = 'Main Position is required';
            }
            toastr.error(msg,'Error');
        }

    }

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

    function uploadIjazah(fileName) {

        if(fileName!='' && fileName!=null){

            var formData = new FormData( $("#fmIjazah")[0]);
            var url = base_url_js+'human-resources/upload_ijazah?fileName='+fileName;

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
    // ===================================

</script>