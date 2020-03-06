<style type="text/css">.no-pad{padding: 0px;}</style>
<script type="text/javascript">
    $(document).ready(function(){
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-career").addClass("active");
        $("#datePicker-career,#datePickerSD-career,#datePicker-join,#datePickerSD-join").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });
        $("#select2-career,#select2SD-career").select2({width:'100%'});
        var employeeTags = employees();
        $( "#autocomplete-career" ).autocomplete({
          source: employeeTags
        });

        $("#form-additional-info .btn-submit").click(function(){
            var itsme = $(this);
            var itsform = itsme.parent().parent().parent();
            itsform.find(".select2-req").each(function(){
                var value = $(this).val();
                if($.isNumeric(value)){
                    if($.trim(value) == ''){
                        error = false;  
                        $(this).addClass("error");
                        $(this).parent().find(".text-message").text("Please fill this field");
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
                $("#form-additional-info")[0].submit();
            }else{
                alert("Please fill out the field.");
            }
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
                            //DeptID = v; 
                            
                            /*selectBoxDept = $cloneRow.find(".select2-term-sd").attr("id","select2SD-career-"+num);
                            selectBoxDept.addClass("no-pad");
                            selectBoxDept.prev().remove();
                            select2GetPosition(selectBoxDept,DeptID);*/
                        }
                        if(k == "PositionID"){
                            var cc = $cloneRow.find(".select2-sd").attr("id","select2SD-career-"+num);
                            cc.prev().remove();
                            cc.prop("disabled",false);
                            cc.select2({width:'100%'});
                            console.log("ehey");
                             
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


        $("#form-additional-info").on("change",".career-DepartmentID",function(){
            var itsme = $(this);
            var value = itsme.val();
            var position = itsme.parent().parent().find(".career-PositionID");
            if($.isNumeric(value)){
                if($.trim(value) != ""){
                    position.prop("disabled",false);
                }else{
                    position.prop("disabled",true);
                    position.select2("val","");
                }
            }else{
                position.prop("disabled",true);
                position.select2("val","");
            }
        });

        $("#form-additional-info").on("change",".career-StatusID",function(){
            var itsme = $(this);
            var value = itsme.val();
            var parent = itsme.parent().parent();
            if($.isNumeric(value)){
                if(value == 5){
                    parent.find(".career-EndJoin").removeClass("required");
                    parent.find(".career-EndJoin").removeClass("error");
                    parent.find(".career-EndJoin").next().text("");
                }else{
                    parent.find(".career-EndJoin").addClass("required");                    
                }
            }else{
                position.prop("disabled",true);
            }
        });

        $("#form-additional-info").on("change",".career-PositionID",function(){
            var itsme = $(this);
            var value = itsme.val();
            var parent = itsme.parent().parent();
            var dept = parent.find(".career-DepartmentID");
            if(!$.isNumeric(value)){
                dept.prop("disabled",true);
                dept.select2("val","");
            }else{
                dept.prop("disabled",false);
            }
        });

        <?php if(!empty($_GET['next'])){
        if($_GET['next'] == "Y"){ ?>
        if(!jQuery.isEmptyObject(myData)){
            var mailSplit = ((!jQuery.isEmptyObject(myData.EmailPU)) ? myData.EmailPU.split('@') : '');
            var Username = (!jQuery.isEmptyObject(mailSplit[0]) ? mailSplit[0]:'');

            var d = new Date(myData.DateOfBirth);
            var getMonth = '' + (d.getMonth() + 1);
            var getDay = '' + d.getDate();
            var getYear = d.getFullYear();

            if (getMonth.length < 2) getMonth = '0' + getMonth;
            if (getDay.length < 2) getDay = '0' + getDay;

            var UserPassword = getDay+getMonth+getYear.toString().substring(2);

            var html  = '<div class = "row">'+
                            '<div class = "col-md-12">'+
                                '<table class = "table">'+
                                    '<tr>'+
                                        '<td>'+
                                            'Username PC'+
                                        '</td>'+
                                        '<td>'+
                                            ':'+
                                        '</td>'+
                                        '<td>'+
                                            '<div class = "UsernamePC" dt = "'+Username+'">'+Username+
                                            '</div>'+
                                        '</td>'+
                                    '</tr>'+ 
                                    '<tr>'+
                                        '<td>'+
                                            'Username Aplikasi PCAM'+
                                        '</td>'+
                                        '<td>'+
                                            ':'+
                                        '</td>'+
                                        '<td>'+
                                            '<div class = "UsernamePCam" dt = "'+myData.NIP+'">'+myData.NIP+
                                            '</div>'+
                                        '</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>'+
                                            'Password'+
                                        '</td>'+
                                        '<td>'+
                                            ':'+
                                        '</td>'+
                                        '<td>'+
                                            '<div class = "PasswordFill" dt = "'+UserPassword+'">'+UserPassword+
                                            '</div>'+
                                        '</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>'+
                                            'Email PU'+
                                        '</td>'+
                                        '<td>'+
                                            ':'+
                                        '</td>'+
                                        '<td>'+
                                            '<div class = "EmailPUFill" dt = "'+myData.EmailPU+'">'+myData.EmailPU+
                                            '</div>'+
                                        '</td>'+
                                    '</tr>'+
                                '</table>'+
                            '</div>'+
                        '</div>';                           
            var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>'+
                         '<button type="button" id="ModalbtnSaveForm" class="btn btn-success">Print</button>';

            $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Akses'+'</h4>');
            $('#GlobalModalLarge .modal-body').html(html);
            $('#GlobalModalLarge .modal-footer').html(footer);
            $('#GlobalModalLarge').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        }

        $(document).off('click', '#ModalbtnSaveForm').on('click', '#ModalbtnSaveForm',function(e) {
            var UsernamePC = $('.UsernamePC').attr('dt'); 
            var UsernamePCam = $('.UsernamePCam').attr('dt');
            var PasswordFill = $('.PasswordFill').attr('dt');
            var EmailPUFill = $('.EmailPUFill').attr('dt');

            var url = base_url_js+'save2pdf/print_akses_karyawan';
            data = {
              UsernamePC : UsernamePC,
              UsernamePCam : UsernamePCam,
              PasswordFill : PasswordFill,
              EmailPUFill : EmailPUFill,
            }
            var token = jwt_encode(data,"UAP)(*");
            FormSubmitAuto(url, 'POST', [
                { name: 'token', value: token },
            ]);
        });
        <?php } } ?>
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
                            <h3 style="margin-top:0px ">Yayasan Pendidikan Agung Podomoro - Podomoro University</h3>
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
        								<th colspan="3">Start/Site Date</th>
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
        								<td>until</td>
                                        <td><input type="text" class="form-control required datepicker-sd career-EndJoin" id="datePickerSD-career" name="endJoin[]" placeholder="End Date">
        									<small class="text-danger text-message"></small></td>
        								<td><select class="form-control required career-LevelID" name="statusLevelID[]" required>
        									<option value="">Choose Level</option>
        									<?php if(!empty($level)){
        									foreach ($level as $l) {        										
        										echo '<option value="'.$l->ID.'">'.$l->name.'</option>';	
    										} } ?>
        								</select>
        								<small class="text-danger text-message"></small></td>
        								<td><select class="form-control select2-req no-pad select2-tmp career-DepartmentID" name="division[]" id="select2-career" required>
                                            <option>Choose one</option>
                                            <?php if(!empty($division)){ 
                                            foreach ($division as $d) { ?>
                                            <option value="<?=$d->ID?>"><?=$d->Division?></option>
                                            <?php } } ?>
                                        </select>
        								<small class="text-danger text-message"></small></td>
        								<td><select class="form-control select2-req no-pad select2-sd career-PositionID" name="position[]" id="select2SD-career" required disabled>
                                            <option>Choose one</option>
                                            <?php if(!empty($position)){ 
                                            foreach ($position as $d) { ?>
                                            <option value="<?=$d->ID?>"><?=$d->Position?></option>
                                            <?php } } ?>
                                        </select>
        								<small class="text-danger text-message"></small></td>
        								<td><input type="text" name="jobTitle[]" class="form-control required career-JobTitle" required></td>
        								<td><input type="text" name="superior[]" required class="form-control required career-Superior autocomplete" id="autocomplete-career"><small class="text-danger text-message"></small></td>
        								<td><select class="form-control required career-StatusID" name="statusID[]" required>
        									<option value="">Choose Status</option>
        									<?php if(!empty($status)){
        									foreach ($status as $ss) {
        										if($ss->ID != 1 && $ss->ID != 2){
        										echo '<option value="'.$ss->ID.'">'.$ss->name.'</option>';	
    										} } } ?>
        								</select>
        								<small class="text-danger text-message"></small></td>
        								<td><input type="text" class="form-control career-Remarks" name="remarks[]" placeholder="No SK" ></td>
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
