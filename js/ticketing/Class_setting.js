class Class_setting {
	
	constructor(DepartmentID){
		this.data = [];
		this.obj = {};
		this.Wrhtml = '';
		this.__setDepartmentID(DepartmentID);
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

	__setDepartmentID = (DepartmentID) => {
		this.obj['DepartmentID'] = DepartmentID;
		return this;
	}

	pageAuthDashboard = () => {
		let html = '';
		html  += '<div class = "row">'+
					'<div class = "col-md-8">'+
						'<div class="widget box">'+
							'<div class="widget-header">'+
								'<h4><i class="icon-reorder"></i> Auth Dashboard</h4>'+
								'<div class="toolbar no-padding">'+
								    '<div class="btn-group">'+
								        '<span data-smt="1" class="btn btn-xs btn-add-person">'+
								            'Add Person'+
								        '</span>'+
								    '</div>'+
								'</div>'+
							'</div>'+
							'<div class="widget-content no-padding ">'+
								'<table class="table table-striped" id="Tblticket_setting_dashboard">'+
									'<thead>'+
										'<tr>'+
										    '<th>No</th>'+
										    '<th>Name</th>'+
										    '<th>Action</th>'+
										'</tr>'+
									'</thead>'+
									'<tbody>'+
									'</tbody>'+    
								'</table>'+
							'</div>'+
						'</div>'+
					'</div>'+
				 '</div>';	

		this.Wrhtml = html;
		return this;

	}

	LoadTable_setting_dashboard = (selector) => {
		if (typeof oTable3 === 'undefined') {
		    let oTable3;
		}

		let recordTable = selector.DataTable({
		    "processing": true,
		    "serverSide": false,
		    "ajax":{
		        url : base_url_js+"rest_ticketing/__CRUDAdmin?apikey="+Apikey, // json datasource
		        ordering : false,
		        type: "post",  // method  , by default get
		        beforeSend: function (xhr)
		        {
		           xhr.setRequestHeader("Hjwtkey",Hjwtkey);
		        },
		        data : function(token){
		              // Read values
		               let data = {
		                      action : 'read_auth_dashboard',
		                      auth : 's3Cr3T-G4N',
		                  };
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
		              'orderable': false,
		              'className': 'dt-body-center',
		              'render': function (data, type, full, meta){
		                  let btnAction = '<div class="btn-group">' +
		                      '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
		                      '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
		                      '  </button>' +
		                      '  <ul class="dropdown-menu">' +
		                      '    <li><a href="javascript:void(0);" class="btnEditDashboard" data-id="'+full[2]+'" data = "'+full[7]+'"><i class="fa fa fa-edit"></i> Edit</a></li>' +
		                      '    <li role="separator" class="divider"></li>' +
		                      '    <li><a href="javascript:void(0);" class="btnRemoveDashboard" data-id="'+full[2]+'"><i class="fa fa fa-trash"></i> Remove</a></li>' +
		                      '  </ul>' +
		                      '</div>';
		                  return btnAction;
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

		oTable3 = recordTable;
	}

	ModalAddAuthDashboard = (...args) => {
		let html = '';
		html = '<div class="row">'+
		            '<div class="col-md-12">'+
		                '<div class = "row">'+
		                    '<div class="col-sm-3">'+
		                        '<label class="control-label">Employee</label>'+
		                    '</div>'+
		                    '<div class="col-sm-6">'+
		                         '<input type = "text" class = "form-control showAutoComplete" placeholder = "Input NIP or Name">'+
		                         '<input type = "hidden" class = "form-control input" name = "NIP">'+
		                    '</div>'+
		                '</div>'+
		            '</div>'+
		        '</div>';

		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Auth Dashboard'+'</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>'+
		    '<button type="button" id="ModalbtnSaveFormDashboard" class="btn btn-success" action = "'+args[0]+'" data-id = "'+args[1]+'">Save</button>');
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});

		return this;

	}

	__AutoCompleteEmployee = (selector) => {
		selector.autocomplete({
		  minLength: 4,
		  select: function (event, ui) {
		    event.preventDefault();
		    var selectedObj = ui.item;
		    $('.input[name="NIP"]').val(selectedObj.value);
		    $('.input[name="NIP"]').attr('selectedtext',selectedObj.label);
		    selector.val(selectedObj.label);
		  },
		  source:
		  function(req, add)
		  {
		    var url = base_url_js+"rest_ticketing/__AutocompleteEmployees?apikey="+Apikey;
		    var data = {
		                auth : 's3Cr3T-G4N',
		                search : selector.val(),
		                };
		    var token = jwt_encode(data,"UAP)(*");
		    $.ajax({
		      type: 'POST',
		      url: url,
		      data: {token:token},
		      dataType: "json",
		      beforeSend: function (xhr)
		      {
		         xhr.setRequestHeader("Hjwtkey",Hjwtkey);
		      },
		      success:function(obj)
		      {
		        add(obj.message) 
		      }
		    });          
		  } 
		})

		selector.autocomplete( "option", "appendTo", "#GlobalModalLarge .modal-body" );
	}

	Submit = async (...args) => {
		let selector = args[0];
		let btnHTML = selector.html();
		let action = args[1];
		let ID = args[2];
		let data = {};
		$('.input').each(function(){
		    var field = $(this).attr('name');
		    data[field] = $(this).val();
		})
		data['UpdatedBy'] = sessionNIP;
		let dataform = {
		    action : 'auth_dashboard',
		    subdata : {
		    	data : data,
		    	ID : ID,
		    	action : action,
		    },
		    auth : 's3Cr3T-G4N',
		    
		};
		let token = jwt_encode(dataform,'UAP)(*');
		let url = base_url_js+'rest_ticketing/__CRUDAdmin';
		let requestHeader = {
			Hjwtkey : Hjwtkey,
		}
		let validation = (action == 'delete') ? true : App_ticketing_setting_admin.Validation(data);
		if (validation) {
			 if (confirm('Are you sure ?')) {
			 	loading_button2(selector);
			 	const response = await AjaxSubmitFormPromises(url,token,[],Apikey,requestHeader);
			 	if (response.status == 1) {
			 	    end_loading_button2(selector,btnHTML);
			 	    oTable3.ajax.reload( null, false );
			 	    $('#GlobalModalLarge').modal('hide');
			 	}
			 	else
			 	{
			 	    toastr.error(data.msg);
			 	    end_loading_button2(selector,btnHTML);
			 	}
			 }
		}
	}

}