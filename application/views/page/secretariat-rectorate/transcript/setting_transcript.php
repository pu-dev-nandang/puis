
<style>
    .table-setting tr td {
       font-weight: bold;
    }
    #tableHonor tr th{
        text-align: center;
        background: #607D8B;
        color: #FFFFFF;
    }
    #tableHonor tr td, #tableEducation tr td {
        text-align: center;
    }

    #tableEducation tr th{
        text-align: center;
        background: #607D8B;
        color: #FFFFFF;
    }
</style>
<div class="row">
     <div class="col-md-12">
        <div class="thumbnail" style="padding: 0px">
            <span class="label-info" style="color: #ffffff;padding: 5px;padding-left:10px;padding-right:10px;font-weight: bold;"><i class="fa fa-cog margin-right"></i>  Study Program Accreditation</span>

            <div style="margin: 5px;margin-top: 15px;">
                <table class="table table-bordered" id="tableStudyAcc">
                    <tr>
                        <th style="width: 10%">Name Study Indo</th>
                        <th style="width: 10%">Name Study Eng</th>
                        <th style="width: 10%">Program</th>
                        <th style="width: 10%">Faculty Name</th>

                        <th style="width: 10%">Accreditation Status</th>
                        <th style="width: 10%">Accreditation Date </th>
                        <th style="width: 10%">Accreditation </th>
                        <th style="width: 2%">Action</th>
                    </tr>

                    <?php foreach ($ProgramStudy AS $itemX){ ?>
                        <tr>
                            <td><?php echo $itemX['Name']; ?></td>
                            <td><?php echo $itemX['NameEng']; ?></td>
                            <td><?php echo $itemX['NameLevel']; ?></td>
                            <td><?php echo $itemX['NameFak']; ?></td>
                            <td><?php echo $itemX['accreditation']; ?></td>

                            <td>
                                <span id="SKBANPTDate<?php echo $itemX['ID']; ?>"><?php echo $itemX['SKBANPTDate']; ?></span>
                                <!--<input class="form-control frmdatepicker hide" id="formAkreditasiBANPTDate<?php //echo $itemX['ID']; ?>" value="<?php //echo $itemX['AkreditasiBANPTDate']; ?>"> -->
                            </td>
                            <td>
                                <span id="NoSKBANPT<?php echo $itemX['ID']; ?>"><?php echo $itemX['NoSKBANPT']; ?></span>
                                <!-- <input class="form-control hide" id="formNoAkreditasiBANPT<?php //echo $itemX['NoAkreditasiBANPT']; ?>" value="<?php //echo $itemX['NoAkreditasiBANPT']; ?>"> -->
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm btnSaveEdStudy hide" id="btnSaveEdStudy<?php echo $itemX['ID']; ?>" data-id="<?php echo $itemX['ID']; ?>">Save</button>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-default dropdown-toggle btnDropDownEdStudy" id="btnDropDownEdStudy<?php echo $itemX['ID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-pencil-square-o"></i> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:void(0);" class="btnEditEdStudy" data-accreditation="<?= $itemX['accreditationID']; ?>" data-id="<?php echo $itemX['ID']; ?>">Edit</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li class="disabled"><a href="javascript:void(0);" class="btnDelEd disabled" disabled="disabled" data-id="<?php echo $itemX['ID']; ?>">Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>

                    <!--                    <button class="btn btn-sm btn-default btn-default-danger"><i class="fa fa-trash"></i></button>-->
                </table>

            </div>
        </div>

    </div>

</div>



<script>
    $(document).ready(function () {

        $('.frmdatepicker').datepicker({
          dateFormat : 'yy-mm-dd',
          changeMonth : true,
          changeYear : true,
          autoSize: true,
          autoclose: true,
          todayHighlight: true
          //uiLibrary: 'bootstrap'
        });

        $( "#formSTDateIssued,#formSTDateOfYudisium,#formTemp_TsDate" )
            .datepicker({
                showOtherMonths:true,
                autoSize: true,
                dateFormat: 'dd MM yy',
                onSelect : function () {
                    var data_date = $(this).val().split(' ');
                    var CustomMoment = moment(data_date[2]+'-'+(parseInt(convertDateMMtomm(data_date[1])) + 1)+'-'+data_date[0]).format('YYYY-MM-DD');
                    var Desc = $(this).attr('data-desc');

                    var elm = '#formSTDateOfYudisiumValue';
                    if(Desc=='Issue') {
                        elm = '#formSTDateIssuedValue';
                    } else if(Desc=='TempTS'){
                        elm = '#formTemp_TsDateValue';
                    }

                    $(elm).val(CustomMoment);
                }
            });
        $('#formSTDateIssued').datepicker('setDate',new Date("<?php echo $Transcript['DateIssued']; ?>"));
        $('#formSTDateOfYudisium').datepicker('setDate',new Date("<?php echo $Transcript['DateOfYudisium']; ?>"));
        $('#formTemp_TsDate').datepicker('setDate',new Date("<?php echo $TempTranscript['Date']; ?>"));

        var loadFirsSKPI = setInterval(function () {

            var filterSKPI = $('#filterSKPI').val();

            if(filterSKPI!='' && filterSKPI!=null){
                loadDataSKPI();
                clearInterval(loadFirsSKPI);
            }

        },1000);

    });


    // Program Study Accreditation
    $(document).on('click','.btnEditEdStudy',function () {

        var ID = $(this).attr('data-id');
        var tr = $(this).closest('tr');
        tr.find('td:eq(4)').html('<select id="AccreditationID'+ID+'" class = "form-control" ><option></option></select>');
        tr.find('td:eq(5)').html('<input type = "text" class = "form-control" value="'+tr.find('td:eq(5)').text().trim()+'" id="SKBANPTDate_'+ID+'">');
        tr.find('td:eq(6)').html('<input type = "text" class = "form-control" value="'+tr.find('td:eq(6)').text().trim()+'" id="NoSKBANPT_'+ID+'">');

        var IDAccreditation = $(this).attr('data-accreditation');

        loadSelectOptionAccreditation('#AccreditationID'+ID,IDAccreditation);

        $('#btnDropDownEdStudy'+ID).addClass('hide');
        $('#btnSaveEdStudy'+ID).removeClass('hide');


    });

    $(document).on('click','.btnSaveEdStudy',function () {

        var ID = $(this).attr('data-id');

        var AccreditationID = $('#AccreditationID'+ID).val();
        var SKBANPTDate = $('#SKBANPTDate_'+ID).val();
        var NoSKBANPT = $('#NoSKBANPT_'+ID).val();



        var data = {
            action : 'updateStudyAcc',
            ID : ID,
            dataForm : {
                AccreditationID : AccreditationID,
                SKBANPTDate : SKBANPTDate,
                NoSKBANPT : NoSKBANPT
            }
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudTranscript';

        $.post(url,{token:token},function (result) {
            toastr.success('Data Saved','Success');
            setTimeout(function () {
                window.location.href = '';
            },500);
        });
    });


    $(document).on('click','.btnSaveEd',function () {
        var ID = $(this).attr('data-id');
        loading_buttonSm('#btnSaveEd'+ID);
        $('#formEDescriptionEng'+ID).prop('disabled',true);
        var formEDescriptionEng = $('#formEDescriptionEng'+ID).val();

        var data = {
            action : 'updateEducation',
            ID : ID,
            DescriptionEng : formEDescriptionEng
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudTranscript';

        $.post(url,{token:token},function (result) {
            toastr.success('Data Saved','Success');
            setTimeout(function () {
                $('#formEDescriptionEng'+ID+', #btnSaveEd'+ID).prop('disabled',false);
                $('#btnSaveEd'+ID).html('Save');

                $('#viewDescriptionEng'+ID).text(formEDescriptionEng);

                $('#btnDropDownEd'+ID+', #viewDescriptionEng'+ID).removeClass('hide');
                $('#btnSaveEd'+ID+', #formEDescriptionEng'+ID).addClass('hide');

            },500);
        });

    });
</script>
