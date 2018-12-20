<div id="pageData" class="btn-read">
                
</div>
<script type="text/javascript">
	$(document).ready(function () {
	   loaddata();
	});

	$(document).on('click','.husage', function () {
	   	var id_m_eq_add = $(this).attr('id_m_eq_add');
	   	var eq_name = $(this).attr('eq_name');
	   	var url = base_url_js+'vreservation/detail_historis';
	      var data = {ID_equipment_additional : id_m_eq_add};
	      var token = jwt_encode(data,'UAP)(*');
	      $.post(url,{token:token},function (data_json) {
	      	var response = jQuery.parseJSON(data_json);
	      	console.log(response);
	      	//  element exist
	      	if($("#PageDetail").length) {
	      		$("#PageDetail").remove();
	      		create_data(response,eq_name);
	      	}
	      	else
	      	{
	      		create_data(response,eq_name);
	      	}
	      });
	});

	function create_data(response,eq_name)
	{
		$("#pageData").after('<div id = "PageDetail" style = "margin-top : 10px"></div>');
		$('html, body').animate({ scrollTop: $('#PageDetail').offset().top }, 'slow');
		loading_page("#PageDetail");
		var html_table ='<div class="col-md-12">'+
		                 //'<div class="table-responsive">'+
		                    '<table class="table table-bordered table-hover table-checkable datatable2">'+
		                    	'<caption><h3>Detail Use '+eq_name+'</h3></caption>'+
		                        '<thead>'+
		                            '<tr>'+
		                           ' <th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
		                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Start</th>'+
		                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">End</th>'+
		                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Agenda</th>'+
		                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Room</th>'+
		                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;" width = "25%">Equipment</th>'+
		                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Requester</th>'+
		                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Detail</th>'+
		                            '</tr>'+
		                       ' </thead>'+
		                        '<tbody>'+
		                        '</tbody>'+
		                    '</table>'+
		                 //'</div>'+   
		                '</div>';
		$("#PageDetail").html(html_table);
		for (var i = 0; i < response.length; i++) {
        	var No = parseInt(i)+1;
        	var Detail = '<span class="btn btn-primary Detail" data ="'+response[i]['Detail']+'" >'+
        	                          '<i class="fa fa-search"></i> Detail'+
        	                         '</span>';
        	$(".datatable2 tbody").append(
        	    '<tr>'+
        	        '<td>'+No+'</td>'+
        	        '<td>'+response[i]['Start']+'</td>'+
        	        '<td>'+response[i]['End']+'</td>'+
        	        '<td>'+response[i]['Agenda']+'</td>'+
        	        '<td>'+response[i]['Room']+'</td>'+
        	        '<td>'+response[i]['Equipment_add']+'</td>'+
        	        '<td>'+response[i]['Req_date']+'</td>'+
        	        '<td>'+Detail+'</td>'+
        	    '</tr>' 
        	    );
        }
        LoaddataTable('.datatable2');     
	}

	function loaddata()
	{
		$("#pageData").empty();
		loading_page('#pageData');
		var html_table ='<div class="col-md-12">'+
		                 //'<div class="table-responsive">'+
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
		                 //'</div>'+   
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
				            '<td align = "center">'+'<a href="javascript:void(0);" usage = "'+response[i]['Usage']+'" class= "husage" id_m_eq_add = "'+response[i]['ID']+'" eq_name = "'+response[i]['Equipment']+'">'+response[i]['Usage']+'</a>'+'</td>'+
				            '<td>'+response[i]['Division']+'</td>'+
				        '</tr>' 
				        );
			}
			LoaddataTable('.datatable');
		}); 

	}
</script>