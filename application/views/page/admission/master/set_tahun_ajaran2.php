
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div id="loadPage"></div>
    </div>
</div>


<script>

    $(document).ready(function () {

        var body = '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <div class="well">' +
            '            <div class="row">' +
            '                <div class="col-md-8">' +
            '                    <div class="">' +
            '                        <select class="form-control" id="formYear">' +
            '                        <option>'+moment().format('YYYY')+'</option>' +
            '                        <option>'+moment().add(1, 'years').format('YYYY')+'</option>' +
            '                        <option>'+moment().add(2, 'years').format('YYYY')+'</option>' +
            '                        <option>'+moment().add(3, 'years').format('YYYY')+'</option>' +
            '                        <option>'+moment().add(4, 'years').format('YYYY')+'</option>' +
            '                        </select>' +
            '                    </div>' +
            '                </div>' +
            '                <div class="col-md-4">' +
            '                    <button class="btn btn-block btn-success" id="btnAddPeriod">Add</button>' +
            '                </div>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    ' +
            '    <div class="col-md-12">' +
            '        <table class="table table-bordered">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th></th>' +
            '                <th style="width: 25%;"><i class="fa fa-cog"></i></th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody id="listTR"></tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>';

        $('#loadPage').html(body);

        $('#btnAddPeriod').click(function () {
            var formYear = $('#formYear').val();
            if(formYear!='' && formYear!=null){

                loading_button('#btnAddPeriod');

                var data = {
                    action : 'insertCRMPeriode',
                    Year : formYear
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'rest2/__crudCRMPeriode';

                $.post(url,{token:token},function (jsonResult) {

                    if(jsonResult.Status=='1'){
                        toastr.success('Data saved','Success');
                    } else {
                        toastr.info('Data exist','Info');
                    }

                    $('#btnAddPeriod').html('Add').prop('disabled',false);
                    loadRowPeriod();

                });

            }
        });

        loadCRMPeriode();

    });

    function loadCRMPeriode() {
        var data = {
            action : 'readCRMPeriode'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'rest2/__crudCRMPeriode';

        $.post(url,{token:token},function (jsonResult) {
            var tr = '<td colspan="3">Data not yet</td>';
            if(jsonResult.length>0){
                tr = '';
                $.each(jsonResult,function (i,v) {

                    var sts = (v.Status=='1' || v.Status==1)
                        ? '<span class="label label-success">Publish</span>'
                        : '<span class="label label-danger">Unpublish</span>';

                    var btn = (v.Status=='1' || v.Status==1) ? ''
                        : '<button class="btn btn-default btn-sm btnPublish" data-year="'+v.Year+'" data-id="'+v.ID+'">Publish</button> ' +
                        '<button class="btn btn-sm btn-danger btn-sm btnRemove" data-year="'+v.Year+'" data-id="'+v.ID+'"><i class="fa fa-trash"></i></button>' ;

                    tr = tr+'<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td><b>'+v.Year+'</b><div style="float: right;">'+sts+'</div></td>' +
                        '<td style="text-align: right;">'+btn+'</td>' +
                        '</tr>';
                });
            }

            $('#listTR').append(tr);
        });
    }

    function loadRowPeriod() {

        var data = {
            action : 'readCRMPeriode'
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'rest2/__crudCRMPeriode';

        $.post(url,{token:token},function (jsonResult2) {

            $('#listTR').empty();
            var tr = '';
            $.each(jsonResult2,function (i,v) {
                var sts = (v.Status=='1' || v.Status==1)
                    ? '<span class="label label-success">Publish</span>'
                    : '<span class="label label-danger">Unpublish</span>';

                var btn = (v.Status=='1' || v.Status==1) ? ''
                    : '<button class="btn btn-default btn-sm btnPublish" data-id="'+v.ID+'">Publish</button> ' +
                    '<button class="btn btn-sm btn-danger btn-sm btnRemove" data-id="'+v.ID+'"><i class="fa fa-trash"></i></button>' ;

                tr = tr+'<tr>' +
                    '<td>'+(i+1)+'</td>' +
                    '<td><b>'+v.Year+'</b><div style="float: right;">'+sts+'</div></td>' +
                    '<td style="text-align: right;">'+btn+'</td>' +
                    '</tr>';
            });
            $('#listTR').html(tr);

        });
    }

    $(document).on('click','.btnPublish',function () {
        var ID = $(this).attr('data-id');
        var year = $(this).attr('data-year');
        var data = {
            action : 'publishCRMPeriode',
            ID : ID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'rest2/__crudCRMPeriode';

        $.post(url,{token:token},function (result) {
            toastr.success('Published','Succedd');
            updateTA(year);
            loadRowPeriod();
        });

    });

    $(document).on('click','.btnRemove',function () {

        if(confirm('Are you sure?')){

            $('.btnRemove').prop('disabled',true);

            var ID = $(this).attr('data-id');
            var data = {
                action : 'removeCRMPeriode',
                ID : ID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'rest2/__crudCRMPeriode';

            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.Status=='0'){
                    toastr.warning('Cannot be deleted','Warning');
                } else {
                    toastr.success('Removed','Succedd');
                    loadRowPeriod();
                }

            });
        }


    });


    function updateTA(ta) {
        var url = base_url_js+'admission/submit_set_tahun_ajaran';
        var data = {
            Ta : parseInt(ta)
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{token:token},function (data_json) {
            // $('#generateToBEMhs').prop('disabled',false).html('Generate');
            $('#NotificationModal').modal('hide');
            toastr.success('Data Tersimpan','Success!');
        }).done(function() {

        }).fail(function() {
            toastr.error('The Database connection error, please try again', 'Failed!!');
        }).always(function() {
            // $('#generateToBEMhs').prop('disabled',false).html('Generate');

        });
    }



</script>