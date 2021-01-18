class Class_total_max_view_log_employees
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
			    oSearch: {sSearch: filterTbl_total_top100_view_log_employees},
			    lengthMenu: [[10], [10]],
			    iDisplayLength : 10,
			    language: {
			        "searchPlaceholder": "Search",
			    },
			    ajax: {
			        url: base_url_js+'it/summary_knowledgebase/get_total_top100_view_log_employees',
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
		$('#tbl_total_top100_view_log_employees .column th').each(function () {
		    columns.push({
		        data: $(this).attr('data-data')
		    });
		});
		this.param.table_config.columns = columns;
		this.param.sel_table = $('#tbl_total_top100_view_log_employees').DataTable(this.param.table_config);
	}

}

const total_max_view_log_employees = new Class_total_max_view_log_employees();

$(document).ready(function(e){
	total_max_view_log_employees.dataTableGenerate();
})