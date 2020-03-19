<div id="general-affair">
	<div class="row">
		<div class="col-sm-4">
			<form id="form-package-order" action="<?=base_url('general-affair/save-package-order')?>" method="post" autocomplete="off">
				<input type="hidden" name="ID" class="form-control ID">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"><i class="fa fa-edit"></i> Package Order Form</h4>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label>Courier Expedition</label>
							<input type="text" name="CourierExpedition" class="form-control required CourierExpedition" placeholder="Kurir Ekspedisi">
							<small class="text-danger text-message"></small>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Shipper</label>
									<input type="text" name="Shipper" class="form-control required Shipper" placeholder="Nama Kurir Pengirim">
									<small class="text-danger text-message"></small>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Shipper Date</label>
									<input type="text" name="DateShipper" class="form-control required DateShipper" id="DateShipper" value="<?=date("Y-m-d")?>">
									<small class="text-danger text-message"></small>
								</div>
							</div>
						</div>	
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Receiver</label>
									<input type="text" name="Receiver" class="form-control required Receiver" placeholder="Penerima paket" value="<?=(!empty($employee) ? $employee->Name : '')?>">
									<small class="text-danger text-message"></small>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Receiver Date</label>
									<input type="text" name="DateReceiver" class="form-control required DateReceiver" id="DateReceiver" placeholder="Tgl diterima paket" value="<?=date("Y-m-d")?>">
									<small class="text-danger text-message"></small>
								</div>
							</div>
						</div>	
						<div class="form-group">
							<label>Note</label>
							<textarea class="form-control PackageNote" name="PackageNote" placeholder="Package Name/Type or Description"></textarea>
							<small class="text-danger text-message"></small>
						</div>	
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Package Owner</label>
									<input type="text" name="BelongsTo" class="form-control required BelongsTo" placeholder="Pemilik Paket">
									<small class="text-danger text-message"></small>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Accepted Date</label>
									<input type="text" name="AcceptedDate" class="form-control AcceptedDate" id="AcceptedDate" placeholder="Tgl paket diserahkan">
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
									<th>Courier Expedition</th>
									<th>Shipper</th>
									<th>Shipper Date</th>
									<th>Receiver</th>
									<th>Receiver Date</th>
									<th>Note</th>
									<th>Package Owner</th>
									<th>Accepted Date</th>
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
                url : base_url_js+'general-affair/fetch-package-order', // json datasource
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
            		"data":"CourierExpedition",
            		"render": function (data, type, row, meta) {
            			var label = "";
            			return data;
            		}
            	},
            	{
            		"data":"Shipper"            		
            	},
            	{
            		"data":"DateShipper"         		
            	},
            	{
            		"data":"Receiver"            		
            	},
            	{
            		"data":"DateReceiver"            		
            	},
            	{
            		"data":"PackageNote"            		
            	},
            	{
            		"data":"BelongsTo"            		
            	},   
            	{
            		"data":"AcceptedDate"            		
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
	$(document).ready(function(){
		fetchPackageOrder();
		$("#DateReceiver,#DateShipper,#AcceptedDate").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });
		$("#form-package-order .btn-submit").click(function(){
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
                $("#form-package-order")[0].submit();
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
			    url : base_url_js+"general-affair/detail-package-order",
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
			    		$("#form-package-order").find("."+k).val(v);
			    	});
			    }
			});
        });
	});
</script>