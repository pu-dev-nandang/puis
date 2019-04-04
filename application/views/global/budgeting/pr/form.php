<div class="row" style="margin-left: 0px;margin-right: 0px;">
	<div class="col-md-12">
		<div class="table-responsive">
			<div id = "PageDataExisting">
			</div>
		</div>
	</div>
</div>

<div class="row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "dtContent">
	
</div>
<script type="text/javascript">
	var ClassDt = {
		BudgetRemaining : [],
		PRCodeVal : "<?php echo $PRCodeVal ?>",
		Year : "<?php echo $Year ?>",
		RuleAccess : [],
	};

	$(document).ready(function() {
		LoadFirstLoad();
	})

	function LoadFirstLoad()
	{
		// check Rule for Input
		var url = base_url_js+"budgeting/checkruleinput";
		var data = {
			NIP : NIP,
		};
		if (ClassDt.PRCodeVal != '') {
			data = {
				NIP : NIP,
				Departement : DivSession,
				PRCodeVal : ClassDt.PRCodeVal,
			};
		}
		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{ token:token },function (resultJson) {
			var response = jQuery.parseJSON(resultJson);

			var access = response['access'];
			if (access.length > 0) {
				ClassDt.RuleAccess = response;
				load_htmlPR();
			}
			else
			{
				$("#pageContent").empty();
				$("#pageContent").html('<h2 align = "center">Your not authorize these modul</h2>');
			}
			
		})
	}

	function load_htmlPR()
	{
		// check data edit or new
		if (ClassDt.PRCodeVal != '') {
			// edit
		}
		else
		{
			// new
			makeDomAwal();
		}
	}

	function makeDomAwal()
	{
		var html = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;">';
			html += '<div class="col-md-4">'+
						'<p id = "labelPeriod">Period : <label>'+ClassDt.Year+'/'+(parseInt(ClassDt.Year)+1 )+'</label></p>'+
						'<p id = "labelDepartment">Department : '+DivSessionName+'</p>'+
						'<p id = "labelPrcode"></p>'+
					'</div>'+
					'<div class="col-md-4">'+
						'<div class="well">'+
							'<div style="margin-top: -15px">'+
								'<label>Budget Remaining</label>'+
							'</div>'+
							'<div id = "Page_Budget_Remaining">'+
								''+
							'</div>'+
						'</div>'+
					'</div>';
			html += '</div>';		

		var htmlInputPR = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_PR">'+
							'<div class = "col-md-12">'+
								'<div class="table-responsive">'+
								'</div>'+
							'</div>'+
						  '</div>';

		var htmlInputFooter = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_Footer">'+
						  '</div>';

		var htmlApproval = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_Approval">'+
						  '</div>';	

		var htmlButton = '<div class = "row" style="margin-left: 0px;margin-right: 0px;margin-top: 5px;" id = "Page_Button">'+
						  '</div>';

		$('#dtContent').html(html+htmlInputPR+htmlInputFooter+htmlApproval+htmlButton);				  				  			  				  		

	}
</script>