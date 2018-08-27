
<style>
    #tableExam>thead>tr>th, #tableExam>tbody>tr>td {
        text-align: center;
    }
</style>

<hr/>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="thumbnail" style="margin-bottom: 10px;">
            <div class="row">
                <div class="col-xs-5" style="">
                    <select id="filterSemester" class="form-control form-filter">
                    </select>
                </div>
<!--                <div class="col-xs-5" style="">-->
<!--                    <select id="filterBaseProdi" class="form-control form-filter"></select>-->
<!--                </div>-->
                <div class="col-xs-2" style="">
                    <select id="filterExam" class="form-control form-filter">
                        <option value="uts">UTS</option>
                        <option value="uas">UAS</option>
                    </select>
                </div>
            </div>
        </div>
        <hr/>

    </div>
</div>

<div class="row">
    <div class="col-md-12" style="min-height: 150px;">
        <div class="">
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

        loSelectOptionSemester('#filterSemester','');

        window.firsLoadExam = setInterval(function () {

            var filterSemester = $('#filterSemester').val();
            var filterBaseProdi = $('#filterBaseProdi').val();
            var filterExam = $('#filterExam').val();

            if(filterSemester!='' && filterSemester!=null &&
                filterExam!='' && filterExam!=null){
                loadDataScheduleExam();
                clearInterval(firsLoadExam);
            }

        },1000);


    });


</script>