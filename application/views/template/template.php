<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title>Podomoro University</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('images/icon/favicon.png'); ?>">
    <?php echo $include; ?>
</head>

<body class="theme-dark">
	<?php echo $header; ?>
	<div id="container" <?php echo (isset($ClassContainer)) ? 'class ="'.$ClassContainer.'"' : '' ?> >
		<?php echo $navigation; ?>
		<!-- <div id="navigation">

		</div> -->

		<div id="content">
			<div class="container" style="position:relative;">
				<!-- Breadcrumbs line && Page Header -->
				<?php echo $crumbs; ?>
				<!-- /Breadcrumbs line && /Page Header -->


				<!--=== Page Content ===-->
				<?php echo $content; ?>

<!--                <div style="position: absolute;-->
<!--                    right: 0;-->
<!--                  bottom: 0px;-->
<!--                  left: 0;text-align: center;">-->
<!--                    <div class="row">-->
<!--                        <div class="col-md-12">-->
<!---->
<!--                            <p style="border-top: 1px solid #ccc;padding-top: 10px;font-style: italic;">-->
<!--                                --- IT PU, We Made With-->
<!--                                <i class="fa fa-heart" style="color: red;" aria-hidden="true"></i> And-->
<!--                                <i class="fa fa-coffee bs-tooltip" aria-hidden="true"  data-placement="top"-->
<!--                                   data-original-title="udah pada ngopi belon? diem diem bae"></i> ----->
<!--                            </p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->

			</div>
			<!-- /.container -->

		</div>
	</div>

    <form id="formGlobalToken" action="" target="_blank" hidden method="post">
        <textarea id="dataToken" class="hide" hidden readonly name="token"></textarea>
    </form>


    <!-- ADDED BY FEBRI @ MARCH 2020 -->
    <?php if(!empty($showNotif)){
    if($showNotif){ ?>
    <style>
    .box-notif.ntf-1{background: #f3e8af85;}
    .box-notif{cursor: pointer;border-bottom:1px solid #f9f9f9;display: inline-flex;width: 100%;margin-bottom: 10px;padding: 10px}
    .box-notif > .picture{width:50px;max-height:50px;margin-right: 10px}
	.box-notif > .info > .created{font-weight: bold;}
	.box-notif > .info{width: 100%}
    </style>
	
	<script>
    $(document).ready(function(){
    	var url = base_url_js+'api/__crudLog';
        var data = {
            action : 'readLog',
            UserID : sessionNIP
        };
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function(response) {
			if(!jQuery.isEmptyObject(response)){
				console.log(response.Details);
				if(response.Details.length > 0){
					var appendList = "";
					$.each(response.Details,function(k,v){
						appendList += '<div class="box-notif ntf-'+v.StatusRead+'" onClick="location.href=\''+base_url_js+v.URLDirect+'\'"><img src="'+v.Icon+'" class="picture img-rounded pull-left"><div class="info"><span class="date pull-right"><i class="fa fa-clock-o"></i> '+moment(v.CreatedAt).format('dddd, DD MMM YYYY HH:mm:ss')+'</span><p class="created">'+v.CreatedName+'</p><p class="title">'+v.Title+'</p></div></div>';
						/*if(v.StatusRead == 1){
						}else{

						}*/

					});
					$("#GlobalModal .modal-header").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> <h4 class="modal-title" id="exampleModalLabel">Notifications</h4>');
					$("#GlobalModal .modal-body").css({"padding":"0px","overflow":"auto","max-height":"200px"}).html(appendList);
					$("#GlobalModal .modal-footer").html('<a href="'+base_url_js+'ShowLoggingNotification">View all notifications</a>');
					$("#GlobalModal").modal("show");
				}
			}
        });
    });
    </script>
    <?php } } ?>
    
    <!-- END ADDED BY FEBRI @ MARCH 2020 -->


</body>
</html>
