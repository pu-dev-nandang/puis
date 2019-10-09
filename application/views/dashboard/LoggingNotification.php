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
			dom: 'l<"toolbar">frtip',
			initComplete: function(){
				$("div.toolbar")
					.html('<div class="toolbar no-padding pull-left" style = "margin-left : 10px;">'+
					'<span data-smt="" class="btn btn-read-all" style = "background-color : #0a885f;color:whitesmoke">'+
						'<i class="fa fa-envelope"></i> Set All Read'+
					'</span>'+
				'</div>');
			}  
			
		});
	}

	$(document).off('click', '.btn-read-all').on('click', '.btn-read-all',function(e) {
		if (confirm('Are you sure ?')) {
			var url = base_url_js+'api/__crudLog';
			var data = {
				action : 'ReadAllLog',
				UserID : sessionNIP
			};
			var token = jwt_encode(data,'UAP)(*');
			$.post(url,{token:token},function (jsonResult) {
				loadLoggingNotification();
				showUnreadLog();
			});
		}
	})
</script>