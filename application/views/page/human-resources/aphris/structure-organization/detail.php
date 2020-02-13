<style type="text/css">
#filter-user .add-me{
	cursor: pointer;
    background: #ddd;
    margin: 5px 0px;
    display: inherit;
    padding: 5px 10px;
    border-radius: 5px;
}
.cursor{cursor: pointer;}
.autocomplete-position-name{margin-bottom: 10px}
.autocomplete-position-name .load-list{background: #ddd;border-radius:5px;display: none; }
</style>
<?php if(!empty($detail)){ ?>
<form id="form-sto-participants" action="<?=base_url('human-resources/master-aphris/save-sto')?>" method="post" autocomplete="off">
	<div class="row">
		<div class="col-sm-12">
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
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<label>User</label>
				<div class="input-group">
			      	<input type="hidden" name="NIP" class="form-control required" required value="<?=(!empty($detail->member) ? $detail->member->NIP:'')?>" >
			      	<input type="text" class="form-control required member" required readonly value="<?=(!empty($detail->member) ? (!empty($detail->member->TitleAhead) ? $detail->member->TitleAhead.' ' : '').$detail->member->Name.(!empty($detail->member->TitleBehind) ? ' '.$detail->member->TitleBehind:'' ) : '' )?>">
			      	<div class="input-group-addon cursor" role="button" data-toggle="collapse" data-target="#filter-user" aria-expanded="false" aria-controls="filter-user"><i class="fa fa-search"></i> Find employee</div>
			      	<small class="text-danger text-message"></small>
			    </div>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<label>Level</label>
				<select class="form-control required" required name="LevelID">
					<option value="">Choose one</option>
					<?php if(!empty($level)){ 
					foreach ($level as $l) { 
						$selected="";
						if(!empty($detail->member)){
							if($l->ID == $detail->member->LevelID){
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
		<div class="col-sm-4">
			<div class="form-group">
				<label>Job title</label>
				<input type="text" class="form-control" name="jobtitle">
			</div>
		</div>
	</div>
	
	<div class="row form-group">
		<div class="col-sm-2">		
			<label>Active</label>
			<select class="form-control required" required name="isActive">
				<option value="">Choose one</option>
				<option value="1" <?=($detail->isActive == 1) ? 'selected':''?> >Active</option>
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
			var NIP = itsme.data("nip");
			var Name = itsme.text();
			$formPost.find(".member").val(Name);
			$formPost.find("input[name=NIP]").val(NIP);
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
			  		console.log($(this).attr("name")+"="+value);
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

		$formPost.on("keyup","input[name=title]",function(){
			var itsme = $(this);
			var value = itsme.val();
			console.log(value);
		});

	});
</script>
<?php }else{echo "<h3>Node not founded</h3>";} ?>