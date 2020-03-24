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
		this.set_html_box('Total User','cyan').writeHtml(selector1).insertJs(() => {
				
		});

		this.set_html_box('Total Login','green').writeHtml(selector2).insertJs(() => {
					
		});

		this.set_html_box('Total Approval','red').writeHtml(selector3).insertJs(() => {
					
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