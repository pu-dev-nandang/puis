
<style>

    #viewkb .item-head:hover{
        background: #f5f5f5;
    }
    #viewkb .numbering {
        width: 30px;
        height: 30px;
        border: 1px solid #3F51B5;
        border-radius: 15px;
        text-align: center;
        padding-top: 5px;
        display: inline-block;
        margin-right: 10px;
        font-size: 11px;
        font-weight: bold;
    }
    #viewkb .info {
        color: orangered;
        font-size: 15px;
    }
    #viewkb .detailKB {
        margin-top: 15px;
    }

    #viewkb .detailKB ul.list-group .list-group-item {
        border-radius: 15px !important;
    }

    #viewkb a {
        text-decoration: none !important;
        display: block;
    }

</style>
<div class="row">
    <div class="col-md-3 panel-admin" style="border-right: 1px solid #CCCCCC;">
<?php if (true){ ?>
     <div class="panel panel-default">
        <div class="panel-heading"></div>
        <div class="panel-body">
          <div class="form-group hide">
      			<label>Division</label>
            <input type="text" id="formKB_IDDivision" value="<?= $this->session->userdata('PositionMain')['IDDivision']; ?>">
      			<!-- <select class="select2-select-00 full-width-fix" id="formKB_IDDivision">
      				<?php for($i = 0; $i < count($G_division); $i++): ?>
      					<option value="<?php echo $G_division[$i]['ID'] ?>" > <?php echo $G_division[$i]['Division'] ?> </option>
      				<?php endfor ?>
      			 </select> -->
      		</div>
          <div class="form-group">
              <label>Type</label>
              <select class="form-control" id="formKBID"></select>
              <a style="float: right;" href="javascript:void(0);" class="" id="btnCrud_KB"><i class="fa fa-edit margin-right"></i> Type</a>
          </div>

        <div class="form-group">
            <label>Desc</label>
            <input class="form-control" id="formKB_Desc"/>
        </div>
        <div class="form-group">
            <label>File</b></label>
          <form id="formupload_files" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
          <input type="file" name="userfile" id="upload_files" accept="">
          <div>PDF, Max Size 8MB</div>
            </form>
          </div>
          <div class="form-group" style="text-align: right;">
              <button class="btn btn-primary" id="saveFormKB">Save</button>
          </div>
        </div>

      </div>
    </div>
    <div class="col-md-9">
      <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="well">
              <div class="form-group">
                <label>Division</label>
                <select class="select2-select-00 full-width-fix" id="Division">
                  <?php for($i = 0; $i < count($G_division); $i++): ?>
                    <option value="<?php echo $G_division[$i]['ID'] ?>" > <?php echo $G_division[$i]['Division'] ?> </option>
                  <?php endfor ?>
                 </select>
              </div>
            </div>
        </div>
      </div>
      <div class="row">
        <div id="" class="col-md-12">
              <ul class="list-group" id="headerlist">
                <?php for($i = 0; $i < count($G_data); $i++): ?>
                  <?php $no = $i+1 ?>
                    <li class="list-group-item item-head">
                                      <a href="javascript:void(0)" data-toggle="collapse" data-target="#<?php echo $i ?>">
                                          <span class="numbering"><b><?php echo $no; ?></b></span>
                                          <span class="info"><b><?php echo $G_data[$i]['Type']?></b></span>
                                      </a>

                      <div id="<?php echo $i ?>" class="collapse detailKB">
                        <ul class="list-group">
                          <?php $data = $G_data[$i]['data'] ?>
                          <?php for($j = 0; $j < count($data); $j++): ?>
                            <li class="list-group-item"><a href="javascript:void(0)" data-toggle="collapse" data-target="#<?php echo $i.'__'.$j ?>">
                                              <b><?php echo $data[$j]['Desc'] ?></b>
                                          </a>
                              <div id="<?php echo $i.'__'.$j ?>" class="collapse">
                                <div style="margin-top: 15px;margin-bottom: 15px;">
                                  <a class="btn btn-default <?php if($data[$j]['=File']==''||$data[$j]['File']==null || $data[$j]['File']=='unavailabe.jpg'){echo 'hide';} ?>" style="display: inline;" href="<?php echo serverRoot.'/fileGetAny/kb'.$data[$j]['File'] ?>" target="_blank"><i class="fa fa-download margin-right"></i> File</a>
                                    <!-- <button type="button" class="btn btn-default btn-sm btn-default-success btnEdit">Edit</button>
                                    <button type="button" class="btn btn-default btn-sm btn-default-danger btnDelete">Delete</button> -->
                                </div>
                              </div>
                            </li>
                          <?php endfor ?>
                        </ul>
                      </div>
                    </li>
                  <?php endfor ?>
              </ul>
        </div>
      </div>
    </div>
  </div>
<?php } ?>


<script type="text/javascript">
	$(document).ready(function () {
		$("#Division option").filter(function() {
		   //may want to use $.trim in here
		   return $(this).val() == "<?php echo $selected ?>";
		 }).prop("selected", true);
		$('#Division').select2({

		});

    loadTypeKB() ;
	});

//save_formKB
$('#saveFormKB').click(function () {

    var formKBID = $('#formKBID').val();
    //var formKB_IDDivision = $('#formKB_IDDivision').val();
    var formKB_Type = $('#formKB_Type').val();
    var formKB_Desc = $('#formKB_Desc').val();
    var upload_files = $('#upload_files').val();


    var input = $('#upload_files');
    var files = input[0].files[0];

    var sz = parseFloat(files.size) / 8000000; // ukuran MB
    var ext = files.type.split('/')[1];
    var uploadFile = (Math.floor(sz)<=2) ? true : false ;



    // ($('#bpp_start').datepicker("getDate")!=null) ? moment($('#bpp_start').datepicker("getDate")).format('YYYY-MM-DD') : '',

    if(
    //formKB_IDDivision!='' && formKB_IDDivision!=null &&
    formKBID!='' && formKBID!=null &&
    formKB_Desc!='' && formKB_Desc!=null
    && uploadFile == true

  ){


      loading_button('#saveFormKB');

      var url = base_url_js+'api3/__crudkb';
      var data = {
          action: 'updateNewKB',
          ID : '', // ID knowledge_base
          dataForm : {
              IDType : formKBID, // menampung id kb type
              Desc : formKB_Desc
          }
      };
      var token = jwt_encode(data,'UAP)(*');


      $.post(url,{token:token},function (jsonResult) {

          toastr.success('Data saved','Success');

          if (upload_files!=null && upload_files!=''){
            upload_kb(jsonResult.ID,'');
          }


          setTimeout(function () {
          $('#saveFormKB').html('Save').prop('disabled',false);
          $('#formKBID').val('');
          $('#formKB_Type').val('');
          $('#formKB_Desc').val('');
          $('#formKB_File').val('');

          }, 500);

        });




        } else {

          if(uploadFile==false){
            toastr.error('Max Size 8 Mb','error');
          } else {
          toastr.error('Form Required','error');
          }


        }

      });

	$(document).on('change','#Division', function () {
	   var url = base_url_js+"kb";
	   var data = {
	   	Division : $(this).val(),
	   };
	   $.post(url,data,function (resultJson) {
	   	$(".list-group").empty();
	   	$(".list-group").html('<div id = "pageloading"></div>');
	   	loading_page('#pageloading');
	   	setTimeout(function () {
	   		$(".list-group").html(resultJson);
	   	},1000);
	   })

	});



  $('#btnCrud_KB').click(function () {

      var bodyModal = '<div class="well row">' +
          '    <div class="col-md-8">' +
          '        <input class="hide" id="formKB_ID">' +
          '        <input class="form-control" id="formKB_Type">' +
          '    </div>' +
          '    <div class="col-md-4">' +
          '        <button class="btn btn-block btn-success" id="btnKBSave">Save</button>' +
          '    </div>' +
          '</div>' +
          '<div class="row">' +
          '    <div class="col-md-12">' +
          '        <hr/>' +
          '        <table class="table table-striped">' +
          '            <thead>' +
          '            <tr>' +
          '                <th style="width: 1%;">No</th>' +
          '                <th>Type</th>' +
          '                <th style="width: 15%;"><i class="fa fa-cog"></i></th>' +
          '            </tr>' +
          '            </thead>' +
          '            <tbody id="listData"></tbody>' +
          '        </table>' +
          '    </div>' +
          '</div>';

      // $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
      //     '<h4 class="modal-title">Sumber Dana</h4>');
      $('#GlobalModal .modal-body').html(bodyModal);
      loadTypeKB();
      $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
      $('#GlobalModal').modal({
          'show' : true,
          'backdrop' : 'static'
      });

      $('#btnKBSave').click(function () {

          var formKB_ID = $('#formKB_ID').val();
          var formKB_Type = $('#formKB_Type').val();

          if(formKB_Type!='' && formKB_Type!=null){

              loading_buttonSm('#btnKBSave');

              var data = {
                  action : 'updateListKB_2',
                  ID : formKB_ID,
                  Type : formKB_Type
              };

              var token = jwt_encode(data,'UAP)(*');
              var url = base_url_js+'api3/__crudkb';

              $.post(url,{token:token},function (result) {
                  loadTypeKB();
                  //loadPenggunaanDana();
                  setTimeout(function () {
                      $('#btnKBSave').prop('disabled',false).html('Save');
                      $('#formKB_ID').val('');
                      $('#formKB_Type').val('');
                  },500);
              });

          } else {
              toastr.warning('Hanya dapat digunakan untuk edit','Warning');
          }

      });

  });




      function loadTypeKB() {

          var data = {
              action : 'viewListKB_2'
          };

          var token = jwt_encode(data,'UAP)(*');
          var url = base_url_js+'api3/__crudkb';

          $.post(url,{token:token},function (jsonResult) {
            console.log(jsonResult);

              $('#listData,#formKBID').empty();
              if(jsonResult.length>0){

                  $.each(jsonResult,function (i,v) {

                      $('#listData').append('<tr>' +
                          '<td>'+(i+1)+'</td>' +
                          '<td>'+v.Type+'</td>' +
                          '<td><button class="btn btn-sm btn-default btnEditKB" data-id="'+v.ID+'" data-j="'+v.Type+'"><i class="fa fa-edit"></i></button></td>' +
                          '</tr>');

                      $('#formKBID').append('<option value="'+v.ID+'">'+v.Type+'</option>');
                  });

              }

          });

      }

      $(document).on('click','.btnEditKB',function () {
          var j = $(this).attr('data-j');
          var ID = $(this).attr('data-id');

          $('#formKB_ID').val(ID);
          $('#formKB_Type').val(j);
      })


      function loadDataKB() {

          var data = {
              action : 'viewListKB'
          };

          var token = jwt_encode(data,'UAP)(*');
          var url = base_url_js+'api3/__crudkb';

          $.post(url,{token:token},function (jsonResult) {
            console.log(jsonResult);

              $('#listData,#formKBID').empty();
              if(jsonResult.length>0){

                  $.each(jsonResult,function (i,v) {

                      $('#listData').append('<tr>' +
                          '<td>'+(i+1)+'</td>' +
                          '<td>'+v.Desc+'</td>' +
                          '<td><button class="btn btn-sm btn-default btnEdit" data-id="'+v.ID+'" data-j="'+v.Desc+'"><i class="fa fa-edit"></i></button></td>' +
                          '</tr>');

                      $('#formKBID').append('<option value="'+v.ID+'">'+v.Desc+'</option>');
                  });

              }

          });

      }

      $(document).on('click','.btnEdit',function () {
          var ID = $(this).attr('data-id');
          var j = $(this).attr('data-j');

          $('#formKB_ID').val(ID);
          $('#formKB_Desc').val(j);
      })



  function upload_kb(ID,FileNameOld) {

      var input = $('#upload_files');
      var files = input[0].files[0];

      var sz = parseFloat(files.size) / 8000000; // ukuran MB
      var ext = files.type.split('/')[1];

      if(Math.floor(sz)<=2){

          var fileName = moment().unix()+'_'+sessionNIP+'.'+ext;
          var formData = new FormData( $("#formupload_files")[0]);

          var url = base_url_js+'kb/upload_kb?fileName='+fileName+'&old='+FileNameOld+'&&id='+ID;

          $.ajax({
              url : url,  // Controller URL
              type : 'POST',
              data : formData,
              async : false,
              cache : false,
              contentType : false,
              processData : false,
              success : function(data) {
                  toastr.success('Upload Success','Saved');
                  setTimeout(function () {
                      // window.location.href = '';
                  },500);
                  // loadDataEmployees();

              }
          });

      }
  }


  $(document).ready(function() {
      loadTypeKB();
  })

  <?php if (isset($action)): ?>
    <?php if ($action == 'write'): ?>
    $(document).off('click', '.btnDelete').on('click', '.btnRemove',function(e) {
      var ID = $(this).attr('data-id');
      var selector = $(this);
      UpdateListKB.ActionData(selector,'deleteListKB',ID);
    })

    $(document).off('click', '.btnDelete').on('click', '.UpdateListKB',function(e) {
        var ID = $(this).attr('data-id');
        var Token = $(this).attr('data');
        var data = jwt_decode(Token);
        for(var key in data) {
            $('.input[name="'+key+'"]').val(data[key]);
          }
          if (data.UploadBukti != null && data.UploadBukti != '') {
               var FileJSon = jQuery.parseJSON(data.UploadBukti);
               if (FileJSon.length > 0) {
                  html = '<li style = "margin-top : 4px;"><a href = "'+base_url_js+'uploads/kb/'+FileJSon[0]+'" target="_blank" class = "Fileexist">File'+'</a></li>';
                  $('.fileShow').html(html);
               }
          }
        $('#btnSave').attr('action','UpdateListKB');
        $('#btnSave').attr('data-id',ID);
    })
    <?php endif ?>
  <?php endif ?>


</script>
