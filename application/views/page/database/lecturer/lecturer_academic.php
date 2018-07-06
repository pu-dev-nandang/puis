
<table>

</table>

<script>

    $(document).ready(function () {
        loadData();
    });
    function loadData() {
        var url = base_url_js+'api/__crudLecturer';
        var NIP = '<?php echo $NIP; ?>';
        var token = jwt_encode({action:'read',NIP:NIP},'UAP)(*');

        $.post(url,{token:token},function (resultJson) {
            console.log(resultJson);
        })
    }
</script>