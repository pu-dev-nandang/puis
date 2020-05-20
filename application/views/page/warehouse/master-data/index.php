<style type="text/css">
	.form-control-plaintext {
	    display: block;
	    width: 100%;
	    padding-top: 0.375rem;
	    padding-bottom: 0.375rem;
	    margin-bottom: 0;
	    line-height: 1.5;
	    color: #76838f;
	    background-color: transparent;
	    border: solid transparent;
	    border-width: 1px 0;
	}
	.form-control-plaintext:disabled, .form-control-plaintext[readonly] {
	    background-color: transparent;
	    opacity: 1; 
	}
	.remove-row{cursor: pointer;}
</style>
<div id="master-data">
	<div class="tabulasi">
		<div class="nav-tab">
		  <!-- Nav tabs -->
		  <ul class="nav nav-tabs" role="tablist">
		    <li role="presentation" class="active"><a href="#unit" aria-controls="unit" data-type="m_unit" role="tab" data-toggle="tab">Label Units</a></li>
		    <li role="presentation"><a href="#status" aria-controls="status" role="tab" data-type="m_status_label" data-toggle="tab">Label Status</a></li>
		  </ul>

		  <!-- Tab panes -->
		  <div class="tab-content">
		    <div role="tabpanel" class="tab-pane active" id="unit" style="border:1px solid #ddd;border-top:0px;padding:10px">
		    	<form action="<?=base_url('warehouse/master-save')?>" method="post" autocomplete="off" id="form-master">
		    		<input type="hidden" name="tablename" class="dbtablename">
		    		<div class="panel panel-default">
		    			<div class="panel-heading">
		    				
		    				<div class="row">
		    					<div class="col-sm-8">
		    						<h4 class="panel-title"><i class="fa fa-bars"></i> List of labeling</h4>
		    					</div>
		    					<div class="col-sm-4">
		    						<div class="btn-group pull-right">
					    				<button class="btn btn-primary btn-xs btn-add-record" type="button" ><i class="fa fa-plus"></i> Add new record</button>
					    				<button class="btn btn-warning btn-xs btn-edit-record" data-type="open" type="button" ><i class="fa fa-edit"></i> <span>Edit record</span></button>
				    				</div>
		    					</div>
		    				</div>
		    			</div>
		    				<table class="table" id="table-list-data">
		    					<thead>
		    						<tr>
		    							<th width="2%">No</th>
		    							<th>Name</th>
		    							<th>Description</th>
		    							<th>Is Active</th>
		    						</tr>
		    					</thead>
		    					<tbody>
				    				<tr><td colspan="4">No data available in table</td></tr>
				    			</tbody>
				    			<tfoot>
				    				<tr>
				    					<td colspan="4" class="text-right">
				    						<button class="btn btn-success btn-sm btn-save-changes" type="button" disabled><i class="fa fa-edit"></i> Save Changes</button>
				    					</td>
				    				</tr>
				    			</tfoot>
		    				</table>

		    		</div>
		    	</form>
		    </div>
		  </div>

		</div>
	</div>
</div>



<script type="text/javascript">
	function fetchMasterData(tablename) {
		$("#form-master .dbtablename").val(tablename);
        var data = {
          tablename : tablename,
      	};
      	var token = jwt_encode(data,'UAP)(*');
        var dataTable = $('body #table-list-data').DataTable( {
            "destroy": true,
            "ordering" : false,
            "retrieve":true,
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 5,
            "responsive": true,
            "ajax":{
                url : base_url_js+'warehouse/fetch-master', // json datasource
                ordering : false,
                data : {token:token},
                type: "post",  // method  , by default get
                error: function(jqXHR){  // error handling
                    $('#GlobalModal .modal-header').html('<h4 class="modal-title">Error Fetch Data</h4>'+
                    	'<button type="button" class="close float-right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                    $('#GlobalModal .modal-body').html(jqXHR.responseText);
                    $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
                    $('#GlobalModal').modal({
                        'show' : true,
                        'backdrop' : 'static'
                    });
                }
            },
            "initComplete": function(settings, json) {
                //loading_modal_hide();
            },
            "columns": [
            	{
	                "data": null,
	                "render": function (data, type, row, meta) {
				        return meta.row + meta.settings._iDisplayStart + 1;
				    }
	            },
	            {
	                "data": "Name",
	                "render": function (data, type, row) {
	            		var label = '<input type="hidden" disabled name="ID[]" class="fm" value="'+row.ID+'">';
	            		label += '<input type="text" disabled class="data noborder fm form-control-plaintext required" name="Name[]" value="'+data+'"><small class="text-danger text-message"></small>';
	            		return label;
	            	}
	            },
	            {
	                "data": "Description",
	                "render": function (data, type, row) {
	                	var Description = $.trim(data).length;
	            		var label = '<textarea disabled class="fm data form-control-plaintext" name="Description[]" rows="1">'+((Description > 0) ? data : "")+'</textarea>';
	            		return label;
	            	}
	            },
	            { 
	            	"data": "IsActive",
	            	"render": function (data, type, row) {
	            		var label = '<select disabled name="IsActive[]" class="fm data form-control-plaintext required">';
	            		label += '<option value="1" '+((data == 1) ? 'selected':'')+' >Active</option>';
	            		label += '<option value="2" '+((data == 2) ? 'selected':'')+' >Not Active</option>';
	            		label += '</select><small class="text-danger text-message"></small>';
	            		return label;
	            	}
	          	}
	        ],
	        "order": [[ 1, 'asc' ]]
        });

		dataTable.on( 'order.dt search.dt', function () {
	        dataTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	            cell.innerHTML = i+1;
	        } );
	    } ).draw();
    }

    function fetchLabelStatus(tablename) {
		$("#form-master .dbtablename").val(tablename);
        var data = {
          tablename : tablename,
      	};
      	var token = jwt_encode(data,'UAP)(*');
        var dataTable = $('body #table-list-data').DataTable( {
            "destroy": true,
            "ordering" : false,
            "retrieve":true,
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 5,
            "responsive": true,
            "ajax":{
                url : base_url_js+'warehouse/fetch-master', // json datasource
                ordering : false,
                data : {token:token},
                type: "post",  // method  , by default get
                error: function(jqXHR){  // error handling
                    $('#GlobalModal .modal-header').html('<h4 class="modal-title">Error Fetch Data</h4>'+
                    	'<button type="button" class="close float-right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                    $('#GlobalModal .modal-body').html(jqXHR.responseText);
                    $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
                    $('#GlobalModal').modal({
                        'show' : true,
                        'backdrop' : 'static'
                    });
                }
            },
            "initComplete": function(settings, json) {
                //loading_modal_hide();
            },
            "columns": [
            	{
	                "data": null,
	                "render": function (data, type, row, meta) {
				        return meta.row + meta.settings._iDisplayStart + 1;
				    }
	            },
	            {
	                "data": "Name",
	                "render": function (data, type, row) {
	            		var label = '<input type="hidden" disabled name="ID[]" class="fm" value="'+row.ID+'">';
	            		label += '<input type="text" disabled class="data noborder fm form-control-plaintext required" name="Name[]" value="'+data+'"><small class="text-danger text-message"></small>';
	            		return label;
	            	}
	            },
	            {
	                "data": "Description",
	                "render": function (data, type, row) {
	                	var Description = $.trim(data).length;
	            		var label = '<textarea disabled class="fm data form-control-plaintext" name="Description[]" rows="1">'+((Description > 0) ? data : "")+'</textarea>';
	            		return label;
	            	}
	            },
	            { 
	            	"data": "Code",
	            	"render": function (data, type, row) {
	            		var label = '<input type="text" disabled class="data noborder fm form-control-plaintext required" name="Code[]" value="'+data+'"><small class="text-danger text-message"></small>';
	            		return label;
	            	}
	          	}
	        ],
	        "order": [[ 1, 'asc' ]]
        });

		dataTable.on( 'order.dt search.dt', function () {
	        dataTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	            cell.innerHTML = i+1;
	        } );
	    } ).draw();
    }

    $(document).ready(function(){
    	fetchMasterData('m_unit');
    	
    	$(".nav-tabs").on("click","li",function(){
    		var itsme = $(this);
    		var type = itsme.find("a").data("type");
			$tableMaster = $("body #table-list-data");
			$tableMaster.DataTable().destroy();
			$tableMaster.find("tbody").empty();
			if(type == "m_status_label"){
				fetchLabelStatus(type);
				$tableMaster.find("thead > tr > th:last").text("Code");
			}else{
				fetchMasterData(type);
			}
    	});

    	$formMaster = $("#form-master");
    	$formMaster.on("keyup keydown",".number",function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
        });

    	$tableData = $("#table-list-data > tbody");
    	$(".btn-add-record").click(function(){
			var countTbody = $tableData.find("tr").length;
			var typeTable = $("#form-master .dbtablename").val();
			var element = '<tr class="line-record"><td class="text-danger remove-row"><i class="fa fa-trash"></i></td><td><input type="hidden" name="ID[]"><input type="text" class="form-control required" name="Name[]"><small class="text-danger text-message"></small></td><td><textarea class="form-control" name="Description[]" rows="1"></textarea></td><td><select name="IsActive[]" class="form-control required"><option value="1" selected>Active</option><option value="2">Not Active</option></select><small class="text-danger text-message"></small></td>'+((typeTable == 'divisions') ? "<td><input type='text' name='Abbr[]' class='form-control'></td><td><input type='text' name='Alias[]' class='form-control'></td>":"")+'</tr>';
			var tabActive = $("body .tabulasi ul.nav-tabs > li.active").find("a").data("type");
			if(tabActive == "m_status_label"){
				element = '<tr class="line-record"><td class="text-danger remove-row"><i class="fa fa-trash"></i></td><td><input type="hidden" name="ID[]"><input type="text" class="form-control required" name="Name[]"><small class="text-danger text-message"></small></td><td><textarea class="form-control" name="Description[]" rows="1"></textarea></td><td><input type="text" name="Code[]" class="required number form-control" ><small class="text-danger text-message"></small></td></tr>';
			}
			if(countTbody > 0){
				$tableData.find("tr:first").before(element);
			}else{
				$tableData.append(element);
			}
			$(".btn-save-changes").prop("disabled",false);
		});

		$tableData.on("click",".remove-row",function(){
			$(this).parent().remove();
			$tableData = $("body #table-list-data > tbody");
			var countTbody = $tableData.find("tr.line-record").length;
			if(countTbody == 0){
				$(".btn-save-changes").prop("disabled",true);	
			}
			
		});
		$(".btn-edit-record").click(function(){
			var type=$(this).data("type");
			$tableData = $("body #table-list-data > tbody");
			$tableData.find("tr").each(function(i){
				$(this).find(".fm").prop("disabled",false);
				$(this).find(".data").toggleClass("form-control-plaintext form-control");
			});
			if(type == "open"){
				$(this).find(".fa").toggleClass("fa-edit fa-times");
				$(this).find("span").text("Cancel edit record");
				$(this).data("type","close");
				$(".btn-save-changes").prop("disabled",false);
			}else{
				$tableData.find("tr").each(function(i){
					$(this).find(".fm").prop("disabled",true);
				});
				$(this).find(".fa").toggleClass("fa-times fa-edit");
				$(this).find("span").text("Edit record");
				$(this).data("type","open");
				$(".btn-save-changes").prop("disabled",true);
			}
		});

		$("body").on("click",".paginate_button",function(){
			$(".btn-save-changes").prop("disabled",true);
			$(".btn-edit-record").data("type","open");
			$(".btn-edit-record").find("i").removeAttr("class").addClass("fa fa-edit");
			$(".btn-edit-record").find("span").text("Edit record");
		});

		$(".btn-save-changes").click(function(){
			var error = false;
			var itsme = $(this);
			var itsform = itsme.parents("#form-master");
			itsform.find(".required").each(function(){
			  	var value = $(this).val();
			  	console.log($(this).attr('name'));
			  	if($.trim(value) == ''){
			  		$(this).addClass("error");
			  		$(this).parent().find(".text-message").text("Please fill out this field");
			  		error = false;
			  	}else{
			  		error = true;
			  		$(this).removeClass("error");
			  	}
		  	});
		 	
		 	var totalError = itsform.find(".error").length;
		  	if(error && totalError == 0 ){
				$(this).prop("disabled",true);
				
				var dataPost = itsform.serialize();
        
		        var token = jwt_encode({POST : dataPost},'UAP)(*');

		        $.ajax({
				    type : 'POST',
				    url : itsform.attr("action"),
				    data : {token:token},
				    dataType : 'json',
				    beforeSend :function(){},
		            error : function(jqXHR){
		            	$("body #GlobalModal .modal-body").html(jqXHR.responseText);
			      	  	$('body #GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
			      	  	$("body #GlobalModal").modal("show");
				    },success : function(response){
				    	$(".btn-edit-record").data("type","open");
						$(".btn-edit-record").find("i").removeAttr("class").addClass("fa fa-edit");
						$(".btn-edit-record").find("span").text("Edit record");
		            	toastr.info(response.message,'Info!');
		            	$('body #table-list-data').DataTable().destroy();
		            	if(response.tablename == "m_unit"){
		            		fetchMasterData(response.tablename);		            		
		            	}else{
		            		fetchLabelStatus(response.tablename);
		            	}
				    }
				});

		  	}else{
		  		alert("Please check out your form again.");
		  	}
		});
    });
</script>