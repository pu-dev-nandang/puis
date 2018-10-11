<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Import</h4>
				<div class="toolbar no-padding">
					<!--<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>-->
				</div>
			</div>
			<div class="widget-content">
				<div class="row">
					<div class="col-md-12">
						<div class="col-xs-6 col-md-offset-3">
							<div class="thumbnail" style="height: 120px">
								<div class="row">
									<div class="col-md-12" align="center">
										<h4>Import Penjualan Formulir</h4>
									</div>
								</div>
								<div class="row" style="margin-top: 10px">
									<div class="col-md-12">
										<div class="col-xs-6 col-md-offset-3" style="">
											<div class="col-xs-6">
												<label class="control-label">Upload File:</label>
												<input type="file" data-style="fileinput" id="ExFile">
											</div>
											<div class="col-xs-6">
												<div class="col-xs-2">
													<button class="btn btn-inverse btn-notification" id="btn-proses">Proses</button>
												</div>
											</div>
										</div>
										<!-- <a href="<?php echo base_url('download_template/admisi-t_import_mhs.xlsm'); ?>">File Template</a> -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="margin-top: 15px">
					<div class="col-md-12">
						<div class="col-xs-6 col-md-offset-3">
							<div class="thumbnail" style="height: 120px">
								<div class="row">
									<div class="col-md-12" align="center">
										<h4>Import No Kwitansi</h4>
									</div>
								</div>
								<div class="row" style="margin-top: 10px">
									<div class="col-md-12">
										<div class="col-xs-6 col-md-offset-3" style="">
											<div class="col-xs-6">
												<label class="control-label">Upload File:</label>
												<input type="file" data-style="fileinput" id="ExFile2">
											</div>
											<div class="col-xs-6">
												<div class="col-xs-2">
													<button class="btn btn-inverse btn-notification" id="btn-proses2">Proses</button>
												</div>
											</div>
										</div>
										<!-- <a href="<?php echo base_url('download_template/admisi-t_import_mhs.xlsm'); ?>">File Template</a> -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> <!-- /.col-md-6 -->
</div>

<script type="text/javascript">

	$(document).on('click','#btn-proses', function () {
		loading_button('#btn-proses');
	  if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
	  		toastr.error('The File APIs are not fully supported in this browser.', 'Failed!!');
	        return;
	      }   

	      input = document.getElementById('ExFile');
	      if (!input) {
	        toastr.error('Um, couldnot find the fileinput element.', 'Failed!!');
	        $('#btn-proses').prop('disabled',false).html('Proses'); 
	      }
	      else if (!input.files) {
	        toastr.error('This browser doesnot seem to support the `files', 'Failed!!');
	        $('#btn-proses').prop('disabled',false).html('Proses'); 
	      }
	      else if (!input.files[0]) {
	        toastr.error('Please select a file before clicking Proses', 'Failed!!');
	        $('#btn-proses').prop('disabled',false).html('Proses'); 
	      }
	      else {
          	processFile();

	      }
	     
	});

		$(document).on('click','#btn-proses2', function () {
			loading_button('#btn-proses2');
		  if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
		  		toastr.error('The File APIs are not fully supported in this browser.', 'Failed!!');
		        return;
		      }   

		      input = document.getElementById('ExFile2');
		      if (!input) {
		        toastr.error('Um, couldnot find the fileinput element.', 'Failed!!');
		        $('#btn-proses2').prop('disabled',false).html('Proses'); 
		      }
		      else if (!input.files) {
		        toastr.error('This browser doesnot seem to support the `files', 'Failed!!');
		        $('#btn-proses2').prop('disabled',false).html('Proses'); 
		      }
		      else if (!input.files[0]) {
		        toastr.error('Please select a file before clicking Proses', 'Failed!!');
		        $('#btn-proses2').prop('disabled',false).html('Proses'); 
		      }
		      else {
	          	processFile2();

		      }
		     
		});

		function processFile2()
		{
			var form_data = new FormData();
			var fileData = document.getElementById("ExFile2").files[0];
			var url = base_url_js + "admission/distribusi-formulir/offline/submit_import_excel_kwitansi_penjualan_formulir_offline";
			form_data.append('fileData',fileData);
		  	$.ajax({
		  	  type:"POST",
		  	  url:url,
		  	  data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
		  	  contentType: false,       // The content type used when sending data to the server.
		  	  cache: false,             // To unable request pages to be cached
		  	  processData:false,
		  	  dataType: "json",
		  	  success:function(data)
		  	  {
		  	    if(data.status == 1) {
		  	    	toastr.options.fadeOut = 100000;
		  	    	toastr.success(data.msg, 'Success!');
		  	    }
		  	    else
		  	    {
		  	    	toastr.options.fadeOut = 100000;
		  	    	toastr.error(data.msg, 'Failed!!');
		  	    }
		    	setTimeout(function () {
		         toastr.clear();
		     	},1000);
		    	$('#btn-proses2').prop('disabled',false).html('Proses');
		 		
		  	  },
		  	  error: function (data) {
		  	    toastr.error("Connection Error, Please try again", 'Error!!');
		  	    $('#btn-proses2').prop('disabled',false).html('Proses');  
		  	  }
		  	})
		}

	function processFile()
	{
		var form_data = new FormData();
		var fileData = document.getElementById("ExFile").files[0];
		var url = base_url_js + "admission/distribusi-formulir/offline/submit_import_excel_penjualan_formulir_offline";
		form_data.append('fileData',fileData);
	  	$.ajax({
	  	  type:"POST",
	  	  url:url,
	  	  data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
	  	  contentType: false,       // The content type used when sending data to the server.
	  	  cache: false,             // To unable request pages to be cached
	  	  processData:false,
	  	  dataType: "json",
	  	  success:function(data)
	  	  {
	  	    if(data.status == 1) {
	  	    	toastr.options.fadeOut = 100000;
	  	    	toastr.success(data.msg, 'Success!');
	  	    }
	  	    else
	  	    {
	  	    	toastr.options.fadeOut = 100000;
	  	    	toastr.error(data.msg, 'Failed!!');
	  	    }
	    	setTimeout(function () {
	         toastr.clear();
	     	},1000);
	    	$('#btn-proses').prop('disabled',false).html('Proses');
	 		
	  	  },
	  	  error: function (data) {
	  	    toastr.error("Connection Error, Please try again", 'Error!!');
	  	    $('#btn-proses').prop('disabled',false).html('Proses');  
	  	  }
	  	})
	}

</script>
