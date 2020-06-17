
<style>
    .form-control[readonly] {
        background: #ffffff;
        color: #333333;
    }
    #formDateEmp, #formDateStd {
        max-width: 200px;
    }

    #sortEmp li ,
    #sortStd li {
        padding-top: 5px;
        padding-bottom: 5px;
        border: 1px solid rgb(214, 213, 213);
        padding-left: 10px;
        padding-right: 10px;
        margin-bottom: 5px;
        border-radius: 6px;
        cursor: grab;
    }
</style>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Action : <span id="viewAction">-</span></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Title</label>
                    <input class="form-control" id="formTitle" placeholder="Title">
                    <input class="hide" id="formID">
                    <input class="hide" id="formSummernoteID">
                </div>
                <div class="form-group">
                    <label>Publish to employees at</label>
                    <input type="text" id="formDateEmp" readonly class="form-control form-exam form-datetime">
                    <input id="formInputDateEmp" class="hide" hidden readonly>
                </div>
                <div class="form-group">
                    <label>Publish to students at</label>
                    <input type="text" id="formDateStd" readonly class="form-control form-exam form-datetime">
                    <input id="formInputDateStd" class="hide" hidden readonly>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea id="formDescription"></textarea>
                </div>
            </div>
            <div class="panel-footer">
                <div style="text-align: right;">
                    <button class="btn btn-lg btn-success" id="btnSaveNQueue">Save & Set Queue</button>

<!--                    <button class="btn btn-lg btn-success" id="btnSaveNQueue2">S</button>-->
                </div>
            </div>
        </div>
    </div>
</div>





<script>
    $(document).ready(function () {

        var ID = getUrlParameter('id');
        var UnixMoment = moment().unix();

        if(typeof ID !== 'undefined'){
            loading_modal_show();
            getDataEula(ID);
        } else {
            $('#formSummernoteID').val(UnixMoment);
            $('#viewAction').html('Create');
        }

        $('#formDateEmp').datepicker({
            showOtherMonths:true,
            autoSize: true,
            dateFormat: 'dd MM yy',
            onSelect : function () {
                var data_date = $(this).val().split(' ');
                var momentDate = moment(data_date[2]+'-'+(parseInt(convertDateMMtomm(data_date[1])) + 1)+'-'+data_date[0]);
                $('#formInputDateEmp').val(momentDate.format('YYYY-MM-DD'));
            }
        });

        $('#formDateStd').datepicker({
            showOtherMonths:true,
            autoSize: true,
            dateFormat: 'dd MM yy',
            onSelect : function () {
                var data_date = $(this).val().split(' ');
                var momentDate = moment(data_date[2]+'-'+(parseInt(convertDateMMtomm(data_date[1])) + 1)+'-'+data_date[0]);
                $('#formInputDateStd').val(momentDate.format('YYYY-MM-DD'));
            }
        });

        $('#formDescription').summernote({
            placeholder: 'Text your description',
            height: 400,
            callbacks: {
                onImageUpload: function(image) {
                    var formSummernoteID = $('#formSummernoteID').val();
                    summernote_UploadImage('#formDescription',image[0],formSummernoteID);
                },
                onMediaDelete : function(target) {
                    summernote_DeleteImage(target[0].src);
                }
            }
        });




    });

    function getDataEula(ID){
        var data = {
            action : 'getDataEula',
            ID : ID
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudEula';
        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                $('#viewAction').html('Edit');

                var d = jsonResult[0];


                $('#formTitle').val(d.Title);
                $('#formID').val(d.ID);
                $('#formSummernoteID').val(d.SummernoteID);

                if(d.PublishAtEmp!=null && d.PublishAtEmp!=''){
                    $('#formDateEmp').datepicker('setDate',new Date(d.PublishAtEmp));
                    $('#formInputDateEmp').val(d.PublishAtEmp);
                }

                if(d.PublishAtStd!=null && d.PublishAtStd!=''){
                    $('#formDateStd').datepicker('setDate',new Date(d.PublishAtStd));
                    $('#formInputDateStd').val(d.PublishAtStd);
                }

                $('#formDescription').summernote('code',d.Description);

            } else {
                $('#viewAction').html('Create');
            }

            setTimeout(function () {
                loading_modal_hide();
            },1000);
        });
    }

    $('#btnSaveNQueue').click(function () {

        var formInputDateEmp = $('#formInputDateEmp').val();
        var formInputDateStd = $('#formInputDateStd').val();

        var formTitle = $('#formTitle').val();
        var formDescription = $('#formDescription').val();

        if(formInputDateEmp=='' && formInputDateStd==''){
            toastr.warning('Please, the publication date is set','Warning');
        } else {
            if(formTitle!='' && formTitle!=null &&
                formDescription!='' && formDescription!=null){

                loading_modal_show();

                var formID = $('#formID').val();
                var formSummernoteID = $('#formSummernoteID').val();

                var EmpDate = (formInputDateEmp!='' && formInputDateEmp!=null) ? formInputDateEmp : '';
                var StdDate = (formInputDateStd!='' && formInputDateStd!=null) ? formInputDateStd : '';

                var data = {
                    action : 'updateDataEula',
                    ID : (formID!='' && formID!=null) ? formID : '',
                    Title : formTitle,
                    Description : formDescription,
                    SummernoteID : formSummernoteID,
                    EmpDate : EmpDate,
                    StdDate : StdDate
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api4/__crudEula';

                $.post(url,{token:token},function (jsonResult) {

                    if(formID==''){
                        $('#formTitle').val('');
                        $('#formDateEmp').val('');
                        $('#formInputDateEmp').val('');
                        $('#formDateStd').val('');
                        $('#formInputDateStd').val('');
                        // $('#formDescription').val('');
                        $('#formDescription').summernote('reset');
                    }

                    loadSetQuery(EmpDate,StdDate,jsonResult.ID);
                });

            } else {
                toastr.warning('Title & Description are required','Warning');
            }
        }

    });

    function loadSetQuery(EmpDate,StdDate,EID_new) {
        var data = {
            action : 'viewListQueueEula',
            EmpDate : EmpDate,
            StdDate : StdDate
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudEula';

        $.post(url,{token:token},function (jsonResult) {

            loading_modal_hide();
            $('#GlobalModal .modal-header').html('<h4 class="modal-title">Set the queue</h4>');

            var viewListEmp = 'No data';
            var viewListStd = 'No data';

            if(jsonResult.Emp.length>0){
                var listEmp = '';
                $.each(jsonResult.Emp,function (i,v) {
                    var bg = (EID_new==v.EID) ? 'style="background: #fff3cf;"' : '';
                    listEmp = listEmp + '<li data-id="'+v.ID+'" '+bg+'>'+v.Title+'</li>';
                });
                viewListEmp = '<ol id="sortEmp">'+listEmp+'</ol>';
            }

            if(jsonResult.Std.length>0){
                var listStd = '';
                $.each(jsonResult.Std,function (i,v) {
                    var bg = (EID_new==v.EID) ? 'style="background: #fff3cf;"' : '';
                    listStd = listStd + '<li data-id="'+v.ID+'" '+bg+'>'+v.Title+'</li>';
                });
                viewListStd = '<ol id="sortStd">'+listStd+'</ol>';
            }

            var viewEmpDate = (EmpDate!='') ? moment(EmpDate).format('DD MMM YYYY') : '-';
            var viewStdDate = (StdDate!='') ? moment(StdDate).format('DD MMM YYYY') : '-';

            var htmlss = '<div class="row">' +
                '    <div class="col-md-12" style="margin-bottom: 20px;">' +
                '        <div class="thumbnail" style="padding: 10px;">' +
                '            <h5 style="margin-top: 5px;">Publish to <span style="color: #2196f3;">employees</span> at '+viewEmpDate+'</h5>' +
                '            '+viewListEmp+' <textarea class="hide" id="dataQueueEmp"></textarea> ' +
                '        </div>' +
                '    </div>' +
                '    <div class="col-md-12">' +
                '        <div class="thumbnail" style="padding: 10px;">' +
                '            <h5 style="margin-top: 5px;">Publish to <span style="color: #2196f3;">student</span> at '+viewStdDate+'</h5>' +
                '            '+viewListStd+' <textarea class="hide" id="dataQueueStd"></textarea> ' +
                '        </div>' +
                '    </div>' +
                '</div>';

            $('#GlobalModal .modal-body').html(htmlss);

            if(jsonResult.Emp.length>0){
                $('#sortEmp').sortable({
                    axis: 'y',
                    update: function (event, ui) {
                        var dataUpdate = [];
                        $('#sortEmp li').each(function () {
                            dataUpdate.push($(this).attr('data-id'));
                        });

                        $('#dataQueueEmp').val(JSON.stringify(dataUpdate));

                    }
                });
            }

            if(jsonResult.Std.length>0){
                $('#sortStd').sortable({
                    axis: 'y',
                    update: function (event, ui) {

                        var dataUpdate = [];
                        $('#sortStd li').each(function () {
                            dataUpdate.push($(this).attr('data-id'));
                        });

                        $('#dataQueueStd').val(JSON.stringify(dataUpdate));

                    }
                });
            }



            $('#GlobalModal .modal-footer').html('<button class="btn btn-success" id="btnSaveQueue">Save</button>');

            // $('#GlobalModal').on('shown.bs.modal', function () {
            //     $('#formSimpleSearch').focus();
            // });

            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        });

    }

    $(document).on('click','#btnSaveQueue',function () {

        loading_button('#btnSaveQueue');

        var dataQueueEmp = $('#dataQueueEmp').val();
        var dataQueueStd = $('#dataQueueStd').val();

        var listEmp = (dataQueueEmp!='') ? JSON.parse(dataQueueEmp) : [];
        var listStd = (dataQueueStd!='') ? JSON.parse(dataQueueStd) : [];

        var data = {
            action : 'updateQueueEula',
            listEmp : listEmp,
            listStd : listStd
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudEula';

        $.post(url,{token:token},function (res) {
            toastr.success('Data saved','Success');
            setTimeout(function () {
                window.location.href="";
            },1000);
        });

    });

    function sendSorting(dataPost) {

        var data = {
            action : 'updateQueueEula',
            data : dataPost
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudEula';

        $.post(url,{token:token},function (res) {

        });

    }


</script>