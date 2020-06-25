<div id="stock-good">
	<div class="row">
		<div class="col-sm-12">
			<p><a class="btn btn-warning" href="<?=base_url('prodi/stock-good')?>"> <i class="fa fa-chevron-left"></i> Back to list</a></p>
		</div>
		<div class="col-sm-12">
			<form action="<?=base_url('prodi/save-stock-good')?>" method="post" autocomplete="off" id="form-stock-good">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"><i class="fa fa-edit"></i> Form Stock Good</h4>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label>Purchasing Order Code</label>
							<input type="text" class="form-control required" name="Code">
							<small class="text-danger text-message"></small>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<div class="btn-group pull-right">
											<button class="btn btn-xs btn-default btn-plus" type="button"><i class="fa fa-plus"></i></button>
										</div>
										<h4 class="panel-title"><i class="fa fa-cart-plus"></i> Form Items</h4>
									</div>
									<div class="panel-body">
										<table class="table table-bordered table-purchase-item">
											<thead>
												<tr>
													<th width="5%">No</th>
													<th>Item Name</th>
													<th width="10%">Quantity</th>
													<th width="15%">Unit</th>
													<th>Note</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1</td>
													<td><input type="text" class="required form-control" name="Name[]"><small class="text-danger text-message"></small></td>
													<td><input type="text" class="required form-control number" name="Quantity[]"><small class="text-danger text-message"></small></td>
													<td><select name="UnitID[]" class="required form-control">
														<option value="">-</option>
														<?php if(!empty($units)){
														foreach ($units as $v) {
															echo '<option value="'.$v->ID.'">'.$v->Name.(!empty($v->Description) ? ' ('.$v->Description.')' : '').'</option>';		
														} } ?>
														</select><small class="text-danger text-message"></small></td>
													<td><textarea class="form-control" name="Note[]" rows="1"></textarea></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					<div class="panel-footer text-right">
						<div class="btn-group">
							<button onclick="location.href='<?=base_url('prodi/stock-good')?>'" class="btn btn-default" type="button">Cancel</button>
							<button type="button" class="btn btn-primary btn-submit">Save changes</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$("#form-stock-good").on("keyup keydown",".number",function(){
        this.value = this.value.replace(/[^0-9\.]/g,'');
    });
	$(".btn-plus").click(function(){
		$tableitem = $(".table-purchase-item");
		var totalRow = $tableitem.find("tbody > tr").length;
		$clonerow = $tableitem.find("tbody > tr:first").clone();
		$clonerow.find(".form-control").val("");
		$clonerow.find(".text-message").text("");
		var label = ' <button class="btn-xs btn-danger btn-remove-row" type="button"><i class="fa fa-trash"></i></button>';
		$clonerow.find("td:first").html( label );
		$tableitem.find("tbody").append($clonerow);
	});
	$(".table-purchase-item").on("click","tbody .btn-remove-row",function(){
		$(this).parent().parent().remove();
	});
	$("#form-stock-good .btn-submit").click(function(){
		var itsme = $(this);
        var itsform = itsme.parent().parent().parent().parent();
        itsform.find(".required").each(function(){
            var value = $(this).val();
            if($.trim(value) == ''){
                $(this).addClass("error");
                $(this).parent().find(".text-message").text("Please fill this field");
                error = false;
            }else{
                error = true;
                $(this).removeClass("error");
                $(this).parent().find(".text-message").text("");
            }
        });
        
        var totalError = itsform.find(".error").length;
        if(error && totalError == 0 ){
            loading_modal_show();
            $("#form-stock-good")[0].submit();
        }else{
            alert("Please fill out the field.");
        }
	});
</script>