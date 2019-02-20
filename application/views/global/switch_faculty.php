<link href="<?php echo base_url('assets/custom.css'); ?>" rel="stylesheet" type="text/css" />
<div id = "PageContent">
	
</div>

<script type="text/javascript">
	var faculty_get = <?php echo json_encode($this->session->userdata('faculty_get')) ?>;
	$(document).ready(function () {
	    var html = '<div class = "row">' +
	    				'<div class = "col-md-12">'+	
	    					'<div class="thumbnail">'+
	    						'<h2 align = "center">Please choose faculty</h2>'+
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
	    for (var i = 0; i < faculty_get.length; i++) {
			var a = i % (styleBorder.length);
			var style = (a == 0) ? styleBorder[0] : styleBorder[1];
			h += '<a href = "javascript:void(0)" class = "anchor" post = "'+faculty_get[i].ID+'"> <div class="metro-big '+style+' hvr-float">'+
					    '<div class="fa fa-file-text"></div>'+
					    '<span class="label bottom">'+faculty_get[i].Name+'</span>'+
					'</div></a>';	
	    }

	    $("#FillContent").html(h);

	    $(".anchor").click(function(){
	    	loadingStart();
	    	var url = window.location.href ;
	    	var faculty = $(this).attr('post');
	    	FormSubmitAuto(url, 'POST', [
	    	    { name: 'faculty', value: faculty },
	    	],'');
	    })

	});
</script>