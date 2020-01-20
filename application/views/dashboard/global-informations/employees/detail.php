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
									<img class="profile-pic" src="<?=$profilePIC?>">
									<p class="status"><span class="btn btn-<?=($detail->StatusLecturerID == '-1' || empty($detail->StatusEmployeeID)) ? 'danger':'info'?>"><?=(!empty($detail->StatusEmployeeID) ? $detail->EmpStatus : "Non Active")?></span></p>
								</div>
								<div class="middle"></div>
							</div>	
						</div>
						<div class="col-sm-10">
							<div class="right">
								<div class="info">
									<h2 class="name"><?=$detail->NIP."-".(!empty($detail->TitleAhead) ? $detail->TitleAhead." ":"").$detail->Name.(!empty($detail->TitleBehind) ? " ".$detail->TitleBehind:"")?></h2>
									<h3 class="prodi"><?=$detail->EmpStatus?></h3>
									<p class="email"><?=$detail->EmailPU?></p>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="ctn historical">
											<div class="heading">
												<h4><i class="fa fa-user"></i> Personal details</h4>
											</div>
											<div class="content">
												<div class="row">
													<label class="col-sm-3">Fullname</label>
													<p class="col-sm-9"><?=$detail->Name?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">Level of Education</label>
													<p class="col-sm-9"><?=$detail->EmpLevelEduName."-".$detail->EmpLevelDesc?></p>
												</div>												
												<div class="row">
													<label class="col-sm-3">Birthdate</label>
													<p class="col-sm-9"><?=$detail->PlaceOfBirth.", ".date("d F Y",strtotime($detail->DateOfBirth))?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">Gender</label>
													<p class="col-sm-9"><?=($detail->Gender == "P") ? "Female":"Male"?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">Religion</label>
													<p class="col-sm-9"><?=$detail->EmpReligion?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">Address</label>
													<p class="col-sm-9"><?=$detail->Address?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">Phone</label>
													<p class="col-sm-9"><?=(!empty($detail->Phone) ? (substr($detail->Phone, 0, -3).'xxx') : '-')?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">Mobile Phone</label>
													<p class="col-sm-9"><?=(!empty($detail->HP) ? substr($detail->HP, 0, -3) . 'xxx' : '-')?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">Email</label>
													<p class="col-sm-9"><?=(!empty($detail->Email) ? $detail->Email : '-')?></p>
												</div>
											</div>
										</div>

									</div>
									<div class="col-sm-6">
										<div class="ctn academic">
											<div class="heading">
												<h4><i class="fa fa-bookmark"></i> Employee Details</h4>
											</div>
											<div class="content">
												<div class="row">
													<label class="col-sm-3">NIP</label>
													<p class="col-sm-9"><?=$detail->NIP?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">Email PU</label>
													<p class="col-sm-9"><?=$detail->EmailPU?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">Status</label>
													<p class="col-sm-9"><?=$detail->EmpStatus?></p>
												</div>
												<div class="row">
													<label class="col-sm-3">Position Main</label>
													<p class="col-sm-9"><?=$detail->DivisionMain."-".$detail->PositionMain?></p>
												</div>	
												<?php if(!empty($detail->PositionOther1)){ ?>											
												<div class="row">
													<label class="col-sm-12">Other Positions :</label>
												</div>
												<div class="row">
													<label class="col-sm-3"><sup>1</sup> Position</label>
													<p class="col-sm-9"><?=$othPositionDiv1->Division."-".$othPosition1->Description?></p>
												</div>
												<?php } ?>
												<?php if(!empty($detail->PositionOther2)){ ?>
												<div class="row">
													<label class="col-sm-3"><sup>2</sup> Position</label>
													<p class="col-sm-9"><?=$othPositionDiv2->Division."-".$othPosition2->Description?></p>
												</div>
												<?php } ?>
												<?php if(!empty($detail->PositionOther3)){ ?>
												<div class="row">
													<label class="col-sm-3"><sup>3</sup> Position</label>
													<p class="col-sm-9"><?=$othPositionDiv3->Division."-".$othPosition3->Description?></p>
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