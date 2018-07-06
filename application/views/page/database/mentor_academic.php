

<style>
    .header-panel {
        border-left: 10px solid #ff9800;
        padding-left: 5px;
        font-weight: bold;
    }

    .left-box, .right-box {
        width: 46%;
    }
    .dual-control {
        width: 35px;
        left: 54%;
        margin-top: 150px;
    }
    .multiple {
        height: 300px !important;
    }
</style>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="thumbnail">
            <div class="row">
                <div class="col-xs-3">
                    <select class="form-control" id="formCurriculum">
                        <option selected disabled>-- Curriculum --</option>
                        <option disabled>-------</option>
                    </select>
                </div>
                <div class="col-xs-3">
                    <select class="form-control" id="formProdi">
                        <option selected disabled>-- Porgram Study --</option>
                        <option disabled>-------</option>
                    </select>
                </div>
                <div class="col-xs-6">
                    <select class="select2-select-00 full-width-fix"
                            size="5" id="formlecturer">
                        <option value=""></option>
                    </select>
                </div>
            </div>

        </div>
        <hr/>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> Set Mentor Academic</h4>
            </div>
            <div class="widget-content clearfix" style="padding: 15px 5px 15px 5px;">

                <div class="row">
                    <div class="col-md-12">
                        <!-- Left box -->
                        <div class="left-box">
                            <input type="text" id="box1Filter" class="form-control box-filter" placeholder="Filter entries..."><button type="button" id="box1Clear" class="filter">x</button>
                            <select id="box1View" multiple="multiple" class="multiple"></select>
                            <span id="box1Counter" class="count-label"></span>
                            <select id="box1Storage"></select>
                        </div>
                        <!--left-box -->

                        <!-- Control buttons -->
                        <div class="dual-control">
                            <button id="to2" type="button" class="btn">&nbsp;&gt;&nbsp;</button>
                            <!--                    <button id="allTo2" type="button" class="btn">&nbsp;&gt;&gt;&nbsp;</button><br>-->
                            <br>
                            <button id="to1" type="button" class="btn">&nbsp;&lt;&nbsp;</button>
                            <!--                    <button id="allTo1" type="button" class="btn">&nbsp;&lt;&lt;&nbsp;</button>-->
                        </div>
                        <!--control buttons -->

                        <!-- Right box -->
                        <div class="right-box">
                            <input type="text" id="box2Filter" class="form-control box-filter" placeholder="Filter entries..."><button type="button" id="box2Clear" class="filter">x</button>
                            <select id="box2View" multiple="multiple" class="multiple"></select>
                            <span id="box2Counter" class="count-label"></span>
                            <select id="box2Storage"></select>
                        </div>
                        <!--right box -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <hr/>
                        <div style="text-align: right;">
                            <button class="btn btn-success" id="btnSubmit">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="thumbnail" style="min-height: 100px;padding: 0px;">
            <div>
                <h3 class="header-panel" id="headerName">-</h3>
            </div>

            <div id="dvMhs"></div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        loadSelectOptionCurriculum('#formCurriculum','');
        loadSelectOptionBaseProdi('#formProdi','');
        loadSelectOptionLecturersSingle('#formlecturer','');

        $('#formlecturer').select2({allowClear: true});

    });

    $('#btnSubmit').click(function () {
        var students = $('#box2View').find('option').map(function() { return this.value }).get().join(",");

        var formCurriculum = $('#formCurriculum').val();
        var formProdi = $('#formProdi').val();
        var formlecturer = $('#formlecturer').val();

        if(formCurriculum!=null && formProdi!= null && formlecturer!=null && formlecturer!=''){
            var dataNPM = students.split(',');
            var data = {
                action : 'add',
                dataForm : {
                    ProdiID :  formProdi.split('.')[0],
                    Year : formCurriculum.split('.')[1],
                    NIP : formlecturer
                },
                dataNPM : dataNPM
            };

            var url = base_url_js+'api/__filterStudents';

            loading_button('#btnSubmit');

            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (result) {
                loadData();

                setTimeout(function (args) {
                    toastr.success('Saved','Success!');
                    // $('#box2View').empty();
                    $('#btnSubmit').prop('disabled',false);
                    $('#btnSubmit').html('Submit');
                },500);
            });
            
        }

    });

    $('#formCurriculum,#formProdi,#formlecturer').change(function () {
        loadData();
    });

    $(document).on('click','.btn-remove',function () {
        var IDMA = $(this).attr('data-id');
        console.log(IDMA);

        $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Delete Student ?? </b> ' +
            '<button type="button" id="btnDeleteOfferYes" data-id="'+IDMA+'" class="btn btn-primary" style="margin-right: 5px;">Yes</button> ' +
            '<button type="button" id="btnDeleteOfferNo" class="btn btn-default" data-dismiss="modal">No</button>' +
            '</div>');

        $('#NotificationModal').modal('show');


    });

    $(document).on('click','#btnDeleteOfferYes',function () {

        loading_buttonSm('#btnDeleteOfferYes');
        $('#btnDeleteOfferNo').prop('disabled',true);

        var IDMA = $(this).attr('data-id');
        var data = {
            action : 'delete',
            IDMA : IDMA
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__filterStudents';

        $.post(url,{token:token},function (result) {
            loadData();
            setTimeout(function () {
                toastr.success('Saved','Success!');
                $('#NotificationModal').modal('hide');
            },500);

        });
    });

    function loadData() {

        var formCurriculum = $('#formCurriculum').val();
        var formProdi = $('#formProdi').val();
        var formlecturer = $('#formlecturer').val();

        if(formCurriculum!=null && formProdi!= null && formlecturer!=null && formlecturer!=''){

            $('#headerName').html($('#formlecturer option:selected').text());

            var data = {
                action : 'readStudents',
                dataFilter : {
                    Year : formCurriculum.split('.')[1],
                    ProdiID : formProdi.split('.')[0],
                    NIP : formlecturer
                }
            };

            var token = jwt_encode(data,'UAP)(*');


            var url = base_url_js+'api/__filterStudents';

            $.post(url,{token:token},function (resultJson) {
                $('#box1View,#box1Storage,#box2View,#box2Storage').empty();

                $('#dvMhs').html('<hr/><table class="table table-bordered table-striped" id="tblDataStd">' +
                    '                <thead>' +
                    '                <tr style="background: #20485A;color: #ffffff;">' +
                    '                    <th style="width: 20%;text-align: center;">NPM</th>' +
                    '                    <th style="text-align: center;">Name</th>' +
                    '                    <th style="width: 10%;text-align: center;">Action</th>' +
                    '                </tr>' +
                    '                </thead>' +
                    '                <tbody id="rowGuidance"></tbody>' +
                    '            </table>');

                for(var a=0;a<resultJson.AllStudents.length;a++){
                    var dataAllStudents = resultJson.AllStudents[a];

                    // if($.inArray(dataAllStudents.NPM,readyNPM)!=-1){
                    if(dataAllStudents.Lecturer!=null && dataAllStudents.Lecturer!=''){
                        $('#box1View').append('<option style="color: orangered;" disabled>'+dataAllStudents.NPM+' - '+dataAllStudents.Name+' | '+dataAllStudents.Lecturer+'</option>');

                        if(dataAllStudents.NIP==formlecturer){
                            $('#rowGuidance').append('<tr>' +
                                '<td style="text-align: center;">'+dataAllStudents.NPM+'</td>' +
                                '<td>'+dataAllStudents.Name+'</td>' +
                                '<td style="text-align: center;">' +
                                '<button class="btn btn-default btn-default-danger btn-sm btn-remove" data-id="'+dataAllStudents.IDMA+'"><i class="fa fa-trash" aria-hidden="true"></i></button>' +
                                '</td>' +
                                '</tr>');
                        }


                    } else {
                        $('#box1View').append('<option value="'+dataAllStudents.NPM+'">'+dataAllStudents.NPM+' - '+dataAllStudents.Name+'</option>');
                    }
                }

                $('#tblDataStd').DataTable({
                    "sDom": "<'row'<'dataTables_header clearfix'<'col-md-3'><'col-md-9'f>r>>t<'row'<'dataTables_footer clearfix'<'col-md-12'p>>>", // T is new
                    'bLengthChange' : false,
                    'bInfo' : false,
                    'pageLength' : 7
                });
            });
        }



    }
</script>