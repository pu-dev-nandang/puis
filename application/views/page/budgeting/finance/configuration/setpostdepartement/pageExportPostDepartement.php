<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="thumbnail">
			<div class="row">
				<div class="col-xs-6">
					<div class="form-group">
						<label>Year</label>
						<select class="select2-select-00 full-width-fix" id="YearPostDepartement">
						     <!-- <option></option> -->
						 </select>
					</div>	
				</div>	
				<div class="col-xs-6">
					<div class="form-group">
						<label>Department</label>
						<select class="select2-select-00 full-width-fix" id="DepartementPost">
						     <!-- <option></option> -->
						 </select>
					</div>	
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<button class="btn btn-inverse" id = "exportexcelpost">Export</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		// load Year
		$("#YearPostDepartement").empty();
		var url = base_url_js+'budgeting/table_all/cfg_dateperiod/1';
		var thisYear = (new Date()).getFullYear();
		$.post(url,function (resultJson) {
			var response = jQuery.parseJSON(resultJson);
			for(var i=0;i<response.length;i++){
			    //var selected = (i==0) ? 'selected' : '';
			    var selected = (response[i].Activated==1) ? 'selected' : '';
			    $('#YearPostDepartement').append('<option value="'+response[i].Year+'" '+selected+'>'+response[i].Year+' - '+(parseInt(response[i].Year) + 1)+'</option>');
			}
			$('#YearPostDepartement').select2({
			   //allowClear: true
			});

			getAllDepartementPU();
		});

		function getAllDepartementPU()
		{
		  var url = base_url_js+"api/__getAllDepartementPU";
		  $('#DepartementPost').empty();
		  $('#DepartementPost').append('<option value="all" >'+'All'+'</option>');
		  $.post(url,function (data_json) {
		    for (var i = 0; i < data_json.length; i++) {
		        var selected = (i==0) ? 'selected' : '';
		        $('#DepartementPost').append('<option value="'+ data_json[i]['Code']  +'" '+selected+'>'+data_json[i]['Name2']+'</option>');
		    }
		   
		    $('#DepartementPost').select2({
		       //allowClear: true
		    });

		  })
		} 
	    
	}); 
</script>