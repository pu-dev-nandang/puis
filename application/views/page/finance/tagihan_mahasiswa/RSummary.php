<script type="text/javascript">
$(document).ready(function() {
	var url = base_url_js+"rest/__rekapintake";
    var data = {
                    auth : 's3Cr3T-G4N',
                    Year : '2019',
               };
	var token = jwt_encode(data,"UAP)(*");
	
	$.post(url,{token:token},function (resultJson) {

	}).always(function() {
			                
	});								
	    
}); // exit document Function
</script>