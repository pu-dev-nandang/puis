<?php if (count($G_data) == 0): ?>
	<div style="color: red">No Result Data</div>
<?php else: ?>
	<?php for($i = 0; $i < count($G_data); $i++): ?>
		<?php $no = $i+1 ?>
	    <li class="list-group-item"><a href="javascript:void(0)" data-toggle="collapse" data-target="#<?php echo $i ?>"><?php echo $no.'. '.$G_data[$i]['Type'] ?></a>
	    	<div id="<?php echo $i ?>" class="collapse">
	    	  <ul class="list-group">
	    	  	<?php $data = $G_data[$i]['data'] ?>
	    	  	<?php for($j = 0; $j < count($data); $j++): ?>
	    	  		<li class="list-group-item"><a href="javascript:void(0)" data-toggle="collapse" data-target="#<?php echo $i.'__'.$j ?>"><?php echo $data[$j]['Questions'] ?></a>
	    	  			<div id="<?php echo $i.'__'.$j ?>" class="collapse">
	    	  				<p style="margin-top: 10px">
	    	  					<?php echo $data[$j]['Answers'] ?>
	    	  				</p>
	    	  				<p style="margin-top: 10px">
	    	  					<a href="<?php echo serverRoot.'/fileGetAny/help-'.$data[$j]['File'] ?>" target="_blank">PDF File</a>
	    	  				</p>
	    	  			</div>
	    	  		</li>	
	    	  	<?php endfor ?>
	    	  </ul>
	    	</div>
	    </li>
	<?php endfor ?>
<?php endif ?>
