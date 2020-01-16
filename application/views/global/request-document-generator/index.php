<div class="row hide" id = "rowChooseDocument">
	<div class="col-md-4 col-md-offset-4">
		<div class="thumbnail">
			<div class="form-group">
				<label>Choose Document</label>
				<select class="form-control" id = "MasterSurat"></select>
			</div>
		</div>
	</div>
</div>

<div class="row" style="margin-top: 10px;">
	<div class="col-md-6">
		<?php echo $table; ?>
	</div>
	<div class="col-md-6">
		<?php echo $form_input; ?>
	</div> 
</div>
<script type="text/javascript">
	var otable;
	$(document).ready(function(){
		var selectorMasterSurat = $('#MasterSurat');
		LoadMasterSuratOP(selectorMasterSurat);
	})

	$(document).off('change', '#MasterSurat').on('change', '#MasterSurat',function(e) {
		var IDMasterSurat = $(this).find('option:selected').val();
		var TokenData = $(this).find('option:selected').attr('datatoken');
		if (IDMasterSurat != '-') {
			if (typeof App_input !== 'undefined') {
			    App_input.DomRequestDocument(IDMasterSurat,TokenData);
			}

			if (typeof App_table !== 'undefined') {
			    App_table.DomListRequestDocument(IDMasterSurat,TokenData);
			}
		}
		else
		{
			if (typeof App_input !== 'undefined') {
			    App_input.Loaded();
			}

			if (typeof App_table !== 'undefined') {
			    App_table.Loaded();
			}
		}
	})
</script>