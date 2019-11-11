<style type="text/css">.different{background: #65b96891;color: #000}.error{border:1px solid red;}.message-error{color:red;}</style>
<div class="modal fade" id="modal-merge-req" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width:100%">
    <div class="modal-content animated jackInTheBox">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Merging Student Data</h4>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-sm-6 col-md-6">
      			<div class="table-responsive">
      				<h4>Original Data</h4>
      				<?php if(!empty($detail_ori)){ ?>
      				<table class="table table-bordered">
      					<tbody>
      						<tr style="background:#eee"><th colspan="3"><i class="fa fa-user"></i> Biodata</th></tr>
      						<tr>
      							<th width="20%">NPM</th>
      							<td><?=$detail_ori->NPM?></td>
      							<td rowspan="5" align="center">
      								<img width="100px" height="150px" src="<?=base_url('/uploads/students/ta_'.$TA.'/'.$detail_ori->Photo)?>" alt="<?=$detail_ori->Name?>">
      							</td>
      						</tr>
      						<tr>
      							<th>Name</th>
      							<td><?=$detail_ori->Name?></td>
      						</tr>
      						<tr>
      							<th>Gender</th>
      							<td><?=($detail_ori->Gender == "L") ? "Male":"Female"?></td>
      						</tr>
      						<tr>
      							<th>Place/ Birth of date</th>
      							<td><?=$detail_ori->PlaceOfBirth.", ".date("m F Y",strtotime($detail_ori->DateOfBirth))?></td>
      						</tr>
      						<tr>
      							<th>Phone</th>
      							<td><?=$detail_ori->Phone?></td>
      						</tr>
      						<tr>
      							<th>Mobile Phone</th>
      							<td colspan="2"><?=$detail_ori->HP?></td>
      						</tr>
      						<tr>
      							<th>Email</th>
      							<td colspan="2"><?=$detail_ori->Email?></td>
      						</tr>
      						<tr>
      							<th>Address</th>
      							<td colspan="2"><?=$detail_ori->Address?></td>
      						</tr>
      						<tr style="background:#eee"><th colspan="3"><i class="fa fa-users"></i> Data parent</th></tr>
      						<tr>
      							<th>#</th>
      							<th>Father</th>
      							<th>Mother</th>
      						</tr>
      						<tr>
      							<th>Name</th>
      							<td><?=$detail_ori->Father?></td>
      							<td><?=$detail_ori->Mother?></td>
      						</tr>
      						<tr>
      							<th>Education</th>
      							<td><?=$detail_ori->EducationFather?></td>
      							<td><?=$detail_ori->EducationMother?></td>
      						</tr>
      						<tr>
      							<th>Occupation</th>
      							<td><?=$detail_ori->OccupationFather?></td>
      							<td><?=$detail_ori->OccupationMother?></td>
      						</tr>
      						<tr>
      							<th>Address</th>
      							<td><?=$detail_ori->AddressFather?></td>
      							<td><?=$detail_ori->AddressMother?></td>
      						</tr>

      					</tbody>
      				</table>
      				<?php } ?>
      			</div>
      		</div>
      		<div class="col-sm-6 col-md-6">
      			<div class="table-responsive">
      				<h4>Requested Data</h4>
      				<?php if(!empty($detail_req)){ ?>
      				<table class="table table-bordered">
      					<tbody>
      						<tr style="background:#eee"><th colspan="3"><i class="fa fa-user"></i> Biodata</th></tr>
      						<tr>
      							<th width="20%">NPM</th>
      							<td><?=$detail_req->NPM?></td>
      							<td rowspan="5" align="center">
      								<?php if(!empty($detail_req->Photo)){ ?>
      								<img width="100px" height="150px" src="<?=$detail_req->pathPhoto.'uploads/ta_'.$TA.'/'.$detail_req->Photo?>" alt="<?=$detail_req->Name?>">
      								<?php }else{ ?>
                      <img width="100px" height="150px" src="<?=base_url('/uploads/students/ta_'.$TA.'/'.$detail_ori->Photo)?>" alt="<?=$detail_ori->Name?>">
                      <?php } ?>
      							</td>
      						</tr>
      						<tr class="<?=($detail_req->Name != $detail_ori->Name) ? 'different':'' ?>" >
      							<th>Name</th>
      							<td><?=$detail_req->Name?></td>
      						</tr>
      						<tr class="<?=($detail_req->Gender != $detail_ori->Gender) ? 'different':'' ?>">
      							<th>Gender</th>
      							<td><?=($detail_req->Gender == "L") ? "Male":"Female"?></td>
      						</tr>
      						<tr class="<?=(($detail_req->PlaceOfBirth != $detail_ori->PlaceOfBirth) || ($detail_req->DateOfBirth != $detail_ori->DateOfBirth) ) ? 'different':'' ?>">
      							<th>Place/ Birth of date</th>
      							<td><?=$detail_req->PlaceOfBirth.", ".date("m F Y",strtotime($detail_req->DateOfBirth))?></td>
      						</tr>
      						<tr class="<?=($detail_req->Phone != $detail_ori->Phone) ? 'different':'' ?>">
      							<th>Phone</th>
      							<td><?=$detail_req->Phone?></td>
      						</tr>
      						<tr class="<?=($detail_req->HP != $detail_ori->HP) ? 'different':'' ?>">
      							<th>Mobile Phone</th>
      							<td colspan="2"><?=$detail_req->HP?></td>
      						</tr>
      						<tr class="<?=($detail_req->Email != $detail_ori->Email) ? 'different':'' ?>">
      							<th>Email</th>
      							<td colspan="2"><?=$detail_req->Email?></td>
      						</tr>
      						<tr class="<?=($detail_req->Address != $detail_ori->Address) ? 'different':'' ?>">
      							<th>Address</th>
      							<td colspan="2"><?=$detail_req->Address?></td>
      						</tr>
      						<tr style="background:#eee"><th colspan="3"><i class="fa fa-users"></i> Data parent</th></tr>
      						<tr>
      							<th>#</th>
      							<th>Father</th>
      							<th>Mother</th>
      						</tr>
      						<tr>
      							<th>Name</th>
      							<td class="<?=($detail_req->Father != $detail_ori->Father) ? 'different':'' ?>"><?=$detail_req->Father?></td>
      							<td class="<?=($detail_req->Mother != $detail_ori->Mother) ? 'different':'' ?>"><?=$detail_req->Mother?></td>
      						</tr>
      						<tr>
      							<th>Education</th>
      							<td class="<?=($detail_req->EducationFather != $detail_ori->EducationFather) ? 'different':'' ?>"><?=$detail_req->EducationFather?></td>
      							<td class="<?=($detail_req->EducationMother != $detail_ori->EducationMother) ? 'different':'' ?>"><?=$detail_req->EducationMother?></td>
      						</tr>
      						<tr>
      							<th>Occupation</th>
      							<td class="<?=($detail_req->OccupationFather != $detail_ori->OccupationFather) ? 'different':'' ?>"><?=$detail_req->OccupationFather?></td>
      							<td class="<?=($detail_req->OccupationMother != $detail_ori->OccupationMother) ? 'different':'' ?>"><?=$detail_req->OccupationMother?></td>
      						</tr>
      						<tr>
      							<th>Address</th>
      							<td class="<?=(trim($detail_req->AddressFather) != trim($detail_ori->AddressFather)) ? 'different':'' ?>"><?=$detail_req->AddressFather?></td>
      							<td class="<?=(trim($detail_req->AddressMother) != trim($detail_ori->AddressMother)) ? 'different':'' ?>"><?=$detail_req->AddressMother?></td>
      						</tr>

      					</tbody>
      				</table>
      				<?php } ?>
      			</div>
      		</div>
      	</div>
  	  </div>
  	  <div class="modal-footer">
      	<div class="row">
      		<div class="col-sm-12 ">
      			<form id="form-approval-req" autocomplete="off">
      				<div class="form-group" style="text-align:left">
					    <label>Note</label>
					    <textarea class="form-control" name="note" placeholder="Write your review here.."></textarea>
                                  <span class="message-error"></span>
				  	</div>
				  	<div class="text-center">
				  		<button class="btn btn-sm btn-primary btn-act" type="button" data-act="1" data-npm="<?=$NPM?>" data-ta="<?=$TA?>" ><i class="fa fa-check"></i> Accept</button>
				  		<button class="btn btn-sm btn-danger btn-act" type="button" data-act="3" data-npm="<?=$NPM?>" data-ta="<?=$TA?>" ><i class="fa fa-times"></i> Reject</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				  	</div>
      			</form>
      		</div>
      	</div>
      </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#modal-merge-req").modal("show");
		$("#modal-merge-req").on("click",".btn-act",function(){
			var itsme = $(this);
			var name = itsme.text();
			var ACT = itsme.data("act");

                  var NPM = itsme.data("npm");
                  var TA = itsme.data("ta");
                  var NOTE = $("#form-approval-req textarea[name=note]").val();
                  var isvalid = false;
                  if(ACT == 3){
                        console.log("reject");
                        if($.trim(NOTE) == ''){
                              console.log("isi:"+$(this).val());
                              $("#form-approval-req textarea[name=note]").addClass("error");
                              $("#form-approval-req textarea[name=note]").parent().find(".message-error").text("Please fill this field");
                              isvalid = false;
                        }else{
                              isvalid=true;
                              $("#form-approval-req textarea[name=note]").removeClass("error");
                              $("#form-approval-req textarea[name=note]").parent().find(".message-error").text("");
                        }
                  }else if(ACT == 1){
                        isvalid = true;
                  }     

                  if(isvalid){
                        if(confirm("Are you sure wants to "+name.toUpperCase()+" this data ?")){
                              var data = {
                                  NPM : NPM,
                                  TA : TA,
                                  ACT : ACT,
                                  NOTE : NOTE
                              };
                              var token = jwt_encode(data,'UAP)(*');
                              $.ajax({
                                  type : 'POST',
                                  url : base_url_js+"database/student/req-appv",
                                  data: {token:token},
                                  dataType : 'json',
                                  beforeSend:function(){
                                    itsme.prop("disabled",true);
                                  },error : function(jqXHR){
                                      alert("Error info:\n"+jqXHR.responseText);
                                  },success : function(response){
                                    loadStudent();
                                    $("#form-approval-req").empty();
                                    toastr.success(response.message,'Info!'); 
                                    $("#modal-merge-req").modal("hide");
                                  }
                              });
                        }
                  }
			
		});
	});
</script>