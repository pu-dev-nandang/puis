

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="well">
            <div class="row">
                <div class="col-md-4">
                    <label>To</label>
                    <select class="form-control filter-eula" id="filterTo">
                        <option value="">All</option>
                        <option disabled>---------</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label>Publication Date</label>
                    <select class="form-control filter-eula" id="filterPublication">
                        <option value="">All Date</option>
                        <option disabled>--------------</option>
                    </select>
                </div>
            </div>
            <div id="loadTitleEULA"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" id="loadTable"></div>
</div>

<script>

    $(document).ready(function () {
        loadSelectOptionEULAPUblicationTo('#filterTo');
        loadSelectOptionEULAPUblicationDate('#filterPublication');
        loadDataLog();
    });
    
    $('#filterTo').change(function () {
        var filterTo = $('#filterTo').val();
        $('#loadTitleEULA').html('');
        $('#filterPublication').html('<option value="">All Date</option>' +
            '                        <option disabled>--------------</option>');
        loadSelectOptionEULAPUblicationDate('#filterPublication','',filterTo);

    });

    $('#filterPublication').change(function () {

        var filterPublication = $('#filterPublication').val();

        if(filterPublication!='' && filterPublication!=null) {
            $('#loadTitleEULA').html('<div class="row" style="margin-top: 15px;">' +
                '                <div class="col-md-12">' +
                '                    <label>Eula Title</label>' +
                '                    <select class="form-control filter-eula" id="filterTitleEULA">' +
                '                       <option value="">All Title</option>' +
                '                       <option disabled>--------</option>' +
                '                    </select>' +
                '                </div>' +
                '            </div>');

            loadSelectOptionEULATitle('#filterTitleEULA','',filterPublication);
        } else {
            $('#loadTitleEULA').html('');
        }

    });

    $(document).on('change','.filter-eula',function () {
        loadDataLog();
    });

    function loadDataLog() {

        $('#loadTable').html('<table id="tableData" class="table table-bordered table-striped table-centre">' +
            '            <thead>' +
            '            <tr style="background: #eceff1;">' +
            '                <th style="width: 1%;">No</th>' +
            '                <th style="width: 15%;">User</th>' +
            '                <th style="width: 10%;">Publication Date</th>' +
            '                <th style="width: 7%;">To</th>' +
            '                <th>Eula Title</th>' +
            '                <th style="width: 10%;">Entred At</th>' +
            '            </tr>' +
            '            </thead>' +
            '        </table>');

        var To = $('#filterTo').val();
        var Publication = $('#filterPublication').val();
        var TitleEULA = $('#filterTitleEULA').val();

        var data = {
            action : 'getLogEULA',
            To : (To!='' && To!=null) ? To : '',
            EDID : (Publication!='' && Publication!=null) ? Publication : '',
            EID : (TitleEULA!='' && TitleEULA!=null) ? TitleEULA : ''
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudEula';

        var dataTable = $('#tableData').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Title, Description"
            },
            "ajax":{
                url :url, // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    loading_modal_hide();
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        } );

    }


</script>