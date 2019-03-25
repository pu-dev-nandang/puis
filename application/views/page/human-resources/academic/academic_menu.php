<style>
    .btn-circle.btn-xl {
    width: 70px;
    height: 70px;
    padding: 10px 16px;
    border-radius: 35px;
    font-size: 24px;
    line-height: 1.33;
}

.btn-circle {
    width: 30px;
    height: 30px;
    padding: 6px 0px;
    border-radius: 15px;
    text-align: center;
    font-size: 12px;
    line-height: 1.42857;
}

.btn-round{
border-radius: 17px;
}
</style> 
<style>
    .form-attd[readonly] {
        cursor: cell;
        background-color: #fff;
        color: #333;
    }
</style>

<style type="text/css">
    @media screen and (min-width: 768px) {
        .modal-content {
          width: 785px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }
    @media screen and (min-width: 992px) {
        .modal-lg {
          width: 950px; /* New width for large modal */
        }
    }
</style>

<div class="row" style="margin-top: 30px;">

    <div class="col-md-12" style="margin-bottom: 15px;">
        <a href="<?php echo base_url('human-resources/academic_employees') ?>" class="btn btn-primary btn-round "><i class="fa fa-arrow-circle-left"></i> Back to list Academic Employee</a>
    </div>

    <div class="col-md-12">

        <div class="thumbnail" style="padding: 15px;">
            <div class="row">
                <div class="col-xs-1" style="text-align: right;padding-right: 0px;">
                    <div id="viewPhoto"></div>
                </div>
                <div class="col-xs-11">
                    <h3 style="margin-top: 0px;border-left: 11px solid #2196f3;padding-left: 10px;font-weight: bold;" id="viewName"></h3>
                    <table class="table">
                        <tr>
                            <td style="width: 50%;">
                                <i class="fa fa-envelope margin-right"></i> (Email PU) <a id="viewEmailPU"></a><br/>
                                <i class="fa fa-envelope margin-right"></i> (Email Other) <a id="viewEmailOther"></a><br/>
                                <i class="fa fa-phone margin-right"></i> (Phone) <span id="viewPhone"></span> <br/>
                                <i class="fa fa-phone margin-right"></i> (HP) <span id="viewHP"></span>
                            </td>
                            <td>
                                <i class="fa fa-map-marker margin-right"></i> <span id="viewAddress"></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <hr/>

        <div class="tabbable tabbable-custom tabbable-full-width">
            <ul class="nav nav-tabs">
                <li class="active"><a href="javascript:void(0)" class="menuDetails" data-page="academic_details" data-toggle="tab"><i class="fa fa-user"></i> Personal Information </a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="list_academic" data-toggle="tab"><i class="fa fa-graduation-cap"></i> Detail Academic</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="academic_sratasatu" data-toggle="tab"><i class="fa fa-university"></i>  Academic S1</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="academic_sratadua" data-toggle="tab"><i class="fa fa-university"></i> Academic S2</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="academic_sratatiga" data-toggle="tab"><i class="fa fa-university"></i> Academic S3</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="otherfiles" data-toggle="tab"><i class="fa fa-files-o"></i> Other Files</a></li>
                
            </ul>
            <div class="tab-content">
                <hr/>
                <div id="divPage"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadDataThumb();
        window.NIP = '<?php echo $NIP; ?>';

        var data = {
            NIP : NIP,
            page : 'academic_details'
        };
        var token = jwt_encode(data,'UAP)(*');
        loadPage(token);
        window.Lecturer_NIP = 0;

        $('input[id$="endTime"]').datetimepicker({
            format: 'HH:mm'
        });

    });

    $(document).on('click','.menuDetails',function () {
        var page = $(this).attr('data-page');
        var data = {
            NIP : NIP,
            page : page
        };
        var token = jwt_encode(data,'UAP)(*');
        loadPage(token);
    });

    $(document).on('click','.btnLecturerAction',function () {
        var page = $(this).attr('data-page');
        var ScheduleID = $(this).attr('data-id');
        var data = {
            NIP : NIP,
            page : page,
            ScheduleID : ScheduleID
        };
        var token = jwt_encode(data,'UAP)(*');
        loadPage(token);

    });

    $(document).on('click','.btnLecturerActionAttd',function () {
        var page = $(this).attr('data-page');
        var ScheduleID = $(this).attr('data-id');
        var data = {
            NIP : NIP,
            page : page,
            ScheduleID : ScheduleID
        };
        var token = jwt_encode(data,'UAP)(*');
        loadPagePresensi(token);

    });


    function loadDataThumb() {
        var url = base_url_js+'api/__crudAcademic';
        var NIP = '<?php echo $NIP; ?>';

        var token = jwt_encode({action:'readMini',NIP:NIP},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {

            $('#viewPhoto').html('<img class="img-rounded" src="'+base_url_img_employee+''+jsonResult.Photo+'" />');

            var linkupdate = base_url_js+"human-resources/employees/edit-employees/"+jsonResult.NIP; 
            var buttonlinkedit = ('<a href="'+linkupdate+'" class="btn btn-sm btn-success btn-round"><i class="fa fa-edit"></i> Edit Employee</a> ');

            $('#viewName').html(jsonResult.NIP+' - '+jsonResult.TitleAhead.trim()+' '+jsonResult.Name+' '+jsonResult.TitleBehind.trim()+' ' +
                            '<span style="float:right;"> '+buttonlinkedit+' | '+jsonResult.Division+' <i class="fa fa-angle-right"></i> <b>'+jsonResult.Position+'</b></span>');

            Lecturer_NIP = jsonResult.NIP.trim();

            var emailPU = (jsonResult.EmailPU!=null && jsonResult.EmailPU!='') ? jsonResult.EmailPU : '-';
            $('#viewEmailPU').html(emailPU);

            var emailOther = (jsonResult.Email!=null && jsonResult.Email!='') ? jsonResult.Email : '-';
            $('#viewEmailOther').html(emailOther);

            var Phone = (jsonResult.Phone!=null && jsonResult.Phone!='') ? jsonResult.Phone : '-';
            $('#viewPhone').html(Phone);

            var HP = (jsonResult.HP!=null && jsonResult.HP!='') ? jsonResult.HP : '-';
            $('#viewHP').html(HP);

            $('#viewAddress').html(jsonResult.Address.trim());
        });
    }

    function loadPage(token) {
        var url = base_url_js+'human-resources/loadpageacademicDetails';

        loading_page('#divpage');
        $.post(url,{token:token},function (html) {
            setTimeout(function () {
                $('#divPage').html(html);
            },500)
        });
    }

    function loadPagePresensi(token) {
        var url = base_url_js+'academic/loadPagePresensi';

        loading_page('#divpage');
        $.post(url,{token:token},function (html) {
            setTimeout(function () {
                $('#divPage').html(html);
            },500)
        });
    }
</script>

<script>
    $(document).on('click','.btnviewlistsrata',function () {
        var filesub = $(this).attr('filesub');
       
            $('#NotificationModal .modal-header').addClass('hide');
            $('#NotificationModal .modal-body').html('<center> '+
                '<iframe src="'+base_url_js+'uploads/files/'+filesub+'" frameborder="0" style="width:745px; height:550px;"></iframe> '+
                '<br/><br/><button type="button" id="btnRemoveNoEditSc" class="btn btn-primary btn-round" data-dismiss="modal"><span class="fa fa-remove"></span> Close</button><button type="button" filesublix ="'+filesub+'" class="btn btn-primary btn-circle pull-right filesublink" data-toggle="tooltip" data-placement="top" title="Full Review"><span class="fa fa-external-link"></span></button>' +
            '</center>');
            $('#NotificationModal .modal-footer').addClass('hide');
            $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
            });
    });

    $(document).on('click','.filesublink',function () {
        var filesubx = $(this).attr('filesublix');
        var url = base_url_js+'uploads/files/'+filesubx;
        window.open(url, '_blank',);
    });

</script>

<!-- ====== function Srata 1 save & edit data ========= -->
 <script>
    $(document).on('click','.btnSavedits1',function () {
        saveditEmployees1();
    });

    function saveditEmployees1() {
        var formNIP = '<?php echo $NIP; ?>';
        var formNoIjazahS1 = $('#formNoIjazahS1').val();
        var formNameUnivS1 = $('#formNameUnivS1').val();
        var formIjazahDate = $('#formEditIjazahDate').val();
        var formMajorS1 = $('#formMajorS1').val();
        var formStudyS1 = $('#formStudyS1').val();
        var gradeS1 = $('#gradeS1').val();
        var totalCreditS1 = $('#totalCreditS1').val();
        var TotSemesterS1 = $('#TotSemesterS1').val();
        var linkijazahs1 = $('#linkijazahs1').val();
        var linktranscripts1 = $('#linktranscripts1').val();
        if(formNIP!=null && formNIP!=''
                    && formNoIjazahS1!='' && formNoIjazahS1!=null
                    && formNameUnivS1!='' && formNameUnivS1!=null
                    && formIjazahDate!='' && formIjazahDate!=null
                    && formMajorS1!='' && formMajorS1!=null
                    && formStudyS1!='' && formStudyS1!=null
                    && gradeS1!='' && gradeS1!=null
                    && totalCreditS1!='' && totalCreditS1!=null
                    && TotSemesterS1!='' && TotSemesterS1!=null 
                    ){ 
                    loading_button('#btnSubmitEmployees');
                    $('#btnCloseEmployees').prop('disabled',true);

                    var data = {
                        action : 'editAcademicS1',
                        formInsert : {
                                NIP : formNIP,
                                NoIjazah : formNoIjazahS1,
                                NameUniversity : formNameUnivS1,
                                IjazahDate : formIjazahDate,
                                Major : formMajorS1,
                                ProgramStudy : formStudyS1,
                                Grade : gradeS1,
                                TotalCredit : totalCreditS1,
                                TotalSemester : TotSemesterS1,
                                linkijazahs1 : linkijazahs1,
                                linktranscripts1 : linktranscripts1 }
                            };

                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'api/__crudEditAcademicData';
                    $.post(url,{token:token},function (result) {
                    
                        if(result==0 || result=='0'){
                            toastr.error('NIK / NIP is exist','Error');
                        } else {  //if success save data
                
                                //uploadfile_transcripts(fileName_Transcript);
                                toastr.success('File Edit Saved','Success');

                        }
                            setTimeout(function () {
                                $('#NotificationModal').modal('hide');
                              window.location.href = '';
                            },1000);
                        });
                 }

        else {
            toastr.error('Form Masih ada yang kosong','Error');
            $('#NotificationModal').modal('show');
            return;
        }
}
 </script>

 <script>
    $(document).on('click','.btnSaveSrata1',function () {
        var NIP = $('#formNIP').val();
        $('#NotificationModal .modal-body').html('<div style="text-align: center;">' +
            'Pastikan data form & File Academic S1 tidak salah! <br/>' +
            'Periksa kembali data yang di input sebelum di Save. ' +
            '<hr/>' +
            '<button type="button" class="btn btn-default" id="btnCloseEmployees" data-dismiss="modal">Close</button> | ' +
            '<button type="button" class="btn btn-success btnSubmitEmployees1">Submit</button>' +
            '</div> ');

        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });
    });
 </script>

 <script>
    $(document).on('click','.btnSubmitEmployees1',function () {
        saveEmployees();
    });
    function saveEmployees() {

        var formNIP = '<?php echo $NIP; ?>';
        var formNoIjazahS1 = $('#formNoIjazahS1').val();
        var formNameUnivS1 = $('#formNameUnivS1').val();
        var formIjazahDate = $('#formIjazahDate').val();
        var formMajorS1 = $('#formMajorS1').val();
        var formStudyS1 = $('#formStudyS1').val();
        var gradeS1 = $('#gradeS1').val();
        var totalCreditS1 = $('#totalCreditS1').val();
        var TotSemesterS1 = $('#TotSemesterS1').val();

        var min=100; 
        var max=999;  
        var random =Math.floor(Math.random() * (+max - +min)) + +min; 

        var type = 'IjazahS1';
        var ext = 'PDF';
        var fileName = type+'_'+NIP+'_'+random+'.'+ext;
        var TypeTrans = 'TranscriptS1';
        var fileName_Transcript = TypeTrans+'_'+NIP+'_'+random+'.'+ext;

        var oFile = document.getElementById("fileIjazah").files[0]; 
        var xFile = document.getElementById("fileTranscript").files[0]; 

            if(formNIP!=null && formNIP!=''
                    && formNoIjazahS1!='' && formNoIjazahS1!=null
                    && formNameUnivS1!='' && formNameUnivS1!=null
                    && formIjazahDate!='' && formIjazahDate!=null
                    && formMajorS1!='' && formMajorS1!=null
                    && formStudyS1!='' && formStudyS1!=null
                    && gradeS1!='' && gradeS1!=null
                    && totalCreditS1!='' && totalCreditS1!=null
                    && TotSemesterS1!='' && TotSemesterS1!=null 
                    && oFile!='' && oFile!=null 
                    && xFile!='' && xFile!=null 
                    ){ 
                    loading_button('#btnSubmitEmployees');
                    $('#btnCloseEmployees').prop('disabled',true);

                    var data = {
                        action : 'addAcademicS1',
                        formInsert : {
                            NIP : formNIP,
                            NoIjazah : formNoIjazahS1,
                            NameUniversity : formNameUnivS1,
                            IjazahDate : formIjazahDate,
                            Major : formMajorS1,
                            ProgramStudy : formStudyS1,
                            Grade : gradeS1,
                            TotalCredit : totalCreditS1,
                            TotalSemester : TotSemesterS1,
                            file_trans : fileName_Transcript,
                            fileName : fileName }
                    };

                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'api/__crudAcademicData';
                    $.post(url,{token:token},function (result) {
                    
                        if(result==0 || result=='0'){
                            toastr.error('NIK / NIP is exist','Error');
                        } else {  //if success save data

                            if ($('#fileIjazah').get(0).files.length === 0) {
                                } else {
                                    var formData = new FormData( $("#tagFM_IjazahS1")[0]);
                                    var url = base_url_js+'human-resources/upload_academic?fileName='+fileName+'&c='+type+'&u='+NIP;
                                    $.ajax({
                                            url : url,  
                                            type : 'POST',
                                            data : formData,
                                            async : false,
                                            cache : false,
                                            contentType : false,
                                            processData : false,
                                            success : function(data) {
                                            }
                                    });
                                    if ($('#fileTranscript').get(0).files.length === 0) {
                                    } else {
                                        uploadfile_transcripts(fileName_Transcript);
                                    }    
                                    toastr.success('Document Saved With File','Success'); 
                                }
                        }   
                            setTimeout(function () {
                                $('#NotificationModal').modal('hide');
                                window.location.href = '';
                            },1000);
                    });
                }

        else {
            toastr.error('form or academic files are still empty!','Error');
            $('#NotificationModal').modal('hide');
            return;
        }
    }


function uploadfile_transcripts(fileName_Transcript) {

        var NIP = '<?php echo $NIP; ?>';                
        var type = 'TranscriptS1';
        var ext = 'PDF';
        var fileName = fileName_Transcript;
        var formData = new FormData( $("#tagFM_TranscriptS1")[0]);
        var url = base_url_js+'human-resources/upload_academic?fileName='+fileName+'&c='+type+'&u='+NIP;
                                            
            $.ajax({
                url : url,  // Controller URL
                type : 'POST',
                data : formData,
                async : false,
                cache : false,
                contentType : false,
                processData : false,
                    success : function(data) {
                        
                    }
                });   
}

</script>
<!-- ====== end function Srata 1 save & edit data ========= -->

<!-- ====== start function Srata 2 save & edit data ========= -->
<script>
    $(document).on('click','.btnSave2',function () {
        var NIP = $('#formNIP').val();
        $('#NotificationModal .modal-body').html('<div style="text-align: center;">' +
            'Pastikan data form & File Academic S2 tidak salah! <br/>' +
            'Periksa kembali data yang di input sebelum di Save. ' +
            '<hr/>' +
            '<button type="button" class="btn btn-default" id="btnCloseEmployees" data-dismiss="modal">Close</button> | ' +
            '<button type="button" class="btn btn-success btnSubmitEmployees2">Submit</button>' +
            '</div> ');

        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });
    });

    $(document).on('click','.btnSubmitEmployees2',function () {
        saveEmployees2();
    });

    function saveEmployees2() {

        var formNIP = '<?php echo $NIP; ?>';
        var formNoIjazahS1 = $('#formNoIjazahS2').val();
        var formNameUnivS1 = $('#formNameUnivS2').val();
        var formIjazahDate = $('#formIjazahDate').val();
        var formMajorS1 = $('#formMajorS2').val();
        var formStudyS1 = $('#formStudyS2').val();
        var gradeS1 = $('#gradeS2').val();
        var totalCreditS1 = $('#totalCreditS2').val();
        var TotSemesterS1 = $('#TotSemesterS2').val();

        var min=100; 
        var max=999;  
        var random =Math.floor(Math.random() * (+max - +min)) + +min; 
        var type = 'IjazahS2';
        var ext = 'PDF';
        var fileName = type+'_'+NIP+'_'+random+'.'+ext;
        var TypeTrans = 'TranscriptS2';
        var fileName_Transcript = TypeTrans+'_'+NIP+'_'+random+'.'+ext;
        var oFile = document.getElementById("fileIjazah").files[0]; 
        var xFile = document.getElementById("fileTranscript").files[0]; 

            if(formNIP!=null && formNIP!=''
                    && formNoIjazahS1!='' && formNoIjazahS1!=null
                    && formNameUnivS1!='' && formNameUnivS1!=null
                    && formIjazahDate!='' && formIjazahDate!=null
                    && formMajorS1!='' && formMajorS1!=null
                    && formStudyS1!='' && formStudyS1!=null
                    && gradeS1!='' && gradeS1!=null
                    && totalCreditS1!='' && totalCreditS1!=null
                    && TotSemesterS1!='' && TotSemesterS1!=null 
                    && oFile!='' && oFile!=null 
                    && xFile!='' && xFile!=null 
                    ){ 
                    loading_button('#btnSubmitEmployees');
                    $('#btnCloseEmployees').prop('disabled',true);

                    var data = {
                        action : 'addAcademicS2',
                        formInsert : {
                            NIP : formNIP,
                            NoIjazah : formNoIjazahS1,
                            NameUniversity : formNameUnivS1,
                            IjazahDate : formIjazahDate,
                            Major : formMajorS1,
                            ProgramStudy : formStudyS1,
                            Grade : gradeS1,
                            TotalCredit : totalCreditS1,
                            TotalSemester : TotSemesterS1,
                            file_trans : fileName_Transcript,
                            fileName : fileName }
                    };

                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'api/__crudAcademicData';
                    $.post(url,{token:token},function (result) {
                    
                        if(result==0 || result=='0'){
                            toastr.error('NIK / NIP is exist','Error');
                        } else {  //if success save data

                            if ($('#fileIjazah').get(0).files.length === 0) {
                                } else {
                                    var formData = new FormData( $("#tagFM_IjazahS2")[0]);
                                    var url = base_url_js+'human-resources/upload_academic?fileName='+fileName+'&c='+type+'&u='+NIP;
                                    $.ajax({
                                            url : url,  
                                            type : 'POST',
                                            data : formData,
                                            async : false,
                                            cache : false,
                                            contentType : false,
                                            processData : false,
                                            success : function(data) {

                                            }
                                    });
                                    if ($('#fileTranscript').get(0).files.length === 0) {
                                        } 
                                        else {
                                            uploadfile_transcripts2(fileName_Transcript);
                                        }    
                                    toastr.success('Document Saved With File','Success'); 
                                }
                        }   
                            setTimeout(function () {
                                $('#NotificationModal').modal('hide');
                                window.location.href = '';
                            },1000);
                    });
                }

        else {
            toastr.error('form or academic files are still empty!','Error');
            $('#NotificationModal').modal('hide');
            return;
        }
    }

function uploadfile_transcripts2(fileName_Transcript) {

        var NIP = '<?php echo $NIP; ?>';                
        var type = 'TranscriptS2';
        var ext = 'PDF';
        var fileName = fileName_Transcript;
        var formData = new FormData( $("#tagFM_TranscriptS2")[0]);
        var url = base_url_js+'human-resources/upload_academic?fileName='+fileName+'&c='+type+'&u='+NIP;
                                            
            $.ajax({
                url : url,  // Controller URL
                type : 'POST',
                data : formData,
                async : false,
                cache : false,
                contentType : false,
                processData : false,
                    success : function(data) {
                        
                    }
                });   
}

</script>

<script>
    $(document).on('click','.btnSavedits2',function () {
        saveditEmployees2();
    });

    function saveditEmployees2() {

        var formNIP = '<?php echo $NIP; ?>';
        var formNoIjazahS1 = $('#formNoIjazahS2').val();
        var formNameUnivS1 = $('#formNameUnivS2').val();
        var formIjazahDate = $('#formEditIjazahDate').val();
        var formMajorS1 = $('#formMajorS2').val();
        var formStudyS1 = $('#formStudyS2').val();
        var gradeS1 = $('#gradeS2').val();
        var totalCreditS1 = $('#totalCreditS2').val();
        var TotSemesterS1 = $('#TotSemesterS2').val();
        var linkijazahs1 = $('#linkijazahs1').val();
        var linktranscripts1 = $('#linktranscripts1').val();
        if(formNIP!=null && formNIP!=''
                    && formNoIjazahS1!='' && formNoIjazahS1!=null
                    && formNameUnivS1!='' && formNameUnivS1!=null
                    && formIjazahDate!='' && formIjazahDate!=null
                    && formMajorS1!='' && formMajorS1!=null
                    && formStudyS1!='' && formStudyS1!=null
                    && gradeS1!='' && gradeS1!=null
                    && totalCreditS1!='' && totalCreditS1!=null
                    && TotSemesterS1!='' && TotSemesterS1!=null 
                    ){ 
                    loading_button('#btnSubmitEmployees');
                    $('#btnCloseEmployees').prop('disabled',true);

                    var data = {
                        action : 'editAcademicS2',
                        formInsert : {
                                NIP : formNIP,
                                NoIjazah : formNoIjazahS1,
                                NameUniversity : formNameUnivS1,
                                IjazahDate : formIjazahDate,
                                Major : formMajorS1,
                                ProgramStudy : formStudyS1,
                                Grade : gradeS1,
                                TotalCredit : totalCreditS1,
                                TotalSemester : TotSemesterS1,
                                linkijazahs1 : linkijazahs1,
                                linktranscripts1 : linktranscripts1 }
                            };

                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'api/__crudEditAcademicData';
                    $.post(url,{token:token},function (result) {
                    
                        if(result==0 || result=='0'){
                            toastr.error('NIK / NIP is exist','Error');
                        } else {  //if success save data
                
                                //uploadfile_transcripts(fileName_Transcript);
                                toastr.success('File Edit Saved','Success');

                        }
                            setTimeout(function () {
                                $('#NotificationModal').modal('hide');
                              window.location.href = '';
                            },1000);
                        });
                 }

        else {
            toastr.error('Form Masih ada yang kosong','Error');
            $('#NotificationModal').modal('show');
            return;
        }

}
 </script>

<!-- ====== end function Srata 2 save & edit data ========= -->

<!-- ====== start function Srata 3 save & edit data ========= -->
<script>
    $(document).on('click','.btnSave3',function () {
        var NIP = $('#formNIP').val();
        $('#NotificationModal .modal-body').html('<div style="text-align: center;">' +
            'Pastikan data form & File Academic S3 tidak salah! <br/>' +
            'Periksa kembali data yang di input sebelum di Save. ' +
            '<hr/>' +
            '<button type="button" class="btn btn-default" id="btnCloseEmployees" data-dismiss="modal">Close</button> | ' +
            '<button type="button" class="btn btn-success" id="btnSubmitEmployees3">Submit</button>' +
            '</div> ');

        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });
    });

    $(document).on('click','#btnSubmitEmployees3',function () {
        saveEmployees3();
    });

    function saveEmployees3() {

        var formNIP = '<?php echo $NIP; ?>';
        var formNoIjazahS1 = $('#formNoIjazahS3').val();
        var formNameUnivS1 = $('#formNameUnivS3').val();
        var formIjazahDate = $('#formIjazahDate').val();
        var formMajorS1 = $('#formMajorS3').val();
        var formStudyS1 = $('#formStudyS3').val();
        var gradeS1 = $('#gradeS3').val();
        var totalCreditS1 = $('#totalCreditS3').val();
        var TotSemesterS1 = $('#TotSemesterS3').val();
        var min=100; 
        var max=999;  
        var random =Math.floor(Math.random() * (+max - +min)) + +min; 
        var type = 'IjazahS3';
        var ext = 'PDF';
        var fileName = type+'_'+NIP+'_'+random+'.'+ext;
        var TypeTrans = 'TranscriptS3';
        var fileName_Transcript = TypeTrans+'_'+NIP+'_'+random+'.'+ext;
        var oFile = document.getElementById("fileIjazah").files[0]; 
        var xFile = document.getElementById("fileTranscript").files[0]; 
        
            if(formNIP!=null && formNIP!=''
                    && formNoIjazahS1!='' && formNoIjazahS1!=null
                    && formNameUnivS1!='' && formNameUnivS1!=null
                    && formIjazahDate!='' && formIjazahDate!=null
                    && formMajorS1!='' && formMajorS1!=null
                    && formStudyS1!='' && formStudyS1!=null
                    && gradeS1!='' && gradeS1!=null
                    && totalCreditS1!='' && totalCreditS1!=null
                    && TotSemesterS1!='' && TotSemesterS1!=null 
                    && oFile!='' && oFile!=null 
                    && xFile!='' && xFile!=null 
                    ){ 
                    loading_button('#btnSubmitEmployees');
                    $('#btnCloseEmployees').prop('disabled',true);

                    var data = {
                        action : 'addAcademicS3',
                        formInsert : {
                            NIP : formNIP,
                            NoIjazah : formNoIjazahS1,
                            NameUniversity : formNameUnivS1,
                            IjazahDate : formIjazahDate,
                            Major : formMajorS1,
                            ProgramStudy : formStudyS1,
                            Grade : gradeS1,
                            TotalCredit : totalCreditS1,
                            TotalSemester : TotSemesterS1,
                            file_trans : fileName_Transcript,
                            fileName : fileName }
                    };

                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'api/__crudAcademicData';
                    $.post(url,{token:token},function (result) {
                    
                        if(result==0 || result=='0'){
                            toastr.error('NIK / NIP is exist','Error');
                        } else {  //if success save data

                            if ($('#fileIjazah').get(0).files.length === 0) {
                                } else {
                                    var formData = new FormData( $("#tagFM_IjazahS3")[0]);
                                    var url = base_url_js+'human-resources/upload_academic?fileName='+fileName+'&c='+type+'&u='+NIP;
                                    $.ajax({
                                            url : url,  
                                            type : 'POST',
                                            data : formData,
                                            async : false,
                                            cache : false,
                                            contentType : false,
                                            processData : false,
                                            success : function(data) {

                                            }
                                    });
                                    if ($('#fileTranscript').get(0).files.length === 0) {
                                        } 
                                        else {
                                            uploadfile_transcripts3(fileName_Transcript);
                                        }    
                                    toastr.success('Document Saved With File','Success'); 
                                }
                        }   
                            setTimeout(function () {
                                $('#NotificationModal').modal('hide');
                                window.location.href = '';
                            },1000);
                    });
                }

        else {
            toastr.error('form or academic files are still empty!','Error');
            $('#NotificationModal').modal('hide');
            return;
        }
    }

function uploadfile_transcripts3(fileName_Transcript) {

        var NIP = '<?php echo $NIP; ?>';                
        var type = 'TranscriptS3';
        var ext = 'PDF';
        var fileName = fileName_Transcript;
        var formData = new FormData( $("#tagFM_TranscriptS3")[0]);
        var url = base_url_js+'human-resources/upload_academic?fileName='+fileName+'&c='+type+'&u='+NIP;
                                            
            $.ajax({
                url : url,  // Controller URL
                type : 'POST',
                data : formData,
                async : false,
                cache : false,
                contentType : false,
                processData : false,
                    success : function(data) {
                        
                    }
            });   
    }
</script>

<script>
    $(document).on('click','.btnSavedits3',function () {
        saveditEmployees3();
    });

    function saveditEmployees3() {
        var formNIP = '<?php echo $NIP; ?>';
        var formNoIjazahS1 = $('#formNoIjazahS3').val();
        var formNameUnivS1 = $('#formNameUnivS3').val();
        var formIjazahDate = $('#formEditIjazahDate').val();
        var formMajorS1 = $('#formMajorS3').val();
        var formStudyS1 = $('#formStudyS3').val();
        var gradeS1 = $('#gradeS3').val();
        var totalCreditS1 = $('#totalCreditS3').val();
        var TotSemesterS1 = $('#TotSemesterS3').val();
        var linkijazahs1 = $('#linkijazahs1').val();
        var linktranscripts1 = $('#linktranscripts1').val();
        if(formNIP!=null && formNIP!=''
                    && formNoIjazahS1!='' && formNoIjazahS1!=null
                    && formNameUnivS1!='' && formNameUnivS1!=null
                    && formIjazahDate!='' && formIjazahDate!=null
                    && formMajorS1!='' && formMajorS1!=null
                    && formStudyS1!='' && formStudyS1!=null
                    && gradeS1!='' && gradeS1!=null
                    && totalCreditS1!='' && totalCreditS1!=null
                    && TotSemesterS1!='' && TotSemesterS1!=null 
                    ){ 
                    loading_button('#btnSubmitEmployees');
                    $('#btnCloseEmployees').prop('disabled',true);

                    var data = {
                        action : 'editAcademicS3',
                        formInsert : {
                                NIP : formNIP,
                                NoIjazah : formNoIjazahS1,
                                NameUniversity : formNameUnivS1,
                                IjazahDate : formIjazahDate,
                                Major : formMajorS1,
                                ProgramStudy : formStudyS1,
                                Grade : gradeS1,
                                TotalCredit : totalCreditS1,
                                TotalSemester : TotSemesterS1,
                                linkijazahs1 : linkijazahs1,
                                linktranscripts1 : linktranscripts1 }
                            };

                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'api/__crudEditAcademicData';
                    $.post(url,{token:token},function (result) {
                    
                        if(result==0 || result=='0'){
                            toastr.error('NIK / NIP is exist','Error');
                        } else {  //if success save data
                
                                //uploadfile_transcripts(fileName_Transcript);
                                toastr.success('File Edit Saved','Success');

                        }
                            setTimeout(function () {
                                $('#NotificationModal').modal('hide');
                              window.location.href = '';
                            },1000);
                        });
                 }

        else {
                toastr.error('Form Masih ada yang kosong','Error');
                $('#NotificationModal').modal('show');
                return;
        }

    }
 </script>

<!-- ====== end function Srata 3 save & edit data ========= -->

<!-- ====== start function Other Files save & edit data ========= -->
<script>
    $(document).on('click','.btnSubmitFiles',function () {
        saveFileDocument();
    });

    function saveFileDocument() {

        var formNIP = '<?php echo $NIP; ?>';
        var NoDocument = $('#NoDocument').val();
        var DescriptionFile = $('#DescriptionFile').val();
        var DateDocument = $('#DateDocument').val();
        // var type = $("#typefiles option:selected").attr("id")
        var type = $("#typefiles option:selected").text();
        var min=100; 
        var max=999;  
        var random =Math.floor(Math.random() * (+max - +min)) + +min; 
        var ext = 'PDF';
        var fileName = type+'_'+NIP+'_'+random+'.'+ext;
        var oFile = document.getElementById("fileOther").files[0]; 

        if(formNIP!=null && formNIP!=''
                    && NoDocument!='' && NoDocument!=null
                    && DescriptionFile!='' && DescriptionFile!=null
                    && DateDocument!='' && DateDocument!=null
                    && type!='' && type!=null
                    && oFile!='' && oFile!=null
                    ){ 
                    loading_button('.btnSubmitFiles');
                    $('#btnCloseEmployees').prop('disabled',true);

                    var data = {
                        action : 'AddFilesDocument',
                        formInsert : {
                            NIP : formNIP,
                            NoDocument : NoDocument,
                            DateDocument : DateDocument,
                            type : type,
                            DescriptionFile : DescriptionFile,
                            fileName : fileName }
                    };

                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'api/__crudAcademicData';
                    $.post(url,{token:token},function (result) {
                    
                        if(result==0 || result=='0'){
                            toastr.error('NIK / NIP is exist','Error');
                        } else {  //if success save data
                
                            if ($('#fileOther').get(0).files.length === 0) {
                                } else {
                                    var formData = new FormData( $("#tagFM_OtherFile")[0]);
                                    var action = 'OtherFiles';
                                    var url = base_url_js+'human-resources/upload_academic?fileName='+fileName+'&c='+type+'&action='+action+'&u='+NIP;
                                     
                                    $.ajax({
                                        url : url,  // Controller URL
                                        type : 'POST',
                                        data : formData,
                                        async : false,
                                        cache : false,
                                        contentType : false,
                                        processData : false,
                                        success : function(data) {
                                    }
                                });   
                            }
                                toastr.success('Other Data Saved','Success');
                        }
                            setTimeout(function () {
                                $('#NotificationModal').modal('hide');
                                window.location.href = '';
                            },1000);

                        });
                }
            else {
                toastr.error('form or file are still empty!','Error');
                $('#NotificationModal').modal('hide');
                return;
            }
    }
                             
</script>
<script>
    $(document).on('click','.btnSubmitEditFiles',function () {
        saveEditFileDocument();
    });

    function saveEditFileDocument() {

        var formNIP = '<?php echo $NIP; ?>';
        var NoDocument = $('#NoDocument').val();
        var DescriptionFile = $('#DescriptionFile').val();
        var DateDocument = $('#DateDocument').val();
        var linkotherfile = $('#linkotherfile').val();
        var typeotherfiles = $('#typeotherfiles').val();
        var idlinkfiles = $('#idlinkfiles').val();
        var newdate = DateDocument.split("/").reverse().join("-");

        if(formNIP!=null && formNIP!=''
                    && NoDocument!='' && NoDocument!=null
                    && DescriptionFile!='' && DescriptionFile!=null
                    //&& DateDocument!='' && DateDocument!=null
                    ){ 
                    loading_button('.btnSubmitEditFiles');
                    $('#btnCloseEmployees').prop('disabled',true);

                    var data = {
                        action : 'EditFilesDocument',
                        formInsert : {
                            formNIP : formNIP,
                            NoDocument : NoDocument,
                            DateDocument : DateDocument,
                            typeotherfiles : typeotherfiles,
                            DescriptionFile : DescriptionFile,
                            linkotherfile : linkotherfile,
                            idlinkfiles : idlinkfiles }
                        };

                    var token = jwt_encode(data,'UAP)(*');
                    var url = base_url_js+'api/__crudEditAcademicData';
                    $.post(url,{token:token},function (result) {
                    
                        if(result==0 || result=='0'){
                            toastr.error('NIK / NIP is exist','Error');
                        } else {  //if success save data
                
                            //var formData = new FormData( $("#tagFM_OtherFile")[0]);
                            //var action = 'OtherFiles';
                            //var url = base_url_js+'human-resources/upload_academic?c='+type+'&action='+action+'&u='+NIP;
                                 
                            //    $.ajax({
                            //            url : url,  // Controller URL
                            //            type : 'POST',
                            //            data : formData,
                             //           async : false,
                            //            cache : false,
                             //           contentType : false,
                            //            processData : false,
                             //           success : function(data) {
                            //        }
                            //    });   

                                //uploadfile_transcripts(fileName_Transcript);
                                toastr.success('Document Data Saved','Success');

                        }
                            setTimeout(function () {
                                $('#NotificationModal').modal('hide');
                                window.location.href = '';
                            },1000);

                        });
            }

    else {
            toastr.error('Form Masih ada yang kosong','Error');
            $('#NotificationModal').modal('hide');
            return;
        }

    }

function uploadfile_transcripts(fileName_Transcript) {

        var NIP = '<?php echo $NIP; ?>';                
        var type = 'TranscriptS1';
        var ext = 'PDF';
        var fileName = fileName_Transcript;
        var formData = new FormData( $("#tagFM_TranscriptS1")[0]);
        var url = base_url_js+'human-resources/upload_academic?fileName='+fileName+'&c='+type+'&u='+NIP;
                                            
            $.ajax({
                url : url,  // Controller URL
                type : 'POST',
                data : formData,
                async : false,
                cache : false,
                contentType : false,
                processData : false,
                    success : function(data) {
                        
                    }
                });   
}

</script>
<!-- ====== end function Other Files 3 save & edit data ========= -->





