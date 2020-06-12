<style type="text/css">
	.checkbox-lb{font-weight: 100;cursor: pointer;}
</style>
<div id="access-logs">
	<div class="row" style="margin-bottom:15px">
		<div class="col-sm-4">
			<div class="btn-group">
				<button class="btn btn-warning btn-sm" type="button" onclick="window.history.go(-1); return false;"><i class="fa fa-angle-double-left"></i> Going back</button>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-3">
			<form id="form-access-control" action="<?=base_url('admin-log-config-save')?>" method="post" autocomplete="off">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"><i class="fa fa-edit"></i> Form Access control</h4>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label>Division</label>
							<select class="select2-select-00 full-width-fix select2-required" id="DivisionID" name="DivisionID">
			                  <option>Choose one</option>
			                  <?php for($i = 0; $i < count($G_division); $i++): ?>
			                    <option value="<?php echo $G_division[$i]['Code'] ?>" > <?php echo $G_division[$i]['Name2'] ?> </option>
			                  <?php endfor ?>
			                 </select>
			                 <span class="text-danger text-message"></span>
						</div>
						<div class="form-group">
							<label>Type Content</label>
							<select class="form-control required" name="TypeContent">
								<option value="">-choose one-</option>
								<option <?=($typecontent == 'user_qna') ? 'selected':''?> value="user_qna">Help</option>
								<option <?=($typecontent == 'knowledge_base') ? 'selected':''?> value="knowledge_base">Knowledge Base</option>
							</select>
							<span class="text-danger text-message"></span>
						</div>
						<div class="form-group">
							<label>Give an access :</label>
						</div>
						<div class="form-group">
							<label class="checkbox-lb"><input type="checkbox" name="IsLogEmp" value="Y"> access for Logs Employee</label>
						</div>
						<div class="form-group">
							<label class="checkbox-lb"><input type="checkbox" name="IsCreateGuide" value="Y"> access for create Guide Line</label>
						</div>
					</div>
					<div class="panel-footer text-right">
						<button class="btn btn-sm btn-primary btn-save" type="button">Save changes</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-sm-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title"><i class="fa fa-bars"></i> List of access control</h4>
				</div>
				<div class="panel-body">
					<div class="fetch-data table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th width="5%">No</th>
									<th>Division</th>
									<th>Type Content</th>
									<th>Access</th>
									<th>Remove</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="5">No data available in table</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>


<script type="text/javascript">
	$(document).ready(function(){
		$("#form-access-control .btn-save").click(function(){
			var itsme = $(this);
			var itsform = itsme.parent().parent().parent();
            itsform.find(".select2-required").each(function(){
                var value = $(this).val();
                if($.isNumeric(value)){
                    if($.trim(value) == ''){
                        $(this).addClass("error");
                        $(this).parent().find(".text-message").text("Please fill this field");
                        error = false;
                    }else{
                        error = true;
                        $(this).removeClass("error");
                        $(this).parent().find(".text-message").text("");
                    }
                }else{
                    error = false;  
                    $(this).addClass("error");
                    $(this).parent().find(".text-message").text("Please fill this field");
                }
            });
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
                loading_modal_show();
                $("#form-access-control")[0].submit();
            }else{
                alert("Please fill out the field.");
            }
		});
	});
</script>