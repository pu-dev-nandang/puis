
<style>
    .form-judiciums .form-control[readonly] {
        cursor: cell;
        background-color: #fff;
        color: #333;
    }
</style>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-6 col-md-offset-3">
        <div class="well">
            <div class="row">
                <div class="col-md-8">
                    <label>Programme Study</label>
                    <select class="form-control" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                        <option disabled>------------------------------------------</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Periode Judiciums</label>
                    <select class="form-control" id="filterJudiciumsYear"></select>
                </div>
            </div>

        </div>
        <hr/>
    </div>
    <div class="col-md-3" style="text-align: right;">
        <button class="btn btn-default" id="btnJudiciumsSetting"><i class="fa fa-cog margin-right"></i> Judiciums Setting</button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-centre table-bordered">
            <thead>
            <tr>
                <th style="width: 1%;">No</th>
                <th style="width: 15%;">Student</th>
                <th>Prodi</th>
            </tr>
            </thead>
            <tbody id="dataProdi"></tbody>
        </table>
    </div>
</div>

<script>

    $(document).ready(function () {
        loadSelectOptionBaseProdi('#filterBaseProdi','');
        loadSelectOptionJudiciumsYear('#filterJudiciumsYear','');

        var firsLoad = setInterval(function () {
            var filterJudiciumsYear = $('#filterJudiciumsYear').val();
            if(filterJudiciumsYear!='' && filterJudiciumsYear!=null){
                loadDataParticipant();
                clearInterval(firsLoad);
            }
        },1000);
    });

    function loadDataParticipant(){
        var filterJudiciumsYear = $('#filterJudiciumsYear').val();
        var filterBaseProdi = $('#filterBaseProdi').val();
        if(filterJudiciumsYear!='' && filterJudiciumsYear!=null){

            var ProdiID = (filterBaseProdi!=null && filterBaseProdi!='') ? filterBaseProdi.split('.')[0] : '';

            var data = {
                action : 'loadDataParticipantOfJudiciums',
                ProdiID : ProdiID,
                Year : filterJudiciumsYear
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudYudisium';

            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {
                        $('#dataProdi').append('<tr>' +
                            '<td>'+(i+1)+'</td>' +
                            '<td style="text-align: left;">'+v.Name+'<br/>'+v.NPM+'</td>' +
                            '<td style="text-align: left;">'+v.ProdiEng+'</td>' +
                            '</tr>');
                    });
                } else {
                    $('#dataProdi').html('<tr><td colspan="3">Data not yet</td></tr>');
                }

            });

        }
    }


    // ===== Setting Judiciums =========
    $('#btnJudiciumsSetting').click(function () {

        $('#GlobalModalLarge .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Judiciums Setting</h4>');

        var htmlss = '<div class="row">' +
            '    <div class="col-md-3">' +
            '        <div class="well form-judiciums">' +
            '        <div class="form-group">' +
            '            <label>Title</label>' +
            '            <input class="hide" id="formID">' +
            '            <input class="form-control" id="formTitle" type="text">' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Year</label>' +
            '            <input class="form-control" id="formYear" type="number">' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Judiciums Date</label>' +
            '           <input type="text" id="formJudiciumsDate" name="regular" class="form-control" readonly>' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Graduation Date</label>' +
            '            <input type="text" id="formGraduationDate" name="regular" class="form-control" readonly>' +
            '        </div>' +
            '        <div class="form-group" style="text-align: right;">' +
            '            <button class="btn btn-primary" id="btnSaveJudiciums">Save</button>' +
            '        </div>' +
            '    </div>' +
            '    </div>' +
            '    <div class="col-md-9">' +
            '        <table class="table table-striped table-centre">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Title</th>' +
            '                <th style="width: 7%;">Year</th>' +
            '                <th>Date</th>' +
            '                <th style="width: 1%;"><i class="fa fa-cog"></i></th>' +
            '            </tr>' +
            '            </thead>' +
            '           <tbody id="loadDataJudiciums"></tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>';

        $('#GlobalModalLarge .modal-body').html(htmlss);

        loadDataJudiciums();


        $("#formJudiciumsDate, #formGraduationDate")
            .datepicker({
                showOtherMonths:true,
                autoSize: true,
                dateFormat: 'dd MM yy',
                // minDate: new Date(moment().year(),moment().month(),moment().date()),
                onSelect : function () {
                    // var data_date = $(this).val().split(' ');
                    // var nextelement = $(this).attr('nextelement');
                    // nextDatePick(data_date,nextelement);
                }
            });

        $('#GlobalModalLarge .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModalLarge').on('shown.bs.modal', function () {
            $('#formSimpleSearch').focus();
        })

        $('#GlobalModalLarge').modal({
            'show' : true,
            'backdrop' : 'static'
        });


        $('#btnSaveJudiciums').click(function () {

            var ID = $('#formID').val();
            var Title = $('#formTitle').val();
            var Year = $('#formYear').val();
            var JudiciumsDate = ($('#formJudiciumsDate').datepicker("getDate")!=null) ? moment($('#formJudiciumsDate').datepicker("getDate")).format('YYYY-MM-DD') : '';
            var GraduationDate = ($('#formGraduationDate').datepicker("getDate")!=null) ? moment($('#formGraduationDate').datepicker("getDate")).format('YYYY-MM-DD') : '';
            var Publish = $('#formPublish').val();

            if(Title!='' && Title!=null && Year!='' && Year!=null &&
                JudiciumsDate!='' && JudiciumsDate!=null &&
            GraduationDate!='' && GraduationDate!=null){

                loading_buttonSm('#btnSaveJudiciums');

                var data = {
                    action : 'updateDataJudiciums',
                    ID : ID,
                    dataForm : {
                        Title : Title,
                        Year : Year,
                        JudiciumsDate : JudiciumsDate,
                        GraduationDate : GraduationDate,
                        Publish : Publish
                    }
                };

                var token = jwt_encode(data,'UAP)(*');
                var url = base_url_js+'api3/__crudYudisium';

                $.post(url,{token:token},function (jsonResult) {

                    toastr.success('Data saved','Success');

                    $('#formTitle').val('');
                    $('#formID').val('');
                    $('#formYear').val('');
                    $('#formJudiciumsDate').val('');
                    $('#formGraduationDate').val('');
                    loadDataJudiciums();
                    setTimeout(function () {
                        $('#btnSaveJudiciums').html('Save').prop('disabled',false);
                    },500);

                });

            } else {
                toastr.warning('Please fill in the required form','Warning');
            }



        });

    });

    function loadDataJudiciums() {

        var data = {
            action : 'readDataJudiciums'
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudYudisium';

        $.post(url,{token:token},function (jsonResult) {

            $('#loadDataJudiciums').empty();
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var jd = (v.JudiciumsDate!='' && v.JudiciumsDate!=null) ? moment(v.JudiciumsDate).format('dddd, DD MMM YYYY') : '';
                    var gd = (v.GraduationDate!='' && v.GraduationDate!=null) ? moment(v.GraduationDate).format('dddd, DD MMM YYYY') : '';
                     var sts = (v.Publish=='0') ? '<div style="color:darkred;margin-bottom: 5px;">Unpublish</div>' : '<div style="color: darkgreen;margin-bottom: 5px;">Publish</div>' ;

                     var stsBtn = (v.Publish=='0') ? 'Publish' : 'Unpublish';

                     var btnAct = '<div class="btn-group">' +
                         '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                         '    <i class="fa fa-edit"></i> <span class="caret"></span>' +
                         '  </button>' +
                         '  <ul class="dropdown-menu">' +
                         '    <li><a href="javascript:void(0);" class="btnEditJudiciums" data-id="'+v.ID+'">Edit</a></li>' +
                         '    <li role="separator" class="divider"></li>' +
                         '    <li><a href="javascript:void(0);" class="btnActPublish" data-act="'+stsBtn+'" data-id="'+v.ID+'">'+stsBtn+'</a></li>' +
                         '  </ul>' +
                         '</div>';

                    $('#loadDataJudiciums').append('<tr>' +
                        '<td style="border-right: 1px solid #CCC;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.Title+'</td>' +
                        '<td>'+v.Year+'</td>' +
                        '<td style="text-align: left;"><div>Judiciums : '+jd+'</div><div>Graduation : '+gd+'</div></td>' +
                        '<td>'+sts+' '+btnAct+'<textarea id="dataEdit_'+v.ID+'" class="hide">'+JSON.stringify(v)+'</textarea></td>' +
                        '</tr>');
                });
            }


        });

    }

    $(document).on('click','.btnEditJudiciums',function () {
        var ID = $(this).attr('data-id');
        var dataEdit = $('#dataEdit_'+ID).val();
        var d = JSON.parse(dataEdit);


        $('#formID').val(d.ID);
        $('#formTitle').val(d.Title);
        $('#formYear').val(d.Year);
        (d.JudiciumsDate!=='0000-00-00' && d.JudiciumsDate!==null) ? $('#formJudiciumsDate').datepicker('setDate',new Date(d.JudiciumsDate)) : '';
        (d.GraduationDate!=='0000-00-00' && d.GraduationDate!==null) ? $('#formGraduationDate').datepicker('setDate',new Date(d.GraduationDate)) : '';
        $('#formPublish').val(d.Publish);

    });

    $(document).on('click','.btnActPublish',function () {
        var ID = $(this).attr('data-id');
        var action = $(this).attr('data-act');

        var data = {
            action : 'updateStatusDataJudiciums',
            ID : ID,
            Publish : (action=='Publish') ? '1' : '0'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudYudisium';

        $.post(url,{token:token},function (result) {
            toastr.success('Data saved','Success');

            setTimeout(function () {
                loadDataJudiciums();
            },500);

        });


    });

</script>