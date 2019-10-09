
<div class="row">
            <div class="col-md-6" style="border-right: 1px solid #afafafb5;">

                    <div class="col-xs-12" id="subsesi">
                        <div class="form-group">
                            <div class="thumbnail" style="padding: 10px;text-align: left;">
                                <h4>Data Academic Transcript S3 </h4>
                               
                                <div class="row"> 
                                    
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label style="display: block"> Name Univesity   <a href="javascript:void(0);" class="btnNameUniversity" style="text-align: right; float:right;"><span class="label label-pill label-primary"><span class="fa fa-plus-circle"></span> University</span></a> </label>
                                            <select class="select2-select-00 form-exam formNameUnivS3" id="formNameUnivS3" style="width: 100%;" size="5"><option value=""></option></select>
                                           
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>No. Ijazah S3</label>
                                            <input class="form-control" id="formNoIjazahS3">
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Date & Year Ijazah</label>
                                            <input class="form-control datepicker" id="formIjazahDate" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label style="display: block">Name Major <a href="javascript:void(0);" class="btnAddMajor" style="text-align: right; float:right;"><span class="label label-pill label-primary"><span class="fa fa-plus-circle"></span> Major </span></a></label>
                                            <select class="select2-select-00 form-exam formMajorS3" id="formMajorS3" style="width: 100%;" size="5"><option value=""></option></select>
                                           
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                             <label style="display: block">Program Study <a href="javascript:void(0);" class="btnAddMajor" style="text-align: right; float:right;"><span class="label label-pill label-primary"><span class="fa fa-plus-circle"></span> Program Study </span></a> </label>
                                              <select class="select2-select-00 form-exam formStudyS3" id="formStudyS3" style="width: 100%;" size="5"><option value=""></option></select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Grade/ IPK</label>
                                            <input class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" id="gradeS3" maxlength="4">
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Total Credit</label>
                                            <input class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" id="totalCreditS3" maxlength="3">
                                        </div>
                                    </div>

                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Total Semester</label>
                                                <div class="input-group number-spinner">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>
                                                </span>
                                                <input type="text" class="form-control text-center" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" id="TotSemesterS3" value="0" maxlength="2">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" data-dir="up"><span class="glyphicon glyphicon-plus"></span></button>
                                                    </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Ijazah</label>
                                            <form id="tagFM_IjazahS3" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                                        <div class="form-group">
                                                            <label class="btn btn-sm btn-default btn-default-warning btn-upload">
                                                                <i class="fa fa-upload margin-right"></i> Upload File
                                                                <input type="file" id="fileIjazah" name="userfile" class="upload_files" style="display: none;" data-fm="tagFM_IjazahS1" accept="application/pdf">
                                                            </label>
                                                    <p style="font-size: 12px;color: #FF0000;">*) Only PDF Files Max Size 5 MB</p>
                                            </div></form>
                                            <div id="element1">Review Ijazah : </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Transcript</label>
                                            <form id="tagFM_TranscriptS3" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                                    <input id="formPhoto" class="hide" value="" hidden />
                                                        <div class="form-group">
                                                            <label class="btn btn-sm btn-default btn-default-warning btn-upload">
                                                                <i class="fa fa-upload margin-right"></i> Upload File
                                                                <input type="file" id="fileTranscript" name="userfile" class="uploadPhotoEmp" style="display: none;" accept="application/pdf">
                                                        </label>
                                                 <p style="font-size: 12px;color: #FF0000;">*) Only PDF Files Max Size 5 MB</p>
                                            </div></form>
                                            <div id="element2">Review Transcript : </div>
                                        </div>
                                    </div>

                                  
                                </div>
                                <div class="row">
                                   <div class="col-md-12" style="text-align: right;">
                                            <hr/>
                                            <button class="btn btn-success btn-round btnSave3"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
                                        </div> 
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- <span id="bodyAddSesi"></span> -->
            </div>

            <span id="loadtablefiles3"></span> 
 </div>

 <script>
    $(document).on('click','.testEdit3', function () {
      
        var NIP = '<?php echo $NIP; ?>';
        var acad = 'S3';
        var fileijazah = $(this).attr('listid_ijazah');
        var filetranscript = $(this).attr('listid_transcript');

        var url = base_url_js+'api/__getdataedits1?n='+NIP+'&j='+fileijazah+'&t='+filetranscript+'&s='+acad;              
        var token = jwt_encode({
            action:'read',
            NIP:NIP
        },'UAP)(*');

        $.post(url,{token:token},function (resultJson) {

                var response = resultJson;
                if(response.length>0){
                    var no = 1;
                    for (var i = 0; i < response.length; i++) {
                        var nameuniv1 = response[i]['Name_University'];

                        for (var j = i+1; j < response.length; j++) {
                            var nameuniv2 = response[j]['Name_University'];
                            
                            if (nameuniv1 == nameuniv2) {
                                if (response[j]['LinkFiles'] != null) {
                                        //var Transcriptx = '<iframe src="'+base_url_js+'uploads/files/'+response[j]['LinkFiles']+'" style="width:200px; height:100px;" frameborder="0"></iframe> <br/><center><button id="btnviewIjazahS1" class="btn btn-sm btn-primary" filesub ="'+response[j]['LinkFiles']+'"><i class="fa fa-eye"></i> View </button></center>';
                                        var Transcriptx = response[j]['LinkFiles'];
                                    } else {
                                        var Transcriptx = '<img src="<?php echo base_url('images/icon/userfalse.png'); ?>" style="width:200px; height:100px">'
                                }

                            }

                    $('#NotificationModal .modal-body').html('<br/><div class="col-xs-12 id="subsesi">      '+
                        '<div class="form-group">                                                   '+
                        '    <div class="thumbnail" style="padding: 10px;text-align: left;">        '+
                        '      <h4>Edit Academic Transcript S3 </h4>                           '+
                        '       <div class="row">                                                   '+
                        '          <div class="col-xs-12">                                          '+
                        '               <div class="form-group">                                    '+
                        '                   <label>Name Univesity</label>                           '+
                        '                   <select class="select2-select-00 form-exam formNameUnivS3" id="formNameUnivS3" style="width: 100%;" size="5"></select>'+
                        '               </div>                                                      '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>No. Ijazah S1</label>                           '+
                        '                    <input class="form-control" id="formNoIjazahS3" value="'+response[i]['NoIjazah']+'">       '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+     
                        '                <div class="form-group">                                   '+
                        '                    <label>Date & Year Ijazah</label>                      '+
                        '                    <input class="form-control editdatepicker" id="formEditIjazahDate" autocomplete="off" value="'+response[i]['DateIjazah']+'">Format : YYYY-MM-DD '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>Name Major</label>                                   '+
                        '                    <select class="select2-select-00 form-exam formMajorS3" id="formMajorS3" style="width: 100%;" size="5"><option value="'+response[i]['Major']+'">'+response[i]['NamaJurusan']+'</option></select>           '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>Program Study </label>                          '+
                        '                    <select class="select2-select-00 form-exam formStudyS3" id="formStudyS3" style="width: 100%;" size="5"><option value="'+response[i]['ProgramStudy']+'">'+response[i]['NamaProgramStudi']+'</option></select>           '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                    '+
                        '                    <label>Grade/ IPK</label>                               '+
                        '                    <input class="form-control" id="gradeS3" maxlength="4" value="'+response[i]['Grade']+'"> '+
                        '                </div>                                                      '+
                        '            </div>                                                          '+
                        '            <div class="col-xs-3">                                          '+
                        '                <div class="form-group">                                    '+
                        '                    <label>Total Credit</label>                             '+
                        '                    <input class="form-control" id="totalCreditS3" maxlength="3" value="'+response[i]['TotalCredit']+'"> '+
                        '                </div>                                                         '+
                        '            </div>                                                             '+
                        '            <div class="col-xs-3">                                             '+
                        '               <div class="form-group">                                        '+ 
                        '                    <label>Total Semester</label>                              '+
                        '                        <div class="input-group number-spinner">               '+
                        '                        <span class="input-group-btn">                         '+
                        '                            <button class="btn btn-default" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button> '+
                        '                        </span>                                                '+
                        '                        <input type="text" class="form-control text-center" id="TotSemesterS3" value="'+response[i]['TotalSemester']+'" > '+
                        '                           <span class="input-group-btn"> '+
                        '                               <button class="btn btn-default" data-dir="up"><span class="glyphicon glyphicon-plus"></span></button> '+
                        '                           </span>                                             '+
                        '                   </div>                                                      '+
                        '               </div>                                                          '+
                        '           </div>                                                              '+
                        '      <div class="col-xs-6">                                              '+
                        '            <div class="form-group">                                            '+
                        '               <label>Ijazah</label>                                           '+
                        '                   <div><iframe src="'+base_url_js+'uploads/files/'+response[i]['LinkFiles']+'" style="width:300px; height:150px;" frameborder="0"></iframe> <br/><center><button class="btn btn-sm btn-primary btn-round btnviewlistsrata" filesub ="'+response[i]['LinkFiles']+'"><i class="fa fa-eye"></i> Preview </button></center>                  '+
                        '                  </div>  '+
                        '            </div>  '+
                        '        <form id="e_tagFM_IjazahS1" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">  '+
                        '            <div class="form-group">  '+
                        '               <label class="btn btn-sm btn-default btn-upload">  '+
                        '                  <i class="fa fa-upload margin-right"></i> Change Upload File  '+
                        '                 <input type="file" id="e_fileIjazah" name="userfile" class="upload_files" data-fm="tagFM_IjazahS1" accept="application/pdf">  '+
                        '               </label>  '+
                        '               <p style="font-size: 12px;color: #FF0000;">*) Only PDF Files Max Size 5 MB</p>  '+
                        '           </div> '+
                        '        </form> ' +
                        '      </div>                                                              '+
                        '      <div class="col-xs-6">                                              '+
                        '           <div class="form-group">                                            '+
                        '               <label>Transcript</label>                                       '+
                        '                   <div><iframe src="'+base_url_js+'uploads/files/'+response[j]['LinkFiles']+'" style="width:300px; height:150px;" frameborder="0"></iframe> <br/><center><button class="btn btn-sm btn-primary btn-round btnviewlistsrata" filesub ="'+response[j]['LinkFiles']+'"><i class="fa fa-eye"></i> Preview </button></center></div>                 '+
                        '                   </div>                                                         '+
                        '            </div> '+
                        '            <form id="e_tagFM_TranscriptS1" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">  '+
                        '                 <div class="form-group">  '+
                        '                    <label class="btn btn-sm btn-default btn-upload">  '+
                        '                      <i class="fa fa-upload margin-right"></i> Change Upload File   '+
                        '                    <input type="file" id="e_fileTranscript" name="userfile" class="upload_filestra" data-fm="tagFM_IjazahS1" accept="application/pdf">  '+
                        '                    </label>  '+
                        '                    <p style="font-size: 12px;color: #FF0000;">*) Only PDF Files Max Size 5 MB</p>  '+
                        '                  </div> '+
                        '             </form> ' +
                        '       </div>                                                                 '+
                        '       <div class="row">                                                      '+
                        '           <div class="col-md-12" style="text-align: right;">                   '+
                        '            <hr/>                                                           '+
                        '           <div><input type="hidden" class="form-control" value="'+response[i]['LinkFiles']+'" id="linkijazahs1"> </div>          '+
                        '           <div><input type="hidden" class="form-control" value="'+response[j]['LinkFiles']+'" id="linktranscripts1">    </div>   '+

                        '           <div><input type="hidden" class="form-control" value="'+response[i]['ID']+'" id="id_linkijazahs1"> </div>              '+
                        '           <div><input type="hidden" class="form-control" value="'+response[j]['ID']+'" id="id_linktranscripts1">    </div>       '+

                        '           <div class="btn-group">   '+ 
                        '                  <button type="button" class="btn btn-danger btn-round" data-dismiss="modal"> <i class="fa fa-remove"></i>Cancel</button> '+ 
                        '                  <button type="button" class="btn btn-success btn-round btnSavedits3" id_linkijazahs1="'+response[i]['ID']+'" id_linktranscripts1="'+response[j]['ID']+'"> <i class="glyphicon glyphicon-floppy-disk"></i> Save</button> '+ 
                        '                   </div>  '+ 
                        '           </div> '+ 

                        '           </div>                                                              '+
                        '       </div>                                                                  '+
                        '   </div>                                                                      '+
                        '</div>                                                                         '+
                    '</div>');

                    $('.editdatepicker').datepicker({
                        dateFormat : 'yy-mm-dd',
                        changeMonth : true,
                        changeYear : true,
                        autoclose: true,
                        todayHighlight: true,
                        uiLibrary: 'bootstrap'
                    });
                    loadSelectOptionUniversity('#formNameUnivS3',''+response[i]['NameUniversity']+'');
                    $('#formNameUnivS3').select2({allowClear: true});

                    loadSelectOptionMajorEmployees('#formMajorS3',''+response[i]['Major']+'','');
                    $('#formMajorS3').select2({allowClear: true});

                    loadSelectOptionProgramStudyEmployees('#formStudyS3',''+response[i]['ProgramStudy']+'','');
                    $('#formStudyS3').select2({allowClear: true});
                    
                    $('#NotificationModal').modal({
                        'backdrop' : 'static',
                        'show' : true
                    }); 
                    i = j;
                    break;
                        }    
                    } //end for
                } //end if
            }); //end json
    });

</script>

 <script>
      $(document).on('click','.btnAddMajor', function () {
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Master Major/ Program Study </h4>');

        var body = '<div class="row">' +
            '    <div class="col-md-5">' +
            '        <div class="well">' +
            '            <div class="form-group">' +
            '                <input class="form-control" id="master_namemajor" placeholder="Name Major/ program Study...">' +
            '            </div>' +
            '            <div style="text-align:right;">' +
            '                <button class="btn btn-success btn-round" id="btnSavemajor"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    ' +
            '    <div id="viewDataMajorProgram" class="col-md-7 table-responsive">' +
            '    </div>' +
            '</div>';
        $('#GlobalModal .modal-body').html(body);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-primary btn-round" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
        loadProgramStudyEmployee();
    });
    
    $(document).on('click','.btnNameUniversity', function () {
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Master University </h4>');

        var body = '<div class="row">' +
            '    <div class="col-md-5">' +
            '        <div class="well">' +
            '            <div class="form-group">' +
            '                <input class="hide" id="formID">' +
            '                <input class="form-control" id="master_codeuniv" placeholder="Code University Dikti...">' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <input class="form-control" id="master_nameuniv" placeholder="Name University...">' +
            '            </div>' +
            '            <div style="text-align:right;">' +
            '                <button class="btn btn-success btn-round" id="btnSaveLembaga"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    ' +
            '    <div id="viewData23" class="col-md-7 table-responsive">' +
            '    </div>' +
            '</div>';
        $('#GlobalModal .modal-body').html(body);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-primary btn-round" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
        loadDataUniversity();
    });
 </script>

 <script>
     $(document).ready(function () {
        loadAcademicS3Details();
        loadforms3();

        loadSelectOptionUniversity('#formNameUnivS3','');
        $('#formNameUnivS3').select2({allowClear: true});

        loadSelectOptionMajorEmployees('#formMajorS3','');
        $('#formMajorS3').select2({allowClear: true});

        loadSelectOptionProgramStudyEmployees('#formStudyS3','');
        $('#formStudyS3').select2({allowClear: true});
    });


    function loadforms3() {
        $("#formNameUnivS3").select2("val", "");
        $("#formMajorS3").select2("val", "");
        $("#formStudyS3").select2("val", "");

        $('#formNoIjazahS3').val('');
        $('#gradeS3').val('');
        $('#totalCreditS3').val('');
        $('#formIjazahDate').val('');
        $('#TotSemesterS3').val('');
        $('#fileIjazah').val('');
        $('#fileTranscript').val('');
    }

    function loadAcademicS3Details() {
        
        var NIP = '<?php echo $NIP; ?>';
        var srata = 'S3';
        var url = base_url_js+'api/__reviewacademics1?NIP='+NIP+'&s='+srata;
        var token = jwt_encode({
            action:'read',
            NIP:NIP},'UAP)(*');

        $.post(url,{token:token},function (resultJson) {
            var response = resultJson;
            //console.log(response);
                $("#loadtablefiles3").append(
                    ' <div class="table-responsive">                                                '+
                    '     <table class="table table-striped table-bordered">                        '+
                    '         <thead>                                                               '+
                    '         <tr style="background: #20485A;color: #FFFFFF;">                      '+
                     '            <th style="width: 2%;text-align: center;">Academic</th>           '+
                    '             <th style="width: 10%;text-align: center;">Name University</th>   '+
                    '             <th style="width: 8%;text-align: center;">Name Major</th>         '+
                    '             <th style="width: 10%;text-align: center;">Preview File</th>      '+
                    '             <th style="text-align: center;width: 8%;">Action</th>             '+
                    '         </tr>                                                                 '+
                    '         </thead>                                                              '+
                    '         <tbody id="dataRow"></tbody>                                          '+
                    '    </table>                                                                   '+
                    '</div> ');  

            if(response.length > 0){
                var no = 1;
                var orbs=0;
                for (var i = 0; i < response.length; i++) {
                    var listdatas1 = response[i]['LinkFiles']; 
                     var iddata1 = response[i]['ID']; 

                    for (var j = i+1; j < response.length; j++) {
                        if (response[i]['NIP'] == response[j]['NIP'] && response[i]['NameUniversity'] == response[j]['NameUniversity']) {
                            var listdatas2 = response[j]['LinkFiles'];
                            var iddata2 = response[j]['ID']; 
                        }
                        else
                        {
                            i = j-1;
                            break;
                        }  
                       i = j;
                    }

                    $("#dataRow").append('<tr>                                                          '+
                    '            <td> S3 </td>                                                          '+         
                    '            <td> '+response[i]['Name_University']+' </td>                           '+   
                    '            <td> '+response[i]['NamaJurusan']+' </td>                              '+                           
                    '       <td><center><div class="btn-group">   '+ 
                    '          <button type="button" class="btn btn-sm btn-primary btn-round btnviewlistsrata" filesub="'+listdatas1+'"> <i class="fa fa-eye margin-right"></i>Ijazah</button> '+ 
                    '          <button type="button" class="btn btn-sm btn-success btn-round btnviewlistsrata" filesub="'+listdatas2+'"> <i class="fa fa-eye margin-right"></i> Transcript</button> '+ 
                    '          </div>  '+ 
                    '          </center></td>    '+   
                   '       <td style="text-align: center;"><button class="btn btn-sm btn-primary btn-circle testdetail3" data-toggle="tooltip" data-placement="top" title="Edit" listid_ijazah ="'+iddata1+'" listid_transcript ="'+iddata2+'"><i class="icon-list icon-large"></i></button>   <button class="btn btn-sm btn-primary btn-circle testEdit3" data-toggle="tooltip" data-placement="top" title="Edit" listid_ijazah ="'+iddata1+'" listid_transcript ="'+iddata2+'"><i class="fa fa-edit"></i></button>   <button class="btn btn-danger btn-circle btndelist" data-toggle="tooltip" data-placement="top" title="Delete" listid_ijazah ="'+iddata1+'" listid_transcript ="'+iddata2+'"><i class="fa fa-trash"></i></button></td>      '+      
                    '         </tr> ');
                }
                
             }

        });
    };

 </script>

 <script>
     $(document).ready(function () {
    $('.datepicker').datepicker({
      dateFormat : 'yy-mm-dd',
      changeMonth : true,
      changeYear : true,
      autoclose: true,
      todayHighlight: true,
      uiLibrary: 'bootstrap'
    });
});

$(document).ready(function () {
    $('.formEditIjazahDate').datepicker({
      dateFormat : 'yy-mm-dd',
      changeMonth : true,
      changeYear : true,
      autoclose: true,
      todayHighlight: true,
      uiLibrary: 'bootstrap'
    });
});

    $(document).on('click', '.number-spinner button', function () {    
        var btn = $(this),
        oldValue = btn.closest('.number-spinner').find('input').val().trim(),
        newVal = 0;
    
    if (btn.attr('data-dir') == 'up') {
        newVal = parseInt(oldValue) + 1;
        } else {
            if (oldValue > 1) {
            newVal = parseInt(oldValue) - 1;
            } else {
            newVal = 1;
        }
    }
        btn.closest('.number-spinner').find('input').val(newVal);
    });
 </script>

<script>
    
</script>

 <script type="text/javascript">
    $('#fileIjazah').change(function (event) {
        var oFile = document.getElementById("fileIjazah").files[0]; 
        if (oFile.size > 5242880) {  // 5 mb for bytes.
                toastr.error('File Max size 5 MB!','Error');
                $('#NotificationModal').modal('hide');
                //return;
        } else {
            $('#element1').empty();
            var file = URL.createObjectURL(event.target.files[0]);
            $('#element1').append('<br/><iframe src="' + file + '" style="width:200px; height:100;" frameborder="0"></iframe>' );
        }
    });
    $('#fileTranscript').change(function (event) {
        var xFile = document.getElementById("fileTranscript").files[0]; 

        if (xFile.size > 5242880) {  // 5 mb for bytes.
            toastr.error('File Max size 5 MB!','Error');
            return;
        } else {
            $('#element2').empty();
            var file = URL.createObjectURL(event.target.files[0]);
            $('#element2').append('<br/><iframe src="' + file + '" style="width:200px; height:100;" frameborder="0"></iframe>');
        }
    });

    $('.upload_files1').change(function (event) {
        var xFile = document.getElementById("fileIjazah").files[0]; 

        if (xFile.size > 5242880) {  // 5 mb for bytes.
            toastr.error('File Max size 5 MB!','Error');
            return;
        } else {
            $('#element2').empty();
            var file = URL.createObjectURL(event.target.files[0]);
            $('#element2').append('<br/><iframe src="' + file + '" style="width:200px; height:100;" frameborder="0"></iframe>');
        }
    });
    $('.upload_files2').change(function (event) {
        var xFile = document.getElementById("fileTranscript").files[0]; 

        if (xFile.size > 5242880) {  // 5 mb for bytes.
            toastr.error('File Max size 5 MB!','Error');
            return;
        } else {
            $('#element2').empty();
            var file = URL.createObjectURL(event.target.files[0]);
            $('#element2').append('<br/><iframe src="' + file + '" style="width:200px; height:100;" frameborder="0"></iframe>');
        }
    });
    
 </script>

 <script>

    $(document).on('click','.testdetail3', function () {
      
        var NIP = '<?php echo $NIP; ?>';
        var acad = 'S3';
        var fileijazah = $(this).attr('listid_ijazah');
        var filetranscript = $(this).attr('listid_transcript');

        var url = base_url_js+'api/__getdataedits1?n='+NIP+'&j='+fileijazah+'&t='+filetranscript+'&s='+acad;              
        var token = jwt_encode({
            action:'read',
            NIP:NIP
        },'UAP)(*');

        $.post(url,{token:token},function (resultJson) {

                var response = resultJson;
                if(response.length>0){
                    var no = 1;
                    for (var i = 0; i < response.length; i++) {
                        var nameuniv1 = response[i]['Name_University'];

                        for (var j = i+1; j < response.length; j++) {
                            var nameuniv2 = response[j]['Name_University'];
                            
                            if (nameuniv1 == nameuniv2) {
                                if (response[j]['LinkFiles'] != null) {
                                        //var Transcriptx = '<iframe src="'+base_url_js+'uploads/files/'+response[j]['LinkFiles']+'" style="width:200px; height:100px;" frameborder="0"></iframe> <br/><center><button id="btnviewIjazahS1" class="btn btn-sm btn-primary" filesub ="'+response[j]['LinkFiles']+'"><i class="fa fa-eye"></i> View </button></center>';
                                        var Transcriptx = response[j]['LinkFiles'];
                                    } else {
                                        var Transcriptx = '<img src="<?php echo base_url('images/icon/userfalse.png'); ?>" style="width:200px; height:100px">'
                                }

                            }

                            if(response[i]['NameUniversity'] == null) {
                                var Univnames = "";
                            } 
                            else {
                                var Univnames = response[i]['NameUniversity'];
                            }

                    $('#NotificationModal .modal-body').html('<br/><div class="col-xs-12 id="subsesi">      '+
                        '<div class="form-group">                                                   '+
                        '    <div class="thumbnail" style="padding: 10px;text-align: left;">        '+
                        '      <h4>Detail Academic Transcript S3 </h4>                           '+
                        '       <div class="row">                                                   '+
                        '          <div class="col-xs-12">                                          '+
                        '               <div class="form-group">                                    '+
                        '                   <label>Name Univesity</label>                           '+
                        '                   <select class="select2-select-00 form-exam formNameUnivS1" id="formNameUnivS1" style="width: 100%;" size="5" disabled><option></option></select>'+
                        '               </div>                                                      '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>No. Ijazah</label>                           '+
                        '                    <input class="form-control" id="formNoIjazahS1" value="'+response[i]['NoIjazah']+'" disabled>       '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+     
                        '                <div class="form-group">                                   '+
                        '                    <label>Date & Year Ijazah</label>                      '+
                        '                    <input class="form-control editdatepicker" id="formEditIjazahDate" autocomplete="off" value="'+response[i]['DateIjazah']+'" disabled>Format : YYYY-MM-DD '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>Name Major</label>                                   '+
                        '                    <select class="select2-select-00 form-exam formMajorS1" id="formMajorS1" style="width: 100%;" size="5" disabled><option value="'+response[i]['Major']+'">'+response[i]['NamaJurusan']+'</option></select>           '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>Program Study </label>                          '+
                        '                    <select class="select2-select-00 form-exam formStudyS1" id="formStudyS1" style="width: 100%;" size="5" disabled><option value="'+response[i]['ProgramStudy']+'">'+response[i]['NamaProgramStudi']+'</option></select>           '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                    '+
                        '                    <label>Grade/ IPK</label>                               '+
                        '                    <input class="form-control" id="gradeS1" maxlength="4" value="'+response[i]['Grade']+'" disabled> '+
                        '                </div>                                                      '+
                        '            </div>                                                          '+
                        '            <div class="col-xs-3">                                          '+
                        '                <div class="form-group">                                    '+
                        '                    <label>Total Credit</label>                             '+
                        '                    <input class="form-control" id="totalCreditS1" maxlength="3" value="'+response[i]['TotalCredit']+'" disabled> '+
                        '                </div>                                                         '+
                        '            </div>                                                             '+
                        '            <div class="col-xs-3">                                             '+
                        '               <div class="form-group">                                        '+ 
                        '                    <label>Total Semester</label>                              '+
                        '                        <div class="input-group number-spinner">               '+
                        '                        <span class="input-group-btn">                         '+
                        '                            <button class="btn btn-default" data-dir="dwn" disabled><span class="glyphicon glyphicon-minus"></span></button> '+
                        '                        </span>                                                '+
                        '                        <input type="text" class="form-control text-center" id="TotSemesterS1" value="'+response[i]['TotalSemester']+'" disabled> '+
                        '                           <span class="input-group-btn"> '+
                        '                               <button class="btn btn-default" data-dir="up" disabled><span class="glyphicon glyphicon-plus"></span></button> '+
                        '                           </span>                                             '+
                        '                   </div>                                                      '+
                        '               </div>                                                          '+
                        '           </div>                                                              '+
                        '      <div class="col-xs-6">                                              '+
                        '            <div class="form-group">                                            '+
                        '               <label>Ijazah</label>                                           '+
                        '                   <div><iframe src="'+base_url_js+'uploads/files/'+response[i]['LinkFiles']+'" style="width:300px; height:150px;" frameborder="0"></iframe> <br/><center><button class="btn btn-sm btn-primary btn-round btnviewlistsrata" filesub ="'+response[i]['LinkFiles']+'"><i class="fa fa-eye"></i> Preview </button></center>                  '+
                        '                  </div>  '+
                        '            </div>  '+
                        '      </div>                                                              '+
                        '      <div class="col-xs-6">                                              '+
                        '           <div class="form-group">                                            '+
                        '               <label>Transcript</label>                                       '+
                        '                   <div><iframe src="'+base_url_js+'uploads/files/'+response[j]['LinkFiles']+'" style="width:300px; height:150px;" frameborder="0"></iframe> <br/><center><button class="btn btn-sm btn-primary btn-round btnviewlistsrata" filesub ="'+response[j]['LinkFiles']+'"><i class="fa fa-eye"></i> Preview </button></center></div>                 '+
                        '                   </div>                                                         '+
                        '            </div> '+
                        '       </div>                                                                 '+
                        '       <div class="row">                                                      '+
                        '           <div class="col-md-12" style="text-align: right;">                   '+
                        '            <hr/>                                                           '+
                        '           <div><input type="hidden" class="form-control" value="'+response[i]['LinkFiles']+'" id="linkijazahs1"> </div>                   '+
                        '           <div><input type="hidden" class="form-control" value="'+response[j]['LinkFiles']+'" id="linktranscripts1">    </div>       '+

                        '           <div><input type="hidden" class="form-control" value="'+response[i]['ID']+'" id="id_linkijazahs1"> </div>                   '+
                        '           <div><input type="hidden" class="form-control" value="'+response[j]['ID']+'" id="id_linktranscripts1">    </div>       '+

                        '           <button type="button" class="btn btn-danger btn-round" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>                                                               '+
                        '           </div>                                                              '+
                        '       </div>                                                                  '+
                        '   </div>                                                                      '+
                        '</div>                                                                         '+
                    '</div>');

                    $('.editdatepicker').datepicker({
                        dateFormat : 'yy-mm-dd',
                        changeMonth : true,
                        changeYear : true,
                        autoclose: true,
                        todayHighlight: true,
                        uiLibrary: 'bootstrap'
                    });
                    loadSelectOptionUniversity('#formNameUnivS1',''+Univnames+'');
                    $('#formNameUnivS1').select2({allowClear: true});

                    loadSelectOptionMajorEmployees('#formMajorS1',''+response[i]['Major']+'','');
                    $('#formMajorS1').select2({allowClear: true});

                    loadSelectOptionProgramStudyEmployees('#formStudyS1',''+response[i]['ProgramStudy']+'','');
                    $('#formStudyS1').select2({allowClear: true});
                    
                    $('#NotificationModal').modal({
                        'backdrop' : 'static',
                        'show' : true
                    }); 
                    i = j;
                    break;
                        }    
                    } //end for
                } //end if
            }); //end json
    });

</script>

 

