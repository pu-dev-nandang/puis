            
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
                                <h4>Upload Other Files </h4>
                               
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
                                                <!-- <option id="KTP" selected="selected">KTP</option>
                                                <option id="CV">Curriculum Vitae (CV)</option>
                                                <option id="SP_Dosen">Surat Pernyataan Dosen</option>
                                                <option id="SK_Dosen">SK Dosen</option>
                                                <option id="SK_Pangkat">SK Pangkat</option>
                                                <option id="SK_JJA">SK Jabatan Fungsional</option>
                                                <option id="Other_Files">Other Document</option> -->
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
            <div id="loadtablefiles"></div> 
            
            
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
            '<br/><br/><button type="button" id="btnRemoveNoEditSc" class="btn btn-primary" data-dismiss="modal">Close</button><button type="button" onclick="newtab();" id="btnRemoveNoEditSc" filesublix ="'+filesub+'" class="btn btn-primary pull-right filesublink" data-dismiss="modal"><span class="fa fa-external-link"></span></button>' +
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
        window.open(url, '_blank', 'scrollbars=1,height=650,width=900');
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

            //var rSpan =(response[0]['TypeAcademic'] == 'KTP').length;
            //alert(response[0]['TypeAcademic'].length=='KTP');
            
            
            if(response.length > 0){
                var no = 1;
                var orbs=0;
                

                for (var i = 0; i < response.length; i++) {                                                                                                                                    

                    $("#dataRow").append('<tr>                                                     '+
                    '            <td>'+response[i]['TypeFiles']+'</td>                             '+       
                    '            <td>'+response[i]['No_Document']+'</td>                           '+    
                    '            <td>'+response[i]['Date_Files']+'</td>                            '+                                                        
                    '            <td>'+response[i]['Description_Files']+'</td>                     '+     
                    '            <td style="text-align: center;"><button id="btnreviewfiles" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Review File" filesub ="'+response[i]['LinkFiles']+'"><i class="fa fa-eye"></i></button></td>      '+     
                    '         </tr> ');

                }
                
             }

        }).done(function() {
        })
    };
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