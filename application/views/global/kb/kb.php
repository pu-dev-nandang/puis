
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
            <input type="text" id="formKB_Division_ID" value="<?= $this->session->userdata('PositionMain')['IDDivision']; ?>">
      			<!-- <select class="select2-select-00 full-width-fix" id="formKB_Division_ID">
      				<?php for($i = 0; $i < count($G_division); $i++): ?>
      					<option value="<?php echo $G_division[$i]['ID'] ?>" > <?php echo $G_division[$i]['Division'] ?> </option>
      				<?php endfor ?>
      			 </select> -->
      		</div>
          <div class="form-group">
              <label>Type</label>
              <input class="form-control" id="formKB_Type" />
          </div>
        <div class="form-group">
            <label>Description</label>
            <input class="form-control" id="formKB_Desc" />
        </div>
        <div class="form-group">
            <label>File</label>
          <form id="formupload_files" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
          <input type="file" name="userfile" id="upload_files" accept="">
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
        <div id="viewListKB" class="col-md-12">
              <ul class="list-group" id="headerlist">
                <?php for($i = 0; $i < count($G_data); $i++): ?>
                  <?php $no = $i+1 ?>
                    <li class="list-group-item item-head">
                                      <a href="javascript:void(0)" data-toggle="collapse" data-target="#<?php echo $i ?>">
                                          <span class="numbering"><?php echo $no; ?></span>
                                          <span class="info"><?php echo $G_data[$i]['Type'] ?></span>
                                      </a>




                      <div id="<?php echo $i ?>" class="collapse detailKB">
                        <ul class="list-group">
                          <?php $data = $G_data[$i]['data'] ?>
                          <?php for($j = 0; $j < count($data); $j++): ?>
                            <li class="list-group-item"><a href="javascript:void(0)" data-toggle="collapse" data-target="#<?php echo $i.'__'.$j ?>">
                                              <b><?php echo $data[$j]['Type'] ?></b>
                                          </a>
                              <div id="<?php echo $i.'__'.$j ?>" class="collapse">
                                <p style="margin-top: 10px">
                                  <?php echo $data[$j]['Desc'] ?>
                                </p>
                                <div style="margin-top: 15px;margin-bottom: 15px;">
                                  <a class="btn btn-default <?php if($data[$j]['File']==''||$data[$j]['File']==null || $data[$j]['File']=='unavailabe.jpg'){echo 'hide';} ?>" style="display: inline;" href="<?php echo serverRoot.'/fileGetAny/kb'.$data[$j]['File'] ?>" target="_blank"><i class="fa fa-download margin-right"></i> PDF File</a>
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
	});

//save_formKB
$('#saveFormKB').click(function () {

    var formKB_Id = $('#formKB_Id').val();
    var formKB_Division_ID = $('#formKB_Division_ID').val();
    var formKB_Type = $('#formKB_Type').val();
    var formKB_Desc = $('#formKB_Desc').val();
    var upload_files = $('#upload_files').val();


    // ($('#bpp_start').datepicker("getDate")!=null) ? moment($('#bpp_start').datepicker("getDate")).format('YYYY-MM-DD') : '',

    if(
    formKB_Division_ID!='' && formKB_Division_ID!=null &&
    formKB_Type!='' && formKB_Type!=null &&
    formKB_Desc!='' && formKB_Desc!=null

  ){


      loading_button('#saveFormKB');

      var url = base_url_js+'api3/__crudkb';
      var data = {
          action: 'updateNewKB',
          ID : (formKB_Id!='' && formKB_Id!=null) ? Id : '',
          dataForm : {
              Division_Id : formKB_Division_ID,
              Type : formKB_Type,
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
          $('#formKB_Id').val('');
          $('#formKB_Type').val('');
          $('#formKB_Desc').val('');
          $('#formKB_File').val('');

          }, 500);

        });




        } else {
          toastr.error('Form Required','error');
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
	   	},2000);
	   })

	});


  function upload_kb(ID,FileNameOld) {

      var input = $('#upload_files');
      var files = input[0].files[0];

      var sz = parseFloat(files.size) / 1000000; // ukuran MB
      var ext = files.type.split('/')[1];

      if(Math.floor(sz)<=8){

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

</script>
