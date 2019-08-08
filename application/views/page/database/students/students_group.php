

<div class="col-md-3" style="border-right: 1px solid #CCCCCC;">
    <div class="well">
        <select class="form-control" id="filterBaseProdi">
            <option value="">-- Select Programme Study --</option>
            <option disabled>--------------</option>
        </select>
    </div>

    <div class="row">
        <div class="col-md-8">
            <input class="hide" id="formID">
            <input class="form-control" id="formCode">
        </div>
        <div class="col-md-4">
            <button class="btn btn-block btn-success" id="btnSaveGS">Save</button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <hr/>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th style="width: 1%;">No</th>
                    <th>Code</th>
                    <th style="width: 1%;"><i class="fa fa-cog"></i></th>
                </tr>
                </thead>
                <tbody id="viewListCode"></tbody>
            </table>
        </div>
    </div>

</div>


<div class="col-md-9">
    <h3 style="text-align: center;">Students Group</h3>
    <hr/>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="well">
                <div class="row">
                    <div class="col-md-8">
                        <select class="form-control" id="l_filterBaseProdi">
                            <option value="">-- Select Programme Study --</option>
                            <option disabled>--------------</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" id="l_filterGroup"></select>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-md-12">
            <div id="viewTableStd"></div>
            <hr/>
            <div id="viewNewTableStd"></div>


        </div>
    </div>

</div>



<script>

    $(document).ready(function () {
        loadSelectOptionBaseProdi('#filterBaseProdi','');
        loadSelectOptionBaseProdi('#l_filterBaseProdi','');
    });

    $('#filterBaseProdi').change(function () {

        loadListCode();


    });

    function loadListCode() {
        var filterBaseProdi = $('#filterBaseProdi').val();

        $('#viewListCode').empty();

        if(filterBaseProdi!='' && filterBaseProdi!=null){

            var data = {
                action : 'view_GS',
                ProdiID : filterBaseProdi
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudGroupStd';

            $.post(url,{token:token},function (jsonResult) {

                console.log(jsonResult);

                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {
                        $('#viewListCode').append('<tr>' +
                            '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                            '<td>'+v.Code+'</td>' +
                            '<td><button class="btn btn-sm btn-default btnEdit" data-id="'+v.ID+'"><i class="fa fa-edit"></i></button><textarea class="hide" id="viewGroup_'+v.ID+'">'+JSON.stringify(v)+'</textarea></td>' +
                            '</tr>');
                    });
                } else {
                    $('#viewListCode').append('<tr><td colspan="3">Data not yet</td></tr>');
                }

            });

        }
    }

    $(document).on('click','.btnEdit',function () {

        var ID = $(this).attr('data-id');
        var viewGroup = $('#viewGroup_'+ID).val();
        var d = JSON.parse(viewGroup);

        $('#formID').val(d.ID);
        $('#formCode').val(d.Code);

    });

    $('#btnSaveGS').click(function () {

        var filterBaseProdi = $('#filterBaseProdi').val();

        var formID = $('#formID').val();
        var formCode = $('#formCode').val();

        if(filterBaseProdi!='' && filterBaseProdi!=null &&
            formCode!='' && formCode!=null){

            loading_buttonSm('#btnSaveGS');

            var ProdiID = filterBaseProdi.split('.')[0];

            var data = {
                action : 'update_GS',
                ID : (formID!='' && formID!=null) ? formID : '',
                dataForm : {
                    ProdiID : ProdiID,
                    Code : formCode
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudGroupStd';

            $.post(url,{token:token},function (result) {

                toastr.success('Data saved','Success');
                loadListCode();
                $('#formID').val('');
                $('#formCode').val('');

                setTimeout(function () {
                    $('#btnSaveGS').html('Save').prop('disabled',false);
                },500);
            });

        }

    });

    // ==============================
    $('#l_filterBaseProdi').change(function () {

        l_loadListCode();


    });

    function l_loadListCode() {
        var filterBaseProdi = $('#l_filterBaseProdi').val();

        $('#l_filterGroup').empty();

        if(filterBaseProdi!='' && filterBaseProdi!=null){

            var data = {
                action : 'view_GS',
                ProdiID : filterBaseProdi
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudGroupStd';

            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {
                        $('#l_filterGroup').append('<option value="'+v.ID+'">'+v.Code+'</option>');
                    });

                    loadStudent();
                }

            });

        }
    }

    $('#l_filterGroup').change(function () {
        loadStudent();
    });

    function loadStudent() {
        var l_filterGroup = $('#l_filterGroup').val();

        if(l_filterGroup!='' && l_filterGroup!=null){

            var data = {
                action : 'viewStudent_GS',
                ProdiGroupID : l_filterGroup
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudGroupStd';

            $('#viewTableStd').html('<table class="table table-striped" id="tableStdGS">' +
                '                    <thead>' +
                '                    <tr>' +
                '                        <th style="width: 5%;">No</th>' +
                '                        <th style="width: 15%;">NPM</th>' +
                '                        <th>Name</th>' +
                '                        <th style="width: 1%;"><i class="fa fa-cog"></i></th>' +
                '                    </tr>' +
                '                    </thead>' +
                '                    <tbody id="swStd"></tbody>' +
                '                </table>');

            $.post(url,{token:token},function (jsonResult) {

                $('#swStd').empty();
                if(jsonResult.length){
                    $.each(jsonResult,function (i,v) {
                        $('#swStd').append('<tr>' +
                            '<td style="border-right: 1px solid #CCCCCC;">'+(i+1)+'</td>' +
                            '<td>'+v.NPM+'</td>' +
                            '<td>'+v.Name+'</td>' +
                            '<td><button data-id="'+v.ID+'" data-npm="'+v.NPM+'" data-pgi="'+v.ProdiGroupID+'" class="btn btn-default btn-sm btnRemoveFromGroup"><i class="fa fa-trash"></i></button></td>' +
                            '</tr>');
                    });
                }


                $('#tableStdGS').dataTable();

            });


            $('#viewNewTableStd').html('<div class="thumbnail" style="padding: 15px;margin-bottom: 30px;">' +
                '                <div class="row">' +
                '                    <div class="col-md-4 col-md-offset-4">' +
                '                        <div class="well">' +
                '                            <div class="row">' +
                '                                <div class="col-md-12">' +
                '                                    <select class="form-control" id="selectKurikulum">' +
                '                                      <option value="">-- Select Year --</option>' +
                '                                   </select>' +
                '                                </div>' +
                '                            </div>' +
                '                        </div>' +
                '                    </div>' +
                '                </div>' +
                '               <div class="row">' +
                '                   <div class="col-md-12" id="viewNewTableStd_2"></div>' +
                '               </div>' +
                '            </div>');
            loadSelectOptionCurriculum('#selectKurikulum','');

            var firsLoad = setInterval(function () {

                var selectKurikulum = $('#selectKurikulum').val();
                if(selectKurikulum!='' && selectKurikulum!=null){
                    loadNewStd();
                    clearInterval(firsLoad);
                }

            },1000);

            setTimeout(function () {
                clearInterval(firsLoad);
            },5000);


            // $('#selectKurikulum').prepend('<option selected value=""></option>');



        }


    }

    $(document).on('change','#selectKurikulum',function () {
        loadNewStd();
    });

    function loadNewStd() {

        $('#viewNewTableStd_2').html('<table class="table table-striped" id="tableStdGS_2">' +
            '                    <thead>' +
            '                    <tr>' +
            '                        <th style="width: 5%;">No</th>' +
            '                        <th style="width: 15%;">NPM</th>' +
            '                        <th>Name</th>' +
            '                        <th style="width: 30%;"><i class="fa fa-cog"></i></th>' +
            '                    </tr>' +
            '                    </thead>' +
            '                    <tbody id="swStd_2"></tbody>' +
            '                </table>' +
            '<textarea class="hide" id="viewArr"></textarea>' +
            '<div style="text-align: right;margin-top: 30px;"><button class="btn btn-lg btn-success" id="btnAddGroupNow">Save</button></div>');


        var selectKurikulum = $('#selectKurikulum').val();
        var l_filterBaseProdi =  $('#l_filterBaseProdi').val();

        if(selectKurikulum!='' && l_filterBaseProdi!=''){

            var ProdiID = l_filterBaseProdi.split('.')[0];

            var Year = selectKurikulum.split('.')[1];
            var data = {
                action : 'viewStudentNew_GS',
                Year : Year,
                ProdiID : ProdiID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudGroupStd';

            $.post(url,{token:token},function (jsonResult) {

                $('#swStd_2').empty();

                var group = $('#l_filterGroup option:selected').text();

                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {
                        $('#swStd_2').append('<tr>' +
                            '<td>'+(i+1)+'</td>' +
                            '<td>'+v.NPM+'</td>' +
                            '<td>'+v.Name+'</td>' +
                            '<td><div class="checkbox">' +
                            '  <label>' +
                            '    <input type="checkbox" class="checkATH" value="'+v.ID+'">' +
                            '    Insert to group : <b>'+group+'</b>'+
                            '  </label>' +
                            '</div></td>' +
                            '</tr>');
                    });
                }


                $('#tableStdGS_2').dataTable();

            });

        }

    }

    $(document).on('change','.checkATH',function () {

        var v = $(this).val();

        var viewArr = $('#viewArr').val();
        viewArr = (viewArr!='') ? JSON.parse(viewArr) : [];

        if($(this).is(':checked')){
            viewArr.push(v);
        } else {
            var index = viewArr.indexOf(v);
            if (index > -1) {
                viewArr.splice(index, 1);
            }
        }
        $('#viewArr').val(JSON.stringify(viewArr));
    });

    $(document).on('click','#btnAddGroupNow',function () {

        var viewArr = $('#viewArr').val();
        viewArr = (viewArr!='') ? JSON.parse(viewArr) : [];

        var l_filterGroup = $('#l_filterGroup').val();

        if(viewArr.length>0 && l_filterGroup!=''){

            loading_modal_show();

            var data = {
                action : 'updateStudent_GS',
                arrID : viewArr,
                ProdiGroupID : l_filterGroup
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudGroupStd';

            $.post(url,{token:token},function (result) {

                toastr.success('Data saved','Success');
                loadStudent();

                setTimeout(function () {
                    loading_modal_hide();
                },500);


            });

        }

    });

    $(document).on('click','.btnRemoveFromGroup',function () {

        if(confirm('Are you sure to remove form this group?')){

            $('.btnRemoveFromGroup').prop('disabled',true);

            var ID = $(this).attr('data-id');
            var NPM = $(this).attr('data-npm');
            var ProdiGroupID = $(this).attr('data-pgi');

            var data = {
                action : 'removeFMGrStudent_GS',
                ID : ID,
                NPM : NPM,
                ProdiGroupID : ProdiGroupID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudGroupStd';

            $.post(url,{token:token},function (result) {
                toastr.success('Data removed','Success');
                loadStudent();
            });

        }

    });
</script>