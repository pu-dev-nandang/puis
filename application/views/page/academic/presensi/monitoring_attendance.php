
<style>
    #tableAttendance tr th {
        text-align: center;
        background: #607d8b;
        color: #FFFFFF;
    }
    .td-center {
        text-align: center;
    }

    .labelAttd {
        min-width: 27px !important;
        min-height: 16px !important;
        display: inline-block !important;
    }
    #tableAttendance .label-success{
        background-color: #47824a;
    }

    #tableAttdLec tr th {
        text-align: center;
        background: #607d8b;
        color: #FFFFFF;
    }

    #tableAttdStd tr th {
        text-align: center;
        background: #576f58;
        color: #FFFFFF;
    }

    #tableAttendance .btn-sm {
        padding: 0px 9px;
    }

    #tableLecAttd tr td:first-child {
        text-align: center;
    }

    #tableSecAttd tr th {
        text-align: center;
        background: #8e5d59;
        color: #FFFFFF;
    }
</style>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="well">
            <div class="row">
                <div class="col-xs-2">
                    <select class="form-control filterAttd" id="filterProgramCampus"></select>
                </div>
                <div class="col-xs-2">
                    <select class="form-control filterAttd" id="filterSemester"></select>
                </div>
                <div class="col-xs-4">
                    <select class="form-control filterAttd" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                        <option disabled>------------------------------------------</option>
                    </select>
                </div>
                <div class="col-xs-2">
                    <select class="form-control filterAttd" id="filterDay">
                        <option value="">-- Show All Day --</option>
                        <option disabled>-------------------</option>
                    </select>
                </div>

                <div class="col-xs-2">
                    <button class="btn btn-block btn-default btn-default-primary">Blast</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="loadTable"></div>
        <table id="example" class="table table-bordered" width="100%">
        </table>
    </div>
</div>


<script>
    $(document).ready(function () {

        loadSelectOptionProgramCampus('#filterProgramCampus','');
        loSelectOptionSemester('#filterSemester','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');
        fillDays('#filterDay','Eng','');

        var loadFirst = setInterval(function () {

            var filterProgramCampus = $('#filterProgramCampus').val();
            var filterSemester = $('#filterSemester').val();

            if(filterProgramCampus!='' && filterProgramCampus!=null
                && filterSemester!='' && filterSemester!=null){
                loadAttendance();
                clearInterval(loadFirst);
            }

        },1000);

    });

    $('.filterAttd').change(function () {
        loadAttendance();
    });

    function loadAttendance() {

        $('#loadTable').html('<table class="table table-bordered table-hover" id="tableAttendance"></table>');

        var filterProgramCampus = $('#filterProgramCampus').val();
        var filterSemester = $('#filterSemester').val();


        if(filterProgramCampus!='' && filterProgramCampus!=null &&
            filterSemester!='' && filterSemester!=null){

            var ProgramsCampusID = filterProgramCampus;
            var SemesterID = filterSemester.split('.')[0];

            var filterBaseProdi = $('#filterBaseProdi').val();
            var DayID = $('#filterDay').val();

            var ProdiID = (filterBaseProdi!='' && filterBaseProdi!=null) ? filterBaseProdi.split('.')[0] : '';



            var data = {
                ProgramsCampusID : ProgramsCampusID,
                SemesterID : SemesterID,
                ProdiID : ProdiID,
                DayID : DayID
            };

            var token = jwt_encode(data,'UAP)(*');

            var dataTable = $('#tableAttendance').DataTable( {
                "processing": true,
                "serverSide": true,
                "iDisplayLength" : 10,
                "ordering" : false,
                "language": {
                    "searchPlaceholder": "Course, lecturer"
                },
                columns: [
                    {
                        name: 'no',
                        title: 'No',
                        width : '3%'
                    },
                    {
                        name: 'course',
                        title: 'Course',
                        width : '30%'
                    },
                    {
                        name: 'group',
                        title: 'Group',
                        width : '6%'

                    },
                    {
                        name : 'credit',
                        title: 'Crdt',
                        width : '1%'
                    },
                    {
                        name : 'std',
                        title: 'Std',
                        width : '3%'
                    },
                    {
                        title: 'Lecturer',
                        width : '20%'
                    },
                    {
                        title: 'Schedule',
                        width : '11%'
                    },
                    {
                        title: '1'
                    },
                    {
                        title: '2'
                    },
                    {
                        title: '3'
                    },
                    {
                        title: '4'
                    },
                    {
                        title: '5'
                    },
                    {
                        title: '6'
                    },
                    {
                        title: '7'
                    },
                    {
                        title: '8'
                    },
                    {
                        title: '9'
                    },
                    {
                        title: '10'
                    },
                    {
                        title: '11'
                    },
                    {
                        title: '12'
                    },
                    {
                        title: '13'
                    },
                    {
                        title: '14'
                    },
                    {
                        title: 'UTS'
                    },
                    {
                        title: 'UAS'
                    }
                ],

                rowsGroup: ['no:name','course:name','group:name','credit:name','std:name'],

                "responsive" : true,
                "ajax":{
                    url : base_url_js+'api2/__getMonitoringAttendance', // json datasource
                    ordering : false,
                    data : {token:token},
                    type: "post",  // method  , by default get
                    error: function(){  // error handling
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
                    },
                    complete : function () {
                        $('.img-fitter').imgFitter({

                            // CSS background position
                            backgroundPosition: 'center center',

                            // for image loading effect
                            fadeinDelay: 400,
                            fadeinTime: 1200

                        });
                    }
                }
            } );

        }



    }

    $(document).on('click','.btnShowDetailAttd',function () {

        var ID_Attd = $(this).attr('data-id');
        var ViewCourse = $(this).attr('data-course');

        var dataLec = JSON.parse($('#dateLec'+ID_Attd).val());

        // Get attendance lecturer
        var data = {
            action : 'readAttendance',
            ID_Attd : ID_Attd
        };

        var token = jwt_encode(data,'UAP)(*');
        var url = base_url_js+'api/__crudAttendance';

        $.post(url,{token:token},function (jsonResult) {



            $('#GlobalModalLarge .modal-dialog').css('width','1600px');
            $('#GlobalModalLarge .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">'+ViewCourse+'</h4>');
            $('#GlobalModalLarge .modal-body').html('<div class="row">' +
                '    <div class="col-md-3">' +
                '        <div class="well">' +
                '           <div class="row">' +
                '               <div class="col-md-12">' +
                '                   <div class="form-group">' +
                '                       <label>Lecturer</label>' +
                '                       <select class="form-control" id="formAttdLec"></select>' +
                '                   </div>' +
                '               </div> ' +
                '            </div>' +
                '            <div class="row">' +
                '                <div class="col-md-5">' +
                '                    <div class="form-group">' +
                '                        <label>Sesi</label>' +
                '                        <select class="form-control" id="formAttdSesi"></select>' +
                '                    </div>' +
                '                </div>' +
                '                <div class="col-md-7">' +
                '                    <label>Date</label>' +
                '                    <input class="form-control">' +
                '                </div>' +
                '            </div>' +
                '            <div class="row">' +
                '                <div class="col-md-6">' +
                '                    <div class="form-group">' +
                '                        <label>Start</label>' +
                '                        <input type="time" class="form-control"/>' +
                '                    </div>' +
                '                </div>' +
                '                <div class="col-md-6">' +
                '                    <div class="form-group">' +
                '                        <label>Start</label>' +
                '                        <input type="time" class="form-control"/>' +
                '                    </div>' +
                '                </div>' +
                '            </div>' +
                '            <div class="row">' +
                '                <div class="col-md-12" style="text-align: right;">' +
                '                    <button class="btn btn-success">Save</button>' +
                '                </div>' +
                '            </div>' +
                '        </div>' +
                '        <div id="tableLec">' +
                '        </div>' +
                '    </div>' +
                '    <div class="col-md-9">' +
                '        <table class="table table-bordered table-striped" id="tableSecAttd">' +
                '            <thead>' +
                '            <tr>' +
                '                <th style="width: 2%;">No</th>' +
                '                <th>Student</th>' +
                '                <th style="width: 4%;">1</th>' +
                '                <th style="width: 4%;">2</th>' +
                '                <th style="width: 4%;">3</th>' +
                '                <th style="width: 4%;">4</th>' +
                '                <th style="width: 4%;">5</th>' +
                '                <th style="width: 4%;">6</th>' +
                '                <th style="width: 4%;">7</th>' +
                '                <th style="width: 4%;">8</th>' +
                '                <th style="width: 4%;">9</th>' +
                '                <th style="width: 4%;">10</th>' +
                '                <th style="width: 4%;">11</th>' +
                '                <th style="width: 4%;">12</th>' +
                '                <th style="width: 4%;">13</th>' +
                '                <th style="width: 4%;">14</th>' +
                '                <th style="width: 4%;">UTS</th>' +
                '                <th style="width: 4%;">UAS</th>' +
                '            </tr>' +
                '            </thead>' +
                '           <tbody id="loadDataAttdStd"></tbody>' +
                '        </table>' +
                '    </div>' +
                '</div>');

            if(dataLec.length>0){
                for(var l=0;l<dataLec.length;l++){
                    $('#formAttdLec').append('<option value="'+dataLec[l]['NIP']+'">'+dataLec[l]['NIP']+' - '+dataLec[l]['Name']+'</option>');
                }
            }

            for(var s=1;s<=14;s++){
                $('#formAttdSesi').append('<option value="'+s+'">'+s+'</option>');
            }

            // Data Attendance Student
            if(jsonResult.length>0){
                var no = 1;
                for(var a=0;a<jsonResult.length;a++){
                    var d = jsonResult[a];
                    $('#loadDataAttdStd').append('<tr>' +
                        '<td>'+no+'</td>' +
                        '<td>'+d.Name+'</td>' +
                        '<td style="background: '+(( (d.M1==1 || d.M1=='1') ? '#fff;' : '#ff000042;' ))+'">' +
                        '   <div class="checkbox checbox-switch switch-success">' +
                        '        <label>' +
                        '           <input type="checkbox" id="" '+( (d.M1==1 || d.M1=='1') ? 'checked' : '' )+'>' +
                        '           <span></span>' +
                        '        </label>' +
                        '   </div>' +
                        '</td>' +
                        '<td style="background: '+(( (d.M2==1 || d.M2=='1') ? '#fff;' : '#ff000042;' ))+'">' +
                        '   <div class="checkbox checbox-switch switch-success">' +
                        '        <label>' +
                        '           <input type="checkbox" id="" '+( (d.M2==1 || d.M2=='1') ? 'checked' : '' )+'>' +
                        '           <span></span>' +
                        '        </label>' +
                        '   </div>' +
                        '</td>' +
                        '<td style="background: '+(( (d.M3==1 || d.M3=='1') ? '#fff;' : '#ff000042;' ))+'">' +
                        '   <div class="checkbox checbox-switch switch-success">' +
                        '        <label>' +
                        '           <input type="checkbox" id="" '+( (d.M3==1 || d.M3=='1') ? 'checked' : '' )+'>' +
                        '           <span></span>' +
                        '        </label>' +
                        '   </div>' +
                        '</td>' +
                        '<td style="background: '+(( (d.M4==1 || d.M4=='1') ? '#fff;' : '#ff000042;' ))+'">' +
                        '   <div class="checkbox checbox-switch switch-success">' +
                        '        <label>' +
                        '           <input type="checkbox" id="" '+( (d.M4==1 || d.M4=='1') ? 'checked' : '' )+'>' +
                        '           <span></span>' +
                        '        </label>' +
                        '   </div>' +
                        '</td>' +
                        '<td style="background: '+(( (d.M5==1 || d.M5=='1') ? '#fff;' : '#ff000042;' ))+'">' +
                        '   <div class="checkbox checbox-switch switch-success">' +
                        '        <label>' +
                        '           <input type="checkbox" id="" '+( (d.M5==1 || d.M5=='1') ? 'checked' : '' )+'>' +
                        '           <span></span>' +
                        '        </label>' +
                        '   </div>' +
                        '</td>' +
                        '<td style="background: '+(( (d.M6==1 || d.M6=='1') ? '#fff;' : '#ff000042;' ))+'">' +
                        '   <div class="checkbox checbox-switch switch-success">' +
                        '        <label>' +
                        '           <input type="checkbox" id="" '+( (d.M6==1 || d.M6=='1') ? 'checked' : '' )+'>' +
                        '           <span></span>' +
                        '        </label>' +
                        '   </div>' +
                        '</td>' +
                        '<td style="background: '+(( (d.M7==1 || d.M7=='1') ? '#fff;' : '#ff000042;' ))+'">' +
                        '   <div class="checkbox checbox-switch switch-success">' +
                        '        <label>' +
                        '           <input type="checkbox" id="" '+( (d.M7==1 || d.M7=='1') ? 'checked' : '' )+'>' +
                        '           <span></span>' +
                        '        </label>' +
                        '   </div>' +
                        '</td>' +
                        '<td style="background: '+(( (d.M8==1 || d.M8=='1') ? '#fff;' : '#ff000042;' ))+'">' +
                        '   <div class="checkbox checbox-switch switch-success">' +
                        '        <label>' +
                        '           <input type="checkbox" id="" '+( (d.M8==1 || d.M8=='1') ? 'checked' : '' )+'>' +
                        '           <span></span>' +
                        '        </label>' +
                        '   </div>' +
                        '</td>' +
                        '<td style="background: '+(( (d.M9==1 || d.M9=='1') ? '#fff;' : '#ff000042;' ))+'">' +
                        '   <div class="checkbox checbox-switch switch-success">' +
                        '        <label>' +
                        '           <input type="checkbox" id="" '+( (d.M9==1 || d.M9=='1') ? 'checked' : '' )+'>' +
                        '           <span></span>' +
                        '        </label>' +
                        '   </div>' +
                        '</td>' +
                        '<td style="background: '+(( (d.M10==1 || d.M10=='1') ? '#fff;' : '#ff000042;' ))+'">' +
                        '   <div class="checkbox checbox-switch switch-success">' +
                        '        <label>' +
                        '           <input type="checkbox" id="" '+( (d.M10==1 || d.M10=='1') ? 'checked' : '' )+'>' +
                        '           <span></span>' +
                        '        </label>' +
                        '   </div>' +
                        '</td>' +
                        '<td style="background: '+(( (d.M11==1 || d.M11=='1') ? '#fff;' : '#ff000042;' ))+'">' +
                        '   <div class="checkbox checbox-switch switch-success">' +
                        '        <label>' +
                        '           <input type="checkbox" id="" '+( (d.M11==1 || d.M11=='1') ? 'checked' : '' )+'>' +
                        '           <span></span>' +
                        '        </label>' +
                        '   </div>' +
                        '</td>' +
                        '<td style="background: '+(( (d.M12==1 || d.M12=='1') ? '#fff;' : '#ff000042;' ))+'">' +
                        '   <div class="checkbox checbox-switch switch-success">' +
                        '        <label>' +
                        '           <input type="checkbox" id="" '+( (d.M12==1 || d.M12=='1') ? 'checked' : '' )+'>' +
                        '           <span></span>' +
                        '        </label>' +
                        '   </div>' +
                        '</td>' +
                        '<td style="background: '+(( (d.M13==1 || d.M13=='1') ? '#fff;' : '#ff000042;' ))+'">' +
                        '   <div class="checkbox checbox-switch switch-success">' +
                        '        <label>' +
                        '           <input type="checkbox" id="" '+( (d.M13==1 || d.M13=='1') ? 'checked' : '' )+'>' +
                        '           <span></span>' +
                        '        </label>' +
                        '   </div>' +
                        '</td>' +
                        '<td style="background: '+(( (d.M14==1 || d.M14=='1') ? '#fff;' : '#ff000042;' ))+'">' +
                        '   <div class="checkbox checbox-switch switch-success">' +
                        '        <label>' +
                        '           <input type="checkbox" id="" '+( (d.M14==1 || d.M14=='1') ? 'checked' : '' )+'>' +
                        '           <span></span>' +
                        '        </label>' +
                        '   </div>' +
                        '</td>' +
                        '</tr>');

                    no+=1;
                }


            }

            var tableLec = '<table class="table table-bordered table-striped" id="tableLecAttd">' +
                '    <thead>' +
                '    <tr style="background: #687479;color: #fff;">' +
                '        <th style="text-align: center;width: 4%;">Sesi</th>' +
                '        <th style="text-align: center;">Lecturer</th>' +
                '    </tr>' +
                '    </thead>' +
                '    <tbody>' +
                '    <tr>' +
                '        <td>1</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    <tr>' +
                '        <td>2</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    <tr>' +
                '        <td>3</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    <tr>' +
                '        <td>4</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    <tr>' +
                '        <td>5</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    <tr>' +
                '        <td>6</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    <tr>' +
                '        <td>7</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    <tr>' +
                '        <td>8</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    <tr>' +
                '        <td>9</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    <tr>' +
                '        <td>10</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    <tr>' +
                '        <td>11</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    <tr>' +
                '        <td>12</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    <tr>' +
                '        <td>13</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    <tr>' +
                '        <td>14</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    <tr>' +
                '        <td>UTS</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    <tr>' +
                '        <td>UAS</td>' +
                '        <td></td>' +
                '    </tr>' +
                '    </tbody>' +
                '</table>';

            $('#tableLec').html(tableLec);

            $('#GlobalModalLarge .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
            $('#GlobalModalLarge').modal({
                'show' : true,
                'backdrop' : 'static'
            });

        });

    });
</script>