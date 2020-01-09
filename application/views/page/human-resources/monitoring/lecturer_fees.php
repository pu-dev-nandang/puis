

<?php
                $ServerName = $_SERVER['SERVER_NAME'];
                if($ServerName=='localhost'){
?>

<div class="col-md-6 col-md-offset-3">
    <div class="well">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Semester</label>
                    <select class="form-control" id="filterSemester"></select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Status Lecturer</label>
                    <select class="form-control" id="formStatusLecturer"></select>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        loSelectOptionSemester('#filterSemester','');
        loadSelectOptionLecturerStatus2('#formStatusLecturer',4);

        var firstLoad = setInterval(function () {

            var filterSemester = $('#filterSemester').val();
            var formStatusLecturer = $('#formStatusLecturer').val();

            if(filterSemester!='' && filterSemester!== null &&
                formStatusLecturer!='' && formStatusLecturer!== null){
                loadLecturer();
                clearInterval(firstLoad);
            }

        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);

    });

    function loadLecturer() {

        var filterSemester = $('#filterSemester').val();
        var formStatusLecturer = $('#formStatusLecturer').val();



    }

</script>

<?php

                } else {
                    echo "<h3>This module is in the process of being developed by the IT Team <i class='fa fa-smile-o'></i> <i class='fa fa-smile-o'></i> <i class='fa fa-smile-o'></i>
                                    <br/><small>we make it with <i class='fa fa-coffee'></i> and <i style='color: #ff00008c;' class='fa fa-heart'></i></small></h3>";
                }
                    ?>