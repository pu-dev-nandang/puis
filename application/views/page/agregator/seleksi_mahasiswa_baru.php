
<style>
    #tableData tr th, #tableData tr td {
        text-align: center;
    }
</style>


<div class="well">
    <div class="row">
        <div class="col-md-2 form-data-edit" style="border-right: 1px solid #CCCCCC;" id = "inputForm">
            <div class="form-group">
                <label>Tahun Akademik</label>
                <input class="hide" id="formID">
                <!-- <input type="number" class="form-control" id="formYear" /> -->
                <select class="form-control" id="formYear"></select>
            </div>
            <div class="form-group">
                <label>Prodi</label>
                <select class="form-control" id="formProdiID"></select>
            </div>
            <div class="form-group">
                <label>Daya Tampung</label>
                <input type="number" class="form-control" id="formCapacity" disabled>
            </div>
            <div class="form-group">
                <label>Pendaftar</label>
                <input type="number" class="form-control" id="formRegistrant">
            </div>
            <div class="form-group">
                <label>Lulus Seleksi</label>
                <input type="number" class="form-control" id="formPassSelection">
            </div>
            <div class="form-group">
                <label>Reguler (Jumlah Mahasiswa Baru)</label>
                <input type="number" class="form-control" id="formRegular">
            </div>
            <div class="form-group">
                <label>Transfer (Jumlah Mahasiswa Baru)</label>
                <input type="number" class="form-control" id="formTransfer">
            </div>
            <hr/>
            <div class="form-group">
                <label>Reguler (Jumlah Mahasiswa)</label>
                <input type="number" class="form-control" id="formRegular2">
            </div>
            <div class="form-group">
                <label>Transfer (Jumlah Mahasiswa)</label>
                <input type="number" class="form-control" id="formTransfer2">
            </div>

            <div class="form-group" style="text-align: right;">
                <button class="btn btn-primary btn-round" id="btnSave">Save</button>
            </div>

        </div>
        <div class="col-md-10" id = "ViewData">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="well">
                        <select class="form-control" id="filterYear"></select>
                    </div>
                </div>
            </div>

          <!-- <div class="table-responsive"> -->
            <div style="text-align: right"> <b>Download File : </b><button class="btn btn-success btn-circle" id="btndownloaadExcel" title="Dowload Excel"><i class="fa fa-file-excel-o"></i> </button></div>
            <table class="table table-striped table-bordered" id="tableData">
                <thead>
                <tr style="background: #20485A;color: #FFFFFF;">
                    <th rowspan="2" style="vertical-align : middle;text-align:center;width: 1%;">No</th>
                    <th rowspan="2" style="vertical-align : middle;text-align:center;">Prodi</th>
                    <th rowspan="2" style="vertical-align : middle;text-align:center;width: 7%;">Daya Tampung</th>
                    <th colspan="2">Jumlah Calon Mahasiswa</th>
                    <th colspan="2">Jumlah Mahasiswa Baru</th>
                    <th colspan="2">Jumlah Mahasiswa</th>
                    <th rowspan="2" style="width: 5%;"><i class="fa fa-cog"></i></th>

                </tr>
                <tr style="background: #20485A;color: #FFFFFF;">
                    <th style="width: 7%;">Pendaftar</th>
                    <th style="width: 7%;">Lulus Seleksi</th>
                    <th style="width: 7%;">Reguler</th>
                    <th style="width: 7%;">Transfer</th>
                    <th style="width: 7%;">Reguler</th>
                    <th style="width: 7%;">Transfer</th>
                </tr>
                </thead>
                <tbody id="listData"></tbody>
            </table>
          <!-- </div> -->
        </div>
    </div>
</div>

<script>
    var passToExcel = [];
    $(document).ready(function () {
         filteryear();
         loadSelectOptionBaseProdi('#formProdiID','');
        var firstLoad2 = setInterval(function () {
            var filterYear = $('#filterYear').val();
            var filterBaseProdi = $('#formProdiID').val();
            if(WaitForLoading == 1 && filterYear!='' && filterYear!=null && filterBaseProdi != '' && filterBaseProdi != null){
               loadDataTable();
               $('#formYear').trigger('change');
               clearInterval(firstLoad2);
            }
            
        },1000);
        setTimeout(function () {
            clearInterval(firstLoad2);
        },5000);

    });

    // $("#btndownloaadExcel").click(function(){
    //
    //     var filterYear = $('#filterYear').val();
    //     var data = {
    //         Year : filterYear
    //     };
    //
    //     var url = base_url_js+'agregator/excel-seleksi-mahasiswa-baru';
    //     var token = jwt_encode(data,"UAP)(*");
    //     FormSubmitAuto(url, 'POST', [
    //         { name: 'token', value: token },
    //     ]);
    // })


    $('#filterYear').change(function () {
        loadDataTable();
    });

    function filteryear() {
        var data = {
            action : 'filterYear'
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB2';

        $.post(url,{token:token},function (jsonResult) {
            $('#filterYear').empty();
            $('#formYear').empty();
            if(jsonResult.length>0){
                for (var i = parseInt(jsonResult.length)-1; i > -1; i--) {
                    var v = jsonResult[i];
                    var y = v.Year+'/'+(parseInt(v.Year)+1);
                    $('#filterYear').append('<option value="'+v.Year+'">'+y+'</option>');
                    $('#formYear').append('<option value="'+v.Year+'">'+y+'</option>');
                }

                // $.each(jsonResult,function (i,v) {

                //     $('#filterYear').append('<option value="'+v.Year+'">'+v.Year+'</option>');
                //     $('#formYear').append('<option value="'+v.Year+'">'+v.Year+'</option>');
                // })
            }

        });
    }

    $('#btnSave').click(function () {

        var formID = $('#formID').val();
        var formYear = $('#formYear option:selected').val();
        var formProdiID = $('#formProdiID').val();
        var formCapacity = $('#formCapacity').val();
        var formRegistrant = $('#formRegistrant').val();
        var formPassSelection = $('#formPassSelection').val();
        var formRegular = $('#formRegular').val();
        var formTransfer = $('#formTransfer').val();
        var formRegular2 = $('#formRegular2').val();
        var formTransfer2 = $('#formTransfer2').val();


        if(formYear!='' && formYear!=null &&
        formProdiID!='' && formProdiID!=null &&
        formCapacity!='' && formCapacity!=null &&
        formRegistrant!='' && formRegistrant!=null &&
        formPassSelection!='' && formPassSelection!=null &&
        formRegular!='' && formRegular!=null &&
        formTransfer!='' && formTransfer!=null &&
        formRegular2!='' && formRegular2!=null &&
        formTransfer2!='' && formTransfer2!=null){

            loading_buttonSm('#btnSave');

            var ProdiID = formProdiID.split('.')[0];

            var data = {
                action : 'crudMHSBaru',
                ID : (formID!='' && formID!=null) ? formID : '',
                dataForm : {
                    Year : formYear,
                    ProdiID : ProdiID,
                    Capacity : formCapacity,
                    Registrant : formRegistrant,
                    PassSelection : formPassSelection,
                    Regular : formRegular,
                    Transfer : formTransfer,
                    Regular2 : formRegular2,
                    Transfer2 : formTransfer2
                }
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudAgregatorTB2';

            $.post(url,{token:token},function (jsonResult) {

                toastr.success('Data saved','Success');
                filteryear();
                setTimeout(function () {
                    loadDataTable();
                    $('#formYear').trigger('change');
                },2000);

                setTimeout(function () {
                    // $('#formID').val('');
                    // $('#formYear').val('');
                    // $('#formCapacity').val('');
                    // $('#formRegistrant').val('');
                    // $('#formPassSelection').val('');
                    // $('#formRegular').val('');
                    // $('#formTransfer').val('');
                    // $('#formRegular2').val('');
                    // $('#formTransfer2').val('');
                    $('#btnSave').html('Save').prop('disabled',false);
                },500);
            })
        }

    });

    function loadDataTable() {
        passToExcel = [];
        var filterYear = $('#filterYear').val();

        var data = {
            action : 'readDataMHSBaru',
            Year : filterYear
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB2';

        $.post(url,{token:token},function (jsonResult) {

            $('#listData').empty();

            if(jsonResult.length>0){
                var arr_total = [0,0,0,0,0,0,0];
                $.each(jsonResult,function (i,v) {

                    var btnAction = '<div class="btn-group inputBtn">' +
                        '  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        '    <i class="fa fa-pencil"></i> <span class="caret"></span>' +
                        '  </button>' +
                        '  <ul class="dropdown-menu">' +
                        '    <li><a href="javascript:void(0);" class="btnEdit" data-id="'+v.ID+'"><i class="fa fa fa-edit"></i> Edit</a></li>' +
                        '    <li role="separator" class="divider"></li>' +
                        '    <li><a href="javascript:void(0);" class="btnRemove" data-tb="db_agregator.student_selection" data-id="'+v.ID+'"><i class="fa fa fa-trash"></i> Remove</a></li>' +
                        '  </ul>' +
                        '</div><textarea id="dataEdit_'+v.ID+'" class="hide">'+JSON.stringify(v)+'</textarea>';

                    $('#listData').append('<tr>' +
                        '<td>'+(i+1)+'</td>' +
                        '<td style="text-align: left;">'+v.ProdiName+'</td>' +
                        '<td>'+checkValue(v.Capacity)+'</td>' +
                        '<td>'+'<a href = "javascript:void(0);" class = "datadetailPendaftar" data = "'+v.d_Registrant+'">'+checkValue(v.Registrant)+'</a>'+'</td>' +
                        '<td>'+'<a href = "javascript:void(0);" class = "datadetailPassSelection" data = "'+v.d_PassSelection+'">'+checkValue(v.PassSelection)+'</a>'+'</td>' +
                        '<td>'+'<a href = "javascript:void(0);" class = "datadetailPassSelection" data = "'+v.d_Regular+'">'+checkValue(v.Regular)+'</a>'+'</td>' +
                        // '<td>'+checkValue(v.Regular)+'</td>' +
                        '<td>'+checkValue(v.Transfer)+'</td>' +
                        // '<td>'+checkValue(v.Regular2)+'</td>' +
                        '<td>'+'<a href = "javascript:void(0);" class = "datadetailPassSelection" data = "'+v.d_Regular2+'">'+checkValue(v.Regular2)+'</a>'+'</td>' +
                        '<td>'+checkValue(v.Transfer2)+'</td>' +
                        '<td>'+btnAction+'</td>' +
                        '</tr>');

                    arr_total[0] = parseInt(arr_total[0]) + parseInt(checkValue(v.Capacity));
                    arr_total[1] = parseInt(arr_total[1]) + parseInt(checkValue(v.Registrant));
                    arr_total[2] = parseInt(arr_total[2]) + parseInt(checkValue(v.PassSelection));
                    arr_total[3] = parseInt(arr_total[3]) + parseInt(checkValue(v.Regular));
                    arr_total[4] = parseInt(arr_total[4]) + parseInt(checkValue(v.Transfer));
                    arr_total[5] = parseInt(arr_total[5]) + parseInt(checkValue(v.Regular2));
                    arr_total[6] = parseInt(arr_total[6]) + parseInt(checkValue(v.Transfer2));

                });

                var AddhtmlBody = '<tr>'+
                                '<td colspan = "2"><label>Total</label></td>';

                for (var i = 0; i < arr_total.length; i++) {
                   AddhtmlBody += '<td>'+arr_total[i]+'</td>';
                }
                AddhtmlBody += '<td></td>'; // col action
                AddhtmlBody += '</tr>';
                $('#listData').append(AddhtmlBody);
                passToExcel = jsonResult;
            }

        })

    }

    $(document).on('click','.btnEdit',function () {
        var ID = $(this).attr('data-id');
        var dataEdit = $('#dataEdit_'+ID).val();

        var d = JSON.parse(dataEdit);

        $('#formID').val(d.ID);
        // $('#formYear').val(d.Year);
        $("#formYear option").filter(function() {
           //may want to use $.trim in here
           return $(this).val() == d.Year;
         }).prop("selected", true);

        $('#formProdiID').val(d.ProdiID+'.'+d.ProdiCode);
        // $('#formCapacity').val(d.Capacity);
        // $('#formRegistrant').val(d.Registrant);
        // $('#formPassSelection').val(d.PassSelection);
        // $('#formRegular').val(d.Regular);
        // $('#formTransfer').val(d.Transfer);
        // $('#formRegular2').val(d.Regular2);
        // $('#formTransfer2').val(d.Transfer2);
        $('#formYear').trigger('change');
    });

    $(document).off('click', '#btndownloaadExcel').on('click', '#btndownloaadExcel',function(e) {
        if (passToExcel.length > 0) {
            var url = base_url_js+'agregator/excel-seleksi-mahasiswa-baru';
            data = {
              passToExcel : passToExcel,
            }
            var token = jwt_encode(data,"UAP)(*");
            FormSubmitAuto(url, 'POST', [
                { name: 'token', value: token },
            ]);
        }

    })


    $(document).off('change', '#formYear').on('change', '#formYear',function(e) {
        var v = $(this).val();
        if (v >= 2019) {
            $('#formRegistrant').prop('disabled',true);
            $('#formPassSelection').prop('disabled',true);
            $('#formRegular').prop('disabled',true);
            $('#formRegular2').prop('disabled',true);
        }
        else
        {
            $('#formRegistrant').prop('disabled',false);
            $('#formPassSelection').prop('disabled',false);
            $('#formRegular').prop('disabled',false);
            $('#formRegular2').prop('disabled',false);
        }

        var formProdiID = $('#formProdiID option:selected').val();
        var P = formProdiID.split('.');
        var ProdiID = P[0];
        LoadDataToInput(v,ProdiID);

    })

    $(document).off('change', '#formProdiID').on('change', '#formProdiID',function(e) {
        $('#formYear').trigger('change');
    })

    function LoadDataToInput(Year,ProdiID)
    {
         var data = {
                    action : 'LoadDataToInputMHSBaru',
                    Year : Year,
                    ProdiID :ProdiID,
          };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api3/__crudAgregatorTB2';

        $.post(url,{token:token},function (jsonResult) {
            var d = jsonResult[0];
            $('#formID').val(d.ID);
            // $('#formYear').val(d.Year);
            $("#formYear option").filter(function() {
               //may want to use $.trim in here
               return $(this).val() == d.Year;
             }).prop("selected", true);

            $('#formProdiID').val(d.ProdiID+'.'+d.ProdiCode);
            $('#formCapacity').val(d.Capacity);
            $('#formRegistrant').val(d.Registrant);
            $('#formPassSelection').val(d.PassSelection);
            $('#formRegular').val(d.Regular);
            $('#formTransfer').val(d.Transfer);
            $('#formRegular2').val(d.Regular2);
            $('#formTransfer2').val(d.Transfer2);
        })
    }


    $(document).off('click', '.datadetailPendaftar').on('click', '.datadetailPendaftar',function(e) {
        var v = parseInt($(this).html());
        if (v > 0) {
            var dt = $(this).attr('data');
            dt = jwt_decode(dt);
            // console.log(dt);
            var html =  '<div class = "row">'+
                            '<div class = "col-md-12">'+
                                '<table class = "table">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<td>No</td>'+
                                            '<td>Name</td>'+
                                            '<td>Formulir Code</td>'+
                                            '<td>Program Studi</td>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody>';
                    if (dt.length > 0) {
                        for (var i = 0; i < dt.length; i++) {
                            html += '<tr>'+
                                        '<td>'+ (parseInt(i)+1) + '</td>'+
                                        '<td>'+ dt[i].Name + '</td>'+
                                        '<td>'+ dt[i].FormulirCode+' / '+dt[i].No_ref + '</td>'+
                                        '<td>'+ dt[i].ProdiName+ '</td>'+
                                    '</tr>';
                        }
                    }
                    else
                    {
                        html += '<tr>'+
                                    '<td colspan="4"><label>No Data Detail</label></td>'+
                                '</tr>';
                    }


                    html  += '</tbody></table></div></div>';


            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Detail</h4>');
            $('#GlobalModal .modal-body').html(html);
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        }
    })

    $(document).off('click', '.datadetailPassSelection').on('click', '.datadetailPassSelection',function(e) {
        var v = parseInt($(this).html());
        if (v > 0) {
            var dt = $(this).attr('data');
            dt = jwt_decode(dt);
            // console.log(dt);
            var html =  '<div class = "row">'+
                            '<div class = "col-md-12">'+
                                '<table class = "table">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<td>No</td>'+
                                            '<td>NPM & Name</td>'+
                                            '<td>Formulir Code</td>'+
                                            '<td>Program Studi</td>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody>';
                    if (dt.length > 0) {
                        for (var i = 0; i < dt.length; i++) {
                            html += '<tr>'+
                                        '<td>'+ (parseInt(i)+1) + '</td>'+
                                        '<td>'+ dt[i].NPM + '<br/>'+dt[i].Name + '</td>'+
                                        '<td>'+ dt[i].FormulirCode+' / '+dt[i].No_ref + '</td>'+
                                        '<td>'+ dt[i].ProdiName+ '</td>'+
                                    '</tr>';
                        }
                    }
                    else
                    {
                        html += '<tr>'+
                                    '<td colspan="4"><label>No Data Detail</label></td>'+
                                '</tr>';
                    }


                    html  += '</tbody></table></div></div>';


            $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">Detail</h4>');
            $('#GlobalModal .modal-body').html(html);
            $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
            $('#GlobalModal').modal({
                'show' : true,
                'backdrop' : 'static'
            });
        }
    })

</script>
