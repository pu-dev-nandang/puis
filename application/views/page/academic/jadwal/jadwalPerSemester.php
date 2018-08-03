

<div class="thumbnail" style="margin-bottom: 10px;">
    <div class="row">
        <div class="col-xs-2" style="">
            <select class="form-control" id="filterST_ProgramCampus"></select>
        </div>
        <div class="col-xs-2" style="">
            <select id="filterST_Semester" class="form-control">
            </select>
        </div>
        <div class="col-xs-3" style="">
            <select id="filterST_BaseProdi" class="form-control"></select>
        </div>

        <div class="col-xs-2" style="">
            <select class="form-control form-filter-jadwal" id="filterST_Combine">
                <option value="">-- Show All --</option>
                <option value="1">Combine Class Yes</option>
                <option value="0">Combine Class No</option>
            </select>
        </div>

        <div class="col-xs-2" style="">
            <div id="selectST_SemesterSc"></div>
        </div>
    </div>

</div>


<script>
    $(document).ready(function () {
        // $('.form-filter-jadwal').prop("disabled",false);
        window.checkedDay = [];
        $('#filterST_ProgramCampus').empty();
        loadSelectOptionProgramCampus('#filterST_ProgramCampus','');

        $('#filterST_BaseProdi').empty();
        $('#filterST_BaseProdi').append('<option value="">-- All Programme Study --</option>' +
            '<option disabled>------------------------------------------</option>');
        loadSelectOptionBaseProdi('#filterST_BaseProdi','');


        $('#filterST_Semester').empty();
        // $('#filterSemester').append('<option value="" disabled selected>-- Year Academic--</option>' +
        //     '                <option disabled>------------------------------------------</option>');
        loSelectOptionSemester('#filterST_Semester','');

        setTimeout(function () {
            loadST_semester();
        },500);

    });
    
    function filterST_Schedule() {
        var ProgramsCampusID = $('#filterST_ProgramCampus').find(':selected').val();
        var SemesterID = $('#filterST_Semester').find(':selected').val().split('.')[0];
        var Prodi = $('#filterST_BaseProdi').find(':selected').val();
        var ProdiID = (Prodi!='') ? Prodi.split('.')[0] : '';
        var CombinedClasses = $('#filterST_Combine').find(':selected').val();
        var CombinedClasses = $('#filterST_Combine').find(':selected').val();

        getST_Schedule(ProgramsCampusID,SemesterID,ProdiID,CombinedClasses);
    }

    public function getST_Schedule(ProgramsCampusID,SemesterID,ProdiID,CombinedClasses) {

    }
    
</script>
