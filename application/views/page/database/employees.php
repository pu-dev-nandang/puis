
<style>
    #tableEmployees thead tr th {background: #20525a;color: #ffffff;text-align: center;}
    .bg-primary {color: #fff;background-color: #337ab7;}
    .bg-success {background-color: #dff0d8;}
    .bg-info {background-color: #d9edf7;}
    .bg-warning {background-color: #fcf8e3 !important;}
</style>

<!-- ADDED BY FEBRI @ DEC 2019 -->
<div class="row">
  <div class="col-sm-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-filter"></i> Form Filter</h4>
      </div>
      <div class="panel-body">
        <form id="form-filter" action="" method="post" autocomplete="off">
          <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                <label>Employee</label>               
                <input type="text" class="form-control" name="staff" placeholder="NIP or Name">               
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Division</label>               
                <select class="form-control" name="division">
                  <option value="">-Choose one-</option>
                  <?php foreach ($division as $d) {
                  echo '<option value="'.$d->ID.'">'.$d->Division.'</option>';
                  } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Position</label>               
                <select class="form-control" name="position" disabled>
                  <option value="">-Choose one-</option>
                  <?php foreach ($position as $p) {
                  echo '<option value="'.$p->ID.'">'.$p->Description.'</option>';
                  } ?>
                </select>               
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
            
            <div class="col-sm-2">
              <div class="form-group">
                <label class="show-more-filter text-success" data-toggle="collapse" data-target="#advance-filter" aria-expanded="false" aria-controls="advance-filter">
                  <span>Advance filter</span> 
                  <i class="fa fa-angle-double-down"></i>
                </label>
              </div>
            </div>
          </div>

          <div id="advance-filter" class="collapse">
            <div class="row">
              <div class="col-sm-2">
                <div class="form-groups">
                  <label>Status employee</label>
                </div>
                <?php if(!empty($statusstd)) {
                foreach ($statusstd as $t) { ?>
                <div class="form-group">
                  <div class="col-sm-10">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" value="<?=$t->IDStatus?>" name="statusstd[]" > <?=$t->Description?>
                      </label>
                    </div>
                  </div>
                </div>
                <?php } } ?>
              </div>
              <div class="col-sm-2">
                <div class="form-groups">
                  <label>Religion</label>
                </div>
                <?php if(!empty($religion)){ 
                foreach ($religion as $rg) { ?>
                <div class="form-group">
                  <div class="col-sm-10">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" value="<?=$rg->IDReligion?>" name="religion[]"> <?=$rg->Religion?>
                      </label>
                    </div>
                  </div>
                </div>
                <?php } } ?>

              </div>
              <div class="col-sm-2">
                <div class="form-group">
                  <label>Gender</label>
                  <div class="form-group">
                    <div class="col-sm-10">
                      <div class="form-checkbox">
                        <label>
                            <input type="checkbox" value="L" name="gender[]"> Male
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-10">
                      <div class="form-checkbox">
                        <label>
                            <input type="checkbox" value="P" name="gender[]" > Female
                        </label>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
              
              
              <div class="col-sm-3">
                <div class="form-group">
                  <label>Last Education</label>
                  <?php if(!empty($level_education)){ 
                  foreach ($level_education as $le) { ?>
                  <div class="form-group">
                    <div class="col-sm-12">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" value="<?=$le->ID?>" name="level_education[]"> <?=$le->Description?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <?php } } ?>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group" style="padding-top:22px">
            <button class="btn btn-primary btn-filter" type="button"><i class="fa fa-search"></i> Search</button>
            <a class="btn btn-default" href="">Clear Filter</a>
          </div>
          
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $("#birthdate_start,#birthdate_end").datepicker({
        dateFormat: 'dd-mm-yy',
        changeYear: true,
        changeMonth: true
    });
    $('#form-filter').on('keyup keypress', function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode === 13) { 
        e.preventDefault();
        return false;
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

    $("#form-filter select[name=division]").change(function(){
      var value = $(this).val();
      if($.trim(value) != ''){
        $("#form-filter select[name=position]").prop("disabled",false);
      }
    });
    $("#form-filter select[name=position]").change(function(){
      var division = $("#form-filter select[name=division]").val();
      if($.trim(division) == ''){
        division.addClass("required");
        alert("Please fill up field Division");
      }
    });
    
  });
</script>
<!-- END ADDED BY FEBRI @ DEC 2019 -->

<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-block btn-default" id="btnSelect"><i class="fa fa-id-card"></i> Select Employees</button>
            </div>
            <div class="col-md-6">
                <button class="btn btn-block btn-default" id="btnPrintIDCard"><i class="fa fa-id-card"></i> Print ID Card</button>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 10px">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title"><i class="fa fa-bars"></i> List of employee</h4>
        </div>
        <div class="panel-body">
          <div id="divDataEmployees"></div>
        </div>
      </div>
    </div>
</div>

<script>
    var TableSess = '';
    var TempCheckBoxIDCard = [];
    $(document).ready(function () {
        loadSelectOptionStatusEmployee('#filterStatusEmployees','');
        loadDataEmployees();
    });

    $('#filterStatusEmployees').change(function () {
        loadDataEmployees();
    });

    function loadDataEmployees() {
        loading_page('#divDataEmployees');

        setTimeout(function () {
            $('#divDataEmployees').html('<table class="table table-bordered table-striped" id="tableEmployees">' +
                '            <thead>' +
                '            <tr>' +
                '                <th style="width: 1%;">No</th>' +
                '                <th style="width: 2%;">NIP</th>' +
                '                <th style="width: 5%;">Employee</th>' +
                '                <th style="width: 10%;">Birthdate</th>' +
                '                <th style="width: 15%;">Position</th>' +
                /*'                <th style="width: 25%;">Address</th>' +*/
                '                <th style="width: 7%;">Action</th>' +
                '                <th style="width: 7%;">Status</th>' +
                '            </tr>' +
                '            </thead>' +
                '        </table>');
            /*UPDATED BY FEBRI @ JAN 2020*/
            /*var filterStatusEmployees = $('#filterStatusEmployees').val();
            var token = jwt_encode({StatusEmployeeID : filterStatusEmployees},'UAP)(*');*/
            var token = jwt_encode({Filter : $("#form-filter").serialize()},'UAP)(*');
            /*END UPDATED BY FEBRI @ JAN 2020*/

            var dataTable = $('#tableEmployees').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "NIP / NIK, Name"
                },
                "ajax":{
                    url : base_url_js+'api/database/__getListEmployees', // json datasource
                    ordering : false,
                    data : {token:token},
                    type: "post",  // method  , by default get
                    error: function(jqXHR){  // error handling
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");

                        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                            '<h4 class="modal-title">Error Fetch Student Data</h4>');
                        $('#GlobalModal .modal-body').html(jqXHR.responseText);
                        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
                        $('#GlobalModal').modal({
                            'show' : true,
                            'backdrop' : 'static'
                        });
                    }
                }
            } );
            TableSess = dataTable;
        },500);

    }

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


    // Update Email
    $(document).on('click','.btn-update-email',function () {

        var Name = $(this).attr('data-name');
        var Email = $(this).attr('data-email');
        var StatusEmployeeID = $(this).attr('data-empid');
        var NIP = $(this).attr('data-nip');

        $('#NotificationModal .modal-body').html('<div style="text-align: center;">Update Email - <b>'+Name+'</b><hr/> ' +
            '<div class="form-group" style="text-align: left;">' +
            '<label>Email</label>' +
            '<input class="hide" value="'+StatusEmployeeID+'" id="formStatusEmployeeID'+NIP+'" /> ' +
            '<input class="form-control" value="'+Email+'" id="formEmail'+NIP+'" /> ' +
            '</div>' +
            '<div style="text-align: right;margin-top: 15px;">' +
            '<button type="button" class="btn btn-default" id="btnCloseUpdateEmail" data-dismiss="modal">Close</button> ' +
            '<button type="button" class="btn btn-success" data-nip="'+NIP+'" data-name="'+Name+'" id="btnSaveChangeEmail">Save</button>' +
            '</div></div>');


        $('#NotificationModal').on('shown.bs.modal', function () {
            $('#formEmail'+NIP).focus();
        })

        $('#NotificationModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    $(document).on('click','#btnSaveChangeEmail',function () {
        var NIP = $(this).attr('data-nip');
        var Name = $(this).attr('data-name');
        var formStatusEmployeeID = $('#formStatusEmployeeID'+NIP).val();
        var formEmail = $('#formEmail'+NIP).val();

        if(formEmail!='' && formEmail!=null && formStatusEmployeeID!='' && formStatusEmployeeID!=null){

            loading_buttonSm('#btnSaveChangeEmail');
            $('#btnCloseUpdateEmail,#formEmail'+NIP).prop('disabled',true);

            var url = base_url_js+'api/__crudEmployees';

            var token = jwt_encode({action:'updateEmailEmployees',NIP : NIP,StatusEmployeeID : formStatusEmployeeID, Email : formEmail},'UAP)(*');
            $.post(url,{token:token},function (result) {


                $('#viewEmail'+NIP).text(formEmail);
                var data = {
                    Type : 'emp',
                    Name : Name,
                    NIP : NIP,
                    Email : formEmail
                };
                var tokenBtn = jwt_encode(data,'UAP)(*');
                $('#btnUpdateEmail'+NIP).attr('data-email',formEmail);
                $('#btnResetPass'+NIP).removeClass('disabled').prop('disabled',false);
                $('#btnResetPass'+NIP).attr('data-token',tokenBtn);
                $('#btnResetPass'+NIP).parent('li').removeClass('disabled');

                setTimeout(function () {
                    $('#NotificationModal').modal('hide');
                },500);

            });


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
            var get_th = $("#tableEmployees thead").find('tr').find('th:eq(0)').text();
            var checkbox = '<input type="checkbox" name="select_all" value="1" id="example-select-all">';
            $("#tableEmployees thead").find('tr').find('th:eq(0)').html(get_th+'&nbsp'+checkbox);
            $("#tableEmployees tr").each(function(){
                var a = $(this);
                var No = a.find('td:eq(0)').text();
                var G_attr = a.find('td:eq(7)').find('.PrintIDCard');
                var type = G_attr.attr('type');
                var NPM = G_attr.attr('data-npm');
                var Name = G_attr.attr('data-name');
                var PathFoto = G_attr.attr('path');
                var email = G_attr.attr('email');
                var checkbox = '<input type="checkbox" class="uniform" type2 = "employees" data-npm="'+NPM+'" data-name="'+Name+'" path = "'+PathFoto+'" email = "'+email+'">';
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
                                          '<th style="width: 55px;">NIP</th>'+
                                          '<th style="width: 55px;">Photo</th>'+
                                          '<th style="width: 55px;">Nama</th>'+
                                       '</tr>'+
                                    '</thead>';

            html += '<tbody>';
            for (var i = 0; i < TempCheckBoxIDCard.length; i++) {
                var checkbox = '<input type="checkbox" class="uniform2" type2 = "employees" data-npm="'+TempCheckBoxIDCard[i]['NPM']+'" data-name="'+TempCheckBoxIDCard[i]['Name']+'" path = "'+TempCheckBoxIDCard[i]['PathFoto']+'" email = "'+TempCheckBoxIDCard[i]['email']+'" checked>';
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


    $(document).on('click','.resetpassBirthDay',function () {

        if(confirm('You are sure to reset your password ?')){
            var date = $(this).attr('data-day');
            var NIP = $(this).attr('data-nip');
            if(date!=''){

                var dataExp = date.split('-');
                var DD = dataExp[2];
                var MM = dataExp[1];
                var YY = dataExp[0].substr(2,2);

                var data = {
                    action : 'resetPassword2BirthDay',
                    NIP :  NIP,
                    PasswordOld : DD+''+MM+''+YY
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api/__crudEmployees';

                $.post(url,{token:token},function (result) {
                    alert('Birthday : '+date+' | Password : '+DD+''+MM+''+YY);
                    toastr.success('Data updated','Success');
                });


            } else {
                toastr.warning('Date of birth not yet setting','Warning');
            }
        }

    });


    /*ADDED BY FEBRI @ JAN 2020*/
    $(document).ready(function(){
      $(".btn-filter").click(function(){
        loadDataEmployees();
      });
    });
    /*END ADDED BY FEBRI @ JAN 2020*/

</script>
