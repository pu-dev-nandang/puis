
<style type="text/css">
  .btn-custom {
    background-color: hsl(86, 79%, 44%) !important;
    background-repeat: repeat-x;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#daf6b5", endColorstr="#7cc817");
    background-image: -khtml-gradient(linear, left top, left bottom, from(#daf6b5), to(#7cc817));
    background-image: -moz-linear-gradient(top, #daf6b5, #7cc817);
    background-image: -ms-linear-gradient(top, #daf6b5, #7cc817);
    background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #daf6b5), color-stop(100%, #7cc817));
    background-image: -webkit-linear-gradient(top, #daf6b5, #7cc817);
    background-image: -o-linear-gradient(top, #daf6b5, #7cc817);
    background-image: linear-gradient(#daf6b5, #7cc817);
    border-color: #7cc817 #7cc817 hsl(86, 79%, 34%);
    color: #333 !important;
    text-shadow: 0 1px 1px rgba(255, 255, 255, 0.66);
    -webkit-font-smoothing: antialiased;
  }

  .btn-unapprove { background-color: hsl(41, 85%, 35%) !important; background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#efb73d", endColorstr="#a5750d"); background-image: -khtml-gradient(linear, left top, left bottom, from(#efb73d), to(#a5750d)); background-image: -moz-linear-gradient(top, #efb73d, #a5750d); background-image: -ms-linear-gradient(top, #efb73d, #a5750d); background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #efb73d), color-stop(100%, #a5750d)); background-image: -webkit-linear-gradient(top, #efb73d, #a5750d); background-image: -o-linear-gradient(top, #efb73d, #a5750d); background-image: linear-gradient(#efb73d, #a5750d); border-color: #a5750d #a5750d hsl(41, 85%, 29%); color: #fff !important; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.39); -webkit-font-smoothing: antialiased; }

</style>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-3">
        <div class="thumbnail" style="min-height: 30px;padding: 10px;">
            <select class="form-control" id="selectCurriculum">
                <option selected value = ''>--- All Curriculum ---</option>
                <option disabled>------</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="thumbnail" style="min-height: 30px;padding: 10px;">
            <select class="form-control" id="selectProdi">
                <option selected value = ''>--- All Prodi---</option>
                <option disabled>------</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="thumbnail" style="min-height: 30px;padding: 10px;">
            <select class="form-control" id="selectPTID">
                <option selected value = ''>--- All Payment Type ---</option>
                <option disabled>------</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="thumbnail" style="min-height: 30px;padding: 10px;">
            <input type="text" name="" class="form-control" placeholder="Input NPM Mahasiswa" id = "NIM" value="">
        </div>
    </div>
</div>
<br>
<!--<div class="thumbnail" style="padding: 10px;">
    <b>Status : </b><i class="fa fa-circle" style="color:#8ED6EA;"></i> Approve & Paid Off | <i class="fa fa-circle" style="color: #eade8e;"></i> Approve & Not Yet Paid Off  
</div>-->

<div class="row">
    <div class="col-md-12">
        <hr/>
        <table class="table table-bordered datatable2 hide" id = "datatable2">
            <thead>
            <tr style="background: #333;color: #fff;">
                <th style="width: 12%;">Program Study</th>
                <th style="width: 20%;">Nama,NPM &  VA</th>
                <th style="width: 15%;">Payment Type</th>
                <th style="width: 15%;">Email PU</th>
                <th style="width: 10%;">Total Invoice</th>
                <th style="width: 20%;">Keterangan</th>
                <th style="width: 10%;">Action</th>
            </tr>
            </thead>
            <tbody id="dataRow"></tbody>
        </table>
    </div>
    <div  class="col-xs-12" align="right" id="pagination_link"></div>
</div>


<script>
    window.dataa = '';
    window.dataaModal = '';
    $(document).ready(function () {
        loadData(1);
        loadSelectOptionCurriculum('#selectCurriculum','');
        loadSelectOptionBaseProdi('#selectProdi','');
        loadSelectOptionPaymentTypeMHS('#selectPTID','');
        getReloadTableSocket();
        // $("#btn-submit").addClass('hide');
    });

    $('#selectCurriculum').change(function () {
        loadData(1);
    });

    $('#selectProdi').change(function () {
        loadData(1);
    });

    $('#selectPTID').change(function () {
        loadData(1);
    });

    $(document).on('keypress','#NIM', function ()
    {

        if (event.keyCode == 10 || event.keyCode == 13) {
          loadData(1);
        }
   }); // exit enter

    $(document).on("click", ".pagination li a", function(event){
      event.preventDefault();
      var page = $(this).attr("data-ci-pagination-page");
      if (page == null){
          page = 1;
      }
      loadData(page);
      // loadData_register_document(page);
    });

    function loadData(page) {
        $("#btn-submit").addClass('hide');
        $("#btn-submit-unapprove").addClass('hide');
        $("#datatable2").addClass('hide');

        $('#dataResultCheckAll').prop('checked', false); // Unchecks it
        $("span").removeClass('checked');

        var ta = $('#selectCurriculum').val();
        var prodi = $('#selectProdi').val();
        var PTID = $('#selectPTID').val();
        var NIM = $('#NIM').val().trim();
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
            var url = base_url_js+'finance/get_list_telat_bayar/'+page;
            var data = {
                ta : ta,
                prodi : prodi,
                PTID  : PTID,
                NIM : NIM,
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
               var resultJson = jQuery.parseJSON(resultJson);
               console.log(resultJson);
                var Data_mhs = resultJson.loadtable;
                data = Data_mhs;
                dataaModal = Data_mhs;
                for(var i=0;i<Data_mhs.length;i++){
                  var ccc = 0;
                  var yy = (Data_mhs[i]['InvoicePayment'] != '') ? formatRupiah(Data_mhs[i]['InvoicePayment']) : '-';
                  // proses status
                  var status = '';

                  var b = 0;
                  var cicilan = 0;
                  var bayar = 0;
                  var htmlCicilan = '';
                  // count jumlah pembayaran dengan status 1
                  for (var j = 0; j < Data_mhs[i]['DetailPayment'].length; j++) {
                    var a = Data_mhs[i]['DetailPayment'][j]['Status'];
                    if(a== 1)
                    {
                      b = parseInt(b) + parseInt(Data_mhs[i]['DetailPayment'][j]['Invoice']);
                      bayar = bayar + 1;
                    }
                    cicilan = cicilan + 1;
                  }


                  if(cicilan == 1)
                  {
                    htmlCicilan = "Tidak Cicilan, Deadline : "+Data_mhs[i]['DetailPayment'][0]['Deadline'];
                  }
                  else
                  {
                    for (var k = 1; k <= cicilan; k++) {
                      var dd = parseInt(k) - 1 ;
                      var bayarStatus = (k > bayar) ? '<i class="fa fa-minus-circle" style="color: red;"></i>' : '<i class="fa fa-check-circle" style="color: green;"></i>';
                      htmlCicilan += '<p>Cicilan ke '+k+ ': '+bayarStatus+ ' ,Deadline : '+Data_mhs[i]['DetailPayment'][dd]['Deadline']+'</p>';
                    }
                  }

                  var tr = '<tr>';
                  // show bintang
                  var bintang = (Data_mhs[i]['Pay_Cond'] == 1) ? '<p style="color: red;">*</p>' : '<p style="color: red;">**</p>'; 
                  $('#dataRow').append(tr +
                      '<td>'+Data_mhs[i]['ProdiEng']+'<br>'+Data_mhs[i]['SemesterName']+'</td>' +
                      // '<td>'+Data_mhs[i]['SemesterName']+'</td>' +
                      '<td>'+bintang+Data_mhs[i]['Nama']+'<br>'+Data_mhs[i]['NPM']+'<br>'+Data_mhs[i]['VA']+'</td>' +
                      // '<td>'+Data_mhs[i]['NPM']+'</td>' +
                      // '<td>'+Data_mhs[i]['Year']+'</td>' +
                      '<td>'+Data_mhs[i]['PTIDDesc']+'</td>' +
                      '<td>'+Data_mhs[i]['EmailPU']+'</td>' +
                      '<td>'+yy+'</td>' +
                      '<td>'+htmlCicilan+'</td>'+
                      '<td>'+'<button class = "DetailPayment" NPM = "'+Data_mhs[i]['NPM']+'">View</button>&nbsp <button class = "edit" NPM = "'+Data_mhs[i]['NPM']+'" semester = "'+Data_mhs[i]['SemesterID']+'" PTID = "'+Data_mhs[i]['PTID']+'" PaymentID = "'+Data_mhs[i]['PaymentID']+'">Edit</button>'+'</td>' +
                      '</tr>');
                 
                }

               if(Data_mhs.length > 0)
               {
                $('#datatable2').removeClass('hide');
                $("#pagination_link").html(resultJson.pagination_link);
               }
               
            }).fail(function() {
              
              toastr.info('No Result Data'); 
              // toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {
                $('#NotificationModal').modal('hide');
            });
            $('#NIM').focus();
    }

    $(document).on('click','.edit', function () {
        var PaymentID = $(this).attr('PaymentID');
        var NPM = $(this).attr('NPM');
        var semester = $(this).attr('semester');
        var PTID = $(this).attr('PaymentID');
        var data = {
            PaymentID : PaymentID,
            NPM : NPM,
            semester  : semester,
            PTID : PTID,
        };
        var token = jwt_encode(data,'UAP)(*');
        window.open(base_url_js+'finance/edit_telat_bayar/'+token,'_blank');


    });

    $(document).on('click','.DetailPayment', function () {
        var NPM = $(this).attr('NPM');
        var html = '';
        var table = '<table class="table table-striped table-bordered table-hover table-checkable tableData">'+
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
        for (var i = 0; i < dataaModal.length; i++) {
          if(dataaModal[i]['NPM'] == NPM)
          {
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
        table += '</table>' ;

        html += table;

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

    function getReloadTableSocket()
    {
      var socket = io.connect( 'http://'+window.location.hostname+':3000' );
      // var socket = io.connect( '<?php echo serverRoot ?>'+':3000' );

      socket.on( 'update_notifikasi', function( data ) {

          //$( "#new_count_message" ).html( data.new_count_message );
          //$('#notif_audio')[0].play();
          if (data.update_notifikasi == 1) {
              // action
              loadData(1);
          }

      }); // exit socket
    }

</script>