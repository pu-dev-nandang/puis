<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<style type="text/css">
  .btn-submit{
    background-color: #1ace37;
  }
</style>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-3">
        <div class="thumbnail" style="min-height: 30px;padding: 10px;">
            <input type="text" name="" class="form-control" placeholder="Input NPM Mahasiswa" id = "NIM">
        </div>
    </div>
</div>
<br>
<div class="row">
  <div class="col-md-12" align="right">
    <button type="button" class="btn btn-default" id = 'idbtn-cari'><span class="glyphicon glyphicon-search"></span> Cari</button>
  </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr/>
        <table class="table table-bordered datatable2 hide" id = "datatable2">
            <thead>
            <tr style="background: #333;color: #fff;">
                <!-- <th style="width: 3%;"></th> -->
                <th style="width: 12%;">Program Study</th>
                <!-- <th style="width: 10%;">Semester</th> -->
                <th style="width: 20%;">Nama</th>
                <!-- <th style="width: 5%;">NPM</th> -->
                <!-- <th style="width: 5%;">Year</th> -->
                <th style="width: 15%;">Payment Type</th>
                <th style="width: 15%;">Email PU</th>
                <th style="width: 15%;">IPS</th>
                <th style="width: 15%;">IPK</th>
                <th style="width: 10%;">Discount</th>
                <th style="width: 10%;">Invoice</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 10%;">Detail Payment</th>
            </tr>
            </thead>
            <tbody id="dataRow"></tbody>
        </table>
        <hr>
        <div id = "inputCicilan" class="hide">
          <div class="widget box">
              <div class="widget-header">
                  <h4 class="header"><i class="icon-reorder"></i>Input Cicilan</h4>
              </div>
              <div class="widget-content">
                  <!--  -->
                   
                  <!-- end widget -->
              </div>
              <hr/>
          </div>
        </div>
    </div>
    
</div>

<script>
    $(document).ready(function () {
        
    });

    $(document).on('click','#idbtn-cari', function () {
        var NPM = $("#NIM").val();
        result = Validation_required(NPM,NPM);
        if (result['status'] == 0) {
          toastr.error(result['messages'], 'Failed!!');
        }
        else
        {
          loadData(1,NPM);
        }
    });

    function loadData(page,NPM) {
        var NIM = NPM;
        $(".widget-content").empty();
        $("#inputCicilan").addClass('hide');
        $('#datatable2').addClass('hide');

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
            $('#dataRow').html('');
            var url = base_url_js+'finance/get_created_tagihan_mhs/'+page;
            var data = {
                ta : '',
                prodi : '',
                PTID  : '',
                NIM : NIM,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
               var resultJson = jQuery.parseJSON(resultJson);
               console.log(resultJson);
              
            }).fail(function() {
              
              toastr.info('No Result Data'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {
                $('#NotificationModal').modal('hide');
            });
    }

    

</script>