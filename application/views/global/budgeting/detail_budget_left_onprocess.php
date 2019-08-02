<div class="row">
	<div class="col-xs-12" align="center">
		<h4><u><?php echo $G_data[0]['NameHeadAccount'].'-'.$G_data[0]['RealisasiPostName'] ?></u></h4>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<h4> OnProcess : <?php echo 'Rp '.number_format($G_data[0]['Using'],2,',','.') ?></h4>
	</div>
</div>
<div id = "root" style="margin-top: 10px;">
	
</div>

<script type="text/javascript">
var ID_budget_left = "<?php echo $ID_budget_left ?>";
var G_budget_left_payment = <?php echo json_encode($G_budget_left_payment) ?>;
var month =['','Jan','Feb','Mar','April','Mei','Jun','Jul','Aug','Sep','Okt','Nov','Des'];
var G_data = <?php echo json_encode($G_data) ?>;
$(document).ready(function() {
	// loadingStart();
	LoadFirstLoad();
}); // exit document Function

function LoadFirstLoad()
{
	var se_content = $('#root');
	var html = '';
	for (var i = 0; i < G_budget_left_payment.length; i++) {
		var MonthName = month[parseInt(G_budget_left_payment[i].Month)];
		html += '<div class = "row content_perbulan" year = "'+G_budget_left_payment[i].Year+'" month = "'+G_budget_left_payment[i].Month+'" >'+
					'<div class = "col-xs-12">'+
						'<div class="panel panel-primary" style="border-color: #437d73;">'+
							'<div class="panel-heading clearfix" style="background-color: #437d73;"><h4 class="panel-title pull-left" style="padding-top: 7.5px;">'+G_budget_left_payment[i].Year+' - '+MonthName+'</h4>'+
							'</div>'+
							'<div class="panel-body">'+
								'adasd'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>';	
	}

	se_content.html(html);
	// LoopAjaxCallback();
}

</script>