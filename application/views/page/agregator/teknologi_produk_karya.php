
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
            '<h4 class="modal-title">Teknologi Tepat Guna, Produk, Karya Seni, Rekayasa Sosial</h4>');

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
            '          <div class="form-group">' +
            '                <label>Program Studi </label> '+
            '                <select class="form-control" id="prodi"></select>' +
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
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Close</button> <button class="btn btn-success" style="text-align: right;" id="btnSaveLembaga"> <i class="glyphicon glyphicon-floppy-disk"></i> Save</button>');

         $( "#tahun_perolehan" )
            .datepicker({
                changeYear: true,
                viewMode: 'years', 
                autoclose: true,
                dateFormat: 'yy',
                onSelect : function () {
                   
                }
        });

        var url = base_url_js+'api3/__crudAllProgramStudy';
        var token = jwt_encode({action : 'viewAllProdi'},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            $('#formYear').append('<option disabled selected></option>');
                for(var i=0;i<jsonResult.length;i++){
                   $('#prodi').append('<option id="'+jsonResult[i].ID+'"> '+jsonResult[i].Name+' </option>');
                }
            });

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

</script>

<script>
    // $('#btnSaveLembaga').click(function () {
     $(document).on('click','#btnSaveLembaga',function () {

        var judul = $('#judul').val();
        var tahun_perolehan = $('#tahun_perolehan').val();
        var prodi_id = $('#prodi option:selected').attr('id');
        var keterangan = $('#keterangan').val();
        var nama_judul =  judul.toUpperCase();

        if(judul!='' && judul!=null &&
            tahun_perolehan!='' && tahun_perolehan!=null &&
            keterangan!='' && keterangan!=null) {

            var data = {
                action : 'save_tekno_produk',
                dataForm : {
                    Nama_judul : nama_judul,
                    Tahun_perolehan : tahun_perolehan,
                    ProdiID : prodi_id,
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


 $('#saveToExcel').click(function () {
        alert('aaa');

        $('select[name="dataTablesLuaran_length"]').val(-1);

        oSettings[0]._iDisplayLength = oSettings[0].fnRecordsTotal();
        oTable.draw();

        setTimeout(function () {
            saveTable2Excel('dataTable2Excel');
        },1000);
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
        //load_prodi();
    });

    function load_prodi() {

        var url = base_url_js+'api3/crudAllProgramStudy';
        var token = jwt_encode({action : 'viewAllProdi'},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            $('#formYear').append('<option disabled selected></option>');
                for(var i=0;i<jsonResult.length;i++){
                   $('#prodi').append('<option id="'+jsonResult[i].ID+'"> '+jsonResult[i].Name+' </option>');
                }
            });
      }

    function loadAkreditasiProdi() {

         $('#viewTable').html(' <table class="table table-bordered dataTable2Excel" id="dataTablesLuaran">' +
            '    <thead>  '+
            '     <tr style="background: #20485A;color: #FFFFFF;">   '+
            '        <th style="text-align: center; width: 5%;">No</th>  '+
            '        <th style="text-align: center;">Luaran Penelitian dan PkM</th>  '+
            '        <th style="text-align: center; width: 15%;">Tahun Perolehan (YYYY)</th>  '+
            '        <th style="text-align: center;">Keterangan</th>  '+
            '    </tr>  '+
            '    </thead>  '+
            '       <tbody id="listData"></tbody>   '+
            '    </tfoot> '+
            '    </table>');

        var url = base_url_js+'api3/__getTeknoProduk';
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
                    //total = total + parseInt(v2.dataEmployees.length);
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