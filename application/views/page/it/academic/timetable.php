
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Timetable</h4>
        </div>
        <div class="panel-body">

            <div class="form-group">
                <label>Semester</label>
                <select class="form-control" id="filterSemester"></select>
            </div>
            <div id="viewDateKRS"></div>

            <hr/>

        </div>
    </div>
</div>



<script>

    $(document).ready(function () {
        loSelectOptionSemester('#filterSemester','');

        var firstLoad = setInterval(function () {
            var filterSemester = $('#filterSemester').val();
            if(filterSemester!='' && filterSemester!=null){
                loadDateKRSOnline();
                clearInterval(firstLoad);
            }
        },1000);


    });

    $('#filterSemester').change(function () {

        var filterSemester = $('#filterSemester').val();
        if(filterSemester!='' && filterSemester!=null){
            loadDateKRSOnline();
        }
    });

    function loadDateKRSOnline() {

        var filterSemester = $('#filterSemester').val();
        var SemesterID = filterSemester.split('.')[0];

        loading_page_simple('#viewDateKRS');

        var data = {
            action : 'getDateKRSOnline',
            SemesterID : SemesterID
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudKurikulum';

        $.post(url,{token:token},function (jsonResult) {

            if(jsonResult.length>0){
                var d = jsonResult[0];

                var Start = (d.krsStart!='' && d.krsStart!=null) ? moment(d.krsStart).format('DD MMM YYYY') : '';
                var End = (d.krsEnd!='' && d.krsEnd!=null) ? moment(d.krsEnd).format('DD MMM YYYY') : '';

                setTimeout(function () {
                    var ck = '<div class="checkbox">' +
                        '                <label>' +
                        '                    <input type="checkbox" id="formStatus"> Open edit timetable' +
                        '                </label>' +
                        '            </div>' +
                        '<div style="margin-top: 10px;text-align: right;">' +
                        '                <button id="btnSubmitEditTB" class="btn btn-default btn-sm">Submit</button>' +
                        '            </div>';

                    $('#viewDateKRS').html('KRS Online : <b>'+Start+' - '+End+'</b>'+ck);



                    var sts = (d.EditTimeTable=='1') ? true : false;

                    $('#formStatus').prop('checked', sts);
                },500);

            } else {
                $('#viewDateKRS').html('Not set');
            }

        });

    }

    $(document).on('click','#btnSubmitEditTB',function () {
        var filterSemester = $('#filterSemester').val();
        var SemesterID = filterSemester.split('.')[0];

        var EditTimeTable = ($('#formStatus').is(':checked')) ? '1' : '0';

        var data = {
            action : 'updateStatusEditTimeTable',
            SemesterID : SemesterID,
            EditTimeTable : EditTimeTable
        };
        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudKurikulum';

        loading_buttonSm('#btnSubmitEditTB');

        $.post(url,{token:token},function () {


            toastr.success('Updated success','Success');
            setTimeout(function () {
                loadDateKRSOnline();
            },500);

        });

    });

</script>