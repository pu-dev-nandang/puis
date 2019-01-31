
<style>
    #tableListStd thead tr th {
        text-align: center;
        background-color: #436888;
        color: #ffffff;
    }
    #tableListStd tbody tr td {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="well">
            <div class="row">
                <div class="col-md-3 col-md-offset-1">
                    <select class="form-control filterSP" id="filterSemester"></select>
                </div>
                <div class="col-md-3">
                    <select class="form-control filterSP" id="filterCurriculum">
                        <option value="">--- All Curriculum ---</option>
                        <option disabled>-----------------------------</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control filterSP" id="filterBaseProdi">
                        <option value="">-- All Programme Study --</option>
                        <option disabled>-----------------------------</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr/>
        <div id="loadTable"></div>
    </div>
</div>


<script>
    $(document).ready(function () {
        loSelectOptionSemester('#filterSemester','');
        loadSelectOptionCurriculumNoSelect('#filterCurriculum','');
        loadSelectOptionBaseProdi('#filterBaseProdi','');

         var bool = 0;
         var urlInarray = [base_url_js+'api/__crudSemester',base_url_js+"api/__getKurikulumSelectOption",base_url_js+"api/__getBaseProdiSelectOption"];

         $( document ).ajaxComplete(function( event, xhr, settings ) {
            if (jQuery.inArray( settings.url, urlInarray )) {
                bool++;
                if (bool == 3) {
                   getStudents();
                }
            }
         });
    });

    $(document).on('change','.filterSP',function () {
        getStudents();
    });

    function getStudents() {
        var filterSemester = $("#filterSemester").val();
        var filterCurriculum = $("#filterCurriculum").val();
        var filterBaseProdi = $("#filterBaseProdi").val();
        
        $('#loadTable').html('<table class="table table-bordered table-striped" id="tableListStd">' +
            '            <thead>' +
            '            <tr>' +
            '                <th rowspan="3" style="width: 3%;"><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>' +
            '                <th rowspan="3" style="width: 10%;">Students</th>' +
            '                <th rowspan="3" style="width: 10%;">Programme Study</th>' +
            '                <th colspan="4">Payment</th>' +
            '                <th rowspan="3" style="width: 5%;">Action</th>' +
            '            </tr>' +
            '            <tr>' +
            '                <th colspan = "2" style="width: 5%;">BPP</th>' +
            '                <th colspan = "2" style="width: 5%;">Credit</th>' +
            '            </tr>' +
            '            <tr>' +
            '                <th  style="width: 5%;">Invoice</th>' +
            '                <th  style="width: 5%;">Payment</th>' +
            '                <th  style="width: 5%;">Invoice</th>' +
            '                <th  style="width: 5%;">Payment</th>' +
            '            </tr>' +
            '            </thead>' +
            '            <tbody></tbody>' +
            '        </table>');
        

        var url = base_url_js+'rest/academic/__assign_by_finance_change_status';
        var data = {
            auth : 's3Cr3T-G4N'
        }
        var token = jwt_encode(data,'UAP)(*');
         $.post(url,{token:token},function (data_json) {
            console.log(data_json);
            $("#tableListStd tbody").empty();
            for (var i = 0; i < data_json.length; i++) {
               var chkbox = '<input type="checkbox" name="id[]" value="' + data_json[i].NPM +';'+data_json[i].SemesterID+ '">';
               var StudentsCol = '<div style="text-align:left;"><b>'+data_json[i].NameMHS+'</b><br>'+data_json[i].NPM+'</div>';
               var ProgrammeStudy = '<div style="text-align:center;"><b>'+data_json[i].NameEng+'</b></div>';
               var BPPInvoice = 0;
               var BPPPayment = 0;
               var CreditInvoice = 0;
               var CreditPayment = 0;
               if (data_json[i].PTID == 2) {
                    BPPInvoice = formatRupiah(data_json[i].Invoice);
                    BPPPayment = formatRupiah(data_json[i].Payment);

                    for (var j = i+1; j < data_json.length; j++) {
                        if (data_json[j].PTID == 3 && data_json[i].NPM == data_json[j].NPM) {
                            CreditInvoice = formatRupiah(data_json[j].Invoice);
                            CreditPayment = formatRupiah(data_json[j].Payment);
                            i=j;
                            break;
                        }
                    }
               }
               else if(data_json[i].PTID == 3)
               {
                    CreditInvoice = formatRupiah(data_json[i].Invoice);
                    CreditPayment = formatRupiah(data_json[i].Payment);
                    for (var j = i+1; j < data_json.length; j++) {
                        if (data_json[j].PTID == 2 && data_json[i].NPM == data_json[j].NPM) {
                            BPPInvoice = formatRupiah(data_json[j].Invoice);
                            BPPPayment = formatRupiah(data_json[j].Payment);
                            i=j;
                            break;
                        }
                    }
               }

               var btnAction = '';
               $("#tableListStd tbody").append('<tr>'+
                    '<td>'+chkbox+'</td>'+
                    '<td>'+StudentsCol+'</td>'+
                    '<td>'+ProgrammeStudy+'</td>'+
                    '<td>'+BPPInvoice+'</td>'+
                    '<td>'+BPPPayment+'</td>'+
                    '<td>'+CreditInvoice+'</td>'+
                    '<td>'+CreditPayment+'</td>'+
                    '<td>'+btnAction+'</td>'+
                    '</tr>'
                );

            }

            var table = $("#tableListStd").DataTable({
                'iDisplayLength' : 10,
                'ordering' : false,
            });
        }).done(function() {
          
        }).fail(function() {
          toastr.error('The Database connection error, please try again', 'Failed!!');
        });

    }
</script>
