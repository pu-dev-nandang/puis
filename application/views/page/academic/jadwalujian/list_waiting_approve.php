
<style>
    #tableWaiting tr th {
        text-align: center;
        background: #885043;
        color: #ffffff;
    }
</style>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="well">
            <div class="row">
                <div class="col-xs-4">
                    <select class="form-control form-filter-list-waiting-approval" id="filterSemester"></select>
                </div>
                <div class="col-xs-6">
                    <select class="form-control form-filter-list-waiting-approval" id="filterBaseProdi"></select>
                </div>
                <div class="col-xs-2">
                    <select class="form-control form-filter-list-waiting-approval" id="filterType">
                        <option value="uts">UTS</option>
                        <option value="uas">UAS</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="divLoadDataWA"></div>
    </div>
</div>



<script>
    $(document).ready(function () {

        loSelectOptionSemester('#filterSemester','');
        $('#filterBaseProdi').append('<option value="">-- All Programme Study --</option>' +
            '<option disabled>------------------------------------------</option>');
        loadSelectOptionBaseProdi('#filterBaseProdi','');

        var firstLoadPage = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            if(filterSemester!='' && filterSemester!=null){
                loadTableWaitingApproval();
                clearInterval(firstLoadPage);
            }
        },1000);

    });

    $('.form-filter-list-waiting-approval').change(function () {
        loadTableWaitingApproval();
    });

    function loadTableWaitingApproval() {
        var filterSemester = $('#filterSemester').val();
        var filterBaseProdi = $('#filterBaseProdi').val();
        var filterType = $('#filterType').val();

        if(filterSemester !='' && filterSemester!=null
                && filterType !='' && filterType!=null){

            loading_page('#divLoadDataWA');

            setTimeout(function () {
                $('#divLoadDataWA').html('<div>' +
                    '            <table class="table table-bordered" id="tableWaiting">' +
                    '                <thead>' +
                    '                <tr>' +
                    '                    <th>Course</th>' +
                    '                    <th style="width: 5%;">Student</th>' +
                    '                    <th style="width: 5%;">Action</th>' +
                    '                    <th style="width: 20%;">Day, Date</th>' +
                    '                    <th style="width: 10%;">Time</th>' +
                    '                    <th style="width: 20%;">Insert by</th>' +
                    '                </tr>' +
                    '                </thead>' +
                    '                <table id=""></table>' +
                    '            </table>' +
                    '        </div>');


                var prodiID = (filterBaseProdi!='') ? filterBaseProdi.split('.')[0] : '' ;

                var data = {
                    SemesterID : filterSemester.split('.')[0],
                    ProdiID : prodiID,
                    Type : filterType
                };

                var token = jwt_encode(data,'UAP)(*');

                var dataTable = $('#tableWaiting').DataTable( {
                    "processing": true,
                    "serverSide": true,
                    "iDisplayLength" : 10,
                    "ordering" : false,
                    "language": {
                        "searchPlaceholder": "Day, Room, Name / NIP Pengawas"
                    },
                    "ajax":{
                        url : base_url_js+"api/__getScheduleExamWaitingApproval?token="+token, // json datasource
                        ordering : false,
                        type: "post",  // method  , by default get
                        error: function(){  // error handling
                            $(".employee-grid-error").html("");
                            $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                            $("#employee-grid_processing").css("display","none");
                        }
                    }
                } );
            },500);

        }

    }
</script>