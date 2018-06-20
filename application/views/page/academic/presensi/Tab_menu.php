<style>
    .form-attd[readonly] {
        cursor: cell;
        background-color: #fff;
        color: #333;
    }
</style>

<!--<h1>Menu</h1>-->
<!--<hr/>-->

<div class="col-md-12">
    <div class="row">
        <div class="col-md-4">
            <div class="">
                <label>Semester Antara</label>
                <input type="checkbox" id="formSemesterAntara" data-toggle="toggle" data-style="ios"/>
            </div>
        </div>

    </div>


    <div class="thumbnail" style="margin-top: 30px;">
        <div class="row">
            <div class="col-md-3">
                <select id="filterSemester" class="form-control filter-presensi"></select>
            </div>
            <div class="col-md-3">
                <select class="form-control filter-presensi" id="filterCombine">
                    <option value="0">Combine Class No</option>
                    <option value="1">Combine Class Yes</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterBaseProdi" class="form-control filter-presensi"></select>
            </div>
            <div class="col-md-3">
                <select id="filterGroup" class="form-control"></select>
            </div>
        </div>
    </div>


    <hr/>
</div>

<div id="divpagePresensi"></div>

<script src="<?php echo base_url('assets/custom/js/presensi.js'); ?>"></script>
<script>

    $(document).ready(function () {

        $('#filterSemester').empty();
        $('#filterSemester').append('<option value="" disabled selected>-- Year Academic--</option>' +
            '                <option disabled>------------------------------------------</option>');
        loSelectOptionSemester('#filterSemester','');

        $('#filterBaseProdi').empty();
        $('#filterBaseProdi').append('<option value="" disabled selected>-- Select Program Study --</option>' +
            '<option disabled>------------------------------------------</option>');
        loadSelectOptionBaseProdi('#filterBaseProdi','');

        var data = {
            // NIP : 2114002,
            page : 'InputPresensi',
            ScheduleID : 4
        };
        var token = jwt_encode(data,'UAP)(*');
        loadPagePresensi(token);

    });

    $(document).on('change','.filter-presensi',function () {
        loadFilterPresensi();
    });

    $('#filterGroup').change(function () {
        var filterGroup = $(this).val();


    });



    function loadPagePresensi(token) {
        var url = base_url_js+'academic/loadPagePresensi';

        loading_page('#divpagePresensi');
        $.post(url,{token:token},function (html) {
            setTimeout(function () {
                $('#divpagePresensi').html(html);
            },500)
        });
    }
    
    function loadFilterPresensi() {
        var filterSemester = $('#filterSemester').val();
        var filterCombine = $('#filterCombine').val();
        var filterBaseProdi = $('#filterBaseProdi').val();

        if(filterSemester!=null && filterSemester!=''){
            var exp_fSemester = filterSemester.split('.');
            var SemesterID = exp_fSemester[0];
            var ProdiID = '';
            var ds = (filterCombine=='1') ? true : false;
            if(filterCombine=='0' && filterBaseProdi!=null && filterBaseProdi!=''){
                var exp_BaseProdi = filterBaseProdi.split('.');
                ProdiID = exp_BaseProdi[0];
            }
            $('#filterBaseProdi').prop('disabled',ds);

            var url = base_url_js+'api/__crudAttendance';
            var data = {
                action : 'filterPresensi',
                SemesterID : SemesterID,
                CombinedClasses : filterCombine,
                ProdiID : ProdiID
            };
            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {

                var sl = $('#filterGroup');

                sl.empty();
                sl.append('<option selected disabled>--- Select Class Group ---</option>');
                if(jsonResult.length>0){
                    for(var i=0;i<jsonResult.length;i++){
                        var dataF = jsonResult[i]
                        sl.append('<option value="'+dataF.ID+'">'+dataF.ClassGroup+'</option>');
                    }
                }
            });
        }





    }


</script>