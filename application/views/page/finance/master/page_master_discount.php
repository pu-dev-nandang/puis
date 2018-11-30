<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<style type="text/css">
  .setfont
  {
    font-size: 12px;
  }
  
</style>
<div class="row">
    <div class="col-xs-12" >
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Discount</h4>
            </div>
            <div class="panel-body">
                  <div class="row" style="margin-top: 30px;">
                      <div class="col-md-8 col-md-offset-2">
                          <div class="widget box">
                              <div class="widget-header">
                                  <h4 class="header"><i class="icon-reorder"></i>Master Discount</h4>
                                  <div class="toolbar no-padding">
                                      <div class="btn-group">
                                        <span data-smt="" class="btn btn-xs btn-add">
                                          <i class="icon-plus"></i> Add Discount
                                         </span>
                                      </div>
                                  </div>
                              </div>
                              <div class="widget-content">
                                  <!-- <div class = 'row'> -->
                                    <div id= "loadtable"></div>
                                  <!-- </div> -->
                                  <!-- -->
                              </div>
                              <hr/>
                          </div>
                      </div>
                  </div>         
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
  $(document).ready(function() {
    loadTableDiscount(loadDataDiscount);
  }); // exit document Function

  $(document).on('click','.btn-add', function () {
     modal_generate('add','Add Discount');
  });

  $(document).on('click','.btn-edit', function () {
    var ID = $(this).attr('data-smt');
     modal_generate('edit','Edit Discount',ID);
  });

  $(document).on('click','.btn-delete', function () {
    var ID = $(this).attr('data-smt');
     $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Apakah anda yakin untuk melakukan request ini ?? </b> ' +
         '<button type="button" id="confirmYesDelete" class="btn btn-primary" style="margin-right: 5px;" data-smt = "'+ID+'">Yes</button>' +
         '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
         '</div>');
     $('#NotificationModal').modal('show');
  });

  $(document).on('click','.btn-Active', function () {
    var ID = $(this).attr('data-smt');
    var Active = $(this).attr('data-active');
     $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Apakah anda yakin untuk melakukan request ini ?? </b> ' +
         '<button type="button" id="confirmYesActive" class="btn btn-primary" style="margin-right: 5px;" data-smt = "'+ID+'" data-active = "'+Active+'">Yes</button>' +
         '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
         '</div>');
     $('#NotificationModal').modal('show');
  });

  $(document).on('click','#confirmYesDelete',function () {
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
        var url = base_url_js+'finance/master/sbmt_discount';
        var aksi = "delete";
        var ID = $(this).attr('data-smt');
        var data = {
            Action : aksi,
            CDID : ID,
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{token:token},function (data_json) {
            setTimeout(function () {
               toastr.options.fadeOut = 10000;
               toastr.success('Data berhasil disimpan', 'Success!');
               loadTableDiscount(loadDataDiscount);
               $('#NotificationModal').modal('hide');
            },2000);
        });
  });

  $(document).on('click','#confirmYesActive',function () {
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
        var url = base_url_js+'finance/master/sbmt_discount';
        var aksi = "getactive";
        var ID = $(this).attr('data-smt');
        var Active = $(this).attr('data-active');
        var data = {
            Action : aksi,
            CDID : ID,
            Active:Active,
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{token:token},function (data_json) {
            setTimeout(function () {
               toastr.options.fadeOut = 10000;
               toastr.success('Data berhasil disimpan', 'Success!');
               loadTableDiscount(loadDataDiscount);
               $('#NotificationModal').modal('hide');
            },2000);
        });
  });


  $(document).on('click','#ModalbtnSaveForm', function () {
    loading_button('#ModalbtnSaveForm');
     var aksi = $("#ModalbtnSaveForm").attr('action');
     var id = $("#ModalbtnSaveForm").attr('kodeuniq');
     var Discount = $("#Discount").val();
     Discount = findAndReplace(Discount, '.', '');
     Discount = findAndReplace(Discount, ',', '.');
     var url = base_url_js+'finance/master/sbmt_discount';
     var data = {
         Action : aksi,
         CDID : id,
         Discount:Discount,
     };

     if (validationInput = validation(data)) {
         var token = jwt_encode(data,"UAP)(*");
         $.post(url,{token:token},function (data_json) {
             setTimeout(function () {
                toastr.options.fadeOut = 10000;
                toastr.success('Data berhasil disimpan', 'Success!');
                $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
                loadTableDiscount(loadDataDiscount);
             },500);
         });
     }
     else
     {
        $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
     }

  });

  function validation(arr)
  {
    var toatString = "";
    var result = "";
    for(var key in arr) {
       switch(key)
       {
        case  "Action" :
        case  "CDID" :
              break;
        /*case  "selectRangking2" :
              toatString += (arr[key] <= arr['selectRangking1']) ? 'End Rangking tidak boleh kecil sama dengan Start Rangking' : '';
              break;*/
       }

    }
    if (toatString != "") {
      toastr.error(toatString, 'Failed!!');
      return false;
    }

    return true;
  }

  function modal_generate(action,title,ID='') {
      var url = base_url_js+"finance/master/modalform_discount";
      var data = {
          Action : action,
          CDID : ID,
      };
      var token = jwt_encode(data,"UAP)(*");
      $.post(url,{ token:token }, function (html) {
          $('#GlobalModal .modal-header').html('<h4 class="modal-title">'+title+'</h4>');
          $('#GlobalModal .modal-body').html(html);
          $('#GlobalModal .modal-footer').html(' ');
          $('#GlobalModal').modal({
              'show' : true,
              'backdrop' : 'static'
          });
      })
  }

  function loadTableDiscount(callback)
  {
      // Some code
      // console.log('test');
      $("#loadtable").empty();
      var table = '<table class="table table-striped table-bordered table-hover table-checkable datatable" id ="Tbldata1">'+
      '<thead>'+
          '<tr>'+
              '<th style="width: 106px;">No</th>'+
              '<th style="width: 106px;">Discount</th>'+
              '<th style="width: 15px;">Action</th>'+
          '</tr>'+
      '</thead>'+
      '<tbody>'+
      '</tbody>'+
      '</table>';
      //$("#loadtableNow").empty();
      $("#loadtable").html(table);

      /*if (typeof callback === 'function') { 
          callback(); 
      }*/
      callback();
  }

  function loadDataDiscount()
  {
      var url = base_url_js+'finance/master/load_discount';
  // loading_page('#loadtableNow');
      $.post(url,function (data_json) {
          var response = jQuery.parseJSON(data_json);
          // console.log(response);
          // $("#loadingProcess").remove();
          var no = 1;
          for (var i = 0; i < response.length; i++) {
               var btn_edit = '<span data-smt="'+response[i]['ID']+'" class="btn btn-xs btn-edit"><i class="fa fa-pencil-square-o"></i> Edit</span>';
               var btn_delete = '<span data-smt="'+response[i]['ID']+'"               class="btn btn-xs btn-delete"><i class="fa fa-trash"> Delete</i></span>';
               var btn_status = '';
               var status = '';

              $("#Tbldata1 tbody").append(
                  '<tr>'+
                      '<td>'+no+'</td>'+
                      '<td>'+response[i]['Discount']+'%</td>'+
                      '<td><div class="btn-group">'+btn_edit+btn_status+btn_delete+'</div></td>'+
                  '</tr>' 
                  );
              no++;
          }
      }).done(function() {
          LoaddataTableStandard('#Tbldata1');
      })
  }  

</script>