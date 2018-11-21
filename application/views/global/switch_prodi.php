<link href="<?php echo base_url('assets/custom.css'); ?>" rel="stylesheet" type="text/css" />
<div id = "PageContent">
	
</div>

<script type="text/javascript">
	var prodi_get = <?php echo json_encode($this->session->userdata('prodi_get')) ?>;
	$(document).ready(function () {
	    var html = '<div class = "row">' +
	    				'<div class = "col-md-12">'+	
	    					'<div class="thumbnail">'+
	    						'<h2 align = "center">Please choose program study</h2>'+
		    					'<div class = "row" style = "margin-top : 5px;margin-left : 10px;margin-right : 10px">'+
		    						'<div id = "FillContent" class = "col-md-12">'+
		    						'</div>'+
		    					'</div>'+	
		    				'</div>'+
		    			'</div>'+	
	    			'</div>';	
	    $("#PageContent").html(html);
	    var styleBorder = ['ie','tube'];
	    var h = '';	
	    for (var i = 0; i < prodi_get.length; i++) {
			var a = i % (styleBorder.length);
			var style = (a == 0) ? styleBorder[0] : styleBorder[1];
			h += '<a href = "javascript:void(0)" class = "anchor" post = "'+prodi_get[i].ID+'"> <div class="metro-big '+style+' hvr-float">'+
					    '<div class="fa fa-file-text"></div>'+
					    '<span class="label bottom">'+prodi_get[i].Name+'</span>'+
					'</div></a>';	
	    }

	    $("#FillContent").html(h);

	    $(".anchor").click(function(){
	    	loadingStart();
	    	var url = window.location.href ;
	    	var Prodi = $(this).attr('post');
	    	FormSubmitAuto(url, 'POST', [
	    	    { name: 'Prodi', value: Prodi },
	    	],'');
	    })

	});
</script>