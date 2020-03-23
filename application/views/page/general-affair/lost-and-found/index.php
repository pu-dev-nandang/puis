<div id="general-affair">
	<div class="row">
		<div class="col-sm-12">
			<div class="btn-group" style="padding: 10px 5px;">
				<button class="btn btn-info btn-sm btn-freetext" type="button" data-type="1">Form Attention</button>
				<button class="btn btn-primary btn-sm btn-freetext" type="button" data-type="2">Form Term and conditions</button>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<form id="form-lost-found" action="<?=base_url('general-affair/save-lost-and-found')?>" method="post" autocomplete="off">
				<input type="hidden" name="ID" class="form-control ID">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"><i class="fa fa-edit"></i> Package Order Form</h4>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label>Code</label>
							<input type="text" name="Code" class="form-control required Code" readonly value="<?=$CodeLNF?>">
							<small class="text-danger text-message"></small>
						</div>
						<div class="form-group">
							<label>Item Name</label>
							<input type="text" name="Name" class="form-control required Name">
							<small class="text-danger text-message"></small>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Location</label>
									<input type="text" name="Location" class="form-control required Location">
									<small class="text-danger text-message"></small>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Date Discover</label>
									<input type="text" name="DateDiscover" class="form-control required DateDiscover" id="DateDiscover" value="<?=date("Y-m-d")?>">
									<small class="text-danger text-message"></small>
								</div>
							</div>
						</div>	
						<div class="form-group">
							<label>Description</label>
							<textarea class="form-control Description" name="Description"></textarea>
							<small class="text-danger text-message"></small>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Receiver</label>
									<input type="text" name="Receivedby" class="form-control Receivedby" id="autocomplete-received">
									<small class="text-danger text-message"></small>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Receiver Date</label>
									<input type="text" name="DateReceiver" class="form-control DateReceiver" id="DateReceiver">
									<small class="text-danger text-message"></small>
								</div>
							</div>
						</div>	
						
					</div>
					<div class="panel-footer text-right">
						<button class="btn btn-success btn-sm btn-submit" type="button">Save Changes</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title"><i class="fa fa-bars"></i> List of Package Order</h4>
				</div>
				<div class="panel-body">
					<div id="fetch-data-tables">
						<table class="table table-bordered" id="table-list-data">
							<thead>
								<tr>
									<th width="5%">No</th>
									<th>Code</th>
									<th>Item Name</th>
									<th>Location</th>
									<th>Date Discover</th>
									<th>Description</th>
									<th>Status</th>
									<th>Received</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="10">No data available in table</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function fetchPackageOrder() {
		//var filtering = $("#form-filter").serialize();		
		var filtering = null;
        var token = jwt_encode({Filter : filtering},'UAP)(*');

        var dataTable = $('#fetch-data-tables #table-list-data').DataTable( {
            "destroy": true,
            "retrieve":true,
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "responsive": true,
            "language": {
                "searchPlaceholder": "Name"
            },
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "ajax":{
                url : base_url_js+'general-affair/fetch-lost-and-found', // json datasource
                ordering : false,
                data : {token:token},
                type: "post",  // method  , by default get
                error: function(jqXHR){  // error handling
                    loading_modal_hide();
                    $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        '<h4 class="modal-title">Error Fetch Student Data</h4>');
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
            		"data":"ID",
            		render: function (data, type, row, meta) {
				        return meta.row + meta.settings._iDisplayStart + 1;
				    }
            	},
            	{
            		"data":"Code",
            		/*"render": function (data, type, row, meta) {
            			var label = "";
            			return data;
            		}*/
            	},
            	{
            		"data":"Name"            		
            	},
            	{
            		"data":"Location"         		
            	},
            	{
            		"data":"DateDiscover"            		
            	},
            	{
            		"data":"Description"            		
            	},
            	{
            		"data":"Status",
            		"render": function (data, type, row, meta) {
            			var label = (data == 1) ? "Available":"Unvailable";
            			return label;
            		}            		
            	},
            	{
            		"data":"Receivedby",
            		"render": function (data, type, row, meta) {
            			var label = "";
            			if($.trim(row.Receivedby).length > 0 && $.trim(row.DateReceiver).length > 0){
            				label = '<p><span class="received"><i class="fa fa-user"></i> '+data+'</span><br><span class="date"><i class="fa fa-calendar"></i> '+row.DateReceiver+'</span></p>';
            			}else{label='<p class="text-center text-danger"><i class="fa fa-exclamation-triangle"></i> Item has not been taken</p>';}
            			return label;
            		}          		
            	},   
            	{
            		"data":"ID",
            		"render": function (data, type, row, meta) {
            			var label = '<button class="btn btn-warning btn-sm btn-edit" data-id="'+data+'" title="Edit"><i class="fa fa-edit"></i></button>';
            			return label;
            		}
            	},
        	]
        });
	}

	function receiverName(){
		var result = [];
        $.ajax({
            type : 'POST',
            url : base_url_js+"human-resource/fetch-user",
            dataType : 'json',
            async: false,
            error : function(jqXHR){
                $("body #GlobalModal .modal-body").html(jqXHR.responseText);
                $("body #GlobalModal").modal("show");
            },success : function(response){
                if(!jQuery.isEmptyObject(response)){
                    $.each(response,function(k,v){
                        result.push(v.ID+"/"+v.Name);                    
                    });
                }
            }
        });

        return result;
	}
	$(document).ready(function(){
		fetchPackageOrder();
		$("#DateDiscover,#DateReceiver").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });
		$("#form-lost-found .btn-submit").click(function(){
            var itsme = $(this);
            var itsform = itsme.parent().parent().parent();
            itsform.find(".required").each(function(){
                var value = $(this).val();
                if($.trim(value) == ''){
                    $(this).addClass("error");
                    $(this).parent().find(".text-message").text("Please fill this field");
                    error = false;
                }else{
                    error = true;
                    $(this).removeClass("error");
                    $(this).parent().find(".text-message").text("");
                }
            });
            
            var totalError = itsform.find(".error").length;
            if(error && totalError == 0 ){
                loading_modal_show();
                $("#form-lost-found")[0].submit();
            }else{
                alert("Please fill out the field.");
            }
        });
        $("#general-affair #table-list-data tbody").on("click",".btn-edit",function(){
        	var itsme = $(this);
        	var ID = itsme.data("id");
        	var data = {
              ID : ID,
          	};
          	var token = jwt_encode(data,'UAP)(*');
			$.ajax({
			    type : 'POST',
			    url : base_url_js+"general-affair/detail-lost-and-found",
			    data : {token:token},
			    dataType : 'json',
			    beforeSend :function(){loading_modal_show()},
	            error : function(jqXHR){
	            	loading_modal_hide();
	            	$("body #GlobalModal .modal-body").html(jqXHR.responseText);
		      	  	$("body #GlobalModal").modal("show");
			    },success : function(response){
	            	loading_modal_hide();
			    	$.each(response,function(k,v){
			    		$("#form-lost-found").find("."+k).val(v);
			    	});
			    }
			});
        });
        $("#general-affair").on("click",".btn-freetext",function(){
        	var itsme = $(this);
        	var text = itsme.text();
        	var TYPE = itsme.data('type');
        	var data = {
              TYPE : TYPE,
          	};
          	var token = jwt_encode(data,'UAP)(*');
			$.ajax({
			    type : 'POST',
			    url : base_url_js+"general-affair/form-lost-and-found-info",
			    data : {token:token},
			    dataType : 'json',
			    beforeSend :function(){loading_modal_show()},
	            error : function(jqXHR){
	            	loading_modal_hide();
	            	$("body #GlobalModal .modal-body").html(jqXHR.responseText);
		      	  	$("body #GlobalModal").modal("show");
			    },success : function(response){
	            	loading_modal_hide();
	            	var ID = ""; var Description = "";
	            	if(!jQuery.isEmptyObject(response)){
	            		ID = response.ID;
	            		Description = response.Description;
	            	}
	            	var formInformation = '<form action="'+base_url_js+'general-affair/save-lost-and-found" method="post" autocomplete="off">'+
	            	'<input type="hidden" name="ID" value="'+ID+'">'+
	            	'<input type="hidden" name="Type" value="'+TYPE+'">'+
	            	'<input type="hidden" name="action" value="lost-found-info">'+
	            	'<div class="form-group"><label>Description</label><textarea class="form-control required" required name="Description">'+Description+'</textarea></div>'+
	            	'<div class="text-right"><button class="btn btn-save btn-sm btn-success">Save changes</button></div>'+
	            	'</form>';
	            	$("body #GlobalModal .modal-header").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'+text+'</h4>');
	            	$("body #GlobalModal .modal-body").html(formInformation);
		      	  	$("body #GlobalModal .modal-footer").addClass("hide");
		      	  	$("body #GlobalModal").modal("show");
			    }
			});
        });
		var receiverTags = receiverName();
	    $( "#autocomplete-received" ).autocomplete({
	      source: receiverTags
	    });
	});
</script>