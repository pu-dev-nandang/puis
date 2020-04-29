
<div class="row">
            <div class="col-md-5" style="border-right: 1px solid #afafafb5;">
                    <div class="col-xs-12" id="subsesi">
                        <div class="form-group">
                            <div class="thumbnail" style="padding: 10px;text-align: left;">
                                <h4><b>Form Data Other Files </b></h4>
                               
                                <div class="row"> 
                                    <div class="col-xs-5">
                                        <div class="form-group">
                                        <label style="display: block"> Category Other File   <a href="javascript:void(0);" class="add_kat_otherfiles" style="text-align: right; float:right;"><span class="label label-pill" style="color: blue"><span class="fa fa-plus-circle"></span> Category File </span></a> </label>
                                        
                                        <div>
                                            <select class="form-control" id="typefiles">
                                                <?php for ($i=0; $i < count($G_TypeFiles); $i++): ?>
                                                    <?php if ($G_TypeFiles[$i]['Type'] == 1 ): ?>
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
                                    <div class="col-xs-4">
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
                                    <div class="col-xs-12">
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
                                                <label>Review File : </label>
                                                <span id="element1"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                   <div class="col-md-12" style="text-align: right;">
                                        <hr/>
                                        <button class="btn btn-success btn-round btnSaveFiles"> <span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
                                    </div> 
                                </div>
                            </div>

                        </div>
                    </div>
            </div>
        <div id="loadtablefiles" class="col-md-7 table-responsive"></div>  
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
        var sizeFile = event.target.files[0].size;
        if(sizeFile < 5000000){
            var file = URL.createObjectURL(event.target.files[0]);
            $('#element1').append('<br/><iframe src="' + file + '" style="width:250px; height:100;" frameborder="0"></iframe>' );
            $(".btnSaveFiles").prop("disabled",false);
        }else{
            alert("Size of this file is too large. ");
            $(".btnSaveFiles").prop("disabled",true);
        }
    });


    $(document).on('click','.btnMasterOtherfile', function () {
        $('#GlobalModalLarge .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Master University </h4>');

        var body = '<div class="row table-responsive">' +
            '    <div class="col-md-5">' +
            '        <div class="well">' +
            '            <div class="form-group">' +
            '                <input class="form-control" id="master_nameuniv" placeholder="Name University...">' +
            '            </div>' +
            '            <div style="text-align:right;">' +
            '                <button class="btn btn-success btn-round" id="btnSaveLembaga"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    ' +
            '    <div id="viewData23" class="col-md-7">' +
            '    </div>' +
            '</div>';
        $('#GlobalModalLarge .modal-body').html(body);

        $('#GlobalModalLarge .modal-footer').html('<button type="button" class="btn btn-primary btn-round" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>');
        $('#GlobalModalLarge').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

</script>

<script>
    $(document).ready(function () {
        loadFilesDetails();
        loadformsotherfiles();
    });
    
    function loadformsotherfiles() {

        $('#typefiles').val('');
        $('#NoDocument').val('');
        $('#DescriptionFile').val('');
        $('#DateDocument').val('');
        $('#fileOther').val('');
        $('#element1').val('');
    
    }

    function loadFilesDetails() {  

        $('#loadtablefiles').html('<table class="table table-bordered table-striped" id="tableDataotherfiles">   '+
            '                    <thead>                                                               '+
            '                    <tr style="background: #20485A;color: #FFFFFF;">                      '+
            '                        <th style="width: 3%;text-align: center;">No</th>                 '+
            '                        <th style="width: 6%;text-align: center;">Category Files</th>         '+
            '                        <th style="width: 5%;text-align: center;">No.Document</th>        '+
            '                        <th style="width: 5%;text-align: center;">Date Document</th>      '+
            '                        <th style="width: 12%;text-align: center;">Description File</th>       '+
            '                        <th style="width: 7%;text-align: center;">Action</th>             '+
            '                    </tr>                                                                 '+
            '                    </thead>                                                              '+
            '                </table>');

        var NIP = '<?php echo $NIP; ?>';
        var token = jwt_encode({action:'readlist_otherfile', NIP: NIP},'UAP)(*');
        var dataTable = $('#tableDataotherfiles').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"api/__reviewotherfile", // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        });
    }

</script>
<script>

    $(document).on('click','.add_kat_otherfiles', function () {
        var body = '<div class="row">' +
            '         <div class="col-md-12">' +
            '           <h4><b> ADD CATEGORY OTHER FILES</b></h4>' +
            '           <div class="well">' +
             '              <div class="form-group">' +
            '                   <label>Name Sort (Alias)</label>'+
            '                   <input class="form-control" id="name_sort">' +
            '               </div>' +
            '               <div class="form-group">' +
            '                   <label>Name Category Other File</label>'+
            '                   <input class="form-control" id="name_kat_otherfiles">' +
            '               </div>' +
            '           </div>' +
            '           <div class="btn-group pull-right">   '+ 
            '                <button type="button" class="btn btn-danger btn-round" data-dismiss="modal"><i class="fa fa-remove"></i>Cancel</button> '+ 
            '                <button type="button" class="btn btn-success btn-round btnSubmitKatOtherFiles"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button> '+
            '           </div>  '+ 
            '        </div> ' +
            '     </div>';
        $('#NotificationModal .modal-body').html(body);
        $('#NotificationModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    $(document).on('change','#e_typefiles', function () {
        var filterKategoriJenis = $('#e_typefiles option:selected').attr('id');
        $("#e_JenisFiles").empty();

        if(filterKategoriJenis == "13") {

            var url = base_url_js+'api/__reviewotherfile';
            var token = jwt_encode({action : 'get_katotherfiles'},'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {
                $('#e_JenisFiles').append('<option id="0" disabled selected>Pilih Kategori Other Files</option>');
                    for(var i=0;i<jsonResult.length;i++){
                            $('#e_JenisFiles').append('<option id="'+jsonResult[i].ID+'"> '+jsonResult[i].Name_other_files+' </option>');
                    }
            });
        } 
        else {

            $("#e_JenisFiles").empty();
            $('#e_JenisFiles').append('<option id="0" selected disabled></option>');

        }
    });

    $(document).on('click','.testEditdocument', function () {

        var NIP = '<?php echo $NIP; ?>';
        var filesnametype = $(this).attr('filesnametype');
        var idfiles = $(this).attr('idfiles');
        var namedoc = $(this).attr('namedoc');
        var linkfileother = $(this).attr('linkfileother');
        var idtypex = $(this).attr('idtypex');

        if (linkfileother != null) {
            var filesxx = '<iframe src="'+base_url_js+'uploads/files/'+linkfileother+'" style="width:300px; height:150px;" frameborder="0"></iframe> <br/><center><button class="btn btn-sm btn-primary btn-round btnviewlistsrata" filesub ="'+linkfileother+'"><i class="fa fa-eye"></i> Preview </button></center>';
            } else {
            var filesxx = '<img src="<?php echo base_url('images/icon/nofiles.png'); ?>" style="width:200px; height:100px;">'
        }
        
        if(namedoc == "null") {
               $('#NotificationModal .modal-body').html('<br/><div class="col-xs-12 id="subsesi">       '+
                        '<div class="form-group">                                                       '+
                        '    <div class="thumbnail" style="padding: 10px;text-align: left;">            '+
                        '      <h4>Edit Other Files</h4>                                                '+
                        '       <div class="row">                                                       '+
                        '        <div class="col-xs-5">                                                 '+
                        '                <div class="form-group">                                       '+
                        '                <label class="control-label">Category File </label>                '+
                        '                <div>                                                          '+
                        '                    <select class="form-control" id="typefiles">               '+
                        '                        <option id="'+idtypex+'">'+filesnametype+'</option>    '+
                        '                        <?php for ($i=0; $i < count($G_TypeFiles); $i++): ?>   '+
                        '                            <?php if ($G_TypeFiles[$i]['Type'] == 1): ?>       '+
                        '                             <option id="<?php echo $G_TypeFiles[$i]['ID'] ?>"><?php echo $G_TypeFiles[$i]['TypeFiles'] ?></option>   '+ 
                        '                            <?php endif ?>  '+
                        '                        <?php endfor ?>  '+
                        '                    </select>  '+
                        '                </div>  '+
                        '               </div>  '+
                        '           </div>  '+
                        '         <div class="col-xs-6"> '+
                        '                <div class="form-group"> '+ 
                        '                    <label>No. Document</label> '+
                        '                   <input class="form-control" id="NoDocument"> '+
                        '               </div> '+
                        '          </div> '+
                        '           <div class="col-xs-5"> '+
                        '             <div class="form-group"> '+
                        '                  <label>Date Document</label> '+
                        '                  <input type="date" class="form-control" id="DateDocument">   '+
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
                        '               <label>Preview File</label>                                      '+
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
                        '         <div class="btn-group">   '+ 
                        '                <button type="button" class="btn btn-danger btn-round" data-dismiss="modal"> <i class="fa fa-remove"></i>Cancel</button> '+ 
                        '                <button type="button" class="btn btn-success btn-round btnSubmitEditFiles" linkothers="'+linkfileother+'" idfiles="'+idfiles+'"> <i class="glyphicon glyphicon-floppy-disk"></i> Save</button> '+ 
                        '               </div>  '+ 

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
                        '                    <select class="form-control" id="e_typefiles">                 '+
                        '                        <option id="'+response[i]['TypeFiles']+'" disabled selected>'+response[i]['NameFiles']+'</option>'+
                        '                        <?php for ($i=0; $i < count($G_TypeFiles); $i++): ?>       '+
                        '                            <?php if ($G_TypeFiles[$i]['Type'] == 1): ?>           '+
                        '                               <option id="<?php echo $G_TypeFiles[$i]['ID'] ?>"><?php echo $G_TypeFiles[$i]['TypeFiles'] ?></option>   '+ 
                        '                            <?php endif ?>                                         '+
                        '                        <?php endfor ?>                                            '+
                        '                    </select>                                                      '+
                        '                </div>                                                             '+
                        '               </div>                                                              '+
                        '           </div>                                                                  '+
                        '         <div class="col-xs-6"> '+
                        '                <div class="form-group"> '+ 
                        '                    <label>No. Document</label> '+
                        '                   <input class="form-control" id="NoDocument" value="'+response[i]['No_Document']+'"> '+
                        '               </div> '+
                        '          </div> '+
                        '           <div class="col-xs-5"> '+
                        '              <div class="form-group"> '+
                        '                  <label>Date Document</label> '+
                        '                   <input type="date" class="form-control" id="DateDocument" value="'+response[i]['Date_Files']+'"> '+
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
                        '                <div id="element1">Review File : </div> '+
                        '                   <div><iframe src="'+base_url_js+'uploads/files/'+response[i]['LinkFiles']+'" style="width:300px; height:150px;" frameborder="0"></iframe> <br/><center><button class="btn btn-sm btn-primary btn-round btnviewlistsrata" filesub ="'+response[i]['LinkFiles']+'"><i class="fa fa-eye"></i> Preview </button></center>                     '+
                        '                   </div>'+
                        '               </div> '+
                        '            </div> '+
                        '        </div>'+ 
                        '        <form id="tagFM_OtherFile" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">   '+
                        '            <label class="btn btn-sm btn-default btn-upload">                                                      '+
                        '                <i class="fa fa-upload margin-right"></i> Change File                                              '+
                        '                       <input type="file" id="fileOther" name="userfile" class="upload_files" accept="application/pdf"> '+
                        '                    </label>                                                                                                              '+
                        '                <p style="font-size: 12px;color: #FF0000;">*) Only PDF Files Max Size 5 MB</p>              '+
                        '        </form>                     '+
                        '    <div class="row"> '+
                        '    <div class="col-md-12" style="text-align: right;"> '+
                        '         <hr/> '+
                        '    <div><input type="hidden" class="form-control" value="'+filesnametype+'" id="typeotherfiles"> </div>        '+
                        '    <div><input type="hidden" class="form-control" value="'+linkfileother+'" id="linkotherfile">   </div>       '+
                        '    <div><input type="hidden" class="form-control" value="'+idfiles+'" id="idlinkfiles">           </div>       '+
                        '         <div class="btn-group">   '+ 
                        '                <button type="button" class="btn btn-danger btn-round" data-dismiss="modal"> <i class="fa fa-remove"></i>Cancel</button> '+ 
                        '                <button type="button" class="btn btn-success btn-round btnSubmitEditFiles" linkothers="'+linkfileother+'" idfiles="'+idfiles+'"> <i class="glyphicon glyphicon-floppy-disk"></i> Save</button> '+ 
                        '               </div>  '+ 

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

    $(document).on('click','.btnSubmitKatOtherFiles', function () {
        var name_katother = $('#name_kat_otherfiles').val();
        var name_sort = $('#name_sort').val();

        if(name_sort!='' && name_sort!=null
            && name_katother!='' && name_katother!=null
            ){
            loading_button('.btnSubmitKatOtherFiles');

            var data = {
                action : 'update_mster_katother',
                name_katother : name_katother,
                name_sort : name_sort
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__loadMstruniversity';

                $.post(url,{token:token},function (jsonResult) {

                    if(jsonResult==0 || jsonResult=='0') { 
                        toastr.error('Sorry, Name Category Other File Already!','Error');
                        $('.btnSubmitKatOtherFiles').html('Save').prop('disabled',false);

                    } else {

                        toastr.success('Category Other File Saved','Success');
                        $('#NotificationModal').modal('hide');

                        setTimeout(function () {
                             loadFilesDetails();
                             loadformsotherfiles();
                            $('.menuDetails[data-page="otherfiles"]').trigger('click');

                        },500);
                    }
                });

            } else {
                toastr.warning('All form is required','Warning');
            }
    });
   
$('#btnSaveEditFiles').click(function () {  
        var NIP = $('#formNIP').val();
        $('#NotificationModal .modal-body').html('<div style="text-align: center;">     ' +
            'Pastikan Data Files tidak salah ! <br/>                                    ' +
            'Periksa kembali data yang di input sebelum di Save.                        ' +
            '<hr/>                                                                      ' +
            '<button type="button" class="btn btn-default" id="btnCloseEmployees" data-dismiss="modal"> Close</button> | ' +
            '<button type="button" class="btn btn-success btnSubmitEditFiles"> Submit</button>' +
            '</div> ');

        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });
    });

</script>


<script>
     $(document).on('click','.btndelotherfile',function () {
        if (window.confirm('Are you sure to delete file ?')) {
            //loading_button('.btndelotherfile');

            var otfile1 = $(this).attr('Idotherfile');
            var data = {
                action : 'deleteother',
                otfile1 : otfile1
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+"api/__delistacaemploy";
            $.post(url,{token:token},function (result) {
                toastr.success('Success Delete File!','Success'); 
                setTimeout(function () {
                    $('.menuDetails[data-page="otherfiles"]').trigger('click');
                  //window.location.href = '';
                },1000);
            });
        }
    });
</script>
