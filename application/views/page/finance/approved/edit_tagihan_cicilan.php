
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

        var table = '<table class="table table-bordered datatable2" id = "datatable2">'+
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
                        // '<th>Detail Payment</th>'+
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
                if(data[6] == 'Lunas')
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
                        '<td>'+'<a href = "<?php echo url_registration ?>document/'+Email+'/'+json[i]['Attachment']+'" target="_blank">File</a></td>'+
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
        table += '<th style="width: 55px;">Action</th>' ;                        
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

  $(document).on('click','.bayar', function () {
      var IDStudent = $(this).attr('IDStudent');
      var bayar = $(this).attr('bayar');
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
           // jsonData = data_json;
           var obj = JSON.parse(data_json);
           console.log(obj);
           var bbb = '';
           for (var i = 0; i < obj.length; i++) {
               bbb += '<tr>'+
                         '<td>'+ (parseInt(i)+1) + '</td>'+
                         '<td>'+ formatRupiah(obj[i]['Invoice']) + '</td>'+
                         '<td>'+ obj[i]['Deadline']+'</td>'+
                       '</tr>';  
           }
           var aaa = '<!--<div class = "row">-->'+
                        '<div id = "tblData" class="table-responsive">'+
                            '<table class="table table-striped table-bordered table-hover table-checkable">'+
                            '<thead>'+
                              '<tr>'+
                                '<th style="width: 5px;">Cicilan ke </th>'+
                                '<th style="width: 5px;">Invoice </th>'+
                                '<th style="width: 5px;">Deadline </th>'+
                                //  '<th style="width: 5px;">Action </th>'+
                               '<tr>'+ 
                            '</thead>'+
                            '<tbody>'+
                            bbb+
                            '</tbody>'+'</table></div>'+
                     '<!--</div>-->';

           var html = '<br><div class="widget box widget_'+Uniformvaluee+' widget_delete">'+
               '<div class="widget-header">'+
                   '<h4 class="header"><i class="icon-reorder"></i> Detail Cicilan '+Nama+'</h4>'+
               '</div>'+
               '<div class="widget-content">'+
                   aaa
               '</div>'+
           '</div>';
           $("#pageTable").append(html);
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
</script>