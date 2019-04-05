
<style>
    #tableShowAnnc thead tr {
        background: #437e88;
        color: #ffffff;
    }
    #tableShowAnnc thead th {
        text-align: center;
    }
    .detail-message {
        max-height: 100px;
        overflow: auto;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <hr/>
        <div id="divAnnouncement"></div>
    </div>
</div>

<script>

    $(document).ready(function () {
       loadDataAnnouncement();
    });

    function loadDataAnnouncement() {

        $('#divAnnouncement').html('<div class="">' +
            '                <table class="table table-bordered table-striped" id="tableShowAnnc">' +
            '                    <thead>' +
            '                    <tr>' +
            '                        <th style="width: 1%;">No</th>' +
            '                        <th style="width: 20%;">Title</th>' +
            '                        <th>Message</th>' +
            '                        <th style="width: 10%;">To</th>' +
            '                        <th style="width: 5%;">File</th>' +
            '                        <th style="width: 15%;">Publish Date</th>' +
            '                        <th style="width: 5%;"><i class="fa fa-cog"></i></th>' +
            '                        <th style="width: 11%;">Created By</th>' +
            '                    </tr>' +
            '                    </thead>' +
            '                    <tbody id="trExam"></tbody>' +
            '                </table>' +
            '            </div>');

        var  token ='';
        var dataTable = $('#tableShowAnnc').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Title, Message"
            },
            "ajax":{
                //url : base_url_js+"api2/__getAnnouncement", // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );

    }

    $(document).on('click','.showUser',function () {

        var token = $(this).attr('data-token');
        var usr = ($(this).attr('data-user')=='std') ? 'NIM' : 'NIP' ;

        var dataUser = jwt_decode(token,'UAP)(*');

        $('#NotificationModal .modal-header').addClass('hide');
        $('#NotificationModal .modal-body').html('<div class="row">' +
            '    <div class="col-md-12">' +
            '        <table class="table table-striped">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>User</th>' +
            '                <th style="width: 25%;">Status</th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody id="showUsr">' +
            '            </tbody>' +
            '        </table>' +
            '       <div style="text-align: right;"><button class="btn btn-default" data-dismiss="modal">Close</button></div>' +
            '    </div>' +
            '</div>');

        var no = 1;
        $.each(dataUser,function (i,v) {

            var usrID = (typeof v.NPM !== 'undefined') ? v.NPM : v.NIP ;

            var Status = '<span style="color: orangered;">Unread</span>';
            if(v.Read==1 || v.Read=='1'){
                Status = '<span style="color: blue;"><i class="fa fa-check"></i> Read</span>';
            } else if(v.Read==2 || v.Read=='2'){
                Status = '<span style="color: green;"><i class="fa fa-bookmark"></i> Saved</span>';
            }

            $('#showUsr').append('<tr>' +
                '<td style="text-align: center;">'+no+'</td>' +
                '<td>'+v.Name+'<br/><span style="font-size: 11px;">'+usrID+'</span></td>' +
                '<td style="text-align: right;">'+Status+'</td>' +
                '</tr>');
            no++;
        });

        $('#NotificationModal .modal-footer').addClass('hide');
        $('#NotificationModal').modal({
            'backdrop' : 'static',
            'show' : true
        });
    })

</script>