
<style>
    #tableStudent thead tr th {
        background: #20525a;
        color: #ffffff;
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="well">
            <div class="row">
                <div class="col-xs-2">
                    <select class="form-control filter-db-std" id="filterCurriculum">
                        <option value="">-- All Class Of --</option>
                        <option disabled>------------------------</option>
                    </select>
                </div>
                <div class="col-xs-3">
                    <select class="form-control filter-db-std" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                        <option disabled>------------------------</option>
                    </select>
                </div>
                <div class="col-xs-2">
                    <select class="form-control filter-db-std" id="filterGroupProdi">
                        <option value="">-- All Group Student --</option>
                        <option disabled>------------------------</option>
                    </select>
                </div>
                <div class="col-xs-2">
                    <select class="form-control filter-db-std" id="filterStatus">
                        <option value="">-- All Status --</option>
                        <option disabled>------------------------</option>
                    </select>
                </div>
                <!-- ADDED BY FEBRI @ NOV 2019 -->
                <?php $Dept = $this->session->userdata('IDdepartementNavigation'); if($Dept=='6') { ?>
                <div class="col-xs-3">
                    <button class="btn btn-block btn-default btn-approve unselect" type="button"><i class="fa fa-warning"></i> Need Approval for Request Updating Biodata</button>
                </div>
                <?php } ?>
                <!-- END ADDED BY FEBRI @ NOV 2019 -->
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php $Dept = $this->session->userdata('IDdepartementNavigation'); if($Dept=='6') { ?>
        <div class="well">
            <div class="row">
                <div class="col-xs-6">
                    <button class="btn btn-block btn-default" id="btnStdDownloadtoExcel"><i class="fa fa-download margin-right"></i> Student to Excel</button>
                </div>
                <div class="col-xs-6">
                    <button class="btn btn-block btn-default" id="btnIPSIPKDownloadtoExcel"><i class="fa fa-download margin-right"></i> IPS/IPK to Excel</button>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php $Dept = $this->session->userdata('IDdepartementNavigation'); if($Dept=='6') { ?>

<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-4">
                <button class="btn btn-block btn-default" id="btnSelect"><i class="fa fa-id-card margin-right"></i> Select Student</button>
            </div>
            <div class="col-md-4">
                <button class="btn btn-block btn-default" id="btnPrintIDCard"><i class="fa fa-id-card margin-right"></i> Print ID Card</button>
            </div>
            <div class="col-md-4" style="border-left: 1px solid #ccc;">
                <a href="<?= base_url('database/students-group'); ?>" class="btn btn-block btn-default"><i class="fa fa-users margin-right"></i> Student Group</a>
            </div>           

        </div>
    </div>
</div>

<?php } ?>

<div class="row" style="margin-top: 10px">
    <div class="col-md-12">

        <hr/>
        <div id="divDataStudent"></div>
    </div>
</div>


<div class="fetchDataRequest"></div>

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
    $(document).ready(function () {
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

                                console.log(rs);
                                $('#filterCurriculum').find('option[value="'+rs+'"]').prop('selected',true);

                            }
                        loadStudent(); 
                        loadingEnd(500); // end loading
                    }, 500);
                  
               }
           }
        });
    });

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
    $('#btnIPSIPKDownloadtoExcel').click(function () {
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

    });

    $('#btnStdDownloadtoExcel').click(function () {
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
    });

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
                loadStudent();
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
    
    function loadStudent(approval=null) { //Updated by Febri @ Nov 2019, add param
        loading_page('#divDataStudent');
        var filterCurriculum = $('#filterCurriculum').val();
        var filterBaseProdi = $('#filterBaseProdi').val();
        var filterGroupProdi = $('#filterGroupProdi').val();
        var filterStatus = $('#filterStatus').val();

        var Year = (filterCurriculum!='' && filterCurriculum!=null)
            ? filterCurriculum.split('.')[1] : '';
        var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null)
            ? filterBaseProdi.split('.')[0] : '';
        var StatusStudents = (filterStatus!='' && filterStatus!=null)
            ? filterStatus : '';

        setTimeout(function () {
            $('#divDataStudent').html('<table class="table table-bordered" id="tableStudent">' +
                '                <thead>' +
                '                <tr>' +
                '                    <th style="width: 5%;">No</th>' +
                '                    <th style="width: 7%;">NIM</th>' +
                '                    <th style="width: 5%;">Photo</th>' +
                '                    <th style="">Name,Email & Formulir Number</th>' +
                '                    <th style="width: 7%;">Class Of</th>' +
                '                    <th style="width: 15%;">Progamme Study</th>' +
                '                    <th style="width: 5%;">Upload Photo</th>' +
                '                    <th style="width: 5%;">Action</th>' +
                '                    <th style="width: 7%;">Login Portal</th>' +
                '                    <th style="width: 5%;">Status</th>' +
                '                </tr>' +
                '                </thead>' +
                '            </table>');

            var data = {
                Year : Year,
                ProdiID : ProdiID,
                GroupProdiID : filterGroupProdi,
                StatusStudents : StatusStudents,
                approvalStudentReq : approval //Updated by Febri @ Nov 2019
            };
            var token = jwt_encode(data,'UAP)(*');

            var dataTable = $('#tableStudent').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "NIM, Name, Programme Study"
                },
                "ajax":{
                    url : base_url_js+'api/database/__getListStudent', // json datasource
                    ordering : false,
                    data : {token:token},
                    type: "post",  // method  , by default get
                    error: function(){  // error handling
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
                    }
                },
                "drawCallback": function( settings ) {
                    // trigger remove button
                    if (DivSessionID != 12 && DivSessionID != 6) { // selain IT dan Academic
                        waitForEl(".btnLoginPortalStudents", function() { 
                          $("#btnSelect,#btnPrintIDCard,.btn-upload,.btn-group").remove();
                        });
                    }
                }
            } );
            TableSess = dataTable;
        },500);

    }

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
    
    $(document).on('click','#btnSelect',function () {
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
    });
        
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
    $(document).on('click','#example-select-all',function () {    
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

    });

    // Handle click on "Select all" control
    $(document).on('click','#example-select-all2',function () {    
      $('input.uniform2').not(this).prop('checked', this.checked);
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
        loadStudent("Yes");
        $(this).toggleClass("btn-block btn-primary");
        $(this).toggleClass("unselect selected");
    });
    $(document).on('click','.btn-approve.selected',function(){
        loadStudent();
        $(this).toggleClass("btn-primary btn-block");
        $(this).toggleClass("selected unselect");
    });

    /*END ADDED BY FEBRI @ NOV 2019*/

</script>