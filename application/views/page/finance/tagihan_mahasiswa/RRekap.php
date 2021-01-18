<div class="row noprint">
  <div class="col-md-12">
   <div class="well">
     <div class="row">
       <div class="col-md-8">
       	<label>Choose Status Student</label>
       	   <div class="fillCheckboxStatus">
       	   		
       	   </div>
       </div>
       <div class="col-md-2" style="margin-top: 22px">
       		<div class="DTTT btn-group">
	        	<button type="button" class="btn btn-convert" id="export_excel_rekap"><i class="fa fa-download" aria-hidden="true"></i> Excel</button>
	      </div>
       </div>
     </div>
   </div>
  </div>
</div>
<div id='conTainS'>
    
</div>

<script type="text/javascript">
	$(document).ready(function(e){
		let selectorCheckBoxStatus = $('.fillCheckboxStatus');
		checkboxListStatuStudent(selectorCheckBoxStatus);

		$('#export_excel_rekap').click(function(){

			// get all checkbox

			let dataCheckbox = [];

			$('.checkboxStatus:checked').each(function(e){
				dataCheckbox.push($(this).val());

			})

			if (dataCheckbox.length > 0) {
				var url = base_url_js+'C_save_to_excel2/reportFin_rekap';
				data = {
				  StatusStudentArr : dataCheckbox,
				}
				var token = jwt_encode(data,"UAP)(*");
				submit(url, 'POST', [
				    { name: 'token', value: token },
				]);
			}
			else
			{
				toastr.info('Please choose status student');
			}

			
		})

	})
</script>