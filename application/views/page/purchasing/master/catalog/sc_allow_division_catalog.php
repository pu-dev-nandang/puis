<script type="text/javascript">
	function loadTable_allow_div(element)
	{
		loading_page(element);
		var url = base_url_js+'purchasing/page/catalog/table_allow_div';
		$.post(url,function (resultJson) {
		    var response = jQuery.parseJSON(resultJson);
		    var html = response.html;
		    var jsonPass = response.jsonPass;
		    $(element).html('<div class = "row"><div class = "col-md-12">'+html+'</div></div>');
		    var table = $('#datatablesServer').DataTable({
		       'iDisplayLength' : 10,
		    });
		    // $('#datatablesServer').DataTable();
		}); // exit spost
	}

	$(document).on('click','.btnpermission', function () {
		var newtr = $(this).closest('tr');
		var btnthis = newtr.find('td:eq(3)').html();
		if (confirm('Are you sure ?')) {
			var department = $(this).attr('department');
			var stnow = $(this).attr('stnow');
			var passaction = (stnow == 1) ? 'delete' : 'add';
			loading_button('.btnpermission[department="'+department+'"]');
			var url = base_url_js+'purchasing/page/catalog/submit-permission-division'; 
			var data = {
				Department : department,
				passaction : passaction
			};

			var token = jwt_encode(data,"UAP)(*");
			$.post(url,{token:token},function (data_json) {
				var resultJson = jQuery.parseJSON(data_json);
				if (resultJson == '') {
					newtr.find('td:eq(3)').empty();
					if (stnow == 1) {
						newtr.find('td:eq(2)').html('Not Allowed');
						newtr.find('td:eq(3)').html('<button class = "btn btn-primary btnpermission" department = "'+department+'" stnow = "0">Allow</button>');
					}
					else
					{
						newtr.find('td:eq(2)').html('Allowed');
						newtr.find('td:eq(3)').html('<button class = "btn btn-inverse btnpermission" department = "'+department+'" stnow = "1">Not Allow</button>');
					}


				}
				else
				{
					newtr.find('td:eq(3)').html(btnthis);
				}

			});

		}
	});
	
</script>