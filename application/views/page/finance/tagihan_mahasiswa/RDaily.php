<div class="row noprint">
  <div class="col-md-12">
   <div class="well">
     <h5>Search</h5>
     <div class="row">
       <div class="col-md-3">
           <label>Daily Penerimaan Bank</label>
           	<div id="datetimepicker" class="input-group input-append date datetimepicker"><input data-format="yyyy-MM-dd" class="form-control" id="DailyTgl" type=" text" readonly="" value="<?php echo date('Y-m-d') ?>"><span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span></div>
       </div>
       <div class="col-md-3">
       	<label>Semester</label>
       	    <select class="form-control" id="selectSemester">
       	    </select>
       </div>
       <div class="col-md-2" style="margin-top: 22px">
       		<div class="DTTT btn-group">
	        	<button type="button" class="btn btn-convert" id="export_excel_daily"><i class="fa fa-download" aria-hidden="true"></i> Excel</button>
	      </div>
       </div>
     </div>
   </div>
  </div>
</div>
<div id='conTainS'>
    
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$('#datetimepicker').datetimepicker({
		  format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
		});
		loadSelectOptionSemesterByload('#selectSemester',1,'');

		$('#export_excel_daily').click(function(){

			var url = base_url_js+'finance/export_excel_report_daily';
			data = {
			  DailyTgl : $("#DailyTgl").val(),
			  selectSemester : $("#selectSemester").val(),
			}
			var token = jwt_encode(data,"UAP)(*");
			submit(url, 'POST', [
			    { name: 'token', value: token },
			]);
		})
	});
</script>