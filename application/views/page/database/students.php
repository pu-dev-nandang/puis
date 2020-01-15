<style>
    #table-list-data thead tr th {
        background: #20525a;
        color: #ffffff;
        text-align: center;
    }
    #table-list-data .detail-user > img.std-img{width: 45px;float: left;margin-right: 10px;}
    #table-list-data .detail-user > img.std-img{width: 45px;float: left;margin-right: 10px;}
    #table-list-data .detail-user > p{margin:0px;font-weight: bold;color: #4d7496}
    #table-list-data .detail-user > p.name{text-transform: uppercase;}
    #table-list-data .detail-user > p.email{font-weight: 100;color: #000}
</style>

<div id="student-data">
    <div class="row">
        <div class="col-sm-12">
            <div id="filter-form">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-filter"></i> Form Filter</h4>
                    </div>
                    <div class="panel-body">
                        <form id="form-filter" method="post" autocomplete="off">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Student</label>                              
                                        <input type="text" class="form-control" name="student" placeholder="NIM or Name or Email">                              
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Class of</label>                             
                                        <select class="form-control" name="Year">
                                            <option value="">-Choose year-</option>
                                            <?php if(!empty($yearIntake)) { 
                                            foreach ($yearIntake as $y) {
                                            echo '<option value="'.$y->Year.'">'.$y->Year.'</option>';
                                            } } ?>
                                        </select>                               
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Study Program</label>                                
                                        <select class="form-control" name="ProdiID" id="prodiID">
                                            <option value="">-Choose one-</option>
                                            <?php foreach ($studyprogram as $s) { 
                                            echo '<option value="'.$s->ID.'">'.$s->Name.'</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Group Student</label>  
                                        <select class="form-control" name="GroupProdiID" id="filterGroupProdi">
                                            <option value="">-Choose one-</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">                               
                                <div class="col-sm-2">
                                    <label class="show-more-filter text-success" data-toggle="collapse" data-target="#advance-filter" aria-expanded="false" aria-controls="advance-filter" style="padding-top:0px">
                                        <span>Advance filter</span> 
                                        <i class="fa fa-angle-double-down"></i>
                                    </label>
                                </div>
                            </div>
                            <div class="collapse" id="advance-filter">
                                <div class="row">
                                    <?php if(!empty($statusstd)){ ?>
                                    <div class="col-sm-3">
                                        <div class="form-groups">
                                            <label>Status</label>                            
                                        </div>
                                        <div class="row">
                                            <?php foreach ($statusstd as $t) { ?>
                                            <div class="col-sm-6">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="status[]" value="<?=$t->CodeStatus?>"> <?=$t->Description?>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>                                        
                                    </div>
                                    <?php } ?>

                                    <?php if(!empty($religion)){ ?>
                                    <div class="col-sm-1">
                                        <div class="form-groups">
                                            <label>Religion</label>
                                        </div>
                                        <div class="row">
                                            <?php foreach ($religion as $rg) { ?>
                                            <div class="col-sm-12">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="religion[]" value="<?=$rg->ID?>"> <?=$rg->Nama?>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    <div class="col-sm-1">
                                        <div class="form-groups">
                                            <label>Gender</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="gender[]" value="P"> Female
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="gender[]" value="L"> Male
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Birthdate</label>
                                            <div class="input-group">
                                                <input type="text" name="birthdate_start" id="birthdate_start" class="form-control" placeholder="Start date">   
                                                <div class="input-group-addon">-</div>
                                                <input type="text" name="birthdate_end" id="birthdate_end" class="form-control" placeholder="End date"> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Graduation Date</label>
                                            <div class="input-group">
                                                <input type="text" name="graduation_start" id="graduation_start" class="form-control" placeholder="Start date"> 
                                                <div class="input-group-addon">-</div>
                                                <input type="text" name="graduation_end" id="graduation_end" class="form-control" placeholder="End date">   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="padding-top:22px">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-filter" type="button"><i class="fa fa-search"></i> Search</button>
                                        <a class="btn btn-default" href="">Clear Filter</a>
                                    </div>
                                </div>
                                <?php $Dept = $this->session->userdata('IDdepartementNavigation'); if($Dept=='6') { ?>                    
                                <div class="col-sm-4 text-right">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-default" id="btnStdDownloadtoExcel"><i class="fa fa-download margin-right"></i> Export Students Information to Excel</button>
                                        <!-- <button type="button" class="btn btn-default" id="btnIPSIPKDownloadtoExcel"><i class="fa fa-download margin-right"></i> Export IPS/IPK Students to Excel</button>                                         -->
                                    </div>
                                </div>
                                <?php } ?>  
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?php $Dept = $this->session->userdata('IDdepartementNavigation'); if($Dept=='6') { ?>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class="panel-title"><i class="fa fa-cogs"></i> Action</h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-2">
                            <button class="btn btn-block btn-default" id="btnSelect"><i class="fa fa-id-card margin-right"></i> Select Student</button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-2">
                            <button class="btn btn-block btn-default" id="btnPrintIDCard"><i class="fa fa-id-card margin-right"></i> Print ID Card</button>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3" style="border-left:1px solid #ddd">
                            <a href="<?= base_url('database/students-group'); ?>" class="btn btn-block btn-default"><i class="fa fa-users margin-right"></i> Student Group</a>
                        </div>           
                        <div class="col-xs-12 col-sm-6 col-md-3" style="border-left:1px solid #ddd">
                            <button class="btn btn-block btn-default btn-approve unselect" type="button"><i class="fa fa-warning"></i> Need Approval for Request Biodata</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div id="fetch-data-tables">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class="panel-title"><i class="fa fa-bars"></i> List of students</h5>
                </div>
                <div class="panel-body">
                    <div id="sorting-data">
                        <div class="row">
                          <div class="col-sm-3">
                            <div class="form-group">
                              <label>Sort by</label>
                              <div class="input-group">
                                <select class="form-control" name="sort_by">
                                  <option value="">-</option>
                                  <option value="NPM">NPM</option>
                                  <option value="Name">Name</option>
                                  <option value="DateOfBirth">Birthdate</option>
                                  <option value="ClassOf">Class Of</option>
                                  <option value="ProdiNameEng">Study Program</option>
                                  <option value="Gender">Gender</option>
                                  <option value="religionName">Religion</option>
                                  <option value="StatusStudent">Status</option>
                                </select>
                                <div class="input-group-addon"></div>
                                <select class="form-control" name="order_by">
                                  <option value="ASC">ASCENDING</option>
                                  <option value="DESC">DESCENDING</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="table-list">
                        <table class="table table-bordered table-striped" id="table-list-data">
                            <thead>
                                <tr>
                                    <th width="2%">No</th>
                                    <th width="20%">Student</th>
                                    <th width="10%">Birthdate</th>
                                    <th width="5%">Religion</th>
                                    <th width="5%">Gender</th>
                                    <th width="5%">Class of</th>
                                    <th width="10%">Study Program</th>
                                    <th width="10%">Status</th>
                                    <th width="8%">Upload Photo</th>
                                    <th width="5%">Action</th>
                                    <th width="8%">Login Portal</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="fetchDataRequest"></div>


<script type="text/javascript">
    function fetchingData(isapprove=false,sort=null,order=null) {
        loading_modal_show();
        var filtering = $("#form-filter").serialize();
        if(isapprove){
            filtering = filtering+"&isapprove="+isapprove;
        }
        if((sort && order) || ( sort !== null && order !== null) ){
          filtering = filtering+"&sortby="+sort+"&orderby="+order;
        }    
        var token = jwt_encode({Filter : filtering},'UAP)(*');
        var dataTable = $('#fetch-data-tables #table-list-data').DataTable( {
            destroy: true,
            retrieve:true,
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "NIM, Name, Study Program"
            },
            "ajax":{
                url : base_url_js+'api/database/__getListStudentPS', // json datasource
                ordering : false,
                data : {token:token},
                type: "post",  // method  , by default get
                error: function(jqXHR){  // error handling
                    loading_modal_hide();
                    $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        '<h4 class="modal-title">Error Fetch Student Data</h4>');
                    $('#GlobalModal .modal-body').html(jqXHR.responseText);
                    $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
                    $('#GlobalModal').modal({
                        'show' : true,
                        'backdrop' : 'static'
                    });
                }
            },
            "initComplete": function(settings, json) {
                loading_modal_hide();
            }
        });
    }

    function getFormData($form){
        var unindexed_array = $form.serializeArray();
        var indexed_array = {};

        $.map(unindexed_array, function(n, i){
            indexed_array[n['name']] = n['value'];
        });

        return indexed_array;
    }

    $(document).ready(function(){
        //fetchingData();

        $("#birthdate_start,#birthdate_end,#graduation_start,#graduation_end").datepicker({
            dateFormat: 'dd-mm-yy',
            changeYear: true,
            changeMonth: true
        });
        $("#form-filter .btn-filter").click(function(){
            $('#fetch-data-tables #table-list-data').DataTable().destroy();
            fetchingData();
        });

        $("#btnSelect").click(function () {
            if (!$('.uniform').length) {
                var get_th = $("#table-list-data thead").find('tr').find('th:eq(0)').text();
                var checkbox = '<input type="checkbox" name="select_all" value="1" id="example-select-all">';
                $("#table-list-data thead").find('tr').find('th:eq(0)').html(get_th+'&nbsp'+checkbox);
                $("#table-list-data tr").each(function(){
                    var a = $(this);
                    var No = a.find('td:eq(0)').text();
                    var G_attr = a.find('td:eq(9)').find('.PrintIDCard');
                    var type = G_attr.attr('type');
                    var NPM = G_attr.attr('data-npm');
                    var Name = G_attr.attr('data-name');
                    var PathFoto = G_attr.attr('path');
                    var email = G_attr.attr('email');
                    var checkbox = '<input type="checkbox" class="uniform" type2 = "student" data-npm="'+NPM+'" data-name="'+Name+'" path = "'+PathFoto+'" email = "'+email+'">';
                    a.find('td:eq(0)').html(No+'&nbsp'+checkbox);
                });
            }
        });


        $("#fetch-data-tables").on("change","#example-select-all",function(){
            if($(this).is(':checked')){
                $("#fetch-data-tables").find("#table-list-data > tbody input[type=checkbox]").prop("checked",true);
            }else{
                $("#fetch-data-tables").find("#table-list-data > tbody input[type=checkbox]").prop("checked",false);                
            }
        });


        $('#btnStdDownloadtoExcel').click(function () {
            var data = getFormData($("#form-filter"));
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'save2excel/student-recap';
            var Year = $("#form-filter").find("select[name=Year]").val();
            if(!Year || Year.length == 0){
                $("#filter-form .panel").animateCss('shake');
                toastr.warning('Please fill form filter at least fill the field "Class of"','Warning');
            }else{
                FormSubmitAuto(url, 'POST', [{ name: 'token', value: token },]);
            }
        });

        $("#btnIPSIPKDownloadtoExcel").click(function(){
            var data = getFormData($("#form-filter"));
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'save2excel/cumulative-recap';
            var Year = $("#form-filter").find("select[name=Year]").val();
            var ProdiID = $("#form-filter").find("select[name=ProdiID]").val();
            if((!Year && Year.length == 0) || (!ProdiID && ProdiID.length == 0) ){
                $("#filter-form .panel").animateCss('shake');
                toastr.warning('Please fill field "Class of" and "Study Program".','Warning');
            }else{
                FormSubmitAuto(url, 'POST', [{ name: 'token', value: token },]);
            }
        });

        $(".show-more-filter").click(function(){
          var isOpen = $(this).attr("aria-expanded");
          if(isOpen == "false"){
            $(this).attr("aria-expanded",true);
            $(this).find("span").text("Show less");
            $(this).find("i.fa").toggleClass("fa-angle-double-down fa-angle-double-up");
          }else{
            $(this).attr("aria-expanded",false);
            $(this).find("span").text("Advance filter");        
            $(this).find("i.fa").toggleClass("fa-angle-double-up fa-angle-double-down");
          }
        });

        $("#sorting-data").on("change","select[name=sort_by]",function(){
          var value = $(this).val();
          var order = $("#sorting-data select[name=order_by]").val();
          var isappv = ($("#student-data .btn-approve").hasClass("selected")) ? true:false;
          $('#fetch-data-tables #table-list-data').DataTable().destroy();
          fetchingData(isappv,value,order);
        });

        $("#sorting-data").on("change","select[name=order_by]",function(){
          var order = $("#sorting-data select[name=sort_by]").val();
          var value = $(this).val();
          var isappv = ($("#student-data .btn-approve").hasClass("selected")) ? true:false;
          $('#fetch-data-tables #table-list-data').DataTable().destroy();
          fetchingData(isappv,order,value);
        });

        $("#prodiID").change(function(){
            var value = $(this).val();
            $("#filterGroupProdi").html("<option value=''>-Choose one-</option>");
            load_SO_ProdiGroup(value,'#filterGroupProdi','');
        });

    });
</script>












 

<script>
    var TaSegment = '<?php echo $this->uri->segment(3) ?>';
    var TableSess = '';
    var TempCheckBoxIDCard = [];

    <?php
        // Get Session Division 
        $P = $this->session->userdata('PositionMain'); 
        $PN = $P['Division'];
        $PID = $P['IDDivision'];
    ?>
    var DivSessionName = '<?php echo $PN ?>'; 
    var DivSessionID = '<?php echo $PID ?>';
    // function remove button
    var waitForEl = function(selector, callback) {
      if (jQuery(selector).length) {
        callback();
      } else {
        setTimeout(function() {
          waitForEl(selector, callback);
        }, 100);
      }
    }; 
    /*$(document).ready(function () {
        loadingStart(); // start loading
        loadSelectOptionClassOf_ASC('#filterCurriculum','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');
        loadSelectOptionStatusStudent('#filterStatus','');
        var bool = 0;
        var urlInarray = [base_url_js+'api/__getKurikulumSelectOptionASC'];

        $( document ).ajaxSuccess(function( event, xhr, settings ) {
           if (jQuery.inArray( settings.url, urlInarray )) {
               bool++;
               if (bool == 1) {
                   setTimeout(function(){
                        // select filterCurriculum by TaSegment
                            if (TaSegment != '' && TaSegment != null) {
                                var S_filterCurriculum = $('#filterCurriculum option');
                                var rs = '';
                                S_filterCurriculum.each(function(){
                                    var v = $(this).val();
                                    var c = $(this).val();
                                    // console.log(c);
                                    v = v.split('.');
                                    if (v[1] == TaSegment) {
                                        rs = c;
                                    }
                                })

                                //console.log(rs);
                                $('#filterCurriculum').find('option[value="'+rs+'"]').prop('selected',true);

                            }
                        loadStudent(); 
                        loadingEnd(500); // end loading
                    }, 500);
                  
               }
           }
        });
    });*/

    $(document).on('change','#filterBaseProdi',function () {
        var filterBaseProdi = $('#filterBaseProdi').val();
        $('#filterGroupProdi').empty();
        $('#filterGroupProdi').append('<option value="">-- All Group Student --</option>' +
            '                        <option disabled>------------------------</option>');
        load_SO_ProdiGroup(filterBaseProdi.split('.')[0],'#filterGroupProdi','');
    });

    $('.filter-db-std').change(function () {
        loadStudent();
    });

    // ===== Download PDF =====
    /*$('#btnIPSIPKDownloadtoExcel').click(function () {
        var filterCurriculum = $('#filterCurriculum').val();
        var filterBaseProdi = $('#filterBaseProdi').val();
        var filterStatus = $('#filterStatus').val();

        if(filterCurriculum!='' && filterCurriculum!=null){

            var ProdiID = (filterBaseProdi!='' && filterBaseProdi!= null) ? filterBaseProdi.split('.')[0] : '';

            var data = {
                Year : filterCurriculum.split('.')[1],
                ProdiID : ProdiID,
                StatusStudentID : filterStatus
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'save2excel/cumulative-recap';

            FormSubmitAuto(url, 'POST', [{ name: 'token', value: token },]);

        } else {

            $('#filterCurriculum').animateCss('shake').css('border','1px solid red');

            toastr.warning('Select curriculum','Warning');

            setTimeout(function (args) {
                $('#filterCurriculum').css('border','1px solid #ccc');
            },5000);
        }

    });*/

    /*$('#btnStdDownloadtoExcel').click(function () {
        var filterCurriculum = $('#filterCurriculum').val();
        var filterBaseProdi = $('#filterBaseProdi').val();
        var filterStatus = $('#filterStatus').val();

        if(filterCurriculum!='' && filterCurriculum!=null){

            var ProdiID = (filterBaseProdi!='' && filterBaseProdi!= null) ? filterBaseProdi.split('.')[0] : '';

            var data = {
                Year : filterCurriculum.split('.')[1],
                ProdiID : ProdiID,
                StatusStudentID : filterStatus
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'save2excel/student-recap';

            FormSubmitAuto(url, 'POST', [{ name: 'token', value: token },]);

        } else {

            $('#filterCurriculum').animateCss('shake').css('border','1px solid red');

            toastr.warning('Select curriculum','Warning');

            setTimeout(function (args) {
                $('#filterCurriculum').css('border','1px solid #ccc');
            },5000);
        }
    });*/

    // === Show Details
    $(document).on('click','.btnDetailStudent',function () {
        var ta = $(this).attr('data-ta');
        var NPM = $(this).attr('data-npm');

        // var url = base_url_js+'api/__crudeStudent';
        var url = base_url_js+'database/showStudent';
        var data = {
            action : 'read',
            formData : {
                ta : ta,
                NPM : NPM
            }
        };

        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (html) {
            // console.log(jsonResult);
            //
            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Detail Mahasiswa</h4>');
            $('#GlobalModal .modal-body').html(html);
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        });


    });

    // Change Status
    $(document).on('click','.btn-change-status',function () {
        var Name = $(this).attr('data-name');
        var NPM = $(this).attr('data-npm');
        var StatusID = $(this).attr('data-statusid');
        var dataYear = $(this).attr('data-year');
        var EmailPU = $(this).attr('data-emailpu');

        var usermail = (EmailPU!='' && EmailPU!=null) ? EmailPU.split('@')[0] : '';

        $('#NotificationModal .modal-body').html('<div style="text-align: center;">Change Status - <b>'+Name+'</b><hr/> ' +
            '<div class="form-group" style="text-align: left;">' +
            '<label>Status</label>' +
            '<select class="form-control" id="formChangeStatus"></select>' +
            '</div>' +
            '<div class="form-group" style="text-align: left;">' +
            '<label>Email PU</label>' +
            // '<input class="form-control" id="formEmailPU" value="'+EmailPU+'" />' +
            '<div class="input-group">' +
            '  <input type="text" class="form-control" placeholder="Username" id="formEmailPU" value="'+usermail+'">' +
            '  <span class="input-group-addon" id="basic-addon2">@podomorouniversity.ac.id</span>' +
            '</div>' +
            '</div>' +
            '<div style="text-align: right;margin-top: 15px;">' +
            '<button type="button" class="btn btn-default" id="btnCloseChangeStatus" data-dismiss="modal">Close</button> ' +
            '<button type="button" class="btn btn-success" data-npm="'+NPM+'" data-year="'+dataYear+'"  id="btnSaveChangeStatus">Save</button>' +
            '</div></div>');

        loadSelectOptionStatusStudent('#formChangeStatus',StatusID);


        $('#NotificationModal').on('shown.bs.modal', function () {
            $('#formNewPassword').focus();
        })

        $('#NotificationModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });
    $(document).on('click','#btnSaveChangeStatus',function () {

        var formChangeStatus = $('#formChangeStatus').val();
        var formEmailPU = $('#formEmailPU').val();

        if(formEmailPU!='' && formEmailPU!=null){

            loading_buttonSm('#btnSaveChangeStatus');
            $('#btnCloseChangeStatus').prop('disabled',true);

            var data = {
                action : 'changeStatus',
                StatusID : formChangeStatus,
                NPM : $(this).attr('data-npm'),
                EmailPU : formEmailPU+'@podomorouniversity.ac.id',
                dataYear : $(this).attr('data-year')
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudStatusStudents';
            $.post(url,{token:token},function (result) {
                //loadStudent();
                $('#fetch-data-tables #table-list-data').DataTable().destroy();
                fetchingData();
                toastr.success('Status Changed','Success');
                setTimeout(function () {
                    $('#NotificationModal').modal('hide');
                },500);
            });
        } else {
            toastr.warning('Email PU','is Required');
            $('#formEmailPU').css('border','1px solid red');
            setTimeout(function () {
                $('#formEmailPU').css('border','1px solid #ccc');
            },2000);

        }


    });
    

    // ==== Upload Foto =========
    $(document).on('change','.uploadPhotoEmp',function () {
        // uploadPhoto();
        var NPM = $(this).attr('data-npm');
        viewImageBeforeUpload(this,'#imgThum'+NPM,'','','','#formTypeImage'+NPM);
        var Type = $('#formTypeImage'+NPM).val();

        var FileName = NPM+'.'+Type;
        var db = $(this).attr('data-db');
        // console.log(db)
        // console.log(NPM)
        // console.log(FileName)
        var selector_this = $(this);
        uploadPhoto(db,NPM,FileName,selector_this);

    });
    function uploadPhoto(db,NPM,fileName,selector_this) {

        if(fileName!='' && fileName!=null){

            var formData = new FormData( $("#fmPhoto"+NPM)[0]);
            var url = base_url_js+'api/database/upload_photo_student?f='+db+'&&fileName='+fileName;

            $.ajax({
                url : url,  // Controller URL
                type : 'POST',
                data : formData,
                async : false,
                cache : false,
                contentType : false,
                processData : false,
                success : function(data) {

                    var jsonData = JSON.parse(data);
                    var urlPath = base_url_js+'uploads/students/'+db+'/'+fileName;
                    var ev = selector_this.closest('tr');
                    var s_pr = ev.find('.PrintIDCard').attr('path',urlPath);

                }
            });

        } else {
            toastr.error('NIK / NIK is empty','Error');
        }

    }
    // ============================

    // Reset Password
    $(document).on('click','.btn-reset-password',function () {

        if(confirm('Reset password ?')){
            var token = $(this).attr('data-token');
            var DataToken = jwt_decode(token,'UAP)(*');
            if(DataToken.Email!='' && DataToken.Email!=null){

                $('#NotificationModal .modal-body').html('<div style="text-align: center;">Reset Password has been send to : <b style="color: blue;">'+DataToken.Email+'</b><hr/><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>');
                $('#NotificationModal').modal('show');

                DataToken.DueDate = dateTimeNow();
                var newToken = jwt_encode(DataToken,'UAP)(*');

                var url = base_url_js+'database/sendMailResetPassword';
                $.post(url,{token:newToken},function (result) {

                });
            } else {
                toastr.error('Email Empty','Error');
            }
        }

    });

    $(document).on('click','.PrintIDCard',function () {
        var type = $(this).attr('type');
        var NPM = $(this).attr('data-npm');
        var Name = $(this).attr('data-name');
        var r = Name.split(" ");
        var c = '';
        for (var i = 0; i < r.length; i++) {
            if (i <= 1) {
              c+= r[i]+" ";
            }
            else
            {
              c+= r[i].substr(0,1)+" ";
            }
        }
        Name = c;
        var PathFoto = $(this).attr('path');
        var email = $(this).attr('email');
        var url = base_url_js+'save2pdf/PrintIDCard';
        var data = [];
        temp = {
          type : type,
          NPM : NPM,
          Name : Name,
          PathFoto : PathFoto,
          email : email,
        }
        data.push(temp);
        var token = jwt_encode(data,"UAP)(*");
        FormSubmitAuto(url, 'POST', [
            { name: 'token', value: token },
        ]);   
    });
    
    /*$(document).on('click','#btnSelect',function () {
        if (!$('.uniform').length) {
            var get_th = $("#tableStudent thead").find('tr').find('th:eq(0)').text();
            var checkbox = '<input type="checkbox" name="select_all" value="1" id="example-select-all">';
            $("#tableStudent thead").find('tr').find('th:eq(0)').html(get_th+'&nbsp'+checkbox);
            $("#tableStudent tr").each(function(){
                var a = $(this);
                var No = a.find('td:eq(0)').text();
                var G_attr = a.find('td:eq(7)').find('.PrintIDCard');
                var type = G_attr.attr('type');
                var NPM = G_attr.attr('data-npm');
                var Name = G_attr.attr('data-name');
                var PathFoto = G_attr.attr('path');
                var email = G_attr.attr('email');
                var checkbox = '<input type="checkbox" class="uniform" type2 = "student" data-npm="'+NPM+'" data-name="'+Name+'" path = "'+PathFoto+'" email = "'+email+'">';
                a.find('td:eq(0)').html(No+'&nbsp'+checkbox);

            })
        }
    }); */
        
    $(document).on('click','#btnPrintIDCard',function () {
        var html = '';
            html += '<div class = "row">'+
                        '<div class = "col-md-12">'+
                            '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
                                  '<thead>'+
                                      '<tr>'+
                                          '<th style="width: 5px;">No &nbsp <input type="checkbox" name="select_all" value="1" id="example-select-all2"></th>'+
                                          '<th style="width: 55px;">NIM</th>'+
                                          '<th style="width: 55px;">Photo</th>'+
                                          '<th style="width: 55px;">Nama</th>'+
                                       '</tr>'+
                                    '</thead>';

            html += '<tbody>';
            for (var i = 0; i < TempCheckBoxIDCard.length; i++) {
                var checkbox = '<input type="checkbox" class="uniform2" type2 = "student" data-npm="'+TempCheckBoxIDCard[i]['NPM']+'" data-name="'+TempCheckBoxIDCard[i]['Name']+'" path = "'+TempCheckBoxIDCard[i]['PathFoto']+'" email = "'+TempCheckBoxIDCard[i]['email']+'" checked>';
                html += '<tr>'+
                            '<td>'+(parseInt(i)+1)+'&nbsp'+checkbox+'</td>'+
                            '<td>'+TempCheckBoxIDCard[i]['NPM']+'</td>'+
                            '<td>'+'<img id="imgThum'+TempCheckBoxIDCard[i]['NPM']+'" src="'+TempCheckBoxIDCard[i]['PathFoto']+'" style="max-width: 35px;" class="img-rounded">'+'</td>'+
                            '<td>'+TempCheckBoxIDCard[i]['Name']+'</td>'+
                        '</tr>';    
            }

            html += '</tbody></table>';                        
            html += '</div></div>';                        

         var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
             '<button type="button" id="ModalbtnSaveForm" class="btn btn-success">Print</button>';

        $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'List Checklist Data'+'</h4>');
        $('#GlobalModalLarge .modal-body').html(html);
        $('#GlobalModalLarge .modal-footer').html(footer);
        $('#GlobalModalLarge').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    $(document).on('click','input[type="checkbox"][class="uniform"]',function () {
        var type = $(this).attr('type2');
        var NPM = $(this).attr('data-npm');
        var Name = $(this).attr('data-name');
        var PathFoto = $(this).attr('path');
        var email = $(this).attr('email');

        temp = {
          type : type,
          NPM : NPM,
          Name : Name,
          PathFoto : PathFoto,
          email : email,
        }
        if(this.checked){
           // Search data
           if (TempCheckBoxIDCard.length > 0) {
               var bool = true;
               for (var i = 0; i < TempCheckBoxIDCard.length; i++) {
                   var NPM2 = TempCheckBoxIDCard[i]['NPM'];
                   if (NPM == NPM2) {
                       bool = false;
                       break;
                   }
               }

               if (bool) { // insert data
                TempCheckBoxIDCard.push(temp);
               }
           }
           else
           {
            TempCheckBoxIDCard.push(temp);
           }
        }
        else
        {
            var bool = true;
            for (var i = 0; i < TempCheckBoxIDCard.length; i++) {
                var NPM2 = TempCheckBoxIDCard[i]['NPM'];
                if (NPM == NPM2) {
                    bool = false;
                    break;
                }
            }

            if (!bool) { // find data
             var arr = [];
             for (var i = 0; i < TempCheckBoxIDCard.length; i++) {
                 var NPM2 = TempCheckBoxIDCard[i]['NPM'];
                 if (NPM != NPM2) {
                   arr.push(TempCheckBoxIDCard[i]) ; 
                 }
             }
             TempCheckBoxIDCard = [];
             TempCheckBoxIDCard = arr;
            }
        }
  
    });

    // Handle click on "Select all" control
    /*$(document).on('click','#example-select-all',function () {    
       // Get all rows with search applied
       var rows = TableSess.rows({ 'search': 'applied' }).nodes();
       // Check/uncheck checkboxes for all rows in the table
       $('input[type="checkbox"]', rows).prop('checked', this.checked);
       $('input[type="checkbox"][class="uniform"]').each(function(){
            var type = $(this).attr('type');
            var NPM = $(this).attr('data-npm');
            var Name = $(this).attr('data-name');
            var PathFoto = $(this).attr('path');
            var email = $(this).attr('email');

            temp = {
              type : type,
              NPM : NPM,
              Name : Name,
              PathFoto : PathFoto,
              email : email,
            }
            if(this.checked){
               // Search data
               if (TempCheckBoxIDCard.length > 0) {
                   var bool = true;
                   for (var i = 0; i < TempCheckBoxIDCard.length; i++) {
                       var NPM2 = TempCheckBoxIDCard[i]['NPM'];
                       if (NPM == NPM2) {
                           bool = false;
                           break;
                       }
                   }

                   if (bool) { // insert data
                    TempCheckBoxIDCard.push(temp);
                   }
               }
               else
               {
                TempCheckBoxIDCard.push(temp);
               }
            }
            else
            {
                var bool = true;
                for (var i = 0; i < TempCheckBoxIDCard.length; i++) {
                    var NPM2 = TempCheckBoxIDCard[i]['NPM'];
                    if (NPM == NPM2) {
                        bool = false;
                        break;
                    }
                }

                if (!bool) { // find data
                 var arr = [];
                 for (var i = 0; i < TempCheckBoxIDCard.length; i++) {
                     var NPM2 = TempCheckBoxIDCard[i]['NPM'];
                     if (NPM != NPM2) {
                       arr.push(TempCheckBoxIDCard[i]) ; 
                     }
                 }
                 TempCheckBoxIDCard = [];
                 TempCheckBoxIDCard = arr;
                }
            }
       })

    });*/

    // Handle click on "Select all" control
    /*$(document).on('click','#example-select-all2',function () {    
      $('input.uniform2').not(this).prop('checked', this.checked);
    });

    */

    $(document).on('click','#ModalbtnSaveForm',function () {
        var data = [];
        $('input[type="checkbox"][class="uniform2"]:checked:not(#example-select-all2):not(#example-select-all)').each(function(){
              var type = $(this).attr('type2');
              var NPM = $(this).attr('data-npm');
              var Name = $(this).attr('data-name');
              var r = Name.split(" ");
              var c = '';
              for (var i = 0; i < r.length; i++) {
                  if (i <= 1) {
                    c+= r[i]+" ";
                  }
                  else
                  {
                    c+= r[i].substr(0,1)+" ";
                  }
              }
              Name = c;
              var PathFoto = $(this).attr('path');
              var email = $(this).attr('email');
              temp = {
                type : type,
                NPM : NPM,
                Name : Name,
                PathFoto : PathFoto,
                email : email,
              }
              data.push(temp);  
        });
        if (data.length > 0) {
            var url = base_url_js+'save2pdf/PrintIDCard';
            var token = jwt_encode(data,"UAP)(*");
            FormSubmitAuto(url, 'POST', [
                { name: 'token', value: token },
            ]); 
        }
        else
        {
            toastr.error('Please checklist the data','!!!Failed');
        }

    });
    

    $(document).on('click','.pagination',function () {
        if ($('#example-select-all').length) {
            $('#example-select-all').remove();
        }   
    });

    $(document).on('keyup','input[type="search"]',function () {
        if ($('#example-select-all').length) {
            $('#example-select-all').remove();
        }   
    });


    /*ADDED BY FEBRI @ NOV 2019*/
    $(document).on('click','.show-request',function(){
        if( !$(this).parent().hasClass("disabled") ){
            var NPM = $(this).data("npm");
            var TA = $(this).data("ta");
            var data = {
                NPM : NPM,
                TA : TA,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.ajax({
                type : 'POST',
                url : base_url_js+"database/student/req-merge",
                data: {token:token},
                dataType : 'html',
                beforeSend : function(){
                    loading_modal_show();
                },error : function(jqXHR){
                    console.log("Error info:\n"+jqXHR.responseText);
                    $("body #GlobalModal .modal-header").html("<h1>Error notification</h1>");
                    $("body #GlobalModal .modal-body").html(jqXHR.responseText);
                    $("body #GlobalModal").modal("show");
                },success : function(response){
                    loading_modal_hide();
                    $(".fetchDataRequest").html(response);
                }
            });
        }
    });
    
    $(document).on('click','.btn-approve.unselect',function(){
        $('#fetch-data-tables #table-list-data').DataTable().destroy();
        fetchingData(true);
        $(this).toggleClass("btn-default btn-primary");
        $(this).toggleClass("unselect selected");
    });
    $(document).on('click','.btn-approve.selected',function(){
        $('#fetch-data-tables #table-list-data').DataTable().destroy();
        fetchingData();
        $(this).toggleClass("btn-primary btn-default");
        $(this).toggleClass("selected unselect");
    });

    /*END ADDED BY FEBRI @ NOV 2019*/

</script> 