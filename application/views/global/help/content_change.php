<?php if (count($G_data) == 0): ?>
	<div class="thumbnail" style="color: red; padding: 20px;">No Result Data</div>
<?php else: ?>
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
                        <li class="list-group-item" data-contentid="<?=$data[$j]['ID']?>" data-type="user_qna" ><a href="javascript:void(0)" data-toggle="collapse" data-target="#<?php echo $i.'__'.$j ?>">
                                <b><?php echo $data[$j]['Questions'] ?></b>
                                <span class="pull-right viewers">
                                <?php if(!empty($data[$j]['CountRead']->Total)){ ?>
                                  <span class="text-success"><i class="fa fa-check-square"></i> has bean read <span class="total-read"><?=$data[$j]['CountRead']->Total?></span> times</span>
                                <?php } ?>
                                </span>
                            </a>
                            <div id="<?php echo $i.'__'.$j ?>" class="collapse">
                                <p style="margin-top: 10px">
                                    <?php echo $data[$j]['Answers'] ?>
                                </p>
                                <div style="margin-top: 15px;margin-bottom: 15px;">
                                    <a class="btn btn-default <?php if($data[$j]['File']==''||$data[$j]['File']==null || $data[$j]['File']=='unavailabe.jpg'){echo 'hide';} ?>" style="display: inline;" href="<?php echo serverRoot.'/fileGetAny/help-'.$data[$j]['File'] ?>" target="_blank"><i class="fa fa-download margin-right"></i> PDF File</a>
                                </div>
                            </div>
                        </li>
                    <?php endfor ?>
                </ul>
            </div>
        </li>
	<?php endfor ?>
<?php endif ?>
