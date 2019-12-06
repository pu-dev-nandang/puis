
<div class="container">
    <div class="row">

        <div class="col-md-10 col-md-offset-1">
            <div class="well">
                <div class="row">
                    <div class="col-md-3">
                        <select class="form-control filter-table" id="filterClassOf"></select>
                    </div>
                    <div class="col-md-6">
                        <select class="form-control filter-table" id="filterBaseProdi">
                            <option value="">--- All Study Program ---</option>
                            <option disabled>------------------</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control filter-table" id="filterStatus"></select>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="row">
        <div class="col-md-12" id="viewDataTable"></div>
    </div>
</div>

<script>

    $(document).ready(function () {

        var YearNow = moment().subtract(4,'years').format('YYYY');
        loadSelectOptionClassOf_Year('#filterClassOf',parseInt(YearNow));
        loadSelectOptionBaseProdi('#filterBaseProdi','');
        loadSelectOptionStatusStudent('#filterStatus',3);

        var firstLoad = setInterval(function (args) {

            var filterClassOf = $('#filterClassOf').val();
            var filterStatus = $('#filterStatus').val();
            if(filterClassOf!='' && filterClassOf!=null && filterStatus!='' && filterStatus!=null){
                loadDataStudent();
                clearInterval(firstLoad);
            }

        },1000);

    });

    $('.filter-table').change(function () {
        loadDataStudent();
    });

    function loadDataStudent(){

        var filterClassOf = $('#filterClassOf').val();
        var filterStatus = $('#filterStatus').val();
        if(filterClassOf!='' && filterClassOf!=null && filterStatus!='' && filterStatus!=null){

            $('#viewDataTable').html(' <table class="table table-centre table-striped table-bordered" id="tableData">' +
                '                <thead>' +
                '                <tr>' +
                '                    <th style="width: 1%;">No</th>' +
                '                    <th style="width: 25%;">Student</th>' +
                '                    <th>Study Program</th>' +
                '                    <th style="width: 15%;">Status</th>' +
                '                    <th style="width: 7%;">SKPI</th>' +
                '                </tr>' +
                '                </thead>' +
                '            </table>');

            var filterBaseProdi = $('#filterBaseProdi').val();

            var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null) 
                ? filterBaseProdi.split('.')[0] : '';

            var url = base_url_js+'api/__crudConfigSKPI';
            var data = {
                action : 'readStudentFromSKPI',
                ClassOf : filterClassOf,
                StatusStudentID : filterStatus,
                ProdiID : ProdiID
            };
            
            var token = jwt_encode(data,'UAP)(*');

            var dataTable = $('#tableData').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "NIM, Name"
                },
                "ajax":{
                    url : url, // json datasource
                    data : {token:token},
                    ordering : false,
                    type: "post",  // method  , by default get
                    error: function(){  // error handling
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
                    }
                }
            });

        }

    }

    $(document).on('click','.btnDownloadSKPI',function () {
       var _href = $(this).attr('data-href');
       var NPM = $(this).attr('data-npm');
       var url = base_url_files+'__setSKPIQRCode';
       var token = jwt_encode({
           data : 'https://uap.ac.id/ds/'+NPM
       },'UAP)(*');

        $.post(url,{token:token},function (jseonResult) {
            window.open(_href);
       });

    });

</script>