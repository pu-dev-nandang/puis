<div class="row btn-read">
	<div class="col-md-12">
		<div class="table-responsive" id = "DivTable">
			
		</div>
	</div>
</div>
<script type="text/javascript">
var G_Approver = <?php echo json_encode($G_Approver) ?>;
var m_type_user = <?php echo json_encode($m_type_user) ?>;
var G_ApproverLength = G_Approver.length;
var JsonStatus = [];
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
	var LoopApprover = '';
	for (var i = 0; i < G_ApproverLength; i++) {
		var ap = i +1;
		LoopApprover += '<th style = "text-align: center;background: #20485A;color: #FFFFFF;" id = "thapprover'+ap+'">'+ap+'</th>';
	}

	var table_html = '<table class="table table-bordered" id = "tableData_po" style="width: 100%;">'+
	            '<thead>'+
	            '<tr>'+
	                '<th rowspan = "2" width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
	                '<th rowspan = "2" style = "text-align: center;background: #20485A;color: #FFFFFF;">Code</th>'+
	                '<th rowspan = "2" style = "text-align: center;background: #20485A;color: #FFFFFF;">Department</th>'+
	                '<th rowspan = "2" style = "text-align: center;background: #20485A;color: #FFFFFF;">Status</th>'+
	                '<th rowspan = "2" style = "text-align: center;background: #20485A;color: #FFFFFF;">Info</th>'+
	                '<th colspan = "'+G_ApproverLength+'" style = "text-align: center;background: #20485A;color: #FFFFFF;" id = "parent_th_approver">Approver</th>'+
	            '</tr>'+
	            '<tr>'+
	            	LoopApprover+
	            '</tr>'+	
	            '</thead>'+
	            '<tbody id="dataRow"></tbody>'+
	        '</table>';
	$("#DivTable").html(table_html);

	var data = {
    	IDDepartementPUBudget : IDDepartementPUBudget,
    	sessionNIP : sessionNIP,
	    auth : 's3Cr3T-G4N',
	    length : G_ApproverLength,
	};
	var token = jwt_encode(data,"UAP)(*");

	var arr_add = [];

	var table = $('#tableData_po').DataTable({
		"fixedHeader": true,
	    "processing": true,
	    "destroy": true,
	    "serverSide": true,
	    "iDisplayLength" : 5,
	    "ordering" : false,
	    "ajax":{
	        url : base_url_js+"rest2/__get_data_spb", // json datasource
	        ordering : false,
	        type: "post",  // method  , by default get
	        data : {token : token},
	        error: function(){  // error handling
	            $(".employee-grid-error").html("");
	            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
	            $("#employee-grid_processing").css("display","none");
	        }
	    },
   	    'createdRow': function( row, data, dataIndex ) {
   	    	$( row ).find('td:eq(0)').attr('align','center');
   	    	var code_url = findAndReplace(data[1],'/','-');
          var ListPR = data[parseInt(data.length) - 1];
          var PRHTML = '';
          PRHTML += '<li>'+ListPR[0]+'</li>';
          var CodeSPB = ListPR[1].CodeSPB;
          var TypeCode = ListPR[1].TypeCode;
          var code_url2 = findAndReplace(CodeSPB,'/','-');
   	    	if (TypeCode == 'PO') {
   	    		$( row ).find('td:eq(1)').html('<div align = "left">'+'<a href="'+base_url_js+'global/purchasing/transaction/spb/list/'+code_url2+'" code="'+CodeSPB+'">'+CodeSPB+'</a><br>'+
   	    			'<label>'+data[1]+'</label><br>Created : '+data[parseInt(data.length) - 2]+'<br>'+PRHTML+'</div>');
   	    	}
   	    	else
   	    	{
   	    		$( row ).find('td:eq(1)').html('<div align = "left">'+'<a href="'+base_url_js+'global/purchasing/transaction/spb/list/'+code_url2+'" code="'+CodeSPB+'">'+CodeSPB+'</a><br>'+'<label>'+data[1]+'</label><br>Created : '+data[parseInt(data.length) - 2]+'<br>'+PRHTML+'</div>');
   	    	}
   	    	
   	    	$( row ).find('td:eq(2)').attr('align','center');
   	    	$( row ).find('td:eq(4)').attr('align','center');
   	    	$( row ).find('td:eq(4)').html('<a href="javascript:void(0)" class="btn btn-info btn_circulation_sheet" code="'+CodeSPB+'">Info</a>');
   	    		
   	    },
        dom: 'l<"toolbar">frtip',
   	    "initComplete": function(settings, json) {

   	    }
	});
}
</script>