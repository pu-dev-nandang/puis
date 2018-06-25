
<style>
    #tableExam>thead>tr>th, #tableExam>tbody>tr>td {
        text-align: center;
    }
</style>

<hr/>
<div class="thumbnail" style="margin-bottom: 10px;">
    <div class="row">
        <div class="col-xs-4" style="">
            <select id="filterSemester" class="form-control form-filter form-filter-jadwal">
            </select>
        </div>
        <div class="col-xs-5" style="">
            <select id="filterBaseProdi" class="form-control form-filter form-filter-jadwal">
                <option value="">--- All Program Study ---</option>
                <option disabled>------------------------------------------</option>
            </select>
        </div>
        <div class="col-xs-3" style="">
            <select id="filterExam" class="form-control form-filter form-filter-jadwal">
                <option disabled selected>--- Exam Type ---</option>
                <option value="uts">UTS</option>
                <option value="uas">UAS</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" style="min-height: 150px;">
        <div class="">
            <hr/>
            <table class="table table-bordered" id="tableExam">
                <thead>
                <tr style="background: #437e88;color: #ffffff;">
                    <th style="width: 7%;">Group</th>
                    <th>Course</th>
                    <th style="width: 15%;">Lecturers</th>
                    <th style="width: 15%;">Pengawas</th>
                    <th style="width: 5%;">Action</th>
                    <th style="width: 20%;">Date</th>
                    <th style="width: 10%;">Time</th>
                    <th style="width: 7%;">Room</th>
                </tr>
                </thead>
                <tbody id="trExam"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // loadSelectOptionProgramCampus('#filterProgramCampus','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');
        $('#filterSemester').append('<option value="" disabled selected>-- Year Academic--</option>' +
            '                <option disabled>------------------------------------------</option>');

        loSelectOptionSemester('#filterSemester','');


    });


</script>