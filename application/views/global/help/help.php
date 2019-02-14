<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="form-group">
			<label>Division</label>
			<select class="select2-select-00 full-width-fix" id="Division">
				<?php for($i = 0; $i < count($G_division); $i++): ?>
					<option value="<?php echo $G_division[$i]['ID'] ?>" > <?php echo $G_division[$i]['Division'] ?> </option>
				<?php endfor ?>
			 </select>
		</div>
	</div>
</div>

<div class="row" style="margin-top: 10px">
	<div class="col-md-12">
		    <ul class="list-group">
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
		    </ul>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$("#Division option").filter(function() {
		   //may want to use $.trim in here
		   return $(this).val() == "<?php echo $selected ?>"; 
		 }).prop("selected", true);
		$('#Division').select2({
		   
		});
	});

	$(document).on('change','#Division', function () {
	   var url = base_url_js+"help";
	   var data = {
	   	Division : $(this).val(),
	   };
	   $.post(url,data,function (resultJson) {
	   	$(".list-group").empty();
	   	$(".list-group").html('<div id = "pageloading"></div>');
	   	loading_page('#pageloading');
	   	setTimeout(function () {
	   		$(".list-group").html(resultJson);
	   	},2000);
	   })
	});
</script>