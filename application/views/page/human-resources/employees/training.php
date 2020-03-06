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
        			<div class="panel panel-default" id="multiple-field" data-source="training-holding">
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
                                Holding Training 
                            </h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered table-holding" id="table-list-training-holding">
                                <thead>
                                    <tr>
                                        <td width="2%">No</td>
                                        <td>Training Title</td>
                                        <td>Trainer Name</td>
                                        <td>Start Event</td>
                                        <td>End Event</td>
                                        <td>Location</td>
                                        <td>Result Feedback</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="hidden" class="form-control train-ID" name="trainingID[]">
                                        <input type="hidden" class="train-category" name="trainingCategory[]" value="holding">
                                        <input type="text" class="form-control train-name required" name="trainingTitle[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control train-trainer required" name="trainingTrainer[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control datepicker-tmp required train-start_event" id="datePicker-training-holding" name="trainingStart[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control datepicker-sd required train-end_event" id="datePickerSD-training-holding" name="trainingEnd[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control train-location required" name="trainingLocation[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control train-feedback" name="trainingFeedback[]">
                                        <small class="text-danger text-message"></small></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
        		</div>
        	</div>


        	<div class="row">
        		<div class="col-sm-12">
        			<div class="panel panel-default" id="multiple-field" data-source="training-local">
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
                                Local Training 
                            </h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered table-local" id="table-list-training-local">
                                <thead>
                                    <tr>
                                        <td width="2%">No</td>
                                        <td>Training Title</td>
                                        <td>Trainer Name</td>
                                        <td>Start Event</td>
                                        <td>End Event</td>
                                        <td>Location</td>
                                        <td>Result Feedback</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="hidden" class="form-control train-ID" name="trainingID[]">
                                        <input type="hidden" class="train-category" name="trainingCategory[]" value="local">
                                        <input type="text" class="form-control train-name required" name="trainingTitle[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control train-trainer required" name="trainingTrainer[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control datepicker-tmp required train-start_event" id="datePicker-training-local" name="trainingStart[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control datepicker-sd required train-end_event" id="datePickerSD-training-local" name="trainingEnd[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control train-location required" name="trainingLocation[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control train-feedback" name="trainingFeedback[]">
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
                $tablename = $(".table-holding");var num = 1;
                $.each(myData.MyEducationTraining,function(key,value){
            		var isHolding = false;
            		if(value.category == "holding"){
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
                	}
                });
                $tablename.find("tbody tr:first").remove();


                $tablename2 = $(".table-local");var num_ = 1;
                $.each(myData.MyEducationTraining,function(key,value){
            		var isHolding = false;
            		if(value.category == "local"){
	                	$cloneRow = $tablename2.find("tbody > tr:last").clone();
	                    $cloneRow.attr("data-table","employees_educations_training").attr("data-id",value.ID).attr("data-name",value.name);
	                    $cloneRow.find("td:first").text(num_);
	                    $.each(value,function(k,v){
	                    	$cloneRow.find(".train-"+k).val(v);    
	                        if(k == "start_event"){
				        		var cc = $cloneRow.find(".datepicker-tmp").attr("id","datePicker-training-local-"+num_).removeClass("hasDatepicker");
				        		cc.datepicker({
						            dateFormat: 'yy-mm-dd',
						            changeYear: true,
						            changeMonth: true
						        });
				        	}  
				        	if(k == "end_event"){
				        		var cc = $cloneRow.find(".datepicker-sd").attr("id","datePickerSD-training-local-"+num_).removeClass("hasDatepicker");
				        		cc.datepicker({
						            dateFormat: 'yy-mm-dd',
						            changeYear: true,
						            changeMonth: true
						        });
				        	}     
	                    });

	            		$tablename2.find("tbody").append($cloneRow);
	                    num_++;
                	}
                });
                $tablename2.find("tbody tr:first").remove();
            }


        }

	});
</script>