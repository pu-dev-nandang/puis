
<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }
</style>

<div class="well">

    <div class="row">

        <div class="col-md-3 form-data-edit" style="border-right: 1px solid #CCCCCC;">
            <div class="form-group">
                <label>Year</label>
                <input class="hide" id="formID">
                <input class="form-control" id="formYear">
            </div>
            <div class="form-group">
                <label>Prodi</label>
                <select class="form-control" id="formProdiID"></select>
            </div>
            <div class="form-group">
                <label>Jumlah Mahasiswa</label>
                <input type="number" class="form-control" id="formTotalStudent">
            </div>
            <div class="form-group" style="text-align: right;">
                <button class="btn btn-primary" id="btnSave">Save</button>
            </div>

        </div>
        <div class="col-md-9">

            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="well">
                        <select class="form-control" id="filterYear"></select>
                    </div>
                </div>
            </div>

            <table class="table table-striped table-bordered" id="tableData">
                <thead>
                <tr>
                    <th style="width: 1%;">No</th>
                    <th style="width: 10%;">Year</th>
                    <th>Prodi</th>
                    <th style="width: 7%;">Total Student</th>
                    <th style="width: 10%;"><i class="fa fa-cog"></i></th>
                </tr>
                </thead>
                <tbody id="listStd"></tbody>
            </table>

        </div>


    </div>


<script>

    $(document).ready(function () {

        loadSelectOptionBaseProdi('#formProdiID','');

        filteryear();

        var firstLoad = setInterval(function () {

            var filterYear = $('#filterYear').val();
            if(filterYear!='' && filterYear!=null){
                loadDataTable();
                clearInterval(firstLoad);
            }

        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);

    });

    $('#filterYear').change(function () {

        loadDataTable();

    });

    function loadDataTable() {

        var filterYear = $('#filterYear').val();

        var data = {
            action : 'readDataMHSBaruAsing',
            Year : filterYear
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB2';

        $.post(url,{token:token},function (jsonResult) {

            $('#listStd').empty();

            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var btnAction = '<div class="btn-group">' +
                        '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
                        '  </button>' +
                        '  <ul class="dropdown-menu">' +
                        '    <li><a href="javascript:void(0);" class="btnEdit" data-id="'+v.ID+'">Edit</a></li>' +
                        '    <li role="separator" class="divider"></li>' +
                        '    <li><a href="javascript:void(0);" class="btnRemove" data-tb="db_agregator.student_selection_foreign" data-id="'+v.ID+'">Remove</a></li>' +
                        '  </ul>' +
                        '</div><textarea id="dataEdit_'+v.ID+'" class="hide">'+JSON.stringify(v)+'</textarea>';

                    $('#listStd').append('<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td>'+v.Year+'</td>' +
                        '<td style="text-align: left;">'+v.ProdiName+'</td>' +
                        '<td>'+v.TotalStudent+'</td>' +
                        '<td>'+btnAction+'</td>' +
                        '</tr>');
                });
            } else {
                $('#listStd').append('<tr><td colspan="5">Data not yet</td></tr>');
            }



        });

    }

    function filteryear() {
        var data = {
            action : 'filterYearMhsAsing'
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB2';

        $.post(url,{token:token},function (jsonResult) {

            $('#filterYear').empty();
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {
                    $('#filterYear').append('<option value="'+v.Year+'">'+v.Year+'</option>');
                })
            }

        });
    }

    $('#btnSave').click(function () {

        var formID = $('#formID').val();
        var formYear = $('#formYear').val();
        var formProdiID = $('#formProdiID').val();
        var formTotalStudent = $('#formTotalStudent').val();

        if(formYear !='' && formYear!=null &&
        formProdiID !='' && formProdiID!=null &&
        formTotalStudent !='' && formTotalStudent!=null){

            loading_buttonSm('#btnSave');

            var ProdiID = formProdiID.split('.')[0];

            var data = {
                action : 'crudMHSBaruAsing',
                ID : (formID!='' && formID!=null) ? formID : '',
                dataForm : {
                    Year : formYear,
                    ProdiID : ProdiID,
                    TotalStudent : formTotalStudent
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB2';

            $.post(url,{token:token},function (jsonResult) {

                toastr.success('Data saved','Success');

                filteryear();
                setTimeout(function () {
                    loadDataTable();
                },2000);



                setTimeout(function () {
                    $('#formID').val('');
                    $('#formYear').val('');
                    $('#formTotalStudent').val('');

                    $('#btnSave').html('Save').prop('disabled',false);

                },500);

            });

        }

    });

    $(document).on('click','.btnEdit',function () {

        var ID = $(this).attr('data-id');
        var dataEdit = $('#dataEdit_'+ID).val();

        var d = JSON.parse(dataEdit);

        $('#formID').val(d.ID);
        $('#formYear').val(d.Year);
        $('#formProdiID').val(d.ProdiID+'.'+d.ProdiCode);
        $('#formTotalStudent').val(d.TotalStudent);

    });

</script>