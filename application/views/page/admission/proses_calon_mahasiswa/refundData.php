<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<div class="widget-header">
				<h4 class="header"><i class="icon-reorder"></i> <?php echo $NameMenu ?></h4>
			</div>
			<div class="widget-content">
				<div class="row">
					<div class="col-md-4 col-md-offset-4">
						<div class="thumbnail">
							<div class="form-group" style="padding: 10px;">
								<label>Class Of</label>
								<select class="form-control" id = "searchClassOf">
									
								</select>
							</div>
						</div>
					</div>
				</div>
			    <div class="row">
			    	<div class="col-md-12">
			    		<div style="margin: 10px;">
			    			<table class="table table-bordered" id = "tableRefund">
			    				<thead>
			    					<tr>
			    						<td>No</td>
			    						<td style="width: 25%">Personal Info</td>
			    						<td>Payment Total</td>
			    						<td>Refund Price</td>
			    						<td>Desc</td>
			    						<td>By & At</td>
			    						<td>Action</td>
			    					</tr>
			    				</thead>
			    				<tbody></tbody>
			    			</table>
			    		</div>
			    	</div>
			    </div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	const getUrl = window.location.href;
	let oTableRefund;

	const loadDefault = () => {
		SelectOpLoadClassOf();
		LoadDataRefund();
	};

	const SelectOpLoadClassOf = () => {
		const starClassOf = 2019;
		const endClassOf = <?php echo date('Y') ?>;
		$('#searchClassOf').empty();
		for (var i = starClassOf; i <= endClassOf; i++) {
			const selected = (i == endClassOf) ? 'selected' : '';
			$('#searchClassOf').append(
				'<option value = "'+i+'" '+selected+' >Class of '+i+'</option>'
				);
		}
	};

	const LoadDataRefund = () => {
		$('#tableRefund tbody').empty();
		var table = $('#tableRefund').DataTable({
		    "fixedHeader": true,
		    "processing": true,
		    "destroy": true,
		    "serverSide": true,
		    "lengthMenu": [
		        [10,25],
		        [10,25]
		    ],
		    "iDisplayLength": 10,
		    "ordering": false,
		    "language": {
		        "searchPlaceholder": "Search Formulir Code / Name",
		    },
		    "ajax": {
		        url: getUrl, // json datasource
		        ordering: false,
		        type: "post", // method  , by default get
		        data: function(token) {
		            // Read values
		            var classOf = $('#searchClassOf option:selected').val();
		            var data = {
		            	action : 'read',
		            	data : {
		            		classOf: classOf
		            	}
		                
		            };
		            
		            var get_token = jwt_encode(data, "UAP)(*");
		            token.token = get_token;
		        },
		        error: function() { // error handling
		            $(".tableRefund-grid-error").html("");
		            $("#tableRefund-grid").append(
		                '<tbody class="tableRefund-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
		            );
		            $("#tableRefund-grid_processing").css("display", "none");
		        }
		    },
		    'columnDefs': [
		        {
		          'targets': 0,
		          'searchable': false,
		          'orderable': false,
		          'className': 'dt-body-center',
		        },
		        {
		          'targets': 1,
		          'searchable': false,
		          'orderable': false,
		          'className': 'dt-body-center',
		          'render': function (data, type, full, meta){
		            let html = '';
		            let dt = full[1];
		            html = dt['No_Ref']+' / '+dt['FormulirCode']+'<br/>'+'<span style = "color : green;">'+dt['Name']+'</span>';
		            return html;
		           }
		        },
		        {
		          'targets': 2,
		          'searchable': false,
		          'orderable': false,
		          'className': 'dt-body-center',
		          'render': function (data, type, full, meta){
		            let html = '';
		            let dt = full[2];
		            let Tot = 0;
		            for (var i = 0; i < dt.length; i++) {
		            	Tot += parseInt(dt[i].Invoice);
		            }
		            html = formatRupiah(Tot);
		            return html;
		           }
		        },
		        {
		          'targets': 3,
		          'searchable': false,
		          'orderable': false,
		          'className': 'dt-body-center',
		          'render': function (data, type, full, meta){
		            let html = '';
		            let dt = full[3];
		            html = formatRupiah(dt);
		            return html;
		           }
		        },
		        {
		          'targets': 5,
		          'searchable': false,
		          'orderable': false,
		          'className': 'dt-body-center',
		          'render': function (data, type, full, meta){
		            let html = '';
		            html =  moment(full[6]).format('DD MMM YYYY') + '<br/>'+'<span style = "color : blue;">'+full[5]+'</span>';
		            return html;
		           }
		        },
		        {
		          'targets': 6,
		          'searchable': false,
		          'orderable': false,
		          'className': 'dt-body-center',
		          'render': function (data, type, full, meta){
		            let html = '';
		            return html;
		           }
		        },
		    ],
		    'createdRow': function(row, data, dataIndex) {
		        
		    },
		    dom: 'l<"toolbar">frtip',
		    "initComplete": function(settings, json) {

		    }
		});

		oTableRefund = table;
	}

	$(document).ready(function(e){
		loadDefault();
	})

	$(document).on('change','#searchClassOf',function(e){
		oTableRefund.ajax.reload(null, false);
	})
</script>