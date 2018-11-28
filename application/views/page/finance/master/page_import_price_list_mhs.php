<div class="row">
    <div class="col-xs-12" >
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Report</h4>
            </div>
            <div class="panel-body">
               <div class="row" style="margin-top: 30px;">
	               	<div class="col-md-12">
	               		<div class="widget box">
	               			<div class="widget-header">
	               				<h4><i class="icon-reorder"></i>Import Price List Mahasiswa</h4>
	               				<div class="toolbar no-padding">
	               					<!--<div class="btn-group">
	               						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
	               					</div>-->
	               				</div>
	               			</div>
	               			<div class="widget-content">
	               				<div class="form-horizontal">
	               					<div class="form-group">
	               							<div class="col-xs-2" style="">
	               							    Kategory
	               							    <select class="select2-select-00 col-md-4 full-width-fix" id="selectKategory">
	               							        <option value="1">*</option>
	               							        <option value="2">**</option>
	               							    </select>
	               							</div>
	               							<div class="col-xs-2" style="">
	               								<label class="control-label">Upload File:</label>
	               								<input type="file" data-style="fileinput" id="ExFile">
	               							</div>
	               							<a href="<?php echo base_url('download_template/finance-Template_import_price_list_mhs.xlsx'); ?>">File Template</a>
	               							<div class="col-xs-1">
	               								<button class="btn btn-inverse btn-notification" id="btn-proses">Proses</button>
	               							</div>
	               						</div>
	               					<!-- </div> -->
	               				</div>
	               			</div>
	               		</div>
	               	</div>
               </div>         
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
	$(document).ready(function () {
	   
	});

	$(document).on('click','#btn-proses', function () {
		loading_button('#btn-proses');
	  if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
	  		toastr.error('The File APIs are not fully supported in this browser.', 'Failed!!');
	  		$('#btn-proses').prop('disabled',false).html('Proses');  
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
	        /*file = input.files[0];
	        fr = new FileReader();
	        fr.onload = receivedText;
          	fr.readAsText(file);
          	//fr.readAsDataURL(file);*/
          	processFile();

	      }
	     
	});

	function processFile()
	{
		var form_data = new FormData();
		var fileData = document.getElementById("ExFile").files[0];
		var url = base_url_js + "finance/tagihan-mhs/submit_import_price_list_mhs";
		var selectKategory = $("#selectKategory").val();
		form_data.append('fileData',fileData);
		form_data.append('Pay_Cond',selectKategory);
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
