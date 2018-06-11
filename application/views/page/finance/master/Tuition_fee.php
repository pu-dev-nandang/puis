<!--coba-->
<div class="row" style="margin-top: 30px;">
    <div class="col-md-4 col-md-offset-4">
        <div class="thumbnail" style="min-height: 30px;padding: 10px;">
            <select class="form-control" id="selectCurriculum">
                <option selected disabled>--- Curriculum ---</option>
                <option disabled>------</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr/>
        <table class="table table-bordered">
            <thead>
            <tr style="background: #333;color: #fff;">
                <th style="">Program Study</th>
                <th style="width: 10%;">SPP</th>
                <th style="width: 10%;">BPP</th>
                <th style="width: 10%;">Credit</th>
                <th style="width: 10%;">Another</th>
            </tr>
            </thead>
            <tbody id="dataRow"></tbody>
        </table>
    </div>
</div>


<script>
    $(document).ready(function () {
        loadSelectOptionCurriculum('#selectCurriculum','');
    });

    $('#selectCurriculum').change(function () {
        loadData();
    });

    function loadData() {
        var CDID = $('#selectCurriculum').val();
        if(CDID!='' && CDID!=null){
            var exp = CDID.split('.');
            var url = base_url_js+'api/__crudTuitionFee';
            var data = {
                action : 'read',
                ClassOf : exp[1].trim()
            };
            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (resultJson) {
               console.log(resultJson);
                $('#dataRow').html('');
               for(var i=0;i<resultJson.length;i++){
                   var dataProdi = resultJson[i];
                   $('#dataRow').append('<tr>' +
                       '<td>'+dataProdi.ProdiName+'</td>' +
                       '<td>'+formatRupiah(parseInt(dataProdi.Detail[0].Cost))+'</td>' +
                       '<td>'+formatRupiah(parseInt(dataProdi.Detail[1].Cost))+'</td>' +
                       '<td>'+formatRupiah(parseInt(dataProdi.Detail[2].Cost))+'</td>' +
                       '<td>'+formatRupiah(parseInt(dataProdi.Detail[3].Cost))+'</td>' +
                       '</tr>');
               }

            });
        }
    }
</script>