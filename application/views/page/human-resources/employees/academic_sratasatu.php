<!--<?php //echo $NIP; ?> -->

<div class="row">
            <div class="col-md-6" style="border-right: 1px solid #afafafb5;">

               

                    <div class="col-xs-12 id="subsesiftg1">
                        <div class="form-group">
                            <div class="thumbnail" style="padding: 10px;text-align: left;">
                                <h4>Data Academic Transcript S1 </h4>
                               
                                <div class="row"> 
                                	<div class="col-xs-4">
                                    	<div class="form-group">
                                        	<label>No. Ijazah S1</label>
                                        	<input class="form-control" id="formNoIjazahS1">
                                    	</div>
                                	</div>
                                	<div class="col-xs-8">
                                    	<div class="form-group">
                                        	<label>Name Univesity</label>
                                        	<input class="form-control" id="formNameUnivS1">
                                    	</div>
                                	</div>
                                	<div class="col-xs-6">
                                    	<div class="form-group">
                                        	<label>Major</label>
                                        	<input class="form-control" id="formMajorS1">
                                    	</div>
                                	</div>
                                	<div class="col-xs-6">
                                    	<div class="form-group">
                                        	<label>Program Study </label>
                                        	<input class="form-control" id="formStudyS1">
                                    	</div>
                                	</div>
                                	<div class="col-xs-6">
                                    	<div class="form-group">
                                        	<label>Grade/ IPK</label>
                                        	<input class="form-control" id="gradeS1">
                                    	</div>
                                	</div>
                                	<div class="col-xs-3">
                                    	<div class="form-group">
                                        	<label>Total Credit</label>
                                        	<input class="form-control" id="totalCreditS1">
                                    	</div>
                                	</div>
                                	<div class="col-xs-3">
                                    	<div class="form-group">
                                        	<label>Total Semester</label>
                                        	<input class="form-control" id="TotSemesterS1">
                                    	</div>
                                	</div>

                                    <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Ijazah</label>
                                            <form id="fmPhoto" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                                    <input id="formPhoto" class="hide" value="" hidden />
                                                        <div class="form-group">
                                                            <label class="btn btn-sm btn-default btn-default-warning btn-upload">
                                                                <i class="fa fa-upload margin-right"></i> Upload Files
                                                                <input type="file" id="filePhoto" name="userfile" class="uploadPhotoEmp" style="display: none;" accept="application/pdf">
                                                            </label>
                                                    <p style="font-size: 12px;color: #ccc;">*) Only pdf files</p>
                                            </div></form>
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Transcript</label>
                                            <form id="fmPhoto" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                                    <input id="formPhoto" class="hide" value="" hidden />
                                                        <div class="form-group">
                                                            <label class="btn btn-sm btn-default btn-default-warning btn-upload">
                                                                <i class="fa fa-upload margin-right"></i> Upload Files
                                                                <input type="file" id="filePhoto" name="userfile" class="uploadPhotoEmp" style="display: none;" accept="application/pdf">
                                                        </label>
                                                 <p style="font-size: 12px;color: #ccc;">*) Only pdf files</p>
                                            </div></form>
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
                    </div>

                    <!-- <span id="bodyAddSesi"></span> -->

                
            </div>
 </div>

 
 <script>

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
            'Pastikan Data & File Academic S1 tidak salah! <br/>' +
            'Periksa kembali data yang di input sebelum di Save. ' +
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

        var formNIP = '<?php echo $NIP; ?>';
        var formNoIjazahS1 = $('#formNoIjazahS1').val();
        var formNameUnivS1 = $('#formNameUnivS1').val();
        var formMajorS1 = $('#formMajorS1').val();
        var formStudyS1 = $('#formStudyS1').val();
        var gradeS1 = $('#gradeS1').val();
        var totalCreditS1 = $('#totalCreditS1').val();
        var TotSemesterS1 = $('#TotSemesterS1').val();
        
        
        if(formNIP!=null && formNIP!=''
            && formNoIjazahS1!='' && formNoIjazahS1!=null
            && formNameUnivS1!='' && formNameUnivS1!=null
            && formMajorS1!='' && formMajorS1!=null
            && formStudyS1!='' && formStudyS1!=null
            && gradeS1!='' && gradeS1!=null
            && totalCreditS1!='' && totalCreditS1!=null
        ){
            loading_button('#btnSubmitEmployees');
            $('#btnCloseEmployees').prop('disabled',true);

            var fileType = $('#formImgType').val();
            var fileName = formNIP+'.'+fileType;
            alert(fileType);
            alert(fileName);

            var data = {
                //arr_Prodi : arr_Prodi,
                //action : 'addEmployees',
                action : 'addAcademic',
                formInsert : {
                    NIP : formNIP,
                    NoIjazah : formNoIjazahS1,
                    NameUniversity : formNameUnivS1,
                    Major : formMajorS1,
                    ProgramStudy : formStudyS1,
                    Grade : gradeS1,
                    TotalCredit : totalCreditS1
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudAcademicData';
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
        } else {
            toastr.error('Form Masih ada yang kosong','Error');
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

<script>
    $(document).ready(function () {
        loadSelectOptionEmployeesSingle('#formEmployees','');
        $('#formPengawas1,#formPengawas2').select2({allowClear: true});
    });

    $('#formEmployees').change(function () {
        loadDataEmployees();
    });

    function loadDataEmployees() {
        var formEmployees = $('#formEmployees').val();
        if(formEmployees!='' && formEmployees!=null){

            var url = base_url_js+'api/__crudEmployees';
            var data = {
                action : 'getEmployeesFiles',
                NIP : formEmployees.trim()
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {

                var d = jsonResult[0];

                var Photo = base_url_img_employee+''+d.Photo;

                $('#viewPhoto').html('<img src="'+Photo+'" style="width: 100%;max-width: 40px;">');
                $('#viewName').html(d.Name);
                $('#viewNIP').html(d.NIPLec);


                // Load files
                var KTP = (d.KTP!='' && d.KTP!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.KTP+'">'+d.KTP+'</a>' : '-';
                var CV = (d.CV!='' && d.CV!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.CV+'">'+d.CV+'</a>' : '-';
                var IjazahS1 = (d.IjazahS1!='' && d.IjazahS1!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.IjazahS1+'">'+d.IjazahS1+'</a>' : '-';
                var TranscriptS1 = (d.TranscriptS1!='' && d.TranscriptS1!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.TranscriptS1+'">'+d.TranscriptS1+'</a>' : '-';
                var IjazahS2 = (d.IjazahS2!='' && d.IjazahS2!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.IjazahS2+'">'+d.IjazahS2+'</a>' : '-';
                var TranscriptS2 = (d.TranscriptS2!='' && d.TranscriptS2!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.TranscriptS2+'">'+d.TranscriptS2+'</a>' : '-';
                var IjazahS3 = (d.IjazahS3!='' && d.IjazahS3!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.IjazahS3+'">'+d.IjazahS3+'</a>' : '-';
                var TranscriptS3 = (d.TranscriptS3!='' && d.TranscriptS3!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.TranscriptS3+'">'+d.TranscriptS3+'</a>' : '-';
                var SP_Dosen = (d.SP_Dosen!='' && d.SP_Dosen!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.SP_Dosen+'">'+d.SP_Dosen+'</a>' : '-';
                var SK_Dosen = (d.SK_Dosen!='' && d.SK_Dosen!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.SK_Dosen+'">'+d.SK_Dosen+'</a>' : '-';
                var SK_Pangkat = (d.SK_Pangkat!='' && d.SK_Pangkat!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.SK_Pangkat+'">'+d.SK_Pangkat+'</a>' : '-';
                var SK_JJA = (d.SK_JJA!='' && d.SK_JJA!=null) ? '<a target="_blank" href="'+base_url_js+'uploads/files/'+d.SK_JJA+'">'+d.SK_JJA+'</a>' : '-';


                $('#viewKTP').html(KTP);
                $('#viewCV').html(CV);
                $('#viewIjazahS1').html(IjazahS1);
                $('#viewTranscriptS1').html(TranscriptS1);
                $('#viewIjazahS2').html(IjazahS2);
                $('#viewTranscriptS2').html(TranscriptS2);
                $('#viewIjazahS3').html(IjazahS3);
                $('#viewTranscriptS3').html(TranscriptS3);
                $('#viewSP_Dosen').html(SP_Dosen);
                $('#viewSK_Dosen').html(SK_Dosen);
                $('#viewSK_Pangkat').html(SK_Pangkat);
                $('#viewSK_JJA').html(SK_JJA);



                var btnKTP = (d.KTP!='' && d.KTP!=null) ? false : true;
                var btnCV = (d.CV!='' && d.CV!=null) ? false : true;
                var btnIjazahS1 = (d.IjazahS1!='' && d.IjazahS1!=null) ? false : true;
                var btnTranscriptS1 = (d.TranscriptS1!='' && d.TranscriptS1!=null) ? false : true;
                var btnIjazahS2 = (d.IjazahS2!='' && d.IjazahS2!=null) ? false : true;
                var btnTranscriptS2 = (d.TranscriptS2!='' && d.TranscriptS2!=null) ? false : true;
                var btnIjazahS3 = (d.IjazahS3!='' && d.IjazahS3!=null) ? false : true;
                var btnTranscriptS3 = (d.TranscriptS3!='' && d.TranscriptS3!=null) ? false : true;
                var btnSP_Dosen = (d.SP_Dosen!='' && d.SP_Dosen!=null) ? false : true;
                var btnSK_Dosen = (d.SK_Dosen!='' && d.SK_Dosen!=null) ? false : true;
                var btnSK_Pangkat = (d.SK_Pangkat!='' && d.SK_Pangkat!=null) ? false : true;
                var btnSK_JJA = (d.SK_JJA!='' && d.SK_JJA!=null) ? false : true;


                $('#btnDelete_KTP').prop('disabled',btnKTP).attr('data-file',d.KTP);
                $('#btnDelete_CV').prop('disabled',btnCV).attr('data-file',d.CV);
                $('#btnDelete_IjazahS1').prop('disabled',btnIjazahS1).attr('data-file',d.IjazahS1);
                $('#btnDelete_TranscriptS1').prop('disabled',btnTranscriptS1).attr('data-file',d.TranscriptS1);
                $('#btnDelete_IjazahS2').prop('disabled',btnIjazahS2).attr('data-file',d.IjazahS2);
                $('#btnDelete_TranscriptS2').prop('disabled',btnTranscriptS2).attr('data-file',d.TranscriptS2);
                $('#btnDelete_IjazahS3').prop('disabled',btnIjazahS3).attr('data-file',d.IjazahS3);
                $('#btnDelete_TranscriptS3').prop('disabled',btnTranscriptS3).attr('data-file',d.TranscriptS3);
                $('#btnDelete_SP_Dosen').prop('disabled',btnSP_Dosen).attr('data-file',d.SP_Dosen);
                $('#btnDelete_SK_Dosen').prop('disabled',btnSK_Dosen).attr('data-file',d.SK_Dosen);
                $('#btnDelete_SK_Pangkat').prop('disabled',btnSK_Pangkat).attr('data-file',d.SK_Pangkat);
                $('#btnDelete_SK_JJA').prop('disabled',btnSK_JJA).attr('data-file',d.SK_JJA);


            });
        }
    }

    $('.upload_files').change(function () {

        var formEmployees = $('#formEmployees').val();
        var input = this;

        if(formEmployees!='' && formEmployees!=null && input.files && input.files[0]){
            var NIP = formEmployees;
            //var fm = $(this).attr('data-fm');
            //var type = fm.split('tagFM_')[1];
            var type = $("#typefiles option:selected").attr("id")
            //alert(type);

            var sz = parseFloat(input.files[0].size) / 1000000; // ukuran MB
            var ext = input.files[0].type.split('/')[1];

            var ds = true;
            if(Math.floor(sz)<=8){
                ds = false;

                var fileName = type+'_'+NIP+'.'+ext;
                var formData = new FormData( $("#form2Upload")[0]);
                var url = base_url_js+'human-resources/employees/upload_files?fileName='+fileName+'&c='+type+'&u='+NIP;

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
                        loadDataEmployees();

                        // var jsonData = JSON.parse(data);

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
                alert('Maksimum size 8 Mb');
            }

        } else {
            toastr.error('Please, Select user before Upload File !','Eror!');
        }


    });

    $('.btnDelete').click(function () {

        var formEmployees = $('#formEmployees').val();
        var ID = $(this).attr('id');
        var colom = ID.split('btnDelete_')[1];
        var files = $(this).attr('data-file');

        if(formEmployees!='' && formEmployees!=null && files!='' && files!=null){
            if(confirm('Remove data?')){
                var url = base_url_js+'human-resources/employees/remove_files?fileName='+files+
                    '&user='+formEmployees.trim()+'&colom='+colom;
                $.get(url,function (result) {
                    toastr.success('Data Removed','Success');
                    loadDataEmployees();
                });
            }
        }



    });

</script>