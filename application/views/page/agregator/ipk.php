

<div class="well">

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <select class="form-control" id="filterYear"> </select>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-12">
             <div id="viewTable"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadIPK();
        selectyearstudy();
    });
    
    function loadIPK() {

        var thisYear = (new Date()).getFullYear();
        var startTahun = parseInt(thisYear) - parseInt(3);
        var selisih =  parseInt(thisYear) - parseInt(startTahun);

        var arr_years =[];
        for (var i = 0; i < 3; i++) {
            var y = parseInt(thisYear) - parseInt(i);
            arr_years.push(y); 
        }

        var thYear = '';
        for (var i = 0; i < arr_years.length; i++) {
            thYear += '<th>'+arr_years[i]+'</th>';
        }
        
         $('#viewTable').html(' <table class="table" id="dataTablesPAM">' +
            '                <thead>' +
            '                <tr>    ' +
            '                    <th colspan="2" style="border-right: 1px solid #ccc;"></th> ' +
             '                   <th style="border-right: 1px solid #ccc; text-align: center"> Jumlah PS </th> ' +
            '                    <th colspan="3" style="border-right: 1px solid #ccc; text-align: center">Jumlah Lulusan pada</th> ' +
            '                    <th colspan="3" style="border-right: 1px solid #ccc; text-align: center">Rata-rata IPK Lulusan pada</th>  ' +
            '                    <th style="border-right: 1px solid #ccc;"></th>  ' +
            '                </tr>  ' +
            '                <tr>' +
            '                    <th style="width: 1%;">No</th>' +
            '                    <th>Program Pendidikan </th>' +
            '                    <th></th>' +
                                thYear+ 
                                thYear+
            //'                    <th style="width: 10%;"><i class="fa fa-cog"></i></th>' +
            '                </tr>' +
            '                </thead>' +
            '                <tbody id="listData"></tbody>' +
            '            </table>');

        var filterYear = $('#filterYear').val();

        if(filterYear!='' && filterYear!=null){

            var data = {
                action : 'viewIPK',
                Year : filterYear
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB5';

            $.post(url,{token:token},function (jsonResult) {



            });
        }    
    }

    function selectyearstudy() {

        var url = base_url_js+'api3/__crudAgregatorTB5';
        var token = jwt_encode({action : 'yearstudy'},'UAP)(*');

        $.post(url,{token:token},function (jsonResult) {
            $('#filterYear').append('<option disabled selected></option>');
                for(var i=0;i<jsonResult.length;i++){
                   $('#filterYear').append('<option id="'+jsonResult[i].Year+'"> '+jsonResult[i].Year+' </option>');
                }
            });
      }
</script>