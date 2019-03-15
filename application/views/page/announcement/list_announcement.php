
<style>
    #tableShowAnnc thead tr {
        background: #437e88;
        color: #ffffff;
    }
    #tableShowAnnc thead th {
        text-align: center;
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
            '                        <th style="width: 15%;">Range Date</th>' +
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
                "searchPlaceholder": "Day, Room, Name / NIP Invigilator"
            },
            "ajax":{
                url : base_url_js+"api2/__getAnnouncement", // json datasource
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
            '                <th>No</th>' +
            '                <th>'+usr+'</th>' +
            '                <th>Name</th>' +
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

            $('#showUsr').append('<tr>' +
                '<td>'+no+'</td>' +
                '<td>'+usrID+'</td>' +
                '<td>'+v.Name+'</td>' +
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