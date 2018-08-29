
<style>
    #tbContrctMonitoring thead th {
        text-align: center;
        background: #20485A;
        color: #FFFFFF;
    }
    #tbContrctMonitoring tbody td {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="well">
            <div class="row">
                <div class="col-xs-4">
                    <select class="form-control" id="filterSemester"></select>
                </div>
                <div class="col-xs-4">
                    <select class="form-control" id="filterStatusEmployees"></select>
                </div>
                <div class="col-xs-4">
                    <input id="filterRangeStart" class="hide" hidden readonly>
                    <input id="filterRangeEnd" class="hide" hidden readonly>
                    <input id="RangeDate" class="hide" hidden readonly>
                    <button class="btn btn-default btn-block" id="formSetRange"><i class="fa fa-calendar" aria-hidden="true"></i> | ( <i id="viewRange"><span></span></i>  )</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr/>
        <div id="divContrctMonitoring"></div>
    </div>
</div>
<ul id="rangec"></ul>

<script>
    $(document).ready(function () {
        $('#filterSemester').empty();
        loSelectOptionSemester('#filterSemester','');

        loadSelectOptionStatusEmployee('#filterStatusEmployees',4);

        window.loadFirst = setInterval(function () {
            var filterRangeStart = $('#filterRangeStart').val();
            var filterRangeEnd = $('#filterRangeEnd').val();

            if(filterRangeStart!='' && filterRangeStart!=null && filterRangeEnd!='' && filterRangeEnd!=null){
                loadTb();
                clearInterval(loadFirst);
            }

        },1000);
    });

    $(document).on('change','#filterSemester,#filterStatusEmployees',function () {
        loadTb();
    });

    function momentRange(start,end) {
        // var fromDate = moment();
        // var toDate = moment().add(15, 'days');

        var fromDate = moment(start);
        var toDate = moment(end);

        var range = moment().range(fromDate, toDate);
        var diff = range.diff('days');

        var array = range.toArray('days');
        // $.each(array, function(i, e) {
        //     $("#rangec").append("<li>" + moment(e).format("DD MM YYYY") + "</li>");
        // });

        var res = {
            diff : diff,
            details : array
        };

        return res;

    }

    $('#formSetRange').daterangepicker({
            startDate: moment().subtract('days', 29),
            endDate: moment(),
            minDate: '01/01/2014',
            maxDate: moment().format('MM/DD/YYYY'),
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

            loadTb();

            $('#viewRange span').html(range_updated);

        });

    $('#viewRange span').html(moment().subtract(29,'days').format('DD MMMM YYYY')+' - '+moment().format('DD MMMM YYYY'));
    $('#RangeDate').val(moment().subtract(29,'days').format('DD MMMM YYYY')+' - '+moment().format('DD MMMM YYYY'));
    $('#filterRangeStart').val(moment().subtract(29,'days').format('YYYY-MM-DD'));
    $('#filterRangeEnd').val(moment().format('YYYY-MM-DD'));


    function loadTb() {

        var filterSemester = $('#filterSemester').val();
        var filterStatusEmployees = $('#filterStatusEmployees').val();

        var filterRangeStart = $('#filterRangeStart').val();
        var filterRangeEnd = $('#filterRangeEnd').val();
        var RangeDate = $('#RangeDate').val();

        if(filterSemester!='' && filterSemester!=null
            && filterStatusEmployees!='' && filterStatusEmployees!=null
            && filterRangeStart !='' && filterRangeStart!=null
            && filterRangeEnd !='' && filterRangeEnd!=null
        ) {

            var SemesterID = filterSemester.split('.')[0];
            var StatusEmployeeID = filterStatusEmployees;

            var data = {
              action : 'showLecturerMonitoring',
                SemesterID : SemesterID,
                StatusEmployeeID : StatusEmployeeID,
                Start : filterRangeStart,
                End : filterRangeEnd
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudEmployees';

            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.length>0){
                    var arrDate = momentRange(filterRangeStart,filterRangeEnd);

                    $('#divContrctMonitoring').html('<table class="table table-bordered" id="tbContrctMonitoring">' +
                        '<thead>' +
                        '<tr>' +
                        '<th style="width: 1%;" rowspan="2">No</th>' +
                        '<th rowspan="2" style="width: 3%;">NIP</th>' +
                        '<th rowspan="2" style="width: 20%;">Name</th>' +
                        '<th rowspan="2" style="width: 2%;">Group</th>' +
                        '<th rowspan="2">Course</th>' +
                        '<th rowspan="2" style="width: 1%;">Credit</th>' +
                        '<th colspan="'+arrDate.details.length+'">'+RangeDate+'</th>' +
                        '<th rowspan="2" style="width: 3%;">Total Sesi</th>' +
                        '<th rowspan="2" style="width: 3%;">Total Credit</th>' +
                        '</tr>' +
                        '<tr id="trHead"></tr>' +
                        '</thead><tbody id="dataLec"></tbody>' +
                        '</table>');

                    $.each(arrDate.details, function(i, e) {
                        var bg = (moment(e).days() == 0 || moment(e).days() == 6) ? 'background:#bb1818;' : '';
                        $("#trHead").append('<th style="font-size:10px;width: 1%;'+bg+'">' + moment(e).format("DD") + '</th>');
                    });

                    var no =1;
                    for(var i=0;i<jsonResult.length;i++){
                        var d = jsonResult[i];

                        var rwSpan = 1 + d.Course.length;

                        $('#dataLec').append('<tr>' +
                            '<td rowspan="'+rwSpan+'">'+no+'</td>' +
                            '<td rowspan="'+rwSpan+'">'+d.NIP+'</td>' +
                            '<td rowspan="'+rwSpan+'" style="text-align: left;">'+d.Name+'</td>' +
                            '</tr>');

                        for(var c=0;c<d.Course.length;c++){
                            var d_c = d.Course[c];
                            $('#dataLec').append('<tr id="trS_'+no+'_'+d_c.ScheduleID+'">' +
                                '<td>'+d_c.ClassGroup+'</td>' +
                                '<td style="text-align: left;">'+d_c.NameEng+'</td>' +
                                '<td>'+d_c.Credit+'</td>' +
                                '</tr>');

                            var totalSesi = 0;
                            $.each(arrDate.details, function(i, e) {
                                var bg = ($.inArray(moment(e).format("YYYY-MM-DD"),d_c.Attendance)!=-1) ? 'style="background:#8bc34a78;font-weight: bold;"' : '';
                                var sts = ($.inArray(moment(e).format("YYYY-MM-DD"),d_c.Attendance)!=-1) ? 1 : '';
                                $("#trS_"+no+"_"+d_c.ScheduleID).append('<td '+bg+'>' + sts + '</td>');

                                if($.inArray(moment(e).format("YYYY-MM-DD"),d_c.Attendance)!=-1){
                                    totalSesi = totalSesi + 1;
                                }

                            });

                            var totalCredit = (totalSesi!=0) ? totalSesi * parseInt(d_c.Credit) : 0;

                            $("#trS_"+no+"_"+d_c.ScheduleID).append('<td>' + totalSesi + '</td>');
                            $("#trS_"+no+"_"+d_c.ScheduleID).append('<td>' + totalCredit + '</td>');
                        }

                        no++;
                    }

                } else {
                    $('#divContrctMonitoring').html('<h4>Data Not Yet</h4>');
                }






            });




        }






    }

    function loadDataPartime() {

        var token = jwt_encode({action:'readPartime'},'UAP)(*');
        var url = base_url_js+'api/__crudPartime';

        $.post(url,{token:token},function (jsonResult) {



        });

    }
</script>