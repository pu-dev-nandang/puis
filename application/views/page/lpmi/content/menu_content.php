<style type="text/css">
.nav-tabs > li > a {
    background-color: #ececec;
}
</style>
<?php $Segment1 = $this->uri->segment(2); ?>
<?php $Segment2 = $this->uri->segment(3); ?>
<div class="col-xs-12">
	<div class="well" style="padding-bottom: 30px">
        <div class="panel-heading clearfix">
            <h3 >
            	Build Content <?= ucwords(str_replace("-"," ",$Segment1)); ?>
            </h3>
            <p id="idDisplay"></p>
        </div>
        <div class="panel-body" id="tabs">
        	<button class="btn btn-success" onclick="add_lpmi()"><i class="glyphicon glyphicon-plus"></i> Create</button>
        	<button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        	<hr>
        	
						
			<div class="table-responsive">
			  <table class="table table-condensed table-striped" id="table">
			      <thead>
			        <tr>
			          <!-- <th>#</th> -->
			          <th><?php if ($Segment1 =="testimonials"){echo "Name";}else{echo "Title";}?></th>
			          <!-- <th>Text</th> -->
			          <th>Published</th>
			          <th>Date Update</th>
			          <th>Lang</th>
			          <th>Action</th>
			        </tr>
			      </thead>
			      <tbody id="viewDatalist">
			        
			      </tbody>
			    </table>
			</div>
		

		</div>
	</div>
</div>
<style type="text/css">.form-horizontal .form-group {
    padding: 5px 15px;
}</style>
<!-- MODAL ADD -->
	<div class="modal fade " id="modal_form" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
	    <div class="modal-dialog modal-lg" style="width: 80%">
	    <div class="modal-content">
	    <div class="modal-header" style="padding: 20px">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	        <h3 class="modal-title" id="myModalLabel">Create Content <?= ucwords(str_replace("-"," ",$Segment1)); ?></h3>
	    </div>
	    
	    <div class="modal-body form">
	    	<form action="#" id="form" class="form-horizontal">
	            			<input type="hidden" value="" name="id"/>
	            			<input type="hidden" value="<?= $Segment1 ?>" name="type"/>
	        <div class="row">
					<div class="col-md-8" >
	                <div class="thumbnail" style="padding: 15px"> 
						<div class="form-group">
	                        <label><?php if ($Segment1 =="testimonials"){echo "Name";}else{echo "Title";}?></label>                        
	                        <input name="title" placeholder="<?php if ($Segment1 =="testimonials"){echo "Name";}else{echo "Title";}?>" class="form-control" type="text">
	                        <span class="help-block"></span>
	                	</div>
	                	 <div class="container" id="show_a1" hidden="true">
						  <div class="row">
						    <div class='col-sm-6' style="padding: 0px">
						      <div class="form-group">
						      	<label>Date</label>
						        <div class="input-group input-append date datetimepicker">
						        	<input data-format="yyyy-MM-dd h:m" class="form-control input_modal_assign_to" type="text" name="date" readonly="" value="<?php echo date('Y-m-d h:m') ?>">
						        	<span class="input-group-addon add-on">
						        		<i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i>
						        	</span>
						        </div>
						      </div>
						    </div>
						  </div>
						</div>
	                	 <div class="form-group">
						    <label for="Description">Text</label>
						    <textarea id="Description" name="description" placeholder="Description" class="form-control"></textarea>
						    <span class="help-block"></span>
					  	</div>
					  	
					  	<div class="form-group" id="show_a2" hidden="true">
	                        <label>Meta description</label>
	                        <small class="red">Max 160 characters</small>
	                        <textarea name="meta_des" placeholder="Meta Description" class="form-control" style="border-color: #d9d9d9;"></textarea>
	                    </div>
	                    <div class="form-group" id="show_a3" hidden="true">
	                        <label>Meta Keywords</label>
	                        <small class="red">No more than 10 keyword</small>
	                        <textarea name="meta_key" placeholder="Meta Keywords" class="form-control" style="border-color: #d9d9d9;"></textarea>
	                    </div>
	                    <div class="form-group" id="photo-preview">
                            <label>File</label>
                            <div>
                                (No file)
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group" id="show_a4" hidden="true">
                            <label  id="label-photo">Upload file </label>
                            <div>
                                <input name="photo" type="file">
                                <p>Max size 2 MB (type file jpg, jpeg, png and pdf)</p>
                                <span class="help-block"></span>
                            </div>
                        </div>
	                </div>    
	            </div> 
	            <div class="col-md-4">
	            	<div class="thumbnail" style="padding: 15px; margin-bottom: 15px"> 	            		
	            		
	                    <div class="form-group" style="margin-bottom: 0px">
	                        <h3>Language</h3>
	                        <select  name="lang" class="form-control">
	                          <option value="">--Select--</option>
							  <option value="Eng">English</option>
							  <option value="Ind">Indonesia</option>							  
							</select>
							<span class="help-block"></span>
	                    </div>
	                    <div class="form-group">
	                        <h3>Published</h3>                        
							
								<input type="radio" value="Yes" name="status" id="st1"> Yes
							
								<input type="radio" value="No" name="status" id="st2"> No
							
							<span class="help-block"></span>
	                    </div>
	            	</div>
	            	<div class="thumbnail" style="padding: 15px"> 	            		
	            		
	                    <div class="form-group" style="margin-bottom: 0px">
	                        <h3>Setting Build</h3>	                                             
							<hr>

								<div><input type="checkbox" id="ad1"> <label id="setingedit0">Add</label> Date
								</div>
								<div><input type="checkbox" id="ad2"> <label id="setingedit">Add</label> Meta Descripton
								</div>
								<div><input type="checkbox" id="ad3"> <label id="setingedit1">Add</label> Meta Keywords
								</div>
								<div><input type="checkbox" id="ad4"> <label id="setingedit2">Add</label> Upload
								</div>										
							<span class="help-block"></span>
	                    </div>
	            	</div>
	            </div>               
	      	</div>
		</div>	

	    <div class="modal-footer">
	       	<button type="button" id="btnSave" onclick="save()" class="btn btn-info">Save</button>
	        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
	    </div>


	    </div>
	</div>
<!--END MODAL ADD-->
<script>

	$( function() {
		$('.datetimepicker').datetimepicker();
	} );

</script>

<script>

    $(document).ready(function () {
    	// show setting build
    	$('#ad1').change(function() {
		  $("#show_a1").prop("hidden", !this.checked);
		});
    	$('#ad2').change(function() {
		  $("#show_a2").prop("hidden", !this.checked);
		});
		$('#ad3').change(function() {
		  $("#show_a3").prop("hidden", !this.checked);
		})
		$('#ad4').change(function() {
			$("#show_a4").prop("hidden", !this.checked);

		})
        $('#Description').summernote({
            placeholder: 'Text your announcement',
            tabsize: 2,
            height: 300,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ],
            callbacks: {
                  onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
                    e.preventDefault();
                    var div = $('<div />');
                    div.append(bufferText);
                    div.find('*').removeAttr('style');
                    setTimeout(function () {
                      document.execCommand('insertHtml', false, div.html());
                    }, 10);
                  }
                }
        });

    }); 

	var save_method; //for save method string
	var table;
	 
	$(document).ready(function() {
	 
	    //datatables
	    table = $('#table').DataTable({ 
	 
	        "processing": true, //Feature control the processing indicator.
	        "serverSide": true, //Feature control DataTables' server-side processing mode.
	        "ordering": true, // Set true agar bisa di sorting
            	"order": [[ 0, 'asc' ]], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
	 
	        // Load data for the table's content from an Ajax source
	        "ajax": {	        	
	            "url": base_url_js+'__ajaxlist_lpmi',
	            "type": "POST",
	            "data": function ( data ) {
		                data.type = '<?= $Segment1 ?>';		                
		            }
	        },	 
	        "deferRender": true,
            	"aLengthMenu": [[5, 10, 50],[ 5, 10, 50]], // Combobox Limit
	        // 	Tambahkan bagian ini:
			// "columns": [
			// 	{data: 'Title'},
			// 	{data: 'Published'},
			// 	{data: 'UpdatedAT'},
			// 	{data: 'Lang'},
			// 	{data: 'Action'},

			// ],
	
	        //Set column definition initialisation properties.
	        "columnDefs": [
			        { 
			        	"searchable": false,
			            "targets": [ 0 ], //first column / numbering column
			            "orderable": false, //set not orderable
			        },
	   
	        ],
	 
	    });

 		//set input/textarea/select event when change value, remove class error and remove text help block 
	    $("input").change(function(){
	        $(this).parent().parent().removeClass('has-error');
	        $(this).next().empty();
	    });
	    $("textarea").change(function(){
	        $(this).parent().parent().removeClass('has-error');
	        $(this).next().empty();
	    });
	    $("select").change(function(){
	        $(this).parent().parent().removeClass('has-error');
	        $(this).next().empty();
	    });
	 
	});

	function add_lpmi()
	{
	    save_method = 'add';
	    $('#form')[0].reset(); // reset form on modals
	    $('.form-group').removeClass('has-error'); // clear error class
	    $('.help-block').empty(); // clear error string
	    $('#modal_form').modal('show'); // show bootstrap modal
	    $('.modal-title').text('Build Content'); // Set Title to Bootstrap modal title
	    $('#photo-preview').hide(); // hide photo preview modal
	    $('#Description').summernote('code', '');
	}
	 
	function edit_lpmi(id)
	{
	    save_method = 'update';
	    $('#form')[0].reset(); // reset form on modals
	    $('.form-group').removeClass('has-error'); // clear error class
	    $('.help-block').empty(); // clear error string
	    $('#setingedit0').text('Edit');  //change name checkbox
	 	$('#setingedit').text('Edit');  //change name checkbox
	 	$('#setingedit1').text('Edit');  //change name checkbox
	 	$('#setingedit2').text('Edit');  //change name checkbox
	    //Ajax Load data from ajax
	    $.ajax({
	        url : base_url_js+'__ajaxedit_lpmi/'+id,
	        type: "GET",
	        dataType: "JSON",
	        success: function(data)
	        {
	 
	            $('[name="id"]').val(data.ID);
	            $('[name="title"]').val(data.Title);
	            // $('[name="description"]').val(data.Description);
	            $('#Description').summernote('code', data.Description);
	            $('[name="meta_des"]').val(data.Meta_des);
	            $('[name="meta_key"]').val(data.Meta_key);
	            // Language
	            if (data.Status=="Yes") {
                	document.getElementById("st1").checked = true;
	            } else {
	                document.getElementById("st2").checked = true;
	            }

	            // if (data.File!=="" ) {
             //    	// document.getElementById("ad4").checked = true;                	
					
	            // } else {
	            //     $('#photo-preview').hide();

	            // }
	            // $('[name="status"]').val(data.Status);
	            $('[name="lang"]').val(data.Lang);
	            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
	            $('.modal-title').text('Update Content'); // Set title to Bootstrap modal title
	 				
	 			// $('#photo-preview').show(); // show photo preview modal
	 			// console.log(data.File);
	 			

	 			if(data.File)
	            {
	            	
			        var fileName = data.File;
			        var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
			        console.log(fileNameExt);
	            	if(!fileNameExt=='pdf'){
		                $('#label-photo').text('Change file'); // label photo upload
		                $('#photo-preview div').html('<img src="'+base_url_js+'uploads/lpmi/'+data.File+'" class="img-responsive">'); // show photo
		                // $('#photo-preview div').append('<input type="checkbox" name="remove_photo" value="'+data.file+'"/> Remove photo when saving'); // remove photo
	                }else{
	                	$('#photo-preview div').html('<iframe src="'+base_url_js+'uploads/lpmi/'+data.File+'" height="100%" width="100%" scrolling="auto"></iframe>');
	                }
	
	            }
	            // else
	            // {
	            //     $('#label-photo').text('Upload Photo1'); // label photo upload
	            //     $('#photo-preview div').text('(No photo)');
	            // }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	            alert('Error get data from ajax');
	        }
	    });
	}
	 
	function reload_table()
	{
	    table.ajax.reload(null,false); //reload datatable ajax 
	}
	 
	function save()
	{
	    $('#btnSave').text('saving...'); //change button text
	    $('#btnSave').attr('disabled',true); //set button disable 
	    var url;
	 
	    if(save_method == 'add') {
	        url = base_url_js+'__ajaxadd_lpmi';
	    } else {
	        url = base_url_js+'__ajaxupdate_lpmi';
	    }
	 
	    // ajax adding data to database
	    var formData = new FormData($('#form')[0]);
	    $.ajax({
	        url : url,
	        type: "POST",
	        data: formData,
	        contentType: false,
	        processData: false,
	        dataType: "JSON",
	        success: function(data)
	        {
	 
	            if(data.status) //if success close modal and reload ajax table
	            {
	            	toastr.success('Data saved','Success');
	                $('#modal_form').modal('hide');
	                reload_table();
	            }
	            else
	            {
	                for (var i = 0; i < data.inputerror.length; i++) 
	                {
	                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
	                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
	                }
	            }
	 
	            $('#btnSave').text('Save'); //change button text
	            $('#btnSave').attr('disabled',false); //set button enable 
	 
	 
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	            alert('Error adding / update data');
	            $('#btnSave').text('Save'); //change button text
	            $('#btnSave').attr('disabled',false); //set button enable 
	 
	        }
	    });
	}
	 
	function delete_lpmi(id)
	{
	    if(confirm('Are you sure delete this data?'))
	    {
	        // ajax delete data to database
	        $.ajax({
	            url : base_url_js+'__ajaxdelete_lpmi/'+id,
	            type: "POST",
	            dataType: "JSON",
	            success: function(data)
	            {
	                //if success reload ajax table
	                $('#modal_form').modal('hide');
	                reload_table();
	            },
	            error: function (jqXHR, textStatus, errorThrown)
	            {
	                alert('Error deleting data');
	            }
	        });
	 
	    }
	}


</script>
