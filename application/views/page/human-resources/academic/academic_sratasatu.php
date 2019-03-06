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

<div class="row">
            <div class="col-md-6" style="border-right: 1px solid #afafafb5;">

                    <div class="col-xs-12 id="subsesi">
                        <div class="form-group">
                            <div class="thumbnail" style="padding: 10px;text-align: left;">
                                <h4>Data Academic Transcript S1 </h4>
                               
                                <div class="row"> 
                                	
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label>Name Univesity</label>
                                            <input class="form-control" id="formNameUnivS1">
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                    	<div class="form-group">
                                        	<label>No. Ijazah S1</label>
                                        	<input class="form-control" id="formNoIjazahS1">
                                    	</div>
                                	</div>

                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Date & Year Ijazah</label>
                                            <input class="form-control" id="formIjazahDate" autocomplete="off">
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
                                        	<input class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" id="gradeS1" maxlength="4">
                                    	</div>
                                	</div>
                                	<div class="col-xs-3">
                                    	<div class="form-group">
                                        	<label>Total Credit</label>
                                        	<input class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" id="totalCreditS1" maxlength="3">
                                    	</div>
                                	</div>

                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Total Semester</label>
                                                <div class="input-group number-spinner">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>
                                                </span>
                                                <input type="text" class="form-control text-center" id="TotSemesterS1" value="0" disabled>
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" data-dir="up"><span class="glyphicon glyphicon-plus"></span></button>
                                                    </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Ijazah</label>
                                            <form id="tagFM_IjazahS1" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
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
                                            <form id="tagFM_TranscriptS1" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
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
                                            <button class="btn btn-success" id="btnSave">Save</button>
                                        </div> 
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- <span id="bodyAddSesi"></span> -->
            </div>

            <span id="loadtablefiles1"></span> 
 </div>

<script>
$(document).ready(function () {
    $('#formIjazahDate').datepicker({
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

    $(document).ready(function () {
        loadAcademicS1Details();
    });

    $(document).on('click','#btnreviewfiles', function () {

        var filesub = $(this).attr('filesub');
        //var url = ''+base_url_js+'uploads/files/'+filesub+' ';

           $('#NotificationModal .modal-header').addClass('hide');
            $('#NotificationModal .modal-body').html('<center> '+
            '<iframe src="'+base_url_js+'uploads/files/'+filesub+'" frameborder="0" style="width:745px; height:550px;"></iframe> '+
            '<br/><br/><button type="button" id="btnRemoveNoEditSc" class="btn btn-primary" data-dismiss="modal">Close</button><button type="button" id="btnRemoveNoEditSc" filesublix ="'+filesub+'" class="btn btn-primary pull-right filesublink" data-toggle="tooltip" data-placement="top" title="Full Review" data-dismiss="modal"><span class="fa fa-external-link"></span></button>' +
            '</center>');
            $('#NotificationModal .modal-footer').addClass('hide');
            $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
            }); 
    });

    $(document).on('click','.filesublink', function () {
        var filesubx = $(this).attr('filesublix');
        var url = base_url_js+'uploads/files/'+filesubx;
        window.open(url, '_blank',);
    });

    $(document).on('click','.testEdit', function () {

        var NIP = '<?php echo $NIP; ?>';
        var acad = 'S1';
        var fileijazahs1 = $(this).attr('fileijazahs1');
        var filetranscripts1 = $(this).attr('filetranscripts1');
        var nameuniv = $(this).attr('nameuniv');

        if (fileijazahs1 != null) {
            var filesx = '<iframe src="'+base_url_js+'uploads/files/'+fileijazahs1+'" style="width:300px; height:150px;" frameborder="0"></iframe> <br/><center><button id="btnreviewfiles" class="btn btn-sm btn-primary" filesub ="'+fileijazahs1+'"><i class="fa fa-eye"></i> Preview </button></center>';
            } else {
            var filesx = '<img src="<?php echo base_url('images/icon/nofiles.png'); ?>" style="width:200px; height:100px;">'
        }
        if (filetranscripts1 != null) {
            var filestrans = '<iframe src="'+base_url_js+'uploads/files/'+filetranscripts1+'" style="width:300px; height:150px;" frameborder="0"></iframe> <br/><center><button id="btnreviewfiles" class="btn btn-sm btn-primary" filesub ="'+filetranscripts1+'"><i class="fa fa-eye"></i> Preview </button></center>';
        } else {
            var filestrans = '<img src="<?php echo base_url('images/icon/nofiles.png'); ?>" style="width:200px; height:100px">'
        }

        if(nameuniv == "null") {
                $('#NotificationModal .modal-body').html('<br/><div class="col-xs-12 id="subsesi">  '+
                        '<div class="form-group">                                                   '+
                        '    <div class="thumbnail" style="padding: 10px;text-align: left;">        '+
                        '      <h4>Edit Academic Transcript S1 </h4>                           '+
                        '       <div class="row">                                                   '+
                        '          <div class="col-xs-12">                                          '+
                        '               <div class="form-group">                                    '+
                        '                   <label>Name Univesity</label>                           '+
                        '                  <input class="form-control" id="formNameUnivS1">         '+
                        '               </div>                                                      '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>No. Ijazah S1</label>                           '+
                        '                    <input class="form-control" id="formNoIjazahS1">       '+
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
                        '                    <input class="form-control" id="formMajorS1">          '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>Program Study </label>                          '+
                        '                    <input class="form-control" id="formStudyS1">          '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                    '+
                        '                    <label>Grade/ IPK</label>                               '+
                        '                    <input class="form-control" id="gradeS1" maxlength="4"> '+
                        '                </div>                                                      '+
                        '            </div>                                                          '+
                        '            <div class="col-xs-3">                                          '+
                        '                <div class="form-group">                                    '+
                        '                    <label>Total Credit</label>                             '+
                        '                    <input class="form-control" id="totalCreditS1" maxlength="3"> '+
                        '                </div>                                                         '+
                        '            </div>                                                             '+
                        '            <div class="col-xs-3">                                             '+
                        '               <div class="form-group">                                        '+ 
                        '                    <label>Total Semester</label>                              '+
                        '                        <div class="input-group number-spinner">               '+
                        '                        <span class="input-group-btn">                         '+
                        '                            <button class="btn btn-default" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button> '+
                        '                        </span>                                                '+
                        '                        <input type="text" class="form-control text-center" id="TotSemesterS1" value="0" disabled> '+
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
                        '               <button type="button" class="btn btn-danger" data-dismiss="modal"> Cancel</button> | <button type="button" class="btn btn-success" id="btnSavedits1" linkijazahs1="'+fileijazahs1+'" linktranscripts1="'+filetranscripts1+'"> Save</button> '+
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

            var url = base_url_js+'api/__getdataedits1?n='+NIP+'&j='+fileijazahs1+'&t='+filetranscripts1+'&s='+acad;                          
            var token = jwt_encode({
            action:'read',
            NIP:NIP},'UAP)(*');

            $.post(url,{token:token},function (resultJson) {

                var response = resultJson;
                if(response.length>0){
                    var no = 1;
                    for (var i = 0; i < response.length; i++) {

                    $('#NotificationModal .modal-body').html('<br/><div class="col-xs-12 id="subsesi">      '+
                        '<div class="form-group">                                                   '+
                        '    <div class="thumbnail" style="padding: 10px;text-align: left;">        '+
                        '      <h4>Edit Academic Transcript S1 </h4>                           '+
                        '       <div class="row">                                                   '+
                        '          <div class="col-xs-12">                                          '+
                        '               <div class="form-group">                                    '+
                        '                   <label>Name Univesity</label>                           '+
                        '                  <input class="form-control" id="formNameUnivS1" value="'+response[i]['NameUniversity']+'">         '+
                        '               </div>                                                      '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>No. Ijazah S1</label>                           '+
                        '                    <input class="form-control" id="formNoIjazahS1" value="'+response[i]['NoIjazah']+'">       '+
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
                        '                    <input class="form-control" id="formMajorS1" value="'+response[i]['Major']+'">          '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>Program Study </label>                          '+
                        '                    <input class="form-control" id="formStudyS1" value="'+response[i]['ProgramStudy']+'">          '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                    '+
                        '                    <label>Grade/ IPK</label>                               '+
                        '                    <input class="form-control" id="gradeS1" maxlength="4" value="'+response[i]['Grade']+'"> '+
                        '                </div>                                                      '+
                        '            </div>                                                          '+
                        '            <div class="col-xs-3">                                          '+
                        '                <div class="form-group">                                    '+
                        '                    <label>Total Credit</label>                             '+
                        '                    <input class="form-control" id="totalCreditS1" maxlength="3" value="'+response[i]['TotalCredit']+'"> '+
                        '                </div>                                                         '+
                        '            </div>                                                             '+
                        '            <div class="col-xs-3">                                             '+
                        '               <div class="form-group">                                        '+ 
                        '                    <label>Total Semester</label>                              '+
                        '                        <div class="input-group number-spinner">               '+
                        '                        <span class="input-group-btn">                         '+
                        '                            <button class="btn btn-default" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button> '+
                        '                        </span>                                                '+
                        '                        <input type="text" class="form-control text-center" id="TotSemesterS1" value="'+response[i]['TotalSemester']+'" disabled> '+
                        '                           <span class="input-group-btn"> '+
                        '                               <button class="btn btn-default" data-dir="up"><span class="glyphicon glyphicon-plus"></span></button> '+
                        '                           </span>                                             '+
                        '                   </div>                                                      '+
                        '               </div>                                                          '+
                        '           </div>                                                              '+
                        '           <div class="col-xs-6">                                              '+
                        '           <div class="form-group">                                            '+
                        '               <label>Ijazah</label>                                           '+
                        '                <form id="tagFM_IjazahS1" enctype="multipart/form-data" accept-charset="utf-8" method="post" action=""> '+
                        '                        <div class="form-group"> '+
                        '                            <label class="btn btn-sm btn-default btn-default-warning btn-upload">  '+
                         '                                   <i class="fa fa-upload margin-right"></i> Change File          '+
                         '                                   <input type="file" id="fileIjazah" name="userfile" class="upload_files1" style="display: none;" data-fm="tagFM_IjazahS1" accept="application/pdf" disabled> '+
                        '                             </label> '+
                        '                        <p style="font-size: 12px;color: #FF0000;">*) Only PDF Files Max Size 5 MB</p> '+
                        '                    </div></form> '+
                        '                   <div><iframe src="'+base_url_js+'uploads/files/'+response[i]['IjazahFile']+'" style="width:300px; height:150px;" frameborder="0"></iframe> <br/><center><button id="btnreviewfiles" class="btn btn-sm btn-primary" filesub ="'+response[i]['IjazahFile']+'"><i class="fa fa-eye"></i> Preview </button></center></div>                     '+
                        '               </div>                                                          '+
                        '           </div>                                                              '+
                        '           <div class="col-xs-6">                                              '+
                        '           <div class="form-group">                                            '+
                        '               <label>Transcript</label>                                       '+
                        '                    <form id="tagFM_TranscriptS1" enctype="multipart/form-data" accept-charset="utf-8" method="post" action=""> '+
                        '                            <input id="formPhoto" class="hide" value="" hidden />  '+
                        '                                <div class="form-group">                           '+
                        '                                    <label class="btn btn-sm btn-default btn-default-warning btn-upload"> '+
                        '                                        <i class="fa fa-upload margin-right"></i> Change File '+
                        '                                        <input type="file" id="fileTranscript" name="userfile" class="upload_files2" style="display: none;" accept="application/pdf" disabled>                                                                     '+
                        '                                </label>                                                                '+
                        '                         <p style="font-size: 12px;color: #FF0000;">*) Only PDF Files Max Size 5 MB</p> '+
                        '                    </div></form>                                                                       '+
                        '                   <div><iframe src="'+base_url_js+'uploads/files/'+response[i]['TranscriptFile']+'" style="width:300px; height:150px;" frameborder="0"></iframe> </div> <br/><center><button id="btnreviewfiles" class="btn btn-sm btn-primary" filesub ="'+response[i]['TranscriptFile']+'"><i class="fa fa-eye"></i> Preview </button></center></div>                 '+
                        '                </div>                                                         '+
                        '            </div>                                                             '+
                        '        </div>                                                                 '+
                        '        <div class="row">                                                      '+
                        '           <div class="col-md-12" style="text-align: right;">                   '+
                        '                <hr/>                                                           '+
                        '    <div><input type="hidden" class="form-control" value="'+response[i]['IjazahFile']+'" id="linkijazahs1"> </div>                   '+
                        '    <div><input type="hidden" class="form-control" value="'+response[i]['TranscriptFile']+'" id="linktranscripts1">    </div>       '+
                        '               <button type="button" class="btn btn-danger" data-dismiss="modal"> Cancel</button> | <button type="button" class="btn btn-success" id="btnSavedits1" linkijazahs1="'+response[i]['IjazahFile']+'" linktranscripts1="'+response[i]['TranscriptFile']+'"> Save</button>                                                               '+
                        '           </div>                                                              '+
                        '       </div>                                                                  '+
                        '   </div>                                                                      '+
                        '</div>                                                                         '+
                    '</div>');
                
                    $('#NotificationModal').modal({
                    'backdrop' : 'static',
                    'show' : true
                    }); 
                        
                    } //end for
      
                } //end if

            }); //end json
             
        } //END IF

        
    });

    function loadAcademicS1Details() {
        
        var NIP = '<?php echo $NIP; ?>';
        var srata = 'S1';
        var url = base_url_js+'api/__reviewacademics1?NIP='+NIP+'&s='+srata;
        var token = jwt_encode({
            action:'read',
            NIP:NIP},'UAP)(*');

        $.post(url,{token:token},function (resultJson) {
            var response = resultJson;
                $("#loadtablefiles1").append(
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
                    var listdatas1 = response[i]['IjazahFile']; 
                    var listdatas2 = response[i]['TranscriptFile']; 

                    //for (var j = i+1; j < response.length; j++) {
                    //    if (response[i]['NIP'] == response[j]['NIP'] && response[i]['NameUniversity'] == response[j]['NameUniversity']) {
                    //        var listdatas2 = response[j]['TranscriptFile'];
                    //    }
                    //    else
                    //    {
                    //        i = j-1;
                    //        break;
                    //    }  
                    //   i = j;
                    //}

                    $("#dataRow").append('<tr>                                                          '+
                    '            <td> S1 </td>                                                          '+         
                    '            <td> '+response[i]['NameUniversity']+' </td>                           '+                             
                    '            <td><center><button id="btnreviewfiles" class="btn btn-sm btn-default btn-default-primary" filesub="'+listdatas1+'"><i class="fa fa-eye margin-right"></i> Preview</button></center></td>                                                '+    
                    '            <td><center><button id="btnreviewfiles" class="btn btn-sm btn-default btn-default-success" filesub="'+listdatas2+'"><i class="fa fa-eye margin-right"></i> Preview</button> </center></td>                                              '+                                
                    '            <td style="text-align: center;"><button id="btnedits1" class="btn btn-sm btn-primary testEdit" data-toggle="tooltip" data-placement="top" title="Edit" nameuniv= "'+response[i]['NameUniversity']+'" fileijazahs1 ="'+listdatas1+'" filetranscripts1 ="'+listdatas2+'"><i class="fa fa-edit"></i></button></td>      '+     
                    '         </tr> ');
                }
                
             }

        });
    };
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
     $(document).on('click','#btnSavedits1',function () {
        saveditEmployees();
    });

    function saveditEmployees() {

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
    $('#btnSave').click(function () {
        var NIP = $('#formNIP').val();
        $('#NotificationModal .modal-body').html('<div style="text-align: center;">' +
            'Pastikan data form & File Academic S1 tidak salah! <br/>' +
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
                //$('#btnDelete_IjazahS1').prop('disabled',btnIjazahS1).attr('data-file',d.IjazahS1);
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

    //$('.upload_files').change(function () {
    function uploadXDSD_files(fileName) {

        var NIP = '<?php echo $NIP; ?>';
        var input = this;

        if(NIP!='' && NIP!=null && input.files && input.files[0]){
            //var NIP = formEmployees;
            var fm = $(this).attr('data-fm');
            var type = fm.split('tagFM_')[1];
            //var type = $("#typefiles option:selected").attr("id")

            var sz = parseFloat(input.files[0].size) / 1000000; // ukuran MB
            var ext = input.files[0].type.split('/')[1];

            var ds = true;
            if(Math.floor(sz)<=8){
                ds = false;

                var fileName = type+'_'+NIP+'.'+ext;
                var formData = new FormData( $("#tagFM_IjazahS1")[0]);
                //var formData = new FormData( $("#"+fm)[0]);
                var url = base_url_js+'human-resources/upload_academic?fileName='+fileName+'&c='+type+'&u='+NIP;
                //alert(url);

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
    };
   // });


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