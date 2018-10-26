<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="thumbnail" style="padding: 10px;">
    <b>Status : </b><i class="fa fa-circle" style="color:#8ED6EA;"></i> Paid Off 
</div>

<div class="row">
    <div class="col-md-12">
        <hr/>
        <div id = "pageTable">
          
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
      loadTableHeader(loadData);
    });

    function loadTableHeader(callback)
    {
        // Some code
        // console.log('test');
        $("#pageTable").empty();

        var table = '<div class="table-responsive"><table class="table table-bordered datatable2" id = "datatable2">'+
                    '<thead>'+
                    '<tr style="background: #333;color: #fff;">'+
                        // '<th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>'+
                        '<th></th>'+
                        '<th>Program Study</th>'+
                        '<th>Nama & Email</th>'+
                        '<th>Formulir Number</th>'+
                        '<th>Tagihan</th>'+
                        '<th>Cicilan</th>'+
                        '<th>Document</th>'+
                        '<th>Status</th>'+
                        '<th>Action</th>'+
                        // '<th>Detail Payment</th>'+
                    '</tr>'+
                    '</thead>'+
                    '<tbody id="dataRow"></tbody>'+
                '</table></div>';
        //$("#loadtableNow").empty();
        $("#pageTable").html(table);

        /*if (typeof callback === 'function') { 
            callback(); 
        }*/
        callback();
    }

  function loadData()
  {
      $.fn.dataTable.ext.errMode = 'throw';
      //alert('hsdjad');
      $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
      {
          return {
              "iStart": oSettings._iDisplayStart,
              "iEnd": oSettings.fnDisplayEnd(),
              "iLength": oSettings._iDisplayLength,
              "iTotal": oSettings.fnRecordsTotal(),
              "iFilteredTotal": oSettings.fnRecordsDisplay(),
              "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
              "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
          };
      };

      var table = $('#datatable2').DataTable( {
          "processing": true,
          "destroy": true,
          "serverSide": true,
          "iDisplayLength" : 10,
          "ordering" : false,
          "ajax":{
              url : base_url_js+"finance/getPayment_admission_edit_cicilan", // json datasource
              ordering : false,
              type: "post",  // method  , by default get
              error: function(){  // error handling
                  $(".employee-grid-error").html("");
                  $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                  $("#employee-grid_processing").css("display","none");
              }
          },
          'createdRow': function( row, data, dataIndex ) {
                // console.log(data[7]);
                if(data[7] == 'Lunas')
                {
                  $(row).attr('style', 'background-color: #8ED6EA; color: black;');
                }
          },
      } );

      // // Handle click on "Select all" control
      // $('#example-select-all').on('click', function(){
      //    // Get all rows with search applied
      //    var rows = table.rows({ 'search': 'applied' }).nodes();
      //    // Check/uncheck checkboxes for all rows in the table
      //    $('input[type="checkbox"]', rows).prop('checked', this.checked);
      // });

      // // Handle click on checkbox to set state of "Select all" control
      // $('#datatable2 tbody').on('change', 'input[type="checkbox"]', function(){
      //    // If checkbox is not checked
      //    if(!this.checked){
      //       var el = $('#example-select-all').get(0);
      //       // If "Select all" control is checked and has 'indeterminate' property
      //       if(el && el.checked && ('indeterminate' in el)){
      //          // Set visual state of "Select all" control
      //          // as 'indeterminate'
      //          el.indeterminate = true;
      //       }
      //    }
      // });          
  }

  $(document).on('click', '.btn_cancel_tui', function () {
        var arrValueCHK = [];
        arrValueCHK.push($(this).attr('id-register-formulir'));
        $('#NotificationModal .modal-body').html('<div style="text-align: center;"><p>Input Reason</p><input type="text" id="InputReason" class="form-control"><br>' +
            '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
            '</div>');
        $('#NotificationModal').modal('show');

        $("#confirmYes").click(function(){
            var InputReason = $("#InputReason").val();
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
            var url = base_url_js+'finance/admission/set_tuition_fee/delete_data';
            console.log(InputReason);
            var data = arrValueCHK;
            var token = jwt_encode(data,"UAP)(*");
            $.post(url,{token:token,InputReason : InputReason},function (data_json) {
                setTimeout(function () {
                   loadTableHeader(loadData);
                   $('#NotificationModal').modal('hide');
                },500);
            }).done(function() {
              
            }).fail(function() {
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {
              $('#NotificationModal').modal('hide');
            });
        })
  });  

  $(document).on('click','.btn-show', function () {
      var ID_register_formulir = $(this).attr('id-register-formulir');
      var Email = $(this).attr('email');
      var Nama = $(this).attr('nama');
      var url = base_url_js+"api/__getDocument";
      var data = {
          ID_register_formulir : ID_register_formulir,
          Email : Email,
      };
      var token = jwt_encode(data,"UAP)(*");
      $.post(url,{ token:token }, function (json) {
          $('#GlobalModal .modal-header').html('<h4 class="modal-title">'+'Document '+Nama+'</h4>');
          var table = '';
          table = '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
          '<thead>'+
              '<tr>'+
                  '<th style="width: 5px;">No</th>'+
                  '<th style="width: 55px;">Dokumen</th>'+
                  '<th style="width: 55px;">Required</th>'+
                  '<th style="width: 55px;">Attachment</th>'+
                  '<th style="width: 55px;">Status</th>';
            
          table += '</tr>' ;  
          table += '</thead>' ; 
          table += '<tbody>' ;
          for (var i =0; i < json.length; i++) {
            table += '<tr>'+
                        '<td>'+ (i+1)+'</td>'+
                        '<td>'+json[i]['DocumentChecklist'] +'</td>'+
                        '<td>'+json[i]['Required'] +'</td>'+
                        // '<td>'+'<a href = "<?php echo url_registration ?>document/'+Email+'/'+json[i]['Attachment']+'" target="_blank">File</a></td>'+
                        '<td>'+'<a href="javascript:void(0)" class="show_a_href" id = "show'+Email+'" filee = "'+json[i]['Attachment']+'" Email = "'+Email+'">File</a></td>'+
                        '<td>'+json[i]['Status'] +'</td>'
                        ; 
          }
           
          table += '</tbody>' ; 
          table += '</table>' ;
          var footer = '<div class="col-sm-12" id="BtnFooter">'+
                          '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
                        '</div>';
          $('#GlobalModal .modal-body').html(table);
          $('#GlobalModal .modal-footer').html(footer);
          $('#GlobalModal').modal({
              'show' : true,
              'backdrop' : 'static'
          });
      })
  });

  $(document).on('click','.btn-payment', function () {
      var ID_register_formulir = $(this).attr('id-register-formulir');
      var Nama = $(this).attr('Nama');
      var html = '';
      var table = '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
                    '<thead>'+
                        '<tr>'+
                            '<th style="width: 5px;">No</th>'+
                            '<th style="width: 55px;">Invoice</th>'+
                            '<th style="width: 55px;">BilingID</th>'+
                            '<th style="width: 55px;">Status</th>'+
                            '<th style="width: 55px;">Deadline</th>'+
                            '<th style="width: 55px;">UpdateAt</th>';
      <?php if ($this->session->userdata('finance_auth_Policy_SYS') == 0): ?>
        // table += '<th style="width: 55px;">Action</th>' ;                        
      <?php endif ?>                      
      table += '</tr>' ;  
      table += '</thead>' ; 
      table += '<tbody>' ;

      var url = base_url_js+'finance/getPayment_detail_admission';
      var data = {
          ID_register_formulir : ID_register_formulir,
      };
      var token = jwt_encode(data,'UAP)(*');
      $.post(url,{token:token},function (resultJson) {
         var DetailPaymentArr = jQuery.parseJSON(resultJson);
         
         var isi = '';
         for (var j = 0; j < DetailPaymentArr.length; j++) {
           var yy = (DetailPaymentArr[j]['Invoice'] != '') ? formatRupiah(DetailPaymentArr[j]['Invoice']) : '-';
           var status = (DetailPaymentArr[j]['Status'] == 0) ? 'Belum Bayar' : 'Sudah Bayar';
           var btn_bayar = (DetailPaymentArr[j]['Status'] == 0) ? '<button class = "bayar" IDStudent = "'+DetailPaymentArr[j]['ID']+'" bayar = "1">Bayar</button>' : '<button class = "bayar" IDStudent = "'+DetailPaymentArr[j]['ID']+'" bayar = "0">Tidak Bayar</button>';
           isi += '<tr>'+
                 '<td>'+ (j+1) + '</td>'+
                 // '<td>'+ Nama + '</td>'+
                 '<td>'+ yy + '</td>'+
                 '<td>'+ DetailPaymentArr[j]['BilingID'] + '</td>'+
                 '<td>'+ status + '</td>'+
                 '<td>'+ DetailPaymentArr[j]['Deadline'] + '</td>'+
                 '<td>'+ DetailPaymentArr[j]['UpdateAt'] + '</td>'+
                 <?php if ($this->session->userdata('finance_auth_Policy_SYS') == 0): ?>
                 '<td>'+ btn_bayar + '</td>'+
                 <?php endif ?>  
               '<tr>'; 
         }

         table += isi+'</tbody>' ; 
         table += '</table>' ;

         html += table;

         var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
             '';

         $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Detail Payment '+Nama+'</h4>');
         $('#GlobalModalLarge .modal-body').html(html);
         $('#GlobalModalLarge .modal-footer').html(footer);
         $('#GlobalModalLarge').modal({
             'show' : true,
             'backdrop' : 'static'
         });   

      }).fail(function() {
        toastr.info('No Action...'); 
        // toastr.error('The Database connection error, please try again', 'Failed!!');
      }).always(function() {

      });

  });

  $(document).on('click','#datatable2 .showModal', function () {
    var ID_register_formulir = $(this).attr('id-register-formulir');
    var html = '';
    var table = '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
                  '<thead>'+
                      '<tr>'+
                          '<th style="width: 5px;">No</th>'+
                          '<th style="width: 55px;">Note</th>'+
                          '<th style="width: 55px;">Rev By</th>'+
                          '<th style="width: 55px;">Rev At</th>';
    table += '</tr>' ;  
    table += '</thead>' ; 
    table += '<tbody>' ;

    var url = base_url_js+'finance/getRevision_detail_admission';
    var data = {
        ID_register_formulir : ID_register_formulir,
    };
    var token = jwt_encode(data,'UAP)(*');
    $.post(url,{token:token},function (resultJson) {
       var DetailArr = jQuery.parseJSON(resultJson);
       
       var isi = '';
       for (var j = 0; j < DetailArr.length; j++) {
         isi += '<tr>'+
                 '<td>'+DetailArr[j]['RevNo'] + '</td>'+
                 '<td>'+DetailArr[j]['Note'] + '</td>'+
                 '<td>'+DetailArr[j]['Name'] + '</td>'+
                 '<td>'+DetailArr[j]['RevAt'] + '</td>'+
              '<tr>'; 
       }

       table += isi+'</tbody>' ; 
       table += '</table>' ;

       html += table;

       var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
           '';

       $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Detail Revision</h4>');
       $('#GlobalModalLarge .modal-body').html(html);
       $('#GlobalModalLarge .modal-footer').html(footer);
       $('#GlobalModalLarge').modal({
           'show' : true,
           'backdrop' : 'static'
       });   

    }).fail(function() {
      toastr.info('No Action...'); 
      // toastr.error('The Database connection error, please try again', 'Failed!!');
    }).always(function() {

    });

  });

  $(document).on('change','#datatable2 input[type=checkbox]', function () {
    var Uniformvaluee = $(this).val();
    var Nama = $(this).attr('nama');
      if(this.checked) {
       var url = base_url_js + "get_detail_cicilan_fee_admisi";
       var data = {
           ID_register_formulir : Uniformvaluee,
       }
       var token = jwt_encode(data,"UAP)(*");
       $.post(url,{token:token},function (data_json) {
          var CanBeDelete = 1;
          // make html
          var mhtml = '<br><div id = "inputCicilan">'+
                        '<div class="widget box widget_'+Uniformvaluee+' widget_delete">'+
                            '<div class="widget-header">'+
                                '<h4 class="header"><i class="icon-reorder"></i>Edit Payment '+Nama+'</h4>'+
                            '</div>'+
                            '<div class="widget-content_'+Uniformvaluee+'">'+
                            '</div>'+
                            '<hr/>'+
                        '</div>'+
                      '</div>';
           $("#pageTable").append(mhtml);           
           var obj = JSON.parse(data_json);

           var DetailPayment = obj;
           // buat table cicilan beserta input
             var div = '';
             var enddiv = '</div>';
             var table = '';
             div = '<div id = "tblData" class="table-responsive">';
             table = '<table class="table table-striped table-bordered table-hover table-checkable tableData_'+Uniformvaluee+'">'+
             '<thead>'+
                 '<tr>';

             get_Invoice = 0; // deklaration total invoice
                
             for (var i = 0; i < DetailPayment.length; i++) {
                   var a = parseInt(i) + 1
                   table += '<th style="width: 75px;">'+'Cicilan '+a+'</th>' ;
                   get_Invoice = parseInt(get_Invoice) + parseInt(DetailPayment[i]['Invoice']);
             }
             // var n = get_Invoice.indexOf(".");
             // get_Invoice = get_Invoice.substring(0, n);  

             table += '<th style="width: 70px;">Note</th>';  
             table += '<th style="width: 70px;">Action</th>';  
             table += '</tr>' ;  
             table += '</thead>' ; 
             table += '<tbody>' ;  
             table += '</tbody>' ; 
             table += '</table>' ; 
           $(".widget-content_"+Uniformvaluee).html(div+table+enddiv);

               var tbodyTbl = '<tr>';
               totMin = 0;
               console.log(DetailPayment);
               for (var i = 0; i < DetailPayment.length; i++) {
                 var cicilan = parseInt(i) + 1;
                 var Cost = DetailPayment[i]['Invoice'];
                 var n = Cost.indexOf(".");
                 var Cost = Cost.substring(0, n);
                 if (DetailPayment[i]['Status'] == 1) {
                   totMin = parseInt(totMin) + parseInt(Cost);
                 }

                 if (CanBeDelete == 1) {
                   if (DetailPayment[i]['Status'] == 1) {
                       CanBeDelete = 0;
                   }
                 }

                 var Invoice = (DetailPayment[i]['Status'] == 1) ? '<label>'+formatRupiah(DetailPayment[i]['Invoice'])+'</label><br>' : '<input type="text" id = "cost_'+Uniformvaluee+i+'" value = "'+Cost+'" class = "form-control costInput_'+Uniformvaluee+'" cicilan = "'+cicilan+'" BilingID = "'+DetailPayment[i]['BilingID']+'" SID = "'+i+'" IDStudent = "'+DetailPayment[i]['ID']+'" get_Invoice = "'+get_Invoice+'" totMin = "'+totMin+'"><br>';
                 var Deadline = (DetailPayment[i]['Status'] == 1) ? '<label>'+DetailPayment[i]['UpdateAt']+'</label><br>Sudah Bayar' : 'Deadline<div id="datetimepicker'+i+'" class="input-group input-append date datetimepicker_'+Uniformvaluee+'">'+
               '<input data-format="yyyy-MM-dd hh:mm:ss" class="form-control datetimepickerClass_'+Uniformvaluee+'"  id="datetime_deadline'+Uniformvaluee+i+'" type="text" cicilan = "'+cicilan+'" value = "'+DetailPayment[i]['Deadline']+'" BilingID = "'+DetailPayment[i]['BilingID']+'" IDStudent = "'+DetailPayment[i]['ID']+'"></input>'+
               '<span class="input-group-addon add-on">'+
                 '<i data-time-icon="icon-time" data-date-icon="icon-calendar">'+
                 '</i>'+
               '</span>'+
           '</div>';
                 tbodyTbl += '<td>'+Invoice+Deadline+'</td>';
               }
               var btn_edit = '<span data-smt="" class="btn btn-xs btn-edit edit_'+Uniformvaluee+'">'+
                                    '<i class="fa fa-pencil-square-o"></i> Edit'+
                                   '</span>';
               // var btn_delete = '<span class="btn btn-xs btn-delete delete_'+Uniformvaluee+'">'+
               //                       '<i class="fa fa-trash"></i> Delete'+
               //
               var textArea = '<textarea rows="2" cols="5" name="textarea" class="limited form-control ket" id-formulir = "'+DetailPayment[0]['ID_register_formulir']+'"></textarea>'
               var btn_delete = '';                 
               tbodyTbl += '<td>'+textArea+'</td>';
               tbodyTbl += '<td>'+btn_edit+btn_delete+'</td>';
               tbodyTbl += '</tr>';
               $(".tableData_"+Uniformvaluee+" tbody").append(tbodyTbl);

               $('.datetimepicker_'+Uniformvaluee).datetimepicker();
               $('.datetimepickerClass_'+Uniformvaluee).prop('readonly',true);
               $('.costInput_'+Uniformvaluee).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
               $('.costInput_'+Uniformvaluee).maskMoney('mask', '9894');

               $('#cost_'+Uniformvaluee+0).focus();

               // event keyup
               $('.costInput_'+Uniformvaluee).keyup(function(){
                   var arrTemp = [];
                   var setMinimal = 500000;
                   // console.log(totMin);
                   // get_Invoice = get_Invoice - totMin ;
                   var total = $(this).attr('get_Invoice');
                   var setPengurangan = $(this).attr('totMin');
                   total = total - setPengurangan;
                   // console.log(setPengurangan);
                   $('.costInput_'+Uniformvaluee).each(function(){
                       var temp = findAndReplace($(this).val(), ".","");
                       /*if (temp < setMinimal) {
                           $(this).val(setMinimal);
                       }*/
                       var arr2 = {
                           id : $(this).attr('id'),
                           valuee : $(this).val(),
                       }
                       arrTemp.push(arr2);
                   })

                   // console.log(arrTemp);

                   var arrValue = [];
                   var count = 0;
                   for (var i = 0; i < arrTemp.length; i++) {
                       var temp = findAndReplace(arrTemp[i]['valuee'], ".","");
                       if (count > total) {
                           var splitcicilan = parseInt(total) / parseInt(arrTemp.length);
                           var splitcicilan = (splitcicilan < setMinimal) ? setMinimal : parseInt(splitcicilan);
                           var cost = 0;
                           var cost_value = splitcicilan;
                           for (var j = 0; j < arrTemp.length; j++) {

                              if (j == (arrTemp.length - 1)) {
                                  cost_value = parseInt(total) - parseInt(cost);
                              }
                              var getID = parseInt(j) + 1;
                              var IDCost = arrTemp[j]['id'];
                              $("#"+IDCost).val(cost_value);
                              cost = cost + cost_value; 
                           }
                       }
                       else
                       {
                           if ((arrTemp.length - 1) == i) {
                               var getID = parseInt(i) + 1;
                               var IDCost = arrTemp[i]['id'];
                               // console.log(IDCost);
                               $("#"+IDCost).val(parseInt(total) - parseInt(count));
                           }
                       }
                       count += parseInt(temp);
                   }
                   $('.costInput_'+Uniformvaluee).maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
                   $('.costInput_'+Uniformvaluee).maskMoney('mask', '9894');
               });

               // event click_edit
               $('.edit_'+Uniformvaluee).click(function(){
                  loading_button('.edit_'+Uniformvaluee); 
                  // get all input
                  // get keterangan
                  var keterangan = $('.ket[id-formulir = "'+Uniformvaluee+'"]').val();
                  console.log(keterangan);
                   var arrTemp = [];
                   $('.costInput_'+Uniformvaluee).each(function(){
                       var Invoice = $(this).val();
                       for(i = 0; i <Invoice.length; i++) {
                        Invoice = Invoice.replace(".", "");
                       }
                       var BilingID =  $(this).attr('BilingID')
                       var SID = $(this).attr('SID');
                       var Deadline = $("#datetime_deadline"+Uniformvaluee+SID).val();
                       var cicilan = $(this).attr('cicilan');
                       var ID = $(this).attr('IDStudent');
                       data = {
                         Invoice : Invoice,
                         BilingID  : BilingID,
                         Deadline       : Deadline,
                         cicilan     : cicilan,
                         ID : ID,
                       }
                       arrTemp.push(data);
                   })

                   // console.log(arrTemp);
                   // check cicilan != 0 dan Deadline is empty
                     var bool = true;
                     var msg = '';
                     for (var i = 0; i < arrTemp.length; i++) {
                       if (arrTemp[i].Invoice == 0) {
                         msg = 'Price Cicilan tidak boleh 0';
                         bool = false
                         break;
                       }

                       if (arrTemp[i].Deadline == "") {
                         msg = 'Deadline belum diisi';
                         bool = false
                         break;
                       }  
                     }

                     if (bool) {
                       // console.log(arrTemp);
                       // hitung tanggal tidak boleh melewati cicilan sebelumnya
                         var bool2 = true;
                         for (var i = 0; i < arrTemp.length; i++) {
                           var date1 = arrTemp[i].Deadline;
                           date1 = date1.substring(0, 10);
                            for (var j = 0; j < arrTemp.length; j++) {
                             if (i < j) {
                                var date2 = arrTemp[j].Deadline;
                                date2 = date2.substring(0, 10);

                                var startDate = moment(date1, "YYYY-MM-DD");
                                var endDate = moment(date2, "YYYY-MM-DD");
                                var result = endDate.diff(startDate, 'days');
                                result = parseInt(result);
                                // console.log(result);
                                if (result <= 0) {
                                 bool2 = false;
                                 // console.log('i ' + date1 + '< j : ' + date2);
                                 break;
                                } 
                             }
                             
                            }

                            if (!bool2) {
                               break;
                               // console.log('i < j');
                            }

                         }
                       // hitung tanggal tidak boleh melewati cicilan sebelumnya

                       if (bool2) {
                         // console.log('ok');
                         var url = base_url_js + "finance/admission/approved/edit_submit";
                         var data = arrTemp
                         var token = jwt_encode(data,"UAP)(*");
                         $.post(url,{token:token,keterangan : keterangan,ID_register_formulir : Uniformvaluee},function (data_json) {
                             // jsonData = data_json;
                             var obj = JSON.parse(data_json); 
                             if(obj != ''){
                                 $('.edit_'+Uniformvaluee).prop('disabled',false).html('<i class="fa fa-pencil-square-o"></i> Edit');
                                 toastr.error(obj, 'Failed!!');
                             }
                             else
                             {
                                 //window.location.reload(true);
                                 $(".widget_delete").remove();
                                 loadTableHeader(loadData); 
                                 toastr.success('Data berhasil disimpan', 'Success!');
                             }

                         }).done(function() {
                           // $('#btn-Save').prop('disabled',false).html('Submit');
                         }).fail(function() {
                           $('.edit_'+Uniformvaluee).prop('disabled',false).html('<i class="fa fa-pencil-square-o"></i> Edit');
                           toastr.error('The Database connection error, please try again', 'Failed!!');
                         }).always(function() {
                          $('.edit_'+Uniformvaluee).prop('disabled',false).html('<i class="fa fa-pencil-square-o"></i> Edit');
                         });
                         // $('.edit_'+Uniformvaluee).prop('disabled',false).html('<i class="fa fa-pencil-square-o"></i> Edit');
                       } else {
                         toastr.error('Tanggal Deadline cicilan tidak boleh mendahului tanggal cicilan sebelumnya', 'Failed!!');
                         $('.edit_'+Uniformvaluee).prop('disabled',false).html('<i class="fa fa-pencil-square-o"></i> Edit');
                       }

                     } else {
                       toastr.error(msg, 'Failed!!');
                       $('.edit_'+Uniformvaluee).prop('disabled',false).html('<i class="fa fa-pencil-square-o"></i> Edit');
                     }
               }); // exit edit click function
           
       }).done(function() {
         
       }).fail(function() {
        
         toastr.error('The Database connection error, please try again', 'Failed!!');
       }).always(function() {
        
       });
    }
    else
    {
        $(".widget_"+Uniformvaluee).remove();
    }

  });

  $(document).on('click','.show_a_href', function () {
    console.log('test');
      var file__  = $(this).attr('filee');
      var emaiil  = $(this).attr('Email');
      var aaa = file__.split(",");
      // console.log(aaa);
      if (aaa.length > 0) {
          // var emaiil = $(this).attr('Email');
          for (var i = 0; i < aaa.length; i++) {
              // window.open('<?php echo url_registration ?>'+'uploads/document/'+NPM+'/'+aaa[i],'_blank');
              // window.open('<?php echo url_registration ?>'+'document/'+emaiil+'/'+aaa[i],'_blank', 'modal=yes');
              window.open('<?php echo url_registration ?>'+'document/'+emaiil+'/'+aaa[i],'_blank');
          }
          
      }
      else
      {
          // window.open('<?php echo url_pas ?>'+'uploads/document/'+NPM+'/'+file__,'_blank');
          window.open('<?php echo url_registration ?>'+'document/'+emaiil+'/'+file__,'_blank');
      }
      
  });
</script>