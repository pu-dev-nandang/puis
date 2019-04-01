
<style>
    #tableEmployees tr th{
        text-align: center;
    }
</style>

<style>
    .btn-circle.btn-xl {
    width: 70px;
    height: 70px;
    padding: 10px 16px;
    border-radius: 35px;
    font-size: 24px;
    line-height: 1.33;
}

.btn-circle {
    width: 30px;
    height: 30px;
    padding: 6px 0px;
    border-radius: 15px;
    text-align: center;
    font-size: 12px;
    line-height: 1.42857;
}

.btn-round{
	border-radius: 17px;
}

</style> 
<!-- class="th-center"  -->
<div class="col-md-12" style="margin-bottom: 15px;">
        <span class="btn btn-primary btn-round btn-action" data-action="addGroupModule"><i class="fa fa-plus-circle"></i> Group Module</span> 
    </div>

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
				            <th style="width: 10%;text-align: center;">Name Module</th>
				            <th style="width: 32%;text-align: center;">Description</th>
				    		<th style="width: 8%;text-align: center;">Date Update </th>
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
     $(document).on('click','.btndeleteversion',function () {
        if (window.confirm('Are you sure to delete version data ?')) {
            loading_button('.btndeleteversion');

            var versionid = $(this).attr('versionid');
            var data = {
                action : 'deleteversion',
                versionid : versionid
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+"api/__deleteversion";
            $.post(url,{token:token},function (result) {
                toastr.success('Success Delete Version Data!','Success'); 
                setTimeout(function () {
                    window.location.href = '';
                },1000);
            });
        }
    });
</script>

<script>
    $(document).on('click','.btneditversion', function () {

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
			            ' <h4 class="modal-title">Detail Version</h4>');
                    $('#GlobalModal .modal-body').html('<table class="table">' +
			             '<tr>' +
			            '	<td style="width: 25%;">No. Version</td>' +
			            '	<td><input class="form-control" id="Namegroup" value="'+response[i]['Version']+'" disabled></td>' +
			            '</tr>' +
			            '<tr>' +
			            '	<td style="width: 25%;">Name Division</td>' +
			            '	<td><input class="form-control" id="Namegroup" value="'+response[i]['Division']+'" disabled></td>' +
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
			            ' <h4 class="modal-title">Detail Version</h4>');
                    $('#GlobalModal .modal-body').html('<table class="table">' +
			             '<tr>' +
			            '	<td style="width: 25%;">No. Version</td>' +
			            '	<td>'+response[i]['Version']+'</td>' +
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
	$('.btn-action').click(function () {
        var action = $(this).attr('data-action');
        var btnSave = (action=='addGroupModule') ? 'add' : 'edit';
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
        	' <span aria-hidden="true">&times;</span></button> '+
            ' <h4 class="modal-title">Add Group Module</h4>');
        $('#GlobalModal .modal-body').html('<table class="table">' +
            '<tr>' +
            '	<td style="width: 25%;">Name Division</td>' +
            '	<td><select class="form-control filterStatusDivision" value="" ><option value="" disabled> --- Select Name Division --- </option></select></td>' +
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
            '	<td style="width: 25%;">Name PIC</td>' +
            '	<td><select class="select2-select-00 form-exam" id="filternamepic" style="max-width: 300px !important;" size="5"><option value=""></option>'+
            '	</select></td>   ' +
            '</tr>' +
            
            '<tr>' +
            '	<td style="width: 25%;">Name Module</td>' +
            '	<td><select class="form-control filterStatusModule" value="" ><option value="" disabled> --- Select Name Module --- </option></select></td>' +
            '</tr>' +
            '<tr>' +
            '	<td style="width: 25%;">Description</td>' +
            '	<td><textarea id="descriptionversion"></textarea></td>' +
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

       
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-danger btn-round" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>' +
            '<button type="button" class="btn btn-success btn-round btnSaveVersion"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

</script>

<script>
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

   

</script>

<script>
    $(document).on('click','.btnSaveGroup',function () {
        savegroupmodule();
    });

    function savegroupmodule() {

        var selectdivision = $('.filterStatusDivision option:selected').attr('id');
        var Namegroup = $('#Namegroup').val();
        var Namemodule = $('#Namemodule').val();
        var Descriptiongroup = $('#Descriptiongroup').val();
        
        if(selectdivision!=null && selectdivision!=''
                    //&& selectdivision!='' && selectdivision!=null
                    && Namegroup!='' && Namegroup!=null
                    && Namemodule!='' && Namemodule!=null
                    && Descriptiongroup!='' && Descriptiongroup!=null)
        { 
            //loading_button('#btnSubmitEmployees');
            //$('#btnCloseEmployees').prop('disabled',true);
            var data = {
                action : 'AddGroupModule',
                formInsert : {
                	division : selectdivision,
                	Namegroup : Namegroup,
                	Namemodule : Namemodule,
                	Descriptiongroup : Descriptiongroup
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudGroupModule';
            $.post(url,{token:token},function (result) {
                    
                if(result==0 || result=='0'){
                    toastr.error('Name division or module already is exist!','Error');
                } else {  //if success save data
                	toastr.success('Group Module Saved','Success');
                	setTimeout(function () {
	                $('#GlobalModal').modal('hide');
	                    window.location.href = '';
	                },1000);
                }
            });
        }
        else {
            toastr.error('The form is still empty!','Error');
            $('#GlobalModal').modal('show');
            return;
        }
     }
</script>

<script>
    $(document).on('click','.btnSaveVersion',function () {
        savedataversion();
    });

    function savedataversion() {

        //var filternamepic = $('#filternamepic option:selected').attr('id');
        var filternamepic = $('#filternamepic').val();
        var filterStatusModule = $('.filterStatusModule option:selected').attr('id');
        var Noversion = $('#Noversion').val();
        var Descriptionversion = $('#descriptionversion').val();
        
        if(filternamepic!=null && filternamepic!=''
                    //&& selectdivision!='' && selectdivision!=null
                    && filterStatusModule!='' && filterStatusModule!=null
                    && Noversion!='' && Noversion!=null
                    && Descriptionversion!='' && Descriptionversion!=null)
        { 
            //loading_button('#btnSubmitEmployees');
            //$('#btnCloseEmployees').prop('disabled',true);
            var data = {
                action : 'AddVersion',
                formInsert : {
                	filternamepic : filternamepic,
                	filterStatusModule : filterStatusModule,
                	Noversion : Noversion,
                	Descriptionversion : Descriptionversion
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudGroupModule';
            $.post(url,{token:token},function (result) {
                    
                if(result==0 || result=='0'){
                    toastr.error('Version already is exist!','Error');
                } else {  //if success save data
                	toastr.success('Version Data Saved','Success');
                	setTimeout(function () {
                	$('#GlobalModal').modal('hide');
                    	window.location.href = '';
                  	},1000);
                }
            });
        }
        else {
            toastr.error('The form is still empty!','Error');
            $('#GlobalModal').modal('show');
            return;
        }
     }
</script>


<script>

    $(document).ready(function () {
        //loadSelectOptionDivision('#filterStatusDivision','');
        loadDataVersion('');
    });

    $('#filterStatusEmployees').change(function () {
        var s = $(this).val();
        //loadDataEmployees(s);
    });

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