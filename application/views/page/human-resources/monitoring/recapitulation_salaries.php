
<style>
    #tableProdi tbody>tr>td {
        vertical-align: middle !important;
    }
</style>

<div class="">
    <div class="well">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Semester</label>
                    <select class="form-control" id="filterSemester"></select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Prodi</label>
                    <select class="form-control" id="filterProdi">
                        <option value="">-- All Prodi --</option>
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Status Lecturer</label>
                    <select class="form-control" id="formStatusLecturer"></select>
                </div>
            </div>

            <div class="col-md-5">
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
<button class="btn btn-primary btn-lg" onclick="printDiv();">Cetak</button>
<div class="" id="DivIdToPrint" style="margin-bottom: 50px;">

    <table class="table table-bordered table-centre" id="tableProdi">
        <thead>
        <tr>
            <th style="width: 1%;">No</th>
            <th style="width: 5%;">NIP</th>
            <th style="width: 7%;">Name</th>
            <th style="width: 3%;">Prodi</th>
            <th style="width: 10%;">Mata Kuliah</th>
            <th style="width: 5%;">SKS Real</th>
            <th style="width: 5%;">Jumlah Sesi</th>
            <th style="width: 5%;">Jumlah Kedatangan</th>
            <th style="width: 5%;">Total SKS</th>
            <th style="width: 10%;">Tarif per SKS</th>
            <th style="width: 10%;">Tunjangan</th>
            <th style="width: 10%;">Honorarium</th>
            <th style="width: 10%;">Tunjangan NIDN</th>
            <th style="width: 10%;">Grand Total</th>
            <th>Keterangan</th>
        </tr>
        </thead>
        <tbody id="listLec"></tbody>
    </table>
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
                loadDataRecapitulation();

                $('#viewRange span').html(range_updated);

            });


        $('#viewRange span').html(moment().subtract(29,'days').format('DD MMMM YYYY')+' - '+moment().format('DD MMMM YYYY'));
        $('#RangeDate').val(moment().subtract(29,'days').format('DD MMMM YYYY')+' - '+moment().format('DD MMMM YYYY'));
        $('#filterRangeStart').val(moment().subtract(29,'days').format('YYYY-MM-DD'));
        $('#filterRangeEnd').val(moment().format('YYYY-MM-DD'));

        var firstLoad = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            var formStatusLecturer = $('#formStatusLecturer').val();
            var filterRangeStart = $('#filterRangeStart').val();
            var filterRangeEnd = $('#filterRangeEnd').val();

            if(filterSemester!='' && filterSemester!=null &&
                formStatusLecturer!='' && formStatusLecturer!=null &&
            filterRangeStart!='' && filterRangeStart!=null &&
            filterRangeEnd!='' && filterRangeEnd!=null){
                loadDataRecapitulation();
            }

        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);

    });

    function printDiv()
    {

        var divToPrint=document.getElementById('DivIdToPrint');

        var newWin=window.open('','Print-Window');

        newWin.document.open();

        newWin.document.write('<html>' +
            '<link href="'+base_url_js+'assets/template/bootstrap/css/bootstrap.min.css" rel="stylesheet">' +
            '<style>' +
            '@page{size:landscape;}' +
            '    .container-image {' +
            '        display: inherit;' +
            '        position: relative;' +
            '        text-align: center;' +
            '        color: white;' +
            '        border: 2px solid #FFFFFF;' +
            '    }' +
            '    .centered {' +
            '        position: absolute;' +
            '        top: 50%;' +
            '        left: 50%;' +
            '        transform: translate(-50%, -50%);' +
            '    }' +
            '</style>' +
            '<body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

        newWin.document.close();

        setTimeout(function(){newWin.close();},10);

    }




    $('#filterSemester,#formStatusLecturer,#filterProdi').change(function () {

        var filterSemester = $('#filterSemester').val();
        var formStatusLecturer = $('#formStatusLecturer').val();

        var filterRangeStart = $('#filterRangeStart').val();
        var filterRangeEnd = $('#filterRangeEnd').val();

        if(filterSemester!='' && filterSemester!=null &&
            formStatusLecturer!='' && formStatusLecturer!=null &&
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
        // var RangeDate = $('#RangeDate').val();



        if(filterSemester!='' && filterSemester!=null &&
            formStatusLecturer!='' && formStatusLecturer!=null){

            var SemesterID = filterSemester.split('.')[0];
            var ProdiID = (filterProdi!='' && filterProdi!='') ? filterProdi.split('.')[0] : '';

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

                $('#listLec').empty();
                if(jsonResult.length>0){

                    var no = 1;
                    var viewProdiLabel = '';
                    var TotalGrandTotal = 0;
                    var TotalGrandTotal_All = 0;

                    $.each(jsonResult,function (i,v) {

                        var Schedule = v.Schedule;
                        var rwSpan = Schedule.length;



                        if(Schedule.length>0){

                            var viewSchedule = '';
                            var TotalHonorarium = 0;
                            var Tarif_NIDN = 0;
                            var Tarif_Tunjangan = 0;
                            $.each(Schedule,function (i2,v2) {

                                var realCredit = v2.Credit;

                                var tkn_l = jwt_encode(v2.TotalLecturer_Details,'UAP)(*');
                                var TotalCredit = (parseInt(v2.Credit) / parseInt(v2.TotalLecturer));
                                var viewTotalCredit = (v2.TotalLecturer > 1)
                                    ? '<a href="javascript:void(0);" class="showDetailLecturer" data-i2="'+i2+'" data-no="'+no+'" data-token="'+tkn_l+'">'+(parseFloat(TotalCredit).toFixed(2))+'</a>'
                                    : v2.Credit;

                                var Tarif_SKS = (v2.Fee_SKS!='' && v2.Fee_SKS!=null
                                    && v2.Fee_SKS!=0 && v2.Fee_SKS!='0') ? parseFloat(v2.Fee_SKS) : 0;
                                var showTarif_SKS = (Tarif_SKS!=0) ? formatRupiah(Tarif_SKS) : '<span style="color: red;">Not set</span>';

                                Tarif_NIDN = (v2.Fee_NIDN!='' && v2.Fee_NIDN!=null
                                    && v2.Fee_NIDN!=0 && v2.Fee_NIDN!='0') ? parseFloat(v2.Fee_NIDN) : 0;
                                var showTarif_NIDN = (Tarif_NIDN!='')
                                    ? formatRupiah(Tarif_NIDN) : '<span style="color: red;">Not set</span>';

                                Tarif_Tunjangan = (v2.Fee_Tunjangan!='' && v2.Fee_Tunjangan!=null
                                    && v2.Fee_Tunjangan!=0 && v2.Fee_Tunjangan!='0')
                                    ? parseFloat(v2.Fee_Tunjangan) * parseInt(v2.AttendingSameDate) : 0;


                                var tr_depan = (i2!=0) ? '<tr>'
                                    : '';

                                var tr_jumlah_kedatangan = '';
                                var tr_tunjangan = '';
                                var tr_tunjangan_NIDN = '';
                                var tr_grandTotal = '';
                                var tr_Keterangan = '';
                                if(i2==0){
                                    var tkn = jwt_encode(v2.AttendingSameDate_Details,'UAP)(*');
                                    tr_jumlah_kedatangan = '<td rowspan="'+rwSpan+'"><a href="javascript:void(0);" class="showAttendingSameDate" data-no="'+no+'" data-attd="'+tkn+'">'+v2.AttendingSameDate+'</a></td>';



                                    var viewdataTunjangan = (Tarif_Tunjangan!=0) ? formatRupiah(Tarif_Tunjangan) : '<span style="color: red;">Not set</span>';
                                    tr_tunjangan = '<td rowspan="'+rwSpan+'" style="background: #8bc34a14;"">'+viewdataTunjangan+'</td>';

                                    tr_tunjangan_NIDN = '<td rowspan="'+rwSpan+'" style="background: #8bc34a14;">'+showTarif_NIDN+'</td>';
                                    tr_grandTotal = '<td rowspan="'+rwSpan+'" style="background: lightyellow;"  id="viewGT_'+no+'">*GT</td>';
                                    tr_Keterangan = '<td rowspan="'+rwSpan+'"></td>';
                                }

                                var JumlahHonorarium = parseFloat(TotalCredit) * Tarif_SKS;
                                TotalHonorarium = TotalHonorarium + JumlahHonorarium



                                var tkn = jwt_encode(v2.Attending_Details,'UAP)(*');
                                viewSchedule = viewSchedule+
                                    tr_depan+'<td style="text-align: left;" id="viewCourse_'+no+'_'+i2+'">'+v2.Course+'</td>' +
                                    '<td>'+realCredit+'</td>' +
                                    '<td><a href="javascript:void(0);" class="showAttending" data-i2="'+i2+'" data-no="'+no+'" data-attd="'+tkn+'">'+v2.Attending+'</a></td>' +tr_jumlah_kedatangan+

                                    '<td>'+viewTotalCredit+'</td>' +
                                    '<td>'+showTarif_SKS+'</td>' +tr_tunjangan+

                                    '<td style="background: #8bc34a14;">'+formatRupiah(JumlahHonorarium)+'</td>' +
                                    tr_tunjangan_NIDN+tr_grandTotal+tr_Keterangan
                                    +'</tr>';
                            });


                            var viewProdiCode = (v.ProdiCode!='' && v.ProdiCode!=null ) ? v.ProdiCode : '<span style="color: #ff5722;">MKDU</span>';

                            var rw2 = '<tr>' +
                                '<td rowspan="'+rwSpan+'">'+(no++)+'</td>' +
                                '<td rowspan="'+rwSpan+'">'+v.NIP+'</td>' +
                                '<td rowspan="'+rwSpan+'" style="text-align: left;" id="viewName_'+no+'">'+v.Name+'</td>'+
                                '<td rowspan="'+rwSpan+'">'+viewProdiCode+'</td>'+viewSchedule+'' +
                                '';


                            viewProdiLabel = (i==0) ? viewProdiCode : viewProdiLabel;

                            if(viewProdiLabel!=viewProdiCode){
                                var totalProdi = '<tr style="background: #eaeaea;">' +
                                    '<td colspan="13" style="text-align: right;color: #333333;font-weight: bold;">'+viewProdiLabel+' Total</td>' +
                                    '<td style="background: #ffeb3b;"><b>'+formatRupiah(TotalGrandTotal)+'</b></td>' +
                                    '<td></td>' +
                                    '</tr>';
                                $('#listLec').append(totalProdi);
                                viewProdiLabel = viewProdiCode;
                                TotalGrandTotal_All = TotalGrandTotal_All + TotalGrandTotal;
                                TotalGrandTotal = (i==(jsonResult.length-1)) ? TotalGrandTotal : 0;
                            }

                            // rowData = rowData+rw2;
                            $('#listLec').append(rw2);

                            var GrandTotal = TotalHonorarium + Tarif_NIDN + Tarif_Tunjangan;
                            $('#viewGT_'+(no-1)).html(formatRupiah(GrandTotal));

                            TotalGrandTotal = TotalGrandTotal + GrandTotal;

                            if(i==(jsonResult.length-1)){
                                TotalGrandTotal_All = TotalGrandTotal_All + TotalGrandTotal;

                                var totalProdi = '<tr style="background: #eaeaea;">' +
                                    '<td colspan="13" style="text-align: right;color: #333333;font-weight: bold;">'+viewProdiLabel+' Total</td>' +
                                    '<td style="background: #ffeb3b;"><b>'+formatRupiah(TotalGrandTotal)+'</b></td>' +
                                    '<td></td>' +
                                    '</tr>' +
                                    '<tr style="background: #cecece;">' +
                                    '<td colspan="13" style="text-align: right;"><h3 style="margin-top: 10px;"><b>Grand Total</b></h3></td>' +
                                    '<td style="background: #4CAF50;color: #ffffff;"><h3 style="margin-top: 10px;">'+formatRupiah(TotalGrandTotal_All)+'</h3></td>' +
                                    '<td></td>' +
                                    '</tr>';

                                $('#listLec').append(totalProdi);
                                viewProdiLabel = viewProdiCode;

                            }



                        }


                    });


                }

            });

        }

    }


    // showAttendingSameDate
    // showAttending

    $(document).on('click','.showAttending',function () {
        var no = $(this).attr('data-no');
        var i2 = $(this).attr('data-i2');
        var Course = $('#viewCourse_'+no+'_'+i2).text();
        var Name = $('#viewName_'+(parseInt(no)+1)).text();
        var tkn = $(this).attr('data-attd');
        var d = jwt_decode(tkn,'UAP)(*');

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Jumlah Sesi | '+Name+' | '+Course+'</h4>');

        var tr = '';
        $.each(d,function (i,v) {

            var viewDate = (v.Date!='' && v.Date!=null) ? moment(v.Date).format('dddd, DD MMM YYYY') : '';
            var viewIn = (v.In!='' && v.In!=null) ? v.In.substr(0,5) : '';
            var viewOut = (v.Out!='' && v.Out!=null) ? v.Out.substr(0,5) : '';

            tr = tr+'<tr>' +
                '<td>'+(i+1)+'</td>' +
                '<td>'+viewDate+'</td>' +
                '<td>'+viewIn+'</td>' +
                '<td>'+viewOut+'</td>' +
                '</tr>';
        });

        var htmlss = '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <table class="table table-bordered table-striped table-centre">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Date</th>' +
            '                <th style="width: 20%;">In</th>' +
            '                <th style="width: 20%;">Out</th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody>'+tr+'</tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    $(document).on('click','.showAttendingSameDate',function () {
        var no = $(this).attr('data-no');
        var Name = $('#viewName_'+(parseInt(no)+1)).text();
        var tkn = $(this).attr('data-attd');
        var d = jwt_decode(tkn,'UAP)(*');

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Jumlah Kedatangan | '+Name+'</h4>');

        var li = '';
        $.each(d,function (i,v) {
            var viewDate = (v.Date!='' && v.Date!=null) ? moment(v.Date).format('dddd, DD MMM YYYY') : '';
            li = li+'<li>'+viewDate+'</li>'
        });

        var htmlss = '<ul>'+li+'</ul>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

    $(document).on('click','.showDetailLecturer',function () {
        var no = $(this).attr('data-no');
        var i2 = $(this).attr('data-i2');
        var Course = $('#viewCourse_'+no+'_'+i2).text();
        var Name = $('#viewName_'+(parseInt(no)+1)).text();

        var tkn = $(this).attr('data-token');
        var d = jwt_decode(tkn,'UAP)(*');

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Total SKS | '+Name+' | '+Course+'</h4>');

        var li = '';
        $.each(d,function (i,v) {
            li = li+'<li>'+v.NIP+' - '+v.Name+'</li>'
        });

        var htmlss = '<ul>'+li+'</ul>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

</script>