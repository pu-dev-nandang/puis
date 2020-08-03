<?php 
        $DivisionID = $this->m_master->getSessionDepartmentPU();
	
 ?>
 <?php if ($DivisionID == "NA.12" || $DivisionID == 'NA.2'): ?>
 	<div class="row">
 		<div class="col-md-3"> <a href="<?php echo base_url().'rektorat/monthly_report/setting' ?>"  style="width: 100%;" class="btn btn-primary">Setting</a></div>
 	</div>
 <?php endif ?>


<div class="row" style="margin-top: 10px;">
	<div class="col-md-3 panel-admin" style="border-right: 1px solid #CCCCCC;">
		<?php echo $InputForm ?>
	</div>
	<div class="col-md-9">
		<div class="thumbnail" style="min-height: 50px;">
			<div class="row">
			    <div class="col-md-12">
			        <?php echo $ViewTable ?>
			    </div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var oTable;
</script>