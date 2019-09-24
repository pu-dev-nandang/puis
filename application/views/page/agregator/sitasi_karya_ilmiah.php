
<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }

    .td-av {
        background: #ffe7c4;
        font-weight: bold;
    }
    .higligh {
        background: lightyellow;
    }
    
</style>

<div class="well">
    <div class="row">
        <div class="col-md-12">
            <div style="text-align: right;margin-bottom: 20px;">
                <button class="btn btn-success form-data-add" id="btnLembagaMitra"><i class="fa fa-plus"></i> Sitasi Karya Ilmiah</button>
            </div>
            <div id="viewTable"></div>
        </div>
    </div>
</div>

<script>
    
     $('#btnLembagaMitra').click(function () {

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title"> Tambah Sitasi Karya Ilmiah</h4>');

        var body = '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <div class="well">' +
            '        <div class="form-group">' +
            '           <label>Nama Dosen Penulis</label>' + 
            '              <select class="full-width-fix" size="5" id="nama_penulis" style="width: 100%;" size="5"><option></option></select>' +
            '        </div>' +
            '            <div class="form-group">' +
            '                <label>Judul Artikel yang Disitasi </label> '+
            '                <input class="form-control" id="judul_artikel">' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <label>Banyaknya Artikel yang Mensitasi </label> '+
            '                 <input class="form-control" id="banyak_artikel"></input>' +
            '            </div>' +
            '          <div class="form-group">' +
            '                <label>Tahun </label> '+
            '                <input class="form-control" id="tahun"></input>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    ' +
            '</div>';

        $('#GlobalModal .modal-body').html(body);
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button class="btn btn-success" style="text-align: right;" id="btnSaveSitasi">Save</button>');

        // --------------------------------
        $(function() { 
          $('#tahun').datepicker( {
            yearRange: "c-100:c",
            changeMonth: false,
            changeYear: true,
            showButtonPanel: true,
            closeText:'Pilih',
            currentText: 'This year',
            onClose: function(dateText, inst) {
              var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
              $(this).val($.datepicker.formatDate('yy', new Date(year, 1, 1)));
            }
          }).focus(function () {
            $(".ui-datepicker-month").hide();
            $(".ui-datepicker-calendar").hide();
            $(".ui-datepicker-current").hide();
            $(".ui-datepicker-prev").hide();
            $(".ui-datepicker-next").hide();
            $("#ui-datepicker-div").position({
              my: "left top",
              at: "left bottom",
              of: $(this)
            });
          }).attr("readonly", false);
        });
        // --------------------------------
        loadSelectOptionLecturersSingle('#nama_penulis','');
        $('#nama_penulis').select2({allowClear: true});
        
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

</script>

<script>
     $(document).on('click','#btnSaveSitasi',function () {

        var nama_penulis = $('#nama_penulis').val();
        var judul_artikel = $('#judul_artikel').val();
        var banyak_artikel = $('#banyak_artikel').val();
        var tahun = $('#tahun').val();
        var nama_judul =  judul_artikel.toUpperCase();

        if(nama_penulis!='' && nama_penulis!=null &&
            judul_artikel!='' && judul_artikel!=null &&
            banyak_artikel!='' && banyak_artikel!=null &&
            tahun!='' && tahun!=null) {

            var data = {
                action : 'save_sitasi_karya',
                dataForm : {
                    NIP_penulis : nama_penulis,
                    Judul_artikel : nama_judul,
                    Banyak_artikel : banyak_artikel,
                    Tahun : tahun
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB5';

            $.post(url,{token:token},function (result) {

                if(result==0 || result=='0'){
                  toastr.error('Maaf, Gagal simpan data !','Error');
                } 
                else {  
                    loadAkreditasiProdi();
                    $('#GlobalModal').modal('hide');
                    toastr.success('Data saved','Success');
                    $('#judul').val('');
                    $('#tahun_perolehan').val('');
                    $('#keterangan').val('');
                }

                setTimeout(function (args) {
                    $('#btnSaveSitasi').html('Save').prop('disabled',false);
                },500);
            });

        } else {
            toastr.error('Soory, All form required','Error');
      }
    });    


</script>

<script>
    $(document).ready(function () {

        window.act = "<?= $accessUser; ?>";
        if(parseInt(act)<=0){
            $('.form-data-add').remove();
        } else {
        }

        loadAkreditasiProdi();
    });

    function loadAkreditasiProdi() {

         $('#viewTable').html(' <table class="table table-bordered" id="dataTablesLuaran">' +
            '    <thead>  '+
            '     <tr>   '+
            '        <th style="text-align: center; width: 5%;">No</th>  '+
            '        <th style="text-align: center;"> Nama Penulis</th>  '+
            '        <th style="text-align: center;"> Judul Artikel yang Disitasi (Jurnal, Volume, Tahun, Nomor, Halaman)  </th>'+
            '        <th style="text-align: center; width: 12%;">Banyaknya Artikel yang Mensitasi</th>  '+
            '        <th style="text-align: center; width: 8%;">Tahun (YYYY)</th>  '+
            '    </tr>  '+
            '    </thead>  '+
            '       <tbody id="listData"></tbody>   '+
            //'    <tfoot id="listDataFoot">  </tfoot>'+
            '    </tfoot> '+
            '    </table>');

        var url = base_url_js+'api3/__getSitasiKarya';
        $.getJSON(url,function (jsonResult) {

            if(jsonResult.length>0) {

                for (var i = 0; i < jsonResult.length; i++) {
                    var v = jsonResult[i]; 

                    $('#listData').append('<tr>' +
                        '   <td style="text-align: center;">'+(i+1)+'</td>' +
                        '   <td style="text-align: left;">'+v.Name+'</td>' +
                        '   <td style="text-align: left;">'+v.Judul_artikel+'</td>' +
                        '   <td style="text-align: center;">'+v.Banyak_artikel+'</td>' +
                        '   <td style="text-align: center;">'+v.Tahun+'</td>' +
                        '</tr>');
                    var total = parseInt(jsonResult.length);
                }
            }
                
            $('#dataTablesLuaran').dataTable();
        });

    }

    function hitungRow(cl) {

        var res = 0;
        $(cl).each(function () {
            res += parseInt($(this).attr('data-val'));
        });

        return res;
    }

</script>