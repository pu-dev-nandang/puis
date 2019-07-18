

<style>
    #dataTable tr th, #dataTable tr td {
        text-align: center;
    }
</style>

<div class="well">
    <div class="row">

        <div class="col-md-3 form-data-edit" style="border-right: 1px solid #CCCCCC;">
            <div class="form-group">
                <label>Lembaga</label>
                <input class="hide" id="formID" />
                <select class="form-control" id="formLembagaID"></select>
            </div>
            <div class="form-group">
                <label>Program Studi</label>
                <select class="form-control" id="formProdiID"></select>
            </div>
            <div class="form-group">
                <label>Status / Peringkat</label>
                <input class="form-control" id="formStatus">
            </div>
            <div class="form-group">
                <label>Masa berlaku</label>
                <input class="form-control" id="formDueDate" />
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control" id="formDescription" rows="3"></textarea>
            </div>
            <div class="form-group" style="text-align: right;">
                <button class="btn btn-primary" id="saveForm">Save</button>
            </div>
        </div>
        <div class="col-md-9">
            <div style="min-height: 30px;" id="viewData"></div>
        </div>

    </div>
</div>


<script>
    $(document).ready(function () {

        window.act = "<?= $accessUser; ?>";
        if(parseInt(act)<=0){
            $('.form-data-edit').remove();
        } else {
            loadSelectOptionLembaga('#formLembagaID','');
            loadSelectOptionBaseProdi('#formProdiID','');
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

        loadDataTable();
    });

    $('#saveForm').click(function () {

        var formID = $('#formID').val();
        var formLembagaID = $('#formLembagaID').val();
        var formProdiID = $('#formProdiID').val();
        var formStatus = $('#formStatus').val();
        var formDueDate = $('#formDueDate').datepicker("getDate");
        var formDescription = $('#formDescription').val();

        if(formLembagaID !='' && formLembagaID!=null &&
            formProdiID !='' && formProdiID!=null &&
        formStatus !='' && formStatus!=null &&
        formDueDate !='' && formDueDate!=null &&
        formDescription !='' && formDescription!=null){

            loading_button('#saveForm');
            var url = base_url_js+'api3/__crudInternationalAccreditation';
            var data = {
                action : 'updateIAP',
                ID : formID,
                dataForm : {
                    LembagaID : formLembagaID,
                    ProdiID : formProdiID,
                    Status : formStatus,
                    DueDate : formDueDate,
                    Description : formDescription,
                    EntredBy : sessionNIP
                }
            };

            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (result) {

                $('#formID').val('');
                $('#formLembagaID').val('');
                $('#formProdiID').val('');
                $('#formStatus').val('');
                $('#formDueDate').val('');
                $('#formDescription').val('');

                toastr.success('Data saved','Success');

                loadDataTable();

                setTimeout(function () {
                    $('#saveForm').html('Save').prop('disabled',false);
                },500);

            });

        }

    });

    function loadDataTable() {

        $('#viewData').html('<table class="table table-striped table-bordered" id="tableData">' +
            '                    <thead>' +
            '                    <tr>' +
            '                        <th style="width: 1%;">No</th>' +
            '                        <th style="width: 15%;">Lembaga</th>' +
            '                        <th style="width: 15%;">Program Studi</th>' +
            '                        <th style="width: 10%;">Status</th>' +
            '                        <th style="width: 15%;">Masa Berlaku</th>' +
            '                        <th>Keterangan</th>' +
            '                        <th style="width: 1%;"><i class="fa fa-cog"></i></th>' +
            '                    </tr>' +
            '                    </thead>' +
            '                </table>');

        var token = jwt_encode({action:'viewListIA',Previlege:act},'UAP)(*');
        var url = base_url_js+'api3/__crudInternationalAccreditation';

        var dataTable = $('#tableData').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Lembaga, Sertifikat, Lingkup, Tingkat"
            },
            "ajax":{
                url : url, // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );

    }

    $(document).on('click','.btnEdit',function () {

        var no = $(this).attr('data-no');
        var dataDetail = $('#viewDetail_'+no).val();
        dataDetail = JSON.parse(dataDetail);


        $('#formID').val(dataDetail.ID);
        $('#formLembagaID').val(dataDetail.LembagaID);
        $('#formProdiID').val(dataDetail.ProdiID+'.'+dataDetail.ProdiCode);
        $('#formStatus').val(dataDetail.Status);
        $('#formDescription').val(dataDetail.Description);

        (dataDetail.DueDate!=='0000-00-00' && dataDetail.DueDate!==null)
            ? $('#formDueDate').datepicker('setDate',new Date(dataDetail.DueDate))
            : '';

    });

</script>