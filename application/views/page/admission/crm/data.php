<style type="text/css">
	/* Button Upload */
	input[type="file"] {
	    display: none;
	}
	.custom-file-upload {
	    border: 1px solid #ccc;
	    display: inline-block;
	    padding: 3px 3px;
	    cursor: pointer;
	} 

	.table thead {
	    background: #d91f2d;
	    color: #fff;
	}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="row" style="margin-left: 0px;margin-right: 0px">
			<div class="col-md-1">
				<label class="btn btn-default"><i class="fa fa-upload" aria-hidden="true"></i> Upload <input class = "file-upload" type="file" style="display: none;"></label>
				</br>
				<a href="<?php echo base_url('download_template/admisi-t_crm_import.xlsx'); ?>">File Template</a>
			</div>
			<!-- <div class="col-md-1">
				<button class="btn btn-default" id = "ExportEx"> <i class="fa fa-download" aria-hidden="true"></i> Download</button>
			</div> -->
		</div>
	</div>
</div>
<div class="row" style="margin-top: 10px;margin-left: 0px;margin-right: 0px">
	<div class="col-md-12">
		<div class="widget box">
		    <div class="widget-header">
		        <h4 class="header"><i class="icon-reorder"></i></h4>
		        <!-- <div class="toolbar no-padding">
		            <div class="btn-group">
		              <span data-smt="" class="btn btn-xs btn-add-event btn-add">
		                <i class="icon-plus"></i> Add
		               </span>
		            </div>
		        </div> -->
		    </div>
		    <div class="widget-content">
		        <div class="row">
		        	<div class="col-md-12">
		        		<div class="table-responsive">
		        			<table class="table table-bordered tableData" id ="tableData3">
		        				<thead>
		        					<tr>
		        						<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">ID</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Candidate Name</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Regional</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">School</th>
		        						<!-- <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Class</th> -->
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Pathway</th>
		        						<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">Gender</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Prospect Year</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Phone</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">MobilePhone</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Email</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">ParentName</th>
		        						<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Action</th>
		        					</tr>
		        				</thead>
		        				<tbody>
		        				</tbody>
		        			</table>
		        		</div>
		        	</div>
		        </div>
		        <!-- -->
		    </div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		loadtable();
	})
</script>