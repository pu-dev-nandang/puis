<div id="student-participants">
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
			                        <label class="show-more-filter text-success" data-toggle="collapse" data-target="#advance-filter" aria-expanded="false" aria-controls="advance-filter" style="padding-top:0px">
			                            <span>Advance filter</span> 
			                            <i class="fa fa-angle-double-down"></i>
			                        </label>
			                    </div>
		                    </div>
			                <div class="collapse" id="advance-filter">
			                    
			                    <div class="row">
			                    	<?php if(!empty($yearIntake)){ ?>
				                    <div class="col-sm-2">
				                        <div class="fams">
					                        <div class="form-groups">
					                            <label>Class Of</label>                            
					                        </div>
					                        <div class="row">
					                            <?php foreach ($yearIntake as $y) { ?>
					                            <div class="col-sm-6">
					                                <div class="checkbox">
					                                    <label>
					                                        <input type="checkbox" name="Year[]" value="<?=$y->Year?>"> <?=$y->Year?>
					                                    </label>
					                                </div>
					                            </div>
					                            <?php } ?>
					                        </div>                                        
				                        </div>                                        
				                    </div>
				                    <?php } ?>

				                    <?php if(!empty($studyprogram)){ ?>
				                    <div class="col-sm-4">
				                    	<div class="fams">
					                        <div class="form-groups">
					                            <label>Study Program</label>                            
					                        </div>
					                        <div class="row">
					                            <?php foreach ($studyprogram as $s) { ?>
					                            <div class="col-sm-6">
					                                <div class="checkbox">
					                                    <label>
					                                        <input type="checkbox" name="ProdiID[]" value="<?=$s->ID?>"> <?=$s->Name?>
					                                    </label>
					                                </div>
					                            </div>
					                            <?php } ?>
					                        </div>                                        
				                        </div>                                        
				                    </div>
				                    <?php } ?>

			                        <?php if(!empty($statusstd)){ ?>
			                        <div class="col-sm-4">
			                        	<div class="fams">
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
			                        </div>
			                        <?php } ?>

			                        <?php if(!empty($religion)){ ?>
			                        <div class="col-sm-1">
			                        	<div class="fams">
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
			                        </div>
			                        <?php } ?>

			                        <div class="col-sm-1">
			                        	<div class="fams">
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
			                        </div>
			                    </div>
			                </div>
			                <div class="row" style="padding-top:22px">
			                    <div class="col-sm-8">
			                        <div class="form-group">
			                            <button class="btn btn-primary btn-filter" type="button"><i class="fa fa-search"></i> Search</button>
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
		<div class="col-sm-12">
			<div id="fetch-data-tables">
				<div class="panel panel-default">
	                <div class="panel-heading">
	                    <h5 class="panel-title"><i class="fa fa-bars"></i> List of students</h5>
	                </div>
	                <div class="panel-body">
	                	<table class="table table-bordered table-striped" id="table-list-data">
                            <thead>
                                <tr>
                                    <th width="2%"><input type="checkbox" name="select_all"></th>
                                    <th width="20%">Student</th>
                                    <th>Email PU</th>
                                    <th>Email Public</th>
                                    <th>Email Father</th>
                                    <th>Email Mother</th>
                                </tr>
                            </thead>
                            <tbody><tr><td colspan="6">Empty data</td></tr></tbody>
                        </table>
	                </div>	
                </div>
			</div>
		</div>
	</div>
</div>	

<script type="text/javascript">
	function fetchingDataStudents(sort=null,order=null) {
        //loading_modal_show();
        var filtering = $("#form-filter").serialize();
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
            "language": {
                "searchPlaceholder": "NIM, Name, Study Program"
            },
            "ajax":{
                url : base_url_js+'api/database/__fetchStudents', // json datasource
                ordering : false,
                data : {token:token},
                type: "post",  // method  , by default get
                error: function(jqXHR){  // error handling
                    //loading_modal_hide();
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
	                "mData": "Name",
	                "mRender": function (data, type, row) {
	                    return "<input type='checkbox' name='std[]' value='"+data+"' clas='chk-std'>";
	                }
	            },
	            { "data": "Name" },
	            { "data": "EmailPU",
	        	  "render": function (data, type, row, meta) {
	        	  	var label = "-";
	        	  	if(!jQuery.isEmptyObject(row.EmailPU) || row.EmailPU !== null){
						label = '<label><input type="checkbox" name="emailPU[]" value="'+row.EmailPU+'" class="emailPU"> '+row.EmailPU+'</label>';
					}
					return label
                  }
            	},
	            { "data": "Email",
	              "render": function (data, type, row, meta) {
	              	var label = "-";
	        	  	if(!jQuery.isEmptyObject(row.Email) || row.Email !== null){
	            		label = '<label><input type="checkbox" name="emailP[]" value="'+row.Email+'" class="emailP"> '+row.Email+'</label>';
	        	  	}
	        	  	return label
	              }
            	},
	            { "data": "EmailFather",
	              "render": function (data, type, row, meta) {
	              	var label = "-";
	        	  	if(!jQuery.isEmptyObject(row.EmailFather) || row.EmailFather !== null){
	            		label = '<label><input type="checkbox" name="emailF[]" value="'+row.EmailFather+'" class="emailF"> '+row.EmailFather+'</label>';
	        	  	}
	        	  	return label
	              }
	        	},
	            { "data": "EmailMother",
	              "render": function (data, type, row, meta) {
	              	var label = "-";
	              	var exEmailMother = $.trim(row.EmailMother);
	              	if(!jQuery.isEmptyObject(row.EmailMother) || exEmailMother !== null || !exEmailMother){
	            		label = '<label><input type="checkbox" name="emailM[]" value="'+row.EmailMother+'" class="emailM"> '+row.EmailMother+'</label>';
	        	  	}
	        	  	return label
	              }
	        	},
	        ],
        });
    }
	$(document).ready(function(){
		fetchingDataStudents();
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
            fetchingDataStudents();
        });
	});
</script>