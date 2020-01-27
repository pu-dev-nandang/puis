<style type="text/css">.different{background: #65b96891;color: #000}.error{border:1px solid red;}.message-error{color:red;}.im-pp{width: 100%;height: auto}</style>
<div class="modal fade" id="modal-merge-req" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width:100%">
    <div class="modal-content animated jackInTheBox">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Merging Student Data</h4>
      </div>
      <div class="modal-body" style="overflow:auto;height:600px">
      	<div class="row">
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <i class="fa fa-database"></i> Original Data
                </h4>
              </div>
              <div class="panel-body">
                <div class="table-responsive">
                  <?php if(!empty($detail_ori)){ ?>
                  <table class="table table-bordered">
                    <thead>
                      <tr style="background:#eee"><th colspan="7"><i class="fa fa-user"></i> Personal Identity</th></tr>
                      <tr>
                        <th width="5%">Photo</th>
                        <th width="15%">Student</th>
                        <th width="15%">ID Card</th>
                        <th width="5%">Gender</th>
                        <th width="15%">Birthdate</th>
                      </tr>
                    </thead>
                    <tbody>                      
                      <tr>
                        <td>
                          <img class="im-pp" style="width:100%" src="<?=base_url('/uploads/students/ta_'.$TA.'/'.$detail_ori->Photo)?>" alt="<?=$detail_ori->Name?>">
                        </td>
                        <td><p class="npm"><?=$detail_ori->NPM?></p>
                            <p class="name"><?=$detail_ori->Name?></p>
                            <p class="mail"><?=$detail_ori->Email?></p>
                        </td>
                        <td><p class="ktp"><label>KTP</label><br><?=$detail_auth_ori->KTPNumber?></p>
                            <p class="card"><label>Access Card Number</label><br><?=$detail_auth_ori->Access_Card_Number?></p></td>
                        <td><?=($detail_ori->Gender == "L") ? "Male":"Female"?></td>
                        <td><?=$detail_ori->PlaceOfBirth.", ".date("m F Y",strtotime($detail_ori->DateOfBirth))?></td>
                      </tr>
                      <tr>
                        <th colspan="2">Address</th>
                        <th>Phone</th>
                        <th>Religion</th>
                        <th colspan="2">Mobile Phone</th>
                      </tr>
                      <tr>
                        <td colspan="2"><p class="address"><?=$detail_ori->Address?></p> </td>
                        <td><p class="phone"><?=$detail_ori->Phone?></p></td>
                        <td><p class="religion"><?=$detail_ori->Religion?></p></td>
                        <td colspan="2"><p class="hp"><?=$detail_ori->HP?></p></td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr style="background:#eee"><th colspan="6"><i class="fa fa-medkit"></i> Health Insurance</th></tr>
                      <tr>
                        <th>Card</th>
                        <th>Company Name</th>
                        <th>Policy Number</th>
                        <th colspan="3">Effective From</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><?php if(!empty($detail_ori->Insurance)){?><img class="im-card" src="<?=base_url('/uploads/students/insurance_card/'.$detail_ori->Insurance->Card)?>" alt="Insurance-<?=$detail_ori->NPM?>"><?php } ?></td>
                        <td><?php if(!empty($detail_ori->Insurance)){ 
                            if(!empty($detail_ori->Insurance->InsuranceOTH)){
                              echo $detail_ori->Insurance->InsuranceOTH;
                            }else{
                              echo $detail_ori->Insurance->InsuranceName;
                            }
                        } ?></td>
                        <td><?php if(!empty($detail_ori->Insurance)){ echo $detail_ori->Insurance->InsurancePolicy; } ?></td>
                        <td colspan="3">
                          <?php if(!empty($detail_ori->Insurance)){ 
                                echo date("d F Y",strtotime($detail_ori->Insurance->EffectiveStart)) ." until ". date("d F Y",strtotime($detail_ori->Insurance->EffectiveEnd)); 
                          } ?>
                        </td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr style="background:#eee"><th colspan="6"><i class="fa fa-users"></i> Data parent</th></tr>
                      <tr>
                        <th>#</th>
                        <th colspan="2">Father</th>
                        <th colspan="3">Mother</th>
                      </tr>
                      <tr>
                        <th>Name</th>
                        <td colspan="2"><?=$detail_ori->Father?></td>
                        <td colspan="3"><?=$detail_ori->Mother?></td>
                      </tr>
                      <tr>
                        <th>Education</th>
                        <td colspan="2"><?=$detail_ori->EducationFather?></td>
                        <td colspan="3"><?=$detail_ori->EducationMother?></td>
                      </tr>
                      <tr>
                        <th>Occupation</th>
                        <td colspan="2"><?=$detail_ori->OccupationFather?></td>
                        <td colspan="3"><?=$detail_ori->OccupationMother?></td>
                      </tr>
                      <tr>
                        <th>Address</th>
                        <td colspan="2"><?=$detail_ori->AddressFather?></td>
                        <td colspan="3"><?=$detail_ori->AddressMother?></td>
                      </tr>
                    </tbody>
                  </table>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <i class="fa fa-clone"></i> Requested Data
                </h4>
              </div>
              <div class="panel-body">
                <div class="table-responsive">
                  <?php if(!empty($detail_req)){ ?>
                  <table class="table table-bordered">
                    <thead>
                      <tr style="background:#eee"><th colspan="7"><i class="fa fa-user"></i> Personal Identity</th></tr>
                      <tr>
                        <th width="5%">Photo</th>
                        <th width="15%">Student</th>
                        <th width="15%">ID Card</th>
                        <th width="5%">Gender</th>
                        <th width="15%">Birthdate</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <?php if(!empty($detail_req->Photo)){ ?>
                          <img class="im-pp" style="width:100%" src="<?=base_url().'/uploads/students/ta_'.$TA.'/'.$detail_req->Photo?>" alt="<?=$detail_req->Name?>">
                          <?php }else{ ?>
                          <img class="im-pp" style="width:100%" src="<?=base_url('/uploads/students/ta_'.$TA.'/'.$detail_ori->Photo)?>" alt="<?=$detail_ori->Name?>">
                          <?php } ?>
                        </td>
                        <td><p class="npm"><?=$detail_req->NPM?></p>
                            <p class="name <?=($detail_req->Name != $detail_ori->Name) ? 'different':'' ?>"><?=$detail_req->Name?></p>
                            <p class="mail <?=($detail_req->Email != $detail_ori->Email) ? 'different':'' ?>"><?=$detail_req->Email?></p>
                        </td>
                        <td><p class="ktp <?=($detail_auth_ori->KTPNumber != $detail_req->KTPNumber) ? 'different':'' ?>"><label>KTP</label><br><?=$detail_req->KTPNumber?></p>
                            <p class="card <?=($detail_auth_ori->Access_Card_Number != $detail_req->Access_Card_Number) ? 'different':'' ?>"><?=$detail_req->Access_Card_Number?></p>
                        </td>
                        <td><p class="gender <?=($detail_req->Gender != $detail_ori->Gender) ? 'different':'' ?>"><?=($detail_req->Gender == "L") ? "Male":"Female"?></p></td>
                        <td><p class="birthdate <?=(($detail_req->PlaceOfBirth != $detail_ori->PlaceOfBirth) || ($detail_req->DateOfBirth != $detail_ori->DateOfBirth) ) ? 'different':'' ?>"><?=$detail_req->PlaceOfBirth.", ".date("m F Y",strtotime($detail_req->DateOfBirth))?></p></td>
                      </tr>
                      <tr>
                        <th colspan="2">Address</th>
                        <th>Phone</th>
                        <th>Religion</th>
                        <th>Mobile Phone</th>
                      </tr>
                      <tr>
                        <td colspan="2"><p class="address <?=($detail_req->Address != $detail_ori->Address) ? 'different':'' ?>"><?=$detail_req->Address?></p></td>
                        <td><p class="phone <?=($detail_req->Phone != $detail_ori->Phone) ? 'different':'' ?>"><?=$detail_req->Phone?></p></td>
                        <td><p class="religion <?=($detail_req->Religion != $detail_ori->Religion) ? 'different':'' ?>"><?=$detail_req->Religion?></p></td>
                        <td colspan="2"><p class="hp <?=($detail_req->HP != $detail_ori->HP) ? 'different':'' ?>"><?=$detail_req->HP?></p></td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr style="background:#eee"><th colspan="7"><i class="fa fa-medkit"></i> Health Insurance</th></tr>
                      <tr>
                        <th>Card</th>
                        <th>Company Name</th>
                        <th>Policy Number</th>
                        <th colspan="3">Effective From</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><img class="im-card" style="width:100%" src="<?=base_url('/uploads/students/insurance_card/'.$detail_req->Card)?>" ></td>
                        <td><?=(!empty($detail_req->InsuranceOTH) ? $detail_req->InsuranceOTH : (!empty($detail_req->Insurance) ? $detail_req->Insurance->Name : $detail_req->InsuranceID ) )?></td>
                        <td><?=$detail_req->InsurancePolicy?></td>
                        <td colspan="3"><?=date("d F Y",strtotime($detail_req->EffectiveStart))?> until <?=date("d F Y",strtotime($detail_req->EffectiveEnd))?></td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr style="background:#eee"><th colspan="7"><i class="fa fa-users"></i> Data parent</th></tr>
                      <tr>
                        <th>#</th>
                        <th colspan="2">Father</th>
                        <th colspan="3">Mother</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th>Name</th>
                        <td colspan="2" class="<?=($detail_req->Father != $detail_ori->Father) ? 'different':'' ?>"><?=$detail_req->Father?></td>
                        <td colspan="3" class="<?=($detail_req->Mother != $detail_ori->Mother) ? 'different':'' ?>"><?=$detail_req->Mother?></td>
                      </tr>
                      <tr>
                        <th>Education</th>
                        <td colspan="2" class="<?=($detail_req->EducationFather != $detail_ori->EducationFather) ? 'different':'' ?>"><?=$detail_req->EducationFather?></td>
                        <td colspan="3" class="<?=($detail_req->EducationMother != $detail_ori->EducationMother) ? 'different':'' ?>"><?=$detail_req->EducationMother?></td>
                      </tr>
                      <tr>
                        <th>Occupation</th>
                        <td colspan="2" class="<?=($detail_req->OccupationFather != $detail_ori->OccupationFather) ? 'different':'' ?>"><?=$detail_req->OccupationFather?></td>
                        <td colspan="3" class="<?=($detail_req->OccupationMother != $detail_ori->OccupationMother) ? 'different':'' ?>"><?=$detail_req->OccupationMother?></td>
                      </tr>
                      <tr>
                        <th>Address</th>
                        <td colspan="2" class="<?=(trim($detail_req->AddressFather) != trim($detail_ori->AddressFather)) ? 'different':'' ?>"><?=$detail_req->AddressFather?></td>
                        <td colspan="3" class="<?=(trim($detail_req->AddressMother) != trim($detail_ori->AddressMother)) ? 'different':'' ?>"><?=$detail_req->AddressMother?></td>
                      </tr>
                    </tbody>
                  </table>
                  <?php } ?>
                </div>
              </div>
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
                itsme.html('<i class="fa fa-refresh fa-spin fa-fw right-margin"></i> Loading...');
                $("#form-approval-req button").prop("disabled",true);
              },error : function(jqXHR){
                console.log(jqXHR);
                $("body #GlobalModal .modal-header").html("<h1>Error notification</h1>");
                $("body #GlobalModal .modal-body").html(jqXHR.responseText);
                $("body #GlobalModal").modal("show");
              },success : function(response){
                $('#fetch-data-tables #table-list-data').DataTable().destroy();
                fetchingData(true);
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