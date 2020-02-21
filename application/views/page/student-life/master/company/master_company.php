

<div id="divTableCompany"></div>

<script>

    $(document).ready(function () {
        loadDataCompany();
    })

    function loadDataCompany() {
        var data = {
            action : 'loadMasterCompany'
        };

        $('#divTableCompany').html('<table class="table table-centre table-striped" id="tableCompany">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Company</th>' +
            '                <th style="width: 5%;"><i class="fa fa-cog"></i></th>' +
            '                <th>Address</th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody id="listDataCompany">' +
            '            </tbody>' +
            '        </table>');

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudTracerAlumni';
        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var btnAct = '<div class="btn-group">' +
                        '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        '    <i class="fa fa-edit"></i> <span class="caret"></span>' +
                        '  </button>' +
                        '  <ul class="dropdown-menu">' +
                        '    <li><a href="'+base_url_js+'student-life/master/company/add-company?id='+v.ID+'">Edit</a></li>' +
                        '    <li role="separator" class="divider"></li>' +
                        '    <li><a href="javascript:void(0);" class="btnCompanyRemove" data-id="'+v.ID+'">Remove</a></li>' +
                        '  </ul>' +
                        '</div>';

                    $('#listDataCompany').append('<tr>' +
                        '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;"><b>'+v.Name+'</b>' +
                        '<br/>Industy : '+v.Industry+
                        '<br/>Phone : '+v.Phone+
                        '</td>' +
                        '<td>'+btnAct+'</td>' +
                        '<td style="text-align: left;">'+v.Address+'</td>' +
                        '</tr>');
                });

                $('#tableCompany').dataTable();
            }
        });
    }


    $(document).on('click','.btnCompanyRemove',function () {

        if(confirm('Are you sure ?')){
            var ID = $(this).attr('data-id');
            var data = {
                action : 'removeMasterCompany',
                ID : ID
            };
            var token = jwt_encode(data,'UAP)(*');

            var url = base_url_js+'api3/__crudTracerAlumni';
            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.Status==1 || jsonResult.Status=='1'){
                    toastr.success(jsonResult.Msg,'Success');
                    setTimeout(function (                                                                                                                                            ) {
                        loadDataCompany();
                    },500);
                } else {
                    toastr.warning(jsonResult.Msg,'Warning');
                }

            });
        }


    });
</script>