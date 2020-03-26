<script type="text/javascript" src="<?php echo base_url('js/research/portal-eksternal/Clas_portal_eksternal.js'); ?>"></script>
<div id="pageDetailBox">
	
</div>
<div class="row">
	<div class="col-xs-8">
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-primary" style="border-color: #42a4ca;">
					<div class="panel-heading clearfix" style="background-color: #42a4ca;border-color: #42a4ca;">
						<h4 class="panel-title pull-left" style="padding-top: 7.5px;">Registration</h4>
					</div>
					<div class="panel-body" id ="pageInput">
						
					</div>										
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-4">
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-primary" style="border-color: #42a4ca;">
					<div class="panel-heading clearfix" style="background-color: #42a4ca;border-color: #42a4ca;">
						<h4 class="panel-title pull-left" style="padding-top: 7.5px;">List</h4>
					</div>
					<div class="panel-body" id ="pageList">
						
					</div>										
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	const App_portal = new Clas_portal_eksternal();
	$(document).ready(function(e){
		const selectorPageInput = $('#pageInput');
		const selectorPageList = $('#pageList');
		App_portal.LoadPageDefault(selectorPageInput,selectorPageList);
	})

	$(document).off('change','.OPtypeUser').on('change','.OPtypeUser',function(e){
		let v = $(this).find('option:selected').val();
		if (v == 'Dosen') {
			$('#pageBiodata').html(App_portal.htmlProfiledosen());
			$('#pageTypeAs').html(App_portal.htmlTypeAsNonMHS());
		}
		else if(v == 'Mahasiswa') {
			$('#pageBiodata').html(App_portal.htmlProfileMHS());
			$('#pageTypeAs').html(App_portal.htmlTypeAsMHS());
		}
		else
		{
			$('#pageBiodata').html(App_portal.htmlProfileMHS());
			$('#pageTypeAs').html(App_portal.htmlTypeAsNonMHS());
		}

		let selectorUniversity = $('#pageBiodata').find('.FrmRegistrasi[name="ID_University"]');
		App_portal.__OPUniversity(selectorUniversity);
	})
</script>