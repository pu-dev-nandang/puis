
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
    <div class="col-md-12" style="margin-top: 20px;">
        <a href="<?php echo base_url('academic/transcript'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left margin-right"></i> Back to List</a>
        <hr/>
    </div>
</div>

<div class="row">

    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <div class="thumbnail" style="padding: 0px">
                    <span class="label-info" style="color: #ffffff;padding: 5px;padding-left:10px;padding-right:10px;font-weight: bold;"><i class="fa fa-cog margin-right"></i> Transcript & Ijazah</span>

                    <div style="margin: 15px">
                        <table class="table table-setting">
                            <tr>
                                <td style="width: 50%">Nomor Keputusan Pendirian Perguruan Tinggai</td>
                                <td style="width: 1%">:</td>
                                <td>
                                    <input id="formSTID" value="<?php echo $Transcript['ID'] ?>" class="hide" hidden readonly>
                                    <input id="formSTNumberUniv" value="<?php echo $Transcript['NumberUniv'] ?>" class="form-control formST" />
                                </td>
                            </tr>
                            <tr>
                                <td>Tempat Diterbitkan</td>
                                <td>:</td>
                                <td>
                                    <input id="formSTPlaceIssued" value="<?php echo $Transcript['PlaceIssued']; ?>" class="form-control formST">
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Diterbitkan</td>
                                <td>:</td>
                                <td>
                                    <input id="formSTDateIssuedValue" value="<?php echo $Transcript['DateIssued']; ?>" class="form-control hide" readonly>
                                    <input id="formSTDateIssued" data-desc="Issue" class="form-control formST">
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Yudisium</td>
                                <td>:</td>
                                <td>
                                    <input id="formSTDateOfYudisiumValue" value="<?php echo $Transcript['DateOfYudisium']; ?>" class="form-control hide" readonly>
                                    <input id="formSTDateOfYudisium" data-desc="Yudisium" class="form-control formST">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: right;">
                                    <button class="btn btn-success" id="btnSaveST">Save</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr/>
            </div>

            <div class="col-md-6">
                <div class="thumbnail" style="padding: 0px">
                    <span class="label-info" style="color: #ffffff;padding: 5px;padding-left:10px;padding-right:10px;font-weight: bold;"><i class="fa fa-cog margin-right"></i>  Graduation Honor</span>

                    <div style="margin: 5px;margin-top: 15px;">
                        <table class="table table-bordered" id="tableHonor">
                            <tr>
                                <th colspan="2" style="width: 20%">IPK</th>
                                <th rowspan="2" style="width: 25%">Predicate Indo</th>
                                <th rowspan="2" style="width: 20%">Predicate Eng</th>
                                <th rowspan="2" style="width: 2%">Act</th>
                            </tr>
                            <tr>
                                <th style="width: 13%">Start</th>
                                <th style="width: 13%">End</th>
                            </tr>

                            <?php
                            foreach ($Graduation AS $itemG){ ?>

                                <tr>
                                    <td><span class="spanGrade<?php echo $itemG['ID'] ?>" id="viewGStart<?php echo $itemG['ID']; ?>"><?php echo $itemG['IPKStart'] ?></span><input value="<?php echo $itemG['IPKStart'] ?>" class="form-control formFillG<?php echo $itemG['ID']; ?> hide" id="formIPKStart<?php echo $itemG['ID']; ?>"></td>
                                    <td><span class="spanGrade<?php echo $itemG['ID'] ?>" id="viewGEnd<?php echo $itemG['ID']; ?>"><?php echo $itemG['IPKEnd'] ?></span><input value="<?php echo $itemG['IPKEnd'] ?>" class="form-control formFillG<?php echo $itemG['ID']; ?> hide" id="formIPKEnd<?php echo $itemG['ID']; ?>"></td>
                                    <td><span class="spanGrade<?php echo $itemG['ID'] ?>" id="viewGDescription<?php echo $itemG['ID'];?>"><?php echo $itemG['Description'] ?></span><input value="<?php echo $itemG['Description'] ?>" class="form-control formFillG<?php echo $itemG['ID']; ?> hide" id="formDescription<?php echo $itemG['ID']; ?>"></td>
                                    <td><span class="spanGrade<?php echo $itemG['ID'] ?>" id="viewGDescriptionEng<?php echo $itemG['ID']; ?>"><?php echo $itemG['DescriptionEng'] ?></span><input value="<?php echo $itemG['DescriptionEng'] ?>" class="form-control formFillG<?php echo $itemG['ID']; ?> hide" id="formDescriptionEng<?php echo $itemG['ID']; ?>"></td>
                                    <td>
                                        <button class="btn btn-success btn-sm btnSaveG hide" id="btnSaveG<?php echo $itemG['ID']; ?>" data-id="<?php echo $itemG['ID']; ?>">Save</button>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-default dropdown-toggle" id="btnDropDown<?php echo $itemG['ID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-pencil-square-o"></i> <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="javascript:void(0);" class="btnEditG" data-id="<?php echo $itemG['ID']; ?>">Edit</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li class="disabled"><a href="javascript:void(0);" class="btnDelG disabled" disabled="disabled" data-id="<?php echo $itemG['ID']; ?>">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                            ?>

                            <!--                    <button class="btn btn-sm btn-default btn-default-danger"><i class="fa fa-trash"></i></button>-->
                        </table>

                    </div>
                </div>
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="thumbnail" style="padding: 0px">
                    <span class="label-info" style="color: #ffffff;padding: 5px;padding-left:10px;padding-right:10px;font-weight: bold;"><i class="fa fa-cog margin-right"></i> Temp. Transcript</span>
                    <div style="padding: 10px;">
                        <hr/>
                        <div class="form-group">
                            <label>No Transcript</label>
                            <input class="form-control" id="formTemp_No" value="<?php echo $TempTranscript['No']; ?>" />
                        </div>
                        <div class="form-group">
                            <label>Tempat Terbit</label>
                            <input class="form-control" id="formTemp_Place" value="<?php echo $TempTranscript['Place']; ?>" />
                        </div>
                        <div class="form-group">
                            <label>Tanggal Terbit</label>
                            <input id="formTemp_TsDateValue" value="<?php echo $TempTranscript['Date']; ?>" class="form-control hide" readonly>
                            <input id="formTemp_TsDate" data-desc="TempTS" class="form-control">
                        </div>
                        <hr/>
                        <div style="text-align: right;">
                            <button class="btn btn-success" id="btnSaveTemp_TS">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="thumbnail" style="padding: 0px;min-height: 100px;">
                    <span class="label-info" style="color: #ffffff;padding: 5px;padding-left:10px;padding-right:10px;font-weight: bold;"><i class="fa fa-cog margin-right"></i>  SKPI</span>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2" style="margin-top: 20px;">
                            <div class="well">
                                <div class="">
                                    <select class="form-control" id="filterSKPI">
                                        <option value="4">Higher Education System in Indonesia</option>
                                        <option value="5">Level of Education and Conditional of Learning</option>
                                        <option value="6">Semester Credit Unit and Duration of Study</option>
                                        <option disabled>-----------------</option>
                                        <option value="7">Indonesian Qualification Framework (KKNI)</option>
                                    </select>
                                </div>
                            </div>
                            <hr/>
                        </div>
                    </div>


                    <div class="row" style="padding: 10px;">
                        <div class="col-md-6">
                            <div class="text-center"><b>Indonesia</b></div>
                            <br/>
                            <textarea rows="5" class="form-control" id="formIndo"></textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center"><b>English</b></div>
                            <br/>
                            <textarea rows="5" class="form-control" id="formEng"></textarea>
                        </div>
                    </div>

                    <div class="row" style="padding: 10px;">
                        <div class="col-md-12 text-right">
                            <hr/>
                            <button class="btn btn-default btn-default-success" id="btnSaveSKPI">Save</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="thumbnail" style="padding: 0px">
            <span class="label-info" style="color: #ffffff;padding: 5px;padding-left:10px;padding-right:10px;font-weight: bold;"><i class="fa fa-cog margin-right"></i>  Education Program</span>

            <div style="margin: 5px;margin-top: 15px;">
                <table class="table table-bordered" id="tableEducation">
                    <tr>
                        <th style="width: 10%">Program Indo</th>
                        <th style="width: 10%">Program Eng</th>
                        <th style="width: 2%">Action</th>
                    </tr>

                    <?php foreach ($Education AS $itemE){ ?>
                        <tr>
                            <td><?php echo $itemE['Description']; ?></td>
                            <td>
                                <span id="viewDescriptionEng<?php echo $itemE['ID']; ?>"><?php echo $itemE['DescriptionEng']; ?></span>
                                <input class="form-control hide" id="formEDescriptionEng<?php echo $itemE['ID']; ?>" value="<?php echo $itemE['DescriptionEng']; ?>"></td>
                            <td>
                                <button class="btn btn-success btn-sm btnSaveEd hide" id="btnSaveEd<?php echo $itemE['ID']; ?>" data-id="<?php echo $itemE['ID']; ?>">Save</button>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-default dropdown-toggle" id="btnDropDownEd<?php echo $itemE['ID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-pencil-square-o"></i> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:void(0);" class="btnEditEd" data-id="<?php echo $itemE['ID']; ?>">Edit</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li class="disabled"><a href="javascript:void(0);" class="btnDelEd disabled" disabled="disabled" data-id="<?php echo $itemE['ID']; ?>">Delete</a></li>
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


<br />

<div class="row">
     <div class="col-md-8">
        <div class="thumbnail" style="padding: 0px">
            <span class="label-info" style="color: #ffffff;padding: 5px;padding-left:10px;padding-right:10px;font-weight: bold;"><i class="fa fa-cog margin-right"></i>  Study Program Accreditation</span>

            <div style="margin: 5px;margin-top: 15px;">
                <table class="table table-bordered" id="tableStudyAcc">
                    <tr>
                        <th style="width: 10%">Name Study Indo</th>
                        <th style="width: 10%">Name Study Eng</th>
                        <th style="width: 10%">Program</th>
                        <th style="width: 10%">Name Faculty</th>

                        <th style="width: 10%">Akreditasi Date </th>
                        <th style="width: 10%">No Akreditasi </th>
                        <th style="width: 2%">Action</th>
                    </tr>

                    <?php foreach ($ProgramStudy AS $itemX){ ?>
                        <tr>
                            <td><?php echo $itemX['Name']; ?></td>
                            <td><?php echo $itemX['NameEng']; ?></td>
                            <td><?php echo $itemX['NameLevel']; ?></td>
                            <td><?php echo $itemX['NameFak']; ?></td>
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
                                        <li><a href="javascript:void(0);" class="btnEditEdStudy" data-id="<?php echo $itemX['ID']; ?>">Edit</a></li>
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

    // ====== SKPI ======
    $('#filterSKPI').change(function () {
        loadDataSKPI();
    });

    $('#btnSaveSKPI').click(function () {
        var filterSKPI = $('#filterSKPI').val();

        if(filterSKPI!='' && filterSKPI!=null) {

            loading_buttonSm('#btnSaveSKPI');
            $('#formIndo,#formEng').prop('disabled',true);

            var formIndo = $('#formIndo').val();
            var formEng = $('#formEng').val();

            var data = {
                action: 'updateSKPI', ID: filterSKPI,
                dataUpdate : {
                    DescInd : formIndo,
                    DescEng : formEng,
                    UpdateBy : sessionNIP,
                    UpdateAt : dateTimeNow()
                }
            };
            var url = base_url_js + 'api/__crudConfigSKPI';
            var token = jwt_encode(data, 'UAP)(*');
            $.post(url,{token:token},function (result) {
                toastr.success('Date save','Success');
                setTimeout(function () {
                    $('#btnSaveSKPI').html('Save').prop('disabled',false);
                    $('#formIndo,#formEng').prop('disabled',false);
                },500);
            });

        }
    });

    function loadDataSKPI() {
        var filterSKPI = $('#filterSKPI').val();

        if(filterSKPI!='' && filterSKPI!=null){

            var url = base_url_js+'api/__crudConfigSKPI';
            var token = jwt_encode({action:'readData', ID:filterSKPI},'UAP)(*');

            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.length>0){
                    $('#formIndo').val(jsonResult[0].DescInd);
                    $('#formEng').val(jsonResult[0].DescEng);
                } else {
                    $('#formIndo').val('');
                    $('#formEng').val('');
                }


            });

        }
    }
    // ====== TUTUP SKPI ======

    // Save Setting Transcript
    $('#btnSaveST').click(function () {
        var ID = $('#formSTID').val();
        var formSTNumberUniv = $('#formSTNumberUniv').val();
        var formSTPlaceIssued = $('#formSTPlaceIssued').val();
        var formSTDateIssuedValue = $('#formSTDateIssuedValue').val();
        var formSTDateOfYudisiumValue = $('#formSTDateOfYudisiumValue').val();

        loading_buttonSm('#btnSaveST');
        $('.formST').prop('disabled',true);

        var data = {
            action : 'updateSettingTranscript',
            ID : ID,
            dataForm : {
                NumberUniv : formSTNumberUniv,
                PlaceIssued : formSTPlaceIssued,
                DateIssued : formSTDateIssuedValue,
                DateOfYudisium : formSTDateOfYudisiumValue
            }
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudTranscript';
        $.post(url,{token:token},function () {
            toastr.success('Data Saved','Success');
            setTimeout(function () {
                $('#btnSaveST').html('Save');
                $('.formST,#btnSaveST').prop('disabled',false);
            },500);
        });

    });

    // ====== Save Setting Temp Transcript ======
    $('#btnSaveTemp_TS').click(function () {

        var formTemp_No = $('#formTemp_No').val();
        var formTemp_Place = $('#formTemp_Place').val();
        var formTemp_TsDateValue = $('#formTemp_TsDateValue').val();

        loading_buttonSm('#btnSaveTemp_TS');
        $('#formTemp_No,#formTemp_Place,#formTemp_TsDate').prop('disabled',true);

        var data = {
            action : 'updateTempTranscript',
            dataForm : {
                No : formTemp_No,
                Place : formTemp_Place,
                Date : formTemp_TsDateValue,
                UpdateBy : sessionNIP,
                UpdateAt : dateTimeNow()
            }
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudTranscript';

        $.post(url,{token:token},function (result) {
            setTimeout(function () {
                $('#btnSaveTemp_TS').html('Save');
                $('#formTemp_No,#formTemp_Place,#formTemp_TsDate,#btnSaveTemp_TS').prop('disabled',false);
            },500);
        });


    });

    // Graduation Honor
    $(document).on('click','.btnEditG',function () {
        var ID = $(this).attr('data-id');
        $('#btnDropDown'+ID+', .spanGrade'+ID).addClass('hide');
        $('#btnSaveG'+ID+', .formFillG'+ID).removeClass('hide');
    });
    $(document).on('click','.btnSaveG',function () {

        var ID = $(this).attr('data-id');
        loading_buttonSm('#btnSaveG'+ID);

        $('#btnSaveG'+ID+', .formFillG'+ID).prop('disabled',true);

        var formIPKStart = $('#formIPKStart'+ID).val();
        var formIPKEnd = $('#formIPKEnd'+ID).val();
        var formDescription = $('#formDescription'+ID).val();
        var formDescriptionEng = $('#formDescriptionEng'+ID).val();
        var data = {
            action:'updateGrade',
            ID:ID,
            dataForm : {
                IPKStart : formIPKStart,
                IPKEnd : formIPKEnd,
                Description : formDescription,
                DescriptionEng : formDescriptionEng
            }

        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudTranscript';

        $.post(url,{token:token},function (result) {
            toastr.success('Data Saved','Success');
            setTimeout(function () {
                $('#btnSaveG'+ID+', .formFillG'+ID).prop('disabled',false);
                $('#btnDropDown'+ID+', .spanGrade'+ID).removeClass('hide');
                $('#btnSaveG'+ID+', .formFillG'+ID).addClass('hide');

                $('#viewGStart'+ID).text(formIPKStart);
                $('#viewGEnd'+ID).text(formIPKEnd);
                $('#viewGDescription'+ID).text(formDescription);
                $('#viewGDescriptionEng'+ID).text(formDescriptionEng);

                $('#btnSaveG'+ID).prop('disabled',false);
                $('#btnSaveG'+ID).html('Save');
            },500);
        });

    });

    // Education Level
    $(document).on('click','.btnEditEd',function () {
        var ID = $(this).attr('data-id');
        $('#btnDropDownEd'+ID+', #viewDescriptionEng'+ID).addClass('hide');
        $('#btnSaveEdStudy'+ID+', #formEDescriptionEng'+ID).removeClass('hide');
    });

    // Program Study Accreditation
    $(document).on('click','.btnEditEdStudy',function () {
        
        var tr = $(this).closest('tr');
        tr.find('td:eq(4)').html('<center><input type = "text" class = "form-control" id="formAkreditasiBANPTDate"></center>');
        tr.find('td:eq(5)').html('<input type = "text" class = "form-control" >');
        
        $('.btnDropDownEdStudy').addClass('hide');
        $('.btnSaveEdStudy').removeClass('hide');

        $('#formAkreditasiBANPTDate').datepicker({
              dateFormat : 'yy-mm-dd',
              changeMonth : true,
              changeYear : true,
              autoclose: true,
              todayHighlight: true,
              uiLibrary: 'bootstrap'
        });

    });

    $(document).on('click','.btnSaveEdStudy',function () {
        var tr = $(this).closest('tr');
        var dateacc = tr.find('td:eq(4)').find('input').val();
        var noacc = tr.find('td:eq(5)').find('input').val();
        var ID = $(this).attr('data-id');

        var data = {
            action : 'updateStudyAcc',
            Dateacc : dateacc,
            ID : ID,
            Noacc : noacc
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