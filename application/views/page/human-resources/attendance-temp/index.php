<style>
    #tableEmployees thead tr th {background: #20525a;color: #ffffff;text-align: center;}
    .bg-primary {color: #fff  !important;background-color: #337ab7;}
    .bg-success {background-color: #dff0d8;}
    .bg-info {background-color: #d9edf7;}
    .bg-warning {background-color: #fcf8e3 !important;}
    #divDataEmployees #tableEmployees tbody td > a.card-link{text-decoration: none !important}
    #divDataEmployees #tableEmployees tbody td > a.card-link .regular{color: #555}
    #divDataEmployees #tableEmployees tbody td > a.card-link .name{font-weight: bold}
    
</style>
<div id="attendance-temporary">
	<div class="filtering">
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
		            <div class="col-sm-2">
		              <div class="form-group">
		                <label>Attendance Start From</label>
		                <input type="text" name="attendance_start" id="attendance_start" class="form-control" placeholder="dd-mm-yyy"> 		                  
		              </div>
		            </div>
		            <div class="col-sm-2">
		              <div class="form-group">
		                <label>Until</label>
		                <input type="text" name="attendance_end" id="attendance_end" class="form-control" placeholder="dd-mm-yyy"> 		                  
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
		
	</div>

	<div class="result">
		<div class="row" style="margin-top: 10px">
		    <div class="col-md-12">
		      <div class="panel panel-default">
		        <div class="panel-heading">            
		          <button class="btn btn-xs btn-primary btn-download pull-right" type="button"><i class="fa fa-download"></i> Export to excel</button>
		          <h4 class="panel-title"><i class="fa fa-bars"></i> List of record home attendances <span>Today (<?= date('d F Y') ?>)</span></h4>
		        </div>
		        <div class="panel-body">
		        	<div id="fetch-data-tables">
		        		<table class="table table-bordered table-striped" id="table-list-data">
                            <thead>
	                            <tr>
	                                <th width="5%">No</th>
	                                <th>NIP</th>
	                                <th>Employee</th>
	                                <th>Position</th>
	                                <th>Total Activity</th>
	                                <th>First Login</th>
	                                <th>Last Login</th>
	                                <th width="5%">Detail</th>
	                            </tr>
                            </thead>
                            <tbody>
                            	<tr><td colspan="8">Empty result</td></tr>
                            </tbody>
                        </table>
		        	</div>
		        </div>
	          </div>
      		</div>
  		</div>	
	</div>
</div>

<script type="text/javascript">
	function fetchAttendance() {
		var filtering = $("#form-filter").serialize();
		
        var token = jwt_encode({Filter : filtering},'UAP)(*');

        var dataTable = $('#fetch-data-tables #table-list-data').DataTable( {
            
            "ajax":{
                url : base_url_js+'human-resources/fetch-attendance-temp', // json datasource
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
                //loading_modal_hide();
            },
            "columns": [
            	{
            		"data":"ID",
            		render: function (data, type, row, meta) {
				        return meta.row + meta.settings._iDisplayStart + 1;
				    }
            	},
            	{
            		"data":"NIP",
            		"render": function (data, type, row, meta) {
            			var label = "";
            			return data;
            		}
            	},
            	{
            		"data":"Name"            		
            	},
            	{
            		"data":"DivisionMain_",
            		"render": function (data, type, row, meta) {
            			var label = data+"-"+row.PositionMain_;
            			return label;
            		}            		
            	},
            	{
            		"data":"TotalActivity"            		
            	},
            	{
            		"data":"FirstLoginPortal"            		
            	},
            	{
            		"data":"LastLoginPortal"            		
            	},
            	{
            		"data":"NIP",
            		"render": function (data, type, row, meta) {
            			var label = '<button class="btn btn-info btn-detail" data-date="'+row.FirstLoginPortal+'" data-dateend="'+row.LastLoginPortal+'" data-id="'+data+'"><i class="fa fa-folder-open"></i></button>';
            			return label;
            		}
            	},
        	]
        });
	}

  $(document).ready(function(){
    $("#attendance_start,#attendance_end").datepicker({
        dateFormat: 'dd-mm-yy',
        changeYear: true,
        changeMonth: true
    });
    $(".btn-download").click(function(){
    	var itsme = $(this);
    	var filtering = $("#form-filter").serialize();
		
        var token = jwt_encode({Filter : filtering},'UAP)(*');
        var urld = base_url_js+"human-resources/download-attendance-temp";
        $("#form-filter").attr("action",urld);
        $("#form-filter")[0].submit();
        /*$.ajax({
            type : 'POST',
            url : urld,
            data: {token:token},
            //dataType : 'json',
            beforeSend :function(){
                itsme.html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
            },error : function(jqXHR){
            	itsme.html('<i class="fa fa-download"></i> Export to excel');
                $("body #GlobalModal .modal-header").html("<h1>Error notification</h1>");
                $("body #GlobalModal .modal-body").html(jqXHR.responseText);
                $("body #GlobalModal").modal("show");
            },success : function(response){
            	itsme.html('<i class="fa fa-download"></i> Export to excel');
		        console.log(response);
		        window.location = urld;
		        var a = document.createElement("a");
		        a.href = response.file; 
		        a.download = response.name;
		        document.body.appendChild(a);
		        a.click();
		        a.remove();
            }
        }); */
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
    $("#form-filter .btn-filter").click(function(){
    	$('body #attendance-temporary #fetch-data-tables #table-list-data').DataTable().destroy();
        fetchAttendance();
        var startDate = $("#form-filter input[name=attendance_start]").val();
        var endDate = $("#form-filter input[name=attendance_end]").val();
        $("#attendance-temporary .result .panel-title >span").text(startDate+(($.trim(endDate).length > 0) ? '-'+endDate:'') ).addClass("bg-success");
    });

    fetchAttendance();
    $("#table-list-data").on("click",".btn-detail",function(){
    	var itsme = $(this);
    	var NIP = itsme.data("id");
    	var DATE = itsme.data("date");
    	var DATEEND = itsme.data("dateend");
    	if($.trim(NIP).length > 0){
    		var data = {
                NIP : NIP,
                DATE : DATE,
                DATEEND : DATEEND
            };
            var token = jwt_encode(data,'UAP)(*');
    		$.ajax({
                type : 'POST',
                url : base_url_js+"human-resources/detail-attendance-temp",
                data: {token:token},
                dataType : 'html',
                beforeSend :function(){
                    itsme.html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
                },error : function(jqXHR){
                	itsme.html('<i class="fa fa-folder-open"></i>');
                    $("body #GlobalModal .modal-header").html("<h1>Error notification</h1>");
                    $("body #GlobalModal .modal-body").html(jqXHR.responseText);
                    $("body #GlobalModal").modal("show");
                },success : function(response){
                	itsme.html('<i class="fa fa-folder-open"></i>');
			        $('#GlobalModal .modal-dialog').css({"width":"80%"});       
                    $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
			            '<h4 class="modal-title">Detail attendance </h4>');
			        $('#GlobalModal .modal-body').html(response);        
			        $('#GlobalModal').modal({
			            'show' : true,
			            'backdrop' : 'static'
			        });
                }
            });
    	}
    	
    });
  });
</script>