
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
            <p style="color:#3968c6;"><b> HKI (Hak Cipta, Desain Produk Industri, dan lainnya)</b></p>
            <div style="text-align: right;margin-bottom: 20px;">
                <button class="btn btn-primary form-data-add" id="btnLembagaMitra"><i class="fa fa-plus"></i> Luaran Penelitian dan PkM</button>
                <button id="saveToExcel" class="btn btn-success"><i class="fa fa-file-excel-o margin-right"></i> Excel</button>
            </div>
            <div id="viewTable"></div>
        </div>
    </div>
</div>

<script>
    var oTable;
    var oSettings;

     $('#btnLembagaMitra').click(function () {

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">HKI (Hak Cipta, Desain Produk Industri, dll.)</h4>');

        var body = '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <div class="well">' +
            '            <div class="form-group">' +
            '                <label>Judul Penelitian dan PkM </label> '+
            '                <input class="hide" id="formID">' +
            '                <input class="form-control" id="judul">' +
            '            </div>' +
            '          <div class="form-group">' +
            '                <label>Tahun Perolehan </label> '+
            '                <input class="form-control" id="tahun_perolehan" ></input>' +
            '            </div>' +
            '            <div class="form-group">' +
             '                <label>Keterangan </label> '+
            '                 <textarea class="form-control" id="keterangan"></textarea>' +
            '            </div>' +
            
            '        </div>' +
            '    </div>' +
            '    ' +
        
            '</div>';

        $('#GlobalModal .modal-body').html(body);
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button class="btn btn-success" style="text-align: right;" id="btnSaveLembaga">Save</button>');

         $( "#tahun_perolehan" )
            .datepicker({
                changeYear: true,
                viewMode: 'years', 
                autoclose: true,
                dateFormat: 'yy',
                // minDate: new Date(moment().year(),moment().month(),moment().date()),
                onSelect : function () {
                    // var data_date = $(this).val().split(' ');
                    // var nextelement = $(this).attr('nextelement');
                    // nextDatePick(data_date,nextelement);
                }
        });

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });


     $('#saveToExcel').click(function () {

        $('select[name="dataTablesLuaran_length"]').val(-1);

        oSettings[0]._iDisplayLength = oSettings[0].fnRecordsTotal();
        oTable.draw();

        setTimeout(function () {
            saveTable2Excel('dataTable2Excel');
        },1000);
    });

</script>

<script>
     $(document).on('click','#btnSaveLembaga',function () {

        var judul = $('#judul').val();
        var tahun_perolehan = $('#tahun_perolehan').val();
        var keterangan = $('#keterangan').val();
        var nama_judul =  judul.toUpperCase();

        if(judul!='' && judul!=null &&
            tahun_perolehan!='' && tahun_perolehan!=null &&
            keterangan!='' && keterangan!=null) {

            var data = {
                action : 'save_hki_produk',
                //ID : (formID!='' && formID!=null) ? formID : '',
                dataForm : {
                    Nama_judul : nama_judul,
                    Tahun_perolehan : tahun_perolehan,
                    Keterangan : keterangan
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
                    $('#btnSaveLembaga').html('Save').prop('disabled',false);
                },500);
            });

        } else {
            toastr.error('All form required','Error');
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
            '     <tr style="background: #20485A;color: #FFFFFF;">   '+
            '        <th style="text-align: center; width: 5%;">No</th>  '+
            '        <th style="text-align: center;">Luaran Penelitian dan PkM</th>  '+
            '        <th style="text-align: center; width: 15%;">Tahun Perolehan (YYYY)</th>  '+
            '        <th style="text-align: center;">Keterangan</th>  '+
            '    </tr>  '+
            '    </thead>  '+
            '       <tbody id="listData"></tbody>   '+
            //'    <tfoot id="listDataFoot">  </tfoot>'+
            '    </tfoot> '+
            '    </table>');

        var url = base_url_js+'api3/__getHkiProduk';
        $.getJSON(url,function (jsonResult) {

            if(jsonResult.length>0) {

                for (var i = 0; i < jsonResult.length; i++) {
                    var v = jsonResult[i]; 

                    $('#listData').append('<tr>' +
                        '   <td style="text-align: center;">'+(i+1)+'</td>' +
                        '   <td style="text-align: left;">'+v.Nama_judul+'</td>' +
                        '   <td style="text-align: center;">'+v.Tahun_perolehan+'</td>' +
                        '   <td style="text-align: left;">'+v.Keterangan+'</td>' +
                        '</tr>');
                    var total = parseInt(jsonResult.length);
                }
            }
                
            //$('#dataTablesLuaran').dataTable();
            oTable = $('#dataTablesLuaran').DataTable();
            oSettings = oTable.settings();
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