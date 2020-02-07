<style type="text/css">
	.mailing-list .list{max-height: 500px;position: relative;overflow: auto;border-right: 1px solid #ddd}
	.mailing-list .list > .calculate{border-top:1px solid #ddd;}
	.mailing-list .list > .calculate > p{margin:0px; padding: 10px;}
	.mailing-list .list > .mail{padding: 10px;font-size: 12px;}
	.mailing-list .list > .mail:not(:last-child){border-bottom:1px solid #ddd;}
	.mailing-list .list > .mail.active,.mailing-list .list > .mail:hover{background-color: #a9c1d6;cursor: pointer;}
	.mailing-list .list > .mail > .mail_to{color: #000;font-size: 14px}
	.mailing-list .list > .mail > .created_info{text-align: center;}
	.mailing-list .list > .mail > .mail_to > .action{display: none;}
	.mailing-list .list > .mail:hover > .mail_to > .action{display: block;}
	.mailing-list .btn-remove-mail{color: #605e5c;cursor: pointer;}
	.mailing-list .list > .mail > .mail_to,
	.mailing-list .list > .mail > .mail_subject,
	.mailing-list .list > .mail > .mail_message{padding: 2px 0px}
	.mailing-list .list > .mail > .mail_subject,.mailing-list .list > .mail > .mail_message,.mailing-list .list > .mail > .mail_to small{color: #605e5c}
	.l-mail{display: block;}
	.mailing-list .detail-mail{padding: 5px 0px;max-height: 500px;position: relative;overflow: auto;}
	.mailing-list .detail-mail .info-created{border-bottom: 1px solid #ddd}
	.mailing-list .detail-mail .info-created,.mailing-list .detail-mail .info{padding: 0px 10px}
	.mailing-list .detail-mail .info-created > .act > .btn-remove-mail{padding: 5px 10px;color: red;}
	.mailing-list .detail-mail .info .cto > span,.mailing-list .detail-mail .info  .ccc > span {cursor: pointer;}
	.mailing-list .detail-mail .info .list{color: blue;text-decoration: underline;}
</style>
<div class="mailing-list">
	<div class="row">
		<div class="col-sm-3 col-md-3" style="padding-right:0px">
			<div class="list">
			    <?php if(!empty($results)){
			    foreach ($results as $v) { ?>
				<div class="mail" data-mail="<?=$v->ID?>">
					<?php if(!empty($access)){
					if($access->isView == 1){ ?>
					<div class="created_info"><span>Created by <?=$v->createdby?></span>
					<?php if(empty($v->isShow)){?><p class="text-danger"><i class="fa fa-exclamation-triangle"></i> This message has been removed by user </p><?php } ?></div>
					<?php } } ?>
					<div class="mail_to">
						<?php if(!empty($access)){
						if(($access->isDelete == 1) && ($v->createdby == $this->session->userdata('NIP').'.'.$this->session->userdata('Name'))){ ?>
						<div class="action pull-right">
							<span class="btn-remove-mail" data-mail="<?=$v->ID?>"><i class="fa fa-trash"></i></span>							
						</div>
						<?php } } ?>
						<div class="to"><span><?php $mail_to=json_decode($v->mail_to,true); echo $mail_to[0].((count($mail_to) > 1) ? ' <small>etc</small>':'');?></span></div>
					</div>
					<div class="mail_subject">
						<div class="date pull-right"><span><?=date("d M Y",strtotime($v->created))?></span></div>
						<div class="subject"><span><?=$v->SubjectOth?></span></div>
					</div>
					<div class="mail_message"><span><?=substr($v->MessageOth,0,80).((strlen($v->MessageOth) > 80) ? "...":"")?></span></div>
				</div>
				<?php } }else{echo '<p style="padding:10px">Empty results</p>';} ?>
				<div class="calculate">
					<p>Total <?=count($results)?> mail</p>
				</div>
			</div>
		</div>
		<div class="col-sm-9 col-md-9" style="padding-left:0px;height:100%">
			<div class="detail-mail hidden">
				<div class="info-created">
					<?php if(!empty($access)){
						if($access->isDelete == 1){ ?>
					<div class="act pull-right">
						<span class="created">dd</span>
						<span class="btn-remove-mail" title="Remove this message" data-mail="0"><i class="fa fa-trash"></i></span>
					</div>
					<?php } } ?>
					<div class="sender">
						<h4 class="createdby">d</h4>
					</div>
				</div>
				<div class="info">
					<h4 class="SubjectOth"></h4>					
					
					<div class="list-mail_to">
						<span class="cto" data-toggle="collapse" data-target="#collapseTO" aria-expanded="false" aria-controls="collapseTO"><span>To:</span> <i class="fa fa-angle-double-down"></i></span>
						<div class="list collapse" id="collapseTO"></div>
					</div>
					<div class="list-mail_cc">
						<span class="ccc" data-toggle="collapse" data-target="#collapseCC" aria-expanded="false" aria-controls="collapseCC"><span>Cc:</span> <i class="fa fa-angle-double-down"></i></span>
						<div class="list collapse" id="collapseCC"></div>
					</div>
					<p>Message: </p>
					<div class="MessageOth">..</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$(".mailing-list .list").on("click",".mail",function(){
			var itsme = $(this);
			var mailID = itsme.data("mail");
			if(mailID != 0){	
				$(".mailing-list .list .mail").removeClass("active");
				itsme.addClass("active");			
				var data = {
	              MAILID : mailID,
	          	};
	          	var token = jwt_encode(data,'UAP)(*');	
	          	$.ajax({
				    type : 'POST',
				    url : base_url_js+"global-informations/message-blast/detailMail",
				    data : {token:token},
				    dataType : 'json',
				    beforeSend :function(){
				    	loading_modal_show();
				    	$(".mailing-list .detailMail").addClass("hidden");
				    },error : function(jqXHR){
				    	loading_modal_hide();
		            	$("body #modalGlobal .modal-body").html(jqXHR.responseText);
			      	  	$("body #modalGlobal").modal("show");
				    },success : function(response){
				    	$(".mailing-list .detail-mail").removeClass("hidden");
				    	loading_modal_hide();
				    	$.each(response,function(k,v){
				    		if(k == "ID"){
				    			$(".mailing-list .detail-mail").find(".btn-remove-mail").attr("data-mail",v);
				    		}
				    		if(k == "mail_to"){
				    			var appendTo = "";
				    			$.each(v,function(key,val){
				    				appendTo += "<span class='l-mail'>"+val+"</span>"
				    			});
				    			$(".mailing-list .detail-mail .list-mail_to > .cto > span").text("To: "+v[0]);
			    				$(".mailing-list .detail-mail .list-mail_to > .list").html(appendTo);
				    		}
				    		if(k == "mail_cc"){
				    			var appendCC = "";
				    			$.each(v,function(key,val){
				    				appendCC += "<span class='l-mail'>"+val+"</span>";
				    			});
				    			$(".mailing-list .detail-mail .list-mail_cc > .ccc > span").text("Cc: "+v[0]);
			    				$(".mailing-list .detail-mail .list-mail_cc > .list").html(appendCC);
				    		}

				    		$(".mailing-list .detail-mail").find("."+k).html(v);
				    	});
				    }
				});
			}			
		});

		$(".cto").click(function(){
			var isOpen = $(this).attr("aria-expanded");
			if(isOpen == "false"){
				$(this).attr("aria-expanded",true);
				$(this).find("span").text("To: show less");
				$(this).find("i.fa").toggleClass("fa-angle-double-down fa-angle-double-up");
			}else{
				$(this).attr("aria-expanded",false);
				var firstMail = $(this).next().find(".l-mail:first").text();
				$(this).find("span").text("To: "+firstMail);				
				$(this).find("i.fa").toggleClass("fa-angle-double-up fa-angle-double-down");
			}
		});
		
		$(".ccc").click(function(){
			var isOpen = $(this).attr("aria-expanded");
			if(isOpen == "false"){
				$(this).attr("aria-expanded",true);
				$(this).find("span").text("Cc: show less");
				$(this).find("i.fa").toggleClass("fa-angle-double-down fa-angle-double-up");
			}else{
				$(this).attr("aria-expanded",false);
				var firstMail = $(this).next().find(".l-mail:first").text();
				$(this).find("span").text("Cc: "+firstMail);				
				$(this).find("i.fa").toggleClass("fa-angle-double-up fa-angle-double-down");
			}
		});


		
	});
</script>