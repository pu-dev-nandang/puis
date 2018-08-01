
<style>
    .navbar .nav > li.current > a {
        background: #0f1f4b85;
    }

    .notificationdivisi
    {
        height: 500px;
        overflow-y: auto;
    }

</style>

<!-- Header -->
<header class="header navbar navbar-fixed-top" role="banner">
    <!-- Top Navigation Bar -->
    <div class="container">

        <!-- Only visible on smartphones, menu toggle -->
        <ul class="nav navbar-nav">
            <li class="nav-toggle"><a href="javascript:void(0);" title=""><i class="fa fa-reorder"></i></a></li>
        </ul>

        <!-- Logo -->
        <a class="navbar-brand" href="<?php echo base_url('dashboard'); ?>">
            <!-- <img src="<?php echo base_url('images/logo-hitam-putih.png'); ?>" alt="Podomoro University" style="width:130px;" /> -->
            <img src="<?php echo base_url('images/logo-header-hitam-putih.png'); ?>" alt="Podomoro University" style="width:150px;" />
            <!-- <strong>Podomoro</strong> University -->
        </a>
        <!-- /logo -->

        <!-- Sidebar Toggler -->
        <a href="#" class="toggle-sidebar bs-tooltip" data-placement="bottom" data-original-title="Toggle navigation">
            <i class="fa fa-reorder"></i>
        </a>
        <!-- /Sidebar Toggler -->

        <!-- Top Left Menu -->
        <ul class="nav navbar-nav navbar-left hidden-xs hidden-sm">
            <li class="<?php if($this->uri->segment(1)=='dashboard'){echo 'current';} ?>">
                <a href="<?php echo base_url('dashboard'); ?>">
                    <i class="icon-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);" id="btn_announcement">
                    <i class="fa fa-bullhorn" aria-hidden="true"></i>
                    <span>Announcement</span>
                </a>
            </li>
            <li class="<?php if($this->uri->segment(1)=='vreservation'){echo 'current';} ?>">
                <a href="<?php echo base_url('vreservation/dashboard/view'); ?>" id="btn_reservation">
                    <i class="fa fa-th-large" aria-hidden="true"></i>
                    <span>Venue Reservation</span>
                </a>
            </li>
            <li class="dropdown hidden-xs hidden-sm" id = 'NotificationPersonal'>

            </li>
        </ul>
        <!-- /Top Left Menu -->

        <!-- Top Right Menu -->
        <ul class="nav navbar-nav navbar-right">

            <!--            <li>-->
            <!--                <a href="javascript:void(0);">-->
            <!--                    Dept : <span style="color:yellow;">--><?php //echo ucwords($departement); ?><!--</span>-->
            <!--                </a>-->
            <!--            </li>-->

            <!-- Messages -->
            <li class="dropdown hidden-xs hidden-sm" id = 'NotificationDivisi'>

            </li>
            <li class="dropdown <?php if($this->uri->segment(1)=='database'){echo 'current';} ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-database"></i>
                    <span>Data</span>
                    <!--                    <i class="icon-caret-down small"></i>-->
                </a>
                <ul class="dropdown-menu">
                    <li class="<?php if($this->uri->segment(2)=='lecturers'){echo 'active';} ?>"><a href="<?php echo base_url('database/lecturers'); ?>">Lecturers</a></li>
                    <li class="<?php if($this->uri->segment(2)=='students'){echo 'active';} ?>"><a href="<?php echo base_url('database/students'); ?>">Students</a></li>
                    <li class="divider"></li>
                    <li class="<?php if($this->uri->segment(2)=='employees'){echo 'active';} ?>"><a href="<?php echo base_url('database/employees'); ?>">Employees</a></li>
                </ul>
            </li>

            <!-- Project Switcher Button -->
            <li class="dropdown <?php if($this->session->userdata('menuDepartement')){echo 'hide';} ?>">
                <a href="#" class="project-switcher-btn dropdown-toggle">
                    <i class="fa fa-folder-open"></i>
                    <span>Services</span>
                </a>
            </li>

            <!-- User Login Dropdown -->
            <li class="dropdown user">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-top: 8px;padding-bottom: 5px;">
                    <!--<img alt="" src="assets/img/avatar1_small.jpg" />-->
                    <!--                    <i class="fa fa-male"></i>-->
                    <!--                    <img src="--><?php //echo base_url('images/avatar.png'); ?><!--" class="img-circle" style="max-width: 35px;border: 3px solid #0f1f4b;"/>-->
                    <img data-src="<?php echo url_pas.'uploads/employees/'.$this->session->userdata('Photo'); ?>"
                         class="img-circle img-fitter" width="35" height="35" style="max-width: 35px;border: 3px solid #0f1f4b;"/>
                    <span class="username"><?php echo $name; ?></span>
                    <i class="fa fa-caret-down small"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url('profile/'.str_replace(' ','-',$this->session->userdata('Name'))); ?>">
                            <i class="fa fa-user"></i>
                            My Profile</a></li>
                    <!--                    <li><a href="pages_calendar.html"><i class="fa fa-calendar"></i> My Calendar</a></li>-->
                    <!--                    <li><a href="#"><i class="fa fa-tasks"></i> My Tasks</a></li>-->
                    <li class="divider"></li>
                    <li><a href="javascript:void(0)" id="useLogOut"><i class="fa fa-power-off"></i> Log Out</a></li>
                </ul>
            </li>
            <!-- /user login dropdown -->
        </ul>
        <!-- /Top Right Menu -->
    </div>
    <!-- /top navigation bar -->

    <?php echo $page_departement; ?>
    <!-- <button id = 'test'>aaa</button> -->
</header> <!-- /.header -->

<img src="">
<!-- Global Modal -->
<div class="modal fade" id="GlobalModal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content animated jackInTheBox">
            <div class="modal-header"></div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Global Modal Large -->
<div class="modal fade" id="GlobalModalLarge" role="dialog">
    <div class="modal-dialog" role="document" style="width:900px;">
        <div class="modal-content animated jackInTheBox">
            <div class="modal-header"></div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Notification -->
<div class="modal fade" id="NotificationModal" role="dialog" style="top: 100px;">
    <div class="modal-dialog" style="width: 400px;" role="document">
        <div class="modal-content animated flipInX">
            <!--            <div class="modal-header"></div>-->
            <div class="modal-body"></div>
            <!--            <div class="modal-footer"></div>-->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>

    $(document).ready(function () {
        $('.departement ,.departement1').addClass('hide');
        loadAllowDivision();
        showHTMLMessagesDivision();
        socket_messages();
    });

    $(document).on('click','#btn_announcement',function () {
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Announcement</h4>');
        $('#GlobalModal .modal-body').html('Announcement (Under Construction)');
        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '<button type="button" class="btn btn-primary"><i class="fa fa-paper-plane-o right-margin" aria-hidden="true"></i> Publish</button>');
        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    $(document).on('click','#useLogOut',function () {
        $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Log Me Out </b><hr/> ' +
            '<button type="button" class="btn btn-primary btnActionLogOut" style="margin-right: 5px;">Yes</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
            '</div>');
        $('#NotificationModal').modal('show');
    });



    $('.departement').click(function () {
        var url = base_url_js+'change-departement';
        var departement = $(this).attr('data-dpt');
        var IDDivision = $(this).attr('division');
        $.post(url,{departement:departement,IDDivision:IDDivision},function () {

            $('#NotificationModal .modal-header').addClass('hide');
            $('#NotificationModal .modal-body').html('<center>' +
                '                    <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>' +
                '                    <br/>' +
                '                    Loading departement . . .' +
                '                </center>');
            $('#NotificationModal .modal-footer').addClass('hide');
            $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
            });

            setTimeout(function () {
                $('#NotificationModal').modal('hide');
                window.location.href = base_url_js+'dashboard';
            },1500);
        });



    });

    function loadAllowDivision() {

        <?php
        $div = $this->session->userdata('ruleUser');
        foreach ($div as $item){ ?>
        allowDepartementNavigation.push(<?php echo $item['IDDivision']; ?>);
        <?php }
        ?>

        for(var i=0;i<allowDepartementNavigation.length;i++){
            $('li[division='+allowDepartementNavigation[i]+']').removeClass('hide');
        }
    };

    function socket_messages()
    {
        var socket = io.connect( 'http://'+window.location.hostname+':3000' );
        // var socket = io.connect( '<?php echo serverRoot ?>'+':3000' );

        socket.on( 'update_notifikasi', function( data ) {

            //$( "#new_count_message" ).html( data.new_count_message );
            //$('#notif_audio')[0].play();
            if (data.update_notifikasi == 1) {
                // action
                showHTMLMessagesDivision();
            }

        }); // exit socket
    }

    function ReadNotifDivision(IDDivision)
    {
        var url = base_url_js+'readNotificationDivision';
        var data = {IDDivision : IDDivision};
        var token = jwt_encode(data,"UAP)(*");

        $.post(url,{token:token},function (data_json) {
            var response = jQuery.parseJSON(data_json);
            if (response == 1) {
                // var socket = io.connect( '<?php echo serverRoot ?>'+':3000' );
                var socket = io.connect( 'http://'+window.location.hostname+':3000' );
                socket.emit('update_notifikasi', {
                    update_notifikasi: '1'
                });
            }
        });

    }

    function showHTMLMessagesDivision()
    {
        var url = base_url_js+'api/__getNotification_divisi';
        $.post(url,function (data_json) {
            var dataa = data_json['data'];
            $("#NotificationDivisi").empty();
            var IDDivision = "<?php echo $this->session->userdata('IDdepartementNavigation') ?>";
            var htmla = '<a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick = "ReadNotifDivision('+IDDivision+')">'+
                '<i class="fa fa-envelope"></i>'+
                '<span class="badge">'+data_json['count']+'</span>'+
                '</a>';
            if (data_json['data'].length > 0) {
                htmla += '<ul class="dropdown-menu extended notificationdivisi" style="max-width: 400px;width: 400px;">'+
                    '<li class="title">'+
                    '<p>You have '+data_json['count']+' new messages to Division</p>'+
                    '</li>';
                for (var i = 0; i < data_json['data'].length; i++) {
                    var token = dataa[i]['Token'];
                    token = jwt_decode(token,"UAP)(*");

                    htmla += '<li>'+
                        '<a href="'+'<?php echo url_pas ?>'+token['URL']+'" style="padding: 22px;">'+
                        '<span class="photo"><img src="'+base_url_js+'images/xx.jpg" alt="" /></span>'+
                        '<span class="subject">'+
                        '<span class="from">'+token['From']+'</span>'+
                        '</span>'+
                        '<span class="text">'+token['subject']+'</span>'+
                        '<span class="time">'+data_json['data'][i]['Created']+'</span>' +
                        '</a>'+
                        '</li>';
                }

                htmla +=  '<li class="footer"><a href="#">View all messages</a></li><ul>';
                $("#NotificationDivisi").append(htmla);
            }// exit if

        })
    }
</script>