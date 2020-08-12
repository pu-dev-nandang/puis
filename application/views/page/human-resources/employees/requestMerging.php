<style type="text/css">.different{background: #65b96891;color: #000}.error{border:1px solid red;}.message-error{color:red;}</style>
<div class="modal fade" id="modal-merge-req" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width:100%">
    <div class="modal-content animated jackInTheBox">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Merging Employee Data</h4>
      </div>
      <div class="modal-body">
      	<div class="tabs-nav">
      		<ul class="nav nav-tabs" role="tablist">
			    <li role="presentation" class="active"><a href="#personal-data" aria-controls="personal-data" role="tab" data-toggle="tab">Personal data</a></li>
                <li role="presentation"><a href="#additional-info" aria-controls="additional-info" role="tab" data-toggle="tab">Additional info</a></li>
                <li role="presentation"><a href="#family" aria-controls="family" role="tab" data-toggle="tab">Family</a></li>
                <li role="presentation"><a href="#educations" aria-controls="educations" role="tab" data-toggle="tab">Educations</a></li>
                <li role="presentation"><a href="#training" aria-controls="training" role="tab" data-toggle="tab">Training</a></li>
                <li role="presentation"><a href="#work-experience" aria-controls="work-experience" role="tab" data-toggle="tab">Work experience</a></li>
                <li role="presentation"><a href="#signature" aria-controls="signature" role="tab" data-toggle="tab">Signature</a></li>
		  	</ul>
      	</div>
      	<div class="content" style="overflow-y:auto;overflow-x:hidden;max-height:400px;padding:15px;border:1px solid #ddd;border-top:0px">
      		<div class="tab-content">
			    <div role="tabpanel" class="tab-pane active" id="personal-data">
			    	<div class="row">
			    		<div class="col-sm-6">
			    			<div class="origin-data">
					    		<div class="panel panel-default">
					    			<div class="panel-heading">
					    				<h4 class="panel-title">Original Data</h4>
					    			</div>
					    			<div class="panel-body">
					    				<div class="data">
					                        <div class="row">
					                            <?php if(!empty($origin->Photo)){ ?>
					                            <div class="col-sm-2">
					                                <div class="text-center">
					                                    <img id="req-photo" src="<?=base_url('uploads/employees/'.$origin->Photo)?>" width="100px" class="img-thumbnail">
					                                </div>
					                            </div>
					                            <?php } ?>
					                            <div class="col-sm-3">                                
					                                <div class="form-group">
					                                    <label>Number of ID Card</label>
					                                    <p><?=(!empty($origin->KTP) ? $origin->KTP : '-')?></p>
					                                </div>
					                                <div class="form-group">
					                                    <label>Gender</label>
					                                    <p><?=(!empty($origin->Gender) ? (($origin->Gender == 'L') ? 'Male':'Female') : '-' )?></p>
					                                </div>
					                                <div class="form-group">
					                                    <label>Religion</label>
					                                    <p><?=(!empty($origin->ReligionID) ? (labelProfileDB("db_employees.religion",array("IDReligion"=>$origin->ReligionID))->Religion) :'-')?></p>
					                                </div>
					                            </div>
					                            <div class="col-sm-3">
					                                <div class="form-group">
					                                    <label>Blood</label>
					                                    <p><?=(!empty($origin->Blood) ? $origin->Blood : '-')?></p>
					                                </div>
					                                <div class="form-group">
					                                    <label>Place of Birthdate</label>
					                                    <p><?=(!empty($origin->PlaceOfBirth) ? $origin->PlaceOfBirth : '-')?></p>
					                                </div>                                
					                            </div>
					                            <div class="col-sm-3">
					                                <div class="form-group">
					                                    <label>Access Card Number</label>
					                                    <p><?=(!empty($origin->Access_Card_Number) ? $origin->Access_Card_Number : '-')?></p>
					                                </div> 
					                                <div class="form-group">
					                                    <label>Email</label>
					                                    <p><?=(!empty($origin->Email) ? $origin->Email : '-')?></p>
					                                </div>                       
					                                <div class="form-group">
					                                    <label>Mobile</label>
					                                    <p><?=(!empty($origin->HP) ? $origin->HP : '-')?></p>
					                                </div>                       

					                            </div>
					                        </div>
					                        <div class="row">
					                            <div class="col-sm-6">
					                                <p><b><i class="fa fa-id-card"></i> Base on ID Card</b></p>
					                                <div class="row">
					                                    <div class="col-sm-12">
					                                        <div class="form-group">
					                                            <label>Address</label>
					                                            <p><?=(!empty($origin->Address) ? $origin->Address : '-')?></p>
					                                        </div>
					                                    </div>
					                                </div>
					                                <div class="row">
					                                    <div class="col-sm-6">
					                                        <div class="form-group">
					                                            <label>Country</label>
					                                            <p><?=(!empty($origin->CountryID)) ? labelProfileDB("db_admission.country",array("ctr_code"=>$origin->CountryID))->ctr_name : '-'?></p>
					                                        </div>
					                                    </div>
					                                    <?php if(!empty($origin->ProvinceID)){ ?>
					                                    <div class="col-sm-6">
					                                        <div class="form-group">
					                                            <label>Province</label>
					                                            <?php $ProvinceName = '-';
					                                            if(!empty($origin->ProvinceID)){
					                                            	$Province = labelProfileDB("db_admission.province",array("ProvinceID"=>$origin->ProvinceID));
					                                            	$ProvinceName = (!empty($Province) ? $Province->ProvinceName : '-');
					                                            }
					                                            ?>
					                                            <p><?=$ProvinceName?></p>
					                                        </div>
					                                    </div>
					                                    <div class="col-sm-6">
					                                        <div class="form-group">
					                                            <label>Region</label>
					                                            <p><?=($origin->RegionID) ? labelProfileDB("db_admission.region",array("RegionID"=>$origin->RegionID))->RegionName : '-'?></p>
					                                        </div>
					                                    </div>
					                                    <div class="col-sm-6">
					                                        <div class="form-group">
					                                            <label>District</label>
					                                            <p><?=(!empty($origin->DistrictID)) ? labelProfileDB("db_admission.district",array("DistrictID"=>$origin->DistrictID))->DistrictName : '-'?></p>
					                                        </div>
					                                    </div>

					                                    <?php } ?>
					                                    <div class="col-sm-3">
					                                        <div class="form-group">
					                                            <label>Post Code</label>
					                                            <p><?=(!empty($origin->Postcode) ? $origin->Postcode : '-')?></p>
					                                        </div>
					                                    </div>
					                                    <div class="col-sm-3">
					                                        <div class="form-group">
					                                            <label>Phone</label>
					                                            <p><?=(!empty($origin->Phone) ? $origin->Phone : '-')?></p>
					                                        </div>
					                                    </div>

					                                </div>
					                            </div>
					                            <div class="col-sm-6">
					                                <p><b><i class="fa fa-map-marker"></i> Current Address</b></p>
					                                <div class="row">
					                                    <div class="col-sm-12">
					                                        <div class="form-group">
					                                            <label>Address</label>
					                                            <p><?=(!empty($origin->CurrAddress) ? $origin->CurrAddress : '-')?></p>
					                                        </div>
					                                    </div>
					                                </div>
					                                <div class="row">
					                                    <div class="col-sm-3">
					                                        <div class="form-group">
					                                            <label>Post Code</label>
					                                            <p><?=(!empty($origin->CurrPostCode) ? $origin->CurrPostCode : '-')?></p>
					                                        </div>
					                                    </div>
					                                    <div class="col-sm-3">
					                                        <div class="form-group">
					                                            <label>Phone</label>
					                                            <p><?=(!empty($origin->CurrPhone) ? $origin->CurrPhone : '-')?></p>
					                                        </div>
					                                    </div>

					                                </div>
					                            </div>
					                        </div>
					                    </div>
					    			</div>
					    		</div>
					    	</div>
			    		</div>
			    		<div class="col-sm-6">
			    			<div class="request-data">
			    				<div class="panel panel-default">
			    					<div class="panel-heading">
			    						<h4 class="panel-title">Request Data</h4>
			    					</div>
			    					<div class="panel-body">
			    						<div class="data">
					                        <div class="row">
					                            <?php if(!empty($request->Photo)){ ?>
					                            <div class="col-sm-2">
					                                <div class="text-center">
					                                    <img id="req-photo" src="<?=base_url('uploads/employees/'.$request->Photo)?>" width="100px" class="img-thumbnail">
					                                </div>
					                            </div>
					                            <?php } ?>
					                            <div class="col-sm-3">                                
					                                <div class="form-group">
					                                    <label>Number of ID Card</label>
					                                    <p><?=(!empty($request->KTP) ? $request->KTP : '-')?></p>
					                                </div>
					                                <div class="form-group">
					                                    <label>Gender</label>
					                                    <p><?=(!empty($request->Gender) ? (($request->Gender == 'L') ? 'Male':'Female') : '-' )?></p>
					                                </div>
					                                <div class="form-group">
					                                    <label>Religion</label>
					                                    <p><?=(!empty($request->ReligionID) ? (labelProfileDB("db_employees.religion",array("IDReligion"=>$request->ReligionID))->Religion) :'-')?></p>
					                                </div>
					                            </div>
					                            <div class="col-sm-3">
					                                <div class="form-group">
					                                    <label>Blood</label>
					                                    <p><?=(!empty($request->Blood) ? $request->Blood : '-')?></p>
					                                </div>
					                                <div class="form-group">
					                                    <label>Place of Birthdate</label>
					                                    <p><?=(!empty($request->PlaceOfBirth) ? $request->PlaceOfBirth : '-')?></p>
					                                </div>                                
					                            </div>
					                            <div class="col-sm-3">
					                                <div class="form-group">
					                                    <label>Access Card Number</label>
					                                    <p><?=(!empty($request->Access_Card_Number) ? $request->Access_Card_Number : '-')?></p>
					                                </div> 
					                                <div class="form-group">
					                                    <label>Email</label>
					                                    <p><?=(!empty($request->Email) ? $request->Email : '-')?></p>
					                                </div>                       
					                                <div class="form-group">
					                                    <label>Mobile</label>
					                                    <p><?=(!empty($request->HP) ? $request->HP : '-')?></p>
					                                </div>                       

					                            </div>
					                        </div>
					                        <div class="row">
					                            <div class="col-sm-6">
					                                <p><b><i class="fa fa-id-card"></i> Base on ID Card</b></p>
					                                <div class="row">
					                                    <div class="col-sm-12">
					                                        <div class="form-group">
					                                            <label>Address</label>
					                                            <p><?=(!empty($request->Address) ? $request->Address : '-')?></p>
					                                        </div>
					                                    </div>
					                                </div>
					                                <div class="row">
					                                    <div class="col-sm-6">
					                                        <div class="form-group">
					                                            <label>Country</label>
					                                            <?php if(!empty($request->CountryID)){ ?>
					                                            <p><?=labelProfileDB("db_admission.country",array("ctr_code"=>$request->CountryID))->ctr_name?></p>
					                                            <?php } ?>
					                                        </div>
					                                    </div>
					                                    <?php if(!empty($request->ProvinceID)){ ?>
					                                    <div class="col-sm-6">
					                                        <div class="form-group">
					                                            <label>Province</label>
					                                            <p><?=labelProfileDB("db_admission.province",array("ProvinceID"=>$request->ProvinceID))->ProvinceName?></p>
					                                        </div>
					                                    </div>
					                                    <div class="col-sm-6">
					                                        <div class="form-group">
					                                            <label>Region</label>
					                                            <p><?=labelProfileDB("db_admission.region",array("RegionID"=>$request->RegionID))->RegionName?></p>
					                                        </div>
					                                    </div>
					                                    <div class="col-sm-6">
					                                        <div class="form-group">
					                                            <label>District</label>
					                                            <p><?=labelProfileDB("db_admission.district",array("DistrictID"=>$request->DistrictID))->DistrictName?></p>
					                                        </div>
					                                    </div>
					                                    <?php } ?>
					                                    <div class="col-sm-3">
					                                        <div class="form-group">
					                                            <label>Post Code</label>
					                                            <p><?=(!empty($request->Postcode) ? $request->Postcode : '-')?></p>
					                                        </div>
					                                    </div>
					                                    <div class="col-sm-3">
					                                        <div class="form-group">
					                                            <label>Phone</label>
					                                            <p><?=(!empty($request->Phone) ? $request->Phone : '-')?></p>
					                                        </div>
					                                    </div>

					                                </div>
					                            </div>
					                            <div class="col-sm-6">
					                                <p><b><i class="fa fa-map-marker"></i> Current Address</b></p>
					                                <div class="row">
					                                    <div class="col-sm-12">
					                                        <div class="form-group">
					                                            <label>Address</label>
					                                            <p><?=(!empty($request->CurrAddress) ? $request->CurrAddress : '-')?></p>
					                                        </div>
					                                    </div>
					                                </div>
					                                <div class="row">
					                                    <div class="col-sm-3">
					                                        <div class="form-group">
					                                            <label>Post Code</label>
					                                            <p><?=(!empty($request->CurrPostCode) ? $request->CurrPostCode : '-')?></p>
					                                        </div>
					                                    </div>
					                                    <div class="col-sm-3">
					                                        <div class="form-group">
					                                            <label>Phone</label>
					                                            <p><?=(!empty($request->CurrPhone) ? $request->CurrPhone : '-')?></p>
					                                        </div>
					                                    </div>

					                                </div>
					                            </div>
					                        </div>
					                        <?php if (isset($request->certificate_request)): ?>
					                        <div class="row">
					                         	<div class="col-md-12">
					                         		<div class="well">
					                         		    <div style="padding: 15;">
					                         		        <h2>Certificate</h2>
					                         		        <br/>
					                         		            <table class="table">
					                         		                <thead>
					                         		                    <tr>
					                         		                        <th>No</th>
					                         		                        <th>Certificate</th>
					                         		                        <th>Publication Year</th>
					                         		                        <th>DueDate</th>
					                         		                        <th>Scale</th>
					                         		                        <th>File</th>
					                         		                    </tr>
					                         		                </thead>
					                         		                <tbody>
					                         		                    <?php for ($i=0; $i < count($request->certificate_request); $i++) : ?>
					                         		                        <tr>
					                         		                            <td><?php echo $i+1 ?></td>
					                         		                            <td><?php echo $request->certificate_request[$i]->Certificate ?></td>
					                         		                            <td><?php echo $request->certificate_request[$i]->PublicationYear ?></td>
					                         		                            <td><?php echo ($request->certificate_request[$i]->Lifetime == '1') ? 'Lifetime' : $request->certificate_request[$i]->Duedate ?></td>
					                         		                            <td><?php echo $request->certificate_request[$i]->Scale ?></td>
					                         		                            <td><a class="btn btn-sm btn-default" target="_blank" href="<?php echo base_url().'uploads/certificate/'.$request->certificate_request[$i]->File ?>">Download</a></td>
					                         		                        </tr>
					                         		                    <?php endfor ?>
					                         		                </tbody>
					                         		            </table>
					                         		    </div>
					                         		</div>
					                         	</div>
					                        </div>
					                        <?php endif ?>
					                    </div>
			    					</div>
			    				</div>
			    			</div>
			    		</div>
			    	</div>
			    </div>
			    <div role="tabpanel" class="tab-pane" id="additional-info">
			    	<div class="row">
			    		<div class="col-sm-6">
			    			<div class="origin-data">
			    				<div class="panel panel-default">
			    					<div class="panel-heading">
			    						<h4 class="panel-title">Original Data</h4>
			    					</div>
			    					<div class="panel-body">
			    						<div class="data">
					                        <div class="row">
					                            <div class="col-sm-12">
					                                <div class="row">
					                                    <div class="col-sm-4">
					                                        <div class="form-group">
					                                            <label>Family Card</label>
					                                            <p><?=(!empty($origin->IDFamilyCard) ? $origin->IDFamilyCard : '-')?></p>
					                                        </div>
					                                    </div>
					                                    <div class="col-sm-4">
					                                        <div class="form-group">
					                                            <label>NPWP</label>
					                                            <p><?=(!empty($origin->IDNPWP) ? $origin->IDNPWP : '-')?></p>
					                                        </div>
					                                    </div>
					                                    <div class="col-sm-4">
					                                        <div class="form-group">
					                                            <label>Passport</label>
					                                            <p><?=(!empty($origin->IDPassport) ? $origin->IDPassport : '-')?></p>
					                                        </div>
					                                    </div>
					                                </div>
					                                <div class="row">
					                                    <div class="col-sm-12">
					                                        <p><b><i class="fa fa-id-card"></i> Bank List</b></p>
					                                        <table class="table table-bordered">
					                                            <thead>
					                                                <tr>
					                                                    <th width="5%">No</th>
					                                                    <th>Bank Name</th>
					                                                    <th>Account Name</th>
					                                                    <th>Account Number</th>
					                                                </tr>
					                                            </thead>
					                                            <tbody>
					                                            <?php if(!empty($origin->MyBank)){ $no=1;
					                                            for ($i=0; $i < count($origin->MyBank); $i++) { ?>
					                                                <tr>
					                                                    <td><?=$no++;?></td>
					                                                    <td><?=$origin->MyBank[$i]->bank?></td>
					                                                    <td><?=$origin->MyBank[$i]->accountName?></td>
					                                                    <td><?=$origin->MyBank[$i]->accountNumber?></td>
					                                                </tr>
					                                            <?php } }else{ ?>
					                                            </tbody>
					                                        </table>
					                                    </div>
					                                                <tr>
					                                                    <td colspan="4">Empty data</td>
					                                                </tr>
					                                            <?php } ?>
					                                </div>
					                            </div>                            
					                        </div>                        
					                    </div>
			    					</div>
			    				</div>			    				
			    			</div>
			    		</div>
			    		<div class="col-sm-6">
			    			<div class="request-data">
			    				<div class="panel panel-default">
			    					<div class="panel-heading">
			    						<h4 class="panel-title">Request Data</h4>
			    					</div>
			    					<div class="panel-body">
			    						<div class="data">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>Family Card</label>
                                                                <p><?=(!empty($request->IDFamilyCard) ? $request->IDFamilyCard : '-')?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>NPWP</label>
                                                                <p><?=(!empty($request->IDNPWP) ? $request->IDNPWP : '-')?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>Passport</label>
                                                                <p><?=(!empty($request->IDPassport) ? $request->IDPassport : '-')?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <p><b><i class="fa fa-id-card"></i> Bank List</b></p>
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="5%">No</th>
                                                                        <th>Bank Name</th>
                                                                        <th>Account Name</th>
                                                                        <th>Account Number</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php if(!empty($request->MyBank)){ $no=1;
                                                                for ($i=0; $i < count($request->MyBank); $i++) { ?>
                                                                    <tr>
                                                                        <td><?=$no++;?></td>
                                                                        <td><?=$request->MyBank[$i]->bank?></td>
                                                                        <td><?=$request->MyBank[$i]->accountName?></td>
                                                                        <td><?=$request->MyBank[$i]->accountNumber?></td>
                                                                    </tr>
                                                                <?php } }else{ ?>
                                                                    <tr>
                                                                        <td colspan="4">Empty data</td>
                                                                    </tr>
                                                                <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>                            
                                            </div>                        
                                        </div>
			    					</div>
			    				</div>
			    			</div>
			    		</div>
			    	</div>
			    </div>
			    <div role="tabpanel" class="tab-pane" id="family">
			    	<div class="row">
			    		<div class="col-sm-12">
			    			<div class="origin-data">
			    				<div class="panel panel-default">
			    					<div class="panel-heading">
			    						<h4 class="panel-title">Original Data</h4>
			    					</div>
			    					<div class="panel-body">
			    						<div class="data">
					                        <table class="table table-bordered">
					                            <thead>
					                                <tr>
					                                    <th width="5%">No</th>
					                                    <th>Relation with officer</th>
					                                    <th>Gender</th>
					                                    <th>Name</th>
					                                    <th>Place</th>
					                                    <th>Date of birth</th>
					                                    <th>Last Education</th>
					                                </tr>
					                            </thead>
					                            <tbody>
					                            <?php if(!empty($origin->MyFamily)){ $no = 1;
					                            for ($j=0; $j < count($origin->MyFamily); $j++) { ?>
					                                <tr>
					                                    <td><?=$no++?></td>
					                                    <td><?=labelProfileDB("db_employees.master_family_relations",array("ID"=>$origin->MyFamily[$j]->relationID))->name?></td>
					                                    <td><?=(($origin->MyFamily[$j]->gender == "P") ? "Female":"Male")?></td>
					                                    <td><?=$origin->MyFamily[$j]->name?></td>
					                                    <td><?=$origin->MyFamily[$j]->placeBirth?></td>
					                                    <td><?=date("d F Y",strtotime($origin->MyFamily[$j]->birthdate))?></td>
					                                    <td><?=(!empty($origin->MyFamily[$j]->lastEduID) ? labelProfileDB("db_employees.level_education",array("ID"=>$origin->MyFamily[$j]->lastEduID))->Level : '')?></td>
					                                </tr>
					                            <?php } }else{ ?>
					                                <tr>
					                                    <td colspan="7">Empty data</td>
					                                </tr>
					                            <?php } ?>
					                            </tbody>
					                        </table>
					                    </div>
			    					</div>
			    				</div>
			    			</div>
			    		</div>
			    		<div class="col-sm-12">
			    			<div class="request-data">
			    				<div class="panel panel-default">
			    					<div class="panel-heading">
			    						<h4 class="panel-title">Request Data</h4>
			    					</div>
			    					<div class="panel-body">
			    						<div class="data">
					                        <table class="table table-bordered">
					                            <thead>
					                                <tr>
					                                    <th width="5%">No</th>
					                                    <th>Relation with officer</th>
					                                    <th>Gender</th>
					                                    <th>Name</th>
					                                    <th>Place</th>
					                                    <th>Date of birth</th>
					                                    <th>Last Education</th>
					                                </tr>
					                            </thead>
					                            <tbody>
					                            <?php if(!empty($request->MyFamily)){ $no = 1;
					                            for ($j=0; $j < count($request->MyFamily); $j++) { ?>
					                                <tr>
					                                    <td><?=$no++?></td>
					                                    <td><?=labelProfileDB("db_employees.master_family_relations",array("ID"=>$request->MyFamily[$j]->relationID))->name?></td>
					                                    <td><?=(($request->MyFamily[$j]->gender == "P") ? "Female":"Male")?></td>
					                                    <td><?=$request->MyFamily[$j]->name?></td>
					                                    <td><?=$request->MyFamily[$j]->placeBirth?></td>
					                                    <td><?=date("d F Y",strtotime($request->MyFamily[$j]->birthdate))?></td>
					                                    <td><?=(!empty($request->MyFamily[$j]->lastEduID ) ? labelProfileDB("db_employees.level_education",array("ID"=>$request->MyFamily[$j]->lastEduID))->Level : '')?></td>
					                                </tr>
					                            <?php } }else{ ?>
					                                <tr>
					                                    <td colspan="7">Empty data</td>
					                                </tr>
					                            <?php } ?>
					                            </tbody>
					                        </table>
					                    </div>
			    					</div>
			    				</div>			    				
			    			</div>
			    		</div>
			    	</div>
			    </div>
			    <div role="tabpanel" class="tab-pane" id="educations">
			    	<div class="row">
			    		<div class="col-sm-6">
			    			<div class="origin-data">
			    				<div class="panel panel-default">
			    					<div class="panel-heading">
			    						<h4 class="panel-title">Original Data</h4>
			    					</div>
			    					<div class="panel-body">
			    						<div class="data">
					                        <div class="panel panel-default">
					                            <div class="panel-heading"><h4 class="panel-title">Educations</h4></div>
					                            <div class="panel-body">
					                                <table class="table table-bordered">
					                                    <thead>
					                                        <tr>
					                                            <th width="5%">No</th>
					                                            <th>Level Education</th>
					                                            <th>Institute Name</th>
					                                            <th>Country/City</th>
					                                            <th>Major</th>
					                                            <th>Graduation Year</th>
					                                            <th>GPA</th>
					                                        </tr>
					                                    </thead>
					                                    <tbody>
					                                    <?php if(!empty($origin->MyEducation)){ $no = 1;
					                                    for ($k=0; $k < count($origin->MyEducation); $k++) { ?>
					                                        <tr>
					                                            <td><?=$no++?></td>
					                                            <td><?=labelProfileDB("db_employees.level_education",array("ID"=>$origin->MyEducation[$k]->levelEduID))->Level?></td>
					                                            <td><?=$origin->MyEducation[$k]->instituteName?></td>
					                                            <td><?=$origin->MyEducation[$k]->location?></td>
					                                            <td><?=$origin->MyEducation[$k]->major?></td>
					                                            <td><?=$origin->MyEducation[$k]->graduation?></td>
					                                            <td><?=$origin->MyEducation[$k]->gpa?></td>
					                                        </tr>
					                                    <?php } }else{ ?>
					                                        <tr>
					                                            <td colspan="7">Empty data</td>
					                                        </tr>   
					                                    <?php } ?>                                     
					                                    </tbody>
					                                </table>
					                            </div>
					                        </div>

					                        <div class="panel panel-default">
					                            <div class="panel-heading"><h4 class="panel-title">Non Educations</h4></div>
					                            <div class="panel-body">
					                                <table class="table table-bordered">
					                                    <thead>
					                                        <tr>
					                                            <th width="5%">No</th>
					                                            <th>Institute Name</th>
					                                            <th>Subject</th>
					                                            <th>Start Even</th>
					                                            <th>End Event</th>
					                                            <th>Country/City</th>
					                                        </tr>
					                                    </thead>
					                                    <tbody>
					                                    <?php if(!empty($origin->MyEducationNonFormal)){ $no = 1;
					                                    for ($k=0; $k < count($origin->MyEducationNonFormal); $k++) { ?>
					                                        <tr>
					                                            <td><?=$no++?></td>
					                                            <td><?=$origin->MyEducationNonFormal[$k]->instituteName?></td>
					                                            <td><?=$origin->MyEducationNonFormal[$k]->subject?></td>
					                                            <td><?=date("d F Y",strtotime($origin->MyEducationNonFormal[$k]->start_event))?></td>
					                                            <td><?=date("d F Y",strtotime($origin->MyEducationNonFormal[$k]->end_event))?></td>
					                                            <td><?=$origin->MyEducationNonFormal[$k]->location?></td>
					                                        </tr>
					                                    <?php } }else{ ?>
					                                        <tr>
					                                            <td colspan="7">Empty data</td>
					                                        </tr>   
					                                    <?php } ?>                                     
					                                    </tbody>
					                                </table>
					                            </div>
					                        </div>
					                    </div>
			    					</div>
			    				</div>
			    			</div>
			    		</div>

			    		<div class="col-sm-6">
			    			<div class="request-data">
			    				<div class="panel panel-default">
			    					<div class="panel-heading">
			    						<h4 class="panel-title">Request Data</h4>
			    					</div>
			    					<div class="panel-body">
			    						<div class="data">
					                        <div class="panel panel-default">
					                            <div class="panel-heading"><h4 class="panel-title">Educations</h4></div>
					                            <div class="panel-body">
					                                <table class="table table-bordered">
					                                    <thead>
					                                        <tr>
					                                            <th width="5%">No</th>
					                                            <th>Level Education</th>
					                                            <th>Institute Name</th>
					                                            <th>Country/City</th>
					                                            <th>Major</th>
					                                            <th>Graduation Year</th>
					                                            <th>GPA</th>
					                                        </tr>
					                                    </thead>
					                                    <tbody>
					                                    <?php if(!empty($request->MyEducation)){ $no = 1;
					                                    for ($k=0; $k < count($request->MyEducation); $k++) { ?>
					                                        <tr>
					                                            <td><?=$no++?></td>
					                                            <td><?=labelProfileDB("db_employees.level_education",array("ID"=>$request->MyEducation[$k]->levelEduID))->Level?></td>
					                                            <td><?=$request->MyEducation[$k]->instituteName?></td>
					                                            <td><?=$request->MyEducation[$k]->location?></td>
					                                            <td><?=$request->MyEducation[$k]->major?></td>
					                                            <td><?=$request->MyEducation[$k]->graduation?></td>
					                                            <td><?=$request->MyEducation[$k]->gpa?></td>
					                                        </tr>
					                                    <?php } }else{ ?>
					                                        <tr>
					                                            <td colspan="7">Empty data</td>
					                                        </tr>   
					                                    <?php } ?>                                     
					                                    </tbody>
					                                </table>
					                            </div>
					                        </div>

					                        <div class="panel panel-default">
					                            <div class="panel-heading"><h4 class="panel-title">Non Educations</h4></div>
					                            <div class="panel-body">
					                                <table class="table table-bordered">
					                                    <thead>
					                                        <tr>
					                                            <th width="5%">No</th>
					                                            <th>Institute Name</th>
					                                            <th>Subject</th>
					                                            <th>Start Even</th>
					                                            <th>End Event</th>
					                                            <th>Country/City</th>
					                                        </tr>
					                                    </thead>
					                                    <tbody>
					                                    <?php if(!empty($request->MyEducationNonFormal)){ $no = 1;
					                                    for ($k=0; $k < count($request->MyEducationNonFormal); $k++) { ?>
					                                        <tr>
					                                            <td><?=$no++?></td>
					                                            <td><?=$request->MyEducationNonFormal[$k]->instituteName?></td>
					                                            <td><?=$request->MyEducationNonFormal[$k]->subject?></td>
					                                            <td><?=date("d F Y",strtotime($request->MyEducationNonFormal[$k]->start_event))?></td>
					                                            <td><?=date("d F Y",strtotime($request->MyEducationNonFormal[$k]->end_event))?></td>
					                                            <td><?=$request->MyEducationNonFormal[$k]->location?></td>
					                                        </tr>
					                                    <?php } }else{ ?>
					                                        <tr>
					                                            <td colspan="7">Empty data</td>
					                                        </tr>   
					                                    <?php } ?>                                     
					                                    </tbody>
					                                </table>
					                            </div>
					                        </div>
					                    </div>
			    					</div>
			    				</div>
			    			</div>
			    		</div>

			    	</div>
			    </div>
			    <div role="tabpanel" class="tab-pane" id="training">
			    	<div class="row">
			    		<div class="col-sm-12">
			    			<div class="origin-data">
			    				<div class="panel panel-default">
			    					<div class="panel-heading">
			    						<h4 class="panel-title">Original Data</h4>
			    					</div>
			    					<div class="panel-body">
			    						<div class="data">
					                        <table class="table table-bordered">
					                            <thead>
					                                <tr>
					                                    <th width="5%">No</th>
					                                    <th>Training Title</th>
					                                    <th>Organizer</th>
					                                    <th>Start Event</th>
					                                    <th>End Event</th>
					                                    <th>Location</th>
					                                    <th colspan="2">Cost</th>
					                                    <th>Certificate</th>
					                                    <th>Category</th>
					                                </tr>
					                            </thead>
					                            <tbody>
					                            <?php if(!empty($origin->MyEducationTraining)){ $no=1;
					                            for ($a=0; $a < count($origin->MyEducationTraining); $a++) { ?>
					                                <tr>
					                                    <td><?=$no++?></td>
					                                    <td><?=$origin->MyEducationTraining[$a]->name?></td>
					                                    <td><?=$origin->MyEducationTraining[$a]->organizer?></td>
					                                    <td><?=date("d F Y H:i:s",strtotime($origin->MyEducationTraining[$a]->start_event))?></td>
					                                    <td><?=date("d F Y H:i:s",strtotime($origin->MyEducationTraining[$a]->end_event))?></td>
					                                    <td><?=$origin->MyEducationTraining[$a]->location?></td>
					                                    <td>Rp <?=number_format($origin->MyEducationTraining[$a]->costCompany,2,',','.')?></td>
					                                    <td>Rp <?=number_format($origin->MyEducationTraining[$a]->costEmployee,2,',','.')?></td>
					                                    <td class="text-center"><?php 
					                                    if(!empty($origin->MyEducationTraining[$a]->certificate)){
					                                        $folderPath = base_url("uploads/profile/training/".$origin->MyEducationTraining[$a]->certificate);
					                                        echo '<img src="'.$folderPath.'" width="50px" height="50px">';
					                                    } ?>
					                                    </td>
					                                    <td><?=$origin->MyEducationTraining[$a]->category?></td>
					                                </tr>
					                            <?php } }else{ ?>
					                                <tr>
					                                    <td colspan="12">Empty data</td>
					                                </tr>
					                            <?php } ?>
					                            </tbody>
					                        </table>
					                    </div>
			    					</div>
			    				</div>
			    			</div>
			    		</div>
			    		<div class="col-sm-12">
			    			<div class="request-data">
			    				<div class="panel panel-default">
			    					<div class="panel-heading">
			    						<h4 class="panel-title">Request Data</h4>
			    					</div>
			    					<div class="panel-body">
			    						<div class="data">
					                        <table class="table table-bordered">
					                            <thead>
					                                <tr>
					                                    <th width="5%">No</th>
					                                    <th>Training Title</th>
					                                    <th>Organizer</th>
					                                    <th>Start Event</th>
					                                    <th>End Event</th>
					                                    <th>Location</th>
					                                    <th colspan="2">Cost</th>
					                                    <th>Certificate</th>
					                                    <th>Category</th>
					                                </tr>
					                            </thead>
					                            <tbody>
					                            <?php if(!empty($request->MyEducationTraining)){ $no=1;
					                            for ($a=0; $a < count($request->MyEducationTraining); $a++) { ?>
					                                <tr>
					                                    <td><?=$no++?></td>
					                                    <td><?=$request->MyEducationTraining[$a]->name?></td>
					                                    <td><?=$request->MyEducationTraining[$a]->organizer?></td>
					                                    <td><?=date("d F Y H:i:s",strtotime($request->MyEducationTraining[$a]->start_event))?></td>
					                                    <td><?=date("d F Y H:i:s",strtotime($request->MyEducationTraining[$a]->end_event))?></td>
					                                    <td><?=$request->MyEducationTraining[$a]->location?></td>
					                                    <td>Rp <?=number_format($request->MyEducationTraining[$a]->costCompany,2,',','.')?></td>
					                                    <td>Rp <?=number_format($request->MyEducationTraining[$a]->costEmployee,2,',','.')?></td>
					                                    <td class="text-center"><?php 
					                                    if(!empty($request->MyEducationTraining[$a]->certificate)){
					                                        $folderPath = base_url("uploads/profile/training/".$request->MyEducationTraining[$a]->certificate);
					                                        echo '<a href="'.$folderPath.'" target="_blank"><img src="'.$folderPath.'" width="50px" height="50px"></a>';
					                                    } ?>
					                                    </td>
					                                    <td><?=$request->MyEducationTraining[$a]->category?></td>
					                                </tr>
					                            <?php } }else{ ?>
					                                <tr>
					                                    <td colspan="12">Empty data</td>
					                                </tr>
					                            <?php } ?>
					                            </tbody>
					                        </table>
					                    </div>
			    					</div>
			    				</div>
			    			</div>
			    		</div>
			    	</div>
			    </div>
			    <div role="tabpanel" class="tab-pane" id="work-experience">
			    	<div class="row">
			    		<div class="col-sm-12">
			    			<div class="panel panel-default">
			    				<div class="panel-heading">
			    					<h4 class="panel-title">Original Data</h4>
			    				</div>
			    				<div class="panel-body">
			    					<div class="data">
				                        <table class="table table-bordered">
				                            <thead>
				                                <tr>
				                                    <th width="5%">No</th>
				                                    <th>Company Name</th>
				                                    <th>Industries</th>
				                                    <th>Start Join</th>
				                                    <th>End Join</th>
				                                    <th>Job Title</th>
				                                    <th>Reason Exit</th>
				                                </tr>
				                            </thead>
				                            <tbody>
				                            <?php if(!empty($origin->MyExperience)){ $no=1; 
				                            for ($b=0; $b < count($origin->MyExperience); $b++) { ?>
				                                <tr>
				                                    <td><?=$no++?></td>
				                                    <td><?=$origin->MyExperience[$b]->company?></td>
				                                    <td><?=labelProfileDB("db_employees.master_industry_type", array("ID"=>$origin->MyExperience[$b]->industryID))->name?></td>
				                                    <td><?=date("d F Y",strtotime($origin->MyExperience[$b]->start_join))?></td>
				                                    <td><?=date("d F Y",strtotime($origin->MyExperience[$b]->end_join))?></td>
				                                    <td><?=$origin->MyExperience[$b]->jobTitle?></td>
				                                    <td><?=$origin->MyExperience[$b]->reason?></td>
				                                </tr>
				                            <?php } }else{ ?>
				                                <tr>
				                                    <td colspan="7">Empty data</td>
				                                </tr>
				                            <?php } ?>
				                            </tbody>
				                        </table>
				                    </div>
			    				</div>
			    			</div>
			    		</div>
			    		<div class="col-sm-12">
			    			<div class="panel panel-default">
			    				<div class="panel-heading">
			    					<h4 class="panel-title">Request Data</h4>
			    				</div>
			    				<div class="panel-body">
			    					<div class="data">
				                        <table class="table table-bordered">
				                            <thead>
				                                <tr>
				                                    <th width="5%">No</th>
				                                    <th>Company Name</th>
				                                    <th>Industries</th>
				                                    <th>Start Join</th>
				                                    <th>End Join</th>
				                                    <th>Job Title</th>
				                                    <th>Reason Exit</th>
				                                </tr>
				                            </thead>
				                            <tbody>
				                            <?php if(!empty($request->MyExperience)){ $no=1; 
				                            for ($b=0; $b < count($request->MyExperience); $b++) { ?>
				                                <tr>
				                                    <td><?=$no++?></td>
				                                    <td><?=$request->MyExperience[$b]->company?></td>
				                                    <td><?=labelProfileDB("db_employees.master_industry_type", array("ID"=>$request->MyExperience[$b]->industryID))->name?></td>
				                                    <td><?=date("d F Y",strtotime($request->MyExperience[$b]->start_join))?></td>
				                                    <td><?=date("d F Y",strtotime($request->MyExperience[$b]->end_join))?></td>
				                                    <td><?=$request->MyExperience[$b]->jobTitle?></td>
				                                    <td><?=$request->MyExperience[$b]->reason?></td>
				                                </tr>
				                            <?php } }else{ ?>
				                                <tr>
				                                    <td colspan="7">Empty data</td>
				                                </tr>
				                            <?php } ?>
				                            </tbody>
				                        </table>
				                    </div>
			    				</div>
			    			</div>
			    		</div>
			    	</div>			    	
			    </div>
			    <div role="tabpanel" class="tab-pane" id="signature">
			    	<div class="row">
			    	<div class="col-sm-6">
			    		<div class="panel panel-default">
				    		<div class="panel-heading">
				    			<h4 class="panel-title">Original Data</h4>
				    		</div>
				    		<div class="panel-body">
				    			<?php if(!empty($origin->Signature)){?>
				    			<img src="<?=base_url('./uploads/signature/'.$origin->Signature)?>" width="200px" class="img-thumbnail" >
				    			<?php } ?>
				    		</div>
				    	</div>
			    	</div>
			    	<div class="col-sm-6">
			    		<div class="panel panel-default">
				    		<div class="panel-heading">
				    			<h4 class="panel-title">Request Data</h4>
				    		</div>
				    		<div class="panel-body">
				    			<?php if(!empty($request->Signature)){?>
				    			<img src="<?=$request->Signature?>" width="200px" class="img-thumbnail" >
				    			<?php } ?>
				    		</div>
				    	</div>
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
				  		<button class="btn btn-sm btn-primary btn-act" type="button" data-act="0" data-nip="<?=$NIP?>" ><i class="fa fa-check"></i> Accept</button>
				  		<button class="btn btn-sm btn-danger btn-act" type="button" data-act="2" data-nip="<?=$NIP?>"  ><i class="fa fa-times"></i> Reject</button>
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
			if(ACT == 2){
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
			}else if(ACT == 0){
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
		              url : base_url_js+"human-resources/employee-request-appv",
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

		$getTabPane = $("#modal-merge-req .modal-body .tab-content > .tab-pane");
		$.each($getTabPane,function(){
			var itsme = $(this);
			var idName = itsme.attr("id");

			//check label is differnt data
			$getOrigin 	= $("#modal-merge-req #"+idName+" .origin-data .form-group");
			$getRequest = $("#modal-merge-req #"+idName+" .request-data .form-group");
			$.each($getRequest,function(){
				var itsReq = $(this);
				var label = itsReq.find("label").text();
				var value = itsReq.find("p").text();
				$.each($getOrigin,function(){
					var itsOri = $(this);
					var rLabel = itsOri.find("label").text();
					var rValue = itsOri.find("p").text();
					if(rLabel == label){
						if(value != rValue){
							itsReq.addClass("different");						
						}
					}
				});
			});
		});
		
	});
</script>
