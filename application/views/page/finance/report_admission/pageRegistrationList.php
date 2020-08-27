<div class="row" style="margin-top: 30px;">
    <div class="col-md-12 formAddFormKD">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> Registration List </h4>
            </div>
            <div class="widget-content">
                <!--  -->
                <div class="row">
                    <div id="panel_web" class="" style="padding:30px;padding-top:0px;">
                         <div class="thumbnail" style="padding: 10px;">
                             <b>Status : </b><i class="fa fa-circle" style="color:#8ED6EA;"></i> Paid Off
                         </div>
                         <br>
                         <div class="row">
                            <div class="col-md-2">
                              <label>Angkatan</label>
                              <select class="select2-select-00 col-md-2 full-width-fix" id="selectTahun">
                                  <option></option>
                              </select>
                            </div>
                            <div class="col-md-2">
                              <label>Online/Offline</label>
                              <select class="form-control" id="selectFormulirType">
                                  <option value="%" >All</option>
                                  <option value="0" >Online</option>
                                  <option value="1" >Offline</option>
                              </select>
                            </div>
                            <div class="col-md-2">
                              <label>Status Payment</label>
                              <select class="form-control" id="selectStatusPayment">
                                  <option value="%" >All</option>
                                  <option value="-" >Tagihan Belum di set</option>
                                  <option value="Lunas" >Lunas</option>
                                  <option value="-100" >Belum Bayar Formulir</option>
                                  <option value="100" >Sudah Bayar Formulir</option>
                                  <option value="Intake" >Intake</option>
                                  <option value="Refund" >Refund</option>
                              </select>
                            </div>
                          </div>
                         <br>
                         <div class="row">
                           <div class="col-md-12">
                             <p  style="color: red;">* <i class="fa fa-circle" style="color:#bdc80e;"></i> <span style="color: #bdc80e;">Online</span></p>
                             <p  style="color: red;"> * <i class="fa fa-circle" style="color:#db4273;"></i> <span style="color: #db4273;">Offline</span></p>
                           </div>
                         </div>
                         <div class = "row">
                          <div class = "col-md-12">
                            <div class = "table-responsive">
                                <div id="dataPageLoad" style="margin-top:0px;">

                                </div>
                            </div>
                          </div>
                        </div>

                        <!-- <div  class="col-md-12" align="right" id="pagination_link"></div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  window.pageHtml = '';
  window.temp = '';
  var oTable1;
  var oTable2;
  function loadTahun()
  {
      var academic_year_admission = "<?php echo $academic_year_admission ?>";
      var thisYear = (new Date()).getFullYear();
      // var startTahun = parseInt(thisYear);
      var startTahun = 2019;
      var selisih = (2018 < parseInt(thisYear)) ? parseInt(2) + (parseInt(thisYear) - parseInt(2018)) : 2;
      for (var i = 0; i <= selisih; i++) {
        var selected = (( parseInt(startTahun) + parseInt(i) )==academic_year_admission) ? 'selected' : '';
        $('#selectTahun').append('<option value="'+ ( parseInt(startTahun) + parseInt(i) ) +'" '+selected+'>'+( parseInt(startTahun) + parseInt(i) )+'</option>');
      }

      $('#selectTahun').select2({
       // allowClear: true
      });

      $('#selectStatus').select2({
        // allowClear: true
      });
  }

  $(document).ready(function () {
      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
          $('#panel_web').addClass('wrap');
          $('#panel_web').css({"padding": "0px", "padding-right": "20px"});
      }
      loadTahun();
      loadPage('data-calon-mhs/1');
  });

  $(document).on('change','#selectTahun,#selectStatusPayment,#selectFormulirType', function () {
      // loadPage(pageHtml+'/1');
      var res = pageHtml.split("/");
      if (res[0] == 'data-calon-mhs') {
        // enable tagihan belum di set
        var selector = $('#selectStatusPayment');
        selector.find('option[value="-"]').attr('disabled',false);
        oTable1.ajax.reload( null, false );
      }
      
  });


  function loadPage(page) {
      $("#dataPageLoad").empty();
      var res = page.split("/");
      switch(res[0]) {
          case 'data-calon-mhs':
            var selector = $('#selectStatusPayment');
            selector.find('option[value="-"]').attr('disabled',false);
            selector.find('option[value="-100"]').attr('disabled',false);
            selector.find('option[value="100"]').attr('disabled',false);
            selector.find('option[value="Intake"]').attr('disabled',false);
            selector.find('option[value="Refund"]').attr('disabled',false);
             $("#dataPageLoad").empty();
             var table = '<table class="table table-bordered datatable2" id = "datatable2">'+
                         '<thead>'+
                         '<tr style="background: #333;color: #fff;">'+
                             // '<th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>'+
                             '<th>No</th>'+
                             '<th>Nama,Email,Phone & Sekolah</th>'+
                             '<th>Prody,Formulir & VA</th>'+
                             '<th>Sales</th>'+
                             '<th>Rangking</th>'+
                             '<th>Beasiswa</th>'+
                             '<th>Document & Exam</th>'+
                             '<th width = "13%">Tagihan</th>'+
                             '<th>Detail Payment</th>'+
                             '<th>Status</th>'+
                             '<th>RegisterAt</th>'+
                         '</tr>'+
                         '</thead>'+
                         '<tbody id="dataRow"></tbody>'+
                     '</table>'
                     ;
             //$("#loadtableNow").empty();
             $("#dataPageLoad").html(table);

             // server side
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
                     url : window.location.href, // json datasource
                     ordering : false,
                     // data : {tahun : $("#selectTahun").val()},
                     data : function(datapost){
                                // Read values
                          var tahunValue =$("#selectTahun option:selected").val() ;
                          var selectFormulirType =$("#selectFormulirType option:selected").val() ;
                          var selectStatusPayment =$("#selectStatusPayment option:selected").val() ;
                                // Append to data
                                datapost.tahun = tahunValue;
                                datapost.FormulirType = selectFormulirType;
                                datapost.StatusPayment = selectStatusPayment;
                          },
                     type: "post",  // method  , by default get
                     error: function(){  // error handling
                         $(".employee-grid-error").html("");
                         $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                         $("#employee-grid_processing").css("display","none");
                     }
                 },
                 'createdRow': function( row, data, dataIndex ) {
                       if(data[9] == 'Lunas')
                       {
                         $(row).attr('style', 'background-color: #8ED6EA; color: black;');
                       }
                 },
             } );
             oTable1 = table;
             pageHtml = 'data-calon-mhs';
              break;
          default:
              'code block'
      }

  }

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
         let dataRefund = [];
         var isi = '';
         for (var j = 0; j < DetailPaymentArr.length; j++) {

          // declare refund data
            if (j == 0 && DetailPaymentArr[0]["Refund"] !== undefined) {
              dataRefund = DetailPaymentArr[0]["Refund"]
            }

           var yy = (DetailPaymentArr[j]['Invoice'] != '') ? formatRupiah(DetailPaymentArr[j]['Invoice']) : '-';
           var status = (DetailPaymentArr[j]['Status'] == 0) ? 'Belum Bayar' : 'Sudah Bayar';
           isi += '<tr>'+
                 '<td>'+ (j+1) + '</td>'+
                 // '<td>'+ Nama + '</td>'+
                 '<td>'+ yy + '</td>'+
                 '<td>'+ DetailPaymentArr[j]['BilingID'] + '</td>'+
                 '<td>'+ status + '</td>'+
                 '<td>'+ DetailPaymentArr[j]['Deadline'] + '</td>'+
                 '<td>'+ DetailPaymentArr[j]['UpdateAt'] + '</td>'+
               '<tr>';
         }

         table += isi+'</tbody>' ;
         table += '</table>' ;

         html += table;

         if (dataRefund.length > 0) {
              html  += '<br/>';
              html += '<div class = "row">'+
                   '<div class = "col-md-12">'+
                     '<div class = "well" style = "padding:10px;">'+
                         '<div class = "row">'+
                           '<div class = "col-md-12">'+
                             '<h4 style = "color:red;">Refund</h4>'+
                           '</div>'+
                           '<div class = "col-md-12">'+
                             '<table class = "table">'+
                               '<tr>'+
                                 '<td style = "font-weight:bold;">Price</td>'+
                                 '<td>:</td>'+
                                 '<td style = "color:blue;">'+formatRupiah(dataRefund[0].Price)+'</td>'+
                               '</tr>'+
                               '<tr>'+
                                 '<td style = "font-weight:bold;">Desc</td>'+
                                 '<td>:</td>'+
                                 '<td style = "color:blue;">'+dataRefund[0].Desc+'</td>'+
                               '</tr>'+
                               '<tr>'+
                                 '<td style = "font-weight:bold;">By</td>'+
                                 '<td>:</td>'+
                                 '<td style = "color:blue;">'+dataRefund[0].NameEMP+'</td>'+
                               '</tr>'+
                               '<tr>'+
                                 '<td style = "font-weight:bold;">At</td>'+
                                 '<td>:</td>'+
                                 '<td style = "color:blue;">'+dataRefund[0].UpdateAt+'</td>'+
                               '</tr>'+
                             '</table>'+
                           '</div>'+
                         '</div>'+
                     '</div>'+
                   '</div>'+
                 '</div>';
         }
         

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

  $(document).on('click','.btn-show', function () {
      var ID_register_formulir = $(this).attr('id-register-formulir');
      var Email = $(this).attr('email');
      var Nama = $(this).attr('nama');
      var url = base_url_js+"api/__getDocument2";
      var data = {
          ID_register_formulir : ID_register_formulir,
          Email : Email,
      };
      var token = jwt_encode(data,"UAP)(*");
      $.post(url,{ token:token }, function (json) {
          var doc = json['doc'];
          $('#GlobalModal .modal-header').html('<h4 class="modal-title">'+'Document '+Nama+'</h4>');
          var html = '<div class = "row">'+
                        '<div class ="col-md-12">';
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
          for (var i =0; i < doc.length; i++) {
            table += '<tr>'+
                        '<td>'+ (i+1)+'</td>'+
                        '<td>'+doc[i]['DocumentChecklist'] +'</td>'+
                        '<td>'+doc[i]['Required'] +'</td>'+
                        // '<td>'+'<a href = "<?php echo url_registration ?>document/'+Email+'/'+json[i]['Attachment']+'" target="_blank">File</a></td>'+
                        '<td>'+'<a href="javascript:void(0)" class="show_a_href" id = "show'+Email+'" filee = "'+doc[i]['Attachment']+'" Email = "'+Email+'">File</a></td>'+
                        '<td>'+doc[i]['Status'] +'</td>'
                        ;
          }

          table += '</tbody>' ;
          table += '</table>' ;
          html += table;
          html += '</div></div>'; // end document

          var ujian = json['ujian'];
          if (ujian.length > 0) {
            var kelulusan = json['kelulusan'];
            html += '<div class= "row" style = "margin-top ; 5px">'+
                      '<div class = "col- md-12">'+
                        '<h5>Exam Result</h5>'+
                        '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<th style="width: 5px;">No</th>'+
                                            '<th style="width: 55px;">Exam Name</th>'+
                                            '<th style="width: 55px;">Bobot</th>'+
                                            '<th style="width: 55px;">Value</th>';
            html += '</tr>' ;
            html += '</thead>' ;
            html += '<tbody>' ;

            var jmlbobot = 0;
            var Nilai_bobot = 0;
            var nilai = 0;
            for (var i =0; i < ujian.length; i++) {
              html += '<tr>'+
                          '<td>'+ (i+1)+'</td>'+
                          '<td>'+ujian[i]['NamaUjian'] +'</td>'+
                          '<td>'+ujian[i]['Bobot'] +'</td>'+
                          '<td>'+ujian[i]['Value'] +'</td>';

              jmlbobot = parseInt(jmlbobot)  + parseInt(ujian[i]['Bobot']);
              Nilai_bobot = parseInt(Nilai_bobot) + ( (parseInt(ujian[i]['Value']) * parseInt(ujian[i]['Bobot']) ) )
            }

             nilai = parseInt(Nilai_bobot) / parseInt(jmlbobot);

            html += '</tbody>' ;
            html += '</table>' ;

            html += '<p>Jumlah Bobot : '+jmlbobot+'</p>'+
                    '<p>Nilai * Bobot : '+Nilai_bobot+'</p>'+
                    '<p>Nilai Akhir : '+nilai+'</p>'+
                    '<p>Status : '+kelulusan[0]['Kelulusan']+'</p>';

          }

          var footer = '<div class="col-sm-12" id="BtnFooter">'+
                          '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
                        '</div>';
          $('#GlobalModal .modal-body').html(html);
          $('#GlobalModal .modal-footer').html(footer);
          $('#GlobalModal').modal({
              'show' : true,
              'backdrop' : 'static'
          });
      })
  });

  $(document).on('click','.show_a_href', function () {
      var file__  = $(this).attr('filee');
      var emaiil  = $(this).attr('Email');
      var aaa = file__.split(",");
      // console.log(aaa);
      if (aaa.length > 0) {
          for (var i = 0; i < aaa.length; i++) {
              window.open('<?php echo url_registration ?>'+'document/'+emaiil+'/'+aaa[i],'_blank');
          }

      }
      else
      {
          window.open('<?php echo url_registration ?>'+'document/'+emaiil+'/'+file__,'_blank');
      }

  });

  $(document).on('click','.Detail', function () {
      var ID_register_formulir = $(this).attr('id-register-formulir');
      var Nama = $(this).attr('nama');
      var url = base_url_js+"admission/detailPayment";
      var data = {
          ID_register_formulir : ID_register_formulir,
      };
      var token = jwt_encode(data,"UAP)(*");
      $.post(url,{ token:token }, function (data) {
          var json = jQuery.parseJSON(data);
          $('#GlobalModal .modal-header').html('<h4 class="modal-title">'+'Detail Payment '+Nama+'</h4>');

          var table = '<table class="table table-striped table-bordered table-hover table-checkable tableData">' +
          '<thead>'+
              '<tr>'+
                  '<th style="width: 5px;">No</th>'+
                  '<th style="width: 55px;">Type</th>'+
                  '<th style="width: 55px;">Discount</th>'+
                  '<th style="width: 55px;">Value</th>'+
                  '<th style="width: 55px;">Status</th>';

          table += '</tr>' ;
          table += '</thead>' ;
          table += '<tbody>' ;
          var payment_register = json.payment_register;
          for (var i =0; i < payment_register.length; i++) {
            table += '<tr>'+
                        '<td>'+ (i+1)+'</td>'+
                        '<td>'+payment_register[i]['Description'] +'</td>'+
                        '<td>'+payment_register[i]['Discount'] +'</td>'+
                        '<td>'+payment_register[i]['Pay_tuition_fee'] +'</td>'+
                        '<td>'+payment_register[i]['Status'] +'</td>'+
                      '<tr>'
                        ;
          }

          table += '</tbody>' ;
          table += '</table>' ;

          var table2 = '';
          table2 = '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
          '<thead>'+
              '<tr>'+
                  '<th style="width: 5px;">No</th>'+
                  '<th style="width: 55px;">Cicilan ke</th>'+
                  '<th style="width: 55px;">BilingID</th>'+
                  '<th style="width: 55px;">Invoice</th>'+
                  '<th style="width: 55px;">Status</th>'+
                  '<th style="width: 55px;">Deadline</th>';
          table2 += '</tr>' ;
          table2 += '</thead>' ;
          table2 += '<tbody>' ;

          var payment_pre = json.payment_pre;
          for (var i =0; i < payment_pre.length; i++) {
            table2 += '<tr>'+
                        '<td>'+ (i+1)+'</td>'+
                        '<td>'+ 'Cicilan ke '+(i+1)+'</td>'+
                        '<td>'+payment_pre[i]['BilingID'] +'</td>'+
                        '<td>'+payment_pre[i]['Invoice'] +'</td>'+
                        '<td>'+payment_pre[i]['Status'] +'</td>'+
                        '<td>'+payment_pre[i]['Deadline'] +'</td>'+
                      '<tr>'
                        ;
          }

          table2 += '</tbody>' ;
          table2 += '</table>' ;

          var footer = '<div class="col-sm-12" id="BtnFooter">'+
                          '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
                        '</div>';
          $('#GlobalModal .modal-body').html(table+table2);
          $('#GlobalModal .modal-footer').html(footer);
          $('#GlobalModal').modal({
              'show' : true,
              'backdrop' : 'static'
          });
      })
  });
</script>