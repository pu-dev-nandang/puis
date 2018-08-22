
<div class="col-md-8 col-md-offset-2">
    <div class="well">
        <div class="row">
            <div class="col-xs-5">
                <select class="form-control" id="filterSemester"></select>
            </div>
            <div class="col-xs-7">
                <select class="form-control" id="filterProgrammeStudy"></select>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#filterSemester').empty();
        loSelectOptionSemester('#filterSemester','');

        loadProdi();
    });

    function loadProdi(){
        $('#filterProgrammeStudy').empty();
        $('#filterProgrammeStudy').append('<option value="" selected disabled>--- Select Programme Study ---</option>');
        loadSelectOptionBaseProdi('#filterProgrammeStudy','');
    }
</script>