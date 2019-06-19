<div class="row" style="margin-left: 0px;margin-right: 0px">
	<div class="col-md-12">
		<div class="thumbnail" style="padding: 10px;">
            <b>Status : </b><i class="fa fa-circle" style="color:#8ED6EA;"></i> Approve
        </div>
	</div>
</div>
<div class="row" style="margin-left: 0px;margin-right: 0px;margin-top: 10px;">
	<div class="col-md-12">
		<div class="table-responsive" id = "DivTable">
			
		</div>
	</div>
</div>
<script type="text/javascript">
	var G_Approver = <?php echo json_encode($G_Approver) ?>;
	var m_type_user = <?php echo json_encode($m_type_user) ?>;
	var G_ApproverLength = G_Approver.length + 4;
	var JsonStatus = [];
	var btn_see_pass = '';

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
			LoopApprover += '<th style = "text-align: center;background: #333;color: #FFFFFF;" id = "thapprover'+ap+'">'+ap+'</th>';
		}

		var table = '<table class="table table-bordered" id = "tableData4">'+
		            '<thead>'+
		            '<tr>'+
		                '<th rowspan = "2" width = "3%" style = "text-align: center;background: #333;color: #FFFFFF;">No</th>'+
		                '<th rowspan = "2" style = "text-align: center;background: #333;color: #FFFFFF;">PR Code</th>'+
		                '<th rowspan = "2" style = "text-align: center;background: #333;color: #FFFFFF;">Department</th>'+
		                '<th rowspan = "2" style = "text-align: center;background: #333;color: #FFFFFF;">Status</th>'+
		                '<th rowspan = "2" style = "text-align: center;background: #333;color: #FFFFFF;">Info</th>'+
		                '<th colspan = "'+G_ApproverLength+'" style = "text-align: center;background: #333;color: #FFFFFF;" id = "parent_th_approver">Approver</th>'+
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
		    "iDisplayLength" : 5,
		    "ordering" : false,
		    "ajax":{
		        url : base_url_js+"budgeting/DataPR", // json datasource
		        ordering : false,
		        type: "post",  // method  , by default get
		        data : {ApproverLength : G_ApproverLength},
		        error: function(){  // error handling
		            $(".employee-grid-error").html("");
		            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
		            $("#employee-grid_processing").css("display","none");
		        }
		    },
		    'createdRow': function( row, data, dataIndex ) {
		    		// console.log(data);
		    		 var endkey = (data.length) - 1;
		    		 var keydepartment = (data.length) - 3;
		    		 $( row ).find('td:eq(1)').html(
		    		 		'<a href = "javascript:void(0)" class = "PRCode" fill = "'+data[1]+'" department = "'+data[keydepartment]+'" style = "color : #d41c1c">'+data[1]+'</a><br>By : '+ data[endkey]
		    		 	)
		    		 $( row ).find('td:eq(4)').attr('align','center');

		    		 /* 
		    		 	Warna Status oleh admin setelah approve semua menjadi color:#8ED6EA; selainnya default
						Warna Status oleh approval ketika akan di approval default jika telah approval color:#8ED6EA;
						Warna Status oleh user selain dua diatas adalah default
		    		 */

		    		 var keyJsonStatus = (data.length) - 2;
		    		 var JsonStatus = data[keyJsonStatus];
		    		 // find NIP
		    		 	var st = {
		    		 		Find : 0,
		    		 		Approval : 0,
		    		 		Approved : 0,
		    		 		AllApprove : 0,
		    		 	};

		    		 	var j_AllApprove = 0;
		    		 	for (var i = 0; i < JsonStatus.length; i++) {
		    		 		if (i > 0) {
		    		 			if (NIP == JsonStatus[i]['NIP'] && JsonStatus[i]['Status'] == 1 ) {
		    		 				st.Find = 1;
		    		 				st.Approval = 1;
		    		 				st.Approved = 1;

		    		 			}
		    		 			else if(NIP == JsonStatus[i]['NIP'] && JsonStatus[i]['Status'] == 0 ) {
		    		 				st.Find = 1;
		    		 				st.Approval = 1;
		    		 				st.Approved = 0;
		    		 			}
		    		 			else
		    		 			{
		    		 				st.Find = 1;
		    		 			}
		    		 		}

		    		 		// check all approve or not
		    		 		if (JsonStatus[i]['Status'] == 0) {
		    		 			j_AllApprove++;
		    		 		}
		    		 	}

		    		 	// check all approve or not
		    		 		if (j_AllApprove > 0) {
		    		 			st.AllApprove = 0;
		    		 		}
		    		 		else
		    		 		{
		    		 			st.AllApprove = 1;
		    		 		}

		    		 	// html warna
		    		 		if (st.AllApprove == 1) {
		    		 			$( row ).attr('style','background-color: #8ED6EA;');
		    		 		}
		    		 		else
		    		 		{
		    		 			if (st.Find == 1 && st.Approval == 1 && st.Approved == 1) {
		    		 				$( row ).attr('style','background-color: #8ED6EA;');
		    		 			}
		    		 		}	
		    },
		} );
	}

	$(document).off('click', '.PRCode').on('click', '.PRCode',function(e) {
		loading_page("#pageContent");
		var ev = $(this).closest('tr');
		var Htmlselected = ev.html();
		// get tombol see
		var btn_see = ev.find('td:eq(4)').html();
		btn_see_pass = btn_see; 
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
               // $("#PageDataExisting").html(ShowHtmlSelected(thead,Htmlselected));
               $(".menuEBudget li").removeClass('active');
               $(".pageAnchor[page='form']").parent().addClass('active');
           },1000);

		});    
	})

	$(document).off('click', '.btn_circulation_sheet').on('click', '.btn_circulation_sheet',function(e) {
	    var PRCode = $(this).attr('PRCode');
	    var url = base_url_js+'rest2/__show_info_pr';
   		var data = {
   		    PRCode : PRCode,
   		    auth : 's3Cr3T-G4N',
   		};
   		var token = jwt_encode(data,"UAP)(*");
   		$.post(url,{ token:token },function (data_json) {
   			var html = '<div class = "row"><div class="col-md-12">';
   				html += '<div class="well"><table class="table table-striped table-bordered table-hover table-checkable tableData" id = "TblModal">'+
   							'<caption><h4>Circulation Sheet</h4></caption>'+
                      '<thead>'+
                          '<tr>'+
                              '<th style="width: 5px;">No</th>'+
                              '<th style="width: 55px;">Desc</th>'+
                              '<th style="width: 55px;">Date</th>'+
                              '<th style="width: 55px;">By</th>';
		        html += '</tr>' ;
		        html += '</thead>' ;
		        html += '<tbody>' ;
		        html += '</tbody>' ;
		        html += '</table></div></div></div>';

		        if (data_json['PR_Status_Summary'].length > 0) {
		        	html +='<div class= "row" style = "margin-top:10px;">'+
		        				'<div class = "col-md-12">'+
		        					'<div class="well"><table class="table table-striped table-bordered table-hover table-checkable tableData" id = "TblModal2">'+
		        						'<caption><h4>PR Status in Purchasing</h4></caption>'+
		        						'<thead>'+
		        							'<tr>'+
		        								'<th>Processing</th>'+
		        								'<th>Pending</th>'+
		        								'<th>Done</th>'+
		        								'<th>Cancel</th>'+
		        							'</tr>'+
		        						'</thead>'+
		        						'<tbody></tbody>'+
		        						'</table></div></div></div>';
		        }
		        
		        if (data_json['PR_link_PO'].length > 0) {
		        	html +='<div class= "row" style = "margin-top:10px;">'+
		        				'<div class = "col-md-12">'+
		        					'<div class="well"><table class="table table-striped table-bordered table-hover table-checkable tableData" id = "TblModal3">'+
		        						'<caption><h4>PR Item To PO</h4></caption>'+
		        						'<thead>'+
		        							'<tr>'+
		        								'<th>No</th>'+
		        								'<th>Catalog</th>'+
		        								'<th>PO/SPK Code</th>'+
		        							'</tr>'+
		        						'</thead>'+
		        						'<tbody></tbody>'+
		        						'</table></div></div></div>';
		        }

   			var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
   			    '';
   			$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Info PR '+PRCode+'</h4>');
   			$('#GlobalModalLarge .modal-body').html(html);
   			$('#GlobalModalLarge .modal-footer').html(footer);
   			$('#GlobalModalLarge').modal({
   			    'show' : true,
   			    'backdrop' : 'static'
   			});

   			// make datatable
   				var table = $('#TblModal').DataTable({
   				      "data" : data_json['PR_Process'],
   				      'columnDefs': [
   					      {
   					         'targets': 0,
   					         'searchable': false,
   					         'orderable': false,
   					         'className': 'dt-body-center',
   					         'render': function (data, type, full, meta){
   					             return '';
   					         }
   					      },
   					      {
   					         'targets': 1,
   					         'render': function (data, type, full, meta){
   					             return full.Desc;
   					         }
   					      },
   					      {
   					         'targets': 2,
   					         'render': function (data, type, full, meta){
   					             return full.Date;
   					         }
   					      },
   					      {
   					         'targets': 3,
   					         'render': function (data, type, full, meta){
   					             return full.Name;
   					         }
   					      },
   				      ],
   				      'createdRow': function( row, data, dataIndex ) {
   				      		$(row).find('td:eq(0)').attr('style','width : 10px;')
   				      	
   				      },
   				});

   				table.on( 'order.dt search.dt', function () {
   				        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
   				            cell.innerHTML = i+1;
   				        } );
   				} ).draw();

   				if (data_json['PR_Status_Summary'].length > 0) {
   					var table2 = $('#TblModal2').DataTable({
   					      "data" : data_json['PR_Status_Summary'],
   					      "ordering": false,
   					      "searching": false,
   					      "paging":   false,
   					      'columnDefs': [
   						      {
   						         'targets': 0,
   						         'render': function (data, type, full, meta){
   						             return full.Item_proc;
   						         }
   						      },
   						      {
   						         'targets': 1,
   						         'render': function (data, type, full, meta){
   						             return full.Item_pending;
   						         }
   						      },
   						      {
   						         'targets': 2,
   						         'render': function (data, type, full, meta){
   						             return full.Item_done;
   						         }
   						      },
   						      {
   						         'targets': 3,
   						         'render': function (data, type, full, meta){
   						             return full.Item_cancel;
   						         }
   						      },
   					      ],
   					      'createdRow': function( row, data, dataIndex ) {
   					      		$(row).find('td:eq(0)').attr('style','width : 10px;')
   					      	
   					      },
   					});

   					table2.on( 'order.dt search.dt', function () {
   					        table2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
   					            cell.innerHTML = i+1;
   					        } );
   					} ).draw();
   				}


   				if (data_json['PR_link_PO'].length > 0) {
	  				var table3 = $('#TblModal3').DataTable({
	  				      "data" : data_json['PR_link_PO'],
	  				      'columnDefs': [
	  					      {
						         'targets': 0,
						         'searchable': false,
						         'orderable': false,
						         'className': 'dt-body-center',
						         'render': function (data, type, full, meta){
						             return '';
						         }
						      },
	  					      {
	  					         'targets': 1,
	  					         'render': function (data, type, full, meta){
	  					             return full.Item;
	  					         }
	  					      },
	  					      {
	  					         'targets': 2,
	  					         'render': function (data, type, full, meta){
	  					         	 var Code = '-';
	  					         	 if (full.Code != '' && full.Code != null && full.Code != undefined) {
	  					         	 	Code = full.Code;
	  					         	 }
	  					             return Code;
	  					         }
	  					      },
	  				      ],
	  				      'createdRow': function( row, data, dataIndex ) {
	  				      		$(row).find('td:eq(0)').attr('style','width : 10px;')
	  				      	
	  				      },
	  				});

	  				table3.on( 'order.dt search.dt', function () {
	  				        table3.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	  				            cell.innerHTML = i+1;
	  				        } );
	  				} ).draw();
   				}

   		});
	})
	    
}); // exit document Function
</script>