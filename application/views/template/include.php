<!--=== CSS ===-->

<!-- Bootstrap -->
<link href="<?php echo base_url('assets/template/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/custom/custom.css'); ?>" rel="stylesheet" type="text/css" />

<!-- jQuery UI -->
<!--<link href="plugins/jquery-ui/jquery-ui-1.10.2.custom.css" rel="stylesheet" type="text/css" />-->
<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/>
<![endif]-->

<!-- Theme -->
<link href="<?php echo base_url('assets/template/css/main.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/template/css/plugins.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/template/css/responsive.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/template/css/icons.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/template/plugins/animate/animate.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/template/plugins/toastr/toastr.min.css'); ?>" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="<?php  echo base_url('assets/template/css/fontawesome/font-awesome.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/template/css/fontawesome4/css/font-awesome.min.css'); ?>">
<!--<link rel="stylesheet" href="--><?php //echo base_url('assets/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css'); ?><!--">-->

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/bootstrap-toggle/css/bootstrap-toggle.min.css'); ?>">
<!--[if IE 7]>
<link rel="stylesheet" href="assets/css/fontawesome/font-awesome-ie7.min.css">
<![endif]-->

<!--[if IE 8]>
<link href="assets/css/ie8.css" rel="stylesheet" type="text/css" />
<![endif]-->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>

<link rel="stylesheet" href="<?php echo base_url('assets/datepicter/css/bootstrap-datetimepicker.min.css'); ?>">

<style media="screen">

    .project-switcher {
        background-color : #0f1f4b;
    }
    .dropdown-menu li a:hover {
        background: #0f1f4b;
    }

    #ui-datepicker-div {
        z-index : 1041 !important;
    }

    #sidebar ul#nav ul.sub-menu li.current a {
        color: #ffffff;
        background: #083f8882;
    }

    #sidebar ul#nav ul.sub-menu li.current a i {
        color: #ffffff;
    }

    #sidebar ul#nav > li.current {
        background: #083f8894;
    }

    #sidebar ul#nav > li.current > a {
        border-right: 10px solid #b30011;
    }

    #sidebar ul#nav > li.current > a , #sidebar ul#nav > li.current > a > .fa {
        color: #ffffff;
        text-shadow : none;
    }

    #sidebar ul#nav li a:hover {
        background: #083f8814;
    }

    .theme-dark #content {
        background-color : #ffffff;
    }

    /*.dropdown-menu {*/
        /*min-width: 100%;*/
    /*}*/

    .left-margin {
        margin-left: 5px;
    }

    .right-margin,.margin-right {
        margin-right: 5px;
    }

    .td-center, .th-center , .tr-center {
        text-align: center;
    }
    .head-center th {
        text-align: center;
    }


    /* Untuk datatable */
    .filter-prodi {
        width: 250px;
        float: right;
        margin-right: 10px;
    }

    h3.heading-small {
        border-left: 15px solid #ff9800;
        padding-left: 10px;
        margin-bottom: 15px;
        font-weight: bold;
    }


    /* Add by Adhi 20180702 */
    .btn-convert { background-color: hsl(145, 62%, 68%) !important; background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#cdf3dd", endColorstr="#7adfa4"); background-image: -khtml-gradient(linear, left top, left bottom, from(#cdf3dd), to(#7adfa4)); background-image: -moz-linear-gradient(top, #cdf3dd, #7adfa4); background-image: -ms-linear-gradient(top, #cdf3dd, #7adfa4); background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #cdf3dd), color-stop(100%, #7adfa4)); background-image: -webkit-linear-gradient(top, #cdf3dd, #7adfa4); background-image: -o-linear-gradient(top, #cdf3dd, #7adfa4); background-image: linear-gradient(#cdf3dd, #7adfa4); border-color: #7adfa4 #7adfa4 hsl(145, 62%, 63%); color: #333 !important; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.33); -webkit-font-smoothing: antialiased; }


    .ui-autocomplete {
        display: block;
        position: absolute;
        z-index: 1000;
        cursor: default;
        padding: 0;
        margin-top: 2px;
        list-style: none;
        background-color: #ffffff;
        border: 1px solid #ccc;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);

        width: auto !important;
        min-width: 115px !important;
    }
    .ui-autocomplete > li {
        padding: 3px 10px;
    }
    .ui-autocomplete > li.ui-menu-item a {
        /*font-weight: bold;*/
        color:#333;
        text-decoration: none;
    }

    .ui-autocomplete > li.ui-menu-item:hover {
        background: #083f88;
        color: #FFFFFF;
    }
    .ui-autocomplete > li.ui-menu-item:hover a {
        color: #FFFFFF;
    }



    .ui-helper-hidden-accessible {
        display: block;
    }

    .daterangepicker .ranges {
        width: 250px;
    }
    .daterangepicker .ranges .input-mini {
        width : 110px !important;
    }
</style>

<!--=== JavaScript ===-->

<script type="text/javascript" src="<?php echo base_url('assets/template/js/libs/jquery-1.10.2.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('assets/template/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/js/libs/lodash.compat.min.js'); ?>"></script>

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="assets/js/libs/html5shiv.js"></script>
<![endif]-->

<!-- Smartphone Touch Events -->
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/event.swipe/jquery.event.move.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/event.swipe/jquery.event.swipe.js"></script>

<!-- General -->
<script type="text/javascript" src="<?php echo base_url('assets/template/');?>js/libs/breakpoints.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/respond/respond.min.js"></script> <!-- Polyfill for min/max-width CSS3 Media Queries (only for IE8) -->
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/cookie/jquery.cookie.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/toastr/toastr.min.js"></script>

<!-- Page specific plugins -->
<!-- Charts -->
<!--[if lt IE 9]>
<script type="text/javascript" src="plugins/flot/excanvas.min.js"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/flot/jquery.flot.tooltip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/flot/jquery.flot.time.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/flot/jquery.flot.growraf.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/easy-pie-chart/jquery.easy-pie-chart.min.js"></script> -->

<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/moment-range/'); ?>moment-range.js"></script>
<!--<script type="text/javascript" src="--><?php //echo base_url('assets/template/'); ?><!--plugins/daterangepicker/moment_id.js"></script>-->
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/blockui/jquery.blockUI.min.js"></script>

<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/fullcalendar/fullcalendar.min.js"></script>

<!-- Noty -->
<!--<script type="text/javascript" src="--><?php //echo base_url('assets/template/'); ?><!--plugins/noty/jquery.noty.js"></script>-->
<!--<script type="text/javascript" src="--><?php //echo base_url('assets/template/'); ?><!--plugins/noty/layouts/top.js"></script>-->
<!--<script type="text/javascript" src="--><?php //echo base_url('assets/template/'); ?><!--plugins/noty/themes/default.js"></script>-->

<!-- DataTables -->
<!-- DataTables -->

<!--<script type="text/javascript" src="--><?php //echo base_url('assets/template/'); ?><!--plugins/datatables/jquery.dataTables.min.js"></script>-->
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/datatables/tes/jquery.dataTables.js"></script>
<!--<script type="text/javascript" src="--><?php //echo base_url('assets/template/'); ?><!--plugins/datatables/tes/dataTables.bootstrap.min.js"></script>-->
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/datatables/tabletools/TableTools.min.js"></script> <!-- optional -->
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/datatables/colvis/ColVis.min.js"></script> <!-- optional -->
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/datatables/DT_bootstrap.js"></script>
<!--<script type="text/javascript" src="--><?php //echo base_url('assets/template/'); ?><!--plugins/datatables/dataTables.rowReorder.js"></script>-->

<!-- Forms -->
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/uniform/jquery.uniform.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/select2/select2.min.js"></script>

<!-- Pickers -->
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/pickadate/picker.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/pickadate/picker.date.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/pickadate/picker.time.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>

<!-- App -->
<script type="text/javascript" src="<?php echo base_url('assets/template/');?>js/app.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/');?>js/plugins.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/');?>js/plugins.form-components.js"></script>

<!-- Dual Box -->
<script type="text/javascript" src="<?php echo base_url('assets/template/');?>plugins/duallistbox/jquery.duallistbox.min.js"></script>


<!-- Form Validation -->
<script type="text/javascript" src="<?php echo base_url('assets/template/');?>plugins/validation/jquery.validate.min.js"></script>

<!-- IMG Fitter -->
<script type="text/javascript" src="<?php echo base_url('assets/');?>plugins/img-fitter/jquery.imgFitter.js"></script>



<!-- Demo JS -->
<script type="text/javascript" src="<?php echo base_url('assets/template/');?>js/custom.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/');?>js/demo/pages_calendar.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/');?>js/demo/form_components.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url('assets/template/');?>js/demo/charts/chart_filled_blue.js"></script> -->
<!-- <script type="text/javascript" src="<?php echo base_url('assets/template/');?>js/demo/charts/chart_simple.js"></script> -->

<!-- JWT Encode -->
<script type="text/javascript" src="<?php echo base_url('assets/plugins/');?>jwt/encode/hmac-sha256.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/plugins/');?>jwt/encode/enc-base64-min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/plugins/');?>jwt/encode/jwt.encode.js"></script>

<!-- JWT Decode -->
<script type="text/javascript" src="<?php echo base_url('assets/plugins/');?>jwt/decode/build/jwt-decode.min.js"></script>

<!--<script type="text/javascript" src="--><?php //echo base_url('assets/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js');?><!--"></script>-->

<script type="text/javascript" src="<?php echo base_url('assets/inputmask/jquery.inputmask.bundle.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datepicter/js/bootstrap-datetimepicker.min.js');?>"></script>

<script type="text/javascript" src="<?php echo base_url('assets/plugins/bootstrap-toggle/js/bootstrap-toggle.min.js');?>"></script>
    
<!-- Socket js -->
<script type="text/javascript" src="<?php echo base_url('node_modules/socket.io/node_modules/socket.io-client/socket.io.js');?>"></script>
<!-- Custom -->
<script type="text/javascript">
    window.base_url_js = "<?php echo base_url(); ?>";
    //window.base_url_js = "<?php //echo 'http://10.1.10.27:8080/siak3/'; ?>//";
    window.base_url_img_employee = "<?php echo base_url('uploads/employees/'); ?>";
    window.base_url_img_student = "<?php echo base_url('uploads/students/'); ?>";
    window.sessionNIP = "<?php echo $this->session->userdata('NIP'); ?>";
    window.timePerCredits = "<?php echo $this->session->userdata('timePerCredits'); ?>";

    window.base_url_sign_out = "<?php echo url_sign_out ?>";
    window.base_url_portal_students = "<?php echo url_sign_in_students ?>";
    window.base_url_portal_lecturers = "<?php echo url_sign_in_lecturers ?>";

    window.allowDepartementNavigation = [];

    window.daysEng = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    window.daysInd = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

    window.timeOption = {
        format : 'hh:ii',
        weekStart: 1,
        todayBtn:  0,
        autoclose: 1,
        todayHighlight: 0,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 1};

    $(document).ready(function(){
        // "use strict";


        App.init(); // Init layout and core plugins
        Plugins.init(); // Init all plugins
        FormComponents.init(); // Init all form-specific plugins

        $('.img-fitter').imgFitter({

            // CSS background position
            backgroundPosition: 'center center',

            // for image loading effect
            fadeinDelay: 400,
            fadeinTime: 1200

        });

    });

    $(document).on('click','.btnActionLogOut',function () {
        var url = base_url_js+"auth/logMeOut";
        loading_page('#NotificationModal .modal-body');
        $.post(url,function (result) {
            setTimeout(function () {
                window.location.href = base_url_sign_out;
            },2000);
        });
    });

    function load_navigation() {
        localStorage.getItem('departement');
    }


    $.fn.extend({
        animateCss: function (animationName, callback) {
            var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
            this.addClass('animated ' + animationName).one(animationEnd, function() {
                $(this).removeClass('animated ' + animationName);
                if (callback) {
                    callback();
                }
            });
            return this;
        }
    });

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    function loading_page(element) {
        $(element).html('<div class="row">' +
            '<div class="col-md-12" style="text-align: center;">' +
            '<h3 class="animated flipInX"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i> <span>Loading page . . .</span></h3>' +
            '</div>' +
            '</div>');
    }

    function loading_text(element) {
        $(element).html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Loading...');
    }

    function loading_button(element) {
        $(''+element).html('<i class="fa fa-refresh fa-spin fa-fw right-margin"></i> Loading...');
        $(''+element).prop('disabled',true);
    }

    function loading_buttonSm(element) {
        $(''+element).html('<i class="fa fa-refresh fa-spin fa-fw"></i>');
        $(''+element).prop('disabled',true);
    }

    function loading_data(element) {
        $(''+element).html('<i class="fa fa-refresh fa-spin fa-fw"></i> please, waiting');
    }

    function convertDateMMtomm(mounth) {
        var arr_mounth = {
            'January': 0,
            'February': 1,
            'March': 2,
            'April': 3,
            'May': 4,
            'June': 5,
            'July': 6,
            'August': 7,
            'September': 8,
            'October': 9,
            'November': 10,
            'December': 11
        }

        return arr_mounth[mounth];
    }

    function dateTimeNow() {
        return moment().format('YYYY-MM-DD HH:mm:ss');
    }

    function ucwords(str) {
        return str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });
    }

    function log(data) {
        console.log(data);
    }

    function genCharArray() {
        var charA = 'A', charZ = 'Z';
        var a = [], i = charA.charCodeAt(0), j = charZ.charCodeAt(0);
        for (; i <= j; ++i) {
            a.push(String.fromCharCode(i));
        }
        return a;
    }

    function loadSelectOptionSemester(element,selected) {

        var token = jwt_encode({action:'read'},'UAP)(*');
        var url = base_url_js+'api/__crudTahunAkademik';
        $.post(url,{token:token},function (jsonResult) {

           if(jsonResult.length>0){
               for(var i=0;i<jsonResult.length;i++){
                   var dt = jsonResult[i];
                   var sc = (selected==dt.ID) ? 'selected' : '';
                   // var v = (option=="Name") ? dt.Name : dt.ID;
                   $(element).append('<option value="'+dt.ID+'.'+dt.Name+'" '+sc+'>'+dt.Name+'</option>');
               }
           }
        });

    }

    function loadSelectOptionProgramCampus(element,selected) {
        var url = base_url_js+'api/__crudProgramCampus';
        var token = jwt_encode({action:'read'},'UAP)(*');
        $.post(url,{token:token},function (data_json) {
            if(data_json.length>0){
                var option = $(element);
                for(var i=0;i<data_json.length;i++){
                    selected = (selected==data_json[i].ID) ? 'selected' : '';
                    option.append('<option value="'+data_json[i].ID+'" '+selected+'>'+data_json[i].Name+'</option>');
                }
            }
        });
    }

    function loadSelectOptionBaseProdi(element,selected) {
        var url = base_url_js+"api/__getBaseProdiSelectOption";
        $.get(url,function (data) {
            for(var i=0;i<data.length;i++){
                var selc = (data[i].ID==selected) ? 'selected' : '';
                $(''+element).append('<option value="'+data[i].ID+'.'+data[i].Code+'" '+selc+'>'+data[i].Level+' - '+data[i].NameEng+'</option>');
            }
        });
    }

    function loadSelectOptionCurriculum(element,selected) {
        var url = base_url_js+"api/__getKurikulumSelectOption";
        $.get(url,function (data_json) {
            // console.log(data_json);
            for(var i=0;i<data_json.length;i++){
                var selected = (data_json[i].ID==selected) ? 'selected' : '';
                $(element).append('<option value="'+data_json[i].ID+'.'+data_json[i].Year+'" '+selected+'>'+data_json[i].NameEng+'</option>');
            }
        });
    }

    function loadSelectOptionBaseProdiAll(element,selected) {
        var url = base_url_js+"api/__getBaseProdiSelectOptionAll";
        $.get(url,function (data) {
            for(var i=0;i<data.length;i++){
                var selc = (data[i].ID==selected) ? 'selected' : '';
                $(''+element).append('<option value="'+data[i].ID+'.'+data[i].Code+'" '+selc+'>'+data[i].NameEng+'</option>');
            }
        });
    }

    function loadSelectOptionEducationLevel(element,selected) {
        var url = base_url_js+"api/__geteducationLevel";
        $.get(url,function (data) {
            for(var i=0;i<data.length;i++){
                var selc = (data[i].ID==selected) ? 'selected' : '';
                $(''+element).append('<option value="'+data[i].ID+'" '+selc+'>'+data[i].Name+'</option>');
            }
        });
    }

    function loadSelectOPtionAllSemester(element,selected,SemesterID,IsSemesterAntara) {

        var url = base_url_js+'api/__crudTahunAkademik';
        var data = {
            action:'DataSemester',
            SemesterID:SemesterID,
            IsSemesterAntara:IsSemesterAntara
        };
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {
            for(var i=0;i<jsonResult.length;i++){
                var color = (jsonResult[i].Semester>8) ? 'red' : '#333';
                if(jsonResult[i].Semester<=14){
                    $(element).append('<option style="color: '+color+';" value="'+jsonResult[i].Semester+'|'+jsonResult[i].Curriculum.ID+'.'+jsonResult[i].Curriculum.Year+'|'+jsonResult[i].Curriculum.NameEng+'">Semester '+jsonResult[i].Semester+'</option>');
                }

            }
        });

    }

    function loadSelectOptionStatusStudent(element,selected) {

        var url = base_url_js+'api/__crudStatusStudents';
        var data = {
            action : 'read'
        };

        var token = jwt_encode(data,'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            if(jsonResult.length>0){
                for(var s=0;s<jsonResult.length;s++){
                    var d = jsonResult[s];
                    var sc = (selected==d.ID) ? 'selected' : '';
                    $(element).append('<option value="'+d.ID+'" '+sc+'>'+d.Description+'</option>');
                }
            }

        });

    }

    $(document).on('click','button[data-toggle=collapse]',function () {
        var cl = $(this).attr("class").split(" ");

        if($.inArray('btn-danger',cl)==1){
            $(this).removeClass('btn-danger');
            $(this).addClass('btn-info');
            $(this).children().removeClass('fa-minus-circle');
            $(this).children().addClass('fa-plus-circle');
        } else {
            $(this).addClass('btn-danger');
            $(this).removeClass('btn-info');
            $(this).children().addClass('fa-minus-circle');
            $(this).children().removeClass('fa-plus-circle');
        }

    });

    function loadSelectOptionAllMataKuliah(element) {

        var url = base_url_js+'api/__getAllMK';
        var option = $(''+element);

        $.get(url,function (data) {
            for(var i=0;i<data.length;i++){
                option.append('<option value="'+data[i].ID+'.'+data[i].MKCode+'">'+data[i].Code+' | '+data[i].MKCode+' - '+data[i].NameEng+'</option>');
            }
        });
    }

    function loadSelectOptionAllMataKuliahForPraSyarat(element,selected) {

        var url = base_url_js+'api/__getAllMK';
        var option = $(''+element);
        $.get(url,function (data) {
            for(var i=0;i<data.length;i++){

                option.append('<option value="'+data[i].ID+'.'+data[i].MKCode+'">'+data[i].Code+' | '+data[i].MKCode+' - '+data[i].NameEng+'</option>')
                    .val(selected).trigger('change');

            }
        });
    }

    function loadSelectOptionAllMataKuliahSingle(element,selected) {
        var url = base_url_js+'api/__getAllMK';
        var option = $(''+element);

        $.get(url,function (data) {

            for(var i=0;i<data.length;i++){
                option.append('<option value="'+data[i].ID+'.'+data[i].MKCode+'" >'+data[i].Code+' | '+data[i].MKCode+' - '+data[i].NameEng+'</option>')
                    .val(''+selected).trigger('change');
            }
        });
    }

    function loadSelectOptionLecturersSingle(element,selected) {

        var url = base_url_js+'api/__getDosenSelectOption';
        $.get(url,function (data) {
            var option = $(''+element);
            for(var i=0; i<data.length; i++){
                option.append('<option value="'+data[i].NIP+'">'+data[i].NIP+' | '+data[i].Name+'</option>')
                    .val(selected).trigger('change');
            }
        });
    }

    function loadSelectOptionConf(element,jenis,selected) {

        var table = jenis;

        var url = base_url_js+"api/__crudKurikulum";
        var data = {
            action : 'read',
            table : table
        };

        var token = jwt_encode(data,'UAP)(*');

        $.post(url,{token:token},function (data_json) {
            if(data_json.length>0){
                for(var i=0;i<data_json.length;i++){
                    var selc = (selected==data_json[i].ID) ?  'selected' : '';

                    $(''+element).append('<option value="'+data_json[i].ID+'" '+selc+'>'+data_json[i].Name+'</option>');
                }
            }
        })
    }

    function loadSelectOptionClassGroup(element,selected) {

        var url = base_url_js+'academic/kurikulum/getClassGroup';
        var token = jwt_encode({action:'read_json'},'UAP)(*');

        $.post(url,{token:token},function (data_json) {

            var option = $(''+element);
            if(data_json.length>0){
                for(var i=0;i<data_json.length;i++){
                    option.append('<optgroup id="opt'+i+'" label="'+data_json[i].optgroup.ProdiName+'"></optgroup>');
                    var opt = $('#opt'+i);
                    var detail = data_json[i].options;
                    for(var x=0;x<detail.length;x++){
                        opt.append('<option value="'+detail[x].ID+'">'+detail[x].Name+'</option>');
                    }
                }
            }


        });

    }


    function loadSelectOptionClassGroupAttendance(SemesterID,element,selected) {

        // var url = base_url_js+'academic/kurikulum/getClassGroup';
        var url = base_url_js+'api/__crudSchedule';
        var token = jwt_encode({action:'getClassGroup',SemesterID:SemesterID},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {

            for(var i=0;i<jsonResult.length;i++){

                var d = jsonResult[i];

                $(element).append('<option value="'+d.ScheduleID+'.'+d.ClassGroup+'">'+d.ClassGroup+' - '+d.Name+'</option>');
            }

        });

    }

    function loadSelectOptionClassroom(element,selected){
        var url = base_url_js+'api/__crudClassroom';
        var token = jwt_encode({action:'read'},'UAP)(*');

        var option = $(''+element);
        $.post(url,{token:token},function (data_json) {
            if(data_json.length>0){
                $(element).empty();
                for(var i=0;i<data_json.length;i++){
                    var selec = (selected==data_json[i].ID) ? 'selected' : '';
                    option.append('<option value="'+data_json[i].ID+'" '+selec+'>'+data_json[i].Room+' | Seat : '+data_json[i].Seat+' | Exam : '+data_json[i].SeatForExam+'</option>');
                }
            }

        });
    }

    function loadSelectOptionTimePerCredit(element,selected) {
        var url = base_url_js+'api/__crudTimePerCredit';
        var token = jwt_encode({action:'read'},'UAP)(*');

        var option = $(''+element);
        $.post(url,{token:token},function (data_json) {
            if(data_json.length>0){
                $(element).empty();
                for(var i=0;i<data_json.length;i++){
                    var selec = (selected==data_json[i].Time) ? 'selected' : '';
                    option.append('<option value="'+data_json[i].Time+'" '+selec+'>'+data_json[i].Time+' minute</option>');
                }
            }

        });
    }

    function loSelectOptionSemester(element,selected) {
        var url = base_url_js+'api/__crudSemester';
        var token = jwt_encode({action:'read',order:'DESC'},'UAP)(*');

        $.post(url,{token:token},function (data_json) {
            var option = $(element);
            if(data_json.length>0){
                for(var i=0;i<data_json.length;i++){
                    var selc = '';
                    if(selected=='selectedNow') {
                        selc = (data_json[i].Status==1) ? 'selected' : '';

                    } else {
                        selc = (selected==data_json[i].ID) ? 'selected' : '';
                    }

                    option.append('<option value="'+data_json[i].ID+'.'+data_json[i].Year+'.'+data_json[i].Code+'" '+selc+'>'+data_json[i].Name+'</option>');

                }
            }
        });
    }

    function loadSelectOptionEmployeesSingle(element,selected) {
        var url = base_url_js+'api/__crudEmployees';
        var token = jwt_encode({action:'read'},'UAP)(*');
        $.post(url,{token:token},function (data_json) {
            var option = $(element);
            if(data_json.length>0){
                for(var i=0;i<data_json.length;i++){
                    var data = data_json[i];
                    option.append('<option value="'+data.NIP+'">'+data.NIP+' - '+data.Name+'</option>');
                }
            }
        })
    }


    // ===== Function HR =====

    function loadSelectOptionStatusEmployee(element,selected) {
        var url = base_url_js+'api/__getStatusEmployee';
        $.getJSON(url,function (jsonResult) {
            for(var i=0;i<jsonResult.length;i++){
                var d = jsonResult[i];
                var sc = (selected!=null && selected!='' && typeof  selected !== 'undefined' && selected==d.IDStatus) ? 'selected' : '';
                var bg = (d.IDStatus < 0) ? 'style="color:red;"' : '' ;
                $(element).append('<option value="'+d.IDStatus+'" '+sc+' '+bg+'>'+d.Description+'</option>');
            }
        });
    }

    // ======================

    function fillDays(element,lang,selected) {
        var days = (lang=='Eng') ? daysEng : daysInd ;

        // $('#NameDay1');
        var val = 1;
        for(var i=0;i<days.length;i++){
            var selc = (val==selected) ? 'selected' : '';
            var hol = (val==6 || val==7) ? 'style="color:red;"' : '' ;
            $(''+element).append('<option value="'+val+'" '+selc+' '+hol+'>'+days[i]+'</option>');
            val += 1;
        }
    }

    function formatRupiah(bilangan) {
        var	number_string = bilangan.toString(),
            sisa 	= number_string.length % 3,
            rupiah 	= number_string.substr(0, sisa),
            ribuan 	= number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return 'Rp. '+rupiah+',-';
    }

    function UrlExists(url) {
        var http = new XMLHttpRequest();
        http.open('HEAD', url, false);
        http.send();
        return http.status!=404;
    }

    function errorInput(element) {
        $(element).css('border','1px solid red');
        setTimeout(function () {
            $(element).css('border','1px solid #cccccc');
        },5000);

        return false;
    }

    // BY ADHI
    function Validation_leastCharacter(leastNumber,string,theName) {
        var result = {status:1, messages:""};
        var stringLenght =  string.length;
        if (stringLenght < leastNumber) {
            result = {status : 0,messages: theName + " at least " + leastNumber + " character"};
        }
        return result;
    }

    function Validation_email(string,theName)
    {
        var result = {status:1, messages:""};
        var regexx =  /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        if (!string.match(regexx)) {
            result = {status : 0,messages: theName + " an invalid email address! "};
        }
        return result;
    }

    function Validation_email_gmail(string,theName)
    {
        var result = {status:1, messages:""};
        var regexx =  /^[a-z0-9](\.?[a-z0-9]){5,}@g(oogle)?mail\.com$/;
        if (!string.match(regexx)) {
            result = {status : 0,messages: theName + " only gmail allowed to register! "};
        }
        return result;
    }

    function Validation_required(string,theName)
    {
        var result = {status:1, messages:""};
        if (string == "" || string == null) {
            result = {status : 0,messages: theName + " is required! "};
        }
        return result;
    }

    function Validation_numeric(string,theName)
    {
        var result = {status:1, messages:""};
        var regexx =  /^\d+$/;;
        if (!string.match(regexx)) {
            result = {status : 0,messages: theName + " only numeric! "};
        }
        return result;
    }

    function LoaddataTable(element) {
        var table = $(element).DataTable({
            'iDisplayLength' : 5,
            'ordering' : true,
            "sDom": "<'row'<'dataTables_header clearfix'<'col-md-3'l><'col-md-9'Tf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>", // T is new
            "oTableTools": {
                "aButtons": [
                    // "copy",
                    // "print",
                    // "csv",
                    {
                        "sExtends" : "xls",
                        "sButtonText" : '<i class="fa fa-download" aria-hidden="true"></i> Excel',
                    },
                    {
                        "sExtends" : "pdf",
                        "sButtonText" : '<i class="fa fa-download" aria-hidden="true"></i> PDF',
                        "sPdfOrientation" : "landscape",
                        // "sPdfMessage" : "Daftar Seluruh Mata Kuliah"
                    }
                ],
                "sSwfPath": base_url_js+"assets/template/plugins/datatables/tabletools/swf/copy_csv_xls_pdf.swf"
            },
        });
    }

    function LoaddataTableStandard(element) {
        var table = $(element).DataTable({
            'iDisplayLength' : 10,
            'ordering' : true,
            // "sDom": "<'row'<'dataTables_header clearfix'<'col-md-3'l><'col-md-9'Tf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>", // T is new
        });
    }

    function getValueChecbox(element)
    {
         var allVals = [];
         $('.datatable2 :checked').each(function() {
           allVals.push($(this).val());
         });
         return allVals;
    }

    function loadingStart()
    {
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
    }

    function loadingEnd(timeout)
    {
        setTimeout(function () {
            $('#NotificationModal').modal('hide');
        },timeout);
    }

    function PopupCenter(url, title, w, h) {
        // Fixes dual-screen position                         Most browsers      Firefox
        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

        width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var left = ((width / 2) - (w / 2)) + dualScreenLeft;
        var top = ((height / 2) - (h / 2)) + dualScreenTop;
        var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        // Puts focus on the newWindow
        if (window.focus) {
            newWindow.focus();
        }
    }

    // Adi
    function loadSelectOptionPaymentTypeAll(element,selected) {
        var url = base_url_js+"api/__getBasePaymentTypeSelectOption";
        $.get(url,function (data) {
            for(var i=0;i<data.length;i++){
                var selc = (data[i].ID==selected) ? 'selected' : '';
                $(''+element).append('<option value="'+data[i].ID+'" '+selc+'>'+data[i].Abbreviation+'</option>');
            }
        });
    }

    // Adi
    function loadSelectOptionPaymentTypeMHS(element,selected) {
        var url = base_url_js+"api/__getBasePaymentTypeSelectOption";
        $.get(url,function (data) {
            for(var i=0;i<data.length;i++){
                if (data[i].ID == 2 || data[i].ID == 3) {
                    var selc = (data[i].ID==selected) ? 'selected' : '';
                    $(''+element).append('<option value="'+data[i].ID+'" '+selc+'>'+data[i].Abbreviation+'</option>');
                }
            }
        });
    }

    function loadSelectOptionDiscount(element,selected) {
        var url = base_url_js+"api/__getBaseDiscountSelectOption";
        $.get(url,function (data) {
            for(var i=0;i<data.length;i++){
                var selc = (data[i].ID==selected) ? 'selected' : '';
                $(''+element).append('<option value="'+data[i].Discount+'" '+selc+'>'+data[i].Discount+'</option>');
            }
        });
    }

    function loadSelectOptionDivision(element,selected) {

        if(selected=='' || typeof selected === "undefined"){
            $(element).append('<option value="" selected>-- Select Division --</option>');
            $(element).append('<option disabled>----------------</option>');
        }

        var url = base_url_js+'api/__getDivision';
        $.getJSON(url,function (jsonResult) {
            for(var i=0;i<jsonResult.length;i++){
                var d = jsonResult[i];
                var sc = ( selected!='' && typeof selected !== "undefined" && selected==d.ID) ? 'selected' : '';
                $(element).append('<option value="'+d.ID+'" '+sc+'>'+d.Division+'</option>');
            }
        });
    }

    function loadSelectOptionPosition(element,selected) {

        if(selected=='' || typeof selected === "undefined"){
            $(element).append('<option value="" selected>-- Select Position --</option>');
            $(element).append('<option disabled>----------------</option>');
        }

        var url = base_url_js+'api/__getPosition';
        $.getJSON(url,function (jsonResult) {
            for(var i=0;i<jsonResult.length;i++){
                var d = jsonResult[i];
                var sc = ( selected!='' && typeof selected !== "undefined" && selected==d.ID) ? 'selected' : '';
                $(element).append('<option value="'+d.ID+'" '+sc+'>'+d.Position+'</option>');
            }
        });
    }

    function loadSelectOptionReligi(element,selected) {
        var url = base_url_js+'api/__getAgama';

        $.getJSON(url,function (jsonResult) {
            for(var i=0;i<jsonResult.length;i++){
                var d = jsonResult[i];
                var sc = (selected!='' && typeof selected !== "undefined" && d.IDReligion == selected) ? 'selected' : '';
                $(element).append('<option value="'+d.IDReligion+'" '+sc+'>'+d.Religion+'</option>');
            }
        });
    }

    function loadSelectOptionEmployeesStatus(element,selected) {
        var url = base_url_js+'api/__getStatusEmployee';
        $.getJSON(url,function (jsonResult) {
            for(var i=0;i<jsonResult.length;i++){
                var d = jsonResult[i];
                var sc = (selected!='' && typeof selected !== "undefined" && selected==d.IDStatus) ? 'selected' : '';
                var color = (d.IDStatus<0) ? 'style="color:red;"' : '';
                $(element).append('<option value="'+d.IDStatus+'" '+color+' '+sc+'>'+d.Description+'</option>');
            }
        });
    }

    function loadYearOfBirth(element,selected){
        $(element).empty();
        var thisYear = (new Date()).getFullYear();
        var startTahun = parseInt(thisYear) - parseInt(120);
        var selisih = parseInt(thisYear) - parseInt(startTahun);
        for (var i = 0; i <= selisih; i++) {
            var valTh = ( parseInt(startTahun) + parseInt(i));
            var sc = (selected!='' && typeof selected !== "undefined" && valTh == selected ) ? 'selected' : '';
            $(element).append('<option value="'+valTh+'" '+sc+'>'+valTh+'</option>');
        }
    }

    function loadMonthBirth(element,selected){
        $(element).empty();
        var month = {
            01 : 'Jan',
            02 : 'Feb',
            03 : 'Mar',
            04 : 'April',
            05 : 'Mei',
            06 : 'Jun',
            07 : 'Jul',
            08 : 'Aug',
            09 : 'Sep',
            10 : 'Okt',
            11 : 'Nov',
            12 : 'Des'
    };

        for(var key in month) {
            var getKey = key.toString();
            var value = (getKey.length == 1) ? '0' + getKey : key;

            var sc = (selected!='' && selected!=null && typeof selected !== "undefined" && value==selected)? 'selected' : '';
            $(element).append('<option value="'+ value +'" '+sc+'>'+month[key]+'</option>');
        }

    }

    function loadCountDays(Year,Month,element,selected){

        $(element).empty();
        var countDays = moment(Year+"-"+Month, "YYYY-MM").daysInMonth()
        // get dd
        for (var i = 1; i <= countDays ; i++) {

            var getKey = i.toString();
            var value =  (getKey.length == 1) ? '0' + getKey : value = i;

            var sc = (selected!='' && typeof selected !== "undefined" && value == selected) ? 'selected' : '';

            $(element).append('<option value="'+ value +'" '+sc+'>'+value+'</option>');
        }
    }

    function viewImageBeforeUpload(input,el_View,el_SizeView,
                                   el_ExtView,el_size,el_Ext) {

        if (input.files && input.files[0]) {
            var sz = parseFloat(input.files[0].size) / 1000;
            var ext = input.files[0].type.split('/')[1];

            $(el_SizeView).html(sz.toFixed(2));
            $(el_ExtView).html(ext);

            $(el_size).val(sz.toFixed(2));
            $(el_Ext).val(ext);

            var reader = new FileReader();

            reader.onload = function(e) {
                $(el_View).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function findAndReplace(string, target, replacement) {
     
     var i = 0, length = string.length;
     
     for (i; i < length; i++) {
     
       string = string.replace(target, replacement);
     
     }
     
     return string;
     
    }
</script>
