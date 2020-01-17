
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
    text-align: center;
}

.btn-group > .btn:first-child, .btn-group > .btn:last-child {
     border-radius: 17px;
}

</style> 

<div class="filter-form" style="margin:0 auto;width:45%;text-align:center;margin-bottom:3em;">
    <div class="row">
        <div class="col-md-5">
            <div class="thumbnail">
                <select class="form-control" id="filterTypeFiles">
                    <option id="0" selected disabled="">--- All Type Files Academic ---</option>
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="thumbnail">
                <select class="form-control" id="filterCategoryOtherFiles">
                    <option id="0" selected disabled>--- All Category Other Files ---</option>
                </select>
            </div>
            
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class=""><i class="icon-reorder"></i> Data Academic Employee Files</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group"></div>
                </div>
            </div>
            <div class="widget-content no-padding">

                <table class="table table-bordered table-striped" id="tablerequestdoc">
                    <thead>
                        <tr style="background: #3968c6;color: #FFFFFF;">
                            <th style="width: 1%;">No</th>
                            <th class="th-center" style="width: 10%;">NIP & Name</th>
                            <th class="th-center" style="width: 8%;">Type File</th>
                            <th class="th-center" style="width: 9%;">Category Other File</th>
                            <th class="th-center" style="width: 12%;">Description File</th>
                            <th class="th-center" style="width: 10%;">User Upload </th>
                            <th class="th-center" style="width: 8%;">Date Upload </th>
                            <th class="th-center" style="width: 7%;">Link File</th>
                            <th class="th-center" style="width: 7%;">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click','.btnviewgroupmodule', function () {

        var file_id = $(this).attr('file_id');
       
        var url = base_url_js+'api/__reviewotherfile?s='+file_id; 
                      
        var token = jwt_encode({
                    action:'get_detailfiles_rektorat',
            },'UAP)(*');

        $.post(url,{token:token},function (resultJson) {
            console.log(resultJson); 
            var response = resultJson;
                if(response.length>0){
                    var no = 1;
                    for (var i = 0; i < response.length; i++) {

                    if(response[i]['TypeFiles'] < 6) {   // 'Only Ijazah Transcript'
                        
                        if(response[i]['DateIjazah'] == null) {
                            var tgl_ijazah = "-";
                        }
                        else {
                            var tgl_ijazah = moment(response[i]['DateIjazah']).format('DD MMM YYYY');
                        }
                        
                        if(response[i]['Name_University'] == null || response[i]['NoIjazah'] == null) {

                            var nama_univ = "-";
                            var no_ijazah = "-";
                            var name_major = "-";
                            var name_program = "-";
                            var data_grade = "-";
                            var data_credit = "-";
                            var data_semester = "-";

                        } else {
                            var nama_univ = response[i]['Name_University'];
                            var no_ijazah = response[i]['NoIjazah'];
                            var name_major = response[i]['Name_MajorProgramstudy'];
                            var name_program = response[i]['NamaProgramStudy'];
                            var data_grade = response[i]['Grade'];
                            var data_credit = response[i]['TotalCredit'];
                            var data_semester = response[i]['TotalSemester'];
                        }

                        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
                            ' <span aria-hidden="true">&times;</span></button> '+
                            ' <h4 class="modal-title">Detail Academic Employee File</h4>');
                        $('#GlobalModal .modal-body').html('<span><b>Data Academic</b></span>' +
                            '<table class="table table-striped">'+
                            '<tr>' +
                            '   <td style="width: 40%;">NIP & Name </td>' +
                            '   <td><b>'+response[i]['NIP']+' - '+response[i]['Name']+'<b></td>' +
                            '</tr>' +
                            '<tr>' +
                            '   <td style="width: 40%;">Type File </td>' +
                            '   <td><b>'+response[i]['M_TypeFiles']+'<b></td>' +
                            '</tr>' +
                            '</tr>' +
                            '   <td style="width: 40%;">Name Univesity</td>' +
                            '   <td><b>'+nama_univ+'<b></td>' +
                            '</tr>' +
                            '<tr>' +
                            '   <td style="width: 40%;">No Ijazah</td>' +
                            '   <td><b>'+no_ijazah+'<b></td>' +
                            '</tr>' +
                            '<tr>' +
                            '   <td style="width: 40%;">Date Ijazah</td>' +
                            '   <td><b>'+tgl_ijazah+' <b></td>' +
                            '</tr>' +
                             '<tr>' +
                            '   <td style="width: 40%;">Major</td>' +
                            '   <td><b>'+name_major+'<b></td>' +
                            '</tr>' +
                            '<tr>' +
                            '   <td style="width: 40%;">Program Study</td>' +
                            '   <td><b>'+name_program+' <b></td>' +
                            '<tr>' +
                            '<tr>' +
                            '   <td style="width: 40%;">Grade/ IPK</td>' +
                            '   <td><b>'+data_grade+' <b></td>' +
                            '<tr>' +
                            '<tr>' +
                            '   <td style="width: 40%;">Total Credit</td>' +
                            '   <td><b>'+data_credit+' <b></td>' +
                            '<tr>' +
                            '<tr>' +
                            '   <td style="width: 40%;">Total Semester</td>' +
                            '   <td><b>'+data_semester+' <b></td>' +
                            '<tr>' +
                            '</table> '+
                            '');
                        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-primary btn-round" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>');
                        $('#GlobalModal').modal({
                            'backdrop' : 'static',
                            'show' : true
                        }); 
                    } 
                    else {        // 'Other Files'

                        var tgl_file = moment( response[i]['Date_Files']).format('DD MMM YYYY');
                        
                        if(response[i]['ID_OtherFiles'] == null) {
                            var data_otherfile = "-";
                        }
                        else {
                            var data_otherfile = response[i]['ID_OtherFiles'];
                        }

                        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
                            ' <span aria-hidden="true">&times;</span></button> '+
                            ' <h4 class="modal-title">Detail Academic Employee File</h4>');
                        $('#GlobalModal .modal-body').html('<span><b>Data Other File</b></span>' +
                            '<table class="table table-striped">'+
                            '<tr>' +
                            '   <td style="width: 40%;">NIP & Name </td>' +
                            '   <td><b>'+response[i]['NIP']+' - '+response[i]['Name']+'<b></td>' +
                            '</tr>' +
                            '<tr>' +
                            '   <td style="width: 40%;">Type File </td>' +
                            '   <td><b>'+response[i]['M_TypeFiles']+'<b></td>' +
                            '</tr>' +
                            '</tr>' +
                            '   <td style="width: 40%;">Category Other File</td>' +
                            '   <td><b>'+data_otherfile+'<b></td>' +
                            '</tr>' +
                            '<tr>' +
                            '   <td style="width: 40%;">No. Document</td>' +
                            '   <td><b>'+response[i]['No_Document']+'<b></td>' +
                            '</tr>' +
                            '<tr>' +
                            '   <td style="width: 40%;">Date Document</td>' +
                            '   <td><b>'+tgl_file+' <b></td>' +
                            '</tr>' +
                             '<tr>' +
                            '   <td style="width: 40%;">Description Files</td>' +
                            '   <td><b>'+response[i]['Description_Files']+'<b></td>' +
                            '</tr>' +
                            '</table> '+
                            '');
                        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-primary btn-round" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>');
                    
                        $('#GlobalModal').modal({
                            'backdrop' : 'static',
                            'show' : true
                        }); 

                    }


                    
                        
                    } //end for
                } //end if
            }); //end json  
         //END IF
    });
</script>


<script>
    $(document).ready(function () {
        load_listacademicemployee('');
        loadfiltertypefiles();
    });

    function load_listacademicemployee(status) {
        var FilesType = $('#filterTypeFiles option:selected').attr('id');
        var FilterOther = $('#filterCategoryOtherFiles option:selected').attr('id');

        var dataTable = $('#tablerequestdoc').DataTable( {
            "processing": true,
            "destroy": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "ajax":{
                url : base_url_js+"api/__getacademicfiles?type="+FilesType+"&other="+FilterOther,
                ordering : false,
                type: "post",  
                error: function(){  
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );
    }
</script>

<script>

    $('#filterTypeFiles').change(function (event) {
    
        var filterKategoriJenis = $('#filterTypeFiles option:selected').attr('id');
        $("#filterCategoryOtherFiles").empty();

        if(filterKategoriJenis == "13") {

            $("#filterCategoryOtherFiles").empty();
            var url = base_url_js+'api/__reviewotherfile';
            var token = jwt_encode({action : 'get_katotherfiles'},'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {
                $('#filterCategoryOtherFiles').append('<option id="" disabled selected>--- Select Other Files ---</option>');
                    for(var i=0;i<jsonResult.length;i++){
                            $('#filterCategoryOtherFiles').append('<option id="'+jsonResult[i].ID+'"> '+jsonResult[i].Name_other_files+' </option>');
                }
            });
            //load_listacademicemployee('');
        } 
        else {

            $('#filterCategoryOtherFiles').append('<option id="" disabled selected>--- All Category Other Files ---</option>');
            load_listacademicemployee('');
        }

    });


    $('#filterCategoryOtherFiles').change(function (event) {
        load_listacademicemployee();
    });

</script>

<script>

    $(document).on('click','.btnviewlistsrata',function () {
        var filesub = $(this).attr('filesub');
       
            $('#NotificationModal .modal-header').addClass('hide');
            $('#NotificationModal .modal-body').html('<center> '+
                '<iframe src="'+base_url_js+'uploads/files/'+filesub+'" frameborder="0" style="width:745px; height:550px;"></iframe> '+
                '<br/><br/><button type="button" id="btnRemoveNoEditSc" class="btn btn-primary btn-round" data-dismiss="modal"><span class="fa fa-remove"></span> Close</button><button type="button" filesublix ="'+filesub+'" class="btn btn-primary btn-circle pull-right filesublink" data-toggle="tooltip" data-placement="top" title="Full Review"><span class="fa fa-external-link"></span></button>' +
            '</center>');
            $('#NotificationModal .modal-footer').addClass('hide');
            $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
            });
    });

    $(document).on('click','.save_allapproved',function () {
        var data = {
            action : 'approved_all',
            formInsert : {
                typerequest : "0"
            }
        };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api2/__crudrequestdoc';
                $.post(url,{token:token},function (result) {
                        
                    if(result==0 || result=='0'){
                         toastr.success('Data is already Approved!','Success');
                    } else {  
                        toastr.success('Success All Approved Request','Success');
                        setTimeout(function () {
                            window.location.href = '';
                        },1000);
                    }
            });
    });

    
    $(document).on('click','.btnapproved',function () {

        if (confirm('Are you sure Approved Request?')) {

            var requestID = $(this).attr('requestid');
            if(requestID!=null && requestID!='') { 

                var data = {
                action : 'Approved',
                formInsert : {
                    requestID : requestID
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__confirmrequest';
                $.post(url,{token:token},function (result) {
                    
                    if(result==0 || result=='0'){ 
                    } 
                    else { 
                        toastr.success('Success Approved Request','Success');
                        load_documentrequestlist();
                    }
                });
            }  
            else {
                toastr.error('Confirmation Error!','Error');
                return;
            }
        } 
    else { 
        return;
    }
});

</script>


<script>
$(document).on('click','.btnrejected',function () {
        
    if (confirm('Are you sure Rejected Request?')) {

        var requestID = $(this).attr('requestid'); 
        
        if(requestID!=null && requestID!='') { 
    
            var data = {
                action : 'Rejected',
                formInsert : {
                    requestID : requestID
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__confirmrequest';
            $.post(url,{token:token},function (result) {
                    
                if(result==0 || result=='0'){
            
                } else { 
                    toastr.success('Success Rejected Request!','Success');
                    load_documentrequestlist();
                }
            });
        }
        else {
            toastr.error('Confirmation Error!!','Error');
            return;
        }

    } else {
        return;
    }
});

</script> 

<script>

function loadfiltertypefiles() {
    var url = base_url_js+'api/__reviewotherfile';
    var token = jwt_encode({action : 'get_typefiles_rektorat'},'UAP)(*');

    $.post(url,{token:token},function (jsonResult) {
        //$('#filterTypeFiles').append('<option id="0" disabled selected>Select Type Files</option>');
            for(var i=0;i<jsonResult.length;i++){
                $('#filterTypeFiles').append('<option id="'+jsonResult[i].ID+'"> '+jsonResult[i].NameFiles+' </option>');
            }
    });
}
</script>