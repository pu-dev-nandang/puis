<style type="text/css">
#table-list-data_processing{background: #428bca;color: #fff;}
.please-open-filter{cursor: pointer}
#table-list-data > thead > tr{background: #20525a;color: #fff}
#table-list-data > tbody > tr{background: #fff;}
</style>
<div id="internal-participants">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-12">
					<div id="filter-form">
		                <div class="panel panel-default">
		                    <div class="panel-heading please-open-filter" data-toggle="collapse" data-target="#open-filter" aria-expanded="false" aria-controls="open-filter" >
		                        <h4 class="panel-title">
		                        <i class="fa fa-filter"></i> <label>Open Form Filter</label>
		                        <span class="pull-right"><i class="fa fa-chevron-down"></i></span>
		                        </h4>
		                    </div>
		                    <div class="panel-body collapse" id="open-filter">
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
			                	<div class="table-responsive">
			                	<table class="table table-bordered" id="table-list-data">
		                            <thead>
		                                <tr>
		                                    <th width="2%"><input type="checkbox" name="select_all"></th>
		                                    <th width="15%">Student</th>
		                                    <th width="15%">Study Program</th>
		                                    <th width="20%">Email</th>
		                                    <th width="20%">Email Parents</th>
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
            "responsive": true,
            "language": {
                "searchPlaceholder": "NIM, Name, Study Program"
            },
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
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
	                "render": function (data, type, row) {
	                    return "<input type='checkbox' name='std[]' value='"+data+"' class='check-box chk-std' data-id='"+row.NPM+"'>";
	                }
	            },
	            { "data": "NPM",
	              "render": function (data, type, row, meta) {
	              	return "<p class='student'><span class='btn btn-xs btn-default pull-right'>"+row.StatusStudent+"</span><span><i class='fa fa-id-card-o'></i> "+row.NPM+"</span><br><span><i class='fa fa-user'></i> "+row.Name+"</span><br><span><i class='fa fa-star'></i> "+row.religionName+"</span> &#183; "+"<span><i class='fa fa-"+((row.Gender == 'L') ? 'mars':'venus')+"'></i> "+((row.Gender == 'L') ? 'Male':'Female')+"</span></p>";
	              }
	          	},
	            { "data": "ProdiNameEng",
	              "render": function (data, type, row, meta) {
	              	return "<p class='npm'>"+row.ProdiEdu+" - "+row.ProdiNameEng+"<br>Class of "+row.ClassOf+"</p>";
	              }
	            },
	            { "data": "EmailPU",
	        	  "render": function (data, type, row, meta) {
	        	  	var label = "";
	        	  	var exEmailPU = isValidEmailAddress($.trim(row.EmailPU));
	        	  	var exEmail = isValidEmailAddress($.trim(row.Email));
	        	  	if(exEmailPU.length > 1){
						label += '<p><label><input type="checkbox" name="emailPU[]" value="'+row.EmailPU+'" class="check-box chk-child chk-'+row.NPM+' emailPU" data-name="'+row.Name+'"> '+row.EmailPU+'</label></p>';
					}
					if(exEmail.length > 1){
	            		label += '<p><label><input type="checkbox" name="emailP[]" value="'+row.Email+'" class="check-box chk-child chk-'+row.NPM+' emailP" data-name="'+row.Name+'"> '+row.Email+'</label></p>';
	        	  	}
					return label
                  }
            	},
	            { "data": "EmailFather",
	              "render": function (data, type, row, meta) {
	              	var label = "";
	              	var exEmailFather = isValidEmailAddress($.trim(row.EmailFather));
	              	var exEmailMother = isValidEmailAddress($.trim(row.EmailMother));
	        	  	if(exEmailFather.length > 1){
	            		label += '<p><label><input type="checkbox" name="emailF[]" value="'+row.EmailFather+'" class="check-box chk-child chk-'+row.NPM+' emailF" data-name="'+row.Father+'"> '+row.EmailFather+'</label></p>';
	        	  	}
	        	  	if(exEmailMother.length > 1){
	            		label += '<p><label><input type="checkbox" name="emailM[]" value="'+row.EmailMother+'" class="check-box chk-child chk-'+row.NPM+' emailM" data-name="'+row.Mother+'"> '+row.EmailMother+'</label></p>';
	        	  	}
	        	  	return label
	              }
	        	}
	        ],
        });
    }
	$(document).ready(function(){
		fetchingDataStudents();
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

	});
</script>