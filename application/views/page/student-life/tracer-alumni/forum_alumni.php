<style type="text/css">
    #tbl_Alumni_Choose.dataTable tbody tr:hover {
       background-color:#71d1eb !important;
       cursor: pointer;
    }
</style>
<div class="row">
	<div class="col-xs-12">
		<div style="text-align: left;">
            <h3 class="heading-small">List Topic</h3>
            <hr>
        </div>
        <table class="table table-striped table-hover" id="tableTopic">
        	<thead>
        		<tr>
        			<th>No</th>
        			<th>Topic</th>
        			<th>Users</th>
        			<th>Comments</th>
        			<th><i class="fa fa-cog"></i></th>
        		</tr>
        	</thead>
        	<tbody>
        		
        	</tbody>
        </table>
	</div>
</div>
<script type="text/javascript">
	// deklarasi rest setting
	const CustomPost = <?php echo json_encode($customPost) ?>;
</script>
<script type="text/javascript" src="<?php echo base_url();?>js/student-life/tracer-alumni/App_forum_alumni.js"></script>
<script type="text/javascript">
	let AppThis = new App_forum_alumni();
	$(document).ready(function(e){
		let selectorTbl = $('#tableTopic');
		AppThis.LoadDefault(selectorTbl);
	})

	$(document).off('click','.btnG_user').on('click','.btnG_user',function(e){
		let token = $(this).attr('data');
		let de = jwt_decode(token);
		AppThis.showModalUser(de);
	})

	$(document).off('click','.btn-add-topic').on('click','.btn-add-topic',function(e){
		AppThis.makeForm_action();
	})

	$(document).off('click','.btnSelectionAlumni').on('click','.btnSelectionAlumni',function(e){
		let datatoken = jwt_decode( $(this).closest('tr').attr('datatoken') );
		AppThis.add_Alumni_Selected(datatoken);
	})

	$(document).off('click','.removeAddAlumniSelected').on('click','.removeAddAlumniSelected',function(e){
		let selector = $(this);
		let dataDecode = jwt_decode( $(this).closest('tr').attr('set_token'));
		AppThis.remove_Alumni_Selected(selector,dataDecode);
	})

	$(document).off('click','.btnSaveModalAlumni').on('click','.btnSaveModalAlumni',function(e){
		let itsme = $(this);
		let action = itsme.attr('action');
		let ID = itsme.attr('data-id');
		AppThis.submit_form_action(itsme,action,ID);
	})
</script>