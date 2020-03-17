<script type="text/javascript">
    $(document).ready(function(){
        $("#form-employee .navigation-tabs ul > li").removeClass("active");
        $("#form-employee .navigation-tabs ul > li.nv-family").addClass("active");
        $("#datePicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });
    });
</script>
<form id="form-family-member" action="<?=base_url('profile/save-changes')?>" method="post" autocomplete="off" style="margin:0px">
<input type="hidden" name="NIP" value="<?=$NIP?>">
<input class="form-control" name="action" type="hidden" value="family" />
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-edit"></i> Please provide details of each regular family member</h4>
        </div>
        <div class="panel-body">
        	<div class="row">
        		<div class="col-sm-12">
        			<div class="panel panel-default" id="multiple-field" data-source="family">
	        			<div class="panel-heading">
	        				<div class="pull-right">
                                <div class="btn-group">
                                    <button class="btn btn-default btn-xs btn-add" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button class="btn btn-default btn-xs btn-remove" type="button">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
	        				<h4 class="panel-title">Family Member</h4>
	        			</div>
	        			<div class="panel-body">
	        				<table class="table table-bordered" id="table-list-family">
				        		<thead>
				        			<tr>
				        				<th width="2%" rowspan="2" style="vertical-align: middle">No</th>
				        				<th style="vertical-align: middle" rowspan="2">Relation with officer</th>
				        				<th style="vertical-align: middle" rowspan="2">Gender</th>
				        				<th style="vertical-align: middle" rowspan="2">Name</th>
				        				<th style="text-align:center" colspan="2">Birtdate</th>
				        				<th style="vertical-align: middle" rowspan="2">Last Education</th>
				        			</tr>
				        			<tr>
				        				<th>Place</th>
				        				<th>Date</th>
				        			</tr>
				        		</thead>
				        		<tbody>
				        			<tr>
				        				<td>1</td>
				        				<td><input type="hidden" class="form-control fam-ID" name="familyID[]">
			        					<select class="form-control required fam-relationID" required name="familyrelation[]" >
				        					<option value="">Choose one</option>
				        					<?php if(!empty($familytree)){
			        						foreach ($familytree as $f) {
			        							echo '<option value="'.$f->ID.'">'.$f->name.'</option>';
			        						} } ?>
				        				</select>
				        				<small class="text-danger text-message"></small></td>
				        				<td><select class="form-control required fam-gender" required name="familygender[]">
				        					<option value="">Choose one</option>
				        					<option value="L">Male</option>
				        					<option value="P">Female</option>
				        				</select><small class="text-danger text-message"></small></td>
				        				<td><input type="text" class="form-control fam-name" name="familyname[]" ></td>
				        				<td><input type="text" class="form-control fam-placeBirth" name="familyplaceBirth[]"><small class="text-danger text-message"></small></td>
				        				<td><input type="text" class="form-control datepicker-tmp fam-birthdate dp-1" name="familybirthdate[]" id="datePicker"><small class="text-danger text-message"></small></td>
				        				<td><select class="form-control fam-lastEduID" name="familylastEdu[]" >
				                            <option value="">Choose one</option>                                                            
				                            <?php if(!empty($educationLevel)){
				                            foreach ($educationLevel as $v) {
				                            echo '<option value="'.$v->ID.'">'.$v->Level.'</option>';
				                            } } ?>
				                        </select>
				                        <small class="text-danger text-message"></small></td>				                        
				        			</tr>
				        		</tbody>
				        	</table>
	        			</div>
	        		</div>	
        		</div>        		
        	</div>
        	
        </div>
        <div class="panel-footer text-right">
            <button class="btn btn-success btn-submit" type="button">Save changes</button>
        </div>
    </div>
</form>

<script type="text/javascript">
	function loadMyFamily(response) {
		$tableFamily = $("#table-list-family");
		var temp = "";var num = 1;
		$.each(response,function(k,v){
			$cloneRow = $tableFamily.find("tbody tr:last").clone();
			$cloneRow.attr("data-table","employees_family_member").attr("data-id",v.ID).attr("data-name",v.name);
			$cloneRow.find("td:first").text(num);

	        $.each(v,function(x,y){
	        	$cloneRow.find(".fam-"+x).val(y);	        	
	        	if(x == "birthdate"){
	        		var cc = $cloneRow.find(".datepicker-tmp").attr("id","datePicker-"+num).removeClass("hasDatepicker");
	        		cc.datepicker({
			            dateFormat: 'yy-mm-dd',
			            changeYear: true,
			            changeMonth: true
			        });
	        	}
	        });
			
			$tableFamily.find("tbody").append($cloneRow);
			num++;	
		});
		$tableFamily.find("tbody tr:first").remove();
	}
	$(document).ready(function(){
		$("#form-family-member .btn-submit").click(function(){
            var itsme = $(this);
            var itsform = itsme.parent().parent().parent();
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
                loading_page_modal();
                $("#form-family-member")[0].submit();
            }else{
                alert("Please fill out the field.");
            }
        });

        var myFams = fetchAdditionalData("<?=$NIP?>");
        if(!jQuery.isEmptyObject(myFams)){
            if(!jQuery.isEmptyObject(myFams.MyFamily)){
                $tablename = $("#table-list-family"); var num = 1;
                $.each(myFams.MyFamily,function(key,value){
                    $cloneRow = $tablename.find("tbody > tr:last").clone();
                    $cloneRow.attr("data-table","employees_family_member").attr("data-id",value.ID).attr("data-name",value.name);
                    $cloneRow.find("td:first").text(num);
                    $.each(value,function(k,v){
                        $cloneRow.find(".fam-"+k).val(v);    
                        if(k == "birthdate"){
			        		var cc = $cloneRow.find(".datepicker-tmp").attr("id","datePicker-"+num).removeClass("hasDatepicker");
			        		cc.datepicker({
					            dateFormat: 'yy-mm-dd',
					            changeYear: true,
					            changeMonth: true
					        });
			        	}                    
                    });
                    
                    $tablename.find("tbody").append($cloneRow);
                    num++;
                });
                $tablename.find("tbody tr:first").remove();
            }
        }
	});
</script>