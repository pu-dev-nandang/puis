<style type="text/css">
	#tableData6 thead th,#tableData6 tfoot td {

	    text-align: center;
	    background: #20485A;
	    color: #FFFFFF;

	}

	#tableData6>thead>tr>th, #tableData6>tbody>tr>th, #tableData6>tfoot>tr>th, #tableData6>thead>tr>td, #tableData6>tbody>tr>td, #tableData6>tfoot>tr>td {
	    border: 1px solid #b7b7b7
	}

	.btn span.glyphicon {    			
		opacity: 0;				
	}
	.btn.active span.glyphicon {				
		opacity: 1;				
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
	window.WCodePostRealisasi = '';
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
		    // obj = obj.sortBy('ID', 'NameUserRole');
		    jsonUserRole = obj.sort(dynamicSort("ID"));
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
		    WCodePostRealisasi = selectedObj.value;
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

		// find CodePostRealisasi
		var url = base_url_js+'budgeting/table_cari/cfg_set_userrole/CodePostRealisasi/'+WCodePostRealisasi;
		$.post(url,function (data_json) {
		    var obj = JSON.parse(data_json);
		    // obj = obj.sortBy('ID_m_userrole'.asc, 'Active'.desc);
		    obj = obj.sort(dynamicSort("ID_m_userrole"));
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
		    		var checked = '';
		    		var active = '';
		    		if(obj.length > 0)
		    		{
		    			// find ID_m_userrole
    			    	var bool = false;
    			        for (var k = 0; k < obj.length; k++) {
    			        	if(obj[k]['ID_m_userrole'] == jsonUserRole[j]['ID'])
    			        	{
    			        		bool = true;
    			        		break;
    			        	}
    			        }
		    			// check status
		    			switch(arrAction[i]) {
		    			    case 'Entry':
		    			        if(bool)
		    			        {
		    			        	if(obj[k]['Entry'] == 1)
		    			        	{
		    			        		checked = 'checked';
		    			        		active = 'active';
		    			        	}
		    			        }
		    			        break;
		    			    case 'Approved':
		    			        if(bool)
		    			        {
		    			        	if(obj[k]['Approved'] == 1)
		    			        	{
		    			        		checked = 'checked';
		    			        		active = 'active';
		    			        	}
		    			        }
		    			        break;
		    			    case 'Cancel':
		    			        if(bool)
		    			        {
		    			        	if(obj[k]['Cancel'] == 1)
		    			        	{
		    			        		checked = 'checked';
		    			        		active = 'active';
		    			        	}
		    			        }
		    			        break;
		    			}
		    		}
		    		var Input = '<div class="btn-group" data-toggle="buttons"><label class="btn btn-success '+active+'">'+
		    						'<input type="checkbox" autocomplete="off" '+checked+' class = "checkboxAction">'+
		    						'<span class="glyphicon glyphicon-ok"></span>'+
		    					'</label></div>';
		    		TD += '<td ID_m_UserRole = "'+jsonUserRole[j]['ID']+'" ActionUser = "'+arrAction[i]+'" align = "center">'+Input+'</td>';
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

		    $("#loadPageTable").html(DivHeader+TableGenerate);

		    $(".checkboxAction").change(function(){
		    	var aa = $(this).closest("td");
		    	var id_m_userrole = aa.attr('id_m_userrole');
		    	var field = aa.attr('actionuser');
		    	var Input = 0;
		    	if(this.checked)
		    	{
		    		Input = 1;
		    	}

		    	var url = base_url_js+'budgeting/save_cfg_set_userrole';
		    	var data = {
		    	            id_m_userrole : id_m_userrole,
		    	            field : field,
		    	            CodePostRealisasi : WCodePostRealisasi,
		    	            Input : Input
		    	            };
		    	var token = jwt_encode(data,"UAP)(*");
		    	$.post(url,{token:token},function (data_json) {
		    	   
		    	})
		    })

		    
		})
	}
</script>