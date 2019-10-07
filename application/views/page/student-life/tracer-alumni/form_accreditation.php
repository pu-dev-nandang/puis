

<div class="row">
    <div class="col-md-4">
        <table class="table">
            <tbody>
            <tr>
                <td colspan="3">
                    <input class="form-control" id="formSearchAlumni" placeholder="Search by Name or NIM">
                </td>
            </tr>

            <tr>
                <td style="width: 35%;">Year</td>
                <td style="width: 1%">:</td>
                <td>
                    <input class="form-control" id="Year">
                </td>
            </tr>
            <tr>
                <td colspan="3" id="viewStudent"></td>
            </tr>
            <tr>
                <td>Alumni</td>
                <td>:</td>
                <td id="viewSelectAlumni"></td>
            </tr>
            <tr>
                <td>Position</td>
                <td>:</td>
                <td>
                    <select class="form-control" id="listJob"></select>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;">
                    <button class="btn btn-success">Save</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="col-md-8" style="border-left: 1px solid #CCCCCC;">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="well">
                    <select class="form-control"></select>
                </div>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th style="width: 2%;">No</th>
                <th style="width: 20%;">Alumni</th>
                <th>Position</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<script>

    $('#formSearchAlumni').keyup(function () {
        var formSearchAlumni = $('#formSearchAlumni').val().trim();
        if(formSearchAlumni!='' && formSearchAlumni!=null){

            var url = base_url_js+'api3/__crudAlumni';
            var data = {
                action : 'searchAlumni',
                key : formSearchAlumni

            };

            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.length>0){

                    var tr = '';
                    $.each(jsonResult,function (i,v) {
                        tr = tr+'<tr>' +
                            '<td>'+(i+1)+'</td>' +
                            '<td><div id="view_'+v.NPM+'">'+ucwords(v.Name)+'</div>'+v.NPM+'</td>' +
                            '<td><button class="btn btn-sm btn-default btnShowJobs" data-npm="'+v.NPM+'"><i class="fa fa-cloud-download"></i></button></td>' +
                            '</tr>';
                    });

                    $('#viewStudent').html('<div class="well">' +
                        '                        <table class="table">' +
                        '                            <thead>' +
                        '                            <tr>' +
                        '                                <th style="width: 1%;">No</th>' +
                        '                                <th>Alumni</th>' +
                        '                                <th style="width: 7%;"><i class="fa fa-cog"></i></th>' +
                        '                            </tr>' +
                        '                            </thead>' +
                        '                            <tbody>'+tr+'</tbody>' +
                        '                        </table>' +
                        '                    </div>');


                }

            });

        }
    });

    $(document).on('click','.btnShowJobs',function () {

        var NPM = $(this).attr('data-npm');

        var url = base_url_js+'api3/__crudAlumni';
        var data = {
            action : 'jobLoadAlumni',
            NPM : NPM

        };
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {

            var Name = $('#view_'+NPM).text();
            $('#viewSelectAlumni').html(Name+'<br/>'+NPM);
            $('#listJob').empty();

            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    $('#listJob').append('<option value="'+v.ID+'">'+v.Title+'</option>');

                });
            }

        });


    });

</script>