
<style>
    .form-attd[readonly] {
        cursor: cell;
        background-color: #fff;
        color: #333;
    }
</style>

<div class="row" style="margin-top: 30px;">

    <div class="col-md-12" style="margin-bottom: 15px;">
        <a href="<?php echo base_url('human-resources/academic_employees') ?>" class="btn btn-warning"><i class="fa fa-arrow-circle-left"></i> Back to list Academic Employee</a>
    </div>

    <div class="col-md-12">

        <div class="thumbnail" style="padding: 15px;">
            <div class="row">
                <div class="col-xs-1" style="text-align: right;padding-right: 0px;">
                    <div id="viewPhoto"></div>
                </div>
                <div class="col-xs-11">
                    <h3 style="margin-top: 0px;border-left: 11px solid #2196f3;padding-left: 10px;font-weight: bold;" id="viewName"></h3>
                    <table class="table">
                        <tr>
                            <td style="width: 50%;">
                                <i class="fa fa-envelope margin-right"></i> (Email PU) <a id="viewEmailPU"></a><br/>
                                <i class="fa fa-envelope margin-right"></i> (Email Other) <a id="viewEmailOther"></a><br/>
                                <i class="fa fa-phone margin-right"></i> (Phone) <span id="viewPhone"></span> <br/>
                                <i class="fa fa-phone margin-right"></i> (HP) <span id="viewHP"></span>
                            </td>
                            <td>
                                <i class="fa fa-map-marker margin-right"></i> <span id="viewAddress"></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <hr/>

        <div class="tabbable tabbable-custom tabbable-full-width">
            <ul class="nav nav-tabs">
                <li class="active"><a href="javascript:void(0)" class="menuDetails" data-page="academic_details" data-toggle="tab"><i class="fa fa-user"></i> Personal Information </a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="list_academic" data-toggle="tab"><i class="fa fa-graduation-cap"></i> Detail Academic</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="academic_sratasatu" data-toggle="tab"><i class="fa fa-university"></i>  Academic S1</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="academic_sratadua" data-toggle="tab"><i class="fa fa-university"></i> Academic S2</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="academic_sratatiga" data-toggle="tab"><i class="fa fa-university"></i> Academic S3</a></li>
                <li class=""><a href="javascript:void(0)" class="menuDetails" data-page="otherFiles" data-toggle="tab"><i class="fa fa-files-o"></i> Other Files</a></li>
                
            </ul>
            <div class="tab-content">
                <hr/>
                <div id="divPage"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadDataThumb();
        window.NIP = '<?php echo $NIP; ?>';

        var data = {
            NIP : NIP,
            page : 'academic_details'
        };
        var token = jwt_encode(data,'UAP)(*');

        loadPage(token);


        window.Lecturer_NIP = 0;

        $('input[id$="endTime"]').datetimepicker({
            format: 'HH:mm'
        });

    });

    $(document).on('click','.menuDetails',function () {
        var page = $(this).attr('data-page');
        var data = {
            NIP : NIP,
            page : page
        };
        var token = jwt_encode(data,'UAP)(*');

        loadPage(token);
    });

    $(document).on('click','.btnLecturerAction',function () {
        var page = $(this).attr('data-page');
        var ScheduleID = $(this).attr('data-id');
        var data = {
            NIP : NIP,
            page : page,
            ScheduleID : ScheduleID
        };
        var token = jwt_encode(data,'UAP)(*');
        loadPage(token);

    });

    $(document).on('click','.btnLecturerActionAttd',function () {
        var page = $(this).attr('data-page');
        var ScheduleID = $(this).attr('data-id');
        var data = {
            NIP : NIP,
            page : page,
            ScheduleID : ScheduleID
        };
        var token = jwt_encode(data,'UAP)(*');
        loadPagePresensi(token);

    });


    function loadDataThumb() {
        var url = base_url_js+'api/__crudAcademic';
        var NIP = '<?php echo $NIP; ?>';

        var token = jwt_encode({action:'readMini',NIP:NIP},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {

            $('#viewPhoto').html('<img class="img-rounded" src="'+base_url_img_employee+''+jsonResult.Photo+'" />');


            $('#viewName').html(jsonResult.NIP+' - '+jsonResult.TitleAhead.trim()+' '+jsonResult.Name+' '+jsonResult.TitleBehind.trim()+' ' +
                            '<span style="float:right;"> | '+jsonResult.Division+' <i class="fa fa-angle-right"></i> <b>'+jsonResult.Position+'</b></span>');

            Lecturer_NIP = jsonResult.NIP.trim();

            var emailPU = (jsonResult.EmailPU!=null && jsonResult.EmailPU!='') ? jsonResult.EmailPU : '-';
            $('#viewEmailPU').html(emailPU);

            var emailOther = (jsonResult.Email!=null && jsonResult.Email!='') ? jsonResult.Email : '-';
            $('#viewEmailOther').html(emailOther);

            var Phone = (jsonResult.Phone!=null && jsonResult.Phone!='') ? jsonResult.Phone : '-';
            $('#viewPhone').html(Phone);

            var HP = (jsonResult.HP!=null && jsonResult.HP!='') ? jsonResult.HP : '-';
            $('#viewHP').html(HP);

            $('#viewAddress').html(jsonResult.Address.trim());
        });
    }

    function loadPage(token) {
        var url = base_url_js+'human-resources/loadpageacademicDetails';

        loading_page('#divpage');
        $.post(url,{token:token},function (html) {
            setTimeout(function () {
                $('#divPage').html(html);
            },500)
        });
    }


    function loadPagePresensi(token) {
        var url = base_url_js+'academic/loadPagePresensi';

        loading_page('#divpage');
        $.post(url,{token:token},function (html) {
            setTimeout(function () {
                $('#divPage').html(html);
            },500)
        });
    }
</script>


