

<div class="row">

    <div class="col-md-12">
        <table class="table table-bordered table-centre">
            <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Child</th>
                <th>Route</th>
                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody id="listMenu"></tbody>
        </table>
    </div>

</div>


<script>

    $(document).ready(function () {
        loadDataShareMenu();
    });

    function loadDataShareMenu() {

        var data = {
          action : 'getShareMenu',
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'apimenu/__crudShareMenu';

        $('#listMenu').empty();

        $.post(url,{token : token},function (jsonResult) {

            $.each(jsonResult,function (i,v) {

                var tdChild = (v.Child.length>0) ? '' : '';

                $('#listMenu').append('<tr>' +
                    '<td>'+(i + 1)+'</td>' +
                    '<td style="text-align: left;">'+v.Icon+' '+v.Name+'</td>' +
                    '<td></td>' +
                    '<td></td>' +
                    '<td></td>' +
                    '</tr>');



            });

            console.log(jsonResult);

        });


    }

</script>