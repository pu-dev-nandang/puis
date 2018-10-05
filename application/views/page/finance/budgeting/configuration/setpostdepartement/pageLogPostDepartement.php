<style type="text/css">
	#tableData4 thead th,#tableData4 tfoot td {

	    text-align: center;
	    background: #20485A;
	    color: #FFFFFF;

	}

	#tableData4>thead>tr>th, #tableData4>tbody>tr>th, #tableData4>tfoot>tr>th, #tableData4>thead>tr>td, #tableData4>tbody>tr>td, #tableData4>tfoot>tr>td {
	    border: 1px solid #b7b7b7
	}
</style>
<div class="col-md-12">
	<div class="table-responsive" id = "DivTable">
		
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		LoadFirstLoad()
	    
	}); // exit document Function

	function LoadFirstLoad()
	{
		LoadDataForTable();
	}

	function LoadDataForTable()
	{
		$("#DivTable").empty();
		var table = '<table class="table table-bordered datatable2" id = "tableData4">'+
		            '<thead>'+
		            '<tr>'+
		                '<th>Code</th>'+
		                '<th>Post Realization</th>'+
		                '<th>Action By</th>'+
		                '<th>Action Time</th>'+
		                '<th>Detail</th>'+
		            '</tr>'+
		            '</thead>'+
		            '<tbody id="dataRow"></tbody>'+
		        '</table>';
		$("#DivTable").html(table);

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

		var table = $('#tableData4').DataTable( {
		    "processing": true,
		    "destroy": true,
		    "serverSide": true,
		    "iDisplayLength" : 25,
		    "ordering" : false,
		    "ajax":{
		        url : base_url_js+"budgeting/DataLogPostDepartement", // json datasource
		        ordering : false,
		        type: "post",  // method  , by default get
		        // data : {length : $("select[name='tableData4_length']").val()},
		        error: function(){  // error handling
		            $(".employee-grid-error").html("");
		            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
		            $("#employee-grid_processing").css("display","none");
		        }
		    },
		    'createdRow': function( row, data, dataIndex ) {
		          // if(data[6] == 'Lunas')
		          // {
		          //   $(row).attr('style', 'background-color: #8ED6EA; color: black;');
		          // }
		    },
		} );
	}
</script>