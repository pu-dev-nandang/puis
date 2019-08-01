

<div class="well">

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <select class="form-control" id="filterYear">
                <option value="2018">2018</option>
                <option value="2017">2017</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
        loadIPK();
    });
    
    function loadIPK() {

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
</script>