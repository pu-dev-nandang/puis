<div class="row">
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

$(document).ready(function() {
		LoadFirstLoad();
		loadingEnd(1500);

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
		                '<th rowspan = "2" style = "text-align: center;background: #20485A;color: #FFFFFF;">Type</th>'+
		                '<th rowspan = "2" style = "text-align: center;background: #20485A;color: #FFFFFF;">Supplier</th>'+
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
		        url : base_url_js+"rest2/__get_data_po/All", // json datasource
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
              var ListPR = data[9];
              var PRHTML = '';
              for (var i = 0; i < ListPR.length; i++) {
                PRHTML += '<li>'+ListPR[i]+'</li>';
              }
       	    	if (data[2] == 'PO') {
       	    		$( row ).find('td:eq(1)').html('<div align = "left"><a href="'+base_url_js+'global/purchasing/transaction/po/list/'+code_url+'" code="'+data[1]+'">'+data[1]+'</a><br>Created : '+data[8]+'<br>'+PRHTML+'</div>');
       	    	}
       	    	else
       	    	{
       	    		$( row ).find('td:eq(1)').html('<div align = "left"><a href="'+base_url_js+'global/purchasing/transaction/spk/list/'+code_url+'" code="'+data[1]+'">'+data[1]+'</a><br>Created : '+data[8]+'<br>'+PRHTML+'</div>');
       	    	}
       	    	
       	    	//$( row ).find('td:eq(1)').attr('align','center');
       	    	$( row ).find('td:eq(2)').attr('align','center');
       	    	$( row ).find('td:eq(4)').attr('align','left');
       	    	$( row ).find('td:eq(5)').attr('align','center');
       	    	$( row ).find('td:eq(5)').html('<a href="javascript:void(0)" class="btn btn-info btn_circulation_sheet" code="'+data[1]+'">Info</a>');
       	    		
       	    },
       	    "initComplete": function(settings, json) {
       	        
       	    }
		});
	}

	$(document).off('click', '.btn_circulation_sheet').on('click', '.btn_circulation_sheet',function(e) {
	    var url = base_url_js+'rest2/__show_info_po';
	    var Code = $(this).attr('code');
   		var data = {
   		    Code : Code,
   		    auth : 's3Cr3T-G4N',
   		};
   		var token = jwt_encode(data,"UAP)(*");
   		$.post(url,{ token:token },function (data_json) {
   			var html = '<div class = "row"><div class="col-md-12"><div class="well">';
   				html += '<table class="table table-striped table-bordered table-hover table-checkable tableData" id = "TblModal">'+
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
		        html += '</table></div></div></div>' ;

            if (data_json['po_invoice_status'].length > 0) {
              html += '<div class = "row" style = "margin-top:10px;"><div class="col-md-12"><div class="well">';
          html += '<table class="table table-striped table-bordered table-hover table-checkable tableData" id = "TblModal2">'+
                      '<caption><h4>Invoice Status</h4></caption>'+
                      '<thead>'+
                          '<tr>'+
                              '<th style="width: 5px;">Invoice PO</th>'+
                              '<th style="width: 55px;">Paid</th>'+
                              '<th style="width: 55px;">Left</th>';
            html += '</tr>' ;
            html += '</thead>' ;
            html += '<tbody>' ;
            html += '</tbody>' ;
            html += '</table></div></div></div>' ;
            }	

   			var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
   			    '';
   			$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Info PO/SPK Code : '+Code+'</h4>');
   			$('#GlobalModalLarge .modal-body').html(html);
   			$('#GlobalModalLarge .modal-footer').html(footer);
   			$('#GlobalModalLarge').modal({
   			    'show' : true,
   			    'backdrop' : 'static'
   			});

   			// make datatable
   				var table = $('#TblModal').DataTable({
   				      "data" : data_json['po_circulation_sheet'],
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


          if (data_json['po_invoice_status'].length > 0) {
            var table2 = $('#TblModal2').DataTable({
                  "data" : data_json['po_invoice_status'],
                  "ordering": false,
                  "searching": false,
                  "paging":   false,
                  'columnDefs': [
                    {
                       'targets': 0,
                       'render': function (data, type, full, meta){
                           return formatRupiah(full.InvoicePO);
                       }
                    },
                    {
                       'targets': 1,
                       'render': function (data, type, full, meta){
                           return formatRupiah(full.InvoicePayPO);
                       }
                    },
                    {
                       'targets': 2,
                       'render': function (data, type, full, meta){
                           return formatRupiah(full.InvoiceLeftPO);
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

   		});
	})
   
}); // exit document Function
</script>
