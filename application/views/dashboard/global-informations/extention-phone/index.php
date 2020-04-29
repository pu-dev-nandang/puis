<style type="text/css">#table-list-data_processing{background: #428bca;color: #fff;}</style>
<div id="extention-phone">
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
				                <label>Employee</label>               
				                <input type="text" class="form-control" name="staff" placeholder="NIP or Name">               
				              </div>
				            </div>
				            <?php if(!empty($division)){ ?>
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
				            <?php } ?>
				            <?php if(!empty($position)){ ?>
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
				            <?php } ?>
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
				                  <label>Status employee</label>
				                </div>
				                <?php if(!empty($statusstd)) {
				                foreach ($statusstd as $t) { 
				                if($t->IDStatus != '-1'){	?>
				                <div class="form-group">
				                  <div class="col-sm-10">
				                    <div class="checkbox">
				                      <label>
				                        <input type="checkbox" value="<?=$t->IDStatus?>" name="status[]" checked > <?=$t->Description?>
				                      </label>
				                    </div>
				                  </div>
				                </div>
				                <?php } } } ?>
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
						            <a class="btn btn-default" href="">Clear</a>
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
			                    <h5 class="panel-title"><i class="fa fa-bars"></i> List of employee</h5>
			                </div>
			                <div class="panel-body">
			                	<div class="table-responsive">
			                	<table class="table table-bordered" id="table-list-data">
		                            <thead>
		                                <tr>
											<th width="5%">No</th>
											<th width="30%">Employee</th>
											<th width="20%">Position</th>
											<th width="20%">Email</th>
											<th width="20%">Extension Phone</th>
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


<script type="text/javascript">
	function isValidEmailAddress(emailAddress) {
	    var pattern = /(?!.*\.{2})^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
	    return pattern.test(String(emailAddress).toLowerCase());
	}
	function fetchDataEmployee(sort=null,order=null) {
		var filtering = $("#form-filter").serialize();
		
        if((sort && order) || ( sort !== null && order !== null) ){
          filtering = filtering+"&sortby="+sort+"&orderby="+order;
        }
        var token = jwt_encode({Filter : filtering},'UAP)(*');

        var dataTable = $('#fetch-data-tables #table-list-data').DataTable( {
            "destroy": true,
            "processing": true,
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
            		"data":"NIP",
            		render: function (data, type, row, meta) {
				        return meta.row + meta.settings._iDisplayStart + 1;
				    }
            	},
            	{
            		"data":"NIP",
            		"render": function (data, type, row, meta) {
            			var Photo = $.trim(row.Photo);
            			var elmPhoto = '<img src="'+base_url_js+'uploads/employees/'+Photo+'" class="img-thumbnail" style="width:60px;float:left;margin-right:10px">';
		              	var exEmpLevelEduName = $.trim(row.EmpLevelEduName);
		              	return "<p class='student'><span class='btn btn-xs btn-default pull-right'>"+row.EmpStatus+"</span><span><i class='fa fa-id-card-o'></i> "+row.NIP+"</span><br><span><i class='fa fa-user'></i> "+row.Name+"</span><br><span><i class='fa fa-star'></i> "+row.EmpReligion+"</span> &#183; "+"<span><i class='fa fa-"+((row.Gender == 'L') ? 'mars':'venus')+"'></i> "+((row.Gender == 'L') ? 'Male':'Female')+"</span>  &#183; <span><i class='fa fa-graduation-cap'></i> "+((exEmpLevelEduName.length > 0) ? row.EmpLevelEduName : '-')+"</span></p>";
	              	}
            	},          	
            	{
            		"data":"DivisionMain",
            		"render": function (data, type, row, meta) {
		              	return "<p class='prodi'><b>"+row.DivisionMain+"</b><br>"+row.PositionMain+"</p>";
	              	}
            	},
            	{ 
            		"data": "Email",
					"render": function (data, type, row, meta) {
						var label = "";
						var exEmail = $.trim(row.Email);
						vMail = (!isValidEmailAddress(exEmail) ? "":exEmail);
						var exEmailPU = $.trim(row.EmailPU);
						vMailPU = (!isValidEmailAddress(exEmailPU) ? "":exEmailPU);
						if(vMail){
							label += '<p>'+vMail+'</label></p>';
						}
						if(vMailPU){
							label += '<p>'+vMailPU+'</label></p>';
						}

						return label
					}
            	}, 
            	{ 
            		"data": "Extension"					
            	}, 
        	]
        });

	}
  $(document).ready(function(){
  	fetchDataEmployee();
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
        $('body #extention-phone #fetch-data-tables #table-list-data').DataTable().destroy();
        fetchDataEmployee();
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