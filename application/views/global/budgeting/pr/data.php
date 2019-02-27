<div class="row" style="margin-left: 0px;margin-right: 0px">
	<div class="col-md-12">
		<div class="table-responsive" id = "DivTable">
			
		</div>
	</div>
</div>
<script type="text/javascript">
	var G_Approver = <?php echo json_encode($G_Approver) ?>;
	var G_ApproverLength = G_Approver.length + 4;
	var JsonStatus = [];

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
		                '<th colspan = "'+G_ApproverLength+'" style = "text-align: center;background: #20485A;color: #FFFFFF;" id = "parent_th_approver">Approver</th>'+
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

	$(document).off('click', '#add_approver').on('click', '#add_approver',function(e) {
		var PRCode = $(this).attr('prcode');
		// get JsonStatus
		var Approver = JsonStatus;
		var html = '<div class = "row"><div class="col-md-12">';
			html += '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
              '<thead>'+
                  '<tr>'+
                      '<th style="width: 2%;">Approver</th>'+
                      '<th style="width: 55px;">Name</th>'+
                      '<th style="width: 55px;">Status</th>'+
                      '<th style="width: 55px;">Action</th>';
	        html += '</tr>' ;
	        html += '</thead>' ;
	        html += '<tbody>' ;
	    var ke = 0;    
		for (var i = 0; i < JsonStatus.length; i++) {
			ke = i + 1;
			switch(JsonStatus[i]['Status']) {
			  case 0:
			  case '0':
			   var stjson = 'Not Approve';
			    break;
			  case 1:
			  case '1':
			    var stjson = 'Approve<br>'+JsonStatus[i]['ApproveAt'];
			    break;
			  case 2:
			  case '2':
			    var stjson =  'Reject';
			    break;  
			  default:
			    var stjson = '-';
			}
			var action = '';
			if (JsonStatus[i]['Status'] != 1 && JsonStatus[i]['ApprovedBy'] != '<?php echo $this->session->userdata('NIP') ?>') {
				action = '<button class="btn btn-default btn-default-success btn-edit-approver" data-action="edit" indexjson="'+i+'" prcode = "'+PRCode+'"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
				action += '<button class="btn btn-default btn-default-danger btn-edit-approver" data-action="delete" indexjson="'+i+'" prcode = "'+PRCode+'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
			}
			html += '<tr>'+
			      '<td>'+ ke + '</td>'+
			      '<td>'+ JsonStatus[i]['ApprovedBy'] +' || '+JsonStatus[i]['NameAprrovedBy']+ '</td>'+
			      '<td>'+ stjson + '</td>'+
			      '<td>'+ action + '</td>'
			    '<tr>';	
		}

		// add sisa
		ke = ke + 1;
		for (var i = 0; i < G_ApproverLength - JsonStatus.length; i++) {
			var action = '<button class="btn btn-default btn-default-primary btn-classroom btn-edit-approver" data-action="add" indexjson="'+(ke-1)+'" prcode = "'+PRCode+'"><i class="fa fa-plus-circle fa-right" aria-hidden="true"></i></button>';
			html += '<tr>'+
			      '<td>'+ ke + '</td>'+
			      '<td>'+ '-'+ '</td>'+
			      '<td>'+ '-' + '</td>'+
			      '<td>'+ action + '</td>'+
			    '<tr>';
			ke++;	    	
		}

		html += '</tbody>' ;
		html += '</table></div></div>' ;

		var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
		    '';
		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Custom Approval'+'</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html(footer);
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});

	})

	$(document).off('click', '.btn-edit-approver').on('click', '.btn-edit-approver',function(e) {
		var PRCode = $(this).attr('prcode');
		var action = $(this).attr('data-action');
		var evtd = $(this).closest('td');
		var evtr = $(this).closest('tr');
		var indexjson = $(this).attr('indexjson');
		switch(action) {
		  case 'add':
		  case 'edit':
		    var url = base_url_js + 'api/__crudEmployees';
		    var data = {
		    	action : 'read',
		    }
		    var token = jwt_encode(data,"UAP)(*");
		    $.post(url,{ token:token },function (data_json) {
		    	var OP = '';
		    		for (var i = 0; i < data_json.length; i++) {
		    			OP += '<option value="'+data_json[i].NIP+'" '+''+'>'+data_json[i].NIP+' | '+data_json[i].Name+'</option>';
		    		}
		    	var html = '<div class ="row" style = "margin-right : 5px;margin-left:5px;">'+
		    					'<div class = "col-md-10">'+
		    						'<select class=" form-control listemployees">'+
		    							'   <option value = "0" selected>-- No Selected --</option>'+OP+
		    						'</select>'+
		    					'</div>'+
		    					'<div class = "col-md-2"><button class = "btn btn-primary saveapprover" prcode = "'+PRCode+'" indexjson = "'+indexjson+'" action = "'+action+'">Save</button>'+
		    					'</div>'+
		    				'</div>';
		    	evtd.attr('style','width : 50%')			
		    	evtd.html(html);
		    	$('select[tabindex!="-1"]').select2({
		    	    //allowClear: true
		    	});

		    });
		    break;
		  case 'delete':
		  	 if (confirm('Are you sure ?')) {
		  	 	loading_button('.btn-edit-approver[indexjson="'+indexjson+'"][action="'+action+'"]');
		  	 	var url = base_url_js + 'budgeting/update_approver';
  	 			var data = {
  	 				PRCode : PRCode,
  	 				action : action,
  	 				indexjson : indexjson,
  	 			}
		  	 	var token = jwt_encode(data,"UAP)(*");
		  	 	$.post(url,{ token:token },function (data_json) {
		  	 		var response = jQuery.parseJSON(data_json);
		  	 		if (response['msg'] == '') { // action success
		  	 			var dt = response['data'];
		  	 			JsonStatus=dt; // Update variable JsonStatus
		  	 			Get_tableData_selected(JsonStatus);
		  	 			evtr.find('td:eq(1)').html('-');
		  	 			evtr.find('td:eq(2)').html('-');
		  	 			var action = '<button class="btn btn-default btn-default-primary btn-classroom btn-edit-approver" data-action="add" indexjson="'+indexjson+'" prcode = "'+PRCode+'"><i class="fa fa-plus-circle fa-right" aria-hidden="true"></i></button>';
		  	 			evtr.find('td:eq(3)').html(action);
		  	 		}
		  	 		else
		  	 		{
		  	 			toastr.error(response['msg'],'!!!Failed');
		  	 		}
		  	 	});
		  	 }
		     
		    break;
		  default:
		    // code block
		}
		// get json employees
			
	})

	$(document).off('click', '.saveapprover').on('click', '.saveapprover',function(e) {
		var evtd = $(this).closest('td');
		var evtr = $(this).closest('tr');
		var EM = evtd.find('.listemployees').val();
		var PRCode = $(this).attr('prcode');
		var action = $(this).attr('action');
		var indexjson = $(this).attr('indexjson');
		loading_button('.saveapprover[indexjson="'+indexjson+'"]');
		var url = base_url_js + 'budgeting/update_approver';
		var data = {
			Approver : EM,
			PRCode : PRCode,
			action : action,
			indexjson : indexjson,
		}
		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{ token:token },function (data_json) {
			var response = jQuery.parseJSON(data_json);
			if (response['msg'] == '') { // action success
				var dt = response['data'];
				var key = (dt.length) - 1; // get last insert
				JsonStatus=dt; // Update variable JsonStatus
				Get_tableData_selected(JsonStatus);
				evtr.find('td:eq(1)').html(dt[key]['ApprovedBy'] + ' || '+dt[key]['NameApprovedBy'] );
				evtr.find('td:eq(2)').html('Not Approve');
				action = '<button class="btn btn-default btn-default-success btn-edit-approver" data-action="edit" indexjson="'+indexjson+'"><i class="fa fa-pencil" aria-hidden="true" prcode = "'+PRCode+'"></i></button>';
				action += '<button class="btn btn-default btn-default-danger btn-edit-approver" data-action="delete" indexjson="'+indexjson+'"><i class="fa fa-trash-o" aria-hidden="true" prcode = "'+PRCode+'"></i></button>';
				evtr.find('td:eq(3)').html(action);
			}
			else
			{
				toastr.error(response['msg'],'!!!Failed');
			}
			$('.saveapprover[indexjson="'+indexjson+'"]').prop('disabled',false).html('Save');
		});
	})

	function Get_tableData_selected(JsonStatus)
	{
		var TD0 = $("#tableData_selected tbody").find('tr:first').find('td:eq(0)').html();
		var TD1 = $("#tableData_selected tbody").find('tr:first').find('td:eq(1)').html();
		var TD2 = $("#tableData_selected tbody").find('tr:first').find('td:eq(2)').html();
		var TD3 = 'Issued & Approval Process';
		$("#tableData_selected tbody").find('tr:first').find('td:eq(3)').html(TD3);	
		var stTd = 5; // start dari td ke 5 untuk approval
		// JsonStatus = jQuery.parseJSON(JsonStatus);
		for (var i = 0; i < JsonStatus.length; i++) {
			var html = '';
			switch(JsonStatus[i]['Status']) {
			  case 0:
			  case '0':
			   var stjson = '-';
			    break;
			  case 1:
			  case '1':
			    var stjson = '<i class="fa fa-check" style="color: green;"></i>';
			    break;
			  case 2:
			  case '2':
			    var stjson =  '<i class="fa fa-times" aria-hidden="true" style="color: red;"></i>';;
			    break;  
			  default:
			    var stjson = '-';
			}
			html += stjson+'<br>'+'Approver : '+JsonStatus[i]['NameApprovedBy']+'<br>'+'Approve At : '+JsonStatus[i]['ApproveAt'];
			$("#tableData_selected tbody").find('tr:first').find('td:eq('+stTd+')').html(html);	
			stTd++;
		}

		for (var i = 0; i < G_ApproverLength-JsonStatus.length; i++) {
			$("#tableData_selected tbody").find('tr:first').find('td:eq('+stTd+')').html('-');	
			stTd++;
		}
	}	

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