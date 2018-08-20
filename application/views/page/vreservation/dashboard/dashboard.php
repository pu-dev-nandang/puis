<div class = 'row'>
	<!--=== Calendar ===-->
	<div class="col-md-12">
		<div class="widget">
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-3"></div>
				<div class="col-md-3"></div>
				<div class="col-md-3">
					<b>Status : </b><br>
					<i class="fa fa-circle" style="color:#6ba5c1;"></i> Available || 
					<i class="fa fa-circle" style="color:#e98180;"></i> Booked ||
					<i class="fa fa-circle" style="color:#ffb848;"></i> Requested 

				</div>
			</div>
			
			<div class="widget-header">
					<h4><i class="icon-calendar"></i> Schedule Today</h4>
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
	/*
	 * pages_calendar.js
	 *
	 * Demo JavaScript used on dashboard and calendar-page.
	 */
	"use strict";
	$(document).ready(function(){
		
		getLoadFirst();
		socket_messages_DASHBOARD();
		//===== Calendar =====//
		// var date = new Date();
		// var d = date.getDate();
		// var m = date.getMonth();
		// var y = date.getFullYear();

		// var h = {};

		// if ($('#calendar_view').width() <= 400) {
		// 	h = {
		// 		left: 'title',
		// 		center: '',
		// 		right: 'prev,next'
		// 	};
		// } else {
		// 	h = {
		// 		left: 'prev,next',
		// 		center: 'title',
		// 		right: 'month,agendaWeek,agendaDay'
		// 	};
		// }

		// $('#calendar_view').fullCalendar({
		// 	defaultView: 'agendaWeek',
		// 	disableDragging: false,
		// 	weekends: false,
		// 	//header: h,
		// 	editable: true,
		// 	events: [{
		// 			title: 'All Day Event',
		// 			start: new Date(y, m, 1),
		// 			backgroundColor: App.getLayoutColorCode('yellow')
		// 		}, {
		// 			title: 'Long Event',
		// 			start: new Date(y, m, d - 5),
		// 			end: new Date(y, m, d - 2),
		// 			backgroundColor: App.getLayoutColorCode('green')
		// 		}, {
		// 			title: 'Repeating Event',
		// 			start: new Date(y, m, d - 3, 16, 0),
		// 			allDay: false,
		// 			backgroundColor: App.getLayoutColorCode('red')
		// 		}, {
		// 			title: 'Repeating Event',
		// 			start: new Date(y, m, d + 4, 16, 0),
		// 			allDay: false,
		// 			backgroundColor: App.getLayoutColorCode('green')
		// 		}, {
		// 			title: 'Meeting',
		// 			start: new Date(y, m, d, 10, 30),
		// 			allDay: false,
		// 		}, {
		// 			title: 'Lunch',
		// 			start: new Date(y, m, d, 12, 0),
		// 			end: new Date(y, m, d, 14, 0),
		// 			backgroundColor: App.getLayoutColorCode('grey'),
		// 			allDay: false,
		// 		}, {
		// 			title: 'Birthday Party',
		// 			start: new Date(y, m, d + 1, 19, 0),
		// 			end: new Date(y, m, d + 1, 22, 30),
		// 			backgroundColor: App.getLayoutColorCode('purple'),
		// 			allDay: false,
		// 		}, {
		// 			title: 'Click for Google',
		// 			start: new Date(y, m, 28),
		// 			end: new Date(y, m, 29),
		// 			backgroundColor: App.getLayoutColorCode('yellow'),
		// 			url: 'http://google.com/',
		// 		}
		// 	]
		// });

		/*$('#calendar_view').fullCalendar({
			defaultView: 'agendaDay',
			      groupByResource: true,
			      resources: [
			        { id: 'a', title: 'Room A' },
			        { id: 'b', title: 'Room B' }
			      ],
			      events: [{"resourceId":"a","title":"All Day Event","start":"2018-08-01"},{"resourceId":"a","title":"Conference","start":"2018-07-31","end":"2018-08-02"},{"resourceId":"b","title":"Meeting","start":"2018-08-01T10:30:00+00:00","end":"2018-08-01T12:30:00+00:00"},{"resourceId":"a","title":"Lunch","start":"2018-08-01T12:00:00+00:00"}]
		});*/

	});

	function getLoadFirst()
	{
		var divHtml = $("#schedule");
		loadDataSchedule(divHtml);
		countApprove();
		
		// setTimeout(function () {
		// 	loadRoomStatus(loadDataRoomStatus);
		// },1000);
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

	function loadRoomStatus(callback)
	{
	    // Some code
	    // console.log('test');
	    // $("#loadtableMenu").empty();
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
	    //$("#loadtableNow").empty();
	    $("#classroom_view").html(table);

	    /*if (typeof callback === 'function') { 
	        callback(); 
	    }*/
	    callback();
	}

	function loadDataRoomStatus()
	{
	//     var url = base_url_js+'vreservation/getroom';
	// // loading_page('#loadtableNow');
	// 	// console.log(getRoom);
	//     $.post(url,function (data_json) {
	//         var response = jQuery.parseJSON(data_json);
	//         //console.log(response);
	//         // $("#loadingProcess").remove();
	//         for (var i = 0; i < response.length; i++) {
	//         	// var Status = (response[i]['approved' == 1])
	//             $(".datatable tbody").append(
	//                 '<tr>'+
	//                     '<td>'+response[i]['room']+'</td>'+
	//                     '<td>'+response[i]['status']+'</td>'+
	//                     '<td>'+response[i]['BookedBy']+'</td>'+
	//                     '<td>'+response[i]['Ends']+'</td>'+
	//                 '</tr>' 
	//                 );
	//         }
	//     }).done(function() {
	//         LoaddataTable('.datatable');
	//     })

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
