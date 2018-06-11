<div class="row" style="margin-top: 30px;">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>File Announcement</h4>
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
								<button class="btn btn-inverse btn-notification hide" id="btn-proses">Proses</button>
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
	    var name = document.getElementById(ID_element).files[0].name;
	    var ext = name.split('.').pop().toLowerCase();
	    if(jQuery.inArray(ext, ['pdf']) == -1) 
	    {
	      toastr.error("Invalid Image File", 'Failed!!');
	      return false;
	    }
	    var oFReader = new FileReader();
	    oFReader.readAsDataURL(document.getElementById(ID_element).files[0]);
	    var f = document.getElementById(ID_element).files[0];
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
		loading_button('#btn-proses');
		var ID_element = $("#fileAnnouncement");
		if (file_validation(ID_element)) {
		  SaveFile(ID_element,ID_document,attachName);
		}
	     
	});
</script>