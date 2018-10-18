<div class="row" style="margin-top: 30px;">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i><?php echo $NameMenu ?></h4>
				<div class="toolbar no-padding">
					<!--<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>-->
				</div>
			</div>
			<div class="widget-content">
				<div class="form-horizontal">
					<div class="form-group">
						<!-- <div class = "row"> -->
							<div class="col-xs-2" style="">
								<label class="control-label">Upload File :</label>
							</div>
							<div class="col-xs-2">
								<input type="file" data-style="fileinput" id="fileAnnouncement">
							</div>
							<div class="col-xs-1">
								<button class="btn btn-inverse btn-notification" id="btn-proses">Proses</button>
							</div>
							
						</div>
					<!-- </div> -->
				</div>
			</div>
		</div>
	</div> <!-- /.col-md-6 -->
</div>

<script type="text/javascript">
	function file_validation(ID_element)
	{
	  try{
	  		var name = document.getElementById("fileAnnouncement").files[0].name;
	  		var ext = name.split('.').pop().toLowerCase();
	  		if(jQuery.inArray(ext, ['pdf']) == -1) 
	  		{
	  		  toastr.error("Invalid Image File", 'Failed!!');
	  		  return false;
	  		}
	  		var oFReader = new FileReader();
	  		oFReader.readAsDataURL(document.getElementById("fileAnnouncement").files[0]);
	  		var f = document.getElementById("fileAnnouncement").files[0];
	  		var fsize = f.size||f.fileSize;
	  		if(fsize > 2000000) // 2mb
	  		{
	  		 toastr.error("Image File Size is very big", 'Failed!!');
	  		 return false;
  			}

	  	}
	  	catch(err)
	  	{
	  		return false;
	  	}
	      return true;
	}

	$(document).on('click','#btn-proses', function () {
		var ID_element = $("#fileAnnouncement");
		if (file_validation(ID_element)) {
		  loading_button('#btn-proses');
		  var form_data = new FormData();
	        var fileData = document.getElementById("fileAnnouncement").files[0];
	        var url = base_url_js + "admission/config/submit_upload_announcement";
	        form_data.append("fileData", fileData);
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
	              $('#btn-proses').prop('disabled',false).html('Proses');
	            },1000);

	          },
	          error: function (data) {
	            toastr.error(data.msg, 'Connection error, please try again!!');
	          }
	        })
		}
	     
	});
</script>