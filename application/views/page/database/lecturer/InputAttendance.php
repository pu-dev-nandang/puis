<h1>Attendance</h1>

<script>
    $(document).ready(function () {
        getDataAttendance();
    });

    function getDataAttendance() {
        var ScheduleID = '<?php echo $ScheduleID; ?>';
        console.log(ScheduleID);

        var url = base_url_js+'api/__crudAttendance';
        var data = {
          action : 'read',
            ScheduleID : ScheduleID
        };
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {
            console.log(jsonResult);
        });
    }
</script>