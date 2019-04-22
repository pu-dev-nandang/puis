<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class=""><i class="icon-reorder"></i> Data Group</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        
                    </div>
                </div>
            </div>
            <div class="widget-content col-md-8">
            	<div class="">
				    <table class="table table-bordered table-striped" id="tablemodule">
				        <thead>
				        <tr style="background: #3968c6;color: #FFFFFF;">
				            <th style="width: 1%;text-align: center;">No</th>
				            <th style="width: 5%;text-align: center;">Name Division</th>
                            <th style="width: 5%;text-align: center;">Name Module</th>
				            <th style="width: 2%;text-align: center;">Status</th>
				            <th style="width: 2%;text-align: center;">Action</th>
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
        loadDataModule('');
    });

    $('#filterStatusEmployees').change(function () {
        var s = $(this).val();
        //loadDataEmployees(s);
    });

    function loadDataModule(status) {
        var dataTable = $('#tablemodule').DataTable( {
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"api/__getdatamodule?s="+status, // json datasource
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
     $(document).on('click','.btndeletemodule',function () {
        if (window.confirm('Are you sure to delete group data ?')) {
            
            var versionid = $(this).attr('versionid');
            
            var data = {
                action : 'deletemodules',
                versionid : versionid
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+"api/__deleteversion";
            $.post(url,{token:token},function (result) {
                toastr.success('Success Delete Group Data!','Success'); 
                setTimeout(function () {
                    window.location.href = '';
                },1000);
            });
        }
    });
</script>

<script>
    $(document).on('click','.btneditgroups', function () {

        var versionid = $(this).attr('groupid');
        var url = base_url_js+'api/__getdetailgroupmod?s='+versionid;                          
        var token = jwt_encode({
                action:'geteditgroupdata'
            },'UAP)(*');

        $.post(url,{token:token},function (resultJson) {
            console.log(resultJson); 
            var response = resultJson;
                if(response.length>0){
                    var no = 1;
                    for (var i = 0; i < response.length; i++) {

                            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
                                ' <span aria-hidden="true">&times;</span></button> '+
                                ' <h4 class="modal-title">Edit Data Group </h4>');
                            $('#GlobalModal .modal-body').html('<table class="table">' +
                                 '<tr>' +
                                '   <td style="width: 25%;">Name Division</td>' +
                                '   <td><select class="form-control filterDivision" id="filterDivisiom"><option id="'+response[i]['IDDivision']+'" disabled selected>'+response[i]['Division']+'</option></select></td>' +
                                '  <td><input type="hidden" class="form-control" id="IDGroupedit" value="'+response[i]['IDGroup']+'"></td>' +
                                '</tr>' +
                                '<tr>' +
                                '   <td style="width: 25%;">Name Group </td>' +
                                '   <td><input class="form-control" id="editNameGroups" value="'+response[i]['NameGroup']+'"></td>' +
                                '</tr>' +
                                '</table>');

                            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-danger btn-round" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button> <button type="button" class="btn btn-success btn-round btneditsavegroups" dataidgroup="'+response[i]['IDGroup']+'"><span class="glyphicon glyphicon-floppy-disk"></span>  Save</button>');

                            $('#GlobalModal').modal({
                                    'backdrop' : 'static',
                                    'show' : true
                                }); 
                            loadfilterDivision();
                 
                    } //end for
                } //end if
            }); //end json  
         //END IF
    });
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

</script>
