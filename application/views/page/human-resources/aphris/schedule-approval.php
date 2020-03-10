<?php $message = $this->session->flashdata('message');
	if(!empty($message)){ ?>
	<script type="text/javascript">
	$(document).ready(function(){
		toastr.info("<?= $this->session->flashdata('message');?>",'Info!');
	});
	</script>
<?php } ?>

<div id="schedule-approval">
	<div class="container" style="width:40%">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title"><i class="fa fa-calendar-check-o"></i> Schedule Approval</h4>
			</div>
			<div class="panel-body">
				<form action="<?=base_url('human-resources/master-aphris/schedule-approval-save')?>" method="post" id="form-schedule" autocomplete="off">  
				    <input type="hidden" name="ID" value="<?=(!empty($result) ? $result->ID : null)?>">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
							    <label>Start Date</label>
							    <input type="text" class="form-control required" name="StartDate" id="StartDate" value="<?=(!empty($result) ? $result->StartDate : null)?>">
							    <small class="text-danger text-message"></small>
						  	</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
							    <label>End Date</label>
							    <input type="text" class="form-control required" name="EndDate" id="EndDate" value="<?=(!empty($result) ? $result->EndDate : null)?>">
							    <small class="text-danger text-message"></small>
						  	</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 text-left">
					  		<button type="button" class="btn btn-success btn-save">Save changes</button>							
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#StartDate,#EndDate").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });

        $("#form-schedule .btn-save").click(function(){
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
                itsme.prop("disabled",true).html('<i class="fa fa-refresh fa-spin"></i>');;

                $("#form-schedule")[0].submit();
            }else{
                alert("Please fill out the field.");
            }
        });
	});
</script>