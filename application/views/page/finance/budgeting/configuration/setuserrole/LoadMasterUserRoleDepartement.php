<style type="text/css">
	#tableData5 thead th,#tableData5 tfoot td {

	    text-align: center;
	    background: #20485A;
	    color: #FFFFFF;

	}

	#tableData5>thead>tr>th, #tableData5>tbody>tr>th, #tableData5>tfoot>tr>th, #tableData5>thead>tr>td, #tableData5>tbody>tr>td, #tableData5>tfoot>tr>td {
	    border: 1px solid #b7b7b7
	}
</style>
<div class="row" style="margin-right: 0px;margin-left: 0px">
	<div class="col-md-6 col-md-offset-3">
		<div class="thumbnail" style="height: 100px">
			<div class="col-xs-6">
				<div class="form-group">
					<label>Year</label>
					<select class="select2-select-00 full-width-fix" id="YearUserRoleDepartement">
					 </select>
				</div>	
			</div>
			<div class="col-xs-6">
				<div class="form-group">
					<label>Search</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="icon-search"></i></span>
						<input type="search" class="form-control" placeholder="Post Departement" id = "PostDepartementAutoComplete">
					</div>
				</div>	
			</div>
		</div>
	</div>	
</div>	

<div class="row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px">
	<div class="col-md-6" id = "loadPageTable1">

	</div>
	<div class="col-md-6" id = "loadPageTable2">
		
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		LoadFirstLoad();
	}); // exit document Function

	function LoadFirstLoad()
	{
		$("#YearUserRoleDepartement").empty();
		var url = base_url_js+'budgeting/table_all/cfg_dateperiod/1';
		var thisYear = (new Date()).getFullYear();
		$.post(url,function (resultJson) {
			var response = jQuery.parseJSON(resultJson);
			for(var i=0;i<response.length;i++){
			    //var selected = (i==0) ? 'selected' : '';
			    var selected = (response[i].Year==thisYear) ? 'selected' : '';
			    $('#YearUserRoleDepartement').append('<option value="'+response[i].Year+'" '+selected+'>'+response[i].Year+'</option>');
			}
			$('#YearUserRoleDepartement').select2({
			   //allowClear: true
			});
			AutoCompletePostDepartement();
		});
		 
		
	}

	function AutoCompletePostDepartement()
	{
		temp = '';
		$("#PostDepartementAutoComplete").autocomplete({
		  minLength: 2,
		  select: function (event, ui) {
		    event.preventDefault();
		    var selectedObj = ui.item;
		    $("#PostDepartementAutoComplete").val(selectedObj.value); 
		  },
		  /*select: function (event,  ui)
		  {

		  },*/
		  source:
		  function(req, add)
		  {
		    var url = base_url_js+'budgeting/AutoCompletePostDepartement';
		    var PostDepartement = $('#PostDepartementAutoComplete').val();
		    var data = {
		                PostDepartement : PostDepartement,
		                };
		    var token = jwt_encode(data,"UAP)(*");          
		    $.post(url,{token:token},function (data_json) {
		        var obj = JSON.parse(data_json);
		        // console.log(obj);
		        add(obj.message) 
		    })
		  } 
		})

	}

	function loadPageTable1()
	{
		var TableGenerate = '<div class="table-responsive">'+
								'<table class="table table-bordered" id ="tableData5">'+
								'<thead>'+
								'<tr>'+
									'<th width = "3%">No</th>'+
		                            '<th>Departement</th>'+
		                            '<th>Code</th>'+
									'<th>Post Realization</th>'+
									'<th>Year</th>'+
									'<th>Budget</th>'+
									'<th>Action</th>'+
								'</tr></thead>'	
							;
		TableGenerate += '<tbody>';
	}
</script>