<style>
.req-btn{text-align: center;cursor: pointer;border-left: 1px solid #ddd;background: #f3f3f3}
.no-padding{padding: 0px}
.panel-title, .req-btn{padding: 10px 0px;font-size: 14px;margin: 0px}
</style>
<div id="stock-good">
	<div class="panel panel-default">
	<div class="panel-heading" style="padding-top:0px;padding-bottom:0px;padding-right:0px">
		<div class="head">
			<div class="row">
				<div class="col-sm-10">
					<h4 class="panel-title"><i class="fa fa-bars"></i> List of Purchase Order</h4>				
				</div>
				<div class="col-sm-2">
					<p class="req-btn" onclick="location.href='<?=base_url('stock-good/form')?>';">
						<i class="fa fa-plus"></i> Add new request
					</p>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="fetch-table">
			<table class="table table-bordered" id="table-list-data">
				<thead>
					<tr>
						<th width="2%">No</th>
						<th>Purchase Order Code</th>
						<th>Date Purchase Order</th>
						<th>Total Item</th>
						<th>Status</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="6">No data available in table</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	</div>
</div>

<script>
function fetchPurchaseOrder() {
        var data = {};
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
                url : base_url_js+'stock-good/fetch-my-purchase-order', // json datasource
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
	                "data": "PurchaseOrderCode",
	                "render": function (data, type, row) {
	            		return data;
	            	}
	            },
	            {
	                "data": "DeliveryOrderCode",
	                "render": function (data, type, row) {
	            		return data;
	            	}
	            },
	            {
	                "data": "ItemName",
	                "render": function (data, type, row) {
	            		return data;
	            	}
	            },
	            {
	                "data": "Total",
	                "render": function (data, type, row) {
	            		return data;
	            	}
	            },
	            {
	                "data": "UnitID",
	                "render": function (data, type, row) {
	            		return data;
	            	}
	            },
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
    	fetchPurchaseOrder();
    });
</script>