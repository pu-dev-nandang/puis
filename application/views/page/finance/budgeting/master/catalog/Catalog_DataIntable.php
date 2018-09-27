<style type="text/css">
	#datatablesServer thead th,#datatablesServer tfoot td {

	    text-align: center;
	    background: #20485A;
	    color: #FFFFFF;

	}

	#datatablesServer>thead>tr>th, #datatablesServer>tbody>tr>th, #datatablesServer>tfoot>tr>th, #datatablesServer>thead>tr>td, #datatablesServer>tbody>tr>td, #datatablesServer>tfoot>tr>td {
	    border: 1px solid #b7b7b7
	}
</style>
<div class="row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px">
	<div class="col-xs-12">
		<div class="table-responsive">
			<table class="table table-bordered tableData" id ="datatablesServer">
				<thead>
					<tr>
						<th width = "3%">No</th>
						<th>Item</th>
						<th>Desc</th>
						<th>Estimate Value</th>
						<th>Photo</th>
						<th>Departement</th>
						<th>DetailCatalog</th>
						<th>CreatedBy</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$.fn.dataTable.ext.errMode = 'throw';
		//alert('hsdjad');
		$.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
		          {
		              return {
		                  "iStart": oSettings._iDisplayStart,
		                  "iEnd": oSettings.fnDisplayEnd(),
		                  "iLength": oSettings._iDisplayLength,
		                  "iTotal": oSettings.fnRecordsTotal(),
		                  "iFilteredTotal": oSettings.fnRecordsDisplay(),
		                  "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
		                  "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
		              };
		          };

		var dataTable = $('#datatablesServer').DataTable( {
		    "processing": true,
		    "destroy": true,
		    "serverSide": true,
		    "iDisplayLength" : 10,
		    "ordering" : false,
		    "ajax":{
		        url : base_url_js+"budgeting/page/catalog/DataIntable/server_side", // json datasource
		        ordering : false,
		        type: "post",  // method  , by default get
		        error: function(){  // error handling
		            $(".employee-grid-error").html("");
		            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
		            $("#employee-grid_processing").css("display","none");
		        }
		    },
		    'createdRow': function( row, data, dataIndex ) {
		        /*var no = 'row'+(dataIndex + 1);
		          $(row).attr('id', no);*/
		    },
		} ); 
	}); // exit document Function
</script>