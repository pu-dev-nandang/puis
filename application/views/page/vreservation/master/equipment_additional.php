<div class="row" style="margin-top: 5px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i>Set Equipment Additional</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                      <span data-smt="" class="btn btn-xs btn-add">
                        <i class="icon-plus"></i> Add
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
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        loadTable(loadData);
    });

   $(document).on('click','.btn-add', function () {
      modal_generate('add','Add Equipment Additional');
   });

   function modal_generate(action,title,ID='') {
       var url = base_url_js+"vreservation/master/modal_form_equipmentadditional";
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

   $(document).on('click','#ModalbtnSaveForm', function () {
       // $.removeCookie('__tawkuuid', { path: '/' });
       loading_button('#ModalbtnSaveForm');
       var url = base_url_js+'vreservation/master/EquipmentAdditional/submit';
       var selectDivision = $("#selectDivision").val().trim();
       var selectEquipmentItem = $("#selectEquipmentItem").val().trim();
       var Qty = $('#Qty').val();
       var id = $("#ModalbtnSaveForm").attr('kodeuniq');
       var action = $(this).attr('action');
       var data = {
                   selectDivision : selectDivision,
                   selectEquipmentItem : selectEquipmentItem,
                   Qty : Qty,
                   Action : action,
                   CDID : id
                   };
       var token = jwt_encode(data,"UAP)(*");
       if (validation2(data)) {
           $.post(url,{token:token},function (data_json) {
               // jsonData = data_json;
               // var obj = JSON.parse(data_json); 
               // console.log(obj);
               $('#GlobalModal').modal('hide');
           }).done(function() {
             loadTable(loadData);
           }).fail(function() {
             toastr.error('The Database connection error, please try again', 'Failed!!');
           }).always(function() {
            $('#ModalbtnSaveForm').prop('disabled',false).html('Save');

           });
       }
       else
       {
           $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
       }          
       
   });


   function validation2(arr)
   {
     var toatString = "";
     var result = "";
     for(var key in arr) {
        switch(key)
        {
         case  "evn_price" :
               result = Validation_numeric(arr[key],key);
               if (result['status'] == 0) {
                 toatString += result['messages'] + "<br>";
               }
               break;
         case  "evn_name" :
               result = Validation_required(arr[key],key);
               if (result['status'] == 0) {
                 toatString += result['messages'] + "<br>";
               }
               break;
          default :
                if(key != 'CDID')
                {
                  result = Validation_required(arr[key],key);
                  if (result['status'] == 0) {
                    toatString += result['messages'] + "<br>";
                  }  
                }
                      
        }

     }
     if (toatString != "") {
       // toastr.error(toatString, 'Failed!!');
       $("#msgMENU").html(toatString);
       $("#msgMENU").removeClass("hide");
       return false;
     }

     return true;
   }

   $(document).on('click','.btn-edit', function () {
     var ID = $(this).attr('data-smt');
      modal_generate('edit','Edit Equipment Room',ID);
   });

   $(document).on('click','.btn-delete', function () {
     var ID = $(this).attr('data-smt');
      $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Are you sure ? </b> ' +
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
         var url = base_url_js+'vreservation/master/EquipmentAdditional/submit';
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
             },500);
         });
   });

   function loadTable(callback)
   {
       // Some code
       // console.log('test');
       $("#loadtable").empty();
       var table = '<table class="table table-striped table-bordered table-hover table-checkable datatable" id ="EventTbl">'+
       '<thead>'+
           '<tr>'+
               '<th style="width: 106px;">No</th>'+
               '<th style="width: 106px;">Equipment</th>'+
               '<th style="width: 106px;">Owner</th>'+
               '<th style="width: 106px;">Qty</th>'+
               '<th style="width: 15px;">Created BY</th>'+
               '<th style="width: 15px;">Created AT</th>'+
               '<th style="width: 15px;">Updated BY</th>'+
               '<th style="width: 15px;">Updated AT</th>'+
               '<th style="width: 15px;">Action</th>'+
           '</tr>'+
       '</thead>'+
       '<tbody>'+
       '</tbody>'+
       '</table>';
       $("#loadtable").html(table);
       callback();
   }

   function loadData()
   {
       var url = base_url_js+'vreservation/master/loaddataJSonEquipment_additional';
   // loading_page('#loadtableNow');
       $.post(url,function (data_json) {
           var response = jQuery.parseJSON(data_json);
           // console.log(response);
           // $("#loadingProcess").remove();
           var no = 1;
           for (var i = 0; i < response.length; i++) {
            var ID_division = response[i]['ID_division'];
            var btn_edit = '';
            var btn_delete = '';
            if (ID_division == "<?php echo $ID_division ?>") {
              var btn_edit = '<span data-smt="'+response[i]['ID']+'" class="btn btn-xs btn-edit"><i class="fa fa-pencil-square-o"></i> Edit</span>';
              var btn_delete = '<span data-smt="'+response[i]['ID']+'"               class="btn btn-xs btn-delete"><i class="fa fa-trash"> Delete</i></span>';
            }
                
               $("#EventTbl tbody").append( 
                   '<tr>'+
                       '<td>'+no+'</td>'+
                       '<td>'+response[i]['Equipment']+'</td>'+
                       '<td>'+response[i]['Division']+'</td>'+
                       '<td>'+response[i]['Qty']+'</td>'+
                       '<td>'+response[i]['NameCreated']+'</td>'+
                       '<td>'+response[i]['CreatedAt']+'</td>'+
                       '<td>'+response[i]['NameUpdated']+'</td>'+
                       '<td>'+response[i]['UpdatedAt']+'</td>'+
                       '<td><div class="btn-group">'+btn_edit+btn_delete+'</div></td>'+
                   '</tr>' 
                   );
               no++;
           }
       }).done(function() {
           LoaddataTableStandard('#EventTbl');
       })
   }
</script>
