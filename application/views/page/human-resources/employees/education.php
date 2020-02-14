<script type="text/javascript">
    $(document).ready(function(){
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-edu").addClass("active");
        $("#datePicker-non-educations,#datePickerSD-non-educations,#datePicker-training,#datePickerSD-training").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });
    });
</script>
<form id="form-educations" action="<?=base_url('human-resources/employees/education-save')?>" method="post" autocomplete="off">
    <input type="hidden" name="NIP" value="<?=$NIP?>">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-edit"></i> Please fill up this form with correctly data</h4>
        </div>
        <div class="panel-body">
        	<div class="row">
        		<div class="col-sm-12">
        			<div class="panel panel-default" id="multiple-field" data-source="educations">
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
                                Educations
                            </h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered" id="table-list-educations">
                                <thead>
                                    <tr>
                                        <td width="2%">No</td>
                                        <td>Level Education</td>
                                        <td>Institute Name</td>
                                        <td>Country/City</td>
                                        <td>Major</td>
                                        <td>Graduation Year</td>
                                        <td>GPA</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="hidden" class="form-control edu-ID" name="eduID[]">
                                            <select class="form-control required edu-levelEduID" required name="eduLevel[]" >
                                                <option value="">Choose one</option>                                                            
                                                <?php if(!empty($educationLevel)){
                                                foreach ($educationLevel as $v) {
                                                echo '<option value="'.$v->ID.'">'.$v->Level.'</option>';
                                                } } ?>
                                            </select>
                                            <small class="text-danger text-message"></small>
                                        </td>
                                        <td><input type="text" class="form-control required edu-instituteName" required name="eduInstitute[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control required edu-location" required name="eduCC[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control edu-major" name="eduMajor[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control required edu-graduation" required name="eduGraduation[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control edu-gpa" name="eduGPA[]">
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
        			<div class="panel panel-default" id="multiple-field" data-source="non-educations">
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
                                Non Formal Educations
                            </h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered" id="table-list-non-educations">
                                <thead>
                                    <tr>
                                        <td width="2%">No</td>
                                        <td>Institute Name</td>
                                        <td>Subject</td>
                                        <td>Start Event</td>
                                        <td>End Event</td>
                                        <td>Country/City</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="hidden" class="form-control non-edu-ID" name="nonEduID[]">
                                        <input type="text" class="form-control non-edu-instituteName" name="nonEduInstitute[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control non-edu-subject" name="nonEduSubject[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control datepicker-tmp non-edu-start_event" id="datePicker-non-educations" name="nonEduStart[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control datepicker-sd non-edu-end_event" id="datePickerSD-non-educations" name="nonEduEnd[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control non-edu-location" name="nonEduCC[]">
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
                            <table class="table table-bordered" id="table-list-training">
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
                                        <input type="text" class="form-control train-name" name="trainingTitle[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control train-trainer" name="trainingTrainer[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control datepicker-tmp train-start_event" id="datePicker-training" name="trainingStart[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control datepicker-sd train-end_event" id="datePickerSD-training" name="trainingEnd[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control train-location" name="trainingLocation[]">
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
		$("#form-educations .btn-submit").click(function(){
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
                loading_modal_show();
                $("#form-educations")[0].submit();
            }else{
                alert("Please fill out the field.");
            }
        });


		var myData = fetchAdditionalData("<?=$NIP?>");
        if(!jQuery.isEmptyObject(myData)){
            if(!jQuery.isEmptyObject(myData.MyEducation)){
                $tablename = $("#table-list-educations"); var num = 1;
                $.each(myData.MyEducation,function(key,value){
                    $cloneRow = $tablename.find("tbody > tr:last").clone();
                    $cloneRow.attr("data-table","employees_educations").attr("data-id",value.ID).attr("data-name",value.instituteName);
                    $cloneRow.find("td:first").text(num);
                    $.each(value,function(k,v){
                        $cloneRow.find(".edu-"+k).val(v);    
                    });
                    
                    $tablename.find("tbody").append($cloneRow);
                    num++;
                });
                $tablename.find("tbody tr:first").remove();
            }

            if(!jQuery.isEmptyObject(myData.MyEducationNonFormal)){
                $tablename = $("#table-list-non-educations"); var num = 1;
                $.each(myData.MyEducationNonFormal,function(key,value){
                    $cloneRow = $tablename.find("tbody > tr:last").clone();
                    $cloneRow.attr("data-table","employees_educations_non_formal").attr("data-id",value.ID).attr("data-name",value.instituteName);
                    $cloneRow.find("td:first").text(num);
                    $.each(value,function(k,v){
                        $cloneRow.find(".non-edu-"+k).val(v);    
                        if(k == "start_event"){
			        		var cc = $cloneRow.find(".datepicker-tmp").attr("id","datePicker-"+num).removeClass("hasDatepicker");
			        		cc.datepicker({
					            dateFormat: 'yy-mm-dd',
					            changeYear: true,
					            changeMonth: true
					        });
			        	}  
			        	if(k == "end_event"){
			        		var cc = $cloneRow.find(".datepicker-sd").attr("id","datePickerSD-"+num).removeClass("hasDatepicker");
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

            if(!jQuery.isEmptyObject(myData.MyEducationTraining)){
                $tablename = $("#table-list-training"); var num = 1;
                $.each(myData.MyEducationTraining,function(key,value){
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
                });
                $tablename.find("tbody tr:first").remove();
            }


        }

	});
</script>