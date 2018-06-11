
<style>
    #tableTahunAkademik tr th {
        text-align: center;
    }
</style>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class=""><i class="icon-reorder"></i> Tahun Akademik</h4>
                <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs btn-th-action" data-action="add" id="btn_addTahunAkademik">
                            <i class="icon-plus"></i> Add Tahun Akademik
                        </span>
                        <span class="btn btn-xs btn-th-action" data-action="add_db" id="btn_addDB">
                            <i class="fa fa-database"></i> Create Database
                        </span>
                    </div>
                </div>
            </div>
            <div class="widget-content">
                <div id="loadTable"></div>
            </div>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {
        loadTable();
    });

    $(document).on('click','.btn-th-action',function () {

        var action = $(this).attr('data-action');
        var id = $(this).attr('data-id');

        if(action=='add'){
            var url = base_url_js+"academic/modal-tahun-akademik";

            var btn_delete = '<button class="btn btn-danger btn-delete-master" style="float: left;" modal-id="'+id+'" id="modalBtnDelete" modal-action="delete">Delete</button>';

            $.post(url,{action:action,id:id},function (html) {

                $('#GlobalModal .modal-header').html('<h4 class="modal-title">Tahun Akademik</h4>');
                $('#GlobalModal .modal-body').html(html);
                $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" id="modalBtnClose" data-dismiss="modal">Close</button>' +
                    '<button type="button" class="btn btn-success" modal-id="'+id+'" modal-action="'+action+'" id="modalBtnSave">Save</button>');
                // '<button type="button" class="btn btn-success btn-th-action" data-action="add1" id="modalBtnSave">Save</button>');
                if(action=='edit'){
                    $('#GlobalModal .modal-footer').append(btn_delete);
                }
                $('#GlobalModal').modal({
                    'show' : true,
                    'backdrop' : 'static'
                });

            });
        }
        else if(action=='publish') {

            $('#NotificationModal .modal-body').html('<div style="text-align: center;"><b>Publish ?? </b> ' +
                '<button type="button" id="btnActionPublish" data-id="'+id+'" class="btn btn-primary" style="margin-right: 5px;">Yes</button>' +
                '<button type="button" id="btnActionNo" class="btn btn-default" data-dismiss="modal">No</button>' +
                '</div>');
            $('#NotificationModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });

        }
        else if(action=='add_db'){

            var url = base_url_js+'api/__crudYearAcademic';
            var token = jwt_encode({action:'read'},'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {

                $('#NotificationModal .modal-body').html('' +
                    '<table class="table table-striped" id="tbdataTA">' +
                    '<tr>' +
                    '<td>Database</td>' +
                    '<td>Status</td>' +
                    '</tr>' +
                    '<tbody id="dataDbTA"></tbody>' +
                    '</table> ' +
                    '<div style="text-align: right;"><hr/>' +
                    '<button type="button" id="btnActionClose" class="btn btn-default" data-dismiss="modal">Close</button>' +
                    '</div>');

                var yp = 1;
                for(var i=0;i<parseInt(jsonResult.length)+2;i++){

                    if(i<=(parseInt(jsonResult.length) - 1)){
                        var year = jsonResult[i].YearAcademic;
                        $('#dataDbTA').append('<tr><td>'+year+'</td><td><i class="fa fa-check-circle" style="color: green;"></i> Exist</td></tr>');
                    } else {
                        var year = jsonResult[parseInt(jsonResult.length) - 1].YearAcademic;
                        var y = parseInt(year)+yp;

                        $('#dataDbTA').append('<tr><td>'+y+'</td><td id="acttd'+y+'"><button class="btn btn-default btn-default-primary btn-sm btn-createdb" data-db="'+y+'">Create DB</button></td></tr>');
                        yp +=1;
                    }

                }

                $('#tbdataTA tr td').css('text-align','center');

                $('#NotificationModal').modal({
                    'show' : true,
                    'backdrop' : 'static'
                });


            });


        }



    });

    $(document).on('click','.btn-createdb',function () {
        var dataDB = $(this).attr('data-db');
        var data = {
          action : 'add',
            dataInsert : {
                YearAcademic : parseInt(dataDB),
                CreateBy : sessionNIP,
                CreateAt : dateTimeNow()
            }
        };

        var btn = 'button[data-db='+dataDB+']';
        loading_buttonSm(btn);
        $('.btn-createdb,#btnActionClose').prop('disabled',true);

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudYearAcademic';
        $.post(url,{token:token},function (result) {
            setTimeout(function () {
                $('#acttd'+dataDB).html('<i class="fa fa-check-circle" style="color: green;"></i> Exist</td></tr>');
                $('.btn-createdb,#btnActionClose').prop('disabled',false);
            },500)
        });
    });

    $(document).on('click','#modalBtnSave, #modalBtnDelete',function () {

        var action = $(this).attr('modal-action');
        var ID = (action=='add')? '' : $(this).attr('modal-id');
        var ProgramCampusID = $('#modalProgram').find(':selected').val();
        var tahun = $('#modalTahun').find(':selected').val().split('.');
        var semester = $('input[name=semester]:checked').val();

        var s = (semester==1) ? 'Ganjil' : 'Genap';
        var Name = tahun[1].trim()+' '+s;

        var process = true;

        if(action=='delete'){
            if(window.confirm('Haous data ?')){
                process = true;
            } else {
                process = false;
            }
        }

        if(process){
            var btn_act = '#'+$(this).attr('id');
            var data = {
                action : action,
                ID : ID,
                dataForm : {
                    ProgramCampusID : ProgramCampusID,
                    Year : tahun[0].trim(),
                    Code : semester,
                    Name : Name,
                    Status : 0,
                    UpdateBy : sessionNIP,
                    UpdateAt : dateTimeNow()
                }
            };

            loading_button(btn_act);
            $('#modalBtnSave, #modalBtnDelete, #modalCurriculum, #modalBtnClose, #modalProgram, #modalTahun, input[name=semester]').prop('disabled',true);

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api/__crudTahunAkademik';
            $.post(url,{token:token},function (result) {
                // console.log(result);
                if(result==0){
                    setTimeout(function () {
                        $(btn_act).prop('disabled',false).html('Save');
                        $('#modalBtnSave, #modalBtnDelete, #modalCurriculum, #modalBtnClose, #modalProgram, #modalTahun, input[name=semester]').prop('disabled',false);
                        toastr.warning('Data Is Exist','Warning!');
                    },500);
                } else {
                    loadTable();
                    setTimeout(function () {
                        toastr.success('Data tersimpan','Success!!');
                        $('#GlobalModal').modal('hide');
                        // $('#modalBtnSave').html('Save');
                        // $('#modalBtnSave, #modalBtnDelete, #modalBtnClose, #modalProgram, #modalTahun, input[name=semester]').prop('disabled',false);
                    },500);
                }


            });
        }


    });

    $(document).on('click','.btn-detail-tahun-akademik',function () {
        var ID = $(this).attr('data-id');
        loadDetailPageTahunAkademik(ID);
    });

    $(document).on('click','#btnActionPublish',function () {
       var url = base_url_js+'api/__crudDataDetailTahunAkademik';
       var ID = $(this).attr('data-id');
       var token = jwt_encode({action:'publish',ID:ID},'UAP)(*');
       loading_buttonSm('#btnActionPublish');
       $('#btnActionNo').prop('disabled',true);
       $.post(url,{token:token},function (result) {
           loadTable();
           setTimeout(function () {
               toastr.success('Data Update','Success');
               $('#btnActionPublish').html('Yes');
               $('#btnActionNo,#btnActionPublish').prop('disabled',false);
               $('#NotificationModal').modal('hide');
           },1000);


       });
    });
    
    
    function loadDetailPageTahunAkademik(ID) {
        loading_page('#loadTable');
        var url = base_url_js+'academic/detail-tahun-akademik';
        $.post(url,{ID:ID},function (html) {
            setTimeout(function () {
                $('#loadTable').html(html);
            },500);
        });
    }

    function loadTable() {

        loading_page('#loadTable');
        var url = base_url_js+'academic/tahun-akademik-table';
        $.get(url,function (html) {
            setTimeout(function () {
                $('#loadTable').html(html);
            },500);

        });

    }


</script>