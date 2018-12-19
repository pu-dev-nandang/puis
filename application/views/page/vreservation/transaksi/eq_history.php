<div id="pageData" class="btn-read">
                
</div>
<script type="text/javascript">
	$(document).ready(function () {
	   loaddata();
	});


	function loaddata()
	{
		$("#pageData").empty();
		loading_page('#pageData');
		var html_table ='<div class="col-md-12">'+
		                 '<div class="table-responsive">'+
		                    '<table class="table table-bordered table-hover table-checkable datatable">'+
		                        '<thead>'+
		                            '<tr>'+
		                           ' <th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
		                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Equipment</th>'+
		                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Qty</th>'+
		                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Total Usage</th>'+
		                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Owner</th>'+
		                            '</tr>'+
		                       ' </thead>'+
		                        '<tbody>'+
		                        '</tbody>'+
		                    '</table>'+
		                 '</div>'+   
		                '</div>';
		var url = base_url_js+'vreservation/list_eq_history';
		$.post(url,function (data_json) {
			$("#pageData").html(html_table);
			var response = jQuery.parseJSON(data_json);
			console.log(response);
			for (var i = 0; i < response.length; i++) {
				var No = parseInt(i)+1;
				    $(".datatable tbody").append(
				        '<tr>'+
				            '<td>'+No+'</td>'+
				            '<td>'+response[i]['Equipment']+'</td>'+
				            '<td align = "center">'+response[i]['Qty']+'</td>'+
				            '<td align = "center">'+'<a href="javascript:void(0);" usage = "'+response[i]['Usage']+'" class= "husage">'+response[i]['Usage']+'</a>'+'</td>'+
				            '<td>'+response[i]['Division']+'</td>'+
				        '</tr>' 
				        );
			}
			//LoaddataTable('.datatable');
		});                
	}
</script>