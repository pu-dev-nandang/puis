class App_forum_alumni_detail {

	constructor(){

	}

	LoadDefault = async () => {
		let cls = this;
		let data = {
			ID : DataID,
			UserID : sessionNIP
		}

		let dataForm = {
			auth : 's3Cr3T-G4N',
			data : data,
		}

		let url = base_url_js+'rest_alumni/__get_detail_topic';
		let token = jwt_encode(dataForm,'UAP)(*');
		let Apikey = CustomPost['get'];
		Apikey = findAndReplace(Apikey, '?apikey=', '');
		loadingStart();
		const ajaxGetResponse = await AjaxSubmitFormPromises(url,token,[],Apikey,CustomPost['header']);
		if (ajaxGetResponse.status == 1) {
			let callback = ajaxGetResponse.callback;
			if (callback.length > 0) {
				let d = callback[0];
				$('#viewTitle').html(d.Topic);
				$('#viewCreate').html('Create at : '+moment(d.CreateAt).format('dddd, DD MMMM YYYY hh:mm A'));
				$('#viewDescription').html(d.Description);
				cls.makeComment(d);
				$('#formComment').val('');
				$('#btnActionComment').removeAttr('forum_commentid');
			}
		}
		loadingEnd(300);
	}

	makeComment = (data) => {
		let cls = this;
		$('#rowComment').empty();
		let G_comment = data['G_comment'];
		let htmlCreate  = cls.HtmlComment(G_comment);
		$('#rowComment').html(htmlCreate);
	}

	HtmlComment = (G_comment) => {
		let cls = this;
		let html = '';
		for (var i = 0; i < G_comment.length; i++) {
			let classLi = (G_comment[i].DivisionName != 'Student') ? 'lecturer' : 'student';
			let imgSrc = (classLi == 'student') ? base_url_js+'upload/students/'+G_comment[i].Year+'/'+G_comment[i].Photo : 
						base_url_js+'upload/employees/'+G_comment[i].Photo;
			let comment_child = G_comment[i].comment_child;
			let CreateAt = moment(G_comment[i].CreateAt).format('dddd, DD MMMM YYYY HH:mm:ss');
			let token = jwt_encode(G_comment[i],'UAP)(*');
			
			let html_RecusiveComment = cls.getRecusiveComment(comment_child);

			html += '<li class = "'+classLi+'">'+
						'<div class = "row">'+
							'<div class = "col-xs-1" style = "text-align:center">'+
								'<img src = "'+imgSrc+'" style="max-width: 40px;" class="img-rounded" >'+
								'<div class = "quote">'+
									'<i class ="fa fa-hashtag"></i>'+
									comment_child.length+
								'</div>'+
							'</div>'+
							'<div class = "col-xs-11">'+
								'<div class = "thumbnail">'+
									'<div class = "row">'+
										'<div class = "col-xs-12">'+
											'<h4 class = "h4-comment">'+
												G_comment[i].Name+
												'<br/>'+
												'<small>'+
													CreateAt+
												'</small>'+
											'</h4>'+
											'<p>'+
												G_comment[i].Comment+
											'</p>'+
											'<div style="text-align: right;">'+
												'<button class="btn btn-sm btn-default btnQuote" token = "'+token+'" ><i class="fa fa-quote-right margin-right"></i> Quote</button>'+
											'</div>'+		
										'</div>'+
									'</div>'+
									html_RecusiveComment+
								'</div>'+
							'</div>'+
						'</div>'+
					'</li>';		

		}

		return html;
	}

	getRecusiveComment = (G_comment) => {
		let cls = this;
		let html = '';
		if (G_comment.length > 0) {
			html  += '<ul>';
			for (var i = 0; i < G_comment.length; i++) {
				let classLi = (G_comment[i].DivisionName != 'Student') ? 'lecturer' : 'student';
				let imgSrc = (classLi == 'student') ? base_url_js+'upload/students/'+G_comment[i].Year+'/'+G_comment[i].Photo : 
							base_url_js+'upload/employees/'+G_comment[i].Photo;
				let comment_child = G_comment[i].comment_child;
				let CreateAt = moment(G_comment[i].CreateAt).format('dddd, DD MMMM YYYY HH:mm:ss');
				let token = jwt_encode(G_comment[i],'UAP)(*');
				let html_RecusiveComment = cls.getRecusiveComment(comment_child);
				html += '<div class = "row" style = "padding:5px;">'+
							'<div class = "col-xs-12">'+
								'<div class = "well">'+
									'<div class = "row">'+
										'<div class = "col-xs-12">'+
											'<h4 class = "h4-comment">'+
												G_comment[i].Name+
												'<br/>'+
												'<small>'+
													CreateAt+
												'</small>'+
											'</h4>'+
											'<p>'+
												G_comment[i].Comment+
											'</p>'+
											'<div style="text-align: right;">'+
												'<button class="btn btn-sm btn-default btnQuote" token = "'+token+'" ><i class="fa fa-quote-right margin-right"></i> Quote</button>'+
											'</div>'+		
										'</div>'+
									'</div>'+
									html_RecusiveComment+
								'</div>'+
							'</div>'+
						'</div>';
			}

			html  += '</ul>';
			
		}

		return html;	

	}

	submit_action_comment = (selector,ParentCommentID) => {
		let cls = this;
		let htmlBtn = selector.html();
		let ForumID = DataID;
		let formComment = $('#formComment').val();
		if (formComment != '') {
			let data = {};
			if (ParentCommentID != undefined && ParentCommentID != '') {
				data['ParentCommentID'] = ParentCommentID;
			}

			data['Comment'] = formComment;
			data['ForumID'] = ForumID;
			data['UserID'] = sessionNIP;
			data['CreateAt'] = "<?php echo date('Y-m-d H:i:s') ?>";

			if (confirm('Are you sure ?')) {
				let dataForm = {
					auth : 's3Cr3T-G4N',
					data : data,
				}

				let url = base_url_js+'rest_alumni/__submit_comment_forum';
				let token = jwt_encode(dataForm,'UAP)(*');

				let Apikey = CustomPost['get'];
				Apikey = findAndReplace(Apikey, '?apikey=', '');
				loading_button2(selector);
				AjaxSubmitForm(url,token,[],Apikey,CustomPost['header']).then(function(response){
				    if (response.status == 1) {
				    	cls.LoadDefault();
				    	toastr.info('Success');
				    }
				    end_loading_button2(selector,htmlBtn);
				}).fail(function(response){
				   toastr.error('Connection error,please try again');
				   end_loading_button2(selector,htmlBtn);
				})
			}

		}
	}
}