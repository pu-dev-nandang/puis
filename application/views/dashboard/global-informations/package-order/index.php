<div id="general-affair">
	<div class="row">
		<div class="col-sm-12">
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
                                    <th>Expedition</th>
                                    <th>Receiver</th>
                                    <th>Package Note</th>
                                    <th>Package Owner</th>
                                    <th>Package Received</th>
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
                    "data":"ExpeditionCom",
                    "render": function (data, type, row, meta) {
                        var label = '<p><span class="company"><i class="fa fa-compass"></i> '+data+'</span><br><span class="courier"><i class="fa fa-user"></i> '+row.ExpeditionCourier+'</span></p>';
                        return label;
                    }
                },
                {
                    "data":"Receiver",
                    "render": function (data, type, row, meta) {
                        var label = '<p><span class="receiver"><i class="fa fa-user"></i> '+data+'</span><br><span class="date"><i class="fa fa-calendar"></i> '+row.ReceiverDate+'</span></p>';
                        return label;
                    }
                },
                {
                    "data":"Note"                   
                },
                {
                    "data":"PackageOwner"                   
                },
                {
                    "data":"PackageReceiverby",
                    "render": function (data, type, row, meta) {
                        var label = "";
                        if(($.trim(row.PackageReceiverby).length > 0) && ($.trim(row.PackageReceiverDate).length > 0)){
                            label = '<p><span class="received"><i class="fa fa-user"></i> '+data+'</span><br><span class="date"><i class="fa fa-calendar"></i> '+row.PackageReceiverDate+'</span></p>';                         
                        }else{
                            label = '<p class="text-center text-danger"><i class="fa fa-exclamation-triangle"></i> Packet has not been taken</p>';
                        }
                        return label;
                    }
                }
            ]
        });
    }
	$(document).ready(function(){
		fetchPackageOrder();
	});
</script>