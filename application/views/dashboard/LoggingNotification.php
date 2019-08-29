<div class="row" style="margin-top: 30px;">
    <div class="col-md-8 col-md-offset-2">
        <div id="loadTableNotif"></div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
	    loadLoggingNotification();
	});

	function loadLoggingNotification()
	{
		$('#loadTableNotif').html('<table id="tableNotif" class="table table-hover">' +
		    '                <thead>' +
		    '                <tr>' +
		    '                    <th style="width: 5%;">Notifications</th>' +
		    '                    <th></th>' +
		    '                </tr>' +
		    '                </thead>' +
		    '                <tbody id="dataNotif"></tbody>' +
		    '            </table>');
		var data = {
			NIP :sessionNIP
		};
		var token = jwt_encode(data,'UAP)(*');
		var dataTable = $('#tableNotif').DataTable( {
		    "processing": true,
		    "serverSide": true,
		    "iDisplayLength" : 10,
		    "ordering" : false,
		    "language": {
		        "searchPlaceholder": "Search"
		    },
		    "ajax":{
		        url : base_url_js+'rest2/__getNotification', // json datasource
		        ordering : false,
		        data : {token:token},
		        type: "post",  // method  , by default get
		        error: function(){  // error handling
		            $(".employee-grid-error").html("");
		            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
		            $("#employee-grid_processing").css("display","none");
		        }
		    },
		    'createdRow': function( row, data, dataIndex ) {
		    	if (data[2] == 1) {
		    		$(row).attr('style','background-color: #eaf1fb;')
		    	}
		    },
		});
	}
</script>