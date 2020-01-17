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

.btn-group > .btn:first-child, .btn-group > .btn:last-child {
     border-radius: 17px;
}
</style> 


<style>
.widget.box {
    border: 1px solid #3968c6;
}
.widget.box .widget-header {
  background: #f9f9f9;
  border-bottom-color: #3968c6;
  line-height: 35px;
  padding: 0 12px;
  margin-bottom: 0;
}
</style>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">

        <div class="col-md-6">
            <div class="widget box">
                <div class="widget-header">
                    <h4 class=""><i class="icon-reorder"></i> Data University</h4>
                    <div class="toolbar no-padding">
                        <div class="">
                            <span class="btn btn-xs btn-primary btn-version add_university" >
                                <i class="icon-plus"></i> Add University
                            </span>
                        </div>
                    </div>
                </div>
                <div class="widget-content">
                    <div class="">
                        <div id="tableuniversity"></div>   
                    </div>            
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="widget box">
                <div class="widget-header">
                    <h4 class=""><i class="icon-reorder"></i> Data Major/ Program Study</h4>
                    <div class="toolbar no-padding">
                        <div class="">
                            <span class="btn btn-xs btn-primary btn-version add_major">
                                <i class="icon-plus"></i> Add Major/ Program Study
                            </span>
                        </div>
                    </div>
                </div>
                <div class="widget-content">
                    <div class="">
                        <div id="tablemajor"></div>   
                    </div>            
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">


        <div class="col-md-6">
            <div class="widget box">
                <div class="widget-header">
                    <h4 class=""><i class="icon-reorder"></i> Category Other Files</h4>
                    <div class="toolbar no-padding">
                        <div class="">
                            <span class="btn btn-xs btn-primary add_kat_otherfiles">
                                <i class="icon-plus"></i> Add Category Other File
                            </span>
                        </div>
                    </div>
                </div>
                <div class="widget-content">
                    <div class="">
                        <div id="tablekatotherfile"></div>   
                    </div>            
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).on('click','.btnSubmitUniversity', function () {
        var master_nameuniv = $('#master_nameuniv').val();

            if(master_nameuniv!='' && master_nameuniv!=null){
                loading_button('#btnSubmitUniversity');

                var data = {
                    action : 'update_mstruniv',
                    master_nameuniv : master_nameuniv
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api/__loadMstruniversity';

                $.post(url,{token:token},function (jsonResult) {

                    if(jsonResult==0 || jsonResult=='0') { 
                        toastr.error('Sorry, Name University Already!','Error');
                        $('#btnSaveLembaga').html('Save').prop('disabled',false);

                    } else {

                        $('#master_nameuniv').val('');
                        toastr.success('Data saved','Success');
                        $('#NotificationModal').modal('hide');
                        loadtableuniversity();

                        setTimeout(function () {
                            $('#btnSaveLembaga').html('Save').prop('disabled',false);
                        },500);
                    }
                });

            } else {
                toastr.warning('All form is required','Warning');
            }
    });


    $(document).on('click','.btnSubmitMajor', function () {
        var master_namemajor = $('#master_namemajor').val();

            if(master_namemajor!='' && master_namemajor!=null){
                loading_button('.btnSubmitMajor');

                var data = {
                    action : 'update_mstermajor',
                    master_namemajor : master_namemajor
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api/__loadMstruniversity';

                $.post(url,{token:token},function (jsonResult) {

                    if(jsonResult==0 || jsonResult=='0') { 
                        toastr.error('Sorry, Name Major/ Program Study Already!','Error');
                        $('.btnSubmitMajor').html('Save').prop('disabled',false);

                    } else {

                        toastr.success('Data saved','Success');
                        loadtablemajor();
                        $('#NotificationModal').modal('hide');

                        setTimeout(function () {
                            $('#btnSubmitMajor').html('Save').prop('disabled',false);
                        },500);
                    }
                });

            } else {
                toastr.warning('All form is required','Warning');
            }
    });


    $(document).on('click','.btnSubmitKatOtherFiles', function () {
        var master_name_katother = $('#master_kat_otherfiles').val();

            if(master_name_katother!='' && master_name_katother!=null){
                loading_button('.btnSubmitKatOtherFiles');

                var data = {
                    action : 'update_mster_katother',
                    master_name_katother : master_name_katother
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api/__loadMstruniversity';

                $.post(url,{token:token},function (jsonResult) {

                    if(jsonResult==0 || jsonResult=='0') { 
                        toastr.error('Sorry, Name Category Other File Already!','Error');
                        $('.btnSubmitKatOtherFiles').html('Save').prop('disabled',false);

                    } else {

                        toastr.success('Data saved','Success');
                        loadtable_kat_otherfile();
                        $('#NotificationModal').modal('hide');

                        setTimeout(function () {
                            $('.btnSubmitKatOtherFiles').html('Save').prop('disabled',false);
                        },500);
                    }
                });

            } else {
                toastr.warning('All form is required','Warning');
            }
    });

</script>

<script>
    $(document).on('click','.add_university', function () {
        var body = '<div class="row">' +
            '         <div class="col-md-12">' +
            '           <h4><b>ADD UNIVERSITY </b></h4>' +
            '           <div class="well">' +
            '               <div class="form-group">' +
            '                   <label>Name University</label>'+
            '                   <input class="form-control" id="master_nameuniv">' +
            '               </div>' +
            '           </div>' +
            '           <div class="btn-group pull-right">   '+ 
            '                <button type="button" class="btn btn-danger btn-round" data-dismiss="modal"> <i class="fa fa-remove"></i>Cancel</button> '+ 
            '                <button type="button" class="btn btn-success btn-round btnSubmitUniversity"> <i class="glyphicon glyphicon-floppy-disk"></i> Save</button> '+ 
            '           </div>  '+ 
            '        </div> ' +
            '      </div>';
        $('#NotificationModal .modal-body').html(body);
        $('#NotificationModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    $(document).on('click','.add_major', function () {
        var body = '<div class="row">' +
            '         <div class="col-md-12">' +
            '           <h4><b> ADD MAJOR/ PROGRAM STUDY</b></h4>' +
            '           <div class="well">' +
            '               <div class="form-group">' +
            '                   <label>Name Major/ Program Study</label>'+
            '                   <input class="form-control" id="master_namemajor">' +
            '               </div>' +
            '           </div>' +
            '           <div class="btn-group pull-right">   '+ 
            '                <button type="button" class="btn btn-danger btn-round" data-dismiss="modal"> <i class="fa fa-remove"></i>Cancel</button> '+ 
            '                <button type="button" class="btn btn-success btn-round btnSubmitMajor"> <i class="glyphicon glyphicon-floppy-disk"></i> Save</button> '+
            '           </div>  '+ 
            '        </div> ' +
            '      </div>';
        $('#NotificationModal .modal-body').html(body);
        $('#NotificationModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    $(document).on('click','.add_kat_otherfiles', function () {
        var body = '<div class="row">' +
            '         <div class="col-md-12">' +
            '           <h4><b> ADD CATEGORY OTHER FILES</b></h4>' +
            '           <div class="well">' +
            '               <div class="form-group">' +
            '                   <label>Name Category Other File</label>'+
            '                   <input class="form-control" id="master_kat_otherfiles">' +
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
</script>

<script>
    $(document).ready(function () {
        loadtableuniversity();
        loadtablemajor();
        loadtable_kat_otherfile();
    });

    function loadtableuniversity() {  
        $('#tableuniversity').html('<table class="table table-bordered table-striped" id="tableDatauniversity">     '+
            '                    <thead>                                                                            '+
            '                    <tr style="background: #20485A;color: #FFFFFF;">                                   '+
            '                        <th style="width: 5%;text-align: center;">No</th>                              '+
            '                        <th style="width: 25%; text-align: center;">Name University</th>               '+
            '                        <th style="text-align: center;width: 8%;">Action</th>                          '+
            '                    </tr>                                                                              '+
            '                    </thead>                                                                           '+
            '                </table>');

        var token = jwt_encode({action:'readlist_university'},'UAP)(*');
        var dataTable = $('#tableDatauniversity').DataTable( {
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

    function loadtablemajor() {  
        $('#tablemajor').html('<table class="table table-bordered table-striped" id="tableDataMajor">                       '+
            '                    <thead>                                                                                    '+
            '                    <tr style="background: #20485A;color: #FFFFFF;">                                           '+
            '                        <th style="width: 5%;text-align: center;">No</th>                                      '+
            '                        <th style="width: 25%; text-align: center;">Name Major/ Program Study</th>             '+
            '                        <th style="text-align: center;width: 8%;">Action</th>                                  '+
            '                    </tr>                                                                                      '+
            '                    </thead>                                                                                   '+
            '                </table>');

        var token = jwt_encode({action:'readlist_major'},'UAP)(*');
        var dataTable = $('#tableDataMajor').DataTable( {
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

    function loadtable_kat_otherfile() {  
        $('#tablekatotherfile').html('<table class="table table-bordered table-striped" id="tableDataKatOtherFile">     '+
            '                    <thead>                                                                                '+
            '                    <tr style="background: #20485A;color: #FFFFFF;">                                       '+
            '                        <th style="width: 5%; text-align: center;">No</th>                                  '+
            '                        <th style="width: 25%; text-align: center;">Name Category Other Files</th>         '+
            '                        <th style="text-align: center;width: 8%;">Action</th>                              '+
            '                    </tr>                                                                                  '+
            '                    </thead>                                                                               '+
            '                </table>');

        var token = jwt_encode({action:'readlist_katotherfiles'},'UAP)(*');
        var dataTable = $('#tableDataKatOtherFile').DataTable( {
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
     $(document).on('click','.btndelotherfile',function () {
        if (window.confirm('Are you sure to delete file ?')) {
           
            var id_otherfile = $(this).attr('Idotherfile');
            var typedata = $(this).attr('typedata');

            var data = {
                action : 'deleteother_master',
                id_otherfile : id_otherfile,
                typedata : typedata
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+"api/__reviewotherfile";
            $.post(url,{token:token},function (result) {
                toastr.success('Success Delete File!','Success'); 
                setTimeout(function () {
                    $('.menuDetails[data-page="otherfiles"]').trigger('click');
                   window.location.href = '';
                },1000);
            });
        }
    });
</script>