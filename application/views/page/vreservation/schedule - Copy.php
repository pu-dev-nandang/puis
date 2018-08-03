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
		padding: 7px;
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
		padding: 7px;
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
		padding: 7px;
		background: #ffb848;
	    /*max-width: 100px;*/
	    height: 45px;
	    border: 1px dotted #333;
	}

	.table-responsive {
	  height: 500px;
	  overflow-y: auto;
	}


</style>


<div class="table-responsive" style="overflow-x:auto;">
	<table class="table table-bordered table2">
	    <thead>
	    	<th class="fixed-side">
	    		Room
	    	</th>
	    	<?php for($i = 0; $i < count($arrHours); $i++): ?>
	    		<th colspan="2" style="text-align: center;"><?php echo $arrHours[$i] ?></th>
	    	<?php endfor ?>   
	    </thead>
	    <tbody>
	    	<?php for($i = 0; $i < count($getRoom); $i++): ?>
	    		<tr>
	    			<td width="4%"><?php echo $getRoom[$i]['Room'] ?></td>
	    			<?php $a = $i ?>
	    			<?php $b = $i ?>
	    			<?php $countTD = count($arrHours) * 2 ?>
	    			<?php $colspan = 2 ?>
	    			<?php for($j = 0; $j < count($arrHours); $j++): ?>
	    				<?php if ($countTD <= 1): ?>
	    					<?php $colspan = 1 ?>
	    				<?php endif ?>
	    				<?php if ($j == $a): ?>
	    					<?php if ($i > 5): ?>
	    						<td><div class="panel-blue" id = "droppable"><span>Available</span></div></td>
	    						<td style="width: 72px;height: 20px;" colspan="<?php echo $colspan ?>">
	    							<div class="panel-red" id = "draggable"><span>Booked <br>by User 3</span></div>
	    						</td>
	    						<?php $countTD = $countTD - $colspan + 1 ?>	
	    					<?php else: ?>
	    						<td style="width: 72px;height: 20px;" colspan="<?php echo $colspan ?>">
	    							<div class="panel-red" id = "draggable"><span>Booked <br>by User 1</span></div>
	    						</td>
	    						<?php $countTD = $countTD - $colspan ?>	
	    					<?php endif ?>
	    					<?php $a= $a+2 ?>
	    				<?php elseif($j == $b): ?>
		    				<td style="width: 72px;height: 20px;" colspan="<?php echo $colspan ?>">
		    					<div class="panel-orange"><span>Requested <br>by User 2</span></div>
		    				</td>
		    				<?php $a = $a -1 ?>	
		    				<?php $b = $b -1 ?>
		    				<?php $countTD = $countTD - $colspan ?>	
		    			<?php else: ?>
		    				<td style="width: 72px;height: 20px;">
		    					<div class="panel-blue" id = "droppable"><span>Available</span></div>
							</td>
		    				<td>
		    					<div class="panel-blue" id = "droppable"><span>Available</span></div>
		    				</td>
		    				<?php $countTD = $countTD - 2 ?>	
		    				<?php $b = $b+3 ?>
		    				<?php $a = $a-2 ?>	
	    				<?php endif ?>
	    				<?php $a++ ?>
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


    $( "tr" ).sortable({
          revert: true
    });
    /*$( ".panel-red" ).draggable({
      connectToSortable: "tr",
      helper: "clone",
      revert: "invalid"
    });*/
    $( "tr" ).disableSelection();

</script>