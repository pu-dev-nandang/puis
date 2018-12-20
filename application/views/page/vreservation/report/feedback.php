<div class="row">
	<div class="col-md-12">
		<div id = "c_page"></div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		loaddata();

		function loaddata()
		{
			$("#c_page").empty();
			var table = '<div class = "table-responsive"><table class="table table-bordered table-hover table-checkable" id = "datatableF">'+
	                        '<thead>'+
	                            '<tr>'+
	                           ' <th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
	                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Start</th>'+
	                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">End</th>'+
	                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Agenda</th>'+
	                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Room</th>'+
	                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Requester</th>'+
	                           ' <th width = "30%" style = "text-align: center;background: #20485A;color: #FFFFFF;">Feedback</th>'+
	                           ' <th width = "10%"style = "text-align: center;background: #20485A;color: #FFFFFF;">FeedbackAt</th>'+
	                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Detail</th>'+
	                            '</tr>'+
	                       ' </thead>'+
	                        '<tbody>'+
	                        '</tbody>'+
	                    '</table></div>';
			//$("#loadtableNow").empty();
			$("#c_page").html(table);


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

			var dataTable = $('#datatableF').DataTable( {
			    "processing": true,
			    "destroy": true,
			    "serverSide": true,
			    "iDisplayLength" : 5,
			    "ordering" : false,
			    "ajax":{
			        url : base_url_js+"vreservation/datafeedback", // json datasource
			        ordering : false,
			        type: "post",  // method  , by default get
			        error: function(){  // error handling
			            $(".employee-grid-error").html("");
			            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
			            $("#employee-grid_processing").css("display","none");
			        }
			    },
			    'createdRow': function( row, data, dataIndex ) {
			    	var Detail = '<span class="btn btn-primary Detail" data ="'+data[8]+'" >'+
			    	                          '<i class="fa fa-search"></i> Detail'+
			    	                         '</span>';
			         $( row ).find('td:eq(8)')
	                       .html(Detail)
			    },
			} ); 
		}

	});
</script>