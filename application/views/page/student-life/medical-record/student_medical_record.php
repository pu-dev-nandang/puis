
<style>
    #SickDateStart, #SickDateEnd {
        background: #ffffff;
        cursor: pointer;
        color: #333333;
    }
</style>

<div class="row">
    <div class="col-md-3 col-md-offset-9" style="margin-bottom: 15px;">
        <div style="text-align: right;">
            <button class="btn btn-default" id="btnCrudMedical">Add Medical Record</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" id="loadTable"></div>
</div>



<script>

    $(document).ready(function () {
        getData();
    });

    $('#btnCrudMedical').click(function () {
        loadModalMedicalRecord('');
    });

    $(document).on('keyup','#filterStudent',function () {

        var filterStudent = $('#filterStudent').val();

        if(filterStudent!='' && filterStudent!=null) {
            var url = base_url_js + 'api/__getStudentsServerSide';
            $.post(url,{key : filterStudent},function (jsonResult) {

                $('#loadStudent').empty();
                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {
                        $('#loadStudent').append('<tr>' +
                            '<td style="width: 1%;">'+(i+1)+'</td>' +
                            '<td style="text-align: left;"><b>'+v.Name+'</b><br/>'+v.NPM+'</td>' +
                            '<td style="width: 5%;"><button class="btn btn-sm btn-default btnAddStudent" data-name="'+v.Name+'" data-npm="'+v.NPM+'"><i class="fa fa-plus"></i></button></td>' +
                            '</tr>');
                    });
                } else {
                    $('#loadStudent').html('<tr>' +
                        '<td colspan="3">Student not yet</td>' +
                        '</tr>');
                }

            });
        }

    });
    $(document).on('click','.btnAddStudent',function () {
        var Name = $(this).attr('data-name');
        var NPM = $(this).attr('data-npm');

        $('#viewStudent').html('<b style="color: darkgreen;">'+NPM+' - '+Name+'</b>');
        $('#NPM').val(NPM);

    });
    
    function getData() {

        $('#loadTable').html('<table class="table table-centre table-striped table-bordered" id="tableData">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th style="width: 20%;">Student</th>' +
            '                <th style="width: 20%;">Disease Name</th>' +
            '                <th>Treated At</th>' +
            '                <th>Allergy</th>' +
            '                <th style="width: 15%;">Personal Doctor\'s Name</th>' +
            '                <th style="width: 5%;"><i class="fa fa-cog"></i></th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody></tbody>' +
            '        </table>');

        var data = {
            action : 'getDataMedicalRecord'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudMedicalRecord';
        var dataTable = $('#tableData').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "NIM, Name"
            },
            "ajax":{
                url : url, // json datasource
                data : {token:token},
                ordering : false,
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            }
        });

    };

    function loadModalMedicalRecord(MedicalRecordID) {

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Medical Record</h4>');

        var htmlss = '<div class="col-md-12"><div class="row">' +
            '            <div class="form-group">' +
            '                <label>Search Student</label>' +
            '                <input id="filterStudent" class="form-control" placeholder="Search student by NPM or Name">' +
            '                <input id="MedicalRecordID" value="'+MedicalRecordID+'" class="hide">' +
            '                <input id="NPM" class="hide formMedicalRecord">' +
            '                <div style="margin-top: 15px;margin-bottom: 15px;"><table class="table table-striped table-centre"><tbody id="loadStudent"></tbody></table></div><hr/>' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <label>Student Selected</label>' +
            '                <div id="viewStudent">-</div>' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <label>Disease Name</label>' +
            '                <input id="DiseaseName" class="form-control formMedicalRecord">' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <label>Sick Date Start</label>' +
            '                <input id="SickDateStart" class="form-control formMedicalRecord" readonly >' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <label>Sick Date End</label>' +
            '                <input id="SickDateEnd" class="form-control formMedicalRecord" readonly >' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <label>Allergy</label>' +
            '                <textarea id="Allergy" class="form-control formMedicalRecord" rows="3"></textarea>' +
            '            </div>' +
            '            <div class="form-group">' +
            '               <div class="checkbox">' +
            '                   <label>' +
            '                       <input type="checkbox" class="formMedicalRecord" id="Periodically" value="1"> Periodically' +
            '                   </label>' +
            '               </div>' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <label>Treated At</label>' +
            '                <textarea id="TreatedAt" class="form-control formMedicalRecord" rows="3"></textarea>' +
            '            </div>' +
            '            <div class="form-group">' +
            '                <label>Personal Doctor\'s Name</label>' +
            '                <input id="PersonalDoctorName" class="form-control formMedicalRecord">' +
            '            </div>' +
            '        </div></div>';

        $('#GlobalModal .modal-body').html(htmlss);

        $( "#SickDateStart,#SickDateEnd" )
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

        if(MedicalRecordID!=''){
            var data = $('#txt_'+MedicalRecordID).val();

            var v = JSON.parse(data);

            $('#NPM').val((v.NPM!='' && v.NPM!=null)? v.NPM : '');

            $('#viewStudent').html('<b style="color: darkgreen;">'+v.NPM+' - '+v.Name+'</b>');

            $('#DiseaseName').val((v.DiseaseName!='' && v.DiseaseName!=null)? v.DiseaseName : '');
            $('#Allergy').val((v.Allergy!='' && v.Allergy!=null)? v.Allergy : '');
            $('#TreatedAt').val((v.TreatedAt!='' && v.TreatedAt!=null)? v.TreatedAt : '');
            $('#PersonalDoctorName').val((v.PersonalDoctorName!='' && v.PersonalDoctorName!=null)? v.PersonalDoctorName : '');

            if(v.Periodically==1 || v.Periodically=='1'){
                $('#Periodically').prop('checked',true);
            }

            (v.SickDateStart!=='0000-00-00' && v.SickDateStart!==null) ? $('#SickDateStart').datepicker('setDate',new Date(v.SickDateStart)) : '';
            (v.SickDateEnd!=='0000-00-00' && v.SickDateEnd!==null) ? $('#SickDateEnd').datepicker('setDate',new Date(v.SickDateEnd)) : '';

        }




        $('#GlobalModal .modal-footer').html('' +
            '<button class="btn btn-success" id="btnSaveMedicalRecord">Save</button> ' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

        $('#btnSaveMedicalRecord').click(function () {

            loading_buttonSm('#btnSaveMedicalRecord');
            $('button[data-dismiss=modal]').prop('disabled',true);

            var MedicalRecordID = ($('#MedicalRecordID').val()!='' && $('#MedicalRecordID').val()!=null)
                ? $('#MedicalRecordID').val() : '';

            var data = {
                action : 'setDataMedicalRecord',
                ID : MedicalRecordID,
                UserID : sessionNIP,
                Updated : '1',
                dataForm : {
                    NPM : $('#NPM').val(),
                    DiseaseName : $('#DiseaseName').val(),
                    SickDateStart : ($('#SickDateStart').datepicker("getDate")!=null)
                        ? moment($('#SickDateStart').datepicker("getDate")).format('YYYY-MM-DD') : '',
                    SickDateEnd : ($('#SickDateEnd').datepicker("getDate")!=null)
                        ? moment($('#SickDateEnd').datepicker("getDate")).format('YYYY-MM-DD') : '',
                    Allergy : $('#Allergy').val(),
                    Periodically : ($('#Periodically').is(':checked')) ? '1' : '0',
                    TreatedAt : $('#TreatedAt').val(),
                    PersonalDoctorName : $('#PersonalDoctorName').val()
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudMedicalRecord';
            $.post(url,{token:token},function (jsonResult) {

                toastr.success('Data saved','Success');
                setTimeout(function () {
                    $('#GlobalModal').modal('hide');
                },500);

            });

        });
    }

    $(document).on('click','.btnEditMedicalRegord',function () {
        var MedicalRecordID = $(this).attr('data-id');
        loadModalMedicalRecord(MedicalRecordID);
    });

</script>