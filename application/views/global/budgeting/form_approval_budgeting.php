<div class="col-md-12">
	<div class="col-md-6 col-md-offset-3">
		<div class="thumbnail" style="height: 100px">
			<div class="col-xs-6 col-md-offset-3"">
				<div class="form-group">
					<label>Departement</label>
					<select class="select2-select-00 full-width-fix" id="Departement">
					     <!-- <option></option> -->
					 </select>
				</div>	
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		LoadFirstLoad()
	    
	}); // exit document Function

	function LoadFirstLoad()
	{
		getAllDepartementPU();
	}

	function getAllDepartementPU()
	{
	  var url = base_url_js+"api/__getAllDepartementPU";
	  $('#Departement').empty();
	  $.post(url,function (data_json) {
	    for (var i = 0; i < data_json.length; i++) {
	        var selected = (i==0) ? 'selected' : '';
	        $('#Departement').append('<option value="'+ data_json[i]['Code']  +'" '+selected+'>'+data_json[i]['Name2']+'</option>');
	    }
	   
	    $('#Departement').select2({
	       //allowClear: true
	    });

	    $("#Departement").change(function(){
	    	loadPageData();
	    })
	    loadPageData();
	  })
	}

	function loadPageData()
	{
		if (!jQuery("#pageInputApproval").length) {
		  var html2 = '<div class = "row" id = "pageInputApproval" style =  "margin-top : 10px"></div>';
		  $("#pageContent").after(html2);
		}
		
		loading_page("#pageInputApproval");
		var url = base_url_js+"budgeting/getLoadApprovalBudget";
		var data = {
					Departement : $("#Departement").val(),
				};
		var token = jwt_encode(data,'UAP)(*');
		$.post(url,{token:token},function (resultJson) {
			var response = jQuery.parseJSON(resultJson);
			var html = response.html;
			var jsonPass = response.jsonPass;
			setTimeout(function () {
			    $("#pageInputApproval").html(html);
			},1000);
		});
	}
</script>
