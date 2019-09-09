
<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-12">

        <div style="text-align: right;margin-top: 20px;">
            <button class="btn btn-lg btn-success"><i class="fa fa-download margin-right"></i> Excel</button>
        </div>
        <table class="table table-striped" id="tableData">
            <thead>
            <tr>
                <th style="width: 1%;" rowspan="2">No</th>
                <th style="width: 15%;" rowspan="2">Jenis Program</th>
                <th rowspan="2">Nama Program Study</th>
                <th colspan="3">Akreditasi Program Study</th>
                <th style="width: 1%;" rowspan="2">Jumlah Mahasiswa</th>
                <th style="width: 1%;" rowspan="2"><i class="fa fa-cog"></i></th>
            </tr>
            <tr>
                <th style="width: 15%;">Status / Peringkat</th>
                <th style="width: 15%;">No. dan Tgl SK</th>
                <th style="width: 15%;">Tgl Kadaluarsa</th>
            </tr>
            </thead>
            <tbody id="listData"></tbody>
        </table>
        <textarea id="dataProdi" class="hide"></textarea>
    </div>
</div>


<script>
    
    $(document).ready(function () {
        loadDataProgrammeStudy();
    });
    
    function loadDataProgrammeStudy() {
        var token = jwt_encode({action:'viewAllDataProdi'},'UAP)(*');
        var url = base_url_js+'api3/__crudProgrameStudy';
        $.post(url,{token:token},function (jsonResult) {

            $('#listData').empty();
            $('#dataProdi').val(JSON.stringify(jsonResult));
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var skDate = (v.SKBANPTDate!='' && v.SKBANPTDate!=null)
                        ? moment(v.SKBANPTDate).format('DD-MM-YYYY')
                        : '';

                    var Akreditation = (v.Akreditation!='' && v.Akreditation!=null) ? v.Akreditation : '-';

                    var btnEdit = '<button class="btn btn-sm btn-default btnActEdit" data-i="'+i+'"><i class="fa fa-edit"></i></button>';

                    $('#listData').append('<tr>' +
                        '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                        '<td>'+v.Description+'</td>' +
                        '<td style="text-align: left;">'+v.Name+'</td>' +
                        '<td>'+Akreditation+'</td>' +
                        '<td>'+v.NoSKBANPT+'</td>' +
                        '<td>'+skDate+'</td>' +
                        '<td>'+v.TotalMhs+'</td>' +
                        '<td>'+btnEdit+'</td>' +
                        '</tr>');
                });
            }

        });
    }

    $(document).on('click','.btnActEdit',function () {

        var i = $(this).attr('data-i');

        var dataProdi = JSON.parse($('#dataProdi').val());

        var d = dataProdi[i];

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+d.Name+'</h4>');
        $('#GlobalModal .modal-body').html(
            '<div class="form-group">' +
            '    <label>Jenis Program</label>' +
            '    <select class="form-control" id="formEducationLevel"><option></option></select>' +
            '</div>' +
            '<div class="form-group">' +
            '    <label>Status / Peringkat</label>' +
            '    <select class="form-control" id="formAccreditation"><option></option></select>' +
            '</div>' +
            '<div class="form-group">' +
            '    <label>No. dan Tgl SK</label>' +
            '    <input class="form-control" id="formNoSKBANPT" value="'+d.NoSKBANPT+'" />' +
            '</div>' +
            '<div class="form-group">' +
            '    <label>Tgl Kadaluarsa</label>' +
            '    <input class="form-control" style="background-color: #ffffff !important;cursor: pointer;color: #333333;" id="formSKBANPTDate" readonly />' +
            '</div>');

        $( "#formSKBANPTDate" )
            .datepicker({
                showOtherMonths:true,
                autoSize: true,
                dateFormat: 'dd MM yy'
            });



        (d.SKBANPTDate!=='0000-00-00' && d.SKBANPTDate!==null) ?
            $('#formSKBANPTDate').datepicker('setDate',new Date(d.SKBANPTDate))
            : '';

        var EducationLevel = (d.EducationLevelID!='' && d.EducationLevelID!=null) ? d.EducationLevelID : '';
        var Accreditation = (d.AccreditationID!='' && d.AccreditationID!=null) ? d.AccreditationID : '';
        loadSelectOptionEducationLevel('#formEducationLevel',EducationLevel);
        loadSelectOptionAccreditation('#formAccreditation',Accreditation);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '<button type="button" class="btn btn-primary" id="btnSave">Save</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        $('#btnSave').click(function () {

            var formEducationLevel = $('#formEducationLevel').val();
            var formAccreditation = $('#formAccreditation').val();
            var formNoSKBANPT = $('#formNoSKBANPT').val();
            var formSKBANPTDate = $('#formSKBANPTDate').datepicker("getDate");



            if(formEducationLevel!='' && formEducationLevel!=null &&
                formAccreditation!='' && formAccreditation!=null &&
            formNoSKBANPT!='' && formNoSKBANPT!=null &&
            formSKBANPTDate!='' && formSKBANPTDate!=null){

                loading_buttonSm('#btnSave');

                var SKBANPTDate = moment(formSKBANPTDate).format('YYYY-MM-DD')

                var data = {
                    action : 'updateProgrammeStudy',
                    ID : d.ID,
                    dataForm : {
                        EducationLevelID : formEducationLevel,
                        AccreditationID : formAccreditation,
                        NoSKBANPT : formNoSKBANPT,
                        SKBANPTDate : SKBANPTDate
                    }
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api3/__crudProgrameStudy';

                $.post(url,{token:token},function (result) {
                    loadDataProgrammeStudy();
                    toastr.success('Data saved','Success');
                    setTimeout(function () {
                        $('#GlobalModal').modal('hide');
                    },500);
                });

            }



        });

    });
    
</script>