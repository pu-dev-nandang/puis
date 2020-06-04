
<div class="row" style="margin-top: 30px;margin-bottom: 10px;">
    <div class="col-md-10 col-md-offset-1">
        <div class="well">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control filter-option" id="filterClassOf"></select>
                </div>
                <div class="col-md-4">
                    <select class="form-control filter-option" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control filter-option" id="filterStatusGenerate">
                        <option value="">-- All Status --</option>
                        <option value="1">Has Been Generated</option>
                        <option style="color: red;" value="0">Not Genrate Yet</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" style="text-align: right;margin-bottom: 20px;">
        <button class="btn btn-lg btn-default" id="btnGenrateShow">Genrate Student</button>
        <button class="btn btn-lg btn-success btnActGenrate hide" id="btnGenrateNow">Genrate Now</button>
        <button class="btn btn-lg btn-danger btnActGenrate hide" id="btnGenrateClear">Clear Selected Student</button>
        <button class="btn btn-lg btn-default btnActGenrate hide" id="btnGenrateCancel">Cancel</button>
        <div class="hide">
            <input id="showGenrateOption" value="0">
            <textarea id="listGenrate"></textarea>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="viewTable"></div>
    </div>
</div>



<script>

    $(document).ready(function () {
        var Year = moment().format('YYYY');
        loadSelectOptionClassOf_DESC('#filterClassOf',(parseInt(Year) - 5));
        loadSelectOptionBaseProdi('#filterBaseProdi','');

        var firstLoad = setInterval(function () {
            var filterClassOf = $('#filterClassOf').val();
            if(filterClassOf!='' && filterClassOf!=null){
                clearInterval(firstLoad);
                loadDataIjazah();
            }
        },1000);

        setTimeout(function () {
            clearInterval(firstLoad);
        },5000);

    });

    $('.filter-option').change(function () {
        loadDataIjazah();
    });

    $('#btnGenrateShow').click(function () {
        $('.ckstd,.btnActGenrate').removeClass('hide');
        $('#btnGenrateShow').addClass('hide');
        $('#showGenrateOption').val(1);
        $('#listGenrate').val('');
    });

    $('#btnGenrateCancel').click(function () {
        $('.ckstd,.btnActGenrate').addClass('hide');
        $('#btnGenrateShow').removeClass('hide');
        $('#showGenrateOption').val(0);
        $('#listGenrate').val('');
    });

    $('#btnGenrateClear').click(function () {
        var listGenrate = $('#listGenrate').val();
        if(listGenrate!=''){
            if(confirm('Are you sure?')){
                $('#listGenrate').val('');
            }
        } else {
            toastr.warning('No data selected','Warning');
        }

    });

    $('#btnGenrateNow').click(function () {
        var listGenrate = $('#listGenrate').val();
        if(listGenrate!=''){

            var d = JSON.parse(listGenrate);
            var listStd = '';
            for(var i=0;i<d.length;i++){
                var s = d[i];
                listStd = listStd+'<tr id="listModalStd_'+s.NPM+'">' +
                    '<td>'+(i+1)+'</td>' +
                    '<td>'+s.NPM+'</td>' +
                    '<td style="text-align: left;" id="modalstdName_'+s.NPM+'">'+s.Name+'</td>' +
                    '<td id="listModalActStd_'+s.NPM+'"><button class="btn btn-sm btn-danger btnModalRemoveSelected" data-npm="'+s.NPM+'"><i class="fa fa-trash"></i></button></td>' +
                    '</tr>';
            }

            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Genrate Student</h4>');

            var htmlss = '<div class="row">' +
                '    <div class="col-md-12">' +
                '        <table class="table table-bordered table-centre">' +
                '            <thead>' +
                '            <tr style="background: #f5f5f5;">' +
                '                <th style="width: 1%">No</th>' +
                '                <th style="width: 20%">NIM</th>' +
                '                <th>Name</th>' +
                '                <th style="width: 15%"><i class="fa fa-cog"></i></th>' +
                '            </tr>' +
                '            </thead>' +
                '       <tbody>'+listStd+'</tbody>' +
                '        </table>' +
                '    </div>' +
                '</div>';

            $('#GlobalModal .modal-body').html(htmlss);

            $('#GlobalModal .modal-footer').html('' +
                '<button class="btn btn-success" id="btnModalSubmit">Save</button> ' +
                '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

            $('#GlobalModal').on('shown.bs.modal', function () {
                $('#formSimpleSearch').focus();
            });

            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        } else {
            toastr.warning('No data selected','Warning');
        }

    });

    function loadDataIjazah() {

        var filterClassOf = $('#filterClassOf').val();

        if(filterClassOf!='' && filterClassOf!=null){

            var filterBaseProdi = $('#filterBaseProdi').val();
            var filterStatusGenerate = $('#filterStatusGenerate').val();

            $('#viewTable').html('<table class="table table-bordered table-centre" id="tableIjazah">' +
                '            <thead>' +
                '            <tr>' +
                '                <th style="width: 1%;">No</th>' +
                '                <th>Student</th>' +
                '                <th style="width: 17%;">Programme Study</th>' +
                '                <th style="width: 17%;">Judisium Date</th>' +
                '                <th style="width: 17%;">Genrate Date</th>' +
                '                <th style="width: 17%;">Token</th>' +
                '                <th style="width: 10%;">Ijazah</th>' +
                '            </tr>' +
                '            </thead>' +
                '            <tbody></tbody>' +
                '        </table>');

            var showGenrateOption = $('#showGenrateOption').val();
            var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null)
                ? filterBaseProdi.split('.')[0] : '' ;

            console.log(showGenrateOption);

            var token = jwt_encode({Year:filterClassOf, ProdiID:ProdiID, Generated : filterStatusGenerate, showGenrateOption : showGenrateOption},'UAP)(*');

            var dataTable = $('#tableIjazah').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "NIM, Name, Programme Study"
                },
                "ajax":{
                    url : base_url_js+'api/__getIjazah', // json datasource
                    ordering : false,
                    data : {token:token},
                    type: "post",  // method  , by default get
                    error: function(){  // error handling
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
                    }
                },
                "drawCallback": function( settings ) {
                    var showGenrateOption = $('#showGenrateOption').val();
                    if(parseInt(showGenrateOption)==1){
                        $('.ckstd').removeClass('hide');
                    }

                    var listGenrate = $('#listGenrate').val();
                    var d = (listGenrate!='') ? JSON.parse(listGenrate) : [];
                    if(d.length>0){
                        for(var i=0;i<d.length;i++){
                            $('#ck_'+d[i].NPM).prop('checked',true);
                        }
                    }

                    // $('#tableIjazah_filter').html('<label><div class="input-group"><span class="input-group-addon"><i class="icon-search"></i></span><input type="search" class="form-control" placeholder="NIM, Name, Programme Study" aria-controls="tableIjazah"></div></label>');
                }
            });


        }

    }

    $(document).on('click','.ckstd',function () {

        var NPM = $(this).attr('data-npm');
        var Name = $('#showName_'+NPM).text();

        var listGenrate = $('#listGenrate').val();
        var d = (listGenrate!='') ? JSON.parse(listGenrate) : [];

        if($(this).is(':checked')){
            // Add NPM
            var arrP = {
                NPM : NPM,
                Name : Name
            };
            d.push(arrP);
            var newData = JSON.stringify(d);
            $('#listGenrate').val(newData);
        } else {
            var newData = [];
            // Remove NPM
            if(d.length>0){
                for(var i=0;i<d.length;i++){
                    if(d[i].NPM!=NPM){
                        newData.push(d[i]);
                    }
                }
            }

            $('#listGenrate').val(JSON.stringify(newData));

        }
    });

    $(document).on('click','.btnModalRemoveSelected',function () {
       if(confirm('Are you sure?')){
           var NPM = $(this).attr('data-npm');

           $('#listModalStd_'+NPM).css('background','#ffebee');
           $('#listModalActStd_'+NPM).html('<button class="btn btn-sm btn-success btnRestore" data-npm="'+NPM+'" style="font-size: 10px !important;">Restore</button>');

           var listGenrate = $('#listGenrate').val();
           var d = (listGenrate!='') ? JSON.parse(listGenrate) : [];
           var newData = [];
           // Remove NPM
           if(d.length>0){
               for(var i=0;i<d.length;i++){
                   if(d[i].NPM!=NPM){
                       newData.push(d[i]);
                   }
               }
           }

           $('#listGenrate').val(JSON.stringify(newData));
       }
    });

    $(document).on('click','.btnRestore',function () {
        var NPM = $(this).attr('data-npm');
        var Name = $('#modalstdName_'+NPM).text();

        $('#listModalStd_'+NPM).css('background','#fff');
        $('#listModalActStd_'+NPM).html('<button class="btn btn-sm btn-danger btnModalRemoveSelected" data-npm="'+NPM+'"><i class="fa fa-trash"></i></button>');

        var listGenrate = $('#listGenrate').val();
        var d = (listGenrate!='') ? JSON.parse(listGenrate) : [];
        var arrP = {
            NPM : NPM,
            Name : Name
        };
        d.push(arrP);
        var newData = JSON.stringify(d);
        $('#listGenrate').val(newData);

    });

    $(document).on('click','#btnModalSubmit',function () {
        var listGenrate = $('#listGenrate').val();

        if(listGenrate!=''){

            $('#GlobalModal').modal('hide');

            loading_modal_show();


            var url = base_url_js+'api/__crudFinalProject';
            var d = JSON.parse(listGenrate);
            var data = {
                action : 'nowGenrateStudent',
                dataForm : d
            };

            var token = jwt_encode(data,'UAP)(*');
            $.post(url,{token:token},function (jsonResult) {
                $('.ckstd,.btnActGenrate').addClass('hide');
                $('#btnGenrateShow').removeClass('hide');
                $('#showGenrateOption').val(0);
                $('#listGenrate').val('');
                loadDataIjazah();
                toastr.success('Data saved','Success');
                setTimeout(function () {
                    loading_modal_hide();
                },500);

            });


        } else {
            toastr.warning('No data selected','Warning');
        }


    });

    $(document).on('click','.btnDownloadIjazah',function () {
        var NPM = $(this).attr('data-npm');
        var DBStudent = $(this).attr('data-db');

        var token = jwt_encode({NPM:NPM,DBStudent:DBStudent},'UAP)(*');
        var url = base_url_js+'save2pdf/ijazah';
        FormSubmitAuto(url,'POST',[{name : 'token', value : token}]);
    });

</script>