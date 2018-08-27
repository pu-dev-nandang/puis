

<style>
    #tableExchange tr th{
        text-align: center;
        background-color: #437e88;
        color: #ffffff;
    }
    #tableExchange tr td {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="well">
            <div class="row">
                <div class="col-xs-7">
                    <select class="form-control" id="filterSemester"></select>
                </div>
                <div class="col-xs-5">
                    <button class="btn btn-default btn-default-success" disabled id="btnDownload2PDFExchange">Download to PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr/>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tableExchange">
                <thead>
                <tr>
                    <th rowspan="2" style="width: 1%;">No</th>
                    <th rowspan="2">Course</th>
                    <th rowspan="2" style="width: 5%;">Group</th>
                    <th rowspan="2" style="width: 3%;">Sesi</th>
                    <th colspan="2">Schedule Exist</th>
                    <th colspan="2" style="background : #884343;">Exchange to</th>
                    <th rowspan="2" style="background : #884343;width: 13%;">Reason</th>
                    <th rowspan="2" style="width: 1%;">S</th>

                </tr>
                <tr>
                    <th style="width: 15%;">Day, Time</th>
                    <th style="width: 7%;">Room</th>

                    <th style="width: 15%;background : #884343;">Day, Time</th>
                    <th style="width: 7%;background : #884343;">Room</th>
                </tr>
                </thead>
                <tbody id="dataEx"></tbody>
            </table>
        </div>

        <form id="FormHide2PDF" action="<?php echo base_url('save2pdf/scheduleExchange'); ?>" method="post" target="_blank">
            <textarea id="dataFormHide2PDF" class="hide" hidden name="token" ></textarea>
        </form>

    </div>
</div>


<script>
    $(document).ready(function () {

        // console.log(moment('2018-08-21').format('dddd, D MMM YYYY'));

        window.dataFormHide2PDF = [];

        $('#filterSemester').empty();
        loSelectOptionSemester('#filterSemester','');

        window.loadFirstTime = setInterval(function () {
            loadScheduleExchage();
        },1000);

    });

    $(document).on('change','#filterSemester',function () {
        loadScheduleExchage();
    });

    $(document).on('click','#btnDownload2PDFExchange',function () {
        $('#FormHide2PDF').submit();
    });

    function loadScheduleExchage() {

        var filterSemester = $('#filterSemester').val();

        if(filterSemester!=null && filterSemester!=''){

            clearInterval(loadFirstTime);

            var SemesterID = filterSemester.split('.')[0];

            var url = base_url_js+'api/__crudScheduleExchange';
            var token = jwt_encode({action:'readBySemesterID',SemesterID:SemesterID},'UAP)(*');


            $.post(url,{token:token},function (jsonResult) {

                $('#btnDownload2PDFExchange').prop('disabled',true);

                var tr = $('#dataEx');
                tr.empty();
                dataFormHide2PDF=[];
                if(jsonResult.length>0){
                    var no = 1;
                    for(var i=0;i<jsonResult.length;i++){
                        var d = jsonResult[i];

                        var s = (d.Status=='1' || d.Status==1) ? '<i class="fa fa-check-circle" style="color: #369c3a;"></i>' : '<i class="fa fa-circle" style="color:#d8d8d8;"></i>';
                        var troom = (d.T_Room!=null && d.T_Room!='') ? d.T_Room : '-';

                        var A_Date = moment(d.A_Date).format('dddd, D MMM YYYY');
                        var A_Time = '('+d.A_StartSessions.substr(0,5)+' - '+d.A_EndSessions.substr(0,5)+')';

                        var T_Date = moment(d.T_Date).format('dddd, D MMM YYYY');
                        var T_Time = '('+d.T_StartSessions.substr(0,5)+' - '+d.T_EndSessions.substr(0,5)+')';

                        tr.append('<tr>' +
                            '<td>'+(no++)+'</td>' +
                            '<td style="text-align: left;"><b>'+d.Course+'</b><br/><i class="fa fa-user right-margin"></i><span>'+d.Lecturer+'</span></td>' +
                            '<td>'+d.ClassGroup+'</td>' +
                            '<td>'+d.A_Sesi+'</td>' +
                            '<td>'+A_Date+'<br/>'+A_Time+'</td>' +
                            '<td>'+d.A_Room+'</td>' +
                            '<td>'+T_Date+'<br/>'+T_Time+'</td>' +
                            '<td>'+troom+'</td>' +
                            '<td style="text-align: left;">'+d.Reason+'</td>' +
                            '<td>'+s+'</td>' +
                            '</tr>');

                        var arr2pdf = {
                            Course : d.Course,
                            Lecturer : d.Lecturer,
                            ClassGroup : d.ClassGroup,
                            A_Sesi : d.A_Sesi,
                            A_Date : A_Date,
                            A_Time : A_Time,
                            A_Room : d.A_Room,
                            T_Date : T_Date,
                            T_Time : T_Time,
                            T_Room : troom,
                            Reason : d.Reason,
                            Status : d.Status
                        };

                        dataFormHide2PDF.push(arr2pdf);
                    }

                    $('#btnDownload2PDFExchange').prop('disabled',false);
                    var token = jwt_encode(dataFormHide2PDF,'UAP)(*');
                    $('#dataFormHide2PDF').val(token);
                }
                else {
                    tr.append('<tr>' +
                        '<td colspan="10">--- Data Not Yet ---</td>' +
                        '</tr>');
                }
            });
        }



    }
</script>