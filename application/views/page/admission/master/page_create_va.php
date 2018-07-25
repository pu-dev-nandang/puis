<style>
    .row-sma {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .form-time {
        padding-left: 0px;
        padding-right: 0px;
    }
    .row-sma .fa-plus-circle {
        color: green;
    }
    .row-sma .fa-minus-circle {
        color: red;
    }
    .btn-action {

        text-align: right;
    }

    #tableDetailTahun thead th {
        text-align: center;
    }

    .form-filter {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #ccc;
    }
    .filter-time {
        padding-left: 0px;
    }
</style>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i> Create VA Register Online</h4>
            </div>
            <div class="widget-content">
                <!--  -->
                <div class="row row-sma">
                    <label class="col-xs-2 control-label">Jumlah Alokasi VA</label>
                    <div class="col-xs-9">
                        <div class="row">
                            <div class="col-xs-4">
                                <select class="select2-select-00 col-md-4 full-width-fix" id="selectJMLVA">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <button class="btn btn-inverse btn-notification btn-add" id="generate">Generate VA</button>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div id="pageData">

                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        loadJMLVa();
        loadTbl_VA(loadVA_available);
    });

    function loadTbl_VA(callback)
    {
        // Some code
        // console.log('test');
        $("#pageData").empty();
        var table = '<table class="table table-striped table-bordered table-hover table-checkable datatable">'+
            '<caption>VA Registration Available</caption>'+
            '<thead>'+
            '<tr>'+
            '<th style="width: 106px;">NO</th>'+
            '<th style="width: 15px;">VA Number</th>'+
            '<th style="width: 15px;">Status</th>'+
            '<th style="width: 15px;">Created</th>'+
            '</tr>'+
            '</thead>'+
            '<tbody>'+
            '</tbody>'+
            '</table>';
        //$("#loadtableNow").empty();
        $("#pageData").html(table);

        /*if (typeof callback === 'function') { 
            callback(); 
        }*/
        callback();
    }

    $(document).on('click','#generate', function () {
        var selectJMLVA = $("#selectJMLVA").val();
        processGenerate(selectJMLVA);
    });

    function processGenerate(selectJMLVA)
    {
        loading_button('#generate');
        var url = base_url_js+'admission/master-registration/generate_va';
        var data = {
            selectJMLVA : selectJMLVA
        };
        var token = jwt_encode(data,"UAP)(*");
        $.post(url,{token:token},function (data_json) {
            setTimeout(function () {
                loadTbl_VA(loadVA_available);
                $('#generate').prop('disabled',false).html('Generate VA');
            },1000);
        });
    }

    function loadJMLVa()
    {
        for (var i = 10; i <= 1000; i= i + 10) {
            var selected = (i==10) ? 'selected' : '';
            $('#selectJMLVA').append('<option value="'+ i +'" '+selected+'>'+i+'</option>');
        }
        $('#selectJMLVA').select2({
            // allowClear: true
        });
    }

    function loadVA_available()
    {
        // loading_page('#pageData');
        var url = base_url_js+'admission/master-registration/loadDataVA-available';
        $.post(url,function (data_json) {
            var response = jQuery.parseJSON(data_json);
            var no = 1;
            for (var i = 0; i < response.length; i++) {
                var status = '';
                $(".datatable tbody").append(
                    '<tr>'+
                    '<td>'+no+'</td>'+
                    '<td>'+response[i].VA+'</td>'+
                    '<td>'+response[i].StatusVA+'</td>'+
                    '<td>'+response[i].CreateAT+'</td>'+
                    '</tr>'
                );
                no++;
            }
        }).done(function() {
            LoaddataTableStandard('.datatable');
        });
    }
</script>