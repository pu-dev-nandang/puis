<script type="text/javascript">
$(document).ready(function() {
	var url = base_url_js+"rest/__rekapmhspayment";
    var data = {
                    auth : 's3Cr3T-G4N',
                    action : 'reset'
               };
	var token = jwt_encode(data,"UAP)(*");
	
	$.post(url,{token:token},function (resultJson) {

	}).always(function() {
			                
	});								
	    
}); // exit document Function
</script>