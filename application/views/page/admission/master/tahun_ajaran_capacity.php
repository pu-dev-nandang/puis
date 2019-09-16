<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div style="text-align: center">
			<strong><?php echo 'Academic Year '.$NamePeriod ?></strong>
		</div>
		<br/>
		<table class="table table-bordered table-striped" id = "tbldata">
			<thead>
				<tr>
					<th>Program Studi</th>
					<th>Capacity</th>
				</tr>
			</thead>
			<tbody>
				<?php for ($i=0; $i < count($G_data); $i++): ?>
					<tr>
						<td>
							<?php echo $G_data[$i]['ProdiNameInd'] ?>
						</td>
						<td>
							<input type="text" name="Capacity" class="form-control Capacity" value="<?php echo $G_data[$i]['Capacity'] ?>" data-id="<?php echo $G_data[$i]['ID_ta_setting'] ?>">
						</td>
					</tr>
				<?php endfor; ?>	
			</tbody>
		</table>
		<br/>
		<div align="right">
			<button class="btn btn-block btn-success" id="btnSave">Save</button>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('.Capacity').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
		$('.Capacity').maskMoney('mask', '9894');
	});

	$(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
		loading_button('#btnSave');
		$('.Capacity').prop('disabled',true);
		var arr_pass = [];
		$('.Capacity').each(function(){
			var Capacity = parseInt(findAndReplace($(this).val(), ".",""));
			var DataID = $(this).attr('data-id');
			var temp = {
				Capacity : Capacity,
				ID : DataID,
			}
			arr_pass.push(temp);
		})

		var url = base_url_js+"admission/config/save_capacity_tahun_ajaran";
		var token = jwt_encode(arr_pass,"UAP)(*");
		$.post(url,{token:token},function (resultJson) {
			toastr.success('Data Saved');
			setTimeout(function () {
				location.reload();
			},1500);
			
		}).fail(function() {
		  toastr.info('Error');
		})
	})
</script>