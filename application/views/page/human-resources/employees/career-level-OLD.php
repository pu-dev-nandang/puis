<!-- CAREER LEVEL RELATED BY STO -->
<style type="text/css">.no-pad{padding: 0px;}</style>
<script type="text/javascript">
	function select2GetDivision($element) {
        $element.select2({width : '100%'})
        .on('change', function(){
          var itsme = $(this);
          var ID = itsme.val();
          //ambil superior/headnya
          $superior = itsme.parent().parent().find("#superior");
          getSuperior(ID,$superior);
          $positionSelect2 = itsme.parent().next().find(".select2-term-sd");
          $positionSelect2.addClass("no-pad");
          select2GetPosition($positionSelect2,ID);
        });
    }

    function select2GetPosition($element,$parentID,$value=0,$valueName="") {
		var selectBox = $element.select2({
            ajax: { 
                url: base_url_js+'human-resources/master-aphris/fetch-position',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (term,status) {
                  return {
                    term: term,
                    id : $parentID
                  };
                },
                results: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.title,
                                slug: item.description,
                                id: item.ID
                            }
                        })
                    };
                },
               cache: true
            },width:"100%"
        });
        if($value != 0 && $.trim($valueName).length > 0){
            selectBox.select2('data', {id: $value, text: $valueName});            
        }
        selectBox.prop("disabled",false);
	}


    function getSuperior(ID,$element) {
        var data = {
          ID : ID
        };
        var token = jwt_encode(data,'UAP)(*');
        $.ajax({
            type : 'POST',
            url : base_url_js+"human-resources/master-aphris/get-superior",
            data : {token:token},
            dataType : 'json',
            beforeSend :function(){loading_modal_show()},
            error : function(jqXHR){
                loading_modal_hide();
                $("body #GlobalModal .modal-body").html(jqXHR.responseText);
                $("body #GlobalModal").modal("show");
            },success : function(response){
                loading_modal_hide();
                if(jQuery.isEmptyObject(response)){
                    alert("Superior is EMPTY, please fill the superior name on Stucture Organization.");
                }else{
                    $element.val(response.NIP+"/"+response.Name);
                }
            }
        });
    }

    $(document).ready(function(){
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-career").addClass("active");
        $("#datePicker-career,#datePickerSD-career,#datePicker-join,#datePickerSD-join").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });
        $("#select2-career").select2({width:'100%'});
        //select2GetDivision($("#select2-career"));

        $("#form-additional-info .btn-submit").click(function(){
            var itsme = $(this);
            var itsform = itsme.parent().parent().parent();
            itsform.find(".select2-req").each(function(){
                var value = $(this).val();
                if($.trim(value) == ''){
                    $(this).parent().find(".text-message").text("Please fill this field");
                    error = false;  
                }else{
                    error = true;
                    $(this).parent().find(".text-message").text("");
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
                $("#form-additional-info")[0].submit();
            }else{
                alert("Please fill out the field.");
            }
        });

        
        $("#form-additional-info").on("change",".select2-tmp",function(){
            var itsme = $(this);
            var ID = itsme.val();

            $superior = itsme.parent().parent().find("#superior");
            getSuperior(ID,$superior);
            $positionSelect2 = itsme.parent().next().find(".select2-term-sd");
            $positionSelect2.addClass("no-pad");
            select2GetPosition($positionSelect2,ID);
        });

        var myData = fetchAdditionalData("<?=$NIP?>");
        if(!jQuery.isEmptyObject(myData)){
            if(!jQuery.isEmptyObject(myData.MyCareer)){
                $tablename = $("#table-list-career"); var num = 1;
                $.each(myData.MyCareer,function(key,value){
                    $cloneRow = $tablename.find("tbody > tr:last").clone();
                    $cloneRow.attr("data-table","employees_career").attr("data-id",value.ID).attr("data-name",value.JobTitle);
                    $cloneRow.find("td:first").text(num);
                    var DeptID = 0; var selectBoxDept = ""; var PositionID = 0;
                    $.each(value,function(k,v){
                        $cloneRow.find(".career-"+k).val(v);    
                        if(k == "StartJoin"){
                            var cc = $cloneRow.find(".datepicker-tmp").attr("id","datePicker-career-"+num).removeClass("hasDatepicker");
                            cc.datepicker({
                                dateFormat: 'yy-mm-dd',
                                changeYear: true,
                                changeMonth: true
                            });
                        }  
                        if(k == "EndJoin"){
                            var cc = $cloneRow.find(".datepicker-sd").attr("id","datePickerSD-career-"+num).removeClass("hasDatepicker");
                            cc.datepicker({
                                dateFormat: 'yy-mm-dd',
                                changeYear: true,
                                changeMonth: true
                            });
                        }  
                        if(k == "DepartmentID"){
                            var cc = $cloneRow.find(".select2-tmp").attr("id","select2-career-"+num);
                            cc.prev().remove();
                            cc.select2({width:'100%'});
                            DeptID = v; 
                            
                            selectBoxDept = $cloneRow.find(".select2-term-sd").attr("id","select2SD-career-"+num);
                            selectBoxDept.addClass("no-pad");
                            selectBoxDept.prev().remove();
                            select2GetPosition(selectBoxDept,DeptID);
                        }
                        if(k == "PositionID"){
                            PositionID = v;
                        }
                        if(k == "PositionName"){
                            select2GetPosition(selectBoxDept,DeptID,PositionID,v);
                        }
                    });
                    
                    $tablename.find("tbody").append($cloneRow);
                    num++;
                });
                $tablename.find("tbody tr:first").remove();
            }

            if(!jQuery.isEmptyObject(myData.MyHistorical)){
                $tablename = $("#table-list-join"); var num = 1;
                $.each(myData.MyHistorical,function(key,value){
                    $cloneRow = $tablename.find("tbody > tr:last").clone();
                    $cloneRow.attr("data-table","employees_joindate").attr("data-id",value.ID).attr("data-name",value.JobTitle);
                    $cloneRow.find("td:first").text(num);
                    var DeptID = 0; var selectBoxDept = ""; var PositionID = 0;
                    $.each(value,function(k,v){
                        $cloneRow.find(".join-"+k).val(v);
                        if(k == "JoinDate"){
                            var cc = $cloneRow.find(".datepicker-tmp").attr("id","datePicker-join-"+num).removeClass("hasDatepicker");
                            cc.datepicker({
                                dateFormat: 'yy-mm-dd',
                                changeYear: true,
                                changeMonth: true
                            });
                        } 
                        if(k == "ResignDate"){
                            var cc = $cloneRow.find(".datepicker-sd").attr("id","datePickerSD-join-"+num).removeClass("hasDatepicker");
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

        $("#form-additional-info").on("change",".join-StatusEmployeeID",function(){
            var itsme = $(this);
            var value = itsme.val();
            var resignField = itsme.parent().parent().find(".join-ResignDate");
            if($.trim(value) == '-1'){
                resignField.addClass("required");
            }else{
                resignField.removeClass("required");
                resignField.removeClass("error");
                resignField.next().text("");
            }
        });
    });
</script>
<form id="form-additional-info" action="<?=base_url('human-resources/employees/career-level-save')?>" method="post" autocomplete="off">
    <input type="hidden" name="NIP" value="<?=$NIP?>">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-edit"></i> Please fill up this form with correctly data</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default" id="multiple-field" data-source="join">
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
                                Historical of Employment Status
                            </h4>
                        </div>
                        <div class="panel-body">
                            <h3 style="margin-top:0px "><?=(!empty($currComp) ? $currComp->Name: '')?></h3>
                            <table class="table table-bordered" id="table-list-join">
                                <thead>
                                    <tr>                                        
                                        <th width="2%">No</th>
                                        <th>Join Date</th>
                                        <th>Resign Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>
                                        <input type="hidden" class="form-control join-ID" name="joinID[]">
                                        <input type="text" name="JoinDate[]" class="form-control required datepicker-tmp join-JoinDate" required id="datePicker-join">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" name="ResignDate[]" class="form-control datepicker-sd join-ResignDate" id="datePickerSD-join">
                                        <small class="text-danger text-message"></small></td>
                                        <td><select class="form-control required join-StatusEmployeeID" required name="StatusEmployeeID[]">
                                            <option value="">Choose one</option>
                                            <?php if(!empty($employees_status)){
                                            foreach ($employees_status as $e) {
                                                echo '<option value="'.$e->IDStatus.'" >'.$e->Description.'</option>';                                                
                                             } } ?>
                                        </select></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
        								<th colspan="2">Start/Site Date</th>
        								<th>Level</th>
        								<th width="10%">Dept</th>
        								<th width="10%">Position</th>
        								<th width="10%">Job Title</th>
        								<th width="10%">Superior</th>
        								<th>Status</th>
        								<th>Remarks</th>
        							</tr>
        						</thead>
        						<tbody>
        							<tr>
        								<td>1</td>
        								<td><input type="hidden" class="form-control career-ID" name="careerID[]" >
        									<input type="text" class="form-control required datepicker-tmp career-StartJoin" id="datePicker-career" required name="startJoin[]" placeholder="Start Date" >
        									<small class="text-danger text-message"></small></td>
        								<td><input type="text" class="form-control datepicker-sd career-EndJoin" id="datePickerSD-career" name="endJoin[]" placeholder="End Date">
        									<small class="text-danger text-message"></small></td>
        								<td><select class="form-control required career-LevelID" name="statusLevelID[]" required>
        									<option value="">Choose Level</option>
        									<?php if(!empty($level)){
        									foreach ($level as $l) {        										
        										echo '<option value="'.$l->ID.'">'.$l->name.'</option>';	
    										} } ?>
        								</select>
        								<small class="text-danger text-message"></small></td>
        								<td>
                                        <select class="form-control select2-req no-pad select2-tmp career-DepartmentID" name="division[]" id="select2-career" required>
                                            <option>Choose one</option>
                                            <?php if(!empty($division)){ 
                                            foreach ($division as $d) { ?>
                                            <option value="<?=$d->ID?>"><?=$d->title?></option>
                                            <?php } } ?>
                                        </select>
        								<small class="text-danger text-message"></small></td>
        								<td><input type="text" class="form-control select2-term-sd position select2-req career-PositionID" id="select2-term-sd-career" name="position[]">
        								<small class="text-danger text-message"></small></td>
        								<td><input type="text" name="jobTitle[]" class="form-control required career-JobTitle" required></td>
        								<td><input type="text" name="superior[]" readonly class="form-control required career-Superior" id="superior" required><small class="text-danger text-message"></small></td>
        								<td><select class="form-control required career-StatusID" name="statusID[]" required>
        									<option value="">Choose Status</option>
        									<?php if(!empty($status)){
        									foreach ($status as $ss) {
        										if($ss->ID != 1 && $ss->ID != 2){
        										echo '<option value="'.$ss->ID.'">'.$ss->name.'</option>';	
    										} } } ?>
        								</select>
        								<small class="text-danger text-message"></small></td>
        								<td><input type="text" class="form-control career-Remarks" name="remarks[]" ></td>
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