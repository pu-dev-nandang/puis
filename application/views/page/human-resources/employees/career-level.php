<style type="text/css">.no-pad{padding: 0px;}</style>
<script type="text/javascript">
	function select2GetDivision($element) {
        $element.select2({
            ajax: { 
                url: base_url_js+'human-resources/master-aphris/fetch-division',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (term,status) {
                  return {
                    term: term
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
            },width : '100%'
        }).on('change', function(){
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

    function select2GetPosition($element,$parentID) {
		$element.select2({
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
            }
        });
        $element.prop("disabled",false);
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
                    alert("Data not founded. Try again.");
                }else{
                    console.log($element);
                    $element.val(response.NIP+"/"+response.Name);
                }
            }
        });
    }


    function testAja(num) {
        console.log(num);
        return "result:"+num; 
    }

    $(document).ready(function(){
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-career").addClass("active");
        $("#datePicker-career,#datePickerSD-career").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });
        select2GetDivision($("#select2-term-ft-career"));

        $("#form-additional-info .btn-submit").click(function(){
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
                $("#form-additional-info")[0].submit();
            }else{
                alert("Please fill out the field.");
            }
        });
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
        								<td><input type="hidden" class="form-control required" required name="careerID[]">
        									<input type="text" class="form-control required datepicker-tmp" id="datePicker-career" required name="startJoin[]" placeholder="Start Date" >
        									<small class="text-danger text-message"></small></td>
        								<td><input type="text" class="form-control required datepicker-sd" id="datePickerSD-career" required name="endJoin[]" placeholder="End Date">
        									<small class="text-danger text-message"></small></td>
        								<td><select class="form-control required" name="statusLevelID[]" required>
        									<option value="">Choose Level</option>
        									<?php if(!empty($status)){
        									foreach ($status as $s) {        										
        										echo '<option value="'.$s->ID.'">'.$s->name.'</option>';	
    										} } ?>
        								</select>
        								<small class="text-danger text-message"></small></td>
        								<td><input type="text" class="form-control no-pad required select2-term-ft" id="select2-term-ft-career">                                        
        								<small class="text-danger text-message"></small></td>
        								<td><input type="text" class="form-control required select2-term-sd" id="select2-term-sd-career">

        								<small class="text-danger text-message"></small></td>
        								<td><input type="text" name="jobTitle[]" class="form-control required" required></td>
        								<td><input type="text" name="superior[]" readonly class="form-control required" id="superior" required><small class="text-danger text-message"></small></td>
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
            <button class="btn btn-success btn-submit" type="button">Save changes</button>
        </div>
    </div>
</form>