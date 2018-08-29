<link href="<?php echo base_url('assets/custom/jquery-ui.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-12 formAddFormKD">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> Data Calon Mahasiswa</h4>
            </div>
            <div class="widget-content">
                <!--  -->
                <div class="row">
                    <div id="panel_web" class="" style="padding:30px;padding-top:0px;">
                         <ul class="nav nav-tabs">
                            <li role="presentation" class="active"><a href="javascript:void(0)" class="tab-btn-submenu-page" data-page="data-calon-mhs/1">Data</a></li>
                            <li role="presentation"><a href="javascript:void(0)" class="tab-btn-submenu-page" data-page="to-be-mhs/1">To Be Mahasiswa</a></li>
                            <!--<li role="presentation"><a href="javascript:void(0)" class="tab-btn-tuition-fee" data-page="tuition_fee_delete/1">Register</a></li>
                            <li role="presentation"><a href="javascript:void(0)" class="tab-btn-tuition-fee" data-page="tuition_fee_delete/1">Register Recycled</a></li>-->
                         </ul>
                         <br>
                         <div class="thumbnail" style="padding: 10px;">
                             <b>Status : </b><i class="fa fa-circle" style="color:#8ED6EA;"></i> Paid Off 
                         </div>
                         <br> 
                        <div id="dataPageLoad" style="margin-top:0px;">
                            
                        </div>
                        <!-- <div  class="col-xs-12" align="right" id="pagination_link"></div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.pageHtml = '';
    window.temp = '';
    $(document).ready(function () {
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            $('#panel_web').addClass('wrap');
            $('#panel_web').css({"padding": "0px", "padding-right": "20px"});
        }
        loadPage('data-calon-mhs/1');
    });

    $('.tab-btn-submenu-page').click(function () {
        var page = $(this).attr('data-page');

        $('li[role=presentation]').removeClass('active');
        $(this).parent().addClass('active');
        loadPage(page);
    });

    function loadPage(page) {
        $("#dataPageLoad").empty(); 
        var res = page.split("/");
        switch(res[0]) {
            case 'data-calon-mhs':
               $("#dataPageLoad").empty();
               var table = '<table class="table table-bordered datatable2" id = "datatable2">'+
                           '<thead>'+
                           '<tr style="background: #333;color: #fff;">'+
                               // '<th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>'+
                               '<th>No</th>'+
                               '<th>Nama,Email & Sekolah</th>'+
                               '<th>Prody,Formulir & VA</th>'+
                               '<th>Sales</th>'+
                               '<th>Rangking</th>'+
                               '<th>Beasiswa</th>'+
                               '<th>Document</th>'+
                               '<th>Tagihan</th>'+
                               '<th>Detail Payment</th>'+
                               '<th>Status Payment</th>'+
                           '</tr>'+
                           '</thead>'+
                           '<tbody id="dataRow"></tbody>'+
                       '</table>';
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


                break;
            case 'to-be-mhs':
                $("#dataPageLoad").empty();
                var table = '<table class="table table-bordered datatable2" id = "datatable2">'+
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
                                '<th>Tagihan</th>'+
                                '<th>Detail Payment</th>'+
                                '<th>Status Payment</th>'+
                            '</tr>'+
                            '</thead>'+
                            '<tbody id="dataRow"></tbody>'+
                        '</table></div><button class = "btn btn-success btn-edit" id = "generateToBEMhs">Generate</button>';
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

                    var checkboxArr = [];
                     table.$('input[type="checkbox"]').each(function(){
                       if(this.checked){
                          checkboxArr.push(this.value);
                       }
                        
                     });

                     if (checkboxArr.length > 0) {
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

                             var url = base_url_js+'admission/proses-calon-mahasiswa/generate_to_be_mhs';
                             var data = {
                                         checkboxArr : checkboxArr,
                                        };
                             var token = jwt_encode(data,"UAP)(*");
                              $.post(url,{token:token},function (data_json) {
                                 // $('#generateToBEMhs').prop('disabled',false).html('Generate');
                                 $('#NotificationModal').modal('hide');
                             }).done(function() {
                               loadPage('to-be-mhs/1');
                             }).fail(function() {
                               toastr.error('The Database connection error, please try again', 'Failed!!');
                             }).always(function() {
                              // $('#generateToBEMhs').prop('disabled',false).html('Generate');

                             });
                         })
                     }
                     else
                     {
                         toastr.error("Silahkan checked dahulu", 'Failed!!');
                     }

                }) // exit click function    

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