
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
            <p style="color:#3968c6;"><b> Rekognisi Dosen</b></p>
            <div style="text-align: right;margin-bottom: 20px;">
                <button class="btn btn-success form-data-add" id="btnLembagaMitra"><i class="fa fa-plus"></i> Rekognisi Dosen</button>
            </div>
            <div id="viewTable"></div>
        </div>
    </div>
</div>

<script>
    
     $('#btnLembagaMitra').click(function () {

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Tambah Data Rekognisi Dosen</h4>');

        var body = '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <div class="well">' +
            '            <div class="form-group">' +
            '                <label>Nama Dosen </label> '+
            '                <input class="hide" id="formID">' +
            '                <input class="form-control" id="nama_dosen">' +
            '            </div>' +
            '          <div class="form-group">' +
            '                <label>Bidang Keahlian </label> '+
            '                <input class="form-control" id="bidang_keahlian" ></input>' +
            '            </div>' +
            '          <div class="form-group">' +
            '                <label>Rekognisi </label> '+
            '                <input class="form-control" id="rekognisi" ></input>' +
            '            </div>' +
            '            <div class="form-group">' +
             '                <label>Tahun </label> '+
            '                 <input class="form-control" id="tahun"></input>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    ' +
            '</div>';

        $('#GlobalModal .modal-body').html(body);
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button class="btn btn-success" style="text-align: right;" id="btnSaveRekognisi">Save</button>');

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

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

</script>

<script>

     $(document).on('click','#btnSaveRekognisi',function () {

        var nama_dosen = $('#nama_dosen').val();
        var bidang_keahlian = $('#bidang_keahlian').val();
        var rekognisi = $('#rekognisi').val();
        var tahun = $('#tahun').val();
        
        if(nama_dosen!='' && nama_dosen!=null &&
            bidang_keahlian!='' && bidang_keahlian!=null &&
            rekognisi!='' && rekognisi!=null &&
            tahun!='' && tahun!=null) {

            var data = {
                action : 'save_rekognisi_dosen',
                //ID : (formID!='' && formID!=null) ? formID : '',
                dataForm : {
                    ID_Dosen : nama_dosen,
                    Bidang_keahlian : bidang_keahlian,
                    Rekognisi : rekognisi,
                    Tahun : tahun
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB3';

            $.post(url,{token:token},function (result) {

                if(result==0 || result=='0'){
                  toastr.error('Maaf, Gagal simpan data !','Error');
                } 
                else {  
                    loadRekognisiDosen();
                    $('#GlobalModal').modal('hide');
                    toastr.success('Data saved','Success');
                    $('#nama_dosen').val('');
                    $('#bidang_keahlian').val('');
                    $('#rekognisi').val('');
                    $('#tahun').val('');
                }

                setTimeout(function (args) {
                    $('#btnSaveRekognisi').html('Save').prop('disabled',false);
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

        loadRekognisiDosen();
    });

    function loadRekognisiDosen() {

         $('#viewTable').html(' <table class="table table-bordered" id="dataTablesLuaran">' +
            '    <thead>  '+
            '     <tr>   '+
            '        <th style="text-align: center; width: 5%;">No</th>  '+
            '        <th style="text-align: center;">Nama Dosen</th>  '+
            '        <th style="text-align: center; width: 15%;">Keahlian</th>  '+
            '        <th style="text-align: center; width: 15%;">Rekognisi</th>  '+
            '        <th style="text-align: center;">Tahun</th>  '+
            '    </tr>  '+
            '    </thead>  '+
            '       <tbody id="listData"></tbody>   '+
            '    </tfoot> '+
            '    </table>');

        var url = base_url_js+'api3/__getRekognisiDosen';
        $.getJSON(url,function (jsonResult) {

            if(jsonResult.length>0) {

                for (var i = 0; i < jsonResult.length; i++) {
                    var v = jsonResult[i]; 
                    //var tahun = moment(v.Tgl_terbit).format('YYYY');

                    $('#listData').append('<tr>' +
                        '   <td style="text-align: center;">'+(i+1)+'</td>' +
                        '   <td style="text-align: left;">'+v.ID_Dosen+'</td>' +
                        '   <td style="text-align: center;">'+v.Bidang_keahlian+'</td>' +
                         '   <td style="text-align: left;">'+v.Rekognisi+'</td>' +
                        '   <td style="text-align: left;">'+v.Tahun+'</td>' +
                        '</tr>');

                    var total = parseInt(jsonResult.length);
                    //total = total + parseInt(v2.dataEmployees.length);
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