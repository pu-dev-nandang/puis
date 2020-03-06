<script type="text/javascript">
    $(document).ready(function(){
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-training").addClass("active");
        $("#datePicker-training,#datePickerSD-training").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });
    });
</script>
<form id="form-training" action="<?=base_url('human-resources/employees/training-save')?>" method="post" autocomplete="off">
    <input type="hidden" name="NIP" value="<?=$NIP?>">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-edit"></i> Please fill up this form with correctly data</h4>
        </div>
        <div class="panel-body">
        	<div class="row">
        		<div class="col-sm-12">
        			<div class="panel panel-default" id="multiple-field" data-source="training">
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
                            <h4 class="panel-title">
                                Training 
                            </h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered table-training" id="table-list-training">
                                <thead>
                                    <tr>
                                        <td width="2%">No</td>
                                        <td>Training Title</td>
                                        <td>Organizer</td>
                                        <td>Start Event</td>
                                        <td>End Event</td>
                                        <td>Location</td>
                                        <td colspan="2">Cost</td>
                                        <td>Certificate</td>
                                        <td width="10%">Category</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="hidden" class="form-control train-ID" name="trainingID[]">
                                        <input type="hidden" class="train-category" name="trainingCategory[]" value="holding">
                                        <input type="text" class="form-control train-name required" name="trainingTitle[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control train-organizer required" name="organizer[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control datepicker-tmp required train-start_event" id="datePicker-training" name="trainingStart[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control datepicker-sd required train-end_event" id="datePickerSD-training" name="trainingEnd[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control train-location required" name="trainingLocation[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control train-costCompany number" name="trainingCostCompany[]" placeholder="Cost from company">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control train-costEmployee number required" name="trainingCostEmployee[]" placeholder="Cost from employee">
                                        <small class="text-danger text-message"></small></td>
                                        <td><div class="fetch-file train-certificate"></div><input type="file" name="certificate[]"  accept="application/pdf" ></td>
                                        <td><select name="category" class="form-control required">
                                            <option value="">Choose one</option>
                                            <option value="local">Local</option>
                                            <option value="holding">Holding</option>
                                            <option value="external">External</option>
                                        </select><small class="text-danger text-message"></small></td>
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
	$(document).ready(function(){
		$("#form-training .btn-submit").click(function(){
            var itsme = $(this);
            var itsform = itsme.parent().parent().parent();
            var error = false;
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
                $("#form-training")[0].submit();
            }else{
                alert("Please fill out the field.");
            }
        });


		var myData = fetchAdditionalData("<?=$NIP?>");
        if(!jQuery.isEmptyObject(myData)){
            if(!jQuery.isEmptyObject(myData.MyEducationTraining)){
                $tablename = $(".table-training");var num = 1;
                $.each(myData.MyEducationTraining,function(key,value){
            		var isHolding = false;
            		//if(value.category == "holding"){
	                	$cloneRow = $tablename.find("tbody > tr:last").clone();
	                    $cloneRow.attr("data-table","employees_educations_training").attr("data-id",value.ID).attr("data-name",value.name);
	                    $cloneRow.find("td:first").text(num);
	                    $.each(value,function(k,v){
	                    	$cloneRow.find(".train-"+k).val(v);    
	                        if(k == "start_event"){
				        		var cc = $cloneRow.find(".datepicker-tmp").attr("id","datePicker-training-"+num).removeClass("hasDatepicker");
				        		cc.datepicker({
						            dateFormat: 'yy-mm-dd',
						            changeYear: true,
						            changeMonth: true
						        });
				        	}  
				        	if(k == "end_event"){
				        		var cc = $cloneRow.find(".datepicker-sd").attr("id","datePickerSD-training-"+num).removeClass("hasDatepicker");
				        		cc.datepicker({
						            dateFormat: 'yy-mm-dd',
						            changeYear: true,
						            changeMonth: true
						        });
				        	}     
	                    });

	            		$tablename.find("tbody").append($cloneRow);
	                    num++;
                	//}
                });
                $tablename.find("tbody tr:first").remove();                
            }


        }

	});
</script>