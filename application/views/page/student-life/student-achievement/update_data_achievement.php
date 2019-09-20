

<div class="row">
    <div class="col-md-8 col-md-offset-2">

        <?php if($ID!=''){ ?>


        <div style="margin-bottom: 20px;">
            <a href="<?= base_url('student-life/student-achievement/list'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left margin-right"></i> Back to list</a>
        </div>

        <?php } ?>


        <div class="well" style="padding: 15px;min-height: 300px;">

            <div class="form-group">

                <div class="row">
                    <div class="col-xs-9">
                        <label>Event Name</label>
                        <input class="hide" id="ID" />
                        <input class="form-control form-update-data" id="Event" />
                    </div>
                    <div class="col-xs-3">
                        <label>Year</label>
                        <input class="form-control form-update-data" id="Year" />
                    </div>
                </div>

            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea class="form-control form-update-data" id="Description" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label>Location</label>
                <textarea class="form-control form-update-data" id="Location" rows="2"></textarea>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label>Start Date</label>
                        <input class="form-control form-update-data" id="StartDate"  style="color: #333333;background: #ffffff;" readonly />
                    </div>
                    <div class="col-xs-6">
                        <label>End Date</label>
                        <input class="form-control form-update-data" id="EndDate"  style="color: #333333;background: #ffffff;" readonly />
                    </div>
                </div>

            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-4">
                        <label>Level</label>
                        <select class="form-control form-update-data" id="Level">
                            <option value="Provinsi">Provinsi / Wilayah</option>
                            <option value="Nasional">Nasional</option>
                            <option value="Internasional">Internasional</option>
                        </select>
                    </div>
                    <div class="col-xs-4">
                        <label>Achievement</label>
                        <input class="form-control form-update-data" id="Achievement" />
                    </div>
                    <div class="col-xs-4">
                        <label>Type</label>
                        <select class="form-control form-update-data" id="Type">
                            <option value="1">Academic</option>
                            <option value="0">Non Academic</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <label>Member</label>
                <input id="filterStudent" class="form-control" placeholder="Search by name or nim student ..." />

                <textarea class="hide" id="dataListStudent"></textarea>

                <div class="row">
                    <div class="col-xs-6">
                        <div id="showStd"></div>
                    </div>
                    <div class="col-xs-6">
                        <div style="background: #ffffff;" id="showStdSelected"></div>
                    </div>
                </div>
            </div>

            <div class="form-group" style="text-align: right;">
                <button class="btn btn-success" id="saveAchievement">Save</button>
            </div>

        </div>
    </div>
</div>

<script>

    $(document).ready(function () {

        window.ID = "<?= $ID; ?>";

        $( "#StartDate,#EndDate" )
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

        if(ID!=''){
            loadDataAch();
        }

    });

    $('#filterStudent').keyup(function () {

        loadStdSearch();

    });

    $(document).on('click','.btnActAddStd',function () {

        var NPM = $(this).attr('data-npm');
        var Name = $(this).attr('data-name');
        var dataListStudent = $('#dataListStudent').val();

        if(dataListStudent!='' && dataListStudent!=null){
            dataListStudent = JSON.parse(dataListStudent);
        } else {
            dataListStudent = [];
        }
        dataListStudent.push({
            NPM : NPM,
            Name : Name
        });
        $('#dataListStudent').val(JSON.stringify(dataListStudent));

        $(this).parent().remove();

        loadStdSelected();

    });

    $(document).on('click','.btnActRemoveStd',function () {
        var NPM = $(this).attr('data-npm');
        var dataListStudent = $('#dataListStudent').val();
        var d = JSON.parse(dataListStudent);

        var n = [];

        if(d.length>0){
            $.each(d,function (i,v) {
                if(v.NPM!=NPM){
                    n.push(v);
                }
            });
        }

        $('#dataListStudent').val(JSON.stringify(n));
        loadStdSelected();
        loadStdSearch();
    });

    function loadStdSelected() {
        var dataListStudent = $('#dataListStudent').val();
        var d = JSON.parse(dataListStudent);

        if(d.length>0){
            $('#showStdSelected').html('<hr/><table class="table tableStd">' +
                '                        <thead>' +
                '                        <tr>' +
                '                            <th>Student Selected</th>' +
                '                            <th style="width: 5%;"><i class="fa fa-cog"></i></th>' +
                '                        </tr>' +
                '                        </thead>' +
                '                        <tbody id="listStdSelected"></tbody>' +
                '                    </table>');

            $.each(d,function (i,v) {
                $('#listStdSelected').append('<tr>' +
                    '                <td>'+v.Name+'<br/>'+v.NPM+'</td>' +
                    '                <td><button data-npm="'+v.NPM+'" class="btn btn-sm btn-default btnActRemoveStd"><i style="color: red;" class="fa fa-minus"></i></button></td>' +
                    '            </tr>');
            });

        } else {
            $('#showStdSelected').html('');
        }

    }

    function loadStdSearch() {
        var filterStudent = $('#filterStudent').val();

        if(filterStudent!='' && filterStudent!=null){
            var url = base_url_js+'api/__getStudentsServerSide';

            $.post(url,{key : filterStudent},function (jsonResult) {

                if(jsonResult.length>0){

                    $('#showStd').html('<hr/><table class="table tableStd" id="">' +
                        '                    <thead>' +
                        '                    <tr>' +
                        '                        <th>Student</th>' +
                        '                        <th style="width: 5%;"><i class="fa fa-cog"></i></th>' +
                        '                    </tr>' +
                        '                    </thead>' +
                        '                    <tbody id="showStdList">' +
                        '                    </tbody>' +
                        '                </table>' +
                        '                <hr/>');
                    var dataListStudent = $('#dataListStudent').val();
                    var arrStdLect = (dataListStudent!='' && dataListStudent!=null)
                        ? JSON.parse(dataListStudent) : [];
                    $.each(jsonResult,function (i,v) {

                        var swBtn = true;
                        if(arrStdLect.length>0){
                            $.each(arrStdLect,function (i2,v2) {
                                if(v2.NPM==v.NPM){
                                    swBtn = false;
                                }
                            });
                        }

                        var btnAct =  (swBtn)
                            ? '<button data-npm="'+v.NPM+'" data-name="'+ucwords(v.Name)+'" class="btn btn-sm btn-default btnActAddStd"><i style="color: green;" class="fa fa-plus"></i></button>'
                            : '';


                        $('#showStdList').append('<tr>' +
                            '<td>'+ucwords(v.Name)+'<br/>'+v.NPM+'</td>' +
                            '<td>'+btnAct+'</td>' +
                            '</tr>');

                    });

                } else {
                    $('#showStd').html('');
                }

            });
        }  else {
            $('#showStd').html('');
        }
    }

    $('#saveAchievement').click(function () {


        var formSubmit = true;
        var dataForm = '{';

        $('.form-update-data').each(function (i,v) {

            var ID = $(this).attr('id');
            var v = ($(this).val()!='') ? $(this).val() : "";

            if(ID=='StartDate' || ID=='EndDate'){
                var d_v = $(this).datepicker("getDate");
                v = (d_v!=null && d_v!='') ? moment(d_v).format('YYYY-MM-DD') : '';
            }

            if(v==""){
                formSubmit = false;
            }

            var koma = (i!=0) ? ',': '';
            dataForm = dataForm+' '+koma+' "'+ID+'" : "'+v+'"' ;

            if((i+1)==$('.form-update-data').length){
                dataForm = dataForm+'}';
            }

        });


        var dataListStudent = $('#dataListStudent').val();


        if(formSubmit && dataListStudent!='' && dataListStudent!=null){

            loading_modal_show();

            var ID = $('#ID').val();

            var data = {
                action : 'updatePAM',
                ID : (ID!='' && ID!=null) ? ID : '',
                dataForm : JSON.parse(dataForm),
                dataListStudent : JSON.parse(dataListStudent)
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB5';

            $.post(url,{token:token},function (result) {

                toastr.success('Data saved','Success');
                setTimeout(function () {
                    window.location.href = '';
                },500);

            });
        } else {
            toastr.error('Form are required','Error');
        }


    });


    // ====
    function loadDataAch() {
        if(ID!=''){

            loading_modal_show();

            var data = {
                action : 'getPAMByID',
                ID : ID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB5';

            $.post(url,{token:token},function (jsonResult) {

                var dataAch = jsonResult.dataAch[0];

                var keyName = Object.keys(dataAch);

                for(var i=0;i<keyName.length;i++){

                    if(keyName[i]=="StartDate" || keyName[i]=="EndDate"){

                        $('#'+keyName[i]).datepicker('setDate',new Date(dataAch[keyName[i]]));

                    } else {
                        $('#'+keyName[i]).val(dataAch[keyName[i]]);
                    }


                }


                var dataAchStd = jsonResult.dataAchStd;
                $('#dataListStudent').val(JSON.stringify(dataAchStd));
                loadStdSelected();

                setTimeout(function () {
                    loading_modal_hide();
                },1000);
            });


        }
    }

</script>