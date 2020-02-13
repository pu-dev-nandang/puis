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
                                        <th>Salary</th>
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
            var firstLoad = setInterval(function () {
                var filterSemesterID = $('#filterSemesterID').val();
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

        Modal_form = (action='add',form_subject = 'Form Salary Lecturer per Credit ',dataObj=[],ID='') => {
            loadingStart();
            var html = '<div class = "row">'+
                        '<div class = "col-md-12">'+
                            '<div class = "form-group">'+
                                '<label>Choose Semester</label>'+
                                '<select class = "form-control frmModal" name = "SemesterID">'+
                                '</select>'+
                            '</div>'+
                            '<div class = "form-group">'+
                                '<label>Choose Employees</label>'+
                                '<div class="input-group">'+
                                    '<input type="text" class="form-control frmModal" readonly name="NIP">'+
                                    '<span class="input-group-btn">'+
                                        '<button class="btn btn-default SearchNIPEMP" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>'+
                                    '</span>'+
                                '</div>'+
                                '<label for="Name"></label>'+
                            '</div>'+
                            '<div class = "form-group">'+
                                '<label>Salary per Credit</label>'+
                                '<input type="text" class = "form-control frmModal" name = "Money">'+
                            '</div>'+ 
                        '</div>'+
                    '</div>';

                    $('#GlobalModalSmall .modal-header').html('<h4 class="modal-title">'+form_subject+'</h4>');
                    $('#GlobalModalSmall .modal-body').html(html);
                    $('#GlobalModalSmall .modal-footer').html('<button type="button" id="ModalbtnSaveLecturerSalary" class="btn btn-success" action = "'+action+'" data-id = "'+ID+'" >Save</button> <button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Close</button>');

            if (action = 'add') {
                var selectorSemesterID = $('.frmModal[name="SemesterID"]');
                loSelectOptionSemester2(selectorSemesterID);
                var firstLoad = setInterval(function () {
                    var filterSemesterID = selectorSemesterID.val();
                    if(filterSemesterID!='' && filterSemesterID!=null && filterSemesterID !='' && filterSemesterID!=null){
                        loadingEnd(1);
                        setTimeout(function () {
                            $('#GlobalModalSmall').modal({
                                'show' : true,
                                'backdrop' : 'static'
                            });
                        },500);
                        
                        clearInterval(firstLoad);
                    }
                },200);
                setTimeout(function () {
                    clearInterval(firstLoad);
                },5000);
            }
            else if(action='edit'){

            }

            // set number money
            $('.frmModal[name="Money"]').maskMoney({thousands:'.', decimal:',', precision:0,allowZero: true});
            $('.frmModal[name="Money"]').maskMoney('mask', '9894');
        }

        LoadTable = () => {
            var table = $('#tbl_lecturer_credit').DataTable({
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
                        var html = formatRupiah(full[3]);
                        return html;
                      }
                    },
                    {
                        'targets': 4,
                        'searchable': false,
                        'orderable': false,
                        'className': 'dt-body-center',
                        'render': function (data, type, full, meta){
                            var btnAction = '<div class="btn-group">' +
                                '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
                                '  </button>' +
                                '  <ul class="dropdown-menu">' +
                                '    <li><a href="javascript:void(0);" class="btnEdit" data-id="'+full[4]+'" data = "'+full['data']+'"><i class="fa fa fa-edit"></i> Edit</a></li>' +
                                '    <li role="separator" class="divider"></li>' +
                                '    <li><a href="javascript:void(0);" class="btnRemove" data-id="'+full[4]+'"><i class="fa fa fa-trash"></i> Remove</a></li>' +
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

                }
            });

            otbl_lecturer_credit = table;
        }


        Validation = (data) => {

        }

        ActionSubmit = (selector,action='add',ID="") => {

        }

        SearchLecturer = (selector) => {
            $('#GlobalModalSmall').modal('hide');
            var html = '';
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

            var table = $('#datatable_employees').DataTable({
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
                        var html = full['NIP']+' - '+full['Name']
                        return html;
                      }
                    },
                    {
                      'targets': 1,
                      'className': 'dt-body-center',
                      'render': function (data, type, full, meta){
                        var html = full['DivisionMain'];
                        return html;
                      }
                    },
                    {
                      'targets': 2,
                      'className': 'dt-body-center',
                      'render': function (data, type, full, meta){
                        var html = full['PositionMain'];
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
                var row = $(this);
                var datatoken = jwt_decode(row.attr('datatoken'));
                selector.closest('.input-group').find('.frmModal').val(datatoken['NIP']);
                selector.closest('.input-group').find('.frmModal').attr('datatoken',row.attr('datatoken'));
                selector.closest('.form-group').find('label[for="Name"]').html(datatoken['Name']);
                $('#GlobalModalLarge').modal('hide');
                $('#GlobalModalSmall').modal({
                    'show' : true,
                    'backdrop' : 'static'
                });
            });
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
       var itsme = $(this);
       AppDeclare.SearchLecturer(itsme);
    })

    $(document).off('click', '#ModalbtnCancleFormSearch').on('click', '#ModalbtnCancleFormSearch',function(e) {
       $('#GlobalModalSmall').modal({
           'show' : true,
           'backdrop' : 'static'
       });
    })
    
</script>