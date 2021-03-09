
<div id="generate-edom">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-envelope"></i> Request Change Password G-suite
                    </h4>
                </div>
                <div class="panel-body">
                  <div class="">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <div class="well">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Need Approve</label>
                                        <select class="form-control" id="filterType">
                                            <option value="">--- All Status ---</option>
                                            <option disabled>-----------------------</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>


                    <div class="row">
                       
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        
                                        <div id="loadTable"></div>
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
 $(document).ready(function(){
     
        loadSelectOptionRequest('#filterType','');
       loadDataRequest();
    });

 $('#filterType').change(function () {
        loadDataRequest();
    });
function loadSelectOptionRequest(element,selected) {

        var data = {action : 'getStatus'};

        var token = jwt_encode(data,'UAP)(*');
  
        var url = "<?php echo base_url('it/finish-changepass'); ?>";

        $.post(url,{token:token},function (jsonResult) {
            $.each(JSON.parse(jsonResult),function (i,v) {
              
                var sc = (selected==v.sts) ? 'selected' : '';
                if (v.sts==0) {
                  $(element).append('<option value="'+v.sts+'" '+sc+'>Pending</option>');
                } else {
                  $(element).append('<option value="'+v.sts+'" '+sc+'>Finish</option>');
                }
       
            });
        });

    }

 function loadDataRequest() {

        $('#loadTable').html('<table id="tableDataRequest" class="table table-bordered table-striped table-centre" style="width:100%">' +
            '               <thead>' +
            '                <tr style="background: #eceff1;">' +
            '                    <th style="width: 1%;text-align: center;">No</th>'+
            '                    <th style="width: 11%;text-align: center;">Username</th>'+
            '                    <th style="width: 18%;text-align: center;">Name</th>'+
            '                    <th style="width: 18%;text-align: center;">Email</th>'+
            '                    <th style="width: 12%;text-align: center;">New Password</th>'+
            '                    <th style="width: 15%;text-align: center;">Entered At</th>'+
            '                    <th style="width: 8%;text-align: center;">Status</th>'+
            '                    <th style="width: 8%;text-align: center;">Action</th>'+ 
            '                </tr>' +
            '                </thead>' +
            '           </table>');

        var filterType = $('#filterType').val();
     

        var data = {
            action : 'viewData',
            filterType : filterType
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = "<?php echo base_url('it/finish-changepass'); ?>";

        var dataTable = $('#tableDataRequest').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Search..."
            },
            "ajax":{
                url :url, // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    loading_modal_hide();
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );

    }

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
      action : 'finish',
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