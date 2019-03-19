
<div class="row">
            <div class="col-md-6" style="border-right: 1px solid #afafafb5;">

                    <div class="col-xs-12" id="subsesi">
                        <div class="form-group">
                            <div class="thumbnail" style="padding: 10px;text-align: left;">
                                <h4>Data Academic Transcript S3 </h4>
                               
                                <div class="row"> 
                                    
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label>Name Univesity</label>
                                            <input class="form-control" id="formNameUnivS3">
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
                                            <label>Major</label>
                                            <input class="form-control" id="formMajorS3">
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Program Study </label>
                                            <input class="form-control" id="formStudyS3">
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
                                            <button class="btn btn-success btnSave3">Save</button>
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
     $(document).ready(function () {
        loadAcademicS3Details();
    });

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
                    '         <tr style="background: #607d8b;color: #FFFFFF;">                      '+
                    '             <th style="width: 5%;text-align: center;">Academic</th>           '+
                     '            <th style="width: 10%;text-align: center;">Name University</th>   '+
                    '             <th style="width: 8%;text-align: center;">Ijazah</th>             '+
                    '             <th style="width: 8%;text-align: center;">Transcript</th>         '+
                    '             <th style="text-align: center;width: 5%;">Action</th>             '+
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

                    for (var j = i+1; j < response.length; j++) {
                        if (response[i]['NIP'] == response[j]['NIP'] && response[i]['NameUniversity'] == response[j]['NameUniversity']) {
                            var listdatas2 = response[j]['LinkFiles'];
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
                    '            <td> '+response[i]['NameUniversity']+' </td>                           '+                             
                    '            <td><center><button class="btn btn-sm btn-default btn-default-primary btnviewlistsrata" filesub="'+listdatas1+'"><i class="fa fa-eye margin-right"></i> Preview</button></center></td>                                                '+    
                    '            <td><center><button class="btn btn-sm btn-default btn-default-success btnviewlistsrata" filesub="'+listdatas2+'"><i class="fa fa-eye margin-right"></i> Preview</button> </center></td>                                              '+                                
                    '            <td style="text-align: center;"><button id="btnedits1" class="btn btn-sm btn-primary testEdit3" data-toggle="tooltip" data-placement="top" title="Edit" nameuniv= "'+response[i]['NameUniversity']+'" fileijazahs1 ="'+listdatas1+'" filetranscripts1 ="'+listdatas2+'"><i class="fa fa-edit"></i></button></td>      '+     
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

    $(document).on('click','.testEdit3', function () {

        var NIP = '<?php echo $NIP; ?>';
        var acad = 'S3';
        var fileijazahs1 = $(this).attr('fileijazahs1');
        var filetranscripts1 = $(this).attr('filetranscripts1');
        var nameuniv = $(this).attr('nameuniv');

        if (fileijazahs1 != null) {
            var filesx = '<iframe src="'+base_url_js+'uploads/files/'+fileijazahs1+'" style="width:300px; height:150px;" frameborder="0"></iframe> <br/><center><button class="btn btn-sm btn-primary btnviewlistsrata" filesub ="'+fileijazahs1+'"><i class="fa fa-eye"></i> Preview </button></center>';
            } else {
            var filesx = '<img src="<?php echo base_url('images/icon/nofiles.png'); ?>" style="width:200px; height:100px;">'
        }
        if (filetranscripts1 != null) {
            var filestrans = '<iframe src="'+base_url_js+'uploads/files/'+filetranscripts1+'" style="width:300px; height:150px;" frameborder="0"></iframe> <br/><center><button class="btn btn-sm btn-primary btnviewlistsrata" filesub ="'+filetranscripts1+'"><i class="fa fa-eye"></i> Preview </button></center>';
        } else {
            var filestrans = '<img src="<?php echo base_url('images/icon/nofiles.png'); ?>" style="width:200px; height:100px">'
        }

        if(nameuniv == "null") {
                $('#NotificationModal .modal-body').html('<br/><div class="col-xs-12 id="subsesi">  '+
                        '<div class="form-group">                                                   '+
                        '    <div class="thumbnail" style="padding: 10px;text-align: left;">        '+
                        '      <h4>Edit Academic Transcript S3 </h4>                           '+
                        '       <div class="row">                                                   '+
                        '          <div class="col-xs-12">                                          '+
                        '               <div class="form-group">                                    '+
                        '                   <label>Name Univesity</label>                           '+
                        '                  <input class="form-control" id="formNameUnivS3">         '+
                        '               </div>                                                      '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>No. Ijazah S3</label>                           '+
                        '                    <input class="form-control" id="formNoIjazahS3">       '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+     
                        '                <div class="form-group">                                   '+
                        '                    <label>Date & Year Ijazah</label>                      '+
                        '                    <input class="form-control" id="formEditIjazahDate" autocomplete="off">Format : YYYY-MM-DD '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>Major</label>                                   '+
                        '                    <input class="form-control" id="formMajorS3">          '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>Program Study </label>                          '+
                        '                    <input class="form-control" id="formStudyS3">          '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                    '+
                        '                    <label>Grade/ IPK</label>                               '+
                        '                    <input class="form-control" id="gradeS3" maxlength="4"> '+
                        '                </div>                                                      '+
                        '            </div>                                                          '+
                        '            <div class="col-xs-3">                                          '+
                        '                <div class="form-group">                                    '+
                        '                    <label>Total Credit</label>                             '+
                        '                    <input class="form-control" id="totalCreditS3" maxlength="3"> '+
                        '                </div>                                                         '+
                        '            </div>                                                             '+
                        '            <div class="col-xs-3">                                             '+
                        '               <div class="form-group">                                        '+ 
                        '                    <label>Total Semester</label>                              '+
                        '                        <div class="input-group number-spinner">               '+
                        '                        <span class="input-group-btn">                         '+
                        '                            <button class="btn btn-default" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button> '+
                        '                        </span>                                                '+
                        '                        <input type="text" class="form-control text-center" id="TotSemesterS3" value="0" maxlength="2"> '+
                        '                           <span class="input-group-btn"> '+
                        '                               <button class="btn btn-default" data-dir="up"><span class="glyphicon glyphicon-plus"></span></button> '+
                        '                           </span>                                             '+
                        '                   </div>                                                      '+
                        '               </div>                                                          '+
                        '           </div>                                                              '+
                        '           <div class="col-xs-6">                                              '+
                        '           <div class="form-group">                                            '+
                        '               <label>Ijazah</label>                                           '+
                        '                   <div> '+filesx+'</div>                                      '+
                        '               </div>                                                          '+
                        '           </div>                                                              '+
                        '           <div class="col-xs-6">                                              '+
                        '           <div class="form-group">                                            '+
                        '               <label>Transcript</label>                                       '+
                        '                   <div> '+filestrans+'</div>                                   '+
                        '                </div>  '+
                        '            </div> '+
                        '        </div>'+
                        '        <div class="row"> '+
                        '           <div class="col-md-12" style="text-align: right;"> '+
                        '                <hr/> '+
                        '    <div><input type="hidden" class="form-control" value="'+fileijazahs1+'" id="linkijazahs1"> </div>              '+
                        '    <div><input type="hidden" class="form-control" value="'+filetranscripts1+'" id="linktranscripts1">    </div>       '+
                        '               <button type="button" class="btn btn-danger" data-dismiss="modal"> Cancel</button> | <button type="button" class="btn btn-success btnSavedits3" linkijazahs1="'+fileijazahs1+'" linktranscripts1="'+filetranscripts1+'"> Save</button> '+
                        '           </div> '+
                        '       </div>'+
                        '   </div>'+
                        '</div>'+
                    '</div>');
                
                    $('#NotificationModal').modal({
                    'backdrop' : 'static',
                    'show' : true
                    }); 
        } 
        else {

            var url = base_url_js+'api/__getdataedits1?n='+NIP+'&j='+fileijazahs1+'&t='+filetranscripts1+'&s='+acad+'&x='+nameuniv;                      
            var token = jwt_encode({
            action:'read',
            NIP:NIP},'UAP)(*');

            $.post(url,{token:token},function (resultJson) {

                var response = resultJson;
                if(response.length>0){
                    var no = 1;
                    for (var i = 0; i < response.length; i++) {

                        var nameuniv1 = response[i]['NameUniversity'];


                        for (var j = i+1; j < response.length; j++) {
                            var nameuniv2 = response[j]['NameUniversity'];
                            
                            if (nameuniv1 == nameuniv2) {

                                if (response[j]['LinkFiles'] != null) {
                                        //var Transcriptx = '<iframe src="'+base_url_js+'uploads/files/'+response[j]['LinkFiles']+'" style="width:200px; height:100px;" frameborder="0"></iframe> <br/><center><button id="btnviewIjazahS1" class="btn btn-sm btn-primary" filesub ="'+response[j]['LinkFiles']+'"><i class="fa fa-eye"></i> View </button></center>';
                                        var Transcriptx = response[j]['LinkFiles'];
                                    } else {
                                        var Transcriptx = '<img src="<?php echo base_url('images/icon/nofiles.png'); ?>" style="width:200px; height:100px">'
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
                        '                  <input class="form-control" id="formNameUnivS3" value="'+response[i]['NameUniversity']+'">         '+
                        '               </div>                                                      '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>No. Ijazah S3</label>                           '+
                        '                    <input class="form-control" id="formNoIjazahS3" value="'+response[i]['NoIjazah']+'">       '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+     
                        '                <div class="form-group">                                   '+
                        '                    <label>Date & Year Ijazah</label>                      '+
                        '                    <input class="form-control" id="formEditIjazahDate" autocomplete="off" value="'+response[i]['DateIjazah']+'">Format : YYYY-MM-DD '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>Major</label>                                   '+
                        '                    <input class="form-control" id="formMajorS3" value="'+response[i]['Major']+'">          '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>Program Study </label>                          '+
                        '                    <input class="form-control" id="formStudyS3" value="'+response[i]['ProgramStudy']+'">          '+
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
                        '                        <input type="text" class="form-control text-center" id="TotSemesterS3" value="'+response[i]['TotalSemester']+'"> '+
                        '                           <span class="input-group-btn"> '+
                        '                               <button class="btn btn-default" data-dir="up"><span class="glyphicon glyphicon-plus"></span></button> '+
                        '                           </span>                                             '+
                        '                   </div>                                                      '+
                        '               </div>                                                          '+
                        '           </div>                                                              '+
                        '           <div class="col-xs-6">                                              '+
                        '           <div class="form-group">                                            '+
                        '               <label>Ijazah</label>                                           '+
                      
                        '                   <div><iframe src="'+base_url_js+'uploads/files/'+response[i]['LinkFiles']+'" style="width:300px; height:150px;" frameborder="0"></iframe> <br/><center><button class="btn btn-sm btn-primary btnviewlistsrata" filesub ="'+response[i]['LinkFiles']+'"><i class="fa fa-eye"></i> Preview </button></center></div>                     '+
                        '               </div>                                                          '+
                        '           </div>                                                              '+
                        '           <div class="col-xs-6">                                              '+
                        '           <div class="form-group">                                            '+
                        '               <label>Transcript</label>                                       '+
                        '                   <div><iframe src="'+base_url_js+'uploads/files/'+Transcriptx+'" style="width:300px; height:150px;" frameborder="0"></iframe> </div> <br/><center><button class="btn btn-sm btn-primary btnviewlistsrata" filesub ="'+Transcriptx+'"><i class="fa fa-eye"></i> Preview </button></center></div>                 '+
                        '                </div>                                                         '+
                        '            </div>                                                             '+
                        '        </div>                                                                 '+
                        '        <div class="row">                                                      '+
                        '           <div class="col-md-12" style="text-align: right;">                   '+
                        '                <hr/>                                                           '+
                        '    <div><input type="hidden" class="form-control" value="'+response[i]['LinkFiles']+'" id="linkijazahs1"> </div>                   '+
                        '    <div><input type="hidden" class="form-control" value="'+Transcriptx+'" id="linktranscripts1">    </div>       '+
                        '               <button type="button" class="btn btn-danger" data-dismiss="modal"> Cancel</button> | <button type="button" class="btn btn-success btnSavedits3" linkijazahs1="'+response[i]['LinkFiles']+'" linktranscripts1="'+Transcriptx+'"> Save</button>                                                               '+
                        '           </div>                                                              '+
                        '       </div>                                                                  '+
                        '   </div>                                                                      '+
                        '</div>                                                                         '+
                    '</div>');
                
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
             
        } //END IF

        
    });


    
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

 

