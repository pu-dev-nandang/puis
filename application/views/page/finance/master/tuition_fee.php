<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="row">
    <div class="col-xs-12" >
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Tagihan Mahasiswa</h4>
            </div>
            <div class="panel-body">
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
                                <button type="button" class="btn btn-info copyLastTf" id = "copyLastTf">
                                  <span class="glyphicon glyphicon-plus"></span> Copy Last Tuition Fee
                                </button>
                            </div>
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
                                <th style="width: 10%;">Action</th>
                            </tr>
                            </thead>
                            <tbody id="dataRow"></tbody>
                        </table>
                    </div>
                </div>           
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
        loadSelectOptionCurriculum2('#selectCurriculum','');
    });

    
    $(document).on('click','#copyLastTf', function () {
      loading_button('#copyLastTf');
       var url = base_url_js+'finance/master/copy-last-tuition_fee';
       var data = {
          verify : 'CreateTuitionFee',
       }
       var token = jwt_encode(data,"UAP)(*");
        $.post(url,{token:token},function (data_json) {
         var data_json = jQuery.parseJSON(data_json);
            setTimeout(function () {
               toastr.options.fadeOut = 10000;
               if(data_json == '')
               {
                 toastr.success('Data berhasil disimpan', 'Success!');
               }
               else
               {
                 toastr.error(data_json, 'Failed!!');
               }
                 $('#copyLastTf').prop('disabled',false).html('Copy Last Tuition Fee');
                 var thisYear = (new Date()).getFullYear();
                 var Tahun = parseInt(thisYear) + parseInt(1);
                 Tahun = 'Curriculum ' + Tahun;
                 $("#selectCurriculum option").filter(function() {
                   //may want to use $.trim in here
                   return $(this).text() == Tahun; 
                 }).prop("selected", true);

                 loadData();
            },500);
        });
    });


    $(document).on('click','.btn-add', function () {
       modal_generate('add','Add Master Tagihan Mahasiswa');
    });

    function modal_generate(action,title,ID='') {
        var url = base_url_js+"finance/master/modal-tagihan-mhs";
        var selectCurriculum = $("#selectCurriculum").val();
        var aa = selectCurriculum.split(".");
        selectCurriculum = aa[1];
        var data = {
            Action : action,
            CDID : ID,
            selectCurriculum : selectCurriculum
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
       Prodi = Prodi.split('.');
       Prodi = Prodi[0];
       var Cost = $("#Cost").val();
       var ClassOf = $("#selectClassOf").val();
       var Pay_Cond = $("#selectPay_Cond").val();
        for(i = 0; i <Cost.length; i++) {
         
         Cost = Cost.replace(".", "");
         
        }

       var url = base_url_js+'finance/master/modal-tagihan-mhs-submit';
       var data = {
           TypePembayaran : TypePembayaran,
           Prodi : Prodi,
           Cost : Cost,
           ClassOf : ClassOf,
           Pay_Cond : Pay_Cond
       };

       if (validationInput = validation(data)) {
           var token = jwt_encode(data,"UAP)(*");
           $.post(url,{token:token},function (data_json) {
            var data_json = jQuery.parseJSON(data_json);
               setTimeout(function () {
                  toastr.options.fadeOut = 10000;
                  if(data_json == '')
                  {
                    toastr.success('Data berhasil disimpan', 'Success!');
                  }
                  else
                  {
                    toastr.error(data_json, 'Failed!!');
                  }
                    $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
                    loadData();
                    $("#GlobalModalLarge").modal('hide');
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
       var bintang = $(this).attr('bintang');
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
                ProdiID : dtprodi,
                bintang : bintang
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
                   var tt = '';
                   var bintang = '*';
                   for (var j = 0; j < 4; j++) {
                       var vv = '<td>-</td>';
                       for (var k = 0; k < dataProdi.Detail.length; k++) {
                         var ss = j + 1;
                         if(ss == dataProdi.Detail[k].PTID)
                         {
                          vv = '<td id = "RID_'+dataProdi.Detail[k].ID+'" Cost = "'+dataProdi.Detail[k].Cost+'" class = "C_'+i+'">'+formatRupiah(parseInt(dataProdi.Detail[k].Cost))+'</td>';
                          bintang = (dataProdi.Detail[k].Pay_Cond == 1) ? '*' : '**';
                          break;
                         }
                       }
                       /*if ( (j in dataProdi.Detail) ) {
                         vv = '<td id = "RID_'+dataProdi.Detail[j].ID+'" Cost = "'+dataProdi.Detail[j].Cost+'" class = "C_'+i+'">'+formatRupiah(parseInt(dataProdi.Detail[j].Cost))+'</td>';
                         bintang = (dataProdi.Detail[j].Pay_Cond == 1) ? '*' : '**';
                       }*/
                       tt += vv;
                   }

                   var btn_delete = '<span data-prodi="'+dataProdi.ProdiID+'" bintang = "'+bintang+'"  class="btn btn-xs btn-delete">'+
                                         '<i class="fa fa-trash"></i> Delete'+
                                        '</span>';         

                   var html = '<tr>' +
                       '<td>'+dataProdi.ProdiName+bintang+'</td>';
                   html += tt;
                   html +=  '<td>'+btn_edit+btn_delete+'</td></tr>';

                   $('#dataRow').append(html);
               }

            });
        }
    }
</script>