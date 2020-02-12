<form id="form-sto-participants" action="" method="post" autocomplete="off">
	<div class="form-group">
		<label>Name</label>
		<input type="hidden" name="ID" value="<?=$detail->ID?>">
		<input type="text" name="title" class="form-control required" required value="<?=$detail->title?>">
	</div>
	<div class="form-group">
		<label>Description</label>
		<textarea class="form-control required" required name="description"></textarea>
	</div>
	<div class="form-group">
		<label>Active</label>
		<select class="form-control required" required name="isActive">
			<option value="">Choose one</option>
			<option value="1" <?=($detail->isActive == 1) ? 'selected':''?> >Active</option>
			<option value="0" <?=($detail->isActive == 1) ? 'selected':''?> >Not Active</option>
		</select>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<i class="fa fa-users"></i> User
			</h4>
		</div>
		<div class="panel-heading">
			<div class="row">
	            <div class="col-sm-6">
	              <div class="form-group">
	                <label>Division</label>               
	                <select class="form-control" name="division">
	                  <option value="">-Choose one-</option>
	                  <?php if(!empty($division)){ 
	                  foreach ($division as $d) {
	                  echo '<option value="'.$d->ID.'">'.$d->Division.'</option>';
	                  } } ?>
	                </select>
	              </div>

	              <div class="form-group">
	                <label>Position</label>               
	                <select class="form-control" name="position">
	                  <option value="">-Choose one-</option>
	                  <?php if(!empty($position)){ 
	                  foreach ($position as $p) {
	                  echo '<option value="'.$p->ID.'">'.$p->Description.'</option>';
	                  } } ?>
	                </select>               
	              </div>

	            </div>
				<div class="col-sm-6">
					<div class="form-groups">
	                  <label>Status employee</label>
	                </div>
	                <?php if(!empty($statusstd)) {
	                foreach ($statusstd as $t) { ?>
	                <div class="form-group">
	                  <div class="col-sm-10">
	                    <div class="checkbox">
	                      <label>
	                        <input type="checkbox" value="<?=$t->IDStatus?>" name="status[]" > <?=$t->Description?>
	                      </label>
	                    </div>
	                  </div>
	                </div>
	                <?php } } ?>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<button class="btn btn-primary btn-sm btn-search" type="button">Search</button>
				</div>
			</div>
		</div>
		<div class="panel-body">
			
		</div>
	</div>
	<div class="form-group text-right">
		<button class="btn btn-success btn-sm btn-submit" type="button">Save changes</button>
	</div>
</form>