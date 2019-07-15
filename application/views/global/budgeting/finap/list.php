<div class="row btn-read">
	<div class="col-md-12">
		<div class="table-responsive" id = "DivTable">
			
		</div>
	</div>
</div>
<script type="text/javascript">
  // get Departmentpu
var IDDepartementPUBudget = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";
$(document).ready(function() {
	LoadFirstLoad();
	loadingEnd(500);
}); // exit document Function

function LoadFirstLoad()
{
	LoadDataForTable();
}

function LoadDataForTable()
{
	$("#DivTable").empty();
	var table_html = '<table class="table table-bordered" id = "tableData_po" style="width: 100%;">'+
	            '<thead>'+
	            '<tr>'+
	                '<th  width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
                  '<th  style = "text-align: center;background: #20485A;color: #FFFFFF;">Code</th>'+
                  '<th  style = "text-align: center;background: #20485A;color: #FFFFFF;">Payment</th>'+
	                '<th  style = "text-align: center;background: #20485A;color: #FFFFFF;">Department</th>'+
	                '<th  style = "text-align: center;background: #20485A;color: #FFFFFF;">Status</th>'+
              '</tr>'+
	            '</thead>'+
	            '<tbody id="dataRow"></tbody>'+
	        '</table>';
	$("#DivTable").html(table_html);
}

</script>