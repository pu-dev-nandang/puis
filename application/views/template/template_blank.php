<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Podomoro University</title>

    <link href="<?php echo base_url('assets/template/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php  echo base_url('assets/template/css/fontawesome/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/template/css/fontawesome4/css/font-awesome.min.css'); ?>">

    <!-- Animated CSS -->
    <link href="<?php echo base_url('assets/template/plugins/animate/animate.css'); ?>" rel="stylesheet" type="text/css" />



    <script type="text/javascript" src="<?php echo base_url('assets/template/js/libs/jquery-1.10.2.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/template/bootstrap/js/bootstrap.min.js'); ?>"></script>

    <!-- JWT Encode -->
    <script type="text/javascript" src="<?php echo base_url('assets/plugins/');?>jwt/encode/hmac-sha256.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/plugins/');?>jwt/encode/enc-base64-min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/plugins/');?>jwt/encode/jwt.encode.js"></script>

    <!-- JWT Decode -->
    <script type="text/javascript" src="<?php echo base_url('assets/plugins/');?>jwt/decode/build/jwt-decode.min.js"></script>

    <!-- Moment JS -->
    <script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/moment-range/'); ?>moment-range.js"></script>

    <!-- ToasTR CSS & JS -->
    <link href="<?php echo base_url('assets/template/plugins/toastr/toastr.min.css'); ?>" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url('assets/template/'); ?>plugins/toastr/toastr.min.js"></script>

    <script>
        window.base_url_js = "<?php echo base_url(); ?>";

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

        function dateTimeNow() {
            return moment().format('YYYY-MM-DD HH:mm:ss');
        }

        function loading_button(element) {
            $(''+element).html('<i class="fa fa-refresh fa-spin fa-fw right-margin"></i> Loading...');
            $(''+element).prop('disabled',true);
        }

        function loading_buttonSm(element) {
            $(''+element).html('<i class="fa fa-refresh fa-spin fa-fw"></i>');
            $(''+element).prop('disabled',true);
        }

        function ucwords(str) {
            return str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });
        }

    </script>
</head>
<body style="background: #f7f7f7;">

<?php echo $content; ?>

<!-- Global Modal -->
<div class="modal fade" id="GlobalModal" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content animated flipInX">
            <div class="modal-header"></div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>