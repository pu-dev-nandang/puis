
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
    <div class="col-md-4 col-md-offset-4">
        <div class="well">
            <div class="row">
                <div class="col-md-7 col-md-offset-3">
                    <select class="form-control filterSP" id="filterSemester"></select>
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

         var bool = 0;
         var urlInarray = [base_url_js+'api/__crudSemester'];

         $( document ).ajaxSuccess(function( event, xhr, settings ) {
            if (jQuery.inArray( settings.url, urlInarray )) {
                bool++;
                if (bool == 1) {
                    setTimeout(function(){ getStudents(); }, 500);
                   
                }
            }
         });
    });

    $(document).on('change','.filterSP',function () {
        getStudents();
    });

    

    function getStudents() {
        var filterSemester = $("#filterSemester").val();
        filterSemester = filterSemester.split('.');
        filterSemester = filterSemester[0];
        
        $('#loadTable').html('<table class="table table-bordered table-striped" id="tableListStd">' +
            '            <thead>' +
            '            <tr>' +
            '                <th rowspan="3" style="width: 1%;"><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>' +
            '                <th rowspan="3" style="width: 10%;">Students</th>' +
            '                <th rowspan="3" style="width: 10%;">Programme Study</th>' +
            '                <th rowspan="3" style="width: 5%;">Status Student</th>' +
            '                <th rowspan="3" style="width: 5%;">Status Payment</th>' +
            '                <th colspan="4">Payment</th>' +
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
            auth : 's3Cr3T-G4N',
            filterSemester: filterSemester,
        }
        var token = jwt_encode(data,'UAP)(*');
         $.post(url,{token:token},function (data_json) {
            $("#tableListStd tbody").empty();
            for (var i = 0; i < data_json.length; i++) {
               var chkbox = '<input type="checkbox" name="id[]" value="' + data_json[i].NPM +';'+data_json[i].SemesterID+';'+data_json[i].NameMHS+'">';
               var StudentsCol = '<div style="text-align:left;"><b>'+data_json[i].NameMHS+'</b><br>'+data_json[i].NPM+'</div>';
               var ProgrammeStudy = '<div style="text-align:center;"><b>'+data_json[i].NameEng+'</b></div>';
               var BPPInvoice = '-';
               var BPPPayment = '-';
               var CreditInvoice = '-';
               var CreditPayment = '-';
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

               $("#tableListStd tbody").append('<tr>'+
                    '<td>'+chkbox+'</td>'+
                    '<td>'+StudentsCol+'</td>'+
                    '<td>'+ProgrammeStudy+'</td>'+
                    '<td>'+data_json[i].StatusMhs+'</td>'+
                    '<td>'+data_json[i].StatusPay+'</td>'+
                    '<td>'+BPPInvoice+'</td>'+
                    '<td>'+BPPPayment+'</td>'+
                    '<td>'+CreditInvoice+'</td>'+
                    '<td>'+CreditPayment+'</td>'+
                    '</tr>'
                );

            }

            var table = $("#tableListStd").DataTable({
                'iDisplayLength' : 10,
                'ordering' : false,
            });

            // Handle click on "Select all" control
            $('#example-select-all').on('click', function(){
               // Get all rows with search applied
               var rows = table.rows({ 'search': 'applied' }).nodes();
               // Check/uncheck checkboxes for all rows in the table
               $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            $("#rowBtnSave").remove();
            $("#loadTable").append(
                    '<div class = "row" id = "rowBtnSave">'+
                        '<div class = "col-md-12" align = "right">'+
                            '<button class = "btn btn-primary" id = "btnsave">Change Status</button>'+
                        '</div>'+
                    '</div>'        
            );

            // $(document).on('click','#btnsave', function () {
            $("#btnsave").click(function(){    
                // loading_button('#btnsave');
                var checkboxArr = [];
                table.$('input[type="checkbox"]').each(function(){
                  if(this.checked){
                     checkboxArr.push(this.value);
                  }
                   
                });
                var html = '';
                // show modal
                    var html1 = '<div class = "row"><div class = "col-md-4 col-md-offset-4"><select class="form-control" id="formChangeStatus"></select></div></div>';
                    html1 += '<div class = "row" style = "margin-top : 10px;"><div class= "col-md-12"><table class="table table-striped table-bordered table-hover table-checkable tableData">'+
                                  '<thead>'+
                                      '<tr>'+
                                          '<th style="width: 5px;">No</th>'+
                                          '<th style="width: 55px;">NPM</th>'+
                                          '<th style="width: 55px;">Nama</th>';
                    html1 += '</tr>' ;  
                    html1 += '</thead>' ; 
                    html1 += '<tbody>' ;
                    for (var i = 0; i < checkboxArr.length; i++) {
                      var No = parseInt(i) + 1;
                      var chk = checkboxArr[i].split(';');
                      html1 += '<tr>'+
                            '<td>'+ (i+1) + '</td>'+
                            '<td>'+ chk[0] + '</td>'+
                            '<td>'+ chk[2] + '</td>'+
                          '<tr>'; 
                    }

                    html1 += '</tbody>' ; 
                    html1 += '</table></div></div>' ;
                    if (checkboxArr.length > 0) {
                      html += html1;
                    }
                    else
                    {
                        toastr.error('Please checklist the data');
                        return;
                    }
                    // end reason cancel payment

                    var footer = '<button type="button" id="ModalbtnCancleForm" data-dismiss="modal" class="btn btn-default">Cancel</button>'+
                        '<button type="button" id="ModalbtnSaveForm" class="btn btn-primary">Save</button>';

                    $('#GlobalModalLarge .modal-header').html('<h4 class="modal-title">'+'List selected'+'</h4>');
                    $('#GlobalModalLarge .modal-body').html(html);
                    $('#GlobalModalLarge .modal-footer').html(footer);
                    $('#GlobalModalLarge').modal({
                        'show' : true,
                        'backdrop' : 'static'
                    });
                    $('#GlobalModalLarge .modal-dialog').attr('style','width : 1080px;');

                    var urlChangeStatus = base_url_js+'api/__crudStatusStudents';
                    var dataChangeStatus = {
                        action : 'read',
                    }

                    var token = jwt_encode(dataChangeStatus,'UAP)(*');
                    $.post(urlChangeStatus,{token:token},function (data_json2) {
                        $("#formChangeStatus").empty();
                        for (var i = 0; i < data_json2.length; i++) {
                            var selected = (data_json2[i].ID == 8) ? 'selected' : '';
                            $("#formChangeStatus").append('<option value = "'+data_json2[i].ID+'" '+selected+'>'+data_json2[i].Description+'</option>'
                            );
                        }

                    })

                    $("#ModalbtnSaveForm").click(function(){
                        loading_button('#ModalbtnSaveForm');
                        var formChangeStatus = $("#formChangeStatus").val();
                        var urlChangeStatus = base_url_js+'rest/academic/__change_status_mhs_multiple';
                        var dataChangeStatus = {
                            auth : 's3Cr3T-G4N',
                            formChangeStatus : formChangeStatus,
                            checkboxArr : checkboxArr,
                        }

                        var token = jwt_encode(dataChangeStatus,'UAP)(*');
                        $.post(urlChangeStatus,{token:token},function (data_json2) {
                            toastr.success('The data has been saved');
                            $("#GlobalModalLarge").modal('hide');
                            $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
                            getStudents();

                        })
                    })

            });    

        }).done(function() {
          
        }).fail(function() {
          toastr.error('The Database connection error, please try again', 'Failed!!');
        });

    }

    // Handle click on checkbox to set state of "Select all" control
    $('#tableListStd tbody').on('change', 'input[type="checkbox"]', function(){
       // If checkbox is not checked
       if(!this.checked){
          var el = $('#example-select-all').get(0);
          // If "Select all" control is checked and has 'indeterminate' property
          if(el && el.checked && ('indeterminate' in el)){
             // Set visual state of "Select all" control
             // as 'indeterminate'
             el.indeterminate = true;
          }
       }
    });
</script>
