<style type="text/css">
	.thumbnail {
	    display: inline-block;
	    display: block;
	    height: auto;
	    max-width: 100%;
	    padding: 16px;
	    line-height: 1.428571429;
	    background-color: #fff;
	    border: 1px solid #aec10b;
	    border-radius: 20px;
	    -webkit-transition: all .2s ease-in-out;
	    transition: all .2s ease-in-out;
	}

	#datatablesServer.dataTable tbody tr:hover {
	   background-color:#71d1eb !important;
	   cursor: pointer;
	}

	h3.header-blue {
	    margin-top: 0px;
	    border-left: 7px solid #2196F3;
	    padding-left: 10px;
	    font-weight: bold;
	}


	.borderless thead>tr>th {
	    vertical-align: bottom;
	    border-bottom: none !important;
	}

	.borderless thead>tr>th, .borderless tbody>tr>th, .borderless tfoot>tr>th, .borderless thead>tr>td, .borderless tbody>tr>td, .borderless tfoot>tr>td {
		    padding: 4px;
		    line-height: 1.428571429;
		    vertical-align: top;
		    border-top: none !important;
	}

	.TD1 {
		width: 35%;
	}

	.TD2 {
		width: 5%;
	}
</style>
<div class="row">
	<div class="col-xs-6 col-md-offset-3" style="min-width: 600px;overflow: auto;">
		<div class="thumbnail">
			<div id = "page_po_list"></div>
		</div>	
	</div>
</div>
<div class="row" style="margin-top: 10px;min-width: 1200px;overflow: auto;">
	<div class="col-xs-6">
		asd
		<!-- <div class="well" id ="page_spb">
		</div> -->
	</div>
	<div class="col-xs-6">
		asd
		<!-- <div class="well" id ="page_good_receipt">
		</div> -->
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		loadingEnd(500);
	});
</script>