<style type="text/css">
#form-sto-participants #list-emp > .panel-body > .multiple-row:nth-child(2) .remove-row{display: none}
#filter-user .add-me{
	cursor: pointer;
    background: #ddd;
    margin: 5px 0px;
    display: inherit;
    padding: 5px 10px;
    border-radius: 5px;
}
#filter-user .add-me.active{background: #499249;color: #fff}
.cursor{cursor: pointer;}
.autocomplete-position-name{margin-bottom: 10px}
.autocomplete-position-name .load-list{
	background: #fff;
	padding: 5px 10px;
    border-radius: 0px 0px 5px 5px;
    padding: 10px;
    border: 1px solid #ddd;
    display: none; 
}
.autocomplete-position-name .load-list .emp-list > p{cursor: pointer;}

</style>
<?php if(!empty($detail)){ ?>
<form id="form-sto-participants" action="<?=base_url('human-resources/master-aphris/save-sto')?>" method="post" autocomplete="off">
	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<label>Type Node</label>
				<select class="form-control type-node required" name="typeNode">
					<option value="">Choose One</option>
					<option value="1" <?=($detail->typeNode == 1) ? 'selected':''?> >Division</option>
					<option value="2" <?=($detail->typeNode == 2) ? 'selected':''?> >Staff</option>
				</select>
				<small class="text-danger text-message"></small>
				<input type="hidden" name="URIID" value="<?=!empty($STOID) ? $STOID : null?>">
			</div>
		</div>
		<div class="col-sm-10">
			<div class="pull-right">
				<button class="btn btn-primary btn-sm btn-child-node" data-parent="<?=$detail->ID?>" type="button"><i class="fa fa-plus"></i> Create Child</button>
				<button class="btn btn-danger btn-sm btn-remove-node" data-id="<?=$detail->ID?>" type="button"><i class="fa fa-trash"></i> Remove</button>
			</div>			
		</div>
	</div>
	<div class="form-group" style="margin-bottom:0px">
		<label>Title</label>
		<input type="hidden" name="ID" value="<?=$detail->ID?>">
		<input type="text" name="title" class="form-control required" required value="<?=$detail->title?>">
		<small class="text-danger text-message"></small>
	</div>
	<div class="autocomplete-position-name">
		<div class="row">
			<div class="col-sm-12">
				<div class="load-list">...</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label>Description</label>
		<textarea class="form-control" name="description"><?=$detail->description?></textarea>
	</div>

	<div class="panel panel-default" id="list-emp">
		<div class="panel-heading">
			<div class="pull-right">
				<div class="btn-group">
					<button class="btn btn-xs btn-default btn-add-emp" type="button"  role="button" data-toggle="collapse" data-target="#filter-user" aria-expanded="false" aria-controls="filter-user"><i class="fa fa-plus"></i></button>
					<button class="btn btn-xs btn-default btn-remove-emp" type="button"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<h4 class="panel-title">Add employee for this node</h4>
		</div>
		<div class="panel-body" style="max-height:300px;overflow:auto">
			<div class="panel panel-default collapse" id="filter-user">
				<div class="panel-heading">	
					<div class="pull-right">
						<span class="cursor" role="button" data-toggle="collapse" data-target="#filter-user" aria-expanded="false" aria-controls="filter-user"><i class="fa fa-times"></i></span>
					</div>
					<h4 class="panel-title">
						<i class="fa fa-filter"></i> Filter User
					</h4>
				</div>
				<div class="panel-heading">
					<div class="row">
			            <div class="col-sm-2">
			            	<div class="form-group">
			            		<label>Employee</label>
			            		<input type="text" class="form-control filter-user employee" >
			            	</div>
			            </div>
			            <div class="col-sm-2">
			              <div class="form-group">
			                <label>Division</label>               
			                <select class="form-control filter-user division">
			                  <option value="">-Choose one-</option>
			                  <?php if(!empty($division)){ 
			                  foreach ($division as $d) {
			                  echo '<option value="'.$d->ID.'">'.$d->Division.'</option>';
			                  } } ?>
			                </select>
			              </div>
			            </div>
						<div class="col-sm-2">
							<div class="form-group">
				                <label>Position</label>               
				                <select class="form-control filter-user position">
				                  <option value="">-Choose one-</option>
				                  <?php if(!empty($position)){ 
				                  foreach ($position as $p) {
				                  echo '<option value="'.$p->ID.'">'.$p->Description.'</option>';
				                  } } ?>
				                </select>               
			              	</div>
						</div>
						<div class="col-sm-3">
							<div class="form-groups">
			                  <label>Status employee</label>
			                </div>
			                <?php if(!empty($statusstd)) {
			                foreach ($statusstd as $t) { ?>
			                <div class="form-group">
			                  <div class="col-sm-12">
			                    <div class="checkbox">
			                      <label>
			                        <input type="checkbox" class="filter-user" value="<?=$t->IDStatus?>" > <?=$t->Description?>
			                      </label>
			                    </div>
			                  </div>
			                </div>
			                <?php } } ?>
						</div>
						<div class="col-sm-3">
							<div class="btn-group">						
								<button class="btn btn-primary btn-sm btn-search" type="button">Search</button>
								<button class="btn btn-default btn-sm btn-clear" type="button">Clear</button>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-body list-data"></div>
			</div>

			<?php if(!empty($detail->member)){
			foreach ($detail->member as $m) { ?>
			<div class="row multiple-row row-<?=$m->NIP?>" data-table="sto_rel_user" data-id="<?=$detail->ID?>" data-nip="<?=$m->NIP?>" data-career="<?=$m->CareerID?>">
				<div class="col-sm-4">
					<div class="form-group">
						<label>Employee</label>
				      	<input type="hidden" name="NIP[]" class="form-control nip" value="<?=$m->NIP?>" >
				      	<input type="text" class="form-control required member name" readonly value="<?=(!empty($m->TitleAhead) ? $m->TitleAhead.' ' : '').$m->Name.(!empty($m->TitleBehind) ? $m->TitleBehind : '')?>">
				      	<small class="text-danger text-message"></small>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label>Status</label>
						<select class="form-control level" name="StatusID[]">
							<option value="">Choose one</option>
							<?php if(!empty($status)){ 
							foreach ($status as $l) { 
								$selected="";
								if(!empty($detail->member)){
									if($l->ID == $m->StatusID){
										$selected = "selected";
									}
								}
							?>
							<option value="<?=$l->ID?>" <?=$selected?> ><?=$l->name?></option>
							<?php } } ?>
						</select>
						<small class="text-danger text-message"></small>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label>Job title</label>
						<input type="text" class="form-control job-title" name="jobtitle[]" value="<?=$m->JobTitle?>">
						<small class="text-danger text-message"></small>
					</div>
				</div>
				<div class="col-sm-1 remove-row">
					<div style="line-height:6">
						<button class="btn btn-sm btn-danger btn-remove-row-user" data-nip="<?=$m->NIP?>" data-id="<?=$detail->ID?>"  data-career="<?=$m->CareerID?>" type="button"><i class="fa fa-trash"></i></button>
					</div>
				</div>
			</div>
			<?php } }else{ ?>

			<div class="row multiple-row hidden">
				<div class="col-sm-4">
					<div class="form-group">
						<label>Employee</label>
				      	<input type="hidden" name="NIP[]" class="form-control nip" >
				      	<input type="text" class="form-control required member name" readonly>
				      	<small class="text-danger text-message"></small>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label>Status</label>
						<select class="form-control level" name="StatusID[]">
							<option value="">Choose one</option>
							<?php if(!empty($status)){ 
							foreach ($status as $l) { ?>
							<option value="<?=$l->ID?>" ><?=$l->name?></option>
							<?php } } ?>
						</select>
						<small class="text-danger text-message"></small>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label>Job title</label>
						<input type="text" class="form-control job-title" name="jobtitle[]">
						<small class="text-danger text-message"></small>
					</div>
				</div>
			</div>
			<?php } ?>

			<div class="list-multiple-row"></div>
		</div>
	</div>
	
	
	<div class="row form-group">
		<div class="col-sm-2">		
			<label>Active</label>
			<select class="form-control required" required name="isActive">
				<option value="">Choose one</option>
				<option value="1" <?=($detail->isActive == 1) ? 'selected':'selected'?> >Active</option>
				<option value="0" <?=($detail->isActive == 0) ? 'selected':''?> >Not Active</option>
			</select>
			<small class="text-danger text-message"></small>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
				<div class="text-center">			
					<button class="btn btn-success btn-sm btn-submit" type="button">Save changes</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>
	
</form>

<script type="text/javascript">
	$(document).ready(function(){
		$formPost = $("#form-sto-participants");
		$formPost.on("click","#filter-user .btn-search",function(){
			var itsme = $(this);
			var parent = itsme.parent().parent().parent();
			var employee = parent.find("input.employee").val();
			var division = parent.find("select.division option:selected").val();
			var position = parent.find("select.position option:selected").val();
			var dataPost = {};
			var status = [];
			$.each(parent.find(".filter-user"),function(){
				if($(this).is(':checked')){
					status.push($(this).val());
				}
			});			

			dataPost = {employee:employee,division:division, position:position, status:status};
			if(!jQuery.isEmptyObject(dataPost)){
				var token = jwt_encode(dataPost,'UAP)(*');
		      	$.ajax({
				    type : 'POST',
				    url : base_url_js+"human-resources/master-aphris/filter-user",
				    data : {token:token},
				    dataType : 'json',
				    beforeSend :function(){},
		            error : function(jqXHR){
		            	alert("Failed error filter.");
		            	console.log(jqXHR.responseText);
				    },success : function(response){
		            	if(!jQuery.isEmptyObject(response)){
		            		var append = '<div class="row">';
		            		$.each(response,function(k,v){
		            			append += '<div class="col-sm-2"><span class="add-me" data-nip="'+v.NIP+'"><i class="fa fa-plus"></i> '+(($.trim(v.TitleAhead).length > 0) ? v.TitleAhead+' ' : '' )+v.Name+(($.trim(v.TitleBehind).length > 0) ? ' '+v.TitleBehind : '' )+'</span></div>';
		            		});
		            		append += '</div>';
		            	}
	            		$("#filter-user .list-data").html(append);
				    }
				});
			}else{alert("Please fill the user form for the filter");}
		});

		$formPost.on("click","#filter-user .btn-clear",function(){
			$("#filter-user .list-data").html("");
		});

		$formPost.on("click","#filter-user .add-me",function(){
			var itsme = $(this);
			var typeNode = $formPost.find("select[name=typeNode]").val();
			var NIP = itsme.data("nip");
			var Name = itsme.text();
			var firstRow = $formPost.find("#list-emp .multiple-row:first");
			var cloneRow = firstRow.clone();
			if(!$formPost.find("#list-emp .multiple-row").hasClass("row-"+NIP)){
				$formPost.find("#filter-user .add-me").removeClass("active");
				itsme.addClass("active");

				cloneRow.removeClass("hidden").removeClass (function (index, className) {
				    return (className.match (/\brow-\S+/g) || []).join(' ');
				}).addClass("row-"+NIP).removeAttr("data-table").removeAttr("data-id");
				cloneRow.find(".form-control").val("").addClass("required");
				cloneRow.find(".nip").val(NIP);
				cloneRow.find(".name").val(Name);
				cloneRow.find(".remove-row").remove();
				if(typeNode == 2){
					$formPost.find("#list-emp .panel-body .list-multiple-row").append(cloneRow);				
				}else{
					$formPost.find("#list-emp .panel-body .multiple-row").remove();
					$formPost.find("#list-emp .panel-body .list-multiple-row").html(cloneRow);
				}
				$formPost.find("#list-emp .multiple-row.hidden").remove();
			}else{
				$formPost.find("#list-emp .panel-body .list-multiple-row").html(cloneRow);
			}
		});

		$formPost.on("click",".btn-submit",function(){
			var error = false;
			var itsme = $(this);
			var itsform = itsme.parent().parent().parent().parent().parent();
		 	itsform.find(".required").each(function(){
			  	var value = $(this).val();
			  	if($.trim(value) == ''){
			  		$(this).addClass("error");
			  		$(this).parent().find(".text-message").text("Please fill this field");
			  		error = false;
			  	}else{
			  		error = true;
			  		$(this).removeClass("error");
			  		$(this).parent().find(".text-message").text("");
			  	}
		  	});
		 	
		 	var totalError = itsform.find(".error").length;
		  	if(error && totalError == 0 ){
		  		itsme.prop("disabled",true);itsme.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>');
		  		$formPost[0].submit();
		  	}else{
		  		alert("Please fill out the field.");
		  	}
		});

		$formPost.on("click",".btn-child-node",function(){
			$(this).parent().remove();
			var parentID = $(this).data("parent");
			$('#GlobalModal .modal-header > .modal-title > span').text("Create child node from ");
            $formPost.find(".form-control").val("");
            var totalRow = $formPost.find("#list-emp .multiple-row").length;
            if(totalRow > 1){
            	$formPost.find("#list-emp .multiple-row").not(":first").remove();
            }
        	$formPost.find("#list-emp .multiple-row:last").addClass("hidden");            	
            $formPost.append('<input type="hidden" name="parentID" value="'+parentID+'">');
		});

		$formPost.on("click",".btn-remove-node",function(){
			var itsme = $(this);
			var ID = itsme.data("id");
			if(confirm("Are you sure wants to remove this node ?\nIf you remove this node, also remove the children of this node.")){
				var data = {
		          ID : ID,
		      	};
		      	var token = jwt_encode(data,'UAP)(*');
				$.ajax({
				    type : 'POST',
				    url : base_url_js+"human-resources/master-aphris/delete-node-sto",
				    data : {token:token},
				    dataType : 'json',
				    beforeSend :function(){itsme.prop("disabled",true);itsme.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>');}
				    ,error : function(jqXHR){
				    	$('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
		                    '<h4 class="modal-title">Error Fetch Student Data</h4>');
		                $('#GlobalModal .modal-body').html(jqXHR.responseText);
				    },success : function(response){
				    	if(!jQuery.isEmptyObject(response)){
				    		$('#GlobalModal').modal("hide");
				    		alert(response.message);
				    		location.reload(true);
				    	}else{alert("Failed execute the command.");}
				    }
				});
			}
		});

		$formPost.on("focus",".form-control",function(){
			var itsme = $(this);
			var name = itsme.attr("name");
			if(name != "title"){
				$formPost.find(".autocomplete-position-name .load-list").html("").css({display:'none'});
			}
		})

		$formPost.on("keyup","input[name=title]",function(){
			var itsme = $(this);
			var value = $.trim(itsme.val());
			if(value.length > 3){				
				var data = {
		          KEYWORD : value,
		      	};
		      	var token = jwt_encode(data,'UAP)(*');
				$.ajax({
				    type : 'POST',
				    url : base_url_js+"human-resources/master-aphris/get-position",
				    data : {token:token},
				    dataType : 'json',
				    error : function(jqXHR){
				    	$('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
		                    '<h4 class="modal-title">Error Fetch Student Data</h4>');
		                $('#GlobalModal .modal-body').html(jqXHR.responseText);
				    },success : function(response){
				    	if(!jQuery.isEmptyObject(response)){
				    		var appendList = '<div class="emp-list">';
				    		$.each(response,function(key,value){
				    			appendList += '<p class="usr-select"><span>'+value.Description+'</span> ('+value.Name+')</p>';
				    		});
				    		appendList += '</div>';
				    		$formPost.find(".autocomplete-position-name .load-list").css({display:'block'}).html(appendList);
				    	}else{
				    		$formPost.find(".autocomplete-position-name .load-list").html("").css({display:'none'});
				    	}
				    }
				});
			}else{$formPost.find(".autocomplete-position-name .load-list").html("").hide().css({display:'none'});}
		});

		$formPost.on("click",".autocomplete-position-name .load-list .emp-list > p",function(){
			var value = $(this).find("span").text();
			$formPost.find("input[name=title]").val(value);
			$formPost.find(".autocomplete-position-name .load-list").html("").css({display:'none'});
		});

		$formPost.on("click",".btn-remove-emp",function(){
			var itsme = $(this);
			var totalRow = $formPost.find("#list-emp .multiple-row").length;
			if(totalRow > 1){
				$lastRow = $formPost.find("#list-emp .multiple-row:last");
				var checkAttr = $lastRow.attr("data-table");
				if (typeof checkAttr !== typeof undefined && checkAttr !== false) {
					var NIP = $lastRow.data("nip");
					var ID = $lastRow.data("id");
					var CAREERID = $lastRow.data("CAREERID");
					var data = {
			          NIP : NIP ,
			          STOID : ID,
			          CAREERID : CAREERID
			      	};
			      	var token = jwt_encode(data,'UAP)(*');
			      	if(confirm("Are you sure wants to remove this "+NIP+" ?")){
						$.ajax({
						    type : 'POST',
						    url : base_url_js+"human-resources/master-aphris/delete-sto-user",
						    data : {token:token},
						    dataType : 'json',
						    error : function(jqXHR){
						    	$('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
				                    '<h4 class="modal-title">Error Fetch Student Data</h4>');
				                $('#GlobalModal .modal-body').html(jqXHR.responseText);
						    },success : function(response){
						    	if(!jQuery.isEmptyObject(response)){					    		
						    		$lastRow.remove();
						    	}
						    }
						});
					}
				}else{
					$lastRow.remove();
				}
			}
		});

		$formPost.on("click",".btn-remove-row-user",function(){
			var itsme = $(this);			
			var NIP = itsme.data("nip");
			var ID = itsme.data("id");
			var CAREERID = itsme.data("career");
			var parent = itsme.parent().parent().parent();
			var data = {
	          NIP : NIP ,
	          STOID : ID,
	          CAREERID : CAREERID
	      	};
	      	var token = jwt_encode(data,'UAP)(*');
	      	if(confirm("Are you sure wants to remove this "+NIP+" ?")){
				$.ajax({
				    type : 'POST',
				    url : base_url_js+"human-resources/master-aphris/delete-sto-user",
				    data : {token:token},
				    dataType : 'json',
				    error : function(jqXHR){
				    	$('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
		                    '<h4 class="modal-title">Error Fetch Student Data</h4>');
		                $('#GlobalModal .modal-body').html(jqXHR.responseText);
				    },success : function(response){
				    	if(!jQuery.isEmptyObject(response)){					    		
				    		parent.remove();
				    	}
				    }
				});
			}
		});

		$formPost.on("change","select[name=typeNode]",function(){
			var value = $(this).val();
			$rows = $formPost.find("#list-emp .panel-body .list-multiple-row .multiple-row");
			var totalRow = $rows.length;
			
			if(value == 1){ //divisi
				if(totalRow > 1){
					var hasAttr = $rows.attr("data-nip");
		            if(typeof hasAttr !== typeof undefined && hasAttr !== false){
		            	$rows.not(":first").remove();
		            }
				}
			}

		});

	});
</script>
<?php }else{echo "<h3>Node not founded</h3>";} ?>