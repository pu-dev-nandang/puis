<style type="text/css">
	#biodata .left > .info > .detail > h5{text-transform: uppercase;font-weight: bold;text-align: center;}
	#biodata .left > .info > .profile-pic{width: 100%}
	#biodata .left > .info > .status > span{width: 100% }
	#biodata .right > .info{margin-bottom: 30px;}
	#biodata .right > .info > h2.name{margin-top: 0px}
	#biodata .right .ctn{margin-bottom: 30px}
	#biodata .right .ctn > .heading{
		background: #555555;
	    padding: 2px 10px;
	    color: #fff;	    
	    border-radius: 0px 10px;
	}
	#biodata .right .ctn > .content > .child:first-child{border-bottom: 1px solid #eee;margin-bottom: 10px}
	#biodata .right .ctn > .content{padding: 20px 20px;border:1px solid #555555;margin-top: -10px;border-radius: 0px 0px 10px 10px}
</style>
<div id="detail-user">
	<div class="row">
		<div class="col-sm-12">
			<div class="act" style="margin-bottom:15px">
				<button class="btn btn-sm btn-warning go-back" type="button" >
					<i class="fa fa-chevron-left"></i> Back to list
				</button>
			</div>
		</div>
		<div class="col-sm-12">
		<?php if(!empty($detail)){?>
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row" id="biodata">
						<div class="col-sm-3 col-md-3">
							<div class="left">
								<div class="info">
									<img class="profile-pic" src="<?=$profilepic?>">
									<p class="status"><span class="btn btn-<?=($detail->StatusStudentID != 1) ? (($detail->StatusStudentID == 3) ? 'info':'danger'):'primary'?>"><?=$detail->StatusStudent?></span></p>
									<div class="detail">
										<h5><?=$detail->Name." ",(($detail->StatusStudentID==1) ? $detail->ProdiTitle:"")?></h5>
										<p class="text"><i class="fa fa-birthday-cake"></i> <span><?=$detail->PlaceOfBirth.", ".date("d F Y",strtotime($detail->DateOfBirth))?></span></p>
										<p class="text"><i class="fa fa-<?=($detail->Gender == 'P') ? 'female':'male'?>"></i> <?=($detail->Gender == 'P') ? 'Female':'Male'?></p>
										<?php if(!empty($detail->religionName)){ ?>
										<p class="text"><i class="fa fa-info-circle"></i> <?=$detail->religionName?></p>
										<?php } ?>
										<?php if(!empty($detail->nationalityName)){ ?>
										<p class="text"><i class="fa fa-flag"></i> <?=$detail->nationalityName?></p>
										<?php } ?>
										<?php if(!empty($detail->Address)){ ?>
										<p class="text"><i class="fa fa-map-marker"></i> 
											<span><?=$detail->Address?></span>
										</p>
										<?php } ?>
										<?php if(!empty($detail->Phone)){ ?>
										<p class="text"><i class="fa fa-phone"></i> <?=(substr($detail->Phone, 0, -3) . 'xxx')?></p>
										<?php } ?>
										<p class="text"><i class="fa fa-mobile"></i> <?=(substr($detail->HP, 0, -3) . 'xxx')?></p>
										<?php if(!empty($detail->Email)){ ?>
										<p class="text"><i class="fa fa-envelope"></i> <?=$detail->Email?></p>
										<?php } ?>
									</div>
								</div>
								<div class="middle"></div>
							</div>	
						</div>
						<div class="col-sm-9 col-md-9">
							<div class="right">
								<div class="info">
									<h2 class="name"><span class="nim"><?=$detail->NPM?></span>-<?=$detail->Name?></h2>
									<h3 class="graduated"><?=$detail->ProdiDegree." (".$detail->ProdiNameEng.")"?></h3>
									<?php if($detail->StatusStudentID == 1){ ?>
									<h3 class="graduate-year">Graduated at <?=$detail->GraduationYear?> from Agung Podomoro University</h3>
									<?php } ?>
									<p class="email"><?=$detail->EmailPU?></p>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="ctn historical">
											<div class="heading">
												<h4><i class="fa fa-graduation-cap"></i> Historical Education</h4>
											</div>
											<div class="content">
												<div class="row">
													<label class="col-sm-3">High School</label>
													<p class="col-sm-9"><?=$detail->HighSchool?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">Graduation Year</label>
													<p class="col-sm-4">-</p>
												</div>

											</div>
										</div>

										<div class="ctn parents">
											<div class="heading">
												<h4><i class="fa fa-users"></i> Student Parents</h4>
											</div>
											<div class="content">
												<div class="child">
													<p><label><i class="fa fa-user"></i> FATHER</label></p>
													<div class="row">
														<label class="col-sm-3">Name</label>
														<p class="col-sm-9"><?=$detail->Father?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Occupation</label>
														<p class="col-sm-9"><?=$detail->OccupationFather?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Education</label>
														<p class="col-sm-9"><?=$detail->EducationFather?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Address</label>
														<p class="col-sm-9"><?=$detail->AddressFather?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Email</label>
														<p class="col-sm-9"><?=(!empty($detail->EmailFather) ? $detail->EmailFather : '-')?></p>
													</div>													
													<div class="row">
														<label class="col-sm-3">Contact</label>
														<p class="col-sm-9"><?=(!empty($detail->PhoneFather) ? (substr($detail->PhoneFather, 0, -3) . 'xxx') : '-')?>
													</div>
												</div>

												<div class="child">
													<p><label><i class="fa fa-user"></i> MOTHER</label></p>
													<div class="row">
														<label class="col-sm-3">Name</label>
														<p class="col-sm-9"><?=$detail->Mother?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Occupation</label>
														<p class="col-sm-9"><?=$detail->OccupationMother?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Education</label>
														<p class="col-sm-9"><?=$detail->EducationMother?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Address</label>
														<p class="col-sm-9"><?=$detail->AddressMother?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Email</label>
														<p class="col-sm-9"><?=(!empty($detail->EmailMother) ? $detail->EmailMother:'-')?></p>
													</div>

													<div class="row">
														<label class="col-sm-3">Contact</label>
														<p class="col-sm-9"><?=(!empty($detail->PhoneMother) ? (substr($detail->PhoneMother, 0, -3) . 'xxx') : "-")?>
													</div>													
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="ctn insurance">
											<div class="heading">
												<h4><i class="fa fa-medkit"></i> Health Insurance</h4>
											</div>
											<div class="content">
												<div class="row">
													<label class="col-sm-4">Insurance Company</label>
													<p class="col-sm-8"><?=(!empty($detail->InsuranceName) ? $detail->InsuranceName : '-')?></p>
												</div>
												<div class="row">
													<label class="col-sm-4">Insurance Policy Number</label>
													<p class="col-sm-8"><?=(!empty($detail->InsurancePolicy) ? $detail->InsurancePolicy : '-')?></p>
												</div>
												<div class="row">
													<label class="col-sm-4">Effective from</label>
													<p class="col-sm-8"><?=((!empty($detail->InsuranceEffectiveStart) && !empty($detail->InsuranceEffectiveEnd)) ? date("d M Y",strtotime($detail->InsuranceEffectiveStart))." <label>until</label> ".date("d M Y",strtotime($detail->InsuranceEffectiveEnd)) : '-')?></p>
												</div>


											</div>
										</div>
										<div class="ctn academic">
											<div class="heading">
												<h4><i class="fa fa-bookmark"></i> Academic</h4>
											</div>
											<div class="content">
												<div class="row">
													<label class="col-sm-4">Program</label>
													<p class="col-sm-8"><?=$detail->ProdiEdu."-".$detail->ProdiDegree?></p>
												</div>
												<div class="row">
													<label class="col-sm-4">Study Program</label>
													<p class="col-sm-8"><?=$detail->ProdiNameEng?></p>
												</div>
												<div class="row">
													<label class="col-sm-4">Year Intake</label>
													<p class="col-sm-8"><?=$detail->ClassOf?></p>
												</div>
												<div class="row">
													<label class="col-sm-4">NIM</label>
													<p class="col-sm-8"><?=$detail->NPM?></p>
												</div>
												<div class="row">
													<label class="col-sm-4">Email PU</label>
													<p class="col-sm-8"><?=$detail->EmailPU?></p>
												</div>												
												<div class="row">
													<label class="col-sm-4">Status</label>
													<p class="col-sm-8"><?=$detail->StatusStudent?></p>
												</div>
												<?php if($detail->StatusStudentID == 1){ ?>
												<div class="row">
													<label class="col-sm-4">Yudisium Date</label>
													<p class="col-sm-8"><?=date("d F Y",strtotime($detail->YudisiumDate))?></p>
												</div>
												<div class="row">
													<label class="col-sm-4">Graduation Date</label>
													<p class="col-sm-8"><?=date("d F Y",strtotime($detail->GraduationDate))?></p>
												</div>
												<?php }else{ ?>
												<div class="row">
													<label class="col-sm-4">Current Semester</label>
													<p class="col-sm-8 fetch-score semes"><i class="fa fa-circle-o-notch fa-spin"></i></p>
												</div>
												<?php } ?>
												<!-- <div class="row">
													<label class="col-sm-4">IPS</label>
													<p class="col-sm-8 fetch-score ips"><i class="fa fa-circle-o-notch fa-spin"></i></p>
												</div> -->
												<div class="row">
													<label class="col-sm-4">IPK</label>
													<p class="col-sm-8 fetch-score ipk"><i class="fa fa-circle-o-notch fa-spin"></i></p>
												</div>

												<?php if(!empty($detail->MentorNIP)){ ?>
												<div class="row">
													<label class="col-sm-4">Mentor</label>
													<p class="col-sm-8"><?='<i class="fa fa-id-card"></i> '.$detail->MentorNIP.'<br><i class="fa fa-user"></i> '.$detail->Mentor.'<br><i class="fa fa-envelope"></i> '.(!empty($detail->MentorEmailPU) ? $detail->MentorEmailPU:'-')?></p>
												</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>

			<script type="text/javascript">
			$(document).ready(function(){
				function fetchScoreStudent() {
					var NPM = "<?=$detail->NPM?>";
					var data = {
		              NPM : NPM,
		          	};
		          	var token = jwt_encode(data,'UAP)(*');
					$.ajax({
			            type : 'POST',
			            url : base_url_js+"global-informations/fetchStudentScore",
			            data : {token:token},
			            dataType : 'json',
			            error : function(jqXHR){
			                $("body #GlobalModal .modal-header").html("<h1>Error notification</h1>");
			                $("body #GlobalModal .modal-body").html(jqXHR.responseText);
			                $("body #GlobalModal").modal("show");
			            },success : function(response){
			            	if(jQuery.isEmptyObject(response)){
			            		$(".fetch-score").text(0);
			            	}else{
				            	$(".fetch-score.semes").text(response.LastSemester);
				            	$(".fetch-score.ips").text(0);
				            	$(".fetch-score.ipk").text(response.dataIPK.IPK);
			            	}
			            }
			        });
				}
				fetchScoreStudent();
			});
			</script>
			
		<?php }else{echo "<h1 class='text-center'>Data is not founded.</h1>";} ?>
		</div>
	</div>
</div>	

<script type="text/javascript">
	$(document).ready(function(){
		$("body #detail-user .go-back").click(function(){
			$("#student-list").removeClass("hidden");
	    	$("#student-detail").empty();
		});
	});
</script>