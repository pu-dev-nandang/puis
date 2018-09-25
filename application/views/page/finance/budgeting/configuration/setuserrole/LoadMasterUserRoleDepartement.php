<style type="text/css">
	#tableData6 thead th,#tableData6 tfoot td {

	    text-align: center;
	    background: #20485A;
	    color: #FFFFFF;

	}

	#tableData6>thead>tr>th, #tableData6>tbody>tr>th, #tableData6>tfoot>tr>th, #tableData6>thead>tr>td, #tableData6>tbody>tr>td, #tableData6>tfoot>tr>td {
	    border: 1px solid #b7b7b7
	}

</style>
<div class="row" style="margin-right: 0px;margin-left: 0px">
	<div class="col-md-6 col-md-offset-3">
		<div class="thumbnail" style="height: 90px">
			<div class="col-md-6 col-md-offset-3">
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
	<div class="col-md-12" id = "loadPageTable">

	</div>
</div>

<script type="text/javascript">
	window.jsonUserRole = [];
	window.DepartementUserRole = '';
	window.RealisasiPostNameUserRole = '';
	$(document).ready(function() {
		LoadFirstLoad();
	}); // exit document Function

	function LoadFirstLoad()
	{
		AutoCompletePostDepartement();
		getUserRole();
	}

	function getUserRole()
	{
		var url = base_url_js+'budgeting/table_all/cfg_m_userrole';
		$.post(url,function (data_json) {
		    var obj = JSON.parse(data_json);
		    obj = obj.sortBy('ID', 'NameUserRole');
		    jsonUserRole = obj;
		})
	}

	function AutoCompletePostDepartement()
	{
		temp = '';
		$("#PostDepartementAutoComplete").autocomplete({
		  minLength: 2,
		  select: function (event, ui) {
		    event.preventDefault();
		    var selectedObj = ui.item;
		    var label = selectedObj.label;
		    label = label.split(' | ');
		    DepartementUserRole = label[2];
		    RealisasiPostNameUserRole = label[1];
		    // console.log(DepartementUserRole + ' = ' + RealisasiPostNameUserRole);
		    $("#PostDepartementAutoComplete").val(selectedObj.value);
		    loadPageTable(); 
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
		    $("#loadPageTable").empty();          
		    $.post(url,{token:token},function (data_json) {
		        var obj = JSON.parse(data_json);
		        // console.log(obj);
		        add(obj.message) 
		    })
		  } 
		})

	}

	function loadPageTable()
	{
		var CodePostRealisasi = $("#PostDepartementAutoComplete").val();
		var DivHeader = '<div class = "row">'+	
							'<div class = "col-xs-3">'+
								'<div class = "col-xs-12"><b>Departement : ' + DepartementUserRole+ '</b></div>'+
								'<div class = "col-xs-12"><b>PostRealizationName : ' + RealisasiPostNameUserRole+ '</b></div>'+
							'</div>'+
						'</div>';	


		var TableGenerate = '<div class = "row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px">'+
								'<div class = "col-xs-12">'+
									'<div class="table-responsive">'+
									'<table class="table table-bordered tableData" id ="tableData6">'+
									'<thead>';
							;
			TableGenerate += '<tr><th></th>';				

		for (var i = 0; i < jsonUserRole.length; i++) {
			TableGenerate += '<th>'+jsonUserRole[i]['NameUserRole']+'</th>';
		}

		TableGenerate += '</tr></thead><tbody>';
		var arrAction = ['Entry','Approved','Cancel'];
		for (var i = 0; i < arrAction.length; i++) {
			var TD = '';
			for (var j = 0; j < jsonUserRole.length; j++) {
				var Input = '<div class="checkbox checbox-switch switch-primary">'+
		                        '<label>'
		                            '<input type="checkbox" id="layoutExam">'
		                            '<span></span>'
		                            '<b> | Random Layout</b>'
		                        '</label>'
                    		'</div>';
				TD += '<td ID_m_UserRole = "'+jsonUserRole[j]['ID']+'" ActionUser = "'+arrAction[i]+'">'+Input+'</td>';
			}
			TableGenerate += '<tr>'+
								'<td>'+arrAction[i]+'</td>'+
								TD+
							 '</tr>';	
		}
		TableGenerate += '</tbody></table></div></div></div>';

		var Submit = '<div class = "row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px">'+
						'<div class="col-md-3 col-md-offset-9" align="center"><button type="button" id="SaveMasterUserRole" class="btn btn-success" >Save</button></div>'+
					 '</div>';	

		$("#loadPageTable").html(DivHeader+TableGenerate+Submit);
	}
</script>