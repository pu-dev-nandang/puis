<div class = 'row'>
	<!--=== Calendar ===-->
	<div class="col-md-12">
		<div class="widget">
			<div class="row">
				<div class="col-md-4 col-md-offset-8">
					<b>Status : </b><br>
					<i class="fa fa-circle" style="color:#6ba5c1;"></i> Available || 
					<i class="fa fa-circle" style="color:#e98180;"></i> Booked ||
					<i class="fa fa-circle" style="color:#20c51b;"></i> Booked ||
					<i class="fa fa-circle" style="color:#ffb848;"></i> Requested 

				</div>
			</div>
			
			<div class="widget-header">
					<h4 id = 'schdate'><i class="icon-calendar"></i> Schedule Date : <?php echo date('Y-m-d') ?></h4>
					<div class="thumbnail" style="padding: 10px;" id = "countRequest">
					    <!-- <b>Total Request : <a href="javascript:void(0)" class="btn-action" data-page="v_request" data-id="1">11</a></b> -->
					</div>
			</div>
			<div class="widget-content">
				<div id="schedule"></div>
			</div>
		</div> <!-- /.widget box -->
	</div> <!-- /.col-md-6 -->
	<!-- /Calendar -->
</div>	

<div class = 'row'>
	<div class="col-md-12">
		<div class="widget">
			<div class="widget-header">
					<h4><i class="fa fa-caret-square-o-right"></i> List Booking</h4>
				</div>
			<div class="widget-content">
				<div id="classroom_view"></div>
			</div>
		</div> 
	</div> <!-- /.col-md-6 -->
	<!-- /Calendar -->
</div>
<!-- <pre>
	<?php print_r($this->session->userdata('V_BookingDay')) ?>
</pre> -->
<script type="text/javascript">
	"use strict";
	$(document).ready(function(){
		getLoadFirst();
		socket_messages_DASHBOARD();
	});

	function getLoadFirst()
	{
		var divHtml = $("#schedule");
		loadDataSchedule(divHtml);
		countApprove();
		loading_page("#classroom_view");
	}

	function socket_messages_DASHBOARD()
	{
	    var socket = io.connect( 'http://'+window.location.hostname+':3000' );
	    // var socket = io.connect( '<?php echo serverRoot ?>'+':3000' );
	    socket.on( 'update_schedule_notifikasi', function( data ) {

	        //$( "#new_count_message" ).html( data.new_count_message );
	        //$('#notif_audio')[0].play();
	        if (data.update_schedule_notifikasi == 1) {
	            // action
	            getLoadFirst();
	            //countApprove();
	        }

	    }); // exit socket
	}

	function loadRoomStatus2(callback)
	{
		$("#classroom_view").empty();
	    var table = '<table class="table table-striped table-bordered table-hover table-checkable datatable">'+
	    '<thead>'+
	        '<tr>'+
	            '<th style="width: 10%;">Room</th>'+
	            '<th style="width: 21%;">Time</th>'+
	            '<th style="width: 13%;">Status</th>'+
	            '<th style="width: 13%;">Booked By</th>'+
	           
	        '</tr>'+
	    '</thead>'+
	    '<tbody>'+
	    '</tbody>'+
	    '</table>';
	    $("#classroom_view").html(table);

	    /*if (typeof callback === 'function') { 
	        callback(); 
	    }*/
	    callback();
	}

	function loadDataRoomStatus2()
	{
		for (var i = 0; i < getRoom.length; i++) {

			for (var j = 0; j < arrHours.length;j++) {
				var bool = false;
				var TD1 = getRoom[i]['Room'];
				var TD2 = '';
				var TD3 = '';
				var TD4 = '';
				for (var k = 0; k < data_pass.length; k++) {
					if (data_pass[k]['ID'] == 0 && data_pass[k]['NIP'] ==  0) {
						var Start = moment(data_pass[k]['start'], 'HH:mm').format('hh:mm a');
						var End = moment(data_pass[k]['end'], 'HH:mm').format('hh:mm a');
					}
					else
					{
						var Start = moment(data_pass[k]['start'], 'YYYY-MM-DD  HH:mm:ss').format('hh:mm a');
						var End = moment(data_pass[k]['end'], 'YYYY-MM-DD  HH:mm:ss').format('hh:mm a');
					}
					
					if(data_pass[k]['room'] == getRoom[i]['Room'] && Start == arrHours[j])
					{
						if (data_pass[k]['approved'] == 1) {
							var TD2 = Start + ' - ' + End;
							var Status = '<span class="label label-danger">Booked</span>';
							var TD3 = Status;
							var TD4 = data_pass[k]['user'];
							bool = true;
							j = j + parseInt(data_pass[k]['colspan']) - 1;
							// console.log(arrHours.length + " == "+ j + " :: " + getRoom[i]['Room'] + " :: " + Start);
							break;
						}
						else
						{
							var TD2 = Start + ' - ' + End;
							var Status = '<span class="label label-warning">Requested</span>';
							var TD3 = Status;
							var TD4 = data_pass[k]['user'];
							bool = true;
							j = j + parseInt(data_pass[k]['colspan']) - 1;
							// console.log(arrHours.length + " == "+ j + " :: " + getRoom[i]['Room'] + " :: " + Start);
							break;
						}
					}
				}

				if (!bool) {
					var TD2 = arrHours[j];
					var Status = '<span class="label label-success">Available</span>';
					var TD3 = Status;
					var TD4 = '-';
				}

				$(".datatable tbody").append(
					                '<tr>'+
					                    '<td>'+TD1+'</td>'+
					                    '<td>'+TD2+'</td>'+
					                    '<td>'+TD3+'</td>'+
					                    '<td>'+TD4+'</td>'+
					                '</tr>' 
					                );
			}

		}

		LoaddataTable('.datatable');

	}
</script>
