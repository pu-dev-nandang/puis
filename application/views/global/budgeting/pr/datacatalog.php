<div id = "ctcatalog">
	
</div>
<script type="text/javascript">
	$(document).ready(function() {
		LoadFirst();

		function LoadFirst()
		{
				var html = '';
				html ='<div class = "row">'+
						'<div class = "col-md-12">'+
							'<table id="example" class="table table-bordered display select" cellspacing="0" width="100%">'+
	               '<thead>'+
	                  '<tr>'+
	                     '<th>No</th>'+
	                     '<th>Item</th>'+
	                     '<th>Desc</th>'+
	                     '<th>Estimate Value</th>'+
	                     '<th>Photo</th>'+
	                     '<th>DetailCatalog</th>'+
	                     '<th>Action</th>'+
	                  '</tr>'+
	               '</thead>'+
	          '</table></div></div>';

	          	$("#ctcatalog").html(html);
	          	var sessIDDepartementPUBudget = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";

	          	var url = base_url_js+'rest/Catalog/__Get_Item';
	          	var data = {
	          		action : 'choices',
	          		auth : 's3Cr3T-G4N',
	          		department : sessIDDepartementPUBudget,
	          	};
	              var token = jwt_encode(data,"UAP)(*");
	          	var table = $('#example').DataTable({
	          	      'ajax': {
	          	         'url': url,
	          	         'type' : 'POST',
	          	         'data'	: {
	          	         	token : token,
	          	         },
	          	         dataType: 'json'
	          	      },
	          	      'columnDefs': [{
	          	         'targets': 0,
	          	         'searchable': false,
	          	         'orderable': false,
	          	         'className': 'dt-body-center',
	          	         'render': function (data, type, full, meta){
	          	         	 console.log(full);
	          	             return '<input type="checkbox" name="id[]" value="' + full[6] + '" estvalue="' + full[7] + '">';
	          	         }
	          	      }],
	          	      'order': [[1, 'asc']]
	          	   });
		}
	}); // exit document Function

</script>