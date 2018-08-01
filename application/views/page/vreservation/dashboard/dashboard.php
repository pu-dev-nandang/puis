<!--=== Calendar ===-->
<div class="col-md-6">
	<div class="widget">
		<div class="widget-header">
				<h4><i class="icon-calendar"></i> Schedule</h4>
			</div>
		<div class="widget-content">
			<div id="calendar_view"></div>
		</div>
	</div> <!-- /.widget box -->
</div> <!-- /.col-md-6 -->
<!-- /Calendar -->

<div class="col-md-6">
	<div class="widget">
		<div class="widget-header">
				<h4><i class="fa fa-caret-square-o-right"></i> Room</h4>
			</div>
		<div class="widget-content">
			<div id="classroom_view"></div>
		</div>
	</div> <!-- /.widget box -->
</div> <!-- /.col-md-6 -->
<!-- /Calendar -->

<script type="text/javascript">
	/*
	 * pages_calendar.js
	 *
	 * Demo JavaScript used on dashboard and calendar-page.
	 */

	"use strict";

	$(document).ready(function(){
		getAllRoom();

		//===== Calendar =====//
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();

		var h = {};

		if ($('#calendar_view').width() <= 400) {
			h = {
				left: 'title',
				center: '',
				right: 'prev,next'
			};
		} else {
			h = {
				left: 'prev,next',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			};
		}

		$('#calendar_view').fullCalendar({
			disableDragging: false,
			header: h,
			editable: true,
			events: [{
					title: 'All Day Event',
					start: new Date(y, m, 1),
					backgroundColor: App.getLayoutColorCode('yellow')
				}, {
					title: 'Long Event',
					start: new Date(y, m, d - 5),
					end: new Date(y, m, d - 2),
					backgroundColor: App.getLayoutColorCode('green')
				}, {
					title: 'Repeating Event',
					start: new Date(y, m, d - 3, 16, 0),
					allDay: false,
					backgroundColor: App.getLayoutColorCode('red')
				}, {
					title: 'Repeating Event',
					start: new Date(y, m, d + 4, 16, 0),
					allDay: false,
					backgroundColor: App.getLayoutColorCode('green')
				}, {
					title: 'Meeting',
					start: new Date(y, m, d, 10, 30),
					allDay: false,
				}, {
					title: 'Lunch',
					start: new Date(y, m, d, 12, 0),
					end: new Date(y, m, d, 14, 0),
					backgroundColor: App.getLayoutColorCode('grey'),
					allDay: false,
				}, {
					title: 'Birthday Party',
					start: new Date(y, m, d + 1, 19, 0),
					end: new Date(y, m, d + 1, 22, 30),
					backgroundColor: App.getLayoutColorCode('purple'),
					allDay: false,
				}, {
					title: 'Click for Google',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					backgroundColor: App.getLayoutColorCode('yellow'),
					url: 'http://google.com/',
				}
			]
		});

		function getAllRoom()
		{
			var url = base_url_js+'vreservation/getRoom/'+page;
			var data = {
			    ta : ta,
			    prodi : prodi,
			    PTID  : PTID,
			    NIM : NIM,
			};
			var token = jwt_encode(data,'UAP)(*');
			$.post(url,{token:token},function (resultJson) {
			   var resultJson = jQuery.parseJSON(resultJson);
			   console.log(resultJson);
			    var Data_mhs = resultJson.loadtable;
			    data = Data_mhs;
			    dataaModal = Data_mhs;
			    for(var i=0;i<Data_mhs.length;i++){
			      var ccc = 0;
			      var yy = (Data_mhs[i]['InvoicePayment'] != '') ? formatRupiah(Data_mhs[i]['InvoicePayment']) : '-';
			      // proses status
			      var status = '';

			      var b = 0;
			      var cicilan = 0;
			      var bayar = 0;
			      var htmlCicilan = '';
			      // count jumlah pembayaran dengan status 1
			      for (var j = 0; j < Data_mhs[i]['DetailPayment'].length; j++) {
			        var a = Data_mhs[i]['DetailPayment'][j]['Status'];
			        if(a== 1)
			        {
			          b = parseInt(b) + parseInt(Data_mhs[i]['DetailPayment'][j]['Invoice']);
			          bayar = bayar + 1;
			        }
			        cicilan = cicilan + 1;
			      }


			      if(cicilan == 1)
			      {
			        htmlCicilan = "Tidak Cicilan, Deadline : "+Data_mhs[i]['DetailPayment'][0]['Deadline'];
			      }
			      else
			      {
			        for (var k = 1; k <= cicilan; k++) {
			          var dd = parseInt(k) - 1 ;
			          var bayarStatus = (k > bayar) ? '<i class="fa fa-minus-circle" style="color: red;"></i>' : '<i class="fa fa-check-circle" style="color: green;"></i>';
			          htmlCicilan += '<p>Cicilan ke '+k+ ': '+bayarStatus+ ' ,Deadline : '+Data_mhs[i]['DetailPayment'][dd]['Deadline']+'</p>';
			        }
			      }

			      var tr = '<tr>';
			      // show bintang
			      var bintang = (Data_mhs[i]['Pay_Cond'] == 1) ? '<p style="color: red;">*</p>' : '<p style="color: red;">**</p>'; 
			      $('#dataRow').append(tr +
			          '<td>'+Data_mhs[i]['ProdiEng']+'<br>'+Data_mhs[i]['SemesterName']+'</td>' +
			          // '<td>'+Data_mhs[i]['SemesterName']+'</td>' +
			          '<td>'+bintang+Data_mhs[i]['Nama']+'<br>'+Data_mhs[i]['NPM']+'<br>'+Data_mhs[i]['VA']+'</td>' +
			          // '<td>'+Data_mhs[i]['NPM']+'</td>' +
			          // '<td>'+Data_mhs[i]['Year']+'</td>' +
			          '<td>'+Data_mhs[i]['PTIDDesc']+'</td>' +
			          '<td>'+Data_mhs[i]['EmailPU']+'</td>' +
			          '<td>'+yy+'</td>' +
			          '<td>'+htmlCicilan+'</td>'+
			          '<td>'+'<button class = "DetailPayment" NPM = "'+Data_mhs[i]['NPM']+'">View</button>&nbsp <button class = "edit" NPM = "'+Data_mhs[i]['NPM']+'" semester = "'+Data_mhs[i]['SemesterID']+'" PTID = "'+Data_mhs[i]['PTID']+'" PaymentID = "'+Data_mhs[i]['PaymentID']+'">Edit</button>'+'</td>' +
			          '</tr>');
			     
			    }

			   if(Data_mhs.length > 0)
			   {
			    $('#datatable2').removeClass('hide');
			    $("#pagination_link").html(resultJson.pagination_link);
			   }
			   
			}).fail(function() {
			  
			  toastr.info('No Result Data'); 
			  // toastr.error('The Database connection error, please try again', 'Failed!!');
			}).always(function() {
			    $('#NotificationModal').modal('hide');
			});
		}

	});
</script>
