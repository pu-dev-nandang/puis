<style type="text/css">
	#biodata .left > .info > .detail > h5{text-transform: uppercase;font-weight: bold;text-align: center;}
	#biodata .left > .info > .profile-pic{width: 100%}
	#biodata .left > .info > .status > span{width: 100% }
	#biodata .right > .info{margin-bottom: 30px;}
	#biodata .right > .info > h2.name{margin-top: 0px}
	#biodata .right .ctn{margin-bottom: 20px}
	#biodata .right .ctn > .heading{
		background: #555555;
	    padding: 2px 10px;
	    color: #fff;
	    margin: 15px auto;
	    border-radius: 0px 10px;
	}
	#biodata .right .ctn > .content > .child:first-child{border-bottom: 1px solid #eee;margin-bottom: 10px}
</style>
<div id="detail-user">
	<div class="row">
		<div class="col-sm-12">
			<div class="act" style="margin-bottom:15px">
				<button class="btn btn-sm btn-warning" type="button" onClick="window.location.reload();" >
					<i class="fa fa-chevron-left"></i> Back to list
				</button>
			</div>
		</div>
		<div class="col-sm-12">
		<?php if(!empty($detail)){?>
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row" id="biodata">
						<div class="col-sm-2">
							<div class="left">
								<div class="info">
									<img class="profile-pic" src="<?=base_url().(!empty($detail->detailTA->Photo) ? 'uploads/students/ta_'.$detail->Year.'/'.$detail->detailTA->Photo:"images/icon/userfalse.png")?>">
									<p class="status"><span class="btn btn-<?=($detail->StatusStudentID != 1) ? (($detail->StatusStudentID == 3) ? 'info':'danger'):'primary'?>"><?=$detail->StatusStudent?></span></p>
									<div class="detail">
										<h5><?=$detail->Name." ",(($detail->StatusStudentID==1) ? $detail->ProdiTitle:"")?></h5>
										<p class="text"><i class="fa fa-birthday-cake"></i> <span><?=$detail->detailTA->PlaceOfBirth.", ".date("d F Y",strtotime($detail->detailTA->DateOfBirth))?></span></p>
										<p class="text"><i class="fa fa-<?=($detail->detailTA->Gender == 'P') ? 'female':'male'?>"></i> <?=($detail->detailTA->Gender == 'P') ? 'Female':'Male'?></p>
										<?php if(!empty($detail->detailTA->religionName)){ ?>
										<p class="text"><i class="fa fa-info-circle"></i> <?=$detail->detailTA->religionName?></p>
										<?php } ?>
										<?php if(!empty($detail->detailTA->nationalityName)){ ?>
										<p class="text"><i class="fa fa-flag"></i> <?=$detail->detailTA->nationalityName?></p>
										<?php } ?>
										<?php if(!empty($detail->detailTA->Address)){ ?>
										<p class="text"><i class="fa fa-map-marker"></i> 
											<span><?=$detail->detailTA->Address?></span><br>
											<span><?=$detail->detailTA->ProvinceName?></span>
										</p>
										<?php } ?>
										<?php if(!empty($detail->detailTA->Phone)){ ?>
										<p class="text"><i class="fa fa-phone"></i> <?=$detail->detailTA->Phone?></p>
										<?php } ?>
										<p class="text"><i class="fa fa-mobile"></i> <?=$detail->detailTA->HP?></p>
										<?php if(!empty($detail->detailTA->Email)){ ?>
										<p class="text"><i class="fa fa-envelope"></i> <?=$detail->detailTA->Email?></p>
										<?php } ?>
									</div>
								</div>
								<div class="middle"></div>
							</div>	
						</div>
						<div class="col-sm-10">
							<div class="right">
								<div class="info">
									<h2 class="name"><span class="nim"><?=$detail->NPM?></span>-<?=$detail->detailTA->Name?></h2>
									<h3 class="graduated"><?=$detail->ProdiDegree." (".$detail->ProdiNameEng.")"?></h3>
									<?php if($detail->StatusStudentID == 1){ ?>
									<h3 class="graduate-year">Graduated at <?=$detail->detailTA->GraduationYear?> from Agung Podomoro University</h3>
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
													<p class="col-sm-9"><?=$detail->detailTA->HighSchool?></p>
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
														<p class="col-sm-9"><?=$detail->detailTA->Father?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Occupation</label>
														<p class="col-sm-9"><?=$detail->detailTA->OccupationFather?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Education</label>
														<p class="col-sm-9"><?=$detail->detailTA->EducationFather?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Address</label>
														<p class="col-sm-9"><?=$detail->detailTA->AddressFather?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Contact</label>
														<p class="col-sm-9"><i class="fa fa-phone"></i> <?=substr($detail->detailTA->PhoneFather, 0, -3) . 'xxx'?>
														<?=(!empty($detail->detailTA->EmailFather) ? "<br><i class='fa fa-envelope'></i> ".$detail->detailTA->EmailFather:"")?></p>
													</div>
												</div>

												<div class="child">
													<p><label><i class="fa fa-user"></i> MOTHER</label></p>
													<div class="row">
														<label class="col-sm-3">Name</label>
														<p class="col-sm-9"><?=$detail->detailTA->Mother?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Occupation</label>
														<p class="col-sm-9"><?=$detail->detailTA->OccupationMother?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Education</label>
														<p class="col-sm-9"><?=$detail->detailTA->EducationMother?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Address</label>
														<p class="col-sm-9"><?=$detail->detailTA->AddressMother?></p>
													</div>
													<div class="row">
														<label class="col-sm-3">Contact</label>
														<p class="col-sm-9"><i class="fa fa-phone"></i> <?=substr($detail->detailTA->PhoneMother, 0, -3) . 'xxx'?>
														<?=(!empty($detail->detailTA->EmailMother) ? "<br><i class='fa fa-envelope'></i> ".$detail->detailTA->EmailMother:"")?></p>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="ctn academic">
											<div class="heading">
												<h4><i class="fa fa-bookmark"></i> Academic</h4>
											</div>
											<div class="content">
												<div class="row">
													<label class="col-sm-3">Program</label>
													<p class="col-sm-4"><?=$detail->ProdiEdu."-".$detail->ProdiDegree?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">Study Program</label>
													<p class="col-sm-9"><?=$detail->ProdiNameEng?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">Year Intake</label>
													<p class="col-sm-4"><?=$detail->Year?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">NIM</label>
													<p class="col-sm-9"><?=$detail->NPM?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">Email PU</label>
													<p class="col-sm-9"><?=$detail->EmailPU?></p>
												</div>												
												<div class="row">
													<label class="col-sm-3">Status</label>
													<p class="col-sm-4"><?=$detail->StatusStudent?></p>
												</div>
												<?php if($detail->StatusStudentID == 1){ ?>
												<div class="row">
													<label class="col-sm-3">Yudisium Date</label>
													<p class="col-sm-4"><?=date("d F Y",strtotime($detail->YudisiumDate))?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">Graduation Date</label>
													<p class="col-sm-4"><?=date("d F Y",strtotime($detail->GraduationDate))?></p>
												</div>
												<?php } ?>
												<?php if(!empty($detail->MentorNIP)){ ?>
												<div class="row">
													<label class="col-sm-3">Mentor</label>
													<p class="col-sm-9"><?='<i class="fa fa-id-card"></i> '.$detail->MentorNIP.'<br><i class="fa fa-user"></i> '.$detail->Mentor.'<br><i class="fa fa-envelope"></i> '.$detail->MentorEmailPU?></p>
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
			
		<?php }else{echo "<h1 class='text-center'>Data is not founded.</h1>";} ?>
		</div>
	</div>
</div>	