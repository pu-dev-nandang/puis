<script type="text/javascript">
    $(document).ready(function(){
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-personal").addClass("active");
    });
</script>

<div class="row">
        <div class="col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading" style="border-radius: 0px;">
                    <h4 class="panel-title"><i class="fa fa-edit"></i> Edit Employer</h4>
                </div>
                <div class="panel-body" style="min-height: 100px;">

                    <div class="row">
                        <div class="col-md-6" style="border-right: 1px solid #afafafb5;">
                            <div class="row">
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label>NIK / NIP</label>
                                        <input class="form-control" disabled id="formNIP" value="<?php echo $arrEmp['NIP']; ?>">
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label>NUP</label>
                                        <input class="form-control" id="formNUP" value="<?php echo $arrEmp['NUP']; ?>">
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label>NIDN</label>
                                        <input class="form-control" id="formNIDN" value="<?php echo $arrEmp['NIDN']; ?>">
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label>NIDK</label>
                                        <input class="form-control" id="formNIDK" value="<?php echo $arrEmp['NIDK']; ?>">
                                    </div>
                                </div>

                </div>

                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                            <label>No KTP *</label>
                            <input class="form-control" id="formKTP" value="<?php echo $arrEmp['KTP']; ?>" />
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>Card Number</label>
                            <input type="text" class="form-control" id="CardNumber" value="<?= $arrEmp['Access_Card_Number']; ?>">
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Religion *</label>
                            <select class="form-control" id="formReligion"></select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-9">
                        <div class="form-group">
                            <label>Name *</label>
                            <input class="form-control" id="formName" value="<?php echo $arrEmp['Name']; ?>" />
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Gender *</label>
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
                            <label>Place Of Birth *</label>
                            <input class="form-control" id="formPlaceOfBirht" value="<?php echo $arrEmp['PlaceOfBirth']; ?>" />
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">

                            <div class="thumbnail" style="padding: 10px;text-align: center;">
                                <h4>Date Of Birth *</h4>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Year</label>
                                            <select class="form-control" id="formYearBirth"></select>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Month</label>
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
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>Mobile</label>
                            <input class="form-control" id="formMobile" value="<?php echo $arrEmp['HP']; ?>" />
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label>Blood</label>
                            <input class="form-control" id="formBlood" value="<?php echo $arrEmp['Blood']; ?>" />
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
                                    <input type="text" class="form-control" id="formEmailPU" value="<?php echo $EmailPU; ?>" disabled />
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
                            <label>Address *</label>
                            <textarea rows="3" class="form-control" id="formAddress" placeholder="Same as your Address on identity card"><?php echo $arrEmp['Address']; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label>Postcode *</label>
                            <input type="text" class="form-control" id="formPostcode" value="<?php echo $arrEmp['Postcode']; ?>">
                        </div>
                    </div>
                </div>
                <p><b>*) Please fill out this field by referring to ID card</b></p>

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
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>Status Employee</label>
                            <select class="form-control" id="formStatusEmployee"></select>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>Status Lecturer</label>
                            <select class="form-control" id="formStatusLecturer">
                                <option>* Not Set</option>
                                <option disabled>------</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-4">

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

                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>Programme Study</label>
                            <select class="form-control" id="formProgrammeStudy"></select>
                        </div>
                        <div class="form-group">
                            <label>Profession</label>
                            <input class="form-control" id="formProfession" value="<?= $arrEmp['Profession']; ?>">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>Level of Education</label>
                            <select class="form-control" id="formLevelEducationID"></select>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="formSerdos" <?= ($arrEmp['Serdos']=='1') ? 'checked' : ''; ?> value="1">
                                    Certified Dosen (Serdos)
                                </label>
                            </div>
                            <input type="text" id="formSerdosNumber" class="form-control" <?= ($arrEmp['Serdos']=='1') ? '' : 'disabled'; ?> value="<?= $arrEmp['SerdosNumber']; ?>" placeholder="Serdos Number">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>Academic Position</label>
                            <select class="form-control" id="formLecturerAcademicPositionID">
                                <option>* Not Set</option>
                                <option disabled>------</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="formCertified" <?= ($arrEmp['Certified']=='1') ? 'checked' : ''; ?> value="1">
                                    Certified
                                </label>
                            </div>
                            <button class="btn btn-sm btn-default" id="btnCertificate"><i class="fa fa-folder margin-right"></i> Certificate</button>
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
                <?php
                // 2017114 Irfan = Button sementara di munculin untuk Irfan IT dulu
                $hideDelete = ($this->session->userdata('NIP') == '2017114') ? '' : 'hide'; ?>
                <div class="<?= $hideDelete ?>">
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

        loadSelectOptionEmployeesStatus2('#formStatusEmployee',"<?php echo $arrEmp['StatusEmployeeID']; ?>");
        loadSelectOptionLecturerStatus2('#formStatusLecturer',"<?php echo $arrEmp['StatusLecturerID']; ?>");

        var ProdiID = "<?php echo $arrEmp['ProdiID']; ?>";
        // if(ProdiID=='' )
        $('#formProgrammeStudy').append('<option selected>* Not set</option>');
        $('#formProgrammeStudy').append('<option disabled>-------------------</option>');
        loadSelectOptionBaseProdi('#formProgrammeStudy',ProdiID);
        FuncEvform_MainDivision();

        loadSelectOptionLevelEducation('#formLevelEducationID',<?= $arrEmp['LevelEducationID']; ?>);
        loadSelectOptionLecturerAcademicPosition('#formLecturerAcademicPositionID',<?= $arrEmp['LecturerAcademicPositionID']; ?>);

        <?php if($arrEmp['StatusForlap']!='' && $arrEmp['StatusForlap']!=null){ ?>
            $('#formStatusForlap').val(<?= $arrEmp['StatusForlap']; ?>);
        <?php } ?>


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
            && SerdosForm==true
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

            var formCertified = ($('#formCertified').is(':checked')) ? '1' : '0';

            var formStatusForlap = $('#formStatusForlap').val();

            var formStatusLecturer = $('#formStatusLecturer').val();
            var formProfession = $('#formProfession').val();

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

                    NUP: formNUP,
                    NIDN: formNIDN,
                    NIDK: formNIDK,
                    KTP: formKTP,
                    Name: formName,
                    TitleAhead: formTitleAhead,
                    TitleBehind: formTitleBehind,
                    Certified: formCertified,
                    Gender: formGender,
                    PlaceOfBirth: formPlaceOfBirht.trim(),
                    DateOfBirth: DateOfBirht,
                    Phone: formPhone,
                    HP: formMobile,
                    Blood: formBlood,
                    Email: formEmail,
                    EmailPU: emailPU,
                    Password_Old: Password_Old,
                    Address: formAddress.trim(),
                    Postcode: formPostcode,
                    Photo: fileName,
                    PositionOther1: PositionOther1,
                    PositionOther2: PositionOther2,
                    PositionOther3: PositionOther3,
                    StatusEmployeeID: formStatusEmployee,
                    StatusLecturerID: formStatusLecturer,
                    Profession: formProfession,
                    StatusForlap : formStatusForlap,
                    Access_Card_Number : Access_Card_Number,
                    Serdos : formSerdos,
                    SerdosNumber : formSerdosNumber

                }
            };

            var token = jwt_encode(data, 'UAP)(*');
            var url = base_url_js + 'api/__crudEmployees';
            $.post(url,{token:token},function (result) {

                if(result.status==0 || result.status=='0'){
                    // toastr.error('NIK / NIP is exist','Error');
                    toastr.error(result.msg,'Error');
                    // $('#NotificationModal').modal('hide');
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
                },2000);


            });

        }
        else {
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
            msg += "</ol>";
            /*END ADDED BY FEBRI @ FEB 2020*/
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
                    console.log(data);
                    
                    //var jsonData = JSON.parse(data);

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
            toastr.error('NIP / NIP is empty','Error');
        }

    }
    // ===================================


    $(document).on('click','#btnCertificate',function () {

        var formNIP = $('#formNIP').val();
        var formName = $('#formName').val();

        $('#GlobalModalLarge .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+formNIP+' - '+formName+'</h4>');

        window.bodyModal = '<div class="row">' +
            '' +
            '    <div class="col-md-4">' +
            '        <div class="well">' +
            '<input id="formID" class="hide">' +
            '            <div class="form-group">' +
            '                <label>Certificate</label>' +
            '                <select class="form-control" id="formCertificate">' +
            '                    <option value="Profesional">Profesional</option>' +
            '                    <option value="Profesi">Profesi</option>' +
            '                    <option value="Industri">Industri</option>' +
            '                    <option value="Kompetensi">Kompetensi</option>' +
            '                </select>' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <label>Publish Year</label>' +
            '                <input class="form-control" id="formPublicationYear" style="color: #333;" readonly>' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <label>Due Date</label>' +
            '                <input class="form-control" id="formDueDate" style="color: #333;" readonly>' +
            '                <div class="checkbox">' +
            '                    <label>' +
            '                        <input type="checkbox" id="formLifetime" value="1">' +
            '                        Lifetime' +
            '                    </label>' +
            '                </div>' +
            '            </div>' +
            '' +
            '            <div class="form-group">' +
            '                <label>Scale</label>' +
            '                <select class="form-control" id="formScale">' +
            '                    <option value="Nasional">Nasional</option>' +
            '                    <option value="Internasional">Internasional</option>' +
            '                </select>' +
            '            </div>' +
            '' +
            '            <div class="form-group">' +
            '                <label>Files</label>' +
            '                <form id="formupload_files" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">' +
            '                    <input type="file" name="userfile" id="upload_files" accept="application/pdf">' +
            '                </form>' +
            '            </div>' +
            '            <div class="form-group" style="text-align: right;">' +
            '                <button class="btn btn-success" id="btnSaveCerti">Save</button>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    ' +
            '    <div class="col-md-8">' +
            '        <table class="table table-bordered table-striped" id="dataTableCerti">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Certificate</th>' +
            '                <th style="width: 20%;">Scale</th>' +
            '                <th style="width: 17%;">File</th>' +
            '                <th style="width: 10%;"><i class="fa fa-cog"></i></th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody id="dataList"></tbody>' +
            '        </table>' +
            '    </div>' +
            '' +
            '</div>';

        $('#GlobalModalLarge .modal-body').html('<div id="loadUlang">'+bodyModal+'</div>');

        loadCertificate();

        $( "#formPublicationYear,#formDueDate" )
            .datepicker({
                showOtherMonths:true,
                autoSize: true,
                dateFormat: 'dd MM yy',
                // minDate: new Date(moment().year(),moment().month(),moment().date()),
                onSelect : function () {
                    // var data_date = $(this).val().split(' ');
                    // var nextelement = $(this).attr('nextelement');
                    // nextDatePick(data_date,nextelement);
                }
            });

        $('#GlobalModalLarge .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModalLarge').modal({
            'show' : true,
            'backdrop' : 'static'
        });




    });

    $(document).on('click','#btnSaveCerti',function () {

        var formID = $('#formID').val();
        var formNIP = $('#formNIP').val();
        var formCertificate = $('#formCertificate').val();
        var formPublicationYear = $('#formPublicationYear').datepicker("getDate");
        var formDueDate = $('#formDueDate').datepicker("getDate");
        var formLifetime = ($('#formLifetime').is(':checked')) ? '1' : '0';
        var formScale = $('#formScale').val();

        if(formPublicationYear!=null && formPublicationYear!=''){

            loading_buttonSm('#btnSaveCerti');

            var data = {
                action : 'UpdateCertificateLec',
                ID : (formID!='' && formID!=null) ? formID : '',
                dataForm : {
                    NIP : formNIP,
                    Certificate : formCertificate,
                    PublicationYear : (formPublicationYear!=null) ? moment(formPublicationYear).format('YYYY-MM-DD') : '',
                    DueDate : (formDueDate!=null) ? moment(formDueDate).format('YYYY-MM-DD') : '',
                    Lifetime : formLifetime,
                    Scale : formScale
                }
            };

            var file = $('#upload_files').val();

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js + 'api/__crudEmployees';
            $.post(url,{token:token},function (jsonResult) {

                var ID = jsonResult.ID;
                var FileName = jsonResult.FileName;

                if(file!='' && file!=null){
                    uploadCertificate(ID,FileName);
                } else {
                    toastr.success('Data Saved','Success');
                }

                setTimeout(function () {

                    $('#loadUlang').html(bodyModal);

                    $( "#formPublicationYear,#formDueDate" )
                        .datepicker({
                            showOtherMonths:true,
                            autoSize: true,
                            dateFormat: 'dd MM yy',
                            // minDate: new Date(moment().year(),moment().month(),moment().date()),
                            onSelect : function () {
                                // var data_date = $(this).val().split(' ');
                                // var nextelement = $(this).attr('nextelement');
                                // nextDatePick(data_date,nextelement);
                            }
                        });

                    loadCertificate();
                },500);

            });

        }

    });

    function loadCertificate() {

        var formNIP = $('#formNIP').val();
        var data = {
            action : 'readCertificateLec',
            NIP : formNIP
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js + 'api/__crudEmployees';

        $.post(url,{token:token},function (jsonResult) {

            $('#dataList').empty();
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var btnAct = '<div class="btn-group">' +
                        '  <button type="button" class="btn btn-default dropdown-toggle btnAct" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
                        '  </button>' +
                        '  <ul class="dropdown-menu">' +
                        '    <li><a href="javascript:void(0);" class="btnEditCerti" data-id="'+v.ID+'">Edit</a></li>' +
                        '    <li role="separator" class="divider"></li>' +
                        '    <li><a href="javascript:void(0);" class="btnDelCerti" data-id="'+v.ID+'">Remove</a></li>' +
                        '  </ul>' +
                        '</div>' +
                        '<textarea class="hide" id="dataEditCerti_'+v.ID+'">'+JSON.stringify(v)+'</textarea>';

                    var p = (v.PublicationYear!='' && v.PublicationYear!=null)
                        ? moment(v.PublicationYear).format('DD MMM YYYY') : '';

                    var d = (v.Duedate!='' && v.Duedate!=null)
                        ? moment(v.Duedate).format('DD MMM YYYY') : '';

                    d = (v.Lifetime!='1') ? d : 'Lifetime';

                    var file = (v.File!='' && v.File!=null) ? '<a class="btn btn-sm btn-default" target="_blank" href="'+base_url_js+'uploads/certificate/'+v.File+'">Download</a>' : '';

                    $('#dataList').append('<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td style="text-align: left;"><b>'+v.Certificate+'</b><br/> '+p+' - '+d+'</td>' +
                        '<td>'+v.Scale+'</td>' +
                        '<td>'+file+'</td>' +
                        '<td>'+btnAct+'</td>' +
                        '</tr>');
                });
            } else {
                $('#dataList').append('<tr><td colspan="5">Sertificate not yet</td></tr>');
            }

        });

    }

    $(document).on('click','.btnEditCerti',function () {

        var ID = $(this).attr('data-id');
        var dataEditCerti = $('#dataEditCerti_'+ID).val();
        var d = JSON.parse(dataEditCerti);

        $('#formID').val(d.ID);
        $('#formCertificate').val(d.Certificate);
        $('#formScale').val(d.Scale);


        (d.DueDate!=='0000-00-00' && d.DueDate!==null)
            ? $('#formDueDate').datepicker('setDate',new Date(d.DueDate))
            : '';
        (d.PublicationYear!=='0000-00-00' && d.PublicationYear!==null)
            ? $('#formPublicationYear').datepicker('setDate',new Date(d.PublicationYear))
            : '';

        var lf = (d.Lifetime=='1') ? true : false ;
        $('#formLifetime').prop('checked',lf);

    });

    function uploadCertificate(ID,FileNameOld) {

        var input = $('#upload_files');
        var files = input[0].files[0];

        var sz = parseFloat(files.size) / 1000000; // ukuran MB
        var ext = files.type.split('/')[1];

        if(Math.floor(sz)<=8){

            var fileName = moment().unix()+'_'+sessionNIP+'.'+ext;
            var formData = new FormData( $("#formupload_files")[0]);

            var url = base_url_js+'human-resources/upload_certificate?fileName='+fileName+'&old='+FileNameOld+'&&id='+ID;

            $.ajax({
                url : url,  // Controller URL
                type : 'POST',
                data : formData,
                async : false,
                cache : false,
                contentType : false,
                processData : false,
                success : function(data) {
                    toastr.success('Upload Success','Saved');
                    setTimeout(function () {
                        // window.location.href = '';
                    },500);
                    // loadDataEmployees();

                }
            });

        }



    }

    $(document).on('click','.btnDelCerti',function () {

        if(confirm('Are you sure to permanent remove?')){

            $('.btnAct').prop('disabled',true);

            var ID = $(this).attr('data-id');
            var dataEditCerti = $('#dataEditCerti_'+ID).val();
            var d = JSON.parse(dataEditCerti);

            var data = {
                action : 'removeCertificateLec',
                ID : ID,
                File : (d.File!='' && d.File!=null) ? d.File : ''
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js + 'api/__crudEmployees';

            $.post(url,{token:token},function (result) {

                toastr.success('Date removed','Success');
                setTimeout(function () {
                    loadCertificate();
                },500);
            });
        }

    })

</script>