<div class="row noprint">
  <div class="col-md-12">
   <div class="well">
     <h5>Search</h5>
     <div class="row">
       <div class="col-md-3">
       	<label>Choose TA</label>
       	    <select class="form-control" id="selectTA">
       	    </select>
       </div>
       <div class="col-md-2" style="margin-top: 22px">
       		<div class="DTTT btn-group">
	        	<button type="button" class="btn btn-convert" id="export_excel_titipan"><i class="fa fa-download" aria-hidden="true"></i> Excel</button>
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
		 loadSelectOptionStudentYear('#selectTA','');

		$('#export_excel_titipan').click(function(){

			var url = base_url_js+'C_save_to_excel2/reportFin_deposit';
			data = {
			  TA : $("#selectTA").val(),
			}
			var token = jwt_encode(data,"UAP)(*");
			submit(url, 'POST', [
			    { name: 'token', value: token },
			]);
		})
	});
</script>