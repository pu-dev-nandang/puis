
<style>
    .navbar .nav > li.current > a {
        background: #0f1f4b85;
    }

    .notificationdivisi
    {
        min-height: 10px;
        max-height: 400px;
        overflow-y: auto;
    }

    .label-image img {
        max-width: 43px;
    }

    #showingLog .dropdown-menu.extended {
        width: 390px;
        height: 345px;
        overflow: scroll;
        overflow-x: hidden;
    }

    #li2ShowLog .from {
        color: #083f88;
    }

    #tableSimpleSearch tr th,#tableSimpleSearch tr td {
        text-align: center;
    }

    .dropdown-menu.extended li .photo img.img-fitter-notif {
        height: 47px;
        width: 40px;
        margin-right: 10px;
    }

    .dropdown-menu.extended li a:hover {
        background: #eaeaea;
        color: #333333;
    }
    .dropdown-menu.extended li a:hover .time {
        color: #adadad;
    }
    .dropdown-menu.extended li a:hover .task .percent {
        color: #333333;
    }

    .table-centre tr th, .table-centre tr td {
        text-align: center;
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
            <li class="<?php if($this->uri->segment(1)=='my-activities'){echo 'current';} ?>">
                <a href="<?php echo base_url('my-activities'); ?>">
                    <i class="fa fa-line-chart"></i>
                    <span>My Activities</span>
                </a>
            </li>
            <?php $sw = ($_SERVER['SERVER_NAME']=='localhost') ? '' : ''; ?>
            <li class="<?php echo $sw.' '; if($this->uri->segment(1)=='ticket'){echo 'current';} ?>">
                <a href="<?php echo base_url('ticket/ticket-today'); ?>" id="btn_announcement">
                    <i class="fa fa-ticket" aria-hidden="true"></i>
                    <span>Ticketing</span>
                </a>
            </li>


            <?php $sw = ($_SERVER['SERVER_NAME']=='localhost') ? '' : 'hide'; ?>
            <li class="<?php echo $sw.' '; if($this->uri->segment(1)=='global-informations'){echo 'current';} ?>">
                <a href="<?php echo base_url('global-informations/students'); ?>" id="btn_announcement">
                    <i class="fa fa-globe" aria-hidden="true"></i>
                    <span>Global Information</span>
                </a>
            </li>


            <?php $DepartmentNav  = $this->session->userdata('IDdepartementNavigation');
            ?>
            <?php if ($this->session->userdata('prodi_get')): ?>
                <?php if (count($this->session->userdata('prodi_get')) > 1 && $DepartmentNav == 15): ?>
                    <li>
                        <a href="<?php echo base_url('dashboard'); ?>">
                            <i class="glyphicon glyphicon-transfer"></i>
                            <span>Change Prodi</span>
                        </a>
                    </li>
                <?php endif ?>
            <?php endif ?>
            <?php if ($this->session->userdata('faculty_get')): ?>
                <?php if (count($this->session->userdata('faculty_get')) > 1 && $DepartmentNav == 34): ?>
                    <li>
                        <a href="<?php echo base_url('dashboard'); ?>">
                            <i class="glyphicon glyphicon-transfer"></i>
                            <span>Change Faculty</span>
                        </a>
                    </li>
                <?php endif ?>
            <?php endif ?>
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


            <!-- Logging -->
            <li class="dropdown" id="showingLog" >
                <a href="#" class="dropdown-toggle" onclick="showLog();" data-toggle="dropdown">
                    <i class="fa fa-sort-amount-asc"></i>
                    <span class="badge totalUnreadLog">0</span>
                </a>
                <ul class="dropdown-menu extended notification" id="li2ShowLog">
                    <li class="title">
                        <p>Log not yet</p>
                    </li>
                </ul>
            </li>

            <!-- Messages -->
            <li class="dropdown hidden-xs hidden-sm" id = 'NotificationDivisi'></li>

            <?php if(count($rule_service)>0){ ?>
                <li class="dropdown <?php if($this->uri->segment(1)=='database'){echo 'current';} ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-cogs"></i>
                        <span>Services</span>
                        <!--                    <i class="icon-caret-down small"></i>-->
                    </a>
                    <ul class="dropdown-menu">

                        <!-- Lecturer -->
                        <?php if(in_array(2,$rule_service)){ ?>
                            <li class="<?php if($this->uri->segment(2)=='lecturers'){echo 'active';} ?>">
                                <a href="<?php echo base_url('database/lecturers'); ?>"><i class="fa fa-user-secret"></i> Lecturers</a></li>
                        <?php } ?>

                        <?php if(in_array(1,$rule_service)){ ?>
                            <li class="<?php if($this->uri->segment(2)=='students'){echo 'active';} ?>">
                                <a href="<?php echo base_url('database/students'); ?>"><i class="fa fa-street-view"></i> Students</a></li>
                        <?php } ?>

                        <?php if(in_array(3,$rule_service)){ ?>
                            <li class="<?php if($this->uri->segment(2)=='employees'){echo 'active';} ?>">
                                <a href="<?php echo base_url('database/employees'); ?>"><i class="fa fa-users"></i> Employees</a></li>
                        <?php } ?>

                        <?php if(in_array(4,$rule_service)){ ?>
                            <li class=""><a href="javascript:void(0);" id="btnSimpleSearch"><i class="fa fa-search"></i> Simple Search</a></li>
                        <?php } ?>

                        <?php if(in_array(7,$rule_service)){ ?>
                            <li class=""><a href="<?php echo base_url('announcement/list-announcement'); ?>"><i class="fa fa-bullhorn"></i> Announcement</a></li>
                        <?php } ?>

                        <?php if(in_array(5,$rule_service)){ ?>
                            <li class="<?php if($this->uri->segment(1)=='vreservation'){echo 'active';} ?>">
                                <a href="<?php echo base_url('vreservation'); ?>"><i class="fa fa-th-large" aria-hidden="true"></i> Venue Reservation</a>
                            </li>
                        <?php } ?>

                        <?php if(in_array(8,$rule_service)){ ?>
                            <li class="<?php if($this->uri->segment(1)=='requestdocument'){echo 'active';} ?>">
                                <a href="<?php echo base_url('requestdocument'); ?>"><i class="glyphicon glyphicon-transfer" aria-hidden="true"></i> Request Document</a>
                            </li>
                        <?php } ?>

                        <?php if(in_array(6,$rule_service)){ ?>
                            <li class="<?php if($this->uri->segment(1)=='budgeting'){echo 'active';} ?>" id = "PageServiceBudgeting">
                                <a href="<?php echo base_url('budgeting'); ?>"><i class="fa fa-money" aria-hidden="true"></i> Budgeting</a>
                            </li>
                        <?php } ?>

                        <?php if(in_array(9,$rule_service)){ ?>
                            <li class="<?php if($this->uri->segment(1)=='agregator'){echo 'active';} ?>">
                                <a href="<?php echo base_url('agregator/akreditasi-eksternal'); ?>"><i class="fa fa-flag" aria-hidden="true"></i> Aggregator (APT)</a>

                            </li>
                        <?php } ?>

                        <?php if(in_array(10,$rule_service)){ ?>
                            <li class="<?php if($this->uri->segment(1)=='agregator-aps'){echo 'active';} ?>">
                                <a href="<?php echo base_url('agregator-aps/kerjasama-tridharma'); ?>"><i class="fa fa-flag" aria-hidden="true"></i> Aggregator (APS)</a>
                            </li>
                        <?php } ?>

                            <!-- Adding for Testing -->
                            <?php $sw = ($_SERVER['SERVER_NAME']=='localhost') ? '' : 'hide'; ?>
                                 <li class="<?php echo $sw.' '; if($this->uri->segment(1)=='requestdocument-generator'){echo 'active';} ?>">
                                     <a href="<?php echo base_url('requestdocument-generator'); ?>"><i class="glyphicon glyphicon-transfer" aria-hidden="true"></i> Request Document Generator</a>
                                 </li>
                            <!-- End Adding for Testing -->
                    </ul>
                </li>
            <?php } ?>


            <!-- Project Switcher Button -->
            <li class="dropdown <?php if($this->session->userdata('menuDepartement')){echo 'hide';} ?>">
                <a href="#" class="project-switcher-btn dropdown-toggle">
                    <i class="fa fa-folder-open"></i>
                    <span>Department</span>
                </a>
            </li>

            <!-- User Login Dropdown -->
            <li class="dropdown user">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-top: 8px;padding-bottom: 5px;">
                    <?php $imgProfile = (file_exists('./uploads/employees/'.$this->session->userdata('Photo')))
                        ? url_pas.'uploads/employees/'.$this->session->userdata('Photo')
                        : url_pas.'images/icon/no_image.png'; ?>
                    <img data-src="<?php echo $imgProfile; ?>"
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
                    <!-- <li>
                        <a href="<?php echo base_url('vreservation/dashboard/view'); ?>" id="btn_reservation">
                            <i class="fa fa-th-large" aria-hidden="true"></i>
                            <span>Venue Reservation</span>
                        </a>
                    </li> -->
                   <!--  <li>
                        <a href="<?php echo base_url('budgeting'); ?>">
                            <i class="fa fa-money" aria-hidden="true"></i>
                            <span>Budgeting</span>
                        </a>
                    </li> -->
                    <li><a href="<?php echo base_url('help'); ?>">
                            <i class="fa fa-bookmark"></i>
                            Help</a></li>
                    <li><a href="<?php echo base_url('kb'); ?>">
                                    <i class="fa fa-file"></i>
                                    Knowledge Base</a></li>
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

<!-- Global Modal Small -->
<div class="modal fade" id="GlobalModalSmall" role="dialog">
    <div class="modal-dialog" role="document" style="width:355px;">
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

     // var socket = io.connect( 'http://'+window.location.hostname+':3000' );

    $(document).ready(function () {
        $('.departement ,.departement1').addClass('hide');
        loadAllowDivision();
        // showHTMLMessagesDivision();
        showUnreadLog();
        // socket_messages();
        wrDepartmentAdmProdi();

        if($.cookie("theme")==null || $.cookie("theme") =='' || $.cookie("theme")=='dark'){
            $('#theme-switcher label').addClass('btn-inverse active');
            $('#theme-switcher label:first-child').removeClass('active');
        }

        // socket.emit('update_log', {
        //     update_log: '1'
        // });

        saveLogUser();

    });

    $('#btnSimpleSearch').click(function () {
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Simple Search</h4>');

        var htmlss = '<div class="row">' +
            '        <div class="col-md-12">' +
            '            <div class="input-group">' +
            '                <input type="text" id="formSimpleSearch" class="form-control" placeholder="Search by NIM, NIP, Name . . .">' +
            '                        <span class="input-group-btn">' +
            '                <button class="btn btn-default" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>' +
            '              </span>' +
            '            </div><!-- /input-group -->' +
            '        </div>' +
            '        <div class="col-md-12"><hr/>' +
            '            <table class="table table-bordered" id="tableSimpleSearch">' +
            '                <thead>' +
            '                <tr style="background: #438882;color: #fff;">' +
            '                    <th>User</th>' +
            '                    <th style="width: 25%;">Status</th>' +
            '                    <th style="width: 5%;">Portal</th>' +
            '                </tr>' +
            '                </thead>' +
            '                <tbody id="trDataUser"></tbody>' +
            '            </table>' +
            '        </div>' +
            '    </div>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModal').on('shown.bs.modal', function () {
            $('#formSimpleSearch').focus();
        })

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    $(document).on('keyup','#formSimpleSearch',function () {

        var formSimpleSearch = $('#formSimpleSearch').val();

        if(formSimpleSearch!='' && formSimpleSearch!=null){
            var url = base_url_js+'api/__getSimpleSearch?key='+formSimpleSearch;
            $.getJSON(url,function (jsonResult) {
                // console.log(jsonResult);
                $('#trDataUser').empty();
                if(jsonResult.length>0){
                    for(var i=0;i<jsonResult.length;i++){
                        var d = jsonResult[i];

                        var bg = '#fff';

                        var btnLoginPortal = '-';
                        if(d.Flag=='std'){
                            btnLoginPortal = '<button class="btn btn-block btn-primary btnLoginPortalStudents" data-npm="'+d.Username+'"><i class="fa fa-sign-in right-margin"></i> Portal</button>';
                        } else {
                            bg = '#ffeb3b3b';

                            if($.inArray('14.7',d.Position)!=-1 || $.inArray('14.6',d.Position)!=-1 || $.inArray('14.5',d.Position)!=-1){
                                btnLoginPortal = '<button class="btn btn-block btn-success btnLoginPortal" data-nip="'+d.Username+'" data-password="'+d.Token+'"><i class="fa fa-sign-in right-margin"></i> Portal</button>';
                            }
                        }

                        $('#trDataUser').append('<tr style="background: '+bg+';">' +
                            '<td style="text-align: left;"><b>'+d.Name+'</b><br/><span>'+d.Username+'</span></td>' +
                            '<td>'+d.Status+'</td>' +
                            '<td>'+btnLoginPortal+'</td>' +
                            '</tr>');
                    }
                }
                else {
                    $('#trDataUser').append('<tr>' +
                        '<td colspan="3">-- Data Not Yet --</td>' +
                        '</tr>');
                }
            });
        }
        else {
            $('#trDataUser').empty();
            $('#trDataUser').append('<tr>' +
                '<td colspan="3">-- Data Not Yet --</td>' +
                '</tr>');
        }
    });

    $(document).on('click','#useLogOut',function () {
        $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Log Me Out </b><hr/> ' +
            '<button type="button" class="btn btn-primary btnActionLogOut" style="margin-right: 5px;">Yes</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
            '</div>');
        $('#NotificationModal').modal('show');
    });

    $(document).on('click','.btnLoginPortalStudents',function () {

        var NPM = $(this).attr('data-npm');

        var token = jwt_encode({NPM:NPM,NIP:sessionNIP},'s3Cr3T-G4N');

        var url = base_url_portal_students+'auth/loginFromAkademik?token='+token;
        PopupCenter(url,'xtf','1300','500');

    });

    $(document).on('click','#btnLoginPortal,.btnLoginPortal',function () {

        var username = $(this).attr('data-nip');
        var password = $(this).attr('data-password');

        var token = jwt_encode({username:username,password:password,NIP:sessionNIP},'s3Cr3T-G4N');

        var url = base_url_portal_lecturers+'auth/loginFromAkademik?token='+token;
        PopupCenter(url,'xtf','1300','500');

    });

    $(document).on('click','.NotificationLinkRead',function () {
        var ID_logging_user = $(this).attr('id_logging_user');
        var url = base_url_js+'api/__crudLog';
        var data = {
            action : 'readLogUser',
            UserID : sessionNIP,
            ID_logging_user : ID_logging_user,
        };
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {

        });
    });

    $(document).on('click','.ViewAllLogNotification',function () {
        window.location.href = base_url_js+'ShowLoggingNotification';
    });

    $(document).on('click','.btnFinalProject_ViewDetailMK',function () {

        var token = $(this).attr('data-token');
        var title = $(this).attr('data-title');
        var dataToken = jwt_decode(token,'UAP)(*');

        var tr = '';
        if(dataToken.length>0){
            $.each(dataToken,function (i,v) {

                var mkt = (v.MKType=='1') ? '<br/><span class="label label-primary">Required</span>' : '';

                tr = tr + '<tr>' +
                    '<td style="border-right: 1px solid #CCCCCC;">'+(i+1)+'</td>' +
                    '<td>'+v.MKCode+''+mkt+'</td>' +
                    '<td style="text-align: left;"><b>'+v.Course+'</b><br/><i>'+v.CourseEng+'</i></td>' +
                    '<td>'+v.Credit+'</td>' +
                    '<td>'+v.Grade+'</td>' +
                    '</tr>'
            });
        }

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Detail Course | '+title+'</h4>');

        var htmlss = '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <table class="table table-centre table-striped">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th style="width: 17%;">MKCode</th>' +
            '                <th>Course</th>' +
            '                <th style="width: 7%;">Credit</th>' +
            '                <th style="width: 7%;">Grade</th>' +
            '            </tr>' +
            '            </thead>' +
            '           <tbody>'+tr+'</tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');


        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });


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
            },500);
        });



    });

    function notifyMe(IDUser,title,body,icon) {
        var options = {
            body: body,
            icon: icon,
            dir : "rtl"
        };

        // requireInteraction : true

        // Let's check if the browser supports notifications
        if (!("Notification" in window)) {
            alert("This browser does not support desktop notification");
        }

        // Let's check if the user is okay to get some notification
        else if (Notification.permission === "granted") {
            // If it's okay let's create a notification

            var notification = new Notification(title,options);

            notification.onclick = function() {
                window.location.href = '';
                notification.close();
            };
            // At last, if the user already denied any notification, and you
            // want to be respectful there is no need to bother them any more.


            // Update show notif 1
            var url = base_url_js+'api/__crudNotification';
            var data = {
                action : 'hideNotifBrowser',
                IDUser : IDUser
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {

            });

        }

        // Otherwise, we need to ask the user for permission
        // Note, Chrome does not implement the permission static property
        // So we have to check for NOT 'denied' instead of 'default'
        else if (Notification.permission !== 'denied') {
            Notification.requestPermission(function (permission) {
                // Whatever the user answers, we make sure we store the information
                if (!('permission' in Notification)) {
                    Notification.permission = permission;
                }

                // If the user is okay, let's create a notification
                if (permission === "granted") {
                    var notification = new Notification(title,options);
                    notification.onclick = function() {
                        window.location.href = '';
                        notification.close();
                    };
                    // At last, if the user already denied any notification, and you
                    // want to be respectful there is no need to bother them any more.


                    // Update show notif 1
                    var url = base_url_js+'api/__crudNotification';
                    var data = {
                        action : 'hideNotifBrowser',
                        IDUser : IDUser
                    };
                    var token = jwt_encode(data,'UAP)(*');
                    $.post(url,{token:token},function (jsonResult) {

                    });
                }
            });


        }



    }

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

    function socket_messages(){

        socket.on( 'update_notifikasi', function( data ) {
            if (data.update_notifikasi == 1) {
                // action
                // showHTMLMessagesDivision();
            }

        }); // exit socket

        // socket.on( 'update_log', function( data ) {

        //     if (data.update_log == 1) {
        //         showUnreadLog();
        //     }

        // }); // exit socket
    }

    function showUnreadLog() {
        var url = base_url_js+'api/__crudLog';
        var data = {
            action : 'getTotalUnreadLog',
            UserID : sessionNIP
        };
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {
            $('.totalUnreadLog').html(jsonResult);
        });
    }

    function showLog() {
        var url = base_url_js+'api/__crudLog';
        var data = {
            action : 'readLog',
            UserID : sessionNIP
        };
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {

            var Details = jsonResult.Details;



            if(Details.length>0){

                $('#li2ShowLog').html('<li class="title">' +
                    '                        <p>You have several logs</p>' +
                    '                    </li>');

                for(var i=0;i<Details.length;i++){
                    var d = Details[i];
                    // console.log(d);
                    var wrn_read = '';
                    if (d.StatusRead == 1) {
                        wrn_read = 'style="background-color: #eaf1fb"';
                    }
                    $('#li2ShowLog').append('<li '+wrn_read+'>' +
                    '                        <a href="'+base_url_js+''+d.URLDirect+'" id_logging_user = "'+d.ID_logging_user+'" class ="NotificationLinkRead" >' +
                    '                            <span class="photo"><img class="img-rounded img-fitter-notif" data-src="'+d.Icon+'"></span>' +
                    '                            <span class="subject"><span class="from">'+d.CreatedName+'</span></span>' +
                    '                            <span class="text">'+d.Title+'</span>' +
                    '                            <div class="time" style="position: relative;padding-left: 5px;text-align: right;"><i class="fa fa-clock-o"></i>' +
                        '                           '+moment(d.CreatedAt).format('dddd, DD MMM YYYY HH:mm:ss')+'</div>' +
                    '                        </a>' +
                    '                    </li>');
                }
                $('#li2ShowLog').append('<li class="footer">' +
                                        '<a href="javascript:void(0);" class = "ViewAllLogNotification">View all logs</a>' +
                                        '</li>');

                $('.img-fitter-notif').imgFitter({
                    // CSS background position
                    backgroundPosition: 'center center',
                    // for image loading effect
                    fadeinDelay: 400,
                    fadeinTime: 1200
                });
            }

            // socket.emit('update_log', {
            //     update_log: '1'
            // });


        });
    }

     // function testMobile() {
     //     socket.emit('mobile_notif', {
     //         Title: 'Judul Notif',
     //         Message: 'Ini bagian isi',
     //         dataUser : ['11140001']
     //     });
     // }



    function ReadNotifDivision(){
        var url = base_url_js+'readNotificationDivision';
        $.get(url,function (data_json) {
            var response = jQuery.parseJSON(data_json);
            if (response == 1) {
                // var socket = io.connect( 'http://'+window.location.hostname+':3000' );
                // socket.emit('update_notifikasi', {
                //     update_notifikasi: '1'
                // });
            }
        });
    }

    function addNotification(dataToken,dataDesc) {
        var url = base_url_js+'api/__crudNotification';

        var data = {
            action : 'addNewNotification',
            dataInsert : {
                PortalType : '1',
                Token : dataToken,
                Desc : dataDesc,
                CreatedBy : sessionNIP,
                Created : dateTimeNow()
            }
        };
        var token = jwt_encode(data,'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            ReadNotifDivision();
        });
    }

    function showHTMLMessagesDivision(){
        var url = base_url_js+'api/__getNotification_divisi';
        $.post(url,function (data_json) {
            var dataa = data_json['data'];
            $("#NotificationDivisi").empty();
            var IDDivision = "<?php echo $this->session->userdata('IDdepartementNavigation') ?>";
            var htmla = '<a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick = "ReadNotifDivision('+IDDivision+')">'+
                '<i class="fa fa-bell"></i>'+
                '<span class="badge">'+data_json['count']+'</span>'+
                '</a>';

            if (data_json['data'].length > 0) {
                htmla += '<ul class="dropdown-menu extended notificationdivisi" style="max-width: 400px;width: 400px;">'+
                    '<li class="title">'+
                    '<p>You have '+data_json['count']+' new messages to Division</p>'+
                    '</li>';

                for (var i = 0; i < dataa.length; i++) {

                    var d = dataa[i];
                    var token = jwt_decode(d['Token'],"UAP)(*");

                    var iconNotif = (token['Icon']!='' && token['Icon']!=null && typeof token['Icon'] !== "undefined")
                        ? '<img data-src="'+token['Icon']+'" class="img-fitter-notif" />'
                        : '<img src="'+base_url_js+'images/xx.jpg" class="" />';

                    // 9 = Finance
                    if(d['Div']==9 || d['Div']=='9'){
                        htmla += '<li>'+
                            '<a href="'+'<?php echo url_pas ?>'+token['URL']+'" style="padding: 22px;">'+
                            '<span class="photo">'+iconNotif+'</span>'+
                            '<span class="subject">'+
                            '<span class="from">'+token['From']+'</span>'+
                            '</span>'+
                            '<span class="text">'+token['subject']+'</span>'+
                            '<span class="time">'+moment(data_json['data'][i]['Created']).format('dddd, DD MMM YYYY')+'</span>' +
                            '</a>'+
                            '</li>';
                    }
                    // 6 = akademik
                    else if(d['Div']==6 || d['Div']=='6'){
                        htmla += '<li>'+
                            '<a href="'+'<?php echo url_pas ?>'+token['URL']+'" style="padding: 22px;">'+
                            '<span class="photo">'+iconNotif+'</span>'+
                            '<span class="subject">'+
                            '<span class="from">'+token['From']+'</span>'+
                            '</span>'+
                            '<span class="text">'+token['Subject']+'</span>'+
                            '<span class="time">'+moment(data_json['data'][i]['Created']).format('dddd, DD MMM YYYY HH:mm:ss')+'</span>' +
                            '</a>'+
                            '</li>';
                    }

                    if(d['ShowNotif']==0 || d['ShowNotif']=='0'){
                        notifyMe(d['IDUser'],token['From'],token['Subject'],token['Icon']);
                    }

                }

                htmla +=  '<li class="footer"><a href="#">View all notification</a></li><ul>';
                $("#NotificationDivisi").append(htmla);

                $('.img-fitter-notif').imgFitter({
                    // CSS background position
                    backgroundPosition: 'center center',
                    // for image loading effect
                    fadeinDelay: 400,
                    fadeinTime: 1200
                });
            }// exit if

        })
    }

    function wrDepartmentAdmProdi(){
        <?php
            $PositionMain = $this->session->userdata('PositionMain');
            $DivisionID = $PositionMain['IDDivision'];
         ?>
         <?php if ($this->session->userdata('IDdepartementNavigation') == 15): ?>
             var NameDiv = "<?php echo $this->session->userdata('prodi_active') ?>";
             var aa = $("#wrDepartment").text();
             aa = aa.replace('Admin','');
             $("#wrDepartment").html(aa + ' '+NameDiv);
         <?php endif ?>

         <?php if ($this->session->userdata('IDdepartementNavigation') == 34): ?>
             var NameDiv = "<?php echo $this->session->userdata('faculty_active') ?>";
             var aa = $("#wrDepartment").text();
             aa = aa.replace('Admin','');
             $("#wrDepartment").html(aa + ' '+NameDiv);
         <?php endif ?>
    }

    function Global_CantAction(element){
        // cannot delete action
          var waitForEl = function(selector, callback) {
            if (jQuery(selector).length) {
              callback();
            } else {
              setTimeout(function() {
                waitForEl(selector, callback);
              }, 100);
            }
          };

          waitForEl(element, function() {
            $(element).remove();
          });
        // end cannot delete action
    }

     function saveLogUser() {

        try {
            $.getJSON("https://api.ipify.org/?format=json", function(e) {
                // console.log(e);
                // console.log(e.ip);

                var dataURL = window.location.href;

                var url = base_url_js+'api3/__crudLogging';

                var data = {
                    action : 'insertLog',
                    dataForm : {
                        NIP : sessionNIP,
                        UserID : sessionNIP,
                        IPPublic : e.ip,
                        URL : dataURL
                    }
                };

                var token = jwt_encode(data,'UAP)(*');

                $.post(url,{token:token},function (result) {

                });

            });
        } catch (e){
            var dataURL = window.location.href;

            var url = base_url_js+'api3/__crudLogging';

            var data = {
                action : 'insertLog',
                dataForm : {
                    NIP : sessionNIP,
                    UserID : sessionNIP,
                    IPPublic : '',
                    URL : dataURL
                }
            };

            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (result) {

            });
        }




     }

</script>
