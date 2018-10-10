
<style>
    #tableQuestion thead tr th {
        text-align: center;
        background: #20485A;
        color: #FFFFFF;
    }
    #tableQuestion tbody tr td {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-12" style="text-align: right;margin-bottom: 10px;">
        <a href="<?php echo base_url('lpmi/lecturer-evaluation/crud-question/insert/0'); ?>" class="btn btn-default btn-default-success">Add Question</a>

    </div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tableQuestion">
                <thead>
                <tr>
                    <th rowspan="2" style="width: 1%;">No</th>
                    <th rowspan="2" style="width: 15%;">Category</th>
                    <th rowspan="2" style="width: 2%;">Order</th>
                    <th colspan="2">Question</th>
                    <th rowspan="2" style="width: 7%;">Type</th>
                    <th rowspan="2" style="width: 10%;">Action</th>
                </tr>
                <tr>
                    <th style="width: 30%;">Indonesia</th>
                    <th style="width: 30%;">English</th>
                </tr>
                </thead>
                <tbody id="trData"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadTable();
    });
    
    function loadTable() {

        var url = base_url_js+'api/__crudLecturerEvaluation';
        var token = jwt_encode({action:'readQuestion'},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
           // console.log(jsonResult);
            $('#trData').empty();
           if(jsonResult.length>0){
               var no = 1;
               for(var i=0;i<jsonResult.length;i++){
                   var d = jsonResult[i];

                   var qEng = (d.QuestionEng!='' && d.QuestionEng!=null) ? d.QuestionEng : '';
                   var q = (d.Question!='' && d.Question!=null) ? d.Question : '';
                   var category = (d.Category!='' && d.Category!=null) ? d.Category : '';
                   var type = (d.Type=='0') ? '<span class="label label-info"><b>Multiple</b></span>' : '<span class="label label-warning"><b>Essay</b></span>';

                   $('#trData').append('<tr>' +
                       '<td>'+(no++)+'</td>' +
                       '<td style="text-align: left;">'+category+'</td>' +
                       '<td>'+d.Order+'</td>' +
                       '<td style="text-align: left;">'+q+'</td>' +
                       '<td style="text-align: left;">'+qEng+'</td>' +
                       '<td>'+type+'</td>' +
                       '<td><div class="btn-group" role="group" aria-label="...">' +
                       '  <a href="'+base_url_js+'lpmi/lecturer-evaluation/crud-question/edit/'+d.ID+'" type="button" class="btn btn-default btn-sm"><i class="fa fa-pencil-square-o"></i></a>' +
                       '  <button type="button" class="btn btn-danger btn-sm btnDeleteQuestion" data-id="'+d.ID+'"><i class="fa fa-trash"></i></button>' +
                       '</div></td>' +
                       '</tr>');
               }
           } else {
               $('#trData').append('<tr>' +
                   '<td colspan="7">Data empty</td>' +
                   '</tr>');
           }
        });

    }

    $(document).on('click','.btnDeleteQuestion',function () {
        if(confirm('Delete data?')){

            var ID = $(this).attr('data-id');
            loading_buttonSm('.btnDeleteQuestion[data-id='+ID+']');
            var token = jwt_encode({action:'deleteQuestion',ID:ID},'UAP)(*');
            var url = base_url_js+'api/__crudLecturerEvaluation';

            $.post(url,{token:token},function (result) {
               toastr.success('Data deleted','Success');
               setTimeout(function () {
                   loadTable();
               },500);
            });
        }

    });
</script>