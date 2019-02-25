<script>
    $(function(){
        
        function loadData(args) {
            //code
            $("#tampil").load("<?php echo base_url('human-resources/files_reviews');?>");
        }
        loadData();
        //var url = base_url_js+'api/__crudCourseOfferings';
        
        function kosong(args) {
            //code
            //$("#kode").val('');
            //$("#judul").val('');
            //$("#pengarang").val('');
        }
        
    })
</script>


<div class="panel panel-primary">
    <div class="panel-heading" style="border-radius: 0px;">
        <h4 class="header">Add Academic Employees</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;">

    	<div class="col-md-4 col-md-offset-4">
        <div class="well">
            <select class="select2-select-00" style="max-width: 100% !important;" size="5" id="formEmployees">
                <option value=""></option>
            </select>
        </div>
    	</div>

    	<table class="table">
                <tr>
                    <td rowspan="3" style="width:10%;text-align: center;" id="viewPhoto">
                        -
                    </td>
                </tr>
                <tr>
                    <td style="width: 10%;">Name</td>
                    <td style="width: 1%;">:</td>
                    <th id="viewName">-</th>
                </tr>
                <tr>
                    <td>NIP</td>
                    <td>:</td>
                    <td id="viewNIP">-</td>
                </tr>
        </table>
        <hr/>
        	<div class="row">
					<label class="col-md-2 control-label">  Select Data Academic:</label>
						<div class="col-md-1">
							<select class="form-control" name="select" id="typeacademic">
									<option id="S1">S1</option>
									<option id="S2">S2</option>
									<option id="S3">S3</option>
							</select>
						</div>
						<button id="addNewSesi" data-group="1" class="btn btn-primary btn-sm"> <span class="fa fa-plus"></span> ADD FORM </button>
			</div> <br/>

        <div class="row">
            <div class="col-md-6" style="border-right: 1px solid #afafafb5;">

                <div class="row">

                     
                    <div class="col-xs-12 trNewSesi1 id="subsesi1">
                        <div class="form-group">
                            <div class="thumbnail" style="padding: 10px;text-align: left;">
                                <h4>Data Academic Transcript S1 </h4>
                               
                                <div class="row"> 
                                	<div class="col-xs-4">
                                    	<div class="form-group">
                                        	<label>No. Ijazah S1</label>
                                        	<input class="form-control" id="formIjazahS1">
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
                                  
                                </div>
                            </div>

                        </div>
                    </div>

                    <span id="bodyAddSesi"></span>

                </div>
            </div>

            <div class="col-md-6">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="well" style="padding: 10px;text-align: center;margin-bottom: 15px;">
                            <h4  class="text-center">Data Files</h4>

                                <div class="form-group">
                                        <label class="col-md-3 control-label">Select Type File </label>
                                        <div class="col-md-6">
                                            <select class="form-control" id="typefiles">
                                                <option id="KTP" selected="selected">Photo KTP</option>
                                                <option id="CV" >Curriculum Vitae</option>
                                                <option id="IjazahS1">Ijazah S1</option>
                                                <option id="IjazahS2">Ijazah S2</option>
                                                <option id="IjazahS2">Ijazah S3</option>
                                                <option id="TranscriptS1">Transcript S1</option>
                                                <option id="TranscriptS2">Transcript S2</option>
                                                <option id="TranscriptS3">Transcript S3</option>
                                                <option id="SP_Dosen">Surat Pernyataan Dosen</option>
                                                <option id="SK_Dosen">SK Dosen</option>
                                                <option id="SK_Pangkat">SK Pangkat</option>
                                                <option id="SK_JJA">SK Jabatan Fungsional</option>
                                            </select>
                                        </div>
                                        <div>
                                            <form id="form2Upload" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                            <div class="form-group">
                                                <label class="btn btn-sm btn-default btn-default-warning btn-upload">
                                                <i class="fa fa-upload margin-right"></i> Upload File
                                                <input type="file" id="fileIjazah" name="userfile" class="upload_files" data-fm="tagFM_KTP"
                                               style="display: none;" accept="application/pdf">
                                               </label>
                                            </div>
                                            </form>
                                        </div>
                                </div>
                                 <hr/>

                                 <div id="tampil"></div>
                            

                            <div id = "AddingProdi">
                                
                            </div>
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

<!-- NEW SESI -->
<script>
    $('#addNewSesi').click(function () {

        window.dataSesi = 1;
        var newSesi = true;
        var typeacademic = $("#typeacademic option:selected").attr("id")
        //alert(newSesi);

        //var Ijazah = $('#formIjazah'+dataSesi).val(); if(Ijazah==''){ newSesi = requiredForm('#formIjazah'+typeacademic+); }
        //var nameuniv = $('#formNameUnivS1'+dataSesi).val(); if(Credit==''){newSesi = requiredForm('#formNameUnivS1'+dataSesi);}
        //var major = $('#formMajorS1'+dataSesi).val(); if(TimePerCredit==''){newSesi = requiredForm('#formMajorS1'+dataSesi);}
        //var Study = $('#formStudyS1'+dataSesi).val(); if(StartSessions==''){newSesi = requiredForm('#formStudyS1'+dataSesi);}
        //var grade = $('#gradeS1'+dataSesi).val(); if(EndSessions==''){newSesi = requiredForm('#gradeS1'+dataSesi);}

        if(newSesi){
            dataSesi = dataSesi + 1;

            $('#subsesi1').removeClass('hide');
            $('#bodyAddSesi').append('<div class="col-xs-12 trNewSesi'+dataSesi+'" id="tes'+dataSesi+'"> ' +
            '            <div class="form-group"> ' +
            '                <div class="thumbnail" style="padding: 10px;text-align: left;"> ' +
            '                   <h4>Data Academic Transcript '+typeacademic+' </h4> ' +
            '                   <div class="row">  ' +
            '                        <div class="col-xs-4"> ' +
            '                            <div class="form-group"> ' +
            '                                <label>No. Ijazah</label> ' +
            '                                <input class="form-control" id="formIjazah'+typeacademic+'"> ' +
            '                            </div> ' +
            '                       </div> ' +
            '                        <div class="col-xs-8"> ' +
            '                            <div class="form-group"> ' +
             '                               <label>Name Univesity</label> ' +
            '                                <input class="form-control" id="formNameUniv'+typeacademic+'"> ' +
            '                            </div> ' +
            '                        </div> ' +
            '                       <div class="col-xs-6"> ' +
            '                           <div class="form-group"> ' +
            '                               <label>Major</label> ' +
            '                               <input class="form-control" id="formMajor'+typeacademic+'"> ' +
            '                           </div> ' +
            '                       </div> ' +
            '                       <div class="col-xs-6"> ' +
            '                           <div class="form-group"> ' +
            '                               <label>Program Study </label> ' +
            '                               <input class="form-control" id="formStudy'+typeacademic+'"> ' +
            '                           </div> ' +
            '                       </div> ' +
            '                       <div class="col-xs-6"> ' +
            '                           <div class="form-group"> ' +
            '                              <label>Grade/ IPK</label> ' +
            '                             <input class="form-control" id="grade'+typeacademic+'"> ' +
            '                        </div> ' +
            '                   </div> ' +
            '                  <div class="col-xs-3"> ' +
            '                     <div class="form-group"> ' +
            '                        <label>Total Credit</label> ' +
            '                         <input class="form-control" id="totalCredit'+typeacademic+'"> ' +
            '                      </div> ' +
            '                   </div> ' +
            '                    <div class="col-xs-3"> ' +
            '                         <div class="form-group"> ' +
            '                              <label>Total Semester</label> ' +
            '                               <input class="form-control" id="TotSemester'+typeacademic+'"> ' +
            '                           </div> ' +
            '                        </div> ' +
            '                      <div class="col-xs-12"> ' +
            '                           <div style="text-align: right;"> ' +
            '                                <div class="form-group"> ' +
            '                                    <button class="btn btn-default btn-default-danger removeNewSesi" data-id="'+dataSesi+'"><i class="fa fa-trash"></i> Delete </button> ' +
            '                                </div> ' +
            '                           </div> ' +
            '                       </div> ' +
            '                    </div> ' +
            '              </div> ' +
            '           </div> ' +
            '        </div> ');

            loadSelectOptionClassroom('#formClassroom'+dataSesi,'');
            fillDays('#formDay'+dataSesi,'Eng','');
            loadSelectOptionTimePerCredit('#formTimePerCredit'+dataSesi,'');

            $('#div_formSesiAwal'+dataSesi).datetimepicker({
                pickDate: false,
                pickSeconds : false
            }).on('changeDate', function(e) {
                var d = new Date(e.localDate);
                var no = $(this).attr('data-no');
                var TimePerCredit = $('#formTimePerCredit'+no).val();
                var Credit = $('#formCredit'+no).val()

                var totalTime = parseInt(TimePerCredit) * parseInt(Credit);

                var sesiAkhir = moment().hours(d.getHours()).minutes(d.getMinutes()).add(parseInt(totalTime), 'minute').format('HH:mm');

                $('#formSesiAkhir'+no).val(sesiAkhir);
                checkSchedule(no);
            });

        } else {
            toastr.warning('Form Sub Sesi '+dataSesi+' Harus Diisi','Warning!');
        }

    });

    $(document).on('click','.removeNewSesi',function() {
        var id=$(this).attr('data-id');
        $('#tes'+id).remove();

    });

    $('#removeNewSesi').click(function () {
        alert(dataSesi);
        if(dataSesi>1){
            alert(dataSesi);
            $('.trNewSesi'+dataSesi).remove();
            dataSesi = dataSesi - 1;
            if(dataSesi==1){
                $('#subsesi1').addClass('hide');
            }
        } else {
            $('#subsesi1').addClass('hide');
            toastr.warning('Belum Ada Sub Sesi','Info');
        }

    });
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