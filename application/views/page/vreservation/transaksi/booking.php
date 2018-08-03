<div class = 'row'>
	<!--=== Calendar ===-->
	<div class="col-md-12">
		<div class="widget">
			<div class="widget-header">
					<h4><i class="icon-calendar"></i> Schedule</h4>
				</div>
			<div class="widget-content">
				<div class = "row">	
					<div class="col-xs-3">
						<div id="datetimepicker1" class="input-group input-append date datetimepicker">
								<input data-format="yyyy-MM-dd hh:mm:ss" class="form-control" id="datetime_deadline1" type="	text" readonly="">
								<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
						</div>
					</div>
				</div>
				<br>
				<!-- <div class = "row">	 -->
					<div id="schedule"></div>
				<!-- </div> -->
			</div>
		</div> <!-- /.widget box -->
	</div> <!-- /.col-md-6 -->
	<!-- /Calendar -->
</div>	

<script type="text/javascript">
	$(document).ready(function(){
		var divHtml = $("#schedule");
		loadDataSchedule(divHtml);

		Date.prototype.addDays = function(days) {
	          var date = new Date(this.valueOf());
	          date.setDate(date.getDate() + days);
	          return date;
	    }
          var date = new Date();

		$('#datetimepicker1').datetimepicker({
		 // startDate: today,
		 // startDate: '+2d',
		 startDate: date.addDays(1),
		});

		$('#datetime_deadline1').prop('readonly',true);
	});

	$(document).on('click','.panel-blue', function () {

    });
</script>
