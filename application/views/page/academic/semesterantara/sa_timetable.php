
<style>
    #tableTimetableSA th {
        text-align: center;
        background: #607D8B;
        color: #fff;
    }

    #tableTimetableSA td:first-child {
        border-right: 1px solid #ccc;
    }

    #tableListStudent th {
        background: #ececec;
        color: #607D8B;
    }

    #tableListStudent th, #tableListStudent td {
        text-align: center;
    }
    .td-attd {
        width: 4%;
    }

    .ck-attd {
        padding-left : 0px;
    }

    .ck-attd input[type=checkbox]{
         float: none;
         margin-left: 0px;
    }

    .sts-pay {
        display: block;
        min-height: 20px;
        margin-top: 13px;
        margin-bottom: 7px;
        vertical-align: middle;
    }
</style>

<!--<h1>sa_timetable</h1>-->

<div id="viewTableTimetable"></div>





<script>

    $(document).ready(function () {
        // loadTimetableSA();
        loadTimetables();
    });

    function loadTimetables() {

        $('#viewTableTimetable').html('<table class="table table-striped" id="tableTimetableSA">' +
            '    <thead>' +
            '    <tr>' +
            '        <th style="width: 1%;border-right: 1px solid #ccc;">No</th>' +
            '        <th style="width: 7%;">Group</th>' +
            '        <th>Course</th>' +
            '        <th style="width: 12%;">Schedule</th>' +
            '        <th style="width: 15%;">Lecturers</th>' +
            '        <th style="width: 5%;">Students</th>' +
            '        <th style="width: 12%;">UTS</th>' +
            '        <th style="width: 12%;">UAS</th>' +
            '        <th style="width: 5%;">Score</th>' +
            '    </tr>' +
            '    </thead>' +
            '</table>');

        var data = {
            SASemesterID : '<?=$SASemesterID; ?>'
        };

        var token = jwt_encode(data,'UAP)(*');

        var dataTable = $('#tableTimetableSA').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "Group, Lecturer"
            },
            "ajax":{
                url : base_url_js+"api2/__getTimetableSA", // json datasource
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


    }

    $(document).on('click','.showStd',function () {
        var token = $(this).attr('data-token');
        var course = $(this).attr('data-course');

        var dataToken = jwt_decode(token,'UAP)(*');
        console.log(dataToken);

        $('#GlobalModalLarge .modal-dialog').css('width','1200px');
        $('#GlobalModalLarge .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">'+course+'</h4>');

        $('#GlobalModalLarge .modal-body').html('<div class="row">' +
            '    <div class="col-md-12">' +
            '        <table class="table table-bordered table-striped" id="tableListStudent">' +
            '            <thead>' +
            '            <tr>' +
            '                <th style="width: 1%;">No</th>' +
            '                <th>Student</th>' +
            '                <th style="width: 4%;">BPP</th>' +
            '                <th style="width: 4%;">Crdt</th>' +
            '                <th class="td-attd">1</th>' +
            '                <th class="td-attd">2</th>' +
            '                <th class="td-attd">3</th>' +
            '                <th class="td-attd">4</th>' +
            '                <th class="td-attd">5</th>' +
            '                <th class="td-attd">6</th>' +
            '                <th class="td-attd">7</th>' +
            '                <th class="td-attd">8</th>' +
            '                <th class="td-attd">9</th>' +
            '                <th class="td-attd">10</th>' +
            '                <th class="td-attd">11</th>' +
            '                <th class="td-attd">12</th>' +
            '                <th class="td-attd">13</th>' +
            '                <th class="td-attd">14</th>' +
            '                <th class="td-attd">UTS</th>' +
            '                <th class="td-attd">UAS</th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody id="listStudent"></tbody>' +
            '        </table>' +
            '    </div>' +
            '</div>');

        if(dataToken.length>0){
            var no = 1;
            $.each(dataToken,function (i,v) {

                var StatusBPP = (v.StatusBPP==0 || v.StatusBPP=='0')
                    ? '<i class="fa fa-times-circle sts-pay" style="color: red;"></i>'
                    : '<i class="fa fa-check-circle sts-pay" style="color: green;"></i>';

                var StatusCredit = (v.StatusCredit==0 || v.StatusCredit=='0')
                    ? '<i class="fa fa-times-circle sts-pay" style="color: red;"></i>'
                    : '<i class="fa fa-check-circle sts-pay" style="color: green;"></i>';

                var p = ((v.StatusBPP==0 || v.StatusBPP=='0') && (v.StatusCredit==0 || v.StatusCredit=='0'))
                    ? '-'
                    : '<div class="checkbox ck-attd">' +
                    '  <label>' +
                    '    <input type="checkbox" value="">' +
                    '  </label>' +
                    '</div>';

                // var p = '<input type="checkbox " value="1">';


                $('#listStudent').append('<tr>' +
                    '<td>'+no+'</td>' +
                    '<td style="text-align: left;"><b>'+v.Name+'</b><br/>'+v.NPM+'</td>' +
                    '<td>'+StatusBPP+'</td>' +
                    '<td>'+StatusCredit+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +
                    '<td>'+p+'</td>' +

                    '</tr>');

                // for(var a=1;a<=14;a++){
                //     var p = ((v.StatusBPP==0 || v.StatusBPP=='0') && (v.StatusCredit==0 || v.StatusCredit=='0'))
                //         ? '-'
                //         : '<div class="checkbox ck-attd">' +
                //         '  <label>' +
                //         '    <input type="checkbox " value="1">' +
                //         '  </label>' +
                //         '</div>';
                //
                //     $('#tdAttd_'+no).append('<td>'+p+'</td>');
                // }
                //
                // // UTS
                // var UTS = ((v.StatusBPP==0 || v.StatusBPP=='0') && (v.StatusCredit==0 || v.StatusCredit=='0'))
                //     ? '-'
                //     : '<div class="checkbox ck-attd">' +
                //     '  <label>' +
                //     '    <input type="checkbox " value="1">' +
                //     '  </label>' +
                //     '</div>';
                //
                // $('#tdAttd_'+no).append('<td>'+UTS+'</td>');
                //
                //
                // // UAS
                // var UAS = ((v.StatusBPP==0 || v.StatusBPP=='0') && (v.StatusCredit==0 || v.StatusCredit=='0'))
                //     ? '-'
                //     : '<div class="checkbox ck-attd">' +
                //     '  <label>' +
                //     '    <input type="checkbox " value="1">' +
                //     '  </label>' +
                //     '</div>';
                //
                // $('#tdAttd_'+no).append('<td>'+UAS+'</td>');

                no++;
            })
        }

        $('#GlobalModalLarge .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModalLarge').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });


    function loadTimetableSA() {

        var data = {
            action : 'loadTimetableSA',
            SASemesterID : '<?=$SASemesterID; ?>'
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api2/__crudSemesterAntara';

        $.post(url,{token:token},function (jsonResult) {

            console.log(jsonResult);

        });


        return false;

        var dataTable = $('#tableMonScore').DataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength" : 10,
            "ordering" : false,
            "language": {
                "searchPlaceholder": "NIM, Student, Group, Lecturer"
            },
            "ajax":{
                url : base_url_js+"api/__getMonScoreStd", // json datasource
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

    }

</script>