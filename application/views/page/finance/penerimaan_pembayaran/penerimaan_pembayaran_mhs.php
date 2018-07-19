<style type="text/css">
    table {
            border:solid #000 !important;
            border-width:1px 0 0 1px !important;
            font-size: 12px;
            }
        th, td {
          border:solid #000 !important;
          border-width:0 1px 1px 0 !important;
        }
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
            <input type="text" name="" class="form-control" placeholder="Input NPM Mahasiswa" id = "NIM">
        </div>
    </div>
</div>

<div class="col-md-12">
      <hr/>
      <div class="col-md-12">
        <div class="DTTT btn-group">
          <button type="button" class="btn btn-convert" id="export_excel"><i class="fa fa-download" aria-hidden="true"></i> Excel</button>
          <!--<a class="btn DTTT_button_pdf" id="ToolTables_DataTables_Table_0_1">
            <span><i class="fa fa-download" aria-hidden="true"></i> PDF
            </span>
          </a>-->
        </div>
        <div id="DataTables_Table_0_filter" class="dataTables_filter">
          <label>
            <div class="input-group">
              <div class="thumbnail" style="min-height: 30px;padding: 10px;">
                  <select class="form-control" id="selectSemester">
                  </select>
              </div>
            </div>
          </label>
        </div>
      </div>  
</div>                        
<br>
<br>
<br>
<br>
<div class="row" id='conTainJS'>
    <!--<div class="col-md-12">
        <hr/>
        <div align="right">
            <div class="btn-group">
                <button type="button" class="btn btn-convert">
                  <i class="fa fa-download" aria-hidden="true"></i> Excel
                </button>
                <button type="button" class="btn btn-convert">
                  <i class="fa fa-download" aria-hidden="true"></i> PDF
                </button>
            </div>
        </div>
        <br>
        <table class="table table-bordered datatable2 " id = "datatable2">
            <thead>
            <tr style="background: #333;color: #fff;">
                <th style="width: 12%;">Program Study</th>
                <th style="width: 20%;">Nama</th>
                <th style="width: 5%;">Payment Type</th>
                <th style="width: 5%;">BilingID</th>
                <th style="width: 10%;">Invoice</th>
                <th style="width: 5%;">Status</th>
                <th style="width: 20%;">Ket</th>
                <th style="width: 5%;">Cetak Faktur</th>
            </tr>
            </thead>
            <tbody id="dataRow"></tbody>
        </table>
    </div>
    <div  class="col-xs-12" align="right" id="pagination_link"></div>-->
</div>

<script type="text/javascript">
    window.dataa = '';
    $(document).ready(function () {
        loadSelectOptionCurriculum('#selectCurriculum','');
        loadSelectOptionBaseProdi('#selectProdi','');
        loadSelectOptionPaymentTypeMHS('#selectPTID','');
        loadSelectOptionSemester('#selectSemester',0);
        loadData(1);
        getReloadTableSocket();
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

    $('#selectSemester').change(function () {
        loadData(1);
    });

    $(document).on('keypress','#NIM', function ()
     {

         if (event.keyCode == 10 || event.keyCode == 13) {
           loadData(1);
         }
    }); // exit enter

    $(document).on("click", "#export_excel", function(event){
      /*loading_button('#export_excel');
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
      });*/
      var url = base_url_js+'finance/export_excel';
      data = {
        Data : dataa,
        Semester : $('#selectSemester').val(),
      }
      var token = jwt_encode(data,"UAP)(*");
      window.open(url+'/'+token,'_blank');

          /*$.post(url,{token:token},function (data_json) {
              var response = jQuery.parseJSON(data_json);
              //console.log(response);
              //window.location.href = base_url_js+'fileGet/'+response;
              window.open(base_url_js+'download/'+response,'_blank');
          }).done(function() {
            // loadTableEvent(loadDataEvent);
          }).fail(function() {
            toastr.error('The Database connection error, please try again', 'Failed!!');
          }).always(function() {
           $('#export_excel').prop('disabled',false).html('<i class="fa fa-download" aria-hidden="true"></i> Excel');

          });*/

    });

    $(document).on("click", ".btn-print", function(event){
      var npm = $(this).attr('npm');
      var semester = $(this).attr('semester');
      var ptid = $(this).attr('ptid');
      var va = $(this).attr('va');
      var bilingid = $(this).attr('bilingid');
      var invoice = $(this).attr('invoice');
      var nama = $(this).attr('nama');
      var prodi = $(this).attr('prodi');
      var Time = $(this).attr('Time');

      var data = {
         npm : npm,
         semester : semester,
         ptid : ptid,
         va : va,
         bilingid : bilingid,
         invoice : invoice,
         nama : nama,
         prodi : prodi,
         Time : Time,
       };
       var token = jwt_encode(data,"UAP)(*");
      window.open(base_url_js+'save2pdf/getpdfkwitansi/'+token,'_blank');

    });

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
        loading_page('#conTainJS');
        dataa = '';

        // get proses data dari database
        var ta = $('#selectCurriculum').val();
        var prodi = $('#selectProdi').val();
        var PTID = $('#selectPTID').val();
        var NIM = $('#NIM').val().trim();
        try{
          var Semester = $('#selectSemester').val();
          console.log(Semester);
          Semester = Semester.split('.');
          Semester = Semester[0];
        }
        catch(e)
        {

        }

        var url = base_url_js+'finance/get_pembayaran_mhs/'+page;
        var data = {
            ta : ta,
            prodi : prodi,
            PTID  : PTID,
            NIM : NIM,
            Semester : Semester,
        };
        var token = jwt_encode(data,'UAP)(*');
        var htmlDy = '<div class="col-md-12">'+
                        '<table class="table table-bordered datatable2 " id = "datatable2">'+
                            '<thead>'+
                            '<tr style="background: #333;color: #fff;">'+
                            '    <th style="width: 12%;">Program Study</th>'+
                            '    <th style="width: 20%;">Nama,NPM & VA</th>'+
                            '    <th style="width: 5%;">Payment Type</th>'+
                            '    <th style="width: 5%;">BilingID</th>'+
                            '    <th style="width: 10%;">Invoice</th>'+
                            '    <th style="width: 10%;">Time</th>'+
                            '    <th style="width: 20%;">Ket</th>'+
                            '    <th style="width: 5%;">Cetak Kwitansi</th>'+
                            '</tr>'+
                            '</thead>'+
                            '<tbody id="dataRow"></tbody>'+
                        '</table>'+
                    '</div>'+
                    '<div  class="col-xs-12" align="right" id="pagination_link"></div>';
   
        $.post(url,{token:token},function (resultJson) {
            var resultJson = jQuery.parseJSON(resultJson);
            console.log(resultJson);
            var Data_mhs = resultJson.loadtable;
            dataa = Data_mhs;
            setTimeout(function () {
                $("#conTainJS").html(htmlDy);

                if (Data_mhs.length > 0) {
                        for (var i = 0; i < Data_mhs.length; i++) {
                            var yy = (Data_mhs[i]['InvoiceStudents'] != '') ? formatRupiah(Data_mhs[i]['InvoiceStudents']) : '-';
                            var btnPrint = '<span data-smt="'+Data_mhs[i]['ID_payment_students']+'" class="btn btn-xs btn-print" NPM = "'+Data_mhs[i]['NPM']+'" Semester = "'+Data_mhs[i]['SemesterName']+'" PTID = "'+Data_mhs[i]['PTIDDesc']+'" VA = "'+Data_mhs[i]['VA']+'" BilingID = "'+Data_mhs[i]['BilingID']+'" Invoice = "'+yy+'" Nama = "'+Data_mhs[i]['Nama']+'"  Prodi = "'+Data_mhs[i]['ProdiEng']+'" Time = "'+Data_mhs[i]['Time']+'"><i class="fa fa-print"></i> Print</span>';
                            $('#dataRow').append('<tr>' +
                                '<td>'+Data_mhs[i]['ProdiEng']+'<br>'+Data_mhs[i]['SemesterName']+'</td>' +
                                '<td>'+Data_mhs[i]['Nama']+'<br>'+Data_mhs[i]['NPM']+'<br>'+Data_mhs[i]['VA']+'</td>' +
                                '<td>'+Data_mhs[i]['PTIDDesc']+'</td>' +
                                '<td>'+Data_mhs[i]['BilingID']+'</td>' +
                                '<td>'+yy+'</td>' +
                                '<td>'+Data_mhs[i]['Time']+'</td>' +
                                '<td>'+Data_mhs[i]['Cicilan']+'</td>' +
                                '<td>'+btnPrint+'</td>' +
                                '</tr>');
                        }
                        
                } else {
                    $('#dataRow').append('<tr><td colspan="8" align = "center">No Result Data</td></tr>');
                }
            },500);    
        }).fail(function() {
          toastr.info('No Result Data'); 
          // toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {
            // $('#NotificationModal').modal('hide');
        });
    }

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