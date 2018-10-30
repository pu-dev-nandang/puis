<style>
    .row-sma {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .form-time {
        padding-left: 0px;
        padding-right: 0px;
    }
    .row-sma .fa-plus-circle {
        color: green;
    }
    .row-sma .fa-minus-circle {
        color: red;
    }
    .btn-action {

        text-align: right;
    }

    #tableDetailTahun thead th {
        text-align: center;
    }

    .form-filter {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #ccc;
    }
    .filter-time {
        padding-left: 0px;
    }
</style>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-12 formAddFormKD">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> <?php echo $NameMenu ?></h4>
                <?php if ($approval == 0): ?>
                    <div class="toolbar no-padding">
                        <div class="btn-group">
                          <span data-smt="" class="btn btn-xs btn-add">
                            <i class="icon-plus"></i> Add
                           </span>
                        </div>
                    </div>
                <?php endif ?>
            </div>
            <?php if ($approval == 0): ?>
                <div class="widget-content">
                    <!--  -->
                    <div class="row row-sma">
                        <label class="col-xs-3 control-label">Wilayah</label>
                        <div class="col-xs-9">
                            <div class="row">
                                <div class="col-xs-12">
                                    <select class="select2-select-00 col-md-12 full-width-fix" id="selectWilayah">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
            <?php endif ?>
            <div id="pageSchool">

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    window.approval = "<?php echo $approval ?>";

    $(document).ready(function () {
        loadSelectOptionWilayahURL();
    });

    function loadSelectOptionWilayahURL()
    {
        //var url = "http://jendela.data.kemdikbud.go.id/api/index.php/cwilayah/wilayahKabGet";
        var url = base_url_js+'api/__getWilayahURLJson';
        $.get(url,function (data_json)  {
            for(var i=0;i<data_json.length;i++){
                var selected = (i==0) ? 'selected' : '';
                //$('#selectWilayah').append('<option value="'+data_json['data'][i].kode_wilayah+'" '+selected+'>'+data_json['data'][i].nama+'</option>');
                $('#selectWilayah').append('<option value="'+data_json[i].RegionID+'" '+selected+'>'+data_json[i].RegionName+'</option>');
            }
            $('#selectWilayah').select2({
                allowClear: true
            });
        }).done(function () {
            pageTableSchool();
        });
    }

    $(document).on('change','#selectWilayah',function () {
        pageTableSchool();
    });

    function pageTableSchool()
    {
        var selectWilayah = $('#selectWilayah').find(':selected').val();
        loading_page('#pageSchool');
        var url = base_url_js+'admission/master-sma/table';
        var data = {
            wilayah : selectWilayah
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{token:token},function (page) {
            setTimeout(function () {
                $('#pageSchool').html(page);
            },500);
        });
    }

    $(document).on('click','.btn-add', function () {
        modal_generate('add','Add');
    });

    function modal_generate(action,title,ID='') {
        var url = base_url_js+"admission/master/modalform_sekolah";
        var data = {
            Action : action,
            CDID : ID,
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token }, function (html) {
            $('#GlobalModal .modal-header').html('<h4 class="modal-title">'+title+'</h4>');
            $('#GlobalModal .modal-body').html(html);
            $('#GlobalModal .modal-footer').html(' ');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        })
    }

    $(document).on('click','#ModalbtnSaveForm', function () {
        // $.removeCookie('__tawkuuid', { path: '/' });
        loading_button('#ModalbtnSaveForm');
        var url = base_url_js+'admission/master/modalform_sekolah/save';
        var selectProvinsi = $("#selectProvinsi").val().trim();
        var selectRegion = $("#selectRegion").val().trim();
        var selectDistrict = $("#selectDistrict").val().trim();
        var selectTypeSekolah = $("#selectTypeSekolah").val().trim();
        var nm_sekolah = $("#nm_sekolah").val().trim();
        var alamat = $("#alamat").val().trim();

        var action = $(this).attr('action');
        var id = $("#ModalbtnSaveForm").attr('kodeuniq');
        var data = {
            selectProvinsi : selectProvinsi,
            selectRegion : selectRegion,
            selectDistrict : selectDistrict,
            selectTypeSekolah : selectTypeSekolah,
            nm_sekolah : nm_sekolah,
            alamat : alamat,
            Action : action,
            CDID : id
        };
        var token = jwt_encode(data,"UAP)(*");
        if (validation2(data)) {
            $.post(url,{token:token},function (data_json) {
                // jsonData = data_json;
                // var obj = JSON.parse(data_json);
                // console.log(obj);
                $('#GlobalModal').modal('hide');
            }).done(function() {
                pageTableSchool();
            }).fail(function() {
                toastr.error('The Database connection error, please try again', 'Failed!!');
            }).always(function() {
                $('#ModalbtnSaveForm').prop('disabled',false).html('Save');

            });
        }
        else
        {
            $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
        }

    });

    function validation2(arr)
    {
        var toatString = "";
        var result = "";
        for(var key in arr) {
            switch(key)
            {
                default :
                    if(key != 'CDID')
                    {
                        result = Validation_required(arr[key],key);
                        if (result['status'] == 0) {
                            toatString += result['messages'] + "<br>";
                        }
                    }

            }

        }
        if (toatString != "") {
            // toastr.error(toatString, 'Failed!!');
            $("#msgMENU").html(toatString);
            $("#msgMENU").removeClass("hide");
            return false;
        }

        return true;
    }

    $(document).on('click','.btn-edit', function () {
        var ID = $(this).attr('data-smt');
        modal_generate('edit','Edit',ID);
    });

    $(document).on('click','.btn-delete', function () {
        var ID = $(this).attr('data-smt');
        $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Apakah anda yakin untuk melakukan request ini ?? </b> ' +
            '<button type="button" id="confirmYesDelete" class="btn btn-primary" style="margin-right: 5px;" data-smt = "'+ID+'">Yes</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
            '</div>');
        $('#NotificationModal').modal('show');
    });

    $(document).on('click','#confirmYesDelete',function () {
        $('#NotificationModal .modal-header').addClass('hide');
        $('#NotificationModal .modal-body').html('<center>' +
            '                    <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>' +
            '                    <br/>' +
            '                    Loading Data . . .' +
            '                </center>');
        $('#NotificationModal .modal-footer').addClass('hide');
        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });
        var url = base_url_js+'admission/master/modalform_sekolah/save';
        var aksi = "delete";
        var ID = $(this).attr('data-smt');
        var data = {
            Action : aksi,
            CDID : ID,
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{token:token},function (data_json) {
            setTimeout(function () {
                toastr.options.fadeOut = 10000;
                toastr.success('Data berhasil disimpan', 'Success!');
                pageTableSchool();
                $('#NotificationModal').modal('hide');
            },500);
        });
    });
</script>