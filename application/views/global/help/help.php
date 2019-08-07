
<style>

    #viewHelp .item-head:hover{
        background: #f5f5f5;
    }
    #viewHelp .numbering {
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
    #viewHelp .info {
        color: orangered;
        font-size: 15px;
    }
    #viewHelp .detailQNA {
        margin-top: 15px;
    }

    #viewHelp .detailQNA ul.list-group .list-group-item {
        border-radius: 15px !important;
    }

    #viewHelp a {
        text-decoration: none !important;
        display: block;
    }

</style>
<div class="row"  >
</div>
<?php if ($this->session->userdata('PositionMain')['IDDivision']=='12'){ ?>
  <div class="row"  >
    <div class="col-md-5" >
      <div class="panel panel-default">
        <div class="panel-heading"></div>
        <div class="panel-body">
          <div class="form-group">
      			<label>Division</label>
      			<select class="select2-select-00 full-width-fix" id="formQNA_Division_ID">
      				<?php for($i = 0; $i < count($G_division); $i++): ?>
      					<option value="<?php echo $G_division[$i]['ID'] ?>" > <?php echo $G_division[$i]['Division'] ?> </option>
      				<?php endfor ?>
      			 </select>
      		</div>
          <div class="form-group">
              <label>Type</label>
              <input class="form-control" id="formQNA_Type" />
          </div>
        <div class="form-group">
            <label>Question</label>
            <input class="form-control" id="formQNA_Questions" />
        </div>
        <div class="form-group">
            <label>Answer</label>
            <input class="form-control" id="formQNA_Answers" />
        </div>
        <div class="form-group">
            <label>File</label>
          <form id="formupload_files" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="">
          <input type="file" name="userfile" id="upload_files" accept="">
            </form>
          </div>
          <div class="form-group" style="text-align: right;">
              <button class="btn btn-primary" id="saveFormQNA">Save</button>
          </div>





        </div>
      </div>
    </div>
  </div>
<?php } ?>


<div class="row">
	<div class="col-md-6 col-md-offset-3">
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

<div class="row" style="margin-top: 10px">
	<div id="viewHelp" class="col-md-8 col-md-offset-2">
		    <ul class="list-group" id="headerlist">
		    	<?php for($i = 0; $i < count($G_data); $i++): ?>
		    		<?php $no = $i+1 ?>
			        <li class="list-group-item item-head">
                                <a href="javascript:void(0)" data-toggle="collapse" data-target="#<?php echo $i ?>">
                                    <span class="numbering"><?php echo $no; ?></span>
                                    <span class="info"><?php echo $G_data[$i]['Type'] ?></span>
                                </a>




			        	<div id="<?php echo $i ?>" class="collapse detailQNA">
			        	  <ul class="list-group">
			        	  	<?php $data = $G_data[$i]['data'] ?>
			        	  	<?php for($j = 0; $j < count($data); $j++): ?>
			        	  		<li class="list-group-item"><a href="javascript:void(0)" data-toggle="collapse" data-target="#<?php echo $i.'__'.$j ?>">
                                        <b><?php echo $data[$j]['Questions'] ?></b>
                                    </a>
			        	  			<div id="<?php echo $i.'__'.$j ?>" class="collapse">
			        	  				<p style="margin-top: 10px">
			        	  					<?php echo $data[$j]['Answers'] ?>
			        	  				</p>
			        	  				<div style="margin-top: 15px;margin-bottom: 15px;">
			        	  					<a class="btn btn-default <?php if($data[$j]['File']==''||$data[$j]['File']==null || $data[$j]['File']=='unavailabe.jpg'){echo 'hide';} ?>" style="display: inline;" href="<?php echo serverRoot.'/fileGetAny/help-'.$data[$j]['File'] ?>" target="_blank"><i class="fa fa-download margin-right"></i> PDF File</a>
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

<script type="text/javascript">
	$(document).ready(function () {
		$("#Division option").filter(function() {
		   //may want to use $.trim in here
		   return $(this).val() == "<?php echo $selected ?>";
		 }).prop("selected", true);
		$('#Division').select2({

		});
	});

//save_formQNA
$('#saveFormQNA').click(function () {

    var formQNA_Id = $('#formQNA_Id').val();
    var formQNA_Division_ID = $('#formQNA_Division_ID').val();
    var formQNA_Type = $('#formQNA_Type').val();
    var formQNA_Questions = $('#formQNA_Questions').val();
    var formQNA_Answers = $('#formQNA_Answers').val();
    var upload_files = $('#upload_files').val();


    // ($('#bpp_start').datepicker("getDate")!=null) ? moment($('#bpp_start').datepicker("getDate")).format('YYYY-MM-DD') : '',

    if(
    formQNA_Division_ID!='' && formQNA_Division_ID!=null &&
    formQNA_Type!='' && formQNA_Type!=null &&
    formQNA_Questions!='' && formQNA_Questions!=null &&
    formQNA_Answers!='' && formQNA_Answers!=null

  ){


      loading_button('#saveFormQNA');

      var url = base_url_js+'api3/__crudqna';
      var data = {
          action: 'updateNewQNA',
          ID : (formQNA_Id!='' && formQNA_Id!=null) ? Id : '',
          dataForm : {
              Division_Id : formQNA_Division_ID,
              Questions : formQNA_Questions,
              Type : formQNA_Type,
              Answers : formQNA_Answers
          }
      };

      var token = jwt_encode(data,'UAP)(*');

      $.post(url,{token:token},function (jsonResult) {

          toastr.success('Data saved','Success');

          if (upload_files!=null && upload_files!=''){
            upload_qna(jsonResult.ID,'');
          }


          setTimeout(function () {
          $('#saveFormQNA').html('Save').prop('disabled',false);
          $('#formQNA_Id').val('');
          $('#formQNA_Type').val('');
          $('#formQNA_Questions').val('');
          $('#formQNA_Answers').val('');
          $('#formQNA_File').val('');

          }, 500);

        });




        } else {
          toastr.error('Form Required','error');
        }

      });

	$(document).on('change','#Division', function () {
	   var url = base_url_js+"help";
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


  function upload_qna(ID,FileNameOld) {

      var input = $('#upload_files');
      var files = input[0].files[0];

      var sz = parseFloat(files.size) / 1000000; // ukuran MB
      var ext = files.type.split('/')[1];

      if(Math.floor(sz)<=8){

          var fileName = moment().unix()+'_'+sessionNIP+'.'+ext;
          var formData = new FormData( $("#formupload_files")[0]);

          var url = base_url_js+'help/upload_help?fileName='+fileName+'&old='+FileNameOld+'&&id='+ID;

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
