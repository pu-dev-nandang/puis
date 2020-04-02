let oTabletestimony;
let oneTime = 0;
class App_testimony {

	constructor(){

	}

	LoadDefault = () => {
		let selectorTbl = $('#tblTestimony');
		let table = selectorTbl.DataTable({
		    "fixedHeader": true,
		    "responsive": true,
		    "processing": true,
		    "destroy": true,
		    "serverSide": true,
		    "lengthMenu": [
		        [5, 10,20],
		        [5, 10,20]
		    ],
		    "iDisplayLength": 10,
		    "ordering": false,
		    "language": {
		        "searchPlaceholder": "Student",
		    },
		    "ajax": {
		        url: base_url_js+'rest_alumni/__Testimony'+CustomPost['get'], // json datasource
		        ordering: false,
		        type: "post", // method  , by default get
		        beforeSend: function (xhr)
		        {
		           let requestHeader = CustomPost['header'];
		           for (let key in requestHeader){
		              xhr.setRequestHeader(key,requestHeader[key]);
		           }
		          
		        },
		        data: function(token) {
		            // Read values
		            let data = {
		                action: 'server_side',
		                auth : 's3Cr3T-G4N',
		                data : {
		                	filterStatus : $('#filterStatus option:selected').val(),	
		                },	
		            };

		            if (oneTime == 0 && urlID != undefined && urlID != '') {
		            	data['data']['SearchUrlID'] =  jwt_decode(urlID);
		            }

		            let get_token = jwt_encode(data, "UAP)(*");
		            token.token = get_token;
		        },
		    },
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
		          'orderable': false,
		          'className': 'dt-body-center',
		          'render': function (data, type, full, meta){
		            let html = full[1]+'<br/>'+moment(new Date(full[2])).format("DD MMMM YYYY H:mm:ss")
		            return html;
		          }
		        },
		        {
		          'targets': 2,
		          'searchable': false,
		          'orderable': false,
		          'render': function (data, type, full, meta){
		            let html = '<div class = "well">'+full[3]+'</div>';
		            return html;
		          }
		        },
		        {
		          'targets': 3,
		          'searchable': false,
		          'orderable': false,
		          'render': function (data, type, full, meta){
		          	let NameStatus = '';
		          	switch (full[4]){
		          		 case '0':
		          		 case  0:
		          		 	NameStatus = '<div style = "color:blue;">Not Approved</div>';
		          		 	break;
		          		 case '1':
		          		 case  1:
		          		 	NameStatus = '<div style = "color:green;">Approved</div>';
		          		 	break;
		          		 case '-1':
		          		 case  -1:
		          		 	NameStatus = '<div style = "color:red;">Reject</div>';
		          		 	break;
		          	}

		            let html = NameStatus + '<br/>'+'<button class = "btn btn-sm btn-primary btnInfo">Info</button>';
		            return html;
		          }
		        },
		        {
		            'targets': 4,
		            'searchable': false,
		            'orderable': false,
		            'className': 'dt-body-center',
		            'render': function (data, type, full, meta){
		            	let btnAction = '';
		            	if (full[4] != 1 && full[4] != '1') {
		            		btnAction = '<button class ="btn btn-sm btn-success btnApprove">Approve</button>'+
		            						' '+
		            						'<button class ="btn btn-sm btn-danger btnReject">Reject</button>'	
		            	}
		                return btnAction;
		            }
		        },
		    ],
		    'createdRow': function(row, data, dataIndex) {
		        $(row).attr('data',data['data']);
		    },
		    dom: 'l<"toolbar">frtip',
		    "initComplete": function(settings, json) {
                oneTime++;
		    }
		});

		oTabletestimony= table;
	}

	__getInfo = (de) => {
		let html  = '';
		let dataRow = '';
		for (var i = 0; i < de.length; i++) {
			dataRow += '<tr>'+
							'<td>'+ (i+1)+'</td>'+
							'<td>'+ de[i].Info+'</td>'+
							'<td>'+ de[i].CreateBy+'</td>'+
							'<td>'+ de[i].CreateAt+'</td>'+
						'</tr>';
		}
		html += '<div class  = "row">'+
					'<div class = "col-md-12">'+
						'<table class = "table table-striped">'+
							'<thead>'+
								'<tr>'+
									'<th>No</th>'+
									'<th>Action</th>'+
									'<th>By</th>'+
									'<th>Time</th>'+
								'</tr>'+
							'</thead>'+
							'<tbody>'+
								dataRow+
							'</tbody>'+
						'</table>'+
					'</div>'+
				'</div>';	

		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Info'+'</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html(''+
		' <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});
	}

	ApproveOrReject = async (selector,ID,action) => {
		let cls = this;
		let htmlBtn = selector.html();
		let data = {
			ID : ID,
			UserID : sessionNIP,
			Status : action,
		}

		let dataForm = {
			auth : 's3Cr3T-G4N',
			data : data,
		}

		let url = base_url_js+'rest_alumni/__testimony_ApproveOrReject';
		let token = jwt_encode(dataForm,'UAP)(*');
		let Apikey = CustomPost['get'];
		Apikey = findAndReplace(Apikey, '?apikey=', '');
		if (confirm('Are you sure ?')) {
			loading_button2(selector);
			const ajaxGetResponse = await AjaxSubmitFormPromises(url,token,[],Apikey,CustomPost['header']);
			end_loading_button2(selector,htmlBtn);
			if (ajaxGetResponse.status == 1) {
				toastr.info('Success');
				oTabletestimony.ajax.reload(null, false);
			}
		}
		
	}

	reloadTable = () => {
		oTabletestimony.ajax.reload(null, false);
	}

}