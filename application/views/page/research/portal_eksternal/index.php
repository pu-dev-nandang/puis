<script type="text/javascript" src="<?php echo base_url('js/research/portal-eksternal/Clas_portal_eksternal.js'); ?>"></script>
<style type="text/css">
	.#pageList .dataTables_wrapper {
	        /*border:solid #000 !important;*/
	        border-width:1px 0 0 1px !important;
	        font-size: 12px;
	}
    #pageList .dataTables_wrapper {
      /*border:solid #000 !important;*/
      border-width:0 1px 1px 0 !important;
    }

    #pageList .dataTables_wrapper li {
    	font-size: 11px;
    }
</style>
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
			$('#pageAccess').html(App_portal.htmlPass());
		}
		else if(v == 'Mahasiswa') {
			$('#pageBiodata').html(App_portal.htmlProfileMHS());
			$('#pageTypeAs').html(App_portal.htmlTypeAsMHS());
			$('#pageAccess').html(App_portal.htmlPass());
		}
		else
		{
			$('#pageBiodata').html(App_portal.htmlProfileMHS());
			$('#pageTypeAs').html(App_portal.htmlTypeAsNonMHS());
			$('#pageAccess').html(App_portal.htmlPass());
		}

		let selectorUniversity = $('#pageBiodata').find('.FrmRegistrasi[name="ID_University"]');
		App_portal.__OPUniversity(selectorUniversity);
		$('.datetimepicker').datetimepicker({
			format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
		});
	})

	$(document).off('change','.FilterOPtypeUser').on('change','.FilterOPtypeUser',function(e){
		App_portal.dataTableList.ajax.reload(null, false);
	})

	$(document).off('click','#btnSaveEksternal').on('click','#btnSaveEksternal',async function(e){
		const selector = $(this);
		const action = selector.attr('action');
		const ID = selector.attr('data-id');
		await App_portal.savePortalEksternal(selector,action,ID);
		
	})

	$(document).off('click','.FrmRegistrasi[name="Password"]').on('click','.FrmRegistrasi[name="Password"]',function(e){
		let tgl = $('.FrmRegistrasi[name="Tgl_lahir"]').val();
		if (tgl != '') {
			let getSplit = tgl.split('-');
			$(this).val(getSplit[2]+getSplit[1]+getSplit[0].substring(2,4));
		}
	})

	$(document).off('click','.btnRemovetEksternal').on('click','.btnRemovetEksternal',async function(e){
		const selector = $(this);
		const decodetoken = jwt_decode(selector.attr('token'));
		const action = 'delete';
		const ID = decodetoken['ID'];
		await App_portal.savePortalEksternal(selector,action,ID);
	})
</script>