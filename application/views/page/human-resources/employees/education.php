<script type="text/javascript">
    $(document).ready(function(){
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-edu").addClass("active");
    });
</script>
<form id="form-additional-info" action="" method="post" autocomplete="off">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-edit"></i> Please fill up this form with correctly data</h4>
        </div>
        <div class="panel-body">
        	<div class="row">
        		<div class="col-sm-12">
        			<div class="panel panel-default" id="multiple-field" data-source="educations">
                        <div class="panel-heading">
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button class="btn btn-default btn-xs btn-add" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button class="btn btn-default btn-xs btn-remove" type="button">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <h4 class="panel-title">
                                Educations
                            </h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered" id="table-list-educations">
                                <thead>
                                    <tr>
                                        <td width="2%">No</td>
                                        <td>Level Education</td>
                                        <td>Institute Name</td>
                                        <td>Country/City</td>
                                        <td>Major</td>
                                        <td>Graduation Year</td>
                                        <td>GPA</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="hidden" class="form-control" name="eduID[]">
                                            <select class="form-control required" required name="eduLevel[]" >
                                                <option value="">Choose one</option>                                                            
                                                <?php if(!empty($educationLevel)){
                                                foreach ($educationLevel as $v) {
                                                echo '<option value="'.$v->ID.'">'.$v->Level.'</option>';
                                                } } ?>
                                            </select>
                                            <small class="text-danger text-message"></small>
                                        </td>
                                        <td><input type="text" class="form-control required" required name="eduInstitute[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control required" required name="eduCC[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control required" required name="eduMajor[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control required" required name="eduGraduation[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control required" required name="eduGPA[]">
                                        <small class="text-danger text-message"></small></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
        		</div>
        	</div>
        	
        	<div class="row">
        		<div class="col-sm-12">
        			<div class="panel panel-default" id="multiple-field" data-source="non-educations">
                        <div class="panel-heading">
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button class="btn btn-default btn-xs btn-add" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button class="btn btn-default btn-xs btn-remove" type="button">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <h4 class="panel-title">
                                Non Formal Educations
                            </h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered" id="table-list-non-educations">
                                <thead>
                                    <tr>
                                        <td width="2%">No</td>
                                        <td>Institute Name</td>
                                        <td>Subject</td>
                                        <td>Start Event</td>
                                        <td>End Event</td>
                                        <td>Certificate</td>
                                        <td>Country/City</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="hidden" class="form-control" name="nonEduID[]">
                                        <input type="text" class="form-control" name="nonEduduInstitute[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control" name="nonEduSubject[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control" id="nonEduStart" name="nonEduStart[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control" id="nonEduEnd" name="nonEduEnd[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="file" name="nonEduCertificate[]"></td>
                                        <td><input type="text" class="form-control" name="nonEduCC[]">
                                        <small class="text-danger text-message"></small></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
        		</div>
        	</div>

        	<div class="row">
        		<div class="col-sm-12">
        			<div class="panel panel-default" id="multiple-field" data-source="training">
                        <div class="panel-heading">
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button class="btn btn-default btn-xs btn-add" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button class="btn btn-default btn-xs btn-remove" type="button">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <h4 class="panel-title">
                                Training
                            </h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered" id="table-list-training">
                                <thead>
                                    <tr>
                                        <td width="2%">No</td>
                                        <td>Training Title</td>
                                        <td>Trainer Name</td>
                                        <td>Start Event</td>
                                        <td>End Event</td>
                                        <td>Location</td>
                                        <td>Result Feedback</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="hidden" class="form-control" name="nonEduID[]">
                                        <input type="text" class="form-control" name="trainingTitle[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control" name="trainingTrainer[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control" id="trainingStart" name="trainingStart[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control" id="trainingEnd" name="trainingEnd[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control" name="trainingLocation[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control" name="trainingFeedback[]">
                                        <small class="text-danger text-message"></small></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
        		</div>
        	</div>

        	
        </div>
        <div class="panel-footer text-right">
            <button class="btn btn-success" type="button">Save changes</button>
        </div>
    </div>
</form>