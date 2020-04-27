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
		let Monthly = await Promise.all([
		       (await cls.M_Category(selectorMonth,selectorYear,selectorStatus1)),
		       (await cls.M_Worker(selectorMonth,selectorYear,selectorStatus1)),
		   ]);
		
		cls.makeDomCategory(Monthly[0],selectorChartMonthly,selectorTableMonthly);
	}

	LoadStatus  = async (selector) =>  {
		await LoadSelectOptionStatusTicket(selector,3,1);
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

			// selectorPage.find('.legend').addClass('hide');	

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
			toastr.info('No result data');
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
		    	Status : selectorStatus1.val(),
		    	StatusWorker : $('#Monthly').find('.SelectStatusWorker').find('option:selected').val(),
		    }
		};
		
		let token = jwt_encode(dataform,'UAP)(*');
		let response = await AjaxSubmitFormPromises(url,token,[],Apikey,requestHeader);
		return response;

	}

	
}
