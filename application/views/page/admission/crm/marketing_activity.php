
<style>
    #formStart, #formEnd {
        background: #ffffff;
        color: #333333;
        cursor: pointer;
    }

    #tableMA tr th {
        text-align: center;
        background: #607d8b;
        color: #ffffff;
    }
</style>

<div class="row">

    <div class="col-md-3" style="border-right: 1px solid #CCCCCC;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create / Update Form Marketing Activity</h4>
            </div>
            <div class="panel-body">
                <input class="hide" id="formID"/>
                <div class="form-group">
                    <label>Title</label>
                    <input class="form-control" id="formTitle"/>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea rows="5" class="form-control" id="formDescription"></textarea>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Start</label>
                            <input class="form-control" id="formStart" readonly>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>End</label>
                            <input class="form-control" id="formEnd" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Time Start</label>
                            <div id="div_formTime" data-no="1" class="input-group div_formTime">
                                <input data-format="hh:mm" type="text" id="formTimeStart" class="form-control form-attd formtime" value="00:00"/>
                                <span class="add-on input-group-addon">
                                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Time End</label>
                            <div id="div_formTime" data-no="1" class="input-group div_formTime">
                                <input data-format="hh:mm" type="text" id="formTimeEnd" class="form-control form-attd formtime" value="00:00"/>
                                <span class="add-on input-group-addon">
                                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    <label>Price</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Rp</span>
                        <input type="text" class="form-control" id="formPrice">
                    </div>
                </div>

                <div class="form-group">
                    <label>Comment</label>
                    <textarea class="form-control" rows="3" id="formComment"></textarea>
                </div>

                <div class="form-group">
                    <label>Participants</label>
                    <div id="viewP">
                        <select class="select2-select-00 full-width-fix"
                                size="5" multiple id="formParticipants"></select>
                    </div>


                </div>
            </div>

            <div class="panel-footer" style="text-align: right;">
                <button class="btn btn-success" id="btnSaveFormMA">Save</button>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="well">
                    <div class="row">
                        <div class="col-md-7">
                            <select class="form-control filterMA" id="filterMonth"></select>
                        </div>
                        <div class="col-md-5">
                            <select class="form-control filterMA" id="filterYear"></select>
                        </div>
                    </div>
                </div>
                <hr/>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped" id="tableMA">
                    <thead>
                    <tr>
                        <th style="width: 1%;">No</th>
                        <th>Event</th>
                        <th style="width: 30%;">Description</th>
                        <th style="width: 10%;"><i class="fa fa-cog"></i></th>
                    </tr>
                    </thead>
                    <tbody id="listMA">
                    <tr>
                        <td colspan="4">Loading data..</td>
                    </tr>
                    </tbody>
                </table>
                <textarea id="viewListMA" class="hide"></textarea>
            </div>
        </div>
    </div>

</div>

<script>

    $(document).ready(function () {

        loading_modal_show();

        // $('#').mask('0.000.000.000', {reverse: true});
        $('#formPrice').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
        $('#formPrice').maskMoney('mask', '9894');


        loadSelectOptionEmployeesSingle('#formParticipants','');
        $('#formParticipants').select2({allowClear: true});

        $( "#formStart,#formEnd")
            .datepicker({
                showOtherMonths:true,
                autoSize: true,
                dateFormat: 'dd M yy',
                // minDate: new Date(moment().year(),moment().month(),moment().date()),
                onSelect : function () {
                    // var data_date = $(this).val().split(' ');
                    // var nextelement = $(this).attr('nextelement');
                    // nextDatePick(data_date,nextelement);
                }
            });

        $('.div_formTime').datetimepicker({
            pickDate: false,
            pickSeconds : false
        })
            .on('changeDate', function(e) {

            });


        loadSelectOptionMonthYear_MA('#filterMonth','#filterYear');

        var firstLoad = setInterval(function () {

            var filterMonth = $('#filterMonth').val();
            var filterYear = $('#filterYear').val();

            if(filterMonth!='' && filterMonth!=null &&
                filterYear!='' && filterYear!=null){
                loadMarketingAct();
                clearInterval(firstLoad);
            }

        },1000);


        setTimeout(function () {
            loading_modal_hide();
        },1000);



    });

    $('#btnSaveFormMA').click(function () {

        var formID = $('#formID').val();
        var formTitle = $('#formTitle').val();
        var formDescription = $('#formDescription').val();
        var formStart = $('#formStart').datepicker("getDate");
        var formEnd = $('#formEnd').datepicker("getDate");
        var formTimeStart = $('#formTimeStart').val();
        var formTimeEnd = $('#formTimeEnd').val();
        var formPrice = $('#formPrice').val();
        var formComment = $('#formComment').val();

        var formParticipants = $('#formParticipants').val();



        if(formTitle!='' && formTitle!=null &&
        formDescription!='' && formDescription!=null &&
        formStart!='' && formStart!=null &&
        formEnd!='' && formEnd!=null &&
        formPrice!='' && formPrice!=null &&
            formTimeStart!='' && formTimeStart!=null &&
            formTimeEnd!='' && formTimeEnd!=null &&
            formParticipants!='' && formParticipants!=null){

            loading_button('#btnSaveFormMA');

            var data = {
                action : 'ins_MA',
                ID : formID,
                dataForm : {
                    Title : formTitle,
                    Description : formDescription,
                    Start : moment(formStart).format('YYYY-MM-DD'),
                    End : moment(formEnd).format('YYYY-MM-DD'),
                    TimeStart : formTimeStart,
                    TimeEnd : formTimeEnd,
                    Price : clearDotMaskMoney(formPrice),
                    Comment : formComment,
                    CreatedBy : sessionNIP
                },
                Participants : formParticipants
            };


            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'rest2/__crudMarketingActivity';

            $.post(url,{token:token},function (result) {

                loadMarketingAct();

                toastr.success('Data saved','Success');

                $('#formID').val('');
                $('#formTitle').val('');
                $('#formDescription').val('');
                $('#formStart').val('');
                $('#formEnd').val('');
                $('#formTimeStart').val('00:00');
                $('#formTimeEnd').val('00:00');
                $('#formPrice').val('');
                $('#formComment').val('');


                $('#formParticipants').val(null).trigger('change');

                setTimeout(function () {
                    $('#btnSaveFormMA').html('Save').prop('disabled',false);
                },1000);
            })


        } else {
            toastr.warning('Form required','Warning');
        }

    });

    function loadMarketingAct() {

        var filterMonth = $('#filterMonth').val();
        var filterYear = $('#filterYear').val();

        if(filterMonth!='' && filterMonth!=null &&
            filterYear!='' && filterYear!=null){

            var data = {
                action : 'filter_MA',
                Month : filterMonth,
                Year : filterYear
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'rest2/__crudMarketingActivity';

            $.post(url,{token:token},function (jsonResult) {

                $('#listMA').empty();
                if(jsonResult.length>0){
                    $('#viewListMA').val(JSON.stringify(jsonResult));
                    $.each(jsonResult,function (i,v) {

                        var dateEv = moment(v.Start).format('dddd, DD MMM YYYY')+' - '+moment(v.End).format('dddd, DD MMM YYYY');

                        var viewtime = '<span class="label label-default">'+dateEv+'</span> <span class="label label-default">'+v.TimeStart.substr(0,5)+' - '+v.TimeEnd.substr(0,5)+'</span>';

                        var Participants = v.Participants;
                        var p = '';
                        $.each(Participants,function (i2,v2) {
                            var koma = (i2!=0) ? ', ' : '';
                            p = p +''+koma+''+v2.Name;
                        });

                        var comment = (v.Comment!=null && v.Comment!='') ? '<div style="background: lightyellow;padding: 5px;margin-top: 15px;border: 1px solid orangered;"><b>Comment :</b> '+v.Comment+'</div>' : '';

                        $('#listMA').append('<tr>' +
                            '<td>'+(i+1)+'</td>' +
                            '<td><b>'+v.Title+'</b>' +
                            '<div><span class="label label-success">'+formatRupiah(v.Price)+'</span> | '+viewtime+'</div><p class="help-block">'+p+'</p></td>' +
                            '<td>'+v.Description+' '+comment+'</td>' +
                            '<td><button class="btn btn-default btn-sm btnEdit" data-id="'+v.ID+'"><i class="fa fa-edit"></i></button> <button class="btn btn-danger btn-sm btnRemove" data-id="'+v.ID+'"><i class="fa fa-trash"></i></button></td>' +
                            '</tr>');
                    });
                } else {
                    $('#viewListMA').html('<tr>' +
                        '                        <td colspan="4">Data not yet</td>' +
                        '                    </tr>');
                }
            });

        }

    }

    $(document).on('click','.btnEdit',function () {

        var viewListMA = $('#viewListMA').val();

        var dataMA = JSON.parse(viewListMA);
        var ID = $(this).attr('data-id');

        var result = $.grep(dataMA, function(e){ return e.ID == ID; });

        var d = result[0];

        $('#formID').val(d.ID);
        $('#formTitle').val(d.Title);
        $('#formDescription').val(d.Description);

        $('#formStart').datepicker('setDate',new Date(d.Start));
        $('#formEnd').datepicker('setDate',new Date(d.End));

        $('#formTimeStart').val(d.TimeStart);
        $('#formTimeEnd').val(d.TimeEnd);

        $('#formPrice').val(parseFloat(d.Price));
        $('#formPrice').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
        $('#formPrice').maskMoney('mask', '9894');

        $('#formComment').val(d.Comment);


        var Participants = d.Participants;
        var pp = [];
        if(Participants.length>0){
            for(var i=0;i<Participants.length;i++){
                pp.push(''+Participants[i].NIP);
            }
        }

        $('#formParticipants').select2('val',pp);

    });

    $(document).on('click','.btnRemove',function () {

        if(confirm('Are you sure to remove?')){
            var ID = $(this).attr('data-id');

            var data = {
                action : 'remove_MA',
                ID : ID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'rest2/__crudMarketingActivity';

            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.Status=='1' || jsonResult.Status==1){
                    loadMarketingAct();
                    toastr.success('Data removed','Success');
                } else {
                    toastr.warning('Data can not removed','Warning');
                }

            });

        }

    });

    $(document).on('change','.filterMA',function () {
        loadMarketingAct();
    });
</script>