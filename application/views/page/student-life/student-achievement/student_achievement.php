
<style>
    #dataTableAc tr th, #dataTableAc tr td {
        text-align: center;
    }
    .tableStd tr th, .tableStd tr td {
        text-align: center;
    }
    .tableStd tr td:first-child {
        text-align: left;
    }
</style>

<div class="row">

    <div class="col-md-12">
        <div class="" style="min-height: 300px;">
            <table class="table table-striped" id="dataTableAc">
                <thead>
                <tr>
                    <th style="width: 1%;">No</th>
                    <th>Event Name</th>
                    <th style="width: 15%;">Event Date</th>
                    <th style="width: 5%;">Level</th>
                    <th style="width: 5%;">Type</th>
                    <th style="width: 10%;">Achievement</th>
                    <th style="width: 5%;"><i class="fa fa-cog"></i></th>
                    <th style="width: 25%;">Member</th>
                </tr>
                </thead>
                <tbody id="dataStdList"></tbody>
            </table>
        </div>
    </div>

</div>

<script>

    $(document).ready(function () {
        loadDataachievement();

    });



    function loadDataachievement() {
        var data = {
            action : 'viewDataPAM'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB5';

        $.post(url,{token:token},function (jsonResult) {

            $('#dataStdList').empty();

            if(jsonResult.length>0){

                $.each(jsonResult,function (i,v) {

                    var StartDate = (v.StartDate!='' && v.StartDate!=null) ? moment(v.StartDate).format('ddd, DD MMM YYYY') : '';
                    var EndDate = (v.EndDate!='' && v.EndDate!=null) ? moment(v.EndDate).format('ddd, DD MMM YYYY') : '';

                    var member = '';
                    if(v.DataStudent.length>0){
                        $.each(v.DataStudent,function (i2,v2) {
                            member = member+'<div> - '+ucwords(v2.Name)+' ('+v2.NPM+')</div>';
                        });
                    }

                    var btnAct = '<div class="btn-group">' +
                        '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        '    <i class="fa fa-edit"></i> <span class="caret"></span>' +
                        '  </button>' +
                        '  <ul class="dropdown-menu">' +
                        '    <li><a href="'+base_url_js+'student-life/student-achievement/update-data-achievement?id='+v.ID+'">Edit</a></li>' +
                        '    <li role="separator" class="divider"></li>' +
                        '    <li><a href="javascript:void(0);" class="actRemove" data-id="'+v.ID+'">Remove</a></li>' +
                        '  </ul>' +
                        '</div>';

                    var lbl = (v.Type=='1' || v.Type==1)
                        ? '<span class="label label-success">Academic</span>'
                        : '<span class="label label-default">Non Academic</span>';

                    var viewEvent = '<a><b>'+v.Event+'</b></a>';

                    $('#dataStdList').append('<tr>' +
                        '<td style="border-right: 1px solid #ccc;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+viewEvent+'</td>' +
                        '<td>'+StartDate+'<br/>'+EndDate+'</td>' +
                        '<td>'+v.Level+'</td>' +
                        '<td>'+lbl+'</td>' +
                        '<td style="border-right: 1px solid #ccc;">'+v.Achievement+'</td>' +
                        '<td>'+btnAct+'</td>' +
                        '<td style="text-align: left;">'+member+'</td>' +
                        '</tr>');

                });

            } else {
                var tds = $('#dataTableAc').children('thead').children('tr').children('th').length;
                $('#dataStdList').html('<tr><td colspan="'+tds+'">Data Not Yet</td></tr>');
            }

        });

    }

    $(document).on('click','.actRemove',function () {

        if(confirm('Are you sure?')){
            loading_modal_show();

            var ID = $(this).attr('data-id');

            var data = {
                action : 'removePAM',
                ID : ID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB5';

            $.post(url,{token:token},function (result) {
                toastr.success('Data removed','Success');
                setTimeout(function () {
                    loadDataachievement();
                    loading_modal_hide();
                },500);
            });
        }



    });

</script>