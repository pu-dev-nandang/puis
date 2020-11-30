
<style>
    .lbl-1 {
        background: #2196F3;
        color: #fff;
        padding: 3px 5px 3px 5px;
        border-radius: .25em;
        font-size: 10px;
    }
    .lbl-2 {
        background: #ce56e2;
        color: #fff;
        padding: 3px 5px 3px 5px;
        border-radius: .25em;
        font-size: 10px;
    }
    .lbl-3 {
        background: #FF9800;
        color: #fff;
        padding: 3px 5px 3px 5px;
        border-radius: .25em;
        font-size: 10px;
    }
    .lbl-point {
        margin-right: 5px;
        font-size: 11px;
        color: #fff;
        padding: 1px 5px 1px 5px;
        border-radius: .25em;
    }
</style>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Action : Create</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Title</label>
                    <input class="form-control">
                </div>
                <div class="form-group">
                    <label>Note</label>
                    <textarea class="form-control" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label>Note For Student</label>
                    <textarea class="form-control" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <button id="btnAddQuestion" class="btn btn-default">Add question</button>
                </div>
            </div>
            <div class="panel-footer" style="text-align: right;">
                <button class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).on('click','.addToQuizFromMyQuestion',function () {
        var ID = $(this).attr('data-id');
    });

    $('#btnAddQuestion').click(function () {
        // GlobalModalLarge
        $('#GlobalModalLarge .modal-body').html('<div id="divTableQuestion"></div>');

        $('#divTableQuestion').html('<div class="">' +
            '            <table class="table table-centre table-bordered table-striped" id="dataTableMasterQuestion" style="width:100%!important;;">' +
            '                <thead>' +
            '                <tr style="background-color: #eceff1;">' +
            '                    <th style="width: 1%;">No</th>' +
            '                    <th>Question</th>' +
            '                    <th style="width: 5%;"><i class="fa fa-cog"></i></th>' +
            '                </tr>' +
            '                </thead>' +
            '                <tbody></tbody>' +
            '            </table>' +
            '        </div>');

        var data = {
            action : 'getMasterQuestion',
            NIP : sessionNIP,
            Portal : 'pcam'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudQuiz';

        var dataTable = $('#dataTableMasterQuestion').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Question . . ."
            },
            "responsive" : true,
            "ajax":{
                url : url, // json datasource
                ordering : false,
                data : {token:token},
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );

        $('#GlobalModalLarge').modal({
            'backdrop' : 'static',
            'show' : true
        });
    });

</script>