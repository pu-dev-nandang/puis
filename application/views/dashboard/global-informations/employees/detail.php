<style type="text/css">
	#biodata .profile-info > h3{margin:0px;padding: 5px}
    #biodata .profile-info > h3:first-child{font-weight: bold;text-transform: uppercase; }
    .half-circle{border-radius: 10px;background: #eee;font-weight: bold;font-size: 12px;padding: 2px 5px;margin: 0px 8px }
    .half-circle.blue{background: #2fa4e7;color: #fff}
    .half-circle.orange{background: #dd5600;color: #fff}
    .bgx{border:1px solid #ddd;padding: 6px 13px;font-weight: normal;border: 1px solid rgba(0, 0, 0, 0.13);}
    .bgx.green{background-color: #51a351;color:#fff;}
    .bgx.red{background-color: #bd362f;color:#fff;}
    .bgx.blue{background-color: #3968c6;color:#fff;}

    #independent-box .box-heading{text-align: left;margin-bottom: 30px;}
    #independent-box .box-heading > h4{font-size: 18px;text-transform: uppercase;font-weight: 100;display: inline-block;background: #067ec3;color: #fff;margin:0px;margin: 0px;padding: 10px 60px 10px 20px;}
    #independent-box .box-ctn{font-size: 14px}
    #independent-box .panel,#independent-box .panel-body{padding-top: 0px}

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
			<div class="panel panel-default" id="biodata">
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-1 text-center">
							<img class="img-thumbnail" id="imgThumbnail" src="<?=$profilePIC?>" style="max-width: 100px;width: 100%;">
						</div>
						<div class="col-sm-4">
							<div class="profile-info">
							<?php 
	                        $today = date("Y-m-d");
	                        $birthDate = $detail->DateOfBirth;
	                        $diff = date_diff(date_create($birthDate), date_create($today));
	                        $myAge = $diff->format('%y');
	                        ?>
                                <h3><?=(!empty($detail->TitleAhead) ? $detail->TitleAhead." ":"").$detail->Name.(!empty($detail->TitleBehind) ? " ".$detail->TitleBehind:"")?></h3>
                                <h3><?=$detail->NIP?></h3>
                                <h3><?=$detail->EmailPU?></h3>
                                <h3><?=$detail->PlaceOfBirth.", ".date("d F Y",strtotime($detail->DateOfBirth))?>
                                	<span class="half-circle blue"><?=$myAge?> years old</span></h3>
                            </div>
						</div>
						<div class="col-sm-5">
							<div class="profile-info">
                                <h3>Division <?=$detail->DivisionMain?></h3>
                                <h3><?=$detail->PositionMain?></h3>
                                <h3>Join Date 
                                <?php if(!empty($detail->MyHistorical)){
                                $firstJoin = $detail->MyHistorical->JoinDate;
                                $diffJ = date_diff(date_create($firstJoin), date_create($today));
                                $myJobYear = $diffJ->format('%y');
                                $myJobMonth = $diffJ->format('%m');
                                $myJobDay = $diffJ->format('%d'); ?>
                                <?=date("d F Y",strtotime($detail->MyHistorical->JoinDate))?> <span class="half-circle orange"><?=(!empty($myJobYear) ? $myJobYear.' years '.$myJobMonth.' months' : ( !empty($myJobMonth) ? $myJobMonth.' months '.$myJobDay.' days' : (!empty($myJobDay) ? $myJobDay.' days' : '0 month') ) ) ?></span>
                                <?php }else{echo "-";} ?></h3>
                                <?php if(!empty($detail->ResignDate)){ ?> 
                                <h3>Resign Date <?=date("d F Y",strtotime($detail->ResignDate))?></h3>
                                <?php } ?>
                            </div>
						</div>
						<div class="col-sm-2">
							<div class="text-right">
                                <span class="bgx <?=(($detail->StatusEmployeeID == 2) ? 'green': ( ($detail->StatusEmployeeID == 1) ? 'blue':'red' ) )?>">
                                <i class="fa fa-handshake-o"></i> <?=strtoupper($detail->EmpStatus)?>
                                </span>
                            </div>
						</div>
					</div>
				</div>
			</div>


			<div class="row" id="independent-box">
				<div class="col-sm-6">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="box-heading">
								<h4><i class="fa fa-user"></i> Personal Data</h4>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="box-ctn historical">
										<div class="content">
											<div class="row">
												<label class="col-sm-3">Fullname</label>
												<p class="col-sm-9"><?=$detail->Name?></p>
											</div>
											<div class="row">
												<label class="col-sm-3">Last Education Level</label>
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
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="box-heading">
								<h4><i class="fa fa-bookmark"></i> Employee Detail</h4>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="box-ctn academic">										
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
			
			
			
		<?php }else{echo "<h1 class='text-center'>Data is not founded.</h1>";} ?>
		</div>
	</div>
</div>	

<script type="text/javascript">
	$(document).ready(function(){
		$("body #detail-user .go-back").click(function(){
			$("#employee-list").removeClass("hidden");
	    	$("#employee-detail").empty();
		});
	});
</script>