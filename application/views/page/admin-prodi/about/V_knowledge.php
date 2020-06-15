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
                Document <?= ucwords(str_replace("-"," ",$Segment2)); ?>
            </h3>
            <p id="idDisplay"></p>
        </div>
        <div class="panel-body" id="tabs">
            <button class="btn btn-success" onclick="add_prodi()"><i class="glyphicon glyphicon-plus"></i> Create</button>
            <?php if($Segment2=='knowledge'){echo '<button class="btn btn-success" onclick="Catprodi()"><i class="glyphicon glyphicon-plus"></i> Add Category</button>';} ?>
            <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
            <hr>            
                        
            <div class="table-responsive">
              <table class="table table-condensed table-striped" id="table">
                  <thead>
                    <tr>
                      <th width="3%">#</th>
                      <th width="20%">Title</th>
                      <!-- <th>Text</th> -->
                      <!-- <th>File</th> -->
                      <th width="10%">Lang</th>
                      <th width="10%">Date Update</th>
                      
                      <th width="10%">Action</th>
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
<!-----Modal Category--->
<div class="modal fade " id="modal_formCat" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
    <div class="modal-dialog modal-md" style="width: 50%">
        <div class="modal-content">
        <div class="modal-header" style="padding: 20px 35px">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 class="modal-title" id="myModalLabel">Upload Document <?= ucwords(str_replace("-"," ",$Segment2)); ?></h3>
        </div>
        
        <div class="modal-body form">
            <form class="getcategory" id="formcat" style="margin: 0 15px;;">                    
                <div class="form-group">  
                    <label for=""><label id="cng">Add</label> Category:</label>  
                        <input type="hidden" value="" name="idcat"/>
                        <input id="namecategory" name="category" class="form-control" type="text" placeholder="Input Category Here" value="">  
                    
                </div> 
                <div class="form-group">
                    <div class="btn btn-success" id="btncategory" onclick="add_category()">Save</div>
                </div>
            </form> 
            <div class="panel-body" style="min-height: 100px;">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <td style="width: 8%">No</td>
                                <td>Category</td>
                                <td>Create by</td>
                                <td>Create at</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody id="viewDatalistCategory">                           
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-info">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div> -->

        </div>
        
        </div>
    </div>
</div>
<!-- MODAL ADD -->
    <div class="modal fade " id="modal_form" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
        <div class="modal-dialog modal-md" style="width: 50%">
        <div class="modal-content">
        <div class="modal-header" style="padding: 20px">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 class="modal-title" id="myModalLabel">Document <?= ucwords(str_replace("-"," ",$Segment2)); ?></h3>
        </div>
        
        <div class="modal-body form">
            <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="" name="id"/>
                            <input type="hidden" value="<?= $Segment2 ?>" name="type"/>
            <div class="row">
                    <div class="col-md-12" >
                    <div class="thumbnail" style="padding: 15px"> 
                        <div class="form-group">
                            <label><?php if ($Segment2 =="knowledge"){echo "Name";}else{echo "Title";}?></label>                        
                            <input name="title" placeholder="<?php if ($Segment2 =="knowledge"){echo "Name";}else{echo "Title";}?>" class="form-control" type="text">
                            <span class="help-block"></span>
                        </div>
                         <!-- <div class="container" id="show_a1" hidden="true">
                          <div class="row">
                            <div class='col-sm-6' style="padding: 0px">
                              <div class="form-group">
                                <label>Date</label>
                                <div class="input-group input-append date datetimepicker">
                                    <input data-format="yyyy-MM-dd hh:mm" class="form-control input_modal_assign_to" type="text" name="date" readonly="" value="">
                                    <span class="input-group-addon add-on">
                                        <i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i>
                                    </span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div> -->
                         <!-- <div class="form-group">
                            <label for="Description">Text</label>
                            <textarea id="Description" name="description" placeholder="Description" class="form-control"></textarea>
                            <span class="help-block"></span>
                        </div> -->
                        
                        <!-- <div class="form-group" id="show_a2" hidden="true">
                            <label>Meta description</label>
                            <small class="red">Max 160 characters</small>
                            <textarea name="meta_des" placeholder="Meta Description" class="form-control" style="border-color: #d9d9d9;"></textarea>
                        </div>
                        <div class="form-group" id="show_a3" hidden="true">
                            <label>Meta Keywords</label>
                            <small class="red">No more than 10 keyword</small>
                            <textarea name="meta_key" placeholder="Meta Keywords" class="form-control" style="border-color: #d9d9d9;"></textarea>
                        </div> -->
                        <div class="form-group" style="margin-bottom: 0px">
                            <label>Select Category</label>
                            <select  name="category" class="form-control">
                              <option value="">--Select--</option>
                                <?php foreach($category as $row):?>
                                <option value="<?php echo $row->ID;?>" ><?php echo $row->Name;?></option>
                                <?php endforeach;?>                           
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group" style="margin-bottom: 0px">
                            <h3>Language</h3>
                            <select  name="lang" class="form-control">
                              <option value="">--Select--</option>
                              <option value="1">English</option>
                              <option value="2">Indonesia</option>                              
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group" id="photo-preview">
                            <label>File</label>
                            <div>
                                (No file)
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label  id="label-photo">Upload file </label>
                            <div>
                                <input name="photo" type="file">
                                <small>*Max size 2 MB (type file jpg, jpeg, png and pdf)</small>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>    
                </div> 
                <!-- <div class="col-md-4">
                    <div id="show_a5" class="thumbnail" style="padding: 15px ;margin-bottom:15px" > 
                        <div class="form-group" style="margin-bottom: 0px">
                            <h3>Select Category</h3>
                            <select  name="category" class="form-control">
                              <option value="">--Select--</option>
                                <?php foreach($category as $row):?>
                                <option value="<?php echo $row->ID;?>" ><?php echo $row->Name;?></option>
                                <?php endforeach;?>                           
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
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
                        
                        <div class="form-group" style="margin-bottom: 15px">
                            <h3>Setting Build</h3>                                               
                            <hr>

                                <div><input type="checkbox" id="ad1" <?php if ($Segment1 =="vision" || $Segment1 =="mission" || $Segment1 =="slider" ||$Segment1 =="target" || $Segment1 =="program" || $Segment1 =="news" || $Segment1 =="knowledge" || $Segment1 =="testimonials" || $Segment1 =="partner"){echo "disabled";}?> onchange="valueChanged1()"> <label id="setingedit0">Add</label> Date
                                </div>
                                <div><input type="checkbox" id="ad2" onchange="valueChanged2()"> <label id="setingedit">Add</label> Meta Descripton
                                </div>
                                <div><input type="checkbox" id="ad3" onchange="valueChanged3()"> <label id="setingedit1">Add</label> Meta Keywords
                                </div>
                                <div><input type="checkbox" id="ad4" <?php if ($Segment1 =="vision" || $Segment1 =="mission" ||$Segment1 =="target" || $Segment1 =="program" || $Segment1 =="event" ){echo "disabled";}?> onchange="valueChanged4()"> <label id="setingedit2">Add</label> Upload
                                </div>
                                <div><input type="checkbox" id="ad5" <?php if ($Segment1 =="vision" || $Segment1 =="mission" || $Segment1 =="slider" ||$Segment1 =="target" || $Segment1 =="program" || $Segment1 =="news" || $Segment1 =="event" || $Segment1 =="testimonials" || $Segment1 =="partner"){echo "disabled";}?> onchange="valueChanged5()"> <label id="setingedit3">Add</label> Category
                                </div>                                      
                            <span class="help-block"></span>
                        </div>
                    </div>             

                    </form>
                                          
                    
                </div>  -->              
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
        
        

        // $('#ad5').change(function() {
        //  $("#show_a5").show();
        //  document.getElementById('show_a5')[ ($("#ad5==''").is(":checked")? "show" : "hide" ]();;
        // });
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

     // show setting build
    $("#show_a1").hide();
    $("#show_a2").hide();   
    $("#show_a3").hide();
    $("#show_a4").hide();
    $("#show_a5").hide();
    function valueChanged1()
    {
        if($('#ad1').is(":checked"))   
            $("#show_a1").show();
        else
            $("#show_a1").hide();
    }
    function valueChanged2()
    {
        if($('#ad2').is(":checked"))   
            $("#show_a2").show();
        else
            $("#show_a2").hide();
    }
    function valueChanged3()
    {
        if($('#ad3').is(":checked"))   
            $("#show_a3").show();
        else
            $("#show_a3").hide();
    }
    function valueChanged4()
    {
        if($('#ad4').is(":checked"))   
            $("#show_a4").show();
        else
            $("#show_a4").hide();
    }
    function valueChanged5()
    {
        if($('#ad5').is(":checked"))   
            $("#show_a5").show();
        else
            $("#show_a5").hide();
    }
   
  //    $('#ad1').change(function() {
        //   $("#show_a1").hide();
        // });
  //    $('#ad2').change(function() {
        //   $("#show_a2").prop("hidden", !this.checked);
        // });
        // $('#ad3').change(function() {
        //   $("#show_a3").prop("hidden", !this.checked);
        // });
        // $('#ad4').change(function() {
        //  $("#show_a4").prop("hidden", !this.checked);
        // });

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
                "url": base_url_js+'prodi/load_data',
                "type": "POST",
                "data": function ( data ) {
                        data.type = '<?= $Segment2 ?>';                     
                    }
            },   
            "deferRender": true,
            "aLengthMenu": [[5, 10, 50],[ 5, 10, 50]], // Combobox Limit
            //  Tambahkan bagian ini:
            // "columns": [
            //  {data: 'Title'},
            //  {data: 'Published'},
            //  {data: 'UpdatedAT'},
            //  {data: 'Lang'},
            //  {data: 'Action'},

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
     

    function add_category()
    {
        $('#btncategory').text('Saving...'); //change button text
        $('#btncategory').attr('disabled',true); //set button disable 
        var url;
     
        if(save_method == 'add') {
            url = base_url_js+'__ajaxaddCat_prodi';
        } else {
            url = base_url_js+'__ajaxupdateCat_Prodi';
        }
     
        // ajax adding data to database
        var category = new FormData($('#formcat')[0]);
        // console.log(category);
        $.ajax({
            url : url,
            type: "POST",
            data: category,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function(data)
            {
     
                if(data.status) //if success close modal and reload ajax table
                {
                    toastr.success('Data saved','Success');
                    // $('#modal_form').modal('hide');
                    // reload_table();
                    loadDatacate();
                    $('#namecategory').val('');
                    $('#btncategory').text('Save'); //change button text
                    $('#btncategory').attr('disabled',false); //set button enable 
                    $('#cng').text('Add')
                }
                
                           
                
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#btncategory').text('Save'); //change button text
                $('#btncategory').attr('disabled',false); //set button enable 
     
            }
        });
        return false;
    }


    function loadDatacate() {
        
        $.ajax({
              type  : 'GET',
              url   : base_url_js+'__ajaxCat_prodi',
              async : false,
              dataType : 'json',
              success : function(data){

                  var html = '';
                  var i;
                  for (i = 0; i < data.length; i++) {
                      html += '<tr>'+
                                '<td>'+(i+1)+'</td>'+
                                '<td>'+data[i].Name+'</td>'+
                                '<td>'+data[i].CreateAt+'</td>'+
                                '<td>'+data[i].CreateBy+'</td>'+
                                '<td>'+
                                  '<a id="tab1-20933" class="btn btn-success" onclick="editCat_prodi('+data[i].ID+')" href="javascript:;" data="'+data[i].ID+'"> Edit</a>'+
                                  '<a id="tab1-20933" class="btn btn-danger" onclick="deleteCat_prodi('+data[i].ID+')" href="javascript:;" data="'+data[i].ID+'"> Delete</a>'+
                                '</td>'+
                              '</tr>';
                              // console.log(data[i].Name);
                  }
                  
                  $('#viewDatalistCategory').html(html);
              }
          })
    }

    function Catprodi()
    {
        loadDatacate(); 
        save_method = 'add';
        $('#formcat')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_formCat').modal('show'); // show bootstrap modal
        $('.modal-title').text('Build Category'); // Set Title to Bootstrap modal title
    }


    function editCat_prodi(id)
    {
        save_method = 'update';
        $.ajax({
            url : base_url_js+'__ajaxeditCat_Prodi/'+id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                $('[name="idcat"]').val(data.ID);
                $('[name="category"]').val(data.Name);  
                $('[name="category"]').focus();   
                $('#cng').text('Edit')                      

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function deleteCat_prodi(id)
    {
        if(confirm('Are you sure delete this data?'))
        {
            // ajax delete data to database
            $.ajax({
                url : base_url_js+'__ajaxdeleteCat_Prodi/'+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    //if success reload ajax table
                    toastr.success('Data delete','Success');
                    $('#modal_form').modal('hide');
                    loadDatacate();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
     
        }
    }



    function add_prodi()
    {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Knowledge Base'); // Set Title to Bootstrap modal title
        $('#photo-preview').hide(); // hide photo preview modal
        $('#Description').summernote('code', '');
        $('#show_a1').hide();
        $('#show_a2').hide();
        $('#show_a3').hide();
        $('#show_a4').hide();
        $('#show_a5').hide();

        $('#setingedit0').text('Add');  //change name checkbox
        $('#setingedit').text('Add');  //change name checkbox
        $('#setingedit1').text('Add');  //change name checkbox
        $('#setingedit2').text('Add');  //change name checkbox
        $('#setingedit3').text('Add');  //change name checkbox
    }
     
    function edit_prodi(id)
    {
        // alert('ok');
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#setingedit0').text('Edit');  //change name checkbox
        $('#setingedit').text('Edit');  //change name checkbox
        $('#setingedit1').text('Edit');  //change name checkbox
        $('#setingedit2').text('Edit');  //change name checkbox
        $('#setingedit3').text('Edit');  //change name checkbox
        //Ajax Load data from ajax
        $('#modal_form').modal('show');
        $.ajax({
            url : base_url_js+'__ajaxedit_prodi/'+id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
     
                $('[name="id"]').val(data.ID);
                $('[name="title"]').val(data.Title);
                // $('[name="description"]').val(data.Description);
                // $('#Description').summernote('code', data.Description);
                // $('[name="meta_des"]').val(data.Meta_des);
                // $('[name="meta_key"]').val(data.Meta_key);
                $('[name="lang"]').val(data.LangID);
                $('[name="category"]').val(data.ID_CatBase);
                // Language
                // if (data.Status=="Yes") {
                //     document.getElementById("st1").checked = true;
                // } else {
                //     document.getElementById("st2").checked = true;
                // }
                // console.log(data.AddDate);
                // if (data.AddDate=='' || data.AddDate=='0000-00-00 00:00:00'){
                //     document.getElementById("ad1").checked = false;
                //     $("#show_a1").hide();
                //     // $("#show_a1").prop("show", this.checked);
                // }else{
                //     document.getElementById("ad1").checked = true;
                //     $("#show_a1").show()
                // }
                // console.log(data.IDCat);
                // if (data.ID_CatBase ){
                //     document.getElementById("ad5").checked = true;
                //     $('#show_a5').show();
                //     // $("#show_a1").prop("show", this.checked);
                //     // console.log('no');
                // }else{
                //     document.getElementById("ad5").checked = false;
                //     // document.getElementById("show_a5").hidden = false;
                //     $('#show_a5').hide();
                //     // console.log('ok');
                // }
                // if (data.File!=="" ) {
             //     // document.getElementById("ad4").checked = true;                   
                    
                // } else {
                //     $('#photo-preview').hide();

                // }
                // $('[name="status"]').val(data.Status);
                // $('[name="lang"]').val(data.Lang);
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
                        $('#photo-preview div').html('<img src="'+base_url_js+'uploads/prodi/'+data.File+'" class="img-responsive">'); // show photo
                        // $('#photo-preview div').append('<input type="checkbox" name="remove_photo" value="'+data.file+'"/> Remove photo when saving'); // remove photo
                    }else{
                        $('#photo-preview div').html('<iframe src="'+base_url_js+'uploads/prodi/'+data.File+'" height="100%" width="100%" scrolling="auto"></iframe>');
                    }
    
                }
                else
                {
                    // $('#label-photo').text('Upload Photo1'); // label photo upload
                    // $('#photo-preview div').text('(No photo)');
                    $('#photo-preview').hide();
                    // document.getElementById("photo-preview").hidden = false;
                }
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
            url = base_url_js+'__ajaxadd_prodi';
        } else {
            url = base_url_js+'__ajaxupdate_prodi';
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
                // $('#modal_form').modal('hidden');   
                // edit_prodi(id);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#btnSave').text('Save'); //change button text
                $('#btnSave').attr('disabled',false); //set button enable 
     
            }
        });
    }
     
    function delete_prodi(id)
    {
        if(confirm('Are you sure delete this data?'))
        {
            // ajax delete data to database
            $.ajax({
                url : base_url_js+'__ajaxdelete_prodi/'+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    //if success reload ajax table
                    toastr.success('Data delete','Success');
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
