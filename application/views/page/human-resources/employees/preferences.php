<style type="text/css">
    #datatable_employees.dataTable tbody tr:hover {
       background-color:#71d1eb !important;
       cursor: pointer;
    }
</style>
<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Relared NIP</h4>
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Select Active Employees</label>
                            <div class="input-group">
                                <input class="form-control" id="searchActiveEmp" placeholder="Search Active Employees...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" id="clearSearchActiveEmp" type="button"><i class="fa fa-times"></i></button>
                                  </span>
                            </div><!-- /input-group -->

                        </div>
                        <table class="table table-striped table-centre">
                            <thead>
                            <tr>
                                <th>Employee</th>
                                <th style="width: 15%;"><i class="fa fa-cog"></i></th>
                            </tr>
                            </thead>
                            <tbody id="listActiveEmp"></tbody>
                        </table>
                    </div>
                    <div class="col-md-8" style="border-left: 1px solid #CCCCCC;min-height: 150px;">
                        <div id="showEmp"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="panel-title">Lecturer Salary per Credit(Sks)</h4>
                    </div>
                    <div class="col-sm-6">
                        <div class="btn-group pull-right">
                          <span data-smt="" class="btn btn-xs btn-add-lecturerCredit">
                            <i class="icon-plus"></i> Add
                           </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="well">
                           <div class="form-group">
                               <label>Choose Semester</label>
                               <select class="form-control" id = "filterSemesterID">
                                   
                               </select>
                           </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- <div class="table-responsive"> -->
                            <table class="table table-centre table-striped" id = "tbl_lecturer_credit">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Semester</th>
                                        <th>Lecturer Name</th>
                                        <th>Honor</th>
                                        <th>Allowance</th>
                                        <th>NIDN Allowance</th>
                                        <th><i class="fa fa-cog"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        readAllNIPRelated();
    });

    function readAllNIPRelated(){

        loading_modal_show();

        var data = {
            action : 'readAllDataRelatedNIP'
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudPrefrencesEmployees';

        $.post(url,{token:token},function (jsonResult) {

            $('#showEmp').html('<table class="table table-centre table-striped">' +
                '                    <thead>' +
                '                    <tr>' +
                '                        <th style="width: 1%;">No</th>' +
                '                        <th style="width: 15%;">Employees</th>' +
                '                        <th>Related</th>' +
                '                        <th style="width: 10%;"><i class="fa fa-cog"></i></th>' +
                '                    </tr>' +
                '                    </thead>' +
                '                    <tbody id="listReltd"></tbody>' +
                '                </table>');
            if(jsonResult.length>0){
                $.each(jsonResult,function (i,v) {

                    var Details = v.Details;
                    var listN = '';
                    $.each(Details,function (i2,v2) {
                        var koma = (i2!=0) ? ', ':'';
                        listN = listN +koma+ '(<span style="color: #0c5fa1;">'+v2.NIP+')</span> '+v2.Name+'';
                    });

                    $('#listReltd').append('<tr>' +
                        '<td style="border-right: 1px solid #CCCCCC;">'+(i+1)+'</td>' +
                        '<td style="text-align: left;"><b>'+v.Name+'</b><br/>'+v.NIP+'</td>' +
                        '<td style="text-align: left;">'+listN+'</td>' +
                        '<td><button class="btn btn-default btn-default-success btnSelectNIP" data-nip="'+v.NIP+'">View</button></td>' +
                        '</tr>');
                });
            } else {
                $('#listReltd').append('<tr>' +
                    '<td colspan="4">No data</td></tr>');
            }

            setTimeout(function () {
                loading_modal_hide();
            },500);

        });
    }


    $('#searchActiveEmp').keyup(function () {

        var searchActiveEmp = $('#searchActiveEmp').val();

        if(searchActiveEmp!='' && searchActiveEmp!=null){
            var url = base_url_js+'api4/__searchEmployees?limit=5&&key='+searchActiveEmp;

            $.getJSON(url,function (jsonResult) {

                $('#listActiveEmp').empty();
                if(jsonResult.length>0){
                    $.each(jsonResult,function (i,v) {

                        var StatusLecturer = (v.StatusLecturer!='' && v.StatusLecturer!=null) ? v.StatusLecturer : '-';

                        $('#listActiveEmp').append('<tr>' +
                            '<td style="text-align: left;"><b style="color: #0c5fa1;">'+v.NIP+' - '+v.Name+'</b><br/>Emp : '+v.StatusEmployees+'<br/>Lec : '+StatusLecturer+'</td>' +
                            '<td><button data-nip="'+v.NIP+'" class="btn btn-sm btn-default btn-default-success btnSelectNIP"><i class="fa fa-arrow-right"></i></button></td>' +
                            '</tr>');
                    });
                }

            });
        }

    });

    $('#clearSearchActiveEmp').click(function () {
        $('#searchActiveEmp').val('');
        $('#listActiveEmp').empty();
    });

    $(document).on('click','.btnSelectNIP',function () {
        var NIP = $(this).attr('data-nip');
        showRealtedDetail(NIP);

    });

    function showRealtedDetail(NIP){

        var data = {
            action : 'getDataRelatedNIP',
            NIP : NIP
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api4/__crudPrefrencesEmployees';

        $.post(url,{token:token},function (jsonResult) {

            var d = jsonResult.DataEmp;
            var Related = jsonResult.Related;

            var StatusLecturer = (d.StatusLecturer!='' && d.StatusLecturer!=null)
                ? d.StatusLecturer : '-';

            var alertNon = (parseInt(d.StatusEmployeeID)>=1) ? '' : '<div class="alert alert-danger" role="alert">The addition of relations only applies to permanent employees or contract employees with active employee status</div>';
            var btnAddRelation = (parseInt(d.StatusEmployeeID)>=1) ? '<button style="float:right;" class="btn btn-success" id="btnAddRelatedNIP">Add related NIP</button>' : '';

            $('#showEmp').html('' +
                '<div class="row"><div class="col-md-12"><button class="btn btn-warning" id="backToListRelated"><i class="fa fa-arrow-left margin-right"></i> Back to list</button><hr/></div></div>' +
                '<div class="row">' +
                '                    <div class="col-md-10 col-md-offset-1" style="text-align: center;">' +
                '                        <div class="well">' +
                '                            <h3 style="margin-top: 5px;color: #0c5fa1;">'+d.NIP+' - '+d.Name+'</h3>' +
                '                            <input class="hide" id="dataNIPInduk" value="'+d.NIP+'" />   ' +
                '                            Status Employees : '+d.StatusEmployees+
                '                            <br/>' +
                '                            Status Lecturer : ' +StatusLecturer+
                '                        </div>' +
                '                    </div>' +
                '                </div>' +
                '                <div class="row">' +
                '                    <div class="col-md-12">' +alertNon+
                '                        <h3><i class="fa fa-link"></i> Related NIP' +
                '                        ' +btnAddRelation+'</h3>'+
                '                        <table class="table table-bordered table-striped table-centre" style="margin-top: 30px;">' +
                '                            <thead>' +
                '                            <tr>' +
                '                                <th style="width: 1%;">No</th>' +
                '                                <th style="width: 15%;">NIP</th>' +
                '                                <th>Name</th>' +
                '                                <th style="width: 10%;"><i class="fa fa-cog"></i></th>' +
                '                            </tr>' +
                '                            </thead>' +
                '                            <tbody id="listRelated"></tbody>' +
                '                        </table>' +
                '                       <textarea class="hide" id="dataRelated"></textarea>' +
                '                    </div>' +
                '                </div>');


            var listNIPRelated = [];
            if(Related.length>0){

                $.each(Related,function (i,v) {
                    listNIPRelated.push(v.RelatedNIP);
                    $('#listRelated').append('<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td>'+v.RelatedNIP+'</td>' +
                        '<td style="text-align: left;">'+v.Name+'</td>' +
                        '<td><button class="btn btn-danger btn-sm btnRemoveRelated" data-id="'+v.ID+'"><i class="fa fa-trash-o"></i></button></td>' +
                        '</tr>');
                });

            } else {

                $('#listRelated').html('<tr>' +
                    '<td colspan="4">No data</td>' +
                    '</tr>');

            }

            $('#dataRelated').val(JSON.stringify(listNIPRelated));

        });

    }

    $(document).on('click','#backToListRelated',function () {
        readAllNIPRelated();
    });

    $(document).on('click','.btnRemoveRelated',function () {

        if(confirm('Are you sure?')){
            loading_modal_show();
            var ID = $(this).attr('data-id');
            var data = {
                action : 'removeDataRelatedNIP',
                ID : ID
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api4/__crudPrefrencesEmployees';

            $.post(url,{token:token},function (result) {
                var NIPInduk = $('#dataNIPInduk').val();
                showRealtedDetail(NIPInduk);
                setTimeout(function () {
                    loading_modal_hide();
                },500);
            });
        }


    });

    $(document).on('click','#btnAddRelatedNIP',function () {

        $('#GlobalModalLarge .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Add related NIP</h4>');

        var htmlss = '<div class="row">' +
            '    <div class="col-md-8 col-md-offset-2">' +
            '        <div class="well">' +
            '            <label>Select Employees</label>' +
            '            <input class="form-control" id="searchEmploy" placeholder="Search employees...">' +
            '        </div>' +
            '    </div>' +
            '</div>' +
            '<div class="row">' +
            '    <div class="col-md-6" style="border-right: 1px solid #CCCCCC;">' +
            '        <table class="table">' +
            '           <thead>' +
            '               <tr>' +
            '                   <th style="width: 1%;">No</th>' +
            '                   <th>Employees</th>' +
            '                   <th style="width: 10%;"><i class="fa fa-cog"></i></th>' +
            '               </tr>' +
            '           </thead>' +
            '           <tbody id="listEmp_1">' +
            '           </tbody>' +
            '       </table>' +
            '    </div>' +
            '    <div class="col-md-6">' +
            '        <table class="table">' +
            '           <thead>' +
            '               <tr>' +
            '                   <th style="width: 1%;">No</th>' +
            '                   <th>Employees</th>' +
            '                   <th style="width: 10%;"><i class="fa fa-cog"></i></th>' +
            '               </tr>' +
            '           </thead>' +
            '           <tbody id="listEmp_2">' +
            '           </tbody>' +
            '       </table>' +
            '    </div>' +
            '</div>';

        $('#GlobalModalLarge .modal-body').html(htmlss);

        $('#GlobalModalLarge .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');


        $('#GlobalModalLarge').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

    $(document).on('keyup','#searchEmploy',function () {
        searchToAddingRelated('');
    });

    function searchToAddingRelated(newNIPRelated){
        var searchEmploy = $('#searchEmploy').val();

        if(searchEmploy!='' && searchEmploy!=null){

            var Related = $('#dataRelated').val();
            var dataRelated = JSON.parse(Related);
            if(newNIPRelated!=''){
                dataRelated.push(newNIPRelated);
            }
            var NIPInduk = $('#dataNIPInduk').val();

            var url = base_url_js+'api4/__searchEmployees?limit=20&&key='+searchEmploy;

            $.getJSON(url,function (jsonResult) {

                $('#listEmp_2,#listEmp_1').empty();

                if(jsonResult.length>0){

                    var lengTb1 = (jsonResult.length>1) ? Math.ceil(parseInt(jsonResult.length) / 2) : 1;
                    $.each(jsonResult,function (i,v) {

                        var elm = ((i+1)>lengTb1) ? '#listEmp_2' : '#listEmp_1';

                        var StatusLecturer = (v.StatusLecturer!='' && v.StatusLecturer!=null) ? v.StatusLecturer : '-';

                        var bgTR = ($.inArray(v.NIP,dataRelated)!=-1 || NIPInduk==v.NIP) ? 'style="background:#daffbe;"' : '';
                        var btnTR = ($.inArray(v.NIP,dataRelated)!=-1 || NIPInduk==v.NIP)
                            ? '<i class="fa fa-check" style="color: green;"></i>'
                            : '<button class="btn btn-sm btn-default btnAddingAsRelated" data-nip="'+v.NIP+'"><i class="fa fa-plus"></i></button>';



                        $(elm).append('<tr '+bgTR+'>' +
                            '<td style="border-right: 1px solid #CCCCCC;text-align: center;">'+(i+1)+'</td>' +
                            '<td><b style="color: mediumblue;font-size: 15px;">'+v.NIP+' - '+v.Name+'</b><br/>' +
                            'Emp : '+v.StatusEmployees+'<br/>' +
                            'Lec : '+StatusLecturer+'' +
                            '</td>' +
                            '<td style="text-align: center;">' +btnTR+
                            '</td>' +
                            '</tr>');


                    });

                }

            });
        }
    }

    $(document).on('click','.btnAddingAsRelated',function () {

        var NIP = $(this).attr('data-nip');
        var NIPInduk = $('#dataNIPInduk').val();

        if(NIP!=NIPInduk && confirm('Are you sure?')){



            var data = {
                action : 'setToDataRelatedNIP',
                NIP : NIP,
                NIPInduk : NIPInduk
            };
            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api4/__crudPrefrencesEmployees';

            $.post(url,{token:token},function (jsonResult) {
                if(parseInt(jsonResult.Status)<0){
                    alert(jsonResult.Msg);
                } else {
                    showRealtedDetail(NIPInduk);
                    searchToAddingRelated(NIP);
                    alert(jsonResult.Msg);
                }
            });
        } else {
            alert('NIP cannot be related to the same NIP');
        }
    });

</script>


<script type="text/javascript">
    let otbl_lecturer_credit;
    let otbl_lecturer_credit_modal;
    let SelectionCopy=[];
    class App_lecturer_credit {
         
        constructor(){
            this.url = base_url_js+'rest3/__lecturer_salary_sks';
            let selectorSemesterID = $('#filterSemesterID');
            let filterSemesterID = selectorSemesterID.val();
             if(filterSemesterID =='' || filterSemesterID ==null || filterSemesterID =='' || filterSemesterID ==null || filterSemesterID === 'undefined' ){
                this.LoadSemester(selectorSemesterID);
             }
        }

        LoadSemester = (selector) => {
            loSelectOptionSemester2(selector);
        }

        selectorSemesterID = (selector) => {

        }

        hideSelector = (selector) =>{
            selector.addClass('hide');
        }

        showSelector = (selector) => {
            selector.removeClass('hide');
        }

        Loaded = () => {
            let cls = this;
            cls.hideSelector($('#tbl_lecturer_credit'));
            let firstLoad = setInterval(function () {
                let filterSemesterID = $('#filterSemesterID').val();
                if(filterSemesterID!='' && filterSemesterID!=null && filterSemesterID !='' && filterSemesterID!=null){
                    cls.showSelector($('#tbl_lecturer_credit'));
                    cls.LoadTable();
                    clearInterval(firstLoad);
                }
            },200);
            setTimeout(function () {
                clearInterval(firstLoad);
            },5000);
            
        }

        Modal_form = (action='add',form_subject = 'Form Salary Lecturer per Credit ',dataObj={},ID='') => {
            loadingStart();
            let html = '<div class = "row">'+
                        '<div class = "col-md-12">'+
                            '<div class = "form-group">'+
                                '<label>Choose Semester</label>'+
                                '<select class = "form-control frmModalLectureSalary" name = "SemesterID">'+
                                '</select>'+
                            '</div>'+
                            '<div class = "form-group">'+
                                '<label>Choose Employees</label>'+
                                '<div class="input-group">'+
                                    '<input type="text" class="form-control frmModalLectureSalary" readonly name="NIP">'+
                                    '<span class="input-group-btn">'+
                                        '<button class="btn btn-default SearchNIPEMP" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
                                    '</span>'+
                                '</div>'+
                                '<label for="Name"></label>'+
                            '</div>'+
                            '<div class = "form-group">'+
                                '<label>Honor per Credit</label>'+
                                '<input type="text" class = "form-control frmModalLectureSalary" name = "Money">'+
                            '</div>'+ 
                            '<div class = "form-group">'+
                                '<label>Allowance</label>'+
                                '<input type="text" class = "form-control frmModalLectureSalary" name = "Allowance">'+
                            '</div>'+
                            '<div class = "form-group">'+
                                '<label>NIDN Allowance</label>'+
                                '<input type="text" class = "form-control frmModalLectureSalary" name = "Allowance_NIDN">'+
                            '</div>'+ 
                        '</div>'+
                    '</div>';

                    $('#GlobalModalSmall .modal-header').html('<h4 class="modal-title">'+form_subject+'</h4>');
                    $('#GlobalModalSmall .modal-body').html(html);
                    $('#GlobalModalSmall .modal-footer').html('<button type="button" id="ModalbtnSaveLecturerSalary" class="btn btn-success" action = "'+action+'" data-id = "'+ID+'" >Save</button> <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');

            if (action == 'add') {
                let selectorSemesterID = $('.frmModalLectureSalary[name="SemesterID"]');
                loSelectOptionSemester2(selectorSemesterID);
                let firstLoad = setInterval(function () {
                    let filterSemesterID = selectorSemesterID.val();
                    if(filterSemesterID!='' && filterSemesterID!=null && filterSemesterID !='' && filterSemesterID!=null){
                        loadingEnd(100);
                        setTimeout(function () {
                            $('#GlobalModalSmall').modal({
                                'show' : true,
                                'backdrop' : 'static'
                            });
                            // set number money
                            $('.frmModalLectureSalary[name="Money"]').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
                            $('.frmModalLectureSalary[name="Money"]').maskMoney('mask', '9894');

                            $('.frmModalLectureSalary[name="Allowance"]').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
                            $('.frmModalLectureSalary[name="Allowance"]').maskMoney('mask', '9894');

                            $('.frmModalLectureSalary[name="Allowance_NIDN"]').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
                            $('.frmModalLectureSalary[name="Allowance_NIDN"]').maskMoney('mask', '9894');
                        },500);
                        
                        clearInterval(firstLoad);
                    }
                },200);
                setTimeout(function () {
                    clearInterval(firstLoad);
                },5000);
            }
            else if(action='edit'){
                let selectorSemesterID = $('.frmModalLectureSalary[name="SemesterID"]');
                loSelectOptionSemester2(selectorSemesterID,dataObj['SemesterID']);
                let firstLoad = setInterval(function () {
                    let filterSemesterID = selectorSemesterID.val();
                    if(filterSemesterID!='' && filterSemesterID!=null && filterSemesterID !='' && filterSemesterID!=null){
                        for (let key in dataObj){
                            if (key == 'SemesterID') {
                                continue;
                            }

                            if (key == 'Money' || key == 'Allowance' || key == 'Allowance_NIDN') {
                                let n = dataObj[key].indexOf(".");
                                dataObj[key] = dataObj[key].substring(0, n);
                            }

                            if (key == 'NIP') {
                                $('.frmModalLectureSalary[name="'+key+'"]').closest('.form-group').find('label[for="Name"]').html(dataObj['LecturerName']);
                            }
                            $('.frmModalLectureSalary[name="'+key+'"]').val(dataObj[key]);
                        }
                        loadingEnd(100);
                        setTimeout(function () {
                            $('#GlobalModalSmall').modal({
                                'show' : true,
                                'backdrop' : 'static'
                            });
                            // set number money
                            $('.frmModalLectureSalary[name="Money"]').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
                            $('.frmModalLectureSalary[name="Money"]').maskMoney('mask', '9894');

                            $('.frmModalLectureSalary[name="Allowance"]').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
                            $('.frmModalLectureSalary[name="Allowance"]').maskMoney('mask', '9894');

                            $('.frmModalLectureSalary[name="Allowance_NIDN"]').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
                            $('.frmModalLectureSalary[name="Allowance_NIDN"]').maskMoney('mask', '9894');                            
                        },500);
                        
                        clearInterval(firstLoad);
                    }
                },200);
                setTimeout(function () {
                    clearInterval(firstLoad);
                },5000);
            }

            
        }

        LoadTable = () => {
            let table = $('#tbl_lecturer_credit').DataTable({
                "fixedHeader": true,
                "responsive": true,
                "processing": true,
                "destroy": true,
                "serverSide": true,
                "lengthMenu": [
                    [5, 10,20],
                    [5, 10,20]
                ],
                "iDisplayLength": 10,
                "ordering": false,
                "language": {
                    "searchPlaceholder": "Search NIP / Name",
                },
                "ajax": {
                    url: this.url, // json datasource
                    ordering: false,
                    type: "post", // method  , by default get
                    data: function(token) {
                        // Read values
                        let getSemesterID = $('#filterSemesterID option:selected').val();
                        let ex = getSemesterID.split('.');
                        let filterSemesterID = ex[0];
                        let data = {
                            auth: 's3Cr3T-G4N',
                            action: 'read_server_side',
                            data : {
                                SemesterID : filterSemesterID,
                            },
                        };
                        let get_token = jwt_encode(data, "UAP)(*");
                        token.token = get_token;
                    },
                    error: function() { // error handling
                        $(".tbl_lecturer_credit-grid-error").html("");
                        $("#tbl_lecturer_credit-grid").append(
                            '<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
                        );
                        $("#tbl_lecturer_credit-grid_processing").css("display", "none");
                    }
                },
                'columnDefs': [
                    {
                      'targets': 0,
                      'searchable': false,
                      'orderable': false,
                      'className': 'dt-body-center',
                    },
                    {
                      'targets': 3,
                      'searchable': false,
                      'orderable': false,
                      'className': 'dt-body-center',
                      'render': function (data, type, full, meta){
                        let html = formatRupiah(full[3]);
                        return html;
                      }
                    },
                    {
                      'targets': 4,
                      'searchable': false,
                      'orderable': false,
                      'className': 'dt-body-center',
                      'render': function (data, type, full, meta){
                        let html = formatRupiah(full[4]);
                        return html;
                      }
                    },
                    {
                      'targets': 5,
                      'searchable': false,
                      'orderable': false,
                      'className': 'dt-body-center',
                      'render': function (data, type, full, meta){
                        let html = formatRupiah(full[5]);
                        return html;
                      }
                    },
                    {
                        'targets': 6,
                        'searchable': false,
                        'orderable': false,
                        'className': 'dt-body-center',
                        'render': function (data, type, full, meta){
                            let btnAction = '<div class="btn-group">' +
                                '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
                                '  </button>' +
                                '  <ul class="dropdown-menu">' +
                                '    <li><a href="javascript:void(0);" class="btnEditLecturerSalary" data-id="'+full[6]+'" data = "'+full['data']+'"><i class="fa fa fa-edit"></i> Edit</a></li>' +
                                '    <li role="separator" class="divider"></li>' +
                                '    <li><a href="javascript:void(0);" class="btnRemoveLecturerSalary" data-id="'+full[6]+'"><i class="fa fa fa-trash"></i> Remove</a></li>' +
                                '  </ul>' +
                                '</div>';
                            return btnAction;
                        }
                    },
                ],
                'createdRow': function(row, data, dataIndex) {
                    
                },
                dom: 'l<"toolbar">frtip',
                "initComplete": function(settings, json) {
                              $("div.toolbar")
                                 .html('<div class="toolbar no-padding pull-right" style = "margin-left : 10px;">'+
                                    '<span data-smt="" class="btn btn-copy-last-smt" page = "form" style = "background-color : #0a885f;color:whitesmoke">'+
                                        ' Copy from last semester'+
                                   '</span>'+
                                '</div>');
                }
            });

            otbl_lecturer_credit = table;
        }


        Validation = (arr) => {
            let toatString = "";
            let result = "";
            for(let key in arr){
               switch(key)
               {
                default :
                      result = Validation_required(arr[key],key);
                      if (result['status'] == 0) {
                        toatString += result['messages'] + "<br>";
                      }
               }
            }

            if (toatString != "") {
              toastr.error(toatString, 'Failed!!');
              return false;
            }
            return true
        }

        ActionSubmit = (selector,action='add',ID="") => {
            let cls = this;
            let data = {};
            $('.frmModalLectureSalary').each(function(e){
                let nm = $(this).attr('name');
                let v = $(this).val();
                if ($(this).is('select')) {
                    v = $(this).find('option:selected').val();
                }

                if (nm == 'SemesterID') {
                    let ex = v.split('.');
                    v = ex[0];
                }

                if (nm =='Money' || nm =='Allowance' || nm =='Allowance_NIDN') {
                    v = findAndReplace(v, ".","");
                }

                data[nm] = v;
            })

            data['UpdatedBy'] = sessionNIP;
            let dataform = {
                action : action,
                data : data,
                ID : ID,
                auth : 's3Cr3T-G4N',
            };

            // cek validation jika tidak delete
            let validation = (action == 'delete') ? true : cls.Validation(data);
            if (validation) {
                if (confirm ('Are you sure ?') ) {
                    loading_button2(selector);
                    let token = jwt_encode(dataform,'UAP)(*');
                    let url = cls.url;
                    AjaxSubmitForm(url,token).then(function(response){
                        if (response.status == 1) {
                            toastr.success('Success');
                            otbl_lecturer_credit.ajax.reload(null, false);
                            $('#GlobalModalSmall').modal('hide');
                        }
                        else
                        {
                            toastr.error(response.msg);
                            end_loading_button2(selector);
                        }
                    }).fail(function(response){
                       toastr.error('Connection error,please try again');
                       end_loading_button2(selector);     
                    })
                }
            }
        }

        SearchLecturer = (selector) => {
            $('#GlobalModalSmall').modal('hide');
            let html = '';
            html ='<div class = "row">'+
                    '<div class = "col-md-12">'+
                      '<div class="table-responsive">'+
                        '<table id="datatable_employees" class="table table-bordered display select hover" cellspacing="0" width="100%">'+
               '<thead>'+
                  '<tr>'+
                     '<th>NIP - Name</th>'+
                     '<th>Division</th>'+
                     '<th>Position</th>'+
                  '</tr>'+
               '</thead>'+
               '<tbody></tbody>'+
                '</table></div></div></div>';

            $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Select Lecturer'+'</h4>');
            $('#GlobalModalLarge .modal-body').html(html);
            $('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnCancleFormSearch" data-dismiss="modal" class="btn btn-default">Close</button>');
            $('#GlobalModalLarge').modal({
                'show' : true,
                'backdrop' : 'static'
            });

            let table = $('#datatable_employees').DataTable({
                "fixedHeader": true,
                "processing": true,
                "destroy": true,
                "serverSide": true,
                "lengthMenu": [
                    [10, 25],
                    [10, 25]
                ],
                "iDisplayLength": 10,
                "ordering": false,
                "language": {
                    "searchPlaceholder": "Search",
                },
                "ajax": {
                    url: base_url_js + "api/database/__fetchEmployees", // json datasource
                    ordering: false,
                    type: "post", // method  , by default get
                    data: function(token) {
                        let filtering = "&isLecturer=yes";
                        let get_token = jwt_encode({Filter : filtering},'UAP)(*');
                        token.token = get_token;
                    },
                    error: function() { // error handling
                        $(".datatable_employees-grid-error").html("");
                        $("#datatable_employees-grid").append(
                            '<tbody class="datatable_employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
                        );
                        $("#datatable_employees-grid_processing").css("display", "none");
                    }
                },
                'columnDefs': [
                    {
                      'targets': 0,
                      'className': 'dt-body-center',
                      'render': function (data, type, full, meta){
                        let html = full['NIP']+' - '+full['Name']
                        return html;
                      }
                    },
                    {
                      'targets': 1,
                      'className': 'dt-body-center',
                      'render': function (data, type, full, meta){
                        let html = full['DivisionMain'];
                        return html;
                      }
                    },
                    {
                      'targets': 2,
                      'className': 'dt-body-center',
                      'render': function (data, type, full, meta){
                        let html = full['PositionMain'];
                        return html;
                      }
                    },
                ],
                'createdRow': function(row, data, dataIndex) {
                    let dt = data;
                    let dataToken = jwt_encode(dt,'UAP)(*');
                    $(row).attr('datatoken',dataToken);
                },
                dom: 'l<"toolbar">frtip',
                "initComplete": function(settings, json) {

                }
            });

            table.on( 'click', 'tr', function (e) {
                let row = $(this);
                let datatoken = jwt_decode(row.attr('datatoken'));
                selector.closest('.input-group').find('.frmModalLectureSalary').val(datatoken['NIP']);
                selector.closest('.input-group').find('.frmModalLectureSalary').attr('datatoken',row.attr('datatoken'));
                selector.closest('.form-group').find('label[for="Name"]').html(datatoken['Name']);
                $('#GlobalModalLarge').modal('hide');
                $('#GlobalModalSmall').modal({
                    'show' : true,
                    'backdrop' : 'static'
                });
            });
        }

        ChooseLecturerCopy = (smt_active) => {
            SelectionCopy=[];
            let html  = '<div class = "row">'+
                            '<div class = "col-md-12">'+
                                '<div class = "form-group">'+
                                    '<label style = "color:blue;">Select Lecturer</label>'+
                                    '<div style = "margin-bottom:10px;">'+
                                        '<div class="thumbnail" style="padding: 10px;">'+
                                        '    <b>Status : </b><i class="fa fa-circle" style="color:#8ED6EA;"></i> Already selected on this semester'+
                                        '</div>'+
                                    '</div>'+
                                    '<table class="table table-centre" id = "tbl_lecturer_credit_modal">'+
                                        '<thead>'+
                                            '<tr>'+
                                            '    <th>No</th>'+
                                            '    <th>Semester</th>'+
                                            '    <th>Lecturer Name</th>'+
                                            '    <th>Honor Per Credit</th>'+
                                            '    <th>Allowance</th>'+
                                            '    <th>NIDN Allowance</th>'+
                                            '    <th><i class="fa fa-cog"></i></th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                            
                                        '</tbody>'+
                                    '</table>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class = "row">'+
                            '<div class = "col-md-12">'+
                                '<div class = "form-group">'+
                                    '<label style = "color:blue;">Lecturer Selected</label>'+
                                    '<table class="table table-centre" id = "tbl_lecturer_credit_selected">'+
                                        '<thead>'+
                                            '<tr>'+
                                            '    <th>Lecturer Name</th>'+
                                            '    <th>Honor Per Credit</th>'+
                                            '    <th>Allowance</th>'+
                                            '    <th>NIDN Allowance</th>'+
                                            '    <th><i class="fa fa-cog"></i></th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                            
                                        '</tbody>'+
                                    '</table>'+ 
                                '</div>'+
                            '</div>'+
                        '</div>';

            $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'Selection'+'</h4>');
            $('#GlobalModalLarge .modal-body').html(html);
            $('#GlobalModalLarge .modal-footer').html('<button type="button" id="ModalbtnSubmitSelection" class="btn btn-success">Submit</button> <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');
            $('#GlobalModalLarge').modal({
                'show' : true,
                'backdrop' : 'static'
            });

            AppDeclare.LoadTableSelection(smt_active);               
        }

        LoadTableSelection = (smt_active) => {
            let table = $('#tbl_lecturer_credit_modal').DataTable({
                "fixedHeader": true,
                "responsive": true,
                "processing": true,
                "destroy": true,
                "serverSide": true,
                "lengthMenu": [
                    [5, 10,20],
                    [5, 10,20]
                ],
                "iDisplayLength": 10,
                "ordering": false,
                "language": {
                    "searchPlaceholder": "Search NIP / Name",
                },
                "ajax": {
                    url: this.url, // json datasource
                    ordering: false,
                    type: "post", // method  , by default get
                    data: function(token) {
                        // Read values
                        let data = {
                            auth: 's3Cr3T-G4N',
                            action: 'selection_read_server_side',
                            data : {
                                smt_active : smt_active,
                                SelectionCopy : SelectionCopy,
                            },
                            smt_active : smt_active,
                        };
                        let get_token = jwt_encode(data, "UAP)(*");
                        token.token = get_token;
                    },
                    error: function() { // error handling
                        $(".tbl_lecturer_credit_modal-grid-error").html("");
                        $("#tbl_lecturer_credit_modal-grid").append(
                            '<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
                        );
                        $("#tbl_lecturer_credit_modal-grid_processing").css("display", "none");
                    }
                },
                'columnDefs': [
                    {
                      'targets': 0,
                      'searchable': false,
                      'orderable': false,
                      'className': 'dt-body-center',
                    },
                    {
                      'targets': 3,
                      'searchable': false,
                      'orderable': false,
                      'className': 'dt-body-center',
                      'render': function (data, type, full, meta){
                        let html = formatRupiah(full[3]);
                        return html;
                      }
                    },
                    {
                      'targets': 4,
                      'searchable': false,
                      'orderable': false,
                      'className': 'dt-body-center',
                      'render': function (data, type, full, meta){
                        let html = formatRupiah(full[4]);
                        return html;
                      }
                    },
                    {
                      'targets': 5,
                      'searchable': false,
                      'orderable': false,
                      'className': 'dt-body-center',
                      'render': function (data, type, full, meta){
                        let html = formatRupiah(full[5]);
                        return html;
                      }
                    },
                    {
                        'targets': 6,
                        'searchable': false,
                        'orderable': false,
                        'className': 'dt-body-center',
                        'render': function (data, type, full, meta){
                            let btnAction = '<button class="btn btn-sm btn-default btn-default-success btnSelectionLecturer"><i class="fa fa-arrow-down"></i></button>';
                            return btnAction;
                        }
                    },
                ],
                'createdRow': function(row, data, dataIndex) {
                    $(row).attr('datatoken',data.data);
                    let deData = jwt_decode(data.data);
                    if (deData['SelectionThisSMT'] == '1') {
                        $(row).attr('style','background-color:#8ED6EA;');
                    }
                },
                dom: 'l<"toolbar">frtip',
                "initComplete": function(settings, json) {
                              
                }
            });

            otbl_lecturer_credit_modal = table;
        }

        add_Lecturer_Selected = (dataDecode) => {
            let set_token = jwt_encode(dataDecode, "UAP)(*");
            SelectionCopy.push(dataDecode['NIP']);
            let style = '';
            if (dataDecode['SelectionThisSMT'] == '1') {
                style = 'style = "background-color:#8ED6EA !important;" ';
            }
            $('#tbl_lecturer_credit_selected').find('tbody').append(
                    '<tr set_token = "'+set_token+'" '+style+'>'+
                        '<td>'+dataDecode['LecturerName']+'</td>'+
                        '<td>'+formatRupiah(dataDecode['Money'])+'</td>'+
                        '<td>'+formatRupiah(dataDecode['Allowance'])+'</td>'+
                        '<td>'+formatRupiah(dataDecode['Allowance_NIDN'])+'</td>'+
                        '<td>'+'<button class = "btn btn-danger removeAddLecturerSelected">Delete</button>'+'</td>'+
                    '</tr>'    
                );
            otbl_lecturer_credit_modal.ajax.reload(null, false);
        }

        remove_Lecturer_Selected = (selector,dataDecode) => {
            let rs = [];
            let NIP = dataDecode['NIP'];
            for (var i = 0; i < SelectionCopy.length; i++) {
                if (NIP != SelectionCopy[i]) {
                    rs.push(SelectionCopy[i]);
                }
            }

            SelectionCopy = rs;
            selector.closest('tr').remove();
            otbl_lecturer_credit_modal.ajax.reload(null, false);
        } 

        ActionSubmitSelection = (selector) => {
            let post = [];
            let cls = this;
            $('#tbl_lecturer_credit_selected tbody tr').each(function(e){
                let datatoken = $(this).attr('set_token');
                let dataDecode = jwt_decode(datatoken);
                post.push(dataDecode);
            })
            if (post.length > 0) {
                let dataform = {
                    action : 'submit_selection',
                    data : post,
                    auth : 's3Cr3T-G4N',
                    sessionNIP : sessionNIP,
                };
                let token = jwt_encode(dataform,'UAP)(*');
                let url = cls.url;
                loading_button2(selector);
                AjaxSubmitForm(url,token).then(function(response){
                    if (response == 1) {
                        toastr.success('Success');
                        otbl_lecturer_credit.ajax.reload(null, false);
                        $('#GlobalModalLarge').modal('hide');
                    }
                    else
                    {
                        toastr.error('Connection error,please try again');
                        end_loading_button2(selector);
                    }
                }).fail(function(response){
                   toastr.error('Connection error,please try again');
                   end_loading_button2(selector);     
                })
            }
            else
            {
                toastr.info('No data selected');
            }
        }     

    }

    let AppDeclare = new App_lecturer_credit();

    $(document).ready(function(e){
        AppDeclare.Loaded();
    })

    $(document).off('change', '#filterSemesterID').on('change', '#filterSemesterID', function(e) {
        otbl_lecturer_credit.ajax.reload(null, false);
    })

    $(document).off('click', '.btn-add-lecturerCredit').on('click', '.btn-add-lecturerCredit', function(e) {
        let action = 'add';
        AppDeclare.Modal_form(action);
    })
   
    $(document).off('click', '.SearchNIPEMP').on('click', '.SearchNIPEMP',function(e) {
       let itsme = $(this);
       AppDeclare.SearchLecturer(itsme);
    })

    $(document).off('click', '#ModalbtnCancleFormSearch').on('click', '#ModalbtnCancleFormSearch',function(e) {
       $('#GlobalModalSmall').modal({
           'show' : true,
           'backdrop' : 'static'
       });
    })

    $(document).off('click', '#ModalbtnSaveLecturerSalary').on('click', '#ModalbtnSaveLecturerSalary',function(e) {
        let action = $(this).attr('action');
        let ID =  $(this).attr('data-id');
        let selector = $(this);
        AppDeclare.ActionSubmit(selector,action,ID);
    })

    $(document).off('click', '.btnEditLecturerSalary').on('click', '.btnEditLecturerSalary',function(e) {
        let selector = $(this);
        let data = jwt_decode($(this).attr('data'));
        let action = 'edit';
        let ID = selector.attr('data-id');
        AppDeclare.Modal_form(action,'Edit Form Salary Lecturer per Credit ',data,ID);
    })

    $(document).off('click', '.btnRemoveLecturerSalary').on('click', '.btnRemoveLecturerSalary',function(e) {
        let selector = $(this);
        let action = 'delete';
        let ID = selector.attr('data-id');
        AppDeclare.ActionSubmit(selector,action,ID);
    })
    
    $(document).off('click', '.btn-copy-last-smt').on('click', '.btn-copy-last-smt',function(e) {
        // get semester actice
        let c = $('#filterSemesterID').find('option[status="1"]').val();
        let smt_active = c.split('.')[0];
        AppDeclare.ChooseLecturerCopy(smt_active);
    })

    $(document).off('click', '.btnSelectionLecturer').on('click', '.btnSelectionLecturer',function(e) {
        let datatoken = jwt_decode( $(this).closest('tr').attr('datatoken') );
        AppDeclare.add_Lecturer_Selected(datatoken);

    })

    $(document).off('click', '.removeAddLecturerSelected').on('click', '.removeAddLecturerSelected',function(e) {
        let selector = $(this);
        let dataDecode = jwt_decode( $(this).closest('tr').attr('set_token'));
        AppDeclare.remove_Lecturer_Selected(selector,dataDecode);
    })

    $(document).off('click', '#ModalbtnSubmitSelection').on('click', '#ModalbtnSubmitSelection',function(e) {
        let selector = $(this);
        AppDeclare.ActionSubmitSelection(selector);
    })
    
    
    
</script>