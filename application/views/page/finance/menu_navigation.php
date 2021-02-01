<div id="sidebar" class="sidebar-fixed">
    <div id="sidebar-content">

        <!--=== Navigation ===-->

        <ul id="nav">
            <?php 
            $authIT =  $this->session->userdata('PositionMain');
            $authIT =  $authIT['IDDivision'];
            ?>


            <?php if ($this->session->userdata('NIP') == '2018018' || $this->session->userdata('NIP') == '2016065' ): ?>
                <li class="<?php if($this->uri->segment(2)=='config'){echo "current open";} ?>">
                    <a href="javascript:void(0);">
                        <i class="fa fa-wrench"></i>
                        Config
                    </a>
                    <ul class="sub-menu">
                        <li class="<?php if($this->uri->segment(2)=='config' && $this->uri->segment(3) == "policysys" ){echo "current";} ?>">
                            <a href="<?php echo base_url('finance/config/policysys'); ?>">
                            <i class="icon-angle-right"></i>
                            Policy System
                            </a>
                        </li>
                    </ul>
                </li>
            <?php endif ?>    
            <li class="<?php if($this->uri->segment(1)=='dashboard'){echo "current";} ?>">
                <a href="<?php echo base_url('dashboard'); ?>">
                    <i class="fa fa-tachometer"></i>
                    Dashboard
                </a>
            </li>
            <li class="<?php if($this->uri->segment(2)=='master'){echo "current open";} ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-globe"></i>
                    Master
                </a>
                <ul class="sub-menu">
                    <li class="<?php if($this->uri->segment(2)=='master' && $this->uri->segment(3) == "mahasiswa" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/master/mahasiswa'); ?>">
                        <i class="icon-angle-right"></i>
                        Mahasiswa
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='master' && $this->uri->segment(3) == "tagihan-mhs" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/master/tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Tagihan Mahasiswa
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='master' && $this->uri->segment(3) == "discount" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/master/discount'); ?>">
                        <i class="icon-angle-right"></i>
                        Discount
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='master' && $this->uri->segment(3) == "import_price_list_mhs" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/master/import_price_list_mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Import PriceList Mahasiswa
                        </a>
                    </li>
                    <!-- <li class="<?php if($this->uri->segment(2)=='master' && $this->uri->segment(3) == "import_beasiswa_mahasiswa" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/master/import_beasiswa_mahasiswa'); ?>">
                        <i class="icon-angle-right"></i>
                        Import Beasiswa Mahasiswa
                        </a>
                    </li> -->
                </ul>
            </li>
            <li class="<?php if($this->uri->segment(2)=='admission'){echo "current open";} ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-address-book-o"></i>
                    Intake
                </a>
                <ul class="sub-menu">
                    <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "approved" ){echo "open-default";} ?>">
                        <a href="javascript:void(0);">
                        <i class="icon-angle-right"></i>
                        Approval Tagihan & Nilai
                        </a>
                        <ul class="sub-menu">
                            <!--<li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "approved" && $this->uri->segment(4) == "nilai-rapor" && $this->uri->segment(5) == ""){echo "current";} ?>">
                                <a href="<?php echo base_url('finance/admission/approved/nilai-rapor'); ?>">
                                    <i class="icon-angle-right"></i>
                                    Nilai Rapor
                                </a>
                            </li>-->
                            <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "approved" && $this->uri->segment(4) == "tuition-fee" ){echo "current";} ?>">
                                <a href="<?php echo base_url('finance/admission/approved/tuition-fee'); ?>">
                                <i class="icon-angle-right"></i>
                                Tagihan & Cicilan
                                </a>
                            </li>
                            <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "approved" && $this->uri->segment(4) == "edit" ){echo "current";} ?>">
                                <a href="<?php echo base_url('finance/admission/approved/edit'); ?>">
                                <i class="icon-angle-right"></i>
                                Edit Tagihan & Cicilan
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "penerimaan-pembayaran"){echo "open-default";} ?>">
                        <a href="javascript:void(0);">
                        <i class="icon-angle-right"></i>
                        Penerimaan Pembayaran
                        </a>
                        <ul class="sub-menu">
                            <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "penerimaan-pembayaran" && $this->uri->segment(4) == "formulir-registration"){echo "open-default";}  ?>">
                                <a href="javascript:void(0);">
                                    <i class="icon-angle-right"></i>
                                    Formulir Registration
                                </a>
                                <ul class="sub-menu">
                                    <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "penerimaan-pembayaran" && $this->uri->segment(4) == "formulir-registration" && $this->uri->segment(5) == "online"){echo "current";} ?>">
                                        <a href="<?php echo base_url('finance/admission/penerimaan-pembayaran/formulir-registration/online'); ?>">
                                            <i class="icon-angle-right"></i>
                                            Online
                                        </a>
                                    </li>
                                    <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "penerimaan-pembayaran" && $this->uri->segment(4) == "formulir-registration" && $this->uri->segment(5) == "offline"){echo "current";} ?>">
                                        <a href="<?php echo base_url('finance/admission/penerimaan-pembayaran/formulir-registration/offline'); ?>">
                                            <i class="icon-angle-right"></i>
                                            Offline
                                        </a>
                                    </li>
                                </ul>  
                            </li>
                            <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "penerimaan-pembayaran" && $this->uri->segment(4) == "biaya" ){echo "current";} ?>">
                                <a href="<?php echo base_url('finance/admission/penerimaan-pembayaran/biaya'); ?>">
                                <i class="icon-angle-right"></i>
                                BPP,SPP,SKS & ETC
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "registration-list"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/admission/registration-list'); ?>">
                        <i class="icon-angle-right"></i>
                        Registration List
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "report"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/admission/report'); ?>">
                        <i class="icon-angle-right"></i>
                        Report
                        </a>
                    </li>
                </ul>
            </li>
            <li class="<?php if($this->uri->segment(2)=='tagihan-mhs'){echo "current open";} ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-money"></i>
                    Tagihan Mahasiswa
                </a>
                <ul class="sub-menu">
                    <?php if ($_SERVER['SERVER_NAME']=='localhost'): ?>
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "import_pembayaran_manual" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/import_pembayaran_manual'); ?>">
                        <i class="icon-angle-right"></i>
                        Import Pembayaran Manual
                        </a>
                    </li>
                    
                    <li>
                        <a href="<?php echo base_url('finance/tagihan-mhs/import_pembayaran_lain'); ?>">
                        <i class="icon-angle-right"></i>
                        Import Pembayaran lain
                        </a>
                    </li>
                    <?php endif ?>
                    
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "set-deposit-mhs"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/set-deposit-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Set Deposit
                        </a>
                    </li>  

                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "set-tagihan-mhs"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/set-tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Set Tagihan
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "set-potongan-lain"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/set-potongan-lain'); ?>">
                        <i class="icon-angle-right"></i>
                        Set Potongan Lain
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "set-cicilan-tagihan-mhs"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/set-cicilan-tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Set Cicilan
                        </a>
                    </li>
                    <!-- <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "edit-cicilan-tagihan-mhs"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/edit-cicilan-tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Edit / Delete Pembayaran
                        </a>
                    </li> -->
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "cancel-tagihan-mhs"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/cancel-tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Batal Tagihan
                        </a>
                    </li>
                    <!-- <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "set-bayar"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/set-bayar'); ?>">
                        <i class="icon-angle-right"></i>
                        Set Bayar
                        </a>
                    </li> -->
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "cek-tagihan-mhs"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/cek-tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Daftar Tagihan
                        </a>
                    </li>
                    <!-- <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "list-telat-bayar"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/list-telat-bayar'); ?>">
                        <i class="icon-angle-right"></i>
                        Daftar Outstanding Pembayaran
                        </a>
                    </li> -->
                   <!--  <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "penerimaan-tagihan-mhs"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/penerimaan-tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Penerimaan Pembayaran
                        </a>
                    </li> -->
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "report"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/report'); ?>">
                        <i class="icon-angle-right"></i>
                        Report
                        </a>
                    </li>
                </ul>
            </li>
            <!-- <li class="<?php if($this->uri->segment(2)=='check-va'){echo "current";} ?>">
                <a href="<?php echo base_url('finance/check-va'); ?>">
                    <i class="fa fa-refresh"></i>
                    Check VA
                </a>
            </li>
            <li class="<?php if($this->uri->segment(2)=='download-log-va'){echo "current";} ?>">
                <a href="<?php echo base_url('finance/download-log-va'); ?>">
                    <i class="fa fa-cloud-download"></i>
                    Download Log VA
                </a>
            </li> -->

            <li class="<?php if($this->uri->segment(2)=='monitoring-yudisium'){echo "current";} ?>">
                <a href="<?php echo base_url('finance/monitoring-yudisium'); ?>">
                    <i class="fa fa-graduation-cap"></i>
                    Monitoring Yudisium
                </a>
            </li>
        </ul>
        <div class="sidebar-widget align-center">
            <div class="btn-group" data-toggle="buttons" id="theme-switcher">
                <label class="btn active">
                    <input type="radio" name="theme-switcher" data-theme="bright"><i class="fa fa-sun-o"></i> Bright
                </label>
                <label class="btn">
                    <input type="radio" name="theme-switcher" data-theme="dark"><i class="fa fa-moon-o"></i> Dark
                </label>
            </div>
        </div>

    </div>
    <div id="divider" class="resizeable"></div>
</div>
<!-- /Sidebar -->

<script type="text/javascript">
    const let_html_cicilan = (DetailPayment) => {
      let html_detail_payment = '';

      for (var i = 0; i < DetailPayment.length; i++) {
        let html_lunas_chk = (DetailPayment[i].Status == 1 || DetailPayment[i].Status == '1' ) ? '<i class="fa fa-check-circle" style="color: green;"></i>' : '<i class="fa fa-minus-circle" style="color: red;"></i>';

        let html_pay = '<span>Invoice : '+formatRupiah(DetailPayment[i].Invoice)+'</span> &nbsp'+html_lunas_chk;

        let detail_new_pay = '';
        let payment_student_details = DetailPayment[i].payment_student_details;
        if (payment_student_details.length > 0) {
           detail_new_pay += '<ol>';
           for (var j = 0; j < payment_student_details.length; j++) {
            detail_new_pay += '<li>'+formatRupiah(payment_student_details[j].Pay)+ ' &nbsp | <label>Tgl Bayar</label> : <span style = "color :green;">'+payment_student_details[j].Pay_Date+'</span></li>';
           }

           html_pay = '<a data-toggle="collapse" href="#detail-payment-list_'+i+'" aria-expanded="false">'+
                  html_pay+ ' | detail bayar : '+ 
                  '</a>'+
                  '<div id = "detail-payment-list_'+i+'" class="panel-collapse collapse">'+
                    detail_new_pay+
                  '</div>';
        }
        else
        {
          if (DetailPayment[i].DatePayment != null) {
            html_pay += ' | <span style = "color :green;">Tgl Bayar : '+DetailPayment[i].DatePayment+'</span>';
          }
          
        }

        html_detail_payment += '<li style = "font-weight: bold;">'+html_pay+'</li>';
      }

      let html_invoice = '<div class = "row">'+
                  '<div class = "col-md-12">'+
                    '<ol><label>Cicilan : </label>'+
                        html_detail_payment+
                    '</ol>'+                  
                  '</div>'+
                 '</div>';

      return html_invoice;
    }
    
    const modal_detail_payment = (data_payment,NPM,PaymentID) => {
        var html = '';
        var isi = '';
        var CancelPayment = [];

        let DetailPaymentArr = [];
        for (var i = 0; i < data_payment.length; i++) {
          if(data_payment[i]['PaymentID'] == PaymentID)
          {
            CancelPayment = data_payment[i]['cancelPay'];
            var totCancelPayment = CancelPayment.length;
            DetailPaymentArr = data_payment[i]['DetailPayment'];
            break;
          }
        }

        html += let_html_cicilan(DetailPaymentArr);

        // potongan lain
        var dataPotonganLain = data_payment[i]['potonganLain'];
        var htmlPotonganLain = '<div class = "row"><div class= col-md-12><h5>List Potongan Lain</h5><table class="table table-striped table-bordered table-hover table-checkable tableData">'+
                      '<thead>'+
                          '<tr>'+
                              '<th style="width: 5px;">No</th>'+
                              '<th style="width: 55px;">Nama Potongan</th>'+
                              '<th style="width: 55px;">Nominal</th>'+
                              '<th style="width: 55px;">Desc</th>'+
                              '<th style="width: 55px;">By & At</th>';
        htmlPotonganLain += '</tr>' ;  
        htmlPotonganLain += '</thead>' ; 
        htmlPotonganLain += '<tbody>' ;
        for (var index = 0; index < dataPotonganLain.length; index++) {
          var No = parseInt(index) + 1;
          htmlPotonganLain += '<tr>'+
                '<td>'+ (index+1) + '</td>'+
                '<td>'+ dataPotonganLain[index]['DiscountName'] + '</td>'+
                '<td>'+ '<span style = "color:blue">'+formatRupiah(dataPotonganLain[index]['DiscountValue'])+'</span>' + '</td>'+
                '<td>'+ dataPotonganLain[index]['Description'] + '</td>'+
                '<td>'+ '<span style = "color:green">'+dataPotonganLain[index]['Name']+ '<br/>' + dataPotonganLain[index]['UpdateAt']+'</span>' + '</td>'+
              '<tr>'; 
        }

        htmlPotonganLain += '</tbody>' ; 
        htmlPotonganLain += '</table></div></div>' ;
        if (dataPotonganLain.length > 0) {
          html += htmlPotonganLain;
        }

        // for Verify Bukti Bayar
            var htmlPaymentProof = '<div class = "row" style = "margin-top : 10px">'+
                                      '<div class = "col-md-12">'+
                                          '<h5>List Proof of Payment</h5>'+
                                            '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
                                              '<thead>'+
                                                '<tr>'+
                                                   '<th style="width: 5px;">No</th>'+
                                                   '<th style="width: 55px;">File</th>'+
                                                   '<th style="width: 55px;">Money Paid</th>'+
                                                   '<th style="width: 55px;">Account Name</th>'+
                                                   '<th style="width: 55px;">Account Owner</th>'+
                                                   '<th style="width: 55px;">Transaction Date</th>'+
                                                   '<th style="width: 55px;">Upload Date</th>'+
                                                   '<th style="width: 55px;">Bank</th>'+
                                                   '<th style="width: 55px;">Status</th>'+
                                                   '<th style="width: 55px;">Action</th>'+
                                                '</tr>'+
                                              '</thead>'+
                                            '<tbody>';
          var payment_proof = data_payment[i]['payment_proof'];
          if (payment_proof.length > 0) {
            for (var i = 0; i < payment_proof.length; i++) {
              var FileUpload = jQuery.parseJSON(payment_proof[i]['FileUpload']);
              var FileAhref = '';

              switch(payment_proof[i].VerifyFinance) {
                case '1':
                  var VerifyFinance = '<span style="color: green;"><i class="fa fa-check-circle margin-right"></i> Verify</span>';
                  break;
                case '2':
                  var VerifyFinance  = '<span style="color: red;"><i class="fa fa-remove margin-right"></i>Reject</span>';
                  break;
                default:
                  var VerifyFinance  = '<span style="color: green;"><i class="fa fa-info-circle margin-right"></i>Not Yet Verify</span>';
              }

              for (var j = 0; j < FileUpload.length; j++) {
                FileAhref = '<a href ="'+base_url_js+'fileGetAny/document-'+NPM+'-'+FileUpload[j].Filename+'" target="_blank">File '+ ((i+1)+j)+'</a>';
              }

              // var btnVerify = (payment_proof[i]['VerifyFinance'] == 1)? '' : '<button class = "verify" idtable = "'+payment_proof[i]['ID']+'">Verify</button><div style = "margin-top : 10px"><button class = "rejectverify" idtable = "'+payment_proof[i]['ID']+'">Reject</button></div>';
              
              var btnVerify = '';

              htmlPaymentProof += '<tr>'+
                                      '<td>'+(i+1)+'</td>'+
                                      '<td>'+FileAhref+'</td>'+
                                      '<td>'+formatRupiah(payment_proof[i]['Money'])+'</td>'+
                                      '<td>'+payment_proof[i]['NoRek']+'</td>'+
                                      '<td>'+payment_proof[i]['AccountOwner']+'</td>'+
                                      '<td>'+payment_proof[i]['Date_transaction']+'</td>'+
                                      '<td>'+payment_proof[i]['Date_upload']+'</td>'+
                                      '<td>'+payment_proof[i]['NmBank']+'</td>'+
                                      '<td>'+VerifyFinance+'</td>'+
                                      '<td>'+btnVerify+'</td>'+
                                  '</tr>';
            }

            htmlPaymentProof += '</tbody></table></div></div>';
            html += htmlPaymentProof;
          }

        // end Verify Bukti Bayar

        // for reason cancel payment
        var htmlReason = '<div class = "row"><div class= col-md-12><h5>List Cancel Payment</h5><table class="table table-striped table-bordered table-hover table-checkable tableData">'+
                      '<thead>'+
                          '<tr>'+
                              '<th style="width: 5px;">No</th>'+
                              '<th style="width: 55px;">Reason</th>'+
                              '<th style="width: 55px;">CancelAt</th>'+
                              '<th style="width: 55px;">CancelBy</th>';
        htmlReason += '</tr>' ;
        htmlReason += '</thead>' ;
        htmlReason += '<tbody>' ;
        for (var i = 0; i < CancelPayment.length; i++) {
          var No = parseInt(i) + 1;
          htmlReason += '<tr>'+
                '<td>'+ (i+1) + '</td>'+
                '<td>'+ CancelPayment[i]['Reason'] + '</td>'+
                '<td>'+ CancelPayment[i]['CancelAt'] + '</td>'+
                '<td>'+ CancelPayment[i]['Name'] + '</td>'+
              '<tr>';
        }

        htmlReason += '</tbody>' ;
        htmlReason += '</table></div></div>' ;
        if (CancelPayment.length > 0) {
          html += htmlReason;
        }
        // end reason cancel payment

        return html;
    }

</script>


