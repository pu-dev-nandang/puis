<script type="text/javascript">
    $(document).ready(function(){
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-training").addClass("active");
        $("#datePicker-training,#datePickerSD-training").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });

        var timeTags = timeList();
        $( "#autocomplete-start-training,#autocomplete-end-training" ).autocomplete({
          source: timeTags
        });
    });
</script>
<form id="form-training" action="<?=base_url('human-resources/employees/training-save')?>" method="post" autocomplete="off" enctype="multipart/form-data">
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
                                        <td colspan="2">Start Event</td>
                                        <td colspan="2">End Event</td>
                                        <td>Location</td>
                                        <td colspan="2">Cost</td>
                                        <td>Certificate*</td>
                                        <td width="10%">Category</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="hidden" class="form-control train-ID" name="trainingID[]">
                                        <input type="text" class="form-control train-name required" name="trainingTitle[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control train-organizer required" name="organizer[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control datepicker-tmp required train-start_event" id="datePicker-training" name="trainingStart[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td width="5%"><input type="text" class="form-control required train-start_time_event autocomplete" id="autocomplete-start-training" name="trainingStartTime[]" value="00:00">
                                        <small class="text-danger text-message"></small></td>

                                        <td><input type="text" class="form-control datepicker-sd required train-end_event" id="datePickerSD-training" name="trainingEnd[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td width="5%"><input type="text" class="form-control required train-end_time_event autocomplete" id="autocomplete-end-training" name="trainingEndTime[]" value="00:00">
                                        <small class="text-danger text-message"></small></td>
                                        
                                        <td><input type="text" class="form-control train-location required" name="trainingLocation[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control train-costCompany number" name="trainingCostCompany[]" placeholder="From company">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control train-costEmployee number required" name="trainingCostEmployee[]" placeholder="From employee">
                                        <small class="text-danger text-message"></small></td>
                                        <td><div class="fetch-file train-certificate"></div><input type="file" name="certificate[]"  accept="image/x-png,image/jpeg" ></td>
                                        <td><select name="trainingCategory[]" class="form-control required train-category">
                                            <option value="">Choose one</option>
                                            <option value="local">Local</option>
                                            <option value="holding">Holding</option>
                                            <option value="external">External</option>
                                        </select><small class="text-danger text-message"></small></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="12">*)Note:
                                        <ol><li>Maximum upload file is 2MB</li>
                                        <li>Only file image with extension JPG/JPEG/PNG allowed</li></ol></td>
                                    </tr>
                                </tfoot>
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
            		//var isHolding = false;
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

                            if(k == "start_event"){
                                var start_time = v.split(/ +/);
                                var get_time = start_time[1].split(/:/);
                                $cloneRow.find(".train-start_event").val(start_time[0]);
                                $cloneRow.find(".train-start_time_event").val(get_time[0]+":"+get_time[1]);
                            }
                            
                            if(k == "end_event"){
                                var end_time = v.split(/ +/);
                                var get_time = end_time[1].split(/:/);
                                $cloneRow.find(".train-end_event").val(end_time[0]);
                                $cloneRow.find(".train-end_time_event").val(get_time[0]+":"+get_time[1]);
                            }

                            if(k == "certificate"){
                                if(v == "" || v == null || $.trim(v).length == 0){
                                }else{
                                    $cloneRow.find(".train-certificate").html('<a href="'+base_url_js+'/uploads/profile/training/'+v+'"  target="_blank" class="btn btn-xs btn-primary" ><i class="fa fa-paperclip"></i> View file</a>');
                                }
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