<style type="text/css">
	#example_budget_ss.dataTable tbody tr:hover {
	   background-color:#71d1eb !important;
	   cursor: pointer;
	}
</style>
<div class="row">
	<div class="col-md-4">
		<?php $this->load->view('page/'.$department.'/kerjasama-perguruan-tinggi/kegiatan_kerja_sama_perguruan_tinggi/form-input') ?>
	</div>
	<div class="col-md-8">
		<?php $this->load->view('page/'.$department.'/kerjasama-perguruan-tinggi/kegiatan_kerja_sama_perguruan_tinggi/view-data') ?>
	</div>
</div>
<script type="text/javascript">
	var oTable;
	function ModalTblLembaga(vfor='')
	{
		vfor = (vfor == '' || vfor == undefined) ? '' : vfor;
		// server side
		var selector = 'example_budget_ss';
		var html  = '<div class = "row">'+
						'<div class = "col-md-12">'+
							'<table class="table table-bordered" id="'+selector+'">'+
								'<thead>'+
									'<tr>'+
										'<th>No</th>'+
										'<th>Lembaga</th>'+
										'<th>Bukti</th>'+
										'<th>Date</th>'+
										'<th>Perjanjian</th>'+
										'<th>Department</th>'+
									'</tr>'+
								'</thead>'+
								'<tbody></tbody>'+
							'</table>'+
						'</div>'+
					'</div>';

		$('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Pilih Lembaga'+'</h4>');
		$('#GlobalModalLarge .modal-body').html(html);
		$('#GlobalModalLarge .modal-footer').html(''+
			'<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');
		$('#GlobalModalLarge').modal({
		    'show' : true,
		    'backdrop' : 'static'
		});


		LoadLembagaServerSide(selector,vfor);
	}

	 function LoadLembagaServerSide(selector = 'example_budget_ss',vfor = '')
	 {
	 	var data = {
	 	    auth : 's3Cr3T-G4N',
	 	    mode : 'DataKerjaSama',
	 	    // Active : 1,
	 	};

	 	var token = jwt_encode(data,"UAP)(*");
	 	$(selector+' tbody').empty();
		var table = $('#'+selector).DataTable({
			"fixedHeader": true,
		    "processing": true,
		    "destroy": true,
		    "serverSide": true,
	      "lengthMenu": [[5], [5]],
		    "iDisplayLength" : 5,
		    "ordering" : false,
	      "language": {
	          "searchPlaceholder": "Search",
	      },
		    "ajax":{
		        url : base_url_js+"rest2/__get_data_kerja_sama_perguruan_tinggi", // json datasource
		        ordering : false,
		        type: "post",  // method  , by default get
		        data : {token : token},
		        error: function(){  // error handling
		            $(".employee-grid-error").html("");
		            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
		            $("#employee-grid_processing").css("display","none");
		        }
		    },
	   	    'createdRow': function( row, data, dataIndex ) {
	   	    	$(row).attr('data-id',data[6]);
	   	    	// get data
	   	    	var tokenEdit = data[7];
	   	    	$(row).attr('data',tokenEdit);
	   	    	$(row).attr('vfor',vfor);
	   	    	// console.log(data);
	   	    	var Bukti = data[2];
	   	    	var a = Bukti.split('--');
	   	    	var html = '';
	   	    	var File = jQuery.parseJSON(a[1]);
	   	    	html = a[0]+'<br>'+'<a href = "'+base_url_js+'fileGetAny/cooperation-'+File[0]+'" target="_blank" class = "Fileexist">Attachment</a>';
	   	    	$( row ).find('td:eq(2)').html(html);

	   	    	var Perjanjian = data[4];
	   	    	html = '';
	   	    	var cc = Perjanjian.split(',');
	   	    	for (var i = 0; i < cc.length; i++) {
	   	    		var zc = cc[i];
	   	    		a = zc.split('--');
	   	    		File = jQuery.parseJSON(a[1]);
	   	    		html += '<li>'+a[0]+'<br>'+'<a href = "'+base_url_js+'fileGetAny/cooperation-'+File[0]+'" target="_blank" class = "Fileexist" style="margin-left:19px;">Attachment</a></li>';
	   	    	}
	   	    	
	   	    	$( row ).find('td:eq(4)').html(html);

	   	    	var Departement = data[5];
	   	    	html = '';
	   	    	cc = Departement.split(',');
	   	    	var arr = [];
	   	    	for (var i = 0; i < cc.length; i++) {
	   	    		var zc = cc[i];
	   	    		a = zc.split('--');
	   	    		var temp = {
	   	    			Code : a[0],
	   	    			Name : a[1],
	   	    		}
	   	    		arr.push(temp);
	   	    	}
	   	    	var selector = $( row ).find('td:eq(5)');
	   	    	HtmlPageDepartmentSelected(arr,selector,'listtblModal');
   	    },
	        dom: 'l<"toolbar">frtip',
	   	    "initComplete": function(settings, json) {

	   	    }
		});

		table.on( 'click', 'tr', function (e) {
			var row = $(this);
			var ID = row.attr('data-id');
			var tokenEdit = row.attr('data');
			var data = jwt_decode(tokenEdit);
			// console.log(data);
			var vforGet = $(this).attr('vfor');
			var s_show = (vforGet == '') ? '.inputshow[name="KerjasamaID"]' : '.inputshowSearch[name="KerjasamaID"]';
			var s_input = (vforGet == '') ? '.input[name="KerjasamaID"]' : '.inputSearch[name="KerjasamaID"]';
			$(s_show).val(data['Lembaga'])
			$(s_input).val(data['KerjasamaID'])
			$(s_input).attr('kategori',data['Kategori']);
			$('#GlobalModalLarge').modal('hide');
			if (vforGet != '') {
				$('.inputSearch').trigger('change');
			}
			else
			{
				$(s_input).trigger('change');
			}
			
		});
	 }

	 function HtmlPageDepartmentSelected(arr,selector,classdt='input_li')
	 {
	 	var html = '<div class = "row">';
	 	var MaxRow = 11;
	 	var Total = arr.length;
	 	var split = parseInt(Total / MaxRow);
	 	var sisa = Total % MaxRow;
	 	if (sisa > 0) {
	 	    split++;
	 	}

	 	var col = parseInt(12 / split);
	 	var sisa = 12 % split;
	 	if (sisa > 0) {
	 		col--;
	 	}

	 	var r = 0;
	 	for (var x = 0; x < split; x++) {
	 		var lihtml = '<ul class ="'+classdt+'" style ="margin-left:-30px;">';
	 		for (var z = 0; z < MaxRow; z++) {
	 			// console.log(r);
	 			if (r == Total) {
	 				break;
	 			}
	 			lihtml += '<li code = "'+arr[r].Code+'">'+arr[r].Name+'</li>';
	 			r++;	
	 		}
	 		lihtml += '</ul>';
	 		html += '<div class = "col-md-'+col+'" >'+lihtml+'</div>';
	 	}

	 	html += '</div>';

	 	selector.html(html);	
	 }

	 function FormEditSelected(dt)
	 {
	 	// console.log(dt);
	 	for (key in dt){
	 		if (key != 'Perjanjian' && key != 'DepartmentKS' && key != 'BuktiUpload' && key != 'KerjasamaID' && key != 'ID' && key != 'FileLain') {
	 			if (key == 'SemesterID' || key == 'Kategori_kegiatan') {
	 				$('.input[name="'+key+'"] option').filter(function() {
	 				   //may want to use $.trim in here
	 				   return $(this).val() == dt[key]; 
	 				}).prop("selected", true);
	 			}
	 			else
	 			{
	 				$('.input[name="'+key+'"]').val(dt[key]);
	 			}
	 		}
	 		else
	 		{
	 			switch(key) {
	 			  case 'DepartmentKS':
	 			    var rsPass = [];
	 			    var arr = dt[key].split(',');
	 			    for (var i = 0; i < arr.length; i++) {
	 			    	var d = arr[i];
	 			    	var cc = d.split('--');
	 			    	var temp = {
	 			    		Code : cc[0],
	 			    		Name : cc[1],
	 			    	};

	 			    	rsPass.push(temp);
	 			    }
	 			    var selector = $('.ListDepartmentSelected');
	 			    HtmlPageDepartmentSelected(rsPass,selector);
	 			    break;
	 			  case 'ID':
	 			  	$('#btnSave').attr('mode','edit');
	 			  	$('#btnSave').attr('data-id',dt[key]);
	 			  break;
	 			  case 'KerjasamaID':
	 			  	$('.input[name="KerjasamaID"]').val(dt[key]);
	 			  	$('.input[name="KerjasamaID"]').attr('kategori',dt['Kategori_kegiatan']);
	 			  	$('.inputshow[name="KerjasamaID"]').val(dt['Lembaga']);
	 			  break;
	 			  default:
	 			    // code block
	 			}
	 		}
	 	}
	 	$('.input[name="KerjasamaID"]').trigger('change');
	 }
</script>