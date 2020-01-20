<style type="text/css">
	#message-blast .heading > h2{margin-top: 0px}
	#message-blast .fetch-message .messages > .middle > .list{overflow: auto;max-height: 50em;padding-top: 10px}
</style>
<div id="message-blast">
	<div class="row">
		<div class="col-sm-12">
			<div class="main-ctn">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-sm-6">
								<a class="btn btn-sm btn-primary btn-new-msg" href="<?=site_url('global-informations/message-blast/new')?>"><i class="fa fa-edit"></i> Create New Message</a>
							</div>
							<div class="col-sm-6">
								<div class="pull-right">
									<div class="dropdown">
									  <button id="drpConfig" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									    <i class="fa fa-cog"></i>
									  </button>
									  <ul class="dropdown-menu pull-right" aria-labelledby="drpConfig">
									    <li><a href="<?=site_url('global-informations/subject-type')?>">Subject Type</a></li>
									    <li><a href="<?=site_url()?>">Configure Mail</a></li>
									  </ul>
									</div>
								</div>								
							</div>
							
						</div>
					</div>
					<div class="panel-body">
						<div class="fetch-message">
							<div class="row">
								<div class="col-sm-3">
									<div class="messages">
										<div class="top">
											<div class="row">
												<div class="col-sm-6">
													<div class="src-msg">
														<div class="text-right">
															<input type="text" name="keywords" placeholder="Search here" class="form-control">
														</div>
													</div>		
												</div>
												<div class="col-sm-6">
													<div class="dropdown">
														<button id="sorted-by" type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														    Sort by
															<span class="caret"></span>
														</button>
														<ul class="dropdown-menu" aria-labelledby="sorted-by">
														  <li class="list-nav"><a class="list-item" data-sort="">s</a></li>
														</ul>
													</div>		
												</div>
											</div>
											
											
										</div>
										<div class="middle">
											<div class="list">
												<div class="load-message">
													<p>ehem</p>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-9">
									<div class="chat-msg">
										<div class="load-chat-message"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


