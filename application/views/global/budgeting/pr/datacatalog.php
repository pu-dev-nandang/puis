<div id = "ctcatalog">
	
</div>
<script type="text/javascript">
	$(document).ready(function() {
		LoadFirst();

		function LoadFirst()
		{
				var html = '';
				html ='<div class = "row">'+
						'<div class = "col-md-12">'+
							'<table id="example22" class="table table-bordered display select" cellspacing="0" width="100%">'+
	               '<thead>'+
	                  '<tr>'+
	                     '<th>No</th>'+
	                     '<th>Item</th>'+
	                     '<th>Desc</th>'+
	                     '<th>Estimate Value</th>'+
	                     '<th>Photo</th>'+
	                     '<th>DetailCatalog</th>'+
	                     '<th>Status</th>'+
	                     '<th>Action</th>'+
	                  '</tr>'+
	               '</thead>'+
	          '</table></div></div>';

	          	$("#ctcatalog").html(html);
	          	var sessIDDepartementPUBudget = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";

	          	var url = base_url_js+'rest/Catalog/__Get_Item';
	          	var data = {
	          		action : 'forUser',
	          		auth : 's3Cr3T-G4N',
	          		department : sessIDDepartementPUBudget,
	          	};
	              var token = jwt_encode(data,"UAP)(*");
	          	var table = $('#example22').DataTable({
	          	      'ajax': {
	          	         'url': url,
	          	         'type' : 'POST',
	          	         'data'	: {
	          	         	token : token,
	          	         },
	          	         dataType: 'json'
	          	      },
	          	      'columnDefs': [{
	          	         'targets': 0,
	          	         'searchable': false,
	          	         'orderable': false,
	          	      },
	          	      {
	          	      	'targets': 6,
	          	      	'className': 'dt-body-center',
	          	      	'render': function (data, type, full, meta){
	          	      		 var btn = '';
	          	      		 var status = '';
	          	      		 if (full[8]== 1 || full[8]== 0) {
	          	      		 	btn = '';
	          	      		 	status = (full[8]== 1) ? 'Approve' : 'Not Approve';
	          	      		 }
	          	      		 else{
	          	      		 	status = 'Reject';
	          	      		 	btn = '<br><br><button type="button" class="btn btn-default btn-edit btn-reason" reason= "'+full[9]+'"> Reason</button>';
	          	      		 }
	          	      		
	          	      		status = status+btn

	          	      	    return status;
	          	      	    // return no;
	          	      	}
	          	      },
	          	      {
	          	      	'targets': 7,
	          	      	'className': 'dt-body-center',
	          	      	'render': function (data, type, full, meta){
	          	      		 // console.log(meta);
	          	      	    return '<button type="button" class="btn btn-warning btn-edit btn-edit-catalog" code="'+full[6]+'"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button> &nbsp <button type="button" class="btn btn-danger btn-delete btn-delete-catalog" code="'+full[6]+'"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
	          	      	    // return no;
	          	      	}
	          	      },
	          	      ],
	          	      'order': [[1, 'asc']]
	          	   });

	          	table.on( 'order.dt search.dt', function () {
	          	       table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	          	           cell.innerHTML = i+1;
	          	       } );
	          	   } ).draw();
		}

		$('#example22 tbody').on('click', '.btn-edit-catalog', function () {
			loading_page("#pageContentCatalog");
			var url = base_url_js+'budgeting/page_pr_catalog/'+'entry_catalog';
				var data = {
					action : 'edit',
					auth : 's3Cr3T-G4N',
					ID : $(this).attr('code'),
				};
			    var token = jwt_encode(data,"UAP)(*");

			$.post(url,{token : token},function (resultJson) {
			    var response = jQuery.parseJSON(resultJson);
			    var html = response.html;
			    var jsonPass = response.jsonPass;
			    setTimeout(function () {
			        $("#pageContentCatalog").empty();
			        $("#pageContentCatalog").html(html);
			        $(".menuCatalog li").removeClass('active');
			        $(".pageAnchorCatalog[page='entry_catalog']").parent().addClass('active');
			    },1000);
			    
			}); // exit spost
		});

		$('#example22 tbody').on('click', '.btn-reason', function () {
			var Reason = $(this).attr('reason');
			var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>'+
			    '';
			$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Reason'+'</h4>');
			$('#GlobalModalLarge .modal-body').html(Reason);
			$('#GlobalModalLarge .modal-footer').html(footer);
			$('#GlobalModalLarge').modal({
			    'show' : true,
			    'backdrop' : 'static'
			});
		});

		$('#example22 tbody').on('click', '.btn-delete-catalog', function () {
			if (confirm("Are you sure?") == true) {
				var data = {
		  	                    Detail : '',
          		                Action : "delete",
          		                Departement : '',
          		                Item : '',
          		                Desc : '',
          		                EstimaValue : '',
          		                ID : $(this).attr('code'),
          		                auth : 's3Cr3T-G4N',
          		                user : "<?php echo $this->session->userdata('NIP') ?>",
	  	                   };
			  	var token = jwt_encode(data,"UAP)(*");
			  	var url = base_url_js + "rest/__InputCatalog_saveFormInput";
			  	$.post(url,{token:token},function (data_json) {
  	               var obj = data_json; 
  	               if(obj == "")
  	               {
  	               	LoadPageCatalog('datacatalog');
  	               	toastr.success("Done", 'Success!');
  	               }
  	               else
  	               {
  	               	toastr.error(obj,'Failed!!');
  	               }

  	           }).done(function() {
  	             
  	           }).fail(function() {
  	             toastr.error('The Database connection error, please try again', 'Failed!!');
  	           }).always(function() {
  	           		

  	           });
			}	
			else {
                return false;
            }
		});
	}); // exit document Function

</script>