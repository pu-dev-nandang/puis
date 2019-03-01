            
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
                                <h4>Data Other Files </h4>
                               
                                <div class="row"> 
                                    <div class="col-xs-5">
                                        <div class="form-group">
                                        <label class="control-label">Type File </label>
                                        <div>
                                            <select class="form-control" id="typefiles">
                                                <?php for ($i=0; $i < count($G_TypeFiles); $i++): ?>
                                                    <?php if ($G_TypeFiles[$i]['Type'] == 1): ?>
                                                     <option id="<?php echo $G_TypeFiles[$i]['ID'] ?>"><?php echo $G_TypeFiles[$i]['TypeFiles'] ?></option>  
                                                    <?php endif ?>
                                                <?php endfor ?>
                                                
                                            </select>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>No. Document</label>
                                            <input class="form-control" id="NoDocument">
                                        </div>
                                    </div>

                                    <div class="col-xs-5">
                                        <div class="form-group">
                                            <label>Date Document</label>
                                            <input class="form-control" id="DateDocument">
                                        </div>
                                    </div>

                                    <div class="col-xs-11">
                                        <div class="form-group">
                                            <label>Description Files</label>
                                            <textarea rows="3" cols="5" name="DescriptionFile" id="DescriptionFile" class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Upload Document</label>
                                                <form id="tagFM_OtherFile" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
                                                    <label class="btn btn-sm btn-default btn-default-warning btn-upload">
                                                        <i class="fa fa-upload margin-right"></i> Upload File
                                                            <input type="file" id="fileOther" name="userfile" class="upload_files" style="display: none;" accept="application/pdf">
                                                    </label>
                                                    <p style="font-size: 12px;color: #FF0000;">*) Only PDF Files Max Size 5 MB</p>
                                                </form> 
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <div id="element1">Review File : </div>
                                        </div>
                                    </div>

                                  
                                </div>
                                <div class="row">
                                   <div class="col-md-12" style="text-align: right;">
                                            <hr/>
                                            <button class="btn btn-success" id="btnSaveFiles">Save</button>
                                        </div> 
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- <span id="bodyAddSesi"></span> -->
            </div>
            
            <span id="loadtablefiles"></span>  
 </div>


<script>
    $(document).ready(function () {
    $('#DateDocument').datepicker({
      dateFormat : 'yy-mm-dd',
      changeMonth : true,
      changeYear : true,
      autoclose: true,
      todayHighlight: true,
      uiLibrary: 'bootstrap'
    });
});

$('#fileOther').change(function (event) {
        $('#element1').empty();
        var file = URL.createObjectURL(event.target.files[0]);
        $('#element1').append('<br/><iframe src="' + file + '" style="width:350px; height:100;" frameborder="0"></iframe>' );
    });
</script>

<script>
    $(document).ready(function () {
        loadFilesDetails();
    });
    //$nestedData[] = ($row["Gender"]=='P') ? 'Female' : 'Male';

    $(document).on('click','#btnreviewfiles', function () {

        var filesub = $(this).attr('filesub');
       
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
        //win.focus();
    });

    function loadFilesDetails() {
        
        var NIP = '<?php echo $NIP; ?>';
        var url = base_url_js+'api/__reviewotherfile?NIP='+NIP;

        var token = jwt_encode({
            action:'read',
            NIP:NIP},'UAP)(*');

        $.post(url,{token:token},function (resultJson) {

            var response = resultJson;
            //console.log(resultJson);

                $("#loadtablefiles").append(
                    ' <div class="table-responsive">                                                '+
                    '     <table class="table table-striped table-bordered">                        '+
                    '         <thead>                                                               '+
                    '         <tr style="background: #607d8b;color: #FFFFFF;">                      '+
                    '             <th style="width: 5%;text-align: center;">Type Files</th>         '+
                    '             <th style="width: 8%;text-align: center;">No.Document</th>        '+
                    '             <th style="width: 8%;text-align: center;">Date Document</th>      '+
                    '             <th style="width: 15%;text-align: center;">Description</th>       '+
                    '             <th style="text-align: center;width: 5%;">Action</th>             '+
                    '         </tr>                                                                  '+
                    '         </thead>                                                              '+
                    '         <tbody id="dataRow"></tbody>                                          '+
                    '    </table>                                                                   '+
                    '</div> ');  

            if(response.length > 0){
                var no = 1;
                var orbs=0;
                

                for (var i = 0; i < response.length; i++) {

                    if (response[i]['No_Document'] == null){
                         var datadoc = '<center> - </center>';
                    } else {
                         var datadoc = ''+response[i]['No_Document']+'';
                    } 

                    if (response[i]['Date_Files'] == null){
                         var datadate = '<center> - </center>';
                    } else {
                         var datadate = ''+response[i]['Date_Files']+'';
                    } 

                    if (response[i]['Description_Files'] == null){
                         var datadesc = '<center> - </center>';
                    } else {
                         var datadesc = ''+response[i]['Description_Files']+'';
                    }                                                                                                                               

                    $("#dataRow").append('<tr>                                                     '+
                    '            <td>'+response[i]['NameFiles']+'</td>                             '+       
                    //'            <td>'+response[i]['No_Document']+'</td>                         '+    
                    '            <td>'+datadoc+'</td>                                               '+    
                    '            <td>'+datadate+'</td>                                              '+                                                       
                   '             <td>'+datadesc+'</td>                                              '+    
                    '            <td style="text-align: center;"><button class="btn btn-sm btn-primary testEdit3" data-toggle="tooltip" data-placement="top" title="Edit File" filesnametype ="'+response[i]['NameFiles']+'" idfiles="'+response[i]['ID']+'" namedoc ="'+response[i]['No_Document']+'"><i class="fa fa-edit"></i></button> </td>      '+     
                    '         </tr> ');

                }
                
             }

        }).done(function() {
        })
    };


$(document).on('click','.testEdit3', function () {

        var NIP = '<?php echo $NIP; ?>';
        var filesnametype = $(this).attr('filesnametype');
        var idfiles = $(this).attr('idfiles');
        var namedoc = $(this).attr('namedoc');
        
        if(namedoc == "null") {
               $('#NotificationModal .modal-body').html('<br/><div class="col-xs-12 id="subsesi">  '+
                        '<div class="form-group">                                                   '+
                        '    <div class="thumbnail" style="padding: 10px;text-align: left;">        '+
                        '      <h4>Edit Academic Transcript '+acad+' </h4>                           '+
                        '       <div class="row">                                                   '+
                        '          <div class="col-xs-12">                                          '+
                        '               <div class="form-group">                                    '+
                        '                   <label>Name Univesity</label>                           '+
                        '                  <input class="form-control" id="formNameUnivS1">         '+
                        '               </div>                                                      '+
                        '            </div>                                                         '+
                        '            <div class="col-xs-6">                                         '+
                        '                <div class="form-group">                                   '+
                        '                    <label>No. Ijazah</label>                           '+
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
                        '               <button type="button" class="btn btn-danger" data-dismiss="modal"> Cancel</button> | <button type="button" class="btn btn-success" id="btnSavedits3" linkijazahs1="'+fileijazahs1+'" linktranscripts1="'+filetranscripts1+'"> Save</button> '+
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

            var url = base_url_js+'api/__getdataedits1?n='+NIP+'&j='+filesnametype+'&t='+idfiles;                          
            var token = jwt_encode({
            action:'read',
            NIP:NIP},'UAP)(*');

            $.post(url,{token:token},function (resultJson) {

                var response = resultJson;
                if(response.length>0){
                    var no = 1;
                    for (var i = 0; i < response.length; i++) {

                    $('#NotificationModal .modal-body').html('');
                
                    $('#NotificationModal').modal({
                    'backdrop' : 'static',
                    'show' : true
                    }); 
                        
                    } //end for
                } //end if
            }); //end json  
        } //END IF
    });
</script>

<script>
    $('#btnSaveFiles').click(function () {
        var NIP = $('#formNIP').val();
        $('#NotificationModal .modal-body').html('<div style="text-align: center;">     ' +
            'Pastikan Data Files tidak salah ! <br/>                                    ' +
            'Periksa kembali data yang di input sebelum di Save.                        ' +
            '<hr/>                                                                      ' +
            '<button type="button" class="btn btn-default" id="btnCloseEmployees" data-dismiss="modal">Close</button> | ' +
            '<button type="button" class="btn btn-success" id="btnSubmitFiles">Submit</button>' +
            '</div> ');

        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });
    });

    $(document).on('click','#btnSubmitFiles',function () {
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
                    ){ 
                    loading_button('#btnSubmitFiles');
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

                                //uploadfile_transcripts(fileName_Transcript);
                                toastr.success('Academic Data Saved','Success');

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


                                                                                                                                                                                                                                                                                     
    function saveFileDocumentold() {

        var formNIP = '<?php echo $NIP; ?>';
        var NoDocument = $('#NoDocument').val();
        var DescriptionFile = $('#DescriptionFile').val();
        var DateDocument = $('#DateDocument').val();
        var type = $("#typefiles option:selected").attr("id")
        var min=100; 
        var max=999;  
        var random =Math.floor(Math.random() * (+max - +min)) + +min; 
        var ext = 'PDF';
        var fileName = type+'_'+NIP+'_'+random+'.'+ext;
        var oFile = document.getElementById("fileOther").files[0]; 

        if (oFile.size > 5242880) {  // 5 mb for bytes.
                toastr.error('File Maksimum size 5 Mb!','Error');
                $('#NotificationModal').modal('hide');
                //return;
        } 
            else {

                if(formNIP!=null && formNIP!=''
                    && NoDocument!='' && NoDocument!=null
                    && DescriptionFile!='' && DescriptionFile!=null
                    && DateDocument!='' && DateDocument!=null
                    && type!='' && type!=null
                    ){ 
                    loading_button('#btnSubmitFiles');
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

                                //uploadfile_transcripts(fileName_Transcript);
                                toastr.success('Academic Data Saved','Success');

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