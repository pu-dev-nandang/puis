<div class="">
    <div class="row">
        <div class="col-md-12" style="margin-top: 50px;">
            <div class="well">
                <div class="row">
                    <div class="col-md-3">
                        <label>Semester</label>
                        <select class="form-control filterLL" id="filterSemester"></select>
                    </div>
                    <div class="col-md-3">
                        <label>Prodi</label>
                        <select class="form-control filterLL" id="selectProdi">
                            <option value="">-- All Prodi --</option>
                            <option disabled>---------</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Status Employees</label>
                        <select class="form-control filterLL" id="formStatusEmployee">
                            <option value="2">Contract Employees</option>
                            <option value="1">Permanent Employees</option>
                            <option value="-1" style="color:red;">Non Active</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Status Lecturer</label>
                        <select class="form-control filterLL" id="formStatusLecturer">
                            <option value="6">Home Base Lecturer</option>
                            <option value="5">Part Time Lecturer</option>
                            <option value="4" selected="">Honorer Lecturer</option>
                            <option value="3">Permanent Lecturer</option>
                            <option value="-1" style="color:red;">Non Active</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="">
    <div class="row">
        <div class="col-md-12">
            <table class="table  table-bordered table-centre">
                <thead>
                <tr style="background: #f3f3f3;">
                    <th style="width: 1%;">No</th>
                    <th>Lecturer</th>
                    <th>Course</th>
                    <th style="width: 30%;">Schedule</th>
                    <th style="width: 3%;">Credit MK</th>
                    <th style="width: 3%;">Credit BKD</th>
                    <th style="width: 20%;">Team</th>
                    <th style="width: 5%;">Credit Difference</th>
                </tr>
                </thead>
                <tbody id="listLecturer">
                </tbody>

            </table>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {
        loSelectOptionSemester('#filterSemester','');
        loadSelectOptionBaseProdi('#selectProdi','');

        var firstLoad = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            var formStatusEmployee = $('#formStatusEmployee').val();
            var formStatusLecturer = $('#formStatusLecturer').val();

            if(filterSemester!='' && filterSemester!=null &&
                formStatusEmployee!='' && formStatusEmployee!=null &&
                formStatusLecturer!='' && formStatusLecturer!=null) {
                loadDataLecturer();
                clearInterval(firstLoad);
            }
        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);

    });

    $('.filterLL').change(function () {

        var filterSemester = $('#filterSemester').val();
        var selectProdi = $('#selectProdi').val();
        var formStatusEmployee = $('#formStatusEmployee').val();
        var formStatusLecturer = $('#formStatusLecturer').val();

        if(filterSemester!='' && filterSemester!=null &&
            formStatusEmployee!='' && formStatusEmployee!=null &&
        formStatusLecturer!='' && formStatusLecturer!=null) {
            loadDataLecturer();
        }

    });

    function loadDataLecturer() {

        var filterSemester = $('#filterSemester').val();
        var selectProdi = $('#selectProdi').val();
        var formStatusEmployee = $('#formStatusEmployee').val();
        var formStatusLecturer = $('#formStatusLecturer').val();

        if(filterSemester!='' && filterSemester!=null &&
            formStatusEmployee!='' && formStatusEmployee!=null &&
            formStatusLecturer!='' && formStatusLecturer!=null) {

            loading_modal_show();

            var SemesterID = filterSemester.split('.')[0];
            var ProdiID = (selectProdi!='' && selectProdi!=null) ? selectProdi.split('.')[0] : '';

            var data = {
                action : 'bkdShowingCredit',
                SemesterID : SemesterID,
                ProdiID : ProdiID,
                StatusEmployeeID : formStatusEmployee,
                StatusLecturerID : formStatusLecturer
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudBKD';

            $.post(url,{token:token},function (jsonResult) {

                $('#listLecturer').empty();

                if(jsonResult.dataLecturer.length>0){

                    var TotalCreditAsli = 0;
                    var TotalCreditBKD = 0;
                    var TotalCreditJadwal = 0;
                    var TotalCreditSelisih = 0;


                    var dataTr = '';

                    $.each(jsonResult.dataLecturer,function (i,v) {


                        if(v.Course.length>0){



                            var Course = v.Course;
                            var rwspn = Course.length + 1;
                            dataTr = dataTr+'<tr>'+
                                '                            <td rowspan="'+rwspn+'">'+(i+1)+'</td>'+
                                '                            <td style="text-align: left;" rowspan="'+rwspn+'">'+v.Name+'<br/>'+v.NIP+'</td>'+
                                '                        </tr>';

                            $.each(Course,function (i2,v2) {


                                var team = '';
                                if(v2.DetailTeam.length>0){
                                    $.each(v2.DetailTeam,function (i3,d) {
                                        team = team+'<div>- '+d.NIP+' '+d.Name+'</div>';
                                    });
                                }

                                var schedule = '';
                                var totalSKS = 0;
                                var totalSKS2 = 0;

                                if(v2.Schedule.length>0){
                                    var tr = '';

                                    $.each(v2.Schedule,function (i4,s) {

                                      var viewCredit2 = (parseFloat(s.Credit) / v2.Schedule.length);

                                        tr = tr+'<tr>' +
                                            '<td>'+s.DayNameEng+'</td>' +
                                            '<td>'+s.StartSessions.substr(0,5)+' - '+s.EndSessions.substr(0,5)+'</td>' +
                                        '<td style="border-right: 1px solid #ccc;">'+s.Room+'</td>' +
                                        '<td><span style="color: blue;">'+s.Credit+' | '+viewCredit2+'</span></td>' +
                                        '</tr>';

                                        totalSKS = totalSKS + parseFloat(s.Credit);
                                        totalSKS2 = totalSKS2 + parseFloat(viewCredit2);
                                    });



                                    schedule = '<table class="table" style="margin-bottom: 0px;">' +
                                        '<tbody>'+tr+'</tbody><tr style="background: #e0f4ff;font-weight: bold;"><td colspan="3" style="border-right: 1px solid #ccc;">Total Credit</td><td>'+totalSKS+' | '+totalSKS2+'</td></tr></table>';
                                }

                                TotalCreditAsli = TotalCreditAsli + parseFloat(v2.CreditMK);
                                TotalCreditBKD = TotalCreditBKD + parseFloat(v2.CreditBKD);
                                TotalCreditJadwal = TotalCreditJadwal + parseFloat(totalSKS);
                                TotalCreditSelisih = TotalCreditSelisih + (totalSKS - parseFloat(v2.CreditMK));


                                //var stlBGtr = ( v2.DetailTeam.length>0) ? 'background: #ff000017;' : 'background: #fff;';
                                var stlBGtr = 'background: #fff;';

                                dataTr = dataTr+'<tr style="'+stlBGtr+'">'+
                        '                                <td style="text-align: left;"><b style="">'+v2.NameEng+'</b><br/>Code : '+v2.MKCode+'<br/>Group : '+v2.ClassGroup+'</td>'+
                        '                                <td style="text-align: left;font-size: 12px;">'+schedule+'</td>'+
                        '                                <td>'+v2.CreditMK+'</td>'+
                        '                                <td style="background: lightyellow;">'+v2.CreditBKD+'</td>'+
                        '                                <td style="text-align: left;font-size: 12px;">'+team+'</td>'+
                        '                                <td style="font-size: 12px;">'+totalSKS+' - '+v2.CreditMK+' = '+(totalSKS-v2.CreditMK)+'</td>'+
'                            </tr>';
                            });

                        }

                    });

                    dataTr = dataTr+'<tr>' +
                        '                    <td colspan="3"></td>' +
                        '                    <td>'+TotalCreditJadwal+'</td>' +
                        '                    <td>'+TotalCreditAsli+'</td>' +
                        '                    <td><b>'+TotalCreditBKD+'</b></td>' +
                        '                    <td>-</td>' +
                        '                    <td>'+TotalCreditJadwal+' - '+TotalCreditAsli+' = '+TotalCreditSelisih+'</td>' +
                        '                </tr>';

                    if(TotalCreditJadwal==0 &&
                        TotalCreditAsli==0 &&
                    TotalCreditBKD==0){
                        $('#listLecturer').html('<tr>' +
                            '<td colspan="8">No data</td>' +
                            '</tr>');
                        hideLoadingpage();
                    } else {
                        $('#listLecturer').html(dataTr);
                        hideLoadingpage();

                    }

                } else {
                    $('#listLecturer').html('<tr>' +
                        '<td colspan="8">No data</td>' +
                        '</tr>');
                    hideLoadingpage();
                }

            });


        }

    }

    function hideLoadingpage() {
        setTimeout(function () {
            loading_modal_hide();
        },500);
    }


</script>
