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
	  
	  height: auto !important;  
	  max-height: 600px;
	  overflow-y: auto;
	}

	.pointer {cursor: pointer;}
</style>


<div class="table-responsive table-area" style="overflow-x:auto;">
	<table class="table table-bordered table2">
		<caption id= "CaptionTBL"><strong><?php echo $date ?></strong></caption>
	<!-- <table class="table2"> -->
	    <thead>
	    	<tr>
	    		<!-- <pre><?php //print_r($data_pass) ?></pre> -->
		    	<th class="fixed-side">
		    		Room
		    	</th>
		    	<?php for($i = 0; $i < count($arrHours); $i= $i + 2): ?>
		    		<th colspan="2" style="text-align: left;"><?php echo $arrHours[$i] ?></th>
		    	<?php endfor ?>
		    </tr>	   
	    </thead>
	    <tbody>
	    	<?php for($i = 0; $i < count($getRoom); $i++): ?>
	    		<tr>
	    			<td width="4%"><?php echo $getRoom[$i]['Room'] ?></td>
					<?php $countTD =  count($arrHours) ?>
						<?php for($j = 0; $j < $countTD; $j++): ?>
							<?php $bool = false ?>
							<?php for($k = 0; $k < count($data_pass); $k++): ?>
								<?php $implode = implode('@@', $data_pass[$k]) ?>
								<?php $converDTS = date("h:i a", strtotime($data_pass[$k]['start'])); ?>
								<?php $converDTE = date("h:i a", strtotime($data_pass[$k]['end'])); ?>
								<?php if ($data_pass[$k]['room'] == $getRoom[$i]['Room'] && $converDTS == $arrHours[$j]): ?>
									<?php if ($data_pass[$k]['approved'] == 1): ?>
										<td style="width: 72px;height: 20px;" room = "<?php echo $getRoom[$i]['Room'] ?>" colspan="<?php echo $data_pass[$k]['colspan'] ?>">
											<div class="panel-red pointer" room = "<?php echo $getRoom[$i]['Room'] ?>" id = "draggable" title="<?php echo $converDTS ?>-<?php echo $converDTE?>" user = "<?php echo $data_pass[$k]['user'] ?>" NIP = "<?php echo $data_pass[$k]['NIP'] ?>" data = "<?php echo $implode; ?>"><span>Booked <br>by <?php echo $data_pass[$k]['user'] ?></span></div>
										</td>
										<?php $bool = true ?>
										<?php $j = $j + (int)$data_pass[$k]['colspan'] - 1 ?>
										<?php break; ?>
									<?php else: ?>
										<td style="width: 72px;height: 20px;" room = "<?php echo $getRoom[$i]['Room'] ?>" colspan="<?php echo $data_pass[$k]['colspan'] ?>">
											<div class="panel-orange pointer" room = "<?php echo $getRoom[$i]['Room'] ?>" title="<?php echo $converDTS ?>-<?php echo $converDTE?>" user = "<?php echo $data_pass[$k]['user'] ?>" NIP = "<?php echo $data_pass[$k]['NIP'] ?>" data = "<?php echo $implode; ?>"><span>Requested <br><?php echo $data_pass[$k]['user'] ?></span></div>
										</td>
										<?php $bool = true ?>
										<?php $j = $j + (int)$data_pass[$k]['colspan'] - 1 ?>
										<?php break; ?>	
									<?php endif ?>
								<?php endif ?>
							<?php endfor ?>
							<?php if (!$bool): ?>
								<td style="width: 72px;height: 20px;">
		    							<div class="panel-blue pointer" id = "droppable" room = "<?php echo $getRoom[$i]['Room'] ?>" title="<?php echo $arrHours[$j]?>"><span>Available</span></div>
								</td>			
							<?php endif ?>	
						<?php endfor ?>		
	    		</tr>
	    	<?php endfor ?>   
	    </tbody>
	</table>
</div>
<script type="text/javascript">
	$( function() {
    	//$( document ).tooltip();
    	$(".panel-red").hover();
    	$(".panel-blue").hover();
    	$(".panel-orange").hover();
  	} );
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