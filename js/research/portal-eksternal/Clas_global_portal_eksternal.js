class Clas_global_portal_eksternal {
	
	constructor() {
		this.data = [];
		this.obj = {};
		this.Wrhtml = '';
	}

	getHtml = () => {
		return this.Wrhtml;
	}

	getdata = () => {

	  return this.data;

	}

	getobj = () => {

	  return this.obj;

	}

	insertJs = (result,...args) => {
	  return result(...args);
	}

	writeHtml = (selector) => {
		selector.html(this.Wrhtml);
		return this;
	}

	LoadDefault = (selector1,selector2,selector3)  => {
		const url = base_url_js+'rest_research/__load_box';
		let dataform = {
			auth : 's3Cr3T-G4N',
		}
		this.set_html_box('Total User','cyan').writeHtml(selector1).insertJs(async() => {
			dataform['action'] = 'total_user';
			let token = jwt_encode(dataform,'UAP)(*');
			let response = await AjaxSubmitFormPromises(url,token);	
			if (response['status'] != undefined) {
				selector1.find('.value').html(response['result']);
				selector1.find('.moreDetail').attr('data',jwt_encode(response['callback'],'UAP)(*'));
			}
			
		});

		this.set_html_box('Total Login Today','green').writeHtml(selector2).insertJs(async() => {
			dataform['action'] = 'total_login_today';
			let token = jwt_encode(dataform,'UAP)(*');
			let response = await AjaxSubmitFormPromises(url,token);
			if (response['status'] != undefined) {
				selector2.find('.value').html(response['result']);
				selector2.find('.moreDetail').attr('data',jwt_encode(response['callback'],'UAP)(*'));
			}		
		});

		this.set_html_box('Total Approval','red').writeHtml(selector3).insertJs(async() => {
			dataform['action'] = 'total_approval';
			let token = jwt_encode(dataform,'UAP)(*');
			let response = await AjaxSubmitFormPromises(url,token);
			if (response['status'] != undefined) {
				selector3.find('.value').html(response['result']);
				selector3.find('.moreDetail').attr('data',jwt_encode(response['callback'],'UAP)(*'));
			}		
		});
	}

	set_html_box = (title,color) =>{
		this.Wrhtml = ' <div class="statbox widget box box-shadow">'+
                            '<div class="widget-content">'+
                                '<div class="visual '+color+'">'+
                                    '<span class="image"><img src="'+base_url_js+'images/icon/no_image.png" style="height: 100px"></span>'+
                                '</div>'+
                                '<div class="title">'+title+'</div>'+
                                '<div class="value">0</div>'+
                                '<a class="moreDetail" href="javascript:void(0);">View More <i class="pull-right icon-angle-right"></i></a>'+
                            '</div>'+
                        '</div>';

		return this;
	}


}