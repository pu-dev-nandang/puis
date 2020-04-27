class Class_ticketing_report extends Class_ticketing_dashboard {
		
	constructor(){
		super();
	}

	loading_page(selector) {
	   selector.html('<div class="row">' +
	        '<div class="col-md-12" style="text-align: center;">' +
	        '<h3 class="animated flipInX"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i> <span>Loading page . . .</span></h3>' +
	        '</div>' +
	        '</div>');
	}

	LoadMonthly = async(selectorMonth,selectorYear,selectorStatus1) => {
		const cls = this;
		const selectorChartMonthly =  $('#Monthly').find('#ShowPieChartByCategory');
		const selectorTableMonthly =  $('#Monthly').find('#ShowTblByCategory');
		cls.loading_page(selectorChartMonthly);
		cls.loading_page(selectorTableMonthly);

		const selectorChartWorkerMonthly = $('#Monthly').find('.pagePieChartWorker');
		const selectorTableWorkerMonthly = $('#Monthly').find('.pageTableWorker');
		cls.loading_page(selectorChartWorkerMonthly);
		cls.loading_page(selectorTableWorkerMonthly);
		
		await Promise.all([
		        (await cls.LoadMonth(selectorMonth)),
		        (await cls.LoadYear(selectorYear)),
		        (await cls.LoadStatus(selectorStatus1)),
		])
		await timeout(500);
		let Monthly = [];
		Monthly[0] = await cls.M_Category(selectorMonth,selectorYear,selectorStatus1);
		cls.makeDomCategory(Monthly[0],selectorChartMonthly,selectorTableMonthly);
		
		Monthly[1] = await cls.M_Worker(selectorMonth,selectorYear,selectorStatus1);
		cls.makeDomWorker(Monthly[1],selectorChartWorkerMonthly,selectorTableWorkerMonthly);
	}

	LoadStatus  = async (selector) =>  {
		const cls = this;
		await cls.LoadSelectOptionStatusTicket(selector,3,1);
		return this;
	}

	LoadSelectOptionStatusTicket = async (selector,selectedata=2,report=0) => {
	    var url =base_url_js+"rest_ticketing/__CRUDStatusTicket";
	    var dataform = {
	        action : 'read',
	        auth : 's3Cr3T-G4N',
	    };
	    var token = jwt_encode(dataform,'UAP)(*');
	    let response = await AjaxSubmitFormPromises(url,token,[],Apikey,requestHeader);
	    selector.empty();
	    response =  response.data;
	    if (report == 0) {
	      selector.append(
	           '<option value = "'+'%'+'" '+'selected'+' >'+'All'+'</option>'
	       );
	    }
	    
	    for (var i = 0; i < response.length; i++) {
	       var selected = (response[i][1] == selectedata) ? 'selected' : '';
	       if (report == 1 && response[i][1] == 4) {
	        continue;
	       }
	       selector.append(
	            '<option value = "'+response[i][1]+'" '+selected+' >'+response[i][2]+'</option>'
	        );
	    }

	    return this;
	}

	M_Category = async (selectorMonth,selectorYear,selectorStatus1) => { // monthly page category
		const cls = this;
		let dateGet = selectorYear.find('option:selected').val()+'-'+selectorMonth.find('option:selected').val();
		let url = base_url_js+'rest_ticketing/__ticketing_report';
		var dataform = {
		    action : 'category',
		    auth : 's3Cr3T-G4N',
		    type : 'Monthly',
		    param : {
		    	dateGet : dateGet,
		    	Department : $('#SelectDepartmentID').find('option:selected').val(),
		    	Status : selectorStatus1.val(),
		    }
		};
		// console.log(dataform)
		let token = jwt_encode(dataform,'UAP)(*');
		let response = await AjaxSubmitFormPromises(url,token,[],Apikey,requestHeader);
		return response;

	}

	makeDomCategory  = (response,selectorChart,selectorTable) => {
		const cls = this;
		if (response.dataTable.recordsTotal > 0) {
			let selectorPage = selectorChart;
			selectorPage.empty();
			cls.insertJs(() => {
				let d_pie = response.graph;
				for (var i=0;i<d_pie.length;i++){
				    d_pie[i].label+=' ('+d_pie[i].data+')'
				}
				// graph
				$.plot(selectorPage, d_pie, $.extend(true, {}, Plugins.getFlotDefaults(), {
					series: {
						pie: {
							show: true,
							radius: 1,
							label: {
								show: true
							}
						},
				
					},
					grid: {
						hoverable: true
					},
					tooltip: true,
					tooltipOpts: {
						content: '%p.0%, %s', // show percentages, rounding to 2 decimal places
						shifts: {
							x: 20,
							y: 0
						}
					},
					// legend: false,
				}));
			})

			selectorPage = selectorTable;
			selectorPage.empty();

			cls.insertJs(() => {
				selectorPage.html(
						'<table class="table table-bordered">'+
							'<thead>'+
								'<tr>'+
									'<th>No</th>'+
									'<th>Ticket</th>'+
									'<th>Requester</th>'+
									'<th>Category</th>'+
								'</tr>'+
							'</thead>'+
							'<tbody>'+
							'	'+
							'</tbody>'+
						'</table>'
					);

				let dt = response.dataTable.data;
				selectorTable = selectorPage.find('table');
		      	selectorTable.DataTable({
		      	    "processing": true,
		      	    "serverSide": false,
		      	    "data" : dt,
      	          	'columnDefs': [
      	          	   {
      	          	      'targets': 0,
      	          	      'searchable': false,
      	          	      'orderable': false,
      	          	      'className': 'dt-body-center',
      	          	   },
      	          	   
      	          	     {
      	          	        'targets': 1,
      	          	        'searchable': false,
      	          	        'orderable': true,
      	          	        'className': 'dt-body-center',
      	          	        'render': function (data, type, full, meta){
      	          	            let html = '<a href="javascript:void(0)" data = "'+full[4]+'" class = "ModalReadMore">'+full[1]+'</a>';
      	          	            return html;
      	          	        }
      	          	     },
      	          	],
		      	});
			})

		}
		else
		{
			selectorChart.html('<p style = "color:red;">No result data</p>')
			selectorTable.html('<p style = "color:red;">No result data</p>')
			// toastr.info('No result data');
		}
	}

	M_Worker = async (selectorMonth,selectorYear,selectorStatus1) => { // monthly page worker
		const cls = this;
		let dateGet = selectorYear.find('option:selected').val()+'-'+selectorMonth.find('option:selected').val();
		let url = base_url_js+'rest_ticketing/__ticketing_report';
		var dataform = {
		    action : 'worker',
		    auth : 's3Cr3T-G4N',
		    type : 'Monthly',
		    param : {
		    	dateGet : dateGet,
		    	Department : $('#SelectDepartmentID').find('option:selected').val(),
		    	Status : selectorStatus1.find('option:selected').val(),
		    	StatusWorker : $('#Monthly').find('.SelectStatusWorker').find('option:selected').val(),
		    }
		};
		
		let token = jwt_encode(dataform,'UAP)(*');
		let response = await AjaxSubmitFormPromises(url,token,[],Apikey,requestHeader);
		return response;

	}

	makeDomWorker = (response,selectorChart,selectorTable) => {
		const cls = this;
		if (response.dataTable.recordsTotal > 0) {
			let selectorPage = selectorChart;
			selectorPage.empty();
			selectorPage.html('<div class = "chart chart-large"></div>');
			cls.insertJs(() => {
				let d_pie = response.graph;
				for (var i=0;i<d_pie.length;i++){
				    d_pie[i].label+=' ('+d_pie[i].data+')'
				}
				// graph
				$.plot(selectorPage.find('.chart'), d_pie, $.extend(true, {}, Plugins.getFlotDefaults(), {
					series: {
						pie: {
							show: true,
							radius: 1,
							label: {
								show: true
							}
						},
				
					},
					grid: {
						hoverable: true
					},
					tooltip: true,
					tooltipOpts: {
						content: '%p.0%, %s', // show percentages, rounding to 2 decimal places
						shifts: {
							x: 20,
							y: 0
						}
					},
					// legend: false,
				}));
			})

			selectorPage = selectorTable;
			selectorPage.empty();

			cls.insertJs(() => {
				selectorPage.html(
						'<table class="table table-bordered">'+
							'<thead>'+
								'<tr>'+
									'<th>No</th>'+
									'<th>Ticket</th>'+
									'<th>Requester</th>'+
									'<th>Worker</th>'+
								'</tr>'+
							'</thead>'+
							'<tbody>'+
							'	'+
							'</tbody>'+
						'</table>'
					);

				let dt = response.dataTable.data;
				selectorTable = selectorPage.find('table');
		      	selectorTable.DataTable({
		      	    "processing": true,
		      	    "serverSide": false,
		      	    "data" : dt,
      	          	'columnDefs': [
      	          	   {
      	          	      'targets': 0,
      	          	      'searchable': false,
      	          	      'orderable': false,
      	          	      'className': 'dt-body-center',
      	          	   },
      	          	   
      	          	     {
      	          	        'targets': 1,
      	          	        'searchable': false,
      	          	        'orderable': true,
      	          	        'className': 'dt-body-center',
      	          	        'render': function (data, type, full, meta){
      	          	            let html = '<a href="javascript:void(0)" data = "'+full[4]+'" class = "ModalReadMore">'+full[1]+'</a>';
      	          	            return html;
      	          	        }
      	          	     },
      	          	],
		      	});
			})

		}
		else
		{
			selectorChart.html('<p style = "color:red;">No result data</p>')
			selectorTable.html('<p style = "color:red;">No result data</p>')
			// toastr.info('No result data');
		}
	}

	LoadDaily = async(selectorDateFilter,selectorStatus2) => {
		const cls = this;
		const selectorChart =  $('#Daily').find('#ShowPieChartByCategory');
		const selectorTable =  $('#Daily').find('#ShowTblByCategory');
		cls.loading_page(selectorChart);
		cls.loading_page(selectorTable);

		const selectorChartWorker = $('#Daily').find('.pagePieChartWorker');
		const selectorTableWorker = $('#Daily').find('.pageTableWorker');
		cls.loading_page(selectorChartWorker);
		cls.loading_page(selectorTableWorker);
		
		await Promise.all([
		        (await cls.LoadStatus(selectorStatus2)),
		])
		await timeout(500);
		let Daily = [];
		Daily[0] = await cls.D_Category(selectorDateFilter,selectorStatus2);
		cls.makeDomCategory(Daily[0],selectorChart,selectorTable);
		Daily[1] = await cls.D_Worker(selectorDateFilter,selectorStatus2);
		cls.makeDomWorker(Daily[1],selectorChartWorker,selectorTableWorker);

		$('#datetimepickerFilterReport').datetimepicker({
		    format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false
		});
	}

	D_Category = async (selectorDateFilter,selectorStatus2) => { // monthly page category
		const cls = this;
		let dateGet = selectorDateFilter.val();
		let url = base_url_js+'rest_ticketing/__ticketing_report';
		var dataform = {
		    action : 'category',
		    auth : 's3Cr3T-G4N',
		    type : 'Daily',
		    param : {
		    	dateGet : dateGet,
		    	Department : $('#SelectDepartmentID').find('option:selected').val(),
		    	Status : selectorStatus2.val(),
		    }
		};
		
		let token = jwt_encode(dataform,'UAP)(*');
		let response = await AjaxSubmitFormPromises(url,token,[],Apikey,requestHeader);
		return response;

	}
	D_Worker = async (selectorDateFilter,selectorStatus2) => { // monthly page worker
		const cls = this;
		let dateGet = selectorDateFilter.val();
		let url = base_url_js+'rest_ticketing/__ticketing_report';
		var dataform = {
		    action : 'worker',
		    auth : 's3Cr3T-G4N',
		    type : 'Daily',
		    param : {
		    	dateGet : dateGet,
		    	Department : $('#SelectDepartmentID').find('option:selected').val(),
		    	Status : selectorStatus2.val(),
		    	StatusWorker : $('#Daily').find('.SelectStatusWorker').find('option:selected').val(),
		    }
		};
		
		let token = jwt_encode(dataform,'UAP)(*');
		let response = await AjaxSubmitFormPromises(url,token,[],Apikey,requestHeader);
		return response;

	}

	
}
