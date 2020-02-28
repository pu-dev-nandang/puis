<div id="panel-list-table" class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <i class="fa fa-bars"></i>
            <span>List of master company</span>
        </h4>
    </div>
    <div class="panel-body">
        <div class="btn-group" style="margin-bottom:10px">
            <a class="btn btn-success" href="<?=site_url('student-life/master/company/add-company')?>"><i class="fa fa-plus"></i> Add new record</a>
        </div>
        <div id="divTableCompany">
            <table class="table table-striped table-bordered" id="tableCompany">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th width="30%">Company</th>
                        <th width="30%">Industry Type</th>
                        <th>Address</th>
                        <th class="text-center" width="7%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">Empty result</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>



<script>

    $(document).ready(function () {
        //loadDataCompany();
        fetchCompany();

        $("#master-company").on('click','.btn-reload',function() {fetchCompany();});
        $("#master-company").on('click','#tableCompany .btnCompanyEdit',function() {
            var ID = $(this).data('id');
            $(location).attr('href', base_url_js+'student-life/master/company/add-company?id='+ID);
        });

        $("#master-company").on('click','#tableCompany .btnCompanyRemove',function() {

            if(confirm('Are you sure wants to remove this data ?')){
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
                            location.reload();
                        },500);
                    } else {
                        toastr.warning(jsonResult.Msg,'Warning');
                    }

                });
            }
        });
    });

    function fetchCompany(){
        var data = {
            action : 'loadMasterCompany'
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudTracerAlumni';
        var resultOBJ = {};

        $.ajax({
            type : 'POST',
            url : url,
            data : {token:token},
            dataType : 'json',
            async: false,
            error : function(jqXHR){
                alert("Failed fetch object master company");
            },success : function(response){
                resultOBJ = response;
            }
        });
        if(!jQuery.isEmptyObject(resultOBJ)){
            var dataTable = $('#divTableCompany #tableCompany').DataTable( {
                "data" : resultOBJ,
                "columns": [
                    { "data": null },
                    { "data": "CompanyName",
                      "render": function (data, type, row, meta) {
                        return '<p class="com-info">'+row.CompanyName+(!jQuery.isEmptyObject(row.Brand) ? '<br><small>'+row.Brand+'</small>':'') +'</p>';
                      }
                    },
                    { "data": "CompanyIndustryName",
                      "render": function (data, type, row, meta) {
                        var label = "";
                        if(!jQuery.isEmptyObject(row.Industry)){
                            label += row.Industry;
                        }else{
                            label += (!jQuery.isEmptyObject(row.CompanyIndustryName) ? row.CompanyIndustryName : '-');
                        }
                        
                        return label;
                      }
                    },
                    { "data": "Address",
                       "render": function (data, type, row, meta) {
                            var label = '<p class="com-info">'+(!jQuery.isEmptyObject(row.CompanyAddress) ? row.CompanyAddress : '');
                            if(!jQuery.isEmptyObject(row.CompanyDistrictName) && !jQuery.isEmptyObject(row.CompanyRegionName) && !jQuery.isEmptyObject(row.CompanyProvinceName) && !jQuery.isEmptyObject(row.CompanyPostcode)){
                                label += '<br>'+row.CompanyDistrictName+', '+row.CompanyRegionName+', '+row.CompanyProvinceName+' '+row.CompanyPostcode+', ';
                            }
                            if(!jQuery.isEmptyObject(row.CompanyCountryName)){
                                label += row.CompanyCountryName;
                            }
                            if(!jQuery.isEmptyObject(row.Phone)){
                                label += '<br>'+row.Phone;
                            }
                            label += '</p>';
                        return label;
                      }
                    },
                    { "data": "ID",
                      "render": function (data) {
                        var label = '<div class="btn-group"><button title="Edit" class="btn btn-warning btn-sm btnCompanyEdit" type="button" data-id="'+data+'"><i class="fa fa-edit"></i></button><button class="btn btn-sm btn-danger btnCompanyRemove" data-id="'+data+'" type="button" title="Remove"><i class="fa fa-trash"></i></button></div>';
                        return label;
                      }
                    }
                ]
            });

            dataTable.on('order.dt search.dt', function () {
                 dataTable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                     cell.innerHTML = i + 1;
                 });
            }).draw();
        }
    }

    function loadDataCompany() {
        var data = {
            action : 'loadMasterCompany'
        };

        $('#divTableCompany').html('<table class="table table-centre table-striped cell-border" id="tableCompany">' +
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


</script>