<script type="text/javascript">
    $(document).ready(function(){
        $("#form-employee .tabulasi-emp > ul > li").removeClass("active");
        $("#form-employee .tabulasi-emp > ul > li.nv-experience").addClass("active");
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
        			<div class="panel panel-default" id="multiple-field" data-source="experience">
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
                            <h4 class="panel-title">Work Experience</h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered" id="table-list-experience">
                                <thead>
                                    <tr>
                                        <th width="2%">No</th>
                                        <th>Company Name</th>
                                        <th>Industries</th>
                                        <th>Strat Join</th>
                                        <th>End Join</th>
                                        <th>Job Title</th>
                                        <th>Reason Exit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="hidden" class="form-control" name="comID[]">
                                        <input type="text" class="form-control required" required name="comName[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td class="industries"><select class="required" required name="comIndustry[]" id="company-industry">
                                                <option value="">Choose one</option>
                                                <?php if(!empty($industry)){
                                                foreach ($industry as $in) { 
                                                    echo '<option value="'.$in->ID.'">'.$in->name.'</option>';
                                                } } ?>
                                            </select>
                                            <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control required" required id="company-start-date" name="comStartJoin[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control required" required id="company-end-date" name="comEndJoin[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control required" required name="comJobTitle[]">
                                        <small class="text-danger text-message"></small></td>
                                        <td><input type="text" class="form-control required" required name="comReason[]">
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