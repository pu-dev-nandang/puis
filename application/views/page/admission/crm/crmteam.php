
<div class="row">
    <div class="col-md-4" style="border-right: 1px solid #CCCCCC;">

<!--        <div style="text-align: right;">-->
<!--            <button class="btn btn-primary" id="btnCRMPeriode">CRM Period</button>-->
<!--            <hr/>-->
<!--        </div>-->

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create Team</h4>
            </div>
            <div class="panel-body" style="min-height: 100px;">

                <div class="alert alert-info" role="alert">
                    <b style="text-align: center;">Year - <span id="viewPeriod"></span></b>
                    <input class="hide" id="formID">
                    <input class="hide" id="formPeriodID">
                </div>

                <div class="form-group">
                    <label>Team Name</label>
                    <input class="form-control" id="formName">
                </div>
                <div class="form-group">
                    <label>Coordinator</label>
                    <select class="select2-select-00 full-width-fix" size="5" id="formCoordinator">
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Member</label>
                    <select class="select2-select-00 full-width-fix"
                            size="5" multiple id="formMember"></select>
                </div>
            </div>
            <div class="panel-footer" style="text-align: right;">
                <button class="btn btn-success" id="btnSaveTeam">Save</button>
            </div>
        </div>
    </div>
    <div class="col-md-8">

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="well">
                    <select class="form-control" id="filterPeriod"></select>
                </div>
                <hr/>
            </div>
        </div>

        <div class="thumbnail" style="min-height: 50px;">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th style="width: 1%;">No</th>
                    <th style="width: 15%;">Team</th>
                    <th>Member</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
                </thead>
                <tbody id="listTeam"></tbody>
            </table>

            <textarea class="hide" id="viewListTeamSw"></textarea>

        </div>
    </div>
</div>



<script>
    $(document).ready(function () {

        loading_modal_show();
        loadActivePeriod();
        loadSelectOptionEmployeesSingle('#formCoordinator','');
        loadSelectOptionEmployeesSingle('#formMember','');
        $('#formCoordinator,#formMember').select2({allowClear: true});

        var firsLoad = setInterval(function (args) {
            var filterPeriod = $('#filterPeriod').val();

            if(filterPeriod!='' && filterPeriod!=null){
                loadTableTeam();
                clearInterval(firsLoad);
            }

        },1000);


        setTimeout(function () {
            loading_modal_hide();
        },1000);


    });

    $(document).on('change','#filterPeriod',function () {
        var filterPeriod = $('#filterPeriod').val();
        if(filterPeriod!=''){
            loadTableTeam();
        }

    });

    $('#btnSaveTeam').click(function () {

        var formID = $('#formID').val();
        var formPeriodID = $('#formPeriodID').val();
        var formName = $('#formName').val();
        var formCoordinator = $('#formCoordinator').val();
        var formMember = $('#formMember').val();

        if(formName!='' && formName!=null &&
            formCoordinator!='' && formCoordinator!=null &&
            formMember!='' && formMember!=null){

            loading_button('#btnSaveTeam');

            loading_modal_show();

            var data = {
                action : (formID!='' && formID!=null) ? 'updateCRMTeam' : 'insertCRMTeam',
                ID : formID,
                team : {
                    PeriodID : formPeriodID,
                    Name : formName,
                    Coordinator : formCoordinator,
                    CreatedBy : sessionNIP
                },
                member : formMember
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'rest2/__crudCRMTeam';

            $.post(url,{token:token},function (result) {
                loadTableTeam();
                $('#btnSaveTeam').html('Save').prop('disabled',false);
                toastr.success('Data saved','Success');
                loading_modal_hide();

                $('#formName,#formID').val('');
                $('#formCoordinator').select2("val","");
                $('#formMember').val(null).trigger('change');
            })


        } else {
            toastr.warning('All form is required','Warning');
        }

    });

    function loadActivePeriod() {
        var data = {
            action : 'activeCRMPeriode'
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'rest2/__crudCRMPeriode';

        $.post(url,{token:token},function (jsonResult) {
            var y = jsonResult[0].Year;
            var i = jsonResult[0].ID;
            $('#viewPeriod').html(y);
            $('#formPeriodID').val(i);

            loadSelectOptionCRMPeriod('#filterPeriod',i);

        });

    }

    function loadTableTeam() {
        var filterPeriod = $('#filterPeriod').val();

        if(filterPeriod!='' && filterPeriod!=null){
            var data = {
                action : 'readCRMTeam',
                PeriodID : filterPeriod
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'rest2/__crudCRMTeam';

            $.post(url,{token:token},function (jsonResult) {
                $('#listTeam').empty();
                $('#viewListTeamSw').val(JSON.stringify(jsonResult));
                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {

                        var Member = v.Member;
                        var listMember = '';
                        if(Member.length>0){
                            $.each(Member,function (i2,v2) {
                                var koma = (i2!=0) ? ', ' : '';
                                listMember = listMember+''+koma+' '+v2.MemberName;
                            })

                        }

                        var btnActionTeam = (v.Status=='1' || v.Status==1)
                            ? '<button class="btn btn-sm btn-default btnEditTeam" data-id="'+v.ID+'"><i class="fa fa-edit"></i></button> ' +
                            '<button class="btn btn-sm btn-danger btnRemoveTeam" data-id="'+v.ID+'"><i class="fa fa-trash"></i></button>'
                            : '-' ;

                        $('#listTeam').append('<tr>' +
                            '<td>'+(i+1)+'</td>' +
                            '<td>'+v.Name+'</td>' +
                            '<td><b>(Co) '+v.CoordinatorName+'</b><div>'+listMember+'</div></td>' +
                            '<td>'+btnActionTeam+'</td>' +
                            '</tr>');
                    });
                } else {
                    $('#listTeam').append('<tr><td colspan="4">Data not yet</td></tr>');
                }
            });
        }
    }

    $(document).on('click','.btnRemoveTeam',function () {

        if(confirm('Are you sure?')){
            var ID = $(this).attr('data-id');

            var data = {
                action : 'removeCRMTeam',
                ID : ID
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'rest2/__crudCRMTeam';

            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.Status==1 || jsonResult.Status=='1'){
                    toastr.success('Team removec','Success');
                } else {
                    toastr.warning('Team can not removed','Warning');
                }

            })
        }

    });

    $(document).on('click','.btnEditTeam',function () {

        var viewListTeamSw = $('#viewListTeamSw').val();

        var viewListTeamSw = JSON.parse(viewListTeamSw);
        var ID = $(this).attr('data-id');

        var result = $.grep(viewListTeamSw, function(e){ return e.ID == ID; });

        var d = result[0];

        $('#viewPeriod').html(d.Year);

        $('#formID').val(d.ID);
        $('#formPeriodID').val(d.PeriodID);
        $('#formName').val(d.Name);

        $('#formCoordinator').select2('val',d.Coordinator);

        var member = [];
        if(d.Member.length>0){
            $.each(d.Member,function (i,v) {
                member.push(v.NIP);
            });
        }

        $('#formMember').select2('val',member);

    });

    // == CRUD PERIOD ===

    // $('#btnCRMPeriode').click(function () {
    //
    //     var data = {
    //         action : 'readCRMPeriode'
    //     };
    //
    //     var token = jwt_encode(data,'UAP)(*');
    //     var url = base_url_js+'rest2/__crudCRMPeriode';
    //
    //     $.post(url,{token:token},function (jsonResult) {
    //
    //         $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
    //             '        <h4 class="modal-title">CRM Period</h4>');
    //
    //         var tr = '<td colspan="3">Data not yet</td>';
    //         if(jsonResult.length>0){
    //             tr = '';
    //             $.each(jsonResult,function (i,v) {
    //
    //                 var sts = (v.Status=='1' || v.Status==1)
    //                     ? '<span class="label label-success">Publish</span>'
    //                     : '<span class="label label-danger">Unpublish</span>';
    //
    //                 var btn = (v.Status=='1' || v.Status==1) ? ''
    //                     : '<button class="btn btn-default btn-sm btnPublish" data-id="'+v.ID+'">Publish</button> ' +
    //                     '<button class="btn btn-sm btn-danger btn-sm btnRemove" data-id="'+v.ID+'"><i class="fa fa-trash"></i></button>' ;
    //
    //                 tr = tr+'<tr>' +
    //                     '<td>'+(i+1)+'</td>' +
    //                     '<td><b>'+v.Year+'</b><div style="float: right;">'+sts+'</div></td>' +
    //                     '<td style="text-align: right;">'+btn+'</td>' +
    //                     '</tr>';
    //             });
    //         }
    //
    //         var body = '<div class="row">' +
    //             '    <div class="col-md-12">' +
    //             '        <div class="well">' +
    //             '            <div class="row">' +
    //             '                <div class="col-md-8">' +
    //             '                    <div class="">' +
    //             '                        <select class="form-control" id="formYear">' +
    //             '                        <option>'+moment().format('YYYY')+'</option>' +
    //             '                        <option>'+moment().add(1, 'years').format('YYYY')+'</option>' +
    //             '                        <option>'+moment().add(2, 'years').format('YYYY')+'</option>' +
    //             '                        <option>'+moment().add(3, 'years').format('YYYY')+'</option>' +
    //             '                        <option>'+moment().add(4, 'years').format('YYYY')+'</option>' +
    //             '                        </select>' +
    //             '                    </div>' +
    //             '                </div>' +
    //             '                <div class="col-md-4">' +
    //             '                    <button class="btn btn-block btn-success" id="btnAddPeriod">Add</button>' +
    //             '                </div>' +
    //             '            </div>' +
    //             '        </div>' +
    //             '    </div>' +
    //             '    ' +
    //             '    <div class="col-md-12">' +
    //             '        <table class="table table-bordered">' +
    //             '            <thead>' +
    //             '            <tr>' +
    //             '                <th style="width: 1%;">No</th>' +
    //             '                <th></th>' +
    //             '                <th style="width: 25%;"><i class="fa fa-cog"></i></th>' +
    //             '            </tr>' +
    //             '            </thead>' +
    //             '            <tbody id="listTR">'+tr+'</tbody>' +
    //             '        </table>' +
    //             '    </div>' +
    //             '</div>';
    //
    //         $('#GlobalModal .modal-body').html(body);
    //         $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
    //         $('#GlobalModal').modal({
    //             'backdrop' : 'static',
    //             'show' : true
    //         });
    //
    //         $('#btnAddPeriod').click(function () {
    //             var formYear = $('#formYear').val();
    //             if(formYear!='' && formYear!=null){
    //
    //                 loading_button('#btnAddPeriod');
    //
    //                 var data = {
    //                     action : 'insertCRMPeriode',
    //                     Year : formYear
    //                 };
    //
    //                 var token = jwt_encode(data,'UAP)(*');
    //                 var url = base_url_js+'rest2/__crudCRMPeriode';
    //
    //                 $.post(url,{token:token},function (jsonResult) {
    //
    //                     if(jsonResult.Status=='1'){
    //                         toastr.success('Data saved','Success');
    //                     } else {
    //                         toastr.info('Data exist','Info');
    //                     }
    //
    //                     $('#btnAddPeriod').html('Add').prop('disabled',false);
    //                     loadRowPeriod();
    //
    //                 });
    //
    //             }
    //         });
    //
    //     });
    //
    //
    //
    // });
    //
    // function loadRowPeriod() {
    //
    //     var data = {
    //         action : 'readCRMPeriode'
    //     };
    //     var token = jwt_encode(data,'UAP)(*');
    //     var url = base_url_js+'rest2/__crudCRMPeriode';
    //
    //     $.post(url,{token:token},function (jsonResult2) {
    //
    //         $('#listTR').empty();
    //         var tr = '';
    //         $.each(jsonResult2,function (i,v) {
    //             var sts = (v.Status=='1' || v.Status==1)
    //                 ? '<span class="label label-success">Publish</span>'
    //                 : '<span class="label label-danger">Unpublish</span>';
    //
    //             var btn = (v.Status=='1' || v.Status==1) ? ''
    //                 : '<button class="btn btn-default btn-sm btnPublish" data-id="'+v.ID+'">Publish</button> ' +
    //                 '<button class="btn btn-sm btn-danger btn-sm btnRemove" data-id="'+v.ID+'"><i class="fa fa-trash"></i></button>' ;
    //
    //             tr = tr+'<tr>' +
    //                 '<td>'+(i+1)+'</td>' +
    //                 '<td><b>'+v.Year+'</b><div style="float: right;">'+sts+'</div></td>' +
    //                 '<td style="text-align: right;">'+btn+'</td>' +
    //                 '</tr>';
    //         });
    //         $('#listTR').html(tr);
    //
    //     });
    // }
    //
    // $(document).on('click','.btnPublish',function () {
    //    var ID = $(this).attr('data-id');
    //     var data = {
    //         action : 'publishCRMPeriode',
    //         ID : ID
    //     };
    //
    //     var token = jwt_encode(data,'UAP)(*');
    //     var url = base_url_js+'rest2/__crudCRMPeriode';
    //
    //     $.post(url,{token:token},function (result) {
    //         toastr.success('Published','Succedd');
    //         loadActivePeriod();
    //         loadRowPeriod();
    //     });
    //
    // });
    //
    // $(document).on('click','.btnRemove',function () {
    //
    //     if(confirm('Are you sure?')){
    //
    //         $('.btnRemove').prop('disabled',true);
    //
    //         var ID = $(this).attr('data-id');
    //         var data = {
    //             action : 'removeCRMPeriode',
    //             ID : ID
    //         };
    //
    //         var token = jwt_encode(data,'UAP)(*');
    //         var url = base_url_js+'rest2/__crudCRMPeriode';
    //
    //         $.post(url,{token:token},function (jsonResult) {
    //
    //             if(jsonResult.Status=='0'){
    //                 toastr.warning('Cannot be deleted','Warning');
    //             } else {
    //                 toastr.success('Removed','Succedd');
    //                 loadRowPeriod();
    //             }
    //
    //         });
    //     }
    //
    //
    // })
</script>