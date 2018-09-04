<style type="text/css">
  .setfont
  {
    font-size: 12px;
  }
  
</style>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i>Set Tahun Ajaran</h4>
            </div>
            <div class="widget-content">
                <!--  -->
                 <div class="row">
                   <div class="col-xs-4">
                     <div class="form-group"><label>Tahun Ajaran</label>
                      <input type="number" class="form-control" value="<?php echo $tahun ?>"  id="TA">
                     </div>
                   </div>
                 </div>
                  <button type="button" class="btn btn-success btn-edit" id="btnSave">Save</button>
                <!-- end widget -->
            </div>
            <hr/>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    
  }); // exit document Function

  $(document).on('click','#btnSave',function () {
    $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Apakah anda yakin untuk melakukan request ini ?? </b> ' +
        '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
        '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
        '</div>');
    $('#NotificationModal').modal('show');
    $("#confirmYes").click(function(){
        $('#NotificationModal .modal-header').addClass('hide');
        $('#NotificationModal .modal-body').html('<center>' +
            '                    <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>' +
            '                    <br/>' +
            '                    Loading Data . . .' +
            '                </center>');
        $('#NotificationModal .modal-footer').addClass('hide');
        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });

        var url = base_url_js+'admission/submit_set_tahun_ajaran';
        var data = {
                    Ta : $('#TA').val(),
                   };
        var token = jwt_encode(data,"UAP)(*");
         $.post(url,{token:token},function (data_json) {
            // $('#generateToBEMhs').prop('disabled',false).html('Generate');
            $('#NotificationModal').modal('hide');
            toastr.success('Data Tersimpan','Success!');
        }).done(function() {
          
        }).fail(function() {
          toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {
         // $('#generateToBEMhs').prop('disabled',false).html('Generate');

        });
    })
  });

</script>