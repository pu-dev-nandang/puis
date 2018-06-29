


<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="thumbnail">
            <div class="row">
                <div class="col-md-6">
                    <select class="form-control" id="filterCurriculum"></select>
                </div>
                <div class="col-md-6">
                    <select class="form-control" id="filterBaseProdi"></select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr/>
        <div id="pageStudents"></div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#filterCurriculum,#filterBaseProdi').empty();
        $('#filterCurriculum').append('<option value="" disabled selected>-- Curriculum--</option>' +
            '                <option disabled>------------------------------------------</option>');
        loadSelectOptionCurriculum('#filterCurriculum','');

        $('#filterBaseProdi').append('<option value="">--- All Program Study ---</option>' +
            '<option disabled>------------------------------------------</option>');
        loadSelectOptionBaseProdi('#filterBaseProdi','');


    });

    $(document).on('change','#filterCurriculum,#filterBaseProdi',function () {
        loadPage();
    });

    $(document).on('click','.btnDetailStudent',function () {
        var ta = $(this).attr('data-ta');
        var NPM = $(this).attr('data-npm');

        // var url = base_url_js+'api/__crudeStudent';
        var url = base_url_js+'database/showStudent';
        var data = {
            action : 'read',
            formData : {
                ta : ta,
                NPM : NPM
            }
        };

        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (html) {
            // console.log(jsonResult);
            //
            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Detail Mahasiswa</h4>');
            $('#GlobalModal .modal-body').html(html);
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        });


    });

    function loadPage() {

        var filterCurriculum = $('#filterCurriculum').val();
        var filterBaseProdi = $('#filterBaseProdi').val();

        if(filterCurriculum!='' && filterCurriculum!=null
        && filterBaseProdi!='' && filterBaseProdi!=null){

            var data = {
                Year : filterCurriculum.split('.')[1],
                ProdiID : filterBaseProdi.split('.')[0]
            };

            var url = base_url_js+'database/loadPageStudents';
            $.post(url,{data:data},function (page) {
                setTimeout(function () {
                    $('#pageStudents').html(page);
                },500);
            });

        }


    }

</script>
