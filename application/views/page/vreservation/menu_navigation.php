<div id="sidebar" class="sidebar-fixed">
    <div id="sidebar-content">
        <!--=== Navigation ===-->
        <ul id="nav">
            <?php 
                $getData  = $this->session->userdata('menu_vreservation_grouping');
                for ($i=0; $i < count($getData); $i++) {
                    $temp = array();
                    $chkSubMenu1 = 0;
                    $Slug = '';
                    $uri = '';
                    $uriSubMenu1 = ''; 
                    $uriSubMenu2 = ''; 
                    // check data  memiliki submenu1 dan submenu2
                        #Submenu 1
                        $SubMenu1_arr = $getData[$i]['Submenu'];
                        for ($j=0; $j < count($SubMenu1_arr); $j++) {
                            $temp2 = array(); 
                            if ($SubMenu1_arr[$j]['SubMenu1']  != 'Empty') {
                                $chkSubMenu1++;
                                $temp2 = array('SubMenu1' => $SubMenu1_arr[$j]['SubMenu1']);
                            }

                            #Submenu2
                            $chkSubMenu2 = 0;
                            $SubMenu2_arr = $SubMenu1_arr[$j]['Submenu'];
                            $data = array();
                            for ($k=0; $k < count($SubMenu2_arr); $k++) { 
                                if ($SubMenu2_arr[$k]['SubMenu2'] != 'Empty') {
                                    $data[] = array(
                                        'ID' => $SubMenu2_arr[$k]['ID'],
                                        'ID_Menu' => $SubMenu2_arr[$k]['ID_Menu'],
                                        'SubMenu1' => $SubMenu2_arr[$k]['SubMenu1'],
                                        'SubMenu2' => $SubMenu2_arr[$k]['SubMenu2'],
                                        'Slug' => $SubMenu2_arr[$k]['Slug'],
                                        'Controller' => $SubMenu2_arr[$k]['Controller'],
                                        'read' => $SubMenu2_arr[$k]['read'],
                                        'write' => $SubMenu2_arr[$k]['write'],
                                        'update' => $SubMenu2_arr[$k]['update'],
                                        'delete' => $SubMenu2_arr[$k]['delete'],

                                    );
                                    $uri = $SubMenu2_arr[$k]['Slug'];
                                    $t = explode('/', $uri);
                                    $uriSubMenu1 = $t[1];
                                    $uriSubMenu2 = $t[2];
                                    $chkSubMenu2++;
                                }
                            }

                            if ($chkSubMenu2 > 0) {
                               $temp2 = $temp2 + array('CountSubMenu2' => $chkSubMenu2,'data' =>$data);
                            }
                            else
                            {
                                $data[] = array(
                                        'ID' => $SubMenu2_arr[0]['ID'],
                                        'ID_Menu' => $SubMenu2_arr[0]['ID_Menu'],
                                        'SubMenu1' => $SubMenu2_arr[0]['SubMenu1'],
                                        'SubMenu2' => $SubMenu2_arr[0]['SubMenu2'],
                                        'Slug' => $SubMenu2_arr[0]['Slug'],
                                        'Controller' => $SubMenu2_arr[0]['Controller'],
                                        'read' => $SubMenu2_arr[0]['read'],
                                        'write' => $SubMenu2_arr[0]['write'],
                                        'update' => $SubMenu2_arr[0]['update'],
                                        'delete' => $SubMenu2_arr[0]['delete'],

                                );
                                $temp2 = $temp2 + array('CountSubMenu2' => $chkSubMenu2,'data' =>$data);
                            }

                            $temp[] = $temp2;   
                            
                        }

                        $open = (count($temp) > 0) ? 'open' : '';
                    // closed php tag    
                    ?>
                    <?php $uriSubMenu1 = $SubMenu1_arr[0]['Submenu'][0]['Slug'] ?>
                    <?php $uriSubMenu1 = explode('/', $uriSubMenu1)  ?>
                    <?php $uriSubMenu1 = $uriSubMenu1[1]  ?>
                    <li segment2 = "<?php echo $uriSubMenu1 ?>" class="<?php if($this->uri->segment(2)==$uriSubMenu1){echo "current ".$open;} ?>">
                        <a href="<?php echo $a = ($open == 'open') ? '#' : $uri ?>">
                            <i class="<?php echo ($getData[$i]['Menu'] == '' || $getData[$i]['Menu'] == null) ? 'fa fa-globe' :  $getData[$i]['Icon'] ?>" aria-hidden="true"></i>
                                <?php echo $getData[$i]['Menu'] ?>
                        </a>
                        <?php if (count($temp) > 0 ): ?>
                            <ul class="sub-menu">
                                <?php for($z = 0; $z < count($temp); $z++): ?>
                                    <?php if ($temp[$z]['CountSubMenu2'] > 0): ?>
                                        <?php $uriSubMenu2 = $temp[$z]['data'][0]['Slug'] ?>
                                        <?php $uriSubMenu2 = explode('/', $temp[$z]['data'][0]['Slug'])  ?>
                                        <?php $uriSubMenu2 = $uriSubMenu2[2]  ?>
                                        <li class="<?php if($this->uri->segment(2)==$uriSubMenu1 && $this->uri->segment(3) == $uriSubMenu2 ){echo "open-default";} ?>">
                                            <a href="javascript:void(0);">
                                                <i class="icon-angle-right"></i>
                                                    <?php echo $temp[$z]['SubMenu1'] ?>
                                            </a>
                                            <ul class="sub-menu">
                                                <?php $countS2 = $temp[$z]['data'] ?>
                                                <?php for($x = 0; $x < count($countS2); $x++): ?>
                                                    <?php $Uri3 = $countS2[$x]['Slug'] ?>
                                                    <?php $t = explode('/', $Uri3)  ?>
                                                    <?php $Uri3 = $t[3]  ?>
                                                    <?php 
                                                    $URI_Slug = $countS2[$x]['Slug'];
                                                    $URI_Slug = explode('/', $URI_Slug);
                                                    $URISlug = $countS2[$x]['Slug'];
                                                    if (in_array('(:any)', $URI_Slug)) {
                                                       $a = count($URI_Slug) - 1;
                                                       $URISlug = '';
                                                       for ($ii=0; $ii < $a; $ii++) { 
                                                        $URISlug .= $URI_Slug[$ii].'/';
                                                       }
                                                       $URISlug = $URISlug.'1';
                                                    }

                                                     ?>
                                                    <li segment2 = "<?php echo $uriSubMenu1 ?>" segment3  = "<?php echo $Uri3 ?>" class="<?php if($this->uri->segment(2)==$uriSubMenu1 && $this->uri->segment(3) == $uriSubMenu2 && $this->uri->segment(4) == $Uri3 && $this->uri->segment(5) == ""){echo "current";} ?>">
                                                        <a href="<?php echo base_url($URISlug); ?>">
                                                            <i class="icon-angle-right"></i>
                                                            <?php echo $countS2[$x]['SubMenu2'] ?>
                                                        </a>
                                                    </li>
                                                <?php endfor ?>      
                                            </ul>
                                        </li>
                                    <?php else: ?>
                                        <?php $uriSubMenu2 = $temp[$z]['data'][0]['Slug'] ?>
                                        <?php $uriSubMenu2 = explode('/', $temp[$z]['data'][0]['Slug'])  ?>
                                        <?php $uriSubMenu2 = $uriSubMenu2[2]  ?>
                                        <?php 
                                        $URI_Slug = $temp[$z]['data'][0]['Slug'];
                                        $URI_Slug = explode('/', $URI_Slug);
                                        $URISlug = $temp[$z]['data'][0]['Slug'];
                                        if (in_array('(:any)', $URI_Slug)) {
                                           $a = count($URI_Slug) - 1;
                                           $URISlug = '';
                                           for ($i=0; $i < $a; $i++) { 
                                            $URISlug .= $URI_Slug[$i].'/';
                                           }
                                           $URISlug = $URISlug.'1';
                                        }

                                         ?>
                                        <li segment2 = "<?php echo $uriSubMenu1 ?>" segment3  = "<?php echo $uriSubMenu2 ?>" class="<?php if($this->uri->segment(2)==$uriSubMenu1 && $this->uri->segment(3) == $uriSubMenu2 ){echo "current";} ?>">
                                            <a href="<?php echo base_url($URISlug); ?>"> 
                                                <i class="icon-angle-right"></i>
                                                    <?php echo $temp[$z]['SubMenu1'] ?>
                                            </a>
                                        </li>                           
                                    <?php endif ?>
                                <?php endfor ?>    
                            </ul>        
                        <?php endif ?>
                    </li>            
            <?php         

                }    

             ?>
        </ul>
        <div class="sidebar-widget align-center">
            <div class="btn-group" data-toggle="buttons" id="theme-switcher">
                <label class="btn active">
                    <input type="radio" name="theme-switcher" data-theme="bright"><i class="fa fa-sun-o"></i> Bright
                </label>
                <label class="btn">
                    <input type="radio" name="theme-switcher" data-theme="dark"><i class="fa fa-moon-o"></i> Dark
                </label>
            </div>
        </div>
    </div>
    <div id="divider" class="resizeable"></div>
</div>
<!-- /Sidebar -->

<?php 
$this->m_master->checkAuth_user();

 ?>

 <script type="text/javascript">
     function loadDataSchedule(divHtml,date = '')
     {  
        divHtml.html('');
        var url = base_url_js+'vreservation/getschedule';
        if (date != '') {
            url = base_url_js+'vreservation/getschedule/'+date;
        }
        $.post(url,function (data_json) {
            var response = jQuery.parseJSON(data_json);
            divHtml.html(response);
        }).done(function() {

        })
     }

     $(document).on('click','.btn-apppove', function () {
        loading_button('.btn-apppove');   
        var id_table = $(this).attr('id_table');
         var url =base_url_js+'vreservation/approve_submit';
         var data = {ID_tbl : id_table};
         var token = jwt_encode(data,'UAP)(*');
         $.post(url,{token:token},function (data_json) {
            var response = jQuery.parseJSON(data_json);
            if (response == '') {
                setTimeout(function () {
                   toastr.options.fadeOut = 10000;
                   toastr.success('Data berhasil disimpan', 'Success!');
                   // send notification other school from client
                   var socket = io.connect( 'http://'+window.location.hostname+':3000' );
                   // var socket = io.connect( '<?php echo serverRoot ?>'+':3000' );
                     socket.emit('update_schedule_notifikasi', { 
                       update_schedule_notifikasi: '1',
                       date : '',
                     });
                   var divHtml = $("#schedule");
                   loadDataSchedule(divHtml);  
                   $('#GlobalModalLarge').modal('hide');
                },500);
            }
            else
            {
                toastr.error(response, 'Failed!!');
            }
             
         });
     })

     $(document).ready(function () {
         socket_messages();
         countApprove();
     });

     function socket_messages()
     {
         var socket = io.connect( 'http://'+window.location.hostname+':3000' );
         // var socket = io.connect( '<?php echo serverRoot ?>'+':3000' );
         socket.on( 'update_schedule_notifikasi', function( data ) {

             //$( "#new_count_message" ).html( data.new_count_message );
             //$('#notif_audio')[0].play();
             if (data.update_schedule_notifikasi == 1) {
                 // action
                 var getDate = data.date;
                 if (getDate == '') {
                    getDate = "<?php echo date('Y-m-d') ?>";
                 }
                 // $("#CaptionTBL").html('<strong>'+getDate+'</strong>');
                 var divHtml = $("#schedule");
                 loadDataSchedule(divHtml);
                 countApprove();
                 // loadDataListApprove();
             }

         }); // exit socket
     }

     function countApprove()
     {
        var url = base_url_js+'vreservation/getCountApprove';
        $.post(url,function (data_json) {
            var response = jQuery.parseJSON(data_json);
            $("#countRequest").html('<b>Total Request : <a href="javascript:void(0)" class="btn-action btn-edit btn-get-link" data-page="transaksi/approve">'+response+'</a></b>');
        }).done(function() {

        })
     }

     $(document).on('click','.btn-get-link', function () {
        var page = $(this).attr('data-page');
        window.open(base_url_js+'vreservation/'+page,'_blank');
     });

    $(document).on('click','.panel-orange', function () {
        var dataJson = $(this).attr('data');
        var room = $(this).attr('room');
        var tgl = $("#datetime_deadline1").val();
        var dtarr = dataJson.split('@@');
        var time =  dtarr[1];
        modal_generate('view','Form Booking Reservation',room,time,tgl,'',dtarr);
    });

    function modal_generate(action,title,room,time,tgl,user = '',dt = []) {
        var url = base_url_js+"vreservation/modal_form";
        var data = {
            Action : action,
            room : room,
            time : time,
            user : user,
            tgl  : tgl,
            dt : dt,
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{ token:token }, function (html) {
            $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+title+'</h4>');
            $('#GlobalModalLarge .modal-body').html(html);
            $('#GlobalModalLarge .modal-footer').html(' ');
            $('#GlobalModalLarge').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        })
    }
 </script>