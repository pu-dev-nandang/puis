<div class="row" style="margin-left: 0px;margin-right: 0px">
	<div class="col-md-12">
		<div class="table-responsive" id = "DivTable">
			
		</div>
	</div>
</div>
<script type="text/javascript">
	var G_Approver = <?php echo json_encode($G_Approver) ?>;
	var G_ApproverLength = G_Approver.length + 4;

$(document).ready(function() {
		LoadFirstLoad()

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

		var table = '<table class="table table-bordered datatable2" id = "tableData4">'+
		            '<thead>'+
		            '<tr>'+
		                '<th rowspan = "2" width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
		                '<th rowspan = "2" style = "text-align: center;background: #20485A;color: #FFFFFF;">PR Code</th>'+
		                '<th rowspan = "2" style = "text-align: center;background: #20485A;color: #FFFFFF;">Department</th>'+
		                '<th rowspan = "2" style = "text-align: center;background: #20485A;color: #FFFFFF;">Status</th>'+
		                '<th rowspan = "2" style = "text-align: center;background: #20485A;color: #FFFFFF;">Circulation Sheet</th>'+
		                '<th colspan = "'+G_ApproverLength+'" style = "text-align: center;background: #20485A;color: #FFFFFF;">Approver</th>'+
		            '</tr>'+
		            '<tr>'+
		            	LoopApprover+
		            '</tr>'+	
		            '</thead>'+
		            '<tbody id="dataRow"></tbody>'+
		        '</table>';
		$("#DivTable").html(table);

		$.fn.dataTable.ext.errMode = 'throw';
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
			"fixedHeader": true,
		    "processing": true,
		    "destroy": true,
		    "serverSide": true,
		    "iDisplayLength" : 25,
		    "ordering" : false,
		    "ajax":{
		        url : base_url_js+"budgeting/DataPR", // json datasource
		        ordering : false,
		        type: "post",  // method  , by default get
		        data : {length : G_ApproverLength},
		        error: function(){  // error handling
		            $(".employee-grid-error").html("");
		            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
		            $("#employee-grid_processing").css("display","none");
		        }
		    },
		    'createdRow': function( row, data, dataIndex ) {
		    		 var endkey = (data.length) - 1;
		    		 var keydepartment = (data.length) - 2;
		    		 $( row ).find('td:eq(1)').html(
		    		 		'<a href = "javascript:void(0)" class = "PRCode" fill = "'+data[1]+'" department = "'+data[keydepartment]+'">'+data[1]+'</a><br>By : '+ data[endkey]
		    		 	)
		    		 $( row ).find('td:eq(4)').attr('align','center');
		    },
		} );
	}

	$(document).off('click', '.PRCode').on('click', '.PRCode',function(e) {
		loading_page("#pageContent");
		var ev = $(this).closest('tr');
		var Htmlselected = ev.html();
		var thead = $(this).closest('table').find('thead').eq($(this).index());
		thead = thead.html();
		var PRCode = $(this).attr('fill');
		var department = $(this).attr('department');
		var url = base_url_js+'budgeting/FormEditPR';
		var data = {
		    PRCode : PRCode,
		    department : department,
		};
		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{ token:token },function (data_json) {
		    var response = jQuery.parseJSON(data_json);
           	var html = response.html;
           	var jsonPass = response.jsonPass;
           setTimeout(function () {
               $("#pageContent").empty();
               $("#pageContent").html(html);
               $("#dataselected").html(ShowHtmlSelected(thead,Htmlselected));

               $(".menuEBudget li").removeClass('active');
               $(".pageAnchor[page='form']").parent().addClass('active');
           },1000);

		});    
	})

	$(document).off('click', '.btn_circulation_sheet').on('click', '.btn_circulation_sheet',function(e) {
	    var PRCode = $(this).attr('PRCode');
	    var url = base_url_js+'rest/__show_circulation_sheet';
   		var data = {
   		    PRCode : PRCode,
   		    auth : 's3Cr3T-G4N',
   		};
   		var token = jwt_encode(data,"UAP)(*");
   		$.post(url,{ token:token },function (data_json) {
   			var html = '<div class = "row"><div class="col-md-12">';
   				html += '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
                      '<thead>'+
                          '<tr>'+
                              '<th style="width: 5px;">No</th>'+
                              '<th style="width: 55px;">Desc</th>'+
                              '<th style="width: 55px;">Date</th>'+
                              '<th style="width: 55px;">By</th>';
		        html += '</tr>' ;
		        html += '</thead>' ;
		        html += '<tbody>' ;

		        for (var i = 0; i < data_json.length; i++) {
		        	var No = parseInt(i) + 1;
		        	html += '<tr>'+
		        	      '<td>'+ No + '</td>'+
		        	      '<td>'+ data_json[i]['Desc'] + '</td>'+
		        	      '<td>'+ data_json[i]['Date'] + '</td>'+
		        	      '<td>'+ data_json[i]['Name'] + '</td>'+
		        	    '<tr>';	
		        }

		        html += '</tbody>' ;
		        html += '</table></div></div>' ;	

   			var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
   			    '';
   			$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Circulation Sheet'+'</h4>');
   			$('#GlobalModalLarge .modal-body').html(html);
   			$('#GlobalModalLarge .modal-footer').html(footer);
   			$('#GlobalModalLarge').modal({
   			    'show' : true,
   			    'backdrop' : 'static'
   			});
   		});
	})

	function ShowHtmlSelected(thead,Htmlselected)
	{
		var html = '<div class = "col-md-10 col-md-offset-1"><div class="table-responsive"><table class="table table-bordered" id = "tableData_selected">'+
		            '<thead>';
		    html += thead;        
		   	html += '</thead><tbody>';
		    html += '<tr>'+Htmlselected+'</tr>'; 
		    html += '</tbody></table></div></div>';
		return html;    	
	}
	    
}); // exit document Function
</script>