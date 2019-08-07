<style>
    .thumbnail {
        min-height: 100px;
        padding: 15px;
    }
</style>

<div class="row">
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <div class="well">
                <div class="row">
                    <div class="col-md-5">
                        <select class="form-control" id="filterSemester"></select>
                    </div>
                    <div class="col-md-5">
                        <select class="form-control" id="filterClassOf"></select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-block btn-success" id="btnSumbit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<input id="viewRed" value="0" class="hide">
<div id="viewData"></div>


<script>

    $(document).ready(function () {
        loadSelectOptionStudentYear('#filterClassOf','');
        loSelectOptionSemester('#filterSemester','');

        var fl = setInterval(function () {

            var filterClassOf = $('#filterClassOf').val();
            var filterSemester = $('#filterSemester').val();

            if(filterClassOf!='' && filterClassOf!=null &&
                filterSemester!='' && filterSemester!=null){
                loadDataKrs();
                clearInterval(fl);
            }

        },1000);

        setTimeout(function () {
            clearInterval(fl);
        },5000);

    });
    
    $('#btnSumbit').click(function () {

        loadDataKrs();
        
    });

    function loadDataKrs() {
        var filterClassOf = $('#filterClassOf').val();
        var filterSemester = $('#filterSemester').val();

        if(filterClassOf!='' && filterClassOf!=null &&
            filterSemester!='' && filterSemester!=null){

            loading_modal_show();
            $('#viewData').empty();

            var SemesterID = filterSemester.split('.')[0];


            var data = {
                action : 'checkDataKRS',
                Year : filterClassOf,
                SemesterID : SemesterID
            };

            var token = jwt_encode(data,'UAP)(*');

            var url = base_url_js+'api3/__crudCheckDataKRS';

            $('#viewRed').val(0);

            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.length>0){

                    $.each(jsonResult,function (i,v) {

                        var krs_A = v.A;
                        var krs_B = v.B;

                        // cek array

                        var valueArr = krs_A.map(function(item){ return item.ScheduleID });
                        var isDuplicate = valueArr.some(function(item, idx){
                            return valueArr.indexOf(item) != idx
                        });
                        console.log(isDuplicate);
                        if(isDuplicate){

                            $('#viewRed').val(1);

                            var a = '';
                            $.each(krs_A,function (i2,v2) {

                                a = a+'<tr>'+
                                    '      <td style="border-right: 1px solid #CCCCCC;">'+(i2+1)+'</td>'+
                                    '      <td>'+v2.ScheduleID+'</td>'+
                                    '      <td>'+v2.ClassGroup+'</td>'+
                                    '      <td><button class="btn btn-sm btn-danger btnRemove" data-id="'+v2.ID+'" data-view="'+i+'" data-scd="'+v2.ScheduleID+'">Remove</button>' +
                                    '</td>'+
                                    '  </tr>';

                            });

                            a = a+'<textarea class="hide" id="viewData_'+i+'">'+JSON.stringify(v)+'</textarea>';

                            var b = '';
                            $.each(krs_B,function (i2,v2) {

                                b = b+'<tr>'+
                                    '      <td style="border-right: 1px solid #CCCCCC;">'+(i2+1)+'</td>'+
                                    '      <td>'+v2.ScheduleID+'</td>'+
                                    '      <td>'+v2.ClassGroup+'</td>'+
                                    '  </tr>';

                            });



                            $('#viewData').append('<div class="row">' +
                                '    <div class="col-xs-6">' +
                                '        <div class="thumbnail"><div style="text-align: center;"><h3 style="margin-top: 0px;">Jadwal di Timetable<br/><small>Setelah approve by Kaprodi</small></h3></div>' +
                                '           <b>'+v.Name+'</b> <br/> '+v.NPM+' ' +
                                '           <table class="table table-striped">' +
                                '                <thead>' +
                                '                <tr>' +
                                '                    <th style="width: 1%;">No</th>' +
                                '                    <th>ScheduleID</th>' +
                                '                    <th>Group</th>' +
                                '                    <th style="width: 5%;">Aksi</th>' +
                                '                </tr>' +
                                '                </thead>' +
                                '                <tbody>'+a+'</tbody>' +
                                '            </table>' +
                                '        </div>' +
                                '    </div>' +
                                '    <div class="col-xs-6">' +
                                '        <div class="thumbnail"><div style="text-align: center;"><h3 style="margin-top: 0px;">Data KRS Online</h3></div>' +
                                '           <b>'+v.Name+'</b> <br/> '+v.NPM+' ' +
                                '           <table class="table table-striped">' +
                                '                <thead>' +
                                '                <tr>' +
                                '                    <th style="width: 1%;">No</th>' +
                                '                    <th>ScheduleID</th>' +
                                '                    <th>Group</th>' +
                                '                </tr>' +
                                '                </thead>' +
                                '                <tbody>'+b+'</tbody>' +
                                '            </table>' +
                                '        </div>' +
                                '    </div>' +
                                ' ' +
                                '</div><div class="row col-md-12"><hr/></div>');
                        }


                    });


                    var viewRed = $('#viewRed').val();
                    if(viewRed==0 || viewRed=='0'){
                        $('#viewData').html('<div style="text-align: center;"><h3>There is no redundant data</h3></div>');
                    }

                } else {
                    $('#viewData').html('<div style="text-align: center;"><h3>There is no redundant data</h3></div>');
                }




                setTimeout(function () {
                    loading_modal_hide();
                },500);

            });


        }
    }


    $(document).on('click','.btnRemove',function () {

        if(confirm('Are you sure?')){
            $('.btnRemove').prop('disabled',true);

            var ID = $(this).attr('data-id');
            var ScheduleID = $(this).attr('data-scd');
            var I = $(this).attr('data-view');

            var viewData = $('#viewData_'+I).val();
            var d = JSON.parse(viewData);

            var filterClassOf = $('#filterClassOf').val();
            var filterSemester = $('#filterSemester').val();
            var SemesterID = filterSemester.split('.')[0];

            var data = {
                action : 'removeRedundancy',
                SPID : ID,
                Year : filterClassOf,
                NPM : d.NPM,
                SemesterID : SemesterID,
                ScheduleID : ScheduleID
            };

            var token = jwt_encode(data,'UAP)(*');
            var url = base_url_js+'api3/__crudCheckDataKRS';

            $.post(url,{token:token},function (jsonResult) {

                if(jsonResult.Status=='0'){
                    toastr.warning('Data can not removed','Warning');
                    $('.btnRemove').prop('disabled',false);
                } else {
                    toastr.success('Data removed','Success');
                    loadDataKrs();
                }

            });
        }


    });


    
</script>