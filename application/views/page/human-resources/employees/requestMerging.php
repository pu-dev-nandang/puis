<style type="text/css">.different{background: #65b96891;color: #000}.error{border:1px solid red;}.message-error{color:red;}</style>
<div class="modal fade" id="modal-merge-req" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width:100%">
    <div class="modal-content animated jackInTheBox">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Merging Employee Data</h4>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-sm-6">
      			<div class="table-responsive">
					<h4>Original Data</h4>
					<?php if(!empty($detail_ori)){ ?>
					<table class="table table-bordered">
						<tbody>
							<tr style="background:#eee"><th colspan="3"><i class="fa fa-user"></i> Biodata</th></tr>
							<tr>
								<th width="20%">NIP</th>
								<td><?=$detail_ori->NIP?></td>
								<td rowspan="5" align="center">
									<img width="100px" height="150px" src="<?=base_url('/uploads/employees/'.$detail_ori->Photo)?>" alt="<?=$detail_ori->Name?>">
								</td>
							</tr>
							<tr>
								<th>Access Card Number</th>
								<td><?=$detail_ori->Access_Card_Number?></td>
							</tr>
							<tr>
								<th>KTP Number</th>
								<td><?=$detail_ori->KTP?></td>
							</tr>

							<tr>
								<th>Fullname</th>
								<td><?=$detail_ori->Name?></td>
							</tr>
							<tr>
								<th>Gender</th>
								<td><?=($detail_ori->Gender == 'L') ? "Male":"Female"?></td>
							</tr>
							<tr>
								<th>Place/Birthdate</th>
								<td><?=$detail_ori->PlaceOfBirth.", ".date("d F Y",strtotime($detail_ori->DateOfBirth))?></td>
							</tr>
							<tr>
								<th>Religion</th>
								<td colspan="2"><?=$religion_ori->Religion?></td>
							</tr>
							<tr>
								<th>Phone</th>
								<td colspan="2"><?=$detail_ori->Phone?></td>
							</tr>
							<tr>
								<th>HP</th>
								<td colspan="2"><?=$detail_ori->HP?></td>
							</tr>
							<tr>
								<th>Email PU</th>
								<td colspan="2"><?=$detail_ori->EmailPU?></td>
							</tr>
							<tr>
								<th>Email</th>
								<td colspan="2"><?=$detail_ori->Email?></td>
							</tr>
							<tr>
								<th>Address</th>
								<td colspan="2"><?=$detail_ori->Address?></td>
							</tr>
							<tr>
								<th>Province</th>
								<td colspan="2"><?=$province_ori->Name?></td>
							</tr>
							<tr>
								<th>City</th>
								<td colspan="2"><?=$city_ori->Name?></td>
							</tr>
						</tbody>
					</table>
					<?php } ?>
				</div>		
      		</div>
      		<div class="col-sm-6">
      			<div class="table-responsive">
      				<h4>Requested Data</h4>
	      			<?php if(!empty($detail_req)){ ?>
      				<table class="table table-bordered">
						<tbody>
							<tr style="background:#eee"><th colspan="3"><i class="fa fa-user"></i> Biodata</th></tr>
							<tr>
								<th width="20%">NIP</th>
								<td><?=$detail_req->NIP?></td>
								<td rowspan="5" align="center">
									<?php if(!empty($detail_req->Photo)){ ?>
      								<img width="100px" height="150px" src="<?=base_url('uploads/employees/'.$detail_req->Photo)?>" alt="<?=$detail_req->Name?>">
      								<?php }else{ ?>
			                      	<img width="100px" height="150px" src="<?=base_url('/uploads/employees/'.$detail_ori->Photo)?>" alt="<?=$detail_ori->Name?>">
			                      	<?php } ?>
								</td>
							</tr>
							<tr class="<?=($detail_ori->Access_Card_Number != $detail_req->Access_Card_Number) ? 'different':''?>">
								<th>Access Card Number</th>
								<td><?=$detail_req->Access_Card_Number?></td>
							</tr>
							<tr class="<?=($detail_ori->KTP != $detail_req->KTP) ? 'different':''?>">
								<th>KTP Number</th>
								<td><?=$detail_req->KTP?></td>
							</tr>

							<tr class="<?=($detail_req->Name != $detail_ori->Name) ? 'different':''?>">
								<th>Fullname</th>
								<td><?=$detail_req->Name?></td>
							</tr>
							<tr class="<?=($detail_req->Gender != $detail_ori->Gender) ? 'different':''?>">
								<th>Gender</th>
								<td><?=($detail_req->Gender == 'L') ? "Male":"Female"?></td>
							</tr>
							<tr class="<?=(($detail_req->PlaceOfBirth != $detail_ori->PlaceOfBirth) || ($detail_req->DateOfBirth != $detail_ori->DateOfBirth) ) ? 'different':''?>">
								<th>Place/Birthdate</th>
								<td><?=$detail_req->PlaceOfBirth.", ".date("d F Y",strtotime($detail_req->DateOfBirth))?></td>
							</tr>
							<tr class="<?=($detail_req->ReligionID != $detail_ori->ReligionID) ? 'different':''?>">
								<th>Religion</th>
								<td colspan="2"><?=$religion_req->Religion?></td>
							</tr>
							<tr class="<?=($detail_req->Phone != $detail_ori->Phone) ? 'different':''?>">
								<th>Phone</th>
								<td colspan="2"><?=$detail_req->Phone?></td>
							</tr>
							<tr class="<?=($detail_req->HP != $detail_ori->HP) ? 'different':''?>">
								<th>HP</th>
								<td colspan="2"><?=$detail_req->HP?></td>
							</tr>
							<tr class="<?=($detail_req->EmailPU != $detail_ori->EmailPU) ? 'different':''?>">
								<th>Email PU</th>
								<td colspan="2"><?=$detail_req->EmailPU?></td>
							</tr>
							<tr class="<?=($detail_req->Email != $detail_ori->Email) ? 'different':''?>">
								<th>Email</th>
								<td colspan="2"><?=$detail_req->Email?></td>
							</tr>
							<tr class="<?=($detail_req->Address != $detail_ori->Address) ? 'different':''?>">
								<th>Address</th>
								<td colspan="2"><?=$detail_req->Address?></td>
							</tr>
							<tr class="<?=($detail_req->ProvinceID != $detail_ori->ProvinceID) ? 'different':''?>">
								<th>Province</th>
								<td colspan="2"><?=$province_req->Name?></td>
							</tr>
							<tr class="<?=($detail_req->CityID != $detail_ori->CityID) ? 'different':''?>">
								<th>City</th>
								<td colspan="2"><?=$city_req->Name?></td>
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
				  		<button class="btn btn-sm btn-primary btn-act" type="button" data-act="1" data-nip="<?=$NIP?>" ><i class="fa fa-check"></i> Accept</button>
				  		<button class="btn btn-sm btn-danger btn-act" type="button" data-act="3" data-nip="<?=$NIP?>"  ><i class="fa fa-times"></i> Reject</button>
              			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				  	</div>
      			</form>
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

			var NIP = itsme.data("nip");
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
		              NIP : NIP,
		              ACT : ACT,
		              NOTE : NOTE
		          };
		          var token = jwt_encode(data,'UAP)(*');
		          $.ajax({
		              type : 'POST',
		              url : base_url_js+"database/lecturers/req-appv",
		              data: {token:token},
		              dataType : 'json',
		              beforeSend:function(){
		                itsme.prop("disabled",true);
		                itsme.html('<i class="fa fa-refresh fa-spin fa-fw right-margin"></i> Loading...');
                		$("#form-approval-req button").prop("disabled",true);
		              },error : function(jqXHR){
		              	$("#modal-merge-req").modal("hide");
		                $("body #GlobalModal .modal-header").html("<h1>Error notification</h1>");
	                    $("body #GlobalModal .modal-body").html(jqXHR.responseText);
	                    $("body #GlobalModal").modal("show");
		              },success : function(response){
		                loadDataEmployees();
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