<div class="row">
	<div class="col-md-4">
		<?php $this->load->view('page/'.$department.'/kerjasama-perguruan-tinggi/master_kerja_sama_perguruan_tinggi/form-input') ?>
	</div>
	<div class="col-md-8">
		<?php $this->load->view('page/'.$department.'/kerjasama-perguruan-tinggi/master_kerja_sama_perguruan_tinggi/view-data') ?>
	</div>
</div>

<script type="text/javascript">
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
		console.log(dt);
		for (key in dt){
			if (key != 'Perjanjian' && key != 'DepartmentKS' && key != 'BuktiUpload' && key != 'KerjasamaID') {
				if (key == 'Kategori' || key == 'Tingkat') {
					$('.input[name="'+key+'"] option').filter(function() {
					   //may want to use $.trim in here
					   return $(this).val() == dt[key]; 
					}).prop("selected", true);
				}
				else
				{
					// console.log(dt[key]);
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
				  case 'Perjanjian':
				  	var rsPass = [];
				  	var Sper = $('.input[name="Perjanjian"]');
				  	Sper.prop('checked',false);
				  	$('.divFile').remove();
				  	var arr = dt[key].split(',');
				  	for (var i = 0; i < arr.length; i++) {
				  		var d = arr[i];
				  		var cc = d.split('--');
				  		var v = cc[0];
				  		var f = cc[1];
				  		f = jQuery.parseJSON(f);
				  		f = '<a href = "'+base_url_js+'fileGetAny/cooperation-'+f[0]+'" target="_blank" class = "Fileexist">File</a>';
				  		var IDP = cc[2];
				  		Sper.each(function(){
				  			if (this.value == v) {
				  				$(this).prop('checked',true);
				  				var tr = $(this).closest('tr');
				  				//console.log(tr);
				  				if (tr.find('td:eq(2)').find('.divFile').length  ) {
				  					tr.find('td:eq(2)').find('.divFile').remove();
				  				}

				  				tr.find('td:eq(2)').append('<div class="divFile">'+f+'</div>');
				  			}
				  		})
				  	}
				  break;
				  case 'BuktiUpload':
				  	var td = $('input[name="'+key+'"]').closest('td');
				  	if (td.find('.divFileUpload').length  ) {	
				  		td.find('.divFileUpload').remove();
				  	}

				  	var f = dt[key];
				  	f = jQuery.parseJSON(f);
				  	f = '<a href = "'+base_url_js+'fileGetAny/cooperation-'+f[0]+'" target="_blank" class = "Fileexist">File</a>';	
				  	td.append('<div class="divFileUpload">'+f+'</div>');
				  break;
				  case 'KerjasamaID':
				  	$('#btnSave').attr('mode','edit');
				  	$('#btnSave').attr('data-id',dt[key]);
				  break;
				  default:
				    // code block
				}
			}
		}
	}
</script>