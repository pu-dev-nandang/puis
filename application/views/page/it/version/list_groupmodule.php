<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class=""><i class="icon-reorder"></i> Data Group Module</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        
                    </div>
                </div>
            </div>
            <div class="widget-content">
            	<div class="">
				    <table class="table table-bordered table-striped" id="tablegroupmodule">
				        <thead>
				        <tr style="background: #3968c6;color: #FFFFFF;">
				            <th style="width: 1%;text-align: center;">No</th>
				            <th style="width: 9%;text-align: center;">Name Division</th>
                            <th style="width: 10%;text-align: center;">Name Group Module</th>
				            <th style="width: 10%;text-align: center;">Name Module</th>
				            <th style="width: 32%;text-align: center;">Description</th>
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


<script>

    $(document).ready(function () {
        //loadSelectOptionDivision('#filterStatusDivision','');
        loadDataGroupModule('');
    });

    $('#filterStatusEmployees').change(function () {
        var s = $(this).val();
        //loadDataEmployees(s);
    });

    function loadDataGroupModule(status) {
        var dataTable = $('#tablegroupmodule').DataTable( {
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"api/__getdatagroupmodule?s="+status, // json datasource
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

<script>
    $(document).on('click','.btnviewgroupmodule', function () {

        var versionid = $(this).attr('versionid');
        var url = base_url_js+'api/__getdetailgroupmod?s='+versionid;                          
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
                        ' <h4 class="modal-title">Detail Group module</h4>');
                    $('#GlobalModal .modal-body').html('<table class="table">' +
                     
                        '<tr>' +
                        '   <td style="width: 35%;">Name Division</td>' +
                        '   <td>'+response[i]['ID']+' - <b> '+response[i]['Division']+'</b></td>' +
                        '</tr>' +
                        '<tr>' +
                           '<tr>' +
                        '   <td style="width: 35%;">Name Group Module</td>' +
                        '   <td>'+response[i]['IDGroup']+' - <b>'+response[i]['NameGroup']+'</b></td>' +
                        '</tr>' +
                        '   <td style="width: 35%;">Name Module</td>' +
                        '   <td>'+response[i]['IDModule']+' - <b>'+response[i]['NameModule']+'</b></td>' +
                        '</tr>' +
                        '<tr>' +
                        '   <td style="width: 35%;">Description Module</td>' +
                        '   <td><b>'+response[i]['Description']+'</b></td>' +
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
    $(document).on('click','.btneditgroupmodule', function () {

        var versionid = $(this).attr('groupid');
        var url = base_url_js+'api/__getdetailgroupmod?s='+versionid;                          
        var token = jwt_encode({
                action:'getedit'
            },'UAP)(*');

        $.post(url,{token:token},function (resultJson) {
            console.log(resultJson); 
            var response = resultJson;
                if(response.length>0){
                    var no = 1;
                    for (var i = 0; i < response.length; i++) {

                        var url = base_url_js+'api/__dropdowneditgroupmod';
                        var token = jwt_encode({action : 'getLastdiversion', IDDivision : ''+response[i]['ID']+'' },'UAP)(*');

                        $.post(url,{token:token},function (jsonResult) {
                            //$('#filtereditgroupmodule').append('<option value="'+response[i]['NameGroup']+'" disabled selected> '+response[i]['NameGroup']+' </option>');
                            for(var i=0;i<jsonResult.length;i++){
                                    $('#filtereditgroupmodule').append('<option id="'+jsonResult[i].IDGroup+'"> '+jsonResult[i].NameGroup+' </option>');
                            }
                        });

                            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
                                ' <span aria-hidden="true">&times;</span></button> '+
                                ' <h4 class="modal-title">Edit Group Module</h4>');
                            $('#GlobalModal .modal-body').html('<table class="table">' +
                                 '<tr>' +
                                '   <td style="width: 25%;">Name Division</td>' +
                                '   <td><input class="form-control" id="editidgroupmodule" value="'+response[i]['Division']+'" disabled></td>' +
                                '  <td><input type="hidden" class="form-control" id="IDModuledit" value="'+response[i]['IDModule']+'"></td>' +
                                '</tr>' +
                                '<tr>' +
                                '   <td style="width: 25%;">Name Group Module</td>' +
                                '   <td><select class="form-control filtergroupmodule" id="filtereditgroupmodule"><option id="'+response[i]['IDGroup']+'" disabled selected> '+response[i]['NameGroup']+' </option> </select></td>' +
                                '</tr>' +
                                '<tr>' +
                                '   <td style="width: 25%;">Name Module</td>' +
                                '   <td> <select class="form-control" id="filtereditgroupname"><option id="'+response[i]['IDModule']+'" disabled selected> '+response[i]['NameModule']+' </option></select></td>' +
                                '</tr>' +
                                '<tr>' +  
                                '   <td style="width: 25%;">Description</td>' +
                                '   <td><textarea id="editdescriptiongroup">'+response[i]['Description']+'</textarea></td>' +
                                '</tr>' +
                                '</table>');

                            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-danger btn-round" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button> <button type="button" class="btn btn-success btn-round btneditsavegroupmod" dataidgroup="'+response[i]['IDGroup']+'"><span class="glyphicon glyphicon-floppy-disk"></span>  Save</button>');

                            $('#GlobalModal').modal({
                                    'backdrop' : 'static',
                                    'show' : true
                                }); 
                            loadSelectOptionEmployeesSingle('#filternamepic','');
                            $('#filternamepic').select2({allowClear: true});


                            $('#editdescriptiongroup').summernote({
                                placeholder: 'Text your Description Group',
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
    $(document).on('change','#filtereditgroupmodule',function () {
        var s = $(this).val();
        $("#filtereditgroupname").empty();
        loadeditselectmodule();
    
    });

    function loadeditselectmodule() {

        var filtereditgroup = $('#filtereditgroupmodule option:selected').attr('id');
        
        if(filtereditgroup!='' && filtereditgroup!=null){
            var url = base_url_js+'api/__dropeditmodule';
            var token = jwt_encode({action : 'geteditLastmodule', filtereditgroup : filtereditgroup},'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {
                    $('#filtereditgroupname').append('<option disabled selected></option>');
                    for(var i=0;i<jsonResult.length;i++){
                           $('#filtereditgroupname').append('<option id="'+jsonResult[i].IDModule+'"> '+jsonResult[i].NameModule+' </option>');
                    }
                });
            }
        }
</script>




