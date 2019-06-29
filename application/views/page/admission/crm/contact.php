
<style>

    .detailContact h4 {
        margin-top: 5px;
    }
    .detailContact ul {
        padding: 0px;
        list-style-type: none;
    }
    .detailContact ul .fa {
        margin-right: 5px;
    }
    #divListContact {
        overflow: auto;
        max-height: 400px;
    }
    .footerContact {
        text-align: right;
    }
</style>

<div class="row">

    <div class="col-md-3" style="border-right: 1px solid #CCCCCC;">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create / Update Contact</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>District (Required)</label>
                    <select class="select2-select-00 full-width-fix" size="5" id="filterDistrict">
                        <option value=""></option>
                    </select>
                </div>
                <div id="viewSchoolName"></div>

                <div class="form-group">
                    <label>Name (Required)</label>
                    <input class="hide" id="formID">
                    <input class="form-control" id="formName">
                </div>

                <div class="form-group">
                    <label>Phone (Required)</label>
                    <input class="form-control" id="formPhone">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" id="formEmail">
                </div>

            </div>

            <div class="panel-footer">
                <div style="text-align: right;">
                    <button class="btn btn-success" id="btnSaveContact">Save</button>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-6" style="border-right: 1px solid #CCCCCC;">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">School List</h4>
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="well">
                            <select class="select2-select-00 full-width-fix" size="5" id="filterDistrict2">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div id="viewTableSchool"></div>
                    </div>
                </div>

            </div>
        </div>


    </div>

    <div class="col-md-3">
        <div class="" style="min-height: 100px;padding: 10px;">
            <h3 style="margin-top: 0px;">Contact List</h3>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." id="filterSerchContact" aria-describedby="basic-addon1">
                </div>
            </div>


            <textarea id="viewListContact" class="hide"></textarea>

            <div id="divListContact">
                <table class="table">
                    <tbody id="listContactView"></tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<script>

    $(document).ready(function () {

        loading_modal_show();

        loadSelectOptionDistrict_select2('#filterDistrict');
        loadSelectOptionDistrict_select2('#filterDistrict2');
        searchContact();
        $('#filterDistrict,#filterDistrict2').select2({allowClear: true});

        setTimeout(function () {
            loading_modal_hide();
        },1000);
    });

    $('#filterDistrict').change(function () {

        getSchoolList();


    });

    $('#btnSaveContact').click(function () {

        var formID = $('#formID').val();

        var filterDistrict = $('#filterDistrict').val();

        var filterSchool = $('#filterSchool').val();
        var formName = $('#formName').val();
        var formPhone = $('#formPhone').val();
        var formEmail = $('#formEmail').val();

        if(filterDistrict!='' && filterDistrict!=null &&
            filterSchool!='' && filterSchool!=null &&
            formName!='' && formName!=null &&
        formPhone!='' && formPhone!=null){

            loading_button('#btnSaveContact');


            var data = {
              action : (formID!='' && formID!=null) ? 'updateContat' :  'insertContat',
                ID : formID,
                dataForm : {
                    SchoolID : filterSchool,
                    Name : formName,
                    Phone : formPhone,
                    Email : formEmail,
                    CreatedBy : sessionNIP
                }
            };

            var token = jwt_encode(data,'UAP)(*');

            var url = base_url_js+'rest2/__crudContact';

            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.Status=='1' || jsonResult.Status==1){

                    // Rest form
                    $('#formID,#formName,#formPhone,#formEmail').val('');

                    searchContact();
                    getSchoolList2();
                    toastr.success('Data saved','Success');
                } else {
                    toastr.error('Data not yet saved','Error');
                }

                $('#btnSaveContact').html('Save').prop('disabled',false);
            });

        } else {
            toastr.warning('Please, Fill in the required form','Warning');
        }


    });

    $('#formPhone').blur(function () {

        var formPhone = $('#formPhone').val();
        if(formPhone!='' && formPhone!=null){
            // var formPhone = parseInt(formPhone);
            var d = formPhone.substr(0,2);
            var v = formPhone;
            if(d==62 || d=='62'){
                v = formPhone;
            } else {
               v  = '62'+parseInt(formPhone);
            }

            $('#formPhone').val(v)
        }

    });


    $('#filterDistrict2').change(function () {
        getSchoolList2();
    });

    function getSchoolList(SchoolID='') {
        var filterDistrict = $('#filterDistrict').val();
        if(filterDistrict!='' && filterDistrict!=null){
            $('#viewSchoolName').html('');
            $('#viewSchoolName').html('<div class="form-group"><label>School Name (Required)</label>' +
                '                           <select class="select2-select-00 full-width-fix" size="5" id="filterSchool">' +
                '                            </select></div>');


            var filterDistrict = $('#filterDistrict').val();
            // console.log(filterDistrict);
            $('#filterSchool').empty();
            loadSelectOptionScheoolBy(filterDistrict,'#filterSchool',SchoolID);
            $('#filterSchool').select2({allowClear: true});

            if(SchoolID!=''){
                $('#filterSchool').select2('val',SchoolID);
            }
        }
    }

    function getSchoolList2() {
        var filterDistrict2 = $('#filterDistrict2').val();
        if(filterDistrict2!='' && filterDistrict2!=null){
            var url = base_url_js+'api/__getSchoolByCityID/'+filterDistrict2;
            $.getJSON(url,function (jsonResult) {

                if(jsonResult.length>0){

                    $('#viewTableSchool').html('<table class="table table-bordered table-striped" id="tableListSchool">' +
                        '                            <thead>' +
                        '                            <tr>' +
                        '                                <th style="width: 1%;">No</th>' +
                        '                                <th style="width: 35%;">School</th>' +
                        '                                <th>Alamat</th>' +
                        '                            </tr>' +
                        '                            </thead>' +
                        '                           <tbody id="listSchool"></tbody>' +
                        '                        </table>');

                    $.each(jsonResult,function (i,v) {

                        var contact = (v.Contact.length>0) ? '<div style="margin-top: 5px;"><button class="btn btn-xs btn-primary btnShowContact" data-id="'+v.ID+'">'+v.Contact.length+' Contact</button></div>' : '';

                        $('#listSchool').append('<tr>' +
                            '<td style="text-align: center;">'+(i+1)+'</td>' +
                            '<td>'+v.SchoolName+''+contact+'</td>' +
                            '<td>'+v.SchoolAddress+'<br/><a class="btn btn-xs btn-default" href="https://www.google.com/maps/place/'+v.Latitude+','+v.Longitude+'" target="_blank">Show In Google Maps</a></td>' +
                            '</tr>');
                    });

                    $('#tableListSchool').dataTable();
                    $('#tableListSchool_paginate').parent().closest('div').removeClass('col-md-6').addClass('col-md-12');

                } else {
                    $('#viewTableSchool').html('<h4>School not found</h4>');
                }

            });
        }
    }

    $('#filterSerchContact').keyup(function () {
        searchContact();
    });

    $(document).on('click','.btnShowContact',function () {
        var ID = $(this).attr('data-id');
        $('#filterSerchContact').val('schid:'+ID);
        searchContact();
    });

    function searchContact() {
        var filterSerchContact = $('#filterSerchContact').val();

        var data = {
            action : 'searchContact',
            key : filterSerchContact
        };

        var token = jwt_encode(data,'UAP)(*');

        var url = base_url_js+'rest2/__crudContact';

        $.post(url,{token:token},function (jsonResult) {

            $('#listContactView').empty();

            if(jsonResult.length>0){
                $('#viewListContact').val(JSON.stringify(jsonResult));
                $.each(jsonResult,function (i,v) {
                    var email = (v.Email!='' && v.Email!=null)
                        ? '<li><i class="fa fa-envelope"></i> '+v.Email+'</li>' : '';
                    $('#listContactView').append('<tr><td>' +
                        '                            <h4>'+v.Name+'</h4>' +
                        '                            <div class="detailContact">' +
                        '                                <ul>' +
                        '                                    <li><i class="fa fa-building"></i> '+v.SchoolName+'</li>' +
                        '                                    <li><i class="fa fa-map-marker"></i> '+v.CityName+'</li>' +
                        '                                    <li><i class="fa fa-phone"></i> '+v.Phone+'</li>' +
                        '                                    '+email+
                        '                                </ul>' +
                        '                                <div class="footerContact">' +
                        '<p class="help-block" style="font-size: 10px;">Updated on : '+moment(v.CreatedAt).format('DD MMM YYYY')+' By '+v.CreatedBy_Name+'</p>' +
                        '                                    <button class="btn btn-xs btn-default btnContactEdit" data-id="'+v.ID+'"><i class="fa fa-edit"></i></button>' +
                        '                                    <button class="btn btn-xs btn-danger btnContactRemove" data-id="'+v.ID+'"><i class="fa fa-trash"></i></button>' +
                        '                                </div>' +
                        '                            </div>' +
                        '                        </td></tr>');
                })
            } else {
                $('#listContactView').html('<tr><td>Contact not found</td></tr>');
            }

        });
    }



    $(document).on('click','.btnContactRemove',function () {
       var ID = $(this).attr('data-id');

       if(confirm('Are you sure?')){

           var data = {
               action : 'removeContact',
               ID : ID
           };

           var token = jwt_encode(data,'UAP)(*');
           var url = base_url_js+'rest2/__crudContact';

           $.post(url,{token:token},function (result) {
                toastr.success('Data removed','Success');
               searchContact();
           })


       }

    });

    $(document).on('click','.btnContactEdit',function () {


        var viewListContact = $('#viewListContact').val();
        var dataContact = JSON.parse(viewListContact);
        var ID = $(this).attr('data-id');

        var result = $.grep(dataContact, function(e){ return e.ID == ID; });

        var d = result[0];

        // $('#filterDistrict').prop('disabled',true);

        $('#filterDistrict').select2('val',d.CityID);

        getSchoolList(d.SchoolID);

        $('#formID').val(d.ID);
        $('#formName').val(d.Name);
        $('#formPhone').val(d.Phone);
        $('#formEmail').val(d.Email);

    });

</script>