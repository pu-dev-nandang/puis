
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

            <div style="text-align: right;">
                <button class="btn btn-success form-data-add" id="btnLembagaMitra"><i class="fa fa-plus"></i> Luaran Penelitian dan PkM</button>
            </div>

            <div class="col-md-3 col-md-offset-4">
                <div class="thumbnail">
                    <select class="form-control" id="filterTahun">
                        <option value="" disabled="">--- Pilih Tahun ---</option>
                        <option value="1">--- Pilih Tahun 1---</option>
                        <option value="2">--- Pilih Tahun 2---</option>
                    </select>
                </div>
                <hr/>
            </div>
    
            
         
            <div id="viewTable"></div>
        </div>
    </div>
</div>

<script>
    
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
                action : 'save_hki_paten',
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
        loadhkipaten();
    });

    $('#filterTahun').change(function () {
        var status = $(this).val();
        loadhkipaten(status);
    });

    function loadhkipaten(status) {

         $('#viewTable').html(' <table class="table table-bordered" id="dataTablesLuaran">' +
            '    <thead>  '+
            '     <tr>   '+
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

        var url : base_url_js+"api3/__getHkiPaten?s="+status, // json datasource
        //var url = base_url_js+'api3/__getHkiPaten';

        $.getJSON(url,function (jsonResult) {

            if(jsonResult.length>0) {

                for (var i = 0; i < jsonResult.length; i++) {
                    var v = jsonResult[i];

                    $('#listData').append('<tr>' +
                        '   <td style="text-align: center;">'+(i+1)+'</td>' +
                        '   <td style="text-align: left;">'+v.NamaJudul+'</td>' +
                        '   <td style="text-align: center;">'+moment(v.Tahun).format('YYYY')+'</td>' +
                        '   <td style="text-align: left;">'+v.Keterangan+'</td>' +
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