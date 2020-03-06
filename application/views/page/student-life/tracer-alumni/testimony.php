<div class="row">
	<div class="col-xs-12">
		<div style="text-align: left;">
            <h3 class="heading-small">List Testimony</h3>
            <hr>
        </div>
        <br/>
        <div class="row">
        	<div class="col-xs-3 col-md-offset-4">
        		<div class="well">
        			<div class="row">
        				<div class="col-md-12">
        					<div class="form-group">
        						<label>Filter Status</label>
        						<select class="form-control" id = "filterStatus">
        							<option value="">All</option>
        							<option value="0">Not Approve</option>
        							<option value="1">Approved</option>
        							<option value="-1">Rejected</option>
        						</select>
        					</div>
        				</div>
        			</div>
        		</div>
        	</div>
        </div>
        <table class="table table-striped" id = "tblTestimony">
			<thead>
				<tr>
					<th>No</th>
					<th>Student & Create at</th>
					<th style="width: 40%">Testimony</th>
					<th>Status</th>
					<th><i class="fa fa-cog"></i></th>
				</tr>
			</thead> 
			<tbody></tbody>       	
        </table>
	</div>
</div>

<script type="text/javascript">
	// deklarasi rest setting
    const CustomPost = <?php echo json_encode($customPost) ?>;
    let urlID = "<?php echo $this->uri->segment(5) ?>";
</script>
<script type="text/javascript" src="<?php echo base_url();?>js/student-life/tracer-alumni/App_testimony.js"></script>
<script type="text/javascript">
	let AppThis = new App_testimony();
	$(document).ready(function(e){
		AppThis.LoadDefault();
	})

	$(document).off('click','.btnInfo').on('click','.btnInfo',function(e){
		let de = $(this).closest('tr').attr('data');
		de = jwt_decode(de);
		AppThis.__getInfo(de['info'])
	})

	$(document).off('click','.btnApprove,.btnReject').on('click','.btnApprove,.btnReject',function(e){
		let itsme = $(this);
		let de = itsme.closest('tr').attr('data');
		de = jwt_decode(de);
		let action;
		if (itsme.html() == 'Approve') {
			action = 1;
		}
		else
		{
			action = -1;
		}
		AppThis.ApproveOrReject(itsme,de['ID'],action);
	})

	$(document).off('change','#filterStatus').on('change','#filterStatus',function(e){
		AppThis.reloadTable();
	})
	
</script>