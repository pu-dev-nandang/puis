<style type="text/css">#table-list-data_processing{background: #428bca;color: #fff;}</style>
<div id="internal-participants">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-12">
					<div id="filter-form">
						<div class="row">
						  <div class="col-sm-12">
						    <div class="panel panel-default">
						      <div class="panel-heading please-open-filter" data-toggle="collapse" data-target="#open-filter" aria-expanded="false" aria-controls="open-filter" >
						        <h4 class="panel-title"><i class="fa fa-filter"></i> <label>Form Filter</label>
						        <span class="pull-right"><i class="fa fa-chevron-down"></i></span>
						        </h4>
						      </div>
						      <div class="panel-body collapse" id="open-filter">
						        <form id="form-filter" action="" method="post" autocomplete="off">
						          <div class="row">
						            <div class="col-sm-2">
						              <div class="form-group">
						                <label>Lecturer</label>               
						                <input type="text" class="form-control" name="staff" placeholder="NIP or Name">               
						              </div>
						            </div>
						            
						            <div class="col-sm-3">
						              <div class="form-group">
						                <label>Position</label>               
						                <select class="form-control" name="position">
						                  <option value="">-Choose one-</option>
						                  <?php foreach ($position as $p) {
						                  echo '<option value="'.$p->ID.'">'.$p->Description.'</option>';
						                  } ?>
						                </select>               
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
						              <?php if(!empty($studyprogram)){ ?>
					                    <div class="col-sm-3">
					                    	<div class="fams">
						                        <div class="form-groups">
						                            <label>Study Program</label>                            
						                        </div>
						                        <div class="row">
						                            <?php foreach ($studyprogram as $s) { ?>
						                            <div class="col-sm-12">
						                                <div class="checkbox">
						                                    <label>
						                                        <input type="checkbox" name="study_program[]" value="<?=$s->ID?>"> <?=$s->NameEng?>
						                                    </label>
						                                </div>
						                            </div>
						                            <?php } ?>
						                        </div>                                        
					                        </div>                                        
					                    </div>
				                      <?php } ?>
						              <div class="col-sm-2">
						                <div class="form-groups">
						                  <label>Status lecturer</label>
						                </div>
						                <?php if(!empty($statusstd)) {
						                foreach ($statusstd as $t) { ?>
						                <div class="form-group">
						                  <div class="col-sm-10">
						                    <div class="checkbox">
						                      <label>
						                        <input type="checkbox" value="<?=$t->IDStatus?>" name="status[]" > <?=$t->Description?>
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
						          </div>
						          
						        </form>
						      </div>
						    </div>
						  </div>
						</div>
					</div>
				</div>
				<div class="col-sm-12">
					<div id="fetch-data-tables">
						<div class="panel panel-default">
			                <div class="panel-heading">
			                    <h5 class="panel-title"><i class="fa fa-bars"></i> List of lecturer</h5>
			                </div>
			                <div class="panel-body">
			                	<div class="table-responsive">
			                	<table class="table table-bordered" id="table-list-data">
		                            <thead>
		                                <tr>
											<th width="2%"><input type="checkbox" name="select_all"></th>
											<th width="30%">Lecturer</th>
											<th width="20%">Study Program</th>
											<th width="20%">Email</th>
											<th width="20%">Email PU</th>
		                                </tr>
		                            </thead>
		                            <tbody><tr><td colspan="9">Empty data</td></tr></tbody>
		                        </table>
		                        </div>
			                </div>	
		                </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	function fetchDataLecturer(sort=null,order=null) {
		var filtering = $("#form-filter").serialize();
		filtering = filtering+"&isLecturer=yes";
        if((sort && order) || ( sort !== null && order !== null) ){
          filtering = filtering+"&sortby="+sort+"&orderby="+order;
        }
        var token = jwt_encode({Filter : filtering},'UAP)(*');

        var dataTable = $('#fetch-data-tables #table-list-data').DataTable( {
            "destroy": true,
            "retrieve":true,
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "responsive": true,
            "language": {
                "searchPlaceholder": "NIP or  Name"
            },
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "ajax":{
                url : base_url_js+'api/database/__fetchEmployees', // json datasource
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
            		"data":"Name",
            		"render": function (data, type, row) {
	                    return "<input type='checkbox' name='std[]' value='"+data+"' class='check-box chk-std' data-id='"+row.NIP+"'>";
	                }
            	},
            	{
            		"data":"NIP",
            		"render": function (data, type, row, meta) {
		              	return "<p class='student'><span><i class='fa fa-id-card-o'></i> "+row.NIP+"</span><br><span><i class='fa fa-user'></i> "+row.Name+"</span><br><span><i class='fa fa-star'></i> "+row.EmpReligion+"</span> &#183; "+"<span><i class='fa fa-"+((row.Gender == 'L') ? 'mars':'venus')+"'></i> "+((row.Gender == 'L') ? 'Male':'Female')+"</span>  &#183; <span><i class='fa fa-graduation-cap'></i> "+row.EmpLevelEduName+"</span></p>";
	              	}
            	},          	
            	{
            		"data":"ProdiNameEng",
            		"render": function (data, type, row, meta) {
		              	return "<p class='prodi'><b>"+row.ProdiDegreeEng+"("+row.ProdiDegree+")"+"</b><br>"+row.ProdiNameEng+"</p>";
	              	}
            	},
            	{ 
            		"data": "Email",
					"render": function (data, type, row, meta) {
						var label = "";
						var exEmail = $.trim(row.Email);
						vMail = (!isValidEmailAddress(exEmail) ? "":exEmail);
						if(vMail.length > 1){
							label += '<p><label><input type="checkbox" name="emailP[]" value="'+vMail+'" class="check-box chk-child chk-'+row.NIP+' emailP" data-name="'+row.Name+'"> '+vMail+'</label></p>';
						}
						return label
					}
            	}, 
            	{ 
            		"data": "EmailPU",
					"render": function (data, type, row, meta) {
						var label = "";
						var exEmailPU = $.trim(row.EmailPU);
						vMail = (!isValidEmailAddress(exEmailPU) ? "":exEmailPU);
						if(vMail.length > 1){
							label += '<p><label><input type="checkbox" name="emailPU[]" value="'+vMail+'" class="check-box chk-child chk-'+row.NIP+' emailP" data-name="'+row.Name+'"> '+vMail+'</label></p>';
						}
						return label
					}
            	}, 
        	]
        });

	}
  $(document).ready(function(){
  	fetchDataLecturer();
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

    $("#form-filter .btn-filter").click(function(){
        $('body #participants-frm #fetch-data-tables #table-list-data').DataTable().destroy();
        fetchDataLecturer();
    });
    $tablelistdata = $("#table-list-data");
    $tablelistdata.on("change","input[name=select_all]",function(){
    	if($(this).is(':checked')){
    		$tablelistdata.find(".check-box").prop("checked",true);
    	}else{
    		$tablelistdata.find(".check-box").prop("checked",false);
    	}
    });
    $tablelistdata.on("change",".chk-std",function(){
    	var id = $(this).data("id");
    	if($(this).is(':checked')){
    		$tablelistdata.find(".chk-child.chk-"+id).prop("checked",true);
    	}else{
    		$tablelistdata.find(".chk-child.chk-"+id).prop("checked",false);
    	}
    });
    $(".please-open-filter").click(function(){
		var isOpen = $(this).attr("aria-expanded");
		if(isOpen == "false"){
			$(this).attr("aria-expanded",true);
			$(this).find(".panel-title > label").text("Close Form Filter");
			$(this).find("i.fa").toggleClass("fa-chevron-down fa-chevron-up");
		}else{
			$(this).attr("aria-expanded",false);
			$(this).find(".panel-title > label").text("Open Form Filter");
			$(this).find("i.fa").toggleClass("fa-chevron-up fa-chevron-down");
		}
	});

  });
</script>