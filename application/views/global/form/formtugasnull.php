<script>
    $(document).ready(function () {
        //alert('Maaf Surat Tugas Keluar hanya untuk Dosen !');

        $('#NotificationModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"> '+
                                ' <span aria-hidden="true">&times;</span></button> '+
                                ' <h4 class="modal-title">Informasi</h4>');
        $('#NotificationModal .modal-body').html('<center><p><b>Maaf Anda tidak punya akses untuk Permintaan Surat Tugas Keluar dan sementara cuma bisa untuk Dosen !</b></p>'+
            '<div class="btn-group"><button class="btn btn-sm btn-danger btn-round btn-addgroup" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> Close</button></center></div></div>');

        $('#NotificationModal').modal({
                'backdrop' : 'static',
                'show' : true
        }); 

          
    });
</script>