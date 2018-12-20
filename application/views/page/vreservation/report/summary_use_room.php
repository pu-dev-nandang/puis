<div class="row">
	<!-- <div class="col-md-12"> -->
		<div class="col-md-8 col-md-offset-2">
			<div class="thumbnail">
				<div class="row">
					<div class="col-md-12" align="center">
						<h4>Search</h4>
					</div>
				</div>
				<div class="row" style="margin-top: 10px">
					<div class="col-md-8 col-md-offset-2">
						<div class="row">
							<div class="col-xs-2">
								<label class="checkbox-inline">
								     <input type="checkbox" class = "dateAll" name="dateAll" id = "dateAll" value = "0"> All
								</label>
							</div>
							<div class="col-xs-10">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Date Range 1</label>
											<input type="text" name="DateRange1" class="form-control" value="<?php echo date('Y-m-d') ?>" id = "DateRange1" readonly="true">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Date Range 2</label>
											<input type="text" name="DateRange2" class="form-control" value="<?php echo date('Y-m-d') ?>" id = "DateRange2" readonly="true">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<button class="btn btn-primary" id = "Search"><i class="fa fa-search"></i> Search</button>
					</div>
				</div>
			</div>
		</div>
	<!-- </div> -->
</div>

<div id = 'pageData'>
	
</div>
<script type="text/javascript">
	$(document).ready(function () {
	    $("#DateRange1").datepicker({
		    dateFormat: 'yy-mm-dd',

	  	});

	  	$("#DateRange2").datepicker({
		    dateFormat: 'yy-mm-dd',

	  	});

	  	$(".dateAll").click(function(){
	  		if ($(this).is(':checked')) {
	  			$("#DateRange1").val('');
	  			$("#DateRange2").val('');
	  			$("#DateRange1").attr('disabled',true);
	  			$("#DateRange2").attr('disabled',true);
	  		}
	  		else
	  		{
	  			$("#DateRange1").val('<?php echo date('Y-m-d') ?>');
	  			$("#DateRange2").val('<?php echo date('Y-m-d') ?>');
	  			$("#DateRange1").attr('disabled',false);
	  			$("#DateRange2").attr('disabled',false);
	  		}
	  	})

	  	$("#Search").click(function(){
	  		$("#PageDetail").remove();
	  		var date1 = $("#DateRange1").val();
	  		var date2 = $("#DateRange2").val();
	  		var url = base_url_js+'api/vreservation/summary_use_room';
		    var data = {
		    			date1 : date1,
		    			date2 : date2,
		    			auth : 's3Cr3T-G4N'
		    			};
		    var token = jwt_encode(data,'UAP)(*');
		    $.post(url,{token:token},function (data_json) {
		      	var response = jQuery.parseJSON(data_json);
		      	console.log(response);
		      	$("#pageData").empty();
		      	loading_page("#pageData");

		      	var html_table ='<div class = "row" style = "margin-top : 10px"><div class="col-md-12">'+
		      	                 //'<div class="table-responsive">'+
		      	                    '<table class="table table-bordered table-hover table-checkable datatable">'+
		      	                        '<thead>'+
		      	                            '<tr>'+
		      	                           ' <th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
		      	                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Category</th>'+
		      	                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Room</th>'+
		      	                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Usage</th>'+
		      	                           '</tr>'+
		      	                       ' </thead>'+
		      	                        '<tbody>'+
		      	                        '</tbody>'+
		      	                    '</table>'+
		      	                 //'</div>'+   
		      	                '</div></div>';
                setTimeout(function () {
                	$("#pageData").html(html_table);
                	for (var i = 0; i < response.length; i++) {
                		var No = parseInt(i)+1;
                		$(".datatable tbody").append(
                		    '<tr>'+
                		        '<td>'+No+'</td>'+
                		        '<td>'+response[i]['NameEng']+'</td>'+
                		        '<td>'+response[i]['Room']+'</td>'+
                		        '<td>'+'<a href="javascript:void(0);" class = "DetailUsage" room = "'+response[i]['Room']+'" date1 = "'+date1+'" date2 = "'+date2+'">'+response[i]['Usage']+'</a></td>'+
                		    '</tr>' 
                		    );
                	}
                	LoaddataTable('.datatable');
                },2000);
			});

	  	})

	});

	$(document).on('click','.DetailUsage', function () {
	   var room = $(this).attr('room');
	   var date1 = $(this).attr('date1');
	   var date2 = $(this).attr('date2');
	   	var url = base_url_js+'api/vreservation/detailroom';
	    var data = {
	    		room : room,
	    		date1 : date1,
	    		date2 : date2,
	    		auth : 's3Cr3T-G4N',
	    		};
	    var token = jwt_encode(data,'UAP)(*');
	    $.post(url,{token:token},function (data_json) {
	      var response = jQuery.parseJSON(data_json);
	      console.log(response);
	      if($("#PageDetail").length) {
	      	$("#PageDetail").remove();
	      	create_data(response,room);
	      }
	      else
	      {
	      	create_data(response,room);
	      }
	    });
	});

	function create_data(response,room)
	{
		$("#pageData").after('<div id = "PageDetail" style = "margin-top : 10px"></div>');
		$('html, body').animate({ scrollTop: $('#PageDetail').offset().top }, 'slow');
		loading_page("#PageDetail");
      	setTimeout(function () {
			var html_table ='<div class = "row" style = "margin-top : 40px"><div class="col-md-12">'+
			                 '<div class="table-responsive">'+
			                    '<table class="table table-bordered table-hover table-checkable datatable2">'+
			                    	'<caption><h3>Detail Use Room '+room+'</h3></caption>'+
			                        '<thead>'+
			                            '<tr>'+
			                           ' <th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
			                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Start</th>'+
			                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">End</th>'+
			                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Agenda</th>'+
			                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Room</th>'+
			                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Requester</th>'+
			                           ' <th style = "text-align: center;background: #20485A;color: #FFFFFF;">Detail</th>'+
			                            '</tr>'+
			                       ' </thead>'+
			                        '<tbody>'+
			                        '</tbody>'+
			                    '</table>'+
			                 '</div>'+   
			                '</div></div>';
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
	        	        '<td>'+response[i]['Req_date']+'</td>'+
	        	        '<td>'+Detail+'</td>'+
	        	    '</tr>' 
	        	    );
	        }
	        LoaddataTable('.datatable2');     
		},2000);
		
	}
	
</script>