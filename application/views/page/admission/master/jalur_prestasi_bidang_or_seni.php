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
                <h4 class="header"><i class="icon-reorder"></i>Jalur Prestasi Bidang Seni</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                      <span data-smt="" class="btn btn-xs btn-add">
                        <i class="icon-plus"></i> Add Potongan
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
    <div class="col-md-2"></div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    loadTable(loadData);
  }); // exit document Function

  $(document).on('click','.btn-add', function () {
     modal_generate('add','Add Potongan');
  });

  $(document).on('click','.btn-edit', function () {
    var ID = $(this).attr('data-smt');
     modal_generate('edit','Edit Potongan',ID);
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
        var url = base_url_js+'admission/master-registration/submit_jpok';
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
               loadTable(loadData);
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
        var url = base_url_js+'admission/master-registration/submit_jpok';
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
               loadTable(loadData);
               $('#NotificationModal').modal('hide');
            },2000);
        });
  });


  $(document).on('click','#ModalbtnSaveForm', function () {
    loading_button('#ModalbtnSaveForm');
     var aksi = $("#ModalbtnSaveForm").attr('action');
     var id = $("#ModalbtnSaveForm").attr('kodeuniq');
     var Tingkat = $("#selectTingkat").val();
     var selectPotonganSPP = $("#selectPotonganSPP").val();
     var selectPotonganSKS = $("#selectPotonganSKS").val();
     var url = base_url_js+'admission/master-registration/submit_jpok';
     var data = {
         Action : aksi,
         CDID : id,
         Tingkat:Tingkat,
         selectPotonganSPP:selectPotonganSPP,
         selectPotonganSKS:selectPotonganSKS,
     };

     if (validationInput = validation(data)) {
         var token = jwt_encode(data,"UAP)(*");
         $.post(url,{token:token},function (data_json) {
             setTimeout(function () {
                toastr.options.fadeOut = 10000;
                toastr.success('Data berhasil disimpan', 'Success!');
                $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
                loadTable(loadData);
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
      var url = base_url_js+"admission/master-registration/modalform_jpok";
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

  function loadTable(callback)
  {
      // Some code
      // console.log('test');
      $("#loadtable").empty();
      var table = '<table class="table table-striped table-bordered table-hover table-checkable datatable" id ="Tbldata1">'+
      '<thead>'+
          '<tr>'+
              '<th style="width: 106px;">No</th>'+
              '<th style="width: 106px;">Tingkat</th>'+
              '<th style="width: 106px;">Potongan SPP</th>'+
              '<th style="width: 106px;">Potongan Biaya SKS (semester-1)</th>'+
              '<th style="width: 106px;">Status</th>'+
              '<th style="width: 15px;">Created</th>'+
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

  function loadData()
  {
      var url = base_url_js+'admission/master-registration/jpok/table_jpok';
  // loading_page('#loadtableNow');
      $.post(url,function (data_json) {
          var response = jQuery.parseJSON(data_json);
          // console.log(response);
          // $("#loadingProcess").remove();
          var no = 1;
          for (var i = 0; i < response.length; i++) {
               var btn_edit = '<span data-smt="'+response[i]['ID']+'" class="btn btn-xs btn-edit"><i class="fa fa-pencil-square-o"></i> Edit</span>';
               var btn_delete = '<span data-smt="'+response[i]['ID']+'"               class="btn btn-xs btn-delete"><i class="fa fa-trash"> Delete</i></span>';
               var btn_status = '<span data-smt="'+response[i]['ID']+'" class="btn btn-xs btn-Active" data-active = "'+response[i]['Active']+'"><i class="fa fa-hand-o-right"> Change Active</i></span>';
               var status = '';
               if(response[i]['Active'] == 0)
               {
                 status = '<i class="fa fa-minus-circle" style="color: red;"></i>';
               }
               else
               {
                 status = '<i class="fa fa-check-circle" style="color: green;"></i>';
               }
              $("#Tbldata1 tbody").append(
                  '<tr>'+
                      '<td>'+no+'</td>'+
                      '<td>'+response[i]['Tingkat']+'</td>'+
                      '<td>'+response[i]['DiskonSPP']+'%'+'</td>'+
                      '<td>'+response[i]['DiskonBiayaSKS']+'%'+'</td>'+
                      '<td>'+status+'</td>'+
                      '<td>'+response[i]['CreateAT']+'</td>'+
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