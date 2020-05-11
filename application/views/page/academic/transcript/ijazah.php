
<div class="row" style="margin-top: 30px;margin-bottom: 30px;">
    <div class="col-md-6 col-md-offset-3">
        <div class="well">
            <div class="row">
                <div class="col-md-6">
                    <select class="form-control filter-option" id="filterClassOf"></select>
                </div>
                <div class="col-md-6">
                    <select class="form-control filter-option" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="viewTable"></div>
    </div>
</div>

<script>

    $(document).ready(function () {
        var Year = moment().format('YYYY');
        loadSelectOptionClassOf_DESC('#filterClassOf',(parseInt(Year) - 5));
        loadSelectOptionBaseProdi('#filterBaseProdi','');

        var firstLoad = setInterval(function () {
            var filterClassOf = $('#filterClassOf').val();
            if(filterClassOf!='' && filterClassOf!=null){
                clearInterval(firstLoad);
                loadDataIjazah();
            }
        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);

    });

    $('.filter-option').change(function () {
        loadDataIjazah();
    });

    function loadDataIjazah() {

        var filterClassOf = $('#filterClassOf').val();

        if(filterClassOf!='' && filterClassOf!=null){

            var filterBaseProdi = $('#filterBaseProdi').val();

            $('#viewTable').html('<table class="table table-bordered table-centre" id="tableIjazah">' +
                '            <thead>' +
                '            <tr>' +
                '                <th style="width: 1%;">No</th>' +
                '                <th>Student</th>' +
                '                <th style="width: 17%;">Judisium Date</th>' +
                '                <th style="width: 17%;">Graduation Date</th>' +
                '                <th style="width: 17%;">Token</th>' +
                '                <th style="width: 10%;">Ijazah</th>' +
                '            </tr>' +
                '            </thead>' +
                '            <tbody></tbody>' +
                '        </table>');

            var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null)
                ? filterBaseProdi.split('.')[0] : '' ;
            var token = jwt_encode({Year:filterClassOf, ProdiID:ProdiID},'UAP)(*');

            var dataTable = $('#tableIjazah').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "NIM, Name, Programme Study"
                },
                "ajax":{
                    url : base_url_js+'api/__getIjazah', // json datasource
                    ordering : false,
                    data : {token:token},
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

</script>