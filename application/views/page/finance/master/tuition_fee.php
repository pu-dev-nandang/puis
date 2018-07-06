<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<!--coba-->
<div class="row" style="margin-top: 30px;">
    <div class="col-md-4 col-md-offset-4">
        <div class="thumbnail" style="min-height: 30px;padding: 10px;">
            <select class="form-control" id="selectCurriculum">
                <option selected disabled>--- Curriculum ---</option>
                <option disabled>------</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr/>
        <div align="right">
            <div class="btn-group">
                <button type="button" class="btn btn-info btn-add">
                  <span class="glyphicon glyphicon-plus"></span> Add
                </button>
            </div>
        </div>
        <br>
        <table class="table table-bordered">
            <thead>
            <tr style="background: #333;color: #fff;">
                <th style="">Program Study</th>
                <th style="width: 10%;">SPP</th>
                <th style="width: 10%;">BPP</th>
                <th style="width: 10%;">Credit</th>
                <th style="width: 10%;">Another</th>
                <th style="width: 10%;">Semester 1 Pay</th>
                <th style="width: 10%;">Action</th>
            </tr>
            </thead>
            <tbody id="dataRow"></tbody>
        </table>
    </div>
</div>


<script>
    $(document).ready(function () {
        loadSelectOptionCurriculum('#selectCurriculum','');
    });

    $(document).on('click','.btn-add', function () {
       modal_generate('add','Add Master Tagihan Mahasiswa');
    });

    function modal_generate(action,title,ID='') {
        var url = base_url_js+"finance/master/modal-tagihan-mhs";
        var data = {
            Action : action,
            CDID : ID,
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token }, function (html) {
            $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+title+'</h4>');
            $('#GlobalModalLarge .modal-body').html(html);
            $('#GlobalModalLarge .modal-footer').html(' ');
            $('#GlobalModalLarge').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        })
    }

    $(document).on('click','#ModalbtnSaveForm', function () {
      loading_button('#ModalbtnSaveForm');
       var TypePembayaran = $("#selectTypePembayaran").val();
       var Prodi = $("#selectProdi").val();
       var Cost = $("#Cost").val();
       var ClassOf = $("#selectClassOf").val();
        for(i = 0; i <Cost.length; i++) {
         
         Cost = Cost.replace(".", "");
         
        }

       var url = base_url_js+'finance/master/modal-tagihan-mhs-submit';
       var data = {
           TypePembayaran : TypePembayaran,
           Prodi : Prodi,
           Cost : Cost,
           ClassOf : ClassOf
       };

       if (validationInput = validation(data)) {
           var token = jwt_encode(data,"UAP)(*");
           $.post(url,{token:token},function (data_json) {
               setTimeout(function () {
                  toastr.options.fadeOut = 10000;
                  toastr.success('Data berhasil disimpan', 'Success!');
                  $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
                  loadData();
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
          case  "Cost" :
                result = Validation_required(arr[key],key);
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

    $('#selectCurriculum').change(function () {
        loadData();
    });

    $(document).on('click','.btn-edit', function () {
       var row = $(this).attr('row');
       var inptext = $(this).attr('inptext');
       if (inptext == 'disabled') {
            $( "."+row).each(function( index ) {
                 var id = $(this).attr('id');
                 id = id.split("_");
                 id = id[1];
                 var Cost = $(this).attr('Cost');
                 var n = Cost.indexOf(".");
                 var Cost = Cost.substring(0, n);
                 
                 var input = '<input type = "text" class = "form-control '+row+'" id = "index'+row+index+'" tid = "'+id+'">';
                  $(this).html(input);
                  $("#index"+row+index).val(Cost);
                  $("#index"+row+index).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
                  $("#index"+row+index).maskMoney('mask', '9894'); 
            });
           $(this).attr('inptext','enabled'); 
       } else {
            var arr = [];
            $( "."+row).each(function( index ) {
                var Cost = $(this).val();
                id = $(this).attr('tid');
                // Cost = Cost.replace(".", "");
                for(i = 0; i <Cost.length; i++) {
                 
                 Cost = Cost.replace(".", "");
                 
                }

                if (Cost != "") {
                    var data = {
                        id : id,
                        Cost : Cost
                    }
                    arr.push(data);
                }
            });

            // update data to db
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
                var url =base_url_js+'finance/master/edited-tagihan-mhs-submit';
                var data = arr;
                var token = jwt_encode(data,'UAP)(*');
                $.post(url,{token:token},function (data_json) {
                    setTimeout(function () {
                       toastr.options.fadeOut = 10000;
                       toastr.success('Data berhasil disimpan', 'Success!');
                       loadData();
                       $('#NotificationModal').modal('hide');
                    },500);
                });
            })
                    
       }
       
    });

    $(document).on('click','.btn-delete', function () {
       var dtprodi = $(this).attr('data-prodi');
       var selectCurriculum = $("#selectCurriculum").val();
       selectCurriculum = selectCurriculum.split(".");
       var ClassOf = selectCurriculum[1]; 
       // update data to db
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
           var url =base_url_js+'finance/master/deleted-tagihan-mhs-submit';
           var data = {
                ClassOf : ClassOf,
                ProdiID : dtprodi
           };
           var token = jwt_encode(data,'UAP)(*');
           $.post(url,{token:token},function (data_json) {
               setTimeout(function () {
                  toastr.options.fadeOut = 10000;
                  toastr.success('Data berhasil disimpan', 'Success!');
                  loadData();
                  $('#NotificationModal').modal('hide');
               },500);
           });
       })
       
    });

    function loadData() {
        var CDID = $('#selectCurriculum').val();
        if(CDID!='' && CDID!=null){
            var exp = CDID.split('.');
            var url = base_url_js+'api/__crudTuitionFee';
            var data = {
                action : 'read',
                ClassOf : exp[1].trim()
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
               console.log(resultJson);
                $('#dataRow').html('');
               for(var i=0;i<resultJson.length;i++){
                   var dataProdi = resultJson[i];
                   var btn_edit = '<span data-smt="" class="btn btn-xs btn-edit" inptext = "disabled" row = "C_'+i+'">'+
                                        '<i class="fa fa-pencil-square-o"></i> Edit'+
                                       '</span>';
                   var btn_delete = '<span data-prodi="'+dataProdi.ProdiID+'" class="btn btn-xs btn-delete">'+
                                         '<i class="fa fa-trash"></i> Delete'+
                                        '</span>';                   
                   var html = '<tr>' +
                       '<td>'+dataProdi.ProdiName+'</td>';

                   for (var j = 0; j < 5; j++) {
                       var vv = '<td>-</td>'
                       if ( (j in dataProdi.Detail) ) {
                         vv = '<td id = "RID_'+dataProdi.Detail[j].ID+'" Cost = "'+dataProdi.Detail[j].Cost+'" class = "C_'+i+'">'+formatRupiah(parseInt(dataProdi.Detail[j].Cost))+'</td>';
                       }

                       html += vv;
                   }

                   html +=  '<td>'+btn_edit+btn_delete+'</td></tr>';

                   $('#dataRow').append(html);
               }

            });
        }
    }
</script>