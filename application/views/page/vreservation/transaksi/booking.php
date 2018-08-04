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
								<input data-format="yyyy-MM-dd" class="form-control" id="datetime_deadline1" type="	text" readonly="" >
								<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
						</div>
					</div>
					<div class="col-xs-3">
					<button class="btn btn-success" id = "search"><span class="glyphicon glyphicon-search"></span> Search</button>
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
			format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
		 startDate: date.addDays(0),
		});

		$('#datetime_deadline1').prop('readonly',true);
	});

	$(document).on('click','.panel-blue', function () {

    });
</script>
