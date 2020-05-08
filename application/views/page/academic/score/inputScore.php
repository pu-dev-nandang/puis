
<style>
    #tableScore thead tr th {
        text-align: center;
        background: #884343;
        color: #ffffff;
        
    }

    #tableScore thead tr td {
        background: #607D8B;
        color: #fff;
        font-weight: bold;
    }

    #tableScore tr td {
        text-align: center;
    }


</style>

<div id="divScore"></div>


<script>
    $(document).ready(function () {

        window.dataIDStudyPlanning=[];

        window.Grade_Assig1 = 0;
        window.Grade_Assig2 = 0;
        window.Grade_Assig3 = 0;
        window.Grade_Assig4 = 0;
        window.Grade_Assig5 = 0;
        window.Grade_Assigment=0;
        window.Grade_UTS=0;
        window.Grade_UAS=0;

        getGrading();
    });

    function getGrading() {

        var ScheduleID = '<?php echo $ScheduleID; ?>';
        var url = base_url_js+'api/__crudScore';

        var token = jwt_encode({action:'checkGrade',ID:ScheduleID},'UAP)(*');
        $.post(url,{token:token},function (jsonResultGrade) {


            if(jsonResultGrade.Status=='1'){
                Grade_Assig1 = jsonResultGrade.Details.Assg1;
                Grade_Assig2 = jsonResultGrade.Details.Assg2;
                Grade_Assig3 = jsonResultGrade.Details.Assg3;
                Grade_Assig4 = jsonResultGrade.Details.Assg4;
                Grade_Assig5 = jsonResultGrade.Details.Assg5;

                Grade_Assigment = jsonResultGrade.Details.Assigment;
                Grade_UTS = jsonResultGrade.Details.UTS;
                Grade_UAS = jsonResultGrade.Details.UAS;

                getDataScore();
            } else {
                $('#divScore').html('<div style="text-align: center;"><h3>Grade Not Yet or Grade Not Yet Approved</h3><button class="btn btn-warning btn-bg menuDetails" id="btnBackFromInputScore">Back</button></div>');
            }

        });
    }
    
    function getDataScore() {

        var ScheduleID = '<?php echo $ScheduleID; ?>';
        var SemesterID = '<?php echo $SemesterID; ?>';
        var url = base_url_js+'api/__crudScore';
        var token = jwt_encode({action:'read',ScheduleID:ScheduleID,SemesterID:SemesterID},'UAP)(*');

        $.post(url,{token:token},function (jsonDataResult) {

            var dataMK = jsonDataResult.Course[0];

            $('#divScore').html('<h3><b>'+dataMK.MKCode+' - '+dataMK.MKNameEng+'</b></h3><input id="formScheduleID" value="'+ScheduleID+'" class="hide" hidden readonly />' +
                '<div class="form-group hide"><label>Total Assigment</label><select class="form-control" style="max-width: 140px;" id="formTotalAsg"></select></div>'+
                '    <div >'+
                '        <div class="table-responsive">'+
                '            <table id="tableScore" class="table table-striped table-bordered table-hover">'+
                '                <thead><tr>'+
                '                    <th rowspan="2" style="width: 1%;">No</th>'+
                '                    <th rowspan="2" style="width: 10%;">Students</th>'+
                '                    <th colspan="5">Assigment ( '+Grade_Assigment+' % )</th>'+
                '                    <th rowspan="2" style="width: 7%;">UTS<br/>( '+Grade_UTS+' % )</th>'+
                '                    <th rowspan="2" style="width: 7%;">UAS<br/>( '+Grade_UAS+' % )</th>'+
                '                    <th rowspan="2" style="width: 7%;">Score</th>'+
                '                    <th rowspan="2">Grade</th>'+
                '                    <th rowspan="2" style="width: 20%;">Status</th>'+
                '                </tr>' +
                '<tr>' +
                '                    <td style="width: 7%;">Assg 1 : ('+Grade_Assig1+' %)</td>'+
                '                    <td style="width: 7%;">Assg 2 : ('+Grade_Assig2+' %)</td>'+
                '                    <td style="width: 7%;">Assg 3 : ('+Grade_Assig3+' %)</td>'+
                '                    <td style="width: 7%;">Assg 4 : ('+Grade_Assig4+' %)</td>'+
                '                    <td style="width: 7%;">Assg 5 : ('+Grade_Assig5+' %)</td>'+
                '</tr>' +
                '</thead>'+
                '                <tbody id="dtRow"></tbody>'+
                '            </table>'+
                '        </div>'+
                '    </div>'+
                ''+
                '    <div style="text-align: right;margin-bottom: 15px;">'+
                '        <hr/>' +
                '<button class="btn btn-warning btn-bg hide" id="btnBackFromInputScore">Back</button>'+
                '        <button class="btn btn-bg btn-success" id="btnSaveScore">Save</button>'+
                '    </div>');


            for(var g=5;g>=1;g--){
                var selected = (g==parseInt(dataMK.TotalAssigment)) ? 'selected' : '';
                $('#formTotalAsg').append('<option value="'+g+'" '+selected+'>'+g+' Assigment</option>');
            }



            var jsonResult = jsonDataResult.Students;
            var tr = $('#dtRow');
            tr.empty();
            var no = 1;
            for(var i=0;i<jsonResult.length;i++){
                var dtsc = jsonResult[i];

                var sSpl = dtsc.Name.split(' ');
                var nameStd = (sSpl.length>3) ? sSpl[0]+' '+sSpl[1] : dtsc.Name ;

                var formAsg1 = (dtsc.Evaluasi1!=null) ? dtsc.Evaluasi1 : 0 ;
                var formAsg2 = (dtsc.Evaluasi2!=null) ? dtsc.Evaluasi2 : 0 ;
                var formAsg3 = (dtsc.Evaluasi3!=null) ? dtsc.Evaluasi3 : 0 ;
                var formAsg4 = (dtsc.Evaluasi4!=null) ? dtsc.Evaluasi4 : 0 ;
                var formAsg5 = (dtsc.Evaluasi5!=null) ? dtsc.Evaluasi5 : 0 ;
                var formUTS = (dtsc.UTS!=null) ? dtsc.UTS : 0 ;
                var formUAS = (dtsc.UAS!=null) ? dtsc.UAS : 0 ;

                var formScore = (dtsc.Score!=null) ? dtsc.Score : 0 ;
                var formGrade = (dtsc.Grade!=null) ? dtsc.Grade : 0 ;
                var formGradeValue = (dtsc.GradeValue!=null) ? dtsc.GradeValue : 0 ;


                var color = '';
                if(dtsc.Grade=='A' || dtsc.Grade=='A-') {
                    color = 'style="color:green;"';
                }
                else if(dtsc.Grade=='B+' || dtsc.Grade=='B' || dtsc.Grade=='B-'){
                    color = 'style="color:blue;"';
                }
                else if(dtsc.Grade=='C+' || dtsc.Grade=='C'){
                    color = 'style="color:yellow;"';
                }
                else if(dtsc.Grade=='D' || dtsc.Grade=='E'){
                    color = 'style="color:red;"';
                }

                var viewScore = (dtsc.Score!=null) ? '<b>'+dtsc.Score+'</b>' : '-';
                var viewGrade = (dtsc.Grade!=null) ? '<b '+color+'>'+dtsc.Grade+'</b>' : '-';

                var optStatus = '<select class="form-control" id="formStatus'+dtsc.NPM+'" data-id="'+dtsc.NPM+'">' +
                    '<option value="0">UTS Need Approval</option>' +
                    '<option value="1">UTS Approved</option>' +
                    '<option value="2">UAS Approved</option>' +
                    '</select>';

                tr.append('<tr>' +
                    '<th>'+(no++)+'</th>' +
                    '<th style="font-weight: normal;text-align: left;"><b>'+nameStd+'</b> ' +
                    '<br/> <i>'+dtsc.NPM+'</i>' +
                    '<input type="hide" class="hide" hidden readonly id="db_student_ID'+dtsc.NPM+'" value="'+dtsc.ID+'" /> ' +
                    '<input type="hide" class="hide" hidden readonly id="db_student'+dtsc.NPM+'" value="'+dtsc.DB_Student+'" /> ' +
                    '</th>' +
                    '                        <td><input class="form-control formScore formAsg formAsg1" style="width: 80px;" id="formAsg1'+dtsc.NPM+'" data-id="'+dtsc.NPM+'" value="'+formAsg1+'" type="number" /></td>' +
                    '                        <td><input class="form-control formScore formAsg formAsg2" style="width: 80px;" id="formAsg2'+dtsc.NPM+'" data-id="'+dtsc.NPM+'" value="'+formAsg2+'" type="number" /></td>' +
                    '                        <td><input class="form-control formScore formAsg formAsg3" style="width: 80px;" id="formAsg3'+dtsc.NPM+'" data-id="'+dtsc.NPM+'" value="'+formAsg3+'" type="number" /></td>' +
                    '                        <td><input class="form-control formScore formAsg formAsg4" style="width: 80px;" id="formAsg4'+dtsc.NPM+'" data-id="'+dtsc.NPM+'" value="'+formAsg4+'" type="number" /></td>' +
                    '                        <td><input class="form-control formScore formAsg formAsg5" style="width: 80px;" id="formAsg5'+dtsc.NPM+'" data-id="'+dtsc.NPM+'" value="'+formAsg5+'" type="number" /></td>' +
                    '                        <td><input class="form-control formScore" style="width: 80px;" id="formUTS'+dtsc.NPM+'" data-id="'+dtsc.NPM+'" value="'+formUTS+'" type="number" /></td>' +
                    '                        <td><input class="form-control formScore" style="width: 80px;" id="formUAS'+dtsc.NPM+'" data-id="'+dtsc.NPM+'" value="'+formUAS+'" type="number" /></td>' +
                    '                        <td style="text-align: center;"><span id="score'+dtsc.NPM+'">'+viewScore+'</span><input class="hide" hidden readonly id="formScoreValue'+dtsc.NPM+'" value="'+formScore+'" /></td>' +
                    '                        <td style="text-align: center;"><span id="grade'+dtsc.NPM+'">'+viewGrade+'</span>' +
                    '                               <input class="hide" hidden readonly id="formGrade'+dtsc.NPM+'" value="'+formGrade+'" />' +
                    '                               <input class="hide" hidden readonly id="formGradeValue'+dtsc.NPM+'" value="'+formGradeValue+'" />' +
                    '                       </td>' +
                    '                       <td>'+optStatus+'</td>' +
                    '                    </tr>');

                if(dtsc.Approval!=null && dtsc.Approval!='') {
                    $('#formStatus'+dtsc.NPM).val(dtsc.Approval);
                }


                dataIDStudyPlanning.push(dtsc.NPM);
            }


            disabledAssigment(parseInt(dataMK.TotalAssigment));
        });
        
    }
</script>

<script>
    $(document).on('change','#formTotalAsg',function () {
        var valu = $('#formTotalAsg').val();

        disabledAssigment(valu);
    });

    function disabledAssigment(valu) {
        $('.formAsg').prop('disabled',false);
        for(var d=(parseInt(valu)+1); d<=5;d++ ){
            $('.formAsg'+d).val(0).prop('disabled',true);
        }
        for(var i=0;i<dataIDStudyPlanning.length;i++){
            countScore(dataIDStudyPlanning[i]);
        }
    }


    $(document).on('keyup','.formScore',function () {
        var ID = $(this).attr('data-id');
        if($(this).val()>100){
            $(this).val(100);
        } else if($(this).val()<0){
            $(this).val(0);
        }
        countScore(ID);

    });
    $(document).on('change','.formScore',function () {
        var ID = $(this).attr('data-id');
        if($(this).val()>100){
            $(this).val(100);
        } else if($(this).val()<0){
            $(this).val(0);
        }
        countScore(ID);
    });
    $(document).on('blur','.formScore',function () {
        var ID = $(this).attr('data-id');
        if($(this).val()==''){
            $(this).val(0);
        }
        if($(this).val()>100){
            $(this).val(100);
        } else if($(this).val()<0){
            $(this).val(0);
        }
        countScore(ID);
    });

    $(document).on('click','#btnSaveScore',function () {

        loading_buttonSm('#btnSaveScore');
        $('#btnBackFromInputScore').prop('disabled',true);

        loading_modal_show();

        var formUpdate = [];
        for(var i=0;i<dataIDStudyPlanning.length;i++){
            var da = {
                DB_Student : $('#db_student'+dataIDStudyPlanning[i]).val(),
                ID : $('#db_student_ID'+dataIDStudyPlanning[i]).val(),
                dataForm : {
                    Evaluasi1 : $('#formAsg1'+dataIDStudyPlanning[i]).val(),
                    Evaluasi2 : $('#formAsg2'+dataIDStudyPlanning[i]).val(),
                    Evaluasi3 : $('#formAsg3'+dataIDStudyPlanning[i]).val(),
                    Evaluasi4 : $('#formAsg4'+dataIDStudyPlanning[i]).val(),
                    Evaluasi5 : $('#formAsg5'+dataIDStudyPlanning[i]).val(),
                    UTS : $('#formUTS'+dataIDStudyPlanning[i]).val(),
                    UAS : $('#formUAS'+dataIDStudyPlanning[i]).val(),
                    Score : $('#formScoreValue'+dataIDStudyPlanning[i]).val(),
                    Grade : $('#formGrade'+dataIDStudyPlanning[i]).val(),
                    GradeValue : $('#formGradeValue'+dataIDStudyPlanning[i]).val(),
                    Approval : $('#formStatus'+dataIDStudyPlanning[i]).val()
                }

            };

            formUpdate.push(da);
        }

        var ScheduleID = $('#formScheduleID').val();
        var TotalAssigment = $('#formTotalAsg').val();

        var url = base_url_js+'api/__crudScore';
        var token = jwt_encode({action:'update',ScheduleID:ScheduleID,TotalAssigment:TotalAssigment,formUpdate:formUpdate},'UAP)(*');
        $.post(url,{token:token},function (resultJson) {
            toastr.success('Score Saved','Success!');
            $('#btnSaveScore').prop('disabled',false).html('Save');
            $('#btnBackFromInputScore').prop('disabled',false);
            setTimeout(function () {
                loading_modal_hide();
            },1000);

        });

    });

    function countScore(ID) {
        var TotalAsg = $('#formTotalAsg').val();

        var TotalAsgValue = 0;
        var arryAssg = [Grade_Assig1,Grade_Assig2,Grade_Assig3,Grade_Assig4,Grade_Assig5];
        var arS = 0;
        for(var a=1;a<=TotalAsg;a++){
            var n = ($('#formAsg'+a+''+ID).val()!='') ? $('#formAsg'+a+''+ID).val() * (arryAssg[arS]/100) : 0;
            TotalAsgValue = TotalAsgValue + parseFloat(n);
            arS++;
        }

        // Misal => tugas 30%, UTS 35%, UAS 35%

        // var AvgAsg = (parseFloat(TotalAsgValue) / parseInt(TotalAsg)) * (Grade_Assigment/100);
        var AvgAsg = (parseFloat(TotalAsgValue)) * (Grade_Assigment/100);
        var AvgUTS = parseFloat($('#formUTS'+ID).val()) * (Grade_UTS/100);
        var AvgUAS = parseFloat($('#formUAS'+ID).val()) * (Grade_UAS/100);

        var TotalScore = AvgAsg + AvgUTS + AvgUAS;
        var Score = parseFloat(TotalScore).toFixed(2);
        $('#score'+ID).html('<b>'+Score+'</b>');
        $('#formScoreValue'+ID).val(Score);

        var url = base_url_js+'api/__crudScore';
        var token = jwt_encode({action:'grade',Score:Score},'UAP)(*');
        $.post(url,{token:token},function (jsonResult) {

            var color = '';
            if(jsonResult.Grade=='A' || jsonResult.Grade=='A-') {
                color = 'style="color:green;"';
            }
            else if(jsonResult.Grade=='B+' || jsonResult.Grade=='B' || jsonResult.Grade=='B-'){
                color = 'style="color:blue;"';
            }
            else if(jsonResult.Grade=='C+' || jsonResult.Grade=='C'){
                color = 'style="color:#dc8300;"';
            }
            else if(jsonResult.Grade=='D' || jsonResult.Grade=='E'){
                color = 'style="color:red;"';
            }

            $('#grade'+ID).html('<b '+color+'>'+jsonResult.Grade+'</b>');
            $('#formGrade'+ID).val(jsonResult.Grade);
            $('#formGradeValue'+ID).val(jsonResult.Score);

        });

    }
</script>
