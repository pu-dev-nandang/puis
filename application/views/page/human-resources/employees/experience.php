<script type="text/javascript">
	
    $(document).ready(function(){
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-experience").addClass("active");
        $("#datePicker-experience,#datePickerSD-experience").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });
        $("#select2-experience").select2({'width':'100%'});

        var companyTags = companyName();
	    $( "#autocomplete-experience" ).autocomplete({
	      source: companyTags
	    });

    });
</script>
<form id="form-experience" action="<?=base_url('human-resources/employees/experience-save')?>" method="post" autocomplete="off">
    <input type="hidden" name="NIP" value="<?=$NIP?>">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-edit"></i> Please fill up this form with correctly data</h4>
        </div>
        <div class="panel-body">
        	<div class="row">
        		<div class="col-sm-12">
        			<div class="panel panel-default" id="multiple-field" data-source="experience">
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
                            <h4 class="panel-title">Work Experience</h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered" id="table-list-experience">
                                <thead>
                                    <tr>
                                        <th width="2%">No</th>
                                        <th>Company Name</th>
                                        <th>Industries</th>
                                        <th>Strat Join</th>
                                        <th>End Join</th>
                                        <th>Job Title</th>
                                        <th>Reason Exit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="hidden" class="form-control exp-ID" name="comID[]">
                                        <input type="text" class="form-control required exp-company autocomplete" required name="comName[]" id="autocomplete-experience">
                                        <small class="text-danger text-message"></small></td>
                                        <td class="industries"><select class="select2-tmp exp-industryID" name="comIndustry[]" id="select2-experience">
                                                <option value="">Choose one</option>
                                                <?php if(!empty($industry)){
                                                foreach ($industry as $in) { 
                                                    echo '<option value="'.$in->ID.'">'.$in->name.'</option>';
                                                } } ?>
                                            </select>
                                            <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control exp-start_join datepicker-tmp required" required id="datePicker-experience" name="comStartJoin[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control exp-end_join datepicker-sd required" required id="datePickerSD-experience" name="comEndJoin[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control exp-jobTitle required" required name="comJobTitle[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control exp-reason required" required name="comReason[]">
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
		$("#form-experience .btn-submit").click(function(){
            var itsme = $(this);
            var itsform = itsme.parent().parent().parent();
            itsform.find(".required").each(function(){
                var value = $(this).val();
                if($.trim(value) == ''){
                    $(this).addClass("error");
                    $(this).parent().find(".text-message").text("Please fill this field");
                    error = false;
                    console.log($(this));
                }else{
                    error = true;
                    $(this).removeClass("error");
                    $(this).parent().find(".text-message").text("");
                }
            });
            
            var totalError = itsform.find(".error").length;
            if(error && totalError == 0 ){
                loading_modal_show();
                $("#form-experience")[0].submit();
            }else{
                alert("Please fill out the field.");
            }
        });


		var myData = fetchAdditionalData("<?=$NIP?>");
        if(!jQuery.isEmptyObject(myData)){
            if(!jQuery.isEmptyObject(myData.MyExperience)){
                $tablename = $("#table-list-experience"); var num = 1;
                $.each(myData.MyExperience,function(key,value){
                    $cloneRow = $tablename.find("tbody > tr:last").clone();
                    $cloneRow.attr("data-table","employees_experience").attr("data-id",value.ID).attr("data-name",value.company);
                    $cloneRow.find("td:first").text(num);
                    $.each(value,function(k,v){
                        $cloneRow.find(".exp-"+k).val(v);    
                        if(k=="industryID"){
                        	var cc = $cloneRow.find(".select2-tmp").attr("id","select2-experience-"+num);
                        	cc.prev().remove();
                        	cc.select2({width:'100%'});
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