
<!-- class="th-center"  -->
<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class=""><i class="icon-reorder"></i> Data Version</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs btn-primary btn-version" data-action="addGroupModule" id="btn_addTahunAkademik">
                            <i class="icon-plus"></i> Add Version
                        </span>
                    </div>
                </div>
            </div>
            <div class="widget-content">
            	<div class="">
				    <table class="table table-bordered table-striped" id="tableversion">
				        <thead>
				        <tr style="background: #3968c6;color: #FFFFFF;">
				            <th style="width: 1%;text-align: center;">No</th>
				            <th style="width: 7%;text-align: center;">No. Version</th>
				            <th style="width: 9%;text-align: center;">Name Division</th>
                            <th style="width: 10%;text-align: center;">Name Group Module</th>
				            <th style="width: 10%;text-align: center;">Name Module</th>
				            <th style="width: 28%;text-align: center;">Description</th>
				    		<th style="width: 10%;text-align: center;">Date Update </th>
				    		<th style="width: 8%;text-align: center;">PIC Name</th>
				            <th style="width: 8%;text-align: center;">Action</th>
				        </tr>
				        </thead>
				    </table>
				</div>            
                <!-- <div id="loadPage"></div> -->
            </div>
        </div>
    </div>
</div>

<hr/>

<script>
    $(document).on('click','.btnsearchmodule',function () {
        var action = $(this).attr('data-action');
        var btnSave = (action=='addGroupModule') ? 'add' : 'edit';
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
            ' <span aria-hidden="true">&times;</span></button> '+
            ' <h4 class="modal-title">Seacrh Module </h4>');
        $('#GlobalModal .modal-body').html('<table class="table">' +
            '<tr>' +
            '   <td style="width: 25%;">Name Module</td>' +
            '   <td><select class="form-control filterStatusModule" value="" ><option value="" disabled> --- Select Name Module --- </option></select></td>' +
            '</tr>' +
            '</table>');
        loadSelectModule();
        
        loadSelectOptionEmployeesSingle('#filternamepic','');
        $('#filternamepic').select2({allowClear: true});

        $('#descriptionversion').summernote({
            placeholder: 'Text your Description Version',
            tabsize: 2,
            height: 200,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });

       
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-success btn-round btnSaveVersion"><span class="fa fa-check-square-o"></span> Select</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

</script>



<script>
    $(document).on('click','.btneditversion', function () {

        var versionid = $(this).attr('versionid');
        var url = base_url_js+'api/__getdetailversion?s='+versionid;                          
        var token = jwt_encode({
                action:'getedit'
            },'UAP)(*');

        $.post(url,{token:token},function (resultJson) {
            console.log(resultJson); 
            var response = resultJson;
                if(response.length>0){
                    var no = 1;
                    for (var i = 0; i < response.length; i++) {

                        var IDDivision = ''+response[i]['IDDivision']+'';

                        var url = base_url_js+'api/__dropdowneditgroupmod';
                        var token = jwt_encode({action : 'getLastdiversion', IDDivision : IDDivision},'UAP)(*');

                        $.post(url,{token:token},function (jsonResult) {
                            $('#filtereditgroup').append('<option disabled selected></option>');
                            for(var i=0;i<jsonResult.length;i++){
                                    $('#filtereditgroup').append('<option id="'+jsonResult[i].IDGroup+'"> '+jsonResult[i].NameGroup+' </option>');
                            }
                        });

                        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
			        	    ' <span aria-hidden="true">&times;</span></button> '+
			                 ' <h4 class="modal-title">Edit Version</h4>');
                        $('#GlobalModal .modal-body').html('<table class="table">' +
    			             '<tr>' +
    			            '	<td style="width: 25%;">No. Version</td>' +
    			            '	<td><input class="form-control" id="Noeditversion" value="'+response[i]['Version']+'" disabled></td>' +
                             '  <td><input type="hidden" class="form-control" id="Idversion" value="'+response[i]['IDVersion']+'" disabled></td>' +
    			            '</tr>' +
    			            '<tr>' +
    			            '	<td style="width: 25%;">Name Division</td>' +
    			            '	<td><input class="form-control" id="nameeditdivisiversion" value="'+response[i]['Division']+'" disabled></td>' +
    			            '</tr>' +
                            '<tr>' +
                            '   <td style="width: 25%;">Name Group Module</td>' +
                            '   <td><select class="form-control filtergroupmodule" id="filtereditgroup"> </select></td>' +
                            '</tr>' +
    			            '<tr>' +
    			            '	<td style="width: 25%;">Name Module</td>' +
    			            '	<td> <select class="form-control" id="filtereditmodule"><option id="'+response[i]['IDModule']+'" disabled> '+response[i]['NameModule']+' </option></select></td>' +
    			            '</tr>' +
                            '</tr>' +
    			            '	<td style="width: 25%;">Name PIC</td>' +
    			            '    <td> <select class="form-control" id="selectpicversion"><option id="'+response[i]['NIP']+'" disabled> '+response[i]['NamePIC']+' </option></select></td>' +
    			            '</tr>' +
    			            '<tr>' +  //
    			            '	<td style="width: 25%;">Description</td>' +
    			            '	<td><textarea id="descriptionversion">'+response[i]['Description']+'</textarea></td>' +
    			            '</tr>' +
    			            '</table>');

                            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-danger btn-round" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button> <button type="button" class="btn btn-success btn-round btneditSaveVersion" dataid="'+response[i]['IDVersion']+'"><span class="glyphicon glyphicon-floppy-disk"></span>  Save</button>');

                            $('#GlobalModal').modal({
                                'backdrop' : 'static',
                                'show' : true
                            }); 

                            loadSelectModule();
                            loadPicversion();
                            loadSelectOptionEmployeesSingle('#filternamepic','');
                            $('#filternamepic').select2({allowClear: true});

                            $('#descriptionversion').summernote({
                                placeholder: 'Text your Description Version',
                                tabsize: 2,
                                height: 200,
                                toolbar: [
                                    // [groupName, [list of button]]
                                    ['style', ['bold', 'italic', 'underline', 'clear']],
                                    ['font', ['strikethrough', 'superscript', 'subscript']],
                                    ['fontsize', ['fontsize']],
                                    ['color', ['color']],
                                    ['para', ['ul', 'ol', 'paragraph']],
                                    ['height', ['height']]
                                ]
                            });
                 
                    } //end for
                } //end if
            }); //end json  
         //END IF
    });
</script>


<script>
    $(document).on('click','.btnviewversion', function () {

        var versionid = $(this).attr('versionid');
        var url = base_url_js+'api/__getdetailversion?s='+versionid;                          
        var token = jwt_encode({
                action:'getdetail'
            },'UAP)(*');

        $.post(url,{token:token},function (resultJson) {
            console.log(resultJson); 
            var response = resultJson;
                if(response.length>0){
                    var no = 1;
                    for (var i = 0; i < response.length; i++) {

                    $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
			        	' <span aria-hidden="true">&times;</span></button> '+
			            ' <h4 class="modal-title">Detail Version Data</h4>');
                    $('#GlobalModal .modal-body').html('<table class="table">' +
			             '<tr>' +
			            '	<td style="width: 25%;">No. Version</td>' +
			            '	<td><b>'+response[i]['Version']+' </b></td>' +
			            '</tr>' +
			            '<tr>' +
			            '	<td style="width: 25%;">Name Division</td>' +
			            '	<td>'+response[i]['Division']+'</td>' +
			            '</tr>' +
			            '<tr>' +
			            '	<td style="width: 25%;">Name Module</td>' +
			            '	<td>'+response[i]['NameModule']+'</td>' +
			            '</tr>' +
			            '	<td style="width: 25%;">Date Update</td>' +
			            '	<td>'+response[i]['UpdateAt']+'</td>' +
			            '</tr>' +
			            '	<td style="width: 25%;">Name PIC</td>' +
			            '	<td>'+response[i]['NamePIC']+'</td>' +
			            '</tr>' +
			            '<tr>' +
			            '	<td style="width: 25%;">Description</td>' +
			            '	<td>'+response[i]['Description']+'</td>' +
			            '</tr>' +
			            '</table>');
                    $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-danger btn-round" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>');
                
                    $('#GlobalModal').modal({
                        'backdrop' : 'static',
                        'show' : true
                    }); 
                        
                    } //end for
                } //end if
            }); //end json  
         //END IF
    });
</script>

<script>
    $('.btn-addgroup').click(function () {
        var action = $(this).attr('data-action');
        var btnSave = (action=='addGroupModule') ? 'add' : 'edit';
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
            ' <span aria-hidden="true">&times;</span></button> '+
            ' <h4 class="modal-title">Add Group Module</h4>');
        $('#GlobalModal .modal-body').html('<table class="table">' +
            '<tr>' +
            '   <td style="width: 25%;">Name Division</td>' +
            '   <td><select class="form-control filterStatusDivision" id="filaddivisi"><option id="" disabled> --- Select Name Division --- </option></select></td>' +
            '</tr>' +
            '<tr>' +
            '   <td style="width: 25%;">Name Group</td>' +
            '   <td><select class="form-control filaddnamegroup" id="filteraddgroup"></select></td>' +
            '</tr>' +
            '<tr>' +
            '   <td style="width: 25%;">Name Module</td>' +
            '   <td><input class="form-control" id="Namemodule"></td>' +
            '</tr>' +
            '<tr>' +
            '   <td style="width: 25%;">Description</td>' +
            '   <td><textarea rows="3" cols="5" name="DescriptionFile" id="Descriptiongroup" class="form-control"></textarea></td>' +
            '</tr>' +
            '</table>');
        loadSelectOptionDivision();
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-danger btn-round" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>' +
            '<button type="button" class="btn btn-success btn-round btnaddSaveGroup"><span class="glyphicon glyphicon-floppy-disk"></span>  Save</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });
</script>

<script>
	$('.btn-action').click(function () {
        var action = $(this).attr('data-action');
        var btnSave = (action=='addGroupModule') ? 'add' : 'edit';
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
        	' <span aria-hidden="true">&times;</span></button> '+
            ' <h4 class="modal-title">New Group Module</h4>');
        $('#GlobalModal .modal-body').html('<table class="table">' +
            '<tr>' +
            '	<td style="width: 25%;">Name Division</td>' +
            '	<td><select class="form-control filterStatusDivision" value="" ><option id="" disabled> --- Select Name Division --- </option></select></td>' +
            '</tr>' +
            '<tr>' +
            '	<td style="width: 25%;">Name Group</td>' +
            '	<td><input class="form-control" id="Namegroup"></td>' +
            '</tr>' +
            '<tr>' +
            '	<td style="width: 25%;">Name Module</td>' +
            '	<td><input class="form-control" id="Namemodule"></td>' +
            '</tr>' +
            '<tr>' +
            '	<td style="width: 25%;">Description</td>' +
            '	<td><textarea rows="3" cols="5" name="DescriptionFile" id="Descriptiongroup" class="form-control"></textarea></td>' +
            '</tr>' +
            '</table>');
        loadSelectOptionDivision();
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-danger btn-round" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>' +
            '<button type="button" class="btn btn-success btn-round btnSaveGroup"><span class="glyphicon glyphicon-floppy-disk"></span>  Save</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });
</script>

<script>

    $('.btn-version').click(function () {

        var action = $(this).attr('data-action');
        var btnSave = (action=='addGroupModule') ? 'add' : 'edit';
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
        	' <span aria-hidden="true">&times;</span></button> '+
            ' <h4 class="modal-title">Add Version </h4>');
        $('#GlobalModal .modal-body').html('<table class="table">' +
            '<tr>' +
            '	<td style="width: 25%;">No. Version</td>' +
            '	<td><input class="form-control" id="Noversion"></td>' +
            '</tr>' +
            '<tr>' +
            '   <td style="width: 25%;">Name Division</td>' +
            '   <td><select class="form-control filterDivision" id="filterDivisi"><option value="" disabled>-- Name Division --</option></select></td>' +
            '</tr>' +
            '<tr>' +
            '   <td style="width: 25%;">Name Group Module</td>' +
            '   <td><select class="form-control filtergroupmodule" id="filtergroup"></select></td>' +
            '</tr>' +
            '<tr>' +
            '   <td style="width: 25%;">Name Module</td>' +
            '   <td><select class="form-control filterStatusModule" id="filternamemodule"></select></td>' +
            '</tr>' +
            '<tr>' +
            '	<td style="width: 25%;">Name PIC</td>' +
            '	<td><select class="select2-select-00 form-exam filternamepic" id="filternamepic" style="max-width: 300px !important;" size="5"><option value=""></option>'+
            '	</select></td>   ' +
            '</tr>' +
            
            '<tr>' +
            '	<td style="width: 25%;">Description</td>' +
            '	<td><textarea id="descriptionversion"></textarea></td>' +
            '</tr>' +
            '</table>');

        //loadSelectModule();
        loadfilterDivision();
        loadSelectOptionEmployeesSingle('#filternamepic','');
        $('#filternamepic').select2({allowClear: true});

        $('#descriptionversion').summernote({
            placeholder: 'Text your Description Version',
            tabsize: 2,
            height: 200,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });

       
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-danger btn-round" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>' +
            '<button type="button" class="btn btn-success btn-round btnSaveVersion"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

</script>

<script>
    $(document).on('change','#filtergroup',function () {
        var s = $(this).val();
        loadselectmodules();
    });

    function loadselectmodules() {

        var filterGroups = $('#filtergroup option:selected').attr('id');
        
        if(filterGroups!='' && filterGroups!=null){
            var url = base_url_js+'api/__dropdownlistmodule';
            var token = jwt_encode({action : 'getListgroupmodule', filterGroups : filterGroups},'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {
                $("#filternamemodule").empty();
                $('#filternamemodule').append('<option disabled selected></option>');

                    for(var i=0;i<jsonResult.length;i++){
                        $('#filternamemodule').append('<option id="'+jsonResult[i].IDModule+'"> '+jsonResult[i].NameModule+' </option>');
                    }
                });
            
            }
        }
</script>


<script>
    $(document).on('change','#filaddivisi',function () {
        var s = $(this).val();
        $("#filteraddgroup").empty();
        //$("#filternamemodule").empty();
        loadaddselectgroup();
    });

    function loadaddselectgroup() {

        var filterDivisi = $('#filaddivisi option:selected').attr('id');
        
        if(filterDivisi!='' && filterDivisi!=null){
            var url = base_url_js+'api/__dropdowngroupmod';
            var token = jwt_encode({action : 'getLastgroupmodule', filterDivisi : filterDivisi},'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {
                    $('#filteraddgroup').append('<option disabled selected></option>');
                    for(var i=0;i<jsonResult.length;i++){
                           $('#filteraddgroup').append('<option id="'+jsonResult[i].IDGroup+'"> '+jsonResult[i].NameGroup+' </option>');
                    }
                });
            }
        }
</script>

<script>
    $(document).on('change','#filterDivisi',function () {
        var s = $(this).val();
        $("#filtergroup").empty();
        $("#filternamemodule").empty();
        loadselectgroup();
    });

    function loadselectgroup() {

        var filterDivisi = $('#filterDivisi option:selected').attr('id');
        
        if(filterDivisi!='' && filterDivisi!=null){
            var url = base_url_js+'api/__dropdowngroupmod';
            var token = jwt_encode({action : 'getLastgroupmodule', filterDivisi : filterDivisi},'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {
                    $('#filtergroup').append('<option disabled selected></option>');
                    for(var i=0;i<jsonResult.length;i++){
                           $('#filtergroup').append('<option id="'+jsonResult[i].IDGroup+'"> '+jsonResult[i].NameGroup+' </option>');
                    }
                });
            }
        }
</script>

<script>
    $(document).on('change','#filtereditgroup',function () {
        var s = $(this).val();
        //$("#filtergroup").empty();
        $("#filtereditmodule").empty();
        loadeditselectmodule();
    });

    function loadeditselectmodule() {

        var filtereditgroup = $('#filtereditgroup option:selected').attr('id');
        
        if(filtereditgroup!='' && filtereditgroup!=null){
            var url = base_url_js+'api/__dropeditmodule';
            var token = jwt_encode({action : 'geteditLastmodule', filtereditgroup : filtereditgroup},'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {
                    $('#filtereditmodule').append('<option disabled selected></option>');
                    for(var i=0;i<jsonResult.length;i++){
                           $('#filtereditmodule').append('<option id="'+jsonResult[i].IDModule+'"> '+jsonResult[i].NameModule+' </option>');
                    }
                });
            }
        }
</script>

<script>
    function loadfilterDivision() {
        var url = base_url_js+'api/__getdivisiversion';
        $.getJSON(url,function (jsonResult) {
            for(var i=0;i<jsonResult.length;i++){
               $('.filterDivision').append('<option id="'+jsonResult[i].IDDivision+'"> '+jsonResult[i].Division+' </option>');
            }
        });
    }


    function loadSelectOptionDivision() {
        var url = base_url_js+'api/__getStatusVersion';
        $.getJSON(url,function (jsonResult) {
            for(var i=0;i<jsonResult.length;i++){
               $('.filterStatusDivision').append('<option id="'+jsonResult[i].ID+'"> '+jsonResult[i].Division+' </option>');
            }
        });
    }

    function loadSelectModule() {
        var url = base_url_js+'api/__getStatusModule';
        $.getJSON(url,function (jsonResult) {
            for(var i=0;i<jsonResult.length;i++){
               $('.filterStatusModule').append('<option id="'+jsonResult[i].IDModule+'"> '+jsonResult[i].NameModule+' </option>');
            }
        });
    }


    function loadPicversion() {
        var url = base_url_js+'api/__getpicversion';
        $.getJSON(url,function (jsonResult) {
            for(var i=0;i<jsonResult.length;i++){
               $('#selectpicversion').append('<option id="'+jsonResult[i].PIC+'"> '+jsonResult[i].Name+' </option>');
            }
        });
    }

   

</script>


<script>

    $(document).ready(function () {
        //loadSelectOptionDivision('#filterStatusDivision','');
        loadDataVersion('');
        //loadselectgroup('');
    });

    // $('').change(function () {
        
        
    // });


    function loadDataVersion(status) {
        var dataTable = $('#tableversion').DataTable( {
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"api/__getdataversion?s="+status, // json datasource
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );
    }
</script>