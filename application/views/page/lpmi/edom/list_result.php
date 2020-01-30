<?php $message = $this->session->flashdata('message');
	if(!empty($message)){ ?>
	<script type="text/javascript">
	$(document).ready(function(){
		toastr.warning("<?= $this->session->flashdata('message');?>",'Info!');
	});
	</script>
<?php } ?>
<style type="text/css">#edom-list-result{width:50%;margin:0 auto;}.error{border:1px solid red;}</style>
<div id="edom-list-result">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">Filter EDOM Results</h4>
				</div>
				<div class="panel-body">
					<form id="form-edom-results" action="<?=site_url('lpmi/lecturer-evaluation/request-edom')?>" method="post">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label>Study Program</label>
									<select class="form-control required" required id="filterProdi" name="prodi">
										<option value="">Choose one</option>
									</select>
									<small class="text-danger text-message"></small>
								</div>
							</div>
							
							<div class="col-sm-4">
								<div class="form-group">
									<label>Year Intake</label>
									<select class="form-control required" required name="intake">
										<option value="">Choose one</option>
										<?php if(!empty($semester)){ $intake ="";
										foreach ($semester as $s) {	
											if($s->Year != $intake){	
												echo "<option value='".$s->Year."'>".$s->Year."</option>";
											}
											$intake = $s->Year;
										} } ?>
									</select>
									<small class="text-danger text-message"></small>
								</div>
							</div>


							<div class="col-sm-4">
								<div class="form-group">
									<label>Semester</label>
									<select class="form-control required" required id="filterSemester" name="semester">
										<option value="">Choose one</option>
									</select>
									<small class="text-danger text-message"></small>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-sm-12 text-center">
								<button class="btn btn-sm btn-info btn-download" type="button"><i class="fa fa-download"></i> Download File</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
        loSelectOptionSemester('#filterSemester','');
        loadSelectOptionBaseProdi('#filterProdi','');

        $("#form-edom-results").on("click",".btn-download",function(){
        	var error = false;
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
		  		$("#form-edom-results")[0].submit();
		  	}else{
		  		alert("Please fill out the field.");
		  	}

          	
        });
    });
</script>