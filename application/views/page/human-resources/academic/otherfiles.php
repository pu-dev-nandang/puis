            
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

    .datepicker {
         z-index: 1050px !important; /* has to be larger than 1050 */
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
                                            <input class="form-control frmdatepicker" id="DateDocument">
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
    $('.frmdatepicker').datepicker({
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
        var url = ''+base_url_js+'uploads/files/'+filesub+' ';


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
                    '             <th style="width: 5%;text-align: center;">Date Document</th>      '+
                    '             <th style="width: 15%;text-align: center;">Description</th>       '+
                    '             <th style="text-align: center;width: 7%;">Action</th>             '+
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
                         var dates = ''+response[i]['Date_Files']+'';
                         var datadate = moment(dates).format('DD-MM-YYYY');
                    } 

                    if (response[i]['Description_Files'] == null){
                         var datadesc = '<center> - </center>';
                    } else {
                         var datadesc = ''+response[i]['Description_Files']+'';
                    }                                                                                                                               

                    $("#dataRow").append('<tr>                                                     '+
                    '            <td>'+response[i]['NameFiles']+'</td>                             '+       
                    '            <td>'+datadoc+'</td>                                               '+    
                    '            <td><center>'+datadate+'</center></td>                                              '+                                                       
                    '             <td>'+datadesc+'</td>                                              '+    
                    '            <td style="text-align: center;"><button id="btnreviewfiles" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Review Files" filesub="'+response[i]['LinkFiles']+'"><i class="fa fa-eye"></i></button> | <button class="btn btn-sm btn-primary testEdit3" data-toggle="tooltip" data-placement="top" title="Edit File" filesnametype="'+response[i]['NameFiles']+'" idtypex="'+response[i]['TypeFiles']+'" idfiles="'+response[i]['ID']+'" linkfileother="'+response[i]['LinkFiles']+'" namedoc ="'+response[i]['No_Document']+'"><i class="fa fa-edit"></i></button> </td>      '+     
                    '         </tr>');

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
        var linkfileother = $(this).attr('linkfileother');
        var idtypex = $(this).attr('idtypex');


        if (linkfileother != null) {
            var filesxx = '<iframe src="'+base_url_js+'uploads/files/'+linkfileother+'" style="width:300px; height:150px;" frameborder="0"></iframe> <br/><center><button id="btnreviewfiles" class="btn btn-sm btn-primary" filesub ="'+linkfileother+'"><i class="fa fa-eye"></i> Preview </button></center>';
            } else {
            var filesxx = '<img src="<?php echo base_url('images/icon/nofiles.png'); ?>" style="width:200px; height:100px;">'
        }
        
        if(namedoc == "null") {
               $('#NotificationModal .modal-body').html('<br/><div class="col-xs-12 id="subsesi">   '+
                        '<div class="form-group">                                                   '+
                        '    <div class="thumbnail" style="padding: 10px;text-align: left;">        '+
                        '      <h4>Edit Other Files</h4>                                            '+
                        '       <div class="row">                                                   '+
                        '        <div class="col-xs-5">                                             '+
                        '                <div class="form-group">                                   '+
                        '                <label class="control-label">Type File </label>            '+
                        '                <div>                                                      '+
                        '                    <select class="form-control" id="typefiles">           '+
                        '                        <option id="'+idtypex+'">'+filesnametype+'</option>  '+
                        '                        <?php for ($i=0; $i < count($G_TypeFiles); $i++): ?>  '+
                        '                            <?php if ($G_TypeFiles[$i]['Type'] == 1): ?>  '+
                        '                             <option id="<?php echo $G_TypeFiles[$i]['ID'] ?>"><?php echo $G_TypeFiles[$i]['TypeFiles'] ?></option>   '+ 
                        '                            <?php endif ?>  '+
                        '                        <?php endfor ?>  '+
                        '                    </select>  '+
                        '                </div>  '+
                         '               </div>  '+
                         '           </div>  '+
                         '   <div class="col-xs-6"> '+
                         '                <div class="form-group"> '+ 
                         '                    <label>No. Document</label> '+
                          '                   <input class="form-control" id="NoDocument"> '+
                          '               </div> '+
                           '          </div> '+
                          '           <div class="col-xs-5"> '+
                           '              <div class="form-group"> '+
                           '                  <label>Date Document</label> '+
                          '                   <input type="date" class="form-control" id="DateDocument">   '+
                           '              </div> '+
                           '          </div> '+
                            '        <div class="col-xs-11"> '+
                            '            <div class="form-group"> '+
                            '                <label>Description Files</label> '+
                            '                <textarea rows="3" cols="5" name="DescriptionFile" id="DescriptionFile" class="form-control"></textarea>'+
                            '            </div> '+
                            '        </div> '+
                          '           <div class="col-xs-6">                                              '+
                        '           <div class="form-group">                                            '+
                        '               <label>Ijazah</label>                                           '+
                        '                        <form id="tagFM_OtherFile" enctype="multipart/form-data" accept-charset="utf-8" method="post" action=""> '+
                        '                            <label class="btn btn-sm btn-default btn-default-warning btn-upload"> '+
                        '                                <i class="fa fa-upload margin-right"></i> Upload File '+
                        '                                    <input type="file" id="fileOther" name="userfile" class="upload_files" style="display: none;" accept="application/pdf"> '+
                         '                           </label> '+
                        '                           <p style="font-size: 12px;color: #FF0000;">*) Only PDF Files Max Size 5 MB</p> '+
                        '                      </form>  '+
                        '                   <div> '+filesxx+'</div>                                      '+
                        '               </div>                                                          '+
                        '           </div>     '+
                        '        </div>'+ //
                        '        <div class="row"> '+
                        '           <div class="col-md-12" style="text-align: right;"> '+
                        '                <hr/> '+
                        '    <div><input type="hidden" class="form-control" value="'+filesnametype+'" id="typeotherfiles"> </div>              '+
                        '    <div><input type="hidden" class="form-control" value="'+linkfileother+'" id="linkotherfile">    </div>       '+
                        '    <div><input type="hidden" class="form-control" value="'+idfiles+'" id="idlinkfiles">    </div>       '+
                        '               <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button> | <button type="button" class="btn btn-success" id="btnSubmitEditFiles" linkothers="'+linkfileother+'" idfiles="'+idfiles+'"> <i class="fa fa-check"></i> Save</button> '+
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
            var acatype = "OTF";
            var url = base_url_js+'api/__getdataedits1?n='+NIP+'&s='+acatype+'&t='+idfiles;                          
            var token = jwt_encode({
                action:'read',
                NIP:NIP
            },'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
            console.log(resultJson); 
            var response = resultJson;
                if(response.length>0){
                    var no = 1;
                    for (var i = 0; i < response.length; i++) {

                    $('#NotificationModal .modal-body').html('<br/><div class="col-xs-12 id="subsesi">      '+
                        '<div class="form-group">                                                           '+
                        '    <div class="thumbnail" style="padding: 10px;text-align: left;">                '+
                        '      <h4>Edit Other Files</h4>                                                    '+
                        '       <div class="row">                                                           '+
                        '        <div class="col-xs-5">                                                     '+
                        '                <div class="form-group">                                           '+
                        '                <label class="control-label">Type File </label>                    '+
                        '                <div>                                                              '+
                        '                    <select class="form-control" id="typefiles">                   '+
                        '                        <?php for ($i=0; $i < count($G_TypeFiles); $i++): ?>       '+
                        '                            <?php if ($G_TypeFiles[$i]['Type'] == 1): ?>           '+
                        '                             <option id="<?php echo $G_TypeFiles[$i]['ID'] ?>"><?php echo $G_TypeFiles[$i]['TypeFiles'] ?></option>   '+ 
                        '                            <?php endif ?>                                         '+
                        '                        <?php endfor ?>                                            '+
                        '                    </select>                                                      '+
                        '                </div>                                                             '+
                        '               </div>                                                              '+
                        '           </div>                                                                  '+
                        '   <div class="col-xs-6"> '+
                        '                <div class="form-group"> '+ 
                        '                    <label>No. Document</label> '+
                        '                   <input class="form-control" id="NoDocument" value="'+response[i]['No_Document']+'"> '+
                        '               </div> '+
                        '          </div> '+
                        '           <div class="col-xs-5"> '+
                        '              <div class="form-group"> '+
                        '                  <label>Date Document</label> '+
                        '                   <input type="date" class="form-control" id="DateDocument" value="'+response[i]['DateDocument']+'"> '+
                        '              </div> '+
                        '          </div> '+
                        '        <div class="col-xs-11"> '+
                        '            <div class="form-group"> '+
                        '                <label>Description Files</label> '+
                        '                <textarea rows="3" cols="5" name="DescriptionFile" id="DescriptionFile" class="form-control" >'+response[i]['Description_Files']+' </textarea>'+
                        '            </div> '+
                        '        </div> '+
                        '        <div class="col-xs-6"> '+
                        '            <div class="form-group"> '+
                        '                <label>Upload Document</label> '+
                        '                    <form id="tagFM_OtherFile" enctype="multipart/form-data" accept-charset="utf-8" method="post" action=""> '+
                        '                        <label class="btn btn-sm btn-default btn-default-warning btn-upload"> '+
                        '                            <i class="fa fa-upload margin-right"></i> Upload File '+
                        '                                <input type="file" id="fileOther" name="userfile" class="upload_files" style="display: none;" accept="application/pdf"> '+
                        '                        </label> '+
                        '                        <p style="font-size: 12px;color: #FF0000;">*) Only PDF Files Max Size 5 MB</p> '+
                        '                    </form>  '+
                        '            </div> '+
                        '        </div> '+
                        '        <div class="col-xs-6"> '+
                        '            <div class="form-group"> '+
                        '                <div id="element1">Review File : </div> '+
                        '            </div> '+
                        '       </div> '+
                        '        </div>'+ //
                        '        <div class="row"> '+
                        '           <div class="col-md-12" style="text-align: right;"> '+
                        '                <hr/> '+
                        '    <div><input type="hidden" class="form-control" value="'+filesnametype+'" id="typeotherfiles"> </div>        '+
                        '    <div><input type="hidden" class="form-control" value="'+linkfileother+'" id="linkotherfile">   </div>       '+
                        '    <div><input type="hidden" class="form-control" value="'+idfiles+'" id="idlinkfiles">           </div>       '+
                        '               <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel </button> | <button type="button" class="btn btn-success" id="btnSubmitEditFiles" linkothers="'+linkfileother+'" idfiles="'+idfiles+'"> <i class="fa fa-check"></i> Save</button> '+
                        '           </div> '+
                        '       </div>'+
                        '   </div>'+
                        '</div>'+
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

$('#btnSaveEditFiles').click(function () {  
        var NIP = $('#formNIP').val();
        $('#NotificationModal .modal-body').html('<div style="text-align: center;">     ' +
            'Pastikan Data Files tidak salah ! <br/>                                    ' +
            'Periksa kembali data yang di input sebelum di Save.                        ' +
            '<hr/>                                                                      ' +
            '<button type="button" class="btn btn-default" id="btnCloseEmployees" data-dismiss="modal">Close</button> | ' +
            '<button type="button" class="btn btn-success" id="btnSubmitEditFiles">Submit</button>' +
            '</div> ');

        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });
    });


    $(document).on('click','#btnSubmitEditFiles',function () {
        saveEditFileDocument();
    });

    $(document).on('click','#btnSubmitFiles',function () {
        saveFileDocument();
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
        alert(DateDocument);
        alert(newdate);

        if(formNIP!=null && formNIP!=''
                    && NoDocument!='' && NoDocument!=null
                    && DescriptionFile!='' && DescriptionFile!=null
                    && DateDocument!='' && DateDocument!=null
                    ){ 
                    loading_button('#btnSubmitEditFiles');
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
                            var action = 'OtherFiles';
                            var url = base_url_js+'human-resources/upload_academic?c='+type+'&action='+action+'&u='+NIP;
                                 
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