<div id="good-receive">
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="row">
				<div class="col-sm-8">
					<h4 class="panel-title"><i class="fa fa-bars"></i> List of good receive</h4>
				</div>
				<div class="col-sm-4">
					<div class="btn-group pull-right">
	    				<button class="btn btn-primary btn-xs btn-add-record" type="button" ><i class="fa fa-plus"></i> Add new record</button>
    				</div>
				</div>
			</div>
		</div>
		<div class="panel-body">
			<table class="table" id="table-list-data">
				<thead>
					<tr>
						<th width="2%">No</th>
						<th>Purchase Order Code</th>
						<th>Delivery Order Code</th>
						<th>Name of Item</th>
						<th>Total</th>
						<th>Unit</th>
						<th>Date of Item Arrived</th>
						<th>Date of Item Received</th>
						<th>Department</th>
						<th>Status Item</th>
						<th>Condition</th>
						<th>Category Item</th>
					</tr>
				</thead>
				<tbody>
    				<tr><td colspan="12">No data available in table</td></tr>
    			</tbody>
			</table>
		</div>
	</div>
</div>


<script type="text/javascript">
	function fetchInventory() {
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
                url : base_url_js+'warehouse/fetch-inventory', // json datasource
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
	            {
	                "data": "DateItemArrived",
	                "render": function (data, type, row) {
	            		return data;
	            	}
	            },
	            {
	                "data": "DateItemReceived",
	                "render": function (data, type, row) {
	            		return data;
	            	}
	            },
	            {
	                "data": "DeptAbbr",
	                "render": function (data, type, row) {
	            		return data;
	            	}
	            },
	            {
	                "data": "StatusItemID",
	                "render": function (data, type, row) {
	            		return data;
	            	}
	            },
	            {
	                "data": "ConditionID",
	                "render": function (data, type, row) {
	            		return data;
	            	}
	            },
	            {
	                "data": "CategItemID",
	                "render": function (data, type, row) {
	            		return data;
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
    	fetchInventory();
    });
</script>