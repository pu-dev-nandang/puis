class Class_template_table_default
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
			    lengthMenu: [[10], [10]],
			    iDisplayLength : 10,
			    "searching": false,
			    ajax: {
			        url: $('#table_default').attr('data-url'),
			        type: 'post',
			        data: function (d) {
			        	let filterTable = {}
			            $('.filterSearch select[tabindex="-1"], .filterSearch input').each(function () {
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
			        [$('#table_default th.default-sort').index(), $('#table_default th.default-sort').attr('data-sort')]
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

	load_default = () => {
		$('.filterSearch').find('select[tabindex!="-1"]').removeClass('form-control');
		$('.filterSearch').find('select[tabindex!="-1"]').removeClass('form-control-sm');
		$('.filterSearch').find('select[tabindex!="-1"]').select2({
		    allowClear: true
		});
	}

	dataTableGenerate = () => {
		var columns = [];
		$('#table_default .column th').each(function () {
		    columns.push({
		        data: $(this).attr('data-data')
		    });
		});
		this.param.table_config.columns = columns;
		this.param.sel_table = $('#table_default').DataTable(this.param.table_config);

	}
}

const template_table_default = new Class_template_table_default();

$(document).ready(function(e){
	template_table_default.load_default();
	template_table_default.dataTableGenerate();

	let tbl = template_table_default.param.sel_table;

	$('body').on('keyup', '.filterSearch input', function (e) {
	    if (e.keyCode == 13) {
	        tbl.ajax.reload((e) => {
	            
	        });
	    }
	});

	$('body').on('change', '.filterSearch select', function (e) {
	    tbl.ajax.reload((e) => {
	        
	    });
	});

	$('body').on('click', '.delete_row_default', function (e) {
	    var get_url = $(this).attr('href');
	    e.preventDefault();
	    if (confirm('Are you sure want to delete ?')) {
	    	delete_row_default(get_url,tbl)
	    }
	});
})

const delete_row_default = async(get_url,tbl) => {
	try {
	  var response =  await AjaxSubmitFormPromises(get_url,'');
	  if (response.status == 'success') {
	  	tbl.ajax.reload((e) => {
	  	    
	  	});
	  	success_message_response(response.message);
	  }
	  else
	  {
	  	error_message_response(response.message);
	  }
	    
	}
	catch(err) {
	  toastr.error('something wrong, please contact IT','!Error');
	}
}