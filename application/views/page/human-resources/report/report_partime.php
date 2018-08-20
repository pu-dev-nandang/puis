<h1>Oke</h1>


<script>
    $(document).ready(function () {
        loadDataPartime();
    });

    function loadDataPartime() {

        var token = jwt_encode({action:'readPartime'},'UAP)(*');
        var url = base_url_js+'api/__crudPartime';

        $.post(url,{token:token},function (jsonResult) {

            console.log(jsonResult);

        });

    }
</script>