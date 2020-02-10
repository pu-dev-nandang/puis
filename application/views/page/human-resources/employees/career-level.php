<script type="text/javascript">
    $(document).ready(function(){
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-career").addClass("active");
        $("#divisionID,#positionID").select2({'width':'100%'});
    });
</script>
<form id="form-additional-info" action="" method="post" autocomplete="off">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-edit"></i> Please fill up this form with correctly data</h4>
        </div>
        <div class="panel-body">
        	<div class="row">
        		<div class="col-sm-12">
        			<div class="panel panel-default" id="multiple-field" data-source="career">
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
        					<h4 class="panel-title">Career Level</h4>
        				</div>
        				<div class="panel-body">
        					<table class="table table-bordered" id="table-list-career">
        						<thead>
        							<tr>
        								<th width="2%">No</th>
        								<th colspan="2">Site Date</th>
        								<th>Level</th>
        								<th>Dept</th>
        								<th>Position</th>
        								<th>Job Title</th>
        								<th>Superior</th>
        								<th>Status</th>
        								<th>Remarks</th>
        							</tr>
        						</thead>
        						<tbody>
        							<tr>
        								<td>1</td>
        								<td><input type="hidden" class="form-control required" required name="careerID[]">
        									<input type="text" class="form-control required" required name="startJoin[]" id="startJoin" placeholder="Start Date" >
        									<small class="text-danger text-message"></small></td>
        								<td><input type="text" class="form-control required" required name="endJoin[]" id="endJoin" placeholder="End Date">
        									<small class="text-danger text-message"></small></td>
        								<td><select class="form-control required" name="statusLevelID[]" required>
        									<option value="">Choose Level</option>
        									<?php if(!empty($status)){
        									foreach ($status as $s) {
        										if($s->ID == 1 || $s->ID == 2){
        										echo '<option value="'.$s->ID.'">'.$s->name.'</option>';	
    										} } } ?>
        								</select>
        								<small class="text-danger text-message"></small></td>
        								<td><select class="required" name="divisionID[]" id="divisionID" required>
        									<option>Choose Division</option>
        									<?php if(!empty($division)){
        									foreach ($division as $d) {
        										echo '<option value="'.$d->ID.'">'.$d->Division.'</option>';	
    										} } ?>
        								</select>
        								<small class="text-danger text-message"></small></td>
        								<td><select class="required" name="positionID[]" id="positionID" required>
        									<option>Choose Position</option>
        									<?php if(!empty($position)){
        									foreach ($position as $p) {
        										echo '<option value="'.$p->ID.'">'.$p->Position.'</option>';	
    										} } ?>
        								</select>
        								<small class="text-danger text-message"></small></td>
        								<td><input type="text" name="jobTitle[]" class="form-control required" required></td>
        								<td><input type="text" name="superior[]" class="form-control required" required><small class="text-danger text-message"></small></td>
        								<td><select class="form-control required" name="statusID[]" required>
        									<option value="">Choose Status</option>
        									<?php if(!empty($status)){
        									foreach ($status as $ss) {
        										if($ss->ID != 1 && $ss->ID != 2){
        										echo '<option value="'.$ss->ID.'">'.$ss->name.'</option>';	
    										} } } ?>
        								</select>
        								<small class="text-danger text-message"></small></td>
        								<td><input type="text" class="form-control" name="remarks[]" ></td>
        							</tr>
        						</tbody>
        					</table>
        				</div>
        			</div>
        		</div>
        	</div>
        </div>
        <div class="panel-footer text-right">
            <button class="btn btn-success" type="button">Save changes</button>
        </div>
    </div>
</form>