<style type="text/css">
	.imgtd {
	    position: relative;
	    text-align: center;
	    color: white;
	}
	/* Centered text */
	.centeredimgtd {
	    position: absolute;
	    top: 50%;
	    left: 50%;
	    transform: translate(-50%, -50%);
	}

	.imgtd img {
	    max-width: 80px;
	}

	.panel-red {
		font-size: 10px;
		color: #fff;
		font-weight: bold;
		text-align: center;
		padding: 1px;
		background: #e98180;
	    /*max-width: 100px;*/
	    height: 45px;
	    border: 1px dotted #333;
	}
	.panel-blue {
		font-size: 10px;
		color: #fff;
		font-weight: bold;
		text-align: center;
		padding: 1px;
		background: #6ba5c1;
	    /*max-width: 100px;*/
	    height: 45px;
	    border: 1px dotted #333;
	}
	.panel-orange {
		font-size: 10px;
		color: #fff;
		font-weight: bold;
		text-align: center;
		padding: 1px;
		background: #ffb848;
	    /*max-width: 100px;*/
	    height: 45px;
	    border: 1px dotted #333;
	}

	.table-responsive {
	  height: 640px;
	  overflow-y: auto;
	}
</style>


<div class="table-responsive table-area" style="overflow-x:auto;">
	<table class="table table-bordered table2">
	<!-- <table class="table2"> -->
	    <thead>
	    	<tr>
	    		<pre><?php print_r($data_pass) ?></pre>
		    	<th class="fixed-side">
		    		Room
		    	</th>
		    	<?php for($i = 0; $i < count($arrHours); $i= $i + 2): ?>
		    		<th colspan="2" style="text-align: center;"><?php echo $arrHours[$i] ?></th>
		    	<?php endfor ?>
		    </tr>	   
	    </thead>
	    <tbody>
	    	<?php for($i = 0; $i < count($getRoom); $i++): ?>
	    		<tr>
	    			<td width="4%"><?php echo $getRoom[$i]['Room'] ?></td>
					<?php $countTD =  count($arrHours) ?>
						<?php for($j = 0; $j < $countTD; $j++): ?>


						<?php endfor ?>		
	    		</tr>
	    	<?php endfor ?>   
	    </tbody>
	</table>
</div>
<script type="text/javascript">
	// $( "#draggable" ).draggable();
	//$( "#draggable3" ).draggable({ containment: "#containment-wrapper", scroll: false });
	/*$( ".panel-red" ).draggable({
		containment: ".table-responsive", scroll: false
	});
    $( ".panel-blue" ).droppable({
      drop: function( event, ui ) {
        $( this ).remove()
          
      }
    });*/


    /*$( "tr" ).sortable({
          revert: true
    });*/
    /*$( ".panel-red" ).draggable({
      connectToSortable: "tr",
      helper: "clone",
      revert: "invalid"
    });*/
    // $( "tr" ).disableSelection();

    /*$(document).ready(function () {
        LoaddataTableStandard('.table2');
    });*/
</script>