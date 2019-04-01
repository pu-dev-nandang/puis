
<style>
    #viewHelp .item-head:hover{
        background: #f5f5f5;

    }
    #viewHelp .numbering {
        width: 30px;
        height: 30px;
        border: 1px solid #3F51B5;
        border-radius: 15px;
        text-align: center;
        padding-top: 5px;
        display: inline-block;
        margin-right: 10px;
        font-size: 11px;
        font-weight: bold;
    }
    #viewHelp .info {
        color: orangered;
        font-size: 15px;
    }
    #viewHelp .detailQNA {
        margin-top: 15px;
    }

    #viewHelp .detailQNA ul.list-group .list-group-item {
        border-radius: 15px !important;
    }

    #viewHelp a {
        text-decoration: none !important;
        display: block;
    }
</style>

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
	<div id="viewHelp" class="col-md-8 col-md-offset-2">
		    <ul class="list-group" id="headerlist">
		    	<?php for($i = 0; $i < count($G_data); $i++): ?>
		    		<?php $no = $i+1 ?>
			        <li class="list-group-item item-head">
                                <a href="javascript:void(0)" data-toggle="collapse" data-target="#<?php echo $i ?>">
                                    <span class="numbering"><?php echo $no; ?></span>
                                    <span class="info"><?php echo $G_data[$i]['Type'] ?></span>
                                </a>




			        	<div id="<?php echo $i ?>" class="collapse detailQNA">
			        	  <ul class="list-group">
			        	  	<?php $data = $G_data[$i]['data'] ?>
			        	  	<?php for($j = 0; $j < count($data); $j++): ?>
			        	  		<li class="list-group-item"><a href="javascript:void(0)" data-toggle="collapse" data-target="#<?php echo $i.'__'.$j ?>">
                                        <b><?php echo $data[$j]['Questions'] ?></b>
                                    </a>
			        	  			<div id="<?php echo $i.'__'.$j ?>" class="collapse">
			        	  				<p style="margin-top: 10px">
			        	  					<?php echo $data[$j]['Answers'] ?>
			        	  				</p>
			        	  				<div style="margin-top: 15px;margin-bottom: 15px;">
			        	  					<a class="btn btn-default" style="display: inline;" href="<?php echo serverRoot.'/fileGetAny/help-'.$data[$j]['File'] ?>" target="_blank"><i class="fa fa-download margin-right"></i> PDF File</a>
			        	  				</div>
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