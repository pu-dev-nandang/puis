<div class="row">
    <div class="col-xs-12" >
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Penerimaan Pembayaran Intake</h4>
            </div>
            <div class="panel-body">
                <div class="thumbnail" style="padding: 10px;">
                    <b>Status : </b><i class="fa fa-circle" style="color:#8ED6EA;"></i> Paid Off 
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr/>
                        <div class="row">
                            <div class="col-xs-2" style="">
                              Angkatan
                              <select class="select2-select-00 col-xs-2 full-width-fix" id="selectTahun">
                                  <option></option>
                              </select>
                            </div>
                        </div>  
                         <br>
                        <div id = "pageTable">
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
      loadTahun();
      loadTableHeader(loadData);
    });

    $(document).on('change','#selectTahun', function () {
        loadTableHeader(loadData);
    });

    function loadTahun()
    {
        var thisYear = (new Date()).getFullYear();
        var startTahun = parseInt(thisYear);
        var selisih = (2018 < parseInt(thisYear)) ? parseInt(1) + (parseInt(thisYear) - parseInt(2018)) : 1;
        for (var i = 0; i <= selisih; i++) {
          var selected = (i==1) ? 'selected' : '';
          $('#selectTahun').append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
        }

        $('#selectTahun').select2({
         // allowClear: true
        });

        $('#selectStatus').select2({
          // allowClear: true
        });
    }


    function loadTableHeader(callback)
    {
        // Some code
        // console.log('test');
        $("#pageTable").empty();

        var table = '<table class="table table-bordered datatable2" id = "datatable2">'+
                    '<thead>'+
                    '<tr style="background: #333;color: #fff;">'+
                        // '<th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>'+
                        '<th>No</th>'+
                        '<th>Program Study</th>'+
                        '<th>Nama & Email</th>'+
                        '<th>Formulir Number</th>'+
                        '<th>Tagihan</th>'+
                        '<th>Cicilan</th>'+
                        '<th>Document</th>'+
                        '<th>Status</th>'+
                        '<th>Detail Payment</th>'+
                    '</tr>'+
                    '</thead>'+
                    '<tbody id="dataRow"></tbody>'+
                '</table>';
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
              url : base_url_js+"finance/getPayment_admission", // json datasource
              ordering : false,
              type: "post",  // method  , by default get
              data : {tahun : $("#selectTahun").val()},
              error: function(){  // error handling
                  $(".employee-grid-error").html("");
                  $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                  $("#employee-grid_processing").css("display","none");
              }
          },
          'createdRow': function( row, data, dataIndex ) {
                // console.log(data[6]);
                if(data[7] == 'Lunas')
                {
                  $(row).attr('style', 'background-color: #8ED6EA; color: black;');
                }
          },
      } );

      // Handle click on "Select all" control
      $('#example-select-all').on('click', function(){
         // Get all rows with search applied
         var rows = table.rows({ 'search': 'applied' }).nodes();
         // Check/uncheck checkboxes for all rows in the table
         $('input[type="checkbox"]', rows).prop('checked', this.checked);
      });

      // Handle click on checkbox to set state of "Select all" control
      $('#datatable2 tbody').on('change', 'input[type="checkbox"]', function(){
         // If checkbox is not checked
         if(!this.checked){
            var el = $('#example-select-all').get(0);
            // If "Select all" control is checked and has 'indeterminate' property
            if(el && el.checked && ('indeterminate' in el)){
               // Set visual state of "Select all" control
               // as 'indeterminate'
               el.indeterminate = true;
            }
         }
      });          
  }

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
                            '<th style="width: 55px;">Payment Date</th>'+
                            '<th style="width: 55px;">UpdateAt</th>';
      <?php if ($this->session->userdata('finance_auth_Policy_SYS') == 0): ?>
        table += '<th style="width: 55px;">Action</th>' ;                        
      <?php endif ?>                      
      table += '</tr>' ;  
      table += '</thead>' ; 
      table += '<tbody>' ;

      var url = base_url_js+'finance/getPayment_detail_admission2';
      var data = {
          ID_register_formulir : ID_register_formulir,
      };
      var token = jwt_encode(data,'UAP)(*');
      $.post(url,{token:token},function (resultJson) {
         var resultJson = jQuery.parseJSON(resultJson);
         var DetailPaymentArr = resultJson['data'];
         var action = resultJson['action'];
         var isi = '';
         for (var j = 0; j < DetailPaymentArr.length; j++) {
           var yy = (DetailPaymentArr[j]['Invoice'] != '') ? formatRupiah(DetailPaymentArr[j]['Invoice']) : '-';
           var status = (DetailPaymentArr[j]['Status'] == 0) ? 'Belum Bayar' : 'Sudah Bayar';
           var btn_bayar = '';
           if(action == 1)
           {
            btn_bayar = (DetailPaymentArr[j]['Status'] == 0) ? '<button class = "bayar" IDStudent = "'+DetailPaymentArr[j]['ID']+'" bayar = "1">Bayar</button>' : '<button class = "bayar" IDStudent = "'+DetailPaymentArr[j]['ID']+'" bayar = "0">Tidak Bayar</button>';
           }

           var PaymentDate = (DetailPaymentArr[j]['DatePayment'] == '' || DetailPaymentArr[j]['DatePayment'] == null || DetailPaymentArr[j]['DatePayment'] == '0000-00-00 00:00:00') ? '' : DetailPaymentArr[j]['DatePayment'];
           var Deadline = (DetailPaymentArr[j]['Deadline'] == '' || DetailPaymentArr[j]['Deadline'] == null || DetailPaymentArr[j]['Deadline'] == '0000-00-00 00:00:00') ? '' : DetailPaymentArr[j]['Deadline'];
           var UpdateAt = (DetailPaymentArr[j]['UpdateAt'] == '' || DetailPaymentArr[j]['UpdateAt'] == null || DetailPaymentArr[j]['UpdateAt'] == '0000-00-00 00:00:00') ? '' : DetailPaymentArr[j]['UpdateAt']
           
           isi += '<tr>'+
                 '<td>'+ (j+1) + '</td>'+
                 // '<td>'+ Nama + '</td>'+
                 '<td>'+ yy + '</td>'+
                 '<td>'+ DetailPaymentArr[j]['BilingID'] + '</td>'+
                 '<td>'+ status + '</td>'+
                 '<td>'+ Deadline + '</td>'+
                 '<td>'+ PaymentDate + '</td>'+
                 '<td>'+ UpdateAt + '</td>'+
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

  $(document).on('click','.bayar', function () {
      var IDStudent = $(this).attr('IDStudent');
      var bayar = $(this).attr('bayar');
      var idget = $(this).attr('IDStudent');
      if (bayar == 1) {
        var html = '<div class="col-xs-12">'+
                      '<div id="datetimepicker1'+idget+'" class="input-group input-append date datetimepicker">'+
                          '<input data-format="yyyy-MM-dd" class="form-control" id="tgl'+idget+'" type=" text" readonly="" value = "<?php echo date('Y-m-d') ?>">'+
                          '<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>'+
                      '</div>'+
                    '</div>';
        var btn_save = '<div class = "row" style = "margin-top : 10px"><div class = "col-xs-12"><button class = "btn btn-success save'+idget+'" idget = "'+idget+'">Save</button></div></div>';
        var rowhead = $( this )
          .closest('.row');
        var td = $( this )
          .closest('td')
          var htmlFirst = '';
        td
          .html(html+btn_save)

          $('#datetimepicker1'+idget).datetimepicker({
            format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
          });

          $('.save'+idget).click(function(){
            loading_button('.save'+idget);
            var url = base_url_js+'finance/bayar_manual_mahasiswa_admission';
            var data = {
                IDStudent : IDStudent,
                bayar : bayar,
                DatePayment :  $("#tgl"+idget).val(),
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
               // var resultJson = jQuery.parseJSON(resultJson);
               loadTableHeader(loadData);
               // $(".bayar[IDStudent='"+IDStudent+"']").remove();
               td.empty();
            }).fail(function() {
              toastr.info('No Action...'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {

            }); 
          })
      }
      else
      {
        loading_button(".bayar[IDStudent='"+IDStudent+"']");
        var url = base_url_js+'finance/bayar_manual_mahasiswa_admission';
        var data = {
            IDStudent : IDStudent,
            bayar : bayar,
        };
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (resultJson) {
           // var resultJson = jQuery.parseJSON(resultJson);
           loadTableHeader(loadData);
           $(".bayar[IDStudent='"+IDStudent+"']").remove();
        }).fail(function() {
          toastr.info('No Action...'); 
          // toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {

        }); 
      }
  });

  $(document).on('click','.show_a_href', function () {
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