class Class_ticketing_dashboard {
		
	constructor(){
		this.Wrhtml='';
		this.TableToday;
		this.TableAll;
		this.data = [];
		this.obj = {};
	}


	LoadDefault = async (selectorMonth,selectorYear,selectorShow,selectorShowToday,PageToday,PageDashboardAll) => {
		let cls = this;
		(await (cls.LoadMonth(selectorMonth))).LoadYear(selectorYear);
		
		this.PageToday(selectorShowToday,PageToday);
		this.PageDashboardAll(selectorMonth,selectorYear,selectorShow,PageDashboardAll);
	}

	htmlTableDashboardToday = () => {
		let html = 	'<div class="row">'+
						'<div class="col-xs-12">'+
							'<div class="table-responsive">'+
								'<table class="table table-bordered">'+
									// '<caption><h4 style="color: green;"><u>Ticket Today</u></h4></caption>'+
									'<thead>'+
										'<tr>'+
											'<th>No</th>'+
											'<th>Dept</th>'+
											'<th>Tot</th>'+
											'<th>Open</th>'+
											'<th>Progress</th>'+
											'<th>Closed</th>'+
										'</tr>'+
									'</thead>'+
									'<tbody>'+
										
									'</tbody>'+
								'</table>'+
							'</div>'+
						'</div>'+
					'</div>';
		        this.Wrhtml = html;
				return this;
	}

	htmlGraphDashboardToday = () => {
		let html = '<div class = "showGraph chart"></div>'+
		           '<div class = "showGraph2 chart"></div>';
        this.Wrhtml = html;
		return this;
	}

	PageToday = async (selectorShowToday,PageToday) => {
		let cls = this;
		if (selectorShowToday.find('option:selected').val() == 1) {
			// table
			PageToday.empty();
			cls.htmlTableDashboardToday().writeHtml(PageToday).insertJs(() => {
			  let selectorTableToday = PageToday.find('table');
		      this.LoadTableToday(selectorTableToday);
		    });
		}
		else
		{
			PageToday.empty();
			cls.htmlGraphDashboardToday().writeHtml(PageToday).insertJs(async() => {
			  let selectorshowGraph = PageToday.find('.showGraph');
			  let selectorshowGraph2 = PageToday.find('.showGraph2');
			  // selectorshowGraph.html('asdsad');
		      let url = base_url_js+'rest_ticketing/__ticketing_dashboard';
		      let requestHeader = {
		      	Hjwtkey : Hjwtkey,
		      }
		      let dateToday = moment().format('YYYY-MM-DD').toString();
		      	if (!$('#SelectDepartmentID').length) {
			      var dataform = {
			          action : 'dashboard_graph_ticket_date',
			          auth : 's3Cr3T-G4N',
			          dateGet : dateToday,
			      };
			    }
			    else
			    {
			    	var dataform = {
			    	    action : 'dashboard_graph_ticket_date',
			    	    auth : 's3Cr3T-G4N',
			    	    dateGet : dateToday,
			    	    Department : $('#SelectDepartmentID').find('option:selected').val(),
			    	};
			    }
		      let token = jwt_encode(dataform,'UAP)(*');
		      const response = await AjaxSubmitFormPromises(url,token,[],Apikey,requestHeader);
		      if (!$('#SelectDepartmentID').length) {
		      	this.GraphShowCanvas(selectorshowGraph,response.NonProdi);
		      	this.GraphShowCanvas(selectorshowGraph2,response.Prodi);
		      }
		      else
		      {
		      	this.GraphShowCanvas(selectorshowGraph,response.NonProdi); // for list
		      	selectorshowGraph2.remove();
		      }
		      
		      
		    });
			
		}
	}

	writeHtml = (selector) => {
		selector.html(this.Wrhtml);
		return this;
	}

	insertJs = (result,...args) => {
	  return result(...args);
	}

	getdata = () => {

	  return this.data;

	}

	getobj = () => {

	  return this.obj;

	}

	htmlTableDashboardAll = () => {
		let html = '<div class="row">'+
	            		'<div class="col-xs-12">'+
	            			'<div class="table-responsive">'+
	            				'<table class="table table-bordered">'+
	            					'<thead>'+
	            						'<tr>'+
	            							'<th>No</th>'+
	            							'<th>Dept</th>'+
	            							'<th>Tot</th>'+
	            							'<th>Open</th>'+
	            							'<th>Progress</th>'+
	            							'<th>Closed</th>'+
	            						'</tr>'+
	            					'</thead>'+
	            					'<tbody>'+
	            					'	'+
	            					'</tbody>'+
	            				'</table>'+
	            			'</div>'+
	            		'</div>'+
            		'</div>'
        this.Wrhtml = html;
		return this;
	}

	PageDashboardAll = (selectorMonth,selectorYear,selectorShow,PageDashboardAll) => {
		let cls = this;
		if (selectorShow.find('option:selected').val() == 1) {
			// table
			PageDashboardAll.empty();
			cls.htmlTableDashboardAll().writeHtml(PageDashboardAll).insertJs(() => {
		      	  let selectorTableToday = PageDashboardAll.find('table');
		          this.LoadTableAll(selectorMonth,selectorYear,selectorTableToday);
		    });
		}
		else
		{
			cls.htmlGraphDashboardToday().writeHtml(PageDashboardAll).insertJs(async() => {
			  let selectorshowGraph = PageDashboardAll.find('.showGraph');
			  let selectorshowGraph2 = PageDashboardAll.find('.showGraph2');
			  let dateGet = selectorYear.find('option:selected').val()+'-'+selectorMonth.find('option:selected').val();
			  // selectorshowGraph.html('asdsad');
		      let url = base_url_js+'rest_ticketing/__ticketing_dashboard';
		      let requestHeader = {
		      	Hjwtkey : Hjwtkey,
		      }
		      let dateToday = moment().format('YYYY-MM-DD').toString();
		      if (!$('#SelectDepartmentID').length) {
		      	var dataform = {
		      	    action : 'dashboard_graph_ticket_all',
		      	    auth : 's3Cr3T-G4N',
		      	    dateGet : dateGet,
		      	};
		      }
		      else
		      { // for list
		      	var dataform = {
		      	    action : 'dashboard_graph_ticket_all',
		      	    auth : 's3Cr3T-G4N',
		      	    dateGet : dateGet,
		      	    Department : $('#SelectDepartmentID').find('option:selected').val(),
		      	};
		      }
		      
		      let token = jwt_encode(dataform,'UAP)(*');
		      const response = await AjaxSubmitFormPromises(url,token,[],Apikey,requestHeader);
		      if (!$('#SelectDepartmentID').length) {
		      	this.GraphShowCanvas(selectorshowGraph,response.NonProdi);
		      	this.GraphShowCanvas(selectorshowGraph2,response.Prodi);
		      }
		      else
		      {
		      	this.GraphShowCanvas(selectorshowGraph,response.NonProdi); // for list
		      	selectorshowGraph2.remove();
		      }
		      
		    });
		}
	}

	LoadTableAll = (selectorMonth,selectorYear,selectorTableToday) => {
		let recordTable = selectorTableToday.DataTable({
		    "processing": true,
		    "serverSide": false,
		    "order": [[ 2, "desc" ]],
		     "iDisplayLength": 10,
		    "language": {
		        "searchPlaceholder": "Search",
		    },
		    "ajax":{
		        url : base_url_js+"rest_ticketing/__ticketing_dashboard?apikey="+Apikey, // json datasource
		        // ordering : false,
		        type: "post",  // method  , by default get
		        beforeSend: function (xhr)
		        {
		           xhr.setRequestHeader("Hjwtkey",Hjwtkey);
		        },
		        data : function(token){
		        	let dateGet = selectorYear.find('option:selected').val()+'-'+selectorMonth.find('option:selected').val();
		              // Read values
		              if (!$('#SelectDepartmentID').length) {
		              	var data = {
		              	       action : 'dashboard_ticket_all',
		              	       auth : 's3Cr3T-G4N',
		              	       dateGet : dateGet,
		              	   };
		              }
		              else
		              {
		              	// for list
		              	var data = {
		              	       action : 'dashboard_ticket_all',
		              	       auth : 's3Cr3T-G4N',
		              	       dateGet : dateGet,
		              	       Department : $('#SelectDepartmentID').find('option:selected').val(),
		              	   };
		              }
		               
		              // Append to data
		              token.token = jwt_encode(data,'UAP)(*');
		        }                                                                     
		     },
		      'columnDefs': [
		         {
		            'targets': 0,
		            'searchable': false,
		            'orderable': false,
		            'className': 'dt-body-center',
		         },
		         
		           {
		              'targets': 2,
		              'searchable': false,
		              'orderable': true,
		              'className': 'dt-body-center',
		              'render': function (data, type, full, meta){
		                  let html = '<a href="javascript:void(0)" data = "'+full[6]+'" action="tot" class = "aHrefDetailAll">'+full[2]+'</a>';
		                  return html;
		              }
		           },
		           {
		              'targets': 3,
		              'searchable': false,
		              'orderable': true,
		              'className': 'dt-body-center',
		              'render': function (data, type, full, meta){
		                  let html = '<a href="javascript:void(0)" data = "'+full[6]+'" action="Open" class = "aHrefDetailAll">'+full[3]+'</a>';
		                  return html;
		              }
		           },
		           {
		              'targets': 4,
		              'searchable': false,
		              'orderable': true,
		              'className': 'dt-body-center',
		              'render': function (data, type, full, meta){
		                  let html = '<a href="javascript:void(0)" data = "'+full[6]+'" action="Progress" class = "aHrefDetailAll">'+full[4]+'</a>';
		                  return html;
		              }
		           },
		           {
		              'targets': 5,
		              'searchable': false,
		              'orderable': true,
		              'className': 'dt-body-center',
		              'render': function (data, type, full, meta){
		                  let html = '<a href="javascript:void(0)" data = "'+full[6]+'" action="Closed" class = "aHrefDetailAll">'+full[5]+'</a>';
		                  return html;
		              }
		           },
		      ],
		    'createdRow': function( row, data, dataIndex ) {
		            
		    },
		    dom: 'l<"toolbar">frtip',
		    initComplete: function(){
		      
		   }  
		});

		recordTable.on( 'order.dt search.dt', function () {
		                           recordTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		                               cell.innerHTML = i+1;
		                           } );
		                       } ).draw();

		this.TableAll = recordTable;
	}

	LoadMonth = async (selectorMonth) => {
		let CurrentMonth = moment().format('MM');
		let arr = ['Jan','Feb','Mar','April',
					'Mei',
					'Jun',
					'Jul',
					'Aug',
					'Sep',
					'Okt',
					'Nov',
					'Des'
				];
		selectorMonth.empty();
		let c = 1;
		for (let i = 0; i < arr.length; i++) {
			 let getKey = c.toString();
			 let value = '';
			 if (getKey.length == 1) {
			      value = '0' + getKey;
			 }
			 else
			 {
			      value = c;
			 }

			 let selected = (CurrentMonth.toString() == value.toString()) ? 'selected' : '';

			 selectorMonth.append('<option value="'+ value +'" '+selected+'>'+arr[i]+'</option>');
			 c++;
		}

		return this;
		
	}

	LoadYear = async (selectorYear) => {
		let CurrentYear = moment().format('YYYY');
		selectorYear.empty();
		for (let i = 2019; i <= CurrentYear; i++) {
		  let selected = (i==CurrentYear) ? 'selected' : '';
		  selectorYear.append('<option value="'+i+'" '+selected+'>'+i+'</option>');
		}

		return this;

	}

	LoadTableToday = (selectorTableToday) => {
		let recordTable = selectorTableToday.DataTable({
		    "processing": true,
		    "serverSide": false,
		    "order": [[ 2, "desc" ]],
		     "iDisplayLength": 10,
		    "language": {
		        "searchPlaceholder": "Search",
		    },
		    "ajax":{
		        url : base_url_js+"rest_ticketing/__ticketing_dashboard?apikey="+Apikey, // json datasource
		        // ordering : false,
		        type: "post",  // method  , by default get
		        beforeSend: function (xhr)
		        {
		           xhr.setRequestHeader("Hjwtkey",Hjwtkey);
		        },
		        data : function(token){
		        	let dateToday = moment().format('YYYY-MM-DD').toString();
		              // Read values
		              if (!$('#SelectDepartmentID').length) {
		              	var data = {
		              	       action : 'dashboard_ticket_date',
		              	       auth : 's3Cr3T-G4N',
		              	       dateGet : dateToday,
		              	   };
		              }
		              else
		              {
		              	var data = {
		              	       action : 'dashboard_ticket_date',
		              	       auth : 's3Cr3T-G4N',
		              	       dateGet : dateToday,
		              	       Department : $('#SelectDepartmentID').find('option:selected').val(),
		              	   };
		              }
		               
		              // Append to data
		              token.token = jwt_encode(data,'UAP)(*');
		        }                                                                     
		     },
		      'columnDefs': [
		         {
		            'targets': 0,
		            'searchable': false,
		            'orderable': false,
		            'className': 'dt-body-center',
		         },
		         {
		            'targets': 2,
		            'searchable': false,
		            'orderable': true,
		            'className': 'dt-body-center',
		            'render': function (data, type, full, meta){
		                let html = '<a href="javascript:void(0)" data = "'+full[6]+'" action="tot" class = "aHrefDetailToday">'+full[2]+'</a>';
		                return html;
		            }
		         },
		         {
		            'targets': 3,
		            'searchable': false,
		            'orderable': true,
		            'className': 'dt-body-center',
		            'render': function (data, type, full, meta){
		                let html = '<a href="javascript:void(0)" data = "'+full[6]+'" action="Open" class = "aHrefDetailToday">'+full[3]+'</a>';
		                return html;
		            }
		         },
		         {
		            'targets': 4,
		            'searchable': false,
		            'orderable': true,
		            'className': 'dt-body-center',
		            'render': function (data, type, full, meta){
		                let html = '<a href="javascript:void(0)" data = "'+full[6]+'" action="Progress" class = "aHrefDetailToday">'+full[4]+'</a>';
		                return html;
		            }
		         },
		         {
		            'targets': 5,
		            'searchable': false,
		            'orderable': true,
		            'className': 'dt-body-center',
		            'render': function (data, type, full, meta){
		                let html = '<a href="javascript:void(0)" data = "'+full[6]+'" action="Closed" class = "aHrefDetailToday">'+full[5]+'</a>';
		                return html;
		            }
		         },
		      ],
		    'createdRow': function( row, data, dataIndex ) {
		            
		    },
		    dom: 'l<"toolbar">frtip',
		    initComplete: function(){
		      
		   }  
		});

		recordTable.on( 'order.dt search.dt', function () {
		                           recordTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		                               cell.innerHTML = i+1;
		                           } );
		                       } ).draw();

		this.TableToday = recordTable;

	}

	pageDetailAll = async(selectorPage,action,dataDecode,valueText,DeptText,pageSet) => {
		let cls = this;
		let url = base_url_js+'rest_ticketing/__ticketing_dashboard';
		let requestHeader = {
			Hjwtkey : Hjwtkey,
		}
		let data = {
			action : action,
			dataDecode : dataDecode,
		}
		let dataform = {
		    action : 'detail_dashboard',
		    subdata : data,
		    auth : 's3Cr3T-G4N',
		    
		};
		let token = jwt_encode(dataform,'UAP)(*');
		const response = await AjaxSubmitFormPromises(url,token,[],Apikey,requestHeader);
		if (response.dataTable.recordsTotal > 0) {
			selectorPage.empty();
				// table
				cls.htmlDetail(action,valueText,DeptText,pageSet).writeHtml(selectorPage).insertJs(() => {
					let dt = response.dataTable.data;
					let selectorTable = selectorPage.find('table');
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

			      	let d_pie = response.graph;
			      	for (var i=0;i<d_pie.length;i++){
			      	    d_pie[i].label+=' ('+d_pie[i].data+')'
			      	}
			      	// graph
			      	$.plot(selectorPage.find('.showGraph'), d_pie, $.extend(true, {}, Plugins.getFlotDefaults(), {
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
			      		}
			      	}));

			    });
			    
		}
		else
		{
			toastr.info('No result data');
		}
	}

	htmlDetail = (action,valueText,DeptText,pageSet) => {
		action = (action == 'tot') ? 'Total ' : action;
		let html = '<div class="row">'+
						'<div class = "col-xs-12">'+
							'<div style = "padding-left: 5px;">'+
								'<button class = "btn btn-warning btn-back-detail" action = "'+pageSet+'">Back</button>'+
							'</div>'+
							'<div align = "center">'+
								'<h4 style = "color:green;">Dept '+DeptText+' '+action+' ticket : '+valueText+'</h4>'+
								'<hr/>'+
							'</div>'+
						'</div>'+
					'</div>'+
	            	'<div class = "row">'+
	            		'<div class = "col-xs-12">'+
	            			'<h4 align ="center" style = "color:#a91c1cd6;">Graph By Category</h4>'+
	            			'<div class = "showGraph chart"></div>'+
	            		'</div>'+
            		'</div>'+
            		'<hr/>'+
					'<div class = "row">'+	
	            		'<div class="col-xs-12">'+
	            			'<h4 align ="center" style = "color:#a91c1cd6;">Data</h4>'+
	            			'<div class="table-responsive">'+
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
	            				'</table>'+
	            			'</div>'+
	            		'</div>'+
	            	'</div>'
	            	
        this.Wrhtml = html;
        return this;
	}

	GraphShowCanvas = (selectorshowGraph,response) => {
		var ds = new Array();
		ds.push({
			label: "Total",
			data: response['Total'],
			bars: {
				show: true,
				barWidth:  0.1,
				order: 1
			}
		});

		ds.push({
			label: "Open",
			data: response['Open'],
			bars: {
				show: true,
				barWidth:  0.1,
				order: 2
			}
		});

		ds.push({
			label: "Progress",
			data: response['Progress'],
			bars: {
				show: true,
				barWidth:  0.1,
				order: 3
			}
		});

		ds.push({
			label: "Closed",
			data: response['Closed'],
			bars: {
				show: true,
				barWidth:  0.1,
				order: 4
			}
		});

		var xAxis = [];
		let c = 10;
		for (var i = 0; i < response['Abbreviation'].length; i++) {
			var cd = response['Abbreviation'];
			var taa = cd[i];
			var aa = [c.toString(), taa];
			xAxis.push(aa);
			c++;
		}

		$.plot(selectorshowGraph, ds, $.extend(true, {}, Plugins.getFlotDefaults()	, {	
			series: {
				lines: { show: false },
				points: { show: false }
			},
			grid:{
				hoverable: true
			},
			tooltip: true,
			tooltipOpts: {
				content: '%s: %y'
			},
			xaxis: { ticks:xAxis}
		}));
	}
}