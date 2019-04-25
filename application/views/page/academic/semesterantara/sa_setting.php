

<div class="row">
    <div class="col-md-6 col-md-offset-3">

        <div class="thumbnail" id="viewSettingAY" style="min-height: 100px;padding: 15px;">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th style="width: 25%;">Description</th>
                    <th style="width: 1%;"></th>
                    <th>Start</th>
                    <th>End</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Payment</td>
                    <td>:</td>
                    <td>
                        <input class="hide" id="ID">
                        <input class="form-control form-date" id="StartPayment">
                    </td>
                    <td>
                        <input class="form-control form-date" id="EndPayment">
                    </td>
                </tr>
                <tr>
                    <td>Maximum Credit</td>
                    <td>:</td>
                    <td>
                        <input class="form-control" id="MaxCredit" type="number">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>KRS</td>
                    <td>:</td>
                    <td>
                        <input class="form-control form-date" id="StartKRS">
                    </td>
                    <td>
                        <input class="form-control form-date" id="EndKRS">
                    </td>
                </tr>
                <tr>
                    <td>Kuliah</td>
                    <td>:</td>
                    <td>
                        <input class="form-control form-date" id="Start">
                    </td>
                    <td>
                        <input class="form-control form-date" id="End">
                    </td>
                </tr>
                <tr>
                    <td>UTS</td>
                    <td>:</td>
                    <td>
                        <input class="form-control form-date" id="StartUTS">
                    </td>
                    <td>
                        <input class="form-control form-date" id="EndUTS">
                    </td>
                </tr>
                <tr>
                    <td>Show UTS Schedule</td>
                    <td>:</td>
                    <td>
                        <input class="form-control form-date" id="ShowUTSSchedule">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Input Nilai UTS</td>
                    <td>:</td>
                    <td>
                        <input class="form-control form-date" id="StartInputUTS">
                    </td>
                    <td>
                        <input class="form-control form-date" id="EndInputUTS">
                    </td>
                </tr>
                <tr>
                    <td>Show Nilai UTS</td>
                    <td>:</td>
                    <td>
                        <input class="form-control form-date" id="ShowUTS">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Maximum Input Tugas</td>
                    <td>:</td>
                    <td>
                        <input class="form-control form-date" id="MaxInputTugas">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>UAS</td>
                    <td>:</td>
                    <td>
                        <input class="form-control form-date" id="StartUAS">
                    </td>
                    <td>
                        <input class="form-control form-date" id="EndUAS">
                    </td>
                </tr>
                <tr>
                    <td>Show UAS Schedule</td>
                    <td>:</td>
                    <td>
                        <input class="form-control form-date" id="ShowUASSchedule">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Input Nilai UAS</td>
                    <td>:</td>
                    <td>
                        <input class="form-control form-date" id="StartInputUAS">
                    </td>
                    <td>
                        <input class="form-control form-date" id="EndInputUAS">
                    </td>
                </tr>
                <tr>
                    <td>Show Nilai UAS</td>
                    <td>:</td>
                    <td>
                        <input class="form-control form-date" id="ShowUAS">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Update to transcript</td>
                    <td>:</td>
                    <td>
                        <input class="form-control form-date" id="UpdateTranscript">
                    </td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="4" style="text-align: right;">
                        <button class="btn btn-success" disabled id="btnUpdateAY">Save</button>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>

    </div>
</div>

<script>

    $(document).ready(function () {
        $( ".form-date" )
            .datepicker({
                showOtherMonths:true,
                autoSize: true,
                dateFormat: 'dd MM yy',
                // minDate: new Date(moment().year(),moment().month(),moment().date()),
                onSelect : function () {
                    // var data_date = $(this).datepicker("getDate").split(' ');
                    // var nextelement = $(this).attr('nextelement');
                    // nextDatePick(data_date,nextelement);
                }
            });

        loadSAAcademicYear();



    });
    
    function loadSAAcademicYear() {

        var data = {
            action : 'loadSettingSAAcademicYear',
            SASemesterID : '<?=$SASemesterID; ?>',
        };
        
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudSemesterAntara';
        
        $.post(url,{token:token},function (jsonResult) {
            console.log(jsonResult);
            if(jsonResult.length>0){
                var d = jsonResult[0];
                $('#ID').val(d.ID);

                if(d.MaxCredit!=null && d.MaxCredit!='' && d.MaxCredit!=0){
                    $('#MaxCredit').val(d.MaxCredit);

                    $('#StartPayment').datepicker('setDate',new Date(d.StartPayment));
                    $('#EndPayment').datepicker('setDate',new Date(d.EndPayment));
                    $('#StartKRS').datepicker('setDate',new Date(d.StartKRS));
                    $('#EndKRS').datepicker('setDate',new Date(d.EndKRS));
                    $('#Start').datepicker('setDate',new Date(d.Start));
                    $('#End').datepicker('setDate',new Date(d.End));
                    $('#StartUTS').datepicker('setDate',new Date(d.StartUTS));
                    $('#EndUTS').datepicker('setDate',new Date(d.EndUTS));
                    $('#ShowUTSSchedule').datepicker('setDate',new Date(d.ShowUTSSchedule));
                    $('#ShowUTS').datepicker('setDate',new Date(d.ShowUTS));

                    $('#StartUAS').datepicker('setDate',new Date(d.StartUAS));
                    $('#EndUAS').datepicker('setDate',new Date(d.EndUAS));
                    $('#ShowUASSchedule').datepicker('setDate',new Date(d.ShowUASSchedule));
                    $('#ShowUAS').datepicker('setDate',new Date(d.ShowUAS));

                    $('#UpdateTranscript').datepicker('setDate',new Date(d.UpdateTranscript));

                    $('#StartInputUTS').datepicker('setDate',new Date(d.StartInputUTS));
                    $('#EndInputUTS').datepicker('setDate',new Date(d.EndInputUTS));
                    $('#StartInputUAS').datepicker('setDate',new Date(d.StartInputUAS));
                    $('#EndInputUAS').datepicker('setDate',new Date(d.EndInputUAS));
                    $('#MaxInputTugas').datepicker('setDate',new Date(d.MaxInputTugas));
                }



                $('#btnUpdateAY').prop('disabled',false);
            } else {
                $('#viewSettingAY').html('-- Semester Antara Not Set --');
                $('#btnUpdateAY').prop('disabled',true);
            }
        });


    }
    
    $('#btnUpdateAY').click(function () {

        var StartPayment = $('#StartPayment').datepicker("getDate");
        var EndPayment = $('#EndPayment').datepicker("getDate");
        var MaxCredit = $('#MaxCredit').val();
        var StartKRS = $('#StartKRS').datepicker("getDate");
        var EndKRS = $('#EndKRS').datepicker("getDate");
        var Start = $('#Start').datepicker("getDate");
        var End = $('#End').datepicker("getDate");
        var StartUTS = $('#StartUTS').datepicker("getDate");
        var EndUTS = $('#EndUTS').datepicker("getDate");
        var StartInputUTS = $('#StartInputUTS').datepicker("getDate");
        var EndInputUTS = $('#EndInputUTS').datepicker("getDate");
        var ShowUTSSchedule = $('#ShowUTSSchedule').datepicker("getDate");
        var ShowUTS = $('#ShowUTS').datepicker("getDate");
        var MaxInputTugas = $('#MaxInputTugas').datepicker("getDate");
        var StartUAS = $('#StartUAS').datepicker("getDate");
        var EndUAS = $('#EndUAS').datepicker("getDate");
        var StartInputUAS = $('#StartInputUAS').datepicker("getDate");
        var EndInputUAS = $('#EndInputUAS').datepicker("getDate");
        var ShowUASSchedule = $('#ShowUASSchedule').datepicker("getDate");
        var ShowUAS = $('#ShowUAS').datepicker("getDate");
        var UpdateTranscript = $('#UpdateTranscript').datepicker("getDate");

        
        

        if(StartPayment!='' && StartPayment!=null &&
            EndPayment!='' && EndPayment!=null &&
        MaxCredit!='' && MaxCredit!=null &&
        StartKRS!='' && StartKRS!=null &&
        EndKRS!='' && EndKRS!=null &&
        Start!='' && Start!=null &&
        End!='' && End!=null &&
        StartUTS!='' && StartUTS!=null &&
        EndUTS!='' && EndUTS!=null &&
        ShowUTS!='' && ShowUTS!=null &&
        StartUAS!='' && StartUAS!=null &&
        EndUAS!='' && EndUAS!=null &&
        ShowUAS!='' && ShowUAS!=null &&
        UpdateTranscript!='' && UpdateTranscript!=null &&
            StartInputUTS != '' && StartInputUTS!=null &&
            EndInputUTS != '' && EndInputUTS!=null &&
            StartInputUAS !='' && StartInputUAS!=null &&
            EndInputUAS !='' && EndInputUAS!=null &&
            MaxInputTugas !='' && MaxInputTugas!=null &&
            ShowUTSSchedule !='' && ShowUTSSchedule!=null &&
            ShowUASSchedule !='' && ShowUASSchedule!=null
        ){

            loading_button('#btnUpdateAY');
            $('#form-control').prop('disabled',true);

            var dataForm = {
                StartPayment : moment(StartPayment).format('YYYY-MM-DD'),
                EndPayment : moment(EndPayment).format('YYYY-MM-DD'),
                MaxCredit : MaxCredit,
                StartKRS : moment(StartKRS).format('YYYY-MM-DD'),
                EndKRS : moment(EndKRS).format('YYYY-MM-DD'),
                Start : moment(Start).format('YYYY-MM-DD'),
                End : moment(End).format('YYYY-MM-DD'),
                StartUTS : moment(StartUTS).format('YYYY-MM-DD'),
                EndUTS : moment(EndUTS).format('YYYY-MM-DD'),
                ShowUTSSchedule : moment(ShowUTSSchedule).format('YYYY-MM-DD'),
                ShowUTS : moment(ShowUTS).format('YYYY-MM-DD'),
                StartUAS : moment(StartUAS).format('YYYY-MM-DD'),
                EndUAS : moment(EndUAS).format('YYYY-MM-DD'),
                ShowUASSchedule : moment(ShowUASSchedule).format('YYYY-MM-DD'),
                ShowUAS : moment(ShowUAS).format('YYYY-MM-DD'),
                UpdateTranscript : moment(UpdateTranscript).format('YYYY-MM-DD'),
                StartInputUTS : moment(StartInputUTS).format('YYYY-MM-DD'),
                EndInputUTS : moment(EndInputUTS).format('YYYY-MM-DD'),
                StartInputUAS : moment(StartInputUAS).format('YYYY-MM-DD'),
                EndInputUAS : moment(EndInputUAS).format('YYYY-MM-DD'),
                MaxInputTugas : moment(MaxInputTugas).format('YYYY-MM-DD')
            };

            var ID = $('#ID').val();

            var data = {
                action : 'updateSAAcademicyear',
                SASemesterID : '<?=$SASemesterID; ?>',
                ID : ID,
                dataForm : dataForm
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api2/__crudSemesterAntara';
            $.post(url,{token:token},function (result) {

                toastr.success('Data saved','Success');
                setTimeout(function (args) {
                    $('#form-control,#btnUpdateAY').prop('disabled',false);
                    $('#btnUpdateAY').html('Save');
                },500);

            });

        } else {
            toastr.warning('All form required','Warning');
        }


    });

</script>