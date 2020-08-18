<link href="<?php echo base_url('assets/custom/jquery-ui.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-12 formAddFormKD">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> <?php echo $NameMenu ?></h4>
                <!-- <div class="toolbar no-padding">
                    <div class="btn-group">
                      <span data-smt="" class="btn btn-xs btn-add">
                        <i class="icon-plus"></i> Add
                       </span>
                    </div>
                </div> -->
            </div>
            <div class="widget-content">
                <!--  -->
                <div class="row">
                    <div id="panel_web" class="" style="padding:30px;padding-top:0px;">
                         <ul class="nav nav-tabs">
                            <li role="presentation" class="active"><a href="javascript:void(0)" class="tab-btn-submenu-page" data-page="data-calon-mhs/1">Data</a></li>
                            <li role="presentation"><a href="javascript:void(0)" class="tab-btn-submenu-page" data-page="to-be-mhs/1">To Be Student</a></li>
                            <!--<li role="presentation"><a href="javascript:void(0)" class="tab-btn-tuition-fee" data-page="tuition_fee_delete/1">Register</a></li>
                            <li role="presentation"><a href="javascript:void(0)" class="tab-btn-tuition-fee" data-page="tuition_fee_delete/1">Register Recycled</a></li>-->
                         </ul>
                         <br>
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
                              </select>
                            </div>
                          </div>
                         <br>
                         <div class="row">
                           <div class="col-md-12">
                             <p style="color: red;">* Detail resend email : history resend email tentang pembayaran formulir</p>
                             <p style="color: red;">* Resend email : Resend email tentang pembayaran formulir</p>
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

    $('.tab-btn-submenu-page').click(function () {
        var page = $(this).attr('data-page');

        $('li[role=presentation]').removeClass('active');
        $(this).parent().addClass('active');
        loadPage(page);
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
        else if (res[0] == 'to-be-mhs') {
          // disable tagihan belum di set
          var selector = $('#selectStatusPayment');
          selector.find('option[value="-"]').attr('disabled',true);
          oTable2.ajax.reload( null, false );
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
                               '<th width = "17%"><i class="fa fa-cog"></i></th>'+
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
                       url : base_url_js+"admission/proses-calon-mahasiswa/getDataPersonal_Candidate", // json datasource
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
            case 'to-be-mhs':
                $("#dataPageLoad").empty();
                var selector = $('#selectStatusPayment');
                selector.find('option[value="-"]').attr('disabled',true);
                selector.find('option[value="-100"]').attr('disabled',true);
                selector.find('option[value="100"]').attr('disabled',true);
                selector.find('option[value="Intake"]').attr('disabled',true);
                var S_StatusPayment = selector.find('option:selected').val();
                // console.log(S_StatusPayment);
                if (S_StatusPayment == '-' || S_StatusPayment == '-100' || S_StatusPayment == '100' || S_StatusPayment == 'Intake'  ) {
                  // selector.find('option[value="%"]').attr('selected',true);
                  $("#selectStatusPayment option").filter(function() {
                     //may want to use $.trim in here
                     return $(this).val() == '%'; 
                  }).prop("selected", true);
                }
                var table = ''+
                            '<table class="table table-bordered datatable2" id = "datatable2">'+
                            '<thead>'+
                            '<tr style="background: #333;color: #fff;">'+
                                // '<th></th>'+
                                '<th>No <input type="checkbox" name="select_all" value="1" id="example-select-all"></th>'+
                                '<th>Nama,Email & Sekolah</th>'+
                                '<th>Prody,Formulir & VA</th>'+
                                '<th>Sales</th>'+
                                '<th>Rangking</th>'+
                                '<th>Beasiswa</th>'+
                                '<th>Document</th>'+
                                '<th width = "13%">Tagihan</th>'+
                                '<th>Detail Payment</th>'+
                                '<th>Status Payment</th>'+
                            '</tr>'+
                            '</thead>'+
                            '<tbody id="dataRow"></tbody>'+
                        '</table><br>'+
                        '<div class = "row">'+
                            '<div class = "col-md-12">'+
                              '<div class="thumbnail" style="min-height: 120px;padding: 10px;">'+
                                '<h4 class="header"><i class="icon-reorder"></i> Generate to be Student</h4>'+
                                '<div class = "col-md-3"> Pilih Semester'+
                                      '<select class="form-control" id="selectSemester">'+
                                      '</select>'+
                                '</div>'+
                                '<div class = "col-md-3"> <br>'+
                                      '<button class = "btn btn-success btn-edit" id = "generateToBEMhs">Generate</button>'+
                                '</div>'+
                              '</div>'+
                            '</div>'+
                        '</div>';
                //$("#loadtableNow").empty();
                $("#dataPageLoad").html(table);

                var table = $('#datatable2').DataTable( {
                    "processing": true,
                    "destroy": true,
                    "serverSide": true,
                    "iDisplayLength" : 10,
                    "ordering" : false,
                    "ajax":{
                        url : base_url_js+"admission/proses-calon-mahasiswa/getDataPersonal_Candidate_to_be_mhs", // json datasource
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
                           // console.log(data);
                          if(data[9] == 'Lunas')
                          {
                            $(row).attr('style', 'background-color: #8ED6EA; color: black;');
                          }
                    },
                } );

                oTable2 = table;

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

                $("#generateToBEMhs").click(function(){
                  let btnGenerate = $(this);
                    var checkboxArr = [];
                     table.$('input[type="checkbox"]').each(function(){
                       if(this.checked){
                          if(this.value != '')
                          {
                            checkboxArr.push(this.value);
                          }

                       }

                     });

                     if (checkboxArr.length > 0) {
                          if (confirm('Are you sure ?')) {
                            oTable2.$('input[type="checkbox"]').attr('disabled',true);
                            btnGenerate.attr('disabled',true);
                            // return;
                            loading_modal_show();

                             var url = base_url_js+'admission/proses-calon-mahasiswa/generate_to_be_mhs';
                             var data = {
                                         checkboxArr : checkboxArr,
                                         Semester : $('#selectSemester').val(),
                                        };
                             var token = jwt_encode(data,"UAP)(*");
                              $.post(url,{token:token},function (data_json) {
                                 // $('#generateToBEMhs').prop('disabled',false).html('Generate');
                                 var json = jQuery.parseJSON(data_json);
                                 if(json == 'Error'){
                                  toastr.error('Please check the completeness of the data', 'Failed!!');
                                 }
                                 else if(json == '')
                                 {
                                  toastr.success('Generate ok', 'Success!');
                                 }
                                 else
                                 {
                                  toastr.success('Proses Finish', 'Success!');
                                 }
                                loading_modal_hide();
                             }).done(function() {
                               loadPage('to-be-mhs/1');
                               loading_modal_hide();
                             }).fail(function() {
                               toastr.error('Please check the completeness of the data', 'Failed!!');
                               loading_modal_hide();
                             }).always(function() {
                              oTable2.$('input[type="checkbox"]').attr('disabled',false);
                              btnGenerate.attr('disabled',false);
                              loadPage('to-be-mhs/1');
                              loading_modal_hide();

                             });
                          }

                     }
                     else
                     {
                         toastr.error("Silahkan checked dahulu", 'Failed!!');
                     }

                }) // exit click function
                var academic_year_admission = "<?php echo $academic_year_admission ?>";
                loadSelectOptionSemester_admission('#selectSemester',academic_year_admission);
                pageHtml = 'to-be-mhs';

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

           var isi = '';
           for (var j = 0; j < DetailPaymentArr.length; j++) {
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

    function submit(action, method, values) {
        var form = $('<form/>', {
            action: action,
            method: method
        });
        $.each(values, function() {
            form.append($('<input/>', {
                type: 'hidden',
                name: this.name,
                value: this.value
            }));
        });
        form.attr('target', '_blank');
        form.appendTo('body').submit();
    }

    $(document).on("click", "#ModalbtnResendEmail", function(event){
      var selector = $(this);
      //903
        if (confirm('Are you sure ? ')) {
          var url = base_url_js+"admission/ResendEmail";
          var data = {
              RegisterID : selector.attr('registerid'),
          };
          var token = jwt_encode(data,"UAP)(*");
          loading_button2(selector);
          $.post(url,{ token:token }, function (data) {
            // action
            if (data.status == 1) {
              toastr.success('Email Send');
              $('#GlobalModalLarge').modal('hide');
            }
            else
            {
              toastr.info('Connection time out error,try again');
            }
            end_loading_button2(selector,'Send');

          }).fail(function(xhr, status, error) {
            end_loading_button2(selector,'Send');
            toastr.info('Connection time out error,try again');
          });
        }
        
    });

    $(document).on("click", ".btnResendEmail", function(event){
      var selector = $(this);
      var dataemailsend = jwt_decode(selector.attr('dataemailsend'));
      var RegisterID = $(this).attr('registerid');
      var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
              ' <button class  = "btn btn-success" id ="ModalbtnResendEmail" registerid = "'+RegisterID+'">Send</button>';
      var html = '<div class = "row">'+
                    '<div class = "col-md-12">'+
                      '<table class = "table">'+
                        '<tr>'+
                          '<td>To</td>'+
                          '<td>:</td>'+
                          '<td>'+dataemailsend['to']+'</td>'+
                        '</tr>'+
                        '<tr>'+
                          '<td>Subject</td>'+
                          '<td>:</td>'+
                          '<td>'+dataemailsend['subject']+'</td>'+
                        '</tr>'+
                        '<tr>'+
                          '<td>Message</td>'+
                          '<td>:</td>'+
                          '<td>'+dataemailsend['msg']+'</td>'+
                        '</tr>'+
                      '</table>'+
                    '</div>'+
                  '</div>';    


      $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'History Resend Email </h4>');
      $('#GlobalModalLarge .modal-body').html(html);
      $('#GlobalModalLarge .modal-footer').html(footer);
      $('#GlobalModalLarge').modal({
          'show' : true,
          'backdrop' : 'static'
      });
      
    });
    
    $(document).on("click", ".btnDetaiLResendEmail", function(event){
        var url = base_url_js+"admission/DetaiLResendEmail";
        var data = {
            RegisterID : $(this).attr('registerid') ,
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token }, function (data) {
          // action
          var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
              '';
          var html  = '';
            if (data.length > 0) {
                html = '<div class = "row">'+
                            '<div class = "col-md-12">'+
                              '<table class = "table">'+
                                '<thead>'+
                                  '<tr>'+
                                    '<td>No</td>'+
                                    '<td>Send By</td>'+
                                    '<td>time</td>'+
                                  '</tr>'+
                                '</thead>'+
                                '<tbody>';
                                    for (var i = 0; i < data.length; i++) {
                                        html += '<tr>'+
                                                  '<td>'+(i+1)+'</td>'+
                                                  '<td>'+data[i].NameEmployee+'</td>'+
                                                  '<td>'+data[i].Update_at+'</td>'+
                                                '</tr>';
                                    }

                        html += '</tbody>'+
                              '</table>'+
                            '</div>'+
                          '</div>'; 
            }
            else
            {
              html += '<p style = "color:red;">No data result</p>';
            }
                               
          $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'History Resend Email </h4>');
          $('#GlobalModalLarge .modal-body').html(html);
          $('#GlobalModalLarge .modal-footer').html(footer);
          $('#GlobalModalLarge').modal({
              'show' : true,
              'backdrop' : 'static'
          });

        })
    });

    $(document).on("click", ".btnLoginPortalRegister", function(event){
      var url = '<?php echo url_registration; ?>'+'auth/loginByPcam';
      var Email = $(this).attr('data-xx');
      var Code = $(this).attr('data-xx2');
      data = {
        Email : Email,
        Code : Code,
        NIP : sessionNIP,
      }
      var token = jwt_encode(data,"UAP)(*");
      submit(url, 'POST', [
          { name: 'token', value: token },
      ]);

    });

    $(document).on('click','.btnSetTahun',function(event) {
      const registerid = $(this).attr('registerid');
      const Data = $(this).attr('data');

      const OptTahun = () => {
        let wr = '';
        const thisYear = (new Date()).getFullYear();
        const EndNextYear = parseInt(thisYear)+ 3;
        const Yearselected = parseInt(thisYear)+1;
        for (var i = thisYear; i <= EndNextYear; i++) {
          var selected = (i == Yearselected) ? 'selected' : '';
          wr+= '<option value = "'+i+'" '+selected+' >'+i+'</option>';
        }
        return wr;
      }


      const htmlWrite  = () => {
        return '<div class = "row">' +
                  '<div class = "col-sm-6">'+
                      '<div class = "form-group">'+
                        '<label>Data</label>'+
                        '<input style = "color:black;" type = "text" class = "form-control" value ="'+Data+'" readonly>'+
                      '</div>'+
                      '<div class = "form-group">'+
                        '<label>Set Tahun</label>'+
                        '<select class = "form-control FrmYear">'+
                          OptTahun()+
                        '</select>'+
                      '</div>'+
                  '</div>'+
                '</div>'  

        ;
      }

      const footer = () => {
        return '<button class = "btn btn-success" id = "sbmtSetTahun" registerid = "'+registerid+'">Save</button>  <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>';
      }
      $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Set Tahun/Angkatan Masuk Universitas </h4>');
      $('#GlobalModalLarge .modal-body').html(htmlWrite());
      $('#GlobalModalLarge .modal-footer').html(footer());
      $('#GlobalModalLarge').modal({
          'show' : true,
          'backdrop' : 'static'
      });
    })

    $(document).on('click','#sbmtSetTahun',async function(e){
      const itsme = $(this);
      const data = {
        SetTa : $('.FrmYear option:selected').val(),
        RegisterID : itsme.attr('registerid'),
      };

      const url = base_url_js + 'admission/proses-calon-mahasiswa/setTahun';
      let token = jwt_encode(data,'UAP)(*');
      loading_button2(itsme);
      try{
        const ajaxGetResponse = await AjaxSubmitFormPromises(url,token);
        if (ajaxGetResponse == 1) {
          toastr.success('Saved');
          oTable1.ajax.reload( null, false );
        }
        else
        {
          toastr.error('Failed');
        }
        $('#GlobalModalLarge').modal('hide')
      }
      catch(err){
        console.log(err);
        toastr.error('Something Wrong');
      }

      end_loading_button2(itsme,'Save');

    })

</script>
