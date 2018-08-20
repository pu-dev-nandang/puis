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
                <h4 class="header"><i class="icon-reorder"></i>Policy</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                      <span data-smt="" class="btn btn-xs btn-add">
                        <i class="icon-plus"></i> Add Policy
                       </span>
                    </div>
                </div>
            </div>
            <div class="widget-content">
                <!--  -->
                  <div id = "pageData"></div>
                <!-- end widget -->
            </div>
            <hr/>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    loadTableJS(loadDataTable);
  }); // exit document Function

  $(document).on('click','.btn-add', function () {
     modal_generate('add','Add Policy');
  });

  $(document).on('click','.btn-edit', function () {
    var ID = $(this).attr('data-smt');
     modal_generate('edit','Edit Policy',ID);
  });

  $(document).on('click','.btn-delete', function () {
    var ID = $(this).attr('data-smt');
     $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Apakah anda yakin untuk melakukan request ini ?? </b> ' +
         '<button type="button" id="confirmYesDelete" class="btn btn-primary" style="margin-right: 5px;" data-smt = "'+ID+'">Yes</button>' +
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
        var url = base_url_js+'vreservation/config/policy/submit';
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
               loadTableJS(loadDataTable);
               $('#NotificationModal').modal('hide');
            },500);
        });
  });


  $(document).on('click','#ModalbtnSaveForm', function () {
    loading_button('#ModalbtnSaveForm');
     var aksi = $("#ModalbtnSaveForm").attr('aksi');
     var id = $("#ModalbtnSaveForm").attr('kodeuniq');
     var selectDivision = $("#selectDivision").val();
     var selectGroupuUser = $("#selectGroupuUser").val();
     var BookingDay = $("#BookingDay").val();

     var url = base_url_js+'vreservation/config/policy/submit';
     var data = {
         Action : aksi,
         CDID : id,
         selectGroupuUser:selectGroupuUser,
         BookingDay:BookingDay,
     };

     if (validationInput = validation(data)) {
         var token = jwt_encode(data,"UAP)(*");
         $.post(url,{token:token},function (data_json) {
             setTimeout(function () {
                toastr.options.fadeOut = 10000;
                toastr.success('Data berhasil disimpan', 'Success!');
                $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
                loadTableJS(loadDataTable);
                $('#GlobalModal').modal('hide');
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
        case  "PriceFormulir" :
              result = Validation_numeric(arr[key],key);
              if (result['status'] == 0) {
                toatString += result['messages'] + "<br>";
              }
              break;
       }

    }
    if (toatString != "") {
      toastr.error(toatString, 'Failed!!');
      return false;
    }

    return true;
  }

  function modal_generate(action,title,ID='') {
      var url = base_url_js+"vreservation/config/policy/modalform";
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

  function loadTableJS(callback)
  {
      // Some code
      // console.log('test');
      $("#pageData").empt
      var table = '<div class = "col-md-12"><div class="table-responsive"> <table class="table table-striped table-bordered table-hover table-checkable datatable" id ="IDTblGroupUser">'+
      '<thead>'+
          '<tr>'+
              '<th style="width: 106px;">Group User</th>'+
              '<th style="width: 106px;">Booking Day</th>'+
              '<th style="width: 15px;">Action</th>'+
          '</tr>'+
      '</thead>'+
      '<tbody>'+
      '</tbody>'+
      '</table></div></div>';
      //$("#loadtableNow").empty();
      $("#pageData").html(table);

      /*if (typeof callback === 'function') { 
          callback(); 
      }*/
      callback();
  }

  function loadDataTable()
  {
      var url = base_url_js+'vreservation/config/policy_json_data'
  // loading_page('#loadtableNow');
      $.post(url,function (data_json) {
          var response = jQuery.parseJSON(data_json);
          // $("#loadingProcess").remove();
          for (var i = 0; i < response.length; i++) {
             var btn_edit = '<span data-smt="'+response[i]['ID']+'" class="btn btn-xs btn-edit"><i class="fa fa-pencil-square-o"></i> Edit</span>';
             var btn_delete = '<span data-smt="'+response[i]['ID']+'"  class="btn btn-xs btn-delete"><i class="fa fa-trash"> Delete</i></span>';
              $(".datatable tbody").append(
                  '<tr>'+
                      '<td>'+response[i]['GroupAuth']+'</td>'+
                      '<td>'+response[i]['BookingDay']+'</td>'+
                      '<td>'+btn_edit+btn_delete+'</td>'+
                  '</tr>' 
                  );
          }
      }).done(function() {
          LoaddataTableStandard('.datatable');
      })
  }

</script>