

<div class="row">
            <div class="col-md-6" style="border-right: 1px solid #afafafb5;">

                    <div class="col-xs-12" id="subsesi">
                        <div class="form-group">
                            <div class="thumbnail" style="padding: 10px;text-align: left;">
                                <h4> Data Academic Transcript S1 </h4>
                               
                                <div class="row"> 
                                	
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label style="display: block"> Name Univesity   <a href="javascript:void(0);" class="btnNameUniversity" style="text-align: right; float:right;"><span class="label label-pill label-primary"><span class="fa fa-plus-circle"></span> University</span></a> </label>
                                            <select class="select2-select-00 form-exam formNameUnivS1" id="formNameUnivS1" style="width: 100%;" size="5"><option value=""></option></select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                    	<div class="form-group">
                                        	<label>No. Ijazah S1 </label> 
                                        	<input class="form-control" id="formNoIjazahS1">
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
                                        	<label style="display: block">Major <a href="javascript:void(0);" class="btnAddMajor" style="text-align: right; float:right;"><span class="label label-pill label-primary"><span class="fa fa-plus-circle"></span> Major </span></a></label>
                                             <select class="select2-select-00 form-exam formMajorS1" id="formMajorS1" style="width: 100%;" size="5"><option value=""></option></select>
                                        	
                                    	</div>
                                	</div>
                                	<div class="col-xs-6">
                                    	<div class="form-group">
                                        	<label style="display: block">Program Study <a href="javascript:void(0);" class="btnAddMajor" style="text-align: right; float:right;"><span class="label label-pill label-primary"><span class="fa fa-plus-circle"></span> Program Study </span></a> </label>
                                             <select class="select2-select-00 form-exam formStudyS1" id="formStudyS1" style="width: 100%;" size="5"><option value=""></option></select>
                                        	<!-- <input class="form-control" id="formStudyS1"> -->
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
                                                <input type="text" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" id="TotSemesterS1" value="0" maxlength="2">          
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Ijazah</label>
                                            <form id="tagFM_IjazahS1" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                                        <div class="form-group">
                                                            <label class="btn btn-sm btn-default btn-default-warning btn-upload">
                                                                <i class="fa fa-upload margin-right"></i> Upload File
                                                                <input type="file" id="fileIjazah" name="userfile" class="upload_filesij" style="display: none;" data-fm="tagFM_IjazahS1" accept="application/pdf">
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
                                                                <input type="file" id="fileTranscript" name="userfile" class="upload_filestra" style="display: none;" accept="application/pdf">
                                                        </label>
                                                 <p style="font-size: 12px;color: #FF0000;">*) Only PDF Files Max Size 5 MB</p>
                                                       </div>
                                            </form>
                                            <div id="element2">Review Transcript : </div>
                                        </div>
                                    </div>

                                  
                                </div>
                                <div class="row">
                                   <div class="col-md-12" style="text-align: right;">
                                            <hr/>
                                            <button class="btn btn-success btn-round btnSaveSrata1"><span class="glyphicon glyphicon-floppy-disk"></span> Save </button>
                                        </div> 
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- <span id="bodyAddSesi"></span> -->
            </div>
    <span id="loadtablefiles1" class="table-responsive"></span> 
 </div>

<script>
$(document).ready(function () {
        loadAcademicS1Details();
        loadforms1();
 
        loadSelectOptionUniversity('#formNameUnivS1','');
        $('#formNameUnivS1').select2({allowClear: true});

        loadSelectOptionMajorEmployees('#formMajorS1','');
        $('#formMajorS1').select2({allowClear: true});

        loadSelectOptionProgramStudyEmployees('#formStudyS1','');
        $('#formStudyS1').select2({allowClear: true});
    });


function loadforms1() {
    $("#formNameUnivS1").select2("val", "");
    $("#formMajorS1").select2("val", "");
    $("#formStudyS1").select2("val", "");

    $('#formNoIjazahS1').val('');
    $('#gradeS1').val('');
    $('#totalCreditS1').val('');
    $('#formIjazahDate').val('');
    $('#TotSemesterS1').val('');
    $('#fileIjazah').val('');
    $('#fileTranscript').val('');
}

  
function loadAcademicS1Details() {
        
        var NIP = '<?php echo $NIP; ?>';
        var srata = 'S1';
        var url = base_url_js+'api/__reviewacademics1?NIP='+NIP+'&s='+srata;
        var token = jwt_encode({
            action:'read',
            NIP:NIP},'UAP)(*');

        $.post(url,{token:token},function (resultJson) {
            var response = resultJson;
            //console.log(response);
                $("#loadtablefiles1").append(
                    ' <div class="table-responsive">                                                '+
                    '     <table class="table table-striped table-bordered table-responsive">                        '+
                    '         <thead>                                                               '+
                    '         <tr style="background: #20485A;color: #FFFFFF;">                      '+
                    '             <th style="width: 2%;text-align: center;">Academic</th>           '+
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
                        if (response[i]['NamaJurusan'] == response[j]['NamaJurusan'] && response[i]['Name_University'] == response[j]['Name_University']) {
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

                    $("#dataRow").append('<tr>                                                      '+
                    '       <td style="text-align: center;"> '+response[i]['TypeAcademic']+' </td>  '+         
                    '       <td> '+response[i]['Name_University']+' </td>                           '+                             
                    '       <td> '+response[i]['NamaJurusan']+' </td>                               '+    
                    '       <td><center><div class="btn-group">   '+ 
                    '          <button type="button" class="btn btn-sm btn-primary btnviewlistsrata" filesub="'+listdatas1+'"> <i class="fa fa-eye margin-right"></i>Ijazah</button> '+ 
                    '          <button type="button" class="btn btn-sm btn-success btnviewlistsrata" filesub="'+listdatas2+'"> <i class="fa fa-eye margin-right"></i> Transcript</button> '+ 
                    '          </div>  '+ 
                    '          </center></td>    '+        
                    '       <td style="text-align: center;"><button class="btn btn-sm btn-primary btn-circle testdetail" data-toggle="tooltip" data-placement="top" title="Edit" listid_ijazah ="'+iddata1+'" listid_transcript ="'+iddata2+'"><i class="icon-list icon-large"></i></button>   <button class="btn btn-sm btn-primary btn-circle testEdit" data-toggle="tooltip" data-placement="top" title="Edit" listid_ijazah ="'+iddata1+'" listid_transcript ="'+iddata2+'"><i class="fa fa-edit"></i></button>   <button class="btn btn-danger btn-circle btndelist" data-toggle="tooltip" data-placement="top" title="Delete" listid_ijazah ="'+iddata1+'" listid_transcript ="'+iddata2+'"><i class="fa fa-trash"></i></button></td>      '+  
                    '         </tr> ');
                }
                
             }

            setTimeout(function () {
                //$('#loadtablefiles1').html(resultJson);
            },500)
        });
    };
</script>

<script>
    
    $(document).on('click','.testEdit', function () {
      
        var NIP = '<?php echo $NIP; ?>';
        var acad = 'S1';
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
                        '      <h4>Edit Academic Transcript S1 </h4>                           '+
                        '       <div class="row">                                                   '+
                        '          <div class="col-xs-12">                                          '+
                        '               <div class="form-group">                                    '+
                        '                   <label>Name Univesity</label>                           '+
                        '                   <select class="select2-select-00 form-exam formNameUnivS1" id="formNameUnivS1" style="width: 100%;" size="5"></select>'+
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
                        '                    <input class="form-control editdatepicker" id="formEditIjazahDate" autocomplete="off" value="'+response[i]['DateIjazah']+'">Format : YYYY-MM-DD '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>Name Major</label>                                   '+
                        '                    <select class="select2-select-00 form-exam formMajorS1" id="formMajorS1" style="width: 100%;" size="5"><option value="'+response[i]['Major']+'">'+response[i]['NamaJurusan']+'</option></select>           '+
                        '                </div>                                                     '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>Program Study </label>                          '+
                        '                    <select class="select2-select-00 form-exam formStudyS1" id="formStudyS1" style="width: 100%;" size="5"><option value="'+response[i]['ProgramStudy']+'">'+response[i]['NamaProgramStudi']+'</option></select>           '+
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
                        '                    <input type="text" class="form-control" id="TotSemesterS1" value="'+response[i]['TotalSemester']+'" maxlength="2"> '+
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
                        '                  <button type="button" class="btn btn-success btn-round btnSavedits1" id_linkijazahs1="'+response[i]['ID']+'" id_linktranscripts1="'+response[j]['ID']+'"> <i class="glyphicon glyphicon-floppy-disk"></i> Save</button> '+ 
                        '               </div>  '+ 
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
                    loadSelectOptionUniversity('#formNameUnivS1',''+response[i]['NameUniversity']+'');
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

<script>
    $(document).on('click','.testdetail', function () {
      
        var NIP = '<?php echo $NIP; ?>';
        var acad = 'S1';
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
                        '      <h4>Detail Academic Transcript S1 </h4>                           '+
                        '       <div class="row">                                                   '+
                        '          <div class="col-xs-12">                                          '+
                        '               <div class="form-group">                                    '+
                        '                   <label>Name Univesity</label>                           '+
                        '                   <select class="select2-select-00 form-exam formNameUnivS1" id="formNameUnivS1" style="width: 100%;" size="5" disabled><option></option></select>'+
                        '               </div>                                                      '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>No. Ijazah S1</label>                           '+
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
                        '                    <input type="text" class="form-control text-center" id="TotSemesterS1" value="'+response[i]['TotalSemester']+'" disabled> '+
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
    $('.editdatepicker').datepicker({
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

