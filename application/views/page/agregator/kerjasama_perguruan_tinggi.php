

<style>
    #tableData tr th {
        text-align: center;
    }
</style>

<div class="well">
    <div class="row">

        <div class="col-md-3 form-data-edit" style="border-right: 1px solid #CCCCCC;">

            <div style="text-align: right;margin-bottom: 20px;">
                <button class="btn btn-default" id="btnLembagaMitra"><i class="fa fa-cog margin-right"></i> Lembaga Mitra Kerjasama</button>
            </div>

            <div class="form-group">
                <label>Lembaga Mitra Kerjasama</label>
                <input class="hide" id="formID" />
                <select id="formLembagaMitraID" class="form-control"></select>
            </div>
            <div class="form-group">
                <label>Tingakat</label>
                <select class="form-control" id="formTingkat">
                    <option value="Internasional">Internasional</option>
                    <option value="Nasional">Nasional</option>
                    <option value="Lokal">Wilayah / Lokal</option>
                </select>
            </div>
            <div class="form-group">
                <label>Bentuk Kegiatan / Manfaat</label>
                <textarea class="form-control" id="formBenefit"></textarea>
            </div>
            <div class="form-group">
                <label>Masa Berlaku</label>
                <input class="form-control" id="formDueDate" />
            </div>
            <div class="form-group">
                <label>Bukti Kerjasama (.pdf | Maks 8 Mb)</label>
                <input type="file">
            </div>

            <div style="text-align: right;">
                <button class="btn btn-primary" id="btnSave">Save</button>
            </div>

        </div>
        <div class="col-md-9">

            <div>
                <table class="table table-striped table-bordered" id="tableData">
                    <thead>
                    <tr>
                        <th style="width: 1%">No</th>
                        <th style="width: 25%">Lembaga Mitra Kerjasama</th>
                        <th style="width: 7%;">Tingkat</th>
                        <th>Bentuk Kegiatan / Manfaat</th>
                        <th style="width: 15%">Bukti Kerjasama</th>
                        <th style="width: 15%">Masa Berlaku</th>
                        <th style="width: 5%"><i class="fa fa-cog"></i></th>
                    </tr>
                    </thead>
                </table>
            </div>

        </div>

    </div>
</div>

<script>

    $(document).ready(function () {

        window.act = "<?= $accessUser; ?>";
        if(parseInt(act)<=0){
            $('.form-data-edit').remove();
        } else {
            loadDataLembagaMitra();
            $( "#formDueDate" )
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
        }

    });

    $('#btnLembagaMitra').click(function () {

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Lembaga Mitra Kerjasama</h4>');

        var body = '<div class="row">' +
            '    <div class="col-md-5">' +
            '        <div class="well">' +
            '            <div class="form-group">' +
            '                <input class="hide" id="formID">' +
            '                <input class="form-control" id="formLembaga" placeholder="Input lembaga..">' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <textarea class="form-control" id="formDescription" placeholder="Input description..."></textarea>' +
            '            </div>' +
            '            <div>' +
            '                <button class="btn btn-success" id="btnSaveLembaga">Save</button>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    ' +
            '    <div class="col-md-7">' +
            '        <table class="table table-striped" id="tableViewLemabagaSurview">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Lembaga</th>' +
            '                <th style="width: 2%;"><i class="fa fa-cog"></i></th>' +
            '            </tr>' +
            '            </thead>' +
            '           <tbody id="listLembaga"></tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>';

        $('#GlobalModal .modal-body').html(body);

        loadDataLembagaMitra();

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        $('#btnSaveLembaga').click(function () {

            var formID = $('#formID').val();
            var formLembaga = $('#formLembaga').val();
            var formDescription = $('#formDescription').val();

            if(formLembaga!='' && formLembaga!=null &&
                formDescription!='' && formDescription!=null){

                loading_buttonSm('#btnSaveLembaga');

                var data = {
                    action : 'updateLembagaMitraKerjasama' ,
                    ID : (formID!='' && formID!=null) ? formID : '',
                    Lembaga : formLembaga,
                    Description : formDescription
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api3/__crudAgregatorTB1';

                $.post(url,{token:token},function (jsonResult) {

                    $('#formID').val('');
                    $('#formLembaga').val('');
                    $('#formDescription').val('');

                    toastr.success('Data saved','Success');

                    loadDataLembagaMitra();

                    setTimeout(function () {

                        $('#btnSaveLembaga').html('Save').prop('disabled',false);

                    },500);

                });

            } else {
                toastr.warning('All form is required','Warning');
            }




        });

    });
    
    function loadDataLembagaMitra() {

        var url = base_url_js+'api3/__crudAgregatorTB1';
        var token = jwt_encode({action : 'readLembagaMitraKerjasama'},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            $('#listLembaga,#formLembagaMitraID').empty();

            if(jsonResult.length>0){

                $.each(jsonResult,function (i,v) {
                    var no = i+1;
                    $('#listLembaga').append('<tr>' +
                        '<td>'+no+'</td>' +
                        '<td style="text-align: left;"><b>'+v.Lembaga+'</b><br/>'+v.Description+'</td>' +
                        '<td><button class="btn btn-default btn-sm btnEditLV" data-no="'+no+'"><i class="fa fa-edit"></i></button>' +
                        '<textarea id="btnEditLV_'+no+'" class="hide">'+JSON.stringify(v)+'</textarea></td>' +
                        '</tr>');

                    $('#formLembagaMitraID').append('<option value="'+v.ID+'">'+v.Lembaga+'</option>');
                });

            } else {
                $('#listLembaga').append('<tr><td colspan="3">-- Tidak ada data --</td></tr>');
            }

        });

    }

    $(document).on('click','.btnEditLV',function () {

        var no = $(this).attr('data-no');
        var dataForm = $('#btnEditLV_'+no).val();
        var dataForm = JSON.parse(dataForm);

        $('#formID').val(dataForm.ID);
        $('#formLembaga').val(dataForm.Lembaga);
        $('#formDescription').val(dataForm.Description);

    });
</script>