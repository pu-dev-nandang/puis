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
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> List Vreservation <?php echo $this->session->userdata('Name') ?></h4>
            </div>
            <div class="widget-content">
                <div class="thumbnail" style="padding: 10px;">
                    <b>Status : </b><i class="fa fa-circle" style="color:#8ED6EA;"></i> Approve 
                </div>
                <br>
                <div id="pageData">
                                
                </div>
            </div>
            <hr/>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        loadDataListApprove();
        socket_messages2();
    });

    $(document).on('click','.btn-delete', function () {
      //loading_button('.btn-edit');
      ID_tbl = $(this).attr('idtbooking');
      $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Are you sure ? </b> ' +
          '<button type="button" id="confirmYes" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
          '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>' +
          '</div>');
      $('#NotificationModal').modal('show');

      $("#confirmYes").click(function(){
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
          var url =base_url_js+'vreservation/cancel_submit';
          var data = {ID_tbl : ID_tbl};
          var token = jwt_encode(data,'UAP)(*');
          $.post(url,{token:token},function (data_json) {
            var response = jQuery.parseJSON(data_json);
            if (response == '') {
                setTimeout(function () {
                   toastr.options.fadeOut = 10000;
                   toastr.success('Data berhasil disimpan', 'Success!');
                   loadDataListApprove();
                   // send notification other school from client
                   var socket = io.connect( 'http://'+window.location.hostname+':3000' );
                   // var socket = io.connect( '<?php echo serverRoot ?>'+':3000' );
                     socket.emit('update_schedule_notifikasi', { 
                       update_schedule_notifikasi: '1',
                       date : '',
                     });
                   $('#NotificationModal').modal('hide');
                },500);
            }
            else
              {
                toastr.error(response, 'Failed!!');
                $('#NotificationModal').modal('hide');
              }
          });
      })
    });  

    function socket_messages2()
    {
        var socket = io.connect( 'http://'+window.location.hostname+':3000' );
        // var socket = io.connect( '<?php echo serverRoot ?>'+':3000' );
        socket.on( 'update_schedule_notifikasi', function( data ) {

            //$( "#new_count_message" ).html( data.new_count_message );
            //$('#notif_audio')[0].play();
            if (data.update_schedule_notifikasi == 1) {
                loadDataListApprove();
            }

        }); // exit socket
    }

    function loadDataListApprove()
    {
        $("#pageData").empty();
        loading_page('#pageData');
        var html_table ='<div class="col-md-12">'+
                         '<div class="table-responsive">'+
                            '<table class="table table-bordered table-hover table-checkable datatable">'+
                                '<thead>'+
                                    '<tr>'+
                                    // '<th style="width: 15px;">No</th>'+
                                   ' <th>Start</th>'+
                                   ' <th>End</th>'+
                                   ' <th>Time</th>'+
                                   ' <th>Agenda</th>'+
                                   ' <th>Room</th>'+
                                   ' <th>Equipment Add</th>'+
                                   ' <th>Personel Support</th>'+
                                   ' <th>Req Date</th>'+
                                   ' <th>Req Layout</th>'+
                                   ' <th>Action</th>'+
                                    '</tr>'+
                               ' </thead>'+
                                '<tbody>'+
                                '</tbody>'+
                            '</table>'+
                         '</div>'+   
                        '</div>';
        var url = base_url_js+'vreservation/json_list_booking_by_user';
        $.post(url,function (data_json) {
            setTimeout(function () {
                var response = jQuery.parseJSON(data_json);
               $("#pageData").html(html_table);
               for (var i = 0; i < response.length; i++) {
                var btn = '<span class="btn btn-xs btn-delete" idtbooking ="'+response[i]['ID']+'" >'+
                                    '<i class="fa fa-times"></i> Cancel'+
                                   '</span>';
                var Req_layout = (response[i]['Req_layout'] == '') ? 'Default' : '<a href="javascript:void(0)" class="btn-action btn-edit btn-get-link" data-page="fileGetAny/vreservation/'+response[i]['Req_layout']+'">Request Layout</a>'; 
                var tr = '<tr>';
                if (response[i]['Status'] ==  1) {
                  tr = '<tr style="background-color: #8ED6EA; color: black;">';
                }
                                 
                $(".datatable tbody").append(
                    tr+
                        '<td>'+response[i]['Start']+'</td>'+
                        '<td>'+response[i]['End']+'</td>'+
                        '<td>'+response[i]['Time']+'</td>'+
                        '<td>'+response[i]['Agenda']+'</td>'+
                        '<td>'+response[i]['Room']+'</td>'+
                        '<td>'+response[i]['Equipment_add']+'</td>'+
                        '<td>'+response[i]['Persone_add']+'</td>'+
                        '<td>'+response[i]['Req_date']+'</td>'+
                        '<td>'+Req_layout+'</td>'+
                        '<td>'+btn+'</td>'+
                    '</tr>' 
                    );
            }
            LoaddataTable('.datatable');
            },500);
        });
    }
</script>