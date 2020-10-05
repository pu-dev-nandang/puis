
<div id="generate-edom">
    <div class="row">
        <div class="col-sm-6 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-envelope"></i> Request Change Password G-suite
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                       
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                 <tr style="background: #3968c6;color: #FFFFFF;">
                                                    <th style="width: 1%;text-align: center;">No</th>
                                                    <th style="width: 7%;text-align: center;">NPM</th>
                                                    <th style="width: 28%;text-align: center;">Name</th>
                                                    <th style="width: 16%;text-align: center;">Email</th>
                                                    <th style="width: 14%;text-align: center;">New Password</th>
                                                    <th style="width: 16%;text-align: center;">Entered At</th>
                                                    <th style="width: 1%;text-align: center;">Status</th>
                                                    <th style="width: 8%;text-align: center;">Action</th>                          
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                  $no=1;

                                                  foreach ($resetpass->result_array() as $res):
                                                ?>
                                                 <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td><?php echo $res['NPM']; ?></td>
                                                    <td><?php echo $res['Name']; ?></td>
                                                    <td><?php echo $res['Email']; ?></td>
                                                    <td><?php echo $res['NewPassword']; ?></td>
                                                    <td><?php echo $res['EnteredAt']; ?></td>
                                                    <td><?php echo $res['Status']; ?></td> 
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-info btn-sm" onclick="finishbtn(<?php echo $res['ID']; ?>);" title="Finish">Finish</button>
                                                        </div>
                                                    </td>                                   
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="ModalConfirm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <input type="hidden" name="hiddenInputForRequest" id="hiddenInputForRequest">
      <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Confirm Request</h4>
      </div>
      <div class="modal-body">
        <center>
          <h2>
            Has this request been resolved ?
          </h2>
        </center>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-sm-12">
            <center>
              <button type="button" class="btn btn-primary waves-effect text-left" onclick="confirmFinishReq();">
                Yes
              </button>
              <button type="button" class="btn btn-default waves-effect text-left" data-dismiss="modal" aria-hidden="true">
                No
              </button>
            </center>
          </div>
        </div>
      </div>
    </div>
  </div> 
</div> 

<script>
 function finishbtn(id)
  {
    $("#ModalConfirm").modal("show");
    $("#hiddenInputForRequest").val(id);
  }


    async function confirmFinishReq()
  {
    var boolValidation = true;
    var datarequest = {
      ID : $("#hiddenInputForRequest").val(),
    };

    var dataAjax = {
      datarequest : datarequest,
    }
    var token = jwt_encode(dataAjax,'UAP)(*');
    var url = "<?php echo base_url('it/finish-changepass'); ?>";
    try{
      var ajax = await AjaxSubmitFormPromises(url,token);
     
      if(ajax['status'] == 1){
        $("#ModalConfirm").modal("hide");
        toastr.success('Request has been completed','Success');
        window.location = '';
      }
      else
      {
        alert(ajax['msg']);
      }
    }
    catch(err){
      toastr.info('Something error');
    }
  }
</script>