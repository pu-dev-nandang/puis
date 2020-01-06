<style>
    #tableEmployees thead tr th {background: #20525a;color: #ffffff;text-align: center;}
    .bg-primary {color: #fff;background-color: #337ab7;}
    .bg-success {background-color: #dff0d8;}
    .bg-info {background-color: #d9edf7;}
    .bg-warning {background-color: #fcf8e3 !important;}
    #divDataEmployees #tableEmployees tbody td > a.card-link{text-decoration: none !important}
    #divDataEmployees #tableEmployees tbody td > a.card-link .regular{color: #555}
    #divDataEmployees #tableEmployees tbody td > a.card-link .name{font-weight: bold}
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
          </div>
          <div class="row">
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
    $(".btn-filter").click(function(){
        loadDataEmployees();
      });
    
  });
</script>
<!-- END ADDED BY FEBRI @ DEC 2019 -->

<!-- UPDATED BY FEBRI @ JAN 2019 -->
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-default" type="button" id="btn-need-appv" data-status="close"><i class="fa fa-warning"></i> Need approval for request biodata</button>
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
<div id="fetchRequestDataEmp"></div>

<script type="text/javascript">
    function loadDataEmployees() {
        loading_page('#divDataEmployees');

        setTimeout(function () {
            $('#divDataEmployees').html('<table class="table table-bordered table-striped" id="tableEmployees">' +
                '            <thead>' +
                '            <tr>' +
                '                <th style="width: 1%;">No</th>' +
                '                <th style="width: 3%;">NIP</th>' +
                '                <th style="width: 10%;">Employee</th>' +
                '                <th style="width: 10%;">Birthdate</th>' +
                '                <th style="width: 5%;">Position</th>' +
                '                <th style="width: 15%;">Address</th>' +
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
                    url : base_url_js+'api/__getEmployeesHR', // json datasource
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
    $(document).ready(function () {
        loadDataEmployees();

        //UPDATED BY FEBRI @ NOV 2019
        $("#btn-need-appv").click(function(){
            var status = $(this).data("status");
            var filterStatus = $('#filterStatusEmployees').val();
            if(status == "close"){
                $(this).toggleClass("btn-default btn-info");
                $(this).data("status","open");
                loadDataEmployees(filterStatus,true);
            }else{
                $(this).toggleClass("btn-info btn-default");
                $(this).data("status","close");
                loadDataEmployees();            
            }
        });


        $("body #divDataEmployees").on("click",".table tbody td .btn-appv",function(){
            var itsme = $(this);
            var NIP = itsme.data("nip");
            var data = {
                NIP : NIP
            };
            var token = jwt_encode(data,'UAP)(*');
            
            $.ajax({
                type : 'POST',
                url : base_url_js+"human-resources/employee-request",
                data: {token:token},
                dataType : 'html',
                beforeSend :function(){
                    $('#globalModal .modal-body').html('<i class="fa fa-spinner fa-pulse fa-fw" style="margin-right: 5px;"></i> Loading...');
                    itsme.prop("disabled",true);
                },error : function(jqXHR){
                    $("body #GlobalModal .modal-header").html("<h1>Error notification</h1>");
                    $("body #GlobalModal .modal-body").html(jqXHR.responseText);
                    $("body #GlobalModal").modal("show");
                },success : function(response){
                    itsme.prop("disabled",false);
                    $("#fetchRequestDataEmp").html(response);
                }
            });
        });        
        //END UPDATED BY FEBRI @ NOV 2019

    });
</script>
<!-- END UPDATED BY FEBRI @ JAN 2019 -->
