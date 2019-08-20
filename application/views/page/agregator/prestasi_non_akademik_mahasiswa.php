
<style>
    #dataTablesPAM tr th, #dataTablesPAM tr td {
        text-align: center;
    }
</style>

<div class="well">

    <div class="row">

        <div class="col-md-3 form-data-edit" style="border-right: 1px solid #CCCCCC;">

            <div class="form-group">
                <label>Nama Kegiatan</label>
                <input class="hide" id="formID">
                <input class="form-control" id="formKegiatan">
            </div>
            <div class="form-group">
                <label>Waktu Penyelenggaraan</label>
                <input class="form-control" id="formWaktuPenyelenggaraan" style="color: #333333;" readonly>
            </div>
            <div class="form-group">
                <label>Tingkat</label>
                <select class="form-control" id="formTingkat">
                    <option value="Provinsi">Provinsi / Wilayah</option>
                    <option value="Nasional">Nasional</option>
                    <option value="Internasional">Internasional</option>
                </select>
            </div>
            <div class="form-group">
                <label>Prestasi yang dicapai</label>
                <input class="form-control" id="formPrestasi">
            </div>

            <div class="form-group" style="text-align: right;">
                <button class="btn btn-primary" id="btnSavePAM">Save</button>
            </div>

        </div>
        <div class="col-md-9">

            <div id="viewTable"></div>


        </div>

    </div>

</div>

<script>

    $(document).ready(function () {

        loadDataPAM();

        $( "#formWaktuPenyelenggaraan" )
            .datepicker({
                showOtherMonths:true,
                autoSize: true,
                dateFormat: 'dd MM yy',
                // minDate: new Date(moment().year(),moment().month(),moment().date()),
                onSelect : function () {
                    // var data_date = $(this).val().split(' ');
                    // var nextelement = $(this).attr('nextelement');
                    // nextDatePick(data_date,nextelement);
                }
            });
    });


    $('#btnSavePAM').click(function () {

        var formID = $('#formID').val();
        var formKegiatan = $('#formKegiatan').val();
        var formWaktuPenyelenggaraan = $('#formWaktuPenyelenggaraan').datepicker("getDate");
        var formTingkat = $('#formTingkat').val();
        var formPrestasi = $('#formPrestasi').val();

        if(formKegiatan!='' && formKegiatan!=null &&
            formWaktuPenyelenggaraan!='' && formWaktuPenyelenggaraan!=null &&
            formTingkat!='' && formTingkat!=null &&
            formPrestasi!='' && formPrestasi!=null){

            loading_buttonSm('#btnSavePAM');

            var data = {
                action : 'updatePAM',
                ID : (formID!='' && formID!=null) ? formID : '',
                dataForm : {
                    Kegiatan : formKegiatan,
                    WaktuPenyelenggaraan : moment(formWaktuPenyelenggaraan).format('YYYY-MM-DD'),
                    Tingkat : formTingkat,
                    Prestasi : formPrestasi,
                    Type : '2'
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB5';

            $.post(url,{token:token},function () {
                loadDataPAM();
                toastr.success('Data saved','Success');

                $('#formID').val('');
                $('#formKegiatan').val('');
                $('#formWaktuPenyelenggaraan').val('');
                $('#formPrestasi').val('');

                setTimeout(function (args) {
                    $('#btnSavePAM').html('Save').prop('disabled',false);
                },500);
            });

        } else {
            toastr.error('All form required','Error');
        }

    });

    function loadDataPAM() {

        $('#viewTable').html(' <table class="table" id="dataTablesPAM">' +
            '                <thead>' +
            '                <tr>' +
            '                    <th style="width: 1%;">No</th>' +
            '                    <th>Kegiatan</th>' +
            '                    <th style="width: 15%;">Waktu</th>' +
            '                    <th style="width: 15%;">Tingkat</th>' +
            '                    <th style="width: 20%;">Prestasi</th>' +
            '                    <th style="width: 10%;"><i class="fa fa-cog"></i></th>' +
            '                </tr>' +
            '                </thead>' +
            '                <tbody id="listData"></tbody>' +
            '            </table>');


        var data = {
            action : 'viewPAM',
            Type : '2'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB5';

        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var btn = '<div class="btn-group">' +
                        '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        '    <i class="fa fa-edit"></i> <span class="caret"></span>' +
                        '  </button>' +
                        '  <ul class="dropdown-menu">' +
                        '    <li><a href="javascript:void(0);" data-id="'+v.ID+'" class="btnEditMAP">Edit</a></li>' +
                        '    <li role="separator" class="divider"></li>' +
                        '    <li><a href="javascript:void(0);" data-id="'+v.ID+'" class="btnRemoveMAP">Remove</a></li>' +
                        '  </ul>' +
                        '</div>' +
                        '<textarea id="viewData_'+v.ID+'" class="hide">'+JSON.stringify(v)+'</textarea>';

                    $('#listData').append('<tr>' +
                        '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.Kegiatan+'</td>' +
                        '<td>'+moment(v.WaktuPenyelenggaraan).format('DD MMM YYYY')+'</td>' +
                        '<td>'+v.Tingkat+'</td>' +
                        '<td>'+v.Prestasi+'</td>' +
                        '<td style="border-left: 1px solid #ccc;">'+btn+'</td>' +
                        '</tr>');

                });
            }

            $('#dataTablesPAM').dataTable();


        });
    }

    $(document).on('click','.btnEditMAP',function () {

        var ID = $(this).attr('data-id');
        var viewData = $('#viewData_'+ID).val();
        var d = JSON.parse(viewData);


        $('#formID').val(d.ID);
        $('#formKegiatan').val(d.Kegiatan);

        $('#formTingkat').val(d.Tingkat);
        $('#formPrestasi').val(d.Prestasi);

        $('#formWaktuPenyelenggaraan').datepicker('setDate',new Date(d.WaktuPenyelenggaraan));

    });

    $(document).on('click','.btnRemoveMAP',function () {

        if(confirm('Hapus data?')){
            var ID = $(this).attr('data-id');
            var data = {
                action : 'removePAM',
                ID : ID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB5';

            $.post(url,{token:token},function (jsonResult) {

                loadDataPAM();
                toastr.success('Data removed','Success');

            });

        }


    });
</script>