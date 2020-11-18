class Class_top5Content
{
	constructor(){
		this.param = [];
		this.param.table_config = {
			    responsive: {
			        details: {
			            type: 'column'
			        }
			    },
			    processing: true,
			    serverSide: true,
			    lengthMenu: [[5], [5]],
			    iDisplayLength : 5,
			    "searching": false,
			    ajax: {
			        url: base_url_js+'it/summary_knowledgebase/get_top5_Content',
			        type: 'post',
			        data: function (d) {
			        	let filterTable = {}
			            $('.filterSearch select, .filterSearch input').each(function () {
			                filterTable[$(this).attr("name")] = $(this).val();
			            });
			            d.filter = filterTable;
			            return d;
			        },
			    },
			    autoWidth: false,
			    columnDefs: [{
			            orderable: false,
			            targets: 'no-sort'
			        }, {
			            className: 'text-center',
			            targets: 'text-center'
			        }, {
			            className: 'text-right',
			            targets: 'text-right'
			        }, {
			            className: 'no-padding',
			            targets: 'no-padding'
			        }, {
			            className: 'td-no-padding',
			            targets: 'td-no-padding'
			        }, {
			            className: 'th-no-padding',
			            targets: 'th-no-padding'
			        }, {
			            className: 'td-image',
			            targets: 'image'
			        }, {
			            visible: false,
			            targets: 'hide'
			        }
			    ],
			    order: [
			        [$('#tbl_total_top5_Content th.default-sort').index(), $('#tbl_total_top5_Content th.default-sort').attr('data-sort')]
			    ],
			    orderCellsTop: true,
			    language: {
			        paginate: {
			            'next': '<i class="icon-arrow-right15"></i>',
			            'previous': '<i class="icon-arrow-left15"></i>'
			        }
			    },
			    "initComplete": function(settings, json) {
			    	
			    },
			    
		};
	}

	dataTableGenerate = () => {
		var columns = [];
		$('#tbl_total_top5_Content .column th').each(function () {
		    columns.push({
		        data: $(this).attr('data-data')
		    });
		});
		this.param.table_config.columns = columns;
		this.param.sel_table = $('#tbl_total_top5_Content').DataTable(this.param.table_config);

	}

	graphShowPie = (sel,data) => {
		let d_pie = data;
		for (var i=0;i<d_pie.length;i++){
		    d_pie[i].label+=' ('+d_pie[i].data+')'
		}
		// graph
		$.plot(sel, d_pie, $.extend(true, {}, Plugins.getFlotDefaults(), {
			series: {
				pie: {
					show: true,
					radius: 1,
					label: {
						show: false
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
	}

	chart = async() => {
		let url  = base_url_js+'it/summary_knowledgebase/pie_chart_top5_Content';
		const processData =  await AjaxSubmitFormPromises(url,'');
		const sel = $('#graph_pie_top5_content');
		this.graphShowPie(sel,processData);
	}


}


const top5Content = new Class_top5Content();

$(document).ready(function(e){
	top5Content.dataTableGenerate();
	
	top5Content.chart();

	let tbl = top5Content.param.sel_table;

	$('body').on('keyup', '.filterSearch input', function (e) {
	    if (e.keyCode == 13) {
	        tbl.ajax.reload((e) => {
	            
	        });
	    }
	});
})