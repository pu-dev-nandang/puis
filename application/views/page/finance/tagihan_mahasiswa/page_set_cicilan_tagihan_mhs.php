<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<style type="text/css">
  .btn-submit{
    background-color: #1ace37;
  }
</style>
<div class="row">
    <div class="col-xs-12" >
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Report</h4>
            </div>
            <div class="panel-body">
               <div class="row" style="margin-top: 30px;">
                   <div class="col-md-3">
                       <div class="thumbnail" style="min-height: 30px;padding: 10px;">
                           <input type="text" name="" class="form-control" placeholder="Input NPM Mahasiswa" id = "NIM">
                       </div>
                   </div>
               </div>
               <br>
               <div class="row">
                 <div class="col-md-12" align="right">
                   <button type="button" class="btn btn-default" id = 'idbtn-cari'><span class="glyphicon glyphicon-search"></span> Cari</button>
                 </div>
               </div>
               <div class="row" style="margin-top: 10px">
                   <div class="col-md-12">
                       <table class="table table-bordered datatable2 hide" id = "datatable2">
                           <thead>
                           <tr style="background: #333;color: #fff;">
                               <th style="width: 1%;">Choose</th>
                               <th style="width: 12%;">Program Study</th>
                               <!-- <th style="width: 10%;">Semester</th> -->
                               <th style="width: 20%;">Nama,NPM &  VA</th>
                               <!-- <th style="width: 5%;">NPM</th> -->
                               <!-- <th style="width: 5%;">Year</th> -->
                               <th style="width: 15%;">Payment Type</th>
                               <th style="width: 15%;">Email PU</th>
                               <th style="width: 15%;">IPS</th>
                               <th style="width: 15%;">IPK</th>
                               <th style="width: 10%;">Discount</th>
                               <th style="width: 10%;">Invoice</th>
                               <th style="width: 10%;">Status</th>
                               <th style="width: 10%;">Detail Payment</th>
                           </tr>
                           </thead>
                           <tbody id="dataRow"></tbody>
                       </table>
                       <hr>
                       <div id = "inputCicilan" class="hide">
                         <div class="widget box">
                             <div class="widget-header">
                                 <h4 class="header"><i class="icon-reorder"></i>Input Cicilan</h4>
                             </div>
                             <div class="widget-content">
                                 <!--  -->
                                  
                                 <!-- end widget -->
                             </div>
                             <hr/>
                         </div>
                       </div>
                   </div>
               </div>
            </div>
        </div>
    </div>
</div>




<script>
    window.dataa = '';
    window.dataaModal = '';
    window.get_Invoice = '';
    window.max_cicilan = '<?php echo $max_cicilan ?>';
    var checkApprove = 0;
    $(document).ready(function () {
        
    });

    $(document).on('keypress','#NIM', function (event)
    {

        if (event.keyCode == 10 || event.keyCode == 13) {
          valuee = $(this).val();
          loadData(1,valuee);
        }
    }); // exit enter

    $(document).on('click','#idbtn-cari', function () {
        var NPM = $("#NIM").val();
        result = Validation_required(NPM,'NPM');
        if (result['status'] == 0) {
          toastr.error(result['messages'], 'Failed!!');
        }
        else
        {
          loadData(1,NPM);
        }
    });

    function loadData(page,NPM) {
        var NIM = NPM;
        $(".widget-content").empty();
        $("#inputCicilan").addClass('hide');
        $('#datatable2').addClass('hide');

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
            $('#dataRow').html('');
            var url = base_url_js+'finance/get_created_tagihan_mhs_not_approved/'+page;
            var data = {
                ta : '',
                prodi : '',
                PTID  : '',
                NIM : NIM,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
               var resultJson = jQuery.parseJSON(resultJson);
               console.log(resultJson);
                var Data_mhs = resultJson.loadtable;
                dataaModal = Data_mhs;
                if (Data_mhs.length > 0) {
                    for(var i=0;i<Data_mhs.length;i++){
                         var ccc = 0;
                         var yy = (Data_mhs[i]['InvoicePayment'] != '') ? formatRupiah(Data_mhs[i]['InvoicePayment']) : '-';
                         // get_Invoice = Data_mhs[i]['InvoicePayment'];
                         //  var n = get_Invoice.indexOf(".");
                         // get_Invoice = get_Invoice.substring(0, n);
                         // dataa = {ID : Data_mhs[i]['PaymentID'],PTID : Data_mhs[i]['PTID'],SemesterID : Data_mhs[i]['SemesterID']};
                         // proses status
                         var status = '';
                         if(Data_mhs[i]['StatusPayment'] == 0)
                         {
                           status = 'Belum Approve <br> Belum Lunas';
                         }
                         else
                         {
                           status = 'Approve';
                           // check lunas atau tidak
                             // count jumlah pembayaran dengan status 1
                             var b = 0;
                             for (var j = 0; j < Data_mhs[i]['DetailPayment'].length; j++) {
                               var a = Data_mhs[i]['DetailPayment'][j]['Status'];
                               if(a== 1)
                               {
                                 b = parseInt(b) + parseInt(Data_mhs[i]['DetailPayment'][j]['Invoice']);
                               }
                             }

                             // console.log('b : '+b+ '  ..InvoicePayment : ' + Data_mhs[i]['InvoicePayment']);
                             if(b < Data_mhs[i]['InvoicePayment'])
                             {
                               status += '<br> Belum Lunas';
                               ccc = 1;
                             }
                             else
                             {
                               status += '<br> Lunas';
                               ccc = 2
                             }
                         }

                        var tr = '<tr NPM = "'+Data_mhs[i]['NPM']+'">';
                        var inputCHK = ''; 
                        if (ccc == 0) {
                         tr = '<tr NPM = "'+Data_mhs[i]['NPM']+'">';
                         inputCHK = '<input type="checkbox" class="uniform" value ="'+Data_mhs[i]['NPM']+'" Prodi = "'+Data_mhs[i]['ProdiEng']+'" Nama ="'+Data_mhs[i]['Nama']+'" semester = "'+Data_mhs[i]['SemesterID']+'" ta = "'+Data_mhs[i]['Year']+'" invoice = "'+Data_mhs[i]['InvoicePayment']+'" discount = "'+Data_mhs[i]['Discount']+'" PTID = "'+Data_mhs[i]['PTID']+'" PTName = "'+Data_mhs[i]['PTIDDesc']+'" PaymentID = "'+Data_mhs[i]['PaymentID']+'" Status = "'+ccc+'">'; 
                        } else if(ccc == 1) {
                           tr = '<tr style="background-color: #eade8e; color: black;" NPM = "'+Data_mhs[i]['NPM']+'">';
                           inputCHK = '<input type="checkbox" class="uniform" value ="'+Data_mhs[i]['NPM']+'" Prodi = "'+Data_mhs[i]['ProdiEng']+'" Nama ="'+Data_mhs[i]['Nama']+'" semester = "'+Data_mhs[i]['SemesterID']+'" ta = "'+Data_mhs[i]['Year']+'" invoice = "'+Data_mhs[i]['InvoicePayment']+'" discount = "'+Data_mhs[i]['Discount']+'" PTID = "'+Data_mhs[i]['PTID']+'" PTName = "'+Data_mhs[i]['PTIDDesc']+'" PaymentID = "'+Data_mhs[i]['PaymentID']+'" Status = "'+ccc+'">'; 
                        }
                        else
                        {
                         tr = '<tr style="background-color: #8ED6EA; color: black;" NPM = "'+Data_mhs[i]['NPM']+'">';
                         inputCHK = ''; 
                        }

                        if(Data_mhs[i]['StatusPayment'] == 0) // menandakan belum approve
                         {
                           // show bintang
                           var bintang = (Data_mhs[i]['Pay_Cond'] == 1) ? '<p style="color: red;">*</p>' : '<p style="color: red;">**</p>';
                           if (Data_mhs[i]['DetailPayment'].length == 1) { // menandakan untuk setting cicilan maka harus memiliki satu detail payment
                               $('#dataRow').append(tr +
                                                      '<td>'+inputCHK+'</td>' +
                                                      '<td>'+Data_mhs[i]['ProdiEng']+'<br>'+Data_mhs[i]['SemesterName']+'</td>' +
                                                      // '<td>'+Data_mhs[i]['SemesterName']+'</td>' +
                                                      '<td>'+bintang+Data_mhs[i]['Nama']+'<br>'+Data_mhs[i]['NPM']+'<br>'+Data_mhs[i]['VA']+'</td>' +
                                                      // '<td>'+Data_mhs[i]['NPM']+'</td>' +
                                                      // '<td>'+Data_mhs[i]['Year']+'</td>' +
                                                      '<td>'+Data_mhs[i]['PTIDDesc']+'</td>' +
                                                      '<td>'+Data_mhs[i]['EmailPU']+'</td>' +
                                                      '<td>'+getCustomtoFixed(Data_mhs[i]['IPS'],2)+'</td>' +
                                                      '<td>'+getCustomtoFixed(Data_mhs[i]['IPK'],2)+'</td>' +
                                                      '<td>'+Data_mhs[i]['Discount']+'%</td>' +
                                                      '<td>'+yy+'</td>' +
                                                      '<td>'+status+'</td>' +
                                                      '<td>'+'<button class = "DetailPayment" NPM = "'+Data_mhs[i]['NPM']+'">View</button>'+'</td>' +
                                                      '</tr>');
                            }
                         } 
                        
                    }

                    if(Data_mhs.length > 0)
                    {
                      // if(Data_mhs[0]['StatusPayment'] == 0) {// menandakan belum approve
                      //   if (Data_mhs[0]['DetailPayment'].length == 1) {
                      //        checkApprove = 1;
                      //         $('#datatable2').removeClass('hide');
                      //         // var sss = '<select class = "full-width-fix" id = "jml_cicilan">'+
                      //         //                    ' <option value = "" disabled selected>--Pilih Jumlah Cicilan--</option>';
                      //         // for (var l = 2; l <= max_cicilan; l++) {
                      //         //     sss += ' <option value = "'+l+'">'+l+'</option>'
                      //         // }

                      //         // sss += '</select>';                   

                      //         // var aaa = '<div class = "row">'+
                      //         //         '<div class="form-group">'+
                      //         //             '<label class="col-xs-1 control-label">Set Cicilan</label>'+  
                      //         //             '<div class = "col-xs-2">'+
                      //         //                sss+
                      //         //             '</div>'+  
                      //         //         '</div>'+    
                      //         //     '</div><br>'+
                      //         //     '<div class = "row" id="pageSetCicilan">'+
                      //         //     '</div>'
                      //         // $(".widget-content").html(aaa);
                      //         // $("#inputCicilan").removeClass('hide');

                      //   } 
                      // }

                      checkApprove = 1;
                       $('#datatable2').removeClass('hide');
                        
                    }
                } else {
                  toastr.error('Error', 'Failed!!');
                }
            }).fail(function() {
              
              toastr.info('No Result Data'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {
                $('#NotificationModal').modal('hide');
            });
    }

    $(document).on('click','.uniform', function () {
      $('input.uniform').prop('checked', false);
      $(this).prop('checked',true);
      var sss = '<select class = "full-width-fix" id = "jml_cicilan">'+
                         ' <option value = "" disabled selected>--Pilih Jumlah Cicilan--</option>';
      for (var l = 2; l <= max_cicilan; l++) {
          sss += ' <option value = "'+l+'">'+l+'</option>'
      }

      sss += '</select>';                   

      var aaa = '<div class = "row">'+
              '<div class="form-group">'+
                  '<label class="col-xs-1 control-label">Set Cicilan</label>'+  
                  '<div class = "col-xs-2">'+
                     sss+
                  '</div>'+  
              '</div>'+    
          '</div><br>'+
          '<div class = "row" id="pageSetCicilan">'+
          '</div>'
      $(".widget-content").html(aaa);
      $("#inputCicilan").removeClass('hide');
      get_Invoice = $(this).attr('invoice');
      var n = get_Invoice.indexOf(".");
      get_Invoice = get_Invoice.substring(0, n);
      dataa = {ID : $(this).attr('paymentid'),PTID : $(this).attr('ptid'),SemesterID : $(this).attr('semester')};
    });


    $(document).on('change','#jml_cicilan', function () {
        $("#btn-div").remove();
        var setMinimal = 500000;
        var Val = $(this).val();
        var splitcicilan = parseInt(get_Invoice) / parseInt(Val);
        var splitcicilan = parseInt(splitcicilan);
        if (Val != " " && Val != null ) 
        {
            var input = '<div class="form-group">';
            var cost = 0;
            var cost_value = splitcicilan;
           for (var i = 1 ; i <= Val; i++) {
                if (i == Val) {
                    cost_value = parseInt(get_Invoice) - parseInt(cost);
                }
                input += '<label class="col-xs-1 control-label">Cicilan '+i+'</label><div class="col-xs-2"><input type="text" id = "cost'+i+'" value = "'+cost_value+'" class = "form-control costInput"><br>Deadline<div id="datetimepicker'+i+'" class="input-group input-append date datetimepicker">'+
                            '<input data-format="yyyy-MM-dd hh:mm:ss" class="form-control" id="datetime_deadline'+i+'" type="text"></input>'+
                            '<span class="input-group-addon add-on">'+
                              '<i data-time-icon="icon-time" data-date-icon="icon-calendar">'+
                              '</i>'+
                            '</span>'+
                        '</div></div>';
                cost = cost + cost_value;
            }

            input += '</div>';

           $("#pageSetCicilan").html(input);
           $("#pageSetCicilan").after('<br><div class = "col-xs-12" align = "right" id="btn-div"><button class="btn btn-success btn-notification btn-Save" id="btn-Save"><i class="icon-pencil icon-white"></i> Submit</button></div>');

           $('.costInput').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
           $('.costInput').maskMoney('mask', '9894');

           Date.prototype.addDays = function(days) {
               var date = new Date(this.valueOf());
               date.setDate(date.getDate() + days);
               return date;
           }
           var date = new Date();
           for (var i = 1 ; i <= Val; i++) {
              $('#datetimepicker'+i).datetimepicker({
               // startDate: today,
               // startDate: '+2d',
               startDate: date.addDays(i),
              });

              $('#datetime_deadline'+i).prop('readonly',true);
           } 
           
        }
    });

    $(document).on('keyup','.costInput', function () {
        var arrTemp = [];
        var setMinimal = 500000;
        $('.costInput').each(function(){
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

        var arrValue = [];
        var count = 0;
        for (var i = 0; i < arrTemp.length; i++) {
            var temp = findAndReplace(arrTemp[i]['valuee'], ".","");
            if (count > get_Invoice) {
                var splitcicilan = parseInt(get_Invoice) / parseInt(arrTemp.length);
                var splitcicilan = (splitcicilan < setMinimal) ? setMinimal : parseInt(splitcicilan);
                var cost = 0;
                var cost_value = splitcicilan;
                for (var j = 0; j < arrTemp.length; j++) {

                   if (j == (arrTemp.length - 1)) {
                       cost_value = parseInt(get_Invoice) - parseInt(cost);
                   }
                   var getID = parseInt(j) + 1;
                   $("#cost"+getID).val(cost_value);
                   cost = cost + cost_value; 
                }
            }
            else
            {
                if ((arrTemp.length - 1) == i) {
                    var getID = parseInt(i) + 1;
                    $("#cost"+getID).val(parseInt(get_Invoice) - parseInt(count));
                }
            }
            count += parseInt(temp);
        }
        $('.costInput').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
        $('.costInput').maskMoney('mask', '9894');
    });

    $(document).on('click','#btn-Save', function () {
        loading_button('#btn-Save');
        var arrTemp = [];
        $('.costInput').each(function(){
            var valuee = $(this).val();
            for(i = 0; i <valuee.length; i++) {
             
             valuee = valuee.replace(".", "");
             
            }

            var SID = $(this).attr('id');
            var SID = SID.substr(SID.length - 1); // => "1"
            var Deadline = $("#datetime_deadline"+SID).val();
            data = {
              Deadline : Deadline,
              Payment  : valuee,
              ID       : dataa.ID,
              PTID     : dataa.PTID,
              SemesterID : dataa.SemesterID
            }
            arrTemp.push(data);
        })

        // check cicilan != 0 dan Deadline is empty
        var bool = true;
        var msg = '';
        for (var i = 0; i < arrTemp.length; i++) {
          if (arrTemp[i].Payment == 0) {
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
          /*var startDate = moment("28.04.2016", "DD.MM.YYYY");
          var endDate = moment("26.04.2016", "DD.MM.YYYY");

          var result = 'Diff: ' + endDate.diff(startDate, 'days');
          console.log(result);*/

        if (bool) {
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
                   console.log(result);
                   if (result <= 0) {
                    bool2 = false;
                    console.log('i ' + date1 + '< j : ' + date2);
                    break;
                   } 
                }
                
               }

               if (!bool2) {
                  break;
                  console.log('i < j');
               }

            }
          // hitung tanggal tidak boleh melewati cicilan sebelumnya

          if (bool2) {
            var url = base_url_js + "finance/tagihan-mhs/set-cicilan-tagihan-mhs/submit";
            var data = arrTemp
            var token = jwt_encode(data,"UAP)(*");
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                var obj = JSON.parse(data_json); 
                if(obj != ''){
                    $('#btn-Save').prop('disabled',false).html('Submit');  
                    toastr.error(obj, 'Failed!!');
                }
                else
                {
                    window.location.reload(true); 
                }

            }).done(function() {
              // $('#btn-Save').prop('disabled',false).html('Submit');
            }).fail(function() {
              $('#btn-Save').prop('disabled',false).html('Submit');  
              toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {
             $('#btn-Save').prop('disabled',false).html('Submit');
            });
          } else {
            toastr.error('Tanggal Deadline cicilan tidak boleh mendahului tanggal cicilan sebelumnya', 'Failed!!');
            $('#btn-Save').prop('disabled',false).html('Submit');
          }

        } else {
          toastr.error(msg, 'Failed!!');
          $('#btn-Save').prop('disabled',false).html('Submit');
        }

    });

    $(document).on('click','.DetailPayment', function () {
        var NPM = $(this).attr('NPM');
        var html = '';
        var table = '<div class = "row"><div class= col-md-12><table class="table table-striped table-bordered table-hover table-checkable tableData">'+
                      '<thead>'+
                          '<tr>'+
                              '<th style="width: 5px;">No</th>'+
                              '<th style="width: 55px;">Nama</th>'+
                              '<th style="width: 55px;">Invoice</th>'+
                              '<th style="width: 55px;">BilingID</th>'+
                              '<th style="width: 55px;">Status</th>'+
                              '<th style="width: 55px;">Deadline</th>'+
                              '<th style="width: 55px;">UpdateAt</th>';
        table += '</tr>' ;  
        table += '</thead>' ; 
        table += '<tbody>' ;
        var isi = '';
        // console.log(dataaModal);
        var CancelPayment = [];
        for (var i = 0; i < dataaModal.length; i++) {
          if(dataaModal[i]['NPM'] == NPM)
          {
            CancelPayment = dataaModal[i]['cancelPay'];
            var totCancelPayment = CancelPayment.length;
            var DetailPaymentArr = dataaModal[i]['DetailPayment'];
            var Nama = dataaModal[i]['Nama'];
            for (var j = 0; j < DetailPaymentArr.length; j++) {
              var yy = (DetailPaymentArr[j]['Invoice'] != '') ? formatRupiah(DetailPaymentArr[j]['Invoice']) : '-';
              var status = (DetailPaymentArr[j]['Status'] == 0) ? 'Belum Bayar' : 'Sudah Bayar';
              isi += '<tr>'+
                    '<td>'+ (j+1) + '</td>'+
                    '<td>'+ Nama + '</td>'+
                    '<td>'+ yy + '</td>'+
                    '<td>'+ DetailPaymentArr[j]['BilingID'] + '</td>'+
                    '<td>'+ status + '</td>'+
                    '<td>'+ DetailPaymentArr[j]['Deadline'] + '</td>'+
                    '<td>'+ DetailPaymentArr[j]['UpdateAt'] + '</td>'+
                  '<tr>'; 
            }
            break;
          }
        }

        table += isi+'</tbody>' ; 
        table += '</table></div></div>' ;
        html += table;

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

        var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
            '';

        $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Detail Payment'+'</h4>');
        $('#GlobalModalLarge .modal-body').html(html);
        $('#GlobalModalLarge .modal-footer').html(footer);
        $('#GlobalModalLarge').modal({
            'show' : true,
            'backdrop' : 'static'
        });    

    });

</script>