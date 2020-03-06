
<div class="col-md-10 col-md-offset-1">
    <div class="well">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Semester</label>
                    <select class="form-control" id="filterSemester"></select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Prodi</label>
                    <select class="form-control" id="filterProdi"></select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Status Lecturer</label>
                    <select class="form-control" id="formStatusLecturer"></select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Range Date</label>
                    <label>Range Date</label>
                    <input id="filterRangeStart" class="hide" hidden readonly>
                    <input id="filterRangeEnd" class="hide" hidden readonly>
                    <input id="RangeDate" class="hide" hidden readonly>
                    <button class="btn btn-default btn-block" id="formSetRange"><i class="fa fa-calendar" aria-hidden="true"></i> | ( <i id="viewRange"><span></span></i>  )</button>
                </div>
            </div>
        </div>
    </div>
</div>



<script>

    $(document).ready(function () {

        loSelectOptionSemester('#filterSemester','');
        loadSelectOptionLecturerStatus2('#formStatusLecturer',4);
        loadSelectOptionBaseProdi('#filterProdi','');

        $('#formSetRange').daterangepicker({
                startDate: moment().subtract('days', 29),
                endDate: moment(),
                minDate: '01/01/2014',
                maxDate: moment().add(10, 'days').format('DD/MM/YYYY'),
                // maxDate: '12/12/2018',
                dateLimit: { days: 31 },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                opens: 'left',
                buttonClasses: ['btn btn-default'],
                applyClass: 'btn-sm btn-primary',
                cancelClass: 'btn-sm',
                format: 'DD/MM/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Submit',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            },

            function (start, end) {

                var range_updated = start.format('DD MMMM YYYY') + ' - ' + end.format('DD MMMM YYYY');

                $('#filterRangeStart').val(start.format('YYYY-MM-DD'));
                $('#filterRangeEnd').val(end.format('YYYY-MM-DD'));
                $('#RangeDate').val(range_updated);

                // loadTb();

                $('#viewRange span').html(range_updated);

            });


        $('#viewRange span').html(moment().subtract(29,'days').format('DD MMMM YYYY')+' - '+moment().format('DD MMMM YYYY'));
        $('#RangeDate').val(moment().subtract(29,'days').format('DD MMMM YYYY')+' - '+moment().format('DD MMMM YYYY'));
        $('#filterRangeStart').val(moment().subtract(29,'days').format('YYYY-MM-DD'));
        $('#filterRangeEnd').val(moment().format('YYYY-MM-DD'));

    });


    $('#filterSemester,#formStatusLecturer,#filterProdi').change(function () {

        var filterSemester = $('#filterSemester').val();
        var formStatusLecturer = $('#formStatusLecturer').val();
        var filterProdi = $('#filterProdi').val();

        var filterRangeStart = $('#filterRangeStart').val();
        var filterRangeEnd = $('#filterRangeEnd').val();

        if(filterSemester!='' && filterSemester!=null &&
            formStatusLecturer!='' && formStatusLecturer!=null &&
            filterProdi!='' && filterProdi!=null &&
            filterRangeStart!='' && filterRangeStart!=null &&
            filterRangeEnd!='' && filterRangeEnd!=null){
            loadDataRecapitulation();
        }

    });
    
    function loadDataRecapitulation() {

        var filterSemester = $('#filterSemester').val();
        var formStatusLecturer = $('#formStatusLecturer').val();
        var filterProdi = $('#filterProdi').val();

        var filterRangeStart = $('#filterRangeStart').val();
        var filterRangeEnd = $('#filterRangeEnd').val();
        var RangeDate = $('#RangeDate').val();



        if(filterSemester!='' && filterSemester!=null &&
            formStatusLecturer!='' && formStatusLecturer!=null &&
            filterProdi!='' && filterProdi!=null){

            var SemesterID = filterSemester.split('.')[0];
            var ProdiID = filterProdi.split('.')[0];

            var url = base_url_js+'api/__crudEmployees';
            var data = {
                action : 'getDataRecapitulation',
                SemesterID : SemesterID,
                ProdiID : ProdiID,
                StatusLecturerID : formStatusLecturer,
                RangeStart : filterRangeStart,
                RangeEnd : filterRangeEnd
            };

            var token = jwt_encode(data,'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {

            });

        }

    }

</script>