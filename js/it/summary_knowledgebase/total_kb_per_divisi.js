class Class_total_kb_per_divisi
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
			    oSearch: {sSearch: filterTbl},
			    lengthMenu: [[5], [5]],
			    iDisplayLength : 5,
			    language: {
			        "searchPlaceholder": "Search",
			    },
			    ajax: {
			        url: base_url_js+'it/summary_knowledgebase/get_total_kb_per_divisi',
			        type: 'post',
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
			        [$('th.default-sort').index(), $('th.default-sort').attr('data-sort')]
			    ],
			    orderCellsTop: true,
			    dom: '<"datatable-scroll-wrap"t>B<"datatable-footer"ipl>',
			    language: {
			        paginate: {
			            'next': '<i class="icon-arrow-right15"></i>',
			            'previous': '<i class="icon-arrow-left15"></i>'
			        }
			    },
			    dom: 'l<"toolbar">frtip',
			    "initComplete": function(settings, json) {
			    	
			    },
			    
		};
	}

	dataTableGenerate = () => {
		var columns = [];
		$('#tbl_total_per_division .column th').each(function () {
		    columns.push({
		        data: $(this).attr('data-data')
		    });
		});
		this.param.table_config.columns = columns;
		this.param.sel_table = $('#tbl_total_per_division').DataTable(this.param.table_config);
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

	chart = async() => {
		const selectorshowGraph = $('#chart_total_per_division_NAC');
		const selectorshowGraph2 = $('#chart_total_per_division_AC');
		const selectorshowGraph_2 = $('#chart_total_per_division_NAC2');
		loading_page2(selectorshowGraph);
		loading_page2(selectorshowGraph2);
		loading_page2(selectorshowGraph_2);
		const processAjax = await this.chart_ajax();

		const responseNA = processAjax.NonProdi;
		this.GraphShowCanvas(selectorshowGraph,responseNA);

		const responseNA2 = processAjax.NonProdi2;
		this.GraphShowCanvas(selectorshowGraph_2,responseNA2);

		const responseAC = processAjax.Prodi;
		this.GraphShowCanvas(selectorshowGraph2,responseAC);
	}

	chart_ajax = async() => {
		 let url = base_url_js+'it/summary_knowledgebase/chart_total_kb_per_divisi';
		 const response = await AjaxSubmitFormPromises(url,'');
		 return response;
	}



}

const total_kb_per_divisi = new Class_total_kb_per_divisi();

$(document).ready(function(e){
	total_kb_per_divisi.chart();
	total_kb_per_divisi.dataTableGenerate();
})