
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Timetable</h4>
        </div>
        <div class="panel-body">

            <b>Krs Online </b>
            <div id="viewDateKRS"></div>
            <div class="checkbox">
                <label>
                    <input type="checkbox"> Open edit timetable
                </label>
            </div>

            <hr/>

        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        loadDateKRSOnline();
    });

    function loadDateKRSOnline() {

        var data = {
            action : 'getDateKRSOnline'
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudKurikulum';

        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                var d = jsonResult[0];

                var Start = (d.krsStart!='' && d.krsStart!=null) ? moment(d.krsStart).format('DD MMM YYYY') : '';
                var End = (d.krsEnd!='' && d.krsEnd!=null) ? moment(d.krsEnd).format('DD MMM YYYY') : '';
                $('#viewDateKRS').html(Start+' - '+End);
            }

        });

    }

</script>