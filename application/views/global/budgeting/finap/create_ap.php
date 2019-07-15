<div class="row">
	<div class="col-xs-6 col-md-offset-3" style="min-width: 600px;overflow: auto;">
		<div class="thumbnail">
			<div id = "page_payment_list"></div>
		</div>	
	</div>
</div>
<div id="page_content">

</div>
<script type="text/javascript">
	var ClassDt = {
		htmlPage_payment_list : function(){
			var html = '';
			html = '<div class = "row" style = "margin-right : 0px;margin-left:0px;">'+
					 '<div class col-md-12>'+
					 	'<div style="padding: 5px;">'+
					 		'<h3 class="header-blue">Choose Payment</h3>'+
					 	'</div>'+
					 	'<div class = "table-responsive">'+
					 	'<table class="table table-bordered datatable2" id = "tableData_payment">'+
					 		'<thead>'+
					 			'<tr>'+
					 				'<th width = "3%" style = "text-align: center;background: #20485A;color: #FFFFFF;">No</th>'+
					 				'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Payment</th>'+
					 				'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Department</th>'+
					 				'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Status</th>'+
					 				'<th style = "text-align: center;background: #20485A;color: #FFFFFF;">Info</th>'+
					 			'</tr>'+
					 		'<thead>'+
					 		'<tbody id="dataRow"></tbody>'+
		        		'</table>'+
		        		'</div>'+
		        	 '</div>'+
		        	'</div>';

		    return html;    	 				
		},
	};

	$(document).ready(function() {
		$('#page_payment_list').html(ClassDt.htmlPage_payment_list);
		Get_data_payment().then(function(data){
			$('.C_radio:first').prop('checked',true);
			$('.C_radio:first').trigger('change');
			loadingEnd(500);
		})
	});


	function Get_data_payment(){
       var def = jQuery.Deferred();
       var data = {
   		   auth : 's3Cr3T-G4N',
       };
       var token = jwt_encode(data,"UAP)(*");
       	var table = $('#tableData_payment').DataTable({
       		"fixedHeader": true,
       	    "processing": true,
       	    "destroy": true,
       	    "serverSide": true,
       	    "iDisplayLength" : 5,
       	    "ordering" : false,
       	    "ajax":{
       	        url : base_url_js+"rest2/__get_data_payment", // json datasource
       	        ordering : false,
       	        type: "post",  // method  , by default get
       	        data : {token : token},
       	        error: function(){  // error handling
       	            $(".employee-grid-error").html("");
       	            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
       	            $("#employee-grid_processing").css("display","none");
       	            def.reject();

       	        },
       	    },
    	    'createdRow': function( row, data, dataIndex ) {
    	    	       var ListPR = data[parseInt(data.length) - 1];
    	    	       var PRHTML = '';
    	    	       PRHTML += ListPR[0];
    	    	       var ID_payment = ListPR[1].ID_payment;
    	    	       var CodeSPB = ListPR[1].CodeSPB;
    	    	       var TypePay = ListPR[1].TypePay;
    	    	       var Perihal = ListPR[1].Perihal;
    	    	       var Code_po_create = '';
    	    	       if (data[1] != null && data[1] != '') {
    	    	       	var Code_po_create = data[1];
    	    	       }

    	    	       var input_radio = '<input class="C_radio" type="radio" name="optradio" id_payment="'+ID_payment+'" TypePay = "'+TypePay+'" CodeSPB= "'+CodeSPB+'" Code_po_create= "'+Code_po_create+'" PRCode= "'+PRHTML+'" >';
    	    	       var Payment = input_radio + ' Type : '+TypePay;
    	    	       if (TypePay == 'Spb') {
    	    	       	Payment += '<br><a href="javascript:void(0)">Code : '+CodeSPB+'</a>';
    	    	       }
    	    	      if (Code_po_create != '') {
    	    	      	 Payment += '<br><label> PO/SPK Code : '+Code_po_create+'</label>';
    	    	      }
    	    	      if (Code_po_create != '') {
    	    	      	 Payment += '<br>PR Code : '+PRHTML;
    	    	      }

    	    	       Payment += '<p style = "color : red;">Perihal : '+Perihal+'</p>';
    	    	       Payment += 'Created : '+data[parseInt(data.length) - 2];
    	    	       
    	    	       $( row ).find('td:eq(1)').html(Payment);
	    		    	
	    		    	$( row ).find('td:eq(2)').attr('align','center');
	    		    	$( row ).find('td:eq(4)').attr('align','center');
	    		    	$( row ).find('td:eq(4)').html('<a href="javascript:void(0)" class="btn btn-info btn_circulation_sheet" id_payment="'+ID_payment+'">Info</a>');
    	    },
       	    "initComplete": function(settings, json) {
       	        def.resolve(json);
       	    }
       	});
       return def.promise();
	}

	$(document).off('click', '.btn_circulation_sheet').on('click', '.btn_circulation_sheet',function(e) {
	    var url = base_url_js+'rest2/__show_info_payment';
	    var ID_payment = $(this).attr('id_payment');
   		var data = {
   		    ID_payment : ID_payment,
   		    auth : 's3Cr3T-G4N',
   		};
   		var token = jwt_encode(data,"UAP)(*");
   		$.post(url,{ token:token },function (data_json) {
   			var html = '<div class = "row"><div class="col-md-12"><div class="well">';
   				html += '<table class="table table-striped table-bordered table-hover table-checkable tableData" id = "TblModal">'+
                      '<caption><h4>Circulation Sheet</h4></caption>'+
                      '<thead>'+
                          '<tr>'+
                              '<th style="width: 5px;">No</th>'+
                              '<th style="width: 55px;">Desc</th>'+
                              '<th style="width: 55px;">Date</th>'+
                              '<th style="width: 55px;">By</th>';
		        html += '</tr>' ;
		        html += '</thead>' ;
		        html += '<tbody>' ;
		        html += '</tbody>' ;
		        html += '</table></div></div></div>' ;

   			var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
   			    '';
   			$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Info Payment'+'</h4>');
   			$('#GlobalModalLarge .modal-body').html(html);
   			$('#GlobalModalLarge .modal-footer').html(footer);
   			$('#GlobalModalLarge').modal({
   			    'show' : true,
   			    'backdrop' : 'static'
   			});

   			// make datatable
   				var table = $('#TblModal').DataTable({
   				      "data" : data_json['payment_circulation_sheet'],
   				      'columnDefs': [
   					      {
   					         'targets': 0,
   					         'searchable': false,
   					         'orderable': false,
   					         'className': 'dt-body-center',
   					         'render': function (data, type, full, meta){
   					             return '';
   					         }
   					      },
   					      {
   					         'targets': 1,
   					         'render': function (data, type, full, meta){
   					             return full.Desc;
   					         }
   					      },
   					      {
   					         'targets': 2,
   					         'render': function (data, type, full, meta){
   					             return full.Date;
   					         }
   					      },
   					      {
   					         'targets': 3,
   					         'render': function (data, type, full, meta){
   					             return full.Name;
   					         }
   					      },
   				      ],
   				      'createdRow': function( row, data, dataIndex ) {
   				      		$(row).find('td:eq(0)').attr('style','width : 10px;')
   				      	
   				      },
   				});

   				table.on( 'order.dt search.dt', function () {
   				        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
   				            cell.innerHTML = i+1;
   				        } );
   				} ).draw();

   		});
	})

	$(document).off('change', '.C_radio:checked').on('change', '.C_radio:checked',function(e) {
		var TypePay = $(this).attr('TypePay');
		var ID_payment = $(this).attr('ID_payment');
		var CodeSPB = $(this).attr('CodeSPB');
		var Code_po_create = $(this).attr('Code_po_create');
		var PR = $(this).attr('prcode');
		MakeDomHtml(ID_payment,TypePay,CodeSPB,Code_po_create,PR);
	})

	function MakeDomHtml(ID_payment,TypePay,CodeSPB,Code_po_create,PR)
	{
		var html = '<div class ="row FormPage" style ="margin-top:10px;">';
						'<div class = "col-xs-12">'+
							'<div class = "form-horizontal">';

		var se_content = $('#page_content');
		if (PR != '' && PR != null) {
			html += '<div class="form-group">'+
						'<label class = "col-sm-2">PR Code</label>'+
						'<div class = "col-xs-3">'+
							'<button class="btn btn-default" id="pdfprintPR" prcode="'+PR+'"> <i class="fa fa-file-pdf-o"></i> '+PR+'</button>'+
						'</div>'+
					'</div>';	
		}

		if (Code_po_create != '' && Code_po_create != null) {
			html += '<div class="form-group">'+
						'<label class = "col-sm-2">PO/SPK Code</label>'+
						'<div class = "col-xs-3">'+
							'<button class="btn btn-default" id="pdfprintPO" Code_po_create="'+Code_po_create+'"> <i class="fa fa-file-pdf-o"></i> '+Code_po_create+'</button>'+
						'</div>'+
					'</div>';	
		}

		html += '<div class="form-group">'+
						'<label class = "col-sm-2">Payment</label>'+
						'<div class = "col-xs-3">'+
							'<button class="btn btn-default" id="pdfprintPO" ID_payment="'+ID_payment+'"> <i class="fa fa-file-pdf-o"></i> '+'View Pay'+'</button>'+
						'</div>'+
					'</div>';

		html += '</div>';		

		se_content.html(html);		

	}
</script>